<?php
class bftestDataMap {
	
	private $loanerName="Bing Fang";
	
    //30fixed
    private $fixed30 = array (
        "rate"   => "A" ,
        "15days" => "B" ,
        "30days" => "C" ,
        "45days" => "D" ,
        "60days" => "E" ,
        "start_row" => 9,
        "rows" => 2
    );

    //20fixed
    private $fixed20 = array (
        "rate"   => "H" ,
        "15days" => "I" ,
        "30days" => "J" ,
        "45days" => "K" ,
        "60days" => "L" ,
        "start_row" => 9,
        "rows" => 2
    );

    public function getMap() {
	    return array(
            "fixed30" => $this->fixed30, 
	        "fixed20" => $this->fixed20			
        );
    }
}
?>