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
                 EXCEL REPORT GENERATOR
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                </header>
              </section>
             <!-- ////////////////////////////////////////// -->
            {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/opt'), 'files'=>true, 'method' => 'post')) }}
             <div class="panel-body">
             <div class="adv-table">
              <div class="form-group">
                 {{Form::label('uploadDataFile', 'Please Select DATA File (Tab Delimited Text File Only):', array('class' => 'control-label col-md-3', 'style'=>'font-size:12pt; font-weight:bold;'))}}
                <div class="controls col-md-9">
                  <div class="fileupload fileupload-new" data-provides="fileupload">
                    <span class="btn btn-white btn-file"  style="border:1px solid;">
                    <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
                    <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                    {{ Form::file('uploadDataFile', ['class' => 'default', 'id'=>'uploadDataFile' ,'required']) }}
                    </span>
                    <span class="fileupload-preview" style="margin-left:5px;"></span>
                    <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                 {{Form::label('uploadCostFile', 'Please Select COST File (Tab Delimited Text File Only):', array('class' => 'control-label col-md-3', 'style'=>'font-size:12pt; font-weight:bold;'))}}
                <div class="controls col-md-9">
                  <div class="fileupload fileupload-new" data-provides="fileupload">
                    <span class="btn btn-white btn-file"  style="border:1px solid;">
                    <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
                    <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                    {{ Form::file('uploadCostFile', ['class' => 'default', 'id'=>'uploadCostFile' ,'required']) }}
                    </span>
                    <span class="fileupload-preview" style="margin-left:5px;"></span>
                    <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
                    </div>
                  </div>
              </div>
              {{Form::submit("Download Excel Report", array('class' => 'btn btn-success', 'id'=>'export_excel_file'))}}
             </div>
              </div>
            {{ Form::close() }}
              </section>
              </div>
              </div>
              <!-- page end-->
              <script type="text/javascript">
              $( document ).ready(function() {

                  $("#uploadDataFile").change(function() {
                    var item = $("#uploadDataFile").val(); 
                    if (item.split(".").pop(-1) != 'txt'){
                      $("#uploadDataFile").val("");
                      alert("Use Only Text(.txt) files to upload!");
                    }
                  });

                  $("#uploadCostFile").change(function() {
                    var item = $("#uploadCostFile").val(); 
                    if (item.split(".").pop(-1) != 'txt'){
                      $("#uploadCostFile").val("");
                      alert("Use Only Text(.txt) files to upload!");
                    }
                  });

                  $("#export_excel_file").click(function() {
                    if ($("#uploadDataFile").val() == ''){
                      alert("Please select Data file first.");
                      return false;
                    }
                    if ($("#uploadCostFile").val() == ''){
                      alert("Please select Cost file first.");
                      return false;
                    }
                  });
              });
              </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
      <script src="{{asset('js/common-scripts.js')}}"></script>
      
@stop