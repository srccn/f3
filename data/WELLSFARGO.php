<?php

class WELLSFARGO extends BasePurchaser {
	
	protected $purchaserName="WELLSFARGO";
	protected $purchaserId = 3;
	protected $excelFile = "data/wells fargo.xls";
	protected $lockdays = [15, 30, 45, 60] ;
    protected $baseLockDays = 60
	
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
    		"sheetName" => "CA Rate Sheet",
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
    
    private purchaseLockDaysConfAdj45 = array (
    		"sheetName" => "Conf Pricing",
    		"lock_days" => 	[45],
    		"loan_type" => ["fixed30", "fixed20", "fixed15", "fixedRelo", "arm51", "arm71","amr101" ],
    		"range" => "F53:F59"
    );
    
    private purchaseLockDaysNoneConfAdj45 = array (
    		"sheetName" => "Non-Conf Pricing",
    		"lock_days" => 	[45],
    		"loan_type" => ["fixed30", "fixed15", "" "arm51", "arm71","amr101" ],
    		"range" => "P41:P45"
    );
    
    public function purchaseLockDayAdj45($loanTypeId, $adj) {
        $query = "insert into purchaser  
                  select price + $adj from purchaser 
                  where purchaser_id =3 
                  and lock_days = $this->$baseLockDays
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

}
?>