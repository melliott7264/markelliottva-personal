<?php
if (!class_exists('WPPostsRateKeys_Central'))
{
	class WPPostsRateKeys_Central
	{
	   /**
	    * The url to central script that returns the Box message
	    *
	    * @static 
	    * @var string
	    */
        static $url_box_msg = 'http://seopressor.com/get_msg_for_plugin_box.php';
		
	   /**
	    * The URL to check if active
	    *
	    * @var string
	    */
        static $url_check_if_active = 'http://seopressor.com/activate.php';
        
	   /**
	    * The URL to check last version
	    *
	    * @var string
	    */
        static $url_check_last_version = 'http://seopressor.com/lvc.php'; 
        
	   /**
	    * The URL to do the automatic download and upgrade
	    *
	    * @var string
	    */
        static $url_to_automatic_upgrade = 'http://seopressor.com/lv_down.php';
        
	   /**
	    * The meta value cached filtered_content
	    *
	    * @static 
	    * @var string
	    */
        static $cache_filtered_content = '_seo_cached_filtered_content'; 
		
	   /**
	    * The meta value cached seopressor_original_post_content
	    *
	    * @static 
	    * @var string
	    */
        static $original_post_content = '_seopressor_original_post_content'; 
		
	   /**
	    * The meta value cached filtered_title
	    *
	    * @static 
	    * @var string
	    */
        static $cache_filtered_title = '_seo_cached_filtered_title'; 
		
	   /**
	    * The meta value cached score
	    *
	    * @static 
	    * @var string
	    */
        static $cache_score = '_seo_cached_score'; 
		
	   /**
	    * The meta value cached suggestions_box
	    *
	    * @static 
	    * @var string
	    */
        static $cache_suggestions_box = '_seo_cached_suggestions_box'; 
		
	   /**
	    * The meta value cached suggestions_page
	    *
	    * @static 
	    * @var string
	    */
        static $cache_suggestions_page = '_seo_cached_suggestions_page'; 
		
	   /**
	    * The meta value to check cache valid
	    *
	    * @static 
	    * @var string
	    */
        static $cache_md5 = '_seo_cache_md5';
        
        /**
         * Check to active
         * 
         * Only actives in this way when the Reactivation isn't required
         * 
         * @return bool True on success, else False
         */
        static function check_to_active() {
        	// Only actives in this way when the Reactivation isn't required
        	$data = WPPostsRateKeys_Settings::get_options();
        	if ($data['allow_manual_reactivation']=='1') {
				// The plugin requires Reactivation
				return FALSE;
        	}
        	
        	// Check domain
        	$clickbank_number = trim(WPPostsRateKeys_Settings::get_clickbank_receipt_number());
        	$current_domain = strtolower(get_bloginfo('wpurl'));
        	$current_domain_arr = parse_url($current_domain);
        	/*
        	 * Take in care that must be compatible with subdomains and directories, so user can 
        	 * install at something.somesite.com/blog/ with just somesite.com as the domain
        	 * 
        	 * so, get domain without subdirectories and wihout protocol part ex: http://
        	 */
        	$current_domain_no_dir = $current_domain_arr['host'];
        	
        	// XXX spread the follow codes for all over the plugin
        	// Get encoded md5 values, one per domain they add in Central Server
        	$md5_central_server_arr = array('36241898c590adfc4b562a19b0c1de0f','ea88e9737d47183db82e3f14bebfd311','ba54f877f8e6b2d7b5e3dc0a5fee65e4','23e97843a1be41c8e26f90694e64d6e2','299c4a884b9692d8d0b7b5605e19b74f');
        	
        	$central_server_domain_clear_text_arr = array('markelliottva.com','markelliottdesign.com','slashow.com','cynthiasteelart.com','cynthiasteeleltd.com');
        	
        	$is_valid_first_step = FALSE;
        	// Check if $current_domain_no_dir has as <<some>. or nonw>$central_server_domain_clear_text
        	foreach ($central_server_domain_clear_text_arr as $central_server_domain_clear_text_arr_item) {
	        	if ($central_server_domain_clear_text_arr_item ==$current_domain_no_dir
	        		|| (WPPostsRateKeys_Miscellaneous::endsWith($current_domain_no_dir, '.' . $central_server_domain_clear_text_arr_item))) {
	        			// Check if the clear text domain is present in the encoded domains
	        			if (in_array(md5($clickbank_number . $central_server_domain_clear_text_arr_item), $md5_central_server_arr)) {
	        				$is_valid_first_step = TRUE;
	        				break;
	        			}
	        	}
        	}
        	
        	if ($is_valid_first_step) {
        		// Active plugin
        		WPPostsRateKeys_Settings::update_active_by_server_response('ACTIVE',TRUE);
        		
        		// After Active the plugin, set the cron job to check against Central Server
        		$in_80_days = time() + (80 * 86400);
        		// XXX modify it to check several times before deactivate it
        		wp_schedule_single_event($in_80_days, 'seopressor_onetime_check_active');
        		
        		return TRUE;
        	}
        	else {
        		return FALSE;
        	}
        }
        
        /**
         * Get the settings that change the rate-suggestion-filters actions
         * 
         * Are the settings that affect the md5 calculation
         * 
         * @param	bool	$as_array		True when data must be returned as array
         * @param	string	$post_keyword	The keyword of the current post processing
         * @return 	string
         */
        static function get_md5_settings($as_array=FALSE, $post_keyword) {
        	$options = WPPostsRateKeys_Settings::get_options();
        	
        	/*
        	 * If keyword is empty, only add setting values that don't depend on that
        	 */
        	if ($post_keyword!='') {
	        	$return['h1_tag_already_in_theme'] = $options['h1_tag_already_in_theme'];
	        	$return['h2_tag_already_in_theme'] = $options['h2_tag_already_in_theme'];
	        	$return['h3_tag_already_in_theme'] = $options['h3_tag_already_in_theme'];
	        	$return['allow_add_keyword_in_titles'] = $options['allow_add_keyword_in_titles'];
	        	$return['allow_automatic_adding_alt_keyword'] = $options['allow_automatic_adding_alt_keyword'];
        	}
        	
        	/* From v5 this feature if deprecated
        	if ($post_keyword!='' 
        			|| ($options['allow_site_wide_keyword']=='1' && $options['site_wide_keywords_list']!='')) {
        	*/
        	if ($post_keyword!='') {
        		$return['allow_bold_style_to_apply'] = $options['allow_bold_style_to_apply'];
	        	$return['bold_style_to_apply'] = $options['bold_style_to_apply'];
	        	
	        	$return['allow_italic_style_to_apply'] = $options['allow_italic_style_to_apply'];
	        	$return['italic_style_to_apply'] = $options['italic_style_to_apply'];
	        	
	        	$return['allow_underline_style_to_apply'] = $options['allow_underline_style_to_apply'];
	        	$return['underline_style_to_apply'] = $options['underline_style_to_apply'];
        	}
        	
        	$return['allow_automatic_adding_rel_nofollow'] = $options['allow_automatic_adding_rel_nofollow'];
        	$return['clickbank_receipt_number'] = $options['clickbank_receipt_number'];
        	
        	if ($as_array)
        		return $return;
        	else // As string
        		return join('',$return);
        }
        
        /**
         * Get Md5 of current values
         * 
         * @param 	int 	$post_id
         * 
         * @return 	string|bool
         */
        static function get_current_values_md5($post_id) {
        	$post_keyword = WPPostsRateKeys_WPPosts::get_keyword($post_id);
        	
        	$data_arr = WPPostsRateKeys_WPPosts::get_wp_post_title_content($post_id);
			$post_title = $data_arr[0];
			$post_content = $data_arr[1];
			
			$settings = self::get_md5_settings(FALSE, $post_keyword);
			
			return md5($post_keyword.$post_title.$post_content.$settings);
        }
        
        /**
         * Check if the data in cache for POST is valid
         * 
         * @param 	int 	$post_id
         * @return 	string|bool
         */
        static function is_cache_valid($post_id) {
        	if (self::get_current_values_md5($post_id)==
        		get_post_meta($post_id, self::$cache_md5, TRUE))
        		return TRUE;
        	else
        		return FALSE;
        }
        
        /**
         * Return the original POST content
         * 
         * This will be used to show the original content of Posts when user edit the Post
         * 
         * @param 	int			$post_id	Used when the function is called from this plugin
         * @return 	string
         * @access 	public
         */
        static function get_original_post_content($post_id) {
        	return get_post_meta($post_id, self::$original_post_content, TRUE);
        }
        
        /**
         * Update the original POST content
         * 
         * This will be used to show the original content of Posts when user edit the Post
         * 
         * @param 	int			$post_id	Used when the function is called from this plugin
         * @param 	string		$original_content
         * @return 	string
         * @access 	public
         */
        static function update_original_post_content($post_id,$original_content) {
        	return update_post_meta($post_id, self::$original_post_content, $original_content);
        }
        
        /**
         * 
         * Return the Filtered POST title
         * 
         * @param 	int			$post_id	Used when the function is called from this plugin
         * @return 	string
         * @access 	public
         */
        static function get_filtered_title($post_id) {
        	return get_post_meta($post_id, self::$cache_filtered_title, TRUE);
        }
        
        /**
         * 
         * Return the score
         * 
         * @param 	int			$post_id	Used when the function is called from this plugin
         * @return 	string
         * @access 	public
         */
        static function get_score($post_id) {
        	$return = get_post_meta($post_id, self::$cache_score, TRUE);
        	if ($return=='')
        		$return = 0;
        		
        	return $return;
        }
        
        /**
         * Return the suggestions_box
         * 
         * @param 	int			$post_id	Used when the function is called from this plugin
         * @return 	string
         * @access 	public
         */
        static function get_suggestions_box($post_id) {
        	return unserialize(get_post_meta($post_id, self::$cache_suggestions_box, TRUE));
        }
        
        /**
         * 
         * Return the suggestions_page
         * 
         * @param 	int			$post_id	Used when the function is called from this plugin
         * @return 	array
         * @access 	public
         */
        static function get_suggestions_page($post_id) {
        	$value = unserialize(get_post_meta($post_id, self::$cache_suggestions_page, TRUE));
        	if ($value)
        		return $value;
        	else 
        		return array();
        }
        
        /**
         * 
         * Store data in Cache
         * 
         * @param 	string		$response
         * @param 	int			$post_id
         * @access 	public
         * @return	bool		TRUE when the cache was updated, else, FALSE		
         */
        static function process_data($data, $post_id='') {
        	if ($post_id=='')
				$post_id = $data['post_id'];
				
			// Save data in Cache
			update_post_meta($post_id, self::$cache_md5, self::get_current_values_md5($post_id));
			update_post_meta($post_id, self::$cache_filtered_title, $data['filtered_title']);
			update_post_meta($post_id, self::$cache_score, $data['score']);
			update_post_meta($post_id, self::$cache_suggestions_box, serialize($data['suggestions_box']));
			update_post_meta($post_id, self::$cache_suggestions_page, serialize($data['suggestions_page']));
			
			// Save filtered Post Content in Post database
			wp_update_post(array('post_content'=>$data['filtered_content'],'ID'=>$post_id));
			// Save original Post
			WPPostsRateKeys_Central::update_original_post_content($post_id,$data['original_content']);
				
        	return TRUE;
        }
        
        /**
         * 
         * Get specific information from Server:
         * - message to show in dashboard Box
         * - if plugin is active
         * 
         * This request is made by the plugin code
         * 
         * @param	string		$info_to_request	Can be: dashboard_box_message, if_active
         * @access 	public
         * @return	string|bool						returns the information or FALSE on fails
         */
        static function get_specific_data_from_server($info_to_request) {
        	
        	if ($info_to_request=='dashboard_box_message') {
        		$url_to_request = self::$url_box_msg;
        	}
        	elseif ($info_to_request=='if_active') {
        		$url_to_request = self::$url_check_if_active . '?clickbank_receipt_number=' 
								. urlencode(WPPostsRateKeys_Settings::get_clickbank_receipt_number())
								. '&plugin_domain=' . urlencode(get_bloginfo('wpurl'));
        	}
        	else // If none of the availables options was selected
        		return FALSE;
        	
        	// Request from server
        	$response = wp_remote_get($url_to_request,array('timeout'=>60));
        	
        	if (is_array($response)) { // Else, was an object(WP_Error)
        		$response = $response['body'];
        		return $response;
        	}
        	else {
        		return FALSE;
        	}
        }
        
		/**
		 * Get remote value: last version
		 * 
		 * @static
		 * @return bool		TRUE on success, FALSE on fails
		 * @access public
		 */
		static public function make_last_version_plugin_request() {			
			// Use WordPress function to get content of a remote URL
			$response = wp_remote_get(self::$url_check_last_version,array('timeout'=>60));
			
			if (is_array($response)) { // Else, was an object(WP_Error)
				$body = $response['body'];
					
				WPPostsRateKeys_Settings::update_last_version($body);
				
				return TRUE;
			}
			else
				return FALSE;
		}

		/**
         * 
         * Get Data and store in Cache
         * 
         * This request is made by the plugin code
         * 
         * @param 	int			$post_id			Used when the function is called from this plugin
         * @param	string		$to_send_serialized	data to send to server
         * @access 	public
         * @return	bool	TRUE on success, FALSE on fails
         */
        static function get_data($post_id) {
        	// Base data
        	$keyword = WPPostsRateKeys_WPPosts::get_keyword($post_id);
        	$data_arr = WPPostsRateKeys_WPPosts::get_wp_post_title_content($post_id);
			$post_title = $data_arr[0];
			
			// Post content: is the original Post content, stored as Post meta data
			$post_content = WPPostsRateKeys::get_content_to_edit($data_arr[1],$post_id);
			
			$settings = self::get_md5_settings(TRUE, $keyword);
        	
        	// Data to store
        	$data = array();
			$data['post_id'] = $post_id;
			$data['filtered_title'] = WPPostsRateKeys_Filters::filter_post_title($post_title,$keyword,$settings);
			$data['filtered_content'] = WPPostsRateKeys_Filters::filter_post_content($keyword,$post_content,$settings);
			$data['original_content'] = $post_content;
			
			/*
			 * $post_id,$keyword,$filtered_content,$filtered_title
        									,$settings,$content
			 */
			$all_post_data = WPPostsRateKeys_ContentRate::get_all_post_data($post_id,$keyword,$data['filtered_content']
														,$data['filtered_title'],$settings,$post_content);
			$data['score'] = $all_post_data[0];
			$data['suggestions_box'] = $all_post_data[1];
			$data['suggestions_page'] = $all_post_data[2];
        		
	        return self::process_data($data, $post_id);
        }
	}
}