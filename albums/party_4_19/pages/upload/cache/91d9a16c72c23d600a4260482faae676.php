<?php print '<!-- uploaded files -->
<div class="white_box"> '; if( @$_vars['uploaded_count'] > 0) { print ' 
  <h2>The following files were uploaded:</h2>
  ';for($i=0; $i<count($_vars['uploaded']); $i++){print' '; print @$_vars['uploaded'][$i]['name'] . ' <br />  ';}print' <br />
  ';}print' '; if( @$_vars['not_uploaded_count'] > 0) { print ' 
  <h2>The following files were NOT uploaded:</h2>
  ';for($i=0; $i<count($_vars['not_uploaded']); $i++){print' '; print @$_vars['not_uploaded'][$i] . ' <br />  ';}print' <br />
  ';}print' '; if( @$_vars['uploaded_count'] > 0 && @$_vars['img_tags'] == 1) { print ' 
  <h2>vBB [IMG] tags</h2>
  <textarea name="img_tags" id="img_tags" cols="95" rows="6">
';for($i=0; $i<count($_vars['uploaded']); $i++){print'[IMG]'; print @$_vars['uploaded'][$i]['url'] . '[/IMG]
';}print'</textarea><br /><br /><input type="image" src="images/copy.gif" onclick="copy(\'img_tags\'); return false;" /><br />
  ';}print' '; if( @$_vars['uploaded_count'] > 0 && @$_vars['img_urls'] == 1) { print ' <br />
  <h2>Links for uploaded images</h2>
  <textarea name="img_urls" id="img_urls" cols="95" rows="6">
';for($i=0; $i<count($_vars['uploaded']); $i++){print''; print @$_vars['uploaded'][$i]['url'] . '
';}print'</textarea><br /><br /><input type="image" src="images/copy.gif" onclick="copy(\'img_urls\'); return false;" /><br />
  ';}print' </div>
'; if( @$_vars['uploaded_count'] > 0 && @$_vars['img_preview'] == 1) { print ' <br />
<div class="white_box"> 
  <h2>Uploaded images</h2>
</div>
';for($i=0; $i<count($_vars['uploaded']); $i++){print' <br /><div class="white_box"> <a href="'; print @$_vars['uploaded'][$i]['url'] . '">'; print @$_vars['uploaded'][$i]['name'] . '</a> 
  <hr />
  <img src="'; print @$_vars['uploaded'][$i]['url'] . '" alt="" /><br />
</div>
';}print' ';}print' 
<!-- End uploaded files -->
'; ?>