<!-- settings-->
<div class="white_box">
{* go through settings array and print out default values *}
<form action="admin.php?action=settings&amp;what=settings" method="post">
<div class="big_div">

<div class="white" ><h1>Uploader Settings</h1>These are settings that tell the uploader where to put the files and how to link to them.</div>

<div class="gray">
<strong>Uploader URL</strong></br>
Enter the URL where a user can access this uploader. <br />Example: http://celerondude.com/uploads/<br />
<input type="text" name="uploader_url" id="uploader_url" size="70" value="{$uploader_url}"/>
</div>

<div class="white">
<strong>Admin email</strong></br>
Your email incase the users have any questions.<br />
<input type="text" name="admin_email" id="admin_email" size="70" value="{$admin_email|"admin@domain.com"}"/>
</div>

<div class="gray">
<strong>Incoming Directories</strong><br />
Enter the directories where the files will go.  They don't necessary have to be in one directories but
it would organized on your part.  Enter them in this format.<br />
<em>Folder name</em>, <em>absolute_path</em>, url<br />
Example1: Images, /public_html/images, http://my_domain.com/images<br />
Example2: Files, /public_html/my/files, http://my_domain.com/my/files<br />
Seperate them with a new line, the first line will be the default one.<br />
Also remember to CHMOD all incoming_directories to 0777, (at least 0666)
<textarea name="incoming_directories" id="incoming_directories" cols="96" rows="8">{$incoming_directories}</textarea>
<br />
Your absolute path based on information from $_SERVER: {$_SERVER.SCRIPT_FILENAME}
</div>

<div class="gray">
<strong>Password Required</strong><br />
This requires that a user enter a username and password before using the uploader.<br />
<input type="radio" name="password_required" id="password_required" value="1" {if $password_required}checked="checked"{/if}/> Yes
<input type="radio" name="password_required" id="password_required" value="0" {if !$password_required}checked="checked"{/if}/> No
</div>

<div class="white">
<strong>Registration</strong><br />
Enable or disable registration of new users.<br />
<input type="radio" name="allow_register" id="allow_register" value="1" {if $allow_register}checked="checked"{/if}/> Enable
<input type="radio" name="allow_register" id="allow_register" value="0" {if !$allow_register}checked="checked"{/if}/> Disable
</div>

<div class="gray">
<strong>Logging</strong><br />
This tells the uploader whether to log users or not.  Logging tells you
which user uploaded what and on which day.  If Password Required is set
to No, the name of the user will be "Unregistered User." In that case, you
should turn logging off.<br />
<input type="radio" name="logging" id="logging" value="1" {if $logging}checked="checked"{/if}/> Log
<input type="radio" name="logging" id="logging" value="0" {if !$logging}checked="checked"{/if}/> Don't Log
</div>

<div class="white">
<strong>User Registration Approval</strong><br />
This feature allows you to either auto approve newly registered users or have them wait
for you to manually approve them.<br />
<input type="radio" name="auto_approve" id="auto_approve" value="1" {if $auto_approve}checked="checked"{/if}/> Auto
<input type="radio" name="auto_approve" id="auto_approve" value="0" {if !$auto_approve}checked="checked"{/if}/> Manual
</div>

<div class="gray">
<strong>Additional Comments</strong><br />
Here you can leave addition comments.  You also have the option to show or not show these comments.
HTML will work.<br />
<textarea name="comments" id="comments" cols="70" rows="8">{$comments}</textarea>
</div>
<div class="gray" style="background-color: #99CCFF; margin-bottom: 0px;">
You can now save your settings now if you'd like.
<input type="Submit" value="Save" name="action[save]" />
</div>
</div>


<br /><br />


<div class="big_div">
<div class="white"><h1>Uploader Options</h1>These are options that determine how the uploader works and its appearance.</div>

<div class="gray"><strong>Max File Size</strong>
<br />The maximum file size that will be accepted.  Size in Kilobytes. Enter "0" (zero) for no limit.
<br />
<input type="text" name="max_file_size" id="max_file_size" size="3" value="{$max_file_size}"/>
</div>

<div class="white"><strong>Default Upload Fields</strong>
<br />This is not very important.  It basically tells the uploader how many input fields to display by default.  Three is
a good number.
<br />
<input type="text" name="default_upload_fields" id="default_upload_fields" size="3" value="{$default_upload_fields}"/>
</div>

<div class="gray"><strong>Max Upload Fields</strong>
<br />This is also not very important. Sometimes a user likes to enter 9999 to see what happens.  This basically stops that :)
Any number greater than this will be revert back to the default number set by the above setting.
<br />
<input type="text" name="max_upload_fields" id="max_upload_fields" size="3" value="{$max_upload_fields}"/>
</div>

<div class="white"><strong>Allowed File Types</strong>
<br />This sets the only file types that will be accepted.  Seperate each extension by a pipe symbol |. (Hold shift + \ key)
Example: .jpg|.zip|.doc|.txt<br />
Note: Don't enter leading OR trailing pips.  This is wrong |.jpg|.zip|
<br />
<input type="text" name="allowed_types" id="allowed_types" size="70" value="{$allowed_types}"/>
</div>

<div class="gray"><strong>Image only</strong>
<br />This setting tells the uploader to accept ONLY image types.  With this
setting enabled, any extension you entered in the "Allowed Extensions" field will be overridden.  The user can only upload
images. The recommended setting is No.
<br />
<input type="radio" name="image_only" id="image_only" value="1" {if $image_only}checked="checked"{/if}/> Yes
<input type="radio" name="image_only" id="image_only" value="0" {if !$image_only}checked="checked"{/if}/> No
</div>

<div class="white"><strong>Allow PHP files</strong>
<br />This tells the uploader to accept PHP files.  Users uploading PHP files can gain access to your server.  It
is recommended that you set this to No.
<br />
<input type="radio" name="allow_php" id="allow_php" value="1" {if $allow_php}checked="checked"{/if}/> Yes
<input type="radio" name="allow_php" id="allow_php" value="0" {if !$allow_php}checked="checked"{/if}/> No
</div>

<div class="gray"><strong>Allow Browsing</strong>
<br />This setting allows the user to browse uploaded files. The default setting is Yes.
<br />
<input type="radio" name="allow_browsing" id="allow_browsing" value="1" {if $allow_browsing}checked="checked"{/if}/> Yes
<input type="radio" name="allow_browsing" id="allow_browsing" value="0" {if !$allow_browsing}checked="checked"{/if}/> No
</div>

<div class="gray"><strong>Allow Overriding of Existing Files</strong>
<br />If the user uploads a file that already exists in the incoming folder, this settings decides whether the new
file should override the old one.  It is recommended that you set this to No.
<br />
<input type="radio" name="allow_override" id="allow_override" value="1" {if $allow_override}checked="checked"{/if}/> Yes
<input type="radio" name="allow_override" id="allow_override" value="0" {if !$allow_override}checked="checked"{/if}/> No
</div>

<div class="white"><strong>Show Comments</strong>
<br />Like the name says, this setting shows or hides the "Additional Comments" you entered in the above text box.
<br />
<input type="radio" name="show_comments" id="show_comments" value="1" {if $show_comments}checked="checked"{/if}/> Yes
<input type="radio" name="show_comments" id="show_comments" value="0" {if !$show_comments}checked="checked"{/if}/> No
</div>

<div class="gray"><strong>Show Rules</strong>
<br />This shows or hides the generated rules based on the settings you have set above.
<br />
<input type="radio" name="show_rules" id="show_rules" value="1" {if $show_rules}checked="checked"{/if}/> Yes
<input type="radio" name="show_rules" id="show_rules" value="0" {if !$show_rules}checked="checked"{/if}/> No
</div>

<div class="white" style="background-color: #99CCFF; margin-bottom: 0px;">
Save all your settings!
<input type="Submit" value="Save" name="action[save]" />
</div>

</div>

</form>
<!-- no more settings -->
