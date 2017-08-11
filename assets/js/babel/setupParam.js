'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var paths = {
  get: 'Query/getdata',
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
    var _this = this;
    console.log(json);
    return $.ajax({
      type: "POST",
      url: url,
      // dataType: "JSON",
      data: json,
      error: function error(xhr, b, c) {
        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
      },
      success: function success(data) {
        // cons ole.log(data);
        if (typeof f == 'function') {
          f(_this.IsJsonString(data) ? JSON.parse(data) : data);
        }
        _this.stat.resolve('and');
        return _this;
      }

    });

    // return (function(data) {
    //   if (typeof f == 'function') {
    //     f(_this.IsJsonString(data) ? JSON.parse(data) : data)
    //     return _this
    //   }
    // })();
  },

  done: function done(f) {
    var _this = this;
    return this.stat.promise().done(function () {
      f();
      _this.stat.resolve('and');
      return _this;
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