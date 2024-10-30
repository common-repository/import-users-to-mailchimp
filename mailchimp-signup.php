<div class="wrap" style="max-width:950px !important;"><form enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
<h2>MailChimp Signup Form Setup</h2>
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
?><br><br>
<?php  $selectedId = get_option('signupid');
if (  $apikey && $keystatus   )
{	
?>
<p><strong>Please select the List you wish to create a Signup Form for</strong></p>

<select name="signupid" style="min-width:200px;">
 <option value=""> Select A List</option>';
<?php
 
foreach ( $keystatus as $list )
		    {
			 if ($list['id'] == $selectedId){
	            $sel = ' selected="selected" ';
	        } else {
	            $sel = '';
	        }
			
			
  echo '<option value="'.$list['id'].'" '.$sel.'>'.$list['name'].'</option>';
           				
		}

?>
</select>
<input type="hidden" name="updatestatus" value="1" />
<input type="submit" name="update" value="submit" class="button" />

<?php $feilds = $api->listMergeVars($selectedId); ?>
<h4>Feilds Includes</h4>
<?php
if (sizeof($feilds)==0 || !is_array($feilds)){
	echo "<p> No Feilds Found</p>";
} else {
	?>
	
	<table class='widefat'>
	<tr valign="top">
	<th>Labels</th>
	<th>Feilds</th>
	<th>Status</th>
	<th>Choose</th>
	</tr>
	<?php
	 $selectedIistId = get_option('tagssaved');
		$idContainer = array();
		$idContainer = preg_split( "/[\s,]+/", $selectedIistId );
		
	foreach($feilds as $var){
	$label = "lab_".$var['tag'];
	if(get_option($label))
	{
	$var['name'] = get_option($label);
	}
	else
	{
	$var['name'] = $var['name'];
	}
		echo '<tr valign="top">
			<td><input type="text" name="lab_'.$var['tag'].'" value="'.$var['name'].'"></td>
			<td>'.$var['tag'].'</td>
			<td>'.($var['req']==1?'Y':'N').'</td><td>';
		if (!$var['req']){
			  $opt = $var['tag'];
			 $selected = array_search( $opt, $idContainer );
			
				
			echo '<input name="tangs[]" type="checkbox" ';
		if ( false === $selected ){} else
				echo "checked='checked'";
			echo 'id="'.$opt.'" value="'.$opt.'"  />';
		} else {
			echo ' * ';
		}
		echo '</td></tr>';
	}
	echo '</table>';
} if($selectedId)
{?><p><input type="hidden" name="updatefeildsetting" value="1" />
<input type="submit" name="feildsetting" value="Update Feilds Setting" class="button" />
</p>
<? } } ?>
</form>
</div>