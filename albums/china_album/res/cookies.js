function setCookie(name, value, expires) {
	var deCookie = name + "=" + escape(value);
	deCookie += "; path=/"	// cookie is valid throughout the entire domain
	if(expires){
		expires= expires.toGMTString();
		deCookie += "; expires="; 
		deCookie += expires; 
	}
	document.cookie = deCookie;
}
function getCookie(name) {
	var dc = document.cookie;
	var prefix = name + "=";
	var begin = dc.indexOf("; " + prefix);
	if (begin == -1) {
		begin = dc.indexOf(prefix);
		if (begin != 0) return null;
	}
	else begin += 2;
	var end = document.cookie.indexOf(";", begin);
	if (end == -1) end = dc.length;
	return unescape(dc.substring(begin + prefix.length, end));
}