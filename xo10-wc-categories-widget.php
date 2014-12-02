<?php
/**
 * Plugin Name: XO10 - WooCommerce Categories widget
 * Plugin URI: http://cartible.com
 * Description: Adds a widget that is able to display WooCommerce product category image thumbnails.
 * Version: 1.1
 * Author: Walter Lee
 * Author URI: http://cartible.com
 * Requires at least: 3.9
 * Tested up to: 4.0
 *
 * Text Domain: xo10-woocommerce-categories-widget
 * Domain Path: /i18n/languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 

// check that woocommerce is active
$plugin = plugin_basename( __FILE__ );		
if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  if( is_plugin_active( $plugin ) ) {
     //deactivate_plugins( $plugin );
    return;
  }
}


if ( ! class_exists( 'XO10_WC_Cats_Plugin' ) ) :

/**
 * Main XO10_WC_Cats_Plugin class
 */
final class XO10_WC_Cats_Plugin {
  
	/**
	 * @var XO10_WC_Cats_Plugin The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main XO10_WC_Cats_Plugin Instance
	 * Ensures only one instance of XO10_WC_Cats_Plugin is loaded or can be loaded.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 * @access private
	 */
	private function __construct() {
    // Setup
		$this->define_constants();
		$this->includes();

		// Hooks
		add_action( 'widgets_init', array( $this, 'include_widgets' ) );
    
    // Housekeeping Hooks
    if(is_admin() ) {
      add_action( 'admin_notices',  array( $this, 'inactive_wc_alert' ) ) ;
		  register_activation_hook( __FILE__, array( $this, 'version_checks' ) );
      register_uninstall_hook( __FILE__, array( 'XO10_WC_Cats_Plugin', 'purge_data' ) );
    }
	}

	/**
	 * Define constants we need
	 */
	private function define_constants() {
    
    $plugin_data = get_plugin_data( __FILE__, false, false );
		define( 'XO10_WC_CATS_PLUGIN_OFFICIAL_NAME', $plugin_data['Name'] );
    
		define( 'XO10_WC_CATS_PLUGIN_PLUGIN_FILE', __FILE__ );
		define( 'XO10_WC_CATS_PLUGIN_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

    define( 'XO10_WC_CATS_PLUGIN_MIN_VERSION_WP' , '3.9' ); 
    define( 'XO10_WC_CATS_PLUGIN_MIN_VERSION_PHP' , '5.3' );
    define( 'XO10_WC_CATS_PLUGIN_MIN_VERSION_WC' , '2.2' );

    define( 'XO10_WC_CATS_PLUGIN_VERSION', $plugin_data['Version'] );
    define( 'XO10_WC_CATS_PLUGIN_DB_VERSION', '1.0' );
    define( 'XO10_WC_CATS_PLUGIN_WP_VERSION', get_bloginfo( 'version' ));
    define( 'XO10_WC_CATS_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    define( 'XO10_WC_CATS_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
    define( 'XO10_WC_CATS_PLUGIN_INCLUDES', XO10_WC_CATS_PLUGIN_DIR . trailingslashit( 'includes' ) );
	}

  
	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
    require_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'widgets/class-categories.php' ); 
    include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'walkers/class-cat-list-walker.php' );
    include_once( XO10_WC_CATS_PLUGIN_DIR . '../woocommerce/includes/abstracts/abstract-wc-widget.php' );
    include_once( XO10_WC_CATS_PLUGIN_DIR . '../woocommerce/includes/walkers/class-product-cat-list-walker.php' );
	}

  
	/**
	 * Include core widgets
	 */
	public function include_widgets() {
    register_widget( 'XO10_WC_Categories_Widget' );
	}

  
  // -----------------------------------------
  // Housekeeping
  // -----------------------------------------
  
  /**
   * Displays error message if WooCommerce is inactive.
   */
  function inactive_wc_alert () {
    if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
      include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'views/alert-wc-inactive.php' );
    }
  }
  
  /**
   * Checks environment when plugin is activated.
   */
  function version_checks() {
    if ( version_compare( XO10_WC_CATS_PLUGIN_WP_VERSION, XO10_WC_CATS_PLUGIN_MIN_VERSION_WP, '<' ) ) {
      exit( '"' . XO10_WC_CATS_PLUGIN_OFFICIAL_NAME . '" requires at least WordPress version [' . XO10_WC_CATS_PLUGIN_MIN_VERSION_WP . '].' );
    }

    if( version_compare(PHP_VERSION, XO10_WC_CATS_PLUGIN_MIN_VERSION_PHP, '<' ) ) {
      exit( '"' . XO10_WC_CATS_PLUGIN_OFFICIAL_NAME . '" requires at least PHP version [' . XO10_WC_CATS_PLUGIN_MIN_VERSION_PHP . '].' );
    }
  }

  static function purge_data() {
    delete_option( 'widget_' . XO10_WC_Categories_Widget::WIDGET_SLUG );
  }
  
  
} // end class

endif;


function xo10_wc_cats_go() {
	return XO10_WC_Cats_Plugin::instance();
}

// run the code
xo10_wc_cats_go();
