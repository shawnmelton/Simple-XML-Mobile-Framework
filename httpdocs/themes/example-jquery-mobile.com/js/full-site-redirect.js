(function(){
	/**
	 * @desc Set cookie so that user does not get bumped to the mobile site.
	 */
	var disableMobileRedirect = (function(){
		var exdate = new Date();
		exdate.setDate(exdate.getDate() + 1);
		document.cookie = "budsmobileredirect=off; expires="+exdate.toUTCString();
	});
	
	
	/**
	 * @desc Has a cookie been set to stop user from being bumped to mobile site?
	 * @return <boolean>
	 */
	var mobileTurnedOff = (function(){
		var cookieParts = document.cookie.split(";");
		for( var i = 0; i < cookieParts.length; i++ ) {
			if( cookieParts[i].indexOf("budsmobileredirect=off") != -1 ) {
				return true;
			}
		}
		
		return false;
	});
	
	
	/**
	 * @desc Is user browsing on a mobile device?
	 * @return <boolean>
	 */
	var onMobileDevice = (function(){
	   try {
	    	var userAgent = navigator.userAgent;
	        if( userAgent == null ) {
	        	return false;
	        }
	
	        var names= ["320x", "160x", "Blazer", "Danger", "UP.Browser", "NetFront", "blackberry", "UP.Link", "CLDC", "J2ME", "AU-MIC", "PalmSource", "PalmOS", "Xiino" , "Windows CE", "Ericsson", "Avantgo", "Nokia", "Samsung", "Symbian", "SEC-SGH", "ATTWS", "Mobile", "Elaine", "OpenWeb", "LGE VX", "LGE-VX", "LGE-LG", "MOTO", "MOT-", "DoCo Mo", "HTC", "SKPD0", "SKPB0", "webOS", "Novarra-Vision"];
	        for( var x in names ) {
	            if( userAgent.indexOf(names[x]) > -1 || (names[x] == "blackberry" && userAgent.toLowerCase().indexOf(names[x]) > -1) ) {
	            	return true;
	            }
	        }
	   } catch (ex) { }
	
	    return false;
	});
	
	if( window.location.href.indexOf("#fs") > -1 ) {
		disableMobileRedirect();
	} else if( mobileTurnedOff() === false && onMobileDevice() ) {
	    window.location="http://m.budsusa.com";
	}
})();