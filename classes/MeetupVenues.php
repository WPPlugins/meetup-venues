<?php

class MeetupVenues {

    /**
     * Default date format
     * @var string
     */
    static $date_format = 'l F jS, Y g:ia';

    /**
     * Callback for the [meetup-venues] shortcode
     * @param array $attr
     * @return string
     */
    public function shortcode($attr = array()) {
        //Check for id(s)
        if (empty($attr['id'])) {
            return '<b>Meetup Venues:</b> Shortcode requires <u>id</u> attribute.';
        }

        //Check for date_format attribute
        if (!empty($attr['date_format'])) {
            //Override default date_format
            MeetupVenues::$date_format = $attr['date_format'];
        }

        //Default template
        $template = 'events_list';
        //Check for template attribute
        if (!empty($attr['template']) && is_file(MEETUP_VENUES_TEMPLATES . $attr['template'] . '.html')) {
            //Override default template
            $template = $attr['template'];
        }

        //Clean up id attribute
        $id = preg_replace('[^0-9,]', '', $attr['id']);

        //Make API request to Meetup.com
        $response = MeetupCom::venueEvents($id);

        //use API response
        if (!isset($response['results'])) {
            return '<b>Meetup Venues:</b> Meetup.com API Error';
        } else {
            return self::render($template, $response);
        }
    }

    /**
     * Render the LightnCandy template with context
     * @param string $template
     * @param array $context
     * @return string
     */
    private function render($template, $context) {
        //Get template html
        $template_html = file_get_contents(MEETUP_VENUES_TEMPLATES . $template . '.html');

        //Compile template
        try {
            $template_compiled = LightnCandy::compile($template_html, array(
                        'flags' => LightnCandy::FLAG_ERROR_EXCEPTION,
                        'helpers' => array(
                            'date_format' => function ($time, $utc_offset) {
                        return date(MeetupVenues::$date_format, ($time + $utc_offset) / 1000);
                    }
                        )
            ));
        } catch (Exception $e) {
            //There was a compile error
            if (current_user_can('manage_options')) {
                //is admin
                return $e->getMessage();
            } else {
                //is visitor
                return '<b>Meetup Venues:</b> Template error at compile.';
            }
        }

        //Prepare the compiled template
        $Template = LightnCandy::prepare($template_compiled);

        //Generate the HTML
        try {
            $html = $Template($context, LCRun3::DEBUG_ERROR_EXCEPTION);
        } catch (Exception $e) {
            //There was a template error
            if (current_user_can('manage_options')) {
                //is admin
                return $e->getMessage();
            } else {
                //is visitor
                return '<b>Meetup Venues:</b> Template error at HTML generation.';
            }
        }
        //return rendered HTML
        return $html;
    }

}
