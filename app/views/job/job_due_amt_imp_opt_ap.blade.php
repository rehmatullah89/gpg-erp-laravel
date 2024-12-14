@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
 <!-- page start-->
              <div class="row">
                  <div class="col-lg-12">
                      <section class="panel">
                          <header class="panel-heading">
                             BULK JOB DUE AMOUNT (AP) UPLOADER
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
                          <div class="panel-body">
                              <div class="stepy-tab">
                                  <ul id="default-titles" class="stepy-titles clearfix">
                                      <li id="default-title-0" class="current-step">
                                          <div>Step 1</div>
                                      </li>
                                      <li id="default-title-1" class="">
                                          <div>Step 2</div>
                                      </li>
                                      <li id="default-title-2" class="">
                                          <div>Step 3</div>
                                      </li>
                                  </ul>
                              </div>
                             
  {{ Form::open(array('before' => 'csrf','id'=>'default','class'=>'form-horizontal' ,'url'=>route('job/job_due_amt_imp_opt_ap'), 'files'=>true, 'method' => 'post')) }}
                            <fieldset title="Step1" class="step" id="default-step-0">
                                      <legend> </legend>
                                     <div class="form-group">
                                       {{Form::label('uploadFile', 'Please Select File:', array('class' => 'control-label col-md-3', 'style'=>'font-size:12pt; font-weight:bold;'))}}
                                       <super>(Tab Delimited Text File Only)</super> 
                                      <div class="controls col-md-9">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                          <span class="btn btn-white btn-file"  style="border:1px solid;">
                                          <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
                                          <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                          {{ Form::file('uploadFile', ['class' => 'default','id'=>'uploadFile' ,'required']) }}
                                          </span>
                                          <span class="fileupload-preview" style="margin-left:5px;"></span>
                                          <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
                                          </div>
                                        </div>
                                    </div>

                                  </fieldset>
                                  <fieldset title="Step 2" class="step" id="default-step-1" >
                                      <legend> </legend>
                                      <?php $i=1;?>
                                      @if(isset($jcostopt_arr) && !empty($jcostopt_arr))
                                      <input type="hidden" name="hidden_count" value="{{count($jcostopt_arr)}}">
                                      @foreach($jcostopt_arr as $val)
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">File Field {{$i}}:</label>
                                          <div class="col-lg-4">
                                              <input type="text" value="{{$val}}" class="form-control" id="field_file_{{$i}}" name="field_file_{{$i}}" readonly>
                                          </div>
                                          <label class="col-lg-2 control-label">DB Field {{$i}}:</label>
                                          <div class="col-lg-4">
                                               {{Form::select('field_db_'.$i, $DBFields,'', ['id' => 'field_db_'.$i, 'class'=>'form-control m-bot15'])}}
                                          </div>
                                      </div>
                                      <?php $i++;?>
                                      @endforeach
                                      @endif
                                  </fieldset>
                                  <fieldset title="Step 3" class="step" id="default-step-2" >
                                      <legend style="color:red;"> WARNNING: All the Previous Records Will be Deleted from database!!</legend>
                                      <?php $i=1;?>
                                      @if(isset($jcostopt_arr))
                                      @foreach($jcostopt_arr as $val)
                                      <div class="form-group">
                                          <label class="col-lg-2 control-label">File Field {{$i}}:</label>
                                          <div class="col-lg-4">
                                              <input type="text" value="{{$val}}" class="form-control" id="file_field_{{$i}}" name="file_field_{{$i}}" readonly>
                                          </div>
                                          <label class="col-lg-2 control-label">DB Field {{$i}}:</label>
                                          <div class="col-lg-4">
                                              <input type="text" value="" class="form-control" id="db_field_{{$i}}" name="db_field_{{$i}}" readonly> 
                                          </div>
                                      </div>
                                      <?php $i++;?>
                                      @endforeach
                                      @endif
                                  </fieldset>
                                  <input type="button" id="finish_button" class="finish btn btn-danger" value="Finish"/>
                              {{ Form::close() }}
                          </div>
                       </section>
                  </div>
              </div>
        <!-- page end-->        
<script src="{{asset('js/jquery.stepy.js')}}"></script>
<script>

      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd',
          minDate: new Date(),
      });

      $(function() {
          $('#default').stepy({
              backLabel: 'Previous',
              block: true,
              nextLabel: 'Next',
              titleClick: true,
              titleTarget: '.stepy-tab',
              next: function(index) {
              var step = '{{$step}}';
              if (step == '0'){
                    if($('#uploadFile').val() == ""){
                      alert("Please select file first."); 
                      return false;  
                    }else{
                      conf = confirm('WARNNING: All the Previous Records Will be Deleted from database!!');
                      if(conf == false){
                        return false;
                      }else{
                         $('#default').submit();
                      }
                    }
                }
              },
          });
        });
    $(document).ready(function() {
      var step = '{{$step}}';
      if (step == '1'){
        $('#default-next-0').click();
        $('#default-back-1').hide();
        $('#default-back-2').hide();
      }
      $('#default-next-1').click(function(){
        var arr = '{{json_encode($jcostopt_arr)}}';
        var i=1;
        if(arr != ''){
          while(i<arr.length){
            $('#db_field_'+i).val($('#field_db_'+i).val());
            i++;
          }
        }
      });
      $('#finish_button').click(function(){
        document.getElementById("uploadFile").required = false;
        $('#default').submit();
      });
          var success = '<?php echo $success;?>';
            if (success == 1)
              alert("Successfully updated list");
            $("#uploadFile").change(function() {
            var item = $("#uploadFile").val(); 
            if (item.split(".").pop(-1) != 'txt'){
              $("#uploadFile").val("");
              alert("Use Only Text(.txt) files to upload!");
             }
          });
      });

  </script>
  <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
  <script src="{{asset('js/common-scripts.js')}}"></script>

@stop