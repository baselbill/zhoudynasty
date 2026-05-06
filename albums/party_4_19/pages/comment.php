<?php
// Settings:

// File in which the entries will be saved (requires CHMOD 666):
$comment_file = "comments.dat";

// Admin E-mail for notifications (optional):
$comment_admin_email = "";

// Add new comments at the top or at the bottom?
$comment_add_comments = "bottom";

// How many comments per page?
$comment_comments_per_page = 10;

// Make links clickable:
$comment_autolink = true;

// Length limitations:
$comment_text_maxlength = 500;
$comment_word_maxlength = 50;

// If no name is entered:
$comment_anonym = "Anonym";

// Time format:
$comment_time_format = "%d.%m.%Y, %H:%M";

// End of settings:

function comment_make_link($string)
 {
  $string = ' ' . $string;
  $string = preg_replace("#(^|[\n ])([\w]+?://.*?[^ \"\n\r\t<]*)#is", "\\1<a href=\"\\2\">\\2</a>", $string);
  $string = preg_replace("#(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:/[^ \"\t\n\r<]*)?)#is", "\\1<a href=\"http://\\2\">\\2</a>", $string);
  $string = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $string);
  $string = substr($string, 1);
  return $string;
 }

if (isset($_GET['comment_id'])) $comment_id = $_GET['comment_id'];
if (isset($_POST['comment_id'])) $comment_id = $_POST['comment_id'];
if (isset($_GET['comment_popup'])) $comment_popup = $_GET['comment_popup'];
if (isset($_POST['comment_popup'])) $comment_popup = $_POST['comment_popup'];

if (empty($comment_popup) && empty($comment_id) && empty($_GET['get_comment_link'])) $comment_id = basename($_SERVER["PHP_SELF"]);

if (isset($comment_id))
 {
  if (isset($_GET['comment_page'])) $comment_page = $_GET['comment_page']; else $comment_page = 1;

// wenn Kommentar eingegeben wurde:
if (isset($_POST['comment_text']) && trim($_POST['comment_text']) != "")
  {
   // Überprüfungen:
   unset($errors);
   if (strlen($_POST['comment_text']) > $comment_text_maxlength)
    $errors[] = "the text is too long (".strlen($_POST['comment_text'])." characters - maximum is ".$comment_text_maxlength." characters)";
   $text_arr = str_replace("\n", " ", $_POST['comment_text']);
   $text_arr = explode(" ",$text_arr); for ($i=0;$i<count($text_arr);$i++) { trim($text_arr[$i]); $laenge = strlen($text_arr[$i]); if ($laenge > $comment_word_maxlength) {
    $errors[] = "the word \"".htmlentities(stripslashes(substr($text_arr[$i],0,$comment_word_maxlength)))."...\" is too long"; } }

   // Schauen, ob doppelt:
   $data = file($comment_file);
   $row_count = count($data);
   for ($row = 0; $row < $row_count; $row++)
     {
      $parts = explode("|", $data[$row]);
      if ($parts[3] == $_POST['comment_id'] && urldecode($parts[4]) == trim($_POST['name']) && trim(urldecode($parts[6])) == trim($_POST['comment_text'])) { $double_entry = true; break; }
     }

   // wenn keine Fehler, Kommentar speichern:
   if (empty($errors) && empty($double_entry))
    {
      $comment_text = urlencode(trim($_POST['comment_text']));
      $name = urlencode(trim($_POST['name']));
      $email_hp = trim($_POST['email_hp']);
      if (substr($email_hp,0,7) == "http://") $email_hp = substr($email_hp,7);
      $email_hp = urlencode(base64_encode($email_hp));

      $uniqid = uniqid("");
      if ($comment_add_comments == "top")
      {
       $data = file($comment_file);
       $c = count($data);
       $datei = fopen($comment_file, 'w+');
       flock($datei, 2);
       fwrite($datei, $uniqid."|".time()."|".$_SERVER["REMOTE_ADDR"]."|".$_POST['comment_id']."|".$name."|".$email_hp."|".$comment_text."\n");
       for ($i = 0; $i < $c; $i++) { fwrite($datei, trim($data[$i])."\n"); }
       flock($datei, 3);
       fclose($datei);
      }
      else
      {
       $datei = fopen($comment_file, "a");
       flock($datei, 2);
       fwrite($datei, $uniqid."|".time()."|".$_SERVER["REMOTE_ADDR"]."|".$_POST['comment_id']."|".$name."|".$email_hp."|".$comment_text."\n");
       flock($datei, 3);
       fclose($datei);
      }

     // E-Mail benachrichtigung an Admin:
     if (isset($comment_admin_email) && $comment_admin_email !="")
      {
       if (isset($comment_popup)) { $acid1="?comment_id=".$comment_id."&ampcomment_popup=true"; $acid2="&comment_id=".$comment_id."&comment_popup=true"; } else { $acid1 = ""; $acid2 = ""; }
       $sender_name = trim($_POST['name']);
       if ($sender_name=="") $sender_name = $comment_anonym;
       if (preg_match("/^[^@]+@.+\.\D{2,5}$/", $email_hp)) $sender_email = urldecode($email_hp); else $sender_email = "no@email.xx";
       $emailbody = "Comment: ".$_POST['comment_id']." by ".$sender_name.":\n\n".$_POST['comment_text'];
       $emailbody .= "\n\n\nLink to the comment:\nhttp://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].$acid1."#comments";
       $header= "From: ".$sender_name." <".$sender_email.">\n";
       $header .= "X-Mailer: PHP/" . phpversion(). "\n";
       $header .= "X-Sender-IP: ".$_SERVER["REMOTE_ADDR"]."\n";
       $header .= "Content-Type: text/plain";
       @mail($comment_admin_email, "Comment: ".$_POST['comment_id'], $emailbody, $header);
      }
     }
   }

  // Kommentar-Datei einlesen:
  $data = file($comment_file);
  $comment_total_entries = count($data);

  // zählen, wieviele Einträge es gibt:
  $comment_count = 0;
  for ($i = 0; $i < $comment_total_entries; $i++)
  {
   $parts = explode("|", $data[$i]);
   if ($parts[3] == $comment_id) { $comment_count++; }
  }

  // Header für Popup-Fenster:
  if (isset($comment_popup))
   {
    ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><head><title>Comments</title><meta http-equiv="content-type" content="text/html; charset=iso-8859-1" /><style type="text/css"><!-- body { color: #000000; background: #ffffff; margin: 15px; padding: 0px; font-family: verdana, arial, sans-serif; font-size: 13px; } p { font-family: verdana, arial, sans-serif; font-size: 13px; } h1 { font-family: verdana, arial, sans-serif; font-size: 18px; font-weight: bold; } --></style></head><body>
<h1 id="comments">You have answered: </h1>
<?php
   }

  // Kommentare anzeigen:
  $comment_k = 0;
  $comment_a = 0;
  for ($i = 0; $i < $comment_total_entries; $i++)
    {
     $parts = explode("|", $data[$i]);
     if ($parts[3] == $comment_id)
      {
       $comment_k++;
       if ($parts[4] != "") $name = htmlentities(stripslashes(urldecode($parts[4]))); else $name = $comment_anonym;
       if ($parts[5] != "")
        {
         $email_hp = htmlentities(stripslashes(base64_decode(urldecode($parts[5]))));
         if (preg_match("/^[^@]+@.+\.\D{2,5}$/", $email_hp))
          {
           $email_parts = explode("@", $email_hp);
           $email_name = $email_parts[0];
           $email_domain_tld = $email_parts[1];
           $domain_parts = explode(".", $email_domain_tld);
           $email_domain = "";
           for ($x = 0; $x < count($domain_parts)-1; $x++)
            {
             $email_domain .= $domain_parts[$x].".";
            }
           $email_tld = $domain_parts[$x];
           $name = "<script type=\"text/javascript\">
           <!--
           document.write('<a href=\"mailto:'); document.write('".$email_name."'); document.write('@'); document.write('".$email_domain."'); document.write('".$email_tld."'); document.write('\" title=\"E-mail to ".$name."\">');
           //-->
           </script>".$name."<script type=\"text/javascript\">
           <!--
           document.write('</a>');
           //-->
           </script>";
          }
         else
          {
           if (isset($comment_popup)) $name = '<a href="http://'.$email_hp.'" title="Homepage: '.$email_hp.'" target="_blank">'.$name.'</a>';
           else $name = '<a href="http://'.$email_hp.'" title="Homepage: '.$email_hp.'">'.$name.'</a>';
          }
        }
       $kommentar = htmlentities(stripslashes(urldecode($parts[6])));
       $kommentar = str_replace("\n", " - ", trim($kommentar));
       if (isset($comment_autolink) && $comment_autolink==true) $kommentar = comment_make_link($kommentar);
       $zeit = $parts[1];

       if ($comment_add_comments == "top")
        {
         if ($comment_page=="show_all" || ($comment_k>($comment_page-1)*$comment_comments_per_page && $comment_k<$comment_page*$comment_comments_per_page+1)) { ?><p style="margin:0px 0px 5px 0px;"><b><?php echo $name; ?>:</b>&nbsp;<?php echo $kommentar; ?><br /><span style="font-size: 10px; color: #808080;">(<?php echo strftime($comment_time_format, $parts[1]); ?>)</span></p><?php $comment_a++; }
        }
       else
        {
         if ($comment_page=="show_all" || ($comment_k > ( ($comment_count-$comment_comments_per_page) - ( ($comment_page-1) * $comment_comments_per_page ) ) && $comment_k < (($comment_count-$comment_comments_per_page)-(($comment_page-1)*$comment_comments_per_page))+($comment_comments_per_page+1))) { ?><p style="margin:0px 0px 5px 0px;"><b><?php echo $name; ?>:</b>&nbsp;<?php echo $kommentar; ?><br /><span style="font-size: 10px; color: #808080;">(<?php echo strftime($comment_time_format, $parts[1]); ?>)</span></p><?php $comment_a++; }
        }
      }
    }

 if ($comment_k == 0) echo "<p><i>No answers yet.</i></p>";
 if ($comment_comments_per_page < $comment_count && $comment_page != "show_all") { ?><p style="margin:10px 0px 3px 0px; font-family: verdana, arial, sans-serif; font-size: 11px;"><?php echo $comment_a; ?> of <?php echo $comment_count; ?> comments (part <?php echo $comment_page; ?>).&nbsp;<?php
 if ($comment_comments_per_page < $comment_count && $comment_page > 1) { ?>[ <a href="<?php echo basename($_SERVER["PHP_SELF"]); ?>?comment_id=<?php echo $comment_id; ?>&amp;comment_page=<?php echo $comment_page-1; if (isset($comment_popup)) echo "&amp;comment_popup=true"; ?>#comments" title="Previous part">&laquo;</a> ] <?php }
 if ($comment_comments_per_page < $comment_count && $comment_page < (($comment_count/$comment_comments_per_page))) { ?>[ <a href="<?php echo basename($_SERVER["PHP_SELF"]); ?>?comment_id=<?php echo $comment_id; ?>&amp;comment_page=<?php echo $comment_page+1; if (isset($comment_popup)) echo "&amp;comment_popup=true"; ?>#comments" title="Next part">&raquo;</a> ] <?php }
 ?>
 [ <a href="<?php echo basename($_SERVER["PHP_SELF"]); ?>?comment_id=<?php echo $comment_id; ?>&amp;comment_page=show_all<?php if (isset($comment_popup)) echo "&amp;comment_popup=true"; ?>#comments" title="show all comments">*</a> ]</p><?php }
 if(isset($errors))
  {
   ?><p style="color:red; font-weight:bold;">Error:</p><ul><?php foreach($errors as $f) { ?><li><?php echo $f; ?></li><?php } ?></ul><?php
  }
 ?>
 <form method="post" action="<?php echo basename($_SERVER["PHP_SELF"]); ?>"><div>
 <?php if (isset($comment_popup)) { ?><input type="hidden" name="comment_popup" value="true" /><?php } ?>
 <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>" />
 <table style="margin-top: 10px;" border="0" cellpadding="1" cellspacing="0">
  <tr>
   <td colspan="3">
   <b>Your answer:</b><br />
   <textarea style="width: 400px;" name="comment_text" cols="45" rows="4"><?php if (isset($errors) && isset($_POST['comment_text'])) echo htmlentities(stripslashes($_POST['comment_text'])); ?></textarea><br />
   </td>
  </tr>
  <tr>
   <td style="font-family: verdana, arial, sans-serif; font-size: 11px; vertical-align: bottom;">Name:<br /><input type="text" name="name" value="<?php if (isset($errors) && isset($_POST['name'])) echo htmlentities(stripslashes($_POST['name'])); else echo ""; ?>" size="23" maxlength="25" /></td>
   <td style="font-family: verdana, arial, sans-serif; font-size: 11px; vertical-align: bottom;">E-mail:<br/>
     <input type="text" name="email_hp" value="<?php if (isset($errors) && isset($_POST['email_hp'])) echo htmlentities(stripslashes($_POST['email_hp'])); else echo ""; ?>" size="23" maxlength="60" /></td>
   <td style="font-family: verdana, arial, sans-serif; font-size: 11px; vertical-align: bottom;"><input type="submit" value="  OK  " /></td>
  </tr>
 </table>
 </div></form>
</body></html><?php
  }

// JavaScript für Popup und Kommentar-Link mit Anzeige, wieviel Kommentare vorhanden sind:
if (isset($_GET['get_comment_link']))
 {
  $data = file($comment_file);
  $comment_total_entries = count($data);
  // zählen, wieviele Einträge es gibt:
  $comment_count = 0;
  for ($i = 0; $i < $comment_total_entries; $i++)
  {
   $parts = explode("|", $data[$i]);
   if ($parts[3] == $_GET['get_comment_link']) $comment_count++;
  }
 if ($comment_count == 0) $link = "No comments";
 elseif ($comment_count == 1) $link = "1 comment";
 else $link = $comment_count . " comments";
 ?>function comment(id)
 {
 var page = "comment.php?comment_id=" + id + "&comment_popup=true";
 popwin = window.open(page,"","width=450,height=500,scrollbars")
 popwin.focus();
 }
 document.open();
 document.write("[ <a title=\"Read or write comments\" href=\"javascript:comment('<?php echo $_GET['get_comment_link']; ?>')\"><?php echo $link; ?></a> ]");
 document.close();
 <?php
 }
?>