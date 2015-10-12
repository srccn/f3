<?php 
class myClass extends BaseController {
    
    function test(){
        // echo "in test function";
        $this->f3->set('page_head','My class -> Test');
        echo Template::instance()->render('layout.htm');
		(new PropertyController)->getLoanLimitByZipCode("02460", 'two_unit');
    }
    
    function home() {
        echo "Hello World";
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
    
    public function testFunc() {
        $myv = new LoanType($this->db);
        $myv->getIDByNameAndConfirming("fixed30",0);
        //var_dump ($myv->all());
    }
}

?>
