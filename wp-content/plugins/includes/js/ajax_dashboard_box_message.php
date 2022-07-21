<script type='text/javascript'>
    //<![CDATA[
	/*
	 * jQuery script for show or hide the Dashboard message.
	 */
    jQuery(document).ready(function($){
		(function() {
		    var container = $('#text_in_seopressor_dashboard_widget');
		    // Request to the Plugin Central Server
		    $.ajax({
		        url: '<?php echo WPPostsRateKeys_Central::$url_box_msg?>',
		        dataType: 'jsonp',
		        data: {
		            'from': 'JS',
		        },
		        success: function(data){
		            container.html(data.response_from_central_server);
		            
		            if (!(container.html())) {
						$('#seopressor_dashboard_widget').css('display', 'none');
					}
		            else {
		            	container.css('display', 'block');
		            }
		        },
		    });
		})();
    });
    //]]>
</script>
<div id='text_in_seopressor_dashboard_widget' style='display:none;'></div>