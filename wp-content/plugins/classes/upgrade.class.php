<?php
if (!class_exists('WPPostsRateKeys_Upgrade'))
{
	class WPPostsRateKeys_Upgrade
	{
		/**
         * Make all checks for requirements to Upgrade
         * 
         * @static
         * @return 	bool	True if all requirements were fullfil, else False
         */
        static function all_checks_for_upgrade() {
        	$check_for_write_permission = self::check_for_write_permission();
        	$check_for_write_permission = $check_for_write_permission[0];
        	
        	if (self::check_for_outgoing_connections() && $check_for_write_permission && self::check_for_ziparchive_class()) {
        		return TRUE;
        	}
        	else {
        		return FALSE;
        	}
        }
        
		/**
         * Make the check for Write permissions
         * 
         * @static
         * @return array	[0]	bool, True if has Write permission, else False
         * 					[1] array, with the list of folder/files without write permission
         */
        static function check_for_write_permission() {
        	
        	$all_has_write_permission = TRUE; 
	        $list_wrong_permissions = array();
        	
	        // Check ppal folder
        	if (!is_writable(WPPostsRateKeys::$plugin_dir)) {
        		$list_wrong_permissions[] = WPPostsRateKeys::$plugin_dir;
        		$all_has_write_permission = FALSE;
        	}
        	
        	$folder_files_arr = WPPostsRateKeys_Miscellaneous::dir_tree(rtrim(WPPostsRateKeys::$plugin_dir,'/'));
        	foreach ($folder_files_arr as $folder_file_item) {
        		if ($folder_file_item!='..' && !is_writable($folder_file_item)) {
			    	$list_wrong_permissions[] = $folder_file_item;
			    	$all_has_write_permission = FALSE;
			    }
			}
        	
        	return array($all_has_write_permission,$list_wrong_permissions);
        }
        
		/**
         * Make the check for Outgoing connections
         * 
         * @static
         * @return 	bool	True if has able to do outgoing connections, else False
         */
        static function check_for_outgoing_connections() {
        	// Try to make a Google connection
        	$response = wp_remote_get('http://www.google.com',array('timeout'=>60));
			if (!is_wp_error($response)) { // Else, was an object(WP_Error)
				return TRUE;
			}
			else {
        		return FALSE;
			}
        }
        
		/**
         * Make the check for ZipArchive class
         * 
         * @static
         * @return 	bool	True if has ZipArchive class, else False
         */
        static function check_for_ziparchive_class() {
        	if (class_exists('ZipArchive')) {
        		return TRUE;
        	}
        	else {
        		return FALSE;
        	}
        }
        
        /**
         * Used to upload to Ftp
         * 
         * @param unknown_type $conn_id
         * @param unknown_type $src_dir
         * @param unknown_type $dst_dir
         */
		function seo_pressor_ftp_putall($conn_id, $src_dir, $dst_dir) {
		   $d = dir($src_dir);
		   while($file = $d->read()) { // do this for each file in the directory
		       if ($file != "." && $file != "..") { // to prevent an infinite loop
		           if (is_dir($src_dir."/".$file)) { // do the following if it is a directory
		               if (!@ftp_nlist($conn_id, $dst_dir."/".$file)) {
		                   ftp_mkdir($conn_id, $dst_dir."/".$file); // create directories that do not yet exist
		               }
		               seo_pressor_ftp_putall($conn_id, $src_dir."/".$file, $dst_dir."/".$file); // recursive part
		           } else {
		               $upload = @ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY); // put the files
		               if (!$upload) {
			               	// Try with pasive mode, turn passive mode on
							ftp_pasv($conn_id, true);
		               		$upload_pasv = @ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY); // put the files
		               		
		               		if (!$upload_pasv) {
			               		global $msg_error;
			               		$msg_error[] = __('The file uploading fails: ','seo-pressor') . $file;
		               		}
		               }
		           }
		       }
		   }
		   $d->close();
		}
	}
}