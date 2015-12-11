<?php

class BBTRateCalculator extends AbstractRateCalculatorController {
	
	
	public function __construct() {
		parent::__construct("BBT");
	}
	
	public function getSRP() {
		return parent::getStateGroupedSRP();
	}
	
	public function getPurchaseRate() {
		parent::calculateAllAdjusts();
		$this->getSuperConfirmingAdj();
		Util::dump("Adjustment", $this->adjusts);
		Util::dump ("Total adjust is " , Util::getSumValue($this->adjusts) );
		return $this->getPurchaseRateType1();
	}
	
}

?>