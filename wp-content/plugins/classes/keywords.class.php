<?php
if (!class_exists('WPPostsRateKeys_Keywords')) {
	class WPPostsRateKeys_Keywords
	{
		/**
		 * Get array of text searated by keyword
		 *
		 * @param 	string $keyword
		 * @param 	string $content
		 * @return 	array
		 */
		static function get_pieces_by_keyword($keyword, $content) {
			// To avoid return an array with one value when the keyword isn't in text
			if (!self::keyword_in_content($keyword, $content))
				return array();
			
			// (?<!\pL) and (?!\pL) for match whole keyword, u for unicode, i for case insensitive
		    $keyword = str_replace('/','\/',$keyword);
			$arr = preg_split('/(?<!\pL)' . $keyword . '(?!\pL)/iu', $content);
		    
			return $arr;
		}
		
		/**
		 * Check if keyword in text
		 *
		 * @param 	string $keyword
		 * @param 	string $content
		 * @return 	bool
		 */
		static function keyword_in_content($keyword, $content) {
			$keyword = str_replace('/','\/',$keyword);
			return preg_match('/(?<!\pL)' . $keyword . '(?!\pL)/iu', $content);
		}
		
		/**
		 * Get how many keywords in text
		 *
		 * @param 	string $keyword
		 * @param 	string $content
		 * @return 	bool
		 */
		static function how_many_keywords($keyword, $content) {
			$keyword = str_replace('/','\/',$keyword);
			return preg_match_all('/(?<!\pL)' . $keyword . '(?!\pL)/iu',$content,$matches);
		}
		
		
        /**
		 *
		 * Find position of Nth occurance of search string
		 *
		 * @param string 	$keyword 			The search string
		 * @param string 	$content 			The string to seach
		 * @param int 		$offset 			The Nth occurance of string
		 * @param array		$pieces_by_keyword	The pieces by keyword
		 *
		 * @return int or false if not found
		 *
		 */
		static function strpos_offset($keyword, $content, $offset, $pieces_by_keyword=array())
		{
			if (count($pieces_by_keyword)==0)
		    	$pieces_by_keyword = self::get_pieces_by_keyword($keyword, $content);
		    
		    /*** check the search is not out of bounds ***/
		    switch( $offset )
		    {
		        case $offset == 0:
		        return false;
		        break;
		    
		        case $offset > max(array_keys($pieces_by_keyword)):
		        return false;
		        break;
		
		        default:
		        return strlen(implode($keyword, array_slice($pieces_by_keyword, 0, $offset)));
		    }
		}
	}
}