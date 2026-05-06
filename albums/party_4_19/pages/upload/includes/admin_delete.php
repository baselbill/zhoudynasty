<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: admin_delete.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : Delete files in the incoming directory.
 *                Submit checked files to index.php?action=viewfiles
*                through POST.
\************************************************/
//-----------------------------------------------
// Page has to be included.
//-----------------------------------------------
if(!defined('UPLOADER'))
{
    exit('hi2u');
}

if(!isset($_POST['action']) || !isset($_POST['selected']) )
{
    $referer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'admin.php';

    if(!isset($_GET['in']))
    {
        $cur = current($Settings['incoming_directories']);
        $root = $cur['path'];
        $url_path = $cur['url'];
        $in = key($Settings['incoming_directories']);
    }
    else
    {
        $in = htmlspecialchars( stripslashes_gpc( trim($_GET['in']) ) );
        $cur = $Settings['incoming_directories'][$in];
        $url_path = $cur['url'];
        $root = $cur['path'];
    }

    $current_dir = @$_GET['dir'] ? endslash($_GET['dir']) : '';

    

    if(stristr($current_dir, '../'))
    {
        show_message('Access denied', 'You cannot view higher than the root directory.');
        redirect('',1);
        return;
    }
    elseif(!is_dir($root))
    {
        show_message('Invalid directory', 'Unable to open ' . $root . $current_dir . ' for reading.
        Please enter the "settings" section enter the correct path.', 0);
        redirect('',3);
        return;
    }
    elseif(!is_dir($root . $current_dir))
    {
        show_message('Invalid directory', 'Could not open ' . $current_dir . '.');
        redirect('', 2, 'You will be redirected to the previous page.');
        return;
    }
    else
    {
        $current_url_path = $url_path . $current_dir;
        // load  list
        $filelist = list_files($root, $current_dir, $url_path, $in);
        //$filelist = $this->list_files($Settings['incoming_dir'] . $current_dir);
        clearstatcache();

        // take care of sorting
        $sortby = isset($_GET['sortby']) ? $_GET['sortby'] : false;
        $order = isset($_GET['order']) ? $_GET['order'] : 'dsc';
        if($sortby)
        {
            if($sortby == 'size')
            {
                $filelist = multisort($filelist, $sortby, $order, SORT_NUMERIC);
            }
            else
            {
                $filelist = multisort($filelist, $sortby, $order);
            }
        }
        $files = 0;
        $total_size = 0;
        $end = count($filelist);
        for($i = 0; $i < $end; $i++)
        {
            $total_size += $filelist[$i]['size'];
            if($filelist[$i]['type'] != 'dir')
            {
                $files++;
                $filelist[$i]['size'] = ($filelist[$i]['size'] > 1000) ? (number_format($filelist[$i]['size'] / 1000, 1)): $filelist[$i]['size'] / 1000;
                $filelist[$i]['size'] .= 'KB';
            }
        }
        foreach($Settings['incoming_directories'] as $name => $path)
        {
            $incoming_directories[] = $name;
        }
        $Template->assign('incoming_directories', $incoming_directories);
        $Template->assign('browse_in', $in);
        $Template->assign('current_dir', rtrim($current_dir, '/'));
        $Template->assign('sortby', $sortby );
        $Template->assign('order', $order );
        $Template->assign_by_ref('files', $filelist );
        $Template->assign('total_files', $files );
        $total_size = $total_size > 1000 ? ($total_size / 1000) . 'MB' : $total_size . 'KB';
        $Template->assign('total_size', $total_size);
        $Template->assign('action', 'delete' );
    }
}
else
{
    $selected =& $_POST['selected'];
    $dir = endslash($_POST['dir']);
    $in = htmlspecialchars( stripslashes_gpc( trim($_POST['browse_in']) ) );
    $cur = $Settings['incoming_directories'][$in];
    $root = $cur['path'];
    
    $deleted = array();
    for($i = 0; $i < count($selected); $i++)
    {
        if( unlink($root . $dir . $selected[$i]) )
        {
            $deleted[] = $selected[$i];
        }
    }

    show_message('Files deleted', 'The follow files have been deleted: <br /><br />' . implode('<br />', $deleted) . '<br /><br />' , 0);
    redirect('', 30);
}



function list_files($root, $path, $url, $in)
{
    $root = rtrim($root, '/') . '/';
    $path = rtrim($path, '/') . '/';
    if($path == '/') { $path = '';}
    $url = rtrim($url, '/') . '/';
    $dir = $root . $path;
    $h = @opendir($dir);
    if(!$h)
    {
        return array();
    }
    $files = array();
    while(false != ($f = readdir($h)))
    {
        if($f != '..' && $f != '.')
        {
            if(!is_dir($root.$path.$f))
            {
                array_push($files, array('name' => $f, 'url' => $url . $path . $f, 'size' => filesize($root.$path.$f), 'time' => date('m/d/y h:iA',filemtime($root.$path.$f)), 'type' => extension($f) ) );
            }
            else
            {
                array_unshift($files, array('name' => $f, 'url' => 'admin.php?action=delete&amp;in=' . $in . '&amp;dir=' . (urlencode($path . $f)), 'size' => '-', 'time' => '-', 'type' => 'dir'));
            }
        }
    }
    if($path != '')
    {
        array_unshift($files, array('name' => 'Up one dir', 'url' => 'admin.php?action=delete&amp;in=' . $in . '&amp;dir=' . urlencode(dirname($path)=='.' ? '' : dirname($path)), 'size' => '-', 'time' => '-', 'type' => 'dir'));
    }
    return $files;
}
   
?>
