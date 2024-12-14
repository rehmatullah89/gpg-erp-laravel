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
                EDIT DEPARTMENT 
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Modify required* Inoformation! </i></b>
                          </header>
              </section>
             <!-- ////////////////////////////////////////// -->
             {{ Form::open(array('before' => 'csrf','method' => 'PUT' ,'url'=>route('department.update', array('id'=> $id)))) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">Department Name*</th>
                            <th style="text-align:center;">Action</th>
                           </tr>
                        </thead>
                      <tbody>
                          <tr>
                            <td data-title="Dept. Name:">
                            {{ Form::text('dept_name',$query_data, array('class' => 'form-control dpd1', 'id' => 'dept_name', 'required')) }}
                            </td>
                            <td style="padding-left:5%;">
                            {{ Form::submit('Update Department Name', array('class' => 'btn btn-success')) }}
                            </td>
                          </tr>
                      </tbody>
                  </table>                                
              </section>
              </div>
              </div>
              {{ Form::close() }}
              </section>
              </div>
              </div>
              <!-- page end-->
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop