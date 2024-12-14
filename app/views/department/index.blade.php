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
                MANAGE DEPARTMENT  
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>View/ Edit/ Delete: departments. </i></b>
                          </header>
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">#</th>
                            <th style="text-align:center;">Name</th>
                            <th style="text-align:center;">Head Name</th>
                            <th style="text-align:center;">Department Users</th>
                            <th style="text-align:center;">Actions</th>
                          </tr>
                        </thead>
                      <tbody>
                        @foreach($query_data as $data)
                          <tr>
                            <td data-title="#ID:">{{ $data['id'] }}</td>
                            <td data-title="Name:">{{ $data['dept_name'] }}</td>
                            <td data-title="Head name:">{{ $data['head'] }}</td>
                            <td data-title="Dept Users:">{{ $data['dept_users'] }}</td>
                            <td data-title="action">
                            <a title="Modify Dept" style="display:inline;" href="{{URL::route('department.edit', array('id'=>$data['id']))}}">
                            {{ Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-lg')) }}
                           </a>
                            <a title="Manage Dept Users" style="display:inline;" href="{{URL::route('department.show', array('id'=>$data['id']))}}">
                            {{ Form::button('<i class="fa fa-users"></i>', array('class' => 'btn btn-success btn-lg', 'title'=>'Manage Dept. users')) }}
                            </a> 
                            {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$data['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('department.destroy', $data['id']))) }}
                            {{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-lg','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$data['id'].'").submit()')) }}
                            {{ Form::close() }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
                  {{ $query_data->links() }}                                  
              </section>
                      <!-- ************************** Modal ***************************** -->
                              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{ Form::button('&times;', array('class' => 'close', 'data-dismiss'=>'modal','aria-hidden'=>'true')) }}
                                              <h4 class="modal-title">Update Holiday</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="form-group">
                                              {{Form::label('holiday_desc', 'Holiday Description*:', array('class' => 'control-label col-md-2'))}}
                                                  <div class="col-md-6">
                                                        {{ Form::text('holiday_desc','', array('class' => 'form-control dpd1', 'id' => 'holiday_desc', 'required')) }} 
                                                        {{ Form::hidden('holiday_id','', array('class' => 'form-control dpd1', 'id' => 'holiday_id', 'required')) }} 
                                                  </div>
                                              </div><br/><br/>
                                              <div class="form-group">
                                                   {{Form::label('DOB', 'Select Date*:', array('class' => 'control-label col-md-2'))}}
                                                  <div class="col-md-6">
                                                      {{ Form::text('DOB','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'DOB', 'required')) }}
                                                  </div>
                                              </div>
                                          </div>
                                            <div class="btn-group" style="padding:20px;">
                                              {{ Form::submit('Submit', array('class' => 'btn btn-success', 'id'=>'submit_holiday_info','data-dismiss'=>'modal')) }}
                                              {{ Form::button('Cancel', array('class' => 'btn btn-danger', 'data-dismiss'=>'modal')) }}
                                            </div>
                                      </div>
                                  </div>
                              </div>
                      <!-- modal -->
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->   
@stop