<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/****************************************
|
| @class    CL Auth
| @type     Library
| @author   Jason Ashdown (aka Flash)
| @version  BETA v0.2
|
*****************************************/

class CL_Auth extends CL_Core
{
/*
*
* Premade Login & Register Forms
* ------------------------------
* To get yourself started quickly with CL Auth, just call $this->cl_auth->login_form() or $this->cl_auth->register_form()
* in your controllers to generate one of these premade forms. You may tweak them as you see fit or copy these forms as a
* template and rewrite them with your own code.
*
*/
	function login_form()
	{
		if ( !$this->isValidUser() )
		{
			# Load Validation
			$this->ci->load->library('validation');
			$val = $this->ci->validation;

			$rules['username']	= "trim|required|xss_clean";
			$rules['password']	= "trim|required|xss_clean";


			$fields['username']	= 'Username';
			$fields['password']	= 'Password';
			$fields['remember'] = 'Remember';

			if ( $this->ci->config->item('CL_captcha_login') == TRUE )
			{
				# Shortcut
				$login_attempts = $this->ci->config->item('CL_captcha_login_attempts');
				$flash_attempts = $this->ci->session->flashdata('login_attempts') + 1;

				if ( $login_attempts < 1 OR $this->ci->session->flashdata('captcha_word') OR $flash_attempts > $login_attempts )
				{
					$rules['captcha_code'] = "trim|required|xss_clean|callback_captcha_check";
					$fields['captcha_code'] = 'Confirmation Code';
				}
			}

			$val->set_rules($rules);
			$val->set_fields($fields);

			if ( $val->run() == TRUE AND $this->login($val->username, $val->password, $val->remember) )
			{
				# If User has a stored URL of where they came from, grab it
				$request_uri = ($uri = $this->ci->session->flashdata('request_uri') AND !empty($uri)) ? $uri : ($this->redirect_url != '' ? $this->redirect_url : '');

				if ( $request_uri == $this->ci->config->item('CL_logout_uri') ) # If the user decides to login on the logout page??
				{
					# Homepage
					redirect('', 'location');
				}
				else
				{
					# Redirect User to Requested URL
					redirect($request_uri, 'location');
				}
			}
			else
			{
				if ( $this->isBanned() )
				{
					redirect($this->ci->config->item('CL_banned_uri'), 'location');
				}
				else
				{
					# Generate captcha
					if ( $this->ci->config->item('CL_captcha_login') == TRUE )
					{
						if ( $login_attempts < 1 OR $this->ci->session->flashdata('captcha_word') OR $flash_attempts > $login_attempts )
						{
							$this->captcha();
						}
					}

					# Keep redirect string in session until successful attempt
					if ( $this->redirect_url != '' )
					{
						$this->ci->session->set_flashdata('request_uri', $this->redirect_url);
					}
					else
					{
						$this->ci->session->keep_flashdata('request_uri');
					}

					$this->ci->load->view($this->ci->config->item('CL_login_page'));
				}

			}
		}
		else
		{
			$this->message = "You are already logged in.";
			$this->ci->load->view($this->ci->config->item('CL_logged_in_page'));
		}
	}

	function register_form()
	{
		if ( !$this->isValidUser() AND $this->ci->config->item('CL_allow_registration') == TRUE )
		{
			$this->ci->load->library('validation');
			$val = $this->ci->validation;

			$u_min = $this->ci->config->item('CL_username_min');
			$u_max = $this->ci->config->item('CL_username_max');
			$p_min = $this->ci->config->item('CL_password_min');
			$p_max = $this->ci->config->item('CL_password_max');

			$rules['username'] = "trim|required|xss_clean|min_length[$u_min]|max_length[$u_max]|callback_username_check|callback_username_format";
			$rules['password'] = "trim|required|xss_clean|min_length[$p_min]|max_length[$p_max]|matches[password_confirm]";
			$rules['password_confirm'] = 'trim|required|xss_clean';
			$rules['email'] = 'trim|required|xss_clean|valid_email|callback_email_check';
			$rules['terms'] = 'required';
			if ( $this->ci->config->item('CL_captcha_registration') == true )
			{
			$rules['captcha_code'] = 'trim|xss_clean|required|callback_captcha_check';
			}


			$val->set_rules($rules);

			$fields['username']	= 'Username';
			$fields['password']	= 'Password';
			$fields['password_confirm'] = 'Confirm Password';
			$fields['email'] = 'Email';
			$fields['terms'] = 'Terms & Conditions';
			if ( $this->ci->config->item('CL_captcha_registration') == true )
			{
			$fields['captcha_code'] = 'Confirmation Code';
			}

			$val->set_fields($fields);

			if ( $val->run() === TRUE AND $user = $this->register($val->username, $val->password, $val->email) )
			{
				// Register our new delightful user!
				if ( $this->ci->config->item('CL_email_verification') == TRUE )
				{
					$from = $this->ci->config->item('CL_support_email');
					$subject = $this->ci->config->item('CL_activate_subject');

					// Activation Link
					$user['activate_url'] = site_url($this->ci->config->item('CL_activate_uri')."/{$user['username_clean']}/{$user['activation_key']}");
					$message = $this->ci->load->view('auth/email/activate', $user, true);

					// Finally
					$this->_email($val->email, $from, $subject, $message);
				}
				else
				{
					$from = $this->ci->config->item('CL_support_email');
					$subject = $this->ci->config->item('CL_account_subject');
					$message = $this->ci->load->view('auth/email/account', '', true);

					// Finally
					$this->_email($val->email, $from, $subject, $message);
				}

				$request_uri = ($uri = $this->ci->session->flashdata('request_uri') AND !empty($uri)) ? $uri : '';

				$this->message = "You have successfully registered. Proceed to ".anchor(site_url($this->ci->config->item('CL_login_uri')), 'Login');
				$this->ci->load->view($this->ci->config->item('CL_register_success'));
			}
			else
			{
				if ( $this->ci->config->item('CL_captcha_registration') AND $this->captcha != true )
				{
					$this->captcha();
				}
				// Keep redirect string in session until successful attempt
				$this->ci->session->keep_flashdata('request_uri');

				$this->ci->load->view($this->ci->config->item('CL_register_page'));
			}
		}
		elseif ( $this->ci->config->item('CL_allow_registration') == FALSE )
		{
			$this->message = "Registration has been disabled.";
			$this->ci->load->view($this->ci->config->item('CL_register_disabled'));
		}
		else
		{
			$this->message = "You are already logged in.";
			$this->ci->load->view($this->ci->config->item('CL_logged_in_page'));
		}
	}
}

/*
*
* CL Core
* --------------------------
* These functions are the Bread & Butter of CL Auth.
* Refer to the documentation for examples and information regarding these functions.
*
*/

class CL_Core
{
	var $_fullAccess;
	var $_banned;
	var $cookie_name;
	var $captcha = false;
	var $captcha_img;

	var $user_error;
	var $message;

	var $redirect_url;

	function CL_Core()
	{
		$this->ci =& get_instance();

		log_message('debug', 'CL Auth Initialized');

		$this->ci->load->library('Session');

		$this->_init();
	}

	function _init()
	{
		$this->cookie_name = $this->ci->config->item('sess_cookie_name');

		// When we load this Module; Auto Login any returning users
		$this->autologin();

		if ( $this->ci->config->item('CL_Auth') != TRUE )
		{
			//$this->ci->load->lang('cl_auth');
			//$this->ci->lang->line('auth_disabled');
			echo "You have CL_Auth turned off.";
			exit;
		}
	}

/*
*
* AutoLogin Functions
*
*/

	function _create_autologin($user_id)
	{
		// User wants to be remembered
		$user = array(
		'login_id' => substr(md5(uniqid(rand().$this->ci->input->cookie($this->cookie_name))), 0, 16),
		'user_id' => $user_id);

		// Load Models
		$this->ci->load->model('cl_auth/user_autologin', 'user_autologin');

		// Prune keys
		$this->ci->user_autologin->pruneKeys($user['user_id']);

		if ( $this->ci->user_autologin->storeKey($user) )
		{
			// Set Users AutoLogin cookie
			$this->_autoCookie($user);

			return true;
		}

		return false;
	}

	function autologin()
	{
		if ( $auto = $this->ci->input->cookie('autologin') AND $this->ci->session->userdata('session_user_id') == 0 )
		{
			// Extract data
			$auto = unserialize($auto);

			if( isset($auto['login_id']) AND $auto['login_id'] AND $auto['user_id'])
			{
				// Load Models
				$this->ci->load->model('cl_auth/user_autologin', 'user_autologin');

				$query = $this->ci->user_autologin->getKey($auto['login_id'], $auto['user_id']);

				if ( $result = $query->row() )
				{
					// User verified, log them in
					$this->_setSession($result);
					// Renew users cookie; To prevent it from expiring
					$this->_autoCookie($auto);
					return true;
				}
			}
		}
		return false;
	}

	function _del_autologin()
	{
		if ( $auto = $this->ci->input->cookie('autologin') )
		{
			// Load Cookie Helper
			$this->ci->load->helper('cookie');

			// Load Models
			$this->ci->load->model('cl_auth/user_autologin', 'user_autologin');

			// Extract data
			$auto = unserialize($auto);

			// Delete db entry
			$this->ci->user_autologin->delKey($auto['login_id'], $auto['user_id']);

			set_cookie('autologin',	'',	-1);
		}
	}

	function _setSession($data)
	{
		$user = array(
		'session_user_id'	=> $data->id, // Used for the "clearSessions" function on Reset Password
		'user_id'			=> $data->id,
		'group_id'			=> $data->group_id,
		'username'			=> $data->username,
		'logged_in'			=> true);

		$this->ci->session->set_userdata($user);
	}

	function _autoCookie($data)
	{
		// Load Cookie Helper
		$this->ci->load->helper('cookie');

		$cookie = array(
		'name' => 'autologin',
		'value' => serialize($data),
		'expire' => $this->ci->config->item('CL_cookie_life'));

		set_cookie($cookie);
	}

/*
*
* End AutoLogin Functions
*
*/

	function check()
	{
		$_pass = false;

		// AutoLogin is now initiated in init() before this function is even called
		//$this->autologin(); // Must appear before $_group

		$_group = $this->ci->session->userdata('group_id');

		if ( $this->ci->config->item('CL_Auth') AND $_group != '' )
		{
			// Load Models
			$this->ci->load->model('cl_auth/group_uri', 'group_uri');

			$_controller = '/'.$this->ci->uri->rsegment(1);
			$_action = $_controller.'/'.$this->ci->uri->rsegment(2);

			$query = $this->ci->group_uri->findURI(array('/', $_controller, $_action), $_group);

			if ( $query->num_rows() )
			{
				$data = $query->row();

				$_pass = true;
				$this->_fullAccess = $data->is_admin;
			}
		}

		if ( $_pass == false )
		{
			$this->denyAccess($_group);
		}
	}

	function checkBanned($id)
	{
		// Load Models
		$this->ci->load->model('cl_auth/users', 'users');

		$query = $this->ci->users->checkBan($id);
		$row = $query->row();

		return $row->banned ? true : false;
	}

	function denyAccess($_user='')
	{
		if ($_user == '')
		{
			$this->ci->session->set_flashdata('request_uri', $this->ci->uri->uri_string());

			redirect($this->ci->config->item('CL_login_uri'), 'location');
			exit;
		}
		else
		{
			redirect($this->ci->config->item('CL_deny_uri'), 'location'); // Forbidden 403
			exit;
		}
	}

/*
*
* isWhatever functions
*
*/

	function isAdmin()
	{
		return $this->_fullAccess ? true : false;
	}

	function isValidUser()
	{
		if ( $this->ci->session AND $this->ci->config->item('CL_Auth') )
			return $this->ci->session->userdata('username') ? true : false;

		return false;
	}

	function isBanned()
	{
		if ( $this->_banned == true )
		{
			$this->logout();
			return true;
		}
		return false;
	}

	function isGroup($group_id=array())
	{
		if ( is_string($group_id) )
		{
			$group_id = array($group_id);
		}

		$pass = false;

		foreach ( $group_id as $val )
		{
			if ( $this->ci->session->userdata('group_id') == $val )
			{
				$pass = true;
			}
		}

		return $pass;
	}

	function getUserID()
	{
		return $this->ci->session->userdata('session_user_id');
	}

	function getUsername()
	{
		return $this->ci->session->userdata('username');
	}

/*
*
* Check functions
*
*/

	function username_check($username)
	{
		// Load Models
		$this->ci->load->model('cl_auth/users', 'users');
		$this->ci->load->model('cl_auth/user_temp', 'user_temp');

		$users = $this->ci->users->checkUsername($username);
		$temp = $this->ci->user_temp->checkUsername($username);

		return (($users->num_rows() + $temp->num_rows()) > 0) ? true : false;
	}

	// Minimum requirements
	// Username: First & Last character should not contain any bad symbols.
	function username_format($username)
	{
		$first = substr($username, 0, 1);
		$last = substr($username, -1);

		$chars = $this->ci->config->item('CL_username_chars');

		if ( !preg_match("/^[-a-z0-9$chars]/i", $username) )
		{
			return true;
		}
		elseif ( !preg_match('/^[a-z0-9]/i', $first) )
		{
			return true;
		}
		elseif ( !preg_match('/^[a-z0-9]/i', $last) )
		{
			return true;
		}

		return false;
	}

	function email_check($email)
	{
		// Load Models
		$this->ci->load->model('cl_auth/users', 'users');
		$this->ci->load->model('cl_auth/user_temp', 'user_temp');

		$users = $this->ci->users->checkEmail($email);
		$temp = $this->ci->user_temp->checkEmail($email);

		return (($users->num_rows() + $temp->num_rows()) > 0) ? true : false;
	}

	function captcha_check($code)
	{
		// Captcha Expired
		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ( ($this->session->flashdata('captcha_time') + $this->config->item('CL_captcha_expire')) < $now )
		{
			// Will replace this error msg with $lang
			$this->message = 'Your confirmation code has expired. Please try again.';
			return false;
		}
		elseif ( $code != $this->session->flashdata('captcha_word') )
		{
			$this->message = 'Your confirmation code does not match the one in the image. Try again.';
			return false;
		}

		return true;
	}

	function login($login, $password, $remember=true)
	{
		// Load Models
		$this->ci->load->model('cl_auth/users', 'users');
		$this->ci->load->model('cl_auth/user_temp', 'user_temp');

		if ( !empty($login) AND !empty($password) )
		{
			if ( $query = $this->ci->users->getLogin($login) AND $query->num_rows() == 1 )
			{
				$row = $query->row();

				if ( $row->banned != 0 )
				{
					$this->_banned = true;
					return false;
				}

				$password = $this->_encode($password);
				$stored_hash = $row->password;

				if ( crypt($password, $stored_hash) === $stored_hash )
				{
					$this->_setSession($row); // Set Users login
					if ( $row->newpass )
					{
						$this->ci->users->clear_newpass($row->id); // Clear any Reset Passwords
					}

					if ( $remember == TRUE )
					{
						$this->_create_autologin($row->id);
					}

					$this->ci->users->clearAttempts($login); // Reset users login attempts
					return true;
				}
				else
				{
					$this->user_error = "Your Password was incorrect.";
				}

				if ( $this->ci->config->item('CL_captcha_login') == TRUE )
				{
					$this->ci->session->set_flashdata('login_attempts', ++$row->login_attempts);
				}

				// Failed to confirm password
				$this->ci->users->failedAttempt($login); // Count attempt
			}
			elseif ( $query = $this->ci->user_temp->getLogin($login) AND $query->num_rows() == 1 )
			{
				$this->user_error = "Your Username hasn't been activated yet! Please check your email.";
			}
			else
			{
				//$val->set_message('username_error', 'Your Username did not match any in our records.');
				$this->user_error = "Your Username did not match any in our records.";
			}
		}

		return false;
	}

	function logout()
	{
		if ( $this->ci->input->cookie('autologin') ) {
			$this->_del_autologin();
		}

		if ( $this->ci->session ) {
			$this->ci->session->sess_destroy(); // This destroys only the cookie, we must destroy the current session independantly

			// Destory users active session
			$user = array(
			'session_user_id'	=> 0, // Used for the "clearSessions" function on Reset Password
			'user_id'			=> 0,
			'group_id'			=> 0,
			'username'			=> '',
			'logged_in'			=> false);

			$this->ci->session->set_userdata($user);
		}
	}

	function register($username, $password, $email)
	{
		if ( $this->ci->config->item('CL_allow_registration') == TRUE )
		{
			// Load Models
			$this->ci->load->model('cl_auth/users', 'users');
			$this->ci->load->model('cl_auth/user_temp', 'user_temp');

			$this->ci->load->helper('url');

			$new_user = array(
			'user_ip'	=> $this->ci->input->ip_address(),
			'username'	=> $username,
			'username_clean' => url_title($username),
			'password'	=> crypt($this->_encode($password)),
			'email'		=> $email);

			$temp = $this->ci->config->item('CL_email_verification');

			if ( $temp )
			{
				$new_user['activation_key'] = md5(rand().microtime());
			}

			$insert = $temp ? $this->ci->user_temp->createTemp($new_user) : $this->ci->users->createUser($new_user);

			if ( !$temp )
			{
				// Create Profile
				$this->ci->users->createProfile($this->ci->db->insert_id());
			}

			if ( $insert )
			{
				// Replace password with plain for email
				$new_user['password'] = $password;
				return $new_user;
			}
		}
		return false;
	}

	function forgotten_pass($login)
	{
		if ($login)
		{
			// Load Models
			$this->ci->load->model('cl_auth/users', 'users');

			if ($query = $this->ci->users->getLogin($login) AND $query->num_rows() == 1)
			{
				// Get User Data
				$row = $query->row();

				if ( strtotime($row->newpass_time) < time() )
				{
					$data['password'] = $this->_gen_pass();
					$encode = crypt($this->_encode($data['password'])); // Encode & Crypt pass

					$data['key'] = md5(rand().microtime());

					$this->ci->users->newpass($row->id, $encode, $data['key']);

					$data['reset_uri'] = site_url($this->ci->config->item('CL_reset_uri')."/{$row->username_clean}/{$data['key']}");

					$message = $this->ci->load->view($this->ci->config->item('CL_forgotten_email'), $data, true);

					$this->_email($row->email, $this->ci->config->item('CL_webmaster_email'), 'New Password request', $message);
					return true;
				}
				else
				{
					$this->user_error = "You already have a new password set. Please check your email.";
				}
			}
			else
			{
				$this->user_error = "Sorry, that username or email address does not exist.";
			}
		}
		return false;
	}

	function reset_pass($user_id, $key='')
	{
		// Load Models
		$this->ci->load->model('cl_auth/users', 'users');
		$this->ci->load->model('cl_auth/user_autologin', 'user_autologin');

		if ( !empty($user_id) AND !empty($key) AND $this->ci->users->activate_newpass($user_id, $key) AND $this->ci->db->affected_rows() > 0 )
		{
			$this->ci->user_autologin->clearKeys($user_id);
			$this->ci->user_autologin->clearSessions($user_id);
			return true;
		}
		return false;
	}

	function activate($username, $key='')
	{
		// Load Models
		$this->ci->load->model('cl_auth/users', 'users');
		$this->ci->load->model('cl_auth/user_temp', 'user_temp');

		if ( $this->ci->config->item('CL_email_verification') )
		{
			$this->ci->user_temp->pruneTemp();
		}

		if ( $query = $this->ci->user_temp->activateUser($username, $key) AND $query->num_rows() > 0 )
		{
			$row = $query->row_array();

			$del = $row['id'];

			// Unset any unwanted fields
			unset($row['id']); // We don't want to copy the id across
			unset($row['activation_key']);

			if ( $this->ci->users->createUser($row) )
			{
				// Create Profile
				$this->ci->users->createProfile($this->ci->db->insert_id());
				// Delete user from temp
				$this->ci->user_temp->deleteUser($del);
				return true;
			}
		}

		return false;
	}

	function change_password($old_pass, $new_pass)
	{
		// Load Models
		$this->ci->load->model('cl_auth/users', 'users');

		if ( $query = $this->ci->users->getUserById($this->ci->session->userdata('user_id')) AND $query->num_rows() > 0 )
		{
			$row = $query->row();

			$pass = $this->_encode($old_pass);

			if ( crypt($pass, $row->password) === $row->password )
			{
				$new_pass = crypt($this->_encode($new_pass));

				return $this->ci->users->change_password($new_pass, $this->ci->session->userdata('user_id'));
			}

			$this->user_error = "Your Old Password is incorrect.";
		}
		return false;
	}

	function _gen_pass($len = 8)
	{
		// No Zero (for user clarity);
		$pool = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$str = '';
		for ($i = 0; $i < $len; $i++)
		{
			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}

		return $str;
	}

/*
* Function: _encode
* Modified for CL_Auth
* Original Author: FreakAuth_light 1.1
*/
	function _encode($password)
	{
		$majorsalt = '';

		// if you set your encryption key let's use it
  		if ($this->ci->config->item('encryption_key')!='')
		{
			// concatenates the encryption key and the password
			$_password = $this->ci->config->item('encryption_key').$password;
		}
		else
		{
			$_password = $password;
		}

		// if PHP5
		if (function_exists('str_split'))
		{
		    $_pass = str_split($_password);
		}
		// if PHP4
		else
		{
			$_pass = array();
		    if (is_string($_password))
		    {
		    	for ($i = 0; $i < strlen($_password); $i++)
		    	{
		        	array_push($_pass, $_password[$i]);
		        }
		     }
		}

		// encrypts every single letter of the password
		foreach ($_pass as $_hashpass)
		{
			$majorsalt .= md5($_hashpass);
		}

        // encrypts the string combinations of every single encrypted letter
        // and finally returns the encrypted password
		return md5($majorsalt);
	}

	function captcha()
	{
		$this->ci->load->plugin('CL_captcha');

		$vals = array(
			'img_path'	 => $this->ci->config->item('CL_captcha_path'),
			'img_url'	 => base_url().'captcha/',
			'font_path'	 => $this->ci->config->item('CL_captcha_fonts_path'),
			'font_size'  => $this->ci->config->item('CL_captcha_font_size'),
			'img_width'	 => $this->ci->config->item('CL_captcha_width'),
			'img_height' => $this->ci->config->item('CL_captcha_height'),
			'show_grid'	 => $this->ci->config->item('CL_captcha_grid'),
			'expiration' => $this->ci->config->item('CL_captcha_expire')
		);

		$cap = create_captcha($vals);

		$store = array(
		'captcha_word' => $cap['word'],
		'captcha_time' => $cap['time']);

		// Plain, simple but effective
		$this->ci->session->set_flashdata($store);

		// Set our captcha
		$this->captcha = true;
		$this->captcha_img = $cap['image'];
	}

	function _email($to, $from, $subject, $message)
	{
		$this->ci->load->library('email');
		$email = $this->ci->email;

		$email->from($from);
		$email->to($to);
		$email->subject($subject);
		$email->message($message);

		return $email->send();
	}
}

?>