function O(i) {
  return typeof i == 'object' ? i : document.getElementById(i)
}

let paintCanvas = function() {

  var canvas = O('logo'),
    context = canvas.getContext('2d'),
    gradient = context.createLinearGradient(0, 0, 0, 89),
    image = new Image();

  image.src = './assets/image/ico-leaf.png'

  window.onload = function() {

    context.font = 'bold italic 70px "PT Sans"'
    context.textBaseline = 'top'

    gradient.addColorStop(0.00, 'white')
    gradient.addColorStop(0.33, 'rgba(75, 183, 10, 0.8)')
    gradient.addColorStop(.66, '#2ea04d')
    context.fillStyle = gradient
    context.fillText("QTT Panel", 0, 0)
    context.drawImage(image, 310, 0)
  }
}



function requireDATA() {
  this.stat = $.Deferred()
}

requireDATA.prototype = {
  runQuery: function(url, obj, f) {

    let json = $.parseJSON(JSON.stringify(obj));

    $.ajax({
      type: "POST",
      url: url,
      dataType: "JSON",
      data: json,
      error: function(xhr, b, c) {
        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
      },
      success: (data) => {
        if (typeof f == 'function') {
          f(this.IsJsonString(data) ? JSON.parse(data) : data);
        }
        this.stat.resolve('and')
      }
    })

    return this;
  },


  done: function(f) {
    this.stat.promise().done(() => {
      f();
      return this;
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

let request = new requireDATA();

$('.loginInput').on('blur', function() {
  request.runQuery("Uservalid/Validate", {
      user: encodeURIComponent($('.loginInput').val())
    },
    function(data) {
      $('#loginChecker').html(data)
    });
})

$(".loginInput").keypress(function(event) {
  if (event.which == 13 && $(this).val() != '') {
    event.preventDefault();
    checkAndLogin()
  }
});


$('.submitLogin').on('click', function() {
  checkAndLogin()
})


function checkAndLogin() {
  let login;

  if ((login = encodeURIComponent($('.loginInput').val())) != '') {

    request.runQuery("Uservalid/Validate", {
        user: encodeURIComponent($('.loginInput').val()),
        sendResult: true
      },
      function(data) {
        console.log(data);
        if (data != false) {
          location.reload();
        } else {
          $('#errorHolder').html("<span class = 'error'>Please check your domain username</span>")
        }
      });


  } else $('#errorHolder').html("<span class = 'error'>Please enter your domain username</span>");

}

paintCanvas()
