

function getBankName(bankCard){
	alert(1111)
	$.getJSON("./bankData.json", {}, function (data) { 
		var bankBin = 0; 
		var isFind = false; 
		for (var key = 10; key >= 2; key--) { 
			bankCard = bankCard.toString();
			bankBin = bankCard.substring(0, key); 
			$.each(data, function (i, item) { 
				if (item.bin == bankBin) { 
					isFind = true; 
					return item.bankName; 
				} 
			}); 
			if (isFind) { 
				break; 
			} 
		} 
		if (!isFind) { 
			return "未知发卡银行"; 
		} 
	}); 
}

