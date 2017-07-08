jQuery('.med a').each(function(index){
	var $this = jQuery(this),
		position = parseInt($this.css('background-position-y'));
	$this.animate(
        {'background-position-y': position + 10}, 1000,
        function(){
        	$this.animate({'background-position-y': position}, 1000);
        }
	);
})