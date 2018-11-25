<?php
/**
 * 默认展示页面
 *
 *
 *
 */


defined('Inshopec') or exit('Access Invalid!');
class indexControl extends mobileHomeControl{
    public function indexOp(){
       //  p($_SESSION);
       // p($_COOKIE);
        $web_seo = C('site_name')."-触屏版";
        Tpl::output('web_seo',$web_seo); 
        //获取8条公告
        $notice = Model()->table('article')->where("ac_id = 1")->limit(8)->order("article_id desc")->field('article_title,article_id')->select();
        Tpl::output('notice',$notice);
        // 获取头条新闻
        $topnews = Model()->table('article')->limit(8)->order("article_id desc")->field('article_title,article_id')->select();
        Tpl::output('topnews',$topnews);
        //猜你喜欢
        $tuijian = Model('goods')->where(array("goods_state"=>1,"goods_verify"=>1))->Order(" rand()")->limit(8)->select();
        Tpl::output('tuijian',$tuijian);
        $code_list = Model('web_config')->getCodeList(array('web_id'=>101,'code_id'=>620));
        if(!empty($code_list) && is_array($code_list)) {
                 
            foreach ($code_list as &$vals) {
                $vals['code_info'] = Model('web_config')->get_array($vals['code_info'],'array');
            }
        }
        Tpl::output('zom_list',$code_list);
        Tpl::showpage('index');
    }

    /*
    *获取首页
    */
    public function ajax_index_dataOp(){
        $model_mb_special = Model('mb_special');
        $data = $model_mb_special->getMbSpecialIndex();
        
        $this->_output_special($data, $_GET['type']);
    }
    public function specialOp(){
        $web_seo = C('site_name')."-专题页";
        Tpl::output('web_seo',$web_seo); 
        Tpl::showpage('special');
    }

     public function get_specialOp(){
         $model_mb_special = Model('mb_special'); 
        $data = $model_mb_special->getMbSpecialItemUsableListByID($_GET['special_id']);
        $this->_output_special($data, $_GET['type'], $_GET['special_id']);
    }
     /**
     * 输出专题
     */

    private function _output_special($data, $type = 'json', $special_id = 0, $datas=array()) {
        $model_special = Model('mb_special');
        
        if($_GET['type'] == 'html') {
            $html_path = $model_special->getMbSpecialHtmlPath($special_id);
            if(!is_file($html_path)) {
                ob_start();
                Tpl::output('list', $data);
                Tpl::showpage('mb_special');
                file_put_contents($html_path, ob_get_clean());
            }
            header('Location: ' . $model_special->getMbSpecialHtmlUrl($special_id));
            die;
        } else {
            $datas['list'] = $data;
            $datas['special_id'] = $special_id;
            $datas = $datas;
            output_data($datas);
        }
    }
    /**
     * 默认搜索词列表
     */
    public function search_key_listOp() {
        //热门搜索
        $list = @explode(',',C('hot_search'));
        if (!$list || !is_array($list)) { 
            $list = array();
        }
                
        //历史搜索
        if (cookie('his_sh') != '') {
            $his_search_list = explode('~', cookie('his_sh'));
        }

        $data['list'] = $list;
        $data['his_list'] = is_array($his_search_list) ? $his_search_list : array();
        output_data($data);
    }
    
    /**
     * 热门搜索列表
     */
    public function search_hot_infoOp() {
                //热门搜索
        if (C('rec_search') != '') {
            $rec_search_list = @unserialize(C('rec_search'));
            $rec_value = array();
            foreach($rec_search_list as $v){
                $rec_value[] = $v['value'];
            }
            
        }
        output_data(array('hot_info'=>$result ? $rec_value : array()));
    }

    /**
     * 高级搜索
     */
    public function search_advOp() {
        $area_list = Model('area')->getAreaList(array('area_deep'=>1),'area_id,area_name');
        if (C('contract_allow') == 1) {
            $contract_list = Model('contract')->getContractItemByCache();
            $_tmp = array();$i = 0;
            foreach ($contract_list as $k => $v) {
                $_tmp[$i]['id'] = $v['cti_id'];
                $_tmp[$i]['name'] = $v['cti_name'];
                $i++;
            }
        }
        $tagarr = array (    
                 0 => 
                array (
                  'name' => 'tag_none',
                  'zhname' => '无',
                  'val' => '1',
                ),
                1 => 
                array (
                  'name' => 'tag_new',
                  'zhname' => '新品上市',
                  'val' => '2',
                ),
                2 => 
                array (
                  'name' => 'tag_tuijian',
                  'zhname' => '热门推荐',
                  'val' => '3',
                ),
                3 => 
                array (
                  'name' => 'tag_temai',
                  'zhname' => '特卖专场',
                  'val' => '4',
                ),
                4 => 
                array (
                  'name' => 'tag_remai',
                  'zhname' => '超级热卖',
                  'val' => '5',
                ),
 /*                3 => 
                array (
                  'name' => 'tag_sale',
                  'zhname' => '特价',
                  'val' => '4',
                ),
               4 => 
                array (
                  'name' => 'tag_berserk',
                  'zhname' => '疯抢',
                  'val' => '5',
                ),
                5 => 
                array (
                  'name' => 'tag_real',
                  'zhname' => '推荐',
                  'val' => '6',
                ),
                6 => 
                array (
                  'name' => 'tag_zk',
                  'zhname' => '折扣',
                  'val' => '7',
                ),
                7 => 
                array (
                  'name' => 'tag_kb',
                  'zhname' => '口碑',
                  'val' => '8',
                ),   */
              
              );
        output_data(array('area_list'=>$area_list ? $area_list : array(),'contract_list'=>$_tmp,'tag_list'=>$tagarr));
    }
    
    /**
     * json输出地址数组 原data/resource/js/area_array.js
     */
    public function json_areaOp()
    {
        $_GET['src'] = $_GET['src'] != 'db' ? 'cache' : 'db';
        echo $_GET['callback'].'('.json_encode(Model('area')->getAreaArrayForJson($_GET['src'])).')';
    }

    /**
     * 根据ID返回所有父级地区名称
     */
    public function json_area_showOp()
    {
        $area_info['text'] = Model('area')->getTopAreaName(intval($_GET['area_id']));
        echo $_GET['callback'].'('.json_encode($area_info).')';
    }
}
