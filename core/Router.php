<?php
class Router {
  public static function route($url){
    //find controller
    $controller =(isset($url[0]) && $url[0]!="" )?ucwords($url[0]):DEFAULT_CONTROLLER;

    $controller_name = $controller ;
    //find method or action
    array_shift($url) ;
    $action = (isset($url[0]) && $url[0]!="" )?$url[0]:DEFAULT_ACRION;
    //acl check 
      $access = self::hasAccess($controller_name , $action)  ; 
      if(!$access){
        $controller_name = $controller = ACCESS_DESTRICTED ;
        $action = "index" ;  
      }
   //parms
    array_shift($url) ;
    $parms = $url ; 
   //dispatch
    $dispatch =  new $controller($controller_name,$action) ;
   //call the methods with parms
    if(method_exists($dispatch,$action)){
        call_user_func_array([$dispatch,$action],[$parms]) ; 
    }else
    {
      dd("the method of"."'".$action."' in the controller of '".$controller_name."' is not exists") ;
    }

  }
  public static function redirect($url){
      if(!headers_sent()){
        header("location: ".PROOT.$url) ; 
        exit ; 
      } else {
        echo "<script>" ; 
        echo "window.location.href='".PROOT.$url."' ;" ; 
        echo "</script>" ;
        echo "<noscript>" ;
        echo '<meta http-equiv="refresh" content="0;url='."'".$url."'".'" >' ;  
        echo "</noscript>" ;
        exit ; 
      }
  }
  public static function hasAccess($controller , $action="index"){
      $controller = ucwords($controller) ; 
      $acl = file_get_contents(ROOT.DS."app/acl.json") ;
      $acl = json_decode($acl ,true) ;   
      $user_access = ["Guest"] ;
      $access = true ; 
      if(Session::exists(CURRENT_USER_SESSION_NAME)){
        $user_access[] = "LoggedIn" ; 
        if(!empty(userinfo()->acl)){
          foreach (json_decode(userinfo()->acl) as $a) {
            $user_access [] = $a ; 
          }
        }
      }
      foreach($user_access as $level){
        if(array_key_exists($level , $acl) && array_key_exists($controller , $acl[$level]) ){
          if($acl[$level][$controller][0] == "*" || in_array($action ,$acl[$level][$controller])) 
            $access = true ; 
            break ; 
          
        } 
      }
      foreach ($user_access as $level){
        if(array_key_exists($level , $acl)){
          $denied = $acl[$level]["denied"] ; 
          if(!empty($denied)&&array_key_exists($controller,$denied)&& in_array($action,$denied[$controller])){
              $access = false ; 
              break ; 
          }
        }
        
      }
    return $access ; 
  }
}
?>
