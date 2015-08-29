/**
 * Created At 8/9/15.
 */
$(document).ready(function() {
  BillingTime.launchDateTimeFormat();
  $('body')
    .on('change', '#DeliBillingRuntimeLocationLocationId', function() {
      var id = $(this).val();
      if (parseInt(id) > 0) {
        $('.selectedHolder').addClass('hidden');
        $('.selectedHolder' + id).removeClass('hidden');
      } else {
        $('.selectedHolder').addClass('hidden');
      }
    })
    .on('change', '.rtProcedureItem', function() {
    var id = $(this).data('id');
    $('.detailHolder' + id).toggleClass('hidden');
  })
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