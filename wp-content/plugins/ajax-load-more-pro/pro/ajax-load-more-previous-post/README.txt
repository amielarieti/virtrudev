=== Ajax Load More: Single Post ===

Contributors: dcooney
Author: Darren Cooney
Author URI: https://connekthq.com/
Plugin URI: https://connekthq.com/ajax-load-more/add-ons/single-post/
Requires at least: 4.0
Tested up to: 5.5
Stable tag: trunk
Homepage: https://connekthq.com/ajax-load-more/
Version: 1.5.2


== Copyright ==
Copyright 2020 Darren Cooney

This software is NOT to be distributed, but can be INCLUDED in WP themes: Premium or Contracted.
This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.


== Description ==

The Single Post add-on will allow you to navigate your single posts with Ajax and adjust the browser URL as you do.

http://connekthq.com/plugins/ajax-load-more/single-post/



== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-previous-post.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard


= Using FTP =

1. Download `ajax-load-more-previous-post.zip`.
2. Extract the `ajax-load-more-previous-post` directory to your computer.
3. Upload the `ajax-load-more-previous-post` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the Plugin dashboard.


== Changelog ==

= 1.5.2 - February 11, 2021 =
* NEW - Added post preview functionality - [View Example](https://connekthq.com/accessibility-and-ajax-load-more/?showads=showpreview).
* FIX - Fixed URL issues with nesting Next Page add-on inside Single Posts add-on.
* UPDATE - PHP and JS code cleanup.
* UPDATE - Various updates to support PHP 8.0+.


= 1.5.1 - January 3, 2021 =
* Fix - Fixed issue with custom query returning all posts if the query was empty.
* FIX - Fixed issue with new custom query feature failing to pass the correct data to the shortcode.


= 1.5.0 - November 11, 2020 =
* NEW - Added support for custom queries using core taxonomy, category and tag query parameters in Ajax Load More 🎉


= 1.4.4 - April 22, 2020 =
* FIX - Fixed issue with anchor links taking user to the top of the page.
* FIX - Fixed issue with encoded characters in post titles.
* UPDATE - Added support for Yoast page titles.


= 1.4.3 - March 18, 2020 =
* NEW - Added new Single Post [implementation](https://connekthq.com/plugins/ajax-load-more/add-ons/single-posts/#implementation) technique. Users are no longer required to use a Repeater Template when using this add-on.


= 1.4.2 - October 1, 2019 =
* NEW - Added option to display a reading progress bar. `single_post_progress_bar` is the shortcode parameter - please view the Shortcode Builder for implementation help.
* UPDATE - Updated default `Scroll to Post` behaviour setting to false.


= 1.4.1 - May 29, 2019 =
This update relates to fixes applied to Ajax Load More `5.1.1`
* FIX - Added fix for browser popstate issue in core Ajax Load More.
* UPDATE - Removed REST API endpoint.


= 1.4.0 - May 6, 2019 =
* UPGRADE NOTICE - This update requires Ajax Load More 5.1+.
* NEW - Single Posts add-on now uses the REST API for Ajax queries. This change can be reverted in ALM Settings.
* FIX - Added fix to suppress PHP warning messages about WP_Query parameters.


= 1.3.2 - March 8, 2019 =
* NEW - Adding translation file.
* NEW - Added update to allow for offsetting first post. Users can now set `offset="1"` in the `[ajax_load_more]` shortcode and render a custom single template before Ajax Load More.
* NEW - Added new `getOffset` function to more reliably get the correct permalink URL while scrolling.
* UPDATE - Update Google Analytics Tracking script.
* UPDATE - Improved scroll to element functionality and URL updates.
* UPDATE - Removed Scroll Speed function and it has been deprecated.
* UPDATE - Updated scroll to element function to use core ALM almScroll function.
* UPDATE - Setting `$wp_query->in_the_loop` and `$wp_query->is_feed` to true for allowing various 3rd plugins to run hooks ion Ajax content.
* FIX - Fixed a bug where the browser URL would not update if a user clicked the back or forward browser buttons.


= 1.3.1 - January 19, 2019 =
* FIX - Fix for `$` functions causing JavaScript errors for some users.


= 1.3.0 - January 15, 2019 =
* UPGRADE NOTICE - If you run this update you must also update ALM core to 4.2.0.
* NEW - Added support for load order single posts by Previous, Next, Latest and ID.
* UPDATE - Renamed addon to `Single Post` from `Previous Post`.
* UPDATE - Adding strip_tags function to page title


= 1.2.3 - January 22, 2018 =
* NEW - Added support for the new gtag Analytics script.


= 1.2.2 - December 7, 2017 =
* NEW - Added new `Back/Fwd Buttons` global setting that will enable/disable pushstate from hijacking the browser back/fwd buttons.
* UPDATE - Updated cache URL parameters when using Cache add-on with Previous Post.


= 1.2.1 - August 8, 2017 =
* NEW - Added support for excluding categories from the previous post query. `previous_post_excluded_terms="23, 76, 90"`.

= 1.2 - May 22, 2017 =
* Added support for caching single posts with the Cache add-on
* NEW - Added support for Disqus comments. When activated, Disqus comments will load via Ajax on post currently in view. Requires ALM 3.0.1.
* FIX - Fixed issue in PHP 7.1 where $data array was being initialized as a string.
* UPDATE - Removing deprecated activation and de-activation functions.
* UPDATE - Updated plugin updater script.

= 1.1.6 =
* UPDATE - Force is_single() and is_singular() to be true in the ajax call.
* UPDATE - Code cleanup.

= 1.1.5 =
* FIX - Updated alm_prev_post_inc() function that fixes issue with post not rendering in preview mode.

= 1.1.4 =
* UPDATE - Updating URL passed to Google Analytics.

= 1.1.3 =
* UPDATE - Adding Google Analytics support for Yoast GA (__gaTracker()) function.

= 1.1.2 =
* FIX - Fixed issue with popstate javascript function firing on page load in Safari.

= 1.1.1 =
* FIX - Fixing php error with  calling function in Theme Repeater add-on.

= 1.1 =
* NEW - Adding new 'previous_post_taxonomy' parameter to allow for querying posts within same taxonomy.
* NEW - Adding new $.fn.almUrlUpdate(permalink, type) callback function. Dispatched after a URL change.

= 1.0.2 =
* UPDATE - Enqueue Previous Post JS only if Ajax Load More shortcode ([ajax_load_more]) is active on current page.

= 1.0.1 =
* BUG - Fixed issue with fwd and back browser buttons. In webkit browsers the user was not moved to the previous/next post.

= 1.0 =
* Initial Release.
