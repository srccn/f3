<?php
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
abstract class AbstractLoadController extends BaseController {

	protected $excelFile;
	protected $excel_map_file;
	protected $dataMap;
	protected $purchaserID;
	protected $purchaserName;
	protected $bank_symbol;
	protected $objPHPExcel;
	protected $mapData;

	public function setExcelMapFile($excelMapFile) {
		
        //check existance of excel map file
	    if (!file_exists($excelMapFile)) {
	        die("Excel mape file  $excelMapFile does not exist." . EOL);
	    } else {
		    $this->excel_map_file = $excelMapFile;
		}
		
		require_once $this->excel_map_file;
		$bank_symbol = pathinfo($this->excel_map_file, PATHINFO_FILENAME); //purchaser symble == map file name
		$this->mapData = new $bank_symbol;
		
		$this->excelFile = $this->mapData->getExcelFile();
		//check existance of excel file
		
	    if (!file_exists($this->excelFile)) {
	        exit("Excel file not found." . EOL);
	    }
		$this->purchaserID   = $this->mapData->getPurchaserId();
		$this->purchaserName = $this->mapData->getPurchaserName();
		
		echo "Excel file to load is $this->excelFile <br>";
		
		//build excel object for accessing spreadsheet
		$this->defineObjPHPExcel($this->excelFile);

	}
	
	public function setRateSheetFile($rateSheetFileName){
		if (!file_exists($rateSheetFileName)) {
			die ("Rate sheet does not exist ". $rateSheetFileName );
		} else {
			$this->mapData->setRateSheetFile($rateSheetFileName);
		}
	}
	
	public function defineObjPHPExcel ($excelFileName) { //example "data/wells.xls"
		require_once 'lib/Classes/PHPExcel/IOFactory.php';
		try {
			$inputFileType = PHPExcel_IOFactory::identify($excelFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly(true);
			$this->objPHPExcel = $objReader->load($excelFileName);
		} catch(PHPExcel_Reader_Exception $e) {
			die('Error loading file: '.$e->getMessage());
		}		
	}
	
	abstract public function pushDataToDB() ;

	abstract public function removeDataInDB() ;

	abstract public function loadDataFromExcel();
	
	public function reloadData() {
	    $this->removeDataInDB();
		$this->loadDataFromExcel();
		$this->pushDataToDB();
	}
	
	public function getPurchaserID()   { return $this->purchaserID; }
	public function getPurchaserName() { return $this->purchaserName; }
	
}

?>
