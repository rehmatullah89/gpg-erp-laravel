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
                  EQUIPMENT MANAGEMENT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Equipment Join Date / Name Filter</i></b>
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
                </header>
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('equipment/index'), 'files'=>true, 'method' => 'post')) }}
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
                                    {{Form::select('Filter', array(''=>'Select Filter','eqp_num'=>'Equipment Number','description'=>'Description','ownership'=>'Ownership','eqp_type'=>'Equipment Type'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
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
                            <td data-title="Total Rentals:">{{$teqps}}</td>
                            <td data-title="Active Rentals">{{$aeqps}}</td>
                            <td data-title="Blocked Rentals">{{$beqps}}</td>
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
                  <td rowspan="2" ><div align="center"><strong>#id</strong></div></td>
                  <td rowspan="2" ><div align="center"><strong>Equip.# </strong></div></td>
                  <td rowspan="2" align="center" ><div align="center"><strong>Equip. Type</strong></div></td>
                  <td rowspan="2" align="center" ><div align="center"><strong>Desc </strong></div></td>
                  <td rowspan="2" align="center" ><div align="center"><strong>Qty.</strong></div></td>
                  <td rowspan="2" align="center" ><div align="center"><strong>Qty. Out</strong></div></td>
                  <td rowspan="2" align="center" ><div align="center"><strong>Owner ship</strong></div></td>
                  <td rowspan="2" align="center" ><div align="center"><strong>Reading</strong></div></td>
                  <td height="30" colspan="3" align="center" ><div align="center"><strong>DAILY RATES $ </strong></div></td>
                  <td colspan="3" align="center" ><div align="center"><strong>WEEKLY RATES $ </strong></div></td>
                  <td colspan="3" align="center" ><div align="center"><strong>MONTHLY RATES $ </strong></div></td>
                  <td rowspan="2" align="center" ><div align="center"><strong>Note</strong></div></td>
                  <td rowspan="2" align="center" ><div align="center"><strong>Status</strong></div></td>
                  <td rowspan="2" ><div align="center"><strong>Action</strong></div></td>
                </tr>
                <tr>
                  <td align="center" ><div align="center"><strong>0-8H</strong></div></td>
                  <td align="center" ><div align="center"><strong>8-16H</strong></div></td>
                  <td align="center" ><div align="center"><strong>16-24H</strong></div></td>
                  <td align="center" ><div align="center"><strong>S</strong></div></td>
                  <td align="center" ><div align="center"><strong>D</strong></div></td>
                  <td align="center" ><div align="center"><strong>T</strong></div></td>
                  <td align="center" ><div align="center"><strong>S</strong></div></td>
                  <td align="center" ><div align="center"><strong>D</strong></div></td>
                  <td align="center" ><div align="center"><strong>T</strong></div></td>
                  </tr>
              </thead>
              <tbody class="cf">
              <?php $index=1;?>
                 @foreach($query_data as $row)
                  <tr>
                    <td data-title=":">{{$row['id']}}</td>
                    <td data-title=":">{{$row['eqp_num']}}</td>
                    <td data-title=":">{{$eqps[$row['gpg_equipment_type_id']]}}</td>
                    <td data-title=":">{{$row['description']}}</td>
                    <td data-title=":">{{$row['quantity']}}</td>
                    <td data-title=":"><?php echo ($row['quantity']>$row['eqp_count']?'<font color="green"><strong>'.number_format($row['eqp_count']).'</strong></font>':($row['quantity']==$row['eqp_count']?'<font color="#c10000"><strong>Out</strong></font>':'<font color="#c10000"><strong>'.number_format($row['eqp_count']).'</strong></font>')); ?></td>
                    <td data-title=":">{{($row['ownership']==1?"Owned":"Rented")}}</td>
                    <td data-title=":">
                    {{ HTML::link('#myModal2','Set Reading', array('data-toggle'=>'modal','name'=>'modalInfo', 'id'=>$row['id']))}}  
                    </td>
                    <td data-title=":">{{($row['daily1']!=0?$row['daily1']:"-")}}</td>
                    <td data-title=":">{{($row['daily2']!=0?$row['daily2']:"-")}}</td>
                    <td data-title=":">{{($row['daily3']!=0?$row['daily3']:"-")}}</td>
                    <td data-title=":">{{($row['weekly1']!=0?$row['weekly1']:"-")}}</td>
                    <td data-title=":">{{($row['weekly2']!=0?$row['weekly2']:"-")}}</td>
                    <td data-title=":">{{($row['weekly3']!=0?$row['weekly3']:"-")}}</td>
                    <td data-title=":">{{($row['monthly1']!=0?$row['monthly1']:"-")}}</td>
                    <td data-title=":">{{($row['monthly2']!=0?$row['monthly2']:"-")}}</td>
                    <td data-title=":">{{($row['monthly3']!=0?$row['monthly3']:"-")}}</td>
                    <td data-title=":">{{$row['note']}}
                    <a href="#myModal" id="{{$row['id']}}" data-toggle='modal' name="modal_link">  
                      <i class="fa-file-text"></i>
                    </a>
                    </td>
                    <td data-title=":">{{$row['status']}}</td>
                    <td data-title=":">
                      <a href="{{URL::route('equipment.edit', array('id'=>$row['id']))}}">
                      {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                      </a>
                      {{ Form::open(array('method' => 'post','id'=>'myForm'.$row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('equipment/deleteEquipment', $row['id']))) }}
                      {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['id'].'").submit()')) }}
                      {{ Form::close() }}
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
                              {{Form::open(array('before' => 'csrf' ,'id'=>'submit_note_form','url'=>route('equipment/saveNote'),'files'=>true, 'method' => 'post')) }}     
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Add Note</h4>
                                          </div>
                                          <div class="modal-body">
                                            <input type="hidden" name="note_id" id="note_id">
                                             {{ Form::textarea('note_text','', array('class' => 'form-control', 'id' => 'note_text')) }}
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save Note', array('class' => 'btn btn-Success','data-dismiss'=>'modal','id'=>'save_note'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                      </div>
                                  </div>
                              </div>
                            {{Form::close()}}  
                          </div>
                        <!-- modal -->
                            <!-- Modal#2 -->
                          <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              {{Form::open(array('before' => 'csrf' ,'id'=>'submit_reading_form','url'=>route('equipment/saveReading'),'files'=>true, 'method' => 'post')) }}     
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Add Reading</h4>
                                          </div>
                                          <div class="modal-body">
                                            <input type="hidden" name="reading_id" id="reading_id">
                                             {{ Form::text('new_reading','', array('class' => 'form-control', 'id' => 'new_reading')) }}
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save Reading', array('class' => 'btn btn-Success','data-dismiss'=>'modal','id'=>'save_reading'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                      </div>
                                  </div>
                              </div>
                            {{Form::close()}}  
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
        if (vl == 'ownership') {
          $('#show_hide_val').html('<select name="ownership" class="form-control"><option>Select Owner Ship</option><option value="1">Owned</option><option value="0">Rented</option></select>');
        }else if(vl == 'eqp_type'){
          $('#show_hide_val').html('<select name="eqp_type" class="form-control"><option>Select Eqp Type</option><option value="1">GENERATOR</option><option value="2">CABLE</option><option value="3">LIGHT TOWER</option><option value="4">CABLE RAMP</option><option value="5">PIGTAIL</option><option value="6">EXTENSION CORD</option><option value="7">POLARIS CONNECTOR</option><option value="8">APPLETON CONNECTOR</option><option value="9">TEMPBOX</option></select>');
        }
        else{
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
      $('a[name=modal_link]').click(function(){
        var id = $(this).attr('id');
        $('#note_id').val(id);
      });
      $('a[name=modalInfo]').click(function(){
        var id = $(this).attr('id');
        $('#reading_id').val(id);
      });
      $('#save_note').click(function(){
        if($('#note_text').val() != ''){
          $('#submit_note_form').submit();
        }
      });
      $('#save_reading').click(function(){
        if($('#new_reading').val() != ''){
          $('#submit_reading_form').submit();
        }
      });
    </script>
@stop