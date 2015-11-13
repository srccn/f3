<?php
class CHASERateCalculator extends AbstractRateCalculatorController {

	public function __construct() {
		parent::__construct("CHASE");
	}

	public function getSRP() {
		return parent::getStateFullListSRP();
	}

	public function getPurchaseRate() {
		Util::dump("Adjusts Details", $this->adjusts);
		Util::dump("Total adjust is " , Util::getSumValue($this->adjusts) );
		return parent::getPurchaseRateType1();
	}
}

?>