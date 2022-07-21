<script type='text/javascript'>
    //<![CDATA[
	/*
	 * jQuery script for check wether is it an updated version of SEOPressor.
	 */
    jQuery(document).ready(function($){
    	var buffer_last_version = '';
        // Request to the Plugin Central Server
        $.ajax({
            url: '<?php echo WPPostsRateKeys_Central::$url_check_last_version ?>',
            dataType: 'jsonp',
            data: {
                'from': 'JS',
            },
            success: function(data){
            	buffer_last_version = data.response_from_central_server;
            },
            complete: function(){
                // Back request to the Plugin
                $.ajax({
                    type: 'POST',
                    url: '<?php echo  WPPostsRateKeys::$plugin_url?>/pages/ajax_last_version.php',
                    data: {
                        'data': buffer_last_version,
                    },
                    async: false,
                });
            },
        });
    });
    //]]>
</script>