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
  <body class="full-width" onload="init_month()">

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
                    {{Form::select('copyFieldServiceId',array(''=>'Select Field Service Work')+$fs_data,'', ['id'=>'copyFieldServiceId','class'=>'form-control m-bot15','style'=>'display:inline;'])}}
                    &nbsp;&nbsp;{{Form::button('COPY FIELD SERVICE WORK',array('id'=>'copy_quote_data_confirm','style'=>'display:inline;','class'=>'btn btn-default'))}}
                    <sub><b>Note:</b> Please make sure before copy a field service work field, all the data on this sheet will be replaced by the selected field service work data.<span style='color:red;'>(THIS CHANGE COULD NOT BE ROLLED BACK.)</span></sub> 
                  </section>
                </div>
                <div class="col-lg-3">
                <br/><br/><br/><br/>  
                  <section class="panel">
                    {{Form::button('RENEW CONTRACT',array('id'=>'renew_contract','style'=>'display:inline; margin:5px;','class'=>'btn btn-default btn-xs'))}}
                    {{Form::button('GENERATE DUPLICATE CONTRACT',array('id'=>'generate_duplicate_contract','style'=>'display:inline; margin:5px;','class'=>'btn btn-default  btn-xs'))}}
                    {{Form::button('SWITCH TO CONSUM CONTRACT COST',array('id'=>'switch_to_contract_cost','style'=>'display:inline; margin:5px;','class'=>'btn btn-default  btn-xs'))}}
                    {{ HTML::link('contract/getConsumerContractFile/'.$job_id.'', 'SHOW CONSUM CONTRACT DOC' , array('class'=>'btn btn-danger btn-xs','style'=>'display:inline; margin:5px;'))}} 
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
      <!--main content start-->
     <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
       {{Form::open(array('method' => 'POST','id'=>'update_consumer_contract_form','files'=>true,'route' => array('contract/updateConsumContractForm')))}} 
     <section id="main-content">
      <section id="wrapper">
         <section class="panel">
          <div class="panel-body">
              <!-- page start-->
            {{ Form::hidden('job_id',$job_id)}}
            <div class="row">
            <div class="col-lg-12">
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
              <section id="no-more-tables">
                 <table class="table table-bordered table-striped table-condensed cf">
                  <tbody class="cf">
                   <tr>
                    <td data-title="Job Number:">Contract Number#: {{ Form::text('job_num',$job_num, array('class' => 'form-control', 'id' => 'job_num','readOnly')) }}</td>
                    <td data-title="Go to:">Attach Contract#:{{ Form::select('GPG_attach_contract_number',array(''=>'Select Contract')+$cn_data,isset($consumContractTblRow['GPG_attach_contract_number'])?$consumContractTblRow['GPG_attach_contract_number']:'', array('class' => 'form-control','id' => 'GPG_attach_contract_number')) }}</td>  
                    <td data-title="Po Number:">Sales Person:{{ Form::select('salePersonId',array(''=>'Sales Person')+$emp_data,isset($consumContractTblRow['GPG_employee_id'])?$consumContractTblRow['GPG_employee_id']:'', array('class' => 'form-control','id' => 'salePersonId')) }}</td> 
                    <td data-title="Prob.%:">Site:{{ Form::text('site',isset($consumContractTblRow['site'])?$consumContractTblRow['site']:'', array('class' => 'form-control','id' => 'site')) }}</td>
                    <td data-title="Prob.%:">Of:{{ Form::text('siteOf',isset($consumContractTblRow['site_of'])?$consumContractTblRow['site_of']:'', array('class' => 'form-control','id' => 'siteOf')) }}</td>
                    <td data-title="Status:">Status:<br/> <span style="color:red; font-weight:bold;"> <?php echo $resSalesTrackingRowtbl!=""?"Renewed - ":""?><?php echo isset($consumContractTblRow["consum_contract_status"])?$consumContractTblRow["consum_contract_status"]:'';?></span></td>     
                    <td data-title="Date:">Won Date:{{ Form::text('dateJobWon',(isset($consumContractTblRow['date_job_won']) && $consumContractTblRow['date_job_won']!=''?date('Y-m-d',strtotime($consumContractTblRow['date_job_won'])):""), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'dateJobWon')) }}</td>
                    <td data-title="Date:">Schedule Date:{{ Form::text('scheduleDate',(isset($consumContractTblRow['schedule_date']) && $consumContractTblRow['schedule_date']!=""?date('Y-m-d',strtotime($consumContractTblRow['schedule_date'])):date('Y-m-d')), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'scheduleDate')) }}</td>
                   </tr>
                  </tbody>
                 </table>
                </section>
              </div>  
              </div>
              </div>
              </section>
              
                <div class="row">
                 
                  <div class="col-lg-3">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                        Billing Info [{{Form::button('FILL JOB SITE INFO', array('onClick'=>'autoFill()','class' => 'btn btn-link btn-xs'))}}]
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">Customer Name:</td><td>{{Form::select('customerBillto',array(''=>'Select Customer')+$customers,$consumContractTblRow['GPG_customer_id'], ['class'=>'form-control','id'=>'customerBillto'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Address 1:</td><td>{{ Form::text('cusAddress1','', array('class' => 'form-control', 'id' => 'cusAddress1')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Address 2:</td><td>{{ Form::text('cusAddress2','', array('class' => 'form-control', 'id' => 'cusAddress2')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('cusCity','', array('class' => 'form-control', 'id' => 'cusCity')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('cusState','', array('class' => 'form-control', 'id' => 'cusState')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('cusZip','', array('class' => 'form-control', 'id' => 'cusZip')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Attn:</td><td>{{ Form::text('cusAtt','', array('class' => 'form-control', 'id' => 'cusAtt')) }}</td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>        
                          </div>
                        </div>
                      </section>
                    </div>
                     <div class="col-lg-3">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Job Site Info [{{Form::button('COPY CUSTOMER DATA', array('onClick'=>'backFill()','class' => 'btn btn-link btn-xs'))}}]
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">Location:</td><td>{{Form::text('_location',$consumContractEqpTblRow['location'], ['class'=>'form-control','id'=>'_location'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Address 1:</td><td>{{ Form::text('_address1',$consumContractEqpTblRow['address1'], array('class' => 'form-control', 'id' => '_address1')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Address 2:</td><td>{{ Form::text('_address2',$consumContractEqpTblRow['address2'], array('class' => 'form-control', 'id' => '_address2'))}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('_city',$consumContractEqpTblRow['city'], array('class' => 'form-control', 'id' => '_city'))}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('_state',$consumContractEqpTblRow['state'], array('class' => 'form-control', 'id' => '_state'))}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('_zip',$consumContractEqpTblRow['zip'], array('class' => 'form-control', 'id' => '_zip'))}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Attn:</td><td>{{ Form::text('_attn',$consumContractEqpTblRow['attn'], array('class' => 'form-control', 'id' => '_attn'))}}</td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>
                          </div>
                        </div>
                      </section>
                    </div>
                  <div class="col-lg-3">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                        Primary Contact Info
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">Name:</td><td>{{Form::text('priContactName',$consumContractTblRow['pri_contact_name'], ['class'=>'form-control','id'=>'priContactName'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('priContactPhone',$consumContractTblRow['pri_contact_phone'], array('class' => 'form-control', 'id' => 'priContactPhone')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Fax:</td><td>{{ Form::text('priContactFax',$consumContractTblRow['pri_contact_fax'], array('class' => 'form-control', 'id' => 'priContactFax')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Cell:</td><td>{{ Form::text('priContactCell',$consumContractTblRow['pri_contact_cell'], array('class' => 'form-control', 'id' => 'priContactCell')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Email:</td><td>{{ Form::text('priContactEmail',$consumContractTblRow['pri_contact_email'], array('class' => 'form-control', 'id' => 'priContactEmail')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Title:</td><td>{{ Form::text('priContactTitle',$consumContractTblRow['pri_contact_title'], array('class' => 'form-control', 'id' => 'priContactTitle')) }}</td></tr>
                                        <tr><td colspan="2">&nbsp;</td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>        
                          </div>
                        </div>
                      </section>
                    </div>
                     <div class="col-lg-3">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                        Alternate Contact Info
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                       <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">Name:</td><td>{{Form::text('altContactName',$consumContractTblRow['alt_contact_name'], ['class'=>'form-control','id'=>'altContactName'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('altContactPhone',$consumContractTblRow['alt_contact_phone'], array('class' => 'form-control', 'id' => 'altContactPhone')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Fax:</td><td>{{ Form::text('altContactFax',$consumContractTblRow['alt_contact_fax'], array('class' => 'form-control', 'id' => 'altContactFax')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Cell:</td><td>{{ Form::text('altContactCell',$consumContractTblRow['alt_contact_cell'], array('class' => 'form-control', 'id' => 'altContactCell')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Email:</td><td>{{ Form::text('altContactEmail',$consumContractTblRow['alt_contact_email'], array('class' => 'form-control', 'id' => 'altContactEmail')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Title:</td><td>{{ Form::text('altContactTitle',$consumContractTblRow['alt_contact_title'], array('class' => 'form-control', 'id' => 'altContactTitle')) }}</td></tr>
                                        <tr><td colspan="2">&nbsp;</td></tr>
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
                                        Equipment Info [Generator or Fire Pump Info]
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td>Make:</td><td>{{Form::text('_make',$consumContractEqpTblRow['make'], ['class'=>'form-control','id'=>'_make'])}}</td></tr>
                                        <tr><td>Model:</td><td>{{Form::text('_model',$consumContractEqpTblRow['model'], ['class'=>'form-control','id'=>'_model'])}}</td></tr>
                                        <tr><td>Serial:</td><td>{{ Form::text('_serial',$consumContractEqpTblRow['serial'], array('class' => 'form-control', 'id' => '_serial')) }}</td></tr>
                                        <tr><td>Spec:</td><td>{{ Form::text('_spec',$consumContractEqpTblRow['spec'], array('class' => 'form-control', 'id' => '_spec')) }}</td></tr>
                                        <tr><td>KW:</td><td>{{ Form::text('_kw',$consumContractEqpTblRow['kw'], array('class' => 'form-control', 'id' => '_kw'))}}</td></tr>
                                        <tr><td>Phase:</td><td>{{ Form::text('_phase',$consumContractEqpTblRow['phase'], array('class' => 'form-control', 'id' => '_phase')) }}</td></tr>
                                        <tr><td>Volts:</td><td>{{ Form::text('_volts',$consumContractEqpTblRow['volts'], array('class' => 'form-control', 'id' => '_volts')) }}</td></tr>
                                        <tr><td>Amps:</td><td>{{ Form::text('_amps',$consumContractEqpTblRow['amps'], array('class' => 'form-control', 'id' => '_amps')) }}</td></tr>
                                        <tr><td>Generator Location:</td><td>{{ Form::text('_engine_level',$consumContractEqpTblRow['engine_level'], array('class' => 'form-control', 'id' => '_engine_level')) }}</td></tr>
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
                                        Engine Info [{{HTML::link('#myModal', 'Add Eqpiupment Details' , array('class' => 'btn btn-link','data-toggle'=>'modal','id'=>'click_modal'))}}]
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td>Make:</td><td>{{Form::text('engMake',$consumContractEqpTblRow['engMake'], ['class'=>'form-control','id'=>'engMake'])}}</td></tr>
                                        <tr><td>Model:</td><td>{{Form::text('engModel',$consumContractEqpTblRow['engModel'], ['class'=>'form-control','id'=>'engModel'])}}</td></tr>
                                        <tr><td>Serial:</td><td>{{ Form::text('engSerial',$consumContractEqpTblRow['engSerial'], array('class' => 'form-control', 'id' => 'engSerial')) }}</td></tr>
                                        <tr><td>Spec:</td><td>{{ Form::text('engSpec',$consumContractEqpTblRow['engSpec'], array('class' => 'form-control', 'id' => 'engSpec')) }}</td></tr>
                                        <tr><td>CPL/SO/OT:</td><td>{{ Form::text('engCplSoOT',$consumContractEqpTblRow['eng_cpl_so_ot'], array('class' => 'form-control', 'id' => 'engCplSoOT')) }}</td></tr>
                                        <tr><td>Fuel Capacity:</td><td>{{ Form::text('engFuelCap',$consumContractEqpTblRow['eng_fuel_capacity'], array('class' => 'form-control', 'id' => 'engFuelCap')) }}</td></tr>
                                        <tr><td>Oil Type:</td><td>{{ Form::text('engOilType',$consumContractEqpTblRow['eng_oil_type'], array('class' => 'form-control', 'id' => 'engOilType')) }}</td></tr>
                                        <tr><td>Oil Qty:</td><td>{{ Form::text('engOilQuantity',$consumContractEqpTblRow['eng_oil_quantity'], array('class' => 'form-control', 'id' => 'engOilQuantity')) }}</td></tr>
                                        <tr><td colspan="2">&nbsp;</td></tr>
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
                                          Contract Information
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr>
                                          <tr><td>Contract Type:</td><td>{{Form::select('contractType',$ContractTypeArray,(strlen($consumContractTblRow['consum_contract_type'])==0)?"2":$consumContractTblRow['consum_contract_type'], ['class'=>'form-control','id'=>'contractType','onchange'=>'calSchedule()'])}}</td></tr>
                                          <tr><td>Contract Start Date:</td><td>{{Form::text('contractSDate',($consumContractTblRow['consum_contract_start_date']!=""?date('Y-m-d',strtotime($consumContractTblRow['consum_contract_start_date'])):date('Y-m-d')), ['class'=>'form-control form-control-inline input-medium default-date-picker','id'=>'contractSDate','readOnly'])}}</td></tr>
                                          <tr><td>Contract End Date:</td><td>{{ Form::text('contractEDate',($consumContractTblRow['consum_contract_end_date']!=""?date('Y-m-d',strtotime($consumContractTblRow['consum_contract_end_date'])):date('Y-m-d')), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'contractEDate','readOnly')) }}</td></tr>
                                          <tr><td>PO Number:</td><td>{{ Form::text('poNumber',$consumContractTblRow['po_number'], array('class' => 'form-control', 'id' => 'poNumber')) }}</td></tr>
                                          <tr><td>PO Start Date:</td><td>{{ Form::text('poSDate',($consumContractTblRow['po_start_date']!=""?date('Y-m-d',strtotime($consumContractTblRow['po_start_date'])):date('Y-m-d')), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'poSDate','readOnly')) }}</td></tr>
                                          <tr><td>PO End Date:</td><td>{{ Form::text('poEDate',($consumContractTblRow['po_end_date']!=""?date('Y-m-d',strtotime($consumContractTblRow['po_end_date'])):date('Y-m-d')), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'poEDate','readOnly')) }}</td></tr>
                                          <tr><td>Contract Doc Terms and Contitions:</td><td>{{ Form::select('contract_terms',$terms,'', array('class' => 'form-control', 'id' => 'contract_terms')) }}</td></tr>
                                          <tr><td>Contract Doc Address:</td><td>{{ Form::select('address',$address,'', array('class' => 'form-control', 'id' => 'address')) }}</td></tr>
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
          <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         ATS Info {{Form::button('<i class="fa fa-plus-circle"></i>', array('class' => 'btn btn-success btn-xs','id'=>'add_new_row'))}}
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="atsTable">
                                        <thead class="cf">
                                          <tr>
                                            <th>Make</th><th>Model</th><th>Serial</th><th>Spec</th><th>Phase</th><th>Volts</th><th>Amps</th><th>Pole</th>
                                          </tr>
                                        </thead>
                                        <tbody class="cf">
                                        <tr>
                                          @if(!empty($ats_arr))
                                            <?php $i=0;?>
                                            @foreach($ats_arr as $atsDataRow)
                                              <td>{{ Form::text('atsMake_'.$i,$atsDataRow['make'], array('class' => 'form-control', 'id' => 'atsMake_'.$i)) }}</td>
                                              <td>{{ Form::text('atsModel_'.$i,$atsDataRow['model'], array('class' => 'form-control', 'id' => 'atsModel_'.$i)) }}</td>
                                              <td>{{ Form::text('atsSerial_'.$i,$atsDataRow['serial'], array('class' => 'form-control', 'id' => 'atsSerial_'.$i)) }}</td>
                                              <td>{{ Form::text('atsSpec_'.$i,$atsDataRow['spec'], array('class' => 'form-control', 'id' => 'atsSpec_'.$i)) }}</td>
                                              <td>{{ Form::text('atsPhase_'.$i,$atsDataRow['phase'], array('class' => 'form-control', 'id' => 'atsPhase_'.$i)) }}</td>
                                              <td>{{ Form::text('atsVolts_'.$i,$atsDataRow['volts'], array('class' => 'form-control', 'id' => 'atsVolts_'.$i)) }}</td>
                                              <td>{{ Form::text('atsAmps_'.$i,$atsDataRow['amps'], array('class' => 'form-control', 'id' => 'atsAmps_'.$i)) }}</td>
                                              <td>{{ Form::text('atsPole_'.$i,$atsDataRow['pole'], array('class' => 'form-control', 'id' => 'atsPole_'.$i)) }}</td>
                                              <?php $i++;?>
                                            @endforeach
                                            <input type="hidden" name="atsCount" id="atsCount" value="{{$i}}">
                                          @else
                                            <td>{{'-'}}</td>
                                            <td>{{ Form::text('atsMake_0','', array('class' => 'form-control', 'id' => 'atsMake_0')) }}</td>
                                            <td>{{ Form::text('atsModel_0','', array('class' => 'form-control', 'id' => 'atsModel_0')) }}</td>
                                            <td>{{ Form::text('atsSerial_0','', array('class' => 'form-control', 'id' => 'atsSerial_0')) }}</td>
                                            <td>{{ Form::text('atsSpec_0','', array('class' => 'form-control', 'id' => 'atsSpec_0')) }}</td>
                                            <td>{{ Form::text('atsPhase_0','', array('class' => 'form-control', 'id' => 'atsPhase_0')) }}</td>
                                            <td>{{ Form::text('atsVolts_0','', array('class' => 'form-control', 'id' => 'atsVolts_0')) }}</td>
                                            <td>{{ Form::text('atsAmps_0','', array('class' => 'form-control', 'id' => 'atsAmps_0')) }}</td>
                                            <td>{{ Form::text('atsPole_0','', array('class' => 'form-control', 'id' => 'atsPole_0')) }}</td>
                                            <input type="hidden" name="atsCount" id="atsCount" value="1">
                                          @endif
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
                 <div class="col-lg-3">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                           Special Billing Information
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr>
                                          <tr><td>{{Form::textarea('billingInformation',$consumContractTblRow['special_billing_info'], ['class'=>'form-control','id'=>'billingInformation','size'=>'30x3'])}}</td></tr>
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
              <div class="col-lg-3">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                           Additional Information
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr>
                                          <tr><td>{{Form::textarea('additionalInformation',$consumContractTblRow['additional_info'], ['class'=>'form-control','id'=>'additionalInformation','size'=>'30x3'])}}</td></tr>
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
          @if(!empty($consumContractEqpTblRow['kw']))
            <div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                        Unit Pricing (kW)
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <thead class="cf">
                                          <tr>
                                            <th>Unit Pricing (kW)</th><th>Level I - PM</th><th>Adjustment</th><th>Adjusted PM</th><th>Visits PM</th><th>Level II - Annual Service</th><th>Adjustment</th><th>Adjusted - Annual Service</th><th>Visits - Annual Service</th><th>Total</th><th>Notes</th>
                                          </tr>
                                        </thead>
                                        <tbody class="cf">
                                          <?php
                                              $res = DB::select(DB::raw("SELECT * FROM gpg_kw_matrix WHERE gpg_kw_matrix.start_kw <= '".$consumContractEqpTblRow['kw']."' AND gpg_kw_matrix.end_kw >= '".$consumContractEqpTblRow['kw']."'"));
                                              if(count($res)>0 || $consumContractTblRow['pm_charges'] || $consumContractTblRow['annual_charges'])
                                              {
                                                $arr = array();
                                                foreach ($res as $key => $value) {
                                                   $arr = (array)$value;
                                                 }?>
                                              <tr>
                                                  <td id="range_kw">{{$arr['start_kw']}} - {{$arr['end_kw']}}</td>
                                                  <td><input type="text" class="form-control"  id="pm_price" name="_pm_charges" readonly="readonly" value="<?php echo '$'.($consumContractTblRow['pm_charges']?$consumContractTblRow['pm_charges']:$arr['pm_charges']);?>" /></td>
                                                  <td><input type="text" class="form-control"  id="pm_adjust" name="_pm_adjust" onkeyup="kw_adjust()" value="<?php echo $consumContractTblRow['pm_adjust']?$consumContractTblRow['pm_adjust']:"";?>" /></td>
                                                  <td><input type="text" class="form-control"  id="new_pm" name="new_pm" readonly="readonly" /></td>
                                                  <td><input type="text" class="form-control"  id="pm_visits" onkeyup="kw_adjust()" name="_pm_visits" value="<?php echo $consumContractTblRow['pm_visits']?$consumContractTblRow['pm_visits']:"";?>" /></td>
                                                  <td><input type="text" class="form-control"  id="annual_price" name="_annual_charges"  readonly="readonly" value="<?php echo '$'.($consumContractTblRow['annual_charges']?$consumContractTblRow['annual_charges']:$arr['annual_charges']);?>" /></td>
                                                  <td><input type="text" class="form-control"  id="annual_adjust" onkeyup="kw_adjust()" name="_annual_adjust" value="<?php echo $consumContractTblRow['annual_adjust']?$consumContractTblRow['annual_adjust']:"";?>" /></td>
                                                  <td><input type="text" class="form-control"  id="new_annual" readonly="readonly" /></td>
                                                  <td><input type="text" class="form-control"  id="annual_visits" name="_annual_visits" onkeyup="kw_adjust()" value="<?php echo $consumContractTblRow['annual_visits']?$consumContractTblRow['annual_visits']:"";?>" /></td>
                                                  <td><input type="text" class="form-control"  id="total_kw_service" readonly="readonly" /></td>
                                                  <td><input type="text" class="form-control"  id="_contract_notes" name="_contract_notes" value="{{$consumContractTblRow['contract_notes']}}"/></td>
                                                </tr>
                                                @if(count($load_bank_matrix)>0)                                                
                                                <tr>
                                                  <td colspan="8">&nbsp;Charging a Service?&nbsp;<input name="charging_load_bank" <?php echo count($load_bank_matrix)>0?"checked":""?> type="checkbox" onchange="this.checked?$('#load_bank').slideDown():$('#load_bank').slideUp()" value="1" />&nbsp;&nbsp;Lump Sum Service Charges? <input type="checkbox" value="1" name="lump_sum_service" id="lump_sum_service" />&nbsp;&nbsp;Visits Per Year&nbsp;&nbsp;<input type="text" id="visits_per_year" name="_visits_per_year" value="<?php echo $consumContractTblRow['visits_per_year']?$consumContractTblRow['visits_per_year']:'4'?>" class="form-control" />&nbsp;&nbsp;</td>
                                                </tr>
                                                @endif
                                              <?php }?>   
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
          @endif
        <div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                          Parts Info
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <thead class="cf">
                                          <tr>
                                            <th>QTY</th><th>COMPONENT</th><th>Part Number</th><th>Manufacturer</th><th>Cost</th><th>List</th><th>Cost Ext</th><th>List Ext</th>
                                          </tr>
                                        </thead>
                                        <tbody class="cf">
                                          <?php $totalCompCost=0;
                                            $totalCompList=0;?>
                                         @foreach($materialRows1 as $materialRow)
                                          <tr>
                                              <td>{{$materialRow['quantity']}}</td>
                                              <td>{{$materialRow['component']}}</td>
                                              <td>{{$materialRow['partNumber']}}</td>
                                              <td>{{$materialRow['manufacturer']}}</td>
                                              <td><?php echo '$'.number_format($materialRow['cost_price'],2);?></td>
                                              <td><?php echo '$'.number_format($materialRow['list_price'],2);?></td>
                                              <td><?php $tCost = $materialRow['cost_price']*$materialRow['quantity'];
                                                    $totalCompCost += $tCost ; echo '$'.number_format($tCost ,2);?></td>
                                              <td><?php $tList = $materialRow['list_price']*$materialRow['quantity'];
                                                 $totalCompList += $tList; echo '$'.number_format($tList,2);?>
                                              </td>
                                          </tr>
                                         @endforeach
                                           <tr>
                                            <td colspan="6" align="right">Total Components&nbsp;</td>
                                            <td><?php echo '$'.number_format($totalCompCost,2)?></td>
                                            <td><?php echo '$'.number_format(doubleval($totalCompList),2)?></td>
                                           </tr>
                                          <tr>
                                            <th>QTY</th><th>MATERIAL</th><th>Part Number</th><th>Part Description</th><th>Cost</th><th>List</th><th>Cost Ext</th><th>List Ext</th>
                                          </tr>
                                          <?php $totalPartCost=0;
                                            $totalPartList=0;?>
                                         @foreach($materialRows2 as $materialRow)
                                          <tr>
                                              <td>{{$materialRow['quantity']}}</td>
                                              <td>{{$materialRow['material']}}</td>
                                              <td>{{$materialRow['partNumber']}}</td>
                                              <td>{{$materialRow['description']}}</td>
                                              <td>{{'$'.number_format($materialRow['cost_price'],2)}}</td>
                                              <td>{{'$'.number_format($materialRow['list_price'],2)}}</td>
                                              <td><?php $tCost = $materialRow['cost_price']*$materialRow['quantity'];
                                                    $totalPartCost += $tCost ; echo '$'.number_format($tCost ,2)?></td>
                                              <td><?php $tList = $materialRow['list_price']*$materialRow['quantity'];
                                                  $totalPartList += $tList; echo '$'.number_format($tList,2)?></td>       
                                          </tr>
                                         @endforeach
                                          <tr>
                                            <td align="right" colspan="6">Total Parts&nbsp;</td>
                                            <td>{{'$'.number_format($totalPartCost,2)}}</td>
                                            <td>{{'$'.number_format(doubleval($totalPartList),2)}}</td>
                                           </tr>
                                          <tr>
                                            <td align="right" colspan="6">Total&nbsp;</td>
                                            <td>{{'$'.number_format($totalCompCost+$totalPartCost,2)}}</td>
                                            <td>{{'$'.number_format($totalCompList+$totalPartList,2)}}</td>
                                          </tr>
                                          <tr>
                                            <td align="right" colspan="6">Total Labor&nbsp;</td>
                                            <td><?php 
                                                $totalLaborCost =  $consumContractTblRow['labor_shop_cost_rate'] * $consumContractTblRow['labor_shop_total_hours'];
                                                $totalLaborCost +=  $consumContractTblRow['labor_labor_cost_rate'] * $consumContractTblRow['labor_labor_total_hours'];
                                                $totalLaborCost +=  $consumContractTblRow['labor_lbt_cost_rate'] * $consumContractTblRow['labor_lbt_total_hours'];
                                                $totalLaborCost +=  $consumContractTblRow['labor_ot_cost_rate'] * $consumContractTblRow['labor_ot_total_hours'];
                                                $totalLaborCost +=  $consumContractTblRow['labor_sub_con_cost_rate'] * $consumContractTblRow['labor_sub_con_total_hours'];
                                                echo '$'.number_format($totalLaborCost,2);?>
                                            </td>
                                            <td><?php 
                                                $totalLaborList =  $consumContractTblRow['labor_shop_list_rate'] * $consumContractTblRow['labor_shop_total_hours'];
                                                $totalLaborList +=  $consumContractTblRow['labor_labor_list_rate'] * $consumContractTblRow['labor_labor_total_hours'];
                                                $totalLaborList +=  $consumContractTblRow['labor_lbt_list_rate'] * $consumContractTblRow['labor_lbt_total_hours'];
                                                $totalLaborList +=  $consumContractTblRow['labor_ot_list_rate'] * $consumContractTblRow['labor_ot_total_hours'];
                                                $totalLaborList +=  $consumContractTblRow['labor_sub_con_list_rate'] * $consumContractTblRow['labor_sub_con_total_hours'];
                                                echo '$'.number_format($totalLaborList,2); 
                                              ?>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="right" colspan="6">Total Other&nbsp;</td>
                                            <td>{{'$'.number_format($other_charge_cost,2)}}</td>
                                            <td>{{'$'.number_format($other_charge_price,2)}}</td>
                                          </tr>
                                          <tr>
                                            <td align="right" colspan="6">MISC. & HAZMAT (<strong><?php echo $consumContractTblRow['hazmat'] ?>%</strong>)&nbsp;</td>
                                            <td>{{'$'.number_format(($consumContractTblRow['sub_cost_total']/100)*$consumContractTblRow['hazmat'],2)}}</td>
                                            <td><?php $hazmatAmountList = ($consumContractTblRow['sub_list_total']/100)*$consumContractTblRow['hazmat'];
                                                echo '$'.number_format($hazmatAmountList,2); ?>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="right" colspan="6">TAX (<strong><? echo $consumContractTblRow['tax_amount'] ?>%</strong>)&nbsp;</td>
                                            <td><?php echo '$'.number_format(($totalPartCost/100)*$consumContractTblRow['tax_amount'],2);?></td>
                                            <td><?php $taxAmountList = ($totalPartList/100)*$consumContractTblRow['tax_amount'];
                                                echo '$'.number_format($taxAmountList,2);?>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="right" colspan="7"><strong>Quoted Estimate</strong></td>
                                            <td><input type="text" id="quotedEstimate" name="quotedEstimate" value="<?php echo round($consumContractTblRow['grand_list_total'],2) ?>"  class="form-control" readonly="readonly" /></td>
                                          </tr>
                                          <tr>
                                            <td align="right" colspan="7"><strong>Total Parts &amp; Related</strong></td>
                                            <td><?php $listWithOutMaterial = $hazmatAmountList + $totalPartList + $taxAmountList; ?>
                                            <input type="text" id="partsTotal" name="partsTotal" value="<?php echo round($listWithOutMaterial,2); ?>"  class="form-control" readonly="readonly"/></td>
                                          </tr>
                                          <tr>
                                            <td align="right" colspan="7"><strong>Total Contract Fixed Labor</strong></td>
                                            <td align="center"  height="15">
                                            <input type="text" id="fixLabTotal" name="fixLabTotal" value="<?php echo round(($consumContractTblRow['grand_list_total'] - $listWithOutMaterial),2) ?>"  class="form-control" readonly="readonly"/>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="right" colspan="7"><strong>Manually Entered Quoted Amount</strong></td>
                                            <td><input type="text" id="manual_amount" onkeyup="calSchedule()" onblur="set_fields_color()" name="manual_amount" value="<?php echo round(($consumContractTblRow['manual_amount']),2);?>"  class="form-control"/>
                                            </td>
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
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                        Billing Costs Table
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <thead>
                                          <tr>
                                            <td align="left">Visits<label for="quatBilling1"><input type="radio" name="quatBilling" id="quatBilling1" value="1" <?php  if ($consumContractTblRow['quarterly_billing']==1) { ?>checked="checked"<?php } ?> onclick="calSchedule();" /> Monthly Billing</label></td>
                                            <td> <label for="quatBilling2"><input type="radio" name="quatBilling" id="quatBilling2" value="2" <?php  if ($consumContractTblRow['quarterly_billing']==2) { ?>checked="checked"<?php } ?> onclick="calSchedule();" /> Every Two Months Billing</label></td>
                                            <td> <label for="quatBilling3"><input type="radio" name="quatBilling" id="quatBilling3" value="3" <?php  if ($consumContractTblRow['quarterly_billing']==3) { ?>checked="checked"<?php } ?> onclick="calSchedule();" /> Quarterly Billing </label></td>
                                            <td> <label for="quatBilling5"><input type="radio" name="quatBilling" id="quatBilling5" value="5" <?php  if ($consumContractTblRow['quarterly_billing']==5) { ?>checked="checked"<?php } ?> onclick="calSchedule();" /> Bi-Annual Billing </label></td>
                                            <td> <label for="quatBilling4"><input type="radio" name="quatBilling" id="quatBilling4" value="4" <?php  if ($consumContractTblRow['quarterly_billing']==4) { ?>checked="checked"<?php } ?> /> Annually Billing </label></td>
                                            <td><b>Schedule Start:</b></td><td colspan="4"><?php 
                                                     $ccSecStart['month'];
                                                  ?><select name="schMonth" id="schMonth" onchange="put_dates(this.value,$('#schYear').value);" class="form-control">
                                                  <?php for($i=1;$i<=12;$i++) { ?>
                                                      <option value="<?php echo $i;?>" <?php echo ($i==$ccSecStart['month']?'selected="selected"':""); ?> ><?php echo date("F",strtotime("01-".str_pad($i, 2, '0',STR_PAD_LEFT)."-".date("Y")))?></option>
                                                  <?php } ?>
                                                    </select>
                                                    <select name="schYear" id="schYear"  class="form-control" onchange="put_dates($('#schMonth').value,this.value);">
                                                  <?php $currYear = date('Y'); 
                                                    for($i=$currYear-10;$i<=$currYear+10;$i++) { ?>
                                                      <option value="<?php echo $i;?>" <?php echo ($i==$ccSecStart['year']?'selected="selected"':""); ?> ><?php echo $i;?></option>
                                                  <?php } ?>
                                                  </select>
                                              </td>
                                          </tr>
                                        </thead>
                                        <tbody class="cf">
                                           <tr>
                                             <th>Month</th><th>Year</th><th>Service</th><th>Price</th><th>Service2</th><th>Price2</th><th>Service3</th><th>Price3</th><th>Notes</th><th>Price</th>
                                           </tr> 
                                           <?php $schCounter=0;
                                           $schTotal =0;
                                           ?>
                                           @foreach($schDataRs as $schDataRow)
                                           <?php 
                                              if ($schDataRow['installment']!='0.00' && $consumContractTblRow['quarterly_billing']==4) 
                                                $annualMonth = $schDataRow['month']-1;
                                                $schTotal +=  $schDataRow['installment'];
                                                $service_data = array();
                                                $result_service = DB::select(DB::raw('SELECT * FROM gpg_consum_contract_schedule_service WHERE gpg_consum_contract_schedule_id = "'.$schDataRow['id'].'"'));
                                                foreach ($result_service as $key => $service_obj){
                                                  $service_data[$service_obj->service_no][$schCounter] = array($service_obj->service,$service_obj->price,$service_obj->notes); 
                                                }
                                           ?>
                                            <tr>
                                              <td id="monthDiv_{{$schCounter}}">{{date("F",strtotime("01-".$schDataRow['month']."-".$schDataRow['year']))}}</td>
                                              <td>{{$schDataRow['year']}}</td>
                                              <td>{{Form::select('schService1_'.$schCounter,array('PM'=>'PM','LBT'=>'LBT','FP'=>'FP','ATS'=>'ATS','FS1'=>'FS1','Annual'=>'Annual','BLT'=>'BLT','BO'=>'BO','FS2'=>'FS2'),$schDataRow['service1'], ['id'=>'schService1_'.$schCounter,'class'=>'form-control m-bot15','style'=>'display:inline;','onchange'=>'calSchedule()'])}}
                                              </td>
                                              <td>
                                              {{Form::text('schPrice1_'.$schCounter,$schDataRow['price1']=="0.00"?"":$schDataRow['price1'], ['id'=>'schPrice1_'.$schCounter,'class'=>'form-control m-bot15','style'=>'display:inline;','onchange'=>'calSchedule()'])}}
                                              </td>
                                              <td>
                                              {{Form::select('schService2_'.$schCounter,array('PM'=>'PM','LBT'=>'LBT','FP'=>'FP','ATS'=>'ATS','FS1'=>'FS1','Annual'=>'Annual','BLT'=>'BLT','BO'=>'BO','FS2'=>'FS2'),$schDataRow['service2'], ['id'=>'schService2_'.$schCounter,'class'=>'form-control m-bot15','style'=>'display:inline;','onchange'=>'calSchedule()'])}}
                                              </td>
                                              <td>{{Form::text('schPrice2_'.$schCounter,$schDataRow['price2']=="0.00"?"":$schDataRow['price2'], ['id'=>'schPrice2_'.$schCounter,'class'=>'form-control m-bot15','style'=>'display:inline;','onchange'=>'calSchedule()'])}}</td>
                                              <td>{{$schDataRow['service3']}}
                                               {{Form::select('schService3_'.$schCounter,array('PM'=>'PM','LBT'=>'LBT','FP'=>'FP','ATS'=>'ATS','FS1'=>'FS1','Annual'=>'Annual','BLT'=>'BLT','BO'=>'BO','FS2'=>'FS2'),$schDataRow['service3'], ['id'=>'schService3_'.$schCounter,'class'=>'form-control m-bot15','style'=>'display:inline;','onchange'=>'calSchedule()'])}} 
                                              </td>
                                              <td>{{Form::text('schPrice3_'.$schCounter,$schDataRow['price3']=="0.00"?"":$schDataRow['price3'], ['id'=>'schPrice3_'.$schCounter,'class'=>'form-control m-bot15','style'=>'display:inline;','onchange'=>'calSchedule()'])}}</td>
                                              <td><input type="text" class="form-control"></td>
                                              <td><input type="text" id="schInstallment_<?=$schCounter?>" name="schInstallment_<?=$schCounter?>"  value="<?=($schDataRow['installment']=="0.00"?"":$schDataRow['installment'])?>"  class="form-control" readonly="readonly" /></td>
                                            </tr>
                                            <?php $schCounter++;?>
                                           @endforeach
                                           <tr><td colspan="6"><input type="button" value="Recalculate" onclick="calSchedule()"></td><td><strong>Total</strong></td><td>{{$schTotal}}</td><td><strong>Difference</strong></td><td id="totalScheduleDiff"><?php $diff = ($consumContractTblRow['consum_contract_type']==3?round(($consumContractTblRow['grand_list_total'] - $listWithOutMaterial),2):$consumContractTblRow['grand_list_total'])-$schTotal; ?>{{'$'.number_format($diff,2)}}</td></tr>
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
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Renewal History
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <thead class="cf">
                                          <tr>
                                            <th>Lead ID</th><th>Contract Number</th><th>Contract Start Date</th><th>Contract End Date</th><th>Contact Name</th><th>Contact Phone</th><th>Quoted Amount</th><th>Calculated Amount</th>
                                          </tr>
                                        </thead>
                                        <tbody class="cf">
                                          <?php $k = 0;?>
                                          @foreach($childrenData as $row)
                                            <?php
                                              if((isset($row['singleLead']) && $row['singleLead'] == true) || count($childrenData) == 1){
                                                    $sql = DB::select(DB::raw("SELECT * FROM gpg_consum_contract gst WHERE gst.job_num like '".$job_num."%' OR gst.job_num='".$job_num."'"));
                                                } else {
                                                    $sql = DB::select(DB::raw("SELECT * FROM gpg_sales_tracking_consum_contract gstcc LEFT JOIN gpg_consum_contract gst ON (gstcc.gpg_consum_contract_id = gst.id) WHERE gstcc.gpg_sales_tracking_id = '".(int)$row['id']."'")); 
                                                    $singleLead = array();
                                                    foreach ($sql as $key => $value) {
                                                      $singleLead = (array)$value;
                                                    }
                                                    if(isset($singleLead['job_num']))
                                                      $sql = DB::select(DB::raw("SELECT * FROM gpg_consum_contract gst WHERE gst.job_num like '".$singleLead['job_num']."%' OR gst.job_num='".$singleLead['job_num']."'"));
                                                }
                                            ?>
                                            @if(count($sql)>0)
                                              @foreach($sql as $rowData)
                                                <?php
                                                  if($job_num == $rowData->job_num){
                                                      $leadColor = 'bgcolor="#FFC1C1"';
                                                  }
                                                  else if($k % 2 == 0){
                                                      $leadColor = 'bgcolor="#FFFFFF"';
                                                  } else  {
                                                      $leadColor = 'bgcolor="#CEEAFF"';
                                                  }                                                  
                                                ?>
                                                <tr  <?php echo $leadColor; ?>>
                                                  <td><?php echo $row['id']; ?></td>
                                                  <td><?php echo $rowData->job_num; ?></td>
                                                  <td><?php echo date('m/d/Y',strtotime($rowData->consum_contract_start_date)); ?></td>
                                                  <td><?php echo date('m/d/Y',strtotime($rowData->consum_contract_end_date)); ?></td>
                                                  <td><?php echo $rowData->pri_contact_name; ?></td>
                                                  <td><?php echo $rowData->pri_contact_phone; ?></td>
                                                  <td><?php echo '$'.number_format($rowData->contract_amount, 2); ?></td>
                                                  <td><?php echo '$'.number_format(isset($rowData->quoted_estimate)?(double)($rowData->quoted_estimate):0, 2); ?></td>
                                                </tr>
                                              @endforeach  
                                            @endif
                                             <?php $k++;?>
                                          @endforeach
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
    </div>
  {{Form::close()}}
</section>
<!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="change_width">
        {{ Form::open(array('before' => 'csrf' ,'id'=>'create_update_equip_deta','url'=>route('contract/eupdatEquipmentDetails'),'files'=>true, 'method' => 'post')) }} {{Form::hidden('job_id',$job_id)}}{{Form::hidden('job_num',$job_num)}}
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Equipment Details</h4>
                                          </div>
                                          <div class="modal-body">
                                        <section id="no-more-tables">
                                         <table class="table table-bordered table-striped table-condensed cf" id="myTable">
                                            <tbody class="cf">
                                                <tr>
                                                  <th>Air Belt:</th><td>{{Form::text('_air_belt','', ['class'=>'form-control','id'=>'_air_belt'])}}</td>
                                                  <th>Air Belt Qty:</th><td>{{Form::text('_air_belt_qty',0, ['class'=>'form-control','id'=>'_air_belt_qty'])}}</td>  
                                                </tr> 
                                                <tr>
                                                  <th>Air Filter 1:</th><td>{{Form::text('_air_filter1','', ['class'=>'form-control','id'=>'_air_filter1'])}}</td>
                                                  <th>Air Filter 1 Qty:</th><td>{{Form::text('_air_filter1_qty',0, ['class'=>'form-control','id'=>'_air_filter1_qty'])}}</td>  
                                                </tr> 
                                                <tr>
                                                  <th>Air Filter 2:</th><td>{{Form::text('_air_filter2','', ['class'=>'form-control','id'=>'_air_filter2'])}}</td>
                                                  <th>Air Filter 2 Qty:</th><td>{{Form::text('_air_filter2_qty',0, ['class'=>'form-control','id'=>'_air_filter2_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Battery Charger:</th><td>{{Form::text('_battery_charger','', ['class'=>'form-control','id'=>'_battery_charger'])}}</td>
                                                  <th>Block Heater:</th><td>{{Form::text('_block_heater','', ['class'=>'form-control','id'=>'_block_heater'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Battery Type:</th><td>{{Form::text('_battery_type','', ['class'=>'form-control','id'=>'_battery_type'])}}</td>
                                                  <th>Battery Qty:</th><td>{{Form::text('_battery_qty',0, ['class'=>'form-control','id'=>'_battery_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Controller Model:</th><td>{{Form::text('_controller_model','', ['class'=>'form-control','id'=>'_controller_model'])}}</td>
                                                  <th>Filter Changed On:</th><td>{{Form::text('_filter_changed_on','', ['class'=>'form-control form-control-inline input-medium default-date-picker','id'=>'_filter_changed_on'])}}</td>  
                                                </tr> 
                                                <tr>
                                                  <th>Coolant Filter:</th><td>{{Form::text('_coolant_filter','', ['class'=>'form-control','id'=>'_coolant_filter'])}}</td>
                                                  <th>Coolant Filter Qty:</th><td>{{Form::text('_coolant_filter_qty',0, ['class'=>'form-control','id'=>'_coolant_filter_qty'])}}</td>  
                                                </tr>  
                                                <tr>
                                                  <th>Fan Belt:</th><td>{{Form::text('_fan_belt','', ['class'=>'form-control','id'=>'_fan_belt'])}}</td>
                                                  <th>Fan Belt Qty:</th><td>{{Form::text('_fan_belt_qty',0, ['class'=>'form-control','id'=>'_fan_belt_qty'])}}</td>  
                                                </tr> 
                                                <tr>
                                                  <th>Fuel Filter 1:</th><td>{{Form::text('_fuel_filter1','', ['class'=>'form-control','id'=>'_fuel_filter1'])}}</td>
                                                  <th>Fuel Filter 1 Qty:</th><td>{{Form::text('_fuel_filter1_qty',0, ['class'=>'form-control','id'=>'_fuel_filter1_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Fuel Filter 2:</th><td>{{Form::text('_fuel_filter2','', ['class'=>'form-control','id'=>'_fuel_filter2'])}}</td>
                                                  <th>Fuel Filter 2 Qty:</th><td>{{Form::text('_fuel_filter2_qty',0, ['class'=>'form-control','id'=>'_fuel_filter2_qty'])}}</td>  
                                                </tr>   
                                                <tr>
                                                  <th>Fuel Filter 3:</th><td>{{Form::text('_fuel_filter3','', ['class'=>'form-control','id'=>'_fuel_filter3'])}}</td>
                                                  <th>Fuel Filter 3 Qty:</th><td>{{Form::text('_fuel_filter3_qty',0, ['class'=>'form-control','id'=>'_fuel_filter3_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Gen Misc:</th><td>{{Form::text('_gen_misc','', ['class'=>'form-control','id'=>'_gen_misc'])}}</td>
                                                  <th>Governor:</th><td>{{Form::text('_governor','', ['class'=>'form-control','id'=>'_governor'])}}</td>  
                                                </tr> 
                                                <tr>
                                                  <th>Lower Hose:</th><td>{{Form::text('_lower_hose','', ['class'=>'form-control','id'=>'_lower_hose'])}}</td>
                                                  <th>Lower Hose Qty:</th><td>{{Form::text('_lower_hose_qty',0, ['class'=>'form-control','id'=>'_lower_hose_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Upper Hose:</th><td>{{Form::text('_upper_hose','', ['class'=>'form-control','id'=>'_upper_hose'])}}</td>
                                                  <th>Upper Hose Qty:</th><td>{{Form::text('_upper_hose_qty',0, ['class'=>'form-control','id'=>'_upper_hose_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Oil Type:</th><td>{{Form::text('_oil_type','', ['class'=>'form-control','id'=>'_oil_type'])}}</td>
                                                  <th>Oil Capacity:</th><td>{{Form::text('_oil_capacity','', ['class'=>'form-control','id'=>'_oil_capacity'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Oil Filter 1:</th><td>{{Form::text('_oil_filter1','', ['class'=>'form-control','id'=>'_oil_filter1'])}}</td>
                                                  <th>Oil Filter 1 Qty:</th><td>{{Form::text('_oil_filter1_qty',0, ['class'=>'form-control','id'=>'_oil_filter1_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Oil Filter 2:</th><td>{{Form::text('_oil_filter2','', ['class'=>'form-control','id'=>'_oil_filter2'])}}</td>
                                                  <th>Oil Filter 2 Qty:</th><td>{{Form::text('_oil_filter2_qty',0, ['class'=>'form-control','id'=>'_oil_filter2_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Oil Filter 3:</th><td>{{Form::text('_oil_filter3','', ['class'=>'form-control','id'=>'_oil_filter3'])}}</td>
                                                  <th>Oil Filter 3 Qty:</th><td>{{Form::text('_oil_filter3_qty',0, ['class'=>'form-control','id'=>'_oil_filter3_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Oil Filter 4:</th><td>{{Form::text('_oil_filter4','', ['class'=>'form-control','id'=>'_oil_filter4'])}}</td>
                                                  <th>Oil Filter 4 Qty:</th><td>{{Form::text('_oil_filter4_qty',0, ['class'=>'form-control','id'=>'_oil_filter4_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Water Separator Part :</th><td>{{Form::text('_water_separator_part_no','', ['class'=>'form-control','id'=>'_water_separator_part_no'])}}</td>
                                                  <th>Water Separator Qty:</th><td>{{Form::text('_water_separator_qty',0, ['class'=>'form-control','id'=>'_water_separator_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Waterpump Belt:</th><td>{{Form::text('_waterpump_belt','', ['class'=>'form-control','id'=>'_waterpump_belt'])}}</td>
                                                  <th>Waterpump Belt Qty:</th><td>{{Form::text('_waterpump_belt_qty',0, ['class'=>'form-control','id'=>'_waterpump_belt_qty'])}}</td>  
                                                </tr> 
                                                <tr>
                                                  <th>Misc:</th><td>{{Form::text('_misc','', ['class'=>'form-control','id'=>'_misc'])}}</td>
                                                  <th>Misc Qty:</th><td>{{Form::text('_misc_qty','', ['class'=>'form-control','id'=>'_misc_qty'])}}</td>  
                                                </tr> 
                                                <tr>
                                                  <th>Misc1:</th><td>{{Form::text('_misc1','', ['class'=>'form-control','id'=>'_misc1'])}}</td>
                                                  <th>Misc1 Qty:</th><td>{{Form::text('_misc1_qty','', ['class'=>'form-control','id'=>'_misc1_qty'])}}</td>  
                                                </tr>
                                                <tr>
                                                  <th>Misc2:</th><td>{{Form::text('_misc2','', ['class'=>'form-control','id'=>'_misc2'])}}</td>
                                                  <th>Misc2 Qty:</th><td>{{Form::text('_misc2_qty','', ['class'=>'form-control','id'=>'_misc2_qty'])}}</td>  
                                                </tr>                                                   
                                            </tbody>
                                         </table>
                                        </section>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save', array('class' => 'btn btn-primary','data-dismiss'=>'modal','id'=>'save_update_data'))}}
                                          {{Form::button('<i class="fa fa-times"></i>', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                      </div>
                                  </div>
                                  {{Form::close()}}
                              </div>
                          </div>
                        <!-- modal -->
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
     $('select[name=customerBillto]').change(function(){
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
            $('#cusAtt').val(data.attn);    
          },
        });
     });
    function autoFill() {
      if (confirm("Job Site Fields will be updated! Do you want to continue?")) {
        document.getElementById("_address1").value = document.getElementById("cusAddress1").value;
        document.getElementById("_address2").value = document.getElementById("cusAddress2").value;
        document.getElementById("_city").value = document.getElementById("cusCity").value;
        document.getElementById("_state").value = document.getElementById("cusState").value;
        document.getElementById("_zip").value = document.getElementById("cusZip").value;
        document.getElementById("_attn").value = document.getElementById("cusAtt").value;
      }
  }
  function backFill(){
    if (confirm("Billing Fields will be updated! Do you want to continue?")) {
      document.getElementById("cusAddress1").value = document.getElementById("_address1").value ;
      document.getElementById("cusAddress2").value = document.getElementById("_address2").value;
      document.getElementById("cusCity").value = document.getElementById("_city").value;
      document.getElementById("cusState").value = document.getElementById("_state").value;
      document.getElementById("cusZip").value = document.getElementById("_zip").value;
      document.getElementById("cusAtt").value = document.getElementById("_attn").value;
    }
  }
  function set_fields_color()
  {
    total_fields = $('#total_services').val();
    amount = $('#manual_amount').val();
    fields_total = 0;
    var color="#FFFFFF";
    for(loop=1; loop<=total_fields; loop++)
    {
      for(loop2=0; loop2<11; loop2++) 
      {
        if($('#schPrice'+loop+'_'+loop2))
        {
          price_input = $('#schPrice'+loop+'_'+loop2);
          
          if(price_input.value.length > 0)
          {
            fields_total = parseFloat(fields_total) + parseFloat(price_input.value);
            if(fields_total > amount)
              color="#FFC1C1";
            price_input.style.backgroundColor = color;
          }
          else
          {
            price_input.style.backgroundColor = "#FFFFFF";
          }
        }
      }
    }
  }
  function kw_adjust()
  {
      var price = clear_num($('#pm_price').val());
      var pm_adjust = $('#pm_adjust').val();
      pm_visits = $('#pm_visits').val()?$('#pm_visits').val():0;
      $('#new_pm').val((price*1) + (pm_adjust*1));
      $('#new_pm').val(roundNumber($('#new_pm').val(),2));
      
      var price = clear_num($('#annual_price').val());
      var pm_adjust = $('#annual_adjust').val();
      $('#annual_visits').val($('#annual_visits').val()!=0?$('#annual_visits').val():1);
      $('#annual_visits').val($('#annual_visits').val()!=""?$('#annual_visits').val():1);
      annual_visits = $('#annual_visits').val();
      
      $('#new_annual').val((price*1) + (pm_adjust*1));
      $('#new_annual').val(roundNumber($('#new_annual').val(),2));
      total_pm = $('#new_pm').val() * pm_visits;
      total_annual = $('#new_annual').val() * annual_visits;
      $('#total_kw_service').val((total_pm*1) + (total_annual*1)); 
      $('#total_kw_service').val(roundNumber($('#total_kw_service').val(),2));
    
  }
  function clear_num(str)
  {
   return str.replace(new RegExp(',', 'g'),'').replace('$','');
  }
  function roundNumber(rnum,rlength) {
    if (rnum > 8191 && rnum < 10485) {
      rnum = rnum-5000;
      var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
      newnumber = newnumber+5000;
    } else {
      var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
    }
    return newnumber;
  }
  var my_months = Array();
  var my_vals = Array();
  function init_month()
  {
    for (k=0; k<12; k++) 
    {
      if($('#monthDiv_'+k).html().length  > 10)
      {
        my_months[$('#monthDiv_'+k).attr('strong').html(k)];
      }
      else
      {
        my_months[$('#monthDiv_'+k).html()] = k; 
      }
        my_vals[k] = $('#schInstallment_'+k).val();
    }
  }
  function calSchedule() {
   var DefaultCurrency = '$';
   var totalPrice = 0.00;
   var sch = 0.00;
   var grandTotal = 0.00;
   var contractType = parseInt($('#contractType').val());
   switch (contractType) {
   case 1 :
   case 2 :
   case 3 :
   var qutEst =  parseFloat($('#manual_amount').val());
   var partTot = parseFloat($('#fixLabTotal').val());
   totalPrice = 0;
   total_services = $('#total_services').val();
      for (i=0; i<=11; i++) {
      for (j=1; j<=total_services; j++) {
      if($('#schPrice' + j + '_' + i))
      {
        if($('#schPrice' + j + '_' + i).val())
        {
          if($('#schPrice' + j + '_' + i).val().substr($('#schPrice' + j + '_' + i).val().length-1,$('#schPrice' + j + '_' + i).val().length)!=".")
            $('#schPrice' + j + '_' + i).val(roundNumber($('#schPrice' + j + '_' + i).val(),2));
          totalPrice += parseFloat($('#schPrice' + j + '_' + i).val());
        }
      }
    }
   }
   if(totalPrice == 0)
   {
  if (contractType==3) totalPrice = (partTot?partTot:0.00);   
  else totalPrice = (qutEst?qutEst:0.00);  
   }
   var ServiceItemList = {  }; 
   var counter = 0;
   for (i=0; i<=11; i++) {
    for (j=1; j<=total_services; j++) {
      if (ServiceItemList[$('#schService' + j + '_' + i)]) 
      {
        if (ServiceItemList[$('#schService' + j + '_' + i).val()]) 
          ServiceItemList[$('#schService' + j + '_' + i).val()]++; 
        else 
          ServiceItemList[$('#schService' + j + '_' + i).val()]=1;  
      }
    }
   }
   for (i=0; i<=11; i++) {
    for (j=1; j<=total_services; j++) {
      if($('#schService' + j + '_' + i))
      {
        if ($('#schService' + j + '_' + i).val() == 'PM' || $('#schService' + j + '_' + i).val() == 'Annual') { 
          pmCount = parseFloat(ServiceItemList['PM']);
          annCount = parseFloat(ServiceItemList['Annual']);
        } else {
          //DG('schPrice' + j + '_' + i).value = ''; 
        }
      }
    }
   } 
   for (i=0; i<=11; i++) {
        $('#schInstallment_' + i).val(''); 
     }
   if ($('#quatBilling1').checked) {
     sch = (totalPrice/12);
     for (i=0; i<=11; i++) {
        $('#schInstallment_' + i).val(roundNumber(sch,2)); 
        grandTotal += roundNumber(sch,2);
     }
   }
   else if ($('#quatBilling2').checked) {
     sch = (totalPrice/6);
     for (i=0; i<=11; i+=2) {
        $('#schInstallment_' + i).val(roundNumber(sch,2)); 
        grandTotal += roundNumber(sch,2);
     }   
     $('#totalSchedule').html(DefaultCurrency + roundNumber(totalPrice,2));  
     }
   else if ($('#quatBilling3').checked) {
     sch = (totalPrice/4);
     for (i=0; i<=11; i+=3) {
        $('#schInstallment_' + i).val(roundNumber(sch,2)); 
        grandTotal += roundNumber(sch,2);
     }   
     $('#totalSchedule').html(DefaultCurrency + roundNumber(totalPrice,2));  
     }
   else if ($('#quatBilling4').checked) {
     sch = totalPrice;
     if(!my_months[$('#BillingMonthDrop').options[$('#BillingMonthDrop').selectedIndex].text])
     init_month();
     $('#schInstallment_' + my_months[$('#BillingMonthDrop').options[$('#BillingMonthDrop').selectedIndex].text]).val(roundNumber(sch,2)); 
     grandTotal += roundNumber(sch,2);
     $('#totalSchedule').html(DefaultCurrency + roundNumber(totalPrice,2));  
     }
   else if ($('#quatBilling5').checked) {
     sch = (totalPrice/2);
     for (i=0; i<=11; i+=6) {
        $('#schInstallment_' + i).val(roundNumber(sch,2)); 
        grandTotal += roundNumber(sch,2);
     }   
     $('#totalSchedule').html(DefaultCurrency + roundNumber(totalPrice,2));  
     }
   $('#totalSchedule').html(DefaultCurrency + roundNumber(grandTotal,2));
   var DIFF = totalPrice - $('#manual_amount').val();
   if (DIFF<0) $('#totalScheduleDiff').css( "color", "red" );
   else $('#totalScheduleDiff').css( "color", "green" );
   $('#totalScheduleDiff').html(DefaultCurrency + roundNumber(DIFF,2));
   break;
   case 4:
   case 5:
      var gtotal = 0.00;
      for (i=0; i<=11; i++) {
     for (j=1; j<=3; j++) {
       if($('#schService' + j + '_' + i)){
        if ($('#schService' + j + '_' + i).val()) {
          if (parseFloat($('#schPrice' + j + '_' + i).val())) totalPrice += parseFloat($('#schPrice' + j + '_' + i).val()); 
        } else $('#schPrice' + j + '_' + i).val('');
       }
     }
     if(totalPrice)
    $('#schInstallment_' + i).val(roundNumber(totalPrice,2));
    else
      $('#schInstallment_' + i).val() = "";
    gtotal += totalPrice;
    totalPrice = 0;
    } 
    var qutEst =  parseFloat($('#quotedEstimate').val());
    var partTot = parseFloat($('#fixLabTotal').val());
    $('#totalSchedule').html(DefaultCurrency + roundNumber(gtotal,2));
     if (contractType==4) var DIFF = (partTot?partTot:0.00) - gtotal;   
        else var DIFF = (qutEst?qutEst:0.00) - gtotal;    
   if (roundNumber(DIFF,2)>=0) $('#totalScheduleDiff').css( "color", "green" );
   else $('#totalScheduleDiff').css( "color", "red" );
   $('#totalScheduleDiff').html(DefaultCurrency + roundNumber(DIFF,2));
   break;
   }
}
  var counter=parseInt($('#atsCount').val());
  $('#add_new_row').click(function(){
       var str = '<tr>';
          str+= '<td><input type="text" value="" name="atsMake_'+counter+'" id="atsMake_'+counter+'" class="form-control"></td>';
          str+= '<td><input type="text" value="" name="atsModel_'+counter+'" id="atsModel_'+counter+'" class="form-control"></td>';
          str+= '<td><input type="text" value="" name="atsSerial_'+counter+'" id="atsSerial_'+counter+'" class="form-control"></td>';
          str+= '<td><input type="text" value="" name="atsSpec_'+counter+'" id="atsSpec_'+counter+'" class="form-control"></td>';
          str+= '<td><input type="text" value="" name="atsPhase_'+counter+'" id="atsPhase_'+counter+'" class="form-control"></td>';
          str+= '<td><input type="text" value="" name="atsVolts_'+counter+'" id="atsVolts_'+counter+'" class="form-control"></td>';
          str+= '<td><input type="text" value="" name="atsAmps_'+counter+'" id="atsAmps_'+counter+'" class="form-control"></td>';
          str+= '<td><input type="text" value="" name="atsPole_'+counter+'" id="atsPole_'+counter+'" class="form-control"></td></tr>';
           $('#atsTable > tbody:last').append(str);
           $('#atsCount').val(parseInt($('#atsCount').val())+parseInt('1'));
           counter = parseInt(counter) + parseInt("1");
  });
  $('#save_update_data').click(function(){
    $('#create_update_equip_deta').submit();
  });
  $('#generate_duplicate_contract').click(function(){
     conf = confirm('Are you Sure you Want to DUPLICATE THE CONTRACT?');
     if (conf){
        $.ajax({
        url: "{{URL('ajax/duplicateContract')}}",
          data: {
            'id' : '{{$job_id}}',
            'cusId':$('#customerBillto option:selected').val()
          },
          success: function (data) {
            alert('Contract has been DUPLICATED Successfully!')
            location.reload();   
          },
        });
     }else
      return false; 
  });
  
 $('#renew_contract').click(function(){
     conf = confirm('Are you Sure you Want to RENEW THE CONTRACT?');
     if (conf){
        $.ajax({
        url: "{{URL('ajax/renewContract')}}",
          data: {
            'id' : '{{$job_id}}',
            'cusId':$('#customerBillto option:selected').val()
          },
          success: function (data) {
            alert('Contract has been Renewed Successfully!')
            location.reload();   
          },
        });
     }else
      return false; 
  });
 $('#copy_quote_data_confirm').click(function(){
  var vl = $('#copyFieldServiceId option:selected').val();
  if (vl == '')
    alert('Error! Please Select a Fields Service Work!');
  else{
    if (confirm('All the data on this sheet will be replaced by the selected field service work data.\nTHIS CHANGE COULD NOT BE ROLLED BACK.')) 
    { 
      var jobNum = '{{$job_num}}';
      var consum_contract_id = '{{$job_id}}';
      var copy_field_service_work_id_index = document.getElementById('copyFieldServiceId').selectedIndex;
      var copy_field_service_work_id = document.getElementById('copyFieldServiceId').options[copy_field_service_work_id_index].value;
      $.ajax({
        url: "{{URL('ajax/copyContract')}}",
          data: {
            'job_id' : consum_contract_id,
            'job_num':jobNum,
            'fsw_id':copy_field_service_work_id
          },
          success: function (data) {
            alert('Contract has been Copied Successfully!')
            location.reload();   
          },
        });
    }else { 
      return false; 
    }
  }  
 });
$('#submit_main_form').click(function(){
  $('#update_consumer_contract_form').submit();
});
  </script>
</body>
</html>
