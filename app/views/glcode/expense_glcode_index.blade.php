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
                 EXPENSE GL-CODE MANAGEMENT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i>Gl-Code Join Date / Name Filter</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('glcode/expense_glcode_index'), 'files'=>true, 'method' => 'post')) }}
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
                                    {{Form::select('Filter', array(''=>'Select Filter','expense_gl_code'=>'Expense Gl-Code','description'=>'Description','status'=>'Status'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                   <td id="show_hide_val" data-title="Filter Value:">
                                    <?php $Filter = Input::get('Filter');?>
                                    @if($Filter == 'status')
                                      <select name="status" class="form-control"><option value="A">Active</option><option value="B">Blocked</option></select>
                                    @else  
                                    {{ Form::text('FVal','', array('class' => 'form-control', 'id' => 'FVal')) }}
                                    @endif
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
                            <th>Total Expense Gl-Codes</th>  
                            <th>Active Expense Gl-Codes</th>  
                            <th>Blocked Expense Gl-Codes</th>  
                          </tr>
                        </thead> 
                        <tbody class="cf">
                          <tr>
                            <td data-title="Total Rentals:">{{$total_codes}}</td>
                            <td data-title="Active Rentals">{{$active_codes}}</td>
                            <td data-title="Blocked Rentals">{{$iac_codes}}</td>
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
                  <th>id#</th>
                  <th>Gl-Code </th>
                  <th>Description</th>
                  <th>Inc/Exc</th>
                  <th >Status</th>
                  <th >Action</th>
                </tr>
              </thead>
              <tbody class="cf">
              <?php $colcount=0;
                $prev = ""; 
              ?>
               @foreach($query_data as $row)
                  <?php
                    if ($prev!= $row['parentID']) {
                     $colcount++;
                    ?>
                    <tr>
                      <td><strong>{{$row['parentID']}}</strong></td>
                      <td>{{$row['parentGlCode']}}</td>
                      <td>{{$row['parentDescription']}}</td>
                      <td>{{$row['parent_exclude']?"Excluded":""}}</td>
                      <td><strong>{{$row['parentStatus']=='A'?'Active':'Blocked'}}</strong></td>
                      <td>
                      <a href="{{URL::route('glcode/edit_expense_glcode', array('id'=>$row['parentID']))}}">
                        {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                      </a>
                      {{ Form::open(array('method' => 'post','id'=>'myForm'.$row['parentID'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('glcode/deleteExpenseGCode', $row['parentID']))) }}
                      {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['parentID'].'").submit()')) }}
                      {{ Form::close() }}
                      </td>
                    </tr>
                 <?php  }     
                  if ($row['childID']) {
                  ?>
                  <tr>
                    <td><strong>{{$row['childID']}}</strong></td>
                    <td>{{$row['childGlCode']}}</td>
                    <td>{{$row['childDescription']}}</td>
                    <td>{{$row['child_exclude']?"Excluded":""}}</td>
                    <td><strong>{{$row['childStatus']=='A'?'Active':'Blocked'}}</strong></td>
                    <td>
                      <a href="{{URL::route('glcode/edit_expense_glcode', array('id'=>$row['childID']))}}">
                        {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                      </a>
                      {{ Form::open(array('method' => 'post','id'=>'myForm'.$row['childID'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('glcode/deleteExpenseGCode', $row['childID']))) }}
                      {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['childID'].'").submit()')) }}
                      {{ Form::close() }}
                    </td>
                  </tr>
                  <?php }  
                  $prev = $row['parentID'];
                  ?>                
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
          $('#show_hide_val').html('<select name="status" class="form-control"><option value="A">Active</option><option value="B">Blocked</option></select>');
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
       $('.default-date-picker').datepicker()
        .on('changeDate', function(ev) {
          var SDate = $('#SDate').val();
          var EDate = $('#EDate').val();
          if (SDate != '' && SDate != '') {
            var sd = new Date(SDate);
            var ed = new Date(EDate);
            if (sd>ed) {
              alert('End date can not be smaller than start date.');
              $('#SDate').val("");
              $('#EDate').val("");
              return false;
            }
          }
        });
    </script>
     <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop