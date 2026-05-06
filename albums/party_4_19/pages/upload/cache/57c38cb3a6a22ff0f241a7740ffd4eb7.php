<?php print '<!-- stats -->
<div class="white_box"> 
<h2>Upload logs</h2>
<div style="padding-top: 3px; padding-bottom: 5px"><a href="admin.php?action=log&amp;clear_log=1">Click here to clear the log file.</a></div>
<table style="width:100%" cellpadding="2" cellspacing="1">
<tr style="background-color: orange; color: white">
<td>Username</td>
<td>IP</td>
<td>File</td>
<td>To</td>
<td>Time</td>
</tr>

';for($i=0; $i<count($_vars['logs']); $i++){print'<tr style="background-color: #F0F0F0">
<td style="font-size: 9px;">'; print @$_vars['logs'][$i]['user'] . '</td>
<td style="font-size: 9px;">'; print @$_vars['logs'][$i]['ip'] . '</td>
<td style="font-size: 9px;">'; print @$_vars['logs'][$i]['file'] . '</td>
<td style="font-size: 9px;">'; print @$_vars['logs'][$i]['dest'] . '</td>
<td style="font-size: 9px;">'; print @$_vars['logs'][$i]['time'] . '</td>
</tr>
';}print'

</table>
</div>
<!-- no more stats-->
'; ?>