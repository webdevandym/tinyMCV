'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

function O(i) {
  return (typeof i === 'undefined' ? 'undefined' : _typeof(i)) == 'object' ? i : document.getElementById(i);
}

var paintCanvas = function paintCanvas() {

  var canvas = O('logo'),
      context = canvas.getContext('2d'),
      gradient = context.createLinearGradient(0, 0, 0, 89),
      image = new Image();

  image.src = './assets/image/ico-leaf.png';

  window.onload = function () {

    context.font = 'bold italic 70px "PT Sans"';
    context.textBaseline = 'top';

    gradient.addColorStop(0.00, 'white');
    gradient.addColorStop(0.33, 'rgba(75, 183, 10, 0.8)');
    gradient.addColorStop(.66, '#2ea04d');
    context.fillStyle = gradient;
    context.fillText("QTT Panel", 0, 0);
    context.drawImage(image, 310, 0);
  };
};
//TODO: replace function to object prototype;


function requireDATA() {
  this.stat = $.Deferred();
}

requireDATA.prototype = {
  runQuery: function runQuery(url, obj, f) {
    var _this = this;

    var json = $.parseJSON(JSON.stringify(obj));
    console.log(json);
    $.ajax({
      type: "POST",
      url: url,
      dataType: "JSON",
      data: json,
      error: function error(xhr, b, c) {
        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
      },
      success: function success(data) {
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
var request = new requireDATA();

$('.loginInput').on('blur', function () {
  request.runQuery("Uservalid/Validate", {
    user: encodeURIComponent($('.loginInput').val())
  }, function (data) {
    $('#loginChecker').html(data);
  });
});

$(".loginInput").keypress(function (event) {
  if (event.which == 13 && $(this).val() != '') {
    event.preventDefault();
    checkAndLogin();
  }
});

$('.submitLogin').on('click', function () {
  checkAndLogin();
});

function checkAndLogin() {
  var login = void 0;

  if ((login = encodeURIComponent($('.loginInput').val())) != '') {

    request.runQuery("Uservalid/Validate", {
      user: encodeURIComponent($('.loginInput').val()),
      sendResult: true
    }, function (data) {
      console.log(data);
      if (data != false) {
        location.reload();
      } else {
        $('#errorHolder').html("<span class = 'error'>Please check your domain username</span>");
      }
    });

    // $.ajax({
    //   method: 'GET',
    //   url: './core/loginControler/loginChecker.php?q=' + login + '&sendResult=true',
    //   success: (data) => {
    //
    //   }
    // })
  } else $('#errorHolder').html("<span class = 'error'>Please enter your domain username</span>");
}

paintCanvas();