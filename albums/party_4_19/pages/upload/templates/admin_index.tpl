<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Uploader</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
{$meta|""}
<link href="templates/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!-- //hide
function increment(fieldName)
{
	document.getElementById(fieldName).value++; 
}
all_checked = true;
function checkall(formName, boxName) {
	for(i = 0; i < document.getElementById(formName).elements.length; i++)
	{
		var formElement = document.getElementById(formName).elements[i];
		if(formElement.type == 'checkbox' && formElement.name == boxName && formElement.disabled == false)
		{
			formElement.checked = all_checked;
		}
	}	
all_checked = all_checked ? false : true;
}
// don't hide -->
</script>
</head>
<body> 
<div id="container"> 
  <div id="container_inside"> {if !$noheader} 
    <!-- This is the header/menu -->
    <div class="white_box"> 
      <table>
        <tr> 
          <td style="text-align: center; width: 50px"><a href="admin.php" class="image"><img src="images/stats.gif" alt="Stats" style="border: 0px"/><br />
            Stats</a></td>
          <td style="text-align: center; width: 50px"><a href="admin.php?action=settings" class="image"><img src="images/settings.gif" alt="Edit Settings" style="border: 0px"/><br />
            Settings</a></td>
          <td style="text-align: center; width: 50px"><a href="admin.php?action=delete" class="image"><img src="images/delete.gif" alt="Delete Files" style="border: 0px"/><br />
            Delete</a></td>
          <td style="text-align: center; width: 50px"><a href="admin.php?action=changepassword" class="image"><img src="images/password.gif" alt="Change Password" style="border: 0px"/><br />
            Password</a></td>
          <td style="text-align: center; width: 50px"><a href="admin.php?action=users" class="image"><img src="images/users.gif" alt="Manage users" style="border: 0px"/><br />
            Users</a></td>
          <td style="text-align: center; width: 50px"><a href="admin.php?action=logout" class="image"><img src="images/logout.gif" alt="Logout" style="border: 0px"/><br />
            Logout</a></td>
          <td style="text-align: center; width: 50px"><a href="index.php" class="image"><img src="images/home.gif" alt="Back to the uploader" style="border: 0px"/><br />
            Uploader</a></td>
        </tr>
      </table>
    </div>
    <br />
    <!-- End header/menu -->
    {/if} {include "admin_{$action|"no action specified"}.tpl"} 
    <!-- This is the footer -->
    <br />
    <div class="white_box" style="text-align:center;"> Uploader by <a href="http://celerondude.com">CeleronDude</a>. 
      <br />
      Processed in {$runtime|"???"} second.</div>
    <!-- End footer -->
  </div>
</div>
</body>
</html>
