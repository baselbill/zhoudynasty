<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file.' );
}

function stripslashes_gpc ( $var )
{
    if ( is_array ( $var ) )
    {
        while ( list ( $k, $v ) = each ( $var ) )
        {
            $var [ $k ] = stripslashes_gpc ( $v );
        }
        return $var;
    }
    else
    {
        return stripslashes ( $var );
    }
}

function build_url ( $base_url, $queries = '', $amp = '&amp;' )
{
    if ( !is_array ( $queries ) )
    {
        $q = $queries;
    }
    else
    {
        $a = parse_url ( $base_url );

        while ( list ( $v, $k ) = each ( $queries ) )
        {
            $q [] = $v . '=' . urlencode ( $k );
        }

        $q = implode ( $amp, $q );
    }

    return ( isset ( $a['scheme'] ) ? $a['scheme'] . '://' : '' ) . ( isset ( $a['host'] ) ? $a['host'] : '' ) . ( isset ( $a['path'] ) ? $a['path'] : '/' ) . ( isset ( $a['query'] ) ? '?' . $a['query'] . $amp : '?' ) . $q ;
}


function get_template ( $template_file, $vars = array ( ) )
{
    $fp = @fopen ( $template_file, 'rb' );

    if ( !$fp )
    {
        exit ( 'Unable to open the template ' . $template_file );
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
    return $str;
}

function slice ( $str, $width )
{
    if ( strlen ( $str ) <= $width )
    {
        return $str;
    }
    return substr ( $str, 0, ( $width / 2 ) - 1 ) . '...' . substr ( $str, - ( $width / 2 - 2 ) );
}

function parse_smilies ( &$str )
{
    global $sb_smilies_url, $sb_smilies;
    
    while ( list ( $v, $k ) = each ( $sb_smilies ) )
    {
        $str = str_replace ( $v, '<img src="' . $sb_smilies_url . $k . '" alt="" />', $str );
    }
    reset ( $sb_smilies );
}

function filter ( &$str )
{
    global $sb_bad_words;

    $words = explode ( ',', $sb_bad_words );
    
    for ( $i = 0; $i < count ( $words ); $i++ )
    {
        if ( $words[$i] !== '' )
        {
            $str = preg_replace ( '#' . $words[$i] . '#i', str_pad ( '', strlen ( $words[$i] ), '*' ), $str );
        }
    }
}


function parse_bb ( &$str )
{
    global $sb_wordwrap_length, $sb_allow_img_tags;

    if ( preg_match_all ( '#(\s+|^)(www|ftp).([^\s\n\r\t\<\>\*\[]+)#is', $str, $m ) )
    {
        for ( $i = 0; $i < count ( $m[0] ); $i++ )
        {
            $str = str_replace ( $m[0][$i], $m[1][$i] . '[url="' . ( $m[2][$i] == 'www' ? 'http://' : 'ftp://' ) . $m[2][$i] . '.' . $m[3][$i] . '"]' . $m[0][$i] . '[/url]', $str );
        }
    }

    $search = array
    (
        '#\[b\](.+?)\[\/b\]#is',
        '#\[i\](.+?)\[\/i\]#is',
        '#\[u\](.+?)\[\/u\]#is',
        $sb_allow_img_tags ? '#\[img\](.+?)\[\/img]#is' : '#\[img\](http|https|ftp|news)://([^\s\n\r\t\<\>\*\[]+)\[\/img]#is',
        '#\[color=([a-z0-9\#]+?)\](.+?)\[\/color\]#is',
        '#(^|\s+)(http|https|ftp|news)://([^\s\n\r\t\<\>\*\[]+)#is',
        '#\[url\]([^\n\r\t\<\>\*\[]+)\[\/url\]#is',
        '#(^|\s+)([\w]+@[\w]+.[\w]{2,3})#is',
        '#\[email=\"*([\w]+@[\w]+.[\w]{2,3})\"*\](.+?)\[\/email\]#is',
        '#\[url=\"*([^\"\']+?)\"*\](.+?)\[\/url\]#ise',

    );

    $replace = array
    (
        '<strong>$1</strong>',
        '<em>$1</em>',
        '<span style="border-bottom: 1px solid;">$1</span>',
        $sb_allow_img_tags ? '<img src="$1" alt="$1" class="shoutbox" />' : '[url="$1://$2"]$1://$2[/url]',
        '<span style="color:$1">$2</span>',
        '$1[url="$2://$3"]$2://$3[/url]',
        '[url="$1"]$1[/url]',
        '$1[url="mailto:$2"]$2[/url]',
        '[url="mailto:$1"]$2[/url]',
        '"<a href=\"".wordwrap(\'$1\','.($sb_wordwrap_length/2).',"\n",1)."\" class=\"shoutbox\" onmouseover=\"this.title=this.href\" onclick=\"window.open(this.href); return false;\">" . slice(\'$2\','.$sb_wordwrap_length.',1)."</a>"',
    );

    $str = preg_replace ( $search, $replace, $str );
}

function format_msg ( &$str )
{
    global $sb_wordwrap_length, $sb_allow_bb, $sb_allow_smilies, $_GET2, $sb_language_filter;

    // language filter
    if ( $sb_language_filter )
    {
        filter ( $str );
    }

    // clean the message up a bit
    $str = nl2br ( $str );
    
    if ( $sb_allow_bb )
    {
        parse_bb ( $str );
    }
    $str = str_replace ( '  ', '&nbsp; ', $str );

    // quick highlight function
    
    $hilite = isset ( $_GET2[G_HIGHLIGHT] ) ? $_GET2[G_HIGHLIGHT] : '';

    if ( $hilite !== '' )
    {
        $words = explode ( ' ', $hilite );

        for ( $i = 0; $i < count ( $words ); $i++ )
        {
            if ( strlen ( $words[$i] ) < 3 )
            {
                continue;
            }
            $str = str_replace ( $words[$i], '<span style="color:red;">' . $words[$i] . '</span>', $str );
        }
    }

    // wordwrapping
    $str = preg_replace ( '#([^\s\n\<\>]{' . $sb_wordwrap_length . ',})#ie', 'wordwrap ( \'$1\', ' . $sb_wordwrap_length . ', "<br />", 1 )', $str );

    if ( $sb_allow_smilies )
    {
        parse_smilies ( $str );
    }
    return $str;
}

function link_name ( $name, $url, $email )
{
    // Valid URL?
    if ( $url != 'http://' && $url != '' && $url != 'Optional' )
    {
        if ( strstr ( $url, 'http://' ) )
        {
            return '<a href="' . $url . '" class="shoutbox" title="URL:' . $url . "\nEmail:" . $email . '">' . $name . '</a>';
        }
        else
        {
            return '<a href="http://' . $url . '" class="shoutbox" title="URL:' . $url . "\nEmail:" . $email . '">' . $name . '</a>';
        }
    }
    // Valid Email address?
    if ( preg_match ( '#([\w]+@[\w]+.[\w]{2,3})#is', $email ) )
    {
        return '<a href="mailto:' . $email . '" class="shoutbox" title="URL:' . $url . "\nEmail:" . $email . '">' . $name . '</a>';
    }
    // Invalid name and Invalid URL, return plain name
    return $name;
}

function get_users_online ( $users_file, $timeout = 60 )
{
    $fp = fopen ( $users_file, 'rb' );
    flock ( $fp, 1 );
    $online = unserialize (  fread ( $fp, filesize ( $users_file ) ) );
    $online [ $_SERVER['REMOTE_ADDR'] ] = time ( );
    fclose ( $fp );
    
    while ( list ( $ip, $last_seen ) = each ( $online ) )
    {
        if ( time ( ) - $last_seen > $timeout )
        {
            unset ( $online[$ip] );
        }
    }
    
    $fp = fopen ( $users_file, 'wb' );
    flock ( $fp, 2 );
    fwrite ( $fp, serialize ( $online ) );
    fclose ( $fp );

    return count ( $online );
}

?>
