Shoutbox version 1

Installation
------------------------------------------------------------------------
1. Extract the files to a temporary directory. Open shoutbox.php
and edit the settings. REMEMBER CHANGE THE ADMIN PASSWORD!! 
The settings are explained in the shoutbox file.

2. Upload to your server. The PHP files, the HTM files,
and the directory "shouts" must be in the same directory.

3. CHMOD the "shouts" directory to 0666 or 0777

4. CHMOD the *.dat files to 0666 or 0777

5. Include the shoutbox into your page via PHP or iframe.

   Using PHP
   *****************************************************
   <?php
   include ( '/path/to/shoutbox.php' );
   ?>
   *****************************************************

   Using Iframes (not recommended,a lot of browsers other than IE cannot render iframes!):
   *****************************************************
   <iframe src="http://yoursite.com/path/to/shoutbox.php"></iframe>
   *****************************************************


Tips if you can't get it to work
------------------------------------------------------------------------
1. $sb_url is the url to the page where the user can view the shoutbox. 
NOT to the shoutbox itself. If you use the Iframes method, $sb_url can 
just be 'shoutbox.php'

That's all i can think of right now. It should work with minimal configurations.
Post in the shoutbox on my website if you need more help.


How to edit the layout
------------------------------------------------------------------------
The layout of your shoutbox is read from the HTM files. 
Just edit them any way you like. The only restriction
is changing the variables inside braces { curly brackets }.
Once you're done changing, save and upload to your server
and you should see the change. Anything that has to do
with the layout of the shoutbox can be found in the HTM files.

Note 1: This shoutbox is intedned to be included into your page. Meaning
the page's headers (<html><body>...etc) are not printed out, just the table, data, etc... 
If you want to use the shoutbox in an iframe with its own header/footer, edit the shoutbox_header/footer.htm
files. This is the order in which the shoutbox will print out data.
 
	shoutbox_header.htm  (<html><body>....whatever)
	the shoutbox content, shouts, admin, etc...
	shoutbox_footer.htm  (whatever....</body></html>)

Note 2: Because of complex parsing, the only way you can change the style of 
parsed url and image tags in posters' messages is through css. Parsed urls are given classname
"shoutbox". So parsed urls look like this <a href="google.com" class="shoutbox">go googling</a>
and parsed images from [IMG] tags look like this <img src="goggle.com" class="shoutbox" />



Other stuffs
-----------------------------------------------------------------------
License: Free to use and redistribute and modify but please leave the
credit link intact.

Other: The .htaccess file inside the "shouts" directory helps keep the 
shouts from being viewed directly. 

Visit my site http://celerondude.com if you need help.