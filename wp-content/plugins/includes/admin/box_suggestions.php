<?php
/**
 * Include to show the Suggestions
 *
 * @uses	int		$post_id
 *
 * @package admin-panel
 * 
 */

// Check is plugin is activated
if (WPPostsRateKeys_Settings::get_active()==0) {
	exit();
}

$messages_texts = array(
	'msg_1' => __('You do not have H1 tag containing your keyword','seo-pressor')
	, 'msg_2' => __('You have H1 tag containing your keyword','seo-pressor')
	, 'msg_3' => __('You do not have H2 tag containing your keyword','seo-pressor')
	, 'msg_4' => __('You have H2 tag containing your keyword','seo-pressor')
	, 'msg_5' => __('You do not have H3 tag containing your keyword','seo-pressor')
	, 'msg_6' => __('You have H3 tag containing your keyword','seo-pressor')
	, 'msg_7' => __('SEOPressor will automatically bold your keyword','seo-pressor')
	, 'msg_8' => __('You do not have enough keywords to bold','seo-pressor')
	, 'msg_9' => __('SEOPressor will automatically italic your keyword','seo-pressor')
	, 'msg_10' => __('You do not have enough keywords to italize','seo-pressor')
	, 'msg_11' => __('SEOPressor will automatically underline your keyword','seo-pressor')
	, 'msg_12' => __('You do not have enough keywords to underline','seo-pressor')
	, 'msg_13' => __('You have an image, SEOPressor will automatically add ALT tag to it','seo-pressor')
	, 'msg_14' => __('You do not have ALT tag set to your keyword','seo-pressor')
	, 'msg_15' => __('You have have ALT tag set to your keyword','seo-pressor')
	, 'msg_16' => __('You do not have ALT tag set to your keyword','seo-pressor')
	, 'msg_17' => __('You do not have an image for SEOPressor to add ALT tag','seo-pressor')
	, 'msg_18' => __('You need to have an image with ALT tag set to your keyword','seo-pressor')
	, 'msg_19' => __("More words needed.",'seo-pressor')
	, 'msg_20' => __('You do not have keyword in the first sentence.','seo-pressor')
	, 'msg_21' => __('You do not have keyword in the last sentence.','seo-pressor')
	, 'msg_22' => __('You do not have an internal link to your other pages.','seo-pressor')
	, 'msg_23' => __('Please add rel=nofollow to your external links.','seo-pressor')
);

$data_arr = WPPostsRateKeys_WPPosts::get_wp_post_title_content($post_id);
$post_title = WPPostsRateKeys::filter_post_title($data_arr[0],$post_id);
$post_content = $data_arr[1];

// Get Score
$box_score = WPPostsRateKeys_Central::get_score($post_id);
$box_suggestions_arr = array();

// Get Keyword
$box_keyword = WPPostsRateKeys_WPPosts::get_keyword($post_id);

// Get data for suggestion Box
if ($box_keyword!='') {
	$all_messages = WPPostsRateKeys_Central::get_suggestions_box($post_id);
	
	if ($all_messages) {
		// About Density
		$box_keyword_density = $all_messages['box_keyword_density'];
		
		if ($box_keyword_density<1)
			$box_keyword_density_message = __('Your keyword density is too low','seo-pressor');
		elseif ($box_keyword_density>6)
			$box_keyword_density_message = __('Your keyword density is too high','seo-pressor');
		else
			$box_keyword_density_message = '';
			
		// Fill box-suggestions
		$box_suggestions_arr_messages = $all_messages['box_suggestions_arr'];
		
		// Change key message by text
		foreach ($box_suggestions_arr_messages as $box_suggestions_arr_messages_item) {
			$box_suggestions_arr[] = array($box_suggestions_arr_messages_item[0],$messages_texts[$box_suggestions_arr_messages_item[1]]);
		}
	}
}
if ($box_score=='')
	$box_score = 0;

include( WPPostsRateKeys::$template_dir . '/includes/admin/box_suggestions.php');