<?php

class  PropertyController extends BaseController {

	private $inputs;
    private $property;
    private $closingOption;
    private $purchasers;
    private $loanNames;
    private $viewRecords = array();
	
	function __construct($inputForm) {
		parent::__construct();
		$this->inputs = $inputForm;
		
		$this->setPurchasers(["BBT", "BOKF", "WELLSFARGO"]);
		$this->setLoanNames([ LoanerConst::FIXED30,
     			              LoanerConst::FIXED15,
     			              LoanerConst::ARM51,
    			              LoanerConst::ARM71  
     	                    ]);
	}
	
	public function setPurchasers($array){
		$this->purchasers = $array;
	}
	
	public function setLoanNames($array){
		$this->loanNames = $array;
	}
	
    function searchRate () {
    	
    	//create LoanProperty base on inputs
    	$property = new LoanProperty($this->inputs);
    	$this->f3->set('loanProperty', $property->getShowArray());
    	$this->f3->set('propertyLabel', $property->getPropertLabel());
    	 
    	//set Closing options and time stamp
    	$this->closingOption = $property->getClosingOption();
    	$this->f3->set('ClosingOption', $this->closingOption);
    	date_default_timezone_set('EST');
    	$this->f3->set('searchStamp', date("m-d-Y g:i a"));

    	//find loan amount options
    	$myoptions = $property->loanAmountOptions;

    	//create fee calculator for property, set totalfee and fee details
     	$myFeeCalculator = new FeeCalculator($property);
     	$totalFee = $myFeeCalculator->getTotalFees();
     	$this->f3->set('totalFee', $totalFee);
     	$this->f3->set('fees', $myFeeCalculator->getFeesArray());
     	//echo "fees : " . $totalFee;
    	
     	//get all purchaser and loan names
     	$purchasers= $this->purchasers;
     	$loanNames = $this->loanNames;

     	foreach ($loanNames as $loanName) {
     		$property->loanName = $loanName;
     		$property->setLoanTypeId();
     		Util::dump( "--------" . $loanName );
     		$this->viewRecords[$loanName] = array();
     		 
     	//$purchasers=["BOKF"];
     	    foreach ($purchasers as $purchaser) {
                $myRecord = new ViewRecord;
                $myRecord->product = $property->loanName;
                $myRecord->purchaser = $purchaser;
				foreach ( $myoptions as $opt ) {
					$property_clone = clone $property;
					
					Util::dump ( "=== purchaser $purchaser, loan option = $opt[1] + $opt[2]" );
					if ($opt [2] > 0) {
						Util::dump ( "=== Primary loan", "" );
					}
					$myRecord1 = clone $myRecord;
					$myRecord1->loanAmount = $opt [1];
					
					$property_clone->loanAmount = $opt [1];
					$property_clone->calculateDerives ();
					$purchaserCalculatorName = $purchaser . "RateCalculator";
					$myRateCalculator = new $purchaserCalculatorName ();
					$myRateCalculator->setProperty ( $property_clone );
					$myRateCalculator->setTotalFee ( $totalFee );
					$myresult = $myRateCalculator->calculteRate ();
					
					$this->setResultRecord($myRecord1, $myresult);
					
					$myRecord2 = null;
					if ($opt [2] > 0) {
						Util::dump ( "=== Secondary loan" );
					    $myRecord2 = clone $myRecord;
						$myresult2 = $myRateCalculator->calculteSecondaryRate ($opt [2]);
						
						$myRecord2->loanAmount = $opt [2];
						$this->setResultRecord($myRecord2, $myresult2) ;
					}
					$resultRecord = array (
							"part1" => $myRecord1,
							"part2" => $myRecord2 
					);
					// var_dump ($resultRecord );
					array_push ( $this->viewRecords[$loanName], $resultRecord );
				} //option
         	}//purchaser
			$r = usort($this->viewRecords[$loanName], 'Util::cmp');
			Util::dump("Calculate result for $loanName", $this->viewRecords[$loanName] );
     	} //loanName
     	//echo json_encode($this->viewRecords)."<br>";
     	$this->f3->set('SearchResults', $this->viewRecords);
     	date_default_timezone_set('EST');
     	$this->f3->set('searchStamp', date("m-d-Y g:i a"));
    }
    
    function setResultRecord (ViewRecord $record ,  array $arr) { //$record will be modified
    	
    	$record->purchaser = $arr ['purchaser'];
    	$record->rate = $arr ['rate'];
    	$record->credit = $arr ['credit'];
    	$record->lockDays = $arr ['lockDays'];
    	$record->margin = $arr ['margin'];
    	$record->minCredit = $arr ['minCredit'];    	
    }

}
?>