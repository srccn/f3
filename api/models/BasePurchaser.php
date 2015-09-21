<?php

abstract class BasePurchaser {
    
    private $loanerName=;
	private $loaner_id =;
	private $lockdays = [] ;

    abstract function getProductMap(); //return an array of data location Map
    abstract function setPurchaserName($name);
    abstract function setLockDays($daysArray);
    
    public function isNameValid(){
        $isValid = false;
        
        return $isValid;
    }
    
    public function getPurchaserIdByName ($name) {
        $id = 1;
        
        return $id;
    }
    
    public function getPurchaserId(){
    	return $this->loaner_id;
    }
    
    public function getLockDays() {
    	return $this->lockdays;
    }
    
    public function getAllPurchaserNames() {
    
    }
}

?>