$(function() {

	city_post();	
	var default_city = getCookie('default_city');
//var cc = JSON.parse(default_city);
//alert(cc['area_id']);
	if(!default_city) {
		city_default();
	}
var $j = jQuery.noConflict();	
var nav=$j(".navbar"); //得到导航对象
var win=$j(window); //得到窗口对象
var sc=$j(document);//得到document文档对象。
win.scroll(function(){
  if(sc.scrollTop()>=100){
    nav.addClass("fixednav"); 
   $j(".navTmp").fadeIn(); 
  }else{
   nav.removeClass("fixednav");
   $j(".navTmp").fadeOut();
  }
}) 	

	function city_post() {
		$.ajax({
			type: "post",
			url: ApiUrl + "/index.php?con=city&fun=index",
			data: {},
			dataType: "json",
			success: function(result) {

				if(result.datas) {
					$("#recently-city ul.table").append(template.render('old_city', result.datas));
					$("#hot-city ul.table").append(template.render('hot_city', result.datas));
					//$("ul.charlist").append(template.render('frist_letter', result.datas));
					$(".abc").append(template.render('city_list', result.datas));
                     
				} else {
					$.sDialog({
						skin: "red",
						content: result.datas.error,
						okBtn: false,
						cancelBtn: false
					});
				}

			}
		});
	}

	function city_default() {
		$.ajax({
			type: "post",
			url: ApiUrl + "/index.php?con=city&fun=default",
			data: {},
			dataType: "json",
			success: function(result) {
                
				if(result.datas.list) {
					var bbb = JSON.stringify(result.datas.list);
					addCookie('default_city', bbb, 72);
				} else {
					$.sDialog({
						skin: "red",
						content: result.datas.error,
						okBtn: false,
						cancelBtn: false
					});
				}
			}
		});
	}

	function myFun(result) {
		var cityName = result.name;
		map.setCenter(cityName);
		if(cityName) {
			$(".city-name").text(cityName);
			

		} else {
			$(".city-name").text('定位失败,请手动选择!');
		}
		//alert("当前定位城市:"+cityName);

	}
	var myCity = new BMap.LocalCity();
	myCity.get(myFun);

	// 百度地图API功能
	var map = new BMap.Map("allmap");
	var point = new BMap.Point(116.331398, 39.897445);
	map.centerAndZoom(point, 12);

	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r) {
		if(this.getStatus() == BMAP_STATUS_SUCCESS) {
			var mk = new BMap.Marker(r.point);
			map.addOverlay(mk);
			map.panTo(r.point);
			addCookie('lat',r.point.lat);
		    addCookie('lon',r.point.lng);
		} else {
			alert('failed' + this.getStatus());
		}
	}, {
		enableHighAccuracy: true
	})
      var Initials=$j('.initials');
        var LetterBox=$j('#letter');
        Initials.find('ul').append('<li>A</li><li>B</li><li>C</li><li>D</li><li>E</li><li>F</li><li>G</li><li>H</li><li>I</li><li>J</li><li>K</li><li>L</li><li>M</li><li>N</li><li>O</li><li>P</li><li>Q</li><li>R</li><li>S</li><li>T</li><li>W</li><li>X</li><li>Y</li><li>Z</li><li>#</li>');
        

        $j(".initials ul li").click(function(){
            var _this=$j(this);
            
            var LetterHtml=_this.html();
            LetterBox.html(LetterHtml).fadeIn();

            Initials.css('background','rgba(145,145,145,0.6)');
            
            setTimeout(function(){
                Initials.css('background','rgba(145,145,145,0)');
                LetterBox.fadeOut();
            },1000);

            var _index = _this.index();
          
            //$('html,body').animate({scrollTop: '20px'}, 300);
          
            
            if(_index==0){
                $j('html,body').animate({scrollTop: '0px'}, 300);//点击第一个滚到顶部
            }else if(_index==25){
                var DefaultTop=$j('.nav-text').offset().top;
                $j('html,body').animate({scrollTop: DefaultTop+'px'}, 300);//点击最后一个滚到#号
            }else{
                var letter = _this.text();
                if($j('#'+letter).length>0){
                	
                    var LetterTop = $j('#'+letter).offset().top;
                    
                    $j('html,body').animate({scrollTop: LetterTop-85+'px'}, 800);
                }

            }
        })

        var windowHeight=$j(window).height();
        var InitHeight=windowHeight-45;
        Initials.height(InitHeight);
        var LiHeight=InitHeight/28;
        Initials.find('li').height(LiHeight);	
        
        
    // 选择地区
    $('#location-city').on('click', function(){        $.areaSelected({            success : function(data){            	var uurl = data.area_id_3 == 0 ? data.area_id_2 : data.area_id_3;                        	window.location.href= WapSiteUrl+'/index.html?area_id='+uurl;                //$('.city_xz').val(data.area_info).attr({'data-areaid':data.area_id, 'data-areaid2':(data.area_id_2 == 0 ? data.area_id_1 : data.area_id_2)});            }        });
        
    });        
     $('#keyarea').on('input',function(){
    	var value = $.trim($('#keyarea').val());
    	if (value == '') {
    		$('#search_tip_list_container').hide();
    	} else {
            $.getJSON(SiteUrl + '/index.php?con=search&fun=city_complete&q=2',{term:$('#keyarea').val()}, function(result) {
            	if (!result.datas.error) {
                	var data = result.datas;
                	data.WapSiteUrl = WapSiteUrl;
                	if (data.list.length > 0) {
                		$('#search_tip_list_container').html(template.render('search_tip_list_script',data)).show();
                	} else {
                		$('#search_tip_list_container').hide();
                	}
            	}
            })
    	}
    });    
});