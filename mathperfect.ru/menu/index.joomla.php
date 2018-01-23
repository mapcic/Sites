{source}
<!-- Navigation -->
<section class="navbar">
	<header class="navbar-header">
		<span class="logo">
			<img src="/templates/mathperfect/img/logo.png" width="220px" alt="">
		</span>
	</header>

	 <nav class="nav hidden-lg-down">
		 <ul class="nav__list"><?php
			$db = JFactory::getDbo();
			$query = $db->getQuery( true );
			$menus = [];
			$active_class = 'nav__link_active';

			$uri = JFactory::getURI();
			$path = $uri->getPath();

			$menu_node = '<li class="nav__item"><a class="nav__link ${is_active}" href="${href}">${title}</a></li>';

			$query->select( $db->qn( array(
					'title',
					'alias',
					'path',
					'home' ) ) )
				->from( $db->qn( '#__menu' ) )
				->where( $db->qn( 'published' ) . ' = 1' )
				->where( $db->qn( 'level' ) . ' = 1' )
				->where( $db->qn( 'menutype' ) . ' = ' . $db->q( 'mainmenu' ) );

			$menus = $db->setQuery( $query )
				->loadObjectList();

			foreach ( $menus as $key => $menu ) {
				$is_active = '/'.$menu->path == $path || ( $menu->home == 1 && $path == '/' ) ?
				 	'nav__link_active' : '';
				$href = $menu->home == 1?
					'/' : $menu->path;

				echo preg_replace(
					array( '/\${href}/', '/\${title}/', '/\${is_active}/' ),
					array( $href, $menu->title, $is_active ),
					$menu_node );
			}
			?></ul>
	 </nav>
	 <a href="#" class="hidden-xl-up"><span class="fa fa-bars fa-2x"></span></a> <!-- mobile menu -->
</section>

<script>
( function( $ ){
	$( document ).ready( function(){
		$( 'section.navbar a.hidden-xl-up' ).on( 'click', switch_mob_menu );
	} );

	function switch_mob_menu(){
		$( 'section.navbar nav.nav' ).toggle( '.hidden-lg-down' );
	}
} )( jQuery )
</script>
<!-- end of navigation -->
{/source}
