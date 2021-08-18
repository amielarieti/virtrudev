=== Ajax Load More: SEO ===

Contributors: dcooney
Author: Darren Cooney
Author URI: https://connekthq.com/
Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/search-engine-optimization/
Requires at least: 4.0
Tested up to: 5.4.1
Stable tag: trunk
Homepage: https://connekthq.com
Version: 1.9.3


== Copyright ==
Copyright 2018 Darren Cooney

This software is NOT to be distributed, but can be INCLUDED in WP themes: Premium or Contracted.
This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.


== Description ==

= Optimize your website URLs with the Ajax Load More Search Engine Optimization add-on! =

The SEO add-on will optimize your content for search engines and site visitors by generating unique paging URLs with each Ajax Load More query.

http://connekthq.com/plugins/ajax-load-more/seo/

== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-seo.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `ajax-load-more-seo.zip`.
2. Extract the `ajax-load-more-seo` directory to your computer.
3. Upload the `ajax-load-more-seo` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the Plugin dashboard.


== Changelog ==

= 1.9.3 - May 1, 2020 =
* UPDATE - Updated default setting for `Scroll to Page` to false for better UX and accessibility. 


= 1.9.2 - August 6, 2019 =
* FIX - Fixed issue with `alm_seo_leading_slash` filter not working in some situations. [Docs](https://connekthq.com/plugins/ajax-load-more/docs/add-ons/search-engine-optimization/#alm_seo_leading_slash)


= 1.9.1 - May 29, 2019 =
This update relates to fixes applied to Ajax Load More `5.1.1`
* FIX - Added fix for browser popstate issue in core Ajax Load More.


= 1.9.0 - March 8, 2019 =
## UPGRADE NOTICE
When updating to SEO 1.9.0 you must also update core (Ajax Load More)[https://wordpress.org/plugins/ajax-load-more/] to version 5.0.

#### What's New
* NEW - SEO URLS now hold the original querystring values as new pages are loaded.
* NEW - Plugin completely re-written in vanilla JS.
* FIX - Fixed IE11 error with `append()` function.
* UPDATE - Improved scroll to element functionality and URL updates. 
* UPDATE - Removed jQuery dependancy.
* UPDATE - Removed Scroll Speed setting as it is no longer used.

= 1.8.1 - December 6, 2018 =
* UPDATE - Fixed potential issue with plugin updater script.
* UPDATE - Improved JS comments.


= 1.8 - September 19, 2018 =
* NEW - Added support for <link rel="next" /> and Mli functionality for link rel next/prev in page <head>
* NEW - Added query results to <noscript/> tags for improved SEO indexing.
* NEW - Added SEO filter to remove <noscript/> injection `add_filter('alm_noscript', '__return_false');'
* NEW - Added support for `SCRIPT_DEBUG` and loading non-minified JS.
* NEW - Added pot file for translation strings.
* UPDATE - Code cleanup


= 1.7.2 - April 17, 2018 =
* NEW - Added new `alm_seo_leading_slash` filter to add a leading slash before {page #} in URL. Useful for users who's permalink's do not have a trailing slash. Available with Ajax Load More 3.5.0 or greater.

= 1.7.1 - January 22, 2018 =
* NEW - Added support for the new gtag Analytics script.

= 1.7.0 - November 16, 2017 =
* UPGRADE NOTICE - You should update core ALM to 3.3.0 before updating SEO to 1.7.0
* NEW - Added new `Back/Fwd Buttons` global setting that will enable/disable pushstate from hijacking the browser fwd/back buttons.
* UPDATE - Updated load more button to be html `<a/>` element when SEO is active.
* UPDATE - Improving SEO functionality by updating `href` of load more button to be next page url. e.g. `<a href="www.yoursite.com/page/4" />`.


= 1.6.4 - October 5, 2017 =
* NEW - Adding `alm_seo_remove_trailing_slash` filter to remove trailing slash


= 1.6.3 - July 14, 2017 =
* Fix - Fixed an issue that was introduced in 1.6.2 where scroll to post is trigged on initial Ajax load when Preloaded was activated - this issue affected users with 'Scroll to Post' set to false.
* UPDATE - Updating plugin updater script.


= 1.6.2 - June 27, 2017 =
* FIX - Fix for initial scrolling to page when 'Enable Window Scrolling' is false in Ajax Load More settings. Before this update, users would not be taking to the current page is this setting was false.


= 1.6.1 - March 2, 2017 =
* FIX - Fixed issue with paging URLs not sending urls to the correct location


= 1.6 - February 14, 2017 =
* NEW - Added support for default WP search URLS. e.g. website.com/page/3/?s=wordpress
* NEW - Added support for SEO + Paging + Preloaded
* UPDATE - Removed permalink structure setting. Now structure is set automatically from global WP settings
* UPDATE - Updating plugin updater script


= 1.5.3 - January 13, 2017 =
* Fix - Updated URL passed to Google Analytics - now passing an absolute path, not including domain.
* UPDATE - Adding updated plugin update script.

= 1.5.2 - August 4, 2016 =
* FIX - Fixed issue with fwd/back browser buttons. This affected users using the Paging and SEO add-ons together.

= 1.5.1 - June 16, 2016 =
* FIX - Fixed issue with fwd and back buttons triggering JS error.

= 1.5 - April 11, 2016 =
* MILESTONE RELEASE - You MUST update to core Ajax Load More 3.0 to run this update.
* UPDATE - Paging URLs now update as users scroll through 'pages'.
* UPDATE - Updating URL builder function - Base URL is now based on Canonical URL set by core Ajax Load More.
* UPDATE - Update to ugly permalink urls.
* FIX - Fixed issues with date archive URLs. e.g. /2015/12/


= 1.4.3 =
* UPDATE - Updating URL passed to Google Analytics.

= 1.4.2 =
* UPDATE - Adding Google Analytics support for Yoast GA (__gaTracker()) function.

= 1.4.1 =
* NEW - Adding new $.fn.almUrlUpdate(permalink, type) callback function. Dispatched after a URL change.

= 1.4 =
* UPDATE REQUIREMENT - This update requires Ajax Load More v2.8.3.
* UPDATE - Enqueue Search Engine Optimization JS only if Ajax Load More shortcode ([ajax_load_more]) is active on current page.
* NEW - Adding minified seo js file.

= 1.3.1 =
* UPDATE - Fix a javascript issue for Preloaded add-on and Seo Add-on.
* FIX - For popstate function. Function is now triggered if SEO is set to "true".

= 1.3 =
* UPDATE - Updating plugin update script. Users are now required to input a license key to receive updates directly within the WP Admin. Please contact us for information regarding legacy license keys.
* FIX - For popstate function. Function was triggered with any hash update - is now contained to ALM only.


= 1.2.2 =
* FIX - Undefined SEO variable.

= 1.2.1 =
* FIX - Undefined GA variable if in debug mode.
* FIX - Wrapping ga('send') function is isFunction() to protect against errors.

= 1.2 =
* UPDATE - Adding Google Analytics pageview support.
* UPDATE - Moved SEO settings and shortcode settings from core ALM to add-on.
* FIX - Fixed bug with Preloaded + SEO and scrolling to current page.

= 1.1.1 =
* FIX - Fixing bug where SEO scroll was not respected in ALM 2.6.1.

= 1.1 =
* UPDATE - Small performance updates to work with Preloaded and Cache.

= 1.0 =
* Initial Release.
