<!--sidebar start-->
<aside>
  <div id="sidebar"  class="nav-collapse ">
    <!-- sidebar menu start-->
    <ul class="sidebar-menu" id="nav-accordion">
      <li>
        <a href="/dashboard">
          <i class="fa fa-dashboard"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="/change_pass">
          <i class="fa fa-laptop"></i>
          <span>CHANGE PASSWORD</span>
        </a>
      </li>
      @foreach($left_menu[0] as $module_id=>$module_data)
<?php

      $moduleName =  Request::segment(1);
      $selectedClass = "";
      if (stripos(strtolower($module_data->module_name), $moduleName) !== false ){
          $selectedClass = "active";
      }else if($moduleName == 'quote' && strtolower($module_data->module_name) == 'jobs admin'){
        $selectedClass = "active";
      }else if($moduleName == 'salestracking' && strtolower($module_data->module_name) == 'sales tracking admin'){
        $selectedClass = "active";
      }else if($moduleName == 'purchaseorder' && strtolower($module_data->module_name) == 'purchase order admin'){
        $selectedClass = "active";
      }else if($moduleName == 'rfi' && strtolower($module_data->module_name) == 'request for information'){
        $selectedClass = "active";
      }else if($moduleName == 'invoice' && strtolower($module_data->module_name) == 'rental list'){
        $selectedClass = "active";
      }
      if($moduleName == 'reports' && strtolower($module_data->module_name) == 'reports'){
        $selectedClass = "active";
      }else if($moduleName == 'reports' && strtolower($module_data->module_name) == 'qc reports admin'){
        $selectedClass = '';
      }else if($moduleName == 'qc_reports' && strtolower($module_data->module_name) == 'qc reports admin'){
        $selectedClass = 'active';
      }
      if($moduleName == 'account' && strtolower($module_data->module_name) == 'admin settings'){
        $selectedClass = "active";
      }else if($moduleName == 'settings' && strtolower($module_data->module_name) == 'admin settings'){
        $selectedClass = "";
      }
      else if($moduleName == 'settings' && strtolower($module_data->module_name) == 'website settings'){
        $selectedClass = "active";
      }

///////////////ACL SECTION ////////////////////////////////
if (Auth::user()->ad_id == '1') {
  $qry = DB::select(DB::raw("Select * from gpg_module"));  
}else
  $qry = DB::select(DB::raw("Select m.module_name,m.module_action from gpg_module m,gpg_mod_perm mp where m.id=mp.GPG_module_id and mp.GPG_ad_acc_id=".Auth::user()->ad_id.""));


?>
@foreach($qry as $mkey=>$mData)
@if($module_data->module_name == $mData->module_name)
      <li class="sub-menu">
        @if($module_data->module_name == 'EXCEL REPORT GENERATOR')
          <a href="/reports/opt">
            <i class="fa {{Generic::set_font_awesome($module_id)}}"></i>
            <span>{{$module_data->module_name}}</span>
          </a>
        @elseif($module_data->module_name == 'BULK JOB UPLOADER')
          <a href="/uploader">
            <i class="fa {{Generic::set_font_awesome($module_id)}}"></i>
            <span>{{$module_data->module_name}}</span>
          </a>
        @else
        <a href="javascript:;" class="<?=$selectedClass?>">
          <i class="fa {{Generic::set_font_awesome($module_id)}}"></i>
          <span>{{$module_data->module_name}}</span>
        </a>
        @endif

<!-- just copy 3 line to code at end comparison and paste-->

        @if(isset($left_menu[$module_data->id])) 
        <ul class="sub">
          @foreach($left_menu[$module_data->id] as $child_module_id=>$child_module_data)
          <?php if ($child_module_data->module_name == 'TIMESHEET MANAGEMENT') { ?>
          <li><a href="/timesheet">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'MANAGE_REBATE') { ?>
          <?php }
          elseif ($child_module_data->module_name == 'SERVICE JOB FSW REPORT') { ?>
          <li><a href="/reports/service_job_fsw_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ACTIVITY INDEX') { ?>
          <li><a href="/activity">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD NEW ACCOUNT') { ?>
          <li><a href="/account/create">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'VIEW UPDATE ACCOUNT') { ?>
          <li><a href="/account">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'JOB REGARDING REPORT') { ?>
          <li><a href="/reports/job_regarding_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'WIP REPORT') { ?>
          <li><a href="/reports/wip_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'CONTRACT COMPARISON REPORT') { ?>
          <li><a href="/reports/contract_comparison_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'GENERAL REPORT SUMMARY') { ?>
          <li><a href="/reports/gen_reportsummary">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'COMPLETED SERVICE JOBS REPORT') { ?>
          <li><a href="/reports/completed_service_jobs_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'INVOICE AMT REPORT') { ?>
          <li><a href="/reports/invoice_amt_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'DETAILED FINANCIAL REPORT SUMMARY') { ?>
          <li><a href="/reports/detailed_financial_report_summary">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'FINANCIAL REPORT SUMMARY') { ?>
          <li><a href="/reports/financial_report_summary">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'JOB TIME REPORT') { ?>
          <li><a href="/reports/job_time_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'MISSINNG HOUR REPORT') { ?>
          <li><a href="/reports/missing_hour_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'GEN SERVICE JOBS REPORT') { ?>
          <li><a href="/reports/gen_service_jobs_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'PAYROLL REPORT') { ?>
          <li><a href="/reports/pay_reportopt">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'COST DUPLICATION REPORT') { ?>
          <li><a href="/reports/cost_duplication_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'TC JOB FSW REPORT') { ?>
          <li><a href="/reports/tc_jobs_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ACTIVITY REPORT') { ?>
          <li><a href="/reports/activity_report">{{$child_module_data->module_name}}</a></li>
           <?php }
          elseif ($child_module_data->module_name == 'GENERAL REPORT') { ?>
          <li><a href="/reports/gen_reportopt">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'MODELING REPORT') { ?>
          <li><a href="/reports/modeling_reportopt">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'EMPLOYEE REPORT') { ?>
          <li><a href="/reports/emp_reportopt">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'DUP SERVICE JOB') { ?>
          <li><a href="/qc_reports/service_job_check">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'SALES TAX EXCEPTION REPORT') { ?>
          <li><a href="/qc_reports/sales_tax_exception_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'WRONG PREVAILING JOBS REPORT') { ?>
          <li><a href="/qc_reports/wrong_prevailing_jobs_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'PROJECTED MARGIN REPORT') { ?>
          <li><a href="/qc_reports/projected_margin_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'JOB EXCEPTION REPORT') { ?>
          <li><a href="/qc_reports/job_exception_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'CUSTOMER JOB REPORT') { ?>
          <li><a href="/qc_reports/customer_job_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'PRO FIXTURES USAGE FREQ') { ?>
          <li><a href="/qc_reports/pro_fixtures_usage_freq">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ENGINE_KW_PRICING') { ?>
          <li><a href="/qc_reports/engine_kw_pricing">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'MAPPING EXPORT') { ?>
          <li><a href="/qc_reports/mapping_export">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'CUSTOMER JOB DETAIL REPORT') { ?>
          <li><a href="/qc_reports/customer_job_detail_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'OVER_HEAD_BUDGETING_IMPORT') { ?>
          <li><a href="/qc_reports/over_head_budgeting">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'OVER_HEAD_BUDGETING_REPORT') { ?>
          <li><a href="/qc_reports/over_head_budgeting_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'BILLABLE HOURS REPORT') { ?>
          <li><a href="/qc_reports/billable_hours_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'PARTS COSTS CHECK') { ?>
          <li><a href="/qc_reports/parts_costs_check">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'CUSTOMER CONTACT DETAIL REPORT') { ?>
          <li><a href="/qc_reports/customer_contact_detail_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'EMPLOYEE PAYABLE AMOUNT REPORT') { ?>
          <li><a href="/qc_reports/employee_payable_amount_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'CUSTOMER CONTACT INFO') { ?>
          <li><a href="/qc_reports/customer_contact_info">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'MISSING JOBS INFO') { ?>
          <li><a href="/qc_reports/missing_jobs_info">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'CONTRACT PROFIT REPORT') { ?>
          <li><a href="/qc_reports/contract_profitability_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'QUICKBOOK ANALYZER') { ?>
          <li><a href="/qc_reports/quickbook_analyzer">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'PROJ ACTIVITY REPORT') { ?>
          <li><a href="/qc_reports/proj_activity_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'FIELD MATERIAL TYPE') { ?>
          <li><a href="/parts/field_material_type_index">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'SALES MANAGEMENT') { ?>
          <li><a href="/salestracking">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'SALES MANAGEMENT CONTACT PHASE') { ?>
          <li><a href="/salestracking/index_contact_phase">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'SALES MANAGEMENT QUOTE PHASE') { ?>
          <li><a href="/salestracking/index_quote_phase">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'CONTACT CALENDAR') { ?>
          <li><a href="/salestracking/contact_calendar">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'DELETED SALES MANAGEMENT') { ?>
          <li><a href="/salestracking/delete_salestracking">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'SALES CONTACT QUOTE PHASE REPORT') { ?>
          <li><a href="/salestracking/contact_quote_phase_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'SALES AS TODAY DATE REPORT') { ?>
          <li><a href="/salestracking/lead_entered_report">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'FIELD MATERIAL INDEX') { ?>
          <li><a href="/parts">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'FIELD MATERIAL PART USED') { ?>
          <li><a href="/parts/field_material_used">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD FIELD MATERIAL') { ?>
          <li><a href="/parts/create">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'FIELD COMPONENT TYPE') { ?>
          <li><a href="/parts/field_component_type_index">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'FIELD COMPONENT INDEX') { ?>
          <li><a href="/parts/field_component_index">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD FIELD COMPONENT') { ?>
          <li><a href="/parts/add_field_component">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'FIELD MATERIAL UPLOADER') { ?>
          <li><a href="/parts/field_material_uploader_opt">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'MANAGEMENT JOB ELECTRICAL SUBQUOTE PROPOSED FIXTURE') { ?>
          <li><a href="/parts/manage_fixture_type">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD JOB ELECTRICAL SUBQUOTE PROPOSED FIXTURE') { ?>
          <li><a href="/parts/add_proposed_fixture">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD JOB ELECTRICAL SUBQUOTE EXISTING FIXTURE') { ?>
          <li><a href="/parts/add_existing_fixture">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'MANAGEMENT JOB ELECTRICAL SUBQUOTE EXISTING FIXTURE') { ?>
          <li><a href="/parts/existing_fixture_index">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD REBATE') { ?>
          <li><a href="/parts/add_rebate">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'CONTRACT AMT UPLOADER') { ?>
          <li><a href="/contract/contract_amt_opt">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'CONTRACT_INFO_IMP') { ?>
          <li><a href="/contract/contract_info_imp">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD EXPENSE TYPE') { ?>
          <li><a href="/expense/create">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'MANAGE EXPENSE TYPE') { ?>
          <li><a href="/expense">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'CURRENT EXPENSE') { ?>
          <li><a href="/expense/current_expense">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'EXPENSE HISTORY') { ?>
          <li><a href="/expense/expense_history">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'EXPENSE UPLOADER') { ?>
          <li><a href="/expense/expense_amt_imp_opt">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'GL EXPENSE INDEX') { ?>
          <li><a href="/expense/glcode_expense_index">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD TASK') { ?>
          <li><a href="/task/create">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'MANAGE TASKS') { ?>
          <li><a href="/task">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ASSIGNED TASKS') { ?>
          <li><a href="/task/assigned_task">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'OWN TASKS') { ?>
          <li><a href="/task/own_task">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'TASK CALENDAR') { ?>
          <?php }
          elseif ($child_module_data->module_name == 'FOLLOWUP UNASSIGNED') { ?>
          <li><a href="/task/admin_followup">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'FOLLOWUP ASSIGNED') { ?>
          <li><a href="/task/user_followup">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'GENERAL SETTINGS') { ?>
          <li><a href="/settings">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD COUNTRY') { ?>
          <li><a href="/settings/create">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'COUNTRY MANAGEMENT') { ?>
          <li><a href="/settings/cview">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'KW MATRIX MANAGEMENT') { ?>
          <li><a href="/settings/kw_matrix">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'EMAILS ATTACHED TO JOBS') { ?>
          <li><a href="/emails">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ARCHIVE EMAILS') { ?>
          <li><a href="/emails/archive_emails">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'EMAILS BLOCK LIST') { ?>
          <li><a href="/emails/blocklist">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ASSET EQUIPMENT MANAGEMENT') { ?>
          <li><a href="/asset">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ASSET ADD EQUIPMENT') { ?>
          <li><a href="/asset/create">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ASSET CHECKOUT EQUIPMENT') { ?>
          <li><a href="/asset/checkout_asset_equipment">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ASSET CHECKIN EQUIPMENT') { ?>
          <li><a href="/asset/checkin_asset_equipment">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ASSET EQPUIPMENT TYPE MANAGEMENT') { ?>
          <li><a href="/asset/asset_equipment_type_index">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ASSET ADD EQUIPMENT TYPE') { ?>
          <li><a href="/asset/add_asset_equipment_type">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'RENTAL LIST') { ?>
          <li><a href="/invoice">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'PO MANAGEMENT') { ?>
          <li><a href="/purchaseorder">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'PO MANAGEMENT RECD') { ?>
          <li><a href="/purchaseorder/index_recd">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'DELETED PO MANAGEMENT') { ?>
          <li><a href="/purchaseorder/delete_purchaseorder">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'GLCODE MANAGEMENT') { ?>
            <li><a href="/glcode">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD NEW GLCODE') { ?>
            <li><a href="/glcode/create">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD NEW EXPENSE GLCODE') { ?>
            <li><a href="/glcode/add_expense_glcode">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'EXPENSE GLCODE MANAGEMENT') { ?>
            <li><a href="/glcode/expense_glcode_index">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD RFI') { ?>
            <li><a href="/rfi/create">{{$child_module_data->module_name}}</a></li>
           <?php }
          elseif ($child_module_data->module_name == 'REQUEST_FOR_INFORMATION_LISTING') { ?>
            <li><a href="/rfi">{{$child_module_data->module_name}}</a></li>  
          <?php }
          elseif ($child_module_data->module_name == 'EQUIPMENT MANAGEMENT') { ?>
          <li><a  href="/equipment">{{$child_module_data->module_name}}</a></li> 
          <?php }
          elseif ($child_module_data->module_name == 'ADD EQUIPMENT') { ?>
          <li><a  href="/equipment/create">{{$child_module_data->module_name}}</a></li> 
          <?php }
          elseif ($child_module_data->module_name == 'RENTAL COMPANY MANAGEMENT') { ?>
          <li><a  href="/equipment/list_rental_company">{{$child_module_data->module_name}}</a></li> 
          <?php }
          elseif ($child_module_data->module_name == 'ADD RENTAL COMPANY') { ?>
          <li><a  href="/equipment/add_rental_company">{{$child_module_data->module_name}}</a></li> 
          <?php }
          elseif ($child_module_data->module_name == 'EQUPMENT READING LOG') { ?>
            <li><a  href="/equipment/equipment_reading_log">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'EMPLOYEES MANAGEMENT') { ?>
            <li><a  href="/employees">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'EMPLOYEE TYPE MANAGEMENT') { ?>
            <li><a  href="/employees/listEmployeeTypes">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD EMPLOYEE TYPE') { ?>
            <li><a  href="/employees/create">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ADD NEW EMPLOYEE') { ?>
            <li><a  href="/employees/addNewEmployee">{{$child_module_data->module_name}}</a></li>
         <?php }
          elseif ($child_module_data->module_name == 'WAGE LOG') { ?>
            <li><a  href="/employees/wageLogList">{{$child_module_data->module_name}}</a></li>
         <?php }
          elseif ($child_module_data->module_name == 'COMMISSION LOG') { ?>
            <li><a  href="/employees/wageCommissionList">{{$child_module_data->module_name}}</a></li>   
         <?php }
          elseif ($child_module_data->module_name == 'BURDEN LOG') { ?>
            <li><a  href="/employees/burdunList">{{$child_module_data->module_name}}</a></li>
          <?php   }
          elseif ($child_module_data->module_name == 'WAGES MANAGEMENT') { ?>
            <li><a  href="/wages">{{$child_module_data->module_name}}</a></li> 
          <?php }
          elseif ($child_module_data->module_name == 'SET TIMESHEET WAGE') { ?>
            <li><a  href="/wages/setTimesheetWage">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'RECALCULATE WAGES') { ?>
            <li><a  href="/wages/recalculateWages">{{$child_module_data->module_name}}</a></li>  
          <?php }
          elseif ($child_module_data->module_name == 'IMPORT WAGES COUNTY') { ?>
            <li><a  href="/wages/importWagesCounty">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'PROJECT MANAGEMENT'){ ?>
          <li><a  href="/job/job_project">{{$child_module_data->module_name}}</a></li>  
          <?php }
          elseif ($child_module_data->module_name == 'SALES AMT IMPORT'){ ?>
            <li><a  href="/job/sales_amt_imp_opt">{{$child_module_data->module_name}}</a></li>  
           <?php }
          elseif ($child_module_data->module_name == 'JOB CALENDAR'){ ?>
            <li><a  href="/job/job_calendar">{{$child_module_data->module_name}}</a></li>    
          <?php }
          elseif ($child_module_data->module_name == 'SHOP WORK JOB LIST'){ ?>
            <li><a  href="/job/shopWorkJobList">{{$child_module_data->module_name}}</a></li> 
          <?php }
          elseif ($child_module_data->module_name == 'JOBS MANAGEMENT'){ ?>
            <li><a  href="/job/job_management">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'JOB COST MANAGE'){ ?>
            <li><a  href="/job/job_cost_manage">{{$child_module_data->module_name}}</a></li>
          <?php }
          elseif ($child_module_data->module_name == 'ELECTRICAL COMMISSION LIST'){ ?>
            <li><a  href="/job/commission_list">{{$child_module_data->module_name}}</a></li>  
          <?php }
          elseif ($child_module_data->module_name == 'SERVICE JOB LIST'){ ?>
            <li><a  href="/job/service_job_list">{{$child_module_data->module_name}}</a></li>  
          <?php }
          elseif ($child_module_data->module_name == 'FINANCIAL REPORT'){ ?>
            <li><a  href="/job/financial_report">{{$child_module_data->module_name}}</a></li>    
          <?php }
          elseif ($child_module_data->module_name == 'JOB DUE AMT AR IMPORT'){ ?>
            <li><a  href="/job/job_due_amt_imp_opt_ar">{{$child_module_data->module_name}}</a></li>
           <?php }
          elseif ($child_module_data->module_name == 'FIELD SERVICE WORK LIST'){ ?>
            <li><a  href="/job/field_service_work_list">{{$child_module_data->module_name}}</a></li>  
           <?php }
          elseif ($child_module_data->module_name == 'JOB COST CHECK'){ ?>
            <li><a  href="/job/job_cost_check">{{$child_module_data->module_name}}</a></li>    
          <?php }
          elseif ($child_module_data->module_name == 'JOB DUE AMT AP IMPORT'){ ?>
            <li><a  href="/job/job_due_amt_imp_opt_ap">{{$child_module_data->module_name}}</a></li> 
          <?php }
          elseif ($child_module_data->module_name == 'CONTRACT AMT IMPORT'){ ?>
            <li><a  href="/job/contract_amt_imp_opt">{{$child_module_data->module_name}}</a></li>      
          <?php 
            }elseif ($child_module_data->module_name == 'ADD NEW WAGE PLAN') { ?>
            <li><a  href="/wages/create">{{$child_module_data->module_name}}</a></li>    
          <?php 
            }elseif ($child_module_data->module_name == 'HOLIDAYS MANAGEMENT') { ?>
            <li><a  href="/holiday">{{$child_module_data->module_name}}</a></li>   
            <?php 
            }elseif ($child_module_data->module_name == 'ADD NEW HOLIDAY') { ?>
            <li><a  href="/holiday/create">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'MANAGE LEAVES') { ?>
            <li><a  href="/holiday/manage_leaves">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'EDIT VACATION SICK') { ?>
            <li><a  href="/holiday/edit_vacation_sick">{{$child_module_data->module_name}}</a></li>     
            <?php 
            }elseif ($child_module_data->module_name == 'MANAGE DEPARTMENT') { ?>
            <li><a  href="/department">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'ADD DEPARTMENT') { ?>
            <li><a  href="/department/create">{{$child_module_data->module_name}}</a></li>
            <?php }
            elseif ($child_module_data->module_name == 'CLR SERVICE JOBS IMP') { ?>
            <li><a  href="/job/clear_service_jobs">{{$child_module_data->module_name}}</a></li> 
             <?php }
            elseif ($child_module_data->module_name == 'LINK BILL ONLY JOB') { ?>
            <li><a  href="/job/link_jobs">{{$child_module_data->module_name}}</a></li> 
             <?php }
            elseif ($child_module_data->module_name == 'JOB EXPORT') { ?>
            <li><a  href="/job/job_export_opt">{{$child_module_data->module_name}}</a></li> 
             <?php }
            elseif ($child_module_data->module_name == 'JOB COST IMPORT') { ?>
            <li><a  href="/job/job_cost_opt">{{$child_module_data->module_name}}</a></li> 
            <?php }
            elseif ($child_module_data->module_name == 'ASSIGN TECHNICIANS IMP') { ?>
            <li><a  href="/job/assign_technicians_imp">{{$child_module_data->module_name}}</a></li> 
            <?php }
            elseif ($child_module_data->module_name == 'ADD NEW CUSTOMER') { ?>
            <li><a  href="/customers/create">{{$child_module_data->module_name}}</a></li> 
            <?php }
            elseif ($child_module_data->module_name == 'VENDORS MANAGEMENT') { ?>
            <li><a  href="/vendors">{{$child_module_data->module_name}}</a></li>
            <?php }
            elseif ($child_module_data->module_name == 'ADD NEW VENDOR') { ?>
            <li><a  href="/vendors/create">{{$child_module_data->module_name}}</a></li>
            <?php }
            elseif ($child_module_data->module_name == 'CUSTOMERS MANAGEMENT') { ?>
            <li><a  href="/customers">{{$child_module_data->module_name}}</a></li>
            <?php
            }elseif (($child_module_data->module_name == 'DEPARTMENT USERS') || ($child_module_data->module_name == 'MANAGE DEPARTMENT USERS')) { ?>
            <li></li>
            <?php 
            }elseif ($child_module_data->module_name == 'ELECTRICAL JOB LIST') { ?>
            <li><a  href="/job/elec_job_list">{{$child_module_data->module_name}}</a></li>     
             <?php 
            }elseif ($child_module_data->module_name == 'SHOP WORK QUOTE LIST') { ?>
            <li><a  href="/quote/shop_work_quote_list">{{$child_module_data->module_name}}</a></li>     
            <?php }elseif ($child_module_data->module_name == 'ELECTRICAL QUOTE LIST') { ?>
            <li><a  href="/quote/elec_quote_list">{{$child_module_data->module_name}}</a></li>
            <?php }elseif ($child_module_data->module_name == 'GRASSIVY QUOTE LIST') { ?>
            <li><a  href="/quote/grassivy_quote_list">{{$child_module_data->module_name}}</a></li>
            <?php }elseif ($child_module_data->module_name == 'SPECIAL PROJECT QUOTE LIST') { ?>
            <li><a  href="/quote/specialproject_quote_list">{{$child_module_data->module_name}}</a></li>
            <?php }
            elseif ($child_module_data->module_name == 'MANAGE PROPERTY LOCATION') { ?>
            <li><a  href="/customers/manageProperty">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'GRASSIVY JOB LIST') { ?>
            <li><a  href="/job/grassivyJobList">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'SPECIAL PROJECT JOB LIST') { ?>
            <li><a  href="/job/specialProjectJobList">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'CONTRACT LIST') { ?>
            <li><a  href="/contract/contractList">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'CONSUM SERVICE TYPE') { ?>
            <li><a  href="/contract/consumServiceType">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'EQUIPMENT INFO INDEX') { ?>
            <li><a  href="/consumecontract/equipmentInfoList">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'ADD NEW JOB') { ?>
            <li><a  href="/quote/create">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'ADD NEW JOB CATEGORY') { ?>
            <li><a  href="/quote/new_jobcat">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'JOB CAT MANAGEMENT') { ?>
            <li><a  href="/quote/">{{$child_module_data->module_name}}</a></li>
            <?php 
            }elseif ($child_module_data->module_name == 'CONTRACT AMT LIST') { ?>
            <li><a  href="/contractAmount/contractAmountList">{{$child_module_data->module_name}}</a></li>
          <?php }else {?>
          <li><a  href="boxed_page.html">{{$child_module_data->module_name}}</a></li>
          <?php   } ?>
          @endforeach   
        </ul> 
        @endif
      </li>
@endif
@endforeach
      @endforeach
      <li>
        <a href="{{URL::to('logout')}}">
          <i class="fa fa-power-off"></i>
          <span>Logout</span>
        </a>
      </li>
    </ul>
    <!-- sidebar menu end-->
  </div>
</aside>
      <!--sidebar end-->
      <script>

      </script>