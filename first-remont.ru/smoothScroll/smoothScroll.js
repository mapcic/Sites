jQuery(document).ready(function(){
	jQuery('a.yakor').on('click', function(event){
		event.preventDefault();
		var anchor = jQuery(this);
		jQuery('html, body').stop().animate({
		scrollTop: jQuery(anchor.attr('href')).offset().top
	}, 500).animate({
		scrollTop: jQuery(this.hash).offset().top -150
	}, 500 );
	e.preventDefault();
	});
	return false;
});

function smoothScroll(event) {
	event.preventDefault();
	var anchor = jQuery(this);
	jQuery('html, body').stop()
		.animate({
			scrollTop: jQuery(anchor.attr('href')).offset().top
		}, 500)
		.animate({
			scrollTop: jQuery(this.hash).offset().top -150
		}, 500 );
	return false;
}
// Устанавливаем class="active" у span
//document.getElementById("user_articles").getElementsByTagName('div')[0].setAttribute("class", "active");
