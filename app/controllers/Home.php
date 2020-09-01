<?php
class Home extends Controller {
    public function __construct($controller,$action)
    {
        parent::__construct($controller,$action) ; 
        
    }
    public function index(array $parms){ 

        $this->view->render("home/index"); 
    }
    
}