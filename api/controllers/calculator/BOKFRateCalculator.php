<?php

class BOKFRatecalculator extends AbstractRateCalculatorController {
	public function __construct() {
		parent::__construct("BOKF");
	}
	
	public function getSRP() {
		return parent::getStateGroupedSRP();
	}
	
	public function getPurchaseRate() {
		var_dump($this->adjusts);
		echo "Total adjust is " . Util::getSumValue($this->adjusts) . "<br>";
		parent::getPurchaseRateType1();
	}
}

?>