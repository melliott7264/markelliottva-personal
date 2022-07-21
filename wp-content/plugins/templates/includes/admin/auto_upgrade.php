<?php
/**
 * Template to show the html for auto upgrade
 * 
 * @package admin-panel
 * 
 */
?>
<div class="wrap">
    <div class="icon32" id="icon-plugins">
        <br>
    </div>
    <h2>
        <?php _e('Plugin Upgrade','seo-pressor')?>
    </h2>
    <?php include( WPPostsRateKeys::$template_dir . '/includes/msg.php'); ?>
    <form action="" method="post">
        <?php wp_nonce_field('wp-posts-rate-keys-auto-upgrade');?>
        
        <div class="postbox" id="seopressor-suggestions">
	       <ul>
				<li>
	        		<?php _e('Step 1: Check for requirements.','seo-pressor');?>
	        		<ul>
	        			<li class="<?php echo ($write_permission_requirement)?'suggestions_yes':'suggestions_no'?>">
	        				<?php _e('Write permission on Plugin folder.','seo-pressor');?>
	        			</li>
	        			<li class="<?php echo ($outgoing_connection_requirement)?'suggestions_yes':'suggestions_no'?>">
	        				<?php _e('Outgoing connections to download latest version allowed.','seo-pressor');?>
	        			</li>
	        			<li class="<?php echo ($zip_archive_requirement)?'suggestions_yes':'suggestions_no'?>">
	        				<?php _e('Relevant PHP libraries.','seo-pressor');?>
	        			</li>
	        		</ul>
	        	</li>
	        	<li>
	        		<?php _e('Step 2: Make a backup of the Plugin files.','seo-pressor');?>
	        	</li>
	        	<li>
	        		<?php _e('Step 3: Proceed with Upgrade.','seo-pressor');?>
	        		<br /><br />
	                <input type="submit" value="Proceed with Upgrade" class="button" name="upgrade" id="upgrade">
	        	</li>
	        </ul>
   		</div>
    </form>
</div>