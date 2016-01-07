<?php

class UserInputForm extends DB\SQL\Mapper {
	
	public function __construct(DB\SQL $db) {
		parent::__construct($db,'user_input_form');
	}
	
	public function add() {
		$this->copyFrom('POST');
		$this->save();
	}	
}

?>