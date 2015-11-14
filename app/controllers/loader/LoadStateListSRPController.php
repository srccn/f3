<?php

class LoadStateListSRPController extends AbstractLoadController{
	
	private $insert_data= array();
	private $DBTableName = "state_srp_full_list";	
	
	
	public function pushDataToDB() {
		//var_dump($this->insert_data);
		$query = "INSERT INTO $this->DBTableName 
		          ( purchaser_id, loan_type_id, escrow, start_amount,end_amount, state, srp) VALUES "
				   .implode(",",$this->insert_data) ;
		$result = $this->runQuery($query);
		if( $result ){	echo "Push $DBTableName executed successfully.", EOL ; return true;}
		else { 	echo "Push $DBTableName execution failed.", EOL; return false;}
	}
	
	public function removeDataInDB() {
		$query = "delete FROM $this->DBTableName where purchaser_id=".$this->purchaserID ;
		$result = $this->runQuery($query);
		if( $result ){	echo "$DBTableName remove successfully.", EOL; return true;}
		else { 	echo "$DBTableName removing failed.", EOL; return false;}
	}
	
	public function loadDataFromExcel() {
      $this->insert_data=[];
      $myMap = $this->mapData->getStateListSRPMap();
      $selectedStates = $myMap["selectedStates"];
      $amountRange    = $myMap["amountRange"];
//      $amountAdj      = $myMap["amountAdj"];
      $mydatamap = $myMap["products"];

      $purchaser_id = $this->purchaserID;
      $products = array_keys($mydatamap);
      $products_count = count($products);
      
	  //populate data into $insert_data arrya
	  for ($i=0; $i < $products_count; $i++ ){
	      $stateRange =  $mydatamap[$products[$i]]["stateCol"];
	      $worksheet = $mydatamap[$products[$i]]['sheetName'];
	      $range= $mydatamap[$products[$i]]['range'];
	      $amountAdjRange= $mydatamap[$products[$i]]['amountAdjRange'];
	      $amountAdj= $mydatamap[$products[$i]]['amountAdj'];
	      
	      $this->objPHPExcel->setActiveSheetIndexByName($worksheet);
	      $result = $this->objPHPExcel->getActiveSheet()->rangeToArray($range,NULL,TRUE,FALSE);
	      $result=Util::cleanTable($result);
	      
	      //get state list
	      $stateListResult = $this->objPHPExcel->getActiveSheet()->rangeToArray($stateRange,NULL,TRUE,FALSE);
	      $stateListResult = Util::cleanTable($stateListResult);
	      $stateListResult = Util::getTableColumn(0, $stateListResult);
	      
	      //merge state list with srp result
	      $result = Util::prependColumn($stateListResult, $result);
	      
	      if (($amountAdj !=null ) && (strlen(trim($amountAdj) ) > 0) ) { //in case $amountAdjRange is defined, load in range for this product
	      	    $rangeAdjustResult = $this->objPHPExcel->getActiveSheet()->rangeToArray($amountAdj,NULL,TRUE,FALSE);
	      	    $rangeAdjustResult = Util::cleanTable($rangeAdjustResult);
	      	    $rangeAdjustResult = Util::getTableColumn(0, $rangeAdjustResult);
	      }

	      $loan_type_id = $mydatamap[$products[$i]]['loan_type_id'];
	      $escrow = $mydatamap[$products[$i]]['escrow'];
	       
	      $result_count = count($result);
	      for ($j=0; $j< $result_count; $j++) { //each rate row
		      //echo implode(",", $result[$j]) , EOL;
		      $stateRead = $stateListResult[$j];
		      
		      $state = $this->ifStringConstainsArrayElement($selectedStates, $stateRead);
		      
		      if ($state) { //only pickup rows that is in state selected
    		      $amountRange_count = count($amountAdjRange);
		          //case1 - range adjusted table
		          if (($amountAdj === null ) || (strlen(trim ($amountAdj) ) === 0)) { 
		          	for ( $k=0; $k < $amountRange_count; $k++ ) { //each amount range column
		                  $insert_row = [ $purchaser_id, $loan_type_id, $escrow, "'".$amountAdjRange[$k][0]."'", "'".$amountAdjRange[$k][1]."'" , "'".$state."'", round($result[$j][$k+1] , 3) ] ;
		                  $insert_row_string = "(" . implode(",", $insert_row) . ")" ;
		                  array_push($this->insert_data, $insert_row_string);
		              } //for $k
		          } else {	//case2 base + range adjust
		          	for ( $k=0; $k < $amountRange_count; $k++ ) { //each amount range column
		          		$amountRange_count = count($amountAdjRange);
		          		$insert_row = [ $purchaser_id, $loan_type_id, $escrow, "'".$amountAdjRange[$k][0]."'", "'".$amountAdjRange[$k][1]."'" , "'".$state."'", round($result[$j][1] + $rangeAdjustResult[$k] , 3) ] ;
		          		$insert_row_string = "(" . implode(",", $insert_row) . ")" ;
		          		array_push($this->insert_data, $insert_row_string);
		          	} //for $k
		          			     
		          }
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