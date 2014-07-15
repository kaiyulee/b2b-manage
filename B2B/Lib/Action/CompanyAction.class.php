<?php
class CompanyAction extends BaseAction {
	public function _empty(){
		$this->index();
	}
	public function index(){
		$mo = D();
		import('ORG.Util.Page');
		$subQuery = $mo->field('A.*,B.qy_name,B.qy_support')
		->table('b2b_merchant_wine A')
		->join('ym_users_qy B on A.ym_id=B.id')
		->group('A.ym_id')
		->select(false);
		$count = $mo->table($subQuery.' a')->count(); 
		$Page = new Page($count,16);
		$page_bar = $Page->show();
		$Page->setConfig('theme','%upPage% %first% %linkPage% %downPage% %end%');
		// $company = $mo->query("select * from b2b_merchant_wine A where exists (select ym_id from ym_users_qy B where A.ym_id=B.id) group by A.ym_id");
		$company = $mo->field('A.*,B.qy_name,B.qy_support')
		->table('b2b_merchant_wine A')
		->join('ym_users_qy B on A.ym_id=B.id')
		->group('A.ym_id')
		->limit($Page->firstRow.','.$Page->listRows)
		->select();
		foreach ($company as $key => $value) {
			$pronum = $mo->table('b2b_merchant_wine')->where('ym_id='.$value['ym_id'])->count();
			$company[$key]['pronum'] = $pronum;
		}
		$this->assign('company',$company);
		$this->assign('pageBar',$page_bar);
		$this->display();
	}
}
