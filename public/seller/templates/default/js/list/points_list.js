var page = pagesize;
var curpage = 1;
var hasmore = true;
var footer = false;
var key = getQueryString("key");
var order = getQueryString("order");
var points_min = getQueryString("points_min");
var points_max = getQueryString("points_max");
var price  = getQueryString("price");
var sc_id = getQueryString("sc_id");
var isable  = getQueryString("isable");
var myDate = new Date;
var searchTimes = myDate.getTime();
$(function() {
    $.animationLeft({
        valve: "#search_adv",
        wrapper: ".nctouch-full-mask",
        scroll: "#list-items-scroll"
    });

     $("#nav_ul").find("a").click(function() {
        $(this).addClass("current").parent().siblings().find("a").removeClass("current");
        if (!$("#sort_inner").hasClass("hide") && $(this).parent().index() > 0) {
            $("#sort_inner").addClass("hide")
        }
    });

    get_list();
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            get_list()
        }
    });
    search_adv()
});
function get_list() {

    if (!hasmore) {
        return false
    }
    hasmore = false;
    param = {};
    param.page = page;
    param.curpage = curpage;
 
    if (key != "") {
        param.key = key
    }
    if (order != "") {
        param.order  = order
    }
    if (sc_id != "") {
        param.sc_id  = sc_id
    }
     if (points_min != "") {
        param.points_min  = points_min
    }
     if (points_max != "") {
        param.points_max  = points_max
    }
     if (price != "") {
        param.price  = price
    }
     if (isable != "") {
        param.isable  = 1
    }

    
    $.getJSON(ApiUrl + "/index.php?con=points&fun=get_list", param,
    function(e) {
        if (!e) {
            e = [];
            e.datas = [];
            e.datas.pointprod_list = [];

        }
        curpage++;
        $(".pre-loading").hide();
        var r = template.render("home_body", e);
        $(".pointsclub").append(r);
        hasmore = e.hasmore
    })
}
function search_adv() {
    $.getJSON(ApiUrl + "/index.php?con=points&fun=get_priceList",
    function(e) {
        var r = e.datas;
        $("#list-items-scroll").html(template.render("search_items", r));
       if (sc_id) {
            $("#sc_id").val(sc_id)
        }
        if (points_max) {
            $("#points_max").val(points_max)
        }
        if (points_min) {
            $("#points_min").val(points_min)
        }
      
        if (price) {
          $(".mprice").each(function(){
            var lv = $(this).attr('price');
            if(lv == price){
                $(this).addClass('current');
            }
          })
        }
         if (isable) {
          $("#isable").addClass('current');
        }
        $("#search_submit").click(function() {
            var lvs = $("#click_level .current").attr('price');
            var e ='';
             r = "";
             if( $("#sc_id").val() !=""){
                 e += "&sc_id=" + $("#sc_id").val();
             }
            
           
            if ($("#points_min").val() != "") {
                e += "&points_min=" + $("#points_min").val()
            }
            if ($("#points_max").val() != "") {
                e += "&points_max=" + $("#points_max").val()
            }
            if (lvs) {
                
                
                e += "&price="+lvs;
            }
            if ($("#isable").hasClass('current')) {
                e += "&isable=1"
            }
       
      
          window.location.href = ApiUrl + "/index.php?con=points&fun=list" + e
        });
        $('a[nctype="items"]').click(function() {
            var e = new Date;
            if (e.getTime() - searchTimes > 300) {
                $(this).toggleClass("current");
                searchTimes = e.getTime()
            }
        });
         $('a[nctype="mprice"]').click(function() {

            if($(this).hasClass('current')){
                $(this).removeClass("current");
            }else{
                $(this).addClass("current").siblings().removeClass("current");
            }
           
        });
        $('input[nctype="mprice"]').on("blur",
        function() {
            if ($(this).val() != "" && !/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test($(this).val())) {
                $(this).val("")
            }
        });
        $("#reset").click(function() {
            $('a[nctype="items"]').removeClass("current");
            $('input[nctype="price"]').val("");
            $("#click_level a").removeClass("current");
            $("#sc_id").val("")
        })
    })
}
function init_get_list(e, r) {
    order = e;
    key = r;
    curpage = 1;
    hasmore = true;
    $(".pre-loading").show();
    $(".pointsclub").html("");
    get_list()
}