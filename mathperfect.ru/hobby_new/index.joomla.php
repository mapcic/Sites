{source}
<section class="news">
<?php
$db = JFactory::getDbo();
$query = $db->getQuery( true );

$menutype = 'mainmenu'; // menutype id is 2;
// $parent_menu_id = 117;

$num = 2;
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

$html = '<article class="preview preview_big">'.
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

$matches = array();
?>
<!-- full previews -->
<?php
foreach( $news as $part ) {
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
        $html );
}
?>

<div class="pagination">
    <a class="pagination__link more_news">
        Показать еще
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
        var is_more = $( this ).hasClass( 'more_news' );
        var cur_number = that.find( 'article' ).length;
        var parent_id = <?php echo $parent_menu_id;?>;

        offset = is_more? offset : offset - count - cur_number;

        $.ajax({
            url: '/templates/mathperfect/khobby_news.php',
            type: 'POST',
            dataType: 'json',
            data: {
                count,
                offset,
                parent_id
            },
            success: function( data ) {
                // that.find( 'article' ).remove();
                $.each( data, function( ind, val ){
                    $( that.find('article').last() ).after( $(val) );
                } );

                info.attr( 'offset', offset + data.length );
                turn_flipping();
            }
        });
    }

    function turn_flipping() {
        nav.off( 'click', flipping );
        nav.on( 'click', flipping );
        if( +info.attr( 'offset' ) == +info.attr( 'number' ) ){
            console.log(1);
            nav.filter( '.more_news' ).off( 'click', flipping ).hide();
        }
    }
})(jQuery);
</script>
{/source}
