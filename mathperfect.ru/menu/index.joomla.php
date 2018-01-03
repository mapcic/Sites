{source}
<!-- Navigation -->
<section class="navbar">
	<header class="navbar-header">
		<span class="logo">
			<img src="img/128.png" alt="">
		</span>
		<p class="navbar__title">MATHPERFECT</p>
	</header>

	 <nav class="nav">
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
				->where( $db->qn( 'menutype' ) . ' = ' . $db->q( 'mainmenu' ) );

			$menus = $db->setQuery( $query )
				->loadObjectList();

			foreach ( $menus as $key => $menu ) {
				$is_active = $menu->path == $path || $menu->home == 1 ?
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
	 <a href="#"><span class="fa fa-bars fa-2x"></span></a> <!-- mobile menu -->
</section>

<script>
(function($) {
	var href = <?php echo $path;?>;
	$( document ).ready( function(){
		$('.nav_link[]').
	} );
})(jQuery)
</script>
<!-- end of navigation -->
{/source}
