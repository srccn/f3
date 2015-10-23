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

	function beforeRoute() {
		if ($this->f3->SESSION['user'] != 'bfang') {
			$this->f3->reroute('/home');
		}
	}	
	
	
	public function index()
	{

// 		if ($this->f3->SESSION['user'] != 'bfang') {
// 			$this->f3->reroute('/home');
// 		}
		
		$user = new Customer($this->db);
		$this->f3->set('users',$user->all());
		$this->f3->set('page_head','Cusotmer List');
		$this->f3->set('view','customer/list.htm');
		
		$_SESSION["id"] = "sriuthgsilgdfisgnaweoir239-57ef";
		
		var_dump($_SESSION);
		
		echo Template::instance()->render('layout.htm');
	}
	
	public function create()
	{
		if($this->f3->exists('POST.create'))
		{
			$user = new Customer($this->db);
			$user->add();
	
			$this->f3->reroute('/customer');
	
		} else
		{
			$this->f3->set('page_head','Create Customer');
			$this->f3->set('view','customer/create.htm');
		}
		echo Template::instance()->render('layout.htm');
	}
	
	public function update()
	{
		$user = new Customer($this->db);
	
		if($this->f3->exists('POST.update'))
		{
			$user->edit($this->f3->get('POST.id'));
			$this->f3->reroute('/customer');
	
		} else
		{
			$user->getById($this->f3->get('PARAMS.id'));
			$this->f3->set('user',$user);
			$this->f3->set('page_head','Update Customer');
			$this->f3->set('view','customer/update.htm');
		}
		echo Template::instance()->render('layout.htm');
	}
	
	public function delete()
	{
		if($this->f3->exists('PARAMS.id'))
		{
			$user = new Customer($this->db);
			$user->delete($this->f3->get('PARAMS.id'));
		}
	
		$this->f3->reroute('/customer');
	}
	
}

?>
