=== Ajax Load More: Call to Actions ===

Contributors: dcooney
Author: Darren Cooney
Author URI: https://connekthq.com/
Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/call-to-actions/
Requires at least: 4.0
Tested up to: 5.6
Stable tag: trunk
Homepage: https://connekthq.com/
Version: 1.0.4.1

== Copyright ==
Copyright 2021 Darren Cooney

This software is NOT to be distributed, but can be INCLUDED in WP themes: Premium or Contracted.
This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.


== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-call-to-actions.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `ajax-load-more-call-to-actions.zip`.
2. Extract the `ajax-load-more-call-to-actions` directory to your computer.
3. Upload the `ajax-load-more-call-to-actions` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the WP plugin dashboard.


== Changelog ==

= 1.0.4.1 - February 16, 2021 =
* HOTFIX - Fix for potential issues with trailing commas causing fatal errors on servers PHP 7.2.x and lower.


= 1.0.4 - February 11, 2021 =
* UPGRADE NOTICE - You must update core ALM when updating Call to Actions.
* Update - Updated CTA and Theme Repeaters integration to include new functionality for [passing variables](https://connekthq.com/plugins/ajax-load-more/docs/code-samples/passing-variables/) method.
* UPDATE - Code clean and refactoring.


= 1.0.3 - May 6, 2019 =
* FIX - Fixed issue if core Ajax Load More is deactivated the add-on will throw a fatal error becasue of undefined methods.


= 1.0.2 - September 19, 2018 =
* UPGRADE NOTICE - If you update this add-on you MUST update to core Ajax Load More v3.7
* FIX - Fixed issue where CTAs were out of position causing issues with SEO add-on.
* FIX - Fixed issue with Paging add-on and CTA positions.


= 1.0.1 - July 17, 2018 =
* UPDATE - Removing `EDD_SL_Plugin_Updater` class include as it was causing performance issues when viewing the plugins dashboard.


= 1.0 - September 4, 2016 =
* Initial Release.
