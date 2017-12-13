=== WordPress Meetings ===
Contributors: Pea, Glocal, needle
Tags: meeting, custom post type, notes
Requires at least: 4.4
Tested up to: 4.9
Version:  2.0.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Creates custom post types for Meetings with custom fields and custom taxonomies that can be used to store and display Meeting Notes/Minutes, Agendas, Proposals and Summaries.



== Description ==

The *WordPress Meetings* plugin creates custom post types for Meetings with custom fields and custom taxonomies that can be used to store and display Meeting Notes/Minutes, Agendas, Proposals and Summaries.

Prior to version 2.0, this plugin was known as *ANP Meetings*. Please deactivate *ANP Meetings* before activating this plugin. The settings from *ANP Meetings* are imported into this plugin on activation and existing content remains unaltered.



== Installation ==

1. Extract the plugin archive
1. Upload plugin files to your `/wp-content/plugins/` directory
1. Optionally make sure that Event Organiser is activated and properly configured
1. Activate the plugin through the 'Plugins' menu in WordPress



== Changelog ==

= 2.0.2 =
* Template fixes and enhancements

= 2.0.1 =
* Migration functionality
* Event kept in sync with Meeting

= 2.0 =
* Major code refactoring
* Removed CMB2 dependency

= 1.3.0 =
* Added meeting Options
* Added default styling that can be overridden in options

= 1.2.0 =
* Replace included CMB2 and Posts-to-Posts libraries with standalone plugins
* Added tmg-plugin-activation library

= 1.1.0 =
* #1639 - Display event link on meetings archive and single meeting
* Display meeting link on single event
* Added styling and Dashicons

= 1.0.9.1 =
* #1632 - Add connection from Meeting to Event, if Event post type exists

= 1.0.9 =
* Feature #1654 & Feature #1688 - Upon plugin activation, added capabilities to roles that can `publish_posts` (by default authors).
* Set capabilities function to return capabilities
* Added filters that can be used to modify capabilities
* Bug #1729
   * Added filters rewrite rules for custom post types and taxonomies.
   * Added `flush_rewrite_rules` on activation
   * Added `flush_rewrite_rules` on custom taxonomy change

= 1.0.8.5 =
* Updated CMB2 and Posts-to-Posts libraries

= 1.0.8.4 =
* Reverted to stable version.

= 1.0.8.3 =
* Bug #1642 - Fixed issue with meetings post types' content not preserving line breaks.

= 1.0.8.2 =
* Fixed issue causing "Agenda" not to appear on agenda archive pages.

= 1.0.8.1 =
*[Feature #1524] Modified views to display new taxonomy.

= 1.0.8 =
*[Feature #1524] Added Taxonomy for Meeting, Agenda, Proposal and Summary
* Added REST API support for post types and taxonomy
* Modified menu names to make more consistent and less overwhelming

= 1.0.7.1 =
* Added dashicons for agenda, proposal and summary post types.

= 1.0.7 =
* [Feature #859]
   * Added `meeting` capability_type and mapped so that roles can be assigned the capability.
   * Changed Meeting, Agenda, Summary and Proposal `capability_type` to `post` and `meeting`

= 1.0.6 =
* Added support for comments to proposal custom post type.

= 1.0.5 =
* Removed unnecessarily `console.log` from JS

= 1.0.4 =
* [Feature #1483]
   * Removed `the_title` filter for proposals archives.
   * Hid custom fields metabox from meeting, agenda, summary and proposal edit screens
   * Removed proposal from pre-get filters

= 1.0.3 =
* [Feature #1483][Bugfix] - Field fixes
   * Added meeting date field to summary
   * Made meeting date required
   * Changed label from Meeting Date to Date Accepted on proposals
* Fixed syntax error in archive view

= 1.0.2 =
* [Feature #1483] - Converted custom fields to use CMB2 library instead of Advanced Custom Fields plugin.
* Added CMB2 library
* Added custom fields
* Replaced all instances of ACF `get_field` with `get_post_meta`
