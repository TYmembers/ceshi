/**
 * Created by Administrator on 2016/10/18.
 */
$(document).ready(function () {
    var date=new Date();
    var pay={};
    pay.way=$(".pay>.actList");//支付方式
    pay.way.bind('click',function () {
        $.ajax({
            type:'post',
            url:"../json/pay.json",
            data:{
                phoneNumber:localStorage.phoneNumber,
                howlong:localStorage.howlong,
                howmuch:localStorage.howmuch,
                payway:$(this).find('h4').text(),
                date:date
            },
            datatype:"json",
            success:function (data) {
                if (data.code==0){
                    localStorage.setItem("success","success")
                }else{

                }//else{查不到手机号是的弹窗}
            }
        });
    })
});