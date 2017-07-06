function initMenu(){
    jQuery(window).on('resize', windowSize);
    jQuery('#mobMenu').on('click', menu_switchMobMenu);

    if ( window.matchMedia('(min-width: 980px)').matches ) {
        destrMobMenu();
        initDescMenu();
    } else {
        destrDescMenu();
        initMobMenu();
    }

    jQuery('.med').each(function(ind){
        jQuery(this).attr('mednum', ind);
    });
}

function initMobMenu(){
    jQuery(window).attr('isMob', 1);
    jQuery('#menumed').addClass('ShliambOff');
    jQuery('#mobMenu').removeClass('ShliambOff');

    jQuery('.med').on('click', menu_touch);
    jQuery('.submed').on('click', stopBubbling);
}

function destrMobMenu(){
    jQuery('.med').off('click', menu_touch);
    jQuery('.submed').off('click', stopBubbling);

    jQuery('.med').attr('enable', 0)
        .find('.submed').addClass('ShliambOff').css('height', 'auto');  
}

function initDescMenu(){
    jQuery(window).attr('isMob', 0);
    jQuery('#menumed').removeClass('ShliambOff');
    jQuery('#mobMenu').addClass('ShliambOff');

    jQuery('.med').on('mouseenter', menu_in);
    jQuery('.med').on('mouseleave', menu_out);
}

function destrDescMenu(){
    jQuery('.med').off('mouseenter', menu_in);
    jQuery('.med').off('mouseleave', menu_out);

    jQuery('.med').attr('enable', 0)
        .find('.submed').addClass('ShliambOff').css('height', 'auto');    
    clearTimeout(+jQuery(window).attr('menuTimerIn'));
    clearTimeout(+jQuery(window).attr('menuTimerOut'));
}


function windowSize( event ) {
    clearTimeout(jQuery(window).attr('menuTimer'));

    var menuTimer = setTimeout(function(){
        if( window.matchMedia('(min-width: 980px)').matches ) {
            if ( jQuery(window).attr('isMob') == 1 ) {
                destrMobMenu();
                initDescMenu();
            }
        }else{
            if ( jQuery(window).attr('isMob') == 0 ) {
                destrDescMenu();
                initMobMenu();
            }
        }
    }, 500);
    
    jQuery(window).attr('menuTimer', menuTimer);
}

function menu_close( obj ) {
    var submed = obj.find('.submed');
    submed.animate(
        {'height' : 0}, 1000, 
        function(){
            obj.attr('enable', 0);
            submed.addClass('ShliambOff').css('height', 'auto');
        });
}

function menu_open( obj ) {
    var submed = obj.find('.submed'), 
        hSubmed = submed.removeClass('ShliambOff').height();

    obj.attr('enable', 1);

    submed.css('height', '0').animate(
        {'height' : hSubmed}, 1000, 
        function(){
            submed.css('height', 'auto');
        });
}

function menu_openDesc( objIn, objOut ) {
    clearTimeout(+jQuery(window).attr('menuTimerOut'));
    if (objIn.attr('mednum') != objOut.attr('mednum')){
        menu_close(objOut);
    }
    if (objIn.attr('enable') != 1){
        menu_open(objIn);
    }
}

function menu_in( event ) {
    event.preventDefault();
    clearTimeout(+jQuery(window).attr('menuTimerOut'));

    var $this = jQuery(this),
        enabledNode = jQuery('.med[enable=1]');

    menuTimer = setTimeout(menu_openDesc, 1000, $this, enabledNode);
    jQuery(window).attr('menuTimerIn', menuTimer);
}

function menu_out( event ) {
    event.preventDefault();
    clearTimeout(+jQuery(window).attr('menuTimerIn'));
    clearTimeout(+jQuery(window).attr('menuTimerOut'));

    var $this = jQuery(this);

    if ( $this.attr('enable') == 1){
        menuTimer = setTimeout(menu_close, 1000, $this);
        jQuery(window).attr('menuTimerOut', menuTimer);
    }
}

function menu_touch(event) {
    event.preventDefault();

    var $this = jQuery(this);

    if ($this.attr('enable') == 1) {
        menu_close($this);
    } else {
        menu_open($this);
    }
}

function menu_switchMobMenu( event ) {
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

function stopBubbling( event ) {
    event.stopPropagation();
}

jQuery(document).ready(function(){
    initMenu();
});