<?php 
class Input{
    public static function sanitizeEamil($email){
        $email = trim($email) ; 
        $email = filter_var($email , FILTER_SANITIZE_EMAIL) ; 
        return $email ; 
    }
    public static function sanitizeString($str){
        $str = trim($str) ; 
        $str = filter_var($str, FILTER_SANITIZE_STRING) ; 
        return $str ; 
    }
    public static function sanitizeNumber($number){
        $number = trim($number) ; 
        $number = filter_var($number , FILTER_SANITIZE_NUMBER_INT) ; 
        return $number ;
    }
    public function sanitizeFloat($float , $numofdigits = null){
        $float = trim($float) ; 
        $float = filter_var($float , FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION) ; 
        if($numofdigits !=null)
        {
            $float = round($float , $numofdigits) ; 
        }
        return $float ; 
    }
    public static function  get($inputname ,$type="string"){
        if(isset($_POST[$inputname])){
            if($type =="string") 
            { 
                
                return self::sanitizeString($_POST[$inputname]) ; 
                
            }
            elseif($type=="int" | $type == "integer" | $type =="number"){
                
                return self::sanitizeNumber($_POST[$inputname]) ;
                
            }
            elseif($type="email"){
                
                return self::sanitizeEamil($_POST[$inputname]) ; 
            }
        }
    }
    public static function exists($inputname){
        return isset($_POST[$inputname])?true : false ; 
    }
    public static function getRequest(){
        if($_POST){
            $request = new stdClass() ; 
            foreach($_POST as $key=>$value) {
                if(!empty($_POST[$key]))
                $request->$key = $value ;
                else 
                $request->$key = null ;
            }
        }
        return $request ; 
    }

}