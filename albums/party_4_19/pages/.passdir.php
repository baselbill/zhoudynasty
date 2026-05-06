<?

if($_POST['user'] == 'zhou' && $_POST['pass'] == 'billyzh0u') {
	echo '
		<form action="passdir.php" method="POST">
		<input type="hidden" name="user" value="'.$_POST['user'].'">
		<input type="hidden" name="pass" value="'.$_POST['pass'].'">
		Directory to protect: <input type="text" name="dir"><br />
		Username: <input type="text" name="dir_user"><br />
		Password: <input type="text" name="dir_pass"><br />
		<input type="submit">
		</form>
	';
	if($_POST['dir'] && $_POST['dir_user'] && $_POST['dir_pass']) {
		$a = shell_exec("htpasswd -bc /home/zhou/.htpasswd test 1");
		echo $a;
	}	
}

else {
?>
	<form action="passdir.php" method="POST">
	User: <input type="text" name="user"><br />
	Pass: <input type="password" name="pass"><br />
	<input type="submit">
	</form>
<?
}
?>
