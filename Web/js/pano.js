function lzw_decode(s) {
    var dict = {};
    var data = (s + "").split("");
    var currChar = data[0];
    var oldPhrase = currChar;
    var out = [currChar];
    var code = 256;
    var phrase;
    for (var i=1; i<data.length; i++) {
        var currCode = data[i].charCodeAt(0);
        if (currCode < 256) {
            phrase = data[i];
        }
        else {
           phrase = dict[currCode] ? dict[currCode] : (oldPhrase + currChar);
        }
        out.push(phrase);
        currChar = phrase.charAt(0);
        dict[code] = oldPhrase + currChar;
        code++;
        oldPhrase = phrase;
    }
    return out.join("");
}

function initialize(alpha) {
  alert(unescape(alpha));
  var point = new google.maps.LatLng(lat,lon);
  var panoramaOptions = {
    position: point,
    pov: {
      heading: 20,
      pitch: 10,
      zoom: 1
    },
    linksControl: false,
    addressControl: false
  };
  var panorama = new  google.maps.StreetViewPanorama(document.getElementById("pano"),panoramaOptions);
  map.setStreetView(panorama);
}
