<?php
class ProandservAction extends BaseAction {
    public function index(){
    	$this->wine();
    }
    //葡萄酒
    public function wine()
    {
    	$this->assign('fl','wine');
		$this->display('wine');
    }

    //白酒
    public function whitewine()
    {
    	$this->assign('fl','whitewine');
		$this->display();
    }
	//烈酒
    public function liquor()
    {
    	$this->assign('fl','liquor');
    	$this->display();
    }

    //酒柜酒具
    public function tools()
    {
    	$this->assign('fl','tools');
    	$this->display();
    }

    //商对商服务
    public function business()
    {   	
    	$this->assign('fl','business');
		$this->display();
    }


    //商品详情
    public function information()
    {   
    	$where['id']  = !empty(intval($_GET['id'])) ? intval($_GET['id']) :1;
    	$info = M()->table('b2b_merchant_wine')->where($where)->select();
    	$image =  M()->table('b2b_merchant_wine_img')->where('b2b_wine_id='.$where['id'])->field('img,img_cat')->order('img_cat')->select();
		$qy_info = M()->query("select * from ym_users_qy A where exists (select ym_id from b2b_merchant_wine B where B.caname_id=".$info[0]['caname_id']." and B.ym_id=A.id)");
		foreach ($qy_info as $key => $value) {
			$pronum = M()->table('b2b_merchant_wine')->where('ym_id='.$value['id'])->count();
			$information = M()->table('b2b_merchant_wine')->where('caname_id='.$info[0]['caname_id'])->field('id,fname,cname,year,wholesale_price')->select();
			$qy_info[$key]['pronum'] = $pronum;
			$qy_info[$key]['information'] = $information[0];
		}

    	$other_info = $this->other_info($info[0]['brand']);
    	$this->assign('info',$info[0]);
    	$this->assign('image',$image);
    	$this->assign('qy_info',$qy_info);
    	$this->assign('other_info',$other_info);
    	$this->assign('fl','information');
		$this->display();
    }

    //js获取红酒
    public function get_wine()
    {
        //$where['brand'] = 4717;
        // $where['is_del'] = 1;
        // $where['sort_name']  = 1; 
		if(!empty($_POST['con1']) || $_POST['con1']=='不限'){$where['country'] = $_POST['con1'];}
    	$page   = !empty(intval($_POST['p'])) ? intval($_POST['p']) :1;
    	$length = 12; 
        $offset = ($page-1)*$length;
    	$count  = M()->table('b2b_merchant_wine')->where($where)->count('id');
    	$result['count'] = $count;
    	$result['page'] = $this->pagers($count,$length,$page);
  		$result['info'] = $this->wine_infos($where,$offset,$length);
    	echo json_encode($result);
    }


	//js获取酒具
    public function get_tools()
    {
        if(!empty($_POST['con1']) || $_POST['con1']=='不限'){$where['tp'] = $_POST['con1'];} 
    	$page   = !empty(intval($_POST['p'])) ? intval($_POST['p']) :1;
    	$length = 6; 
        $offset = ($page-1)*$length;
    	$count  = M()->table('b2b_merchant_tool')->where($where)->count('id');
    	$result['count'] = $count;
    	$result['page'] = $this->pagers($count,$length,$page);
  		$result['info'] = $this->tools_infos($where,$offset,$length);
    	echo json_encode($result); 	
    }


    //js获取物流/报关/仓储
    public function get_business()
    {
    	if(!empty($_POST['con1']) || $_POST['con1']=='不限'){$where['tp'] = $_POST['con1'];} 
    	$page   = !empty(intval($_POST['p'])) ? intval($_POST['p']) :1;
    	$length = 6; 
        $offset = ($page-1)*$length;
    	$count  = M()->table('b2b_merchant_service')->where($where)->count('id');
    	$result['count'] = $count;
    	$result['page'] = $this->pagers($count,$length,$page);
  		$result['info'] = $this->business_infos($where,$offset,$length);
    	echo json_encode($result);
    }

    //红酒数据
	public function wine_infos($where,$offset,$length)
	{
		$info = M()->table('b2b_merchant_wine')->where($where)->limit($offset,$length)->select();
		foreach ($info as $k => $v) {
			if(($k+1)%4==0)
			{
				$result .= '<li class="list_r_li">';
			}else{
				$result .= '<li>';
			}
			$result .= '<div class="z_main_r_list_div">
							<p class="pic">
								<span class="edge"></span><span class="container"></span>
								<a href="/Proandserv/information/id/'.$v['id'].'.html">
								<img src="http://issue.b2b.wine.cn/Image/images/0001_a.jpg"></a>
							</p>
							<h3>'.$v['cname'].'</h3>
							<p class="e_name">'.$v['fname'].'</p>
							<p class="comeFrom">产地：<span>'.$v['country'].'</span></p>
						</div>
					</li>'; 

		}
		return $result;
	}

    //酒具数据
	public function tools_infos($where,$offset,$length)
	{
		$info = M()->table('b2b_merchant_tool')->where($where)->limit($offset,$length)->select();
		foreach ($info as $k => $v) {
			$result .= '<li>
							<img src="http://issue.b2b.wine.cn/Image/images/bingo.gif" alt=""/>
							<div class="div_528div">
								<h4>'.$v['nm'].'</h4>
								<p class="top_528p clearfix">
						    		<span>酒具类型：<em>'.$v['tp'].'</em></span>
									<span>产地：<em>无无无无无</em></span>
									<span>市场零售价：<em>￥无无无无无</em></span>
								</p>
								<p class="bottom_528p">'.$v['ym_id'].'</p>
				  			</div>
			 			</li>'; 

		}
		return $result;
	}


    //物流/报关/仓储数据
	public function business_infos($where,$offset,$length)
	{
		$info = M()->table('b2b_merchant_service')->where($where)->limit($offset,$length)->select();
		foreach ($info as $k => $v) {
			$result .= '<li>
							<img src="http://issue.b2b.wine.cn/Image/images/bingo.gif" alt=""/>
							<div class="div_528div">
								<h4>'.$v['nm'].'</h4>
								<p class="top_528p clearfix">
						    		<span>服务类型：<em>'.$v['tp'].'</em></span>
									<span>产地：<em>无无无无无</em></span>
									<span>市场零售价：<em>￥无无无无无</em></span>
								</p>
								<p class="bottom_528p">'.$v['ym_id'].'</p>
				  			</div>
			 			</li>';  
		}
		return $result;
	}


	//该品牌其他酒款

	public function other_info($brand)
	{
		$info = M()->table('b2b_merchant_wine w')->join('b2b_merchant_wine_img i on w.id = i.b2b_wine_id')->where('w.brand='.$brand)->limit(8)->field('w.id,w.cname,i.img')->select();
		return $info;
	}
	//ajax 分页
	public function pagers($count,$length,$page)
	{

	    $page_count = ($count > 0) ? intval(ceil($count / $length)) : 1;

		if($page == 1)
		{
			$pager   .= '<a href="javascript:void(0)">&lt;上一页</a>';
		}else{
			$pager   .= '<a href="javascript:goPage('.($page-1).')">&lt;上一页</a>';
		}
		
		
		for ($i=1;$i<=$page_count;$i++)
		{
			if($page == $i)
			{
				$pager .= '<span class="current">'.$i.'</span>';
			}else{
				$pager .= '<a href="javascript:goPage('.$i.')">'.$i.'</a>';
			}
		}

		if($page == $page_count)
		{
			//$pager['page_next'] = $pager['page_count'];
			$pager   .= '<a href="javascript:void(0)">下一页&gt;</a>';
		}else{
			$pager	 .= '<a href="javascript:goPage('.($page+1).')">下一页&gt;</a>';
		}
		return $pager;
	}







}
