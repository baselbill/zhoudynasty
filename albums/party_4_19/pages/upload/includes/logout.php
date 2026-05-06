<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: logout.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : clear cookies and session vars
\************************************************/

//-----------------------------------------------
// Page has to be included.
//-----------------------------------------------

if(!defined('UPLOADER'))
{
    exit('hi2u');
}

// clear cookies
setcookie('uploader_userid', '', time()-3600, '/');
setcookie('uploader_password', '', time()-3600, '/');
// clear sesssions, twice!
session_start();
session_unregister('uploader_username');
session_unregister('uploader_userid');
unset($_SESSION['uploader_username']);
unset($_SESSION['uploader_userid']);
show_message('Logged out', 'All cookies and sessions have been cleared.');
redirect('index.php', 1, 'You will be taken to the main page.');
?>
