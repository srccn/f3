<?php 
class myClass extends BaseController {
    
    function test(){
        // echo "in test function";
        $this->f3->set('page_head','Green Bird - Customers');
        echo Template::instance()->render('layout.htm');
		//(new PropertyController)->getLoanLimitByZipCode("02460", 'two_unit');
    }
    
    function home() {
        //echo "Hello World";
        //return;
        $this->f3->set('page_head','Home');
//     	if (isset($this->f3->SESSION['username']) && !empty($this->f3->SESSION['username'] )) {
//     		$this->f3->set('view','restricted.html');
//     	} else {
//     	    $this->f3->set('view','home.htm');
//     	}
   	    $this->f3->set('view','home.htm');
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
        				SELECT password, id
        				FROM   user
        				WHERE  name = '$name'
        				";
        		$result=$this->runQuery($query);
        		$regpassword = $result[0]['password']; 
        		$userId = $result[0]['id'];
        		echo $userId;
        		
        		if (Util::verifyHash($this->f3->POST['password'], $regpassword)) {
        			$this->f3->SESSION['username'] = $this->f3->POST['username'];
        		} else {
        			echo "Sign in Failed. ";
        		}
    	    }
     	}
     	$this->f3->set('message','Welcome ');
     	
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
    	$phone = $this->f3->POST['phone'];
		$timeStamp = time();
		
		//check user existence 
		$query = "select count(*) as result from user where name = '$name'";
		$result = $this->runQuery($query);

		if ($result[0]['result'] > 0 ) {
            $this->f3->set('message', 'Sing up failed. User name is already exist, try to choose antoher one.')	;
            $this->f3->set('view','home.htm');
            echo Template::instance()->render('layout.htm');
		} else {
			$query = "
				INSERT into user
				(name, email,password,regdate,phone)
				VALUES ('$name' , '$email', '$passwrod', $timeStamp, '$phone')
			" ;
			 
			$this->runQuery($query);
			$this->f3->SESSION['username'] = $name;
			$this->f3->reroute('/home');
		}
    }
    
    function signout() {
    	//$this->f3->SESSION['user'] = 'unknwn'; 
    	session_destroy();
    	Util::dump($this->f3->SESSION);
    	$this->f3->reroute('/home');
    }
    
    function showFront(){
    	$this->gotoPage('front.html');
    }
    
    function showFront_t(){
    	$this->f3->set('view','front_t.html');
    	echo Template::instance()->render('layout.htm');
    }
    
    function showRestricted(){
    	$this->gotoPage('restricted.html');
    }
    
    function showStatic() {
        $view = new view;
        echo $view->render('front.html');
    }

    function calculate() {
        $myRequest = $this->f3->get('REQUEST');
        
//         if (! isset($myRequest['submit'])) { //in case call calculate directly, redirect to front
//         	$this->f3->reroute('/front');
//         }
        
        $myProperty = new PropertyController($myRequest);
        $myProperty->searchRate();
        
        echo Template::instance()->render('result.htm');
        
    }
	
	function testPurchaseLoader () {
	    $myloader = new LoadPurchaseController ;
		$myloader->setExcelMapFile("data/WELLSFARGO.php");
		$myloader->reloadData();
	}

	function testAdjLtvCcLoader(){
	    echo "Test load to table adj_ltv_cc <br>";
		$myloader = new LoadAdj_ltv_ccController ;
		$myloader->setExcelMapFile("data/CHASE.php"); //donnot use BBT, data manually inputed
		$myloader->reloadData();
	}

	function testStateListSRPLoader(){
		echo "Test load to table  state_srp_full_list <br>";
		$myloader = new LoadStateListSRPController ;
		$myloader->setExcelMapFile("data/CHASE.php");
		$myloader->defineObjPHPExcel('data/chase srp.xls');
		$myloader->reloadData();
	}
	
	
    function testFunc() {
        $myv = new LoanType($this->db);
        $myv->getIDByNameAndConfirming("fixed30",0);
        //var_dump ($myv->all());
    }
    
    function gotoPage( $pageUrl ) {
    	if (isset($this->f3->SESSION['username']) && !empty($this->f3->SESSION['username'] )) {
    		//$this->f3->reroute('/customer');
    		$this->f3->set('view',$pageUrl);
    	} else {
    		$this->f3->set('message', 'You are trying to access restricted area, please sign in first');
    		$this->f3->set('view','home.htm');
    	}
    	echo Template::instance()->render('layout.htm');
    	 
    }
    
    function saveForm(){
    	
    	$name=$this->f3->SESSION['username'];
    	$name = str_replace (";", "", $name );

    	$query = "select id from user where name = '" .$name."'";
    	$result = $this->runQuery($query);
    	
    	$input = new UserInputForm($this->db);
    	$input->edit($result[0]['id']);
    	$this->f3->set('message','alert saved.');
    	echo "succeeded from saveForm";
    }
    
    function loadInputForm(){
    	if (isset($this->f3->SESSION['username']) && !empty($this->f3->SESSION['username'] )) {
	    	$username = $this->f3->SESSION['username'];
	    	//$username="qq";
	    	$query = "
			    	SELECT id
			    	FROM   user
			    	WHERE  name = '$username'
		    	";
	    	$result=$this->runQuery($query);
	    	$userId = $result[0]['id'];
	    	 
	    	$inputForm = new UserInputForm($this->db);
	    	$inputForm->getByUserId($userId);
	    	//print_r($this->f3->get('myForm'));
	    	
	    	$theForm = $this->f3->get('myForm');
	    	unset ($theForm['id'] );
	    	unset ($theForm['user_id'] );
	    	
	    	echo json_encode($theForm);
    	} else {
    		$this->f3->set("message","please log in first.");
    		die("unknow login user");
    	}
    }
    
    function uploadFile(){
    	$this->f3->set('view','uploadfile.html');
    	echo Template::instance()->render('layout.htm');    	
    }
    
    function upload() {
    	$target_dir = "data/";
    	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    	
    	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    		echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    	} else {
    		echo "Sorry, there was an error uploading your file.";
    	}
    	
    	echo "<br><br>start update DB for " . $this->f3->get('POST.purchaserName') . " please wait...<br><br>";
    	
    	$myloader = new LoadPurchaseController ;
    	$myloader->setExcelMapFile("data/BBT.php");
    	$myloader->reloadData();    	
    	
    }
    
}

?>