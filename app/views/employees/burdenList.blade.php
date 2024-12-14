@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$sdate = "";
$edate = "";
?>
 <header class="panel-heading">
     	BURDEN LOG MANAGEMENT
    <span class="tools pull-right">
       <a href="javascript:;" class="fa fa-chevron-down"></a>
    </span>
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

 {{ Form::open(array('before' => 'csrf' ,'action' => 'EmployeeController@employeeBurdunList', 'files'=>true, 'method' => 'post', 'id' => 'customer_filters_form')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Start Date:">
                                    {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td><td data-title="End Date:">
                                    {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>                                  
                                 
                                  <td data-title="Filter:">
                                    {{Form::label('fliter', 'Filter:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('Filter', array(''=>'Select Filter','emp_name' => 'Employee Name' ), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Filter Value:">
                                    {{Form::label('filterValue', 'Filter Value:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('FVal','', array('class' => 'form-control form-control-inline input-medium', 'id' => 'filterValue')) }}
                                  </td>
                                </tr>
                             
                                <tr>
                                <td colspan="8">
                                  <span class="smallblack"><strong>Note:</strong> 
                                     </span><br/>
                                     <span>
                                          1.Please leave empty the End date if you want to search for a particular date.
                                     </span><br>
                                      <span>
                                         2.Please leave empty the Start date if you want to search from very start to End Date
                                      </span>
                                  
                                </td>                                
                              </tbody>
                          </table>
                                    <br/>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info submit-form', 'style'=>'margin-top:-15px;'))}}
                                  {{Form::reset('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
                      </section>
                               {{ Form::close() }}
                               
<div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                    <thead class="cf">

                        <tr>
                            <th style="text-align:center;">ID#</th>
                            <th style="text-align:center;">Employee Name</th>
                            <th style="text-align:center;">Commission</th>
                            <th style="text-align:center;">Start From</th>
                            <th style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                <tbody>
                
                <?php 
                
                 $prev = "";
               
                foreach($query_data as $data){ ?>
                
                    <?php 
                        if ($prev!= $data['gpg_employee_id']) {
                            $curFlag=0;
                     ?>
                     <tr>
                        <td data-title="#ID:" align="center" ><strong>&nbsp;{{ $data['gpg_employee_id'] }}</strong></td>
                        <td data-title="Employee Name:" height="25" colspan="5" style="text-align:left" ><strong>&nbsp;{{$data['emp_name']}}</strong><strong>&nbsp;</strong></td>
                     </tr>

                    
                      <?php }?>
                    
                     <tr>
                        <td data-title="#ID:" align="center" >&nbsp;<?php echo $data['id'] ?></td>
                        <td data-title="//" style="text-align:left">&nbsp;//</td>
                        <td data-title="Burden">&nbsp;
                        <?php echo $_DefaultCurrency.number_format($data['burden'],2);  ?><?php if ($data['cur']=='current' && $curFlag==0)  echo "&nbsp;<strong>[Current]</strong>" ?></td>
                        <td data-title="Start Date">&nbsp;
                            <?php  if (!empty($data['start_date'])) echo date("m/d/Y",strtotime($data['start_date'])); 
                                ?></td>
                        <td align="center" nowrap="nowrap">
                            <a href="{{URL::route('employees/deleteBurden', array('id'=>$data['id']))}}" onclick="javascript:return(confirm('Are you source, you want to DELETE this?'))">Delete</a>
                        </td>
                    </tr>
                
                <?php 
                
                if ($data['cur']=='current') $curFlag =1;
		  $prev = $data['gpg_employee_id'];
                  
                }?>
               
                    </tbody>
                </table>
                {{ $query_data->links() }}
              </section>
              </div>
              </div>
      
        <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
        <script src="{{asset('js/common-scripts.js')}}"></script>
<script type="text/javascript">

    $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
    });
 $("section[mysection=hide_n_show]").hide();
        
        $('#togglerButton').click(function(){
           $("section[mysection=hide_n_show]").toggle("slow");
           if ($('#toggle_div_plus').attr("class") == "fa fa-plus")
              $('#toggle_div_plus').removeClass('fa fa-plus').addClass('fa fa-minus');
           else 
              $('#toggle_div_plus').removeClass('fa fa-minus').addClass('fa fa-plus');
        }); 
</script>
@stop