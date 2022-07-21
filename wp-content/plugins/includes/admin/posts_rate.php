<?php
/**
 * Include to show the posts
 *
 * @package admin-panel
 * 
 */

// Check is plugin is Aweber activated
if (WPPostsRateKeys_Settings::get_active()==0) {
	exit();
}

// Update keywords
if (isset($_REQUEST['update_keywords'])) {
	// Security: Check nonce
	check_admin_referer('WPPostsRateKeys-update-keywords');
	
	foreach ($_REQUEST as $p_name => $p_value) {
			if (strpos($p_name,'seo_p_key')===0) {
				// If a keyword field, example of ID 1: seo_p_key__1
				$p_name_arr = explode('__',$p_name);
				$post_page_id = $p_name_arr[1];
				
				WPPostsRateKeys_WPPosts::update_keyword($post_page_id,$p_value);
			}
	}
	
	$msg_notify[] = __('The Keyword list was updated.','seo-pressor');
}

// Query
$query_arr = array();

// Initialize Begin Date and take in care if the user specify it
$date_begin = '';
if (isset($_REQUEST['month_begin'])) {
	
	// If is selected some of the values
	if ($_REQUEST['month_begin']!='' || $_REQUEST['day_begin']!='' || $_REQUEST['year_begin']!='') {
		if ($_REQUEST['month_begin']!='' && $_REQUEST['day_begin']!='' && $_REQUEST['year_begin']!='' 
			&& @checkdate($_REQUEST['month_begin'],$_REQUEST['day_begin'],$_REQUEST['year_begin'])) {
			// Take in care the Begin Date
			$date_begin = mktime(0,0,0,$_REQUEST['month_begin'],$_REQUEST['day_begin'],$_REQUEST['year_begin']);
			
			$query_arr[] = 'month_begin='.$_REQUEST['month_begin'];
			$query_arr[] = 'day_begin='.$_REQUEST['day_begin'];
			$query_arr[] = 'year_begin='.$_REQUEST['year_begin'];
		}
		else {
			// Show message notifying a wrong begin date
			$msg_error[] = __('The Begin Date is invalid','seo-pressor');
		}
	}
}
// Initialize End Date and take in care if the user specify it
$date_end = '';
if (isset($_REQUEST['month_end'])) {
	
	// If is selected some of the values
	if ($_REQUEST['month_end']!='' || $_REQUEST['day_end']!='' || $_REQUEST['year_end']!='') {
		if ($_REQUEST['month_end']!='' && $_REQUEST['day_end']!='' && $_REQUEST['year_end']!=''
			&& @checkdate($_REQUEST['month_end'],$_REQUEST['day_end'],$_REQUEST['year_end'])) {
			// Take in care the Begin Date
			$date_end = mktime(0,0,0,$_REQUEST['month_end'],$_REQUEST['day_end'],$_REQUEST['year_end']);
			
			$query_arr[] = 'month_end='.$_REQUEST['month_end'];
			$query_arr[] = 'day_end='.$_REQUEST['day_end'];
			$query_arr[] = 'year_end='.$_REQUEST['year_end'];
		}
		else {
			// Show message notifying a wrong begin date
			$msg_error[] = __('The End Date is invalid','seo-pressor');
		}
	}
}

$order_by = 'date';
$order_dir = 'DESC';

if (isset($_REQUEST['order_by'])) {
	
	// The follow is a security check
	if ($_REQUEST['order_by']=='rate' || $_REQUEST['order_by']=='date' || $_REQUEST['order_by']=='post_title') {
		$order_by = $_REQUEST['order_by'];
	}
}
if (isset($_REQUEST['order_dir'])) {
	
	// The follow is a security check
	if ($_REQUEST['order_dir']=='DESC' || $_REQUEST['order_dir']=='ASC') {
		$order_dir = $_REQUEST['order_dir'];
	}
}

// Pagination code
if ($page_link=='post')
	$items_count = WPPostsRateKeys_WPPosts::get_wp_posts_count($date_begin,$date_end);
else 
	$items_count = WPPostsRateKeys_WPPosts::get_wp_posts_count($date_begin,$date_end,'page');
include(WPPostsRateKeys::$plugin_dir . '/includes/paginator.php');
$pagination_offset = $pagination_first_item-1;
$pagination_limit = $form['page_size'];
// End: Pagination code

// Get POSTs/PAGE with rates and posts that are between the previous dates (if specified)
if ($page_link=='post')
	$posts_arr = WPPostsRateKeys_WPPosts::get_wp_posts($date_begin,$date_end,$order_by,$order_dir,'post',$pagination_offset,$pagination_limit);
else 
	$posts_arr = WPPostsRateKeys_WPPosts::get_wp_posts($date_begin,$date_end,$order_by,$order_dir,'page',$pagination_offset,$pagination_limit);
	
if (count($posts_arr)==0) {
	if ($page_link=='post')
		$msg_notify[] = __('None Post to show','seo-pressor');
	else
		$msg_notify[] = __('None Page to show','seo-pressor');
}

// set Query
$query_str = '&' . join('&',$query_arr);
// Add pagination get variable
if (isset($_REQUEST['goto'])) {
	if ($_REQUEST['goto']!='' && $_REQUEST['goto']!='0') {
		$query_str .= '&goto=' . $_REQUEST['goto'];
	}
}

include( WPPostsRateKeys::$template_dir . '/includes/admin/posts_rate.php');