<?php 
// require 'PHPMailerAutoload.php';
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer.php';

$mail = new PHPMailer;                            

$mail->SMTPAuth = true;                          
$mail->Host = 'smtp.yandex.ru';
$mail->Username = '';                 
$mail->Password = '';                           
$mail->SMTPSecure = 'ssl';                           
$mail->Port = 465;                                   

$mail->From = $_POST['email'];
$mail->FromName = $_POST['name'].' '.$_POST['phone'];

$mail->addAddress('', 'Recepient Name');

$mail->isHTML(true);

$mail->Subject = 'Вопрос мастеру.';
$mail->Body = $_POST['msg'];

if(!$mail->send()) {
    echo 0;
} else {
    echo 1;
}
?>