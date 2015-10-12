<?php

class BasePurchaser {
	
//	protected $purchaserName="Purchaser name matchs DB registed name";
//	protected $purchaserId = 1 //Purchaser id ;
//	protected $excelFile = "excel spread sheet data file";
	
	private $property;
	
	public function getPurchaserId(){
    	return $this->purchaserId;
    }
    
    public function getExcelFile() {
    	return $this->excelFile;
    }
	public function getPurchaserName(){
    	return $this->purchaserName;
    }
	
    public function setProperty(PropertyController $p) {
    	$this->property = $p;
    }



}

?>