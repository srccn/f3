<?php

class InputForm {
	
	//take from inouts
	public $adress; //optional
	public $zip;
	public $marketPrice;
	public $numberUnit;
	public $type; //condo, house
	public $occType; //primary, commercial, investment
	public $purchaseType; //purchase, refinance, cash out refinance
	public $loanAmount;
	public $lockDays;
	public $creditScore;
	public $loanName; //fixed30, fixed15, arm51, arm71
	public $confirmingmargin;
	public $jumbogmargin;
	public $mincredit;
	public $closingOption;	
	
	function getSampleForm() {
		return array (
			"type"          => "house",	//house or condo			
			"zip"          => "02460",				
			"marketPrice"  => "300000",
			"numberUnit"   => LoanerConst::ONE_UNIT,
		    "purchaseType" => LoanerConst::PURCHASE,
			"occType"	   => LoanerConst::PRIMARY_HOME,
			"loanAmount"   => "240000",
			"lockDays"	   => "45",
			"creditScore"  => "740",
			"loanName"	   => LoanerConst::FIXED30,
			"confirmingmargin"	=> "1.0",
			"jumbomargin"		=> "0.5",
			"mincredit"	   => "0",
			"closingOption"	=> LoanerConst::CLOSING_OPTION_NOPOINT_NOCLOSINGCOST 
		);
	}
    
	function setInputForm(array $a) {
		foreach( $a as $key =>$value ) {
			$this->$key = $value;
		}
	}

	function toArray() {
		return array (
				"zip"           => $this->zip,
				"marketPrice"   => $this->marketPrice,
				"numberUnit"    => $this->numberUnit,
				"type"		    => $this->type,
				"occType"	    => $this->occType,
				"loanAmount"    => $this->loanAmount,
				"lockDays"	    => $this->lockDays,
				"creditScore"   => $this->creditScore,
				"confirmingmargin"	=> $this->confirmingmargin,
				"loanName"	    => $this->loanName,
				"jumbogmargin"		=> $this->jumbogmargin,
				"mincredit"	    => $this->mincredit,
				"closingOption"	=> $this->closingOption
		);		
	}
	
	
}