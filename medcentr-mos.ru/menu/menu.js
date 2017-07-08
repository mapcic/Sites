function initMenu(){
    jQuery(window).on('resize', windowSize);
    jQuery('#mobMenu').on('click', menu_switchMobMenu);

    jQuery('.med').attr('desctOn', 0).each(function(ind){
        var $this = jQuery(this),
            fChild = jQuery($this.find('.submed').children()[0]);

        $this.attr('mednum', ind);

        if ( jQuery($this.find('a')[0]).text() == fChild.text() ) {
            fChild.attr('mobileOn', 1);
        }

        var numChild = $this.find('.submed')
            .children().not('[mobileOn=1]').length;

        if ( numChild > 0 ) {
            $this.attr('desctOn', 1);
        }
    });

    if ( window.matchMedia('(min-width: 980px)').matches ) {
        initDescMenu();
    } else {
        initMobMenu();
    }
}

function initMobMenu(){
    jQuery(window).attr('isMob', 1);
    jQuery('#menumed').addClass('ShliambOff');
    jQuery('#mobMenu').removeClass('ShliambOff');

    jQuery('.med').on('click', menu_touch)
        .find('.submed').on('click', stopBubbling)
            .find('[mobileOn=1]').removeClass('ShliambOff');
}

function destrMobMenu(){
    jQuery('.med')
        .off('click', menu_touch)
        .attr('enable', 0)
        .find('.submed')
            .off('click', stopBubbling)
            .addClass('ShliambOff')
            .css('height', 'auto');
}

function initDescMenu(){
    jQuery(window).attr('isMob', 0);
    jQuery('#menumed').removeClass('ShliambOff');
    jQuery('#mobMenu').addClass('ShliambOff');

    jQuery('.med[desctOn=1]').on('mouseenter', menu_in).on('mouseleave', menu_out)
        .find('.submed [mobileOn=1]')
            .addClass('ShliambOff');
}

function destrDescMenu(){
    jQuery('.med').attr('enable', 0)
        .filter('[desctOn=1]')
        .off('mouseenter', menu_in).off('mouseleave', menu_out)
        .each(function(){
            var $this = jQuery(this);
            clearTimeout($this.attr('menuTimerIn'));
            clearTimeout($this.attr('menuTimerOut'));
        })
        .find('.submed')
            .addClass('ShliambOff')
            .css('height', 'auto');
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
    var submed = obj.find('.submed'),
        isMob = jQuery(window).attr('isMob') == 1;

    isMob? obj.off('click', menu_touch): '';
    
    obj.attr('enable', 0);

    submed.animate(
        {'height' : 0}, 500, 
        function(){
            submed.addClass('ShliambOff').css('height', 'auto');
            isMob? obj.on('click', menu_touch): '';
        });
}

function menu_open( obj ) {
    var submed = obj.find('.submed'), 
        hSubmed = submed.removeClass('ShliambOff').height(),
        isMob = jQuery(window).attr('isMob') == 1;

    obj.attr('enable', 1);

    isMob? obj.off('click', menu_touch): '';

    submed.css('height', '0').animate(
        {'height' : hSubmed}, 500, 
        function(){
            submed.css('height', 'auto');
            isMob? obj.on('click', menu_touch): '';
        });
}

function menu_openDesct( obj ) {
    var enabledNodes = jQuery('.med[enable=1]');

    enabledNodes.each(function(ind){
        var $this = jQuery(this);
        clearTimeout($this.attr('menuTimerOut'));
        menu_close($this);
    });

    menu_open(obj);
}

function menu_in( event ) {
    event.preventDefault();

    var $this = jQuery(this),
        enabledNodes = jQuery('.med[enable=1]');

    clearTimeout($this.attr('menuTimerOut'));

    if ($this.attr('enable') != 1) {
        menuTimer = setTimeout(menu_openDesct, 500, $this);
        $this.attr('menuTimerIn', menuTimer);
    }

}

function menu_out( event ) {
    event.preventDefault();

    var $this = jQuery(this);

    clearTimeout($this.attr('menuTimerIn'));

    if ( $this.attr('enable') == 1){
        menuTimer = setTimeout(menu_close, 500, $this);
        $this.attr('menuTimerOut', menuTimer);
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
            {'height' : hMenumed}, 500, 
            function(){
                menumed.css('height', 'auto');
            });
    } else {
        menumed.animate(
            {'height' : 0}, 500, function(){
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