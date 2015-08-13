function base64_encode(data) {
  // http://kevin.vanzonneveld.net
  // +   original by: Tyler Akins (http://rumkin.com)
  // +   improved by: Bayron Guevara
  // +   improved by: Thunder.m
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Pellentesque Malesuada
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Rafał Kukawski (http://kukawski.pl)
  // *     example 1: base64_encode('Kevin van Zonneveld');
  // *     returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
  // mozilla has this native
  // - but breaks in 2.0.0.12!
  //if (typeof this.window['btoa'] == 'function') {
  //    return btoa(data);
  //}
  var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = "",
    tmp_arr = [];

  if (!data) {
    return data;
  }

  do { // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1 << 16 | o2 << 8 | o3;

    h1 = bits >> 18 & 0x3f;
    h2 = bits >> 12 & 0x3f;
    h3 = bits >> 6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  enc = tmp_arr.join('');

  var r = data.length % 3;

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);

}

function base64_decode(data) {
  // http://kevin.vanzonneveld.net
  // +   original by: Tyler Akins (http://rumkin.com)
  // +   improved by: Thunder.m
  // +      input by: Aman Gupta
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Onno Marsman
  // +   bugfixed by: Pellentesque Malesuada
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // *     example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
  // *     returns 1: 'Kevin van Zonneveld'
  // mozilla has this native
  // - but breaks in 2.0.0.12!
  //if (typeof this.window['atob'] == 'function') {
  //    return atob(data);
  //}
  var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    dec = "",
    tmp_arr = [];

  if (!data) {
    return data;
  }

  data += '';

  do { // unpack four hexets into three octets using index points in b64
    h1 = b64.indexOf(data.charAt(i++));
    h2 = b64.indexOf(data.charAt(i++));
    h3 = b64.indexOf(data.charAt(i++));
    h4 = b64.indexOf(data.charAt(i++));

    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

    o1 = bits >> 16 & 0xff;
    o2 = bits >> 8 & 0xff;
    o3 = bits & 0xff;

    if (h3 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1);
    } else if (h4 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1, o2);
    } else {
      tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
    }
  } while (i < data.length);

  dec = tmp_arr.join('');

  return dec;
}

function utf8_decode(str_data) {
  // http://kevin.vanzonneveld.net
  // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // +      input by: Aman Gupta
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Norman "zEh" Fuchs
  // +   bugfixed by: hitwork
  // +   bugfixed by: Onno Marsman
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // *     example 1: utf8_decode('Kevin van Zonneveld');
  // *     returns 1: 'Kevin van Zonneveld'
  var tmp_arr = [], i = 0, ac = 0, c1 = 0, c2 = 0, c3 = 0;
  str_data += '';
  while (i < str_data.length) {
    c1 = str_data.charCodeAt(i);
    if (c1 < 128) {
      tmp_arr[ac++] = String.fromCharCode(c1);
      i++;
    } else if (c1 > 191 && c1 < 224) {
      c2 = str_data.charCodeAt(i + 1);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
      i += 2;
    } else {
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    }
  }
  return tmp_arr.join('');
}

/**
 *  AJAX IFRAME METHOD (AIM)
 *  http://www.webtoolkit.info/
 **/
ajaxSubmitForm = {
  frame: function(c) {
    var n = 'cible_' + Math.floor(Math.random() * 99999);
    var d = document.createElement('DIV');
    d.innerHTML = '<iframe style="display:none" src="about:blank" id="' + n + '" name="' + n + '" onload="ajaxSubmitForm.loaded(\'' + n + '\')"></iframe>';
    document.body.appendChild(d);
    var i = document.getElementById(n);
    if (c && typeof (c.onComplete) == 'function') {
      i.onComplete = c.onComplete;
      i.idform = c.ajaxConfig.idform;
      i.silent = c.ajaxConfig.silent;
      i.ajaxConfig = c.ajaxConfig;
    }
    return n;
  },
  form: function(f, name) {
    f.setAttribute('target', name);
  },
  submit: function(f, c) {
    if ($("input[name='ajaxSubmitFormSendingDataASDF[" + c.ajaxConfig.idform + "]']").length == 0) {
      $(f).append('<input type="hidden" name="ajaxSubmitFormSendingDataASDF[' + c.ajaxConfig.idform + ']" value="1">');
    }
    ajaxSubmitForm.form(f, ajaxSubmitForm.frame(c));
    if (c && typeof (c.onStart) == 'function') {
      return c.onStart(c.ajaxConfig);
    }
    else {
      return true;
    }
  },
  loaded: function(id) {
    var i = document.getElementById(id);
    if (i.contentDocument) {
      var d = i.contentDocument;
    }
    else if (i.contentWindow) {
      var d = i.contentWindow.document;
    }
    else {
      var d = window.frames[id].document;
    }
    if (d.location.href == "about:blank") {
      return true;
    }
    if (typeof (i.onComplete) == 'function') {
      i.onComplete(d.body.innerHTML, i.ajaxConfig);
    }
    var idframe = $(i).attr('id');
    setTimeout('$("#' + idframe + '").parent().remove();', 200);
  }
}

function ajaxPressSubmitForm(object, ajaxConfig) {
  if (ajaxConfig == undefined) {
    ajaxConfig = {};
  }
  ajaxConfig.idform = $(object).attr('id');
  if (ajaxConfig.silent == undefined) {
    ajaxConfig.silent = true;
  }
  return ajaxSubmitForm.submit(object, {'onStart': ajaxStartSubmitForm, 'onComplete': ajaxCompleteSubmitForm, 'ajaxConfig': ajaxConfig});
}

function ajaxStartSubmitForm(ajaxConfig) {
}

function ajaxCompleteSubmitForm(response, ajaxConfig) {
  ajaxProcessResponse(response, ajaxConfig);
}

function ajaxSendData(link, send, option) {
  var headerData = '';
  if (typeof option != "undefined") {
    var silent = (typeof option.silent == "undefined") ? true : option.silent;
    if (silent == false) {
      myLoadingDialog();
    }
    headerData = (typeof option.header == "undefined") ? true : option.header;
  }


  $.ajax({
    url: link,
    type: 'POST',
    data: send,
    beforeSend: function(xhr) {
      xhr.setRequestHeader('SF-Ajax-Header', headerData);
    },
    success: function(response) {
      closeMyLoadingDialog();
      ajaxProcessResponse(response, option);
    }
  });
}

function ajaxProcessResponse(response, option) {
  if (option == undefined) {
    option = {};
  }

  try {
    if (jQuery.type(response) == 'object') {
      var data = response;
    }
    else {
      eval('var data = ' + response);
    }
    option.type = 'json';
  }
  catch (err) {
  }


  if (option.type != undefined && option.type == 'htmlUpdate') {
    var tmpTarget = 'tmpTarget_' + Math.floor(Math.random() * 99999);
    var objectReplace, objectResponse;
    $('body').append('<div id="' + tmpTarget + '" style="display:none"></div>');
    $('#' + tmpTarget).html(response);

    if (option.desc == undefined) {
      option.desc = option.source;
    }

    if (option.source[0] == undefined) {
      if (option.desc.element != undefined) {
        objectReplace = $(option.desc.path)[option.desc.element];
      } else {
        objectReplace = $(option.desc.path);
      }
      if (option.source.element != undefined) {
        objectResponse = $('#' + tmpTarget).find(option.source.path)[option.source.element];
      } else {
        objectResponse = $('#' + tmpTarget).find(option.source.path)[0];
      }
      $(objectReplace).html($(objectResponse).html());
    } else {
      for (var i in option.source) {
        if (option.desc[i].element != undefined) {
          objectReplace = $(option.desc[i].path)[option.desc[i].element];
        } else {
          objectReplace = $(option.desc[i].path);
        }
        if (option.source[i].element != undefined) {
          objectResponse = $('#' + tmpTarget).find(option.source[i].path)[option.source[i].element];
        } else {
          objectResponse = $('#' + tmpTarget).find(option.source[i].path)[0];
        }
        $(objectReplace).html($(objectResponse).html());
      }
    }

    $('#' + tmpTarget).remove();

    if (option.callback != undefined) {
      if (typeof (option.callback) == "function") {
        option.callback();
      } else {
        eval(option.callback);
      }
    }
    return;
  }

  for (var x in data) {
    switch (x) {
      case 'id_display' :
        for (var y in data[x]) {
          data[x][y] = utf8_decode(base64_decode(data[x][y]));
          $('#' + y).html(data[x][y]);
        }
        break;
      case 'id_append' :
        for (var y in data[x]) {
          data[x][y] = utf8_decode(base64_decode(data[x][y]));
          $('#' + y).append(data[x][y]);
        }
        break;
      case 'script' :
        for (var y in data[x]) {
          eval(data[x][y]);
        }
        break;
      case 'location' :
        var link = data[x].replace(/&amp;/g, "&");
        window.location.href = link;
        break;
      case 'modal_dialog':
        if (typeof data[x]['title'] != "undefined") {
          data[x]['title'] = utf8_decode(base64_decode(data[x]['title']));
        }
        if (typeof data[x]['content'] != "undefined") {
          data[x]['content'] = utf8_decode(base64_decode(data[x]['content']));
        }
        myOpenDialog(data[x]);
        break;
    }
  }
  if (jQuery().sf_radio_checkbox) {
    $("input[type=checkbox], input[type=radio]").sf_radio_checkbox();
  }
}

function myModalDialogClose(id) {
  $("#" + id).dialog("destroy");
  $("#" + id).remove();
}

function myOpenDialog(data) {
  //myOpenDialog({content:'content',title:'hoho',modal:false});
  var id = 'mydialog_' + (new Date().getTime());
  var modal = (typeof data['modal'] == "undefined") ? true : data['modal'];
  var resizable = (typeof data['resizable'] == "undefined") ? false : data['resizable'];
  var draggable = (typeof data['draggable'] == "undefined") ? true : data['draggable'];
  var show = (typeof data['show'] == "undefined") ? false : data['show'];
  var width = (typeof data['width'] == "undefined") ? 'auto' : data['width'];
  var height = (typeof data['height'] == "undefined") ? 'auto' : data['height'];
  var title = (typeof data['title'] == "undefined") ? '' : data['title'];
  var hide_title = (typeof data['hide_title'] == "undefined") ? false : data['hide_title'];
  var content = (typeof data['content'] == "undefined") ? (typeof data['id_content'] == "undefined" ? '' : $('#' + data['id_content']).html()) : data['content'];
  if (height == '100%') {
    height = $(window).height();
  }
  $("<div id='" + id + "' style='padding-bottom:10px; height:300px; overflow:auto'>" + content + "</div>").dialog({
    title: title,
    width: width,
    height: height,
    resizable: resizable,
    draggable: draggable,
    modal: modal,
    show: show,
    buttons: {
      //"Delete all items": function() {
      //    $( this ).dialog( "close" );
      //},
    },
    create: function(event, ui) {
      if (modal) {
        $(".ui-dialog-content").dialog("close");
      }
      if (hide_title) {
        $(".ui-widget-header").hide();
      }
      $(event.target).parent().css('position', 'fixed');
      //$('#' + id).parent().children('.ui-dialog-buttonpane').html('footer here');
      myinit();
    },
    open: function() {
      $('#' + id, window.parent.document).scrollTop(0);
    },
    close: function(event, ui) {
      myModalDialogClose(id);
    }
  });
}

function myLoadingDialog() {
  //create loading dialog here
}

function closeMyLoadingDialog() {
  //close loading dialog here
}

function redirect(link) {
  window.location = link;
}

function unique(prefix) {
  if (typeof prefix === 'undefined') {
    prefix = 'sf';
  }
  return prefix + (((1 + Math.random()) * 0x100000000) | 0).toString(16).substring(1);
}

$(document).ready(function() {

  var body = $('body');
//  var content = new LinkScreenHandleContent();
//  content.init(body);
  body
    .on('hidden.bs.modal', '#sfDialogModel', function() {
      if ($(this).attr('data-id-selector-content') !== undefined) {
        var id = $(this).attr('data-id-selector-content');
        $(id).html($(this).find('.modal-body').html());
        $(this).remove();
      }
    })
    .on('show.bs.modal', '#sfDialogModel', function() {
      if ($(this).find('.list-screen-handle').length > 0) {
        var listScreenHelp = new ListScreenHandle();
        listScreenHelp.refresh();
        listScreenHelp.init($(this));
      }
    })
    .on("click", ".sfDialog", function(e) {
      var loading = false;
      if ($(this).attr('sfDlg-loading') == "true") {
        loading = true;
      }

      $('#sfDialogModel').remove();

      var strSfDlg =
        '<div class="modal" id="sfDialogModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 10000">' +
          '  <div class="modal-dialog">' +
          '    <div class="modal-content">' +
          '      <div class="modal-header">' +
          '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
          '        <h4 class="modal-title" id="sfDialogModel_title"></h4>' +
          '      </div>' +
          '      <div class="modal-body" id="sfDialogModel_body"></div>' +
          '      <div class="modal-footer" id="sfDialogModel_footer">' +
          '        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
          '      </div>' +
          '    </div>' +
          '  </div>' +
          '</div>';

      body.append(strSfDlg);
      var datasend = {}

      if ($(this).attr('sfDlg-title') == "false") {
        $('#sfDialogModel .modal-header').hide();
      }
      if ($(this).attr('sfDlg-footer') == "false") {
        $('#sfDialogModel .modal-footer').hide();
      }
      if ($(this).attr('sfDlg-width') != "") {
        $('#sfDialogModel .modal-dialog').css({width: $(this).attr('sfDlg-width')});
      }
      if ($(this).attr('sfDlg-datasend') != "") {
        datasend = getDataFromCollection($(this).attr('sfDlg-datasend'));
      }
      if ($(this).attr('href') !== undefined) {
        ajaxSendData($(this).attr('href'), datasend, {slient: loading, header: 'sfDialog'});
        e.preventDefault();
      }
      if ($(this).attr('data-id-selector-content') !== undefined) {
        var id = $(this).attr('data-id-selector-content');
        var temp = $(id);

        /*
         * btn optionally will appear in header or footer of modal, list available :
         * data-title (name)
         * data-show-btn-save-foot (name)
         * data-show-btn-remove-foot (name)
         * data-show-btn-save-head (name)
         * data-show-btn-remove-head (name)
         * data-remove-close-btn (true false)
         * */

        if (temp.attr('data-width') != undefined) {
          $('#sfDialogModel .modal-dialog').css({width: temp.attr('data-width')});
        }

        if (temp.attr('data-title') !== undefined) {
          $('#sfDialogModel_title').text(temp.attr('data-title'));
        }
        if (temp.attr('data-remove-close-btn') == 'true') {
          $('#sfDialogModel_footer').empty();
        }
        if (temp.attr('data-show-btn-save-foot') !== undefined) {
          var btnSFoot = '<button type="button" id="sfDialogModelSaveBtn" class="btn btn-success">' + temp.attr('data-show-btn-save-foot') + '</button>';
          $('#sfDialogModel_footer').append(btnSFoot);
        }
        if (temp.attr('data-show-btn-remove-foot') !== undefined) {
          var btnRFoot = '<button type="button" id="sfDialogModelRemoveBtn" class="btn btn-danger">' + temp.attr('data-show-btn-remove-foot') + '</button>';
          $('#sfDialogModel_footer').append(btnRFoot);
        }
        if (temp.attr('data-show-btn-save-head') !== undefined) {
          var btnSHead = '<button style="position: absolute; top: 1em; right:8.3em" type="button" id="sfDialogModelSaveBtn" class="btn btn-success">' + temp.attr('data-show-btn-save-head') + '</button>';
          $('#sfDialogModel_header').append(btnSHead);
        }
        if (temp.attr('data-show-btn-remove-head') !== undefined) {
          var btnRHead = '<button style="position: absolute; top: 1em; right:2.3em" type="button" id="sfDialogModelRemoveBtn" class="btn btn-danger">' + temp.attr('data-show-btn-remove-head') + '</button>';
          $('#sfDialogModel_header').append(btnRHead);
        }
        $('#sfDialogModel_body').html($(id).html());
        $(id).empty();
        $('#sfDialogModel').attr('data-id-selector-content', id).addClass('fade').modal('show');
      }
    });
});

function sfDialogModelClose() {
  $('#sfDialogModel').modal('hide');
}


function getDataFromCollection(idCollection, type) {
  if (type == 'json') {
    var data = {};
  }
  else {
    var data = '';
  }

  var element = $.merge($('#' + idCollection + ' input'), $('#' + idCollection + ' textarea'));
  var dataArray = {};
  for (var i = 0; i < element.length; i++) {
    var object = $(element[i]);

    if (object.attr('disabled') == 'disabled') {
      continue;
    }

    if (object.attr('type') == 'radio' || object.attr('type') == 'checkbox') {
      if (!object.is(":checked")) {
        continue;
      }
    }
    dataArray[object.attr('name')] = object.val();
  }

  for (key in dataArray) {
    if (type == 'json') {
      data[key] = dataArray[key];
    }
    else {
      data += key + '=' + encodeURIComponent(dataArray[key]) + '&';
    }

  }
  return data;
}
