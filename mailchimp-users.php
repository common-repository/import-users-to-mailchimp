<div class="wrap" style="max-width:950px !important;">
<h2>MailChimp Users</h2>
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
<?php
foreach ( $keystatus as $list )
		{
			$listName = $list['name'];
			$list_id = $list['id'];
$retval = $api->listMembers($list_id, 'subscribed', null, 0, 5000 );

if ($api->errorCode){
	//echo "Unable to load listMembers()!";
	//echo "\n\tCode=".$api->errorCode;
	//echo "\n\tMsg=".$api->errorMessage."\n";
	//echo "Members returned: ". sizeof($retval). "\n";
} else {
	//echo "Members returned: ". sizeof($retval). "\n";
	foreach($retval as $member){
	 
		$retval1 = $api->listMemberInfo( $list_id, $member['email']);
		 foreach($retval1 as $k=>$v){
        if (is_array($v)){
            //handle the merges
            foreach($v as $l=>$w){
                echo "\t$l = $w\n";
            }
        } else {
            echo "$k = $v\n";
        }
    }
	}
}
		
		

		/*
			$selected = array_search( $list_id, $idContainer );

		
			print "<option><input type=CHECKBOX value=\"$list_id\" name='searchableListID[]' ";
			if ( false === $selected ){} else
				print "checked";
			print "> $listName</option>";*/
		}

?>
</div>