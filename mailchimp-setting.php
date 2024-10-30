<div class="wrap" style="max-width:950px !important;">
<h2>MailChimp Settings</h2>
<div class="dbx-content">
<script>
   var mailchimp_jQuery = jQuery.noConflict();
   mailchimp_jQuery(document).ready(function() { 
   options = { url:'<?php  echo get_bloginfo( 'wpurl' ).'/wp-content/plugins/import-users-to-mailchimp/mailchimp-import.php';?>', type: 'POST', dataType: 'text',beforeSubmit: inAdminbeforeSignupForm,success: inAdminon_success
            };
	  mailchimp_jQuery('#importusers').ajaxForm(options);
        }); 
   function inAdminbeforeSignupForm()
   {
		   mailchimp_jQuery('#mailchimpmessage').html('<img src="<?php  echo get_bloginfo( 'wpurl' ).'/wp-content/plugins/import-users-to-mailchimp/loader.gif';?>" />');
		  
   }
   
   function inAdminon_success(data)
   {
  
	         mailchimp_jQuery('#mailchimpmessage').html(data);
		
   }</script>
<form enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">

<?php
if ( !class_exists( 'MCAPI' ) )
{
	require_once 'include/MCAPI.class.php';
}

?>


<?php $apikey = get_option( 'apikey' );
		$api = new MCAPI( $apikey );
		

	print "<p><strong>API Key Setting</strong></p>";
$keystatus =  $api->lists();
	if ( $apikey && !$keystatus )
	{	print "<p><em>Unable to connet MailChimp Server with this key!</em> <p/>";
		print "<p>Set your valid Mailchimp API Key, which you can find on the <a href=\"http://us1.admin.mailchimp.com/account/api\">MailChimp website</a>, ";
		
	}
	else if ( $apikey && $keystatus )
	{		print "<p>Connected...<p/>";
		
	}
	else
	{
	print "<p><em>No API Key has been saved yet!</em></p>";
		print "<p>Set your Mailchimp API Key, which you can find on the <a href=\"http://us1.admin.mailchimp.com/account/api\">MailChimp website</a>, ";
		print "in the text box below. Once the API Key is set, you will see the various options.</p>";
	}
?>

<p>Set Your MailChimp API Key: <input type="text" name="apikey" size="60" value="<?php echo $apikey;?>" /></p>
<div class="submit"><input type="submit" name="save_apikey" value="Save API Key" /></div>
<?php 
if (  $apikey && $keystatus  )
{
	
?>
<p><strong>Mailing List setting</strong></p>

<?php
	

	
       // user selected list Id's
		$selectedIistId = get_option('listids');
		$idContainer = array();
		$idContainer = preg_split( "/[\s,]+/", $selectedIistId );

		print "<p>Please select mailing list, which you want to update?</p>";
		print "<ul>";
		foreach ( $keystatus as $list )
		{
			$listName = $list['name'];
			$list_id = $list['id'];

		
		

		
			$selected = array_search( $list_id, $idContainer );

		
			print "<li><input type=CHECKBOX value=\"$list_id\" name='searchableListID[]' ";
			if ( false === $selected ){} else
				print "checked";
			print "> $listName</li>";
		}

		print "</ul>";

		// Now add options for when to update the mailing list (add, delete, update)
		$duringRegistration = get_option('duringRegistration');
		$duringDelete = get_option('duringDelete');
		$duringUpdate = get_option('duringUpdate');

		print "<p>When would you like to update your selected Mailing Lists?</p>";
		print "<ul>";

		print "<li><input type=CHECKBOX value='1' name=\"duringRegistration\" ";
		if ( "0" == $duringRegistration ){} else
			print "checked";
		print "> When a user Register</li>";

		print "<li><input type=CHECKBOX value='1' name=\"duringDelete\" ";
		if ( "0" == $duringDelete ){} else
			print "checked";
		print "> When a user deleted</li>";

		print "<li><input type=CHECKBOX value='1' name=\"duringUpdate\" ";
		if ( "0" == $duringUpdate ){} else
			print "checked";
		print "> When a user updates his information</li>";

		print "</ul>";
	
	


?>
<div class="submit"><input type="submit" name="save_options" value="Save Options" /></div>
</form>
<form enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>" id="importusers" name="importusers"  method="POST">
<div id="mailchimpmessage"></div>
<p><strong>Import Existing User</strong></p>
<?php
	

	
       // user selected list Id's
		$selectedIistId = get_option('imporListId');

		$idContainer = array();
		$idContainer = preg_split( "/[\s,]+/", $selectedIistId );

		print "<p>Please select mailing list, which you want to update during users Import?</p>";
		print "<ul>";
		foreach ( $keystatus as $list )
		{
			$listName = $list['name'];
			$list_id = $list['id'];		
			$selected = array_search( $list_id, $idContainer );
		
			print "<li><input type=CHECKBOX value=\"$list_id\" name='imporListId[]' ";
			if ( false === $selected ){} else
				print "checked";
			print "> $listName</li>";
		}

		print "</ul>";
		?>
<div class="submit"><input type="submit" id="import" name="Import_Users" value="Import Users" /></div> <?php } ?>
</form>
</div>