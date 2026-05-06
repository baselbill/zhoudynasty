<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file' );
}
//--------------------------------------------------
// Get blocked list, is IP blocked?
//--------------------------------------------------
$fp = fopen ( $sb_root . BLOCKED_FILE, 'rb' );

$sb_filesize = filesize ( $sb_root . BLOCKED_FILE );

$sb_list = $sb_filesize ? unserialize ( fread ( $fp, filesize ( $sb_root . BLOCKED_FILE ) ) ) : array ( );

fclose ( $fp );

if ( in_array ( $_SERVER['REMOTE_ADDR'], $sb_list ) )
{
    header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => M_BLOCKED ), '&' ) );
    
    return;
}

//--------------------------------------------------
// Get required inputs
//--------------------------------------------------
$sb_post_name =     isset ( $_POST2[P_NAME] )      ? trim ( $_POST2[P_NAME] )     : '';
$sb_post_message =  isset ( $_POST2[P_MESSAGE] )   ? trim ( $_POST2[P_MESSAGE] )  : '';
$sb_post_url =      isset ( $_POST2[P_URL] )       ? trim ( $_POST2[P_URL] )      : '';
$sb_post_email =    isset ( $_POST2[P_EMAIL] )     ? trim ( $_POST2[P_EMAIL] )    : '';
$sb_post_private =  isset ( $_POST2[P_PRIVATE] )   ? (bool)$_POST2[P_PRIVATE]     : false;
$sb_post_remember = isset ( $_POST2[P_REMEMBER] )  ? (bool)$_POST2[P_REMEMBER]    : false;

$sb_error_var = 0;

if ( strlen ( $sb_post_name ) === 0 )
{
    $sb_error_var = E_NONAME;
}
elseif ( strlen ( $sb_post_name ) > $sb_max_name_length )
{
    $sb_error_var = E_LONGNAME;
}

if ( strlen ( $sb_post_message ) === 0 )
{
    $sb_error_var = E_NOMSG;
}
elseif ( strlen ( $sb_post_message ) > $sb_max_message_length )
{
    $sb_error_var = E_LONGMSG;
}

if ( $sb_error_var )
{
    header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => $sb_error_var ), '&' ) );

    break;
}

if ( $sb_post_remember )
{
    setcookie ( C_NAME, $sb_post_name, time ( ) + 2592000, '/' );
    setcookie ( C_EMAIL, $sb_post_email, time ( ) + 2592000, '/' );
    setcookie ( C_URL, $sb_post_url, time ( ) + 2592000, '/' );
}
elseif ( !$sb_post_remember && isset ( $_COOKIE[C_NAME] ) )
{
    setcookie ( C_NAME, $sb_post_name, time ( ) - 2592000, '/' );
    setcookie ( C_EMAIL, $sb_post_email, time ( ) - 2592000, '/' );
    setcookie ( C_URL, $sb_post_url, time ( ) - 2592000, '/' );
}

require_once ( $sb_root . 'shoutbox.class.php' );

$sb_obj = new shoutbox ( $sb_root . 'shouts' );

$sb_obj->add_shout ( $sb_post_name, $sb_post_url, $sb_post_email, $sb_post_message, $sb_post_private );

header ( 'Location: ' . build_url ( $sb_url, array ( G_PAGE => 1 ), '&' ) );
            
?>
