/**
 * Created by Administrator on 2016/10/17.
 */
$(document).ready(function () {
    function init() {
        login();
        recharge();
    }
//登录页
    function login() {
        var log={};
        log.phone =$("#phonenumber");//手机号id
        log.icon =$("#icon-right");//对勾id
        log.code=$("#authenticode");//验证码内容
        log.codeBtn=$("#authenticode-button");  //验证码获取按钮
        log.logBtn=$("#login-button");

        // 验证手机号
        var logphone=log.phone[0];
        logphone.addEventListener("keyup",function() {
            if (isPhone(log.phone.val())){
                log.icon.show();
                log.codeBtn.removeClass("beforesend").addClass("sendcode");
            }
        });

        //发送验证码
        log.codeBtn.bind("click",function () {
            time(log.codeBtn);
            $.ajax({
                type:'post',
                // url:"../json/code.json",
                url:'http://127.0.0.1/cz/Home/Login/Verification_Code',
                data:{
                    phoneNumber:log.phone.val()
                },
                datatype:"json",
                success:function (data) {
                    console.log(data);
                    if (data.code ==0){
                        log.code.val(data.message);
                        log.codeBtn.toggleClass("sendcode").val("验证码正确");
                        time=null;
                    }
                }
            });

        });

        //登录
        log.logBtn.bind("click",function (){
            $.ajax({
                type:'post',
                // url:'../json/login.json',
                url:'http://127.0.0.1/cz/Home/Login/login',
                data:{
                    phoneNumber:log.phone.val(),
                    codeNumber:log.code.val()
                },
                datatype:'json',
                success:function (data) {
                    console.log(data.code);
                    console.log(data.message);
                    if(data.code ==0) {
                        localStorage.setItem("loginNumber",log.phone.val());
                        location.href = "../webhtml/push.html";
                    }
                }
            })
        });
    }

    init();

    // createAlert(tip[0])
});


