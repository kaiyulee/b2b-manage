<?php
class BaseAction extends Action {
	public function __construct(){
		parent::__construct();
		$ymId = cookie('ym_user_uid');
		if (empty($ymId)) {
			header("location:".C('TMPL_PARSE_STRING.__YM__'));
			exit;
		}
	}
}