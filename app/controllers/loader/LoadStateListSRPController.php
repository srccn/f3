<?php

class LoadStateListSRPController extends AbstractLoadController{
	
	private $insert_data= array();
	private $DBTableName = "loaner.state_srp_full_list";	
	
	
	public function pushDataToDB() {
		var_dump($this->insert_data);
		$query = "INSERT INTO $this->DBTableName 
		          ( purchaser_id, loan_type_id, escrow, start_amount,end_amount, state, srp) VALUES "
				   .implode(",",$this->insert_data) ;
		$result = $this->runQuery($query);
	}
	
	public function removeDataInDB() {
		$query = "delete FROM $this->DBTableName where purchaser_id=".$this->purchaserID ;
		$result = $this->runQuery($query);
	}
	
	public function loadDataFromExcel() {
      $this->insert_data=[];
      $myMap = $this->mapData->getStateListSRPMap();
      $selectedStates = $myMap["selectedStates"];
      $amountRange    = $myMap["amountRange"];
      $amountAdj      = $myMap["amountAdj"];
      $mydatamap = $myMap["products"];

      $purchaser_id = $this->purchaserID;
      $products = array_keys($mydatamap);
      $products_count = count($products);
      
	  //populate data into $insert_data arrya
	  for ($i=0; $i < $products_count; $i++ ){
	      $stateCol =  $mydatamap[$products[$i]]["stateCol"];
	      $worksheet = $mydatamap[$products[$i]]['sheetName'];
	      $range= $mydatamap[$products[$i]]['range'];
	      $this->objPHPExcel->setActiveSheetIndexByName($worksheet);
	      $result = $this->objPHPExcel->getActiveSheet()->rangeToArray($range,NULL,TRUE,FALSE);
	      
	      $result=Util::cleanTable($result);
	      $loan_type_id = $mydatamap[$products[$i]]['loan_type_id'];
	      $escrow = $mydatamap[$products[$i]]['escrow'];
	       
	      $result_count = count($result);
	      for ($j=0; $j< $result_count; $j++) { //each rate row
		      //echo implode(",", $result[$j]) , EOL;
		      $stateRead = $result[$j][0];
		      
		      $state = $this->ifStringConstainsArrayElement($selectedStates, $stateRead);
		      
		      if ($state) { //only pickup rows that is in state selected
		          $amountRange_count = count($amountRange);
		          for ( $k=0; $k < $amountRange_count; $k++ ) { //each lock days column
		              $insert_row = [ $purchaser_id, $loan_type_id, $escrow, "'".$amountRange[$k][0]."'", "'".$amountRange[$k][1]."'" , "'".$state."'", round($result[$j][$k+1] , 3) ] ;
		              $insert_row_string = "(" . implode(",", $insert_row) . ")" ;
		              array_push($this->insert_data, $insert_row_string);
		          } //for $k
		      }
	      }//for $j
		  
      } //for $i
	  //in case we need to derive price for lock 45 days price from baseLockDays		;
	}
	
	private function ifStringConstainsArrayElement($array, $checkString ) {
		foreach ($array as $arrayElement) {
			if ( strpos($checkString,$arrayElement) !== false ){
				return $arrayElement;
			}
		}
		return false;
	}

}

?>