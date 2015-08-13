$(function() {
  if (jQuery().dataTable) {
    var _dt = $(".items-table"),
      options = {},
      nosearchable = [], nosortable = [], novisible = [],
      data_nosearchable = _dt.data('nosearchable'),
      data_nosortable = _dt.data('nosortable'),
      data_novisible = _dt.data('novisible'),
      data_sdom = _dt.data('sdom'),
      data_idisplaylength = _dt.data('idisplaylength'),
      data_aocolumns = _dt.data('aocolumns'),
      data_aasorting = _dt.data('aasorting');

    if (data_nosearchable) {
      data_nosearchable = data_nosearchable.split(",");
      $.each(data_nosearchable, function(index, value) {
        nosearchable.push(parseInt(value, 10))
      });
    }
    if (data_nosortable) {
      data_nosortable = data_nosortable.split(",");
      $.each(data_nosortable, function(index, value) {
        nosortable.push(parseInt(value, 10))
      });
    }
    if (data_novisible) {
      data_novisible = data_novisible.split(",");
      $.each(data_novisible, function(index, value) {
        novisible.push(parseInt(value, 10))
      });
    }

    options = {
      aLengthMenu: [
        [3, 10, 15, 25, 50, 100, -1],
        [3, 10, 15, 25, 50, 100, sf.datatable.sAll]
      ],
      stateSave: true,
      //iDisplayLength: 10,
      //bFilter: false,
      oSearch: {bSmart: false},
      oLanguage: {
        sLengthMenu: sf.datatable.sLengthMenu,
        sInfo: sf.datatable.sInfo,
        sInfoEmpty: sf.datatable.sInfoEmpty,
        sSearch: sf.datatable.sSearch,
        oPaginate: {
          sPrevious: sf.datatable.oPaginate.sPrevious,
          sNext: sf.datatable.oPaginate.sNext
        }
      },
      aoColumnDefs: [
        {bSortable: false, aTargets: nosortable},
        {bSearchable: false, aTargets: nosearchable},
        {bVisible: false, aTargets: novisible }
      ],
      bAutoWidth: false
    };

    if (data_sdom) {
      options["sDom"] = data_sdom;
    }

    if (data_aocolumns) {
      options["aoColumns"] = eval(data_aocolumns);
    }

    if (data_idisplaylength) {
      options["iDisplayLength"] = data_idisplaylength;
    } else {
      options["iDisplayLength"] = 10;
    }

    if (data_aasorting) {
      options["aaSorting"] = eval(data_aasorting);
    }

    _dt.dataTable(options);
  }
});

