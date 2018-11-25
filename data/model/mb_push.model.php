<?php
/**
 * 推送通知记录
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

class mb_pushModel extends Model{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 增加短信记录
     *
     * @param
     * @return int
     */
    public function addPush($log_array) {
        $log_id = $this->table('mb_push')->insert($log_array);
        return $log_id;
    }

    /**
     * 修改记录
     *
     * @param
     * @return bool
     */
    public function editPush($condition, $data) {
        if (empty($condition)) {
            return false;
        }
        if (is_array($data)) {
            $result = $this->table('mb_push')->where($condition)->update($data);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 查询单条记录
     *
     * @param
     * @return array
     */
    public function getPushInfo($condition) {
        if (empty($condition)) {
            return false;
        }
        $result = $this->table('mb_push')->where($condition)->order('log_id desc')->find();
        return $result;
    }

    /**
     * 查询记录
     *
     * @param
     * @return array
     */
    public function getPushList($condition = array(), $page = '', $limit = '', $order = 'log_id desc') {
        $result = $this->table('mb_push')->where($condition)->page($page)->limit($limit)->order($order)->select();
        return $result;
    }
    
    /**
     * 取得记录数量
     *
     * @param
     * @return int
     */
    public function getPushCount($condition) {
        return $this->table('mb_push')->where($condition)->count();
    }

    /**
     * 百度云推送接口对象
     *
     * @param
     * @return array
     */
    public function getPushSDK($api_key,$api_secret) {
        $sdk = array();
        if (!empty($api_key) && !empty($api_secret)) {
            require_once (BASE_DATA_PATH.DS.'api'.DS.'baidu'.DS.'sdk.php');
            $sdk = new PushSDK($api_key,$api_secret);
            $rs = $sdk->queryTags(array('limit'=> 5));
            $total_num = $rs['total_num'];
            if ($total_num < 5) {//初始化会员级别标签
                $sdk->createTag('v0');
                $sdk->createTag('v1');
                $sdk->createTag('v2');
                $sdk->createTag('v3');
            }
        }
        return $sdk;
    }
}
