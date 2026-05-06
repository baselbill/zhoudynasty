// ===
s_ua=navigator.userAgent.toLowerCase();
s_dl=document.getElementById?1:0;
s_iE=document.all&&!window.innerWidth&&s_ua.indexOf("msie")!=-1?1:0;
s_oP=s_ua.indexOf("opera")!=-1&&document.clear?1:0;
s_oP7=s_oP&&document.appendChild?1:0;
s_oP7m=s_oP&&!s_oP7?1:0;
s_nS=s_dl&&!document.all&&s_ua.indexOf("opera")==-1?1:0;
s_nS4=document.layers?1:0;
s_kNv=s_ua.indexOf("konqueror")!=-1?parseFloat(s_ua.substring(s_ua.indexOf("konqueror/")+10)):0;
s_kN=s_kNv>=2.2?1:0;
s_kN3p=s_kNv>=3?1:0;
s_kN31p=s_kNv>=3.1?1:0;
s_kN32p=s_kNv>=3.2?1:0;
s_mC=s_ua.indexOf("mac")!=-1?1:0;
s_sF=s_mC&&s_ua.indexOf("safari")!=-1?1:0;
s_iE5M=s_mC&&s_iE&&s_dl?1:0;
s_iE4M=s_mC&&s_iE&&!s_dl?1:0;
s_iE4=!s_mC&&s_iE&&!s_dl?1:0;
s_ct=0;
	
var loadedTimer = null;
function doOnload() {
	isSlideLoaded = true;

	// set background of comments
	if (doShowComments) {
		var comBgDiv = document.getElementById("CommentsBgDIV");
		comBgDiv.style.backgroundImage = "url("+resourcesPath+"commentsbg.png)";
		if (pinnedDown) autoShowInfoLayer();
		else document.getElementById('pindown').src = resourcesPath + 'pindown.gif';
	}
	
	//show the image
	writeImage();
	
	// if image has finished loading before page is loaded, notify navigation frame now
	if (isSlideImageLoaded) slideImageLoaded();
}

function getCSSElement(theClass,element) {
	var cssRules;
	if (document.all) {
		cssRules = 'rules';
	}
	else if (document.getElementById) {
		cssRules = 'cssRules';
	}
	theClass = theClass.toLowerCase();
	for (var S = 0; S < document.styleSheets.length; S++){
		for (var R = 0; R < document.styleSheets[S][cssRules].length; R++) {
			if (document.styleSheets[S][cssRules][R].selectorText.toLowerCase() == theClass) {
				return document.styleSheets[S][cssRules][R].style[element];
			}
		}
	}
	return "";
}

var widthDiff = 0;
var heightDiff = 0;
function writeImage() {
	if (slideImage == null) return;
		
	var layer = document.getElementById("PhotoDIV");		
	if (layer != null) {
		layer.innerHTML = '';
	
		getPageSize();
		
		if (imgWidth > pageWidth) {
			imgHeight = parseInt((imgHeight * pageWidth) / imgWidth);
			imgWidth = pageWidth;
		}
		if (imgHeight > pageHeight) {
			imgWidth = parseInt((imgWidth * pageHeight) / imgHeight);
			imgHeight = pageHeight;
		}
	
		var zoom = parseInt( getParameter("zoom") );
		if (!isNaN(zoom)) {
			// zoom into image
			imgHeight = parseInt(imgHeight * zoom / 100);
			imgWidth = parseInt(imgWidth * zoom / 100);
			
			// use the original image to zoom if the image size becomes too large
			if (zoomWithOriginals && (imgWidth > imageWidth || imgHeight > imageHeight)) {
				if (imageWidth < originalWidth) {
					slideImage.src = originalPath;
				}
			}
		}
		// if the image is only 1 pixel smaller than the frame, make it fit the frame (=small deformation)
		if (pageHeight - imgHeight == 1) imgHeight = pageHeight;
		if (pageWidth - imgWidth == 1) imgWidth = pageWidth;
		
		//center image
		if (imgHeight != pageHeight) {
			heightDiff = parseInt( (pageHeight - imgHeight) / 2) ;
		}
		if (imgWidth != pageWidth) {
			widthDiff = parseInt( (pageWidth - imgWidth) / 2) ;
		}
		
		var mouseOverHTML = doShowComments ? ' onmouseover="showComments(true)" onmouseout="showComments(false)"' : '';
		layer.outerHTML = '<SPAN id="PhotoDIV" style="position: absolute; top: '+heightDiff+'px; left: '+widthDiff+'px; z-index: 0;"'+mouseOverHTML+'>'+
							'<img src="'+slideImage.src+'" width="'+imgWidth+'" height="'+imgHeight+'" alt="" border="0" class="Photo" name="imgphoto" id="imgphoto"'+mouseOverHTML+'>'+
							'</SPAN>';
		
		if (imgHeight > pageHeight || imgWidth > pageWidth) {
			//Allow drag of photo within certain boundries
			var maxLeft = widthDiff*-1;
			maxLeft = maxLeft < 0 ? 0 : maxLeft;
			var maxTop = heightDiff*-1;
			maxTop = maxTop < 0 ? 0 : maxTop;
			ADD_DHTML("PhotoDIV"+RESET_Z+MAXOFFBOTTOM+maxTop+MAXOFFLEFT+maxLeft+MAXOFFRIGHT+maxLeft+MAXOFFTOP+maxTop);
		}
	}
}


var commentsShown = false;
var commentsHeight = "";
var commentsHeaderHeight = "";
var commentsTimer = null;
var commentsInterval = 100;
var commentsStep = 5;
function showComments(show) {
	if (!doShowComments) return;
	
	show = show == null ? commentsShown : show;
	show = pinnedDown ? true : show;
	if (show != commentsShown && commentsTimer != null) {
		clearInterval(commentsTimer);
		commentsTimer = null
	}
	commentsShown = show;
		
	if (commentsHeight == "") {
		commentsHeight = getCSSElement("DIV.CommentsDIV", "height");
		if (isNaN(parseInt(commentsHeight))) {
			commentsHeight = "";
		} else {
			commentsHeight = parseInt(commentsHeight);
		}
	}
	if (commentsHeaderHeight == "") {
		commentsHeaderHeight = parseInt(getCSSElement("td.CommentsHeader", "height"));
		if (isNaN(parseInt(commentsHeaderHeight))) {
			commentsHeaderHeight = "";
		} else {
			commentsHeaderHeight = parseInt(commentsHeaderHeight);
		}
	}
	
	var comDiv = document.getElementById("CommentsDIV");
	var comBgDiv = document.getElementById("CommentsBgDIV");
	if (comDiv != null && comBgDiv != null && commentsHeight != "" && commentsHeaderHeight != "") {		
		var y = parseInt(comDiv.style.top);
		y = isNaN(y) ? parseInt(getCSSElement("DIV.CommentsDIV", "top")) : y;
		if (show) {
			//Show comments
			if (y >= 0) {
				comDiv.style.top = 0;
				clearInterval(commentsTimer);
			} else {
				comDiv.style.top = y + commentsStep;
				if (commentsTimer == null) commentsTimer = setInterval('showComments()', commentsInterval);
			}
		} else {
			//Hide comments
			if (y < (commentsHeight * -1)) {
				comDiv.style.top = -1 * commentsHeight;
				clearInterval(commentsTimer);
			} else {
				comDiv.style.top = y - commentsStep;
				if (commentsTimer == null) commentsTimer = setInterval('showComments()', commentsInterval);
			}
		}
		comBgDiv.style.top = parseInt(comDiv.style.top) + commentsHeaderHeight;
	}
}

function commentsDIVMouseOver() {
	showComments(true);
	if (dd.elements.PhotoDIV != null) {
		dd.elements.PhotoDIV.setDraggable(false);
	}
}
function commentsDIVMouseOut() {
	showComments(false);
	if (dd.elements.PhotoDIV != null) {
		dd.elements.PhotoDIV.setDraggable(true);
	}
}
function pinDownUp() {
	var img = document.getElementById('pindown');
	if (img != null) {
		if (pinnedDown) {
			img.src = resourcesPath + 'pindown.gif';
			pinnedDown = false;
		} else {
			img.src = resourcesPath + 'pinup.gif';
			pinnedDown = true;
		}
	}
	if (rememberPinDownState) setCookie('pindownstate', pinnedDown, new Date(2099,1,1));
}
function autoShowInfoLayer() {
	// show comments layer by default
	var comDiv = document.getElementById("CommentsDIV");
	var comBgDiv = document.getElementById("CommentsBgDIV");
			
	if (commentsHeaderHeight == "") {
		commentsHeaderHeight = parseInt(getCSSElement("td.CommentsHeader", "height"));
		if (isNaN(parseInt(commentsHeaderHeight))) {
			commentsHeaderHeight = 0;
		} else {
			commentsHeaderHeight = parseInt(commentsHeaderHeight);
		}
	}
	
	// show correct pindown/up image
	var img = document.getElementById('pindown');
	if (pinnedDown) {
		img.src = resourcesPath + 'pinup.gif';
	} else {
		img.src = resourcesPath + 'pindown.gif';
	}
			
	comDiv.style.top = 0;
	comBgDiv.style.top = commentsHeaderHeight;
	commentsShown = true;
}