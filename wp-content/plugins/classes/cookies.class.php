<?php
if (!class_exists('WPPostsRateKeys_Cookies'))
{
	class WPPostsRateKeys_Cookies
	{
		/**
	     * The name of the cookie for "page size" pagination
	     *
	     * @var string
	     */
        static $cookie_pagination_page_size = 'seopressor_page_size';
        
		/**
	     * The name of the list of cookie to add in next page reload
	     *
	     * @var string
	     */
        static $cookie_list_to_add = 'seopressor_list_to_add';
        
        
		/**
		 * Store the cookie value to save it in next page load
		 *
		 * @param string $name
		 * @param string $value
		 * @static 
		 * @return void
		 */
		static function add_cookie($name,$value)
		{
			@session_start();
			$_SESSION[ self::$cookie_list_to_add ][$name] = $value;
		}
		
		/**
		 * Save the cookies stored before any output
		 *
		 * @static 
		 * @return void
		 */
		static function save_cookies_pending_to_save()
		{
			@session_start();
			if (isset($_SESSION[ self::$cookie_list_to_add ])) {
				foreach ($_SESSION[ self::$cookie_list_to_add ] as $name => $value) {
					@setcookie( $name , $value, time()+60*60*24*30);
				}
			}	
		}
		
	}
}