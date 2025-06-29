<?php
/**
 * Admin Page Handler
 *
 * @package PluginThemeDashboardManager
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin Page Class
 *
 * @since 1.0.0
 */
class PTDM_Admin_Page {

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
    }

    /**
     * Add admin menu
     *
     * @since 1.0.0
     */
    public function add_admin_menu() {
        add_management_page(
            __( 'Plugin & Theme Dashboard', 'plugin-theme-dashboard-manager' ),
            __( 'Plugin & Theme Dashboard', 'plugin-theme-dashboard-manager' ),
            'manage_options',
            'plugin-theme-dashboard',
            array( $this, 'admin_page_content' )
        );
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @since 1.0.0
     * @param string $hook_suffix The current admin page.
     */
    public function enqueue_admin_scripts( $hook_suffix ) {
        if ( 'tools_page_plugin-theme-dashboard' !== $hook_suffix ) {
            return;
        }

        wp_enqueue_style(
            'ptdm-admin-styles',
            PTDM_PLUGIN_URL . 'assets/css/admin-styles.css',
            array(),
            PTDM_VERSION
        );

        wp_enqueue_script(
            'ptdm-admin-scripts',
            PTDM_PLUGIN_URL . 'assets/js/admin-scripts.js',
            array( 'jquery' ),
            PTDM_VERSION,
            true
        );

        wp_localize_script(
            'ptdm-admin-scripts',
            'ptdm_ajax',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'ptdm_export_nonce' ),
                'strings'  => array(
                    'exporting' => __( 'Exporting...', 'plugin-theme-dashboard-manager' ),
                    'error'     => __( 'An error occurred. Please try again.', 'plugin-theme-dashboard-manager' ),
                ),
            )
        );
    }

    /**
     * Admin page content
     *
     * @since 1.0.0
     */
    public function admin_page_content() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'plugin-theme-dashboard-manager' ) );
        }

        // Get current tab
        $current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'plugins';

        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Plugin & Theme Dashboard', 'plugin-theme-dashboard-manager' ); ?></h1>
            
            <p class="description">
                <?php esc_html_e( 'View and manage all installed plugins and themes. Export the complete list to CSV for documentation purposes.', 'plugin-theme-dashboard-manager' ); ?>
            </p>

            <div class="ptdm-export-section">
                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <?php wp_nonce_field( 'ptdm_export_csv', 'ptdm_export_nonce' ); ?>
                    <input type="hidden" name="action" value="ptdm_export_csv">
                    <button type="submit" class="button button-primary">
                        <span class="dashicons dashicons-download"></span>
                        <?php esc_html_e( 'Export to CSV', 'plugin-theme-dashboard-manager' ); ?>
                    </button>
                </form>
            </div>

            <nav class="nav-tab-wrapper">
                <a href="?page=plugin-theme-dashboard&tab=plugins" 
                   class="nav-tab <?php echo 'plugins' === $current_tab ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'Plugins', 'plugin-theme-dashboard-manager' ); ?>
                    <span class="ptdm-count">(<?php echo esc_html( count( $this->get_plugins_data() ) ); ?>)</span>
                </a>
                <a href="?page=plugin-theme-dashboard&tab=themes" 
                   class="nav-tab <?php echo 'themes' === $current_tab ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'Themes', 'plugin-theme-dashboard-manager' ); ?>
                    <span class="ptdm-count">(<?php echo esc_html( count( $this->get_themes_data() ) ); ?>)</span>
                </a>
            </nav>

            <div class="ptdm-content">
                <?php if ( 'plugins' === $current_tab ) : ?>
                    <?php $this->display_plugins_table(); ?>
                <?php else : ?>
                    <?php $this->display_themes_table(); ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Display plugins table
     *
     * @since 1.0.0
     */
    private function display_plugins_table() {
        $plugins = $this->get_plugins_data();
        ?>
        <div class="ptdm-table-container">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th scope="col"><?php esc_html_e( 'Plugin Name', 'plugin-theme-dashboard-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Version', 'plugin-theme-dashboard-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Status', 'plugin-theme-dashboard-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Author', 'plugin-theme-dashboard-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Description', 'plugin-theme-dashboard-manager' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $plugins ) ) : ?>
                        <tr>
                            <td colspan="5"><?php esc_html_e( 'No plugins found.', 'plugin-theme-dashboard-manager' ); ?></td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ( $plugins as $plugin ) : ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html( $plugin['name'] ); ?></strong>
                                </td>
                                <td><?php echo esc_html( $plugin['version'] ); ?></td>
                                <td>
                                    <span class="ptdm-status ptdm-status-<?php echo esc_attr( $plugin['status'] ); ?>">
                                        <?php echo esc_html( $plugin['status'] ); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html( $plugin['author'] ); ?></td>
                                <td><?php echo esc_html( $plugin['description'] ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Display themes table
     *
     * @since 1.0.0
     */
    private function display_themes_table() {
        $themes = $this->get_themes_data();
        ?>
        <div class="ptdm-table-container">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th scope="col"><?php esc_html_e( 'Theme Name', 'plugin-theme-dashboard-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Version', 'plugin-theme-dashboard-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Status', 'plugin-theme-dashboard-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Author', 'plugin-theme-dashboard-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Description', 'plugin-theme-dashboard-manager' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $themes ) ) : ?>
                        <tr>
                            <td colspan="5"><?php esc_html_e( 'No themes found.', 'plugin-theme-dashboard-manager' ); ?></td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ( $themes as $theme ) : ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html( $theme['name'] ); ?></strong>
                                </td>
                                <td><?php echo esc_html( $theme['version'] ); ?></td>
                                <td>
                                    <span class="ptdm-status ptdm-status-<?php echo esc_attr( $theme['status'] ); ?>">
                                        <?php echo esc_html( $theme['status'] ); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html( $theme['author'] ); ?></td>
                                <td><?php echo esc_html( $theme['description'] ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Get plugins data
     *
     * @since 1.0.0
     * @return array
     */
    public function get_plugins_data() {
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = get_plugins();
        $active_plugins = get_option( 'active_plugins' );
        $plugins_data = array();

        foreach ( $plugins as $plugin_file => $plugin_data ) {
            $plugins_data[] = array(
                'name'        => $plugin_data['Name'],
                'version'     => $plugin_data['Version'],
                'status'      => in_array( $plugin_file, $active_plugins, true ) ? 'active' : 'inactive',
                'author'      => $plugin_data['Author'],
                'description' => $plugin_data['Description'],
            );
        }

        // Sort by name
        usort( $plugins_data, function( $a, $b ) {
            return strcasecmp( $a['name'], $b['name'] );
        } );

        return $plugins_data;
    }

    /**
     * Get themes data
     *
     * @since 1.0.0
     * @return array
     */
    public function get_themes_data() {
        $themes = wp_get_themes();
        $current_theme = get_stylesheet();
        $themes_data = array();

        foreach ( $themes as $theme_slug => $theme ) {
            $themes_data[] = array(
                'name'        => $theme->get( 'Name' ),
                'version'     => $theme->get( 'Version' ),
                'status'      => $theme_slug === $current_theme ? 'active' : 'inactive',
                'author'      => $theme->get( 'Author' ),
                'description' => $theme->get( 'Description' ),
            );
        }

        // Sort by name
        usort( $themes_data, function( $a, $b ) {
            return strcasecmp( $a['name'], $b['name'] );
        } );

        return $themes_data;
    }
} 