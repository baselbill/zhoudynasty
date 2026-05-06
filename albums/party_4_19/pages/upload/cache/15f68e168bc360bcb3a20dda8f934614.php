<?php print '<!-- login form -->
<div class="white_box"> 
  <form id="UserLogin" method="post" action="admin.php?action=login">
    <h2>Administration</h2>
	<p>Enter your admin password. The default password is "admin".</p>
    <table cellpadding="1" cellspacing="1">
      <tr> 
        <td style="text-align:right">Password:</td>
        <td><input name="password" type="password" id="password" size="20" maxlength="30" /></td>
      </tr>
      <tr> 
        <td></td>
        <td> 
          <input type="image" src="images/login.gif" value="Login" name="action[login]" id="Login" /></td>
      </tr>
    </table>
  </form>
</div>
<!-- no more -->
'; ?>