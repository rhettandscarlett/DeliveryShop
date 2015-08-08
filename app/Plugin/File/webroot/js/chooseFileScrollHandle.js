/**
 * Created At 6/19/15.
 */

var lastScrollTop = 0;
$(".choose-file-container").scroll(function() {
  var here = $(this);
  var scrollTop = here.scrollTop();
  //scroll up will not add more element
  if (scrollTop < lastScrollTop){
    return;
  }
  lastScrollTop = scrollTop;
  if (FilePlugin.globalVariable.page > FilePlugin.globalVariable.pageCount){
    return;
  }
  //scrolled to end of div
  if(scrollTop + here.height() >= here[0].scrollHeight - 20) {
    FilePlugin.onEventInputFilter(here.data('categorycode'), here.data('id'));
  }
});