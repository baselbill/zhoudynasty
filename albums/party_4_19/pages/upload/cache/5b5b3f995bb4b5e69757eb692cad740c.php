<?php print '<!-- message-->
<div class="white_box">
<h1>' . (isset($_vars['title']) ? @$_vars['title'] : 'Uploader output message:') . '</h1>
'; print @$_vars['message'] . '
</div>
<!-- no more message -->

'; ?>