function mail_init() {
	jQuery('.mail .send').on('click', send_mail);
}

function send_mail() { 
	var $this = jQuery(this),
		form = $this.parents('.mail'),
		name = form.find('.name'),
		phone = form.find('.phone'),
		email = form.find('.email'),
		msg = form.find('.msg');

	if ( !is_email(email.val()) || !is_msg(msg.val()) ) {
		return 0;
	}

	jQuery.ajax({
		type: 'POST', url: 'mail.php',
	    data: {
	    	'name' : name.val(),
	    	'email' : email.val(), 
	    	'phone' : phone.val(),
	    	'msg' : msg.val()
		},
		success: function(data) {
			jQuery.each([name, email, phone, msg], function(ind, val) {
				val.val('');
			});
		}
	});
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