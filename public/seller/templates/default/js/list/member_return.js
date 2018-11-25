$(function() {
    var e = getCookie("key");
    var r = new ncScrollLoad;
    r.loadInit({
        url: ApiUrl + "/index.php?con=member_return&fun=get_return_list",
        getparam: {
            key: e
        },
        tmplid: "return-list-tmpl",
        containerobj: $("#return-list"),
        iIntervalId: true,
        data: {
            WapSiteUrl: WapSiteUrl
        },
        callback: function() {
            $(".delay-btn").click(function() {
                return_id = $(this).attr("return_id");
                $.getJSON(ApiUrl + "/index.php?con=member_return&fun=delay_form", {
                    key: e,
                    return_id: return_id
                },
                function(r) {
                    checkLogin(r.login);
                      layer.open({
                        content: '发货 <span id="delayDay">' + r.datas.return_delay + '</span> 天后，当商家选择未收到则要进行延迟时间操作；<br> 如果超过 <span id="confirmDay">' + r.datas.return_confirm + "</span> 天不处理按弃货处理，直接由管理员确认退款。",
                        btn: ['要', '不要'],
                         yes: function(index){
                            $.ajax({
                                type: "post",
                                url: ApiUrl + "/index.php?con=member_return&fun=delay_post",
                                data: {
                                    key: e,
                                    return_id: return_id
                                },
                                dataType: "json",
                                success: function(e) {
                                    checkLogin(e.login);
                                    if (e.datas.error) {
                                        layer.open({
                                            content:e.datas.error,
                                            time:2

                                        });
                                        layer.close(index);
                                        return false
                                    }
                                    window.location.href = ApiUrl + "/index.php?con=member_return"
                                }
                            })
                        },
                        no: function() {
                            window.location.href = ApiUrl + "/index.php?con=member_return"
                        }
                    });
                 
                        
                    return false
                })
            })
        }
    })
});