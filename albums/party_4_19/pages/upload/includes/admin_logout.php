<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: admin_logout.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : clear all session variables
\************************************************/
session_start();
session_unregister('uploader_admin');
unset($_SESSION['uploader_admin']);
show_message('Logged out','You are now logged out');
redirect('admin.php',0);

?>
