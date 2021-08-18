=== Easy Query PRO ===
Contributors: dcooney
Donate link: http://connekthq.com/donate/
Tags: query, builder, wp_query, easy, simple, generator, paging, paged, quickly, shortcode builder, shortcode, search, tags, category, post types, taxonomy, meta_query, post format, wmpl, archives, date
Requires at least: 3.7
Tested up to: 5.7
Stable tag: 2.3.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy Query is the fastest and simplest way to build WordPress queries without ever touching a single line of code.

== Description ==

Create complex queries using the Easy Query custom query builder then add a generated shortcode to your pages via the content editor or directly into your template files using the WP_Query Generator.

https://connekthq.com/plugins/easy-query/


== Installation ==

How to install Easy Query PRO.

= Using FTP =

1. Download `easy-query-pro.zip`
2. Extract the `easy-query-pro` directory to your computer
3. Upload the `easy-query-pro` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Changelog ==

= 2.3.1.1 - March 21, 2021 =
* FIX - Removed issue with unintended echo when using Easy Query [Saved Query](https://connekthq.com/plugins/easy-query/documentation/saved-queries/) functionality.

= 2.3.1 - February 17, 2020 =
* NEW - Added `archive="true"` parameter that will automatically pull content on archive pages - taxonomy, category, tag, date (year, month, day) and authors are currently supported.

= 2.3.0 - January 21, 2020 =
* NEW - Added ability to load and run saved queries by shortcode. `[easy_query id="23"]`
* NEW - Added functionality to create new saved queries directly from the Saved Query section.
* UPDATE - Admin UI updates.

= 2.2.2 - August 11, 2019 =
* FIX - Fixed issue with Easy Query pagination showing last page link when not required.

= 2.2.1 - July 9, 2019 =
* FIX - Fixed issue with Easy Query pagination not working on static frontpage.
* UPDATE - Updated plugin updater.

= 2.2 - April 26, 2018 =
* NEW - Added support for multiple taxonomy queries.
* NEW - Added support for multiple meta queries.
* NEW - Added support for `tag__and` and `category__and` WordPress queries.
* FIX - Fix for saved query error in PHP 7.1+

= 2.1 - January 5, 2017=
** NEW - Added responsive layout templates - users can select a predefined template from the Template page - https://connekthq.com/plugins/easy-query/docs/layouts/
** NEW - Added ability to load CSS from current theme directory - https://connekthq.com/plugins/easy-query/docs/css/
** NEW - New filter hooks for template content - docs coming soon.
** NEW - Adding generated shortcode to Wp_Query generator. It can now be saved with a query.
** UPDATE - Updating plugin updater class (EDD_SL_Plugin_Updater) to 1.6.

= 2.0 - December 12, 2016=
** NEW - New look and Feel for 2017 :)
** NEW - Updated paging styles and settings.
** UPDATE - Improvements in template loading.
** UPDATE - Improved WP_Query.

= 1.1.1 =
** FIX ** Pagination not working.
** Update ** Various UI Improvements.

= 1.1.0 =
** NEW ** Updated Saved Queries to allow for title changes.
** FIX ** Issue with saved queries not saving periodically.
** UPDATE ** Various UI Updates and enhancements.
** UPDATE ** Updated License activation form.

= 1.0.5 =
** FIX ** Updated custom classes being applied to multiple elements in the parsed shortcode. custom_classes param should only be applied to the direct parent of easy query items.
** UPDATE ** Updating broken admin layout in WordPress 4.4

= 1.0.4 =
** NEW ** Added new shortcode parameter 'custom_args' which will let users pass custom query params. e.g. custom_args="post_parent:1745;tag_slug__and:array(design,development)"

= 1.0.3 =
Security Update - We have added an extra layer of security verification around the saving of custom templates and queries.

= 1.0.2 =
Fix for date query parameters

= 1.0.1 =
Updating multisite activation function. Easy Query installation function is now triggered when a new site is created.

= 1.0 =
Initial Release
