<?php
/************************************************\
 * File Uploader
 * **********************************************
 * File Name	: admin_settings.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Purpose      : Change settings as well as hotlinking protection
\************************************************/
$what = isset($_GET['what']) ? $_GET['what'] : 'default';

if(isset($_POST['action']['save']))
{
    if($what == 'settings')
    {
        // unset $action
        unset($_POST['action']);
        // clean some stuff
        $_POST['comments'] = str_replace("\n", '<br />', $_POST['comments']);
        $_POST['comments'] = stripslashes_gpc($_POST['comments']);
        $_POST['uploader_url'] = endslash($_POST['uploader_url']);

        // loop through the new settings and replace
        foreach($_POST as $name => $value)
        {
            $Settings[$name] = $value;
        }
        
        $incoming = explode("\n", $_POST['incoming_directories']);
        $Settings['incoming_directories'] = array();
        for($i = 0; $i < count($incoming); $i++)
        {
            if(!empty($incoming[$i]))
            {
                list($name, $path, $url) = explode(',', $incoming[$i]);
                $name = htmlspecialchars(stripslashes_gpc(trim($name)));
                $Settings['incoming_directories'][$name]['path'] = endslash(trim($path));
                $Settings['incoming_directories'][$name]['url'] = endslash(trim($url));
            }
        }

        // save
        write_file($settings_file, $Settings);
        show_message('Settings saved', 'All settings have been saved.');
        redirect('', 1);
    }
    elseif($what == 'hotlinking')
    {
        if(isset($_POST['disable']))
        {
            while( list($name, $info)  = each($Settings['incoming_directories']))
            {
                @unlink($info['path'] . '.htaccess');
            }
            show_message('Hotlinking protection disabled', 'The .htaccess file(s) have been deleted.');
            redirect('',2);
        }
        else
        {
            $htaccess_code[] =  'DirectoryIndex index.php';
            $htaccess_code[] =  'RewriteEngine on';
            $htaccess_code[] =  'RewriteCond %{HTTP_REFERER} !^$';
            $allowed =& explode("\n", $_POST['allowed_urls']);
            for($i = 0; $i < count($allowed); $i++)
            {
                if(!empty($allowed[$i]))
                {
                    $htaccess_code[] = 'RewriteCond %{HTTP_REFERER} !^' . endslash(trim($allowed[$i])) . '.*$      [NC]';
                }
            }
            $htaccess_code[] =  'RewriteRule .*\.(' . $_POST['file_types'] . ')$ '. $_POST['redirect_url'] . ' [R,NC]';
            while( list($name, $info)  = each($Settings['incoming_directories']))
            {
                $fp = fopen($info['path'] . '.htaccess', 'w');
                fwrite($fp, implode("\r\n", $htaccess_code));
                fclose($fp);
            }

            show_message('Saved!', 'The settings has been saved to the .htaccess file.');
            redirect('',2);
        }
    }
    else
    {
        fatal_error('The form in the admin_settings.tpl must include "what" in the url.
        For example: <form action="admin.php?action=settings&amp;what=settings">');
    }
}
else
{

    if($what == 'settings')
    {
        $Settings['comments'] = str_replace('<br />', "\n", $Settings['comments']);
        foreach($Settings as $name => $value)
        {
            $Template->assign($name, $value);
        }
        $incoming_directories = array();

        foreach($Settings['incoming_directories'] as $name => $url_and_path)
        {
          
            $path = $url_and_path['path'];
            $url = $url_and_path['url'];
            $incoming_directories[] = "$name, $path, $url";
        }
        $Template->assign('incoming_directories', implode("\n", $incoming_directories));
        $Template->assign('action', 'settings');
    }
    elseif($what == 'hotlinking')
    {
        $exists = 1;
        while( list($name, $info)  = each($Settings['incoming_directories']))
        {
            if(!file_exists($info['path'] . '.htaccess'))
            {
                $exists = 0;
            }
        }
        if(!$exists)
        {
            $allowed_urls = implode("\n", array('# Erease this line, .htaccess not found, default settings loaded', 'http://' . $_SERVER['SERVER_NAME'], 'http://www.another_domain.com', $Settings['uploader_url']));
            $redirect_url = $Settings['uploader_url'] . 'nolinking_example.gif';
            $file_types = str_replace('.', '', $Settings['allowed_types']);
        }
        else
        {
            // preg match old file, read from one of the file
            reset($Settings['incoming_directories']);
            list($name, $info) = each($Settings['incoming_directories']);
            $lines = file($info['path'] . '.htaccess');
            $allowed_urls = array();
            $file_types = $Settings['allowed_types'];
            $redirect_url = $Settings['uploader_url'] . 'nolinking_example.gif';
            for($i = 0; $i < count($lines); $i++)
            {
                if(preg_match("#RewriteCond \%{HTTP_REFERER} \!\^(.+?)\.\*\\$#i", $lines[$i], $m))  {
                    $allowed_urls[] = $m[1];
                }
                if(preg_match("#RewriteRule \.\*\\\.\((.*?)\)\\$[\s]*(.+?)[\s]*\[R\,NC\]#i", $lines[$i], $m))
                {
                    $file_types = str_replace('.', '', $m[1]);
                    $redirect_url = $m[2];
                }
            }
            $allowed_urls = implode("\n", $allowed_urls);
            
        }
        $Template->assign_by_ref('file_types', $file_types);
        $Template->assign_by_ref('redirect_url', $redirect_url);
        $Template->assign_by_ref('allowed_urls', $allowed_urls);
        $Template->assign('action', 'hotlinking');
    }
    else
    {
        $menu = '<a href="admin.php?action=settings&amp;what=settings">Edit uploader settings</a><br /><br />';
        $menu .= '<a href="admin.php?action=settings&amp;what=hotlinking">Hot linking protection</a><br /><br />';
        show_message('What to do?', $menu, 0);
    }
}


?>
