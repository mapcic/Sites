{source}
<section class="hobby-news">
 <header>
     <h3>Новости хобби</h3>
</header>
<?php
$db = JFactory::getDbo();
$query = $db->getQuery( true );

$menutype = 'mainmenu'; // menutype id is 2;
$parent_menu_id = 108;

$num = 3;
$offset = 0;

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
    ->where( $db->qn( 'b.published' ).' = 1' )
    ->where( $db->qn( 'b.level' ) . ' > 2' )
    ->order( $db->qn( 'a.created' ).' DESC' )
    ->setLimit($num, $offset);

$news = $db->setQuery( $query )
    ->loadObjectList();

$href_hobby_root = (string) $db->setQuery($subQuery)
    ->loadResult();

$news_html = '<article class="hobby-preview">'.
        '<span class="preview__date">${date}</span>'.
        '<span class="preview__icon"></span>'.
        '<h4 class="preview__header">'.
            '<a href="${href}">${title}</a>'.
        '</h4>'.
        '<p class="preview__text">'.
            '${intro}'.
        '</p>'.
    '</article>';

foreach( $news as $part ) {
    $date = substr($part->created, 0, 10);

    // $max_words = 10;
    // preg_match_all( '/([A-zА-я]+[^A-zА-я]*)/', $part->title, $matches, PREG_PATTERN_ORDER );
    // $title = count( $matches[1] ) <= $max_words ? $part->title : implode( '', array_slice( $matches[1], 0, $max_words ) );

    $title = $part->title;

    $href = $part->path;

    $max_words = 10;
    $intro = preg_replace( '/<[^>]*>/U', '', $part->introtext );
    preg_match_all( '/([а-яА-ЯЁёa-zA-Z0-9]+[^а-яА-ЯЁёa-zA-Z0-9]*)/u', $intro, $matches, PREG_PATTERN_ORDER );
    $intro = count( $matches[1] ) <= $max_words ? $intro : implode( '', array_slice( $matches[1], 0, $max_words ) ).'...';

    echo preg_replace( array( '/\${date}/', '/\${href}/', '/\${title}/', '/\${intro}/' ),
        array( $date, $href, $title, $intro ),
        $news_html );
}

echo preg_replace('/\${href_hobby_root}/', $href_hobby_root, '<div class="look_more_hobby_news"><a href="${href_hobby_root}">Смотреть больше новостей...</a></div>')
?>
</section>
{/source}
