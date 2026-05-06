<?php print '<!-- the images -->
';for($i=0; $i<count($_vars['files']); $i++){print'<div class="white_box">
Filename: <a href="'; print @$_vars['files'][$i]['url'] . '" title="Right click > Copy">'; print @$_vars['files'][$i]['name'] . '</a><br />
IMG TAG: [IMG]'; print @$_vars['files'][$i]['url'] . '[/IMG]
<hr />
<img src="'; print @$_vars['files'][$i]['url'] . '" alt="'; print @$_vars['files'][$i]['name'] . '" style="border: 1px #cccccc solid"/>  <br />
</div><br />
';}print'
<!-- no more images-->'; ?>