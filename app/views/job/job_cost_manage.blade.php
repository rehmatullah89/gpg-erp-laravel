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
                JOBS COST MANAGEMENT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b>Search by:</b><i>Job Cost Date / Name Filter </i>
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
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/job_cost_manage'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                     <th>
                                      {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                     <th>
                                        {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                      <th><b>Filter</b></th>
                                      <th><b>Filter Value</b></th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td data-title="Start Date:">
                                        {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'start_date')) }}
                                      </td>
                                      <td data-title="End Date:">
                                        {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'end_date')) }}
                                      </td>
                                      <td data-title="Filter:">
                                        {{Form::select('Filter', array(''=>'Select Filter','job_num' => 'Job Number', 'jobType' => 'Job Type','jobCheck'=>'Jobs Dont exist in system'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
                                      </td>
                                      <td id="fval_td" data-title="Filter Value:">
                                        {{ Form::text('FVal','', array('class' => 'form-control', 'id' => 'filter_value')) }}
                                      </td>
                                      <td>{{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                                          {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                      </td>
                                    </tr>
                                   </tbody>
                                </table>
                                  </section>
                               {{ Form::close() }}
              </section>
             <!-- ////////////////////////////////////////// -->
              <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>VIEW JOBS COSTS</b>
              </header>
              <div class="panel-body"> 
              <div class="adv-table">
              <section id="no-more-tables" >
     <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                          <th>Del</th>
                                          <th style="text-align:center;">Job Num</th>
                                          <th style="text-align:center;">Name</th>
                                          <th style="text-align:center;">Type</th>
                                          <th style="text-align:center;">Date</th>
                                          <th style="text-align:center;">Num</th>
                                          <th style="text-align:center;">Source Name</th>
                                          <th style="text-align:center;">Memo </th>
                                          <th style="text-align:center;">Account</th>
                                          <th style="text-align:center;">Clr</th>
                                          <th style="text-align:center;">Split</th>
                                          <th style="text-align:center;">Amount</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($query_data as $row)
                                          <tr>
                                            <td>
                                             {{ Form::open(array('method' => 'post','id'=>'myForm'.$row->id.'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('job/destroyJobCost', $row->id))) }}
                                             {{Form::hidden('id',$row->id)}}
                                             {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row->id.'").submit()')) }}
                                             {{ Form::close() }}
                                            </td>
                                            <td>{{$row->job_num}}</td>
                                            <td><?php $nameJonNum = preg_split('/:/',$row->name); echo $nameJonNum[0]; ?></td>
                                            <td>{{$row->type}}</td>
                                            <td>{{($row->date!=''?date('m/d/Y',strtotime($row->date)):"-")}}</td>
                                            <td>{{$row->num}}</td>
                                            <td>{{$row->source_name}}</td>
                                            <td>{{$row->memo}}</td>
                                            <td>{{$row->account}}</td>
                                            <td>{{$row->clr}}</td>
                                            <td>{{$row->split}}</td>
                                            <td>{{'$'.number_format($row->amount,2)}}</td>
                                          </tr>
                                        @endforeach
                                      </tbody>
                                  </table>
                                  {{ HTML::link("job/excelJobCostManageExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
                                  <br/>
                {{$query_data->appends($allInputs)->links()}}
              </section>
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
       <script>
       $(document).ready(function(){
          $('#reset_search_form').click(function(){
              $('#start_date').val("");
              $('#end_date').val("");
              $('#Filter').val("");
              $('#filter_value').val("");
              $('#fval_td').html('<input id="FVal" type="text" class="form-control m-bot15" style="display: block;" value="" name="FVal">');
              $('#not_exist').attr('checked', false);
              $('#not_inv').attr('checked', false);
              $('#out_of_range').attr('checked', false);
            });
          
          $('#Filter').change(function(){
            if($("option:selected", this).val() == 'jobType'){
              $('#fval_td').html('<select name="jobType" class="form-control m-bot15"><option Value="gpg">Electrical Jobs</option><option Value="service">Service Jobs</option></select>');
            }else if($("option:selected", this).val() == 'jobCheck'){
              $('#fval_td').html('<input id="FVal" class="form-control m-bot15" type="text" style="display: block;" value="" name="FVal" readOnly>');
            }else{
              $('#fval_td').html('<input id="FVal" class="form-control m-bot15" type="text" style="display: block;" value="" name="FVal">');
            }
          });

           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
            
       });   
      </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop