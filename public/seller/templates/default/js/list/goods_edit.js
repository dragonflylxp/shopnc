(function($) {
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

        cateSelected: function(options) {

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

                var e = '<div id="areaSelected">' + '<div class="nctouch-full-mask left">' + '<div class="nctouch-full-mask-bg"></div>' + '<div class="nctouch-full-mask-block">' + '<div class="header">' + '<div class="header-wrap">' + '<div class="header-l"><a href="javascript:void(0);"><i class="back"></i></a></div>' + '<div class="header-title">' + "<h1>选择分类</h1>" + "</div>" + '<div class="header-r"><a href="javascript:void(0);"><i class="close"></i></a></div>' + "</div>" + "</div>" + '<div class="nctouch-main-layout">' + '<div class="nctouch-single-nav">' + '<ul id="filtrate_ul" class="area">' + '<li class="selected"><a href="javascript:void(0);">一级分类</a></li>' + '<li><a href="javascript:void(0);" >二级分类</a></li>' + '<li><a href="javascript:void(0);" >三级分类</a></li>' + "</ul>" + "</div>" + '<div class="nctouch-main-layout-a"><ul class="nctouch-default-list"></ul></div>' + "</div>" + "</div>" + "</div>" + "</div>";

                $("body").append(e);

                _getAreaList();

                _bindEvent();

                _close()

            }

            function _getAreaList() {

                $.ajax({

                    type: "get",

                    url: ApiUrl + "/index.php?con=seller_goods&fun=ajax_goods_class",

                    data: {

                        gc_id: ASID,

                        deep: ASDEEP

                    },

                    dataType: "json",

                    async: false,

                    success: function(e) {

                        if (e.datas.class_list.length == 0) {

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

                        for (var n = 0; n < t.class_list.length; n++) {

                            a += '<li><a href="javascript:void(0);" data-id="' + t.class_list[n].gc_id + '" data-name="' + t.class_list[n].gc_name + '" data-deep="' + ASDEEP + '"><h4>' + t.class_list[n].gc_name + '</h4><span class="arrow-r"></span> </a></li>'

                        }

                        $("#areaSelected").find(".nctouch-default-list").html(a);

                        if (typeof myScrollArea == "undefined") {

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

                        } else {

                            myScrollArea.refresh()

                        }

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

                    ASINFO += ASNAME + ">";

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

                                t = "一级分类";

                                break;

                            case 1:

                                t = "二级分类";

                                break;

                            case 2:

                                t = "三级分类";

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

                    area_info:ASINFO.substring(0,ASINFO.length-1)

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


// 计算折扣

jQuery('input[name="g_price"],input[name="g_marketprice"]').change(function(){

    discountCalculator();

});
//删除图片
$("body").on('click','.upload_del',function(){
    var sal = $(this);
    sal.siblings('a').find('.pic-thumb').remove();
    sal.siblings('input').val('');
    sal.remove();
})

// 计算折扣

function discountCalculator() {

    var _price = parseFloat($('input[name="g_price"]').val());

    var _marketprice = parseFloat($('input[name="g_marketprice"]').val());

    if(_price>_marketprice){

      layer.open({content:'售价不得高于市场价!'});

      return;

    }

    if((!isNaN(_price) && _price != 0) && (!isNaN(_marketprice) && _marketprice != 0)){

        var _discount = parseInt(_price/_marketprice*100);

        $('input[name="g_discount"]').val(_discount);

    }

}

$("#g_cate").on("click",function() {
    $.cateSelected({
        success: function(a) {
           console.log(a);
            $("#g_cate").text(a.area_info);
            $("input[name='cate_name']").val(a.area_info);
            $("input[name='cate_id']").val(a.area_id);
        

        }

    })

})

$("#edit_goods").click(function(){

    $("#sub_goods").submit();

})