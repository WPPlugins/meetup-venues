<form class="wrap" id="meetup-venues" onsubmit="return false" autocomplete="off">
    <div class="meetup-venues-row">
        <div class="meetup-venues-col-left">
            <div class="meetup-venues-box">
                <h3 class="meetup-venues-box-header">Templates</h3>
                <div class="meetup-venues-box-body">
                    <ul>
                        <?php
                        foreach (scandir(MEETUP_VENUES_TEMPLATES) as $file) {
                            if (substr($file, -4) == 'html') {
                                $template = pathinfo($file, PATHINFO_FILENAME);
                                $default = ($template == 'events_list');
                                echo '<li><label>';
                                $checked = ($default) ? 'checked' : '';
                                echo "<input type='radio' name='template' class='template' value='{$template}' {$checked} />";
                                echo $file;
                                if ($default) {
                                    echo ' <i>(default)</i>';
                                }
                                echo '</label></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <h3 class="meetup-venues-box-header">Time Format</h3>
                <div class="meetup-venues-box-body">
                    <ul>
                        <li>
                            <label>
                                <input type='radio' name='date_format' class='date_format' value='' checked /> <?php echo date('l F jS, Y g:ia') ?> <i>(default)</i>
                            </label>
                        </li>

                        <li>
                            <label>
                                <input type='radio' name='date_format' class='date_format' value='Y-m-d g:ia' /> <?php echo date('Y-m-d g:ia') ?>
                            </label>
                        </li>

                        <li>
                            <label>
                                <input type='radio' name='date_format' class='date_format' value='m/d/Y g:ia' /> <?php echo date('m/d/Y g:ia') ?>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="meetup-venues-col-right">
            <div class="meetup-venues-box">
                <h3 class="meetup-venues-box-header">Venue Search</h3>
                <div class="meetup-venues-box-body">
                    <table id="meetup-venues-search-table">
                        <tbody>
                            <tr>
                                <th class="row">
                                    <label for="city">City, State & Country</label> <small>(required)</small>
                                </th>
                                <td>
                                    <input id="city" class="regular-text" type="text" placeholder="City Name"/>,
                                    <input id="state" class="regular-text" type="text" placeholder='State "CA"' maxlength="2"/>
                                    <input id="country" class="regular-text" type="text" placeholder='Country "US"' value="US" maxlength="2"/>
                                    <p class="description">Needed for narrowing down the results.</p>
                                </td>
                            </tr>
                            <tr>
                                <th class="row">
                                    <label for="search">Search Venues</label>
                                </th>
                                <td>
                                    <input id="search" class="regular-text" type="text" placeholder="Search by venue name" />
                                    <p><input id="submit" class="button button-primary" type="button" value="Search" name="submit"><p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="searching" style="display: none" class="meetup-venues-box-body">
                    <p>Searching Venues...</p>
                </div>

                <div id="search_results" style="display: none">
                    <h3 class="meetup-venues-box-header">Search Results</h3>
                    <div  class="meetup-venues-box-body">
                        <table class="wp-list-table widefat fixed">
                            <thead>
                                <tr>
                                    <th style="width:40px;"></th>
                                    <th style="width:100px">ID</th>
                                    <th style="width:300px">Venue Name</th>
                                    <th style="width:100px">Events</th>
                                    <th>Address</th>
                                    <th style="width:200px">City</th>
                                </tr>
                            </thead>
                            <tbody id="search_results_list">

                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="search_no_results" style="display: none">
                    <h3 style='text-align: center' class="meetup-venues-box-header">No Results</h3>
                    <div class="meetup-venues-box-body">
                        No venues found matching "<span id="search_span"></span>"
                    </div>
                </div>

                <h3 class="meetup-venues-box-header">Shortcode Result</h3>
                <div class="meetup-venues-box-body">
                    <p><input type='text' id='shortcode' value='[meetup-venues id=""]' onfocus='this.select()' /></p>
                    <p class="description">Copy the generated shortcode where ever shortcodes are supported in your theme.</p>
                </div>
            </div>
        </div>

        <div class="clear clearfix"></div>
    </div>
</form>

<script><?php include_once __DIR__ . '/venues.js' ?></script>
<style><?php include_once __DIR__ . '/venues.css' ?></style>

<style>
    #shortcode { width: 100%; }
    #meetup-venues-search-table {
        width: 100%;
    }
    #meetup-venues-search-table th, #meetup-venues-search-table td {
        vertical-align: top;
    }
    #meetup-venues-search-table .row { width: 200px; }
    #meetup-venues-settings .post-body {
        background: none repeat scroll 0 0 #FFFFFF;
        border-bottom: 1px solid #DFDFDF;
        border-top: 1px solid #FFFFFF;
        padding: 0 10px 10px;
    }
    #city { width: 10em; }
    #state, #country { width: 7em; }
</style>