/*
SF Module for reporting purpose. Require d3 library
*/
var sf_module_report = function(options){
	var me = this;
	me.options = options;
	this.version = "1.0.0";

	if (!me.options.TemplateDir) {
		me.options.TemplateDir = "tmpl";
	}
	var container = $('#' + me.options.container);
	if(!me.options.width) me.options.width = container.width();
	if(!me.options.height) {
        me.options.height = container.height();
    }
	if(!me.options.margin) me.options.margin = {top: 20, right: 20, bottom: 30, left: 40};

};

sf_module_report.prototype = {

	setData: function(data){
		this.originaldata = data;
	},
	setAjax: function(ajax){
		this.ajax = ajax;
	},

	refresh: function(){
		var me = this;
		var ajaxoptions = me.ajax;
		var reportoptions = me.options;
		var functionMap = {
			"bar": "drawBar",
			"pie": "drawPie",
			"mpie": "drawMultiplePie",
			"table": "drawTable",
			"gantt": "drawGantt",
			"duohoribar": "drawDuoHorizontalBar",
			"coloritem": "drawColorItem",
		};
		var drawFunc = functionMap[reportoptions.charttype];

		if(ajaxoptions){
			
			$.ajax({
                url: ajaxoptions.url,
                type: 'POST',
                data: {
                  'ds': ajaxoptions.queryname,
                  'params': ajaxoptions.params,
                },
                datatype: 'json',
                success: function (json) {
                	var d = json.map(ajaxoptions.mapfunction);
                    me[drawFunc](d);
                }
            });
		}
		else{
			var d = [];
			if(typeof(me.originaldata) == "function") d = me.originaldata();
			else d = me.originaldata;
			if(!d) return;
			me[drawFunc](d);
	    }
	},

	getDefaultSvg: function(){
		var me = this;
		var svg = {};
		if(this.__svg__) {
			svg = this.__svg__;
		}
		else{
			svg = d3.select("#" + me.options.container).append("svg")
			.attr("width", me.options.width + me.options.margin.left + me.options.margin.right)
			.attr("height", me.options.height + me.options.margin.top + me.options.margin.bottom)
			.append("g")
			;
			this.__svg__ = svg;
			if(me.options.title){
				this.drawTitle(svg, me.options.title, null);
			}
		}
		return svg;
	},

	drawTitle: function(svg, title, position){
		var me = this;
		var cx = (me.options.width - me.options.margin.left - me.options.margin.right) / 2;
		var cy = (me.options.height - me.options.margin.top - me.options.margin.bottom) / 2;
		var titleSize = measure(title, 'chartTitle');
		svg.append("text")
		.attr("x", cx - titleSize.width / 2)
		.attr("y", 0)
		.attr("class", "chartTitle")
		.text(me.options.title)
		;

	},

    drawColorLegend: function(color){
    	var me = this;
        d3.select("#" + me.options.legendcontainer).selectAll("svg").remove();
        var g = d3.select("#" + me.options.legendcontainer).selectAll(".legend")
            .data(color.domain().slice());


        var legend = g
            .enter().append("svg")
            .attr("class", "legend")
            .attr("width", radius)
            .attr("height", 20);
        g.exit().remove();

        legend.append("rect")
            .attr("width", 18)
            .attr("height", 18)
            .style("fill", color);

        legend.append("text")
            .attr("x", 20)
            .attr("y", 9)
            .attr("dy", ".35em")
            .text(function(d) {return d; });

    },


    /*
    	drawGantt data [{l:label, s:milisecond start, e:milisecond end}]
		require options.colorcode {l:{p:position, c:color}}
    */
	drawGantt: function(data){
		var me = this;
		var padding = 20;
		if(me.options.padding) padding = me.options.padding;
		var w = me.options.width;
		var h = me.options.height;
		var svg = me.getDefaultSvg();

		// scalings
		var xScale = d3.time.scale()
			.domain([d3.min(data, function(d){return new Date(+d.s);}), d3.max(data, function(d){return new Date(+d.e);})])
			.range([5*padding, w - padding]);
		
		// draw labels
		var labels = [];
	   	for(var l in me.options.colorcode){
	   		labels.push(l);
	   	}

	   	svg.selectAll('text')
	   		.data(labels)
	   		.enter()
	   		.append('text')
	   		.attr('x', padding * 4)
	   		.attr('y', function(d){return me.options.colorcode[d].position * padding * 1.2 + padding + 10;})
	   		.attr("dx", -3) // padding-right
		    .attr("dy", ".35em") // vertical-align: middle
			.attr("text-anchor", "end")
	   		.text(function(d){return d;})
	   		.attr('font-family', "sans-serif")
	     	.attr('font-size', '11px')
	   	;

	   	svg.append('line').attr('x1', padding * 4).attr('y1', 0).attr('x2', padding * 4).attr('y2', h)
	   		.attr("stroke-width", 1).attr('stroke', 'black')
	   	;

	   	// draw Gantt bars
	   	var rectsHolder = svg.selectAll('rect').data(data);

	   	var rects = rectsHolder.enter().append('rect')
	   		.attr('x', function(d){return xScale(+d.s);})
	   		.attr('y', function(d){return (+me.options.colorcode[d.l].position) * padding * 1.2 + padding;})
	   		.attr('width', function(d){return xScale(+d.e) - xScale(+d.s);})
	   		.attr('height', 20)
	   		.attr('fill', function(d){return me.options.colorcode[d.l].color;})
	   	;
	   	rectsHolder.transition().duration(1000)
	   		.attr('x', function(d){return xScale(+d.s);})
	   		.attr('y', function(d){return (+me.options.colorcode[d.l].position) * padding * 1.2 + padding;})
	   		.attr('width', function(d){return xScale(+d.e) - xScale(+d.s);})
	   		.attr('height', 20)
	   		.attr('fill', function(d){return me.options.colorcode[d.l].color;})
	   	;
	   	
	   	rectsHolder.exit().remove();

	   	// Draw top & bottom axis
	    var monthConvert = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	    var formatTick = function(d){
	    	var dt = new Date(d); 
	    	var date = dt.getDate();
	    	var month = dt.getMonth();
	    	var year = dt.getFullYear();
	    	var hours = dt.getHours();
	    	var minutes = dt.getMinutes();
	    	return monthConvert[month] + ", " + date + " " + hours + ":" + minutes;
	    };

	    var dateDiff = function(s, e){
	    	var diff = e - s;
	    	diff = Math.floor(diff / 1000);
	    	var s = diff % 60;
	    	diff = Math.floor(diff / 60);
	    	var m = diff % 60;
	    	diff = Math.floor(diff / 60);
	    	var h = diff % 60;
	    	return h + ":" + m + "h";
	    }

	    // create if there is no axis
	    if(!me.__axisExist__){
		    var xAxisBottom = d3.svg.axis().scale(xScale).orient("bottom").ticks(3).tickFormat(formatTick);
		    var xAxisTop = d3.svg.axis().scale(xScale).orient("top").ticks(3).tickFormat(formatTick);
		    me.__axisExist__ = true;
		    svg.append('g').attr('class', 'bottom axis')
		   		.attr('transform', 'translate(0, ' + (h-padding) + ')')
		   		.call(xAxisBottom)
		   	;
		    svg.append('g').attr('class', 'top axis')
		    	.attr('transform', 'translate(0, ' + (padding) + ')')
		   		.call(xAxisTop)
		   	;
	    }
	    // otherwise, update the scale
	    else{
		    var xAxisBottom = d3.svg.axis().scale(xScale).orient("bottom").ticks(3).tickFormat(formatTick);
		    var xAxisTop = d3.svg.axis().scale(xScale).orient("top").ticks(3).tickFormat(formatTick);
			svg.selectAll('g.top.axis').call(xAxisTop);
			svg.selectAll('g.bottom.axis').call(xAxisBottom);
	    }

	   	// Show tooltips
	   	if(me.options.tooltipContainerId){
	   		var tooltip;
	   		if(me.__tooltip__){
	   			tooltip = me.__tooltip__;
	   		}
	   		else{
	   			var tooltip = d3.select("#" + me.options.tooltipContainerId);
		   		// var tooltiptemplate = $("#" + me.options.tooltipContainerId).html();
		   		// tooltip = d3.select('body').append('div').attr('id', 'tooltipContainer').attr('class', 'tip');
		   		// tooltip.html(tooltiptemplate);
		   		// tooltip.style('visibility', 'hidden');
		   		me.__tooltip__ = tooltip;
		   	}

	   		rects.on("mouseover", function(d){
	   			var item = this;

	   			if(typeof me.options.tooltipFunction == "function"){
	   				tooltip = me.options.tooltipFunction(d, tooltip);
	   			}
   	// 			var left = (+item.getAttribute('x')) + (+item.getAttribute('width')) + padding;
				// var top = (+item.getAttribute('y')) - padding * 1.5;
				tooltip.style("visibility", "visible");
				// .style('left', left).style('top', top);

				return tooltip;
	   		})
	   		.on("mousemove", function(){return tooltip.style("top", (d3.event.pageY - 100)+"px").style("left",(d3.event.pageX - 10)+"px");})
	   		.on("mouseout", function(d){return tooltip.style("visibility", "hidden");});
	   	}

	   	// Show modal if needed
	   	if(me.options.modalContainerId){
	   		rects.on("click", function(d){
	   			me.options.modalFunction(d);
	   		});
	   	}
	},

    drawHorizontal: function(_svg, _data, _color, padding, width, labels){
    	var iW = width;
    	var xScale = d3.scale.linear();
    	xScale.domain([0, d3.max(_data, function(d){return d.v;})])
    		.range([padding, iW - padding]);
    	
    	var rects = _svg.selectAll('rect').data(_data);
    	rects.enter().append('rect').attr('x', padding).attr('y', function(d){ return labels[d.l] * padding * 1.5 + 2 * padding})
    		.attr('width', function(d){return xScale(d.v);}).attr('height', 20).attr('fill', _color);
    	rects.transition().duration(1000).attr('width', function(d){return xScale(d.v);});
    	rects.exit().remove();
    	var texts = _svg.selectAll('text').data(_data)
    	texts.enter().append('text').attr('x', function(d){return padding + xScale(d.v);}).attr('y', function(d){return labels[d.l] * padding * 1.5 + 2 * padding + 10;})
    		.attr('dx', -3).attr('dy', ".35em").attr('text-anchor', 'end')
    		.text(function(d){return d.v;}).attr('font-family', 'sans-serif').attr('font-size', '11px');
    	texts.transition().duration(1000).attr('x', function(d){return padding + xScale(d.v);}).text(function(d){return d.v;});
    	texts.exit().remove();
    },
	/*
		data:[{l:label, c1:chart1value, c2:chart2value}]
		optional: 	me.options.converter.c1tick: convert chart1value to the desired format
					me.options.converter.c2tick
	*/
	drawDuoHorizontalBar: function(data){
		var me = this;
		var meId = me.options.container;
		var padding = 20;
		if(me.options.padding) padding = me.options.padding;
		var w = me.options.width;
		var h = me.options.height;
		var w1 = w / 5;
		var w2 = w3 = w1 * 2;

		var svgl, svg1, svg2;
		if (me.__svgl__){
			svgl = me.__svgl__;
			svg1 = me.__svg1__;
			svg2 = me.__svg2__;
		}
		else{
			me.__svgl__ = svgl = d3.select('#' + meId).append('svg').attr('width', w1).attr('height', h).append('g');
			me.__svg1__ = svg1 = d3.select('#' + meId).append('svg').attr('width', w2).attr('height', h).append('g');
			me.__svg2__ = svg2 = d3.select('#' + meId).append('svg').attr('width', w3).attr('height', h).append('g');
		}
		
		//	Draw labels
		var labels = {};
		for(var i = 0; i < data.length; i ++) {labels[data[i].l] = i;}
		
		var textx = svgl.selectAll('text').data(data);
		textx.enter()
			.append('text')
			.attr('x', w1)
			.attr('y', function(d){return labels[d.l] * padding * 1.5 + 2 * padding + 10;})
			.attr("dx", -3) // padding-right
		    .attr("dy", ".35em") // vertical-align: middle
			.attr("text-anchor", "end")
	   		.text(function(d){return d.l;})
	   		.attr('font-family', "sans-serif")
	     	.attr('font-size', '11px')
	    ;
	    textx.transition().duration(1000)
	    	.attr('y', function(d){return labels[d.l] * padding * 1.5 + 2 * padding + 10;})
	    	.text(function(d){return d.l;});
	    ;
	    textx.exit().remove();

		me.drawHorizontal(svg1, data.map(function(d){return {"l":d.l, "v":+d.c1};}), '#C3C3E5', padding, w2, labels);
		me.drawHorizontal(svg2, data.map(function(d){return {"l":d.l, "v":+d.c2};}), '#FF6600', padding, w3, labels);
	},


	/*
		legend: [{"c":"color", "n":"name"}]
		id: div id to contain this drawing
	*/
	drawColorLegend2: function(){
    	var me = this;
    	var divId = '#' + me.options.legendcontainer;
    	var legend = [];
    	for(var prop in me.options.colorcode){
    		legend.push({"c": me.options.colorcode[prop].color, "n":prop});
    	}
        d3.select(divId).selectAll("svg").remove();
        var g = d3.select(divId).selectAll(".legend").data(legend);
        var rects = g.enter().append('svg').attr('class', 'legend').attr('width', 100).attr('height', 30);
		rects.append('rect').attr('width', 10).attr('height', 10).attr('fill', function(d){return  d.c;});
		rects.append('text').attr('x', 10).attr('y', 10).text(function(d){return d.n;});
    },
	/*
		data: [{"n":"name", "s":"status"}]
	*/

	drawColorItem: function(data){
		var me = this;
		d3.select('#' + me.options.container).selectAll('svg').remove();
		var svgs = d3.select("#" + me.options.container).selectAll('svg').data(data);
		var newSvgs = svgs.enter().append('svg').attr('width', 100).attr('height', 30);
		
		newSvgs.append('rect').attr('width', 10).attr('height', 10).attr('fill', function(d){return  me.options.colorcode[d.s].color;});
		newSvgs.append('text').attr('x', 10).attr('y', 10).text(function(d){return d.n;});

		if(me.options.legendcontainer){
			me.drawColorLegend2();
		}
	},

    /*drawTable data: ...*/
    drawTable: function(data){
    	var me = this;
        var svg = {};
        var columns = [];
        for(var column in data[0]){
            columns.push(column);
        }
        $("#" + me.options.container).empty();
        svg = d3.select("#" + me.options.container).append("table").attr("id", me.options.container + "_tbl");

        var thead = svg.append('thead');
// append the header row
        thead.append("tr")
            .selectAll("th")
            .data(columns)
            .enter()
            .append("th")
            .text(function(column) { return column; });
        var tBody = svg.append('tbody');
        tBody.selectAll("tr").remove();
        var rows = tBody.selectAll("tr").data(data);

        rows.enter().append("tr");
        rows.exit().remove();

        var cells = rows.selectAll("td")
            .data(function(row){
                return columns.map(
                    function(column){
                        return{column:column, value:row[column]};
                    }
                );
            });
        cells.enter()
            .append("td")
            .text(function(d){return d.value});
        cells.exit().remove();
        $("#" + me.options.container + "_tbl").fixedHeaderTable({ footer: false, cloneHeadToFoot: false, fixedColumn: false });
    },

	drawMultiplePie: function(data){
		var me = this;
		var margin = me.options.margin,
		width = me.options.width - margin.left - margin.right,
		height = me.options.height - margin.top - margin.bottom;
		;

		var radius = 74,
		padding = 10;

		var color = d3.scale.ordinal()
		.range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

		color.domain(data.domain);
		
		var arc = d3.svg.arc()
		.outerRadius(radius)
		.innerRadius(radius - 30);

		var pie = d3.layout.pie()
		.sort(null)
		.value(function(d) { return +d.v; });


		
		data.data.forEach(function(d) {
			d.categories = color.domain().map(function(name) {
				if(d.data[name])
					return {l: name, v: +d.data[name]};
				else
					return {l: name, v: 0};
			});
		});

		if(me.options.legendcontainer){

			var legend = d3.select("#" + me.options.legendcontainer).selectAll(".legend")
			.data(color.domain().slice())
			.enter().append("svg")
			.attr("class", "legend")
			.attr("width", radius)
			.attr("height", 20);

			legend.append("rect")
			.attr("width", 18)
			.attr("height", 18)
			.style("fill", color);

			legend.append("text")
			.attr("x", 20)
			.attr("y", 9)
			.attr("dy", ".35em")
			.text(function(d) { return d; });
		}
		var svg = {};
		if(this.__svg__) svg = this.__svg__;
		else{
			svg = d3.select("#" + me.options.container).selectAll(".pie")
			.data(data.data)
			.enter().append("svg")
			.attr("class", "pie")
			.attr("width", radius * 2)
			.attr("height", radius * 2)
			.append("g")
			.attr("transform", "translate(" + radius + "," + radius + ")");
			this.__svg__ = svg;
		}
		svg.selectAll(".arc")
		.data(function(d) { return pie(d.categories); })
		.enter().append("path")
		.attr("class", "arc")
		.attr("d", arc)
		.style("fill", function(d) { return color(d.data.l); });

		svg.append("text")
		.attr("dy", ".35em")
		.style("text-anchor", "middle")
		.text(function(d) { return d.name; });

	},

	drawPie: function(data){
		var me = this;
		var margin = me.options.margin,
		width = me.options.width - margin.left - margin.right,
		height = me.options.height - margin.top - margin.bottom;

		radius = Math.min(width, height) / 2;

		// var color = d3.scale.ordinal()
		// .range(me.options.colorcode.map(function(d){return d.color}));


		var arc = d3.svg.arc()
		.outerRadius(radius - 10)
		.innerRadius(0);

		var pie = d3.layout.pie()
		.sort(null)
		.value(function(d) { return +d.v; });


        if(me.options.legendcontainer){
            // color.domain(data.map(function(d){return d.l;}));
            // this.drawColorLegend(color);
            me.drawColorLegend2();
//            var legend = d3.select("#" + me.options.legendcontainer).selectAll(".legend")
//                .data(color.domain())
//                .enter().append("svg")
//                .attr("class", "legend")
//                .attr("width", radius)
//                .attr("height", 20);
//
//            legend.append("rect")
//                .attr("width", 18)
//                .attr("height", 18)
//                .style("fill", color);
//
//            legend.append("text")
//                .attr("x", 20)
//                .attr("y", 9)
//                .attr("dy", ".35em")
//                .text(function(d) { return d; });
        }

		var svg = {};
		if(this.__svg__) svg = this.__svg__;
		else{
			svg = d3.select("#" + me.options.container).append("svg")
			.attr("width", width + margin.left + margin.right)
			.attr("height", height + margin.top + margin.bottom)
			.append("g")
			.attr("transform", "translate(" + width/2 + "," + height / 2 + ")")
			;
			this.__svg__ = svg;
		}

		if(me.options.title){
			this.drawTitle(svg, me.options.title, null);
		}

		

		// var g = svg.datum(data).selectAll("path")
	 //      	.data(pie(data));

  //   	g.enter().append("path")
  //     	.attr("fill", function(d, i) { return color(d.data.l); })
  //     	.attr("d", arc)
  //     	.each(function(d) { this._current = d; }); // store the initial angles

		var g = svg.selectAll(".arc")
		.data(pie(data));

		g.enter().append("g")
		.attr("class", "arc")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")")
		;

		g.append("path")
		.attr("d", arc)
		.style("fill", function(d) {return me.options.colorcode[d.data.l].color; })
		;

		// var path = g.selectAll("g.arc").data(function(d, i){console.log(d); return d.data;});
		
		// path.enter().append("path").attr("class", "path").attr("d", arc).style("fill", function(d) { console.log(d); return color(d.data.l); });

		if(me.options.showtext){
			g.append("text")
			.attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
			.attr("dy", ".35em")
			.style("text-anchor", "middle")
			.text(function(d) { return d.data.l; });
		}

		// g.transition().duration(400).attrTween("d", function(a){
		// 	var i = d3.interpolate(this._current, a);
		//   	this._current = i(0);

		// 	return function(t) {
		// 	return arc(i(t));
		// 	};
		// })
		// .attr("fill", function(d, i) { return color(d.data.l); });
		g.exit().remove();

	},


	/*Data for drawing with bar: ([{l:label, v:value}])*/
	drawBar: function(data){
		var me = this;
		var margin = me.options.margin,
		width = me.options.width - margin.left - margin.right,
		height = me.options.height - margin.top - margin.bottom;

		/*var formatPercent = d3.format(".0%");*/

		var x = d3.scale.ordinal()
		.rangeRoundBands([0, width], .1);

		var y = d3.scale.linear()
		.range([height, 0]);

		var xAxis = d3.svg.axis()
		.scale(x)
		.orient("bottom");

		var yAxis = d3.svg.axis()
		.scale(y)
		.orient("left");

        if(me.options.yaxislabel){
            yAxis = yAxis.tickFormat(me.options.yaxislabel);
        }

		/*.tickFormat(formatPercent);*/
		x.domain(data.map(function(d) { return d.l; }));
		y.domain([0, d3.max(data, function(d) { return +d.v; })]);

		var svg = {};
		if(this.__svg__) {
			svg = this.__svg__;
		}
		else{
			svg = d3.select("#" + me.options.container).append("svg")
			.attr("width", width + margin.left + margin.right)
			.attr("height", height + margin.top + margin.bottom)
			.append("g")
			.attr("transform", "translate(" + margin.left + "," + margin.top + ")")
			;
			this.__svg__ = svg;
			if(me.options.title){
				this.drawTitle(svg, me.options.title, null);
			}
			svg.append("g")
			.attr("class", "x axis")
			.attr("transform", "translate(0," + height + ")")
			.call(xAxis);

			svg.append("g")
			.attr("class", "y axis")
			.call(yAxis)
			;
		}

		svg.selectAll(".x.axis").transition().duration(1000).call(xAxis);
		svg.selectAll(".y.axis").transition().duration(1000).call(yAxis);

		var bars = svg.selectAll(".bar")
		.data(data);

		bars.enter().append("rect")
		.attr("class", "bar")
		.attr("x", function(d) { return x(d.l); })
		.attr("width", x.rangeBand())
		.attr("y", function(d) { return y(+d.v); })
		.attr("height", function(d) { return height - y(+d.v); });

		bars.transition()
        .duration(1000)
        .attr("x", function(d) { return x(d.l); })
		.attr("width", x.rangeBand())
		.attr("y", function(d) { return y(+d.v); })
		.attr("height", function(d) { return height - y(+d.v); });

        bars.exit().remove();

		if(typeof(this.interactiveFunction) == "function"){
			var childfunc = this.interactiveFunction;
			bars.on("click", function(d){childfunc(d.l);});
		}
	}
};



function measure(text, classname) {
	if(!text || text.length === 0) return {height: 0, width: 0};

	var container = d3.select('body').append('svg').attr('class', classname);
	container.append('text').attr({x: -1000, y: -1000}).text(text);

	var bbox = container.node().getBBox();
	container.remove();

	return {height: bbox.height, width: bbox.width};
}

function setDropdownValue(id, jsonUrl, queryName, param, mapfunction){
	var data = {ds: queryName};
	if(param) data.params = params;
	$.ajax({
        url: jsonUrl,
        type: 'POST',
        data: data,
        datatype: 'json',
        success: function (json) {
        	var data = json.map(function(d){return mapfunction(d);});
        	var select = d3.select('#' + id).selectAll('option').data(data);
        	select.enter().append('option').attr('value', function(d){return d.id}).text(function(d){return d.value});
        	select.transition().attr('value', function(d){return d.id}).text(function(d){return d.value});
        	select.exit().remove();
        }
    });
}