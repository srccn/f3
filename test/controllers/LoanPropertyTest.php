<?php

class LoanPropertyTest extends PHPUnit_Framework_TestCase {
    
    
	private $testInputForm;
	
     /**
     * @beforeClass
     */
 	function setup(){
 		include_once('test/AutoLoader.php');
        $this->testInputForm = new InputForm();
 	}
 	
 	/**
 	 * @test
 	 */
 	public function testCalculateDerives() {
        
 		$inputArray = $this->testInputForm->getSampleForm(); //default zip code 02460
        $testLoanProperty = new LoanProperty($inputArray);
        $testLoanProperty->calculateDerives();
        $this->assertEquals($testLoanProperty->getState(), "MA");
        $this->assertEquals($testLoanProperty->confirmingUpperLimit, 517500);
        $this->assertEquals($testLoanProperty->isConfirming, 1);
        
        
        
        $inputArray['zip']="72519"; //AR
        $inputArray['loanAmount']="500000"; //above confirming limit
        $testLoanProperty2 = new LoanProperty($inputArray);
        $testLoanProperty2->calculateDerives();
        $this->assertEquals($testLoanProperty2->getState(), "AR");
        $this->assertEquals($testLoanProperty2->confirmingUpperLimit, 417000);
        $this->assertEquals($testLoanProperty2->isConfirming, 0);
 	}

    
}
