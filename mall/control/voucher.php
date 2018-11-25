<?php
/**
 * 领取免费代金券
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;


defined('Inshopec') or exit('Access Invalid!');
class voucherControl extends BaseHomeControl{
    public function __construct() {
        parent::__construct();
        parent::checkLogin();
    }
    /**
     * 免费代金券页面
     */
    public function getvoucherOp() {
        $t_id = intval($_GET['tid']);
        $error_url = getReferer();
        if (!$error_url){
            $error_url = 'index.php';
        }
        if($t_id <= 0){
            showDialog('代金券信息错误',$error_url,'error');
        }
        $model_voucher = Model('voucher');
        //获取领取方式
        $gettype_array = $model_voucher->getVoucherGettypeArray();
        //获取代金券状态
        $templatestate_arr = $model_voucher->getTemplateState();
        //查询代金券模板详情
        $where = array();
        $where['voucher_t_id'] = $t_id;
        $where['voucher_t_gettype'] = $gettype_array['free']['sign'];
        $where['voucher_t_state'] = $templatestate_arr['usable'][0];
        $where['voucher_t_end_date'] = array('gt',time());
        $template_info = $model_voucher->getVoucherTemplateInfo($where);
        if (empty($template_info)){
            showDialog('代金券信息错误', $error_url, 'error');
        }
        if ($template_info['voucher_t_total']<=$template_info['voucher_t_giveout']){//代金券不存在或者已兑换完
            showDialog('代金券已兑换完', $error_url, 'error');
        }
        TPL::output('template_info',$template_info);
        Tpl::showpage('voucher.getvoucher');
    }
    /**
     * 领取免费代金券
     */
    public function getvouchersaveOp() {
        $t_id = intval($_GET['tid']);
        if($t_id <= 0){
            showDialog('代金券信息错误','','error');
        }
        $model_voucher = Model('voucher');
        //验证是否可领取代金券
        $data = $model_voucher->getCanChangeTemplateInfo($t_id, intval($_SESSION['member_id']), intval($_SESSION['store_id']));
        if ($data['state'] == false){
            showDialog($data['msg'], '', 'error');
        }
        try {
            $model_voucher->beginTransaction();
            //添加代金券信息
            $data = $model_voucher->exchangeVoucher($data['info'], $_SESSION['member_id'], $_SESSION['member_name']);
            if ($data['state'] == false) {
                throw new Exception($data['msg']);
            }
            $model_voucher->commit();
            showDialog('代金券领取成功', ($_GET['jump'] === '0') ? '':urlMember('member_voucher', 'voucher_list'), 'succ');
        } catch (Exception $e) {
            $model_voucher->rollback();
            showDialog($e->getMessage(), '', 'error');
        }
        
    }
}
