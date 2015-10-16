<?php

class  PropertyController extends BaseController {

	private $inputs;
    private $property;
	
	function __construct($inputForm) {
		$this->inputs = $inputForm;
	}
	
    function test () {
    	
    	$property = new LoanProperty($this->inputs);
    	$property->printProperty();
    	$myoptions = $property->loanAmountOptions;

    	foreach ($myoptions as $opt)
    	
     	$myFeeCalculator = new FeeCalculator($property);
     	$totalFee = $myFeeCalculator->getTotalFees();
     	//echo "fees : " . $totalFee;
    	
     	$purchasers=["BBT", "BOKF", "WELLSFARGO"];
     	//$purchasers=["BOKF"];
     	foreach ($purchasers as $purchaser) {

     		foreach ($myoptions as $opt) {
     			$property_clone = clone $property; 
     		    echo "========== purchaser $purchaser, loan option = $opt[1] + $opt[2] <br>" ;
     		    echo "========== Primary loan<br>";
     		    $property_clone->loanAmount   = $opt[1];
     		    $property_clone->calculateDerives();
     		    $purchaserCalculatorName  = $purchaser."RateCalculator" ;
     		    $myRateCalculator = new $purchaserCalculatorName ;
     		    $myRateCalculator->setProperty($property_clone);
     		    $myRateCalculator->setTotalFee($totalFee);
     		    $myRateCalculator->calculteRate();
     		    echo "========== Secondary loan<br>";
     		    $myRateCalculator->calculteSecondaryRate( $opt[2] );
     		    echo "<hr>";
     		}
     	}
    }

}
?>