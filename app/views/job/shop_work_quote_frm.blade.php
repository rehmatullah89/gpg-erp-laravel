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
                  </section>
                </div>
              <!--logo end-->
            <div class="col-lg-6">
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
    {{Form::open(array('method' => 'POST','id'=>'update_shop_work_quote','files'=>true,'route' => array('quote/updateShopWorkQuoteFrm')))}} 
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
                        <td data-title="Job Number:">Shop Work Quote#: {{ Form::text('job_num',$job_num, array('class' => 'form-control', 'id' => 'job_num','readOnly')) }}</td>
                        <td>Sales Person: {{Form::select('salePersonId',array(''=>'Sales Person')+$sales_persons,(isset($shopWorkQuoteTblRow['GPG_employee_id'])?$shopWorkQuoteTblRow['GPG_employee_id']:0), ['class'=>'form-control','id'=>'salePersonId'])}}</td>  
                        <td data-title="Status:">Status:<br/> <span style="color:red; font-weight:bold;"> {{$shopWorkQuoteTblRow['shop_work_quote_status']}}</span></td>     
                        <td data-title="Date:">Date:{{ Form::text('scheduleDate',($shopWorkQuoteTblRow['schedule_date']!=""?date('Y-m-d',strtotime($shopWorkQuoteTblRow['schedule_date'])):date('Y-m-d')), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'scheduleDate')) }}</td>  
                        <td data-title="Time:" style="width:20%;">Time: <div class="input-group bootstrap-timepicker">{{ Form::text('schedule_time',$shopWorkQuoteTblRow['schedule_time'], array('class' => 'form-control timepicker-default','id' => 'schedule_time')) }}  <span class="input-group-btn">{{Form::button('<i class="fa fa-clock-o"></i>', array('class' => 'btn btn-default'))}}</span></div></td>
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
                                        <tr><td style="background-color:#FFFFCC;">Customer Name:</td><td>{{Form::select('customerBillto',$customers,$shopWorkQuoteTblRow['GPG_customer_id'] , ['class'=>'form-control','id'=>'customerBillto'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Job Description:</td><td>{{ Form::text('jobDescription',$shopWorkQuoteTblRow['sub_task'], array('class' => 'form-control', 'id' => 'jobDescription')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Location:</td><td>{{ Form::text('location',$shopWorkQuoteTblRow['location'], array('class' => 'form-control', 'id' => 'location')) }}</td></tr>
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
                                         Contact's Info
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">Contact:</td><td>{{Form::text('contact', $shopWorkQuoteTblRow['main_contact_name'] , ['class'=>'form-control','id'=>'contact'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('phone',$shopWorkQuoteTblRow['main_contact_phone'], array('class' => 'form-control', 'id' => 'phone')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Fax:</td><td>{{ Form::text('fax',$shopWorkQuoteTblRow['fax'], array('class' => 'form-control', 'id' => 'fax'))}}</td></tr>
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
                                         Equipment Needed
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td data-title="Equip Needed:">{{ Form::textarea('equipmentNeeded', $shopWorkQuoteTblRow['equipment_needed'],['class'=>'form-control']) }}</td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                 </div>
                              </div>
                          </div>
                      </div>
                  </section>
              </div>
                  <div class="col-lg-9">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Equipment Details {{Form::button('<i class="fa fa-plus-circle"></i>', array('title'=>'Add New Row','class' => 'btn btn-primary btn-xs','id'=>'add_new_row'))}}
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="mytable">
                                        <thead class="cf">
                                          <tr>
                                            <th>Del</th>
                                            <th>Search</th>
                                            <th>QTY</th>
                                            <th>Equipment Type</th>
                                            <th>Equipment Number</th>
                                            <th>Description/Size/Length/ Rating</th>
                                            <th>Brand</th>
                                            <th>Cost</th>
                                            <th>List</th>
                                            <th>Vendor Cost</th>
                                            <th>Sell Price</th>
                                            <th>Margin %</th>
                                            <th>Cost Total Component</th>
                                            <th>List Total Component</th>
                                          </tr>
                                        </thead>  
                                        <tbody class="cf">
                                        @if(!empty($queryComponenRows))
                                          <?php $i=1; ?>
                                          @foreach($queryComponenRows as $queryComponenRow)
                                          <tr>
                                            <td>{{Form::button('<i class="fa fa-trash-o"></i>', array('title'=>'Delete','class' => 'btn btn-danger btn-xs','name'=>'delete_row','id'=>$queryComponenRow['id'],'table'=>'gpg_shop_work_quote_component'))}}</td>
                                            <td>{{HTML::link('#myModal5', 'Eqp.' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'search_equipment','row'=>$i,'type'=>'comp'))}}</td>
                                            <td>{{Form::text('_comp_quantity_'.$i,(isset($queryComponenRow['quantity'])?$queryComponenRow['quantity']:''), array('class' => 'form-control', 'id' => '_comp_quantity_'.$i,'onkeyup'=>'cal_component('.$i.')'))}}</td>
                                            <td>{{Form::select('_comp_component_'.$i,array(''=>'-','NEWCOMPONENT'=>'ADD NEW')+$equipments,(isset($queryComponenRow['component_id'])?$queryComponenRow['component_id']:''), ['class'=>'form-control','id'=>'_comp_component_'.$i])}}</td>
                                            <td>{{Form::select('_comp_part_'.$i,array(''=>'-'),(isset($queryComponenRow['part_id'])?$queryComponenRow['part_id']:''), ['class'=>'form-control','id'=>'_comp_part_'.$i,'onchange'=>'fill_component(this,"'.$i.'")'])}}</td>
                                            <td>{{Form::text('comp_manufacturer_label_'.$i,'', array('class' => 'form-control', 'id' => 'comp_manufacturer_label_'.$i,'readOnly'))}}</td>
                                            <td>{{Form::text('comp_vendor_label_'.$i,'', array('class' => 'form-control', 'id' => 'comp_vendor_label_'.$i,'readOnly'))}}</td>
                                            <td>{{Form::text('_comp_cost_'.$i,(isset($queryComponenRow['cost_price'])?$queryComponenRow['cost_price']:''), array('class' => 'form-control', 'id' => '_comp_cost_'.$i,'onblur'=>'cal_sell_margin('.$i.',2)','onkeyup'=>'cal_sell_margin('.$i.',2)'))}}</td>
                                            <td>{{Form::text('_comp_list_'.$i,(isset($queryComponenRow['list_price'])?$queryComponenRow['list_price']:''), array('class' => 'form-control', 'id' => '_comp_list_'.$i,'onkeyup'=>'cal_component('.$i.')'))}}</td>
                                            <td>{{Form::text('_comp_vendor_cost_'.$i,(isset($queryComponenRow['gpg_vendor_cost'])?$queryComponenRow['gpg_vendor_cost']:''), array('class' => 'form-control', 'id' => '_comp_vendor_cost_'.$i))}}</td>
                                            <td>{{Form::text('_comp_sell_price_cost_'.$i,(isset($queryComponenRow['gpg_comp_sell_price_cost'])?$queryComponenRow['gpg_comp_sell_price_cost']:''), array('class' => 'form-control', 'id' => '_comp_sell_price_cost_'.$i,'onblur'=>'cal_sell_margin('.$i.',1)','onkeyup'=>'cal_sell_margin('.$i.', 1)'))}}</td>
                                            <td>{{Form::text('_comp_margin_'.$i,(isset($queryComponenRow['margin'])?$queryComponenRow['margin']:''), array('class' => 'form-control', 'id' => '_comp_margin_'.$i,'onblur'=>'cal_sell_margin('.$i.', 2)','onkeyup'=>'cal_sell_margin('.$i.',2)'))}}</td>
                                            <td>{{Form::text('_comp_cost_total_'.$i,round((isset($queryComponenRow['quantity'])?$queryComponenRow['quantity']:0)*(isset($queryComponenRow['cost_price'])?$queryComponenRow['cost_price']:0),2), array('class' => 'form-control', 'id' => '_comp_cost_total_'.$i,'readOnly'))}}</td>
                                            <td>{{Form::text('_comp_total_'.$i,round((isset($queryComponenRow['quantity'])?$queryComponenRow['quantity']:0)*(isset($queryComponenRow['gpg_comp_sell_price_cost']) && $queryComponenRow['gpg_comp_sell_price_cost']==0?$queryComponenRow['list_price']:(isset($queryComponenRow['gpg_comp_sell_price_cost'])?$queryComponenRow['gpg_comp_sell_price_cost']:0)),2), array('class' => 'form-control', 'id' => '_comp_total_'.$i,'readOnly'))}}</td>
                                            <input type="hidden" id="equip_count" name="equip_count" value="{{$i}}">
                                          </tr>
                                          <?php $i++; ?>
                                          @endforeach
                                          <tr><td colspan="12">TOTAL COMPONENTS:</td><td>{{Form::text('_comp_sub_cost_total','', array('class' => 'form-control', 'id' => '_comp_sub_cost_total','readonly'))}}</td><td>{{Form::text('_comp_sub_total','', array('class' => 'form-control', 'id' => '_comp_sub_total','readonly'))}}</td></tr>
                                        @else
                                          <tr>
                                            <td>{{'x'}}</td>
                                            <td>{{HTML::link('#myModal5', 'Eqp.' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'search_equipment','row'=>'1','type'=>'comp'))}}</td>
                                            <td>{{Form::text('_comp_quantity_1','', array('class' => 'form-control', 'id' => '_comp_quantity_1','onkeyup'=>'cal_component(1)'))}}</td>
                                            <td>{{Form::select('_comp_component_1',array(''=>'-','NEWCOMPONENT'=>'ADD NEW')+$equipments,'', ['class'=>'form-control','id'=>'_comp_component_1'])}}</td>
                                            <td>{{Form::select('_comp_part_1',array(''=>'-'),'', ['class'=>'form-control','id'=>'_comp_part_1','onchange'=>'fill_component(this,"1")'])}}</td>
                                            <td>{{Form::text('comp_manufacturer_label_1','', array('class' => 'form-control', 'id' => 'comp_manufacturer_label_1','readOnly'))}}</td>
                                            <td>{{Form::text('comp_vendor_label_1','', array('class' => 'form-control', 'id' => 'comp_vendor_label_1','readOnly'))}}</td>
                                            <td>{{Form::text('_comp_cost_1','', array('class' => 'form-control', 'id' => '_comp_cost_1','onblur'=>'cal_sell_margin(1,2)','onkeyup'=>'cal_sell_margin(1,2)'))}}</td>
                                            <td>{{Form::text('_comp_list_1','', array('class' => 'form-control', 'id' => '_comp_list_1','onkeyup'=>'cal_component(1)'))}}</td>
                                            <td>{{Form::text('_comp_vendor_cost_1','', array('class' => 'form-control', 'id' => '_comp_vendor_cost_1'))}}</td>
                                            <td>{{Form::text('_comp_sell_price_cost_1','', array('class' => 'form-control', 'id' => '_comp_sell_price_cost_1','onblur'=>'cal_sell_margin(1,1)','onkeyup'=>'cal_sell_margin(1, 1)'))}}</td>
                                            <td>{{Form::text('_comp_margin_1','', array('class' => 'form-control', 'id' => '_comp_margin_1','onblur'=>'cal_sell_margin(1, 2)','onkeyup'=>'cal_sell_margin(1,2)'))}}</td>
                                            <td>{{Form::text('_comp_cost_total_1','', array('class' => 'form-control', 'id' => '_comp_cost_total_1','readOnly'))}}</td>
                                            <td>{{Form::text('_comp_total_1','', array('class' => 'form-control', 'id' => '_comp_total_1','readOnly'))}}</td>
                                            <input type="hidden" id="equip_count" name="equip_count" value="1">
                                          </tr>
                                          <tr><td colspan="12"><b>TOTAL COMPONENTS:</b></td><td>{{Form::text('_comp_sub_cost_total','', array('class' => 'form-control', 'id' => '_comp_sub_cost_total','readonly'))}}</td><td>{{Form::text('_comp_sub_total','', array('class' => 'form-control', 'id' => '_comp_sub_total','readonly'))}}</td></tr>
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
<!-- row2 -->
            <div class="row">
              <div class="col-lg-3">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Scope Of Work
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td data-title="Equip Needed:">{{ Form::textarea('scopeOfWork', $shopWorkQuoteTblRow['task'],['class'=>'form-control']) }}</td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                 </div>
                              </div>
                          </div>
                      </div>
                  </section>
              </div>
                  <div class="col-lg-9">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         MATERIAL Details {{Form::button('<i class="fa fa-plus-circle"></i>', array('title'=>'Add New Row','class' => 'btn btn-primary btn-xs','id'=>'add_material_row'))}}
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="mytable2">
                                        <thead class="cf">
                                          <tr>
                                            <th>Del</th>
                                            <th>Search</th>
                                            <th>QTY</th>
                                            <th>MATERIAL</th>
                                            <th>Part Number</th>
                                            <th>Description</th>
                                            <th>Manufacturer</th>
                                            <th>Vendor</th>
                                            <th>Cost</th>
                                            <th>List</th>
                                            <th>Vendor Cost</th>
                                            <th>Sell Price</th>
                                            <th>Margin %</th>
                                            <th>Cost Total Component</th>
                                            <th>List Total Component</th>
                                          </tr>
                                        </thead>  
                                        <tbody class="cf">
                                        @if(!empty($queryMaterialRes))
                                          <?php $i=1; ?>
                                         @foreach($queryMaterialRes as $queryMaterialRow)
                                          <tr>
                                            <td>{{Form::button('<i class="fa fa-trash-o"></i>', array('title'=>'Delete','class' => 'btn btn-danger btn-xs','name'=>'delete_row','id'=>$queryMaterialRow['id'],'table'=>'gpg_shop_work_quote_material'))}}</td>
                                            <td>{{HTML::link('#myModal5', 'Part' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'search_equipment','row'=>$i,'type'=>'mat'))}}</td>
                                            <td>{{Form::text('_mat_quantity_'.$i,$queryMaterialRow['quantity'], array('class' => 'form-control', 'id' => '_mat_quantity_'.$i,'onkeyup'=>'cal_material('.$i.')'))}}</td>
                                            <td>{{Form::select('_mat_material_'.$i,array(''=>'-','NEWMATERIAL'=>'ADD NEW')+$getMaterial,$queryMaterialRow['material_id'], ['class'=>'form-control','id'=>'_mat_material_'.$i])}}</td>
                                            <td>{{Form::select('_mat_part_'.$i,array(''=>'-'),'', ['class'=>'form-control','id'=>'_mat_part_'.$i,'onchange'=>'fill_material(this,"1")'])}}</td>
                                            <td>{{Form::text('mat_description_label_'.$i,$queryMaterialRow['description'], array('class' => 'form-control', 'id' => 'mat_description_label_'.$i,'readOnly'))}}</td>
                                            <td>{{Form::text('mat_manufacturer_label_'.$i,$queryMaterialRow['manufacturer'], array('class' => 'form-control', 'id' => 'mat_manufacturer_label_'.$i,'readOnly'))}}</td>
                                            <td>{{Form::text('mat_vendor_label_'.$i,$queryMaterialRow['vendor'], array('class' => 'form-control', 'id' => 'mat_vendor_label_'.$i,'readOnly'))}}</td>
                                            <td>{{Form::text('_mat_cost_'.$i,$queryMaterialRow['cost_price'], array('class' => 'form-control', 'id' => '_mat_cost_'.$i,'onkeyup'=>'cal_mat_sell_margin('.$i.',2)'))}}</td>
                                            <td>{{Form::text('_mat_list_'.$i,$queryMaterialRow['list_price'], array('class' => 'form-control', 'id' => '_mat_list_'.$i,'onkeyup'=>'cal_material('.$i.')'))}}</td>
                                            <td>{{Form::text('_mat_vendor_cost_'.$i,$queryMaterialRow['gpg_vendor_cost'], array('class' => 'form-control', 'id' => '_mat_vendor_cost_'.$i))}}</td>
                                            <td>{{Form::text('_mat_sell_price_'.$i,$queryMaterialRow['gpg_mat_sell_price_cost'], array('class' => 'form-control', 'id' => '_mat_sell_price_'.$i,'onkeyup'=>'cal_mat_sell_margin('.$i.', 1)'))}}</td>
                                            <td>{{Form::text('_mat_margin_'.$i,$queryMaterialRow['margin'], array('class' => 'form-control', 'id' => '_mat_margin_'.$i,'onkeyup'=>'cal_mat_sell_margin('.$i.',2)'))}}</td>
                                            <td>{{Form::text('_mat_cost_total_'.$i,round($queryMaterialRow['quantity']*$queryMaterialRow['cost_price'],2), array('class' => 'form-control', 'id' => '_mat_cost_total_'.$i,'readOnly'))}}</td>
                                            <td>{{Form::text('_mat_total_'.$i,round($queryMaterialRow['quantity']*(isset($queryMaterialRow['gpg_comp_sell_price_cost']) && $queryMaterialRow['gpg_comp_sell_price_cost']==0?$queryMaterialRow['list_price']:(isset($queryMaterialRow['gpg_comp_sell_price_cost'])?$queryMaterialRow['gpg_comp_sell_price_cost']:0)),2), array('class' => 'form-control', 'id' => '_mat_total_'.$i,'readOnly'))}}</td>
                                            <input type="hidden" id="mat_count" name="mat_count" value="{{$i}}">
                                           </tr>
                                          <?php $i++; ?>
                                          @endforeach
                                          <tr><td colspan="13"><b>TOTAL PARTS:</b></td><td>{{Form::text('_mat_sub_cost_total','', array('class' => 'form-control', 'id' => '_mat_sub_cost_total','readonly'))}}</td><td>{{Form::text('_mat_sub_total','', array('class' => 'form-control', 'id' => '_mat_sub_total','readonly'))}}</td></tr>
                                        @else
                                          <tr>
                                            <td>{{'x'}}</td>
                                            <td>{{HTML::link('#myModal5', 'Part' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'search_equipment','row'=>'1','type'=>'mat'))}}</td>
                                            <td>{{Form::text('_mat_quantity_1','', array('class' => 'form-control', 'id' => '_mat_quantity_1','onkeyup'=>'cal_material(1)'))}}</td>
                                            <td>{{Form::select('_mat_material_1',array(''=>'-','NEWMATERIAL'=>'ADD NEW')+$getMaterial,'', ['class'=>'form-control','id'=>'_mat_material_1'])}}</td>
                                            <td>{{Form::select('_mat_part_1',array(''=>'-'),'', ['class'=>'form-control','id'=>'_mat_part_1','onchange'=>'fill_material(this,"1")'])}}</td>
                                            <td>{{Form::text('mat_description_label_1','', array('class' => 'form-control', 'id' => 'mat_description_label_1','readOnly'))}}</td>
                                            <td>{{Form::text('mat_manufacturer_label_1','', array('class' => 'form-control', 'id' => 'mat_manufacturer_label_1','readOnly'))}}</td>
                                            <td>{{Form::text('mat_vendor_label_1','', array('class' => 'form-control', 'id' => 'mat_vendor_label_1','readOnly'))}}</td>
                                            <td>{{Form::text('_mat_cost_1','', array('class' => 'form-control', 'id' => '_mat_cost_1','onkeyup'=>'cal_mat_sell_margin(1,2)'))}}</td>
                                            <td>{{Form::text('_mat_list_1','', array('class' => 'form-control', 'id' => '_mat_list_1','onkeyup'=>'cal_material(1)'))}}</td>
                                            <td>{{Form::text('_mat_vendor_cost_1','', array('class' => 'form-control', 'id' => '_mat_vendor_cost_1'))}}</td>
                                            <td>{{Form::text('_mat_sell_price_1','', array('class' => 'form-control', 'id' => '_mat_sell_price_1','onkeyup'=>'cal_mat_sell_margin(1, 1)'))}}</td>
                                            <td>{{Form::text('_mat_margin_1','', array('class' => 'form-control', 'id' => '_mat_margin_1','onkeyup'=>'cal_mat_sell_margin(1,2)'))}}</td>
                                            <td>{{Form::text('_mat_cost_total_1','', array('class' => 'form-control', 'id' => '_mat_cost_total_1','readOnly'))}}</td>
                                            <td>{{Form::text('_mat_total_1','', array('class' => 'form-control', 'id' => '_mat_total_1','readOnly'))}}</td>
                                            <input type="hidden" id="mat_count" name="mat_count" value="1">
                                          </tr>
                                          <tr><td colspan="13"><b>TOTAL PARTS:</b></td><td>{{Form::text('_mat_sub_cost_total','', array('class' => 'form-control', 'id' => '_mat_sub_cost_total','readonly'))}}</td><td>{{Form::text('_mat_sub_total','', array('class' => 'form-control', 'id' => '_mat_sub_total','readonly'))}}</td></tr>
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
              <!-- row2 end-->
              <!-- row3 start -->
            <div class="row">
              <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Scope Of Work Details {{Form::button('<i class="fa fa-plus-circle"></i>', array('title'=>'Add New Row','class' => 'btn btn-primary btn-xs','id'=>'add_row_scope_work'))}}
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="mytable3">
                                        <thead>
                                          <tr>
                                            <th>Del</th>
                                            <th>SCOPE OF WORK</th>
                                            <th>Shop</th>
                                            <th>Labor</th>
                                            <th>LBT</th>
                                            <th>OT</th>
                                            <th>Sub - Con</th>
                                          </tr>
                                        </thead>
                                        <tbody class="cf">
                                        @if(!empty($queryLabor_arr))
                                          <?php $i=1;?>
                                          @foreach($queryLabor_arr as $queryLaborResRow)
                                          <tr>
                                            <input type="hidden" name="laborWorkHourId_{{$i}}" value="{{$queryLaborResRow['id']}}">
                                            <td>{{Form::button('<i class="fa fa-trash-o"></i>', array('title'=>'Delete','class' => 'btn btn-danger btn-xs','name'=>'delete_row','id'=>$queryLaborResRow['id'],'table'=>'gpg_shop_work_quote_labor'))}}</td>
                                            <td>{{Form::text('_labor_scope_'.$i,$queryLaborResRow['scope_work'], array('class'=>'form-control', 'id'=>'_labor_scope_'.$i))}}</td>
                                            <td>{{Form::text('_labor_shop_'.$i,$queryLaborResRow['shop'], array('onkeyup'=>'cal_labor_total("shop")','class'=>'form-control', 'id'=>'_labor_shop_'.$i))}}</td>
                                            <td>{{Form::text('_labor_labor_'.$i,$queryLaborResRow['labor'], array('onkeyup'=>'cal_labor_total("labor")','class'=>'form-control', 'id'=>'_labor_labor_'.$i))}}</td>
                                            <td>{{Form::text('_labor_LBT_'.$i,$queryLaborResRow['lbt'], array('onkeyup'=>'cal_labor_total("LBT")','class'=>'form-control', 'id'=>'_labor_LBT_'.$i))}}</td>
                                            <td>{{Form::text('_labor_OT_'.$i,$queryLaborResRow['ot'], array('onkeyup'=>'cal_labor_total("OT")','class'=>'form-control', 'id'=>'_labor_OT_'.$i))}}</td>
                                            <td>{{Form::text('_labor_sub_con_'.$i,$queryLaborResRow['sub_con'], array('onkeyup'=>'cal_labor_total("sub_con")','class'=>'form-control', 'id'=>'_labor_sub_con_'.$i))}}</td>
                                            <input type="hidden" name="labor_counter" id="labor_counter" value="{{$i}}">
                                          </tr>
                                          <?php $i++;?>
                                          @endforeach
                                        @else
                                          <tr>
                                            <td>x</td>
                                            <input type="hidden" name="laborWorkHourId_1" value="">
                                            <td>{{Form::text('_labor_scope_1','', array('class'=>'form-control', 'id'=>'_labor_scope_1'))}}</td>
                                            <td>{{Form::text('_labor_shop_1','', array('onkeyup'=>'cal_labor_total("shop")','class'=>'form-control', 'id'=>'_labor_shop_1'))}}</td>
                                            <td>{{Form::text('_labor_labor_1','', array('onkeyup'=>'cal_labor_total("labor")','class'=>'form-control', 'id'=>'_labor_labor_1'))}}</td>
                                            <td>{{Form::text('_labor_LBT_1','', array('onkeyup'=>'cal_labor_total("LBT")','class'=>'form-control', 'id'=>'_labor_LBT_1'))}}</td>
                                            <td>{{Form::text('_labor_OT_1','', array('onkeyup'=>'cal_labor_total("OT")','class'=>'form-control', 'id'=>'_labor_OT_1'))}}</td>
                                            <td>{{Form::text('_labor_sub_con_1','', array('onkeyup'=>'cal_labor_total("sub_con")','class'=>'form-control', 'id'=>'_labor_sub_con_1'))}}</td>
                                            <input type="hidden" name="labor_counter" id="labor_counter" value="1">
                                          </tr>
                                        @endif
                                        </tbody>
                                     </table>
                                   </section>
                                 </div>
                              </div>
                          </div>
                      </div>
                  </section>
                <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Sublet Labor Details {{Form::button('<i class="fa fa-plus-circle"></i>', array('title'=>'Add New Row','class' => 'btn btn-primary btn-xs','id'=>'add_sub_row'))}}
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="mytable4">
                                        <thead>
                                          <tr>
                                            <th>Del</th>
                                            <th>Sublet Labor</th>
                                            <th>Shop</th>
                                            <th>Labor</th>
                                            <th>LBT</th>
                                            <th>OT</th>
                                            <th>Sub - Con</th>
                                          </tr>
                                        </thead>
                                        <tbody class="cf">
                                         @if(!empty($querySubLabor_arr))
                                          <?php $i=1;?>
                                          @foreach($querySubLabor_arr as $querySubLaborResRow)
                                          <tr>
                                            <input type="hidden" name="subLaborWorkHourId_{{$i}}" value="{{$querySubLaborResRow['id']}}">
                                            <td>{{Form::button('<i class="fa fa-trash-o"></i>', array('title'=>'Delete','class' => 'btn btn-danger btn-xs','name'=>'delete_row','id'=>$querySubLaborResRow['id'],'table'=>'gpg_shop_work_quote_labor'))}}</td>
                                            <td>{{Form::text('_sub_labor_scope_'.$i,$querySubLaborResRow['scope_work'], array('class'=>'form-control', 'id'=>'_sub_labor_scope_'.$i))}}</td>
                                            <td>{{Form::text('_sub_labor_shop_'.$i,$querySubLaborResRow['shop'], array('onkeyup'=>'cal_labor_total("shop")','class'=>'form-control', 'id'=>'_sub_labor_shop_'.$i))}}</td>
                                            <td>{{Form::text('_sub_labor_labor_'.$i,$querySubLaborResRow['labor'], array('onkeyup'=>'cal_labor_total("labor")','class'=>'form-control', 'id'=>'_sub_labor_labor_'.$i))}}</td>
                                            <td>{{Form::text('_sub_labor_LBT_'.$i,$querySubLaborResRow['lbt'], array('onkeyup'=>'cal_labor_total("LBT")','class'=>'form-control', 'id'=>'_sub_labor_LBT_'.$i))}}</td>
                                            <td>{{Form::text('_sub_labor_OT_'.$i,$querySubLaborResRow['ot'], array('onkeyup'=>'cal_labor_total("OT")','class'=>'form-control', 'id'=>'_sub_labor_OT_'.$i))}}</td>
                                            <td>{{Form::text('_sub_labor_sub_con_'.$i,$querySubLaborResRow['sub_con'], array('onkeyup'=>'cal_labor_total("sub_con")','class'=>'form-control', 'id'=>'_sub_labor_sub_con_'.$i))}}</td>
                                          </tr>
                                          <input type="hidden" name="subl_counter" id="subl_counter" value="{{$i}}">
                                          <?php $i++;?>
                                          @endforeach
                                         @else
                                          <tr>
                                            <td>x</td>
                                            <input type="hidden" name="subLaborWorkHourId_1" value="">
                                            <td>{{Form::text('_sub_labor_scope_1','', array('class'=>'form-control', 'id'=>'_sub_labor_scope_1'))}}</td>
                                            <td>{{Form::text('_sub_labor_shop_1','', array('onkeyup'=>'cal_labor_total("shop")','class'=>'form-control', 'id'=>'_sub_labor_shop_1'))}}</td>
                                            <td>{{Form::text('_sub_labor_labor_1','', array('onkeyup'=>'cal_labor_total("labor")','class'=>'form-control', 'id'=>'_sub_labor_labor_1'))}}</td>
                                            <td>{{Form::text('_sub_labor_LBT_1','', array('onkeyup'=>'cal_labor_total("LBT")','class'=>'form-control', 'id'=>'_sub_labor_LBT_1'))}}</td>
                                            <td>{{Form::text('_sub_labor_OT_1','', array('onkeyup'=>'cal_labor_total("OT")','class'=>'form-control', 'id'=>'_sub_labor_OT_1'))}}</td>
                                            <td>{{Form::text('_sub_labor_sub_con_1','', array('onkeyup'=>'cal_labor_total("sub_con")','class'=>'form-control', 'id'=>'_sub_labor_sub_con_1'))}}</td>
                                            <input type="hidden" name="subl_counter" id="subl_counter" value="1">
                                          </tr> 
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
                  <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Other Charges Details 
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="mytable2">
                                        <thead class="cf">
                                          <tr>
                                            <th>Other Charges</th>
                                            <th>Cost Per Hour Rate</th>
                                            <th>List Per Hour Rate</th>
                                            <th>Total Hours</th>
                                            <th>Cost Total</th>
                                            <th>List Total</th>
                                          </tr>
                                        </thead>  
                                        <tbody class="cf">
                                          <tr>
                                            <td>Total Shop Labor Hours </td>
                                            <td>{{Form::text('_labor_shop_cost_rate',number_format((empty($shopWorkQuoteTblRow['labor_shop_cost_rate'])?40:$shopWorkQuoteTblRow['labor_shop_cost_rate']),2), array('onkeyup'=>'cal_labor_total_price("shop")','class'=>'form-control', 'id'=>'_labor_shop_cost_rate'))}}</td>
                                            <td>{{Form::text('_labor_shop_rate',number_format((empty($shopWorkQuoteTblRow['labor_shop_list_rate'])?98:$shopWorkQuoteTblRow['labor_shop_list_rate']),2), array('onkeyup'=>'cal_labor_total_price("shop")','class'=>'form-control', 'id'=>'_labor_shop_rate'))}}</td>
                                            <td>{{Form::text('_labor_shop_hour','', array('onkeyup'=>'cal_labor_total_price("shop")','class'=>'form-control', 'id'=>'_labor_shop_hour'))}}</td>
                                            <td>{{Form::text('_labor_shop_cost_total','', array('class'=>'form-control', 'id'=>'_labor_shop_cost_total','readOnly'))}}</td>
                                            <td>{{Form::text('_labor_shop_total','', array('class'=>'form-control', 'id'=>'_labor_shop_total','readOnly'))}}</td>
                                          </tr>
                                          <tr>
                                            <td>Total Contract Hours</td>
                                            <td>{{Form::text('_labor_labor_cost_rate',number_format((empty($shopWorkQuoteTblRow['labor_labor_cost_rate'])?40:$shopWorkQuoteTblRow['labor_labor_cost_rate']),2), array('onkeyup'=>'cal_labor_total_price("labor")','class'=>'form-control', 'id'=>'_labor_labor_cost_rate'))}}</td>
                                            <td>{{Form::text('_labor_labor_rate',number_format((empty($shopWorkQuoteTblRow['labor_labor_list_rate'])?95:$shopWorkQuoteTblRow['labor_labor_list_rate']),2), array('onkeyup'=>'cal_labor_total_price("labor")','class'=>'form-control', 'id'=>'_labor_labor_rate'))}}</td>
                                            <td>{{Form::text('_labor_labor_hour','', array('onkeyup'=>'cal_labor_total_price("labor")','class'=>'form-control', 'id'=>'_labor_labor_hour'))}}</td>
                                            <td>{{Form::text('_labor_labor_cost_total','', array('class'=>'form-control', 'id'=>'_labor_labor_cost_total','readOnly'))}}</td>
                                            <td>{{Form::text('_labor_labor_total','', array('class'=>'form-control', 'id'=>'_labor_labor_total','readOnly'))}}</td>
                                          </tr>
                                          <tr>
                                            <td>Total Load Bank Hours</td>
                                            <td>{{Form::text('_labor_LBT_cost_rate',number_format((empty($shopWorkQuoteTblRow['labor_lbt_cost_rate'])?40:$shopWorkQuoteTblRow['labor_lbt_cost_rate']),2), array('onkeyup'=>'cal_labor_total_price("LBT")','class'=>'form-control', 'id'=>'_labor_LBT_cost_rate'))}}</td>
                                            <td>{{Form::text('_labor_LBT_rate',number_format((empty($shopWorkQuoteTblRow['labor_lbt_list_rate'])?142:$shopWorkQuoteTblRow['labor_lbt_list_rate']),2), array('onkeyup'=>'cal_labor_total_price("LBT")','class'=>'form-control', 'id'=>'_labor_LBT_rate'))}}</td>
                                            <td>{{Form::text('_labor_LBT_hour','', array('onkeyup'=>'cal_labor_total_price("LBT")','class'=>'form-control', 'id'=>'_labor_LBT_hour'))}}</td>
                                            <td>{{Form::text('_labor_LBT_cost_total','', array('class'=>'form-control', 'id'=>'_labor_LBT_cost_total','readOnly'))}}</td>
                                            <td>{{Form::text('_labor_LBT_total','', array('class'=>'form-control', 'id'=>'_labor_LBT_total','readOnly'))}}</td>
                                          </tr>
                                          <tr>
                                            <td>Total Over-Time Hours</td>
                                            <td>{{Form::text('_labor_OT_cost_rate',number_format((empty($shopWorkQuoteTblRow['labor_ot_cost_rate'])?60:$shopWorkQuoteTblRow['labor_ot_cost_rate']),2), array('onkeyup'=>'cal_labor_total_price("OT")','class'=>'form-control', 'id'=>'_labor_OT_cost_rate'))}}</td>
                                            <td>{{Form::text('_labor_OT_rate',number_format((empty($shopWorkQuoteTblRow['labor_ot_list_rate'])?142:$shopWorkQuoteTblRow['labor_ot_list_rate']),2), array('onkeyup'=>'cal_labor_total_price("OT")','class'=>'form-control', 'id'=>'_labor_OT_rate'))}}</td>
                                            <td>{{Form::text('_labor_OT_hour','', array('onkeyup'=>'cal_labor_total_price("OT")','class'=>'form-control', 'id'=>'_labor_OT_hour'))}}</td>
                                            <td>{{Form::text('_labor_OT_cost_total','', array('class'=>'form-control', 'id'=>'_labor_OT_cost_total','readOnly'))}}</td>
                                            <td>{{Form::text('_labor_OT_total','', array('class'=>'form-control', 'id'=>'_labor_OT_total','readOnly'))}}</td>
                                          </tr>
                                          <tr>
                                            <td>Total Sub-Con Hours</td>
                                            <td>{{Form::text('_labor_sub_con_cost_rate',number_format((empty($shopWorkQuoteTblRow['labor_sub_con_cost_rate'])?40:$shopWorkQuoteTblRow['labor_sub_con_cost_rate']),2), array('onkeyup'=>'cal_labor_total_price("sub_con")','class'=>'form-control', 'id'=>'_labor_sub_con_cost_rate'))}}</td>
                                            <td>{{Form::text('_labor_sub_con_rate',number_format((empty($shopWorkQuoteTblRow['labor_sub_con_list_rate'])?110:$shopWorkQuoteTblRow['labor_sub_con_list_rate']),2), array('onkeyup'=>'cal_labor_total_price("sub_con")','class'=>'form-control', 'id'=>'_labor_sub_con_rate'))}}</td>
                                            <td>{{Form::text('_labor_sub_con_hour','', array('onkeyup'=>'cal_labor_total_price("sub_con")','class'=>'form-control', 'id'=>'_labor_sub_con_hour'))}}</td>
                                            <td>{{Form::text('_labor_sub_con_cost_total','', array('class'=>'form-control', 'id'=>'_labor_sub_con_cost_total','readOnly'))}}</td>
                                            <td>{{Form::text('_labor_sub_con_total','', array('class'=>'form-control', 'id'=>'_labor_sub_con_total','readOnly'))}}</td>
                                          </tr>
                                          <tr>
                                            <td colspan="3">TOTAL LABOR</td>
                                            <td>{{Form::text('_labor_sub_hour_total','', array('class'=>'form-control', 'id'=>'_labor_sub_hour_total','readOnly'))}}</td>
                                            <td>{{Form::text('_labor_sub_cost_total','', array('class'=>'form-control', 'id'=>'_labor_sub_cost_total','readOnly'))}}</td>
                                            <td>{{Form::text('_labor_sub_total','', array('class'=>'form-control', 'id'=>'_labor_sub_total','readOnly'))}}</td>
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
              <!-- row3 end -->
              <!-- row4 -->
            <div class="row">
                  <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         TOTAL'S Details {{Form::button('<i class="fa fa-plus-circle"></i>', array('title'=>'Add New Row','class' => 'btn btn-primary btn-xs','id'=>'add_totals_row'))}}
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="mytable5">
                                        <thead class="cf">
                                          <tr>
                                            <th>Del</th>
                                            <th>QTY</th>
                                            <th colspan="5">Description</th>
                                            <th>Cost Price</th>
                                            <th>List Price</th>
                                            <th>Cost Total</th>
                                            <th>List Total</th>
                                          </tr>
                                        </thead>  
                                        <tbody class="cf">
                                          @if(!empty($queryOtherCharge))
                                            <?php $i=1; ?>
                                            @foreach($queryOtherCharge as $queryOtherChargeResRow)
                                              <tr>
                                                <input type="hidden" value="{{$queryOtherChargeResRow['id']}}" name="otherId_{{$i}}">
                                                <td>{{Form::button('<i class="fa fa-trash-o"></i>', array('title'=>'Delete','class' => 'btn btn-danger btn-xs','name'=>'delete_row','id'=>$queryOtherChargeResRow['id'],'table'=>'gpg_shop_work_quote_other'))}}</td>  
                                                <td>{{Form::text('_other_charge_qty_'.$i,$queryOtherChargeResRow['other_charge_qty'], array('class'=>'form-control', 'id'=>'_other_charge_qty_'.$i))}}</td>
                                                <td>{{Form::text('_other_charge_description_'.$i,(isset($queryOtherChargeResRow['other_charge_description'])?$queryOtherChargeResRow['other_charge_description']:''), array('class'=>'form-control', 'id'=>'_other_charge_description_'.$i))}}</td>
                                                <td colspan="5">{{Form::text('_other_charge_cost_price_'.$i,$queryOtherChargeResRow['other_charge_cost_price'], array('class'=>'form-control', 'id'=>'_other_charge_cost_price_'.$i,'onkeyup'=>'cal_other_charges('.$i.')'))}}</td>
                                                <td>{{Form::text('_other_charge_price_'.$i,$queryOtherChargeResRow['other_charge_price'], array('class'=>'form-control', 'id'=>'_other_charge_price_'.$i,'onkeyup'=>'cal_other_charges('.$i.')'))}}</td>
                                                <td>{{Form::text('_other_charge_cost_total_'.$i,round($queryOtherChargeResRow['other_charge_qty']*$queryOtherChargeResRow['other_charge_cost_price'],2), array('class'=>'form-control', 'id'=>'_other_charge_cost_total_'.$i,'readOnly'))}}</td>
                                                <td>{{Form::text('_other_charge_total_'.$i,round($queryOtherChargeResRow['other_charge_qty']*$queryOtherChargeResRow['other_charge_price'],2), array('class'=>'form-control', 'id'=>'_other_charge_total_'.$i,'readOnly'))}}</td>
                                              </tr>
                                                <input type="hidden" name="totals_counter" id="totals_counter" value="{{$i}}">
                                                <?php $i++; ?>
                                            @endforeach
                                          @else
                                          <tr>
                                            <input type="hidden" value="" name="otherId_1">
                                            <td>x</td>  
                                            <td>{{Form::text('_other_charge_qty_1','', array('class'=>'form-control', 'id'=>'_other_charge_qty_1'))}}</td>
                                            <td>{{Form::text('_other_charge_description_1','Mileage', array('class'=>'form-control', 'id'=>'_other_charge_description_1','readOnly'))}}</td>
                                            <td colspan="5">{{Form::text('_other_charge_cost_price_1','', array('class'=>'form-control', 'id'=>'_other_charge_cost_price_1','onkeyup'=>'cal_other_charges(1)'))}}</td>
                                            <td>{{Form::text('_other_charge_price_1','', array('class'=>'form-control', 'id'=>'_other_charge_price_1','onkeyup'=>'cal_other_charges(1)'))}}</td>
                                            <td>{{Form::text('_other_charge_cost_total_1','', array('class'=>'form-control', 'id'=>'_other_charge_cost_total_1','readOnly'))}}</td>
                                            <td>{{Form::text('_other_charge_total_1','', array('class'=>'form-control', 'id'=>'_other_charge_total_1','readOnly'))}}</td>
                                          </tr>
                                          <tr>
                                            <td>x</td>  
                                            <td>{{Form::text('_other_charge_qty_2','', array('class'=>'form-control', 'id'=>'_other_charge_qty_2'))}}</td>
                                            <td>{{Form::text('_other_charge_description_2','Freight', array('class'=>'form-control', 'id'=>'_other_charge_description_2','readOnly'))}}</td>
                                            <td colspan="5">{{Form::text('_other_charge_cost_price_2','', array('class'=>'form-control', 'id'=>'_other_charge_cost_price_2','onkeyup'=>'cal_other_charges(2)'))}}</td>
                                            <td>{{Form::text('_other_charge_price_2','', array('class'=>'form-control', 'id'=>'_other_charge_price_2','onkeyup'=>'cal_other_charges(2)'))}}</td>
                                            <td>{{Form::text('_other_charge_cost_total_2','', array('class'=>'form-control', 'id'=>'_other_charge_cost_total_2','readOnly'))}}</td>
                                            <td>{{Form::text('_other_charge_total_2','', array('class'=>'form-control', 'id'=>'_other_charge_total_2','readOnly'))}}</td>
                                          </tr>
                                            <input type="hidden" name="totals_counter" id="totals_counter" value="2">
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
              <!-- row4 end-->
            <!-- row5 -->
            <div class="row">
                 <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="mytable2">
                                        <tbody class="cf">
                                        <tr><td>{{HTML::link('#myModal4', 'Manage Files' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'manage_files','id'=>$job_id,'job_num'=>$job_num))}}</td></tr>
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
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="mytable2">
                                        <tbody class="cf">
                                          <tr><th colspan="2">TOTAL OTHER CHARGES</th><td>{{Form::text('_other_charge_sub_cost_total','', array('class'=>'form-control', 'id'=>'_other_charge_sub_cost_total','readOnly'))}}</td><td>{{Form::text('_other_charge_sub_total','', array('class'=>'form-control', 'id'=>'_other_charge_sub_total','readOnly'))}}</td></tr>
                                          <tr><th colspan="2">SUB TOTAL</th><td>{{Form::text('_sub_cost_total','', array('class'=>'form-control', 'id'=>'_sub_cost_total','readOnly'))}}</td><td>{{Form::text('_sub_total','', array('class'=>'form-control', 'id'=>'_sub_total','readOnly'))}}</td></tr>
                                          <tr><th colspan="2">MISC. & HAZMAT{{Form::text('_hazmat',number_format((empty($shopWorkQuoteTblRow['hazmat'])?6:$shopWorkQuoteTblRow['hazmat']),2), array('style'=>'width:60px; display:inline;','class'=>'form-control', 'id'=>'_hazmat','onkeyup'=>'cal_total_all()'))}} </th><td>{{Form::text('_hazmat_cost_total','', array('class'=>'form-control', 'id'=>'_hazmat_cost_total','readOnly'))}}</td><td>{{Form::text('_hazmat_total','', array('class'=>'form-control', 'id'=>'_hazmat_total','readOnly'))}}</td></tr>
                                          <tr><th colspan="2">TAX{{Form::text('_tax',number_format((empty($shopWorkQuoteTblRow['tax_amount'])?9.75:$shopWorkQuoteTblRow['tax_amount']),2), array('style'=>'width:60px; display:inline;','class'=>'form-control', 'id'=>'_tax','onkeyup'=>'cal_total_all()'))}} </th><td>{{Form::text('_tax_cost_total','', array('class'=>'form-control', 'id'=>'_tax_cost_total','readOnly'))}}</td><td>{{Form::text('_tax_total','', array('class'=>'form-control', 'id'=>'_tax_total','readOnly'))}}</td></tr>
                                          <tr><th colspan="2">TOTAL  </th><td>{{Form::text('_grand_cost_total','', array('class'=>'form-control', 'id'=>'_grand_cost_total','readOnly'))}}</td><td>{{Form::text('_grand_total','', array('class'=>'form-control', 'id'=>'_grand_total','readOnly'))}}</td></tr>
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
              <!-- row5 end-->  
        </section>
  </section>
    <div class="btn-group" style="padding:20px;">
      {{Form::button('Save/Update Changes', array('class' => 'btn btn-primary', 'id'=>'submit_main_form'))}}
      {{ Form::button('Export PDF' , array('id'=>'getElecticalQuotePdfFile','class'=>'btn btn-danger'))}} 
    </div>
  {{Form::close()}}
  {{HTML::link('#myModal', '' , array('class' => 'btn btn-link','data-toggle'=>'modal','id'=>'click_modal'))}}
  {{HTML::link('#myModal2', '' , array('class' => 'btn btn-link','data-toggle'=>'modal','id'=>'click_mat_modal'))}}
</section>
  <!-- Modal# 1-->
           <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ADD NEW TYPE & PART</h4>
                      </div>
                    <div class="modal-body">
                 {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('quote/addNewTypenPart'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('job_id',$job_id)}} {{Form::hidden('job_num',$job_num)}} {{Form::hidden('type','comp')}}{{Form::hidden('_gpg_field_component_type_id','',['id'=>'_gpg_field_component_type_id'])}}     
                    <div class="form-group">
                      <section id="no-more-tables"  style="padding:10px;">
                        <table class="table table-bordered table-striped table-condensed cf">
                          <tbody class="cf">
                          <tr  id="type_name_tr"><th>Type Name*:</th><td>{{Form::text('_type_name','', array('class'=>'form-control', 'id'=>'_type_name'))}}</td></tr>
                          <tr><th>Description:</th><td>{{Form::text('_description','', array('class'=>'form-control', 'id'=>'_description','readOnly'))}}</td></tr>
                          <tr><th>Part Number:</th><td>{{Form::text('_part_number','', array('class'=>'form-control', 'id'=>'_part_number'))}}</td></tr>
                          <tr><th>Manufacturer:</th><td>{{Form::text('_manufacturer','', array('class'=>'form-control', 'id'=>'_manufacturer'))}}</td></tr>
                          <tr><th>Cost:</th><td>{{Form::text('_cost','', array('class'=>'form-control', 'id'=>'_cost'))}}</td></tr>
                          <tr><th>Margin:</th><td>{{Form::text('_margin','', array('class'=>'form-control', 'id'=>'_margin'))}}</td></tr>
                          <tr><th>List:</th><td>{{Form::text('_list','', array('class'=>'form-control', 'id'=>'_list'))}}</td></tr>
                          <tr><th>Vendor:</th><td>{{Form::select('_gpg_vendor_id',array(''=>'-')+$gpg_vendor,'', ['class'=>'form-control','id'=>'_gpg_vendor_id'])}} 
                           <span style='color:red;'>If New Vendor?<span> 
                          {{Form::text('new_vendor_name','', array('class'=>'form-control', 'id'=>'new_vendor_name'))}}</td></tr>
                          <tr><th>Vendor Cost:</th><td>{{Form::text('_gpg_vendor_cost','', array('class'=>'form-control', 'id'=>'_gpg_vendor_cost'))}}</td></tr>
                          <tr><th>Notes:</th><td>{{Form::text('_note','', array('class'=>'form-control', 'id'=>'_note'))}}</td></tr>
                          <tr><th>Model #:</th><td>{{Form::text('_model_number','', array('class'=>'form-control', 'id'=>'_model_number'))}}</td></tr>
                          <tr><th>Serial #:</th><td>{{Form::text('_serial_number','', array('class'=>'form-control', 'id'=>'_serial_number'))}}</td></tr>
                          <tr><th>Spec #:</th><td>{{Form::text('_spec_number','', array('class'=>'form-control', 'id'=>'_spec_number'))}}</td></tr>
                          </tbody>
                        </table>
                      </section> 
                   {{Form::close()}}
                  <div class="btn-group" style="padding:20px;">
                    {{Form::button('Save', array('class' => 'btn btn-success', 'id'=>'submit_type_part_modal'))}}
                   {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                  </div>
                </div>
              </div>
            </div>
        </div>
        </div>
        <!-- modal# 1 end--> 
        <!-- Modal# 2-->
           <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ADD NEW TYPE & PART</h4>
                      </div>
                    <div class="modal-body">
                 {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_quotemat_form','url'=>route('quote/addNewTypenPart'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('job_id',$job_id)}} {{Form::hidden('job_num',$job_num)}} {{Form::hidden('type','mat')}}{{Form::hidden('_gpg_field_material_type_id','',['id'=>'_gpg_field_material_type_id'])}}     
                    <div class="form-group">
                      <section id="no-more-tables"  style="padding:10px;">
                        <table class="table table-bordered table-striped table-condensed cf">
                          <tbody class="cf">
                          <tr  id="type_name_tr2"><th>Type Name*:</th><td>{{Form::text('_type_name','', array('class'=>'form-control', 'id'=>'_type_name2'))}}</td></tr>
                          <tr><th>Description:</th><td>{{Form::text('_description','', array('class'=>'form-control', 'id'=>'_description','readOnly'))}}</td></tr>
                          <tr><th>Part Number:</th><td>{{Form::text('_part_number','', array('class'=>'form-control', 'id'=>'_part_number2'))}}</td></tr>
                          <tr><th>Manufacturer:</th><td>{{Form::text('_manufacturer','', array('class'=>'form-control', 'id'=>'_manufacturer'))}}</td></tr>
                          <tr><th>Cost:</th><td>{{Form::text('_cost','', array('class'=>'form-control', 'id'=>'_cost'))}}</td></tr>
                          <tr><th>Margin:</th><td>{{Form::text('_margin','', array('class'=>'form-control', 'id'=>'_margin'))}}</td></tr>
                          <tr><th>List:</th><td>{{Form::text('_list','', array('class'=>'form-control', 'id'=>'_list'))}}</td></tr>
                          <tr><th>Vendor:</th><td>{{Form::select('_gpg_vendor_id',array(''=>'-')+$gpg_vendor,'', ['class'=>'form-control','id'=>'_gpg_vendor_id'])}} 
                          <span style='color:red;'>If New Vendor?<span> 
                          {{Form::text('new_vendor_name','', array('class'=>'form-control', 'id'=>'new_vendor_name'))}}</td></tr>
                          <tr><th>Vendor Cost:</th><td>{{Form::text('_gpg_vendor_cost','', array('class'=>'form-control', 'id'=>'_gpg_vendor_cost'))}}</td></tr>
                          <tr><th>Notes:</th><td>{{Form::text('_note','', array('class'=>'form-control', 'id'=>'_note'))}}</td></tr>
                          <tr><th>Model #:</th><td>{{Form::text('_model_number','', array('class'=>'form-control', 'id'=>'_model_number'))}}</td></tr>
                          <tr><th>Serial #:</th><td>{{Form::text('_serial_number','', array('class'=>'form-control', 'id'=>'_serial_number'))}}</td></tr>
                          <tr><th>Spec #:</th><td>{{Form::text('_spec_number','', array('class'=>'form-control', 'id'=>'_spec_number'))}}</td></tr>
                          </tbody>
                        </table>
                      </section> 
                   {{Form::close()}}
                  <div class="btn-group" style="padding:20px;">
                    {{Form::button('Save', array('class' => 'btn btn-success', 'id'=>'submit_mat_type_modal'))}}
                   {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                  </div>
                </div>
              </div>
            </div>
        </div>
        </div>
        <!-- modal# 2 end--> 
         <!-- Modal# 3-->
           <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ATTACHMENT MANAGEMENT</h4>
                      </div>
                    <div class="modal-body">
                 {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_my_files','url'=>route('quote/manageQuoteFiles'),'files'=>true, 'method' => 'post')) }}     
                   {{Form::hidden('fjob_id',$job_id)}} {{Form::hidden('fjob_num',$job_num)}} 
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
          <!-- Modal# 5-->
           <div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title"> Equipment List</h4>
                      </div>
                    <div class="modal-body">
                     <div class="form-group">
                         <section id="no-more-tables"  style="padding:10px;">
                          <table class="table table-bordered table-striped table-condensed cf">
                           <thead class="cf">
                            <tr><th>#</th><th>Select Equipment</th></tr>
                            </thead>
                              <tbody class="cf" id="list_equips">

                              </tbody>
                            </table>
                         </section> 
                   </div>
                   {{Form::close()}}
                  <div class="btn-group" style="padding:20px;">
                    {{Form::button('Submit', array('class' => 'btn btn-success', 'id'=>'submit_equips'))}}
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
    $('#submit_main_form').click(function(){
      $('#update_shop_work_quote').submit();
    })
    $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
    });
    $('.timepicker-default').timepicker();
    
    var eqpcount = parseInt('1')+parseInt($('#equip_count').val());
    $('#add_new_row').click(function(){
      str = '<tr><td>x</td><td><a class="btn btn-link" type="comp" row="'+eqpcount+'" name="search_equipment" data-toggle="modal" href="#myModal5">Eqp.</a></td>';
      str +='<td><input type="text" value="" name="_comp_quantity_'+eqpcount+'" onkeyup="cal_component('+eqpcount+')" id="_comp_quantity_'+eqpcount+'" class="form-control"></td>'; 
      str +='<td><select name="_comp_component_'+eqpcount+'" id="_comp_component_'+eqpcount+'" class="form-control">'+document.getElementById('_comp_component_1').innerHTML+'</select></td>';
      str +='<td><select name="_comp_part_'+eqpcount+'" onchange="fill_component(this,'+eqpcount+')" id="_comp_part_'+eqpcount+'" class="form-control"><option selected="selected" value="">-</option></select></td>';
      str +='<td><input type="text" value="" name="comp_manufacturer_label_'+eqpcount+'" readonly="readOnly" id="comp_manufacturer_label_'+eqpcount+'" class="form-control"></td>';
      str +='<td><input type="text" value="" name="comp_vendor_label_'+eqpcount+'" readonly="readOnly" id="comp_vendor_label_'+eqpcount+'" class="form-control"></td>';
      str +='<td><input type="text" value="" name="_comp_cost_'+eqpcount+'" onkeyup="cal_sell_margin('+eqpcount+',2)" onblur="cal_sell_margin('+eqpcount+',2)" id="_comp_cost_'+eqpcount+'" class="form-control"></td>';
      str +='<td><input type="text" value="" name="_comp_list_'+eqpcount+'" onkeyup="cal_component('+eqpcount+')" id="_comp_list_'+eqpcount+'" class="form-control"></td>';
      str +='<td><input type="text" value="" name="_comp_vendor_cost_'+eqpcount+'" id="_comp_vendor_cost_'+eqpcount+'" class="form-control"></td>';
      str +='<td><input type="text" value="" name="_comp_sell_price_cost_'+eqpcount+'" onkeyup="cal_sell_margin('+eqpcount+', 1)" onblur="cal_sell_margin('+eqpcount+',1)" id="_comp_sell_price_cost_'+eqpcount+'" class="form-control"></td>';
      str +='<td><input type="text" value="" name="_comp_margin_'+eqpcount+'" onkeyup="cal_sell_margin('+eqpcount+',2)" onblur="cal_sell_margin('+eqpcount+', 2)" id="_comp_margin_'+eqpcount+'" class="form-control"></td>';
      str +='<td><input type="text" value="" name="_comp_cost_total_'+eqpcount+'" readonly="readOnly" id="_comp_cost_total_'+eqpcount+'" class="form-control"></td>';
      str +='<td><input type="text" value="" name="_comp_total_'+eqpcount+'" readonly="readOnly" id="_comp_total_'+eqpcount+'" class="form-control"></td></tr>';
      $('#mytable tr:last').before(str);
      
      $('#_comp_component_'+eqpcount).change(function(){
      if($(this).val() == 'NEWCOMPONENT'){
         document.getElementById('click_modal').click();
      }else{
          var abc = eqpcount-1;
          var id = $('#_comp_component_'+abc+' option:selected').val();
          $.ajax({
            url: "{{URL('ajax/getFieldCompnMat')}}",
              data: {
                'id' : id,
                'type':'comp',
                'now':''
              },
              success: function (data) {
                $('#_comp_part_'+(eqpcount-1)).html(data);
              },
          });     
      }
    });
      $('#equip_count').val(parseInt($('#equip_count').val())+parseInt('1'));
      eqpcount = parseInt(eqpcount) + parseInt("1");
    }); 
      /*-- end of add line -- */
      /* -- for second table start -- */
    var matcount = parseInt('1')+parseInt($('#mat_count').val());
    $('#add_material_row').click(function(){
      str1 = '<tr> <td>x</td><td><a class="btn btn-link" type="mat" row="'+matcount+'" name="search_equipment" data-toggle="modal" href="#myModal5">Part</a></td>';
      str1 += '<td><input type="text" value="" name="_mat_quantity_'+matcount+'" onkeyup="cal_material('+matcount+')" id="_mat_quantity_'+matcount+'" class="form-control"></td>';
      str1 += '<td><select name="_mat_material_'+matcount+'" id="_mat_material_'+matcount+'" class="form-control">'+document.getElementById('_mat_material_1').innerHTML+'</select></td>';
      str1 += '<td><select name="_mat_part_'+matcount+'" onchange="fill_material(this,'+matcount+')" id="_mat_part_'+matcount+'" class="form-control"><option selected="selected" value="">-</option></select></td>';
      str1 += '<td><input type="text" value="" name="mat_description_label_'+matcount+'" readonly="readOnly" id="mat_description_label_'+matcount+'" class="form-control"></td>';
      str1 += '<td><input type="text" value="" name="mat_manufacturer_label_'+matcount+'" readonly="readOnly" id="mat_manufacturer_label_'+matcount+'" class="form-control"></td>';
      str1 += '<td><input type="text" value="" name="mat_vendor_label_'+matcount+'" readonly="readOnly" id="mat_vendor_label_'+matcount+'" class="form-control"></td>';
      str1 += '<td><input type="text" value="" name="_mat_cost_'+matcount+'" onkeyup="cal_mat_sell_margin('+matcount+',2)" id="_mat_cost_'+matcount+'" class="form-control"></td>';
      str1 += '<td><input type="text" value="" name="_mat_list_'+matcount+'" onkeyup="cal_material('+matcount+')" id="_mat_list_'+matcount+'" class="form-control"></td>';
      str1 += '<td><input type="text" value="" name="_mat_vendor_cost_'+matcount+'" id="_mat_vendor_cost_'+matcount+'" class="form-control"></td>';
      str1 += '<td><input type="text" value="" name="_mat_sell_price_'+matcount+'" onkeyup="cal_mat_sell_margin('+matcount+', 1)" id="_mat_sell_price_'+matcount+'" class="form-control"></td>';
      str1 += '<td><input type="text" value="" name="_mat_margin_'+matcount+'" onkeyup="cal_mat_sell_margin('+matcount+',2)" id="_mat_margin_'+matcount+'" class="form-control"></td>';
      str1 += '<td><input type="text" value="" name="_mat_cost_total_'+matcount+'" readonly="readOnly" id="_mat_cost_total_'+matcount+'" class="form-control"></td>';
      str1 += '<td><input type="text" value="" name="_mat_total_'+matcount+'" readonly="readOnly" id="_mat_total_'+matcount+'" class="form-control"></td></tr>';
      $('#mytable2 tr:last').before(str1);
      
      $('#_mat_material_'+matcount).change(function(){
       if($(this).val() == 'NEWMATERIAL'){
         document.getElementById('click_mat_modal').click();
      }else{
        var def = matcount-1;
            var id = $('#_mat_material_'+def+' option:selected').val();
            $.ajax({
              url: "{{URL('ajax/getFieldCompnMat')}}",
                data: {
                  'id' : id,
                  'type':'mat',
                  'now':''
                },
                success: function (data) {
                  $('#_mat_part_'+def).html(data);
                },
            });     
        }
      });

      $('#mat_count').val(parseInt($('#mat_count').val())+parseInt('1'));
      matcount = parseInt(matcount) + parseInt("1");
    });
      /* -- for second table end -- */
      /* -- labor counter add new row starts--*/
      var lbrcounter = parseInt('1')+parseInt($('#labor_counter').val());
    $('#add_row_scope_work').click(function(){
       str2='<tr><td>x</td>'; 
       str2 +='<td><input type="text" value="" name="_labor_scope_'+lbrcounter+'" id="_labor_scope_'+lbrcounter+'" class="form-control"></td>'; 
       str2 +='<td><input type="text" value="" name="_labor_shop_'+lbrcounter+'" id="_labor_shop_'+lbrcounter+'" class="form-control" onkeyup="cal_labor_total('+"'shop'"+')"></td>'; 
       str2 +='<td><input type="text" value="" name="_labor_labor_'+lbrcounter+'" id="_labor_labor_'+lbrcounter+'" class="form-control" onkeyup="cal_labor_total('+"'labor'"+')"></td>'; 
       str2 +='<td><input type="text" value="" name="_labor_LBT_'+lbrcounter+'" id="_labor_LBT_'+lbrcounter+'" class="form-control" onkeyup="cal_labor_total('+"'LBT'"+')"></td>'; 
       str2 +='<td><input type="text" value="" name="_labor_OT_'+lbrcounter+'" id="_labor_OT_'+lbrcounter+'" class="form-control" onkeyup="cal_labor_total('+"'OT'"+')"></td>'; 
       str2 +='<td><input type="text" value="" name="_labor_sub_con_'+lbrcounter+'" id="_labor_sub_con_'+lbrcounter+'" class="form-control" onkeyup="cal_labor_total('+"'sub_con'"+')"></td></tr>'; 
       
      $('#mytable3 tr:last').after(str2);
      $('#labor_counter').val(parseInt($('#labor_counter').val())+parseInt('1'));
      lbrcounter = parseInt(lbrcounter) + parseInt("1");
    });
      /* -- labor counter add new row ends--*/
      /* -- add row for sub table --*/
        var subcounter = parseInt('1')+parseInt($('#subl_counter').val());
        $('#add_sub_row').click(function(){
          str3 = '<tr><td>x</td>';
          str3 += '<td><input type="text" value="" name="_sub_labor_scope_'+subcounter+'" id="_sub_labor_scope_'+subcounter+'" class="form-control"></td>';
          str3 += '<td><input type="text" value="" name="_sub_labor_shop_'+subcounter+'" id="_sub_labor_shop_'+subcounter+'" class="form-control" onkeyup="cal_labor_total('+"'shop'"+')"></td>';
          str3 += '<td><input type="text" value="" name="_sub_labor_labor_'+subcounter+'" id="_sub_labor_labor_'+subcounter+'" class="form-control" onkeyup="cal_labor_total('+"'labor'"+')"></td>';
          str3 += '<td><input type="text" value="" name="_sub_labor_LBT_'+subcounter+'" id="_sub_labor_LBT_'+subcounter+'" class="form-control" onkeyup="cal_labor_total('+"'LBT'"+')"></td>';
          str3 += '<td><input type="text" value="" name="_sub_labor_OT_'+subcounter+'" id="_sub_labor_OT_'+subcounter+'" class="form-control" onkeyup="cal_labor_total('+"'OT'"+')"></td>';
          str3 += '<td><input type="text" value="" name="_sub_labor_sub_con_'+subcounter+'" id="_sub_labor_sub_con_'+subcounter+'" class="form-control" onkeyup="cal_labor_total('+"'sub_con'"+')"></td></tr>';

          $('#mytable4 tr:last').after(str3);
          $('#subl_counter').val(parseInt($('#subl_counter').val())+parseInt('1'));
          subcounter = parseInt(subcounter) + parseInt("1");
        });
      /* -- add row for sub table --*/
      /* -- add row for table5 --*/
      var tcounter = parseInt('1')+parseInt($('#totals_counter').val());
        $('#add_totals_row').click(function(){
          var str4 = '<tr><td>x</td>';
          str4 += '<td><input type="text" value="" name="_other_charge_qty_'+tcounter+'" id="_other_charge_qty_'+tcounter+'" class="form-control"></td>';
          str4 += '<td><input type="text" value="" name="_other_charge_description_'+tcounter+'" id="_other_charge_description_'+tcounter+'" class="form-control"></td>';
          str4 += '<td colspan="5"><input type="text" value="" name="_other_charge_cost_price_'+tcounter+'" onkeyup="cal_other_charges('+tcounter+')" id="_other_charge_cost_price_'+tcounter+'" class="form-control"></td>';
          str4 += '<td><input type="text" value="" name="_other_charge_price_'+tcounter+'" onkeyup="cal_other_charges('+tcounter+')" id="_other_charge_price_'+tcounter+'" class="form-control"></td>';
          str4 += '<td><input type="text" value="" name="_other_charge_cost_total_'+tcounter+'" readonly="readOnly" id="_other_charge_cost_total_'+tcounter+'" class="form-control"></td>';
          str4 += '<td><input type="text" value="" name="_other_charge_total_'+tcounter+'" readonly="readOnly" id="_other_charge_total_'+tcounter+'" class="form-control"></td></tr>';

          $('#mytable5 tr:last').after(str4);
          $('#totals_counter').val(parseInt($('#totals_counter').val())+parseInt('1'));
          tcounter = parseInt(tcounter) + parseInt("1");
      });
      /* -- add row for table5 end --*/

    $('#submit_type_part_modal').click(function(){
      if($('#_type_name').val() != ''){
        $('#submit_file_form').submit();
      }else if($('#_part_number').val() != ''){
        $('#submit_file_form').submit();
      }else{
        alert('Type Name can not be empty!');
      }
    });
    
    $('#submit_mat_type_modal').click(function(){
      if($('#_type_name2').val() != ''){
        $('#submit_quotemat_form').submit();
      }else if($('#_part_number2').val() != ''){
        $('#submit_quotemat_form').submit();
      }else{
        alert('Type Name can not be empty!');
      }
    });

    $('#_comp_component_1').change(function(){
      if($(this).val() == 'NEWCOMPONENT'){
         document.getElementById('click_modal').click();
      }else{
          var id = $('#_comp_component_1 option:selected').val();
          $.ajax({
            url: "{{URL('ajax/getFieldCompnMat')}}",
              data: {
                'id' : id,
                'type':'comp',
                'now':''
              },
              success: function (data) {
                $('#_comp_part_1').html(data);
              },
          });     
      }
    });

     $('#_mat_material_1').change(function(){
      if($(this).val() == 'NEWMATERIAL'){
         document.getElementById('click_mat_modal').click();
      }else{
          var id = $('#_mat_material_1 option:selected').val();
          $.ajax({
            url: "{{URL('ajax/getFieldCompnMat')}}",
              data: {
                'id' : id,
                'type':'mat',
                'now':''
              },
              success: function (data) {
                $('#_mat_part_1').html(data);
              },
          });     
      }
    });
    $('button[name=delete_row]').click(function(){
      var id = $(this).attr('id');
      var table = $(this).attr('table');
      var conf = confirm('Are you sure, you want to delete this...!');
      if(conf && table!='' && id != ''){
         $.ajax({
            url: "{{URL('ajax/getFieldCompnMat')}}",
              data: {
                'id' : id,
                'table':table
              },
              success: function (data) {
                if(data == '1'){
                  alert('Deleted row successfully!').
                  location.reload();     
                }
              },
          });
      }
    });
      $('a[name=manage_files]').click(function(){
        var job_num = $(this).attr('job_num');
        var job_id = $(this).attr('id');
        $.ajax({
              url: "{{URL('ajax/getQuoteFiles')}}",
              data: {
                'id' : job_id,
                'num': job_num,
                'table':'Shop Work'
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
                          'table':'Shop Work'
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
  $('#submit_attachments').click(function(){
    $('#submit_my_files').submit();
  });

  $('a[name=search_equipment]').click(function(){
    var row = $(this).attr('row');
    var type = $(this).attr('type');
      $.ajax({
        url: "{{URL('ajax/searchParts')}}",
          data: {
            'type' : type,
            'row':row
          },
          success: function (data) {
          $('#list_equips').html(data);
          $('button[name=clickme]').click(function(){
            var id = $(this).attr('id');
            var type = $(this).attr('type');
            var itt = $(this).attr('row');
              if(type == 'comp'){
                $.ajax({
                    url: "{{URL('ajax/getFieldCompnMat')}}",
                      data: {
                        'id' : id,
                        'type':type,
                        'now':'fillcomp'
                      },
                      success: function (data) {
                        $('#_comp_component_'+itt).val(id);
                        $('#comp_manufacturer_label_'+itt).val(data.manufacturer);
                        $('#comp_vendor_label_'+itt).val(data.name);
                        $('#_comp_cost_'+itt).val(data.cost);
                        $('#_comp_list_'+itt).val(data.list);
                        $('#_comp_margin_'+itt).val(data.margin);
                        $('#_comp_vendor_cost_'+itt).val(data.gpg_vendor_cost);
                        $('#_comp_sell_price_cost_'+itt).val(data.list);
                        $('#_comp_cost_total_'+itt).val(data.cost);
                        $('#_comp_total_'+itt).val(data.list);
                        $('#myModal5').modal('hide');
                      },
                });
              }else if(type == 'mat'){
                $.ajax({
                    url: "{{URL('ajax/getFieldCompnMat')}}",
                      data: {
                        'id' : id,
                        'type':type,
                        'now':'fillcomp'
                      },
                      success: function (data) {
                        $('#_mat_material_'+itt).val(id);
                        $('#mat_description_label_'+itt).val(data.description);
                        $('#mat_manufacturer_label_'+itt).val(data.manufacturer);
                        $('#mat_vendor_label_'+itt).val(data.gpg_vendor_id);
                        $('#_mat_cost_'+itt).val(data.cost);
                        $('#_mat_list_'+itt).val(data.list);
                        $('#_mat_margin_'+itt).val(data.margin);
                        $('#_mat_vendor_cost_'+itt).val(data.gpg_vendor_cost);
                        $('#_mat_sell_price_cost_'+itt).val(data.list);
                        $('#_mat_cost_total_'+itt).val(data.cost);
                        $('#_mat_total_'+itt).val(data.cost);
                        $('#myModal5').modal('hide');
                      },
                  });
              }
          });
        },
      });

  });


  function fill_material(obj, itt){
   if(obj.value == 'NEWMATERIAL'){
        var id = $('#_mat_material_'+itt+' option:selected').val();
        $('#_gpg_field_material_type_id').val(id);
        $('#_type_name2').hide();
        $('#type_name_tr2').hide(); 
        $('#submit_quotemat_form').attr('action','{{route("quote/addNewPart")}}');     
        document.getElementById('click_mat_modal').click();
      }else{
      var id = $('#_mat_material_'+itt+' option:selected').val();
          $.ajax({
            url: "{{URL('ajax/getFieldCompnMat')}}",
              data: {
                'id' : id,
                'type':'mat',
                'now':'fillcomp'
              },
              success: function (data) {
                $('#mat_description_label_'+itt).val(data.description);
                $('#mat_manufacturer_label_'+itt).val(data.manufacturer);
                $('#mat_vendor_label_'+itt).val(data.gpg_vendor_id);
                $('#_mat_cost_'+itt).val(data.cost);
                $('#_mat_list_'+itt).val(data.list);
                $('#_mat_margin_'+itt).val(data.margin);
                $('#_mat_vendor_cost_'+itt).val(data.gpg_vendor_cost);
                $('#_mat_sell_price_cost_'+itt).val(data.list);
                $('#_mat_cost_total_'+itt).val(data.cost);
                $('#_mat_total_'+itt).val(data.cost);
              },
          }); 
    }

  }

  function fill_component(obj, itt){
    if(obj.value == 'NEWCOMPONENT'){
      var id = $('#_comp_component_'+itt+' option:selected').val();
      $('#_gpg_field_component_type_id').val(id);
      $('#_type_name').hide();
      $('#type_name_tr').hide(); 
      $('#submit_file_form').attr('action','{{route("quote/addNewPart")}}');     
      document.getElementById('click_modal').click();
    }else{
      var id = $('#_comp_component_'+itt+' option:selected').val();
          $.ajax({
            url: "{{URL('ajax/getFieldCompnMat')}}",
              data: {
                'id' : id,
                'type':'comp',
                'now':'fillcomp'
              },
              success: function (data) {
                $('#comp_manufacturer_label_'+itt).val(data.manufacturer);
                $('#comp_vendor_label_'+itt).val(data.name);
                $('#_comp_cost_'+itt).val(data.cost);
                $('#_comp_list_'+itt).val(data.list);
                $('#_comp_margin_'+itt).val(data.margin);
                $('#_comp_vendor_cost_'+itt).val(data.gpg_vendor_cost);
                $('#_comp_sell_price_cost_'+itt).val(data.list);
                $('#_comp_cost_total_'+itt).val(data.cost);
                $('#_comp_total_'+itt).val(data.list);
              },
          }); 
    }
  }

  function cal_component(itt) {
     var comp_cost_val = parseFloat($('#_comp_quantity_'+itt).val()*$('#_comp_cost_'+itt).val());
     var comp_val = parseFloat($('#_comp_quantity_'+itt).val()*$('#_comp_list_'+itt).val());
     $('#_comp_cost_total_'+itt).val((!isNaN(comp_cost_val)?roundNumber(comp_cost_val,2):0));
     $('#_comp_total_'+itt).val((!isNaN(comp_val)?roundNumber(comp_val,2):0));
     cal_total_all();
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
  function cal_total_all() {
     var total_amount = 0;
     var total_cost_amount = 0;
     var material_cost_amount = 0;
     var component_cost_amount = 0;
     var material_amount = 0;
     var component_amount = 0;
     var labor_amount = 0;
     var labor_cost_amount = 0;
     var labor_hour = 0;
     var other_charge_amount = 0;
     var other_charge_cost_amount = 0;
     var counter = 1;
     var fval = '';
      while (1) {
        if(typeof $("#_comp_total_"+counter).val() != 'undefined') { 
          fval = parseFloat($("#_comp_cost_total_"+counter).val());
           if (!isNaN(fval)) 
              component_cost_amount+=fval;
              fval = parseFloat($("#_comp_total_"+counter).val());
           if (!isNaN(fval)) 
              component_amount+=fval;
        }
        else break;
        counter++;
      }
    $('#_comp_sub_cost_total').val(roundNumber(component_cost_amount,2));  
    $('#_comp_sub_total').val(roundNumber(component_amount,2));  
    counter = 1;  
    while (1) {
      if(typeof $("#_mat_total_"+counter).val() != 'undefined') { 
          fval = parseFloat($("#_mat_cost_total_"+counter).val());
        if (!isNaN(fval)) 
          material_cost_amount+=fval;
          fval = parseFloat($("#_mat_total_"+counter).val());
        if (!isNaN(fval)) 
          material_amount+=fval;
        } 
        else 
          break;
        counter++;
    }   
    $('#_mat_sub_cost_total').val(roundNumber(material_cost_amount,2));  
    $('#_mat_sub_total').val(roundNumber(material_amount,2));  
    var labFields = Array("shop","labor","LBT","OT","sub_con");
    var i=0;
    for (i=0; i<labFields.length; i++) {
      fval = parseFloat($("#_labor_" + labFields[i] + "_total").val())
      if (!isNaN(fval)) 
        labor_amount+=fval; 
      fval = parseFloat($("#_labor_" + labFields[i] + "_cost_total").val())
      if (!isNaN(fval)) 
        labor_cost_amount+=fval; 
      fval = parseFloat($("#_labor_" + labFields[i] + "_hour").val()); 
      if (!isNaN(fval)) 
        labor_hour+=fval;     
    }
    
    $('#_labor_sub_hour_total').val(roundNumber(labor_hour,2));  
    $('#_labor_sub_total').val(roundNumber(labor_amount,2));  
    $('#_labor_sub_cost_total').val(roundNumber(labor_cost_amount,2));  
    counter = 1;  
    while (1) {
        if(typeof $("#_other_charge_total_"+counter).val() != 'undefined') { 
            fval = parseFloat($("#_other_charge_cost_total_"+counter).val());
          if (!isNaN(fval)) 
            other_charge_cost_amount+=fval;
            fval = parseFloat($("#_other_charge_total_"+counter).val());
          if (!isNaN(fval)) 
            other_charge_amount+=fval;
        } 
        else 
          break;
        counter++;
    }   
    $('#_other_charge_sub_cost_total').val(roundNumber(other_charge_cost_amount,2)); 
    $('#_other_charge_sub_total').val(roundNumber(other_charge_amount,2)); 
    total_amount = component_amount + material_amount + labor_amount + other_charge_amount;
    total_cost_amount = component_cost_amount + material_cost_amount + labor_cost_amount + other_charge_cost_amount;
    $('#_sub_total').val(roundNumber(total_amount,2));  
    $('#_sub_cost_total').val(roundNumber(total_cost_amount,2));  
    var hazmat_percent = parseFloat($('#_hazmat').val());
    if (!isNaN(hazmat_percent)) { 
       $('#_hazmat_total').val(roundNumber((hazmat_percent/100)*total_amount,2));
       $('#_hazmat_cost_total').val(roundNumber((hazmat_percent/100)*total_cost_amount,2));
    }
    else { 
      $('#_hazmat_total').val(0);
      $('#_hazmat_cost_total').val(0);
    }
    fval = parseFloat($("#_hazmat_total").val());
    if (!isNaN(fval)) 
      total_amount+=fval;
      fval = parseFloat($("#_hazmat_cost_total").val());
    if (!isNaN(fval))
      total_cost_amount+=fval;
    var tax_percent = parseFloat($('#_tax').val());
    if (!isNaN(tax_percent)) { 
      $('#_tax_total').val(roundNumber((tax_percent/100)*material_amount,2));
      $('#_tax_cost_total').val(roundNumber((tax_percent/100)*material_cost_amount,2));
    }
    else { 
      $('#_tax_total').val(0);
      $('#_tax_cost_total').val(0);
    }
    fval = parseFloat($("#_tax_total").val());
    if (!isNaN(fval)) 
      total_amount+=fval;
    fval = parseFloat($("#_tax_cost_total").val());
    if (!isNaN(fval)) 
      total_cost_amount+=fval;
    $('#_grand_total').val(roundNumber(total_amount,2));  
    $('#_grand_cost_total').val(roundNumber(total_cost_amount,2));  
  }
  function cal_sell_margin(itt, swit)
  {
    var margin = '';
    switch(swit)
    {
      case 1: // sell price entered
        sell_price = $('#_comp_sell_price_cost_' + itt).val();
        cost = $('#_comp_cost_' + itt).val();
        if(cost=="")
          cost = 0;
        if(sell_price=="")
          sell_price = 0;
        margin = sell_price - cost;
        if(sell_price != 0)
        {
          margin = (margin/sell_price)*100;
          margin = roundNumber(margin,2);
        }
        else
          margin = 0;
        $('#_comp_margin_' + itt).val(margin);
        cal_component_margin(itt);
      break;
      case 2: // margin entered
        margin = $('#_comp_margin_' + itt).val();
        margin = margin/100;
        if(margin==1)
          margin = 0;
        margin = 1 - margin;
        cost = $('#_comp_cost_' + itt).val();
        if(cost=="")
          cost = 0;
        sell_price = cost/margin;
        sell_price = roundNumber(sell_price,4);
        $('#_comp_sell_price_cost_' + itt).val(sell_price);
        cal_component_margin(itt);
      break;
    }
  }
  function cal_component_margin(itt){
    var comp_cost_val = $('#_comp_cost_'+itt).val();
    var comp_val = $('#_comp_list_'+itt).val();
    var comp_margin = $('#_comp_margin_'+itt).val();
    var dividend = 0;
    if(comp_margin/100==1)
     {
       dividend = .9999;
     }
     else 
     {
       dividend = comp_margin/100;
     }
    var listPrice = (comp_cost_val/(1-dividend));
    $('#_comp_list_'+itt).val(roundNumber(listPrice,2));
    cal_component(itt);
  }
  function cal_material(itt) {
  
   var mat_cost_val = parseFloat($('#_mat_quantity_'+itt).val() * $('#_mat_cost_'+itt).val());
   var mat_val = parseFloat($('#_mat_quantity_'+itt).val() * $('#_mat_list_'+itt).val()); 
   $('#_mat_cost_total_'+itt).val(!isNaN(mat_cost_val)?roundNumber(mat_cost_val,2):0);
   $('#_mat_total_'+itt).val(!isNaN(mat_val)?roundNumber(mat_val,2):0);
   cal_total_all();
}
function cal_mat_sell_margin(itt, swit)
{
  var margin = '';
  switch(swit)
  {
    case 1: // sell price entered
      sell_price = $('#_mat_sell_price_' + itt).val();
      cost = $('#_mat_cost_' + itt).val();
      if(cost=="")
        cost = 0;
      if(sell_price=="")
        sell_price = 0;
      margin = sell_price - cost;
      if(sell_price != 0)
      {
        margin = (margin/sell_price)*100;
        margin = roundNumber(margin,2);
      }
      else
        margin = 0;
      $('#_mat_margin_' + itt).val(margin);
      cal_material(itt);
    break;
    case 2: // margin entered
      margin = $('#_mat_margin_' + itt).val();
      margin = margin/100;
      if(margin==1)
        margin = 0;
      margin = 1 - margin;
      cost = $('#_mat_cost_' + itt).val();
      if(cost=="")
        cost = 0;
      sell_price = cost/margin;
      sell_price = roundNumber(sell_price,4);
      $('#_mat_sell_price_' + itt).val(sell_price);
      cal_material(itt);
    break;
  }
}
//typeof $("#_other_charge_total_"+counter).val() != 'undefined'
function cal_labor_total(type) {
   var t_amount = 0;
   var t_hour = 0;
   var counter = 1;
   var fval = '';
   
   while (1) {
     if(typeof $("#_labor_" + type + "_"+counter).val() != 'undefined') { 
          fval = parseFloat($("#_labor_" + type + "_"+counter).value);
          if (!isNaN(fval)) 
            t_hour+=fval;
     }
      else break;
      counter++;
  }
   counter = 1;  
  while (1) {
    if(typeof $("#_sub_labor_" + type + "_"+counter).val() != 'undefined') { 
      fval = parseFloat($("#_sub_labor_" + type + "_"+counter).value)
      if (!isNaN(fval)) 
        t_hour+=fval;
    }else 
      break;
    counter++;
  }   
  $('#_labor_' + type + '_hour').val(roundNumber(t_hour,2));  
  cal_labor_total_price(type);
}

function cal_labor_total_price(type) {
  var labor_rate = parseFloat($('#_labor_' + type + '_rate').val());
  var labor_cost_rate = parseFloat($('#_labor_' + type + '_cost_rate').val());
  var t_hour = parseFloat($('#_labor_' + type + '_hour').val());
  if (!isNaN(labor_rate) && !isNaN(t_hour)) 
    $('#_labor_' + type + '_total').val(roundNumber(labor_rate * t_hour,2));    
  else 
    $('#_labor_' + type + '_total').val(0); 
  if (!isNaN(labor_cost_rate) && !isNaN(t_hour)) 
    $('#_labor_' + type + '_cost_total').val(roundNumber(labor_cost_rate * t_hour,2));    
  else $('#_labor_' + type + '_cost_total').val(0); 
  cal_total_all();
}

function cal_other_charges(itt) {
  var other_charge_val = parseFloat($('#_other_charge_qty_'+itt).val() * $('#_other_charge_price_'+itt).val()); 
  var other_charge_cost_val = parseFloat($('#_other_charge_qty_'+itt).val() * $('#_other_charge_cost_price_'+itt).val()); 
  $('#_other_charge_total_'+itt).val(!isNaN(other_charge_val)?roundNumber(other_charge_val,2):0);
  $('#_other_charge_cost_total_'+itt).val(!isNaN(other_charge_cost_val)?roundNumber(other_charge_cost_val,2):0);  
  cal_total_all();
}

  </script>
  </body>
</html>
