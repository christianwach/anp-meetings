=== Activist Network Meetings ===
Contributors: Pea, Glocal
Tags: meeting, custom post type, notes
Requires at least: 4.4
Tested up to: 4.7
Version: 1.0.11
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Creates custom post types for Meetings with custom fields and custom taxonomies that can be used to store and display meeting notes/minutes and decisions.

== Description ==

== Usage ==

== Revisions ==

= 1.0.11 - January 8, 2017 =
* Bug #1715 - Updated CMB2 library to latest.

= 1.0.10 - Dec 20, 2016 =
* Feature #1688 - Enabled users with `publish_posts` capability to publish meetings.

= 1.0.9 - Nov 29, 2016 =
* # Feature #1632 - Associate meeting post types with Events
   * Replaced `meeting_tag` with `event-tag`
   * Registered `event-tag` for agenda, summary and proposal
   * Removed `meeting_tag`
   * Modified connections from events to agenda, events to summary and events to proposals
   * Added `is_meeting` custom field
   * Made meeting taxonomy fields conditional based on `is_meeting` field
   * Added JS to show and hide meeting fields based on `is_meeting` field

= 1.0.8.3 - Nov 8, 2016 =
* Bug #1642 - Fixed issue with meetings post types' content not preserving line breaks.

= 1.0.8.2 - Aug 26, 2016 =
* Fixed issue causing "Agenda" not to appear on agenda archive pages.

= 1.0.8.1 - Aug 24, 2016 =
*[Feature #1524] Modified views to display new taxonomy.

= 1.0.8 - Aug 24, 2016 =
*[Feature #1524] Added Taxonomy for Meeting, Agenda, Proposal and Summary
* Added REST API support for post types and taxonomy
* Modified menu names to make more consistent and less overwhelming

= 1.0.7.1 - Aug 15, 2016 =
* Added dashicons for agenda, proposal and summary post types.

= 1.0.7 - Aug 15, 2016 =
* [Feature #859]
   * Added `meeting` capability_type and mapped so that roles can be assigned the capability.
   * Changed Meeting, Agenda, Summary and Proposal `capability_type` to `post` and `meeting`

= 1.0.6 - July 27, 2016 =
* Added support for comments to proposal custom post type.

= 1.0.5 - July 25, 2016 =
* Removed unnecessarily `console.log` from JS

= 1.0.4 - July 15, 2016 =
* [Feature #1483]
   * Removed `the_title` filter for proposals archives.
   * Hid custom fields metabox from meeting, agenda, summary and proposal edit screens
   * Removed proposal from pre-get filters

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
