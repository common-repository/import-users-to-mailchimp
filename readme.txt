=== Import Users to MailChimp ===

Contributors: SandyIN
Tags: Import Users to MailChimp,newsletter,signup,email,custom MailChimp signup Form,Automatically subscibe uses to mailchip during registration
Requires at least: 2.5
Tested up to: 3.2.1
Stable tag: 2.4

The Import Users to MailChimp allows you to quickly and easily import all existing users to mailchimp.


== Description ==

This plugin allows you to quickly and easily import wordpress existing usres to mailchimp lists,add a signup form for your MailChimp list as a widget,
Automatically add, remove, and update users to your  mailing lists as users subscribe and unsubscribe to your site.

To use, save your MailChimp API Key on the options page then start Importing users to your slected MailChimp lists and You can configure the plugin to update your mailing list when 
1) a new user subscribes,

2) a user unsubscribes, or 

3)a user updates his information. 

You may also selecting options for the Merge Fields for setup, and then add the Widget to your site

Author URI:  [NoidaSoft.com] (http://www.noidasoft.com)


== Installation ==

= Version 1.0 =

1. Unzip our archive and upload the entire `import-users-to-mailchimp' directory to your `/wp-content/plugins/` directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Go to Options and look for sepearte menu "MailChimp Settings" at the left

4- Enter your mailchimp API key.

5- Select  your lists to have your visitors Automatically subscribe to.

6- Select  your lists to import all exiting usres.

7- On second memu "Mailchimp Signup Form" Select One of your lists to have your visitors subscribe to  and go to Presentation->Widgets and enable the 'MailChimp Signup Form' widget

= Advanced =

If you want to signup form an other place at the theme.
so, simple use this `<?php SingnUPForm(); ?>` code.


Note: you will need to install the Exec_PHP plugin to use that method of display. It can be found here:
http://wordpress.org/extend/plugins/exec-php


== Screenshots ==

1. Entering your MailChimp API
2. Selecting your MailChimp lists to Automatically add, remove, and update users to your MailChimp mailing list as users subscribe and unsubscribe to your WordPress site 
3. Import all existing usesrs to selected MailChimp lists
4. Configuring extra fields on your Signup Form and also labels



