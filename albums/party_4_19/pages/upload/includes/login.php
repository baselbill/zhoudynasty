<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: login.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : Log user in, set session and cookie
\************************************************/

//-----------------------------------------------
// Page has to be included.
//-----------------------------------------------

if(!defined('UPLOADER'))
{
    exit('hi2u');
}

if(isset($_POST['username']) && !empty($_POST['username']))
{
    // authenticate
    $username = clean( $_POST['username']);
    $password = md5( $_POST['password'] );
    $user = authenticate($username, $password);
    if($user)
    {
        if($user['closed'])
        {
            show_message('Account closed', 'Your account has been closed by the administrator. If you think
            this is an error, please <a href="mailto:' . $Settings['admin_email'] . '">email</a> the administrator.', 1);
        }
        elseif(!$user['approved'])
        {
            show_message('Account not approved', 'Your account has not been approved.  You cannot log in at the
            time being.', 1);
            redirect('', 5);
        }
        else
        {
            // setcookie
            setcookie('uploader_userid', $user['userid'], time()+3600*24*60, '/');
            setcookie('uploader_password', $user['password'], time()+3600*24*60, '/');
            // set session
            session_start();
            $_SESSION['uploader_username'] = $user['username'];
            $_SESSION['uploader_userid'] = $user['userid'];
            $Template->assign('logged_in', 1);
            $Template->assign('username', $user['username']);
            show_message('Login successful', 'Hello ' . $user['username'] . ', you are now logged in.');
            redirect('index.php', 1, 'You will be taken to the main page.');
        }

    }
    else
    {
        show_message('Login failed', 'Incorrect username or password.  Please try again.', 1);
        redirect();
    }
}
else
{
    // show the form
    $Template->assign('action', 'login');
}
        

?>
