
jQuery(document).ready(function() {

    var waitTime = 30000; // 30 seconds * 1000 miliseconds

    setInterval(function() {
        //alert("Message to alert every 1 minute");
        GetAnotherQuote();
    }, waitTime);

});


function GetAnotherQuote() {

    jQuery.ajax({
        type: "GET",
        url: spk_quoters.spk_quotes_ajax,
        data: 'current_id='+jQuery( '#ur_quote_pid' ).val(),
        datatype: "html",
        success: function(result){

            var AnotherQuote = result.split( '|' );

            jQuery( '#quote_content' ).fadeOut('medium', function(){
                jQuery( '#quote_content' ).html( AnotherQuote[0] ).fadeIn('medium');
            });

            jQuery( '#ur_quote_pid' ).val( AnotherQuote[1] );

        }
    });

}