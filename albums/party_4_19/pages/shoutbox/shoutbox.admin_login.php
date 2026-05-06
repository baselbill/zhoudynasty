<?php
if ( $sb_admin_loggedin )
{
    print 'You are already logged in.';

    return;
}

if ( isset ( $_POST2[P_ADMIN_PW] ) )
{
    if ( md5 ( $_POST2[P_ADMIN_PW] ) == md5 ( $sb_admin_password ) )
    {
        $sb_admin_loggedin = true;

        setcookie ( C_ADMIN_PW, md5 ( $sb_admin_password ), time ( ) + 3600 * 24 * 30, '/' );

        header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'admin' ), '&' ) );
    }
    else
    {
        header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => E_BAD_PW ), '&' ) );

        $sb_admin_loggedin = false;
    }
}

if ( !$sb_admin_loggedin )
{
    print ( get_template ( $sb_root . 'shoutbox_login.htm', array ( 'back_url' => ( isset ( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : $sb_url ), 'password_field' => P_ADMIN_PW, 'form_action' => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'login' ) ) ) ) );
}
?>
