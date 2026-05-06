<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: register.php
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

//-----------------------------------------------
// Trying to register again?
//-----------------------------------------------
if($logged_in)
{
    show_message('Already registered', 'You are already registered.');
    redirect('index.php', 1);
    return;
}

//-----------------------------------------------
// Is registration closed?
//-----------------------------------------------
if(!$Settings['allow_register'])
{
    show_message('Registration closed', 'Registration has been closed by the administrator.');
    redirect('index.php', 2);
    return;
}

if( isset($_POST['username']) )
{
    // register, do some checking first
    $username       = $_POST['username'];
    $password1      = $_POST['password1'];
    $password2      = $_POST['password2'];
    $email          = $_POST['email'];
    $info           = clean($_POST['info']);
    // start checking
    $error = array();
    $flag = 0;
    if(empty($username))
    {
        $error[] = 'Your username cannot be blank.';
        $flag = 1;
    }
    elseif(strlen($username) > 30)
    {
        $error[] = 'Your username is longer than 30 characters.';
        $flag = 1;
    }
    elseif(strlen($username) < 3)
    {
        $error[] = 'Your username is shorter than 3 characters.';
        $flag = 1;
    }
    if(empty($password1))
    {
        $error[] = 'You must have a password.';
        $flag = 1;
    }
    elseif($password1 != $password2)
    {
        $error[] = 'Your passwords do not match.';
    }
    if(empty($email))
    {
        $error[] = 'You must have an email.';
        $flag = 1;
    }
    elseif(!preg_match("#[a-z0-9_]+?\@.+?\..+#i", $email))
    {
        $error[] = 'Your email appears to be invalid. (Ex: someone@domain.com)';
        $flag = 1;
    }
    // open users db
    include('includes/users.class.php');
    $Users = new Users($users_file);

    if($Users->user_exists($username))
    {
        $flag = 1;
        $error[] = 'The name you entered has been taken.  Please choose another.';
    }
    elseif($Users->email_exists($email))
    {
        $flag = 1;
        $error[] = 'We already have a user with the email you entered.  Did you forget your password?';
    }
    
    if(!$flag)
    {
        $username = clean($username);
        $password = md5($password1);
        $approved = $Settings['auto_approve'];
        $closed = 0;
        // do register
        $Users->register($username, $password, $email, $info, $closed, $approved);
        $Users->save();
        if(!$approved)
        {
            show_message('Registration successlful', 'Hello ' . $username . ', you are
            now registered.  However your account will have to be approved by the
            administrator before you can use this service.  An email will be sent to
            you when your account has been approved.', 1);
        }
        else
        {
            show_message('Registration successlful', 'Hello ' . $username . ', you are
            now registered.  <a href="index.php?action=login">Click here to login.</a>', 1);
            redirect('index.php?action=login', 3);
        }
    }
    else
    {
        $error[] = '<br />Click the <a href="javascript:history.go(-1);">Back</a> button on your browser and try again.';
        show_message('Registration error', implode('<br />', $error) );
    }

    
}
else
{
    // show the form
    $Template->assign('noheader', 1);
    $Template->assign('action', 'register');
}
        

?>
