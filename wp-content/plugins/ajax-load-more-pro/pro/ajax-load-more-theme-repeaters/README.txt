=== Ajax Load More: Theme Repeaters ===

Contributors: dcooney
Author: Darren Cooney
Author URI: https://connekthq.com/
Plugin URI: https://connekthq.com/ajax-load-more/theme-repeaters/
Requires at least: 4.0
Tested up to: 5.6
Stable tag: trunk
Homepage: http://connekthq.com/ajax-load-more/
Version: 1.1.3.2

== Copyright ==
Copyright 2021 Darren Cooney, Connekt Media

This software is NOT to be distributed, but can be INCLUDED in WP themes: Premium or Contracted.
This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.


== Description ==

= Manage repeater templates from within your current theme directory =

The Theme Repeaters add-on will allow you to load, edit and maintain repeater templates directly from your current theme folder.

https://connekthq.com/plugins/ajax-load-more/add-ons/theme-repeaters/

== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-theme-repeaters.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `ajax-load-more-theme-repeaters.zip`
2. Extract the `ajax-load-more-theme-repeaters.zip` to your computer
3. Upload the `ajax-load-more-theme-repeaters` directory to the `/wp-content/plugins/` directory
4. Ensure Ajax Load More is installed prior to activating the1 repeater plugin
5. Activate the plugin in the Plugin dashboard


== Changelog ==

= 1.1.3.2 - February 16, 2021 =
* HOTFIX - Fix for potential issues with trailing commas causing fatal errors on servers PHP 7.2.x and lower.


= 1.1.3.1 - February 14, 2021 =
* HOTFIX - Fix for potential fatal error due to invalid amount of arguments being passed to Theme Repeaters filter.
* UPDATE - Adding defaults to all filters.


= 1.1.3 - February 11, 2021 =
* UPGRADE NOTICE - You must update core ALM when updating Theme Repeaters.
* Update - Update integration with core ALM to include new functionality for [passing variables](https://connekthq.com/plugins/ajax-load-more/docs/code-samples/passing-variables/) method.
* UPDATE - Various updates to support PHP 8.0+.


= 1.1.2 - January 25, 2020 =
* UPDATE - Updated HTML layout of Theme Repeater selection in shortcode builder.


= 1.1.1 - May 6, 2019 =
* FIX - Fixed issue if core Ajax Load More is deactivated the add-on will throw a fatal error becasue of undefined methods.


= 1.1 - June 7, 2018 =
- SECURITY - Adding security patch to fix potential vulnerability in Theme Repeater file paths.


= 1.0.9 - November 16, 2017 =
* UPDATE - added support for using Theme Repeater templates in the new Users add-on.


= 1.0.8 - October 5, 2017 =
* UPDATE - added support for Gallery Field Type of the Advanced Custom Fields extensions.
* UPDATE - Updating plugin updater script


= 1.0.7 - April 4, 2017 =
* FIX - Adding compatibility with PHP 7.1
* UPDATE - Updating plugin updater script


= 1.0.6 - May 24, 2016 =
* NEW - Adding new alm_get_rest_theme_repeater() function for compatibility wit REST API add-on.


= 1.0.5 =
* FIX - Adding new $alm_current; variable that will return the current item number relative to the active loop - must upgrade core Ajax Load More to 2.8.7.


= 1.0.4 =
* FIX - Fixed add-on php warning that would appear upon plugin activation.


= 1.0.3 =
* FIX - Fixed issue with the passing of ALM template variables.


= 1.0.2 =
* NEW - Adding child theme support.


= 1.0.1 =
* BUG - Fix PHP warning for users with PHP version less than 5.4 installed.


= 1.0 =
* Initial plugin release
