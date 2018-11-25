<?php defined('Inshopec') or exit('Access Invalid!');?>
<?php
$zbgtype=Model('zhibo')->getgtype();
$zbgifts=Model('zhibo')->getgifts();
?>

<div class="gift_nav">
 <?php $i=1; 
 foreach ($zbgtype as $val){
	if($i==1){$class="giftNow";}else{$class="";}
		echo '<label class="'.$class.'" id="labels_'.$i.'" type="'.$i.'" style="position: relative; width: 52px;">'.$val['name'].'</label>';
	$i++;
	}
?>
</div>
<?php
$i=1; //类列序号
foreach ($zbgifts as $val){
	if($i>1){$class="none";}			
	$html.='<div class="gift_list '.$class.'" id="gift_'.$i.'">';
	$n=floor(count($zbgifts[$i])/18);//页码
	if($n>0){$html.='<a href="javascript:void(0);" class="ptShowPrewGift3 giftpopLeft" style=""></a>';}
	$p=1;
	$nn=0;
	foreach ($zbgifts[$i] as $v){				
		$z=$p-1;
		if($p%18==1){
			if($nn==1){$display="none";}
			$html.='<ul class="monday_gift '.$display.'" id="type_'.$v['tid'].'_'.$nn.'">';}
			$html.='<li style="cursor:pointer;position:relative;" class="ptChoseGift" tips="null" giftid="'.$v['id'].'" gname="'.$v[name].'" price="5" showtype="1" tip="价值：'.$v[price].'积分" type="1" alt="'.$v[name].'">
				 <img src="'.BASE_SITE_URL.'/shop/player/zbgift/img/'.$i.'/'.$p.'.png" style="vertical-align: middle;" height="40px" width="40px" alt="'.$v[name].'">
				 <p class="name">'.$v[name].'</p>
				 <p class="price">'.$v[price].'</p>
				</li>';		
		if($p%18==0 || count($zbgifts[$i])==$p){
			$html.='</ul>';  $nn++;				
		}
		$p++;
	}	
if($n>=1){$html.='<a href="javascript:void(0);" class="ptShowNextGift3 giftpopRight gifgrayRig" style=""></a>';}
$html.='</div>';
$i++;
}
echo $html;
?>


