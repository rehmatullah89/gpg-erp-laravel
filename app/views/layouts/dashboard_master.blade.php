<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="{{asset('img/favicon.png')}}">

    <title>Global Power Group</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-reset.css')}}" rel="stylesheet">
    <!--external css-->
    <link href="{{asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-datepicker/css/datepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-fileupload/bootstrap-fileupload.css') }}" />
    <!--right slidebar-->
    <link href="{{asset('css/slidebars.css')}}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/style-responsive.css')}}" rel="stylesheet" />
    <link href="{{asset('css/gpgstyles.css')}}" rel="stylesheet" />
     <style>
    div#Electrical-Jobs-Listing-Filters text tspan{
        display: none;
    }
     div#Grassivy-Jobs-Listing-Filters text tspan{
        display: none;
    }
     div#Service-Jobs-Listing-Filters text tspan{
        display: none;
    }
    .btn-xs{
        border-radius: 3px;
        font-size: 12px;
        line-height: 1.5;
        padding: 1px 5px;
    }
    </style>
    
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{ asset('js/jquery-ui-1.9.2.custom.min.js') }}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script class="include" type="text/javascript" src="{{asset('js/jquery.dcjqaccordion.2.7.js')}}"></script>
    <script src="{{asset('js/jquery.scrollTo.min.js')}}"></script>
    <script src="{{asset('js/slidebars.min.js')}}"></script>
    <script src="{{asset('js/respond.min.js')}}" ></script>
    <script src="{{ asset('assets/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-fileupload/bootstrap-fileupload.js') }}"></script>
    <!--common script for all pages-->
    
    <link href="{{asset('assets/morris.js-0.4.3/morris.css')}}" rel="stylesheet" />
    <script src="{{asset('assets/morris.js-0.4.3/raphael-min.js')}}" type="text/javascript"></script>
	<script src="{{asset('js/morris.js')}}" type="text/javascript"></script>
    
    <!--dynamic table-->
    <link href="{{asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('assets/data-tables/DT_bootstrap.css')}}" />
    <script type="text/javascript" language="javascript" src="{{asset('assets/advanced-datatable/media/js/jquery.dataTables.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/data-tables/DT_bootstrap.js')}}"></script>
        <script type="text/javascript" src="{{ asset('js/dynamic_table_init.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-timepicker/compiled/timepicker.css') }}" />
     <script src="{{ asset('assets/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/form-component.js')}}"></script>
    
    <script>
		$(document).ready(function() {

            $.getScript('js/jquery.nicescroll.js', function() {
             });
            $.getScript('js/common-scripts.js', function() {
             });

			$('#contracts-table').dataTable( {
				"aaSorting": [[ 4, "desc" ]]
			});
		});
	</script>
    
    <script type="text/javascript">
    var i = 0;
    function drawGraph(div, data, title, countTitle, amountTitle,color, type){
        if(type == 'bar1'){
            Morris.Bar({
                element: div,
                data: data,
                xkey: 'name',
                ykeys: ['price', 'count'],
                labels: [amountTitle, countTitle],
                xLabelAngle: 90,
                stacked: true,
                hideHover: 'auto',
                gridTextSize: 12,
                resize: true,
                xLabelAngle: 60,
                yLabelAngle: 60,
                barColors: [color],
                hoverCallback: function (index, options, content, row) {
                   return "<div class='morris-hover-point' style='color: " +color+ "'><b style='color: #000;'>"+options.data[index].name+'</b><br />'+' '+amountTitle+': $'+(options.data[index].price).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+'<br />'+countTitle+ ': '+options.data[index].count+"</div>";
                }
            });
        }
        if(type == 'multiBar'){
            Morris.Bar({
                element: div,
                data: data,
                hideHover: 'auto',
                xLabelAngle: 90,
                gridTextSize: 12,
                resize: true,
                xLabelAngle: 60,
                yLabelAngle: 60,
                xkey: 'name',
                ykeys: ['added','completed','open_jobs','open_contract','allocated_hr'],
                labels: ['Added','Completed','Open Jobs','Open Contract','Allocated Hours'],
                barColors:['#F15854', '#F17CB0', '#5DA5DA', '#FAA43A', '#60BD68', '#60BD68', '#B2912F', '#B276B2', '#DECF3F']
            });
        }
		if(type == 'bar'){
            Morris.Bar({
                element: div,
                data: data,
                hideHover: 'auto',
                xLabelAngle: 90,
                gridTextSize: 12,
                resize: true,
                xLabelAngle: 60,
                yLabelAngle: 60,
                xkey: 'name',
                ykeys: ['price'],
                labels: ['price'],
                barColors: [color]
            });
        }
        if(type == 'donut'){
            Morris.Donut({
                element: div,
                data: data,
                colors: ['#F15854','#4D4D4D','#F17CB0', '#5DA5DA', '#FAA43A', '#60BD68', '#60BD68', '#B2912F', '#B276B2', '#DECF3F'],
                showLabel: "click",
                hideHover: true,
                formatter: function (x,y) {
                    return x+'~#~'+ y.amount;
                }
            }).on('hover', function(i, row){
                  var amountValue = row.value.split('~#~')
                    $('#lb_'+div).html(row.label);
                    $('#qt_'+div).html(countTitle+': '+amountValue[0]);
                    $('#amt_'+div).html(amountTitle+': $'+amountValue[1]);
                    
                $('div#Electrical-Jobs-Listing-Filters tspan').parent().hide();
                $('div#Grassivy-Jobs-Listing-Filters tspan').parent().hide();
                $('div#Service-Jobs-Listing-Filters tspan').parent().hide();
                 
                });
        }
        if(type == 'pie'){
            Morris.Pie({
                element: div,
                data: data,
                colors: ['#F15854','#4D4D4D','#F17CB0', '#5DA5DA', '#FAA43A', '#60BD68', '#60BD68', '#B2912F', '#B276B2', '#DECF3F'],
                showLabel: "click",
                formatter: function (x,y) {
                    return x+'~#~'+ y.amount;
                }
            }).on('hover', function(i, row){
                    var amountValue = row.value.split('~#~')
                    $('#lb_'+div).html(row.label);
                    $('#qt_'+div).html(countTitle+': '+amountValue[0]);
                    $('#amt_'+div).html(amountTitle+': '+amountValue[1]);
                });
        }
    }
    function DataToggel(dataDiv, graphDiv){
        $('#'+dataDiv).toggle(function(){
            $('#'+graphDiv).toggle();
        });
    }
	
	function get_report_data(selection){
		$('#reports_results_box').fadeTo(500,0.2);
		$('#btn_go').hide();
		$('#loading-img').show();
		var mon = $('#m').val();
		var yer = $('#y').val();
		var flag = $("input[name='general_service_jobs_radio']:checked").val();
		
		$.get('get_dashboard_gen_sev_job_panel/'+mon+'/'+yer+'/'+flag+'/'+selection+'/table', function(data){
			//alert(data);
			$('#reports_results_box').html(data);
			$('#reports_results_box').fadeTo(500,1);
			if(selection != 'selection'){
				$('#m').val($('#selected_month').val());
				$('#y').val($('#selected_year').val());
			}
			
			$('#btn_go').show();
			$('#loading-img').hide();
		});
		
		$.get('get_dashboard_gen_sev_job_panel/'+mon+'/'+yer+'/'+flag+'/'+selection+'/chart', function(data1){
			$('#multibar-graph').html('');
			drawGraph("multibar-graph", JSON.parse(data1), "General Service Jobs Report", "Count", "Amount", "#6883a3", "multiBar");
		});
		
		$('#jobs_open_count').html('100');
	}
	</script>
    <script>

function countUp(count, ele)
{
	//alert(count);
	count = parseFloat(count);
	//alert(count);
    var div_by = 100,
        speed = Math.round(count / div_by),
        display = document.getElementById(ele),
        run_count = 1,
        int_speed = 5,
		counter = 0;

    var int = setInterval(function() {
        if(run_count < div_by){
            //display.text(speed * run_count);
			display.innerHTML = speed * run_count;
            run_count++;
        } else if(parseFloat(display.innerHTML) < count) {
            var curr_count = parseFloat(display.innerHTML) + 1;
            //display.text(curr_count);
			display.innerHTML = curr_count;
        } else {
            clearInterval(int);
        }
    }, int_speed);
}


</script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>
 <body>
 <section id="container" class="">
    @include("layouts/dashboard_master/header")
    @include("layouts/dashboard_master/sidebar")
    <section id="main-content">
        <section class="wrapper">
            @yield('small_widgets')   
            @yield('dashboard_panels')   
        </section>
    </section>
    
 </section>
  <!-- js placed at the end of the document so the pages load faster -->
    
    
 </body>
 </html>