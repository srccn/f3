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
		
		creditScore.value = "741";
		switch (selectedValue) {
		
	    	case 'conforming741':
	    	    price.value = "400000";
	    	    loanAmount.value = "324000";
	    	    break
                
	    	case 'superConforming741' :
        	    price.value = "600000";
        	    loanAmount.value = "486000";
        	    break

	    	case 'nonConforming741':
        	    price.value = "800000";
        	    loanAmount.value = "648000";
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
