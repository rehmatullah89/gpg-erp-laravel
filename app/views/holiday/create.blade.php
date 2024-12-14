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
                Add New Holiday 
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Fill required* Inoformation to Add Holiday! </i></b>
                          </header>
              </section>
             <!-- ////////////////////////////////////////// -->
             {{ Form::open(array('before' => 'csrf' ,'url'=>route('holiday.store'), 'id'=>'frmid1', 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">Holiday Description*</th>
                            <th style="text-align:center;">Day*</th>
                            <th style="text-align:center;">Action</th>
                           </tr>
                        </thead>
                      <tbody>
                        <tr>
                          <td data-title="#Name">
                            {{ Form::text('holiday_desc','', array('class' => 'form-control dpd1', 'id' => 'holiday_desc', 'required')) }}
                          </td>
                          <td data-title="#Date">
                            {{ Form::text('DOB','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'DOB', 'required')) }}
                          </td>
                            <td style="padding-left:5%;">
                              {{ Form::submit('Submit Holiday Info', array('class' => 'btn btn-success')) }}
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