<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file.' );
}

//--------------------------------------------------
// get template
//--------------------------------------------------
function get_block_template ( $template_file, $vars = array ( ) )
{
    global $sb_date_format;

    $fp = @fopen ( $template_file, 'rb' );

    if ( !$fp )
    {
        exit ( 'Unable to open the block template ' . $template_file );
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

    if ( preg_match ( '#(\{start blocked\})(.+?)(\{end blocked\})#mis', $str, $m ) )
    {
        $m[1] = "';" . 'for ( $i = 0; $i < count ( $sb_list ); $i++ ) { ' . "print '";
        $s =& $m[2];
        $s = preg_replace ( '#\{rowcolor:(.+?)\|(.+?)\}#', "'.".'($i&1?\'$1\':\'$2\')'.".'",$s);
        $s = str_replace ( '{end blocked}', "';} print '", $s );
        $s = str_replace ( '{ip}', "' . " . '$sb_list[$i]' . ".'", $s );
        $m[3] = "';} print '";

        $str = str_replace ( $m[0], $m[1] . $m[2] . $m[3], $str );
    }
    $str .= "';";

    return $str;
}


//--------------------------------------------------
// Get required vars
//--------------------------------------------------
$sb_ip = isset ( $_POST2[P_IP_ADDR] ) ? $_POST2[P_IP_ADDR] : 0;

//--------------------------------------------------
// Get list
//--------------------------------------------------
$fp = fopen ( $sb_root . BLOCKED_FILE, 'rb' );

$sb_filesize = filesize ( $sb_root . BLOCKED_FILE );

$sb_list = $sb_filesize ? unserialize ( fread ( $fp, filesize ( $sb_root . BLOCKED_FILE ) ) ) : array ( );

fclose ( $fp );

//--------------------------------------------------
// Do blocking
//--------------------------------------------------
if ( $sb_ip )
{
    // Is valid IP??
    if ( !preg_match ( '#^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$#', $sb_ip ) )
    {
         header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => E_INVALID_IP ), '&' ) );
        
        return;
    }
    
    $sb_found = array_search ( $sb_ip, $sb_list );
    
    if ( $sb_found === false || $sb_found === NULL )
    {
        // Block
        $sb_list [] = $sb_ip;
        
        header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => M_IP_BLKD ), '&' ) );
    }
    else
    {
        // unblock
        unset ( $sb_list [ $sb_found ] );
        
        $sb_list = array_values ( $sb_list );
        
        header ( 'Location: ' . build_url ( $sb_url, array ( G_ACTION => 'showmsg', G_MESSAGE => M_IP_UNBLKD ), '&' ) );
    }
    
    $fp = fopen ( $sb_root . BLOCKED_FILE, 'wb' );
    
    fwrite ( $fp, serialize ( $sb_list ) );
    
    fclose ( $fp );
}
else
{
    if ( !is_array ( $sb_list ) || !count ( $sb_list ) )
    {
        // No blocked IP yet
        $sb_list [] = 'No IP addresses blocked.';
    }
    eval (  get_block_template ( $sb_root . 'shoutbox_block.htm', array ( 'ip_field' => P_IP_ADDR, 'form_action' =>  build_url ( $sb_url, array ( G_ACTION => 'admin', G_TASK => 'block' ) ), 'back_url' => build_url ( $sb_url, array ( G_ACTION => 'admin') ) ) ) );
}



?>
