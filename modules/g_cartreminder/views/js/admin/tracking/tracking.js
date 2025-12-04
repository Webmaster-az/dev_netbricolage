/**
* This is main js file. Don't edit the file if you want to update module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/


var dashtrends_data;
var dashtrends_chart;
$(document).ready(function(){
    $(".datepicker").datepicker({
        prevText: "",
        nextText: "",
        dateFormat: "yy-mm-dd"});
    line_chart_trends('email tracking', jQuery.parseJSON( $('.chart_emails').val() ));
    $('#dateRange').click(function(){
        var dateform = $('#datefrom').val();
        var dateto = $('#dateto').val();
        var data = {};
    	data.datefrom = dateform;
        data.dateto = dateto;
    	data.ajax = 1;
        data.action = "Dateshow";
    	data.controller_ajax = help_class_name;
    	$.ajax({
    		type: "POST",
    		url: currentIndex+"&token="+token,
    		data: data,
    		dataType: 'json',
    		async : true,
    		success: function(datas)
    		{
              //return false;
              $('.total_checking').text(datas.count);
    		  line_chart_trends('email tracking', datas.data);
    		},
    		error: function(error)
    		{
              return false;
    		}
    	});
    });
});
function line_chart_trends(widget_name, chart_details)
{
    //console.log(chart_details);
	//if (chart_details.data[0].values.length <= 1)
//		return false;
	nv.addGraph(function() {
		var chart = nv.models.lineChart()
			.useInteractiveGuideline(true)
			.x(function(d) { return (d !== undefined ? d[0] : 0); })
			.y(function(d) { return (d !== undefined ? parseInt(d[1]) : 0); })
			.margin({left: 80});
		chart.xAxis.tickFormat(function(d) {
			date = new Date(d*1000);
            return dateFormat(date, chart_details['date_format']);
		});
		first_data = new Array();
		$.each(chart_details.data, function(index, value) {
				first_data.push(chart_details.data[index]);
		});
		dashtrends_data = chart_details.data;
		dashtrends_chart = chart;
		d3.select('#chart svg')
			.datum(first_data)
			.call(chart);
		nv.utils.windowResize(chart.update);
		return chart;
	});
}