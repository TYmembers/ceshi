<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller{
	function _initialize(){
		if(!isset($_SESSION['login'])){
			$this->error("请先登录！" ,U('Login/index'));
			exit;
		}
	}
}