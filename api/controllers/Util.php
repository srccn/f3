<?php

class Util extends BaseController {

	protected $f3;
	protected $db;

    static function getLoadTypeId($term, $isConfirming, $purchaseOrRef, $fixOrArm) {
        $id=0;
        
        return $id;
    }
    
    static function resultString($resultArray) {
         return $resultArray[0]['result'];
    }
}

?>