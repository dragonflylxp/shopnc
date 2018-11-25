var page = pagesize;
var curpage = 1;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString("keyword"));
var key = getQueryString("key");
var order = getQueryString("order");
var points_min = getQueryString("points_min");
var points_max = getQueryString("points_max");
var level  = getQueryString("level");
var isable  = getQueryString("isable");
var myDate = new Date;
var searchTimes = myDate.getTime();
$(function() {
    $.animationLeft({
        valve: "#search_adv",
        wrapper: ".nctouch-full-mask",
        scroll: "#list-items-scroll"
    });
    $("#header").on("click", ".header-inp",
    function() {
        location.href = ApiUrl + "/index.php?con=goods&fun=search&keyword=" + keyword
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
   if (keyword != "") {
        param.keyword = keyword
    }
    if (key != "") {
        param.key = key
    }
    if (order != "") {
        param.order  = order
    }
  
     if (points_min != "") {
        param.points_min  = points_min
    }
     if (points_max != "") {
        param.points_max  = points_max
    }
     if (level != "") {
        param.level  = level
    }
     if (isable != "") {
        param.isable  = 1
    }

    
    $.getJSON(ApiUrl + "/index.php?con=points&fun=get_gifts", param,
    function(e) {
        if (!e) {
            e = [];
            e.datas = [];
            e.datas.pointprod_list = [];

        }
           $.each(e.datas, function(k, v) {

                $.each(v, function(kk, vv) {
                  
                            vv.url = buildUrl('points', vv.pgoods_id);
            

                    });
                });
        
      
        curpage++;
        $(".pre-loading").hide();
        var r = template.render("home_body", e);
        $("#duihuanList").append(r);
        hasmore = e.hasmore
    })
}
function search_adv() {
    $.getJSON(ApiUrl + "/index.php?con=points&fun=get_lever",
    function(e) {
        var r = e.datas;
        $("#list-items-scroll").html(template.render("search_items", r));
    
        if (points_max) {
            $("#points_max").val(points_max)
        }
        if (points_min) {
            $("#points_min").val(points_min)
        }
        if (keyword) {
          $("#keyword").val(keyword);
        }
        if (level) {
          $(".level").each(function(){
            var lv = $(this).attr('level');
            if(lv == level){
                $(this).addClass('current');
            }
          })
        }
         if (isable) {
          $("#isable").addClass('current');
        }
        $("#search_submit").click(function() {
            var lvs = $("#click_level .current").attr('level');
            var e = '';
            r = "";
             
            if ($("#keyword").val() != "") {
                e += "&keyword=" + $("#keyword").val();
                
            }
            if ($("#points_min").val() != "") {
                e += "&points_min=" + $("#points_min").val()
            }
            if ($("#points_max").val() != "") {
                e += "&points_max=" + $("#points_max").val()
            }
            if (lvs) {
                
                
                e += "&level="+lvs;
            }
            if ($("#isable").hasClass('current')) {
                e += "&isable=1"
            }
       
     
           window.location.href = ApiUrl + "/index.php?con=points&fun=gifts" + e
        });
        $('a[nctype="items"]').click(function() {
            var e = new Date;
            if (e.getTime() - searchTimes > 300) {
                $(this).toggleClass("current");
                searchTimes = e.getTime()
            }
        });
         $('a[nctype="level"]').click(function() {

            if($(this).hasClass('current')){
                $(this).removeClass("current");
            }else{
                $(this).addClass("current").siblings().removeClass("current");
            }
           
        });
        $('input[nctype="price"]').on("blur",
        function() {
            if ($(this).val() != "" && !/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test($(this).val())) {
                $(this).val("")
            }
        });
        $("#reset").click(function() {
            $('a[nctype="items"]').removeClass("current");
            $('input[nctype="price"]').val("");
            $("#click_level a").removeClass("current");
            $('#keyword').val();
        })
    })
}
function init_get_list(e, r) {
    order = e;
    key = r;
    curpage = 1;
    hasmore = true;
    $(".pre-loading").show();
    $("#duihuanList").html("");
    get_list()
}