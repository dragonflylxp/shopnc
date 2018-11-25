$(function() {

	var e = getCookie("key");

	if (!e) {

		window.location.href = WapUrl + "/tmpl/member/login.html";;

		return

	}

	var a = getQueryString("order_id");

	$.getJSON(ApiUrl + "/index.php?con=member_evaluate&fun=get_pj", {

		key: e,

		order_id: a

	}, function(r) {

		if (r.datas.error) {

			layer.open({

				content:r.datas.error,

				time:1.5

			});

			return false

		}

		var l = template.render("member-evaluation-script", r.datas);

		$("#member-evaluation-div").html(l);

		$('input[name="file"]').ajaxUploadImage({

			url: ApiUrl + "/index.php?con=sns_album&fun=file_upload",

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

				e.parent().after('<div class="pic-thumb"><img src="' + a.datas.file_url + '"/></div>');

				e.parent().siblings(".upload-loading").remove();

				e.parents("a").next().val(a.datas.file_name)

			}

		});

		$(".star-level").find("i").click(function() {

			var e = $(this).index();

			for (var a = 0; a < 5; a++) {

				var r = $(this).parent().find("i").eq(a);

				if (a <= e) {

					r.removeClass("star-level-hollow").addClass("star-level-solid")

				} else {

					r.removeClass("star-level-solid").addClass("star-level-hollow")

				}

			}

			$(this).parent().next().val(e + 1)

		});

		$(".btn-l").click(function() {

			var r = $("form").serializeArray();

			var l = {};

			l.key = e;

			l.order_id = a;

			for (var t = 0; t < r.length; t++) {

				l[r[t].name] = r[t].value

			}

			var loading = layer.open({type:2,content:'提交中...'});

			$.ajax({

				type: "post",

				url: ApiUrl + "/index.php?con=member_evaluate&fun=save",

				data: l,

				dataType: "json",

				async: false,

				success: function(e) {

					checkLogin(e.login);

					layer.close(loading);

					if (e.datas.error) {

						layer.open({

							content:e.datas.error,

							time:1.5

						});

						return false

					}

					window.location.href = ApiUrl + "/index.php?con=member_order"

				}

			})

		})

	})

});