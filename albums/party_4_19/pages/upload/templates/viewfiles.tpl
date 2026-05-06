<!-- the images -->
{loop name=i var=$files}
<div class="white_box">
Filename: <a href="{$files[i].url}" title="Right click > Copy">{$files[i].name}</a><br />
IMG TAG: [IMG]{$files[i].url}[/IMG]
<hr />
<img src="{$files[i].url}" alt="{$files[i].name}" style="border: 1px #cccccc solid"/>  <br />
</div><br />
{/loop}
<!-- no more images-->