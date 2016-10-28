<?php
	//xch 2016.01.18
	//登录、登出
	namespace Admin\Controller;
	use Think\Controller;

	class StatisticsController extends CommonController{ 
		public function _initialize(){
			if(preg_match('/^((4,(.*){0,})|((.*){1,}(,4,)(.*){0,}))$/',$_SESSION['login']['root'])){

			}else{
				// if(preg_match('/^(.*){1,}(,4,)(.*){0,}$/',$_SESSION['login']['root'])){
				// 	$this->error("what");
				// }
			    // $this->error("您无权访问");
			    var_dump($_SESSION);
			    die();
			}
		}
		/**
		 * 数据统计
		 * @access public
		 * @param int usernum 用户总数
		 * @param int dealernum 经销商总数
		 * @param int dealerpay 经销商充值金额
		 * @param int pass 成功充值数
		 * @param int over 失败充值数
		 * @param int poundage 手续费
		 * @param int price 销售总额
		 * @param int profit 纯利润
		*/
		
		public function statistics(){
			$nav="statistics";
			$where=array();
			//判断走的是日期区间还是时间区间
			if((isset($_POST['starttime']) && !empty($_POST['overtime'])) || (isset($_POST['overtime']) && !empty($_POST['overtime']))){
				//日期区间
				if((isset($_POST['starttime']) && !empty(I('post.starttime'))) && (isset($_POST['overtime']) && !empty(I('post.overtime')))){
					$start=strtotime($_POST['starttime']);
					$over=strtotime($_POST['overtime']);
					$where['create_time']=array(array("EGT",$start),array("ELT",$over));
					$map['starttime']=I('post.starttime');
					$map['overtime']=I('post.overtime');
				}else{
					if(isset($_POST['starttime']) && !empty(I('post.starttime'))){
						$start=strtotime($_POST['starttime']);
						$where['create_time']=array("EGT",$start);
						$map['starttime']=I('post.starttime');
					}else{
						$map['starttime']='';
					}
					if(isset($_POST['overtime']) && !empty(I('post.overtime'))){
						$over=strtotime($_POST['overtime']);
						$where['create_time']=array("ELT",$over);
						$map['overtime']=I('post.overtime');
					}else{
						$map['overtime']='';
					}
				}
			}else{
				//时间区间
				if(isset($_POST['operation']) &&!empty($_POST['operation'])){
					$operation=I('post.operation');

					$ymd=date("Y-m-d",$_SERVER['REQUEST_TIME']);
					if($operation==1){
						$time=strtotime($ymd);
						$where['create_time']=array('EGT',$time);
						$map['operation']=1;
					}elseif($operation==2){
						$time=strtotime($ymd);
						$start=strtotime('-1 day',$time);
						$over=strtotime($time);
						$where['create_time']=array(array('EGT',$start),array('ELT',$over));
						$map['operation']=2;
					}elseif($operation==3){
						$time=strtotime($ymd);
						$start=strtotime('-3 day',$time);
						$where['create_time']=array('EGT',$start);
						$map['operation']=3;
					}elseif($operation==4){
						$time=strtotime($ymd);
						$start=strtotime('-1 week',$time);
						$where['create_time']=array('EGT',$start);
						$map['operation']=4;
					}elseif($operation==5){
						$time=strtotime($ymd);
						$start=strtotime('-1 month',$time);
						$where['create_time']=array('EGT',$start);
						$map['operation']=5;
					}elseif($operation==6){
						$time=strtotime($ymd);
						$start=strtotime('-3 month',$time);
						$where['create_time']=array('EGT',$start);
						$map['operation']=6;
					}elseif($operation==7){
						$time=strtotime($ymd);
						$start=strtotime('-6 month',$time);
						$where['create_time']=array('EGT',$start);
						$map['operation']=7;
					}elseif($operation==8){
						$time=strtotime($ymd);
						$start=strtotime('-1 year',$time);
						$where['create_time']=array('EGT',$start);
						$map['operation']=8;
					}elseif($operation==9){
						$time=strtotime($ymd);
						$start=strtotime('-2 year',$time);
						$where['create_time']=array('EGT',$start);
						$map['operation']=9;
					}else{
						$time=strtotime($ymd);
						$start=strtotime('-3 year',$time);
						$where['create_time']=array('EGT',$start);
						$map['operation']=10;
					}
				}else{
					$time=strtotime($ymd);
					$where['create_time']=array('EGT',$time);
					$map['operation']=1;
				}
			}
			

			$user=M('User');
			$order=M('Orderinfo');
			$usernum=$user->where($where)->count();
			$dealernum=$user->where($where)->where('genre=1')->count();

			$orderlist=$order->where($where)->where('usertype=2 and state=2')->select();
			$dealerpay=0;
			if($orderlist){
				foreach ($orderlist as $key => $value) {
					$dealerpay=$dealerpay+$value['payment'];
				}
			}
			
			$pass=$order->where($where)->where('state=2')->count();
			$over=$order->where($where)->where('state=3')->count();

			$poundage=0;

			$pricelist=$order->where($where)->where('state=2')->select();
			$price=0;
			if($pricelist){
				foreach ($pricelist as $key => $value) {
					$price=$price+$value['payment'];
				}
			}

			$profit=$price-$poundage;

			$list=array();

			$list['usernum']=$usernum;
			$list['dealernum']=$dealernum;
			$list['dealerpay']=$dealerpay;
			$list['pass']=$pass;
			$list['over']=$over;
			$list['poundage']=$poundage;
			$list['price']=$price;
			$list['profit']=$profit;

			$this->assign('nav',$nav);
			$this->assign('where',$where);
			$this->assign('map',$map);
			$this->assign('list',$list);
			$this->display();
		}
	}

?>