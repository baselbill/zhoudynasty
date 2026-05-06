<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: uploader.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : on form submit, loop through $_FILES
 *                and move it into the incoming_dir.
                  otherwise display the upload form.
\************************************************/

//-----------------------------------------------
// Page has to be included.
//-----------------------------------------------
if(!defined('UPLOADER'))
{
    exit('hi2u');
}

//-----------------------------------------------
// Get action from the form
//-----------------------------------------------
$act = @key($_POST['action']);
if($act == 'doupload')
{
    $uploaded = array();
    $not_uploaded = array();
    $count = 0;
    $destination_name = htmlspecialchars(stripslashes_gpc(trim($_POST['destination'])));
    $destination = $Settings['incoming_directories'][$destination_name]['path'];
    $url_path = $Settings['incoming_directories'][$destination_name]['url'];
    foreach($_FILES as $file)
    {
        $error = 0;
        $file['name'] = clean($file['name']);
        if ( !isset ($file['error']) )
        {
            $error = is_uploaded_file ($file['name']);
        }
        else
        {
            $error = ($file['error'] != 0);
        }

        // check for empty file
        if(!$error && $file['size'] === 0)
        {
            $error = 1;
            $not_uploaded[] = $file['name'] . ' is empty or invalid.';
        }
        // check for non-images, if image_only was set
        if(!$error && $Settings['image_only'] && !stristr($file['type'], 'image') )
        {
            $error = 1;
            $not_uploaded[] = $file['name'] . ' is not an image.';
        }
        // check if file is php
        if(!$error && !$Settings['allow_php'] && (extension($file['name']) == '.php') )
        {
            $error = 1;
            $not_uploaded[] = $file['name'] . ', php files are not allowed.';
        }
        // check for the size of the file
        if(!$error && $Settings['max_file_size'] && (($file['size']/ 1000) > $Settings['max_file_size']))
        {
            $error = 1;
            $not_uploaded[] = $file['name'] . ", file size(". $file['size']/ 1000 . 'KB) is greater than ' . $Settings['max_file_size'] . 'KB.';
        }
        // check or existing file
        if(!$error && !$Settings['allow_override'] && file_exists($destination . $file['name']))
        {
            $error = 1;
            $not_uploaded[] = $file['name'] . ', this file already exists in the destination folder.';
        }
        // check for bad characters in the file name
        if(!$error && preg_match("#\\|\/|\:|\*|\?|\&|\<|\>|\|#i", $file['name']))
        {
            $error = 1;
            $not_uploaded[] = $file['name'] . ', file has non alphanumeric character(s).';
        }
        // check for file type
        if(!$error && ($Settings['allowed_types'] != 'all') && !preg_match("#{$Settings['allowed_types']}$#i", $file['name']))
        {
            $error = true;
            $not_uploaded[] = $file['name'] . ', file type not permitted.';
        }
        // logging
        if(!$error && ($Settings['logging']))
        {
            // open old logs
            if(!file_exists($log_file) )
            {
                if(!@touch($log_file))
                {
                    fatal_error('Log error not found, attempt to create file failed.');
                }
            }

            $old = load_file($log_file);
            
            if(!isset($current_user))
            {
                $current_user = 'Unregistered User';
            }
            $log['user'] = $current_user;
            $log['ip'] = $_SERVER['REMOTE_ADDR'] .'(' . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ')';
            $log['file'] = $file['name'];
            $log['dest'] = $destination_name;
            $log['time'] =  date('m/d/y h:iA');
            $old[] = $log;
            write_file($log_file, $old);
        }

        if(!$error)
        {
            if(!@move_uploaded_file($file['tmp_name'], $destination . $file['name']) )
            {
                $not_uploaded[] = 'Could not move "' . $file['name'] . '" to the incoming directory.';
            }
            else
            {
                $uploaded[$count]['name'] = $file['name'];
                $uploaded[$count]['url'] = $url_path . $file['name'];
                $count++;
            }
        }
    }// foreach
    
    if(!$count && !count($not_uploaded))
    {
        show_message('No file to upload', 'You did not select any file to upload.', 0);
        redirect('', 2);
    }
    else
    {
        $Template->assign_by_ref('uploaded', $uploaded);
        $Template->assign_by_ref('not_uploaded', $not_uploaded);
        $Template->assign_by_ref('uploaded_count', $count);
        $Template->assign('not_uploaded_count', count($not_uploaded));
        $Template->assign('img_tags', isset($_POST['img_tags']));
        $Template->assign('img_preview', isset($_POST['img_preview']));
        $Template->assign('img_urls', isset($_POST['img_urls']));
        $Template->assign('action', 'uploaded');
    }


}
//-----------------------------------------------
// No action from the form, display upload form.
//-----------------------------------------------
else
{
    $upload_fields = array();
    $number_of_fields = isset( $_POST['number_of_fields'] ) && intval($_POST['number_of_fields']) > 0 ? intval($_POST['number_of_fields']) : $Settings['default_upload_fields'];
    if($number_of_fields > $Settings['max_upload_fields'])
    {
        $number_of_fields = $Settings['default_upload_fields'];
    }
    for($i = 1; $i <= $number_of_fields; $i++)
    {
        $upload_fields[] = $i;
    }
    $allowed_types = implode(' ', explode('|', $Settings['allowed_types']));
    $Template->assign('show_rules', $Settings['show_rules']);
    $Template->assign_by_ref('upload_fields', $upload_fields);
    $Template->assign_by_ref('number_of_fields', $number_of_fields);
    $Template->assign_by_ref('max_file_size', $Settings['max_file_size']);
    $Template->assign_by_ref('image_only', $Settings['image_only']);
    $Template->assign_by_ref('allowed_types', $allowed_types);
    $Template->assign_by_ref('comments', $Settings['comments']);
    $Template->assign_by_ref('show_comments', $Settings['show_comments']);
    $Template->assign('url_path', $Settings['url_path']);
    $incoming = array();
    foreach($Settings['incoming_directories'] as $name => $path)
    {
        $incoming[] = $name;
    }
    $Template->assign('incoming_directories', $incoming );
    $Template->assign('action', 'form');
}

?>
