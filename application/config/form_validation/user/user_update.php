<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Form Validation Rules for User Updates
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2012, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

// SELF UPDATE ---------------------------
$config['self_update'] = array(
	array(
		'field' => 'user_email',
		'label' => 'EMAIL ADDRESS',
		'rules' => 'trim|required|max_length[255]|valid_email|external_callbacks[formval_callbacks,_update_email,self_update]'
	),
	array(
		'field' => 'user_pass',
		'label' => 'PASSWORD',
		'rules' => 'trim|matches[user_pass_confirm]|external_callbacks[formval_callbacks,_check_password_strength,FALSE]'
	),
	array(
		'field' => 'user_pass_confirm',
		'label' => 'CONFIRMED PASSWORD',
		'rules' => 'trim'
	),
	array(
		'field' => 'last_name',
		'label' => 'LAST NAME',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'first_name',
		'label' => 'FIRST NAME',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'license_number',
		'label' => 'LICENSE NUMBER',
		'rules' => 'trim|required|alpha_numeric|max_length[8]'
	)
);

// UPDATE USER ---------------------------
$config['update_user'] = array(
	array(
		'field' => 'user_level',
		'label' => 'USER LEVEL',
		'rules' => 'trim|required|integer|external_callbacks[formval_callbacks,_stop_level_up]'
	),
	array(
		'field' => 'user_email',
		'label' => 'EMAIL ADDRESS',
		'rules' => 'trim|required|max_length[255]|valid_email|external_callbacks[formval_callbacks,_update_email,update_user]'
	),
	array(
		'field' => 'user_pass',
		'label' => 'PASSWORD',
		'rules' => 'trim|matches[user_pass_confirm]|external_callbacks[formval_callbacks,_check_password_strength,FALSE]'
	),
	array(
		'field' => 'user_pass_confirm',
		'label' => 'CONFIRMED PASSWORD',
		'rules' => 'trim'
	),
	array(
		'field' => 'user_banned',
		'label' => 'BANNED',
		'rules' => 'trim|integer'
	),
	array(
		'field' => 'last_name',
		'label' => 'LAST NAME',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'first_name',
		'label' => 'FIRST NAME',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'license_number',
		'label' => 'LICENSE NUMBER',
		'rules' => 'trim|required|alpha_numeric|max_length[8]'
	)
);

// PROFILE IMAGE -------------------------
$config['profile_image'] = array(
	array(
		'field' => 'profile_image',
		'label' => 'PROFILE IMAGE',
		'rules' => 'trim'
	)
);

/* End of file user_update.php */
/* Location: /application/config/form_validation/user/user_update.php */