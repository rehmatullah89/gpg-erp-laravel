<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>Global Power Group</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-reset.css')}}" rel="stylesheet">
    <!--external css-->
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link href="{{asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-datepicker/css/datepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-timepicker/compiled/timepicker.css') }}" />
    <!--<link href="css/navbar-fixed-top.css" rel="stylesheet">-->

    <!-- Custom styles for this template -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/style-responsive.css')}}" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{ asset('js/jquery-ui-1.9.2.custom.min.js') }}"></script>
    <style>
div#change_width{
      width: 900px;
}
@media (max-width: 800px){
  div#change_width{
      width: 100%;
    }
}
.ui-autocomplete {
    width: 300px;
    margin-top:-15000px;
    background-color:red;
}  
.min_width{
  min-width:100px;
}
</style>
  </head>
  <body class="full-width">

  <section id="container" class="">
      <!--header start-->
      <header class="header white-bg">
          <div class="navbar-header" style="display:inline;">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                  <span class="fa fa-bars"></span>
              </button>

              <!--logo start-->
               <div class="col-lg-6">
                  <section class="panel">
                  {{ HTML::image(asset('img/gpglogo.jpg'), 'GPG Logo', array('style' => 'display:inline; width:100px; height:70px;')) }}
                    {{Form::select('copyElectricalQuoteId',$list_quotes,$job_id, ['id'=>'copyElectricalQuoteId','class'=>'form-control m-bot15','style'=>'display:inline;'])}}{{ Form::checkbox('is_child','1','', array('id'=>'is_child','class' => 'input-group','style'=>'display:inline;')) }}&nbsp;&nbsp;Create a new child {{Form::button('Copy Quote Data',array('id'=>'copy_quote_data_confirm','style'=>'display:inline;','class'=>'btn btn-default'))}} 
                    </section>
                </div>
                <div class="col-lg-3">
                  <section class="panel">
                  <br/><br/><br/>
                  <label>{{ Form::radio('jobElecQuoteFrm', 'M', (Input::old('jobElecQuoteFrm') == 'M'), array('id'=>'jobElecQuoteFrm', 'class'=>'radio','style'=>'display:inline;')) }}Switch to Job Electrical Quote Form</label><br/>
                  <label>{{ Form::radio('jobElecQuoteFrm', 'F', (Input::old('jobElecQuoteFrm') == 'F'), array('id'=>'jobElecQuoteFrm2', 'class'=>'radio','style'=>'display:inline;','checked'))}}Switch to Job Electrical Sub Quote Form</label><br/>
                  <label>{{ Form::radio('jobElecQuoteFrm', 'S', (Input::old('jobElecQuoteFrm') == 'S'), array('id'=>'jobElecQuoteFrm3', 'class'=>'radio','style'=>'display:inline;')) }}Switch to Electrical Equipment Pricing Form</label>
                  </section>
                </div>
              <!--logo end-->
            <div class="col-lg-3">
              <div class="top-nav" style="display:inline;">
              <ul class="nav pull-right top-menu">
                <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <img alt="" src="{{asset('img/avatar1_small.jpg')}}">
                          <span class="username"><?php echo Auth::user()->fname." ".Auth::user()->lname?></span>
                          <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu extended logout">
                          <div class="log-arrow-up"></div>
                          <li><a href="logout"><i class="fa fa-key"></i> Log Out</a></li>
                      </ul>
                  </li>
            </ul>
          </div>
        </div>  
          </div>

      </header>
      <!--header end-->
      <!--sidebar start-->

      <!--sidebar end-->
      <!--main content start-->
     <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
       {{ Form::open(array('method' => 'POST','id'=>'update_elec_subquote_frm','files'=>true,'route' => array('quote/updateElectricSubQuoteFrm')))}} 
     <section id="main-content">
      <section id="wrapper">
         <section class="panel">
          <div class="panel-body">
              <!-- page start-->
            {{ Form::hidden('job_id',$job_id)}}
            <div class="row">
            <div class="col-lg-12">
              <section id="no-more-tables">
                  <table class="table table-bordered table-striped table-condensed cf">
                    <tbody class="cf">
                  <tr>
                    <td data-title="Job Number:">Electrical Qt #: {{ Form::text('job_num',$job_num, array('class' => 'form-control', 'id' => 'job_num','readOnly')) }}</td>
                   <td data-title="Go to:">Go to:{{ Form::select('quote_id',$quote_ids_arr,$job_id, array('class' => 'form-control','id' => 'quote_id')) }}</td>  
                   <td data-title="Po Number:">Po Number:{{ Form::text('_po_number',$jobElectricalQuoteTblRow['po_number'], array('class' => 'form-control','id' => '_po_number')) }}</td> 
                   <td data-title="Status:">Status:<br/><span style="color:red; font-weight:bold;"> {{$jobElectricalQuoteTblRow['electrical_status']}}</span></td>     
                  <td data-title="Date:">Date:{{ Form::text('scheduleDate',($jobElectricalQuoteTblRow['schedule_date']!=""?date('Y-m-d',strtotime($jobElectricalQuoteTblRow['schedule_date'])):date('Y-m-d')), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'scheduleDate')) }}</td>
                  <td data-title="Time:">Time: <div class="input-group bootstrap-timepicker">{{ Form::text('schedule_time',$jobElectricalQuoteTblRow['schedule_time'], array('class' => 'form-control timepicker-default','id' => 'schedule_time')) }}  <span class="input-group-btn">{{Form::button('<i class="fa fa-clock-o"></i>', array('class' => 'btn btn-default'))}}</span></div></td>
                  </tr>
                  <tr>
                    <td data-title="Stage:">Stage:{{Form::select('_electrical_qote_stage_id',$gpg_settings,$jobElectricalQuoteTblRow["electrical_qote_stage_id"], ['id' => '_electrical_qote_stage_id', 'class'=>'form-control m-bot15'])}}</td>
                    <td data-title="Job Type:">Job Type:{{Form::select('_elec_quote_type',$elecJobTypeArray,$jobElectricalQuoteTblRow["elec_quote_type"], ['id' => '_elec_quote_type', 'class'=>'form-control m-bot15'])}}</td>
                    <td data-title="Prob.%:">Prob.%:{{ Form::text('_probability',($jobElectricalQuoteTblRow['probability'])?$jobElectricalQuoteTblRow['probability']:'', array('class' => 'form-control','id' => '_probability')) }}</td>
                    <td data-title="Electrical Qt. Status:">Electrical Qt. Status:{{ Form::text('_elec_quote_status',$jobElectricalQuoteTblRow['elec_quote_status'], array('class' => 'form-control','id' => '_elec_quote_status')) }}</td>
                    <td data-title="Est. Close Date:" colspan="2">Est. Close Date:{{ Form::text('_estimated_close_date',($jobElectricalQuoteTblRow['estimated_close_date']!=""?date('Y-m-d',strtotime($jobElectricalQuoteTblRow['estimated_close_date'])):''), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => '_estimated_close_date')) }}</td>
                  </tr>
                    </tbody>
                   </table>
                </section>
              </div>  
              </div>
              </div>
              </section>
               <div class="row"  id="show_hide_billing_info">
                  <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                  <!--#1 -->            <tr><td style="background-color:#FFFFCC;">Customer Name:</td><td>{{Form::select('_GPG_customer_id', $jobElectricalQuoteTblRow['customer_drop_down'],$jobElectricalQuoteTblRow['GPG_customer_id'] , ['class'=>'form-control','id'=>'_GPG_customer_id'])}}</td><td colspan="2"><b>Project Info</b></td><td colspan="2"><b>Additional Costs</b></td><td colspan="2"><b>Sale Summary Info</b></td><td colspan="2"><b>SDGE Rebate Summary</b></td><td colspan="2"><b>Totals Summary</b></td></tr>

                  <!--#2 -->            <tr><td style="background-color:#FFFFCC;">Address 1:</td><td>{{ Form::text('cusAddress1',$jobElectricalQuoteTblRow['customer_info']['address'], array('class' => 'form-control', 'id' => 'cusAddress1')) }}</td><td style="background-color:#FFFFCC;">Project Name:</td><td>{{Form::text('_project_name', $jobElectricalQuoteTblRow['project_name'] , ['class'=>'form-control','id'=>'_project_name'])}}</td><td style="background-color:#FFFFCC;">other mat- qu dis & wags:</td><td>{{Form::text('txt_wages',$jobElectricalQuoteTblRow['other_wages'], ['class'=>'form-control','id'=>'txt_wages','onkeyup'=>"set_total(1)"])}}</td><td style="background-color:#FFFFCC;">Listed Material Cost:</td><td>{{Form::text('txt_sum_line_total','', ['class'=>'form-control','id'=>'txt_sum_line_total','readOnly'])}}</td><td style="background-color:#FFFFCC;">Incentive Total:</td><td>{{Form::text('txt_incentive_total','', ['class'=>'form-control','id'=>'txt_incentive_total','readOnly'])}}</td><td style="background-color:#FFFFCC;">Total Project Cost:</td><td>{{Form::text('txt_total_project_cost','', ['class'=>'form-control','id'=>'txt_total_project_cost','readOnly'])}}</td></tr>

                  <!--#3 -->            <tr><td style="background-color:#FFFFCC;">Address 2:</td><td>{{ Form::text('cusAddress2',$jobElectricalQuoteTblRow['customer_info']['address2'], array('class' => 'form-control', 'id' => 'cusAddress2')) }}</td><td style="background-color:#FFFFCC;">Address:</td><td>{{ Form::text('_project_address',$jobElectricalQuoteTblRow['project_address'], array('class' => 'form-control', 'id' => '_project_address')) }}</td><td style="background-color:#FFFFCC;">Disposal:</td><td>{{Form::text('txt_disposal',$jobElectricalQuoteTblRow['disposal'], ['class'=>'form-control','id'=>'txt_disposal','onkeyup'=>"set_total(1)"])}}</td><td style="background-color:#FFFFCC;">Material Margin:</td><td>{{Form::text('txt_material_margin','', ['class'=>'form-control','id'=>'txt_material_margin','readOnly'])}}</td><td style="background-color:#FFFFCC;">Incentive Rate / Kw Saved:</td><td>{{Form::text('txt_incentive_rate_per_kw','', ['class'=>'form-control','id'=>'txt_incentive_rate_per_kw','readOnly'])}}</td><td style="background-color:#FFFFCC;">Annual Energy Saving:</td><td>{{Form::text('sum_annual_energy_bill_saving','', ['class'=>'form-control','id'=>'sum_annual_energy_bill_saving','readOnly'])}}</td></tr>

                  <!--#4 -->            <tr><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('cusCity',$jobElectricalQuoteTblRow['customer_info']['city'], array('class' => 'form-control', 'id' => 'cusCity')) }}</td><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('_project_city',$jobElectricalQuoteTblRow['project_city'], array('class' => 'form-control', 'id' => '_project_city')) }}</td><td style="background-color:#FFFFCC;">Clean Up:</td><td>{{Form::text('txt_cleanup',$jobElectricalQuoteTblRow['clean_up'], ['class'=>'form-control','id'=>'txt_cleanup','onkeyup'=>"set_total(1)"])}}</td><td style="background-color:#FFFFCC;">Sale Price of Materials:</td><td>{{Form::text('txt_sale_price_material','', ['class'=>'form-control','id'=>'txt_sale_price_material','readOnly'])}}</td><td style="background-color:#FFFFCC;">SPC On-Peak Demand Reduction Incentive:</td><td>{{Form::text('txt_reduction_incentive','', ['class'=>'form-control','id'=>'txt_reduction_incentive','readOnly'])}}</td><td style="background-color:#FFFFCC;">Estimated Utility Incentive:</td><td>{{Form::text('estimated_utility_incentive','', ['class'=>'form-control','id'=>'estimated_utility_incentive','readOnly'])}}</td></tr>

                  <!--#5 -->            <tr><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('cusState',$jobElectricalQuoteTblRow['customer_info']['state'], array('class' => 'form-control', 'id' => 'cusState')) }}</td><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('_project_state',$jobElectricalQuoteTblRow['project_state'], array('class' => 'form-control', 'id' => '_project_state')) }}</td><td style="background-color:#FFFFCC;">Lift Rental:</td><td>{{Form::text('txt_lift_rental',$jobElectricalQuoteTblRow['lift_rental'], ['class'=>'form-control','id'=>'txt_lift_rental','onkeyup'=>"set_total(1)"])}}</td><td style="background-color:#FFFFCC;">Additional Costs:</td><td>{{Form::text('txt_additional_cost','', ['class'=>'form-control','id'=>'txt_additional_cost','readOnly'])}}</td><td style="background-color:#FFFFCC;">Qualifying kW Reduction <input type="text" name="reduction_constant" id="reduction_constant" value="<?php echo !empty($jobElectricalQuoteTblRow['reduction_constant'])?$jobElectricalQuoteTblRow['reduction_constant']:'0';?>" onKeyUp="set_total(1)" style="width:30px;"/>:</td><td>{{Form::text('txt_qualifying_kw_reduction','', ['class'=>'form-control','id'=>'txt_qualifying_kw_reduction','readOnly'])}}</td><td style="background-color:#FFFFCC;">Amount to be paid through OBF:</td><td>{{Form::text('amount_paid_obf','', ['class'=>'form-control','id'=>'amount_paid_obf','readOnly'])}}</td></tr>

                  <!--#6 -->            <tr><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('cusZip',$jobElectricalQuoteTblRow['customer_info']['zipcode'], array('class' => 'form-control', 'id' => 'cusZip')) }}</td><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('_project_zip',$jobElectricalQuoteTblRow['project_zip'], array('class' => 'form-control', 'id' => '_project_zip')) }}</td><td style="background-color:#FFFFCC;">Total:</td><td>{{Form::text('txt_total','', ['class'=>'form-control','id'=>'txt_total','readOnly'])}}</td><td style="background-color:#FFFFCC;">Sale Price Labour:</td><td>{{Form::text('txt_sum_line_labor','', ['class'=>'form-control','id'=>'txt_sum_line_labor','readOnly'])}}</td><td style="background-color:#FFFFCC;">Rate/ kw:</td><td>{{Form::text('txt_rate_per_kw',$jobElectricalQuoteTblRow['rate_per_kw'], ['class'=>'form-control','id'=>'txt_rate_per_kw','onkeyup'=>'set_total(1)'])}}</td><td style="background-color:#FFFFCC;">Project Payback in Months:</td><td>{{Form::text('payback_in_months','', ['class'=>'form-control','id'=>'payback_in_months','readOnly'])}}</td></tr>

                  <!--#7 -->            <tr><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('cusPhone',$jobElectricalQuoteTblRow['customer_info']['phone_no'], array('class' => 'form-control', 'id' => 'cusPhone')) }}</td><td style="background-color:#FFFFCC;">Contact:</td><td>{{ Form::text('_project_contact',$jobElectricalQuoteTblRow['project_contact'], array('class' => 'form-control', 'id' => '_project_contact'))}}</td><td colspan="2">&nbsp;</td><td style="background-color:#FFFFCC;">Applicable Sales Tax <input type="text" name="txt_sales_tax" id="txt_sales_tax" value="<?php echo !empty($jobElectricalQuoteTblRow['sales_tax'])?$jobElectricalQuoteTblRow['sales_tax']:'';?>" onKeyUp="calculate_tax(this.value)" style="width:30px;"/>:</td><td>{{Form::text('txt_applicable_sales_tax','', ['class'=>'form-control','id'=>'txt_applicable_sales_tax','readOnly'])}}</td><td style="background-color:#FFFFCC;">Rebate Total:</td><td>{{Form::text('txt_rebate_total','', ['class'=>'form-control','id'=>'txt_rebate_total','readOnly'])}}</td><td style="background-color:#FFFFCC;">Project Payback in Years:</td><td>{{Form::text('payback_in_years','', ['class'=>'form-control','id'=>'payback_in_years','readOnly'])}}</td></tr>

                  <!--#8 -->            <tr><td style="background-color:#FFFFCC;">Sales Person:</td><td>{{Form::select('_GPG_employee_id',$jobElectricalQuoteTblRow['salesPerson_drop_down'],$jobElectricalQuoteTblRow['GPG_customer_id'], ['class'=>'form-control','id'=>'_GPG_employee_id'])}}</td><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('_project_phone',$jobElectricalQuoteTblRow['project_phone'], array('class' => 'form-control', 'id' => '_project_phone')) }}</td><td colspan="2">&nbsp;</td><td style="background-color:#FFFFCC;">Sale Price:</td><td>{{Form::text('txt_sale_price_total','', ['class'=>'form-control','id'=>'txt_sale_price_total','readOnly'])}}</td><td style="background-color:#FFFFCC;">Total SDGE Incentive with OBF <input type="text" name="incentive_obf" id="incentive_obf" value="<?php echo empty($jobElectricalQuoteTblRow['incentive_obf'])?'':$jobElectricalQuoteTblRow['incentive_obf'];?>" onKeyUp="set_total(1)" style="width:30px;"/>:</td><td>{{Form::text('sdge_incentive_obf','', ['class'=>'form-control','id'=>'sdge_incentive_obf','readOnly'])}}</td><td style="background-color:#FFFFCC;">Energy Reduction:</td><td>{{Form::text('txt_energy_reduction','', ['class'=>'form-control','id'=>'txt_energy_reduction','readOnly'])}}</td></tr>

                  <!--#9 -->            <tr><td style="background-color:#FFFFCC;">Estimator:</td><td>{{Form::select('_GPG_estimator_id',$jobElectricalQuoteTblRow['estimator_drop_down'],$jobElectricalQuoteTblRow['GPG_estimator_id'], ['class'=>'form-control','id'=>'_GPG_estimator_id'])}}</td><td colspan="2">[{{Form::button('COPY CUSTOMER DATA', array('onClick'=>'autoFill();','class' => 'btn btn-link btn-xs'))}}]</td><td style="background-color:#FFFFCC;">Employee Assigned</td><td>{{Form::select('employee_assigned', $jobElectricalQuoteTblRow['salesPerson_drop_down'],$jobElectricalQuoteTblRow['gpg_employee_assigned'] , ['class'=>'form-control','id'=>'employee_assigned'])}}</td><td colspan="2">&nbsp;</td><td style="background-color:#FFFFCC;">Average House of Operations (AHO):</td><td>{{Form::text('aho',$jobElectricalQuoteTblRow['aho'], ['class'=>'form-control','id'=>'aho','onkeyup'=>'set_total(1)'])}}</td><td style="background-color:#FFFFCC;">Five Year Energy Savings:</td><td>{{Form::text('year_energy_saving','', ['class'=>'form-control','id'=>'year_energy_saving','readOnly'])}}</td></tr>

                  <!--#10 -->            <tr><td colspan="12"><b> Custom Fields</b></td></tr>
                  <!--#11 -->            <tr><td  style="background-color:#FFFFCC;">Reservation Number:</td><td>{{ Form::text('reservation_number',$jobElectricalQuoteTblRow['reservation_number'], array('onkeyup'=>"set_total(1)",'class' => 'form-control', 'id' => 'reservation_number')) }}</td><td  style="background-color:#FFFFCC;">Tax ID number:</td><td>{{ Form::text('tax_id',$jobElectricalQuoteTblRow['tax_id'], array('onkeyup'=>"set_total(1)",'class' => 'form-control', 'id' => 'tax_id')) }}</td><td  style="background-color:#FFFFCC;">Tax Status:</td><td>{{ Form::text('tax_status',$jobElectricalQuoteTblRow['tax_status'], array('onkeyup'=>"set_total(1)",'class' => 'form-control', 'id' => 'tax_status')) }}</td><td  style="background-color:#FFFFCC;">Building Square footage:</td><td>{{ Form::text('building_square',$jobElectricalQuoteTblRow['building_square'], array('onkeyup'=>"set_total(1)",'class' => 'form-control', 'id' => 'building_square')) }}</td><td  style="background-color:#FFFFCC;">Average House of Operations (AHO)::</td><td>{{ Form::text('aho',$jobElectricalQuoteTblRow['aho'], array('onkeyup'=>"set_total(1)",'class' => 'form-control', 'id' => 'aho')) }}</td><td colspan="2"></td></tr>
                                      </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>        
                          </div>
                        </div>
                      </section>
                    </div>
                  </div>
                <div class="row">
                  <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables" style="overflow-x: scroll;">
                                     <table class="table table-bordered table-striped table-condensed cf" id="myTable">
                                        <thead class="cf">
                                           <tr bgcolor="#F2F2F2" >
                                            <th rowspan="2">{{Form::button('<i class="fa fa-plus-circle"></i>', array('class' => 'btn btn-success btn-xs','name'=>'add_another_row'))}}</th>
                                            <th rowspan="2">Location</th>
                                            <th colspan="2">Fixture Information and Product Numbers</th>
                                            <th rowspan="2">Notes</th>
                                            <th rowspan="2">Pro. Fix. Document</th>
                                            <th colspan="2">Lamps per Fixture</th>
                                            <th colspan="2">Fixture Quantity</th>
                                            <th colspan="2">Fixture Watts</th>
                                            <th colspan="2">Each Fixture kW</th>
                                            <th colspan="2">Measure Annual kWh</th>
                                            <th >Annual kWh Savings</th>
                                            <th colspan="2">Annual Hours of Operation</th>
                                            <th colspan="2">Annual Energy Multiplier:<input type="text" name="new_annual_energy_cost" id="new_annual_energy_cost" value="<? echo ($Constants['annual_energy_cost']!= NULL)?$Constants['annual_energy_cost']:'';?>" class="textRed" style="background-color:#FFFFFF;width:30px;" readonly="readonly"/><br/>Annual Energy Cost</th>
                                            <th rowspan="2">Annual Energy Bill**&nbsp;Saving&nbsp;</th>
                                            <th rowspan="2">Unit Fixture&nbsp;Cost&nbsp;</th>
                                            <th rowspan="2">Line Total Cost</th>
                                            <th rowspan="2">Material Mark Up:<input type="text" name="new_material_mark_up" id="new_material_mark_up" size="5" value="<? echo ($Constants['material_mark_up']!= NULL)?$Constants['material_mark_up']:'';?>" class="textRed" style="background-color:#FFFFFF;width:30px" readonly="readonly"/><br />Customer InvoiceMaterial Cost</th>
                                            <th rowspan="2">Labor Hours Multiplier:<input type="text" name="new_labor_hours_multiplier" id="new_labor_hours_multiplier" size="5"  value="<? echo ($Constants['labor_hours_multiplier']!= NULL)?$Constants['labor_hours_multiplier']:'';?>" class="textRed" style="background-color:#FFFFFF;width:30px" readonly="readonly"/><br />UnitLabor Hours</th>
                                            <th rowspan="2">Total LineLabor&nbsp;Hours&nbsp;</th>
                                            <th rowspan="2">&nbsp;Labor Rate:<input type="text" name="new_labor_rate" id="new_labor_rate" size="5" value="<? echo ($Constants['labor_rate']!= NULL)?$Constants['labor_rate']:'';?>" class="textRed" style="background-color:#FFFFFF;width:30px" readonly="readonly"/><br />Line Labor&nbsp;</th>
                                            <th colspan="4">Rebate Calculataions</th>
                                            <th colspan="4">Rebate Calculations</th>
                                            <th colspan="4">Rebate Calculations</th>
                                            <th colspan="4">Rebate Calculations</th>
                                            <th colspan="2">Incentive Rate / kWh Saved:<input type="text" name="new_incentive_rate" id="new_incentive_rate" size="5" value="<? echo ($Constants['incentive_rate']!= NULL)?$Constants['incentive_rate']:'';?>" class="textRed" style="background-color:#FFFFFF;width:30px" readonly="readonly"/><br/>Incentive Calculations</th>
                                          </tr>
                                          <tr bgcolor="#F2F2F2">
                                            <th>EXISTING</th>
                                            <th>PROPOSED</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <td>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Per Unit Incentive</th>
                                            <th>Line Item Incentive</th>
                                          </tr>  
                                        </thead>
                                        <tbody class="cf">
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td data-title="Location:" class="min_width">{{Form::text('new_location_1','', ['class'=>'form-control','id'=>'new_location_1'])}}</td>
                                            <td data-title="Ex Fix:" class="min_width">{{Form::select('new_ExFix_1',$existing_arr,'', ['id'=>'new_ExFix_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="pro Fix:" class="min_width">{{Form::select('new_ProFix_1',$proposed_arr,'', ['id'=>'new_ProFix_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="Note:" class="min_width">{{Form::text('new_notes_1','', ['class'=>'form-control','id'=>'new_notes_1'])}}</td>
                                            <td data-title="Lamp Fix:" class="min_width">{{Form::text('doc_view_1','', ['class'=>'form-control','id'=>'doc_view_1','readOnly'])}}</td>
                                            <td data-title="Lamp Fix Quanity:" class="min_width">{{Form::text('new_lamps_fixture_quantity_ex_1','', ['id'=>'new_lamps_fixture_quantity_ex_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="Lamp FIx Qnty Pro:" class="min_width">{{Form::text('new_lamps_fixture_quantity_pro_1','', ['id'=>'new_lamps_fixture_quantity_pro_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="Fix Quantity:" class="min_width">{{Form::text('new_fixture_quantity_ex_1','', ['id'=>'new_fixture_quantity_ex_1','class'=>'form-control m-bot15','onkeyup'=>'RowValueChange(1,1)'])}}</td>
                                            <td data-title="Fixt Qunatity Pro:" class="min_width">{{Form::text('new_fixture_quantity_pro_1','', ['id'=>'new_fixture_quantity_pro_1','class'=>'form-control m-bot15','onkeyup'=>'RowValueChange(1,1)'])}}</td>
                                            <td data-title="Fixt Watt:" class="min_width">{{Form::text('new_fixture_watts_ex_1','', ['id'=>'new_fixture_watts_ex_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Fixt Watt pro:" class="min_width">{{Form::text('new_fixture_watts_pro_1','', ['id'=>'new_fixture_watts_pro_1','class'=>'form-control m-bot15','readOnly'])}}</td>  
                                            <td data-title="Fixt Kwx:" class="min_width">{{Form::text('new_each_fixture_kw_ex_1','', ['id'=>'new_each_fixture_kw_ex_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Fixt Kw pro:" class="min_width">{{Form::text('new_each_fixture_kw_pro_1','', ['id'=>'new_each_fixture_kw_pro_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Annual Fixt kw:" class="min_width">{{Form::text('new_measure_annual_kwh_ex_1','', ['id'=>'new_measure_annual_kwh_ex_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Annual Fixt kw pro:" class="min_width">{{Form::text('new_measure_annual_kwh_pro_1','', ['id'=>'new_measure_annual_kwh_pro_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Annual kw saving:" class="min_width">{{Form::text('new_annual_kwh_savings_pro_1','', ['id'=>'new_annual_kwh_savings_pro_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Annual hour x:" class="min_width">{{Form::text('new_annual_hours_of_operation_ex_1','', ['id'=>'new_annual_hours_of_operation_ex_1','class'=>'form-control m-bot15','onkeyup'=>'RowValueChange(1,1)'])}}</td>
                                            <td data-title="Annual hour pro:" class="min_width">{{Form::text('new_annual_hours_of_operation_pro_1','', ['id'=>'new_annual_hours_of_operation_pro_1','class'=>'form-control m-bot15','onkeyup'=>'RowValueChange(1,1)'])}}</td>
                                            <td data-title="Annual Energy Cost x:" class="min_width">{{Form::text('new_annual_energy_cost_ex_1','', ['id'=>'new_annual_energy_cost_ex_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Annual Energy Cost pro" class="min_width">{{Form::text('new_annual_energy_cost_pro_1','', ['id'=>'new_annual_energy_cost_pro_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Energy Bill x:" class="min_width">{{Form::text('new_annual_energy_bill_saving_1','', ['id'=>'new_annual_energy_bill_saving_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Unit fixt:" class="min_width">{{Form::text('new_unit_fixture_cost_1','', ['id'=>'new_unit_fixture_cost_1','class'=>'form-control m-bot15','onkeyup'=>'RowValueChange(1,1)'])}}</td>
                                            <td data-title="Line tcost:" class="min_width">{{Form::text('new_line_total_cost_1','', ['id'=>'new_line_total_cost_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Material Cost:" class="min_width">{{Form::text('new_material_cost_1','', ['id'=>'new_material_cost_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Unit Labor hr:" class="min_width">{{Form::text('new_unit_labor_hour_1','', ['id'=>'new_unit_labor_hour_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="TLine labor hr:" class="min_width">{{Form::text('new_total_line_labor_hour_1','', ['id'=>'new_total_line_labor_hour_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Line Lbr:" class="min_width">{{Form::text('new_line_labor_1','', ['id'=>'new_line_labor_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Lbr Qntity:" class="min_width">{{Form::text('new_rebate_quantity1_1','', ['id'=>'new_rebate_quantity1_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="Rebate Measure:" class="min_width">{{Form::select('new_rebate_measure1_1',$proposed_arr,'', ['id'=>'new_rebate_measure1_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="Rebate Amt:" class="min_width">{{Form::text('new_rebate_amount1_1','', ['id'=>'new_rebate_amount1_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Rebate sub total:" class="min_width">{{Form::text('new_rebate_subtotal1_1','', ['id'=>'new_rebate_subtotal1_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Rebate Qntity2:" class="min_width">{{Form::text('new_rebate_quantity2_1','', ['id'=>'new_rebate_quantity2_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="Rebate Measure2" class="min_width">{{Form::select('new_rebate_measure2_1',$proposed_arr,'', ['id'=>'new_rebate_measure2_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="Rebate Amt2" class="min_width">{{Form::text('new_rebate_amount2_1','', ['id'=>'new_rebate_amount2_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Rebate sub total2:" class="min_width">{{Form::text('new_rebate_subtotal2_1','', ['id'=>'new_rebate_subtotal2_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Rebate Qntity3:" class="min_width">{{Form::text('new_rebate_quantity3_1','', ['id'=>'new_rebate_quantity3_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="Rebate Measure3:" class="min_width">{{Form::select('new_rebate_measure3_1',$proposed_arr,'', ['id'=>'new_rebate_measure3_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="Rebate Amt3:" class="min_width">{{Form::text('new_rebate_amount3_1','', ['id'=>'new_rebate_amount3_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Rebate sub total3:" class="min_width">{{Form::text('new_rebate_subtotal3_1','', ['id'=>'new_rebate_subtotal3_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Rebate Qntity4:" class="min_width">{{Form::text('new_rebate_quantity4_1','', ['id'=>'new_rebate_quantity4_1','class'=>'form-control m-bot15','onKeyUp'=>'setRebateSubtotal(this,1,"new_");'])}}</td>
                                            <td data-title="Rebate Measure4:" class="min_width">{{Form::select('new_rebate_measure4_1',$proposed_arr,'', ['id'=>'new_rebate_measure4_1','class'=>'form-control m-bot15'])}}</td>
                                            <td data-title="Rebate Amt4:" class="min_width">{{Form::text('new_rebate_amount4_1','', ['id'=>'new_rebate_amount4_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="Rebate sub total4:" class="min_width">{{Form::text('new_rebate_subtotal4_1','', ['id'=>'new_rebate_subtotal4_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="unit/incentive:" class="min_width">{{Form::text('new_per_unit_incentive_1','', ['id'=>'new_per_unit_incentive_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <td data-title="item/incentive:" class="min_width">{{Form::text('new_line_item_incentive_1','', ['id'=>'new_line_item_incentive_1','class'=>'form-control m-bot15','readOnly'])}}</td>
                                            <input type="hidden" value="1" name="xpCounter_id" id="xpCounter_id">
                                          </tr>
                                        </tbody>
                                     </table>
                                   </section>
                                 </div>
                              </div>
                        </div>
                    </div>
                  </section>
                </div>
              </div>
              <div class="row">
                  <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables" style="overflow-x: scroll;">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <thead class="cf">
                                          <tr>
                                            <th rowspan="2">&nbsp;DEL&nbsp;</th>
                                            <th rowspan="2">&nbsp;&nbsp;Edit&nbsp;&nbsp;</th>
                                            <th rowspan="2">Location</th>
                                            <th colspan="2">&nbsp;Fixture Information and Product Numbers&nbsp;</th>
                                            <th rowspan="2">&nbsp;Notes&nbsp;</th> 
                                            <th rowspan="2">&nbsp;Pro. Fix.<br />Document&nbsp;</th> 
                                            <th colspan="2">&nbsp;Lamps per Fixture&nbsp;</th>
                                            <th colspan="2">&nbsp;Fixture Quantity&nbsp;</th>
                                            <th rowspan="2">Fixtures Left</th>
                                            <th colspan="2">Fixture Watts</th>
                                            <th colspan="2">Each Fixture kW</th>
                                            <th colspan="2">Measure Annual kWh</th>
                                            <th>Annual kWh Savings</th>
                                            <th colspan="2">Annual Hours of Operation</th>
                                            <th colspan="2">Annual Energy Multiplier:<input type="text" name="annual_energy_cost" id="annual_energy_cost" value="<? echo ($Constants['annual_energy_cost']!= NULL)?$Constants['annual_energy_cost']:'';?>" class="textRed" style="width:30px;" /><br/>Annual Energy Cost **</th>
                                            <th rowspan="2">Annual Energy Bill**&nbsp;Saving&nbsp;</th>
                                            <th rowspan="2">Unit Fixture&nbsp;Cost&nbsp;</th>
                                            <th rowspan="2">Line Total Cost</th>
                                            <th rowspan="2">Material Mark Up:<input type="text" name="material_mark_up" id="material_mark_up" size="5" value="<? echo ($Constants['material_mark_up']!= NULL)?$Constants['material_mark_up']:'';?>" class="textRed" style="width:30px"/><br/>Customer Invoice Material Cost </th>
                                            <th rowspan="2">Labor Hours Multiplier:<input type="text" name="labor_hours_multiplier" id="labor_hours_multiplier" size="5"  value="<? echo ($Constants['labor_hours_multiplier']!= NULL)?$Constants['labor_hours_multiplier']:'';?>" class="textRed" style="width:30px" />Unit Labor Hours</th>
                                            <th rowspan="2">Total LineLabor&nbsp;Hours&nbsp;</td>
                                            <th rowspan="2">&nbsp;Labor Rate:<input type="text" name="labor_rate" id="labor_rate" size="5" value="<? echo ($Constants['labor_rate']!= NULL)?$Constants['labor_rate']:'';?>" class="textRed" style="width:30px" /><br />Line Labor&nbsp;</th>
                                            <th colspan="4">Rebate Calculataions</th>
                                            <th colspan="4">Rebate Calculations</th>
                                            <th colspan="4">Rebate Calculations</th>
                                            <th colspan="4">Rebate Calculations</td>
                                            <th colspan="2">Incentive Rate / kWh Saved:<input type="text" name="incentive_rate" id="incentive_rate" size="5" value="<? echo ($Constants['incentive_rate']!= NULL)?$Constants['incentive_rate']:'';?>" class="textRed" style="width:30px" /><br />Incentive Calculations</th>
                                            <th rowspan="2">Exclude <br />Incentive</th>
                                          </tr>
                                          <tr bgcolor="#F2F2F2">
                                            <th>EXISTING</th>
                                            <th>PROPOSED</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro.&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th> 
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Per Unit Incentive</th>
                                            <th>Line Item Incentive</th>
                                          </tr>   
                                        </thead>
                                        <tbody class="cf">
                                        @if(!empty($getJESQ))
                                          <?php $i=1;?>
                                          @foreach($getJESQ as $getJESQRow)
                                            <?php $i++;?>
                                            <tr>
                                              <td>{{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-xs','name'=>'delete_jesq_row','id'=>$getJESQRow['id']))}}</td>
                                              <td>{{ Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-danger btn-xs','name'=>'edit_jesq_row','id'=>$getJESQRow['id']))}}</td>
                                              <td>{{$getJESQRow['location']}}</td>
                                              <td>{{$getJESQRow['fixture_name']}}</td>
                                              <td>{{$getJESQRow['fixture_name_pro']}}</td>
                                              <td>{{strlen($getJESQRow['notes'])>0?substr($getJESQRow['notes'],0,15)."...":""}}</td>
                                              <td>
                                                  @if(empty($getJESQRow['docs']))
                                                     {{'-'}}
                                                  @else
                                                    {{$getJESQRow['gpg_job_electrical_subquote_proposed_fixtures_id']}}   
                                                  @endif       
                                              </td>
                                              <td>{{$getJESQRow['lamps_fixture_quantity_ex']}}</td>
                                              <td>{{$getJESQRow['lamps_fixture_quantity_pro']}}</td>
                                              <td>{{$getJESQRow['fixture_quantity_ex']}}</td>
                                              <td>{{$getJESQRow['fixture_quantity_pro']}}</td>
                                              <td>
                                              @if(isset($installed_fix_arr[$getJESQRow['id']]))    
                                                {{ number_format(($getJESQRow['fixture_quantity_pro']-$installed_fix_arr[$getJESQRow['id']]),2)}}
                                              @else 
                                                {{$getJESQRow['fixture_quantity_pro']}}
                                              @endif  
                                              </td>
                                              <td><div id="fixture_watts_exDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="fixture_watts_proDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="each_fixture_kw_exDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="each_fixture_kw_proDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="measure_annual_kwh_exDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="measure_annual_kwh_proDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="annual_kwh_savings_proDIV_<?php echo $i; ?>"></div></td>
                                              <td>{{$getJESQRow['annual_hours_of_operation_ex']}}</td>
                                              <td>{{$getJESQRow['annual_hours_of_operation_pro']}}</td>
                                              <td><div id="annual_energy_cost_exDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="annual_energy_cost_proDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="annual_energy_bill_savingDIV_<?php echo $i; ?>"></div></td>
                                              <td>{{$getJESQRow['unit_fixture_cost']}}</td>
                                              <td><div id="line_total_costDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="material_costDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="unit_labor_hourDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="total_line_labor_hourDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="line_laborDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_quantity1DIV_<?php echo $i; ?>"></div></td>
                                              <td>
                                              @if(!empty($getJESQRow['rebate1Detail']))
                                                {{$getJESQRow['rebate1Detail']['rebate_measure'].'/'.$getJESQRow['rebate1Detail']['rebate_start_year']}}
                                              @endif
                                              </td>
                                              <td><div id="rebate_amount1DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_subtotal1DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_quantity2DIV_<?php echo $i; ?>"></div></td>
                                              <td>
                                              @if(!empty($getJESQRow['rebate2Detail']))
                                               {{$getJESQRow['rebate2Detail']['rebate_measure'].'/'.$getJESQRow['rebate2Detail']['rebate_start_year']}}
                                              @endif
                                              </td>
                                              <td><div id="rebate_amount2DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_subtotal2DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_quantity3DIV_<?php echo $i; ?>"></div></td>
                                              <td>
                                              @if(!empty($getJESQRow['rebate3Detail']))
                                               {{$getJESQRow['rebate3Detail']['rebate_measure'].'/'.$getJESQRow['rebate3Detail']['rebate_start_year']}}
                                              @endif
                                              </td>
                                              <td><div id="rebate_amount3DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_subtotal3DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_quantity4DIV_<?php echo $i; ?>"></div></td>
                                              <td>
                                              @if(!empty($getJESQRow['rebate4Detail']))
                                               {{$getJESQRow['rebate4Detail']['rebate_measure'].'/'.$getJESQRow['rebate4Detail']['rebate_start_year']}}
                                              @endif
                                              </td>
                                              <td><div id="rebate_amount4DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_subtotal4DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="per_unit_incentiveDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="line_item_incentiveDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="_<?php echo $i; ?>"></div></td>
                                            </tr>
                                            <tr id="Totals_row" class="sum_totals">
                                              <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                              <td id="lamps_fixture_quantity_exTotal"></td>
                                              <td id="lamps_fixture_quantity_proTotal"></td>
                                              <td id="fixture_quantity_exTotal"></td>
                                              <td id="fixture_quantity_proTotal"></td>
                                              <td></td>
                                              <td></td><td></td><td></td><td></td>
                                              <td id="measure_annual_kwh_exTotal"></td>
                                              <td id="measure_annual_kwh_proTotal"></td>
                                              <td id="annual_kwh_savings_proTotal"></td>
                                              <td></td><td></td>
                                              <td id="annual_energy_cost_exTotal"></td>
                                              <td id="annual_energy_cost_proTotal"></td>
                                              <td id="annual_energy_bill_savingTotal"></td>
                                              <td></td>
                                              <td id="line_total_costTotal"></td>
                                              <td id="material_costTotal"></td>
                                              <td></td>
                                              <td id="total_line_labor_hourTotal"></td>
                                              <td id="line_laborTotal"></td>
                                              <td id="rebate_quantity1Total"></td>
                                              <td></td><td></td>
                                              <td id="rebate_subtotal1Total"></td>
                                              <td id="rebate_quantity2Total"></td>
                                              <td></td><td></td>
                                              <td id="rebate_subtotal2Total"></td>
                                              <td id="rebate_quantity3Total"></td>
                                              <td></td><td></td>
                                              <td id="rebate_subtotal3Total"></td>
                                              <td id="rebate_quantity4Total"></td><td></td><td></td>
                                              <td id="rebate_subtotal4Total"></td>
                                              <td></td>
                                              <td id="line_item_incentiveTotal"></td>
                                              <td></td>
                                            </tr>
                                          @endforeach
                                        @endif
                                        </tbody>
                                     </table>
                                   </section>
                                 </div>
                              </div>
                        </div>
                    </div>
                  </section>
                </div>
              </div>
    </section>
  </section>
  <div class="btn-group" style="padding:20px;">
   {{Form::button('Save/Update Changes', array('class' => 'btn btn-primary', 'id'=>'submit_main_form'))}}
    {{HTML::link("quote/excelSubQuoteFormExport?id=$job_id&j_num=$job_num&frm=1", 'Export Excel' , array('class'=>'btn btn-success'))}}
    {{HTML::link("quote/excelSubQuoteFormExport2?id=$job_id&j_num=$job_num&frm=2", 'Export Excel-2' , array('class'=>'btn btn-warning'))}}
    {{ Form::button('Export PDF' , array('id'=>'getElecticalSubQuotePdfFile','class'=>'btn btn-danger'))}} 
  </div>  
  {{Form::close()}}
</section>
  <!-- js placed at the end of the document so the pages load faster -->
  <script src="{{asset('js/bootstrap.min.js')}}"></script>
  <script class="include" type="text/javascript" src="{{asset('js/jquery.dcjqaccordion.2.7.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/hover-dropdown.js')}}"></script>
  <script src="{{asset('js/jquery.scrollTo.min.js')}}"></script>
  <script src="{{asset('js/jquery.nicescroll.js')}}" type="text/javascript"></script>
  <script src="{{asset('js/respond.min.js')}}" ></script>
  <script src="{{ asset('assets/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
  <script src="{{ asset('assets/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
  <script type="text/javascript" src="{{asset('js/form-component.js')}}"></script>
  <link href="{{asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />
  <link href="{{asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="{{asset('assets/data-tables/DT_bootstrap.css')}}" />
  <script type="text/javascript" language="javascript" src="{{asset('assets/advanced-datatable/media/js/jquery.dataTables.js')}}"></script>
  <script type="text/javascript" src="{{asset('assets/data-tables/DT_bootstrap.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/dynamic_table_init.js') }}"></script>
  <!-- morrsi -->
  <link href="{{asset('assets/morris.js-0.4.3/morris.css')}}" rel="stylesheet" />
  <script src="{{asset('assets/morris.js-0.4.3/raphael-min.js')}}" type="text/javascript"></script>
  <script src="{{asset('js/morris.js')}}" type="text/javascript"></script>
  <!--right slidebar-->
  <script src="{{asset('js/slidebars.min.js')}}"></script>
  <!--common script for all pages-->
  <script src="{{asset('js/common-scripts.js')}}"></script>
   <script src="{{asset('js/job/job_quote_equip_pricing_frm.js')}}"></script>
   <script type="text/javascript">
$("input[name='jobElecQuoteFrm']").change(function(){
    var jid= '<?php echo $job_id;?>';
    var jnum= '<?php echo $job_num;?>';
    var cpage = $(this).attr('id');
    if (cpage == 'jobElecQuoteFrm3')
     window.location.href = '{{URL::to("job/job_electrical_equipment_pricing_frm/'+jid+'/'+jnum+'")}}';
    else if(cpage == 'jobElecQuoteFrm2')
      window.location.href = '{{URL::to("job/job_electrical_subquote_frm/'+jid+'/'+jnum+'")}}';
    else
      window.location.href = '{{URL::to("job/job_electrical_quote_frm/'+jid+'/'+jnum+'")}}';
  });
$('#submit_main_form').click(function(){
  $('#update_elec_subquote_frm').attr('action','{{URL::to("quote/updateElectricSubQuoteFrm")}}').submit();
});
  $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
        });
     $('.timepicker-default').timepicker();

  function autoFill() {
      if (confirm("Job Site Fields will be updated! Do you want to continue?")) {
        document.getElementById("_project_address").value = document.getElementById("cusAddress1").value;
        document.getElementById("_project_city").value = document.getElementById("cusCity").value;
        document.getElementById("_project_state").value = document.getElementById("cusState").value;
        document.getElementById("_project_zip").value = document.getElementById("cusZip").value;
        document.getElementById("_project_phone").value = document.getElementById("cusPhone").value;
      }
  }
    $('#copy_quote_data_confirm').click(function(){
    var conf =confirm("All the data of the Selected Quote will be Copied to this Quote.THIS CHANGE COULD NOT BE Reverted!");
    var checkBoxCheck = $('#is_child').is(':checked');
    if(conf){
        $.ajax({
           url: "{{URL('ajax/CopyQuoteData')}}",
           data: {
            'job_id' : '<?php echo $job_id;?>',
            'job_num' : '<?php echo $job_num;?>',
            'check_box': checkBoxCheck,
            'copy_id' : $("#copyElectricalQuoteId option:selected").val()
           },
            success: function (data) {
           if (data == 1){     
              alert("Copied/Created Successfully!");
            location.reload();
           }
          },
        });
    }
  });
  $('select[name=_GPG_customer_id]').change(function(){
      var cid = $(this).val();
      $.ajax({
        url: "{{URL('ajax/getCustomerInfo')}}",
          data: {
            'cid' : cid
          },
          success: function (data) {
            $('#cusAddress1').val(data.address);    
            $('#cusAddress2').val(data.address2);    
            $('#cusCity').val(data.city);    
            $('#cusState').val(data.state);    
            $('#cusZip').val(data.zipcode);    
            $('#cusPhone').val(data.phone_no);    
          },
        });
  });
var counter_xp = 1;
  $('button[name=add_another_row]').click(function(){
      counter_xp = parseInt(counter_xp) + parseInt('1');
      $('#xpCounter_id').val(counter_xp);
      var str = '<tr><td>&nbsp;</td><td class="min_width"><input data-title="Location:" type="text" value="" name="new_location_'+counter_xp+'" id="new_location_'+counter_xp+'" class="form-control"></td>';
          str+= '<td data-title="Ex Fix:" class="min_width"><select name="new_ExFix_'+counter_xp+'" class="form-control m-bot15" id="new_ExFix_'+counter_xp+'">'+document.getElementById("new_ExFix_1").innerHTML+'</select></td>';
          str+= '<td data-title="pro Fix:" class="min_width"><select name="new_ProFix_'+counter_xp+'" class="form-control m-bot15" id="new_ProFix_'+counter_xp+'">'+document.getElementById("new_ProFix_1").innerHTML+'</td>';
          str+= '<td data-title="Note:" class="min_width"><input type="text" value="" name="new_notes_'+counter_xp+'" id="new_notes_'+counter_xp+'" class="form-control"></td>';                                  
          str+= '<td data-title="Lamp Fix:" class="min_width"><input type="text" value="" name="doc_view_'+counter_xp+'" readonly="readOnly" id="doc_view_'+counter_xp+'" class="form-control"></td>';                                  
          str+= '<td data-title="Lamp Fix Quanity:" class="min_width"><input type="text" value="" name="new_lamps_fixture_quantity_ex_'+counter_xp+'" class="form-control m-bot15" id="new_lamps_fixture_quantity_ex_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Lamp FIx Qnty Pro:" class="min_width"><input type="text" value="" name="new_lamps_fixture_quantity_pro_'+counter_xp+'" class="form-control m-bot15" id="new_lamps_fixture_quantity_pro_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Fix Quantity:" class="min_width"><input type="text" value="" name="new_fixture_quantity_ex_'+counter_xp+'"  class="form-control m-bot15" id="new_fixture_quantity_ex_'+counter_xp+'" onkeyup="RowValueChange('+counter_xp+',1)"></td>';                                  
          str+= '<td data-title="Fixt Qunatity Pro:" class="min_width"><input type="text" value="" name="new_fixture_quantity_pro_'+counter_xp+'"  onkeyup="RowValueChange('+counter_xp+',1)"  class="form-control m-bot15" id="new_fixture_quantity_pro_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Fixt Watt:" class="min_width"><input type="text" value="" name="new_fixture_watts_ex_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_fixture_watts_ex_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Fixt Watt pro:" class="min_width"><input type="text" value="" name="new_fixture_watts_pro_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_fixture_watts_pro_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Fixt Kwx:" class="min_width"><input type="text" value="" name="new_each_fixture_kw_ex_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_each_fixture_kw_ex_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Fixt Kw pro:" class="min_width"><input type="text" value="" name="new_each_fixture_kw_pro_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_each_fixture_kw_pro_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Annual Fixt kw:" class="min_width"><input type="text" value="" name="new_measure_annual_kwh_ex_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_measure_annual_kwh_ex_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Annual Fixt kw pro:" class="min_width"><input type="text" value="" name="new_measure_annual_kwh_pro_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_measure_annual_kwh_pro_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Annual kw saving:" class="min_width"><input type="text" value="" name="new_annual_kwh_savings_pro_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_annual_kwh_savings_pro_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Annual hour x:" class="min_width"><input type="text" value="" name="new_annual_hours_of_operation_ex_'+counter_xp+'"  onkeyup="RowValueChange('+counter_xp+',1)"  class="form-control m-bot15" id="new_annual_hours_of_operation_ex_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Annual hour pro:" class="min_width"><input type="text" value="" name="new_annual_hours_of_operation_pro_'+counter_xp+'"  onkeyup="RowValueChange('+counter_xp+',1)"  class="form-control m-bot15" id="new_annual_hours_of_operation_pro_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Annual Energy Cost x:" class="min_width"><input type="text" value="" name="new_annual_energy_cost_ex_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_annual_energy_cost_ex_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Annual Energy Cost pro" class="min_width"><input type="text" value="" name="new_annual_energy_cost_pro_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_annual_energy_cost_pro_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Energy Bill x:" class="min_width"><input type="text" value="" name="new_annual_energy_bill_saving_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_annual_energy_bill_saving_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Unit fixt:" class="min_width"><input type="text" value="" name="new_unit_fixture_cost_'+counter_xp+'"  onkeyup="RowValueChange('+counter_xp+',1)"  class="form-control m-bot15" id="new_unit_fixture_cost_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Line tcost:" class="min_width"><input type="text" value="" name="new_line_total_cost_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_line_total_cost_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Material Cost:" class="min_width"><input type="text" value="" name="new_material_cost_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_material_cost_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Unit Labor hr:" class="min_width"><input type="text" value="" name="new_unit_labor_hour_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_unit_labor_hour_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="TLine labor hr:" class="min_width"><input type="text" value="" name="new_total_line_labor_hour_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_total_line_labor_hour_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Line Lbr:" class="min_width"><input type="text" value="" name="new_line_labor_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_line_labor_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Lbr Qntity:" class="min_width"><input type="text" value="" name="new_rebate_quantity1_'+counter_xp+'" class="form-control m-bot15" id="new_rebate_quantity1_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate Measure:" class="min_width"><select name="new_rebate_measure1_'+counter_xp+'" class="form-control m-bot15" id="new_rebate_measure1_'+counter_xp+'">'+document.getElementById("new_rebate_measure1_1").innerHTML+'</select></td>';                                  
          str+= '<td data-title="Rebate Amt:" class="min_width"><input type="text" value="" name="new_rebate_amount1_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_rebate_amount1_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate sub total:" class="min_width"><input type="text" value="" name="new_rebate_subtotal1_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_rebate_subtotal1_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate Qntity2:" class="min_width"><input type="text" value="" name="new_rebate_quantity2_'+counter_xp+'"  class="form-control m-bot15" id="new_rebate_quantity2_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate Measure2" class="min_width"><select name="new_rebate_measure2_'+counter_xp+'" class="form-control m-bot15" id="new_rebate_measure2_'+counter_xp+'">'+document.getElementById("new_rebate_measure2_1").innerHTML+'</select></td>';                                  
          str+= '<td data-title="Rebate Amt2" class="min_width"><input type="text" value="" name="new_rebate_amount2_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_rebate_amount2_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate sub total2:" class="min_width"><input type="text" value="" name="new_rebate_subtotal2_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_rebate_subtotal2_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate Qntity3:" class="min_width"><input type="text" value="" name="new_rebate_quantity3_'+counter_xp+'"  class="form-control m-bot15" id="new_rebate_quantity3_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate Measure3:" class="min_width"><select name="new_rebate_measure3_'+counter_xp+'" class="form-control m-bot15" id="new_rebate_measure3_'+counter_xp+'">'+document.getElementById("new_rebate_measure3_1").innerHTML+'</select></td>';                                  
          str+= '<td data-title="Rebate Amt3:" class="min_width"><input type="text" value="" name="new_rebate_amount3_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_rebate_amount3_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate sub total3:" class="min_width"><input type="text" value="" name="new_rebate_subtotal3_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_rebate_subtotal3_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate Qntity4:" class="min_width"><input type="text" value="" name="new_rebate_quantity4_'+counter_xp+'" onkeyup="setRebateSubtotal(this,'+counter_xp+',&quot;new_&quot;);" class="form-control m-bot15" id="new_rebate_quantity4_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate Measure4:" class="min_width"><select name="new_rebate_measure4_'+counter_xp+'" class="form-control m-bot15" id="new_rebate_measure4_'+counter_xp+'">'+document.getElementById("new_rebate_measure4_1").innerHTML+'</select></td>';                                  
          str+= '<td data-title="Rebate Amt4:" class="min_width"><input type="text" value="" name="new_rebate_amount4_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_rebate_amount4_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="Rebate sub total4:" class="min_width"><input type="text" value="" name="new_rebate_subtotal4_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_rebate_subtotal4_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="unit/incentive:" class="min_width"><input type="text" value="" name="new_per_unit_incentive_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_per_unit_incentive_'+counter_xp+'"></td>';                                  
          str+= '<td data-title="item/incentive:" class="min_width"><input type="text" value="" name="new_line_item_incentive_'+counter_xp+'" readonly="readOnly" class="form-control m-bot15" id="new_line_item_incentive_'+counter_xp+'"></td></tr>';                                  
           $('#myTable > tbody:last').append(str);                                            
  });

  $('#getElecticalSubQuotePdfFile').click(function(){
    $('#update_elec_subquote_frm').attr('action','{{URL::to("quote/getElecticalSubQuotePdfFile")}}').submit();
  });

   </script>
  </body>
</html>
