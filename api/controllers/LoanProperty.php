<?php

class LoanProperty extends BaseController {

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
	public $creditScroe;
	public $loanName; //fixed30, fixed15, arm51, arm71
	public $confirmingmargin;
	public $jumbogmargin;
	public $mincredit;
	
	//calculated value
	public $state; //optional
	public $LTV;
	public $confirmingUpperLimit;
	public $isConfirming;
	public $loanTypeId;
	public $loanLimitCheck;
	public $margin;
	public $loanAmountOptions = [];
	//	protected $isSupperConfirming;
	
	//variables hold calculation
	//protected $fees;     //hold fees
	//protected $adjusts;  //hold adjusts
	
	public function __construct($inputs) {
		parent::__construct();
		$this->setInput($inputs);
	}
	
	function setInput( $inputs ){
	    
		//taken from inouts
		$this->numberUnit = $inputs["numberUnit"];
		$this->type=$inputs["type"];
		$this->occType=$inputs["occType"];
		$this->purchaseType=$inputs["purchaseType"];
		$this->loanAmount=intVal ($inputs["loanAmount"]);
		$this->zip=$inputs["zip"];
		$this->marketPrice=intVal ($inputs["marketPrice"]);
		$this->creditScore=intVal ($inputs["creditScore"]);
		$this->loanName=$inputs["loanName"];
		$this->lockDays=$inputs["lockDays"];
		$this->confirmingmargin=$inputs["confirmingmargin"];
		$this->jumbomargin=$inputs["jumbomargin"];
		$this->mincredit=$inputs["mincredit"];
		
		$this->calculateDerives();
		
		//calculate derived value base on inputs.
// 		$this->setLTV();
// 		$this->setState();
// 		$this->setLoanLimitByZipCode();
// 		$this->setIsConfirming();
// 		$this->setLoanTypeId();
// 		$this->setMargin();
// 		$this->loanLimitCheck = $this->loanLimitCheck();
// 		$this->loanAmountOptions = $this->calculateLoanOptions();
	}	
	
	private function setLTV(){
		$this->LTV = round($this->loanAmount/$this->marketPrice, 2) ;
		return  $this->LTV ;
	}
	
	private function setState() {
		$state = $this->runQuery("select t1.state as result from county_loan_limit t1, zip_county t2 where t1.countycode = t2.countycode and t2.zipcode='$this->zip';");
		$this->state = Util::resultString($state);
	}
	
	function getState () {
		return $this->state;
	}

	private function setLoanLimitByZipCode(){
		$results = $this->runQuery("select GetConfirmingLoanUpperLimit('$this->zip' , '$this->numberUnit') as result ");
		//var_dump($results[0]);
		$this->confirmingUpperLimit = intVal( Util::resultString($results));
		//return $this->confirmingUpperLimit;
	}
	
	private function setIsConfirming(){
		$upperLimit = $this->confirmingUpperLimit;
		if ($this->loanAmount > $upperLimit) {
			$this->isConfirming = 0;
		} else {
			$this->isConfirming = 1;
			if ($this->loanAmount > LoanerConst::CONFIRMING_LIMIT_AMOUNT) {
				$this->isConfirming = 2; //2 represent supperconfirming
			}
		}
		return $this->isConfirming;
	}
	
	private function setLoanTypeId(){
		$result = $this->runQuery("
				select loan_type_id as result
				from loan_type
				where type_variable_name like '%$this->loanName%'
				and confirming = $this->isConfirming "
		);
	
		if ( !$result ) die("Failed to find loan type : " . $this->loanName);
	    $this->loanTypeId = intval (Util::resultString($result)) ;
	    return $this->loanTypeId;
	}	
	
	private function setMargin() {
		switch ($this->isConfirming) {
			case 0 :
			    $this->margin = $this->jumbomargin ;
			    break;
			case 1 :
			    $this->margin = $this->confirmingmargin ;
			    break;
			case 2 :
			   	$this->margin = $this->confirmingmargin ;
			   	break;
			default : 
				$this->margin = $this->confirmingmargin ;
		}
	}
	
	function loanLimitCheck(){
		//Amount checks
		if ($this->loanAmount > LoanerConst::MAXIMUM_LIMIT_AMOUNT) {
			echo "Loan Amount is greater than limit no SRP value. <br>";
			return false;
		}elseif ($this->loanAmount < LoanerConst::MIMIMUM_LIMIT_AMOUNT) {
			echo "Loan Amount is less than mimimum limit no SRP value. <br>";
			return false;
		}
	
		//Credit Score and LTV checks
		if ($this->purchaseType == LoanerConst::PURCHASE ) {
			if ($this->creditScore < LoanerConst::MIMIMUM_CREDIT_SCORE_PURCHASE) {
				echo "Credit score does not meet mimimun for purchase. <br>";
				return false;
			}
			if ($this->LTV * 100 > LoanerConst::MAXMUM_LTV_PURCHASE) {
				echo "Failed LTV check for purchase. <br>";
				return false;
			}
		}
		if ($this->purchaseType == LoanerConst::REFINANCE) {
			if ($this->creditScore < LoanerConst::MIMIMUM_CREDIT_SCORE_REFINANCE) {
				echo "Credit score does not meet mimimun for refinance. <br>";
				return false;
			}
			if ($this->LTV * 100 > LoanerConst::MAXMUM_LTV_REFINANCE) {
				echo "Failed LTV check for refinance. <br>";
				return false;
			}
		}
		if ($this->purchaseType == LoanerConst::COREFINANCE ) {
			if ($this->creditScore < LoanerConst::MIMIMUM_CREDIT_SCORE_COREFINANCE) {
				echo "Credit score does not meet mimimun for cash out refinance. <br>";
				return false;
			}
			if ($this->LTV * 100 > LoanerConst::MAXMUM_LTV_COREFINANCE) {
				echo "Failed LTV check for cahs out purchase. <br>";
				return false;
			}
		}
		//passed all checks
		return true;
	}
	
	private function calculateLoanOptions () {

		$options = [];
		
		//by default user always have option to mortgage all loanAmount
		$option = [ $this->isConfirming , $this->loanAmount, 0];
		array_push($options, $option);
		 
		switch ( $this->isConfirming ) {
			case 0:
				$option = [ 1 , 416999, $this->loanAmount - 416999 ];
				array_push($options, $option);
				if ( $this->confirmingUpperLimit > 417000 ) {
					$option = [ 2, $this->confirmingUpperLimit -1 ,
							$this->loanAmount - $this->confirmingUpperLimit + 1  ];
					array_push($options, $option);
				}
				break;
			case 2 :
				$option = [ 1, 417000, $this->loanAmount - 417000 ];
				array_push($options, $option);
				break;
			default :
				// do nothing keep 	$this->loanAmountOptions empty
		}
		//$this->loanAmountOptions = options;
		return $options;
	}
	
	function calculateDerives() {
		$this->setLTV();
		$this->setState();
		$this->setLoanLimitByZipCode();
		$this->setIsConfirming();
		$this->setLoanTypeId();
		$this->setMargin();
		$this->loanLimitCheck = $this->loanLimitCheck();
		$this->loanAmountOptions = $this->calculateLoanOptions();		
	}
	
	public function printProperty(){
		echo "<hr>";
		print "number of Unit is : $this->numberUnit<br>";
		echo "property type is : $this->type<br>";
		echo "Occupancy is $this->occType<br>";
		echo "Purchase od refinance is : $this->purchaseType<br>";
		echo "Loan amount is : $this->loanAmount<br>";
		echo "Property zip code is : $this->zip<br>";
		echo "Property marcket price is : $this->marketPrice<br>";
		echo "Credit Score is : $this->creditScore<br>";
		echo "loanName : $this->loanName<br>";
		echo "lockDays : $this->lockDays<br>";
		echo "confirming Margin : $this->confirmingmargin<br>";
		echo "Jumbo Margin : $this->jumbomargin <br>";
		echo "------ derived values ------ <br>";
		echo "State is : $this->state<br>";
		echo "LTV is : $this->LTV<br>";
		echo "Confirming Upper limit : $this->confirmingUpperLimit<br>";
		echo "is Confirming : $this->isConfirming<br>";
		echo "Loan limit check passed : $this->loanLimitCheck <br>";
		echo "Margin for calculation : $this->margin <br>";
		echo "Minimum Credit : $this->mincredit <br>";
		print_r ($this->loanAmountOptions);
		echo "<hr>";
	
	}	
	
	private function setTest () {
		$this->numberUnit='two_unit';
		$this->type='house';
		$this->occType='primary';
		$this->purchaseType='purchase';
		$this->loanAmount=700000;
		$this->zip='02460';
		$this->marketPrice=800000;
		$this->creditScore=780;
		$this->loanName="fixed30";
		$this->state = $this->getState();
	
	}
}

?>