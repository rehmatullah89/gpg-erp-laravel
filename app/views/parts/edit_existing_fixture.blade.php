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
                  EDIT EXISTING FIXTURE
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
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('parts/edit_existing_fixture',array('id'=>$data[0]->id)), 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody>
                          <tr>
                            <th style="text-align:center;">Existing Fixture Name*:</th>
                            <td>{{ Form::text('fixture_name',$data[0]->fixture_name, array('class' => 'form-control dpd1', 'id' => 'fixture_name', 'required')) }}</td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Existing Fixture Type*:</th>
                          <td>{{ Form::select('_gpg_job_electrical_subquote_fixtures_type_id',array(''=>'Select Fixture Type')+$types,$data[0]->gpg_job_electrical_subquote_fixtures_type_id, array('class' => 'form-control dpd1', 'id' => '_gpg_job_electrical_subquote_fixtures_type_id', 'required')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;" class="type_ksize">Watts:</th>
                            <td>{{ Form::text('_watts',$data[0]->watts, array('class' => 'form-control dpd1', 'id' => '_watts')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Material Price:</th>
                            <td>{{ Form::text('_material_price',$data[0]->material_price, array('class' => 'form-control dpd1', 'id' => '_material_price')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Labor Hours:</th>
                            <td>{{ Form::text('_labor_hours',$data[0]->labor_hours, array('class' => 'form-control dpd1', 'id' => '_labor_hours')) }}</td>
                          </tr>
                      </tbody>
                  </table>
                    {{ Form::submit('Save', array('class' => 'btn btn-success')) }}
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