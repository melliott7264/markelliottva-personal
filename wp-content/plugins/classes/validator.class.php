<?php
if (!class_exists('WPPostsRateKeys_Validator')) {
	class WPPostsRateKeys_Validator
	{
		/**
		 * Parse a data to be shown in templates
		 *
		 * @static 
		 * @param 	string 	$string	text to be parsed
		 * @return 	string
		 * @access 	public
		 */
		static function parse_output($string) {
	    	return stripcslashes($string);
	    }
	    
		/**
		 * Parse an array of data to be shown in templates
		 *
		 * @static 
		 * @param array $array with text to be parsed
		 * @return string
		 * @access public
		 */
		static function parse_array_output($array) {
			// TODO invest, if ( get_magic_quotes_gpc() )
	    	return stripslashes_deep($array);
	    }
	}
}