<?php
if (!class_exists('WPPostsRateKeys_HtmlStyles')) {
	class WPPostsRateKeys_HtmlStyles
	{
	    /**
         * Return all the styles for bold, underline or italic
         * 
         * @return 	array
         * @static 
         */
        static function get_styles() {
        	
        	return array(
        			array('<b>','</b>','bold')
        			, array('<strong>','</strong>','bold')
        			, array('style','font-weight: bold','bold')
        			, array('<i>','</i>','italic')
        			, array('<em>','</em>','italic')
        			, array('style','font-style: italic','italic')
        			, array('<u>','</u>','underline')
        			, array('style','text-decoration: underline','underline')
        			, array('<h1','</h1>','H1') // without > to allow define attributes
        			, array('<h2','</h2>','H2') // without > to allow define attributes
        			, array('<h3','</h3>','H3') // without > to allow define attributes
        		);
        }
        
	    /**
         * Return all the h_styles
         * 
         * @param 	string	$h			can be H1, H2 or H3
         * @return 	array
         * @static 
         */
        static function get_h_styles($h) {
        	$all_styles = self::get_styles();
        	$return = array();
        	
        	foreach ($all_styles as $style) {
        		if ($style[2]==$h)
        			$return[] = $style;
        	}
        	
        	return $return;
        }
        
	    /**
         * Return all the italic_styles
         * 
         * @return 	array
         * @static 
         */
        static function get_italic_styles() {
        	$all_styles = self::get_styles();
        	$return = array();
        	
        	foreach ($all_styles as $style) {
        		if ($style[2]=='italic')
        			$return[] = $style;
        	}
        	
        	return $return;
        }
        
	    /**
         * Return all the bold_styles
         * 
         * @return 	array
         * @static 
         */
        static function get_bold_styles() {
        	$all_styles = self::get_styles();
        	$return = array();
        	
        	foreach ($all_styles as $style) {
        		if ($style[2]=='bold')
        			$return[] = $style;
        	}
        	
        	return $return;
        }
        
	    /**
         * Return all the underline_styles
         * 
         * @return 	array
         * @static 
         */
        static function get_underline_styles() {
        	$all_styles = self::get_styles();
        	$return = array();
        	
        	foreach ($all_styles as $style) {
        		if ($style[2]=='underline')
        			$return[] = $style;
        	}
        	
        	return $return;
        }
        
	    /**
         * Apply the bold HTML style
         * 
         * @param 	string	$keyword
         * @return 	string
         * @static 
         */
        static function apply_bold_styles($keyword) {
        	$settings = WPPostsRateKeys_Central::get_md5_settings(TRUE,$keyword);
        	
        	$design_arr = self::get_bold_styles();
        	$selected_design_id = $settings['bold_style_to_apply'];
        	$selected_design = $design_arr[$selected_design_id];
        	
        	if ($selected_design[0]=='style') {
        		$html_before = '<span style="' . $selected_design[1] . '">'; 
        		$html_after = '</span>';
        	}
        	else {
        		$html_before = $selected_design[0]; 
        		$html_after = $selected_design[1];
        	}
        	
        	return $html_before . $keyword . $html_after;
        }
        
	    /**
         * Apply the italic HTML style
         * 
         * @param 	string	$keyword
         * @return 	string
         * @static 
         */
        static function apply_italic_styles($keyword) {
        	$settings = WPPostsRateKeys_Central::get_md5_settings(TRUE,$keyword);
        	$design_arr = self::get_italic_styles();
        	$selected_design_id = $settings['italic_style_to_apply'];
        	$selected_design = $design_arr[$selected_design_id];
        	
        	if ($selected_design[0]=='style') {
        		$html_before = '<span style="' . $selected_design[1] . '">'; 
        		$html_after = '</span>';
        	}
        	else {
        		$html_before = $selected_design[0]; 
        		$html_after = $selected_design[1];
        	}
        	
        	return $html_before . $keyword . $html_after;
        }
        
	    /**
         * Apply the underline HTML style
         * 
         * @param 	string	$keyword
         * @return 	string
         * @static 
         */
        static function apply_underline_styles($keyword) {
        	$settings = WPPostsRateKeys_Central::get_md5_settings(TRUE,$keyword);
        	$design_arr = self::get_underline_styles();
        	$selected_design_id = $settings['underline_style_to_apply'];
        	$selected_design = $design_arr[$selected_design_id];
        	
        	if ($selected_design[0]=='style') {
        		$html_before = '<span style="' . $selected_design[1] . '">'; 
        		$html_after = '</span>';
        	}
        	else {
        		$html_before = $selected_design[0]; 
        		$html_after = $selected_design[1];
        	}
        	
        	return $html_before . $keyword . $html_after;
        }
        
	    /**
         * Check if has some of the three styles applied
         * 
         * Checks too:
         * - if the keyword is inside a tag
         * - if the keyword is inside a shortcode
         * 
         * @param	string	$content
         * @param	string	$keyword
         * @param	int		$key_pos
         * @return 	bool
         * @static 
         */
        static function if_some_style_or_in_tag_attribute($content,$keyword,$key_pos=1) {
			$pieces = WPPostsRateKeys_Keywords::get_pieces_by_keyword($keyword,$content);
			
			$before_key_pos = $key_pos - 1;
			
			if (count($pieces)>$key_pos) {
				$some_style = self::if_some_style_in_pieces($pieces,$before_key_pos,array(),$keyword);
				if ($some_style) { // If true, so some style is applied
					return $some_style;
				}
				else {
					$in_tag = self::keyword_in_tag_attribute($pieces,$before_key_pos);
					if ($in_tag) {
						return array(TRUE,'in_tag');
					}
					else {
						$in_shortcode = self::keyword_in_shortcode($pieces,$before_key_pos);
						
						if ($in_shortcode)
							return array(TRUE,'in_shortcode');
					}
				}
			}
			
        	return FALSE;
        }
        
        /**
         * Check if has the keyword is in a shortcode
         * 
         * @param	array	$pieces
         * @param	int		$before_key_pos
         * @return 	bool
         * @static 
         */
        static function keyword_in_shortcode($pieces,$before_key_pos) {
        	
        	// Make piece 1 as the join of all pieces before the current keyword to check
        	$piece1 = '';
        	for ($i=0;$i<=$before_key_pos;$i++) {
        		$piece1 .= $pieces[$i];
        	}
        	
        	// Check for keyword inside shortcode
        	$last_less_than = strrpos($piece1,'[');
        	if ($last_less_than!==FALSE) {
        		$last_bigger_than = strrpos($piece1,']');
        		if ($last_bigger_than===FALSE || $last_bigger_than<$last_less_than)
        			return TRUE;
        	}
        	
        	return FALSE;
        }
        
        /**
         * Check if has the keyword is in a tag
         * 
         * @param	array	$pieces
         * @param	int		$before_key_pos
         * @return 	bool
         * @static 
         */
        static function keyword_in_tag_attribute($pieces,$before_key_pos) {
        	
        	// Make piece 1 as the join of all pieces before the current keyword to check
        	$piece1 = '';
        	for ($i=0;$i<=$before_key_pos;$i++) {
        		$piece1 .= $pieces[$i];
        	}
        	
        	// Check for keyword in alt or href attribute
        	$last_less_than = strrpos($piece1,'<');
        	if ($last_less_than!==FALSE) {
        		$last_bigger_than = strrpos($piece1,'>');
        		if ($last_bigger_than===FALSE || $last_bigger_than<$last_less_than)
        			return TRUE;
        	}
        	
        	return FALSE;
        }
        
        
	    /**
         * Get the content of Alt tag
         * 
         * 
         * @param	html	$sub_piece
         * @return 	string	this include the delimiter
         * @static 
         */
        static function get_content_in_alt($sub_piece) {
        	// Have alt tag, check if has the keyword
        	// Get the content of the Alt tag
        	// 		Get from alt to end piece of string 
        	if (substr_count($sub_piece,' alt=')!=0) {
        		$alt_content = substr($sub_piece,strpos($sub_piece,' alt=')+strlen(' alt='));
        	}
        	else {
        		$alt_content = substr($sub_piece,strpos($sub_piece,' alt =')+strlen(' alt ='));
        	}
        	// 		Get until second "'" or '"'
        	//			Find first "'" or '"'
        	$first_simple = strpos($alt_content,"'");
        	$first_double = strpos($alt_content,'"');
        	
        	if ($first_simple!==FALSE && $first_double!==FALSE) {
        		if ($first_simple<$first_double) {
        			$use_to_delimit = "'";
        			$first_pos = $first_simple;
        		}
				else {
        			$use_to_delimit = '"';
        			$first_pos = $first_double;
				}
        	}
        	elseif ($first_simple!==FALSE) {
        		$use_to_delimit = "'";
        		$first_pos = $first_simple;
        	}
        	else {
        		$use_to_delimit = '"';
        		$first_pos = $first_double;
        	}
        	// Get second position
        	$second_pos = strpos($alt_content,$use_to_delimit,$first_pos+1);
        	// Get text inside the Alt tag, without " or ' delimiter
        	$inside_alt_tag = substr($alt_content,$first_pos,$second_pos-$first_pos+2); // +2 to include the final delimiter
        	
        	return $inside_alt_tag;
        }
        
	    /**
         * If user select to force it: automatic add_rel_nofollow_external_links
         * 
         * @param	html	$content
         * @return 	html
         * @static 
         */
        static function add_rel_nofollow_external_links($content) {
        	$settings = WPPostsRateKeys_Central::get_md5_settings(TRUE,'some_key');
        	$wp_url = get_bloginfo('wpurl');
        	
        	$wp_url_clean = str_replace('http://www.','',$wp_url);
        	$wp_url_clean = str_replace('https://www.','',$wp_url_clean);
        	$wp_url_clean = str_replace('https://','',$wp_url_clean);
        	$wp_url_clean = str_replace('http://','',$wp_url_clean);
        	
        	if ($settings['allow_automatic_adding_rel_nofollow']) {
	        	// Go through all links tags and check if is external with do follow, then add the nofollow 
	        	$matches = array();
	        	
	        	preg_match_all('/<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU',$content,$matches);
	        	
	        	// In $matches[0] stores the whole tag a, in $matches[1] stores the href URLs
	        	$index = 0;
	        	foreach ($matches[0] as $tags) {
	        		$url = $matches[1][$index];
	        		
	        		// Check if is external
	        		$is_external = FALSE;
	        		
	        		// Clean from http://www. and http://
	        		$url_clean = str_replace('http://www.','',$url);
	        		$url_clean = str_replace('https://www.','',$url_clean);
	        		$url_clean = str_replace('https://','',$url_clean);
	        		$url_clean = str_replace('http://','',$url_clean);
	        		
	        		if ((strpos($url,'http://')===0 || strpos($url,'https://')===0) && strpos($url_clean,$wp_url_clean)!==0) // Url of code begins with https:// or http://
        				$is_external = TRUE;
	        		
	        		// Check if is do follow
	        		$is_dofollow = FALSE;
	        		if (substr_count($tags,'rel="nofollow"')==0
	        			&& substr_count($tags,'rel=nofollow')==0
	        			&& substr_count($tags,'rel="no follow"')==0
	        			)
	        			$is_dofollow = TRUE;
	        		
	        		if ($is_external && $is_dofollow) {
	        			// Add rel="nofollow" attribute
	        			
	        			$old_a_tag = $tags;
	        			$new_tag = str_replace('<a ','<a rel="nofollow" ',$old_a_tag);
	        			
	        			// Replace in content Old tag with New tag
	        			$content = str_replace($old_a_tag,$new_tag,$content);
	        		}
	        			
	        		$index++;
	        	} 
        	}
        	
        	return $content;
        }
        
	    /**
         * If user select to force it: automatic adding of alt=keyword
         * 
         * to all images in the content that do not have alt tags 
         * 
         * @param	html	$new_content
         * @param	string	$post_keyword
         * @return 	html
         * @static 
         */
        static function add_keyword_in_alt($new_content, $post_keyword) {
        	$settings = WPPostsRateKeys_Central::get_md5_settings(TRUE,$post_keyword);
        	
        	if ($settings['allow_automatic_adding_alt_keyword']) {
        		
        		// explode the string by <img begginin tag
        		$str_arr = explode('<img',$new_content);
        		$new_str_arr = array();
        		
        		for ($i=0;$i<count($str_arr);$i++) { 
        			if ($i==0) // Ignore the first piece of html because there isn't no <img tag
        				$new_str_arr[] = $str_arr[$i];
        			else {
        				$piece = $str_arr[$i];
        				
        				$pos_bigger_than = strpos($piece,'>'); // Finding the next >, is the one that close the <img tag
        				if ($pos_bigger_than) {
	        				// Check if between the beginning of the $piece up to the next > possition is an alt tag
	        				$sub_piece = substr($piece,0,$pos_bigger_than);
	        				
	        				if (substr_count($sub_piece,' alt=')==0
	        					&& substr_count($sub_piece,' alt =')==0
	        					) { // haven't alt tag
	        					
	        					$piece = ' alt="' . $post_keyword . '"' . $piece;
	        				}
	        				else {
	        					// Check if has alt tag but is empty
	        					$inside_alt_tag = self::get_content_in_alt($sub_piece);
	        					if (trim($inside_alt_tag,"'\" ")==''){
	        						// replace old tag value with new one
	        						$piece = str_ireplace(" alt=$inside_alt_tag",' alt="' . $post_keyword . '"',$piece);
	        					}
	        				}
        				}
        				
        				$new_str_arr[] = $piece;
        			}
        		}
        		
        		return join('<img', $new_str_arr);
        		
        	}
        	
        	return $new_content;
        }
        
	    /**
         * Check if has some of the three styles applied to the pieces of code
         * 
         * @param	array	$pieces
         * @param	int		$index				keyword possition
         * @param	array	$arrays_to_check
         * @param	string	$keyword
         * @return 	bool|array
         * @static 
         */
        static function if_some_style_in_pieces($pieces, $index, $arrays_to_check=array(), $keyword) {
        	// determine both parts of texts
        	$piece1 = '';
	        $piece2 = '';
        	for ($i=0;$i<count($pieces);$i++) {
        		if ($i<=$index) {
        			if ($piece1=='')
        				$piece1 .= $pieces[$i];
					else
        				$piece1 .= $keyword . $pieces[$i];
        		}
        		else {
        			if ($piece2=='')
        				$piece2 .= $pieces[$i];
					else
        				$piece2 .= $keyword . $pieces[$i];
        		}
        	}
        	
        	// Check in the already defined style arrays
        	if (count($arrays_to_check)==0)
        		$arrays_to_check = self::get_styles();
        	
        	foreach ($arrays_to_check as $to_check) {
        		if ($to_check[0]!='style') {
        			$begin_with = FALSE;
        			$end_with = FALSE;
        			
        			if (strpos($to_check[2],'H')===0) {
        				// The Checks for H1, H2 and H3 are different, this tags can have other tags inside
        				
        				// Check if begin with
        				$first_open_tag_left = strripos($piece1,$to_check[0]);
        				$first_close_tag_left = strripos($piece1,$to_check[1]);
        				/*
        				 * check in the left
        				 * If: has at least one <h1
        				 * and: 
        				 * 		haven't </h1>
        				 * 		or
        				 * 		have </h1> before <h1>
        				 */
        				if ($first_open_tag_left!==FALSE) {
	        				if (
	        				$first_close_tag_left===FALSE
	        				|| ($first_close_tag_left!==FALSE && $first_close_tag_left < $first_open_tag_left)
	        				)
	        					$begin_with = TRUE;
	        			}
        				// Check if end with
        				$first_open_tag_right = stripos($piece2,$to_check[0]);
        				$first_close_tag_right = stripos($piece2,$to_check[1]);
        				/*
        				 * check in the right
        				 * If: has at least one </h1
        				 * and: 
        				 * 		haven't <h1>
        				 * 		or
        				 * 		have </h1> before <h1>
        				 */
        				if ($first_close_tag_right!==FALSE) {
	        				if (
	        				$first_open_tag_right===FALSE
	        				|| ($first_open_tag_right!==FALSE && $first_close_tag_right < $first_open_tag_right)
	        				)
	        					$end_with = TRUE;
	        			}
        			}
        			else {
	        			// Check if begins with
	        			// Determine the position (from rigth to left) of the "<"
	        			$first_back_less = strripos($piece1,'<'); 
	        			// Determine the position (from rigth to left) of the current tag to check
	        			$first_back_tag = strripos($piece1,$to_check[0]);
	
	        			if ($first_back_less!==FALSE && $first_back_tag!==FALSE) {
	        				if ($first_back_less == $first_back_tag)
	        					$begin_with = TRUE;
	        			}
	
	        			// Check if ends with
	        			// Determine the position (from left to rigth) of the "<"
	        			$first_less = stripos($piece2,'<'); 
	        			// Determine the position (from left to rigth) of the current tag to check
	        			$first_tag = stripos($piece2,$to_check[1]);
	
	        			if ($first_less!==FALSE && $first_tag!==FALSE) {
	        				if ($first_less == $first_tag)
	        					$end_with = TRUE;
	        			}
        			}
        				
        			if ($begin_with && $end_with)
        				return array(TRUE,$to_check[2]);
        		}
        		else {
        			// 
        			/*
        			 * Some tag with style="$to_check[1]"
        			 * example:
        			 * <span style="font-weight: bold;">keyword</span>
        			 * <p style="some: some; font-style: italic" id="some">keyword</span>
        			 * <span style="text-decoration: underline;">keyword</span>
        			 */
        			
        			$strpos_1 = strrpos($piece1,'<');
	        		$strpos_2 = strrpos($piece1,'>');
        			
        			$sub_str = substr($piece1,$strpos_1,$strpos_2-$strpos_1);
        			
        			if (substr_count($sub_str,$to_check[1])>0)
        				return array(TRUE,$to_check[2]);
        		}
        	}
        	
        	return FALSE;        	
        }
	}
}