<?php
/*
Plugin Name: QuickVouch
Plugin URI: http://quickvouch.com/wordpress
Description: Integrates QuickVouch with WordPress to collect testimonials. Go to <a href="options-general.php?page=quickvouch.php">configuration panel</a> for more settings.
Version: 1.0.0
Author: Cesar Serna
Author URI: http://www.cesarserna.com
*/

define('qv_vNum','1.0');

// Check for location modifications in wp-config
// Then define accordingly
if ( !defined('WP_CONTENT_URL') ) {
	define('QV_PLUGPATH',get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');
	define('QV_PLUGDIR', ABSPATH.'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');
} else {
	define('QV_PLUGPATH',WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
	define('QV_PLUGDIR',WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
}
if ( !class_exists('SERVICES_JSON') ) {
	if ( !function_exists('json_decode') ){
		function json_decode($content, $assoc=false){
			require_once 'includes/JSON.php';
			if ( $assoc ){
				$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
			} else {
				$json = new Services_JSON;
			}
			return $json->decode($content);
		}
	}
	if ( !function_exists('json_encode') ){
		function json_encode($content){
			require_once 'includes/JSON.php';
			$json = new Services_JSON;
			return $json->encode($content);
		}
	}
}

add_action('admin_menu', 'qv_admin_home');
function qv_admin_home()
{
	if (function_exists('add_menu_page'))
	{
		add_menu_page('QuickVouch', 'QuickVouch', '10', 'qv_admin_generalsettings', 'qv_admin_generalsettings','http://cdn.quickvouch.com/quickvouch-16x16.png');
	}
	
	if (function_exists('add_submenu_page')) {
	}
}

function qv_admin_generalsettings()
{

if($_POST['qv_generalsettings'])
{
	if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'qv_nonce'))
	{
		die("Failed Security Check");
	}
	else
	{
		$qv_mode = $_POST['qv_mode'];
		$qv_gvrid = $_POST['qv_global_vouchrequest'];
		$qv_exclude = $_POST['qv_exclude'];
		update_option('qv_global_vouchrequest', $qv_gvrid);
		update_option('qv_mode', $qv_mode);
		update_option('qv_exclude', $qv_exclude);
	}
}
$qv_mode = get_option('qv_mode');
$qv_gvrid = get_option('qv_global_vouchrequest');
$qv_exclude = get_option('qv_exclude');

if ($qv_mode == '')
{
	$qv_mode = 'adminonly';
}
?>
<style>
	#poststuff .inside, #poststuff .inside p
	{
		font-size:14px;
		line-height: 20px;
	}
</style>
<div class="wrap">
  <h2><a href="http://quickvouch.com/" target="_new">QuickVouch</a> - General Settings</h2>
  
  
<div id="poststuff" class="metabox-holder has-right-sidebar">
	<div class="inner-sidebar">
		<div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position:relative;">
			<div id="qv_one" class="postbox">
			<h3 class="hndle"><span>About QuickVouch:</span></h3>
				<div class="inside">
				QuickVouch is the simplest way to start collecting testimonials on your site.
				</div>
			</div>
			<div id="qv_two" class="postbox">
			<h3 class="hndle"><span>Give A Testimonial!</span></h3>
				<div class="inside">
				<b>If you have a moment, please give the <a href="http://quickvouch.com/v/f84113154c990a6bff460dfbc8b78c6a" target="_new">QuickVouch WordPress Plugin a great testimonial!</a></b>
				</div>
			</div>
		</div>
	</div>	
	<div class="has-sidebar sm-padded" >
		<div id="post-body-content" class="has-sidebar-content">
			<div class="meta-box-sortabless">
				<div id="sm_rebuild" class="postbox">
					<h3 class="hndle"><span>General Website Settings</span></h3>
					<div class="inside">
					  <form method="post">
					    <table>
					      <tr>
					        <td colspan="2"><b>QuickVouch Mode:</b><br/></td>
					      </tr>
					      <tr>
					        <td valign="top" style="padding-top:3px;"><input type="radio" name="qv_mode" value="global" <?php if ($qv_mode=='global'){echo $qv_mode;} ?>/></td>
					        <td valign="top">
								<b>On</b> - All visitors will see the QuickVouch icon.
					        </td>
					     	</tr>
					        <tr>
					        <td valign="top" style="padding-top:3px;"><input type="radio" name="qv_mode" value="off" <?php if ($qv_mode=='off'){echo $qv_mode;} ?>/></td>
					        <td valign="top">        
					          <b>Off</b> - This will turn off the QuickVouch icon. 
					      	</td>
					      </tr>
					        <tr>
					        <td valign="top" style="padding-top:3px;"><input type="radio" name="qv_mode" value="adminonly" <?php if ($qv_mode=='adminonly'){echo 'checked=\'checked\'';} ?>/></td>
					        <td valign="top">
					          <b>Admin Only</b> - This will turn on the QuickVouch icon for Administrator only. (So you can see it first!).
					        </td>
					      </tr>
					    </table>
					    <br/>
					    <table>
					      <tr>
					        <td><b>Global Vouch Request ID:</b><br/></td>
					        </tr>
					        <tr>
					        <td>
					          <input size="50" type="text" name="qv_global_vouchrequest" value="<?php echo $qv_gvrid; ?>"/>
					        </td>
					      </tr>
					    </table>
					    <!--<b>Note:</b> You can assign a different Vouch Request ID per Post and Page. To do this either create or edit an existing post or page.
					    <br/>-->
					    <br/> 
					    <table>
					      <tr>
					        <td><b>Exclude Post/Page by ID:</b><br/></td>
					        </tr>
					        <tr>
					        <td>
					          <input size="50" type="text" name="qv_exclude" value="<?php echo $qv_exclude; ?>"/>
					          <br/>
					          You can exclude posts and pages by entering a comma separated string of id's. (ex. 2,45,77)
					        </td>
					      </tr>
					    </table>
					    <br/>
				        <input type="hidden" name="qv_generalsettings" value="update" />
					    <input name="_wpnonce" type="hidden" value="<?php echo wp_create_nonce('qv_nonce'); ?>" />
					    <input type="submit" value="Save Your Settings"/>				
				    	</form>
	
					</div>
				</div>
			</div>  
		</div>
	</div>
</div>  
</div>
<?php
}


add_action('wp_footer', 'qv_footer_js');
function qv_footer_js()
{
	global $current_user, $post;
	get_currentuserinfo();
	
	$qv_mode = get_option('qv_mode');
	$qv_gvrid = get_option('qv_global_vouchrequest');
	
	if ($qv_mode != '')
	{
		if ($qv_mode == 'off')
			return;
		if ($qv_mode == 'adminonly')
		{
			$user = get_userdata($current_user->ID);
			if($user->user_level == 10) {
				if (!qv_is_excluded($post->ID))
				{
					echo qv_print_footer_js($qv_gvrid);
				}
			}
		}
		if ($qv_mode == "on")
		{
			if (!qv_is_excluded($post->ID))
			{
				echo qv_print_footer_js($qv_gvrid);
			}
		}
	}
}
function qv_is_excluded($pid='')
{
	$ret = false;
	$qv_exclude = get_option('qv_exclude');
	if ($pid != '')
	{
		if ($qv_exclude != '')
		{
			$aExclude = explode(',', $qv_exclude);
			foreach($aExclude as $ex)
			{
				if (intval($ex) == intval($pid))
					return true;
			}
		}			
	}
	return $ret;
}
function qv_print_footer_js($qv_vr='')
{
	$js_str='';
	if ($qv_vr != '')
	{
		$js_str .= '<!-- QuickVouch Start -->
		<div id="qv_heart"><a id="' . $qv_vr . '" href="http://quickvouch.com"><span>give a testimonial!</span><img src="http://cdn.quickvouch.com/images/qvheart.png" border="0" alt="feedback and testimonials"/></a></div>
		<script src="http://cdn.quickvouch.com/js/qvlb.js" type="text/javascript"></script>
		<!-- QuickVouch End -->';
	}
	return $js_str;
}

?>
