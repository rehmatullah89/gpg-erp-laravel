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
                  ASSET EQUIPMENT MANAGEMENT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b> SEARCH ASSET EQUIPMENT by:<i>Date / Filter</i></b>
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
                 <?php $uriSegment = Request::segment(2);?>
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('glcode/index'), 'files'=>true, 'method' => 'post')) }}
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
                                    {{Form::select('Filter', array(''=>'Select Filter','eqp_num'=>'Equipment Number','eqp_serial_num'=>'Serial Number','description'=>'Description','eqp_type'=>'Equipment Type','status'=>'Equipment Status'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
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
                            <th>Total EQUIPMENT</th>  
                            <th>Active EQUIPMENT</th>  
                            <th>Blocked EQUIPMENT</th>  
                          </tr>
                        </thead> 
                        <tbody class="cf">
                          <tr>
                            <td data-title="Total Rentals:">{{$tid}}</td>
                            <td data-title="Active Rentals">{{$aid}}</td>
                            <td data-title="Blocked Rentals">{{$bid}}</td>
                          </tr>
                        </tbody>
                  </table>
                </section>
              </div>
              </section>
                </div>
              </div>

              <div class="row">
              <div class="col-sm-12">
              <section class="panel">
              <div class="panel-body">
              <section id="no-more-tables" >
              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Eqp.# </th>
                  <th >Serial # </th>
                  <th >Plate #</th>
                  <th >Eqp. Type</th>
                  <th >Eqp. Description </th>
                  <th >Eqp. Condition</th>
                  <th >Job Number</th>
                  <th >Technician</th>
                  <th >Assign Status</th>
                  <th >Date</th>
                  <th >Status</th>
                  <th >Action</th>
                </tr>
              </thead>
              <tbody class="cf">
              <?php
                $eqpArr = '';
                foreach ($eqpArrs as $key => $value) {
                  $eqpArr.='<option value='.$key.'>'.$value.'</option>';
                }
              ?>
              @foreach($query_data as $row)
                <tr>
                  <td>{{$row['eqp_num']}}</td>
                  <td>{{$row['eqp_serial_num']}}</td>
                  <td>{{$row['eqp_plate_number']}}</td>
                  <td>{{$row['asset_equipment_type']}}</td>
                  <td>{{substr($row['description'],0,20).'...'}}</td>
                  <td><?php echo ($row['eqp_condition']==0?"<font color=\"green\">Condition Ok</font>":($row['eqp_condition']==1?"<font color=\"#c10000\">Need to be fixed</font>":"")); ?></td>
                  <td>{{isset($row['eqpCheckinDate']['job_num'])?$row['eqpCheckinDate']['job_num']:'-'}}</td>
                  <td>{{isset($row['eqpCheckinDate']['employee'])?$row['eqpCheckinDate']['employee']:'-'}}</td>
                  <td><?php echo ($row['assign_status']=="checkin"?"Checkin":($row['assign_status']=="checkout"?"Checkout":"")); ?></td>
                  <td>{{isset($row['eqpCheckinDate']['checkout_date'])?date('m/d/Y',strtotime($row['eqpCheckinDate']['checkout_date'])):'-'}}</td>
                  <td><?php echo ($row['status']==1?"Active":($row['status']==0?"Blocked":"")); ?></td>
                  <td>
                     <a href="{{URL::route('asset.edit', array('id'=>$row['id']))}}">
                     {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-success btn-xs'))}}
                     </a> 
                     {{ Form::open(array('method' => 'DELETE','id'=>'myForm'. $row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('asset.destroy', $row['id']))) }}
                     {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['id'].'").submit()')) }}
                     {{ Form::close() }}
                     <br/>
                     <a href="#myModal" data-toggle="modal" id="{{$row['id']}}" jobNum="{{isset($row['eqpCheckinDate']['job_num'])?$row['eqpCheckinDate']['job_num']:'-'}}" name="equip_history">
                       {{Form::button('<i class="fa fa-history"></i>', array('class' => 'btn btn-warning btn-xs'))}}
                     </a>
                      <a href="#myModal2" data-toggle="modal" id="{{$row['id']}}" jobNum="{{isset($row['eqpCheckinDate']['job_num'])?$row['eqpCheckinDate']['job_num']:'-'}}" name="equip_health">
                       {{Form::button('<i class="fa fa-hospital-o"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                     </a>
                  </td>
                </tr>
              @endforeach
              </tbody>
              </table>
             {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
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
                <h4 class="modal-title">EQUIPMENT HISTORY</h4>
                </div>
                  <div class="modal-body">
                    <section id="no-more-tables">
                          <table class="table table-bordered table-striped table-condensed cf">
                            <thead>
                              <tr>
                                <th>Index</th>
                                <th>Technician</th>
                                <th>Job Num</th>
                                <th>Checkout Date</th>
                                <th>Checkout Comment</th>
                                <th>Checkin Date</th>
                                <th>Checkin Comment</th>
                              </tr>
                            </thead>
                            <tbody id="show_equip_history">
                            </tbody>
                          </table>
                    </section>       
                  </div>
                  <div class="btn-group" style="padding:20px;">
                    {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                  </div>
                 </div>
            </div>
          </div>
        </div>
      <!-- modal -->
      <!-- Modal#2 -->
        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                <h4 class="modal-title">EQUIPMENT HEALTH</h4>
                </div>
                  <div class="modal-body">
                    <section id="no-more-tables">
                          <table class="table table-bordered table-striped table-condensed cf">
                            <thead>
                              <tr>
                                <th>Index</th>
                                <th>Checkin Date</th>
                                <th>Checkin Condition</th>
                                <th>Repair Date</th>
                                <th>Repaired Desc</th>
                              </tr>
                            </thead>
                            <tbody id="show_equip_health">
                            </tbody>
                          </table>
                    </section>       
                  </div>
                  <div class="btn-group" style="padding:20px;">
                  {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                  </div>
                 </div>
              </div>
          </div>
        </div>
      <!-- modal#2 end -->
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
          $('#show_hide_val').html('<select name="status" class="form-control"><option value="1">Active</option><option value="0">Blocked</option></select>');
        }else if(vl == 'eqp_type'){
           $('#show_hide_val').html('<select name="eqp_type" class="form-control">{{$eqpArr}}</select>');      
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

      $('a[name=equip_history]').click(function(){
        var id = $(this).attr('id');
        var jobNum = $(this).attr('jobNum');
        $.ajax({
            url: "{{URL('ajax/getEquipHist')}}",
              data: {
               'id' : id
              },
            success: function (data) {
              $('#show_equip_history').html(data);
            },
        });
      });
      $('a[name=equip_health]').click(function(){
        var id = $(this).attr('id');
        var jobNum = $(this).attr('jobNum');
         $.ajax({
            url: "{{URL('ajax/getEquipHealth')}}",
              data: {
               'id' : id
              },
            success: function (data) {
              $('#show_equip_health').html(data);
            },
        });
      });
    </script>
@stop