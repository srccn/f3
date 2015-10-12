<?php

class WELLSFARGORateCalculator extends AbstractRateCalculatorController {

	public function __construct() {
		parent::__construct("WELLSFARGO");
	}
	
	public function getSRP() {
		return parent::getStateFullListSRP();
	}
	
	public function getPurchaseRate() {
		var_dump($this->adjusts);
		echo "Total adjust is " . Util::getSumValue($this->adjusts) . "<br>";
		parent::getPurchaseRateType1();
	}
}

?>