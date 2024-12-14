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
      <!--header start-->
      <header class="header white-bg">
          <div class="navbar-header" style="display:inline;">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                  <span class="fa fa-bars"></span>
              </button>
<?php
if($job_num[0] == 'J')
      $table = 'special_project';
    elseif($job_num[0] == 'M'){
      $table = 'grassivy';
    }
    else
      $table = 'electrical';
$vendor_select = '';
foreach ($gpg_vendor as $key => $value) {
  $vendor_select .= '<option value='.$key.'>'.filter_var($value, FILTER_SANITIZE_STRING).'</option>';
}
?>
              <!--logo start-->
               <div class="col-lg-6">
                  <section class="panel">
                  {{ HTML::image(asset('img/gpglogo.jpg'), 'GPG Logo', array('style' => 'display:inline; width:100px; height:70px;')) }}
                    {{Form::select('copyElectricalQuoteId',$list_quotes,$job_id, ['id'=>'copyElectricalQuoteId','class'=>'form-control m-bot15','style'=>'display:inline;'])}}{{ Form::checkbox('is_child','1','', array('id'=>'is_child','class' => 'input-group','style'=>'display:inline;')) }}&nbsp;&nbsp;Create a new child {{Form::button('Copy Quote Data',array('id'=>'copy_quote_data_confirm','style'=>'display:inline;','class'=>'btn btn-default'))}} 
                    </section>
                </div>
                <div class="col-lg-3">
                  <section class="panel" id="hide_selects" style="display:none;">
                  <br/><br/><br/>
                  <label>{{ Form::radio('jobElecQuoteFrm', 'M', (Input::old('jobElecQuoteFrm') == 'M'), array('id'=>'jobElecQuoteFrm', 'class'=>'radio','style'=>'display:inline;')) }}Switch to Job Electrical Quote Form</label><br/>
                  <label>{{ Form::radio('jobElecQuoteFrm', 'F', (Input::old('jobElecQuoteFrm') == 'F'), array('id'=>'jobElecQuoteFrm2', 'class'=>'radio','style'=>'display:inline;'))}}Switch to Job Electrical Sub Quote Form</label><br/>
                  <label>{{ Form::radio('jobElecQuoteFrm', 'S', (Input::old('jobElecQuoteFrm') == 'S'), array('id'=>'jobElecQuoteFrm3', 'class'=>'radio','style'=>'display:inline;','checked')) }}Switch to Electrical Equipment Pricing Form</label>
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
       {{ Form::open(array('method' => 'POST','id'=>'update_elec_equip_pricing_frm','files'=>true,'route' => array('quote/updateElectricQuotePricingFrm')))}} 
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
		              	<td data-title="Job Number:"><?php echo str_replace('_', ' ', ucfirst($table));?> Qt #: {{ Form::text('job_num',$job_num, array('class' => 'form-control', 'id' => 'job_num','readOnly')) }}</td>
                   <td data-title="Go to:">Go to:{{ Form::select('quote_id',$quote_ids_arr,$job_id, array('class' => 'form-control','id' => 'quote_id')) }}</td>  
                   <td data-title="Po Number:">Po Number:{{ Form::text('_po_number',$jobElectricalQuoteTblRow['po_number'], array('class' => 'form-control','id' => '_po_number')) }}</td> 
                   <td data-title="Status:">Status:<br/><span style="color:red; font-weight:bold;"> {{$jobElectricalQuoteTblRow['jobTypeStatus']}}</span></td>     
		              <td data-title="Date:">Date:{{ Form::text('scheduleDate',($jobElectricalQuoteTblRow['schedule_date']!=""?date('Y-m-d',strtotime($jobElectricalQuoteTblRow['schedule_date'])):date('Y-m-d')), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'scheduleDate')) }}</td>
		              <td data-title="Time:">Time: <div class="input-group bootstrap-timepicker">{{ Form::text('schedule_time',$jobElectricalQuoteTblRow['schedule_time'], array('class' => 'form-control timepicker-default','id' => 'schedule_time')) }}  <span class="input-group-btn">{{Form::button('<i class="fa fa-clock-o"></i>', array('class' => 'btn btn-default'))}}</span></div></td>
                  </tr>
                  <tr>
                    <td data-title="Stage:">Stage:{{Form::select('_'.$table.'_qote_stage_id',$gpg_settings,$jobElectricalQuoteTblRow["qote_stage_id"], ['id' => '_'.$table.'_qote_stage_id', 'class'=>'form-control m-bot15'])}}</td>
                    <td data-title="Job Type:">Job Type:{{Form::select('_'.($table=='electrical'?'elec':$table).'_quote_type',$elecJobTypeArray,(isset($jobElectricalQuoteTblRow["quote_type"])?$jobElectricalQuoteTblRow["quote_type"]:'-'), ['id' => '_'.$table.'_quote_type', 'class'=>'form-control m-bot15'])}}</td>
                    <td data-title="Prob.%:">Prob.%:{{ Form::text('_probability',($jobElectricalQuoteTblRow['probability'])?$jobElectricalQuoteTblRow['probability']:'', array('class' => 'form-control','id' => '_probability')) }}</td>
                    <td data-title="Electrical Qt. Status:"><?php echo str_replace('_', ' ', ucfirst($table));?> Qt. Status:{{ Form::text('_'.($table=='electrical'?'elec':$table).'_quote_status',(isset($jobElectricalQuoteTblRow['quote_status'])?$jobElectricalQuoteTblRow['quote_status']:'-'), array('class' => 'form-control','id' => '_'.($table=='electrical'?'elec':$table).'_quote_status')) }}</td>
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
                  <div class="col-lg-12">
                    <section class="panel">
                      <section id="no-more-tables">
                        <table class="table table-bordered table-striped table-condensed cf">
                          <tbody class="cf">
                            <tr>
                              <td style="background-color:#FFFFCC;"> Consolidate Pricing:&nbsp;&nbsp;{{ Form::checkbox('consolidate','1','', array('id'=>'consolidate','class' => 'input-group','style'=>'display:inline;','onClick'=>'checkAllConsolidate(this);')) }}</td>
                              <td style="background-color:#FFFFCC;">Set Global Margin:{{Form::text('GlobalMargin','', ['class'=>'form-control','style'=>'width:50px; display:inline;','id'=>'GlobalMargin'])}} {{Form::button('Set All', array('class' => 'btn btn-success btn-xs','id'=>'set_all','onClick'=>'setGlobalMargin(document.getElementById("GlobalMargin").value); return false;'))}}</td>
                              <td style="background-color:#FFFFCC;">EBM Load (.csv) or (.txt):{{Form::file('EBMfileToUpload','', ['class'=>'form-control','style'=>'width:50px; display:inline;','id'=>'EBMfileToUpload'])}}{{Form::button('Upload', array('class' => 'btn btn-success btn-xs','id'=>'update_all','style'=>'float: left !important; display:inline !important;'))}}</td>
                              <td style="background-color:#FFFFCC;"> Excel Load (.xls) or (.xlsx):{{Form::file('ExlFileToUpload','', ['class'=>'form-control','style'=>'width:50px; display:inline;','id'=>'ExlFileToUpload'])}}{{Form::button('Upload', array('class' => 'btn btn-success btn-xs','id'=>'excel_upload','style'=>'float: left !important; display:inline !important;'))}} </td>
                            </tr>
                          </tbody>
                        </table>
                    </section>
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
                                     <table class="table table-bordered table-striped table-condensed cf" id="Equipment">
                                        <thead class="cf">
                                        <tr><th colspan="13">Equipments</th></tr>
                                        <tr><th>Del</th><th>Vendor</th><th>Quantity</th><th>Description</th><th>Cost:{{Form::text('equipment_cost','', ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'equipment_cost'])}}</th><th>Sell Price</th><th>Margin:{{Form::text('equipment_margin','', ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'equipment_margin'])}}</th><th>Calculated Sell Price</th><th>Total Cost</th><th>Margin $</th><th>Taxable?</th><th>Inc. in Bill of Mat.</th><th>Order</th></tr>
                                        </thead>
                                        <tbody class="cf">
                                        <?php $i=0;?>
                                        @foreach($getElectricalEquipmentPricing as $getEEProw)
                                        <tr><?php $i++;?>
                                        {{Form::hidden('Equipment_id_'.$i,$getEEProw['id'])}}
                                        <td data-title="Del">{{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-xs','name'=>'delete_elm_row','id'=>$getEEProw['id'],'table'=>'equipment'))}}</td>
                                        <td data-title="">
                                          {{Form::select('Equipment_vendor_'.$i,$gpg_vendor,$getEEProw['gpg_vendor_id'], ['id' => 'Equipment_vendor_'.$i, 'class'=>'form-control m-bot15'])}}
                                        </td>
                                        <td data-title="Eqp Qnty:">{{Form::text('Equipment_quantity_'.$i,$getEEProw['equipment_quantity'], ['class'=>'form-control','id'=>'Equipment_quantity_'.$i,'onchange'=>"ChangeRowValue('Equipment',".$i.",1)"])}}</td>
                                        <td data-title="Eqp. Desc:">{{Form::text('Equipment_description_'.$i,$getEEProw['equipment_description'], ['class'=>'form-control','id'=>'Equipment_description_'.$i,'onchange'=>"ChangeRowValue('Equipment',".$i.",1)"])}}</td>
                                        <td data-title="Cost:">{{Form::text('Equipment_cost_'.$i,number_format($getEEProw['equipment_cost'],2), ['class'=>'form-control','id'=>'Equipment_cost_'.$i,'onchange'=>"calc_margin('Equipment','".$i."',1,2)"])}}</td>
                                        <td data-title="Sell Price:">{{Form::text('Equipment_sell_price_cost_'.$i,(($getEEProw['equipment_sell_price_cost']=='0.00')?'':number_format($getEEProw['equipment_sell_price_cost'],2)), ['class'=>'form-control','id'=>'Equipment_sell_price_cost_'.$i,'onchange'=>"calc_margin('Equipment','".$i."',1,1)"])}}</td>
                                        <td data-title="Margin:">{{Form::text('Equipment_margin_percent_'.$i,(($getEEProw['equipment_margin_percent']=='0.00')?'':number_format($getEEProw['equipment_margin_percent'],2)), ['class'=>'form-control','id'=>'Equipment_margin_percent_'.$i,'onchange'=>'calc_margin("Equipment",'.$i.',1,'.$i.')'])}}</td>
                                        <td data-title="Eqp Sell Price:">{{Form::text('Equipment_sell_price_'.$i,number_format($getEEProw['equipment_sell_price'],2), ['class'=>'form-control','id'=>'Equipment_sell_price_'.$i,'readOnly'])}}</td>
                                        <td data-title="">{{Form::text('Equipment_total_cost_'.$i,number_format($getEEProw['equipment_total_cost'],2), ['class'=>'form-control','id'=>'Equipment_total_cost_'.$i,'readOnly'])}}</td>
                                        <td data-title="Margin $:">{{Form::text('Equipment_margin_'.$i,number_format($getEEProw['equipment_margin'],2), ['class'=>'form-control','id'=>'Equipment_margin_'.$i,'readOnly'])}}</td>
                                        <td data-title="Taxable:">{{Form::checkbox('Equipment_include_tax_'.$i,'1',($getEEProw['equipment_include_tax']=='1')?1:0, array('id'=>'Equipment_include_tax_'.$i,'class' => 'input-group','style'=>'display:inline;','onclick'=>"calculate_tax();")) }}</td>
                                        <td data-title="Inc. in Bill:">{{Form::checkbox('Equipment_consolidate_'.$i,$getEEProw['id'],'', array('id'=>'Equipment_consolidate_'.$i,'class' => 'input-group','style'=>'display:inline;','disabled'))}}</td>
                                        <td data-title="Order:">{{Form::text('Equipment_order_'.$i,$getEEProw['equipment_order'], ['class'=>'form-control','id'=>'Equipment_order_'.$i])}}</td>
                                        </tr>
                                        @endforeach
                                        <tr><td colspan="7">{{Form::button('<i class="fa fa-plus-circle"></i>', array('class' => 'btn btn-success btn-xs','id'=>'add_another_row'))}}</td>
                                        <td>{{Form::text('Equipment_sell_price_total',(isset($Totals['equipment_sell_price_total'])?number_format($Totals['equipment_sell_price_total'],2):''), ['readonly','class'=>'form-control','id'=>'Equipment_sell_price_total'])}}</td>
                                          <td>{{Form::text('Equipment_total_cost_total',(isset($Totals['equipment_total_cost_total'])?number_format($Totals['equipment_total_cost_total'],2):''), ['readonly','class'=>'form-control','id'=>'Equipment_total_cost_total'])}}</td>
                                          <td>{{Form::text('Equipment_margin_total',(isset($Totals['equipment_margin_total'])?number_format($Totals['equipment_margin_total'],2):''), ['readonly','class'=>'form-control','id'=>'Equipment_margin_total'])}}</td><td></td><td></td><td></td>
                                        </tr>
                                        <input type="hidden" value="<?php echo $i;?>" name="genCounter_id" id="genCounter_id">
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
                                     <table class="table table-bordered table-striped table-condensed cf" id="Labor">
                                        <thead class="cf">
                                        <tr><th colspan="11">Labor</th></tr>
                                        <tr><th>Del</th><th>Quantity</th><th>Description</th><th>Cost:{{Form::text('labor_cost','', ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'labor_cost'])}}</th><th>Sell Price</th><th>Margin:{{Form::text('labor_margin','', ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'labor_margin'])}}</th><th>Calculated Sell Price</th><th>Total Cost</th><th>Margin $</th><th>Inc. in Bill of Mat.</th><th>Order</th></tr>
                                        </thead>
                                        <tbody class="cf">
                                        <?php $i=0;?>
                                        @foreach($getELP as $getELProw)
                                        <tr><?php $i++;?>
                                         {{Form::hidden('Labor_id_'.$i,$getELProw['id'])}}
                                        <td data-title="Del">{{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-xs','name'=>'delete_elm_row','id'=>$getELProw['id'],'table'=>'labor'))}}</td>
                                        <td data-title="Lbr Qnty:">{{Form::text('Labor_quantity_'.$i,$getELProw['labor_quantity'], ['class'=>'form-control','id'=>'Labor_quantity_'.$i,'onchange'=>"ChangeRowValue('Labor',".$i.",1)"])}}</td>

                                        <td data-title="Eqp. Desc:">{{Form::text('Labor_description_'.$i,$getELProw['labor_description'], ['class'=>'form-control','id'=>'Labor_description_'.$i,'onchange'=>"ChangeRowValue('Labor',".$i.",1)"])}}</td>

                                        <td data-title="Cost:">{{Form::text('Labor_cost_'.$i,$getELProw['labor_cost'], ['class'=>'form-control','id'=>'Labor_cost_'.$i,'onchange'=>"calc_margin('Labor','".$i."',1,".$i.")"])}}</td>

                                        <td data-title="Sell Price:">{{Form::text('Labor_sell_price_cost_'.$i,(($getELProw['labor_sell_price_cost']=='0.00')?'':number_format($getELProw['labor_sell_price_cost'],2)), ['class'=>'form-control','id'=>'Labor_sell_price_cost_'.$i,'onchange'=>"calc_margin('Labor','".$i."',1,1)"])}}</td>

                                        <td data-title="Margin:">{{Form::text('Labor_margin_percent_'.$i,(($getELProw['labor_margin_percent']=='0.00')?'':$getELProw['labor_margin_percent']), ['class'=>'form-control','id'=>'Labor_margin_percent_'.$i,'onchange'=>"calc_margin('Labor','".$i."',1,".$i.")"])}}</td>

                                        <td data-title="Eqp Sell Price:">{{Form::text('Labor_sell_price_'.$i,number_format($getELProw['labor_sell_price'],2), ['class'=>'form-control','id'=>'Labor_sell_price_'.$i,'readOnly'])}}</td>
                                        <td data-title="">{{Form::text('Labor_total_cost_'.$i,number_format($getELProw['labor_total_cost'],2), ['class'=>'form-control','id'=>'Labor_total_cost_'.$i,'readOnly'])}}</td>

                                        <td data-title="Margin $:">{{Form::text('Labor_margin_'.$i,number_format($getELProw['labor_margin'],2), ['class'=>'form-control','id'=>'Labor_margin_'.$i,'readOnly'])}}</td>

                                        <td data-title="Inc. in Bill:">{{Form::checkbox('Labor_consolidate_'.$i,$getELProw['id'],'', array('id'=>'Labor_consolidate_'.$i,'class' => 'input-group','style'=>'display:inline;','disabled'))}}</td>

                                        <td data-title="Order:">{{Form::text('Labor_order_'.$i,$getELProw['labor_order'], ['class'=>'form-control','id'=>'Labor_order_'.$i])}}</td>
                                        </tr>
                                        @endforeach
                                        <tr><td colspan="6">{{Form::button('<i class="fa fa-plus-circle"></i>', array('class' => 'btn btn-success btn-xs','id'=>'add_new_row'))}}</td>
                                        <td>{{Form::text('Labor_sell_price_total',(isset($Totals['labor_sell_price_total'])?number_format($Totals['labor_sell_price_total'],2):''), ['class'=>'form-control','id'=>'Labor_sell_price_total','readonly'])}}</td>
                                          <td>{{Form::text('Labor_total_cost_total',(isset($Totals['labor_total_cost_total'])?number_format($Totals['labor_total_cost_total'],2):''), ['readonly','class'=>'form-control','id'=>'Labor_total_cost_total'])}}</td>
                                          <td>{{Form::text('Labor_margin_total',(isset($Totals['labor_margin_total'])?number_format($Totals['labor_margin_total'],2):''), ['readonly','class'=>'form-control','id'=>'Labor_margin_total'])}}</td><td></td><td></td>
                                        </tr>
                                        <input type="hidden" value="<?php echo $i;?>" name="labCounter_id" id="labCounter_id">
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
                                     <table class="table table-bordered table-striped table-condensed cf" id="Misc">
                                        <thead class="cf">
                                        <tr><th colspan="13">Misc</th></tr>
                                        <tr><th>Del</th><th>Vendor</th><th>Quantity</th><th>Description</th><th>Cost:{{Form::text('misc_cost','', ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'misc_cost'])}}</th><th>Sell Price</th><th>Margin:{{Form::text('misc_margin','', ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'misc_margin'])}}</th><th>Calculated Sell Price</th><th>Total Cost</th><th>Margin $</th><th>Taxable?</th><th>Inc. in Bill of Mat.</th><th>Order</th></tr>
                                        </thead>
                                        <tbody class="cf">
                                        <?php $i=0;?>
                                        @foreach($getEMP as $getEMProw)
                                        <tr><?php $i++;?>
                                         {{Form::hidden('Misc_id_'.$i,$getEMProw['id'])}}
                                        <td data-title="Del">{{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-xs','name'=>'delete_elm_row','id'=>$getEMProw['id'],'table'=>'misc'))}}</td>
                                        <td data-title="">
                                          {{Form::select('Misc_vendor_'.$i,$gpg_vendor,$getEMProw['gpg_vendor_id'], ['id' => 'Misc_vendor_'.$i, 'class'=>'form-control m-bot15'])}}
                                        </td>
                                        <td data-title="Eqp Qnty:">{{Form::text('Misc_quantity_'.$i,$getEMProw['misc_quantity'], ['class'=>'form-control','id'=>'Misc_quantity_'.$i,'onchange'=>"ChangeRowValue('Misc',".$i.",1)"])}}</td>
                                        <td data-title="Eqp. Desc:">{{Form::text('Misc_description_'.$i,$getEMProw['misc_description'], ['class'=>'form-control','id'=>'Misc_description_'.$i,'onchange'=>"ChangeRowValue('Misc',".$i.",1)"])}}</td>
                                        <td data-title="Cost:">{{Form::text('Misc_cost_'.$i,$getEMProw['misc_cost'], ['class'=>'form-control','id'=>'Misc_cost_'.$i,'onchange'=>"calc_margin('Misc','".$i."',1,".$i.")"])}}</td>
                                        <td data-title="Sell Price:">{{Form::text('Misc_sell_price_cost_'.$i,(($getEMProw['misc_sell_price_cost']=='0.00')?'':number_format($getEMProw['misc_sell_price_cost'],2)), ['class'=>'form-control','id'=>'Misc_sell_price_cost_'.$i,'onchange'=>"calc_margin('Misc','".$i."',1,1)"])}}</td>
                                        <td data-title="Margin:">{{Form::text('Misc_margin_percent_'.$i,(($getEMProw['misc_margin_percent']=='0.00')?'':$getEMProw['misc_margin_percent']), ['class'=>'form-control','id'=>'Misc_margin_percent_'.$i,'onchange'=>"calc_margin('Misc','".$i."',1,".$i.")"])}}</td>
                                        <td data-title="Eqp Sell Price:">{{Form::text('Misc_sell_price_'.$i,number_format($getEMProw['misc_sell_price'],2), ['class'=>'form-control','id'=>'Misc_sell_price_'.$i,'readOnly'])}}</td>
                                        <td data-title="">{{Form::text('Misc_total_cost_'.$i,number_format($getEMProw['misc_total_cost'],2), ['class'=>'form-control','id'=>'Misc_total_cost_'.$i,'readOnly'])}}</td>
                                        <td data-title="Margin $:">{{Form::text('Misc_margin_'.$i,number_format($getEMProw['misc_margin'],2), ['class'=>'form-control','id'=>'Misc_margin_'.$i,'readOnly'])}}</td>
                                        <td data-title="Taxable:">{{Form::checkbox('Misc_include_tax_'.$i,'1',($getEMProw['misc_include_tax']=='1')?1:0, array('id'=>'Misc_include_tax_'.$i,'class' => 'input-group','style'=>'display:inline;','onclick'=>"calculate_tax();")) }}</td>
                                        <td data-title="Inc. in Bill:">{{Form::checkbox('Misc_consolidate_'.$i,$getEMProw['id'],'', array('id'=>'Misc_consolidate_'.$i,'class' => 'input-group','style'=>'display:inline;','disabled'))}}</td>
                                        <td data-title="Order:">{{Form::text('Misc_order_'.$i,$getEMProw['misc_order'], ['class'=>'form-control','id'=>'Misc_order_'.$i])}}</td>
                                        </tr>
                                        @endforeach
                                        <tr><td colspan="7">{{Form::button('<i class="fa fa-plus-circle"></i>', array('class' => 'btn btn-success btn-xs','id'=>'add_new_misc_row'))}}</td>
                                        <td>{{Form::text('Misc_sell_price_total',(isset($Totals['misc_sell_price_total'])?number_format($Totals['misc_sell_price_total'],2):''), ['readonly','class'=>'form-control','id'=>'Misc_sell_price_total'])}}</td>
                                          <td>{{Form::text('Misc_total_cost_total',(isset($Totals['misc_total_cost_total'])?number_format($Totals['misc_total_cost_total'],2):''), ['readonly','class'=>'form-control','id'=>'misc_total_cost_total'])}}</td>
                                          <td>{{Form::text('Misc_margin_total',(isset($Totals['misc_margin_total'])?number_format($Totals['misc_margin_total'],2):''), ['readonly','class'=>'form-control','id'=>'Misc_margin_total'])}}</td><td></td><td></td><td></td>
                                        </tr>
                                        <?php
                                        $grand_total_sale_price  = (isset($Totals['equipment_sell_price_total'])?$Totals['equipment_sell_price_total']:0)+(isset($Totals['labor_sell_price_total'])?$Totals['labor_sell_price_total']:0)+(isset($Totals['misc_sell_price_total'])?$Totals['misc_sell_price_total']:0);
                                        $grand_total_cost = (isset($Totals['equipment_total_cost_total'])?$Totals['equipment_total_cost_total']:0)+(isset($Totals['labor_total_cost_total'])?$Totals['labor_total_cost_total']:0)+(isset($Totals['misc_total_cost_total'])?$Totals['misc_total_cost_total']:0);
                                        $grand_total_margin = (isset($Totals['equipment_margin_total'])?$Totals['equipment_margin_total']:0) + (isset($Totals['labor_margin_total'])?$Totals['labor_margin_total']:0) + (isset($Totals['misc_margin_total'])?$Totals['misc_margin_total']:0);
                                        $sale_price_tax = ((isset($Totals['sales_tax'])?$Totals['sales_tax']:0)/100) * $TotalTaxable;
                                        ?>
                                        <input type="hidden" value="<?php echo $i;?>" name="miscCounter_id" id="miscCounter_id">
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
                                        <tr><td> Scope Of Work</td><td>Exclusions</td><td>SUBTOTAL</td><td > {{Form::text('Grand_total_sale_price',number_format($grand_total_sale_price,2), ['class'=>'form-control','id'=>'Grand_total_sale_price','readOnly'])}}</td><td > {{Form::text('Grand_total_cost',number_format($grand_total_cost,2), ['class'=>'form-control','id'=>'Grand_total_cost','readOnly'])}}</td><td > {{Form::text('Grand_total_margin',number_format($grand_total_margin,2), ['class'=>'form-control','id'=>'Grand_total_margin','readOnly'])}}</td></tr>
                                        <tr><td rowspan="3">{{ Form::textarea('_scope_of_work', $jobElectricalQuoteTblRow['scope_of_work'],['class'=>'form-control']) }}</td><td rowspan="3">{{ Form::textarea('_exclusions', $jobElectricalQuoteTblRow['exclusions'],['class'=>'form-control']) }}</td><td>SALES TAX {{Form::text('sales_tax',(isset($Totals['sales_tax'])?$Totals['sales_tax']:0), ['onchange'=>"calculate_tax()",'class'=>'form-control','style'=>'width:50px; display:inline;','id'=>'sales_tax'])}}</td><td >{{Form::text('sale_price_tax',number_format($sale_price_tax), ['class'=>'form-control','id'=>'sale_price_tax','readOnly'])}}</td><td>{{Form::text('cost_tax',number_format($sale_price_tax), ['class'=>'form-control','id'=>'cost_tax','readOnly'])}}</td><td>{{Form::text('margin_tax','', ['class'=>'form-control','id'=>'margin_tax','readOnly'])}}</td></tr>

                                        <tr><td>SUBTOTAL:</td><td>{{Form::text('tax_total_sale_price',number_format($sale_price_tax + $grand_total_sale_price,2), ['class'=>'form-control','id'=>'tax_total_sale_price'])}}</td><td>{{Form::text('tax_total_cost',number_format($sale_price_tax + $grand_total_cost,2), ['class'=>'form-control','id'=>'tax_total_cost'])}}</td><td>{{Form::text('tax_total_margin',number_format($grand_total_margin,2), ['class'=>'form-control','id'=>'tax_total_margin'])}}</td></tr>
                                         
                                         <tr><td colspan="4">{{HTML::link('#myModal4', 'Manage Attachments' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'manage_files'))}}</td></tr>
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
  <div class="btn-group">
   {{Form::button('Save/Update Changes', array('class' => 'btn btn-primary', 'id'=>'submit_main_form'))}}
  {{Form::close()}}
  {{ HTML::link("quote/excelEquipQuoteExport?table=$table&id=$job_id&j_num=$job_num", 'Export Excel' , array('class'=>'btn btn-success'))}}
  {{ Form::button('Export PDF' , array('id'=>'getElecticalQuotePdfFile','class'=>'btn btn-danger'))}} 
  </div>
</section>
{{HTML::link('#myModal', '' , array('class' => 'btn btn-link','data-toggle'=>'modal','id'=>'click_modal'))}}

                        <!-- Modal# 2-->
           <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ATTACHMENT MANAGEMENT: [{{$job_num}}]</h4>
                      </div>
                    <div class="modal-body">
                 {{Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('quote/manageQuoteFiles'),'files'=>true, 'method' => 'post')) }}   
                 {{Form::hidden('fjob_id',$job_id)}} {{Form::hidden('fjob_num',$job_num)}}     <div class="form-group">
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
   <script src="{{asset('js/job/job_quote_equip_pricing_frm.js')}}"></script>
   <script type="text/javascript">
  var job_num = '<?php echo $job_num;?>';
  if(job_num.charAt(0) == 'M' || job_num.charAt(0) == 'J')
    $('#hide_selects').hide();
  else
    $('#hide_selects').show();
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
$('#update_elec_equip_pricing_frm').attr('action','{{URL::to("quote/updateElectricQuotePricingFrm")}}').submit();
});
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
  var counter=parseInt('1')+parseInt($('#genCounter_id').val());
  $('#add_another_row').click(function(){
    var selector = '{{$vendor_select}}';  
    if (document.getElementById("Equipment_vendor_1") != null){
        selector = document.getElementById("Equipment_vendor_1").innerHTML;
    }
       var str = '<tr><td data-title="Del"><button type="button" id="" name="delete_elm_row" table="equipment" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button></td>';
          str +='<td data-title=""><select name="Equipment_vendor_'+counter+'" class="form-control m-bot15" id="Equipment_vendor_'+counter+'">'+selector+'</select></td>';
          str +='<td data-title="Eqp Qnty:"><input type="text" value="" name="Equipment_quantity_'+counter+'" onchange="ChangeRowValue('+"'Equipment'"+','+counter+',1)" id="Equipment_quantity_'+counter+'" class="form-control"></td>';
          str +='<td data-title="Eqp. Desc:"><input type="text" value="" name="Equipment_description_'+counter+'" onchange="ChangeRowValue('+"'Equipment'"+','+counter+',1)" id="Equipment_description_'+counter+'" class="form-control"></td>';
          str +='<td data-title="Cost:"><input type="text" value="" name="Equipment_cost_'+counter+'" onchange="calc_margin('+"'Equipment'"+','+counter+',1,2)" id="Equipment_cost_'+counter+'" class="form-control"></td>';
          str +='<td data-title="Sell Price:"><input type="text" value="" name="Equipment_sell_price_cost_'+counter+'" onchange="calc_margin('+"'Equipment'"+','+counter+',1,1)" id="Equipment_sell_price_cost_'+counter+'" class="form-control"></td>';
          str +='<td data-title="Margin:"><input type="text" value="" name="Equipment_margin_percent_'+counter+'" onchange="calc_margin('+"'Equipment'"+','+counter+',1,'+counter+')" id="Equipment_margin_percent_'+counter+'" class="form-control"></td>';
          str +='<td data-title="Eqp Sell Price:"><input type="text" value="" name="Equipment_sell_price_'+counter+'" readonly="readOnly" id="Equipment_sell_price_'+counter+'" class="form-control"></td>';
          str +='<td data-title=""><input type="text" value="" name="Equipment_total_cost_'+counter+'" readonly="readOnly" id="Equipment_total_cost_'+counter+'" class="form-control"></td>';
          str +='<td data-title="Margin $:"><input type="text" value="" name="Equipment_margin_'+counter+'" readonly="readOnly" id="Equipment_margin_'+counter+'" class="form-control"></td>';
          str +='<td data-title="Taxable:"><input type="checkbox" value="1" name="Equipment_include_tax_'+counter+'" checked="checked" onclick="calculate_tax();" style="display:inline;" class="input-group" id="Equipment_include_tax_'+counter+'"></td>';
          str +='<td data-title="Inc. in Bill:"><input type="checkbox" value="" name="Equipment_consolidate_'+counter+'" disabled="disabled" style="display:inline;" class="input-group" id="Equipment_consolidate_'+counter+'"></td>';
          str +='<td data-title="Order:"><input type="text" value="" name="Equipment_order_'+counter+'" id="Equipment_order_'+counter+'" class="form-control"></td></tr>';

           $('#Equipment tr').eq(-1).before(str);
           $('#genCounter_id').val(parseInt($('#genCounter_id').val())+parseInt('1'));
           counter = parseInt(counter) + parseInt("1");
  });

  var lab_counter=parseInt('1')+parseInt($('#labCounter_id').val());
    $('#add_new_row').click(function(){
    var str1 ='<tr><td data-title="Del"><button type="button" table="labor" id="" name="delete_elm_row" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button></td>';
        str1 += '<td data-title="Lbr Qnty:"><input type="text" value="" name="Labor_quantity_'+lab_counter+'" onchange="ChangeRowValue('+"'Labor'"+','+lab_counter+',1)" id="Labor_quantity_'+lab_counter+'" class="form-control"></td>';  
        str1 += '<td data-title="Eqp. Desc:"><input type="text" value="" name="Labor_description_'+lab_counter+'" onchange="ChangeRowValue('+"'Labor'"+','+lab_counter+',1)" id="Labor_description_'+lab_counter+'" class="form-control"></td>';  
        str1 += '<td data-title="Cost:"><input type="text" value="" name="Labor_cost_'+lab_counter+'" onchange="calc_margin('+"'Labor'"+','+lab_counter+',1,'+lab_counter+')" id="Labor_cost_'+lab_counter+'" class="form-control"></td>';  
        str1 += '<td data-title="Sell Price:"><input type="text" value="" name="Labor_sell_price_cost_'+lab_counter+'" onchange="calc_margin('+"'Labor'"+','+lab_counter+',1,1)" id="Labor_sell_price_cost_'+lab_counter+'" class="form-control"></td>';  
        str1 += '<td data-title="Margin:"><input type="text" value="" name="Labor_margin_percent_'+lab_counter+'" onchange="calc_margin('+"'Labor'"+','+lab_counter+',1,'+lab_counter+')" id="Labor_margin_percent_'+lab_counter+'" class="form-control"></td>';  
        str1 += '<td data-title="Eqp Sell Price:"><input type="text" value="" name="Labor_sell_price_'+lab_counter+'" readonly="readOnly" id="Labor_sell_price_'+lab_counter+'" class="form-control"></td>';  
        str1 += '<td data-title=""><input type="text" value="" name="Labor_total_cost_'+lab_counter+'" readonly="readOnly" id="Labor_total_cost_'+lab_counter+'" class="form-control"></td>';  
        str1 += '<td data-title="Margin $:"><input type="text" value="" name="Labor_margin_'+lab_counter+'" readonly="readOnly" id="Labor_margin_'+lab_counter+'" class="form-control"></td>';  
        str1 += '<td data-title="Inc. in Bill:"><input type="checkbox" value="" name="Labor_consolidate_'+lab_counter+'" disabled="disabled" style="display:inline;" class="input-group" id="Labor_consolidate_'+lab_counter+'"></td>';  
        str1 += ' <td data-title="Order:"><input type="text" value="" name="Labor_order_'+lab_counter+'" id="Labor_order_'+lab_counter+'" class="form-control"></td></tr>';     
    $('#Labor tr').eq(-1).before(str1);
    $('#labCounter_id').val(parseInt($('#labCounter_id').val())+parseInt('1'));
    lab_counter = parseInt(lab_counter) + parseInt("1");
  });
  var misc_counter=parseInt('1')+parseInt($('#miscCounter_id').val());
  $('#add_new_misc_row').click(function(){
    var selector = '{{$vendor_select}}';
    var str2 = '<tr><td data-title="Del"><button type="button" id="" name="delete_elm_row" table="misc" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button></td>';
        str2 +='<td data-title=""><select name="Misc_vendor_'+misc_counter+'" class="form-control m-bot15" id="Misc_vendor_'+misc_counter+'">'+ (document.getElementById("Misc_vendor_1") != null?document.getElementById("Misc_vendor_1").innerHTML:selector)+'</select></td>';
        str2 +='<td data-title="Eqp Qnty:"><input type="text" value="" name="Misc_quantity_'+misc_counter+'" onchange="ChangeRowValue('+"'Misc'"+','+misc_counter+',1)" id="Misc_quantity_'+misc_counter+'" class="form-control"></td>';
        str2 +='<td data-title="Eqp. Desc:"><input type="text" value="" name="Misc_description_'+misc_counter+'" onchange="ChangeRowValue('+"'Misc'"+','+misc_counter+',1)" id="Misc_description_'+misc_counter+'" class="form-control"></td>';
        str2 +='<td data-title="Cost:"><input type="text" value="" name="Misc_cost_'+misc_counter+'" onchange="calc_margin('+"'Misc'"+','+misc_counter+',1,'+misc_counter+')" id="Misc_cost_'+misc_counter+'" class="form-control"></td>';
        str2 +='<td data-title="Sell Price:"><input type="text" value="" name="Misc_sell_price_cost_'+misc_counter+'" onchange="calc_margin('+"'Misc'"+','+misc_counter+',1,1)" id="Misc_sell_price_cost_'+misc_counter+'" class="form-control"></td>';
        str2 +='<td data-title="Margin:"><input type="text" value="" name="Misc_margin_percent_'+misc_counter+'" onchange="calc_margin('+"'Misc'"+','+misc_counter+',1,'+misc_counter+')" id="Misc_margin_percent_'+misc_counter+'" class="form-control"></td>';
        str2 +='<td data-title="Eqp Sell Price:"><input type="text" value="" name="Misc_sell_price_'+misc_counter+'" readonly="readOnly" id="Misc_sell_price_'+misc_counter+'" class="form-control"></td>';
        str2 +='<td data-title=""><input type="text" value="" name="Misc_total_cost_'+misc_counter+'" readonly="readOnly" id="Misc_total_cost_'+misc_counter+'" class="form-control"></td>';
        str2 +='<td data-title="Margin $:"><input type="text" value="" name="Misc_margin_'+misc_counter+'" readonly="readOnly" id="Misc_margin_'+misc_counter+'" class="form-control"></td>';
        str2 +='<td data-title="Taxable:"><input type="checkbox" value="1" name="Misc_include_tax_'+misc_counter+'" checked="checked" onclick="calculate_tax();" style="display:inline;" class="input-group" id="Misc_include_tax_'+misc_counter+'"></td>';
        str2 +='<td data-title="Inc. in Bill:"><input type="checkbox" value="" name="Misc_consolidate_'+misc_counter+'" disabled="disabled" style="display:inline;" class="input-group" id="Misc_consolidate_'+misc_counter+'"></td>';
        str2 +='<td data-title="Order:"><input type="text" value="" name="Misc_order_'+misc_counter+'" id="Misc_order_'+misc_counter+'" class="form-control"></td></tr>';

           $('#Misc tr').eq(-1).before(str2);
           $('#miscCounter_id').val(parseInt($('#miscCounter_id').val())+parseInt('1'));
           misc_counter = parseInt(misc_counter) + parseInt("1");
  });
  $('#save_update_data').click(function(){
    $("#edit_update_gen_costs").submit(); //Submit the form
  });

  $('#submit_attachments').click(function(){
        $('#submit_file_form').submit();
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
                          'table':'<?php echo $table ?>'
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
              $('a[name=dld_quote_file]').click(function(){
                var id = $(this).attr('id');
                $('#download_id').val(id);
                $('#download_file').submit();
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
    window.location.href = '{{URL::to("job/job_electrical_equipment_pricing_frm/'+jid+'/'+jnum+'")}}';
  });

  $('button[name=delete_elm_row]').click(function(){
      var id = $(this).attr('id');
      var table = $(this).attr('table');
      conf = confirm('Are you sure? You want to delete this ...?');
      if(conf && id!=''){
        $.ajax({
           url: "{{URL('ajax/deleteEquipPricingLine')}}",
           data: {
            'id': id,
            'table':table,
            'jid': '<?php echo $job_id;?>'
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
  $('#getElecticalQuotePdfFile').click(function(){
      var table = '<?php echo $table;?>';
      if(table == 'grassivy' || table == 'special_project'){
        $('#update_elec_equip_pricing_frm').attr('action','{{URL::to("quote/getEquipPricingPdfFile?table=$table")}}').submit();
      }else{
        $('#update_elec_equip_pricing_frm').attr('action','{{URL::to("quote/getElecticalQuotePdfFile")}}').submit();
      }
  });
   </script>
  </body>
</html>
