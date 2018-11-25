<?php
use shopec\Tpl;


defined('Inshopec') or exit('Access Invalid!');
class show_zhiboControl extends BaseStoreControl {
	public function __construct(){
		parent::__construct();
	}
	public function indexOp(){
		//获取店铺直播权限
		$store_id=$this->store_info['store_id'];
		$zb_config=Model('zhibo')->zb_config($store_id);
		if($zb_config['type']=="1" && $zb_config['uid']==$_SESSION['member_id']){
			$data=array('link'=>$_SERVER["REMOTE_ADDR"]);
			$zb_config=Model('zhibo')->zb_config_updata($store_id,$data);
			$zb_config=Model('zhibo')->zb_config($store_id);
			}
		$leavel=intval(Model('setting')->getRowSetting('store_zb_leavel'));	
		if($zb_config['type']=="0"){$as="?o=&s=&a=".$store_id;}		
		if($zb_config['type']=="1"){$as="?o=".$zb_config['type']."&s=".$zb_config['link']."&a=".$store_id;}
		if($zb_config['type']=="2"){$as="?o=".$zb_config['type']."&s=".$zb_config['link']."&a=".$store_id;}
		if($zb_config['type']=="3"){$as="?o=".$zb_config['type']."&s=ali&a=".$store_id;}
		$condition['member_id']=$_SESSION['member_id'];
		$user=Model('member')->getMemberInfo($condition);
		
		$condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
		$model_goods = Model('goods'); // 字段
        $fieldstr ="goods_id,goods_commonid,goods_name,goods_jingle,store_id,store_name,goods_price,goods_promotion_price,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_good_star,evaluation_count,goods_promotion_type";
		//得到最新12个商品列表
		if (C('dbdriver') == 'oracle') {
			$oracle_fields = array();
			$fields = explode(',', $fieldstr);
			foreach ($fields as $val) {
				$oracle_fields[] = 'min('.$val.') '.$val;
			}
			$fields = implode(',', $oracle_fields);
		}
		$count = $model_goods->getGoodsOnlineCount($condition,"distinct goods_commonid");
		$new_goods_list = $model_goods->getGoodsOnlineList($condition, $fields, 12, 'goods_id desc', 0, 'goods_commonid', false, $count);

		$condition['goods_commend'] = 1;
		//得到12个推荐商品列表
		$count = $model_goods->getGoodsOnlineCount($condition,"distinct goods_commonid");
		$recommended_goods_list = $model_goods->getGoodsOnlineList($condition, $fields, 12, 'goods_id desc', 0, 'goods_commonid', false, $count);
		
		$goods_list = $this->getGoodsMore($new_goods_list, $recommended_goods_list);
		Tpl::output('new_goods_list',$goods_list[1]);
		Tpl::output('recommended_goods_list',$goods_list[2]);
		
		Tpl::output('as',$as);
		Tpl::output('member',$user);
		Tpl::output('zb_config',$zb_config);
		Tpl::output('page','zhibo');
        Tpl::showpage('zhibo');
    }
	public function gift_sendOp() {
        //判断是否为导航页面
		$gid=$_POST['gid'];//虚拟动画ID
		$gnum=$_POST['gnum'];//数量
		$userid=$_SESSION['member_id'];//赠送用户ID
		$reid=$_POST['reid'];//收益用户ID
		
		$gift=Model('zhibo')->getgiftid($gid);//获取礼物单价
		$pay=$gift['price']*$gnum; //应扣金额
		$toutiao=$pay>5000?"1":"0";
		$toutiao=$pay>10000?"2":$toutiao;
		$condition['member_id'] = $userid; 
		$user=Model('member')->getMemberInfo($condition);
		if($user['member_points']>=$pay){
			$payresult=Model('zhibo')->zhiboPointsLog($userid,$reid,$gift['name'],$gnum,$pay);
			if($payresult){
				$data=array(
					"s"=>"1",
					"msg"=>"赠送成功",
					"gid"=>$gift['id'],
					"tid"=>$gift['tid'],
					"img"=>$gift['img'],
					"name"=>$gift['name'],
					"val"=>$gift['value'],
					"num"=>$gnum,
					"toutiao"=>$toutiao
				);}
				else{
					$data=array(
					"s"=>"1",
					"msg"=>"系统错误"
					);
					}
			}
		else{
			$kouluan=false;
			$data=array(
					"s"=>"0",
					"msg"=>"余额不足"
					);
			}
        
		echo json_encode($data);
    }
	public function chaxunOp() {
		$userid=$_SESSION['member_id'];//用户ID
		$condition['member_id'] = $userid; 
		$user=Model('member')->getMemberInfo($condition);
		$return['s']='success'; 
		$return["points"]=$user['member_points'];
		$return["money"]=$user['available_predeposit'];   
		echo json_encode($return);
    }
	public function duihuanOp() {
		$userid=$_SESSION['member_id'];//用户ID
		$condition['member_id'] = $userid; 
		$user=Model('member')->getMemberInfo($condition);
		$duihuan=$_POST['change'];
		if($duihuan<0){$duihuan=-$duihuan;}
		$duihuan=floor($duihuan);
		$kmoney=$duihuan/100;
	
		//余额扣除		
		$des="直播兑换".$duihuan."积分扣除人民币".$kmoney."元";
		if($user['available_predeposit']>=$kmoney){
		$mresult=Model('zhibo')->zbmoneychange($userid,$kmoney,$des);	
		//积分增加
		if($mresult){
		$des="直播账户兑换".$duihuan."积分扣除人民币".$kmoney."元";
		$presult=Model('zhibo')->zbpointchange($userid,$duihuan,$des);
			if($presult){
				$s="success";
				}
			else{$error="积分不变";}
			}
		else{
			$error="账户扣款失败";
			}
		}
		else{
			$error="账户余额不足！请充值！";
			}
		//构建返回数组
		if($error){
			$return['s']='error';
			$return['msg']=$error;
			}
		else{
			$user=Model('member')->getMemberInfo($condition);
			$return['s']='success'; 
			$return["points"]=$user['member_points'];
			$return["money"]=$user['available_predeposit'];   
			}   
		echo json_encode($return);
    }
	public function zhibodoOp(){
		$store_id=$this->store_info['store_id'];
		if($_POST["change"]=="start"){
			$data=array('status'=>"1");
			}
		else{
			$data=array('status'=>"0");	
				}
		$zb_config=Model('zhibo')->zb_config_updata($store_id,$data);
		echo json_encode($data);	
		}
    private function getGoodsMore($goods_list1, $goods_list2 = array()) {
        if (!empty($goods_list2)) {
            $goods_list = array_merge($goods_list1, $goods_list2);
        } else {
            $goods_list = $goods_list1;
        }
        // 商品多图
        if (!empty($goods_list)) {
            $goodsid_array = array();       // 商品id数组
            $commonid_array = array(); // 商品公共id数组
            $storeid_array = array();       // 店铺id数组
            foreach ($goods_list as $value) {
                $goodsid_array[] = $value['goods_id'];
                $commonid_array[] = $value['goods_commonid'];
                $storeid_array[] = $value['store_id'];
            }
            $goodsid_array = array_unique($goodsid_array);
            $commonid_array = array_unique($commonid_array);

            // 商品多图
            $goodsimage_more = Model('goods')->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)));

            foreach ($goods_list1 as $key => $value) {
                // 商品多图
                foreach ($goodsimage_more as $v) {
                    if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
                        $goods_list1[$key]['image'][] = $v;
                    }
                }
            }

            if (!empty($goods_list2)) {
                foreach ($goods_list2 as $key => $value) {
                    // 商品多图
                    foreach ($goodsimage_more as $v) {
                        if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
                            $goods_list2[$key]['image'][] = $v;
                        }
                    }
                }
            }
        }
        return array(1=>$goods_list1,2=>$goods_list2);
    }

    public function show_articleOp() {
        //判断是否为导航页面
        $model_store_navigation = Model('store_navigation');
        $store_navigation_info = $model_store_navigation->getStoreNavigationInfo(array('sn_id' => intval($_GET['sn_id'])));
        if (!empty($store_navigation_info) && is_array($store_navigation_info)){
            Tpl::output('store_navigation_info',$store_navigation_info);
            Tpl::showpage('article');
        }
    }

	/**
	 * 全部商品
	 */
	public function goods_allOp(){

		$condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        if (trim($_GET['inkeyword']) != '') {
            $condition['goods_name'] = array('like', '%'.trim($_GET['inkeyword']).'%');
        }

		// 排序
        $order = $_GET['order'] == 1 ? 'asc' : 'desc';
		switch (trim($_GET['key'])){
			case '1':
				$order = 'goods_id '.$order;
				break;
			case '2':
				$order = 'goods_promotion_price '.$order;
				break;
			case '3':
				$order = 'goods_salenum '.$order;
				break;
			case '4':
				$order = 'goods_collect '.$order;
				break;
			case '5':
				$order = 'goods_click '.$order;
				break;
			default:
				$order = 'goods_id desc';
				break;
		}

		//查询分类下的子分类
		if (intval($_GET['stc_id']) > 0){
		    $condition['goods_stcids'] = array('like', '%,' . intval($_GET['stc_id']) . ',%');
		}

		$model_goods = Model('goods');
		$fieldstr = "goods_id,goods_commonid,goods_name,goods_jingle,store_id,store_name,goods_price,goods_promotion_price,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_good_star,evaluation_count,goods_promotion_type";
        $recommended_goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, 24);
        $recommended_goods_list = $this->getGoodsMore($recommended_goods_list);
		Tpl::output('recommended_goods_list',$recommended_goods_list[1]);
        loadfunc('search');

		//输出分页
		Tpl::output('show_page',$model_goods->showpage('5'));
		$stc_class = Model('store_goods_class');
		$stc_info = $stc_class->getStoreGoodsClassInfo(array('stc_id' => intval($_GET['stc_id'])));
		Tpl::output('stc_name',$stc_info['stc_name']);
		Tpl::output('page','index');

		Tpl::showpage('goods_list');
	}

	/**
	 * ajax获取动态数量
	 */
	function ajax_store_trend_countOp(){
		$count = Model('store_sns_tracelog')->getStoreSnsTracelogCount(array('strace_storeid'=>$this->store_info['store_id']));
		echo json_encode(array('count'=>$count));exit;
	}
	/**
	 * ajax 店铺流量统计入库
	 */
	public function ajax_flowstat_recordOp(){
	    $store_id = intval($_GET['store_id']);
	    if ($store_id <= 0 || $_SESSION['store_id'] == $store_id){
	        echo json_encode(array('done'=>true,'msg'=>'done')); die;
	    }
		//确定统计分表名称
		$last_num = $store_id % 10; //获取店铺ID的末位数字
		$tablenum = ($t = intval(C('flowstat_tablenum'))) > 1 ? $t : 1; //处理流量统计记录表数量
		$flow_tablename = ($t = ($last_num % $tablenum)) > 0 ? "flowstat_$t" : 'flowstat';
		//判断是否存在当日数据信息
		$stattime = strtotime(date('Y-m-d',time()));
		$model = Model('stat');
		//查询店铺流量统计数据是否存在
		$store_exist = $model->getoneByFlowstat($flow_tablename,array('stattime'=>$stattime,'store_id'=>$store_id,'type'=>'sum'));
		if ($_GET['act_param'] == 'goods' && $_GET['op_param'] == 'index'){//统计商品页面流量
		    $goods_id = intval($_GET['goods_id']);
		    if ($goods_id <= 0){
		        echo json_encode(array('done'=>false,'msg'=>'done')); die;
		    }
		    $goods_exist = $model->getoneByFlowstat($flow_tablename,array('stattime'=>$stattime,'goods_id'=>$goods_id,'type'=>'goods'));
		}
		//向数据库写入访问量数据
		$insert_arr = array();
		if($store_exist){
		    $model->table($flow_tablename)->where(array('stattime'=>$stattime,'store_id'=>$store_id,'type'=>'sum'))->setInc('clicknum',1);
		} else {
		    $insert_arr[] = array('stattime'=>$stattime,'clicknum'=>1,'store_id'=>$store_id,'type'=>'sum','goods_id'=>0);
		}
		if ($_GET['act_param'] == 'goods' && $_GET['op_param'] == 'index'){//已经存在数据则更新
		    if ($goods_exist){
		        $model->table($flow_tablename)->where(array('stattime'=>$stattime,'goods_id'=>$goods_id,'type'=>'goods'))->setInc('clicknum',1);
		    } else {
		        $insert_arr[] = array('stattime'=>$stattime,'clicknum'=>1,'store_id'=>$store_id,'type'=>'goods','goods_id'=>$goods_id);
		    }
		}
		if ($insert_arr){
		    $model->table($flow_tablename)->insertAll($insert_arr);
		}
		echo json_encode(array('done'=>true,'msg'=>'done'));
	}
}
?>
