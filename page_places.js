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

jQuery(document).on('click', '#place-clear', function () {
    let destElem = jQuery('form#add_place_form');
    jQuery("tr#selected-row").removeAttr("id");
    destElem.find('#place_id').val(-1);
    destElem.find('#place_name').val("");
    destElem.find('#latitude').val("");
    destElem.find('#longitude').val("");
    destElem.find('tr.place-categories .tag-wrapper span.tourism-tag').map(function () {
            jQuery(this).find("input").prop('checked', false);
    }).get();
});