<?php

class MeetupCom {

    static $base = 'http://api.meetup.com/';
    static $default_params = array('key' => false);

    /**
     * Search for venues within given geographic area
     * @param string $city
     * @param string $state
     * @param string $country
     * @param string $text
     * @return array
     */
    static function searchVenues($city, $state, $country, $text) {
        $data = array(
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'text' => $text,
            'order' => 'rating_count',
            'desc' => 'true'
        );
        return self::GET('2/open_venues', $data);
    }

    /**
     * Get events by venue_id
     * @param string $venue_id can be comma deliminated list of venue_ids
     * @param string $status comma deliminated list (upcoming|past|proposed|suggested|cancelled|draft)
     * @return array
     */
    static function venueEvents($venue_id, $status = 'upcoming') {
        $data = array(
            'venue_id' => $venue_id,
            'status' => $status,
            'time' => '0,'
        );
        return self::GET('2/events', $data);
    }

    /* CURL & REST properties and methods */

    static $ch;
    static $ch_opt = array(
        CURLOPT_RETURNTRANSFER => true
    );
    static $ch_errorno = false;
    static $ch_error = false;
    static $ch_headers = array();

    /**
     * Get the API URL with endpoint
     * @param string $endpoint
     * @param array $data query string
     * @return string
     */
    static function _url($endpoint, $data = false) {
        $url = self::$base . $endpoint . '.json' . '/';
        if ($data) {
            $url .= '?' . http_build_query($data);
        }
        return $url;
    }

    /**
     * HTTP GET
     * @param string $endpoint
     * @param array $data query string parameters
     * @param boolean $cache
     * @return array
     */
    static function GET($endpoint, $data = null, $cache = true) {
        //Overrite the default_params with $data
        $data = array_merge(self::$default_params, $data);
        //Get request URL with query string
        $url = self::_url($endpoint, $data);
        //Create unique key for caching
        $key = 'MeetupCom' . md5($url);
        //Read cache
        $response = get_transient($key);
        if (!$cache || $response === false) {
            /* response not cached, expired, or $cache is FALSE */
            //Make API request
            $response = self::_curl($url);
            //Cache response
            set_transient($key, $response, HOUR_IN_SECONDS);
        }
        //return JSON response
        return json_decode($response) ? json_decode($response, 1) : $response;
    }

    /**
     * HTTP POST
     * @param string $endpoint
     * @param array $data POST data
     * @return array
     */
    static function POST($endpoint, $data = null) {
        //Overrite the default_params with $data
        $data = array_merge(self::$default_params, $data);
        //Get request URL
        $url = self::_url($endpoint);
        //Make API request
        $response = self::_curl($url, $data);
        return json_decode($response) ? json_decode($response, 1) : $response;
    }

    static function _curl($url, $data = null) {
        //Reset MeetupCom::$ch_errorno AND MeetupCom::$ch_error
        self::$ch_errorno = false;
        self::$ch_error = false;
        //Initialize cURL
        self::$ch = curl_init($url);
        //If $data is not NULL, the request is a POST
        if ($data != null) {
            self::$ch_opt[CURLOPT_POST] = true;
            self::$ch_opt[CURLOPT_POSTFIELDS] = http_build_query($data);
            self::$ch_opt[CURLOPT_HEADERFUNCTION] = array(MeetupCom, '_header_callback');
        }
        //Set the cURL options from MeetupCom::$ch_opt array
        curl_setopt_array(self::$ch, self::$ch_opt);
        //Execute request and return response
        $response = curl_exec(self::$ch);
        //Check for error
        if (curl_errno(self::$ch)) {
            /* Has an error */
            self::$ch_errorno = curl_errno(self::$ch);
            self::$ch_error = curl_error(self::$ch);
        }
        //Close cURL
        curl_close(self::$ch);
        //Return the response
        return $response;
    }

    /**
     * @tutorial http://ontodevelopment.blogspot.com/2011/04/curloptheaderfunction-tutorial-with.html
     * @param handle $ch cURL handle
     * @param string $header_line
     * @return type
     */
    static function _header_callback($ch, $header_line) {
        list($header, $value) = explode(':', $header_line);
        $this->ch_headers[$header] = $value;
        return strlen($header_line);
    }

}
