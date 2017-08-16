function returnReportFromDB() {
  let a = [],
    obj = {},
    valList = ['name', 'startDate', 'endDate'];

  if (arguments[0].split(',')[1]) {
    a = arguments[0].split(',')
  } else {
    a = arguments;
  }

  for (let i = 0; i < a.length; i++) {
    obj[valList[i]] = a[i];
  }

  $('#tableReport th').off()
  // console.log(path)
  HttpRequest.runQuery(paths.get, {
    method: 'getReportWeek',
    data: obj
  }, (data) => {
    // console.log(clearSpecSymbol(data), clearSpecSymbol($('#ReportWeek').html()));
    if (clearSpecSymbol(data, true) !== clearSpecSymbol($('#ReportWeek').html())) $('#ReportWeek').html(data)
  }).done(() => {
    if (document.getElementById('tableReport')) {
      $('#multiDelete').css('display', 'initial')
    } else {
      $('#multiDelete').css('display', 'none')
    }
  });




  function clearSpecSymbol(val, spec) {
    val = val.replace(/[^\w]/g, '').toLowerCase();
    if (spec) {
      val = val.replace(/\Bdiv$/, '')
    }

    return val;
  }
}

function todayMonthFromCalend(obj) {
  let monthDay
  obj = obj || $('#table-c td.today');
  if (obj.attr("data-month")) {
    monthDay = parseInt(obj.attr("data-month"));
  } else {
    monthDay = (parseInt($('#calendar-box').attr('data-month')) + 1);
  }


  // console.log(monthDay);
  return monthDay;
}

function monthTotalTime() {
  let month = parseFloat($('#calendar-box').attr('data-month')) + 1,
    dateObj = moment(new Date($('#calendar-box').attr('data-year') + '-' + (month < 10 ? '0' + month : month) + '-' + '01')).endOf('month'),
    start = momentShort(dateObj._i),
    end = momentShort(dateObj._d),
    time,
    monthsFull = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    monthsShort = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

  HttpRequest.runQuery(paths.get, {
    method: 'getTotalHoursOfMonth',
    data: {
      start: start,
      end: end,
      user: $('#programmerName option:selected').attr('idnum')
    }
  }, (data) => {

    $.each(data.days, function (index, value) {

      if ([0, 6].indexOf(new Date(index).getDay()) != -1) {
        data.days[index] += 8;
      }

      data.dayTotal += data.days[index];
    })

    $('#totalHoursofMoth').html('Total hours of ' + monthsFull[month - 1] + ': ' + data.summ + 'h' + (data.dayTotal > 0 ? ' (OverTime: ' + data.dayTotal + 'h)' : ''));
  })



}

function getDateForDB(dateOfWeek, func, skipWeek) {
  $('#table-c td').off()

    !skipWeek && printWeekDate(momentShort(dateOfWeek, 1), momentShort(dateOfWeek, 7), null, false)

  if (typeof func == 'function') {
    func.call();
  }

  $('#table-c td').on('click', function () {
    if ($(this).html() != '-') {

      toggleSelectElemetnTable(true)

      $('#table-c td').removeClass('today');
      $(this).addClass('today')
      let _this = this;
      let month = todayMonthFromCalend();

      let newYear = function (o) {
        let year = parseFloat($('#calendar-box').attr('data-year'));

        if ([12, 1].indexOf(month) !== -1) {

          switch (month) {
          case 12:
            if (o.attr('month') == 'previous') {
              year--;
            }
            break;
          case 1:
            if (o.attr('month') == 'next') {
              year++;
            }
          }

        }

        return year.toString();
      }

      let date = new Date(newYear($(_this)) + "-" + month + "-" + $(this).html()),
        checkedDate = momentShort(date).substring(5),
        curentWeek = $('#weekSEchk').val().match(/\d{2}-\d{2}/g),
        startOfWeek = momentShort(date, 1),
        endOfWeek = momentShort(date, 7),
        userName = $('#programmerName').val(),
        path = "./web/forms";

      if (!(curentWeek[0] <= checkedDate && checkedDate <= curentWeek[1])) {
        printWeekDate(startOfWeek, endOfWeek, null, true)
        // console.log(startOfWeek + endOfWeek)

        returnReportFromDB(userName, startOfWeek, endOfWeek)
      }
    }
  });
}

function getCurentReport(name, date) {
  let dateEdit;

  if (date == 'getCalendar' && $('td.today').text() != '') {
    dateEdit = momentShort(new Date($('#calendar-box').attr('data-year') + " " + todayMonthFromCalend() + " " + $('td.today').html()));
  } else {
    dateEdit = momentShort(new Date());
  }

  if (!name || !dateEdit || name == undefined) throw "Check user login or date. Maybe incorect transfer. Function getCurentReport() file calendar.js"
  // date = new Date('2006-12-27').toJSON().slice(0,10)
  // console.log($('td.today').text() + dateEdit)

  var startOfWeek = momentShort(dateEdit, 1),
    endOfWeek = momentShort(dateEdit, 7),
    userName = name,
    path = "./web/forms";

  returnReportFromDB(userName, startOfWeek, endOfWeek)

};

log('STATUS: Report worker loaded ... ');
