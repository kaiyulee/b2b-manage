<?php
class MerchantBjljModel extends Model{
	protected $_map = array(
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
}