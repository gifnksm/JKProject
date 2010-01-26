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
  maxShowNeighborPage: 3,
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

    this.element.append(' ');
    this.element.append(this._nextLink);

    this._prevDots = $('<span>').append('...')
      .insertAfter(this._links[0]).hide();
    this._nextDots = $('<span>').append('...')
      .insertBefore(this._links[plen-1]).hide();
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
    var lst = this._pages.length - 1;
    page >>= 0;
    if (page < 0) {
      page = 0;
    } else if (page > lst) {
      page = lst;
    }
    this._current = page;
    var minIdx = this._current - this.maxShowNeighborPage,
        maxIdx = this._current + this.maxShowNeighborPage;

    function setVisible(elem, flag) {
      if (flag)
        elem.css({ display: 'inline' });
      else
        elem.hide();
    }

    setVisible(this._prevDots, minIdx > 1);
    setVisible(this._nextDots, maxIdx < lst);
    this._prevLink.toggleClass('disabled', page == 0);
    $.each(this._links, function(i, l) {
             l.toggleClass('highlight', i == page);
             setVisible(l, i == 0 || i == lst
                        || (minIdx <= i && i <= maxIdx));
           });
    this._nextLink.toggleClass('disabled', page == lst);
    $.each(this._callbacks, function(i, f) { f(page); });
  }
};
