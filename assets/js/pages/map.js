var map;

function initialize(id) {
    var lat = document.getElementById(id + '_lat').value;
    var lon = document.getElementById(id + '_lon').value;

    var latlng = new google.maps.LatLng(lat, lon);
    map = new google.maps.Map(document.getElementById(id + '_map'), {
        center: latlng,
        zoom: 12,
        mapTypeId: 'roadmap'
    });
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
    });
    var input = document.getElementById(id + '_search');
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    var geocoder = new google.maps.Geocoder();
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();
    autocomplete.addListener('place_changed', function () {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();

        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }

        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        bindDataToForm(place, id);
        //infowindow.setContent(place.formatted_address);
        //infowindow.open(map, marker);

    });
    delete map;

}

function bindDataToForm(address, id) {
    console.log(address);
    var country_id;
    var country = address.address_components.filter(function (address_component) {
        return address_component.types.includes("country");
    });
    var state = address.address_components.filter(function (address_component) {
        return address_component.types.includes("administrative_area_level_1");
    });
    var postcode = address.address_components.filter(function (address_component) {
        return address_component.types.includes("postal_code");
    });
    var street1 = address.address_components.filter(function (address_component) {
        return address_component.types.includes("subpremise");
    });
    var street = address.address_components.filter(function (address_component) {
        return address_component.types.includes("street_number");
    });
    var street_name = address.address_components.filter(function (address_component) {
        return address_component.types.includes("route");
    });
    var suburb1 = address.address_components.filter(function (address_component) {
        return address_component.types.includes("locality");
    });
    var suburb2 = address.address_components.filter(function (address_component) {
        return address_component.types.includes("administrative_area_level_2");
    });

    if(suburb1.length == suburb2.length) {
        var sub = (suburb1.length) ? suburb1[0].long_name : ((suburb2.length) ? suburb2[0].long_name : '');
    } else {
        var sub = ((suburb1.length) && (suburb2.length)) ? suburb1[0].long_name + " " + suburb2[0].long_name : ((suburb1.length) ? suburb1[0].long_name : ((suburb2.length) ? suburb2[0].long_name : ''));
    }
    
    //administrative_area_level_2
    //locality
    //subpremise
    var strt1 = ((street.length) && (street_name.length)) ? street[0].long_name + " " + street_name[0].long_name : ((street.length) ? street[0].long_name : ((street_name.length) ? street_name[0].long_name : ''));
    var strt = (street1.length) ? street1[0].long_name + '/' + strt1 : strt1;
    
    if(country.length && (country[0].long_name == 'United Kingdom')) {
        state = address.address_components.filter(function (address_component) {
            return address_component.types.includes("postal_town");
        });
        var subking = address.address_components.filter(function (address_component) {
            return address_component.types.includes("neighborhood");
        });
        sub = (subking.length) ? subking[0].long_name : sub;
    }

    document.getElementById(id + '_lat').value = address.geometry.location.lat();
    document.getElementById(id + '_lon').value = address.geometry.location.lng();
    //document.getElementById(id+'_country').value = country.length ? country[0].long_name: "";
    document.getElementById(id + '_postcode').value = postcode.length ? postcode[0].long_name : "";
    //document.getElementById(id+'_state').value = state.length ? state[0].long_name: "";
    //document.getElementById(id+'_suburb').value = suburb.length ? suburb[0].long_name: "";
    //document.getElementById(id+'_street').value = street.length ? street[0].long_name: "";
    document.getElementById(id + '_suburb').value = sub;
    document.getElementById(id + '_street').value = strt;

    $.ajax({
        type: "POST",
        data: {'country': country[0].long_name, 'state': state[0].long_name},
        url: "user/profile/get_map_states_list",
        success: function (result) {
            result = JSON.parse(result);
            country_id = result.id;
            if (result.id != '') {
                $('select#' + id + '_state').html(result.output);
            } else {
                $('select#' + id + '_state').html('<option value=""></option>');
            }
            $('select#' + id + '_state').selectpicker("refresh");

            $('select#' + id + '_country').val(country_id);
            $('select#' + id + '_country').selectpicker("refresh");
        }
    });

}

$(document).ready(function () {
    $('input[name="location"]').on('keyup keypress', function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $(document).on('click', '.edit_home_add', function () {
        setTimeout(function () {
            initialize('home');
        }, 1000);
    });
    $(document).on('click', '.edit_emer_add', function () {
        setTimeout(function () {
            initialize('emerg');
        }, 1000);
    });
    $(document).on('click', '.edit_doc_add', function () {
        setTimeout(function () {
            initialize('doctor');
        }, 1000);
    });
    $(document).on('click', '#inlineRadio4', function () {
        setTimeout(function () {
            initialize('elder');
        }, 1000);
    });
    $(document).on('click', '.edit_supp_eld_add', function () {
        setTimeout(function () {
            initialize('support');
        }, 1000);
    });
    $(document).on('click', '.supported_other_loc', function () {
        setTimeout(function () {
            initialize('minding');
        }, 1000);
    });
    
    if ($("#minding_id").length && $("#minding_id").val() != '') {
        initialize('minding');
    }
});