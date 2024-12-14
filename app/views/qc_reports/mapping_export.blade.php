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
                UNMAPPED RECORDS EXPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->  
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>Export :</b>UNMAPPED RECORDS DATA</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/mapping_export'), 'files'=>true, 'method' => 'post')) }}
                                <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                    <tbody>
                                    <tr>
                                      <td><b>UNMAPPED RECORDS EXPORT: </b></td>
                                      <td>
                                        {{Form::select('method',array(''=>'Select Export Type','gpg_vendor~2'=>'Vendors','gpg_customer~3'=>'Customers','gpg_expense_gl_code~0'=>'Accounts','gpg_job~1'=>'Jobs'),'', ['id' => 'method', 'class'=>'form-control m-bot15'])}}
                                      </td>
                                      <td>
                                        {{Form::submit('Report', array('class' => 'btn btn-primary'))}}
                                        {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                      </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <br/>
                                  </section>
                               {{ Form::close() }}
              </section> 
                <section id="no-more-tables" style="padding:10px;">
                  <div>{{''}}</div>
                </section>  
              </section>
              </div>
              </div>
         <script>
           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
          $('#reset_search_form').click(function(){
              $('#method').val("");
          });
         /* $('#project_title').change(function(){

          });*/
        </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop