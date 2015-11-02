    $(document).ready(function() {
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
        });
    });