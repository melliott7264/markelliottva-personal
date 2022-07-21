<?php
/**
 * Template to show the Suggestions
 *
 * @uses	array	$suggestions_arr	each item is a text with a suggetions like "Please add your keyword in the first sentence."
 * 
 * @package admin-panel
 * 
 */
?>
<div class="wrap">
    <h2>
        <?php _e('Suggestions','seo-pressor')?>
    </h2>
    <br>
    <?php include( WPPostsRateKeys::$template_dir . '/includes/msg.php'); ?>
    <?php if (isset($suggestions_arr) && count($suggestions_arr)>0) {?>
    <div id="suggestions" class="postbox">
        <ul>
            <?php foreach ($suggestions_arr as $suggestions_arr_item) {?>
            <li>
                <?php echo $suggestions_arr_item?>
            </li>
            <?php }?>
        </ul>
    </div>
    <?php }?>
</div>
