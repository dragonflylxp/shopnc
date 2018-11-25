$(function() {
    var a = document.title;
    var e = '<div class="header-wrap">' + '<div class="header-l">' + '<a href="javascript:history.go(-1)">' + '<i class="back"></i>' + "</a></div>" + "<h1>" + a + "</h1>" + "</div>";
    $("#header").html(e);
    $.getJSON(ApiUrl + "/index.php?con=register&fun=agreement",
    function(a) {
        $("#document").html(a.datas.doc_content)
    })
});