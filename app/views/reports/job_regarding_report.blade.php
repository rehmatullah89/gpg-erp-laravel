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
                  JOB REGARDING REPORT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Dates / Filters</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Date Start:">
                                    {{Form::label('SDate', 'Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td>
                                  <td data-title="Date End:">
                                    {{Form::label('EDate', 'Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Date Scheduled Start:">
                                    {{Form::label('SchDate1', 'Date Scheduled Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SchDate1','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SchDate1')) }}
                                  </td>
                                  <td data-title="Date Scheduled End:">
                                    {{Form::label('SchEDate1', 'Date Scheduled End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SchEDate1','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SchEDate1')) }}
                                  </td>
                                  <td data-title="Job Status:">
                                    {{Form::label('optJobStatus', 'Job Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('optJobStatus', array(""=>"ALL","completed"=>"Have been Completed","notcompleted"=>"Jobs Not Completed"), null, ['id' => 'optJobStatus', 'class'=>'form-control m-bot15'])}}
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
                  <th>Customer</th>
                  <th>Fuel Delivery</th>
                  <th>Coolant Flush </th>
                  <th> Fuel Polish   </th>
                  <th>Fuel Sample</th>
                  <th>Load Bank Test</th>
                  <th>Permit Fine </th>
                  <th>Repair </th>
                  <th>Service Engine-Fire Pump </th>
                  <th>Service Generator </th>
                  <th>Total </th>
              </tr>
              </thead>
              <tbody class="cf">
                <?php 
                  $colcount=0;
                  $grandTot = array();
                  foreach($query_data as $key=>$value) {
                    $tot = 0;
                    $entry=0;
                    $entry2=0;
                    $colcount++;
                    echo "<tr  bgcolor=\"".($colcount%2==0?"#FFFFCC":"#FFFFFF")."\">";
                    echo "<td height=\"35\" align = \"center\"  nowrap>&nbsp;".$key."&nbsp;</td>";
                    foreach($regardingArray as $key1=>$value1) {
                      if (!isset($grandTot[$value1]["tot"]) && $entry==0){
                        $grandTot[$value1]["tot"] =0;
                        $entry++;
                      }
                      if (!isset($value[$value1]["cnt"]) && $entry2==0){
                        $value[$value1]["cnt"] =0;
                        $entry2++;
                      }
                      echo "<td  align = \"center\"  nowrap>".HTML::link('job/service_job_list',isset($value[$value1]["cnt"])?$value[$value1]["cnt"]:'-', array('class'=>'btn btn-link btn-xs'))."</td>";
                      @$grandTot[$value1]["tot"] = @$grandTot[$value1]["tot"] + @$value[$value1]["cnt"];
                      $tot = $tot + @$value[$value1]["cnt"];
                    }
                    echo "<td height=\"35\" align = \"center\"  nowrap>&nbsp;<strong>".$tot."</strong>&nbsp;</td>";
                    echo "</tr>";
                  }     
                ?>
              </tbody>
              </table>
              {{ HTML::link("reports/excelRegardingReportExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
              <br/>
             {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
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
          $('#SchDate1').val("");
          $('#SchEDate1').val("");
          $('#optJobStatus').val("");
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop