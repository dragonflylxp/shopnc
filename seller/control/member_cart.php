<?php

/**
 * 我的购物车
 *
 *
 *
 *
 */







defined('Inshopec') or exit('Access Invalid!');



class member_cartControl extends mobileMemberControl {



    public function __construct() {

        parent::__construct();

    }

    

    /**

     * 购物车列表

     */

    public function get_cart_listOp() {

        $model_cart = Model('cart');



        $condition = array('buyer_id' => $this->member_info['member_id']);

        $cart_list  = $model_cart->listCart('db', $condition);



        // 购物车列表 [得到最新商品属性及促销信息]

        $cart_list = logic('buy_1')->getGoodsCartList($cart_list, $jjgObj);



		$model_goods = Model('goods');



		$sum = 0;

		$cart_a = array();

		foreach ($cart_list as $key => $val) {

			$val['store_id']=0;

			$cart_a[$val['store_id']]['store_id'] = $val['store_id'];

			$cart_a[$val['store_id']]['store_name'] = $val['store_name'];



			$goods_data = $model_goods->getGoodsOnlineInfoForShare($val['goods_id']);

   

			$cart_a[$val['store_id']]['goods'][$key]= $goods_data;

			

			$cart_a[$val['store_id']]['goods'][$key]['cart_id'] = $val['cart_id'];

			$cart_a[$val['store_id']]['goods'][$key]['goods_num'] = $val['goods_num'];

			$cart_a[$val['store_id']]['goods'][$key]['goods_image_url'] = cthumb($val['goods_image'], $val['store_id']);

			if($goods_data['goods_spec']=='N;'){

				$cart_a[$val['store_id']]['goods'][$key]['goods_spec'] = '';

			}





            $goods_spec =array_values((array) unserialize($cart_a[$val['store_id']]['goods'][$key]['goods_spec']));

        

            $spec_name = array_values((array) unserialize($cart_a[$val['store_id']]['goods'][$key]['spec_name']));

            foreach ($goods_spec as $k => $v) {

                  foreach ($spec_name as $kv => $vv) {

                        $data[$k]['type'] =$spec_name[$k];

                        $data[$k]['name'] =$v;

                     

                }

            }



          

                       // $da. = $vvv['type'].':'.$vvv1['type']

                //$da = $vvv['']

                 



           

            $cart_a[$val['store_id']]['goods'][$key]['goods_type'] = $data;

            

            // $cart_a[$val['store_id']]['goods'][$key]['goods_spec'] = $goods_spec[1];

            // $cart_a[$val['store_id']]['goods'][$key]['spec_name'] = $spec_name[1];



			if($goods_data['goods_promotion_type']){

				$cart_a[$val['store_id']]['goods'][$key]['goods_price'] = $goods_data['goods_promotion_price'];

			}

			$cart_a[$val['store_id']]['goods'][$key]['gift_list'] = $val['gift_list'];

			$cart_list[$key]['goods_sum'] = ncPriceFormat($val['goods_price'] * $val['goods_num']);

			$sum += $cart_list[$key]['goods_sum'];

		}

		

		/*

		$sum = 0;

        foreach ($cart_list as $key => $value) {

			//$model_goods = Model('goods');

			//$cart_list[$key]['goods'] = $model_goods->getGoodsOnlineInfoForShare($value['goods_id']);

            $cart_list[$key]['goods_image_url'] = cthumb($value['goods_image'], $value['store_id']);

            $cart_list[$key]['goods_sum'] = ncPriceFormat($value['goods_price'] * $value['goods_num']);

            $sum += $cart_list[$key]['goods_sum'];

        }

		*/

	

       output_data(array('cart_list' => $cart_a, 'sum' => ncPriceFormat($sum),'cart_count'=>count($cart_list)));

    }



    /**

     * 购物车添加

     */

    public function cart_addOp() {

        $goods_id = intval($_POST['goods_id']);

        $quantity = intval($_POST['quantity']);

        if($goods_id <= 0 || $quantity <= 0) {

            output_error('参数错误');

        }



        $model_goods = Model('goods');

        $model_cart = Model('cart');

        $logic_buy_1 = Logic('buy_1');



        $goods_info = $model_goods->getGoodsOnlineInfoAndPromotionById($goods_id);



        //验证是否可以购买

        if(empty($goods_info)) {

            output_error('商品已下架或不存在');

        }



        //特卖

        $logic_buy_1->getGroupbuyInfo($goods_info);



        //限时折扣

        $logic_buy_1->getXianshiInfo($goods_info,$quantity);



        if ($goods_info['store_id'] == $this->member_info['store_id']) {

            output_error('不能购买自己发布的商品');

        }

        if(intval($goods_info['goods_storage']) < 1 || intval($goods_info['goods_storage']) < $quantity) {

            output_error('库存不足');

        }



        $param = array();

        $param['buyer_id']  = $this->member_info['member_id'];

        $param['store_id']  = $goods_info['store_id'];

        $param['goods_id']  = $goods_info['goods_id'];

        $param['goods_name'] = $goods_info['goods_name'];

        $param['goods_price'] = $goods_info['goods_price'];

        $param['goods_image'] = $goods_info['goods_image'];

        $param['store_name'] = $goods_info['store_name'];



        $result = $model_cart->addCart($param, 'db', $quantity);

        if($result) {

            output_data('1');

        } else {

            output_error('收藏失败');

        }

    }



    /**

    *cookie更新购物车

    */

    public function cart_batchaddOp(){

        

        $cartlist = $_POST['cartlist'];

        $ginfo = explode('|', $cartlist);

        foreach($ginfo as $val){

            if(!empty($val) && $val!='null'){

               $data[] = explode(',', $val); 

            }

            

        }

       

        foreach($data as $v){

            

            $goods_id = intval($v[0]);

            $goods_num = intval($v[1]);

            if($goods_id <= 0 || $goods_num <= 0) {

                output_error('参数错误');

            }

             $model_goods = Model('goods');

            $model_cart = Model('cart');

            $logic_buy_1 = Logic('buy_1');



            $goods_info = $model_goods->getGoodsOnlineInfoAndPromotionById($goods_id);



            //验证是否可以购买

            if(empty($goods_info)) {

               unset($val);

            }

              //特卖

            $logic_buy_1->getGroupbuyInfo($goods_info);



            //限时折扣

            $logic_buy_1->getXianshiInfo($goods_info,$goods_num);



            if ($goods_info['store_id'] == $this->member_info['store_id']) {

               unset($val);

            }

            if(intval($goods_info['goods_storage']) < 1 || intval($goods_info['goods_storage']) < $goods_num) {

                unset($val);

            }

            $param = array();

            $param['buyer_id']  = $this->member_info['member_id'];

            $param['store_id']  = $goods_info['store_id'];

            $param['goods_id']  = $goods_info['goods_id'];

            $param['goods_name'] = $goods_info['goods_name'];

            $param['goods_price'] = $goods_info['goods_price'];

            $param['goods_image'] = $goods_info['goods_image'];

            $param['store_name'] = $goods_info['store_name'];

            $result = $model_cart->addCart($param, 'db', $goods_num);

        }



      

        



       



      



       

        

    }

    /**

     * 购物车删除

     */

    public function cart_delOp() {

        $cart_id = intval($_POST['cart_id']);



        $model_cart = Model('cart');



        if($cart_id > 0) {

            $condition = array();

            $condition['buyer_id'] = $this->member_info['member_id'];

            $condition['cart_id'] = $cart_id;



            $model_cart->delCart('db', $condition);

        }



        output_data('1');

    }



    /**

     * 更新购物车购买数量

     */

    public function cart_edit_quantityOp() {

        $cart_id = intval(abs($_POST['cart_id']));

        $quantity = intval(abs($_POST['quantity']));

        if(empty($cart_id) || empty($quantity)) {

            output_error('参数错误');

        }



        $model_cart = Model('cart');



        $cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id, 'buyer_id' => $this->member_info['member_id']));



        //检查是否为本人购物车

        if($cart_info['buyer_id'] != $this->member_info['member_id']) {

            output_error('参数错误');

        }



        //检查库存是否充足

        if(!$this->_check_goods_storage($cart_info, $quantity, $this->member_info['member_id'])) {

            output_error('超出限购数或库存不足');

        }



        $data = array();

        $data['goods_num'] = $quantity;

        $update = $model_cart->editCart($data, array('cart_id'=>$cart_id));

        if ($update) {

            $return = array();

            $return['quantity'] = $quantity;

            $return['goods_price'] = ncPriceFormat($cart_info['goods_price']);

            $return['total_price'] = ncPriceFormat($cart_info['goods_price'] * $quantity);

            output_data($return);

        } else {

            output_error('修改失败');

        }

    }



    /**

     * 检查库存是否充足

     */

    private function _check_goods_storage(& $cart_info, $quantity, $member_id) {

        $model_goods= Model('goods');

        $model_bl = Model('p_bundling');

        $logic_buy_1 = Logic('buy_1');



        if ($cart_info['bl_id'] == '0') {

            //普通商品

            $goods_info = $model_goods->getGoodsOnlineInfoAndPromotionById($cart_info['goods_id']);



            //特卖

            $logic_buy_1->getGroupbuyInfo($goods_info);

            if ($goods_info['ifgroupbuy']) {

                if ($goods_info['upper_limit'] && $quantity > $goods_info['upper_limit']) {

                    return false;

                }

            }



            //限时折扣

            $logic_buy_1->getXianshiInfo($goods_info,$quantity);



            if(intval($goods_info['goods_storage']) < $quantity) {

                return false;

            }

            $goods_info['cart_id'] = $cart_info['cart_id'];

            $cart_info = $goods_info;

        } else {

            //优惠套装商品

            $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id' => $cart_info['bl_id']));

            $goods_id_array = array();

            foreach ($bl_goods_list as $goods) {

                $goods_id_array[] = $goods['goods_id'];

            }

            $bl_goods_list = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);



            //如果有商品库存不足，更新购买数量到目前最大库存

            foreach ($bl_goods_list as $goods_info) {

                if (intval($goods_info['goods_storage']) < $quantity) {

                    return false;

                }

            }

        }

        return true;

    }

	



	/**

     * 检查购物车数量

     */

	public function cart_countOp() {		

		$model_cart = Model('cart');

		$count = $model_cart->countCartByMemberId($this->member_info['member_id']);

		$data['cart_count'] = $count;

		output_data($data);

	}



}

