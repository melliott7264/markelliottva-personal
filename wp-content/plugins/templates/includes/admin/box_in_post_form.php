<?php
/**
 * Template to show the box for keyword in the POST form
 *
 * @uses string $keyword_value
 * 
 * @package admin-panel
 * 
 */
?>
<p>
    <label for="WPPostsRateKeys_keyword">
        <?php _e('Enter Main Keyword','seo-pressor');?>
    </label>
    <input type="text" value="<?php echo $keyword_value?>" id="WPPostsRateKeys_keyword" name="WPPostsRateKeys_keyword">
</p>