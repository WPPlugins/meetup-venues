<?php

class MeetupVenuesAdmin {

    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('wp_ajax_meetup_venues_search', array($this, 'venues_search'));
        add_action('wp_ajax_meetup_venue_events', array($this, 'venue_events'));
    }

    /**
     * Add options page
     */
    public function add_plugin_page() {

        $callback_settings = array($this, 'create_options_page');
        $callback_shortcodes = array($this, 'create_venues_page');

        $page_title = 'Meetup Venues';
        $menu_title = 'Meetup Venues';
        $capability = 'manage_options';
        $menu_slug = 'meetup-venues';

        // Add the top-level admin menu
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback_shortcodes);

        //This page is for shorcode generation
        $page_title = 'Meetup Venues';
        $sub_menu_title = 'Shortcode Generator';
        add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $callback_shortcodes);


        // This page will be under "Settings"
        $page_title = 'Settings';
        $sub_menu_title = 'Settings';
        $sub_menu_slug = 'meetup-venues-settings';
        add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $sub_menu_slug, $callback_settings);

        /*
          // This page will be under "Settings"
          add_options_page('Meetup Venues Settings', 'Meetup Venues', 'manage_options', 'meetup-venues-setting', array($this, 'create_options_page'));

          //This page is for shorcode generation
          add_menu_page('Meetup Venues', 'Meetup Venues', 'manage_options', 'meetup-venues', array($this, 'create_venues_page'), plugins_url('/images/icon.png', __FILE__));
         */
    }

    /**
     * Options page callback
     */
    public function create_options_page() {
        include_once dirname(__DIR__) . '/options.php';
    }

    /**
     * Venues page callback
     */
    public function create_venues_page() {
        //We need the jQuery Accordion plugin
        wp_enqueue_script('jquery-ui-accordion');
        include_once dirname(__DIR__) . '/venues.php';
    }

    /**
     * AJAX action "venues_search" callback
     */
    public function venues_search() {
        //Set the response to be JSON
        header('Content-type: application/json');
        //Set variables
        $city = !empty($_POST['city']) ? $_POST['city'] : false;
        $state = !empty($_POST['state']) ? $_POST['state'] : false;
        $country = !empty($_POST['country']) ? $_POST['country'] : 'US';
        $search = !empty($_POST['search']) ? $_POST['search'] : false;
        if (!$city) {
            json_encode(array('error' => 'Missing City'));
            exit;
        }
        if (!$state) {
            json_encode(array('error' => 'Missing State'));
            exit;
        }
        if (!$country) {
            json_encode(array('error' => 'Missing Country'));
            exit;
        }
        //Convert search string to be Meetup.com API ready
        $text = implode(' AND ', explode(' ', $search));
        //$text = $search . '*';
        //Make API request
        $response = MeetupCom::searchVenues($city, $state, $country, $text);
        if (!empty($response['results'])) {
            /* Has results */
            echo json_encode($response);
        } else if (isset($response['results'])) {
            /* No results */
            echo json_encode(array('results' => array()));
        } else {
            /* Had error */
            echo json_encode(array('error' => 'Meetup.com API Error', 'details' => $response));
        }
        //stop output
        exit;
    }

    /**
     * AJAX action "venue_events" callback
     */
    public function venue_events() {
        //Set the response to be JSON
        header('Content-type: application/json');
        $id = !empty($_POST['id']) ? $_POST['id'] : false;
        $response = MeetupCom::venueEvents($id, 'upcoming,past');
        if (!empty($response['results'])) {
            /* Has results */
            echo json_encode($response);
        } else if (isset($response['results'])) {
            /* No results */
            echo json_encode(array('results' => array()));
        } else {
            /* Had error */
            echo json_encode(array('error' => 'Meetup.com API Error', 'details' => $response));
        }
        //stop output
        exit;
    }

}
