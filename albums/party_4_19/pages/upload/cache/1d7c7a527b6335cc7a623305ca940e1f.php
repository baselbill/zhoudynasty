<?php print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Uploader</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
' . (isset($_vars['meta']) ? @$_vars['meta'] : '') . '
<link href="templates/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!-- //hide
function copy(id)
{
	if(!document.all)
	{
		alert(\'Sorry, only internet explorer supports this feature.\');
		return;
	}
	f = document.getElementById(id);
	obj = f.createTextRange();
	obj.select();
	obj.execCommand(\'copy\');
}
function increment(fieldName)
{
	document.getElementById(fieldName).value++; 
}
all_checked = true;
function checkall(formName, boxName) {
	for(i = 0; i < document.getElementById(formName).elements.length; i++)
	{
		var formElement = document.getElementById(formName).elements[i];
		if(formElement.type == \'checkbox\' && formElement.name == boxName && formElement.disabled == false)
		{
			formElement.checked = all_checked;
		}
	}	
all_checked = all_checked ? false : true;
}
// don\'t hide -->
</script>
</head>
<body> 
<div id="container"> 
  <div id="container_inside"> '; if( !@$_vars['noheader']) { print ' 
    <!-- This is the header/menu -->
    <div class="white_box"> 
      <table>
        <tr> 
          <td style="text-align: center; width: 50px"><a href="index.php" class="image"><img src="images/home.gif" alt="Go back to the main page" style="border: 0px"/><br />
            Home</a></td>
          <td style="text-align: center; width: 50px"><a href="index.php?action=browse" class="image"><img src="images/browse.gif" alt="Browse uploaded files" style="border: 0px"/><br />
            Browse</a></td>
          '; if( @$_vars['logged_in']) { print '
          <td style="text-align: center; width: 50px"><a href="index.php?action=changepassword" class="image"><img src="images/password.gif" alt="Change Password" style="border: 0px"/><br />
            Password</a></td>
          ';}print' '; if( @$_vars['logged_in']) { print '
          <td style="text-align: center; width: 50px"><a href="index.php?action=logout" class="image"><img src="images/logout.gif" alt="Log out of the uploader." style="border: 0px"/><br />
            Logout</a></td>
          ';}print' </tr>
      </table>
    </div>
    <br />
    <!-- End header/menu -->
    ';}print' ' . ($this->fetch('' . (isset($_vars['action']) ? @$_vars['action'] : 'no action specified') . '.tpl')).' 
    <!-- This is the footer -->
    <br />
    <div class="white_box" style="text-align:center;"> Uploader by <a href="http://celerondude.com">CeleronDude</a>. 
      <br />
      Processed in ' . (isset($_vars['runtime']) ? @$_vars['runtime'] : '???') . ' second.</div>
    <!-- End footer -->
  </div>
</div>
</body>
</html>
'; ?>