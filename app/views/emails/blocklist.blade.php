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
                  EMAILS BLOCK LIST 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel-body">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b>Search by:</b><i> Block Type / Block Value Filter </i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('emails/blocklist'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <tbody>
                                    <tr>
                                     <th> <b>Block Type</b></th><td data-title="MailBox:">{{Form::select('btype_search', array(''=>'Select Block Type','to'=>'To Email','from'=>'From Email','subject'=>'Subject'), null, ['id' => 'btype_search', 'class'=>'form-control m-bot15'])}}</td>
                                      <th><b>Block Value</b></th><td data-title="Filter Value:">{{ Form::text('block_value','', array('class' => 'form-control', 'id' => 'block_value')) }}</td>
                                      <td>
                                        {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                                      </td>
                                    </tr>
                                  </tbody>
                                 </table>
                                </section>
                               {{ Form::close() }}
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                    <thead class="cf">
                      <tr>
                        <th style="text-align:center;">Active</th>
                        <th style="text-align:center;">Blocked By</th>
                        <th style="text-align:center;">     Block Value   </th>
                        <th style="text-align:center;">Block All</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $colcount=0;
                      $email_type_arr["from"] = "From Email" ;
                      $email_type_arr["to"] = "To Email" ;
                      $email_type_arr["subject"] = "Subject" ;
                      $email_type_arr["both"] = "From & To Email" ;
                    ?>
                      @foreach($data_arr as $row)
                        <tr>
                          <td><input type="checkbox" name="chk_active[]" <?php echo ($row['active']==1?'checked=checked':'')?>></td>
                          <td>{{$email_type_arr[$row['block_type']]}}</td>
                          <td>{{$row['block_value'] }}</td>
                          <td><?php echo ($row['block_all'] == 1)?"yes":"no"; ?></td>
                        </tr>
                      @endforeach                       
                    </tbody>
                  </table>
                </section>
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
              
       <script>
       $(document).ready(function(){

           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });

       });   
  </script>
   <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop