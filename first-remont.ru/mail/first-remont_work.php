<style type="text/css">
#orderStatusRequest, #orderChangeSaveDivWrap{
	position: fixed;
	top: 0;
	left: 0;
	z-index: 1000;
	overflow: hidden;
	width: 100%;
	height: 100%;
	display: flex;
	align-items: center;
	justify-content: center; 
}
div.boxi {
	background: #fff;
	padding: 20px;
	box-shadow: rgba(94, 94, 94, 0.71) 0 0 15px 1px;
	z-index: 9999;
}
</style>
<div style="clear:left;"></div>
<h1>Задайте вопрос:</h1>
<p>Мы постараемся как можно быстрее ответить на ваш вопрос, а вы, пожалуйста, постарайтесь задать его, максимально развернуто. Спасибо...</p>

<div id="question">
	<div id="email">Ваш e-mail: <span style="color: #c0c0c0;" required><em> * </em></span><br />
		<input class="qwz" name="email" type="text"></input>
	</div>
	<div id="phoner">Ваш телефон: <span style="color: #c0c0c0;"><em> </em></span><br /> 
		<input class="qwz" name="phoner" type="text" value="+7 (___) ___-__-__" placeholder="+7 (___) ___-__-__"></input>
	</div>
	<div id="message">Ваш вопрос:<span style="color: #c0c0c0;"><em> * </em></span><br />
		<textarea class="qwztext" cols="0" name="mess" required></textarea>
	</div>
	<div id="button">
		<input id="nextbutton" class="submit" name="button" type="submit" value="Отправить" />
	</div>
</div>

<script type="text/javascript" src="/templates/first-remont/js/jquery.maskedinput.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
	function showMessege( text ){
		$('body').append('<div id="orderStatusRequest" style="display: none"><div class="boxi"><h2>'+text+'</h2></div></div>');
		$('#orderStatusRequest').fadeIn(500).fadeOut(3000, function() { $('#orderStatusRequest').remove()});
	}
	$("#phoner input").mask("+7 (999) 999-99-99");
	$('#button').click(function(event) {
		event.preventDefault();

		var data = {
				'email' : $('#email input').val(),
				'phone': $('#phoner input').val(),
				'message': $('#message textarea').val()
			},
			resp = 0;

		$.ajax({
			url: '/templates/first-remont/php/mail.php',
			type: 'POST',
			dataType: 'json',
			data: { params : data },
		})
		.done(function(resp) {
			if( resp ){
				$('#question input, #question textarea').not('[name=button]').val('');
				showMessege( 'Ваш вопрос отправлен. Мы обязательно ответим.' );
			}else{
				showMessege( 'Проверьте адрес электронной почты, поле вопроса и повторите попытку.' );
			}
		})
		.fail(function(){
			showMessege( 'Что-то пошло не так, повторите попытку чуть позже.' );
		});
	});
});
</script>

<!-- Server part -->
<?php
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );    
define( 'JPATH_BASE', preg_replace('/(?:\/[\w\-]+){3}$/', '', dirname(__FILE__)) );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$sented = 0;

JFactory::getApplication('site')->initialise();
if (isset( $_POST['params'] ) && !empty( $_POST['params']['message'] ) && JMailHelper::isEmailAddress( $_POST['params']['email'] )) {
	$mailer = JFactory::getMailer();
	//$mailer->isHTML(false);

	$mailer->addCustomHeader('From', '<'.$_POST['params']['email'].'>');
	$mailer->addCustomHeader('Sender', 'first-remont@yandex.ru');

  	//$mailer->setSender();
	$mailer->setSubject('Вопрос по ремонту');
	$mailer->addRecipient('first-remont@yandex.ru');
	$mailer->setBody($_POST['params']['message'].'Номер телефона:'.$_POST['params']['phone'].$_POST['params']['email']);

	// $mailer = JFactory::getMailer();
	// $mailer->isHTML(false);

	// $mailer->setSender('prmaximus@yandex.ru');
	// $mailer->addCustomHeader('Sender', 'prmaximus@yandex.ru');
	// $mailer->addReplyTo($_POST['params']['email'], 'Mike');
	// $mailer->setFrom($_POST['params']['email'], 'Mike', false);

	// $mailer->setSubject('Question');
	// $mailer->addRecipient('prmaximus@inbox.ru');
	// $mailer->setBody($_POST['params']['message'].' '.$_POST['params']['email']);

	// $sented = $mailer->Send();

	$sented = $mailer->Send();
}

echo $sented;
?>