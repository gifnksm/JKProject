var Pager = function(items) {
  this.element = $('<div class="pager">');
  this._callbacks = [];
  if (items === undefined)
    this.updateItems([]);
  else
    this.updateItems(items);
};

Pager.prototype = {
  itemsPerPage: 5,
  _current: 0,
  updateItems: function(items) {
    this._items = items;
    this.element.empty();
    var len = this._items.length,
        plen = Math.ceil(len / this.itemsPerPage),
        p;
    if (plen <= 0)
      plen = 1;

    this._pages = Array(plen);
    this._links = Array(plen);
    var self = this;
    for (p = 0; p < plen; p++) {
      this._pages[p] = this._items.slice(
        this.itemsPerPage * p, this.itemsPerPage*(p+1));

      this._links[p] = $('<a href="javascript: void(0);">').append(p+1);
      (function(l, p) {
        l.click(function() { self.goTo(p); });
       })(this._links[p], p);
      this.element.append(this._links[p]);
      this.element.append(' ');
    }

    this._prevLink = $('<a href="javascript: void(0);">').append('&laquo;');
    this._prevLink.click(function() { self.prev(); });
    this.element.prepend(' ');
    this.element.prepend(this._prevLink);

    this._nextLink = $('<a href="javascript: void(0);">').append('&raquo;');
    this._nextLink.click(function() { self.next(); });
    this.element.append(this._nextLink);
    this.goTo(0);
  },
  _callbacks: null,
  addCallback: function(fun) {
    this._callbacks.push(fun);
  },
  currentItems: function() {
    return this._pages[this._current];
  },
  prev: function() { this.goTo(this._current - 1); },
  next: function() { this.goTo(this._current + 1); },
  goTo: function(page) {
    page >>= 0;
    if (page < 0) {
      page = 0;
    } else if (page >= this._pages.length) {
      page = this._pages.length - 1;
    }
    this._current = page;
    this._prevLink.toggleClass('disabled', page == 0);
    $.each(this._links, function(i, l) { l.toggleClass('highlight', i == page); });
    this._nextLink.toggleClass('disabled', page == this._pages.length - 1);
    $.each(this._callbacks, function(i, f) { f(page); });
  }
};
