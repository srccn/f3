<?php
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
abstract class AbstractRateCalculatorController extends BaseController {
	
    protected   $purchaserName;	
    protected   $purchaserId;	
    protected   $property ;
	protected   $adjusts;
	protected   $fees;

	abstract public function getSRP();
	
	abstract public function getPurchaseRate();
	
	
	public function __construct($purchaserName) {
		parent::__construct();
		$this->adjusts = [];
		$this->purchaserName = $purchaserName;
		$this->purchaserId   = $this->getPurchaserIdByName($purchaserName);
	}
	
	public function setProperty( LoanProperty $property ) {
		$this->property = $property;
	}

	public function setTotalFee( $fees ) {
		$this->fees = $fees;
	}
	
	public function getAdjusts(){
		return $this->adjusts;
	}
	
	private function getLtvCcAdj() {
		$adjName = "LtvCcAdj";
		$returnVal = 0;
		
		$LTV = $this->property->LTV;
		$creditScore = $this->property->creditScore ;
		$result = $this->runQuery(
				"select adjust as result
				from   adj_ltv_cc
				where  ltv_value < $LTV * 100
				and  cc_value  < $creditScore
				and  purchaser_id = $this->purchaserId
				order by ltv_value desc, cc_value desc
				limit 1
				");
		
				$returnVal = floatval(Util::resultString($result));
				$this->adjusts[$adjName] = $returnVal;
				return $returnVal;		
	}
	
	private function getLtvCcPmiAdj() {
		$adjName = "LtvCcPmiAdj";
		$returnVal = 0;
		
		$LTV = $this->property->LTV;
		$creditScore = $this->property->creditScore ;
		$result = $this->runQuery(
				"select adjust as result
				from   adj_ltv_cc_pmi
				where  ltv_value <= $LTV * 100 and
				cc_value  < $creditScore and
				purchaser_id = $this->purchaserId
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
	
	private function getLtvOtherAdj(){
		$adjName = "LtvOtherAdj";
		$returnVal = 0;
		
		$result = $this->runQuery(
				"select adjust_condo,
		        		adjust_invest,
			        	adjust_2Units,
				        adjust_34Units,
        				adjust_arm,
		        		adjust_highBalanceArm
				   from adj_ltv_others
				  where purchaser_id = $this->purchaserId
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
			    $this->numberUnit == "four_unit" ) {
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
	
	public function getPurchaseRateType1() { //look up purchase table 
		$adjust = Util::getSumValue($this->adjusts);
		// echo $this->getSRP($purchaserId) . "<br>";
		$SRP = $this->getSRP();
		//echo "SRP is $SRP <br>" ;
		//echo "Fees is $this->fees <br>" ;
		$fees = $this->fees;
		$margin = $this->property->margin;
		$lockDays = $this->property->lockDays;
		$loanTypeId = $this->getSRPLoanTypeId(); //$this->property->loanTypeId ;
		$loanAmount = $this->property->loanAmount ;
		//        echo $fees . "<br>";
		//        echo $adjust . "<br>";
		//        echo $SRP . "<br>";
		//        echo $this->loanAmount . "<br>";
	
		$query ="
				select rate , ((purchase_price + $adjust - $margin + $SRP - 100)/100 * $loanAmount -  $fees) credit
				from purchase
				where purchaser_id = $this->purchaserId
				and lock_days_id = $lockDays
				and loan_type_id = $loanTypeId
				and ((purchase_price + $adjust - $margin + $SRP - 100)/100 * $loanAmount -  $fees) > 0
				order by rate asc
				limit 1
				";
		//echo $query . "<br>";
		$result = $this->runQuery($query);
		echo "Rate = " .$result[0]['rate'] ."  Credit = " .$result[0]['credit'] ."<br>";
		//var_dump($result);
	}	

	protected function getStateGroupedSRP() {
	
		$loanTypeId = $this->property->loanTypeId;
	
		//set reference base
		if (strpos($this->property->loanName, 'fix') !== FALSE) {
			$baseRef = 1 ; //30dixed bases
		} else {
			$baseRef = 8; //51 arm based
		}
	
		//echo "$this->zip" . "," . $this->loanAmount .",". $loanTypeId. ",".$purchaserId ."<br>";
	
		//find base SRP
		$zip = $this->property->zip ;
		$loanAmount = $this->property->loanAmount;
		$result = $this->runQuery("select GetGroupedSRPByZip(
				'$zip',
				$baseRef ,
				$loanAmount,
				$this->purchaserId ) as result ");
	
		if ( ! $result ) die("Failed to find SRP : " . "$this->property->zip  $this->property->loanAmount $loanTypeId ");
	
		//get base loan type for srp calcualtion
		$srp_calculation_loan_type_id = $this->getSRPLoanTypeId();
	
		//find SRP deduction from base if applicable:
		$deduction = 0;
		if ($srp_calculation_loan_type_id != $baseRef ) {
	
			$deduction = $this->runQuery("
					select deduction as result
					from purchaser_srp_loan_type_ref
					where purchaser_id = $this->purchaserId
					and loan_type_id = $srp_calculation_loan_type_id
					and ref_loan_type_id = $baseRef
					and hasdata = 0
					");
			if ( ! $deduction ) die("Failed to find SRP deduction : " . $this->property->zip. ",".  $this->property->loanAmount.",". $this->property->loanTypeId );
		}
	
		return floatval(Util::resultString($result)) - floatval(Util::resultString($deduction));
	}
	
	private function getSRPLoanTypeId () {
	
		$loanTypeId = $this->property->loanTypeId;
		//from loan_type_id to find loan type base id that is not condirminged for SRP calculation
	
		$base_type_id_result = $this->runQuery("
				SELECT loan_type_base_srp_id as bID
				FROM loaner.loan_type
				WHERE loan_type_id=$loanTypeId
				");
		if ( ! $base_type_id_result ) die("Failed to find loan_type_base_id for :  $loanTypeId");
		$srp_calculation_loan_type_id = intval($base_type_id_result[0]['bID']);
	
		//echo $srp_calculation_loan_type_id."<br>";
		return $srp_calculation_loan_type_id;
	}	
	
	protected function getSuperConfirmingAdj() {
		$adjName = "SuperConfirmingAdj";
		if ($this->property->isConfirming != 2) {
			echo "Not a super confirming loan, skip super confirming adj <br>";
			$this->adjusts[$adjName] = 0;
			return;
		}
		$LTV = $this->property->LTV ;
		$purchaseType = $this->property->purchaseType ; 
		$result = $this->runQuery("
				SELECT $purchaseType as result
				FROM adj_ltv_super_confirming
				WHERE purchaser_id = $this->purchaserId
				AND ltv_value < $LTV * 100
				ORDER BY ltv_value desc
				LIMIT 1
				");
		//var_dump($result);
		$returnVal = 0.00 ;
		if ($result === null) { // find null value
			die ("SuperConfirming LTV greater than max limit");
		}
	
		if ( count($result) == 0 )  { //not find any value
			$returnVal = 0.00;
		}
	
		$returnVal = floatVal($result[0]['result']);
	
		$this->adjusts[$adjName] = $returnVal;
		//echo "$returnVal <br>";
		return $returnVal;
	}
	
	protected function getStateFullListSRP() {
		$state = $this->property->state;
		$loanAmount = $this->property->loanAmount;
		$query = "";
		//right now supprot wells fargo - purchaser_id = 3
		// state = MA NH CT only
	
		$loanTypeId = $this->getSRPLoanTypeId();//$this->property->loanTypeId ;//getLoanTypeId();
		//echo "loan type id is : $loanTypeId <br>";
	
		if ( $this->property->isConfirming == 0) { //non confirming case
			$query = "SELECT $state  as result
			FROM  state_srp_full_list
			WHERE  start_amount = 'confirming'
			AND  loan_type_id = $loanTypeId
			AND  purchaser_id = $this->purchaserId 
			";
		}
	
		if ($this->property->isConfirming == 2 ) {// supper confirming
			$query = "SELECT $state  as result
				FROM  state_srp_full_list
				WHERE  end_amount ='confirming'
				AND  loan_type_id = $loanTypeId
				AND  purchaser_id = $this->purchaserId  
				";
			}
	
	    if ( $this->property->isConfirming == 1 ) {
				$query = "SELECT $state as result
				FROM  state_srp_full_list
				WHERE  $loanAmount > convert(start_amount, UNSIGNED)
				AND  loan_type_id = $loanTypeId
				AND  purchaser_id = $this->purchaserId 
				ORDER BY  convert(start_amount, UNSIGNED) desc
				LIMIT  1
				";
	
	    }
		echo "srp query is : ".$query."<br>";
		$result = $this->runQuery($query);
		//var_dump($result) ;
		return $result[0]['result'];
	}	

	public function getPurchaserIdByName() {
		$name = $purchaserName ;//$this->f3->get('PARAMS.BankName');
		$result = $this->db->exec("select purchaser_id as result from purchaser where purchaser_name='$this->purchaserName'");
		//var_dump( $result );
		return intval ( Util::resultString($result) );
	}	
	
	public function calculteRate () {
		Util::dump("ltv cc adjust", $this->getLtvCcAdj());
		Util::dump("ltv cc pmi adjust",$this->getLtvCcPmiAdj());
		Util::dump("ltv other adjust",$this->getLtvOtherAdj());
		Util::dump("Find purchaser SRP", $this->getSRP());

		echo "<br> Calculate purchaser $this->purchaserId with margin ". $this->property->margin . " % <br>";
		$this->getPurchaseRate();
	}
	
	
}

?>