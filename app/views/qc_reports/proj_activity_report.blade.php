@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
  <style type="text/css">
#header{
  background-color: yellow;  
    font-size: 20px;
    z-index: 1;
}
#header2{
  color:blue;
  font-size: 14px;
    z-index: 1;
}
#heading{
  color:#0099FF;
  font-size: 12px;
    text-align: center;
    background: #FFFFCC;
}
#heading2{
  color:black;
  font-size: 12px;
    text-align: center;
    background: #FFFFCC;
}
#header3{
  color:silver;
  font-size: 16px;
    z-index: 1;
}
#code {
        background: black;
        color: white;
        display: inline-block;
        vertical-align: right;
        margin-left:30px;
}
.txt_align{
  text-align: center !important;
  padding: 4px !important;
}
.profile{
  text-align: center !important;
  padding: 4px !important;
}
#td_color{
  background-color: #FFFFCC;
  padding: 4px !important;
}
div#profile{
  display:table;         
  width:auto;
  position:absolute;         
  background-color:#CFF3FC;         
  border:1px solid  #7F3333;         
  border-spacing:10px;/*cellspacing:poor IE support for  this*/
  white-space: nowrap;
}
.divRow{
  display: table-row;
}
.divCell{
  display: table-cell;
  border: solid;
  border-width: thin;
  padding: 5px;
}
.t_Heading
{
  display: table-row;
  font-weight: bold;
  text-align: center;
}
.disp_block{
  background-color: #ABDB77;
    display: inline-block;
    vertical-align: middle;
    margin-top: 5px;
    margin-right: 40px;
    width: 35px;
    height: 20px;
}
.disp_block1{
  background-color: #AAD4FF;
    display: inline-block;
    vertical-align: middle;
    margin-top: 5px;
    width: 35px;
    height: 20px;
}
</style>
              <!-- page start-->
              <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                Project Activity Report
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->  
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>SEARCH by:</b> Project Activity Report</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/proj_activity_report'), 'files'=>true, 'method' => 'post')) }}
                                <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                    <tbody>
                                    <tr>
                                      <td><b>Select Activity Report: </b></td>
                                      <td>
                                        {{Form::select('project_title',array(''=>'Select A Report')+$projects,'', ['id' => 'project_title', 'class'=>'form-control m-bot15'])}}
                                      </td>
                                      <td>
                                        {{Form::submit('Search', array('class' => 'btn btn-success'))}}
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
                  <div>{{$report}}</div>
                </section>  
              </section>
              </div>
              </div>
         <script>
           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
          $('#reset_search_form').click(function(){
              $('#project_title').val("");
          });
         /* $('#project_title').change(function(){

          });*/
        </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop