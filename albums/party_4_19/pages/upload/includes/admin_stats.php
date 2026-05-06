<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: admin_stats.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : Stats and such
\************************************************/
include_once('includes/users.class.php');
$Users = new Users($users_file);
$Template->assign('users', $Users->user_count() );
$Template->assign('last_user', $Users->last_user() );
$total_files = array();
$total_size = array();
$total_dirs = array();
foreach($Settings['incoming_directories'] as $name => $url_and_path)
{
    $stats = dir_stats($url_and_path['path']);
    if($stats['size'] > 1000000)
    {
        $stats['size'] = number_format( $stats['size'] / 1000000 ) . 'MB';
    }
    else
    {
        $stats['size'] = number_format( $stats['size'] / 1000 ) . 'KB';
    }
    $total_files[] = $name . ': ' . $stats['files'] . ' file(s)';
    $total_size[] = $name . ': ' . $stats['size'];
    $total_dirs[] = $name . ': ' . $stats['dirs'];
}
$Template->assign('total_files', implode('<br />' , $total_files));
$Template->assign('total_size', implode('<br />' , $total_size));
$Template->assign('total_dirs', implode('<br />' , $total_dirs));
$Template->assign('action', 'stats');
?>
