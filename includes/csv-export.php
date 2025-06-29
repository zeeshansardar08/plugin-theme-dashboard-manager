<?php
/**
 * CSV Export Handler
 *
 * @package PluginThemeDashboardManager
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * CSV Export Class
 *
 * @since 1.0.0
 */
class PTDM_CSV_Export {

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'admin_post_ptdm_export_csv', array( $this, 'handle_csv_export' ) );
        add_action( 'wp_ajax_ptdm_export_csv', array( $this, 'handle_ajax_csv_export' ) );
    }

    /**
     * Handle CSV export via admin-post.php
     *
     * @since 1.0.0
     */
    public function handle_csv_export() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'plugin-theme-dashboard-manager' ) );
        }

        // Verify nonce
        if ( ! isset( $_POST['ptdm_export_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ptdm_export_nonce'] ) ), 'ptdm_export_csv' ) ) {
            wp_die( __( 'Security check failed.', 'plugin-theme-dashboard-manager' ) );
        }

        $this->generate_csv();
    }

    /**
     * Handle AJAX CSV export
     *
     * @since 1.0.0
     */
    public function handle_ajax_csv_export() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'plugin-theme-dashboard-manager' ) );
        }

        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ptdm_export_nonce' ) ) {
            wp_send_json_error( __( 'Security check failed.', 'plugin-theme-dashboard-manager' ) );
        }

        $this->generate_csv();
    }

    /**
     * Generate and download CSV file
     *
     * @since 1.0.0
     */
    private function generate_csv() {
        // Get data
        $admin_page = new PTDM_Admin_Page();
        $plugins_data = $admin_page->get_plugins_data();
        $themes_data = $admin_page->get_themes_data();

        // Prepare CSV data
        $csv_data = array();

        // Add header row
        $csv_data[] = array(
            __( 'Type', 'plugin-theme-dashboard-manager' ),
            __( 'Name', 'plugin-theme-dashboard-manager' ),
            __( 'Version', 'plugin-theme-dashboard-manager' ),
            __( 'Status', 'plugin-theme-dashboard-manager' ),
            __( 'Author', 'plugin-theme-dashboard-manager' ),
            __( 'Description', 'plugin-theme-dashboard-manager' ),
        );

        // Add plugins data
        foreach ( $plugins_data as $plugin ) {
            $csv_data[] = array(
                __( 'Plugin', 'plugin-theme-dashboard-manager' ),
                $plugin['name'],
                $plugin['version'],
                $plugin['status'],
                $plugin['author'],
                $plugin['description'],
            );
        }

        // Add themes data
        foreach ( $themes_data as $theme ) {
            $csv_data[] = array(
                __( 'Theme', 'plugin-theme-dashboard-manager' ),
                $theme['name'],
                $theme['version'],
                $theme['status'],
                $theme['author'],
                $theme['description'],
            );
        }

        // Generate filename
        $filename = 'plugins_themes_list_' . date( 'Y-m-d_H-i-s' ) . '.csv';

        // Set headers for CSV download
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );

        // Create file pointer connected to the output stream
        $output = fopen( 'php://output', 'w' );

        // Add BOM for UTF-8
        fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

        // Write CSV data
        foreach ( $csv_data as $row ) {
            fputcsv( $output, $row );
        }

        // Close file pointer
        fclose( $output );

        exit;
    }

    /**
     * Get CSV data as array (for testing purposes)
     *
     * @since 1.0.0
     * @return array
     */
    public function get_csv_data() {
        $admin_page = new PTDM_Admin_Page();
        $plugins_data = $admin_page->get_plugins_data();
        $themes_data = $admin_page->get_themes_data();

        $csv_data = array();

        // Add header row
        $csv_data[] = array(
            __( 'Type', 'plugin-theme-dashboard-manager' ),
            __( 'Name', 'plugin-theme-dashboard-manager' ),
            __( 'Version', 'plugin-theme-dashboard-manager' ),
            __( 'Status', 'plugin-theme-dashboard-manager' ),
            __( 'Author', 'plugin-theme-dashboard-manager' ),
            __( 'Description', 'plugin-theme-dashboard-manager' ),
        );

        // Add plugins data
        foreach ( $plugins_data as $plugin ) {
            $csv_data[] = array(
                __( 'Plugin', 'plugin-theme-dashboard-manager' ),
                $plugin['name'],
                $plugin['version'],
                $plugin['status'],
                $plugin['author'],
                $plugin['description'],
            );
        }

        // Add themes data
        foreach ( $themes_data as $theme ) {
            $csv_data[] = array(
                __( 'Theme', 'plugin-theme-dashboard-manager' ),
                $theme['name'],
                $theme['version'],
                $theme['status'],
                $theme['author'],
                $theme['description'],
            );
        }

        return $csv_data;
    }
} 