<?php

if ( !class_exists('Admin_Tools_Page') ) :

class Admin_Tools_Page {

  public $logfile;

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
  <?php $this->render_last_rows(); ?>
</div>
<?php
  }

  public function render_last_rows() {
    require_once 'class-access-log-utils.php';
    $rows = Access_Log_Utils::ftail( $this->logfile, 10 );

?>
<h2><?php echo basename( $this->logfile ); ?></h2>
<div class="log-table-view">
  <table class="wp-list-table widefat striped" cellspacing="0">
    <tbody>
      <?php foreach( $rows as $row ) : ?>
      <tr>
        <td><span class="logrow"><?php echo $row; ?></span></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php
  }
}

endif;
