<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file' );
}
//--------------------------------------------------
// Check password
//--------------------------------------------------
if ( !isset ( $_COOKIE2[C_ADMIN_PW] ) )
{
    $sb_admin_action = 'login';
    
    $sb_admin_loggedin = false;
}
else
{
    if ( $_COOKIE2[C_ADMIN_PW] == md5 ( $sb_admin_password ) )
    {
        $sb_admin_action = isset ( $_GET[G_TASK] ) ? $_GET[G_TASK] : 'default';
        
        $sb_admin_loggedin = true;
    }
    else
    {
        $sb_admin_action = 'login';
        
        $sb_admin_loggedin = false;
    }
}

switch ( $sb_admin_action )
{
    default:
            //--------------------------------------------------
            // Directory size
            //--------------------------------------------------
            $sb_shouts_dir_size = 0;
            
            $h = opendir ( $sb_root . SHOUTS_DIR );

            while ( false !== ( $f = readdir ( $h ) ) )
            {
                if ( is_file ( $sb_root . SHOUTS_DIR . '/' . $f ) )
                {
                    $sb_shouts_dir_size += filesize ( $sb_root . SHOUTS_DIR . '/' . $f );
                }
            }
            closedir ( $h );

            clearstatcache ( );
            
            //--------------------------------------------------
            // shout counts
            //--------------------------------------------------
            require_once ( $sb_root . 'shoutbox.class.php' );

            $sb_obj = new shoutbox ( $sb_root . 'shouts' );
            
            $sb_tpl_vars = array
            (
                'back_url'         => $sb_url,

                'logout_url'       => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'logout' ) ),

                'list_url'         => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'list' ) ),

                'delete_url'       => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'delete' ) ),

                'edit_url'         => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'edit' ) ),

                'block_url'        => build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'block' ) ),

                'shouts_count'     => $sb_obj->get_shouts_count ( ),

                'index_size'       => number_format ( filesize ( $sb_root . SHOUTS_DIR . '/shout_index.txt' ) / 1024, 2 ) . 'KB',

                'shouts_dir_size'  => number_format ( $sb_shouts_dir_size / 1024, 2 ) . 'KB',
            );
            
            print get_template ( $sb_root . 'shoutbox_admin.htm', $sb_tpl_vars );
            
    break;
    
    case 'block': require ( $sb_root . 'shoutbox.admin_block.php' ); break;
    
    case 'list': require ( $sb_root . 'shoutbox.admin_list.php' ); break;
    
    case 'view': require ( $sb_root . 'shoutbox.admin_view.php' ); break;
    
    case 'edit': require ( $sb_root . 'shoutbox.admin_edit.php' ); break;
    
    case 'delete': require ( $sb_root . 'shoutbox.admin_delete.php' ); break;
    
    case 'login': require ( $sb_root . 'shoutbox.admin_login.php' ); break;
    
    case 'logout':

         setcookie ( C_ADMIN_PW, '', time ( ) - 3600 * 24 * 30, '/' );

         header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => M_LOGGED_OUT ), '&' ) );

         break;
}
?>
