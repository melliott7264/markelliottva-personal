<?php
if (!class_exists('WPPostsRateKeys_ContentRate')) {
	class WPPostsRateKeys_ContentRate
	{
        /**
         * Function to the get all the POST data
         * 
         * This return:
         *  the Scrore in percet
         *  the Suggestions for the page 
         *  and the Suggestion box data 
         * 
         * @param	int		$post_id
         * @param	string	$keyword
         * @param	string	$new_content	is the filtered content
         * @param	string	$filtered_title
         * @param	array	$settings
         * @param	string	$content		is the original Post content, stored as Post meta data
         * @return 	array
         * @static 
         */
        static function get_all_post_data($post_id,$keyword,$new_content,$filtered_title
        									,$settings,$content) {
        	
        	$total_score = 0;
        	$box_suggestions = array('box_keyword_density'=>0,'box_suggestions_arr'=>array());
        	$suggestions_arr = array();
        	
        	if ($keyword!='') { // Only checks if is some keyword defined
        		
        		/*
        		 * Making general checks
        		 */
        		
        		// Check for: Is the Keyword bold
	        	$is_keyword_bold = self::is_keyword_bold($new_content, $keyword);
	        	// Check for: Is the Keyword italized
	        	$is_keyword_italized = self::is_keyword_italized($new_content, $keyword);
	        	// Check for: Is the Keyword underlined
	        	$is_keyword_underlined = self::is_keyword_underlined($new_content, $keyword);
	        	// Check for: Keyword Density Pointer
	        	$keyword_density_pointer = self::get_keyword_density_pointer($new_content, $keyword);
	        	// Check for: Post Word Count
	        	$post_word_count = self::get_post_word_count($new_content);
	        	// Check for: Keyword in the Title
	        	$keyword_in_title = WPPostsRateKeys_Keywords::keyword_in_content($keyword,$filtered_title);
	        	// Check for: Keyword in H1 Tag
	        	if ($settings['h1_tag_already_in_theme']==1 && $keyword_in_title) { // Check first if user set this as already done by theme and the keyword is in title
	        		$keyword_inside_some_h1 = TRUE;
	        	}
	        	else { // Search in content
	        		$keyword_inside_some_h1 = self::keyword_inside_some_h($keyword,$new_content,$filtered_title,'H1');
	        	}
	        	// Check for: Keyword in H2 Tag
	        	if ($settings['h2_tag_already_in_theme']==1 && $keyword_in_title) { // Check first if user set this as already done by theme and the keyword is in title
	        		$keyword_inside_some_h2 = TRUE;
	        	}
	        	else { // Search in content
	        		$keyword_inside_some_h2 = self::keyword_inside_some_h($keyword,$new_content,$filtered_title,'H2');
	        	}
	        	// Check for: Keyword in H3 Tag
	        	// Check for: Keyword in H2 Tag
	        	if ($settings['h3_tag_already_in_theme']==1 && $keyword_in_title) { // Check first if user set this as already done by theme and the keyword is in title
	        		$keyword_inside_some_h3 = TRUE;
	        	}
	        	else { // Search in content
	        		$keyword_inside_some_h3 = self::keyword_inside_some_h($keyword,$new_content,$filtered_title,'H3');
	        	}
	        	// Check for: Keyword in the First Sentence
	        	$keyword_in_first_sentence = self::keyword_in_first_sentence($keyword,$new_content);
	        	// Check for: Keyword in the Last Sentence
	        	$keyword_in_last_sentence = self::keyword_in_last_sentence($keyword,$new_content);
	        	// Check for: Keyword at Beginning of the Post in First sentence
	        	$keyword_at_beginning = self::keyword_at_beginning($keyword,$new_content);
	        	// Check for: Image Alt Text has Keyword
	        	$image_alt_text_has_keyword = self::image_alt_text_has_keyword($keyword,$new_content);
	        	// Check for: Post have outgoing link to external sites with do-follow
	        	$link_external_do_follow = self::link_external_do_follow($new_content);
	        	// Check for: Link to Internal Pages with Keyword as Anchor Text
	        	$link_internal_as_anchor = self::link_internal_as_anchor($keyword,$new_content);
	        	// Check for: Link to External Pages with Keyword as Anchor Text
	        	$link_external_as_anchor = self::link_external_as_anchor($keyword,$new_content);
	        	
	        	/*
	        	 ******************** Score calculations
	        	 */
	        	// Check for: Is the Keyword bold
	        	if ($is_keyword_bold)
	        		$total_score += 6;
	        	else 
	        		$total_score -= 1;
	        	
	        	// Check for: Is the Keyword italized
	        	if ($is_keyword_italized)
	        		$total_score += 4;
	        		
	        	// Check for: Is the Keyword underlined
	        	if ($is_keyword_underlined)
	        		$total_score += 3;
	        		
	        	// Check for: Keyword Density Pointer
	        	if ($keyword_density_pointer<1)
	        		$total_score -= 1;
	        	elseif ($keyword_density_pointer>=1 && $keyword_density_pointer<2)
	        		$total_score += 2;
	        	elseif ($keyword_density_pointer>=2 && $keyword_density_pointer<5)
	        		$total_score += 5;
	        	elseif ($keyword_density_pointer>=5 && $keyword_density_pointer<=6)
	        		$total_score += 3;
	        	elseif ($keyword_density_pointer>6)
	        		$total_score -= 2;

	        	// Check for: Post Word Count
	        	if ($post_word_count<=200)
	        		$total_score -= 1;
	        	elseif ($post_word_count>=350 && $post_word_count<500)
	        		$total_score += 2;
	        	elseif ($post_word_count>=500 && $post_word_count<=700)
	        		$total_score += 4;
	        	elseif ($post_word_count>700)
	        		$total_score += 6;
	        	
	        	// Check for: Keyword in the Title
	        	if ($keyword_in_title)
	        		$total_score += 11;
	        	else 
	        		$total_score -= 2;
        		
	        	// Check for: Keyword in H1 Tag
	        	if ($keyword_inside_some_h1)
	        		$total_score += 8;
	        	else 
	        		$total_score -= 1;
        		
	        	// Check for: Keyword in H2 Tag
	        	if ($keyword_inside_some_h2)
	        		$total_score += 7;
	        	else 
	        		$total_score -= 1;
        		
	        	// Check for: Keyword in H3 Tag
	        	if ($keyword_inside_some_h3)
	        		$total_score += 5;
	        		
	        	// Check for: Keyword in the First Sentence
	        	if ($keyword_in_first_sentence)
	        		$total_score += 5;
	        	else 
	        		$total_score -= 1;
	        		
	        	// Check for: Keyword in the Last Sentence
	        	if ($keyword_in_last_sentence)
	        		$total_score += 4;
	        		
	        	// Check for: Keyword at Beginning of the Post in First sentence
	        	if ($keyword_at_beginning)
	        		$total_score += 2;
	        		
	        	// Check for: Image Alt Text has Keyword
	        	if ($image_alt_text_has_keyword)
	        		$total_score += 5;
	        	else 
	        		$total_score -= 1;
	        		
	        	// Check for: Post have outgoing link to external sites with do-follow
	        	if ($link_external_do_follow)
	        		$total_score -= 3;
	        		
	        	// Check for: Link to Internal Pages with Keyword as Anchor Text
	        	if ($link_internal_as_anchor)
	        		$total_score += 3;
	        		
	        	// Check for: Link to External Pages with Keyword as Anchor Text
	        	if ($link_external_as_anchor)
	        		$total_score += 3;
	        	
        		$total_score = number_format(($total_score+13)/90*100, 2);
	        		
	        	/*
	        	 ******************* Suggestions Box
	        	 */
        		// Get keyword density, Set with only two decimal numbers
				if (is_float($keyword_density_pointer)) {
					$box_keyword_density = number_format($keyword_density_pointer,2);
				}
				else {
					$box_keyword_density = $keyword_density_pointer;
				}
				// Get suggestions
				$box_suggestions_arr = array();
				/*
	        	 * Check for:
	        	 * - YES: You have H1 tag containing your keyword
	        	 * - NO: You do not have H1 tag containing your keyword
	        	 * 
	        	 * - YES: You have H2 tag containing your keyword
	        	 * - NO: You do not have H2 tag containing your keyword
	        	 * 
	        	 * - YES: You have H3 tag containing your keyword
	        	 * - NO: You do not have H3 tag containing your keyword
	        	 */	
	        	// Check for: Keyword in H1 Tag
	        	if (!$keyword_inside_some_h1)
	        		$box_suggestions_arr[] = array(0,'msg_1');
	        	else 
	        		$box_suggestions_arr[] = array(1,'msg_2');
	        	
	        	// Check for: Keyword in H2 Tag
	        	if (!$keyword_inside_some_h2)
	        		$box_suggestions_arr[] = array(0,'msg_3');
	        	else 
	        		$box_suggestions_arr[] = array(1,'msg_4');
	        	
	        	// Check for: Keyword in H3 Tag
	        	if (!$keyword_inside_some_h3)
	        		$box_suggestions_arr[] = array(0,'msg_5');
	        	else 
	        		$box_suggestions_arr[] = array(1,'msg_6');
	        		
	        	/*
	        	 * Check for:
	        	 * - YES: SEOPressor will automatically bold your keyword
	        	 * - YES: SEOPressor will automatically underline your keyword
	        	 * - YES: SEOPressor will automatically italic your keyword
	        	 * (show these 3 when there is a keyword for SEOPressor to decorate)
	        	 * 
	        	 * - NO: You do not have enough keywords to bold
	        	 * - NO: You do not have enough keywords to italize
	        	 * - NO: You do not have enough keywords to underline
	        	 */
	        	// First determine which design could be apply to the original content
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
	        	
	        	if (!($already_apply_bold && $already_apply_italic && $already_apply_underline)) {
		        	// Pass through all keyword until ends or until are applied all designs
		        	$how_many_keys = WPPostsRateKeys_Keywords::how_many_keywords($keyword, $content);
		        	$how_many_keys_to_decorate = 0;
		        	// First, only check for designs already applied
		        	for ($i=1;$i<=$how_many_keys;$i++) {
		        		
		        		// Getting the position
		        		$key_pos = WPPostsRateKeys_Keywords::strpos_offset($keyword,$content,$i);
		        		
		        		if ($key_pos!==FALSE) {
			        		$already_style = WPPostsRateKeys_HtmlStyles::if_some_style_or_in_tag_attribute($content,$keyword,$i);
			        		
			        		if ($already_style) {
			        			if ($already_style[1] == 'bold')
			        				$already_apply_bold = TRUE;
			        			elseif ($already_style[1] == 'italic')
			        				$already_apply_italic = TRUE;
			        			elseif ($already_style[1] == 'underline')
			        				$already_apply_underline = TRUE;
			        		}
			        		else 
			        			$how_many_keys_to_decorate++;
			        	}
		        	}
	        	}
	        	
	        	if ($settings['allow_bold_style_to_apply']) {
		        	if (!$already_apply_bold && $how_many_keys_to_decorate>0) { // At least one
		        		$box_suggestions_arr[] = array(1,'msg_7');
		        		$how_many_keys_to_decorate--; // Minus one, because is already counted
		        	}
		        	elseif (!$already_apply_bold) // If has keywords to apply design, but Bold is already apply (no applied by SeoPressor)
		        		$box_suggestions_arr[] = array(0,'msg_8');
	        	}
	        	
	        	if ($settings['allow_italic_style_to_apply']) {
		        	if (!$already_apply_italic && $how_many_keys_to_decorate>0) {
		        		$box_suggestions_arr[] = array(1,'msg_9');
		        		$how_many_keys_to_decorate--; // Minus one, because is already counted
		        	}
		        	elseif (!$already_apply_italic) // If has keywords to apply design, but Italic is already apply
		        		$box_suggestions_arr[] = array(0,'msg_10');
	        	}
	        	
	        	if ($settings['allow_underline_style_to_apply']) {
		        	if (!$already_apply_underline && $how_many_keys_to_decorate>0)
		        		$box_suggestions_arr[] = array(1,'msg_11');
		        	elseif (!$already_apply_underline) // If has keywords to apply design, but Underline is already apply
		        		$box_suggestions_arr[] = array(0,'msg_12');
		        }
		        
		        /*
		         * If check in settings:
		         * 		- YES: You have an image, SEOPressor will automatically add ALT tag to it
		         * (if image has no ALT tag. If image has ALT tag and not set to keyword, show warning)
		         * 		- NO: You do not have an image for SEOPressor to add ALT tag
		         * 		- NO: You do not have ALT tag set to your keyword (if ALT != keyword)
		         * 
		         * If not check in settings:
		         * 	if image:
		         * 		- YES: You have have ALT tag set to your keyword.
		         * 		- NO: You do not have ALT tag set to your keyword.
		         * 	else:
		         * 		- NO: You need to have an image with ALT tag set to your keyword
		         */
		        
		        $str_arr = explode('<img',$content);
		        if (count($str_arr)>1) { // At least one image
		        		$at_least_one_to_add_alt = FALSE;
		        		$at_least_one_with_alt_no_keyword = FALSE;
		        		$at_least_one_with_alt_as_keyword = FALSE;
		        		for ($i=0;$i<count($str_arr);$i++) {
		        			if ($i!=0) { // Ignore the first piece of html because there isn't no <img tag
		        				$piece = $str_arr[$i];
		        				
		        				$pos_bigger_than = strpos($piece,'>'); // Finding the next >, is the one that close the <img tag
		        				if ($pos_bigger_than) {
			        				// Check if between the beginning of the $piece up to the next > possition is an alt tag
			        				$sub_piece = substr($piece,0,$pos_bigger_than);
			        				
			        				if (substr_count($sub_piece,' alt=')==0
			        					&& substr_count($sub_piece,' alt =')==0
			        					) { // haven't alt tag
			        					
			        					$at_least_one_to_add_alt = TRUE;
			        					break;
			        				}
			        				else {
			        					$inside_alt_tag = WPPostsRateKeys_HtmlStyles::get_content_in_alt($sub_piece);
			        					
			        					if (! WPPostsRateKeys_Keywords::keyword_in_content($keyword, $inside_alt_tag)) {
			        						// This can happends when is empty (plugin will add it) or when haven't the keyword
					        				if (trim($inside_alt_tag,"'\" ")=='')
				        						$at_least_one_to_add_alt = TRUE;
				        					else
			        							$at_least_one_with_alt_no_keyword = TRUE;
			        					}
			        					else 
			        						$at_least_one_with_alt_as_keyword = TRUE;
			        				}
		        				}
		        			}
		        		}
		        		
		        		if ($settings['allow_automatic_adding_alt_keyword']) {
			        		if ($at_least_one_to_add_alt) {
			        			$box_suggestions_arr[] = array(1,'msg_13');
			        		}
			        		elseif (!$at_least_one_with_alt_as_keyword) {
			        			// will be show when at least one image set the ALT to something different to Keyword
			        			$box_suggestions_arr[] = array(0,'msg_14');
			        		}
		        		}
		        		else {
		        			if ($at_least_one_with_alt_as_keyword) {
			        			$box_suggestions_arr[] = array(1,'msg_15');
			        		}
			        		else {
			        			$box_suggestions_arr[] = array(0,'msg_16');
			        		}
		        		}
		        		
		        	}
		        	else {
		        		// None image
		        		if ($settings['allow_automatic_adding_alt_keyword']) {
		        			$box_suggestions_arr[] = array(0,'msg_17');
		        		}
		        		else {
		        			$box_suggestions_arr[] = array(0,'msg_18');
			        	}
		        	}
		        
	        	/*
	        	 * - NO: More words needed.
	        	 * - NO: You do not have keyword in the first sentence.
	        	 * - NO: You do not have keyword in the last sentence.
	        	 * - NO: You do not have an internal link to your other pages.
	        	 * - NO: Please add rel=nofollow to your external links.
	        	 */
	        	// Check for: Post Word Count
		        if ($post_word_count<200)
		        	$box_suggestions_arr[] = array(0,'msg_19');
		        // Check for: Keyword in the First Sentence
		        if (!self::keyword_in_first_sentence($keyword,$new_content))
		        	$box_suggestions_arr[] = array(0,'msg_20');
		        // Check for: Keyword in the Last Sentence
		        if (!self::keyword_in_last_sentence($keyword,$new_content))
		        	$box_suggestions_arr[] = array(0,'msg_21');
		        // Check for: has_link_internal
		        if (!self::has_link_internal($keyword,$new_content))
		        	$box_suggestions_arr[] = array(0,'msg_22');
		        // Check for: Post have outgoing link to external sites with do-follow
		        if (self::link_external_do_follow($new_content))
		        	$box_suggestions_arr[] = array(0,'msg_23');
        		
		        $box_suggestions = array('box_keyword_density'=>$box_keyword_density,'box_suggestions_arr'=>$box_suggestions_arr);
		        
		        /*
	        	 ************************ Suggestions page
	        	 */
	        	// Check for: Is the Keyword bold
	        	if (!$is_keyword_bold && $settings['allow_bold_style_to_apply'])
	        		$suggestions_arr[] = 'msg_1';
	        		
	        	// Check for: Is the Keyword italized
	        	if (!$is_keyword_italized && $settings['allow_italic_style_to_apply'])
	        		$suggestions_arr[] = 'msg_2';
	        		
	        	// Check for: Is the Keyword underlined
	        	if (!$is_keyword_underlined && $settings['allow_underline_style_to_apply'])
	        		$suggestions_arr[] = 'msg_3';
	        		
	        	// Check for: Keyword Density Pointer
	        	if ($keyword_density_pointer<1)
	        		$suggestions_arr[] = 'msg_4';
	        	elseif ($keyword_density_pointer>6)
	        		$suggestions_arr[] = 'msg_5';

	        	// Check for: Post Word Count
	        	if ($post_word_count<200)
	        		$suggestions_arr[] = 'msg_6';
	        	
	        	// Check for: Keyword in the Title
	        	if (!$keyword_in_title)
	        		$suggestions_arr[] = 'msg_7';
        		
	        	// Check for: Keyword in H1 Tag
	        	if (!$keyword_inside_some_h1)
	        		$suggestions_arr[] = 'msg_8';
        		
	        	// Check for: Keyword in H2 Tag
	        	if (!$keyword_inside_some_h2)
	        		$suggestions_arr[] = 'msg_9';
        		
	        	// Check for: Keyword in H3 Tag
	        	if (!$keyword_inside_some_h3)
	        		$suggestions_arr[] = 'msg_10';
	        		
	        	// Check for: Keyword in the First Sentence
	        	if (!$keyword_in_first_sentence)
	        		$suggestions_arr[] = 'msg_11';
	        		
	        	// Check for: Image Alt Text has Keyword
	        	if (!$image_alt_text_has_keyword && $settings['allow_automatic_adding_alt_keyword'])
	        		$suggestions_arr[] = 'msg_12';
	        		
	        	// Check for: Post have outgoing link to external sites with do-follow
	        	if (self::link_external_do_follow($new_content))
	        		$suggestions_arr[] = 'msg_13';
	        		
	        	// Check for: Link to Internal Pages with Keyword as Anchor Text
	        	if (!$link_internal_as_anchor)
	        		$suggestions_arr[] = 'msg_14';
	        		
	        	if (count($suggestions_arr)==0)
        			$suggestions_arr[] = 'msg_15';
	        	
        	}
        	else {
        		// Set suggestion to show when none keyword is specified
        		$suggestions_arr[] = 'msg_16';
        	}
        	
        	return array($total_score, $box_suggestions, $suggestions_arr);
        }
        
        /**
         * Function to check if: Link to External Pages with Keyword as Anchor Text
         * 
         * @param 	string	$keyword
         * @param 	string	$content
         * @return 	bool
         * @static 
         */
        static function link_external_as_anchor($keyword,$content) {
        	$wp_url = get_bloginfo('wpurl');
        		
        	$wp_url_clean = str_replace('http://www.','',$wp_url);
        	$wp_url_clean = str_replace('https://www.','',$wp_url_clean);
        	$wp_url_clean = str_replace('https://','',$wp_url_clean);
        	$wp_url_clean = str_replace('http://','',$wp_url_clean);
        		
			// Go through all links tags and check if keyword in as anchor
        	$matches = array();
        	
        	preg_match_all('/<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU',$content,$matches);
        	
        	// In $matches[0] stores the whole tag a, in $matches[1] stores the href URLs, in $matches[2] stores the texts used as anchors
        	$index = 0;
        	foreach ($matches[1] as $url) {
        		$text = $matches[2][$index];
        		
        		// Check if is external
        		$is_external = FALSE;
        		
        		// Clean from http://www. and http:// and https://www. and https://
        		$url_clean = str_replace('http://www.','',$url);
        		$url_clean = str_replace('https://www.','',$url_clean);
        		$url_clean = str_replace('https://','',$url_clean);
        		$url_clean = str_replace('http://','',$url_clean);
        		
        		if ((strpos($url,'http://')===0 || strpos($url,'https://')===0) && strpos($url_clean,$wp_url_clean)!==0) // Url of code begins with https:// or http://
        			$is_external = TRUE;
        			
        		// Check if is key
        		$has_key_in_text = FALSE;
        		if (WPPostsRateKeys_Keywords::keyword_in_content($keyword,$text))
        			$has_key_in_text = TRUE;
        		
        		if ($is_external && $has_key_in_text)
        			return TRUE;
        			
        		$index++;
        	}
        	
			return FALSE;
        }
        
	    /**
         * Function to check if: Link to Internal Pages
         * 
         * @param 	string	$keyword
         * @param 	string	$content
         * @return 	bool
         * @static 
         */
        static function has_link_internal($keyword,$content) {
        	$wp_url = get_bloginfo('wpurl');
        	
        	$wp_url_clean = str_replace('http://www.','',$wp_url);
        	$wp_url_clean = str_replace('https://www.','',$wp_url_clean);
        	$wp_url_clean = str_replace('https://','',$wp_url_clean);
        	$wp_url_clean = str_replace('http://','',$wp_url_clean);
        	
       		 // Go through all links tags and check if keyword in as anchor
        	$matches = array();
        	
        	preg_match_all('/<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU',$content,$matches);
        	
        	// In $matches[0] stores the whole tag a, in $matches[1] stores the href URLs, in $matches[2] stores the texts used as anchors
        	$index = 0;
        	foreach ($matches[1] as $url) {
        		$text = $matches[2][$index];
        		
        		// Check if is external
        		$is_internal = FALSE;
        		
        		// Clean from http://www. and http:// and https://www. and https://
        		$url_clean = str_replace('http://www.','',$url);
        		$url_clean = str_replace('https://www.','',$url_clean);
        		$url_clean = str_replace('https://','',$url_clean);
        		$url_clean = str_replace('http://','',$url_clean);
        		
        		
        		if (strpos($url,'http://')!==0 || strpos($url_clean,$wp_url_clean)===0)
        			return TRUE;
        		
        		$index++;
        	}
        	
			return FALSE;
        }
        
	    /**
         * Function to check if: Link to Internal Pages with Keyword as Anchor Text
         * 
         * @param 	string	$keyword
         * @param 	string	$content
         * @return 	bool
         * @static 
         */
        static function link_internal_as_anchor($keyword,$content) {
        	$wp_url = get_bloginfo('wpurl');
        	
        	$wp_url_clean = str_replace('http://www.','',$wp_url);
        	$wp_url_clean = str_replace('https://www.','',$wp_url_clean);
        	$wp_url_clean = str_replace('https://','',$wp_url_clean);
        	$wp_url_clean = str_replace('http://','',$wp_url_clean);
        	
			// Go through all links tags and check if keyword in as anchor
        	$matches = array();
        	
        	preg_match_all('/<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU',$content,$matches);
        	
        	// In $matches[0] stores the whole tag a, in $matches[1] stores the href URLs, in $matches[2] stores the texts used as anchors
        	$index = 0;
        	foreach ($matches[1] as $url) {
        		$text = $matches[2][$index];
        		
        		// Check if is external
        		$is_internal = FALSE;
        		
        		// Clean from http://www. and http:// and https://www. and https://
        		$url_clean = str_replace('http://www.','',$url);
        		$url_clean = str_replace('https://www.','',$url_clean);
        		$url_clean = str_replace('https://','',$url_clean);
        		$url_clean = str_replace('http://','',$url_clean);
        		
        		if ((strpos($url,'http://')!==0 && strpos($url,'https://')!==0) || strpos($url_clean,$wp_url_clean)===0)
        			$is_internal = TRUE;
        		
        		// Check if is key
        		$has_key_in_text = FALSE;
        		if (WPPostsRateKeys_Keywords::keyword_in_content($keyword,$text))
        			$has_key_in_text = TRUE;
        		
        		if ($is_internal && $has_key_in_text)
        			return TRUE;
        			
        		$index++;
        	}
        	
			return FALSE;
        }
        
	    /**
         * Function to check if: Post have outgoing link to external sites with do-follow
         * 
         * @param 	string	$keyword
         * @param 	string	$content
         * @return 	bool
         * @static 
         */
        static function link_external_do_follow($content) {
        	$wp_url = get_bloginfo('wpurl');
        	
        	$wp_url_clean = str_replace('http://www.','',$wp_url);
        	$wp_url_clean = str_replace('https://www.','',$wp_url_clean);
        	$wp_url_clean = str_replace('https://','',$wp_url_clean);
        	$wp_url_clean = str_replace('http://','',$wp_url_clean);
			
			// Go through all links tags and check if is external with do follow
        	$matches = array();
        	
        	preg_match_all('/<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU',$content,$matches);
        	
        	// In $matches[0] stores the whole tag a, in $matches[1] stores the href URLs
        	$index = 0;
        	foreach ($matches[0] as $tags) {
        		$url = $matches[1][$index];
        		if ($url && trim($url)!='') { // This check is because are problems when a Url is dentified but not as part of a link but as an image
	        		// Check if is external
	        		$is_external = FALSE;
	        		
	        		// Clean from http://www. and http:// and https://www. and https://
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
	        		
	        		if ($is_external && $is_dofollow)
	        			return TRUE;
        		}
        		$index++;
        	}
        	
			return FALSE;
        }
        
	    /**
         * Function to check if: Image Alt Text has Keyword
         * 
         * @param 	string	$keyword
         * @param 	string	$content
         * @return 	bool
         * @static 
         */
        static function image_alt_text_has_keyword($keyword,$content) {
			// Go through all images tags and check if has alt text and has keyword
        	$matches = array();
        	/*
        	 * http://www.the-art-of-web.com/php/parse-links/
        	 * 
        	 * starts with: <img
        	 * any series of characters NOT containing the > symbol
        	 * alt=
        	 * a series of characters up to, but not including, the next double-quote (") - 1st capture
        	 * any series of characters NOT containing the > symbol
        	 * the string: ">
        	 * 
        	 * modifiers:
        	 * i - matches are 'caseless' (upper or lower case doesn't matter)
        	 * U - matches are 'ungreedy'
        	 * 
        	 */
        	preg_match_all('/<img\s[^>]*alt=\"([^\"]*)\"[^>]*>/siU',$content,$matches);
        	
        	// In $matches[0] stores the whole match, in $matches[1] stores the alt texts
	        foreach ($matches[1] as $an_alt_text) {
	        	if (WPPostsRateKeys_Keywords::keyword_in_content($keyword,$an_alt_text))
	        		return TRUE;
			}
			
			return FALSE;
        }
        
	    /**
         * Function to check if: Keyword at Beginning of the Post in First sentence
         * 
         * @param 	string	$keyword
         * @param 	string	$content
         * @return 	bool
         * @static 
         */
        static function keyword_at_beginning($keyword,$content) {
			$content_no_html = strip_tags($content);

			if (stripos($content_no_html,$keyword)===0)
				return TRUE;
			else 
				return FALSE;
        }
        
	    /**
         * Function to check if: Keyword in the Last Sentence
         * 
         * @param 	string	$keyword
         * @param 	string	$content
         * @return 	bool
         * @static 
         */
        static function keyword_in_last_sentence($keyword,$content) {
        	
        	$content_no_html = strip_tags($content);
        	
        	// Trim blank spaces
        	$content_no_html = trim($content_no_html);
        	
        	$pieces = WPPostsRateKeys_Keywords::get_pieces_by_keyword($keyword,$content_no_html);
        	if (count($pieces)>0) {
        		$last_piece = $pieces[count($pieces)-1];
        		
        		// Improved v5: Remove new lines, empty spaces and html tags
        		$last_piece = strip_tags($last_piece);
        		$last_piece = str_replace("\r",'',$last_piece);
        		$last_piece = str_replace("\n",'',$last_piece);
        		$last_piece = str_replace("&nbsp;",'',$last_piece);
        		$last_piece = str_replace(' ','',$last_piece);
        		$last_piece = str_replace(' ','',$last_piece); // Seems different from previous line, but for PHP-string isn't
        		
        		// Remove the last dot if is in the last of the sentence, because is irrelevant
        		$last_piece = rtrim($last_piece,'.'); // Don't remove if is a previous dot
        		
        		// If in the last piece is a dot (with space to void a number match like $12.50), the keyword isn't in the last sentence
        		if (substr_count($last_piece,'. ')>0 || substr_count($last_piece,'? ')>0 || substr_count($last_piece,'! ')>0)
        			return FALSE;
        		else 
        			return TRUE;
        	}
        	
        	return FALSE;
        }
        
		/**
		 * Get the first sentence in a text
		 * 
		 * Valid for the follow examples:
		 * - 'A simple test without a character to end the sentence'
		 * - 'This is a sentence with a price $ 7.50 and ends with exclamation! This sentence will not shown.'
		 * - 'This sentence will end with the follow dots .... This sentence will not shown!'
		 * - 'A simple test!'
		 * - '... But what about me?';
		 * - 'We at StackOverflow.com prefer prices below US$ 7.50. Really, we do.'
		 * - 'This will probably break after this pause .... or won\'t it?'
		 * 
		 * @param 	string $string
		 * @return	string
		 */
		private static function get_first_sentence($string) {
			
			$array = preg_split('/(^.*\w+.*[\.\?!][\s])/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
		    // You might want to count() but I chose not to, just add
		    return trim($array[0] . $array[1]);
		}
		
		/**
         * Function to check if: Keyword in the First Sentence
         * 
         * @param 	string	$keyword
         * @param 	string	$content
         * @return 	bool
         * @static 
         */
        static function keyword_in_first_sentence($keyword,$content) {
        	$first_sentence = self::get_first_sentence($content);
        	
        	// Process the "first sentence" that some times has more than one sentence
        	$content_arr = WPPostsRateKeys_Keywords::get_pieces_by_keyword($keyword,$first_sentence);
		    if (count($content_arr) > 0) {
				$content_slice = $content_arr[0];
				if (substr_count($content_slice,'. ') > 0 || substr_count($content_slice,'! ')>0
					|| substr_count($content_slice,'? ')>0) {
					return FALSE;
				} else {
					return TRUE;
				}
			}
			return FALSE;
        }
        
	    /**
         * Function to check if is a keyword inside a H<some> tag
         * 
         * @param 	string	$keyword
         * @param 	string	$content
         * @param 	string	$title
         * @param 	string	$h			can be H1, H2 or H3
         * @return 	bool
         * @static 
         */
        static function keyword_inside_some_h($keyword,$content,$title,$h) {
        	
        	// Set the array of styles that define the current check
        	$arrays_to_check = WPPostsRateKeys_HtmlStyles::get_h_styles($h);
        	
        	// Check in title
        	$pieces = WPPostsRateKeys_Keywords::get_pieces_by_keyword($keyword,$title);
        	
        	// Checks for each piece of code, is needed at least one
        	for ($i=0;$i<(count($pieces)-1);$i++) {
        		
        		$result = WPPostsRateKeys_HtmlStyles::if_some_style_in_pieces($pieces, $i, $arrays_to_check, $keyword);
        		if ($result && strpos($result[1],'H')===0)
        			return TRUE;
        	}
        	
        	// Check in content
        	$pieces = WPPostsRateKeys_Keywords::get_pieces_by_keyword($keyword,$content);
        	
        	// Checks for each piece of code, is needed at least one
        	for ($i=0;$i<(count($pieces)-1);$i++) {
        		
        		$result = WPPostsRateKeys_HtmlStyles::if_some_style_in_pieces($pieces, $i, $arrays_to_check, $keyword);
        		if ($result && strpos($result[1],'H')===0)
        			return TRUE;
        	}
        	
        	return FALSE;
        }
        
	    /**
         * Function to the get the: Post Word Count
         * 
         * @param 	string	$new_content	the content to search in (after changes made by our filter)
         * @return 	int
         * @static 
         */
        static function get_post_word_count($new_content) {
        	// Remove tags html to not be counted as words
        	$content_no_html = strip_tags($new_content);
        	
        	// How many words
        	$how_many_words = str_word_count($content_no_html);
        	
        	return $how_many_words;
        }
        
	    /**
         * Function to the get the: Keyword Density Pointer
         * 
         * @param 	string	$new_content	the content to search in (after changes made by our filter)
         * @param 	string	$keyword
         * @return 	double					return the percent of keywords in the text
         * @static 
         */
        static function get_keyword_density_pointer($new_content, $keyword) {
        	// Remove tags html to not be counted as words or keywords
        	$content_no_html = strip_tags($new_content);
        	$content_no_html = str_replace("&nbsp;",'',$content_no_html);
        	
        	// How many words: this method allow don't fails when keyword is a phrase
			$post_content_no_keywords = str_replace($keyword, '', $content_no_html);
        	$how_many_words = self::get_post_word_count($post_content_no_keywords);
        	
        	// How many times is the key in content
        	$how_many_keys = WPPostsRateKeys_Keywords::how_many_keywords($keyword, $content_no_html);
        	
        	// Update keyword count: this allow don't fails when keyword is a phrase
        	$how_many_words += $how_many_keys;
        	
        	if ($how_many_words>0)
        		$percent = $how_many_keys * 100 / $how_many_words;
        	else
        		$percent = 0;
        	
        	return $percent;
        }
        
	    /**
         * Function to the check if: Is the Keyword underlined
         * 
         * @param 	string	$new_content	the content to search in (after changes made by our filter)
         * @param 	string	$keyword
         * @return 	bool
         * @static 
         */
        static function is_keyword_underlined($new_content, $keyword) {
        	
        	$pieces = WPPostsRateKeys_Keywords::get_pieces_by_keyword($keyword,$new_content);
        	
        	$arrays_to_check = WPPostsRateKeys_HtmlStyles::get_underline_styles();
        	
        	// Checks for each piece of code, is needed at least one
        	for ($i=0;$i<(count($pieces)-1);$i++) {
        		
        		$result = WPPostsRateKeys_HtmlStyles::if_some_style_in_pieces($pieces, $i, $arrays_to_check, $keyword);
        		if ($result && $result[1]=='underline')
        			return TRUE;
        	}
        	
        	return FALSE;
        }
        
	    /**
         * Function to the check if: Is the Keyword Italized
         * 
         * @param 	string	$new_content	the content to search in (after changes made by our filter)
         * @param 	string	$keyword
         * @return 	bool
         * @static 
         */
        static function is_keyword_italized($new_content, $keyword) {
        	$pieces = WPPostsRateKeys_Keywords::get_pieces_by_keyword($keyword,$new_content);
        	
        	$arrays_to_check = WPPostsRateKeys_HtmlStyles::get_italic_styles();
        	
        	// Checks for each piece of code, is needed at least one
        	for ($i=0;$i<(count($pieces)-1);$i++) {
        		
        		$result = WPPostsRateKeys_HtmlStyles::if_some_style_in_pieces($pieces, $i, $arrays_to_check, $keyword);
        		if ($result && $result[1]=='italic')
        			return TRUE;
        	}
        	
        	return FALSE;
        }
        
	    /**
         * Function to the check if: Is the Keyword Bold
         * 
         * @param 	string	$new_content	the content to search in (after changes made by our filter)
         * @param 	string	$keyword
         * @return 	bool
         * @static 
         */
        static function is_keyword_bold($new_content, $keyword) {
        	
        	$pieces = WPPostsRateKeys_Keywords::get_pieces_by_keyword($keyword,$new_content);
        	
        	$arrays_to_check = WPPostsRateKeys_HtmlStyles::get_bold_styles();
        	
        	// Checks for each piece of code, is needed at least one
        	for ($i=0;$i<(count($pieces)-1);$i++) {
        		
        		$result = WPPostsRateKeys_HtmlStyles::if_some_style_in_pieces($pieces, $i, $arrays_to_check, $keyword);
        		if ($result && $result[1]=='bold')
        			return TRUE;
        	}
        	
        	return FALSE;
        }
	}
}