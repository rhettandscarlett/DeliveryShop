if (typeof htmlEncode == "undefined")
	function htmlEncode(value) {
		//create a in-memory div, set it's inner text(which jQuery automatically encodes)
		//then grab the encoded contents back out.  The div never exists on the page.
		return $('<div/>').text(value).html();
	}

(function ($) {
	$(document).ready(function () {
		$('.ReportContainer').each(function () {
			var me = this;
			var reportType = $(me).data('report_type');

			if (reportType.toLowerCase() == "table") {
				var tblId = $(me).find('.report table').attr('id');
				var pagerId = $(me).find('.report .pager').attr('id');
				var reportOptionUrl = $(me).data('option_url');
				var queryUrl = $(me).data('query_url');

				var reportObject = $(this).data('reportObject');

				// create the report object if not exists
				if (typeof reportObject == "undefined" || reportObject == null) {
					reportObject = Object();
					reportObject.refresh = function () {
						// Now get the report options from server
						$.ajax(
							{
								url: reportOptionUrl,
								success: function (d) {
									var postData = {};
									var allData = $(me).data();
									for (var prop in allData) {
										if (allData.hasOwnProperty(prop)) {
											if (prop.substring(0, 6) == "param_") {
												postData[prop.substring(6)] = allData[prop];
											}
										}
									}

									var reportOption = JSON.parse(d);
									reportOption.postData = postData;
									reportOption.url = queryUrl;
									reportOption.pager = '#' + pagerId;
									$.ajax({
											url: queryUrl,
											data: {params: postData},
											error: function (e) {
												console.log(e);
											},
											success: function (d2) {
												var reportData = JSON.parse(d2);
												var tbl = $('#' + tblId);
												tbl.html('');
												var html = "";
												// generate header
												var th = "";
												if (reportOption.noHeader) {
												}
												else {
													for (var i = 0; i < reportOption.colNames.length; i++) {
														var tdClasses = "";
														if (i == 0) {
															tdClasses = "first";
														}
														if (reportOption.colModel[i]["align"]) {
															tdClasses += " text-" + reportOption.colModel[i]["align"];
														}
														if (reportOption.colModel[i]["width"]) {
															tdClasses += " col-sm-" + reportOption.colModel[i]["width"];
														}

														th += "<td class='" + tdClasses + "'>";
														if (reportOption.firstRowAsHeader && reportData.length > 0) {
															th += htmlEncode(reportData[0][reportOption.colModel[i]["name"]]);
														}
														else {
															th += htmlEncode(reportOption.colNames[i]);
														}
														th += "</td>";
													}
													html += "<thead><tr>" + th + "</tr></thead>";
													if (reportOption.showFooter)
														html += "<tfoot><tr>" + th + "</tr></tfoot>";
												}

												// generate body
												html += "<tbody>";
												var iStartOffset = 0;
												if (reportOption.firstRowAsHeader) iStartOffset = 1;
												for (i = iStartOffset; i < reportData.length; i++) {
													eval('var conditionMap = ' + reportOption.conditionMap);
													if (conditionMap == null) conditionMap = "";
													if (typeof conditionMap == "function") html += "<tr " + conditionMap(reportData[i]) + ">";
													else html += "<tr " + conditionMap + ">";

													for (j = 0; j < reportOption.colModel.length; j++) {

														var tdClasses = "";
														if (j == 0) {
															tdClasses = "first";
														}
														if (reportOption.colModel[j]["align"]) {
															tdClasses += " text-" + reportOption.colModel[j]["align"];
														}
														html += "<td class='" + tdClasses + "'>";
														html += htmlEncode(reportData[i][reportOption.colModel[j]["name"]]) + "</td>";
													}
													html += "</tr>";
												}
												html += "</tbody>";
												tbl.html(html);
											}
										}
									);
								}
							}
						);
					};
					$(this).data('reportObject', reportObject);
					reportObject.refresh();
				}
				// set the refresh event for this container
				$(this).on('refreshReport', function () {
					var reportObject = $(this).data('reportObject');
					reportObject.refresh();
				});
			}
		});
		$('button.reload').click(function () {
			var id = $(this).attr('id');
			$('.' + id).trigger('refreshReport');
		});
	});


})(jQuery);

