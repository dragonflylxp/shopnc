/*! layer mobile-v1.6 弹层组件移动版 License LGPL http://layer.layui.com/mobile By 贤心 */

; !function (a) { "use strict"; var b = ""; b = b ? b : document.scripts[document.scripts.length - 1].src.match(/[\s\S]*\//)[0]; var c = document, d = "querySelectorAll", e = "getElementsByClassName", f = function (a) { return c[d](a) }; var g = { type: 0, shade: !0, shadeClose: !0, fixed: !0, anim: !0 }; a.ready = { extend: function (a) { var b = JSON.parse(JSON.stringify(g)); for (var c in a) b[c] = a[c]; return b }, timer: {}, end: {} }, ready.touch = function (a, b) { var c; a.addEventListener("touchmove", function () { c = !0 }, !1), a.addEventListener("touchend", function (a) { a.preventDefault(), c || b.call(this, a), c = !1 }, !1) }; var h = 0, i = ["layermbox"], j = function (a) { var b = this; b.config = ready.extend(a), b.view() }; j.prototype.view = function () { var a = this, b = a.config, d = c.createElement("div"); a.id = d.id = i[0] + h, d.setAttribute("class", i[0] + " " + i[0] + (b.type || 0)), d.setAttribute("index", h); var g = function () { var a = "object" == typeof b.title; return b.title ? '<h3 style="' + (a ? b.title[1] : "") + '">' + (a ? b.title[0] : b.title) + '</h3><button class="layermend"></button>' : "" }(), j = function () { var a, c = (b.btn || []).length; return 0 !== c && b.btn ? (a = '<span type="1">' + b.btn[0] + "</span>", 2 === c && (a = '<span type="0">' + b.btn[1] + "</span>" + a), '<div class="layermbtn">' + a + "</div>") : "" }(); if (b.fixed || (b.top = b.hasOwnProperty("top") ? b.top : 100, b.style = b.style || "", b.style += " top:" + (c.body.scrollTop + b.top) + "px"), 2 === b.type && (b.content = '<i></i><i class="laymloadtwo"></i><i></i><div>' + (b.content || "") + "</div>"), d.innerHTML = (b.shade ? "<div " + ("string" == typeof b.shade ? 'style="' + b.shade + '"' : "") + ' class="laymshade"></div>' : "") + '<div class="layermmain" ' + (b.fixed ? "" : 'style="position:static;"') + '><div class="section"><div class="layermchild ' + (b.className ? b.className : "") + " " + (b.type || b.shade ? "" : "layermborder ") + (b.anim ? "layermanim" : "") + '" ' + (b.style ? 'style="' + b.style + '"' : "") + ">" + g + '<div class="layermcont">' + b.content + "</div>" + j + "</div></div></div>", !b.type || 2 === b.type) { var l = c[e](i[0] + b.type), m = l.length; m >= 1 && k.close(l[0].getAttribute("index")) } document.body.appendChild(d); var n = a.elem = f("#" + a.id)[0]; b.success && b.success(n), a.index = h++, a.action(b, n) }, j.prototype.action = function (a, b) { var c = this; if (a.time && (ready.timer[c.index] = setTimeout(function () { k.close(c.index) }, 1e3 * a.time)), a.title) { var d = b[e]("layermend")[0], f = function () { a.cancel && a.cancel(), k.close(c.index) }; ready.touch(d, f), d.onclick = f } var g = function () { var b = this.getAttribute("type"); 0 == b ? (a.no && a.no(), k.close(c.index)) : a.yes ? a.yes(c.index) : k.close(c.index) }; if (a.btn) for (var h = b[e]("layermbtn")[0].children, i = h.length, j = 0; i > j; j++) ready.touch(h[j], g), h[j].onclick = g; if (a.shade && a.shadeClose) { var l = b[e]("laymshade")[0]; ready.touch(l, function () { k.close(c.index, a.end) }), l.onclick = function () { k.close(c.index, a.end) } } a.end && (ready.end[c.index] = a.end) }; var k = { v: "1.6", index: h, open: function (a) { var b = new j(a || {}); return b.index }, close: function (a) { var b = f("#" + i[0] + a)[0]; b && (b.innerHTML = "", c.body.removeChild(b), clearTimeout(ready.timer[a]), delete ready.timer[a], "function" == typeof ready.end[a] && ready.end[a](), delete ready.end[a]) }, closeAll: function () { for (var a = c[e](i[0]), b = 0, d = a.length; d > b; b++) k.close(0 | a[0].getAttribute("index")) } }; "function" == typeof define ? define(function () { return k }) : a.layer = k }(window);
var SiteUrl = " http://manpay.sicpay.com/seller";
var ApiUrl = " http://manpay.sicpay.com/seller";
var WapUrl = " http://manpay.sicpay.com/wap";
var ShopUrl = " http://manpay.sicpay.com";
var pagesize = 20;
var WapSiteUrl = " http://manpay.sicpay.com/seller";
// var IOSSiteUrl = "https://itunes.apple.com/us/app/";
// var AndroidSiteUrl = "http://www.ynlmsc.pw//download/app/1.apk";
var WeiXinOauth = true;
if(WeiXinOauth){
    var key = getCookie('key'); 
    if(key==null){          
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){

        	
            window.location.href=ApiUrl+"/index.php?con=auto&fun=login&ref="+encodeURIComponent(window.location.href);
        }
    }
}

function getQueryString(e) {
    var t = new RegExp("(^|&)" + e + "=([^&]*)(&|$)");
    var a = window.location.search.substr(1).match(t);
    if (a != null) return a[2];
    return ""

}

function addCookie(e, t, a) {

    var n = e + "=" + escape(t) + "; path=/";

    if (a > 0) {

        var r = new Date;

        r.setTime(r.getTime() + a * 3600 * 1e3);

        n = n + ";expires=" + r.toGMTString()

    }

    document.cookie = n

}

function getCookie(e) {

    var t = document.cookie;

    var a = t.split("; ");

    for (var n = 0; n < a.length; n++) {

        var r = a[n].split("=");

        if (r[0] == e) return unescape(r[1])

    }

    return null

}

function delCookie(e) {

    var t = new Date;

    t.setTime(t.getTime() - 1);

    var a = getCookie(e);

    if (a != null) document.cookie = e + "=" + a + "; path=/;expires=" + t.toGMTString()

}

function checkLogin(e) {

    if (e == 0) {

        location.href = WapUrl + "/tmpl/member/login.html";

        return false

    } else {

        return true

    }

}

function contains(e, t) {

    var a = e.length;

    while (a--) {

        if (e[a] === t) {

            return true

        }

    }

    return false

}

function mark(act) {

    if (act == "show") {

        if ($("#alertMark").length < 1) {

            $("body").append('<div id="alertMark" style="position:fixed;width:100%;height:100%;z-index:98;background:rgba(0,0,0,.4);top:0;left:0;display:none;"></div>');

        }

        $("#alertMark").show();

    }

    if (act == "hide") {

        $("#alertMark").remove();

    }

}



function loading(act) {

    if (act == "start") {

        $("body").append('<div id="loaddiv" style="width:100%;text-align:center;height:55px;position:fixed;top:50%;margin-top:-25px;z-index:9999;"><img src='+WapSiteUrl+'/templates/default/images/loader.gif></div>');

        mark("show");

    }

    if (act == "stop") {

        $("#loaddiv").remove();

        mark("hide");

    }

}

//文字滚动

function scrollUp(tag, height, timer) {

    tag.each(function() {

        var ul = $(this).find("ul");

        setInterval(function() {

            ul.animate({

                marginTop: -height

            },

            "normal",

            function() {

                ul.find("li:first").clone().appendTo(ul);

                ul.find("li:first").remove();

                ul.css({

                    marginTop: 0

                });

            });

        },

        timer);

    });

};

function buildUrl(e, t) {

    switch (e) {

    case "keyword":

        return ApiUrl + "/index.php?con=goods&fun=list&keyword=" + encodeURIComponent(t);

    case "special":

        return ApiUrl + "/special.html?special_id=" + t;

    case "goods":

        return ApiUrl + "/index.php?con=goods&fun=detail&goods_id=" + t;

    case "points":

        return ApiUrl + "/index.php?con=points&fun=detail&pgoods_id=" + t;

    

    case "url":

        return t

    }

    return ApiUrl

}

function errorTipsShow(e) {

    $(".error-tips").html(e).show();

    setTimeout(function() {

        errorTipsHide()

    },

    3e3)

}

function errorTipsHide() {

    $(".error-tips").html("").hide()

}

function writeClear(e) {

    

    if (e.val().length > 0) {

        e.parent().addClass("write")

    } else {

        e.parent().removeClass("write")

    }

    btnCheck(e.parents("form"))

}

function btnCheck(e) {

    var t = true;

    e.find("input").each(function() {

        if ($(this).hasClass("no-follow")) {

            return

        }

        if ($(this).val().length == 0) {

            t = false

        }

    });

    if (t) {

        e.find(".btn").parent().addClass("ok")

    } else {

        e.find(".btn").parent().removeClass("ok")

    }

}

function getSearchName() {

    var e = decodeURIComponent(getQueryString("keyword"));

    if (e == "") {

        if (getCookie("deft_key_value") == null) {

            $.getJSON(ApiUrl + "/index.php?con=index&fun=search_hot_info",

            function(e) {

                var t = e.datas.hot_info;

                if (typeof t.name != "undefined") {

                    $("#keyword").attr("placeholder", t.name);

                    $("#keyword").html(t.name);

                    addCookie("deft_key_name", t.name, 1);

                    addCookie("deft_key_value", t.value, 1)

                } else {

                    addCookie("deft_key_name", "", 1);

                    addCookie("deft_key_value", "", 1)

                }

            })

        } else {

            $("#keyword").attr("placeholder", getCookie("deft_key_name"));

            $("#keyword").html(getCookie("deft_key_name"))

        }

    }

}

function getFreeVoucher(e) {

    var t = getCookie("key");

    if (!t) {

        checkLogin(0);

        return

    }

    $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=member_voucher&fun=voucher_freeex",

        data: {

            tid: e,

            key: t

        },

        dataType: "json",

        success: function(e) {

            checkLogin(e.login);

            var t = "领取成功";

        

            if (e.datas.error) {

                t = "领取失败：" + e.datas.error;

              

            }

            layer.open({

                content:t,

                time:1.5

            })

     

        }

    })

}

function updateCookieCart(e) {

    var t = decodeURIComponent(getCookie("goods_cart"));

    if (t) {

        $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=member_cart&fun=cart_batchadd",

            data: {

                key: e,

                cartlist: t

            },

            dataType: "json",

            async: false

        });

        delCookie("goods_cart")

    }

}

function getCartCount(e, t) {

    var a = 0;

    delCookie("cart_count")

    if (getCookie("key") !== null && getCookie("cart_count") === null) {

        var e = getCookie("key");

        $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=member_cart&fun=cart_count",

            data: {

                key: e

            },

            dataType: "json",

            async: false,

            success: function(e) {

                if (typeof e.datas.cart_count != "undefined") {

                    addCookie("cart_count", e.datas.cart_count, t);

                    a = e.datas.cart_count

                }

            }

        })

    } else {

        a = getCookie("cart_count")

    }

    if (a > 0 && $(".nctouch-nav-menu").has(".cart").length > 0) {

        $(".nctouch-nav-menu").has(".cart").find(".cart").parents("li").find("sup").show();

        $("#header-nav").find("sup").show()

    }

}
function getSellerChatCount() {



        var e = getCookie("sellerkey");

        if (e !== null) {

            $.getJSON(ApiUrl + "/index.php?con=seller_chat&fun=get_msg_count", {

                key: e

            },

            function(e) {

                if (e.datas > 0) {

                    $("#header").find(".message").parent().find("sup").show();

                    $("#header-nav").find("sup").show()

                }

            })

        }

        $("#header").find(".message").parent().click(function() {

            window.location.href = WapSiteUrl + "/index.php?act=seller_chat&fun=list"

        })



}

function getChatCount() {

    if ($("#header").find(".message").length > 0) {

        var e = getCookie("key");

        if (e !== null) {

            $.getJSON(ApiUrl + "/index.php?con=member_chat&fun=get_msg_count", {

                key: e

            },

            function(e) {

                if (e.datas > 0) {

                    $("#header").find(".message").parent().find("sup").show();

                    $("#header-nav").find("sup").show()

                }

            })

        }

        $("#header").find(".message").parent().click(function() {

            window.location.href = WapSiteUrl + "/index.php?act=member_chat&fun=list"

        })

    }

}

$(function() {

    $(".input-del").click(function() {

        $(this).parent().removeClass("write").find("input").val("");

        btnCheck($(this).parents("form"))

    });

    $("body").on("click", "label",

    function() {

        if ($(this).has('input[type="radio"]').length > 0) {

            $(this).addClass("checked").siblings().removeAttr("class").find('input[type="radio"]').removeAttr("checked")

        } else if ($(this).has('[type="checkbox"]')) {

            if ($(this).find('input[type="checkbox"]').prop("checked")) {

                $(this).addClass("checked")

            } else {

                $(this).removeClass("checked")

            }

        }

    });

    if ($("body").hasClass("scroller-body")) {

        new IScroll(".scroller-body", {

            mouseWheel: true,

            click: true

        })

    }

    $("#header").on("click", "#header-nav",

    function() {

        if ($(".nctouch-nav-layout").hasClass("show")) {

            $(".nctouch-nav-layout").removeClass("show")

        } else {

            $(".nctouch-nav-layout").addClass("show")

        }

    });

    $("#header").on("click", ".nctouch-nav-layout",

    function() {

        $(".nctouch-nav-layout").removeClass("show")

    });

    $(document).scroll(function() {

        $(".nctouch-nav-layout").removeClass("show")

    });

    getSearchName();

    getCartCount();

    getChatCount();
    getSellerChatCount();
    $(document).scroll(function() {

        e()

    });

    $(".fix-block-r,footer").on("click", ".gotop",

    function() {

        btn = $(this)[0];

        this.timer = setInterval(function() {

            $(window).scrollTop(Math.floor($(window).scrollTop() * .8));

            if ($(window).scrollTop() == 0) clearInterval(btn.timer, e)

        },

        10)

    });

    function e() {

        if($(window).scrollTop() == 0 ){

            $("#goTopBtn").addClass("hide");

            // $('.filter-menu').css({'bottom':'4.2rem'});

            // $('.filter-top').css({'bottom':'4.2rem'});

        }else{

              $("#goTopBtn").removeClass("hide");

              // $('.filter-menu').css({'bottom':'6.6rem'});

              // $('.filter-top').css({'bottom':'6.6rem'});

        }

     

    }

}); (function($) {

    $.extend($, {

        scrollTransparent: function(e) {

            var t = {

                valve: "#header",

                scrollHeight: 50

            };

            var e = $.extend({},

            t, e);

            function a() {

                $(window).scroll(function() {

                    if ($(window).scrollTop() <= e.scrollHeight) {

                        $(e.valve).addClass("transparent").removeClass("posf")

                    } else {

                        $(e.valve).addClass("posf").removeClass("transparent")

                    }

                })

            }

            return this.each(function() {

                a()

            })()

        },

        areaSelected: function(options) {

            var defaults = {

                success: function(e) {}

            };

            var options = $.extend({},

            defaults, options);

            var ASID = 0;

            var ASID_1 = 0;

            var ASID_2 = 0;

            var ASID_3 = 0;

            var ASNAME = "";

            var ASINFO = "";

            var ASDEEP = 1;

            var ASINIT = true;

            function _init() {

                if ($("#areaSelected").length > 0) {

                    $("#areaSelected").remove()

                }

                var e = '<div id="areaSelected">' + '<div class="nctouch-full-mask left">' + '<div class="nctouch-full-mask-bg"></div>' + '<div class="nctouch-full-mask-block">' + '<div class="header">' + '<div class="header-wrap">' + '<div class="header-l"><a href="javascript:void(0);"><i class="back"></i></a></div>' + '<div class="header-title">' + "<h1>选择地区</h1>" + "</div>" + '<div class="header-r"><a href="javascript:void(0);"><i class="close"></i></a></div>' + "</div>" + "</div>" + '<div class="nctouch-main-layout">' + '<div class="nctouch-single-nav">' + '<ul id="filtrate_ul" class="area">' + '<li class="selected"><a href="javascript:void(0);">一级地区</a></li>' + '<li><a href="javascript:void(0);" >二级地区</a></li>' + '<li><a href="javascript:void(0);" >三级地区</a></li>' + "</ul>" + "</div>" + '<div class="nctouch-main-layout-a"><ul class="nctouch-default-list"></ul></div>' + "</div>" + "</div>" + "</div>" + "</div>";

                $("body").append(e);

                _getAreaList();

                _bindEvent();

                _close()

            }

            function _getAreaList() {

                $.ajax({

                    type: "get",

                    url: ApiUrl + "/index.php?con=area&fun=area_list",

                    data: {

                        area_id: ASID

                    },

                    dataType: "json",

                    async: false,

                    success: function(e) {

                        if (e.datas.area_list.length == 0) {

                            _finish();

                            return false

                        }

                        if (ASINIT) {

                            ASINIT = false

                        } else {

                            ASDEEP++

                        }

                        $("#areaSelected").find("#filtrate_ul").find("li").eq(ASDEEP - 1).addClass("selected").siblings().removeClass("selected");

                        checkLogin(e.login);

                        var t = e.datas;

                        var a = "";

                        for (var n = 0; n < t.area_list.length; n++) {

                            a += '<li><a href="javascript:void(0);" data-id="' + t.area_list[n].area_id + '" data-name="' + t.area_list[n].area_name + '"><h4>' + t.area_list[n].area_name + '</h4><span class="arrow-r"></span> </a></li>'

                        }

                        $("#areaSelected").find(".nctouch-default-list").html(a);

                        

                            if (typeof IScroll == "undefined") {

                                $.ajax({

                                    url: ApiUrl + "/templates/default/js/iscroll.js",

                                    dataType: "script",

                                    async: false

                                })

                            }

                            myScrollArea = new IScroll("#areaSelected .nctouch-main-layout-a", {

                                mouseWheel: true,

                                click: true

                            })

                        

                    }

                });

                return false

            }

            function _bindEvent() {

                $("#areaSelected").find(".nctouch-default-list").off("click", "li > a");

                $("#areaSelected").find(".nctouch-default-list").on("click", "li > a",

                function() {

                    ASID = $(this).attr("data-id");

                    eval("ASID_" + ASDEEP + "=$(this).attr('data-id')");

                    ASNAME = $(this).attr("data-name");

                    ASINFO += ASNAME + " ";

                    var _li = $("#areaSelected").find("#filtrate_ul").find("li").eq(ASDEEP);

                    _li.prev().find("a").attr({

                        "data-id": ASID,

                        "data-name": ASNAME

                    }).html(ASNAME);

                    if (ASDEEP == 3) {

                        _finish();

                        return false

                    }

                    _getAreaList()

                });

                $("#areaSelected").find("#filtrate_ul").off("click", "li > a");

                $("#areaSelected").find("#filtrate_ul").on("click", "li > a",

                function() {

                    if ($(this).parent().index() >= $("#areaSelected").find("#filtrate_ul").find(".selected").index()) {

                        return false

                    }

                    ASID = $(this).parent().prev().find("a").attr("data-id");

                    ASNAME = $(this).parent().prev().find("a").attr("data-name");

                    ASDEEP = $(this).parent().index();

                    ASINFO = "";

                    for (var e = 0; e < $("#areaSelected").find("#filtrate_ul").find("a").length; e++) {

                        if (e < ASDEEP) {

                            ASINFO += $("#areaSelected").find("#filtrate_ul").find("a").eq(e).attr("data-name") + " "

                        } else {

                            var t = "";

                            switch (e) {

                            case 0:

                                t = "一级地区";

                                break;

                            case 1:

                                t = "二级地区";

                                break;

                            case 2:

                                t = "三级地区";

                                break

                            }

                            $("#areaSelected").find("#filtrate_ul").find("a").eq(e).html(t)

                        }

                    }

                    _getAreaList()

                })

            }

            function _finish() {

                var e = {

                    area_id: ASID,

                    area_id_1: ASID_1,

                    area_id_2: ASID_2,

                    area_id_3: ASID_3,

                    area_name: ASNAME,

                    area_info: ASINFO

                };

                options.success.call("success", e);

                if (!ASINIT) {

                    $("#areaSelected").find(".nctouch-full-mask").addClass("right").removeClass("left")

                }

                return false

            }

            function _close() {

                $("#areaSelected").find(".header-l").off("click", "a");

                $("#areaSelected").find(".header-l").on("click", "a",

                function() {

                    $("#areaSelected").find(".nctouch-full-mask").addClass("right").removeClass("left")

                });

                return false

            }

            return this.each(function() {

                return _init()

            })()

        },

        animationLeft: function(e) {

            var t = {

                valve: ".animation-left",

                wrapper: ".nctouch-full-mask",

                scroll: ""

            };

            var e = $.extend({},

            t, e);

            function a() {

                $(e.valve).click(function() {

                    $(e.wrapper).removeClass("hide").removeClass("right").addClass("left");

                    if (e.scroll != "") {

                        if (typeof myScrollAnimationLeft == "undefined") {

                            if (typeof IScroll == "undefined") {

                                $.ajax({

                                    url: WapSiteUrl + "/templates/default/js/iscroll.js",

                                    dataType: "script",

                                    async: false

                                })

                            }

                            myScrollAnimationLeft = new IScroll(e.scroll, {

                                mouseWheel: true,

                                click: true

                            })

                        } else {

                            myScrollAnimationLeft.refresh()

                        }

                    }

                });

                $(e.wrapper).on("click", ".header-l > a",

                function() {

                    $(e.wrapper).addClass("right").removeClass("left")

                })

            }

            return this.each(function() {

                a()

            })()

        },

        animationUp: function(e) {

            var t = {

                valve: ".animation-up",

                wrapper: ".nctouch-bottom-mask",

                scroll: ".nctouch-bottom-mask-rolling",

                start: function() {},

                close: function() {}

            };

            var e = $.extend({},

            t, e);

            function a() {

                e.start.call("start");

                $(e.wrapper).removeClass("down").addClass("up");

                if (e.scroll != "") {

                    if (typeof myScrollAnimationUp == "undefined") {

                        if (typeof IScroll == "undefined") {

                            $.ajax({

                                url: WapSiteUrl + "/templates/default/js/iscroll.js",

                                dataType: "script",

                                async: false

                            })

                        }

                        myScrollAnimationUp = new IScroll(e.scroll, {

                            mouseWheel: true,

                            click: true

                        })

                    } else {

                        myScrollAnimationUp.refresh()

                    }

                }

            }

            return this.each(function() {

                if (e.valve != "") {

                    $(e.valve).on("click",

                    function() {

                        a()

                    })

                } else {

                    a()

                }

                $(e.wrapper).on("click", ".nctouch-bottom-mask-bg,.nctouch-bottom-mask-close",

                function() {

                    $(e.wrapper).addClass("down").removeClass("up");

                    e.close.call("close")

                })

            })()

        }

    })

})(Zepto);

$.fn.ajaxUploadImage = function(e) {

    var t = {

        url: "",

        data: {},

        start: function() {},

        success: function() {}

    };

    var e = $.extend({},

    t, e);

    var a;

    function n() {

        if (a === null || a === undefined) {

            alert("请选择您要上传的文件！");

            return false

        }

        return true

    }

    return this.each(function() {

        $(this).on("change",

        function() {

            var t = $(this);

            e.start.call("start", t);

            a = t.prop("files")[0];

            if (!n) return false;

            try {

                var r = new XMLHttpRequest;

                r.open("post", e.url, true);

                r.setRequestHeader("X-Requested-With", "XMLHttpRequest");

                r.onreadystatechange = function() {

                    if (r.readyState == 4) {

                        returnDate = $.parseJSON(r.responseText);

                        e.success.call("success", t, returnDate)

                    }

                };

                var i = new FormData;

                for (k in e.data) {

                    i.append(k, e.data[k])

                }

                i.append(t.attr("name"), a);

                result = r.send(i)

            } catch(o) {

                console.log(o);

                alert(o)

            }

        })

    })

};

function loadSeccode() {

    $("#codekey").val("");

    $.ajax({

        type: "get",

        url: ApiUrl + "/index.php?con=seccode&fun=makecodekey",

        async: false,

        dataType: "json",

        success: function(e) {

            $("#codekey").val(e.datas.codekey)

        }

    });

    $("#codeimage").attr("src", ApiUrl + "/index.php?con=seccode&fun=makecode&k=" + $("#codekey").val() + "&t=" + Math.random())

}

function favoriteStore(e) {

    var t = getCookie("key");

    if (e <= 0) {



      layer.open({

            content:'参数错误',

            time:1.5

        })

        return false

    }

    var a = false;

    $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=member_favorites_store&fun=favorites_add",

        data: {

            key: t,

            store_id: e

        },

        dataType: "json",

        async: false,

        success: function(e) {



            if (e.nologin) {

                checkLogin(0);

                return

            }

            if (e.code == 200) {

                a = true;

                layer.open({

                    content:'收藏成功!',

                    time:1.5

                });

            } else {

            

                layer.open({

                    content:e.datas.error,

                    time:1.5

                 })

            }

        }

    });

    return a

}

function dropFavoriteStore(e) {

    var t = getCookie("key");

    if (!t) {

        checkLogin(0);

        return

    }

    if (e <= 0) {

        

         layer.open({

            content:'参数错误',

            time:1.5

         })

        return false

    }

    var a = false;

    $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=member_favorites_store&fun=favorites_del",

        data: {

            key: t,

            store_id: e

        },

        dataType: "json",

        async: false,

        success: function(e) {

            if (e.code == 200) {

                a = true

            } else {

                layer.open({

                    content:e.datas.error,

                    time:1.5

                 })

            }

        }

    });

    return a

}

function favoriteGoods(e) {

    var t = getCookie("key");

    if (!t) {

        checkLogin(0);

        return

    }

    if (e <= 0) {

     

         layer.open({

            content:"参数错误",

            time:1.5

         })

        return false

    }

    var a = false;

    $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=member_favorites&fun=favorites_add",

        data: {

            key: t,

            goods_id: e

        },

        dataType: "json",

        async: false,

        success: function(e) {

            if (e.code == 200) {

                a = true

            } else {

                 layer.open({

                    content:e.datas.error,

                    time:1.5

                 })

            }

        }

    });

    return a

}

function dropFavoriteGoods(e) {

    var t = getCookie("key");

    if (!t) {

        checkLogin(0);

        return

    }

    if (e <= 0) {

       layer.open({

            content:"参数错误",

            time:1.5

         })

        return false

    }

    var a = false;

    $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=member_favorites&fun=favorites_del",

        data: {

            key: t,

            fav_id: e

        },

        dataType: "json",

        async: false,

        success: function(e) {

            if (e.code == 200) {

                a = true

            } else {

                  layer.open({

                    content:e.datas.error,

                    time:1.5

                 })

            }

        }

    });

    return a

}

function loadCss(e) {

    var t = document.createElement("link");

    t.setAttribute("type", "text/css");

    t.setAttribute("href", e);

    t.setAttribute("href", e);

    t.setAttribute("rel", "stylesheet");

    css_id = document.getElementById("auto_css_id");

    if (css_id) {

        document.getElementsByTagName("head")[0].removeChild(css_id)

    }

    document.getElementsByTagName("head")[0].appendChild(t)

}

function loadJs(e) {

    var t = document.createElement("script");

    t.setAttribute("type", "text/javascript");

    t.setAttribute("src", e);

    t.setAttribute("id", "auto_script_id");

    script_id = document.getElementById("auto_script_id");

    if (script_id) {

        document.getElementsByTagName("head")[0].removeChild(script_id)

    }

    document.getElementsByTagName("head")[0].appendChild(t)

}





/*过滤*/

function strFilter1(suc) {

    var re = /\b(and|or|exec|execute|insert|select|delete|update|alter|create|drop|count|\*|chr|char|asc|mid|substring|master|truncate|declare|xp_cmdshell|restore|backup|net +user|net +localgroup +administrators)\b/;

    return suc.replace(re, '').replace(/</gi, "＜").replace(/>/gi, "＞");

}



/*提示信息*/

 var TipMsg = {

     showTimer: null,

     position: function (msg, tag, timer, leftplus, topplus, direction) {/*"提示",$(this),2000,向左偏移,向上偏移,方向*/

         clearTimeout(this.showTimer);

         if ($("#tipBox").length==0) { $("body").append('<div id="tipBox"></div>'); } else { $("#tipBox").show(); }

         var tagOff = tag.offset() || tag.position(), the = $("#tipBox");

         the.html('<div>' + msg + '</div>')

         var h = the.height() + 30;

         var _direction = direction || "up";

         if (leftplus == null) { leftplus = 0 }

         if (topplus == null) { topplus = 0 }

         if (_direction == "up") {

             the.css({ top: tagOff.top - h - 20, left: tagOff.left + leftplus }).removeClass("downTip leftTip rightTip");

             the.fadeIn(300).animate({ top: tagOff.top - h + topplus }, 300);

         } else if (_direction == "down") {

             the.css({ top: tagOff.top + tag.outerHeight() + 10, left: tagOff.left + leftplus }).addClass("downTip");

             the.fadeIn(300).animate({ top: tagOff.top + tag.outerHeight() + topplus }, 300);

         } else if (_direction == "left") {

             the.css({ top: tagOff.top + topplus, left: tagOff.left - tag.outerWidth()-10 }).addClass("leftTip");

             the.fadeIn(300).animate({ left: tagOff.left - tag.outerWidth() - 10 + leftplus }, 300);

         } else if (_direction == "right") {

             the.css({ top: tagOff.top + topplus, left: tagOff.left + tag.outerWidth()+10 + leftplus }).addClass("rightTip");

             the.fadeIn(300).animate({ left: tagOff.left + tag.outerWidth() + 10 + leftplus }, 300);

         }

         if (timer != -1) {

             the.hover(function () { clearTimeout(TipMsg.showTimer); }, function () {

                 TipMsg.showTimer = setTimeout(function () {

                     the.fadeOut(300, function () { the.remove(); });

                 }, timer);

             });

             TipMsg.showTimer = setTimeout(function () {

                 the.fadeOut(300, function () { the.remove(); });

             }, timer);

         }

     }

    

 };

 /*提示框*/

var wintip = null;

function trim(str) { return String(str).replace(/^\s+|\s+$/, '') }

function $GET(id) { return document.getElementById(id) || null }

function showtip(msg, obj, opt, time) {

    if (time == undefined) {

        time = 5000;

    }

    if (typeof opt == 'object') {

        if (opt['left'] == null) { opt.left = 0 }

        if (opt['top'] == null) { opt.top = 0 }

    } else { opt = { left: 0, top: 0 } }

    if (!wintip) { if ($GET('tipdiv')) { wintip = $GET('tipdiv') } }

    if (!wintip) {

        var wintip = document.createElement("span");

        wintip.id = "tipdiv";

        var wintipmsg = document.createElement("span");

        wintipmsg.id = "tipdiv_msg";

        wintip.appendChild(wintipmsg);

        wintip.posoff = { offx: 0, offy: 0 }

        wintip.msgobj = wintipmsg;

        wintip.hide = function () { this.style.display = 'none'; this.msgobj.innerHTML = ''; }

        document.body.appendChild(wintip);

        wintip.hide();

        wintip.position = function (posobj) {

            var pos = { left: parseInt(posobj.offsetLeft), top: parseInt(posobj.offsetTop) }

            while (posobj = posobj.offsetParent) {

                pos.left += posobj.offsetLeft;

                pos.top += posobj.offsetTop

            }

            this.style.left = ((pos.left - 20) + opt.left) + 'px';

            this.style.top = ((pos.top - 36) + opt.top) + 'px';

            return this;

        }

        wintip.posshow = function (time, posobj) {

            this.style.display = document.all ? '' : 'block';

            this.position(posobj);

            clearTimeout(wintip.timer)

            wintip.timer = setTimeout(function () { $("#tipdiv").remove(); }, time);

            return this;

        }

        wintip.setmsg = function (msg) { this.msgobj.innerHTML = msg; return this; }

    }

    wintip.setmsg(msg).posshow(time || 2500, obj);

}

//代金券兑换

function ajax_cashing(vid,obj){

   $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=points&fun=voucherexchange_save",

        data: {

            vid: vid

        },

        dataType: "json",

        async: false,

        success: function(e) {

            if (e.code == 200) {

               layer.open({

                    content:e.datas,

                    time:1.5

                 })

            } else {

              if(e.datas.error.url){

                setTimeout(function () {

                    window.location.href = e.datas.error.url;   

                }, 1000); 

              }else{

                   layer.open({

                    content:e.datas.error,

                    time:1.5

                 })

              }





               

            }

        }

    });

}



$(function(){

    $(".logoutbtn").click(function() {

        var e = getCookie("key");

        var i = "wap";

        layer.open({

        content: '您确定要退出吗？',

        btn: ['嗯', '不要'],

        yes: function(index){

         $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=logout",

            data: {

                key: e,

                client: i

            },

            success: function(a) {

                if (a) {

                    delCookie("key");

                    delCookie("cart_count");

                    delCookie("zomzc");

                    delCookie("username");

                    delCookie("tt520_member_avatar");

                    layer.close(index);

                    location.href = WapSiteUrl;

                }

            }

        })

        

      },no:function(index){

         layer.close(index);

      }



    })

  })

 $(".logoutseller").click(function() {

    

        var i = "wap";

        layer.open({

        content: '您确定要退出吗？',

        btn: ['嗯', '不要'],

        yes: function(index){

         $.ajax({

            type: "post",

            url: ApiUrl + "/index.php?con=seller_logout",

            data: {

     

                client: i

            },

            success: function(a) {

                if (a) {

                    delCookie("sellerkey");

                    delCookie("key");

                    delCookie("seller_name");

                    delCookie("store_name");

                    delCookie("cart_count");

                    delCookie("zomzc");

                    delCookie("username");

                    delCookie("tt520_member_avatar");

                    layer.close(index);

                    location.href = WapUrl;

                }

            }

        })

        

      },no:function(index){

         layer.close(index);

      }



    });

  });

    /*悬浮菜单点击显示*/

    $(".filter-menu-title").click(function() {

        $(".filter-menu").toggleClass("active");

    });

    $("#home-div-link").click(function(){

        window.location.href=ApiUrl;

    })

    $("#class-div-link").click(function(){

      window.location.href=ApiUrl+"/index.php?con=goods_class";  

    })

    $("#search-div-link").click(function(){

      window.location.href=ApiUrl+"/index.php?con=goods&fun=search";     

    })

    $("#cart-div-link").click(function(){

        window.location.href=ApiUrl+"/index.php?con=cart";     

    })

    $("#member-div-link").click(function(){

        window.location.href=ApiUrl+"/index.php?con=member";    

    })

})
