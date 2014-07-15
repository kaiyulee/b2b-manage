<?php
class IndexAction extends Action{
	public function _empty() {
		$this->index();
	}
	public function index() {
		$this->display();
	}
}