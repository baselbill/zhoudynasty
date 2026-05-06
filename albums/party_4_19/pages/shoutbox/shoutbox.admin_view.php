<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file.' );
}

$sb_shoutid = isset ( $_GET2[G_SHOUTID] ) ? intval ( $_GET2[G_SHOUTID] ) : 0;

if ( !$sb_shoutid )
{
    header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => E_NO_SELECTED ), '&' ) );
}
else
{
    require_once ( $sb_root . 'shoutbox.class.php' );

    $sb_obj = new shoutbox ( $sb_root . 'shouts' );
    
    $sb_found = $sb_obj->get_shout ( $sb_shoutid );
    
    if ( !$sb_found )
    {
        $sb_back_url = build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'list' ), '&' );
        
        header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => E_NOTFOUND, G_BACKURL => $sb_back_url ), '&') );
        
        return;
    }
    
    $sb_tpl_vars = array
    (
         'shout_id'     => $sb_shoutid,
         'name'         => $sb_found[1],
         'url'          => $sb_found[2],
         'email'        => $sb_found[3] == '' ? 'No Email' : $sb_found[3],
         'private'      => $sb_found[5] ? 'Yes' : 'No',
         'ip'           => $sb_found[6],
         'time'         => date ( $sb_date_format, $sb_found[7] ),
         'message'      => format_msg ( $sb_found[4] ),
         'back_url'     => isset ( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : $sb_url,
         'edit_url'     => build_url ( $sb_url, array ( G_ACTION => "admin", G_TASK => "edit", G_SHOUTID => $sb_shoutid ) ),
    );
    
    print get_template ( $sb_root . 'shoutbox_view.htm', $sb_tpl_vars );

}
?>
