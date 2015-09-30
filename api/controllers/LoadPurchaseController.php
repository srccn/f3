<?php
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
class LoadPurchaseController extends AbstractLoadController {

	private $insert_data= array();
	private $DBTableName = "loaner.purchase";

	public function pushDataToDB() {
		$query = "INSERT INTO $DBTableName ( purchaser_id, loan_type_id, rate, lock_days_id, purchase_price) VALUES "
				   .implode(",",$this->insert_data) ;
		
		$result = $this->db->exec($query);
		if( $result ){	echo 'PushData executed successfully.', EOL ; return true;}
		else { 	echo 'PushData execution failed.', EOL; return false;}
	}

	public function removeDataInDB() {
		$this->removeDataInDBByPurchaserID($this->getPurchaserID());
	}
	
	public function removeDataInDBByPurchaserID($purchaserID) {
		
		$query = "delete FROM $DBTableName where purchaser_id=".$purchaserID ;
		
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
      unset($this->objPHPExcel);
	}
	
	public function test () {
		
		//BBT
        //$myLoader = new LoadPurchaseController;
        //$this->setExcelMapFile("data/BBT.php");
        //$this->reloadData();
        //unset($myLoader);
		
/*		
        //BOKF
        $bokfLoader = new ExcelLoaderController;
        $bokfLoader->setExcelFile("data/BOKF CMS Rate Sheet.xlsx");
        $bokfLoader->setExcelMapFile("data/BOKF.php");
        $bokfLoader->setBankSymbol("BOKF");
        $bokfLoader->removeDataInDB();
        $bokfLoader->loadRateDataFromExcel();
        $bokfLoader->pushDataToDB_purchase();
		unset($bokfLoader);
*/
    }

}

?>
