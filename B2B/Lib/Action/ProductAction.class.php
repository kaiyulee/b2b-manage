<?php
/**
 * 酒款相关操作
 * 
 * @author zouqiang@eswine.com
 * @since 2014.06.02
 */
class ProductAction extends BaseAction {
	// 前端name传值对应酒款表字段的映射
	protected static $_map_wine_fields = array(
			'fnm'=>'fname',
			'cnm'=>'cname',
			'cad'=>'caname_id',
			'brd'=>'brand',
			'wtp'=>'winetype',
			'grp'=>'grape',
			'cty'=>'country',
			'reg'=>'region',
			'tit'=>'title',
			'yer'=>'year',
			'hpc'=>'wholesale_price'
		);
	protected static $_map_bjlj_fields = array(
			'std' => 'std_id',
			'brd' => 'brand',
			'typ' => 'type',
			'reg' => 'region',
			'fld' => 'flavor_id',
			'flv' => 'flavor',
			'bcd' => 'barcode',
			'cnm' => 'cname',
			'enm' => 'ename',
			'tit' => 'title',
			'acd' => 'alcohol_degree',
			'cpt' => 'capacity',
			'spt' => 'specificat',
			'hpc' => 'wholesale_price',
			'hos' => 'brew_house',
			'raw' => 'raw_material',
			'ftr' => 'product_features',
			'prs' => 'brew_process',
			'onr' => 'honor_awards'
		);
	public function index() {
		$mo = D('MerchantWine');
		$ymId = cookie('ym_user_uid');
		import('ORG.Util.Page');
		$proCount = $mo->field('id')->where('ym_id='.$ymId)->count();
		$Page = new Page($proCount,10);
		$Page->setConfig('theme','%upPage% %first% %linkPage% %downPage% %end%');
		$show = $Page->show();
		$res = $mo->where('ym_id='.$ymId)
				  ->field('id,fname,cname,caname_id,country,region,winetype')
		          ->limit($Page->firstRow.','.$Page->listRows)
		          ->order('id DESC')
		          ->select();
		$module = M();
		$srvCount = $module->table('b2b_merchant_service')->field('id')->where('ym_id='.cookie('ym_user_uid'))->count();
		foreach ($res as $key => $value) {
			$winetypeName = $module->table('jiuku_winetype')->field('cname')->where('id = ' . $value['winetype'] . ' and status = 1 and is_del = "-1"')->find();
			$countryName = $module->table('jiuku_country')->field('cname')->where('id = ' . $value['country'] . ' and status = 1 and is_del = "-1"')->find();
			list($_1stRegion,) = explode(',', $value['region']);
			$regionName = $module->table('jiuku_region')->field('cname')->where('id = ' . $_1stRegion . ' and status = 1 and is_del = "-1"')->find();
			if (!empty($value['caname_id'])) {
				$res[$key]['label'] = $this->getWineLabel($value['caname_id']);
			} else {
				$img = $module->table('b2b_merchant_wine_img')->field('img')
				                                       ->where('ym_id='.$ymId.' and b2b_wine_id='.$value['id'].' and img_cat=1')
				                                       ->order('id DESC')
				                                       ->find();
				$res[$key]['label'] = $img['img'];
			}
			$res[$key]['winetype_name'] = $winetypeName['cname'];
			$res[$key]['country_name'] = $countryName['cname'];
			$res[$key]['region_name'] = $regionName['cname'];
		}
		$brdCount = $mo->query('select count(1) as cnt from (select id from __TABLE__ group by brand) A');
		$countArr = array('procount' => $proCount, 'srvcount' => $srvCount, 'brdcount' => $brdCount[0]['cnt']);
		$this->assign($countArr);
		$this->assign('res',$res);
		$this->assign('count',$count);
		$this->assign('page',$show);
		$this->display('myproduct');
	}

	public function myService() {
		$mo = M('MerchantWine');
		import('ORG.Util.Page');
		$proCount = $mo->where('ym_id='.cookie('ym_user_uid'))->count();
		$srvCount = $mo->table('b2b_merchant_service')->field('id')->where('ym_id='.cookie('ym_user_uid'))->count();
		$Page = new Page($srvCount,10);
		$Page->setConfig('theme','%upPage% %first% %linkPage% %downPage% %end%');
		$show = $Page->show();
		$res = $mo->table('b2b_merchant_service')
		          ->where('ym_id='.cookie('ym_user_uid'))
		          ->limit($Page->firstRow.','.$Page->listRows)
		          ->select();
		$brdCount = $mo->query('select count(1) as cnt from (select id from __TABLE__ group by brand) A');
		$countArr = array('procount' => $proCount, 'srvcount' => $srvCount, 'brdcount' => $brdCount[0]['cnt']);
		$this->assign($countArr);
		$this->assign('res',$res);
		$this->assign('page',$show);
		$this->display();
	}
	public function myBrand() {
		$mo = D('MerchantWine');
		$ymId = cookie('ym_user_uid');
		$brand = $mo->field('brand')->where('ym_id='.$ymId)->group('brand')->select();
		foreach ($brand as $key => $value) {
			$brandName = $mo->table('jiuku_winery')
		                    ->field('fname,cname')
		                    ->where('id = ' . $value['brand'] . ' and status = 1 and is_del = "-1"')
		                    ->find();
			$brand[$key]['fname'] = $brandName['fname'];
			$brand[$key]['cname'] = $brandName['cname'];
		}
		// dump($brand);exit;
		$proCount = $mo->table('b2b_merchant_wine')->where('ym_id='.cookie('ym_user_uid'))->count();
		$srvCount = $mo->table('b2b_merchant_service')->field('id')->where('ym_id='.cookie('ym_user_uid'))->count();
		$brdCount = $mo->query('select count(1) as cnt from (select id from __TABLE__ group by brand) A');
		$countArr = array('procount' => $proCount, 'srvcount' => $srvCount, 'brdcount' => $brdCount[0]['cnt']);
		$this->assign($countArr);
		$this->assign('res',$brand);
		$this->display('mybrand');
	}
	public function chooseCategory() {
		$this->display('step1');
	}

	public function addTool() {
		$this->display('step2_add_tool');
	}

	public function addService() {
		$this->display('step2_add_service');
	}

	public function importWine(){
		$this->display('step2_import_wine');
	}

	public function addWine() {
		$country = A('Common')->getCountry();
		$years = A('Common')->getYears();
		$brands = A('Common')->getBrands();
		$wtype = A('Common')->getWineType();
		$this->assign('wtype',$wtype);
		$this->assign('country',$country);
		$this->assign('brands',$brands);
		$this->assign('year',$years);
		$this->display('step2_just_add_wine');
	}

	public function addBj(){
		$this->display('addbj');
	}

	public function addLj(){
		$this->display('addlj');
	}

	public function selectWine() {
		$country = A('Common')->getCountry();
		$wtype = A('Common')->getWineType();
		// dump($country);exit;
		$this->assign('country',$country);
		$this->assign('wtype',$wtype);
		$this->display('step2_search_to_add_wine');
	}

	public function customize() {
		$this->getWineById();
		$years = A('Common')->getYears();
		$this->assign('years',$years);
		$this->display();
	}
	public function wineEdit() {
		$id = $this->_get('id');
		if (!is_numeric($id)) {
			die('请确认酒款是否存在');
		}
		$ymId = cookie('ym_user_uid');
		$mo = D('MerchantWine');
		$module = M();
		$res = $mo->where('id = ' . $id . ' and ym_id = ' . $ymId)->find();
		$winetypeName = $module->table('jiuku_winetype')->field('cname')->where('id = ' . $res['winetype'] . ' and status = 1 and is_del = "-1"')->find();
		$countryName = $module->table('jiuku_country')->field('cname')->where('id = ' . $res['country'] . ' and status = 1 and is_del = "-1"')->find();
		// $regionName = $module->table('jiuku_region')->field('cname')->where('id = ' . $res['region'])->find();
		$wineryName = $module->table('jiuku_winery')->field('cname')->where('id = ' . $res['brand'] . ' and status = 1 and is_del = "-1"')->find();
		$regionSet = explode(',', $res['region']);
		$region = array();
		foreach ($regionSet as $key => $value) {
			$regionName = $module->table('jiuku_region')->field('cname')->where('id = ' . $value . ' and status = 1 and is_del = "-1"')->find();
			$region[] = array('id'=>$value,'name'=>$regionName['cname']);
		}
		$grapeSet = array_filter(explode(',', $res['grape']));
		$grapes = array();
		if (!empty($grapeSet)) {
			foreach ($grapeSet as $k => $v) {
				$grapeName = $module->table('jiuku_grape')->field('fname,cname')->where('id = ' . $v . ' and status = 1 and is_del = "-1"')->find();
				$grapes[] = array('id' => $v, 'fname'=>$grapeName['fname'], 'cname' => $grapeName['cname']);
			}
		}
		$imgs = $module->table('b2b_merchant_wine_img')
		               ->field('img,img_cat')
		               ->where('b2b_wine_id='.$id.' and ym_id='.$ymId)
		               ->group('img_cat')
		               ->select();
		$res['winetype_name'] = $winetypeName['cname'];
		$res['country_name'] = $countryName['cname'];
		$res['winery_name'] = $wineryName['cname'];
		$res['region_set'] = $region;
		$res['grape_set'] = $grapes;
		$res['year_set'] = explode(',', $res['year']);
		$res['imgs'] = $imgs;
		// dump($grapeSet);exit;
		$country = A('Common')->getCountry();
		$years = A('Common')->getYears();
		$brands = A('Common')->getBrands();
		$wtype = A('Common')->getWineType();
		$this->assign('wtype',$wtype);
		$this->assign('country',$country);
		$this->assign('brands',$brands);
		$this->assign('grapes',array_filter($grapes));
		$this->assign('year',$years);
		$this->assign('imgs',$imgs);
		$this->assign('yearset',array_filter($res['year_set']));
		$this->assign('res',$res);
		$this->display('wine_edit');
	}

	public function bjljEdit($id=0) {
		if (!is_numeric($id)) {
			die('请确认酒款是否存在');
		}
		$mo = M();
		$ymId = cookie('ym_user_uid');
		$res = $mo->table('b2b_merchant_bjlj')->where('id='.$id.' and ym_id='.$ymId)->find();
		$type = $res['type'];
		$imgs = $mo->table('b2b_merchant_wine_img')
		               ->field('img,img_cat')
		               ->where('b2b_wine_id='.$id.' and ym_id='.$ymId.' and type='.$type)
		               ->group('img_cat')
		               ->select();
		$this->assign('res',$res);
		$this->assign('imgs',$imgs);
		$this->display('bjlj_edit');
	}

	public function toolEdit($id) {
		if (!is_numeric($id)) {
			die('请确认酒款是否存在');
		}
		$mo = M();
		$res = $mo->table('b2b_merchant_tool')->where('ym_id='.cookie('ym_user_uid').' and id='.$id)->find();
		$imgs = $mo->table('b2b_merchant_other_img')->where('ym_id='.cookie('ym_user_uid').' and ref='.$id.' and cat="tool"')->select();
		$this->assign('res',$res);
		$this->assign('imgs',$imgs);
		$this->display('tool_edit');
	}

	public function serviceEdit($id) {
		if (!is_numeric($id)) {
			die('请确认酒款是否存在');
		}
		$mo = M();
		$res = $mo->table('b2b_merchant_service')->where('ym_id='.cookie('ym_user_uid').' and id='.$id)->find();
		$imgs = $mo->table('b2b_merchant_other_img')->where('ym_id='.cookie('ym_user_uid').' and ref='.$id.' and cat="service"')->select();
		$this->assign('res',$res);
		$this->assign('imgs',$imgs);
		$this->display('service_edit');
	}

	public function search() {
		$module = M();
		$srh_map = array(
			'cid'	=>	'A.country_id',
			'rid'	=>	'A.region_id',
			'nid'	=>	'A.winery_id',
			'tid'	=>	'A.winetype_id'
		);
		$gets = array();
		$gets['cid'] = $this->_get('cid');
		$gets['rid'] = $this->_get('rid');
		$gets['nid'] = $this->_get('nid');
		$gets['tid'] = $this->_get('tid');
		$criteria = KVMapping($srh_map,$gets);
		$criteria = array_filter($criteria);
		$input = $this->_get('input');		
		if (!empty($input)) {
			if (preg_match('/[\x7f-\xff]+/', $input)) {
				$criteria['A.cname'] = array('like','%'.$input.'%');
			} else {
				$criteria['A.fname'] = array('like','%'.$input.'%');
			}
		}
		// extra condition
		$criteria['A.status'] = '1';
		$criteria['A.is_del'] = '-1';
		$criteria['A.is_merge'] = '-1';
		$fields = 'A.id,A.fname,A.cname,A.wine_id,A.country_id,A.region_id,A.grape_id,A.winetype_id,A.winery_id,B.caname_id AS et';
		import('ORG.Util.Page');
		$count = $module->table('jiuku_wine_caname A')->where($criteria)->count();
		$Page = new Page($count,10);
		$Page->setConfig('theme','%upPage% %first% %linkPage% %downPage% %end%');
		$show = $Page->show();
		$result = $module->table('jiuku_wine_caname A')
					->join('left join b2b_merchant_wine B ON A.id = B.caname_id')
					->field($fields)
					->where($criteria)
					->limit($Page->firstRow.','.$Page->listRows)
					->order('A.id DESC')
					->select();
		foreach ($result as $key => $value) {
			$winetypeName = $module->table('jiuku_winetype')->field('cname')->where('id = ' . $value['winetype_id'] . ' and status = 1 and is_del = "-1"')->find();
			$countryName = $module->table('jiuku_country')->field('cname')->where('id = ' . $value['country_id'] . ' and status = 1 and is_del = "-1"')->find();
			$regionName = $module->table('jiuku_region')->field('cname')->where('id = ' . $value['region_id'] . ' and status = 1 and is_del = "-1"')->find();
			$wineryName = $module->table('jiuku_winery')->field('cname')->where('id = ' . $value['winery_id'] . ' and status = 1 and is_del = "-1"')->find();
			$result[$key]['label'] = $this->getWineLabel($value['id']);
			$result[$key]['winetype_name'] = $winetypeName['cname'];
			$result[$key]['country_name'] = $countryName['cname'];
			$result[$key]['region_name'] = $regionName['cname'];
			$result[$key]['winery_name'] = $wineryName['cname'];
		}
		$this->assign('total',$count);
		$this->assign('list',$result);
		$this->assign('page',$show);
		$this->display();
	}
	/**
	 * search wines from houtai.wine.cn
	 */ 
	public function searchHouTai() {
		// todo 
	}

	public function getWineById(){
		$ids = $this->_get('ids');
		if (empty($ids)) {
			return false;
		}
		$module = M();
		$fields = 'A.id,A.fname,A.cname,A.wine_id,A.country_id,A.region_id,A.winetype_id,A.winery_id,B.caname_id AS et';
		$criteria['A.status'] = '1';
		$criteria['A.is_del'] = '-1';
		$criteria['A.is_merge'] = '-1';
		$criteria['A.id'] = array('in',$ids);
		$result = $module->table('jiuku_wine_caname A')
					->join('left join b2b_merchant_wine B ON A.id = B.caname_id')
					->field($fields)
					->where($criteria)
					->order('A.id DESC')
					->select();
		foreach ($result as $key => $value) {
			$winetypeName = $module->table('jiuku_winetype')->field('cname')->where('id = ' . $value['winetype_id'] . ' and status = 1 and is_del = "-1"')->find();
			$countryName = $module->table('jiuku_country')->field('cname')->where('id = ' . $value['country_id'] . ' and status = 1 and is_del = "-1"')->find();
			$regionName = $module->table('jiuku_region')->field('cname')->where('id = ' . $value['region_id'] . ' and status = 1 and is_del = "-1"')->find();
			$wineryName = $module->table('jiuku_winery')->field('cname')->where('id = ' . $value['winery_id'] . ' and status = 1 and is_del = "-1"')->find();
			$result[$key]['winetype_name'] = $winetypeName['cname'];
			$result[$key]['country_name'] = $countryName['cname'];
			$result[$key]['region_name'] = $regionName['cname'];
			$result[$key]['winery_name'] = $wineryName['cname'];
		}
		$this->assign('list',$result);
	}

	public function doAddWineBySearch(){
		$data = $this->_request();
		$info = $data['info'];
		if (empty($info)) {
			return false;
		}
		$mo = D('MerchantWine');
		$ymId = cookie('ym_user_uid');
		$flag = false;
		foreach ($info as $key => $value) {
			$trueData = KVMapping(static::$_map_wine_fields,$value);
			$trueData['ym_id'] = $ymId;
			$mo->create($trueData);
			$flag = $mo->add();
		}
		if ($flag) {
			$msg['st'] = 1;
		} else {
			$msg['st'] = 0;
		}
		$this->ajaxReturn($msg);
	}

	public function doAddWine() {
		// 添加非标准[用户自建]
		$data = $this->_request();
		$info = $data['info'];
		$mo = D('MerchantWine');
		$trueData = KVMapping(static::$_map_wine_fields,$info);
		if (empty($trueData)) {
			return false;
		}
		$mo->create($trueData);
		$mo->ym_id = cookie('ym_user_uid');
		$result = $mo->add();
		$receivedImg = $data['imgs'];
		array_filter($receivedImg);
		// the step below won't be showed to user-side, whether or not the imgs are uploaded successfully.
		if (!empty($receivedImg)) {
			if ($result) {
				# add the imgs to db
				foreach ($receivedImg as $key => $value) {
					$data = array();
					switch ($key) {
						case 'cat1':
							$data['img_cat'] = '1';
							break;
						case 'cat2':
							$data['img_cat'] = '2';
							break;
						case 'cat3':
							$data['img_cat'] = '3';
							break;
					}
					$data['img'] = $value;
					$data['ym_id'] = cookie('ym_user_uid');
					$data['type'] = 0;
					$data['b2b_wine_id'] = $result;
					$moo = M();
					$moo->table('b2b_merchant_wine_img')->data($data)->add();
				}
			} else {
				# delete the imgs file
				$imagesPath = './Upload/images/';
				foreach ($receivedImg as $key => $imgName) {
					$imgFileName = $imagesPath . $imgName;
					unlink($imgFileName);
				}
			}
		}
		if (false !== $result) {
			$this->ajaxReturn(array('st'=>1),'JSON');
		} else {
			$this->ajaxReturn(array('st'=>0),'JSON');
		}
	}

	public function doAddBjlj() {
		// 用户自建白酒洋酒
		$data = $this->_request();
		$info = $data['info'];
		$mo = D('MerchantBjlj');
		$trueData = KVMapping(static::$_map_bjlj_fields,$info);
		if (empty($trueData)) {
			return false;
		}
		$imgType = $trueData['type'];
		$mo->create($trueData);
		$mo->ym_id = cookie('ym_user_uid');
		$result = $mo->add();
		$receivedImg = $data['imgs'];
		array_filter($receivedImg);
		// the step below won't be showed to user-side, whether or not the imgs are uploaded successfully.
		if (!empty($receivedImg)) {
			if ($result) {
				# add the imgs to db
				foreach ($receivedImg as $key => $value) {
					$data = array();
					switch ($key) {
						case 'cat1':
							$data['img_cat'] = '1';
							break;
						case 'cat2':
							$data['img_cat'] = '2';
							break;
						case 'cat3':
							$data['img_cat'] = '3';
							break;
					}
					$data['img'] = $value;
					$data['ym_id'] = cookie('ym_user_uid');
					$data['type'] = $imgType;
					$data['b2b_wine_id'] = $result;
					$moo = M();
					$moo->table('b2b_merchant_wine_img')->data($data)->add();
				}
			} else {
				# delete the imgs file
				$imagesPath = './Upload/images/';
				foreach ($receivedImg as $key => $imgName) {
					$imgFileName = $imagesPath . $imgName;
					unlink($imgFileName);
				}
			}
		}
		if (false !== $result) {
			$this->ajaxReturn(array('st'=>1),'JSON');
		} else {
			$this->ajaxReturn(array('st'=>0),'JSON');
		}
	}

	public function doAddTool(){
		$mo = D('MerchantTool');
		$mo->create();
		$mo->ym_id = cookie('ym_user_uid');
		$result = $mo->add();
		// 关于图片
		// 前端这么传:{imgs: {img1, img2, img3}}
		// 后端这么接:$receivedImg = $this->_post('imgs'); 
		$imgStr = $this->_post('imgs');
		$receivedImg = array_filter(explode(',', $imgStr));
		$this->_afterAdd($result,$receivedImg,'tool');
		if (false !== $result) {
			$this->ajaxReturn(array('st'=>1),'JSON');
		} else {
			$this->ajaxReturn(array('st'=>0),'JSON');
		}
	}

	public function doAddService() {
		$mo = D('MerchantService');
		$mo->create();
		$mo->ym_id = cookie('ym_user_uid');
		$result = $mo->add();
		// 关于图片
		// 前端这么传:{imgs: {img1, img2, img3}}
		// 后端这么接:$receivedImg = $this->_post('imgs'); 
		$imgStr = $this->_post('imgs');
		$receivedImg = array_filter(explode(',', $imgStr));
		$this->_afterAdd($result,$receivedImg,'service');
		if (false !== $result) {
			$this->ajaxReturn(array('st'=>1),'JSON');
		} else {
			$this->ajaxReturn(array('st'=>0),'JSON');
		}
	}

	/**
	 * 在执行添加酒具或者服务的操作后,根据添加结果处理图片,仅适用与酒具和服务添加
	 * @param boolean || int $add_handler 添加酒具或者服务时返回的结果
	 * @param array $receivedImg 图片数组
	 * @param string $cat 图片所属酒具还是服务
	 * @return void 
	 */
	protected function _afterAdd($add_handler,$receivedImg,$cat){
		if (!empty($receivedImg)) {
			if ($add_handler) {
				# add the imgs to db
				foreach ($receivedImg as $key => $value) {
					$data = array();
					$data['ym_id'] = cookie('ym_user_uid');
					$data['imgnm'] = $value;
					$data['cat'] = $cat;
					$data['ref'] = $add_handler;
					M()->table('b2b_merchant_other_img')->data($data)->add();
				}
			} else {
				# delete the imgs file
				$imagesPath = './Upload/images/';
				foreach ($receivedImg as $key => $imgName) {
					$imgFileName = $imagesPath . $imgName;
					unlink($imgFileName);
				}
			}
		}
	}

	protected function getMyWine(){
		$mo = D('MerchantWine');
		$ymId = cookie('ym_user_uid');
		if (!is_numeric(intval($ymId))) {
			return false;
		}
		$list = $mo->where('ym_id='.$ymId)->select();
		return $list;
	}

	protected function getMyTool(){
		$mo = D('MerchantTool');
		$ymId = cookie('ym_user_uid');
		if (!is_numeric(intval($ymId))) {
			return false;
		}
		$list = $mo->where('ym_id='.$ymId)->select();
		return $list;
	}

	protected function getMyService(){
		$mo = D('MerchantService');
		$ymId = cookie('ym_user_uid');
		if (!is_numeric(intval($ymId))) {
			return false;
		}
		$list = $mo->where('ym_id='.$ymId)->select();	
		return $list;
	}

	public function uploadWineImg() {
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
        $upload->maxSize = 2000000;
        $upload->allowExts = array('jpg','jpeg','png','gif');
        $upload->subType = 'custom';
        $upload->autoSub = true;
        $upload->savePath = './Upload/images/';
        $upload->subDir = 'wine/label/';
        $info = $upload->upload();
        if (!$info) {
        	// $error = $upload->getErrorMsg();
        	$st = array('st' => 0);
        	$this->ajaxReturn($st,'JSON');
        } else {
        	$uploadFileInfo = $upload->getUploadFileInfo();
        	$imgArr = array();
        	foreach ($uploadFileInfo as $key => $value) {
        		$imgArr['msg'] = $value['savename'];
        	}
        	$imgArr['st'] = 1;
        	$this->ajaxReturn($imgArr,'JSON');
        }
    	return false;
	}

	public function uploadOtherImg(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
        $upload->maxSize = 3000000;
        $upload->allowExts = array('jpg','jpeg','png','gif');
        $upload->savePath = './Upload/images/';
        $info = $upload->upload();
        if (!$info) {
        	//$error = $upload->getErrorMsg();
        	$img['st'] = 0;
        	$this->ajaxReturn($img,'JSON');
        } else {
        	$uploadFileInfo = $upload->getUploadFileInfo();
        	foreach ($uploadFileInfo as $key => $value) {
        		$img['st'] = 1;
        		$img['img'] = $uploadFileInfo[$key]['savename'];
        	}
        	$this->ajaxReturn($img,'JSON');
        }
    	return false;
	}
	public function deleteOtherImg(){
		$imgName = $this->_request('img');
		if (empty($imgName)) {
			return false;
		}
		// delete from disk
		$fileName = './Upload/images/' . $imgName;
		if (!file_exists($fileName)) {
			return false;
		}
		$st = unlink($fileName);
		// then delete from database
		if ($st) { // 物理文件删除成功
			$mo = M();
			$where = array();
			$where['imgnm'] = $imgName;	
			//这里的删除语句不作过多条件限制是因为TP上传时采用的默认命名规则是unique,不会产生同名文件.且同一目录下也不可能存在同名文件,请知晓.
			$del = $mo->table('b2b_merchant_other_img')->where($where)->delete();
			$img = array();
			if (false === $del) {
				$img['st'] = 0;
				$this->ajaxReturn($img,'JSON');
			} else {
				$img['st'] = 1;
				$this->ajaxReturn($img,'JSON');
			}
		}
		return false;
	}

	// 标准酒款图片
	public function getWineLabel($std_id) {
		if (empty($std_id)) {
			return false;
		}
		$mo = M();
		$wid = $mo->table('jiuku_wine_caname')->field('wine_id')->where('id='.$std_id.' and is_del="-1" and status = 1 and is_merge="-1"')->find();
		$wine_id = $wid['wine_id'];
		if (empty($wine_id)) {
			return false;
		}
		$file = $mo->table('jiuku_wine_label')->field('filename')->where('wine_id='.$wine_id.' and is_del="-1"')->find();
		$img = $file['filename'];
		return $img;
	}

//  -------------------------------updates-------------------------------
	public function doUpdateWine($id) {
		if (!is_numeric($id)) {
			die('请确认酒款!');
		}
		$data = $this->_request();
		$info = $data['info'];
		$mo = D('MerchantWine');
		$trueData = KVMapping(static::$_map_wine_fields,$info);
		if (empty($trueData)) {
			return false;
		}
		$mo->create($trueData);
		$mo->ym_id = cookie('ym_user_uid');
		$result = $mo->where('id='.$id)->save();
		$receivedImg = $data['imgs'];
		$receivedImg = array_filter($receivedImg);
		// the step below won't be showed to user-side, whether or not the imgs are uploaded successfully.
		if (!empty($receivedImg)) {
			if ($result) {
				# add the imgs to db
				foreach ($receivedImg as $key => $value) {
					$data = array();
					switch ($key) {
						case 'cat1':
							$data['img_cat'] = '1';
							break;
						case 'cat2':
							$data['img_cat'] = '2';
							break;
						case 'cat3':
							$data['img_cat'] = '3';
							break;
					}
					$data['img'] = $value;
					// $data['ym_id'] = cookie('ym_user_uid');
					// $data['type'] = 0;
					// $data['b2b_wine_id'] = $result;
					$moo = M();
					$moo->table('b2b_merchant_wine_img')
					    ->where('img_cat = '. $data['img_cat'] .' and b2b_wine_id = ' .$id.' and type=0')
					    ->data($data)
					    ->save();
				}
			}
		}
		if ($result) {
			$this->ajaxReturn(array('st'=>1),'JSON');
		} else {
			$this->ajaxReturn(array('st'=>0),'JSON');
		}
	}
	//更新酒具或者服务
	public function doUpdateSt($id){
		if (!is_numeric($id)) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据非法'),'JSON');
			return false;
		}
		$type = $this->_request('type');
		if (empty($type) || (intval($type) != 1 && intval($type) != 2)) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据非法0'),'JSON');
			return false;
		}
		switch ($type) {
			case '1':
				$mo = D('MerchantTool');
				break;
			
			case '2':
				$mo = D('MerchantService');
				break;
		}
		$mo->create();
		$result = $mo->where('id='.$id)
		             ->save();
		$imgStr = $this->_post('imgs');
		$receivedImg = array_filter(explode(',', $imgStr));
		$this->_afterAdd($result,$receivedImg,'service');
		if (false !== $result) {
			$this->ajaxReturn(array('st'=>1),'JSON');
		} else {
			$this->ajaxReturn(array('st'=>0),'JSON');
		}
	}

	public function doUpdateBjlj($id){
		if (!is_numeric($id)) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据非法'),'JSON');
			return false;
		}
		$data = $this->_request();
		$info = $data['info'];
		$mo = D('MerchantBjlj');
		$trueData = KVMapping(static::$_map_bjlj_fields,$info);
		if (empty($trueData)) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据非法0'),'JSON');
			return false;
		}
		$imgType = $trueData['type'];
		$mo->create($trueData);
		// $mo->ym_id = cookie('ym_user_uid');
		$result = $mo->where('id='.$id)->save();
		$receivedImg = $data['imgs'];
		$receivedImg = array_filter($receivedImg);
		// the step below won't be showed to user-side, whether or not the imgs are uploaded successfully.
		if (!empty($receivedImg)) {
			if ($result) {
				# add the imgs to db
				foreach ($receivedImg as $key => $value) {
					$data = array();
					switch ($key) {
						case 'cat1':
							$data['img_cat'] = '1';
							break;
						case 'cat2':
							$data['img_cat'] = '2';
							break;
						case 'cat3':
							$data['img_cat'] = '3';
							break;
					}
					$data['img'] = $value;
					$moo = M();
					$moo->table('b2b_merchant_wine_img')
					    ->where('img_cat = '. $data['img_cat'] .' and b2b_wine_id = ' .$id. ' and type='.$imgType)
					    ->data($data)
					    ->save();
				}
			} 
		}
		if (false !== $result) {
			$this->ajaxReturn(array('st'=>1),'JSON');
		} else {
			$this->ajaxReturn(array('st'=>0),'JSON');
		}
	}
// end of updates
// -------------------------------deletes-------------------------------
	public function deleteWine($id){
		if (!is_numeric($id)) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据非法'),'JSON');
			return false;
		}
		$mo = D('MerchantWine');
		$count = $mo->where('id='.$id.' and ym_id='.cookie('ym_user_uid'))->count();
		if (!$count) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据不存在或已删除'),'JSON');
			return false;
		}
		$res = $mo->where('id='.$id.' and ym_id='.cookie('ym_user_uid'))->delete();
		if (false !== $res) {
			$this->ajaxReturn(array('st'=>1,'msg'=>'success'),'JSON');
		} else {
			$this->ajaxReturn(array('st'=>0,'msg'=>'error'),'JSON');
		}
	}

	public function deleteSt($id){
		if (!is_numeric($id)) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据非法'),'JSON');
			return false;
		}
		$type = $this->_request('type');
		if (empty($type)) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据非法'),'JSON');
			return false;
		}
		switch ($type) {
			case '1':
				$mo = D('MerchantTool');
				break;
			
			case '2':
				$mo = D('MerchantService');
				break;
		}
		$count = $mo->where('id='.$id.' and ym_id='.cookie('ym_user_uid'))->count();
		if (!$count) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据不存在或已删除'),'JSON');
			return false;
		}
		$res = $mo->where('id='.$id.' and ym_id='.cookie('ym_user_uid'))->delete();
		if (false !== $res) {
			$this->ajaxReturn(array('st'=>1,'msg'=>'success'),'JSON');
		} else {
			$this->ajaxReturn(array('st'=>0,'msg'=>'error'),'JSON');
		}
	}

	public function deleteBjlj($id){
		if (!is_numeric($id)) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据非法'),'JSON');
			return false;
		}
		$mo = M('MerchantBjlj');
		$count = $mo->where('id='.$id.' and ym_id='.cookie('ym_user_uid'))->count();
		if (!$count) {
			$this->ajaxReturn(array('st'=>0,'msg'=>'数据不存在或已删除'),'JSON');
			return false;
		}
		$res = $mo->where('id='.$id.' and ym_id='.cookie('ym_user_uid'))->delete();
		if (false !== $res) {
			$this->ajaxReturn(array('st'=>1,'msg'=>'success'),'JSON');
		} else {
			$this->ajaxReturn(array('st'=>0,'msg'=>'error'),'JSON');
		}
	}
// end of deletes
}
