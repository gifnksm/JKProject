// 無計画にコード足してったらスパゲティーになっちまったぜー
$.spin.imageBasePath = '/resource/image/spin1/';

function num2alph(num) {
  return String.fromCharCode(Number(num) + 'A'.charCodeAt(0));
}

var Layout = {
  westOpener: null,
  westCloser: null,
  layout: null,
  _detailMode: false,
  init: function() {
    Layout.westOpener = $('<a id="west-opener" href="javascript: void(0);"></a>')
      .prependTo($('#map-container .header'));
    Layout.westCloser = $('<a id="west-closer" href="javascript: void(0);"></a>')
      .prependTo('#list-container .header');

    Layout.layout = $('body').layout(Layout.options);
    var self = this;
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
      slideTrigger_open: 'mouseover',
      onopen: function() { Layout.westOpener.hide(); },
      onclose: function() { Layout.westOpener.show(); }
    },
    center: {
      onresize_end: function() { GMap.resize(); },
      minSize: 0
    }
  },
  searchMode: function() {
  },
  detailMode: function() {
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
    GMap.setPageMarker(items);
  }
};

var Detail = {
  element: null,
  map: null,
  _tmpl: $.createTemplateURL('templates/detail.tpl'),
  init: function(map) {
    this.element = $('#detail-content');
    this.map = map;
  },
  show: function(id) {
    var self = this;
    this.element.html('読み込み中…');
    $.ajax({ type: 'post',
             url: 'detail.php',
             dataType: 'json',
             cache: true,
             data: ['id=' + id,
                    SearchForm.sendData
                   ].join('&'),
             success: function(data) {
               self.element.html(self._tmpl.get(data));
             },
             error: function() { alert('詳細情報の読み込みに失敗しました'); }
           });
  },
  parseOpen: function(text) {
    var groups = text.split(/\|(?=<)/);
    if (groups.length == 1)
      return groups[0];
    return '<dl>' +
      $.map(groups, function(g) {
              var first = true;
              return g.replace(/<(.+?)>/g, function(_, s) {
                                 if (first) {
                                   first = false;
                                   return "<dt>" + s + "</dt>";
                                 }
                                 return "<dd>" + s + "</dd>";
                               });
            }).join('') + '</dl>';
  },
  parseBarrier: function(category) {
    function getMessage(arr, value) {
      for (var i = 0; i < arr.length; i++) {
        if (arr[i].length == 1)
          return arr[i][0];
        if (arr[i][0] === value)
          return arr[i][1];
      }
      return undefined;
    }

    var map = this.map;
    var dds = $.map(category.items,
                    function(item) {
                      var arrs = map[item.name];
                      var message = getMessage(arrs[0], item.value);
                      if (item.color && message === undefined)
                        message = getMessage(arrs[1], item.value);
                      if (message === undefined)
                        return undefined;
                      message = message.replace(/%d/g, item.value);
                      return '<dd' + (
                        item.color ? ' class="' + item.color + '"' : ''
                      ) + '>' + message + '</dd>';
                    });
    if (dds.length == 0)
      return '';
    var dt = '<dt>'
      + '<img src="' + category.icon + '" alt="" />'
      + category.title + '</dt>';
    return dt + dds.join('');
  }
};

var GMap = {
  defaultLatLng: new google.maps.LatLng(35.60709019396141,139.6853256225586),
  map: null,
  canvas: null,
  _detailMode: false,
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
    new google.maps.Point(10, 34)   // anchor point
  ),
  _createIcon: function(color, alphabet) {
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
  _createDot: function(color) {
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
  _data: {},
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

    GMap._data = {};
    GMap._markers = {};
    GMap._dots = {};
    GMap._tooltips = {};
    GMap._infoWindows = {};
    GMap._allIDs = [];
    GMap._infoWindowTmpl.setParam('category', data.category);
    $.each(data.result, function(i, d) {
             var gm = google.maps;
             GMap._data[d.id] = d;
             GMap._allIDs.push(d.id);
             var dot = GMap._dots[d.id] = new gm.Marker(
               $.extend({ position: new gm.LatLng(d.lat, d.lng),
                          map: GMap.map,
                          zIndex: 0
                        }, GMap._createDot(d.score.color)));
             GMap._tooltips[d.id]
               = new Tooltip(dot, GMap._tooltipTmpl.get(d), 5, GMap.map);
             GMap._infoWindows[d.id] =
               new gm.InfoWindow({ content: GMap._infoWindowTmpl.get(d) });
             GMap._addEvents(dot, d.id);
           });
  },
  _pageItems: null,
  setPageMarker: function(items) {
    this._pageItems = items;
    if (GMap._detailMode)
      return;
    GMap.setMarker(items, true);
  },
  setMarker: function(data, alpha) {
    $.each(GMap._allIDs, function(i, id) {
             if (id in GMap._markers)
               GMap._markers[id].setMap(null);
           });
    GMap._markers = {};
    GMap.showInfoWindow(null);
    $.each(data.sort(function(d1, d2) { return d2.lat - d1.lat; }),
           function(i, d) {
             var gm = google.maps;
             var marker = GMap._markers[d.id] = new gm.Marker(
               $.extend({ position: new gm.LatLng(d.lat, d.lng),
                          map: GMap.map,
                          zIndex: 100
                        }, GMap._createIcon(d.score.color,
                                            alpha ? num2alph(i) : 'dot')));
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
    if (GMap._detailMode) {
      GMap.detailMode(id);
      return;
    }
    $.each(GMap._infoWindows, function(i, w) { w.close(); });
    if (id in GMap._infoWindows)
      GMap._infoWindows[id].open(GMap.map, GMap._markers[id] || GMap._dots[id]);
  },
  searchMode: function() {
    GMap._detailMode = false;
    GMap.map.setOptions({ disableDefaultUI: false });
    GMap.canvas.animate({ width: '100%', height: '100%' },
                        'fast', function() {
                          google.maps.event.trigger(GMap.map, 'resize');
                        });
    Layout.searchMode();
    if (this._pageItems)
      GMap.setMarker(this._pageItems, true);
  },
  detailMode: function(id) {
    if (!(id in GMap._data))
      return;

    GMap._detailMode = true;
    $.each(GMap._infoWindows, function(i, w) { w.close(); });
    var d = GMap._data[id], map = GMap.map;
    map.setOptions({ disableDefaultUI: true });
    Detail.show(id);
    Layout.detailMode();
    GMap.canvas.animate({ width: 300, height: 200 },
                        'fast', function() {
                          google.maps.event.trigger(GMap.map, 'resize');
                          map.setCenter(new google.maps.LatLng(d.lat, d.lng));
                          GMap.setMarker([d], false);
                        });
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
                       GMap.searchMode();
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
  this._condURL = '/resource/json/' + id + '-type.json';
};
ConditionForm.tmpl =  $.createTemplateURL('templates/condition-form.tpl');
ConditionForm.prototype = {
  form: null,
  _condURL: null,
  created: false,
  init: function() {
    this.form = $('#' + this.id + '-form');
  },
  _createContent: function(data) {
    this.created = true;
    this.form.html(ConditionForm.tmpl.get(data, {type: this.id}));
    $('input.spin', this.form).spin();
    if (this._setData)
      this.setValue(this._setData);
    if (typeof this.afterCreation == 'function')
      this.afterCreation();
  },
  loadData: function(callback) {
    var self = this;
    JSONLoader.addHandler(this._condURL, function(data) {
                            self._createContent(data);
                            setTimeout(callback, 0);
                          });
  },
  show: function(callback) {
    if (!this.created) {
      var self = this;
      this.loadData(function() { self.show(callback); });
      this.form.show();
      return;
    }
    if (this._setData)
      this.setValue(this._setData);
    this.form.show();
    if (typeof callback == 'function')
      callback();
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
  acf: new ConditionForm('additional-condition'),
  dcf: $.extend(new ConditionForm('detail-condition'),
                {afterCreation: function() {
                   var dl = this.form.children('dl');
                   $('dd:not(:first)', dl).hide();
                   $('dt a', dl).click(
                     function() {
                       var dd = $(this).parent().next();
                       if (dd.is(':visible')) {
                         dd.slideUp('fast');
                       } else {
                         $('dd', dl).slideUp('fast'); // コメントアウトで動作変更
                         dd.slideDown('fast');
                       }
                       return false;
                     });
                 }}),
  init: function(personalData, conditionMap) {
    this._personalData = personalData;
    this._conditionMap = conditionMap;
    var pdHash = this._phHash = {};
    $(this._personalData).each(function (i, d) { pdHash[d.name] = d.values; });
    this.searchBox.init();
    this.searchBox.submit = function(term) { self.submit(term); };
    this.acf.init();
    this.dcf.init();

    var self = this;
    this.scn = $('#search-condition-name').append(
      $.map(this._personalData, function(d) {
            return new Option(d.title, d.name);
            })).change(
              function() {
                var $$ = $(this);
                if ($$.val() in pdHash) {
                  self.acf.setValue(pdHash[$$.val()]);
                }
                if (self.dcf.created)
                  self.setDetail();
              }).change();

    $([this.dcf.form[0], this.acf.form[0]]).change(
      function() {
        var $$ = $(this);
        if (!(custom_name in pdHash)) {
          self.scn.append(new Option('カスタム', custom_name));
        }
        self.scn.val([custom_name]);
        pdHash[custom_name] = {};
        $($$.serializeArray()).each(
          function(i, o) {
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
    this.detailFlag = false;
    $('#search-condition-detail-button').click(
      function() {
        var $$ = $(this);
        if (self.detailFlag) {
          if (!confirm('簡易設定モードに移行すると，詳細設定で設定した内容が消えてしまいます。\n簡易設定モードに移行しますか？'))
            return false;
          self.acf.show();
          self.dcf.hide();
          $$.text('さらに詳細な条件を指定');
          $('#search-status').text('(簡易検索)');
        } else {
          self.acf.hide();
          self.dcf.show(function() { self.setDetail(); });
          $$.text('簡易条件指定に切り替える');
          $('#search-status').text('(詳細検索)');
        }
        self.detailFlag = !self.detailFlag;
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
  setDetail: function() {
    var acf = this.acf.form;
    var dcf = this.dcf.form;
    var origData = acf.serializeArray();
    function getVal(key) {
      var objs = $.map(origData, function(i) {
                         return i.name == key ? i : [];
                       });
      if (objs.length == 0)
        return undefined;
      return objs[0].value;
    }
    function setVal(key, val) {
      $('input[name="'+key+'"]', dcf).val([val]);
    }
    $.each(
      this._conditionMap,
      function(i, a) {
        if (!$.isArray(a)) {
          // same name, same value
          setVal(a, getVal(a));
          return;
        }
        var key = a[0], val, sets = a[1];
        if ($.isArray(key)) {
          if (getVal(key[0]) != key[1]) {
            return;
          }
          val = key[1];
          key = key[0];
        } else {
          val = getVal(key);
        }
        $.each(sets, function(i, s) {
                 if ($.isArray(s)) {
                   setVal(s[0], s[1]);
                 } else {
                   setVal(s, val);
                 }
               });
      });
  },
  submit: function(term) {
    if (term == '')
      return;
    var center = GMap.map.getCenter();
    var self = this;
    this.disable();
    if (this._sc !== null && this._sc.is(':not(:hidden)'))
      this._sc.slideUp('fast');

    if (this.dcf.created) {
      if (!this.detailFlag)
        this.setDetail();
      sendData();
      return;
    }

    if (this.acf.created) {
      createDCF();
      return;
    }

    this.acf.loadData(createDCF);

    function createDCF() {
      self.dcf.loadData(function() {
                          self.setDetail();
                          sendData();
                        });
    }

    function sendData() {
      self.sendData = self.dcf.form.serialize();
      $.ajax({ type: 'post',
               url: 'response.php',
               dataType: 'json',
               cache: true,
               data: ['searchTerm=' + encodeURIComponent(term),
                      'lat=' + encodeURIComponent(center.lat()),
                      'lng=' + encodeURIComponent(center.lng()),
                      self.sendData
                     ].join('&'),
               success: function(data) {
                 Layout.layout.open('west');
                 GMap.setData(data);
                 List.setData(data);
                 self.enable();
               },
               error: function() { self.enable(); }
             });
    }
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

JSONLoader.preload('login_dummy.php',
                   '/account/personal.php',
                   '/resource/json/condition-map.json',
                   '/resource/json/message-map.json');

$(function() {
    Layout.init();
    List.init();
    JSONLoader.addHandler(['/account/personal.php',
                           '/resource/json/condition-map.json'],
                          function(personal, conditionMap) {
                            SearchForm.init(personal, conditionMap);
                          });
    LocationBox.init();
    JSONLoader.addHandler('/resource/json/message-map.json',
                         function(data) { Detail.init(data); });
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

