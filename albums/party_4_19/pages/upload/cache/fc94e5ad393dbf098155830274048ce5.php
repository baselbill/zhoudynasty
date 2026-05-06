<?php print '<!-- user list -->
<div class="white_box"> 
  <h1>Account management</h1>
  <form  id="account" action="admin.php?action=users&amp;what=account" method="post">
  <table style="width: 100%">
    <tr> 
      <td><a href="admin.php?action=users&amp;what=account&amp;sortby=userid"><strong>ID</strong></a></td>	
      <td><a href="admin.php?action=users&amp;what=account&amp;sortby=username"><strong>Username</strong></a></td>
      <td><a href="admin.php?action=users&amp;what=account&amp;sortby=email"><strong>Email</strong></a></td>
      <td><a href="admin.php?action=users&amp;what=account&amp;sortby=extra"><strong>Extra Info</strong></a></td>
      <td><a href="admin.php?action=users&amp;what=account&amp;sortby=closed"><strong>Status</strong></a></td>	
    </tr>
    ';for($i=0; $i<count($_vars['users']); $i++){print'    <tr style="background-color: ' . (($i & 1) ? 'white' : '#F4F4F4') . '">  
      <td>'; print @$_vars['users'][$i]['userid'] . '</td>
      <td>'; print @$_vars['users'][$i]['username'] . '</td>
      <td>'; print @$_vars['users'][$i]['email'] . '</td>
      <td>'; print @$_vars['users'][$i]['extra'] . '</td>
      <td>Closed<input type="radio" name="userid['; print @$_vars['users'][$i]['userid'] . ']" value="1" '; if( @$_vars['users'][$i]['closed']) { print 'checked="checked"';}print' />
	  Open<input type="radio" name="userid['; print @$_vars['users'][$i]['userid'] . ']" value="0" '; if( !@$_vars['users'][$i]['closed']) { print 'checked="checked"';}print' /></td>
    </tr>
    ';}print' 
    <tr>
      <td colspan="4"><br /><input type="submit" value="Submit" name="action[account]" /></td>
    </tr>
  </table>
</form>
<p><br /><a href="admin.php?action=users">Back to users option</a></p>
</div>
<!-- end user list -->
'; ?>