<?php

/**
 * Plugin Name: Meetup.com Venues
 * Plugin URI: http://ontodevelopment.com
 * Description: Show Meetup.com events that are venue specific
 * Version: 0.02
 * Author: Brian Wendt
 * Author URI: http://ontodevelopment.com
 * License: GPL2
 * Template Engine: https://github.com/zordius/lightncandy
 */
if (!defined('MEETUP_VENUES_NAME'))
    define('MEETUP_VENUES_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('MEETUP_VENUES_DIR'))
    define('MEETUP_VENUES_DIR', WP_PLUGIN_DIR . '/' . MEETUP_VENUES_NAME);

if (!defined('MEETUP_VENUES_URL'))
    define('MEETUP_VENUES_URL', WP_PLUGIN_URL . '/' . MEETUP_VENUES_NAME);

if (!defined('MEETUP_VENUES_TEMPLATES'))
    define('MEETUP_VENUES_TEMPLATES', MEETUP_VENUES_DIR . '/templates/');

//Include the Meetup.com REST API code used for this plugin
include_once MEETUP_VENUES_DIR . '/classes/MeetupCom.php';

//Set the API key that the user supplied in the Settings page
MeetupCom::$default_params['key'] = get_option('meetup_api_key');
//If no key has been set yet
if (MeetupCom::$default_params['key'] === false) {
    //create the WP option with blank string
    add_option('meetup_api_key', '');
}

if (is_admin()) {
    /* Admin Panel Code */
    //include the class for handeling the Admin Dashboard
    include_once MEETUP_VENUES_DIR . '/classes/MeetupVenuesAdmin.php';
    $MeetupVenuesAdmin = new MeetupVenuesAdmin();

    //Add link
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'meetup_venues_settings_link');

    //Define the callback for the above filter
    function meetup_venues_settings_link($links) {
        //Add the link for the Settings page
        $settings_href = admin_url('options-general.php?page=meetup-venues-setting');
        $links[] = '<a href="' . $settings_href . '">Settings</a>';
        //Add the link for the Shorcode Generation page
        $shortcode_href = admin_url('admin.php?page=meetup-venues');
        $links[] = '<a href="' . $shortcode_href . '">Shortcode</a>';
        //return new links array
        return $links;
    }

} else {
    /* Frontend Code */
    //Include the class for handeling the Frontend
    include_once MEETUP_VENUES_DIR . '/classes/MeetupVenues.php';
    //Include the class for template rendering
    include_once MEETUP_VENUES_DIR . '/classes/lightncandy.php';

    //Add the [meetup-venues] shortcode with callback MeetupVenues::shortcode()
    add_shortcode('meetup-venues', array('MeetupVenues', 'shortcode'));

    /**
     * Add support to theme for shortcodes in widgets
     * @todo Look into if this is bad practice
     */
    add_filter('widget_text', 'do_shortcode');
}