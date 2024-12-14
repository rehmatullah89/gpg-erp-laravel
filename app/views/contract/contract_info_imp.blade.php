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
                 CONTRACT INFO. IMPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
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
            {{ Form::open(array('before' => 'csrf' ,'url'=>route('contract/contract_info_imp'), 'files'=>true, 'method' => 'post')) }}
             <div class="panel-body">
             <div class="adv-table">
              <div class="form-group">
                 {{Form::label('uploadFile', 'Please Select File (Xls or Xlsx File Only):', array('class' => 'control-label col-md-3', 'style'=>'font-size:12pt; font-weight:bold;'))}}
                <div class="controls col-md-9">
                  <div class="fileupload fileupload-new" data-provides="fileupload">
                    <span class="btn btn-white btn-file"  style="border:1px solid;">
                    <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
                    <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                    {{ Form::file('uploadFile', ['class' => 'default', 'id'=>'uploadFile' ,'required']) }}
                    </span>
                    <span class="fileupload-preview" style="margin-left:5px;"></span>
                    <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
                    </div>
                  </div>
              </div>
              {{Form::submit("Import Info >>", array('class' => 'btn btn-success', 'id'=>'insert_update_counties'))}}
             </div>
              </div>
            {{ Form::close() }}
              </section>
              </div>
              </div>
              <!-- page end-->
              <script type="text/javascript">
              $( document ).ready(function() {

                var success = '<?php echo $success;?>';
                if (success == 1)
                  alert("Successfully updated list");

                  $("#uploadFile").change(function() {
                    var item = $("#uploadFile").val(); 
                    if (item.split(".").pop(-1) != 'xls' && item.split(".").pop(-1) != 'xlsx'){
                      $("#uploadFile").val("");
                      alert("Use Only Xlx or Xlsx files to upload!");
                    }
                  });

                  $("#insert_update_counties").click(function() { 
                    if ($("#uploadFile").val() == ''){
                      alert("Please select file first.");
                      return false;
                    }
                  });
              });
              </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
      <script src="{{asset('js/common-scripts.js')}}"></script>
      
@stop