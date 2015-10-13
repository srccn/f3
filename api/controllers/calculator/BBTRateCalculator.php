<?php

class BBTRateCalculator extends AbstractRateCalculatorController {
	
	
	public function __construct() {
		parent::__construct("BBT");
	}
	
	public function getSRP() {
		return parent::getStateGroupedSRP();
	}
	
	public function getPurchaseRate() {
		$this->getSuperConfirmingAdj();
		var_dump($this->adjusts);
		echo "Total adjust is " . Util::getSumValue($this->adjusts) . "<br>";
		$this->getPurchaseRateType1();
	}
	
}

?>