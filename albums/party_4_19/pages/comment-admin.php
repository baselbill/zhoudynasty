<?php
// Admin password:
$pw = "zh23ou09";

// Comment file:
$comment_file = "comments.dat";

$comment_comments_per_page = 10;
$comment_time_format = "%d.%m.%Y, %H:%M Uhr";
$comment_anonym = "Anonym";
if (isset($_GET['comment_page'])) $comment_page = $_GET['comment_page']; else $comment_page = 1;
if (isset($_POST['category'])) $category = $_POST['category'];
if (isset($_GET['category'])) $category = $_GET['category'];

session_start();
// Login:
if (!isset($_SESSION['admin']) || isset($_POST['login']))
 {
  if ($_POST['ppw'] == $pw)
   {
    $_SESSION['admin'] = true;
    header("location: ".basename($_SERVER['PHP_SELF']));
   }
  else die("Password wrong!");
 }

 if (isset($_GET['logout']))
 {
  session_destroy();
  header("location: ".basename($_SERVER['PHP_SELF']));
 }

// Löschen:
if (isset($_POST['delete_id']) && isset($_SESSION["admin"]))
 {
  $data = file($comment_file);
  $row_count = count($data);
  $fp = fopen($comment_file, 'w+');
  flock($fp, 2);
  for ($row = 0; $row < $row_count; $row++)
   {
    $parts = explode("|", $data[$row]);
    if (!in_array($parts[0], $_POST['delete_id'])) { fwrite($fp, trim($data[$row])."\n"); }
   }
  flock($fp, 3);
  fclose($fp);
   header("location: ".basename($_SERVER["PHP_SELF"])."?category=".$_POST['category']."&comment_page=".$_POST['comment_page']);
 }

// Editieren:
if (isset($_POST['edit_id']) && isset($_SESSION["admin"]))
 {
  $data = file($comment_file);
  $row_count = count($data);
  $fp = fopen($comment_file, "w+");
  flock($fp, 2);
  for ($row = 0; $row < $row_count; $row++)
   {
    $parts = explode("|", $data[$row]);
    if ($parts[0] == $_POST['edit_id']) { fwrite($fp, $parts[0]."|".$parts[1]."|".$parts[2]."|".$parts[3]."|".urlencode($_POST['name'])."|".$email_hp = urlencode(base64_encode($_POST['email_hp']))."|".urlencode($_POST['comment_text'])."\n"); }
    else { fwrite($fp, trim($data[$row])."\n"); }
   }
  flock($fp, 3);
  fclose($fp);
  header("location: ".basename($_SERVER["PHP_SELF"])."?category=".$_POST['category']."&comment_page=".$_POST['comment_page']);
 }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>my little commet script - admin area</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
<!--
body             { color: #000000; background: #ffffff; margin: 20px; padding: 0px; font-family: verdana, arial, sans-serif; font-size: 13px;}
p                { font-family: verdana, arial, sans-serif; font-size: 13px; }
h1               { font-family: verdana, arial, sans-serif; font-size: 18px; font-weight: bold; }
h2               { margin-top: 30px; font-family: verdana, arial, sans-serif; font-size: 18px; font-weight: bold; }
.comments-th     { padding: 5px; border: 1px solid #aaa; font-weight: bold; vertical-align: top; text-align: left; }
.comments-td     { padding: 5px; border: 1px solid #aaa; vertical-align: top; text-align: left; }
.submit-td       { padding: 5px; vertical-align: top; }
-->
</style>
<script language="javascript">
<!--
 function checkall()
 {
  if(document.commentform.check_all)
   {
    var c = document.commentform.check_all.checked;
   }
  for (var i=0;i<document.commentform.elements.length;i++)
   {
    var e = document.commentform.elements[i];
    if(e.name != 'check_all') e.checked = c;
   }
 }
//-->
</script>
</head>
<body>
<h1>my little commet script - admin area<?php if (isset($_SESSION['admin'])) { ?> <span style="font-size: 11px; font-weight: normal;">[ <a href="<?php echo basename($_SERVER['PHP_SELF']); ?>?logout">log out</a> ]</span><?php } ?>
</h1>
<?php
if (!isset($_SESSION['admin']) && empty($_POST['login']))
 {
  ?><form action="<?php echo basename($_SERVER['PHP_SELF']); ?>" method="post">
  Password:<br />
  <input type="password" name="ppw" value="" size="" maxlength="" />
  <input type="submit" name="login" value="OK">
  </form><?php
 }

elseif (isset($_SESSION['admin']) && empty($_GET['edit']))
 {
  $categories[] = "";
  $data = file($comment_file);
  $row_count = count($data);
  for ($row = 0; $row < $row_count; $row++)
   {
    $parts = explode("|", $data[$row]);
    if (!in_array($parts[3], $categories)) { $categories[] = $parts[3]; }
   }

  if ($row_count > 0)
   {
    ?><div style="height: 35px; float: left;">
    <form style="float: left;" method="post" action="<?php echo basename($_SERVER['PHP_SELF']); ?>">
    <select size="1" name="category" onchange="this.form.submit();">
    <option value="">choose:</option>
    <?php
    sort($categories);
    for ($x=1; $x<count($categories); $x++)
     {
      if (isset($category) && $categories[$x]==$category) echo ('<option value="'.$categories[$x].'" selected="selected">'.$categories[$x].'</option>');
      else echo ('<option value="'.$categories[$x].'">'. $categories[$x].'</option>');
     }
     ?></select>
     </form></div>
     <?php
     if (isset($category) && $category != "")
      {
       // Kommentar-Datei einlesen:
       $data = file($comment_file);
       $comment_total_entries = count($data);

       // zählen, wieviele Einträge es gibt:
       $comment_count = 0;
       for ($i = 0; $i < $comment_total_entries; $i++)
        {
         $parts = explode("|", $data[$i]);
         if ($parts[3] == $category) $comment_count++;
        }
       ?><div style="height: 35px;"><p style="text-align: right;">
       <?php
       if ($comment_count > 0)
        {
         if ($comment_comments_per_page < $comment_count && $comment_page != "show_all") { ?>Part <?php echo $comment_page; ?> of <?php echo $comment_count; ?> comments.&nbsp;<?php
         if ($comment_comments_per_page < $comment_count && $comment_page > 1) { ?>[ <a href="<?php echo basename($_SERVER["PHP_SELF"]); ?>?category=<?php echo $category; ?>&amp;comment_page=<?php echo $comment_page-1; ?>" title="Previous part">&laquo;</a> ] <?php }
         if ($comment_comments_per_page < $comment_count && $comment_page < (($comment_count/$comment_comments_per_page))) { ?>[ <a href="<?php echo basename($_SERVER["PHP_SELF"]); ?>?category=<?php echo $category; ?>&amp;comment_page=<?php echo $comment_page+1; ?>" title="next Part">&raquo;</a> ] <?php }
         ?> [ <a href="<?php echo basename($_SERVER["PHP_SELF"]); ?>?category=<?php echo $category; ?>&amp;comment_page=show_all" title="show all comments">all</a> ]<?php }
         else echo "&nbsp;";
        }
       ?></p></div><?php
       if ($comment_count == 0) echo "&nbsp;";
       else
       {
        ?><form name="commentform" method="post" action="<?php echo basename($_SERVER['PHP_SELF']); ?>">
  <input type="hidden" name="category" value="<?php echo $category; ?>" />
  <input type="hidden" name="comment_page" value="<?php echo $comment_page; ?>" />
  <table style="border-collapse: collapse;" border="0" cellpadding="0" cellspacing="0">
  <tr>

  <th class="comments-th">Date</th>
  <th class="comments-th">Name</th>
  <th class="comments-th">Comment</th>
  <th class="comments-th">edit</th>
  <th class="comments-th">delete</th>
  </tr>
  <?php
  // show comments:
  $comment_k = 0;
  $comment_a = 0;
  for ($i = 0; $i < $comment_total_entries; $i++)
    {
     $parts = explode("|", $data[$i]);
     if ($parts[3] == $category)
      {
       $comment_k++;
       if ($parts[4] != "") $name = htmlentities(stripslashes(urldecode($parts[4]))); else $name = $comment_anonym;
       if ($parts[5] != "")
        {
         $email_hp = htmlentities(stripslashes(base64_decode(urldecode($parts[5]))));
         if (preg_match("/^[^@]+@.+\.\D{2,5}$/", $email_hp)) $name = '<a href="mailto:'.$email_hp.'" title="E-mail: '.$email_hp.'">'.$name.'</a>';
         else $name = '<a href="http://'.$email_hp.'" title="Homepage: '.$email_hp.'">'.$name.'</a>';
        }
       $kommentar = htmlentities(stripslashes(urldecode($parts[6])));
       #$kommentar = str_replace("\n", " - ", trim($kommentar));
       if (isset($comment_autolink) && $comment_autolink==true) $kommentar = comment_make_link($kommentar);
       $zeit = $parts[1];

       /*if ($comment_add_comments == "top")
        {
         if ($comment_page=="show_all" || ($comment_k>($comment_page-1)*$comment_comments_per_page && $comment_k<$comment_page*$comment_comments_per_page+1)) { ?><p style="margin:0px 0px 5px 0px;"><b><?php echo $name; ?>:</b>&nbsp;<?php echo $kommentar; ?><br /><span style="font-size: 10px; color: #808080;">(<?php echo strftime($comment_time_format, $parts[1]); ?>)</span><?php if (isset($_COOKIE['comment_cookie_pw']) && $_COOKIE['comment_cookie_pw'] == $comment_pw) { ?><span style="font-size: 11px;">&nbsp;&nbsp;[ <a href="<?php echo basename($_SERVER["PHP_SELF"]); ?>?comment_delete=<?php echo $parts[0]; if (isset($comment_popup)) echo "&amp;comment_popup=true"; ?>">löschen</a> ]</span><?php } ?></p><?php $comment_a++; }
        }
       else
        {  */

       if ($comment_page=="show_all" || ($comment_k > ( ($comment_count-$comment_comments_per_page) - ( ($comment_page-1) * $comment_comments_per_page ) ) && $comment_k < (($comment_count-$comment_comments_per_page)-(($comment_page-1)*$comment_comments_per_page))+($comment_comments_per_page+1))) { ?><tr><td class="comments-td"><?php echo strftime($comment_time_format, $parts[1]); ?></td><td class="comments-td"><?php echo $name; ?></td><td class="comments-td"><?php echo $kommentar; ?></td><td class="comments-td"><a href="<?php echo basename($_SERVER['PHP_SELF'])."?edit=" . $parts[0] ."&amp;category=".$category ."&amp;comment_page=".$comment_page; ?>">edit</a></td><td class="comments-td"><input type="checkbox" name="delete_id[]" value="<?php echo $parts[0]; ?>"></td></tr><?php $comment_a++; }

      }

    }

 ?><tr><td colspan="4"></td><td class="submit-td"><input name="check_all" type="checkbox" value="check all" onclick="checkall();" /><span style="font-size: 11px;">all</span><br /><input style="font-weight: bold; color: red;" type="submit" name="delete_comments" value="delete" /></td></tr>
 </table>
 </form><?php
  }
 }
}
else echo "<i>No comments yet.</i>";
}

elseif (isset($_SESSION['admin']) && isset($_GET['edit']))
 {
  $data = file($comment_file);
  $row_count = count($data);
  for ($row = 0; $row < $row_count; $row++)
   {
    $parts = explode("|", $data[$row]);
    if ($parts[0] == $_GET['edit'])
     {
      $name = urldecode($parts[4]);
      $email_hp = base64_decode(urldecode($parts[5]));
      $comment_text = urldecode($parts[6]);
      break;
     }
   }

  ?>[ <a href="<?php echo basename($_SERVER["PHP_SELF"]); ?>?category=<?php echo $category; ?>&amp;comment_page=<?php echo $comment_page; ?>">back</a> ]
  <form method="post" action="<?php echo basename($_SERVER["PHP_SELF"]); ?>"><div>
  <input type="hidden" name="edit_id" value="<?php echo $_GET['edit']; ?>" />
  <input type="hidden" name="category" value="<?php echo $category; ?>" />
  <input type="hidden" name="comment_page" value="<?php echo $comment_page; ?>" />
  <table style="margin-top: 10px;" border="0" cellpadding="1" cellspacing="0">
  <tr>
   <td colspan="3">
   <b>Edit comment:</b><br />
   <textarea style="width: 400px;" name="comment_text" cols="45" rows="10"><?php if (isset($comment_text)) echo htmlentities(stripslashes($comment_text)); ?></textarea><br />
   </td>
  </tr>
  <tr>
   <td style="font-family: verdana, arial, sans-serif; font-size: 11px; vertical-align: bottom;">Name:<br /><input type="text" name="name" value="<?php if (isset($name)) echo htmlentities(stripslashes($name)); else echo ""; ?>" size="23" maxlength="25" /></td>
   <td style="font-family: verdana, arial, sans-serif; font-size: 11px; vertical-align: bottom;">E-mail or Homepage:<br/><input type="text" name="email_hp" value="<?php if (isset($email_hp)) echo htmlentities(stripslashes($email_hp)); else echo ""; ?>" size="23" maxlength="60" /></td>
   <td style="font-family: verdana, arial, sans-serif; font-size: 11px; vertical-align: bottom;"><input type="submit" name="edit_submitted" value="  OK  " /></td>
  </tr>
  </table>
  </div></form><?php
 }
else echo "<i>No comments.</i>";

?>
</body>
</html>