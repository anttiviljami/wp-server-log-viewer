<?php

if ( ! class_exists('Admin_Tools_Page') ) :

class Admin_Tools_Page {
  private $capability_required = 'activate_plugins';

  public static $instance;

  public static function init() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new Admin_Tools_Page();
    }
    return self::$instance;
  }

  private function __construct() {
    $this->logfile = '/data/log/nginx-access.log';

    add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
    add_action( 'wp_ajax_fetch_log_rows', array( $this, 'ajax_fetch_log_rows' ) );
	}

  public function admin_enqueue_styles( $hook ) {
    wp_register_style( 'wp_access_log_viewer', plugin_dir_url( __DIR__ ) . 'dist/styles/wp-server-log-viewer.css' );
    wp_register_script( 'wp_access_log_viewer', plugin_dir_url( __DIR__ ) . 'dist/scripts/wp-server-log-viewer.js' );

    if( $hook === 'tools_page_wp-server-log-viewer' ) {
      wp_enqueue_style( 'wp_access_log_viewer' );
      wp_enqueue_script( 'wp_access_log_viewer' );
    }
  }


  public function add_submenu_page() {
    add_submenu_page(
      'tools.php',
      __('Server Logs', 'wp-server-log-viewer'),
      __('Server Logs', 'wp-server-log-viewer'),
      $this->capability_required,
      'wp-server-log-viewer',
      array( $this, 'render_tools_page' )
    );
  }

  public function render_tools_page() {
    $regex = null;
    if( isset( $_GET['regex'] ) ) {
      $regex = $_GET['regex'];
    }
?>
<div class="wrap">
  <h1><?php _e('Server Logs', 'wp-server-log-viewer'); ?> <a href="#" class="page-title-action"><?php _e('Add New', 'wp-server-log-viewer'); ?></a></h1>
  <h2 class="screen-reader-text">Select log file list</h2>
  <ul class="subsubsub">
    <li><a href="tools.php?page=wp-server-log-viewer" class="current">nginx-access.log</a> |</li>
    <li><a href="tools.php?page=wp-server-log-viewer">nginx-error.log</a></li>
  </ul>
  <?php $this->render_log_view( '/data/log/nginx-access.log', $regex ); ?>
</div>
<?php
  }

  public function render_log_view( $logfile, $regex = null ) {
?>
<div class="log-view">
  <div class="tablenav top">
    <form class="log-filter" method="get">
      <label class="screen-reader-text" for="regex">Regex:</label>
      <input type="hidden" name="page" value="wp-server-log-viewer">
      <input type="search" name="regex" value="<?php echo $regex; ?>" placeholder="">
      <input type="submit" class="button" value="Filter">
    </form>
  </div>
  <div class="log-table-view" data-logfile="<?php esc_attr_e( $logfile ); ?>" data-logbytes="<?php esc_attr_e( filesize( $logfile ) ); ?>" data-regex="<?php esc_attr_e( $regex ); ?>">
    <table class="wp-list-table widefat striped" cellspacing="0">
      <tbody>
        <?php $result = $this->render_rows( $logfile, -1, 50, $regex ); ?>
      </tbody>
    </table>
  </div>
  <?php if ( ! $result ) : ?>
  <p><?php _e('No hits.', 'wp-server-log-viewer' ); ?></p>
  <?php endif; ?>
</div>
<?php
  }

  public function render_rows( $logfile, $offset, $lines, $regex = null, $cutoff_bytes = null ) {
    require_once 'class-access-log-utils.php';

    // escape special regex chars
    $regex = '#' . preg_quote( $regex ) . '#';

    $rows = WP_Log_Utils::read_log_lines_backwards( $logfile, $offset, $lines, $regex, $cutoff_bytes );

    if( empty( $rows ) ) {
      return false;
    }

    foreach( $rows as $row ) : ?>
      <tr>
        <td><span class="logrow"><?php echo $row; ?></span></td>
      </tr>
    <?php endforeach;

    return true;
  }

  public function ajax_fetch_log_rows() {
    // check permissions
    if( !current_user_can( $this->capability_required ) ) {
      exit;
    }

    if( isset( $_REQUEST['logfile'] ) ) {
      $logfile = $_REQUEST['logfile'];
    }
    else {
      exit;
    }

    $offset = 0;
    if( isset( $_REQUEST['offset'] ) ) {
      $offset = -( 1 + (int) $_REQUEST['offset'] );
    }

    $regex = null;
    if( isset( $_REQUEST['regex'] ) ) {
      $regex = $_REQUEST['regex'];
    }

    $cutoff_bytes = null;
    if( isset( $_REQUEST['cutoff_bytes'] ) ) {
      $cutoff_bytes = (int) $_REQUEST['cutoff_bytes'];
    }

    $this->render_rows( $logfile, $offset, 100, $regex, $cutoff_bytes );
    exit;
  }
}

endif;
