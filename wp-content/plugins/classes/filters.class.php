<?php
if (!class_exists('WPPostsRateKeys_Filters')) {
	class WPPostsRateKeys_Filters {
		
		/**
         * 
         * Filter the POST title
         * 
         * @param	string	$title
         * @param	string	$keyword
         * @param	string	$settings
         * @return 	string
         */
        static function filter_post_title($title, $keyword, $settings) {
        	
        	if ($keyword=='')
        		return $title;
        	
        	// If setting allow plugin to add keyword, add if isn't already
        	if (($settings['allow_add_keyword_in_titles']) && !WPPostsRateKeys_Keywords::keyword_in_content($keyword,$title)) {
        		// Add keyword behind the TITLE, like this: title | keyword
        		$new_title = $title . ' | ' . $keyword;
        		
        		return $new_title;
        	}
        	else 
        		return $title;
		}
		
        /**
         * Apply bold, italic and underline to a content
         * 
         * Just once for every typeface is enough. If there is less than 3 occurrences,
         * then, priority to bold, then italize, then underline.
         * 
         * @param 	string		$content
         * @param 	string		$keyword
         * @return 	string
         * @access 	public
         */
        function apply_biu_to_content($content, $keyword) {
        	$settings = WPPostsRateKeys_Central::get_md5_settings(TRUE,$keyword);
        	
        	$new_content = $content;
        	
        	/*
        	 * Check for this $already_apply_... because: 
        	 * if the first keyword is italized, second is underlined,
        	 * then, we will find the third to bold. If there is no third keyword, then no bold face
        	 * 
        	 */
        	if ($settings['allow_bold_style_to_apply'])
        		$already_apply_bold = FALSE;
			else
        		$already_apply_bold = TRUE;
        	if ($settings['allow_italic_style_to_apply'])
        		$already_apply_italic = FALSE;
			else
        		$already_apply_italic = TRUE;      	
        	if ($settings['allow_underline_style_to_apply'])
        		$already_apply_underline = FALSE;
			else
        		$already_apply_underline = TRUE;

        	// Pass through all keyword until ends or until are applied all designs
        	$how_many_keys = WPPostsRateKeys_Keywords::how_many_keywords($keyword, $new_content);
        	
        	
        	// To avoid make the request for each keyword: Get pieces by keyword for determine if some has the design applied
        	$pieces_by_keyword = WPPostsRateKeys_Keywords::get_pieces_by_keyword($keyword, $new_content);
        	// First, only check for designs already applied
        	for ($i=1;$i<=$how_many_keys;$i++) {
        		
        		// Stop if are already all the design applied
	        	if ($already_apply_bold && $already_apply_italic && $already_apply_underline)
	        		break;
        		
        		// Getting the position
        		$key_pos = WPPostsRateKeys_Keywords::strpos_offset($keyword,$new_content,$i,$pieces_by_keyword);
        		
        		if ($key_pos!==FALSE) {
	        		$already_style = WPPostsRateKeys_HtmlStyles::if_some_style_or_in_tag_attribute($new_content,$keyword,$i);
	        		
	        		if ($already_style) {
	        			if ($already_style[1] == 'bold')
	        				$already_apply_bold = TRUE;
	        			elseif ($already_style[1] == 'italic')
	        				$already_apply_italic = TRUE;
	        			elseif ($already_style[1] == 'underline')
	        				$already_apply_underline = TRUE;
	        		}
	        	}
        	}
        	
        	// Apply designs pendings to apply
        	for ($i=1;$i<=$how_many_keys;$i++) {
        		
        		// Stop if are already all the design applied
	        	if ($already_apply_bold && $already_apply_italic && $already_apply_underline)
	        		break;
        		
        		// Getting the position. Here can't be calculate one time ($pieces_by_keyword) and rehuse it because the content changes
        		$key_pos = WPPostsRateKeys_Keywords::strpos_offset($keyword,$new_content,$i);
        		
        		// Getting this text of keyword, allow us to be aware if a Keyword has any upper case
        		$text_replace = substr($new_content,$key_pos,strlen($keyword));
        		
        		if ($key_pos!==FALSE) {
	        		$already_style = WPPostsRateKeys_HtmlStyles::if_some_style_or_in_tag_attribute($new_content,$keyword,$i);
	        		
	        		if ($already_style) {
	        			if ($already_style[1] == 'bold')
	        				$already_apply_bold = TRUE;
	        			elseif ($already_style[1] == 'italic')
	        				$already_apply_italic = TRUE;
	        			elseif ($already_style[1] == 'underline')
	        				$already_apply_underline = TRUE;
	        		}
	        		else {
		        		if (!$already_apply_bold) {
		        			$keyword_with_style = WPPostsRateKeys_HtmlStyles::apply_bold_styles($text_replace);
			        		$already_apply_bold = TRUE;
		        		}
		        		elseif (!$already_apply_italic) {
		        			$keyword_with_style = WPPostsRateKeys_HtmlStyles::apply_italic_styles($text_replace);
			        		$already_apply_italic = TRUE;
		        		}
		        		elseif (!$already_apply_underline) {
		        			$keyword_with_style = WPPostsRateKeys_HtmlStyles::apply_underline_styles($text_replace);
			        		$already_apply_underline = TRUE;
		        		}
		        		
		        		$new_content = substr_replace($new_content,$keyword_with_style
		        							,$key_pos,strlen($keyword));
		        							
		        		// Calculate how many keyword, because in case the keyword is, for example "b" this value will change 
		        		$how_many_keys = WPPostsRateKeys_Keywords::how_many_keywords($keyword, $new_content);
	        		}
	        	}
        	}
        	
        	return $new_content;
        }

		/**
         * 
         * Filter the POST content
         * 
         * @param	string	$keyword
         * @param	string	$content
         * @param	string	$settings
         * @return 	string
         * @access 	public
         */
        static function filter_post_content($keyword, $content, $settings) {
        	
        	$new_content = $content;
	        
        	if ($keyword!='') { // Only apply global keywords, if keyword isn't specified
        		$new_content = self::apply_biu_to_content($new_content, $keyword);
	        	
	        	// Only if keyword is defined: Add alt="keyword" for <img /> tags that haven't it
	        	$new_content = WPPostsRateKeys_HtmlStyles::add_keyword_in_alt($new_content, $keyword);
        	
        	}
        	
        	// Add of rel="nofollow" to external links
        	$new_content = WPPostsRateKeys_HtmlStyles::add_rel_nofollow_external_links($new_content);
        	
        	return $new_content;
		}
	}
}