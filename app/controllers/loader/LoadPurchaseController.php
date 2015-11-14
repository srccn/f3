<?php
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
class LoadPurchaseController extends AbstractLoadController {

	private $insert_data= array();
	private $DBTableName = "purchase";
	private $AdjDBTableName = "purchase_lockdays_adj";
	
	public function pushDataToDB() {
		$query = "INSERT INTO $this->DBTableName ( purchaser_id, loan_type_id, rate, lock_days_id, purchase_price) VALUES "
				   .implode(",",$this->insert_data) ;
		
		$result = $this->runQuery($query);
		if( $result ){	echo 'Push price Data executed successfully.', EOL ; return true;}
		else { 	echo 'Push price Data execution failed.', EOL; return false;}
	}

	public function removeDataInDB() {
		$query = "delete FROM $this->DBTableName where purchaser_id=".$this->purchaserID ;
		
		$result = $this->runQuery($query);
		if( $result ){	echo 'purchase data remove successfully.', EOL; return true;}
		else { 	echo 'Purchase data removing failed.', EOL; return false;}
	}

	public function pushAdjDataToDB() {
		$query = "INSERT INTO $this->AdjDBTableName ( purchaser_id, loan_type_id, adjust ) VALUES "
		.implode(",",$this->insert_data) ;
	
		$result = $this->runQuery($query);
		if( $result ){	echo 'Push adj Data executed successfully.', EOL ; return true;}
		else { 	echo 'PushData execution failed.', EOL; return false;}
	}
	
	public function removeAdjDataInDB() {
		$query = "delete FROM $this->AdjDBTableName where purchaser_id=".$this->purchaserID ;
		
		$result = $this->runQuery($query);
		if( $result ){	echo 'Adj data remove successfully.', EOL; return true;}
		else { 	echo 'Adj data removing failed.', EOL; return false;}
	}
	

	public function loadDataFromExcel(){
      $this->insert_data=[];
      $mydatamap = $this->mapData->getMap();
      $purchaser_id = $this->purchaserID;
      $products = array_keys($mydatamap);
      $products_count = count($products);
      
	  //populate data into $insert_data arrya
	  for ($i=0; $i < $products_count; $i++ ){
	      $loan_type =  $mydatamap[$products[$i]]["loan_type"];
	      $worksheet = $mydatamap[$products[$i]]['sheetName'];
	      $range= $mydatamap[$products[$i]]['range'];
	      $this->objPHPExcel->setActiveSheetIndexByName($worksheet);
	      $result = $this->objPHPExcel->getActiveSheet()->rangeToArray($range,NULL,TRUE,FALSE);
	      $result = Util::cleanTable($result);

	      $result_count = count($result);
	      for ($j=0; $j< $result_count; $j++) { //each rate row
		      //echo implode(",", $result[$j]) , EOL;
		      $lock_days = $mydatamap[$products[$i]]['lock_days'];
		      $lock_days_count = count($lock_days);
		      for ( $k=0; $k < $lock_days_count; $k++ ) { //each lock days column
		          $insert_row = [ $purchaser_id, $loan_type, $result[$j][0], $lock_days[$k], round($result[$j][$k+1] , 3) ] ;
		          $insert_row_string = "(" . implode(",", $insert_row) . ")" ;
		          array_push($this->insert_data, $insert_row_string);
		      } //for $k
	      }//for $j
		  
      } //for $i
	  //in case we need to derive price for lock 45 days price from baseLockDays
	}
	
    public function addPriceAdjData () {
    	if (! method_exists($this->mapData, 'purchaseLockDayAdj45') ){  
	        return;
	    }
    	//update adj data into purchase_lockdays_adj
		$this->removeAdjDataInDB();
    	$this->loadAdjData();
	    $this->pushAdjDataToDB();
	    
		//execute query to add price data in table 
		$query = "SELECT purchaser_id, loan_type_id, adjust 
				  FROM purchase_lockdays_adj
				  WHERE purchaser_id = $this->purchaserID ";
		$result = $this->runQuery($query);
		foreach ($result as $row) {
			$updateQuery = $this->mapData->purchaseLockDayAdj45($row['loan_type_id'],$row['adjust']);
			$this->runQuery($updateQuery);	
		}
	
	}
	
	public function loadAdjData () {
		//cleanup old data
		
		$this->insert_data=[];
	    $mydatamap = $this->mapData->getPriceAdjMap();
		$purchaser_id = $this->purchaserID;
		$products = array_keys($mydatamap);
		$products_count = count($products);
		$myLoanType = new LoanType($this->db);
		
	  for ($i=0; $i < $products_count; $i++ ){
	      $loan_type_ids =  $mydatamap[$products[$i]]["loan_type_id"]; //this is an array of loan types 
	      $worksheet = $mydatamap[$products[$i]]['sheetName'];
	      $range= $mydatamap[$products[$i]]['range'];
		  $confirming = $mydatamap[$products[$i]]['confirming'];
	      $lock_days  = $mydatamap[$products[$i]]['lock_day'];
	      $this->objPHPExcel->setActiveSheetIndexByName($worksheet);
	      $result = $this->objPHPExcel->getActiveSheet()->rangeToArray($range,NULL,TRUE,FALSE);

		  $result_count = count($result);
	      for ($j=0; $j< $result_count; $j++) { //each rate row
	      	  if ($loan_type_ids[$j] > 0 ) { //skip minus id  
		          $insert_row = [ $purchaser_id, $loan_type_ids[$j], $result[$j][0] ] ;
			      $insert_row_string = "(" . implode(",", $insert_row) . ")" ;
			      array_push($this->insert_data, $insert_row_string);
	      	  } // if
	      }//for $j
      } //for $i
	}		
	
	public function reloadData() {
		parent::reloadData();
		$this->addPriceAdjData();
	}
	
	
}

?>
