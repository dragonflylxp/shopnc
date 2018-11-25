
var smilies_array = new Array;

var resourceSiteUrl = "";
smilies_array[1] = [["1", ":smile:", "smile.gif", "28", "28", "28", "微笑"], ["2", ":sad:", "sad.gif", "28", "28", "28", "难过"], ["3", ":biggrin:", "biggrin.gif", "28", "28", "28", "呲牙"], ["4", ":cry:", "cry.gif", "28", "28", "28", "大哭"], ["5", ":huffy:", "huffy.gif", "28", "28", "28", "发怒"], ["6", ":shocked:", "shocked.gif", "28", "28", "28", "惊讶"], ["7", ":tongue:", "tongue.gif", "28", "28", "28", "调皮"], ["8", ":shy:", "shy.gif", "28", "28", "28", "害羞"], ["9", ":titter:", "titter.gif", "28", "28", "28", "偷笑"], ["10", ":sweat:", "sweat.gif", "28", "28", "28", "流汗"], ["11", ":mad:", "mad.gif", "28", "28", "28", "抓狂"], ["12", ":lol:", "lol.gif", "28", "28", "28", "阴险"], ["13", ":loveliness:", "loveliness.gif", "28", "28", "28", "可爱"], ["14", ":funk:", "funk.gif", "28", "28", "28", "惊恐"], ["15", ":curse:", "curse.gif", "28", "28", "28", "咒骂"], ["16", ":dizzy:", "dizzy.gif", "28", "28", "28", "晕"], ["17", ":shutup:", "shutup.gif", "28", "28", "28", "闭嘴"], ["18", ":sleepy:", "sleepy.gif", "28", "28", "28", "睡"], ["19", ":hug:", "hug.gif", "28", "28", "28", "拥抱"], ["20", ":victory:", "victory.gif", "28", "28", "28", "胜利"], ["21", ":sun:", "sun.gif", "28", "28", "28", "太阳"], ["22", ":moon:", "moon.gif", "28", "28", "28", "月亮"], ["23", ":kiss:", "kiss.gif", "28", "28", "28", "示爱"], ["24", ":handshake:", "handshake.gif", "28", "28", "28", "握手"]];

$(function() {

    template.helper("isEmpty",

    function(e) {

        for (var t in e) {

            return false

        }

        return true

    });

    $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=seller_chat&fun=get_user_list",

        data: {

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

                    url: ApiUrl + "/index.php?con=seller_chat&fun=del_msg",

                    data: {


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