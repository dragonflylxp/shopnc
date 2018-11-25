//数量选择
function quantity(){
	var quantity = Number($("#quantity").val());
	var perNumber = Number($("#perNumber").val());
	var perMinNumber = Number($("#perMinNumber").val());
	
	$(".btn-reduce").click(function(){
		if(quantity>perMinNumber){
			quantity-=1;
			$("#quantity").val(quantity);
		}else{
			$("#quantity").val(perMinNumber);
		}
	});
	
	$(".btn-add").click(function(){
		if(quantity<perNumber){
			quantity+=1;
			$("#quantity").val(quantity);
		}else{
			$("#quantity").val(perNumber);
		}
	})
}
quantity();