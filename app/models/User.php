<?php 
class User extends Model {
    private $_isLogin , $_sessionName , $_cookieName ; 
    public static $currentLoggedInUsr = null ; 
    public function __construct($user_email_or_id="")
    {
        $table = "users" ;
        parent::__construct($table) ; 
        $this->_sessionName = CURRENT_USER_SESSION_NAME ;
        $this->_cookieName = REMEMBER_ME_COOKIE_NAME ; 
        $this->_softDelete = true ; 
        if(!empty($user_email_or_id)){
            if(is_integer($user_email_or_id)){
                $user = $this->_db->findFirst($table,["id"=>$user_email_or_id]) ; 
            }else 
            {
                $user = $this->_db->findFirst($table,["email"=>$user_email_or_id]) ; 
            }
        }
        if(isset($user)&&!empty($user)){
            foreach($user as $key=>$value){
                $this->$key = $value ; 
            }
        }
    }
    public function findByEmail($email){
        return $this->findFirst(["email"=>$email]) ; 
    }
    public function findByUsername($username){
        return $this->findFirst(["username"=>$username]) ; 
    }
    public function login($rememberme = false){
        Session::set($this->_sessionName,$this->id) ; 
        if($rememberme){
            $hash = md5(uniqid() . rand(0,100)) ;
            $agent = Session::uagent_no_version() ; 
            Cookie::set($this->_cookieName,$hash ,REMEMBER_COOKIE_EXPIRY) ; 
            $fields = ["session"=>$hash , "user_agent"=>$agent , "user_id"=>$this->id] ;
            $check = $this->_db->query("SELECT * FROM sessions WHERE user_id = ?", [$this->id])->get() ; 
            if($check){ 
                $this->_db->query("DELETE FROM sessions WHERE user_id = ? AND user_agent = ?",[$this->id , $agent]) ; 
            }
            
            $this->_db->create("sessions",$fields) ; 
        }
    }
    public static function currentLoggedInUser(){
        if(self::$currentLoggedInUsr != null)
            return  self::$currentLoggedInUsr ; 
        if(Session::exists(CURRENT_USER_SESSION_NAME)){
            self::$currentLoggedInUsr= new User((int)Session::get(CURRENT_USER_SESSION_NAME)) ; 
            return self::$currentLoggedInUsr ;
        } 
        return null ;
        
    }
    public  function logout(){
        $agent = Session::uagent_no_version() ; 
        $check = $this->_db->query("SELECT * FROM sessions WHERE user_id = ?", [$this->id])->get() ; 
            if($check){
                $this->_db->query("DELETE FROM sessions WHERE user_id = ? AND user_agent = ?",[userinfo()->id , $agent]) ; 
            } 
        Session::delete(CURRENT_USER_SESSION_NAME) ; 
        if(Cookie::Exists(REMEMBER_ME_COOKIE_NAME)){
            Cookie::delete(REMEMBER_ME_COOKIE_NAME) ;
        }
        self::$currentLoggedInUsr = null ; 
        return true ; 
    } 
    public static function loginFromCookie(){
        $user  = new User() ;
        $data=$user->_db->query("SELECT * FROM sessions WHERE session=? AND user_agent = ?" , [Cookie::get(REMEMBER_ME_COOKIE_NAME),Session::uagent_no_version()])->get() ; 
        $data = array_shift($data)  ;
        $user = $user->findByID($data->user_id);
        self::$currentLoggedInUsr = $user ; 
        Session::set(CURRENT_USER_SESSION_NAME,$data->user_id) ; 
        Router::redirect("") ; 
    }
    
}