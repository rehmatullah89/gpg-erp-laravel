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
                SALES TRACKING (CONTACT/QUOTE PHASE REPORT)
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Sales Tracking Date & Filters</i></b>
                </header>
                  @if (isset($errors) && ($errors->any()))
                              <div class="alert alert-danger">
                                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                                  <h4>Error</h4>
                                     <ul>
                                      {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                                     </ul>
                              </div>
                          @endif
                          @if(@Session::has('success'))
                              <div class="alert alert-success alert-block">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                                 <h4>Success</h4>
                                  <ul>
                                  {{ Session::get('success') }}
                                 </ul>
                              </div>
                          @endif
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('salestracking/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <thead>
                              <tr>
                                <th>
                                   {{Form::label('SDate', 'Date Created Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                </th>
                                <th>
                                  {{Form::label('EDate', 'Date Created Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('lead_id_start', 'Lead Id Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('lead_id_end', 'Lead Id End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('Filter', 'Filter:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('FVal', 'Filter Value:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                 <th>
                                  {{Form::label('cusid', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                 <th>
                                  {{Form::label('days_order', 'Order by:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
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
                                   <td data-title="Lead Id Start:">
                                    {{ Form::text('lead_id_start','', array('class' => 'form-control', 'id' => 'lead_id_start')) }}
                                  </td>
                                  <td data-title="Lead Id End:">
                                    {{ Form::text('lead_id_end','', array('class' => 'form-control', 'id' => 'lead_id_end')) }}
                                  </td>
                                  <td data-title="Filter:">
                                    {{Form::select('Filter', array(''=>'Select Filter','sinceLastContact'=>'Since The Last Contact','daysAsOfToday'=>'As of Today','activeDays'=>'Active Contact/Quote'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                   <td data-title="Filter Value:">
                                    {{ Form::text('FVal','', array('class' => 'form-control', 'id' => 'FVal')) }}
                                  </td>
                                  <td data-title="Customer:">
                                    {{Form::select('cusid', array(''=>'ALL')+$cus_arr, null, ['id' => 'cusid', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Order by:">
                                    {{Form::select('days_order', array('daysAsOfToday~DESC'=>'Days Greatest First','daysAsOfToday~ASC'=>'Days Smallest First','a.enter_date~DESC'=>'Created Date Latest First','a.enter_date~ASC'=>'Created Date Earliest First'), null, ['id' => 'days_order', 'class'=>'form-control m-bot15'])}}
                                  </td>
                              </tr>
                            </tbody>
                          </table>
                          {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                          {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
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
                  <th>Created Date</th>
                  <th>Lead ID</th>
                  <th>Customer</th>
                  <th >Location</th>
                  <th >Sales Person</th>
                  <th >Days As of Today</th>
                  <th >Contact Date</th>
                  <th >Activity</th>
                  <th >Out Come of Activity</th>
                  <th >Days Since Created Date</th>
                  <th >Days Since Last Contact Date</th>
              </tr>
              </thead>
              <tbody class="cf">
                <?php
                  $preLead = '';
                  $preContactDate = '';
                  $fg = false;
                ?>
                  @foreach($query_data as $salesTrackingRow)
                  <?php
                    if ($preLead!=$salesTrackingRow['lead_id']){
                      $fg= !$fg;
                    }
                  ?>
                  <tr>
                  <?php if ($preLead!=$salesTrackingRow['lead_id']){
                      $preContactDate ='';
                  ?>
                    <td>{{date('m/d/Y',strtotime($salesTrackingRow['lead_entered']))}}</td>
                    <td>{{$salesTrackingRow['lead_id']}}</td>
                    <td>{{$salesTrackingRow['customer']}}</td>
                    <td>{{$salesTrackingRow['lead_loaction']}}</td>
                    <td>{{$salesTrackingRow['salesPerson']}}</td>
                    <td>{{$salesTrackingRow['daysAsOfToday']}}</td>
                  <?php } else {
                   ?>
                    <td align="center" colspan="6">-</td>
                  <?php }?>
                    <td>{{date('m/d/Y',strtotime($salesTrackingRow['contact_entered']))}}</td>
                    <td>{{$salesTrackingRow['contact_details']}}</td>
                    <td>{{$salesTrackingRow['contact_note']}}</td>
                    <td>{{$salesTrackingRow['daysSinceCreated']}}</td>
                    <td><?php if(!empty($preContactDate) && !empty($salesTrackingRow['contact_entered'])) { 
                      $date1 = new DateTime($preContactDate);
                      $date2 = new DateTime($salesTrackingRow['contact_entered']);
                      $interval = $date1->diff($date2);
                      echo $interval->d;
                      }
                    ?></td>
                  </tr>
                  <?php 
                    $preContactDate = $salesTrackingRow['contact_entered'];
                    $preLead = $salesTrackingRow['lead_id'];
                  ?>
                  @endforeach
              </tbody>
              </table>
              {{ HTML::link("salestracking/excelSTExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}<br/>
             {{ $query_data->links() }}   
            </section>
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
          $('#Filter').val("");
          $('#FVal').val("");
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop