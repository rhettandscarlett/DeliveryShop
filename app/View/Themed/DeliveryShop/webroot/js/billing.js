/**
 * Created At 8/9/15.
 */
$(document).ready(function() {
  BillingTime.launchDateTimeFormat();
});

var BillingTime = {
  launchDateTimeFormat: function () {
    var SOne = $('#datetimepicker1');
    var pickedUpDate = SOne.find('input').attr('data-value');
    SOne.datetimepicker({
      format: 'DD/MM/YYYY'
    }).data("DateTimePicker").setDate(pickedUpDate);
  }
};