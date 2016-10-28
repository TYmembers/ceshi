<?php
	function check_verify($code, $id = ''){    
		$verify = new \Think\Verify();   
	 	return $verify->check($code, $id);
	 }
	function dowith_sql($str){
	    $refuse_str="and|or|select|update|from|where|order|by|*|delete|'|insert|into|values|create|table|database";
	    $arr=explode("|",$refuse_str);
	    for($i=0;$i<count($arr);$i++)
	    {
	        //$replace="[".$arr[$i]."]";
	        //$str=str_replace($arr[$i],$replace,$str);
		if(strpos($str,$arr[$i])!=false)
		{
			echo "<Script Language=JavaScript>alert('防注入系统提示你：请不要在参数中包含非法字符。');</Script>";
			echo "非法操作！系统已经给你做了如下记录：<br>";
			echo "操作IP：".$_SERVER["REMOTE_ADDR"]."<br>";
			echo "操作时间：".$_SERVER["REQUEST_TIME"]."<br>";
			echo "操作页面：".$_SERVER["SCRIPT_NAME"]."<br>";
			echo "提交数据：".$str;
			exit();
		}
	    }
	    return $str;
	}
	foreach ($_GET as $key=>$value)
	{
	    $_GET[$key]=dowith_sql($value);
	    
	}
	foreach ($_POST as $key=>$value)
	{
	    $_POST[$key]=dowith_sql($value);
	    
	}



	//自定义生成TOKEN的方法
	function create_token($uid,$pass){
		$time=time();
		$str=md5($uid).md5($pass).md5($time);
		$str=md5(sha1($str));
		$str=md5(base64_encode($str));
		return $str;
	}
