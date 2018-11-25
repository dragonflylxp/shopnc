var SiteUrl = "https://shop99.yifensport.com/mall";
var ApiUrl = "https://shop99.yifensport.com/mob";
var SellerUrl = "https://shop99.yifensport.com/seller";
var pagesize = 10;
var WapSiteUrl = "https://shop99.yifensport.com/wap";
var IOSSiteUrl = "https://shop99.yifensport.com/";
var AndroidSiteUrl = "https://shop99.yifensport.com/";

// auto url detection
(function() {
    var m = /^(https?:\/\/.+)\/wap/i.exec(location.href);
    if (m && m.length > 1) {
        SiteUrl = m[1] + '/mall';
        ApiUrl = m[1] + '/mob';
        WapSiteUrl = m[1] + '/wap';
    }
})();
