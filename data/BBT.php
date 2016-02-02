<?php

class BBT extends BasePurchaser {
	
	protected $purchaserName="BBT";
	protected $purchaserId = 1;
//	protected $excelFile = "data/BBT.xlsx";
	protected $lockdays = [15, 30, 45, 60] ;
	protected $superConfirmingCalculateMethod = LoanerConst::ADJUST;
	
    //30fixed
    private $fixed30 = array (
    	"sheetName" => "CA Rate Sheet",	
    	"lock_days" => 	[15, 30, 45, 60], 
    	"loan_type" => 1,	
        "range" => "A17:E36"
    );

    //20fixed
    private $fixed20 = array (
    	"sheetName" => "CA Rate Sheet",
    	"lock_days" => 	[15, 30, 45, 60], 
    	"loan_type" => 2,
        "range" => "F17:J36"
    );

    //15fixed
    private $fixed15 = array (
    		"sheetName" => "CA Rate Sheet",
    	"lock_days" => 	[15, 30, 45, 60], 
    	"loan_type" => 3,
    	"range" => "K17:O36"
    );
    
    //10fixed
    private $fixed10 = array (
    		"sheetName" => "CA Rate Sheet",
    	"lock_days" => 	[15, 30, 45, 60], 
    	"loan_type" => 4,
    	"range" => "A40:E59"
    );
    
    //ncfixed30
    private $ncfixed30 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[15, 30, 45, 60],
    		"loan_type" => 13,
    		"range" => "F64:J83"
    );

    //ncfixed15
    private $ncfixed15 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[15, 30, 45, 60],
    		"loan_type" => 15,
    		"range" => "K64:O83"
    );

    //arm31
    private $arm31 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 7,
    		"range" => "A112:C131"
    );
    
    //arm51
    private $arm51 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 8,
    		"range" => "D112:F131"
    );
    
    //arm71
    private $arm71 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 9,
    		"range" => "G112:I131"
    );

    //arm101
    private $arm101 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 10,
    		"range" => "J112:L131"
    );
    
    //ncarm31
    private $ncarm31 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 19,
    		"range" => "A135:C154"
    );

    //ncarm51
    private $ncarm51 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 20,
    		"range" => "D135:F154"
    );

    //ncarm71
    private $ncarm71 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 21,
    		"range" => "G135:I154"
    );

    //ncarm101
    private $ncarm101 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 22,
    		"range" => "J135:L154"
    );

    
    public function __construct(){
    	setRateSheetFile("data/BBT.xlsx");
    }
    
    public function isConfirmingEligible(PropertyController $property) { //take property argument return if eligible
    	$minCreditScore[LoanerConst::PURCHASE] = 680;
    	$minCreditScore[LoanerConst::REFINANCE] = 680;
    	$minCreditScore[LoanerConst::COREFINANCE] = 680;
    	$maxLtv[LoanerConst::PURCHASE]  = 95;
    	$maxLtv[LoanerConst::REFINANCE] = 80;
    	
    	if ($property->$creditScroe < 680) {
    		return false;
    	}
    	
    	if (! $property->isConfirming) { //this check is only for confirming or super confirming
    		return true;
    	}
    	
    	if ($propert->$creditScroe < 680) {
    		return false;
    	} else {
    		if ($property->purchaseType === LoanerConst::PURCHASE) {
    			if ($property->LTV <= $maxLtv[LoanerConst::PURCHASE] ) {
    				return true;
    			} else {
    				return false;
    			}
    		}
    		
    		if (    $property->purchaseType === LoanerConst::REFINANCE || 
    				$property->purchaseType === LoanerConst::COREFINANCE ) 
    		{
    			if ($property->LTV <= $maxLtv[LoanerConst::REFINANCE] ) {
    				return true;
    			} else {
    				return false;
    			}
    		}
    		echo "Error - undefined purchase type {$property->purchaseType} <br>" ;
    		return false;
    	}
    }
    
    public function getMap() {
	    return array(
            "fixed30" => $this->fixed30, 
	        "fixed20" => $this->fixed20,			
	        "fixed15" => $this->fixed15,			
   	        "fixed10" => $this->fixed10,			
            "ncfixed30" => $this->ncfixed30, 
	        "ncfixed15" => $this->ncfixed15, 
	        "arm31" => $this->arm31, 
	        "arm51" => $this->arm51, 
	    	"arm71" => $this->arm71, 
	    	"arm101" => $this->arm101, 
	        "ncarm31" => $this->ncarm31, 
	        "ncarm51" => $this->ncarm51, 
	    	"ncarm71" => $this->ncarm71, 
	    	"ncarm101" => $this->ncarm101 
	    );
    }

}
?>