<?php

class UtilTableTest extends PHPUnit_Framework_TestCase {

	/**
	 * @beforeClass
	 */
	function setup(){
		include_once('test/AutoLoader.php');
	
	}
	
	/**
	 * @test
	 */
	public function testCleanTable() {
	
		$testArray = array ();
		array_push($testArray, [1.1,'',1.2,1.3,'',1.4]);
		array_push($testArray, ['' ,2.1,2.2,'',2.3,2.4,'']);
		array_push($testArray, [null,3.1,3.2,null,3.3,'',3.4]);
		
		$returnArray = Util::cleanTable($testArray);
		//clean table as 3 x 4
		$this->assertEquals(3, count($returnArray));
		
		$this->assertEquals(4, count($returnArray[0]));
		$this->assertEquals(4, count($returnArray[1]));
		$this->assertEquals(4, count($returnArray[2]));
	}	
	
	/**
	 * @test
	 */
	public function testRotateTable() {
	
		$testArray = array ();
		//populate a clean 2x4
		array_push($testArray, [1.1,1.2,1.3,1.4]);
		array_push($testArray, [2.1,2.2,2.3,2.4]);
	
		$returnArray = Util::rotateTable($testArray);
		
		//return a 4x2
		$this->assertEquals(2, count($returnArray[0]));
		$this->assertEquals(4, count($returnArray));
	}
	
	/**
	 * @test
	 */
	public function testGetSumValue() {
	
		$testArray = array (
				"a" => 1.1,
				"b" => 1.2,
				"c" => 1.3,
				"d" => 1.4,
				"x" => null,
				"y" => "abc"
		);

	
		$sum = Util::getSumValue($testArray);
	
		$this->assertEquals(5.0, $sum);
	}
	
}
