<?php
/**
 * 任务计划 - 小时执行的任务
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

class storesearchControl extends BaseCronControl {
    /**
     * 执行频率常量 1小时
     * @var int
     */
    const EXE_TIMES = 3600;

    private $_doc;
    private $_xs;
    private $_index;
    private $_search;
    private $_contract_item;

    /**
     * 默认方法
     */
    public function indexOp() {
        //更新全文搜索内容
        $this->_xs_update();
    }

    /**
     * 初始化对象
     */
    private function _ini_xs(){
        require(BASE_DATA_PATH.'/api/xs/lib/XS.php');
        $this->_doc = new XSDocument();
        $this->_xs = new XS(C('fullindexer.appstorename'));
        $this->_index = $this->_xs->index;
        $this->_search = $this->_xs->search;
        $this->_search->setCharset(CHARSET);

    }

    /**
     * 全量创建索引
     */
    public function xs_createOp() {
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();
        $model = Model();
        try {
            //每次批量更新店铺数
            $step_num = 10;
            $model_store = Model('store');
            $condition['store_state'] = 1;
            $count = $model_store->getStoreCount($condition);
            echo 'StoreTotal:'.$count."\n";
            if ($count != 0) {
                for ($i = 0; $i <= $count; $i = $i + $step_num){
                    $goods_list = $model_store->getStoreList($condition,'','', '*', "{$i},{$step_num}");
                    $this->_build_goods($goods_list);
                    echo $i." ok\n";
                    flush();
                    ob_flush();
                }
            }

            if ($count > 0) {
                sleep(2);
                $this->_index->flushIndex();
                sleep(2);
                $this->_index->flushLogging();
            }
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    /**
     * 更新增量索引
     */
    public function _xs_update() {
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();
        $model = Model();
        try {
            //更新多长时间内的新增(编辑)商品信息，该时间一般与定时任务触发间隔时间一致,单位是秒,默认3600
            $step_time = self::EXE_TIMES + 60;
            //每次批量更新商品数
            $step_num = 100;

            $model_store = Model('store');
            $condition = array();
            $condition['store_time'] = array('egt',TIMESTAMP-$step_time);
            $condition['store_state'] = 1;
            $count = $model_store->getStoreCount($condition);
            echo 'StoreTotal:'.$count."\n";
            if ($count != 0) {
                for ($i = 0; $i <= $count; $i = $i + $step_num){
                    $goods_list = $model_store->getStoreList($condition,'','', '*', "{$i},{$step_num}");
                    $this->_build_goods($goods_list);
                    echo $i." ok\n";
                    flush();
                    ob_flush();
                }
            }
            if ($count > 0) {
                sleep(2);
                $this->_index->flushIndex();
                sleep(2);
                $this->_index->flushLogging();
            }
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    /**
     * 索引商品数据
     * @param array $goods_list
     */
    private function _build_goods($goods_list = array()) {
        if (empty($goods_list) || !is_array($goods_list)) return;

        //整理需要索引的数据
        foreach ($goods_list as $k => $v) {

            $index_data = array();
            $index_data['storepk'] = $v['store_id'];
            $index_data['store_id'] = $v['store_id'];
            $index_data['store_name'] = $v['store_name'];



            
            //添加到索引库
            $this->_doc->setFields($index_data);
            $this->_index->update($this->_doc);
        }
    }

    public function xs_clearOp(){
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();

        try {
            $this->_index->clean();
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    public function xs_flushLoggingOp(){
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();
        try {
            $this->_index->flushLogging();
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    public function xs_flushIndexOp(){
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();

        try {
            $this->_index->flushIndex();
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }
}
