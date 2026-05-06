<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: functions.inc.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : functions
\************************************************/
function stripslashes_gpc($str)
{
    return get_magic_quotes_gpc() ? stripslashes($str) : $str;
}
function clean($str)
{
    return nl2br(htmlspecialchars( stripslashes_gpc ($str) ) );
}

function endslash($str)
{
    return rtrim($str, '/') . '/';
}

function extension($file)
{
    $pos = strrpos($file,'.');
    if(!$pos)
        return 'Unknown';
    $str = substr($file, $pos, strlen($file));
    return $str;
}

function timer($start = 0, $digits = 8)
{
    list($m, $s) = explode(' ', microtime());
    return round((double)$s + (double)$m - $start, $digits);
}

function show_message($title, $message, $noheader = 1)
{
    global $Template;
    $Template->assign('noheader', $noheader);
    $Template->assign_by_ref('title', $title);
    $Template->assign_by_ref('message', $message);
    $Template->assign('action', 'show_message');
}

function fatal_error($message)
{
    global $Template;
    $Template->assign('action', 'show_message');
    $Template->assign('title', 'Fatal error');
    $Template->assign('noheader', 1);
    $Template->assign('message', $message);
    $Template->display('index.tpl');
    exit();
}

function redirect($url = '', $time = 2, $message = '')
{
    global $Template;
    if(empty($url))
    {
        $url = isset($_SERVER['HTTP_REFERER']) ? stripslashes_gpc($_SERVER['HTTP_REFERER']) : 'index.php';
    }
    if(empty($message))
    {
        $message = 'You will now be redirected to the previous page.';
    }
    $meta = '<meta http-equiv="Refresh" content="'. $time . '; URL=' . $url . '">';
    $Template->assign_append('message', ' ' . $message .' <a href="' . $url . '" title="Go now">Click
    here if you are not redirected in ' . $time . ' second' . ( $time >1 ? 's.' : '.'));
    $Template->assign_by_ref('meta', $meta);
}

function authenticate($username, $password)
{
    global $users_file;
    // load users
    include('includes/users.class.php');
    $Users = new Users($users_file);
    $u = $Users->get_user_info_by_name($username);
    if( is_array($u) && $u['password'] == $password)
    {
        return $u;
    }
    return false;
}

function authenticate_by_id($userid, $password)
{
    global $users_file;
    // load users
    include('includes/users.class.php');
    $Users = new Users($users_file);
    $u = $Users->get_user_info_by_id($userid);
    if( is_array($u) && $u['password'] == $password)
    {
        return $u;
    }
    return false;
}

function rand_pass($howmany = 8, $int = true)
{
    $l = 'abcdefghijklmnopqrstuvwxyz';
    $l .= strtoupper($l);
    if($int) $l .= '0123456789';
    $r = '';
    for($i = 0; $i < $howmany; $i++)
    {
        $r .= $l[rand(0, strlen($l)-1)];
    }
    return $r;
}

function &load_file($file)
{
    if(!file_exists($file))
    {
        fatal_error("Could not open '$file' for reading.");
    }
    if( function_exists('file_get_contents') )
    {
        return unserialize( file_get_contents($file) );
    }
    else
    {
        $fp = fopen($file, 'r');
        flock ($fp, 1);
        $data = '';
        while ( !feof ($fp) )
        {
            $data .= fread ($fp, 1024);
        }
        return unserialize( $data );
    }
}

function write_file($file, $data)
{
    $fp = @fopen($file, 'w');
    if(!$fp)
    {
        fatal_error("Could not open '$file' for writting.");
    }
    flock($fp, 2);
    fwrite($fp, serialize($data));
    fclose($fp);
}


function dir_stats($dir)
{
    $size = 0;
    $dirs = 0;
    $files = 0;

    $dir = endslash($dir);

    $h = @opendir($dir);
    if(!$h)
    {
        return array('size' => $size, 'dirs' => $dirs, 'files' => $files);
    }
    while( false != ($f = @readdir($h)))
    {
        if($f != '.' && $f != '..')
        {
            if(is_dir($dir.$f))
            {
                $dirs++;
                $dirs_in = dir_stats( $dir . $f );
                $dirs += $dirs_in['dirs'];
            }
            else
            {
                $size += filesize($dir.$f);
                $files++;
                if(isset($dirs_in))
                {
                    $size += $dirs_in['size'];
                    $files += $dirs_in['files'];
                }
            }
        }
    }
    return array('size' => $size, 'dirs' => $dirs, 'files' => $files);
}

function multisort($array, $column, $order = 'asc', $type = SORT_STRING)
{
    if(!isset($array[0][$column]))
    {
        return $array;
    }
    for($i = 0; $i < count($array); $i++)
    {
        $temp[$i] = $array[$i][$column];
    }
    switch($order)
    {
        default:
        case 'asc':
            $order = SORT_ASC;
        break;
        case 'dsc':
        case 'desc':
            $order = SORT_DESC;
        break;
    }
    array_multisort($temp, $order, $type , $array);

    return $array;
}
?>
