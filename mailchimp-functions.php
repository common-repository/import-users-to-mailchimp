<?php
/*
Plugin Name: Import users to MailChimp
Plugin URI: http://wordpress.org/extend/plugins/import-users-to-mailchimp/
Description: This plugin allows you to quickly and easily import wordpress existing usres to mailchimp lists,add a signup form for your MailChimp list as a widget,Automatically add, remove, and update users to your  mailing lists as users subscribe and unsubscribe to your site.
Author: SandyIN
Version: 1.0

 Copyright 2009 -Import users to MailChimp
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( !class_exists( 'MCAPI' ) )
{
	require_once 'include/MCAPI.class.php';
}


 if (function_exists('add_action')) {
	 			add_action('admin_menu', 'export_users_to_mailchimp_adminpage'); // Add Admin menu			
			
				add_action('user_register','duringRegistration');// execute when a new user registers				
                add_action('delete_user','duringDelete');	   // execute when a  user deleted
                add_action('profile_update','duringUpdate' );	// execute when a  user information updated
			    add_action('wp_head', 'scriptUrl');
				add_action('template_redirect', 'mailchimp_scripts');
				add_action('wp_head', 'mailchimp_css');
	}
function export_users_to_mailchimp_adminpage()
   {
    add_menu_page('Mailchimp Settings', 'Mailchimp Settings', 'manage_options', 'mailchimp-setting', 'export_users_to_mailchimp_admin');
    add_submenu_page( 'mailchimp-setting', 'Mailchimp Signup Form', 'Mailchimp Signup Form', 'manage_options', 'mailchimp-signup', 'signupform');
    }
	
	
	  
	
	
function export_users_to_mailchimp_admin() {
if (!current_user_can('manage_options'))
	{
    	wp_die( __('You do not have sufficient permissions to access this page.') );
  	}
 if( isset($_POST['save_apikey']) && !empty($_POST['apikey']))
 {
 	update_option('apikey',$_POST['apikey']);
	print '<div id="message" class="updated fade"><p>Saved API Key!</p></div>';
 }
 if( isset($_POST['save_options']))
	{
	
	foreach( $_POST['searchableListID'] as $postVar )
		{	
		 $ids .=  $postVar.",";			
		}
		update_option('listids',$ids);
	    update_option('duringRegistration',$_POST['duringRegistration']==1 ? 1 :0);
	    update_option('duringDelete',$_POST['duringDelete']==1 ? 1 :0);
	    update_option('duringUpdate',$_POST['duringUpdate']==1 ? 1 :0);	
		  print '<div id="message" class="updated fade"><p>Option Saved</p></div>';
	}
	 if( isset($_POST['Import_Users']))
	{
	foreach( $_POST['imporListId'] as $postVar )
		{	
		 $ids .=  $postVar.",";			
		}
		 update_option('imporListId',$ids);
        ImportAllUserToMailChimp();
	   print '<div id="message" class="updated fade"><p>Import Completed</p></div>';
	}
require_once 'mailchimp-setting.php';
}


/* During Registration*/

function duringRegistration($userID)
{

	    $apikey = get_option( 'apikey' );
		$api = new MCAPI( $apikey );
		$user_info = get_userdata( $userID );	
		if(get_option('duringRegistration') && $apikey )
		{
				$selectedIistId = get_option('listids');
				$idContainer = array();
				$idContainer = preg_split( "/[\s,]+/", $selectedIistId);
				$loop=0;
				while($loop <=  count($idContainer)-2 )
				{
				$list_id = $idContainer[$loop];
				$merge_vars = array('FNAME'=>$user_info->display_name, 'LNAME'=>$user_info->last_name);
				// you can set more information like 
			/*	
				$yourinterest = esc_attr( get_the_author_meta('yourinterest',$userID));
				$jobdescription = esc_attr( get_the_author_meta('jobdescription',$userID));
				$industry = esc_attr( get_the_author_meta('industry',$userID));
				$country = esc_attr( get_the_author_meta('country',$userID));
				
				$merge_vars = array('NAME'=>$user_info->first_name, 'USERNAME'=>$user_info->user_login,'SURNAME'=>$user_info->nickname, 'JOBDESCRIP'=>$jobdescription, 'INDUSTRY'=>$industry, 'YOURINTERE'=>$yourinterest, 'COUNTRY'=>$country); */
				
				
				$api->listSubscribe($list_id, $user_info->user_email, $merge_vars);   //add user information to mailchimp
				$loop++;
	            }
}
		}
		

function duringDelete($userID)
   {

	    $apikey = get_option( 'apikey' );
		$api = new MCAPI( $apikey );
		$user_info = get_userdata( $userID );	
		if(get_option('duringDelete') && $apikey )
		{
				$selectedIistId = get_option('listids');
				$idContainer = array();
				$idContainer = preg_split( "/[\s,]+/", $selectedIistId);
				$loop=0;
				while($loop <=  count($idContainer)-2 )
				{
				$list_id = $idContainer[$loop];
			   $api->listUnsubscribe( $list_id, $user_info->user_email ); // Delete from mailchimp
			   $loop++;
	            }
     }
  }
		
		function duringUpdate($userID)
    {

	    $apikey = get_option( 'apikey' );
		$api = new MCAPI( $apikey );
		$user_info = get_userdata( $userID );	
		if(get_option('duringUpdate') && $apikey )
		{
				$selectedIistId = get_option('listids');
				$idContainer = array();
				$idContainer = preg_split( "/[\s,]+/", $selectedIistId);
				$loop=0;
				while($loop <=  count($idContainer)-2 )
				{
				$list_id = $idContainer[$loop];
				$merge_vars = array('FNAME'=>$user_info->display_name, 'LNAME'=>$user_info->last_name);						
				$merge_vars['EMAIL'] = $user_info->user_email;
			    $retval = $api->listUpdateMember( $list_id, $user_info->user_email, $merge_vars);
				$loop++;
	            }
     }
		}
		
		function ImportAllUserToMailChimp()
		{
		global $wpdb;	
		$szSort = "user_nicename";
		$aUsersID = $wpdb->get_col( $wpdb->prepare(
			"SELECT $wpdb->users.ID FROM $wpdb->users ORDER BY %s ASC"
			, $szSort ));
		foreach ( $aUsersID as $iUserID ) :
		duringImport($iUserID);
		endforeach; 

		}
		function duringImport($userID)
{

	    $apikey = get_option( 'apikey' );
		$api = new MCAPI( $apikey );
		$user_info = get_userdata( $userID );	
		if($apikey )
		{     
				$selectedIistId = get_option('imporListId');
				$idContainer = array();
				$idContainer = preg_split( "/[\s,]+/", $selectedIistId);
				$loop=0;
				while($loop <=  count($idContainer)-2 )
				{
				$list_id = $idContainer[$loop];
				$merge_vars = array('FNAME'=>$user_info->display_name, 'LNAME'=>$user_info->last_name);
				// you can set more information like 
			/*	
				$yourinterest = esc_attr( get_the_author_meta('yourinterest',$userID));
				$jobdescription = esc_attr( get_the_author_meta('jobdescription',$userID));
				$industry = esc_attr( get_the_author_meta('industry',$userID));
				$country = esc_attr( get_the_author_meta('country',$userID));
				
				$merge_vars = array('NAME'=>$user_info->first_name, 'USERNAME'=>$user_info->user_login,'SURNAME'=>$user_info->nickname, 'JOBDESCRIP'=>$jobdescription, 'INDUSTRY'=>$industry, 'YOURINTERE'=>$yourinterest, 'COUNTRY'=>$country); */
				
				 $api->listSubscribe($list_id, $user_info->user_email, $merge_vars);   //add user information to mailchimp
				$loop++;
	         }
}
		}
		
		// Save options for signup forms
		
		function signupform()
	   {
	   $apikey = get_option( 'apikey' );
		    $api = new MCAPI( $apikey );
			 if( isset($_POST['update']) && $_POST['updatestatus']==1)
			  {
			  update_option('signupid',$_POST['signupid']);
			  print '<div id="message" class="updated fade"><p>Saved..</p></div>';
	   }
	   if( isset($_POST['feildsetting']) && $_POST['updatefeildsetting']==1)
	    {	  
	   foreach( $_POST['tangs'] as $postVar )
		 {	
		 $ids .=  $postVar.",";			 		
		 }
		  $selectedId = get_option('signupid');
		  $feilds = $api->listMergeVars($selectedId);		  
		  foreach($feilds as $var){
		  update_option('lab_'.$var['tag'],$_POST['lab_'.$var['tag']]);
		  }
		  
		print '<div id="message" class="updated fade"><p>Saved..</p></div>';
		update_option('tagssaved',$ids);
	    }
	   require_once 'mailchimp-signup.php';
	}
		
		// Show Signup form
		
		function SingnUPForm()
		{
		  
		   
		    $apikey = get_option( 'apikey' );
		    $api = new MCAPI( $apikey );
		    $selectedId = get_option('signupid');
		    $feilds = $api->listMergeVars($selectedId);
		    $selectedIistId = get_option('tagssaved');
		    $idContainer = array();
		    $idContainer = preg_split( "/[\s,]+/", $selectedIistId );
			$error = signupToMailchimp();
		   echo "<div id='mailchimp-newsletter'><form name='newsletter' method='post'  id='newsletter' action=''  >
		   <div id='mailchimpmessage'>".$error."</div><ul class='mailchimpform'>";
		  foreach($feilds as $var){
		      $opt = $var['tag'];
			 $selected = array_search( $opt, $idContainer );
			 
			 if ($var['req'])
			   {
				echo '<li><label>'.get_option('lab_'.$var['tag']).'*</label><input type="text" size="18" value="" name="'.$opt.'" id="'.$opt.'"/></li>';
				}
			 else 
			 { if ( false === $selected ){} else
			   {
				echo '<li><label>'.get_option('lab_'.$var['tag']).'</label><input type="text" size="18" value="" name="'.$opt.'" id="'.$opt.'"/></li>';
				}
				}
		  }
		  echo "<li><label></label><input type='hidden'  value='1' name='subscribestatus' id='subscribestatus'/><input type='submit' size='18' value='Subscribe' name='subscribe' id='subscribemailchimp'/></li></ul></form></div>";
		
		}

function mailchimp_scripts() {
	if(!is_admin())
	{
		wp_enqueue_script('mailchimp_scripts1', $src = WP_CONTENT_URL.'/plugins/import-users-to-mailchimp/mailchimp-ajax.js', $deps = array('jquery'));
	}
}

if (function_exists('register_sidebar_widget'))
		register_sidebar_widget('MailChimp Signup Form', 'SingnUPForm');

function scriptUrl(){
    $url = get_bloginfo( 'wpurl' ).'/wp-content/plugins/import-users-to-mailchimp/mailchimp-ajax-signup.php';
	$loader = get_bloginfo( 'wpurl' ).'/wp-content/plugins/import-users-to-mailchimp/loader.gif';
    echo '<script type="text/javascript">
var scriptUrl = "'.$url.'";
var loaderUrl = "'.$loader.'";
</script>';
}



 function signupToMailchimp()
 {
        if($_POST['subscribestatus']==1 && isset($_POST['subscribe'])):
        $apikey = get_option( 'apikey' );
		$api = new MCAPI( $apikey );
		$merge = array();
		$errs = array();
		$user_info = get_userdata( $userID );
		$selectedId = get_option('signupid');
		$feilds = $api->listMergeVars($selectedId);
		if($apikey && $selectedId )
		{
		foreach($feilds as $var){
		 if ($var['req'])
			   {
			   if(!$_REQUEST[$var['tag']])
			   $errs[]=get_option('lab_'.$var['tag'])." is required.";
			   }
	    if($var['tag']!='EMAIL')
		$merge[$var['tag']] = $_REQUEST[$var['tag']];  		
		}
		
			if(sizeof($errs)<1)
			{
			
					if(!$api->listSubscribe($selectedId, $_REQUEST['EMAIL'], $merge))
					{
					if($api->errorCode==214)
					{
					$errs[]="That email address is already subscribed to the list.";
					}
					else if($api->errorCode==250)
					{
					 list($field, $rest) = explode(' ',$api->errorMessage,2);
					 $errs[] = __("You must fill in").' '.htmlentities($field,ENT_COMPAT,'UTF-8').'.';
					}
					else if($api->errorCode==254)
					{
					list($i1, $i2, $i3, $field, $rest) = explode(' ',$api->errorMessage,5);
					$errs[] = sprintf(__("%s has invalid content"),htmlentities($field,ENT_COMPAT,'UTF-8')).'.';
					}
					else if($api->errorCode==502)
					{
					$errs[]="Invalid Email Address.";
					}
					else
					{
					$errs[] = $api->errorCode.":".$api->errorMessage;
					}
					}
					else
					{
					$msg = "<strong class='mailchimp-success'>Thank you for signed up! Please look for our confirmation subscription email!</strong>";
					}
					
			}		
		
		if (sizeof($errs)>0){
			$msg = '<ul class="mailchimp-error">';
			foreach($errs as $error){
				$msg .= "<li> ".htmlentities($error, ENT_COMPAT, 'UTF-8').'</li>';
			}
			$msg .= '</ul>';
		    }
		}
		
		echo $msg;
		endif;
 }


	function mailchimp_css()
	{
	$url = get_bloginfo( 'wpurl' ).'/wp-content/plugins/import-users-to-mailchimp/css/mailchimp-ajax.css';
	echo '<link rel="stylesheet" href="'.$url.'" media="screen" />';
	}


 function ajaxImport()
 {
     foreach( $_POST['imporListId'] as $postVar )
		{	
		 $ids .=  $postVar.",";			
		}
		 update_option('imporListId',$ids);
        ImportAllUserToMailChimp();
	    print '<div id="message" class="updated fade"><p>Import Completed</p></div>';
 }
?>