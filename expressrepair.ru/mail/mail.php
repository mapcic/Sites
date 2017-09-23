<?php 
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();            
$mail->SMTPDebug = 2;                               

$mail->SMTPAuth = true;                          
$mail->Host = 'smtp.yandex.ru';
$mail->Username = 'prmaximus@yandex.ru';                 
$mail->Password = '';                           
$mail->SMTPSecure = 'ssl';                           
$mail->Port = 465;                                   

$mail->From = $_POST['email'];
// $mail->FromName = 'Full Name';

$mail->addAddress('prmaximus@yandex.ru', 'Recepient Name');

$mail->isHTML(true);

$mail->Subject = 'Вопрос мастеру.';
$mail->Body = 'Имя:'.$_POST['name'].'Телефон:'.$_POST['phone'].'<br>'.$_POST['msg'];
// $mail->AltBody = 'This is the plain text version of the email content';

if(!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent successfully';
}
?>