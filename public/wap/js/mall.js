var page = pagesize;
var curpage = 1;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString("keyword"));
var sc_id = getQueryString("sc_id");
var area_info = decodeURIComponent(getQueryString("area_info"));
var myDate = new Date;
var searchTimes = myDate.getTime();
$(function() {
	$.animationLeft({
		valve: "#search_adv",
		wrapper: ".nctouch-full-mask2",
		scroll: "#list-items-scroll"
	});
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

	$("#search_btn").click(function() {
		var area_info = encodeURIComponent($('#area_info').val());
		if(area_info==0){
			var area_info= 0;
		}
		var sc=getQueryString("sc_id");
		if(sc==0){
			var sc= 0;
		}
		var e = $("#mall_keyword").val();
		if (e != "") {
			window.location.href = WapSiteUrl + "/tmpl/mall.html?keyword=" + encodeURIComponent(e)+"&area_info=" + area_info+"&sc_id=" + sc
		}
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
	search_adv()
});

function get_list() {
	$(".loading").remove();
	if (!hasmore) {
		return false
	}
	hasmore = false;
	param = {};
	param.page = page;
	param.curpage = curpage;
	if (sc_id != "") {
		param.sc_id = sc_id
	} else if (keyword != "") {
		param.keyword = keyword
	} 

	$.getJSON(ApiUrl + "/index.php?con=store&fun=store_list&store_state=1" + window.location.search.replace("?", "&"), param, function(e) {
		if (!e) {
			e = [];
			e.datas = [];
			e.datas.store_list = []
		}
		$(".loading").remove();
		curpage++;
		var r = template.render("home_body", e);
		$("#cart-list-wp .favorites-store-list").append(r);
		hasmore = e.hasmore
	})
}
function search_adv() {
	$.getJSON(ApiUrl + "/index.php?con=store&fun=store_class_list", function(e) {
		var r = e.datas;
		$("#list-items-scroll").html(template.render("search_items", r));
	})
}
function init_get_list(e, r) {
	order = e;
	key = r;
	curpage = 1;
	hasmore = true;
	$("#cart-list-wp .favorites-store-list").html("");
	$("#footer").removeClass("posa");
	get_list()
}
