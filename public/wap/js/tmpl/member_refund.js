$(function(){
    var key = getCookie('key');
    //渲染list
    var load_class = new ncScrollLoad();
    load_class.loadInit({
        'url':ApiUrl + '/index.php?con=member_refund&fun=get_refund_list',
        'getparam':{key :key },
        'tmplid':'refund-list-tmpl',
        'containerobj':$("#refund-list"),
        'iIntervalId':true,
        'data':{WapSiteUrl:WapSiteUrl}
    });
});