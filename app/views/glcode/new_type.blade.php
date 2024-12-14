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
                ADD NEW EXPENSE GL CODE TYPE  
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>View/ Edit/ Delete: Expense Gl-Code Types. </i></b>
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
              <section id="no-more-tables" >
                  {{ Form::open(array('method' => 'post','id'=>'glcodeform', 'route' => array('glcode/createGLCEType'))) }}
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                    <tbody class="cf">
                      <tr>
                        <th>Add New Expense Gl-Code Type:</th>
                        <td>{{ Form::text('type','', array('class' => 'form-control dpd1', 'required')) }}</td>
                        <td>{{ Form::submit('Submit', array('class' => 'btn btn-success')) }}</td>
                      </tr>
                    </tbody>
                  </table>
                  {{Form::close()}}
              </section>
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">#id</th>
                            <th style="text-align:center;">Type</th>
                            <th style="text-align:center;">Action</th>
                          </tr>
                        </thead>
                      <tbody>
                          @foreach($data as $obj)
                            <tr>
                              <td data-title="id:">{{$obj->id}}</td>
                              <td data-title="Type:">{{$obj->type}}</td>
                              <td  data-title="Action:">
                                {{ HTML::link('#myModal','Edit', array('data-toggle'=>'modal','class' => 'btn btn-primary btn-xs','id'=>$obj->id,'name'=>'modalInfo', 'vtype'=>$obj->type,'style'=>'display:inline;'))}} 
                                {{ Form::open(array('method' => 'post','id'=>'myForm'.$obj->id.'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('glcode/deleteGlCType', $obj->id))) }}
                                {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$obj->id.'").submit()')) }}
                                {{ Form::close() }}
                              </td>
                            </tr>
                          @endforeach
                      </tbody>
                  </table>
                  {{ HTML::link('glcode/add_expense_glcode', 'Back' , array('class'=>'btn btn-link btn-xs'))}}
              </section>
                      <!-- ************************** Modal ***************************** -->
                              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{ Form::button('&times;', array('class' => 'close', 'data-dismiss'=>'modal','aria-hidden'=>'true')) }}
                                              <h4 class="modal-title">Update Expense Gl-Code Type</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="form-group">
                                                <div class="col-md-3" style="margin-top:8px;">
                                                  {{Form::label('glcode_type', 'GL-Code Type*:', array('class' => 'control-label col-md-2'))}}
                                                </div>
                                                <div class="col-md-6">
                                                    {{ Form::text('glcode_type','', array('class' => 'form-control dpd1', 'id' => 'glcode_type', 'required')) }}
                                                    {{ Form::hidden('glcode_id','', array( 'id'=>'glcode_id')) }}     
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                              {{ Form::submit('Submit', array('class' => 'btn btn-success','id'=>'submit_holiday_info')) }}
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
      $('a[name=modalInfo]').click(function(){
        $('#glcode_id').val($(this).attr('id'));
        $('#glcode_type').val($(this).attr('vtype'));
      });
    
     $("#submit_holiday_info").click(function(){
       if( $('#glcode_id').val() != "" && $('#glcode_type').val() != ""){
          $.ajax({
                      url: "{{URL('ajax/updateGLCType')}}",
                      data: {
                        'id' : $('#glcode_id').val(),
                        'type' : $('#glcode_type').val()
                      },
                      success: function (data) {
                        if (data == 0) {
                          alert('Data Validation fails! Please enter Valid value.');
                          return false;
                        }else{
                          location.reload();
                          alert("GL-Code updated successfully!");
                        }
                      },
          });
       }
       else
        alert("Please fill required information!");
     });

    </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
   
@stop