(function( $ ){
    var that = $( 'section.news' );
    var nav = that.find( '.pagination__link' );
    var info = that.find( '.info' );

    $( document ).ready( function(){
        turn_flipping();
    } );

    function flipping( event ) {
        event.preventDefault();

        var count = +info.attr( 'count' );
        var number = +info.attr( 'number' );
        var offset = +info.attr( 'offset' );
        var is_next = $( this ).hasClass( 'next_news' );

        $.ajax({
            url: '/templates/mathperfect/php/main_news.php',
            type: 'POST',
            dataType: 'json',
            data: {
                count,
                number,
                offset,
                is_next
            },
            success: function( data ) {
                that.find( 'article' ).remove();

                $.each( data, function( ind, val ){
                    $(val).appendTo( ind > 1? that.find( '.small' ) : that );
                } );

                info.attr( 'offset', is_next? offset + data.length : offset - data.length );
                turn_flipping();
            }
        });
    }

    function turn_flipping() {
        nav.on( 'click', flipping );
        if( +info.attr( 'offset' ) <= +info.attr( 'count' ) )
            nav.filter( '.previous_news' ).off( 'click', flipping );
        if( +info.attr( 'offset' ) == +info.attr( 'number' ) )
            nav.filter( '.next_news' ).off( 'click', flipping );
    }
})(jQuery);
