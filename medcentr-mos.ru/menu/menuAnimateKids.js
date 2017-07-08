function menu_kidsAnimate(){
	var menuKinds = jQuery('.med a.kids'),
		position = parseInt(menuKinds.css('background-position-y'));
	menuKinds.animate(
        {'background-position-y': position - 2}, 1000,
        function(){
        	menuKinds.animate(
        		{'background-position-y': position}, 1000,
        		function(){
        			setTimeout(menu_kidsAnimate, 1000);
        		}
        	);
        }
	);
}