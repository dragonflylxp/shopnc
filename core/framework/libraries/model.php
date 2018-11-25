<?php
use shopec\db\db;
/**
 * 核心文件
 *
 * 模型类
 *
 * @package    core
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @author     shopec Team
 * @since      File available since Release v1.1
 */
class Model{

    protected $name = '';
    protected $table_prefix = '';
    protected $init_table = null;
    protected $table_name = '';
    protected $options = array();
    protected $db = null;
    protected $unoptions = true;    //是否清空参数项，默认清除

    public function __construct($table = null){
        if (!is_null($table)){
            $this->table_name = $table;
        }
        $this->table_prefix = DBPRE;
        if (!is_object($this->db)){
            $this->db = Db::getInstance();
        }
    }

    public function __call($method,$args) {
        if(in_array(strtolower($method),array('table','order','where','on','limit','having','lock','group','master','distinct','pk','key'),true)) {
            $this->options[strtolower($method)] =   $args[0];
            if (strtolower($method) == 'table'){
                if (strpos($args[0],',') !== false){
                    $args[0] = explode(',',$args[0]);
                    $this->table_name = '';
                }else{
                    $this->table_name = $args[0];
                }
            }
            return $this;
        }elseif(in_array(strtolower($method),array('page'),true)){
            if ($args[0] == null){
                return $this;
            }elseif(!is_numeric($args[0]) || $args[0] <= 0){
                $args[0] = 10;
            }

            if (is_numeric($args[1]) && $args[1] > 0){
                //page(2,30)形式，传入了每页显示数据和总记录数
                if ($args[0] > 0){
                    $this->options[strtolower($method)] =   $args[0];
                    pagecmd('setEachNum',   $args[0]);
                    $this->unoptions = false;
                    pagecmd('setTotalNum',  $args[1]);
                    return $this;
                }else{
                    $args[0] = 10;
                }
            }
            $this->options[strtolower($method)] =   $args[0];
            pagecmd('setEachNum',   $args[0]);
            $this->unoptions = false;
            pagecmd('setTotalNum',  $this->get_field('COUNT(*) AS nc_count'));
            return $this;
        }elseif(in_array(strtolower($method),array('min','max','count','sum','avg'),true)){
            $field =  isset($args[0])?$args[0]:'*';
            return $this->get_field(strtoupper($method).'('.$field.') AS nc_'.$method);
        }elseif(strtolower($method)=='count1'){
            $field =  isset($args[0])?$args[0]:'*';
            $options['field'] = ('count('.$field.') AS nc_count');
            $options =  $this->parse_options($options);
            $options['limit'] = 1;
            $result = $this->db->select($options);
            if(!empty($result)) {
                return reset($result[0]);
            }
        }elseif(strtolower(substr($method,0,6))=='getby_') {
            $field   =   substr($method,6);
            $where[$field] =  $args[0];
            return $this->where($where)->find();
        }elseif(strtolower(substr($method,0,7))=='getfby_') {
            $name   =   substr($method,7);
            $where[$name] =$args[0];
            //getfby_方法只返回第一个字段值
            if (strpos($args[1],',') !== false){
                $args[1] = substr($args[1],0,strpos($args[1],','));
            }
            return $this->where($where)->get_field($args[1]);
        }elseif (in_array($method,array('beginTransaction','commit','rollback','checkActive'))) {
            $this->db->$method();
        }elseif (in_array($method,array('gettotalnum','gettotalpage'))) {
           return pagecmd($method);
        }else{
            $error = 'Model Error:  Function '.$method.' is not exists!';
            throw_exception($error);
            return;
        }
    }

    function prefix($prefix){
        $this->table_prefix = $prefix;
        return $this;
    }

    /**
     * 查询
     *
     * @return array
     */
    public function select() {
        $options =  $this->parse_options();
        if ($options['limit'] !== false) {
            if (empty($options['where']) && empty($options['limit'])){
                //如果无条件，默认检索30条数据
                $options['limit'] = 30;
            }elseif ($options['where'] !== true && empty($options['limit'])){
                //如果带WHERE，但无LIMIT，最多只检索1000条记录
                $options['limit'] = 1000;
            }
        }
        $resultSet = $this->db->select($options);

        if(empty($resultSet)) {
            return array();
        }
        if ($options['key'] != '' && is_array($resultSet)){
            $tmp = array();
            foreach ($resultSet as $value) {
                $tmp[$value[$options['key']]] = $value;
            }
            $resultSet = $tmp;
        }
        return $resultSet;
    }

    /**
     * 取得第N列内容
     *
     * @param array/int $options
     * @return null/array
     */
    public function getfield($col = 1) {
        if (intval($col)<=1) $col = 1;
        $options =  $this->parse_options();
        if (empty($options['where']) && empty($options['limit'])){
            //如果无条件，默认检索30条数据
            $options['limit'] = 30;
        }elseif ($options['where'] !== true && empty($options['limit'])){
            //如果带WHERE，但无LIMIT，最多只检索1000条记录
            $options['limit'] = 1000;
        }

        $resultSet = $this->db->select($options);
        if(false === $resultSet) {
            return false;
        }
        if(empty($resultSet)) {
            return null;
        }
        $return = array();
        $cols = array_keys($resultSet[0]);
        foreach ((array)$resultSet as $k => $v) {
            $return[$k] = $v[$cols[$col-1]];
        }
        return $return;
    }

    protected function parse_options($options = array()) {
        if(is_array($options) && !empty($options)) {
            $options =  array_merge($this->options,$options);
        } else {
            $options = $this->options;
        }
        if(!isset($options['table'])){
            $options['table'] =$this->getTableName();
        }elseif(false !== strpos(trim($options['table'],', '),',')){
            foreach(explode(',', trim($options['table'],', ')) as $val){
                $tmp[] = $this->getTableName($val).' AS `'.$val.'`';
            }
            $options['table'] = implode(',',$tmp);
        }else{
            $options['table'] =$this->getTableName($options['table']);
        }
        if ($this->unoptions === true){
            $this->options  =   array();
        }else{
            $this->unoptions = true;
        }
        return $options;
    }

    public function get_field($field,$sepa=null) {
        $options['field']    =  $field;
        $options =  $this->parse_options($options);
        if(strpos($field,',')) { // 多字段
            $resultSet = $this->db->select($options);
            if(!empty($resultSet)) {
                $_field = explode(',', $field);
                $field  = array_keys($resultSet[0]);
                $move   =  $_field[0]==$_field[1]?false:true;
                $key =  array_shift($field);
                $key2 = array_shift($field);
                $cols   =   array();
                $count  =   count($_field);
                foreach ($resultSet as $result){
                    $name   =  $result[$key];
                    if($move) { // 删除键值记录
                        unset($result[$key]);
                    }
                    if(2==$count) {
                        $cols[$name]   =  $result[$key2];
                    }else{
                        $cols[$name]   =  is_null($sepa)?$result:implode($sepa,$result);
                    }
                }
                return $cols;
            }
        }else{
            $options['limit'] = 1;
            $result = $this->db->select($options);
            if(!empty($result)) {
                return reset($result[0]);
            }
        }
        return null;
    }

    /**
     * 返回一条记录
     *
     * @param string/int $options
     * @return null/array
     */
    public function find() {
        $options = array('limit'=> 1,'_limit'=>1);
        $options =  $this->parse_options($options);
        $result = $this->db->select($options);
        if(empty($result)) {
            return array();
        }
        return $result[0];
    }

    /**
     * 删除
     *
     * @param array $options
     * @return bool/int
     */
    public function delete() {
        $options =  $this->parse_options($options);
        $result =   $this->db->delete($options);
        return false !== $result ? true : false;
    }

    /**
     * 更新
     *
     * @param array $data
     * @return boolean
     */
    public function update($data = array()) {
        if(empty($data)) return false;
        $options =  $this->parse_options();
        $result = $this->db->update($data,$options);
        return false !== $result ? true : false;
    }

    /**
     * 插入
     *
     * @param array $data
     * @param bool $replace
     * @return mixed int/false
     */
    public function insert($data = '', $replace = false) {
        if(empty($data)) return false;
        $options =  $this->parse_options();
        return $this->db->insert($data,$options,$replace);
//         if(false !== $result ) {
//             $insertId   =   $this->getLastId();
//             if($insertId) {
//                 return $insertId;
//             }
//         }
//         return $result;
//         if(false !== $result ) {
//             $flag = $this->db->getAll("SELECT sequence_name FROM user_sequences WHERE sequence_name='SEQU_".strtoupper($this->table_name)."'");
//             $insertId = $flag ? $this->db->getLastId('master',$this->table_name) : 0;
//             if($insertId) {
//                 return $insertId;
//             }
//         }
//         return $result;
    }

    /**
     * 批量插入
     *
     * @param array $dataList
     * @param bool $replace
     * @return boolean
     */
    public function insertAll($dataList, $replace = false){
        if(empty($dataList)) return false;
        // 分析表达式
        $options =  $this->parse_options();
        // 写入数据到数据库
        $result = $this->db->insertAll($dataList,$options,$replace);
        if(false !== $result ) return true;
        return $result;
    }

    /**
     * 取得表名
     *
     * @param string $table
     * @return string
     */
    protected function getTableName($table = null){
        if (is_null($table)){
            $return = '`'.$this->table_prefix.$this->table_name.'`';
        }else{
            $return = '`'.$this->table_prefix.$table.'`';
        }
        return $return;
    }

    /**
     * 指定查询字段
     *
     * @param mixed $field
     * @return Model
     */
    public function field($field){
        if(true === $field) {
            $field   =  '*';
        }
        $this->options['field']   =   $field;
        return $this;
    }

    /**
     * 组装join
     *
     * @param string $join
     * @return Model
     */
    public function join($join) {
        if (false !== strpos($join,',')){
            foreach (explode(',',$join) as $key=>$val) {
                if (in_array(strtolower($val),array('left','inner','right'))){
                    $this->options['join'][] = strtoupper($val).' JOIN';
                }else{
                    $this->options['join'][] = 'LEFT JOIN';
                }
            }
        }elseif (in_array(strtolower($join),array('left','inner','right'))){
            $this->options['join'][] = strtoupper($join).' JOIN';
        }
        return $this;
    }

    public function setInc($field, $step = 1) {
        return $this->set_field($field,array('exp',$field.'+'.$step));
    }

    public function setDec($field, $step = 1) {
        return $this->set_field($field,array('exp',$field.'-'.$step));
    }

    public function set_field($field, $value = '') {
        if(is_array($field)) {
            $data = $field;
        }else{
            $data[$field]   =  $value;
        }
        return $this->update($data);
    }

    /**
     * 显示分页链接
     *
     * @param int $style 分页风格
     * @return string
     */
    public function showpage($style = null){
        return pagecmd('show',$style);
    }

    /**
     * 显示页码
     *
     * @return string
     */
    public function shownowpage(){
        return pagecmd('getnowpage');
    }

    /**
     * 显示信息总数
     *
     * @return string
     */
    public function gettotalnum(){
        return pagecmd('gettotalnum');
    }

    /**
     * 清空MODEL中的options、table_name属性
     *
     */
    public function cls(){
        $this->options = array();
        $this->table_name = '';
        return $this;
    }

    public function query($sql, $host = 'master'){
        return $this->db->query($sql,$host);
    }
    /**
     * 直接SQL查询,返回查询结果
     *by:511613932
     * @param string $sql
     * @return array
     */
    public function getAll1($sql)
    {
        return $this->db->getAll1($sql);
    }
    /**
     * 直接SQL查询,返回查询结果
     *by:511613932
     * @param string $sql
     * @return array
     */    
	public function getCol($sql, $host = 'master')
	{
		$res = $this->db->query($sql, $host);

		if ($res !== false) {
			$arr = array();

			while ($row = mysqli_fetch_row($res)) {
				$arr[] = $row[0];
			}

			return $arr;
		}
		else {
			return false;
		}
	}    
     /**
     * 
     *by:511613932
     * @param string $sql
     * @return array
     */
    public function getOne($sql)
    {
        return $this->db->getOne($sql);
    }
    
       /**
     * 
     *by:511613932
     * @param string $sql
     * @return array
     */
    public function getRow($sql)
    {
        return $this->db->getRow2($sql);
    }
      
    
    public function select1($param, $obj_page='', $host = 'slave'){
        return $this->db->select1($param,$obj_page,$host);
    }

    public function insert1($table_name, $insert_array=array()){
        return $this->db->insert1($table_name,$insert_array);
    }

    public function update1($table_name, $update_array = array(), $where = '', $host = 'master'){
        if (is_string($where)) {
            if (substr(strtolower(ltrim($where)),0,3) == 'and') {
                $where = substr(strtolower(ltrim($where)),4);
            }
        }
        return $this->db->update1($table_name,$update_array,$where,$host);
    }

    public function delete1($table_name, $where = '', $host = 'master'){
        return $this->db->delete1($table_name,$where,$host);
    }

    public function getRow1($param, $fields = '*', $host = 'slave'){
        return $this->db->getRow1($param,$fields,$host);
    }

    public function getCount1($table, $condition = null, $host = 'slave'){
        return $this->db->getCount1($table,$condition,$host);
    }
}
