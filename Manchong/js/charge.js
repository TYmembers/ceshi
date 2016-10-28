/**
 * Created by Administrator on 2016/10/17.
 */

$(document).ready(function () {
    var recharge={};
    recharge.phoneNumber=$("#rechange-phonenumber");//电话号码
    recharge.type=$("#mobile-type");//号码运营商
    recharge.changeBtn=$("#changenumber");//更改号码
    recharge.numberBefore=$(".push>.phoneNumber");//号码记录
    recharge.numberList=$(".push>.phoneNumber>li");
    recharge.clearNum=$(".push>.phoneNumber>li:nth-child(4)");//清除充值记录
    recharge.time=$(".checkbox>li");//到账时间
    recharge.icon=$(".checkbox>li>i"); //选择框
    recharge.price=$(".push .moneyList a");//选择价钱

// 默认电话号码

    recharge.phoneNumber.val(localStorage.loginNumber);
    //切换号码
    recharge.changeBtn.bind("click",function () {
        recharge.type.hide();
        recharge.phoneNumber.css({color:"#ccc",fontSize:"1.2rem"}).val("请您输入手机号码")
            .focus(function () {
                $(this).val("").css({color:"#555",fontSize:"1.3rem"});
            });
        recharge.numberBefore.show();
    });

    //  验证手机号码的归属地
    function getMobileOperation() {
        if (isPhone(recharge.phoneNumber.val())){
            recharge.numberBefore.hide();
            $.ajax({
                type:'post',
                url:"../json/mobiletype.json",
                data:{
                    phoneNumber:recharge.phoneNumber.val()
                },
                datatype:"json",
                success:function (data) {
                    if (data.code==0){
                        recharge.type.text("用户绑定号码"+data.province+data.operation).show();
                        localStorage.setItem('phoneNumber',recharge.phoneNumber.val());
                    }else{
                        createAlert(tip[1]);
                    }//else{查不到手机号是的弹窗}
                }
            });
        }
    }
    getMobileOperation();

    //输入切换号码验证归属地
    recharge.phoneNumber[0].onkeyup=function () {
        getMobileOperation();
    };

    //点击充值号码填充
    recharge.numberList.bind("click",function () {
        recharge.phoneNumber.val($(this).children(".number").text());
        recharge.numberBefore.hide();
    });

    //清空历史充值号码
    recharge.clearNum.bind("click",function () {
        $(this).parent().hide();
    });

    //选择到帐时间,单选
    recharge.icon.bind("click",function () {
        $(this).children("span").toggleClass("disc")
            .parents("li").siblings("li").children("i").
        children("span").removeClass("disc");

        localStorage.setItem("howlong",$(this).siblings('span').text());
    });

    //选择充值价钱
    recharge.price.bind("click",function (e) {
        if (recharge.icon.children('span').hasClass("disc")){
            e.preventDefault();
            localStorage.setItem("howmuch",$(this).children('h4').text());
            $(this).toggleClass("active").siblings("a").removeClass("active");
            location.href = "../webhtml/pay.html";
        }else{
            $(this).removeAttr("href");
            createAlert(tip[2]);
        }
    });

});
