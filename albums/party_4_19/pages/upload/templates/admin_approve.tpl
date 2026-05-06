<!-- user list -->
<div class="white_box"> 
  <h1>Registered users</h1>
  <form  id="approve" action="admin.php?action=users&amp;what=approve" method="post">
  <table style="width: 100%">
    <tr> 
      <td><a href="admin.php?action=users&amp;what=approve&amp;sortby=userid"><strong>ID</strong></a></td>	
      <td><a href="admin.php?action=users&amp;what=approve&amp;sortby=username"><strong>Username</strong></a></td>
      <td><a href="admin.php?action=users&amp;what=approve&amp;sortby=email"><strong>Email</strong></a></td>
      <td><a href="admin.php?action=users&amp;what=approve&amp;sortby=extra"><strong>Extra Info</strong></a></td>
      <td><a href="javascript:checkall('approve', 'userid[]')"><strong>Check All</strong></a></td>	
    </tr>
    {loop name=i var=$users} 
    <tr style="background-color: {rowcolor:i|#F4F4F4|white}">  
      <td>{$users[i].userid}</td>
      <td>{$users[i].username}</td>
      <td>{$users[i].email}</td>
      <td>{$users[i].extra}</td>
      <td><input type="checkbox" id="userid[]" name="userid[]" value="{$users[i].userid}" /></td>
    </tr>
    {/loop} 
    <tr>
      <td colspan="4"><input type="submit" value="Approve" name="action[approve]" /></td>
    </tr>
  </table>
</form>
<p><br /><a href="admin.php?action=users">Back to users option</a></p>
</div>
<!-- end user list -->
