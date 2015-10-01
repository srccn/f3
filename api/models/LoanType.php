<?php

class LoanType extends \DB\SQL\Mapper {

	public function __construct(DB\SQL $db) {
        parent::__construct($db,'loan_type');
    }
	
	public function all() {
        $this->load();
        return $this->query;
    }
	
	public function getIDByNameAndConfirming($name, $confirming) {
        $result = $this->load(array('type_variable_name like ? and confirming=?', "%$name%", $confirming));
        return ($result['loan_type_id']);
    }

}
?>