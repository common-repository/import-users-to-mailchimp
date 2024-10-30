   var mailchimp_jQuery = jQuery.noConflict();
   mailchimp_jQuery(document).ready(function() { 
   options = { url: scriptUrl, type: 'POST', dataType: 'text',
                beforeSubmit: beforeSignupForm, 
                success: on_success
            };
	  mailchimp_jQuery('#newsletter').ajaxForm(options);
        }); 
   function beforeSignupForm()
   {
		   mailchimp_jQuery('#mailchimpmessage').html('<img src="'+loaderUrl+'" />');
		    mailchimp_jQuery('#subscribemailchimp').attr("disabled","disabled");
			 mailchimp_jQuery('#subscribemailchimp').attr("value","Subscibe...");
   }
   
   function on_success(data)
   {
	         mailchimp_jQuery('#subscribemailchimp').attr("value","Subscibe");
			 mailchimp_jQuery('#subscribemailchimp').attr("disabled","");
	   mailchimp_jQuery('#mailchimpmessage').html(data);
	     var reg = new RegExp("class='mailchimp-success'", 'i');
    if (reg.test(data)){
        mailchimp_jQuery('#newsletter').each(function(){
	        this.reset();
    	});
		 
			 
	}
   }