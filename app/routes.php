<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// checking authorization
Route::group(['before'=>'auth'], function() {

    Route::get('dashboard',"MainController@dashboard");
    Route::get('change_pass', array('as' => 'change_pass','uses'=>'MainController@changePassword')); 
    Route::post('change_pass', array('as' => 'change_pass','uses'=>'MainController@changePassword')); 
    Route::get('/', function()
	{
		return Redirect::to('login');
	});
	Route::get('logout',function(){
		Auth::logout();
		return Redirect::to('login');
	});
	
	/*Route::get('dashboard_gen_sev_job_panel/{mon}/{yer}/{flag}', function($mon, $yer, $flag){
		get_dashboard_gen_sev_job_panel($mon, $yer, $flag);
	});*/
        /*
        * AccountController
        */
        Route::get('account/index', array('as' => 'account/index','uses'=>'AccountController@index'));
        Route::post('account/index', array('as' => 'account/index','uses'=>'AccountController@index'));
        Route::post('account/changePass', array('as' => 'account/changePass','uses'=>'AccountController@changePass'));      
        Route::get('account/excelAccountsExport', array('as' => 'account/excelAccountsExport','uses'=>'AccountController@excelAccountsExport'));
        Route::Resource('account','AccountController');
        /*
        * ActivityController
        */
        Route::get('activity/index', array('as' => 'activity/index','uses'=>'ActivityController@index'));
        Route::post('activity/index', array('as' => 'activity/index','uses'=>'ActivityController@index'));
        Route::Resource('activity','ActivityController');
        /*
        * SalesTrackingController
        */
        Route::get('salestracking/index', array('as' => 'salestracking/index','uses'=>'SalesTrackingController@index'));
        Route::post('salestracking/index', array('as' => 'salestracking/index','uses'=>'SalesTrackingController@index'));
        Route::get('salestracking/index_contact_phase', array('as' => 'salestracking/index_contact_phase','uses'=>'SalesTrackingController@listContactPhase'));
        Route::post('salestracking/index_contact_phase', array('as' => 'salestracking/index_contact_phase','uses'=>'SalesTrackingController@listContactPhase'));
        Route::get('salestracking/index_quote_phase', array('as' => 'salestracking/index_quote_phase','uses'=>'SalesTrackingController@listQuotePhase'));
        Route::post('salestracking/index_quote_phase', array('as' => 'salestracking/index_quote_phase','uses'=>'SalesTrackingController@listQuotePhase'));
        Route::post('salestracking/createQSTModal', array('as' => 'salestracking/createQSTModal','uses'=>'SalesTrackingController@createQSTModal'));
        Route::get('salestracking/contact_calendar', array('as' => 'salestracking/contact_calendar','uses'=>'SalesTrackingController@contactCalendar'));
        Route::post('salestracking/contact_calendar', array('as' => 'salestracking/contact_calendar','uses'=>'SalesTrackingController@contactCalendar'));
        Route::get('salestracking/delete_salestracking', array('as' => 'salestracking/delete_salestracking','uses'=>'SalesTrackingController@deletedSalesTracking'));
        Route::get('salestracking/contact_quote_phase_report', array('as' => 'salestracking/contact_quote_phase_report','uses'=>'SalesTrackingController@contactQuotePhaseReport'));
        Route::post('salestracking/contact_quote_phase_report', array('as' => 'salestracking/contact_quote_phase_report','uses'=>'SalesTrackingController@contactQuotePhaseReport'));
        Route::get('salestracking/lead_entered_report', array('as' => 'salestracking/lead_entered_report','uses'=>'SalesTrackingController@leadEnteredReport'));        
        Route::post('salestracking/lead_entered_report', array('as' => 'salestracking/lead_entered_report','uses'=>'SalesTrackingController@leadEnteredReport'));        
        Route::post('salestracking/manageSTFiles', array('as' => 'salestracking/manageSTFiles','uses'=>'SalesTrackingController@manageSTFiles'));        
        Route::post('salestracking/updateCSTModal', array('as' => 'salestracking/updateCSTModal','uses'=>'SalesTrackingController@updateCSTModal'));          
        Route::post('salestracking/updateSTRModal', array('as' => 'salestracking/updateSTRModal','uses'=>'SalesTrackingController@updateSTRModal'));          
        Route::get('salestracking/excelSTExport', array('as' => 'salestracking/excelSTExport','uses'=>'SalesTrackingController@excelSTExport'));        
        Route::post('salestracking/updateRCPModal', array('as' => 'salestracking/updateRCPModal','uses'=>'SalesTrackingController@updateRCPModal'));        
        Route::post('salestracking/createRCPModal', array('as' => 'salestracking/createRCPModal','uses'=>'SalesTrackingController@createRCPModal'));        
        Route::get('salestracking/excelLDExport', array('as' => 'salestracking/excelLDExport','uses'=>'SalesTrackingController@excelLDExport'));        
        Route::get('ajax/getContactSTInfo', array('uses'=>'SalesTrackingController@getContactSTInfo'));       
        Route::get('ajax/getSTFiles', array('uses'=>'SalesTrackingController@getSTFiles'));       
        Route::get('ajax/deleteSTFile', array('uses'=>'SalesTrackingController@deleteSTFile'));       
        Route::get('ajax/getSTRowData', array('uses'=>'SalesTrackingController@getSTRowData'));       
        Route::get('ajax/restoreSTR', array('uses'=>'SalesTrackingController@restoreSTR'));       
        Route::Resource('salestracking','SalesTrackingController');
        /*
        * PartsController
        */
        Route::get('parts/field_material_type_index', array('as' => 'parts/field_material_type_index','uses'=>'PartsController@fieldMaterialTypeIndex'));
        Route::get('parts/index', array('as' => 'parts/index','uses'=>'PartsController@index'));
        Route::get('parts/field_material_used', array('as' => 'parts/field_material_used','uses'=>'PartsController@fieldMaterialUsed'));
        Route::post('parts/field_material_used', array('as' => 'parts/field_material_used','uses'=>'PartsController@fieldMaterialUsed'));
        Route::get('parts/field_component_type_index', array('as' => 'parts/field_component_type_index','uses'=>'PartsController@fieldComponentTypeIndex'));
        Route::post('parts/field_component_type_index', array('as' => 'parts/field_component_type_index','uses'=>'PartsController@fieldComponentTypeIndex'));
        Route::get('parts/field_component_index', array('as' => 'parts/field_component_index','uses'=>'PartsController@fieldComponentIndex'));
        Route::post('parts/field_component_index', array('as' => 'parts/field_component_index','uses'=>'PartsController@fieldComponentIndex'));
        Route::get('parts/add_field_component', array('as' => 'parts/add_field_component','uses'=>'PartsController@addFieldComponent'));
        Route::get('parts/edit_field_component/{id}', array('as' => 'parts/edit_field_component','uses'=>'PartsController@editFieldComponent'));
        Route::post('parts/edit_field_component/{id}', array('as' => 'parts/edit_field_component','uses'=>'PartsController@editFieldComponent'));
        Route::post('parts/add_field_component', array('as' => 'parts/add_field_component','uses'=>'PartsController@addFieldComponent'));
        Route::get('parts/field_material_uploader_opt', array('as' => 'parts/field_material_uploader_opt','uses'=>'PartsController@fieldMaterialUploader'));
        Route::post('parts/field_material_uploader_opt', array('as' => 'parts/field_material_uploader_opt','uses'=>'PartsController@fieldMaterialUploader'));
        Route::get('parts/manage_fixture_type', array('as' => 'parts/manage_fixture_type','uses'=>'PartsController@manageFixtureType'));
        Route::post('parts/manage_fixture_type', array('as' => 'parts/manage_fixture_type','uses'=>'PartsController@manageFixtureType'));
        Route::get('parts/add_proposed_fixture', array('as' => 'parts/add_proposed_fixture','uses'=>'PartsController@addProposedFixture'));
        Route::post('parts/add_proposed_fixture', array('as' => 'parts/add_proposed_fixture','uses'=>'PartsController@addProposedFixture'));
        Route::get('parts/add_existing_fixture', array('as' => 'parts/add_existing_fixture','uses'=>'PartsController@addExistingFixture'));
        Route::post('parts/add_existing_fixture', array('as' => 'parts/add_existing_fixture','uses'=>'PartsController@addExistingFixture'));
        Route::get('parts/existing_fixture_index', array('as' => 'parts/existing_fixture_index','uses'=>'PartsController@existingFixtureIndex'));
        Route::post('parts/existing_fixture_index', array('as' => 'parts/existing_fixture_index','uses'=>'PartsController@existingFixtureIndex'));
        Route::get('parts/add_rebate', array('as' => 'parts/add_rebate','uses'=>'PartsController@addRebate'));
        Route::get('parts/edit_existing_fixture/{id}', array('as' => 'parts/edit_existing_fixture','uses'=>'PartsController@editEFix'));  
        Route::get('parts/excelPartsExport', array('as' => 'parts/excelPartsExport','uses'=>'PartsController@excelPartsExport'));          
        Route::post('parts/edit_existing_fixture/{id}', array('as' => 'parts/edit_existing_fixture','uses'=>'PartsController@editEFix'));  
        Route::post('parts/index', array('as' => 'parts/index','uses'=>'PartsController@index'));
        Route::post('parts/deletePartType/{id}', array('as' => 'parts/deletePartType','uses'=>'PartsController@deletePartType'));
        Route::get('ajax/updatePartType', array('uses'=>'PartsController@updatePartType'));  
        Route::get('ajax/switchPartStatus', array('uses'=>'PartsController@switchPartStatus'));  
        Route::get('ajax/updateEquipType', array('uses'=>'PartsController@updateEquipType'));  
        Route::post('parts/deleteEquipType/{id}', array('as' => 'parts/deleteEquipType','uses'=>'PartsController@deleteEquipType'));
        Route::post('parts/deleteSEquip/{id}', array('as' => 'parts/deleteSEquip','uses'=>'PartsController@deleteSEquip'));
        Route::post('parts/deleteFixtureType/{id}', array('as' => 'parts/deleteFixtureType','uses'=>'PartsController@deleteFixtureType'));
        Route::post('parts/destroyEFix/{id}', array('as' => 'parts/destroyEFix','uses'=>'PartsController@destroyEFix'));
        Route::get('ajax/switchCompStatus', array('uses'=>'PartsController@switchCompStatus'));  
        Route::get('ajax/updateFixType', array('uses'=>'PartsController@updateFixType'));  
        Route::get('ajax/switchArchStatus', array('uses'=>'PartsController@switchArchStatus'));  
        Route::get('ajax/updateCreateRebate', array('uses'=>'PartsController@updateCreateRebate'));  
        Route::Resource('parts','PartsController');
		/*
		* TaskController
		*/
        Route::get('task/assigned_task', array('as' => 'task/assigned_task','uses'=>'TaskController@assignedTask'));
        Route::post('task/assigned_task', array('as' => 'task/assigned_task','uses'=>'TaskController@assignedTask'));
        Route::get('task/own_task', array('as' => 'task/own_task','uses'=>'TaskController@ownedTask'));
        Route::post('task/own_task', array('as' => 'task/own_task','uses'=>'TaskController@ownedTask'));
        Route::get('task/admin_followup', array('as' => 'task/admin_followup','uses'=>'TaskController@adminFollowup'));
        Route::get('task/user_followup', array('as' => 'task/user_followup','uses'=>'TaskController@userFollowup'));
        Route::get('task/index', array('as' => 'task/index','uses'=>'TaskController@index'));
        Route::post('task/index', array('as' => 'task/index','uses'=>'TaskController@index'));
        Route::get('ajax/getModalInfo', array('uses'=>'TaskController@getModalInfo'));
        Route::post('task/updateInfoModal', array('as' => 'task/updateInfoModal','uses'=>'TaskController@updateInfoModal'));      
        Route::post('task/updateFollowUp', array('as' => 'task/updateFollowUp','uses'=>'TaskController@updateFollowUp'));      		
        Route::get('ajax/getFollowupList', array('uses'=>'TaskController@getFollowupList'));
        Route::post('task/answerFollowUp', array('as' => 'task/answerFollowUp','uses'=>'TaskController@answerFollowUp'));      				
        Route::post('task/deleteAssignedTask/{id}', array('as' => 'task/deleteAssignedTask','uses'=>'TaskController@deleteAssignedTask'));      				
        Route::post('task/deleteOwnedTask/{id}', array('as' => 'task/deleteOwnedTask','uses'=>'TaskController@deleteOwnedTask'));      				
        Route::post('task/deleteFollowUp/{id}', array('as' => 'task/deleteFollowUp','uses'=>'TaskController@deleteFollowUp'));      				
		Route::Resource('task','TaskController');

		/*
		ExpenseController
		*/
        Route::get('expense/index', array('as' => 'expense/index','uses'=>'ExpenseController@index'));
        Route::get('expense/current_expense', array('as' => 'expense/current_expense','uses'=>'ExpenseController@currentExpense'));
        Route::post('expense/current_expense', array('as' => 'expense/current_expense','uses'=>'ExpenseController@currentExpense'));
        Route::get('expense/expense_history', array('as' => 'expense/expense_history','uses'=>'ExpenseController@expenseHistory'));
        Route::post('expense/expense_history', array('as' => 'expense/expense_history','uses'=>'ExpenseController@expenseHistory'));
        Route::get('expense/expense_amt_imp_opt', array('as' => 'expense/expense_amt_imp_opt','uses'=>'ExpenseController@expenseAmtImpOpt'));
        Route::post('expense/expense_amt_imp_opt', array('as' => 'expense/expense_amt_imp_opt','uses'=>'ExpenseController@expenseAmtImpOpt'));
        Route::get('expense/glcode_expense_index', array('as' => 'expense/glcode_expense_index','uses'=>'ExpenseController@glcodeExpenseIndex'));
        Route::post('expense/glcode_expense_index', array('as' => 'expense/glcode_expense_index','uses'=>'ExpenseController@glcodeExpenseIndex'));
        Route::get('expense/index', array('as' => 'expense/index','uses'=>'ExpenseController@index'));
        Route::post('expense/deleteCurrentExp/{id}', array('as' => 'expense/deleteCurrentExp','uses'=>'ExpenseController@deleteCurrentExp'));      				
        Route::post('expense/deleteExpHistory/{id}', array('as' => 'expense/deleteExpHistory','uses'=>'ExpenseController@deleteExpHistory'));      				
        Route::post('expense/deleteGlCodeExpense/{id}', array('as' => 'expense/deleteGlCodeExpense','uses'=>'ExpenseController@deleteGlCodeExpense'));      				
        Route::get('expense/excelGLExpExport', array('as' => 'expense/excelGLExpExport','uses'=>'ExpenseController@excelGLExpExport'));
		Route::Resource('expense','ExpenseController');

		/*
		* SettingsController
		*/
        Route::get('settings/index', array('as' => 'settings/index','uses'=>'SettingsController@index'));
        Route::post('settings/index', array('as' => 'settings/index','uses'=>'SettingsController@index'));
        Route::get('settings/cview', array('as' => 'settings/cview','uses'=>'SettingsController@countryList'));
        Route::get('settings/kw_matrix', array('as' => 'settings/kw_matrix','uses'=>'SettingsController@kwMatrix'));
        Route::get('ajax/updateKWMatrix', array('uses'=>'SettingsController@updateKWMatrix'));
		Route::Resource('settings','SettingsController');
		/*
        QCReportsController
        */
        Route::get('qc_reports/service_job_check', array('as' => 'qc_reports/service_job_check','uses'=>'QCReportsController@serviceJobCheck'));
        Route::post('qc_reports/service_job_check', array('as' => 'qc_reports/service_job_check','uses'=>'QCReportsController@serviceJobCheck'));
        Route::get('qc_reports/sales_tax_exception_report', array('as' => 'qc_reports/sales_tax_exception_report','uses'=>'QCReportsController@salesTaxExceptionReport'));
        Route::post('qc_reports/sales_tax_exception_report', array('as' => 'qc_reports/sales_tax_exception_report','uses'=>'QCReportsController@salesTaxExceptionReport'));
        Route::get('qc_reports/excelSalesTaxExcpExport', array('as' => 'qc_reports/excelSalesTaxExcpExport','uses'=>'QCReportsController@excelSalesTaxExcpExport'));
        Route::get('qc_reports/wrong_prevailing_jobs_report', array('as' => 'qc_reports/wrong_prevailing_jobs_report','uses'=>'QCReportsController@wrongPrevailingJobsReport'));
        Route::post('qc_reports/wrong_prevailing_jobs_report', array('as' => 'qc_reports/wrong_prevailing_jobs_report','uses'=>'QCReportsController@wrongPrevailingJobsReport'));
        Route::get('qc_reports/excelWrongPrevJobExport', array('as' => 'qc_reports/excelWrongPrevJobExport','uses'=>'QCReportsController@excelWrongPrevJobExport'));
        Route::get('qc_reports/projected_margin_report', array('as' => 'qc_reports/projected_margin_report','uses'=>'QCReportsController@projectedMarginReport'));
        Route::post('qc_reports/projected_margin_report', array('as' => 'qc_reports/projected_margin_report','uses'=>'QCReportsController@projectedMarginReport'));
        Route::get('qc_reports/excelProjMarjinRepExport', array('as' => 'qc_reports/excelProjMarjinRepExport','uses'=>'QCReportsController@excelProjMarjinRepExport'));
        Route::get('qc_reports/job_exception_report', array('as' => 'qc_reports/job_exception_report','uses'=>'QCReportsController@jobExceptionReport'));
        Route::post('qc_reports/job_exception_report', array('as' => 'qc_reports/job_exception_report','uses'=>'QCReportsController@jobExceptionReport'));
        Route::get('ajax/getDetailsInfo', array('uses'=>'QCReportsController@getDetailsInfo'));
        Route::get('qc_reports/excelDupServJobExport', array('as' => 'qc_reports/excelDupServJobExport','uses'=>'QCReportsController@excelDupServJobExport'));
        Route::get('qc_reports/customer_job_report', array('as' => 'qc_reports/customer_job_report','uses'=>'QCReportsController@customerJobReport'));
        Route::post('qc_reports/customer_job_report', array('as' => 'qc_reports/customer_job_report','uses'=>'QCReportsController@customerJobReport'));
        Route::get('qc_reports/excelCustomerJobRepExport', array('as' => 'qc_reports/excelCustomerJobRepExport','uses'=>'QCReportsController@excelCustomerJobRepExport'));
        Route::get('qc_reports/pro_fixtures_usage_freq', array('as' => 'qc_reports/pro_fixtures_usage_freq','uses'=>'QCReportsController@proFixturesUsageFreq'));
        Route::post('qc_reports/pro_fixtures_usage_freq', array('as' => 'qc_reports/pro_fixtures_usage_freq','uses'=>'QCReportsController@proFixturesUsageFreq'));
        Route::get('qc_reports/excelProFixtureUsageExport', array('as' => 'qc_reports/excelProFixtureUsageExport','uses'=>'QCReportsController@excelProFixtureUsageExport'));
        Route::get('qc_reports/engine_kw_pricing', array('as' => 'qc_reports/engine_kw_pricing','uses'=>'QCReportsController@engineKwPricing'));
        Route::post('qc_reports/engine_kw_pricing', array('as' => 'qc_reports/engine_kw_pricing','uses'=>'QCReportsController@engineKwPricing'));
        Route::get('qc_reports/excelEngineKWPricingExport', array('as' => 'qc_reports/excelEngineKWPricingExport','uses'=>'QCReportsController@excelEngineKWPricingExport'));
        
        Route::get('qc_reports/customer_job_detail_report', array('as' => 'qc_reports/customer_job_detail_report','uses'=>'QCReportsController@customerJobDetailReport'));
        Route::post('qc_reports/customer_job_detail_report', array('as' => 'qc_reports/customer_job_detail_report','uses'=>'QCReportsController@customerJobDetailReport'));
        Route::get('qc_reports/excelCustJobDetailRepExport', array('as' => 'qc_reports/excelCustJobDetailRepExport','uses'=>'QCReportsController@excelCustJobDetailRepExport'));
        Route::get('qc_reports/over_head_budgeting', array('as' => 'qc_reports/over_head_budgeting','uses'=>'QCReportsController@overHeadBudgeting'));
        Route::post('qc_reports/over_head_budgeting', array('as' => 'qc_reports/over_head_budgeting','uses'=>'QCReportsController@overHeadBudgeting'));
        Route::get('qc_reports/over_head_budgeting_report', array('as' => 'qc_reports/over_head_budgeting_report','uses'=>'QCReportsController@overHeadBudgetingReport'));
        Route::post('qc_reports/over_head_budgeting_report', array('as' => 'qc_reports/over_head_budgeting_report','uses'=>'QCReportsController@overHeadBudgetingReport'));
        Route::get('ajax/getOHBudgetInfo', array('uses'=>'QCReportsController@getOHBudgetInfo'));
        Route::get('ajax/getGLCodeData', array('uses'=>'QCReportsController@getGLCodeData'));
        Route::get('ajax/updateGLCodeData', array('uses'=>'QCReportsController@updateGLCodeData'));
        Route::get('qc_reports/glDetailReportExport', array('as' => 'qc_reports/glDetailReportExport','uses'=>'QCReportsController@glDetailReportExport'));
        Route::get('qc_reports/glDetailAccountNosExport', array('as' => 'qc_reports/glDetailAccountNosExport','uses'=>'QCReportsController@glDetailAccountNosExport'));
        Route::get('qc_reports/ohBudgetingReportSimpleExport', array('as' => 'qc_reports/ohBudgetingReportSimpleExport','uses'=>'QCReportsController@ohBudgetingReportSimpleExport'));
        Route::get('qc_reports/ohBudgetingReportParentChildExport', array('as' => 'qc_reports/ohBudgetingReportParentChildExport','uses'=>'QCReportsController@ohBudgetingReportParentChildExport'));
        Route::get('qc_reports/companyModelingExport', array('as' => 'qc_reports/companyModelingExport','uses'=>'QCReportsController@companyModelingExport'));
        Route::get('qc_reports/billable_hours_report', array('as' => 'qc_reports/billable_hours_report','uses'=>'QCReportsController@billableHoursReport'));
        Route::post('qc_reports/billable_hours_report', array('as' => 'qc_reports/billable_hours_report','uses'=>'QCReportsController@billableHoursReport'));
        Route::get('qc_reports/excelBillableHoursExport', array('as' => 'qc_reports/excelBillableHoursExport','uses'=>'QCReportsController@excelBillableHoursExport'));
        Route::get('qc_reports/parts_costs_check', array('as' => 'qc_reports/parts_costs_check','uses'=>'QCReportsController@partsCostsCheck'));
        Route::post('qc_reports/parts_costs_check', array('as' => 'qc_reports/parts_costs_check','uses'=>'QCReportsController@partsCostsCheck'));
        Route::get('qc_reports/excelPartsCostsExport', array('as' => 'qc_reports/excelPartsCostsExport','uses'=>'QCReportsController@excelPartsCostsExport'));
        Route::get('qc_reports/customer_contact_detail_report', array('as' => 'qc_reports/customer_contact_detail_report','uses'=>'QCReportsController@customerContactDetailReport'));
        Route::post('qc_reports/customer_contact_detail_report', array('as' => 'qc_reports/customer_contact_detail_report','uses'=>'QCReportsController@customerContactDetailReport'));
        Route::get('qc_reports/excelCustContactDetailRepExport', array('as' => 'qc_reports/excelCustContactDetailRepExport','uses'=>'QCReportsController@excelCustContactDetailRepExport'));
        Route::get('qc_reports/employee_payable_amount_report', array('as' => 'qc_reports/employee_payable_amount_report','uses'=>'QCReportsController@employeePayableAmountReport'));
        Route::post('qc_reports/employee_payable_amount_report', array('as' => 'qc_reports/employee_payable_amount_report','uses'=>'QCReportsController@employeePayableAmountReport'));
        Route::get('ajax/getEmpPayableAmtInfo', array('uses'=>'QCReportsController@getEmpPayableAmtInfo'));
        Route::get('qc_reports/excelEmpPayableAmtRepExport', array('as' => 'qc_reports/excelEmpPayableAmtRepExport','uses'=>'QCReportsController@excelEmpPayableAmtRepExport'));
        Route::get('qc_reports/customer_contact_info', array('as' => 'qc_reports/customer_contact_info','uses'=>'QCReportsController@customerContactInfo'));
        Route::post('qc_reports/customer_contact_info', array('as' => 'qc_reports/customer_contact_info','uses'=>'QCReportsController@customerContactInfo'));
        Route::get('qc_reports/excelCustContactInfoRepExport', array('as' => 'qc_reports/excelCustContactInfoRepExport','uses'=>'QCReportsController@excelCustContactInfoRepExport'));
        Route::get('qc_reports/missing_jobs_info', array('as' => 'qc_reports/missing_jobs_info','uses'=>'QCReportsController@missingJobsInfo'));
        Route::post('qc_reports/missing_jobs_info', array('as' => 'qc_reports/missing_jobs_info','uses'=>'QCReportsController@missingJobsInfo'));
        Route::get('qc_reports/excelMissingJobsInfoRepExport', array('as' => 'qc_reports/excelMissingJobsInfoRepExport','uses'=>'QCReportsController@excelMissingJobsInfoRepExport'));
        Route::get('qc_reports/mapping_export', array('as' => 'qc_reports/mapping_export','uses'=>'QCReportsController@mappingExport'));
        Route::post('qc_reports/mapping_export', array('as' => 'qc_reports/mapping_export','uses'=>'QCReportsController@mappingExport'));
        Route::get('qc_reports/mappingExportResult', array('as' => 'qc_reports/mappingExportResult','uses'=>'QCReportsController@mappingExportResult'));
        Route::get('qc_reports/quickbook_analyzer', array('as' => 'qc_reports/quickbook_analyzer','uses'=>'QCReportsController@quickbookAnalyzer'));
        Route::post('qc_reports/quickbook_analyzer', array('as' => 'qc_reports/quickbook_analyzer','uses'=>'QCReportsController@quickbookAnalyzer'));
        Route::get('qc_reports/contract_profitability_report', array('as' => 'qc_reports/contract_profitability_report','uses'=>'QCReportsController@contractProfitabilityReport'));
        Route::post('qc_reports/contract_profitability_report', array('as' => 'qc_reports/contract_profitability_report','uses'=>'QCReportsController@contractProfitabilityReport'));
        Route::get('qc_reports/contractProfitablityExport', array('as' => 'qc_reports/contractProfitablityExport','uses'=>'QCReportsController@contractProfitablityExport'));
        Route::get('qc_reports/proj_activity_report', array('as' => 'qc_reports/proj_activity_report','uses'=>'QCReportsController@projActivityReport'));
        Route::post('qc_reports/proj_activity_report', array('as' => 'qc_reports/proj_activity_report','uses'=>'QCReportsController@projActivityReport'));

        /*
		ReportsController
		*/
        Route::get('reports/opt', array('as' => 'reports/opt','uses'=>'ReportsController@generateReport'));
        Route::post('reports/opt', array('as' => 'reports/opt','uses'=>'ReportsController@generateReport'));
        Route::get('reports/excelCostDataExport', array('uses' => 'ReportsController@excelCostDataExport', 'as' => 'reports/excelCostDataExport'));
        Route::get('reports/detailed_financial_report_summary', array('uses' => 'ReportsController@detailedFinancialReportSummary', 'as' => 'reports/detailed_financial_report_summary'));
		Route::post('reports/detailed_financial_report_summary', array('uses' => 'ReportsController@detailedFinancialReportSummary', 'as' => 'reports/detailed_financial_report_summary'));
        Route::get('reports/excelDFRSExport', array('uses' => 'ReportsController@excelDFRSExport', 'as' => 'reports/excelDFRSExport'));
        Route::get('reports/tc_jobs_report', array('uses' => 'ReportsController@tcJobsReport', 'as' => 'reports/tc_jobs_report'));
        Route::post('reports/tc_jobs_report', array('uses' => 'ReportsController@tcJobsReport', 'as' => 'reports/tc_jobs_report'));
        Route::get('reports/excelTCJReportExport', array('uses' => 'ReportsController@excelTCJReportExport', 'as' => 'reports/excelTCJReportExport'));
        Route::get('reports/service_job_fsw_report', array('uses' => 'ReportsController@serviceJobFSWReport', 'as' => 'reports/service_job_fsw_report'));
        Route::post('reports/service_job_fsw_report', array('uses' => 'ReportsController@serviceJobFSWReport', 'as' => 'reports/service_job_fsw_report'));
        Route::get('reports/servJobFSWRepExport', array('uses' => 'ReportsController@servJobFSWRepExport', 'as' => 'reports/servJobFSWRepExport'));
        Route::get('reports/job_regarding_report', array('uses' => 'ReportsController@jobRegardingReport', 'as' => 'reports/job_regarding_report'));
        Route::post('reports/job_regarding_report', array('uses' => 'ReportsController@jobRegardingReport', 'as' => 'reports/job_regarding_report'));
        Route::get('reports/excelRegardingReportExport', array('uses' => 'ReportsController@excelRegardingReportExport', 'as' => 'reports/excelRegardingReportExport'));
        Route::get('reports/wip_report', array('uses' => 'ReportsController@wipReport', 'as' => 'reports/wip_report'));
        Route::post('reports/wip_report', array('uses' => 'ReportsController@wipReport', 'as' => 'reports/wip_report'));
        Route::get('reports/excelWIPReportExport', array('uses' => 'ReportsController@excelWIPReportExport', 'as' => 'reports/excelWIPReportExport'));
        Route::get('reports/contract_comparison_report', array('uses' => 'ReportsController@contractComparisonReport', 'as' => 'reports/contract_comparison_report'));
        Route::post('reports/contract_comparison_report', array('uses' => 'ReportsController@contractComparisonReport', 'as' => 'reports/contract_comparison_report'));
        Route::get('reports/excelCCRExport', array('uses' => 'ReportsController@excelCCRExport', 'as' => 'reports/excelCCRExport'));       
        Route::get('reports/completed_service_jobs_report', array('uses' => 'ReportsController@completedServiceJobsReport', 'as' => 'reports/completed_service_jobs_report'));       
        Route::post('reports/completed_service_jobs_report', array('uses' => 'ReportsController@completedServiceJobsReport', 'as' => 'reports/completed_service_jobs_report'));       
        Route::get('reports/excelCSJReportExport', array('uses' => 'ReportsController@excelCSJReportExport', 'as' => 'reports/excelCSJReportExport'));       
        Route::get('reports/invoice_amt_report', array('uses' => 'ReportsController@invoiceAmtReport', 'as' => 'reports/invoice_amt_report'));       
        Route::post('reports/invoice_amt_report', array('uses' => 'ReportsController@invoiceAmtReport', 'as' => 'reports/invoice_amt_report'));       
        Route::get('reports/exceInvoiceAmtRepExport', array('uses' => 'ReportsController@exceInvoiceAmtRepExport', 'as' => 'reports/exceInvoiceAmtRepExport'));       
        Route::get('reports/financial_report_summary', array('uses' => 'ReportsController@financialReportSummary', 'as' => 'reports/financial_report_summary'));       
        Route::post('reports/financial_report_summary', array('uses' => 'ReportsController@financialReportSummary', 'as' => 'reports/financial_report_summary'));       
        Route::get('reports/excelFinRepSumryExport', array('uses' => 'ReportsController@excelFinRepSumryExport', 'as' => 'reports/excelFinRepSumryExport'));       
        Route::get('reports/cost_duplication_report', array('uses' => 'ReportsController@costDuplicationReport', 'as' => 'reports/cost_duplication_report'));       
        Route::post('reports/cost_duplication_report', array('uses' => 'ReportsController@costDuplicationReport', 'as' => 'reports/cost_duplication_report'));       
        Route::post('reports/deleteCostDuplic/{id}', array('uses' => 'ReportsController@deleteCostDuplic', 'as' => 'reports/deleteCostDuplic'));       
        Route::get('reports/excelCostDuplicReportExport', array('uses' => 'ReportsController@excelCostDuplicReportExport', 'as' => 'reports/excelCostDuplicReportExport'));              
        Route::get('reports/job_time_report', array('uses' => 'ReportsController@jobTimeReport', 'as' => 'reports/job_time_report'));              
        Route::post('reports/job_time_report', array('uses' => 'ReportsController@jobTimeReport', 'as' => 'reports/job_time_report'));              
        Route::get('reports/missing_hour_report', array('uses' => 'ReportsController@missingHoursReport', 'as' => 'reports/missing_hour_report'));              
        Route::post('reports/missing_hour_report', array('uses' => 'ReportsController@missingHoursReport', 'as' => 'reports/missing_hour_report'));              
        Route::get('reports/excelMissingHourReportExport', array('uses' => 'ReportsController@excelMissingHourReportExport', 'as' => 'reports/excelMissingHourReportExport'));              
        Route::get('reports/excelExportJobTimeRep', array('uses' => 'ReportsController@excelExportJobTimeRep', 'as' => 'reports/excelExportJobTimeRep'));              
        Route::get('reports/gen_service_jobs_report', array('uses' => 'ReportsController@genServiceJobsReport', 'as' => 'reports/gen_service_jobs_report'));              
        Route::post('reports/gen_service_jobs_report', array('uses' => 'ReportsController@genServiceJobsReport', 'as' => 'reports/gen_service_jobs_report'));              
        Route::get('reports/serviceJobMultipleExport', array('uses' => 'ReportsController@serviceJobMultipleExport', 'as' => 'reports/serviceJobMultipleExport'));               
        Route::get('reports/pay_reportopt', array('uses' => 'ReportsController@payrollReport', 'as' => 'reports/pay_reportopt'));               
        Route::post('reports/pay_reportopt', array('uses' => 'ReportsController@payrollReport', 'as' => 'reports/pay_reportopt'));               
        Route::get('reports/excelPayRollExport', array('uses' => 'ReportsController@excelPayRollExport', 'as' => 'reports/excelPayRollExport'));               
        Route::get('reports/emp_reportopt', array('uses' => 'ReportsController@employeeReport', 'as' => 'reports/emp_reportopt'));               
        Route::post('reports/emp_reportopt', array('uses' => 'ReportsController@employeeReport', 'as' => 'reports/emp_reportopt'));               
        Route::get('reports/excelEmpRepExport', array('uses' => 'ReportsController@excelEmpRepExport', 'as' => 'reports/excelEmpRepExport'));               
        Route::get('reports/activity_report', array('uses' => 'ReportsController@activityReport', 'as' => 'reports/activity_report'));               
        Route::post('reports/activity_report', array('uses' => 'ReportsController@activityReport', 'as' => 'reports/activity_report'));               
        Route::get('reports/modeling_reportopt', array('uses' => 'ReportsController@compModelingReport', 'as' => 'reports/modeling_reportopt'));               
        Route::post('reports/modeling_reportopt', array('uses' => 'ReportsController@compModelingReport', 'as' => 'reports/modeling_reportopt'));               
        Route::get('reports/excelCompModelingExport', array('uses' => 'ReportsController@excelCompModelingExport', 'as' => 'reports/excelCompModelingExport'));               
        Route::get('reports/gen_reportopt', array('uses' => 'ReportsController@generalReport', 'as' => 'reports/gen_reportopt'));               
        Route::post('reports/gen_reportopt', array('uses' => 'ReportsController@generalReport', 'as' => 'reports/gen_reportopt'));               
        Route::get('reports/excelGenRepExport', array('uses' => 'ReportsController@excelGenRepExport', 'as' => 'reports/excelGenRepExport'));               
        Route::get('reports/gen_reportsummary', array('uses' => 'ReportsController@generalReportSummary', 'as' => 'reports/gen_reportsummary'));               
        Route::post('reports/gen_reportsummary', array('uses' => 'ReportsController@generalReportSummary', 'as' => 'reports/gen_reportsummary'));               
        Route::get('reports/excelGenRepSummaryExport', array('uses' => 'ReportsController@excelGenRepSummaryExport', 'as' => 'reports/excelGenRepSummaryExport'));                      
        Route::Resource('reports','ReportsController');
        /*
		UploaderController
		*/
		Route::get('uploader/index', array('as' => 'uploader/index','uses'=>'UploaderController@index'));
		Route::post('uploader/index', array('as' => 'uploader/index','uses'=>'UploaderController@index'));
		Route::Resource('uploader','UploaderController');

		/*
		* Customers CustomerController
		*/
        Route::get('ajax/getAjaxJobDetailHTML', array('uses'=>'CustomerController@getAjaxJobDetailHTML'));
        Route::get('customers/create', array('uses'=>'CustomerController@create'));
 		Route::post('customers/create', array('as' => 'customers/create', 'uses' => 'CustomerController@create'));        
        Route::post('customers/index', array('uses'=>'CustomerController@index'));
        Route::get('customers/index', array('uses'=>'CustomerController@index'));
        Route::post('customers/manageProperty', array('uses'=>'CustomerController@manageProperty'));
        Route::get('customers/manageProperty', array('uses'=>'CustomerController@manageProperty'));
        Route::post('ajax/getAjaxAreaAsset', array('uses'=>'CustomerController@getAjaxAreaAsset'));
        Route::post('ajax/setAjaxLocationAreaAsset', array('uses'=>'CustomerController@setAjaxLocationAreaAsset'));
        Route::post('ajax/deleteAjaxAreaAsset', array('uses'=>'CustomerController@deleteAjaxAreaAsset'));       
        Route::Resource('customers','CustomerController');
        /*---------------- Customer-----------------------------------*/    
        
       /*---------------- Employees-----------------------------------         
	   * Employees EmployeesController
	   */
        Route::get('employees/create', array('uses'=>'EmployeeController@create'));
		Route::post('employees/create', array('as' => 'employees/create', 'uses' => 'EmployeeController@create'));
        Route::get('employees/index', array('uses'=>'EmployeeController@index'));
        Route::post('employees/index', array('uses'=>'EmployeeController@index'));
        Route::post('employees/listEmployeeTypes', array('uses'=>'EmployeeController@listEmployeeTypes'));
        Route::get('employees/listEmployeeTypes', array('uses'=>'EmployeeController@listEmployeeTypes'));
        Route::post('employees/addNewEmployee', array('as' => 'employees/addNewEmployee','uses'=>'EmployeeController@addNewEmployee'));
        Route::get('employees/addNewEmployee', array('as' => 'employees/addNewEmployee','uses'=>'EmployeeController@addNewEmployee'));
        Route::post('employees/editEmployee', array('as' => 'employees/editEmployee','uses'=>'EmployeeController@editEmployee'));
        Route::get('employees/editEmployee', array('as' => 'employees/editEmployee','uses'=>'EmployeeController@editEmployee'));
        Route::post('ajax/employees/getDeductionsHTML', array('as' => 'ajax/employees/getDeductionsHTML','uses'=>'EmployeeController@manageDeductions'));
        Route::get('ajax/employees/getDeductionsHTML', array('as' => 'ajax/employees/getDeductionsHTML','uses'=>'EmployeeController@manageDeductions'));
        Route::post('ajax/employees/changeEmployeePassword', array('as' => 'ajax/employees/changeEmployeePassword','uses'=>'EmployeeController@changeEmployeePassword'));
        Route::get('ajax/employees/changeEmployeePassword', array('as' => 'ajax/employees/changeEmployeePassword','uses'=>'EmployeeController@changeEmployeePassword'));
        Route::post('ajax/employees/setBurden', array('as' => 'ajax/employees/setBurden','uses'=>'EmployeeController@setEmployeeBurden'));
        Route::get('ajax/employees/setBurden', array('as' => 'ajax/employees/setBurden','uses'=>'EmployeeController@setEmployeeBurden'));
        Route::post('ajax/employees/setCommission', array('as' => 'ajax/employees/setCommission','uses'=>'EmployeeController@setEmployeeCommission'));
        Route::get('ajax/employees/setCommission', array('as' => 'ajax/employees/setCommission','uses'=>'EmployeeController@setEmployeeCommission'));
        Route::post('ajax/employees/setRate', array('as' => 'ajax/employees/setRate','uses'=>'EmployeeController@setEmployeeRate'));
        Route::get('ajax/employees/setRate', array('as' => 'ajax/employees/setRate','uses'=>'EmployeeController@setEmployeeRate'));
        Route::post('employees/viewRecord', array('as' => 'employees/viewRecord','uses'=>'EmployeeController@viewEmployeeRecord'));
        Route::get('employees/viewRecord', array('as' => 'employees/viewRecord','uses'=>'EmployeeController@viewEmployeeRecord'));
        Route::post('employees/deleteEmployee', array('as' => 'employees/deleteEmployee','uses'=>'EmployeeController@deleteEmployee'));
        Route::get('employees/deleteEmployee', array('as' => 'employees/deleteEmployee','uses'=>'EmployeeController@deleteEmployee'));
        Route::post('employees/wageLogList', array('as' => 'employees/wageLogList','uses'=>'EmployeeController@employeeWageList'));
        Route::get('employees/wageLogList', array('as' => 'employees/wageLogList','uses'=>'EmployeeController@employeeWageList'));
        Route::post('employees/deleteWageRate', array('as' => 'employees/deleteWageRate','uses'=>'EmployeeController@deleteWageRate'));
        Route::get('employees/deleteWageRate', array('as' => 'employees/deleteWageRate','uses'=>'EmployeeController@deleteWageRate'));
        Route::post('employees/wageCommissionList', array('as' => 'employees/wageCommissionList','uses'=>'EmployeeController@employeeCommissionList'));
        Route::get('employees/wageCommissionList', array('as' => 'employees/wageCommissionList','uses'=>'EmployeeController@employeeCommissionList'));
        Route::post('employees/deleteWageCommission', array('as' => 'employees/deleteWageCommission','uses'=>'EmployeeController@deleteWageCommission'));
        Route::get('employees/deleteWageCommission', array('as' => 'employees/deleteWageCommission','uses'=>'EmployeeController@deleteWageCommission'));
        Route::post('employees/burdunList', array('as' => 'employees/burdunList','uses'=>'EmployeeController@employeeBurdunList'));
        Route::get('employees/burdunList', array('as' => 'employees/burdunList','uses'=>'EmployeeController@employeeBurdunList'));
        Route::post('employees/deleteBurden', array('as' => 'employees/deleteBurden','uses'=>'EmployeeController@deleteBurden'));
        Route::get('employees/deleteBurden', array('as' => 'employees/deleteBurden','uses'=>'EmployeeController@deleteBurden'));
        Route::post('employees/jobHistoryList/{id}', array('as' => 'employees/jobHistoryList','uses'=>'EmployeeController@employeeJobHistoryList'));
        Route::get('employees/jobHistoryList/{id}', array('as' => 'employees/jobHistoryList','uses'=>'EmployeeController@employeeJobHistoryList'));
        Route::Resource('employees','EmployeeController'); 
        /*---------------- Employees End-----------------------------------*/

        /*
		 * Vendors vendorsController
 		*/
        Route::get('ajax/getAjaxPermissionHTML', array('uses'=>'VendorController@getAjaxPermissionHTML'));
        Route::get('vendors/create', array('uses'=>'VendorController@create'));
 		Route::post('vendors/create', array('as' => 'vendors/create', 'uses' => 'VendorController@create'));
        Route::post('vendors/index', array('uses'=>'VendorController@index'));
        Route::get('vendors/index', array('uses'=>'VendorController@index'));
        Route::post('vendors/updateVendorPermissions', array('as' => 'vendors/updateVendorPermissions','uses'=>'VendorController@updateVendorPermissions'));
        Route::get('vendors/updateVendorPermissions', array('as' => 'vendors/updateVendorPermissions','uses'=>'VendorController@updateVendorPermissions')); 
        Route::Resource('vendors','VendorController');
        /*---------------- Vendors-----------------------------------*/

        /*
		EmailsController
        */
        Route::get('emails/archive_emails', array('as' => 'emails/archive_emails','uses'=>'EmailsController@archiveEmails')); 
        Route::post('emails/archive_emails', array('as' => 'emails/archive_emails','uses'=>'EmailsController@archiveEmails')); 
        Route::get('emails/blocklist', array('as' => 'emails/blocklist','uses'=>'EmailsController@eblockList')); 
        Route::post('emails/blocklist', array('as' => 'emails/blocklist','uses'=>'EmailsController@eblockList')); 
        Route::get('emails/index', array('as' => 'emails/index','uses'=>'EmailsController@index')); 
        Route::post('emails/index', array('as' => 'emails/index','uses'=>'EmailsController@index')); 
        Route::get('ajax/getEmailDetails', array('uses'=>'EmailsController@getEmailDetails'));
        Route::Resource('emails','EmailsController');

	/* ------
	Time sheet Controller Methods
	*/
	Route::get('checkEditable', 'TimeSheetController@checkEditable');
	Route::post('timesheet/updateTimeSheet', array('as' => 'timesheet.updateTimesheet', 'uses' => 'TimeSheetController@updateTimeSheet'));
	Route::get('search', array('as' => 'timesheet.search', 'uses' => 'TimeSheetController@search'));
	Route::post('search', array('as' => 'timesheet.search', 'uses' => 'TimeSheetController@search'));
	Route::get('ajax/getJobNumberAutocomplete', array('uses'=>'TimeSheetController@getJobNumberAutocomplete'));
	Route::get('ajax/setProjectTaskArrays', array('uses' => 'TimeSheetController@setProjectTaskArrays'));
	Route::post('checkEditable', array('as' => 'checkEditable', 'uses' => 'TimeSheetController@checkEditable'));
	Route::get('timesheet/emp_hist/{id}', array('as' => 'timesheet/emp_hist', 'uses' => 'TimeSheetController@empAttenHist'));
	Route::Resource('timesheet','TimeSheetController');
	
	/* -----
	EquipmentController
	*/
	Route::get('equipment/list_rental_company', array('as' => 'equipment/list_rental_company', 'uses' => 'EquipmentController@listRentalCompany'));
	Route::post('equipment/list_rental_company', array('as' => 'equipment/list_rental_company', 'uses' => 'EquipmentController@listRentalCompany'));
	Route::get('equipment/add_rental_company', array('as' => 'equipment/add_rental_company', 'uses' => 'EquipmentController@addRentalCompany'));
	Route::post('equipment/add_rental_company', array('as' => 'equipment/add_rental_company', 'uses' => 'EquipmentController@addRentalCompany'));
	Route::get('equipment/equipment_reading_log', array('as' => 'equipment/equipment_reading_log', 'uses' => 'EquipmentController@equipmentReadingLog'));
	Route::post('equipment/equipment_reading_log', array('as' => 'equipment/equipment_reading_log', 'uses' => 'EquipmentController@equipmentReadingLog'));
	Route::post('equipment/delEquipReadingLog/{id}', array('as' => 'equipment/delEquipReadingLog', 'uses' => 'EquipmentController@delEquipReadingLog'));	
	Route::post('equipment/deleteEquipment/{id}', array('as' => 'equipment/deleteEquipment', 'uses' => 'EquipmentController@deleteEquipment'));	
	Route::post('equipment/index', array('as' => 'equipment/index', 'uses' => 'EquipmentController@index'));	
	Route::post('equipment/saveNote', array('as' => 'equipment/saveNote', 'uses' => 'EquipmentController@saveNote'));	
	Route::post('equipment/saveReading', array('as' => 'equipment/saveReading', 'uses' => 'EquipmentController@saveReading'));	
	Route::Resource('equipment','EquipmentController');
	/*
	AssetController
	*/
	Route::get('asset/checkin_asset_equipment', array('as' => 'asset/checkin_asset_equipment', 'uses' => 'AssetController@checkInAssetEquipment'));
	Route::post('asset/checkin_asset_equipment', array('as' => 'asset/checkin_asset_equipment', 'uses' => 'AssetController@checkInAssetEquipment'));
	Route::get('asset/checkout_asset_equipment', array('as' => 'asset/checkout_asset_equipment', 'uses' => 'AssetController@checkoutAssetEquipment'));
	Route::post('asset/checkout_asset_equipment', array('as' => 'asset/checkout_asset_equipment', 'uses' => 'AssetController@checkoutAssetEquipment'));
	Route::get('asset/asset_equipment_type_index', array('as' => 'asset/asset_equipment_type_index', 'uses' => 'AssetController@assetEquipmentTypeManage'));
	Route::get('asset/add_asset_equipment_type', array('as' => 'asset/add_asset_equipment_type', 'uses' => 'AssetController@addAssetEquipmentType'));
	Route::post('asset/add_asset_equipment_type', array('as' => 'asset/add_asset_equipment_type', 'uses' => 'AssetController@addAssetEquipmentType'));
	Route::get('ajax/getAssetEquipHistory', array('uses'=>'AssetController@getAssetEquipHistory'));
	Route::post('asset/deleteAssetType/{id}', array('as' => 'asset/deleteAssetType', 'uses' => 'AssetController@deleteAssetType'));
	Route::get('asset/edit_asset_type/{id}', array('as' => 'asset/edit_asset_type', 'uses' => 'AssetController@editAssetType'));
	Route::post('asset/edit_asset_type/{id}', array('as' => 'asset/edit_asset_type', 'uses' => 'AssetController@editAssetType'));
	Route::get('ajax/getEquipHist', array('uses'=>'AssetController@getEquipHist'));
	Route::get('ajax/getEquipHealth', array('uses'=>'AssetController@getEquipHealth'));
	Route::Resource('asset','AssetController');

	/*
	InvoiceController
	*/
	Route::get('invoice/index', array('as' => 'invoice/index', 'uses' => 'InvoiceController@index'));	
	Route::post('invoice/index', array('as' => 'invoice/index', 'uses' => 'InvoiceController@index'));
	Route::post('invoice/postDateCompletion', array('as' => 'invoice/postDateCompletion', 'uses' => 'InvoiceController@postDateCompletion'));
	Route::get('ajax/delDateCompletion', array('uses'=>'InvoiceController@delDateCompletion'));
	Route::get('invoice/excelInvExport', array('uses' => 'InvoiceController@excelInvExport', 'as' => 'invoice/excelInvExport'));
	Route::get('invoice/getInvPdffile/{id}/{cid}', array('uses' => 'InvoiceController@getInvPdffile', 'as' => 'invoice/getInvPdffile'));
	Route::Resource('invoice','InvoiceController');
	/* -----
	GLCodeController
	*/
	Route::get('glcode/add_expense_glcode', array('as' => 'glcode/add_expense_glcode', 'uses' => 'GLCodeController@addExpenseGlcode'));
	Route::post('glcode/add_expense_glcode', array('as' => 'glcode/add_expense_glcode', 'uses' => 'GLCodeController@addExpenseGlcode'));
	Route::get('glcode/new_type', array('as' => 'glcode/new_type', 'uses' => 'GLCodeController@newGLcodetype'));
    Route::get('glcode/index', array('as' => 'glcode/index', 'uses' => 'GLCodeController@index')); 
	Route::post('glcode/index', array('as' => 'glcode/index', 'uses' => 'GLCodeController@index'));	
	Route::post('glcode/deleteGlCType/{id}', array('as' => 'glcode/deleteGlCType', 'uses' => 'GLCodeController@deleteGlCType'));	
	Route::get('ajax/updateGLCType', array('uses'=>'GLCodeController@updateGLCType'));
	Route::post('glcode/createGLCEType', array('as' => 'glcode/createGLCEType', 'uses' => 'GLCodeController@createGLCEType'));	
	Route::get('glcode/expense_glcode_index', array('as' => 'glcode/expense_glcode_index', 'uses' => 'GLCodeController@expenseGlcodeManage'));
	Route::post('glcode/expense_glcode_index', array('as' => 'glcode/expense_glcode_index', 'uses' => 'GLCodeController@expenseGlcodeManage'));
	Route::post('glcode/deleteExpenseGCode/{id}', array('as' => 'glcode/deleteExpenseGCode', 'uses' => 'GLCodeController@deleteExpenseGCode'));	
	Route::get('glcode/edit_expense_glcode/{id}', array('as' => 'glcode/edit_expense_glcode', 'uses' => 'GLCodeController@editExpenseGlcode'));
	Route::post('glcode/edit_expense_glcode/{id}', array('as' => 'glcode/edit_expense_glcode', 'uses' => 'GLCodeController@editExpenseGlcode'));
	Route::Resource('glcode','GLCodeController');
	/*
	PurchaseOrder
	*/
	Route::get('purchaseorder/index_recd', array('as' => 'purchaseorder/index_recd', 'uses' => 'PurchaseOrderController@indexRecd'));
	Route::post('purchaseorder/index_recd', array('as' => 'purchaseorder/index_recd', 'uses' => 'PurchaseOrderController@indexRecd'));
	Route::get('purchaseorder/delete_purchaseorder', array('as' => 'purchaseorder/delete_purchaseorder', 'uses' => 'PurchaseOrderController@deletedPurchaseOrders'));
	Route::get('purchaseorder/index', array('as' => 'purchaseorder/index', 'uses' => 'PurchaseOrderController@index'));
	Route::post('purchaseorder/index', array('as' => 'purchaseorder/index', 'uses' => 'PurchaseOrderController@index'));
	Route::post('purchaseorder/updatepoInfo', array('as' => 'purchaseorder/updatepoInfo', 'uses' => 'PurchaseOrderController@updatepoInfo'));
	Route::post('purchaseorder/managePOFiles', array('as' => 'purchaseorder/managePOFiles', 'uses' => 'PurchaseOrderController@managePOFiles'));
	Route::get('ajax/getPOFiles', array('uses'=>'PurchaseOrderController@getPOFiles'));
	Route::get('ajax/deletePOFile', array('uses'=>'PurchaseOrderController@deletePOFile'));
	Route::get('purchaseorder/excelPOExport', array('uses' => 'PurchaseOrderController@excelPOExport', 'as' => 'purchaseorder/excelPOExport'));
	Route::get('purchaseorder/excelRPOExport', array('uses' => 'PurchaseOrderController@excelRPOExport', 'as' => 'purchaseorder/excelRPOExport'));
	Route::get('purchaseorder/po_item_form/{id}', array('uses' => 'PurchaseOrderController@poItemForm', 'as' => 'purchaseorder/po_item_form'));
	Route::post('purchaseorder/po_item_form/{id}', array('uses' => 'PurchaseOrderController@poItemForm', 'as' => 'purchaseorder/po_item_form'));
	Route::get('ajax/getVendorInfo', array('uses'=>'PurchaseOrderController@getVendorInfo'));
	Route::post('purchaseorder/delPORow/{id}', array('uses' => 'PurchaseOrderController@delPORow', 'as' => 'purchaseorder/delPORow'));
	Route::post('purchaseorder/delPOHistRow/{id}', array('uses' => 'PurchaseOrderController@delPOHistRow', 'as' => 'purchaseorder/delPOHistRow'));
	Route::get('purchaseorder/getPOPdffile/{id}', array('as' => 'purchaseorder/getPOPdffile', 'uses' => 'PurchaseOrderController@getPOPdffile'));
	Route::get('purchaseorder/getPOPdfslip/{id}', array('as' => 'purchaseorder/getPOPdfslip', 'uses' => 'PurchaseOrderController@getPOPdfslip'));
	Route::get('ajax/delPOAmtDate', array('uses'=>'PurchaseOrderController@delPOAmtDate'));
	Route::get('ajax/getPOAmt', array('uses'=>'PurchaseOrderController@getPOAmt'));
	Route::post('purchaseorder/updatePOAmtDate', array('uses' => 'PurchaseOrderController@updatePOAmtDate', 'as' => 'purchaseorder/updatePOAmtDate'));
	Route::get('ajax/restorePO', array('uses'=>'PurchaseOrderController@restorePO'));
	Route::Resource('purchaseorder','PurchaseOrderController');
	/* ------
	Wages Controller Methods
	*/
	Route::get('ajax/validateJobNumber', array('uses'=>'WagesController@validateJobNumber'));
	Route::get('ajax/getContractNumberAutocomplete', array('uses'=>'WagesController@getContractNumberAutocomplete'));
	Route::get('ajax/testContractNumber', array('uses'=>'WagesController@testContractNumber'));
	Route::get('wages/setTimesheetWage', array('as' => 'wages/setTimesheetWage', 'uses' => 'WagesController@setTimesheetWage'));
	Route::get('wages/importWagesCounty', array('as' => 'wages/importWagesCounty', 'uses' => 'WagesController@importWagesCounty'));
	Route::get('wages/recalculateWages', array('as' => 'wages/recalculateWages', 'uses' => 'WagesController@recalculateWages'));
	Route::get('wages/insertUpdateCounties', array('as' => 'wages/insertUpdateCounties', 'uses' => 'WagesController@insertUpdateCounties'));
	Route::post('wages/insertUpdateCounties', array('as' => 'wages/insertUpdateCounties', 'uses' => 'WagesController@insertUpdateCounties'));
	Route::Resource('wages','WagesController');
	Route::get('search', array('as' => 'wages.search', 'uses' => 'WagesController@search'));
	Route::post('search', array('as' => 'wages.search', 'uses' => 'WagesController@search'));
	Route::post('recalculateWagesAuto', array('as' => 'wages.recalculateWagesAuto', 'uses' => 'WagesController@recalculateWagesAuto'));
	Route::post('updateWageInfo', array('as' => 'wages.updateWageInfo', 'uses' => 'WagesController@updateWageInfo'));
	Route::get('ajax/creatNewTasktype', array('uses' => 'WagesController@creatNewTasktype'));
	Route::get('ajax/createNewEmployeetype', array('uses' => 'WagesController@createNewEmployeetype'));
	Route::get('ajax/updateWagesAuto', array('uses' => 'WagesController@updateWagesAuto'));
	Route::get('get_dashboard_gen_sev_job_panel/{mon}/{yer}/{flag}/{selection}/{usage}',"MainController@dashboard_gen_sev_job_panel_data");
	
	//Route::get('dashboard_gen_sev_job_panel/{mon}/{yer}/{flag}', "MainController@get_dashboard_gen_sev_job_panel");
	/*
	* Holiday controller
	*/
	Route::get('ajax/updateHolidayInfo', array('uses'=>'HolidayController@updateHolidayInfo'));
	Route::get('ajax/updateStatus', array('uses'=>'HolidayController@updateStatus'));
	Route::post('holiday/updateBalance', array('as' => 'holiday/updateBalance', 'uses' => 'HolidayController@updateBalance'));
	Route::get('holiday/edit_vacation_sick', array('as' => 'holiday/edit_vacation_sick', 'uses' => 'HolidayController@editVacationSick'));
	Route::post('holiday/edit_vacation_sick', array('as' => 'holiday/edit_vacation_sick', 'uses' => 'HolidayController@editVacationSick'));
	Route::get('holiday/manage_leaves', array('as' => 'holiday/manage_leaves', 'uses' => 'HolidayController@manageLeaves'));
	Route::post('holiday/manage_leaves', array('as' => 'holiday/manage_leaves', 'uses' => 'HolidayController@manageLeaves'));
	Route::Resource('holiday','HolidayController');
	/*
	* Department Controller
	*/
	Route::post('department/manageDepartmentUsers/{id}', array('as' => 'department/manageDepartmentUsers', 'uses' => 'DepartmentController@manageDepartmentUsers'));
	Route::Resource('department','DepartmentController');
	/*
	* Electrical Job Controller
	*/
	Route::get('job/job_form/{id}/{j_num}', array('as' => 'job/job_form', 'uses' => 'JobController@jobForm'));
	Route::get('job/job_form_report/{id}/{j_num}/{type}/{viewby}', array('as' => 'job/job_form_report', 'uses' => 'JobController@jobFormReport'));
	Route::get('job/job_form_report_excel/{id}/{j_num}/{type}/{viewby}/{budget}', array('as' => 'job/job_form_report_excel', 'uses' => 'JobController@jobFormReportExcel'));
	Route::get('ajax/jobFormReportAjax', array('uses'=>'JobController@jobFormReportAjax'));
	Route::get('ajax/deleteFiles', array('uses'=>'JobController@deleteFiles'));
	Route::post('job/importExcelJobTasks', array('as' => 'job/importExcelJobTasks', 'uses' => 'JobController@importExcelJobTasks'));
	Route::get('job/elec_job_list', array('as' => 'job/elec_job_list', 'uses' => 'JobController@electricalJobList'));
	Route::post('job/elec_job_list', array('as' => 'job/elec_job_list', 'uses' => 'JobController@electricalJobList'));	
	/*
	* GrassIVY Job Actions
	*/
	Route::get('job/grassivyJobList', array('as' => 'job/grassivyJobList', 'uses' => 'JobController@electricalJobList'));
	Route::post('job/grassivyJobList', array('as' => 'job/grassivyJobList', 'uses' => 'JobController@electricalJobList'));
	Route::get('quote/grassivy_quote_list', array('as' => 'quote/grassivy_quote_list', 'uses' => 'JobQuoteController@electricalQuoteList'));
	Route::post('quote/grassivy_quote_list', array('as' => 'quote/grassivy_quote_list', 'uses' => 'JobQuoteController@electricalQuoteList'));
	Route::get('job/job_grassivy_equipment_pricing_frm/{id}/{j_num}', array('uses' => 'JobQuoteController@jobElectricalEquipForm', 'as' => 'quote/job_grassivy_equipment_pricing_frm'));
	/*
	* Special Project Job Actions
	*/
	Route::get('job/specialProjectJobList', array('as' => 'job/specialProjectJobList', 'uses' => 'JobController@electricalJobList'));
	Route::post('job/specialProjectJobList', array('as' => 'job/specialProjectJobList', 'uses' => 'JobController@electricalJobList'));
	Route::get('quote/specialproject_quote_list', array('as' => 'quote/specialproject_quote_list', 'uses' => 'JobQuoteController@electricalQuoteList'));
	Route::post('quote/specialproject_quote_list', array('as' => 'quote/specialproject_quote_list', 'uses' => 'JobQuoteController@electricalQuoteList'));
	Route::get('job/job_special_project_equipment_pricing_frm/{id}/{j_num}', array('uses' => 'JobQuoteController@jobElectricalEquipForm', 'as' => 'quote/job_special_project_equipment_pricing_frm'));

	/*
	* Shop Work Job Actions
	*/
	Route::get('job/shopWorkJobList', array('as' => 'job/shopWorkJobList', 'uses' => 'JobController@electricalJobList'));
	Route::post('job/shopWorkJobList', array('as' => 'job/shopWorkJobList', 'uses' => 'JobController@electricalJobList'));
	Route::get('quote/shop_work_quote_list', array('as' => 'quote/shop_work_quote_list', 'uses' => 'JobQuoteController@electricalQuoteList'));
	Route::post('quote/shop_work_quote_list', array('as' => 'quote/shop_work_quote_list', 'uses' => 'JobQuoteController@electricalQuoteList'));
	Route::get('job/shop_work_quote_frm/{id}/{j_num}', array('uses' => 'JobQuoteController@shopWorkQuoteForm', 'as' => 'quote/shop_work_quote_frm'));
	/*
	* Serrvice Job Actions
	*/
	Route::get('job/clear_service_jobs', array('as' => 'job/clear_service_jobs', 'uses' => 'JobController@insertUpdateClearServiceJobs'));
	Route::post('job/clear_service_jobs', array('as' => 'job/clear_service_jobs', 'uses' => 'JobController@insertUpdateClearServiceJobs'));
	Route::get('job/assign_technicians_imp', array('as' => 'job/assign_technicians_imp', 'uses' => 'JobController@assignTechniciansImport'));
	Route::post('job/assign_technicians_imp', array('as' => 'job/assign_technicians_imp', 'uses' => 'JobController@assignTechniciansImport'));
		

	/*
	* Jobs Actions
	*/
	Route::get('ajax/getInvoiceInfo', array('uses'=>'JobController@getInvoiceInfo'));
	Route::get('ajax/updateJobs', array('uses'=>'JobController@updateJobs'));
	Route::get('ajax/deleteJobs', array('uses'=>'JobController@deleteJobs'));
	Route::get('ajax/deleteSFW', array('uses'=>'JobController@deleteSFW'));
	Route::get('ajax/getFSWFiles', array('uses'=>'JobController@getFSWFiles'));
	Route::get('ajax/deleteFSWFiles', array('uses'=>'JobController@deleteFSWFiles'));	
	Route::get('ajax/deleteEquipHistory', array('uses'=>'JobController@deleteEquipHistory'));
	Route::get('ajax/getAreaAssetFields', array('uses'=>'JobController@getAreaAssetFields'));
	Route::get('ajax/getJobTaskInfo', array('uses'=>'JobController@getJobTaskInfo'));
	Route::get('ajax/getEmails', array('uses'=>'JobController@getEmails'));
	Route::get('ajax/displayJobTasks', array('uses'=>'JobController@displayJobTasks'));
	Route::get('ajax/createJobProjectTask', array('uses'=>'JobController@createJobProjectTask'));
	Route::post('job/createRFI', array('uses' => 'JobController@createRFI', 'as' => 'job/createRFI'));
	Route::post('job/destroyRFI/{id}/{cid}/{jid}/{jnum}', array('uses' => 'JobController@destroyRFI', 'as' => 'job/destroyRFI'));
	Route::post('job/completeRFI/{id}/{jid}/{jnum}', array('uses' => 'JobController@completeRFI', 'as' => 'job/completeRFI'));
	Route::post('job/createProjJob', array('uses' => 'JobController@createProjJob', 'as' => 'job/createProjJob'));
	Route::post('job/createProjTaskJob', array('uses' => 'JobController@createProjTaskJob', 'as' => 'job/createProjTaskJob'));
	Route::post('job/updateProjTaskJob', array('uses' => 'JobController@updateProjTaskJob', 'as' => 'job/updateProjTaskJob'));
	Route::post('job/manageFiles', array('uses' => 'JobController@manageFiles', 'as' => 'job/manageFiles'));
	Route::get('job/excelExport', array('uses' => 'JobController@excelExport', 'as' => 'job/excelExport'));
	Route::get('job/getPdffile/{id}', array('as' => 'job/getPdffile', 'uses' => 'JobController@getPdffile'));
	Route::get('ajax/deleteJobTask', array('uses'=>'JobController@deleteJobTask'));
	Route::get('ajax/deleteJobProjectTask', array('uses'=>'JobController@deleteJobProjectTask'));
	Route::get('ajax/updateJobProjectTask', array('uses'=>'JobController@updateJobProjectTask'));
	Route::post('job/updateElectricJobs', array('uses' => 'JobController@updateElectricJobs', 'as' => 'job/updateElectricJobs'));
	Route::post('job/equipCheckOut', array('uses' => 'JobController@equipCheckOut', 'as' => 'job/equipCheckOut'));
	Route::get('job/link_jobs', array('as' => 'job/link_jobs', 'uses' => 'JobController@linkJobs'));
	Route::post('job/link_jobs', array('as' => 'job/link_jobs', 'uses' => 'JobController@linkJobs'));
	Route::get('job/job_export_opt', array('as' => 'job/job_export_opt', 'uses' => 'JobController@jobExportOpt'));
	Route::post('job/job_export_opt', array('as' => 'job/job_export_opt', 'uses' => 'JobController@jobExportOpt'));
	Route::get('job/jobExport', array('uses' => 'JobController@jobExport', 'as' => 'job/jobExport'));
	Route::get('job/job_cost_opt', array('uses' => 'JobController@jobCostImport', 'as' => 'job/job_cost_opt'));
	Route::post('job/job_cost_opt', array('uses' => 'JobController@jobCostImport', 'as' => 'job/job_cost_opt'));
	Route::get('job/job_due_amt_imp_opt_ar', array('uses' => 'JobController@jobDueAmtImpOptAr', 'as' => 'job/job_due_amt_imp_opt_ar'));
	Route::post('job/job_due_amt_imp_opt_ar', array('uses' => 'JobController@jobDueAmtImpOptAr', 'as' => 'job/job_due_amt_imp_opt_ar'));
	Route::get('job/job_due_amt_imp_opt_ap', array('uses' => 'JobController@jobDueAmtImpOptAp', 'as' => 'job/job_due_amt_imp_opt_ap'));
	Route::post('job/job_due_amt_imp_opt_ap', array('uses' => 'JobController@jobDueAmtImpOptAp', 'as' => 'job/job_due_amt_imp_opt_ap'));
	Route::get('job/contract_amt_imp_opt', array('uses' => 'JobController@contractAmtImpOpt', 'as' => 'job/contract_amt_imp_opt'));
	Route::post('job/contract_amt_imp_opt', array('uses' => 'JobController@contractAmtImpOpt', 'as' => 'job/contract_amt_imp_opt'));
	Route::get('job/sales_amt_imp_opt', array('uses' => 'JobController@salesAmtImpOpt', 'as' => 'job/sales_amt_imp_opt'));
	Route::post('job/sales_amt_imp_opt', array('uses' => 'JobController@salesAmtImpOpt', 'as' => 'job/sales_amt_imp_opt'));
	Route::get('job/job_cost_check', array('uses' => 'JobController@jobCostCheck', 'as' => 'job/job_cost_check'));
	Route::post('job/job_cost_check', array('uses' => 'JobController@jobCostCheck', 'as' => 'job/job_cost_check'));
	Route::get('job/excelJobCostExport', array('uses' => 'JobController@excelJobCostExport', 'as' => 'job/excelJobCostExport'));
	Route::get('job/job_cost_manage', array('uses' => 'JobController@jobCostManage', 'as' => 'job/job_cost_manage'));
	Route::post('job/job_cost_manage', array('uses' => 'JobController@jobCostManage', 'as' => 'job/job_cost_manage'));
	Route::post('job/destroyJobCost', array('uses' => 'JobController@destroyJobCost', 'as' => 'job/destroyJobCost'));
	Route::get('job/excelJobCostManageExport', array('uses' => 'JobController@excelJobCostManageExport', 'as' => 'job/excelJobCostManageExport'));
	Route::get('job/field_service_work_list', array('uses' => 'JobController@fieldServiceWorkList', 'as' => 'job/field_service_work_list'));
	Route::post('job/field_service_work_list', array('uses' => 'JobController@fieldServiceWorkList', 'as' => 'job/field_service_work_list'));
	Route::post('job/manageFSWFiles', array('as' => 'job/manageFSWFiles', 'uses' => 'JobController@manageFSWFiles'));
	Route::get('job/work_order_frm/{id}/{j_num}', array('uses' => 'JobController@workOrderFrm', 'as' => 'job/work_order_frm'));
	Route::post('job/updateFSWFrm', array('as' => 'job/updateFSWFrm', 'uses' => 'JobController@updateFSWFrm'));
	Route::get('job/commission_list', array('uses' => 'JobController@commissionList', 'as' => 'job/commission_list'));
	Route::post('job/commission_list', array('uses' => 'JobController@commissionList', 'as' => 'job/commission_list'));
	Route::get('ajax/getCommAmt', array('uses'=>'JobController@getCommAmt'));
	Route::get('ajax/getEstCommAmt', array('uses'=>'JobController@getEstCommAmt'));
	Route::post('job/postEstCommAmt', array('as' => 'job/postEstCommAmt', 'uses' => 'JobController@postEstCommAmt'));
	Route::post('job/postCommAmt', array('as' => 'job/postCommAmt', 'uses' => 'JobController@postCommAmt'));
	Route::get('job/financial_report', array('uses' => 'JobController@financialReport', 'as' => 'job/financial_report'));
	Route::post('job/financial_report', array('uses' => 'JobController@financialReport', 'as' => 'job/financial_report'));
	Route::get('job/service_job_list', array('uses' => 'JobController@serviceJobList', 'as' => 'job/service_job_list'));
	Route::post('job/service_job_list', array('uses' => 'JobController@serviceJobList', 'as' => 'job/service_job_list'));	
	Route::get('ajax/updateFWS', array('uses'=>'JobController@updateFWS'));
	Route::post('job/creatUpdataNotes', array('uses' => 'JobController@creatUpdataNotes', 'as' => 'job/creatUpdataNotes'));	
	Route::post('job/attachJobNum', array('uses' => 'JobController@attachJobNum', 'as' => 'job/attachJobNum'));	
	Route::post('job/attachJobDate', array('uses' => 'JobController@attachJobDate', 'as' => 'job/attachJobDate'));	
	Route::post('job/creatUpdataJobFiles', array('uses' => 'JobController@creatUpdataJobFiles', 'as' => 'job/creatUpdataJobFiles'));	
	Route::get('job/service_customer_view', array('uses' => 'JobController@serviceCustomerView', 'as' => 'job/service_customer_view'));
	Route::post('job/service_customer_view', array('uses' => 'JobController@serviceCustomerView', 'as' => 'job/service_customer_view'));
	Route::get('job/contract_view', array('uses' => 'JobController@contractView', 'as' => 'job/contract_view'));
	Route::post('job/contract_view', array('uses' => 'JobController@contractView', 'as' => 'job/contract_view'));
	Route::get('job/job_management', array('uses' => 'JobController@jobManagement', 'as' => 'job/job_management'));
	Route::post('job/job_management', array('uses' => 'JobController@jobManagement', 'as' => 'job/job_management'));
	Route::get('job/editJob', array('uses' => 'JobController@editJob', 'as' => 'job/editJob'));
	Route::post('job/updateThisJob', array('uses' => 'JobController@updateThisJob', 'as' => 'job/updateThisJob'));
    Route::get('job/job_calendar', array('uses' => 'JobController@jobCalendar', 'as' => 'job/job_calendar'));
    Route::post('job/job_calendar', array('uses' => 'JobController@jobCalendar', 'as' => 'job/job_calendar'));
    Route::get('job/job_calendar_view', array('uses' => 'JobController@jobCalendarView', 'as' => 'job/job_calendar_view'));
    Route::get('job/job_project', array('uses' => 'JobController@jobProjectManagement', 'as' => 'job/job_project'));
    Route::post('job/job_project', array('uses' => 'JobController@jobProjectManagement', 'as' => 'job/job_project'));
    Route::post('job/job_calendar_view', array('uses' => 'JobController@jobCalendarView', 'as' => 'job/job_calendar_view'));
    Route::post('job/deleteJobProjTasks', array('uses' => 'JobController@deleteJobProjTasks', 'as' => 'job/deleteJobProjTasks'));
    Route::get('ajax/getnShowNotes', array('uses'=>'JobController@getnShowNotes'));
    Route::get('ajax/getSJAttaches', array('uses'=>'JobController@getSJAttaches'));
    Route::get('job/serviceCustomerExport', array('uses' => 'JobController@serviceCustomerExport', 'as' => 'job/serviceCustomerExport'));
    Route::get('job/serviceContractExport', array('uses' => 'JobController@serviceContractExport', 'as' => 'job/serviceContractExport'));
    Route::get('job/getDownloadJobFile/{id}/{table}', 'JobController@getDownloadJobFile');
    Route::Resource('job','JobController');

	/*
	* 	RFI Controller
	*/
	
	Route::get('ajax/closeDiscussion', array('uses'=>'RFIController@closeDiscussion'));
	Route::get('ajax/deleteDiscussion', array('uses'=>'RFIController@deleteDiscussion'));
	Route::get('rfi/request_for_info/{id}', array('uses' => 'RFIController@requestForInfo', 'as' => 'rfi/request_for_info'));
	Route::post('rfi/search', array('uses' => 'RFIController@search', 'as' => 'rfi/search'));
	Route::post('rfi/saveReply', array('uses' => 'RFIController@saveReply', 'as' => 'rfi/saveReply'));
	Route::Resource('rfi','RFIController');
	

	/*
	* Electrical Jobs Quote Controller
	*/
	Route::get('quote/elec_quote_list', array('as' => 'quote/elec_quote_list', 'uses' => 'JobQuoteController@electricalQuoteList'));
	Route::post('quote/elec_quote_list', array('as' => 'quote/elec_quote_list', 'uses' => 'JobQuoteController@electricalQuoteList'));
	Route::post('quote/manageQuoteFiles', array('as' => 'quote/manageQuoteFiles', 'uses' => 'JobQuoteController@manageQuoteFiles'));
	Route::get('ajax/deleteQuotes', array('uses'=>'JobQuoteController@deleteQuotes'));
	Route::get('ajax/deleteQuoteFile', array('uses'=>'JobQuoteController@deleteQuoteFile'));
	Route::get('ajax/getQuoteFiles', array('uses'=>'JobQuoteController@getQuoteFiles'));
	Route::get('quote/excelQuoteExport', array('uses' => 'JobQuoteController@excelQuoteExport', 'as' => 'quote/excelQuoteExport'));
	Route::get('job/job_electrical_quote_frm/{id}/{j_num}', array('uses' => 'JobQuoteController@jobElectricalQuoteForm', 'as' => 'quote/job_electrical_quote_frm'));
	Route::get('quote/excelSubQuoteFormExport', array('uses' => 'JobQuoteController@excelSubQuoteFormExport', 'as' => 'quote/excelSubQuoteFormExport'));
	Route::get('quote/excelSubQuoteFormExport2', array('uses' => 'JobQuoteController@excelSubQuoteFormExport', 'as' => 'quote/excelSubQuoteFormExport2'));
	Route::get('quote/excelQuoteFormExport', array('uses' => 'JobQuoteController@excelQuoteFormExport', 'as' => 'quote/excelQuoteFormExport'));
	Route::get('job/job_electrical_equipment_pricing_frm/{id}/{j_num}', array('uses' => 'JobQuoteController@jobElectricalEquipForm', 'as' => 'quote/job_electrical_equipment_pricing_frm'));
	Route::get('job/job_electrical_subquote_frm/{id}/{j_num}', array('uses' => 'JobQuoteController@jobElectricalSubquoteForm', 'as' => 'quote/job_electrical_subquote_frm'));
	Route::get('quote/excelEquipQuoteExport', array('uses' => 'JobQuoteController@excelEquipQuoteExport', 'as' => 'quote/excelEquipQuoteExport'));
	Route::get('ajax/getCustomerInfo', array('uses'=>'JobQuoteController@getCustomerInfo'));
	Route::get('ajax/CopyQuoteData', array('uses'=>'JobQuoteController@CopyQuoteData'));
	Route::post('quote/eupdateGenCost', array('as' => 'quote/eupdateGenCost', 'uses' => 'JobQuoteController@eupdateGenCost'));
	Route::get('ajax/deleteEquipPricingLine', array('uses'=>'JobQuoteController@deleteEquipPricingLine'));	
	Route::post('quote/updateElectricQuoteFrm', array('as' => 'quote/updateElectricQuoteFrm', 'uses' => 'JobQuoteController@updateElectricQuoteFrm'));
	Route::post('quote/updateElectricQuotePricingFrm', array('as' => 'quote/updateElectricQuotePricingFrm', 'uses' => 'JobQuoteController@updateElectricQuotePricingFrm'));
	Route::post('quote/updateElectricSubQuoteFrm', array('as' => 'quote/updateElectricSubQuoteFrm', 'uses' => 'JobQuoteController@updateElectricSubQuoteFrm'));
	Route::get('ajax/getCustomerInfo', array('uses'=>'JobQuoteController@getCustomerInfo'));
	Route::post('quote/getElecticalQuotePdfFile', array('uses' => 'JobQuoteController@getElecticalQuotePdfFile', 'as' => 'quote/getElecticalQuotePdfFile'));
	Route::post('quote/getEquipPricingPdfFile', array('uses' => 'JobQuoteController@getEquipPricingPdfFile', 'as' => 'quote/getEquipPricingPdfFile'));
	Route::post('quote/getElecticalSubQuotePdfFile', array('uses' => 'JobQuoteController@getElecticalSubQuotePdfFile', 'as' => 'quote/getElecticalSubQuotePdfFile'));
	Route::get('ajax/addJobCategory', array('uses'=>'JobQuoteController@addJobCategory'));	
	Route::get('quote/new_jobcat', array('uses' => 'JobQuoteController@addJobCategory', 'as' => 'quote/new_jobcat'));
	Route::post('quote/new_jobcat', array('uses' => 'JobQuoteController@addJobCategory', 'as' => 'quote/new_jobcat'));
	Route::post('quote/udpate_jobcat', array('uses' => 'JobQuoteController@updateJobCat', 'as' => 'quote/udpate_jobcat'));
	Route::post('quote/addNewTypenPart', array('uses' => 'JobQuoteController@addNewTypenPart', 'as' => 'quote/addNewTypenPart'));
	Route::get('ajax/getFieldCompnMat', array('uses'=>'JobQuoteController@getFieldCompnMat'));	
	Route::post('quote/addNewPart', array('uses' => 'JobQuoteController@addNewPart', 'as' => 'quote/addNewPart'));
	Route::get('ajax/deleteFromFrmShopWork', array('uses'=>'JobQuoteController@deleteFromFrmShopWork'));	
	Route::get('ajax/searchParts', array('uses'=>'JobQuoteController@searchParts'));	
    Route::post('quote/updateShopWorkQuoteFrm', array('uses' => 'JobQuoteController@updateShopWorkQuoteFrm', 'as' => 'quote/updateShopWorkQuoteFrm'));
	Route::get('quote/getDownloadFile/{id}/{table}', 'JobQuoteController@getDownloadFile');
    Route::Resource('quote','JobQuoteController');


	/**
	* Contract Consume Contoller	
	**/
	Route::get('contract/contractList', array('as' => 'contract/contractList', 'uses' => 'ContractController@contractList'));	
	Route::get('contract/downloadFile', array('as' => 'contract/downloadFile', 'uses' => 'ContractController@downloadFile'));	
	Route::get('contract/excelExport', array('as' => 'contract/excelExport', 'uses' => 'ContractController@excelExport'));
	Route::get('contract/consumServiceType', array('as' => 'contract/consumServiceType', 'uses' => 'ContractController@consumServiceType'));
	Route::get('ajax/contractInvoiceAmount', array('uses' =>'ContractController@contractInvoiceAmount'));
	Route::get('ajax/deleteContracts', array('as' => 'ajax/deleteContracts', 'uses' =>'ContractController@deleteContracts'));
	Route::get('ajax/deleteContractFile', array('uses'=>'ContractController@deleteContractFile'));
	Route::get('ajax/getContractFiles', array('uses'=>'ContractController@getContractFiles'));
	Route::get('ajax/saveContractServiceType', array('uses'=>'ContractController@saveContractServiceType'));
	Route::get('ajax/deleteContractServiceType', array('uses'=>'ContractController@deleteContractServiceType'));
	Route::post('contract/manageContractFiles', array('as' => 'contract/manageContractFiles', 'uses' => 'ContractController@manageContractFiles'));	
	Route::post('contract/contractList', array('as' => 'contract/contractList', 'uses' => 'ContractController@contractList'));
	Route::get('contract/contract_amt_opt', array('as' => 'contract/contract_amt_opt', 'uses' => 'ContractController@contractAmtOpt'));	
	Route::post('contract/contract_amt_opt', array('as' => 'contract/contract_amt_opt', 'uses' => 'ContractController@contractAmtOpt'));	
	Route::get('contract/contract_info_imp', array('as' => 'contract/contract_info_imp', 'uses' => 'ContractController@contractInfoImp'));		
	Route::post('contract/contract_info_imp', array('as' => 'contract/contract_info_imp', 'uses' => 'ContractController@contractInfoImp'));		
	Route::get('contract/consum_contract_frm/{id}/{j_num}', array('as' => 'contract/consum_contract_frm', 'uses' => 'ContractController@consumContractForm'));		
	Route::post('contract/updateConsumContractForm', array('as' => 'contract/updateConsumContractForm', 'uses' => 'ContractController@updateConsumContractForm'));		
	Route::post('contract/eupdatEquipmentDetails', array('as' => 'contract/eupdatEquipmentDetails', 'uses' => 'ContractController@eupdatEquipmentDetails'));		
	Route::get('ajax/renewContract', array('uses'=>'ContractController@renewContract'));
	Route::get('ajax/duplicateContract', array('uses'=>'ContractController@duplicateContract'));
	Route::get('ajax/copyContract', array('uses'=>'ContractController@copyContract'));
	Route::get('contract/getConsumerContractFile/{id}', array('as' => 'contract/getConsumerContractFile', 'uses' => 'ContractController@getConsumerContractFile'));		
	Route::Resource('contract','ContractController');
	
	/**
	*
	* Consume Contract Equipment Controller
	*
	**/	
	Route::get('consumecontract/editEquipmentInfo/{id}', array('as' => 'consumecontract/editEquipmentInfo', 'uses' => 'ConsumeContractController@editEquipmentInfo'));
	Route::post('consumecontract/editEquipmentInfo/{id}', array('as' => 'consumecontract/editEquipmentInfo', 'uses' => 'ConsumeContractController@editEquipmentInfo'));
	
	Route::get('consumecontract/equipmentInfoList', array('as' => 'consumecontract/equipmentInfoList', 'uses' => 'ConsumeContractController@equipmentInfoList'));
	Route::post('consumecontract/equipmentInfoList', array('as' => 'consumecontract/equipmentInfoList', 'uses' => 'ConsumeContractController@equipmentInfoList'));	
	
	Route::get('ajax/deleteConsumeContracts', array('uses' =>'ConsumeContractController@deleteConsumeContracts'));

	Route::Resource('consumecontract','ConsumeContractController');

	/**
	*
	* Contract Amount Controller
	*
	**/

	Route::get('contractAmount/contractAmountList', array('as' => 'contractAmount/contractAmountList', 'uses' => 'ContractAmountController@contractAmountList'));
	Route::post('contractAmount/contractAmountList', array('as' => 'contractAmount/contractAmountList', 'uses' => 'ContractAmountController@contractAmountList'));
	Route::get('contractAmount/excelExport', array('as' => 'contractAmount/excelExport', 'uses' => 'ContractAmountController@excelExport'));

	Route::get('ajax/deleteContractAmount', array('uses' =>'ContractAmountController@deleteContractAmount'));

	Route::Resource('contractAmount','ContractAmountController');


	/**
	*
	* Contract Tenure Controller
	*
	**/

	Route::get('contractTenure/contractTenureList', array('as' => 'contractTenure/contractTenureList', 'uses' => 'ContractTenureController@contractTenureList'));
	Route::post('contractTenure/contractTenureList', array('as' => 'contractTenure/contractTenureList', 'uses' => 'ContractTenureController@contractTenureList'));
	Route::get('contractTenure/excelExport', array('as' => 'contractTenure/excelExport', 'uses' => 'ContractTenureController@excelExport'));	

	Route::Resource('contractTenure','ContractTenureController');
	
	

});

App::missing(function($exception)
{
	// echo "missing";
	// die;

    return View::make('404');
});
//Route::group(['after'=>'auth'],function(){
		
	Route::get('login',	"MainController@showLogin");

	Route::post('loginaction', 'MainController@authenticate');
	//return Redirect::to('dashboard');	

//});


App::error(function(Exception $e) {
    //return View::make('404');
});


Route::get('{folder}/{file}',function($folder,$file){

		if($file !== 'favicon.png')
			Session::set('url_to',$folder.'/'.$file);
		
		if(Auth::check()){
			Session::forget('url_to');
			//return App::make(ucwords($folder)."Controller")->{$file}();
		}
		else
			return Redirect::to('login');	//url="job/index"
		//$path = ucwords($folder)."Controller@".$file;
		//return App::make(ucwords($folder)."Controller")->{$file}();
	});

Route::get('register', function()
{
	return View::make('hello');
});

# filter automatically every request for the CSRF token
Route::when('*', 'csrf', array('post', 'put', 'delete'));
