<?php

/*
 *    pdo
 * 
 */
class CMODEL_PDO{

    protected $con;
    protected $table;

    // constructer
    public function __construct($con){  $this->setConnection($con);  }

    // table name set
    protected function setTableName($table){  $this->table = $table;  }

    // database connecter set
    public function setConnection($con){  $this->con = $con;  }

    // prepared statement
    public function prepare($sql){  return $this->con->prepare($sql);  }

    // create prepared statement
    public function execute($sql, $params = array()){
        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // data extraction
    public function fetch($sql, $params = array()){  
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    // multiple data extraction
    public function fetchAll($sql, $params = array()){
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    // escape
    public function quote($values){  return $this->con->quote($values);  }

    // transaction start
    public function beginTransaction(){  $this->con->beginTransaction();  }

    // commit
    public function commit(){  $this->con->commit();  }

    // rollback
    public function rollBack(){  $this->con->rollBack();  }

    // get all count
    public function getCount(){
        $stmt = $this->execute("SELECT FOUND_ROWS()");
        return $stmt->fetchColumn();
    }

    // get last insert id
    public function getInsertId($bool = true){
        if($bool === true) {
            $sql = "SELECT LAST_INSERT_ID() as last_insert_id";
            $ret = $this->fetch($sql);
                return $ret['last_insert_id'];
        } else {
            return $this->con->lastInsertId();
        }
    }

    // data insert
    public function insert($name, $value = "", $table = ""){
        if(empty($table)){  $table = $this->table;  }

        $params = array();
        if(!is_array($name)){
            $params[] = array($name => $value);
        }else{
            if(!isset($name[0])){
                $params[] = $name;
            }else{
                $params = $name;
            }            
        }

        if(empty($name))  throw new PDOException('Error : There is no value to be inserted into the database.');

        // column name
        $column = "";
        foreach($params[0] as $key => $val){  $column .= "`{$key}`,";  }
        $column = substr($column, 0, -1);

        $db_params = array();

        $sql_param = "";
        $tmp = "";
        $tmp2 = "";
        foreach($params as $key => $val){
            foreach($val as $key2 => $val2){
                $tmp = ":".$key2."_".$key;
                $tmp2 .= $tmp.",";
                $db_params[$tmp] = $val2;
            }
            $sql_param .= "(".substr($tmp2, 0, -1)."),";
        }

        $sql_param = substr($sql_param, 0, -1);

        $sql = "INSERT INTO `{$table}` ({$column}) VALUES {$sql_param}";

        $this->execute($sql, $db_params);

        return $this->getInsertId();
    }

    // data update
    public function update($params, $where = "", $table = ""){
        if(empty($params)){
            throw new PDOException('Error : No update data!');
        }

        if(empty($table)){  $table = $this->table;  }
        $db_params = array();

        $params_sql = "";
        if(is_array($params)){
            foreach($params as $key => $val){
                $params_sql .= "`".$key."` = :".$key.",";
                $db_params[":{$key}"] = $val;
            }
            $params_sql = substr($params_sql, 0, -1);
        }else{
            $params_sql = $params;
        }

        $where_sql = "";
        if(!empty($where)){
            $where_sql = " WHERE ";

            if(is_array($where)){
                foreach($where as $key => $val){
                    $where_sql .= " `".$key."` = :where_".$key." AND";
                    $db_params[":where_{$key}"] = $val;
                }
                $where_sql = substr($where_sql, 0, -3);
            }else{
                $where_sql = $where;
            }
        }

        $sql = "UPDATE `".$table."` SET ".$params_sql." ".$where_sql;

        $this->execute($sql, $db_params);
    }

    // data delete
    public function delete($where, $table = ""){
        if(empty($table)){  $table = $this->table;  }

        if(!empty($where)){
            $where_sql = " WHERE ";

            if(is_array($where)){
                foreach($where as $key => $val){
                    $where_sql .= "`{$key}` = :where_{$key} AND ";
                    $db_params[":where_{$key}"] = $val;
                }
                $where_sql = substr($where_sql, 0, strrpos($where_sql, 'AND'));
            }else{
                $where_sql .= $where;
            }
        }

        $sql = "DELETE FROM `{$table}` {$where_sql}";

        $this->execute($sql, $db_params);
    }

    // get last insert id
    public function fetch_column($table){
        if(empty($table)){  $table = $this->table;  }
        $sql = "SHOW COLUMNS FROM `{$table}`";
        $ret = $this->fetch($sql);
    }

}
