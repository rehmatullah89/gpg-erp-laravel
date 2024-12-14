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
                 SET TIMESHEET WAGE 
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
              <div class="panel-body">
             <div class="adv-table">
             {{ HTML::link('javascript:;', 'Click here to Update Wages', array('id' => 'pulsate-regular','class'=>'btn btn-info'))}}
             </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
       <script>
       $(document).ready(function(){
        
        $("#pulsate-regular").effect( "pulsate", 
          {times:500}, 1000000 
        );
      
        $('#pulsate-regular').click(function(){
          $("#pulsate-regular").after('&nbsp;&nbsp;<span class="glyphicon glyphicon-refresh spinning"></span> Updating Wages... ');
          $(this).attr("disabled", "disabled");
          $.ajax({
                      url: "{{URL('ajax/updateWagesAuto')}}",
                      success: function (data) {
                        alert("Wages has been Updated successfully!");
                        location.reload();
                      },
                });
        });


       });   
      </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
      <script src="{{asset('js/common-scripts.js')}}"></script>
      
@stop