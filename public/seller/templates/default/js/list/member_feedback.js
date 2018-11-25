$(function() {
   
    $("#feedbackbtn").click(function() {
        var a = $("#feedback").val();
        if (a == "") {
              layer.open({
	            content: '请填写反馈内容!',
	            time: 1.5
	        });
            return false
        }
        var loading = layer.open({type:2,content:'提交中...'});
        $.ajax({
            url: ApiUrl + "/index.php?con=member_feedback&fun=feedback_add",
            type: "post",
            dataType: "json",
            data: {
        
                feedback: a
            },
            success: function(e) {
                    layer.close(loading);
                    if (!e.datas.error) {
                      
                        layer.open({
                            content:'提交成功!',
                            time:1.5
                        });
                        setTimeout(function() {
                            window.location.href = WapSiteUrl + "/index.php?con=member_account"
                        },
                        3e3)
                    } else {
                         layer.open({
                            content:e.datas.error,
                            time:1.5
                        });
                      
                    }
            
            }
        })
    })
});