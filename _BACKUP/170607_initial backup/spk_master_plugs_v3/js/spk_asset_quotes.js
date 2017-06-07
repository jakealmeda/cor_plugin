// var AjaxFileLocation = "https://test.understandingrelationships.com/wp-content/plugins/spk_quotes/php_ajax/",
var AjaxFileLocation = location.protocol + "//" + location.host + "/wp-content/plugins/spk_master_plugs_v3/ajax/",
    PhpQuery;

jQuery(document).ready(function() {

    var waitTime = 30000; // 30 seconds

    setInterval(function() {
        //alert("Message to alert every 1 minute");
        GetAnotherQuote();
    }, waitTime);

});


function GetAnotherQuote() {

    jQuery.ajax({
        type: "GET",
        url: AjaxFileLocation + "spk_quotes_query.php",
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