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
                  EDIT VACATION SICK BALANCES 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>SEARCH BY:</b> Employee Join Date / Name Filter</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('holiday/edit_vacation_sick'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                      <th>
                                      {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                      <th>
                                        {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                      <th><b>Filter</b></th>
                                      <th><b>Filter Value</b></th>
                                    </tr>
                                  </thead>
                                  <tbody><tr>
                                  <td data-title="Start Date:">
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'start_date')) }}
                                 </td><td data-title="End Date:">
                                 {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'end_date')) }}
                                 </td>
                                    <td data-title="Filter:">
                                   <div>
                                    {{Form::select('filter_val', array(''=>'Select Filter','name' => 'Real Name', 'login' => 'Login Name', 'status' => 'Member Status', 'new_member' => 'New Members'), null, ['id' => 'filter_val', 'class'=>'form-control m-bot15'])}}
                                    </div>
                                    </td>
                                    <td data-title="Filter Value:"><div id="filter_change">
                                    </div></td>
                                    </tr></tbody></table>
                                    <br/>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info', 'style'=>'margin-top:-15px;'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
                                  </section>
                               {{ Form::close() }}
              </section>     
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                        <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                            <i><b>  VIEW/EDIT VACATION/SICK BALANCES</b></i>
                        </header>
                        {{ Form::open(array('before' => 'csrf' ,'url'=>route('holiday/updateBalance'), 'files'=>true, 'method' => 'post')) }}
                                <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                          <th style="text-align:center;">#ID</th>
                                          <th style="text-align:center;">Name</th>
                                          <th style="text-align:center;">Vacation Balance<br/>(hours)</th>
                                          <th style="text-align:center;">Sick Balance<br/>(hours)</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                     @foreach ($query_data as $key => $row)
                                        <tr>
                                          <td data-title="#ID:">{{$row['id']}}
                                          {{ Form::hidden('empid[]',$row['id']) }}
                                          </td>
                                          <td data-title="Name:" align="center">{{$row['name']}}</td>
                                          <td data-title="Vacation:" align="center">
                                            {{ Form::text('vacation[]',$row['vacation'], array('class' => 'form-control')) }}
                                          </td>
                                          <td data-title="Sick:" align="center">                                            {{ Form::text('sick[]',$row['sick'], array('class' => 'form-control')) }}
                                          </td>
                                        </tr>
                                     @endforeach  
                                      </tbody>
                                </table>
                                <br/>
                                {{ Form::submit('Update Vacation/Sick Balances', array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;')) }}
                                <br/>
                         {{ Form::close() }}   
                           {{ $query_data->links() }}  
                </section>
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
       <script>
           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
          
          $('div#filter_change').html("<input type='text' name='FVal' class='form-control'>");
          $("select#filter_val").change(function() { 
              if( $(this).find('option:selected').val() == 'status')
                  $('div#filter_change').html("<select name='filter_status' class='form-control m-bot15'><option value='A'>Active Members</option><option value='B'>Inactive Members</option></select>"); 
              else
                $('div#filter_change').html("<input type='text' name='FVal' class='form-control'>"); 
          });

          $('#reset_search_form').click(function(){
              $('#start_date').val("");
              $('#end_date').val("");
              $('#filter_val').val("");
              $('div#filter_change').html("<input type='text' name='FVal' class='form-control'>");
          });

      </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop