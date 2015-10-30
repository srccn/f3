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
    
    static function finacialNumber($number) {
    	$number = round ( floatval($number) , 2 );
    	return ($number < 0 ? "(".abs($number).")" : $number);
    }

    static function cmp ($a, $b) {
    	
    	if ($a['part1']->rate == null && $b['part1']->rate != null) {
    		return 1;
    	}

    	if ($a['part1']->rate != null && $b['part1']->rate == null) {
    		return -1;
    	}

    	if ($a['part1']->rate == null && $b['part1']->rate == null) {
    		return -1;
    	}    	
    	
    	if (($a['part1']->rate) == ($b['part1']->rate) ) {
     		if ( intVal($a['part1']->credit) === intVal($b['part1']->credit) ) {
     			return 0;
    		}
    		return (intVal($a['part1']->credit) < intVal($b['part1']->credit) ) ? 1 : -1;
    	}
    	return ( ($a['part1']->rate) < ($b['part1']->rate) ) ? -1:1 ;
    }
    
}

?>