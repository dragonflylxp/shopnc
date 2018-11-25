var order_id;

$(function() {

	var e = getCookie("key");

	if (!e) {

		window.location.href = WapUrl + "/tmpl/member/login.html";

	}

	$.getJSON(ApiUrl + "/index.php?con=member_refund&fun=refund_all_form", {

		key: e,

		order_id: getQueryString("order_id")

	}, function(a) {

		a.datas.ApiUrl = ApiUrl;

		$("#order-info-container").html(template.render("order-info-tmpl", a.datas));

		order_id = a.datas.order.order_id;

		$("#allow_refund_amount").val("￥" + a.datas.order.allow_refund_amount);

		$('input[name="refund_pic"]').ajaxUploadImage({

			url: ApiUrl + "/index.php?con=member_refund&fun=upload_pic",

			data: {

				key: e

			},

			start: function(e) {

				e.parent().after('<div class="upload-loading"><i></i></div>');

				e.parent().siblings(".pic-thumb").remove()

			},

			success: function(e, a) {

				checkLogin(a.login);

				if (a.datas.error) {

					e.parent().siblings(".upload-loading").remove();

					layer.open({

						content:'图片尺寸过大！',

						time:1.5

					});

					return false

				}

				e.parent().after('<div class="pic-thumb"><img src="' + a.datas.pic + '"/></div>');

				e.parent().siblings(".upload-loading").remove();

				e.parents("a").next().val(a.datas.file_name)

			}

		});

		$(".btn-l").click(function() {

			var a = $("form").serializeArray();

			var r = {};

			r.key = e;

			r.order_id = order_id;

			for (var n = 0; n < a.length; n++) {

				r[a[n].name] = a[n].value

			}

			if (r.buyer_message.length == 0) {

				layer.open({

						content:'请填写退款说明',

						time:1.5

					});

				return false

			}

			$.ajax({

				type: "post",

				url: ApiUrl + "/index.php?con=member_refund&fun=refund_all_post",

				data: r,

				dataType: "json",

				async: false,

				success: function(e) {

					checkLogin(e.login);

					if (e.datas.error) {

						layer.open({

							content:e.datas.error,

							time:1.5

						});

						return false

					}

					window.location.href = ApiUrl + "/index.php?con=member_refund"

				}

			})

		})

	})

});