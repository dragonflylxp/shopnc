function tidyStoreNewGoodsData(t) {
    if (t.goods_list.length <= 0) {
        return t
    }
    var e = $("#newgoods").find('[addtimetext="' + t.goods_list[0].goods_addtime_text + '"]');
    var o = "";
    $.each(t.goods_list,
    function(s, r) {
        if (o != r.goods_addtime_text && e.html() == null) {
            t.goods_list[s].goods_addtime_text_show = r.goods_addtime_text;
            o = r.goods_addtime_text
        }
    });
    return t
}
$(function() {
    var t = getCookie("key");
    var e = getQueryString("store_id");
    if (!e) {
        window.location.href = WapSiteUrl; 
    }
    $("#goods_search").attr("href", ApiUrl + "/index.php?con=store&fun=store_search&store_id=" + e);
    $("#store_categroy").attr("href", ApiUrl + "/index.php?con=store&fun=store_search&store_id=" + e);
    $("#store_intro").attr("href",ApiUrl + "/index.php?con=store&fun=store_intro&store_id=" + e);
    function o() {
        $("#store_sliders").each(function() {
            if ($(this).find(".item").length < 2) {
                return
            }
            Swipe(this, {
                startSlide: 2,
                speed: 400,
                auto: 3e3,
                continuous: true,
                disableScroll: false,
                stopPropagation: false,
                callback: function(t, e) {},
                transitionEnd: function(t, e) {}
            })
        })
    }
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?con=store&fun=store_info",
        data: {
            key: t,
            store_id: e
        },
        dataType: "json",
        success: function(t) {
            var e = t.datas;
            var s = e.store_info.store_name + " - 店铺首页";
            document.title = s;
            var r = template.render("store_banner_tpl", e);
            $("#store_banner").html(r);
            if (e.store_info.is_favorate) {
                $("#store_notcollect").hide();
                $("#store_collected").show()
            } else {
                $("#store_notcollect").show();
                $("#store_collected").hide()
            }
            if (e.store_info.mb_title_img) {
                $(".store-top-bg .img").css("background-image", "url(" + e.store_info.mb_title_img + ")")
            } else {
                var a = [];
                a[0] = WapSiteUrl + "/images/store_h_bg_01.jpg";
                a[1] = WapSiteUrl + "/images/store_h_bg_02.jpg";
                a[2] = WapSiteUrl + "/images/store_h_bg_03.jpg";
                a[3] = WapSiteUrl + "/images/store_h_bg_04.jpg";
                a[4] = WapSiteUrl + "/images/store_h_bg_05.jpg";
                var i = Math.round(Math.random() * 4);
                $(".store-top-bg .img").css("background-image", "url(" + a[i] + ")")
            }
            if (e.store_info.mb_sliders.length > 0) {
                e.ApiUrl = ApiUrl;
                var r = template.render("store_sliders_tpl", e);
                $("#store_sliders").html(r);
                o()
            } else {
                $("#store_sliders").parent().hide()
            }
            // $("#store_kefu").click(function() {
            //     window.location.href = WapSiteUrl + "/tmpl/member/chat_info.html?t_id=" + t.datas.store_info.member_id
            // });
            var r = template.render("goods_recommend_tpl", e);
            $("#goods_recommend").html(r)
        }
    });
    $("#goods_rank_tab").find("a").click(function() {
        $("#goods_rank_tab").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        var t = $(this).attr("data-type");
        var o = t + "desc";
        var s = 3;
        $("[nc_type='goodsranklist']").hide();
        $("#goodsrank_" + t).show();
        if ($("#goodsrank_" + t).html()) {
            return
        }
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=store&fun=store_goods_rank",
            data: {
                store_id: e,
                ordertype: o,
                num: s
            },
            dataType: "json",
            success: function(e) {
                if (e.code == 200) {
                    var o = template.render("goodsrank_" + t + "_tpl", e.datas);
                    $("#goodsrank_" + t).html(o)
                }
            }
        })
    });
    $("#goods_rank_tab").find("a[data-type='collect']").trigger("click");
    $("#nav_tab").waypoint(function() {
        $("#nav_tab_con").toggleClass("fixed")
    },
    {
        offset: "50"
    });
    function s() {
        var t = {};
        t.store_id = e;
        var o = new ncScrollLoad;
        o.loadInit({
            url: ApiUrl + "/index.php?con=store&fun=store_new_goods",
            getparam: t,
            tmplid: "newgoods_tpl",
            containerobj: $("#newgoods"),
            iIntervalId: true,
            resulthandle: "tidyStoreNewGoodsData"
        })

       
    }
    function r() {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?con=store&fun=store_promotion",
            data: {
                store_id: e
            },
            dataType: "json",
            success: function(t) {
                t.datas.store_id = e;
                var o = template.render("storeactivity_tpl", t.datas);
                $("#storeactivity_con").html(o)
            }
        })
    }
    $("#nav_tab").find("a").click(function() {
        $("#nav_tab").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        $("#storeindex_con,#allgoods_con,#newgoods_con,#storeactivity_con").hide();
        window.scrollTo(0, 0);
        var t = $(this).attr("data-type");
        switch (t) {
        case "storeindex":
            $("#storeindex_con").show();
            o();
            break;
        case "allgoods":
            if (!$("#allgoods_con").html()) {
                $("#allgoods_con").load(ApiUrl + "/index.php?con=store&fun=store_goods_list",
                function() {
                    $(".goods-search-list-nav").addClass("posr");
                    $(".goods-search-list-nav").css("top", "0");
                    $("#sort_inner").css("position", "static")
                })
            }
            $("#allgoods_con").show();
            break;
        case "newgoods":
            if (!$("#newgoods").html()) {
                s()
            }
            $("#newgoods_con").show();
            break;
        case "storeactivity":
            if (!$("#storeactivity_con").html()) {
                r()
            }
            $("#storeactivity_con").show();
            break
        }
    });
    $("#store_voucher").click(function() {
        if (!$("#store_voucher_con").html()) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=voucher&fun=voucher_tpl_list",
                data: {
                    store_id: e,
                    gettype: "free"
                },
                dataType: "json",
                async: false,
                success: function(t) {
                    if (t.code == 200) {
                        var e = template.render("store_voucher_con_tpl", t.datas);
                        $("#store_voucher_con").html(e)
                    }
                }
            })
        }
        $.animationUp({
            valve: ""
        })
    });
    $("#store_voucher_con").on("click", '[nc_type="getvoucher"]',
    function() {
        getFreeVoucher($(this).attr("data-tid"))
    });
    $("#store_notcollect").live("click",
    function() {
        var t = favoriteStore(e);
        if (t) {
            $("#store_notcollect").hide();
            $("#store_collected").show();
            var o;
            var s = (o = parseInt($("#store_favornum_hide").val())) > 0 ? o + 1 : 1;
            $("#store_favornum").html(s);
            $("#store_favornum_hide").val(s)
        }
    });
    $("#store_collected").live("click",
    function() {
        var t = dropFavoriteStore(e);
        if (t) {
            $("#store_collected").hide();
            $("#store_notcollect").show();
            var o;
            var s = (o = parseInt($("#store_favornum_hide").val())) > 1 ? o - 1 : 0;
            $("#store_favornum").html(s);
            $("#store_favornum_hide").val(s)
        }
    })
});