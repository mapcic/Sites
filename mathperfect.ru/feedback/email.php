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

	$mailer->addCustomHeader( 'From', '<'.$_POST['params']['email'].'>' );
	$mailer->addCustomHeader( 'Sender', 'mathperfect@yandex.ru' );

	$mailer->setSubject( 'Вопрос по ремонту' );
	$mailer->addRecipient( 'mathperfect@yandex.ru' );
	$mailer->setBody( $_POST['params']['message'].' Номер телефона: '.$_POST['params']['phone'].$_POST['params']['email']);

	$sented = $mailer->Send();
}

echo $sented;
?>
