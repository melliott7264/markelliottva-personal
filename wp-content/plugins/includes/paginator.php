<?php
/**
 * Include to show the paginator
 * 
 * Called from all page with lists
 * 
 * @uses	int		$items_count			the number of items in the list to paginate
 * @uses 	bool	$show_tpl				determine when to show the template file, or only calculate the pagination's variables		
 * @uses 	bool	$dont_show_tag_target		
 */

/*
 * Set the page size
 */
@session_start();
if (isset($_REQUEST['page_size'])) {
	// Set value in cookie
	WPPostsRateKeys_Cookies::add_cookie( WPPostsRateKeys_Cookies::$cookie_pagination_page_size , $_REQUEST['page_size']);
	$_SESSION[ WPPostsRateKeys_Cookies::$cookie_pagination_page_size ] = $_REQUEST['page_size'];
	$form['page_size'] = $_REQUEST['page_size'];
}
else {
	// if the user cookie have a value for page size, use it for the page size
	if (isset($_SESSION[ WPPostsRateKeys_Cookies::$cookie_pagination_page_size ])) {
		$form['page_size'] = $_SESSION[ WPPostsRateKeys_Cookies::$cookie_pagination_page_size ];
	}
	elseif (isset($_COOKIE[ WPPostsRateKeys_Cookies::$cookie_pagination_page_size ])) {
		$form['page_size'] = $_COOKIE[ WPPostsRateKeys_Cookies::$cookie_pagination_page_size ];
	}
	else { // use the wp default page size
		$form['page_size'] = 30;
	}
}

/*
 * Calculate the last page
 */
$form['last_page'] = ceil($items_count / $form['page_size']);

/*
 * Set the current page
 */

$form['current_page'] = 1;
if (isset($_REQUEST['goto'])) {
	if ($_REQUEST['goto']!='' && $_REQUEST['goto']!='0') {
		$form['current_page'] = $_REQUEST['goto'];
	}
}

if ($form['current_page']>$form['last_page'])
	$form['current_page'] = $form['last_page'];
	
// Define first and last item to show of list
$pagination_first_item = ( ($form['current_page'] - 1) * $form['page_size'] ) + 1;
if ($pagination_first_item<0) $pagination_first_item = 0;

if (!isset($items_count))
	$items_count = 0;
	
$pagination_last_item = $pagination_first_item + $form['page_size'] - 1;
if ($pagination_last_item>$items_count)
	$pagination_last_item = $items_count;
if ($items_count==0)
	$pagination_last_item = 0;
/*
 * Set the next page
 */
$form['next'] = '';
$next_page = $form['current_page'] + 1;
if ($next_page<=$form['last_page'])
	$form['next'] = $next_page;

/*
 * Set the previous page
 */
$form['prev'] = '';
$prev_page = $form['current_page'] - 1;
if ($prev_page>=1)
	$form['prev'] = $prev_page;

/*
 * Get url of the current page (with all get parameters except "goto" and "a" parameter), 
 * this url will always contain the simbol "?" even if the url haven't parameters
 */
// $form['url'] = GpcKits_Miscellaneous::get_current_url(array('goto','a'));
// Change to solve the problem that when click on the pagination links of the article details page, the user is redirected to the article list
$form['url'] = WPPostsRateKeys_Miscellaneous::get_current_url(array('goto'));
if (substr_count($form['url'],'?')==0)
	$form['url'] .= '?';

// Get parameters passed by GET, except "goto" parameter
// (ignored because isn't needed) $form['query_parameters'] = WPPostsRateKeys_Miscellaneous::get_parameters_by_get('goto');
if (isset($show_tpl) && $show_tpl) {
	// Include the template
	include( WPPostsRateKeys::$plugin_dir . '/templates/includes/paginator.php');
}