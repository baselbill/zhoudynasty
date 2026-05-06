function getParameter(paramName) {
	var queryString = document.location.href.substr(document.location.href.indexOf(pageExtension == null ? ".html" : pageExtension) + 6);
	if (queryString == "") {
		return "";
	} else {
		var paramArray = queryString.split("&");
		for (var i = 0; i < paramArray.length; i++) {
			var param = paramArray[i].split("=");
			if (param.length == 2) {
				if (param[0].toLowerCase() == paramName) {
					return param[1];
				}
			} else {
				if (param[0].toLowerCase() == paramName) {
					return true;
				}
			}
		}
	}
	return "";
}
