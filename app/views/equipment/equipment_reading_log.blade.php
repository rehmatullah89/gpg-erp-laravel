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
                  WAGES LOG MANAGEMENT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Employee Join Date / Name Filter</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('equipment/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
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
                                    {{Form::select('Filter', array(''=>'Select Filter','eqp_num'=>'Equipment Num'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
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
                  <th>Equipment Name </th>
                  <th>Reading</th>
                  <th >Dated</th>
                  <th >Equipment Type</th>
                  <th >Action</th>
              </tr>
              </thead>
              <tbody class="cf">
              <?php $index=1;?>
                @foreach($data_arr as $row)
                  <tr>
                    <td data-title="id:">{{$row['gpg_equipment_id']}}</td>
                    <td data-title="Eqp Num:">{{$row['eqp_num']}}</td>
                    <td data-title="id:">{{$row['reading']}}</td>
                    <td data-title="id:">{{date('m/d/Y',strtotime($row['created_on']))}}</td>
                    <td data-title="id:">{{($row['gpg_equipment_type_id']=='1')?"GENERATOR":'-'}}</td>
                    <td data-title="Action:">
                      {{ Form::open(array('method' => 'post','id'=>'myForm'.$row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('equipment/delEquipReadingLog', $row['id']))) }}
                      {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['id'].'").submit()')) }}
                      {{ Form::close() }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
              </table>
             <!-- links here -->
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
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop