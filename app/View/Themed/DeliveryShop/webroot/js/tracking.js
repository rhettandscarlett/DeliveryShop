/**
 * Created At 8/12/15.
 */
$(document).ready(function() {
  $('body').on('click', '.toggleDetail .toggleShow', function() {
    var t = $(this);
    t.parents('.toggleDetail').siblings('.detailHolder').show();
    t.addClass('hidden');
    t.siblings('.toggleHide').removeClass('hidden');
  }).on('click', '.toggleDetail .toggleHide', function() {
    var t = $(this);
    t.parents('.toggleDetail').siblings('.detailHolder').hide();
    t.addClass('hidden');
    t.siblings('.toggleShow').removeClass('hidden');
  });
});