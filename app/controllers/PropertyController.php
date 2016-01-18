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
		
		$this->setPurchasers(["BBT", "BOKF", "WELLSFARGO","CHASE"]);
//		$this->setPurchasers(["CHASE"]);
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
    	
    	//determine purchaser list
    	if (in_array("ALL", $property->purchaserSelection) ) {
    		//do ntohing take default all;
    	} else {
    		$this->setPurchasers($property->purchaserSelection);
    	}

    	//determine loanName - what kind of loan selected.
    	if (in_array("all", $property->loanNameSelection) ) {
    		//do ntohing take default all;
    	} else {
    		$this->setLoanNames($property->loanNameSelection);
    	}
    	
    	//find loan amount options
    	$myoptions = $property->loanAmountOptions;

    	//build calculation target list use loanAmount, loanName, purchaser
        $calTargetArray = Util::arrayComb(array (
        		$this->loanNames,
        		$property->loanAmountOptions,
        		$this->purchasers
        ));
    	
        //var_dump($calTargetArray);
        
     	//calculate fees for each option in an array
        $myFeeCalculator = new FeeCalculator($property);
     	$totalFeeByOptions = $myFeeCalculator->getOptionsFee();
      	$this->f3->set('feeOptions', $totalFeeByOptions);
    	
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
                $myRecord->product = $loanName;
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
					$property_clone->setLoanTypeId();
					//Util::dump ( "Loan Property : ",  $property_clone);
					$purchaserCalculatorName = $purchaser . "RateCalculator";
					$myRateCalculator = new $purchaserCalculatorName ();
					$myRateCalculator->setProperty ( $property_clone );
					//set total fee for this option 
					$myRateCalculator->setTotalFee ( $totalFeeByOptions[$opt[0]][2] );
					$myresult = $myRateCalculator->calculteRate ();
					
					if ( $myresult == null ) { //incase no valid result found, skip to next
						continue;
					}
					
					$this->setResultRecord($myRecord1, $myresult);
					
					$myRecord2 = null;
					if ($opt [2] > 0) {
						Util::dump ( "=== Secondary loan" );
					    $myRecord2 = clone $myRecord;
					    
					    $mySecondaryRateCalculator = new SecondaryRateCalculator($opt[2], $property_clone->loanTerm);
					    
						$myresult2 = $mySecondaryRateCalculator->getSecondaryRate();
						
						$myRecord2->loanAmount = $opt [2];
						$this->setResultRecord($myRecord2, $myresult2) ;
					}
					$resultRecord = array (
							"part1" => $myRecord1,
							"part2" => $myRecord2, 
							"option" => $opt[0],
							"fees" => $totalFeeByOptions[$opt[0]]
					);
					// var_dump ($resultRecord );
					array_push ( $this->viewRecords[$loanName], $resultRecord );
				} //option
         	}//purchaser
			$r = usort($this->viewRecords[$loanName], 'Util::cmp');
			$bestResult =  (Util::bestResult($this->viewRecords[$loanName]));
			$this->viewRecords[$loanName] = $bestResult;
			Util::dump("Calculate result for $loanName", $this->viewRecords[$loanName] );
     	} //loanName
     	//echo json_encode($this->viewRecords)."<br>";
     	$this->f3->set('SearchResults', $this->viewRecords);
     	//$this->f3->set('SearchResults', $bestResult);
    }
    
    function searchPrimaryRate($loanProperty, $loanName, $saveOption){
    	
    	$returnViewRecord = new ViewRecord;
    	$returnViewRecord->product = $loanName;
    	$returnViewRecord->purchaser = $purchaser;
    	$returnViewRecord->loanAmount = $optLoanAmount;
    	 
    	$property_clone = clone $loanProperty;

    	$property_clone->loanName = $loanName;
    	$property_clone->loanAmount = $optLoanAmount;
    	$property_clone->setLoanTypeId();
    	$property_clone->calculateDerives ();
    	
    	$purchaserCalculatorName = $purchaser . "RateCalculator";
    	$myRateCalculator = new $purchaserCalculatorName ();
    	$myRateCalculator->setProperty ( $property_clone ); 
    	//set fees
    	$myRateCalculator->setTotalFee ($optTotalFee);
    	//calculate rate
    	$myresult = $myRateCalculator->calculteRate ();
    	setResultRecord($returnViewRecord, $myresult);
    	
    	return $returnViewRecord;
    }
    
    function searchSecondaryRate() {
    	
    }
    
    function setResultRecord (ViewRecord $record ,  array $arr) { //$record will be modified
    	
    	$record->purchaser = $arr ['purchaser'];
    	$record->purchaserId = $arr ['purchaserId'];
    	$record->rate = sprintf ('%0.3f' , $arr ['rate']);
    	$record->price = sprintf ('%0.3f' , $arr ['price']);
    	$record->loanTypeId = $arr ['loanTypeId'];
    	$record->loanTerm = $arr ['loanTerm'];
    	$record->credit = $arr['credit'];
    	$record->lockDays = $arr['lockDays'];
    	$record->margin = $arr['margin'];
    	$record->minCredit = $arr['minCredit'];    	
    	$record->monthlyPayment = sprintf('%0.2f' , $arr['monthlyPayment']);    	
    	$record->adjusts = $arr['adjusts'];    	
    	$record->SRP = sprintf('%0.3f' , $arr['SRP']);    	
    }

}
?>