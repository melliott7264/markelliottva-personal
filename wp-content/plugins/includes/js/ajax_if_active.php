<script type='text/javascript'>
    //<![CDATA[
    /*
     * jQuery script for check wether the plugin is active or not and show a notification for that.
     */
    jQuery(document).ready(function($){
        (function(){
            var buffer = '';
            // Request to the Plugin Central Server
            $.ajax({
                url: '<?php echo WPPostsRateKeys_Central::$url_check_if_active?>',
                dataType: 'jsonp',
                data: {
                    'from': 'JS',
                    'clickbank_receipt_number': '<?php echo $clickbank_receipt_number ?>',
                    'plugin_domain': '<?php echo $plugin_domain ?>',
                },
                success: function(data){
                    buffer = data.response_from_central_server;
                },
				cache: false,
                complete: function(){
                    // Back request to the Plugin
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo WPPostsRateKeys::$plugin_url ?>/pages/ajax_if_active.php?t=<?php echo time()?>',
                        dataType: 'html',
                        data: {
                            'data': buffer,
                        },
                        cache: false,
                        async: false,
                        success: function($activation_msg){               
							if (buffer == 'ACTIVE') {
	                        	$('#message-by-ajax-activation').html($activation_msg);
								$('#message-by-ajax-updated').html('<p>' + $activation_msg + '</p>');
								$('#message-by-ajax-updated').css('display', 'block');						
							}
							else if (buffer == 'NODB') {
								$('#message-by-ajax-activation').html($activation_msg);
								$('#message-by-ajax-error').html('<p>' + $activation_msg + '</p>');
								$('#message-by-ajax-error').css('display', 'block');
							}
							else {
								$('#message-by-ajax-activation').html($activation_msg);
								$('#message-by-ajax-error').html('<p>' + $activation_msg + '</p>');
								$('#message-by-ajax-error').css('display', 'block');								
							}
                        },
                    });
                },
            });
        })();
    });
    //]]>
</script>