=== Activist Network Meetings ===
Contributors: Pea, Glocal
Tags: meeting, custom post type, notes
Requires at least: 4.4
Tested up to: 4.4.1
Stable tag: 1.0.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Creates custom post types for Meetings with custom fields and custom taxonomies that can be used to store and display meeting notes/minutes and decisions.

== Description ==

== Useage == 

== Revisions ==

= 1.0.3 - July 14, 2016 =
* [Feature #1483][Bugfix] - Field fixes
   * Added meeting date field to summary
   * Made meeting date required
   * Changed label from Meeting Date to Date Accepted on proposals
* Fixed syntax error in archive view

= 1.0.2 - July 13, 2016 =
* [Feature #1483] - Converted custom fields to use CMB2 library instead of Advanced Custom Fields plugin.
* Added CMB2 library
* Added custom fields
* Replaced all instances of ACF `get_field` with `get_post_meta`