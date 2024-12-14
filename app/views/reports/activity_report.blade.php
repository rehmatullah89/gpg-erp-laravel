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
               ACTIVITY REPORT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>SEARCH by:</b> Dates </i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/activity_report'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                     <th>
                                      {{Form::label('SDateCreated', 'Created Start Date:*', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                     <th>
                                        {{Form::label('EDateCreated', 'Created End Date:*', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                      <th><b>Actions</b></th>
                                    </tr>
                                  </thead>
                                  <tbody><tr>
                                    <td data-title="Created Start Date:">
                                     {{ Form::text('SDateCreated','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDateCreated','required')) }}
                                    </td>
                                    <td data-title="Created End Date:">
                                     {{ Form::text('EDateCreated','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDateCreated','required')) }}
                                    </td>
                                    <td data-title="Action:">
                                      {{Form::submit('Generate Report', array('class' => 'btn btn-success'))}}
                                      {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                    </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                  </section>
                               {{ Form::close() }}
              </section>  
                <section id="flip-scroll" style="padding:10px;">
                <?php $dbDateStart = Input::get('SDateCreated'); $dbDateEnd=Input::get('EDateCreated');?>
                <span><b>Period Start Date:</b>{{!empty($dbDateStart)?$dbDateStart:date('Y-m-d')}}</span>
                <span style="margin-left:20px;"><b>Period End Date:</b>{{!empty($dbDateEnd)?$dbDateEnd:date('Y-m-d')}}</span>
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>TimeSheet Date</th>
                        <th> Job Number  </th>
                        <?php 
                          $DayInfo = '';
                          for ($i = 1; $i <= $tDays; $i++) {
                            $DayInfo_s = DB::select(DB::raw("select ADDDATE('" .(!empty($dbDateStart)?$dbDateStart:date('Y-m-d')). "', INTERVAL " . ($i - 1) . " DAY) as int_days"));
                            if (!empty($DayInfo_s) && isset($DayInfo_s[0]->int_days)){
                              $DayInfo = $DayInfo_s[0]->int_days;
                            }
                            ?>
                            <th align="center" nowrap="nowrap" bgcolor="<?php echo (date("D", strtotime($DayInfo)) == "Sat" || date("D", strtotime($DayInfo)) == "Sun" ? "#FFFFCC" : "#FFFFFF"); ?>" ><strong>&nbsp;
                            <?php
                                $Dates[] = $DayInfo;
                                echo date('Y-m-d', strtotime($DayInfo));
                            ?> &nbsp;</strong></th><?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $activityCount = array();
                        $curRow = 1;
                        foreach ($query_data as $key => $EmployeeJob_row){
                          $THurs =0;
                      ?>
                      <tr>
                        <td bgcolor="#F0F0F0" nowrap="nowrap">{{$EmployeeJob_row['empName']}}</td>                      
                        <td bgcolor="#F0F0F0" nowrap="nowrap" align="center">{{$EmployeeJob_row['tsDate']}}</td>
                        <td bgcolor="#F0F0F0" nowrap="nowrap">{{$EmployeeJob_row['JobNum']}}</td>
                        <?php  for ($i=0; $i<count($Dates); $i++) {  ?>
                        <td align="center" nowrap="nowrap" bgcolor="<?php echo $color = (date("D",strtotime($Dates[$i]))=="Sat" || date("D",strtotime($Dates[$i]))=="Sun"?"#FFFFCC":"#FFFFFF"); ?>" >&nbsp;&nbsp;<?php                      
                        $Hurs = @$datesArr[$Dates[$i]][$EmployeeJob_row['empId']][$EmployeeJob_row['JobNum']][$EmployeeJob_row['JobId']];
                        if ($Hurs!="") {
                          $activityCount[$Dates[$i]] = 1+@$activityCount[$Dates[$i]]; 
                          echo $Hurs;
                        if ($color=="#FFFFFF") 
                          $THurs+= round($Hurs,2);
                        } 
                        ?>&nbsp;&nbsp;</td>
                        <?php   }  ?>
                        </td>
                      <?php
                        $curRow++;
                      }?>
                      <tr>
                        <td height="25" nowrap="nowrap" bgcolor="#F0F0F0"><strong>Total Activity Count:</strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <?php  for ($i=0; $i<count($Dates); $i++) {  ?>
                        <td align="center" nowrap="nowrap" bgcolor="<?php echo $color = (date("D",strtotime($Dates[$i]))=="Sat" || date("D",strtotime($Dates[$i]))=="Sun"?"#FFFFCC":"#FFFFFF"); ?>" >&nbsp;&nbsp;<strong><?php 
                          echo @$activityCount[$Dates[$i]];
                        ?>&nbsp;&nbsp;</strong>
                        </td>
                        <?php   }  ?>
                      </tr>
                    </tbody>
                  </table>
                  {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
                </section>
              </section>
              </div>
              </div>
              <!-- page end-->
       <script>
           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
          
          $('#reset_search_form').click(function(){
              $('#SDateCreated').val("");
              $('#EDateCreated').val("");
          });
        </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop