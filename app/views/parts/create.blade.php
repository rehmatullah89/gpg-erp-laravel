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
                  ADD PART
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
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('parts.store'), 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody>
                          <tr>
                          <th style="text-align:center;">Type:*</th>
                          <td>{{ Form::select('_gpg_field_material_type_id',array(''=>'Select Type')+$type_arr,'', array('class' => 'form-control dpd1', 'id' => '_gpg_field_material_type_id', 'required')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Description:*</th>
                            <td>{{ Form::text('_description','', array('class' => 'form-control dpd1', 'id' => '_description', 'required')) }}</td>
                          </tr> 
                          <tr>
                            <th style="text-align:center;" class="type_ksize">Part #:*</th>
                            <td>{{ Form::text('part_number','', array('class' => 'form-control dpd1', 'id' => 'part_number', 'required')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Manufacturer:</th>
                            <td>{{ Form::text('_manufacturer','', array('class' => 'form-control dpd1', 'id' => '_manufacturer')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Cost:</th>
                            <td>{{ Form::text('_cost','', array('class' => 'form-control dpd1', 'id' => '_cost')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Margin:</th>
                            <td>{{ Form::text('_margin','', array('class' => 'form-control dpd1', 'id' => '_margin','onkeyup'=>"calListPrice()")) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">List:</th>
                            <td>{{ Form::text('_list','', array('class' => 'form-control dpd1', 'id' => '_list')) }}</td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Vendor:</th>
                          <td>{{ Form::select('_gpg_vendor_id',array(''=>'Select Vendor')+$gpg_vendor,'', array('class' => 'form-control dpd1', 'id' => '_gpg_vendor_id', 'required')) }}</td>
                          </tr>
                           <tr>
                            <th style="text-align:center;">Vendor List:</th>
                            <td>{{ Form::text('_gpg_vendor_cost','', array('class' => 'form-control dpd1', 'id' => '_gpg_vendor_cost')) }}</td>
                          </tr>
                           <tr>
                            <th style="text-align:center;">Notes:</th>
                            <td>{{ Form::text('_note','', array('class' => 'form-control dpd1', 'id' => '_note')) }}</td>
                          </tr>
                           <tr>
                            <th style="text-align:center;">Model #:</th>
                            <td>{{ Form::text('_model_number','', array('class' => 'form-control dpd1', 'id' => '_model_number')) }}</td>
                          </tr>
                           <tr>
                            <th style="text-align:center;">Serial #:</th>
                            <td>{{ Form::text('_serial_number','', array('class' => 'form-control dpd1', 'id' => '_serial_number')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Spec #:</th>
                            <td>{{ Form::text('_spec_number','', array('class' => 'form-control dpd1', 'id' => '_spec_number')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Price is fixed:</th>
                            <td>{{ Form::checkbox('_fixed_price', 1, null, ['class' => 'form-control']) }}</td>
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
    <script type="text/javascript">
  function calListPrice(){
    var _cost = $('#_cost').val();
    var _margin = $('#_margin').val();
    var dividend = 0;
    if(_margin/100==1)
     {
      dividend = .9999;
     }else{
      dividend = _margin/100;
     }
    var listPrice = (_cost/(1-dividend));
    $('#_list').val(listPrice);
  }
    </script>
    @stop