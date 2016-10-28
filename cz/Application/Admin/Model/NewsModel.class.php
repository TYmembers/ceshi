<?php
namespace Admin\Model;
use Think\Model;
class NewsModel extends Model{
	public function getAll(){
		//$class=$goos->select();
		$userlist = $this->field("id,time,title,num")->select();

		//处理数据
		foreach($userlist as $key=>$val){
			$userlist[$key]['time']=date("Y-m-d H:i:s",$userlist[$key]['time']);
			
		}


		return $userlist;

	}
}



?>