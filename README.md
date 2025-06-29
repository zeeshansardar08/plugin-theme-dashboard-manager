# Plugin & Theme Dashboard Manager

A comprehensive WordPress plugin that provides an organized dashboard to view and export all installed plugins and themes with their status information.

## Description

Plugin & Theme Dashboard Manager creates a dedicated admin page within the WordPress dashboard where administrators can:

- View all installed plugins with their status (active/inactive)
- View all installed themes with their status (active/inactive)
- Export the complete list to CSV for documentation purposes
- Search and sort through the data
- Access detailed information including versions, authors, and descriptions

## Features

### Core Features
- **Plugin Management**: View all installed plugins with status indicators
- **Theme Management**: View all installed themes with status indicators
- **CSV Export**: Export complete plugin and theme data to CSV format
- **Responsive Design**: Works seamlessly on desktop and mobile devices
- **Search Functionality**: Quickly find specific plugins or themes
- **Sortable Tables**: Sort data by any column (name, version, status, author)

### Security Features
- **Capability Checks**: Only users with `manage_options` capability can access
- **Nonce Verification**: All form submissions are protected with nonces
- **Input Sanitization**: All user input is properly sanitized
- **Output Escaping**: All output is properly escaped for security

### User Experience
- **WordPress Admin Integration**: Follows WordPress admin design patterns
- **Loading States**: Visual feedback during export operations
- **Keyboard Shortcuts**: Quick access with keyboard navigation
- **Tab Navigation**: Easy switching between plugins and themes views
- **Status Indicators**: Clear visual status badges for active/inactive items

## Installation

### Method 1: WordPress Admin (Recommended)
1. Download the plugin ZIP file
2. Go to **Plugins > Add New** in your WordPress admin
3. Click **Upload Plugin** and select the ZIP file
4. Click **Install Now** and then **Activate**

### Method 2: Manual Installation
1. Extract the plugin files
2. Upload the `plugin-theme-dashboard-manager` folder to `/wp-content/plugins/`
3. Go to **Plugins** in your WordPress admin
4. Find "Plugin & Theme Dashboard Manager" and click **Activate**

## Usage

### Accessing the Dashboard
1. After activation, go to **Tools > Plugin & Theme Dashboard** in your WordPress admin
2. The dashboard will display all installed plugins and themes

### Viewing Data
- **Plugins Tab**: Shows all installed plugins with their status
- **Themes Tab**: Shows all installed themes with their status
- **Status Indicators**: Green badges for active items, red for inactive
- **Count Display**: Shows the number of items in each category

### Exporting Data
1. Click the **"Export to CSV"** button at the top of the page
2. The file will be automatically downloaded with a timestamp
3. The CSV includes: Type, Name, Version, Status, Author, Description

### Search and Sort
- **Search**: Use the search box to filter items by name, author, or description
- **Sort**: Click any column header to sort the data
- **Keyboard Shortcuts**:
  - `Ctrl/Cmd + E`: Export to CSV
  - `Ctrl/Cmd + 1`: Switch to Plugins tab
  - `Ctrl/Cmd + 2`: Switch to Themes tab

## Requirements

- **WordPress**: 6.0 or higher
- **PHP**: 7.4 or higher
- **User Role**: Administrator (manage_options capability)

## File Structure

```
plugin-theme-dashboard-manager/
├── plugin-theme-dashboard-manager.php    # Main plugin file
├── includes/
│   ├── admin-page.php                    # Admin page handler
│   └── csv-export.php                    # CSV export functionality
├── assets/
│   ├── css/
│   │   └── admin-styles.css              # Admin styles
│   └── js/
│       └── admin-scripts.js              # Admin JavaScript
├── languages/
│   └── plugin-theme-dashboard-manager.pot # Translation template
├── uninstall.php                         # Cleanup on uninstall
└── README.md                             # This file
```

## Development

### Code Standards
This plugin follows WordPress coding standards:
- **PHP**: WordPress Coding Standards (WPCS)
- **JavaScript**: WordPress JavaScript Coding Standards
- **CSS**: WordPress CSS Coding Standards

### Security Best Practices
- All user input is sanitized using `sanitize_text_field()`
- All output is escaped using `esc_html()` and `esc_attr()`
- Nonces are used for all form submissions
- Capability checks ensure proper access control

### Hooks and Filters
The plugin provides several hooks for extensibility:

```php
// Filter plugin data before display
add_filter('ptdm_plugins_data', 'my_custom_plugin_filter');

// Filter theme data before display
add_filter('ptdm_themes_data', 'my_custom_theme_filter');

// Modify CSV export data
add_filter('ptdm_csv_data', 'my_custom_csv_filter');
```

## Internationalization

The plugin is fully translatable:
- Text domain: `plugin-theme-dashboard-manager`
- POT file included in `/languages/` directory
- All user-facing strings are translatable

## Changelog

### Version 1.0.0
- Initial release
- Plugin and theme dashboard interface
- CSV export functionality
- Search and sort capabilities
- Responsive design
- Security implementation

## Support

For support, feature requests, or bug reports:
- **Website**: [https://zeecreatives.com](https://zeecreatives.com)
- **Email**: support@zeecreatives.com

## License

This plugin is licensed under the GPL v2 or later.

```
Copyright (C) 2024 ZeeCreatives

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
```

## Credits

- **Author**: ZeeCreatives
- **WordPress Compatibility**: 6.0+
- **PHP Compatibility**: 7.4+
- **Tested with**: WordPress 6.4

## WordPress.org Submission Checklist

This plugin is prepared for WordPress.org submission with:

- ✅ Proper plugin header with all required fields
- ✅ Security implementation (nonces, capability checks, sanitization)
- ✅ WordPress coding standards compliance
- ✅ Internationalization support
- ✅ Proper file structure
- ✅ Uninstall cleanup
- ✅ Comprehensive documentation
- ✅ Responsive design
- ✅ Accessibility considerations
- ✅ Performance optimization
- ✅ Error handling

## Future Enhancements

Potential features for future versions:
- Scheduled CSV exports via cron
- Email export functionality
- Plugin/theme update notifications
- Bulk operations
- Advanced filtering options
- API endpoints for external access
- Integration with popular backup plugins 