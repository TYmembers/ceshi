<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    /**
     * 头像上传
     * @access public 
     * @param array where 条件数组
     * @param string uid 用户id
     * @param string tel 电话号码
     * @param array data 修改数据
     * @param string pic 原图 spic 小图
    */
    public function head(){
        $admin=M('User');
        $where['uid']=I('post.tel');
        //获取图片类型
        $type=substr($_FILES['pic']['type'], 5);

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     0 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath  =      'Img/'; // 设置附件上传目录
        // 上传文件 
        $info   =   $upload->uploadOne($_FILES['pic']);
        if(!$info) {// 上传错误提示错误信息        
                $this->error($upload->getError());    
        }else{
        // 上传成功 获取上传文件信息
            $data['pic'] = $info['savepath'].$info['savename'];

            $url = str_replace('\\','/',realpath(dirname(__FILE__). '/../../../'));
            
            $data['pic']=$url.'/Uploads/'.$data['pic'];
            $data['spic'] = $url.'/Uploads/'.$info['savepath'].'s_'.$info['savename'];

            $image = new \Think\Image();
            $image->open($data['pic']);
            // 生成一个缩放后填充大小150*150的缩略图并取代原图
            $image->thumb(100, 100,\Think\Image::IMAGE_THUMB_FILLED)->save($data['spic']);

            //判断图片类型是否是jpeg
            $len=-(28+strlen($type));
            $data['pic'] = substr($data['pic'],$len);
            $data['spic'] = substr($data['spic'],$len-2);
        }
        $result=$admin->where($where)->save($data);
        if($result){
            $return['state']=1;
            $return['message']='头像更换成功';
            $result['sql']=$admin->getLastSql();
        }else{
            $return['state']=2;
            $return['message']='头像更换失败';
        }
        echo json_encode($return);
    }
}