<?php
namespace Home\Controller;
use Think\Controller;
class InterfaceController extends Controller {
	/**
     * 向上流量充值下单接口的封装
     * @access public 
     * @param varchar agent 代理商编号
    */
    public function flow($agent='admin'){
        //请求地址
        $url="http://shop.test.bolext.cn:81/shop/buyunit/orderpayforflow.do?";
        //代理商编号.由系统配置
        $macid=$agent;
        //运营商编号----全国移动流量3100，全国联通流量2100，全国电信流量1100，省内电信流量1600，省内联通流量2600，省内移动流量3600
        // $arsid=$operate;
        $arsid=$_POST['arsid'];
        //充值面额，整数
        $deno=intval($_POST['deno']);
        //商家订单号生成
        $orderid=date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        //充值号码,11位数字
        $phone=$_POST['phone'];
        //时间戳
        $time=time()+8*3600;//时间戳
        $array=array('macid'.$macid,'arsid'.$arsid,'deno'.$deno,'orderid'.$orderid,'phone'.$phone,'time'.$time);
        //将字符串按照字典排序
        sort($array);
        //拼装后的字符串
        $ar=implode(NULL,$array);
        $ab="-----BEGIN RSA PRIVATE KEY-----
        MIICXgIBAAKBgQDL2pg7PcmU+TKT4DTMJaf1KhIrbg0dBgCx+xwd9gxZMhAWLwoK
        LZnYdoZQrXZVYOiT2vqgfu+xvdJHjBOd+nqhagPqhq69Qr57+qHasdXlHieDITRr
        IuazY/pxP8MESwRS92oJabNJclmgvI+Leh1XixYZMrg67Bs7AhimKQgtBwIDAQAB
        AoGBAKKOOX5kEu78mFxbGT8BeCGD3uFK7KIMO1mxyAYMQmSOC03lTLg5DMkUGp8S
        8525nTzqDZkWH8U4fQoYpTwAlO/d5/XG92iywXXWkSypXEveh8dtxosAHUe91Pmo
        mBCIAhma06sZGFwdezNH+NP1MNX/8N6w/YHF6qB+H34izG0hAkEA5W1DHBZGfOHq
        GE/VgB83sydLPGxX9pb0KWWMIBRaGv/7OpbVNpvNkKXYRbzxzXBA/m9EXfZBK6Yz
        SKXd4vy1NQJBAON3Eceao/xr1zpw4pkHopuOrd0SQVgLcRxOC109P0zTzLYUDT4H
        LO1TN+u40IJZNW0hQ3Obp7aPX2zLnyxODMsCQEpPUHxJbr1GQxdqzEE6W0UoxgKl
        KPySujSqUm+Vh/XU0Z+ReS+92SAvx1QXNc6PvE1s5pz0hPlJVLUEHFFH/r0CQQDO
        rbX+A8jU5rfdZgy+t21Mosvff2LYOS1BZrh0s938VMZA+t89aQ+tZFv/VyI+Dgi5
        a+v584jkHEm8dRfgDdsZAkEAu0d3G3iiCFWgaMw2viRKvhXm0Gr9kHjMnUCM30QZ
        TU7RgHTiqJFbTxjDJLHgvzched4PYlRqcqoT8FUYNUODHA==
        -----END RSA PRIVATE KEY-----";
        //判断私钥是否可用
        $pi_key=openssl_pkey_get_private($ab);
        $encrypted="";
        openssl_sign($ar,$encrypted,$pi_key);
        //加密后的内容通常含有特殊字符，需要编码转换
        $encrypted=base64_encode($encrypted);
        $sign=$encrypted;
        $sign=urlencode($sign);
        $url=$url."arsid=".$arsid;
        $url=$url."&deno=".$deno;
        $url=$url."&macid=".$macid;
        $url=$url."&orderid=".$orderid;
        $url=$url."&phone=".$phone;
        $url=$url."&sign=".$sign;
        $url=$url."&time=".$time;
        //初始化一个句柄
        $ch=curl_init();
        //需要获取数据的url地址
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $ex=curl_exec($ch);
        curl_close($ch);
        //将xml语句转化成对象格式
        $cd=simplexml_load_string($ex);
        //exit json_encode($cd);//将数据已json格式返回给客户端
        echo @preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", json_encode($cd));
        exit;
    }


    /**
     * 流量价格查询
     * @access public 
     * @param varchar agent 代理商编号
    */
    public function flowsel($agent='admin'){
        //请求地址
        $url="http://shop.test.bolext.cn:81/shop/buyunit/orderpaypriceforflow.do?";
        //代理商编号.由系统配置
        $macid=$agent;
        //运营商编号----全国移动流量3100，全国联通流量2100，全国电信流量1100，省内电信流量1600，省内联通流量2600，省内移动流量3600
        $arsid=$_POST['arsid'];
        //充值面额，整数
        $deno=intval($_POST['deno']);
        //商家订单号生成
        $orderid=date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        //充值号码,11位数字
        $phone=$_POST['phone'];
        //时间戳
        $time=time()+8*3600;//时间戳
        $array=array('arsid'.$arsid,'phone'.$phone,'deno'.$deno,'time'.$time,'orderid'.$orderid,'macid'.$macid);
        //将字符串按照字典排序
        sort($array);
        //拼装后的字符串
        $aa=implode(NULL,$array);
        $ab="-----BEGIN RSA PRIVATE KEY-----
        MIICXgIBAAKBgQDL2pg7PcmU+TKT4DTMJaf1KhIrbg0dBgCx+xwd9gxZMhAWLwoK
        LZnYdoZQrXZVYOiT2vqgfu+xvdJHjBOd+nqhagPqhq69Qr57+qHasdXlHieDITRr
        IuazY/pxP8MESwRS92oJabNJclmgvI+Leh1XixYZMrg67Bs7AhimKQgtBwIDAQAB
        AoGBAKKOOX5kEu78mFxbGT8BeCGD3uFK7KIMO1mxyAYMQmSOC03lTLg5DMkUGp8S
        8525nTzqDZkWH8U4fQoYpTwAlO/d5/XG92iywXXWkSypXEveh8dtxosAHUe91Pmo
        mBCIAhma06sZGFwdezNH+NP1MNX/8N6w/YHF6qB+H34izG0hAkEA5W1DHBZGfOHq
        GE/VgB83sydLPGxX9pb0KWWMIBRaGv/7OpbVNpvNkKXYRbzxzXBA/m9EXfZBK6Yz
        SKXd4vy1NQJBAON3Eceao/xr1zpw4pkHopuOrd0SQVgLcRxOC109P0zTzLYUDT4H
        LO1TN+u40IJZNW0hQ3Obp7aPX2zLnyxODMsCQEpPUHxJbr1GQxdqzEE6W0UoxgKl
        KPySujSqUm+Vh/XU0Z+ReS+92SAvx1QXNc6PvE1s5pz0hPlJVLUEHFFH/r0CQQDO
        rbX+A8jU5rfdZgy+t21Mosvff2LYOS1BZrh0s938VMZA+t89aQ+tZFv/VyI+Dgi5
        a+v584jkHEm8dRfgDdsZAkEAu0d3G3iiCFWgaMw2viRKvhXm0Gr9kHjMnUCM30QZ
        TU7RgHTiqJFbTxjDJLHgvzched4PYlRqcqoT8FUYNUODHA==
        -----END RSA PRIVATE KEY-----";
        //判断私钥是否可用
        $pi_key=openssl_pkey_get_private($ab);
        $encrypted="";
        openssl_sign($aa,$encrypted,$pi_key);
        //加密后的内容通常含有特殊字符，需要编码转换
        $encrypted=base64_encode($encrypted);
        $sign=$encrypted;
        $sign=urlencode($sign);
        $url=$url."phone=".$phone;
        $url=$url."&deno=".$deno;
        $url=$url."&orderid=".$orderid;
        $url=$url."&arsid=".$arsid;
        $url=$url."&time=".$time;
        $url=$url."&macid=".$macid;
        $url=$url."&sign=".$sign;
        //初始化一个句柄
        $ch=curl_init();
        //需要获取数据的url地址
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $aa=curl_exec($ch);
        curl_close($ch);
        //将xml语句转化成对象格式
        $cd=simplexml_load_string($aa);
        echo @preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", json_encode($cc));
        exit;
    }


    /**
     * 订单查询
     * @access public 
     * @param varchar agent 代理商编号
    */
    public function order($agent){
        //通过http协议中的POST获得
        $orderid=$_POST['orderid'];
        //请求地址
        $url="http://shop.test.bolext.cn:81/shop/buyunit/query.do?";
        //代理商编号.由系统配置
        $macid=$agent;
        //商家订单号生成
        $orderid=date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        //时间戳
        $time=time()+8*3600;//时间戳
        $array=array('orderid'.$orderid,'macid'.$macid,'time'.$time);
        //将字符串按照字典排序
        sort($array);
        //拼装后的字符串
        $aa=implode(NULL,$array);
        $ab="-----BEGIN RSA PRIVATE KEY-----
        MIICXgIBAAKBgQDL2pg7PcmU+TKT4DTMJaf1KhIrbg0dBgCx+xwd9gxZMhAWLwoK
        LZnYdoZQrXZVYOiT2vqgfu+xvdJHjBOd+nqhagPqhq69Qr57+qHasdXlHieDITRr
        IuazY/pxP8MESwRS92oJabNJclmgvI+Leh1XixYZMrg67Bs7AhimKQgtBwIDAQAB
        AoGBAKKOOX5kEu78mFxbGT8BeCGD3uFK7KIMO1mxyAYMQmSOC03lTLg5DMkUGp8S
        8525nTzqDZkWH8U4fQoYpTwAlO/d5/XG92iywXXWkSypXEveh8dtxosAHUe91Pmo
        mBCIAhma06sZGFwdezNH+NP1MNX/8N6w/YHF6qB+H34izG0hAkEA5W1DHBZGfOHq
        GE/VgB83sydLPGxX9pb0KWWMIBRaGv/7OpbVNpvNkKXYRbzxzXBA/m9EXfZBK6Yz
        SKXd4vy1NQJBAON3Eceao/xr1zpw4pkHopuOrd0SQVgLcRxOC109P0zTzLYUDT4H
        LO1TN+u40IJZNW0hQ3Obp7aPX2zLnyxODMsCQEpPUHxJbr1GQxdqzEE6W0UoxgKl
        KPySujSqUm+Vh/XU0Z+ReS+92SAvx1QXNc6PvE1s5pz0hPlJVLUEHFFH/r0CQQDO
        rbX+A8jU5rfdZgy+t21Mosvff2LYOS1BZrh0s938VMZA+t89aQ+tZFv/VyI+Dgi5
        a+v584jkHEm8dRfgDdsZAkEAu0d3G3iiCFWgaMw2viRKvhXm0Gr9kHjMnUCM30QZ
        TU7RgHTiqJFbTxjDJLHgvzched4PYlRqcqoT8FUYNUODHA==
        -----END RSA PRIVATE KEY-----";
        //判断私钥是否可用
        $pi_key=openssl_pkey_get_private($ab);
        $encrypted="";
        openssl_sign($aa,$encrypted,$pi_key);
        $encrypted=base64_encode($encrypted);
        $sign=$encrypted;
        $sign=urlencode($sign);
        $url=$url."macid=".$macid."&orderid=".$orderid."&sign=".$sign."&time=".$time;
        //初始化一个句柄
        $ch=curl_init();
        //需要获取数据的url地址
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $ac=curl_exec($ch);
        curl_close($ch);
        //将xml语句转化成对象格式
        $a=simplexml_load_string($ac);
        echo @preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", json_encode($a));
        exit;
    }

    /**
     * 余额查询
     * @access public 
     * @param varchar agent 代理商编号
    */
    public function balance(){
        //请求地址
        $url="http://shop.test.bolext.cn:81/shop/buyunit/balance.do?";
        //代理商编号.由系统配置
        $macid=$agent;
        //时间戳
        $time=time()+8*3600;//时间戳
        $array=array('macid'.$macid,'time'.$time);
        $aa=implode(NULL,$array);
        $ab="-----BEGIN RSA PRIVATE KEY-----
        MIICXgIBAAKBgQDL2pg7PcmU+TKT4DTMJaf1KhIrbg0dBgCx+xwd9gxZMhAWLwoK
        LZnYdoZQrXZVYOiT2vqgfu+xvdJHjBOd+nqhagPqhq69Qr57+qHasdXlHieDITRr
        IuazY/pxP8MESwRS92oJabNJclmgvI+Leh1XixYZMrg67Bs7AhimKQgtBwIDAQAB
        AoGBAKKOOX5kEu78mFxbGT8BeCGD3uFK7KIMO1mxyAYMQmSOC03lTLg5DMkUGp8S
        8525nTzqDZkWH8U4fQoYpTwAlO/d5/XG92iywXXWkSypXEveh8dtxosAHUe91Pmo
        mBCIAhma06sZGFwdezNH+NP1MNX/8N6w/YHF6qB+H34izG0hAkEA5W1DHBZGfOHq
        GE/VgB83sydLPGxX9pb0KWWMIBRaGv/7OpbVNpvNkKXYRbzxzXBA/m9EXfZBK6Yz
        SKXd4vy1NQJBAON3Eceao/xr1zpw4pkHopuOrd0SQVgLcRxOC109P0zTzLYUDT4H
        LO1TN+u40IJZNW0hQ3Obp7aPX2zLnyxODMsCQEpPUHxJbr1GQxdqzEE6W0UoxgKl
        KPySujSqUm+Vh/XU0Z+ReS+92SAvx1QXNc6PvE1s5pz0hPlJVLUEHFFH/r0CQQDO
        rbX+A8jU5rfdZgy+t21Mosvff2LYOS1BZrh0s938VMZA+t89aQ+tZFv/VyI+Dgi5
        a+v584jkHEm8dRfgDdsZAkEAu0d3G3iiCFWgaMw2viRKvhXm0Gr9kHjMnUCM30QZ
        TU7RgHTiqJFbTxjDJLHgvzched4PYlRqcqoT8FUYNUODHA==
        -----END RSA PRIVATE KEY-----";
        //判断私钥是否可用
        $pi_key=openssl_pkey_get_private($ab);
        $encrypted="";
        openssl_sign($aa,$encrypted,$pi_key);
        $encrypted=base64_encode($encrypted);
        $sign=$encrypted;
        $sign=urlencode($sign);
        $url=$url."macid=".$macid."&sign=".$sign."&time=".$time;
        //初始化一个句柄
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $cd=curl_exec($ch);
        curl_close($ch);
        $ac=simplexml_load_string($cd);
        exit(json_encode($ac));
    }

    /**
     * 对账
     * @access public 
     * @param varchar agent 代理商编号
    */
    public function yewu(){
        $date=$_POST['date'];//对账数据的日期
        $url="http://shop.test.bolext.cn:81/shop/buyunit/billdownload.do?";
        $macid=$agent;
        $time=time()+8*3600;
        $array=array('macid'.$macid,'date'.$date,'time'.$time);
        sort($array);
        $aa=implode(NULL,$array);
        $ab="-----BEGIN RSA PRIVATE KEY-----
        MIICXgIBAAKBgQDL2pg7PcmU+TKT4DTMJaf1KhIrbg0dBgCx+xwd9gxZMhAWLwoK
        LZnYdoZQrXZVYOiT2vqgfu+xvdJHjBOd+nqhagPqhq69Qr57+qHasdXlHieDITRr
        IuazY/pxP8MESwRS92oJabNJclmgvI+Leh1XixYZMrg67Bs7AhimKQgtBwIDAQAB
        AoGBAKKOOX5kEu78mFxbGT8BeCGD3uFK7KIMO1mxyAYMQmSOC03lTLg5DMkUGp8S
        8525nTzqDZkWH8U4fQoYpTwAlO/d5/XG92iywXXWkSypXEveh8dtxosAHUe91Pmo
        mBCIAhma06sZGFwdezNH+NP1MNX/8N6w/YHF6qB+H34izG0hAkEA5W1DHBZGfOHq
        GE/VgB83sydLPGxX9pb0KWWMIBRaGv/7OpbVNpvNkKXYRbzxzXBA/m9EXfZBK6Yz
        SKXd4vy1NQJBAON3Eceao/xr1zpw4pkHopuOrd0SQVgLcRxOC109P0zTzLYUDT4H
        LO1TN+u40IJZNW0hQ3Obp7aPX2zLnyxODMsCQEpPUHxJbr1GQxdqzEE6W0UoxgKl
        KPySujSqUm+Vh/XU0Z+ReS+92SAvx1QXNc6PvE1s5pz0hPlJVLUEHFFH/r0CQQDO
        rbX+A8jU5rfdZgy+t21Mosvff2LYOS1BZrh0s938VMZA+t89aQ+tZFv/VyI+Dgi5
        a+v584jkHEm8dRfgDdsZAkEAu0d3G3iiCFWgaMw2viRKvhXm0Gr9kHjMnUCM30QZ
        TU7RgHTiqJFbTxjDJLHgvzched4PYlRqcqoT8FUYNUODHA==
        -----END RSA PRIVATE KEY-----";
        //判断私钥是否可用
        $pi_key=openssl_pkey_get_private($ab);
        $encrypted="";
        openssl_sign($aa,$encrypted,$pi_key);
        $encrypted=base64_encode($encrypted);
        $sign=$encrypted;
        $sign=urlencode($sign);
        $url=$url."date=".$date."&macid=".$macid."&sign=".$sign."&time=".$time;
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $ac=curl_exec($ch);
        curl_close($ch);
        echo $ac;
    }

    /**
     * 订单结果通知
     * @access public 
     * @param varchar agent 代理商编号
    */
    public function orderResult(){
        $deno=$_GET['deno'];
        $errcode=$_GET['errcode'];
        $errinfo=urldecode($_GET['errinfo']);//解码
        $id=$_GET['id'];
        $orderid=$_GET['orderid'];
        $sign=$_GET['sign'];
        $successdeno=$_GET['successdeno'];
        $array=array('deno'.$deno,'errcode'.$errcode,'errinfo'.$errinfo,'id'.$id,'orderid'.$orderid,'successdeno'.$successdeno);
        sort($array);
        $aa=implode(NULL,$array);
        $ab="-----BEGIN PUBLIC KEY-----
        MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDG78YogpvISlW/mvP0cIVBbrVu
        1OuhRuyaMGgo00CZmn2556T0n0rmNMBFMdah//lfYvlRxZQk1x6luoP1w7p8P+V9
        aIvVJ6eaBflzRTVkODB+TI9nt4fL5WsHS6gaLc73lIpvbCywYNfyltKyTSOBHzT3
        WUoWPblFHTFciJE76wIDAQAB
        -----END PUBLIC KEY-----";
        $res=openssl_pkey_get_public($ab);//获得可用的公钥
        if(openssl_verify($aa,base64_decode($sign),$res)){
            exit(json_encode("true"));
        }else{
            exit(json_encode("false"));
        }
    }
}