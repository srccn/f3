<?php

class QueryDB extends BaseController {

    private $result;
    
    function runQuery ($q) {
        //$query = 'select GetConfirmingLoanUpperLimit("02163", "two_unit") ';
        $this->result = $this->db->exec($q);
    }
    
    function getConfirming($zip, $propertyType, $loanAmount) {
    	$query= 'select GetConfirmingLoanUpperLimit("' . $zip. '", "' . $propertyType. '")';
    	$this->runQuery($query);
        var_dump($this->result);
    	$row = array_values ($this->result[0]);        
    	echo "Confirming limit :" . $row[0] . "  ";
    	echo "Loan Amount :" . $loanAmount . " <br> ";
    	echo ($row[0] > $loanAmount ) ? "Yes" :  "No";
    	return $row[0];
    }
    
    function printResult($q) {
        $this->runQuery($q);
        var_dump($this->result->fetch_all());
    }
    
    function getFees() {
    	$query = "select * from fees where id=1 ;" ;
    	$this->runQuery($query);
    	$rows =  $this->result->fetch_assoc();
    	$this->printAssoc($rows);
    	$sum=0;
    	foreach($rows as $x => $x_value) {
    		if ($x != 'id') {
    			$sum += $x_value;
    		}
    	}
    	echo "Sum of Fee is : " . $sum;
    			
    }
    
    function getGroupedSRPByZip($zipCode, $loanType, $loanAmount, $upperLimit, $purchaser){
        $query = 'select GetGroupedSRPByZip("02460", ' .
            $loanType . "," .
            $loanAmount . "," .
            $upperLimit .",".
            $purchaser . 
            ")";
        $this->runQuery($query);
    	$row = $this->result->fetch_array(MYSQLI_NUM);
    	var_dump($row);        
    }
    
    function getRate($purchaserID, $loanType, $lockDays, $zipCode, $loanAmount, $propertyType, $margiin) {
        $query = 'call proc_GetRate(' . $purchaserID . ',' .
            $loanType . ',' .
            $lockDays . ',' .
            '"'.$zipCode.'"'  . ',' .
            $loanAmount . ',' .
            '"'.$propertyType.'"' . ',' .
            $margiin .
            ")";
        echo $query . "<br>" ;
        $this->runQuery($query);
        while ($row = $this->result->fetch_assoc()) {
        	$this->printAssoc($row);
        }
        mysqli_free_result($this->result);
    }
    
    function __destruct() {
        // $this->conn->close();
    }
    
    function printAssoc($assocArray){
    	foreach($assocArray as $x => $x_value) {
    		//echo "Key=" . $x . ", Value=" . $x_value;
    		echo $x . "=" . $x_value . ",";
    	}
        echo "<br>";
    }
    
    
    
}

?>