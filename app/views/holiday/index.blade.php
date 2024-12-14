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
                 Holiday MANAGEMENT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>View/ Edit/ Delete: holidays. </i></b>
                          </header>
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">#</th>
                            <th style="text-align:center;">Description</th>
                            <th style="text-align:center;">Day</th>
                            <th style="text-align:center;">Action</th>
                          </tr>
                        </thead>
                      <tbody>
                      <?php
                      $dataArray = array();
                      ?>
                        @foreach($query_data as $data)
                          <?php
                          $dataArray[$data->id] = array('name' =>$data->name , 'date' =>$data->date );
                          ?>
                          <tr>
                            <td data-title="#ID">{{ $data->id }}</td>
                            <td data-title="#name">{{ $data->name }}</td>
                            <td data-title="Date"> {{date("l F d, Y",strtotime( $data->date))}}</td>
                            <td data-title="action">
                            <a style="display:inline;" data-toggle="modal" href="#myModal" class="link_id" id="<?php echo $data->id;?>">
                            {{ Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs', 'title'=>'Manage Holidays.')) }}
                            </a>
                            {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$data->id.'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('holiday.destroy', $data->id))) }}
                            {{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$data->id.'").submit()')) }}         
                            {{ Form::close() }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
                  {{ $query_data->links() }}                                  
              </section>
                      <!-- ************************** Modal ***************************** -->
                              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{ Form::button('&times;', array('class' => 'close', 'data-dismiss'=>'modal','aria-hidden'=>'true')) }}
                                              <h4 class="modal-title">Update Holiday</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="form-group">
                                                  {{Form::label('holiday_desc', 'Holiday Description*:', array('class' => 'control-label col-md-2'))}}
                                                  <div class="col-md-6">
                                                    {{ Form::text('holiday_desc','', array('class' => 'form-control dpd1', 'id' => 'holiday_desc', 'required')) }}
                                                    {{ Form::hidden('holiday_id','', array( 'id'=>'holiday_id')) }}     
                                                  </div>
                                              </div><br/><br/>
                                              <div class="form-group">
                                                   {{Form::label('holiday_desc', 'Select Date*:', array('class' => 'control-label col-md-2'))}}
                                                  <div class="col-md-6">
                                                    {{ Form::text('DOB','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'DOB', 'required')) }}
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                              {{ Form::submit('Submit', array('class' => 'btn btn-success','id'=>'submit_holiday_info')) }}
                                              {{ Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal')) }}
                                          </div>
                                      </div>
                                  </div>
                              </div>
                      <!-- modal -->
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd',
          minDate: new Date()
      });

     $("a.link_id").click(function(){
        var dataArray = JSON.parse("<?php echo addslashes(json_encode($dataArray)); ?>");
        $("#holiday_id").val($(this).attr("id"));
        $("#holiday_desc").val(dataArray[$(this).attr("id")].name); 
        $("#DOB").val(dataArray[$(this).attr("id")].date); 
      });

     $("#submit_holiday_info").click(function(){
       if($("#holiday_id").val() != "" && $("#holiday_desc").val() != "" && $("#DOB").val() != "" ){
          $.ajax({
                      url: "{{URL('ajax/updateHolidayInfo')}}",
                      data: {
                        'id' : $("#holiday_id").val(),
                        'desc' : $("#holiday_desc").val(),
                        'date' : $("#DOB").val(),
                      },
                      success: function (data) {
                        location.reload();
                        alert("Holiday Information successfully updated!");
                      },
          });
       }
       else
        alert("No field should be empty.");
     });

    </script>
     
@stop