<!-- The upload form -->
<div class="white_box"> 
  <form id="UploadForm" action="index.php" method="post" enctype="multipart/form-data">
    <div> 
      <h1>Select your files</h1>
      {loop name=i var=$upload_fields}<strong>{$upload_fields[i]}</strong>. 
      <input type="file" name="file{$upload_fields[i]}" size="70" style="margin-bottom: 3px"/>
      <br />
      {/loop} <br />
          
      <input type="image" src="images/upload.gif" name="action[doupload]" />
    </div>
    <div> <br />
      <h1>Upload options</h1>
	Upload into: <strong id="selected_destination">Uploader default</strong><br />
      <select name="destination" cols="50" id="destination" onchange="document.getElementById('selected_destination').innerHTML = this.value;">
	{loop name=j var=$incoming_directories}
	    <option value="{$incoming_directories[j]}">{$incoming_directories[j]}</option>
	{/loop}
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
      <input type="text" id="number_of_fields" name="number_of_fields" size="2" value="{$number_of_fields}" />
      <div style="position: absolute; display:inline;"> 
        <input type="image" src="images/add.gif" name="add" onclick="increment('number_of_fields'); return false; "/>
        <input type="image" src="images/reload.gif" name="action[reload]" />
      </div>
    </div>
    {if $show_rules}
    <div> <br />
      <h1>Upload rules</h1>
      Max file size: {$max_file_size}KB<br />
      Image only: {$image_only|yes_or_no}<br />
      File name cannot contain the following characters: \ / : * ? < > | &<br />
      Allowed file types: {$allowed_types} </div>{/if}
	{if $show_comments}
    <div> <br />
      <h1>Administrator comments</h1>
      {$comments} </div>{/if}
  </form>
</div>
<!-- End of the upload form -->
