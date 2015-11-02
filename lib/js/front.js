    $(document).ready(function() {
        $('#CalculateFor').on ('change', function() {
            if( this.value === "bycredit" ) {
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