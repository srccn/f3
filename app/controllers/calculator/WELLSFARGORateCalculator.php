<?php

class WELLSFARGORateCalculator extends AbstractRateCalculatorController {

	public function __construct() {
		parent::__construct("WELLSFARGO");
	}
	
	public function getSRP() {
		return parent::getStateFullListSRP();
	}
	
	public function getPurchaseRate() {
		parent::calculateAllAdjusts();
		Util::dump("Adjusts Details", $this->adjusts);
		Util::dump("Total adjust is " , Util::getSumValue($this->adjusts) );
		return parent::getPurchaseRateType1();
	}
}

?>