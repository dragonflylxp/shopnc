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

     

            var a = t.datas;

            

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

});