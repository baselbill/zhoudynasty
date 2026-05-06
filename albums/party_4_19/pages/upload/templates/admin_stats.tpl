<!-- stats -->
<div class="white_box"> 
<h2>Uploader statistics</h2>
<table style="width: 100%" cellpadding="3">
<tr style="background-color: #F8F8F8">
<td>Registered users:</td>
<td>{$users}  | <a href="admin.php?action=users&amp;what=view">View all</a></td>
</tr>
<tr>
<td>Last registered user:</td>
{if $users > 0}
<td><strong>{$last_user.username}</strong> | Approved: <strong>{$last_user.approved|yes_or_no}</strong></td>
{else}
<td>None</td>
{/if}
</tr>
<tr style="background-color: #F8F8F8">
<td>Total files in incoming directory:</td>
<td>{$total_files}</td>
</tr>
<tr>
<td>Directories in incoming directory:</td>
<td>{$total_dirs}</td>
</tr>
<tr style="background-color: #F8F8F8">
<td>Total size of incoming directory:</td>
<td>{$total_size}</td>
</tr>
<tr>
<td>Upload log:</td>
<td><a href="admin.php?action=log">View</a> | <a href="admin.php?action=log&amp;clear_log=1">Clear</a></td>
</tr>
</table>
</div>
<!-- no more stats-->
