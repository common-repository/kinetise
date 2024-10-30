=== Plugin Name ===
Contributors: kinetise
Donate link: 
Tags: kinetise, api, endpoint, json, rest
Requires at least: 4.0
Tested up to: 4.6.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Kinetise WordPress plugin allows seamless communication with Mobile apps created in Kinetise.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Name screen to configure the plugin

== Changelog ==

= 2.0.5 =

- empty image appended to description

= 2.0.4 =

- default page size

= 2.0.3 =

- fixes small issues with php older than 5.5
- allows editing post content and title separately

= 2.0.2 =

- small fixes and improved stability
- better image handling

= 2.0 =

- authorization controller added (login, logout, register actions);
- authorized user with appropriate permitions now can add/edit/delete posts;
- comments now depend from wordpress settings (you could add comment even if that was forbidden in settings before);
- now authorized user can edit/delete his comment;
- if user has appropriate permithions he can edit/delete someone elses comennts;
- reply to comment action added (if nested comments allowed)

= 1.7 =

Codebase improvements

= 1.6 =

Updated tutorial, added versioning, added comments functionality

= 1.4 =

First public version

== Screenshots ==

No screenshots at the moment

== Upgrade Notice ==

= 1.7 =

Latest stable version of the plugin

== Frequently Asked Questions ==

= I cannot install the plugin =

Please check whether you have PHP 5.5 installed.