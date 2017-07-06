<div class="box" style="border: #8be4f9 solid 2px; box-shadow: #a4b7c1 1px 1px 3px;">
	<div id="menumed">
    	<div class="med">
        	<a class="vraci ped" href="/">Педиатр</a>
        	<div class="ShliambOff submed">
	        	<?php menu('351');?>
        	</div>
        </div>
        <div class="med">
        	<a class="vraci gas" href="/">Гастроэнтеролог</a>
        	<div class="ShliambOff submed">
	        	<?php menu('353');?>
        	</div>
        </div>
        <div class="med">
        	<a class="vraci aler" href="/">Алерголог-имунолог</a>
        	<div class="ShliambOff submed">
	        	<?php menu('390');?>
        	</div>
        </div>
        <div class="med">
        	<a class="vraci hir" href="/">Хирург</a>
        	<div class="ShliambOff submed">
	        	<?php menu('351, 353');?>
        	</div>
        </div>
        <div class="med">
        	<a class="vraci ort" href="/">Ортопед-травматолог</a>
        	<div class="ShliambOff submed">
	        	<?php menu('351, 353, 390');?>
        	</div>
        </div>
        <div style="clear:left;"></div>
	</div><!--menumed-->
</div>

<?php
function getChild(&$arr, $num, $id) {
	$out = array();
	$key = array();

	if ($num > count($arr)){
		return $out;
	}

	foreach ($arr[$num] as $key => $val) {
		if ( $val->parent_id == $id ) {
			$out[] = $val;
			$del[] = $key;
		}
	}

	foreach ($del as $key => $val) {
		unset($arr[$num][$val]);
	}

	return $out;
}

function printChild(&$arr, $num, $id, $levelOffset) {
	$out = '';
	$child = getChild($arr, $num, $id);

	if (empty($child)) {
		return $out;	
	}
	
	foreach ($child as $key => $val) {
		$out = $out.'<div class="menuLevel'.($num - $levelOffset).'" menu="'.$val->id.'"><a class="menuNodeTitle" href="/'.$val->path.'">'.$val->title.'</a></div>'.printChild($arr, $num+1, $val->id, $levelOffset);
	}

	return $out;
}


// menu('351, 353, 390')
function menu( $ids ) {
	$db = JFactory::getDbo();
	$out = array();

	$subQueryParent = $db->getQuery(true)
		->select('GROUP_CONCAT(CONCAT('.$db->qn('path').', "/.*") SEPARATOR "|")')
		->from($db->qn('#__menu'))
		->where($db->qn('id').' REGEXP '.$db->q(str_replace(', ', '|', $ids)));

	$subQueryFrom = $db->getQuery(true)
		->select($db->qn(array('id', 'title', 'path', 'level', 'parent_id', 'link')))
		->from($db->qn('#__menu'))
		->where($db->qn('id').' IN ('.$ids.') OR '.$db->qn('path').' REGEXP ('.$subQueryParent.') AND'.$db->qn('published').' = 1');

	$query = $db->getQuery(true)
		->select($db->qn(array('A.id', 'A.title', 'A.path', 'A.level', 'A.parent_id')))
		->from('('.$subQueryFrom.') AS '.$db->qn('A'))
		->join('left', 
			$db->qn('#__content', 'B')
			.' ON '.
			$db->qn('A.link').' LIKE CONCAT("index.php?option=com_content&view=article&id=",'.$db->qn('B.id').')')
		->order($db->qn('A.title'));

	$nodes = $db->setQuery($query)->loadObjectList();
	$nodes = (empty($nodes))? array() : $nodes; 

	$menuByLevel = array();
	foreach ($nodes as $key => $val) {
		$menuByLevel[$val->level][] = $val;	
	}

	$keys = array_keys($menuByLevel); 
	sort($keys); 
	$levelOffset = $keys[0];

	foreach ($menuByLevel[$levelOffset] as $key => $val) {
		$out = '<div class="menuLevel0" menu="'.$val->id.'"><a class="menuNodeTitle" href="/'.$val->path.'">'.$val->title.'</a></div>'.printChild($menuByLevel, $levelOffset+1, $val->id, $levelOffset);
		echo $out;
	}
}

?>