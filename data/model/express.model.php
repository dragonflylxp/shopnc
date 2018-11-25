<?php
/**
 * 快递模型
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');
class expressModel extends Model {
    public function __construct(){
        parent::__construct('express');
    }

    /**
     * 查询快递列表
     *
     * @param string $id 指定快递编号
     * @return array
     */
    public function getExpressList() {
        return rkcache('express', true);
    }

    /**
     * 根据编号查询快递列表
     */
    public function getExpressListByID($id = null) {
        $express_list = rkcache('express', true);

        if(!empty($id)) {
            $id_array = explode(',', $id);
            foreach ($express_list as $key => $value) {
                if(!in_array($key, $id_array)) {
                    unset($express_list[$key]);
                }
            }
            return $express_list;
        } else {
            return array();
        }
    }

    /**
     * 查询详细信息
     */
    public function getExpressInfo($id) {
        $express_list = $this->getExpressList();
        return $express_list[$id];
    }
    /**
     * 根据快递公司ecode获得快递公司信息
     * @param $ecode string 快递公司编号
     * @return array 快递公司详情
     */
    public function getExpressInfoByECode($ecode){
        $ecode = trim($ecode);
        if (!$ecode){
            return array('state'=>false,'msg'=>'参数错误');
        }
        $express_list = $this->getExpressList();
        $express_info = array();
        if ($express_list){
            foreach ($express_list as $v){
                if ($v['e_code'] == $ecode){
                    $express_info = $v;
                }
            }
        }
        if (!$express_info){
            return array('state'=>false,'msg'=>'快递公司信息错误');
        } else {
            return array('state'=>true,'data'=>array('express_info'=>$express_info));
        }
    }
    /**
     * 查询物流信息
     * @param unknown $e_code
     * @param unknown $shipping_code
     * @return multitype:
     */
    function get_express($e_code, $shipping_code) {
        $result = array();
        if (C('express_api') == '2'){//快递鸟
            $_info = $this->getOrderTracesByJson($e_code, $shipping_code);
            $list = $_info['Traces'];
            if (!empty($list) && is_array($list)) {
                foreach ($list as $key => $value) {
                    $v = array();
                    $v['time'] = $value['AcceptTime'];
                    $v['context'] = $value['AcceptStation'];
                    $result[] = $v;
                }
            }
        } else {//快递100
            $post_data = array();
            $post_data["customer"] = C('express_kuaidi100_id');
            $key= C('express_kuaidi100_key');
            $post_data["param"] = "{'com':'$e_code','num':'$shipping_code'}";
            $post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
            $post_data["sign"] = strtoupper($post_data["sign"]);
            $ch = curl_init();
        	curl_setopt($ch, CURLOPT_POST, 1);
        	curl_setopt($ch, CURLOPT_HEADER, 0);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        	curl_setopt($ch, CURLOPT_URL,'http://poll.kuaidi100.com/poll/query.do');
        	$o=""; 
            foreach ($post_data as $k=> $v){
                $o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
            }
            $post_data=substr($o,0,-1);
        	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        	$result = curl_exec($ch);
        	curl_close($ch);
        	$data = str_replace("\&quot;",'"',$result);
        	$data = json_decode($data,true);
            $result = array_reverse($data['data']);
        }
        return $result;
    }
    /**
     * 快递鸟查询物流轨迹
     */
    function getOrderTracesByJson($e_code, $shipping_code){
    	$requestData= "{'ShipperCode':'$e_code','LogisticCode':'$shipping_code'}";
    	
        $kdniao_id = C('express_kdniao_id');
        $kdniao_key = C('express_kdniao_key');
    	$datas = array(
            'EBusinessID' => $kdniao_id,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = urlencode(base64_encode(md5($requestData.$kdniao_key)));
        
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx');
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
		$data = curl_exec($ch);
		curl_close($ch);
    	$result=json_decode($data, true);	
    	return $result;
    }
}
