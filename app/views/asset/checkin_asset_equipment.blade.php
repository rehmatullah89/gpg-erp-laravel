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
                CHECKIN EQUIPMENT
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
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('asset/checkin_asset_equipment'), 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody>
                          <tr>
                          <input type="hidden" name="line_id" id="line_id" value="">
                          <th style="text-align:center;">Select Equipment*:</th>
                          <td>{{ Form::select('_gpg_asset_equipment_id',array(''=>'Select Equipment Number')+$asset_arr,'', array('class' => 'form-control dpd1', 'id' => '_gpg_asset_equipment_id', 'required')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Job Number*:</th>
                            <td><i id="_job_num"></i></td>
                          </tr> 
                          <tr>
                            <th style="text-align:center;" class="type_ksize">Technician*:</th>
                            <td><i id="_gpg_employee_id"></i></td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Checkout Date*:</th>
                            <td><i id="_checkout_date"></i></td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Checkin Date*:</th>
                            <td>{{ Form::text('_checkin_date','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => '_checkin_date','required')) }}</td>
                          </tr>
                           <tr>
                            <th style="text-align:center;">Equipment Health:</th>
                            <td>{{ Form::checkbox('_health_check', 1, null, ['class' => 'form-control']) }}</td>
                          </tr> 
                           <tr>
                            <th style="text-align:center;">What to Repair:</th>
                            <td>{{ Form::textArea('_eqp_checkin_condition_description','', array('class' => 'form-control dpd1', 'id' => '_eqp_checkin_condition_description')) }}</td>
                          </tr>
                      </tbody>
                  </table>
                    {{ Form::submit('Checkin Equipment', array('class' => 'btn btn-success')) }}
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
            format: 'yyyy-mm-dd'
          });
      $('#_gpg_asset_equipment_id').change(function(){
          var id = $("#_gpg_asset_equipment_id option:selected").val();
          $.ajax({
              url: "{{URL('ajax/getAssetEquipHistory')}}",
                data: {
                  'id' : id 
                },
                success: function (data) {
                  $('#_job_num').html(data.job_num);
                  $('#_gpg_employee_id').html(data.tech);
                  $('#_checkout_date').html(data.checkout_date);
                  $('#line_id').val(data.id);
              },
          });
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
    @stop