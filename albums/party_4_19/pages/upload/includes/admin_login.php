<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: admin_login.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : set session variable uploader_admin
\************************************************/
if(isset($_POST['password']))
{
    $password = md5($_POST['password']);
    
    if($password == $Settings['admin_password'])
    {
        session_start();
        $_SESSION['uploader_admin'] = 1;
        show_message('Login successful', 'You are now logged in.');
        redirect('admin.php', 0, 'You will now be taken to the admin section.');
    }
    else
    {
        show_message('Incorrect password', 'Please enter the correct password.');
        redirect('admin.php?action=login', 1);
    }
}
else
{
    $Template->assign('action', 'login');
}


?>
