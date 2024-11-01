<?php

//set defaults
add_option("user_beforeTweet", '<p>');
add_option("user_afterTweet", '</p>');
add_option("list_beforeTweet", '<p>');
add_option("list_afterTweet", '</p>');

add_option("yatp_show_avatars",'');

add_option("yatp_cache",'600');

add_action('admin_menu', 'yatp_options_page');

function yatp_options_page() {
 	add_options_page('YATP Options', 'YaTp', 'administrator', 'yatp', 'yatp_options');
	add_action( 'admin_init', 'yatp_reg' );
}


function yatp_reg() {
	register_setting( 'yatp_options', 'user_beforeTweet' );
	register_setting( 'yatp_options', 'user_afterTweet' );
	register_setting( 'yatp_options', 'list_beforeTweet' );
	register_setting( 'yatp_options', 'list_afterTweet' );
	register_setting( 'yatp_options', 'yatp_show_avatars' );
	register_setting( 'yatp_options', 'yatp_cache');

}

function yatp_options() {
?>
<div class="wrap">
<h2>Yet another Twitter plugin - settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'yatp_options' ); ?>
    <table class="form-table">

	<tr valign="top">
        <th scope="row"><h3>Single User Twitter Feed</h3><p><small>Template tag: yatp_user_feed("username",tweet limit (default 5))</small></p></th>
	</tr>
        <tr valign="top">
        <th scope="row">Before Tweet</th>
        <td><input type="text" name="user_beforeTweet" value="<?php echo get_option('user_beforeTweet'); ?>" />
	<p><small>Code you want before a tweet.</small></p></td>
        </tr>
        <tr valign="top">
        <th scope="row">After Tweet</th>
        <td><input type="text" name="user_afterTweet" value="<?php echo get_option('user_afterTweet'); ?>" /?
	<p><small>Code you want after a tweet.</small></p></td>
        </tr>
        

	<tr valign="top">
        <th scope="row"><h3>Twitter List Feed</h3><p><small>Template tag: yatp_list_feed("username","listname",tweet limit (default 5))</small></p></th>
	</tr>
        <tr valign="top">
        <th scope="row">Before Tweet</th>
        <td><input type="text" name="list_beforeTweet" value="<?php echo get_option('list_beforeTweet'); ?>" />
	<p><small>Code you want before a tweet.</small></p></td>
        </tr>
        <tr valign="top">
        <th scope="row">After Tweet</th>
        <td><input type="text" name="list_afterTweet" value="<?php echo get_option('list_afterTweet'); ?>" /?
	<p><small>Code you want after a tweet.</small></p></td>
        </tr>
		
	<tr valign="top">
        <th scope="row"><h3>Other Options</h3></th>
		<tr valign="top">
        <th scope="row">Display User Avatars</th>
		<?php if(get_option('yatp_show_avatars')=='1'){ ?>
        <td><input name="yatp_show_avatars" type="checkbox" id="yatp_show_avatars" value="1" checked="checked"/>
		<?php } else { ?>
		<td><input name="yatp_show_avatars" type="checkbox" id="yatp_show_avatars" value="1" />
		<?php } ?>
	<p><small>Show user avatar next to each tweet</small></p></td>
	</tr>
	<tr valign="top">
        <th scope="row">Feed Cache Duration</th>
        <td><input type="text" name="yatp_cache" value="<?php echo get_option('yatp_cache'); ?>" /?
	<p><small>Lifetime of cached twitter feed (in seconds). Default: 600 (10 Minutes)</small></p></td>
        </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } ?>