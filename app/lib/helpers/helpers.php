<?php
function dd($data){
  echo"<pre>" ;
  var_dump($data) ;
  echo "</pre>";
  die() ; 
}
function sanitize($value){
  if(is_integer($value))
  return filter_var($value,FILTER_SANITIZE_NUMBER_INT)  ; 
  if(is_string($value))
  return trim(filter_var($value,FILTER_SANITIZE_STRING)) ; 
} 
function currentUser(){
  return User::currentLoggedInUser() ; 
}
function userinfo(){
  if(currentUser() != null ){
    $v =[] ; 
        foreach (currentUser() as $value ) {
            $v[] = $value ; 
        }
      return $v[1] ; 
  }else{
    return null ; 
  }
}
