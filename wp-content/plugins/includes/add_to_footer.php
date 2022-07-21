<?php
/**
 * Include for the HTMl to add to WP footer
 * 
 */

// Get links
$name_link = WPPostsRateKeys_Settings::get_name_link();
$seo_link = WPPostsRateKeys_Settings::get_seo_link();

/*
 * 
 * If affiliate ID is entered, replace "http://www.seopressor.com" for 
 * "http://$AffiliateID.seopressor.hop.clickbank.net"
 * 	
 * else: use the default link.
 */
$clickbank_id = WPPostsRateKeys_Settings::get_clickbank_id();
if ($clickbank_id!='') {
	$name_link = str_replace('http://www.seopressor.com',"http://$clickbank_id.seopressor.hop.clickbank.net",$name_link);
}

include( WPPostsRateKeys::$template_dir . '/includes/add_to_footer.php');