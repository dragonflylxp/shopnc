<?php

/**

 * 用户登陆

 */







defined('Inshopec') or exit('Access Invalid!');



class messageControl extends mobileMemberControl {



    public function __construct() {

        parent::__construct();

      

    }

    /*

    *用户中心首页

    */

    public function indexOp() {

         Tpl::output('web_seo',C('site_name').' - '.'系统消息');

         Tpl::showpage('member_message');

    }



    /*

    *消息类型列表

    */

    public function typelistOp() {

         Tpl::output('web_seo',C('site_name').' - '.'系统消息');

         Tpl::showpage('message_typelist');

    }



    /*

    *获取用户中心信息

    */

    public function ajax_messageOp(){

        // output_data(array('member_info' => $member_info));

        $model_message    = Model('message');

        $wheres['from_member_id'] = '0';

        $wheres['message_type'] = '1';

        $wheres['to_member_id'] = $_SESSION['member_id'];

        $no_del_member_id = $_SESSION['member_id'];

        $wheres['del_member_id'] = array(array('notlike','$no_del_member_id%'));

        $article_total = Model()->table('message')->where($wheres)->count();

        $page = intval($_GET['p'])-1;

        $pageSize = 10; //每页显示数 

        $totalPage = ceil($article_total/$pageSize); //总页数 

        $startPage = $page*$pageSize;

        $where['limit'] ="{$startPage},{$pageSize}";

        $where['from_member_id'] = '0';

        $where['message_type'] = '1';

        $where['to_member_id'] = $_SESSION['member_id'];

        $where['no_del_member_id'] = $_SESSION['member_id'];

        $message_array  = $model_message->listMessage($where);

        $arrs = array();

       

        if (!empty($message_array) && is_array($message_array)){

            foreach ($message_array as $k=>$v){

                $v['message_open'] = '0';



                //去掉换行、制表等特殊字符，可以echo一下看看效果  

                $html = preg_replace("/[\t\n\r]+/","",$v['message_body']);  

                  

                //匹配表达式，注意两点，一是包含在'/ /'里面，再就是/要做转义处理成\/  

                $partern='/([^<>]+)<a href="([^<>]+)" target="_blank">([^<>]+)<\/a>/';  

                  

                //匹配结果  

                preg_match_all($partern,$html,$result);

                $v['message_time'] = date('Y-m-d H:i:s',$v['message_time']);

                $v['message_body'] = $result[1][0];

                $v['message_url'] = $result[2][0];

                if(strstr($v['message_url'],'member_order&fun=show_order')){

                    $info = explode('=', $v['message_url']);

                    $v['message_url'] = urlMobile('member_order','order_detail',array('order_id'=>$info[count($info)-1]));    

                }elseif(strstr($v['message_url'],'predeposit&fun=pd_log_list')){

                    $v['message_url'] = urlMobile('member_fund','predepositlog_list');

                }elseif(strstr($v['message_url'],'member_refund&fun=view')){

                    $info = explode('=', $v['message_url']);

                    $v['message_url'] = urlMobile('member_refund','member_refund_info',array('refund_id'=>$info[count($info)-1]));

                }elseif(strstr($v['message_url'],'member_order&fun=order_detail')){

                    $v['message_url'] = urlMobile('member_order','member_refund_info');

                }elseif(strstr($v['message_url'],'member_voucher&fun=index')){

                    $v['message_url'] = urlMobile('member_voucher');

                }elseif(strstr($v['message_url'],'predeposit&fun=rcb_log_list')){

                    $v['message_url'] = urlMobile('member_fund');

                }elseif(strstr($v['message_url'],'member_return&fun=view')){

                    $info = explode('=', $v['message_url']);

                    $v['message_url'] = urlMobile('member_return','member_return_info',array('return_id'=>$info[count($info)-1]));

                }else{

                     $v['message_url'] = 'javascript:void(0);';

                }



                $v['message_url_title']  = $result[3][0];

                

                if (!empty($v['read_member_id'])){

                    $tmp_readid_arr = explode(',',$v['read_member_id']);

                    if (in_array($_SESSION['member_id'],$tmp_readid_arr)){

                        $v['message_open'] = '1';

                    }

                }

               

                $message_array[$k]  = $v;

                 

         }

            $arrs['status'] = 1;

            $arrs['pages'] = $totalPage;

            $arrs['datas']['nlists'] = $message_array;

            echo json_encode($arrs);

        }else{

            $arrs['status'] = 0;

            $arrs['datas']['nlists'] = array();

            $arrs['info'] = '没有了,别点了...';

            echo json_encode($arrs);

        }

        

    

    }



  



   

}

