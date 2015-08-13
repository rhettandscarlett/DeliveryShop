if (!jQuery) {
  throw new Error("Requires jQuery")
}

(function ($) {
  var defaults = {
    topSpacing: 0,
    bottomSpacing: 0,
    className: 'is-sticky',
    wrapperClassName: 'sticky-wrapper',
    center: false,
    getWidthFrom: ''
  },
  $window = $(window),
    $document = $(document),
    sticked = [],
    windowHeight = $window.height(),
    scroller = function () {
      var scrollTop = $window.scrollTop(),
        documentHeight = $document.height(),
        dwh = documentHeight - windowHeight,
        extra = (scrollTop > dwh) ? dwh - scrollTop : 0,
        e_width = $(".page-body").outerWidth();

      for (var i = 0; i < sticked.length; i++) {
        var s = sticked[i],
          elementTop = s.stickyWrapper.offset().top,
          etse = elementTop - s.topSpacing - extra;

        s.stickyElement.css('width', e_width);
        if (scrollTop <= etse) {
          if (s.currentTop !== null) {
            s.stickyElement
              .css('position', '')
              .css('top', '')
              .css('width', '');
            s.stickyElement.parent().removeClass(s.className);
            s.currentTop = null;
          }
        }
        else {
          var newTop = documentHeight - s.stickyElement.outerHeight()
            - s.topSpacing - s.bottomSpacing - scrollTop - extra;
          if (newTop < 0) {
            newTop = newTop + s.topSpacing;
          } else {
            newTop = s.topSpacing;
          }
          if (s.currentTop != newTop) {
            var newWidth = s.stickyElement.parent().width();
            s.stickyElement
              .css('position', 'fixed')
              .css({'top': newTop,'width': newWidth});

//            if (typeof s.getWidthFrom !== 'undefined') {
//              s.stickyElement.css('width', $(s.getWidthFrom).width());
//            }

            s.stickyElement.parent().addClass(s.className);
            s.currentTop = newTop;
          }
        }
      }
    },
    resizer = function () {
      scroller();
      windowHeight = $window.height();
    },
    methods = {
      init: function (options) {
        var o = $.extend(defaults, options);
        return this.each(function () {
          var stickyElement = $(this);

          var stickyId = stickyElement.attr('id');
          var wrapper = $('<div></div>')
            .attr('id', stickyId + '-sticky-wrapper')
            .addClass(o.wrapperClassName);
          stickyElement.wrapAll(wrapper);

          if (o.center) {
            stickyElement.parent().css({width: stickyElement.outerWidth(), marginLeft: "auto", marginRight: "auto"});
          }

          if (stickyElement.css("float") == "right") {
            stickyElement.css({"float": "none"}).parent().css({"float": "right"});
          }

          var stickyWrapper = stickyElement.parent();
          //stickyWrapper.css('height', stickyElement.outerHeight());
          sticked.push({
            topSpacing: o.topSpacing,
            bottomSpacing: o.bottomSpacing,
            stickyElement: stickyElement,
            currentTop: null,
            stickyWrapper: stickyWrapper,
            className: o.className,
            getWidthFrom: o.getWidthFrom
          });
        });
      },
      update: scroller
    };

  if (window.addEventListener) {
    window.addEventListener('scroll', scroller, false);
    window.addEventListener('resize', resizer, false);
  } else if (window.attachEvent) {
    window.attachEvent('onscroll', scroller);
    window.attachEvent('onresize', resizer);
  }

  $.fn.sticky = function (method) {
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' + method + ' does not exist on jQuery.sticky');
    }
  };
  $(function () {
    setTimeout(scroller, 0);
  });
})(jQuery);

jQuery(function () {
  $("#sidebar a.dropdown-toggle").click(function () {
    var e = $(this).next(".submenu");
    var t = $(this).children(".arrow");
    if (t.hasClass("fa-angle-right")) {
      t.addClass("anim-turn90")
    } else {
      t.addClass("anim-turn-90")
    }
    e.slideToggle(400, function () {
      if ($(this).is(":hidden")) {
        t.attr("class", "arrow fa fa-angle-right")
      } else {
        t.attr("class", "arrow fa fa-angle-down")
      }
      t.removeClass("anim-turn90").removeClass("anim-turn-90")
    })
  });
  $("#sidebar.sidebar-collapsed #sidebar-collapse > i").attr("class", "fa fa-angle-double-right");
  $("#sidebar-collapse").click(function () {
    $("#sidebar").toggleClass("sidebar-collapsed");
    if ($("#sidebar").hasClass("sidebar-collapsed")) {
      $("#sidebar-collapse > i").attr("class", "fa fa-arrow-circle-right");
      //$.cookie("sidebar-collapsed", "true");
      $("#sidebar ul.nav-list").parent(".slimScrollDiv").replaceWith($("#sidebar ul.nav-list"))
    } else {
      $("#sidebar-collapse > i").attr("class", "fa fa-arrow-circle-left");
      //$.cookie("sidebar-collapsed", "false");
    }
  });
  $("#sidebar").on("show.bs.collapse", function () {
    if ($(this).hasClass("sidebar-collapsed")) {
      $(this).removeClass("sidebar-collapsed")
    }
  });
  $("#sidebar .search-form").click(function () {
    $('#sidebar .search-form input[type="text"]').focus()
  });
  $("#sidebar .nav > li.active > a > .arrow").removeClass("fa-angle-right").addClass("fa-angle-down");

  $(".is-pending").click(function (e) {
    e.preventDefault();
    alert("Sorry, this function is not available now.");
  });

  $(".show-tooltip").tooltip({
    container: "body",
    delay: {
      show: 10
    }
  });

  jQuery(".page-body").on("click", ".block .block-tool > button", function (e) {
    if (jQuery(this).data("action") === undefined) {
      return;
    }
    var t = jQuery(this).data("action");
    var n = jQuery(this);
    switch (t) {
      case"collapse":
        jQuery(n).children("i").addClass("anim-turn180");
        jQuery(this).parents(".block").children(".block-body").slideToggle(500, function () {
          if (jQuery(this).is(":hidden")) {
            jQuery(n).children("i").attr("class", "fa fa-chevron-down")
          } else {
            jQuery(n).children("i").attr("class", "fa fa-chevron-up")
          }
        });
        break;
//      case"close":
//        jQuery(this).parents(".block").fadeOut(500, function() {
//          jQuery(this).remove();
//        });
    }
    e.preventDefault();
  });

  $("#page-title").sticky({topSpacing: 78});

  if (jQuery().sf_radio_checkbox) {
    $("input[type=checkbox], input[type=radio]").sf_radio_checkbox();
  }

  if ($('#UserModelEmail')) {
    if ($('#UserModelEmail').val() === '') {
      $('#UserModelEmail').focus();
    } else {
      $('#UserModelPassword').focus();
    }
  }

  if ($('#UserAdminEmail')) {
    if ($('#UserAdminEmail').val() === '') {
      $('#UserAdminEmail').focus();
    } else {
      $('#UserAdminPassword').focus();
    }
  }

  $.ajaxSetup({
    statusCode: {
      401: function () {
        // Redirec the to the login page.
        window.location = '/user/account/login';

      },
      403: function () {
        // 403 -- Access denied
        window.location = '/user/account/login';
      }
    }
  });

  $(".navigation-toggle a").click(function (a) {
    a.preventDefault();
    var t = e(".canvas");
    t.hasClass("nav-active") ? e(".canvas").removeClass("nav-active") : e("html, body").animate({
      scrollTop: "0px"
    }, function () {
      e(".canvas").addClass("nav-active")
    })
  });

  initTree();
});

function show_msg(title, message) {
  $.gritter.add({
    title: title,
    text: message,
    sticky: false,
    time: 3000,
    position: 'top-left',
    class_name: "my-sticky-class"
  });
}

function callbackMenu() {
  $("#WindowshopMenuType").on("change", function () {
    var el = $(this);
    if (el.val() == 1) {
      $("#WindowshopMenuPage").closest('.form-group').show();
      $("#WindowshopMenuData").closest('.form-group').hide();
    } else {
      $("#WindowshopMenuPage").closest('.form-group').hide();
      $("#WindowshopMenuData").closest('.form-group').show();
    }
  });

  $("#WindowshopMenuType").trigger('change');

  $("#WindowshopMenuPage").on("change", function () {
    $("#WindowshopMenuData").val($(this).val());
  });
}

var tree_id = "sf-tree-menu-20140903";
function storeTree(t) {
  if (typeof (Storage) !== "undefined") {
    var tree = localStorage.getItem(tree_id),
      node_id = t.data('id') + "",
      node_idx = -1;

    if (tree) {
      tree = tree.split(",");
      node_idx = tree.indexOf(node_id)
    } else {
      tree = [];
    }

    if (t.hasClass("fa-plus-square")) {
      if (node_idx < 0) {
        tree.push(node_id);
        localStorage.setItem(tree_id, tree.toString());
      }
    } else {
      if (node_idx > -1) {
        tree.splice(node_idx, 1);
        localStorage.setItem(tree_id, tree.toString());
      }
    }
  }
}

function initTree() {
  $('.tree li:has(ul)').addClass('parent_li');
  $('#menus').on('click', '.tree li.parent_li span i', function (e) {
    var children = $(this).closest('li.parent_li').find(' > ul > li');
    if (children.is(":visible")) {
      children.hide('fast');
      $(this).addClass('fa-plus-square').removeClass('fa-minus-square');
    } else {
      children.show('fast');
      $(this).addClass('fa-minus-square').removeClass('fa-plus-square');
    }
    storeTree($(this));
    e.stopPropagation();
  });
  $('#menus').on('click', '.tree li.parent_li span a', function (e) {
    e.preventDefault();
    var ela = $(this),
      url = ela.prop('href'),
      options = {
        type: 'htmlUpdate',
        source: [{"path": ".block-body", "element": 1}, {"path": ".col-md-6", "element": 1}],
//        silence: true,
        callback: function () {
          callbackMenu();
        }
      };
    ajaxSendData(url, '', options);
  });

  if (typeof (Storage) !== "undefined") {
    var tree = localStorage.getItem(tree_id);
    if (tree) {
      tree = tree.split(",");
      if ($.isArray(tree)) {
        $.each(tree, function (idx, node_id) {
          var e = $(".tree i[data-id=" + node_id + "]"),
            children = e.closest('li.parent_li').find(' > ul > li');
          e.addClass('fa-plus-square').removeClass('fa-minus-square');
          children.hide();
        });
      }
    }
  }
}

;
var headerH = 0;
$(document).ready(function () {
  headerH = $('.main-header').height();
  $('.prddt-thumb a').click(function (event) {
    var nsrc = $(this).attr('href');
    $('.prddt-img img').fadeTo(200, 0.2, function () {
      $(this).attr('src', nsrc).fadeTo(300, 1);
    });
    $('.prddt-thumb a').removeClass('active');
    $(this).addClass('active');
    return false;
  });

  $('.prod-cat').on('click', '.config-show-detail',function (event) {
    $('.config-cart-detail').slideDown(300);
    $('.menu-mask').show();
    return false;
  });


  var topmenu_ac = false;
  $('.topbar').click(function (event) {
    if ($(window).width() < 767) {
      $(this).find('.menu').slideToggle(300);
      $('.menu-mask').toggle(200);
      topmenu_ac = true;
    }
  });


  $('.menu-mask').click(function () {
    $('.config-cart-detail').slideUp(300);
    if (topmenu_ac) {
      topmenu_ac = false;
      $('.topbar .menu').slideUp(300);
    }
    $('.menu-mask').hide();
  });

//  $('.hastip').each(function () {
//    $(this).qtip({
//      content: {
//        text: $(this).next('div')
//      },
//      style: 'qtip-bootstrap',
//      position: {
//        my: 'center left', // Position my top left...
//        at: 'center right', // at the bottom right of...
//        adjust: {
//          x: 10
//        }
//      }
//    });
//  });

  $('.nav-tabs a').click(function (event) {
    var nHref = $(this).attr('href');
    $('.nav-tabs li').removeClass('active');
    $('.tab-pane').removeClass('active');
    $(nHref).addClass('active').fadeIn(300);
    $(this).parent().addClass('active');
    return false;
  });

});
$(window).load(function () {

  headerH = $('.main-header').height();

  var prdH = 0;
  var prdImg = 0;
  $('.product-item').each(function () {
    prdImg = Math.max(prdImg, $(this).find('.prd-img').height());
  });
  $('.product-item .prd-img').height(prdImg);
  $('.product-item').each(function () {
    prdH = Math.max(prdH, $(this).height());
  });
  $('.product-item').height(prdH);


  if ($('.config-preview').height() < $('.config-wrap').height()) {
    $('.config-preview').height($('.config-wrap').height() - 40);
  }
});
$(window).scroll(function () {
  var crrTop = $(window).scrollTop();
  if (crrTop > headerH) {
    $('.mainnav').addClass('fixtop');
  } else {
    $('.mainnav').removeClass('fixtop');
  }
});


