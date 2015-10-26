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
    
    static function dump($message, $toDump = null) {
    	
    	$f3 = Base::instance();
    	if ( $f3->get('DETAIL') > 0 ) {
            echo $message . " : " ;
            if ( $toDump != null ) {
            	var_dump($toDump);
            	 
            }
    	}
    }

    
    static function hashString ($plainString) {
    	return password_hash($plainString, PASSWORD_BCRYPT, ['cost'=>10]);
    }
    
    static function verifyHash($plainString, $hashedString) {
    	return password_verify($plainString, $hashedString);
    }
    
}

?>