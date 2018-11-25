<?php
/**
 * 平台充值卡使用日志
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

defined('Inshopec') or exit('Access Invalid!');

class rcb_logModel extends Model
{
    public function __construct()
    {
        parent::__construct('rcb_log');
    }

    /**
     * 获取充值卡使用日志列表
     *
     * @param array $condition 条件数组
     * @param int $pageSize 分页长度
     *
     * @return array 充值卡使用日志列表
     */
    public function getRechargeCardBalanceLogCount($condition)
    {
        return $this->where($condition)->count();
    }

    /**
     * 获取充值卡使用日志列表
     *
     * @param array $condition 条件数组
     * @param int $pageSize 分页长度
     *
     * @return array 充值卡使用日志列表
     */
    public function getRechargeCardBalanceLogList($condition, $pageSize = 20, $limit = null, $sort = 'id desc')
    {
        if ($condition) {
            $this->where($condition);
        }

        if ($sort) {
            $this->order($sort);
        }

        if ($limit) {
            $this->limit($limit);
        } else {
            $this->page($pageSize);
        }

        return $this->select();
    }
}
