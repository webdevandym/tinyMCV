function tracker() {
  this.data = {}
}

tracker.prototype = {
  getData: function (selector) {
    let _this = this


    $(selector).find('*').each(function (index) {
      if ($(this).attr('name') !== undefined) {
        _this.data[$(this).attr('name')] = $(this).val();
      }
    })

    console.log(this.data);
    return this;
  },

  procStartTable() {
    let data = this.data;
    HttpRequest.runQuery(paths.get, {
      method: 'getStartTable',
      data
    }, (data) => {
      console.log(data);
      // $('#tableTracker tbody').html(data);
    })

    return this;
  }
}
