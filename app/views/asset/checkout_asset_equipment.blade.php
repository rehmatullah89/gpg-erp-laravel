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
                CHECKOUT EQUIPMENT
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
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('asset/checkout_asset_equipment'), 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody>
                          <tr>
                          <th style="text-align:center;">Select Equipment*:</th>
                          <td>{{ Form::select('_gpg_asset_equipment_id',array(''=>'Select Equipment')+$asset_arr,'', array('class' => 'form-control dpd1', 'id' => '_gpg_asset_equipment_id', 'required')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Job Number*:</th>
                            <td>{{ Form::text('_job_num','', array('class' => 'form-control dpd1', 'id' => '_job_num', 'required')) }}</td>
                          </tr> 
                          <tr>
                            <th style="text-align:center;" class="type_ksize">Technician*:</th>
                            <td>{{ Form::select('_gpg_employee_id',array(''=>'Select Technician')+$techs,'', array('class' => 'form-control dpd1', 'id' => '_gpg_employee_id','required')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Checkout Date*:</th>
                            <td>{{ Form::text('_checkout_date','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => '_checkout_date','required')) }}</td>
                          </tr>
                           <tr>
                            <th style="text-align:center;">Equipment Condition:</th>
                            <td>{{ Form::textArea('_eqp_checkout_condition_description','', array('class' => 'form-control dpd1', 'id' => '_eqp_checkout_condition_description')) }}</td>
                          </tr>
                      </tbody>
                  </table>
                    {{ Form::submit('Checkout Equipment', array('class' => 'btn btn-success')) }}
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
       $('#_job_num').focus(function() {  
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
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
    @stop