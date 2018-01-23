<?php

header('Content-Type: application/json; charset=UTF-8');
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$array = explode ( DS , dirname(__FILE__));
$array = array_slice ($array , 0, count($array)-1);
$string = implode ( DS , $array );
define( 'JPATH_BASE', $string);

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
$app = JFactory::getApplication('site')->initialise();

function getStatus( ){
	$params = json_decode( $_POST[ 'params' ] );
	$db = JFactory::getDbo( );
	$query = $db->getQuery( true );
		$query
			->select( '*' )
			->from( $db->quoteName( '#__custom_tasks' ) )
			->where( $db->quoteName('custom_id').' = '.$db->quote((int)$params->custom_id))
			->order( 'id DESC' );
	$result = $db->setQuery( $query )
		->loadAssoc( );
	$result = json_encode( $result );
	echo $result;
}
// Получение всех записей
function getList( ){
	$db = JFactory::getDbo( );
	$query = $db->getQuery( true );
		$query
			->select( '*' )
			->from( $db->quoteName( '#__custom_tasks' ) )
			->order( 'id DESC' );
	$result = $db
		->setQuery( $query )
		->loadAssocList( );
	$result = json_encode( $result );
	echo $result;
}
// Добавление записи
function add(){
	$db 	= JFactory::getDbo( );
	$query 	= $db->getQuery( true );
	$fields	= json_decode( $_POST[ 'params' ] );

	$query ->select('*')
		->from($db->quoteName('#__custom_tasks'))
		->where($db->quoteName('custom_id').' = '.(int)$fields->custom_id);
	$result = $db ->setQuery( $query )
		->loadAssoc( );

	if(!empty($result)){
		echo( json_encode( '<h1>Уже сущетсвует!</h1>' ) );
		exit;
	}

	$columns = array(
	    'custom_id',
	    'title',
	    'description',
	    'status'
	);
	$values = array(
	    $db->quote( $fields->custom_id ),
	    $db->quote( $fields->title ),
	    $db->quote( $fields->description ),
	    $db->quote( $fields->status )
	);

	$query  ->insert( $db->quoteName( '#__custom_tasks' ) )
	    	->columns( $db->quoteName( $columns ) )
	    	->values( implode( ',', $values ) );

	$db 	->setQuery( $query )
	    	->execute( );

    echo( json_encode( '<h1>Добавлено!</h1>' ) );
}

function hide( ){
	$db 	= JFactory::getDbo( );
	$query 	= $db->getQuery( true );
	$fields = json_decode( $_POST[ 'params' ] );

	$conditions = $db->quoteName('id').'='.$fields->id;

	$query 	->delete($db->quoteName('#__custom_tasks'))
    		->where($conditions);

    $db 	->setQuery( $query )
    		->execute();

    echo( json_encode( '<h1>Удалено!</h1>' ) ) ;
}

function save( ){
	$db = JFactory::getDbo( );
	$query = $db->getQuery( true );
	$fields = json_decode( $_POST[ 'params' ] );

	$fieldsQuery = array(
		$db->quoteName( 'title' ).'='.$db->quote( $fields->title ),
		$db->quoteName( 'description' ).'='.$db->quote( $fields->description ),
		$db->quoteName( 'status' ).'='.$db->quote( $fields->status ),
		$db->quoteName( 'last_change_date' ).'="'.date( 'Y-m-d H:i:s' ).'"'
	);
	$conditions = $db->quoteName( 'id' ).'='.$db->quote( $fields->id );

	$query 	->update( $db->quoteName( '#__custom_tasks' ) )
    		->set( $fieldsQuery )
    		->where( $conditions );

    $db 	->setQuery( $query )
    		->execute( );

    echo( json_encode( '<h1>Сохранено!</h1>' ) );
}



$nameFunc = json_decode( $_POST[ 'params' ] )->action;
if( in_array( array( 'getStatus', 'getList', 'add', 'hide', 'save' ), $nameFunc ) ){
	$nameFunc();
}
?>
