function putData(clb) {



  // get customer
  HttpRequest.runQuery(paths.get, {
      method: 'getProject',
      data: {
        query: 'Customer'
      }
    }, (data) => {
      $('#customerID').html(data);
    })
    .done(function() {
      var ProjectItems = ['#projectID', '#objectType'];

      for (let i = 0; i < ProjectItems.length; i++) {

        $(ProjectItems[i]).change(function() {
          getObjName()
        })
      };

      console.log(document.getElementById("customerID").value);

      $('#projectID').getPjName(document.getElementById("customerID").value)
    })


  if ($('#jobType')) {
    console.log('hi');
    let request = new requireDATA();
    request.runQuery(paths.get, {
      method: 'getJobType',
    }, (data) => {
      $('#jobType').html(data);
    })

  }

  //
  //     HttpRequest.runQuery(paths.main, {
  //         getUserName: {
  //           id: "programmerName"
  //         },
  //         getObjectType: {
  //           id: "objectType"
  //         }
  //       }, function() {
  //         getData4JSON();
  //       })
  //       .done(() => {
  //         getObjName(true, null, null, () => {
  //
  //           $('body').addClass('loaded')
  //           if (typeof clb === 'function') {
  //             clb();
  //           }
  //         })
  //       })
  //
  //   })
}

console.log('STATUS: Ajax request file loaded ...');

let getData4JSON = function(data) {
  $.each(JSON.parse(data), (it, v) => {

    if (v.condition != undefined && !v.condition) return;

    let selector = v.id != undefined ? '#' + v.id : '.' + v.class,
      store = '';

    if (v.val instanceof Array) {
      $.each(v.val, (item, val) => {
        if (val != '') store += val;
      })
    }

    store = store || v.val;

    $(selector).html(store.replace(/^ | $/, ''))

  })
}


Object.prototype.getPjName = function(name, editor) {

  let _this = this;

  HttpRequest.runQuery(paths.get, {
      method: 'getProject',
      data: {
        query: name
      }
    }, (data) => {
      $(_this).html(data);
    })
    .done(function() {
      if (document.getElementById('jobType')) {
        let transfVal = 0,
          obj = 0;

        if (editor) {
          transfVal = trval || {
              name: $('#name option:selected').val(),
              type: $('#objectType_m option:selected').val()
            },
            obj = "#objNameTS_m";
        };
        getObjName(0, obj, transfVal)
        firstRun = false;
      }
    })

  // $.get(paths.main + 'getProject&object=' + {
  //   query: name
  // }.parsetoJSON(), function(data) {
  //   $(_this).html(data)
  // }).done(function() {
  //   if (document.getElementById('jobType')) {
  //     let transfVal = 0,
  //       obj = 0;
  //
  //     if (editor) {
  //       transfVal = trval || {
  //           name: $('#name option:selected').val(),
  //           type: $('#objectType_m option:selected').val()
  //         },
  //         obj = "#objNameTS_m";
  //     };
  //     getObjName(0, obj, transfVal)
  //     firstRun = false;
  //   }
  // })
}


Object.defineProperty(Object.prototype, 'getPjName', {
  enumerable: false
});
