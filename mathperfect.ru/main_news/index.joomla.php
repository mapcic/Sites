{source}
<section class="news">
<?php
$db = JFactory::getDbo();
$query = $db->getQuery( true );

$menutype = 'hidemenu'; // menutype id is 2;
$parent_menu_id = 109;

$num = 6;
$offset = 0;

$default_img = '/templates/mathperfect/img/janko-ferlic-174927-jpg.jpg';

$query = $db->getQuery( true )
    ->select( 'COUNT('.$db->qn(id).')')
    ->from( $db->qn( '#__menu' ) )
    ->where( $db->qn( 'published' ).' = 1', 'AND' )
    ->where( $db->qn( 'parent_id' ).' = '.$parent_menu_id );
$num_news = $db->setQuery( $query )
    ->loadResult();

$query = $db->getQuery( true )
    ->select( $db->qn( array(
        'a.introtext',
        'a.created',
        'b.title',
        'b.path' ) ) )
    ->from( $db->qn( '#__content', 'a' ) )
    ->innerJoin( $db->qn( '#__menu', 'b' ).' ON '.
        $db->qn( 'b.link' ).' LIKE CONCAT("%\?option=com_content&view=article&id=", '.$db->qn( 'a.id' ).')' )
    ->where( $db->qn( 'b.menutype' ).' = '.$db->q( $menutype ), 'AND' )
    ->where( $db->qn( 'b.parent_id' ).' = '.$parent_menu_id, 'AND' )
    ->where( $db->qn( 'b.published' ).' = 1' )
    ->order( $db->qn( 'a.created' ).' DESC' )
    ->setLimit($num, $offset);

    $news = $db->setQuery( $query )
    ->loadObjectList();

if( count( $news ) > 2 ) {
    $full_part = array_slice( $news, 0, 2 );
    $small_part = array_slice( $news, 2, count( $news ) );
} else {
    $full_part = $news;
    $small_part = array();
}

$full_html = '<article class="preview preview_big">'.
        '<div class="preview-content">'.
            '<div class="preview__text">'.
                '<span class="preview__date">${date}</span>'.
                '<h4 class="preview__header">'.
                    '<a href="${href}">${title}</a>'.
                '</h4>'.
                '<p>${intro}</p>'.
            '</div>'.
            '<span class="preview__img">'.
                '<img class="img" src="${src}">'.
            '</span>'.
        '</div>'.
    '</article>';

$small_html = '<article class="preview preview_small">'.
        '<span>'.
            '<img class="img" src="${src}">'.
        '</span>'.
        '<div class="preview__overlay">'.
            '<h4 class="preview_small-header">'.
                '<a href="${href}">${title}</a>'.
            '</h4>'.
        '</div>'.
    '</article>';

$matches = array();
?>
<!-- full previews -->
<?php
foreach( $full_part as $part ) {
    preg_match( '/<img[^\>]*src="([^\>"]*)"[^\>]*>/U', $part->introtext, $matches );
    $src = count($matches) > 1 ? $matches[1] : $default_img;

    $date = substr($part->created, 0, 10);

    // $max_words = 10;
    // preg_match_all( '/([A-zА-я]+[^A-zА-я]*)/', $part->title, $matches, PREG_PATTERN_ORDER );
    // $title = count( $matches[1] ) <= $max_words ? $part->title : implode( '', array_slice( $matches[1], 0, $max_words ) );

    $title = $part->title;
    $href = $part->path;

    $max_words = 20;
    $intro = preg_replace( '/<[^>]*>/U', '', $part->introtext );
    preg_match_all( '/([а-яА-ЯЁёa-zA-Z0-9]+[^а-яА-ЯЁёa-zA-Z0-9]*)/u', $intro, $matches, PREG_PATTERN_ORDER );

    $intro = count( $matches[1] ) <= $max_words ? $intro : implode( '', array_slice( $matches[1], 0, $max_words ) ).'...';

    echo preg_replace( array( '/\${date}/', '/\${href}/', '/\${title}/', '/\${src}/', '/\${intro}/' ),
        array( $date, $href, $title, $src, $intro ),
        $full_html );
}
?>
<!-- small previews -->
<div class="small">
<?php
foreach( $small_part as $part ) {
    preg_match( '/<img[^\>]*src="([^\>"]*)"[^\>]*>/U', $part->introtext, $matches );
    $src = count($matches) > 1 ? $matches[1] : $default_img;

    // $max_words = 10;
    // preg_match_all( '/([A-zА-я]+[^A-zА-я]*)/', $part->title, $matches, PREG_PATTERN_ORDER );
    // $title = count( $matches[1] ) <= $max_words ? $part->title : implode( '', array_slice( $matches[1], 0, $max_words ) );

    $title = $part->title;
    $href = $part->path;

    echo preg_replace( array( '/\${href}/', '/\${title}/', '/\${src}/' ),
        array( $href, $title, $src ),
        $small_html);
}
?>
</div> <!-- end of small previews -->

<div class="pagination">
    <a class="pagination__link previous_news">
        <i class="fa fa-angle-left"></i>
        Предыдущая
    </a>
    <a class="pagination__link next_news">
        Следующая
        <i class="fa fa-angle-right"></i>
    </a>
</div>

<div class="info" class="hidden" count="<?php echo $num;?>" number="<?php echo $num_news;?>" offset="<?php echo count( $news );?>"></div>
</section> <!-- end of news -->
<script>
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
        var cur_number = that.find( 'article' ).length;

        offset = is_next? offset : offset - count - cur_number;

        $.ajax({
            url: '/templates/mathperfect/main_news.php',
            type: 'POST',
            dataType: 'json',
            data: {
                count,
                offset
            },
            success: function( data ) {
                that.find( 'article' ).remove();

                $.each( data, function( ind, val ){
                    if( ind < 1 )
                        $(val).insertBefore( that.find( '.small' ) );
                    if( ind >= 1)
                        $(val).appendTo( that.find( '.small' ) );
                } );

                info.attr( 'offset', offset + data.length );
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
</script>
{/source}
