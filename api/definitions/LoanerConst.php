<?php


interface LoanerConst {
	
	//calculation method 
	const LOOKUP = "lookup";
	const ADJUST = "adjust";
	
	//confirming amount
	const MIMIMUM_LIMIT_AMOUNT = 50000;
	const CONFIRMING_LIMIT_AMOUNT = 417000;
	const MAXIMUM_LIMIT_AMOUNT = 2000000;
	
	//loan amount confirming type 
	const CONFIRMING = "confirming" ;
	const SUPPERCONFIRMING = "superConfirming";
	const NONECONFIRMING = "noneConfirming";
	
	//loan term 
	const FIXED = 'fixed';
	const AMR = 'arm';
	
	//fixed year term
	const YEAR30 = 30;
	const YEAR25 = 25;
	const YEAR20 = 20;
	const YEAR15 = 15;
	const YEAR10 = 10;
	
	//arm years
	const ARM31  = 'arm31';
	const ARM51  = 'arm51';
	const ARM71  = 'arm71';
	const ARM101 = 'arm101';
	
	//loan purpose 
	const PURCHASE =  'purchase';
	const REFINANCE = 'rtrefinance';
	const COREFINANCE = 'corefinance' ; //cash out refinance
	
	//property units
	const ONE_UNIT   = 'one_unit';
	const TWO_UNIT   = 'two_unit';
	const THREE_UNIT = 'three_unit';
	const FOUR_UNIT  = 'four_unit';
	
	//property
	const HOUSE = 'house';
	const CONDO = 'condo';
	
	//Property occupation
	const PRIMARY_HOME = 'primaryHome';
	const SECONDARY_HOME = 'secondaryHome';
	const INVESTMENT = 'investmentHouse';
}

?>