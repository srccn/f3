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
     		    echo ("=== purchaser $purchaser, loan option = $opt[1] + $opt[2]") ;
     		    if ($opt[2] > 0) {
     		        Util::dump ("=== Primary loan","");
     		    }
     		    
     		    $myRecord->loanAmount=$opt[1];
     		    
     		    $property_clone->loanAmount = $opt[1];
     		    $property_clone->calculateDerives();
     		    $purchaserCalculatorName  = $purchaser."RateCalculator" ;
     		    $myRateCalculator = new $purchaserCalculatorName ;
     		    $myRateCalculator->setProperty($property_clone);
     		    $myRateCalculator->setTotalFee($totalFee);
     		    $myresult = $myRateCalculator->calculteRate();
     		    
     		    $myRecord->rate = $myresult['rate'];
     		    $myRecord->credit = $myresult['credit'];
     		    $myRecord->lockDays = $myresult['lockDays'];

     		    if ($opt[2] > 0) {
     		        Util::dump("=== Secondary loan");
     		        $myRecord2 = clone $myRecord;
     		        $myresult2 = $myRateCalculator->calculteSecondaryRate( $opt[2] );
     		        
     		        $myRecord2->loanAmount = $opt[2];
     		        $myRecord2->rate = $myresult2['rate'];
     		        $myRecord2->credit = $myresult2['credit'];
     		        $myRecord2->lockDays = $myresult2['lockDays'];
     		         
     		    }
     		    var_dump(array ($myRecord, $myRecord2));
     		    echo "<hr>";
     		}
     	}
    }

}
?>