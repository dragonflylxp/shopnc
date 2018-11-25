<?php
/**
 * 分销店铺页
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class storeControl extends BaseDistributeControl
{

    const PAGESIZE = 15;
    public function indexOp()
    {
        $store_id = intval($_GET['store_id']);
        //店铺信息
        $store_info = Model('store')->getStoreInfoByID($store_id);
        Tpl::output('store_info', $store_info);

        //统计
        $cur_time = time();
        $start_time = strtotime(date('Y-m-d',strtotime('-30 day')));
        $condition = array();
        $condition['distri_time'] = array('time',array($start_time,$cur_time));
        $condition['store_id'] = $store_id;
        $model_dis_goods = Model('dis_goods');
        $distri_goods_count = $model_dis_goods->getDistriGoodsCount($condition);

        Tpl::output('distri_count',intval($distri_goods_count));

        $model_dis_bill = Model('dis_bill');
        $condition = array();
        $condition['store_id'] = $store_id;
        $condition['dis_pay_time'] = array('time',array($start_time,$cur_time));
        $distri_bill_count = $model_dis_bill->getDistriBillInfo($condition,'sum(dis_pay_amount) as dis_pay_amount');

        Tpl::output('bill_count',floatval($distri_bill_count['dis_pay_amount']));

        //分销商品列表
        $order = 'goods_commonid desc';
        $fields = "goods_commonid,goods_name,goods_jingle,gc_id,store_id,store_name,goods_price,goods_image,sale_count,click_count,gc_id_3,gc_id_1,gc_id_2,goods_verify,goods_state,is_own_shop,areaid_1,dis_commis_rate";
        $condition = array();
        $condition['is_dis'] = 1;
        if (C('dbdriver') == 'oracle') {
            $oracle_fields = array();
            $fields = explode(',', $fields);
            foreach ($fields as $val) {
                $oracle_fields[] = 'min(' . $val . ') ' . $val;
            }
            $fields = implode(',', $oracle_fields);
        }
        $condition['store_id'] = $store_id;
        $model_goods = Model('goods');
        $goods_list = $model_goods->getGoodsCommonOnlineList($condition, $fields, self::PAGESIZE, $order);
        if (!empty($goods_list)) {
            //查库搜索
            $commonid_array = array(); // 商品公共id数组
            foreach ($goods_list as $value) {
                $commonid_array[] = $value['goods_commonid'];
            }
            // 商品多图
            $goodsimage_more = $model_goods->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)), '*', 'is_default desc,goods_image_id asc');

            //搜索的关键字
            foreach ($goods_list as $key => $value) {
                foreach ($goodsimage_more as $v) {
                    if ($value['goods_commonid'] == $v['goods_commonid'] && $v['is_default'] == 1) {
                        $goods_list[$key]['image'][] = $v['goods_image'];
                    }
                }
                $goods_list[$key]['store_domain'] = $store_info['store_domain'];
            }
        }

        $distri_info = $this->_distri_count($store_id);
        $bill_info = $this->_bill_count($store_id);
        Tpl::output('distri_info',$distri_info);
        Tpl::output('bill_info',$bill_info);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage(7));

        Tpl::showpage('store');
    }

    /**
     * 获取30天分销量统计
     */
    private function _distri_count($store_id){
        $return = array();
        $model = Model();
        $cur_time = time();
        $start_time = strtotime(date('Y-m-d',strtotime('-30 day')));
        $condition = array();
        $condition['distri_time'] = array('time',array($start_time,$cur_time));
        $condition['store_id'] = $store_id;
        $rest = $model->table('dis_goods')->field('FROM_UNIXTIME(distri_time,\'%Y/%m/%d\') as distri_times,count(*) as dis_amount')->where($condition)->group('distri_times')->select();
        $date_arr = $this->_dateList($cur_time,$start_time);
        $data_arr = array();
        $keys_arr = array();
        if(!empty($rest)){
            foreach($rest as $value){
                $data_arr[$value['distri_times']] = $value['dis_amount'];
                $keys_arr[] = $value['distri_times'];
            }
        }
        foreach($date_arr as $value){
            if(!in_array($value,$keys_arr)){
                $data_arr[$value] = 0;
            }
        }
        ksort($data_arr);
        $tmp_data = array_values($data_arr);
        $return['labels'] = $date_arr;
        $return['datasets'] = array(
            'fillColor' => 'rgba(255,68,0,.3)',
            'strokeColor' => 'rgba(255,68,0,.6)',
            'pointColor' => 'rgba(255,68,0,.9)',
            'pointStrokeColor' => '#fff',
            'data' => $tmp_data,
        );
        $max_val = max($tmp_data);
        Tpl::output('distri_opt',ceil($max_val/5) + 1);

        return $this->_getStr($return,'count');
    }

    /**
     * 获取30天佣金统计
     */
    private function _bill_count(){
        $store_id = intval($_GET['store_id']);
        $return = array();
        $model = Model();
        $cur_time = time();
        $start_time = strtotime(date('Y-m-d',strtotime('-30 day')));
        $condition = array();
        $condition['dis_pay_time'] = array('time',array($start_time,$cur_time));
        $condition['store_id'] = $store_id;
        $rest = $model->table('dis_pay')->field('FROM_UNIXTIME(dis_pay_time,\'%Y/%m/%d\') as dis_pay_times,sum(dis_pay_amount) as dis_pay_amount')->where($condition)->group('dis_pay_times')->select();
        $date_arr = $this->_dateList($cur_time,$start_time);
        $data_arr = array();
        $keys_arr = array();
        if(!empty($rest)){
            foreach($rest as $value){
                $data_arr[$value['dis_pay_times']] = $value['dis_pay_amount'];
                $keys_arr[] = $value['dis_pay_times'];
            }
        }
        foreach($date_arr as $value){
            if(!isset($data_arr[$value])){
                $data_arr[$value] = 0;
            }
        }
        ksort($data_arr);
        $tmp_data = array_values($data_arr);
        $return['labels'] = $date_arr;
        $return['datasets'] = array(
            'fillColor' => 'rgba(67,133,222,.3)',
            'strokeColor' => 'rgba(67,133,222,.6)',
            'pointColor' => 'rgba(67,133,222,.9)',
            'pointStrokeColor' => '#fff',
            'data' => $tmp_data,
        );
        $max_val = max($tmp_data);
        Tpl::output('bill_opt',ceil($max_val/5) + 1);

        return $this->_getStr($return,'bill');
    }

    private function _getStr($param){
        $date_str = '';
        foreach($param['labels'] as $val){
            $date_str .= ',"'.$val.'"';
        }

        $date_str = substr($date_str,1);

        $data_str = implode(',',$param['datasets']['data']);
            $str = <<<EOF
{
  labels : [$date_str],
  datasets : [
	{
      fillColor : "{$param['datasets']['fillColor']}",
      strokeColor : "{$param['datasets']['strokeColor']}",
      pointColor : "{$param['datasets']['pointColor']}",
      pointStrokeColor : "{$param['datasets']['pointStrokeColor']}",
      data : [$data_str]
    }
  ]
}
EOF;
        return $str;

    }

    private function _dateList($cur_time,$start_time){
        $arr = array();
        $i = 1;
        while(true){
            $m = $i*86400;
            $t = $start_time + $m;
            if($t >= $cur_time){
                break;
            }
            $arr[] = date('Y/m/d',$t);
            $i++;
        }
        return $arr;
    }

}