<?php print '<!-- stats -->
<div class="white_box"> 
<h2>Uploader statistics</h2>
<table style="width: 100%" cellpadding="3">
<tr style="background-color: #F8F8F8">
<td>Registered users:</td>
<td>'; print @$_vars['users'] . '  | <a href="admin.php?action=users&amp;what=view">View all</a></td>
</tr>
<tr>
<td>Last registered user:</td>
'; if( @$_vars['users'] > 0) { print '
<td><strong>'; print @$_vars['last_user']['username'] . '</strong> | Approved: <strong>' . (isset($_vars['last_user']['approved']) ? $this->yes_or_no(@$_vars['last_user']['approved']) : 'yes_or_no') . '</strong></td>
';}else{print'
<td>None</td>
';}print'
</tr>
<tr style="background-color: #F8F8F8">
<td>Total files in incoming directory:</td>
<td>'; print @$_vars['total_files'] . '</td>
</tr>
<tr>
<td>Directories in incoming directory:</td>
<td>'; print @$_vars['total_dirs'] . '</td>
</tr>
<tr style="background-color: #F8F8F8">
<td>Total size of incoming directory:</td>
<td>'; print @$_vars['total_size'] . '</td>
</tr>
<tr>
<td>Upload log:</td>
<td><a href="admin.php?action=log">View</a> | <a href="admin.php?action=log&amp;clear_log=1">Clear</a></td>
</tr>
</table>
</div>
<!-- no more stats-->
'; ?>