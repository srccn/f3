<?php

class  PropertyController extends BaseController {

	private $inputs;
    private $property;
    private $viewRecords = array();
	
	function __construct($inputForm) {
		$this->inputs = $inputForm;
	}
	
    function test () {
    	
    	$property = new LoanProperty($this->inputs);
    	$property->printProperty();
    	$myoptions = $property->loanAmountOptions;

     	$myFeeCalculator = new FeeCalculator($property);
     	$totalFee = $myFeeCalculator->getTotalFees();
     	//echo "fees : " . $totalFee;
    	
     	$purchasers=["BBT", "BOKF", "WELLSFARGO"];
     	//$purchasers=["BOKF"];
     	foreach ($purchasers as $purchaser) {
            $myRecord = new ViewRecord;
            $myRecord->product = $property->loanName;
            $myRecord->purchaser = $purchaser;
            
     		foreach ($myoptions as $opt) {
     			$property_clone = clone $property; 
     		    Util::dump("=== purchaser $purchaser, loan option = $opt[1] + $opt[2]") ;
     		    if ($opt[2] > 0) {
     		        Util::dump ("=== Primary loan","");
     		    }
     		    $myRecord1 = clone $myRecord;
     		    
     		    $myRecord1->loanAmount=$opt[1];
     		    
     		    $property_clone->loanAmount = $opt[1];
     		    $property_clone->calculateDerives();
     		    $purchaserCalculatorName  = $purchaser."RateCalculator" ;
     		    $myRateCalculator = new $purchaserCalculatorName ;
     		    $myRateCalculator->setProperty($property_clone);
     		    $myRateCalculator->setTotalFee($totalFee);
     		    $myresult = $myRateCalculator->calculteRate();
     		    
     		    //$myRecord->loanAmount=$opt[1];
     		    $myRecord1->rate = $myresult['rate'];
     		    $myRecord1->credit = $myresult['credit'];
     		    $myRecord1->lockDays = $myresult['lockDays'];
     		    $myRecord1->margin = $myresult['margin'];
     		    $myRecord1->minCredit = $myresult['minCredit'];

     		    $myRecord2 = clone $myRecord;
     		    if ($opt[2] > 0) {
     		        Util::dump("=== Secondary loan");
     		        $myresult2 = $myRateCalculator->calculteSecondaryRate( $opt[2] );
     		        
     		        $myRecord2->loanAmount = $opt[2];
     		        $myRecord2->rate =     $myresult2['rate'];
     		        $myRecord2->credit =   $myresult2['credit'];
     		        $myRecord2->lockDays = $myresult2['lockDays'];
     		        $myRecord2->margin =    $myresult2['margin'];
     		        $myRecord2->minCredit = $myresult2['minCredit'];
     		         
     		    }
     		    $resultRecord = array(
     		    		               "part1" => $myRecord1, 
     		    		               "part2" => $myRecord2
     		    		        );
     		    //var_dump ($resultRecord );
     		    array_push($this->viewRecords, $resultRecord);
     		    //echo "<hr>";
     		} //option
     	}//purchaser
     	$r = uasort($this->viewRecords, 'Util::cmp');
     	var_dump($this->viewRecords);
    }

}
?>