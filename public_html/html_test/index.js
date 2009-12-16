google.load("maps", "2");

$(document).ready(
  function() {
    google.setOnLoadCallback(
      function() {
        var map = new google.maps.Map2(document.getElementById("map"));
        map.setCenter(new google.maps.LatLng(35.60709019396141,139.6853256225586),13);

        map.addControl(new GLargeMapControl());
        map.addControl(new GMapTypeControl());
        map.addControl(new GOverviewMapControl());
        map.setMapType(G_NORMAL_MAP);

        var redIcon = new GIcon(G_DEFAULT_ICON);
        redIcon.image = "http://www.openspc2.org/Google/Maps/marker/color/0xf77.png";

        var blueIcon = new GIcon(G_DEFAULT_ICON);
        blueIcon.image = "http://www.openspc2.org/Google/Maps/marker/color/0x6af.png";
        var yellowIcon = new GIcon(G_DEFAULT_ICON);
        yellowIcon.image = "http://www.openspc2.org/Google/Maps/marker/color/0xff0.png";

        points.forEach(function(pt) {
                         var opt = {};
                         switch (pt.state) {
                         case -1: opt.icon = redIcon; break;
                         case 0: opt.icon = yellowIcon; break;
                         case 1: opt.icon = blueIcon; break;
                         }
                         map.addOverlay(new GMarker(new GPoint(pt.x, pt.y), opt));
                       });

        // IEでのメモリリークを防止
        $(window).unload(GUnload);

        var geocoder = new GClientGeocoder();
        var list = $('#list');

        function showInfoWindow(name, place, point, marker) {
          marker.openInfoWindowHtml(
            '検索ワード: ' + name + '<br />' +
              'Lat: ' + point.lat() + ', Lng: ' + point.lng() + '<br />' +
              '住所: ' + place.address
          );
        }

        $('#search').focus();
        $('form').submit(
          function(event) {
            event.preventDefault();
            geocoder.getLocations(
              $('#search').val(),
              function(response) {
                console.log('response: %o', response);
                if (!response || response.Status.code != 200) {
                  alert('"' + response.name + '" は見つかりませんでした');
                } else {
                  response.Placemark.forEach(
                    function(place) {
                      var point = new GLatLng(place.Point.coordinates[1],
                                              place.Point.coordinates[0]);
                      var showWindow = function() {
                        showInfoWindow(response.name, place, point, marker);
                      };

                      var marker = new GMarker(point);
                      map.addOverlay(marker);
                      GEvent.addListener(marker, 'click', showWindow);
                      list.append(
                        $('<div>')
                          .addClass('item')
                          .append($('<h2>').append(
                                    $('<a>')
                                      .attr('href', 'javascript: void(0);')
                                      .append(response.name)
                                      .click(showWindow)))
                          .append(
                            $('<div>')
                              .addClass('desc')
                              .append('<strong>Lat:</strong> ' + point.lat() + ', ')
                              .append('<strong>Lng:</strong> ' + point.lng())
                              .append('<br />')
                              .append('<strong>Addr:</strong> ' + place.address)));
                    });
                }
              });
          });

        $('#search').val('美濃加茂市山之上町 飲食店');
        $('form').submit();
        $('#search').val('飲食店 可児市 おいしい');
        $('form').submit();
        $('#search').val('犬山市 犬山城');
        $('form').submit();
        $('#search').val('大岡山 四川屋台');
        $('form').submit();
        $('#search').val('武蔵小杉タワープレイス');
        $('form').submit();
      });
  });

var points = [
  {
    name: "hoge1",
    x: 139.68549862504005,
    y: 35.60725592797462,
    state: -1
  },
  {
    name: "hoge2",
    x: 139.68528673052788,
    y: 35.607098916812774,
    state: 1
  },
  {
    name: "hoge3",
    x: 139.68568673052788,
    y: 35.607098916812774,
    state: 0
  }
];