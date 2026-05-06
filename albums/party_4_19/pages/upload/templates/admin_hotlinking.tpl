<!-- hotlinking form -->
<div class="white_box">
<h1>Hotlinking protection</h1>
<form action="admin.php?action=settings&amp;what=hotlinking" method="post">
<p>Allow linking from these sites. A URL on every line.</p>
<textarea name="allowed_urls" cols="90" rows="12">{$allowed_urls}</textarea><br />
<p>Redirect denied requests to:</p>
<input type="text" value="{$redirect_url}" name="redirect_url" size="80" /><br />
File types to protect, seperate them with the pipe | symbold:<br />
<input type="text" value="{$file_types}" name="file_types" size="80" /><br />
<input type="checkbox" name="disable" value="1" />Disable Hotlinking protection. Check here and the .htaccess file
in the incoming directory will be deleted.<br /><br />
<input type="submit" value="Save" name="action[save]" />
</form>
</div>
<!-- end hotlinking -->