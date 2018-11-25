$(function() {
    Array.prototype.unique = function() {
        var e = [];
        for (var t = 0; t < this.length; t++) {
            if (e.indexOf(this[t]) == -1) e.push(this[t])
        }
        return e
    };
    var e = decodeURIComponent(getQueryString("keyword"));
    if (e) {
        $("#keyword").val(e);
        writeClear($("#keyword"))
    }
    $("#keyword").on("input",
    function() {
        var e = $.trim($("#keyword").val());
        if (e == "") {
            $("#search_tip_list_container").hide()
        } else {
            $.getJSON(ApiUrl + "/index.php?con=goods&fun=auto_complete", {
                term: $("#keyword").val()
            },
            function(e) {
                if (!e.datas.error) {
                    var t = e.datas;
                    t.WapSiteUrl = WapSiteUrl;
                    if (t.list.length > 0) {
                        $("#search_tip_list_container").html(template.render("search_tip_list_script", t)).show()
                    } else {
                        $("#search_tip_list_container").hide()
                    }
                }
            })
        }
    });
    $(".input-del").click(function() {
        $(this).parent().removeClass("write").find("input").val("")
    });
    template.helper("$buildUrl", buildUrl);
    $.getJSON(ApiUrl + "/index.php?con=index&fun=search_key_list",
    function(e) {
        var t = e.datas;
        t.WapSiteUrl = WapSiteUrl;
        $("#hot_list_container").html(template.render("hot_list", t));
        $("#search_his_list_container").html(template.render("search_his_list", t))
    });
    $("#header-nav").click(function() {
        if ($("#keyword").val() == "") {
            window.location.href = buildUrl("keyword", getCookie("deft_key_value") ? getCookie("deft_key_value") : "")
        } else {
            window.location.href = buildUrl("keyword", $("#keyword").val())
        }
    })
});