<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Verify;
class LoginController extends Controller {
	/*
	 * 登录主页面
	 */
	function index() {
		$this->display();
	}
	/*
	 * 检测登录信息
	 */
	function check(){
		$verify=trim(I('post.verify'));
		import("Think.Verify");
		$imageob=new Verify();
		//var_dump($imageob->check($verify));exit;
		if($imageob->check(strtolower($verify))){
			$where['user']=trim(I('post.username'));
			$where['password']=md5(md5(md5(md5(I('post.password')))));

			$row=M('admin')->where('user="'.$where['user'].'" and password="'.$where['password'].'"')->find();
			// $row=M('admin')->where($where)->find();
			// echo M('admin')->getLastSql();
			// die();
			if($row){
				//将管理员信息存入session
				$_SESSION['login']=$row;
				$this->success("登录成功",U("Order/user_order"));
			}else{
				$this->error("用户名或密码错误，请重登",U("Login/index"));
			}
		}else{
			$this->error("验证码错误",U("Login/index"));
		}
	}
	/*
	 * 生成登陆页面的验证码
	 *
	 */
	function vcode(){
		import("Think.Verify");
		$imageob=new Verify();
		$imageob->fontSize=20;
		$imageob->useNoise=false;
		$imageob->length=4;
		$imageob->entry();
	}
	/*
	 * 退出登录
	 */
	function logout(){
		unset($_SESSION['login']);
		$this->redirect("Login/index");
	}
}