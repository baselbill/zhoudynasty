<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file.' );
}

//--------------------------------------------------
// Get shouts to be deleted
//--------------------------------------------------
$sb_shouts        = isset ( $_POST2[P_SHOUTS] )     ? $_POST2[P_SHOUTS] : array ( );
$sb_form_task     = isset ( $_POST2[P_FORM_TASK] )  ? $_POST2[P_FORM_TASK] : 0;
$sb_timeout       = isset ( $_POST2[P_TIMEOUT] )    ? (float)$_POST2[P_TIMEOUT] : 0;

require_once ( $sb_root . 'shoutbox.class.php' );

$sb_obj = new shoutbox ( $sb_root . 'shouts' );

if ( $sb_form_task === '{1}' )
{
    if ( !count ( $sb_shouts ) )
    {
        header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => E_NO_SELECTED ), '&' ) );
    }
    else
    {
        for ( $i = 0; $i < count ( $sb_shouts ); $i++ )
        {
            $sb_obj->delete_shout ( $sb_shouts[$i] );
        }
        
        header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => M_DEL_SUCCESS ), '&' ) );
    }
}
elseif ( $sb_form_task === '{2}' )
{
    $sb_obj->prune_shouts ( $sb_timeout * 3600 * 24 );
    
    header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => M_DEL_SUCCESS ), '&' ) );
}
else
{
    header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => E_INVALID ), '&' ) );
}




?>
