var SiteUrl = "http://manpay.sicpay.com/mall";
var ApiUrl = "http://manpay.sicpay.com/mob";
var SellerUrl = "http://manpay.sicpay.com/seller";
var pagesize = 10;
var WapSiteUrl = "http://manpay.sicpay.com/wap";
var IOSSiteUrl = "http://manpay.sicpay.com/";
var AndroidSiteUrl = "http://manpay.sicpay.com/";

// auto url detection
(function() {
    var m = /^(https?:\/\/.+)\/wap/i.exec(location.href);
    if (m && m.length > 1) {
        SiteUrl = m[1] + '/mall';
        ApiUrl = m[1] + '/mob';
        WapSiteUrl = m[1] + '/wap';
    }
})();
