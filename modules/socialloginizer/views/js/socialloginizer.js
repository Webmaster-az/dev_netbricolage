/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2015 Knowband
* @license   see file: LICENSE.txt
*/
	var set = 0;


function closeModalForm()
{
	$("#account-title").html(' ');
	$("#socialuseritem").val("10");
	$("#social-email-search").val(null);
    $('.modal-backdrop').hide();
    $('#socialstatmodal').modal('hide');
}

function configurationAccordian(id)
{
$("#"+id+"_accordian").accordion({ 
      animated: 'bounceslide',
      autoHeight: false, 
      collapsible: true, 
      event: 'click', 
      active: false,
      animate: 100
    });
}
  
 
  
  
$(document).ready(function(){

    var account_arr = ["facebook", "google", "live", "linkedin" , "twitter", "insta", "amazon", "pay", "yahoo", "foursquare", "github", "disqus", "vk", "wordpress", "dropbox"];
	$.each(account_arr,function(i,val){
        getMailChimpList(val);
		configurationAccordian(val);
	});
    $("#stat_graph").live('click', function(){	    
	    if (set == 0)
			drawOrderGraph(graphdata); 
    });
    if (!$('input#socialloginizer_show_popup').is(':checked')) {
        $('#socialloginizer_redirect_url').show();
    }
    $('#socialloginizer_show_popup').change(function() {
        if($(this).is(":checked")) {
            $('#socialloginizer_redirect_url').hide();
        }
        else
            $('#socialloginizer_redirect_url').show();
    });
    
});


function drawOrderGraph(data)
{
	 set = 1;
	
	if (parseInt(lcount) == 0 && parseInt(rcount) == 0)
	{
		$("#analysis_graph").css("height","auto");
		return;
	}
		
	
    var login_count = [], account = [], color = [], register_count = [];
    var i;

    var length = data.length;
    

    for (i = 0; i < length; i++)
    {
	    
        account.push([i, String(data[i]['account_type'])]);
        login_count.push([i, parseInt(data[i]['login_count'])]);
        register_count.push([i, parseInt(data[i]['register_count'])]);
    }
    

    if (login_count.length > 0 || register_count.length > 0)
    {
        var dataset = [
            {
                label: login,
                data: login_count,
                bars: {order:1, lineWidth: 0}
//		color:"red"
            },
            {
                label: register,
                data: register_count,
                bars: {order:2, lineWidth: 0}
//		color:"red"
            }
	   
        ];
        
        var options = {
            
            series: {
		grow: {active:true}
            },
	    bars: {
                    show:true,
                    barWidth: 0.3,
                    fill:1
            },
            xaxis: {
                ticks: account,
                axisLabel: account_type,
		tickColor: "#fff",
		axisLabelPadding: 10,
		axisLabelUseCanvas: true,
		autoscaleMargin: 0.01
		 
            },
	    yaxis: {
                axisLabel: count,
		tickColor: "#e6e6e6",
		//axisLabelPadding: 10,
		axisLabelUseCanvas: true
            },
            legend: {
//		     container:$("#legendContainer"),            
		noColumns: 0,
                backgroundColor: null,
                backgroundOpacity: 0.9,
                labelBoxBorderColor: null,
                position: "ne"
            },
            grid: {
                hoverable: true,
                borderWidth: 1,
                borderColor: '#EEEEEE',
                mouseActiveRadius: 10,
                backgroundColor: "#ffffff",
                axisMargin: 20
            },
	    colors: ["rgb(219, 68, 55)", "rgb(56, 113, 207)"]
        };
        
        $.plot($("#analysis_graph"), dataset, options);
        $("#analysis_graph").CreateVerticalGraphToolTip();
    }
    else
    {
        $('#analysis_graph').html('<div class="no_chart"><span>' + empty_list_msg + '</span></div>');
    }

}

var previousPoint = null, previousLabel = null;
$.fn.CreateVerticalGraphToolTip = function () {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();
                
                var x = item.dataIndex;
                var y = item.datapoint[1];

                var color = item.series.color;
                sfl_graph_showTooltip(item.pageX, item.pageY, color,
                            "<strong>" + item.series.xaxis.ticks[x].label +" "+ item.series.label +  "</strong> : <strong>" + y + "</strong> ");
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

function sfl_graph_showTooltip(x, y, color, contents) {
    $('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        top: y - 40,
        left: x - 20,
        border: '1px solid ' + color,
        padding: '3px',
        'font-size': '11px',
        'border-radius': '5px',
        'background-color': '#fff',
        'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
        opacity: 0.9
    }).appendTo("body").fadeIn(200);
}

function getMailChimpList(account)
{
    var html = "<font color='red'>No list exists for this API key!</font>";
    $("#"+account+"_loading").hide();
    $("#"+account+"_list").html(html);
    $('select.vss_sc_ver15#'+account+'_selectlist').selectpicker();
}