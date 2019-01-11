

var nodeSiteUrl = "";

var memberInfo = {};

var resourceSiteUrl = "";

var smilies_array = new Array;

smilies_array[1] = [["1", ":smile:", "smile.gif", "28", "28", "28", "微笑"], ["2", ":sad:", "sad.gif", "28", "28", "28", "难过"], ["3", ":biggrin:", "biggrin.gif", "28", "28", "28", "呲牙"], ["4", ":cry:", "cry.gif", "28", "28", "28", "大哭"], ["5", ":huffy:", "huffy.gif", "28", "28", "28", "发怒"], ["6", ":shocked:", "shocked.gif", "28", "28", "28", "惊讶"], ["7", ":tongue:", "tongue.gif", "28", "28", "28", "调皮"], ["8", ":shy:", "shy.gif", "28", "28", "28", "害羞"], ["9", ":titter:", "titter.gif", "28", "28", "28", "偷笑"], ["10", ":sweat:", "sweat.gif", "28", "28", "28", "流汗"], ["11", ":mad:", "mad.gif", "28", "28", "28", "抓狂"], ["12", ":lol:", "lol.gif", "28", "28", "28", "阴险"], ["13", ":loveliness:", "loveliness.gif", "28", "28", "28", "可爱"], ["14", ":funk:", "funk.gif", "28", "28", "28", "惊恐"], ["15", ":curse:", "curse.gif", "28", "28", "28", "咒骂"], ["16", ":dizzy:", "dizzy.gif", "28", "28", "28", "晕"], ["17", ":shutup:", "shutup.gif", "28", "28", "28", "闭嘴"], ["18", ":sleepy:", "sleepy.gif", "28", "28", "28", "睡"], ["19", ":hug:", "hug.gif", "28", "28", "28", "拥抱"], ["20", ":victory:", "victory.gif", "28", "28", "28", "胜利"], ["21", ":sun:", "sun.gif", "28", "28", "28", "太阳"], ["22", ":moon:", "moon.gif", "28", "28", "28", "月亮"], ["23", ":kiss:", "kiss.gif", "28", "28", "28", "示爱"], ["24", ":handshake:", "handshake.gif", "28", "28", "28", "握手"]];

var t_id = getQueryString("t_id");



$(function() {

    $.getJSON(ApiUrl + "/index.php?con=seller_chat&fun=get_node_info", {


        u_id: t_id,



    },

    function(t) {

        checkLogin(t.login);

        e(t.datas);

        if (!$.isEmptyObject(t.datas.chat_goods)) {

            var a = t.datas.chat_goods;

            var s = '<div class="nctouch-chat-product"> <a href="' + ApiUrl + "/index.php?con=goods&fun=detail&goods_id=" + a.goods_id + '" target="_blank"><div class="goods-pic"><img src="' + a.pic24 + '" alt=""/></div><div class="goods-info"><div class="goods-name">' + a.goods_name + '</div><div class="goods-price">￥' + a.goods_promotion_price + "</div></div></a> </div>";

            $("#chat_msg_html").append(s)

        }

    });

    var e = function(e) {

        nodeSiteUrl = e.node_site_url;

        memberInfo = e.member_info;

        userInfo = e.user_info;

        $("h1").html(memberInfo.store_name != "" ? memberInfo.store_name: memberInfo.member_name);

        resourceSiteUrl = e.resource_site_url;

        if (!e.node_chat) {

          

             layer.open({

                content:'在线聊天系统暂时未启用',

                time:2

            });

                   

            return false

        }

        var t = document.createElement("script");

        t.type = "text/javascript";

        t.src = nodeSiteUrl + "/socket.io/socket.io.js";

        document.body.appendChild(t);

        a();

        function a() {



            setTimeout(function() {

                if (typeof io === "function") {

                    s()

                } else {

                    a()

                }

            },

            500)

        }

        function s() {
            var e = nodeSiteUrl;
            var t = 0;
            var a = {};
            a["u_id"] = userInfo.member_id;
            a["u_name"] = userInfo.member_name;
            a["avatar"] = userInfo.member_avatar;
            a["s_id"] = userInfo.store_id;
            a["s_name"] = userInfo.store_name;
            a["s_avatar"] = userInfo.store_avatar;
            socket = io.connect(e, { 'resource': 'resource', 'reconnect': false });
            socket.on("connect",
            function() {
                t = 1;
                socket.emit("update_user", a);
                socket.on("get_msg",
                function(e) {
                    o(e)
                });
                socket.on("disconnect",
                function() {
                    t = 0
                })
            });

            function s(e) {
                if (t === 1) {
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?con=seller_chat&fun=send_msg",
                        data: e,
                        dataType: "json",
                        success: function(e) {
                            if (e.code == 200) {

                                var t = e.datas.msg;
                                socket.emit("send_msg", t);

                                t.avatar = userInfo.store_avatar;
                                t.class = "msg-me";
                                n(t)
                            } else {
                                 layer.open({
                                        content:e.datas.error,
                                        time:2
                                    });
                                return false
                            }
                        }
                    })
                }
            }
            function i(e, a) {

                if (t === 1) {

                    socket.emit("del_msg", {

                        max_id: e,

                        f_id: a

                    })

                }

            }

            function o(e) {
                var t;
                for (var a in e) {
                    var s = e[a];
                    if (e[a].f_id != t_id) {
                        continue
                    }
                    t = a;
                    s.avatar = memberInfo.member_avatar;
                    s.class = "msg-other";
                    n(s)
                }

                if (typeof t != "undefined") {

                    i(t, t_id)

                }

            }

            $("#submit").click(function() {

                var e = $("#msg").val();

        

                $("#msg").val("");

                if (e == "") {

                    layer.open({

                            content:'请填写内容',

                            time:2

                        });

                    return false

                }

                s({


                  
                    t_id: t_id,
                    t_name: memberInfo.member_name,
                    t_msg: e,

                

                });

                $("#chat_smile").addClass("hide");

                $(".nctouch-chat-con").css("bottom", "2rem")

            })

        }

        for (var i in smilies_array[1]) {

            var o = smilies_array[1][i];

            var r = '<img title="' + o[6] + '" alt="' + o[6] + '" data-sign="' + o[1] + '" src="' + resourceSiteUrl + "/js/smilies/images/" + o[2] + '">';

            $("#chat_smile > ul").append("<li>" + r + "</li>")

        }

        $("#open_smile").click(function() {

            if ($("#chat_smile").hasClass("hide")) {

                $("#chat_smile").removeClass("hide");

                $(".nctouch-chat-con").css("bottom", "7rem")

            } else {

                $("#chat_smile").addClass("hide");

                $(".nctouch-chat-con").css("bottom", "2rem")

            }

        });

        $("#chat_smile").on("click", "img",

        function() {

            var e = $(this).attr("data-sign");

            var t = $("#msg")[0];

            var a = t.selectionStart;

            var s = t.selectionEnd;

            var i = t.scrollTop;

            t.value = t.value.substring(0, a) + e + t.value.substring(s, t.value.length);

            t.setSelectionRange(a + e.length, s + e.length)

        });

        $("#chat_msg_log").click(function() {

            $.ajax({

                type: "post",

                url: ApiUrl + "/index.php?con=seller_chat&fun=get_chat_log&page=50",

                data: {



                    t_id: t_id,

                    t: 30

                },

                dataType: "json",

                success: function(e) {

                    if (e.code == 200) {

                        if (e.datas.list.length == 0) {

                  

                             layer.open({

                                content:'暂无聊天记录',

                                time:2

                            });

                            return false

                        }

                        e.datas.list.reverse();

                        $("#chat_msg_html").html("");

                        for (var t = 0; t < e.datas.list.length; t++) {

                            var a = e.datas.list[t];

                            if (a.f_id != t_id) {

                                var s = {};

                                s.class = "msg-me";

                                s.avatar = userInfo.store_avatar;

                                s.t_msg = a.t_msg;

                                n(s)

                            } else {

                                var s = {};

                                s.class = "msg-other";

                                s.avatar =memberInfo.member_avatar;

                                s.t_msg = a.t_msg;

                                n(s)

                            }

                        }

                    } else {

                        layer.open({

                            content:e.datas.error,

                            time:2

                        });

                     



                        return false

                    }

                }

            })

        });

        function n(e) {
         
            e.t_msg = c(e.t_msg);

            var t = '<dl class="' + e.class + '"><dt><img src="' + e.avatar + '" alt=""/><i></i></dt><dd>' + e.t_msg +"</dd></dl>";

            $("#chat_msg_html").append(t);

            if (!$.isEmptyObject(e.chat_goods) && !$('.nctouch-chat-product').html()) {

                var a = e.chat_goods;

                var t = '<div class="nctouch-chat-product"> <a href="' + ApiUrl + "/index.php?con=goods&fun=detail&goods_id=" + a.goods_id +  '" target="_blank"><div class="goods-pic"><img src="' + a.pic24 + '" alt=""/></div><div class="goods-info"><div class="goods-name">' + a.goods_name + '</div><div class="goods-price">￥' + a.goods_promotion_price + "</div></div></a> </div>";

                $("#chat_msg_html").append(t)

            }

            $("#anchor-bottom")[0].scrollIntoView()

        }

        function c(e) {

            if (typeof smilies_array !== "undefined") {

                e = "" + e;

                for (var t in smilies_array[1]) {

                    var a = smilies_array[1][t];

                    var s = new RegExp("" + a[1], "g");

                    var i = '<img title="' + a[6] + '" alt="' + a[6] + '" src="' + resourceSiteUrl + "/js/smilies/images/" + a[2] + '">';

                    e = e.replace(s, i)

                }

            }

            return e

        }

    }

});
