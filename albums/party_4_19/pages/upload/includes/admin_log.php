<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: admin_log.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : clear all log content
\************************************************/
if(!isset($_GET['clear_log']))
{
    $logs =& load_file($log_file );
    $Template->assign_by_ref('logs', $logs);
    $Template->assign('action', 'log');
}
else
{
    write_file($log_file, array());
    show_message('Log file cleared', 'The log file has been cleared.');
    redirect('admin.php?action=stats',1);
}
?>
