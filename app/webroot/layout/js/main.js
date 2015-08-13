var slideTotal = 0, slideCurrent = 0, slideTimer, fix_window;
$(document).ready(function () {
  isIE = false;

  var isiPad = (navigator.userAgent.match(/iPad/i) != null);


  $('.main-menu>li').mouseenter(function(){
    $('.menu-overlay').css('display','block');
  }).mouseleave(function(){
    $('.menu-overlay').css('display','none');
  })

  $('.banner-box').mouseenter(function(event) {
		var bgLink =  $(this).index() ;
		$('.main-banner-bg .bg').removeClass('active').fadeOut(600).eq(bgLink).stop(true,true).addClass('active').fadeIn(800);
	});

  $('.prddt-thumb a').click(function (event) {
    var nsrc = $(this).attr('href');
    $('.prddt-imgbig img').fadeTo(200, 0.2, function () {
      $(this).attr('src', nsrc).fadeTo(300, 1);
    });
    $('.prddt-thumb a').removeClass('active');
    $(this).addClass('active');
    return false;
  });

  $('.hastip').click(function(event) {
  	var leftpos = $(this).position().left + $(this).width() + 50;
  	$('.info-tip').css({'left':leftpos}).slideToggle(500);
  	$('.menu-mask').toggle()
  });

  $('.menu-mask').click(function(event) {
  	$('.info-tip').slideToggle(500);
  	$('.menu-mask').toggle()
  });
  
});
$(window).load(function(){

	var thumbHeight = 0;
  $('.prddt-thumb a').each(function (i, e) {
    thumbHeight = Math.max($(this).height(), thumbHeight);
  })
  $('.prddt-thumb a').height(thumbHeight);
  
})
$(window).resize(function(){
  
})