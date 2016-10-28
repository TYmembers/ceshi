/**
 * Created by Administrator on 2016/10/18.
 */
$().ready(function () {
    //我的账户
    var account={};
    account.welcome=$(".account .header h4");//欢迎用户
    account.logout=$(".account .goback .loginOut ");//登出

    account.welcome.text("欢迎您 , "+localStorage.loginNumber);
    account.logout.bind("click",function () {
        localStorage.clear();
        location.href="../webhtml/welcome.html";
    })
});
