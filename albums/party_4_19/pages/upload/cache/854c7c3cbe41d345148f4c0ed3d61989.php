<?php print '<!-- The registration form -->
<div class="white_box"> 
  <form id="Register" action="index.php?action=register" method="post">
    <div> 
      <h1>Name and contact info</h1>
      <table style="width: 100%">
        <tr style="background-color: #F5F5F5"> 
          <td style="padding: 5px"><strong>Username:</strong><br />
            3-30 characters</td>
          <td><input type="text" name="username" id="username" size="25" maxlength="30" /></td>
        <tr> 
          <td style="padding: 5px"><strong>Password:</strong><br />
            Cannot be empty 4-30 characters</td>
          <td><input type="password" name="password1" size="25" maxlength="20" /></td>
        </tr>
        <tr style="background-color: #F5F5F5"> 
          <td style="padding: 5px"><strong>Confirm Password:</strong><br />
            Same as above</td>
          <td><input type="password" name="password2" size="25" maxlength="20" /></tr></tr>
        <tr> 
          <td style="padding: 5px"><strong>Email address, must be valid</strong>.</td>
          <td><input type="text" name="email" id="email" size="35" maxlength="200" /></td>
        </tr>
        <tr style="background-color: #F5F5F5"> 
          <td style="padding: 5px"><strong>Additional info, optional.</strong></td>
          <td><textarea name="info" cols="40" rows="3" /></textarea></td>
        </tr>
        <tr> 
          <td></td>
          <td><br />
            <input type="image" src="images/submit.gif" name="action[register]" /></td>
        </tr>
      </table>
    </div>
  </form>
</div>
<!-- End of the registration form -->
'; ?>