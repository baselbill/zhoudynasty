<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file.' );
}
//--------------------------------------------------
// &amp; back to &, etc
//--------------------------------------------------
function make_unsafe ( $str )
{
    $str = str_replace ( '&amp;', '&', $str );
    $table = get_html_translation_table ( HTML_ENTITIES );
    array_shift ( $table );
    unset ( $table [ '"' ] );
    $table = array_flip ( $table );
    $str = strtr ( $str, $table );
    return preg_replace ( '#\n\[i\]Edited.+?\[\/i\]$#is', '', $str );
}
//--------------------------------------------------
// Get ID and edit var
//--------------------------------------------------
$sb_shoutid = isset ( $_GET2[G_SHOUTID] ) ? intval ( $_GET2[G_SHOUTID] ) : 0;
$sb_do_edit = isset ( $_POST2[P_FORM_TASK] );

require_once ( $sb_root . 'shoutbox.class.php' );

$sb_obj = new shoutbox ( $sb_root . 'shouts' );


if ( !$sb_shoutid )
{
    header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => E_NO_SELECTED ), '&' ) );
}
else
{
    //--------------------------------------------------
    // Do edit
    //--------------------------------------------------
    if ( $sb_do_edit )
    {
        $sb_back_url = build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'list' ), '&' );
        
        if ( $sb_obj->edit_shout( $sb_shoutid, $_POST2[P_NAME], $_POST2[P_URL], $_POST2[P_EMAIL], $_POST2[P_MESSAGE] ) )
        {
            header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => M_EDITED, G_BACKURL => $sb_back_url ), '&' ) );
        }
        else
        {
            header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => M_NOTEDITED, G_BACKURL => $sb_back_url ), '&' ) );
        }
        return;
    }

    //--------------------------------------------------
    // Get shout and display into form
    //--------------------------------------------------
    $sb_found = $sb_obj->get_shout ( $sb_shoutid );
    
    if ( !$sb_found )
    {
        $sb_back_url = build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'list' ), '&' );

        header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => E_NOTFOUND, G_BACKURL => $sb_back_url ), '&') );

        return;
    }

    $sb_tpl_vars = array
    (
         'form_action'             => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'edit', G_SHOUTID => $sb_shoutid ) ),
         'form_task'               => P_FORM_TASK,
         'shout_id'                => $sb_shoutid,
         'name'                    => $sb_found[1],
         'url'                     => $sb_found[2],
         'email'                   => $sb_found[3] == '' ? 'No Email' : $sb_found[3],
         'private'                 => $sb_found[5] ? 'Yes' : 'No',
         'ip'                      => $sb_found[6],
         'time'                    => date ( $sb_date_format, $sb_found[7] ),
         'message'                 => make_unsafe ( $sb_found[4] ) . "\n[i]Edited by Admin on " . date ( $sb_date_format ) . '[/i]',
         'back_url'                => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'list' ) ),
         'name_field'              => P_NAME,
         'message_field'           => P_MESSAGE,
         'url_field'               => P_URL,
         'private_field'           => P_PRIVATE,
         'email_field'             => P_EMAIL,
         'remember_field'          => P_REMEMBER,
    );

    print get_template ( $sb_root . 'shoutbox_edit.htm', $sb_tpl_vars );

}
?>
