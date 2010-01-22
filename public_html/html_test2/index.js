$.spin.imageBasePath = '/resource/image/spin1/';

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
    List._pageTmpl.setParam('category', data.category);
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
    GMap._infoWindowTmpl.setParam('category', data.category);
    $.each(data.result, function(i, d) {
             var gm = google.maps;
             GMap._allIDs.push(d.id);
             var dot = GMap._dots[d.id] = new gm.Marker(
               $.extend({ position: new gm.LatLng(d.lat, d.lng),
                          map: GMap.map,
                          zIndex: 0
                        }, GMap.createDot(d.score.color)));
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
                        }, GMap.createIcon(d.score.color, num2alph(i))));
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

var ConditionForm = function(id) {
  this.id = id;
  this._tmpl = $.createTemplateURL('templates/' + id + '-form.tpl');
  this._condURL = id + '-type.json';
};
ConditionForm.prototype = {
  form: null,
  _tmpl: null,
  _condURL: null,
  created: false,
  init: function() {
    this.form = $('#' + this.id + '-form');
  },
  _createContent: function(data) {
    this.created = true;
    this.form.html(this._tmpl.get(data));
    $('input.spin', this.form).spin();
    var dl = this.form.children('dl');
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
    this.show();
  },
  show: function() {
    if (!this.created) {
      var self = this;
      JSONLoader.addHandler(this._condURL, function(data) {
                              self._createContent(data);
                            });
    }
    if (this._setData)
      this.setValue(this._setData);
    this.form.show();
  },
  hide: function() { this.form.hide(); },
  setValue: function(data) {
    var form = this.form;
    this._setData = data;
    form[0].reset();
    $.each(data, function(name, value) {
             $('input[name="'+name+'"]', form).val([value]);
           });
  },
  serialize: function() { return this.form.serialize(); }
};

var SearchForm = {
  searchBox: new TextBox('search'),
  dcf: new ConditionForm('detail-condition'),
  acf: new ConditionForm('additional-condition'),
  init: function(personalData) {
    this._personalData = personalData;
    var pdHash = this._phHash = {};
    $(this._personalData).each(function (i, d) { pdHash[d.name] = d.values; });
    this.searchBox.init();
    this.searchBox.submit = function(term) { self.submit(term); };
    this.dcf.init();
    this.acf.init();

    var self = this;
    var scn = $('#search-condition-name').append(
      $.map(this._personalData, function(d) {
            return new Option(d.title, d.name);
            })).change(
              function() {
                var $$ = $(this);
                if ($$.val() in pdHash) {
                  self.acf.setValue(pdHash[$$.val()]);
                }
              }).change();
    $([this.dcf.form[0], this.acf.form[0]]).change(
      function() {
        var $$ = $(this);
        if (!(custom_name in pdHash)) {
          scn.append(new Option('カスタム', custom_name));
        }
        scn.val([custom_name]);
        pdHash[custom_name] = {};
        $($$.serializeArray()).each(function(i, o) {
                                 pdHash[custom_name][o.name] = o.value;
                               });
      });
    this._sc = $('#search-condition');
    this._scl = $('#search-condition-link')
      .one('click', function() {
              self.acf.show();
              self.dcf.hide();
            })
      .click(
        function() {
          if (self._sc.is(':hidden')) {
            self._sc.slideDown('fast');
            self._updateSCPosition();
          } else {
            self._sc.slideUp('fast');
          }
        });
    $(window).resize(function() { self._updateSCPosition(); });
    var custom_name = 'custom';
    $('#search-condition-complete-button, #search-condition-close-link').click(
      function() {
        self._scl.click();
        return false;
      });
    var detailFlag = false;
    $('#search-condition-detail-button').click(
      function() {
        var $$ = $(this);
        if (detailFlag) {
          if (!confirm('簡易設定モードに移行すると，詳細設定で設定した内容が消えてしまいます。\n簡易設定モードに移行しますか？'))
            return false;
          self.acf.show();
          self.dcf.hide();
          $$.text('さらに詳細な条件を指定');
          $('#search-status').text('(簡易検索)');
        } else {
          self.acf.hide();
          self.dcf.show();
          $$.text('簡易条件指定に切り替える');
          $('#search-status').text('(詳細検索)');
        }
        detailFlag = !detailFlag;
        return false;
      }
    );
  },
  _updateSCPosition: function() {
    var o = this._scl.offset(), top = o.top + this._scl.height() + 3;
    this._sc.css({ top: top,
                   left: (o.left + this._scl.width() - this._sc.width()) });
    var mh = $(window).height() - top
      - (this._sc.innerHeight() - this._sc.height())
      - $('#search-condition-header').height()
      - $('#search-condition-footer').height()
      - 20;
    $('#search-condition-forms').css({ maxHeight: mh });
  },
  submit: function(term) {
    if (term == '')
      return;
    var center = GMap.map.getCenter();
    this.disable();
    if (this._sc !== null && this._sc.is(':not(:hidden)'))
      this._sc.slideUp('fast');

    var self = this;
    $.ajax({ type: 'post',
             url: 'response.php',
             dataType: 'json',
             cache: true,
             data: ['searchTerm=' + encodeURIComponent(term),
                    'lat=' + encodeURIComponent(center.lat()),
                    'lng=' + encodeURIComponent(center.lng()),
                    this.dcf.serialize()
                   ].join('&'),
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

var LoginMessage = {
  _data: null,
  _messageTmpl: $.createTemplateURL('templates/login-message.tpl'),
  init: function(data) {
    $('#login-message').html(this._messageTmpl.get(this._data = data));
  }
};

JSONLoader.preload('login_dummy.php', '/account/personal.php');

$(function() {
    Layout.init();
    List.init();
    JSONLoader.addHandler('/account/personal.php', function(data) {
                            SearchForm.init(data);
                          });
    LocationBox.init();
    GMap.init($('#map'));
    JSONLoader.addHandler('login_dummy.php', function(data) {
                            LoginMessage.init(data); });
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

