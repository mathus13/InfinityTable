<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Database Tables Config
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2012, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

$config['user_table']                   = 'users';

$config['profiles_table']               = 'user_profiles';

$config['errors_table']                 = 'login_errors';

$config['IP_hold_table']                = 'ips_on_hold';

$config['username_or_email_hold_table'] = 'username_or_email_on_hold';

$config['denied_access_table']          = 'denied_access';

$config['registration_table']           = 'registration';

$config['temp_reg_data_table']          = 'temp_registration_data';

$config['custom_uploader_table']        = 'custom_uploader_table';

$config['auto_populate_table']          = 'auto_populate';

$config['category_menu_table']          = 'category_menu';

/* End of file db_tables.php */
/* Location: /application/config/db_tables.php */