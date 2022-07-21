<?php
/**
 * Include to show the Suggestions
 *
 * @package admin-panel
 * 
 * @uses	int	$post_id
 */

// Check is plugin is Aweber activated
if (WPPostsRateKeys_Settings::get_active()==0) {
	exit();
}

$all_page_suggestion_messages = array(
	'msg_1'=>__('Please add the keyword once more for SEOPressor to automatically bold it when your page is being loaded.','seo-pressor')
	, 'msg_2'=>__('Please add the keyword once more for SEOPressor to automatically italize it when your page is being loaded.','seo-pressor')
	, 'msg_3'=>__('Please add the keyword once more for SEOPressor to automatically underline it when your page is being loaded.','seo-pressor')
	, 'msg_4'=>__('Your keyword density is too low, you need more repetitions of your keyword.','seo-pressor')
	, 'msg_5'=>__('Your article is over-optimized, less keyword density is probably good!','seo-pressor')
	, 'msg_6'=>__("More words needed, short articles don't rank very well.",'seo-pressor')
	, 'msg_7'=>__('Please include your keyword in the Title of the post.','seo-pressor')
	, 'msg_8'=>__('You need a H1 tag with your keyword inside it.','seo-pressor')
	, 'msg_9'=>__('You need a H2 tag with your keyword inside it.','seo-pressor')
	, 'msg_10'=>__('You need a H3 tag with your keyword inside it.','seo-pressor')
	, 'msg_11'=>__('Please add your keyword in the first sentence.','seo-pressor')
	, 'msg_12'=>__('It is good to have an image in your content and SEOPressor will automatically add ALT tag to your image.','seo-pressor')
	, 'msg_13'=>__('Try your best to add rel=nofollow to outbound links pointing to external sites.','seo-pressor')
	, 'msg_14'=>__('You should point a link to your other pages with keyword as anchor text.','seo-pressor')
	, 'msg_15'=>__('You are doing pretty well!','seo-pressor')
	, 'msg_16'=>__('No Suggestions.','seo-pressor')
);

if (!(isset($post_id))) {
	$msg_error[] = __('You must click in Suggestion Link ','seo-pressor')
				. '<a href="'
				. admin_url('admin.php?page=seopressor-posts-score')
				. '">'
				. __('here','seo-pressor')
				. '</a>'
				;
}
else {
	// Fill suggestions
	$suggestions_arr_msg_keys = WPPostsRateKeys_Central::get_suggestions_page($post_id);
	$suggestions_arr = array();
	foreach ($suggestions_arr_msg_keys as $suggestions_arr_msg_keys_item) {
		$suggestions_arr[] = $all_page_suggestion_messages[$suggestions_arr_msg_keys_item];
	}
}

include( WPPostsRateKeys::$template_dir . '/includes/admin/suggestions.php');

