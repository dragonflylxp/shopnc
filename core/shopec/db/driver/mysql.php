<?php
namespace shopec\db\driver;
/**
 * mysqli驱动
 *
 *
 * @package    db
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @author     shopec Team
 * @since      File available since Release v1.1
 */
use shopec\Log,shopec\db\db;

defined('Inshopec') or exit('Access Invalid!');
class Mysql extends Db{

    private static $link = array();

    private static $iftransacte = true;

    public function __construct(){
        if (!extension_loaded('mysqli')){
            throw_exception("Db Error: mysqli is not install");
        }
    }

    private function connect($host = 'slave'){
        if (C('db.master') == C('db.slave')){
            if (is_object(self::$link['slave'])){
                self::$link['master'] = & self::$link['slave'];return ;
            }elseif (is_object(self::$link['master'])){
                self::$link['slave'] = & self::$link['master'];return ;
            }
        }
        if (!in_array($host,array('master','slave'))) $host = 'slave';
        $conf = C('db.'.$host);
        if (is_object(self::$link[$host])) return;
        self::$link[$host] = new \mysqli($conf['dbhost'], $conf['dbuser'], $conf['dbpwd'], $conf['dbname'], $conf['dbport']);
        if (mysqli_connect_errno()) throw_exception("Db Error: database connect failed");
        switch (strtoupper($conf['dbcharset'])){
            case 'UTF-8':
                $query_string = "
                         SET CHARACTER_SET_CLIENT = utf8,
                         CHARACTER_SET_CONNECTION = utf8,
                         CHARACTER_SET_DATABASE = utf8,
                         CHARACTER_SET_RESULTS = utf8,
                         CHARACTER_SET_SERVER = utf8,
                         COLLATION_CONNECTION = utf8_general_ci,
                         COLLATION_DATABASE = utf8_general_ci,
                         COLLATION_SERVER = utf8_general_ci,
                         sql_mode=''";
                break;
            case 'GBK':
                $query_string = "
                        SET CHARACTER_SET_CLIENT = gbk,
                         CHARACTER_SET_CONNECTION = gbk,
                         CHARACTER_SET_DATABASE = gbk,
                         CHARACTER_SET_RESULTS = gbk,
                         CHARACTER_SET_SERVER = gbk,
                         COLLATION_CONNECTION = gbk_chinese_ci,
                         COLLATION_DATABASE = gbk_chinese_ci,
                         COLLATION_SERVER = gbk_chinese_ci,
                         sql_mode=''";
                break;
            default:
                $error = "Db Error: charset is Invalid";
                throw_exception($error);
        }
        //进行编码声明
        if (!self::$link[$host]->query($query_string)){
            throw_exception("Db Error: ".mysqli_error(self::$link[$host]));
        }
    }

    /**
     * 取得数组
     *
     * @param string $sql
     * @return bool/null/array
     */
    public function getAll($sql, $host = 'slave'){
    	
        $this->connect($host);
        $result = self::query($sql, $host);
        if ($result === false) return array();
        $array = array();
        while ($tmp=mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $array[] = $tmp;
        }
        
        return !empty($array) ? $array : null;
    }
/**
 * by:511613932
 */        
	public function getRow2($sql, $limited = false, $host = 'slave')
	{
		if ($limited == true) {
			$sql = trim($sql . ' LIMIT 1');
		}
        $this->connect($host);
		$res = self::query($sql, $host);

		if ($res !== false) {
			return mysqli_fetch_assoc($res);
		}
		else {
			return false;
		}
	}    
	
	
/**
 * by:511613932
 */    
	public function getAll1($sql, $host = 'slave')
	{
		$this->connect($host);
		$res = self::query($sql, $host);

		if ($res !== false) {
			$arr = array();

			while ($row = mysqli_fetch_assoc($res)) {
				$arr[] = $row;
			}

			return $arr;
		}
		else {
			return false;
		}
	}    
/**
 * by:511613932
 */
	public function getCol($sql, $host = 'slave')
	{
		$this->connect($host);
		$res = self::query($sql);

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
	public function getOne($sql, $limited = false, $host = 'slave')
	{
		if ($limited == true) {
			$sql = trim($sql . ' LIMIT 1');
		}
        $this->connect($host);
        
		$res = self::query($sql, $host);

		if ($res !== false) {
			$row = mysqli_fetch_row($res);

			if ($row !== false) {
				return $row[0];
			}
			else {
				return '';
			}
		}
		else {
			return false;
		}
	}   
    /**
     * 执行查询
     *
     * @param string $sql
     * @return mixed
     */
    public function query($sql, $host = 'master'){
        $this->connect($host);
        if (C('debug')) addUpTime('queryStartTime');
        $query = self::$link[$host]->query($sql);
        if (C('debug')) addUpTime('queryEndTime');
        if ($query === false){
            $error = 'Db Error: '.mysqli_error(self::$link[$host]);
            if (C('debug')) {
                throw_exception($error.'<br/>'.$sql);
            } else {
                Log::record($error."\r\n".$sql,Log::ERR);
                Log::record($sql,Log::SQL);
                return false;
            }
        }else {
            Log::record($sql." [ RunTime:".addUpTime('queryStartTime','queryEndTime',6)."s ]",Log::SQL);
            return $query;
        }
    }

    /**
     * 取得上一步插入产生的ID
     *
     * @return int
     */
    public function getLastId($host = 'master'){
        $this->connect($host);
        $id = mysqli_insert_id(self::$link[$host]);
        if (!$id){
            $result = self::query('SELECT last_insert_id() as id',$host);
            if ($result === false) return false;
            $id = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $id = $id['id'];
        }
        return $id;
    }

    public function checkActive($host = 'master') {
        if (is_object(self::$link[$host])) {
            self::$link[$host]->close();
            self::$link[$host] = null;
        }
    }

    /**
     * SELECT查询
     *
     * @param array $param 参数
     * @param object $obj_page 分类对象
     * @return array
     */
    public function select1($param, $obj_page='', $host = 'slave'){
        self::connect($host);
        static $_cache = array();

        if (empty($param)) throw_exception('Db Error: select param is empty!');

        if (empty($param['field'])){
            $param['field'] = '*';
        }
        if (empty($param['count'])){
            $param['count'] = 'count(*)';
        }

        if (isset($param['index'])){
            $param['index'] = 'USE INDEX ('.$param['index'].')';
        }

        if (trim($param['where']) != ''){
            if (strtoupper(substr(trim($param['where']),0,5)) != 'WHERE'){
                if (strtoupper(substr(trim($param['where']),0,3)) == 'AND'){
                    $param['where'] = substr(trim($param['where']),3);
                }
                $param['where'] = 'WHERE '.$param['where'];
            }
        }else {
            $param['where'] = '';
        }
        $param['where_group'] = '';
        if (!empty($param['group'])){
            $param['where_group'] .= ' group by '.$param['group'];
        }
        $param['where_order'] = '';
        if (!empty($param['order'])){
            $param['where_order'] .= ' order by '.$param['order'];
        }

        //判断是否是联表
        $tmp_table = explode(',',$param['table']);
        if (!empty($tmp_table) && count($tmp_table) > 1){
            //判断join表数量和join条件是否一致
            if ((count($tmp_table)-1) != count($param['join_on'])){
                throw_exception('Db Error: join number is wrong!');
            }

            //trim 掉空白字符
            foreach($tmp_table as $key=>$val){
                $tmp_table[$key] = trim($val) ;
            }

            //拼join on 语句
            for ($i=1;$i<count($tmp_table);$i++){
                $tmp_sql .= $param['join_type'].' `'.DBPRE.$tmp_table[$i].'` as `'.$tmp_table[$i].'` ON '.$param['join_on'][$i-1].' ';
            }
            $sql = 'SELECT '.$param['field'].' FROM `'.DBPRE.$tmp_table[0].'` as `'.$tmp_table[0].'` '.$tmp_sql.' '.$param['where'].$param['where_group'].$param['where_order'];

            //如果有分页，那么计算信息总数
            $count_sql = 'SELECT '.$param['count'].' as count FROM `'.DBPRE.$tmp_table[0].'` as `'.$tmp_table[0].'` '.$tmp_sql.' '.$param['where'].$param['where_group'];
        }else {
            $sql = 'SELECT '.$param['field'].' FROM `'.DBPRE.$param['table'].'` as `'.$param['table'].'` '.$param['index'].' '.$param['where'].$param['where_group'].$param['where_order'];
            $count_sql = 'SELECT '.$param['count'].' as count FROM `'.DBPRE.$param['table'].'` as `'.$param['table'].'` '.$param['index'].' '.$param['where'];
        }
        //limit ，如果有分页对象的话，那么优先分页对象
        if (is_object($obj_page)){
            $count_query = self::query($count_sql,$host);
            $count_fetch = mysqli_fetch_array($count_query,MYSQLI_ASSOC);
            $obj_page->setTotalNum($count_fetch['count']);
            $param['limit'] = $obj_page->getLimitStart().",".$obj_page->getEachNum();
        }
        if ($param['limit'] != ''){
            $sql .= ' limit '.$param['limit'];
        }
        if ($param['cache'] !== false){
            $key =  is_string($param['cache_key'])?$param['cache_key']:md5($sql);
            if (isset($_cache[$key])) return $_cache[$key];
        }
        $result = self::query($sql,$host);
        if ($result === false) $result = array();
        while ($tmp=mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $array[] = $tmp;
        }
        if ($param['cache'] !== false && !isset($_cache[$key])){
            $_cache[$key] = $array;
        }
        return $array;
    }

    /**
     * 插入操作
     *
     * @param string $table_name 表名
     * @param array $insert_array 待插入数据
     * @return mixed
     */
    public function insert1($table_name, $insert_array=array()){
        self::connect('master');
        return $this->insert($insert_array,array('table'=>'`'.DBPRE.$table_name.'`'));
    }

    /**
     * 更新操作
     *
     * @param string $table_name 表名
     * @param array $update_array 待更新数据
     * @param string $where 执行条件
     * @return bool
     */
    public function update1($table_name, $update_array = array(), $where = ''){
        self::connect('master');
        if (!is_array($update_array)) return false;
        return $this->update($update_array,array('table'=>'`'.DBPRE.$table_name.'`','where'=>$where));
    }

    /**
     * 删除操作
     *
     * @param string $table_name 表名
     * @param string $where 执行条件
     * @return bool
     */
    public function delete1($table_name, $where = '', $host = 'master'){
        self::connect($host);
        if (trim($where) != ''){
            if (strtoupper(substr(trim($where),0,5)) != 'WHERE'){
                if (strtoupper(substr(trim($where),0,3)) == 'AND'){
                    $where = substr(trim($where),3);
                }
                $where = ' WHERE '.$where;
            }
            $sql = 'DELETE FROM `'.DBPRE.$table_name.'` '.$where;
            return self::query($sql, $host);
        }else {
            throw_exception('Db Error: the condition of delete is empty!');
        }
    }

    /**
     * 取得一行信息
     *
     * @param array $param
     * @param string $fields
     * @return array
     */
    public function getRow1($param, $fields = '*', $host = 'slave'){
        self::connect($host);
        $table = $param['table'];
        $wfield = $param['field'];
        $value = $param['value'];

        if (is_array($wfield)){
            $where = array();
            foreach ($wfield as $k => $v){
                $where[] = $v."='".$value[$k]."'";
            }
            $where = implode(' and ',$where);
        }else {
            $where = $wfield."='".$value."'";
        }

        $sql = "SELECT ".$fields." FROM `".DBPRE.$table."` WHERE ".$where;
        $result = self::query($sql,$host);
        if ($result === false) return array();
        return mysqli_fetch_array($result,MYSQLI_ASSOC);
    }

    /**
     * 返回单表查询记录数量
     *
     * @param string $table 表名
     * @param $condition mixed 查询条件，可以为空，也可以为数组或字符串
     * @return int
     */
    public function getCount1($table, $condition = null, $host = 'slave'){
        self::connect($host);

        if (!empty($condition) && is_array($condition)){
            $where = '';
            foreach ($condition as $key=>$val) {
                $key = self::parseKey1($key);
                $val = self::parseValue1($val);
                $where .= ' AND '.$key.'='.$val;
            }
            $where = ' WHERE '.substr($where,4);
        }elseif(is_string($condition)){
            if (strtoupper(substr(trim($condition),0,3)) == 'AND'){
                $where = ' WHERE '.substr(trim($condition),4);
            }else{
                $where = ' WHERE '.$condition;
            }
        }
        $sql = 'SELECT COUNT(*) as `count` FROM `'.DBPRE.$table.'` as `'.$table.'` '.(isset($where) ? $where : '');
        $result = self::query($sql,$host);
        if ($result === false) return 0;
        $result = mysqli_fetch_array($result,MYSQLI_ASSOC);
        return $result['count'];
    }

    /**
     * 执行SQL语句
     *
     * @param string $sql 待执行的SQL
     * @return
     */
    public function execute($sql, $host = 'master'){
        $this->connect($host);
        $result = self::query($sql,$host);
        return $result;
    }

    /**
     * 格式化字段
     *
     * @param string $key 字段名
     * @return string
     */
    public function parseKey1($key){
        $key   =  trim($key);
        if(!preg_match('/[,\'\"\*\(\)`.\s]/',$key)) {
           $key = '`'.$key.'`';
        }
        return $key;
    }

    /**
     * 格式化值
     *
     * @param mixed $value
     * @return mixed
     */
    public function parseValue1($value){
        $value = addslashes(stripslashes($value));//重新加斜线，防止从数据库直接读取出错
        return "'".$value."'";
    }

    public function beginTransaction($host = 'master'){
        $this->connect($host);
        if (self::$iftransacte){
            self::$link[$host]->autocommit(false);//关闭自动提交
        }
        self::$iftransacte = false;
    }

    public function commit($host = 'master'){
        if (!self::$iftransacte){
            self::$link[$host]->commit();
            self::$link[$host]->autocommit(true);//开启自动提交
            self::$iftransacte = true;
        }
    }

    public function rollback($host = 'master'){
        if (!self::$iftransacte){
            self::$link[$host]->rollback();
            self::$link[$host]->autocommit(true);
            self::$iftransacte = true;
        }
    }

    protected function _parseTable($table, $type = '') {
        if (is_string($table)) {
            if (in_array($type,array('select','update'))) {
                $_table = trim(str_replace(DBPRE,'',$table),'`');
                $table = $table .' '.$_table;
            }
        }
        return $table;
    }
}
