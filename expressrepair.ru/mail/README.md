# Рассылка почты
## Описание
Для работы необходимо SMTP. Например Yandex SMTP.

## Установка
Вставить содержимое mail.html в документ. Загрузить на сервер mail.js, mail.php, PHPMailer.php. Попровить пути:

- В mail.html путь к mail.js
```html
...
<script onload="mail_init()" type="text/javascript" src="путь/до/mail.js" defer></script>
...
```
-В mail.js путь до mail.php
```javascript
...
jQuery.ajax({
	type: 'POST', url: 'путь/до/mail.php',
    data: {
...
```
-В mail.php поправить путь до PHPMailer.php
```php
...
require 'путь/до/PHPMailer.php';
...
```

Настроить SMTP в mail.php (пример для [Яндыкса](https://yandex.ru/support/mail-new/mail-clients.html)):
```php
...
$mail->Host = 'smtp.yandex.ru';
$mail->Username = 'my_email@yandex.ru';                 
$mail->Password = 'my_password';                           
$mail->SMTPSecure = 'ssl';                           
$mail->Port = 465;     
...
``` 

Прописать почту, для получения сообщений в mail.php
```php
...
$mail->addAddress('my_recepient_email@yandex.ru', 'Recepient Name');
...
```