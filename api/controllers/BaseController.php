<?php

class BaseController {

	protected $f3;
	protected $db;
	protected $logger;
		
    function __construct () {
		$f3 = Base::instance();
	    $db=new DB\SQL($f3->get('db_dns') . $f3->get('db_name'), 
							  $f3->get('db_user'), 
							  $f3->get('db_pass')
							  );
		if (!$db) {
		    die("Database object createion failed.");
		}
		
		$logger = new Log('error.log');
		
		$this->f3 = $f3;
		$this->db = $db;
		$this->logger = $logger;
	}
	
	function runQuery($query){
		$result = null;
		try {
		    $result = $this->db->exec($query);
		}catch (\Exception $e) {
			$this->logger->write($e->getMessage());
			echo ("Exception when executing query : " . $query ."<br>");
		}
		return $result;
	}
	
	function beforeRoute() {
		if (! $this->authenticationCheck() ) {
			die ("Please provide user name and password to enter.");
		}
	}
	
	function afterRoute() {
		;
	}
	
	
	function authenticationCheck() {
		$user = new \DB\SQL\Mapper($this->db, 'user');
		$auth = new \Auth($user, array('id'=>'name', 'pw'=>'password'));
		$loginResult = $auth->basic();
		return $loginResult;
	}

}


?>