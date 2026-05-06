<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: viewfiles.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : Show images submitted through POST
 *
\************************************************/
//-----------------------------------------------
// Page has to be included.
//-----------------------------------------------
if(!defined('UPLOADER'))
{
    exit('hi2u');
}
if(!isset($_POST['selected']))
{
    show_message('No selection', 'You did not select any file to view.', 0);
    redirect('', 2);
}
else
{
    $files = array();
    $dir = endslash(clean($_POST['dir']));
    if($dir == '/') $dir = '';
    $in = htmlspecialchars( stripslashes_gpc( trim( $_POST['browse_in']) ) );
    $cur = $Settings['incoming_directories'][$in];
    $url_path = $cur['url'];
    
    $selected =& $_POST['selected'];
    for($i = 0; $i < count($selected); $i++)
    {
        $files[$i]['name'] = $selected[$i];
        $files[$i]['url'] = $url_path . $dir .  $selected[$i];
    }
    $Template->assign('action', 'viewfiles');
    $Template->assign_by_ref('files', $files);
}
?>
