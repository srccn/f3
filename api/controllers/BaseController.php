<?php

class BaseController {

	protected $f3;
	protected $db;
		
    function __construct () {
		$f3 = Base::instance();
	    $db=new DB\SQL($f3->get('db_dns') . $f3->get('db_name'), 
							  $f3->get('db_user'), 
							  $f3->get('db_pass')
							  );
		if (!$db) {
		    die("Database object createion failed.");
		}
		$this->f3 = $f3;
		$this->db = $db;
	}

}


?>