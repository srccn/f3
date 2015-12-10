<?php
class FeeCalculatorTest extends PHPUnit_Framework_TestCase {
	/**
	 * @beforeClass
	 */
	function setup(){
		include_once('test/AutoLoader.php');
	}
	
	/**
	 * @test
	 */
	public function testSetFixedFees () {
		$testInputForm = new InputForm();
		$testInputs = $testInputForm->getSampleForm();
		$testLoanProperty = new LoanProperty($testInputs);
	
		$testCalculator = new FeeCalculator($testLoanProperty);
		
		$testCalculator->setFixedFees();
		$this->assertEquals(18.00, $testCalculator->getFeesArray()['804 credit_report']); 
		$this->assertEquals(850.00, $testCalculator->getFeesArray()['801 Origination']);
		$this->assertEquals(87.00, $testCalculator->getFeesArray()['TaxService']);
		$this->assertEquals(85.00, $testCalculator->getFeesArray()['HomeRegistration']);
		
	}

	
}