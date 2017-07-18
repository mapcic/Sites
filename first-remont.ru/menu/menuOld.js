(function($){
	function menu_enterSubmenu( event ) {
		event.preventDefault();
		event.stopPropagation();
			var submenu = $(this),
			submenu_id = submenu.attr('menu'),
			isMob = window.matchMedia('(max-width: 949px)').matches,
			loaded = submenu.attr('loaded'+(isMob? 'Mob' : '')),
			delay = isMob? 0 : 500;

		var	order = submenu.attr('order'),
			sort = submenu.attr('sort');

		var menuTimer = setTimeout(function() {
			if ( loaded == -1 ){
				submenu.attr('loaded', 0);
				$.ajax({
					type : 'POST', url : '/templates/first-remont-2/php/menu.php', dataType: 'json', 
					data: { ids : submenu_id, sort : sort, order : order, isMob : isMob },
					success: function( data ) {
						var submenus = $('#menu #submenus')
							submenuAjax = submenus.find('.submenus[menu="'+data.submenu+'"]').attr('loaded'+(data.isMob? 'Mob' : ''), 1),
							submenu = isMob? submenuAjax.find('.mobSubmenu') : $('#menu #submenu'), 
							html = '<div class="submenu frOff" menu="'+data.submenu+'" offset="0" height="0" parentHeight="0">';
						$.each(data.submenus, function(index, val){
							var node = '<div class="submenuNode level'+val.level+'"><a href="/'+val.path+'">'+val.title+'</a></div>';
							html = html+node;
						});
						$(html+'</div>').appendTo(submenu);						

						submenus.find('.activeSubMenu').removeClass('activeSubMenu');
						submenuAjax.addClass('activeSubMenu');
						submenu.removeClass('frOff').find('.submenu').addClass('frOff').filter('.submenu[menu="'+id+'"]').removeClass('frOff');

						// menu_addSubmenu(data.submenu, data.submenus);
						// menu_showSubmenu( submenuAjax, menu, data.submenu );
					},
					error: function( data ) {
						$('#menu .submenus[menu="'+data.submenu+'"]').attr('loaded'+(data.isMob? 'Mob' : ''), -1);
					}
				});
			}
			if ( loaded == 1 ) {
				menu_showSubmenu( submenu, menu, submenu_id );
				$('#menu #submenus .activeSubMenu').removeClass('activeSubMenu');
				submenu.addClass('activeSubMenu');
				if ( isMob ) {
					submenu.find('.mobSubmenu').removeClass('frOff')
				} else {
					$('#menu #submenu').removeClass('frOff').find('.submenu').addClass('frOff').filter('.submenu[menu="'+submenu_id+'"]').removeClass('frOff');
				}
			}
		}, delay);
		submenu.attr('menuTimer', menuTimer);
	}
	
	// function menu_addSubmenu(submenu_id, data, isMob){
	// 	var submenu = isMob? $('#menu .submenus[menu='+submenu_id+'] .mobSubmenu') : $('#menu #submenu'), 
	// 		html = '<div class="submenu frOff" menu="'+submenu_id+'" offset="0" height="0" parentHeight="0">';
	// 	$.each(data, function(index, val){
	// 		var node = '<div class="submenuNode level'+val.level+'"><a href="/'+val.path+'">'+val.title+'</a></div>';
	// 		html = html+node;
	// 	});
	// 	$(html+'</div>').appendTo(submenu);
	// }

	// function menu_showSubmenu(submenu, menu, id, isMob){
		// menu.find('#submenus .activeSubMenu').removeClass('activeSubMenu');
		// submenu.addClass('activeSubMenu');
		// menu.find('#submenu').removeClass('frOff').find('.submenu').addClass('frOff').filter('.submenu[menu="'+id+'"]').removeClass('frOff');
	// }

	function menu_leave( event ) {
		var menu = $(this);
		menuTimer = setTimeout(function(){
			menu.find('#submenus .activeSubMenu').removeClass('activeSubMenu');
			menu.find('#submenu').addClass('frOff').find('.submenu').addClass('frOff');
		}, 2000);
		menu.attr('menuTimer', menuTimer);
	}

	function menu_enter( event ) {
		clearTimeout($(this).attr('menuTimer'));
	}

	function menu_leaveSubmenu( event ) {
		clearTimeout($(this).attr('menuTimer'));
	}

	function menu_scroll( event ) {
		event.preventDefault(); // lock support in another browers
		event.stopPropagation(); // lock support in another browers

		var wrap = $(this),
			wrapH = wrap.height(),
			node = wrap.children().not('.frOff'),
			nodeH = node.height(),
			offsetMax = wrapH - nodeH;
		
		if ( offsetMax >= 0 ) {
			return false;
		}

		var	direction = event.originalEvent.wheelDelta? +event.originalEvent.wheelDelta : -+event.originalEvent.detail,
			deltaH = direction > 0? 30 : -30,
			offsetH = +node.attr('offset') + deltaH;
		offsetH = ( offsetH > offsetMax )? (( offsetH <= 0 )? offsetH : 0) : offsetMax;

		node.attr('offset', offsetH).css('top', offsetH);
	}

	function menu_windowSize( event ) {
		clearTimeout($(window).attr('menuTimer'));
		var menuTimer = setTimeout(function(){
			var wraps = ['#submenu', '#main-block'];
			$.each(wraps, function(key, val){
				var wrap = $(val),
					oldWH = +wrap.attr('height'),
					newWH = wrap.height(),
					deltaWH = newWH - oldWH;
					nodes = wrap.attr('height', newWH).children();
				nodes.each(function( key, val ){
					var node = $(val),
						offsetH = +node.attr('offset');
					if( window.matchMedia('(min-width: 950px)').matches ){
						if( deltaWH > 0 && offsetH < 0 ){
							offsetH = ( (offsetH = offsetH + deltaWH) > 0 )? 0 : offsetH;
							node.attr('offset', offsetH).attr('smooth')? node.animate({'top' : offsetH}, 500) : '';
							if ( $(window).attr('isMob') == 1 ){
								menu_destMob();
								menu_initDesc();
							}
						}
					}else{
						node.attr('offset', 0).css('top', 0);
						if ( $(window).attr('isMob') == 0 ){
							menu_destDesc();
							menu_initMob();
						}
					}
				});
			});
		}, 1000);
		$(window).attr('menuTimer', menuTimer);
	}

	function menu_init( ) {
		$(window).on('resize', menu_windowSize);

		if ( window.matchMedia('(min-width: 950px)').matches ) {
			menu_initDesc();
		} else {
			menu_initMob();
		}
	}

	function menu_initDesc( ) {
		$(window).attr('isMob', '0')
		$('#menu .submenus').on('mouseenter', menu_enterSubmenu).on('mouseleave', menu_leaveSubmenu);
		$('#menu').on('mouseleave', menu_leave).on('mouseenter', menu_enter);
		$('#main-block').on('mousewheel DOMMouseScroll', menu_scroll);
		$('#submenu').on('mousewheel DOMMouseScroll', menu_scroll);
	}

	function menu_initMob( ) {
		$(window).attr('isMob', '1')
		$('#mobMenu').on('click', menu_mobMenu);
		$('#menu .submenus').on('click', menu_enterSubmenu);
		$('#menu #submenus .mobSubmenu').on('click', menu_stopBubbling);
	}

	function menu_destDesc( ) {
		$('#menu .submenus').off('mouseenter', menu_enterSubmenu).off('mouseleave', menu_leaveSubmenu);
		$('#menu').off('mouseleave', menu_leave).off('mouseenter', menu_enter);
		$('#main-block').off('mousewheel DOMMouseScroll', menu_scroll);
		$('#submenu').off('mousewheel DOMMouseScroll', menu_scroll);
	}

	function menu_destMob( ) {
		$('#mobMenu').off('click', menu_mobMenu);
		$('#menu .submenus').off('click', menu_enterSubmenu);
		$('#menu #submenus .mobSubmenu').off('click', menu_stopBubbling);
	}

	function menu_stopBubbling( event ) {
		event.stopPropagation();
	}

	function menu_mobMenu( event ) {
		event.preventDefault();
		var menu = $('#menu');
		
		if ( menu.hasClass('frOn') ) {
			menu.removeClass('frOn');
		} else {
			menu.addClass('frOn');
		}
	}

	$(document).ready(function(){
		menu_initMob();
	});
})(jQuery);

if (true) {} else {}