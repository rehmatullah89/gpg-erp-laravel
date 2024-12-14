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
                  JOBS BY PROJECTED MARGIN/QUOTED AMOUNT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i>  JOB LIST</i></b>
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
                                   <td data-title="Select List:">
                                    {{Form::label('optList', 'Select List:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight:bold;'))}} 
                                    {{ Form::select('optList',array('elist'=>'Electrical Quote List','hlist'=>'Shop Work Quote List','flist'=>'Service Field Work List','glist'=>'Grassivy Quote List','splist'=>'Special Project Quote List'),'', array('class' => 'form-control', 'id' => 'optList')) }}
                                   </td>
                                   <td data-title="Quoted Date Start:">
                                    {{Form::label('sqMade', 'Quoted Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('sqMade','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'sqMade')) }}
                                   </td>
                                   <td data-title="Quoted Date End:">
                                    {{Form::label('eqMade', 'Quoted Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('eqMade','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'eqMade')) }}
                                   </td>
                                   <td data-title="Date Won Start:">
                                    {{Form::label('sqWon', 'Date Won Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('sqWon','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'sqWon')) }}
                                   </td>
                                   <td data-title="Date Won End:">
                                    {{Form::label('eqWon', 'Date Won End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('eqWon','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'eqWon')) }}
                                   </td>
                                   <td data-title="Quoted Amount Start:">
                                    {{Form::label('SQuotedAmount', 'Quoted Amt. Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SQuotedAmount','', array('class' => 'form-control', 'id' => 'SQuotedAmount')) }}
                                   </td>
                                   <td data-title="Quoted Amount End:">
                                    {{Form::label('EQuotedAmount', 'Quoted Amt. End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EQuotedAmount','', array('class' => 'form-control', 'id' => 'EQuotedAmount')) }}
                                   </td>
                                   <td data-title="Projected Margin Start:">
                                    {{Form::label('SProjectedMargin', 'Projec. Margin Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SProjectedMargin','', array('class' => 'form-control', 'id' => 'SProjectedMargin')) }}
                                   </td>
                                   <td data-title="Projected Margin End:">
                                    {{Form::label('EProjectedMargin', 'Projec. Margin End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EProjectedMargin','', array('class' => 'form-control', 'id' => 'EProjectedMargin')) }}
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
                  <th>Serial</th>
                  <th>Quoted Date</th>
                  <th>Job Number</th>
                  <th>Quoted Amount</th>
                  <th>Projected Margin</th>
                  <th>Status </th>
                  <th>Date Won</th>
              </tr>
              </thead>
              <tbody class="cf">
                <?php 
                  $SrNo = 0;
                  foreach($query_data as $key => $row)
                  {
                    $SrNo++;
                    ?>
                    <tr bgcolor="<?php echo $row['date_job_won'] == ' - '? '#FFFFFF':'#DDF0FF' ?>">
                      <td height="30">{{$SrNo}}</td>            
                      <td>{{$row['created_on']}}</td>
                      <td>{{ HTML::link('quote/'.(preg_match("/E/i",$row['job_num'])?"elec_quote_list":(preg_match("/HS/i",$row['job_num'])?'shop_work_quote_list':((preg_match("/M/i",$row['job_num'])) ? "grassivy_quote_list" : ((preg_match("/J/i",$row['job_num']) ? 'specialproject_quote_list' : 'field_service_work_list'))))),$row['job_num'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                      <td>{{'$'.number_format($row['quoted_amount'],2)}}</td>
                      <td>{{'$'.number_format($row['projected_margin'],2)}}</td>
                      <td>{{$row['status']}}</td>
                      <td>{{$row['date_job_won']}}</td>         
                    </tr>
                <?php }?>  
              </tbody>
              </table>
              {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
              <br/>
              {{ HTML::link("qc_reports/excelProjMarjinRepExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
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
          $('#optList').val("elist");
          $('#sqMade').val("");
          $('#eqMade').val("");
          $('#sqWon').val("");
          $('#eqWon').val("");
          $('#SQuotedAmount').val("");
          $('#EQuotedAmount').val("");
          $('#SProjectedMargin').val("");
          $('#EProjectedMargin').val("");
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop