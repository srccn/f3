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
        //echo "zip           : " . $myRequest['zipCode'] . "<br>";
        //echo "Loan Amount   : " . $myRequest['loanAmount'] . "<br>";
        //echo "Property type : " . $myRequest['properyType'] . "<br>" ;
        //echo "Lock Days     : " . $myRequest['lockDays'] . "<br>" ;
        //echo "<h3>Is confirming loan or not</h3>";

        foreach ($myRequest as $key => $value) {
            echo "Key: $key; Value: $value<br>";
        }
        
        $myProperty = new PropertyController;
        $myProperty->setInput($myRequest);
        $myProperty->test();
    }
    
}

?>
