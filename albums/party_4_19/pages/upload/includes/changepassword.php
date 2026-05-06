<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: changepassword.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : Change user password
\************************************************/
if(!isset($logged_in) || !$logged_in)
{
    show_message('You are not logged in.', 'You are not currently loggged in.  You cannot
    change your password.');
    redirect('index.php', 1);
    return;
}


if( isset($_POST['current_password']) && !empty($_POST['current_password']) )
{
    include_once('includes/users.class.php');
    $Users = new users($users_file);
    $user = $Users->get_user_info_by_id($_COOKIE['uploader_userid']);

    if(md5($_POST['current_password']) != $user['password'])
    {
        show_message('Incorrect password', 'The current password
        you entered is incorrect.  Please try again.', 0);
        redirect('index.php?action=changepassword', 3);
    }
    elseif(empty($_POST['password1']))
    {
        show_message('Empty password', 'You cannot have an empty password. ', 0);
        redirect('',3);
    }
    elseif($_POST['password1'] != $_POST['password2'])
    {
        show_message('Error, passwords do not match.', '
        The passwords you entered do not match.
        Please enter the same password in both fields.', 0);
        redirect('index.php?action=changepassword', 3);
    }
    else
    {
        show_message('Password changed!', '
        Your password has been changed. You will now be logged out so
        that you can login again with the new password.', 0);
        redirect('index.php?action=logout',1);
        $Users->change_password($_COOKIE['uploader_userid'], md5($_POST['password1']));
        $Users->save();
    }
}
else
{
    $Template->assign('action', 'changepassword');
}
?>
