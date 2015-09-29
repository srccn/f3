<?php

class BOKF {
	
	private $loanerName="BOKF";
	private $loaner_id = 2;
	private $lockdays = [7, 15, 30, 45, 60, 75] ;
	
    //30fixed
    private $fixed30 = array (
    	"sheetName" => "CONF FIXED",
    	"lock_days" => 	[7, 15, 30, 45, 60, 75] , 
    	"loan_type" => 1,	
        "range" => "B27:H39"
    );

    //25fixed
    private $fixed25 = array (
    	"sheetName" => "CONF FIXED",
    	"lock_days" => 	[7, 15, 30, 45, 60, 75] ,
    	"loan_type" => 5,
    	"range" => "B27:H39"
    );
    
    
    //20fixed
    private $fixed20 = array (
    	"sheetName" => "CONF FIXED",
    	"lock_days" => 	[7, 15, 30, 45, 60, 75], 
    	"loan_type" => 2,
        "range" => "J27:P39"
    );

    //15fixed
    private $fixed15 = array (
    	"sheetName" => "CONF FIXED",
    	"lock_days" => 	[7, 15, 30, 45, 60, 75], 
    	"loan_type" => 3,
    	"range" => "R27:X39"
    );
    
    //10fixed
    private $fixed10 = array (
    	"sheetName" => "CONF FIXED",
    	"lock_days" => 	[7, 15, 30, 45, 60, 75],
    	"loan_type" => 4,
    	"range" => "B75:H87"
    );
    
    //ncfixed30
    private $ncfixed30 = array (
    		"sheetName" => "SUPERCONF",
    		"lock_days" => 	[30, 45, 60],
    		"loan_type" => 13,
    		"range" => "B20:E32"
    );
    
    //ncfixed15
    private $ncfixed15 = array (
    		"sheetName" => "SUPERCONF",
    		"lock_days" => 	[30, 45, 60],
    		"loan_type" => 15,
    		"range" => "G20:J32"
    );
	
	//arm51
    private $arm51 = array (
    		"sheetName" => "ARMS",
    		"lock_days" => 	[30, 45, 60],
    		"loan_type" => 8,
    		"range" => "B77:R87"
    );
	//arm71
    private $arm71 = array (
    		"sheetName" => "ARMS",
    		"lock_days" => 	[30, 45, 60],
    		"loan_type" => 9,
    		"range" => "G77:J87"
    );
	//amr101
    private $arm101 = array (
    		"sheetName" => "ARMS",
    		"lock_days" => 	[30, 45, 60],
    		"loan_type" => 10,
    		"range" => "L77:O87"
    );
	
	//ncarm51
    private $ncarm51 = array (
    		"sheetName" => "ARMS",
    		"lock_days" => 	[30, 45, 60],
    		"loan_type" => 20,
    		"range" => "B97:E107"
    );	
	//ncarm71
    private $ncarm71 = array (
    		"sheetName" => "ARMS",
    		"lock_days" => 	[30, 45, 60],
    		"loan_type" => 21,
    		"range" => "G97:J107"
    );	
	//ncarm101
    private $ncarm101 = array (
    		"sheetName" => "ARMS",
    		"lock_days" => 	[30, 45, 60],
    		"loan_type" => 22,
    		"range" => "L97:O107"
    );	
    
	//ltv_cc_adjust
	private $adj_ltc_cc = array (
    		"sheetName" => "CONVENTIONL ADJUSTERS",
			"ltc" => [0,60,70,75,80,85,90,95], //rage pick = max ( value < given_value) 
			"cc"  => [740,720,700,680,660,640,620,0] , //range pick = max (value < given_value)
        	"range" => "C64:O71"		
	);
	
    public function getMap() {
	    return array(
            "fixed30" => $this->fixed30, 
            "fixed25" => $this->fixed25, 
	    	"fixed20" => $this->fixed20,			
	        "fixed15" => $this->fixed15,			
	        "fixed10" => $this->fixed10,			
	        "ncfixed30" => $this->ncfixed30,			
  	        "ncfixed15" => $this->ncfixed15,
			"arm51" => $this->arm51,
			"arm71" => $this->arm71,
			"arm101" => $this->arm101,
			"ncarm51" => $this->ncarm51,
			"ncarm71" => $this->ncarm71,
			"ncarm101" => $this->ncarm101
	    );
    }

	public function getAdj(){
	    return array (
		    "adj_ltc_cc" => $this->adj_ltc_cc	
		);
	}
	
    public function getPurchaserId(){
    	return $this->loaner_id;
    }
    
    public function getLockDays() {
    	return $this->lockdays;
    }
    
}
?>