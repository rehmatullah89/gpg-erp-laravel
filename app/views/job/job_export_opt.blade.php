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
                JOBS EXPORT 
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Fill required* Inoformation to get Export file! </i></b>
                          </header>
              </section>
             <!-- ////////////////////////////////////////// -->
             {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/job_export_opt'), 'id'=>'frmid1', 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">Start Date*</th>
                            <th style="text-align:center;">End Date*</th>
                            <th style="text-align:center;">JOB TYPE*</th>
                            <th style="text-align:center;">Action</th>
                           </tr>
                        </thead>
                      <tbody>
                        <tr>
                          <td data-title="#Name">
                            {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate', 'required')) }}
                          </td>
                          <td data-title="#Date">
                            {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate', 'required')) }}
                          </td>
                          <td>
                          {{Form::select('typeId',$job_type_arr,'', ['id'=>'typeId', 'class'=>'form-control m-bot12','style'=>'width:50%; display:inline;','required'])}}  
                          </td>
                            <td>
                              {{ Form::submit('Export Jobs', array('class' => 'btn btn-success')) }}
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
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd',
          minDate: new Date()
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop