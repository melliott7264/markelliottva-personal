<?php
if (!class_exists('WPPostsRateKeys_Settings')) {
	class WPPostsRateKeys_Settings 
	{
	   /**
	    * The name for plugin options in the DB
	    *
	    * @var string
	    */
        static $db_option = 'WPPostsRateKeys_Options';
        
	   /**
	    * The URL for download
	    *
	    * @var string
	    */
        static $url_download = 'http://seopressor.com/download/download.php';
        
		/**
		 * Get number of posts to process at same time
		 * 
		 * Use for bulk processing of posts with invalid cache
		 * 
		 * @static
		 * @return string
		 * @access public
		 */
		static public function get_number_of_post_to_process_at_same_time() {
			$options = self::get_options();
			return $options['number_of_posts_for_bulk_requests'];
		}
		
		/**
		 * Get message to show to notify about new versions
		 * 
		 * @static
		 * @return string
		 * @access public
		 */
		static public function get_msg_for_new_version() {
			return __('There is a new version of the SEOPressor Plugin. You can download the new version ','seo-pressor')
	        					. '<a href="' . self::get_download_url() . '">' 
	        					. __('here','seo-pressor') 
	        					. '</a>'
	        					. __(' or you can ','seo-pressor')
	        					. '<a href="' . get_bloginfo ( 'wpurl' ) 
	        					. '/wp-admin/admin.php?page=seopressor-auto-upgrade">' 
	        					. __(' automatically upgrade','seo-pressor')
	        					. '</a>';
		}
		
		/**
		 * Update setting value: last_version
		 * 
		 * @static
		 * @param 	string	$new_value
		 * @access 	public
		 */
		static public function update_last_version($new_value) {
			if ($new_value!='') {
				$options = self::get_options();
				
				// Update the value in options
				$options['last_version'] = $new_value;
						
				self::update_options($options);
			}
		}
		
		/**
		 * Get setting value: last_version
		 * 
		 * @static
		 * @return string
		 * @access public
		 */
		static public function get_last_version() {
			$options = self::get_options();
		   	return $options['last_version'];
		}
		
		/**
		 * Get setting value: seo_link
		 * 
		 * @static
		 * @return string	HTML code
		 * @access public
		 */
		static public function get_seo_link() {
			$options = self::get_options();
		   	return $options['seo_link'];
		}
		
		/**
		 * Get setting value: name_link
		 * 
		 * @static
		 * @return string
		 * @access public
		 */
		static public function get_name_link() {
			$options = self::get_options();
		   	return $options['name_link'];
		}
		
		/**
		 * Get setting value: allow_seopressor_footer
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function get_allow_seopressor_footer() {
			$options = self::get_options();
		   	return $options['allow_seopressor_footer'];
		}
		
		/**
		 * Get setting value: allow_bold_style_to_apply
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function get_allow_bold_style_to_apply() {
			$options = self::get_options();
		   	return $options['allow_bold_style_to_apply'];
		}
		
		/**
		 * Get setting value: allow_italic_style_to_apply
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function get_allow_italic_style_to_apply() {
			$options = self::get_options();
		   	return $options['allow_italic_style_to_apply'];
		}
		
		/**
		 * Get setting value: allow_underline_style_to_apply
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function get_allow_underline_style_to_apply() {
			$options = self::get_options();
		   	return $options['allow_underline_style_to_apply'];
		}
		
		/**
		 * Get setting value: bold_style_to_apply
		 * 
		 * @static
		 * @return string	HTML code
		 * @access public
		 */
		static public function get_bold_style_to_apply() {
			$options = self::get_options();
		   	return $options['bold_style_to_apply'];
		}
		
		/**
		 * Get setting value: italic_style_to_apply
		 * 
		 * @static
		 * @return string	HTML code
		 * @access public
		 */
		static public function get_italic_style_to_apply() {
			$options = self::get_options();
		   	return $options['italic_style_to_apply'];
		}
		
		/**
		 * Get setting value: underline_style_to_apply
		 * 
		 * @static
		 * @return string	HTML code
		 * @access public
		 */
		static public function get_underline_style_to_apply() {
			$options = self::get_options();
		   	return $options['underline_style_to_apply'];
		}
		
		/**
		 * Get setting value: active
		 * 
		 * @static
		 * @return string
		 * @access public
		 */
		static public function get_active() {
			$options = self::get_options();
			return $options['active'];
		}
		
		/**
		 * Update activation by Central Server response
		 * 
		 * @param	string	$response
		 * @param	bool	$user_submit		TRUE when user was who hit the activation button
		 * @static
		 * @return 	string	message for user
		 * @access 	public
		 */
		static public function update_active_by_server_response($response, $user_submit=FALSE) {
			
			$options = self::get_options();
			$tmp_msg = $options['last_activation_message'];
			
			// Ignored IF ACTIVE, because the activation is only when user click on button Activate
			if ($response=='ACTIVE') {
				if ($user_submit) { // Don't put this in previous IF to avoid that if ACTIVE enter in the last ELSE
					$options['active'] = 1;		
					$options['allow_manual_reactivation'] = 0;		
					$tmp_msg = __('The plugin is Active.','seo-pressor');
				}
			}
			elseif (substr_count($response,'ALREADYDOMAIN-')>0) {
				$options['active'] = 0;
				$error_arr = explode('ALREADYDOMAIN-',$response);
				$tmp_msg = __('Your Single Domain license has already been activated on ','seo-pressor') . $error_arr[1] . __('. If you need assistant, please contact support at ','seo-pressor') . '<a href="http://askdanieltan.com/ask/">http://askdanieltan.com/ask/</a>' . __(' with your receipt number.','seo-pressor');
			}
			else {
				// $response == 'NODB'
				$options['active'] = 0;
				$tmp_msg = __('Your receipt number is not active, please try again in 10 minutes or contact support at ','seo-pressor') . '<a href="http://askdanieltan.com/ask/">http://askdanieltan.com/ask/</a>' . __(' with your receipt number.','seo-pressor');
			}
			
			$options['last_activation_message'] = $tmp_msg;
			self::update_options($options);
			
			return $tmp_msg;
		}
		
		/**
		 * Get setting value: check_if_active_url
		 * 
		 * @static
		 * @return string
		 * @access public
		 */
		static public function get_check_if_active_url() {
			return self::$url_check_if_active;
		}
		
		/**
		 * Get setting value: current_version
		 * 
		 * @static
		 * @return string
		 * @access public
		 */
		static public function get_current_version() {
			$options = self::get_options();
		   	return $options['current_version'];
		}
		
		/**
		 * Get setting value: download_url
		 * 
		 * @static
		 * @return string
		 * @access public
		 */
		static public function get_download_url() {
			return self::$url_download;
		}
		
		/**
		 * Get setting value: clickbank_id
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function get_clickbank_id() {
			$options = self::get_options();
		   	return $options['clickbank_id'];
		}
		
		/**
		 * Get setting value: allow_automatic_adding_alt_keyword
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function get_allow_automatic_adding_alt_keyword() {
			$options = self::get_options();
		   	return $options['allow_automatic_adding_alt_keyword'];
		}
		
		/**
		 * Get setting value: allow_automatic_adding_rel_nofollow
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function get_allow_automatic_adding_rel_nofollow() {
			$options = self::get_options();
		   	return $options['allow_automatic_adding_rel_nofollow'];
		}
		
		/**
		 * Get setting value: allow_add_keyword_in_titles
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function get_allow_add_keyword_in_titles() {
			$options = self::get_options();
		   	return $options['allow_add_keyword_in_titles'];
		}
		
		/**
		 * Get setting value: clickbank_receipt_number
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function get_clickbank_receipt_number() {
			$options = self::get_options();
		   	return $options['clickbank_receipt_number'];
		}
		
		/**
		 * Set setting value: last_activation_message
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function set_last_activation_message($msg) {
			$options = self::get_options();
		   	$options['last_activation_message'] = $msg;
		   	self::update_options($options);
		}
		
		/**
		 * Get setting value: locale
		 * 
		 * @static
		 * @return bool
		 * @access public
		 */
		static public function get_locale() {
			$options = self::get_options();
		   	return $options['locale'];
		}
		
		/**
		 * Updates the General Settings of Plugin
		 * 
		 * @return void
		 * @access public
		 */
        static function update_options($options) {
        	// Update all Post contens XXX pending to define after answer
        	// XXX only do if some settings change
        	WPPostsRateKeys_WPPosts::plugin_process_posts_with_invalid_cache_data(TRUE);
	    	return update_option(self::$db_option, $options);	
	    }
        
	    
    	/**
		 * Return the General Settings of Plugin, and set them to default values if they are empty
		 * 
		 * @return array general options of plugin
		 * @access public
		 */
        static function get_options() {
        	// default values
		    $options = array 
		    (
		        'allow_add_keyword_in_titles' => 0
		        ,'allow_seopressor_footer' => 1
		        , 'allow_bold_style_to_apply' => 1
		        , 'bold_style_to_apply' => 0
		        , 'allow_italic_style_to_apply' => 1
		        , 'italic_style_to_apply' => 0
		        , 'allow_underline_style_to_apply' => 1
		        , 'underline_style_to_apply' => 0
		        , 'seo_link' => '<a href="http://www.CharteredSEO.com">SEO</a>'
		        , 'name_link' => '<a href="http://www.seopressor.com">SEOPressor</a>'
		        , 'clickbank_receipt_number' => ''
		        , 'active' => 0
		        , 'allow_manual_reactivation' => 0
		        , 'last_activation_message' => ''
		        , 'current_version' => WPPostsRateKeys::VERSION
		        , 'last_version' => WPPostsRateKeys::VERSION
		        , 'allow_automatic_adding_alt_keyword' => 1
		        , 'allow_automatic_adding_rel_nofollow' => 1
		        , 'clickbank_id' => ''
		        , 'locale' => '' // '' is default => English
		        , 'number_of_posts_for_bulk_requests' => 10
		        , 'h1_tag_already_in_theme' => 1
		        , 'h2_tag_already_in_theme' => 0
		        , 'h3_tag_already_in_theme' => 0
		    );
		    
	        // get saved options
			$saved = get_option(self::$db_option);
		    
			// assign them
		    if (!empty($saved)) {
		        foreach ($saved as $key => $option) {
		        	$options[$key] = WPPostsRateKeys_Validator::parse_output($option);
		        }
		    }
		    
		    // update the options if necessary
	        if ($saved != $options)
	        	update_option(self::$db_option, $options);
	        //return the options
	        return $options;
        }
	}
}