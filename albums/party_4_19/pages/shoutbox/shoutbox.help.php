<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file.' );
}

//--------------------------------------------------
// get help template
//--------------------------------------------------
function get_help_template ( $template_file, $vars = array ( ) )
{
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

    if ( preg_match ( '#(\{start smilies\})(.+?)(\{end smilies\})#mis', $str, $m ) )
    {
        $m[1] = "';" . '$i = 0; foreach ( $sb_smilies as $text => $icon ) { ' . "print '";
        $s =& $m[2];
        $s = preg_replace ( '#\{rowcolor:(.+?)\|(.+?)\}#', "'.".'($i&1?\'$1\':\'$2\')'.".'",$s);
        $s = str_replace ( '{end smilies}', "';} print '", $s );
        $s = str_replace ( '{text}', "' . " . '$text' . ".'", $s );
        $s = str_replace ( '{image}', "' . " . '$sb_smilies_url . $icon' .  ".'", $s );
        $m[3] = "';\$i++;} print '";

        $str = str_replace ( $m[0], $m[1] . $m[2] . $m[3], $str );
    }
    $str .= "';";

    return $str;
}

eval ( get_help_template ( $sb_root . 'shoutbox_help.htm', array ( 'back_url' => $sb_url )  ) );


?>
