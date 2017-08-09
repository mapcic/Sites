function getUseVkMachine(){
	jQuery.ajax({
		url:"path/to/getUseVkMachine.php",
		dataType: 'json',
		success: function( resp ) {
			jQuery('#useVkMachine .uvmNum').html(resp.num);
		},
		error: function(){
			setTimeout(getUseVkMachine, 5000);
		}
	});
}

jQuery(document).ready(function(){
	getUseVkMachine();
});