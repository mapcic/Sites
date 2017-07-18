function getUseVkMachine(){
	jQuery.ajax({
		url:"http://machine.shliambur.ru/vkmachine.getUseVkMachine",
		dataType: 'jsonp',
		success: function( resp ) {
			jQuery('#useVkMachine .uvmNum').html(resp.num);
		},
		error: function(){
			setTimeout(getUseVkMachine, 5000);
		}
	});
}