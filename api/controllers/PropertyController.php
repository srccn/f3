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
    protected $lockDays;
	protected $creditScroe;
    protected $loanName; //fixed30, fixed15, arm51, arm71
	protected $LTV;
	protected $isConfirming;
	protected $isSupperConfirming;
	protected $fees;
	protected $adjusts;
	protected $margin;
	
    
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
        $this->lockDays=$inputs["lockDays"];
        $this->margin=$inputs["margin"];
        
        $this->getLTV();
        $this->setIsConfirming();
        
    }
    
    public function setTest () {    
        $this->numberUnit='two_unit';
        $this->type='house';
        $this->occType='primary';
        $this->purchaseType='purchase';
        $this->loanAmount=700000;
        $this->zip='02460';
        $this->marketPrice=800000;
        $this->creditScore=780;
        $this->loanName="fixed30";
        $this->state = $this->getState();
    
    }
    
    function setIsConfirming(){
        $upperLimit = $this->getLoanLimitByZipCode();
        if ($this->loanAmount > $upperLimit) {
            $this->isConfirming = 0;
        } else {
            $this->isConfirming = 1;
            if ($this->loanAmount > LoanerConst::CONFIRMING_LIMIT_AMOUNT) {
            	$this->isSupperConfirming = 1;
            } else {
            	$this->isSupperConfirming = 0;
            }
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
        echo "lockDays : $this->lockDays<br>";
        echo "Margin : $this->margin<br>";
        echo "<hr>";
    
    }
    
    function getLtvCcAdj($purchaserId){
		$adjName = "LtvCcAdj";
		$returnVal = 0;
		
        $this->getLTV();
        $result = $this->db->exec(
            "select adjust as result 
             from   adj_ltv_cc 
             where  ltv_value < $this->LTV*100 
        	   and  cc_value  < $this->creditScore
        	   and  purchaser_id = $purchaserId
             order by ltv_value desc, cc_value desc
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
             where  ltv_value <= $this->LTV * 100 and
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
		
		//echo "$this->zip" . "," . $this->loanAmount .",". $loanTypeId. ",".$purchaserId ."<br>";
		
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
	
	function getSRP($purchaserId) {
		$result = $this->db->exec("select getSRPFunction as func from purchaser where purchaser_id = $purchaserId");
		return $result[0]['func'];
	}
	
	function loanLimitCheck(){
		//Amount checks
		if ($this->loanAmount > LoanerConst::MAXIMUM_LIMIT_AMOUNT) {
			echo "Loan Amount is greater than limit no SRP value. <br>";
			return false;
		}elseif ($this->loanAmount < LoanerConst::MIMIMUM_LIMIT_AMOUNT) {
			echo "Loan Amount is less than mimimum limit no SRP value. <br>";
			return false;
		}
		
		//Credit Score and LTV checks
		if ($this->purchaseType == LoanerConst::PURCHASE ) {
			if ($this->creditScore < LoanerConst::MIMIMUM_CREDIT_SCORE_PURCHASE) {
			    echo "Credit score does not meet mimimun for purchase. <br>";
			    return false;
		    }
		    if ($this->LTV * 100 > LoanerConst::MAXMUM_LTV_PURCHASE) {
		    	echo "Failed LTV check for purchase. <br>";
		    	return false;
		    }
		}
		if ($this->purchaseType == LoanerConst::REFINANCE) {
			if ($this->creditScore < LoanerConst::MIMIMUM_CREDIT_SCORE_REFINANCE) {
				echo "Credit score does not meet mimimun for refinance. <br>";
    			return false;
		    }
		    if ($this->LTV * 100 > LoanerConst::MAXMUM_LTV_REFINANCE) {
		    	echo "Failed LTV check for refinance. <br>";
		    	return false;
		    }
		}
		if ($this->purchaseType == LoanerConst::COREFINANCE ) {
			if ($this->creditScore < LoanerConst::MIMIMUM_CREDIT_SCORE_COREFINANCE) {
				echo "Credit score does not meet mimimun for cash out refinance. <br>";
				return false;
		    }
		    if ($this->LTV * 100 > LoanerConst::MAXMUM_LTV_COREFINANCE) {
		    	echo "Failed LTV check for cahs out purchase. <br>";
		    	return false;
		    }
		}
		//passed all checks
		return true;
	}
	
	
	function getStateFullListSRP($purchaserId) {
		$state = $this->getState();
		$query = "";
		//right now supprot wells fargo - purchaser_id = 3
		// state = MA NH CT only
		
		$loanTypeId = $this->getLoanTypeId();
		
		if ( ! $this->isConfirming ) { //non confirming case
			$query = "SELECT $state  as result
			          FROM  state_srp_full_list
			         WHERE  start_amount = 'confirming'
			           AND  loan_type_id = $loanTypeId
			           AND  purchaser_id = $purchaserId
			";
		}
		
		if ($this->isSupperConfirming) {// supper confirming
			$query = "SELECT $state  as result
			            FROM  state_srp_full_list
			           WHERE  end_amount ='confirming'
			             AND  loan_type_id = $loanTypeId
			             AND  purchaser_id = $purchaserId
			";
		}
		
		if ($this->isConfirming) {
			$query = "SELECT $state as result
			            FROM  state_srp_full_list
			           WHERE  $this->loanAmount > convert(start_amount, UNSIGNED)
			             AND  loan_type_id = $loanTypeId
			             AND  purchaser_id = $purchaserId
			        ORDER BY  convert(start_amount, UNSIGNED) desc
			           LIMIT  1
			";
				
		}
		//echo $query."<br>";
        $result = $this->db->exec($query);
        //var_dump($result) ;
		return $result[0]['result'];
	}
	
	
    function getPurchaseRate($purchaserId, $margin) {
        $adjust = Util::getSumValue($this->adjusts);
        echo $this->getSRP($purchaserId) . "<br>";
        $SRP = $this->{$this->getSRP($purchaserId)}($purchaserId);
        $fees = Util::getSumValue($this->fees);
        $margin = $this->margin;
        $loanTypeId = 1 ;//only have 30fix data now
//        echo $fees . "<br>";
//        echo $adjust . "<br>";
//        echo $SRP . "<br>";
//        echo $this->loanAmount . "<br>";
        
        $result = $this->db->exec("
            select rate , ((purchase_price + $adjust - $margin + $SRP - 100)/100 * $this->loanAmount -  $fees) credit 
            from purchase
            where purchaser_id = $purchaserId
              and lock_days_id = $this->lockDays
              and loan_type_id = $loanTypeId 
              and ((purchase_price + $adjust - $margin + $SRP - 100)/100 * $this->loanAmount -  $fees) > 0
            order by rate asc
            limit 1
        ");
        
        var_dump($result);
    
    }
	
    function test () {
		$this->logger->write(__FUNCTION__);
        //$this->setTest();
        $this->getLTV();
        $this->printProperty();
        
        Util::dump("State", $this->getState());
        Util::dump("LTV", $this->getLTV());
        Util::dump("Confirming loan limit", $this->getLoanLimitByZipCode());
        Util::dump("set confirming ", $this->setIsConfirming());
        echo " -- is SupperConfirming : {$this->isSupperConfirming} <br>";
        Util::dump("find loan type Id",$this->getLoanTypeId());
        Util::dump("Appraisal fee",$this->getAppraisalFee());
        Util::dump("Lender Insurance fee",$this->getLenderInsuranceFee());
        Util::dump("Title Insurance fee" ,$this->getTitleInsuranceFee());
        Util::dump("Recording fee", $this->getRecordingFee());
        Util::dump("Recording other fee",$this->getRecordingOtherFee());
        Util::dump("Attorney fee",$this->getAttoneyFee());
        var_dump($this->fees);
        echo "Total Fee is " . Util::getSumValue($this->fees) . "<br><br><br>";
//        $this->getStateFullListSRP(3);
//        echo $this->getSRP(2) . "<br>";
        
        $purchasers=[1,2,3];
        foreach ($purchasers as $purchaserId) {
        	echo "<hr>";
            echo "Purchaser number $purchaserId";
        	echo "<hr>";
        	if (! $this->loanLimitCheck()) { //if failed loanamount check , skip
        		continue;
        	}
            Util::dump("ltv cc adjust",$this->getLtvCcAdj($purchaserId));
            Util::dump("ltv cc pmi adjust",$this->getLtvCcPmiAdj($purchaserId));
            Util::dump("ltv other adjust",$this->getLtvOtherAdj($purchaserId));
		    var_dump($this->adjusts);
		    echo "Total adjust is " . Util::getSumValue($this->adjusts) . "<br>";
            Util::dump("Find purchaser $purchaserId SRP",$this->{$this->getSRP($purchaserId)}($purchaserId));
            //echo "<hr>";
            echo "<br> Calculate bank $purchaserId with margin $this->margin % <br>";
            $this->getPurchaseRate($purchaserId, $this->margin);
            
        }
       
    }

}
?>