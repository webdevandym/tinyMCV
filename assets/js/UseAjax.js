var putData = function (clb) {

  // get customer
  HttpRequest.runQuery(paths.get, {
      method: 'getProject',
      data: {
        query: 'Customer'
      }
    }, (data) => {
      $('#customerID')
        .html(data);
    })
    .done(function () {
      var ProjectItems = ['#projectID', '#objectType'];

      for (let i = 0; i < ProjectItems.length; i++) {

        $(ProjectItems[i])
          .change(function () {
            getObjName()
          })
      };

      $('#projectID')
        .getPjName(document.getElementById("customerID")
          .value)
    })


  if ($('#jobType')) {

    HttpRequest.runQuery(paths.get, {
      method: 'getJobType',
    }, (data) => {
      $('#jobType').html(data);
    })

  }


  HttpRequest.runQuery(paths.get, {
      getUserName: {
        id: "programmerName"
      },
      getObjectType: {
        method: 'getObjectType',
        id: "objectType"
      }
    }, function (data) {
      getData4JSON(data);
    })
    .done(() => {
      getObjName(true, null, null, () => {

        $('body')
          .addClass('loaded')
        if (typeof clb === 'function') {
          clb();
        }
      })
    })
}

log('STATUS: Ajax request file loaded ...');

let getData4JSON = function (data) {

  $.each((function () {
    try {
      let res = JSON.parse(data)
      return res
    } catch (e) {
      return data
    }
  })(), (it, v) => {
    // console.log(v);
    if (v.val) {

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
    }
  });
};


Object.prototype.getPjName = function (name, editor) {

  let _this = this;

  HttpRequest.runQuery(paths.get, {
      method: 'getProject',
      data: {
        query: name
      }
    }, (data) => {

      $(_this)
        .html(data);
    })
    .done(function () {

      if (document.getElementById('jobType')) {
        let transfVal = 0,
          obj = 0;

        if (editor) {
          transfVal = trval || {
              name: $('#name option:selected')
                .val(),
              type: $('#objectType_m option:selected')
                .val()
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
