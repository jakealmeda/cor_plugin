function GetAnotherQuote(){jQuery.ajax({type:"GET",url:spk_quoters.spk_quotes_ajax,data:"current_id="+jQuery("#ur_quote_pid").val(),datatype:"html",success:function(e){var t=e.split("|");jQuery("#quote_content").fadeOut("medium",function(){jQuery("#quote_content").html(t[0]).fadeIn("medium")}),jQuery("#ur_quote_pid").val(t[1])}})}jQuery(document).ready(function(){setInterval(function(){GetAnotherQuote()},3e4)});