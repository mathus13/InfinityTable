<?php
function merge_doc($object, $array){
	foreach($array as $k=>$v){
		$object->$k = $v;
	}
	return $object;
}
function reduce($results){
	if(count($results['rows'])>0){
		$list = array();
		foreach($results['rows'] as $row){
			$list[]=$row['value'];
		}
		return $list;
	}else{
		return false;
	}
}
