<?php
class UtilTest extends PHPUnit_Framework_TestCase {
	

	/**
	 * @beforeClass
	 */
	function setup(){
		include_once('test/AutoLoader.php');
	}
	
	/**
	 * @test
	 */
	public function testCalMonthlyPayment () {
		$sample =  Util::calMonthlyPayment(360000, 2.625, 15);
		$this->assertEquals (2421.68 ,$sample );
	}	
}