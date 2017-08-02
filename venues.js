
jQuery(document).ready(function($) {
    //Inputs
    $city = $('#city');
    $state = $('#state');
    $country = $('#country');
    $search = $('#search');

    //Other elements
    $searching = $('#searching');
    $search_results = $('#search_results');
    $search_results_list = $('#search_results_list');
    $search_no_results = $('#search_no_results');
    $search_span = $('#search_span');

    //Event Handling
    $('#submit').click(submit_venues_search);
    $('#wpbody').on('change', '.venue,input[type=radio]', update_shortcode);
});

function submit_venues_search() {
    if ($city.val().length < 2) {
        $city.select();
        return;
    }
    if ($state.val().length != 2) {
        $state.select();
        return;
    }
    venues_search($city.val(), $state.val(), $country.val(), $search.val());
}

function venues_search(city, state, country, search) {
    $ = jQuery;
    $search_span.text(search);
    $searching.show();
    $search_results.hide();
    $search_no_results.hide();
    $.ajax({
        url: ajaxurl,
        data: {
            action: 'meetup_venues_search',
            city: city,
            state: state,
            country: country,
            search: search
        },
        type: 'POST',
        dataType: 'json',
        success: function(json) {
            $searching.hide();
            if (json.error) {
                alert(json.error);
                return;
            }
            if (json.results == null || json.results.length < 1) {
                $search_no_results.show();
            } else if (json.results) {
                $search_results.show();
                $search_results_list.html('');
                for (var idx in json.results) {
                    var result = json.results[idx];
                    $checkbox = $('<input/>').attr({
                        type: 'checkbox',
                        value: result.id,
                        'class': 'venue'
                    });

                    $td_toggle = $('<td/>').append($checkbox);
                    $td_id = $('<td/>').html(result.id);
                    $td_name = $('<td/>').html(result.name);
                    $td_events = $('<td/>').html('<i>searching</i>').attr('id', 'events_' + result.id);
                    var address = result.address_1;
                    if (result.address_2 != undefined)
                        address += '<br/>' + result.address_2;
                    $td_address = $('<td/>').html(address);
                    $td_city = $('<td/>').html(result.city);
                    var className = (idx % 2 === 0) ? 'alternate' : '';
                    $tr = $('<tr/>')
                            .append($td_toggle, $td_id, $td_name, $td_events, $td_address, $td_city)
                            .addClass(className);
                    $search_results_list.append($tr);
                    get_events(result.id);
                }
            }
        },
        error: function(e1, e2, e3) {
            console.log(e1, e2, e3);
        }
    });
}

function update_shortcode() {
    $ = jQuery;
    /* IDs */
    var list = [];
    $('.venue:checked').each(function() {
        list.push($(this).val());
    });
    var id = list.join(',');
    var attrs = '';
    /* TEMPLATE */
    var template = $('.template:checked').val();
    if (template !== 'events_list') {
        attrs += ' template="' + template + '"';
    }
    /* Date/Time format */
    var date_format = $('.date_format:checked').val();
    if (date_format !== '') {
        attrs += ' date_format="' + date_format + '"';
    }
    /* build shortcode */
    var shortcode = '[meetup-venues id="' + id + '"' + attrs + ']';
    $('#shortcode').val(shortcode);
}

function get_events(venue_id) {
    $ = jQuery;
    $.ajax({
        url: ajaxurl,
        data: {
            action: 'meetup_venue_events',
            id: venue_id
        },
        venue_id: venue_id,
        type: 'POST',
        dateType: 'json',
        success: function(json) {
            var count = (json.results && json.results.length > 0) ? json.results.length : '-';
            $('#events_' + this.venue_id).html(count);
        }
    });
}