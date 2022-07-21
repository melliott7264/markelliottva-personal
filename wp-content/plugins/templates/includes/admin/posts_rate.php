<?php
/**
 * Template to show the posts
 *
 * @uses array	$posts_arr
 * @uses string	$title
 * 
 * @package admin-panel
 * 
 */

$month_texts = array(
	'01'=>__('January','seo-pressor')
	,'02'=>__('February','seo-pressor')
	,'03'=>__('March','seo-pressor')
	,'04'=>__('April','seo-pressor')
	,'05'=>__('May','seo-pressor')
	,'06'=>__('June','seo-pressor')
	,'07'=>__('July','seo-pressor')
	,'08'=>__('August','seo-pressor')
	,'09'=>__('September','seo-pressor')
	,'10'=>__('October','seo-pressor')
	,'11'=>__('November','seo-pressor')
	,'12'=>__('December','seo-pressor')
);
?>
<div class="wrap">
<h2><?php echo $title?></h2>
<?php include( WPPostsRateKeys::$template_dir . '/includes/msg.php'); ?>
<form action="" method="get">
<?php wp_nonce_field('WPPostsRateKeys-update-keywords');?>
<?php if (isset($_REQUEST['order_by'])) {?>
<input type="hidden" name="order_by" value="<?php echo $_REQUEST['order_by']?>"> <?php }?>
<?php if (isset($_REQUEST['order_dir'])) {?>
<input type="hidden" name="order_dir" value="<?php echo $_REQUEST['order_dir']?>"> <?php }?>
<input type="hidden" name="page" value="<?php echo $_REQUEST['page']?>">
<label for="begin_date"><?php _e('Begin Date','seo-pressor')?></label>
&nbsp;<select name="month_begin">
	<option value=""><?php _e('Month','seo-pressor'); ?></option>
	<?php for ($i=1;$i<=12;$i++) { ?>
		<option <?php 
		echo (isset($_REQUEST['month_begin']) && $_REQUEST['month_begin']==$i)?'selected="selected"':'' ?> value="<?php echo $i?>"><?php 
		if ($i<10)
			echo $month_texts["0$i"];
		else
			echo $month_texts[$i];?></option>
	<?php } ?>
</select>
<select name="day_begin">
	<option value=""><?php _e('Day','seo-pressor'); ?></option>
	<?php for ($i=1;$i<=31;$i++) { ?>
		<option <?php 
		echo (isset($_REQUEST['day_begin']) && $_REQUEST['day_begin']==$i)?'selected="selected"':'' ?> value="<?php echo $i?>"><?php 
		echo $i?></option>
	<?php } ?>
</select>,
<input type="text" title="<?php _e('Year','seo-pressor'); ?>" size="4" value="<?php echo (isset($_REQUEST['year_begin']))?$_REQUEST['year_begin']:'' ?>" name="year_begin" id="year_begin">
&nbsp;&nbsp;&nbsp;
<label for="end_date"><?php _e('End Date','seo-pressor')?></label>
<select name="month_end">
	<option value=""><?php _e('Month','seo-pressor'); ?></option>
	<?php for ($i=1;$i<=12;$i++) { ?>
		<option <?php 
		echo (isset($_REQUEST['month_end']) && $_REQUEST['month_end']==$i)?'selected="selected"':'' ?> value="<?php echo $i?>"><?php 
		if ($i<10)
			echo $month_texts["0$i"];
		else
			echo $month_texts[$i];
			?></option>
	<?php } ?>
</select>
<select name="day_end">
	<option value=""><?php _e('Day','seo-pressor'); ?></option>
	<?php for ($i=1;$i<=31;$i++) { ?>
		<option <?php 
		echo (isset($_REQUEST['day_end']) && $_REQUEST['day_end']==$i)?'selected="selected"':'' ?> value="<?php echo $i?>"><?php 
		echo $i?></option>
	<?php } ?>
</select>,
<input type="text" title="<?php _e('Year','seo-pressor'); ?>" size="4" value="<?php echo (isset($_REQUEST['year_end']))?$_REQUEST['year_end']:'' ?>" name="year_end" id="year_end">
&nbsp;&nbsp;&nbsp;
    <input type="submit" value="<?php _e('Search','seo-pressor') ?>" class="button-secondary action" name="search" />
    <a href="?page=<?php echo $_REQUEST['page']?>"><?php _e('Clear Search','seo-pressor');?></a>

<?php if (isset($posts_arr) && count($posts_arr)>0) {?>
<br><br>
<table cellspacing="0" class="widefat">
	<thead>
	<tr>
	<th scope="col"><a href="?page=<?php echo $_REQUEST['page'] . $query_str?>&order_by=rate&order_dir=<?php echo ($order_dir=='DESC')?'ASC':'DESC'?>" ><?php _e('Score (%)','seo-pressor');?></a></th>
	<th scope="col"><a href="?page=<?php echo $_REQUEST['page'] . $query_str?>&order_by=post_title&order_dir=<?php echo ($order_dir=='DESC')?'ASC':'DESC'?>" ><?php _e('Title','seo-pressor');?></a></th>
	<th scope="col"><?php _e('SEOPressor Keyword','seo-pressor');?></th>
	<th scope="col"><a href="?page=<?php echo $_REQUEST['page'] . $query_str?>&order_by=date&order_dir=<?php echo ($order_dir=='DESC')?'ASC':'DESC'?>" ><?php _e('Date','seo-pressor');?></a></th>
	<th scope="col"><?php _e('Suggestions','seo-pressor');?></th>
	</tr>
	</thead>

	<tfoot>
	<tr>
	<th scope="col"><a href="?page=<?php echo $_REQUEST['page'] . $query_str?>&order_by=rate&order_dir=<?php echo ($order_dir=='DESC')?'ASC':'DESC'?>" ><?php _e('Score (%)','seo-pressor');?></a></th>
	<th scope="col"><a href="?page=<?php echo $_REQUEST['page'] . $query_str?>&order_by=post_title&order_dir=<?php echo ($order_dir=='DESC')?'ASC':'DESC'?>" ><?php _e('Title','seo-pressor');?></a></th>
	<th scope="col"><?php _e('SEOPressor Keyword','seo-pressor');?></th>
	<th scope="col"><a href="?page=<?php echo $_REQUEST['page'] . $query_str?>&order_by=date&order_dir=<?php echo ($order_dir=='DESC')?'ASC':'DESC'?>" ><?php _e('Date','seo-pressor');?></a></th>
	<th scope="col"><?php _e('Suggestions','seo-pressor');?></th>
	</tr>
	</tfoot>
	<tbody>
	<?php 
	$index = 0;
	foreach ($posts_arr as $post) {
        	?>
        	<script>
			//<![CDATA[
				/*
				 * jQuery script for update by Ajax the Post Score.
				 */
	        	jQuery(document).ready(function($){
		            var post_score = $('#buffer_<?php echo $post['ID']?>');                        
		            $('#td-score-<?php echo $post['ID']?>').html(post_score.html());
		            if (post_score.html() < 50) {
		            	$('#tr-<?php echo $post['ID']?>').addClass('scores-red-background');
		                $('#td-score-<?php echo $post['ID']?>').addClass('scores-red-not-anchor');
		                $('#td-title-<?php echo $post['ID']?>').addClass('scores-red-anchor');
		                $('#td-date-<?php echo $post['ID']?>').addClass('sscores-red-not-anchor');
		                $('#td-suggestions-<?php echo $post['ID']?>').addClass('scores-red-anchor');
		            }
					if (post_score.html() == -1) {
						$('#td-score-<?php echo $post['ID']?>').attr('title','<?php _e('-1 Score because the License is invalid','seo-pressor')?>');
					}
		        });
            //]]>
        	</script>
        	<span id="buffer_<?php echo $post['ID']?>" style="display:none;"><?php echo $post['rate']?></span>
      
	<tr id="tr-<?php echo $post['ID']?>" valign="top" class="<?php echo ($index%2==0)?'alternate':''?> author-self status-publish iedit">
		<td id="td-score-<?php echo $post['ID']?>"></td>
		<td id="td-title-<?php echo $post['ID']?>"><a href="<?php echo WPPostsRateKeys_WPPosts::get_link_to_post_page_edit_page($post['ID'],$page_link)?>"><?php echo $post['post_title']?></a></td>
		<td><input type=text name="seo_p_key__<?php echo $post['ID']?>" maxlength="80" value="<?php echo $post['keyword']?>">			
		</td>
		<td id="td-date-<?php echo $post['ID']?>"><?php echo $month_texts[date('m',strtotime($post['post_date']))] . ' ' . date('d, Y h:i',strtotime($post['post_date']))?></td>
		<td id="td-suggestions-<?php echo $post['ID']?>"><a href="<?php echo admin_url('admin.php?page=seopressor-suggestions&pid=' . $post['ID'])?>"><?php _e('Suggestions','seo-pressor')?></a></td>
	</tr>
	<?php
    	$index++;
	} ?>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="update_keywords" class="button-secondary action" value="<?php _e('Update keywords','seo-pressor')?>">
        </p>
        <?php 
// Paginator code
$show_tpl = TRUE;
$dont_show_form = TRUE;
include(WPPostsRateKeys::$plugin_dir . '/includes/paginator.php');
// End: Paginator code
        ?>
        <?php }?>
    </form>
</div>