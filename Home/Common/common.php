<?php
/**
 * key与value的映射,用于前端name传值来寻找对应的数据表字段
 * @param array $map 映射规则
 * @param array $data 需要被映射的数据
 * @return array 返回映射后的结果数组
 * @author zouqiang@eswine.com
 */
function KVMapping($map='',$data=''){
	if (empty($map) || empty($data)) {
		return false;
	} else if (!is_array($map) || !is_array($data)) {
		return false;
	}		 
	$trueData = array();
	foreach ($data as $key => $value) {
		if (array_key_exists($key, $map)) {
			$trueData[$map[$key]] = $value;
		}
	}
	return $trueData;
}