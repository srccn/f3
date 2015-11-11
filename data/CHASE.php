<?php

class CHASE  extends BasePurchaser{
	
	protected $purchaserName="CHASE";
	protected $purchaserId = 4;
	protected $excelFile = "data/chase.xls";
	protected $lockdays = [15, 30, 45, 60, 75] ;
	protected $superConfirmingCalculateMethod = LoanerConst::LOOKUP;
	
    //30fixed
    private $fixed30 = array (
    	"sheetName" => "Agency Fixed",
    	"lock_days" => 	[15, 30, 45, 60, 75] , 
    	"loan_type" => 1,	
        "range" => "B12:L31"
    );

    //20fixed
    private $fixed20 = array (
    	"sheetName" => "Agency Fixed",
    	"lock_days" => 	[15, 30, 45, 60, 75], 
    	"loan_type" => 2,
        "range" => "B35:L54"
    );

    //15fixed
    private $fixed15 = array (
    	"sheetName" => "Agency Fixed",
    	"lock_days" => 	[15, 30, 45, 60, 75], 
    	"loan_type" => 3,
    	"range" => "O35:Y54"
    );
    
    //10fixed
    private $fixed10 = array (
    	"sheetName" => "Agency Fixed",
    	"lock_days" => 	[15, 30, 45, 60, 75], 
    	"loan_type" => 3,
    	"range" => "O35:Y54"
    );
    
	
	//arm51
    private $arm51 = array (
    		"sheetName" => "Agency ARMs",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 8,
    		"range" => "B77:L25"
    );
	//arm71
    private $arm71 = array (
    		"sheetName" => "Agency ARMs",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 9,
    		"range" => "O12:Y25"
    );
	//amr101
    private $arm101 = array (
    		"sheetName" => "Agency ARMs",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 10,
    		"range" => "B29:L42"
    );
	
	//supperarm51
    private $ncarm51 = array (
    		"sheetName" => "Agency ARMs",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 30,
    		"range" => "O29:Y42"
    );	

    //supfixed30
    private $supfixed30 = array (
    		"sheetName" => "Agency Fixed High Balance",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 23,
    		"range" => "B11:L30"
    );
    
    //supfixed15
    private $supfixed15 = array (
    		"sheetName" => "Agency Fixed High Balance",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 25,
    		"range" => "O11:Y30"
    );    
    
	//ltv_cc_adjust fixed conforming
	private $adj_ltv_cc1 = array (
    		"sheetName" => "Agency Fixed Adj",
			"rotate" => 1,
			"ltv" => [97,95,90,85,80,75,70,60,0], //rage pick = max ( value < given_value) 
			"cc"  => [0,620,640,660,680,700,720,740] , //range pick = max (value < given_value)
        	"range" => "J12:Y20"		
	);

	//ltv_cc_adjust fixed supper conforming
	private $adj_ltv_cc2 = array (
			"sheetName" => "Agency Fixed High Balance Adj",
			"rotate" => 1,
			"ltv" => [97,95,90,85,80,75,70,60,0], //rage pick = max ( value < given_value)
			"cc"  => [0,620,640,660,680,700,720,740] , //range pick = max (value < given_value)
			"range" => "J13:Y21"
	);

	//ltv_cc_adjust arm
	private $adj_ltv_cc3 = array (
			"sheetName" => "Agency ARMs Adj",
			"rotate" => 1,
			"ltv" => [97,95,90,85,80,75,70,60,0], //rage pick = max ( value < given_value)
			"cc"  => [0,620,640,660,680,700,720,740] , //range pick = max (value < given_value)
			"range" => "J13:Y21"
	);
	
	private $adj_others = array (
    		"sheetName" => "",
	        "item" => ['adjust_condo','adjust_invest','adjust_2Units','adjust_34Units','adjust_arm','adjust_highBalanceArm'],
			"value" => [0.75,3.75,1,1,0,0] ,
			"max_ltv" => 95
	);
	
    public function getMap() {
	    return array(
            "fixed30" => $this->fixed30, 
	    	"fixed20" => $this->fixed20,			
	        "fixed15" => $this->fixed15,			
	        "fixed10" => $this->fixed10,			
	        "supfixed30" => $this->supfixed30,			
  	        "supfixed15" => $this->supfixed15,
			"arm51" => $this->arm51,
			"arm71" => $this->arm71,
			"arm101" => $this->arm101,
			"ncarm51" => $this->ncarm51,
	    );
    }

	public function getAdjLtvCcMap(){
	    return array (
		    "adj_ltv_cc1" => $this->adj_ltv_cc1
		);
	}
    
    public function getAdjOthersMap(){
	    return array (
			"adj_others" => $this->$adj_others
		);
    }

}
?>