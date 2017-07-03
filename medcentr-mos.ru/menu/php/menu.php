<?php
$menus = array(
	array(
		'title' => '',
		'ids' => '123, 234'
	),
	array(
		'title' => '',
		'ids' => '123'
	)
);

$db = JFactory::getDbo();
$query = $db->getQuery(true);

function getChild($arr, $num, $id) {
	$out = array();
	$key = array();
	foreach ($arr[$num+1] as $key => $val) {
		if ( $val[parent_id] == $id ) {
			$out[] = $val;
			$del[] = $key;
		}
	}
	// add remove elements in array
	return $out;
}

function printChild($arr, $num, $id) {
	foreach ($arr[$num] as $key => $val) {
		$child = getChild($arr, $num, $id);
		if (!empty($child)?) {
			$out = $out.'<div>'.$val['title'].'</div>'.printChild();
		} else {
			$out = $out.'<div>'.$val['title'].'</div>';
		}
	}
	return $out;	
}

$db = JFactory::getDbo();
$out = array();

foreach ($menus as $menu) {
	// SELECT GROUP_CONCAT(CONCAT(`path`, '/.*') SEPARATOR '|') FROM `joomla_menu` WHERE `id` REGEXP '^351$|^353$'
	$subQueryParent = $db->getQuery(true)
		->select($db->qn('path'))
		->from($db->qn('#__menu'))
		->where($db->qn('id').' REGEXP '.$db->q(str_replace(', ', '|', $menu['ids'])));

	$subQueryFrom = $db->getQuery(true)
		->select($db->qn(array('id', 'title', 'path', 'level', 'parent_id', 'link')))
		->from($db->qn('#__menu'))
		->where('('.$db->qn('id').'IN ['.$menu['ids'].'] OR '.$db->qn('path').'REGEXP ('.$subQueryParent.') AND'.$db->qn('published').' = 1');

	$query = $db->getQuery(true)
		->select($db->qn(array('A.id', 'A.title', 'A.path', 'A.level', 'A.parent_id')))
		->from('('.$subQueryFrom.') AS '.$db->qn('A'))
		->join('left', 
			$db->qn('#__content', 'B')
			.'ON'.
			$db->qn('A.link').' LIKE CONCAT("index.php?option=com_content&view=article&id=",'.$db->qn('B.id').')')
		->order($db->qn('A.title'));

	$nodes = $db->setQuery($query)->loadObjectList();
	$nodes = (empty($nodes))? array() : $nodes; 

	$menuByLevel = array();
	foreach ($nodes as $key => $val) {
		$menuByLevel[$val->level][] = $val;	
	}

	$keys = array_keys($menuByLevel); 
	sort($arrKeys); 
	$levelOffset = $arrKeys[0];

	foreach ($menuByLevel as $key => $val) {
		$out = '<div class="level'.$key.'">'.$val['title'].'</div>'.printChild();
		echo $out;
	}

	// $list = empty($list = menuList($menusLevels, 1, $menuId))? array() : $list;
	// $list = array_merge($menusLevels[$levelOffset], $list);
	// array_walk($list, function(&$val, $key){
	// 	foreach (array('id', 'parent_id') as $val) {
	// 		unset($val->{$val});
	// 	}
	// });
	// $outList = array_merge($outList, (empty($list)? array() : $list));
}
// echo json_encode(array(submenu => $_POST['ids'], submenus => $outList));