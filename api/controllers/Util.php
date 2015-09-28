<?php

class Util extends BaseController {

	protected $f3;
	protected $db;

    static function getLoadTypeId($term, $isConfirming, $purchaseOrRef, $fixOrArm) {
        $id=0;
        
        return $id;
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
        echo $message . " : " ;
        var_dump($toDump);
    }
    
    
}

?>