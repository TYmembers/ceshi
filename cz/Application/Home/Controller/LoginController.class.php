<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {

    public function login(){
        //先判断电话号码的合法性
        if(preg_match('/^1(3[0-9]|4[57]|5[0-35-9]|7[01678]|8[0-9])\\d{8}$/',$_GET['tel'])){
            //确认号码合法后开始业务逻辑，打开事务回滚
            $user = D('User');
            $login = D('Login');
            $login->startTrans();

            //确认该号码是否有开通账号记录
            $map['uid']=I('get.tel');
            $map['tel']=I('get.tel');
            $sel=$user->where($map)->find();

            if($sel){
                //号码已注册账号，直接记录登录信息令其登录
                $data['user']=$map['uid'];
                $data['ip']=get_client_ip();
                $data['time']=time();
                $data['role']=1;
                $data['token']=create_token($sel['tel'],$sel['password']);
                $add=$login->add($data);
                if($add){
                    $login->commit();
                    $_SESSION['user']['user']=$map['uid'];
                    $_SESSION['user']['token']=$data['token'];
                    $return['code']=1;
                    $return['message']='登录成功';
                }else{
                    $login->rollback();
                    $return['code']=2;
                    $return['message']='登录失败';
                }
            }else{
                //号码未注册，自动注册，生成登录记录
                $map['creat_time']=time();
                $map['genre']=1;
                $result=$user->add($map);

                $data['user']=$map['uid'];
                $data['ip']=get_client_ip();
                $data['time']=time();
                $data['role']=1;
                $data['token']=create_token($map['tel'],'');
                $add=$login->add();
                if($result && $add){
                    $login->commit();
                    $return['code']=3;
                    $return['message']='注册账号成功，自动登录';
                }else{
                    $login->rollback();
                    $return['code']=4;
                    $return['message']='注册失败，请重试';
                }
            }
            
        }else{
            $return['code']=5;
            $return['message']='电话号码错误';
        }
        echo $this->AjaxReturn($return);
    }
}