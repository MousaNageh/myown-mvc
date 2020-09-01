<?php 
class DB {
    private static $_instance = null ; 
    private $_pdo , $_query ; 
    private $_error = false;
    private $_result  ; 
    private $_count = 0 ; 
    private $_listInsertID =null ; 
    private $_not_fetched_actions = ["insert","update","delete","show"] ; 
    private $_action="" ; 
    private function __construct(){
        try{
            $this->_pdo = new PDO("mysql:host=".HOST_NAME.";dbname=".DATABASENAME , DB_USERNAME,DB_PASSWORD ,array(
                PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES utf8" , 
                PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION //not needed
                ) 
        ) ; 
        }
        catch(PDOException $ex){
            die($ex->getMessage()) ; 
        }
    }


    public static function getInstance(){
        if(self::$_instance ==null){
            self::$_instance = new DB() ; 
        }
        return self::$_instance ; 
    } 


    public  function query($query,$parms=[]){
        
        $this->_error = false ;
        $x =1 ; 
        $this->_query = $this->_pdo->prepare($query) ; 
        if(count($parms)>0){
            foreach($parms as $parm) {
                $this->_query->bindValue($x , $parm) ; 
                $x++ ; 
            }
        }
        if($this->_query->execute()){
            if(!in_array($this->_action,$this->_not_fetched_actions)){
                if($this->_query->rowCount() >0){
                    $this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ) ;
                }
            }
            $this->_count = $this->_query->rowCount() ; 
            $this->_listInsertID = $this->_pdo->lastInsertId() ;  
        }else {
            $this->_error = true ; 
        }
        
        return $this ; 
    }


    public function create($tablename,array $key_value_array){
        $this->_action ="insert" ; 
        $clumnsString ="" ; 
        $valuesString ="" ;
        $values = [] ;
        foreach($key_value_array as $clumn=>$value){
            $clumnsString  .="`".$clumn."`," ; 
            $valuesString.= "?," ; 
            $values[] =$value ;  
        }
        $clumnsString = rtrim($clumnsString ,",") ; 
        $valuesString =rtrim($valuesString,",") ;
        $sql = "INSERT INTO $tablename ($clumnsString) VALUES ($valuesString)" ; 
        if(!$this->query($sql,$values)->error()){
            $this->_action="" ; 
            return $this ; 
        }
        $this->_action="" ; 
        return false ; 
        
    }


    public function update($table ,$condtion , array $values_key_value){
        $this->_action = "update" ; 
        $filedString = "" ;
        $values = [] ;  
        foreach($values_key_value as $clumn =>$value) {
            $filedString .=" ".$clumn."=?," ;
            $values[] = $value ; 
        }
        $filedString =rtrim($filedString,",") ;
        $sql = "UPDATE $table SET $filedString WHERE $condtion" ;  
        if(!$this->query($sql,$values)->error()){
            $this->_action ="" ;
            return $this ; 
        }
        return false ; 
    }


    public function delete($table , $condtion) {
        $this->_action="delete" ; 
        if(!$this->query("DELETE FROM $table WHERE $condtion ")->error()){
            $this->_action="" ; 
            return $this ; 
        }
        return false ; 
    }

    protected function _read($table,$condtion, $order,$limit){
        $condtionString = "SELECT * FROM $table " ; 
        $bind = [] ; 
        if(count($condtion)>0){
            $condtionString.="WHERE " ; 
            foreach($condtion as $clumn=>$value){
                $condtionString.=$clumn."= ? AND " ; 
            }
            $condtionString = trim($condtionString) ; 
            $condtionString = rtrim($condtionString,"AND") ; 
            $bind[] = $value ; 
            
        }
        if($order!="") {
            $condtionString .=" ORDER BY $order " ; 
            
        }
        if($limit !=null) {
            $condtionString .=" LIMIT $limit" ; 
        }
        
        if(!$this->query($condtionString,$bind)->error()){
            return $this->_result ;  
        }
        return false  ; 
        
    }
    public function find($table,$condtion_key_value = [] , $order="",$limit=null){
        return $this->_read($table,$condtion_key_value,$order,$limit) ; 
    }
    public function findFirst($table,$condtion_key_value = [] ,$order=""){
        return $this->_read($table,$condtion_key_value,$order,1) ;
    }




    public function get(){
        return $this->_result ; 
    }
    
    public function count(){
        return $this->_count ; 
    }
    public function first(){
        if($this->count()>0) 
        {
            return $this->_result[0] ; 
        }
        return [] ; 
    }
    public function lastID(){
        return $this->_listInsertID ; 
    }
    public function get_Clumns_names($table){
        $clumns = [] ; 
        foreach($this->query("SHOW COLUMNS FROM  $table")->get() as $clumn){
            $clumns[]=$clumn->Field ; 
        }
        return $clumns; 
    }
    public function get_clumns_description($table){
        return $this->query("SHOW COLUMNS FROM $table")->get() ; 
    }
    public function error(){
        
        return $this->_error ;
    }
    


}