<?php


interface LoanerConst {
	
	//calculation method 
	const LOOKUP = 1;
	const ADJUST = 2;
	
	//confirming amount
	const CONFIRMING_AMOUNT = 417000;
	
	//loan amount confirming type 
	const CONFIRMING = 1 ;
	const SUPPERCONFIRMING = 2;
	const NONECONFIRMING = 3;
	
	//loan term 
	const FIXED = 1;
	const AMR = 2;
	
	//fixed year term
	const YEAR30 = 30;
	const YEAR25 = 25;
	const YEAR20 = 20;
	const YEAR15 = 15
	const YEAR10 = 10;
	
	//arm years
	const ARM31  = 31;
	const ARM51  = 51;
	const ARM71  = 71;
	const ARM101 = 101;
	
	//loan purpose 
	const PURCHASE =  1;
	const REFINANCE = 2;
	const COREFINANCE = 3 ; //cash out refinance
	
	//property units
	const ONE_UNIT   = 1;
	const TWO_UNIT   = 2;
	const THREE_UNIT = 3;
	const FOUR_UNIT  = 4
	
	//property
	const HOUSE = 1;
	const CONDO = 2;
	
	//Property occupation
	const PRIMARY_HOME = 1;
	const SECONDARY_HOME = 2;
	const INVESTMENT = 3;
}

?>