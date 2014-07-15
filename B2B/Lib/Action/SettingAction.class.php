<?php
class SettingAction extends BaseAction {
	public function _empty(){
		$this->index();
	}
	public function index(){
		$this->display();
	}
	public function address(){
		$this->display();
	}
	public function indexSet(){
		$mo = M();
		$res = $mo->table('b2b_merchant_page')->field('img,content')->where('ym_id='.cookie('ym_user_uid').' and channel="主页"')->find();
		$this->assign('res',$res);
		$this->display('indexset');
	}
	public function introSet(){
		$mo = M();
		$res = $mo->table('b2b_merchant_page')->field('content')->where('ym_id='.cookie('ym_user_uid').' and channel="企业介绍"')->find();
		$this->assign('res',$res);
		$this->display('introset');
	}
	public function policySet(){
		$mo = M();
		$res = $mo->table('b2b_merchant_page')->field('content')->where('ym_id='.cookie('ym_user_uid').' and channel="招商政策"')->find();
		$this->assign('res',$res);
		$this->display('policyset');
	}

	public function setPageContent(){
		$channel = $this->_post('channel');
		$ymId = cookie('ym_user_uid');
		$isAdded = true;
		$Ym = M();
		switch($channel){
			case 1:
				$channel = '主页';
				$htmlfile = 'company_'.$ymId;
				$htmltemplate = 'TemplateForCompany:index';
				break;
			case 2:
				$channel = '企业介绍';
				$htmlfile = 'company_'.$ymId.'_intro';
				$htmltemplate = 'TemplateForCompany:intro';
				break;
			case 3:
				$channel = '招商政策';
				$htmlfile = 'company_'.$ymId.'_agencies';
				$htmltemplate = 'TemplateForCompany:agencies';
				break;
		}
		$data = $Ym->table('b2b_merchant_page')->where('ym_id='.$ymId.' AND channel="'.$channel.'"')->find();
		if(empty($data)){
			$isAdded = false;	
		}
		$pageContent = $this->_post('pagecontent');
		$pageImg = $this->_post('pageimg');
		$msg = array();
		if($isAdded){ // 更新数据
			if ($channel == '主页') {
				$saveData['img'] = $pageImg;
			}
			$saveData['content'] = $pageContent;
			$res = $Ym->table('b2b_merchant_page')->where('ym_id='.$ymId.' AND channel="'.$channel.'"')->save($saveData);
			if ($res === false) {
				$msg['st'] = 0;
				$msg['msg'] = '失败';
			} else {
				$msg['st'] = 1;
				$msg['msg'] = '成功';
			}
		}else{ // 新增数据
			$addData['ym_id'] = cookie('ym_user_uid');
			if ($channel == '主页') {
				$addData['img'] = $pageImg;
			}
			$addData['content'] = $pageContent;
			$addData['channel'] = $channel;
			$addData['channel_order'] = 0;
			$addData['display'] = 1;
			$res = $Ym->table('b2b_merchant_page')->add($addData);
			if ($res) {
				$msg['st'] = 1;
				$msg['msg'] = '成功';
			} else {
				$msg['st'] = 0;
				$msg['msg'] = '失败';
			}
		}
		// 生成静态页面 
		$htmlpath = $_SERVER['DOCUMENT_ROOT'].'/Html/company/';
		$aside = $Ym->table('ym_users_qy A')->join('ym_users B on A.id=B.id')->where('A.id='.$ymId)->find();
		$this->assign($aside);
		$this->assign('banner',$pageImg);
		$this->assign('content',$pageContent);
		$this->buildHtml($htmlfile,$htmlpath,$htmltemplate);
		echo json_encode($msg);
	}
	/**
	 * 首页设置上传图片部分.里面作两种情况判断,一是,图片对应记录已存在则更新,否则添加
	 */
	public function uploadIndexImg(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
        $upload->maxSize = 2000000;
        $upload->allowExts = array('jpg','jpeg','png','gif');
        $upload->savePath = './Upload/images/';
        $info = $upload->upload();
        if(!$info){
            $this->error($upload->getErrorMsg());
        }else{
			$uploadFileInfo = $upload->getUploadFileInfo();
			$mod = M();
			$record = $mod->table('b2b_merchant_page')->field('channel,img')->where('ym_id='.cookie('ym_user_uid').' AND channel="主页"')->find();
			if($record){ // 存在
				$saveData['img'] = $uploadFileInfo[0]['savename'];
				$saveStatus = $mod->table('b2b_merchant_page')->where('ym_id='.cookie('ym_user_uid').' AND channel="主页"')->save($saveData);
				if ($saveStatus) {
					$indexPicPath = './Upload/images/index_pic_'.cookie('ym_user_uid').'/';
					if (!is_dir($indexPicPath)) {
						mkdir($indexPicPath,0777,true);
					}
					rename('./Upload/images/'.$record['img'],$indexPicPath.$record['img']);
				}
				if ($saveStatus !== false) {
					$st = 1;
				} else {
					$st = 0;
				}
			}else{ // 不存在
				$addData['ym_id'] = cookie('ym_user_uid');
				$addData['img'] = $uploadFileInfo[0]['savename'];
				$addData['channel'] = '主页';
				$added = $mod->table('b2b_merchant_page')->add($addData);
				if ($added !== false) {
					$st = 1;
				} else {
					$st = 0;
				}
			}
        }
        $res = array('img' => $uploadFileInfo[0]['savename'],'st'=>$st);
        echo json_encode($res);
    }   
		
	// 检查字段是否已有值
	protected function is_empty($table,$field,$where){
		$mode = M();
		$box = $mode->table($table)->field($field)->where($where)->find();
		if ( empty( $box ) ) {
			return true;
		} else {
			return false;
		}
	}
}
