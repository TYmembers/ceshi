<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    function _initialize(){
        $where['user']=I('get.uid');
        $where['time']=array('EGT',time()-7200);
        $where['close']=1;
        $where['token']=I('get.token');
        $login=M('Login');
        $result=$login->where($where)->find();
        if(!$result){
        	$return['code']=2;
        	$return['message']='登录过期';
        	echo $this->AjaxReturn($return,'JSON');
        }
    }     
}