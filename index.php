<?php
session_start() ;
define("DS","/") ;
define("ROOT",__DIR__) ;
$url = isset($_SERVER["PATH_INFO"])?explode(DS,trim($_SERVER["PATH_INFO"])):[] ;
if(isset($url[0])&&empty($url[0])){
    array_shift($url);
}

require_once ROOT.DS."core".DS."bootstrap.php" ;   
if(!Session::exists(CURRENT_USER_SESSION_NAME)&&Cookie::Exists(REMEMBER_ME_COOKIE_NAME)){
    
    User::loginFromCookie() ; 
}

//the flow of the app 
/*
1) we write the index which all of thinks will included on it 
in index will will get the path of the url by using $_SERVER["PATH_INFO"] and convert it store it in $url 
2)we make the bootstrap of the application and include it in index 
bootstrap will include the required file like core files config files controller files and else 
will clall the router 
3)we create the router will use the $url and convert it to array to determine which and controller will used 
4) we create application controller wich has some config for errors end security 
5)create the mean controller will all controllers will extends form all controllers
and this controller will take the controller name and 
action from the router and create the view 
6)create the core view 
7)create DB class 
8)create Model class
*/

