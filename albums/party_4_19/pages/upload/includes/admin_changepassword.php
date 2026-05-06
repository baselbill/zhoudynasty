<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: admin_changepassword.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : Change admin password
\************************************************/
//-----------------------------------------------
// Page has to be included.
//-----------------------------------------------
if(!defined('UPLOADER'))
{
    exit('hi2u');
}

if(isset($_POST['password1']) && isset($_POST['password2']) && !empty($_POST['password1']) )
{
    $password1 = md5($_POST['password1']);
    $password2 = md5($_POST['password2']);
    if( $password1 != $password2 )
    {
        show_message('Passwords do not match', 'The new passwords you entered do not match.', 0);
        redirect('', 1);
    }
    else
    {
        $Settings['admin_password'] = $password1;
        write_file($settings_file, $Settings);
        show_message('Password changed', 'Your password has been changed.', 0);
        redirect('admin.php?action=logout', 1, 'You will now be logged out so that you can login again with your new password.');
    }
}
else
{
    $Template->assign('action', 'changepassword');
}
?>
