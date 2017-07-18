function machineBox() {
    initMachineGB();
    initMachineIB();
    $(window).off('resize', machineResizeW);
    $(window).on('resize', machineResizeW);
}

function initMachineGB() {
    var gls = $('.machineGalleryBox');

    gls.each(function(ind){
        var gl = $(this).attr('gId', ind),
            imgs = gl.find('img').attr('gId', ind);
        
        if(imgs.length == 0){
            return 1;
        }

        imgs.filter('.machineImgBox').removeClass('machineImgBox');
        imgs.each(function(ind){
            var img = $(this).attr('mpId', ind - 1).attr('mId', ind).attr('mnId', ind + 1);
        });
        $(imgs[0]).attr('mpId', '');
        $(imgs[imgs.length-1]).attr('mnId', '');

        imgs.off('click', machineOpenI);
        imgs.on('click', machineOpenI);
    });

    return 1;
}

function initMachineIB() {
    var imgs = $('img.machineImgBox');

    if ( imgs.length == 0 ) {
        return 0;
    }

    imgs.each(function(ind){
        var img = $(this).attr('mpId', ind - 1).attr('mId', ind).attr('mnId', ind + 1);
    });
    $(imgs[0]).attr('mpId', '');
    $(imgs[imgs.length-1]).attr('mnId', '');

    imgs.off('click', machineOpenI);
    imgs.on('click', machineOpenI);

    return 1;
}

function machineLoadI( data ) {
    var box = $('#machineBox'),
        imgHD = box.find('img'),
        btns = box.find('#machineNextImg, #machinePreviosImg');

    imgHD.attr('src', data.srcHd).on('load', function(){
        var img = $(this),
            mW = this.width,
            mH = this.height;
        machineStatusLoadOff();
        img.attr('mnId', data.mnId).attr('mpId', data.mpId).attr('gId', data.gId).attr('mH', mH).attr('mW', mW).parent().removeClass('machineOff').height(mH).width(mW).parent().removeClass('machineOff');
        machineSizeI( img );
    }).on('error', function(){
        machineCloseB();
        machineStatusLoadOff();
    });

    btns.removeClass('machineOff');
    if( data.mnId == ''){
        btns.filter('#machineNextImg').addClass('machineOff');
    }
    if( data.mpId == '' ){
        btns.filter('#machinePreviosImg').addClass('machineOff');
    }
}


function machineOpenI( event ) {
    event.preventDefault();
    event.stopPropagation();

    machineCloseB();
    var img = $(this),
        box = machineCreateB().addClass('machineOff');

    machineStatusLoadOn(img);

    machineLoadI({
        srcHd : img.attr('hd'),
        gId : (img.attr('gId')? img.attr('gId') : false),
        mnId : img.attr('mnId'),
        mpId : img.attr('mpId')
    });
}

function machineLeafI( event ){
    event.preventDefault();
    event.stopPropagation();


    var but = $(this),
        to = but.filter('#machineNextImg').length == 1? 'mnId' : 'mpId',
        img = but.parent().addClass('machineOff').find('img'),
        toImg = img.attr('gId') != ''? $('.machineGalleryBox[gId="'+img.attr('gId')+'"] img[mId="'+img.attr(to)+'"]') : $('.machineImgBox["'+img.attr(to)+'"]');

    machineStatusLoadOn($('#machineBox #machineDimmer'));

    machineLoadI({
        srcHd : toImg.attr('hd'),
        gId : (toImg.attr('gId')? toImg.attr('gId') : false),
        mnId : toImg.attr('mnId'),
        mpId : toImg.attr('mpId')
    });
}

function machineKeyControl( event ){
    var nextBtn = $('#machineNextImg'),
        prevBtn = $('#machinePreviosImg');

    if( event.which == 39 && nextBtn.css('display') != 'none' ){
        event.preventDefault()
        nextBtn.trigger('click');
    }

    if( event.which == 37 && prevBtn.css('display') != 'none' ){
        event.preventDefault()
        prevBtn.trigger('click');
    }

    if( event.which == 27 ){
        event.preventDefault()
        $('#machineDimmer').trigger('click');
    }

}

function machineSizeI( img = null ) {
    var img = img? img : $('#machineBox img'),
        marginH = 100, marginW = 100,
        imgH = img.attr('mH'), imgW = img.attr('mW'),
        // imgH = img.height(), imgW = img.width(),
        winH = $(window).height(), winW = $(window).width(),
        dH = winH - imgH - marginH, dW = winW - imgW - marginW,
        newImgH = winH - marginH, newImgW = winW - marginW;

    if ( dH < 0 || dW < 0) {
        if ( dH < dW ) {
            imgW = imgW*newImgH/imgH;
            imgH = newImgH;
        } else {
            imgH = imgH*newImgW/imgW;
            imgW = newImgW;
        }
    }

    img.parent().height(imgH).width(imgW);

    return [imgW, imgH];
}

function machineCreateB() {
    var box = '<div id="machineBox"><div id="machineDimmer"></div><div id="machineImgBox" class="machineOff"><div id="machinePreviosImg"><div class="machinePreviosT"></div></div><img src="" mnId="" mpId="" gId=""><div id="machineNextImg"><div class="machineNextT"></div></div><div id="machineCloseBox"><span id="machineCloseX">X</span></div></div><div id="machineImgWait"></div></div>';

    if ( $('#machineBox').length != 0 ) {
        $('#machineBox').remove();
    }

    box = $(box).appendTo('body');
    box.find('#machinePreviosImg, #machineNextImg').on('click', machineLeafI);
    box.find('#machineDimmer, #machineCloseBox').on('click', machineCloseB);

    $(document).on('keydown', machineKeyControl);

    return box;   
}

function machineResizeW() {
    clearTimeout($(window).attr('machineBoxT'));

    var machineBoxT = setTimeout(machineSizeI, 1000);
}

function machineCloseB( event ) {
    $('#machineBox').remove();
    machineStatusLoadOff();
    $(document).off('keydown', machineKeyControl);
}

function machineStatusLoadOn( node ){
    machineStatusLoadOff();
    var nH = node.height(),
        nW = node.width();

    node.wrap('<div id="machineWrap"></div>')
        .parent().height(nH).width(nW)
            .append('<div id="machineWrapDimmer"></div><div id="machineLoader"></div>');
    $('#machineWrapDimmer').on('click', machineCloseB);
}

function machineStatusLoadOff(){
    var wrap = $('#machineWrap');

    wrap.find('#machineLoader, #machineWrapDimmer').remove();
    wrap.children().unwrap();
}