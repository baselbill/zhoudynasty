<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: admin.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : load admin actions
\************************************************/

//-----------------------------------------------
// Initialize some stuff
//-----------------------------------------------
ob_start('ob_gzhandler');
error_reporting(E_ALL);
define('UPLOADER', 1);

//-----------------------------------------------
// Require neccessary files and initialize objects
//-----------------------------------------------
require_once('includes/common.inc.php');
require_once('includes/template.class.php');
require_once('includes/functions.inc.php');
$Template = new Template('templates');
$Template->_cache = 1;
$Template->_cache_dir = 'cache/';
$start = timer();

//-----------------------------------------------
// Load the settings file
//-----------------------------------------------
$Settings = load_file($settings_file);

//-----------------------------------------------
// Files to include and the action
//-----------------------------------------------
$includes = array(
                    'default'               => 'admin_stats.php',
                    'login'                 => 'admin_login.php',
                    'logout'                => 'admin_logout.php',
                    'log'                   => 'admin_log.php',
                    'settings'              => 'admin_settings.php',
                    'delete'                => 'admin_delete.php',
                    'changepassword'        => 'admin_changepassword.php',
                    'users'                 => 'admin_manageusers.php',
                );
if( !isset($_GET['action']) || empty($_GET['action']) || !isset($includes[$_GET['action']]) )
{
    $action = 'default';
}
else
{
    $action =& $_GET['action'];
}


//-----------------------------------------------
// Authenticate admin
//-----------------------------------------------
$logged_in = false;
if($action != 'login' && $action != 'logout')
{
    session_start();
    $logged_in = isset($_SESSION['uploader_admin']);
    if(!$logged_in)
    {
        $action = 'login';
    }
}

//-----------------------------------------------
// Load action file.
//-----------------------------------------------
require( 'includes/' . $includes[$action]);

//-----------------------------------------------
// Included files made changes to the template
// Now print everything
//-----------------------------------------------
if(!$logged_in)
{
    $Template->assign('noheader', 1);
}

$Template->assign('runtime', timer($start) );
$Template->display('admin_index.tpl');
?>
