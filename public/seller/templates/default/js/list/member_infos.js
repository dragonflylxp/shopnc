/*生日选择*/
function initBirthdaySelect(y,m,d)
{
    var dropYear = document.getElementById("myyear");
    var dropMonth = document.getElementById("mymonth");
    var dropDay = document.getElementById("myday");

    var nowYear = new Date().getFullYear();
    var beginYear = nowYear - 72;
    var endYear = nowYear - 10;
    for (var i = endYear; i >= beginYear; i--) {
        var opt = new Option(i + "年", i);
        dropYear.options[dropYear.options.length] = opt;
    }

    for (var i = 1; i <= 12; i++) {
        if (i < 10) {
            var opt = new Option(i + "月", "0" + i);
        } else {
            var opt = new Option(i + "月", i);
        }
        dropMonth.options[dropMonth.options.length] = opt;
    }

    var bigMonths = new Array("01", "03", "05", "07", "08", "10", "12");
    var smallMonths = new Array("04", "06", "09", "11");
    //得到天数
    function initDay_fun(temp) {
        var days = 31;
        if (temp == "big") {
            days = 31;
        } else if (temp == "small") {
            days = 30;
        } else {
            var year = dropYear.value;
            if (((year % 4) == 0 && (year % 100) != 0) || (year % 400 == 0)) {//year如果能被4除尽，返回29天的dropDays
                days = 29;
            } else {//year如果不能被4除尽，返回28天的dropDays
                days = 28;
            }
        }
        for (var i = 1; i <= days; i++) {
            var opt = new Option(i + "日", i < 10 ? "0" + i : i);
            dropDay.options[dropDay.options.length] = opt;
        }
    }

    dropYear.onchange = function () {
        dropMonth.options[0].selected = true;
        dropDay.options.length = 1;
        if (this.value == -1) {
            dropMonth.disabled = true;
            dropDay.disabled = true;
        } else {
            dropMonth.disabled = false;
            dropDay.disabled = false;
        }
    }
    dropMonth.onchange = function () {
        var isGone = true;
        if (dropMonth.value == -1) {
            dropDay.options.length = 1;
        } else {
            if (isGone) {//初始化dropDay为31天
                for (var i = 0; i < bigMonths.length; i++) {
                    if (dropMonth.value == bigMonths[i]) {
                        initDay_fun("big");
                        isGone = false;
                        break;
                    }
                }
            }
            if (isGone) {//初始化dropDay为30天
                for (var i = 0; i < smallMonths.length; i++) {
                    if (dropMonth.value.toString() == smallMonths[i].toString()) {
                        initDay_fun("small");
                        isGone = false;
                        break;
                    }
                }
            }
            if (isGone) {//初始化dropDay为28天或29天
                initDay_fun("flat");
            }
        }
        dropDay.options[0].selected = true;

    }
    if (y != '' && y != null) {
        dropYear.value = y;
        if (m < 10) m = "0" + m;
        dropMonth.value = m;
        dropMonth.onchange();
        if (d < 10) d = "0" + d;
        dropDay.value = d;
    }
}

$(function() {
    var e = getCookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "?index.php&con=login";
        return
    }
    $("#truename").keyup(function(){
        if($("#truename").val() !=''){
            $(".form-btn").addClass("ok");
        }else{
            $(".form-btn").removeClass("ok"); 
        }
    });
    $("#nextform").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        var a = $.trim($("#truename").val());
        if (a) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=member_account&fun=update_truename",
                data: {
                    key: e,
                    truename: a
                },
                dataType: "json",
                success: function(e) {
                    if (e.code == 200) {
                         layer.open({
                            content: '设置成功',
                            time:1.5
                        });

                        setTimeout("location.href = ApiUrl + '/index.php?con=member_account'", 2e3)
                    } else {
                         layer.open({
                            content: e.datas.error,
                            time:1.5
                        });
                    }
                }
            })
        }
    })
    $("#nextform1").click(function() {
        var a =  $('.nctouch-inp-con input[name="sex"]:checked').val();
  
        if (a) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?con=member_account&fun=update_sex",
                data: {
                    key: e,
                    sex: a
                },
                dataType: "json",
                success: function(e) {
                    if (e.code == 200) {
                         layer.open({
                            content: '设置成功',
                            time:1.5
                        });
                        
                        setTimeout("location.href = ApiUrl + '/index.php?con=member_account'", 2e3)
                    } else {
                         layer.open({
                            content: e.datas.error,
                            time:1.5
                        });
                    }
                }
            })
        }
    })
     $('#edtBirthBtn').click(function(){
        var myyear =$('#myyear').val();
        var mymonth = $('#mymonth').val();
        var myday = $('#myday').val();
        var birthday=myyear+'-'+mymonth+'-'+myday;
            if (myyear == 0 || mymonth == 0 || myday == 0) {
              layer.open({content: '生日选择有误', time: 1});
              return false;
            }else{
                $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?con=member_account&fun=update_birthday",
                    data: {
                        key: e,
                        birthday: birthday
                    },
                    dataType: "json",
                    success: function(e) {
                        if (e.code == 200) {
                             layer.open({
                                content: '设置成功',
                                time:1.5
                            });
                            
                            setTimeout("location.href = ApiUrl + '/index.php?con=member_account'", 2e3)
                        } else {
                             layer.open({
                                content: e.datas.error,
                                time:1.5
                            });
                        }
                    }
                })
            }
            
        });
 
});