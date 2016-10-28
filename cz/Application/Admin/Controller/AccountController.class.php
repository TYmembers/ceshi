<?php
	//xch 2016.01.18
	//管理员模块 管理员列表、添加、删除、修改密码、查看登录记录、冻结及恢复账号使用
	namespace Admin\Controller;
	class AccountController extends CommonController{ 
		//权限判断
		public function _initialize(){
			if(preg_match('/^((3,(.*){0,})|((.*){1,}(,3,)(.*){0,}))$/',$_SESSION['login']['root'])){

			}else{
			    $this->error("您无权访问");
			}
		}
		/**
		 * 管理员列表
		 * @access public
		 * @param array date 条件
		 * @param int level 等级
		 * @return array adminlist 管理员数据
		 * @return array pageButton 分页信息
		 * @return int total 信息数量
		*/
		public function account_management(){
			$nav="admin";
			$admin=M('Admin');
			//选择普通管理员等级
			$date['level']=array('GT',$_SESSION['login']['level']);
			//分页
			$total=$admin->where($date)->count();
			$page=new \Think\Page($total,10);
			//得到数据
			$adminlist=$admin->where($date)->order("id desc")->limit($page->firstRow.','.$page->listRows)->select();
			// if($adminlist){
			// 	foreach ($adminlist as $key => $value) {
			// 		if($adminlist[$key]['root']==''){
			// 			$adminlist[$key]['root']="无任何权限";
			// 		}else{
			// 			$adminlist[$key]['root']=trim($value['root'],',');
			// 		}
			// 	}
			// }
			//按钮
			$pageButton=$page->show();
			$this->assign('list',$adminlist);
			$this->assign('pageButton',$pageButton);
			$this->assign('total',$total);
			$this->assign('nav',$nav);
			$this->display();
		}

		/**
		 * 添加管理员
		 * @access public 
		 * @param $x $y 用两种编码方式计算出输入的长度，用来判断是否汉字
		 * @param string user 账号
		 * @param string password 密码
		 * @param string name 操作员
		 * @param string role 角色
		*/
		public function addadmin(){
			$user=I('post.user');
			$role=I('post.role');
			$name=I('post.name');
			$password=I('post.password');
			//判断基本信息是否输入
			if(empty($user) || empty($role) || empty($name) || empty($password)){
				echo "信息填写不完全";
				die();
			}

			$date['user']=$user;

			//判断用户名是否汉字
			$x = mb_strlen($user,'gb2312');
			$y = mb_strlen($user,'utf8');
			if($x != $y){
				echo "账号不能为中文";
				die();
			}

			$admin=D('Admin');
			$operation=D('Operation');
			$operation->startTrans();
			$result=$admin->where($date)->find();
			//判断用户名是否已存在，不予添加
			if($result){
				$operation->rollback();
				echo "用户名已存在";
				die();
			}

			$date['password']=md5(md5(md5(md5($password))));
			$date['pass']=md5(md5(md5(md5($password))));
			$date['level']=$_SESSION['login']['level']+1;
			$date['name']=$name;
			$date['role']=$role;
			
			$add=$admin->add($date);

			$map['user']=$_SESSION['login']['name'];
			$map['operation']='添加管理员'.$date['name'];
			$map['ip']=get_client_ip();
			$map['time']=$_SERVER['REQUEST_TIME'];

			$info=$operation->add($map);

			if($add && $info){
				$operation->commit();
				echo 1;
			}else{
				$operation->rollback();
				echo "管理员添加失败！";
			}
		}

		/**
		 * 添加代理商或用户
		 * @access public 
		 * @param $x $y 用两种编码方式计算出输入的长度，用来判断是否汉字
		 * @param string tel 电话
		 * @param string pass 密码
		 * @param string company 公司名称
		 * @param string user 用户名称
		 * @param string genre 用户角色 1-用户 2-代理商
		 * @param string pass 得到的密码
		 * @param int create_time 创建时间
		*/
		public function addagent(){
			$genre=$_POST['genre'];
			if(empty($_POST['tel']) || empty($_POST['pass']) || empty($_POST['user'])){
				echo "信息填写不完全1";
				die();
			}
			
			//判断角色类型，若为经销商，则判断公司名称是否填写，实例化company表，并开启事物
			if($genre==2){
				if(empty($_POST['company'])){
					echo "信息填写不完全2";
					die();
				}
				//后台添加的经销商，审核经销商直接通过
				$date['state']=4;
				$information=D('Information');
				//写入操作表的操作内容为经销商
				$data['operation']='添加经销商'.$_POST['user'];
			}else{
				//写入操作表的操作内容为用户
				$data['operation']='添加普通用户'.$_POST['user'];
			}

			//开启事务回滚
			$operation=D('Operation');
			$user=D('User');
			$user->startTrans();

			if(preg_match('/^1(3[0-9]|4[57]|5[0-35-9]|7[01678]|8[0-9])\\d{8}$/',$_POST['tel'])){
	            $date['tel']=I('post.tel');
	        }else{
	            echo "联系电话有误";
	            die();
	    	}

	    	$date['uid']=I('post.user');

			//判断用户名是否汉字
			$x = mb_strlen($date['uid'],'gb2312');
			$y = mb_strlen($date['uid'],'utf8');
			if($x != $y){
				echo "账号不能为中文";
				die();
			}

			$result=$user->where('uid="'.$date['uid'].'" or tel="'.$date['tel'].'"')->find();
			//判断用户名是否已存在
			if($result){
				echo "账号或手机号已存在，不能重复添加";
				die();
			}

			$date['password']=md5(md5(md5(md5(I('post.pass')))));
			$date['genre']=$genre;
			$date['create_time']=$_SERVER['REQUEST_TIME'];
			$add=$user->add($date);

			//操作表的数据录入的其他基本信息
			$data['user']=$_SESSION['login']['name'];
			$data['ip']=get_client_ip();
			$data['time']=$_SERVER['REQUEST_TIME'];
			$oper=$operation->add($data);

			//处理用户表后，判断添加的用户类型，若为经销商（代理商）则执行信息表的添加数据
			if($genre==2){
				$map['uid']=$date['uid'];
				$map['level']=1;
				$map['company']=$_POST['company'];
				$info=$information->add($map);

				if($info && $add && $oper){
					$user->commit();
					echo 1;
				}else{
					$user->rollback();
					echo "添加经销商失败！";
				}
			}else{
				if($add && $oper){
					$user->commit();
					echo 1;
				}else{
					$user->rollback();
					echo "添加普通用户失败！";
				}
			}

			
		}

		/**
		 * 删除管理员
		 * @access public 
		 * @param int id 管理员id
		 * @param string user 管理员名称
		 * @param string operation 操作内容
		 * @param string ip 操作时的ip地址
		 * @param int time 操作的时间戳
		*/
		public function del(){
			$id=$_POST['id'];
			$admin = D('Admin');
			$operation=D('Operation');
			$operation->startTrans();

			$list=$admin->where('id='.$id)->find();

			$result=$admin->delete($id);

			$data['user']=$_SESSION['login']['name'];
			$data['operation']='删除管理员'.$list['name'];
			$data['ip']=get_client_ip();
			$data['time']=$_SERVER['REQUEST_TIME'];

			$info=$operation->add($data);
			if($info && $result){
				$operation->commit();
				echo 1;
			}else{
				$operation->rollback();
				echo '删除管理员失败！';
			}
			
		}

		/**
		 * 修改管理员权限
		 * @access public 
		 * @param array where 条件数组
		 * @param int id 要修改的管理员id
		 * @param string root 权限数据
		 * @param array data 操作数据表写入的数据
		 * @param string user 管理员名称
		 * @param string operation 操作内容
		 * @param string ip 操作时的ip地址
		 * @param int time 操作的时间戳
		*/
		public function root(){
			//处理传入的数据，将数组处理成字符串
			// $root=implode(',', $_POST['root']);
			$map['root']=$_POST['root'].',';
			//判断权限是否被修改为无权限
			if(empty($map['root'])){
				echo '管理员的权限为空';
				die();
			}

			$admin=D('Admin');
			$operation=D('Operation');
			$operation->startTrans();

			$where['user']=$_POST['user'];

			$user=$admin->where($where)->field('user,name')->find();

			$data['user']=$_SESSION['login']['name'];
			$data['operation']='修改管理员"'.$user['name'].'"的权限为'.$map['root'];
			$data['ip']=get_client_ip();
			$data['time']=$_SERVER['REQUEST_TIME'];
			
			$result=$admin->where($where)->save($map);
			$info=$operation->add($data);
			if($result && $info){
				$operation->commit();
				echo 1;
			}else{
				$operation->rollback();
				echo 2;
			}
		}

		/**
		 * 修改管理员密码
		 * @access public 
		 * @param $map 条件数组 id 要修改的管理员id
		 * @param $date 数据数组 password 新密码
		*/
		public function update(){
			$oldpass=I('post.oldpass');
			$newpass=I('post.newpass');
			$type=I('post.type');
			if(empty($oldpass) || empty($newpass)){
				echo "您填写的旧密码或新密码为空";
				die();
			}
			$admin=D('Admin');
			$map['id']=I('post.id');
			if($type==1){
				$map['password']=md5(md5(md5(md5($oldpass))));
			}elseif($type=2){
				$map['pass']=md5(md5(md5(md5($oldpass))));
			}
			
			$sel=$admin->where($map)->find();
			if(!$sel){
				echo "您的旧密码填写错误";
				die();
			}
			
			$operation=D('Operation');
			$operation->startTrans();

			$data['user']=$_SESSION['login']['name'];
			if($type==1){
				$data['operation']='管理员修改了自己的登录密码';
			}elseif($type=2){
				$data['operation']='管理员修改了自己的支付密码';
			}
			
			$data['ip']=get_client_ip();
			$data['time']=$_SERVER['REQUEST_TIME'];

			if($type==1){
				$date['password']=md5(md5(md5(md5($newpass))));
			}elseif($type=2){
				$date['pass']=md5(md5(md5(md5($newpass))));
			}
			$result=$admin->where($map)->save($date);
			$finally=$operation->add($data);
			if($result && $finally){
				$operation->commit();
				echo 1;
			}else{
				$operation->rollback();
				echo "修改失败";
			}
		}

		/**
		 * 查看管理员登录记录
		 * @access public 
		 * @param $map 条件数组 id 要查看的管理员id
		*/
		public function adminlogin(){
			$where=array();

			isset($_GET['name'])?$where['name']=@$_GET['name']:'';

			$operation=M('Operation');
			$total=$operation->where($where)->count();
			$page=new \Think\Page($total,10);
			//得到数据
			$list=$operation->where($where)->order("id desc")->limit($page->firstRow.','.$page->listRows)->select();

			if($list){
				foreach ($list as $key => $value) {
					$list[$key]['time']=date("Y-m-d H:i:s",$list[$key]['time']);
				}
			}

			//按钮
			$pageButton=$page->show();
			$this->assign('list',$list);			
			$this->assign('pageButton',$pageButton);
			$this->assign('total',$total);
			$this->display();
		}


		/**
		 * 管理员权限
		 * @access public
		 * @param array date 条件
		 * @return string root 信息数量
		*/
		public function user_root(){
			$admin=M('Admin');
			$date['user']=$_POST['user'];
			$adminlist=$admin->where($date)->find();
			if($adminlist){
				$echo['code']=1;
				$echo['root']=trim($adminlist['root'],',');
				$echo['message']='查询成功';
			}else{
				$echo['code']=2;
				$echo['message']='数据不存在';
			}
			echo $this->AjaxReturn($echo);
		}
	}

?>