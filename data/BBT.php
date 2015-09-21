<?php

class BBT {
	
	private $loanerName="BBT";
	private $loaner_id = 1;
	private $lockdays = [15, 30, 45, 60] ;
	
    //30fixed
    private $fixed30 = array (
    	"sheetName" => "CA Rate Sheet",	
    	"lock_days" => 	[15, 30, 45, 60], 
    	"loan_type" => 1,	
        "range" => "A16:E35"
    );

    //20fixed
    private $fixed20 = array (
    	"sheetName" => "CA Rate Sheet",
    	"lock_days" => 	[15, 30, 45, 60], 
    	"loan_type" => 2,
        "range" => "F16:J35"
    );

    //15fixed
    private $fixed15 = array (
    		"sheetName" => "CA Rate Sheet",
    	"lock_days" => 	[15, 30, 45, 60], 
    	"loan_type" => 3,
    	"range" => "K16:O35"
    );
    
    //10fixed
    private $fixed10 = array (
    		"sheetName" => "CA Rate Sheet",
    	"lock_days" => 	[15, 30, 45, 60], 
    	"loan_type" => 4,
    	"range" => "A39:E58"
    );
    
    //ncfixed30
    private $ncfixed30 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[15, 30, 45, 60],
    		"loan_type" => 13,
    		"range" => "F63:J82"
    );

    //ncfixed15
    private $ncfixed15 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[15, 30, 45, 60],
    		"loan_type" => 15,
    		"range" => "K63:O82"
    );

    //arm31
    private $arm31 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 7,
    		"range" => "A125:C144"
    );
    
    //arm51
    private $arm51 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 8,
    		"range" => "D125:F144"
    );
    
    //arm71
    private $arm71 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 9,
    		"range" => "G125:I144"
    );

    //arm101
    private $arm101 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 10,
    		"range" => "J125:L144"
    );
    
    //ncarm31
    private $ncarm31 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 18,
    		"range" => "A148:C167"
    );

    //ncarm51
    private $ncarm51 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 19,
    		"range" => "D148:F167"
    );

    //ncarm71
    private $ncarm71 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 20,
    		"range" => "G148:I167"
    );

    //ncarm101
    private $ncarm101 = array (
    		"sheetName" => "CA Rate Sheet",
    		"lock_days" => 	[30, 60],
    		"loan_type" => 21,
    		"range" => "J148:L167"
    );
    
    
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
    
    public function getPurchaserId(){
    	return $this->loaner_id;
    }
    
    public function getLockDays() {
    	return $this->lockdays;
    }
    
}
?>