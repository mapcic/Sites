{source}
<?php
    $db = JFactory::getDbo();
    $query = $db->getQuery( true );

    $menutype = 'hidemenu'; // menutype id is 2;
    $parent_menu_id = 181;

    $subQuery = $db->getQuery( true )
        ->select( $db->qn('path') )
        ->from( $db->qn('#__menu') )
        ->where( $db->qn('id').' = '.$db->escape( $parent_menu_id, true ) );
    $query = $db->getQuery( true )
        ->select( $db->qn( array(
            'a.fulltext',
            'a.title' ) ) )
        ->from( $db->qn( '#__content', 'a' ) )
        ->innerJoin( $db->qn( '#__menu', 'b' ).' ON '.
            $db->qn( 'b.link' ).' LIKE CONCAT("%\?option=com_content&view=article&id=", '.$db->qn( 'a.id' ).')' )
        ->where( $db->qn( 'b.path' ).' LIKE CONCAT(('.$subQuery.'), "'. ( $parent_menu_id == 1 ? '' : '/') .'%")', 'AND')
        ->where( $db->qn( 'b.menutype' ).' = '.$db->q( $menutype ) );

    $achivements= $db->setQuery( $query )
        ->loadObjectList();

    $out = array();
    foreach( $achivements as $a ) {
        $title = $a->title;

        preg_match( '/<img[^\>]*src="([^\>"]*)"[^\>]*>/U', $a->fulltext, $matches );
        $img = count($matches) > 1 ? '/'.$matches[1] : '';

        $text = preg_replace( '/<[^>]*>/U', '', $a->fulltext );
        $text = preg_replace( '/\{[^\}]*\}/U', '', $text );
        $text = preg_replace( '/\"/U', '\\\"', $text );

        $out[] = (object)[
            'img' => $img,
            'title' => $title,
            'text' => $text
        ];
    }

    echo(json_encode($out));
?>
{/source}
