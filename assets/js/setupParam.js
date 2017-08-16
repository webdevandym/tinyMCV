'use strict';


var paths = {
    get: 'Query/getdata',
    edit: 'Query/editdata'
  },
  firstRun = true,
  logStatus = true,
  log = function (str) {
    if (logStatus) console.log(str);
  },
  runner = {};

Object.prototype.parsetoJSON = function () {
  if (typeof this == 'object') {
    return JSON.stringify(this);
  } else throw 'This val is not Object!';

}

Object.defineProperty(Object.prototype, 'parsetoJSON', {
  enumerable: false
});

function defferedQuery(queryName, f, wait = 200) {

  clearTimeout(runner.queryName);

  if (typeof f !== 'function') return;
  runner.queryName = setTimeout(function () {
    f();
  }, wait);

}

function requireDATA() {

}

requireDATA.prototype = {
  runQuery: function (url, obj, f) {

    var json = $.parseJSON(JSON.stringify(obj));
    var _this = this;
    // console.log(json);
    return $.ajax({
      type: "POST",
      url: url,
      dataType: "JSON",
      data: json,
      error: function (xhr, b, c) {
        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
      },
      success: (data) => {
        if (typeof f == 'function') {
          f(_this.IsJsonString(data) ? JSON.parse(data) : data)
        }
      }
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

log('STATUS: Setup config complete ...')
