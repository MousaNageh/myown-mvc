<?php 
class Session{
    public static function exists($name){
        return isset($_SESSION[$name])?true : false ; 
    }
    public static function get($name){
        return $_SESSION[$name] ; 
    }
    public static function set($name,$value){
        $_SESSION[$name] = $value ; 
        return $_SESSION[$name] ; 
    }
    public static function delete($name){
        if(self::exists($name)){
            unset($_SESSION[$name]) ; 
            return true ;
        }
        return false ; 
    }
    public static function uagent_no_version(){
        $uagent = preg_replace("/\/(\w|\.)+/","",$_SERVER["HTTP_USER_AGENT"]) ;
        $uagent = preg_replace("/; ?/","_",$_SERVER["HTTP_USER_AGENT"]) ;
        return $uagent ; 
    }
}