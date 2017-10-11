function startAnimate() {
	var adress = jQuery('.item-102 a');
	
	menuAnimate(adress);
}

function menuAnimate(obj) {
	function onBright(obj) {
		obj.css({
		    'border': '#ffe211 solid 2px'
		});
		setTimeout(offBright, 500, obj);
	}
	function offBright(obj) { 
		obj.css({
		    'border': '#dde3e9 solid 2px'
		});
		setTimeout(onBright, 500, obj);
	}
	setTimeout(offBright, 500, obj);
}