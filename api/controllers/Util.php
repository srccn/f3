<?php

class Util extends BaseController {

	protected $f3;
	protected $db;


	static function getSumValue($array) {
		$sum=0;
	    foreach ($array as $key => $value) {
            $sum += $value;
        }
		return $sum;
	}
	
    static function resultString($resultArray) {
        $result="";
        $size = count($resultArray);
        for ($i=0;$i<$size-1;$i++){
            $result = $result . $resultArray[$i]['result'] . ",";
        }
        $result = $result . $resultArray[$size-1]['result'] ;
        
        return $result;
    }
    
    static function dump($message, $toDump) {
 //       echo $message . " : " ;
 //       var_dump($toDump);
    }

    public static function getPurchaserIdByName($purchaserName) {
    	$name = $purchaserName ;//$this->f3->get('PARAMS.BankName');
    	$result = $this->db->exec("select purchaser_id as result from purchaser where purchaser_name='$name'");
    	//var_dump( $result );
    	return intval ( Util::resultString($result) );
    }
    
    public static function getAllPurchaserNames(){
    	$result = $this->db->exec("select purchaser_name as result from purchaser");
    	//var_dump($result);
    	return Util::resultString($result);
    }    
    
    
    
}

?>