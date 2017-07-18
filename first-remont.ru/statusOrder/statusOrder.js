(function($){
    function order_getStatus( event ){
        event.preventDefault();
        var respObj = $('div#searchFormDivResponse'),
            values = {};

        $('#searchFormForm').find( 'input' ).each(function(ind, val){
            val = $(val);
            values[ val.attr('name') ] = val.val();
        });

       
        if( respObj.children().length == 0 ){
            $('<div id="blackF"><div id="resBaz"><a style="float:right;" class="question"> Вернуться </a><p class="zag">Результат: <a class="yakor one" style="margin:0 0 0 10px !important;" target="_blank" href="/question/">уточнить</a></p><div class="blad"><table id="searchFormDivTable" class="frOff" border="0" class="itop"><thead><tr class="zags"><td>Номер квитанции:</td><td>Устройство:</td><td>Ремонт:</td><td>Статус:</td></tr></thead><tbody><tr><td name="custom_id"></td><td name="title"></td><td name="description"></td><td name="status"></td></tr></tbody></table><div class="emptyResponse frOff"><p>К сожалению, такого номера квитанции не существует!</p><p>Номер квитанции находиться на самой квитанции!)</p></div></div></div></div>')
                .appendTo(respObj).on('click', function(event){
                    event.preventDefault();
                    respObj.addClass('frOff');
                });
        }     

        $.ajax({
            type: 'POST', cache: false, dataType: 'json', url: urlPhp, 
            data: { params : JSON.stringify( values ) },
            success: function( data ){
                if( data ){
                    var table = $('table#searchFormDivTable');
                    table.removeClass('frOff');
                    $('div#emptyResponse').addClass('frOff');

                    $.each(['custom_id', 'title', 'description', 'status'], function( ind, val ){
                        table.find('[name='+val+']').html(data[val]);
                    });
                }else{
                    $('table#searchFormDivTable').addClass('frOff');
                    $('div#emptyResponse').removeClass('frOff');
                }
                respObj.removeClass('frOff');
            }
        });
    }

    $(document).ready(function(){
        $('#searchFormForm input[type=submit]').on('click', order_getStatus);
    });
})(jQuery);