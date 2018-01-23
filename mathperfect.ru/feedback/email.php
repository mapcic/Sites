<?php
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'JPATH_BASE', preg_replace('/(?:\/[\w\-]+){2}$/', '', dirname(__FILE__)) );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

JFactory::getApplication('site')->initialise();

$sented = 0;

if (isset( $_POST['params'] ) && !empty( $_POST['params']['msg'] ) && !empty( $_POST['params']['name'] ) && JMailHelper::isEmailAddress( $_POST['params']['email'] )) {
	$mailer = JFactory::getMailer();
	$config = JFactory::getConfig();

	$mailer->addRecipient($config->get('mailfrom', ''));

	$mailer->setSender(array( $config->get('mailfrom', ''), $config->get('sitename', '') ));
	$mailer->setSubject('ОБРАТНАЯ СВЯЗЬ. '.$_POST['params']['name']);

	$mailer->addReplyTo($_POST['params']['email'], $_POST['params']['name']);

	$mailer->isHTML(true);
	$mailer->setBody( $_POST['params']['msg'] );

	$mailer->addCustomHeader('From', '<'.$_POST['params']['email'].'>');

	$sented = $mailer->Send();
}
?>
