<?php

class  PropertyController extends BaseController {

	private $inputs;

	function __construct($inputForm) {
		$this->inputs = $inputForm;
	}
	
    function test () {
    	
    	$property = new LoanProperty($this->inputs);
    	$property->printProperty();
    	
    	$myFeeCalculator = new FeeCalculator($property);
    	$totalFee = $myFeeCalculator->getTotalFees();
    	//echo "fees : " . $totalFee;
    	
     	$purchasers=["BBT", "BOKF", "WELLSFARGO"];
     	foreach ($purchasers as $purchaser) {
     		echo "========== purchaser $purchaser <br>" ;
     		$purchaserCalculatorName  = $purchaser."RateCalculator" ;
     		$myRateCalculator = new $purchaserCalculatorName ;
     		$myRateCalculator->setProperty($property);
     		$myRateCalculator->setTotalFee($totalFee);
     		$myRateCalculator->calculteRate();

     		echo "<hr>";
     	}
       
    }

}
?>