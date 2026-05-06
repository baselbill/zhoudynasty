<?php
ob_start ( );
//--------------------------------------------------
// User defined settings. 1 = enable/allow, 0 = disable/disallow
//--------------------------------------------------

// Admin password, change it!!
$sb_admin_password = 'zh23ou09';

// URL to access the shoutbox. Ex: http://yoursite.com/index.php?page=shoutbox or http://yoursite.com/shoutbox.php
$sb_url = 'shoutbox.php';

// Order of shouts on the page: 1 for newest shouts to oldest, 0 for oldest to newest
$sb_shouts_order = 1;

// Enable basic checkings to make sure your server can run the shoutbox
$sb_checking = 1;

// Shouts to display per page. 0 for all on one page
$sb_perpage =  10;

// Max characters allowed in poster's name
$sb_max_name_length = 25;

// max characters allowed in poster's message
$sb_max_message_length = 1000;

// The date format, not very important
$sb_date_format = 'm/d/y h:i A';

// insert line breaks for looooooooooooonnnnnnnngggggggg words over this many characters
$sb_wordwrap_length = 26;

// Enable or disable tracking of users reading the shoutbox.
$sb_users_online = 1;

// Timeout in seconds before user is considered OFFLINE(from the shoutbox)
$sb_users_timout = 30;

// Bad word filter, "bitch" will be replaced with "*****"
$sb_language_filter = 1;

// Bad words. Just seperate them words with a command like so: "bad,word,hehe,yes,very,naughty".
//Try not to leave any spaces in between the words, like this: "bad, words, suck, yeah , i know".
$sb_bad_words = 'fuck,faggot,nigger,jew';

// Allow BB code
$sb_allow_bb = 0;

// Allow IMG tags with BB codes.
$sb_allow_img_tags = 0;

// Allow smilies parsing. Turns :) into the smilie image.
$sb_allow_smilies = 1;

// URL to the folder of smilies.
// Add trailing slash at the end, meaning "http://google.com/smilies" is wrong, "http://google.com/smilies/" is correct!
$sb_smilies_url = '/shoutbox/smilies/';

//--------------------------------------------------
// Smilies table, if you have disabled smilies, then don't worry about this.
// Look carefully and follow the pattern. The format is:
//     'smilie text'    =>    'smilie icon',
//--------------------------------------------------
$sb_smilies = array
             ( // Edit in here, don't delete this parenthesis --------------------------------------
             
					':)'            => 'smile.gif',
					':('            => 'frown.gif',
					':D'            => 'biggrin.gif',
					'=D'            => 'biggrin.gif',
					';)'            => 'wink.gif',
					':-/'           => 'rolleyes.gif',
					'8-)'           => 'cool.gif',
					':cool:'        => 'cool.gif',
					':tard:'        => 'tard.gif',
					':mad:'         => 'mad.gif',
					':o'            => 'eek.gif',
					':eek:'         => 'eek.gif',
					':confused:'    => 'confused.gif',
					'b7'            => 'dancebaconsmall.gif',
					':google:'      => 'google.gif',
					':lol:'         => 'lol.gif',
					
             ); // don't delete this parenthesis and semicolon------------------------
             

//------------------------------------------------------------------------------
// Avoid variable conflicts. Chances are there will be none but you never know.
//-----------------------------------------------------------------------------
define ( 'G_ACTION',      'act'                     );
define ( 'G_TASK',        'task'                    );
define ( 'G_PAGE',        'pagenumber'              );
define ( 'G_MESSAGE',     'message'                 );
define ( 'G_SHOUTID',     'shoutid'                 );
define ( 'G_HIGHLIGHT',   'highlight'               );
define ( 'G_BACKURL',     'redir'                   );

define ( 'P_NAME',        'name'                    );
define ( 'P_MESSAGE',     'message'                 );
define ( 'P_URL',         'url'                     );
define ( 'P_PRIVATE',     'private'                 );
define ( 'P_EMAIL',       'email'                   );
define ( 'P_REMEMBER',    'remember'                );
define ( 'P_ADMIN_PW',    'shoutbox_admin_pw'       );
define ( 'P_TIMEOUT',     'timeout'                 );
define ( 'P_SHOUTS',      'shouts'                  );
define ( 'P_FORM_TASK',   'task'                    );
define ( 'P_IP_ADDR',     'ip_address'              );

define ( 'C_NAME',        'shoutbox_name'           );
define ( 'C_URL',         'shoutbox_url'            );
define ( 'C_EMAIL',       'shoutbox_email'          );
define ( 'C_ADMIN_PW',    'shoutbox_admin_pw'       );

//------------------------------------------------------------------------------
// Not much to change in here
//-----------------------------------------------------------------------------
define ( 'E_NONAME',      1                         );
define ( 'E_LONGNAME',    2                         );
define ( 'E_NOMSG',       3                         );
define ( 'E_LONGMSG',     4                         );
define ( 'E_BAD_PW',      5                         );
define ( 'E_NO_MSG',      6                         );
define ( 'E_INVALID',     10                        );
define ( 'E_NO_SELECTED', 11                        );
define ( 'E_NOTFOUND',    15                        );
define ( 'E_INVALID_IP',  22                        );

define ( 'M_LOGGED_OUT',  7                         );
define ( 'M_DEL_FAILED',  8                         );
define ( 'M_DEL_SUCCESS', 9                         );
define ( 'M_EDITED',      13                        );
define ( 'M_NOTEDITED',   14                        );
define ( 'M_BLOCKED',     19                        );
define ( 'M_IP_BLKD',     20                        );
define ( 'M_IP_UNBLKD',   21                        );

define ( 'SHOUTBOX',      'yes'                     );
define ( 'SHOUTS_DIR',    'shouts'                  );
define ( 'ONLINE_FILE',   'online.dat'              );
define ( 'BLOCKED_FILE',  'blocked_ips.dat'           );

//----------------------------------------------------------------------------
// Output messages. Change if you want.
//----------------------------------------------------------------------------
$sb_language = array (

         E_NONAME           => 'You need a name.',
         
         E_LONGNAME         => 'Your name is longer than ' . $sb_max_name_length . ' characters.',

         E_NOMSG            => 'You need something to say.',

         E_LONGMSG          => 'Your message is longer than ' . $sb_max_message_length . ' characters.',
         
         E_BAD_PW           => 'Incorrect password. Try again.',
         
         E_NO_MSG           => 'No message to display',
         
         E_INVALID          => 'Invalid action.',
         
         E_NO_SELECTED      => 'You did not select any shout.',
         
         E_NOTFOUND         => 'That shout does not exist.',
         
         E_INVALID_IP       => 'That IP address is invalid. IP addresses look like 123.123.123.123',
         
         M_BLOCKED          => 'You have been blocked from posting on this shoutbox by the Administrator.',
         
         M_LOGGED_OUT       => 'You have been logged out. You will be taken to the shoutbox.',
         
         M_DEL_SUCCESS      => 'Shouts successfully deleted.',
         
         M_DEL_FAILED       => 'Unable to delete shout(s).',
         
         M_EDITED           => 'Shout successfully edited.',
         
         M_NOTEDITED        => 'An error has occured while editing. The shout ID was invalid therefore the shout could not be found.',
         
         M_IP_BLKD          => 'IP was blocked.',
         
         M_IP_UNBLKD        => 'IP was unblocked.',
         
);

//----------------------------------------------------------------------------
// Nothing to edit down here, save this file and you are done!
//----------------------------------------------------------------------------

$sb_old_magic_quotes = get_magic_quotes_runtime( );

set_magic_quotes_runtime( 0 );


function timer ( $start = 0, $digits = 8 )
{
    list ( $m, $s ) = explode ( ' ', microtime ( ) );

    return round ( (double)$s + (double)$m - $start, $digits );
}

$sb_timer_start = timer ( );

//--------------------------------------------------
// Get some variables
//--------------------------------------------------
$sb_root = str_replace ( 'shoutbox.php', '', __FILE__ );

//--------------------------------------------------
// Load some files
//--------------------------------------------------
if ( !file_exists ( $sb_root . 'shoutbox.functions.php' ) )
{
    print 'Could not find "shoutbox.functions.php" in "' . $sb_root . '"';

    return;
}
else
{
    require ( $sb_root . 'shoutbox.functions.php' );
}

//--------------------------------------------------
// Clean GPC
//--------------------------------------------------
$_GET2    = get_magic_quotes_gpc ( ) ? stripslashes_gpc ( $_GET ) : $_GET;
$_POST2   = get_magic_quotes_gpc ( ) ? stripslashes_gpc ( $_POST ) : $_POST;
$_COOKIE2 = get_magic_quotes_gpc ( ) ? stripslashes_gpc ( $_COOKIE ) : $_COOKIE;


//--------------------------------------------------
// Do some checking before starting
//--------------------------------------------------
if ( $sb_checking && ini_get ( 'safe_mode' ) )
{
    print 'Safe mode is on. To ignore this and continue, change checking to 0';

    return;
}

if ( !file_exists ( $sb_root . SHOUTS_DIR ) )
{
    print "The 'shouts' directory does not exists in '$sb_root'";
    
    return;
}
else
{
    if ( !is_writeable ( $sb_root . SHOUTS_DIR ) )
    {
        print "The 'shouts' directory (" . $sb_root . SHOUTS_DIR  .") is not writeable. <a href=\"http://www.google.com/search?hl=en&ie=UTF-8&oe=UTF-8&q=chmod\">CHMOD</a> it to 0666/0755/0755.";
        
        return;
    }
}

if ( $sb_users_online )
{
    if ( !file_exists ( $sb_root . ONLINE_FILE ) )
    {
        print 'The users file does not exists. You cannot track users online. Create an emtpy file called "' . ONLINE_FILE . '" inside the directory "' . $sb_root . '" and CHMOD it to 0666.';
        
        return;
    }
    if ( !is_writeable ( $sb_root . ONLINE_FILE ) )
    {
        print 'The users online file "' . $sb_root . ONLINE_FILE . '" is not writeable. CHMOD it to 0666 or 0777.';
        
        return;
    }
}

if ( !file_exists ( $sb_root . BLOCKED_FILE ) )
{
    print 'The blocked ip file does not exists. Create an emtpy file called "' . BLOCKED_FILE . '" inside the directory "' . $sb_root . '" and CHMOD it to 0666.';

    return;
}
if ( !is_writeable ( $sb_root . BLOCKED_FILE ) )
{
    print 'The blocked ip file "' . $sb_root . BLOCKED_FILE . '" is not writeable. CHMOD it to 0666 or 0777.';

    return;
}

//--------------------------------------------------
// Is there a header?
//--------------------------------------------------
print file_exists ( $sb_root . 'shoutbox_footer.htm' ) ? get_template ( $sb_root . 'shoutbox_footer.htm' ) : '';

//--------------------------------------------------
// Load in action file
//--------------------------------------------------
$sb_action = isset ( $_GET2[G_ACTION] ) ? $_GET2[G_ACTION] : 'Default' ;

switch ( $sb_action )
{
    case 'help': require ( $sb_root . 'shoutbox.help.php' ); break;
    
    case 'admin':
    
         require ( $sb_root . 'shoutbox.admin.php' );
    
    break;

    case 'add': require ( $sb_root . 'shoutbox.add.php' ); break;
    
    case 'stats': require ( $sb_root . 'shoutbox.stats.php' ); break;

    case 'showmsg':
    
         $sb_back_url = isset ( $_GET2[G_BACKURL] ) ? $_GET2[G_BACKURL] : ( isset ( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : $sb_url );

         header ( 'Refresh: 2;URL=' . $sb_back_url );
         
         $sb_message = isset ( $_GET2[G_MESSAGE] ) ? $_GET2[G_MESSAGE] : 0;

         $sb_output = isset ( $sb_language[$sb_message] ) ? $sb_language[$sb_message] : $sb_language[E_NO_MSG];
         
         print get_template ( $sb_root . 'shoutbox_message.htm', array ( 'message' => $sb_output, 'back_url' => $sb_back_url ) );

    break;
    
    default:
            require ( $sb_root . 'shoutbox.default.php' );
    break;
}

//--------------------------------------------------
// Is there a footer?
//--------------------------------------------------
print file_exists ( $sb_root . 'shoutbox_footer.htm' ) ? get_template ( $sb_root . 'shoutbox_footer.htm', array ( 'timer' => timer ( $sb_timer_start, 4 ) ) ) : '';

set_magic_quotes_runtime( $sb_old_magic_quotes );

ob_end_flush ( );
?>
