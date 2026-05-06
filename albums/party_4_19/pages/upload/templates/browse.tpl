<!-- The file list -->
<div class="white_box">
<form id="files" method="post" action="index.php?action=viewfiles">
<h2>You are now browsing in {$browse_in}</h2>
<strong>Browse others:</strong>
{loop name=i var=$incoming_directories}
<a href="index.php?action=browse&amp;in={$incoming_directories[i]}">{$incoming_directories[i]}</a> 
{/loop}
<br /><br />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="border-bottom: 1px #CC6600 solid;"><a class="orange" title="Sort this column." href="index.php?action=browse&amp;dir={$current_dir}&amp;in={$browse_in}&amp;sortby=name&amp;order={if $order == 'asc'}dsc{else}asc{/if}">File Name</a>{if $sortby == 'name'}  <img src="images/{if $order == 'asc'}up.gif{else}down.gif{/if}" alt="" />{/if}</td>
    <td style="border-bottom: 1px #CC6600 solid;"><a class="orange" title="Sort this column." href="index.php?action=browse&amp;dir={$current_dir}&amp;in={$browse_in}&amp;sortby=size&amp;order={if $order == 'asc'}dsc{else}asc{/if}">Size</a>{if $sortby == 'size'}  <img src="images/{if $order == 'asc'}up.gif{else}down.gif{/if}" alt="" />{/if}</td>
    <td style="border-bottom: 1px #CC6600 solid;"><a class="orange" title="Sort this column." href="index.php?action=browse&amp;dir={$current_dir}&amp;in={$browse_in}&amp;sortby=time&amp;order={if $order == 'asc'}dsc{else}asc{/if}">Uploaded</a>{if $sortby == 'time'}  <img src="images/{if $order == 'asc'}up.gif{else}down.gif{/if}" alt="" />{/if}</td>
    <td style="border-bottom: 1px #CC6600 solid;"><a class="orange" title="Sort this column." href="index.php?action=browse&amp;dir={$current_dir}&amp;in={$browse_in}&amp;sortby=type&amp;order={if $order == 'asc'}dsc{else}asc{/if}">Type</a>{if $sortby == 'type'}  <img src="images/{if $order == 'asc'}up.gif{else}down.gif{/if}" alt="" />{/if}</td>
    <td style="border-bottom: 1px #CC6600 solid;"><a class="orange" title="Check all items in this column." href="javascript:checkall('files', 'selected[]')">Select</a></td>
  </tr>
  
  {loop name=i var=$files}
  <tr style="background-color:white;">
    <td style="border-bottom: #F0F0F0 1px solid;"><a href="{$files[i].url}">{$files[i].name}</a></td>
    <td style="border-bottom: #F0F0F0 1px solid;">{$files[i].size}</td>
    <td style="border-bottom: #F0F0F0 1px solid;">{$files[i].time}</td>
    <td style="border-bottom: #F0F0F0 1px solid;">{$files[i].type}</td>
    <td style="border-bottom: #F0F0F0 1px solid;"><div style="text-align:center"><input id="selected[]" name="selected[]" type="checkbox" value="{$files[i].name}" {if $files[i].type == 'dir'}disabled="disabled"{/if}/></div></td>
  </tr>  
  {/loop}
  <tr>
    <td style="border-top: 1px #CC6600 solid;">{$total_files} file(s)</td>
    <td style="border-top: 1px #CC6600 solid;">{$total_size}</td>
    <td style="border-top: 1px #CC6600 solid;">&nbsp;</td>
    <td style="border-top: 1px #CC6600 solid;">&nbsp;<input type="hidden" name="dir" id="dir" value="{$current_dir}" /><input type="hidden" name="browse_in" id="browse_in" value="{$browse_in}" /></td>
    <td style="border-top: 1px #CC6600 solid;"><div style="text-align:center"><a title="View all checked items." href="javascript:document.getElementById('files').submit()" class="orange">View</a></div></td>
  </tr>
</table>
</form>
</div><br />
<!-- end file list-->