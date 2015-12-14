<?php
class CHASERateCalculator extends AbstractRateCalculatorController {

	public function __construct() {
		parent::__construct("CHASE");
	}

	public function getSRP() {
		$state = $this->property->state;
		$loanAmount = $this->property->loanAmount;
		$query = "";
	
		$loanTypeId = $this->getSRPLoanTypeId();//$this->property->loanTypeId ;//getLoanTypeId();
		
		//convert non confirming id to base id
		if ($loanTypeId == 13 ) $loanTypeId = 1; //fixed30
		if ($loanTypeId == 15 ) $loanTypeId = 3; //fixed15
		if ($loanTypeId == 20 ) $loanTypeId = 8; //arm51
		if ($loanTypeId == 21 ) $loanTypeId = 9; //arm71
		
		$query = "SELECT SRP  as result
		    FROM  state_srp_full_list
		    WHERE  convert(start_amount, UNSIGNED) <= $loanAmount
		    AND  convert(end_amount, UNSIGNED) >= $loanAmount
			AND  loan_type_id = $loanTypeId
			AND  purchaser_id = $this->purchaserId
			AND  state = '$state'
			";
		
		$result = $this->runQuery($query);
		//var_dump($result) ;
		return $result[0]['result'];
	}

	public function getPurchaseRate() {
		parent::calculateAllAdjusts();
		Util::dump("Adjusts Details", $this->adjusts);
		Util::dump("Total adjust is " , Util::getSumValue($this->adjusts) );
		return parent::getPurchaseRateType1();
	}
}

?>