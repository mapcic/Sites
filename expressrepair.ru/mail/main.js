function mail_init() {
	jQuery('.mail .send').on('click', send_email);
}

function send_mail { 
	var $this = jQuery(this),
		form = $this.find('.mail'),
		name = form.find('.name'),
		phone = form.find('.phone'),
		email = form.find('.email'),
		msg = form.find('.msg');

	// if ( !is_email(email.val()) || !is_msg(msg.val)) {
	// 	return 0;
	// }

	Email.send(
		"prmaximus@yandex.ru",
		email.val(),
		"Вопрос мастеру.",
		'Имя:'+name.val()+'Телефон:'+phone.val()+'<br>'+msg.val(),
		{token: "63cb3a19-2684-44fa-b76f-debf422d8b00"}
	);
}

function clear_string( str ) {
    return str.replace(/^\s+|\s+$/g,''); 
}

function is_email( email ) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function is_msg( msg ) {
	if(clear_string(msg) == '') {
		return false;
	}
	return true;
}