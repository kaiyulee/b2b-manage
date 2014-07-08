<?php
class CommonAction extends BaseAction {
	public static $mod = null;
	public function __construct(){
		static::$mod = M();
	}

	public function upload(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize = 2000000;
		$upload->exts = array('jpg','jpeg','png','gif');
		$upload->savePath = './Upload/images';
		$info = $upload->upload();
		if(!$info){
			$this->error($upload->getError());
		}else{
			echo '上传成功';
		}
	}

	public function getCountry(){
		$where = "status=1 and is_del='-1'";
		$result = static::$mod->table('jiuku_country')->field('id,fname,cname')->where($where)->select();
		return $result;
	}	
	// only level zero
	public function getRegByCtry(){
		$country = intval($_REQUEST['ct']);
		$level = 0;
		if ($lv = $this->_get('reglv')) {
			$level = $lv;
		}
		if (empty($country)) {
			$where = "status = 1 and is_del = '-1'";
		} elseif (!is_numeric($country)) {
			return false;
		} else {
			$where = "country_id = $country and status = 1 and is_del = '-1' and pid = $level";
		}
		$result = static::$mod->table('jiuku_region')->field('id,fname,cname,pid,pid2,regionlevel_id,country_id')->where($where)->select();
		echo json_encode($result);

	}

	public function getWineType(){
		$result = static::$mod->table('jiuku_winetype')->field('id,fname,cname')->where('status=1 and is_del = \'-1\'')->select();
		return $result;
	}

	public function getBrand($where = ''){
		$where['status'] = '1';
		$where['is_del'] = '-1';
		$result = static::$mod->table('jiuku_winery')->field('id,fname,cname,country_id')->where($where)->select();
		return $result;
	}	

	public function getBrands(){
		$keyup = false;
		$keyup = $this->_post('is_keyup',false,0);
		$match = $this->_post('match');
		$mode = M();
		$where = array();
		$where['status'] = '1';
		$where['is_del'] = '-1';
		if (isset($keyup) && $keyup == true) {
			$where['_string'] = 'fname LIKE "%'.$match.'%" OR cname LIKE "%'.$match.'%"';
			$result = $mode->table('jiuku_winery')->field('id,fname,cname,country_id')->where($where)->limit(100)->select();
			echo json_encode($result);
		} else {
			$result = $mode->table('jiuku_winery')->field('id,fname,cname,country_id')->where($where)->select();
		}
		return $result;
	}

	public function getGrapeType(){
		$mode = M();
		$match = trim($this->_get('match'));
		$list = $mode->table('jiuku_grape')->field('id,fname,cname')->where('fname LIKE "%'.$match.'%" OR cname LIKE "%'.$match.'%"')->select();
		if(empty($list)){
			return false;
		}
		echo json_encode($list);
		return $list;
	}

	public function getYears(){
		return array_reverse(range(1900,date('Y')));
	}

	public function logout() {
		cookie('ym_user_uid',null);
		header("location:".C('TMPL_PARSE_STRING.__YM__').'/index.php/User/loginout');
		exit;
	}
}
