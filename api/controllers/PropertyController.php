<?php

class PropertyController extends BaseController {

	protected $adress;
	protected $state;
	protected $zip;
	protected $marketPrice;
	protected $numberUnit;
	protected $type; //condo, house
	protected $occType; //primary, commercial, investment
	protected $purchaseType; //purchase, refinance
	protected $loanAmount;
	
	function getLoanLimitByZipCode($zip, $number_unit){
	    $results = $this->db->exec("select  GetConfirmingLoanUpperLimit('$zip' , '$number_unit')");
		var_dump($results[0]);
		return $result;
	}
	
	function isConfirmingLoan(){
	
	}

	function getStateByZipCode($zip) {
	
	}
	
	function getLTV(){
	    return $this->loanAmount/$this->marcketPrice ;
	}

}

?>