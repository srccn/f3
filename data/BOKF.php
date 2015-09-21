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
    
    public function getMap() {
	    return array(
            "fixed30" => $this->fixed30, 
            "fixed25" => $this->fixed25, 
	    	"fixed20" => $this->fixed20,			
	        "fixed15" => $this->fixed15,			
	        "fixed10" => $this->fixed10,			
	        "ncfixed30" => $this->ncfixed30,			
  	        "ncfixed15" => $this->ncfixed15,			
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