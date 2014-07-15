<?php
class CproductAction extends Action {
	public function plist($id=0){
		if (empty($id) || !is_numeric($id)) {
			header('location:'.C('TMPL_PARSE_STRING.__ROOT__'));
			EXIT;
		}
		$ymId = $id;
		$mo = M();
		$res = $mo->table('b2b_merchant_wine')->where('ym_id='.$ymId)->select();
		$aside = $mo->table('ym_users_qy A')->join('ym_users B on A.id=B.id')->where('A.id='.$ymId)->find();
		// dump($aside);
		// echo $mo->getlastsql();
		import('ORG.Util.Page');
		$count = count($res);
		$Page = new Page($count,16);

		$page_bar = $Page->show();
		$Page->setConfig('theme','%upPage% %first% %linkPage% %downPage% %end%');
		$res = $mo->table('b2b_merchant_wine')->where('ym_id='.$ymId)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($res as $key => $value) {
			$regionSet = explode(',',$value['region']);
			list($reg,,) = $regionSet;
			$region = $mo->table('jiuku_region')->field('fname,cname')->where('id='.$reg)->find();
			$res[$key]['reg_name'] = $region['cname'];
			$img = $mo->table('jiuku_winery_img')->field('filename')->where('winery_id='.$value['brand'].' and status=1 and is_del="-1"')->find();
			$res[$key]['brand_img'] = $img['filename'];
		}
		// dump($res[0]);
		$this->assign($aside);
		$this->assign('res',$res);
		$this->assign('pageBar',$page_bar);
		$this->display();
	}
}