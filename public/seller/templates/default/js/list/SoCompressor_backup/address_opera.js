$(function() {
    var a = getCookie("key");
    $.sValid.init({
        rules: {
            true_name: "required",
            mob_phone: "mobile",
            area_info: "required",
            address: "required"
        },
        messages: {
            true_name: "姓名必填！",
            mob_phone: "手机号码格式不正确！",
            area_info: "地区必填！",
            address: "街道必填！"
        },
        callback: function(a, e, r) {
            if (a.length > 0) {
                var i = "";
                $.map(e,
                function(a, e) {
                    i += "<p>" + a + "</p>"
                });
                errorTipsShow(i)
            } else {
                errorTipsHide()
            }
        }
    });
    $("#header-nav").click(function() {
        $(".btn").click()
    });
    $(".btn").click(function() {
        if ($.sValid()) {
            var e = $("#true_name").val();
            var r = $("#mob_phone").val();
            var i = $("#address").val();
            var d = $("#area_info").attr("data-areaid2");
            var t = $("#area_info").attr("data-areaid");
            var n = $("#area_info").val();
            var o = $("#is_default").attr("checked") ? 1 : 0;
            var loading = layer.open({type:2,content:'提交中...'});
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=member_address&fun=address_add",
                data: {
                    key: a,
                    true_name: e,
                    mob_phone: r,
                    city_id: d,
                    area_id: t,
                    address: i,
                    area_info: n,
                    is_default: o
                },
                dataType: "json",
                success: function(a) {
                    layer.close(loading);
                    if (a) {
                        location.href = ApiUrl + "/index.php?con=member_address"
                    } else {
                        location.href = ApiUrl
                    }
                }
            })
        }
    });
    $("#area_info").on("click",
    function() {
        $.areaSelected({
            success: function(a) {
                $("#area_info").val(a.area_info).attr({
                    "data-areaid": a.area_id,
                    "data-areaid2": a.area_id_2 == 0 ? a.area_id_1: a.area_id_2
                })
            }
        })
    })
});