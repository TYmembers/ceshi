/**
 * Created by Administrator on 2016/10/21.
 */
var comman=window.comman;
$(document).ready(function () {
    function init() {
        selestTY();//普通用户订单下拉单
        alertTY();//弹窗
        modifyTY();////账户管理页面修改权限
        inputTY();//input  时的各种判断
        pageChangeTY();//切换页面
        ableInput();//设置页面的回复禁用
        // optionTimeTY();//统计报表页面筛选时间
        exitSysterm();//退出登录清空本地存储
        viewAuthority();//账户管理页面查看权限
        viewLicense();//查看营业执照

    }
    var account = $(".userbox>a");
    function selestTY() {
        var select={};
        select.icon = $(".nav .triangle");//触发按钮
        select.box=$("ul.order-select");//筛选框
        select.list=$('ul.order-select>li');//每条筛选条目
        select.content=select.list.text();//每条筛选条目的具体内容

        select.icon.bind("mouseenter",function () {
            select.box.slideDown("slow",function () {

            });
        });

        select.box.bind('mouseleave',function () {
            $(this).slideUp(1000);
        });

        select.list.bind('click',function () {
            $(this).addClass("active").siblings("li").removeClass("active");
            select.box.slideUp(500);
        });
    }

    function alertTY() {
        var alert={};
        alert.out = $(".mainbox .output-btn");//导出订单按钮
        alert.agentout=$(".mainbox .agent-output-btn");//经销商用户界面的导出按钮
        alert.alertbox=$(".alert-box");//弹窗盒子
        alert.agentbox=$(".agent-alert-box");//经销商用户的弹窗盒子

        alert.log=$(".account-info .changelog");//修改登录密码按钮
        alert.logalertbox=$(".login-box");//修改登录密码的盒子

        alert.pay=$(".account-info .changepay");//修改支付密码的盒子
        alert.payalertbox=$(".pay-box");//修改支付密码的盒子

        alert.adminbox=$(".alert-admin-box");//输入管理员密码弹窗
        alert.moneybox=$(".alert-money-box");//打款弹窗
        alert.paying=$("table .orange .paying");//表格中打款按钮
        alert.moneybtn=$(".alert-admin-box .money-btn");//激发打款弹窗的按钮

        alert.moneyin=$(".alert-money-box .moneyin");//账户余额
        alert.moneyout=$(".alert-money-box .moneyout");//返利的余额

        alert.close =$(".close-btn");//关闭弹窗的按钮
        alert.confirm=$(".yes-btn");//弹窗的确定按钮

        alert.paying.bind("click",function () {
            var mybalance= $(this).parent("td").siblings("td.mybalance").text();
            var myrebate=$(this).parent("td").siblings("td.myrebate").text();
            var userID=$(this).parents('tr').children('td').eq(3).text();//获取userID
            localStorage.setItem("userID",userID);

            alert.moneyin.text(mybalance);
            alert.moneyout.text( myrebate);
            alert.adminbox.show();

        });

        alert.moneybtn.bind("click",function () {
                alert.moneybox.show();
        });



        alert.out.bind("click",function () {
            alert.alertbox.show();
        });

        alert.agentout.bind("click",function () {
            alert.moneybox.hide();
            alert.adminbox.hide();
            alert.agentbox.show();
        });

        alert.close.bind('click',function () {
            alert.alertbox.hide();
            alert.logalertbox.hide();
            alert.payalertbox.hide();
        });
        alert.confirm.bind("click",function () {
            alert.alertbox.hide();
            alert.logalertbox.hide();
            alert.payalertbox.hide();
        });

        alert.log.bind('click',function () {
            alert.logalertbox.show();
        });

        alert.pay.bind('click',function () {
            alert.payalertbox.show();
        });
    }

    function modifyTY() {
        var modify={};
        modify.modify = $(".admin-list .modify");//修改权限按钮
        modify.box = $(".modify-box");//修改权限的弹出盒子
        modify.icon = $(".modify-box .triangle");//下拉按钮三角
        modify.lists = $(".modify-box .modify-lists");//下拉列表
        modify.li = $(".modify-box .modify-lists>li");//下拉的每一项
        modify.newli=$(".modify-box .newli");//已选择的每一项
        modify.confirm=$(".modify-box .yes-btn");//确定按钮
        modify.delete =$(".modify-box .no");//删除已选择的选项
        modify.close =$(".modify-box .close-btn");//关闭弹窗
        modify.modify.bind('click',function () {
            var account=$(this).parents("tr").children("td").eq(2).text();
            localStorage.setItem("account",account);
            console.log(account);
            modify.box.show();
        });


        modify.icon.bind('click',function () {
            modify.lists.slideToggle();
        });

        modify.li.bind("click",function () {
            var n=$(this).index();
            $(this).addClass("active");
            modify.newli[n].style.display="block";

        });

        modify.delete.bind("click",function () {
            $(this).parents(".newli").hide();
            var n=$(this).parents(".newli").index()-1;
            var li = $(modify.li[n]);
            li.removeClass("active");
        });

        // var select=[];//记录被选中的li的部分
        //
        //
        // modify.confirm.bind("click",function () {
        //
        //     $.each(modify.newli,function (i) {
        //         if (modify.newli.eq(i).css("display")=="block"){
        //             select.push(i+1)
        //         }
        //     });
        //     localStorage.select=select;
        //     select=[];
        //     modify.box.hide();
        // });

        modify.close.bind("click",function () {
            modify.box.hide();
        });
    }

//公共弹窗
    var tip=["输入不能为空!", "还未添加任何权限"];
    function myAlert(tip) {
        var $commanalert=$(" <div class='comman-alert'></div>");
        var $alertheader=$(" <div class='alert-header'>!! 重要提示</div>");
        var $alertbody=$("<div class='alert-body'></div>" );
        var $p=$("<p>"+tip+"</p>");
        var $yes=$("<div class='yes-btn'>确定</div>");
        $alertbody.append($p);
        $alertbody.append($yes);
        $commanalert.append($alertheader);
        $commanalert.append($alertbody);
        $("header").after($commanalert);

        var commanalert={};
        commanalert.confirm=$(".comman-alert .yes-btn"); //确定按钮
        commanalert.box=$(".comman-alert");//弹窗盒子

        commanalert.confirm.bind("click",function () {
            commanalert.box.hide();
        });
    }

    function inputTY() {
        var input={};
        input.text=$("input[type='text']");//文本输入框
        input.tel= $("input[type='tel']");//电话输入框
        // input.text.on("blur",function () {
        //     if ($(this).val()==""){
        //         myAlert(tip[0]);
        //     }
        // });
    }

    function pageChangeTY() {
    var page = $(".paging>li:not('.nextpage')>a");
    var next = $(".paging>li.nextpage>a");

    page.bind('click', function (e) {
        e.preventDefault();
        $(this).parent('li').addClass("active")
            .siblings("li").removeClass("active");

    });

    next.bind("click", function () {
        $(".paging>li.active").removeClass("active")
            .next("li:not('.next')").addClass("active");

    });
}

    function ableInput() {
    var setup={};
    setup.modify=$(".setup-body>.btnbox>.modify");//修改按钮
    setup.obj=$(".setup-body input.border");//被修改的框

    setup.modify.bind('click',function () {
        setup.obj.removeAttr("disabled").attr("color","#999");
    });
}


//统计报表页面筛选时间
    // function optionTimeTY() {
    // var opt={};
    // opt.select=$("#opttime");

    // function getData() {
    //     var td=$("tr.mean td");
    //     $.ajax({
    //         type:"post",
    //         url:"../json/statistic.json",
    //         data:{
    //             option:opt.select.val()
    //         },
    //         datatype:"json",
    //         success:function (data) {
    //             if (data.code==0){
    //                 var lists=data.content;
    //                 $.each(lists,function (i) {
    //                     (function (i) {
    //                         td.eq(i).text(lists[i]);
    //                     })(i)
    //                 });
    //             }
    //         }
    //     });
    // }
    // getData();
    // opt.select.change(function () {
    //     getData();

    // })

    // }

    function exitSysterm() {
        var exitbtn=$(".adminbox a");
        exitbtn.bind('click',function () {
            localStorage.clear();
        })
    }

    function viewLicense() {
        var license={};
        license.view=$(".order-table td.view-license");//查看按钮
        license.img =$('.order-table td.view-license>img');
        license.imgurl=license.img.attr('src');//营业执照的小图的src
        license.mask = $(".fullmask");//遮罩层
        license.close=$(".fullmask .close-icon");//关闭按钮



        license.view.bind('click',function () {
            console.log($(this).text());
            license.mask.show()
                .find('img').attr('src',license.imgurl);
        });

        license.close.bind('click',function () {
            license.mask.hide();
        })
    }

    // function viewAuthority() {
    //     var authority={};
    //     authority.view = $(".admin-list .list-body td:nth-child(5)");//查看按钮
    //     authority.box=$(".authority");//权限盒子
    //     authority.close=$(".authority>.close");//关闭按钮

    //     authority.view.bind('click',function () {
    //          authority.account=$(this).siblings("td").eq(2).text();//账号
    //          authority.numli =$(this).parents('tr').index();//查看时距离顶部的li的数目
    //          authority.sibtd=$(this).parent('tr').siblings('tr').children('td:nth-child(5)');
    //         $.ajax({
    //             type:"post",
    //             url:"../Account/user_root",
    //             data:{
    //                 user:authority.account
    //             },
    //             datatype:'json',
    //             success:function (data) {
    //                if(data.code==1){
    //                 console.log(data.root);
    //                var arr=data.root.split(",");
    //                    var top= 54;
    //                    top +=authority.numli*41;
    //                    authority.box.css('top',top+"px");
    //                    var contents=[
    //                        "用户管理",
    //                        "充值设置",
    //                        "账户管理",
    //                        "统计报表"
    //                    ];
    //                    var content;
    //                    $.each(arr,function (i) {
    //                         var index=parseInt(arr[i])-1;
    //                        content=contents[index];
    //                        var li=$("<li>"+content+"</li>");
    //                        authority.box.append(li);
    //                    });
    //                    authority.box.show();
    //                    authority.sibtd.css('pointer-events','none');
    //                    authority.close.bind('click',function () {
    //                        authority.box.hide()
    //                            .children('li').remove();
    //                        authority.sibtd.css('pointer-events','all');
    //                    });


    //                }else if(data.code==2){
                       
    //                }
    //             }
    //         });
    //     })
    // }

    init();
});
