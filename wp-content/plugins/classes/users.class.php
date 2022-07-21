<?php
if (!class_exists('WPPostsRateKeys_Users')) {
	class WPPostsRateKeys_Users
	{
		/**
		 * Check if an administrator is authenticated, if not, redirect to User Login page 
		 *
		 * @static 
		 * @return void
		 * @access public
		 * 
		 */
		static function an_admin_must_be_authenticated() 
		{
			if (!self::is_administrator_authenticate()) {
				/*
				 * Use WordPress function as a safe way to redirect to any URL. 
				 * Ensures the resulting HTTP Location header is legitimate. 
				 */
				wp_redirect(WPPostsRateKeys::$plugin_url . "../../../wp-login.php");
			}
		}
		
		/**
		 * Check if user is authenticated, if not, redirect to User Login page 
		 *
		 * @static 
		 * @return void
		 * @access public
		 * 
		 */
		static function user_must_be_authenticated() 
		{
			/*
			 * Use WP function to:
			 * Checks whether the cookie is present on the client browser. If it is not, the user is 
			 * sent to the wp-login.php login screen. After logging in, the user is sent back to the 
			 * page he or she attempted to access. 
			 */
			auth_redirect();
		}
		
		/**
		 * Checks if the current visitor is a logged in user.
		 *
		 * @static 
		 * @return bool True if user is logged in, false if not logged in.
		 * @access public
		 * 
		 */
		static function is_user_authenticate() 
		{
			if (is_user_logged_in())
				return TRUE;
			else
				return FALSE;
		}
		
		/**
		 * Get user login
		 *
		 * @static 
		 * @param int $user_id when isn't received the informations is about the authenticated user
		 * @return string
		 * @access public
		 */
		static function get_user_login($user_id = '') 
		{
			if ($user_id=='')
				$user_id = self::get_current_user_id();
			
			// Call to WP function to get user data
			$user_data = get_userdata($user_id);
			if ($user_data!=NULL)
				return $user_data->user_login;
			else
				return '';
		}
		
		/**
		 * Checks if the administrator is logued
		 *
		 * @static 
		 * @return boolean return TRUE if is administrator, else return FALSE
		 * @access public
		 */
		static function is_administrator_authenticate() 
		{
			/*
			 * Call to WP function to check if current user can Manage the Options in WP. 
			 * This permission is only for administrators.
			 */			
			if (current_user_can('manage_options'))
      		 	return TRUE;
      		else 
      			return FALSE;
		}
		
		/**
		 * Get user name
		 *
		 * @static 
		 * @param int $user_id
		 * @return string
		 * @access public
		 */
		static function get_user_name($user_id='') 
		{
			if ($user_id=='')
				$user_id = self::get_current_user_id();
			
			// Call to WP function to get user data
			$user_data = get_userdata($user_id);
			return $user_data->display_name;
		}
		
		/**
		 * Checks if user is the "admin" user
		 *
		 * @static 
		 * @param int $user_id
		 * @return boolean return TRUE if is administrator, else return FALSE
		 * @access public 
		 */
		static function is_admin_user($user_id='') 
		{
			if ($user_id=='')
				$user_id = self::get_current_user_id();
			
			// Call to WP function to get user data
			$user_data = get_userdata($user_id);
			if ($user_data->user_login=='admin' && $user_id==1)
				return TRUE;
			else
				return FALSE;
		}
		
		/**
		 * Get User ID  
		 * 
		 * @static 
		 * @param string $user_login
		 * @return int
		 * @access public
		 */
		static function get_user_id($user_login) 
		{
			// Call to WP function to get all data from user, or FALSE if any user with that login
			$user_data = get_userdatabylogin($user_login);
			
			return $user_data->ID;
		}		
	}
}