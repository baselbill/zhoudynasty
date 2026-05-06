<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: users.class.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : simple users class to add/delete/find users
\************************************************/

class Users
{
    var $_users = array();
    var $db_file = '';

    function Users($file)
    {
        // load user
        $fp = @fopen($file, 'r');
        if(!$fp)
        {
            $this->error('users(): could not open '. $file);
        }
        $this->db_file = $file;
        flock($fp, 1);
        $data = fread($fp, filesize($file));
        $this->_users = unserialize($data);
        if(!is_array($this->_users))
        {
            $this->_users = array();
        }
        fclose($fp);
        unset($data);
        clearstatcache();
    }
    
    function register($username, $password, $email, $extra = '', $closed = 0, $approved = 0)
    {
        if($this->user_exists($username))
        {
            $this->error('register(): user exists');
        }
        
        if(count($this->_users) < 1)
        {
            $userid = 1;
        }
        else
        {
            $last = end($this->_users);
            $userid = $last['userid'] + 1;
        }
        
        $user['username'] = $username;
        $user['password'] = $password;
        $user['email'] = strtolower($email);
        $user['extra'] = $extra;
        $user['closed'] = $closed;
        $user['userid'] = $userid;
        $user['approved'] = $approved;

        $this->_users[$userid] = $user;
    }

    
    function get_user_info_by_id($userid)
    {
        return isset($this->_users[$userid]) ? $this->_users[$userid] : false;
    }
    
    function get_user_info_by_name($username)
    {
        $username = strtolower($username);
        foreach($this->_users as $uid => $usr)
        {
            if(strtolower($usr['username']) == $username)
            {
                return $usr;
            }
        }
        return false;
    }

    function get_user_info_by_email($email)
    {
        $email = strtolower($email);
        foreach($this->_users as $uid => $usr)
        {
            if(strtolower($usr['email']) == $email)
            {
                return $usr;
            }
        }
        return false;
    }

    function change_password($userid, $newpassword)
    {
        if( isset($this->_users[$userid]))
        {
            $this->_users[$userid]['password'] = $newpassword;
            return true;
        }
        return false;
    }
    
    function delete_account($userid)
    {
        if( isset($this->_users[$userid]) )
        {
            unset($this->_users[$userid]);
            return true;
        }
        return false;
    }
    
    function user_exists($username)
    {
        return (bool)$this->get_user_info_by_name($username);
    }
    
    function email_exists($email)
    {
        return (bool)$this->get_user_info_by_email($email);
    }
    
    function close_account($userid)
    {
        if( isset($this->_users[$userid]) )
        {
            $this->_users[$userid]['closed'] = 1;
            return true;
        }
        return false;
    }

    function open_account($userid)
    {
        if( isset($this->_users[$userid]) )
        {
            $this->_users[$userid]['closed'] = 0;
            return true;
        }
        return false;
    }

    function approve_account($userid)
    {
        if( isset($this->_users[$userid]) )
        {
            $this->_users[$userid]['approved'] = 1;
            return true;
        }
        return false;
    }
    
    function get_non_approved_list()
    {
        $r = array();
        foreach($this->_users as $uid => $usr)
        {
            if(!$usr['approved'])
            {
                $r[] = $usr;
            }
        }
        return $r;
    }
    

    function get_closed_accounts_list()
    {
        $r = array();
        foreach($this->_users as $uid => $usr)
        {
            if($uid['closed'])
            {
                $r[] = $usr;
            }
        }
        return $r;
    }
    
    function user_count()
    {
        return count($this->_users);
    }
    
    function last_user()
    {
        return end($this->_users);
    }
    
    function get_all_users()
    {
        if(count($this->_users))
        {
            return array_values($this->_users);
        }
        return false;
    }
    
    function save()
    {
        $fp = fopen($this->db_file, 'w');
        flock($fp, 2);
        fwrite($fp, serialize($this->_users));
        fclose($fp);
    }
    
    function error($msg)
    {
        print '<hr>' . $msg . '<hr>';
        exit;
    }
    
}
?>
