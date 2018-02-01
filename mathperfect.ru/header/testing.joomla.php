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

$breadCrumbs = '';
$paths = [];

if( $path != '/' ) {
    $parts = explode( '/', $path );
    
    for ($i=0; $i < count( $parts ); $i++) { 
        $paths[] = $i == 0? $parts[0].'/' : $paths[ $i - 1 ].$parts[ $i ].'/';
    }
  
    $query = $db->getQuery(true)
        ->select( $db->qn( array( 'title', 'path' ) ) )
        ->from( $db->qn('#__menu') )
        ->where( $db->qn( 'path' ).' IN ('. implode( ', ', $paths ) .')')
        ->order( $db->qn( 'path' ))

    $crumbs = $db->setQuery( $query )
        ->loadObjectList();

    for ($i=0; $i < count($crumbs); $i++) { 
        $breadCrumbs = $breadCrumbs.'\/<div class="breadCrumbs"><a href="\/'.$crumbs[i]->path.'">'.$crumbs[i]->title.'</div>'
    }
}

?>

    <div class="title">
    	<span class="icon">
    		<img src="/templates/<?php echo($doc->template.DS.'icons'.DS.$root_path);?>.png" alt="">
    	</span>
        <div id="breadcrumbs"><?php echo $breadCrumbs; ?></div>
    	<h3><?php echo $title; ?></h3>
    </div>
{/source}
