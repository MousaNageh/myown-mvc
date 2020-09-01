<?php 
class Register extends Controller {
    public function __construct($controller,$action)
    {
        parent::__construct($controller,$action) ; 
        $this->load_model("User") ; 
    }
    public function login(array $parms){
        if(!Session::exists(CURRENT_USER_SESSION_NAME) ){
            $this->view->render("register/login");
        }else{
            Router::redirect("") ; 
        }

    }
    public function check(){
    
        if($_POST){ 
            
            
            //dd($validation->getErrors()) ;

            $validation = true ; 
            if($validation){
                
                $user = $this->UserModel->findByEmail(Input::get("email","email")) ; 
                if($user->id !=null){
                    if(sha1($_POST["password"])==$user->password)
                    {
                        $remeberme =  (Input::exists("rememberMe") && Input::get("rememberMe")) ? true : false ; 
                        $user->login($remeberme) ;
                        Router::redirect("") ; 
                    }
                    else 
                    {
                        $this->view->render("register/login",["passworderror"=>"wrong password .", "password"=>Input::get("password"),"email"=>Input::get("email")]);
                        
                    }
                }else{ 
                    
                    $this->view->render("register/login",["error"=>"wrong email .","email"=>Input::get("email"),"password"=>Input::get("password")]); 
                }
            }
        }
        else {
            Router::redirect("register/login") ; 
        }
    }
    public function logout(){
        if(Session::exists(CURRENT_USER_SESSION_NAME)){
            currentUser()->logout() ; 
        Router::redirect("register/login") ;  
        } 
        else  
        Router::redirect("") ; 
    }
    public function register(){
        $this->view->render("register/register") ; 
    }
} 
