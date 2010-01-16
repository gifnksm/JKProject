// usage
// JSONLoader.addHander('hoge.json', callback)
// JSONLoader.addHander(['hoge.json', 'hage.json'], callback)
// JSONLoader.addHander([['hoge.json', false], ['moge.json', true]], callback)
// JSONLoader.addHander([['hoge.json', false]], callback)

var JSONLoader = {
  _uniqueID: 0,
  _loading: {},                 // url: bool => false: loading, true: loaded
  _files: {},                   // id: data
  _callbacks: [],               // {depends: [id], fun: callback}
  _checkLoaded: function() {
    var self = this;
    this._callbacks = $.map(
      this._callbacks,
      function(obj) {
        var l = obj.depends.length, arg = new Array(l);
        for (var i = 0; i < l; i++) {
          var id = obj.depends[i];
          if (!self._loading[id]) // if loading
            return obj;
          arg[i] = self._files[id];
        }
        // all dependents are loaded
        if (typeof obj.callback == 'function')
          obj.callback.apply(null, arg);
        return [];
      });
  },
  preload: function(urls) {
    if (arguments.length == 1)
      this.addHandler(urls, null);
    else
      this.addHandler(Array.prototype.slice.apply(arguments), null);
  },
  addHandler: function(depends, callback) {
    if (!$.isArray(depends))
      depends = [depends];
    var self = this;
    var ids = $.map(depends,
           function(url) {
             var useCache = true;
             if ($.isArray(url)) {
               useCache = url[1];
               url = url[0];
             }
             var id = (useCache
                       ? 'cache'
                       : 'unique' + (self._uniqueID++))
               + '::' + url;
             if (!(id in self._loading)) {
               self._loading[id] = false;
               $.getJSON(
                 url,
                 function(data) {
                   self._loading[id] = true;
                   self._files[id] = data;
                   self._checkLoaded();
                 });
             }
             return id;
           });
    this._callbacks.push({ depends: ids, callback: callback });
    this._checkLoaded();
  }
};

// for debug
// JSONLoader.addHandler('personal-conf.json',
//                       function(a) { console.log('loaded A', a); });
// JSONLoader.addHandler(['data/0.json', 'personal-conf.json'],
//                       function(a, b) { console.log('loaded B', a, b); });
// JSONLoader.addHandler(['personal-conf.json', 'personal-conf.json'],
//                       function(a, b) { console.log('loaded C', a, b); });
// JSONLoader.addHandler(['personal-conf.json', 'login_dummy.php'],
//                       function(a, b) { console.log('loaded D', a, b); });
// JSONLoader.addHandler(['personal-conf.json', 'login_dummy.php'],
//                       function(a, b) { console.log('loaded E', a, b); });
// JSONLoader.addHandler(['personal-conf.json', ['login_dummy.php', false]],
//                       function(a, b) { console.log('loaded F', a, b); });
// JSONLoader.addHandler(['personal-conf.json', ['login_dummy.php', false]],
//                       function(a, b) { console.log('loaded G', a, b); });
