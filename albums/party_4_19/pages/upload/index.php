<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: index.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : load actions
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
                    'default'               => 'uploader.php',
                    'login'                 => 'login.php',
                    'logout'                => 'logout.php',
                    'register'              => 'register.php',
                    'lostpassword'          => 'lostpassword.php',
                    'browse'                => 'browse.php',
                    'viewfiles'             => 'viewfiles.php',
                    'changepassword'        => 'changepassword.php',
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
// Authenticate user
//----------------------------------------------- 
$logged_in = 0;
if($Settings['password_required'] && $action != 'register' && $action != 'lostpassword' && $action != 'login' && $action != 'lostpassword')
{
    session_start();
    if(!isset($_SESSION['uploader_username']))
    {
        if(isset($_COOKIE['uploader_userid']) && isset($_COOKIE['uploader_password']))
        {
            $user = authenticate_by_id($_COOKIE['uploader_userid'], $_COOKIE['uploader_password']);
            if(!$user)
            {
                $action = 'login';
            }
            else
            {
                $logged_in = 1;
                $current_user = $user['username'];
                $Template->assign('logged_in', 1);
            }
        }
        else
        {
            $action = 'login';
        }
    }
    else
    {
        $current_user = $_SESSION['uploader_username'];
        $logged_in = 1;
        $Template->assign('logged_in', 1);
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
if($Settings['password_required'] && !$logged_in)
{
    $Template->assign('noheader', 1);
}

$Template->assign('runtime', timer($start) );
$Template->display('index.tpl');
?>
