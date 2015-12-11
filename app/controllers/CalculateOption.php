<?php
class CalculateOption {
	private $loanName;
	private $purchaser;
	private $loanAmount;
	
	public function __construct ($loanName_in, $purchaser_in, $loanAmount_in) {
		$this->loanName = $loanName_in;
		$this->purchaser = $purchaser_in;
		$this->loanAmount = $loanAmount_in;
	}
	
	
	
}