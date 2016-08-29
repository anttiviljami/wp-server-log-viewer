<?php
/**
 * Plugin name: WP Server Log Viewer
 * Plugin URI: https://github.com/anttiviljami/wp-server-log-viewer
 * Description: View and analyse your server logs from within the WordPress admin dashboard
 * Version: 1.0
 * Author: @anttiviljami
 * Author URI: https://github.com/anttiviljami
 * License: GPLv3
 * Text Domain: wp-server-log-viewer
 */

/** Copyright 2016 Antti Kuosmanen

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 3, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists('WP_Server_Log_Viewer') ) :

class WP_Server_Log_Viewer {
  public static $instance;

  public static function init() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new WP_Server_Log_Viewer();
    }
    return self::$instance;
  }

  private function __construct() {
    // create the admin page
    require_once 'lib/class-admin-tools-page.php';
    if ( class_exists( 'Admin_Tools_Page' ) ) {
      Admin_Tools_Page::init();
    }

    // load textdomain for translations
    add_action( 'plugins_loaded',  array( $this, 'load_our_textdomain' ) );
  }

  /**
   * Loads gettext translation files
   *
   * @static
   * @access public
   * @return void
   */
  public static function load_our_textdomain() {
    load_plugin_textdomain( 'wp-server-log-viewer', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
  }
}

endif;

// init the plugin
$access_log_viewer = WP_Server_Log_Viewer::init();

