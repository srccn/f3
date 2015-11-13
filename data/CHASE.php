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
    		"range" => "B11:L24"
    );
	//arm71
    private $arm71 = array (
    		"sheetName" => "Agency ARMs",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 9,
    		"range" => "O11:Y24"
    );
	//amr101
    private $arm101 = array (
    		"sheetName" => "Agency ARMs",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 10,
    		"range" => "B28:L41"
    );
	
	//supperarm51
    private $ncarm51 = array (
    		"sheetName" => "Agency ARMs",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 30,
    		"range" => "O28:Y41"
    );	

    //supfixed30
    private $supfixed30 = array (
    		"sheetName" => "Agency Fixed High Balance",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 23,
    		"range" => "B12:L31"
    );
    
    //supfixed15
    private $supfixed15 = array (
    		"sheetName" => "Agency Fixed High Balance",
    		"lock_days" => 	[15, 30, 45, 60, 75],
    		"loan_type" => 25,
    		"range" => "O12:Y25"
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

    public function getStateListSRPMap () {
    	$selectedStates=["AK", "MA", "NH", "RI", "CT", "VT", "NY"];
        $conforming_rage = [["70000","74999"],["75000","89999"],["90000","99999"],
    			    ["100000","109999"],["110000","124999"],["125000","149999"], ["150000","174999"],
    			    ["175000","224999"],["225000","249999"],["250000","274999"], ["275000","417000"],
    			    ["417000","1300000"]
        ];
    
    	$srpFixed30 = array (
    			"sheetName" => "Agcy Conf FR" ,
    			"loan_type_id" => 1,
    			"escrow" => 1,
    			"stateCol" =>"A14:B64",
    			"range" => "C14:C64",
    	        "amountAdjRange" => $conforming_rage,
    			"amountAdj" => "C67:C78"
    	);
    	$srpFixed15 = array  (
    			"sheetName" => "Agcy Conf FR" ,
    			"loan_type_id" => 3,
    			"escrow" => 1,
    			"stateCol" =>"A14:B64",
    			"range" => "H14:H64",
    	        "amountAdjRange" => $conforming_rage,
    			"amountAdj" => "H67:H78"
    
    	);
    	$srpArm51   = array (
    			"sheetName" => "Agcy Conf ARM" ,
    			"loan_type_id" => 8,
    			"escrow" => 1,
    			"stateCol" =>"A15:B65",
    			"range" =>"C15:D65",
    	        "amountAdjRange" => $conforming_rage,
    			"amountAdj" => "C68:C79"
    			    	);
    	$srpArm71   = array (
    			"sheetName" => "Agcy Conf ARM" ,
    			"loan_type_id" => 9,
    			"escrow" => 1,
    			"stateCol" =>"A15:B65",
    			"range"=>"F15:F65",
    	        "amountAdjRange" => $conforming_rage,
    			"amountAdj" => "F68:F79"
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