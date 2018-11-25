$(function() {

    var t = getCookie("key");

    if (!t) {

        window.location.href =  WapUrl + "/tmpl/member/login.html";

    }

    var e = new ncScrollLoad;

    e.loadInit({

        url: ApiUrl + "/index.php?con=member_favorites_store&fun=favorites_list",

        getparam: {

            key: t

        },

        tmplid: "sfavorites_list",

        containerobj: $("#favorites_list"),

        iIntervalId: true,

        data: {

            ApiUrl: ApiUrl

        }

    });

    $("#favorites_list").on("click", "[nc_type='fav_del']",function() {

        var t = $(this).attr("data_id");

        if (t <= 0) {

               layer.open({

                content: '删除失败',

                time: 1.5

            });

        }

        if (dropFavoriteStore(t)) {

            $("#favitem_" + t).remove();

            if (!$.trim($("#favorites_list").html())) {

                  window.location.reload();

            }

        }

    })

});