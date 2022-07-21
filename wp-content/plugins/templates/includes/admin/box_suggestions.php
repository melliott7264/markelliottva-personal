<?php
/**
 * Template to show the Suggestions
 *
 * @uses	float	$box_keyword_density
 * @uses	string	$box_keyword_density_message	can be blank
 * @uses	string	$box_keyword
 * @uses	float	$box_score
 * @uses	array	$box_suggestions_arr
 * @uses	int	$box_suggestions_arr_item[0]	1- YES, 0- NO
 * @uses	string	$box_suggestions_arr_item[1] 	text with a suggetions like "Please add your keyword in the first sentence."
 * 
 * @package admin-panel
 * 
 */
?>
<div class="wrap widget_box">
    <table>
        <thead>
            <th class="box_table_header center">
                <?php _e('Score','seo-pressor'); ?>
            </th>
            <th class="box_table_header">
                <?php _e('SEOPressor Keyword','seo-pressor'); ?>
            </th>
        </thead>
        <tbody>
            <tr>
                <td title="<?php echo ($box_score==-1)?__('-1 Score because the License is invalid','seo-pressor'):$box_score?>" class="<?php echo ($box_score<50)?'box_score_red':'box_score_green'?>">
                    <?php echo $box_score?>
                    <?php 
                    if ($box_score!=-1) {
                    	_e('%','seo-pressor');			 
                    }?>
                </td>
                <td class="widget_box <?php echo ($box_score<50)?'box_score_red_text':'box_score_green_text'?>" style="text-align:center">
                    <?php echo $box_keyword?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php 
	if ($box_keyword!='') {
		if ($box_keyword_density!==NULL) {?>
    <table>
        <thead>
            <th colspan="2" class="box_table_header center">
                <?php _e('Keyword Density','seo-pressor'); ?>
            </th>
        </thead>
        <tbody>
            <tr>
                <td class="<?php echo ($box_keyword_density>6 || $box_keyword_density<1)?'box_score_red':'box_score_green'?>">
                    <?php echo $box_keyword_density;?>
                    <?php _e('%','seo-pressor');?>
                </td>
                <?php if ($box_keyword_density_message!='') {?>
                <td class="widget_box <?php echo ($box_keyword_density>6 || $box_keyword_density<1)?'box_score_red_text':'box_score_green_text'?>">
                    <?php echo $box_keyword_density_message;?>
                </td>
                <?php }?>
            </tr>
        </tbody>
    </table>
    <?php 
		}?>
    <ul>
        <?php foreach ($box_suggestions_arr as $box_suggestions_arr_item) {?>
        <li class="<?php echo ($box_suggestions_arr_item[0]==1)?'suggestions_yes':'suggestions_no'?>">
            <?php echo $box_suggestions_arr_item[1];?>
        </li>
        <?php }?>
    </ul>
    <?php }?>
</div>
