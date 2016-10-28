<?php
	namespace Admin\Controller;
	use Think\Controller;
	class UserController extends CommonController{
		public function _initialize(){
			//权限判断
			if(preg_match('/^1,(.*){0,}$/',$_SESSION['login']['root'])){

			}else{
			    $this->error("您无权访问");
			}
		}
		
		/**
	     * 用户查询
	     * @access public 
	     * @param  string uid 用户查询条件
	     * @param  string tel 手机查询条件
	     * @param  string orderid 订单查询条件
	     * @param  string starttime 时间查询条件起点
	     * @param  string overtime 时间查询条件终点
	    */
		public function user_management(){
			$nav='user';
			$where=array();
			$time=array();
			//检索条件的记录
			if(isset($_GET['tel']) && !empty(I('get.tel'))){
				if(preg_match('/^1(3[0-9]|4[57]|5[0-35-9]|7[01678]|8[0-9])\\d{8}$/',$_GET['tel'])){
	            	$where['tel']=I('get.tel');
					$map['tel']=I('get.tel');
		        }else{
		            $this->error("联系电话有误");
		    	}
			}else{
				$map['tel']='';
			}
			if(isset($_GET['uid']) && !empty(I('get.uid'))){
				$where['uid']=I('get.uid');
				$map['uid']=I('get.uid');
			}else{
				$map['uid']='';
			}
			if((isset($_GET['starttime']) && !empty(I('get.starttime'))) && (isset($_GET['overtime']) && !empty(I('get.overtime')))){
				$start=strtotime($_GET['starttime']);
				$over=strtotime($_GET['overtime']);
				$where['create_time']=array(array("EGT",$start),array("ELT",$over));
				$map['starttime']=I('get.starttime');
				$map['overtime']=I('get.overtime');
			}else{
				if(isset($_GET['starttime']) && !empty(I('get.starttime'))){
					$start=strtotime($_GET['starttime']);
					$where['create_time']=array("EGT",$start);
					$map['starttime']=I('get.starttime');
				}else{
					$map['starttime']='';
				}
				if(isset($_GET['overtime']) && !empty(I('get.overtime'))){
					$over=strtotime($_GET['overtime']);
					$where['create_time']=array("ELT",$over);
					$map['overtime']=I('get.overtime');
				}else{
					$map['overtime']='';
				}
			}
			

			$user=M('User');
			$total=$user->where($where)->where('genre=1')->count();
			$page=new \Think\Page($total,5);
			$userlist=$user->limit($page->firstRow.','.$page->listRows)->where($where)->where('genre=1')->select();
			if($userlist){
				$order=M('Orderinfo');
				foreach ($userlist as $key => $value) {
					$userlist[$key]['create_time']=date("Y-m-d H:i:s",$value['create_time']);
					$orderlist=$order->order('id desc')->where('uid='.$userlist[$key]['uid'])->field('uid,id,create_time')->find();
					if($orderlist){
						$userlist[$key]['last_time']=date("Y-m-d H:i:s",$orderlist['create_time']);
					}else{
						$userlist[$key]['last_time']="该账户从未消费";
					}
					
				}
			}
			$pageButton=$page->show();
			$this->assign('list',$userlist);
			$this->assign('pageButton',$pageButton);
			$this->assign('total',$total);
			$this->assign('nav',$nav);
			$this->assign('where',$map);
			$this->display();

		}

		/**
	     * 代理商查询
	     * @access public 
	     * @param  string uid 用户查询条件
	     * @param  string tel 手机查询条件
	     * @param  string orderid 订单查询条件
	     * @param  string starttime 时间查询条件起点
	     * @param  string overtime 时间查询条件终点
	    */
		public function agent_management(){
			$nav='user';
			$where=array();
			//检索条件的记录
			if(isset($_GET['tel']) && !empty(I('get.tel'))){
				if(preg_match('/^1(3[0-9]|4[57]|5[0-35-9]|7[01678]|8[0-9])\\d{8}$/',$_GET['tel'])){
	            	$where['user.tel']=I('get.tel');
					$map['tel']=I('get.tel');
		        }else{
		            $this->error("联系电话有误");
		    	}
			}else{
				$map['tel']='';
			}
			if(isset($_GET['uid']) && !empty(I('get.uid'))){
				$where['user.uid']=I('get.uid');
				$map['uid']=I('get.uid');
			}else{
				$map['uid']='';
			}

			if((isset($_GET['starttime']) && !empty(I('get.starttime'))) && (isset($_GET['overtime']) && !empty(I('get.overtime')))){
				$start=strtotime($_GET['starttime']);
				$over=strtotime($_GET['overtime']);
				$where['user.create_time']=array(array("EGT",$start),array("ELT",$over));
				$map['starttime']=I('get.starttime');
				$map['overtime']=I('get.overtime');
			}else{
				if(isset($_GET['starttime']) && !empty(I('get.starttime'))){
					$start=strtotime($_GET['starttime']);
					$where['user.create_time']=array("EGT",$start);
					$map['starttime']=I('get.starttime');
				}else{
					$map['starttime']='';
				}
				if(isset($_GET['overtime']) && !empty(I('get.overtime'))){
					$over=strtotime($_GET['overtime']);
					$where['user.create_time']=array("ELT",$over);
					$map['overtime']=I('get.overtime');
				}else{
					$map['overtime']='';
				}
			}
			

			$user=M('User');
			$total=$user->where($where)->where('user.genre=2')->count();
			$page=new \Think\Page($total,5);
			$userlist=$user->order('information.id desc')->limit($page->firstRow.','.$page->listRows)->join('information ON user.uid=information.uid')->where($where)->where('user.genre=2')->field('user.uid,user.id,user.pic,user.tel,user.state,user.create_time,information.company,information.work,information.license,information.balance,information.commission')->select();
			// var_dump($userlist);die();
			if($userlist){
				$information=M('Information');
				foreach ($userlist as $key => $value) {
					$userlist[$key]['create_time']=date("Y-m-d H:i:s",$value['create_time']);
					if($userlist[$key]['balance']==0){
						$userlist[$key]['balance']='zero';
					}

					if($userlist[$key]['commission']==0){
						$userlist[$key]['commission']='zero';
					}
				}
			}
			$pageButton=$page->show();
			$this->assign('list',$userlist);
			$this->assign('pageButton',$pageButton);
			$this->assign('total',$total);
			$this->assign('nav',$nav);
			if(isset($map)){
				$this->assign('where',$map);
			}
			$this->display();

		}


		/**
		 * 审核经销商
		 * @access public 
		 * @param int state 1-无状态 2-审核不通过 3-待审核 4-审核通过
		 * @param $result 处理结果
		*/
		public function update(){
			$map['uid']=$_POST['id'];
			
			$date['state']=$_POST['type'];

			$user=D('User');
			$operation=D('Operation');
			$users=$user->where($map)->find();

			if($users['genre']==1){
				$information=D('Information');
			}
			$operation->startTrans();

			$data['user']=$_SESSION['login']['name'];
			$data['ip']=get_client_ip();
			$data['time']=$_SERVER['REQUEST_TIME'];
			if($date['state']==2){
				$data['operation']='审核用户名为"'.$map['uid'].'"的经销商申请结果为不通过';
			}
			if($date['state']==4){
				if($users['genre']==1){
					$date['genre']=2;
					//普通用户升级经销商添加信息
					$list['uid']=$map['uid'];
					$list['company']='暂未输入';
					$list['level']=1;
					$addinfo=$information->add($list);
				}
				$data['operation']='审核用户名为"'.$map['uid'].'"的经销商申请结果为通过';
			}

			$result=$user->where($map)->save($date);
			$info=$operation->add($data);
			if($result && $info){
				//判断是否为普通用户通过经销商申请
				if($date['state']==4 && $users['genre']==1){
					if($addinfo){
						$operation->commit();
						$type=1;
					}else{
						$operation->rollback();
						$type=2;
					}
				}else{
					$operation->commit();
					$type=1;
				}
				
			}else{
				$operation->rollback();
				$type=2;
			}
			echo $type;
		}


		/**
		 * 确认管理员密码
		 * @access public 
		 * @param string user 账号
		 * @param string password 密码
		*/
		public function pass(){
			$where['user']=$_SESSION['login']['user'];
			$pass=I('post.pass');
			$where['password']=md5(md5(md5(md5($pass))));
			
			$admin=M('Admin');
			$result=$admin->where($where)->find();
			if($result){
				echo 1;
			}else{
				echo "管理员密码错误";
			}
		}


		/**
		 * 打款
		 * @access public 
		 * @param string user 账号
		 * @param string password 密码
		*/
		public function paying(){
			$where['uid']=I('post.uid');
			$map['price']=I('post.price');
			
			$information=D('Information');
			$operation=D('Operation');
			$operation->startTrans();

			$price=$information->where($where)->find();
			$list['balance']=$price['balance']+$map['price'];

			$data['user']=$_SESSION['login']['name'];
			$data['operation']='管理员'.$_SESSION['login']['name'].'给ID为"'.$where['uid'].'"用户打款'.$map['price'].'元';
			$data['ip']=get_client_ip();
			$data['time']=$_SERVER['REQUEST_TIME'];
			$result=$information->where($where)->save($list);
			$add=$operation->add($data);
			if($result && $add){
				$operation->commit();
				echo 1;
			}else{
				$operation->rollback();
				echo $information->getLastSql();
			}
		}


		/**
		 * 转换余额
		 * @access public 
		 * @param string uid 用户id
		 * @param int price 打款金额
		*/
		public function balances(){
			$where['uid']=I('post.uid');
			
			$information=D('Information');
			$operation=D('Operation');
			$operation->startTrans();

			$sel=$information->where($where)->find();

			if(!isset($sel)){
				echo "非法操作用户";
				die();
			}

			$map['balance']=$sel['balance']+$sel['commission'];
			$map['commission']=0;

			$result=$information->where($where)->save($map);

			$data['user']=$_SESSION['login']['name'];
			$data['operation']='管理员'.$_SESSION['login']['name'].'给ID为"'.$where['uid'].'"用户将返利的'.$sel['commission'].'元转为余额';
			$data['ip']=get_client_ip();
			$data['time']=$_SERVER['REQUEST_TIME'];

			$add=$operation->add($data);
			if($result && $add){
				$operation->commit();
				echo 1;
			}else{
				$operation->rollback();
				echo "转为余额失败";
			}
		}
	}

?>
