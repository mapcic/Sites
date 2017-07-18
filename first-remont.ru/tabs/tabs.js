(function($){
	$(".tab_content").addClass('frOff');
	$("ul.tabs li:first").addClass('active');
	$(".tab_content:first").removeClass('frOff');
	
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass('active');
		$(this).addClass('active');
		$(".tab_content").addClass('frOff');
		$($(this).find('a').attr('href')).removeClass('frOff');
		return false;
	});
})(jQuery);