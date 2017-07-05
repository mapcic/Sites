function initMenu(){
    jQuery(window).on('resize', windowSize);

    // jQuery('.med').on('click', switchMenu);
    // jQuery('.submed').on('click', stopBubbling);
    jQuery('#mobMenu').on('click', switchMenuMed);

    if ( window.matchMedia('(min-width: 980px)').matches ) {
        initDescMenu();
    } else {
        initMobMenu();
    }
}

function initMobMenu(){
    jQuery(window).attr('isMob', '1');
    jQuery('#menumed').addClass('ShliambOff');
    jQuery('#mobMenu').removeClass('ShliambOff');
}

function initDescMenu(){
    jQuery(window).attr('isMob', '0');
    jQuery('#menumed').removeClass('ShliambOff');
    jQuery('#mobMenu').addClass('ShliambOff');
}

function windowSize( event ) {
    clearTimeout(jQuery(window).attr('menuTimer'));

    var menuTimer = setTimeout(function(){
        if( window.matchMedia('(min-width: 980px)').matches ) {
            if ( jQuery(window).attr('isMob') == 1 ) {
                initDescMenu();
            }
        }else{
            if ( jQuery(window).attr('isMob') == 0 ) {
                initMobMenu();
            }
        }
    }, 500);
    
    jQuery(window).attr('menuTimer', menuTimer);
}

function menu_close( obj ) {
    obj.animate(
        {'height' : 0}, 1000, 
        function(){
            obj.addClass('ShliambOff').css('height', 'auto');
        });
}

function menu_open( obj ) {
    var hObj = obj.removeClass('ShliambOff').height();
    obj.css('height', '0').animate(
        {'height' : hObj}, 1000, 
        function(){
            obj.css('height', 'auto');
        });
}

function menu_in( event ) {
    event.preventDefault();

    var $this = jQuery(this),
        enabledNode = jQuery('.med[enabled=1]');

    menu_close( enabledNode );
    menu_open($this);
    // jQuery.when(menu_close( enabledNode )).then(menu_open($this));
}

function menu_out( obj ) {
    event.preventDefault();

    var $this = jQuery(this);

    if ( $this.attr('enable') == 1){
        menu_close( enabledNode );
    }
}

function switchMenu(event) {
    event.preventDefault();
    
    var $this = jQuery(this),
        isEnable = +$this.attr('enable'),
        menus = jQuery('.med'),
        nodes = menus.find('.submed'),
        node = $this.find('.submed');

    if (isEnable != 1){
        enabled = menus.filter('[enable="1"]').find('.submed');

        menus.attr('enable', 0);
        $this.attr('enable', 1);
        
        var hNode = node.removeClass('ShliambOff').height();
        node.css('height', '0').animate(
            {'height' : hNode}, 1000, 
            function(){
                node.css('height', 'auto');
            });

        enabled.animate(
            {'height' : 0}, 1000, function(){
            enabled.addClass('ShliambOff')
                .css('height', 'auto');
        });
    } else {
        $this.attr('enable', 0);
        node.animate(
            {'height' : 0}, 1000, function(){
            node.addClass('ShliambOff').css('height', 'auto');
        });
    }
}

function stopBubbling( event ) {
    event.stopPropagation();
}

function switchMenuMed( event ) {
    event.preventDefault();

    var menumed = jQuery('#menumed');
    if (menumed.hasClass('ShliambOff')) {
        var hMenumed = menumed.removeClass('ShliambOff').height();
        menumed.css('height', '0').animate(
            {'height' : hMenumed}, 1000, 
            function(){
                menumed.css('height', 'auto');
            });
    } else {
        menumed.animate(
            {'height' : 0}, 1000, function(){
            menumed.addClass('ShliambOff').css('height', 'auto');
        });
    }
}

jQuery(document).ready(function(){
    initMenu();
});