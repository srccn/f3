<?php

 class InputFormTest extends PHPUnit_Framework_TestCase {

 	/**
     * @beforeClass
     */
 	function setup(){
 		include_once('test/AutoLoader.php');

 	}
 	
 	/**
 	 * @test
 	 */
 	public function testSetInputForm () {

		$testArray = array ( 
				"zip" => "001122",
				"loanAmount" => "12345"
		);
		$im = new InputForm;
		$im->setInputForm($testArray);
		$this->assertEquals($im->zip, "001122");
		$this->assertEquals($im->loanAmount ,"12345");
	}
	
	/**
	 * @test
	 */
	public function testGetSampleInputForm () {
		$im = new InputForm;
		$sampleArray = $im->getSampleForm();
		$this->assertEquals( count($sampleArray) , 12);
		$this->assertEquals($sampleArray['loanAmount'] , 240000 );
		$this->assertEquals($sampleArray['occType'] ,  LoanerConst::PRIMARY_HOME );
		$this->assertEquals($sampleArray['type'] , LoanerConst::PURCHASE );
	}
}
	
