<!-- login form -->
<div class="white_box"> 
  <form id="UserLogin" method="post" action="index.php?action=login">
    <div> 
      <h2>Login now</h2>
      You must have an account to use be able to use this uploader. Please <a href="index.php?action=register" title="Register for an account">register</a> 
      if you don't have one. </a> <br />
      <br />
      <table cellpadding="1" cellspacing="1">
        <tr> 
          <td style="text-align:right">Username:</td>
          <td><input name="username" type="text" id="username" size="20" maxlength="30" /></td>
        </tr>
        <tr> 
          <td style="text-align:right">Password:</td>
          <td><input name="password" type="password" id="password" size="20" maxlength="30" /></td>
        <tr>
          <td></td>
          <td><a href="index.php?action=lostpassword" title="Get a new password">Forgot 
            your password?</a></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <input type="image" src="images/login.gif" value="Login" name="login" id="Login" /></td>
        </tr>
      </table>
    </div>
  </form>
</div>
<!-- no more -->
