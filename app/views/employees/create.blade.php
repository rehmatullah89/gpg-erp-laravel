@extends("layouts/dashboard_master")
@section('content')
 
@stop
@section('dashboard_panels')
    <!-- page start--> 
<?php
    $action = "add";  
    $breadCrumb = "ADD NEW EMPLOYEE TYPE";
    if(isset($GpgEmployeeTypeData) && !empty($GpgEmployeeTypeData)){
         $action = "update";
         $breadCrumb = "UPDATE EMPLOYEE TYPE";
    }
?>
    
    <div class="row">
      <div class="col-sm-12">
    <section class="panel">
    <header class="panel-heading">
     
        {{$breadCrumb}}
      
    </header>
    <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
    <section class="panel">
        <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
            <i>  Employee Type Information: </i>
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
            
        @if($action == 'add')
            {{ Form::open(array('before' => 'csrf' ,'url'=>route('employees.create'), 'id'=>'employeeTypeForm', 'files'=>true, 'method' => 'post')) }}
        @elseif($action == 'update')
            {{ Form::open(array('before' => 'csrf' ,'url'=>URL::route('employees.update', array('id'=>$GpgEmployeeTypeData->type_id)), 'id'=>'employeeTypeForm', 'files'=>true, 'method' => 'put')) }}
            <input type="hidden" name="type_id" value="<?php echo $GpgEmployeeTypeData->type_id; ?>">
        @endif
          <section id="no-more-tables" style="padding:10px;">
          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
            <tbody>
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Type Name*:</th>
                      <td>
                          {{ Form::text('cname',@$GpgEmployeeTypeData->type, array('class' => 'form-control', 'id' => 'cname', 'required')) }}
                      </td>
                  </tr>
                  <tr>
                      <td>&nbsp;</td>   
                      <td >
                          <br><br>
                          @if($action == 'add')
                            {{Form::submit("Add Type", array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                          @else
                            {{Form::submit("Update Type", array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                          @endif  
                      </td>
                  </tr>

              <br/>

            </section>
        {{ Form::close() }}
    </section>
<!-- ////////////////////////////////////////// -->
 </section>
 </div>
 </div>
 <!-- page end-->


<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script>
<script type="text/javascript">
  $( document ).ready(function() {
      //
  });
</script>    
@stop