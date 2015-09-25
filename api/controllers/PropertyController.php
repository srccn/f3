<?php

class  PropertyController extends BaseController {

    protected $adress; //optional
	protected $state; //optional
	protected $zip; 
	protected $marketPrice;
	protected $numberUnit;
	protected $type; //condo, house
	protected $occType; //primary, commercial, investment
	protected $purchaseType; //purchase, refinance
	protected $loanAmount;
	
/*    
    function __construct($numberUnit, $type, $occType, $purchaseType, $loanAmount,$zip, $marketPrice ){
        
        $this->numberUnit = $numberUnit;
        $this->type=$type;
        $this->occType=$occType;
        $this->purchaseType=$purchaseType;
        $this->loanAmount=$loanAmount;
        $this->zip=$zip;
        $this->marketPrice=$marketPrice;
    }
*/    
    public function setTest () {    
        $this->numberUnit='two_unit';
        $this->type='house';
        $this->occType='primary';
        $this->purchaseType='purchase';
        $this->loanAmount=350000;
        $this->zip='02460';
        $this->marketPrice=430000;
    
    }
    
	function getLoanLimitByZipCode($zip, $number_unit){
	    $results = $this->db->exec("select GetConfirmingLoanUpperLimit('$zip' , '$number_unit') as result ");
		//var_dump($results[0]);
		return Util::resultString($results);
	}
    
    function getState() {
        $state = $this->db->exec("select t1.state as result from county_loan_limit t1, zip_county t2 where t1.countycode = t2.countycode and t2.zipcode='$this->zip';");
        //var_dump ( $state );
        return Util::resultString($state);
    }
	
	function isConfirmingLoan(){
	
	}
    
    function getRecordingFee() {
        //input state, purchase or refinance, lookup default 350, 65.
        $state = $this->getState();
        $result = $this->db->exec ("select $this->purchaseType as result from fee_recording where state='$state'");
        var_dump($state);
        if ( ! $result ) {
            return 350;
        } else {
            //var_dump($result);
            return Util::resultString($result);
        }
        
    }
    
    function getRecordingOtherFee(){
        //input state, purchase or refinance, lookup default 350, 65.
        $state = $this->getState();
        $result = $this->db->exec ("select $this->purchaseType as result from fee_recording_other where state='$state'");
        if ( ! $result ) {
            return 65;
        } else {
            //var_dump($result);
            return Util::resultString($result);
        }

    }
    
    function getAttoneyFee(){
        $state = $this->getState();
        $result = $this->db->exec ("select $this->purchaseType as result from fee_attorney where state='$state'");
        if ( ! $result ) {
            return 490 * 2;
        } else {
            //var_dump($result);
            return Util::resultString($result);
        }
    }

	function getLTV(){
	    return round($this->loanAmount/$this->marketPrice, 2) ;
	}
    
    function getAppraisalFee() {
        $state = $this->getState();
        $fee=0;
        switch ($this->numberUnit) {
            case "condo":
                $fee=350;
                break;
            case "one_unit":
                $fee=350;
                break;
            case "two_unit":
                $fee=525;
                break;
            case "three_unit":
                $fee=525;
                break;
            case "four_unit":
                $fee=525;
                break;
            default: // unrecognized unit type, use defualt assumption value 375
                $fee=375;
        }
        if ($this->loanAmount > 1000000) {
            $fee=525;
        }
        if ($state != "MA") {
            $fee +=125;
        }
        return $fee;
    }
    
    function getLenderInsuranceFee() {
        $result = 0;
        if ($this->purchaseType == "refinance") {
            $result = round($this->loanAmount * 0.15/100 ,0);
        }
        if ($this->purchaseType == "purchase") {
            $result = round($this->loanAmount * 0.25/100, 0 );
        }
        
        return $result;
        
    }
    
    function getTitleInsuranceFee(){
        $result = 0;
        if ($this->purchaseType == "refinance") {
            $reuslt = 0;
        }
        if ($this->purchaseType == "purchase") {
            $lenderInsuranceFee = $this->getLenderInsuranceFee();
            $result = round($this->marketPrice * 0.4/100 + 175 - $lenderInsuranceFee, 0 );
        }
        return $result;
    }
    
    function test () {
        $this->setTest();
        var_dump($this->getState());
        var_dump($this->getLTV());
        var_dump($this->getAppraisalFee());
        var_dump($this->getLoanLimitByZipCode($this->zip, $this->numberUnit));
        var_dump($this->getLenderInsuranceFee());
        var_dump($this->getTitleInsuranceFee());
        var_dump($this->getRecordingFee());
        var_dump($this->getRecordingOtherFee());
        var_dump($this->getAttoneyFee());
    }

}
?>