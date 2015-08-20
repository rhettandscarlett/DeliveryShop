$(document).ready(function() {
  $('#_filter').change(function() {
    var filterId = $(this).val();
    if (filterId) {
      window.location = sf.curLink + "?filterId=" + filterId;
    } else {
      window.location = sf.curLink;
    }

  });
});