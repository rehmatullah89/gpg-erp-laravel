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
                 SERVICE EQUIPMENT MANAGEMENT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i>  Date / Filter</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('parts/field_component_index'), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <thead>
                              <tr>
                                <th>
                                   {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                </th>
                                <th>
                                  {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('Filter', 'Filter:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('FVal', 'Filter Value:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  Actions
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                  <td data-title="Start Date:">
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td>
                                  <td data-title="End Date:">
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Filter:">
                                    {{Form::select('Filter', array(''=>'Select Filter','part_number'=>'Part #','model_number'=>'Model #','serial_number'=>'Serial #'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                   <td id="show_hide_val" data-title="Filter Value:">
                                    {{ Form::text('FVal','', array('class' => 'form-control', 'id' => 'FVal')) }}
                                  </td>
                                  <td>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                </td>
                                </tr>
                              </tbody>
                          </table>
                      </section>
                               {{ Form::close() }}
              </section>
              </section>
              </div>
                <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <div class="panel-body">
              <section id="no-more-tables" >
              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>id#</th>
                  <th>Type</th>
                  <th>Part #</th>
                  <th >Manufacturer</th>
                  <th >Cost</th>
                  <th >List</th>
                  <th >Margin %</th>
                  <th >Vendor</th>
                  <th >Vendor Cost</th>
                  <th >Note</th>
                  <th >Model #</th>
                  <th >Serial #</th>
                  <th >Spec #</th>
                  <th >Action</th>
              </tr>
              </thead>
              <tbody class="cf">
                @foreach($query_data as $row)
                  <tr>
                    <td>{{$row['id']}}</td>
                    <td>{{$row['component_type']}}</td>
                    <td>{{$row['part_number']}}</td>
                    <td>{{$row['manufacturer']}}</td>
                    <td>{{'$'.number_format($row['cost'],2)}}</td>
                    <td>{{'$'.number_format($row['list'],2)}}</td>
                    <td>{{$row['margin']}}</td>
                    <td><?php if(!empty($row['gpg_vendor_id'])) { echo DB::table('gpg_vendor')->where('id','=',$row['gpg_vendor_id'])->where('status','=','A')->pluck('name');}?></td>
                    <td>{{number_format($row['gpg_vendor_cost'],2)}}</td>
                    <td title="{{$row['note']}}">{{substr($row['note'], 1, 5).'...'}}</td>
                    <td title="{{$row['model_number']}}">{{substr($row['model_number'], 1, 5).'...'}}</td>
                    <td title="{{$row['serial_number']}}">{{substr($row['serial_number'], 1, 5).'...'}}</td>
                    <td title="{{$row['spec_number']}}">{{substr($row['spec_number'], 1, 5).'...'}}</td>
                    <td>
                      <a href="{{URL::route('parts/edit_field_component', array('id'=>$row['id']))}}">
                          {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                      </a> 
                      {{ Form::open(array('method' => 'post','id'=>'myForm'.$row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('parts/deleteSEquip', $row['id']))) }}
                      {{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['id'].'").submit()')) }}         
                      {{ Form::close() }}
                      @if($row['is_active']!=1) 
                          {{ Form::button('<i class="fa fa-lightbulb-o"></i>', array('class' => 'btn btn-default btn-xs','id'=>$row['id'],'name'=>'switch_material','value'=>'1')) }}
                      @else
                          {{ Form::button('<i class="fa fa-lightbulb-o"></i>', array('class' => 'btn btn-warning btn-xs','id'=>$row['id'], 'name'=>'switch_material','value'=>'0')) }}
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
              </table>
             {{ $query_data->links() }}    
            </section>
            </div>  
          </section>
        </div>
        </div>      
      </div>
    <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
      });
      $("section[mysection=hide_n_show]").hide();
      $('#togglerButton').click(function(){
         $("section[mysection=hide_n_show]").toggle("slow");
         if ($('#toggle_div_plus').attr("class") == "fa fa-plus")
            $('#toggle_div_plus').removeClass('fa fa-plus').addClass('fa fa-minus');
         else 
            $('#toggle_div_plus').removeClass('fa fa-minus').addClass('fa fa-plus');
      }); 
      
      $('#reset_search_form').click(function(){
              $('#SDate').val("");
              $('#EDate').val("");
              $('#Filter').val("");
              $('#FVal').val("");
      });
    </script>
<script type="text/javascript">
$('button[name=switch_material]').click(function(){
 var id = $(this).attr('id');
 var vl = $(this).attr('value');
  $.ajax({
      url: "{{URL('ajax/switchCompStatus')}}",
      data: {
        'id' : id,
        'status' : vl
      },
        success: function (data) {
        alert("Comp. Status successfully updated!");
        location.reload();
      },
  });
});
</script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop