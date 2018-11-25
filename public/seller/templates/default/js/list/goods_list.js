var page = pagesize;
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = "";
$(function() {
    if (getQueryString("data-state") != "") {
        $("#filtrate_ul").find("li").has('a[data-state="' + getQueryString("data-state") + '"]').addClass("selected").siblings().removeClass("selected")
    }

    $("#search_btn").click(function() {
        reset = true;
        t()
    });

    $("#fixed_nav").waypoint(function() {
        $("#fixed_nav").toggleClass("fixed")
    },{
        offset: "50"
    });

    function t() {
        if (reset) {
            curpage = 1;
            hasMore = true
        }

        $(".loading").remove();
        if (!hasMore) {
            return false
        }
        hasMore = false;
        var t = $("#filtrate_ul").find(".selected").find("a").attr("data-state");
        var keyword = $("#keyword").val();
        $.ajax({

            type: "post",

            url:ApiUrl + "/index.php?con=seller_goods&fun=goods_list&page=" + page + "&curpage=" + curpage,

            data: {

                goods_type: t,

                keyword: keyword

            },

            dataType: "json",

            success: function(e) {

                $("#loding").hide();

                // checkLogin(e.login);

                curpage++;

                hasMore = e.hasmore;

                // if (!hasMore) {

                //     return false;

                // }

               

                var t = e;

                t.ApiUrl = ApiUrl;

   

                template.helper("$getLocalTime",

                function(e) {

                    var t = new Date(parseInt(e) * 1e3);

                    var r = "";

                    r += t.getFullYear() + "年";

                    r += t.getMonth() + 1 + "月";

                    r += t.getDate() + "日 ";

                    r += t.getHours() + ":";

                    r += t.getMinutes();

                    return r

                });

                template.helper("p2f",

                function(e) {

                    return (parseFloat(e) || 0).toFixed(2)

                });

                template.helper("parseInt",

                function(e) {

                    return parseInt(e)

                });

                var r = template.render("order-list-tmpl", t);

                if (reset) {

                    reset = false;

                    $("#goods_list").html(r)

                } else {

                    $("#goods_list").append(r)

                }

            }

        })

    }

   $("body").on("click", "em",function() {

        if (!$(this).hasClass("checked")) {

            var url =  ApiUrl + "/index.php?con=seller_goods&fun=goods_show";

            var success = '上架成功!';

            $(this).addClass("checked");

        } else {

            var url =  ApiUrl + "/index.php?con=seller_goods&fun=goods_unshow";

            var success = '下架成功!';

            $(this).removeClass("checked");

        }

         var goods_commonid = $(this).parents('li').attr('goods_id');

         

           $.ajax({

                type: "post",

                url: url,

                data: {

                    commonids:goods_commonid,

         

                },

                dataType: "json",

                async: false,

                success: function(e) {

                    if (e.code == 200) {

                        layer.open({

                            content:success,

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

    });

     $("body").on("click", ".liinfo",function() {

        

        $(this).siblings('.list_zz').show();

    })
     $("body").on("click",".list_zz",function(){
        $(this).hide();
     })

     $("body").on("click",".zz_list_del",function(event){

         event.stopPropagation();

        var goods_id = $(this).parents('.list_zz').attr('goods_id');



         $.ajax({

                type: "post",

                url:ApiUrl + "/index.php?con=seller_goods&fun=goods_drop",

                data: {

                    commonids:goods_id,

         

                },

                dataType: "json",

                async: false,

                success: function(e) {

                    if (e.code == 200) {

                        layer.open({

                            content:'删除成功!',

                            time:1.5

                        });

                         $(".goods_id_"+goods_id).remove();

                    } else {

                    

                        layer.open({

                            content:e.datas.error,

                            time:1.5

                         })

                       

                    }

                }

            });

 

     })

     $("body").on("click",".zz_list_edit",function(event){

         var goods_id = $(this).parents('.list_zz').attr('goods_id');

        window.location.href = ApiUrl + "/index.php?con=seller_goods&fun=edit&goods_id="+goods_id;

     })

     $("body").on("click",".zz_list_xq",function(event){

        var goods_id = $(this).parents('.list_zz').attr('goods_id');

        window.location.href = WapUrl + "/tmpl/product_detail.html?goods_id="+goods_id;

     })

    $("#filtrate_ul").find("a").click(function() {

        $("#filtrate_ul").find("li").removeClass("selected");

        $(this).parent().addClass("selected").siblings().removeClass("selected");

        reset = true;

        window.scrollTo(0, 0);

        t()

    });

    t();

    $(window).scroll(function() {

        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {

            t()

        }

    })

});

