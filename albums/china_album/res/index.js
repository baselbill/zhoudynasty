/* Page onLoad function */
function doOnload() {
	getPageSize();
	
	// get the width of the thumbnails layer
	if (scrollDIV == null) scrollDIV = document.getElementById("ThumbailsScrollDIV");
	if (scrollDIV != null) {
		layerWidth = scrollDIV.offsetWidth;
	}
	
	// when window is resized, remove and add track and thumb layer
	if (dd.elements.thumb) {
		dd.elements.thumb.del();
		dd.elements.track.del();
		scrollWidth = null;
		scrollDIVToPercentage(0);
		selectImage(selectedImgNr);
	} else {
		// select the first image
		if (autoShowFirstSlide) selectImage(0);
	}
	
	// make scrollbar draggable
	maxThumbX = pageWidth - 22;
	if (layerWidth > pageWidth) {
		ADD_DHTML("thumb"+MAXOFFLEFT+minThumbX+MAXOFFRIGHT+maxThumbX+HORIZONTAL+CURSOR_W_RESIZE);
		ADD_DHTML("track"+MAXOFFLEFT+0+MAXOFFRIGHT+0+HORIZONTAL+CURSOR_HAND);
	} else {
		ADD_DHTML("thumb"+NO_DRAG);
		ADD_DHTML("track"+NO_DRAG);
	}
	dd.elements.thumb.moveTo(dd.elements.track.x+1, dd.elements.track.y-2); 
	dd.elements.thumb.moveBy(dd.elements.thumb.x, 0); 
	dd.elements.thumb.setZ(dd.elements.track.z+1); 
	dd.elements.track.addChild("thumb"); 
	dd.elements.thumb.defx = dd.elements.track.x+1;
}

/* Image functions */
function mouse0verThumbnail(image, imgnr) {
	if (imgnr != selectedImgNr) {
		if (image != null) {
			image.className = "ThumbnailMoused";
			if (rollOverThumbs) image.src = image.src.replace(/_1\.jpg/g, ".jpg").replace(/_1\.JPG/g, ".JPG");
		}
	}
}

function mouse0utThumbnail(image, imgnr) {
	if (imgnr != selectedImgNr) {
		if (image != null) {
			image.className = "Thumbnail";
			if (rollOverThumbs) {
				if (image.src.indexOf('_1.jpg') == -1) image.src = image.src.replace(/\.jpg/g, "_1.jpg");
				if (image.src.indexOf('_1.JPG') == -1) image.src = image.src.replace(/\.JPG/g, "_1.JPG")
			}
		}
	}
}

function selectImage(imgnr) {
	zoom = 100;
	selectImage(imgnr, null);
}
function selectImage(imgnr, zoomImg) {
	//Set currently selected image class back to normal
	var image = document.images["Thumb"+selectedImgNr];
	if (image != null) {
		image.className = "Thumbnail";
		if (rollOverThumbs) {
			if (image.src.indexOf('_1.jpg') == -1) image.src = image.src.replace(/\.jpg/g, "_1.jpg");
			if (image.src.indexOf('_1.JPG') == -1) image.src = image.src.replace(/\.JPG/g, "_1.JPG")
		}
	}
	
	//Set selected image class
	var image = document.images["Thumb"+imgnr];
	if (image != null) {
		image.className = "ThumbnailSelected";
		if (rollOverThumbs) image.src = image.src.replace(/_1\.jpg/g, ".jpg").replace(/_1\.JPG/g, ".JPG");
		selectedImgNr = imgnr;
	}
	
	//open image url
	tlink = document.getElementById("hrefThumb"+imgnr);
	if (tlink != null) {
		var p = tlink.protocol;
		var url = '';
		if (p.indexOf('http') == 0 || p.indexOf('ftp') == 0) {
			// web, not local
			url = escape(unescape(tlink.pathname));
			if (url.substr(0, 1) != '/') url = '/' + url;
		} else {
			// running on local
			url = tlink.href;
		}
		if (zoomImg == null) {
			zoom = 100;
			parent.Photos.document.location.href = url;
		} else {
			parent.Photos.document.location.href = url + '?zoom='+zoomImg;
		}
	}
	
	return false;
}

function nextImage() {
	if (selectedImgNr < (imageCount - 1))  {
		selectImage(selectedImgNr + 1);
	} else {
		//Start over again
		selectImage(0);
	}
}
function previousImage() {
	if (selectedImgNr > 0)  {
		selectImage(selectedImgNr - 1);
	} else {
		//Start over again
		selectImage(imageCount - 1);
	}
}

/* Slideshow functions */
function startstopSlideShow(start) {
	var startLayer = document.getElementById("startSlideshow");
	var stopLayer = document.getElementById("stopSlideshow");
	var timerLayer = document.getElementById("slideshowTimer");
	
	if (start) {
		if (selectedImgNr >= 0)	slideshowTimer = setTimeout('doSlideShow()', slideshowInterval);
		else doSlideShow();
		slideshowRunning = true;
		
		startLayer.style.display = "none";
		stopLayer.style.display = "inline";
		if (timerLayer != null) timerLayer.style.display = "block";
	} else {
		if (slideshowTimer != null) clearTimeout(slideshowTimer);
		slideshowTimer = null;
		slideshowRunning = false;
		
		startLayer.style.display = "inline";
		stopLayer.style.display = "none";
		if (timerLayer != null) timerLayer.style.display = "none";
	}
}
function doSlideShow() {
	nextImage();
}
function slideLoaded() {
	if (slideshowRunning) {
		if (slideshowTimer != null) clearTimeout(slideshowTimer);
		slideshowTimer = setTimeout('doSlideShow()', slideshowInterval);
	} else if (autoStartSlideshow) {
		autoStartSlideshow = false;
		startstopSlideShow(true);
	}
}
function adjustSlideshowTiming(up) {
	slideshowInterval = Math.max(1000, slideshowInterval + 1000 * (up ? 1 : -1));
	var sslayer = document.getElementById("slideshowTiming");
	if (sslayer != null) {
		sslayer.innerHTML = new String(parseInt(slideshowInterval / 1000));
	}
	
	//reset interval
	clearTimeout(slideshowTimer);
	slideshowTimer = setTimeout('doSlideShow()', slideshowInterval);
	return false;
}

// function to zoom the image in and out
function adjustZoom(zoomIn) {
	if (zoomIn) {
		zoom = zoom + 25
	} else {
		zoom = zoom - 25
	}
	selectImage(selectedImgNr, zoom);
}

// function to download the original image (or the slide image if original image is not available)
function downloadImage() {
	if (selectedImgNr < 0) return;
	
	var isMinNS4=(navigator.appName.indexOf("Netscape")>=0&&parseFloat(navigator.appVersion)>=4)?1:0;
	var isMinIE4=(document.all)?1:0;
	
	getScreenSize();
	var winft = 'scrollbars=no,width=600,height=450';
	if (isMinNS4) winft += ',screenX=' + (screenW/2 - 325) + ',screenY=' + (screenH/2 - 250);
	if (isMinIE4) winft += ',left=' + (screenW/2 - 325) + ',top=' + (screenH/2 - 250);
	winft += ',directories=no,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no';
	var photoWin = window.open('', 'downloadedimage', winft);
	photoWin.document.write('<HTML><HEAD><TITLE>Download photo</TITLE></HEAD><BODY><IMG SRC="'+downloadURL[selectedImgNr]+'" BORDER="0"></BODY></HTML>');
	photoWin.document.close();
}

var scrollWidth = null;
function scrollDIVToPercentage(percentage) {
	if (layerWidth != null && pageWidth != null) {
		if (scrollWidth == null) scrollWidth = layerWidth - pageWidth;
		if (scrollWidth != null && !isNaN(scrollWidth) && scrollDIV != null) {
			scrollDIV.style.left = -1 * parseInt( scrollWidth * percentage );
		}
	}
}
