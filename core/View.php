<?php

class View
{
  protected $_head , $_body  ,$_outputBuffer , $_layout = DEFAULT_LAYOUT ;
  protected $_siteTitle = SITE_TITLE ;
  protected $data="" ;
  protected $viewname ;
  function __construct()
  {

  }
  public function render($viewname,$data=[]) {
    $this->viewname = $viewname ;
    if(file_exists(ROOT.DS."app".DS."views".DS.$viewname.".php")){
      if(!empty($data)){
        foreach($data as $key =>$value){
          $this->$key = $value ; 
        }
      }
      require_once ROOT.DS."app".DS."views".DS.$viewname.".php" ;
      require_once ROOT.DS."app".DS."views".DS."layouts".DS.$this->_layout.".php" ;
      return $this ;
    }else
    {
      dd("the view of ' ".ROOT.DS."app".DS."views".DS.$viewname.".php"." ' not exists") ;
    }
  }
  public function content($type){
    if($type =="head"){
      return $this->_head ;
    }elseif($type=="body"){
      return $this->_body ;
    }else{
      return false ;
    }
  }
  public function start($type){
    $this->_outputBuffer =$type ;
    ob_start() ; //this function store all the code until use end function which will end storing
  }
  public function end(){
      if($this->_outputBuffer =="head"){
        $this->_head = ob_get_clean() ;
      }elseif($this->_outputBuffer=="body"){
        $this->_body = ob_get_clean() ;
      }else{
        dd("you must use start method") ;
      }

  }
  public function getSiteTitle(){
    return $this->_siteTitle ;
  }
  public function setSeiteTitle($title){
    $this->_siteTitle =$title ;
  }
  public function setLayout($path){
    $this->_layout =$path ;
  }
  public function with($name , $value){
    $this->$name = $value ;
    return  $this->render($this->viewname) ;
  }

}
