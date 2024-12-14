<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>Horizontal Menu</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-reset.css')}}" rel="stylesheet">
    <!--external css-->
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link href="{{asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-datepicker/css/datepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-timepicker/compiled/timepicker.css') }}" />
    <!--<link href="css/navbar-fixed-top.css" rel="stylesheet">-->

    <!-- Custom styles for this template -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/style-responsive.css')}}" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{ asset('js/jquery-ui-1.9.2.custom.min.js') }}"></script>
  </head>

  <body class="full-width">

  <section id="container" class="">
      <!--header start-->
      <header class="header white-bg">
          <div class="navbar-header" style="display:inline;">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                  <span class="fa fa-bars"></span>
              </button>

              <!--logo start-->
              {{ HTML::image(asset('img/gpglogo.jpg'), 'GPG Logo', array('style' => 'display:inline; width:80px; height:50px;')) }}
              <span><br/>12060 Woodside Ave,Lakeside, CA 92040</span>
              <!--logo end-->
              
              <div class="top-nav" style="display:inline;">
              <ul class="nav pull-right top-menu">
                <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <img alt="" src="{{asset('img/avatar1_small.jpg')}}">
                          <span class="username"><?php echo Auth::user()->fname." ".Auth::user()->lname?></span>
                          <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu extended logout">
                          <div class="log-arrow-up"></div>
                          <li><a href="logout"><i class="fa fa-key"></i> Log Out</a></li>
                      </ul>
                  </li>
            </ul>
          </div>

          </div>

      </header>
      <!--header end-->
      <!--sidebar start-->

      <!--sidebar end-->
      <!--main content start-->
     <br/><br/><br/><br/><br/>
         <section class="panel">
         	<div class="panel-body">
              <!-- page start-->
              <section id="no-more-tables"  style="padding:10px;">
                <table class="table table-bordered table-striped table-condensed cf">
                  @if($type == 'labor')
                  <thead class="cf">
                  <tr><th colspan="4">Labor Group by:{{Form::select('sortbylabor', array('date' => 'Date','tech'=>'Technician'), $viewby , ['class'=>'form-control','id'=>'labor'])}}</th><th colspan="2"><span id="excel_export" style="color:red; cursor:pointer;">Excel Export</span></th><th colspan="3">Budgeted Labor Cost:{{ Form::text('budgetedLabor',$jobTblRow['budgeted_labor'], array('class' => 'form-control', 'id' => 'budgetedLabor','style'=>'display:inline;')) }}</th><th colspan="3">Budgeted Hours:{{ Form::text('budgetedHours',$jobTblRow['budgeted_hours'], array('class' => 'form-control', 'id' => 'budgetedHours','style'=>'display:inline;')) }}</th><th colspan="2">Hours Left:{{ Form::text('hoursLeft',$jobTblRow['budgeted_hours'], array('class' => 'form-control', 'id' => 'hoursLeft','readOnly')) }}</th></tr>
                  <tr><th>Tech</th><th>Type</th><th>Date</th><th>Time In</th><th>Time Out</th><th>Total</th><th>Total in decimal</th><th>Reg</th><th>OT</th><th>DT</th><th>Reg $</th><th>OT $</th><th>DT $</th><th>Total $</th></tr>
                  </thead>
                  @elseif($type == 'jobcost')
                  <thead class="cf">
                  <tr><th colspan="3"> Job Cost(s) Sort By:{{Form::select('jobcost', array('date' => 'date','source_name'=>'Source Name','type'=>'Type','split'=>'Split','memo'=>'Memo'), $viewby , ['class'=>'form-control','id'=>'jobcost'])}}</th><th><span id="excel_export" style="color:red; cursor:pointer;">Excel Export</span></th><th colspan="3">Budgeted Material Cost:{{ Form::text('budgetedMaterial',$jobTblRow['budgeted_material'], array('class' => 'form-control', 'id' => 'budgetedMaterial')) }}</th><th colspan="3">Budgeted Material Cost Left:{{ Form::text('budgetedMaterialLeft','', array('class' => 'form-control', 'id' => 'budgetedMaterialLeft','readOnly')) }}</th></tr>
                  <tr><th>Type</th><th>Date</th><th>Num</th><th>Name</th><th>Source Name</th><th>Memo</th><th>Account</th><th>Clr</th><th>Split</th><th>Amount</th></tr>  
                  </thead>
                  @elseif($type == 'jobpo')
                  <thead class="cf">
                  <tr><th colspan="12">Purchase Order(s) Sort By: (&nbsp;<span id="excel_export" style="color:red; cursor:pointer;">Excel Export</span> ){{Form::select('jobpo', array('' => 'Date','poVendor'=>'Vendor','poRequest'=>'Request by'), $viewby , ['class'=>'form-control','id'=>'jobpo'])}}</th></tr>  
                  <tr><th>Po#</th><th>Date</th><th>Job#/GL Code</th><th>Form of Payment</th><th>Vendor</th><th>Req. By</th><th>PO Writer</th><th>Quoted Amt</th><th>Amt to Date</th><th>Estimated Receipt Date</th><th>Sales/Ord.#</th><th>Note</th></tr>
                  </thead>
                  @elseif($type == 'jobpo_detail')
                  <tr><th colspan="10">Purchase Order Detail(s) Sort By:  (&nbsp;<span id="excel_export" style="color:red; cursor:pointer;">Excel Export</span> ){{Form::select('jobpodetail', array('purchase_order_created_on' => 'Po Date','id'=>'Item#','created_on'=>'Item Date'), $viewby , ['class'=>'form-control','id'=>'jobpo_detail'])}}</th></tr>
                  <tr><th>Po#</th><th>Po Date</th><th>Item#</th><th>Item Date</th><th>Job#/GL Code</th><th>Description</th><th>Qty</th><th>Rate</th><th>Amount</th><th>Received</th></tr>
                  @endif
                  <tbody class="cf">
                  {{$display_data}}
                  </tbody>
                </table>
              </section> 
           </div>
        </section>                      
        <!-- page end-->  

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script class="include" type="text/javascript" src="{{asset('js/jquery.dcjqaccordion.2.7.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/hover-dropdown.js')}}"></script>
    <script src="{{asset('js/jquery.scrollTo.min.js')}}"></script>
    <script src="{{asset('js/jquery.nicescroll.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/respond.min.js')}}" ></script>
	<script src="{{ asset('assets/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
	<script src="{{ asset('assets/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/form-component.js')}}"></script>

    <link href="{{asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('assets/data-tables/DT_bootstrap.css')}}" />
    <script type="text/javascript" language="javascript" src="{{asset('assets/advanced-datatable/media/js/jquery.dataTables.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/data-tables/DT_bootstrap.js')}}"></script>
        <script type="text/javascript" src="{{ asset('js/dynamic_table_init.js') }}"></script>
        <!-- morrsi -->
        <link href="{{asset('assets/morris.js-0.4.3/morris.css')}}" rel="stylesheet" />
    <script src="{{asset('assets/morris.js-0.4.3/raphael-min.js')}}" type="text/javascript"></script>
  <script src="{{asset('js/morris.js')}}" type="text/javascript"></script>
  <!--right slidebar-->
  <script src="{{asset('js/slidebars.min.js')}}"></script>

    <!--common script for all pages-->
    <script src="{{asset('js/common-scripts.js')}}"></script>
<script type="text/javascript">
	$('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
      });
  $("select").change(function() {
    var type = $(this).attr('id');
    var viewby = $("select option:selected").val();
    var jid= '<?php echo $job_id;?>';
    var jnum= '<?php echo $job_num;?>'; 
    window.location.href = '{{URL::to("job/job_form_report/'+jid+'/'+jnum+'/'+type+'/'+viewby+'")}}';
  });
    $('#hoursLeft').val('-'+$('#total_hours_calculated').html());
    $('#budgetedHours').change(function(){
        $('#hoursLeft').val(parseInt($(this).val())+parseInt($('#hoursLeft').val()));    
    });
    if ($('#total_material_cost').html() != null) {
      var mat_cost = $('#total_material_cost').html().replace(/,/g,'').split('$');
      var orgi_cost = parseFloat($('#budgetedMaterial').val())-parseFloat(mat_cost[1]);
      $('#budgetedMaterialLeft').val(orgi_cost.toFixed(2));
      $('#budgetedMaterial').change(function(){
        var mat_cost = $('#total_material_cost').html().replace(/,/g,'').split('$');
        var orgi_cost = parseFloat($('#budgetedMaterial').val())-parseFloat(mat_cost[1]);
        $('#budgetedMaterialLeft').val(orgi_cost.toFixed(2));
      });
    }

    $('#excel_export').click(function(){
      var type = $("select").attr('id');
      var viewby = $("select option:selected").val();
      var jid= '<?php echo $job_id;?>';
      var jnum= '<?php echo $job_num;?>'; 
      var budgetedLabor = parseInt($('#budgetedLabor').val()); 
      if (budgetedLabor == '')
        budgetedLabor = 0;
      if (viewby == '')
        viewby = 0;
      //alert(viewby);
      window.location.href = '{{URL::to("job/job_form_report_excel/'+jid+'/'+jnum+'/'+type+'/'+viewby+'/'+budgetedLabor+'")}}';
    });
    
    </script>
  </body>
</html>
