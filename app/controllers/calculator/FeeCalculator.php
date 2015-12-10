<?php

class FeeCalculator extends BaseController {

	private $property ;
	private $fees;
	private $amountRelatedFees;
	
	function __construct(LoanProperty $property){
		parent::__construct();
		$this->property = clone $property;
		$this->setFixedFees();
	}
	
	public function setFixedFees () {
		$this->fees = array(
				"804 credit_report" => 18.00 ,
				"flood_certification" => 15.00 ,
				"801 Origination" => 850.00 ,
				"TaxService" => 87.00 ,
				"HomeRegistration" => 85.00 ,
		);
	}
	
	function getOptionsFee(){
		$feeName = "SecondLoanCharge";
		$returnVal = array();
		
		$options = $this->property->loanAmountOptions ;
		foreach ( $options as $opt ) {
			$feeVal = array();
			//first loan cost
			$feeVal[0] = $this->getTotalFees($opt[1]);
			
			//second loan cost if any
			$feeVal[1] = $opt[2] ? 525 + $option[2] * 0.0025 : 0;
			
			//total cost
			$feeVal[2] = $feeVal[0] + $feeVal[1];
			
			//cost ddetails
			$feeDetails = $this->fees;
			$feeDetails['SecondLoanInitiationFee'] = $feeVal[1];
			$feeDetails_json = json_encode($feeDetails);
			
			$feeVal[3] = $feeDetails_json;
				
			$returnVal[$opt[0]] = $feeVal;
		}
		return $returnVal;
	}
	
	function getRecordingFee() {
		//input state, purchase or refinance, lookup default 350, 65.
		$feeName = "1201 RecordingFee";
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
		$feeName = "1202 RecordingOtherFee";
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
		$feeName = "1107 AttoneyFee";
		$returnVal = null;
	
		$state = $this->property->state;
		$purchaserType = $this->property->purchaseType;
		$query = "select $purchaserType as result from fee_attorney where state='$state'";
		$result = $this->runQuery($query);
		if ( ! $result ) {
			$returnVal =  490 * 2; //double default for unlisted state
		} else {
			$returnVal = intval( Util::resultString($result) );
		}
		$this->fees[$feeName] = $returnVal;
		return $returnVal;
	}
	
	function getAppraisalFee() {
		$feeName = "803 AppraisalFee";
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
		$feeName = "806 LenderInsuranceFee";
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
		$feeName = "1108 TitleInsuranceFee";
		$returnVal = 0;
	    if ($this->property->purchaseType == LoanerConst::REFINANCE || 
			$this->property->purchaseType == LoanerConst::COREFINANCE ) {
				$returnVal = 0;
		}
		if ($this->property->purchaseType == LoanerConst::PURCHASE) {
			$returnVal = round($this->property->marketPrice * 0.4/100 + 175, 0 );
		}
		$this->fees[$feeName] = $returnVal;
		return $returnVal;
	}
	
	//optional 
	function getOwnerTitleInsuranceFee(){
		$feeName = "OwnerTitleInsuranceFee";
		$returnVal = 0;
		if ($this->property->purchaseType == LoanerConst::REFINANCE ||
				$this->property->purchaseType == LoanerConst::COREFINANCE ) {
					$returnVal = 0;
				}
				if ($this->property->purchaseType == LoanerConst::PURCHASE) {
					$lenderInsuranceFee = $this->getLenderInsuranceFee();
					$returnVal = round($this->property->marketPrice * 0.4/100 + 175 - $lenderInsuranceFee, 0 );
				}
				$this->fees[$feeName] = $returnVal;
				return $returnVal;
	}
	
	function getTotalFees($optionAmount=0) {
		
		$this->property->loanAmount = $optionAmount;
		
		Util::dump("Appraisal fee",       $this->getAppraisalFee());
		Util::dump("Lender Insurance fee",$this->getLenderInsuranceFee());
		Util::dump("Title Insurance fee" ,$this->getTitleInsuranceFee());
		Util::dump("Recording fee",       $this->getRecordingFee());
		Util::dump("Recording other fee", $this->getRecordingOtherFee());
		Util::dump("Attorney fee",        $this->getAttoneyFee());
		Util::dump("Total Fee is " ,      Util::getSumValue($this->fees) );
		return 	intVal (Util::getSumValue($this->fees)) ;	
	}
	
	public function getFeesArray() {
		return $this->fees;
	}
	
}

?>