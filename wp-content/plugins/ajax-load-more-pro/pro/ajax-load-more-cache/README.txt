=== Ajax Load More: Cache ===

Contributors: dcooney
Author: Darren Cooney
Author URI: https://connekthq.com/
Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/cache/
Requires at least: 4.0.0
Tested up to: 5.6.0
Stable tag: trunk
Homepage: https://connekthq.com/ajax-load-more/
Version: 1.7.3


== Copyright ==
Copyright 2020 Darren Cooney

This software is NOT to be distributed, but can be INCLUDED in WP themes: Premium or Contracted.
This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.


== Description ==

= The Cache add-on creates static HTML files of Ajax Load More requests then serves those static pages to your visitors without querying the database. =

Caching will improve the user experience of your site by boosting server performance and dramatically reducing content load times for your visitors.

https://connekthq.com/plugins/ajax-load-more/add-ons/cache/

== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-cache.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `ajax-load-more-cache.zip`.
2. Extract the `ajax-load-more-cache` directory to your computer.
3. Upload the `ajax-load-more-cache` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the WP plugin dashboard.

== Changelog ==

= 1.7.3 - January 3, 2021 =
* FIX - Fixed issue with [Auto Cache Generation](https://connekthq.com/plugins/ajax-load-more/docs/add-ons/cache/#auto-generate) and included version of JQuery of WP 5.6.

= 1.7.2 - November 11, 2020 =
* FIX - Fixed issue with PHP error on Cache admin page when the cache directory does not exist.


= 1.7.1 - June 29, 2020 =
* FIX - Fixed issue with 'Delete cache when new posts are published.` setting causing issues for Editor, Author roles saving and creating posts.


= 1.7.0 - June 12, 2020 =
* NEW - Added support for caching results with the [Filters add-on](https://connekthq.com/plugins/ajax-load-more/add-ons/filters/)
* NEW - Added support for nested cache directories for when caching [Filters](https://connekthq.com/plugins/ajax-load-more/add-ons/filters/) or [WooCommerce](https://connekthq.com/plugins/ajax-load-more/add-ons/woocommerce/) add-on results.
* UPDATE - Updated warning before deleting a cache directory. Users are now instructed as to which specific directory is up for deletion.
* FIX - Fixed PHP warning message about undefined index 0 on ALM Cache admin page.


= 1.6.1 - May 1, 2020 =
* NEW - Added new cache API for caching Single Posts using the new implementation method. This new API will allow for more robust caching options in the future.


= 1.6.0 - December 28, 2018 =
* NEW - Added functionality to auto-generate ALM Cache - [view docs](https://connekthq.com/plugins/ajax-load-more/docs/add-ons/cache/#auto-generate).
* NEW - Added new `do_action('alm_clear_cache')` function to clear the entire cache. This allows developers to run custom cache clear scripts.
* NEW - Added uninstaller script to remove `alm-cache` directory on plugin deletion.
* UPDATE - Removing `mkdir()` functions in favor of core WP function `wp_mkdir_p()` which helps with permission issues on directory creation.


= 1.5.3 - September 19, 2018 =
* FIX - Fixed bug with cache creation for paged URLs and the SEO add-on.
* Update - Admin UI/UX updates


= 1.5.2 - April 17, 2018 =
* NEW - Added new `alm_cache_deleted` action dispatched after Ajax Load More cache is deleted and created.
* NEW - Added new `alm_custom_user_role` filter that allows developers to define the [user role](https://codex.wordpress.org/Roles_and_Capabilities) for access to view and delete the Ajax Load More cache. Default is [edit_theme_options](https://codex.wordpress.org/Roles_and_Capabilities#edit_theme_options).
* NEW - Added new language file for localization.
* UPDATE - Code clean up.


= 1.5.1 - November 16, 2017 =
* UPGRADE NOTICE - You should update core ALM to 3.3.0 before updating Cache to 1.5.1
* UPDATE - Updated interface of the cache dashboard to match updates to core ALM admin interface.


= 1.5.0 - May 22, 2017 =
* NEW - Added support for caching single posts with the Previous Post add-on.
* NEW - Added new `alm_cache_path` filter for updating the path to the cache directory.
* NEW - Added new `alm_cache_url` filter for updating the URL to the cache directory.
* UPDATE - Added hook (future_to_publish) for clearing cache after schedule pos is published.
* UPDATE - Updated value passed by the `data-cache-path` parameter. This parameter was required while using cache and filtering. Path without the cache_id is now passed as the `data-cache-path` value.
* UPDATE - Removing deprecated activation and de-activation functions.


= 1.4.0 - May 4, 2017=
* UPDATE NOTICE - Before updating to Cache 1.4.0 please update core Ajax load More to 3.0
* NEW - Added support for Advanced Custom Fields extension (Relationship + Repeater fields)
* NEW - Added support for Next Page add-on
* UPDATE - Moved cache directory from the ajax-load-more-chace directory to the WordPress uploads directory (uploads/alm-cache).
* UPDATE - Updated plugin updater class


= 1.3.1 =
* NEW -  New class for loading cache admin js
* UPDATE - Updating plugin updater script


= 1.3.0 =
* UPGRADE NOTICE - This update requires Ajax Load More 2.13.1.
* NEW -  Adding caching support for initial requests if page > 1. e.g. /page/10/ will now be cached and served to visitors as page-1-10.html
* FIX - JS issue with Cache settings page for Addon users.
* UPDATE - Fixed header alignment issue on Cache settings page.


= 1.2.4 =
* FIX - Adding support for Theme Repeater Templates.

= 1.2.3 =
* FIX - Fixing issue with deletion of individual cache items.


= 1.2.2 =
* UPDATE - Security fix for Ajax Load More 2.8.1.2


= 1.2.1 =
* UPDATE - Updating plugin update script. Users are now required to input a license key to receive updates directly within the WP Admin. Please contact us for information regarding legacy license keys.

= 1.2 =
* UPDATE - Moved Cache admin settings and shortcode settings from core ALM to add-on.


= 1.1 =
* Fixed issue with Cache + SEO where initial user might end up caching multiple pages in a single query if the page requested was greater than 1.
* Performance Updates.


= 1.0 =
* Initial Release.
