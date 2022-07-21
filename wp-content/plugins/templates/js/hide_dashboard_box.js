<!-- // Used only for Plugin-Requests -->
<script type='text/javascript'>
//<![CDATA[
	/*
	 * jQuery script for hide the Dashboard widget if there is no message.
	 */
	jQuery(document).ready(function($){
		(function(){
			if (!($('#seopressor_dashboard_widget #text_in_seopressor_dashboard_widget').html())) {
				$('#seopressor_dashboard_widget').css('display', 'none');
			}
		})();
	});
//]]>
</script>