<?php
//paths
$config_path = ROOT.DS."config".DS ;
$lib_path = ROOT.DS."app".DS."lib".DS;
$GLOBALS["core_path"]= ROOT.DS."core".DS ;
$GLOBALS["controller_path"] = ROOT.DS."app".DS."controllers".DS ;
$GLOBALS["model_path"] = ROOT.DS."app".DS."models".DS ; 
//load config and helpers functions
require_once $config_path."config.php" ;
require_once $lib_path."helpers".DS."functions.php" ;
require_once $lib_path."helpers".DS."helpers.php" ;
//autoload

function autoload($classname) {
  if(file_exists($GLOBALS["core_path"].$classname.".php")){
        require_once $GLOBALS["core_path"].$classname.".php" ;
  }
  elseif (file_exists($GLOBALS["controller_path"].$classname.".php")){
      require_once $GLOBALS["controller_path"].$classname.".php" ;
  }
  elseif (file_exists($GLOBALS["model_path"].$classname.".php")){
    require_once $GLOBALS["model_path"].$classname.".php";
}
else 
{
  dd($GLOBALS["model_path"].$classname.".php") ; 
}
}
spl_autoload_register("autoload") ; 
// $conection = DB::getInstance() ;

Router::route($url) ;
