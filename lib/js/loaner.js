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
       
       //for result page 
       $('#btnFeeDetails').click ( function() {
            $('#divFeeDetails').toggle(1000);
            $('#divLoanProperty').toggle(1000);
       });

       $('#btnRateDetails').click ( function() {
           $('.hiddenCol').fadeToggle("slow", "linear");
      });
        
        
    });