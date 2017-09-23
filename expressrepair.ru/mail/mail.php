<?php 
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();            
$mail->SMTPDebug = 2;                               

$mail->SMTPAuth = true;                          
$mail->Host = "smtp.yandex.ru";
$mail->Username = "prmaximus@yandex.ru";                 
$mail->Password = "";                           
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";                           
//Set TCP port to connect to 
$mail->Port = 587;                                   

$mail->From = "name@gmail.com";
$mail->FromName = "Full Name";

$mail->addAddress("name@example.com", "Recepient Name");

$mail->isHTML(true);

$mail->Subject = "Subject Text";
$mail->Body = "<i>Mail body in HTML</i>";
$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    echo "Message has been sent successfully";
}
?>