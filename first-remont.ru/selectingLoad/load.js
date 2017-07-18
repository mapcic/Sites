function addScript(src){
  var script = document.createElement('script');
  script.src = src;
  script.async = false; // чтобы гарантировать порядок
  document.head.appendChild(script);
}

addScript('1.js'); // загружаться эти скрипты начнут сразу
addScript('2.js'); // выполнятся, как только загрузятся
addScript('3.js'); // но, гарантированно, в порядке 1 -> 2 -> 3

// 

var script = document.createElement('script');
script.src = "https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.3.0/lodash.js"
document.body.appendChild(script);

script.onload = function() {
    // после выполнения скрипта становится доступна функция _
    alert( _ ); // её код
  }

// 

var script = document.createElement('script');
script.src = "https://example.com/404.js"
document.body.appendChild(script);

script.onerror = function() {
  alert( "Ошибка: " + this.src );
};

// My

var script = document.createElement('script');

script.src = src;
script.onload = function(){
}

document.head.appendChild(script);

(function($){
	$(document).ready(function(){
		if( window.matchMedia('(min-width: 950px)').matches ){
			var flag = false;
			if(window.VK){
				VK.Widgets.Like("vk_like", {type: "mini"});
				$('div[name=vkSocial]').removeClass('frOff');
				flag = true;
			}						
			scripts = {
				'yaSocial' : '//yandex.st/share/share.js',
				'gSocial' : 'https://apis.google.com/js/plusone.js'
			}
			$.each(scripts, function(key, val){
				var script = document.createElement('script');
				script.src = val;
				script.onload = function(){
					$('div[name='+key+']').removeClass('frOff');
					flag = true;
				}
				document.body.appendChild(script);
			});
			if(flag){
				$('div[name=social]').removeClass('frOff');
			}
		}
	});
})(jQuery);