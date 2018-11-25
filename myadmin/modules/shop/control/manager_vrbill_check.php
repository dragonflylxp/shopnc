<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/4/004
 * Time: 12:07
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class manager_vrbill_checkControl extends SystemControl{

    /**
     * Must be larger than page size of pagination
     */
    const EXPORT_SIZE = 100;

    private $_links = array(
        array('url'=>'con=manager_vrbill_check&fun=index','text'=>'待审核申请'),
        array('url'=>'con=manager_vrbill_check&fun=get_money_state','text'=>'提现状态')
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function indexOp()
    {
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'index'));
        Tpl::showpage('manager_vrbill_check.index');
    }

    protected function getConditionAndSort()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            foreach (array('uid', 'manager_name' ) as $sk) {
                if (strlen($q = trim((string) $_REQUEST[$sk]))) {
                    $condition[$sk] = array('like', '%' . $q . '%');
                }
            }
            if (strlen($q = trim((string) $_REQUEST['state']))) {
                $condition['state'] = (int) $q;
            }

            $sdate = $_GET['sdate'] ? strtotime($_GET['sdate'] . ' 00:00:00') : 0;
            $edate = $_GET['edate'] ? strtotime($_GET['edate'] . ' 00:00:00') : 0;
            if ($sdate > 0 || $edate > 0) {
                $condition['apply_date'] = array('time', array($sdate, $edate));
            }
            $sdate = $_GET['sdate2'] ? strtotime($_GET['sdate2'] . ' 00:00:00') : 0;
            $edate = $_GET['edate2'] ? strtotime($_GET['edate2'] . ' 00:00:00') : 0;
            if ($sdate > 0 || $edate > 0) {
                $condition['pay_date'] = array('time', array($sdate - 1, $edate));
            }

        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'uid':
                    case 'manager_name':
                        $condition[$_REQUEST['qtype']] = array('like', '%' . $q . '%');
                        break;

                }
            }
        }

        switch ($_REQUEST['sortname']) {
            case 'start_time':
            case 'end_time':
            case 'uid':
                $sort = $_REQUEST['sortname'];
                break;
            default:
                $sort = 'grade';
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

    /**
     * 显示管理人实物结算列表审核
     * 申请状态，1默认未申请，2申请，3审核完成，4确认打款，5审核未通过
     */

    public function index_xmlOp()
    {
        list($condition, $sort) = $this->getConditionAndSort();
        $condition['state'] = array('not in',array(1,3,4));

        $model = Model('manager_vr_bill');
        $list = (array) $model->getManagerBillList($condition, $_REQUEST['rp'], null, $sort);

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($list as $val) {
            $i = array();


            if(in_array(intval($val['state']), array(2))) {
                $operation = "<a class='btn orange' href=\"index.php?con=manager_vrbill_check&fun=show_manager_vr_bill&member_id=". $val['uid'] ."\"><i class=\"fa fa-check-circle\"></i>审核</a>";
            } else {
                $operation = "<a class='btn green' href=\"index.php?con=manager_vrbill_check&fun=show_manager_vr_bill&member_id=". $val['uid'] ."\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            }
            $i['operation'] = $operation;

            $i['uid'] = $val['uid'];
            $i['manager_name'] = $val['manager_name'];
            if($val['grade']==1){
                $i['grade']='大区级';
            }
            if($val['grade']==2){
                $i['grade']='省级';
            }
            if($val['grade']==3){
                $i['grade']='市级';
            }
            if($val['grade']==4){
                $i['grade']='区、县级';
            }

            $area = '';
            if(!empty($val['area_region'])){
                $area = $val['area_region'];
            }
            if(!empty($val['province_id'])){
                $v = $model->getAreaInfo($val['province_id']);

                $area.= "&nbsp".$v['area_name'];
            }
            if(!empty($val['city_id'])){
                $v = $model->getAreaInfo($val['city_id']);
                $area.= "&nbsp".$v['area_name'];
            }
            if(!empty($val['district_id'])){
                $v = $model->getAreaInfo($val['district_id']);
                $area.= "&nbsp".$v['area_name'];
            }
            $i['area'] = $area;
            $i['total'] = $val['total'];

            if(!empty($val['apply_date'])){
                $i['apply_date'] = date('Y-m-d', $val['apply_date']);
            }else{
                $i['apply_date'] = '--';
            }
            //审核状态
            if($val['state'] == 2){
                $i['end_time'] = '申请提现';
            }
            if($val['state'] == 5){
                $i['state'] = '审核未通过';
            }


            $data['list'][$val['mb_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }


    /**
     * 展示管理人提现信息
     *
     */
    public function show_manager_vr_billOp(){

        $member_id = intval($_GET['member_id']);

        if ($member_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_manager_real_bill = Model('manager_vr_bill');
        $manager_real_bill_info = $model_manager_real_bill->getManagerVrBillInfo(array('member_id'=>$member_id));

        if($manager_real_bill_info['grade'] == 1){
            $manager_real_bill_info['grade'] = '大区级';
        }
        if($manager_real_bill_info['grade'] == 2){
            $manager_real_bill_info['grade'] = '省级';
        }
        if($manager_real_bill_info['grade'] == 3){
            $manager_real_bill_info['grade'] = '市级';
        }
        if($manager_real_bill_info['grade'] == 4){
            $manager_real_bill_info['grade'] = '区、县级';
        }
        if (!$manager_real_bill_info){
            showMessage('参数错误','','html','error');
        }


        Tpl::output('bill_info',$manager_real_bill_info);
        Tpl::showpage('manager_vrbill_check.show');
    }


    /**
     * 管理人提现审核
     */
    public function bill_check_verifyOp() {
        $model_manager = Model('manager_vr_bill');
//        $manager_state = $model_manager->getOne(array('manager_id'=>$_POST['manager_id']));
        $member_id = $_POST['member_id'];
        $verify_type = $_POST['verify_type'];
        $param = array();
        if($verify_type == 3){
            $param['state'] = 3;
            $this->log("管理人ID为 :".$member_id." 的地区管理人虚拟提现申请审核通过");
        }
        if($verify_type == 5){
            $param['state'] = 5;
            $param['pay_content'] = $_POST['pay_content'];
            $this->log("管理人ID为 :".$member_id." 的地区管理人虚拟提现申请审核失败");
        }

        $result = $model_manager->editManagerInfo(array('uid' => $member_id), $param);
        if ($result) {
            showMessage('提交成功', 'index.php?con=manager_vrbill_check&fun=index');
        } else {
            showMessage('提交失败');
        }

    }

    /**
     * 提现状态显示页面
     */

    public function get_money_stateOp()
    {
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'get_money_state'));
        Tpl::showpage('manager_vrbill_state.index');
    }
    /**
     * 显示管理人实物结算列表审核
     * 申请状态，1默认未申请，2申请，3审核完成，4确认打款，5审核未通过
     */

    public function get_index_xmlOp()
    {
        list($condition, $sort) = $this->getConditionAndSort();
        $condition['state'] = array('not in',array(1,2,5));

        $model = Model('manager_vr_bill');
        $list = (array) $model->getManagerBillList($condition, $_REQUEST['rp'], null, $sort);
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($list as $val) {
            $i = array();


            if(in_array(intval($val['state']), array(3))) {
                $operation = "<a class='btn orange' href=\"index.php?con=manager_vrbill_check&fun=show_manager_state&member_id=". $val['uid'] ."\"><i class=\"fa fa-check-circle\"></i>确认提现</a>";
            } else {
                $operation = "<a class='btn green' href=\"index.php?con=manager_vrbill_check&fun=show_manager_state&member_id=". $val['uid'] ."\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            }
            $i['operation'] = $operation;

            $i['uid'] = $val['uid'];
            $i['manager_name'] = $val['manager_name'];
            if($val['grade']==1){
                $i['grade']='大区级';
            }
            if($val['grade']==2){
                $i['grade']='省级';
            }
            if($val['grade']==3){
                $i['grade']='市级';
            }
            if($val['grade']==4){
                $i['grade']='区、县级';
            }

            $area = '';
            if(!empty($val['area_region'])){
                $area = $val['area_region'];
            }
            if(!empty($val['province_id'])){
                $v = $model->getAreaInfo($val['province_id']);

                $area.= "&nbsp".$v['area_name'];
            }
            if(!empty($val['city_id'])){
                $v = $model->getAreaInfo($val['city_id']);
                $area.= "&nbsp".$v['area_name'];
            }
            if(!empty($val['district_id'])){
                $v = $model->getAreaInfo($val['district_id']);
                $area.= "&nbsp".$v['area_name'];
            }
            $i['area'] = $area;
            $i['total'] = $val['total'];

            if(!empty($val['apply_date'])){
                $i['apply_date'] = date('Y-m-d', $val['apply_date']);
            }else{
                $i['apply_date'] = '--';
            }
            if(!empty($val['pay_date'])){
                $i['pay_date'] = date('Y-m-d', $val['pay_date']);
            }else{
                $i['pay_date'] = '未付款';
            }

            //审核状态
            if($val['state'] == 3){
                $i['end_time'] = '审核完成';
            }
            if($val['state'] == 4){
                $i['state'] = '已打款';
            }


            $data['list'][$val['mb_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }
    /**
     * 地区管理人提现状态
     *
     */
    public function show_manager_stateOp(){

        $member_id = intval($_GET['member_id']);

        if ($member_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_manager_real_bill = Model('manager_vr_bill');
        $manager_real_bill_info = $model_manager_real_bill->getManagerVrBillInfo(array('member_id'=>$member_id));
        if($manager_real_bill_info['grade'] == 1){
            $manager_real_bill_info['grade'] = '大区级';
        }
        if($manager_real_bill_info['grade'] == 2){
            $manager_real_bill_info['grade'] = '省级';
        }
        if($manager_real_bill_info['grade'] == 3){
            $manager_real_bill_info['grade'] = '市级';
        }
        if($manager_real_bill_info['grade'] == 4){
            $manager_real_bill_info['grade'] = '区、县级';
        }
        if (!$manager_real_bill_info){
            showMessage('参数错误','','html','error');
        }

        Tpl::output('bill_info',$manager_real_bill_info);
        Tpl::showpage('manager_vrbill_state.show');
    }
    /**
     * 管理人提现确认
     */
    public function bill_state_verifyOp() {
        $model_manager = Model('manager_vr_bill');
//        $manager_state = $model_manager->getOne(array('manager_id'=>$_POST['manager_id']));
        $member_id = $_POST['member_id'];
        $verify_type = $_POST['verify_type'];
        $param = array();
        if($verify_type == 4){
            $param['state'] = 4;
            $param['pay_date'] = strtotime($_POST['pay_date']);
            $param['pay_comment'] = $_POST['pay_comment'];
            $this->log("管理人ID为 :".$member_id." 的地区管理人虚拟结算已提现");
        }
        if($verify_type == 3){
            $param['state'] = 3;
//            $this->log("管理人ID为 :".$manager_id." 的地区管理人提现取消");
        }

        $result = $model_manager->editManagerInfo(array('uid' => $member_id), $param);
        if ($result) {
            showMessage('提交成功', 'index.php?con=manager_vrbill_check&fun=get_money_state');
        } else {
            showMessage('提交失败');
        }

    }


    /**
     * 导出提现审核页面
     */
    public function export_step1Op()
    {
        $model = Model('manager_vr_bill');

        if ($_REQUEST['ids']) {
            $condition = array();
            $condition['mb_id'] = array('in', $_REQUEST['ids']);
            $sort = null;
        } else {
            list($condition, $sort) = $this->getConditionAndSort();

        }

        if (!is_numeric($_GET['curpage'])) {
            $count = $model->getManagerBillCount($condition);
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
                Tpl::output('murl', 'index.php?con=manager_vrbill_check&fun=index');
                Tpl::showpage('export.excel');
                return;
            }

            //如果数量小，直接下载
            $condition['state'] = array('not in',array(1,3,4));
            $data = $model->getManagerBillList($condition, self::EXPORT_SIZE, null, $sort);
            $this->createExcel($data);
            return;
        }

        //下载
        $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
        $limit2 = self::EXPORT_SIZE;

        $data = $model->getManagerBillList($condition, 20, "{$limit1},{$limit2}", $sort);


        $this->createExcel($data);
    }
    /**
     * 导出提现状态页面
     */
    public function export_step2Op()
    {
        $model = Model('manager_vr_bill');

        if ($_REQUEST['ids']) {
            $condition = array();
            $condition['mb_id'] = array('in', $_REQUEST['ids']);
            $sort = null;
        } else {
            list($condition, $sort) = $this->getConditionAndSort();

        }

        if (!is_numeric($_GET['curpage'])) {
            $count = $model->getManagerBillCount($condition);
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
                Tpl::output('murl', 'index.php?con=manager_vrbill_check&fun=index');
                Tpl::showpage('export.excel');
                return;
            }

            //如果数量小，直接下载
            $condition['state'] = array('not in',array(1,2,5));
            $data = $model->getManagerBillList($condition, self::EXPORT_SIZE, null, $sort);
            $this->createExcel2($data);
            return;
        }

        //下载
        $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
        $limit2 = self::EXPORT_SIZE;

        $data = $model->getManagerBillList($condition, 20, "{$limit1},{$limit2}", $sort);


        $this->createExcel($data);
    }
    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        $model = Model('manager_vr_bill');
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'管理人ID');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'管理人名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'管理人等级');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'管理区域');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'本期应金额');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'申请时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'提现状态');

        //data
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>"\t".$v['uid']);
            $tmp[] = array('data'=>"\t".$v['manager_name']);
            //管理人等级
            if($v['grade']==1){
                $tmp[] = array('data'=>"\t".'大区级');
            }
            if($v['grade']==2){
                $tmp[] = array('data'=>"\t".'省级');
            }
            if($v['grade']==3){
                $tmp[] = array('data'=>"\t".'市级');
            }
            if($v['grade']==4){
                $tmp[] = array('data'=>"\t".'区、县级');
            }
            //管理区域
            $area = '';
            if(!empty($v['area_region'])){
                $area = $v['area_region'];
            }
            if(!empty($v['province_id'])){
                $val = $model->getAreaInfo($v['province_id']);

                $area.=$val['area_name'];
            }
            if(!empty($v['city_id'])){
                $val = $model->getAreaInfo($v['city_id']);
                $area.=$val['area_name'];
            }
            if(!empty($v['district_id'])){
                $val = $model->getAreaInfo($v['district_id']);
                $area.=$val['area_name'];
            }
            $tmp[] =  array('data'=>"\t".$area);

            $tmp[] = array('data'=>"\t".$v['total']);
            $tmp[] = array('data'=>"\t".date('Y-m-d H:i:s', $v['apply_date']));
            if($v['state'] == 2){
                $tmp[] = array('data'=>"\t".'申请提现');
            }
            if($v['state'] == 5){
                $tmp[] = array('data'=>"\t".'审核未通过');
            }




            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('管理人提现审核列表',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('管理人提现审核列表',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }
    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel2($data = array()){
        $model = Model('manager_vr_bill');
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'管理人ID');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'管理人名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'管理人等级');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'管理区域');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'本期应金额');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'申请时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'付款时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'审核状态');

        //data
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>"\t".$v['uid']);
            $tmp[] = array('data'=>"\t".$v['manager_name']);
            //管理人等级
            if($v['grade']==1){
                $tmp[] = array('data'=>"\t".'大区级');
            }
            if($v['grade']==2){
                $tmp[] = array('data'=>"\t".'省级');
            }
            if($v['grade']==3){
                $tmp[] = array('data'=>"\t".'市级');
            }
            if($v['grade']==4){
                $tmp[] = array('data'=>"\t".'区、县级');
            }
            //管理区域
            $area = '';
            if(!empty($v['area_region'])){
                $area = $v['area_region'];
            }
            if(!empty($v['province_id'])){
                $val = $model->getAreaInfo($v['province_id']);

                $area.=$val['area_name'];
            }
            if(!empty($v['city_id'])){
                $val = $model->getAreaInfo($v['city_id']);
                $area.=$val['area_name'];
            }
            if(!empty($v['district_id'])){
                $val = $model->getAreaInfo($v['district_id']);
                $area.=$val['area_name'];
            }
            $tmp[] =  array('data'=>"\t".$area);

            $tmp[] = array('data'=>"\t".$v['total']);
            $tmp[] = array('data'=>"\t".date('Y-m-d H:i:s', $v['apply_date']));
            if(!empty($v['pay_date'])){
                $tmp[] = array('data'=>"\t".date('Y-m-d H:i:s', $v['pay_date']));
            }else{
                $tmp[] = array('data'=>"\t".'未付款');
            }

            if($v['state'] == 3){
                $tmp[] = array('data'=>"\t".'审核完成');
            }
            if($v['state'] == 4){
                $tmp[] = array('data'=>"\t".'已付款');
            }




            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('管理人提现状态列表',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('管理人提现状态列表',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }
    /**
     * 删除
     */
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
        Model('manager_vr_bill')->delManagerById($id);

        $this->log("删除{$count}位管理员（#ID: {$_GET['id']}）");

        $this->jsonOutput();
    }


}