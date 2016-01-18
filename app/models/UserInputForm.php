<?php

class UserInputForm extends DB\SQL\Mapper {
	
	public function __construct(DB\SQL $db) {
		parent::__construct($db,'user_input_form');
	}
	
	public function add() {
		$this->copyFrom('POST');
		$this->save();
	}
	
	public function edit($userId) {
		$this->load(array('user_id=?',$userId));
		$this->copyFrom('POST');
		$this->isAlertActive=1;
		$this->loanNameSelection= implode(",", $_POST['loanNameSelection']);
		if (! $this->dry()) {
			$this->update();
		} else {
			$this->user_id = $userId;
			$this->save();
				
		}
	}
	
	public function getByUserId($userId) {
		$this->load(array('user_id=?',$userId));
		$this->copyto('myForm');
	}
	
}

?>