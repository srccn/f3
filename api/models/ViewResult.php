<?php

class ViewResult {
	
	public $products = [] ;
	
	public function getViewResult() {

        $products = array (
            "fixed30" => array (
            	"purchaserName1" => array (	
            		"opt1" => array (
            		  "part1" => array (
            				"partAmount"  => 0,
            				"partRate"    => 0,
            				"partCredit"  => 0,
            				"partLockDays"=> 0,
            		  ),	
            		  "part2" => array (
            				"partAmount"  => 0,
            				"partRate"    => 0,
            				"partCredit"  => 0,
            				"partLockDays"=> 0,
            		  )	
            		),	
            		"opt2" => array (),	
            		"opt3" => array ()
            	),
            	"purchaserName2" => array (),
            	"purchaserName3" => array ()
            ),
       		"fixed15" => array (),
       		"arm51" => array (),
       		"arm71" => array ()
        );		
		
        print_r ($this->products);
        
		return $this->products;
	}
	
	function initialize() {
		$products = array (
			"fixed30" => array (),
			"fixed15" => array (),
			"arm51" => array (),
			"arm71" => array ()
		);
		$this->products = $products;		
	}
	
	function isProductExist($productName){
		return array_key_exists($productName, $this->products);
	}
	
	function isPurchaserExistInProduct($purchaserName, $productName) {
		$searchProduct = $this->products[$productName];
		if (!$searchProduct ) {
			echo "product $productName not defined <br>";
			return false;
		} else {
			return array_key_exists($purchaserName, $searchProduct);
		}
	}
	
	function isOptionExistInProductPurchaser($optinName, $purchaserName, $productName) {
		$searchProduct = $this->products[$productName];
		if (! $searchProduct ) {
			echo "product $productName not defined <br>";
			return false;
		} else {
			$searchPurchaser = $this->products[$productName]["$purchaserName"];
			if (! $searchPurchaser) {
				echo "product $productName and purchaser $purchaserName not defined ";
				return false;
			} else {
				return array_key_exists($optinName, $searchPurchaser);
			}
		}
	}
	
	private function addChild($parent, $child){
		if (! array_key_exists($child, $parent) ){
			$parent[$child] = array();
		}
	}
	
	function addByFullPath($datapath){ // fixed30/purchaser1/option1/part1
		$pathElement = split("/", $datapath);
		$count = count($pathElement);
		$root = $this->products;
		
		for ($i = 0; $i < $count; $i++){
			$this->addChild($root, $pathElement[$i]);
			$root = $root[$pathElement[$i]];
		}
		
// 		if (! $this->isProductExist($pathElement[0])) {
// 			$this->products[$pathElement[0]] = array();
// 		} 
// 		if ( ! $this->isPurchaserExistInProduct($pathElement[1], $pathElement[0])) {
// 				$this->products[$pathElement[0]][$pathElement[1]] = array();
// 		}
// 		if ( ! $this->isOptionExistInProductPurchaser($pathElement[2], $pathElement[1], $pathElement[0]) ) {
// 				$this->products[$pathElement[0]][$pathElement[1]][$pathElement[2]] = array();
// 		}
		
// 		$this->products[$pathElement[0]][$pathElement[1]][$pathElement[2]][$pathElement[3]] = array();
		
	}

}

$r = new ViewResult;
$r->initialize();
var_dump( $r->isProductExist("fixed30") );
var_dump( $r->isProductExist("xxfixed30"));
$r->addByFullPath("fixed15/purchaser2/option1/part1");
$r->addByFullPath("fixed15/purchaser2/option1/part2");
$r->addByFullPath("fixed15/purchaser2/option2/part1");
$r->addByFullPath("fixed15/purchaser2/option2/part2");
$r->getViewResult();


?>