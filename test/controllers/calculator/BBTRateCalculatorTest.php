<?php
class BBTRateCalculatorTest extends PHPUnit_Framework_TestCase {

	/**
	 * @beforeClass
	 */
	function setup(){
		include_once('test/AutoLoader.php');
	
	}
	
	/**
	 * @test
	 */
	public function testGetPurchaserId () {
		$testInputForm = new InputForm();
		$testInputs = $testInputForm->getSampleForm();
		$testLoanProperty = new LoanProperty($testInputs);
		
		$testCalculator = new BBTRateCalculator;
		$testCalculator->setProperty($testLoanProperty);
		$testCalculator->setTotalFee(2500);		
		
		$testPurchaserId = $testCalculator->getPurchaserIdByName();
		$this->assertEquals(1, $testPurchaserId); //BBT is purchaser 1
		
	}
		
	/**
	 * @test
	 */
	public function testGetSRP () {
		$testInputForm = new InputForm();
		$testInputs = $testInputForm->getSampleForm();
		$testLoanProperty = new LoanProperty($testInputs);
	
		$testCalculator = new BBTRateCalculator;
		$testCalculator->setProperty($testLoanProperty);
		$testCalculator->setTotalFee(2500);
	
		$testSPRLoanTypeId = $testCalculator->getSRPLoanTypeId();
		$this->assertEquals(1, $testSPRLoanTypeId);

		$testSPR = $testCalculator->getSRP();
		$this->assertEquals(1.60, $testSPR); //BBT give 1.6 SPR for sample property
		
	}

	/**
	 * @test
	 */
	public function testGetAdjusts () {
		$testInputForm = new InputForm();
		$testInputs = $testInputForm->getSampleForm();

		$testLoanProperty = new LoanProperty($testInputs);
	
		$testCalculator = new BBTRateCalculator;
		$testCalculator->setProperty($testLoanProperty);
		$testCalculator->setTotalFee(2500);
	
		$testCalculator->calculateAllAdjusts();
		
		$testAdjusts = $testCalculator->getAdjusts();
		//var_dump($testAdjusts);
		$this->assertEquals(-0.75, $testAdjusts['LtvCcAdj']); //740 with 0.80
		$this->assertEquals(0, $testAdjusts['LtvCcPmiAdj']); //740 with 0.80
		$this->assertEquals(0, $testAdjusts['LtvOtherAdj']); //740 with 0.80// 		$testCalculator = new BBTRateCalculator;

	}

	/**
	 * @test
	 */
	public function testGetAdjustsVary () {	
		$testInputForm = new InputForm();
		$testInputs = $testInputForm->getSampleForm();
		
		$testInputs['loanAmount']=295000;
		$testInputs['creditScore']=710;
		$testInputs['type']=LoanerConst::CONDO;
		$testLoanProperty = new LoanProperty($testInputs);
		
		$testCalculator = new BBTRateCalculator;
		$testCalculator->setProperty($testLoanProperty);
		$testCalculator->setTotalFee(2500);
		
		$testCalculator->calculateAllAdjusts();
		$testAdjusts = $testCalculator->getAdjusts();
		$this->assertEquals(-1.0, $testAdjusts['LtvCcAdj']); //700 with 0.98
		$this->assertEquals(-2.5, $testAdjusts['LtvCcPmiAdj']); //700 with 0.98
		$this->assertEquals(-0.75, $testAdjusts['LtvOtherAdj']); //condo
		$this->assertEquals(-4.25, $testCalculator->getTotalAdjusts());		
	}
	
}

?>