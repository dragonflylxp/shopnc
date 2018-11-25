$(function() {

    var e = getCookie("key");

    if (!e) {

      window.location.href =  WapUrl + "/tmpl/member/login.html";

    }

    template.helper("isEmpty",

    function(e) {

        for (var t in e) {

            return false

        }

        return true

    });

    $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=member_chat&fun=get_user_list",

        data: {

            key: e,

            recent: 1

        },

        dataType: "json",

        success: function(t) {

            checkLogin(t.login);

            t.datas.ApiUrl = ApiUrl;

            var a = t.datas;

            
            for(i in a.list){
                
                a.list[i].t_msg = c( a.list[i].t_msg);
            }

            $("#messageList").html(template.render("messageListScript", a));

            $(".msg-list-del").click(function() {

                var t = $(this).attr("t_id");

                $.ajax({

                    type: "post",

                    url: ApiUrl + "/index.php?con=member_chat&fun=del_msg",

                    data: {

                        key: e,

                        t_id: t

                    },

                    dataType: "json",

                    success: function(e) {

                        if (e.code == 200) {

                            location.reload()

                        } else {

                            layer.open({

                                content:e.datas.error,

                                time:1.5

                            });

                            return false

                        }

                    }

                })

            })

        }

    })


  function c(e) {

            if (typeof smilies_array !== "undefined") {

                e = "" + e;

                for (var t in smilies_array[1]) {

                    var a = smilies_array[1][t];

                    var s = new RegExp("" + a[1], "g");

                    var i = "\\ "+a[6];

                    e = e.replace(s, i)

                }

            }

            return e

        }

});