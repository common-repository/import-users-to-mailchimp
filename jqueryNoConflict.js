var noConMailChimp = jQuery.noConflict();
noConMailChimp(document).ready(function(){ 
noConMailChimp('#subscribemailchimp').click(function()
								   {
									   alert(scriptUrl);
									   noConMailChimp.ajax({
   type: "POST",
   url: scriptUrl,
   dataType: "html",
   success: function(msg){
     alert( "Data Saved: " + msg );
   }
 });
								   });						   

 });