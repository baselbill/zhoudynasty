<?php print '<!-- The file list -->
<div class="white_box">
<form id="files" method="post" action="admin.php?action=delete">
<h2>You are now browsing in '; print @$_vars['browse_in'] . '</h2>
<strong>Browse others:</strong>
';for($i=0; $i<count($_vars['incoming_directories']); $i++){print'<a href="admin.php?action=delete&amp;in='; print @$_vars['incoming_directories'][$i] . '">'; print @$_vars['incoming_directories'][$i] . '</a> 
';}print'
<br /><br />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="border-bottom: 1px #CC6600 solid;"><a class="orange" title="Sort this column." href="admin.php?action=delete&amp;dir='; print @$_vars['current_dir'] . '&amp;in='; print @$_vars['browse_in'] . '&amp;sortby=name&amp;order='; if( @$_vars['order'] == 'asc') { print 'dsc';}else{print'asc';}print'">File Name</a>'; if( @$_vars['sortby'] == 'name') { print '  <img src="images/'; if( @$_vars['order'] == 'asc') { print 'up.gif';}else{print'down.gif';}print'" alt="" />';}print'</td>
    <td style="border-bottom: 1px #CC6600 solid;"><a class="orange" title="Sort this column." href="admin.php?action=delete&amp;dir='; print @$_vars['current_dir'] . '&amp;in='; print @$_vars['browse_in'] . '&amp;sortby=size&amp;order='; if( @$_vars['order'] == 'asc') { print 'dsc';}else{print'asc';}print'">Size</a>'; if( @$_vars['sortby'] == 'size') { print '  <img src="images/'; if( @$_vars['order'] == 'asc') { print 'up.gif';}else{print'down.gif';}print'" alt="" />';}print'</td>
    <td style="border-bottom: 1px #CC6600 solid;"><a class="orange" title="Sort this column." href="admin.php?action=delete&amp;dir='; print @$_vars['current_dir'] . '&amp;in='; print @$_vars['browse_in'] . '&amp;sortby=time&amp;order='; if( @$_vars['order'] == 'asc') { print 'dsc';}else{print'asc';}print'">Uploaded</a>'; if( @$_vars['sortby'] == 'time') { print '  <img src="images/'; if( @$_vars['order'] == 'asc') { print 'up.gif';}else{print'down.gif';}print'" alt="" />';}print'</td>
    <td style="border-bottom: 1px #CC6600 solid;"><a class="orange" title="Sort this column." href="admin.php?action=delete&amp;dir='; print @$_vars['current_dir'] . '&amp;in='; print @$_vars['browse_in'] . '&amp;sortby=type&amp;order='; if( @$_vars['order'] == 'asc') { print 'dsc';}else{print'asc';}print'">Type</a>'; if( @$_vars['sortby'] == 'type') { print '  <img src="images/'; if( @$_vars['order'] == 'asc') { print 'up.gif';}else{print'down.gif';}print'" alt="" />';}print'</td>
    <td style="border-bottom: 1px #CC6600 solid;"><a class="orange" title="Check all items in this column." href="javascript:checkall(\'files\', \'selected[]\')">Delete</a></td>
  </tr>
  
  ';for($i=0; $i<count($_vars['files']); $i++){print'  <tr style="background-color:white;">
    <td style="border-bottom: #F0F0F0 1px solid;"><a href="'; print @$_vars['files'][$i]['url'] . '">'; print @$_vars['files'][$i]['name'] . '</a></td>
    <td style="border-bottom: #F0F0F0 1px solid;">'; print @$_vars['files'][$i]['size'] . '</td>
    <td style="border-bottom: #F0F0F0 1px solid;">'; print @$_vars['files'][$i]['time'] . '</td>
    <td style="border-bottom: #F0F0F0 1px solid;">'; print @$_vars['files'][$i]['type'] . '</td>
    <td style="border-bottom: #F0F0F0 1px solid;"><div style="text-align:center"><input id="selected[]" name="selected[]" type="checkbox" value="'; print @$_vars['files'][$i]['name'] . '" '; if( @$_vars['files'][$i]['type'] == 'dir') { print 'disabled="disabled"';}print'/></div></td>
  </tr>  
  ';}print'
  <tr>
    <td style="border-top: 1px #CC6600 solid;">'; print @$_vars['total_files'] . ' file(s)</td>
    <td style="border-top: 1px #CC6600 solid;">'; print @$_vars['total_size'] . '</td>
    <td style="border-top: 1px #CC6600 solid;">&nbsp;</td>
    <td style="border-top: 1px #CC6600 solid;">&nbsp;<input type="hidden" name="browse_in" id="browse_in" value="'; print @$_vars['browse_in'] . '" /><input type="hidden" name="dir" id="dir" value="'; print @$_vars['current_dir'] . '" /></td>
    <td style="border-top: 1px #CC6600 solid;">&nbsp;</td>
  </tr>
</table>
<br />
<input type="submit" name="action[delete]" value="Delete" style="background-color: red; color: white;" onclick="return confirm(\'Are you sure you want to delete the selected files?\');" />
</form>
</div><br />
<!-- end file list-->'; ?>