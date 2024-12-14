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
</style>
  </head>
  <body class="full-width">

  <section id="container" class="">
  <?php
if($job_num[0] == 'J')
      $table = 'special_project';
    elseif($job_num[0] == 'M'){
      $table = 'grassivy';
    }
    else
      $table = 'electrical';
?>
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
                  <label>{{ Form::radio('jobElecQuoteFrm', 'M', (Input::old('jobElecQuoteFrm') == 'M'), array('id'=>'jobElecQuoteFrm', 'class'=>'radio','style'=>'display:inline;','checked')) }}Switch to Job Electrical Quote Form</label><br/>
                  <label>{{ Form::radio('jobElecQuoteFrm', 'F', (Input::old('jobElecQuoteFrm') == 'F'), array('id'=>'jobElecQuoteFrm2', 'class'=>'radio','style'=>'display:inline;'))}}Switch to Job Electrical Sub Quote Form</label><br/>
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
       {{Form::open(array('method' => 'POST','id'=>'update_elec_jobs','files'=>true,'route' => array('quote/updateElectricQuoteFrm')))}} 
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
                   <td data-title="Status:">Status:<br/> <span style="color:red; font-weight:bold;"> {{$jobElectricalQuoteTblRow['electrical_status']}}</span></td>     
                  <td data-title="Date:">Date:{{ Form::text('_schedule_date',($jobElectricalQuoteTblRow['schedule_date']!=""?date('Y-m-d',strtotime($jobElectricalQuoteTblRow['schedule_date'])):date('Y-m-d')), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => '_schedule_date')) }}</td>
                  <td data-title="Time:" style="width:20%">Time: <div class="input-group bootstrap-timepicker">{{ Form::text('schedule_time',$jobElectricalQuoteTblRow['schedule_time'], array('class' => 'form-control timepicker-default','id' => 'schedule_time')) }}  <span class="input-group-btn">{{Form::button('<i class="fa fa-clock-o"></i>', array('class' => 'btn btn-default'))}}</span></div></td>
                  </tr>
                  <tr>
                    <td data-title="Stage:">Stage:{{Form::select('_electrical_qote_stage_id',$gpg_settings,$jobElectricalQuoteTblRow["electrical_qote_stage_id"], ['id' => '_electrical_qote_stage_id', 'class'=>'form-control m-bot15'])}}</td>
                    <td data-title="Job Type:">Job Type:{{Form::select('_elec_quote_type',$elecJobTypeArray,$jobElectricalQuoteTblRow["elec_quote_type"], ['id' => '_elec_quote_type', 'class'=>'form-control m-bot15'])}}</td>
                    <td data-title="Prob.%:">Prob.%:{{ Form::text('_probability',($jobElectricalQuoteTblRow['probability'])?$jobElectricalQuoteTblRow['probability']:'', array('class' => 'form-control','id' => '_probability')) }}</td>
                    <td data-title="Electrical Qt. Status:">Electrical Qt. Status:{{ Form::text('_elec_quote_status',$jobElectricalQuoteTblRow['elec_quote_status'], array('class' => 'form-control','id' => '_elec_quote_status')) }}</td>
                    <td data-title="Est. Close Date:" colspan="2"  style="width:20%">Est. Close Date:{{ Form::text('_estimated_close_date',($jobElectricalQuoteTblRow['estimated_close_date']!=""?date('Y-m-d',strtotime($jobElectricalQuoteTblRow['estimated_close_date'])):''), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => '_estimated_close_date')) }}</td>
                  </tr>
                    </tbody>
                   </table>
                </section>
              </div>  
              </div>
              </div>
              </section>
                <div class="row"  id="show_hide_billing_info">
                  <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Customer Billing Address 
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">Customer Name:</td><td colspan="3">{{Form::select('_GPG_customer_id', $jobElectricalQuoteTblRow['customer_drop_down'],$jobElectricalQuoteTblRow['GPG_customer_id'] , ['class'=>'form-control','id'=>'_GPG_customer_id'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Address 1:</td><td>{{ Form::text('cusAddress1',$jobElectricalQuoteTblRow['customer_info']['address'], array('class' => 'form-control', 'id' => 'cusAddress1')) }}</td><td style="background-color:#FFFFCC;">Address 2:</td><td>{{ Form::text('cusAddress2',$jobElectricalQuoteTblRow['customer_info']['address2'], array('class' => 'form-control', 'id' => 'cusAddress2')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('cusCity',$jobElectricalQuoteTblRow['customer_info']['city'], array('class' => 'form-control', 'id' => 'cusCity')) }}</td><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('cusState',$jobElectricalQuoteTblRow['customer_info']['state'], array('class' => 'form-control', 'id' => 'cusState')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('cusZip',$jobElectricalQuoteTblRow['customer_info']['zipcode'], array('class' => 'form-control', 'id' => 'cusZip')) }}</td><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('cusPhone',$jobElectricalQuoteTblRow['customer_info']['phone_no'], array('class' => 'form-control', 'id' => 'cusPhone')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Sales Person:</td><td>{{Form::select('_GPG_employee_id',$jobElectricalQuoteTblRow['salesPerson_drop_down'],$jobElectricalQuoteTblRow['GPG_customer_id'], ['class'=>'form-control','id'=>'_GPG_employee_id'])}}
                                        </td><td style="background-color:#FFFFCC;">Estimator:</td><td>{{Form::select('_GPG_estimator_id',$jobElectricalQuoteTblRow['estimator_drop_down'],$jobElectricalQuoteTblRow['GPG_estimator_id'], ['class'=>'form-control','id'=>'_GPG_estimator_id'])}}
                                        </td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>        
                          </div>
                        </div>
                      </section>
                    </div>
                     <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Job Site Address [{{Form::button('COPY CUSTOMER DATA', array('onClick'=>'autoFill();','class' => 'btn btn-link btn-xs'))}}]
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">Project Name:</td><td colspan="3">{{Form::text('_project_name', $jobElectricalQuoteTblRow['project_name'] , ['class'=>'form-control','id'=>'_project_name'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Address:</td><td>{{ Form::text('_project_address',$jobElectricalQuoteTblRow['project_address'], array('class' => 'form-control', 'id' => '_project_address')) }}</td><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('_project_city',$jobElectricalQuoteTblRow['project_city'], array('class' => 'form-control', 'id' => '_project_city')) }}</td></tr><tr><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('_project_state',$jobElectricalQuoteTblRow['project_state'], array('class' => 'form-control', 'id' => '_project_state')) }}</td><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('_project_zip',$jobElectricalQuoteTblRow['project_zip'], array('class' => 'form-control', 'id' => '_project_zip')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Contact:</td><td>{{ Form::text('_project_contact',$jobElectricalQuoteTblRow['project_contact'], array('class' => 'form-control', 'id' => '_project_contact'))}}</td><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('_project_phone',$jobElectricalQuoteTblRow['project_phone'], array('class' => 'form-control', 'id' => '_project_phone')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Estimator Address:</td><td>{{Form::select('contact_info_id',$jobElectricalQuoteTblRow["contact_info"],$jobElectricalQuoteTblRow["contact_info_id"], ['class'=>'form-control','id'=>'contact_info_id'])}}
                                        </td><td style="background-color:#FFFFCC;">Terms and Conditions:</td><td>{{Form::select('terms_and_conditions_id',$jobElectricalQuoteTblRow['terms'],$jobElectricalQuoteTblRow['terms_and_conditions_id'], ['class'=>'form-control','style'=>'width:150px;','id'=>'terms_and_conditions_id'])}}
                                        </td></tr>
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
                  <div class="col-lg-4">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Generator's Cost 
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td>Generator:</td><td colspan="2">{{Form::text('_gen_cost', $jobElectricalQuoteTblRow['gen_cost'] , ['class'=>'form-control','id'=>'_gen_cost','onchange'=>'cal_gen_ats(this.id,this.value)'])}}</td></tr>
                                        <tr><td>Automatic Transfer Switch:</td><td colspan="2">{{Form::text('_ats_cost', $jobElectricalQuoteTblRow['gen_cost'] , ['class'=>'form-control','id'=>'_ats_cost','onchange'=>'cal_gen_ats(this.id,this.value)'])}}</td></tr>
                                        <tr><td>Materials Cost:</td><td colspan="2">{{Form::text('_material_cost', $jobElectricalQuoteTblRow['material_cost'] , ['class'=>'form-control','id'=>'_material_cost','readOnly'])}}</td></tr>
                                        <tr><td>Total Hours @ $:&nbsp;{{Form::text('_labor_hour_rate', $jobElectricalQuoteTblRow['labor_hour_rate'] , ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'_labor_hour_rate',"onchange"=>"cal_gen_ats(this.id,this.value)"])}}</td><td colspan="2">{{Form::text('_labor_cost', $jobElectricalQuoteTblRow['labor_cost'] , ['class'=>'form-control','id'=>'_labor_cost','readOnly'])}}</td></tr>
                                        <tr><td>Miscellaneous:&nbsp;{{Form::text('_misc_percent', $jobElectricalQuoteTblRow['misc_percent'] , ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'_misc_percent',"onchange"=>"cal_gen_ats(this.id,this.value)"])}}</td><td colspan="2">{{Form::text('_misc_cost', $jobElectricalQuoteTblRow['misc_cost'],['class'=>'form-control','id'=>'_misc_cost','readOnly'])}}</td></tr>
                                        <tr><td><b>Total: </b></td><td colspan="2">{{Form::text('_general_net_total', $jobElectricalQuoteTblRow['general_net_total'],['class'=>'form-control','id'=>'_general_net_total','readOnly'])}}</td></tr>
                                        <tr><td><b>Margin:&nbsp;{{Form::text('_general_margin', $jobElectricalQuoteTblRow['general_margin'] , ['class'=>'form-control','style'=>'width:40px; display:inline;',"onchange"=>"cal_gen_ats(this.id,this.value)",'id'=>'_general_margin'])}}</b></td><td colspan="2">{{Form::text('_general_margin_total', $jobElectricalQuoteTblRow['general_margin'],['class'=>'form-control','id'=>'_general_margin_total','readOnly'])}}</td></tr>
                                        <tr><td><b>Net Total: </b></td><td colspan="2">{{Form::text('_general_net_total', $jobElectricalQuoteTblRow['general_net_total'],['class'=>'form-control','id'=>'_general_net_total','readOnly'])}}</td></tr>
                                        <tr><td colspan="4" align="center"><b>Delivery Cost</b></td></tr>
                                        <tr><td><b>Labor Hrs/$:&nbsp;{{Form::text('_delivery_labor_hour_rate', $jobElectricalQuoteTblRow['delivery_labor_hour_rate'] , ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'_delivery_labor_hour_rate'])}}</b></td><td>{{Form::text('_delivery_labor_hour', $jobElectricalQuoteTblRow['delivery_labor_hour'],['class'=>'form-control','id'=>'_delivery_labor_hour'])}}</td><td>{{Form::text('_delivery_labor_hour_total', $jobElectricalQuoteTblRow['delivery_labor_hour_total'],['class'=>'form-control','id'=>'_delivery_labor_hour_total','readOnly'])}}</td></tr><tr><td><b>Mileage/$:&nbsp;{{Form::text('_delivery_mileage_rate', $jobElectricalQuoteTblRow['delivery_mileage_rate'] , ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'_delivery_mileage_rate'])}}</b></td><td>{{Form::text('_delivery_mileage', $jobElectricalQuoteTblRow['delivery_mileage'],['class'=>'form-control','id'=>'_delivery_mileage'])}}</td><td>{{Form::text('_delivery_mileage_total', $jobElectricalQuoteTblRow['delivery_mileage_total'],['class'=>'form-control','id'=>'_delivery_mileage_total','readOnly'])}}</td></tr>
                                        </tbody>
                                     </table>
                                  </section>
                                </div>
                              </div>
                        </div>
                    </div>
                  </section>
                </div>
                <div class="col-lg-4">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Freight LBS.
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td>Generator:</td><td colspan="2">{{Form::text('_freight_gen_cost', $jobElectricalQuoteTblRow['freight_gen_cost'] , ['class'=>'form-control','id'=>'_freight_gen_cost',"onchange"=>"cal_freight();"])}}</td></tr>
                                        <tr><td>ATS:</td><td colspan="2">{{Form::text('_freight_ats_cost', $jobElectricalQuoteTblRow['freight_ats_cost'] , ['class'=>'form-control','id'=>'_freight_ats_cost'])}}</td></tr>
                                        <tr><td>Housing:</td><td colspan="2">{{Form::text('_freight_housing_cost', $jobElectricalQuoteTblRow['freight_housing_cost'] , ['class'=>'form-control','id'=>'_freight_housing_cost'])}}</td></tr>
                                        <tr><td>Tank:</td><td colspan="2">{{Form::text('_freight_tank_cost', $jobElectricalQuoteTblRow['freight_tank_cost'] , ['class'=>'form-control','id'=>'_freight_tank_cost'])}}</td></tr>
                                        <tr><td>Accessories:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$</td><td colspan="2">{{Form::text('_freight_acc_cost', $jobElectricalQuoteTblRow['freight_acc_cost'],['class'=>'form-control','id'=>'_freight_acc_cost'])}}</td></tr>
                                        <tr><td><b>Total: </b></td><td colspan="2">{{Form::text('_freight_total_cost', $jobElectricalQuoteTblRow['freight_total_cost'],['class'=>'form-control','id'=>'_freight_total_cost','readOnly'])}}</td></tr>
                                        <tr><td>&nbsp;</td><td colspan="2">{{Form::text('abc','',['class'=>'form-control','readOnly'])}}</td></tr>
                                        <tr><td>&nbsp;</td><td colspan="2">{{Form::text('abc','',['class'=>'form-control','readOnly'])}}</td></tr>
                                        <tr><td colspan="4" align="center"><b>Start Up Cost</b></td></tr>
                                        <tr><td><b>Labor Hrs/$:{{Form::text('_startup_labor_hour_rate', $jobElectricalQuoteTblRow['startup_labor_hour_rate'] , ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'_startup_labor_hour_rate'])}}</b></td><td>{{Form::text('_startup_labor_hour', $jobElectricalQuoteTblRow['startup_labor_hour'],['class'=>'form-control','id'=>'_startup_labor_hour'])}}</td><td>{{Form::text('_startup_labor_hour_total', $jobElectricalQuoteTblRow['startup_labor_hour_total'],['class'=>'form-control','id'=>'_startup_labor_hour_total','readOnly'])}}</td></tr>
                                         <tr><td><b>Mileage/$:{{Form::text('_startup_mileage_rate', $jobElectricalQuoteTblRow['startup_mileage_rate'] , ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'_startup_mileage_rate'])}}</b></td><td>{{Form::text('_startup_mileage', $jobElectricalQuoteTblRow['startup_mileage'],['class'=>'form-control','id'=>'_startup_mileage'])}}</td><td>{{Form::text('_startup_mileage_total', $jobElectricalQuoteTblRow['startup_mileage_total'],['class'=>'form-control','id'=>'_startup_mileage_total','readOnly'])}}</td></tr>
                                        </tbody>
                                     </table>
                                  </section>
                                </div>
                              </div>
                        </div>
                    </div>
                  </section>
                </div>
                <div class="col-lg-4">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Total's Info
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td height="84">Generator & ATS:</td><td colspan="2">{{Form::text('_gen_ats_total', $jobElectricalQuoteTblRow['gen_ats_total'] , ['class'=>'form-control','id'=>'_gen_ats_total','readOnly'])}}</td></tr>
                                        <tr><td height="84">Miscellaneous:</td><td  colspan="2">{{Form::text('_misc_total', $jobElectricalQuoteTblRow['misc_total'] , ['class'=>'form-control','id'=>'_misc_total','readOnly'])}}</td></tr>
                                        <tr><td height="84">Start Up:</td><td  colspan="2">{{Form::text('_startup_total', $jobElectricalQuoteTblRow['startup_total'] , ['class'=>'form-control','id'=>'_startup_total','readOnly'])}}</td></tr>
                                        <tr><td height="84">Materials:</td><td  colspan="2">{{Form::text('_material_total', $jobElectricalQuoteTblRow['material_total'] , ['class'=>'form-control','id'=>'_material_total','readOnly'])}}</td></tr>
                                        <tr><td height="84">Labor:</td><td  colspan="2">{{Form::text('_labor_total', $jobElectricalQuoteTblRow['labor_total'] , ['class'=>'form-control','id'=>'_labor_total','readOnly'])}}</td></tr>
                                        <tr><td height="84">Delivery Labor & Milleage:</td><td  colspan="2">{{Form::text('_delivery_total', $jobElectricalQuoteTblRow['delivery_total'] , ['class'=>'form-control','id'=>'_delivery_total','readOnly'])}}</td></tr>
                                        <tr><td height="84">Freight:{{Form::text('_freight_factor', $jobElectricalQuoteTblRow['freight_factor'], ['class'=>'form-control','style'=>'width:50px; display:inline;','id'=>'_freight_factor',"onchange"=>"cal_freight()"])}}</td><td  colspan="2">{{Form::text('_freight_total', $jobElectricalQuoteTblRow['freight_total'] , ['class'=>'form-control','id'=>'_freight_total','readOnly'])}}</td></tr>
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
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="otherTable">
                                        <thead class="cf">
                                        <tr><th>{{Form::button('<i class="fa fa-plus-circle"></i>', array('class' => 'btn btn-success btn-xs','id'=>'add_new_row'))}}&nbsp;&nbsp;QTY</th><th>Description</th><th>Cost Price</th><th>Other Charges</th></tr>
                                        </thead>
                                        <tbody class="cf">
                                        <?php $i=0;?>
                                        @foreach($quote_other_info as $queryOtherChargeResRow)
                                        <tr><?php $i++;?>
                                        <td data-title="Qty">
                                       {{Form::text('other_charge_qty_'.$i,$queryOtherChargeResRow['other_charge_qty'], ['class'=>'form-control','id'=>'other_charge_qty_'.$i])}}
                                        </td><td data-title="Description:"><?php if(!in_array($queryOtherChargeResRow['other_charge_description'],array("Mileage","Freight"))) { ?>
                                        {{Form::text('other_charge_description_'.$i,$queryOtherChargeResRow['other_charge_description'], ['class'=>'form-control','id'=>'other_charge_description_'.$i])}}
                                        <?php } else { echo $queryOtherChargeResRow['other_charge_description']; } ?></td>
                                        <td data-title="Coast Price:">
                                        {{Form::text('other_charge_cost_price_'.$i,$queryOtherChargeResRow['other_charge_cost_price'], ['class'=>'form-control','id'=>'other_charge_cost_price_'.$i])}}</td><td  data-title="Other Charges:" style='width:300px;'> {{Form::text('other_charge_cost_total_'.$i,$queryOtherChargeResRow['other_charge_cost_price'], ['class'=>'form-control','id'=>'other_charge_cost_total_'.$i,'readOnly'])}}</td>
                                        </tr>
                                        @endforeach
                                        {{Form::hidden('otherCounter',$i,['id'=>'otherCounter_id'])}}
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </section>
                  </div>
              <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td> Scope Of Work</td><td>Exclusions</td><td>Total Cost </td><td > {{Form::text('_cost_gross_total',$jobElectricalQuoteTblRow['cost_gross_total'], ['class'=>'form-control','id'=>'_cost_gross_total'])}}</td></tr>
                                        <tr><td rowspan="3">{{ Form::textarea('_scope_of_work', $jobElectricalQuoteTblRow['scope_of_work'],['class'=>'form-control']) }}</td><td rowspan="3">{{ Form::textarea('_exclusions', $jobElectricalQuoteTblRow['exclusions'],['class'=>'form-control']) }}</td><td>Total Margin</td><td >{{Form::text('_margin_gross_total',$jobElectricalQuoteTblRow['margin_gross_total'], ['class'=>'form-control','id'=>'_margin_gross_total'])}}</td></tr>
                                        <tr><td>TOTAL Sale with out Tax</td><td >{{Form::text('_grand_total_no_tax',$jobElectricalQuoteTblRow['grand_total_no_tax'], ['class'=>'form-control','id'=>'_grand_total_no_tax'])}}</td></tr>
                                        <tr><td>Sales Tax  %:{{Form::text('_tax_amount',number_format((empty($jobElectricalQuoteTblRow['tax_amount'])?7.75:$jobElectricalQuoteTblRow['tax_amount']),2) , ['class'=>'form-control','style'=>'width:50px; display:inline;','id'=>'_tax_amount',"onchange"=>"cal_total_all()"])}}</td><td >{{Form::text('_tax_cost_total',$jobElectricalQuoteTblRow['tax_cost_total'], ['class'=>'form-control','id'=>'_tax_cost_total'])}}</td></tr>
                                         <tr><td colspan="2">{{HTML::link('#myModal4', 'Manage Attachments' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'manage_files'))}}</td><td>TOTAL Sale with Tax :</td><td >{{Form::text('_grand_total',$jobElectricalQuoteTblRow['grand_total'], ['class'=>'form-control','id'=>'_grand_total'])}}</td></tr>
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
      {{ HTML::link("quote/excelQuoteFormExport?table=$table&id=$job_id&j_num=$job_num", 'Export Excel' , array('class'=>'btn btn-success'))}}
      {{ Form::button('Export PDF' , array('id'=>'getElecticalQuotePdfFile','class'=>'btn btn-danger'))}} 
    </div>
  {{Form::close()}}
</section>
{{HTML::link('#myModal', '' , array('class' => 'btn btn-link','data-toggle'=>'modal','id'=>'click_modal'))}}
                       <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" id="change_width">
    {{ Form::open(array('before' => 'csrf' ,'id'=>'edit_update_gen_costs','url'=>route('quote/eupdateGenCost'),'files'=>true, 'method' => 'post')) }} {{Form::hidden('job_id',$job_id)}}{{Form::hidden('job_num',$job_num)}}
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Material & Labor Sheet:</h4>
                                          </div>
                                          <div class="modal-body">
                                        <section id="no-more-tables">
                                         <table class="table table-bordered table-striped table-condensed cf" id="myTable">
                                            <thead class="cf">
                                             <tr><th>Type</th><th>Qty</th><th>Description</th><th>Material Cost</th><th>Total Material Cost</th><th>Hours Est.</th><th>Total Hours @</th><th>Total Cost Labor & Materials</th></tr> 
                                            </thead>
                                            <tbody class="cf">
                                            @if(!empty($gen_cost))  
                                              <?php $i=0;?>
                                              @foreach($gen_cost as $genData)
                                                <tr>
                                                <td data-title="Type:">{{Form::select('type_'.$i,array('Material','Labor'),$genData['type'], ['id' => 'type_'.$i, 'class'=>'form-control m-bot15'])}}</td><td data-title="Qty:">{{Form::text('quantity_'.$i,$genData['quantity'], ['class'=>'form-control','id'=>'quantity_'.$i,"onkeyup"=>"cal_labor_material_list(this.id,this.value)"])}}</td>
                                                <td data-title="Descp:">{{Form::text('description_'.$i,$genData['description'], ['class'=>'form-control','id'=>'description_'.$i])}}</td>
                                                <td data-title="Material Cost:">{{Form::text('material_cost_'.$i,$genData['material_cost'], ['class'=>'form-control','id'=>'material_cost_'.$i,"onkeyup"=>"cal_labor_material_list(this.id,this.value)"])}}</td>
                                                <td data-title="Total Mat. Cost:">{{Form::text('total_material_cost_'.$i,'', ['class'=>'form-control','id'=>'total_material_cost_'.$i,'readOnly'])}}</td>
                                                <td data-title="Hrs Est:">{{Form::text('est_hour_'.$i,$genData['est_hour'], ['class'=>'form-control','id'=>'est_hour_'.$i])}}</td>
                                                <td data-title="Total Hrs:">{{Form::text('act_hour_'.$i,$genData['act_hour'], ['class'=>'form-control','id'=>'act_hour_'.$i,"onkeyup"=>"cal_labor_material_list(this.id,this.value)"])}}</td>
                                                <td data-title="Total Cost/Labor/Material:">{{Form::text('main_totalLabel_'.$i,'', ['class'=>'form-control','id'=>'main_totalLabel_'.$i,'readOnly'])}}</td>
                                              </tr>
                                               <?php $i++;?>
                                              @endforeach
                                              {{Form::hidden('genCounter',($i-1),['id'=>'genCounter_id'])}}
                                            @else  
                                              <tr>
                                                <td data-title="Type:">{{Form::select('type_'.$i,array('Material','Labor'),'', ['id' => 'type_'.$i, 'class'=>'form-control m-bot15'])}}</td>
                                                <td data-title="Qty:">{{Form::text('quantity_'.$i,'', ['class'=>'form-control','id'=>'quantity_'.$i,"onkeyup"=>"cal_labor_material_list(this.id,this.value)"])}}</td>
                                                <td data-title="Descp:">{{Form::text('description_'.$i,'', ['class'=>'form-control','id'=>'description_'.$i])}}</td>
                                                <td data-title="Material Cost:">{{Form::text('material_cost_'.$i,'', ['class'=>'form-control','id'=>'material_cost_'.$i,"onkeyup"=>"cal_labor_material_list(this.id,this.value)"])}}</td>
                                                <td data-title="Total Mat. Cost:">{{Form::text('total_material_cost_'.$i,'', ['class'=>'form-control','id'=>'total_material_cost_'.$i,'readOnly'])}}</td>
                                                <td data-title="Hrs Est:">{{Form::text('est_hour_'.$i,'', ['class'=>'form-control','id'=>'est_hour_'.$i])}}</td>
                                                <td data-title="Total Hrs:">{{Form::text('act_hour_'.$i,'', ['class'=>'form-control','id'=>'act_hour_'.$i,"onkeyup"=>"cal_labor_material_list(this.id,this.value)"])}}</td>
                                                <td data-title="Total Cost/Labor/Material:">{{Form::text('main_totalLabel_'.$i,'', ['class'=>'form-control','id'=>'main_totalLabel_'.$i,'readOnly'])}}</td>
                                              </tr>
                                              {{Form::hidden('genCounter',($i-1),['id'=>'genCounter_id'])}}
                                            @endif  
                                            </tbody>
                                         </table>
                                        </section>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('<i class="fa fa-plus-circle"></i>', array('class' => 'btn btn-success','id'=>'add_another_row'))}}
                                          {{Form::button('Save/Update', array('class' => 'btn btn-primary','data-dismiss'=>'modal','id'=>'save_update_data'))}}
                                          {{Form::button('<i class="fa fa-times"></i>', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                      </div>
                                  </div>
                                  {{Form::close()}}
                              </div>
                          </div>
                        <!-- modal -->
                        <!-- Modal# 2-->
           <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ATTACHMENT MANAGEMENT: [{{$job_num}}]</h4>
                      </div>
                    <div class="modal-body">
                 {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('quote/manageQuoteFiles'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('fjob_id',$job_id)}} {{Form::hidden('fjob_num',$job_num)}}     <div class="form-group">
                                               <section id="no-more-tables"  style="padding:10px;">
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                <thead class="cf">
                                                  <tr><th>#</th><th>Category Name </th><th>Action</th></tr>
                                                </thead>
                                                <tbody class="cf" id="display_quote_files">
                                                </tbody>
                                                </table>
                                              </section> 
                  <div style="display: inline;">
                   {{ Form::file('attachment', array('style'=>'float: left !important; display:inline !important; width:50%;' ,'id' => 'attachment')) }}
                          </div> </div>
                   {{Form::close()}}
                  <div class="btn-group" style="padding:20px;">
                    {{Form::button('Submit', array('class' => 'btn btn-success', 'id'=>'submit_attachments'))}}
                   {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- modal# 2 end--> 
<!-- Modal Paste Here.... -->
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
  <script type="text/javascript">
    $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
        });
     $('.timepicker-default').timepicker();
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
    function autoFill() {
      if (confirm("Job Site Fields will be updated! Do you want to continue?")) {
        document.getElementById("_project_address").value = document.getElementById("cusAddress1").value;
        document.getElementById("_project_city").value = document.getElementById("cusCity").value;
        document.getElementById("_project_state").value = document.getElementById("cusState").value;
        document.getElementById("_project_zip").value = document.getElementById("cusZip").value;
        document.getElementById("_project_phone").value = document.getElementById("cusPhone").value;
      }
  }
  $('#_material_cost').click(function(){
    document.getElementById('click_modal').click();
   });
  $('#_labor_cost').click(function(){
    document.getElementById('click_modal').click();
   });
  var counter=parseInt('1')+parseInt($('#genCounter_id').val());
  $('#add_another_row').click(function(){
       var str = '<tr><td data-title="Type:"><select name="type_'+counter+'" class="form-control m-bot15" id="type'+counter+'"><option value="0">Material</option><option value="1">Labor</option></select></td>';
          str += '<td data-title="Qty:"><input type="text" value="" onkeyup="cal_labor_material_list(this.id,this.value)" name="quantity_'+counter+'" id="quantity_'+counter+'" class="form-control"></td>';
          str += '<td data-title="Descp:"><input type="text" value="" name="description_'+counter+'" id="description_'+counter+'" class="form-control"></td>';
          str += '<td data-title="Material Cost:"><input type="text"  onkeyup="cal_labor_material_list(this.id,this.value)" value="" name="material_cost_'+counter+'" id="material_cost_'+counter+'" class="form-control"></td>';
          str += '<td data-title="Total Mat. Cost:"><input type="text" value="" name="total_material_cost_'+counter+'" readonly="readOnly" id="total_material_cost_'+counter+'" class="form-control"></td>';
          str += '<td data-title="Hrs Est:"><input type="text" value="" name="est_hour_'+counter+'" id="est_hour_'+counter+'" class="form-control"></td>';
          str += '<td data-title="Total Hrs:"><input type="text" value=""  onkeyup="cal_labor_material_list(this.id,this.value)" name="act_hour_'+counter+'" id="act_hour_'+counter+'" class="form-control"></td>';
          str += '<td data-title="Total Cost/Labor/Material:"><input type="text" value="" name="main_totalLabel_'+counter+'"  readonly="readOnly" id="main_totalLabel_'+counter+'" class="form-control"></td></tr>';
           $('#myTable > tbody:last').append(str);
           $('#genCounter_id').val(parseInt($('#genCounter_id').val())+parseInt('1'));
           counter = parseInt(counter) + parseInt("1");
  });

  var otcounter = parseInt('1')+parseInt($('#otherCounter_id').val());
  $('#add_new_row').click(function(){
    var str1 = '<tr><td data-title="Qty"><input type="text" value="1" name="other_charge_qty_'+otcounter+'" id="other_charge_qty_'+otcounter+'" class="form-control"></td>';
      str1 += '<td data-title="Description:"><input type="text" value="" name="other_charge_description_'+otcounter+'" id="other_charge_description_'+otcounter+'" class="form-control"></td>';
      str1 += '<td data-title="Coast Price:"><input type="text" value="0" name="other_charge_cost_price_'+otcounter+'" id="other_charge_cost_price_'+otcounter+'" class="form-control"></td>';
      str1 += '<td style="width:300px;" data-title="Other Charges:"><input type="text" value="1" name="other_charge_cost_total_'+otcounter+'" id="other_charge_cost_total_'+otcounter+'" class="form-control"></td></tr>'; 

    $('#otherTable > tbody:last').append(str1);
    $('#otherCounter_id').val(parseInt($('#otherCounter_id').val())+parseInt('1'));
    otcounter = parseInt(otcounter) + parseInt("1");
  });

  $('#save_update_data').click(function(){
    $("#edit_update_gen_costs").submit(); //Submit the form
  });

  function cal_labor_material_list(rid,fval) {
    var total_list = 0.00;
    var row_id =  rid.split("_").pop(-1);

    fval = parseFloat($("#quantity_"+row_id).val());
    fval2 = parseFloat($("#material_cost_"+row_id).val());
    
    fval3 = parseFloat($("#act_hour_"+row_id).val());
    fval4 = parseFloat($("#_labor_hour_rate").val());
   
    if (!isNaN(fval) && !isNaN(fval2)) { 
      total_list = fval*fval2;
      $("#total_material_cost_"+row_id).val(fval*fval2);
    }
    else { 
      total_list = 0.00;
      $("#total_material_cost_"+row_id).val('0');
    }
    if (!isNaN(fval3) && !isNaN(fval4)){ 
      total_list += (fval3*fval4);
    }
    
    $("#main_totalLabel_"+row_id).val(total_list);
  }//function ends here

  function cal_gen_ats(fid,fval) {
  var total_gen_ats = 0.00;
  var misc_total = 0.00;
  fval = parseFloat($("#_gen_cost").val());
  if (!isNaN(fval)) { 
     total_gen_ats += fval ;
    $("#_gen_ats_total").val(total_gen_ats);
  }
  
  fval = parseFloat($("#_ats_cost").val());
  if (!isNaN(fval)) { 
    total_gen_ats += fval ;
    $("#_gen_ats_total").val(total_gen_ats);
  } 
 
  fval = parseFloat($("#_material_cost").val());
  if (!isNaN(fval)) { 
     total_gen_ats += misc_total = fval;
     $("#_material_total").val(fval); 
  } else $("#_material_total").val(0.00);
   
  fval = parseFloat($("#_labor_hour_rate").val());
  fval2 = parseFloat($("#_labor_hour").val());
  if (!isNaN(fval) && !isNaN(fval2)) { 
     total_gen_ats += (fval*fval2);
     misc_total += (fval*fval2);
     $("#_labor_cost").value = fval*fval2; 
     $("#_labor_total").value = fval*fval2; 
  } else {
     $("#_labor_cost").val('0.00');
     $("#_labor_total").val('0.00');
  }
  
  fval = parseFloat($("#_misc_percent").val());
  if (!isNaN(fval)) { 
    misc_total = ((fval * misc_total)/100)
    total_gen_ats += misc_total ;
    $("#_misc_total").val(misc_total);
    $("#_misc_cost").val(misc_total) ;
  } else { 
    $("#_misc_cost").val('0.00');
    $("#_misc_total").val('0.00');
  }
  $("#_general_cost_total").val(total_gen_ats);
  
  fval = parseFloat($("#_general_margin").val());
  if (!isNaN(fval)) { 
    $("#_general_margin_total").val(((total_gen_ats/(fval==0?1:fval)) - total_gen_ats));
    total_gen_ats += ((total_gen_ats/(fval==0?1:fval)) - total_gen_ats);
  } else { 
      $("#_general_margin_total").val('0.00');
  }
  $("#_general_net_total").val(total_gen_ats);
  
  cal_total_all(); 
}
function cal_freight() {
  var total_freight = 0.00;
  var fval;
  fval = parseFloat($("#_freight_gen_cost").val());
  if (!isNaN(fval)) 
    total_freight+=fval;
  fval = parseFloat($("#_freight_ats_cost").val());
  if (!isNaN(fval)) 
    total_freight+=fval;
  fval = parseFloat($("#_freight_housing_cost").val());
  if (!isNaN(fval)) 
    total_freight+=fval;
  fval = parseFloat($("#_freight_tank_cost").val());
  if (!isNaN(fval)) 
    total_freight+=fval;
  fval = parseFloat($("#_freight_acc_cost").val());
  if (!isNaN(fval)) 
    total_freight+=fval;
  $("#_freight_total_cost").val(total_freight);   
  var freight_factor = parseFloat($("#_freight_factor").val());
  var tval = total_freight * (!isNaN(freight_factor)?freight_factor:0);
  $("#_freight_total").val(tval);  
  cal_total_all(); 
}
function cal_total_all() {
  var total_cost = 0.00;
  var matCost = 0.00; 
  
  fval = parseFloat($("#_gen_ats_total").val());
  if (!isNaN(fval)) { 
    total_cost+=fval;
      matCost += fval;
  }
  
  fval = parseFloat($("#_misc_total").val());
  if (!isNaN(fval)) total_cost+=fval;
  
  fval = parseFloat($("#_startup_total").val());
  if (!isNaN(fval)) total_cost+=fval;
  
  fval = parseFloat($("#_material_total").val());
  if (!isNaN(fval)) { 
      total_cost+=fval;
    matCost += fval;
  }
  fval = parseFloat($("#_labor_total").val());
  if (!isNaN(fval)) total_cost+=fval;
    fval = parseFloat($("#_delivery_total").val());
  if (!isNaN(fval)) total_cost+=fval;
    fval = parseFloat($("#_freight_total").val());
  if (!isNaN(fval)) total_cost+=fval;
  $("#_cost_gross_total").val(total_cost); 
  fval = parseFloat($("#_general_margin").val());
  
  if (!isNaN(fval)) {  
    $("#_margin_gross_total").val(((total_cost/(fval==0?1:fval)) - total_cost));
    total_cost += ((total_cost/(fval==0?1:fval)) - total_cost);    
  } else { 
      $("#_margin_gross_total").val('0.00');
  }
  
  counter = 1;  
  while (1) {
      if(typeof $('#other_charge_cost_total_'+counter).val() != 'undefined') { 
        fval = parseFloat($("#other_charge_cost_total_"+counter).val());
        if (!isNaN(fval)) 
          total_cost+=fval;
      } 
      else 
        break;
      counter++;
  }   
  
  $("#_grand_total_no_tax").val(total_cost);
  fval = parseFloat($("#_tax_amount").val());
  if (!isNaN(fval)) { 
    $("#_tax_cost_total").val(((matCost*fval)/100));
    total_cost += ((matCost*fval)/100);
  } else { 
      $("#_tax_cost_total").val('0.00');
  }
  $("#_grand_total").val(total_cost); 

}//func ends

  $('#submit_attachments').click(function(){
        $('#submit_file_form').submit();
  });
  $('#submit_main_form').click(function(){
    $('#update_elec_jobs').submit();
  });

  $('a[name=manage_files]').click(function(){
        $.ajax({
              url: "{{URL('ajax/getQuoteFiles')}}",
              data: {
                'id' : '{{$job_id}}',
                'num': '{{$job_num}}',
                'table':'{{$table}}'
              },
            success: function (data) {
              $('#display_quote_files').html(data);
               $('a[name=del_quote_file]').click(function(){
                var result = confirm("Are you sure! you want to delete....?");
                if(result){
                  $.ajax({
                        url: "{{URL('ajax/deleteQuoteFile')}}",
                        data: {
                          'id' : $(this).attr('id'),
                          'table':'{{$table}}'
                        },
                        success: function (data) {
                          if (data == 1){     
                            alert("Deleted Successfully!");
                            location.reload();
                          }
                      },
                  });
                }  
               });
            },
        });
  });
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
            'copy_id' : $("#copyElectricalQuoteId option:selected").val(),
            'table':'{{$table}}'
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

  $('#quote_id').change(function(){
    var jid = $(this).val();
    var jnum = $('#quote_id option:selected').text();
    window.location.href = '{{URL::to("job/job_electrical_quote_frm/'+jid+'/'+jnum+'")}}';
  });

  $("input[name='jobElecQuoteFrm']").change(function(){
    var jid= '<?php echo $job_id;?>';
    var jnum= '<?php echo $job_num;?>';
    var cpage = $(this).attr('id');
    if (cpage == 'jobElecQuoteFrm2')
     window.location.href = '{{URL::to("job/job_electrical_subquote_frm/'+jid+'/'+jnum+'")}}';
    else if(cpage == 'jobElecQuoteFrm3')
      window.location.href = '{{URL::to("job/job_electrical_equipment_pricing_frm/'+jid+'/'+jnum+'")}}';
    else
      window.location.href = '{{URL::to("job/job_electrical_quote_frm/'+jid+'/'+jnum+'")}}';
  });

  $('#getElecticalQuotePdfFile').click(function(){
      $('#update_elec_jobs').attr('action','{{URL::to("quote/getElecticalQuotePdfFile")}}').submit();
  });
  </script>

  </body>
</html>
