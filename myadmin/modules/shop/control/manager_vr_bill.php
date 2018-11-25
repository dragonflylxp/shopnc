<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/4/004
 * Time: 12:07
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class manager_vr_billControl extends SystemControl{

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

        Tpl::showpage('manager_vr_bill.index');
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
            if ($sdate > 0 ) {
                $condition['start_time'] = array('time', array($sdate-1,''));

            }

            $edate = $_GET['edate2'] ? strtotime($_GET['edate2'] . ' 00:00:00') : 0;
            if ( $edate > 0) {
                $condition['end_time'] = array('time', array('',$edate));

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

    public function index_xmlOp()
    {
        list($condition, $sort) = $this->getConditionAndSort();
        //取得开始和结束日期
        $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));//当前时间
        $time = strtotime('-1 month',$current_time);//上个月
        $start_time = strtotime(date('Y-m-01 00:00:00', $time));//开始时间是上个月的1号
        $end_time = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");////该月最后一天最后一秒时unix时间戳
        if(empty($condition)){
            $condition = array('start_time'=>$start_time,'end_time'=>$end_time);
        }

        $model = Model('manager_vr_bill');
        $list = (array) $model->getManagerBillList($condition, $_REQUEST['rp'], null, $sort);
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($list as $val) {
            $i = array();


            $i['operation'] =  <<<EOB
<a class="btn green confirm-del-on-click" href="index.php?con=manager_vr_bill&fun=show_manager_vr_bill&member_id={$val['uid']}" ><i class='fa fa-pencil-square-o'></i>查看</a>
EOB;

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
                $area.="&nbsp".$v['area_name'];
            }
            if(!empty($val['city_id'])){
                $v = $model->getAreaInfo($val['city_id']);
                $area.="&nbsp".$v['area_name'];
            }
            if(!empty($val['district_id'])){
                $v = $model->getAreaInfo($val['district_id']);
                $area.="&nbsp".$v['area_name'];
            }
            $i['area'] = $area;
            $i['total'] = $val['total'];

            $i['start_time'] = date('Y-m-d', $val['start_time']);
            $i['end_time'] = date('Y-m-d', $val['end_time']);

            $data['list'][$val['mb_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 查看管理人详细信息显示页面
     *
     */
    public function index_show_xmlOp()
    {

        list($condition, $sort) = $this->getConditionAndSort();
        $uid = $_GET['uid'];
        $model = Model('manager_vr_bill');
        $list = (array) $model->getManagerShowList($uid,$condition, $_REQUEST['rp'], null, $sort);

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($list as $val) {
            $i = array();
            $i['operation'] =  <<<EOB
<a class="btn green confirm-del-on-click" href="index.php?con=manager_vr_bill&fun=show_manager_vr_bill&member_id={$val['uid']}" ><i class='fa fa-pencil-square-o'></i>查看</a>
EOB;

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

                $area.="&nbsp".$v['area_name'];
            }
            if(!empty($val['city_id'])){
                $v = $model->getAreaInfo($val['city_id']);
                $area.="&nbsp".$v['area_name'];
            }
            if(!empty($val['district_id'])){
                $v = $model->getAreaInfo($val['district_id']);
                $area.="&nbsp".$v['area_name'];
            }
            $i['area'] = $area;
            $i['total'] = $val['total'];
            $i['start_time'] = date('Y-m-d', $val['start_time']);
            $i['end_time'] = date('Y-m-d', $val['end_time']);


            $data['list'][$val['mb_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }
    /**
     * 地区管理人列表
     *
     */
    public function show_manager_vr_billOp(){

        $member_id = intval($_GET['member_id']);

        if ($member_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_manager_real_bill = Model('manager_vr_bill');
        $manager_real_bill_info = $model_manager_real_bill->getManagerVrBillInfo(array('member_id'=>$member_id));
        if (!$manager_real_bill_info){
            showMessage('参数错误','','html','error');
        }


        Tpl::output('bill_info',$manager_real_bill_info);
        Tpl::showpage('manager_vr_bill.show');
    }



    /**
     * 导出
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
//            取得开始和结束日期
            $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));//当前时间
            $time = strtotime('-1 month',$current_time);//上个月
            $start_time = strtotime(date('Y-m-01 00:00:00', $time));//开始时间是上个月的1号
            $end_time = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");////该月最后一天最后一秒时unix时间戳
            if(empty($condition)){
                $condition = array('start_time'=>$start_time,'end_time'=>$end_time);
            }

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
                Tpl::output('murl', 'index.php?con=manager_vr_bill&fun=index');
                Tpl::showpage('export.excel');
                return;
            }

            //如果数量小，直接下载
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
     * 导出展示页面
     */
    public function export_step2Op()
    {
        $model = Model('manager_vr_bill');
        $uid = $_GET['uid'];

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
                Tpl::output('murl', 'index.php?con=manager_vr_bill&fun=index');
                Tpl::showpage('export.excel');
                return;
            }

            //如果数量小，直接下载

            $data = $model->getManagerShowList($uid,$condition, self::EXPORT_SIZE, null, $sort);

            $this->createExcel($data);
            return;
        }

        //下载
        $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
        $limit2 = self::EXPORT_SIZE;

        $data = $model->getManagerShowList($uid,$condition, 20, "{$limit1},{$limit2}", $sort);

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
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'开始结算时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'结束结算时间');

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
            $tmp[] = array('data'=>"\t".date('Y-m-d H:i:s', $v['start_time']));
            if($v['end_time'] > 0){
                $tmp[] = array('data'=>"\t".date('Y-m-d H:i:s', $v['end_time']));
            }


            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('管理人列表',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('管理人列表',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
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