function pinSymbol(color) {
    return {
        path: 'm -0.20481678,-32.067469 c -6.12035052,6e-5 -11.08185822,4.96157 -11.08192522,11.08192 0.006,4.36783 2.5770447,8.32474 6.5657057,10.104791 L 0.00210722,0.01028153 4.7690712,-11.09657 c 3.740005,-1.87849 6.1027088,-5.703729 6.1080358,-9.888979 -6.9e-5,-6.12035 -4.9615748,-11.08185 -11.08192378,-11.08192 z m 0.206924,5.07978 c 3.31488698,10e-6 6.00213198,2.68726 6.00213998,6.00214 -9e-6,3.31489 -2.687253,6.00213 -6.00213998,6.002139 -3.31488452,-9e-6 -6.00212852,-2.687249 -6.00213752,-6.002139 9e-6,-3.31488 2.687253,-6.00213 6.00213752,-6.00214 z',
        fillColor: color,
        fillOpacity: 1,
        strokeWidth: 0,
        strokeWeight: 0,
        scale: 1
    };
}

function initAutocomplete() {
    let map = new google.maps.Map(document.getElementById('info-tourism-map'), {
        center: {lat: 47.966659, lng: 5.030354},
        zoom: 15,
        mapTypeId: 'satellite'
    });
    let searchBox = new google.maps.places.SearchBox(document.getElementById('info-tourism-searchbar'));

    let markers = [];
    searchBox.addListener('places_changed', function() {
        let places = searchBox.getPlaces();
        if (places.length === 0)
            return;
        markers.forEach(function(marker) {
            marker.setMap(null);
        });
        markers = [];

        let bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            markers.push(new google.maps.Marker({
                map: map,
                icon: pinSymbol("#FFF"),
                title: place.name,
                position: place.geometry.location
            }));
            if (place.geometry.viewport)
                bounds.union(place.geometry.viewport);
            else
                bounds.extend(place.geometry.location);
        });
        map.fitBounds(bounds);
    });
}