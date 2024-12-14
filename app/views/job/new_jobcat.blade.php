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
                ADD NEW JOB CATEGORY
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Fill required* Information to Add New JOB CATEGORY! </i></b>
                          </header>
              </section>
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
             <!-- ////////////////////////////////////////// -->
             {{ Form::open(array('before' => 'csrf' ,'url'=>route('quote/new_jobcat'), 'id'=>'frmid1', 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody class="cf">
                          <tr>
                            <th style="text-align:center;">Job Category Name*:</th>
                            <td data-title="#Name">
                            {{ Form::text('name','', array('class' => 'form-control dpd1', 'id' => 'name', 'required')) }}
                          </td>
                           <td style="padding-left:5%;">
                              {{ Form::submit('Submit Job Category Name', array('class' => 'btn btn-success')) }}
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
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd',
          minDate: new Date()
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop