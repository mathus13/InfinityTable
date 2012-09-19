<?php
class couch_helper{
	function merge_doc($object, $array){
		foreach($array as $k=>$v){
			$object->$k = $v;
		}
		return $object;
	}
	private function reduce($results){
		if(counts($results['rows'])>0){
			$list = array();
			foreach($results['rows'] as $row){
				$list[]=$row['value'];
			}
			return $list;
		}else{
			return false;
		}
	}
}
