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
                JOBS COST RECORD CHECK
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b>Search by:</b><i> Job Cost Date / Name Filter </i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/job_cost_check'), 'files'=>true, 'method' => 'post')) }}
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
                                        {{Form::select('Filter', array(''=>'Select Filter','gpg_job_cost.job_num' => 'Job Number', 'jobType' => 'Job Type'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
                                      </td>
                                      <td id="fval_td" data-title="Filter Value:">
                                        {{ Form::text('FVal','', array('class' => 'form-control', 'id' => 'filter_value')) }}
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>{{ Form::checkbox('not_exist','1','', array('id'=>'not_exist','class' => 'input-group','style'=>'display:inline;')) }} Show Jobs Not Exist.</td>
                                      <td>{{ Form::checkbox('not_inv','1','', array('id'=>'not_inv','class' => 'input-group','style'=>'display:inline;')) }} Show Jobs not Invoiced.</td>
                                      <td>{{ Form::checkbox('out_of_range','1','', array('id'=>'out_of_range','class' => 'input-group','style'=>'display:inline;')) }} Show Jobs are Invoiced But Out Of Range.</td>
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
                  <b>VIEW JOBS COSTS</b><span style="margin-left:150px; color:red;">[Cost Value: ${{number_format($total_sum,2)}}]</span>
              </header>
              <div class="panel-body"> 
              <div class="adv-table">
              <section id="no-more-tables" >
     <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                          <th style="text-align:center;">Cost Job Num</th>
                                          <th style="text-align:center;">Customer</th>
                                          <th style="text-align:center;">Regarding</th>
                                          <th style="text-align:center;">Cost Date </th>
                                          <th style="text-align:center;">Cost Amount</th>
                                          <th style="text-align:center;">Job Records </th>
                                          <th style="text-align:center;">Job Invoice Date</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($query_data as $row)
                                          <tr>
                                            <td>{{$row->cost_job_num}}</td>
                                            <td>{{$row->job_customer}}</td>
                                            <td><?php $str=substr( $row->job_task,0,25); if(strlen( $row->job_task)>25) $str=$str."..."; echo $str;?></td>
                                            <td>{{($row->cost_date!=''?date('m/d/Y',strtotime($row->cost_date)):"-")}}</td>
                                            <td>{{'$'.number_format($row->cost_amount,2)}}</td>
                                            <td>{{(!empty($row->job_job_num)?$row->job_job_num:'<font color="#c10000"><strong>Job Not Exists</strong></font>')}}</td>
                                            <td><?php
                                                if (isset($inv_amt_date[$row->job_id]) && !empty($inv_amt_date[$row->job_id]['invoice_date']) && !empty($inv_amt_date[$row->job_id]['invoice_amount'])) {
                                                  echo '<font color="#c10000">'.(!empty($inv_amt_date[$row->job_id]['invoice_date'])?date('m/d/Y',strtotime($inv_amt_date[$row->job_id]['invoice_date'])):'').' ['.'$'.number_format($inv_amt_date[$row->job_id]['invoice_amount'],2).'] Not In Range</font>';   
                                                }else
                                                  echo '<font color="#c10000">Not Invoiced</font>';
                                            ?></td>
                                          </tr>
                                        @endforeach
                                      </tbody>
                                  </table>
                                  {{ HTML::link("job/excelJobCostExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
                                  <br/>
                {{ $query_data->appends($allInputs)->links() }}
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