<?php
/**
 * Include to auto upgrade page 
 *
 * @package admin-panel
 * 
 */

/**
 * 
 * Upload a directory to the FTP
 * 
 * @param 	string	$conn_id
 * @param 	string	$src_dir
 * @param 	string	$dst_dir
 */

global $msg_error;

/*
 * Check Requirements to Auto-Upgrade
 */
// Check if plugin is activated
if (WPPostsRateKeys_Settings::get_active()==0) {
	$msg_error[] = __("The Plugin isn't Active.",'seo-pressor');
}

// Check if Class ZipArchive exists
$zip_archive_requirement = TRUE;
if (!WPPostsRateKeys_Upgrade::check_for_ziparchive_class()) {
	$msg_error[] = __("ZipArchive class is required. Please request your hosting provider to install this for your server",'seo-pressor');
	$zip_archive_requirement = FALSE;
}

// Check if allowed outgoing connection
$outgoing_connection_requirement = TRUE;
if (!WPPostsRateKeys_Upgrade::check_for_outgoing_connections()) {
	$msg_error[] = __("The server where the Plugin is installed is blocking outgoing connections. Please check with your hosting provider to allow outgoing connections to SEOPressor.com",'seo-pressor');
$outgoing_connection_requirement = FALSE;
}

// Check write permission
$write_permission_requirement = TRUE;
$check_for_write_permission = WPPostsRateKeys_Upgrade::check_for_write_permission();
if (!$check_for_write_permission[0]) {
	$list_msg = __("Write permission is required to update SEOPressor. Please contact your hosting provider to compile your PHP handler to SuPHP or enable permissions to the following folders:",'seo-pressor');
	$list_msg .= '<br>';
	foreach ($check_for_write_permission[1] as $msg_item) {
		$list_msg .= '<br>' . $msg_item;
	}
	$msg_error[] = $list_msg;
	$write_permission_requirement = FALSE;
}

// Check if is needed the upgrade
$current_options = WPPostsRateKeys_Settings::get_options(); 
if (!version_compare($current_options['current_version'], $current_options['last_version'], "<")) {
	$msg_notify[] = __("You don't need upgrade. You already has the last version.",'seo-pressor');
}

if (!isset($msg_error) && isset($_POST['upgrade'])) {
	/*
	 * Direct Upgrade
	 */
	if (version_compare($current_options['current_version'], $current_options['last_version'], "<")) {
		// Set path to file
		$file_name = WPPostsRateKeys::$plugin_dir . '/seo_pressor_last_ver.zip';
			
		// Download last version
		$last_ver_url = WPPostsRateKeys_Central::$url_to_automatic_upgrade . '?cbc=' . $current_options['clickbank_receipt_number'];
		$response = wp_remote_get($last_ver_url,array('timeout'=>60));
		
		if (!is_wp_error($response)) { // Else, was an object(WP_Error)
        	$zip_content = $response['body'];
        	if ($zip_content=='INVALID_LICENSE') {
        		$msg_error = __('Your license is invalid.','seo-pressor');
        	}
        	else {
        		if (file_put_contents($file_name, $zip_content)) {
        			// Unzip the files
					$zip = new ZipArchive;
					if ($zip->open($file_name) === TRUE) {
						@$zip->extractTo(WPPostsRateKeys::$plugin_dir . '..');
						
						// Used because extractTo can return True if one file could be extracted but other not!
						$last_error = error_get_last();
						if (key_exists('message',$last_error)) {
							if (substr_count($last_error['message'],'failed to open stream: Permission denied')>0
								&& substr_count($last_error['message'],'ZipArchive::extractTo') >0
								) {
								$msg_error = __('The upgrade fails. Check the Web Server user have permission to rewrite the content on the plugin folder ','seo-pressor')
						    		. WPPostsRateKeys::$plugin_dir
						    		. '<br><br>'
							        . __(' Details: ','seo-pressor')
							        . $last_error['message'];
								;
							}
						}
						
					    $zip->close();
					    
					    // Delete Zip
					    @unlink($file_name);
					} else
					    $msg_error = __('The upgrade fails. Check the permission to rewrite the content of the plugin folder ','seo-pressor')
					    		. WPPostsRateKeys::$plugin_dir;
        		}
        		else {
        			$msg_error = __('Your plugin folder needs to have write permission.','seo-pressor');
        		}
        	}
        }
        else {
        	/*@var WP_Error $response*/
			$msg_error = __('Connection to download last version fails. Error: ','seo-pressor')
						. $response->get_error_message($response->get_error_code()); 
        }
		
		if (!isset($msg_error)) {
			// Deactivate and Activate the plugin calling the upgrade script
			include( WPPostsRateKeys::$plugin_dir . '/includes/upgrade.php');

			// If successfully, notify user
			$msg_notify[] = __('The upgrade was successfully. Now you can remove write access to your Plugin files.','seo-pressor');
		}
	}
	else {
		$msg_notify[] = __("You don't need upgrade. You already has the last version.",'seo-pressor');
	}
}

if (isset($msg_error)) {
	$msg_error[] = __('Check error message and try again.','seo-pressor'); 
}

include( WPPostsRateKeys::$template_dir . '/includes/admin/auto_upgrade.php');