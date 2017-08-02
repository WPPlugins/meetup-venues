<?php
if(!empty($_POST['api_key'])){
    $this->api_key = (String) $_POST['api_key'];
    update_option('meetup_api_key', $this->api_key);
}
?>
<form method="post">
    <table class="form-table">
        <tbody>
            <tr>
                <th class="row">
                    <label for="api_key">Meetup.com API Key</label>
                </th>
                <td>
                    <input id="api_key" class="regular-text" type="text" value="<?php echo $this->api_key ?>" name="api_key">
                    <p class="description"><a href="https://secure.meetup.com/meetup_api/key/">https://secure.meetup.com/meetup_api/key/</a></p>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input id="submit" class="button button-primary" type="submit" value="Save Changes" name="submit">
    </p>
</form>