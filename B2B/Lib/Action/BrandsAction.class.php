<?php
class BrandsAction extends Action {
	public function _empty() {
		$this->index();
	}
	public function index($country=0) {
		$mo = D('MerchantWine');
		$where = array();
		if (!empty($_REQUEST['ctry'])) {
			$country = $_REQUEST['ctry'];
			if (is_numeric($country)) {
				$where['country'] = $country;
			}
		}
		import('ORG.Util.Page');
		$subQuery = $mo->field('brand')->where($where)->group('brand')->select(false);
		$count = $mo->table($subQuery.' a')->count();
		$Page = new Page($count,16);
		$page_bar = $Page->show();
		$Page->setConfig('theme','%upPage% %first% %linkPage% %downPage% %end%');
		$brands = $mo->field('brand,country')->where($where)->group('brand')->limit($Page->firstRow.','.$Page->listRows)->select();
		// dump($brands);exit;
		$len = count($brands);
		$brandIds = $brands[0]['brand'];
		for ($i=1; $i < $len; $i++) { 
			$brandIds .= ','.$brands[$i]['brand'];
		}
		// echo $brandIds;exit;
		$where = array();
		$where['status'] = '1';
		$where['is_del'] = '-1';
		$where['id'] = array('in',$brandIds);
		$all_brands = $mo->table('jiuku_winery')->field('id,fname,cname')->where($where)->order('id')->select();
		foreach ($all_brands as $key => $value) {
			$img = $mo->table('jiuku_winery_img')->field('filename')->where('winery_id='.$value['id'].' and status=1 and is_del="-1"')->find();
			$all_brands[$key]['brand_img'] = $img['filename'];
		}
		// dump($all_brands);
		$this->assign('brands',$all_brands);
		$this->assign('pageBar',$page_bar);
		$this->display();
	}
	public function bj() {
		$mo = D('MerchantBjlj');
		$res = $mo->field('brand')->group('brand')->select();
		$brands = array();
		foreach ($res as $key => $value) {
			$brand = $mo->field('a.id,a.cname,b.img')
			            ->table('jiuku_brand a')
			            ->join('jiuku_brand_img b on a.id=b.fid')
			            ->where('a.cname="'.$value['brand'].'"')
			            ->find();
			$brands[]=$brand;
		}
		$brands = array_filter($brands);
		$this->assign('brand',$brands);
		$this->display();
	}
	public function getwinery($id) {
		if (!is_numeric($id)) {
			header('location:'.C('TMPL_PARSE_STRING.__ROOT__').'/Brands');
			die();
		}
		$w = M();
		$brand = $w->table('jiuku_winery')->where('id='.$id)->find();
		// $merchants = $w->table('b2b_merchant_wine')->field('ym_id')->where('brand='.$id)->select();
		$company = $w->query("select * from ym_users_qy A where exists (select ym_id from b2b_merchant_wine B where B.brand={$id} and B.ym_id=A.id)");
		foreach ($company as $key => $value) {
			$pronum = $w->table('b2b_merchant_wine')->where('ym_id='.$value['id'])->count();
			$company[$key]['pronum'] = $pronum;
		}
		// echo $w->getlastsql();
		// dump($company);exit;
		// dump($brand);
		$this->assign('brand',$brand);
		$this->assign('company',$company);
		$this->display('brandinfo');
	}
	public function getjkbrand($name) {
		if (empty($name)) {
			header('location:'.C('TMPL_PARSE_STRING.__ROOT__').'/Brands/bj');
			die();
		}
		$w = M();
		$brand = $w->table('jiuku_brand')->where('cname="'.$name.'"')->find();
		// $merchants = $w->table('b2b_merchant_bjlj')->field('ym_id')->where('brand='.$name)->select();
		$company = $w->query("select * from ym_users_qy A where exists (select ym_id from b2b_merchant_bjlj B where B.brand='{$name}' and B.ym_id=A.id)");
		foreach ($company as $key => $value) {
			$pronum = $w->table('b2b_merchant_bjlj')->where('ym_id='.$value['id'])->count();
			$company[$key]['pronum'] = $pronum;
		}
		// echo $w->getlastsql();
		// dump($company);exit;
		// dump($brand);
		$this->assign('brand',$brand);
		$this->assign('company',$company);
		$this->display('bj_brandinfo');
	}
}