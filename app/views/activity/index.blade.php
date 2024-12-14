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
                  ACTIVITY MANAGEMENT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i>Activity Date / Filter</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('activity/index'), 'files'=>true, 'method' => 'post')) }}
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
                                    {{Form::select('Filter', array(''=>'Select Filter','full_name'=>'Full Name','user_name'=>'Login Name','user_type'=>'User Area','description'=>'Item Accessed'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
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
                            <th>Action</th>  
                            <th>Date Time</th>  
                            <th>Full Name</th>  
                            <th>Login Name</th>  
                            <th>User Area</th>  
                            <th>Module</th>  
                            <th>Module Sub</th>  
                            <th>Item Accessed</th>  
                            <th>URL</th>  
                          </tr>
                        </thead> 
                        <tbody class="cf">
                          <?php $colcount=0;
                            $SDate = Input::get("SDate");
                            $EDate = Input::get("EDate");
                            $Filter = Input::get("Filter");
                            $FVal = Input::get("FVal");
                            $user_type = Input::get("user_type");
                          ?>
                          @foreach($query_data as $row)
                            <?php $colcount++; ?>
                            <tr  bgcolor="<?php  echo ($colcount%2==0?"#FFFFCC":"#FFFFFF"); ?>" <?php  if ($Filter=="new_member" and $colcount <=($FVal-$limit*($page-1))) { ?>bgcolor="#FFEBBB"<?php  } ?>>
                              <td align="center" bgcolor="#FFC1C1" class="smallblack">
                              {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('activity.destroy', $row['id']))) }}
                              {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['id'].'").submit()')) }}
                              {{ Form::close() }}  
                              </td>
                              <td nowrap="nowrap">{{date('m/d/Y', strtotime($row['datetime_stamp']))." ".date("g:ia", strtotime($row['datetime_stamp']))}}</td>
                              <td height="30" nowrap="nowrap">{{$row['full_name']}}</td>
                              <td nowrap="nowrap">{{$row['user_name']}}</td>
                              <td nowrap="nowrap">{{strtoupper($row['user_type'])}}</td>
                              <td nowrap="nowrap">{{$row['main_module']}}</td>
                              <td>{{$row['sub_module']}}</td>
                              <td>{{$row['description']}}</td>
                              <td><a href="{{$row['url']}}">{{$row['url']}}</a></td>
                            </tr>
                          @endforeach
                        </tbody>
                  </table>
                </section>
              </div>
              {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}<br/>
              {{ HTML::link("job/excelExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}   
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
      $('#Filter').on('change',function(){
        var vl = $(this).val();
        if (vl == 'user_type') {
          $('#show_hide_val').html('<select name="user_type" class="form-control"><option value="">Select User Area</option><option value="admin">ADMIN</option><option value="front">FRONT</option></select>');
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
@stop