<?php
class IndexAction extends BaseAction {
    public function index(){
    	$this->commentList();
		$this->display();
    }
	public function commentList(){
		$mode = M();
		$ymId = $this->_cookie('ym_user_uid');
		if (!is_numeric(intval($ymId))) {
			return false;
		}
		import('ORG.Util.Page');
		$count = $mode->table('b2b_merchant_user_comment')->where('ym_id='.$ymId)->count();
		$Page = new Page($count,20);
		$show = $Page->show();
		$list = $mode->table('b2b_merchant_user_comment')->where('ym_id='.$ymId)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		$this->assign('page',$show);
		$this->assign('list',$list);
	}

	private function deleteComment(){
		$cmid = $this->_post('cmid');
		if (is_numeric(intval($cmid))) {
			$mo = M();
			$res = $mo->table('b2b_merchant_user_comment')->where('id='.$cmid)->delete();
		}
		$msg = array();
		if (false !== $res) {
			$msg['st'] = 1;
		} else {
			$msg['st'] = 0;
		}
		echo json_encode($msg);
	}
}
