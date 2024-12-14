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
                  RECALCULATE WAGES 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\HEADER\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                </header>
              </section>
             <!-- ////////////////////////////////////////// -->
            <div class="panel-body">
             <div class="adv-table">
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('wages.recalculateWagesAuto'), 'files'=>true, 'method' => 'post')) }}
                {{Form::select('employees[]', $emp_row, null, ['multiple','class'=>'form-control'])}}
                <br/>
                {{Form::submit('Update', array('class' => 'btn btn-success'))}}
              {{ Form::close() }}
             </div>
            </div>
              </section>
              </div>
              </div>
              <!-- page end-->
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
      <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop