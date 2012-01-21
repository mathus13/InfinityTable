<?php
// You must call validation to get the users posted information
$val = $this->validation;
?>
Welcome to <?php echo $this->config->item('CL_website_name');?>,

To finalise your new account, you must follow the activation link below:
<?php echo $activate_url."\n";?>

Please activate your account within <?php echo $this->config->item('CL_temp_expire') / 60 / 60;?> hours otherwise your registration will become invalid and you will have to register again.

Remember. You can use either you username OR email address to login.
Your login details are as follows:

Login: <?php echo $username."\n";?>
Email: <?php echo $email."\n";?>
Password: <?php echo $password."\n";?>

We hope that you enjoy your stay with us :)

Regards,
The <?php echo $this->config->item('CL_website_name');?> Team