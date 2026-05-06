<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: admin_manageusers.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : Mange users accounts.
\************************************************/
//-----------------------------------------------
// Page has to be included.
//-----------------------------------------------
if(!defined('UPLOADER'))
{
    exit('hi2u');
}

// since there will be many different forms submitting to this same script, decide which action it is
// based on the submit form button.
$action = @key($_POST['action']);

if($action)
{
    include('includes/users.class.php');
    $Users = new Users($users_file);
    
    if($action == 'approve')
    {
        if(!isset($_POST['userid']))
        {
            show_message('No user selected', 'You did not select any user to approve', 0);
            redirect('',2);
        }
        else
        {
            $userid =& $_POST['userid'];
            for($i = 0; $i < count($userid); $i++)
            {
                $user = $Users->get_user_info_by_id($userid[$i]);
                $emailmessage = 'Hello ' . $user['username'] . ",\n\n" . 'You registered for an account at ' . $Settings['uploader_url'] . "\n";
                $emailmessage .= 'Your account has been approved by the administrator.' . "\n";
                $emailmessage .= "\n\nThanks!!!\nThe nice people at " . $Settings['uploader_url'];
                mail($user['email'], 'Account Approved', $emailmessage, "From: {$Settings['admin_email']}");
                $Users->approve_account($userid[$i]);
            }
            $Users->save();
            show_message('Done', 'Saved!', 0);
            redirect('', 1);
        }
    }
    elseif($action == 'account')
    {
        if(!isset($_POST['userid']))
        {
            show_message('No user selected', 'You did not select any user.', 0);
            redirect('',2);
        }
        else
        {
            $userid =& $_POST['userid'];
            foreach($userid as $uid => $closed)
            {
                if($closed)
                {
                    $Users->close_account($uid);
                }
                else
                {
                    $Users->open_account($uid);
                }
            }
            $Users->save();
            show_message('Done', 'Saved!', 0);
            redirect('', 1);
        }
    }
    elseif($action == 'delete')
    {
        if(!isset($_POST['userid']))
        {
            show_message('No user selected', 'You did not select any user to delete', 0);
            redirect('',2);
        }
        else
        {
            $userid =& $_POST['userid'];
            for($i = 0; $i < count($userid); $i++)
            {
                $Users->delete_account($userid[$i]);
            }
            $Users->save();
            show_message('Done', 'Saved!', 0);
            redirect('', 1);
        }
    }
}
else
{
    // not submission.  Show the the actions of each type.
    $what = isset($_GET['what']) ? $_GET['what'] : 'nothing';
    if($what != 'nothing')
    {
        include('includes/users.class.php');
        $Users = new Users($users_file);
    }
    switch($what)
    {
        case 'view':
            $all = $Users->get_all_users();
            if(!$all)
            {
                show_message('No users', 'You have no registered users.<p><a href="admin.php?action=users">Back to users option</a></p>', 0);
            }
            else
            {
                $sortby = isset($_GET['sortby']) ? trim($_GET['sortby']) : 'userid';
                if($sortby == 'userid') $sorttype = SORT_NUMERIC; else $sorttype = SORT_STRING;
                multisort($all, $sortby,'asc', $sorttype);
                $Template->assign_by_ref('users', $all);
                $Template->assign('action', 'list_users');
            }
        break;
        case 'account':
            $all = $Users->get_all_users();
            if(!$all)
            {
                show_message('No users', 'You have no new users.<p><a href="admin.php?action=users">Back to users option</a></p>', 0);
            }
            else
            {
                $sortby = isset($_GET['sortby']) ? trim($_GET['sortby']) : 'userid';
                if($sortby == 'userid') $sorttype = SORT_NUMERIC; else $sorttype = SORT_STRING;
                multisort($all, $sortby,'asc', $sorttype);
                $Template->assign_by_ref('users', $all);
                $Template->assign('action', 'account');
            }
        break;
        case 'approve':
            $all = $Users->get_non_approved_list();
            if(!$all)
            {
                show_message('No users', 'You have no new users.<p><a href="admin.php?action=users">Back to users option</a></p>', 0);
            }
            else
            {
                $sortby = isset($_GET['sortby']) ? trim($_GET['sortby']) : 'userid';
                if($sortby == 'userid') $sorttype = SORT_NUMERIC; else $sorttype = SORT_STRING;
                multisort($all, $sortby,'asc', $sorttype);
                $Template->assign_by_ref('users', $all);
                $Template->assign('action', 'approve');
            }
        break;
        case 'delete':
            $all = $Users->get_all_users();
            if(!$all)
            {
                show_message('No users', 'You have no registered users.<p><a href="admin.php?action=users">Back to users option</a></p>', 0);
            }
            else
            {
                $sortby = isset($_GET['sortby']) ? trim($_GET['sortby']) : 'userid';
                if($sortby == 'userid') $sorttype = SORT_NUMERIC; else $sorttype = SORT_STRING;
                multisort($all, $sortby,'asc', $sorttype);
                $Template->assign_by_ref('users', $all);
                $Template->assign('action', 'delete_users');
            }
        break;
        default:
        // nothing, show menus
        $Template->assign('action', 'show_users_menu');
    }
}

?>
