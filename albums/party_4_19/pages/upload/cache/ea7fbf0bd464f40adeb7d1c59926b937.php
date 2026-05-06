<?php print '<!-- The upload form -->
<div class="white_box"> 
  <form id="UploadForm" action="index.php" method="post" enctype="multipart/form-data">
    <div> 
      <h1>Select your files</h1>
      ';for($i=0; $i<count($_vars['upload_fields']); $i++){print'<strong>'; print @$_vars['upload_fields'][$i] . '</strong>.      <input type="file" name="file'; print @$_vars['upload_fields'][$i] . '" size="70" style="margin-bottom: 3px"/>
      <br />
      ';}print' <br />
          
      <input type="image" src="images/upload.gif" name="action[doupload]" />
    </div>
    <div> <br />
      <h1>Upload options</h1>
	Upload into: <strong id="selected_destination">Uploader default</strong><br />
      <select name="destination" cols="50" id="destination" onchange="document.getElementById(\'selected_destination\').innerHTML = this.value;">
	';for($j=0; $j<count($_vars['incoming_directories']); $j++){print'	    <option value="'; print @$_vars['incoming_directories'][$j] . '">'; print @$_vars['incoming_directories'][$j] . '</option>
	';}print'
      </select>
      <br />
      <br />
      Post upload actions.<br />
      <input type="checkbox" id="img_preview" name="img_preview" value="1" checked="checked" />
      <label for="img_preview">Preview uploaded files</label>
      <br />
      <input type="checkbox" id="img_tags" name="img_tags" value="1" checked="checked" />
      <label for="img_tags">vBB [IMG] tags</label>
      <br />
      <input type="checkbox" id="img_urls" name="img_urls" value="1" />
      <label for="img_urls">List URLs</label>
      <br />
      <br />
      Add more upload fields.<br />
      <input type="text" id="number_of_fields" name="number_of_fields" size="2" value="'; print @$_vars['number_of_fields'] . '" />
      <div style="position: absolute; display:inline;"> 
        <input type="image" src="images/add.gif" name="add" onclick="increment(\'number_of_fields\'); return false; "/>
        <input type="image" src="images/reload.gif" name="action[reload]" />
      </div>
    </div>
    '; if( @$_vars['show_rules']) { print '
    <div> <br />
      <h1>Upload rules</h1>
      Max file size: '; print @$_vars['max_file_size'] . 'KB<br />
      Image only: ' . (isset($_vars['image_only']) ? $this->yes_or_no(@$_vars['image_only']) : 'yes_or_no') . '<br />
      File name cannot contain the following characters: \\ / : * ? < > | &<br />
      Allowed file types: '; print @$_vars['allowed_types'] . ' </div>';}print'
	'; if( @$_vars['show_comments']) { print '
    <div> <br />
      <h1>Administrator comments</h1>
      '; print @$_vars['comments'] . ' </div>';}print'
  </form>
</div>
<!-- End of the upload form -->
'; ?>