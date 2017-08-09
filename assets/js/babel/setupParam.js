'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var paths = {
  main: './app/Controllers/mainInfoLoader.php?method=',
  reportEditor: './app/Controllers/reportEditeTools.php?method='
},
    firstRun = true;

Object.prototype.parsetoJSON = function () {
  if (_typeof(this) == 'object') {
    return JSON.stringify(this);
  } else throw 'This val is not Object!';
};

Object.defineProperty(Object.prototype, 'parsetoJSON', {
  enumerable: false
});

function requireDATA() {
  this.stat = $.Deferred();
}

requireDATA.prototype = {
  runQuery: function runQuery(url, obj, f) {

    var json = $.parseJSON(JSON.stringify(obj));
    console.log(json);
    var _this = this;
    $.ajax({
      type: "POST",
      url: url,
      dataType: "JSON",
      data: json,
      error: function error(xhr, b, c) {
        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
      },
      success: function success(data) {
        console.log(data);
        if (typeof f == 'function') {
          f(_this.IsJsonString(data) ? JSON.parse(data) : data);
        }
        _this.stat.resolve('and');
      }
    });

    return this;
  },

  done: function done(f) {
    var _this2 = this;

    this.stat.promise().done(function () {
      f();
      return _this2;
    });
  },

  IsJsonString: function IsJsonString(str) {
    try {
      JSON.parse(str);
    } catch (e) {
      return false;
    }
    return true;
  }
};

var HttpRequest = new requireDATA();

//LZW Compression/Decompression for Strings
var LZW = {
  compress: function compress(uncompressed) {
    "use strict";
    // Build the dictionary.

    var i,
        dictionary = {},
        c,
        wc,
        w = "",
        result = [],
        dictSize = 256;
    for (i = 0; i < 256; i += 1) {
      dictionary[String.fromCharCode(i)] = i;
    }

    for (i = 0; i < uncompressed.length; i += 1) {
      c = uncompressed.charAt(i);
      wc = w + c;
      //Do not use dictionary[wc] because javascript arrays
      //will return values for array['pop'], array['push'] etc
      // if (dictionary[wc]) {
      if (dictionary.hasOwnProperty(wc)) {
        w = wc;
      } else {
        result.push(dictionary[w]);
        // Add wc to the dictionary.
        dictionary[wc] = dictSize++;
        w = String(c);
      }
    }

    // Output the code for w.
    if (w !== "") {
      result.push(dictionary[w]);
    }
    return result;
  },

  decompress: function decompress(compressed) {
    "use strict";
    // Build the dictionary.

    var i,
        dictionary = [],
        w,
        result,
        k,
        entry = "",
        dictSize = 256;
    for (i = 0; i < 256; i += 1) {
      dictionary[i] = String.fromCharCode(i);
    }

    w = String.fromCharCode(compressed[0]);
    result = w;
    for (i = 1; i < compressed.length; i += 1) {
      k = compressed[i];
      if (dictionary[k]) {
        entry = dictionary[k];
      } else {
        if (k === dictSize) {
          entry = w + w.charAt(0);
        } else {
          return null;
        }
      }

      result += entry;

      // Add w+entry[0] to the dictionary.
      dictionary[dictSize++] = w + entry.charAt(0);

      w = entry;
    }
    return result;
  }
};