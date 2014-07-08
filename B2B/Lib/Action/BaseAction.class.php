<?php
class BaseAction extends Action {
	public function __construct(){
		parent::__construct();
		$ymId = cookie('ym_user_uid');
		if (empty($ymId)) {
			header("location:".C('TMPL_PARSE_STRING.__YM__'));
			exit;
		} else {
			$userinfo = $this->getUserInfo();
			$this->assign('user',$userinfo);
		}
	}

	/**
     * Enter description here...
     * 获取用户资料
     */
    public function getUserInfo(){
    	$userinfoModel = D("UsersInfo");
    	$userqyModel = D("UsersQy");
    	if($this->getUid()){
    		$id = $this->getUid();
    		$userinfo = $userinfoModel->where("id=".$id)->find();
            $userinfo["qy_name"] = $userqyModel->where("id=".$id)->getField("qy_name");   
            $userinfo["b_type"] = $userqyModel->where("id=".$id)->getField("b_type");   
            $userinfo["comany_valid"] = $userqyModel->where("id=".$id)->getField("comany_valid");   
    		return $userinfo;
    	}
    	return false;
    }

    public function getUid(){
		return isset($_SESSION["ym_users"]["uid"])?$_SESSION["ym_users"]["uid"]:false;
    }
}