<?php
$this->load->helper('html');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<title>Example : CL Auth</title>

<?php echo link_tag('cl_style/main.css'); ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv='expires' content='-1' />
<meta http-equiv='pragma' content='no-cache' />
<meta name='robots' content='none' />

</head>

<body>

<a name="top"></a>
<div id="header">
<?php
$img = img(array('src' => 'cl_style/images/cl_auth_logo.gif', 'style' => 'float: left; margin-right: 10px;'));
echo anchor('example', $img);
?>
	<?php echo anchor('http://www.jasonashdown.co.uk/cl_auth_doc/', 'CL Auth Documentation'); ?>
	<div>Author: <a href="http://www.jasonashdown.co.uk" target="_blank">Jason Ashdown</a></div>
</div>

<?php
/*
* Login Bar
* ---------
* If visitor is not a logged in user, display our Login Bar
*/
if ( !$this->cl_auth->isValidUser() ) : ?>
<p class="small">You can have this <b>Login Bar</b> displayed throughout your website so anyone can login from anywhere.
<div id="login-bar">
<?php

$username = array(
'name'		=> 'username',
'id'		=> 'username',
'size'		=> 20,
'maxlength'	=> $this->config->item('CL_login_maxlength'));


$password = array(
'name'		=> 'password',
'id'		=> 'password',
'size'		=> 20,
'maxlegnth'	=> $this->config->item('CL_password_max'));


$remember = array(
'name'	=> 'remember',
'id'	=> 'remember',
'value'	=> true,
'style' => 'margin: 0');


// Start our Login Bar Here
echo form_open($this->config->item('CL_login_uri'));

echo form_label('Username ', $username['id']).form_input($username);
echo form_label('Password ', $password['id']).form_password($password);
// Remember Me checkbox (default: turned on)
echo form_checkbox($remember).form_label('Remember me', $remember['id'], array('style' => 'margin-right: 20px'));

// Submit button
echo form_submit('login', 'login');

// Forgotten password
echo anchor($this->config->item('CL_forgotten_uri'), 'forgotten password?');
//echo form_hidden('remember', 'true'); // You can use this "remember me" instead of a checkbox

echo form_hidden('redirect_url', $this->uri->uri_string()); // Redirect the user back to the page where they logged in from
echo form_close();
?>
</div>
<?php
endif;
?>

<div id="user-links">
<?php
if ( !$this->cl_auth->isValidUser() ) :

echo anchor($this->config->item('CL_login_uri'), 'Login').' - ';
echo anchor($this->config->item('CL_register_uri'), 'Register').' ';

else:

?>Welcome <strong><?php echo $this->cl_auth->getUsername(); ?></strong>, 
<?php
echo anchor($this->config->item('CL_changepass_uri'), 'Change Password') . ', ';
echo anchor($this->config->item('CL_logout_uri'), 'Logout');
?>

<?php
endif;
?>
</div>

<div id="content">
