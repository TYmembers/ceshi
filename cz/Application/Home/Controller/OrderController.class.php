<?php
namespace Home\Controller;
use Think\Controller;
class OrderController extends CommonController {
    /**
     * 订单列表
     * @access public 
     * @param array $where 条件数组
     * @param string uid 用户id
     * @return array return 返回值
    */
    public function orderlist(){
        $order=M("Orderinfo");
        $where['uid']=I('post.uid');
        $total=$order->count();
        $page=new \Think\Page($total,10);
        $list=$order->limit($page->firstRow.','.$page->listRows)->where($where)->field("id,uid,tel,genre,recharge,create_time,state,operator")->select();
        if($list){
            $operator=C("OPERATOR_TYPE");
            $ordertype=C("ORDER_TYPE");
            $genre=C("MODE_TYPE");
            $recharge=C("PRICE_TYPE");
            foreach ($list as $key => $value) {
                //运营商
                $list[$key]['operator']=$operator[ $value['operator'] ];
                //订单状态
                $list[$key]['type']=$ordertype[ $value['state'] ];
                //充值金额
                $list[$key]['recharge']=$recharge[ $value['recharge'] ];
                //充值类型
                $list[$key]['genre']=$genre[ $value['genre'] ];
                //创建时间
                $list[$key]['create_time']=date("Y-m-d H:i:s",$list[$key]['create_time']);
            }
        }
        echo $this->AjaxReturn($list);
    }


    /**
     * 下单
     * @access public 
     * @param string uid 用户id
     * @param string tel 充值号码
     * @param int mode 支付方式
     * @param string orderid 订单号
     * @param double payment 支付金额
     * @param int recharge 充值数量
     * @param int genre 充值类型
     * @param int arrival 到账时间
     * @param int operator 运营商
     * @param int create_time 下单时间
     * @return array return 返回值
    */
    public function goods(){
        //优先判断号码是否合理
        if(preg_match('/^1(3[0-9]|4[57]|5[0-35-9]|7[01678]|8[0-9])\\d{8}$/',$_POST['tel'])){
            $map['tel']=I('post.tel');
        }else{
            $this->error("联系电话有误");
        }
		$goods=M('Orderinfo');
        //生成订单号，确定订单号未重复
		do{
			$map['orderid']=time().rand(0,999);
			$order=$goods->where($map)->find();
		}while($order==true);

		$map['uid']=I('post.uid');
		$map['mode']=I('post.mode');
		$map['arrival']=I('post.arrival');
		$map['operator']=I('post.operator');
		$map['recharge']=I('post.recharge');
		$map['genre']=I('post.genre');
		$map['payment']=I('post.payment');
		$map['create_time']=time();
		$result=$goods->add($map);

		if($result){
        	$return['message']="充值订单生成成功";
        	$return['state']="1";
        }else{
        	$return['message']="充值订单生成失败";
        	$return['state']="2";
        }
        
        echo $this->AjaxReturn($return);

	}
 
}