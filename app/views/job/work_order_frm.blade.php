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

              <!--logo start-->
               <div class="col-lg-9">
                  <section class="panel">
                  {{ HTML::image(asset('img/gpglogo.jpg'), 'GPG Logo', array('style' => 'display:inline; width:100px; height:70px;')) }}
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
     <br/><br/><br/><br/><br/>
       {{Form::open(array('method' => 'POST','id'=>'update_elec_jobs','files'=>true,'route' => array('job/updateFSWFrm')))}} 
     <section id="main-content">
      <section id="wrapper">
         <section class="panel">
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
          <div class="panel-body">
              <!-- page start-->
            {{ Form::hidden('job_id',$job_id)}}
            <div class="row">
            <div class="col-lg-12">
              <section id="no-more-tables">
                  <table class="table table-bordered table-striped table-condensed cf">
                    <tbody class="cf">
                  <tr>
                  <td data-title="Job Number:">Job Number: {{ Form::text('job_num',$job_num, array('class' => 'form-control', 'id' => 'job_num','readOnly')) }}</td>
                  <td data-title="Status:">Status:<br/> <span style="color:red; font-weight:bold;"> {{$workOrderTblRow['field_service_work_status']}}</span></td>     
                  <td data-title="Date:">Date:{{ Form::text('scheduleDate',($workOrderTblRow['schedule_date']!=""?date('Y-m-d',strtotime($workOrderTblRow['schedule_date'])):date('Y-m-d')), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'scheduleDate')) }}</td>
                  </tr>
                  </tbody>
                   </table>
                </section>
              </div>  
              </div>
              </div>
              </section>
                <div class="row">
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
                                        <tr><td style="background-color:#FFFFCC;">Customer Name:</td><td>{{Form::select('customerBillto', $workOrderTblRow['customer_drop_down'],$workOrderTblRow['GPG_customer_id'] , ['class'=>'form-control','id'=>'customerBillto'])}}</td><td style="background-color:#FFFFCC;">Location Name:</td><td>{{Form::select('locationNameId', $workOrderTblRow['locationNameId'],$workOrderTblRow['gpg_consum_contract_equipment_id'] , ['class'=>'form-control','id'=>'locationNameId'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Main Contact Phone:</td><td>{{ Form::text('mainContactPhone',$workOrderTblRow['main_contact_phone'], array('class' => 'form-control', 'id' => 'mainContactPhone')) }}</td><td style="background-color:#FFFFCC;">Billing Contact Name:</td><td>{{ Form::text('billingContactName',$workOrderTblRow['billing_contact_name'], array('class' => 'form-control', 'id' => 'billingContactName')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Address 1:</td><td>{{ Form::text('cusAddress1',$workOrderTblRow['billing_contact_name'], array('class' => 'form-control', 'id' => 'cusAddress1')) }}</td><td style="background-color:#FFFFCC;">Address 2:</td><td>{{ Form::text('cusAddress2','', array('class' => 'form-control', 'id' => 'cusAddress2')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('cusCity','', array('class' => 'form-control', 'id' => 'cusCity')) }}</td><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('cusState','', array('class' => 'form-control', 'id' => 'cusState')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('cusZip','', array('class' => 'form-control', 'id' => 'cusZip')) }}</td><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('cusPhone','', array('class' => 'form-control', 'id' => 'cusPhone')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Email:</td><td>{{Form::text('cusEmail','', ['class'=>'form-control','id'=>'cusEmail'])}}</td><td colspan="2">[{{Form::button('FILL JOB SITE INFO', array('onClick'=>'autoFillJobSiteInfo();','class' => 'btn btn-link btn-xs'))}}]</td></tr>
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
                                         Job Site Address
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                          <tr><td style="background-color:#FFFFCC;">Sales Person:</td><td>{{Form::select('salePersonId', $workOrderTblRow['salesPerson_drop_down'],$workOrderTblRow['GPG_employee_id'] , ['class'=>'form-control','id'=>'salePersonId'])}}</td><td style="background-color:#FFFFCC;">Main Contact Name:</td><td>{{ Form::text('mainContactName',$workOrderTblRow['main_contact_name'], array('class' => 'form-control', 'id' => 'mainContactName')) }}</td></tr>
                                          <tr><td style="background-color:#FFFFCC;">Address1:</td><td>{{ Form::text('_address1',(isset($workOrderTblRow['consumContractEqpTblRow']['address1'])?$workOrderTblRow['consumContractEqpTblRow']['address1']:''), array('class' => 'form-control', 'id' => '_address1')) }}</td><td style="background-color:#FFFFCC;">Address2:</td><td>{{ Form::text('_address2',(isset($workOrderTblRow['consumContractEqpTblRow']['address2'])?$workOrderTblRow['consumContractEqpTblRow']['address2']:''), array('class' => 'form-control', 'id' => '_address2')) }}</td></tr>
                                          <tr><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('_city',(isset($workOrderTblRow['consumContractEqpTblRow']['city'])?$workOrderTblRow['consumContractEqpTblRow']['city']:''), array('class' => 'form-control', 'id' => '_city')) }}</td><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('_state',(isset($workOrderTblRow['consumContractEqpTblRow']['state'])?$workOrderTblRow['consumContractEqpTblRow']['state']:''), array('class' => 'form-control', 'id' => '_state')) }}</td></tr>
                                          <tr><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('_zip',(isset($workOrderTblRow['consumContractEqpTblRow']['zip'])?$workOrderTblRow['consumContractEqpTblRow']['zip']:''), array('class' => 'form-control', 'id' => '_zip')) }}</td><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('_phone',(isset($workOrderTblRow['consumContractEqpTblRow']['phone'])?$workOrderTblRow['consumContractEqpTblRow']['phone']:''), array('class' => 'form-control', 'id' => '_phone')) }}</td></tr>
                                          <tr><td style="background-color:#FFFFCC;">Cell:</td><td>{{ Form::text('cell',$workOrderTblRow['cell'], array('class' => 'form-control', 'id' => 'cell')) }}</td><td colspan="2">[{{Form::button('FILL CUSTOMER FIELDS', array('onClick'=>'autoFillJobSiteInfo();','class' => 'btn btn-link btn-xs'))}}]</td></tr>
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
                           <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         <span style="display:inline;">Scope Of Work</span>{{Form::select('set_scope_this', $sett_arr,'', ['class'=>'form-control','id'=>'set_scope_this'])}} 
                                      </a>
                                  </h4>
                            </div>
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        @foreach($sett_arr2 as $k=>$val)
                                          <input type="hidden" id="scope_template_{{$k}}" value="{{$val}}">
                                        @endforeach
                                        <tr><td>{{ Form::textarea('scopeOfWork',$workOrderTblRow['task'],['id'=>'scopeOfWork','class'=>'form-control','size' => '30x4']) }}</td></tr>
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
                           <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         <span style="display:inline;">Misc Info </span>
                                      </a>
                                  </h4>
                            </div>
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                          <tr><td>{{ Form::textarea('miscInfo',$workOrderTblRow['work_order_info'],['id'=>'miscInfo','class'=>'form-control','size' => '30x5']) }}</td></tr>
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
                           <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         <span style="display:inline;">Special Billing Insructions</span>
                                      </a>
                                  </h4>
                            </div>
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                          <tr><td>{{ Form::textarea('specBillingIns',$workOrderTblRow['special_billing_ins'],['id'=>'specBillingIns','class'=>'form-control','size' => '30x5']) }}</td></tr>
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
                  <div class="col-lg-3">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                        Generator Info
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td>Make:</td><td >{{Form::text('_make',(isset($workOrderTblRow['consumContractEqpTblRow']['make'])?$workOrderTblRow['consumContractEqpTblRow']['make']:''), ['class'=>'form-control','id'=>'_make'])}}</td></tr>
                                        <tr><td>Model:</td><td >{{Form::text('_model',(isset($workOrderTblRow['consumContractEqpTblRow']['model'])?$workOrderTblRow['consumContractEqpTblRow']['model']:''), ['class'=>'form-control','id'=>'_model'])}}</td></tr>
                                        <tr><td>Serial:</td><td >{{Form::text('_serial',(isset($workOrderTblRow['consumContractEqpTblRow']['serial'])?$workOrderTblRow['consumContractEqpTblRow']['serial']:''), ['class'=>'form-control','id'=>'_serial'])}}</td></tr>
                                        <tr><td>KW:</td><td >{{Form::text('_genkw',(isset($workOrderTblRow['consumContractEqpTblRow']['kw'])?$workOrderTblRow['consumContractEqpTblRow']['kw']:''), ['class'=>'form-control','id'=>'_genkw'])}}</td></tr>
                                        <tr><td>Spec:</td><td >{{Form::text('_spec',(isset($workOrderTblRow['consumContractEqpTblRow']['spec'])?$workOrderTblRow['consumContractEqpTblRow']['spec']:''), ['class'=>'form-control','id'=>'_spec'])}}</td></tr>
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
                                         Engine Info
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td>Make:</td><td >{{Form::text('engMake',$workOrderTblRow['eng_make'], ['class'=>'form-control','id'=>'engMake'])}}</td></tr>
                                        <tr><td>Model:</td><td >{{Form::text('engModel',$workOrderTblRow['eng_model'], ['class'=>'form-control','id'=>'engModel'])}}</td></tr>
                                        <tr><td>Serial:</td><td >{{Form::text('engSerial',$workOrderTblRow['eng_serial'], ['class'=>'form-control','id'=>'engSerial'])}}</td></tr>
                                        <tr><td>Spec:</td><td >{{Form::text('engSpec',$workOrderTblRow['eng_spec'], ['class'=>'form-control','id'=>'engSpec'])}}</td></tr>
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
                                         Labor * tech desired is not guarenteed!
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td>Qty of Techs:</td><td>{{Form::text('qtyofTechs',$workOrderTblRow['qty_techs'], ['class'=>'form-control','id'=>'qtyofTechs'])}}</td></tr>
                                        <tr><td>Qty of Helpers:</td><td>{{Form::text('qtyofHelpers',$workOrderTblRow['qty_helpers'], ['class'=>'form-control','id'=>'qtyofHelpers'])}}</td></tr>
                                        <tr><td>Skill Level of Tech:</td><td>{{Form::text('skillofTech',$workOrderTblRow['skill_level_tech'], ['class'=>'form-control','id'=>'skillofTech'])}}</td></tr>
                                        <tr><td>Multi Day Job?:</td><td>{{Form::select('multiJob',array('1'=>'Yes','0'=>'No'),$workOrderTblRow['multiday_job'], ['class'=>'form-control','id'=>'multiJob'])}}</td></tr>
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
                                         Labor * tech desired is not guarenteed!
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                       <tbody class="cf">
                                        <tr><td>Weekend Work:</td><td>{{Form::text('weekendWork',$workOrderTblRow['weekend_work'], ['class'=>'form-control','id'=>'weekendWork'])}}</td></tr>
                                        <tr><td>After Hours Work:</td><td>{{Form::text('afterHoursWork',$workOrderTblRow['after_hours_work'], ['class'=>'form-control','id'=>'afterHoursWork'])}}</td></tr>
                                        <tr><td>Tech Desired:</td><td>{{Form::text('TechDesired',$workOrderTblRow['tech_desired'], ['class'=>'form-control','id'=>'TechDesired'])}}</td></tr>
                                        <tr><td>If Yes How Many days?</td><td>{{Form::text('multiJobDays',$workOrderTblRow['tech_days'], ['class'=>'form-control','id'=>'multiJobDays'])}}</td></tr>
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
                                        Special Equipment Needed.
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <thead>
                                          <tr>
                                            <th>QTY</th>
                                            <th>COMPONENTS</th>
                                            <th>Part Number</th>
                                            <th>Manufacturer</th>
                                            <th>Vendor</th>
                                            <th>GPG Cost Price Each</th>
                                            <th>GPG List Price Each</th>
                                            <th>Margin%</th>
                                          </tr>
                                        </thead>
                                        <tbody class="cf">
                                          @foreach($componentRows as $componentRow)
                                          <tr>
                                            <td>{{$componentRow['quantity']}}</td>
                                            <td>{{$componentRow['component']}}</td>
                                            <td>{{$componentRow['partNumber']}}</td>
                                            <td>{{$componentRow['manufacturer']}}</td>
                                            <td>{{$componentRow['vendor']}}</td>
                                            <td>{{'$'.number_format($componentRow['cost_price'],2)}}</td>
                                            <td>{{'$'.number_format($componentRow['list_price'],2)}}</td>
                                            <td>{{$componentRow['margin']}}</td>
                                          </tr>  
                                          @endforeach  
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
                <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         PARTS NEEDED
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <thead>
                                          <tr>
                                            <th>QTY</th>
                                            <th>MATERIAL</th>
                                            <th>Part Number</th>
                                            <th>Manufacturer</th>
                                            <th>Vendor</th>
                                            <th>GPG Cost Price Each</th>
                                            <th>GPG List Price Each</th>
                                            <th>Margin%</th>
                                          </tr>
                                        </thead>
                                        <tbody class="cf">
                                          @foreach($materialRows as $materialRow)
                                          <tr>
                                            <td>{{isset($materialRow['quantity'])?$materialRow['quantity']:''}}</td>
                                            <td>{{isset($materialRow['material'])?$materialRow['material']:''}}</td>
                                            <td>{{isset($materialRow['partNumber'])?$materialRow['partNumber']:''}}</td>
                                            <td>{{isset($materialRow['manufacturer'])?$materialRow['manufacturer']:''}}</td>
                                            <td>{{isset($materialRow['vendor'])?$materialRow['vendor']:''}}</td>
                                            <td>{{isset($materialRow['cost_price'])?('$'.number_format($materialRow['cost_price'],2)):''}}</td>
                                            <td>{{isset($materialRow['list_price'])?('$'.number_format($materialRow['list_price'],2)):''}}</td>
                                            <td>{{isset($materialRow['margin'])?$materialRow['margin']:''}}</td>
                                          </tr>
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
            <div class="row">
                  <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Billing
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <thead>
                                          <tr>
                                            <th>Price Quoted</th>
                                            <th>PO#</th>
                                            <th>Is this an Original Job Request or a Changed to A Current Job</th>
                                            <th>If Existing, What is the job #</th>
                                            <th>Zone Index</th>
                                            <th>Manage Attachments</th>
                                          </tr>
                                        </thead>
                                        <tbody class="cf">
                                          <tr>
                                            <td>{{'$'.number_format($workOrderTblRow['grand_list_total'],2)}}</td>
                                            <td>{{Form::text('poNumber',$workOrderTblRow['po_number'], array('class' => 'form-control', 'id' => 'poNumber')) }}</td>
                                            <td>{{Form::select('jobCheck',array('0'=>'No','1'=>'Yes'),$workOrderTblRow['job_check'], ['class'=>'form-control','id'=>'jobCheck'])}}</td>
                                            <td>{{Form::text('attachJobNum',$workOrderTblRow['GPG_attach_job_num'], array('class' => 'form-control', 'id' => 'attachJobNum')) }}</td>
                                            <td>{{Form::select('_zone_index_id',$zone_index_arr,$workOrderTblRow['zone_index_id'], ['class'=>'form-control','id'=>'_zone_index_id'])}}</td>
                                            <td  data-title="Attachments:" style="padding-bottom:7.8px;">{{HTML::link('#myModal5', 'Manage Files' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'manage_files','id'=>$job_id,'job_num'=>$job_num))}}</td>
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
    </section>
  </section>
    <div class="btn-group" style="padding:20px;">
      {{Form::button('Save/Update Changes', array('class' => 'btn btn-primary', 'id'=>'submit_main_form'))}}
    </div>
  {{Form::close()}}
</section>  
        <!-- Modal# -->
           <div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ATTACHMENT MANAGEMENT</h4>
                      </div>
                    <div class="modal-body">
                    {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('job/manageFSWFiles'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('fjob_id',$job_id,array('id' => 'change_job_id' ))}} {{Form::hidden('fjob_num',$job_num,array('id' => 'change_job_num' ))}}     
                    <div class="form-group">
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
        <!-- modal# end-->   
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
  function autoFillJobSiteInfo() {
    if (confirm("The JOB SITE Address Fields will be updated. Do you want to continue?")) {
      $("#_address1").val($("#cusAddress1").val());
      $("#_address2").val($("#cusAddress2").val());
      $("#_city").val($("#cusCity").val());
      $("#_state").val($("#cusState").val());
      $("#_zip").val($("#cusZip").val());
      $("#_phone").val($("#cusPhone").val());
    } 
  }
  function autoFill() {
    if (confirm("The Customer Address Fields will be updated. Do you want to continue?")) {
      $("#cusAddress1").val($("#_address1").val());
      $("#cusAddress2").val($("#_address2").val());
      $("#cusCity").val($("#_city").val());
      $("#cusState").val($("#_state").val());
      $("#cusZip").val($("#_zip").val());
      $("#cusPhone").val($("#_phone").val());
    } 
  }
  
  $('#set_scope_this').change(function(){
    var id = $(this).val();
    $('#scopeOfWork').val($('#scope_template_'+id).val());
  });

 $('#customerBillto').change(function(){
   var id = $(this).val();
     $.ajax({
          url: "{{URL('ajax/getCustomerInfo')}}",
          data: {
           'cid' : id
          },
          success: function (data){
            $('#cusAddress1').val(data.address);    
            $('#cusAddress2').val(data.address2);    
            $('#cusCity').val(data.city);    
            $('#cusState').val(data.state);    
            $('#cusZip').val(data.zipcode);    
            $('#cusPhone').val(data.phone_no);    
         },
      });
 });
  


  $('#submit_attachments').click(function(){
        $('#submit_file_form').submit();
  });
  $('#submit_main_form').click(function(){
    $('#update_elec_jobs').submit();
  });

      $('a[name=manage_files]').click(function(){
        var job_num = '{{$job_num}}';
        var job_id = "{{$job_id}}";
        $('#change_job_id').val(job_id);
        $('#change_job_num').val(job_num);
        $.ajax({
              url: "{{URL('ajax/getFSWFiles')}}",
              data: {
                'id' : job_id,
                'num': job_num
              },
            success: function (data) {
              $('#display_quote_files').html(data);
               $('a[name=del_fsw_file]').click(function(){
                var result = confirm("Are you sure! you want to delete....?");
                if(result){
                  $.ajax({
                        url: "{{URL('ajax/deleteFSWFiles')}}",
                        data: {
                          'id' : $(this).attr('id')
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
</script>
</body>
</html>
