 

<?php defined('Inshopec') or exit('Access Invalid!');



class red_packetControl extends mobileHomeControl {



    public function __construct() {

        parent::__construct();

    }



    public function indexOp() {
         Tpl::output('web_seo',C('site_name').' - '.'红包大派送');
         Tpl::showpage('red_packet');

    }

     public function getinfoOp() {
        $id = intval($_GET ['id']);

        // 红包详细信息
        $model = Model();
        $packet_detail = $model->table('red_packet')->where(array('id'=>$id))->find();
        if (empty($packet_detail)) {
            output_error('红包已被抢光或不存在');
        }
		
		//活动规则
		//$rule = explode("\n", $packet_detail['packet_descript']);

		//最近10名中奖者
		//$packet_rec = $model->table('red_packet_rec')->where(array('packet_id'=>$id))->order('id DESC')->limit(10)->select();

        output_data(array('packet_detail' => $packet_detail, 'rec'=>$packet_rec, 'rule'=>$rule));
    }









 }