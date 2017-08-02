=== Plugin Name ===
Contributors: OntoDevelopment
Donate link: http://ontodevelopment.com/donate/
Tags: meetup.com, meetup
Requires at least: 3.9.1
Tested up to: 3.9.1
Vsersion: 0.02
Stable tag: 0.02
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to list Meetup.com events associated to a venue.

== Description ==

Meetup Venues allows you to list Meetup.com events associated to a venue. This plugin is useful for the venue's
website to display what is going on at their venue.

A caveat to this plugin; when groups/users post events they do not always use the existing venue. This results in there
being more than one venue listed per real physical location. You may need to use a comma deliminated list for the
venue_id attribute.

== Installation ==

1. Create a new directory called 'meetup-venues' in '/wp-content/plugins/'
1. Upload the contents of the zip file to the '/wp-content/plugins/meetup-venues' directory
1. Activate the plugin through the 'Plugins' menu in WordPress.

After installing and activating this plugin, you will need to provide your Meetup.com API key.

Your key can be acquired here: https://secure.meetup.com/meetup_api/key/

== Frequently Asked Questions ==

= Do I need to be a member of Meetup.com? =

Yes, in order to get an API key you will need to be a member. However this plugin uses nothing else from your account.

== Screenshots ==

1. Venues Search in Admin Dashboard
1. Shortcode being used in the post content and a widget

== Changelog ==

= 0.02 =
MVP Beta
* Changed the admin menues
* Updated the html & css for the shortcode generator to be cleaner
* Minor PHP changes to better follow "best practices"

= 0.01 =
MVP Beta
* Launched initial beta version

== Upgrade Notice ==
No upgrades planned.