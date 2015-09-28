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
	protected $creditScroe;
    protected $loanName; //fixed30, fixed15, arm51, arm71
	protected $LTV;
	protected $isConfirming;
	protected $fees;
	protected $adjusts;
	
    
    function setInput($inputs ){
        
        $this->numberUnit = $inputs["numberUnit"];
        $this->type=$inputs["type"];
        $this->occType=$inputs["occType"];
        $this->purchaseType=$inputs["purchaseType"];
        $this->loanAmount=$inputs["loanAmount"];
        $this->zip=$inputs["zip"];
        $this->marketPrice=$inputs["marketPrice"];
        $this->creditScore=$inputs["creditScore"];
        $this->loanName=$inputs["loanName"];

        $this->getLTV();
        $this->setIsConfirming();
        
    }
    
    public function setTest () {    
        $this->numberUnit='two_unit';
        $this->type='house';
        $this->occType='primary';
        $this->purchaseType='purchase';
        $this->loanAmount=390000;
        $this->zip='02460';
        $this->marketPrice=400000;
        $this->creditScore=780;
        $this->loanName="fixed30";
    
    }
    
    function setIsConfirming(){
        $upperLimit = $this->getLoanLimitByZipCode();
        if ($this->loanAmount > $upperLimit) {
            $this->isConfirming = 0;
        } else {
            $this->isConfirming = 1;
        }
        return $this->isConfirming;
    }
    
	function getLoanLimitByZipCode(){
	    $results = $this->db->exec("select GetConfirmingLoanUpperLimit('$this->zip' , '$this->numberUnit') as result ");
		//var_dump($results[0]);
		return Util::resultString($results);
	}
    
    function getState() {
        $state = $this->db->exec("select t1.state as result from county_loan_limit t1, zip_county t2 where t1.countycode = t2.countycode and t2.zipcode='$this->zip';");
        //var_dump ( $state );
        return Util::resultString($state);
    }

	function getLTV(){
        $this->LTV = round($this->loanAmount/$this->marketPrice, 2) ;
	    return  $this->LTV ;
	}
	
    function getRecordingFee() {
        //input state, purchase or refinance, lookup default 350, 65.
		$feeName = "RecordingFee";
		$returnVal = null ;
        $state = $this->getState();
        $result = $this->db->exec ("select $this->purchaseType as result from fee_recording where state='$state'");
        if ( ! $result ) {
            $returnVal = 350;
        } else {
            //var_dump($result);
            $returnVal = intval ( Util::resultString($result) );
        }
		$this->fees[$feeName] = $returnVal;
        return $returnVal;
    }
    
    function getRecordingOtherFee(){
        //input state, purchase or refinance, lookup default 350, 65.
		$feeName = "RecordingOtherFee";
		$returnVal = null;

		$state = $this->getState();
        $result = $this->db->exec ("select $this->purchaseType as result from fee_recording_other where state='$state'");
        if ( ! $result ) {
            $returnVal =  65;
        } else {
            //var_dump($result);
            $returnVal = intval(  Util::resultString($result) );
        }
		$this->fees[$feeName] = $returnVal;
        return $returnVal;
    }
    
    function getAttoneyFee(){
		$feeName = "AttoneyFee";
		$returnVal = null;

		$state = $this->getState();
        $result = $this->db->exec ("select $this->purchaseType as result from fee_attorney where state='$state'");
        if ( ! $result ) {
            $returnVal =  490 * 2; //double default for unlisted state
        } else {
            $returnVal = intval( Util::resultString($result) );
        }
		$this->fees[$feeName] = $returnVal;
        return $returnVal;
	}

    function getAppraisalFee() {
		$feeName = "AppraisalFee";
		$returnVal = null;

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
		$returnVal = $fee;
		$this->fees[$feeName] = $returnVal;
        return $returnVal;
    }
    
    function getLenderInsuranceFee() {
		$feeName = "LenderInsuranceFee";
		$returnVal = 0;
		
        if ($this->purchaseType == "refinance") {
            $returnVal = round($this->loanAmount * 0.15/100 ,0);
        }
        if ($this->purchaseType == "purchase") {
            $returnVal = round($this->loanAmount * 0.25/100, 0 );
        }
        
		$this->fees[$feeName] = $returnVal;
        return $returnVal;
    }
    
    function getTitleInsuranceFee(){
		$feeName = "TitleInsuranceFee";
		$returnVal = 0;
		
        if ($this->purchaseType == "refinance") {
            $returnVal = 0;
        }
        if ($this->purchaseType == "purchase") {
            $lenderInsuranceFee = $this->getLenderInsuranceFee();
            $returnVal = round($this->marketPrice * 0.4/100 + 175 - $lenderInsuranceFee, 0 );
        }
		$this->fees[$feeName] = $returnVal;
        return $returnVal;
    }
    
    function printProperty(){
        echo "<hr>";
        print "number of Unit is : $this->numberUnit<br>";
        echo "property type is : $this->type<br>";
        echo "Occupancy is $this->occType<br>";
        echo "Purchase od refinance is : $this->purchaseType<br>";
        echo "Loan amount is : $this->loanAmount<br>";
        echo "Property zip code is : $this->zip<br>";
        echo "Property marcket price is : $this->marketPrice<br>";
        echo "Credit Score is : $this->creditScore<br>";
        echo "LTV is : $this->LTV<br>";
        echo "is Confirming : $this->isConfirming<br>";
        echo "loanName : $this->loanName<br>";
        echo "<hr>";
    
    }
    
    function getLtvCcAdj(){
		$adjName = "LtvCcAdj";
		$returnVal = 0;
		
        $this->getLTV();
        $result = $this->db->exec(
            "select adjust as result 
             from   adj_ltv_cc 
             where  ltv_value > $this->LTV and
                    cc_value  < $this->creditScore
             order by ltv_value asc, cc_value desc
             limit 1
        ");
		
		$returnVal = floatval(Util::resultString($result));
		$this->adjusts[$adjName] = $returnVal;
        return $returnVal;
    }
    
    function getLtvCcPmiAdj($purchaserId){
		$adjName = "LtvCcPmiAdj";
		$returnVal = 0;

		$this->getLTV();
        $result = $this->db->exec(
            "select adjust as result 
             from   adj_ltv_cc_pmi 
             where  ltv_value <= $this->LTV and
                    cc_value  < $this->creditScore and
                    purchaser_id = $purchaserId
             order by ltv_value asc, cc_value desc
             limit 1
        ");
        
        if (! $result ) {
            $returnVal =  0;
        } else {
            $returnVal = floatval (Util::resultString($result));
        }
		
		$returnVal = floatval(Util::resultString($result));
		$this->adjusts[$adjName] = $returnVal;
        return $returnVal;
    }
    
    function getLtvOtherAdj($purchaserId){
		$adjName = "LtvOtherAdj";
		$returnVal = 0;

        $result = $this->db->exec(
            "select adjust_condo, 
                    adjust_invest,
                    adjust_2Units,
                    adjust_34Units,
                    adjust_arm,
                    adjust_highBalanceArm  
            from adj_ltv_others 
            where purchaser_id ='$purchaserId'
        ");
        
        if ($this->type == "condo") {
            $returnVal += $result[0]["adjust_condo"];
        }
        if ($this->occType == "investment") {
            $returnVal += $result[0]["adjust_invest"];
        }
        if ($this->numberUnit == "two_unit") {
            $returnVal += $result[0]["adjust_2Units"];
        }
        if ($this->numberUnit == "three_unit" ||
            $this->numberUnit == "four_unit"
           ) {
            $returnVal += $result[0]["adjust_34Units"];
        }
        if (strpos($this->loanName, 'arm') !== FALSE) {
            $return_adj += $result[0]["adjust_arm"];
            if ($this->LTV > 0.9) {
                $returnVal += $result[0]["adjust_highBalanceArm"];
            }
        }
        
        //echo $return_adj . "<br><br>" ;
		$this->adjusts[$adjName] = $returnVal;
        return $returnVal;
        
    }
    
    function getLoanTypeId(){
        if (strpos($this->loanName, 'fix') !== FALSE) { //fixed 
            $result = $this->db->exec ("
                select loan_type_id as result 
                from loan_type 
                where type_variable_name like '%$this->loanName%' and 
                      confirming =$this->isConfirming"
            );
        } else { //arm
            $result = $this->db->exec ("
                select loan_type_id as result 
                from loan_type 
                where type_variable_name = '$this->loanName' "
            );
        }
        
        if ( !$result ) die("Failed to find loan type : " . $this->loanName);
        
        return intval (Util::resultString($result));
        
    }
    
	function getStateGroupedSRP($purchaserId) {
		
		$loanTypeId = $this->getLoanTypeId();
		
		//set reference base
		if (strpos($this->loanName, 'fix') !== FALSE) {
		    $baseRef = 1 ; //30dixed bases
		} else {
		    $baseRef = 9; //71 arm based
		}
		
		echo "$this->zip" . "," . $this->loanAmount .",". $loanTypeId. ",".$purchaserId ."<br>";
		
		//find base SRP 
        $result = $this->db->exec ("select GetGroupedSRPByZip(
		                  '$this->zip',
						  $baseRef ,
						  $this->loanAmount,
						  $purchaserId ) as result ");
		
        if ( ! $result ) die("Failed to find SRP : " . "$this->zip  $this->loanAmount $loanTypeId ");
		
		//find SRP deduction from base if applicable:
		$deduction = 0;
		if ($loanTypeId != $baseRef ) {
		
			$deduction = $this->db->exec("
				select deduction as result
				from purchaser_srp_loan_type_ref
				where purchaser_id = $purchaserId
				  and loan_type_id = $loanTypeId
				  and ref_loan_type_id = $baseRef 
				  and hasdata = 0
				");
            if ( ! $deduction ) die("Failed to find SRP deduction : " . "$this->zip  $this->loanAmount $loanTypeId ");
		}
		
		return floatval(Util::resultString($result)) - floatval(Util::resultString($deduction));
	}
	
	
    function test () {
		$this->logger->write(__FUNCTION__);
        //$this->setTest();
        $this->getLTV();
        $this->printProperty();
        
        Util::dump("State", $this->getState());
        Util::dump("LTV", $this->getLTV());
        Util::dump("Appraisal fee",$this->getAppraisalFee());
        Util::dump("Confirming loan limit", $this->getLoanLimitByZipCode());
        Util::dump("set confirming ", $this->setIsConfirming());
        Util::dump("Lender Insurance fee",$this->getLenderInsuranceFee());
        Util::dump("Title Insurance fee" ,$this->getTitleInsuranceFee());
        Util::dump("Recording fee", $this->getRecordingFee());
        Util::dump("Recording other fee",$this->getRecordingOtherFee());
        Util::dump("Attorney fee",$this->getAttoneyFee());
        Util::dump("ltv cc adjust",$this->getLtvCcAdj());
        Util::dump("ltv cc pmi adjust",$this->getLtvCcPmiAdj(2));
        Util::dump("ltv other adjust",$this->getLtvOtherAdj(2));
        Util::dump("find loan type Id",$this->getLoanTypeId());
        Util::dump("Find bank 2 SPR",$this->getStateGroupedSRP(2));
		var_dump($this->fees);
		echo "Total Fee is " . Util::getSumValue($this->fees) . "<br>";
		var_dump($this->adjusts);
		echo "Total adjust is " . Util::getSumValue($this->adjusts) . "<br>";

    }

}
?>