$(function() {
    var e = getCookie("key");
    if (!e) {
        location.href =  WapSiteUrl + "/index.php?con=login"
    }
    function s() {

        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=member_address&fun=address_list",
            data: {
                key: e
            },
            dataType: "json",
            success: function(e) {
                checkLogin(e.login);
                if (e.datas.address_list == null) {
                    return false
                }
                $('#loding').hide();
                var s = e.datas;
                var t = template.render("saddress_list", s);
                $("#address_list").empty();
                $("#address_list").append(t);
                $(".deladdress").click(function() {
                    var e = $(this).attr("address_id");
                  
                    layer.open({
                    
                        content: '确认删除吗？',
                        btn: ['嗯', '不要'],
                        yes: function(index){
                             a(e)
                            layer.close(index);
                        }
                    });
                })
                $(".address_url").click(function() {
                    var aid = $(this).attr("address_id");
                    location.href =  ApiUrl + "/index.php?con=member_address&fun=address_opera_edit&address_id="+aid;
                })
            }
        })
    }
    s();
    function a(a) {
        var loading = layer.open({type:2,content:'删除中...'});
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=member_address&fun=address_del",
            data: {
                address_id: a,
                key: e
            },
            dataType: "json",
            success: function(e) {
                checkLogin(e.login);
                layer.close(loading);
                if (e) {
                    s()
                }
            }
        })
    }
});