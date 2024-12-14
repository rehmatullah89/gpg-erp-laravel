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
                EDIT EQUIPMENT 
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
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('equipment.update',array('id'=>$data_arr['id'])), 'files'=>true, 'method' => 'put')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody>
                          <tr>
                          <th style="text-align:center;">Equipment Type:</th>
                          <td>
                            {{ Form::select('eqpType',$eqps,$data_arr['gpg_equipment_type_id'], array('class' => 'form-control dpd1', 'id' => 'eqpType', 'required')) }}
                            </td>
                          </tr>
                          <tr><th colspan="2"><div id="headingMain"></div> Information</th></tr>
                          <tr>
                          <th style="text-align:center;">Equipment#*:</th>
                          <td>{{ Form::text('eqp_num',$data_arr['eqp_num'], array('class' => 'form-control dpd1', 'id' => 'eqp_num', 'required')) }}</td>
                          </tr>
                          <tr class="qntity">
                            <th style="text-align:center;">Quantity:</th>
                            <td>{{ Form::text('_quantity',$data_arr['quantity'], array('class' => 'form-control dpd1', 'id' => '_quantity')) }}</td>
                          </tr> 
                          <tr>
                            <th style="text-align:center;" class="type_ksize">Kilowatt Size:</th>
                            <td>{{ Form::text('_description',$data_arr['description'], array('class' => 'form-control dpd1', 'id' => '_description')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Fuel Tank Size:</th>
                            <td>{{ Form::text('_fuel_tank_size',$data_arr['fuel_tank_size'], array('class' => 'form-control dpd1', 'id' => '_fuel_tank_size')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Phase:</th>
                            <td>{{ Form::text('_phase',$data_arr['phase'], array('class' => 'form-control dpd1', 'id' => '_phase')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Volts:</th>
                            <td>{{ Form::text('_volts',$data_arr['volts'], array('class' => 'form-control dpd1', 'id' => '_volts')) }}</td>
                          </tr>
                          <tr class="gen" id="gserial">
                            <th style="text-align:center;">Serial#:</th>
                            <td>{{ Form::text('_serial',$data_arr['serial'], array('class' => 'form-control dpd1', 'id' => '_serial')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Model#:</th>
                            <td>{{ Form::text('_model',$data_arr['model'], array('class' => 'form-control dpd1', 'id' => '_model')) }}</td>
                          </tr>
                          <tr class="gen">
                          <th style="text-align:center;">Auto Start:</th>
                          <td>
                            {{ Form::select('_auto_start',array('YES'=>'YES','NO'=>'NO'),$data_arr['auto_start'], array('class' => 'form-control dpd1', 'id' => '_auto_start')) }}
                          </td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Ownership:</th>
                          <td>
                            {{ Form::select('_ownership',array('1'=>'Owned','0'=>'Rented'),$data_arr['ownership'], array('class' => 'form-control dpd1', 'id' => '_ownership')) }}
                          </td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Fuel Filter 1:</th>
                            <td>{{ Form::text('_fuel_filter_one',$data_arr['fuel_filter_one'], array('class' => 'form-control dpd1', 'id' => '_fuel_filter_one')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Fuel Filter 1 Qty:</th>
                            <td>{{ Form::text('_fuel_filter_one_qty',$data_arr['fuel_filter_one_qty'], array('class' => 'form-control dpd1', 'id' => '_fuel_filter_one_qty')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Fuel Filter 2:</th>
                            <td>{{ Form::text('_fuel_filter_two',$data_arr['fuel_filter_two'], array('class' => 'form-control dpd1', 'id' => '_fuel_filter_two')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Fuel Filter 2 Qty :</th>
                            <td>{{ Form::text('_fuel_filter_two_qty',$data_arr['fuel_filter_two_qty'], array('class' => 'form-control dpd1', 'id' => '_fuel_filter_two_qty')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Oil Filter 1:</th>
                            <td>{{ Form::text('_oil_filter_one',$data_arr['oil_filter_one'], array('class' => 'form-control dpd1', 'id' => '_oil_filter_one')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Oil Filter 1 Qty:</th>
                            <td>{{ Form::text('_oil_filter_one_qty',$data_arr['oil_filter_one_qty'], array('class' => 'form-control dpd1', 'id' => '_oil_filter_one_qty')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Oil Filter 2:</th>
                            <td>{{ Form::text('_oil_filter_two',$data_arr['oil_filter_two'], array('class' => 'form-control dpd1', 'id' => '_oil_filter_two')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Oil Filter 2 Qty:</th>
                            <td>{{ Form::text('_oil_filter_two_qty',$data_arr['oil_filter_two_qty'], array('class' => 'form-control dpd1', 'id' => '_oil_filter_two_qty')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Air Filter 1:</th>
                            <td>{{ Form::text('_air_filter_one',$data_arr['air_filter_one'], array('class' => 'form-control dpd1', 'id' => '_air_filter_one')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Air Filter 1 Qty:</th>
                            <td>{{ Form::text('_air_filter_one_qty',$data_arr['air_filter_one_qty'], array('class' => 'form-control dpd1', 'id' => '_air_filter_one_qty')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Air Filter 2:</th>
                            <td>{{ Form::text('_air_filter_two',$data_arr['air_filter_two'], array('class' => 'form-control dpd1', 'id' => '_air_filter_two')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Air Filter 2 Qty:</th>
                            <td>{{ Form::text('_air_filter_two_qty',$data_arr['air_filter_two_qty'], array('class' => 'form-control dpd1', 'id' => '_air_filter_two_qty')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Oil Type:</th>
                            <td>{{ Form::text('_oil_type',$data_arr['oil_type'], array('class' => 'form-control dpd1', 'id' => '_oil_type')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Oil Capacity:</th>
                            <td>{{ Form::text('_oil_capcity',$data_arr['oil_capcity'], array('class' => 'form-control dpd1', 'id' => '_oil_capcity')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Upper Tank Fuel Capacity:</th>
                            <td>{{ Form::text('_upper_tank_fuel_capcity',$data_arr['upper_tank_fuel_capcity'], array('class' => 'form-control dpd1', 'id' => '_upper_tank_fuel_capcity')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">Lower Tank Fuel Capacity:</th>
                            <td>{{ Form::text('_lower_tank_fuel_capcity',$data_arr['lower_tank_fuel_capcity'], array('class' => 'form-control dpd1', 'id' => '_lower_tank_fuel_capcity')) }}</td>
                          </tr>
                           <tr>
                          <th style="text-align:center;">Status:</th>
                          <td>
                            {{ Form::select('_status',array('A'=>'Active','B'=>'Blocked'),$data_arr['status'], array('class' => 'form-control dpd1', 'id' => '_status')) }}
                          </td>
                          </tr>
                          <tr><th colspan="2">Miscellaneous</th></tr>
                          <tr>
                            <th style="text-align:center;" class="daily_id">0-8 HOURS (Daily Rate):</th>
                            <td>{{ Form::text('_daily1',$data_arr['daily1'], array('class' => 'form-control dpd1', 'id' => '_daily1')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">8-16 HOURS (Daily Rate):</th>
                            <td>{{ Form::text('_daily2',$data_arr['daily2'], array('class' => 'form-control dpd1', 'id' => '_daily2')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">16-24 HOURS (Daily Rate):</th>
                            <td>{{ Form::text('_daily3',$data_arr['daily3'], array('class' => 'form-control dpd1', 'id' => '_daily3')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;" class="weekly_id">SINGLE (Weekly Rate):</th>
                            <td>{{ Form::text('_weekly1',$data_arr['weekly1'], array('class' => 'form-control dpd1', 'id' => '_weekly1')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">DOUBLE (Weekly Rate):</th>
                            <td>{{ Form::text('_weekly2',$data_arr['weekly2'], array('class' => 'form-control dpd1', 'id' => '_weekly2')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">TRIPLE (Weekly Rate):</th>
                            <td>{{ Form::text('_weekly3',$data_arr['weekly3'], array('class' => 'form-control dpd1', 'id' => '_weekly3')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;" class="monthly_id">SINGLE (MONTHLY Rate):</th>
                            <td>{{ Form::text('_monthly1',$data_arr['monthly1'], array('class' => 'form-control dpd1', 'id' => '_monthly1')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">DOUBLE (MONTHLY Rate):</th>
                            <td>{{ Form::text('_monthly2',$data_arr['monthly2'], array('class' => 'form-control dpd1', 'id' => '_monthly2')) }}</td>
                          </tr>
                          <tr class="gen">
                            <th style="text-align:center;">TRIPLE (MONTHLY Rate):</th>
                            <td>{{ Form::text('_monthly3',$data_arr['monthly3'], array('class' => 'form-control dpd1', 'id' => '_monthly3')) }}</td>
                          </tr>
                      </tbody>
                  </table>
                    {{ Form::submit('Update Equipment', array('class' => 'btn btn-success')) }}
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
    $( document ).ready(function() {
        var id = $("#eqpType option:selected").val();
        var name = $("#eqpType option:selected").text();
        $('#headingMain').html(name);    
        $('#headingMain').html(name);  
        if(id != '1'){
        $('.gen').hide();
        $('#headingMain').html($('#eqpType option:selected').text());
        $('.daily_id').html('Daily Charges');
        $('.weekly_id').html('Weekly Charges');
        $('.monthly_id').html('Monthly Charges');
        $('.type_ksize').html('Type:');
        $('.qntity').show();
      }
      else{
        $('.gen').show();
        $('#headingMain').html($('#eqpType option:selected').text());
        $('.daily_id').html('0-8 HOURS (Daily Rate):');
        $('.weekly_id').html('SINGLE (Weekly Rate):');
        $('.monthly_id').html('SINGLE (MONTHLY Rate):');
        $('.type_ksize').html('Kilowatt Size:');
        $('.qntity').hide();
      }
      if(id == '3')
        $('#gserial').show();  
    });
    
    $('#eqpType').change(function(){
      if($(this).val() != '1'){
        $('.gen').hide();
        $('#headingMain').html($('#eqpType option:selected').text());
        $('.daily_id').html('Daily Charges');
        $('.weekly_id').html('Weekly Charges');
        $('.monthly_id').html('Monthly Charges');
        $('.type_ksize').html('Type:');
        $('.qntity').show();
      }
      else{
        $('.gen').show();
        $('#headingMain').html($('#eqpType option:selected').text());
        $('.daily_id').html('0-8 HOURS (Daily Rate):');
        $('.weekly_id').html('SINGLE (Weekly Rate):');
        $('.monthly_id').html('SINGLE (MONTHLY Rate):');
        $('.type_ksize').html('Kilowatt Size:');
        $('.qntity').hide();
      }
      if($(this).val() == '3')
        $('#gserial').show();
    });
    </script>
@stop