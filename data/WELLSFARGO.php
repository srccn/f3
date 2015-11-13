<?php

class WELLSFARGO extends BasePurchaser {
	
	protected $purchaserName="WELLSFARGO";
	protected $purchaserId = 3;
	protected $excelFile = "data/wells fargo.xls";
	protected $lockdays = [15, 30, 45, 60] ;
    protected $baseLockDays = 60;
    protected $superConfirmingCalculateMethod = LoanerConst::LOOKUP;
	
    //30fixed
    private $fixed30 = array (
    	"sheetName" => "Conf Pricing",	
    	"lock_days" => 	[30, 60], 
    	"loan_type" => 1,	
        "range" => "B24:D41"
    );

    //20fixed
    private $fixed20 = array (
    	"sheetName" => "Conf Pricing",
    	"lock_days" => 	[30, 60], 
    	"loan_type" => 2,
        "range" => "E24:G39"
    );

    //15fixed
    private $fixed15 = array (
    		"sheetName" => "Conf Pricing",
    	"lock_days" => 	[30, 60],
    	"loan_type" => 3,
    	"range" => "H24:J38"
    );
    
    //10fixed
    private $fixed10 = array (
    		"sheetName" => "Conf Pricing",
    	"lock_days" => 	[30, 60],
    	"loan_type" => 4,
    	"range" => "K24:M34"
    );
    
    //ncfixed30
    private $ncfixed30 = array (
    		"sheetName" => "Non-Conf Pricing",
    		"lock_days" => 	[60],
    		"loan_type" => 13,
    		"range" => "B17:C31"
    );

    //ncfixed15
    private $ncfixed15 = array (
    		"sheetName" => "Non-Conf Pricing",
    		"lock_days" => 	[60],
    		"loan_type" => 15,
    		"range" => "F17:G32"
    );

    //arm51
    private $arm51 = array (
    		"sheetName" => "Conf Pricing",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 8,
    		"range" => "E68:G84"
    );
    
    //arm71
    private $arm71 = array (
    		"sheetName" => "Conf Pricing",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 9,
    		"range" => "H68:J83"
    );

    //arm101
    private $arm101 = array (
    		"sheetName" => "Conf Pricing",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 10,
    		"range" => "K68:M81"
    );
    
    //ncarm51
    private $ncarm51 = array (
    		"sheetName" => "Non-Conf Pricing",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 20,
    		"range" => "K17:L32"
    );

    //ncarm71
    private $ncarm71 = array (
    		"sheetName" => "Non-Conf Pricing",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 21,
    		"range" => "M17:N32"
    );

    //ncarm101
    private $ncarm101 = array (
    		"sheetName" => "Non-Conf Pricing",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 22,
    		"range" => "O17:P32"
    );
    
    private $purchaseLockDaysConfAdj45 = array (
    		"sheetName" => "Conf Pricing",
    		"lock_day" => 	45,
		    "confirming" => 1,
    		//"loan_type"    => ["fixed30", "fixed20", "fixed15", "fixedRelo", "arm51", "arm71","amr101" ],
    		"loan_type_id" => [1, 2, 3, -1 , 8, 9, 10 ],
    		"range" => "F53:F59"
    );
    
    private $purchaseLockDaysNoneConfAdj45 = array (
    		"sheetName" => "Non-Conf Pricing",
    		"lock_day" => 	45,
		    "confirming" => 0,
    		//"loan_type"    => ["fixed30", "fixed15", "arm51", "arm71","amr101" ],
    		"loan_type_id" => [13, 15, 20, 21,22 ],
    		"range" => "P41:P45"
    );
    
    //ltv_cc_adjust
    private $adj_ltv_cc = array (
    		"sheetName" => "Conf Adjusters",
    		"ltv" => [0,60,70,75,80,85,90], //rage pick = max ( value < given_value)
    		"cc"  => [740,720,700,680,660,640,620,0] , //range pick = max (value < given_value)
    		"range" => "H76:N83" ,
    		"max_ltv" => 95
    );
    
    public function purchaseLockDayAdj45($loanTypeId, $adj) {
        $query = "INSERT INTO purchase ( purchaser_id, loan_type_id, rate, lock_days_id,  purchase_price ) 
                    SELECT purchaser_id, loan_type_id, rate, 45,  purchase_price + $adj from purchase 
                    WHERE purchaser_id = 3 
                      AND lock_days_id = $this->baseLockDays
				      AND loan_type_id = $loanTypeId
                  " ;
        return $query;
    }
    
    public function getMap() {
	    return array(
            "fixed30" => $this->fixed30, 
	        "fixed20" => $this->fixed20,			
	        "fixed15" => $this->fixed15,			
   	        "fixed10" => $this->fixed10,			
            "ncfixed30" => $this->ncfixed30, 
	        "ncfixed15" => $this->ncfixed15, 
	        //"arm31" => $this->arm31, 
	        "arm51" => $this->arm51, 
	    	"arm71" => $this->arm71, 
	    	"arm101" => $this->arm101, 
	        //"ncarm31" => $this->ncarm31, 
	        "ncarm51" => $this->ncarm51, 
	    	"ncarm71" => $this->ncarm71, 
	    	"ncarm101" => $this->ncarm101 
	    );
    }

	public function getPriceAdjMap(){
		return array (
            "Conf45" => $this->purchaseLockDaysConfAdj45,
            "NoneConf45" => $this->purchaseLockDaysNoneConfAdj45
		);
	}
	
	public function getAdjLtvCcMap(){
		return array (
				"adj_ltv_cc" => $this->adj_ltv_cc
		);
	}
	
	public function getStateListSRPMap () {
		$selectedStates=["AK", "MA", "NH", "RI", "CT", "VT", "NY"];
		$common_range =[["minimum","99999"],["100000","139999"],["140000","179999"],
				       ["180000","239999"],["240000","299999"],["300000","confirming"], ["confirming","maximum"]];

		$srpFixed30 = array (
				"sheetName" => "Conv Full Grid" ,
				"loan_type_id" => 1,
				"escrow" => 0,
				"stateCol" =>"A7:A57",
				"range" => "B7:H57", 
		        "amountAdjRange" => $common_range,
				"amountAdj"=>""
		);
		$srpFixed15 = array  (
				"sheetName" => "Conv Full Grid" ,
				"loan_type_id" => 3,
				"escrow" => 0,
				"stateCol" =>"A136:A186",
				"range" => "B136:H186",
		        "amountAdjRange" => $common_range,
				"amountAdj"=>""
								
		);
		$srpArm51   = array ( 
				"sheetName" => "Conv Full Grid" ,
				"loan_type_id" => 8,
				"escrow" => 0,
				"stateCol" =>"A264:A314",
				"range" =>"B264:H314",
		        "amountAdjRange" => $common_range,
				"amountAdj"=>""
		);
		$srpArm71   = array ( 
			    "sheetName" => "Conv Full Grid" ,
				"loan_type_id" => 9,
				"escrow" => 0,
				"stateCol" =>"A328:A378",
				"range"=>"B328:H378",
		        "amountAdjRange" => $common_range,
				"amountAdj"=>""
		);

		return array (
		    "selectedStates" => $selectedStates,
			"amountRange" => $amountRange,		
			"amountAdj"  => $amountAdj,
			"products" => array ( 
					 "srpFixed30" => $srpFixed30,
			         "srpFixed15" => $srpFixed15,
			         "srpArm51"   => $srpArm51,
			         "srpArm71"   => $srpArm71
			)
		);
	}
}
?>