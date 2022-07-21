<?php
/**
 * Include to show the plugin settings
 *
 * @package admin-panel
 * 
 */

// Security: Check if is admin user
WPPostsRateKeys_Users::an_admin_must_be_authenticated();

/*
 * Activation ans Settings actions
 */

if (isset($_POST['Submit_save_changes']) || isset($_POST['Submit_activation']) || isset($_POST['Submit_reactivation'])) {
	// Security: Check nonce
	check_admin_referer('WPPostsRateKeys-save-settings');
	
	$original_data = WPPostsRateKeys_Settings::get_options();

	// Set same values for hardcode
	$data = $original_data;
	// Get values defined by user
	if (isset($_POST['allow_add_keyword_in_titles']))
		$data['allow_add_keyword_in_titles'] = 1;
	else
		$data['allow_add_keyword_in_titles'] = 0;
	if (isset($_POST['allow_seopressor_footer']))
		$data['allow_seopressor_footer'] = 1;
	else
		$data['allow_seopressor_footer'] = 0;
	if (isset($_POST['allow_bold_style_to_apply']))
		$data['allow_bold_style_to_apply'] = 1;
	else
		$data['allow_bold_style_to_apply'] = 0;
	if (isset($_POST['allow_italic_style_to_apply']))
		$data['allow_italic_style_to_apply'] = 1;
	else
		$data['allow_italic_style_to_apply'] = 0;
	if (isset($_POST['allow_underline_style_to_apply']))
		$data['allow_underline_style_to_apply'] = 1;
	else
		$data['allow_underline_style_to_apply'] = 0;
	
	/* From v5 this feature if deprecated
	if (isset($_POST['allow_site_wide_keyword']))
		$data['allow_site_wide_keyword'] = 1;
	else
		$data['allow_site_wide_keyword'] = 0;
	$data['site_wide_keywords_list'] = $_POST['site_wide_keywords_list'];
	*/
	$data['bold_style_to_apply'] = $_POST['bold_style_to_apply'];
	$data['italic_style_to_apply'] = $_POST['italic_style_to_apply'];
	$data['underline_style_to_apply'] = $_POST['underline_style_to_apply'];
	if (isset($_POST['allow_automatic_adding_alt_keyword']))
		$data['allow_automatic_adding_alt_keyword'] = 1;
	else
		$data['allow_automatic_adding_alt_keyword'] = 0;
	if (isset($_POST['allow_automatic_adding_rel_nofollow']))
		$data['allow_automatic_adding_rel_nofollow'] = 1;
	else
		$data['allow_automatic_adding_rel_nofollow'] = 0;
	if (isset($_POST['h1_tag_already_in_theme']))
		$data['h1_tag_already_in_theme'] = 1;
	else
		$data['h1_tag_already_in_theme'] = 0;
	if (isset($_POST['h2_tag_already_in_theme']))
		$data['h2_tag_already_in_theme'] = 1;
	else
		$data['h2_tag_already_in_theme'] = 0;
	if (isset($_POST['h3_tag_already_in_theme']))
		$data['h3_tag_already_in_theme'] = 1;
	else
		$data['h3_tag_already_in_theme'] = 0;
	$data['clickbank_id'] = trim($_POST['clickbank_id']);
	$data['locale'] = $_POST['locale'];
	/* Deprecated functionality: no more bulk updates of Cache data
	$data['number_of_posts_for_bulk_requests'] = $_POST['number_of_posts_for_bulk_requests'];
	*/
	
	WPPostsRateKeys_Settings::update_options($data);
	$msg_notify[] = __('The Settings were updated.');
	
	if (isset($_POST['Submit_reactivation'])) {
		// Manual rectivation
		// Check if is filled clickbank_receipt_number
		if ($data['clickbank_receipt_number']!='') {
			// Check if already in Central Database
			$response = WPPostsRateKeys_Central::get_specific_data_from_server('if_active');
				
        	if ($response) { // Else, was a problem in connection
				$msg_notify[] = WPPostsRateKeys_Settings::update_active_by_server_response($response, TRUE);
			}
			else {
				$msg_error[] = __('A problem occurs when try to Re-Activate the plugin. Check your networks connection.','seo-pressor');
			}
		}
		else {
			$msg_error[] = __('The ClickBank Receipt Number must be filled for activation.','seo-pressor');
		}
	}
	
	/*
	 * Activation Code
	 */
	if (isset($_POST['Submit_activation'])) {
		// Check is isn't already activated
		if (WPPostsRateKeys_Settings::get_active()==1) {
			$tmp_msg = __('The plugin is active.','seo-pressor');
			$msg_notify[] = $tmp_msg;
			WPPostsRateKeys_Settings::set_last_activation_message($tmp_msg);
		}
		elseif ($data['allow_manual_reactivation']=='1') {
			$msg_notify[] = __('The plugin requires Reactivation.','seo-pressor');
		}
		else {
			// Check if is filled clickbank_receipt_number
			if ($data['clickbank_receipt_number']!='') {
				// Check against embebed code, not against Central Server
				$response = WPPostsRateKeys_Central::check_to_active();
				
        		if ($response) { // Else, was a problem in connection
					$tmp_msg = __('SEOPressor was activated successfully.','seo-pressor');
					$msg_notify[] = $tmp_msg;
					WPPostsRateKeys_Settings::set_last_activation_message($tmp_msg);
					
				}
				else {
					$tmp_msg = __("Activation failed. Either your receipt number is invalid or current domain name isn't registered. <a href='http://seopressor.com/download/download.php'>Please add your domain to your license and try again.</a> Contact support at ",'seo-pressor') . '<a href="http://askdanieltan.com/ask/">http://askdanieltan.com/ask/</a>' . __(' with your receipt number.','seo-pressor');
					$msg_error[] = $tmp_msg;
					WPPostsRateKeys_Settings::set_last_activation_message($tmp_msg);
				}
			}
			else {
				$tmp_msg = __('The ClickBank Receipt Number must be filled for activation.','seo-pressor');
				$msg_error[] = $tmp_msg;
				WPPostsRateKeys_Settings::set_last_activation_message($tmp_msg); 
			}
		}
	}
}

// Get the data of the Plugin Options
$data = WPPostsRateKeys_Settings::get_options();

// Get all type of Bold
$bold_arr = WPPostsRateKeys_HtmlStyles::get_bold_styles();
// Get all type of Italic
$italic_arr = WPPostsRateKeys_HtmlStyles::get_italic_styles();
// Get all type of Underline
$underline_arr = WPPostsRateKeys_HtmlStyles::get_underline_styles();

// Show activation Status
$msg_status = $data['last_activation_message'];
if ($msg_status=='' && $data['active']==0) {
	$msg_status = __('The plugin is Inactive.','seo-pressor');
}
if ($msg_status=='' && $data['active']==1) {
	$msg_status = __('The plugin is Active.','seo-pressor');
}

/*
 * Get available languages:
 * 	all locales in files at /lang/ 
 */
$all_locales = array();
$lang_dir = WPPostsRateKeys::$plugin_dir . '/lang/';
if ($handle = opendir($lang_dir) ) {
    while (false !== ($file = readdir($handle))) {
    	if (!is_dir($lang_dir . $file) && $file!='.' && $file!='..' && substr_count($file,'.mo')>0 && $file!='default.mo') { // Only .mo files
        	$domain = str_ireplace('seo-pressor-','',$file);
        	$domain = str_ireplace('.mo','',$domain);
        	
        	$all_locales[] = $domain;
    	}
    }
    closedir($handle);
}

include( WPPostsRateKeys::$template_dir . '/includes/admin/general_settings.php');