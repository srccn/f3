<?php
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
class LoadPurchaseController extends AbstractLoadController {

	private $insert_data= array();
	private $DBTableName = "loaner.purchase";

	public function pushDataToDB() {
		$query = "INSERT INTO $this->DBTableName ( purchaser_id, loan_type_id, rate, lock_days_id, purchase_price) VALUES "
				   .implode(",",$this->insert_data) ;
		
		$result = $this->db->exec($query);
		if( $result ){	echo 'PushData executed successfully.', EOL ; return true;}
		else { 	echo 'PushData execution failed.', EOL; return false;}
	}

	public function removeDataInDB() {
		$this->removeDataInDBByPurchaserID($this->getPurchaserID());
	}
	
	public function removeDataInDBByPurchaserID($purchaserID) {
		
		$query = "delete FROM $this->DBTableName where purchaser_id=".$purchaserID ;
		
		$result = $this->db->exec($query);
		if( $result ){	echo 'remove successfully.', EOL; return true;}
		else { 	echo 'data removing failed.', EOL; return false;}
		
	}

	public function loadDataFromExcel(){

      $mydatamap = $this->mapData->getMap();
      $purchaser_id = $this->getPurchaserId();
      $products = array_keys($mydatamap);
      $products_count = count($products);
      
	  //populate data into $insert_data arrya
	  for ($i=0; $i < $products_count; $i++ ){
	      $loan_type =  $mydatamap[$products[$i]]["loan_type"];
	      $worksheet = $mydatamap[$products[$i]]['sheetName'];
	      $range= $mydatamap[$products[$i]]['range'];
	      $this->objPHPExcel->setActiveSheetIndexByName($worksheet);
	      $result = $this->objPHPExcel->getActiveSheet()->rangeToArray($range,NULL,TRUE,FALSE);
	      $result_count = count($result);
	      for ($j=0; $j< $result_count; $j++) { //each rate row
		      //echo implode(",", $result[$j]) , EOL;
		      $lock_days = $mydatamap[$products[$i]]['lock_days'];
		      $lock_days_count = count($lock_days);
		      for ( $k=0; $k < $lock_days_count; $k++ ) { //each lock days column
		          $insert_row = [ $purchaser_id, $loan_type, $result[$j][0], $lock_days[$k], $result[$j][$k+1] ] ;
		          $insert_row_string = "(" . implode(",", $insert_row) . ")" ;
		          array_push($this->insert_data, $insert_row_string);
		      } //for $k
	      }//for $j
		  
      } //for $i
	  //in case we need to derive lock 45 days price from baseLockDays
	  if ( method_exists($this->mapData, 'purchaseLockDayAdj45') ){  
	      $this->addPriceAdjData($loan_type, 45);
	  }
	}
	
    public function addPriceAdjData ($loan_type_id, $lockDays) {
	    //update adj data
		
		//find if confirming from loan type
		
		//if confirming find confirming adj
		
		//get purchaseLockDayAdj45 query use (load_type and lock days)
		
		//execute query to add data
	
	}
	
	public function addAdjData () {
	    $mydatamap = $this->mapData->getPriceAdjMap();
		$purchaser_id = $this->getPurchaserId();
		$products = array_keys($mydatamap);
		$products_count = count($products);
		$myLoanType = new LoanType($this->db);
		
	  for ($i=0; $i < $products_count; $i++ ){
	      $loan_type =  $mydatamap[$products[$i]]["loan_type"]; //this is an array of loan types 
	      $worksheet = $mydatamap[$products[$i]]['sheetName'];
	      $range= $mydatamap[$products[$i]]['range'];
		  $confirming = $mydatamap[$products[$i]]['confirming'];
	      $lock_days  = $mydatamap[$products[$i]]['lock_day'];
	      $this->objPHPExcel->setActiveSheetIndexByName($worksheet);
	      $result = $this->objPHPExcel->getActiveSheet()->rangeToArray($range,NULL,TRUE,FALSE);

		  $result_count = count($result);
	      for ($j=0; $j< $result_count; $j++) { //each rate row
		          $insert_row = [ $loan_type[$j], $result[$j][0] ] ;
			      $insert_row_string = "(" . implode(",", $insert_row) . ")" ;
			      array_push($this->insert_data, $insert_row_string);
	      }//for $j
		  
      } //for $i
	   var_dump($this->insert_data);	
	}		
	
	
}

?>
