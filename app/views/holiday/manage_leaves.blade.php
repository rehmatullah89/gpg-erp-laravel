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
                  MANAGE LEAVE APPLICATIONS
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>SEARCH by:</b> Leave Date / Field Filters</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('holiday/manage_leaves'), 'files'=>true, 'method' => 'post')) }}
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
                              <i><b> VIEW / EDIT LEAVE APPLICATIONS</b></i>
                          </header>
     <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                          <th style="text-align:center;">#ID</th>
                                          <th style="text-align:center;">Employee Name</th>
                                          <th style="text-align:center;">Type</th>
                                          <th style="text-align:center;">Date/ Day</th>
                                          <th style="text-align:center;">Hours</th>
                                          <th style="text-align:center;">Vac Bal</th>
                                          <th style="text-align:center;">Sick Bal</th>
                                          <th style="text-align:center;">Status</th>
                                          <th style="text-align:center;">
                                           {{ Form::checkbox('checkThis', '','', array('class' => 'input-group', 'style'=>'display:inline;','onclick'=>'toggle(this)')) }}
                                          </th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      @foreach ($query_data as $key => $data)
                                        <tr>
                                          <td data-title="#ID:">{{$data['id']}}</td>
                                          <td data-title="Employee Name:">{{$data['emp_name']}}</td>
                                          <td data-title="Type:">{{$data['off_type']}}</td>
                                          <td data-title="Date/Day:"><strong>{{date('Y-m-d'." l",strtotime($data['leave_date']))}}</strong><br>
                                          {{date("h:iA",strtotime($data['start_time']))." TO ".date("h:iA",strtotime($data['end_time']))}}</td>
                                          <td data-title="Hours">{{$data['hours']}}</td>
                                          <td data-title="Vac Bal:">h</td>
                                          <td data-title="Sick Bal:">h</td>
                                          <td data-title="Status:">
                                          @if(empty($data['status'])) {{'Pending'}}
                                            @elseif ($data['status'] == 'R') {{'Rejected'}}
                                            @elseif ($data['status'] == 'A') {{'Approved'}}
                                          @endif
                                          </td>
                                          <td data-title="Select/Modify:">
                                          {{ Form::checkbox('statusCheck[]', '','', array('id' => $data['id'])) }}
                                          </td>
                                        </tr>
                                      @endforeach  
                                        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                          <td align="center" data-title="#Modify Status:">
                                           {{Form::select('change_status', array('A' => 'APPROVED', 'R' => 'REJECTED', '' => 'PENDING'), null, ['id' => 'change_status', 'style'=>'width:auto;'])}}&nbsp;&nbsp;
                                           {{ Form::submit('Update', array('class' => 'btn btn-success btn-xs', 'id'=>'update_status')) }}
                                          </td>
                                        </tr>
                                      </tbody>
                                  </table>
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

          
          function toggle(source) {
            checkboxes = document.getElementsByName('statusCheck[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
            }
          }

          $("#update_status").click(function(){
              checkboxes = document.getElementsByName('statusCheck[]');
              for(var i=0, n=checkboxes.length;i<n;i++) {
                if (checkboxes[i].checked == 1){
                  $.ajax({
                      url: "{{URL('ajax/updateStatus')}}",
                      data: {
                        'id' : $(checkboxes[i]).attr('id'),
                        'status' : $("#change_status").val(),
                      },
                      success: function (data) {
                        location.reload();
                        //alert("LEAVE APPLICATIONS Status successfully updated!");
                      },
                  });
                }
              }
          });  

          
              
      </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop