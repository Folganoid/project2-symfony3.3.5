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

    $('.marker_link').click(function(){
        var id = $(this).attr('value');
        var crdx = $(this).attr('coordx');
        var crdy = $(this).attr('coordy');

        google.maps.event.trigger(window['marker'+id], 'click');
        map.setCenter({lat: +crdx, lng: +crdy});

    });



    /**
     * add marker on map
     * @param vr
     * @param cnt
     */
    function addMarker(vr, cnt) {

        var picArray;
        var comArray;

        vr.addListener('click', function () {

            toggleBounce();
            curM = cnt;
            getPics();
            getComments();

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

            /**
             * get pictures
             */
            function getPics() {
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
                    list += "<li><img src='../img/" + picArray[i].filename + "'><a href='../img/" + picArray[i].filename + "' data-uk-lightbox title='" + picArray[i].name + " (" + picArray[i].date + ")'>+</a></li>"
                }

                $('#map_slider').empty();
                $('#map_slider').append(list);
            }

            function getComments() {

                $.ajax({
                    type: "POST",
                    url: "/comment_by_marker_id/" + cnt,
                    async: false,
                    cache: false,
                    success: function (html) {
                        comArray = JSON.parse(html);
                        console.log(comArray);
                    }
                });

                var list = '';

                for (i = 0; i < comArray.length; i++) {
                    list += "<li><b>"+ comArray[i].username +"</b> - <i>(" + comArray[i].date.date + ")</i> - "+ comArray[i].content + "</li>";
                }

                $('#map_comments').empty();
                $('#map_comments').append(list);

            }
        });
    }
}

/**
 * common functions
 */
$(document).ready(function () {
 //   var windowHeight = (window.innerHeight > 350) ? window.innerHeight - 270 : 200;
 //  $('#map').attr('style', 'height: ' + windowHeight + 'px');

});

