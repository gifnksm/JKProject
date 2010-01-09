function Tooltip(marker, content, padding, map) {
  this._marker = marker;
  this._content = content;
  this._padding = padding;
  this._map = map;
  this._div = null;

  this.setMap(map);
}

Tooltip.prototype = new google.maps.OverlayView();
Tooltip.prototype.onAdd = function() {
  var div = document.createElement('div');
  div.className = 'tooltip';
  div.style.position = 'absolute';
  div.style.visibility = 'hidden';
  div.style.zIndex = 1000;
  if (Object.prototype.toString.call(this._content) == '[object String]')
    div.innerHTML = this._content;
  else
    div.appendChild(this._content);
  this._div = div;

  var panes = this.getPanes();
  panes.overlayImage.appendChild(div);
};
Tooltip.prototype.draw = function() {
  var overlayProjection = this.getProjection();
  var div = this._div;
  var markerPos = overlayProjection.fromLatLngToDivPixel(this._marker.getPosition());
  var xPos = markerPos.x;
  var yPos = markerPos.y;
  this._div.style.top = yPos + this._padding + 'px';
  this._div.style.left = xPos + this._padding + 'px';
};
Tooltip.prototype.onRemove = function() {
  this._div.parentNode.removeChild(this._div);
  this._div = null;
};
Tooltip.prototype.hide = function() {
  if (this._div) this._div.style.visibility = 'hidden';
};
Tooltip.prototype.show = function() {
  if (this._div) this._div.style.visibility = 'visible';
};
Tooltip.prototype.toggle = function() {
  if (this._div) {
    if (this._div.style.visibility == 'hidden')
      this._div.style.visibility = 'visible';
    else
      this._div.style.visibility = 'hidden';
  }
};
Tooltip.prototype.toggleDOM = function() {
  if (this.getMap())
    this.setMap(null);
  else
    this.setMap(this._map);
};
