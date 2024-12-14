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
                WRONG PREVAILING JOBS
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>SEARCH by:</b> Job Number</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/wrong_prevailing_jobs_report'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                    <tbody>
                                    <tr>
                                      <td><b>Job Number: </b></td>
                                      <td>
                                        {{Form::text('optJobnum','', ['id' => 'optJobnum', 'class'=>'form-control m-bot15'])}}
                                      </td>
                                      <td>
                                        {{Form::submit('Search', array('class' => 'btn btn-success'))}}
                                        {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                      </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <br/>
                                  </section>
                               {{ Form::close() }}
              </section> 
                <section id="no-more-tables" style="padding:10px;">
                  <b>{{count($query_data)}}</b> entries found
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Serial</th>
                        <th>Job Number</th>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php $SrNo=1;?>
                     @foreach($query_data as $row)
                       <tr>
                          <td height="30">{{$SrNo++}}</td>
                          <td>{{ HTML::link('job/job_form/'.$row['GPG_job_id'].'/'.$row['job_num'].'', $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$row['GPG_job_id'],'j_num'=>$row['job_num']))}}</td>
                          <td>{{date('m/d/Y',strtotime($row['date']))}}</td>
                          <td>{{$row['time_in']}}</td>
                          <td>{{$row['time_out']}}</td>
                      </tr>
                     @endforeach
                    </tbody>  
                  </table>  
                </section>  
              </section>
              </div>
              </div>
              {{ HTML::link("qc_reports/excelWrongPrevJobExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
              <br/>
              {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
       <script>
           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
          
          $('#reset_search_form').click(function(){
              $('#optJobnum').val("");
          });
           $('#optJobnum').focus(function() {  
              $(this).autocomplete({
                source: function (request, response) {
                $("span.ui-helper-hidden-accessible").before("<br/>");  
                $.ajax({
                    url: "{{URL('ajax/getJobNumberAutocomplete')}}",
                    data: {
                        JobNumber: this.term
                    },
                success: function (data) {
                  response( $.map( data, function( item ) {
                  return {
                      label: item.name,
                      value: item.id
                  };
                }));
                },
               });
              },
             });
            });
        </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop