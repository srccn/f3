<?php 
    echo "zip           : " . $_REQUEST['zipCode'] . "<br>";
    echo "Loan Amount   : " . $_REQUEST['loanAmount'] . "<br>";
    echo "Property type : " . $_REQUEST['properyType'] . "<br>" ;
    echo "Lock Days     : " . $_REQUEST['lockDays'] . "<br>" ;
    
    echo "<h3>Is confirming loan or not</h3>";
    require 'testQuery.php';
    $myQuery = new QueryDB();
    $upperLimit = $myQuery->getConfirming($_REQUEST['zipCode'], $_REQUEST['properyType'], $_REQUEST['loanAmount']);
    echo $upperLimit;
    
    echo "<h3>Find SRP</h3>";
    if (isset($_REQUEST['fixed30'])) {
        echo "30 years Fixed " ;
        echo "<br> - BBT <br>";
        $myQuery = new QueryDB();
        $myQuery->getGroupedSRPByZip($_REQUEST['zipCode'],1,$_REQUEST['loanAmount'], $upperLimit,1);
        echo "<br> - BOKF <br>";
        $myQuery = new QueryDB();
        $myQuery->getGroupedSRPByZip($_REQUEST['zipCode'],1,$_REQUEST['loanAmount'],$upperLimit,2);
    };
    if (isset($_REQUEST['fixed15'])) {echo "15 years Fixed <br>" ; };
    if (isset($_REQUEST['fixed10'])) {echo "10 years Fixed <br>" ; };
    
    if (isset($_REQUEST['arm31'])) {echo "3-1 ARM <br>" ; };
    if (isset($_REQUEST['arm51'])) {echo "5-1 ARM <br>" ; };
    if (isset($_REQUEST['arm71'])) {echo "7-1 ARM <br>" ; };

    echo "<h3>Find rate</h3>";
    if (isset($_REQUEST['fixed30'])) {
        echo "30 years Fixed <br>" ; 
        echo "<br> - BBT <br>";
        $myQuery = new QueryDB();
        $myQuery->getRate(1,1,$_REQUEST['lockDays'],$_REQUEST['zipCode'], $_REQUEST['loanAmount'],$_REQUEST['properyType'],1.00);
       echo "<br> - BOKF <br>";
        $myQuery = new QueryDB();
        $myQuery->getRate(2,1,$_REQUEST['lockDays'],$_REQUEST['zipCode'], $_REQUEST['loanAmount'],$_REQUEST['properyType'],1.00);
    }; 
    if (isset($_REQUEST['fixed15'])) {echo "15 years Fixed <br>" ; }; 
    if (isset($_REQUEST['fixed10'])) {echo "10 years Fixed <br>" ; }; 

    if (isset($_REQUEST['arm31'])) {echo "3-1 ARM <br>" ; };
    if (isset($_REQUEST['arm51'])) {echo "5-1 ARM <br>" ; };
    if (isset($_REQUEST['arm71'])) {echo "7-1 ARM <br>" ; };


    echo "<h3>List of Fees</h3>";
    $myQuery = new QueryDB();
    $myQuery->getFees();
    
?>
