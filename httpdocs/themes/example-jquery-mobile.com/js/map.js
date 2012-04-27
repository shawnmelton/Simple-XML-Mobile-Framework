$(document).ready(function(){
	var map;
	var geoCoder;
	var initialize = (function() {
		geoCoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(36.846, -76.285);
	    var myOptions = {
	    	zoom: 13,
	    	center: latlng,
	    	mapTypeId: google.maps.MapTypeId.ROADMAP
	    };
	
	    map = new google.maps.Map(document.getElementById("map"), myOptions);
	});
	
	var addMarker = (function(address, markerContent){
		geoCoder.geocode( {"address": address}, function(results, status) {
			if( status == google.maps.GeocoderStatus.OK ) {
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
	
				var infoWindow = new google.maps.InfoWindow({
				    content: markerContent
				});
	
				google.maps.event.addListener(marker, "click", function() {
					infoWindow.open(map, marker);
				});
			}
		});
	});
	
	initialize();
	
	var address = "100 Main Street Norfolk VA 23508";
	addMarker(address, ""+ 
		"<strong>jQuery Mobile Example</strong><br>"+
		"100 Main Street<br>Norfolk, VA 23508<br>"+
		'<a target="_blank" href="http://maps.google.com/?q='+ address +'">Directions</a>'
	);
});