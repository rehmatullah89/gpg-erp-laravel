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
               Update GL-CODE
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Enter required* Inoformation! </i></b>
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
              </section>
             <!-- ////////////////////////////////////////// -->
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('glcode.update',array('id'=>$row['id'])), 'files'=>true, 'method' => 'put')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody>
                          <tr>
                          <th style="text-align:center;">GL-Code#*:</th>
                          <td>{{ Form::text('gl_code',$row['gl_code'], array('class' => 'form-control dpd1', 'id' => 'gl_code', 'required')) }}
                              <input type="hidden" name="old_glCode" value="{{$row['gl_code']}}">
                          </td>
                          </tr>
                          <tr class="qntity">
                            <th style="text-align:center;">Description:</th>
                            <td>{{ Form::text('description',$row['description'], array('class' => 'form-control dpd1', 'id' => 'description','required')) }}</td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Status:</th>
                          <td>
                            {{ Form::select('status',array('A'=>'Active','B'=>'Blocked'),$row['status'], array('class' => 'form-control dpd1', 'id' => 'eqpType', 'required')) }}
                            </td>
                          </tr>
                        </tbody>
                  </table>
                    {{ Form::submit('Update GL-Code', array('class' => 'btn btn-success')) }}
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