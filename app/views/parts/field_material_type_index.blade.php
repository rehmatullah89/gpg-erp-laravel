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
                    PART TYPE MANAGEMENT  
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                      <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                             <b><i>View/ Edit/ Delete: part types. </i></b>
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
                            <th style="text-align:center;">Part Type</th>
                            <th style="text-align:center;">Action</th>
                          </tr>
                        </thead>
                      <tbody>
                        @foreach($query_data as $row)
                          <tr>
                            <td>{{$row->id}}</td>
                            <td>{{$row->name}}</td>
                            <td>
                              <a style="display:inline;" data-toggle="modal" href="#myModal" class="link_id" id="{{$row->id}}" name="edit_link" typeName="{{htmlentities($row->name)}}">
                                {{ Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs', 'title'=>'Manage Holidays.')) }}
                              </a>
                              {{ Form::open(array('method' => 'post','id'=>'myForm'.$row->id.'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('parts/deletePartType', $row->id))) }}
                              {{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row->id.'").submit()')) }}         
                              {{ Form::close() }}
                            </td>
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
                                              <h4 class="modal-title">Update Part Type</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="form-group">
                                                  {{Form::label('_name', 'Part Type Name*:', array('class' => 'control-label col-md-2'))}}
                                                  <div class="col-md-6">
                                                    {{ Form::text('_name','', array('class' => 'form-control dpd1', 'id' => '_name', 'required')) }}
                                                    {{ Form::hidden('_type_id','', array('id' => '_type_id')) }}
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                              {{ Form::submit('Submit', array('class' => 'btn btn-success','id'=>'submit_part_type')) }}
                                              {{ Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal')) }}
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
    <script type="text/javascript">
     $('a[name=edit_link]').click(function(){
        var id = $(this).attr('id');
        var type = $(this).attr('typeName');
        var encodedStr = type.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
          return '&#'+i.charCodeAt(0)+';';
        });
        $('#_name').val(encodedStr);
        $('#_type_id').val(id);
     });
     $('#submit_part_type').click(function(){
        if ( $('#_name').val() == ''){
          alert('Field Part Type must not be empty!');
          return false;
        }else{
          $.ajax({
            url: "{{URL('ajax/updatePartType')}}",
              data: {
                'id' : $("#_type_id").val(),
                'name' : $("#_name").val()
              },
              success: function (data) {
                alert("Part Type successfully updated!");
                location.reload();
            },
          });
        }
     });

    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop