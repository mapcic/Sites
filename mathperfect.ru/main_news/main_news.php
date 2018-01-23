<?php
define('_JEXEC', 1); define('DS', DIRECTORY_SEPARATOR);
define('JPATH_BASE', preg_replace('/(?:\/[\w\-]+){2}$/', '', dirname(__FILE__)));

require_once (JPATH_BASE .DS.'includes'.DS.'defines.php');
require_once (JPATH_BASE .DS.'includes'.DS.'framework.php');

$count = $_POST[ 'count' ];
$offset = $_POST[ 'offset' ];

$db = JFactory::getDbo();
$query = $db->getQuery( true );

$menutype = 'hidemenu'; // menutype id is 2;
$parent_menu_id = 109;

$default_img = '/templates/mathperfect/img/janko-ferlic-174927-jpg.jpg';

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
    ->setLimit( $count, $offset );

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
$out = array();

foreach( $full_part as $part ) {
    preg_match( '/<img[^\>]*src="([^\>"]*)"[^\>]*>/U', $part->introtext, $matches );
    $src = count($matches) > 1 ? $matches[1] : $default_img;

    $date = substr($part->created, 0, 10);
    $title = $part->title;
    $href = $part->path;
    $intro = preg_replace( '/<img[^>]*>/U', '', $part->introtext );

    $out[] = preg_replace( array( '/\${date}/', '/\${href}/', '/\${title}/', '/\${src}/', '/\${intro}/' ),
        array( $date, $href, $title, $src, $intro ),
        $full_html );
}

foreach( $small_part as $part ) {
    preg_match( '/<img[^\>]*src="[^\>"]">/U', $part->introtext, $matches );
    $src = $matches[1];
    $src = count($matches) > 1 ? $matches[1] : $default_img;

    $title = strtoupper( $part->title );
    $href = $part->path;

    $out[] = preg_replace( array( '/\${href}/', '/\${title}/', '/\${src}/' ),
        array( $href, $title, $src ),
        $small_html);
}

echo json_encode($out);
?>
