$(document).ready(function() {


    if (window.location.pathname.indexOf("front") != -1) {
//        if(typeof(sessionStorage.setFrontVisit)=='undefined' || sessionStorage.setFrontVisit==''){
//        	sessionStorage.setFrontVisit='yes';
            //alert("Hello my friend. Loading you previouse setting ....");
            //populate input form from _POST 

            $.ajax ({
				url : "/f3/loadForm" ,
				type: "POST",
				data: "",
				success: function (data, textStatus, jqXHR) {
					//console.log(data);
					var obj = JSON.parse(data);
					//console.log(obj);
					if (obj.zip !== null) {
						$('#numberUnit').val(obj.numberUnit);
						$('#marketPrice').val(obj.marketPrice);
						$('#loanAmount').val(obj.loanAmount);
						$('#zip').val(obj.zip);
						$('#creditScore').val(obj.creditScore);
						$('#closingOption').val(obj.closingOption);
						$('#occType').val(obj.occType);
						$('#occType').val(obj.occType);
						$('#loanNameSelection').val(obj.loanNameSelection.split(','));
						$('#lockDays').val(obj.lockDays);
						document.inputForm.type.value = obj.type ;
						document.inputForm.purchaseType.value = obj.purchaseType ;
						document.getElementById("message").innerHTML +="  load previouse input data successfully";
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					document.getElementById("message").innerHTML +=" load previouse input data failed";
				}
			});		
            
//        }
    }	
	
	$('#btnSignin').click ( function() {
		if ( $('#frmSignup').is(':visible') ) {
			$('#frmSignup').fadeToggle("fast");
		}
		$('#frmSignin').fadeToggle("slow", "linear");
	});
	$('#btnSignup').click ( function() {
		if ( $('#frmSignin').is(':visible') ) {
			$('#frmSignin').fadeToggle("fast");
		}
		$('#frmSignup').fadeToggle("slow", "linear");
	});
	
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
		$('#optionsDiv').toggle('slow');
		//$('#optionsList').toggle();
		//$('#brokerOptionDiv').toggle();
		$('#openCloseOptionIcon').toggleClass('glyphicon-menu-up');
	});

	$('#brokerOptionLink').click ( function() {
		$('#brokerOptionDiv').toggle()
	});
	
//	//save inputs buttom - moved to result page
//	$('#SaveInput').click ( function() {
//		var target_rate = prompt("Please enter your target rate for recieving email alert\n" +
//				"For example 4.25 ", "4.25")
//		if (target_rate != null && !isNaN(target_rate) ) {
//			document.getElementById("message").innerHTML="Set " + target_rate + " for alert.";
//			inputForm =$('#inputForm').serialize();
//			
//			//document.getElementById("message").innerHTML += "<br>Form details : " + inputForm;
//			
//			$.ajax ({
//				url : "/f3/saveForm" ,
//				type: "POST",
//				data: inputForm + "&targetRate="+target_rate,
//				success: function (data, textStatus, jqXHR) {
//					document.getElementById("message").innerHTML +="  saved successfully";
//				},
//				error: function (jqXHR, textStatus, errorThrown) {
//					document.getElementById("message").innerHTML +="  save failed";
//				}
//			});		
//		} 
//		else {
//			document.getElementById("message").innerHTML="No alert set or updated.";
//		}
//	});

	//Find Rates button clicked
	$('#inputForm').submit( function(ev) {
		    
		    $("#resultPanel").html("Calculating ....");
		
		    //alert("searching rate ....");
		    //return false;
		    inputForm =$('#inputForm').serialize();
		    
			$.ajax ({
				url : "/f3/calculate" ,
				type: "POST",
				data: inputForm,
				success: function (data2, textStatus, jqXHR) {
					$("#resultPanel").html(data2);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert("rate search failed");
				}
			});
			 ev.preventDefault();
			 return false;
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

//  selection of caulculation result moved to result page	
//	$('.rg').change(function(){
//		//alert("caught it");
//		$('#SaveInput').removeAttr('disabled');
//	});
	

	// auto close message after 2 seconds
	setTimeout(function() {  
    		$('#message').fadeOut('slow');  
		}, 3000); // <-- time in milliseconds  
	

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
        ' <button id="clsoePopup" href ="#rateArea" onClick="closeDialog();" class="btn btn-link">Close</button> <hr>' +
        out ;
	document.getElementById('fade').style.display='block';
	
}

function closeDialog() {
	document.getElementById('light').style.display='none';
	document.getElementById('fade').style.display='none';
} 

function cleanRatePageVisit () {
	sessionStorage.removeItem('setFrontVisit') ;
    return true;
}

function saveOptionClick (evt) {
	console.log("clicked");
	var target_rate = prompt("Please enter your target rate for recieving email alert\n" +
			"For example 4.25 ", "4.25")
	if (target_rate != null && !isNaN(target_rate) ) {
		document.getElementById("message").innerHTML="Set " + target_rate + " for alert.";
		var saveOption = evt.target.value;
		
		$('button[name="saveOption"]').removeClass("btn-warning");
		$('button[name="saveOption"]').addClass("btn-info");
		$(evt.target).removeClass("btn-info");
		if ( ! $(evt.target).hasClass("btn-warning")) {
		    $(evt.target).addClass("btn-warning");
		}
		
		var inputForm =$('#inputForm').serialize();
		//inputForm.replace('%5B%5D','[]'); 
		inputForm += "&targetRate="+target_rate;
		inputForm += "&saveOption="+saveOption;
		
		document.getElementById("message").innerHTML += "Option selection is " + saveOption ;
		
		//document.getElementById("message").innerHTML += "<br>Form details : " + inputForm;
		
		$.ajax ({
			url : "/f3/saveForm" ,
			type: "POST",
			data: inputForm,
			success: function (data, textStatus, jqXHR) {
				document.getElementById("message").innerHTML +="  saved successfully";
				$('#message').show().delay(2000).fadeOut();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				document.getElementById("message").innerHTML +="  save failed";
			}
		});		
	} 
	else {
		document.getElementById("message").innerHTML="No alert set or updated.";
	}
}


