"use strict";

$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();

  $('[data-toggle="tooltip-image"]').tooltip({
    animated: 'fade',
    placement: 'top',
    html: true
  });

  $("#avatar").change(function () {
    var fData = this.files[0],
      fReader = new FileReader(); // Set Demo realtime
    fReader.onload = function (e) { // console.log(e)
      $('#avatar-img').attr('src', e.target.result);
    }
    fReader.readAsDataURL(fData);
  });

  $(".datepicker").datepicker({
    dateFormat: 'yy-mm-dd',
    numberOfMonths: 2
  });

  $(".krajee-input").fileinput({
    language: "th",
    browseClass: "btn btn-secondary",
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "webp"],
    showPreview: false,
    showRemove: false,
    showUpload: false,
  });

  var dateFormat = "yy-mm-dd",
    from = $(".date-start").datepicker({
      defaultDate: "+1w",
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      numberOfMonths: 1,
    }).on("change", function () {
      to.datepicker("option", "minDate", getDate(this));
    }),
    to = $(".date-end").datepicker({
      defaultDate: "+1w",
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      numberOfMonths: 1,
    });

  function getDate(element) {
    var date;
    try {
      date = $.datepicker.parseDate(dateFormat, element.value);
    } catch (error) {
      date = null;
    }

    return date;
  }


  $('.anchor').on('click', function () {
    if ($(this).next('.items').is(":hidden")) {
      $(this).next('.items').show();
    } else {
      $(this).next('.items').hide();
    }
  });
});

function selectCheckList(elm, label, default_text) {
  elm.on("click", function () {
    var checkedlx = elm.filter(":checked");
    if (checkedlx.length == 0) {
      label.empty();
      label.append("--- " + default_text + " ---");
    } else {
      label.empty();
      label.append("เลือกแล้ว " + checkedlx.length + " รายการ");
    }
  });
}

var popupwindow = function (url, title, w, h) {
  var left = (screen.width / 2) - (w / 2);
  var top = (screen.height / 2) - (h / 2);

  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
};

// Custom required text field
var elements = document.querySelectorAll("INPUT, SELECT");
for (var i = 0; i < elements.length; i++) {
  elements[i].oninvalid = function (e) {
    e.target.setCustomValidity("");
    if (!e.target.validity.valid) {
      e.target.setCustomValidity("ช่องนี้ไม่สามารถเว้นว่างได้");
    }
  };
  elements[i].oninput = function (e) {
    e.target.setCustomValidity("");
  };
}

