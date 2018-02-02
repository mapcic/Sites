{source}
<div class="achievements-of-pupils">
    <div class="slides" style="display: none;"><?php
        $db = JFactory::getDbo();
        $query = $db->getQuery( true );
        $default_img = '/templates/mathperfect/img/janko-ferlic-174927-jpg.jpg';

        $menutype = 'mainmenu'; // menutype id is 2;
        $parent_menu_id = 106;

        $subQuery = $db->getQuery( true )
            ->select( $db->qn('path') )
            ->from( $db->qn('#__menu') )
            ->where( $db->qn('id').' = '.$db->escape( $parent_menu_id, true ) );
        $query = $db->getQuery( true )
            ->select( $db->qn( array(
                'a.introtext',
                'a.created',
                'b.title',
                'b.path' ) ) )
            ->from( $db->qn( '#__content', 'a' ) )
            ->innerJoin( $db->qn( '#__menu', 'b' ).' ON '.
                $db->qn( 'b.link' ).' LIKE CONCAT("%\?option=com_content&view=article&id=", '.$db->qn( 'a.id' ).')' )
            ->where( $db->qn( 'b.path' ).' LIKE CONCAT(('.$subQuery.'), "'. ( $parent_menu_id == 1 ? '' : '/') .'%")', 'AND')
            ->where( $db->qn( 'b.menutype' ).' = '.$db->q( $menutype ), 'AND' )
            ->where( $db->qn( 'b.level' ).' > 1 ' )
            ->where( $db->qn( 'b.published' ).' = 1' );

        $cvs = $db->setQuery( $query )
            ->loadObjectList();

        $cv_html = '<div title="${title}">${fulltext}</div>';

        foreach( $cvs as $cv ) {
            $title = $cv->title;
            $fulltext = $cv->fulltext;

            echo preg_replace( array( '/\${title}/', '/\${fulltext}/' ),
                array( $title, $fulltext ),
                $cv_html );
        }
    ?></div>
    <div class="slider">
        <a href="#">
            <i class="control control__left fa fa-angle-left fa-3x"></i>
        </a>
        <div class="slider__inner">
            <div class="slider__items">
                <div class="slide slide_active">
                    <div class="slide__item">
                        <div class="slide__text">
                            <h4 class="slide_title"></h4>
                            <div class="slide_fulltext"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#"><i class="control control__right fa fa-angle-right fa-3x"></i></a>
    </div>
</div>

<script>
( function( $ ) {
    $(document).ready( function() {
        const $slider_wrap = $( '.cv' );
        const slides = $slider_wrap.find( '.slides div' ).map( ( ind, val ) => {
            const $val = $( val );
            const data = {
                'title': $val.attr( 'title' ),
                'fulltext': $val.html()
            }

            return data;
        });

        const slen = slides.length;
        const $slides = $slider_wrap.find( '.slide__item' );

        $slides.each( ( ind, val ) => {
            const $val = $( val );

            $val.find( '.slide_title' ).html( slides[ind].title );
            $val.find( '.slide_fulltext' ).html( slides[ind].fulltext );

            $val.attr( 'ind', ind );
        });

        const $btns = $slider_wrap.find( '.control' ).on( 'click', function( event ) {
            event.preventDefault();
            const $this = $( this );

            $slides.each( ( ind, val ) => {
                const $val = $( val );
                const direct = $this.hasClass( 'control__right' )? 1 : -1;

                let next_ind = +$val.attr( 'ind' ) + direct;

                if ( next_ind < 0 ) next_ind = slen - 1;
                if ( next_ind > slen - 1 ) next_ind = 0;

                $val.find( '.slide_title' ).html( slides[next_ind].title );
                $val.find( '.slide_fulltext' ).html( slides[next_ind].intro );

                $val.attr( 'ind', next_ind );
            } );
        } );
    } );
} )( jQuery )
</script>
{/source}
