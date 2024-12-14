@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
              <!-- page start-->
          <div class="row">
            <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                  SERVICE JOBS CONTRACT VIEW
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>Service Job's Contract Listing </i></b>
                </header>
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Start Date:">
                                    {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td><td data-title="End Date:">
                                    {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Contract Number Start:">
                                    {{Form::label('CNumberStart', 'Contract Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CNumberStart','', array('class' => 'form-control', 'id' => 'CNumberStart')) }}
                                  </td>
                                  <td data-title="Contract Number End:">
                                    {{Form::label('CNumberEnd', 'Contract Number End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CNumberEnd','', array('class' => 'form-control', 'id' => 'CNumberEnd')) }}
                                  </td>
                                   <td data-title="Job Number Start:">
                                    {{Form::label('JNumberStart', 'Job Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('JNumberStart','', array('class' => 'form-control', 'id' => 'JNumberStart')) }}
                                  </td>
                                  <td data-title="Job Number End:">
                                    {{Form::label('JNumberEnd', 'Job Number End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('JNumberEnd','', array('class' => 'form-control', 'id' => 'JNumberEnd')) }}
                                  </td>
                                  <td data-title="Status:">
                                    {{Form::label('jobstatus', 'Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('jobstatus', array(''=>'ALL','incomplete'=>'Incomplete','complete'=>'Complete'), null, ['id' => 'jobstatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                </tr>
                                <tr>
                                <td colspan="5">
                                  {{ Form::checkbox('invoice_data','1','', array('id'=>'invoice_data','class' => 'input-group','style'=>'display:inline;')) }}
                                  Invoice Amounts
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  {{ Form::checkbox('material_cost','1','', array('id'=>'material_cost','class' => 'input-group','style'=>'display:inline;')) }}
                                  Material Cost 
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  {{ Form::checkbox('labor_cost','1','', array('id'=>'labor_cost','class' => 'input-group','style'=>'display:inline;')) }}
                                  Labor Cost 
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  {{ Form::checkbox('cost_to_dat','1','', array('id'=>'cost_to_dat','class' => 'input-group','style'=>'display:inline;')) }}
                                  Cost to Date
                                </td>
                                <td colspan="2">
                                  {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                  {{HTML::link("job/serviceContractExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export as Excel' , array('class'=>'btn btn-success'))}}
                                </td>
                                </tr>
                              </tbody>
                          </table>
                      </section>
                               {{ Form::close() }}
              </section>
              </section>
              </div>

              <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <div class="panel-body">
                <sectionid="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr>
                            <th>Total Contracts</th>  
                            <th>Total Jobs</th>  
                            <th>Completed Jobs</th>  
                            <th>Incomplete Jobs</th>  
                          </tr>
                        </thead> 
                        <tbody class="cf">
                          <tr>
                            <td>{{count($qry_data)}}</td>
                            <td>{{$total_all}}</td>
                            <td>{{$total_c}}</td>
                            <td>{{$total_ic}}</td>
                          </tr>
                        </tbody>
                  </table>
                </section>
              </div>
              </section>
                </div>
              </div>

                <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              
              <div class="panel-body">
              <div class="adv-table">
              <table class="display table table-bordered">
              <thead>
              <tr>
                  <th>Action</th>
                  <th>Contract #</th>
                  <th>Task</th>
                  <th>Count</th>
                  <th class="hidden-phone">Complete</th>
                  <th class="hidden-phone">In-Complete</th>
              </tr>
              </thead>
              <tbody>
              <?php $index=1;?>
                    @foreach($qry_data as $key=>$arr_data)
                      <tr>
                        <td data-title="Actions:">{{Form::button('<i class="fa fa-plus"></i>', array('class' => 'btn btn-success', 'onclick'=>'toggleCustomerInfo('.$index.')'))}} </td>  
                        <td data-title=":">{{$key}}</td>
                        <td data-title=":">{{$arr_data['cus_name']}}</td>
                        <td data-title=":" class="hidden-phone">{{$arr_data['count']['total']+1}}</td>
                        <td data-title=":" class="hidden-phone">{{'-'}}</td>
                        <td data-title=":" class="hidden-phone">{{$arr_data['count']['incomplete']+1}}</td>
                      </tr>
                    <tr id="hideme_{{$index}}">
                      <td colspan="6">
                          <table class="table table-bordered table-striped table-condensed cf">
                           @foreach($arr_data as $k=>$val)  
                           @if($k != 'count' && $k != 'cus_name' && $k != 'service_types')    
                            <tr><th>Contract#</th><td>{{$k}}</td></tr>
                            <tr><th>Job Number</th><th>Location</th><th>Task</th></tr>
                            @foreach($val as $k2=>$v2)
                              @if($k2 != 'cus_name' && $k2 != 'count')
                              <tr>
                                <td>{{$k2}}</td><td>{{$v2['location']}}</td><td>{{$v2['task']}}</td>
                              </tr>
                              @endif
                            @endforeach
                          @endif  
                          @endforeach
                        </table>
                        </td>
                    <?php $index++;?>  
                    @endforeach
                  </tr>  
                </tbody>
              </table>
            </div>
            </div>  
          </section>
        </div>
        </div>      
      </div>
              <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
      });
   
      $("section[mysection=hide_n_show]").hide();
      $('#togglerButton').click(function(){
         $("section[mysection=hide_n_show]").toggle("slow");
         if ($('#toggle_div_plus').attr("class") == "fa fa-plus")
            $('#toggle_div_plus').removeClass('fa fa-plus').addClass('fa fa-minus');
         else 
            $('#toggle_div_plus').removeClass('fa fa-minus').addClass('fa fa-plus');
      }); 

      $('#reset_search_form').click(function(){
              $('#SDate').val("");
              $('#EDate').val("");
              $('#CNumberStart').val("");
              $('#CNumberEnd').val("");
              $('#JNumberStart').val("");
              $('#JNumberEnd').val("");
              $('#jobstatus').val("");
      });
    
      $('input[type=radio][name=job_status]').change(function(){
          if ($(this).attr("id") == 'radio2'){
            $('#label_onChange').html("Completed Date:");
            $('#value_onChange').html('<input class="form-control form-control-inline input-medium default-date-picker"  size="16" name="date_completed" id="date_completed" type="text" value="" />');
            $('.default-date-picker').datepicker({
                format: 'yyyy-mm-dd'
            });
          }else{
            $('#label_onChange').html("");
            $('#value_onChange').html("");
          }

      });
    

    $( document ).ready(function() {
        var cnt = '{{count($qry_data)}}';  
        var icnt = 1;
        while(icnt <= cnt){
            $('#hideme_'+icnt).hide();
            icnt = parseInt(icnt) + parseInt("1");
         }
    });
    function toggleCustomerInfo(id){
      $('#hideme_'+id).toggle();
    }
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop