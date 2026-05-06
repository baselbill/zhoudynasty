<!-- user list -->
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
    {loop name=i var=$users} 
    <tr style="background-color: {rowcolor:i|#F4F4F4|white}">  
      <td>{$users[i].userid}</td>
      <td>{$users[i].username}</td>
      <td>{$users[i].email}</td>
      <td>{$users[i].extra}</td>
      <td>Closed<input type="radio" name="userid[{$users[i].userid}]" value="1" {if $users[i].closed}checked="checked"{/if} />
	  Open<input type="radio" name="userid[{$users[i].userid}]" value="0" {if !$users[i].closed}checked="checked"{/if} /></td>
    </tr>
    {/loop} 
    <tr>
      <td colspan="4"><br /><input type="submit" value="Submit" name="action[account]" /></td>
    </tr>
  </table>
</form>
<p><br /><a href="admin.php?action=users">Back to users option</a></p>
</div>
<!-- end user list -->
