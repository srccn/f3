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
 	public function testCalculateDerivesSampleInput() {
        
 		$inputArray = $this->testInputForm->getSampleForm(); //default zip code 02460
        $testLoanProperty = new LoanProperty($inputArray);
        $testLoanProperty->calculateDerives();
        
        //verify result
        $this->assertEquals($testLoanProperty->getState(), "MA");
        $this->assertEquals($testLoanProperty->confirmingUpperLimit, 523250);
        $this->assertEquals($testLoanProperty->isConfirming, 1);
        $this->assertEquals($testLoanProperty->LTV, 0.80);
        $this->assertEquals($testLoanProperty->mincredit, 0); //default npncc
        $this->assertEquals(count ($testLoanProperty->loanAmountOptions) , 1); //default npncc
        $this->assertEquals($testLoanProperty->margin , 1); //confirming margin
        $this->assertContains("No point, No cloing cost" , $testLoanProperty->getClosingOption()); //none confirming margin
        $this->assertTrue($testLoanProperty->loanLimitCheck()); //passed basic check
        
 	}

 	/**
 	 * @test
 	 */
 	public function testCalculateDerivesVary() {
 		$inputArray = $this->testInputForm->getSampleForm();
 		$inputArray['zip']="72519"; //AR
 		$inputArray['marketPrice']="800000"; 
 		$inputArray['loanAmount']="600000"; //above confirming limit
 		$inputArray['closingOption']= LoanerConst::CLOSING_OPTION_BY_MIN_CREDIT; 
 		$inputArray['mincredit']= -2000; 
 		$testLoanProperty = new LoanProperty($inputArray);
 		$testLoanProperty->calculateDerives();

 		//verify result
 		$this->assertEquals($testLoanProperty->getState(), "AR");
 		$this->assertEquals($testLoanProperty->confirmingUpperLimit, 417000);
 		$this->assertEquals($testLoanProperty->isConfirming, 0); 		
        $this->assertEquals($testLoanProperty->mincredit, -2000); //pay 2000 
 		$this->assertEquals(count ($testLoanProperty->loanAmountOptions) , 2); //default npncc
        $this->assertEquals($testLoanProperty->margin , 0.5); //none confirming margin
        $this->assertContains("with minimum +credits/-payment" , $testLoanProperty->getClosingOption()); //none confirming margin
        $this->assertTrue($testLoanProperty->loanLimitCheck()); //passed basic check
        
 	}
 	
 	/**
 	 * @test
 	 */
 	public function testCalculateDerivesLoanLimitCheck() {
 		
 		$inputArray = $this->testInputForm->getSampleForm(); 

 		$testLoanProperty = new LoanProperty($inputArray);
 		
 		$testLoanProperty->loanAmount = 2000001; //loanAmount more than 2million
 		$this->assertFalse($testLoanProperty->loanLimitCheck());

 		$testLoanProperty->loanAmount = 49999; //loanAmount < 50000
 		$this->assertFalse($testLoanProperty->loanLimitCheck());
 		
 		//reset loanAmount
 		$testLoanProperty->loanAmount = $inputArray['loanAmount'];

 		// creditScore for Purchasing
 		$testLoanProperty->creditScore = 670 ; // credit score < 680
 		$this->assertFalse($testLoanProperty->loanLimitCheck());
 			
 		$testLoanProperty->creditScore = 680 ; // credit score < 680
 		$this->assertTrue($testLoanProperty->loanLimitCheck());

 		$testLoanProperty->creditScore = $inputArray['creditScore'] ;
 		//LTV for purchasing
 		$LV_save = $testLoanProperty->LTV;
 		$testLoanProperty->LTV = 0.97; // max LTV for purchase
 		$this->assertFalse($testLoanProperty->loanLimitCheck());
 			
 		$testLoanProperty->LTV = 0.96; 
 		$this->assertTrue($testLoanProperty->loanLimitCheck());
 		$testLoanProperty->LTV = $LV_save;
 		
 	}
 	
}
