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
                  COUNTRIES MANAGEMENT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>View/ Edit/ Delete: countries. </i></b>
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
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">#</th>
                            <th style="text-align:center;">Name</th>
                            <th style="text-align:center;">State2</th>
                            <th style="text-align:center;">State3</th>
                            <th style="text-align:center;">Zip</th>
                            <th style="text-align:center;">Action</th>
                          </tr>
                        </thead>
                      <tbody>
                      <?php $i=1;?>
                          @foreach($query_data as $row)
                            <tr>
                              <td>{{$i++}}</td>
                              <td>{{$row['country']}}</td>
                              <td>{{$row['state2']}}</td>
                              <td>{{$row['state3']}}</td>
                              <td>{{$row['zip']}}</td>
                              <td>
                                <a href="{{URL::route('settings.edit', array('id'=>$row['country_id']))}}">
                                {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                                </a>
                                {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$row['country_id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('settings.destroy', $row['country_id']))) }}
                                {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['country_id'].'").submit()')) }}
                                {{ Form::close() }} 
                              </td>
                            </tr>
                          @endforeach
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
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
 @stop