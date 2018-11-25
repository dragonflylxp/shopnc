<?php
/**
 * 平台兑换券
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class exchange_cardControl extends SystemControl
{
    /**
     * Must be larger than page size of pagination
     */
    const EXPORT_SIZE = 100;

    public function __construct()
    {
        parent::__construct();
    }

    public function indexOp()
    {
        Tpl::showpage('exchange_card.index');
    }

    protected function getConditionAndSort()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            foreach (array('sn', 'batchflag', 'admin_name', ) as $sk) {
                if (strlen($q = trim((string) $_REQUEST[$sk]))) {
                    $condition[$sk] = array('like', '%' . $q . '%');
                }
            }
            if (strlen($q = trim((string) $_REQUEST['member_name']))) {
                $condition['member_name'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['state']))) {
                $condition['state'] = (int) $q;
            }

            $sdate = $_GET['sdate'] ? strtotime($_GET['sdate'] . ' 00:00:00') : 0;
            $edate = $_GET['edate'] ? strtotime($_GET['edate'] . ' 00:00:00') : 0;
            if ($sdate > 0 || $edate > 0) {
                $condition['tscreated'] = array('time', array($sdate, $edate));
            }

            $sdate = $_GET['sdate2'] ? strtotime($_GET['sdate2'] . ' 00:00:00') : 0;
            $edate = $_GET['edate2'] ? strtotime($_GET['edate2'] . ' 00:00:00') : 0;
            if ($sdate > 0 || $edate > 0) {
                $condition['tsused'] = array('time', array($sdate, $edate));
            }

        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'sn':
                    case 'batchflag':
                    case 'admin_name':
                        $condition[$_REQUEST['qtype']] = array('like', '%' . $q . '%');
                        break;
                    case 'member_name':
                        $condition[$_REQUEST['qtype']] = $q;
                        break;
                }
            }
        }

        switch ($_REQUEST['sortname']) {
            case 'denomination':
            case 'tscreated':
            case 'tsused':
                $sort = $_REQUEST['sortname'];
                break;
            default:
                $sort = 'id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }

        return array(
            $condition,
            $sort,
        );
    }

    public function index_xmlOp()
    {
        list($condition, $sort) = $this->getConditionAndSort();

        $model = Model('exchange_card');
        $list = (array) $model->getRechargeCardList($condition, $_REQUEST['rp'], null, $sort);
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($list as $val) {
            $i = array();

            $isUsed = $val['state'] == 1 && $val['member_id'] > 0 && $val['tsused'] > 0;

            $i['operation'] = $isUsed ? '--' : <<<EOB
<a class="btn green confirm-del-on-click" href="javascript:;" data-href="index.php?con=exchange_card&fun=del_card&id={$val['id']}"><i class="fa fa-trash"></i>删除</a>
EOB;

            $i['sn'] = $val['sn'];
            $i['batchflag'] = $val['batchflag'];
            $i['denomination'] = $val['denomination'];
            $i['admin_name'] = $val['admin_name'];
            $i['tscreated'] = date('Y-m-d H:i:s', $val['tscreated']);

            if ($val['member_name']) {
                $i['member_name'] = $val['member_name'];
            } else {
                $i['member_name'] = '-';
            }
            if($val['state']==0){
                $i['tsused'] = '';
                $i['state'] = '可用';
            }
            if($val['state']==1){
                $i['tsused'] = date('Y-m-d H:i:s', $val['tsused']);
                $i['state'] = '已用';
            }
            $i['start_time'] = date("Y-m-d", $val['start_time']);
            if($val['end_time']==null){
                $i['end_time']='永久可用';
            }else{
                $i['end_time'] = date("Y-m-d", $val['end_time']);
            }
            $data['list'][$val['id']] = $i;

        }


        echo Tpl::flexigridXML($data);
        exit;
    }

    public function add_cardOp()
    {
        if (!chksubmit()) {
            Tpl::showpage('exchange_card.add_card');
            return;
        }

        $denomination = (float) $_POST['denomination'];
        if ($denomination < 0.01) {
            showMessage('面额不能小于0.01', '', 'html', 'error');
            return;
        }
        if ($denomination > 1000) {
            showMessage('面额不能大于1000', '', 'html', 'error');
            return;
        }

        $total = (int) $_POST['total'];
            if ($total < 1 || $total > 9999) {
                showMessage('总数只能是1~9999之间的整数', '', 'html', 'error');
                exit;
            }
        $model = Model('exchange_card');
        $startTime = $_POST['start_time'];
        $sTime = strtotime($startTime);
        $endTime = $_POST['end_time'];
        if($endTime==null){
            $endTime=null;
        }else{
            $eTime = strtotime($endTime);
        }

        $batchflag = $_POST['batchflag'];
        $func_name = $_POST['func_name'];
        $adminName = $this->admin_info['name'];
        $ts = time();
        //查询出数据库所有券号
        $arr=$model->field('sn')->select();
        $sn_old=array_column($arr,'sn');
        $snToInsert = array();
        for($i=1;$i<=$total;$i++){
            $sn_code = $this->checkSn($sn_old);
            $snToInsert[] = array(

                'sn' => $sn_code,
                'denomination' => $denomination,
                'batchflag' => $batchflag,
                'admin_name' => $adminName,
                'tscreated' => $ts,
                'start_time' => $sTime,
                'end_time' =>$eTime,
                'func_name'=>$func_name
            );
            $sn_old[] = $sn_code;
        }

        if (!$model->insertAll($snToInsert)) {
            showMessage('操作失败', '', 'html', 'error');
            exit;
        }

//        操作日志
        $countInsert = count($snToInsert);
        $this->log("新增{$countInsert}张兑换券（面额￥{$denomination}，批次标识“{$batchflag}”）");

        $msg = '操作成功';
        showMessage($msg, urlAdminShop('exchange_card', 'index'));
    }

    /**
     * 获取平台兑换券券号
     * @param $sn_old
     * @return string
     */
    private function checkSn($sn_old){
        $sn_code = $this->getRandomString(12);
        if(in_array($sn_code,$sn_old)){
            $this->checkSn($sn_old);
        }else{
            return $sn_code;
        }
    }

    public function del_cardOp()
    {
        if (empty($_GET['id'])) {
            showMessage('参数错误', '', 'html', 'error');
        }

        $id = trim($_GET['id']);
        if (is_string($id) && strpos($id, ',') !== false) {
            $id = explode(',', $id);
        }

        $count = count($id);
        Model('exchange_card')->delRechargeCardById($id);

        $this->log("删除{$count}张兑换券（#ID: {$_GET['id']}）");

        $this->jsonOutput();
    }
    /**
     * 生成随机数sn
     */
    public function getRandomString($len)
    {
        $chars = "0123456789";
        $str = "";
        $lc = strlen($chars)-1;
        for ($i = 0;$i < $len; $i++){
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
    /**
     * 导出
     */
    public function export_step1Op()
    {
        $model = Model('exchange_card');

        if ($_REQUEST['ids']) {
            $condition = array();
            $condition['id'] = array('in', $_REQUEST['ids']);
            $sort = null;
        } else {
            list($condition, $sort) = $this->getConditionAndSort();
        }

        if (!is_numeric($_GET['curpage'])) {
            $count = $model->getRechargeCardCount($condition);
            $array = array();
            //显示下载链接
            if ($count > self::EXPORT_SIZE) {
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i * self::EXPORT_SIZE > $count ? $count : $i * self::EXPORT_SIZE;
                    $array[$i] = $limit1 . ' ~ ' . $limit2;
                }
                Tpl::output('list', $array);
                Tpl::output('murl', 'index.php?con=exchange_card&fun=index');
                Tpl::showpage('export.excel');
                return;
            }

            //如果数量小，直接下载
            $data = $model->getRechargeCardList($condition, self::EXPORT_SIZE, null, $sort);
            $this->createExcel($data);
            return;
        }

        //下载
        $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
        $limit2 = self::EXPORT_SIZE;

        $data = $model->getRechargeCardList($condition, 20, "{$limit1},{$limit2}", $sort);

        $this->createExcel($data);
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'兑换券券号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'批次标识');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'面额(元)');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'发布管理员');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'发布时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'使用人');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'使用时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'使用状态');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'开始使用时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'结束使用时间');

        //data
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>"\t".$v['sn']);
            $tmp[] = array('data'=>"\t".$v['batchflag']);
            $tmp[] = array('data'=>"\t".$v['denomination']);
            $tmp[] = array('data'=>"\t".$v['admin_name']);
            $tmp[] = array('data'=>"\t".date('Y-m-d H:i:s', $v['tscreated']));
            if ($v['state'] == 1 && $v['member_id'] > 0 && $v['tsused'] > 0) {
                $tmp[] = array('data'=>"\t".$v['member_name']);
                $tmp[] = array('data'=>"\t".date('Y-m-d H:i:s', $v['tsused']));
            } else {
                $tmp[] = array('data'=>"\t-");
                $tmp[] = array('data'=>"\t");
            }
            if($v['state']==0){
                $tmp[] = array('data'=>"\t".'可用');
            }
            if($v['state']==1){
                $tmp[] = array('data'=>"\t".'已用');
            }
            $tmp[] = array('data'=>"\t".date('Y-m-d H:i:s', $v['start_time']));
            if($v['end_time']==null){
                $tmp[] = array('data'=>"\t".'永久可用');
            }else{
                $tmp[] = array('data'=>"\t".date('Y-m-d H:i:s', $v['end_time']));
            }
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('兑换券',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('兑换券',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }


}
