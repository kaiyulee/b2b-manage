<?php
$currentUrl = $_SERVER['REQUEST_URI'];
if(preg_match('#^\/company\/company_[1-9]+(_\w+)?.html$#i', $currentUrl,$match)){
	$html = strtolower($match[0]);
	$filename = $_SERVER['DOCUMENT_ROOT'].'/Html'.$html;
	if (is_file($filename)) {
		include_once $filename;
	} else {
		die($html.'不存在，请转至<a href="http://b2b.wine.cn/Setting/">页面->展铺装修</a>处设置');
	}
	exit;
}