=== Plugin & Theme Dashboard Manager ===
Contributors: zeecreatives
Tags: plugins, themes, dashboard, export, csv, admin
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A dashboard to view installed plugins and themes with status, plus CSV export.

== Description ==

Plugin & Theme Dashboard Manager adds a Tools page that lists all installed plugins and themes with their status (active/inactive), versions, authors, and descriptions. It also provides a one-click CSV export for documentation or audits.

Key features:

* Plugins and themes overview with status
* CSV export of plugins and themes
* Search and sortable columns
* Keyboard shortcuts for export and tab switching

Benefits:

* Faster audits and inventory reports
* Clear visibility into active vs inactive items
* Quick CSV export for compliance or client handoff
* No changes made to plugins or themes

Security and access:

* Capability checks (`manage_options`)
* Nonce verification for exports
* Sanitized input and escaped output

== Installation ==

1. Upload the `plugin-theme-dashboard-manager` folder to `/wp-content/plugins/`.
2. Activate the plugin through the Plugins menu.
3. Go to Tools -> Plugin & Theme Dashboard.

== Frequently Asked Questions ==

= Who can access the dashboard? =
Only users with the `manage_options` capability.

= Does this plugin modify plugins or themes? =
No. It only reads installed plugin and theme data and provides a CSV export.

= What data is stored? =
Only a single activation timestamp option is stored (`ptdm_activated`). No external requests are made.

== Screenshots ==

1. Plugins tab overview.
2. Themes tab overview.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
* Initial release.
