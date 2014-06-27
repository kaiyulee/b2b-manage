<?php
class MerchantWineModel extends Model{
	protected $_map = array(
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
}