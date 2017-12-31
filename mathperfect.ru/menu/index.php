<?php
$db = JFactory::getDbo();
$query = $db->getQuery( true );
$menus = [];

$menu_node = '<li class="nav__item"><a class="nav__link" href="${href}">${title}</a></li>';

$query->select( $db->qn( array(
		'title',
		'alias',
		'path',
		'home' ) ) )
	->from( $db->qn( '#__menu' ) )
	->where( $db->qn( 'published' ) . ' = 1' )
	->where( $db->qn( 'mainmenu' ) . ' = ' . $db->q( 'mainmenu' ) );

$menus = $db->setQuery( $query )
	->loadObjectList();

foreach ( $menus as $key => $menu ) {
	echo preg_replace( array( '${href}', '${title}' ), 
		array( $menu->path, $menu->title ), 
		$menu_node );
}
?>