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
                  FIXTURE TYPE MANAGEMENT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i>  Fixture Type Created Date / Filter</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('parts/manage_fixture_type'), 'files'=>true, 'method' => 'post')) }}
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
                                    {{Form::select('Filter', array(''=>'Select Filter','id'=>'ID','name'=>'Fixture Name','status'=>'Fixture Type Status'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
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
                  <th>Fixture Type</th>
                  <th>Status</th>
                  <th >Action</th>
              </tr>
              </thead>
              <tbody class="cf">
               @foreach($query_data as $row)
                <tr>
                  <td>{{$row['id']}}</td>
                  <td>{{$row['name']}}</td>
                  <td>{{($row['status']=='A'?'Active':'Blocked')}}</td>
                  <td>
                    {{ HTML::link('#myModal','Edit', array('class' => 'btn btn-success btn-xs','data-toggle'=>'modal','id'=>$row['id'],'name'=>'modalInfo', 'status'=>$row['status'], 'fname'=>$row['name']))}} 
                    {{ Form::open(array('method' => 'post','id'=>'myForm'.$row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('parts/deleteFixtureType', $row['id']))) }}
                    {{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['id'].'").submit()')) }}         
                    {{ Form::close() }}
                  </td>
                </tr>
               @endforeach
              </tbody>
              </table>
             {{-- {{ $query_data->links() }}     --}}
            </section>
            </div>  
          </section>
        </div>
        </div>      
      </div>
      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
             {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
             <h4 class="modal-title">EDIT FIXTURE TYPE</h4>
            </div>
            <div class="modal-body">
              <section id="no-more-tables" >
              <table class="table table-bordered table-striped table-condensed cf" >
                <tbody>
                  <tr>
                    <input type="hidden" name="fixture_id" id="fixture_id" value="">
                    <th>Fixtute Type*:</th><td>{{ Form::text('_fixtue_type','', array('class' => 'form-control dpd1', 'id' => '_fixtue_type')) }}</td>
                  </tr>
                   <tr>
                    <th>Status:</th><td>{{ Form::select('status',array('A'=>'Active','B'=>'Blocked'),'', array('class' => 'form-control dpd1', 'id' => 'fstatus')) }}</td>
                  </tr>
                </tbody>
              </table>
              </section>
            </div>
          <div class="btn-group" style="padding:20px;">
          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
          {{Form::button('Save', array('class' => 'btn btn-success','data-dismiss'=>'modal','id'=>'save_data'))}}
        </div>
      </div>
      </div>
    </div>
    <!-- modal -->
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
      $('#Filter').on('change',function(){
        var vl = $(this).val();
        if (vl == 'status') {
          $('#show_hide_val').html('<select name="status" class="form-control"><option value="">Select Status</option><option value="A">Active</option><option value="B">Blocked</option></select>');
        }else{
          $('#show_hide_val').html('<input type="text" value="" name="FVal" id="FVal" class="form-control">');
        }
      });

      $('#reset_search_form').click(function(){
              $('#SDate').val("");
              $('#EDate').val("");
              $('#Filter').val("");
              $('#show_hide_val').html('<input type="text" value="" name="FVal" id="FVal" class="form-control">');
              $('#FVal').val("");
      });
    </script>
<script type="text/javascript">
$('a[name=modalInfo]').click(function(){
  var id = $(this).attr('id');
  var status = $(this).attr('status');
  var name = $(this).attr('fname');
  $('#_fixtue_type').val(name);
  $('#fixture_id').val(id);
  $('#fstatus').val(status);
});
$('#save_data').click(function(){
  var id = $('#fixture_id').val();
  var status = $('#fstatus').val();
  var ftype = $('#_fixtue_type').val();

    $.ajax({
      url: "{{URL('ajax/updateFixType')}}",
      data: {
        'id' : id,
        'status' : status,
        'ftype' : ftype
      },
        success: function (data) {
        alert("Updated successfully updated!");
        location.reload();
      },
  });
});
</script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop