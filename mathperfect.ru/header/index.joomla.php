{source}
<?php
$uri = JFactory::getURI();
$db = JFactory::getDbo();
$query = $db->getQuery( true );
$path = $uri->getPath();

define('DS', DIRECTORY_SEPARATOR);

$conditions = $path == '/'? $db->qn( 'home' ).' = 1' :
	$db->qn( 'path' ).' = '.$db->q( substr( $path, 1 ) );

$query->select( $db->qn('title') )
	->from( $db->qn('#__menu') )
	->where( $conditions );

$title = $db->setQuery( $query )
	->loadResult();

$root_path_arr = [];
preg_match( '/^\/([^\/]*)/', $path, $root_path_arr );

$root_path = $path == '/'? 'glavnaya' : $root_path_arr[1];
?>
<header class="main-header">
	<div class="title">
		<span class="icon">
			<img src="/templates/<?php echo($doc->template.DS.'icons'.DS.$root_path);?>.png" alt="">
		</span>
		<h3><?php echo $title; ?></h3>
	</div>
</header>
{/source}
