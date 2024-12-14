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
                  JOBS MANAGEMENT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>Jobs Management</i></b>
                </header>
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <thead>
                              <tr>
                                <th>
                                   {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                </th>
                                <th>
                                  {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('Filter', 'Filter:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('FVal', 'Filter Value:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  Actions
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                  <td data-title="Start Date:">
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td>
                                  <td data-title="End Date:">
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Filter:">
                                    {{Form::select('Filter', array(''=>'Select','job_num'=>'Job Number','location'=>'Location','status'=>'Status'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                   <td id="show_hide_val" data-title="Filter Value:">
                                    <?php 
                                    $Filter = Input::get('Filter');
                                    $status = Input::get('status');
                                    ?>
                                    @if($Filter == 'status')
                                    <select name="status" class="form-control"><option value="1" <?php echo ($status=="1")?'selected':'';?>>Assigned</option><option value="2" <?php echo ($status=="2")?'selected':'';?>>Un-assigned</option></select>
                                    @else
                                    {{ Form::text('FVal','', array('class' => 'form-control', 'id' => 'FVal')) }}
                                    @endif
                                  </td>
                                  <td>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
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
                <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" >
                        <thead class="cf">
                          <tr>
                            <th>Total Jobs</th>  
                            <th>Completed Jobs</th>  
                            <th>Assigned Jobs</th>  
                            <th>Unassigned Jobs</th>  
                          </tr>
                        </thead> 
                        <tbody class="cf">
                          <tr>
                            <td data-title="Total Jobs">{{$total_jobs}}</td>
                            <td data-title="Completed Jobs">{{$comp_jobs}}</td>
                            <td data-title="Assigned jobs">{{$assign_jobs}}</td>
                            <td data-title="Unassigned Jobs">{{$uassign_jobs}}</td>
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
              <section id="no-more-tables" >
              <table class="table table-bordered table-striped table-condensed cf" >
              <thead>
              <tr>
                  <th>Action</th>
                  <th>id#</th>
                  <th>Job Number </th>
                  <th>Job Category </th>
                  <th >Wage Plan </th>
                  <th >Customer</th>
                  <th >Status</th>
                  <th >Priority</th>
                  <th >Complete</th>
              </tr>
              </thead>
              <tbody>
              <?php $index=1;?>
                @foreach($qry_data as $row)
                  <tr>
                    <td data-title="Action:">
                    {{Form::button('<i class="fa fa-plus"></i>', array('class' => 'btn btn-success btn-xs', 'onclick'=>'toggleCustomerInfo('.$index.')'))}}
                    <a href="{{URL::route('job.edit', array('id'=>$row['id']))}}">
                      {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                    </a>  
                    </td>
                    <td data-title="JOb id:">{{$row['id']}}</td>
                    <td data-title="Job Num:">{{$row['job_num']}}</td>
                    <td data-title="Job Type:">{{$row['GPG_job_type_name']}}</td>
                    <td data-title="Price:">{{number_format($row['price'],2)}}</td>
                    <td data-title="Customer:">{{$row['gpg_customer_name']}}</td>
                    <td data-title="Assigned to:">{{(isset($row['GPG_employee_id'])?'Assigned':'Un-assigned')}}</td>
                    <td data-title="Priority:">{{$row['priority']}}</td>
                    <td data-title="Completed:">{{'-'}}</td>
                  </tr>
                  <tr id="hideme_{{$index}}"><td colspan="9">
                    <table class="table table-bordered table-striped table-condensed cf">
                      <thead>
                        <tr>
                          <th>Location</th><th>Task</th><th>Sub Task</th><th>Generator Size</th><th>Assigned To</th>
                        </tr>
                      </thead>
                       <tbody>
                         <tr>
                           <td>{{($row['location']!=""?@$row['location']:"-")}}</td>
                           <td>{{($row['task']!=""?@$row['task']:"-")}}</td>
                           <td>{{($row['sub_task']!=""?@$row['sub_task']:"--")}}</td>
                           <td>{{($row['generator_size']!=""?@$row['generator_size']:"-")}}</td>
                           <td>{{($row['GPG_employee_id']!=""?@$row['GPG_employee_id']:"-")}}</td>
                         </tr>
                       </tbody>
                    </table>
                  </td>  
                  </tr>
                  <?php $index++;?>
                @endforeach    
              </tbody>
              </table>
              {{ $qry_data->appends(array_filter(Input::except('_token')))->links() }}
            </section>
            </div>  
          </section>
        </div>
        </div>      
      </div>
              <!-- page end-->
    <script type="text/javascript">
      $(function () {
      
          $("#SDate").datepicker({
              numberOfMonths: 2,
              onSelect: function (selected) {
                  var dt = new Date(selected);
                  dt.setDate(dt.getDate() + 1);
                  $("#EDate").datepicker("option", "minDate", dt);
              }
          });

          $("#EDate").datepicker({
              numberOfMonths: 2,
              onSelect: function (selected) {
                  var dt = new Date(selected);
                  dt.setDate(dt.getDate() - 1);
                  $("#SDate").datepicker("option", "maxDate", dt);
                    }
           });
      });

      $("section[mysection=hide_n_show]").hide();
      $('#togglerButton').click(function(){
         $("section[mysection=hide_n_show]").toggle("slow");
         if ($('#toggle_div_plus').attr("class") == "fa fa-plus")
            $('#toggle_div_plus').removeClass('fa fa-plus').addClass('fa fa-minus');
         else 
            $('#toggle_div_plus').removeClass('fa fa-minus').addClass('fa fa-plus');
      }); 
      $('#Filter').on('change',function(){
        var vl = $(this).val();
        if (vl == 'status') {
          $('#show_hide_val').html('<select name="status" class="form-control"  value="{{$Filter}}"><option value="1">Assigned</option><option value="2">Un-assigned</option></select>');
        }else{
          $('#show_hide_val').html('<input type="text" value="" name="FVal" id="FVal" class="form-control">');
        }
      });

      $('#reset_search_form').click(function(){
              $('#SDate').val("");
              $('#EDate').val("");
              $('#Filter').val("");
              $('#show_hide_val').html('<input type="text" value="" name="FVal" id="FVal" class="form-control">');
              $('#FVal').val("");
      });
    $( document ).ready(function() {
        var cnt = 100;  
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