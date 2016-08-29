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

    add_action( 'admin_init', array( $this, 'init_actions' ) );
    add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
    add_action( 'wp_ajax_fetch_log_rows', array( $this, 'ajax_fetch_log_rows' ) );
  }

  /**
   * Enqueues styles and scripts for the admin tools page
   *
   * @param mixed $hook
   * @access public
   * @return void
   */
  public function admin_enqueue_styles( $hook ) {
    wp_register_style( 'wp_access_log_viewer', plugin_dir_url( __DIR__ ) . 'dist/styles/wp-server-log-viewer.css' );
    wp_register_script( 'wp_access_log_viewer', plugin_dir_url( __DIR__ ) . 'dist/scripts/wp-server-log-viewer.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'), null, true );

    if( $hook === 'tools_page_wp-server-log-viewer' ) {
      wp_enqueue_style( 'wp_access_log_viewer' );
      wp_enqueue_style('wp-jquery-ui-dialog');
      wp_enqueue_script( 'wp_access_log_viewer' );
    }
  }


  /**
   * Adds the submenu page for Server Logs under tools
   *
   * @access public
   * @return void
   */
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


  /**
   * Renders the admin tools page content
   *
   * @see add_submenu_page
   *
   * @access public
   * @return void
   */
  public function render_tools_page() {
    global $current_log;

    $regex = null;
    if( isset( $_GET['regex'] ) ) {
      $regex = $_GET['regex'];
    }

    $current_log = 0;
    if( isset( $_GET['log'] ) ) {
      $current_log = (int) $_GET['log'];
    }

    $logs = get_option( 'server_logs' );
    if( is_null( $logs ) ) {
      $logs = [];
    }

    $logfile = null;
    if( isset( $logs[ $current_log ] ) ) {
      $logfile = $logs[ $current_log ];
    }

?>
<div class="wrap">
  <h1><?php _e('Server Logs', 'wp-server-log-viewer'); ?> <a href="#" class="page-title-action"><?php _e('Add New', 'wp-server-log-viewer'); ?></a></h1>
  <h2 class="screen-reader-text">Select log file list</h2>
  <ul class="subsubsub">
    <?php foreach( $logs as $key => $log ) : ?>
    <li><a href="tools.php?page=wp-server-log-viewer&log=<?php echo $key ?>" class="<?php echo $key == $current_log ? 'current' : ''; ?>"><?php echo basename( $log ); ?></a><?php echo ( $key < ( count( $logs ) - 1 ) ) ? ' |' : ''; ?></li>
    <?php endforeach; ?>
  </ul>
  <p class="clear"></p>
  <?php $this->render_log_view( $logfile, $regex ); ?>
</div>
<?php $this->render_new_log_dialog(); ?>
<?php
  }


  /**
   * Renders the log view for a specific $logfile on the tools page
   *
   * @param string $logfile
   * @param string $regex
   * @access public
   * @return void
   */
  public function render_log_view( $logfile, $regex = null ) {
    global $current_log;
?>
<div class="log-view">
  <?php if( is_readable( $logfile ) ) : ?>
  <div class="tablenav top">
    <form class="log-filter" method="get">
      <label class="screen-reader-text" for="regex">Regex:</label>
      <input type="hidden" name="page" value="wp-server-log-viewer">
      <input type="hidden" name="log" value="<?php echo $current_log; ?>">
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
  <?php endif; ?>
  <?php if( ! is_null( $logfile ) ) : ?>
    <?php if( ! is_readable( $logfile ) ) : ?>
    <div id="message" class="notice notice-error">
      <p><?php echo wp_sprintf( __("File %s does not exist or we don't have permissions to read it.", 'wp-server-log-viewer' ), $logfile ); ?></p>
    </div>
    <?php elseif ( ! $result ) : ?>
    <p><?php _e('No hits.', 'wp-server-log-viewer' ); ?></p>
    <?php endif; ?>
  <?php else : ?>
    <div id="message" class="notice updated">
      <p><?php echo _e('No logs yet. Click "Add New" to view your first log.', 'wp-server-log-viewer' ); ?></p>
    </div>
  <?php endif; ?>
</div>

<?php if( ! is_null( $logfile ) ) : ?>
<form method="get">
  <label class="screen-reader-text" for="regex">Regex:</label>
  <input type="hidden" name="page" value="wp-server-log-viewer">
  <input type="hidden" name="action" value="remove">
  <input type="hidden" name="log" value="<?php echo $current_log; ?>">
  <p>
    <input type="submit" class="button delete" value="Remove Log">
  </p>
</form>
<?php endif; ?>
<?php
  }


  /**
   * Renders $lines rows of a $logfile ending at $offset from the end of the cutoff marker
   *
   * @param string $logfile
   * @param int $offset
   * @param int $lines
   * @param string $regex
   * @param int $cutoff_bytes
   * @access public
   * @return void
   */
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


  public function render_new_log_dialog() {
?>
<div id="log-dialog" class="hidden">
  <form action="" method="get">
    <input type="hidden" name="page" value="wp-server-log-viewer">
    <input type="hidden" name="action" value="new">
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="logpath">Log Path</label></th>
          <td><input name="logpath" type="text" value="<?php echo ini_get('error_log'); ?>" class="regular-text"></td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input type="submit" class="button button-primary" id="create-new-log-submit" value="<?php _e('Add New Log', 'wp-server-log-viewer'); ?>">
    </p>
  </form>
</div>
<?php
  }


  /**
   * An ajax endpoint that fetches and renders the log rows for a logfile
   *
   * @access public
   * @return void
   */
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


  /**
   * Handles form submissions on the admin tools page
   *
   * @access public
   * @return void
   */
  public function init_actions() {
    // only run these actions on the log viewer page
    if( ! isset( $_GET['page'] ) || 'wp-server-log-viewer' != $_GET['page'] ) {
      return;
    }

    if( isset( $_GET['action'] ) && 'new' === $_GET['action'] && isset( $_GET['logpath'] ) ) {
      // new log was added
      $logs = get_option( 'server_logs' );
      if( is_null( $logs ) ) {
        $logs = [];
      }

      $log = trim( $_GET['logpath'] );
      $logs[] = $log;
      $logs = array_values( $logs );

      $index = array_search( $log, $logs );

      update_option( 'server_logs', $logs );

      wp_safe_redirect( admin_url('tools.php?page=wp-server-log-viewer&log=' . $index) );
    }

    if( isset( $_GET['action'] ) && 'remove' === $_GET['action'] && isset( $_GET['log'] ) ) {
      // log was removed
      $logs = get_option( 'server_logs' );
      if( is_null( $logs ) ) {
        $logs = [];
      }

      unset( $logs[ (int) $_GET['log'] ] );
      $logs = array_values( $logs );

      update_option( 'server_logs', $logs );

      wp_safe_redirect( admin_url('tools.php?page=wp-server-log-viewer') );
    }
  }

}

endif;
