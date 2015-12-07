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

function showDialog(pageid) {
	
	var data = pageid;
	var out = "";
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
           out += key + " ---   $" + data[key] + "<br>";
        }
     }
	document.getElementById('light').style.display='block';
	document.getElementById('light').innerHTML = 
		"<br> Loan Closing Cost Details." + 
        ' <a id="clsoePopup" href ="#" onClick="closeDialog();">Close</a> <hr>' +
        out ;
	document.getElementById('fade').style.display='block';
	
} 
function closeDialog() {
	document.getElementById('light').style.display='none';
	document.getElementById('fade').style.display='none';        
}     
