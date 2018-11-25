//封顶价
var maxPrice=0;
//当前价
var currentPrice=1;
//起拍价
var startPrice=1;
//最低加价幅度
var priceLowerOffset=1;
//最高加价幅度
var priceHigherOffset=1000;


/**
 * 正在拍卖：点+
 * */
function incre(){
	var userprice = $("#buyPrice").val();
	var price = Number($.trim(userprice));
	maxPrice = Number($("#maxPrice").val());
	currentPrice = Number($("#currentPrice").data("price"));
	priceLowerOffset = Number($("#priceLowerOffset").text());
	var limitPrice = !isNaN(maxPrice) && maxPrice >= 1;
	if(limitPrice){
		if(price+priceLowerOffset>maxPrice){
			$("#buyPrice").val(maxPrice);
			alert("已是最高价！");
			return;
		}
	}
	if(price+priceLowerOffset<currentPrice+priceLowerOffset){
		$("#buyPrice").val(currentPrice+priceLowerOffset);
	}
	else if(price+priceLowerOffset>=currentPrice+priceLowerOffset){
		$("#buyPrice").val(price+priceLowerOffset);
	}
	else{
		$("#buyPrice").val(currentPrice+priceLowerOffset);
	}
}


/**
 * 正在拍卖：点-
 * */
function decre(){
	var userprice = $("#buyPrice").val();
	var price = Number(jQuery.trim(userprice));
	maxPrice = Number($("#maxPrice").val());
	currentPrice = Number($("#currentPrice").data("price"));
	var limitPrice = !isNaN(maxPrice) && maxPrice >= 1;
	if(limitPrice){
		if(price-priceLowerOffset>maxPrice){
			$("#buyPrice").val(maxPrice);
			return ;
		}
	}
	if(price-priceLowerOffset<currentPrice+priceLowerOffset){
		$("#buyPrice").val(currentPrice);
		pbDialog(json_languages.lowest_price,"",0);
	}
	else if(price-priceLowerOffset>=currentPrice+priceLowerOffset && price-priceLowerOffset<=currentPrice+priceHigherOffset){
		$("#buyPrice").val(price-priceLowerOffset);
	}
	else{
		$("#buyPrice").val(currentPrice);
		pbDialog(json_languages.lowest_price,"",0);
	}
}