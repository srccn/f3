<?php 
class myClass extends BaseController {
    
    function test(){
        // echo "in test function";
        $this->f3->set('page_head','Green Bird - Customers');
        echo Template::instance()->render('layout.htm');
		//(new PropertyController)->getLoanLimitByZipCode("02460", 'two_unit');
    }
    
    function home() {
 
   		if ($this->f3->POST['username'] == 'bfang') {
    			$this->f3->SESSION['user'] = 'bfang';
    			$this->f3->reroute('/customer');
    	} else {
    		 
    	     $this->f3->set('view','home.htm');
    	}
         echo Template::instance()->render('layout.htm');
    }
    
    function signin() {

        $this->f3->set('view','signin.htm');
        echo Template::instance()->render('layout.htm');
    }
    
    function verifysignin () {
    	if (! empty ( $this->f3->POST)) {
    		if ($this->f3->POST['username'] == 'bfang') {
    			$this->f3->SESSION['user'] = 'bfang';
    			$this->f3->reroute('/customer');
    		}
    	} else {
    		$this->f3->reroute('/home');
    	}
    	 
    }

    function signup() {
        $this->f3->set('view','signup.htm');
        echo Template::instance()->render('layout.htm');
    }
    function signout() {
    	//$this->f3->SESSION['user'] = 'unknwn'; 
    	session_destroy();
    	var_dump($this->f3->SESSION);
    	$this->f3->reroute('/home');
    }
    
    function showStatic() {
        $view = new view;
        echo $view->render('front.html');
    }

    function calculate() {
        $myRequest = $this->f3->get('REQUEST');
//        foreach ($myRequest as $key => $value) {
//            echo "Key: $key; Value: $value<br>";
//        }
        
        $myProperty = new PropertyController($myRequest);
        //$myProperty->setInput($myRequest);
        $myProperty->test();
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