var page = pagesize;
var curpage = 1;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString("keyword"));
var key = getQueryString("key");
var order = getQueryString("order");
var sc_id = getQueryString("sc_id");
var area_id = decodeURIComponent(getQueryString("area_info"));
var myDate = new Date;
var searchTimes = myDate.getTime();
$(function() {
     $("#area_info").on("click",
        function() {
            
            $.areaSelected({
                success: function(a) {
                    $("#area_info").val(a.area_info).attr({
                        "data-areaid": a.area_id,
                        "data-areaid2": a.area_id_2 == 0 ? a.area_id_1: a.area_id_2
                    })
                }
            })
    });
    
    if (keyword != "") {
        $("#keyword").html(keyword)
    }


    get_list();
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            get_list()
        }
    });
    $('#serach_store').click(function(){
        var keyword =$('#keyword').val();
        var area_info = $('#area_info').val();
        location.href = ApiUrl+'/index.php?con=shop&keyword='+keyword+'&area_info='+area_info;
    });
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
    if (area_id != "") {
        param.area_id  = area_id
    }
     if (sc_id != "") {
        param.sc_id  = sc_id
    }
     
    $.getJSON(ApiUrl + "/index.php?con=shop&fun=shop_list", param,
    function(e) {
        if (!e) {
            e = [];
            e.datas = [];
            e.datas.store_list = [];
            e.datas.search_list_goods =[];

        }
     
        curpage++;

        template.helper('formatnum', function(str) {
           
            return parseInt(str);
              
        });
        $('.pre-loading').hide();
        e.datas.ApiUrl = ApiUrl;
        var html = template.render('category-one', e.datas);
        $("#categroy-cnt").append(html);
        hasmore = e.hasmore
    })
}

function init_get_list(e, r) {
    order = e;
    key = r;
    curpage = 1;
    hasmore = true;
    $(".favorites-store-list").html("");
    $('.pre-loading').show();
    get_list()
}