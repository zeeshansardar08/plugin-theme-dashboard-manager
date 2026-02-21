/**
 * Site Extensions Snapshot - Admin Scripts
 *
 * @package PluginThemeDashboardManager
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Main Admin Scripts Class
     */
    var PTDMAdmin = {

        /**
         * Initialize the admin scripts
         */
        init: function() {
            this.bindEvents();
            this.initTooltips();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            // Export button click handler
            $(document).on('click', '.ptdm-export-section .button', function(e) {
                PTDMAdmin.handleExportClick(e);
            });

            // Tab navigation
            $(document).on('click', '.nav-tab-wrapper .nav-tab', function(e) {
                PTDMAdmin.handleTabClick(e);
            });

            // Keyboard navigation
            $(document).on('keydown', function(e) {
                PTDMAdmin.handleKeyboardNavigation(e);
            });
        },

        /**
         * Handle export button click
         *
         * @param {Event} e - Click event
         */
        handleExportClick: function(e) {
            var $button = $(e.currentTarget);
            var $form = $button.closest('form');
            
            // Add loading state
            $button.addClass('ptdm-loading');
            $button.prop('disabled', true);
            
            // Update button text
            var originalText = $button.html();
            $button.html('<span class="dashicons dashicons-update"></span> ' + ptdm_ajax.strings.exporting);
            
            // Submit form
            $form.submit();
            
            // Reset button after a delay (in case of error)
            setTimeout(function() {
                $button.removeClass('ptdm-loading');
                $button.prop('disabled', false);
                $button.html(originalText);
            }, 5000);
        },

        /**
         * Handle tab navigation
         *
         * @param {Event} e - Click event
         */
        handleTabClick: function(e) {
            var $tab = $(e.currentTarget);
            var targetTab = $tab.attr('href').split('tab=')[1];
            
            // Store current tab in session storage
            sessionStorage.setItem('ptdm_current_tab', targetTab);
        },

        /**
         * Handle keyboard navigation
         *
         * @param {Event} e - Keydown event
         */
        handleKeyboardNavigation: function(e) {
            // Ctrl/Cmd + E for export
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 69) {
                e.preventDefault();
                $('.ptdm-export-section .button').click();
            }
            
            // Ctrl/Cmd + 1 for plugins tab
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 49) {
                e.preventDefault();
                $('.nav-tab-wrapper .nav-tab[href*="tab=plugins"]').click();
            }
            
            // Ctrl/Cmd + 2 for themes tab
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 50) {
                e.preventDefault();
                $('.nav-tab-wrapper .nav-tab[href*="tab=themes"]').click();
            }
        },

        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            // Add tooltips to status indicators
            $('.ptdm-status').each(function() {
                var $status = $(this);
                var status = $status.text().toLowerCase();
                var tooltip = '';
                
                if (status === 'active') {
                    tooltip = ptdm_ajax.strings.tooltip_active;
                } else if (status === 'inactive') {
                    tooltip = ptdm_ajax.strings.tooltip_inactive;
                }
                
                if (tooltip) {
                    $status.attr('title', tooltip);
                }
            });
        },

        /**
         * Show notification message
         *
         * @param {string} message - Message to display
         * @param {string} type - Message type (success, error, warning)
         */
        showNotification: function(message, type) {
            type = type || 'success';
            
            var $notice = $('<div class="ptdm-notice ptdm-notice-' + type + '">' + message + '</div>');
            
            $('.wrap h1').after($notice);
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $notice.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        },

        /**
         * Format file size
         *
         * @param {number} bytes - Size in bytes
         * @returns {string} Formatted size
         */
        formatFileSize: function(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            var k = 1024;
            var sizes = ['Bytes', 'KB', 'MB', 'GB'];
            var i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        /**
         * Debounce function
         *
         * @param {Function} func - Function to debounce
         * @param {number} wait - Wait time in milliseconds
         * @returns {Function} Debounced function
         */
        debounce: function(func, wait) {
            var timeout;
            return function executedFunction() {
                var later = function() {
                    clearTimeout(timeout);
                    func();
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        /**
         * Search functionality
         */
        initSearch: function() {
            var $searchInput = $('<input type="text" class="ptdm-search" placeholder="' + ptdm_ajax.strings.search_placeholder + '" />');
            $('.ptdm-export-section').after($searchInput);
            
            var debouncedSearch = this.debounce(function() {
                var searchTerm = $searchInput.val().toLowerCase();
                
                $('.ptdm-table-container tbody tr').each(function() {
                    var $row = $(this);
                    var text = $row.text().toLowerCase();
                    
                    if (text.indexOf(searchTerm) > -1) {
                        $row.show();
                    } else {
                        $row.hide();
                    }
                });
            }, 300);
            
            $searchInput.on('input', debouncedSearch);
        },

        /**
         * Initialize responsive table
         */
        initResponsiveTable: function() {
            var $tables = $('.ptdm-table-container table');
            
            $tables.each(function() {
                var $table = $(this);
                var $wrapper = $table.closest('.ptdm-table-container');
                
                // Add responsive wrapper if not exists
                if (!$wrapper.hasClass('ptdm-responsive')) {
                    $wrapper.addClass('ptdm-responsive');
                }
            });
        },

        /**
         * Initialize sortable tables
         */
        initSortableTables: function() {
            $('.ptdm-table-container th').each(function() {
                var $th = $(this);
                var columnIndex = $th.index();
                
                $th.css('cursor', 'pointer');
                $th.append('<span class="dashicons dashicons-arrow-up-alt2 ptdm-sort-icon"></span>');
                
                $th.on('click', function() {
                    PTDMAdmin.sortTable($th.closest('table'), columnIndex);
                });
            });
        },

        /**
         * Sort table by column
         *
         * @param {jQuery} $table - Table element
         * @param {number} columnIndex - Column index to sort by
         */
        sortTable: function($table, columnIndex) {
            var $tbody = $table.find('tbody');
            var $rows = $tbody.find('tr').toArray();
            var sortDirection = $table.data('sort-direction') === 'asc' ? 'desc' : 'asc';
            
            // Sort rows
            $rows.sort(function(a, b) {
                var aText = $(a).find('td').eq(columnIndex).text().trim();
                var bText = $(b).find('td').eq(columnIndex).text().trim();
                
                if (sortDirection === 'asc') {
                    return aText.localeCompare(bText);
                } else {
                    return bText.localeCompare(aText);
                }
            });
            
            // Re-append sorted rows
            $tbody.empty().append($rows);
            
            // Update sort direction
            $table.data('sort-direction', sortDirection);
            
            // Update sort icons
            $table.find('.ptdm-sort-icon').removeClass('dashicons-arrow-up-alt2 dashicons-arrow-down-alt2');
            $table.find('th').eq(columnIndex).find('.ptdm-sort-icon').addClass(
                sortDirection === 'asc' ? 'dashicons-arrow-up-alt2' : 'dashicons-arrow-down-alt2'
            );
        }
    };

    /**
     * Initialize when document is ready
     */
    $(document).ready(function() {
        PTDMAdmin.init();
        
        // Restore current tab from session storage
        var currentTab = sessionStorage.getItem('ptdm_current_tab');
        if (currentTab) {
            var $tab = $('.nav-tab-wrapper .nav-tab[href*="tab=' + currentTab + '"]');
            if ($tab.length) {
                $tab.addClass('nav-tab-active');
            }
        }
        
        // Initialize additional features
        PTDMAdmin.initSearch();
        PTDMAdmin.initResponsiveTable();
        PTDMAdmin.initSortableTables();
    });

})(jQuery); 






