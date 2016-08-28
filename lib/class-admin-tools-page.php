<?php

if ( ! class_exists('Admin_Tools_Page') ) :

class Admin_Tools_Page {
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
    wp_register_style( 'wp_access_log_viewer', plugin_dir_url( __DIR__ ) . 'dist/styles/wp-access-log-viewer.css' );
    wp_register_script( 'wp_access_log_viewer', plugin_dir_url( __DIR__ ) . 'dist/scripts/wp-access-log-viewer.js' );

    if( $hook === 'tools_page_wp-access-log-viewer' ) {
      wp_enqueue_style( 'wp_access_log_viewer' );
      wp_enqueue_script( 'wp_access_log_viewer' );
    }
  }


  public function add_submenu_page() {
    add_submenu_page(
      'tools.php',
      __('Access Log Viewer', 'wp-access-log-viewer'),
      __('Access Logs', 'wp-access-log-viewer'),
      'activate_plugins',
      'wp-access-log-viewer',
      array( $this, 'render_tools_page' )
    );
  }

  public function render_tools_page() {
?>
<div class="wrap">
  <h1><?php _e('Access Log Viewer', 'wp-access-log-viewer'); ?></h1>
  <?php $this->render_log_view( '/data/log/continuity.log' ); ?>
  <?php $this->render_log_view( '/data/log/php-error.log' ); ?>
  <?php $this->render_log_view( '/data/log/nginx-access.log' ); ?>
  <?php $this->render_log_view( '/data/log/nginx-error.log' ); ?>
</div>
<?php
  }

  public function render_log_view( $logfile ) {
?>
<h2><?php echo basename( $logfile ); ?></h2>
<div class="log-table-view" data-logfile="<?php esc_attr_e( $logfile ); ?>" data-logbytes="<?php esc_attr_e( filesize( $logfile ) ); ?>">
  <table class="wp-list-table widefat striped" cellspacing="0">
    <tbody>
      <?php $this->render_rows( $logfile, -1, 50 ); ?>
    </tbody>
  </table>
</div>
<?php
  }

  public function render_rows( $logfile, $offset, $lines, $cutoff_bytes = null ) {
    require_once 'class-access-log-utils.php';
    $rows = WP_Log_Utils::read_log_lines_backwards( $logfile, $offset, $lines, $cutoff_bytes );

    foreach( $rows as $row ) : ?>
      <tr>
        <td><span class="logrow"><?php echo $row; ?></span></td>
      </tr>
    <?php endforeach;
  }

  public function ajax_fetch_log_rows() {
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

    $cutoff_bytes = null;
    if( isset( $_REQUEST['cutoff_bytes'] ) ) {
      $cutoff_bytes = (int) $_REQUEST['cutoff_bytes'];
    }

    $this->render_rows( $logfile, $offset, 100, $cutoff_bytes );
    exit;
  }
}

endif;
