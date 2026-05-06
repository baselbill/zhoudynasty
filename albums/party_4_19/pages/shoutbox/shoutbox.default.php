<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file' );
}

//--------------------------------------------------
// Function to get the display template
//--------------------------------------------------
function get_display_template ( $template_file, $vars = array ( ) )
{
    global $sb_date_format, $sb_admin_logged_in;

    $fp = @fopen ( $template_file, 'rb' );

    if ( !$fp )
    {
        exit ( 'Unable to open the display template ' . $template_file );
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
    $str = str_replace ( "'", "\'", str_replace("\\", "\\\\", $str) );
    
    $str = "print '" . $str;
    
    if ( preg_match ( '#(\{start shouts\})(.+?)(\{end shouts\})#mis', $str, $m ) )
    {
        $m[1] = "';" . 'for ( $i = 0; $i < count ( $sb_shouts ); $i++ ) { ' . "print '";
        $s =& $m[2];
        $s = preg_replace ( '#\{rowcolor:(.+?)\|(.+?)\}#', "'.".'($i&1?\'$1\':\'$2\')'.".'",$s);
        $s = str_replace ( '{end shouts}', "';} print '", $s );
        $s = str_replace ( '{name}', "' . " . '$sb_shouts[$i][1]' . ".'", $s );
        $s = str_replace ( '{name_linked}', "' . " . 'link_name ( $sb_shouts[$i][1], $sb_shouts[$i][2], $sb_shouts[$i][3]  )' . ".'", $s );
        $s = str_replace ( '{message}', "' . " . '($sb_shouts[$i][5]&&!$sb_admin_logged_in?"Private shout [".$sb_shouts[$i][0]."]" : format_msg ( $sb_shouts[$i][4] ) )' . ".'", $s );
        $s = str_replace ( '{shout_id}', "' . " . '$sb_shouts[$i][0]' . ".'", $s );
        $s = str_replace ( '{time}', "' . " . 'date("'.$sb_date_format.'",$sb_shouts[$i][7]) . (time()-$sb_shouts[$i][7]<3600*24?" (Today)":"")' . ".'", $s );
        $s = str_replace ( '{url}', "' . " . '$sb_shouts[$i][2]' . ".'", $s );
        $s = str_replace ( '{email}', "' . " . '$sb_shouts[$i][3]' . ".'", $s );
        $m[3] = "';} print '";
        $str = str_replace ( $m[0], $m[1] . $m[2] . $m[3], $str );
    }
    
    $str .= "';";
    
    return $str;
}


require_once ( $sb_root . 'shoutbox.class.php' );

$sb_obj = new shoutbox ( $sb_root . SHOUTS_DIR );

// admin?
$sb_admin_logged_in = isset ( $_COOKIE2[C_ADMIN_PW] ) && $_COOKIE2[C_ADMIN_PW] == md5 ( $sb_admin_password );

if ( $sb_perpage && $sb_obj->get_shouts_count( ) > $sb_perpage )
{
    $sb_total_pages = ceil ( $sb_obj->get_shouts_count ( ) / $sb_perpage );
    
    $sb_cur_page = isset ( $_GET2[G_PAGE] ) ? abs ( intval ( $_GET2[G_PAGE] ) ) : 1;

    if ( $sb_cur_page > $sb_total_pages || $sb_cur_page < 1 )
    {
        $sb_cur_page = 1;
    }

    if ( $sb_cur_page == 1 )
    {
        $sb_shouts = $sb_obj->get_shouts ( -1, $sb_perpage );
    }
    else
    {
        $sb_end = $sb_obj->get_shouts_count ( ) - ( $sb_perpage * ( $sb_cur_page - 1 ) );

        $sb_start = ( $sb_cur_page == $sb_total_pages ) ? 0 : $sb_end - $sb_perpage;

        $sb_shouts = $sb_obj->get_shouts ( $sb_start, $sb_end );
    }

}
else
{
    $sb_total_pages = 1;

    $sb_shouts = $sb_obj->get_all_shouts ( );

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
    $sb_shouts[0] = array ( 1, 'Shoutbox Message', '', '', 'No shouts yet. Be the first!', 0, 0, time ( ) );
    $sb_cur_page = 0;
    $sb_total_pages = 0;
}


            
$sb_tpl_info = array
(
    'total_shouts'      => $sb_obj->get_shouts_count ( ),
    'poster_name'       => isset ( $_COOKIE2[C_NAME] ) ? $_COOKIE2[C_NAME] : 'Guest',
    'poster_url'        => isset ( $_COOKIE2[C_URL] ) ? $_COOKIE2[C_URL] : 'http://',
    'poster_email'      => isset ( $_COOKIE2[C_EMAIL] ) ? $_COOKIE2[C_EMAIL] : '',
    'current_page'      => $sb_cur_page,
    'total_pages'       => $sb_total_pages,
    'users_online'      => $sb_users_online ? get_users_online ( $sb_root . ONLINE_FILE, $sb_users_timout ) : 'Feature Disabled',
    'next_link'         => build_url ( $sb_url, array ( G_PAGE => ( $sb_cur_page == $sb_total_pages ? 1 : $sb_cur_page +1 ) ) ),
    'prev_link'         => build_url ( $sb_url, array ( G_PAGE => ( $sb_cur_page <= 1 ? $sb_total_pages : $sb_cur_page -1 ) ) ),
    'form_action'       => build_url ( $sb_url, array ( G_ACTION => 'add' ) ),
    'help_url'          => build_url ( $sb_url, array ( G_ACTION => 'help' ) ),
    'stats_url'         => build_url ( $sb_url, array ( G_ACTION => 'stats' ) ),
    'admin_url'         => build_url ( $sb_url, array ( G_ACTION => 'admin' ) ),
    'name_field'        => P_NAME,
    'message_field'     => P_MESSAGE,
    'url_field'         => P_URL,
    'private_field'     => P_PRIVATE,
    'email_field'       => P_EMAIL,
    'remember_field'    => P_REMEMBER,
    'wordwrap_width'    => 80,
);

eval ( get_display_template ( $sb_root . 'shoutbox_display.htm', $sb_tpl_info ) );

return;
?>
