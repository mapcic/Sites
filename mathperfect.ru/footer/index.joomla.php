{source}
<!-- footer -->
<?php

$menutype = 'mainmenu'; // menutype id is 2;
$parent_menu_id = 108;

function loadMenu( $parent_id, $menutype ) {
    $db = JFactory::getDbo();
    $query = $db->getQuery( true );

    $query->select( $db->qn( array(
            'title',
            'path' ) ) )
        ->from( $db->qn( '#__menu' ) )
        ->where( $db->qn( 'published' ) . ' = 1' )
        ->where( $db->qn( 'parent_id' ) . ' = '.$parent_id )
        ->where( $db->qn( 'menutype' ) . ' = ' . $db->q( $menutype ) );

    $submenus = $db->setQuery( $query )
        ->loadObjectList();

    $li_html = '<li class="list__item"><a href="${href}">${title}</a></li>';
    $out = array();
    foreach ( $submenus as $submenu ) {
        $out[] = preg_replace( array( '/\${href}/', '/\${title}/' ),
            array( $submenu->href, $submenu->title ),
            $li_html );
    }

    return implode( '', $out );
}
?>
<footer class="footer">
    <div class="footer-col">
        <h3 class="footer__header">Учебные материалы</h3>
        <ul class="footer__list">
            <?php echo loadMenu( 104, 'mainmenu' )?>
        </ul>
    </div>
    <div class="footer-col">
        <h3 class="footer__header">Достижения учеников</h3>
        <ul class="footer__list">
            <?php echo loadMenu( 106, 'mainmenu' )?>
        </ul>
    </div>
    <div class="footer-col">
        <h3 class="footer__header">Хобби</h3>
        <ul class="footer__list">
            <?php echo loadMenu( 108, 'mainmenu' )?>
        </ul>
    </div>
    <div class="footer-col">
        <h3 class="footer__header">Контакты</h3>
        <ul class="footer__list">
            <li class="list__item">
                <span class="icon"></span>
                Тел.: +7 (953) 264-56-98
            </li>
            <li class="list__item">
                <ul class="socials">
                    <li class="social__icon">
                        <a href="#"><span class="fa fa-instagram fa-2x"></span></a>
                    </li>
                    <li class="social__icon">
                        <a href="#"><span class="fa fa-twitter fa-2x"></span></a>
                    </li>
                    <li class="social__icon">
                        <a href="#"><span class="fa fa-facebook fa-2x"></span></a>
                    </li>
                    <li class="social__icon">
                        <a href="#"><span class="fa fa-vk fa-2x "></span></a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</footer> <!-- end of footer -->
{/source}
