<?php
/**
 * Template to display paginator for any list
 *
 * @uses number	$form['page_size']		number of items per page
 * @uses number $form['next']			number of the next page
 * @uses number $form['prev']			number of the previous page
 * @uses number $form['current_page']	number of the current page
 * @uses number $form['last_page']		number of the current page
 * @uses string $form['url']			url of the current page (with all get parameters except "page" parameter), this url will always contain the simbol "?" even if the url haven't parameters
 *
 * @package general
 * 
 * 
 */
?>
<div class="tablenav" id="ps">
<div class="tablenav-pages"><span class="displaying-num"><?php 
_e('Displaying','seo-pressor'); ?> <?php 
echo $pagination_first_item?>&#8211;<?php 
echo $pagination_last_item?> <?php _e('of','seo-pressor')?> <?php 
echo $items_count?></span>
<?php if ($form['prev'] != '') { ?>
<a class='prev page-numbers' href='<?php echo $form['url']; ?>&amp;goto=<?php echo $form['prev']; ?><?php echo (isset($show_tag_target) && $show_tag_target)?'#topps':'' ?>'>&laquo;</a>
<?php }
// Go trhough each page
for ($i=1;$i<=$form['last_page'];$i++) {
	if ($i==$form['current_page']) {
	?>
	<span class='page-numbers current'><?php echo $i ?></span>
	<?php } else { ?>
	<a class='page-numbers' href='<?php echo $form['url']; ?>&amp;goto=<?php echo $i ?><?php echo (isset($show_tag_target) && $show_tag_target)?'#topps':'' ?>'><?php echo $i ?></a>
	<?php
	}
}
?>
<?php if ($form['next'] != '') { ?>
<a class='next page-numbers' href='<?php echo $form['url']; ?>&amp;goto=<?php echo $form['next']; ?><?php echo (isset($show_tag_target) && $show_tag_target)?'#topps':'' ?>'>&raquo;</a>
<?php } ?>
</div>
</div>
<?php if (!$dont_show_form) {?>
<form method="get" action="<?php echo $form['url']; ?>&amp;goto=<?php echo $form['current_page']; ?>">
<?php }?>
<?php 
if (isset($form['query_parameters'])) {
foreach ($form['query_parameters'] as $name => $value) {?>
<input type="hidden" name="<?php echo $name?>" value="<?php echo $value ?>" />
<?php } } ?>
<input type="hidden" name="goto" value="<?php echo $form['current_page']; ?>" />
<label for="page-size"><?php _e('Items per page','seo-pressor'); ?>:</label>
<input type="text" name="page_size" value="<?php echo $form['page_size']; ?>" id="page-size" size="2" />
<input type="submit" name="paginator_save_items_per_page" value="<?php _e('Save','seo-pressor'); ?>" />
<?php if (!$dont_show_form) {?>
</form>
<?php }?>