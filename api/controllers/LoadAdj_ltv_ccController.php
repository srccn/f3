<?php
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
class ExcelLoaderController extends BaseController {

	private $excel_file;
	private $excel_map_file;
	private $dataMap;
	private $purchaserID;
	private $insert_data= array();
	private $bank_symbol;
	
	public function pushDataToDB_purchase() {
		$query = "INSERT INTO loaner.purchase ( purchaser_id, loan_type_id, rate, lock_days_id, purchase_price) VALUES "
				   .implode(",",$this->insert_data) ;
		
		$result = $this->db->exec($query);
		if( $result ){	echo 'Query executed successfully.', EOL ; return true;}
		else { 	echo 'Query execution failed.', EOL; return false;}
	}

	public function pushDataToDB_adj_ltv_cc() {
		$query = "INSERT INTO loaner.adj_ltv_cc (purchaser_id, ltv_value, cc_value, adjust) VALUES "
				   .implode(",",$this->insert_data) ;
		
		$result = $this->db->exec($query);
		if( $result ){	echo 'Query executed successfully.', EOL ; return true;}
		else { 	echo 'Query execution failed.', EOL; return false;}
	}
	
	
	
	public function removeDataInDB() {
		$this->removeDataInDBByPurchaserID($this->getPurchaserID());
	}
	
	public function removeDataInDBByPurchaserID($purchaserID) {
		
		$query = "delete FROM loaner.purchase where purchaser_id=".$purchaserID ;
		
		$result = $this->db->exec($query);
		if( $result ){	echo 'remove successfully.', EOL; return true;}
		else { 	echo 'data removing failed.', EOL; return false;}
		
	}

	public function loadRateDataFromExcel(){
	  //check existance of excel file
	  if (!file_exists($this->excel_file)) {
	    exit("Excel file $this->excel_file not found." . EOL);
	  }
	  //check existance of excel map file
	  if (!file_exists($this->excel_map_file)) {
	    exit("Excel mape file  $this->excel_map_file not found." . EOL);
	  }
		
      require_once $this->excel_map_file;
      $mydata = new $this->bank_symbol;
      
      //require_once '/lib/Classes/PHPExcel/IOFactory.php';
      require_once 'lib/Classes/PHPExcel.php';
      $objPHPExcel = PHPExcel_IOFactory::load($this->excel_file);

      $mydatamap = $mydata->getMap();
      $purchaser_id = $mydata->getPurchaserId();
      $products = array_keys($mydata->getMap());
      $products_count = count($products);
      
	  //populate data into $insert_data arrya
	  for ($i=0; $i < $products_count; $i++ ){
	      $loan_type =  $mydatamap[$products[$i]]["loan_type"];
	      $worksheet = $mydatamap[$products[$i]]['sheetName'];
	      $range= $mydatamap[$products[$i]]['range'];
	      $objPHPExcel->setActiveSheetIndexByName($worksheet);
	      $result = $objPHPExcel->getActiveSheet()->rangeToArray($range,NULL,TRUE,FALSE);
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
      
	}
	
	public function loadAdjDataFromExcel () {
        //check existance of excel file
	    if (!file_exists($this->excel_file)) {
	        exit("Excel file $this->excel_file not found." . EOL);
	    }
	    //check existance of excel map file
	    if (!file_exists($this->excel_map_file)) {
	        exit("Excel mape file  $this->excel_map_file not found." . EOL);
	    }
		
		require_once $this->excel_map_file;
        $mydata = new $this->bank_symbol;
      
        //require_once '/lib/Classes/PHPExcel/IOFactory.php';
        require_once 'lib/Classes/PHPExcel.php';
        $objPHPExcel = PHPExcel_IOFactory::load($this->excel_file);
				
		$mydatamap = $mydata->getAdjMap();
	    $purchaser_id = $mydata->getPurchaserId();
		$adjusts = array_keys($mydata->getAdjMap());
		$adjusts_count = count($adjusts);
		for ($i=0; $i < $adjusts_count; $i++) {
		    $worksheet = $mydatamap[$adjusts[$i]]['sheetName'];
			$range= $mydatamap[$adjusts[$i]]['range'];
			$objPHPExcel->setActiveSheetIndexByName($worksheet);
	        $result = $objPHPExcel->getActiveSheet()->rangeToArray($range,NULL,TRUE,FALSE);
			$result_count = count ($result);
            for ($j=0; $j < $result_count; $j++) {
                //compose array purchaser_id, ltv_value, cc_value, adjust
                $cc_value = $mydatamap[$adjusts[$i]]['cc'][$j];
		        $ltvs = $mydatamap[$adjusts[$i]]['ltv'];
                $ltvs_count = count($ltvs);
                $scan = 0;
			    for ($k=0;$k<$ltvs_count;$k++){
                    while ( $result[$j][$scan] == null) {
                        $scan++ ;
                        break;
                    } 
                    $adjust = $result[$j][$scan];
                    $scan++;
                    //compose array purchaser_id, ltv_value, cc_value, adjust 
                    //echo $purchaser_id . "," . $mydatamap[$adjusts[$i]]['ltv'][$k] . "," . $cc_value . ",". $adjust . "<br>" ;
					$insert_row = [ $purchaser_id, $mydatamap[$adjusts[$i]]['ltv'][$k], $cc_value, $adjust] ;
		            $insert_row_string = "(" . implode(",", $insert_row) . ")" ;
		            array_push($this->insert_data, $insert_row_string);
                }//$k each row read
            } // $j for each row
		}//$i each of the adjustsment
	}
	
	public function setExcelFile($fileName) {
		$this->excel_file = $fileName;
	}
	public function setExcelMapFile($fileName) {
		$this->excel_map_file = $fileName;
	}
	
	public function setBankSymbol($symbol) {
		$this->bank_symbol = $symbol;
	}
	
	public function getPurchaserID(){
		require_once $this->excel_map_file;
        $mydata = new $this->bank_symbol ;
		return $purchaser_id = $mydata->getPurchaserId();
	}
	
	
	public function test () {
        $myLoader = new ExcelLoaderController;
        $myLoader->setExcelFile("data/BBT.xls");
        $myLoader->setExcelMapFile("data/BBT.php");
        $myLoader->setBankSymbol("BBT");
        $myLoader->removeDataInDB();
        $myLoader->loadRateDataFromExcel();
        $myLoader->pushDataToDB_purchase();
        unset($myLoader);
        unset($DataMap);

        //BOKF
        $bokfLoader = new ExcelLoaderController;
        $bokfLoader->setExcelFile("data/BOKF CMS Rate Sheet.xlsx");
        $bokfLoader->setExcelMapFile("data/BOKF.php");
        $bokfLoader->setBankSymbol("BOKF");
        $bokfLoader->removeDataInDB();
        $bokfLoader->loadRateDataFromExcel();
        $bokfLoader->pushDataToDB_purchase();
		unset($bokfLoader);
	}
	
	public function test_adj() {
        $bokfLoader = new ExcelLoaderController;
        $bokfLoader->setExcelFile("data/BOKF CMS Rate Sheet.xlsx");
        $bokfLoader->setExcelMapFile("data/BOKF.php");
        $bokfLoader->setBankSymbol("BOKF");
	    $bokfLoader->loadAdjDataFromExcel();
	    $bokfLoader->pushDataToDB_adj_ltv_cc();
	}
	
}

?>
