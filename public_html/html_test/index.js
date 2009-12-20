google.load("maps", "2");

$(document).ready(
  function() {
    google.setOnLoadCallback(
      function() {
        var map = new google.maps.Map2(document.getElementById("map"));
        map.setCenter(
          new google.maps.LatLng(35.60709019396141,139.6853256225586),16);
        map.addControl(new GLargeMapControl());
        map.addControl(new GMapTypeControl());
        map.addControl(new GOverviewMapControl());
        map.setMapType(G_NORMAL_MAP);

        var blueIcon = new GIcon(G_DEFAULT_ICON);
        blueIcon.image = "http://www.openspc2.org/Google/Maps/marker/color/0x6af.png";
        map.addOverlay(
          new GMarker(
            new GPoint(139.68549862504005, 35.60725592797462),
            {icon: blueIcon}));
        map.addOverlay(
          new GMarker(
            new GPoint(139.68528673052788, 35.607098916812774),
            {}));


        // IEでのメモリリークを防止
        $(window).unload(GUnload);
      });
  });

