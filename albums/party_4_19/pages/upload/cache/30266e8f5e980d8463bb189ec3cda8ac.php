<?php print '<!-- user list -->
<div class="white_box"> 
  <h1>Registered users</h1>
  <form  id="delete" action="admin.php?action=users&amp;what=approve" method="post">
    <table style="width: 100%">
      <tr> 
        <td><a href="admin.php?action=users&amp;what=approve&amp;sortby=userid"><strong>ID</strong></a></td>
        <td><a href="admin.php?action=users&amp;what=approve&amp;sortby=username"><strong>Username</strong></a></td>
        <td><a href="admin.php?action=users&amp;what=approve&amp;sortby=email"><strong>Email</strong></a></td>
        <td><a href="admin.php?action=users&amp;what=approve&amp;sortby=extra"><strong>Extra 
          Info</strong></a></td>
        <td><a href="javascript:checkall(\'delete\', \'userid[]\')"><strong>Check 
          All</strong></a></td>
      </tr>
      ';for($i=0; $i<count($_vars['users']); $i++){print'      <tr style="background-color: ' . (($i & 1) ? 'white' : '#F4F4F4') . '"> 
        <td>'; print @$_vars['users'][$i]['userid'] . '</td>
        <td>'; print @$_vars['users'][$i]['username'] . '</td>
        <td>'; print @$_vars['users'][$i]['email'] . '</td>
        <td>'; print @$_vars['users'][$i]['extra'] . '</td>
        <td><input type="checkbox" id="userid[]" name="userid[]" value="'; print @$_vars['users'][$i]['userid'] . '" />
          Delete</td>
      </tr>
      ';}print' 
      <tr> 
        <td colspan="4"><input type="submit" name="action[delete]" value="Delete" style="background-color: red; color: white;" onclick="return confirm(\'Are you sure you want to delete the selected users?\');" /> 
        </td>
      </tr>
    </table>
  </form>
  <p><br />
    <a href="admin.php?action=users">Back to users option</a></p>
</div>
<!-- end user list -->
'; ?>