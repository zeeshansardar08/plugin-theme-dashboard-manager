<?php
/**
 * Plugin Name: Plugin & Theme Dashboard Manager
 * Plugin URI: https://zignites.com
 * Description: A comprehensive dashboard to view and export all installed plugins and themes with their status information.
 * Version: 1.0.0
 * Author: Zignites
 * Author URI: https://zignites.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: plugin-theme-dashboard-manager
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.9
 * Requires PHP: 7.4
 * Network: false
 *
 * @package PluginThemeDashboardManager
 * @since 1.0.0
 */

// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'PTDM_VERSION', '1.0.0' );
define( 'PTDM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PTDM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PTDM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main Plugin Class
 *
 * @since 1.0.0
 */
class Plugin_Theme_Dashboard_Manager {

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
    }

    /**
     * Initialize the plugin
     *
     * @since 1.0.0
     */
    public function init() {
        // Load required files
        $this->load_dependencies();
        
        // Initialize admin functionality
        if ( is_admin() ) {
            $this->init_admin();
        }
    }

    /**
     * Load plugin dependencies
     *
     * @since 1.0.0
     */
    private function load_dependencies() {
        require_once PTDM_PLUGIN_DIR . 'includes/admin-page.php';
        require_once PTDM_PLUGIN_DIR . 'includes/csv-export.php';
    }

    /**
     * Initialize admin functionality
     *
     * @since 1.0.0
     */
    private function init_admin() {
        new PTDM_Admin_Page();
        new PTDM_CSV_Export();
    }

    /**
     * Load plugin textdomain
     *
     * @since 1.0.0
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'plugin-theme-dashboard-manager',
            false,
            dirname( PTDM_PLUGIN_BASENAME ) . '/languages'
        );
    }

    /**
     * Plugin activation hook
     *
     * @since 1.0.0
     */
    public static function activate() {
        // Check if user has proper permissions
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        // Add activation timestamp
        add_option( 'ptdm_activated', time() );
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation hook
     *
     * @since 1.0.0
     */
    public static function deactivate() {
        // Check if user has proper permissions
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        // Flush rewrite rules
        flush_rewrite_rules();
    }
}

// Initialize the plugin
$plugin_theme_dashboard_manager = new Plugin_Theme_Dashboard_Manager();

// Register activation and deactivation hooks
register_activation_hook( __FILE__, array( 'Plugin_Theme_Dashboard_Manager', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Plugin_Theme_Dashboard_Manager', 'deactivate' ) ); 