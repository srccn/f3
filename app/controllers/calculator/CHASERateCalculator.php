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
		
		$this->adjusts += $this->addChasespecificAdjusts_1();
		
		Util::dump("Adjusts Details", $this->adjusts);
		Util::dump("Total adjust is " , Util::getSumValue($this->adjusts) );
		return parent::getPurchaseRateType1();
	}
	
	public function addChasespecificAdjusts_1 () { //> 15 year, 1 unit, cltv <= 80%
		$adjustName = "> 15 Year Purchase/Rate Term Refis, 1 Unit Only. Max CLTV 80%.";
		$returnval = 0;
		//qualification check
		if ( ! ($this->property->loanTerm > 15) ) {
			return Array($adjustName => $returnval );
		}
		
		if ($this->property->purchaseType != LoanerConst::PURCHASE && $this->property->purchaseType != LoanerConst::REFINANCE ) {
			return Array($adjustName => $returnval );
		}
		
		
		if ($this->property->numberUnit != LoanerConst::ONE_UNIT || $this->property->type != LoanerConst::HOUSE ) {
			return Array($adjustName => $returnval );
		}
		
		if ($this->property->LTV > 0.80 ) {
			return Array($adjustName => $returnval );
		}
		
        //when passed checks , table look up by LTV and FICO		
		$LTV = $this->property->LTV;
		$cc_value = $this->property->creditScore;
		$query = "
				SELECT adjust as result
				FROM chase_adj_ltv_cc_1
				WHERE ltv_value <= $LTV * 100
				  AND cc_value < $cc_value
			 ORDER BY ltv_value desc, cc_value desc
				LIMIT 1 ;
				";
		$result = $this->runQuery($query);
		$returnval = floatval(Util::resultString($result));
		
		return  Array($adjustName => $returnval );
	}
	
}

?>