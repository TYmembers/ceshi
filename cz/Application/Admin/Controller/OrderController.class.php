<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
class OrderController extends CommonController {
	/*
	 * 用户订单页
	 */
	function user_order() {
	$wherelist=array();
		if(!empty($_GET['tel'])){
			$wherelist[]="tel = ".trim(I('get.tel'));
		}
		if(!empty($_GET['orderid'])){
			$wherelist[]="orderid = ".trim(I('get.orderid'));
		}
		if(!empty($_GET['uid'])){
			$wherelist[]="uid = ".trim(I('get.uid'));
		}
		if(!empty($_GET['start']) && !empty($_GET['close'])){
			$start=strtotime(I('get.start'));
			$close=strtotime(I('get.close'));
			$wherelist[]="create_time >= '{$start}' and  create_time <= '{$close}'";
		}
		$where='';
		if(count($wherelist) > 0){
			$where=join(' and ', $wherelist);
		}
 		//实现分页
		$arr=M('orderinfo')->field("count(*) as num")->where($where)->find();
		$num=$arr['num'];
		//每页显示页数
		$pageSize=3;
		import('Think.Page');
		$Page=new Page($num,$pageSize,$_GET);		
		$first=$Page->firstRow;
		$data=M('orderinfo')->where($where)->limit($first,$pageSize)->select();
		// 设置分页显示
		$Page -> setConfig('header','共%TOTAL_ROW%条');
		$Page -> setConfig('first','首页');
		$Page -> setConfig('last','共%TOTAL_PAGE%页');
		$Page -> setConfig('prev','上一页');
		$Page -> setConfig('next','下一页');
		$Page -> setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
		$pagestr=$Page->show();
		//echo $pagestr;exit;
		$this->assign("nav",ACTION_NAME);
		$this->assign("pagestr",$pagestr);
		$this->assign("data",$data);
		$this->assign("get",$_GET); 
		$this->display();
	}

	
	/*
	 * 创建新窗口 导出用户订单excel
	 */
	function user_output(){
		//导出订单excel
		$start=strtotime($_GET['start']);
		$close=strtotime($_GET['close']);
		if($start >= $close || $start==null || $close==null){
			$this->error("日期输入有误!请重输",$_SERVER['HTTP_REFERE']);
			exit;
		}
		$data=M('orderinfo')->where("create_time >= '{$start}' and  create_time <= '{$close}'")->select();
		vendor('PHPExcel');
		$objPHPExcel=new \PHPExcel();
		//var_dump($objPHPExcel);exit;
		$objSheet=$objPHPExcel->getActiveSheet();
		$objSheet->setTitle('普通用户账单');
		$objSheet->setCellValue("A1","创建时间")->setCellValue("B1","订单号")->setCellValue("C1","用户ID")->setCellValue("D1","手机号")->setCellValue("E1","运营商")->setCellValue("F1","充值金额")->setCellValue("G1","类型")->setCellValue("H1","支付金额")->setCellValue("I1","支付方式");
		$j=2;
		$objSheet->getColumnDimension('A')->setAutoSize(true);
		$objSheet->getColumnDimension('B')->setAutoSize(true);
		$objSheet->getColumnDimension('C')->setAutoSize(true);
		$objSheet->getColumnDimension('D')->setAutoSize(true);
		foreach($data as $key=>$val){
			$objSheet->setCellValue("A".$j,date('Y-m-d H:s:i',$val['create_time']))->setCellValue("B".$j,' '.$val['orderid'])->setCellValue("C".$j,$val['uid'])->setCellValue("D".$j,$val['tel'])->setCellValue("E".$j,$val['operator'])->setCellValue("F".$j,$val['payment'])->setCellValue("G".$j,$val['genre'])->setCellValue("H".$j,$val['recharge'])->setCellValue("I".$j,$val['mode']);
			$j++;
		}
		$objWriter=\PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="browser_excel.xls"');//告诉浏览器将输出文件的名称
		header('Cache-Control: max-age=0');
		$objWriter->save("php://output");
	}
	/*
	 * 代理商订单页
	 */
	function agent_order(){
		$wherelist=array();
		if(!empty($_GET['tel'])){
			$wherelist[]="o.tel = ".trim($_GET['tel']);
		}
		if(!empty($_GET['orderid'])){
			$wherelist[]="o.orderid = ".trim($_GET['orderid']);
		}
		if(!empty($_GET['uid'])){
			$wherelist[]="o.uid = ".trim($_GET['uid']);
		}
		if(!empty($_GET['start']) && !empty($_GET['close'])){
			$start=strtotime($_GET['start']);
			$close=strtotime($_GET['close']);
			$wherelist[]="o.create_time >= '{$start}' and  o.create_time <= '{$close}'";
		}
		$where='';
		if(count($wherelist) > 0){
			$where=join(' and ', $wherelist);
		}if(!empty($_GET['company'])){
			$where="i.company = '".trim($_GET['company'])."'";
		}
		//echo $_GET['company'];exit;
 		//实现分页
		$arr=M('orderinfo')->field("count(*) as num")->where($where)->join("information as i on i.uid=o.uid")->alias("o")->find();		
		$num=$arr['num'];
		//echo M('orderinfo')->getLastSql();exit;
		//每页显示页数
		$pageSize=3;
		import('Think.Page');
		$page=new Page($num,$pageSize,$_GET);		
		$first=$page->firstRow;
		$data=M('orderinfo')->where($where)->limit($first,$pageSize)->join("information as i on i.uid=o.uid")->alias("o")->select();
		// 设置分页显示
		$page -> setConfig('header','共%TOTAL_ROW%条');
		$page -> setConfig('first','首页');
		$page -> setConfig('last','共%TOTAL_PAGE%页');
		$page -> setConfig('prev','上一页');
		$page -> setConfig('next','下一页');
		$page -> setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
		$pagestr=$page->show();
		//echo $pagestr;exit;
		$this->assign("nav",ACTION_NAME);
		$this->assign("pagestr",$pagestr);
		$this->assign("data",$data);
		$this->assign("get",$_GET); 
		$this->display();
	}
	//创建新窗口 导出用户订单excel
	function agent_output(){
		//导出订单excel
		$start=strtotime($_GET['start']);
		$close=strtotime($_GET['close']);
		if($start >= $close || $start==null || $close==null){
			$this->error("日期输入有误!请重输",$_SERVER['HTTP_REFERE']);
			exit;
		}
		$data=M('orderinfo')->join(" information as i on i.orderid=o.orderid")->alias("o")->where("create_time >= '{$start}' and  create_time <= '{$close}'")->select();
		//var_dump($data);exit;
		vendor('PHPExcel');
		$objPHPExcel=new \PHPExcel();
		//var_dump($objPHPExcel);exit;
		$objSheet=$objPHPExcel->getActiveSheet();
		$objSheet->setTitle('代理商账单');
		$objSheet->setCellValue("A1","创建时间")->setCellValue("B1","订单号")->setCellValue("C1","用户ID")->setCellValue("D1","手机号")->setCellValue("E1","运营商")->setCellValue("F1","充值金额")->setCellValue("G1","类型")->setCellValue("H1","支付金额")->setCellValue("I1","支付方式")->setCellValue("J1","门店名称");
		$j=2;
		$objSheet->getColumnDimension('A')->setAutoSize(true);
		$objSheet->getColumnDimension('B')->setAutoSize(true);
		$objSheet->getColumnDimension('C')->setAutoSize(true);
		$objSheet->getColumnDimension('D')->setAutoSize(true);
		foreach($data as $key=>$val){
			$objSheet->setCellValue("A".$j,date('Y-m-d H:s:i',$val['create_time']))->setCellValue("B".$j,' '.$val['orderid'])->setCellValue("C".$j,$val['uid'])->setCellValue("D".$j,$val['tel'])->setCellValue("E".$j,$val['operator'])->setCellValue("F".$j,$val['payment'])->setCellValue("G".$j,$val['genre'])->setCellValue("H".$j,$val['recharge'])->setCellValue("I".$j,$val['mode'])->setCellValue("j".$j,$val['company']);
			$j++;
		}
		$objWriter=\PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="browser_excel.xls"');//告诉浏览器将输出文件的名称
		header('Cache-Control: max-age=0');
		$objWriter->save("php://output");
	}

}