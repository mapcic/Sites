{source}
<section class="feedback">
    <header class="feedback__header">
        <h3>Обратная связь</h3>
    </header>
    <form class="feedback-form">
        <div class="feedback-form__group">
            <input type="text" placeholder="ФИО" name="name" tabindex="1">
        </div>
        <div class="feedback-form__group">
            <input type="text" placeholder="Введите ваш email" name="email">
        </div>
        <div class="feedback-form__group">
            <textarea name="" id="" rows="7" placeholder="Введите ваше сообщение" name="msg"></textarea>
        </div>
    </form>
    <button class="btn" type="submit">Отправить</button>
</section>

<script>
const $ = jQuery;

$(document).ready(function(){
    const name = $('.feedback input[name="name"]');
    const email = $('.feedback input[name="email"]');
    const msg = $('.feedback textarea[name="msg"]');

    const params = { name: name.val(),
         email: email.val(),
         msg: msg.val()
     };

     console.log( params );

    $.ajax({
        url: '/templates/mathperfect/php/email.php',
        data: { params },
        type: 'POST',
        dataType: 'json',
        success: function( data ){
            name.val('');
            email.val('');
            msg.val('');
        }
    })
});

</script>
{/source}
