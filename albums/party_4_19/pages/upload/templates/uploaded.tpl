<!-- uploaded files -->
<div class="white_box"> {if $uploaded_count > 0} 
  <h2>The following files were uploaded:</h2>
  {loop name=i var=$uploaded} {$uploaded[i].name} <br />
  {/loop} <br />
  {/if} {if $not_uploaded_count > 0} 
  <h2>The following files were NOT uploaded:</h2>
  {loop name=i var=$not_uploaded} {$not_uploaded[i]} <br />
  {/loop} <br />
  {/if} {if $uploaded_count > 0 && $img_tags == 1} 
  <h2>vBB [IMG] tags</h2>
  <textarea name="img_tags" id="img_tags" cols="95" rows="6">
{loop name=i var=$uploaded}
[IMG]{$uploaded[i].url}[/IMG]
{/loop}</textarea><br /><br /><input type="image" src="images/copy.gif" onclick="copy('img_tags'); return false;" /><br />
  {/if} {if $uploaded_count > 0 && $img_urls == 1} <br />
  <h2>Links for uploaded images</h2>
  <textarea name="img_urls" id="img_urls" cols="95" rows="6">
{loop name=i var=$uploaded}
{$uploaded[i].url}
{/loop}</textarea><br /><br /><input type="image" src="images/copy.gif" onclick="copy('img_urls'); return false;" /><br />
  {/if} </div>
{if $uploaded_count > 0 && $img_preview == 1} <br />
<div class="white_box"> 
  <h2>Uploaded images</h2>
</div>
{loop name=i var=$uploaded} <br />
<div class="white_box"> <a href="{$uploaded[i].url}">{$uploaded[i].name}</a> 
  <hr />
  <img src="{$uploaded[i].url}" alt="" /><br />
</div>
{/loop} {/if} 
<!-- End uploaded files -->
