<?php
class CustomerController extends BaseController {

    public $customerName;
	public $customerEmail;
	public $customerPhone;
	public $customerCreditScore;
	
	
	function setCreditScore($score){
	    $this->customerCreditScore = $score;
	}

	function getCreditScore(){
	    return $this->customerCreditScore;
	}
	
	function getCustomerInfoByEmail($email) {
	    $results = $this->db-exec("select name, email, phone, credit_score from customer where email='$email'");
		//var_dump ( $results ) ;
		if ( ! $results ) {
		    die ("not found customer with email " . $email);
		} else {
			$this->customerName        = $resutls[0]['name'];
			$this->customerEmail       = $results[0]['email'];
			$this->customerPhone       = $results[0]['phone'];
			$this->customerCreditScore = $results[0]['credit_score'];
		}
	}
}

?>
