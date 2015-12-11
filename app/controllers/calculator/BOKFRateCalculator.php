<?php

class BOKFRatecalculator extends AbstractRateCalculatorController {
	public function __construct() {
		parent::__construct("BOKF");
	}
	
	public function getSRP() {
		return parent::getStateGroupedSRP();
	}
	
	public function getPurchaseRate() {
		parent::calculateAllAdjusts();
		Util::dump("Adjusts Details", $this->adjusts);
		Util::dump("Total adjust is " , Util::getSumValue($this->adjusts) );
		return parent::getPurchaseRateType1();
	}
}

?>