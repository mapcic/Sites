jQuery('form#emailForm').submit(sentEmail);
jQuery(function($){
	$("#phoner").mask("+7 (999) 999-99-99");
});

function sentEmail(event){
	event.preventDefault();
	
	email = jQuery(this).find('input[name="email"]').attr('value');
	mob = jQuery(this).find('input[name="mob"]').attr('value');
	mess = jQuery(this).find('textarea[name="mess"]').attr('value');
	
	jQuery.ajax({
		type: "POST",
			url: "https://mandrillapp.com/api/1.0/messages/send.json",
	    data: {
		    'key': 'L5mLLGpOxBd6bMgzbsnh1A',
		    'message': {
				'from_email': email,
				'subject': 'Вопрос с сайта first-remont.ru',
				'text' : mess, 
				'from_name' : mob,
				'to':[{
				    'email': 'first-remont@mail.ru',
				    'name': 'Мастеру',
				    'type': 'to'
				}]
		    }
		},
	    success: function(data){
	    	jQuery('form#emailForm input[type="text"]').attr('value','');
	    	jQuery('form#emailForm textarea[name="mess"]').attr('value','');
	    	showMessege( 'Отправлено' );
	    },
	    error: function(data){
	    	showMessege( 'Проверьте адрес электронной почты и повторите попытку');
	    }
	});
}

function showMessege( text ){
	jQuery('body').append('<div id="orderStatusRequest" style="display: none"><div class="orderChangeSaveDivWrap"></div><div id="orderStatusRequestEcho"><h2>'+text+'</h2></div></div>');
	jQuery('#orderStatusRequest').fadeIn(1000).fadeOut(1000, function() { jQuery('#orderStatusRequest').remove()});
}