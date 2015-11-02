<?php 
class myClass extends BaseController {
    
    function test(){
        // echo "in test function";
        $this->f3->set('page_head','Green Bird - Customers');
        echo Template::instance()->render('layout.htm');
		//(new PropertyController)->getLoanLimitByZipCode("02460", 'two_unit');
    }
    
    function home() {
   		if (isset($this->f3->SESSION['username']) && !empty($this->f3->SESSION['username'] )) {
    		$this->f3->reroute('/customer');
    	} else {
    	    $this->f3->set('view','blank.htm');
    	}
         echo Template::instance()->render('layout.htm');
    }
    
    function signin() {
        $this->f3->set('view','signin.htm');
        echo Template::instance()->render('layout.htm');
    }

    
    function verifysignin () {
     	if (! empty ( $this->f3->POST)) {
        	if ( isset ($this->f3->POST['username'] ) && isset($this->f3->POST['password']) ) {
        		
        		$name = $this->f3->POST['username'];
        		
        		//if check passwrod succeeded
        		$query = "
        				SELECT password
        				FROM   loaner.user
        				WHERE  name = '$name'
        				";
        		$result=$this->runQuery($query);
        		$regpassword = $result[0]['password']; 
        		
        		if (Util::verifyHash($this->f3->POST['password'], $regpassword)) {
        			$this->f3->SESSION['username'] = $this->f3->POST['username'];        			
        		} else {
        			echo "Sign in Failed. ";
        		}
    	    }
     	}
     	$this->f3->set('message','Welcom ');
     	
	    $this->f3->reroute('/home');
    }

    function signup() {
        $this->f3->set('view','signup.htm');
        echo Template::instance()->render('layout.htm');
    }
    
    function addUser(){
    	$name=$this->f3->POST['username'];
    	$email = $this->f3->POST['email'];
    	$passwrod = Util::hashString($this->f3->POST['password']);
		$timeStamp = time();
    	$query = "
    			INSERT into loaner.user 
    			            (name, email,password,regdate)
    			VALUES ('$name' , '$email', '$passwrod', $timeStamp)
    			" ;
    	Util::dump($query);
    	$this->runQuery($query);
    	
    	$this->f3->SESSION['username'] = $name;
    	$this->f3->reroute('/home');
    }
    
    function signout() {
    	//$this->f3->SESSION['user'] = 'unknwn'; 
    	session_destroy();
    	Util::dump($this->f3->SESSION);
    	$this->f3->reroute('/home');
    }
    
    function showStatic() {
        $view = new view;
        echo $view->render('front.html');
    }

    function calculate() {
        $myRequest = $this->f3->get('REQUEST');
        
        $myProperty = new PropertyController($myRequest);
        $myProperty->test();
        
        echo Template::instance()->render('result.htm');
        
    }
	
	function testPurchaseLoader () {
	    $myloader = new LoadPurchaseController ;
		$myloader->setExcelMapFile("data/BOKF.php");
		$myloader->reloadData();
	}

	function testAdjLtvCcLoader(){
	    echo "Test load to table adj_ltv_cc <br>";
		$myloader = new LoadAdj_ltv_ccController ;
		$myloader->setExcelMapFile("data/BOKF.php");
		$myloader->reloadData();
	}
    
    function testFunc() {
        $myv = new LoanType($this->db);
        $myv->getIDByNameAndConfirming("fixed30",0);
        //var_dump ($myv->all());
    }
}

?>