<?php

class ViewRecord extends BaseController {
	public $product;
	public $purchaser;
	public $purchaserId;
	public $loanAmount;
	public $rate=0;
	public $rate_lowrate;
	public $rate_lowlowrate;
	public $price;
	public $loanTypeId;
	public $loanTerm;
	public $price_highrate;    //price at par rate - 1/8 
	public $price_lowrate;    //price at par rate - 1/8 
	public $price_lowlowrate; //price ar par rate - 1/4
	public $credit;
	public $credit_highrate;
	public $credit_lowrate;
	public $credit_lowlowrate;
	public $lockDays;
	public $lockDays_highrate;
	public $lockDays_lowrate;
	public $lockDays_lowlowrate;
	public $monthlyPayment;
	public $monthlyPayment_highrate;
	public $monthlyPayment_lowrate;
	public $monthlyPayment_lowlowrate;
	public $margin;
	public $minCredit;
	public $adjusts;
	public $SRP;
	
	public function __construct() {
		parent::__construct();
		$this->product = null;
	}
	
    public function populateLowRateData () {
    	
    	
    	if (isset ($this->product) && 
    			isset($this->loanAmount) && $this->loanAmount > 0 &&
    			isset($this->purchaser) && 
    			isset($this->credit) 
    	){
    		$query = " SELECT rate, lock_days_id lockDays, max(purchase_price) price
    		             FROM purchase
    		            WHERE purchaser_id = $this->purchaserId
    		              and lock_days_id >= $this->lockDays
				          and loan_type_id = $this->loanTypeId
				     group by rate
				       having rate <= $this->rate + 0.125
				          and rate <> $this->rate
				     order by rate desc 
				        limit 2
				     ";
    		
    		$result = $this->runQuery($query);
    		
    		$this->price_lowrate = $result[0]['price'];
    		$this->price_lowlowrate = $result[1]['price'];
    		
    		$this->lockDays_lowrate = $result[0]['lockDays'];
    		$this->lockDays_lowlowrate = $result[1]['lockDays'];

    		$this->rate_lowrate = $result[0]['rate'];
    		$this->rate_lowlowrate = $result[1]['rate'];

    		$this->credit_lowrate = round ( $this->credit + $this->loanAmount * ($this->price_lowrate - $this->price ) / 100 , 0);
    		$this->credit_lowlowrate = round ( $this->credit + $this->loanAmount * ($this->price_lowlowrate - $this->price ) / 100, 0);

    		$this->monthlyPayment_lowrate = 
    		        sprintf ('%0.2f' ,Util::calMonthlyPayment($this->loanAmount, $this->rate_lowrate, $this->loanTerm) );
    		$this->monthlyPayment_lowlowrate = 
    		        sprintf ('%0.2f' ,Util::calMonthlyPayment($this->loanAmount, $this->rate_lowlowrate, $this->loanTerm) );
    	
    	}else {
    		echo "not ready to calculate lower rate data";
    	}
    }
	
}

?>