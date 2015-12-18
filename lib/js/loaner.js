$(document).ready(function() {

	//for front page
	$('#closingOption').on ('change', function() {
		if( this.value === "bymincredit" ) {
			$('#mincredit').show();
		} else {
			$('#mincredit').attr("value", 0);
			$('#mincredit').hide();
		}; 
	});

	$('#optionLink').click ( function() {
		$('#optionsDiv').toggle()
		$('#optionsList').toggle()
	});

	$('#brokerOptionLink').click ( function() {
		$('#brokerOptionDiv').toggle()
	});

	//for front page easy testing purpose
	$('#sampleDataList').change (function(){
		
		var selectedValue = $('#sampleDataList').val();
		var price = document.getElementById('marketPrice');
		var loanAmount = document.getElementById('loanAmount');
		var creditScore = document.getElementById('creditScore');
		
		switch (selectedValue) {
		
	    	case 'conforming741_81':
	    	    price.value = "400000";
	    	    loanAmount.value = "324000";
	    		creditScore.value = "741";
	    	    break;
                
	    	case 'superConforming741_81' :
        	    price.value = "600000";
        	    loanAmount.value = "486000";
        		creditScore.value = "741";
        	    break;

	    	case 'nonConforming741_81':
        	    price.value = "800000";
        	    loanAmount.value = "648000";
        		creditScore.value = "741";
        	    break;

	    	case 'conforming741_65':
        	    price.value = "400000";
        	    loanAmount.value = "260000";
        		creditScore.value = "741";
        	    break;
			
	    	case 'superConforming741_65' :
        	    price.value = "600000";
        	    loanAmount.value = "390000";
        		creditScore.value = "741";
        	    break;

	    	case 'nonConforming741_65':
        	    price.value = "800000";
        	    loanAmount.value = "520000";
        		creditScore.value = "741";
        	    break;
			
	    	case 'conforming681_91':
        	    price.value = "400000";
        	    loanAmount.value = "364000";
        		creditScore.value = "681";
        	    break;
			
	    	case 'superConforming681_91' :
        	    price.value = "600000";
        	    loanAmount.value = "546000";
        		creditScore.value = "681";
        	    break;

	    	case 'nonConforming681_91':
        	    price.value = "900000";
        	    loanAmount.value = "819000";
        		creditScore.value = "681";
        	    break;
        	    
			default :
		};
		return false;
	});
	
	
	//for result page 
	$('#btnFeeDetails').click ( function() {
		//$('#divFeeDetails').toggle(1000);
		$('#divLoanProperty').toggle(1000);
		$('.hiddenCol').fadeToggle("slow", "linear");
	});

	$('#btnRateDetails').click ( function() {
		$('.hiddenCol').fadeToggle("slow", "linear");
	});
	

});

function showDialog (pageid, string_in) {
	
	var data = pageid;
	var data2 = string_in;
	var out = "";
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
           out += key + " ---   $" + data[key] + "<br>";
        }
     }
    
     if ( typeof data2 !== 'undefined' && data2 !== "") {
    	out += "<hr>" 
    	out += "SRP ---   " + data2 + "<br>"; 
     }
    
	document.getElementById('light').style.display='block';
	document.getElementById('light').innerHTML = 
		"<br> Loan Calculation Details." + 
        ' <a id="clsoePopup" href ="#rateArea" onClick="closeDialog();">Close</a> <hr>' +
        out ;
	document.getElementById('fade').style.display='block';
	
}

function closeDialog() {
	document.getElementById('light').style.display='none';
	document.getElementById('fade').style.display='none';
}     
