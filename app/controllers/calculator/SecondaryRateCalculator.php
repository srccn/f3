<?php
class SecondaryRateCalculator {

	private $secondaryLoanAmount;
	private $loanYearTerm;
	
	function __construct($loanAmount_in, $loanTerm_in){
		$this->secondaryLoanAmount = $loanAmount_in;
		$this->loanYearTerm = $loanTerm_in;
	}
	
	function setTerm($term) {
		$this->loanYearTerm = $term;
	}
	
	function setLoanAmount($amount){
		$this->secondaryLoanAmount = $amount;
	}
	
	function getSecondaryRate(){
		$rate = 4.49;
		$secondaryLoanAmount = $this->secondaryLoanAmount;
		$yearTerm = $this->loanYearTerm;
		
		Util::dump( "Secondary loan $secondaryLoanAmount at hard coded Rate 4.49 % ");
		return array (
				"purchaser" => "PartiotsBank",
				"rate" => $rate ,
				"credit" => 0,
				"localDays" => 45,
				"monthlyPayment" => Util::calMonthlyPayment($secondaryLoanAmount, $rate, $yearTerm)
		);		
	}
	
}