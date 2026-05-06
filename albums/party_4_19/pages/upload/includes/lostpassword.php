<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: lostpassword.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : Send email with new password to user
\************************************************/

//-----------------------------------------------
// Page has to be included.
//-----------------------------------------------
if(!defined('UPLOADER'))
{
    exit('hi2u');
}

if( isset($_POST['email']) && !empty($_POST['email']) )
{
    $email = trim($_POST['email']);
    
    if(!preg_match("#[a-z0-9_]+?\@.+?\..+#i", $email))
    {
        show_message('Invalid email address', 'The email address you entered appears to be
        invalid.  Please enter a valid email address.',1);
        redirect('',2);
    }
    else
    {
        include('includes/users.class.php');
        $users = new users($users_file);
        $user = $users->get_user_info_by_email($email);
        if($user)
        {
            // found user
            if($user['closed'])
            {
                show_message('Account closed', 'Your account has been closed. If you think this is
                an error, please email the <a href="mailto:' . $Settings['admin_email'] . '">administrator</a>.',1);
            }
            elseif(!$user['approved'])
            {
                show_message('Account not approved', 'Your account has not been approved
                by the administrator. You cannot get a new password at this time. If you think this is
                an error, please email the <a href="mailto:' . $Settings['admin_email'] . '">administrator</a>.', 1);
            }
            else
            {
                //
                $newpass = rand_pass(12,1);
                $users->change_password($user['userid'], md5($newpass) );
                $users->save();
                unset($users);
                $emailmessage = 'Hello ' . $user['username'] . ",\n\n";
                $emailmessage .= 'Here is your new password: ' . $newpass . "\n";
                $emailmessage .= "\n\nThanks!!!\nThe nice people at " . $Settings['uploader_url'];
                mail($user['email'], 'Your new password', $emailmessage, "From: {$Settings['admin_email']}");
                show_message('Password sent', 'A new password has been sent to ' . $email . '.',1);
                redirect('index.php?action=login', 3, 'You will now be taken to the login page.');
            }
        }
        else
        {
            show_message('User not found', 'There is no user with that email address.
            Please <a href="index.php?action=register" title="Register">register</a> or
            <a href="javascript:history.go(-1)" title="Register">try again.</a>', 1);
        }
    }
}
else
{
    $Template->assign('noheader', 1);
    $Template->assign('action', 'lostpassword');
}

?>
