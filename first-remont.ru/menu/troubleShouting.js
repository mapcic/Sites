var elem = document.getElementById('container');

if (elem.addEventListener) {
  if ('onwheel' in document) {
    // IE9+, FF17+
    elem.addEventListener("wheel", onWheel);
  } else if ('onmousewheel' in document) {
    // устаревший вариант события
    elem.addEventListener("mousewheel", onWheel);
  } else {
    // Firefox < 17
    elem.addEventListener("MozMousePixelScroll", onWheel);
  }
} else { // IE8-
  elem.attachEvent("onmousewheel", onWheel);
}
 onmousewheel onwheel

// Это решение предусматривает поддержку IE8-
function onWheel(e) {
  e = e || window.event;

  // deltaY, detail содержат пиксели
  // wheelDelta не дает возможность узнать количество пикселей
  // onwheel || MozMousePixelScroll || onmousewheel
  var delta = event.deltaY || event.detail || event.wheelDelta;

  var info = document.getElementById('delta');

  info.innerHTML = +info.innerHTML + delta;

  event.preventDefault ? event.preventDefault() : (event.returnValue = false);
}


function menu_scroll(event){
  event = event || window.event;
  event.preventDefault ? event.preventDefault() : (event.returnValue = false);
  event.stopPropagation();
  
  var obj = $(this);
  var delta = event.originalEvent.wheelDelta? +event.originalEvent.wheelDelta : -+event.originalEvent.detail*5;
  var offset = +obj.attr('offset') + delta;
  var diff = +obj.height() - $(window).height()  + +obj.parent().css('padding-top').replace('px', '') + +obj.children().first().css('margin-top').replace('px', '');
  
  if(diff <= 0){
    return false;
  }
  
  offset = (offset > 0)? 0 : ((-offset > delta)? -delta : offset );
  obj.attr('offset', offset);
  obj.css('top', offset);
}