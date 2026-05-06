var pageWidth = 0;
var pageHeight = 0;
function getPageSize() {
	  if( typeof( window.innerWidth ) == 'number' ) {
		    //Non-IE
		    pageWidth = window.innerWidth;
		    pageHeight = window.innerHeight;
	  } else if( document.documentElement &&
	      ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		    //IE 6+ in 'standards compliant mode'
		    pageWidth = document.documentElement.clientWidth;
		    pageHeight = document.documentElement.clientHeight;
	  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
  	    //IE 4 compatible
		    pageWidth = document.body.clientWidth;
		    pageHeight = document.body.clientHeight;
	  }
}

var screenW = 640, screenH = 480;
function getScreenSize() {
	if (parseInt(navigator.appVersion)>3) {
 		screenW = screen.availWidth;
 		screenH = screen.availHeight;
	}
	else if ( navigator.appName == "Netscape" && parseInt(navigator.appVersion)==3 && navigator.javaEnabled() ) {
 		var jToolkit = java.awt.Toolkit.getDefaultToolkit();
		var jScreenSize = jToolkit.getScreenSize();
		screenW = jScreenSize.width;
		screenH = jScreenSize.height;
	}
}
