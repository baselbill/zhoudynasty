<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file' );
}

//--------------------------------------------------
// get template for listing
//--------------------------------------------------
function get_list_template ( $template_file, $vars = array ( ) )
{
    global $sb_date_format;

    $fp = @fopen ( $template_file, 'rb' );

    if ( !$fp )
    {
        exit ( 'Unable to open the list template ' . $template_file );
    }

    $str = fread ( $fp, filesize ( $template_file ) );

    fclose ( $fp );

    if ( count ( $vars ) )
    {
        while ( list ( $v, $k ) = each ( $vars ) )
        {
            $str = str_replace ( '{' . $v . '}', $k, $str );
        }
    }
    $str = "print '" . str_replace ( "'", "\'", str_replace("\\", "\\\\", $str) );
    
    if ( preg_match ( '#(\{start shouts\})(.+?)(\{end shouts\})#mis', $str, $m ) )
    {
        // $this->index_data [] = array ( $shout_id, $name, $ip, $time, $shout_file )
        $m[1] = "';" . 'for ( $i = 0; $i < count ( $sb_shouts ); $i++ ) { ' . "print '";
        $s =& $m[2];
        $s = preg_replace ( '#\{rowcolor:(.+?)\|(.+?)\}#', "'.".'($i&1?\'$1\':\'$2\')'.".'",$s);
        $s = str_replace ( '{end shouts}', "';} print '", $s );
        $s = str_replace ( '{name}', "' . " . '$sb_shouts[$i][1]' . ".'", $s );
        $s = str_replace ( '{shout_id}', "' . " . '$sb_shouts[$i][0]' . ".'", $s );
        $s = str_replace ( '{time}', "' . " . 'date("'.$sb_date_format.'",$sb_shouts[$i][3])' . ".'", $s );
        $s = str_replace ( '{ip}', "' . " . '$sb_shouts[$i][2]' . ".'", $s );
        $s = str_replace ( '{delete_url}', "' . " . 'build_url ( $sb_url, array ( G_ACTION => "admin", G_TASK => "delete", G_SHOUTID => $sb_shouts[$i][0] ) )' . ".'", $s );
        $s = str_replace ( '{edit_url}', "' . " . 'build_url ( $sb_url, array ( G_ACTION => "admin", G_TASK => "edit", G_SHOUTID => $sb_shouts[$i][0] ) )' . ".'", $s );
        $s = str_replace ( '{view_url}', "' . " . 'build_url ( $sb_url, array ( G_ACTION => "admin", G_TASK => "view", G_SHOUTID => $sb_shouts[$i][0] ) )' . ".'", $s );

        $m[3] = "';} print '";
        
        $str = str_replace ( $m[0], $m[1] . $m[2] . $m[3], $str );
    }
    $str .= "';";

    return $str;
}

require_once ( $sb_root . 'shoutbox.class.php' );

$sb_obj = new shoutbox ( $sb_root . SHOUTS_DIR );

$sb_cur_page = isset ( $_GET2[G_PAGE] ) ? abs ( intval ( $_GET2[G_PAGE] ) ) : 1;

if ( $sb_perpage )
{
    $sb_total_pages = ceil ( $sb_obj->get_shouts_count ( ) / $sb_perpage );

    if ( $sb_cur_page > $sb_total_pages || $sb_cur_page < 1 )
    {
        $sb_cur_page = 1;
    }

    if ( $sb_cur_page === 1 )
    {
        $sb_shouts = $sb_obj->get_shouts_index ( -1, $sb_perpage );
    }
    else
    {
        $sb_end = $sb_obj->get_shouts_count ( ) - ( $sb_perpage * ( $sb_cur_page - 1 ) );

        $sb_start = ( $sb_cur_page == $sb_total_pages ) ? 0 : $sb_end - $sb_perpage;

        $sb_shouts = $sb_obj->get_shouts_index ( $sb_start, $sb_end );
    }
}
else
{
    $sb_total_pages = 1;

    $sb_shouts = $sb_obj->index_data;
    
    $sb_cur_page = 1;
    
    $sb_total_pages = 1;
}

if ( $sb_shouts_order )
{
    $sb_shouts = array_reverse ( $sb_shouts );
}

if ( !count ( $sb_shouts ) )
{
    // Dummy
    $sb_shouts[0] = array ( 0, 'Shoutbox Message', '', '', 'No shouts to delete.', 0, 0, time ( ) );

    $sb_cur_page = 0;

    $sb_total_pages = 0;
}

$sb_tpl_info = array
(
    'form_action'       => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'delete' ) ),
    'form_task'         => P_FORM_TASK,
    'timeout'           => P_TIMEOUT,
    'shouts'            => P_SHOUTS . '[]',
    'back_url'          => build_url ( $sb_url, array ( G_ACTION => 'admin') ),
    'total_shouts'      => $sb_obj->get_shouts_count ( ),
    'current_page'      => $sb_cur_page,
    'total_pages'       => $sb_total_pages,
    'next_link'         => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'list', G_PAGE => ( $sb_cur_page == $sb_total_pages ? 1 : $sb_cur_page+1 ) ) ),
    'prev_link'         => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'list', G_PAGE => ( $sb_cur_page <= 1 ? $sb_total_pages : $sb_cur_page-1 ) ) ),
);

eval ( get_list_template ( $sb_root . 'shoutbox_list.htm', $sb_tpl_info ) );

return;
?>
