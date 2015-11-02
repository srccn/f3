<?php

 class InputFormTest extends PHPUnit_Framework_TestCase {

 	function __construct(){
 		include_once('test/AutoLoader.php');
 		AutoLoader::registerDirectory('app/models');
 	}
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
		
}
	
