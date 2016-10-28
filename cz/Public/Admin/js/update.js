/**
 * Created by Administrator on 2016/10/27.
 */
function viewAuthority() {
    var authority={};
    authority.view = $(".admin-list .list-body td:nth-child(5)");//查看按钮
    authority.noview=$('.admin-list .list-body td:nth-child(5):contains("无任何权限")');
    authority.box=$(".authority");//权限盒子
    authority.close=$(".authority>.close");//关闭按钮

    authority.noview.css('pointer-events','none');
    authority.view.bind('click',function () {
        authority.account=$(this).siblings("td").eq(2).text();//账号
        authority.numli =$(this).parents('tr').index();//查看时距离顶部的li的数目
        authority.sibtd=$(this).parent('tr').siblings('tr').children('td:nth-child(5)');

        $.ajax({
            type:"post",
            url:"../Account/user_root",
            // url:"../json/authority.json",
            data:{
                user:authority.account
            },
            datatype:'json',
            success:function (data) {
                if(data.code==1){
                    var arr=data.root.split(",");
                    var top= 54;
                    top +=authority.numli*41;
                    authority.box.css('top',top+"px");
                    var contents=[
                        "用户管理",
                        "充值设置",
                        "账户管理",
                        "统计报表"
                    ];
                    var content;
                    $.each(arr,function (i) {
                        var index=parseInt(arr[i])-1;
                        content=contents[index];
                        var li=$("<li>"+content+"</li>");
                        authority.box.append(li);
                    });
                    authority.box.show();
                    authority.sibtd.css('pointer-events','none');
                    authority.close.bind('click',function () {
                        authority.box.hide()
                            .children('li').remove();
                        authority.sibtd.css('pointer-events','all');
                        authority.noview.css('pointer-events','none');
                    });
                }

            }
        });
    });


}