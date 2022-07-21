<?php
/**
 * Template to show the administrator's setting
 *
 * @uses string	$msg_for_settings_about_method	message data requests
 * @uses int	$posts_without_valid_cache		number of posts without valid cache
 * @uses string	$msg_status						message about plugin activation Status
 * @uses array	$data
 * @uses array	$bold_arr
 * @uses array	$italic_arr
 * @uses array	$underline_arr
 * @uses array	$all_locales					list of available languages
 * 
 * @package admin-panel
 * 
 */
?>
<div class="wrap">
    <h2><?php _e('General Settings','seo-pressor')?></h2>
    <?php include( WPPostsRateKeys::$template_dir . '/includes/msg.php');?>
    <form action="" method="post">
        <?php wp_nonce_field('WPPostsRateKeys-save-settings');?>
    		
        <table class="form-table">
		     <tr valign="top">
        		<th>
				<label for="seo-language"><?php _e('Language','seo-pressor')?></label>				
					<select id="locale" name="locale">
						<?php // First print the default language?>
						<option <?php echo ($data['locale'] == '')?'selected="selected"':'' ?> value=""><?php _e('Default (English)','seo-pressor');?></option>
						<?php foreach ($all_locales as $all_locales_item) { ?>
							<option <?php echo ($data['locale'] == $all_locales_item)?'selected="selected"':'' ?> value="<?php echo $all_locales_item?>"><?php echo $all_locales_item?></option>
						<?php }?>
					</select>
				</th>
			</tr>
			<tr class="form-field">
                <th>
                    <?php _e('Show SEOPressor Footer','seo-pressor');?>
                </th>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" id="allow_seopressor_footer_checkbox" name="allow_seopressor_footer" value="allow_seopressor_footer"
                    <?php echo ($data['allow_seopressor_footer']=='1')?'checked="checked"':''?>
/>
                    <label for="allow_seopressor_footer_checkbox">
                        <?php _e('Enable SEOPressor Attribution Link.','seo-pressor'); ?>
                    </label>
                    <br/>
                </td>
            </tr>
            <tr class="form-field">
                <td colspan="2">
                    <?php _e('ClickBank ID:','seo-pressor');?>
                   <input class="regular-text" type="text" id="clickbank_id" name="clickbank_id" value="<?php echo $data['clickbank_id']?>" />
                    <br/>
                </td>
            </tr>
			<tr class="form-field">
                <th>
                    <?php _e('Automatic Decoration','seo-pressor');?>
                </th>
            </tr> 
			<tr>
                <td>
                	<input type="checkbox" id="allow_bold_style_to_apply_checkbox" name="allow_bold_style_to_apply" value="1"
                    <?php echo ($data['allow_bold_style_to_apply']=='1')?'checked="checked"':''?>
/>
                    <label for="allow_bold_style_to_apply_checkbox">
                        <?php _e('Allow SEOPressor to automatically decorate your keyword with bold.','seo-pressor'); ?>
                    </label>
                    <br/>
                    <input type="checkbox" id="allow_italic_style_to_apply_checkbox" name="allow_italic_style_to_apply" value="1"
                    <?php echo ($data['allow_italic_style_to_apply']=='1')?'checked="checked"':''?>
/>
                    <label for="allow_italic_style_to_apply_checkbox">
                        <?php _e('Allow SEOPressor to automatically decorate your keyword with italic.','seo-pressor'); ?>
                    </label>
                    <br/>
                    <input type="checkbox" id="allow_underline_style_to_apply_checkbox" name="allow_underline_style_to_apply" value="1"
                    <?php echo ($data['allow_underline_style_to_apply']=='1')?'checked="checked"':''?>
/>
                    <label for="allow_underline_style_to_apply_checkbox">
                        <?php _e('Allow SEOPressor to automatically decorate your keyword with underline.','seo-pressor'); ?>
                    </label>        
                </td>
			</tr>
            <tr>
                <td>     
					<input type="checkbox" id="allow_automatic_adding_alt_keyword_checkbox" name="allow_automatic_adding_alt_keyword" value="1"
                    <?php echo ($data['allow_automatic_adding_alt_keyword']=='1')?'checked="checked"':''?>
/>	
					<label for="allow_automatic_adding_alt_keyword_checkbox">
                        <?php _e('Allow SEOPressor to automatically add alt="keyword" to all images in the content that do not have an alt tag.','seo-pressor'); ?>
                    </label>
                    <br/>
                    <input type="checkbox" id="allow_automatic_adding_rel_nofollow_checkbox" name="allow_automatic_adding_rel_nofollow" value="1"
                    <?php echo ($data['allow_automatic_adding_rel_nofollow']=='1')?'checked="checked"':''?>
/>
                    <label for="allow_automatic_adding_rel_nofollow_checkbox">
                        <?php _e('Allow SEOPressor to automatically add rel="nofollow"  to external links.','seo-pressor'); ?>
                    </label>
                    <br/>
                    <input type="checkbox" id="h1_tag_already_in_theme" name="h1_tag_already_in_theme" value="1"
                    <?php echo ($data['h1_tag_already_in_theme']=='1')?'checked="checked"':''?>
/>
                    <label for="h1_tag_already_in_theme">
                        <?php _e('H1 tag already in theme for Post title.','seo-pressor'); ?>
                    </label> 
                    <br/>
                    <input type="checkbox" id="h2_tag_already_in_theme" name="h2_tag_already_in_theme" value="1"
                    <?php echo ($data['h2_tag_already_in_theme']=='1')?'checked="checked"':''?>
/>
                    <label for="h2_tag_already_in_theme">
                        <?php _e('H2 tag already in theme for Post title.','seo-pressor'); ?>
                    </label> 
                    <br/>
                    <input type="checkbox" id="h3_tag_already_in_theme" name="h3_tag_already_in_theme" value="1"
                    <?php echo ($data['h3_tag_already_in_theme']=='1')?'checked="checked"':''?>
/>
                    <label for="h3_tag_already_in_theme">
                        <?php _e('H3 tag already in theme for Post title.','seo-pressor'); ?>
                    </label> 
                    <br/>
                    <input type="checkbox" id="allow_add_keyword_in_titles" name="allow_add_keyword_in_titles" value="1"
                    <?php echo ($data['allow_add_keyword_in_titles']=='1')?'checked="checked"':''?>
/>
                    <label for="allow_add_keyword_in_titles">
                        <?php _e('Allow SEOPressor to automatically add the Keyword in Posts titles.','seo-pressor'); ?>
                    </label>
                </td>
            </tr>
            <tr class="form-field">
                <th>
                    <?php _e('Select the Bold Style','seo-pressor'); ?>
                </th>
            </tr>
            <tr>
                <td>
                    <?php 
	      	$index = 0;
	      	foreach ($bold_arr as $bold_item) { ?>
                    <label title="bold_style_to_apply_<?php echo $index?>">
                        <input type="radio" name="bold_style_to_apply"
                        <?php echo ($data['bold_style_to_apply']==$index)?'checked="checked"':''?>
 value="<?php echo $index?>">
                        <?php echo htmlentities($bold_item[0]) ?>
                        - 
                        <?php echo htmlentities($bold_item[1]) ?>
                    </label>
                    <br>
                    <?php 
	      		$index++;
	      	} ?>
                    <span class="description">&nbsp;
                        <?php _e('This style will be used to display the keyword in Bold','seo-pressor'); ?>
                    </span>
                </td>
            </tr>
            <tr class="form-field">
                <th>
                    <?php _e('Select the Italic Style','seo-pressor'); ?>
                </th>
            </tr>
            <tr>
                <td>
                    <?php 
	      	$index = 0;
	      	foreach ($italic_arr as $italic_item) { ?>
                    <label title="italic_style_to_apply_<?php echo $index?>">
                        <input type="radio" name="italic_style_to_apply"
                        <?php echo ($data['italic_style_to_apply']==$index)?'checked="checked"':''?>
 value="<?php echo $index?>">
                        <?php echo htmlentities($italic_item[0]) ?>
                        - 
                        <?php echo htmlentities($italic_item[1]) ?>
                    </label>
                    <br>
                    <?php 
	      		$index++;
	      	} ?>
                    <span class="description">&nbsp;
                        <?php _e('This style will be used to display the keyword in Italic','seo-pressor'); ?>
                    </span>
                </td>
            </tr>
            <tr class="form-field">
                <th>
                    <?php _e('Select the Underline Style','seo-pressor'); ?>
                </th>
            </tr>
            <tr>
                <td>
                    <?php 
	      	$index = 0;
	      	foreach ($underline_arr as $underline_item) { ?>
                    <label title="underline_style_to_apply_<?php echo $index?>">
                        <input type="radio" name="underline_style_to_apply"
                        <?php echo ($data['underline_style_to_apply']==$index)?'checked="checked"':''?>
 value="<?php echo $index?>">
                        <?php echo htmlentities($underline_item[0]) ?>
                        - 
                        <?php echo htmlentities($underline_item[1]) ?>
                    </label>
                    <br>
                    <?php 
	      		$index++;
	      	} ?>
                    <span class="description">&nbsp;
                        <?php _e('This style will be used to underline the keyword','seo-pressor'); ?>
                    </span>
                </td>
            </tr>
        </table>
        <h2 id="activation_settings">
            <?php _e('Activation Settings','seo-pressor')?>
        </h2>
        <table class="form-table">
            <tr class="form-field">
                <th>
                    <label for="activation_name">
                        <?php _e('Activation Status','seo-pressor'); ?>
                    </label>
                </th>
                <td><div id="message-by-ajax-activation">
                    <?php echo $msg_status; ?></div>
                </td>
            </tr>
        </table>
        <p class="submit">
            <?php if ($data['active']==0 && $data['allow_manual_reactivation']=='0') { ?>
            <input type="submit" value="<?php _e('Activate Plugin Now','seo-pressor') ?>" class="button-primary" name="Submit_activation" />
            <?php }
            elseif ($data['active']==0 && $data['allow_manual_reactivation']=='1') { ?>
            <input type="submit" value="<?php _e('Re Activate Plugin Now','seo-pressor') ?>" class="button-primary" name="Submit_reactivation" />
            <?php }?>
            <input type="submit" value="<?php _e('Save settings','seo-pressor') ?>" class="button-primary" name="Submit_save_changes" />
        </p>
    </form>
</div>