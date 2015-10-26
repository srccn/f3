<?php

class BOKFRatecalculator extends AbstractRateCalculatorController {
	public function __construct() {
		parent::__construct("BOKF");
	}
	
	public function getSRP() {
		return parent::getStateGroupedSRP();
	}
	
	public function getPurchaseRate() {
		Util::dump("Adjusts Details", $this->adjusts);
		Util::dump("Total adjust is " , Util::getSumValue($this->adjusts) );
		parent::getPurchaseRateType1();
	}
}

?>