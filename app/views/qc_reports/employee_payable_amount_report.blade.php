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
                  EMPLOYEE PAYABLE AMOUNT REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i>Employee Name/ Type</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Start Date:" style="width:12.5%;">
                                    {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td>
                                  <td data-title="End Date:" style="width:12.5%;">
                                    {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Filter:" style="width:12.5%;">
                                    {{Form::label('employee', 'Filter:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight:bold;'))}} 
                                    {{ Form::select('employee',array(''=>'ALL')+$emp_arr,'', array('class' => 'form-control', 'id' => 'employee')) }}
                                  </td>
                                   <td data-title="Job Start Number:" style="width:12.5%;">
                                    {{Form::label('SJobNumber', 'Job Start Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SJobNumber','', array('class' => 'form-control', 'id' => 'SJobNumber')) }}
                                  </td>
                                  <td data-title="Job End Number:" style="width:12.5%;">
                                    {{Form::label('EJobNumber', 'Job End Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EJobNumber','', array('class' => 'form-control', 'id' => 'EJobNumber')) }}
                                  </td>
                                  <td style="width:12.5%;">
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
                    <table class="table table-bordered table-striped table-condensed cf">
                      <tr>
                        <td>
                        {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/excelEmpPayableAmtRepExport'), 'files'=>true, 'method' => 'get')) }}
                          <select name="Etype" class="form-control">
                            <option value="1">Summarized Report</option>
                              <option value="2">Job Detail Report</option>
                              <option value="0">Detailed Report</option>
                          </select>
                        </td>
                        <td>
                          <input type="submit" value="EXPORT >>" class="btn btn-success">
                        </td>
                        {{ Form::close() }}  
                      </tr>
                    </table><br/>
                    <table class="table table-bordered table-striped table-condensed cf" >
                      <thead>
                        <tr>
                          <th>Action</th>
                          <th>Employee Name</th>
                          <th>Employee Rate</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                        $SDate = Input::get("SDate");
                        $EDate = Input::get("EDate");
                        $SJobNumber = strtoupper(Input::get("SJobNumber"));
                        $EJobNumber = strtoupper(Input::get("EJobNumber"));
                        $Filter = Input::get("Filter");
                        $FVal = Input::get("FVal");
                        $colcount=0;
                        $jj = 0;
                        $array_check_row = array() ;
                        $previous_employee_name = "";
                        foreach ($query_data as $key => $row) { 
                          $perHourLabor = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$row['GPG_employee_Id'])->where('type','=','h')->where('start_date','<=',$row['timesheet_date'])->pluck('rate');
                        if(!isset($array_check_row[$row['employee_name']])){
                          $colcount++;
                          $previous_employee_name = $row['employee_name'] ;
                        ?>
                          <tr  bgcolor="<? echo ($colcount%2==0?"#FFFFCC":"#FFFFFF"); ?>" <? if ($Filter=="new_member" and $colcount <=($FVal-$limit*($page-1))) { ?>bgcolor="#FFEBBB"<? } ?>>
                            <td align="center">{{Form::button('<i class="fa fa-plus"></i>', array('class' => 'btn btn-success btn-xs', 'onclick'=>'toggleCustomerInfo('.$colcount.','.$row["GPG_employee_Id"].')'))}}</td>
                            <td height="30" align="center" ><strong>{{$row['employee_name']}}</strong></td>
                            <td align="center"><strong style="padding-right:200px">{{$perHourLabor}}</strong></td>
                          </tr>
                          <tr id="hideme_{{$colcount}}" bgcolor="#FFFFCC"><td colspan="6">
                          <table class="table table-bordered table-striped table-condensed cf">
                            <thead>
                              <tr>
                                <th>&nbsp;</th><th>Customer Name</th><th>Employee Rate</th><th>Wage Rate</th><th>H & W Rate</th><th>Pension</th><th>Vacations</th><th>Training</th><th>Other</th><th>Total</th><th>Hours</th>
                              </tr>
                            </thead>
                             <tbody id="show_detail_data_{{$row['GPG_employee_Id']}}">
                               
                             </tbody>
                          </table>
                          </td></tr>
                        <? }
                          $array_check_row[$row['employee_name']] = $row['employee_name'] ;
                        } ?>
                      </tbody>
                    </table>
                  </section>
                </div>
              </section>
              </div>
        </div>
        </div>
      <!-- modal #2 end-->
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
          $('#employee').val("");
          $('#SJobNumber').val("");
          $('#EJobNumber').val("");
      });
      function toggleCustomerInfo(id,emp_id){
          $('#hideme_'+id).toggle();
          var SDate = $('#SDate').val();
          var EDate = $('#EDate').val();
          var SJobNumber = $('#SJobNumber').val();
          var EJobNumber = $('#EJobNumber').val();
          $.ajax({
              url: "{{URL('ajax/getEmpPayableAmtInfo')}}",
                data: {
                 'SDate' : SDate,
                 'EDate' : EDate,
                 'emp_id' : emp_id,
                 'SJobNumber' : SJobNumber,
                 'EJobNumber' : EJobNumber
                },
              success: function (data) {
                $('#show_detail_data_'+emp_id).html(data);
              },
          });
      }
      $( document ).ready(function() {
        var cnt = 100;  
        var icnt = 1;
        while(icnt <= cnt){
            $('#hideme_'+icnt).hide();
            icnt = parseInt(icnt) + parseInt("1");
         }
    });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop