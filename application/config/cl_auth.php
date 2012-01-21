<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| CONFIGURATION ARRAY FOR CL AUTH
|
| @subpackage  Config
| @category    CL Auth Configuration
| @author      Jason Ashdown aka Flash
| @copyright   Copyright (c) 2008
| @version     BETA v0.2
*/

/*
=====================
MAIN CONFIGURATION
=====================
*/

// Enable/Disable (WARNING! Disabling may break your pages)
$config['CL_Auth'] = TRUE;

// Site Details
$config['CL_website_name'] = 'Your Website';
$config['CL_webmaster_email'] = 'webmaster@yourhost.com';

$config['CL_cookie_life'] = 60*60*24*31*2; // Default 2 months

// Session Class
$config['track_activity'] = TRUE;
$config['regen'] = TRUE;

// Database
$config['CL_table_prefix'] = 'cl_';

$config['CL_users_table'] = 'users';
$config['CL_user_profile_table'] = 'user_profile';
$config['CL_user_temp_table'] = 'user_temp';
$config['CL_groups_table'] = 'groups';
$config['CL_group_to_uri_table'] = 'group_uri';

// URI Locations
$config['CL_banned_uri'] = '/auth/banned';
$config['CL_deny_uri'] = '/auth/deny';
$config['CL_login_uri'] = '/auth/login';
$config['CL_logout_uri'] = '/auth/logout';
$config['CL_register_uri'] = '/auth/register';
$config['CL_activate_uri'] = '/auth/activate';
$config['CL_forgotten_uri'] = '/auth/forgotten';
$config['CL_reset_uri'] = '/auth/reset';
$config['CL_changepass_uri'] = '/auth/changepassword';

// Registration
$config['CL_allow_registration'] = TRUE;
$config['CL_email_verification'] = FALSE;
$config['CL_captcha_registration'] = TRUE;
$config['CL_temp_expire'] = 60*60*24*2; // Default 48 Hours

$config['CL_username_min'] = 3; // Default 3
$config['CL_username_max'] = 25; // Default 25
$config['CL_password_min'] = 6; // Default 6
$config['CL_password_max'] = 18; // Default 18

$config['CL_username_chars'] = ''; // Regular expression. Allow extra characters in registration e.g. $%._\s@'" (\s is a whitespace in reg exp)

// Login
$config['CL_captcha_login'] = TRUE;
$config['CL_captcha_login_attempts'] = 3;
/*
* Maxlength characters that can be used on the login field.
* Usernames can be email addresses, so its been boosted to 80 characters.
*/
$config['CL_login_maxlength'] = 80;

$config['CL_forgotten_expire'] = 900; // Default 15mins

// Captcha Settings
$config['CL_captcha_path'] = './captcha/'; // Absolute path to directory
$config['CL_captcha_fonts_path'] = $config['CL_captcha_path'].'fonts'; // Usually located in the same folder as captcha
$config['CL_captcha_width'] = 320;
$config['CL_captcha_height'] = 95;
$config['CL_captcha_font_size'] = ''; // Leave blank for random font-size
$config['CL_captcha_grid'] = TRUE;

$config['CL_captcha_expire'] = 180; // Default 180 seconds = 3 mins

// Email
$config['CL_support_email'] = 'webmaster@yourhost.com';
$config['CL_account_subject'] = $config['CL_website_name'].' account details';
$config['CL_activate_subject'] = $config['CL_website_name'].' activation';

$config['CL_activate_email'] = 'auth/email/activate';
$config['CL_account_email'] = 'auth/email/account';
$config['CL_forgotten_email'] = 'auth/email/forgotten';

// Forms
$config['CL_login_page'] = 'auth/login_form';
$config['CL_register_page'] = 'auth/register_form';
$config['CL_forgotten_page'] = 'auth/forgotten_form';
$config['CL_changepass_page'] = 'auth/changepass_form';

// Pages
$config['CL_deny_page'] = 'auth/general_message';
$config['CL_banned_page'] = 'auth/general_message';
$config['CL_logged_in_page'] = 'auth/general_message';
$config['CL_logout_page'] = 'auth/general_message';

$config['CL_register_success'] = 'auth/general_message';
$config['CL_activate_success'] = 'auth/general_message';
$config['CL_forgotten_success'] = 'auth/general_message';
$config['CL_reset_success'] = 'auth/general_message';
$config['CL_changepass_success'] = 'auth/general_message';

$config['CL_register_disabled'] = 'auth/general_message';
$config['CL_activate_failed'] = 'auth/general_message';
$config['CL_reset_failed'] = 'auth/general_message';

$config['CL_terms_page'] = 'auth/terms';


$config['CL_header'] = 'cl_header';
$config['CL_footer'] = 'cl_footer';
?>