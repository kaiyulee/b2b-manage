<?php
class CompanyAction extends BaseAction {
	public function _empty(){
		$this->index();
	}
	public function index(){
		$this->display();
	}
}
