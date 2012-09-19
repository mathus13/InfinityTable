<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Authentication Config
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2012, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

/*
| -----------------------------------------------------------------
|						DISABLE INSTALLER						
| -----------------------------------------------------------------
| If set to TRUE, the init controller is disabled.
| If set to FALSE, the init controller is enabled, and you will 
| be able to populate the database, create an admin, and create
| test users.
| 
*/

$config['disable_installer'] = TRUE;

/*
| -----------------------------------------------------------------
|							ACCOUNT TYPES							
| -----------------------------------------------------------------
| This definition sets the account types that will be used for authentication.
|
| Admin should remain being called Admin, but the key may be changed. 
| Keep in mind that if you change the number to higher than 9, then the 
| user_level field of the users table will need to be adjusted.
|
| No user level should ever be set with a key of 0.
|
*/

$config['account_types'] = array(
	'1' => 'Customer',
	'6' => 'Manager',
	'9' => 'Admin'
);

/*
| -----------------------------------------------------------------
|							ACCOUNT GROUPS							
| -----------------------------------------------------------------
| This definition sets the account groups that will be used for authentication.
|
*/

$config['account_groups'] = array(
	'Employees' => 'Manager,Admin'
);

/*
| -----------------------------------------------------------------
|						MAX_ALLOWED_ATTEMPTS						
| -----------------------------------------------------------------
| This definition sets the maximum amount of failed login attempts
| or failed password recovery attempts before the IP or username is
| placed on hold.
| 
*/

$config['max_allowed_attempts'] = 5;

/*
| -----------------------------------------------------------------
|							DENY_ACCESS						
| -----------------------------------------------------------------
| If for some reason login attempts exceed the max_login_attempts
| value, then when they reach the number held in this definition,
| their IP address is added to the deny list in the local Apache
| configuration file.
|
| SET TO ZERO TO DISABLE THIS FUNCTIONALITY
| 
*/

$config['deny_access'] = 10;

/*
| -----------------------------------------------------------------
|					DENIED ACCESS REASON						
| -----------------------------------------------------------------
| The reasons why an IP address may be in the deny list
| 
*/

$config['denied_access_reason'] = array(
	'0' => 'Not Specified',
	'1' => 'Login Attempts',
	'2' => 'Malicious User',
	'3' => 'Hacking Attempt',
	'4' => 'Spam',
	'5' => 'Obscene Language',
	'6' => 'Threatening Language'
);

/*
| -----------------------------------------------------------------
|					APACHE CONFIG FILE LOCATION						
| -----------------------------------------------------------------
| The location, including filename, or your Apache config file.
| 
*/

$config['apache_config_file_location'] = FCPATH . '.htaccess';

/*
| -----------------------------------------------------------------
|							SECONDS_ON_HOLD							
| -----------------------------------------------------------------
| This definition sets the amount of time an IP or username is on 
| hold if the maximum amount of failed login attempts or failed
| password recovery attempts is reached.
| 
| 600 seconds is 10 minutes
|
*/

$config['seconds_on_hold'] = 600;

/*
| -----------------------------------------------------------------
|						DISALLOW_MULTIPLE_LOGINS					
| -----------------------------------------------------------------
| This setting attempts to either allow or disallow an account to be 
| logged in by the same user on more than one device, or with more 
| than one browser on the same device.
|
*/

$config['disallow_multiple_logins'] = TRUE;

/*
| -----------------------------------------------------------------
|						ALLOW REMEMBER ME							
| -----------------------------------------------------------------
| This setting allows you to turn on and off the ability to have 
| a persistant login where users may choose to stay logged in 
| even after the browser has closed.
|
*/

$config['allow_remember_me'] = FALSE;

/*
| -----------------------------------------------------------------
|					REMEMBER ME COOKIE NAME							
| -----------------------------------------------------------------
| This setting allows you to choose the name of the remember me cookie.
| Remember that Internet Explorer doesn't like underscores.
|
*/

$config['remember_me_cookie_name'] = 'rememberMe';

/*
| -----------------------------------------------------------------
|					REMEMBER ME EXPIRATION							
| -----------------------------------------------------------------
| How long (in seconds) the remember me funcationality allows the session to last.
|
*/

$config['remember_me_expiration'] = 93062220;

/*
| -----------------------------------------------------------------
|					HTTP USER COOKIE NAME							
| -----------------------------------------------------------------
| This setting allows you to choose the name of the http user cookie.
| While the authentication cookie is handled in the session, the 
| http user cookie allows for the user data to be stored so that 
| the user is semi-identifiable, or for other general purpose use 
| related to the logged in user. DO NOT USE FOR AUTHENTICATION!
|
*/

$config['http_user_cookie_name'] = 'httpUser';

/*
| -----------------------------------------------------------------
|			CSRF FLASHDATA NAME AND EXPIRATION FOR HTTP*						
| -----------------------------------------------------------------
| *These settings only apply to a cookie that is set when "cookie_secure"
| is set to TRUE in config/config and CSRF is loaded on an HTTP page.
|
| The expiration setting sets the expiration for that cookie. The value is the
| number of seconds you want the cookie to last. Default expiration is 1 hour.
|
| The name is the cookie name ... duh!
|
| Note: While better than nothing, if you have "cookie_secure" 
| set to TRUE, it means you have a security certificate, and you 
| should consider that any page that has a form on it should 
| probably be an HTTPS page. This cookie offers less protection
| when compared to the session library, so if you are 
| using forms on HTTP pages, just know that those forms should 
| only contain simple data that is not sensitive.
|
*/

$config['csrfFd_cookie_name'] = 'csrfFd';
$config['csrfFd_expiration'] = 3600;

/*
| -----------------------------------------------------------------
|					RECOVERY CODE EXPIRATION							
| -----------------------------------------------------------------
| How long (in seconds) the password recovery code is good for.
| The default is two hours.
|
*/

$config['recovery_code_expiration'] = 60 * 60 * 2;

/*
| -----------------------------------------------------------------
|				DELETE SESSION COOKIE ON LOGOUT							
| -----------------------------------------------------------------
| When the user logs out, their session cookie can either have
| the userdata unset, or you can choose to have the cookie completely
| deleted. Set to FALSE to keep the cookie, TRUE to delete it.
| By default, CodeIgniter just deletes the userdata, so set to 
| FALSE if you want to maintain this behavior.
|
| Note: unless you set 'show_login_form_on_logout' to FALSE,
| the session cookie is immediately re-created.
*/

$config['delete_session_cookie_on_logout'] = FALSE;

/*
| -----------------------------------------------------------------
|				SHOW LOGIN FORM ON LOGOUT							
| -----------------------------------------------------------------
| When the user logs out, they can be presented with a login form
| on the logout page, or else just show the logout confirmation page.
| The default (TRUE) is to show the login form.
*/

$config['show_login_form_on_logout'] = TRUE;


/* End of file authentication.php */
/* Location: /application/config/authentication.php */