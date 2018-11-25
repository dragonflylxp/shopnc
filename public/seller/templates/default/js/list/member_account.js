$(function() {

    var e = getCookie("key");

    if (!e) {

        window.location.href =  WapUrl + "/tmpl/member/login.html";

        return

    }

  

    $.ajax({

        type: "get",

        url: ApiUrl + "/index.php?con=member_account&fun=get_mobile_info",

        data: {

            key: e

        },

        dataType: "json",

        success: function(e) {

            if (e.code == 200) {

                if (e.datas.state) {

                    $("#mobile_link").attr("href", "member_mobile_modify.html");

                    $("#mobile_value").html(e.datas.mobile)

                }

            } else {}

        }

    });

    $.ajax({

        type: "get",

        url: ApiUrl + "/index.php?con=member_account&fun=get_paypwd_info",

        data: {

            key: e

        },

        dataType: "json",

        success: function(e) {

            if (e.code == 200) {

                if (!e.datas.state) {

                    $("#paypwd_tips").html("未设置")

                }

            } else {}

        }

    })

});