<?php
if (!class_exists('WPPostsRateKeys_WPPosts')) {
	class WPPostsRateKeys_WPPosts
	{

	   /**
	    * The name of the keyword metadata
	    *
	    * @static 
	    * @var string
	    */
        static $keyword_metadata = 'posts_rate_key';
        
        /**
         * Function to process posts with invalid cache data
         * 
         * XXX usefull?
         * 
         * @param	int	$number	determine how many posts will be proccessed
         * @return 	int			posts with invalid cache data
         * @static 
         */
        static function plugin_process_posts_with_invalid_cache_data($process_all = FALSE) {
        	if ($process_all) {
        		$posts_without_valid_cache_arr = WPPostsRateKeys_WPPosts::get_posts_without_valid_cache('all');
        	}
        	else {
	        	// Get the number of posts without a valid Cache and the data
	        	$number_of_post_at_same_time = WPPostsRateKeys_Settings::get_number_of_post_to_process_at_same_time();
				$posts_without_valid_cache_arr = WPPostsRateKeys_WPPosts::get_posts_without_valid_cache($number_of_post_at_same_time);
        	}
        	
			$posts_without_valid_cache = $posts_without_valid_cache_arr[0];
			$posts_without_valid_cache_posts_data = $posts_without_valid_cache_arr[1];
			
			// Request data of post which invalid Cache
			foreach ($posts_without_valid_cache_posts_data as $post_id) {
				// Passing to it the serialized data to send to server
				$cache_was_updated = WPPostsRateKeys_Central::get_data($post_id);
				if ($cache_was_updated)
					$posts_without_valid_cache--; // Reduce from $posts_without_valid_cache the already updated
			}
			
			return $posts_without_valid_cache;
        }
        
        /**
         * Function to the href for link_to_post_page_edit_page
         * 
         * @global	string	$wp_version
         * @param 	int		$post_id
         * @param 	string	$is_post_page	Can be the string: 'page' or 'post'
         * @return 	string
         * @static 
         */
        static function get_link_to_post_page_edit_page($post_id, $is_post_page) {
        	global $wp_version;
		
        	$href = get_bloginfo ( 'wpurl' ) . '/wp-admin/';
        	
	        // If version 3.0 always is "post" the link
			if (!version_compare($wp_version, "3.0", '<'))
				$href .= 'post';
			else
				$href .= $is_post_page;
				
			$href .= '.php?action=edit&post=' . $post_id;
			
			return $href;
        }
        
        /**
         * Function to the update the "posts_rate_key" postmeta value
         * 
         * @param 	int		$post_id
         * @param 	string	$new_keyword
         * @return 	void
         * @static 
         */
        static function update_keyword($post_id,$new_keyword) {
        	
        	$new_keyword = trim($new_keyword);
        	
        	// Use WP function to update the value
        	update_post_meta($post_id, self::$keyword_metadata, $new_keyword);
        }
        
        /**
         * Function to the get the number of posts/pages without valid cache
         * 
         * Don't count or return the posts that, using Ajax-Request method, can't be updated
         * because are too big
         * 
         * @param	int		$number_of_post_at_same_time	Numer of posts with invalid Cache to return the data Of
         * @param	bool	$return_post_id_as_key			False when the array to return doesn't need to have the post id
         * @return 	array
         * @static 
         */
        static function get_posts_without_valid_cache($number_of_post_at_same_time=0, $return_post_id_as_key=TRUE) {
        	
        	$number_of_posts_pages_without_valid_cache = 0;
        	$data_to_return = array();
        	
        	global $wpdb;
        	
        	$query = "SELECT $wpdb->posts.ID,$wpdb->posts.post_title,$wpdb->posts.post_content
						FROM $wpdb->posts 
						WHERE ($wpdb->posts.post_type = 'page' or $wpdb->posts.post_type = 'post') 
						and $wpdb->posts.post_status != 'auto-draft' and $wpdb->posts.post_status != 'trash'";
			
			$posts = $wpdb->get_results( $query );
			foreach ($posts as $post_data) {
				$post_title = $post_data->post_title;
				$post_content = $post_data->post_content;
				$post_keyword = WPPostsRateKeys_WPPosts::get_keyword($post_data->ID);
				$settings = WPPostsRateKeys_Central::get_md5_settings(FALSE, $post_keyword);
				
				// Calculate the md5 here and not use the function for this, to avoid make a request to the database por each post
				$current_md5 = md5($post_keyword.$post_title.$post_content.$settings);
				$cached_md5 = get_post_meta($post_data->ID, WPPostsRateKeys_Central::$cache_md5, TRUE);
				
				if ($current_md5!=$cached_md5) {
					
					$number_of_posts_pages_without_valid_cache++;
					
					// Fill $data_to_return if the post hasn't a valid Cache and the number of required posts isn't filled
					if ($number_of_post_at_same_time==='all' || ($number_of_post_at_same_time>0 && count($data_to_return)<$number_of_post_at_same_time)) {
						$data_to_return[] = $post_data->ID;
					}
				}
			}
			
			return array($number_of_posts_pages_without_valid_cache,$data_to_return);
        }
        
        /**
         * Function to the get the "posts_rate_key" postmeta value
         * 
         * @param 	int		$post_id
         * @return 	string
         * @static 
         */
        static function get_keyword($post_id) {
        	
        	// Use WP function to get the value
        	$key = get_post_meta($post_id, self::$keyword_metadata, TRUE);
        	
        	// Return the keyword with blank spaces, to macth only a whole word or phrase
        	return $key;
        }
        
        /**
         * Function to get the count of Posts
         * 
         * @global	object	$wpdb				WP object to access database
         * @param 	int		$begin_date			a valid date
         * @param 	int		$end_date			a valid date
         * @param 	strin	$post_type			allowed values are: post and page
         * @return	array of objects with posts data
         * @static 
         */
        static function get_wp_posts_count($begin_date='',$end_date='', $post_type = 'post') {
			global $wpdb;
			
			// Set default query
			$query = "SELECT count($wpdb->posts.ID)
						FROM $wpdb->posts 
						WHERE $wpdb->posts.post_type = '$post_type' and $wpdb->posts.post_status != 'auto-draft' and $wpdb->posts.post_status != 'trash'";
			
			// Only add conditions of date if are specified by user
			if ($begin_date!='') {
				$query .= " and DATE($wpdb->posts.post_date) >= '" . date('Y-m-d',$begin_date) . "'";
			}
			
			if ($end_date!='') {
				// compare only date part to avoid problems with posts in the same date in some time
				$query .= " and DATE($wpdb->posts.post_date) <= '" . date('Y-m-d',$end_date) . "'";
			}
			
			return $wpdb->get_var($query);
        }
						
        /**
         * Function to get Post title and content from database
         * 
         * @global	object	$wpdb			WP object to access database
         * @param 	int		$post_id
         * @return	array	Of strings, first the title
         */
        static function get_wp_post_title_content($post_id) {
			
        	global $wpdb;
			
			$query = "SELECT post_title,post_content FROM $wpdb->posts where ID = $post_id";
			$post_data = $wpdb->get_row( $query );
			if ($post_data)
				return array($post_data->post_title,$post_data->post_content);
        }
			
        /**
         * Function to get the list of Posts
         * 
         * @global	object	$wpdb			WP object to access database
         * @param 	int		$begin_date		a valid date
         * @param 	int		$end_date		a valid date
         * @param 	string	$order_by
         * @param 	string	$order_dir
         * @param 	int		$pagination_offset	index of the first row to return
         * @param 	int		$pagination_limit	number of rows to return
         * @return	array of objects with posts data
         * @static 
         */
        static function get_wp_posts($begin_date='',$end_date='',$order_by = 'rate', $order_dir = 'DESC', $post_type = 'post',$pagination_offset,$pagination_limit) {
			global $wpdb;
			
			// Set default query
			$query = "SELECT $wpdb->posts.ID,$wpdb->posts.post_title,$wpdb->posts.post_date
						FROM $wpdb->posts 
						WHERE $wpdb->posts.post_type = '$post_type' and $wpdb->posts.post_status != 'auto-draft' and $wpdb->posts.post_status != 'trash'";
			

			// Only add conditions of date if are specified by user
			if ($begin_date!='') {
				$query .= " and DATE($wpdb->posts.post_date) >= '" . date('Y-m-d',$begin_date) . "'";
			}
			
			if ($end_date!='') {
				// compare only date part to avoid problems with posts in the same date in some time
				$query .= " and DATE($wpdb->posts.post_date) <= '" . date('Y-m-d',$end_date) . "'";
			}
			
			// Add default order by date and limit
			$query .= " order by $wpdb->posts.post_date DESC LIMIT $pagination_offset,$pagination_limit";
			$posts = $wpdb->get_results( $query );
			
			// Convert Objects to Arrays, and add the rate
			$posts_arr = array();
			foreach ($posts as $post) {
				$tmp_post_item = (array) $post;
				
				// Add a field 'keyword' that will be used to order the list
				$tmp_post_item['keyword'] = WPPostsRateKeys_WPPosts::get_keyword($tmp_post_item['ID']);

				// Add a field 'date' that will be used to order the list
				$tmp_post_item['date'] = strtotime($tmp_post_item['post_date']);
				
				// Filter the title by our filter
				$tmp_post_item['post_title'] = WPPostsRateKeys::filter_post_title($tmp_post_item['post_title'],$tmp_post_item['ID']);
				
				/*
				 * Add rate
				 * 
				 * Make the Plugin-Request of needed
				 * The Ajax-Request is done in Html.
				 */
				if (!WPPostsRateKeys_Central::is_cache_valid($tmp_post_item['ID'])) {
					$tmp_post_item['rate_success'] = WPPostsRateKeys_Central::get_data($tmp_post_item['ID']);
				}
				else {
					$tmp_post_item['rate_success'] = TRUE; // No need of update
				}
				$tmp_post_item['rate'] = WPPostsRateKeys_Central::get_score ($tmp_post_item['ID']); // This is the value in Cache, that can be modified in the moment of show the post if the Cache is invalid
				
				$posts_arr[] = $tmp_post_item;
			}
			
			// Order by Rate
			if ($order_by=='rate' || $order_by=='date')
				$value_type = 'num';
			else 
				$value_type = 'str';
				
			$posts_arr = WPPostsRateKeys_Miscellaneous::sortmddata($posts_arr,$order_by,$order_dir,$value_type);
			
			return $posts_arr;
		}
	}
}