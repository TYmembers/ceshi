<?php
	//xch 2016.01.18
	//登录、登出
	namespace Admin\Controller;

	class SetupController extends CommonController{ 
		public function _initialize(){
			if(preg_match('/^((2,(.*){0,})|((.*){1,}(,2,)(.*){0,}))$/',$_SESSION['login']['root'])){

			}else{
			    $this->error("您无权访问");
			}
		}
		/**
		 * 充值设置
		*/
		
		public function setup(){
			$nav="setup";
			$setup=M('Setup');
			if(isset($_GET['dealer'])){
				$where['operator']=$_GET['dealer'];
			}else{
				$where['operator']=1;
			}
			
			$userlist=$setup->where($where)->find();
			
			$this->assign('list',$userlist);
			$this->assign('nav',$nav);
			$this->assign('where',$where);
			$this->display();
		}

		/**
		 * 充值设置
		*/
		
		public function save(){
			$setup=D('Setup');
			$operation=D('Operation');
			$operation->startTrans();
			$dealer=$_POST['dealer'];
			if(($dealer>3 || $dealer<1) && !is_numeric($dealer)){
				$this->error('对不起，您修改的运营商不存在');
			}

			$operators=C('OPERATOR_TYPE');
			$user=$operators[$dealer];

			$result=$setup->where("operator=".$dealer)->find();

			if(is_numeric($_POST['corpus'])){
				$list['corpus']=$_POST['corpus'];
			}else{
				$this->error('本金项填写有误');
			}

			if(is_numeric($_POST['rebate'])){
				$list['discount']=$_POST['rebate'];
			}else{
				$this->error('折扣填写有误');
			}

			if(is_numeric($_POST['price'])){
				$list['price']=$_POST['price'];
			}else{
				$this->error('售价填写有误');
			}

			if(is_numeric($_POST['gain'])){
				$list['profit']=$_POST['gain'];;
			}else{
				$this->error('利润填写有误');
			}

			if($result){
				$info=$setup->where('operator='.$dealer)->save($list);
				$data['operation']='修改运营商"'.$user.'"的充值设置';
			}else{
				$list['operator']=$dealer;
				$info=$setup->add($list);
				$data['operation']='添加运营商"'.$user.'"的充值设置';
			}

			$data['user']=$_SESSION['login']['name'];
			var_dump($_SESSION);
			$data['ip']=get_client_ip();
			$data['time']=$_SERVER['REQUEST_TIME'];

			$operinfo=$operation->add($data);

			if($info && $operinfo){
				$operation->commit();
				$this->redirect('setup?dealer='.$dealer);
			}else{
				$operation->rollback();
				if($result){
					echo "修改失败";
				}else{
					echo "添加失败";
				}
			}
		}
	}

?>