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
                LINK BILL ONLY JOBS 
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                      <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                        <b><i>LINK BILL ONLY JOBS </i></b>
                      </header>
              </section>
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
             <!-- ////////////////////////////////////////// -->
             {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/link_jobs'), 'id'=>'frmid1', 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;" id="mytable">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">SELECT BILL ONLY JOB</th>
                            <th style="text-align:center;">SELECT LINK TO JOB</th>
                            <th style="text-align:center;">Actions</th>
                           </tr>
                        </thead>
                      <tbody>
                        <tr>
                          <td data-title="#Name">
                            {{ Form::text('bill_only_job_num','', array('class' => 'form-control dpd1', 'id' => 'bill_only_job_num', 'required')) }}
                          </td>
                          <td data-title="#Date">
                            {{ Form::text('link_to_job_num_0','', array('class' => 'form-control', 'id' => 'link_to_job_num_0', 'required')) }}
                            {{ Form::hidden('count_records',1, array('id' =>'count_records','required')) }}
                          </td>
                          <td data-title="Actions:">
                            {{Form::button('<i class="fa fa-plus-circle"></i>', array('class' => 'btn btn-primary','id'=>'add_new_row','style'=>'width:20%; display:inline;'))}}
                            {{ Form::button('Consolidate & Complete Job', array('class' => 'btn btn-success','style'=>'width:60%; display:inline;','id'=>'LinkJobId'))}}
                          </td>
                          </tr>
                      </tbody>
                  </table>                                
              </section>
              </div>
              </div>
              {{ Form::close() }}
              </section>
              </div>
              </div>
              <!-- page end-->
    <script type="text/javascript">
       $('#bill_only_job_num').focus( function() {  
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
       $('#link_to_job_num_0').focus( function() {  
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
      var count = $('input#count_records').val();
      $('#add_new_row').click( function() {
          var str = "<tr><td></td><td><input type='text' name='link_to_job_num_"+count+"' id='link_to_job_num_"+count+"' class='form-control'></td><td></td></tr>";
          $('#mytable > tbody:last').append(str);
            $('#link_to_job_num_'+count).focus( function() {  
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
            count = parseInt(count) + parseInt("1");
            $('input#count_records').val(count);
      });
      
      $('#LinkJobId').click(function(){
          var bill_only_job_num = $('#bill_only_job_num').val();
          if(bill_only_job_num == '')
            alert('Please Provide valid vlaues!');
          else{
            var conf = confirm("Warning! The Select jobs will be assigned the Billing Job and status will be changed to Completed. Do you want to continue....");
            if(conf == true && $('#bill_only_job_num').val() != '' && $('#bill_only_job_num').val() != 'link_to_job_num_0'){ 
              $('#frmid1').submit();
            }
          }
      });      
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop