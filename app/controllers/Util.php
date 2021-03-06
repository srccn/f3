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
        if ( $toDump != null ) {
            	ob_start();
            	var_dump($toDump);
            	$content = ob_get_clean();
           	  //var_dump($toDump);
        } else {
            	$content = "<br>";
        }
        if ($f3->get('LOG_OUTPUT')) {
            $logger = new Log($f3->get('OUTPUT_LOGFILE')) ;
            $logger->write ($message . " : " .$content);
        }
    	if ( $f3->get('SHOW_DETAIL') > 0 ) {
            echo $message . " : " . $content ;
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
    
    static function calMonthlyPayment($loanAmount,$rate, $numYears) {
    	$r = $rate / (12 * 100) ;
    	$n = 12 * $numYears ;
    	
    	$monthlyPayment = ($r * $loanAmount) / (1- pow((1+$r), -$n)) ;
    	
    	return round ( $monthlyPayment, 2 );
    }
    
    static function bestResult ($resultArray) {
    	$saveViewRecord[0] = array ( 
    		"part1" =>	new ViewRecord(),
    		"part2"	=>  null,
    		"option" => 0	
    	);
    	
    	$saveViewRecord[1] = array ( 
    		"part1" =>	new ViewRecord(),
    		"part2"	=>  null,
    		"option" => 1	
    	);
    	$saveViewRecord[2] = array ( 
    		"part1" =>	new ViewRecord(),
    		"part2"	=>  null,
    		"option" => 2	
    	);
    	 
    	$bestOption = array();
    	
    	foreach ($resultArray as $viewRecord) {
    		if ($viewRecord['part1']->rate == null) {
    			continue;
    		}
    		
    		if ($saveViewRecord[$viewRecord['option']]['part1']->rate == 0) {
    			$saveViewRecord[$viewRecord['option']] = $viewRecord;
    			continue;
    		}
    		
    		if ($viewRecord['part1']->rate < $saveViewRecord[$viewRecord['option']]['part1']->rate) {
    			$saveViewRecord[$viewRecord['option']] = $viewRecord;
    			continue;
    		}
    		
    	    if ($viewRecord['part1']->rate == $saveViewRecord[$viewRecord['option']]['part1']->rate  &&
    	    		$viewRecord['part1']->credit > $saveViewRecord[$viewRecord['option']]['part1']->credit
    	    		) {
    			$saveViewRecord[$viewRecord['option']] = $viewRecord;
    		}
    	}
    	
    	//package return array
    	$saveViewRecord[0]['part1']->populateLowRateData();
    	$returnArray = array($saveViewRecord[0]) ;

    	if ($saveViewRecord[1]['part1']->rate != null) {
    		$saveViewRecord[1]['part1']->populateLowRateData();
    		array_push($returnArray, $saveViewRecord[1]);
    	}
        if ($saveViewRecord[2]['part1']->rate != null) {
        	$saveViewRecord[2]['part1']->populateLowRateData();
    		array_push($returnArray, $saveViewRecord[2]);
    	}
    	return $returnArray;
    }
    
    static function cleanTable ($resultTable) {
    	$returnTable = array();
    	
    	foreach ($resultTable as $tableRow) {
    		$cleanRow = array_filter($tableRow, function($var){return (isset($var) && strlen($var) > 0 );});
    		array_push($returnTable, array_values ($cleanRow) );
    	}
    	return $returnTable;
    }
    
    static function rotateTable($resultTable) {
    	$a = new ArrayIterator($resultTable);
    	$returnTable = $a->getArrayCopy();
    	array_unshift($returnTable, null);
    	return call_user_func_array('array_map', $returnTable);
    }
    
    static function prependColumn($column, $table) {
    	
    	if (count($column) !== count($table)) {
    		die ("failed prepend Column, due to number of elements mismatch." . count($column)." vs ". count($table) );
    	}
    	
    	$a = new ArrayIterator($table);
    	$returnTable = $a->getArrayCopy();
        $number = count($column);
        for ($i=0; $i<$number; $i++){
        	array_unshift($returnTable[$i], $column[$i]);
        }
    	return $returnTable;
    }
    
    static function getTableColumn($columnNumber, $table) {
    	$returnColumn = array_column($table, $columnNumber);
    	return $returnColumn;
    }
    
    static function arrayComb($arrays) //http://stackoverflow.com/questions/2516599/php-2d-array-output-all-combinations
	{
	    $result = array();
	    $arrays = array_values($arrays);
	    $sizeIn = sizeof($arrays);
	    $size = $sizeIn > 0 ? 1 : 0;
	    foreach ($arrays as $array)
	        $size = $size * sizeof($array);
	    for ($i = 0; $i < $size; $i ++)
	    {
	        $result[$i] = array();
	        for ($j = 0; $j < $sizeIn; $j ++)
	            array_push($result[$i], current($arrays[$j]));
	        for ($j = ($sizeIn -1); $j >= 0; $j --)
	        {
	            if (next($arrays[$j]))
	                break;
	            elseif (isset ($arrays[$j]))
	                reset($arrays[$j]);
	        }
	    }
	    return $result;
	}
    
}

?>