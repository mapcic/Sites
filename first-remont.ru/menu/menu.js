function menu_init( ) {
	$(window).on('resize', menu_windowSize);

	if ( window.matchMedia('(min-width: 980px)').matches ) {
		menu_initDesc();
	} else {
		menu_initMob();
	}

	menu_node_init();
}

function menu_initDesc( ) {
	$(window).attr('isMob', '0');
	$('#menu .submenus').on('mouseenter', menu_enterSubmenu).on('mouseleave', menu_leaveSubmenu);
	$('#menu').on('mouseleave', menu_leave).on('mouseenter', menu_enter);
	$('#main-block').on('mousewheel DOMMouseScroll', menu_scroll);
	$('#submenu').on('mousewheel DOMMouseScroll', menu_scroll);
}

function menu_initMob( ) {
	$(window).attr('isMob', '1');
	$('#mobMenu').on('click', menu_mobMenu);
	$('#menu .submenus').on('click', menu_enterSubmenu);
	$('#menu #submenus .mobSubmenu').on('click', menu_stopBubbling);
}

function menu_destDesc( ) {
	$('#menu .submenus').off('mouseenter', menu_enterSubmenu).off('mouseleave', menu_leaveSubmenu);
	$('#menu').off('mouseleave', menu_leave).off('mouseenter', menu_enter);
	$('#main-block').off('mousewheel DOMMouseScroll', menu_scroll);
	$('#submenu').off('mousewheel DOMMouseScroll', menu_scroll);

	var wraps = ['#submenu', '#main-block'];
	$.each(wraps, function(key, val){
		$(val).removeAttr('height').children().css('top', 0);
	});
}

function menu_destMob( ) {
	$('#mobMenu').off('click', menu_mobMenu);
	$('#menu .submenus').off('click', menu_enterSubmenu);
	$('#menu #submenus mobSubmenu').off('click', menu_stopBubbling);

	$('#menu').removeClass('frOn').find('.submenus').removeClass('activeSubMenu').find('.mobSubmenu').addClass('frOff');
}

function menu_windowSize( event ) {
	clearTimeout($(window).attr('menuTimer'));

	var menuTimer = setTimeout(function(){
		var wraps = ['#submenu:not(.frOff)', '#main-block'];
		
		$.each(wraps, function(key, val){
			var wrap = $(val);
			
			if (wrap.length == 0){
				return true;
			}			
			
			var	oldWH = wrap.attr('height')? +wrap.attr('height') : wrap.height(),
				newWH = wrap.height(),
				deltaWH = newWH - oldWH,
				nodes = wrap.attr('height', newWH).children();
			
			nodes.each(function( key, val ){
				var node = $(val),
					offsetH = +node.attr('offset');
				
				if( window.matchMedia('(min-width: 980px)').matches ) {
					if( deltaWH > 0 && offsetH < 0 ) {
						offsetH = ( (offsetH = offsetH + deltaWH) > 0 )? 0 : offsetH;
						node.attr('offset', offsetH).animate({'top' : offsetH}, 200);
					}
					if ( $(window).attr('isMob') == 1 ) {
						menu_destMob();
						menu_initDesc();
					}
				}else{
					node.attr('offset', 0).css('top', 0);
					if ( $(window).attr('isMob') == 0 ) {
						menu_destDesc();
						menu_initMob();
					}
				}
			});
		});
	}, 500);
	
	$(window).attr('menuTimer', menuTimer);
}

function menu_enterSubmenu( event ) {
	event.preventDefault();

	var submenu = $(this),
		submenu_id = submenu.attr('menu'),
		isMob = $(window).attr('isMob'),
		loaded = submenu.attr('loaded'+((isMob == 1)? 'Mob' : '')),
		delay = (isMob == 1)? 0 : 500;

	var	order = submenu.attr('order'),
		sort = submenu.attr('sort');

	var menuTimer = setTimeout(function() {
		if ( loaded == -1 ){
			submenu.attr('loaded'+((isMob == 1)? 'Mob' : ''), 0);
			$.ajax({
				type : 'POST', url : '/templates/first-remont-2/php/menu.php', dataType: 'json', 
				data: { ids : submenu_id, sort : sort, order : order, isMob : isMob },
				success: function( data ) {	
					menu_addSubmenu( data ); 
					menu_switchSubmenu( data.submenu );
				},
				error: function( data ) {
					var isMob = (data.isMob == 1)? true : false;
					$('#menu .submenus[menu="'+data.submenu+'"]').attr('loaded'+(isMob? 'Mob' : ''), -1);
				}
			});
		}
		if ( loaded == 1 ) {
			menu_switchSubmenu( submenu_id );
		}
	}, delay);
	submenu.attr('menuTimer', menuTimer);
}

function menu_addSubmenu( data ) {
	var submenus = $('#menu #submenus'),
		isMob = data.isMob == 1? true : false,
		submenuAjax = submenus.find('.submenus[menu="'+data.submenu+'"]').attr('loaded'+(isMob? 'Mob' : ''), 1),
		submenu = isMob? submenuAjax.find('.mobSubmenu') : $('#menu #submenu'), 
		html = '<div class="submenu '+(isMob? '': 'frOff')+'" menu="'+data.submenu+'" offset="0">';

	$.each(data.submenus, function(index, val){
		var node = '<div class="submenuNode level'+val.level+'"><a href="/'+val.path+'">'+val.title+'</a></div>';
		html = html+node;
	});
	$(html+'</div>').appendTo(submenu);	
}

function menu_switchSubmenu( id ) {
	var isMob = $(window).attr('isMob') == 1? true : false,
		menu = $('#menu'),
		submenu = menu.find('.submenus[menu="'+id+'"]').addClass('activeSubMenu');
	
	if ( isMob ) {
		var mobSubmenu = submenu.find('.mobSubmenu'),
			isMSMOff = mobSubmenu.hasClass('frOff');
		if( isMSMOff ){
			var hMobSubMemu = mobSubmenu.removeClass('frOff').height();
			mobSubmenu.removeClass('frOff').css('height', 0).animate({'height' : hMobSubMemu}, 1000, function(){
				mobSubmenu.css('height', 'auto');
			});
		}else{
			mobSubmenu.animate({'height' : 0}, 1000, function(){
				mobSubmenu.css('height', 'auto');
				mobSubmenu.addClass('frOff');
				submenu.removeClass('activeSubMenu');
			});
		}
	} else {
		menu.find('#submenus .submenus').removeClass('activeSubMenu');
		submenu.addClass('activeSubMenu');
		menu.find('#submenu').removeClass('frOff').find('.submenu').addClass('frOff').filter('.submenu[menu="'+id+'"]').removeClass('frOff');
	}
}

function menu_scroll( event ) {
	event.preventDefault();
	event.stopPropagation();

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

function menu_leave( event ) {
	var menu = $(this);
	
	var menuTimer = setTimeout(function(){
		menu.find('#submenus .activeSubMenu').removeClass('activeSubMenu');
		menu.find('#submenu').addClass('frOff').find('.submenu').addClass('frOff').css('top', 0);
	}, 1000);
	
	menu.attr('menuTimer', menuTimer);
}

function menu_leaveSubmenu( event ) {
	clearTimeout($(this).attr('menuTimer'));
}

function menu_enter( event ) {
	clearTimeout($(this).attr('menuTimer'));
}

function menu_stopBubbling( event ) {
	event.stopPropagation();
}

function menu_mobMenu( event ) {
	event.preventDefault();
	var menu = $('#menu');

	if ( menu.hasClass('frOn') ) {
		menu.animate({'height' : 0}, 1000, function(){
			menu.removeClass('frOn');
			menu.css('height', 'auto');
		});
	} else {
		var hMenu = menu.addClass('frOn').height();
		menu.css('height', '0').animate({'height' : hMenu}, 1000, function(){
			menu.css('height', 'auto');
		});
	}
}

function menu_nodeInit() {
	jQuery('select.menuNode').one('focus', menu_loadMenuNode);
}

function menu_loadMenuNode( event ) {
	event.preventDefault();

	var node = jQuery(this),
		loaded = node.attr('loaded'),
		menuId = node.attr('menu'),
		order = submenu.attr('order'),
		sort = submenu.attr('sort');

	jQuery.ajax({
		type : 'POST', url : '/templates/first-remont-2/php/menu.php', dataType: 'json', 
		data: { ids : menuId, sort : sort, order : order, isMob : isMob },
		success: function( data ) {
			menu_nodeAddOptions( data );
		},
		error: function( data ) {
			node.one('focus', menu_loadMenuNode);
		}
	});
}

function menu_nodeAddOptions( data ) {
	var node = jQuery('select.menuNode[menu='+data.submenu+']'),
		regex = new RegExp('(?:'+node.attr('del').replace(/\s*,\s*/, '?|')+')', 'i'),
		options = '';

	jQuery.each(data.submenus, function(id, val){
		options = options + '<option value="'+val.path+'">'+val.title.replace(regex, '')+'</option>';
	});

	node.append(options);
	node.on('change', menu_nodeChangeOption);
}

function menu_nodeChangeOption( event ){
	event.preventDefault();
	document.location.href = jQuery(this).val();
}