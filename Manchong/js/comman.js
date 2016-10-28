/**
 * Created by Administrator on 2016/10/17.
 */



//弹窗
var tip=[
    '恭喜您，充值成功！',
    '请输入正确的手机号码',
    '请选择到帐时间'
];
function createAlert(tip) {
    $("body").prepend("<div class='full-mask'></div>");
    $(".full-mask").append("<div class='alert-box'></div>");
    $(".alert-box").append("<div class='alert-content-box'></div><div class='alert-button'>知道了</div>");
    $(".alert-content-box").append("<i class='iconfont alerticon'>&#xe623;</i><p>"+tip+"</p>");

    var alertBtn=$(".alert-button");
    alertBtn.bind("click",function(){
        $(this).parents(".full-mask").hide();
    });
}



//倒计时
wait=30;
function time(o) {
    o.removeClass("sendcode").addClass("beforesend");
    o.attr("disabled", true);
    o.val("重新发送(" + wait + ")");
    wait--;
    var x=setTimeout(function() {
            time(o)
        },
        1000);
    if (wait==0){
        clearTimeout(x);
        o.toggleClass("sendcode").val("验证码");
    }

}

//手机号码验证
function isPhone(phone){
    var rules = /^1[3|4|5|7|8][0-9]{9}$/;
    return rules.test(phone)
}

//弹窗样式



