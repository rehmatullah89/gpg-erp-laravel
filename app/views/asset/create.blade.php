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
                ADD ASSET EQUIPMENT
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Enter required* Inoformation! </i></b>
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
              </section>
             <!-- ////////////////////////////////////////// -->
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('asset.store'), 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody>
                          <tr>
                          <th style="text-align:center;">Asset Type:*</th>
                          <td>{{ Form::select('gpg_asset_equipment_type_id',array(''=>'Select Asset Type','6'=>'Load Banks','7'=>'Chart Recorder','8'=>'Vehicles'),'', array('class' => 'form-control dpd1', 'id' => '_gpg_asset_equipment_type_id', 'required')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Equipment Number*:</th>
                            <td>{{ Form::text('eqp_num','', array('class' => 'form-control dpd1', 'id' => '_eqp_num', 'required')) }}</td>
                          </tr> 
                          <tr>
                            <th style="text-align:center;" class="type_ksize">Serial Number:</th>
                            <td>{{ Form::text('_eqp_serial_num','', array('class' => 'form-control dpd1', 'id' => '_eqp_serial_num')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Plate Number:</th>
                            <td>{{ Form::text('_eqp_plate_number','', array('class' => 'form-control dpd1', 'id' => '_eqp_plate_number')) }}</td>
                          </tr>
                           <tr>
                            <th style="text-align:center;">Status:</th>
                            <td>{{ Form::select('_status',array(''=>'Select Status','0'=>'Blocked','1'=>'Active'),'', array('class' => 'form-control dpd1', 'id' => '_status')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Equipment Photo:</th>
                            <td>{{ Form::file('eqp_image','', array('class' => 'form-control dpd1', 'id' => 'eqp_image')) }}</td>
                          </tr>
                           <tr>
                            <th style="text-align:center;">Description:</th>
                            <td>{{ Form::textArea('_description','', array('class' => 'form-control dpd1', 'id' => '_description')) }}</td>
                          </tr>
                      </tbody>
                  </table>
                    {{ Form::submit('Add Equipment', array('class' => 'btn btn-success')) }}
              </section>
              </div>
              </div>
              {{ Form::close() }}
              </section>
              </div>
              </div>
              <!-- page end-->
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
    @stop