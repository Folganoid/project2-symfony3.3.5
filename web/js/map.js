// AIzaSyDEF7mhKBOcKf3DOomC3CPSq8htToAlZ84

/**
 * google map API
 */
function initMap() {

    var curM;
    var cntr = {lat: 51.336389, lng: 33.863056};
    var markersArray;

    /**
     * get markers array
     */
    $.ajax({
        type: "POST",
        url: "/markers/" + $('#map').attr('value'),
        async: false,
        cache: false,
        success: function (html) {
            markersArray = JSON.parse(html);
        }
    });

    if (markersArray.length > 0) {
        cntr = {lat: +markersArray[0].coordX, lng: +markersArray[0].coordY}
    }

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 8,
        center: cntr
    });

    for (i = 0; i < markersArray.length; i++) {
        window['marker' + markersArray[i].id] = new google.maps.Marker({
            map: map,
            animation: google.maps.Animation.DROP,
            position: {lat: +markersArray[i].coordX, lng: +markersArray[i].coordY},
            title: markersArray[i].name
        });

        addMarker(window['marker' + markersArray[i].id], markersArray[i].id);
    }

    $('.marker_link').click(function () {
        var id = $(this).attr('value');
        var crdx = $(this).attr('coordx');
        var crdy = $(this).attr('coordy');

        google.maps.event.trigger(window['marker' + id], 'click');
        map.setCenter({lat: +crdx, lng: +crdy});

    });


    /**
     * add marker on map
     * @param vr
     * @param cnt
     */
    function addMarker(vr, cnt) {

        vr.addListener('click', function () {

            toggleBounce();
            curM = cnt;
            getPics(cnt);
            getComments(cnt);
            $('#comment_val').attr('marker', cnt);
            $('.uk-hidden').attr('class', 'uk-container');

            /**
             * toggle marker animation
             */
            function toggleBounce() {
                if (curM >= 0) window['marker' + curM].setAnimation(null);
                if (vr.getAnimation() != null) {
                    vr.setAnimation(null);
                } else {
                    vr.setAnimation(google.maps.Animation.BOUNCE);
                }
            }
        });
    }
}

/**
 * common functions
 */
$(document).ready(function () {

    $('#comment_but').click(function () {

        if($('#comment_val').val()) {
            $.ajax({
                type: "POST",
                url: "/send_comment",
                cache: false,
                data: {
                    "data": $('#comment_val').val(),
                    "marker": $('#comment_val').attr('marker'),
                    "rate": $('#comment_rate').val()
                },
                success: function (result) {
                    getComments($('#comment_val').attr('marker'));
                }
            });
        }
    });

});

/**
 *get comments
 * @param cnt
 */
function getComments(cnt) {

    var comArray;

    $.ajax({
        type: "POST",
        url: "/comment_by_marker_id/" + cnt,
        async: false,
        cache: false,
        success: function (html) {
            comArray = JSON.parse(html);
        }
    });

    var list = '';
    var totalRate = 0;
    var totalRateCount = 0;

    for (i = 0; i < comArray.length; i++) {

        list += '<li><div>' +
            '<b>' + comArray[i].username + '</b>' +
            '<i> (' + comArray[i].date.date.substring(0, 19) + ')</i>' +
            '<span> ' + getStars(comArray[i].rate) + '</span>' +
            '<div class="uk-comment-body"><p>' + comArray[i].content + '</p>' +
            '</div></div></li>';

        if (comArray[i].rate != 0) {
            totalRate += comArray[i].rate;
            totalRateCount++;
        }
    }

    $('#star_title').empty();
    $('#marker_title').empty();
    $('#map_comments').empty();

    if (totalRate != 0 && totalRateCount != 0) {
        $('#star_title').append(getStars(totalRate/totalRateCount));
        $('#marker_title').append((totalRate/totalRateCount).toFixed(2))
    }

    $('#map_comments').append(list);

}

/**
 * get pictures
 */
function getPics(cnt) {

    var picArray;

    $.ajax({
        type: "POST",
        url: "/picture_by_marker_id/" + cnt,
        async: false,
        cache: false,
        success: function (html) {
            picArray = JSON.parse(html);
        }
    });

    var list = '';

    for (i = 0; i < picArray.length; i++) {
        list += "<li>" +
            "<img src='../img/" + picArray[i].filename + "'>" +
            "<a href='../img/" + picArray[i].filename + "' data-uk-lightbox title='" + picArray[i].name +
            " (" + picArray[i].date + ")'>" +
            "<span class='uk-icon-plus-square-o icon-img'></span></a>" +
            "</li>"
    }

    $('#map_slider').empty();
    $('#map_slider').append(list);
}

/**
 * get rate stars
 */
function getStars(rate) {

    var stars = 5;
    var result = '';

    if (rate != 0) {

        var com = rate.toFixed(2);
        var nat = (com + '').substring(0, 1);
        var half = +com % 1;


        for (z = 0; z < +nat; z++) {
            result += '<i class="uk-icon-star"></i>';
        }

        if (half > 0) {
            result += '<i class="uk-icon-star-half-empty"></i>';
            stars--;
        }

        for (z = 0; z < (stars - +nat); z++) {
            result += '<i class="uk-icon-star-o"></i>';
        }
    }

    return result;
}

