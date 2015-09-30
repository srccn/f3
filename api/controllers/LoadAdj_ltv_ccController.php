<?php
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
class LoadAdj_ltv_ccController extends AbstractLoadController {

	private $insert_data= array();
	private $DBTableName = "loaner.adj_ltv_cc";

	
	public function pushDataToDB() {
		$query = "INSERT INTO $this->DBTableName (purchaser_id, ltv_value, cc_value, adjust) VALUES "
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

	
	public function loadDataFromExcel () {

        $mydatamap = $this->mapData->getMap();
        $purchaser_id = $this->getPurchaserId();
        $products = array_keys($mydatamap);
        $products_count = count($products);
		
				
		$mydatamap = $this->mapData->getAdjMap();
	    $purchaser_id = $this->getPurchaserId();
		$adjusts = array_keys($mydatamap);
		$adjusts_count = count($adjusts);
		for ($i=0; $i < $adjusts_count; $i++) {
		    $worksheet = $mydatamap[$adjusts[$i]]['sheetName'];
			$range= $mydatamap[$adjusts[$i]]['range'];
			$this->objPHPExcel->setActiveSheetIndexByName($worksheet);
	        $result = $this->objPHPExcel->getActiveSheet()->rangeToArray($range,NULL,TRUE,FALSE);
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
		unset($this->objPHPExcel);
	}
	
}

?>
