<?php

class BankController extends BaseController {

	private $BankName;
	private $BankId;
	
	function getBankIdByName() {
		$name = $this->f3->get('PARAMS.BankName');
	    $result = $this->db->exec("select purchaser_id from purchaser where purchaser_name='$name'");
	    var_dump( $result );
		return $result;
	}
	
	function getAllBankNames(){
	    $result = $this->db->exec("select purchaser_name from purchaser");
	    var_dump($result);
		return $result;
	}

}

?>