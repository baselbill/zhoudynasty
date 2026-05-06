<!-- user list -->
<div class="white_box"> 
  <h1>Registered users</h1>
  <table style="width: 100%">
    <tr> 
      <td><a href="admin.php?action=users&amp;what=view&amp;sortby=userid"><strong>ID</strong></a></td>	
      <td><a href="admin.php?action=users&amp;what=view&amp;sortby=username"><strong>Username</strong></a></td>
      <td><a href="admin.php?action=users&amp;what=view&amp;sortby=email"><strong>Email</strong></a></td>
      <td><a href="admin.php?action=users&amp;what=view&amp;sortby=extra"><strong>Extra Info</strong></a></td>
      <td><a href="admin.php?action=users&amp;what=view&amp;sortby=approved"><strong>Apprvd</strong></a></td>
      <td><a href="admin.php?action=users&amp;what=view&amp;sortby=closed"><strong>Acct Closed</strong></a></td>
    </tr>
    {loop name=i var=$users} 
    <tr style="background-color: {rowcolor:i|#F4F4F4|white}">  
      <td>{$users[i].userid}</td>
      <td>{$users[i].username}</td>
      <td>{$users[i].email}</td>
      <td>{$users[i].extra}</td>
      <td>{$users[i].approved|yes_or_no}</td>     
      <td>{$users[i].closed|yes_or_no}</td>
    </tr>
    {/loop} 
  </table>
<p><br /><a href="admin.php?action=users">Back to users option</a></p>
</div>
<!-- end user list -->
