$(function() {
    var e = getQueryString("store_id");
    var o = getCookie("key");
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?con=store&fun=get_store_intro",
        data: {
            key: o,
            store_id: e
        },
        dataType: "json",
        success: function(e) {
            var o = e.datas;
            var t = template.render("store_intro_tpl", o);
            $("#store_intro").html(t);
            if (o.store_info.is_favorate) {
                $("#store_notcollect").hide();
                $("#store_collected").show()
            } else {
                $("#store_notcollect").show();
                $("#store_collected").hide()
            }
        }
    });
    $("#store_notcollect").live("click",
    function() {
        var o = favoriteStore(e);
        if (o) {
            $("#store_notcollect").hide();
            $("#store_collected").show();
            var t;
            var r = (t = parseInt($("#store_favornum_hide").val())) > 0 ? t + 1 : 1;
            $("#store_favornum").html(r);
            $("#store_favornum_hide").val(r)
        }
    });
    $("#store_collected").live("click",
    function() {
        var o = dropFavoriteStore(e);
        if (o) {
            $("#store_collected").hide();
            $("#store_notcollect").show();
            var t;
            var r = (t = parseInt($("#store_favornum_hide").val())) > 1 ? t - 1 : 0;
            $("#store_favornum").html(r);
            $("#store_favornum_hide").val(r)
        }
    })
});