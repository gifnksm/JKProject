function num2alph(num) {
  return String.fromCharCode(Number(num) + 'A'.charCodeAt(0));
}

var Layout = {
  westOpener: null,
  westCloser: null,
  layout: null,
  init: function() {
    Layout.westOpener = $('<a id="west-opener" href="javascript: void(0);"></a>')
      .prependTo($('#map-container .header'));
    Layout.westCloser = $('<a id="west-closer" href="javascript: void(0);"></a>')
      .prependTo('#list-container .header');

    Layout.layout = $('body').layout(Layout.options);
    Layout.layout.addOpenBtn(Layout.westOpener, 'west');
    Layout.layout.addCloseBtn(Layout.westCloser, 'west');
    Layout.westOpener.hide();
  },
  options: {
    north: {
      size: 'auto',
      spacing_open: 0,
      closable: false,
      resizable: false
    },
    west: {
      size: 350,
      spacing_open: 0,
      spacing_closed: 0,
      resizable: false,
      initClosed: true,
      onopen: function() { Layout.westOpener.hide(); },
      onclose: function() { Layout.westOpener.show(); }
    },
    center: {
      onresize_end: function() { GMap.resize(); }
    }
  }
};

var List = {
  _pager: null,
  _data: null,
  _pageTmpl: $.createTemplateURL('templates/page.tpl'),
  _list: null,
  init: function() {
    List._pager = new Pager();
    $('#list-container .footer').append(List._pager.element);
    List._list = $('#list');
    List._pager.addCallback(function(page) { List.updatePage(page); });
  },
  setData: function(data) {
    List._data = data;
    List._pager.updateItems(data.result);
  },
  updatePage: function(page) {
    var items = List._pager.currentItems();
    List._list.html(List._pageTmpl.get(items));
    GMap.setMarker(items);
  }
};

var GMap = {
  defaultLatLng: new google.maps.LatLng(35.60709019396141,139.6853256225586),
  map: null,
  canvas: null,
  _mapBounds: null,
  _canvasSize: [],
  _canvasOffset: [],
  init: function(canvas) {
    GMap.canvas = canvas;
    GMap.map = new google.maps.Map(
      canvas[0],
      {
        zoom: 17,
        center: GMap.defaultLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scaleControl: true,
        keyboardShortcuts: true
      });

    function updateSize() {
      GMap._mapBounds = GMap.map.getBounds();
      GMap._canvasSize = { width: canvas.width(), height: canvas.height() };
      GMap._canvasOffset = canvas.offset();
    }
    google.maps.event.addListener(
      GMap.map, 'bounds_changed', updateSize);
    updateSize();
  },
  _iconsCache: {},
  _shadowIcon: new google.maps.MarkerImage(
    '/resource/image/pin/shadow.png',
    new google.maps.Size(37, 34),   // icon size
    new google.maps.Point(0, 0),    // origin of clickable region
    new google.maps.Point(10, 34) // anchor point
  ),
  createIcon: function(color, alphabet) {
    if (alphabet === undefined)
      alphabet = '';
    if (color === undefined)
      color = 'red';
    var key = alphabet + '.' +  color;
    if (!(key in GMap._iconsCache))
      GMap._iconsCache[key] = new google.maps.MarkerImage(
        '/resource/image/pin/' + alphabet + '.' + color + '.png',
        new google.maps.Size(20, 34),   // icon size
        new google.maps.Point(0, 0),    // origin of clickable region
        new google.maps.Point(10, 34)   // anchor point
      );
    return {
      icon: GMap._iconsCache[key],
      shadow: GMap._shadowIcon
    };
  },
  _dotCache: {},
  createDot: function(color) {
    if (color === undefined)
      color = 'red';
    var key = color;
    if (!(key in GMap._dotCache))
      GMap._dotCache[key] = new google.maps.MarkerImage(
        '/resource/image/dot.' + color + '.png',
        new google.maps.Size(9, 9),     // icon size
        new google.maps.Point(0, 0),    // origin of clickable region
        new google.maps.Point(5, 5) // anchor point
      );
    return {
      icon: GMap._dotCache[key]
    };
  },
  resize: function() {
    var pgRatio = GMap._mapBounds.toSpan().lng() / GMap._canvasSize.width,
        dx = (GMap.canvas.offset().left - GMap._canvasOffset.left) * pgRatio,
        dy = (GMap.canvas.offset().top  - GMap._canvasOffset.top ) * pgRatio,
        dw = (GMap.canvas.width()       - GMap._canvasSize.width ) * pgRatio,
        dh = (GMap.canvas.height()      - GMap._canvasSize.height) * pgRatio,
        oldCenter = GMap._mapBounds.getCenter(),
        center = new google.maps.LatLng(oldCenter.lat() + dy + dh / 2,
                                        oldCenter.lng() + dx + dw / 2);

    if (dx == 0 && dy == 0) {
      google.maps.event.trigger(GMap.map, 'resize');
      return;
    }

    GMap.map.setCenter(new google.maps.LatLng(center.lat() + 10));
    google.maps.event.trigger(GMap.map, 'resize');
    setTimeout(function() { GMap.map.setCenter(center); }, 0);
  },
  _allIDs: [],
  _markers: {},
  _dots: {},
  _tooltips: {},
  _tooltipTmpl: $.createTemplateURL('templates/tooltip.tpl'),
  _infoWindows: {},
  _infoWindowTmpl: $.createTemplateURL('templates/info-window.tpl'),
  _addEvents: function(overlay, id) {
    var gm = google.maps;
    var tooltip = GMap._tooltips[id];
    gm.event.addListener(overlay, 'mouseover', function() { tooltip.show(); });
    gm.event.addListener(overlay, 'mouseout', function() { tooltip.hide(); });
    gm.event.addListener(overlay, 'click', function() {
                           GMap.showInfoWindow(id);
                         });
  },
  setData: function(data) {
    $.each(GMap._allIDs, function(i, id) {
             GMap._dots[id].setMap(null);
             if (id in GMap._markers)
               GMap._markers[id].setMap(null);
             GMap._tooltips[id].setMap(null);
             GMap._infoWindows[id].close();
           });

    GMap._markers = {};
    GMap._dots = {};
    GMap._tooltips = {};
    GMap._infoWindows = {};
    GMap._allIDs = [];
    $.each(data.result, function(i, d) {
             var gm = google.maps;
             GMap._allIDs.push(d.id);
             var dot = GMap._dots[d.id] = new gm.Marker(
               $.extend({ position: new gm.LatLng(d.lat, d.lng),
                          map: GMap.map,
                          zIndex: 0
                        }, GMap.createDot(d.score)));
             GMap._tooltips[d.id]
               = new Tooltip(dot, GMap._tooltipTmpl.get(d), 5, GMap.map);
             GMap._infoWindows[d.id] =
               new gm.InfoWindow({ content: GMap._infoWindowTmpl.get(d) });
             GMap._addEvents(dot, d.id);
           });
  },
  setMarker: function(data) {
    $.each(GMap._allIDs, function(i, id) {
             if (id in GMap._markers)
               GMap._markers[id].setMap(null);
           });
    GMap._markers = {};
    GMap.showInfoWindow(null);
    $.each(data, function(i, d) {
             var gm = google.maps;
             var marker = GMap._markers[d.id] = new gm.Marker(
               $.extend({ position: new gm.LatLng(d.lat, d.lng),
                          map: GMap.map,
                          zIndex: 100
                        }, GMap.createIcon(d.score, num2alph(i))));
             GMap._addEvents(marker, d.id);
           });
  },
  _geocoder: new google.maps.Geocoder(),
  goToAddr: function(address, callback) {
    GMap._geocoder.geocode(
      { address: address },
      function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          GMap.map.fitBounds(results[0].geometry.viewport);
          callback(true, results);
        } else {
          alert("住所が見つかりませんでした：" + status);
          callback(false, results);
        }
      });
  },
  showInfoWindow: function(id) {
    $.each(GMap._infoWindows, function(i, w) { w.close(); });
    if (id in GMap._infoWindows)
      GMap._infoWindows[id].open(GMap.map, GMap._markers[id] || GMap._dots[id]);
  }
};

var TextBox = function(id) {
  this.id = id;
};
TextBox.prototype = {
  element: null,
  button: null,
  form: null,
  init: function() {
    this.element = $('#' + this.id).setEmptyMessage('empty');
    this.button = $('#' + this.id + '-button');
    this.form = $('#' + this.id + '-form');
    this._allElems = $('#%%, #%%-button, #%%-form'.replace(/%%/g, this.id));
    var self = this;
    this.form.submit(function() {
                       self.submit(self.val());
                       return false;
                     });
    $(window).unload(function() { self.enable(); });
  },
  submit: function(val) {},
  val: function() {
    if (this.element === null || this.element.hasClass('empty'))
      return '';
    return this.element.val();
  },
  reset: function() { this.element.val('').change().blur(); },
  enable: function() { this._allElems.removeAttr('disabled'); },
  disable: function() { this._allElems.attr('disabled', 'disabled'); }
};

var SearchForm = {
  searchBox: new TextBox('search'),
  _dcf: null,
  _dcfTmpl: $.createTemplateURL('templates/detail-condition-form.tpl'),
  _condTypes: null,
  init: function() {
    this.searchBox.init();
    var self = this;
    this.searchBox.submit = function(term) { self.submit(term); };
    this._dcf = $('#detail-condition-form');
    $('#detail-condition-link').click(
      function() {
        var $$ = $(this);
        if (self._dcf.is(':hidden')) {
          var o = $$.offset(), top = (o.top + $$.height() + 3);
          self._dcf.css({ top: top,
                          left: (o.left + $$.width() - self._dcf.width())
                  }).slideDown('fast');
          var getMH = function() {
            return $(window).height() - top
              - (self._dcf.innerHeight() - self._dcf.height())
              - $('#detail-condition-header').height()
              - 10;
          };
          if (self._condTypes == null) {
            $.getJSON('cond-type.json', function(data) {
                        self._condTypes = data;
                        self._createDCForm();
                        $('dl', self._dcf).css({ maxHeight: getMH() });
                      });
          } else {
            self._updateDCForm();
            $('dl', self._dcf).css({ maxHeight: getMH() });
          }
        } else {
          self._dcf.slideUp('fast');
        }
      });
  },
  _createDCForm: function() {
    this._dcf.html(this._dcfTmpl.get(this._condTypes.conditions));
    $('#detail-condition-complete-link').click(
      function() {
        $('#detail-condition-link').click();
        return false;
      });
    var dl = this._dcf.children('dl');
    $('dd:not(:first)', dl).hide();
    $('dt a', dl).click(function() {
                          var e = $(this).parent().next();
                          if (e.is(':visible')) {
                            e.slideUp('fast');
                          } else {
                            $('dd', dl).slideUp('fast');
                            e.slideDown('fast');
                          }
                          return false;
                        });
    // $('dt a', dl).click(function() {
    //                       var e = $(this).parent().next();
    //                       if (e.is(':hidden'))
    //                         e.slideDown('fast');
    //                       else
    //                         e.slideUp('fast');
    //                       return false;
    //                     });
    this._updateDCForm();
  },
  _updateDCForm: function() {

  },
  submit: function(term) {
    if (term == '')
      return;
    var center = GMap.map.getCenter();
    this.disable();
    var self = this;
    $.ajax({ type: 'post',
             url: 'response.php',
             dataType: 'json',
             cache: true,
             data: {
               searchTerm: term,
               lat: center.lat(),
               lng: center.lng()
             },
             success: function(data) {
               Layout.layout.open('west');
               GMap.setData(data);
               List.setData(data);
               self.enable();
             },
             error: function() { self.enable(); }
           });
  },
  enable: function() { this.searchBox.enable(); },
  disable: function() { this.searchBox.disable(); }
};

var LocationBox = new TextBox('location');
LocationBox.submit = function(val) {
  if (val == '')
    return;
  this.disable();
  var self = this;
  GMap.goToAddr(
    val,
    function(success, results) {
      if (success) self.reset();
      else self.element.focus();
      self.enable();
    });
};


$(function() {
    Layout.init();
    List.init();
    SearchForm.init();
    LocationBox.init();
    GMap.init($('#map'));

    // var marker = new google.maps.Marker(
    //   $.extend({ position: GMap.defaultLatLng,
    //              map: GMap.map,
    //              zIndex: 100
    //            }, GMap.createIcon('red', 'A')));
  });


// インプットボックスにグレーのメッセージを出す処理
// http://semooh.jp/jquery/ref/cont/text_hint/
// http://www.maro-z.com/archives/389
(function($) {
   $.fn.setEmptyMessage = function(c) {
     return this.each(
       function() {
         var $$ = $(this);
         $$.blur(function() { if ($$.hasClass(c)) $$.val($$.attr('title')); })
           .focus(function() { if ($$.hasClass(c)) $$.val(''); })
           .change(function() { $$.toggleClass(c, $$.val() == ''); })
           .change().blur();
         $(window).unload(function() { if ($$.hasClass(c)) $$.val(''); });
       });
   };
 })(jQuery);

