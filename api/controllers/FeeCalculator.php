<?php

class FeeCalculator extends BaseController {

	private $property ;
	private $fees;

	function __construct(LoanProperty $property){
		parent::__construct();
		$this->property = $property;
	}
	
	function getRecordingFee() {
		//input state, purchase or refinance, lookup default 350, 65.
		$feeName = "RecordingFee";
		$returnVal = null ;
		$state = $this->property->state;
		$purchaserType = $this->property->purchaseType;
		$result = $this->runQuery("select $purchaserType as result from fee_recording where state='$state'");
		if ( ! $result ) {
			$returnVal = 350;
		} else {
			//var_dump($result);
			$returnVal = intval ( Util::resultString($result) );
		}
		$this->fees[$feeName] = $returnVal;
		return $returnVal;
	}
	
	function getRecordingOtherFee(){
		//input state, purchase or refinance, lookup default 350, 65.
		$feeName = "RecordingOtherFee";
		$returnVal = null;
	
		$state = $this->property->state;
		$purchaserType = $this->property->purchaseType;
		$result = $this->runQuery("select $purchaserType as result from fee_recording_other where state='$state'");
		if ( ! $result ) {
			$returnVal =  65;
		} else {
			//var_dump($result);
			$returnVal = intval(  Util::resultString($result) );
		}
		$this->fees[$feeName] = $returnVal;
		return $returnVal;
	}

	function getAttoneyFee(){
		$feeName = "AttoneyFee";
		$returnVal = null;
	
		$state = $this->property->state;
		$purchaserType = $this->property->purchaseType;
		$result = $this->runQuery("select $purchaserType as result from fee_attorney where state='$state'");
		if ( ! $result ) {
			$returnVal =  490 * 2; //double default for unlisted state
		} else {
			$returnVal = intval( Util::resultString($result) );
		}
		$this->fees[$feeName] = $returnVal;
		return $returnVal;
	}
	
	function getAppraisalFee() {
		$feeName = "AppraisalFee";
		$returnVal = null;
	
		$state = $this->property->state;
		
		$fee=0;
		switch ($this->property->numberUnit) {
			case LoanerConst::CONDO :
				$fee=350;
				break;
			case LoanerConst::ONE_UNIT :
				$fee=350;
				break;
			case LoanerConst::TWO_UNIT :
				$fee=525;
				break;
			case LoanerConst::THREE_UNIT :
				$fee=525;
				break;
			case LoanerConst::FOUR_UNIT :
				$fee=525;
				break;
			default: // unrecognized unit type, use defualt assumption value 375
				$fee=375;
		}
		if ($this->property->loanAmount > 1000000) {
			$fee=525;
		}
		if ($state != "MA") {
			$fee +=125;
		}
		$returnVal = $fee;
		$this->fees[$feeName] = $returnVal;
		return $returnVal;
	}	

	function getLenderInsuranceFee() {
		$feeName = "LenderInsuranceFee";
		$returnVal = 0;
	
		if ($this->property->purchaseType == LoanerConst::REFINANCE || 
			$this->property->purchaseType == LoanerConst::COREFINANCE ) {
			$returnVal = round($this->property->loanAmount * 0.15/100 ,0);
		}
		if ($this->property->purchaseType == LoanerConst::PURCHASE) {
			$returnVal = round($this->property->loanAmount * 0.25/100, 0 );
		}
	
		$this->fees[$feeName] = $returnVal;
		return $returnVal;
	}

	function getTitleInsuranceFee(){
		$feeName = "TitleInsuranceFee";
		$returnVal = 0;
	    if ($this->property->purchaseType == LoanerConst::REFINANCE || 
			$this->property->purchaseType == LoanerConst::COREFINANCE ) {
				echo "here2 <br>";
				$returnVal = 0;
		}
		if ($this->property->purchaseType == LoanerConst::PURCHASE) {
			$lenderInsuranceFee = $this->getLenderInsuranceFee();
			$returnVal = round($this->property->marketPrice * 0.4/100 + 175 - $lenderInsuranceFee, 0 );
		}
		$this->fees[$feeName] = $returnVal;
		return $returnVal;
	}

	function getTotalFees() {
		Util::dump("Appraisal fee",       $this->getAppraisalFee());
		Util::dump("Lender Insurance fee",$this->getLenderInsuranceFee());
		Util::dump("Title Insurance fee" ,$this->getTitleInsuranceFee());
		Util::dump("Recording fee",       $this->getRecordingFee());
		Util::dump("Recording other fee", $this->getRecordingOtherFee());
		Util::dump("Attorney fee",        $this->getAttoneyFee());
		var_dump($this->fees);
		echo "Total Fee is " . Util::getSumValue($this->fees) . "<hr>";
		return 	intVal (Util::getSumValue($this->fees)) ;	
	}
	
}

?>