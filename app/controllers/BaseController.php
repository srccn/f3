<?php

class BaseController {

	protected $f3;
	protected $db;
	protected $queryLogger;
		
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
		$this->queryLogger = new Log($f3->get('QUERY_LOGFILE'));
	}
	
	function runQuery($query){
		$result = null;
		try {
			if ( $this->f3->get('LOG_QUERY') ) {
		        $this->queryLogger->write(" --- Query : " . $query );
			}
			$result = $this->db->exec($query);
		}catch (\Exception $e) {
			$this->queryLogger->write($e->getMessage());
			throw new Exception("Exception when run query : ");
		}
		
		if (! $result) {
           // throw new Exception ("Query result is null : " .$query);			
		}
		return $result;
	}
	
	function beforeRoute() {
        ;
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