<?php
/*
Plugin Name: MailChimp
Plugin URI: http://www.mailchimp.com/plugins/mailchimp-wordpress-plugin/
Description: The MailChimp plugin allows you to easily setup a Subscribe box for your MailChimp list - So easy a chimp could do it!
Version: 1.1.8
Author: MailChimp API Support Team
Author URI: http://mailchimp.com/api/
*/

/*  Copyright 2008  MailChimp.com  (email : api@mailchimp.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if (!class_exists('MCAPI')) {
	require_once( str_replace('//','/',dirname(__FILE__).'/') .'MCAPI.class.php');
}
// includes the widget code so it can be easily called either normally or via ajax
include_once('mailchimp_includes.php');
// some custom CSS//
function mailchimpSF_admin_css() {
echo "
<style type='text/css'>
.error_msg { color: red; }
.success_msg { color: green; }
</style>
";
}
function mailchimpSF_main_css() {
echo "
<style type='text/css'>
.mc_error_msg { color: red; }
.mc_success_msg { color: green; }
.mc_merge_var{ padding:0; margin:0; }
.mc_custom_border{ padding:5px; ";
if (get_option('mc_custom_style')=='on'){
	echo "border-width: ".get_option('mc_form_border_width').'px;';
	if (get_option('mc_form_border_width')==0){
    	echo "border-style: none;";
	} else {
    	echo "border-style: solid;";
    }
	echo "border-color: #".get_option('mc_form_border_color').';';
	echo "color: #".get_option('mc_form_text_color').';';
	echo "background-color: #".get_option('mc_form_background').';';
}
echo "
}
.mc_custom_border legend {";
if (get_option('mc_custom_style')=='on'){
	echo "border-width: ".get_option('mc_header_border_width').'px;';
	if (get_option('mc_header_border_width')==0){
    	echo "border-style: none;";
	} else {
    	echo "border-style: solid;";
    }
	echo "border-color: #".get_option('mc_header_border_color').';';
	echo "color: #".get_option('mc_header_text_color').';';
	echo "background-color: #".get_option('mc_header_background').';';
  	echo "font-size: 1.2em;padding:5px 10px;";
}
echo "
}
#mc_signup_form .mc_var_label, #mc_signup_form .mc_input { float:left; clear:both; }
#mc_signup_form legend { padding:.5em;margin:0; }
#mc-indicates-required { float:left; clear:both; }
#mc_display_rewards { clear:both; }
#mc_interests_header { font-weight:bold; clear:both; padding-top:.2em;}
div.mc_interest{ clear:both;}
input.mc_interest { float:left; }
label.mc_interest_label {float:left; clear:right;}
.mc_signup_submit { width:100%; text-align:center; clear:both; padding:.2em; }
</style>
<!--[if IE]>
<style type='text/css'>
#mc_signup_form fieldset { position: relative; }
#mc_signup_form legend {
padding:.3em;
position: absolute;
top: -1em;
left: .2em;
}
#mc_message { padding-top:1em; }
</style>
<![endif]--> 
";
}//mc_main_css

// some javascript to get ajax version submitting to the proper location
function mailchimpSF_main_js(){
    $url = get_bloginfo( 'wpurl' ).'/wp-content/plugins/mailchimp/mailchimp_ajax.php';
    echo '<script type="text/javascript">
var mc_ajax_url = "'.$url.'";
</script>';
}


// Hook for initializing the plugins, mostly for i18n
add_action( 'init', 'mailchimpSF_plugin_init' );

// Hook for our css
add_action('admin_head', 'mailchimpSF_admin_css');
add_action('admin_head', 'mailchimpSF_main_js'); //just to stop js error
add_action('wp_head', 'mailchimpSF_main_css');
add_action('wp_head', 'mailchimpSF_main_js');

// Hook for adding admin menus
add_action('admin_menu', 'mailchimpSF_add_pages');

// Initialize this plugin. Called by 'init' hook.
function mailchimpSF_plugin_init(){
  //the "complicated" path is so people can use this with WPMU or just move things around
  load_plugin_textdomain( 'mailchimp_i18n', str_replace(ABSPATH,'',dirname(__FILE__).'/po') );
  if (get_option('mc_use_javascript')=='on'){
      wp_enqueue_script( 'mcJavascript', get_bloginfo('wpurl').'/wp-content/plugins/mailchimp/js/mailchimp.js', array('jquery', 'jquery-form'));  
  }
  
}

// action function for above hook
function mailchimpSF_add_pages(){
	//7 is lowest w/ Plugin Editing capability
	add_options_page( __( 'MailChimp Setup', 'mailchimp_i18n' ), __( 'MailChimp Setup', 'mailchimp_i18n' ), 7, 'mailchimpSF_setup_page', 'mailchimpSF_setup_page');  
}

function mailchimpSF_setup_page(){

$msg = '';
if (get_option('mc_password')!=''){
    //some upgrade code for < 0.5 users - we want to rip out the password we've been saving.
    $api = new MCAPI(get_option('mc_username'), get_option('mc_password'));
  	if ($api->errorCode == ''){
        update_option('mc_apikey', $api->api_key);
        //this should already be here, but let's make sure anyway
		$req = $api->getAffiliateInfo();
		update_option('mc_user_id', $req['user_id']);
    } else {
        $msg = "<span class='error_msg'>".__( "While upgrading the plugin setup, we were unable to login to your account. You will need to login again and setup your list.", 'mailchimp_i18n' )."<br/>";
    }
    delete_option('mc_password');
}
?>
<div class="wrap">
<h2><?php echo __( 'MailChimp List Setup', 'mailchimp_i18n');?> </h2>
<?php
if ($_REQUEST['action']==='logout'){
    update_option('mc_apikey', '');
}
//see if we need to set/change the username & password.
if (isset($_REQUEST['mc_username']) && isset($_REQUEST['mc_password'])){
	$delete_setup = false;
	$api = new MCAPI($_REQUEST['mc_username'], $_REQUEST['mc_password']);
	if ($api->errorCode == ''){
		$msg = "<span class='success_msg'>".htmlentities(__( "Success! We were able to verify your username & password! Let's continue, shall we?", 'mailchimp_i18n' ))."</span>";
		update_option('mc_username', $_REQUEST['mc_username']);
		update_option('mc_apikey', $api->api_key);
		$req = $api->getAffiliateInfo();
		update_option('mc_user_id', $req['user_id']);
		if (get_option('mc_list_id')!=''){
			$lists = $api->lists();
			//but don't delete if the list still exists...
            $delete_setup = true;
			foreach($lists as $list){ if ($list['id']==get_option('mc_list_id')){ $list_id = $_REQUEST['mc_list_id']; $delete_setup=false; } }
		}
	} else {
		$msg .= "<span class='error_msg'>".htmlentities(__( 'Uh-oh, we were unable to login and verify your username & password. Please check them and try again!', 'mailchimp_i18n' ))."<br/>";
		$msg .= __( 'The server said:', 'mailchimp_i18n' )."<i>".$api->errorMessage."</i></span>";
		if (get_option('mc_username')==''){
			$delete_setup = true;
		}
	}
	if ($delete_setup){
		delete_option('mc_user_id');
		delete_option('mc_rewards');
		delete_option('mc_use_javascript');
		delete_option('mc_use_unsub_link');
		delete_option('mc_list_id');
		delete_option('mc_list_name');
		delete_option('mc_interest_groups');
		delete_option('mc_show_interest_groups');
		$mv = get_option('mc_merge_vars');
		if (!is_array($mv)){
		    $mv = unserialize($mv);
		}
		if (is_array($mv)){
	        foreach($mv as $var){
		        $opt = 'mc_mv_'.$var['tag'];
		        delete_option($opt);
	        }
	    }
		delete_option('mc_merge_vars');
	}
	//set these for the form fields below
	$user = $_REQUEST['mc_username'];
} else {
	$user = get_option('mc_username');
	$pass = get_option('mc_password');
}
if (get_option('mc_apikey')!=''){
    $GLOBALS["mc_api_key"] = get_option('mc_apikey');
	$api = new MCAPI('no_login','is_needed');
	$lists = $api->lists();
	
	foreach($lists as $list){ if ($list['id']==$_REQUEST['mc_list_id']){ $list_id = $_REQUEST['mc_list_id']; $list_name = $list['name']; } }
	$orig_list = get_option('mc_list_id');
	if ($list_id != ''){
        update_option('mc_list_id', $list_id);
	    update_option('mc_list_name', $list_name);
        if ($orig_list != $list_id){
	        update_option('mc_header_content',__( 'Sign up for', 'mailchimp_i18n' ).' '.$list_name);
	        update_option('mc_submit_text',__( 'Subscribe', 'mailchimp_i18n' ));

	        update_option('mc_custom_style','on');
	        update_option('mc_use_javascript','on');
	        update_option('mc_use_unsub_link','off');
	        update_option('mc_header_border_width','1');
	        update_option('mc_header_border_color','E3E3E3');
	        update_option('mc_header_background','FFFFFF');
	        update_option('mc_header_text_color','CC6600');
	        
	        update_option('mc_form_border_width','1');
	        update_option('mc_form_border_color','C4D3EA');
	        update_option('mc_form_background','EEF3F8');
	        update_option('mc_form_text_color','555555');
	        
    	    update_option('mc_show_interest_groups', 'on' );
        }
	    $mv = $api->listMergeVars($list_id);
	    $ig = $api->listInterestGroups($list_id);
	    update_option('mc_merge_vars', serialize( $mv ) );
	    foreach($mv as $var){
		    $opt = 'mc_mv_'.$var['tag'];
		    //turn them all on by default
		    if ($orig_list != $list_id){
    		    update_option($opt, 'on' );
    		}
	    }
	    update_option('mc_interest_groups', serialize( $ig ) );

	    $msg = '<span class="success_msg">'.
	        sprintf(__( 'Success! Loaded and saved the info for %d Merge Variables and %d Interest Groups from your list'),
        	            sizeof($mv) , sizeof($ig) ).
	        ' "'.$list_name.'"<br/><br/>'.
		    __('Now you should either Turn On the MailChimp Widget or change your options below, then turn it on.', 'mailchimp_i18n').'</span>';
    }

}
if (isset($_REQUEST['reset_list'])){
	delete_option('mc_list_id');
	delete_option('mc_list_name');
	delete_option('mc_merge_vars');
	delete_option('mc_interest_groups');

	delete_option('mc_use_javascript');
	delete_option('mc_use_unsub_link');
	
	delete_option('mc_header_content');
	delete_option('mc_submit_text');

	delete_option('mc_custom_style');

	delete_option('mc_header_border_width');
	delete_option('mc_header_border_color');
	delete_option('mc_header_background');
	delete_option('mc_header_text_color');

	delete_option('mc_form_border_width');
	delete_option('mc_form_border_color');
	delete_option('mc_form_background');
	delete_option('mc_form_text_color');

	$msg = '<span class="success_msg">'.__('Successfully Reset your List selection... Now you get to pick again!', 'mailchimp_i18n').'</span>';
}
if (isset($_REQUEST['change_form_settings'])){
	if (isset($_REQUEST['mc_rewards'])){
		update_option('mc_rewards', 'on');
		if ($msg) $msg .= '<br/>';
		$msg .= '<span class="success_msg">'.__('Monkey Rewards turned On!', 'mailchimp_i18n').'</span>';
	} else if (get_option('mc_rewards')!='off') {
		update_option('mc_rewards', 'off');
		if ($msg) $msg .= '<br/>';
		$msg .= '<span class="success_msg">'.__('Monkey Rewards turned Off!', 'mailchimp_i18n').'</span>';
	}
	if (isset($_REQUEST['mc_use_javascript'])){
		update_option('mc_use_javascript', 'on');
		if ($msg) $msg .= '<br/>';
		$msg .= '<span class="success_msg">'.__('Fancy Javascript submission turned On!', 'mailchimp_i18n').'</span>';
	} else if (get_option('mc_use_javascript')!='off') {
		update_option('mc_use_javascript', 'off');
		if ($msg) $msg .= '<br/>';
		$msg .= '<span class="success_msg">'.__('Fancy Javascript submission turned Off!', 'mailchimp_i18n').'</span>';
	}
	
	if (isset($_REQUEST['mc_use_unsub_link'])){
		update_option('mc_use_unsub_link', 'on');
		if ($msg) $msg .= '<br/>';
		$msg .= '<span class="success_msg">'.__('Unsubscribe link turned On!', 'mailchimp_i18n').'</span>';
	} else if (get_option('mc_use_unsub_link')!='off') {
		update_option('mc_use_unsub_link', 'off');
		if ($msg) $msg .= '<br/>';
		$msg .= '<span class="success_msg">'.__('Unsubscribe link turned Off!', 'mailchimp_i18n').'</span>';
	}

	$content = stripslashes($_REQUEST['mc_header_content']);
	$content = str_replace("\r\n","<br/>", $content);
	update_option('mc_header_content', $content );

	$submit_text = stripslashes($_REQUEST['mc_submit_text']);
	$submit_text = str_replace("\r\n","", $submit_text);
	update_option('mc_submit_text', $submit_text);

	if (isset($_REQUEST['mc_custom_style'])){
		update_option('mc_custom_style','on');
	} else {
		update_option('mc_custom_style','off');
	}

	//we told them not to put these things we are replacing in, but let's just make sure they are listening...
	update_option('mc_header_border_width',str_replace('px','',$_REQUEST['mc_header_border_width']) );
	update_option('mc_header_border_color', str_replace('#','',$_REQUEST['mc_header_border_color']));
	update_option('mc_header_background',str_replace('#','',$_REQUEST['mc_header_background']));
	update_option('mc_header_text_color', str_replace('#','',$_REQUEST['mc_header_text_color']));

	update_option('mc_form_border_width',str_replace('px','',$_REQUEST['mc_form_border_width']) );
	update_option('mc_form_border_color', str_replace('#','',$_REQUEST['mc_form_border_color']));
	update_option('mc_form_background',str_replace('#','',$_REQUEST['mc_form_background']));
	update_option('mc_form_text_color', str_replace('#','',$_REQUEST['mc_form_text_color']));

	if (isset($_REQUEST['mc_show_interest_groups'])){
		update_option('mc_show_interest_groups','on');
	} else {
		update_option('mc_show_interest_groups','off');
	}
	$mv = get_option('mc_merge_vars');
	if (!is_array($mv)){ 
	    $mv = unserialize(get_option('mc_merge_vars'));
	}
	foreach($mv as $var){
		$opt = 'mc_mv_'.$var['tag'];
		if (isset($_REQUEST[$opt]) || $var['req']=='Y'){
			update_option($opt,'on');
		} else {
			update_option($opt,'off');
		}
	}
    if ($msg) $msg .= '<br/>';
	$msg .= '<span class="success_msg">'.__('Successfully Updated your List Subscribe Form Settings!', 'mailchimp_i18n').'</span>';

}
if ($msg){
    echo '<div id="mc_message" class=""><p><strong>'.$msg.'</strong></p></div>';
}
?>
<?php 
//wp_nonce_field('update-options'); 
if (get_option('mc_apikey')==''){
?>
<div>
<form method="post" action="options-general.php?page=mailchimpSF_setup_page">
<h3><?php echo __('Login Info', 'mailchimp_i18n');?></h3>
<?php echo __('To start using the MailChimp plugin, we first need to login and get your API Key. Please enter your MailChimp username and password below.', 'mailchimp_i18n'); ?>
<br/>
<?php echo __("Don't have a MailChimp account? <a href='http://www.mailchimp.com/tryit.phtml' target='_blank'>Try one for Free</a>!", 'mailchimp_i18n'); ?>
<br/>
<table class="form-table">
<tr valign="top">
<th scope="row"><?php echo __('Username', 'mailchimp_i18n');?>:</th>
<td><input name="mc_username" type="text" id="mc_username" class="code" value="<?php echo $user; ?>" size="20" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php echo __('Password', 'mailchimp_i18n');?>:</th>
<td><input name="mc_password" type="text" id="mc_password" class="code" value="<?php echo $pass; ?>" size="20" /></td>
</tr>
</table>
<input type="hidden" name="action" value="update"/>
<input type="hidden" name="page_options" value="mc_username,mc_password" />
<input type="submit" name="Submit" value="<?php echo htmlentities(__('Save & Check'));?>" class="button" />
</form>
</div>
<?php 
    if (get_option('mc_username')!=''){
	    echo '<strong>'.__('Notes', 'mailchimp_i18n').':</strong><ul>
		    <li><i>'.__('Changing your settings at MailChimp.com may cause this to stop working.', 'mailchimp_i18n').'</i></li>
		    <li><i>'.__('If you change your login to a different account, the info you have setup below will be erased.', 'mailchimp_i18n').'</i></li>
		    <li><i>'.__('If any of that happens, no biggie - just reconfigure your login and the items below...', 'mailchimp_i18n').'</i></li></ul>
	    <br/>';
    }
echo '</p>';
} else {
?>
<table style="min-width:400px;"><tr><td><h3><?php echo __('Logged in as', 'mailchimp_i18n');?>: <?php echo get_option('mc_username')?></h3>
</td><td>
<form method="post" action="options-general.php?page=mailchimpSF_setup_page">
<input type="hidden" name="action" value="logout"/>
<input type="submit" name="Submit" value="<?php echo __('Logout', 'mailchimp_i18n');?>" class="button" />
</form>
</td></tr></table>
<?php
}
?>
<?php
//Just get out if nothing else matters...
if (get_option('mc_apikey') == '') return;

if (get_option('mc_apikey')!=''){
?>
<?php 
//wp_nonce_field('update-options'); ?>
<h3><?php echo __('Your Lists', 'mailchimp_i18n')?></h3>
<div>
<?php echo __('Please select the List you wish to create a Signup Form for.', 'mailchimp_i18n');?><br/>
<form method="post" action="options-general.php?page=mailchimpSF_setup_page">
<?php
    $GLOBALS["mc_api_key"] = get_option('mc_apikey');
	$api = new MCAPI('no_login','is_needed');
	$lists = $api->lists();
	rsort($lists);
	if (sizeof($lists)==0){
		echo "<span class='error_msg'>".
		       sprintf(__("Uh-oh, you don't have any lists defined! Please visit %s, login, and setup a list before using this tool!", 'mailchimp_i18n'),
                    "<a href='http://www.mailchimp.com/'>MailChimp</a>")."</span>";
	} else {
	    echo '<table style="min-width:400px"><tr><td>
    	    <select name="mc_list_id" style="min-width:200px;">
            <option value=""> --- '.__('Select A List','mailchimp_i18n').' --- </option>';
	    foreach ($lists as $list){
	        if ($list['id'] == get_option('mc_list_id')){
	            $sel = ' selected="selected" ';
	        } else {
	            $sel = '';
	        }
		    echo '<option value="'.$list['id'].'" '.$sel.'>'.htmlentities($list['name']).'</option>';
	    }
?>
</select></td><td>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="mc_list_id" />
<input type="submit" name="Submit" value="<?php echo __('Update List', 'mailchimp_i18n');?>" class="button" />
</td></tr>
<tr><td colspan="2">
<strong><?php echo __('Note:', 'mailchimp_i18n');?></strong> <em><?php echo __('Updating your list will not cause settings below to be lost. Changing to a new list will.', 'mailchimp_i18n');?></em>
</td></tr>
</table>
</form>
</div>
<br/>
<?php
    } //end select list
} else {
//display the selected list...
?>

<?php 
//wp_nonce_field('update-options'); ?>
<p class="submit">
<form method="post" action="options-general.php?page=mailchimpSF_setup_page">
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="mc_list_id" />
<input type="submit" name="reset_list" value="<?php echo __('Reset List Options and Select again', 'mailchimp_i18n');?>" class="button" />
</form>
</p>
<h3><?php echo __('Subscribe Form Widget Settings for this List', 'mailchimp_i18n');?>:</h3>
<h4><?php echo __('Selected MailChimp List', 'mailchimp_i18n');?>: <?php echo get_option('mc_list_name'); ?></h4>
<?php
}
//Just get out if nothing else matters...
if (get_option('mc_list_id') == '') return;
$mv = get_option('mc_merge_vars');
$ig = get_option('mc_interest_groups');
if (!is_array($mv)){
    //apparently in WP 2.6 get_option() unserializes arrays for us.
    $mv = unserialize($mv);
    $ig = unserialize($ig);
}
if (!is_array($ig)){
    //means we got false returned
   $ig = unserialize($ig);
}
?>

<div>
<form method="post" action="options-general.php?page=mailchimpSF_setup_page">
<div style="width:600px;">
<input type="submit" name="change_form_settings" value="<?php echo __('Update Subscribe Form Settings', 'mailchimp_i18n');?>" class="button" />
<table class="widefat">
    <tr valign="top">
    <th scope="row"><?php echo __('Monkey Rewards', 'mailchimp_i18n');?>:</th>
    <td><input name="mc_rewards" type="checkbox" <?php if (get_option('mc_rewards')=='on' || get_option('mc_rewards')=='' ) {echo 'checked="checked"';} ?> id="mc_rewards" class="code" />
    <i><label for="mc_rewards"><?php echo __('turning this on will place a "powered by MailChimp" link in your form that will earn you credits with us. It is optional and can be turned on or off at any time.');?></label></i>
    </td>
    </tr>
    <tr valign="top">
    <th scope="row"><?php echo __('Use Javascript Support?', 'mailchimp_i18n');?>:</th>
    <td><input name="mc_use_javascript" type="checkbox" <?php if (get_option('mc_use_javascript')=='on' ) {echo 'checked="checked"';} ?> id="mc_use_javascript" class="code" />
    <i><label for="mc_use_javascript"><?php echo __('turning this on will use fancy javascript submission and should degrade gracefully for users not using javascript. It is optional and can be turned on or off at any time.');?></label></i>
    </td>
    </tr>
    <tr valign="top">
    <th scope="row"><?php echo __('Include Unsubscribe link?', 'mailchimp_i18n');?>:</th>
    <td><input name="mc_use_unsub_link" type="checkbox" <?php if (get_option('mc_use_unsub_link')=='on' ) {echo 'checked="checked"';} ?> id="mc_use_unsub_link" class="code" />
    <i><label for="mc_use_unsub_link"><?php echo __('turning this on will add a link to your host unsubscribe form');?></label></i>
    </td>
    </tr>
    <tr valign="top">
	<th scope="row"><?php echo __('Header content', 'mailchimp_i18n');?>:</th>
	<td>
	<textarea name="mc_header_content" rows="2" cols="50"><?php echo htmlentities(get_option('mc_header_content'));?></textarea><br/>
	<i><?php echo __('You can fill this with your own Text, HTML markup (including image links), or Nothing!', 'mailchimp_i18n');?></i>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php echo __('Submit Button text', 'mailchimp_i18n');?>:</th>
	<td>
	<input type="text" name="mc_submit_text" size="30" value="<?php echo get_option('mc_submit_text');?>"/>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php echo __('Custom Styling', 'mailchimp_i18n');?>:</th>
	<td>
	<table class="widefat">

		<tr><th><label for="mc_custom_style"><?php echo __('Turned On?', 'mailchimp_i18n');?></label></th><td><input type="checkbox" name="mc_custom_style" id="mc_custom_style" <?php if (get_option('mc_custom_style')=='on'){echo 'checked="checked"';}?> /></td></tr>
        <tr><th colspan="2"><?php echo __('Header Settings (only applies if there are no HTML tags in the Header Content area above)', 'mailchimp_i18n');?>:</th></tr>
		<tr><th><?php echo __('Border Width', 'mailchimp_i18n');?>:</th><td><input type="text" name="mc_header_border_width" size="3" maxlength="3" value="<?php echo get_option('mc_header_border_width');?>"/> px<br/>
			<i><?php echo __('Set to 0 for no border, do not enter <strong>px</strong>!', 'mailchimp_i18n');?></i>
		</td></tr>
		<tr><th><?php echo __('Border Color', 'mailchimp_i18n');?>:</th><td>#<input type="text" name="mc_header_border_color" size="7" maxlength="6" value="<?php echo get_option('mc_header_border_color');?>"/><br/>
			<i><?php echo __('do not enter initial <strong>#</strong>', 'mailchimp_i18n');?></i>
		</td></tr>
		<tr><th>Text Color:</th><td>#<input type="text" name="mc_header_text_color" size="7" maxlength="6" value="<?php echo get_option('mc_header_text_color');?>"/><br/>
			<i><?php echo __('do not enter initial <strong>#</strong>', 'mailchimp_i18n');?></i>
		</td></tr>
		<tr><th>Background Color:</th><td>#<input type="text" name="mc_header_background" size="7" maxlength="6" value="<?php echo get_option('mc_header_background');?>"/><br/>
			<i><?php echo __('do not enter initial <strong>#</strong>', 'mailchimp_i18n');?></i>
		</td></tr>
		
        <tr><th colspan="2"><?php echo __('Form Settings', 'mailchimp_i18n');?>:</th></tr>
		<tr><th><?php echo __('Border Width', 'mailchimp_i18n');?>:</th><td><input type="text" name="mc_form_border_width" size="3" maxlength="3" value="<?php echo get_option('mc_form_border_width');?>"/> px<br/>
			<i><?php echo __('Set to 0 for no border, do not enter <strong>px</strong>!', 'mailchimp_i18n');?></i>
		</td></tr>
		<tr><th><?php echo __('Border Color', 'mailchimp_i18n');?>:</th><td>#<input type="text" name="mc_form_border_color" size="7" maxlength="6" value="<?php echo get_option('mc_form_border_color');?>"/><br/>
			<i><?php echo __('do not enter initial <strong>#</strong>', 'mailchimp_i18n');?></i>
		</td></tr>
		<tr><th>Text Color:</th><td>#<input type="text" name="mc_form_text_color" size="7" maxlength="6" value="<?php echo get_option('mc_form_text_color');?>"/><br/>
			<i><?php echo __('do not enter initial <strong>#</strong>', 'mailchimp_i18n');?></i>
		</td></tr>
		<tr><th>Background Color:</th><td>#<input type="text" name="mc_form_background" size="7" maxlength="6" value="<?php echo get_option('mc_form_background');?>"/><br/>
			<i><?php echo __('do not enter initial <strong>#</strong>', 'mailchimp_i18n');?></i>
		</td></tr>
	</table>
</td>
</tr>
</table>
</div>
<input type="submit" name="change_form_settings" value="<?php echo __('Update Subscribe Form Settings', 'mailchimp_i18n');?>" class="button" />
<div style="width:400px;">
<h4><?php echo __('Merge Variables Included', 'mailchimp_i18n');?></h4>
<?php
if (sizeof($mv)==0 || !is_array($mv)){
	echo "<i>".__('No Merge Variables found.', 'mailchimp_i18n')."</i>";
} else {
	?>
	
	<table class='widefat'>
	<tr valign="top">
	<th><?php echo __('Name', 'mailchimp_i18n');?></th>
	<th><?php echo __('Tag', 'mailchimp_i18n');?></th>
	<th><?php echo __('Required?', 'mailchimp_i18n');?></th>
	<th><?php echo __('Include?', 'mailchimp_i18n');?></th>
	</tr>
	<?php
	foreach($mv as $var){
		echo '<tr valign="top">
			<td>'.htmlentities($var['name']).'</td>
			<td>'.$var['tag'].'</td>
			<td>'.($var['req']==1?'Y':'N').'</td><td>';
		if (!$var['req']){
			$opt = 'mc_mv_'.$var['tag'];
			echo '<input name="'.$opt.'" type="checkbox" ';
			if (get_option($opt)=='on') { echo ' checked="checked" '; }
			echo 'id="'.$opt.'" class="code" />';
		} else {
			echo ' - ';
		}
		echo '</td></tr>';
	}
	echo '</table>';
}
echo '<h4>'.__('Interest Groups', 'mailchimp_i18n').'</h4>';
if (!$ig || $ig=='' || $ig=='N'){
	echo "<i>".__('No Interest Groups Setup for this List', 'mailchimp_i18n')."</i>";
} else {
	?>
	<table class='widefat'>
	<tr valign="top">
	<th width="75px"><label for="mc_show_interest_groups"><?php echo __('Show?', 'mailchimp_i18n');?></label></th><th>
	<input name="mc_show_interest_groups" id="mc_show_interest_groups" type="checkbox" <?php if (get_option('mc_show_interest_groups')=='on') { echo 'checked="checked"'; } ?> id="mc_show_interest_groups" class="code" />
	</th></tr>
	<tr valign="top">
	<th><?php echo __('Name', 'mailchimp_i18n');?>:</th><th><?php echo $ig['name']; ?></th>
	</tr>
	<tr valign="top">
	<th><?php echo __('Input Type', 'mailchimp_i18n');?>:</th><td><?php echo $ig['form_field']; ?></td>
	</tr>
	<tr valign="top">
	<th><?php echo __('Options', 'mailchimp_i18n');?>:</th><td><ul>
	<?php
	foreach($ig['groups'] as $interest){
		echo '<li>'.htmlentities($interest);
	}
	echo '</ul></td></tr></table>';
}
?>
<p class="submit">
<input type="hidden" name="action" value="update" />
<input type="submit" name="change_form_settings" value="<?php echo __('Update Subscribe Form Settings', 'mailchimp_i18n');?>" class="button" />
</p>
</div>
</form>
</div>
</div><!--wrap-->
<?php
}//mailchimpSF_setup_page()


add_action('plugins_loaded', 'mailchimpSF_register_widgets');
function mailchimpSF_register_widgets(){

	if (!function_exists('register_sidebar_widget')) {
		return;
	}
	register_sidebar_widget( 'MailChimp Widget', 'mailchimpSF_display_widget');
}

function mailchimpSF_shortcode($atts){
	mailchimpSF_display_widget();
}
add_shortcode('mailchimpsf_widget', 'mailchimpSF_shortcode');


?>
