// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Author: wk <weika520@qq.com | http://www.wcphp.com> 2017/9/5 19:20
// +----------------------------------------------------------------------
$(function(){
    $('#loginBtn').on('click',function(){
        var param = $('#loginForm').serializeArray();
        $.ajax({
            type: "POST",
            url: "/login",
            data: param,
            dataType:"JSON",
            success: function(msg){
                console.log(msg.body);
                //登录失败
                if(msg.body.ret != 0 ){
                    alert(msg.body.retinfo);
                }else{
                    console.log(msg.body.data.jump_url);
                    window.location.href = msg.body.data.jump_url;
                }
            }
        });


    });

});