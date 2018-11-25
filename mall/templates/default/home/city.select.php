<?php defined('Inshopec') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/city.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
jQuery(function() {

		$("#yui_3").autocomplete({ 
        messages: {
            noResults: '',
            results: function() {}
        },	
		//minLength:0,
        source: function (request, response) {
            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?con=search&fun=city_complete', request, function (data, status, xhr) {
                $('#ui-id-1').unwrap();
                
                response(data);
                if (status == 'success') {                    
                    $('#ui-id-1').wrap("<div id='top_search_area'></div>").css({'zIndex':'1000','width':'300px'});
                }
            });
       }
	}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a href=<?php echo SHOP_SITE_URL;?>/index.php?con=city&fun=select_city&city_id="+item.id+">" + item.label + "</a>" )
        .appendTo( ul );
    };
$('#ui-id-2').find('li').click(function(){
	
	alert($(this).val());
})

	$('.first').click(function(){
		var first = $(this).attr('nc_first');
		$(".citylist li").each(function(){
			if($(this).attr('first')==first){
				$(this).show();
			}else{
				$(this).hide();
			}
		});
	});

	$('.letter_all').click(function(){
		$(".citylist li").show();
	});

	$(".cc-J-search-suggest").find("li").hover(function() {
		$(this).addClass("cc-search-suggest__item--over");
	}, function() {
		$(this).removeClass("cc-search-suggest__item--over");
	});
	
});


	

</script>

<div class="nc_city_search">
	<div class="nc_city_nav">
		<div class="nc_city_wrap">
			<div class="clearfix" id="nc_city_hot">
				<dl>
					<dt>热门城市:</dt>
					<?php if(!empty($output['list'])){?>
					<?php foreach($output['list'] as $h){?>
					
					<?php if($h['hot_area']==1){?>
					<dd><a href="<?php echo SHOP_SITE_URL;?>/index.php?con=city&fun=select_city&city_id=<?php echo $h['area_id'];?>"><?php echo $h['area_name'];?></a></dd>
					<?php }?>
					<?php }?>
					<?php }?>			
				</dl>
			</div>
		</div>
	</div>
	
	
	<div class="city_province">
		  <form id="changeCity" name="form1" method="get" action="index.php?con=city&fun=select_city">
		  	<input type="hidden" name="parent_id" id="_area" value="">
		  <span class="label">按省份选择：</span>
		  <div class="area-region-select">
		  	
		  	<input id="region" name="region" type="hidden" value="" >
		  </div>
		  <a href="JavaScript:void(0);" class="btn btn-mini" id="mini">确定</a>
		  </form>	
		<span class="label" style="margin-left:155px;">直接搜索：</span>
		<div class="cc-searchbox" style="display: inline-block;" >
            <input tabindex="1" type="text" name="w" data-smartbox="/search/city?keyword=" placeholder="请输入城市中文或拼音" class="cc-input f-text auto-complete" style="width:292px;" autocomplete="off" id="yui_3">

        </div>
	</div>
	
	
	<div class="nc_city_cont">
		<div class="nc_tit clearfix">
			<span class="nc_tit_label">按拼音首字母选择：</span>
			<ul id="nc_city_list">
				<li><a href="javascript:void(0)" class='letter_all'>全部</a></li>
				<?php if(!empty($output['letter'])){?>
				<?php foreach($output['letter'] as $lv){?>
				<li><a href="javascript:void(0)" class='first' nc_first="<?php echo $lv;?>"><?php echo $lv;?></a></li>
				<?php }?>
				<?php }?>
			</ul>
			<span class="trangle"></span>
		</div>

		<div id="nc_city_A" class="nc_city_numlist">
			<ul class='citylist'>
			<?php if(!empty($output['letter'])){?>
			<?php foreach($output['letter'] as $v){?>
					<li class="clearfix" first="<?php echo $v;?>">
						<span class="lable"><strong><?php echo $v;?></strong></span>
						<span>
							<?php if(!empty($output['list'])){?>
							<?php foreach($output['list'] as $l){?>
							<?php if($v==$l['first_letter']){?>
							<a href="<?php echo SHOP_SITE_URL;?>/index.php?con=city&fun=select_city&city_id=<?php echo $l['area_id'];?>"><?php echo $l['area_name'];?></a>
							<?php }?>
							<?php }?>
							<?php }?>
						</span>
					</li>
			<?php }?>
			<?php }?>
			</ul>
		</div>
	</div>
</div>
<script>
$("#region").nc_region({src:'db',show_deep:2});
$(".btn-mini").click(function(){
    if($("#changeCity").valid()){
        if ($('#region').fetch('area_id')) {
        	var deep = $('#region').fetch('selected_deep')+1;
            if(deep > '2'){
            	//$("#changeCity").submit();
            	window.location.href="index.php?con=city&fun=select_city&city_id="+$('#_area').val();
            }else{
            	alert('请选择城市');
            }
        }else{
        	alert('请选择城市');
        }
      
    }

	});


</script>	