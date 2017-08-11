'use strict';


var paths = {
    get: 'Query/getdata',
    reportEditor: './app/Controllers/reportEditeTools.php?method='
  },
  firstRun = true;

Object.prototype.parsetoJSON = function() {
  if (typeof this == 'object') {
    return JSON.stringify(this);
  } else throw 'This val is not Object!';

}

Object.defineProperty(Object.prototype, 'parsetoJSON', {
  enumerable: false
});



function requireDATA() {
  this.stat = $.Deferred()
}

requireDATA.prototype = {
  runQuery: function(url, obj, f) {

    var json = $.parseJSON(JSON.stringify(obj));
    var _this = this;
    // console.log(json);
    return $.ajax({
      type: "POST",
      url: url,
      // dataType: "JSON",
      data: json,
      error: function(xhr, b, c) {
        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
      },
      success: (data) => {
        // console.log(data);
        if (typeof f == 'function') {
          f(_this.IsJsonString(data) ? JSON.parse(data) : data)

        }
        _this.stat.resolve('and')
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


  done: function(f) {
    let _this = this;
    return this.stat.promise().done(() => {
      f();
      _this.stat.resolve('and')
      return _this;
    })

  },

  IsJsonString(str) {
    try {
      JSON.parse(str);
    } catch (e) {
      return false;
    }
    return true;
  }
}

var HttpRequest = new requireDATA();
