$(function() {
    var t = getQueryString("store_id");
    $("#goods_search_all").attr("href", ApiUrl + "/index.php?con=store&fun=store_goods&store_id=" + t);
    $("#search_btn").click(function() {
        var e = $("#search_keyword").val();
        if (e != "") {
            window.location.href = ApiUrl + "/index.php?con=store&fun=store_goods&store_id=" + t + "&keyword=" + encodeURIComponent(e)
        }
    });
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?con=store&fun=store_goods_class",
        data: {
            store_id: t
        },
        dataType: "json",
        success: function(t) {
            var e = t.datas;
            var o = e.store_info.store_name + " - 店内搜索";
            document.title = o;
            e.ApiUrl =ApiUrl;
            var r = template.render("store_category_tpl", e);
            $("#store_category").html(r)
        }
    })
});