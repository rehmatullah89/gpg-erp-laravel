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
               ADD A NEW COUNTRY
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Fill required* Inoformation to Add country! </i></b>
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
             {{ Form::open(array('before' => 'csrf' ,'url'=>route('settings.store'), 'id'=>'frmid1', 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr><th style="text-align:center;">Country Name*:</th><td>{{ Form::text('country','', array('class' => 'form-control dpd1', 'id' => 'country', 'required')) }}</td></tr>
                          <tr><th style="text-align:center;">State2: </th><td>{{ Form::text('st1','', array('class' => 'form-control dpd1', 'id' => 'st1')) }}</td></tr>
                          <tr><th style="text-align:center;">State3: </th><td>{{ Form::text('st2','', array('class' => 'form-control dpd1', 'id' => 'st2')) }}</td></tr>
                          <tr><th style="text-align:center;">Zip Code: </th><td>{{ Form::text('zip','', array('class' => 'form-control dpd1', 'id' => 'zip')) }}</td></tr>
                        </tbody>
                  </table>                                
              </section>
              {{ Form::submit('Add Country', array('class' => 'btn btn-success')) }}
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