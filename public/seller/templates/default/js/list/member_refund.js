$(function() {
    var e = getCookie("key");
    var t = new ncScrollLoad;
    t.loadInit({
        url: ApiUrl + "/index.php?con=member_refund&fun=get_refund_list",
        getparam: {
            key: e
        },
        tmplid: "refund-list-tmpl",
        containerobj: $("#refund-list"),
        iIntervalId: true,
        data: {
            WapSiteUrl: WapSiteUrl
        }
    })
});