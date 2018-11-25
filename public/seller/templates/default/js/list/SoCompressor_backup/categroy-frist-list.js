$(function() {
    var e;
    $("#header").on("click", ".header-inp",
    function() {
        location.href = ApiUrl + "/index.php?con=goods&fun=search"
    });
    $.getJSON(ApiUrl + "/index.php?con=goods_class&fun=list",
    function(t) {
        var r = t.datas;
        r.ApiUrl = ApiUrl;
        var a = template.render("category-one", r);
        $("#categroy-cnt").html(a);
        e = new IScroll("#categroy-cnt", {
            mouseWheel: true,
            click: true
        })
    });
    get_brand_recommend();
    $("#categroy-cnt").on("click", ".category",
    function() {
        $(".pre-loading").show();
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        var t = $(this).attr("date-id");
        $.getJSON(ApiUrl + "/index.php?con=goods_class&fun=get_child_all", {
            gc_id: t
        },
        function(e) {
            var t = e.datas;
            t.ApiUrl = ApiUrl;
            var r = template.render("category-two", t);
            $("#categroy-rgt").html(r);
            $(".pre-loading").hide();
            new IScroll("#categroy-rgt", {
                mouseWheel: true,
                click: true
            })
        });
        e.scrollToElement(document.querySelector(".categroy-list li:nth-child(" + ($(this).parent().index() + 1) + ")"), 1e3)
    });
    $("#categroy-cnt").on("click", ".brand",
    function() {
        $(".pre-loading").show();
        get_brand_recommend()
    })
});
function get_brand_recommend() {
    $(".category-item").removeClass("selected");
    $(".brand").parent().addClass("selected");
    $.getJSON(ApiUrl + "/index.php?con=brand&fun=recommend_list",
    function(e) {
        var t = e.datas;
        t.ApiUrl = ApiUrl;
        var r = template.render("brand-one", t);
        $("#categroy-rgt").html(r);
        $(".pre-loading").hide();
        new IScroll("#categroy-rgt", {
            mouseWheel: true,
            click: true
        })
    })
}