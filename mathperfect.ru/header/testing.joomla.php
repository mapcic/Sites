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

$breadCrumbs = [];
$paths = [];
$parts = array_slice(explode( '/', $path ), 1);
$breadCrumbsHtml = '';

if( $path != '/' && count($parts) > 1) {

    for ($i=0; $i < count( $parts ) - 1; $i++) {
        $paths[] = $i == 0? $parts[0] : $paths[ $i - 1 ].'/'.$parts[ $i ];
    }

    $query = $db->getQuery(true)
        ->select( $db->qn( array( 'title', 'path' ) ) )
        ->from( $db->qn('#__menu') )
        ->where( $db->qn( 'path' ).' IN ('. implode( ', ', $db->q($paths) ) .')')
        ->order( $db->qn( 'path' ));

    $crumbs = $db->setQuery( $query )
        ->loadObjectList();

    for ($i=0; $i < count($crumbs); $i++) {
        $breadCrumbs[] = '<div class="breadCrumbs__item"><a href="/'.$crumbs[$i]->path.'">'.$crumbs[$i]->title.'</a></div>';
    }

    $breadCrumbsHtml = implode('<div class="breadCrumbs__item_separator">/</div>', $breadCrumbs);
}

?>

    <div class="title">
    	<span class="icon">
    		<img src="/templates/<?php echo($doc->template.DS.'icons'.DS.$root_path);?>.png" alt="">
    	</span>
        <div class="breadCrumbs"><?php echo $breadCrumbsHtml; ?></div>
    	<h3><?php echo $title; ?></h3>
    </div>
{/source}
