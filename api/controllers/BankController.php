<?php

class BankController extends BaseController {

	private $BankName;
	private $BankId;
	
	function getBankIdByName() {
		$name = $this->f3->get('PARAMS.BankName');
	    $result = $this->db->exec("select purchaser_id as result from purchaser where purchaser_name='$name'");
	    //var_dump( $result );
		return Util::resultString($result);
	}
	
	function getAllBankNames(){
	    $result = $this->db->exec("select purchaser_name as result from purchaser");
	    //var_dump($result);
		return Util::resultString($result);
	}
    
    function test() {
        $this->f3->set("PARAMS.BankName","BBT");
        var_dump($this->getAllBankNames());
        var_dump($this->getBankIdByName());
    }
}

?>