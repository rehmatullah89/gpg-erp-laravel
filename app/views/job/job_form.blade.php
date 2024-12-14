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
          .ui-autocomplete {
            width: 300px;
            margin-top:-15000px;
            background-color:red;
          }  
        </style>
      </head>
      <?php header('Content-Type: application/pdf'); ?>
      <body class="full-width">

        <section id="container" class="">
          <!--header start-->
          <header class="header white-bg">
            <div class="navbar-header" style="display:inline;">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="fa fa-bars"></span>
              </button>

              <!--logo start-->
              {{ HTML::image(asset('img/gpglogo.jpg'), 'GPG Logo', array('style' => 'display:inline; width:80px; height:50px;')) }}
              <span><br/>12060 Woodside Ave,Lakeside, CA 92040</span>
              <!--logo end-->

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

          </header>
          <!--header end-->
          <!--sidebar start-->

          <!--sidebar end-->
          <!--main content start-->
          <br/><br/><br/><br/><br/>
          {{ Form::open(array('method' => 'POST','id'=>'update_elec_jobs','files'=>true,'route' => array('job/updateElectricJobs')))}} 
          <section id="main-content">
            <section id="wrapper">
             <section class="panel">
              <div class="panel-body">
                <!-- page start-->
                {{ Form::hidden('job_id',$j_id)}}
                <div class="row">
                  <div class="col-lg-12">
                    <section id="no-more-tables">
                      <table class="table table-bordered table-striped table-condensed cf">
                        <tbody class="cf">
                          <tr>
                           <td data-title="Job Number:">Job Number: {{ Form::text('job_num',$job_num, array('class' => 'form-control', 'id' => 'job_num','readOnly')) }}</td>
                           <td data-title="Schedule Date:">Schedule Date:{{ Form::text('scheduleDate',($jobTblRow['schedule_date']!='0000-00-00')?$jobTblRow['schedule_date']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'scheduleDate')) }}</td>
                           <td data-title="Time:">Time: <div class="input-group bootstrap-timepicker">{{ Form::text('schedule_time',$jobTblRow['schedule_time'], array('class' => 'form-control timepicker-default','id' => 'schedule_time')) }}  <span class="input-group-btn">{{Form::button('<i class="fa fa-clock-o"></i>', array('class' => 'btn btn-default'))}}</span></div></td>
                           <td data-title="Link to Job:">Link to Job:{{ Form::text('link_to_job_num',(isset($jobTblRow['link_job_num']) && $jobTblRow['link_job_num'] != 'NULL')?$jobTblRow['link_job_num']:'', array('class' => 'form-control','id' => 'link_to_job_num')) }}</td>
                           <td data-title="Apply Repair Asset:">Apply Repair to Asset:{{Form::select('gpg_asset_equipment_id', $asset_equip, $jobTblRow['gpg_asset_equipment_id'], ['class'=>'form-control','id'=>'gpg_asset_equipment_id'])}}</td>
                           <td data-title="Job Completed">Job/(Date) Completed:&nbsp;{{ Form::checkbox('jobCompleted','1',$jobTblRow['complete'], array('id'=>'jobCompleted','class' => 'input-group','style'=>'display:inline;')) }}{{ Form::text('dateCompletion',(isset($jobTblRow['date_completion']) && $jobTblRow['date_completion'] != '0000-00-00')?$jobTblRow['date_completion']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'dateCompletion')) }}</td></tr>

                           <tr><td data-title="Job Closed:">Job Closed:{{ Form::checkbox('jobClosed','1',$jobTblRow['closed'], array('id'=>'jobClosed','class' => 'input-group')) }}</td>
                             <td data-title="Closing Date:">Closing Date:{{ Form::text('closingDate',($jobTblRow['closing_date']!='0000-00-00')?$jobTblRow['closing_date']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'closingDate','readOnly')) }}</td>
                             <td data-title="National Accoutn Company:">National Account Company:{{Form::select('nationalAccount', $nat_acc, $jobTblRow['national_account'], ['class'=>'form-control','id'=>'nationalAccount'])}}</td>
                             <td data-title="SubContractor:">Subcontractor:{{Form::select('subContractor', $sub_contact, $jobTblRow['sub_contractor'], ['class'=>'form-control','id'=>'subContractor','style'=>'width:220px;'])}}</td>
                             <td data-title="Property Mange:">Property Management:{{Form::select('propertyManagement', $nat_acc, $jobTblRow['property_management'], ['class'=>'form-control','id'=>'propertyManagement','onchange'=>'get_property_data("Location",this.value);'])}}</td>
                             <td data-title="Job Type:">Job Type:{{Form::select('elecJobType', $elec_job, $jobTblRow['elec_job_type'], ['class'=>'form-control','id'=>'elecJobType'])}}</td></tr>
                           </tbody>
                         </table>
                       </section>
                     </div>  
                   </div>
                 </div>
               </section>  
               <br/>
               <div class="panel"> 
                {{Form::button('Show/Hide Billing Info', array('style'=>'color:red;','class' => 'btn btn-link', 'id'=>'toggle_bill_info'))}}
              </div>
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
                               <tr><td style="background-color:#FFFFCC;">Bill To:</td><td>{{Form::select('customerBillto', $bill_custs, $jobTblRow['GPG_customer_id'], ['class'=>'form-control','id'=>'customerBillto'])}}</td><td style="background-color:#FFFFCC;">Technicians:	{{Form::button('[Clear]', array('class' => 'btn btn-link', 'id'=>'reset_fields'))}}</td><td>{{Form::select('jobTechnicians[]', $sal_emps_arr, explode(',',$jobTblRow['technicians']), ['multiple','class'=>'form-control','id'=>'jobTechnicians'])}}
                               </td></tr>
                               <tr><td style="background-color:#FFFFCC;">Address 1:</td><td>{{ Form::text('cusAddress',$jobCTblRow['address'], array('class' => 'form-control', 'id' => 'cusAddress1')) }}</td><td style="background-color:#FFFFCC;">Address 2:</td><td>{{ Form::text('cusAddress2',$jobCTblRow['address2'], array('class' => 'form-control', 'id' => 'cusAddress2')) }}</td></tr>
                               <tr><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('cusState',$jobCTblRow['state'], array('class' => 'form-control', 'id' => 'cusState')) }}</td><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('cusZip',$jobCTblRow['zipcode'], array('class' => 'form-control', 'id' => 'cusZip')) }}</td></tr>
                               <tr><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('cusPhone',$jobCTblRow['phone_no'], array('class' => 'form-control', 'id' => 'cusPhone')) }}</td><td style="background-color:#FFFFCC;">Attn:</td><td>{{ Form::text('cusAtt',$jobTblRow['attach_job_num'], array('class' => 'form-control', 'id' => 'cusAtt')) }}</td></tr>
                               <tr><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('cusCity',$jobCTblRow['city'], array('class' => 'form-control', 'id' => 'cusCity')) }}</td><td style="background-color:#FFFFCC;">Fixed Price:</td><td>{{ Form::text('fixedPrice',$jobTblRow['fixed_price'], array('class' => 'form-control', 'id' => 'fixedPrice')) }}</td></tr>
                               <tr><td style="background-color:#FFFFCC;">NTE:</td><td>{{ Form::text('NTE',$jobTblRow['nte'], array('class' => 'form-control', 'id' => 'NTE')) }}</td><td style="background-color:#FFFFCC;">Subcontract NTE:</td><td>{{ Form::text('subNTE',$jobTblRow['sub_nte'], array('class' => 'form-control', 'id' => 'subNTE')) }}</td></tr>
                               <tr><td style="background-color:#FFFFCC;">T & M:</td><td>{{ Form::text('TM',$jobTblRow['time_material'], array('class' => 'form-control', 'id' => 'TM')) }}</td><td style="background-color:#FFFFCC;">Cust PO:</td><td>{{ Form::text('cusPO',$jobTblRow['cus_purchase_order'], array('class' => 'form-control', 'id' => 'cusPO')) }}</td></tr>
                               <tr><td style="background-color:#FFFFCC;">Contact:</td><td>{{ Form::text('contact',$jobTblRow['bill_contact'], array('class' => 'form-control', 'id' => 'contact')) }}</td><td style="background-color:#FFFFCC;">Amt. Note:</td><td>{{ Form::text('amountnote',$jobTblRow['amount_note'], array('class' => 'form-control', 'id' => 'amountnote')) }}</td></tr>
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
                          Job Site Address [{{Form::button('FILL CUSTOMER FIELDS', array('onClick'=>'autoFill();','class' => 'btn btn-link btn-xs'))}}]
                        </a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                      <div class="panel-body">
                        <section id="no-more-tables">
                         <table class="table table-bordered table-striped table-condensed cf">
                           <tbody class="cf">
                             <tr><td style="background-color:#FFFFCC;">JobSite:</td><td>{{ Form::text('jobSite',$jobTblRow['location'], array('class' => 'form-control', 'id' => 'jobSite')) }}</td><td style="background-color:#FFFFCC;">LocationID/Property Location:</td><td id="chnge_to_list">{{ Form::text('locationID',$jobTblRow['location_id'], array('class' => 'form-control', 'id' => 'locationID')) }}</td></tr>
                             <tr><td style="background-color:#FFFFCC;">JobSite Contact:</td><td>{{ Form::text('jobSiteContact',$jobTblRow['job_site_contact'], array('class' => 'form-control', 'id' => 'jobSiteContact')) }}</td><td style="background-color:#FFFFCC;">Property Area:</td><td id="parea_fields">{{ Form::text('aaaaaa','', array('class' => 'form-control', 'readOnly')) }}</td></tr>
                             <tr><td style="background-color:#FFFFCC;">Address 1:</td><td>{{ Form::text('jobAddress1',$jobTblRow['address1'], array('class' => 'form-control', 'id' => 'jobAddress1')) }}</td><td style="background-color:#FFFFCC;">Property Asset:</td><td id="passet_fields">{{ Form::text('aaaaaa','', array('class' => 'form-control', 'readOnly')) }}</td></tr>
                             <tr><td style="background-color:#FFFFCC;">Address 2:</td><td>{{ Form::text('jobAddress2',$jobTblRow['address2'], array('class' => 'form-control', 'id' => 'jobAddress2')) }}</td><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('jobCity',$jobTblRow['city'], array('class' => 'form-control', 'id' => 'jobCity')) }}</td></tr>
                             <tr><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('jobState',$jobTblRow['state'], array('class' => 'form-control', 'id' => 'jobState')) }}</td><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('jobZip',$jobTblRow['zip'], array('class' => 'form-control', 'id' => 'jobZip')) }}</td></tr>
                             <tr><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('jobPhone',$jobTblRow['phone'], array('class' => 'form-control', 'id' => 'jobPhone')) }}</td><td style="background-color:#FFFFCC;">Sales Person:</td><td>{{Form::select('salePersonId', $sal_emps_arr, $jobTblRow['GPG_employee_id'], ['class'=>'form-control','id'=>'salePersonId'])}}</td></tr>
                             <tr><td style="background-color:#FFFFCC;">Estimator:</td><td>{{Form::select('estimator', $sal_emps_arr, $jobTblRow['estimator'], ['class'=>'form-control','id'=>'estimator'])}}</td><td style="background-color:#FFFFCC;">Job Manager:</td><td>{{Form::select('job_manager', $sal_emps_arr, $jobTblRow['job_manager'], ['class'=>'form-control','id'=>'job_manager'])}}</td></tr>
                             <tr><td style="background-color:#FFFFCC;">COD:</td><td>{{ Form::text('COD',$jobTblRow['cod'], array('class' => 'form-control', 'id' => 'COD')) }}</td><td style="background-color:#FFFFCC;">Cust WO:</td><td>{{ Form::text('cusWO',$jobTblRow['cus_work_order'], array('class' => 'form-control', 'id' => 'cusWO')) }}</td></tr>
                             <tr><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('otherPhone',$jobTblRow['bill_phone'], array('class' => 'form-control', 'id' => 'otherPhone')) }}</td><td style="background-color:#FFFFCC;">Contract Amount:</td><td>{{ Form::text('contractAmount',round($jobTblRow['contract_amount'],2), array('class' => 'form-control', 'id' => 'contractAmount')) }}</td></tr>
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
         @if(isset($assigned_eqpsArr) && !empty($assigned_eqpsArr))
         <section class="panel">  
          <section id="no-more-tables">
            <table class="table table-bordered table-striped table-condensed cf">
              <thead  class="cf">
                <tr><th colspan='9' style="background-color:#FFFFCC;">  Job Equipment Info: {{HTML::link('#myModal', 'Assign another Equipment' , array('class' => 'btn btn-link','data-toggle'=>'modal','id'=>'assign_equip_for_job'))}}</th></tr>
                <tr><th>Action</th><th>Equipment Desc.</th><th>Tech Person</th><th>Current Status</th><th>Checkout Date</th><th>Checkout Comments</th><th>Checkin Date</th><th>Checkin Comments</th><th>Health Status</th></tr>
              </thead>
              <tbody class="cf">
                @foreach($assigned_eqpsArr as $data)
                <tr><td data-title="Action">{{ Form::button('Del', array('style'=>'display:inline;','id'=>'deleteEquipHist','name'=>$data['id'],'class'=>'btn btn-danger btn-xs'))}}</td>
                  <td data-title="Equip:">{{@$data['name']}}</td><td data-title="Emp Name:">{{@$data['emp_name']}}</td><td data-title="Equip Status:">{{@$data['status']}}</td><td data-title="ChckOut date:">{{@$data['codate']}}</td><td data-title="CheckOut Comment:">{{@$data['cocomment']}}</td><td data-title="CheckIn date:">{{@$data['cidate']}}</td><td data-title="Check In Comment:">{{@$data['cicomment']}}</td><td data-title="Health Status:">{{@$data['hstatus']}}</td></tr>
                  @endforeach
                </tbody>
              </table>
            </section>
          </section>
          @else
          <div class="panel">            
            [{{HTML::link('#myModal', 'Assign Equipment for this Job' , array('class' => 'btn btn-link','data-toggle'=>'modal','id'=>'assign_equip_for_job'))}}]
          </div>
          @endif
          <section class="panel">  
           <div class="panel-heading"><b> Invoice Info </b></div>
           <section id="no-more-tables">
            <table class="table table-bordered table-striped table-condensed cf" id="mytable">
              <thead  class="cf">
               <tr><th>Invoice#</th><th>Attached Document</th><th>Invoice Date</th><th>Invoice Amount</th><th>Sales Tax Amount</th><th>Net Invoice Amount</th></tr>
             </thead>
             <tbody class="cf">
               <?php $invoiceAmountDbTotal=0; $invoiceTaxAmountDbTotal=0; $invAmtTotal=0; $ii=1;?>
               @foreach($job_inv_info_arr as $data)
               <tr><td data-title="Invoice Number:">{{ Form::text('invNumber_'.$ii,$data['invoice_number'], array('class' => 'form-control', 'id' => 'invNumber_'.$ii)) }}</td><td data-title="Attachment:">
                @if(empty($data['attachment']))  
                {{ Form::file('invFile_'.$ii,$data['attachment'], array('id' => 'invFile_'.$ii)) }}
                @else
                {{HTML::link(public_path().'/img/'.$data['attachment'], $data['attachment'] , array('class' => 'btn btn-link', 'id'=>'invFile_'.$ii))}}
                @endif
              </td><td data-title="Invoice Date:">{{ Form::text('invDate_'.$ii,$data['invoice_date'], array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'invDate_'.$ii)) }}</td><td data-title="Invoice Amt:">{{ Form::text('invAmount_'.$ii,number_format($data['invoice_amount'],2), array('class' => 'form-control','onchange'=>'calInvAmount(this.id,this.value)', 'id' => 'invAmount_'.$ii)) }}</td><td data-title="Invoice Tax:">{{ Form::text('invTaxAmount_'.$ii,$data['tax_amount'], array('class' => 'form-control','onchange'=>'calInvAmount(this.id,this.value)', 'id' => 'invTaxAmount_'.$ii)) }}</td><td data-title="Net Total:" id='invoice_net_total_<?php echo $ii; ?>'>
              <?php

              $invoiceAmountDbTotal += $data['invoice_amount'] ;
              $invoiceTaxAmountDbTotal += $data['tax_amount'] ;
              $invAmt = $data['invoice_amount'] - $data['tax_amount'];
              echo '$'.number_format($invAmt,2);
              $invAmtTotal += $invAmt;
              ++$ii;
              ?>
            </td></tr>
            {{ Form::hidden('invoiceCounter',$ii, array('id' => 'invoiceCounter')) }} 
            @endforeach
            <tr><td data-title="Add New:" colspan="2"><i class='fa fa-plus-square' id='create_another_row'></i></td>
             <td data-title="Grand Total:">Grand Total $</td><td data-title="Invoice Amt:" id="individual_total"> ${{number_format($invoiceAmountDbTotal,2)}}</td><td data-title="Sales Tax Amt:" id="individual_tax">${{number_format($invoiceTaxAmountDbTotal,2)}}</td><td data-title="Net Invoice Amt:" id="grand_total_all">${{number_format($invAmtTotal,2)}}</td></tr>
           </tbody>
         </table>
       </section>
     </section>  
     <br/>
     <section class="panel">  
       <div class="panel-heading">
        <b>Notes & Attachments</b>
      </div> 
      <section id="no-more-tables">
        <table class="table table-bordered table-striped table-condensed cf">
          <thead  class="cf">
           <tr><th>Scope Of Work</th><th> Actual Work Completed</th><th> Recommendation</th><th>Project Notes<br/>{{ Form::text('billingNoteDate',date('Y-m-d'), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'billingNoteDate')) }}</th><th> Emails:<br/>{{HTML::link('#myModal2', 'Attach Email' , array('class' => 'btn btn-link','data-toggle'=>'modal', 'id'=>'attach_email'))}}</th><th>RFI's: [<i class='fa fa-plus-square' id='toggle_rfis'></i>]<br/>
             {{HTML::link('#myModal3', 'Attach RFI' , array('class' => 'btn btn-link','data-toggle'=>'modal', 'id'=>'attach_rfi'))}}
           </thead>
           <tbody class="cf">
             <tr>
              <td data-title="Scop of Work:">{{ Form::textarea('jobDescription', $jobTblRow['task'], ['class' => 'form-control','cols' => '20', 'rows'=>'5','id'=>'jobDescription']) }}</td>
              <td data-title="Actual Work Comp:">{{ Form::textarea('workCompleted', $jobTblRow['work_completed'], ['class' => 'form-control','cols' => '20', 'rows'=>'5','id'=>'workCompleted']) }}</td>
              <td data-title="Recomendation:">{{ Form::textarea('recommendation',$jobTblRow['recommendation'], ['class' => 'form-control','cols' => '20', 'rows'=>'5','id'=>'recommendation']) }}</td>
              <td data-title="Project Note:">{{ Form::textarea('billingNote', strip_tags($notes_arr,'<br/>'), ['class' => 'form-control','cols' => '20', 'rows'=>'5','id'=>'billingNote']) }}</td>
              <td data-title="Email Attach:">No Emails Attached</td>
              <td data-title="RFI Attach:">
                <section id="no-more-tables">
                  <table class="table table-bordered table-striped table-condensed cf" id="rfi_table">
                    <thead  class="cf"><tr><th>RFI Details</th><th>Actions</th></tr></thead>
                    <tbody class="cf">
                      @if(empty($rfi_arr))
                      {{'No Information Requested For This Job.'}}
                      @else	    
                      @foreach($rfi_arr as $row)
                      <tr>
                       <td style="padding-left:10%;"><b>Title:</b>{{$row['title']}}<br/><b>Message:</b>{{$row['rfi_message']}}<br/><b>By:</b>{{Auth::user()->fname}}</td>
                       <td>
                         @if($row['status'] == 0)
                         {{ Form::open(array('method' => 'POST','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('job/completeRFI', $row['id'],$jobTblRow['id'],$job_num)))}}
                         {{ Form::submit('Comp', array('style'=>'display:inline;','class' => 'btn btn-primary btn-xs','onclick'=>'confirm("Are you sure you want to Complete this RFI?")'))}}
                         {{ Form::close() }}
                         @endif
                         {{ Form::open(array('method' => 'POST','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('job/destroyRFI', $row['id'],($row['gpg_rfi_id'] == ''?0:$row['gpg_rfi_id']),$jobTblRow['id'],$job_num)))}}
                         {{ Form::submit('Del', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'confirm("Are you sure you want to delete this...")'))}}
                         {{ Form::close() }}
                       </td></tr>
                       @endforeach
                       @endif
                     </tbody>
                   </table>
                 </section>
               </td>
             </tr>
           </tbody>
         </table>
       </section>
     </section>  
     <b>Create / View Job Task(s)</b>
     @if(empty($job_proj_arr))
     <section class="panel"> 
      <section id="no-more-tables">
        <table class="table table-bordered table-striped table-condensed cf">
          <tbody  class="cf">
           <tr><th style="background-color:#FFFFCC">Creat New Project:</th><td>
            {{HTML::link('#myModal5', 'Create New Project' , array('class' => 'btn btn-primary','data-toggle'=>'modal'))}}
          </td>
          <th style="background-color:#FFFFCC">  Excel Load only (.xlsx):</th><td>
          <div style="display: inline;">
           {{HTML::link('#myModal8', 'Upload Job Tasks Via Excel File' , array('class' => 'btn btn-primary','data-toggle'=>'modal'))}}
         </div></td></tr>
       </tbody>
     </table>
   </section>
  </section>
  @else
  <section class="panel"> 
   <section id="no-more-tables">
    <table class="table table-bordered table-striped table-condensed cf">
      <tbody  class="cf">
       <tr><th style="background-color:#FFFFCC">Project: {{$jobTblRow['project_title']}} &nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-plus-square' id='toggle_existing_project'></i></th><td>
         {{HTML::link('#myModal6', 'Create New Task' , array('class' => 'btn btn-primary','data-toggle'=>'modal'))}}
       </td>
       <th style="background-color:#FFFFCC"> Update {{$jobTblRow['project_title']}} Project: &nbsp;Excel Load only (.xlsx)</th><td>
       <div style="display: inline;">
        {{HTML::link('#myModal8', 'Update Job Tasks Via Excel File' , array('class' => 'btn btn-primary','data-toggle'=>'modal'))}}
      </div></td></tr>
    </tbody>
  </table>
  </section>
  </section>
  <section class="panel"> 
    <section id="no-more-tables">
      <table class="table table-bordered table-striped table-condensed cf" id="project_task_toggle_table">
        <thead  class="cf">
          <tr><th>Actions</th><th>Completed</th><th>Completed Date</th><th>ID</th><th>Task Title</th><th>Technician</th><th>Days</th><th>Start</th><th>End</th><th>Parent Task</th><th>Subcontractor</th><th>Task Detail</th><th>Include Days (Sat/Sun)</th><th>Resource Forcasted</th><th>Resource Used</th><th>Order</th></tr>	
        </thead>
        <tbody  class="cf">
         @foreach ($job_proj_arr as $projectRow)
         <tr>
           <td>
            {{ Form::button('Del', array('name'=>'del_task_by_id','task_id'=>$projectRow['job_task_id'],'style'=>'display:inline;','class' => 'btn btn-danger btn-xs'))}}
            {{HTML::link('#myModal7', 'Update' , array('class' => 'btn btn-primary btn-xs','data-toggle'=>'modal','task_id_for_job'=>$projectRow['job_task_id'],'name'=>'task_id_for_update_job_modal'))}}
          </td>
          <td>{{($projectRow['completed']==1?'Yes':'No')}}</td>
          <td>{{(!empty($projectRow['completed_date'])?date('m/d/Y',strtotime($projectRow['completed_date'])):'-')}}</td>
          <td>{{HTML::link('#myModal9', $projectRow['pcode_1'].'.'.$projectRow['pcode_2'].'.'.$projectRow['pcode_3'] , array('class' => 'btn btn-link btn-xs','data-toggle'=>'modal','id_for_job_task'=>$projectRow['id'],'name'=>'show_tasks_of_project'))}}</td>
          <td>{{$projectRow['project_activity_id']."-".($projectRow['title'])}}</td>
          <td>{{$projectRow['owner_name']}}</td>
          <td><?php if(!empty($projectRow['days'])) echo $projectRow['days']; ?></td>
          <td>{{(!empty($projectRow['start_date'])?date('m/d/Y',strtotime($projectRow['start_date'])):'')}}</td>
          <td>{{(!empty($projectRow['end_date'])?date('d/m/Y',strtotime($projectRow['end_date'])):'')}}</td>
          <td>{{$projectRow['parent_task']}}</td>
          <td> {{$projectRow['subcontractor']}}</td>
          <td>{{substr($projectRow['notes'],0,15)}}</td>
          <td>{{($projectRow['include_days']==1?'Yes':'No')}}</td>
          <td>{{number_format($projectRow['resource_hours'],2)}}</td>
          <td>{{number_format($projectRow['labour_hours'],2)}}</td>
          <td>{{ Form::text('order_1',$projectRow['order_no'], array('class'=>'form-control','readOnly')) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </section>
  </section>
  @endif
  <br/>
  <div class="row">
    <div class="col-lg-6">
      <div class="panel-group m-bot20" id="accordion">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                Daily Log  <i class='fa fa-plus-square' id='toggle_daily_logs'></i> 
              </a>
            </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
              <section id="no-more-tables">
               <table class="table table-bordered table-striped table-condensed cf" id="daily_log_table">
                 <thead><tr><th>Date</th><th>Technician</th><th>Daily Log</th></tr></thead>
                 <tbody class="cf">
                   @foreach($job_logs_arr as $row)
                   <tr><td data-title="Date:">{{date('m/d/Y',strtotime($row['timesheet_date']))}}</td><td data-title="Technecian:">{{$row['name']}}</td><td data-title="Daily Log:">{{$row['workdone']}}</td></tr>
                   @endforeach
                 </tbody>
               </table>
             </section>      
           </div>
         </div>
       </div>    
     </div>
   </div>
   <div class="col-lg-6">
    <div class="panel-group m-bot20" id="accordion">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
             Job Files <i class='fa fa-plus-square' id='toggle_job_files'></i> 
           </a>
         </h4>
       </div>
       <div id="collapseOne" class="panel-collapse collapse in">
        <div class="panel-body">
          <section id="no-more-tables">
           <table class="table table-bordered table-striped table-condensed cf" id="job_files_table">
             <thead><tr><th>Uplaoded Date</th><th>Uploaded By</th><th>File</th></tr></thead>
             <tbody class="cf">
               @foreach($job_files_arr as $row)
               <tr><td>{{date('m/d/Y',strtotime($row['created_on']))}}</td><td>{{$row['GPG_timesheet_detail_id']}}</td><td>{{$row['displayname']}}</td></tr>
               @endforeach
             </tbody>
           </table>
         </section>      
       </div>
     </div>
   </div>    
  </div>
  </div>
  </div> 
  <!-- ****************************************** -->
  <div class="row">
    <div class="col-lg-6">
      <div class="panel-group m-bot20" id="accordion">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
               Project Milestones  <i class='fa fa-plus-square' id='toggle_project_milst'></i> 
             </a>
           </h4>
         </div>
         <div id="collapseOne" class="panel-collapse collapse in">
          <div class="panel-body">
            <section id="no-more-tables">
             <table class="table table-bordered table-striped table-condensed cf" id="proj_milst_table">
               <thead><tr><th>Date Job Won{{ Form::text('dateJobWon',(isset($jobTblRow['date_job_won']) && $jobTblRow['date_job_won']!='0000-00-00')?$jobTblRow['date_job_won']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker')) }}</th><th>Date Order Placed{{ Form::text('dateEqpOrdered',(isset($jobTblRow['date_eqp_ordered']) && $jobTblRow['date_eqp_ordered'] != '0000-00-00')?$jobTblRow['date_eqp_ordered']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker')) }}</th><th>Date Order Confirmed{{ Form::text('dateEqpEngaged',(isset($jobTblRow['date_eqp_engaged']) && $jobTblRow['date_eqp_engaged']!='0000-00-00')?$jobTblRow['date_eqp_engaged']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker')) }}</th><th>Date Order Confirmed Margin{{ Form::text('orderConfirmedMargin','', array('class' => 'form-control','readonly')) }}</th><th>Date Permit Ordered{{ Form::text('datePermitOrdered',(isset($jobTblRow['date_permit_ordered']) && $jobTblRow['date_permit_ordered'] != '0000-00-00')?$jobTblRow['date_permit_ordered']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker')) }}</th><th>Date Permit Expected{{ Form::text('datePermitExpected',(isset($jobTblRow['date_permit_expected']) && $jobTblRow['date_permit_expected'] != '0000-00-00')?$jobTblRow['date_permit_expected']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker')) }}</th><th>Expected Date of Completion{{ Form::text('dateCompletionExpected',(isset($jobTblRow['date_completion_expected']) && $jobTblRow['date_completion_expected'] != '0000-00-00')?$jobTblRow['date_completion_expected']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker')) }}</th></tr></thead>
               <tbody class="cf">
                <tr><td>Project Type:</td><td>{{Form::select('jobProjectType[]',$jobProjectType, null, ['multiple','class'=>'form-control','id'=>'jobProjectType'])}}</td><td>Project Doc Upload:</td><td>{{HTML::link('#myModal4', 'Manage Files' , array('class' => 'btn btn-link','data-toggle'=>'modal'))}}</td><td>Download Attachments:</td><td colspan="2">
                 @if(!empty($JP_Att_arr))
                 @foreach($JP_Att_arr as $key=>$data)
                 {{HTML::link('job/getDownloadJobFile/'.$data['id'].'/Electrical', $data['displayname'] , array('class' => 'btn btn-link'))}}
                 <br/>
                 @endforeach
                 @endif
               </td></tr>
             </tbody>
           </table>
         </section>      
       </div>
     </div>
   </div>    
  </div>
  </div>
  <div class="col-lg-6">
    <div class="panel-group m-bot20" id="accordion">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
              Tasks <i class='fa fa-plus-square' id='toggle_tasks'></i> 
            </a>
          </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
          <div class="panel-body">
            <section id="no-more-tables">
             <table class="table table-bordered table-striped table-condensed cf" id="tasks_tg_table">
               <thead><tr><th>Description</th><th>Date Started</th><th>Date Completed</th><th>Completion Notes</th></tr></thead>
               <tbody class="cf">
                 @foreach($taskDataRes_tbl as $taskDataRes)
                 <tr><td>{{$taskRes['task_detail']}}</td><td>{{date('m/d/Y',strtotime($taskRes['start_date']))}}</td><td><?php if(empty($taskRes['completion_date'])) echo "-"; else echo date('m/d/Y',strtotime($taskRes['completion_date']));?></td><td><?php if(empty($taskRes['completion_note'])) echo "-"; else echo $taskRes['completion_note'];?></td></tr>
                 @endforeach
               </tbody>
             </table>
           </section>      
         </div>
       </div>
     </div>    
   </div>
  </div>
  </div> 
  <br/>
  <!-- ========================= -->
  <div class="col-lg-6">
    <div class="panel">
      <header class="panel-heading">
        Labor Summary table
      </header>
      <section id="no-more-tables">
        <table class="table table-striped table-advance table-hover" id="tasks_tg_table">
          <thead class="cf"><tr><th>Techenician</th><th>Hours in (Decimal)</th><th>Total $</th></tr></thead>
          <tbody class="cf">
            @foreach($labor_arr as $data)
            <tr><td data-title="Tech:">{{$data['label']}}</td><td data-title="Hours:">{{$data['value']}}</td><td data-title="Amount:">{{$data['amount']}}</td></tr>
            @endforeach    
          </tbody>
        </table>
      </section>                          
      {{ HTML::link('job/job_form_report/'.$jobTblRow['id'].'/'.$job_num.'/labor/date', 'View Detail Report Table' , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$jobTblRow['id'],'j_num'=>$job_num))}} 
    </div>
  </div>
  <div class=" col-lg-6">
    <div class="panel">
      <header class="panel-heading">
        Job Cost(s) Summary table
      </header>
      <section id="no-more-tables">
        <table class="table table-striped table-advance table-hover" id="tasks_tg_table">
          <thead class="cf"><tr><th>Type</th><th>Date</th><th>Amount</th></tr></thead>
          <tbody class="cf">
            @foreach($jobCost_Arr as $data)
            <tr><td data-title="Type:">{{$data['label']}}</td><td data-title="Date:">{{$data['value']}}</td><td data-title="Amount:">{{$data['amount']}}</td></tr>
            @endforeach    
          </tbody>
        </table>
      </section>     
      {{ HTML::link('job/job_form_report/'.$jobTblRow['id'].'/'.$job_num.'/jobcost/date', 'View Detail Report Table' , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$jobTblRow['id'],'j_num'=>$job_num))}}

    </div>
  </div>
  <!-- ========================= -->
  <div class=" col-lg-6">
    <div class="panel">
      <header class="panel-heading">

       Purchase Order(s)

     </header>
     <section id="no-more-tables">
      <table class="table table-striped table-advance table-hover" id="tasks_tg_table">
        <thead class="cf"><tr><th>Po#</th><th>Date</th><th>Amount</th></tr></thead>
        <tbody class="cf">
          @foreach($poJobCostArr as $data)
          <tr><td data-title="PO#">{{$data['label']}}</td><td data-title="Date:">{{$data['value']}}</td><td data-title="Amount:">{{$data['amount']}}</td></tr>
          @endforeach    
        </tbody>
      </table>
    </section>     
    {{ HTML::link('job/job_form_report/'.$jobTblRow['id'].'/'.$job_num.'/jobpo/id', 'View Detail Report Table' , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$jobTblRow['id'],'j_num'=>$job_num))}}   
  </div>
  </div>

  <div class=" col-lg-6">
    <div class="panel">
      <header class="panel-heading">
        Purchase Order Detail(s)
      </header>
      <section id="no-more-tables">
        <table class="table table-striped table-advance table-hover" id="tasks_tg_table">
          <thead class="cf"><tr><th>Po#</th><th>Item#</th><th>Amount</th></tr></thead>
          <tbody class="cf">
            @foreach($poDataQryArr as $data)
            <tr><td data-title="PO#:">{{$data['label']}}</td><td data-title="Item#:">{{$data['value']}}</td><td data-title="Amount:">{{$data['amount']}}</td></tr>
            @endforeach    
          </tbody>
        </table>
      </section>     
      {{ HTML::link('job/job_form_report/'.$jobTblRow['id'].'/'.$job_num.'/jobpo_detail/id', 'View Detail Report Table' , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$jobTblRow['id'],'j_num'=>$job_num))}}
    </div> 
  </div>    
  <div class="panel">
    {{ HTML::link('job/getPdffile/'.$jobTblRow['id'].'', 'Show PDF Invoice Form' , array('class'=>'btn btn-danger','style'=>'float:right;'))}}     
    {{Form::button('Update Forms Info', array('id'=>'update_forms_info','style'=>'float:right;','class' => 'btn btn-success'))}}
    {{Form::close()}}  
  </div>
  <!-- page end-->
  </div>
  </section>
  </section>
  </section>
  </section>
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
          <h4 class="modal-title">Assign Equipment(s):</h4>
        </div>
        {{ Form::open(array('before' => 'csrf' ,'id'=>'equip_request_form','url'=>route('job/equipCheckOut'),'files'=>true, 'method' => 'post')) }} {{Form::hidden('job_id',$jobTblRow['id'])}} {{Form::hidden('job_num',$job_num)}}
        <div class="modal-body">
         <div class="form-group">
          <section id="no-more-tables"  style="padding:10px;">
           <table class="table table-bordered table-striped table-condensed cf">
            <tbody class="cf">
              <tr><th>Select Equipment:</th><td>{{Form::select('assetEqpId', $eqp_arr, null, ['class'=>'form-control','id'=>'assetEqpId'])}}</td></tr>
              <tr><th>Select Technician</th><td>{{Form::select('assetEqpTech', $sal_emps_arr, null, ['class'=>'form-control','id'=>'assetEqpTech'])}} </td></tr>
              <tr><th>Checkout Date</th><td>{{ Form::text('assetEqpCheckoutDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'assetEqpCheckoutDate')) }}</td></tr>
              <tr><th>Equipment Condition:</th><td>{{ Form::textarea('assetEqpCond', null, ['class' => 'form-control','cols' => '20', 'rows'=>'5','id'=>'assetEqpCond']) }}</td></tr>
            </tbody>
          </table>
        </section> 
      </div>
      <div class="btn-group" style="padding:20px;">
       {{Form::submit('Check Out', array('class' => 'btn btn-success', 'id'=>'submit_asset_equip','data-dismiss'=>'modal'))}}
       {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
     </div>
   </div>
   {{Form::close()}}
  </div>
  </div>
  </div>
  <!-- modal end-->  
  <!-- Modal#2 -->
  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
          <h4 class="modal-title">Search Emails:</h4>
        </div>
        <div class="modal-body">
         <div class="form-group">
          <!-- ...code here.... -->
          <section id="no-more-tables"  style="padding:10px;">
           <table class="table table-bordered table-striped table-condensed cf">
            <tbody class="cf">
              <tr><th>Sender/Receiver:</th><td>{{Form::text('sender_receiver_name','', ['class'=>'form-control','id'=>'sender_receiver_name'])}}</td>
                <th>Subject:</th><td>{{Form::text('subject','', ['class'=>'form-control','id'=>'subject'])}}</td><td rowspan="2" >{{Form::submit('Search', array('class' => 'btn btn-primary', 'id'=>'search_email'))}}</td></tr>

                <tr><th>Start Date</th><td>{{ Form::text('start_date','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'start_date')) }}</td><th>End Date:</th><td>{{ Form::text('end_date', '', ['class' => 'form-control form-control-inline input-medium default-date-picker','id'=>'end_date']) }}</td></tr>
              </tbody>
            </table>
          </section> 
          <section id="no-more-tables"  style="padding:10px;">
           <table class="table table-bordered table-striped table-condensed cf">
             <thead class="cf">
              <tr><th>Sender/Reciver</th><th>Subject</th><th>Date</th></tr>
            </thead>
            <tbody class="cf">
             <div id="show_searched_email_data"></div>
           </tbody>
         </table>
       </section>
     </div>
     <div class="btn-group" style="padding:20px;">
       {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
     </div>
   </div>
  </div>
  </div>
  </div>
  <!-- modal#2 end-->          
  <!-- Modal#3 -->
  <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
          <h4 class="modal-title">Add New RFI(s):</h4>
        </div>
        {{ Form::open(array('before' => 'csrf' ,'id'=>'multiform','url'=>route('job/createRFI'),'files'=>true, 'method' => 'post')) }}
        <div class="modal-body">
         <div class="form-group">
          <section id="no-more-tables"  style="padding:10px;">
            {{ Form::hidden('creator',Auth::user()->ad_id)}}
            {{ Form::hidden('jobId',$jobTblRow['id'])}}
            <table class="table table-bordered table-striped table-condensed cf">
              <tbody class="cf">
                <tr><th>Creator:</th><td><?php echo Auth::user()->fname." ".Auth::user()->lname?></td></tr>
                <tr><th>Date:</th><td><?php echo date("F d, Y l"); ?></td></tr>
                <tr><th>Job Number:</th><td>{{ Form::text('JobNumber',$job_num, array('class' => 'form-control', 'id' => 'JobNumber','readOnly')) }}</td></tr>
                <tr><th>Title:</th><td>{{ Form::text('rfi_title','', array('class' => 'form-control', 'id' => 'rfi_title')) }}</td></tr>
                <tr><th>Requested To:</th><td>{{Form::select('RequestToId', $sal_emps_arr, '', ['class'=>'form-control','id'=>'RequestToId'])}}</td></tr>
                <tr><th>Comment:</th><td>{{ Form::textarea('rfi', null, ['class' => 'form-control','cols' => '20', 'rows'=>'5','id'=>'rfi']) }}</td></tr>
                <tr><th>Attach Image:</th><td>{{ Form::file('rfifileToUpload', ['class' => 'form-control','id'=>'rfifileToUpload']) }}</td></tr>
                <tr><th>Complete:</th><td>{{ Form::checkbox('rfiStatus', '',null, ['class' => 'form-control','id'=>'rfiStatus']) }}</td></tr>
              </tbody>
            </table>
          </section> 
        </div>
        <div class="btn-group" style="padding:20px;">
         {{Form::submit('Check Out', array('class' => 'btn btn-success', 'id'=>'create_new_rfi','data-dismiss'=>'modal'))}}
         {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
       </div>
     </div>
     {{ Form::close() }}
   </div>
  </div>
  </div>
  <!-- modal#3 end--> 
  <!-- Modal#4 -->
  <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
          <h4 class="modal-title">ATTACHMENT MANAGEMENT: [{{$job_num}}]</h4>
        </div>
        <div class="modal-body">
         {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('job/manageFiles'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('job_id',$jobTblRow['id'])}} {{Form::hidden('job_num',$job_num)}}                        
         <div class="form-group">
          <section id="no-more-tables"  style="padding:10px;">
           <table class="table table-bordered table-striped table-condensed cf">
            <thead class="cf">
             <tr><th>#</th><th>Category Name </th><th>Action</th></tr>
           </thead>
           <tbody class="cf">
             <?php $colcount=1; ?>
             @foreach($JP_Att_arr as $row)
             <tr>
               <td>{{$colcount++}}</td>
               <td>{{$row['displayname']}}</td>
               <!-- job/delAttach -->
               <td>
                {{ Form::button('<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-danger btn-xs','data-dismiss'=>'modal','name'=>'trash_job_file','id'=>$row['id'])) }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </section> 

      <div style="display: inline;">
        {{ Form::file('attachment', array('style'=>'float: left !important; display:inline !important; width:50%;' ,'id' => 'attachment')) }}
      </div> </div>
      {{Form::close()}}

      <div class="btn-group" style="padding:20px;">
       {{Form::submit('Submit', array('class' => 'btn btn-success', 'id'=>'submit_attachments'))}}
       {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
     </div>
   </div>

  </div>
  </div>
  </div>
  <!-- modal#4 end--> 
  <!-- Modal#5 -->
  <div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
          <h4 class="modal-title">Create/Update Project Task(s):</h4>
        </div>
        {{ Form::open(array('before' => 'csrf' ,'id'=>'jobProjFrm','url'=>route('job/createProjJob'),'files'=>true, 'method' => 'post')) }}          {{Form::hidden('jobId',$jobTblRow['id'])}} {{Form::hidden('jobNum',$job_num)}}
        <div class="modal-body">
         <div class="form-group">
          <!-- ...code here.... -->
          <section id="no-more-tables"  style="padding:10px;">
           <table class="table table-bordered table-striped table-condensed cf">
            <tbody class="cf">
              <tr><th>Project Title:</th><td>{{ Form::text('projectTitle','', array('class' => 'form-control', 'id' => 'projectTitle')) }}</td></tr>
              <tr><th>Task Title:</th><td>{{ Form::text('projectTaskTitle','', array('class' => 'form-control', 'id' => 'projectTaskTitle','required')) }}</td></tr>
              <tr><th>Start Date:</th><td>{{ Form::text('projectStartDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'projectStartDate','required')) }}</td></tr>
              <tr><th>Days Needed:</th><td>{{ Form::text('projectDays','', array('class' => 'form-control', 'id' => 'projectDays','required')) }}</td></tr>
              <tr><th>Resouce Forecast:</th><td>{{ Form::text('projectResourceForecast','', array('class' => 'form-control', 'id' => 'projectResourceForecast')) }}</td></tr>
              <tr><th>Subcontractor:</th><td>{{ Form::text('subcontractor','', array('class' => 'form-control', 'id' => 'subcontractor')) }}</td></tr>
              <tr><th>Activity ID:</th><td>{{ Form::text('project_activity_id','', array('class' => 'form-control', 'id' => 'project_activity_id')) }}</td></tr>
              <tr><th>Employee Type:</th><td>{{Form::select('selectEmpType', $empTypeArr, null, ['class'=>'form-control','id'=>'selectEmpType'])}}</td></tr>
              <tr><th>Technician:</th><td>{{Form::select('projectOwnerLeft[]', $sal_emps_arr, null, ['multiple','class'=>'form-control','id'=>'projectOwnerLeft'])}} </td></tr>
              <tr><th>Task Detail:</th><td>{{ Form::textarea('notes', null, ['class' => 'form-control','cols' => '20', 'rows'=>'3','id'=>'notes']) }}</td></tr>
              <tr><th>Parent Task: </th><td>{{Form::select('parentTask', $jobProj_SArr, null, ['class'=>'form-control','id'=>'parentTask'])}} </td></tr>
              <tr><th>Include Days(Sat/Sun):</th><td>{{ Form::checkbox('projectIncludeDays', '',null, ['class' => 'form-control','id'=>'projectIncludeDays']) }}</td></tr>
              <tr><th>Completed:</th><td>{{ Form::checkbox('projectCompleted', '',null, ['class' => 'form-control','id'=>'projectCompleted']) }}</td></tr>
            </tbody>
          </table>
        </section> 
      </div>
      <div class="btn-group" style="padding:20px;">
       {{Form::submit('Save', array('class' => 'btn btn-success', 'id'=>'create_update_proj_task','data-dismiss'=>'modal'))}}
       {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
     </div>
   </div>
   {{Form::close()}}
  </div>
  </div>
  </div>
  <!-- modal#5 end-->  
  <!-- Modal#6 -->
  <div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
          <h4 class="modal-title">Create/Update Project Task(s):</h4>
        </div>
        <div class="modal-body">
         {{ Form::open(array('before' => 'csrf' ,'id'=>'jobTaskProjFrm','url'=>route('job/createProjTaskJob'),'files'=>true, 'method' => 'post')) }} {{Form::hidden('jobId2',$jobTblRow['id'])}} {{Form::hidden('jobNum2',$job_num)}}                        
         <div class="form-group">
          <!-- ...code here.... -->
          <section id="no-more-tables"  style="padding:10px;">
           <table class="table table-bordered table-striped table-condensed cf">
            <tbody class="cf">
              <tr><th>Project Title:</th><td>{{ Form::text('projectTitle2',$jobTblRow['project_title'], array('class' => 'form-control', 'id' => 'projectTitle2','readOnly')) }}</td></tr>
              <tr><th>Task Title:</th><td>{{ Form::text('projectTaskTitle2','', array('class' => 'form-control', 'id' => 'projectTaskTitle2','required')) }}</td></tr>
              <tr><th>Start Date:</th><td>{{ Form::text('projectStartDate2','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'projectStartDate2')) }}</td></tr>
              <tr><th>Days Needed:</th><td>{{ Form::text('projectDays2','', array('class' => 'form-control', 'id' => 'projectDays2','required')) }}</td></tr>
              <tr><th>Resouce Forecast:</th><td>{{ Form::text('projectResourceForecast2','', array('class' => 'form-control', 'id' => 'projectResourceForecast2')) }}</td></tr>
              <tr><th>Subcontractor:</th><td>{{ Form::text('subcontractor2','', array('class' => 'form-control', 'id' => 'subcontractor2')) }}</td></tr>
              <tr><th>Activity ID:</th><td>{{ Form::text('project_activity_id2','', array('class' => 'form-control', 'id' => 'project_activity_id2')) }}</td></tr>
              <tr><th>Employee Type:</th><td>{{Form::select('selectEmpType2', $empTypeArr, null, ['class'=>'form-control','id'=>'selectEmpType2'])}}</td></tr>
              <tr><th>Technician:</th><td>{{Form::select('projectOwnerLeft2[]', $sal_emps_arr, null, ['multiple','class'=>'form-control','id'=>'projectOwnerLeft2'])}} </td></tr>
              <tr><th>Task Detail:</th><td>{{ Form::textarea('notes2', null, ['class' => 'form-control','cols' => '20', 'rows'=>'3','id'=>'notes2']) }}</td></tr>
              <tr><th>Parent Task: </th><td>{{Form::select('parentTask2', $jobProj_SArr, null, ['class'=>'form-control','id'=>'parentTask2'])}} </td></tr>
              <tr><th>Include Days(Sat/Sun):</th><td>{{ Form::checkbox('projectIncludeDays2', '',null, ['class' => 'form-control','id'=>'projectIncludeDays2']) }}</td></tr>
              <tr><th>Completed:</th><td>{{ Form::checkbox('projectCompleted2', '',null, ['class' => 'form-control','id'=>'projectCompleted2']) }}</td></tr>
            </tbody>
          </table>
        </section> 
      </div>
      <div class="btn-group" style="padding:20px;">
       {{Form::submit('Save', array('class' => 'btn btn-success', 'id'=>'create_update_taskForProj','data-dismiss'=>'modal'))}}
       {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
     </div>
     {{Form::close()}}
   </div>
  </div>
  </div>
  </div>
  <!-- modal#6 end-->  
  <!-- Modal#7 -->
  <div class="modal fade" id="myModal7" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
          <h4 class="modal-title">Create/Update Project Task(s):</h4>
        </div>
        <div class="modal-body">
         {{ Form::open(array('before' => 'csrf' ,'id'=>'jobTaskProjFrmUpdate','url'=>route('job/updateProjTaskJob'),'files'=>true, 'method' => 'post')) }} {{Form::hidden('jobId3',$jobTblRow['id'])}} {{Form::hidden('jobNum3',$job_num)}} {{ Form::hidden('task_hidden_id','', array('id' => 'task_hidden_id')) }}                      
         <div class="form-group">
          <!-- ...code here.... -->
          <section id="no-more-tables"  style="padding:10px;">
           <table class="table table-bordered table-striped table-condensed cf">
            <tbody class="cf">
              <tr><th>Project Title:</th><td>{{ Form::text('projectTitle3',$jobTblRow['project_title'], array('class' => 'form-control', 'id' => 'projectTitle3','readOnly')) }}</td></tr>
              <tr><th>Task Title:</th><td>{{ Form::text('projectTaskTitle3','', array('class' => 'form-control', 'id' => 'projectTaskTitle3','required')) }}</td></tr>
              <tr><th>Start Date:</th><td>{{ Form::text('projectStartDate3','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'projectStartDate3')) }}</td></tr>
              <tr><th>Days Needed:</th><td>{{ Form::text('projectDays3','', array('class' => 'form-control', 'id' => 'projectDays3','required')) }}</td></tr>
              <tr><th>Resouce Forecast:</th><td>{{ Form::text('projectResourceForecast3','', array('class' => 'form-control', 'id' => 'projectResourceForecast3')) }}</td></tr>
              <tr><th>Subcontractor:</th><td>{{ Form::text('subcontractor3','', array('class' => 'form-control', 'id' => 'subcontractor3')) }}</td></tr>
              <tr><th>Activity ID:</th><td>{{ Form::text('project_activity_id3','', array('class' => 'form-control', 'id' => 'project_activity_id3')) }}</td></tr>
              <tr><th>Employee Type:</th><td>{{Form::select('selectEmpType3', $empTypeArr, null, ['class'=>'form-control','id'=>'selectEmpType3'])}}</td></tr>
              <tr><th>Technician:</th><td>{{Form::select('projectOwnerLeft3[]', $sal_emps_arr, null, ['multiple','class'=>'form-control','id'=>'projectOwnerLeft3'])}} </td></tr>
              <tr><th>Task Detail:</th><td>{{ Form::textarea('notes3', null, ['class' => 'form-control','cols' => '20', 'rows'=>'3','id'=>'notes3']) }}</td></tr>
              <tr><th>Parent Task: </th><td>{{Form::select('parentTask3', $jobProj_SArr, null, ['class'=>'form-control','id'=>'parentTask3'])}} </td></tr>
              <tr><th>Include Days(Sat/Sun):</th><td>{{ Form::checkbox('projectIncludeDays3', '',null, ['class' => 'form-control','id'=>'projectIncludeDays3']) }}</td></tr>
              <tr><th>Completed:</th><td>{{ Form::checkbox('projectCompleted3', '',null, ['class' => 'form-control','id'=>'projectCompleted3']) }}</td></tr>
            </tbody>
          </table>
        </section> 
      </div>
      <div class="btn-group" style="padding:20px;">
       {{Form::submit('Save', array('class' => 'btn btn-success', 'id'=>'create_update_taskForProjUpdate','data-dismiss'=>'modal'))}}
       {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
     </div>
     {{Form::close()}}
   </div>
  </div>
  </div>
  </div>
  <!-- modal#7 end-->
  <!-- Modal#8 -->
  <div class="modal fade" id="myModal8" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
          <h4 class="modal-title">Import Excel File {Job Task(s)}:</h4>
        </div>
        <div class="modal-body">
         <div class="form-group">
          <!-- ...code here.... -->
          <section id="no-more-tables"  style="padding:10px;">
           <table class="table table-bordered table-striped table-condensed cf">
            <tbody class="cf">
              {{ Form::open(array('before' => 'csrf' ,'id'=>'multiform2','url'=>route('job/importExcelJobTasks'),'files'=>true, 'method' => 'post')) }}
              {{Form::hidden('job_id',$jobTblRow['id'])}} {{Form::hidden('job_num',$job_num)}}
              {{ Form::file('ExlFileToUpload', array('style'=>'float: left !important; display:inline !important; width:50%;' ,'id' => 'ExlFileToUpload')) }}
              {{ Form::button('Upload File', array('class'=>'btn btn-success btn-xs','style'=>'float: left !important; display:inline !important;' , 'id' => 'ExlbuttonUpload')) }}
              {{Form::close()}}
            </tbody>
          </table>
        </section> 
      </div>
      <div class="btn-group" style="padding:20px;">
       {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
     </div>
   </div>
  </div>
  </div>
  </div>
  <!-- modal#8 end-->  
  <!-- Modal#9 -->
  <div class="modal fade" id="myModal9" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
          <h4 class="modal-title">Project Tasks Managment:</h4>
        </div>
        <div class="modal-body">
         <div class="form-group">
          <section id="no-more-tables"  style="padding:2px;">
           <table class="table table-bordered table-striped table-condensed cf">
            <thead class="cf">
              <tr><th>Task Title</th><th>Electrician</th><th>Status</th><th>Projected</th><th>Actual</th><th>Completed</th><th>Add Task</th></tr>
            </thead>
            <tbody class="cf">
              <tr><td data-title="Task:">{{ Form::text('project_task','', array('class' => 'form-control', 'id' => 'project_task','required')) }}</td>
                <td data-title="Electrician:">{{Form::select('electrician', $sal_emps_arr, null, ['class'=>'form-control','id'=>'electrician'])}}</td>
                <td data-title="Status:">{{ Form::checkbox('task_status','0','', array('id'=>'task_status','class' => 'input-group','style'=>'display:inline;')) }}</td>
                <td data-title="Projected:">{{ Form::text('projected','', array('class' => 'form-control', 'id' => 'projected','required')) }}</td>
                <td data-title="Actual:">{{ Form::text('actual','', array('class' => 'form-control', 'id' => 'actual','required')) }}</td>
                <td data-title="Completed:">{{ Form::checkbox('completed_task','0','', array('id'=>'completed_task','class' => 'input-group','style'=>'display:inline;')) }}</td>
                <td data-title="Add Task:">{{Form::button('Add Task', array('class' => 'btn btn-primary btn-xs','id'=>'create_quick_task'))}}</td></tr>
              </tbody>
            </table>
          </section>
          <div id="display_job_tasks_data"></div> 
        </div>
        <div class="btn-group" style="padding:20px;">
         {{Form::button('Cancel', array('class' => 'btn btn-warning','data-dismiss'=>'modal'))}}
       </div>
     </div>
   </div>
  </div>
  </div>
  <!-- modal#9 end-->  

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
    $('#link_to_job_num').focus(function() {
      $(this).autocomplete({
        source: function (request, response) {
          $.ajax({
            url: "{{URL('ajax/getJobNumberAutocomplete')}}",
            data: {
              JobNumber: this.term
            },
            success: function (data) {
              response( $.map( data, function( item ) {
                return {
                  label: item.name,
                  value: item.id
                };
              }));
            },
          });
        },
      });
    }); 
    $('#reset_fields').click(function(){
      $('#jobTechnicians').val("");
    }); 
    function autoFill() {
     if (confirm("The Customer Address Fields will be updated. Do you want to continue?")) {
      document.getElementById("cusAddress1").value = document.getElementById("jobAddress1").value;
      document.getElementById("cusAddress2").value = document.getElementById("jobAddress2").value;
      document.getElementById("cusCity").value = document.getElementById("jobCity").value;
      document.getElementById("cusState").value = document.getElementById("jobState").value;
      document.getElementById("cusZip").value = document.getElementById("jobZip").value;
      document.getElementById("cusPhone").value = document.getElementById("jobPhone").value;
    } 

  }

  function get_property_data(type,id) {
    if (id != "") {
     $.ajax({
      url: "{{URL('ajax/getAreaAssetFields')}}",
      data: {
        'id' : id,
        'type' : type
      },
      success: function (data) {
        if (type == "Location"){
         $('#chnge_to_list').html('<select id="propertyLocation" name="propertyLocation" class="form-control" onchange="get_property_data(\'Area\',this.value);">'+data+'</select>');
       }
       else if (type == "Area"){
         $('#parea_fields').html('<select id="propertyArea" name="propertyArea" class="form-control" onchange="get_property_data(\'Asset\',this.value);">'+data+'</select>');
       }
       else if (type == "Asset") {
         $('#passet_fields').html('<select multiple="multiple" id="propertyAsset" name="propertyAsset" class="form-control">'+data+'</select>');
       }

     },
   });
   }
  }
  $('#toggle_bill_info').click(function(){
    $('#show_hide_billing_info').toggle('slow');
  });

  $('#jobClosed').click(function () {
   if($("#jobClosed").is(':checked'))
    $("#closingDate").val('<?php echo date('Y-m-d');?>');
  else
    $("#closingDate").val('');
  });
  if(isNaN($('#invoiceCounter').val()))
    var invCount = 1;
  else
    var invCount = parseInt($('#invoiceCounter').val());
  var now_count = parseInt('0')+invCount;
  $('#create_another_row').click(function(){
    $('.default-date-picker').datepicker({
      format: 'yyyy-mm-dd'
    });
        $('#mytable tr:last').before("<tr><td data-title='Invoice Number:'><input class='form-control' type='text' id='invNumber_"+now_count+"' name='invNumber_"+now_count+"'></td><td data-title='Invoice File:'><input type='file' id='invFile_"+now_count+"' name='invFile_"+now_count+"'></td><td data-title='Invoice Date:'><input type='text' id='invDate_"+now_count+"' name='invDate_"+now_count+"' class='form-control form-control-inline input-medium default-date-picker' value='<?php echo date('Y-m-d');?>'></td><td data-title='Invoice Amt:'><input class='form-control' onchange='calInvAmount(this.id,this.value);' type='text' value='0' id='invAmount_"+now_count+"' name='invAmount_"+now_count+"'></td><td data-title='Invoice Tax:'><input class='form-control'  value='0' onchange='calInvAmount(this.id,this.value);' type='text' id='invTaxAmount_"+now_count+"' name='invTaxAmount_"+now_count+"'></td><td id='invoice_net_total_"+now_count+"'></td></tr>");
        if(now_count == 0)
          now_count =1;
        if(isNaN($('#invoiceCounter').val())){ 
          $('#update_elec_jobs').append('<input type="hidden" name="invoiceCounter" id="invoiceCounter" />'); 
        }  
        $('#invoiceCounter').val(now_count);
        $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
        });
        now_count = parseInt(now_count) + parseInt('1');
      });
  function calInvAmount(id,val){
    if ($('#invoiceCounter').val() == ""){
     if(id == 'invAmount_0'){
      var str = $('#invAmount_0').val();
      $('#individual_total').html(val);
      $('#invoice_net_total_0').html(parseFloat(str.replace(',', ''))-parseInt($('#invTaxAmount_0').val()));
      $('#grand_total_all').html(parseFloat(str.replace(',', ''))-parseInt($('#invTaxAmount_0').val()));
   			 		//;
           }
           else if(id == 'invTaxAmount_0'){
            var str = $('#invAmount_0').val();
            $('#individual_tax').html(val);
            $('#invoice_net_total_0').html(parseFloat(str.replace(',', ''))-parseInt($('#invTaxAmount_0').val()));
            $('#grand_total_all').html(parseFloat(str.replace(',', ''))-parseInt($('#invTaxAmount_0').val()));
          }
        }
        else{
         if (id.indexOf('invAmount_') > -1) {
          var split = id.split('invAmount_');
  		    //split[1]
  		    var tax = $('#invTaxAmount_'+split[1]).val();
  		    if(tax == "")
  		    	tax=0;
  		    var indv_total = 0;
  		    var gr_total = 0;

  		    for(i=0;i<=$('#invoiceCounter').val();i++){
            if ($('#invAmount_'+i).val() != null)
             indv_total = parseFloat(indv_total) + parseFloat(($('#invAmount_'+i).val()).replace(',', ''));
           if(i==0 && $('#invoice_net_total_'+i).val() != null)
            gr_total += parseFloat(((document.getElementById('invoice_net_total_'+i).innerHTML).replace(',', '')).replace('$', ''));
          else if($('#invoice_net_total_'+i).val() != null)	
           gr_total = parseFloat(gr_total) + parseFloat(document.getElementById('invoice_net_total_'+i).innerHTML);		
       }
       $('#individual_total').html(indv_total);
       $('#invoice_net_total_'+split[1]).html(parseFloat($('#invAmount_'+split[1]).val())-parseFloat(tax));
       $('#grand_total_all').html(gr_total);
     }

     if(id.indexOf('invTaxAmount_') > -1){
       var split = id.split('invTaxAmount_');
  		    //split[1]
  		    var str = $('#invAmount_'+split[1]).val();
  		    var indv_tax = 0;
         var gr_total = 0;

         for(i=0;i<=$('#invoiceCounter').val();i++){
           if ($('#invTaxAmount_'+i).val() != null)
             indv_tax = parseFloat(indv_tax) + parseFloat($('#invTaxAmount_'+i).val());
           if(i==0 && $('#invoice_net_total_'+i).val() != null)
            gr_total += parseFloat(((document.getElementById('invoice_net_total_'+i).innerHTML).replace(',', '')).replace('$', ''));
          else if ($('#invoice_net_total_'+i).val() != null)	
           gr_total = parseFloat(gr_total) + parseFloat(document.getElementById('invoice_net_total_'+i).innerHTML);				
       }
       $('#individual_tax').html(indv_tax);
       $('#invoice_net_total_'+split[1]).html(parseFloat(str.replace(',', ''))-parseFloat($('#invTaxAmount_'+split[1]).val()));
       $('#grand_total_all').html(gr_total);

     }
   }

   } //function ends here
   $("#daily_log_table").hide();
   $('#toggle_daily_logs').click(function(){
     $("#daily_log_table").toggle("slow");
     if ($('#toggle_daily_logs').attr("class") == "fa fa-plus-square")
      $('#toggle_daily_logs').removeClass('fa fa-plus-square').addClass('fa fa-minus-square');
    else 
      $('#toggle_daily_logs').removeClass('fa fa-minus-square').addClass('fa fa-plus-square');
  }); 

   $("#job_files_table").hide();
   $('#toggle_job_files').click(function(){
     $("#job_files_table").toggle("slow");
     if ($('#toggle_job_files').attr("class") == "fa fa-plus-square")
      $('#toggle_job_files').removeClass('fa fa-plus-square').addClass('fa fa-minus-square');
    else 
      $('#toggle_job_files').removeClass('fa fa-minus-square').addClass('fa fa-plus-square');
  });

   $("#proj_milst_table").hide();
   $('#toggle_project_milst').click(function(){
     $("#proj_milst_table").toggle("slow");
     if ($('#toggle_project_milst').attr("class") == "fa fa-plus-square")
      $('#toggle_project_milst').removeClass('fa fa-plus-square').addClass('fa fa-minus-square');
    else 
      $('#toggle_project_milst').removeClass('fa fa-minus-square').addClass('fa fa-plus-square');
  }); 

   $("#tasks_tg_table").hide();
   $('#toggle_tasks').click(function(){
     $("#tasks_tg_table").toggle("slow");
     if ($('#toggle_tasks').attr("class") == "fa fa-plus-square")
      $('#toggle_tasks').removeClass('fa fa-plus-square').addClass('fa fa-minus-square');
    else 
      $('#toggle_tasks').removeClass('fa fa-minus-square').addClass('fa fa-plus-square');
  }); 

   $('#search_email').click(function(){
     if ($('#sender_receiver_name').val()!='' || $('#subject').val()!='' || $('#start_date').val()!='' || $('#end_date').val()!='') {

      var send_rec_name=0;
      if ($('#sender_receiver_name').val() != '')
       send_rec_name=$('#sender_receiver_name').val();
     var subject=0;
     if ($('#subject').val() != '')
       subject=$('#subject').val();
     var start_date=0;
     if ($('#start_date').val() != '')
       start_date=$('#start_date').val();
     var end_date=0;
     if ($('#end_date').val() != '')
       end_date=$('#end_date').val();	

     $.ajax({
      url: "{{URL('ajax/getEmails')}}",
      data: {
        'name' : send_rec_name,
        'subject' : subject,
        'start_date' : start_date,
        'end_date' : end_date,
        'job_num' : '<?php echo $job_num;?>'
      },
      success: function (data) {
        $('#show_searched_email_data').html(data);    
                           	//$("#show_searched_email_data").replaceWith(data);
                           },
                         });

   }
  });

  $('#create_new_rfi').click(function(){
  			$("#multiform").submit(); //Submit the form
     });

  $("#rfi_table").hide();
  $('#toggle_rfis').click(function(){
   $("#rfi_table").toggle("slow");
   if ($('#toggle_rfis').attr("class") == "fa fa-plus-square")
    $('#toggle_rfis').removeClass('fa fa-plus-square').addClass('fa fa-minus-square');
  else 
    $('#toggle_rfis').removeClass('fa fa-minus-square').addClass('fa fa-plus-square');
  });

  $("#project_task_toggle_table").hide();
  $('#toggle_existing_project').click(function(){
   $("#project_task_toggle_table").toggle("slow");
   if ($('#toggle_existing_project').attr("class") == "fa fa-plus-square")
    $('#toggle_existing_project').removeClass('fa fa-plus-square').addClass('fa fa-minus-square');
  else 
    $('#toggle_existing_project').removeClass('fa fa-minus-square').addClass('fa fa-plus-square');
  });

  $('#create_update_proj_task').click(function(){
   if($('#projectTitle').val() != '' && $('#projectStartDate').val() != '' && $('#projectTaskTitle').val() != '' && $('#projectDays').val() != ''){
    $('#jobProjFrm').submit();
  }
  else{
    alert("Please Fill all required Fields!");
    return false;
  }
  });

  $('#create_update_taskForProj').click(function(){
   if($('#projectTitle2').val() != '' && $('#projectStartDate2').val() != '' && $('#projectTaskTitle2').val() != '' && $('#projectDays2').val() != ''){
    $('#jobTaskProjFrm').submit();
  }
  else{
    alert("Please Fill all required Fields!");
    return false;
  }
  });

  $('a[name=task_id_for_update_job_modal]').click(function(){
   var task_id = $(this).attr('task_id_for_job');
   $('#task_hidden_id').val(task_id); 
   $.ajax({
    url: "{{URL('ajax/getJobTaskInfo')}}",
    data: {
      'task_id' : task_id,
    },
    success: function (data) {
      $('#projectTaskTitle3').val(data.title);    
      $('#projectStartDate3').val(data.start_date);    
      $('#projectDays3').val(data.days);    
      $('#projectResourceForecast3').val(data.resource_hours);    
      $('#subcontractor3').val(data.subcontractor);    
      $('#project_activity_id3').val(data.project_activity_id);    
      $('#selectEmpType3').val(data.task_type);    
      $('#projectOwnerLeft3').val(data.GPG_employee_id);    
      $('#notes3').val(data.notes);    
      $('#parentTask3').val(data.parent_task);    
      $('#projectIncludeDays3').val(data.include_days);
    },
  });

  });

  $('#create_update_taskForProjUpdate').click(function(){
   if($('#projectTitle3').val() != '' && $('#projectStartDate3').val() != '' && $('#projectTaskTitle3').val() != '' && $('#projectDays3').val() != ''){
    $('#jobTaskProjFrmUpdate').submit();
  }
  else
    alert("Please Fill all required Fields!");
  });

  $('#submit_attachments').click(function(){
    $('#submit_file_form').submit();
  });

  $("#update_forms_info").click(function() {
    $("#update_elec_jobs").submit();
  });


  $("button[name='trash_job_file']").click(function(){
    var result = confirm("Want to delete?");
    if (result) {
      var id = $(this).attr('id');
      $.ajax({
        url: "{{URL('ajax/deleteFiles')}}",
        data: {
          'id' : id,
          'job_id' : '<?php echo $jobTblRow["id"];?>'
        },
        success: function (data) {
          if (data == '1'){
            alert("Deleted Successfully.");
            location.reload();
          }else{
            alert('Error Occured!');
          }
        },
      });

    }
  });

  $('#submit_asset_equip').click(function(){
    if ($('#assetEqpTech').val() == '' || $('#assetEqpCheckoutDate').val() == ''){
      alert("Please Select an Employee & check Out Date!");
      return false;
    }else{
      $("#equip_request_form").submit();
    }

  });

  $('#deleteEquipHist').click(function(){
    var result = confirm("Are u sure? you Want to delete?");
    if (result) {
      var id = $(this).attr('name');
      $.ajax({
        url: "{{URL('ajax/deleteEquipHistory')}}",
        data: {
          'id' : id
        },
        success: function (data) {
          if (data == '1'){
            alert("Deleted Successfully.");
            location.reload();
          }else{
            alert('Error Occured!');
          }
        },
      });
    }
  });

  $("a[name='show_tasks_of_project']").click(function(){
    var job_proj_id = $(this).attr('id_for_job_task');
    if (job_proj_id != ''){
      $.ajax({
        url: "{{URL('ajax/displayJobTasks')}}",
        data: {
          'id' : job_proj_id
        },
        success: function (data) {
         $('#display_job_tasks_data').html(data);
         $("button[name=up_job_task]").click(function(){
          var id = $(this).attr('up_id');
          $.ajax({
            url: "{{URL('ajax/updateJobProjectTask')}}",
            data: {
              'id' : id,
              'job_id' : '<?php echo $jobTblRow["id"];?>',
              'job_num' : '<?php echo $job_num;?>'
            },
            success: function (data) {
              alert("Updated Successfully.");
              location.reload();
            },
          });
        });

         $("button[name=del_job_task]").click(function(){
          var id = $(this).attr('del_id'); 
          var result = confirm("Are you sure? You want to delete this ...?");
          if(result){
           $.ajax({
            url: "{{URL('ajax/deleteJobProjectTask')}}",
            data: {
              'id' : id,
              'job_id' : '<?php echo $jobTblRow["id"];?>',
              'job_num' : '<?php echo $job_num;?>'
            },
            success: function (data) {
              alert("Deleted Successfully.");
              location.reload();
            },
          });
         }
       });

         $('#create_quick_task').click(function(){
          var proj_task = $('#project_task').val();
          var electrician = $('#electrician').val();
          var actual = $('#actual').val();
                    var task_status = document.getElementById("task_status").checked; //status check
                    if (task_status == 1)
                      task_status =1;
                    else
                      task_status =0;
                    var projected = $('#projected').val();
                    var completed_task = document.getElementById("completed_task").checked; //complete check
                    if (completed_task == 1)
                      completed_task =1;
                    else
                      completed_task =0;
                    if(proj_task != '' && electrician!='' && projected != ''){
                      $.ajax({
                        url: "{{URL('ajax/createJobProjectTask')}}",
                        data: {
                          'job_proj_id' : job_proj_id,
                          'proj_task': proj_task,
                          'electrician': electrician,
                          'task_status': task_status,
                          'projected': projected,
                          'completed_task': completed_task  
                        },
                        success: function (data) {
                          alert("Created Successfully.");
                                      //location.reload();
                                    },
                                  });
                    }else{
                      alert('Pleas, fill required fields!');
                    }
                  });

  },
  });
  }
  });

  $("button[name=del_task_by_id]").click(function(){
   var id = $(this).attr('task_id'); 
   var result = confirm("Are you sure? You want to delete this ...?");
   if(result){
    $.ajax({
      url: "{{URL('ajax/deleteJobTask')}}",
      data: {
        'id' : id,
        'job_id' : '<?php echo $jobTblRow["id"];?>',
        'job_num' : '<?php echo $job_num;?>'
      },
      success: function (data) {
        alert("Job Task, Deleted Successfully.");
        location.reload();
      },
    });
  }
  });
  </script>
  <script type="text/javascript">
    var i = 0;
    function drawGraph(div, data, title, countTitle, amountTitle, color, type){
      if(type == 'donut'){
        Morris.Donut({
          element: div,
          data: data,
          colors: ['#F15854','#F10855','#4D4D4D','#F17CB0', '#5DA5DA', '#B276B2', '#DECF3F','#571919','#FAA43A', '#60BD68', '#F1BC68', '#B2912F','#9C5353','#3B1FC9'],
          showLabel: "click",
          hideHover: true,
          formatter: function (x,y) {
            return x+'~#~'+ y.amount;
          }
        }).on('hover', function(i, row){
          var amountValue = row.value.split('~#~')
          $('#lb_'+div).html(row.label);
          $('#amt_'+div).html(amountTitle+': $'+amountValue[1]);
          if (div == 'Electrical-Jobs-Listing-Filters' || div == 'PO-JOB-Cost-Filters') {
            jdate = amountValue[0];
            jdatej = new Date(jdate*1000);
            $('#qt_'+div).html(countTitle+': '+jdatej.toLocaleDateString()); 
          }else{
            $('#qt_'+div).html(countTitle+': '+amountValue[0]);
          }


          $('div#Electrical-Jobs-Listing-Filters tspan').parent().hide();
          $('div#Service-Jobs-Listing-Filters tspan').parent().hide();
          $('div#PO-JOB-Cost-Filters tspan').parent().hide();
          $('div#PO-Detail-Data-Filters tspan').parent().hide();

        });
      }
    }
    $("#ExlFileToUpload").change(function() {
      var item = $("#ExlFileToUpload").val(); 
      if (item.split(".").pop(-1) != 'xlsx'){
        $("#ExlFileToUpload").val("");
        alert("Use Only Text(.xlsx) files to upload!");
      }
    });
    $('#ExlbuttonUpload').click(function(){
      if($("#ExlFileToUpload").val() == ''){
        alert('Please Select file first!');
        return false;
      }else{
        $('#multiform2').submit();
      }
    });
  </script>
  </body>
  </html>
