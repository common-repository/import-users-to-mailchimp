<?php
ob_start();
include_once('../../../wp-blog-header.php');
include_once('mailchimp-functions.php');
signupToMailchimp($_POST);
?>	