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
( function($){
    $( document ).ready( function(){
        var name = $('.feedback input[name="name"]');
        var email = $('.feedback input[name="email"]');
        var msg = $('.feedback textarea');

        $('section.feedback .btn').on( 'click', function( e ){
            e.preventDefault();

            var params = {
                name: name.val(),
                email: email.val(),
                msg: msg.val()
            };

            console.log(params);

            $.ajax({
                url: '/templates/mathperfect/email.php',
                data: { params }, type: 'POST', dataType: 'json',
                success: function( data ){
                    console.log(data);
                    name.val('');
                    email.val('');
                    msg.val('');
                }
            })
        } );
    } );
} )( jQuery );
</script>
{/source}
