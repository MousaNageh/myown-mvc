<?php 
class Model {
    protected $_db , $_table , $_modelname , $_softDelete = false ,$_columnNames=[] ; 
    public $id ; 
    public function __construct($table)
    {
        $this->_db = DB::getInstance() ; 
        $this->_table = $table ; 
        $this->_columnNames = $this->_db->get_Clumns_names($table) ; 
        //$this->_setTableClumns() ; 
        $this->_modelname = ucwords($this->_table) ;
        $this->_modelname = rtrim($this->_modelname,"s") ; 
        
    }
    // protected function _setTableClumns(){
    //     foreach($this->_columnNames as $clumn){
    //         $this->{$columnName} = null ; 
    //     }
    // }
    public function get_columns(){
        return $this->_columnNames ; 
    }
    public function get_columns_info(){
        return $this->_db->get_clumns_description($this->_table) ; 
    }
    public function find($condtion_key_vale=[],$order="",$limit=null){
        $instances=[] ; 
        $resualts = $this->_db->find($this->_table ,$condtion_key_vale,$order,$limit) ;
        if($resualts){
            foreach ($resualts as $clumn) {
                $obj = new $this->_modelname($this->_table) ; 
                foreach($clumn as $key=>$value){
                    $obj->$key = $value ; 
                }
                $instances[]=$obj ; 
            } 
            return $instances ; 
        }else {
            return null ; 
        }
    }
    public function findFirst($condtion_key_vale=[],$order=""){
        
        $resualt = $this->_db->findFirst($this->_table,$condtion_key_vale ,$order) ;
        if($resualt){
            $resualt = array_shift($resualt) ;
        
            $obj = new $this->_modelname($this->_table) ; 
            foreach($resualt as $key=>$value) 
            {
                $obj->$key = $value ; 
            } 
            return $obj ;
        }else {
            $emptyclass = new stdClass() ; 
            foreach($this->_columnNames as $clumn){
                $emptyclass->$clumn = null ; 
            }
            return $emptyclass ; 
        }
        
    }

    public function findByID($id){
        return $this->findFirst(["id"=>$id]) ; 
    }
    public function create($parms_key_value){
        if(empty($parms_key_value))
        {
            return false ; 
        }
        
        return $this->findByID($this->id) ; 
    }
    public function update($values_key_value=[]){
        if(empty($values_key_value))
        {
            return false ; 
        }
        $this->_db->update($this->_table,"id=".$this->id , ["fname"=>"modeupdate"]) ;
        return $this->findByID($this->id) ; 
    }
    public function delete(){
        if(empty($this->id) ||$this->id ==null) return false ;
        if($this->_softDelete){
            return $this->update(["delete"=>1]) ; 
        }
        $this->_db->delete($this->_table,"id=".$this->id) ; 
    }
    public function query($query , $parms){
        return $this->_db->query($query , $parms)  ;
    }
    public function data(){
        $data = new stdClass() ; 
        foreach($this->_columnNames as $clumn){
            $data->$clumn = $this->$clumn ; 
        } 
        return $data ; 
    }
    public function assign(array $parms_key_value){
        if(!empty($parms_key_value)){
            foreach($parms_key_value as $key=>$value){
                if(array_key_exists($key,$this->_columnNames)){
                    $this->$key = sanitize($value) ; 
                }else {
                    return false ; 
                }
            }
            return $this ; 
        }else{
            return false ; 
        }
    }
    public function save(){
        $fields = [] ; 
        foreach($this->_columnNames as $clumn){
            $fields[$clumn]=$this->$clumn ; 
        }
        //determine to update or insert 
        if(property_exists($this,"id")&& !empty($this->id)){
            return $this->update($fields) ;
        }else{
            return $this->create($fields) ; 
        }
    }
    
}