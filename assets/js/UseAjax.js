function putData(clb) {

  // get customer
  HttpRequest.runQuery(paths.get, {
    method: 'getProject',
    val: {
      query: 'Customer'
    }
  }, (data) => {
    console.log(data);
  });


  //
  // $.get(paths.main + 'getProject&object=' + {
  //     query: 'Customer'
  //   }.parsetoJSON(), function(data) {
  //     $('#customerID').html(data)
  //   })
  //   // get Project and Users
  //   .done(() => {
  //
  //     var ProjectItems = ['#projectID', '#objectType'];
  //
  //     for (let i = 0; i < ProjectItems.length; i++) {
  //
  //       $(ProjectItems[i]).change(function() {
  //         getObjName()
  //       })
  //     };
  //
  //     $('#projectID').getPjName(document.getElementById("customerID").value)
  //
  //     let jobQuery = function() {
  //       if ($('#jobType')) {
  //         $.get(paths.reportEditor + 'getJobType', function(data) {
  //           $('#jobType').html(data);
  //         })
  //       }
  //     }
  //     jobQuery()
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

console.log('Ajax Loaded ...');

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
  let path = "./web/forms",

    _this = this;

  $.get(paths.main + 'getProject&object=' + {
    query: name
  }.parsetoJSON(), function(data) {
    $(_this).html(data)
  }).done(function() {
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
}


Object.defineProperty(Object.prototype, 'getPjName', {
  enumerable: false
});
