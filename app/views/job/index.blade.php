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
                    JOB CATEGORY MANAGEMENT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>View/ Edit/ Delete: Job Categories. </i></b>
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
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">#</th>
                            <th style="text-align:center;">Category Name </th>
                           <th style="text-align:center;">Action</th>
                          </tr>
                        </thead>
                      <tbody>
                      <?php
                      $dataArray = array();
                      ?>
                        @foreach($job_types as $data)
                          <tr>
                            <td data-title="#ID">{{ $data['id'] }}</td>
                            <td data-title="#name">{{ $data['name'] }}</td>
                            <td data-title="action">
                            <a href="{{URL::route('quote.edit', array('id'=>$data['id']))}}">
                            {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                            </a>         
                            {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$data['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('quote.destroy', $data['id']))) }}
                            {{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$data['id'].'").submit()')) }}         
                            {{ Form::close() }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
              </section>
                     
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script> 
@stop