=== Ajax Load More: Comments ===

Contributors: dcooney
Author: Darren Cooney
Author URI: https://connekthq.com/
Plugin URI: https://connekthq.com/plugins/ajax-load-more/add-ons/comments/
Requires at least: 4.0
Tested up to: 5.3.0
Stable tag: trunk
Homepage: https://connekthq.com/
Version: 1.2.0.1


== Copyright ==
Copyright 2019 Darren Cooney

This software is NOT to be distributed, but can be INCLUDED in WP themes: Premium or Contracted.
This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.


== Description ==

= Load blog comments on demand with Ajax Load More and the Comments add-on! =

The Comments add-on queries your single post comments and returns them via Ajax.

http://connekthq.com/plugins/ajax-load-more/add-ons/comments/

== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-comments.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `ajax-load-more-comments.zip`.
2. Extract the `ajax-load-more-comments` directory to your computer.
3. Upload the `ajax-load-more-comments` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the WP plugin dashboard.

== Changelog ==

= 1.2.0.1 - November 18, 2019 =
* FIX - Fixed issue with using Paging, Preloaded and Comments together would cause pagination to be off by a page.
* UPDATE - Removed legacy REST API route


= 1.2.0 - May 6, 2019 =
* UPGRADE NOTICE - This update requires Ajax Load More 5.1+.
* NEW - Comments add-on now uses the REST API for Ajax queries. This change can be reverted in ALM Settings.
* NEW - Added custom JavaScript fix for reply links in Comment query causing page refresh.
* FIX - Fixed issue with `offset` parameter not working correctly.


= 1.1.4 - March 8, 2019 =
* FIX - Fixed issue with preloaded comments not working because of an incorrectly `comments_post_id`. 


= 1.1.3 - December 6, 2018 =
* FIX - Fixed issue with comment ordering when a user had 'Newer' comments appear first selected on `settings -> discussion` in admin. This fix ensure comments are always ordered by date and follows the ordering params set by Ajax Load More.
* FIX - Fixed issue with Preloaded comments options not passing correctly from core ALM.


= 1.1.2 - March 24, 2017 =
* Updated query function to support paging add-on
* Updated version of plugin update script.


= 1.1.1 - September 6, 2016 =
* MILESTONE - Must update core Ajax Load More to 2.12.0 when updating this add-on.
* FIX - Updated alm_comments_query() to return totalposts and postcount in json array - this is required for the latest Ajax Load More update. 


= 1.1 - May 27, 2016 =
* FIX - Fix for updated 'page' parameter in core Ajax Load More causing issue with repeating comments
* NEW - Adding support for Preloaded comments. Will be available when Ajax Load More 2.11.1 is released June 2, 2016.


= 1.0.2 =
* MILESTONE - Must update core Ajax Load More to 2.10 in order to run this updated add-on.
* UPDATE - Updating comment return type to json.


= 1.0.1 =
* UPDATE - Adding support for order and orderby parameters. Orderby uses values found in get_comments() function. https://codex.wordpress.org/Function_Reference/get_comments


= 1.0 =
* Initial Release.

