<?php 
/*
1) required 
2) email 
3) string 
5) min: 
6) max: 
8)unique:table,cloumn,except_id
9)alpha:number
10)num:value
*/
class Validate{
    private $_passsed = false , $_error =[] ,$_db=null ; 
    public function __construct()
    {
        $this->_db = DB::getInstance() ; 
    }
    public function addError($error){
        $this->_error[] = $error ; 
    }
    
    public function check($source , $rules){
        $this->_error=[] ;  
        foreach($source as $post_name => $post_value){
            if(array_key_exists($post_name,$rules)){
                $arrayOfRules = trim($rules[$post_name]) ;
                $arrayOfRules = explode("|",$arrayOfRules) ; 
                foreach ($arrayOfRules as $rule){
                    if($rule == "required"){
                        if(empty($post_value))
                        $this->addError("the $post_name field is required") ; 
                    }elseif($rule == "email"){  
                        $_POST[$post_name] = Input::sanitizeEamil($_POST[$post_name]) ; 
                        if(!filter_var($_POST[$post_name] , FILTER_VALIDATE_EMAIL)) 
                        $this->addError("the $post_name field is not valid") ; 
                    }elseif($rule == "string"){
                        $_POST[$post_name] = Input::sanitizeString($_POST[$post_name]) ;
                        if(!is_string( $_POST[$post_name]))
                        $this->addError("the $post_name field is not string") ; 
                    }elseif (preg_match("/alpha:\d+/",$rule)){
                        $num = str_replace("alpha:","",$rule) ; 
                        $num = trim($num) ; 
                        $num = Input::sanitizeNumber($num) ; 
                        preg_match_all("/[A-Z]/",$post_value,$matches) ;
                        $matches = array_shift($matches) ; 
                        if($num>count($matches))
                        $this->addError("the $post_name field at least must contain $num Alpha character") ; 
                    }elseif (preg_match("/num:\d+/",$rule)){
                        $num = str_replace("num:","",$rule) ; 
                        $num = trim($num) ; 
                        $num = Input::sanitizeNumber($num) ; 
                        preg_match_all("/\d/",$post_value,$matches) ;
                        $matches = array_shift($matches) ; 
                        if($num>count($matches))
                        $this->addError("the $post_name field at least must contain $num numbers or digits") ; 
                    }elseif (preg_match("/min:\d+/",$rule)){
                        $num = str_replace("min:","",$rule) ; 
                        $num = trim($num) ; 
                        $num = Input::sanitizeNumber($num) ; 
                        if($num>strlen($post_value))
                        $this->addError("the $post_name field must be at least $num digits") ; 
                    }
                    elseif (preg_match("/max:\d+/",$rule)){
                        $num = str_replace("max:","",$rule) ; 
                        $num = trim($num) ; 
                        $num = Input::sanitizeNumber($num) ; 
                        if(strlen($post_value)>$num)
                        $this->addError("the $post_name field max $num digits") ; 
                    }elseif(preg_match("/unique:\w+/",$rule)){
                        //dd("unique") ; 
                    }
                    
                }
            }else{
                dd("in the rules array there is not valid key") ;
            }
            
        }
        
        
    }
    


    


    public function passed(){
        if(empty($this->_error)){
            $this->_passsed = true ; 
        }
        return $this->_passsed ; 
    }
    public function getErrors(){
        return $this->_error ; 
    }
    
}