    $(document).ready(function() {
        $('#btnFeeDetails').click ( function() {
             $('#divFeeDetails').toggle(1000);
             $('#divLoanProperty').toggle(1000);
        });

        $('#btnRateDetails').click ( function() {
            $('.hiddenCol').fadeToggle("slow", "linear");
       });
        
    });