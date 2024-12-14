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
                 GENERAL REPORTS/JOB TIME REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>SEARCH by:</b> Dates / Job Number</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/job_time_report'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                     <th>
                                      {{Form::label('JobTimeSDate', 'Job Time Start Date:*', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                     <th>
                                        {{Form::label('JobTimeEDate', 'End Date:*', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                      <th><b>jobNum</b></th>
                                    </tr>
                                  </thead>
                                  <tbody><tr>
                                  <td data-title="Job Time Start Date:">
                                    {{ Form::text('JobTimeSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'JobTimeSDate','required')) }}
                                   </td><td data-title="End Date:">
                                   {{ Form::text('JobTimeEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'JobTimeEDate','required')) }}
                                   </td>
                                    <td data-title="jobNum:">
                                   <div>
                                      {{Form::text('jobNum','', ['id' => 'jobNum', 'class'=>'form-control m-bot15'])}}
                                    </div>
                                    </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <br/>
                                  {{Form::submit('Generate Report', array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
                                  </section>
                               {{ Form::close() }}
              </section>     
              </section>
              </div>
              </div>
              <!-- page end-->
       <script>
           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
          
          $('#reset_search_form').click(function(){
              $('#JobTimeSDate').val("");
              $('#JobTimeEDate').val("");
              $('#jobNum').val("");
          });
           $('#jobNum').focus(function() {  
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