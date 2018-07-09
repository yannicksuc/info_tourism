jQuery(document).ready(function($){
    $('.category-color-field').wpColorPicker();
});

//Places form

jQuery(document).on('click', '.change-updatable-place', function () {
    let row = jQuery(this);
    jQuery("tr#selected-row").removeAttr("id");
    jQuery(this).attr("id","selected-row");
    let id = row.find(".place_id").text();
    let name = row.find(".place_name").text();
    let coord = row.find(".place_coord").text().split(' ');
    let categories = row.find('.tag-wrapper span.tourism-tag').map(function () {
        return jQuery(this).text();
    }).get();

    let destElem = jQuery('form#add_place_form');
    destElem.find('#place_id').val(id);
    destElem.find('#place_name').val(name);
    destElem.find('#latitude').val(coord[0]);
    destElem.find('#longitude').val(coord[1]);
    destElem.find('tr.place-categories .tag-wrapper span.tourism-tag').map(function () {
        jQuery(this).find("input").prop('checked', (categories.indexOf(jQuery(this).text()) !== -1));
    }).get();
});

function clearPlaces() {
    let destElem = jQuery('form#add_place_form');
    jQuery("tr#selected-row").removeAttr("id");
    destElem.find('#place_id').val(-1);
    destElem.find('#place_name').val("");
    destElem.find('#latitude').val("");
    destElem.find('#longitude').val("");
    destElem.find('tr.place-categories .tag-wrapper span.tourism-tag').map(function () {
        jQuery(this).find("input").prop('checked', false);
    }).get();
}

jQuery(document).on('click', '#place-clear', clearPlaces);

//Map Managment

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

function fillReceivers($place) {
    clearPlaces();
    jQuery('.info-tourism-map-receiver.address').val("POMME");
    jQuery('.info-tourism-map-receiver.longitude').val("3333");
    jQuery('.info-tourism-map-receiver.latitude').val("3333");
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
        fillReceivers(places);
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
//            info-tourism-map-receiver latitude
            if (place.geometry.viewport)
                bounds.union(place.geometry.viewport);
            else
                bounds.extend(place.geometry.location);
        });
        map.fitBounds(bounds);
    });
}