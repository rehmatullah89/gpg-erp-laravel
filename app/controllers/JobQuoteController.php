<?php

class JobQuoteController extends \BaseController {

	protected $elecJobTypeArray = array( "GeneratorOnly" => "Generator Only", "GeneratorAndPermit" => "Generator & Permit", "Permit" => "Permit", "MonthlyMaintenance" => "Monthly Maintenance", "Retrofit" => "Retrofit", "Standard" => "Standard", "EmergencyCall" => "Emergency Call", "TroubleShoot" => "Trouble Shoot", "InfraredScan" => "Infrared Scan", "ContractJob" => "Contract Job", "ATS" => "ATS", "ArcFlashStudy" => "Arc Flash Study", "ChartRecording" => "Chart Recording", "CircuitTracing" => "Circuit Tracing", "WarrantyNonBillable" => "Warranty (Non Billable)", "GenTracker" => "Gen Tracker" );
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		$job_types = DB::table('gpg_job_type')->select('*')->get();
		$job_types_arr = array();
		foreach ($job_types as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$temp_arr[$key] = $value; 		
			}
			$job_types_arr[] = $temp_arr;
		}
		$params = array('left_menu' => $modules, 'job_types'=>$job_types_arr);
		return View::make('job.index', $params);
	}

	/*
	* Electrical Job List
	*/
	public function electricalQuoteList(){
		$modules = Generic::modules();
		# get all inputs except _token to parse paginate links
		$allInputs = Input::except('_token');
		Input::flash();

		$uriSegment = Request::segment(2);
		$reqJobListing = (isset($uriSegment) && $uriSegment != '') ? $uriSegment : 'elec_quote_list';
 	  	$table_before = 'gpg_job_';
 	  	switch($reqJobListing) {
			case 'grassivy_quote_list':
				$Heading = 'GRASSIVY QUOTE LISTING ';
				$table = 'Grassivy';
			break;
			case 'specialproject_quote_list':
				$Heading = 'SPECIAL PROJECT QUOTE LISTING ';
				$table = 'Special Project';
			break;
			case 'shop_work_quote_list':
				$Heading = 'SHOP WORK QUOTE LISTING';
				$table = 'Shop Work';
				$table_before = 'gpg_';
			break;
			default:
				$Heading = 'ELECTRICAL QUOTE LISTING ';
				$table = 'Electrical';
			break;
	  	}

		$page = Input::get('page', 1);
   		$data = $this->getByPage($page, 100);
   		$query_data = Paginator::make($data->items, $data->totalItems, 100);
 		
 		$customers = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$cust_arr = array(''=>'ALL');
		foreach ($customers as $key => $value)
				$cust_arr[$value->id] = $value->name;

 		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		
		$quote_files = DB::select(DB::raw("select * from ".$table_before.str_replace(' ', '_',strtolower($table))."_quote_attachment where ".$table_before.str_replace(' ', '_',strtolower($table))."_quote_id != '' ORDER BY ".$table_before.str_replace(' ', '_',strtolower($table))."_quote_id"));	
		$fileAtt = '';
		$temp_id = '';
		$Quotefiles = array();
		foreach ($quote_files as $key => $value) {
			$id_str = $table_before.str_replace(' ', '_',strtolower($table))."_quote_id";
			if ($temp_id == $value->$id_str)
				$fileAtt .= $value->displayname;
			else 
				$fileAtt = $value->displayname.'<br/>';
			$temp_id = $value->$id_str;
			$Quotefiles[$value->$id_str] = $fileAtt;
		}
		
		$params = array('left_menu' => $modules, 'query_data'=>$query_data,'cust_arr'=>$cust_arr,'salesp_arr'=>$salesp_arr,'Quotefiles'=>$Quotefiles);
 		$params['allInputs'] = $allInputs;
 		$params['page_heading'] = $Heading;	
 		$params['table'] = $table;
 		
 		return View::make('job.elec_quote_list', $params);
	}
	
	/*
	* Export Excel File
	*/
	public function excelQuoteExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('QuoteExportFile', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getByPage($page);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$customers = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->get();
			$cust_arr = array(''=>'ALL');
			foreach ($customers as $key => $value)
					$cust_arr[$value->id] = $value->name;

	 		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
			$salesp_arr = array(''=>'ALL');
			foreach ($salesPerson as $key => $value)
					$salesp_arr[$value->id] = $value->name;	 		
	 		
			$params = array('query_data'=>$query_data,'cust_arr'=>$cust_arr,'salesp_arr'=>$salesp_arr);
		        $sheet->loadView('job.excelQuoteExport',$params);
		    });
		})->export('xls');
 	}
	/*
	* paginator for index quote list	
	*/
	public function getByPage($page = 1, $limit = null)
	{
	  $results = new \StdClass;
	  $results->page = $page;
	  $results->totalItems = 0;
	  $limitOffset = '';
	  if($limit != null){
	  	$results->limit = $limit;
	  	$start = $limit * ($page - 1);
	  	$limitOffset = 'limit ' . $start . ', ' . $limit;
	  }
	  $results->items = array();
	  $temp_arr = array();
	  $items_arr = array();
      $materialCostQueryPart ="";
	  $invoiceQueryPart ="";
	  $laborCostQueryPart ="";
	  $queryPartSort = "";
      $partQuery = "";

      #select grassivy, electrical or speical project quote list
      $uriSegment = Request::segment(2);
      $reqJobListing = (isset($uriSegment) && $uriSegment != '') ? $uriSegment : 'elec_quote_list';
 	  $table_before = 'gpg_job_';
 	  $track_table = 'gpg_sales_tracking_job_';
 	  $_labor_pricing  = '_labor_pricing';
 	  switch($reqJobListing){
			case 'grassivy_quote_list':
				$table = 'grassivy';
				$stable = 'grassivy'; 
			break;
			case 'specialproject_quote_list':
				$table = 'special_project';
				$stable = 'special_project';
			break;
			case 'shop_work_quote_list':
				$table = 'shop_work';
				$stable = 'shop_work';
				$table_before = 'gpg_';
				$track_table = 'gpg_sales_tracking_';
				$_labor_pricing = '_quote_labor';
			break;
			default:
				$table = 'electrical';
				$stable = 'elec';
			break;
	  }
	  if($reqJobListing == 'excelQuoteExport'){
      	if ($_REQUEST['table'] == 'Shop Work') {
			$table_before = 'gpg_';
			$table = 'shop_work';
			$reqJobListing = 'shop_work_quote_list';
			$track_table = 'gpg_sales_tracking_';
			$_labor_pricing = '_quote_labor';
		}else
			$table = strtolower($_REQUEST['table']);
      	if ($table == 'electrical') {
      		$stable = 'elec';
      	}else
	    	$stable = $table;
      }

 	  $SDate2 = "";
 	  if (isset($_REQUEST['SDate2']))
        $SDate2 = $_REQUEST['SDate2'];
	   $EDate2 = "";
	  if (isset($_REQUEST['EDate2']))
        $EDate2 = $_REQUEST['EDate2'];  
	   $JobWonSDate = "";
	  if (isset($_REQUEST['JobWonSDate']))
        $JobWonSDate = $_REQUEST['JobWonSDate'];    
	   $JobWonEDate = "";
	  if (isset($_REQUEST['JobWonEDate']))
        $JobWonEDate = $_REQUEST['JobWonEDate'];    
	   $InvoiceSDate = "";
	  if (isset($_REQUEST['InvoiceSDate']))
        $InvoiceSDate = $_REQUEST['InvoiceSDate'];  
 	   $InvoiceEDate = "";
 	  if (isset($_REQUEST['InvoiceEDate']))
        $InvoiceEDate = $_REQUEST['InvoiceEDate'];    
	   $SJobNumber = "";
	  if (isset($_REQUEST['SJobNumber']))
        $SJobNumber = $_REQUEST['SJobNumber'];    
	   $EJobNumber = "";
	  if (isset($_REQUEST['EJobNumber']))
        $EJobNumber = $_REQUEST['EJobNumber'];    
	  $ignoreCostDate = "";
 	  if (isset($_REQUEST['ignoreCostDate']))
        $ignoreCostDate = $_REQUEST['ignoreCostDate'];
	    $ignoreInvoiceDate = "";
	  if (isset($_REQUEST['ignoreInvoiceDate']))
       $ignoreInvoiceDate = $_REQUEST['ignoreInvoiceDate'];        
	   $optJobNumber = "";
	  if (isset($_REQUEST['optJobNumber']))
	        $optJobNumber = $_REQUEST['optJobNumber'];    
	   $optEmployee = "";
	  if (isset($_REQUEST['optEmployee']))
	        $optEmployee = $_REQUEST['optEmployee'];    
	   $optCustomer = "";
	  if (isset($_REQUEST['optCustomer']))
	        $optCustomer = $_REQUEST['optCustomer'];    
	   $optStatus = "";
	   if (isset($_REQUEST['optStatus']))
	   		$optStatus = $_REQUEST['optStatus'];
	   $optJobStatus = "";
	  if (isset($_REQUEST['optJobStatus']))
	        $optJobStatus = $_REQUEST['optJobStatus'];    
	   $optSort = "";
	  if (isset($_REQUEST['optSort']))
	        $optSort = $_REQUEST['optSort'];   

	 	/* ********************************************************************* Search variables **************************************************************************************************************************************************************************************************************************************************************************************** */
		if ( $SDate2 != "" and $EDate2 != "" ) {
		    $partQuery .= " AND created_on >= '" . date('Y-m-d', strtotime($SDate2)) . " 00:00:00' AND created_on <= '" . date('Y-m-d', strtotime($EDate2)) . " 23:59:59' ";
		    if ( $ignoreCostDate == '' ) {
		        $materialCostQueryPart = " AND gpg_job_cost.date >= '" . date('Y-m-d', strtotime($SDate2)) . "' AND gpg_job_cost.date <= '" . date('Y-m-d', strtotime($EDate2)) . "' ";
		        $laborCostQueryPart = " AND gpg_timesheet.date >= '" . date('Y-m-d', strtotime($SDate2)) . "' AND gpg_timesheet.date <= '" . date('Y-m-d', strtotime($EDate2)) . "' ";
		    }
		    if ( $ignoreInvoiceDate == '' and $InvoiceSDate == '' ) {
		        $invoiceQueryPart = " AND gpg_job_invoice_info.invoice_date >= '" . date('Y-m-d', strtotime($SDate2)) . "' AND gpg_job_invoice_info.invoice_date <= '" . date('Y-m-d', strtotime($EDate2)) . "' ";
		    }
		} elseif ( $SDate2 != "" ) {
		    $partQuery .= " AND date_format(created_on,'%Y-%m-%d') = '" . date('Y-m-d', strtotime($SDate2)) . "'";
		    if ( $ignoreCostDate == '' ) {
		        $materialCostQueryPart = " AND gpg_job_cost.date = '" . date('Y-m-d', strtotime($SDate2)) . "' ";
		        $laborCostQueryPart = " AND gpg_timesheet.date = '" . date('Y-m-d', strtotime($SDate2)) . "' ";
		    }
		    if ( $ignoreInvoiceDate == '' and $InvoiceSDate == '' ) {
		        $invoiceQueryPart = " AND gpg_job_invoice_info.invoice_date = '" . date('Y-m-d', strtotime($SDate2)) . "' ";
		    }
		}
		if ( $InvoiceSDate != "" and $InvoiceEDate != "" ) {
		    $partQuery .= " AND GPG_attach_job_num = (select gpg_job_invoice_info.job_num from gpg_job_invoice_info where gpg_job_invoice_info.job_num= GPG_attach_job_num AND gpg_job_invoice_info.invoice_date >= '" . date('Y-m-d', strtotime($InvoiceSDate)) . "' AND  gpg_job_invoice_info.invoice_date <= '" . date('Y-m-d', strtotime($InvoiceEDate)) . "'  order by gpg_job_invoice_info.invoice_date desc limit 0,1)";
		    if ( $ignoreCostDate == '' and $SDate == '' ) {
		        $materialCostQueryPart = " AND gpg_job_cost.date >= '" . date('Y-m-d', strtotime($InvoiceSDate)) . "' AND gpg_job_cost.date <= '" . date('Y-m-d', strtotime($InvoiceEDate)) . "' ";
		        $laborCostQueryPart = " AND gpg_timesheet.date >= '" . date('Y-m-d', strtotime($InvoiceSDate)) . "' AND gpg_timesheet.date <= '" . date('Y-m-d', strtotime($InvoiceEDate)) . "' ";
		    }
		    if ( $ignoreInvoiceDate == '' ) {
		        $invoiceQueryPart = " AND gpg_job_invoice_info.invoice_date >= '" . date('Y-m-d', strtotime($InvoiceSDate)) . "' AND gpg_job_invoice_info.invoice_date <= '" . date('Y-m-d', strtotime($InvoiceEDate)) . "' ";
		    }
		} elseif ( $InvoiceSDate != "" ) {
		    $partQuery .= " AND GPG_attach_job_num = (select gpg_job_invoice_info.job_num from gpg_job_invoice_info where gpg_job_invoice_info.job_num= GPG_attach_job_num AND  gpg_job_invoice_info.invoice_date = '" . date('Y-m-d', strtotime($InvoiceSDate)) . "' order by gpg_job_invoice_info.invoice_date desc limit 0,1)";
		    if ( $ignoreCostDate == '' and $SDate == '' ) {
		        $materialCostQueryPart = " AND gpg_job_cost.date = '" . date('Y-m-d', strtotime($InvoiceSDate)) . "' ";
		        $laborCostQueryPart = " AND gpg_timesheet.date = '" . date('Y-m-d', strtotime($InvoiceSDate)) . "' ";
		    }
		    if ( $ignoreInvoiceDate == '' ) {
		        $invoiceQueryPart = " AND gpg_job_invoice_info.invoice_date = '" . date('Y-m-d', strtotime($InvoiceSDate)) . "' ";
		    }
		}
		if ( $JobWonSDate != "" and $JobWonEDate != "" )
		    $partQuery .= " AND date_job_won >= '" . date('Y-m-d', strtotime($JobWonSDate)) . "' AND date_job_won <= '" . date('Y-m-d', strtotime($JobWonEDate)) . "' ";
		elseif ( $JobWonSDate != "" )
		    $partQuery .= " AND date_job_won = '" . date('Y-m-d', strtotime($JobWonSDate)) . "'";
		if ( $optJobNumber != "" )
		    $partQuery .= " AND job_num like '" . $optJobNumber . "%'";
		if ( $optEmployee != "" )
		    $partQuery .= " AND gpg_employee_id = '$optEmployee' ";
		if ( $optCustomer != "" )
		    $partQuery .= " AND gpg_customer_id = '$optCustomer' ";
		if ( $optStatus != "" )
		    $partQuery .= " AND electrical_status = '$optStatus' ";
		if ( $optJobStatus == "completed" )
		    $partQuery .= " AND (select id from gpg_job where  complete = '1' and job_num = GPG_attach_job_num limit 0,1)";
		if ( $optJobStatus == "notcompleted" )
		    $partQuery .= " AND (select id from gpg_job where complete = '0' and job_num = GPG_attach_job_num limit 0,1)";
		if ( $optJobStatus == "invoiced" )
		    $partQuery .= " AND (select gpg_job_id from gpg_job_invoice_info where job_num = GPG_attach_job_num  limit 0,1) ";
		if ( $optJobStatus == "not_invoiced" )
		    $partQuery .= "AND ifnull(GPG_attach_job_num,'') <> '' AND if((select gpg_job_id from gpg_job_invoice_info where job_num = GPG_attach_job_num  limit 0,1)>0,0,1) ";
		if ( $optJobStatus == "comp_inv" )
		    $partQuery .= "AND ((select id from gpg_job where  complete = '1' and job_num = GPG_attach_job_num limit 0,1) AND (select gpg_job_id from gpg_job_invoice_info where job_num = GPG_attach_job_num  limit 0,1)) ";
		if ( $optJobStatus == "not_comp_inv" )
		    $partQuery .= " AND ((select id from gpg_job where  complete = '0' and job_num = GPG_attach_job_num limit 0,1) AND (select gpg_job_id from gpg_job_invoice_info where job_num = GPG_attach_job_num  limit 0,1)) ";
		if ( $optJobStatus == "completed_not_invoiced" )
		    $partQuery .= " AND ((select id from gpg_job where  complete = '1' and job_num = GPG_attach_job_num limit 0,1) AND if((select gpg_job_id from gpg_job_invoice_info where job_num = GPG_attach_job_num  limit 0,1)>0,0,1)) ";
		
		if (empty($optSort))
		    $queryPartSort .= " order by job_num desc,created_on desc";
		if ( $optSort == "customerAndDate" )
		    $queryPartSort .= " order by customer,created_on desc";
		else if ( $optSort == "salespersonAndDate" )
		    $queryPartSort .= " order by salesPerson,created_on desc";
	/* -------------------------------------------------------------------------------- End Search Variables -------------------------------------------------------------------------------------------------------------------------------*/
		
	  $temp_Array = array(); 			
	  $query_count = DB::select( DB::raw("select count(id) as count from ".$table_before.$table."_quote where 1 $partQuery"));
	  $query = DB::select( DB::raw("select *,(select name from gpg_customer where id = GPG_customer_id) as customer,(select name from gpg_employee where id = GPG_employee_id) as salesPerson from ".$table_before.$table."_quote where 1 $partQuery $queryPartSort $limitOffset"));	
	  $i=1;
	  foreach ($query as $key2 => $value2) {
	  	foreach ($value2 as $key => $value) {
	  		if (empty($value) && $key != 'GPG_attach_job_num' && $key != $stable.'_quote_status' && $key != $table.'_status') {
	  			$temp_Array[$key]  = '-';
	  			if ($key == $stable.'_quote_type'){
	  				$temp_Array['quote_type']  = '-';
	  			}
	  			if($key == $table.'_qote_stage_id'){
	  				$temp_Array['qote_stage_id'] = '-';
	  			}
	  		}
	  		else if($key == $stable.'_quote_status'){
	  			if (empty($value)) 
	  				$temp_Array['quote_status'] = '-';
	  			else
	  				$temp_Array['quote_status'] = $value;
	  		}
	  		else if($key == $table.'_status'){
	  			if (empty($value))
	  				$temp_Array['job_type_status'] = '-';
	  			else
	  				$temp_Array['job_type_status'] = $value;
	  		}
	  		else if($key == $stable.'_quote_type' && !empty($value)){
	  			$temp_Array['quote_type'] = $this->elecJobTypeArray[$value];
	  		}else if($key == $table.'_qote_stage_id' && !empty($value)){
	  			$qtemp1 = DB::select( DB::raw("SELECT value FROM gpg_settings WHERE id ='".$value."'"));
	  			if (!empty($qtemp1)) {
	  				$temp_Array['qote_stage_id'] = $qtemp1[0]->value;
	  			}else
		  			$temp_Array['qote_stage_id'] = '-';
	  		}
			else if($key == 'GPG_attach_job_num') {
				if (empty($value)){
					$temp_Array[$key]  = '-';
					$temp_Array['attachJobRes'] = array();
					$temp_Array['commData'] = array();
					$temp_Array['time_diff_dec'] = '-';
				}
				else{
						$temp_Array['GPG_attach_job_num'] = $value;
						$tempq = DB::select( DB::raw("select id,job_num,tax_amount,(select concat(invoice_number,'#~#',sum(invoice_amount),'#~#',invoice_date,'#~#',sum(tax_amount),'#~#',count(id)) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $invoiceQueryPart group by gpg_job_invoice_info.gpg_job_id) as invoice_data,(select sum(total_wage) from gpg_timesheet_detail , gpg_timesheet where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num $laborCostQueryPart) as labor_cost , (select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $materialCostQueryPart) as material_cost,(SELECT sales_commission FROM gpg_employee_commission WHERE gpg_employee_commission.gpg_employee_id = gpg_job.GPG_employee_id and gpg_employee_commission.start_date <= DATE(gpg_job.created_on) order by start_date desc limit 0,1) as sales_commission from gpg_job where job_num = '".$value."'"));
		  				$attachJobRes = array();
		  				if (!empty($tempq)) {
		  					foreach ($tempq as $key3 => $value3) {
		  						foreach ($value3 as $key4 => $value4){
		  							if($key4 == 'id'){
		  								if (!empty($value4)) {
		  									$tempq2 = DB::select( DB::raw("select comm_date,sum(ifnull(comm_paid,0)) as amt,count(id) as cnt from gpg_job_commission WHERE gpg_job_id = '".$value4."' group by gpg_job_commission.gpg_job_id order by created_on desc"));
		  									if(!empty($tempq2))
		  									foreach ($tempq2 as $key5 => $value5) {
		  										$tempArr = array();
		  										foreach ($value5 as $key6 => $value6) {
		  											$tempArr[$key6] = $value6;
		  										}
		  										$temp_Array['commData'] = $tempArr; 
		  									}else
			  									$temp_Array['commData'] = array();
		  								}else
		  									$temp_Array['commData'] = array();
		  							}
		  							$attachJobRes[$key4] = $value4;		
		  						}	
		  						$temp_Array['attachJobRes'] = $attachJobRes;
		  					}
		  				}else 
			  				$temp_Array['attachJobRes'] = $attachJobRes;

			  			$tempq1 = DB::select( DB::raw("SELECT FORMAT(SUM(time_diff_dec),2) as time_diff_dec FROM gpg_timesheet_detail WHERE job_num = '".$value."'"));
		  				if (!empty($tempq1) && isset($tempq1[0]->time_diff_dec)) {
		  					$temp_Array['time_diff_dec'] = $tempq1[0]->time_diff_dec;
		  				}else
		  					$temp_Array['time_diff_dec'] = '-';
		  		}
	  		}else{
	  			if ($key == 'id') {
	  				$qtemp = DB::select( DB::raw("SELECT gpg_sales_tracking_id FROM ".$track_table.$table."_quote WHERE ".$table_before.$table."_quote_id ='".$value."'"));
	  				if (!empty($qtemp)) {
	  					$temp_Array['gpg_sales_tracking_id'] = $qtemp[0]->gpg_sales_tracking_id;
	  				}else
	  				 	$temp_Array['gpg_sales_tracking_id'] = '-';
	  				
	  				if($reqJobListing != 'shop_work_quote_list')
		  				$qtemp0 = DB::select( DB::raw("SELECT FORMAT(SUM(labor_quantity),2) as labor_quantity FROM ".$table_before.$table.$_labor_pricing." WHERE ".$table_before.$table."_quote_id = '".$value."'"));
	  				else
	  					$qtemp0 = DB::select( DB::raw("SELECT FORMAT(SUM(labor),2) as labor FROM ".$table_before.$table.$_labor_pricing." WHERE ".$table_before.$table."_quote_id = '".$value."'"));
	  				if (!empty($qtemp0) && !empty($qtemp0[0]->labor_quantity)){
	  				 		$temp_Array['labor_quantity'] = $qtemp0[0]->labor_quantity;
	  				}else
	  				 	$temp_Array['labor_quantity'] = '-'; 
	  				if ($reqJobListing == 'shop_work_quote_list') {
	  					$freight0 = DB::select(DB::raw("select sum(other_charge_cost_price*other_charge_qty) as freight from gpg_shop_work_quote_other where other_charge_description='Freight' and gpg_shop_work_quote_id = '".$value."'"));
	  					$temp_Array['freight'] = $freight0[0]->freight;
	  					$mileage0 = DB::select(DB::raw("select sum(other_charge_cost_price*other_charge_qty) as mileage from gpg_shop_work_quote_other where other_charge_description='Mileage' and gpg_shop_work_quote_id = '".$value."'"));
	  					$temp_Array['mileage'] = $mileage0[0]->mileage;
	  				} 	
	  			}
	  			$temp_Array[$key]  = $value;
	  		}
	  	}		
	  	$items_arr[] = $temp_Array;
	  }	
	  /*echo "<pre>";
	  print_r($items_arr);
	  die();*/
	  if (isset($query_count[0]->count)){
		  $results->totalItems = $query_count[0]->count;
		  $results->items = $items_arr;
	  }

	  return $results;
	}
	/*
	* Local methods
	*/
	public function sqlstr_clear($str)
	{
    	return "ROUND(REPLACE(REPLACE(".$str.",',',''),'".'$'."',''),2)";
	}
	public function clear_num($num)
	{
	   return doubleval(str_replace('$','',str_replace(',','',$num)));
	}

	/*
	* getQuoteFiles
	*/
	public function getQuoteFiles(){
		$id = Input::get('id');
		$job_num = Input::get('num');
		$table = Input::get('table');
		$table_pre = 'gpg_job_';
		if ($table == 'Shop Work') {
			$table_pre = 'gpg_';
		}
		$colcount = 1;
		$conCatStr = '';
		$quote_files = DB::select(DB::raw("select * from ".$table_pre.str_replace(' ', '_',strtolower($table))."_quote_attachment where ".$table_pre.str_replace(' ', '_',strtolower($table))."_quote_id = '$id'"));
		if (!empty($quote_files)){
			foreach($quote_files as $key=>$row){
        	    $conCatStr .='<tr><td>'.$colcount++.'</td><td>'.$row->displayname.'</td><td><a class="btn btn-danger btn-xs" id="'.$row->id.'" name="del_quote_file">Delete</a><a href="/quote/getDownloadFile/'.$row->id.'/'.$table.'" class="btn btn-success btn-xs" id="'.$row->id.'" name="dld_quote_file">Download</a></td></tr>';
			}
    	}
    	return $conCatStr;
	}
	
	/*
	* manageQuoteFiles
	*/
	public function manageQuoteFiles(){
		if (!empty($_POST['fjob_id'])){
			$job_id = $_POST['fjob_id'];
			$job_num = $_POST['fjob_num'];
			$file = Input::file('attachment');
			$file_type_settings =  DB::table('gpg_settings')
			    ->select('*')
			    ->where('name', '=', '_ImgExt')
			    ->get();    
			$file_types = explode(',', $file_type_settings[0]->value);
			if (!empty($file)){
				if (in_array($file->getClientOriginalExtension(), $file_types)) {
					$ext1 = explode(".",$file->getClientOriginalName());
				 	$ext2 = end($ext1);
				 	$filename = "electrical_quote_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = $file->move($destinationPath, $filename);
					//insert into db
					DB::table('gpg_job_electrical_quote_attachment')->insert(array('gpg_job_electrical_quote_id' =>$job_id,'filename'=>$filename ,'displayname'=>$file->getClientOriginalName()));		
				}
			}
		}
		return Redirect::to('quote/elec_quote_list');
	}
	
	/*
	* jobElectricalQuoteForm	
	*/
	public function jobElectricalQuoteForm($id,$j_num){

		$modules = Generic::modules();
		$query_data = DB::select(DB::raw("select * from gpg_job_electrical_quote where id = '$id'"));
		$other = DB::select(DB::raw("select * from gpg_job_electrical_quote_other WHERE gpg_job_electrical_quote_id = '$id' order by id asc"));
		$quote_other_info = array();
		foreach ($other as $key0 => $value0) {
			$quote_other_info[] = array('id' =>$value0->id ,'gpg_job_electrical_quote_id' =>$value0->gpg_job_electrical_quote_id ,'other_charge_qty' =>$value0->other_charge_qty ,'other_charge_description' =>$value0->other_charge_description ,'other_charge_cost_price' =>$value0->other_charge_cost_price);
		}
		$Quote_Data = array();
		foreach ($query_data as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				if ($key == 'GPG_customer_id') {
					$Quote_Data['customer_drop_down'] = DB::table('gpg_customer')->select('id','name')->lists('name','id');
					$Quote_Data['salesPerson_drop_down'] = DB::table('gpg_employee')->select('id','name')->lists('name','id');
					$Quote_Data['estimator_drop_down'] = DB::table('gpg_employee')->select('id','name')->where('frontend', 'like', '%sales%')->orderBy('name', 'asc')->lists('name','id');
					if (!empty($value)){
						$QData = DB::table('gpg_customer')->select('*')->where('id','=',$value)->get();
						$QdataArr = array();
						foreach ($QData as $key3 => $value3) {
							foreach ($value3 as $key4 => $value4) {
								$QdataArr[$key4] = $value4;
							}
						}
						$Quote_Data['customer_info'] = $QdataArr;	
					}
					else
						$Quote_Data['customer_info'] = array();
				}
				if ($key == 'contact_info_id'){
					$contact_info = DB::table('gpg_settings')->where('name', 'like', '_ContactInfo%')->orderBy('value', 'asc')->lists('value', 'id');
					$terms = DB::table('gpg_settings')->where('name', 'like', '_TermsAndConditions%')->orderBy('value', 'asc')->lists('value', 'id');
					$Quote_Data['contact_info'] = $contact_info;
					$Quote_Data['terms'] = $terms;
				}
				if (is_float($value))
					$Quote_Data[$key] = number_format($value,2);
				else
					$Quote_Data[$key] = $value; 
			}
		}
		$gpg_settings = DB::table('gpg_settings')->where('name', 'like', '_ElectricalQuoteStage%')->orderBy('value', 'asc')->lists('id', 'value');
		$gpg_settings = array('' => 'Select Stage') + $gpg_settings;
		ksort($this->elecJobTypeArray);
		$gen_cost = DB::select(DB::raw("select * FROM gpg_job_electrical_quote_labor_material_list WHERE  gpg_job_electrical_quote_id = '$id' order by id"));
		$arrayTemp = array();
		foreach ($gen_cost as $keyGen => $valueGen) {
			foreach ($valueGen as $key => $value) {
				$arrayGenCost[$key] = $value;
			}
			$arrayTemp[] = $arrayGenCost; 
		}

		$res = DB::select(DB::raw("select id,concat(job_num,' ',ifnull((select name from gpg_customer where id = GPG_customer_id),''),'&nbsp; | &nbsp;',ifnull(project_name,''),'&nbsp; | &nbsp;',ifnull(project_address,''),'') as name from gpg_job_electrical_quote ORDER BY job_num ASC"));
		$list_quotes = array();
		foreach ($res as $key => $value){
			$list_quotes[$value->id] = $value->name; 
		}

		$quotes_ids = DB::select(DB::raw("SELECT id,job_num FROM gpg_job_electrical_quote WHERE if(POSITION(\":\" IN job_num)=0,concat(job_num,\":\"),job_num) LIKE '".(!strpos($j_num,":")?$j_num:substr($j_num,0,strlen($j_num)-3)).":%'"));
		$quote_ids_arr = array();
		foreach ($quotes_ids as $key => $value) {
			$quote_ids_arr[$value->id] = $value->job_num; 
		}

		$params = array('left_menu' => $modules,'job_id'=>$id,'job_num'=>$j_num,'jobElectricalQuoteTblRow'=>$Quote_Data,'gpg_settings'=>$gpg_settings,'elecJobTypeArray'=>$this->elecJobTypeArray,'quote_other_info'=>$quote_other_info,'gen_cost'=>$arrayTemp,'list_quotes'=>$list_quotes,'quote_ids_arr'=>$quote_ids_arr);
 		return View::make('job.job_electrical_quote_frm', $params);
	}
	/*
	* eupdateGenCost
	*/
	public function eupdateGenCost(){
		DB::table('gpg_job_electrical_quote_labor_material_list')->where('gpg_job_electrical_quote_id','=',$_POST['job_id']);
		$i=0;
		if (isset($_POST['genCounter'])) 
			while ($i < $_POST['genCounter']){
				DB::table('gpg_job_electrical_quote_labor_material_list')->insert(array('gpg_job_electrical_quote_id' =>$_POST['job_id'],'quantity' =>$_POST['quantity_'.$i],'type' =>$_POST['type_'.$i],'description' =>$_POST['description_'.$i],'material_cost' =>$_POST['material_cost_'.$i],'est_hour' =>$_POST['est_hour_'.$i],'act_hour' =>$_POST['act_hour_'.$i],'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				$i++;
			}
		return Redirect::to('job/job_electrical_quote_frm/'.$_POST['job_id'].'/'.$_POST['job_num'].'');
	}
	
	/*
	* getCustomerInfo
	*/
	public function getCustomerInfo(){
		$id = Input::get('cid');
		$cinfo = DB::table('gpg_customer')->select('*')->where('id','=',$id)->get();
		$customer_arr = array();
		foreach ($cinfo as $key1 => $value1) {
			foreach ($value1 as $key => $value) {
				$customer_arr[$key] = $value;
			}
		}
		return $customer_arr; 
	}
	/*
	* getDownloadFile
	*/
	public function getDownloadFile($id,$table){
		$data = DB::table('gpg_job_'.str_replace(' ', '_',strtolower($table)).'_quote_attachment')->where('id', '=',$id)->select('*')->get();
		$file_path = public_path()."/img/".@$data[0]->filename;
		if (file_exists($file_path)){
			$headers = array('Content-Type: application/vnd.ms-excel; charset=utf-8');
 		    return Response::download($file_path, 'info.xls',$headers);
        }else
        	return Redirect::route('quote/elec_quote_list')->withMessage('file not found');       
	}

	/*
	* deleteQuoteFile
	*/
	public function deleteQuoteFile(){
		$id = Input::get('id');
		$table = Input::get('table');
		DB::table('gpg_job_'.str_replace(' ', '_',strtolower($table)).'_quote_attachment')->where('id', '=',$id)->delete();
		return 1;
	}

	/*
	* Copy Quote Data
	*/
	public function CopyQuoteData(){
		$jobElecQuoteQuery = array();
		$job_id = Input::get('job_id');
		$job_num = Input::get('job_num');
		$copy_id = Input::get('copy_id');
		$check_box = Input::get('check_box');
		$table = Input::get('table');
		if ($check_box == false) {
			$gpg_sales_tracking_job_electrical_quote = DB::table('gpg_job_'.$table.'_quote')->select('*')->where('id','=',$job_id)->get();
			$Select_job_electrical_quote = "GPG_customer_id, GPG_employee_id, project_name, project_address, project_city, project_state, project_zip, project_contact, project_phone, gen_cost, ats_cost, material_cost, labor_hour_rate, labor_hour, labor_cost, misc_percent, misc_cost, general_cost_total, general_margin, general_margin_total, general_net_total, delivery_labor_hour_rate, delivery_labor_hour, delivery_labor_hour_total, delivery_mileage_rate, delivery_mileage, delivery_mileage_total, freight_gen_cost, freight_ats_cost, freight_housing_cost, freight_tank_cost, freight_acc_cost, freight_total_cost, startup_labor_hour_rate, startup_labor_hour, startup_labor_hour_total, startup_mileage_rate, startup_mileage, startup_mileage_total, gen_ats_total, misc_total, startup_total, material_total, labor_total, delivery_total, freight_factor, freight_total, cost_gross_total, scope_of_work, margin_gross_total, grand_total_no_tax, tax_amount, tax_cost_total, grand_total";
			
			$copy_job_electrical_quote = DB::select(DB::raw("SELECT ".$Select_job_electrical_quote." FROM gpg_job_".$table."_quote WHERE id ='$copy_id'"));
			foreach ($copy_job_electrical_quote as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					$jobElecQuoteQuery[substr($key,0,strlen($key))] = $value;					
				}
			}
			$jobElecQutoeUpdate = DB::table('gpg_job_'.$table.'_quote')->where('id','=',$job_id)->update($jobElecQuoteQuery);
			if($table == 'electrical'){
					DB::table('gpg_job_'.$table.'_quote_other')->where('gpg_job_'.$table.'_quote_id','=',$job_id)->delete();
				$job_electrical_quote_other = DB::table('gpg_job_'.$table.'_quote_other')->select('*')->where('gpg_job_'.$table.'_quote_id','=',$copy_id)->get();	
				if (isset($job_electrical_quote_other) && !empty($job_electrical_quote_other)){
					foreach ($job_electrical_quote_other as $key => $value) {
						DB::table('gpg_job_'.$table.'_quote_other')->insert(array('gpg_job_'.$table.'_quote_id' =>$job_id,'other_charge_qty'=>$value->other_charge_qty,'other_charge_description'=>$value->other_charge_description,'other_charge_cost_price'=>$value->other_charge_cost_price,'created_on'=>date('Y-m-d'),'modified_on' =>date('Y-m-d')));
					}
				}
			}
			// update gpg_sales_tracking table
			$copy_gpg_sales_tracking_id = '';
			$copy_gpg_sales_tracking_id0 = DB::table('gpg_sales_tracking_job_'.$table.'_quote')->select('gpg_sales_tracking_id')->where('gpg_job_'.$table.'_quote_id','=',$copy_id)->get();
			if (!empty($copy_gpg_sales_tracking_id0) && isset($copy_gpg_sales_tracking_id0[0]->gpg_sales_tracking_id)){
				$copy_gpg_sales_tracking_id = $copy_gpg_sales_tracking_id0[0]->gpg_sales_tracking_id;
			}
			$gpg_sales_tracking_id = '';
			$gpg_sales_tracking_id0 = DB::table('gpg_sales_tracking_job_'.$table.'_quote')->select('gpg_sales_tracking_id')->where('gpg_job_'.$table.'_quote_id','=',$job_id)->get();
			if (!empty($gpg_sales_tracking_id0) && isset($gpg_sales_tracking_id0[0]->gpg_sales_tracking_id)){
				$gpg_sales_tracking_id = $gpg_sales_tracking_id0[0]->gpg_sales_tracking_id;
			}
			$gpg_sales_tracking_copy_data_arr = array();
			if($copy_gpg_sales_tracking_id){
				$gpg_sales_tracking_copy_data = DB::table('gpg_sales_tracking')->select('projected_sale_price','labor_cost','material_cost')->where('id','=',$job_id)->get();
				foreach ($gpg_sales_tracking_copy_data as $key => $value){
					$gpg_sales_tracking_copy_data_arr = array('projected_sale_price'=>$value->projected_sale_price,'labor_cost'=>$value->labor_cost,'material_cost'=>$value->material_cost);
				}
			}
	
			if($gpg_sales_tracking_copy_data_arr) {
				$typeofSale0 = DB::table('gpg_sales_tracking')->select('type_of_sale')->where('id','=',$gpg_sales_tracking_id)->get();  
				if(!empty($typeofSale0) && isset($typeofSale0[0]->type_of_sale)) {
					$typeofSale = $typeofSale0[0]->type_of_sale;
					if($typeofSale==str_replace('_', ' ', ucfirst($table)) || $typeofSale=="Generators"){	
						DB::table('gpg_sales_tracking')->where('id','=',$gpg_sales_tracking_id)->update(array('projected_sale_price'=>$gpg_sales_tracking_copy_data_arr['projected_sale_price'],'labor_cost'=>$gpg_sales_tracking_copy_data_arr['labor_cost'],'material_cost'=>$gpg_sales_tracking_copy_data_arr['material_cost']));
					}
				}
			}
			// update gpg_job table
			$getDataCopyJobNumarr = array();
			$getJobNum0 = DB::table('gpg_job_'.$table.'_quote')->select('GPG_attach_job_num')->where('id','=',$job_id)->get(); 
			if (isset($getJobNum0[0]->GPG_attach_job_num) && !empty($getJobNum0)){
					$getJobNum = $getJobNum0[0]->GPG_attach_job_num;
					$active_job_num0 = DB::select(DB::raw("SELECT job_num FROM gpg_job_".$table."_quote WHERE IF(POSITION(\":\" IN job_num)=0,CONCAT(job_num,\":\"),job_num) LIKE '".(!strpos($job_num,":")?$job_num:substr($job_num,0,strlen($job_num)-3)).":%' GROUP BY ".$table."_status ORDER BY ".$table."_status DESC,job_num asc limit 0,1"));
					$active_job_num = '';
					if (isset($active_job_num0[0]->job_num) && !empty($active_job_num0)){
						$active_job_num = $active_job_num0[0]->job_num;
					}
					if($active_job_num = $job_num)
					{
						$getCopyJobNum0 = DB::table('gpg_job_'.$table.'_quote')->select('GPG_attach_job_num')->where('id','=',$copy_id)->get();
						$getCopyJobNum = '';
						if (isset($getCopyJobNum0[0]->GPG_attach_job_num) && !empty($getCopyJobNum0)){
							$getCopyJobNum = $getCopyJobNum0[0]->GPG_attach_job_num;
							$getDataCopyJobNum	= DB::select(DB::raw("select budgeted_labor, budgeted_hours, budgeted_material, contract_amount, location, address1, city, state, zip, job_site_contact, phone, task from gpg_job  WHERE job_num = '".$getCopyJobNum."'"));
							foreach ($getDataCopyJobNum as $key => $value) {
							   $getDataCopyJobNumarr = array('budgeted_labor'=>$value->budgeted_labor,'budgeted_hours'=>$value->budgeted_hours,'budgeted_material'=>$value->budgeted_material,'contract_amount'=>$value->contract_amount,'location'=>$value->location,'address1'=>$value->address1,'city'=>$value->city,'state'=>$value->state,'zip'=>$value->zip,'job_site_contact'=>$value->job_site_contact,'phone'=>$value->phone,'task'=>$value->task);
							}
							DB::table('gpg_job')->where('job_num','=',$getJobNum)->update(array('budgeted_labor'=>$getDataCopyJobNumarr['budgeted_labor'], 'budgeted_hours'=>$getDataCopyJobNumarr['budgeted_hours'], 'budgeted_material'=>$getDataCopyJobNumarr['budgeted_material'], 'contract_amount'=>$getDataCopyJobNumarr['contract_amount'], 'location'=>$getDataCopyJobNumarr['location'], 'address1'=>$getDataCopyJobNumarr['address1'], 'city'=>$getDataCopyJobNumarr['city'], 'state'=>$getDataCopyJobNumarr['state'], 'zip'=>$getDataCopyJobNumarr['zip'], 'job_site_contact'=>$getDataCopyJobNumarr['job_site_contact'], 'phone'=>$getDataCopyJobNumarr['phone'], 'task'=>$getDataCopyJobNumarr['task']));
						}
					}
			}
		}else{ 
			$not_copy_fields_gpg_job_electrical_quote = "'id','created_on','".$table."_status','date_job_won','modified_on','job_num','location'";
			$all_fields_res = DB::select(DB::raw("SHOW FIELDS FROM gpg_job_".$table."_quote WHERE Field NOT IN (".$not_copy_fields_gpg_job_electrical_quote.")"));
			$str_update_fields_gpg_job_electrical_quote = "";
			foreach ($all_fields_res as $key => $value) {
				$str_update_fields_gpg_job_electrical_quote .= $value->Field.",";
			}
			$copy_job_electrical_quote_id = '';
			$copy_job_electrical_quote_job_num = '';
			$str_update_fields_gpg_job_electrical_quote = substr($str_update_fields_gpg_job_electrical_quote,0,strlen($str_update_fields_gpg_job_electrical_quote)-1);
			$copy_job_electrical_quote_arr = DB::select(DB::raw("SELECT id,job_num FROM gpg_job_".$table."_quote WHERE id = '".$copy_id."'"));
			if (isset($copy_job_electrical_quote_arr) && !empty($copy_job_electrical_quote_arr)) {
				$copy_job_electrical_quote_id = $copy_job_electrical_quote_arr[0]->id;
				$copy_job_electrical_quote_job_num = $copy_job_electrical_quote_arr[0]->job_num;			
			}

			$getMaxElectricalQuote = '';	
			$getMaxElectricalQuoteid = '';
			$getMaxElectricalQuote_arr = DB::select(DB::raw("select id,job_num from gpg_job_".$table."_quote where concat(job_num,':') like '".$copy_job_electrical_quote_job_num.":%' order by id desc limit 0,1"));
			if (isset($getMaxElectricalQuote_arr) && !empty($getMaxElectricalQuote_arr)){
				$getMaxElectricalQuote = $getMaxElectricalQuote_arr[0]->job_num;
				$getMaxElectricalQuoteid = $getMaxElectricalQuote_arr[0]->id;			
			}

			$newElectricalQuote = explode(":",$getMaxElectricalQuote);
			if (empty($newElectricalQuote[1])) 
				$newElectricalQuote1 = $newElectricalQuote[0].":01";
			else 
				$newElectricalQuote1 = $newElectricalQuote[0].":".str_pad((int) $newElectricalQuote[1]=$newElectricalQuote[1]+1,2,"0",STR_PAD_LEFT);
		 	$dataArr = array();
			$select_data = DB::select(DB::raw("select $str_update_fields_gpg_job_electrical_quote FROM gpg_job_".$table."_quote WHERE id='".$getMaxElectricalQuoteid."'"));
			foreach ($select_data as $keyD => $valueData) {
				foreach ($valueData as $key => $value) {
					$dataArr[$key] = $value;
				}
			}
			$merger_arr = array('job_num'=>$newElectricalQuote1,$table.'_status'=>'Quote', 'created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d'));
			$dataArr = $dataArr + $merger_arr;
			DB::table('gpg_job_'.$table.'_quote')->insert($dataArr);
		 	$inserted_quote_id = DB::table('gpg_job_'.$table.'_quote')->max('id');
		 	
		 	// Copy electrical quote other data
			$not_copy_fields_gpg_job_electrical_quote = "'id','gpg_job_".$table."_quote_id','created_on','modified_on'";
			$all_fields_res = DB::select(DB::raw("SHOW FIELDS FROM gpg_job_".$table."_quote_other WHERE Field NOT IN (".$not_copy_fields_gpg_job_electrical_quote.")"));
			$str_update_fields_gpg_job_electrical_quote = "";
			foreach ($all_fields_res as $key => $value) {
				$str_update_fields_gpg_job_electrical_quote .= $value->Field.",";
			}
			$str_update_fields_gpg_job_electrical_quote = substr($str_update_fields_gpg_job_electrical_quote,0,strlen($str_update_fields_gpg_job_electrical_quote)-1);
			$upd_qry = DB::select(DB::raw("SELECT ".$str_update_fields_gpg_job_electrical_quote.", '".$inserted_quote_id."' as gpg_job_".$table."_quote_id FROM gpg_job_".$table."_quote_other WHERE gpg_job_".$table."_quote_id = '$copy_id'"));
			$data_arr2 = array();
			if (isset($upd_qry) && !empty($upd_qry)) {
				foreach ($upd_qry as $keyU => $valueU) {
					foreach ($valueU as $key => $value) {
						$data_arr2[$key] = $value;
					}
				}
			}
			$merger_arr2 = array('created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d'));
			$data_arr = $data_arr2 + $merger_arr2;
			if (!empty($data_arr2))
				DB::table('gpg_job_'.$table.'_quote_other')->insert($data_arr);
			
			// Copy Electrical Quote Material and labor data
			$not_copy_fields_gpg_job_electrical_quote = "'id','gpg_job_".$table."_quote_id','created_on','modified_on'";
			$all_fields_res = DB::select(DB::raw("SHOW FIELDS FROM gpg_job_".$table."_quote_labor_material_list WHERE Field NOT IN (".$not_copy_fields_gpg_job_electrical_quote.")"));
			$str_update_fields_gpg_job_electrical_quote = "";
			foreach ($all_fields_res as $key => $value) {
				$str_update_fields_gpg_job_electrical_quote .= $value->Field.",";
			}
			$str_update_fields_gpg_job_electrical_quote = substr($str_update_fields_gpg_job_electrical_quote,0,strlen($str_update_fields_gpg_job_electrical_quote)-1);
			$upd_qry2 = Db::select(DB::raw("SELECT ".$str_update_fields_gpg_job_electrical_quote.", '".$inserted_quote_id."' as gpg_job_".$table."_quote_id, NOW() as created_on, NOW() as modified_on FROM gpg_job_".$table."_quote_labor_material_list WHERE gpg_job_".$table."_quote_id = '$copy_id'"));
			$data_arr3 = array();
			if (isset($upd_qry) && !empty($upd_qry)) {
				foreach ($upd_qry2 as $keyU2 => $valueU2) {
					foreach ($valueU2 as $key => $value) {
						$data_arr3[$key] = $value;
					}
				}
			}
			if (!empty($data_arr3))
				DB::table('gpg_job_'.$table.'_quote_labor_material_list')->insert($data_arr3);

			/// Copy Electrical Quote Attachments
			$not_copy_fields_gpg_job_electrical_quote = "'id','gpg_job_".$table."_quote_id','created_on','modified_on'";
			$all_fields_res = DB::select(DB::raw("SHOW FIELDS FROM gpg_job_".$table."_quote_attachment WHERE Field NOT IN (".$not_copy_fields_gpg_job_electrical_quote.")"));
			$str_update_fields_gpg_job_electrical_quote = "";
			$all_fields_arr = array();
			foreach ($all_fields_res as $key => $value) {
				$str_update_fields_gpg_job_electrical_quote .= $value->Field.",";
			}
			$str_update_fields_gpg_job_electrical_quote = substr($str_update_fields_gpg_job_electrical_quote,0,strlen($str_update_fields_gpg_job_electrical_quote)-1);
			$upd_qry3 = DB::select(DB::raw("SELECT ".$str_update_fields_gpg_job_electrical_quote.", '".$inserted_quote_id."' as gpg_job_".$table."_quote_id, NOW() as created_on, NOW() as modified_on FROM gpg_job_".$table."_quote_attachment WHERE gpg_job_".$table."_quote_id = '$copy_id'"));
			$upd3Data = array();
			foreach ($upd_qry3 as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					$upd3Data[$key] = $value;
				}
			}
			if (!empty($upd3Data))
				DB::table('gpg_job_'.$table.'_quote_attachment')->insert($upd3Data);

			// Copy Subquote data
			$not_copy_fields_gpg_job_electrical_quote = "'id','job_".$table."_quote_id'";
			$all_fields_res2 = DB::select(DB::raw("SHOW FIELDS FROM gpg_job_".$table."_subquote WHERE Field NOT IN (".$not_copy_fields_gpg_job_electrical_quote.")"));
			$str_update_fields_gpg_job_electrical_quote = "";
			foreach ($all_fields_res2 as $key => $value) {
				$str_update_fields_gpg_job_electrical_quote .= $value->Field.",";
			}
			$str_update_fields_gpg_job_electrical_quote = substr($str_update_fields_gpg_job_electrical_quote,0,strlen($str_update_fields_gpg_job_electrical_quote)-1);
			$upd_qry4 = DB::select(Db::raw("SELECT ".$str_update_fields_gpg_job_electrical_quote.", '".$inserted_quote_id."' as job_".$table."_quote_id FROM gpg_job_".$table."_subquote WHERE job_".$table."_quote_id = '$copy_id'"));
			$upd4Data = array();
			foreach ($upd_qry4 as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					$upd4Data[$key] = $value;
				}
			}
			if(!empty($upd4Data))
				DB::table('gpg_job_'.$table.'_subquote')->insert($upd4Data);

			// Copy Pricing Data Equipment
			$not_copy_fields_gpg_job_electrical_quote = "'id','gpg_job_".$table."_quote_id','created_on','modified_on'";
			$all_fields_res3 = DB::select(DB::raw("SHOW FIELDS FROM gpg_job_".$table."_equipment_pricing WHERE Field NOT IN (".$not_copy_fields_gpg_job_electrical_quote.")"));
			$str_update_fields_gpg_job_electrical_quote = "";
			foreach ($all_fields_res3 as $key => $value) {
				$str_update_fields_gpg_job_electrical_quote .= $value->Field.",";	
			}
			$str_update_fields_gpg_job_electrical_quote = substr($str_update_fields_gpg_job_electrical_quote,0,strlen($str_update_fields_gpg_job_electrical_quote)-1);
			$upd_qry5 =  DB::select(DB::raw("SELECT ".$str_update_fields_gpg_job_electrical_quote.", '".$inserted_quote_id."' as gpg_job_".$table."_quote_id, NOW() as created_on, NOW() as modified_on FROM gpg_job_".$table."_equipment_pricing WHERE gpg_job_".$table."_quote_id = '$copy_id'")); 
			$upd5Data = array();
			foreach ($upd_qry5 as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					$upd5Data[$key] = $value;
				}
			}
			if(!empty($upd5Data))
				DB::table('gpg_job_'.$table.'_equipment_pricing')->insert($upd5Data);

			// Copy Pricing Data Labor
			$not_copy_fields_gpg_job_electrical_quote = "'id','gpg_job_".$table."_quote_id','created_on','modified_on'";
			$all_fields_res4 =  DB::select(DB::raw("SHOW FIELDS FROM gpg_job_".$table."_labor_pricing WHERE Field NOT IN (".$not_copy_fields_gpg_job_electrical_quote.")"));
			$str_update_fields_gpg_job_electrical_quote = "";
			foreach ($all_fields_res4 as $key => $value) {
				$str_update_fields_gpg_job_electrical_quote .= $value->Field.",";
			}
			$str_update_fields_gpg_job_electrical_quote = substr($str_update_fields_gpg_job_electrical_quote,0,strlen($str_update_fields_gpg_job_electrical_quote)-1);
			$upd_qry6 = DB::select(DB::raw("SELECT ".$str_update_fields_gpg_job_electrical_quote.", '".$inserted_quote_id."' as gpg_job_".$table."_quote_id, NOW() as created_on, NOW() as modified_on FROM gpg_job_".$table."_labor_pricing WHERE gpg_job_".$table."_quote_id = '$copy_id'"));
			$upd6Data = array();
			foreach ($upd_qry6 as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					$upd6Data[$key] = $value;
				}
			}
			if(!empty($upd6Data))
				DB::table('gpg_job_'.$table.'_labor_pricing')->insert($upd6Data);

			// Copy Pricing Data Labor
			$not_copy_fields_gpg_job_electrical_quote = "'id','gpg_job_".$table."_quote_id','created_on','modified_on'";
			$all_fields_res5 = DB::select(DB::raw("SHOW FIELDS FROM gpg_job_".$table."_misc_pricing WHERE Field NOT IN (".$not_copy_fields_gpg_job_electrical_quote.")"));
			$str_update_fields_gpg_job_electrical_quote = "";
			foreach ($all_fields_res5 as $key => $value) {
				$str_update_fields_gpg_job_electrical_quote .= $value->Field.",";
			}
			$str_update_fields_gpg_job_electrical_quote = substr($str_update_fields_gpg_job_electrical_quote,0,strlen($str_update_fields_gpg_job_electrical_quote)-1);
			$upd_qry7 = DB::select(DB::raw("SELECT ".$str_update_fields_gpg_job_electrical_quote.", '".$inserted_quote_id."' as job_".$table."_quote_id, NOW() as created_on, NOW() as modified_on FROM gpg_job_".$table."_misc_pricing WHERE gpg_job_".$table."_quote_id = '$copy_id'"));
			$upd7Data = array();
			foreach ($upd_qry7 as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					$upd7Data[$key] = $value;
				}
			}
			if(!empty($upd7Data))
				DB::table('gpg_job_'.$table.'_misc_pricing')->insert($upd7Data);

			// Copy Pricing Data Totals
			$not_copy_fields_gpg_job_electrical_quote = "'id','gpg_job_".$table."_quote_id'";	
			$all_fields_res8 = DB::select(DB::raw("SHOW FIELDS FROM gpg_job_".$table."_pricing_totals WHERE Field NOT IN (".$not_copy_fields_gpg_job_electrical_quote.")"));
			$str_update_fields_gpg_job_electrical_quote = "";
			foreach ($all_fields_res8 as $key => $value) {
				$str_update_fields_gpg_job_electrical_quote .= $value->Field.",";
			}
			$str_update_fields_gpg_job_electrical_quote = substr($str_update_fields_gpg_job_electrical_quote,0,strlen($str_update_fields_gpg_job_electrical_quote)-1);
			$upd_qry8 = DB::select(DB::raw("SELECT ".$str_update_fields_gpg_job_electrical_quote.", '".$inserted_quote_id."' as gpg_job_".$table."_quote_id FROM gpg_job_".$table."_pricing_totals WHERE gpg_job_".$table."_quote_id = '$copy_id'"));
			$upd8Data = array();
			foreach ($upd_qry8 as $key2 => $value2){
				foreach ($value2 as $key => $value){
					$upd8Data[$key] = $value;
				}
			}
			if(!empty($upd8Data))
				DB::table('gpg_job_'.$table.'_pricing_totals')->insert($upd8Data);
			
			$gpg_sales_trackings = DB::select(DB::raw("SELECT gpg_sales_tracking_id FROM gpg_sales_tracking_job_".$table."_quote WHERE gpg_job_".$table."_quote_id = '$copy_id'"));
			if(!empty($gpg_sales_trackings) && isset($gpg_sales_trackings[0]->gpg_sales_tracking_id)){
				$gpg_sales_tracking_id = $gpg_sales_trackings[0]->gpg_sales_tracking_id;
				DB::table('gpg_sales_tracking_job_'.$table.'_quote')->insert(array('gpg_job_'.$table.'_quote_id'=>$inserted_quote_id,'gpg_sales_tracking_id'=>$gpg_sales_tracking_id));
			}
		}
		
		return 1;

	}
	/*
	* jobElectricalEquipForm
	*/
	public function jobElectricalEquipForm($id,$j_num){
		if($j_num[0] == 'J')
			$table = 'special_project';
		elseif($j_num[0] == 'M'){
			$table = 'grassivy';
		}
		else
			$table = 'electrical';
		$modules = Generic::modules();
		$query_data = DB::select(DB::raw("select * from gpg_job_".$table."_quote where id = '$id'"));
		$eqppricing = DB::select(DB::raw("select * from gpg_job_".$table."_equipment_pricing where gpg_job_".$table."_quote_id = '".$id."' order by equipment_order ASC"));
		$getElectricalEquipmentPricing = array();
		foreach ($eqppricing as $key0 => $value0){
			foreach ($value0 as $key => $value) {
				$temp_equip[$key] = $value;	
			}
			$getElectricalEquipmentPricing[] = $temp_equip;
		}
		$Quote_Data = array();
		foreach ($query_data as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				if ($key == 'GPG_customer_id') {
					$Quote_Data['customer_drop_down'] = DB::table('gpg_customer')->select('id','name')->lists('name','id');
					$Quote_Data['salesPerson_drop_down'] = DB::table('gpg_employee')->select('id','name')->lists('name','id');
					$Quote_Data['estimator_drop_down'] = DB::table('gpg_employee')->select('id','name')->where('frontend', 'like', '%sales%')->orderBy('name', 'asc')->lists('name','id');
					if (!empty($value)){
						$QData = DB::table('gpg_customer')->select('*')->where('id','=',$value)->get();
						$QdataArr = array();
						foreach ($QData as $key3 => $value3) {
							foreach ($value3 as $key4 => $value4) {
								$QdataArr[$key4] = $value4;
							}
						}
						$Quote_Data['customer_info'] = $QdataArr;	
					}
					else
						$Quote_Data['customer_info'] = array();
				}
				if ($key == 'contact_info_id'){
					$contact_info = DB::table('gpg_settings')->where('name', 'like', '_ContactInfo%')->orderBy('value', 'asc')->lists('value', 'id');
					$terms = DB::table('gpg_settings')->where('name', 'like', '_TermsAndConditions%')->orderBy('value', 'asc')->lists('value', 'id');
					$Quote_Data['contact_info'] = $contact_info;
					$Quote_Data['terms'] = $terms;
				}
				if($key == $table.'_status'){
					$Quote_Data['jobTypeStatus'] = $value; 
				}
				if($key == $table.'_qote_stage_id'){
					$Quote_Data['qote_stage_id'] = $value; 
				}
				if($key == $table.'_quote_type' || $key == substr($table, 0, 3).'_quote_type'){
					$Quote_Data['quote_type'] = $value; 
				}
				if($key == $table.'_quote_status' || $key == substr($table, 0, 3).'_quote_status'){
					$Quote_Data['quote_status'] = $value; 
				}
				if (is_float($value))
					$Quote_Data[$key] = number_format($value,2);
				else
					$Quote_Data[$key] = $value; 
			}
		}
		$gpg_settings = DB::table('gpg_settings')->where('name', 'like', '_ElectricalQuoteStage%')->orderBy('value', 'asc')->lists('id', 'value');
		$gpg_settings = array('' => 'Select Stage') + $gpg_settings;
		ksort($this->elecJobTypeArray);
		$arrayTemp = array();
		if ($table == 'electrical') {
			$gen_cost = DB::select(DB::raw("select * FROM gpg_job_".$table."_quote_labor_material_list WHERE  gpg_job_".$table."_quote_id = '$id' order by id"));
			foreach ($gen_cost as $keyGen => $valueGen) {
				foreach ($valueGen as $key => $value) {
					$arrayGenCost[$key] = $value;
				}
				$arrayTemp[] = $arrayGenCost; 
			}	
		}
		
		$res = DB::select(DB::raw("select id,concat(job_num,' ',ifnull((select name from gpg_customer where id = GPG_customer_id),''),'&nbsp; | &nbsp;',ifnull(project_name,''),'&nbsp; | &nbsp;',ifnull(project_address,''),'') as name from gpg_job_".$table."_quote ORDER BY job_num ASC"));
		$list_quotes = array();
		foreach ($res as $key => $value){
			$list_quotes[$value->id] = $value->name; 
		}

		$quotes_ids = DB::select(DB::raw("SELECT id,job_num FROM gpg_job_".$table."_quote WHERE if(POSITION(\":\" IN job_num)=0,concat(job_num,\":\"),job_num) LIKE '".(!strpos($j_num,":")?$j_num:substr($j_num,0,strlen($j_num)-3)).":%'"));
		$quote_ids_arr = array();
		foreach ($quotes_ids as $key => $value) {
			$quote_ids_arr[$value->id] = $value->job_num; 
		}
		$gpg_vendor = DB::table('gpg_vendor')->select('id','name')->where('status', '=', 'A')->orderBy('name', 'asc')->lists('name','id');
		$TotalQry = DB::table('gpg_job_'.$table.'_pricing_totals')->select('*')->where('gpg_job_'.$table.'_quote_id','=',$id)->get();		
		$Totals = array();
		foreach ($TotalQry as $key0 => $value0) {
			foreach ($value0 as $key => $value) {
				$Totals[$key] = $value;
			}
		}

		$getElectricalLaborPricing = DB::select(DB::raw("select * from gpg_job_".$table."_labor_pricing where gpg_job_".$table."_quote_id = '".$id."' order by labor_order ASC"));
		$getELP = array();
		foreach ($getElectricalLaborPricing as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$temp_arry[$key] = $value; 
			}
			$getELP[] = $temp_arry;
		}
		$getElectricalMiscPricing = DB::select(DB::raw("select * from gpg_job_".$table."_misc_pricing where gpg_job_".$table."_quote_id = '".$id."' order by misc_order ASC"));
		$getEMP = array();
		foreach ($getElectricalMiscPricing as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$temp_arry2[$key] = $value; 
			}
			$getEMP[] = $temp_arry2;
		}
        $EquipmentTaxable = DB::table('gpg_job_'.$table.'_equipment_pricing')->where('gpg_job_'.$table.'_quote_id','=',$id)->where('equipment_include_tax','=',1)->sum('equipment_sell_price');
	    $LaborTaxable = DB::table('gpg_job_'.$table.'_labor_pricing')->where('gpg_job_'.$table.'_quote_id','=',$id)->where('labor_include_tax','=',1)->sum('labor_sell_price');
	    $MiscTaxable = DB::table('gpg_job_'.$table.'_misc_pricing')->where('gpg_job_'.$table.'_quote_id','=',$id)->where('misc_include_tax','=',1)->sum('misc_sell_price');
	    $TotalTaxable =  $EquipmentTaxable + $LaborTaxable + $MiscTaxable;
     	$params = array('left_menu' => $modules,'job_id'=>$id,'job_num'=>$j_num,'jobElectricalQuoteTblRow'=>$Quote_Data,'gpg_settings'=>$gpg_settings,'elecJobTypeArray'=>$this->elecJobTypeArray,'getElectricalEquipmentPricing'=>$getElectricalEquipmentPricing,'gen_cost'=>$arrayTemp,'list_quotes'=>$list_quotes,'quote_ids_arr'=>$quote_ids_arr,'gpg_vendor'=>$gpg_vendor,'Totals'=>$Totals,'getELP'=>$getELP,'getEMP'=>$getEMP,'TotalTaxable'=>$TotalTaxable);
 		return View::make('job.job_electrical_equipment_pricing_frm', $params);

	}
	/*
	* excelEquipQuoteExport
	*/
	public function excelEquipQuoteExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('EquipQuoteExportFile', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		   	
		   	$j_num = $_REQUEST['j_num'];
		   	$id = $_REQUEST['id'];
		   	if($j_num[0] == 'J')
				$table = 'special_project';
			elseif($j_num[0] == 'M'){
				$table = 'grassivy';
			}
			else{
				$table = 'electrical';
			}
			
			$modules = Generic::modules();
			$query_data = DB::select(DB::raw("select * from gpg_job_".$table."_quote where id = '$id'"));
			$eqppricing = DB::select(DB::raw("select * from gpg_job_".$table."_equipment_pricing where gpg_job_".$table."_quote_id = '".$id."' order by equipment_order ASC"));
			$getElectricalEquipmentPricing = array();
			foreach ($eqppricing as $key0 => $value0){
				foreach ($value0 as $key => $value) {
					$temp_equip[$key] = $value;	
				}
				$getElectricalEquipmentPricing[] = $temp_equip;
			}
			$Quote_Data = array();
			foreach ($query_data as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					if ($key == 'GPG_customer_id') {
						$Quote_Data['customer_drop_down'] = DB::table('gpg_customer')->select('id','name')->lists('name','id');
						$Quote_Data['salesPerson_drop_down'] = DB::table('gpg_employee')->select('id','name')->lists('name','id');
						$Quote_Data['estimator_drop_down'] = DB::table('gpg_employee')->select('id','name')->where('frontend', 'like', '%sales%')->orderBy('name', 'asc')->lists('name','id');
						if (!empty($value)){
							$QData = DB::table('gpg_customer')->select('*')->where('id','=',$value)->get();
							$QdataArr = array();
							foreach ($QData as $key3 => $value3) {
								foreach ($value3 as $key4 => $value4) {
									$QdataArr[$key4] = $value4;
								}
							}
							$Quote_Data['customer_info'] = $QdataArr;	
						}
						else
							$Quote_Data['customer_info'] = array();
					}
					if ($key == 'contact_info_id'){
						$contact_info = DB::table('gpg_settings')->where('name', 'like', '_ContactInfo%')->orderBy('value', 'asc')->lists('value', 'id');
						$terms = DB::table('gpg_settings')->where('name', 'like', '_TermsAndConditions%')->orderBy('value', 'asc')->lists('value', 'id');
						$Quote_Data['contact_info'] = $contact_info;
						$Quote_Data['terms'] = $terms;
					}
					if($key == $table.'_status'){
						$Quote_Data['jobTypeStatus'] = $value; 
					}
					if($key == $table.'_qote_stage_id'){
						$Quote_Data['qote_stage_id'] = $value; 
					}
					if($key == $table.'_quote_type' || $key == substr($table, 0, 3).'_quote_type'){
						$Quote_Data['quote_type'] = $value; 
					}
					if($key == $table.'_quote_status' || $key == substr($table, 0, 3).'_quote_status'){
						$Quote_Data['quote_status'] = $value; 
					}
					if (is_float($value))
						$Quote_Data[$key] = number_format($value,2);
					else
						$Quote_Data[$key] = $value; 
				}
			}
			$gpg_settings = DB::table('gpg_settings')->where('name', 'like', '_ElectricalQuoteStage%')->orderBy('value', 'asc')->lists('id', 'value');
			$gpg_settings = array('' => 'Select Stage') + $gpg_settings;
			ksort($this->elecJobTypeArray);
			$arrayTemp = array();
			if ($table == 'electrical') {
				$gen_cost = DB::select(DB::raw("select * FROM gpg_job_".$table."_quote_labor_material_list WHERE  gpg_job_".$table."_quote_id = '$id' order by id"));
				foreach ($gen_cost as $keyGen => $valueGen) {
					foreach ($valueGen as $key => $value) {
						$arrayGenCost[$key] = $value;
					}
					$arrayTemp[] = $arrayGenCost; 
				}	
			}
			
			$res = DB::select(DB::raw("select id,concat(job_num,' ',ifnull((select name from gpg_customer where id = GPG_customer_id),''),'&nbsp; | &nbsp;',ifnull(project_name,''),'&nbsp; | &nbsp;',ifnull(project_address,''),'') as name from gpg_job_".$table."_quote ORDER BY job_num ASC"));
			$list_quotes = array();
			foreach ($res as $key => $value){
				$list_quotes[$value->id] = $value->name; 
			}

			$quotes_ids = DB::select(DB::raw("SELECT id,job_num FROM gpg_job_".$table."_quote WHERE if(POSITION(\":\" IN job_num)=0,concat(job_num,\":\"),job_num) LIKE '".(!strpos($j_num,":")?$j_num:substr($j_num,0,strlen($j_num)-3)).":%'"));
			$quote_ids_arr = array();
			foreach ($quotes_ids as $key => $value) {
				$quote_ids_arr[$value->id] = $value->job_num; 
			}
			$gpg_vendor = DB::table('gpg_vendor')->select('id','name')->where('status', '=', 'A')->orderBy('name', 'asc')->lists('name','id');
			$TotalQry = DB::table('gpg_job_'.$table.'_pricing_totals')->select('*')->where('gpg_job_'.$table.'_quote_id','=',$id)->get();		
			$Totals = array();
			foreach ($TotalQry as $key0 => $value0) {
				foreach ($value0 as $key => $value) {
					$Totals[$key] = $value;
				}
			}

			$getElectricalLaborPricing = DB::select(DB::raw("select * from gpg_job_".$table."_labor_pricing where gpg_job_".$table."_quote_id = '".$id."' order by labor_order ASC"));
			$getELP = array();
			foreach ($getElectricalLaborPricing as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					$temp_arry[$key] = $value; 
				}
				$getELP[] = $temp_arry;
			}
			$getElectricalMiscPricing = DB::select(DB::raw("select * from gpg_job_".$table."_misc_pricing where gpg_job_".$table."_quote_id = '".$id."' order by misc_order ASC"));
			$getEMP = array();
			foreach ($getElectricalMiscPricing as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					$temp_arry2[$key] = $value; 
				}
				$getEMP[] = $temp_arry2;
			}
	        $EquipmentTaxable = DB::table('gpg_job_'.$table.'_equipment_pricing')->where('gpg_job_'.$table.'_quote_id','=',$id)->where('equipment_include_tax','=',1)->sum('equipment_sell_price');
		    $LaborTaxable = DB::table('gpg_job_'.$table.'_labor_pricing')->where('gpg_job_'.$table.'_quote_id','=',$id)->where('labor_include_tax','=',1)->sum('labor_sell_price');
		    $MiscTaxable = DB::table('gpg_job_'.$table.'_misc_pricing')->where('gpg_job_'.$table.'_quote_id','=',$id)->where('misc_include_tax','=',1)->sum('misc_sell_price');
		    $TotalTaxable =  $EquipmentTaxable + $LaborTaxable + $MiscTaxable;
			$params = array('left_menu' => $modules,'job_id'=>$id,'job_num'=>$j_num,'jobElectricalQuoteTblRow'=>$Quote_Data,'gpg_settings'=>$gpg_settings,'elecJobTypeArray'=>$this->elecJobTypeArray,'getElectricalEquipmentPricing'=>$getElectricalEquipmentPricing,'gen_cost'=>$arrayTemp,'list_quotes'=>$list_quotes,'quote_ids_arr'=>$quote_ids_arr,'gpg_vendor'=>$gpg_vendor,'Totals'=>$Totals,'getELP'=>$getELP,'getEMP'=>$getEMP,'TotalTaxable'=>$TotalTaxable,'table'=>$table);
 
		        $sheet->loadView('job.excelEquipQuoteExport',$params);
		    });
		})->export('xls');
	}

	/*
	* updateElectricQuoteFrm
	*/
	public function updateElectricQuoteFrm(){
		$job_electrical_quote_id = Input::get('job_id');
    	$jobNum = Input::get('job_num');
		$TotalLinesOtherCharges = Input::get('otherCounter');
		$active_job_num = '';
		$active_job_num0 = DB::select(DB::raw("SELECT job_num FROM gpg_job_electrical_quote WHERE IF(POSITION(\":\" IN job_num)=0,CONCAT(job_num,\":\"),job_num) LIKE '".(!strpos($jobNum,":")?$jobNum:substr($jobNum,0,strlen($jobNum)-3)).":%' GROUP BY electrical_status ORDER BY electrical_status DESC,job_num asc limit 0,1"));
		if (!empty($active_job_num0))
			$active_job_num = $active_job_num0[0]->job_num;
		$gpg_sales_tracking_id = DB::table('gpg_sales_tracking_job_electrical_quote')->where('gpg_job_electrical_quote_id','=',$job_electrical_quote_id)->pluck('gpg_sales_tracking_id');
		$cusFields = array("address"=>"cusAddress1","address2"=>"cusAddress2","city"=>"cusCity","state"=>"cusState","zipcode"=>"cusZip","phone_no"=>"cusPhone","attn"=>"cusAtt");
		$jobElecQuoteQuery = '';
		while (list($ke,$vl)= each($_POST)) {
	  		if (preg_match("/_filter_changed_on/i",$ke) || preg_match("/_estimated_close_date/i",$ke))
	  	 		$jobElecQuoteQuery[substr($ke,1,strlen($ke))]=($vl!=''?date('Y-m-d',strtotime($vl)):NULL);
	   		else if (preg_match("/^_/i",$ke)) 
	   			$jobElecQuoteQuery[substr($ke,1,strlen($ke))]=$vl;
		}
		unset($jobElecQuoteQuery['token']);
		$array2 = array('schedule_time'=>$_POST['schedule_time'], 'modified_on'=>date('Y-m-d'), 'contact_info_id'=>$_POST["contact_info_id"], 'terms_and_conditions_id'=>$_POST['terms_and_conditions_id']);
		DB::table('gpg_job_electrical_quote')->where('id','=',$job_electrical_quote_id)->update($jobElecQuoteQuery+$array2);
		$cusFieldQuery = array();
		while (list($key,$value)= each($cusFields)) {
			if ($value != 'cusAtt') 
		   		$cusFieldQuery[$key] = $_POST[$value];
		}
		$array3 = array('modified_on' => date('Y-m-d'));
		if($active_job_num==$jobNum)
			DB::table('gpg_customer')->where('id','=',$_POST['_GPG_customer_id'])->update($cusFieldQuery+$array3);
		$otherTotal = 0;
		for ($i=0; $i<=$TotalLinesOtherCharges; $i++) { 
	 		if(!empty($_POST['other_charge_description_'.$i])){
				if(empty($_POST['otherId_'.$i]) && DB::table('gpg_job_electrical_quote_other')->where('id','=',$_POST['otherId_'.$i])->count('id')==0)
	  			{
	   				$otherQuery = DB::table('gpg_job_electrical_quote_other')->insert(array('gpg_job_electrical_quote_id'=>$job_electrical_quote_id,'other_charge_qty'=>$_POST['other_charge_qty_'.$i], 'other_charge_description'=>$_POST['other_charge_description_'.$i], 'other_charge_cost_price'=>$_POST['other_charge_cost_price_'.$i], 'created_on'=>date('Y-m-d') , 'modified_on'=> date('Y-m-d')));
	  			}else{
					$otherQuery = DB::table('gpg_job_electrical_quote_other')->where('id','=',$_POST['otherId_'.$i])->update(array('gpg_job_electrical_quote_id'=> $job_electrical_quote_id, 'other_charge_qty'=>$_POST['other_charge_qty_'.$i], 'other_charge_description'=>$_POST['other_charge_description_'.$i], 'other_charge_cost_price'=>$_POST['other_charge_cost_price_'.$i], 'modified_on'=>date('Y-m-d'), 'contact_info_id'=>$_POST['contact_info_id'], 'terms_and_conditions_id'=> $_POST['terms_and_conditions_id']));
				}
			$otherTotal += $_POST['other_charge_cost_price_'.$i];
	  		} 
		} 

		$result_totals = DB::select(DB::raw("SELECT	(IFNULL(subquote_total_cost,0)) AS quoted_total, (IFNULL(subquote_material_cost,0)) AS quoted_material, (IFNULL(subquote_labor_cost,0)) AS quoted_labor, (IFNULL(subquote_labor_hour,0)) AS labor_hour, (select sum(gpg_job_invoice_info.invoice_amount) from gpg_job_invoice_info,gpg_job where gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job.link_job_num = '$jobNum') as InvAmt FROM gpg_job_electrical_quote WHERE id = '".$job_electrical_quote_id."'"));
		$temp_row = array();
		foreach ($result_totals as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$temp_row[$key]= $value;
			}
		}
		$typeofSale = DB::table('gpg_sales_tracking')->where('id','=',$gpg_sales_tracking_id)->pluck('type_of_sale');
		if($typeofSale=="Electrical" || $typeofSale=="Generators")
	 	{	
		    if($active_job_num==$jobNum)
				DB::table('gpg_sales_tracking')->where('id','=',$gpg_sales_tracking_id)->update(array('projected_sale_price'=> (($_POST["_grand_total"] + $temp_row['quoted_total'])-$temp_row['InvAmt']), 'labor_cost'=>($_POST['_labor_total']+$_POST['_misc_total']+$_POST['_startup_total']+$_POST['_delivery_total']+$_POST['_freight_total']+$otherTotal + $temp_row['quoted_labor']), 'material_cost'=>($_POST['_material_total']+$_POST['_gen_ats_total'] + $temp_row['quoted_material'])));
		}

		$getJobNum = DB::table('gpg_job_electrical_quote')->where('id','=',$job_electrical_quote_id)->pluck('GPG_attach_job_num');
		if($getJobNum){
			if($active_job_num==$jobNum)
				DB::table('')->where('job_num','=',$getJobNum)->update(array('budgeted_labor'=>($_POST['_labor_total']+$_POST['_misc_total']+$_POST['_startup_total']+$_POST['_delivery_total']+$_POST['_freight_total']+$otherTotal + $temp_row['quoted_labor']), 'budgeted_hours'=>($_POST['_labor_hour'] + $temp_row['labor_hour']), 'budgeted_material'=>($_POST['_material_total']+$_POST['_gen_ats_total'] + $temp_row['quoted_material']), 'contract_amount'=>(($_POST["_grand_total"] + $temp_row['quoted_total'])-$temp_row['InvAmt']), 'location'=> $_POST['_project_name'], 'address1'=> $_POST['_project_address'], 'city'=> $_POST['_project_city'], 'state'=>$_POST['_project_state'],'zip'=>$_POST['_project_zip'], 'job_site_contact'=>$_POST['_project_contact'], 'phone'=>$_POST['_project_phone'], 'task'=>$_POST['_scope_of_work']));   	  
		}

		// material total
	  	$grand_total_material = $_POST['_material_total']+$_POST['_gen_ats_total'];
		// labor total 
	 	$grand_total_labor = $_POST['_labor_total']+$_POST['_misc_total']+$_POST['_startup_total']+$_POST['_delivery_total']+$_POST['_freight_total']+$otherTotal;
		DB::table('gpg_job_electrical_quote')->where('id','=',$job_electrical_quote_id)->update(array('grand_total_material'=>$grand_total_material ,'grand_total_labor'=>$grand_total_labor, 'contact_info_id'=>$_POST['contact_info_id'], 'terms_and_conditions_id'=>$_POST['terms_and_conditions_id']));	 
	
		/*echo "<pre>";
		print_r($_POST);
		die();*/
		return Redirect::to('job/job_electrical_quote_frm/'.$_POST['job_id'].'/'.$_POST['job_num'].'');
	}

	/*
	* updateElectricQuotePricingFrm
	*/
	public function updateElectricQuotePricingFrm(){

		$job_electrical_quote_id = Input::get('job_id');
		$jobNum = Input::get('job_num');
		if($jobNum[0] == 'J')
			$table = 'special_project';
		elseif($jobNum[0] == 'M'){
			$table = 'grassivy';
		}
		else
			$table = 'electrical';

		$gpg_sales_tracking_id = DB::table('gpg_sales_tracking_job_'.$table.'_quote')->where('gpg_job_'.$table.'_quote_id','=',$job_electrical_quote_id)->pluck('gpg_sales_tracking_id');
		$cusFields = array("address"=>"cusAddress1","address2"=>"cusAddress2","city"=>"cusCity","state"=>"cusState","zipcode"=>"cusZip","phone_no"=>"cusPhone","attn"=>"cusAtt","estimated_close_date"=>"_estimated_close_date");
		$jobElecQuoteQuery = array();
		while (list($ke,$vl)= each($_POST)){
		  if (preg_match("/_filter_changed_on/i",$ke) || preg_match("/_estimated_close_date/i",$ke)) 
		  	$jobElecQuoteQuery[substr($ke,1,strlen($ke))] = ($vl!=''?date('Y-m-d',strtotime($vl)):NULL); 
		  else if (preg_match("/^_/i",$ke) && ($ke=='_scope_of_work' || $ke=='_exclusions')) 
		  	$jobElecQuoteQuery[substr($ke,1,strlen($ke))] = $vl;
		  else if (preg_match("/^_/i",$ke))
		    $jobElecQuoteQuery[substr($ke,1,strlen($ke))] = ($vl);
		}
		unset($jobElecQuoteQuery['token']);
		$jobElecQutoeUpdate = '';
		$jobElecQutoeUpdate = DB::table('gpg_job_'.$table.'_quote')->where('id','=',$job_electrical_quote_id)->update($jobElecQuoteQuery+array('schedule_time'=>$_POST['schedule_time'],'modified_on'=>date('Y-m-d'),'contact_info_id'=>$_POST['contact_info_id'],'terms_and_conditions_id'=>$_POST['terms_and_conditions_id']));
		$cusFieldQuery = array();
		if($jobElecQutoeUpdate)
		{
			while (list($key,$value)= each($cusFields)) {
				if ($value != 'cusAtt' && $value != '_estimated_close_date' ) 
			   		$cusFieldQuery[$key] = $_POST[$value];
			}
			DB::table('gpg_customer')->where('id','=',$_POST['_GPG_customer_id'])->update($cusFieldQuery+array('modified_on' => date('Y-m-d')));
		}
		$typeofSale = DB::table('gpg_sales_tracking')->where('id','=',$gpg_sales_tracking_id)->pluck('type_of_sale');
		$arrFields = array("vendor","quantity","description","cost","margin_percent","order","sell_price","total_cost","margin","include_tax","sell_price_cost");
	
		////////// ***** Equipments Table *******/////////////
		$rows_previous = DB::table('gpg_job_'.$table.'_equipment_pricing')->where('gpg_job_'.$table.'_quote_id','=',$job_electrical_quote_id)->count();
		$rows_new = $_POST['genCounter_id'] - $rows_previous;
		//insert new data first
		$partEquipmetQuery = array();
		if($rows_new>0){
			for($row_next=$rows_previous+1; $row_next<=$_POST['genCounter_id'];$row_next++){
				for ($j=0; $j< count($arrFields); $j++) {
				  	if($arrFields[$j]=='vendor'){
					 	$partEquipmetQuery = $partEquipmetQuery + array('gpg_vendor_id'=> $this->clear_num($_POST['Equipment_'.$arrFields[$j].'_'.$row_next])); 
					}else{		  
				 		$partEquipmetQuery = $partEquipmetQuery + array('equipment_'.$arrFields[$j] => $this->clear_num($_POST['Equipment_'.$arrFields[$j].'_'.$row_next])); 
					}
	  			}
	  			DB::table('gpg_job_'.$table.'_equipment_pricing')->insert($partEquipmetQuery+array('gpg_job_'.$table.'_quote_id'=>$job_electrical_quote_id,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'))); 
			}
		}
		//update old data for equipments
		$EquipmetpartQuery = array();
		if ($rows_previous>0) {
			for ($i=1; $i<=$rows_previous; $i++){
				$EquipmetpartQuery = array();
				for ($j=0; $j< count($arrFields); $j++){	
				 	if($arrFields[$j]=='vendor'){ 
						$EquipmetpartQuery = $EquipmetpartQuery + array('gpg_vendor_id'=>$_POST['Equipment_'.$arrFields[$j].'_'.$i]); 
					}else{
						$EquipmetpartQuery = $EquipmetpartQuery + array('equipment_'.$arrFields[$j] => $this->clear_num($_POST['Equipment_'.$arrFields[$j].'_'.$i])); 
					}
	  			}
	  			DB::table('gpg_job_'.$table.'_equipment_pricing')->where('id','=',$_POST['Equipment_id_'.$i])->update($EquipmetpartQuery+array('modified_on'=>date('Y-m-d')));
			}
		}
		////////// ***** Labor Table *******///////////// from process page line#118
		//$rows_previous2 = DB::table('gpg_job_'.$table.'_labor_pricing')->where('gpg_job_'.$table.'_quote_id','=',$job_electrical_quote_id)->count();
		DB::table('gpg_job_'.$table.'_labor_pricing')->where('gpg_job_'.$table.'_quote_id','=',$job_electrical_quote_id)->delete();
		//$rows_new2 = $_POST['labCounter_id'] - $rows_previous2;
		//insert new data first
		if(isset($_POST['labCounter_id']) && $_POST['labCounter_id']>0){
			for($row_next2=1; $row_next2<=$_POST['labCounter_id'];$row_next2++){
				$partLaborQuery = array();
				for ($j=0; $j< count($arrFields); $j++) {
				  	if($arrFields[$j]=='vendor' || $arrFields[$j]=='include_tax'){
					 	//xxxx $partEquipmetQuery = $partEquipmetQuery + array('gpg_vendor_id'=> $this->clear_num($_POST['Equipment_'.$arrFields[$j].'_'.$row_next])); 
					}else{		  
				 		$partLaborQuery = $partLaborQuery + array('labor_'.$arrFields[$j] => ($_POST['Labor_'.$arrFields[$j].'_'.$row_next2])); 
					}
	  			}
	  			DB::table('gpg_job_'.$table.'_labor_pricing')->insert($partLaborQuery+array('gpg_job_'.$table.'_quote_id'=>$job_electrical_quote_id,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'))); 
			}
		}
		////////// ***** Misc Table *******///////////// from process page line#118
		// $rows_previous3 = DB::table('gpg_job_'.$table.'_misc_pricing')->where('gpg_job_'.$table.'_quote_id','=',$job_electrical_quote_id)->count();
		// $rows_new3 = $_POST['miscCounter_id'] - $rows_previous3;
		DB::table('gpg_job_'.$table.'_misc_pricing')->where('gpg_job_'.$table.'_quote_id','=',$job_electrical_quote_id)->delete();
		//insert new data first
		if($_POST['miscCounter_id']>0){
			for($row_next3=1; $row_next3<=$_POST['miscCounter_id'];$row_next3++){
				$partMisctQuery = array();
				for ($j=0; $j< count($arrFields); $j++) {
				  	if($arrFields[$j]=='vendor'){
					 	$partMisctQuery = $partMisctQuery + array('gpg_vendor_id'=> $this->clear_num($_POST['Misc_'.$arrFields[$j].'_'.$row_next3])); 
					}else{		  
				 		$partMisctQuery = $partMisctQuery + array('misc_'.$arrFields[$j] => $this->clear_num($_POST['Misc_'.$arrFields[$j].'_'.$row_next3])); 
					}
	  			}
	  			DB::table('gpg_job_'.$table.'_misc_pricing')->insert($partMisctQuery+array('gpg_job_'.$table.'_quote_id'=>$job_electrical_quote_id,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'))); 
			}
		}
		//update old data for misc
		/*$MiscpartQuery = array();
		if ($rows_previous3>0) {
			for ($i=1; $i<=$rows_previous3; $i++) { 
				$MiscpartQuery = array();
				for ($j=0; $j< count($arrFields); $j++) {	
				 	if($arrFields[$j]=='vendor'){ 
						$MiscpartQuery = $MiscpartQuery + array('gpg_vendor_id'=>$_POST['Misc_'.$arrFields[$j].'_'.$i]); 
					}else{
						$MiscpartQuery = $MiscpartQuery + array('misc_'.$arrFields[$j] => $this->clear_num($_POST['Misc_'.$arrFields[$j].'_'.$i])); 
					}
	  			}
	  			DB::table('gpg_job_'.$table.'_misc_pricing')->where('id','=',$_POST['Misc_id_'.$i])->update($MiscpartQuery+array('modified_on'=>date('Y-m-d')));
			}
		}*/
		/// Totals Records ///
		$partTotalsQuery = array('gpg_job_'.$table.'_quote_id'=>$job_electrical_quote_id, 'equipment_sell_price_total' => $this->clear_num($_POST['Equipment_sell_price_total']), 'equipment_total_cost_total'=> $this->clear_num($_POST['Equipment_total_cost_total']), 'equipment_margin_total' => $this->clear_num($_POST['Equipment_margin_total']), 'labor_sell_price_total' => $this->clear_num($_POST['Labor_sell_price_total']), 'labor_total_cost_total' => $this->clear_num($_POST['Labor_total_cost_total']), 'labor_margin_total' => $this->clear_num($_POST['Labor_margin_total']), 'misc_sell_price_total' => $this->clear_num($_POST['Misc_sell_price_total']), 'misc_total_cost_total' => $this->clear_num($_POST['Misc_total_cost_total']), 'misc_margin_total' => $this->clear_num($_POST['Misc_margin_total']), 'sales_tax' => $_POST['sales_tax']);
		$totalRecords = DB::table('gpg_job_'.$table.'_pricing_totals')->select('gpg_job_'.$table.'_quote_id')->where('gpg_job_'.$table.'_quote_id','=',$job_electrical_quote_id)->count();
		if($totalRecords >=1){
			DB::table('gpg_job_'.$table.'_pricing_totals')->where('gpg_job_'.$table.'_quote_id','=',$job_electrical_quote_id)->update($partTotalsQuery);
		} else {
			DB::table('gpg_job_'.$table.'_pricing_totals')->insert($partTotalsQuery);
		}
		$getJobNum = DB::table('gpg_job_'.$table.'_quote')->where('id','=',$job_electrical_quote_id)->pluck('GPG_attach_job_num');
		$result_totals = DB::select(DB::raw("SELECT 
											(IFNULL(subquote_total_cost,0)) AS quoted_total, 
											(IFNULL(subquote_material_cost,0)) AS quoted_material, 
											(IFNULL(subquote_labor_cost,0)) AS quoted_labor,
											(IFNULL(subquote_labor_hour,0)) AS labor_hour,
											(select sum(gpg_job_invoice_info.invoice_amount) from gpg_job_invoice_info,gpg_job where gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job.link_job_num = '$getJobNum') as InvAmt  
										FROM gpg_job_".$table."_quote WHERE id = '".$job_electrical_quote_id."'"));
		foreach ($result_totals as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$temp_row[$key] = $value;		
			}
		}
		$active_job_num = '';
		$active_job_num0 = DB::select(DB::raw("SELECT job_num FROM gpg_job_".$table."_quote WHERE IF(POSITION(\":\" IN job_num)=0,CONCAT(job_num,\":\"),job_num) LIKE '".(!strpos($jobNum,":")?$jobNum:substr($jobNum,0,strlen($jobNum)-3)).":%' GROUP BY ".$table."_status ORDER BY ".$table."_status DESC,job_num asc limit 0,1"));
		
		if (!empty($active_job_num)){
			$active_job_num = $active_job_num0[0]->job_num;
		}
	  	if($typeofSale == str_replace('_', ' ', ucfirst($table)) || $typeofSale=="Generators")
	    {	
		 	if($active_job_num==$jobNum)
		 		DB::table('gpg_sales_tracking')->where('id','=',$gpg_sales_tracking_id)->update(array('projected_sale_price'=>(($this->clear_num($_POST["tax_total_sale_price"]) +  $temp_row['quoted_total'])-$temp_row['InvAmt']),'labor_cost'=>($this->clear_num($_POST['Labor_total_cost_total']) +  $temp_row['quoted_labor']),'material_cost'=>($this->clear_num($_POST['Equipment_total_cost_total']) + $temp_row['quoted_material'])));
		}
		DB::table('gpg_job_'.$table.'_quote')->where('id','=',$job_electrical_quote_id)->update(array(/*'consolidated'=>$_POST['consolidate'],*/ 'global_margin'=>$_POST['GlobalMargin'], 'grand_total_no_tax'=>$this->clear_num($_POST['Grand_total_sale_price']), 'grand_total'=>$this->clear_num($_POST['tax_total_sale_price']), 'grand_total_material'=>$this->clear_num($_POST['Equipment_total_cost_total']), 'grand_total_labor'=>$this->clear_num($_POST['Labor_total_cost_total']) ,'margin_gross_total'=>$this->clear_num($_POST['tax_total_margin']), 'contact_info_id'=>$_POST['contact_info_id'], 'terms_and_conditions_id'=>$_POST['terms_and_conditions_id']));
	  	if($getJobNum) {
			$update_job_table = false;
		 	if($active_job_num==$jobNum)
		 		DB::table('gpg_job')->where('job_num','=',$getJobNum)->update(array('budgeted_labor'=>($this->clear_num($_POST('Labor_total_cost_total')) + $temp_row['quoted_labor']), 'budgeted_hours'=>( $this->clear_num($_POST['Labor_quantity_total']) + $temp_row['labor_hour']), 'budgeted_material'=>($this->clear_num($_POST['Equipment_total_cost_total']) + $temp_row['quoted_material']), 'contract_amount'=>(($this->clear_num($_POST["tax_total_sale_price"]) +  $temp_row['quoted_total'])-$temp_row['InvAmt']), 'location'=> $_POST['_project_name'], 'address1'=> $_POST['_project_address'], 'city'=>$_POST['_project_city'], 'state'=>$_POST['_project_state'],'zip'=>$_POST['_project_zip'], 'job_site_contact'=>$_POST['_project_contact'], 'phone'=>$_POST['_project_phone'], 'task'=>$_POST['_scope_of_work'])); 
		} 
		return Redirect::to('job/job_'.$table.'_equipment_pricing_frm/'.$_POST['job_id'].'/'.$_POST['job_num'].'');
	}

	/*
	* jobElectricalSubquoteForm
	*/
	public function jobElectricalSubquoteForm($job_id,$job_num){
		$modules = Generic::modules();
		$res = DB::select(DB::raw("select id,concat(job_num,' ',ifnull((select name from gpg_customer where id = GPG_customer_id),''),'&nbsp; | &nbsp;',ifnull(project_name,''),'&nbsp; | &nbsp;',ifnull(project_address,''),'') as name from gpg_job_electrical_quote ORDER BY job_num ASC"));
		$list_quotes = array();
		foreach ($res as $key => $value){
			$list_quotes[$value->id] = $value->name; 
		}
		$quotes_ids = DB::select(DB::raw("SELECT id,job_num FROM gpg_job_electrical_quote WHERE if(POSITION(\":\" IN job_num)=0,concat(job_num,\":\"),job_num) LIKE '".(!strpos($job_num,":")?$job_num:substr($job_num,0,strlen($job_num)-3)).":%'"));
		$quote_ids_arr = array();
		foreach ($quotes_ids as $key => $value) {
			$quote_ids_arr[$value->id] = $value->job_num; 
		}
		$query_data = DB::select(DB::raw("select * from gpg_job_electrical_quote where id = '$job_id'"));
		$Quote_Data = array();
		foreach ($query_data as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				if ($key == 'GPG_customer_id') {
					$Quote_Data['customer_drop_down'] = array(''=>'Select Customer') + DB::table('gpg_customer')->select('id','name')->lists('name','id');
					$Quote_Data['salesPerson_drop_down'] = array(''=>'Select Employee') + DB::table('gpg_employee')->select('id','name')->lists('name','id');
					$Quote_Data['estimator_drop_down'] = array(''=>'Select Estimator') + DB::table('gpg_employee')->select('id','name')->where('frontend', 'like', '%sales%')->orderBy('name', 'asc')->lists('name','id');
					if (!empty($value)){
						$QData = DB::table('gpg_customer')->select('*')->where('id','=',$value)->get();
						$QdataArr = array();
						foreach ($QData as $key3 => $value3) {
							foreach ($value3 as $key4 => $value4) {
								$QdataArr[$key4] = $value4;
							}
						}
						$Quote_Data['customer_info'] = $QdataArr;	
					}
					else
						$Quote_Data['customer_info'] = array();
				}
				if ($key == 'contact_info_id'){
					$contact_info = DB::table('gpg_settings')->where('name', 'like', '_ContactInfo%')->orderBy('value', 'asc')->lists('value', 'id');
					$terms = DB::table('gpg_settings')->where('name', 'like', '_TermsAndConditions%')->orderBy('value', 'asc')->lists('value', 'id');
					$Quote_Data['contact_info'] = $contact_info;
					$Quote_Data['terms'] = $terms;
				}
				if (is_float($value))
					$Quote_Data[$key] = number_format($value,2);
				else
					$Quote_Data[$key] = $value; 
			}
		}
		$gpg_settings = DB::table('gpg_settings')->where('name', 'like', '_ElectricalQuoteStage%')->orderBy('value', 'asc')->lists('id', 'value');
		$gpg_settings = array('' => 'Select Stage') + $gpg_settings;
		$Consts = DB::select(DB::raw("Select annual_energy_cost,material_mark_up,labor_hours_multiplier,labor_rate,incentive_rate From gpg_job_electrical_quote Where id = '$job_id'"));	
		$Constants = array();
		foreach ($Consts as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$Constants[$key] = $value;
			}
		}
		$existings = DB::select(DB::raw("select * from gpg_job_electrical_subquote_existing_fixtures where archive_status = '0' OR id IN (SELECT gpg_job_electrical_subquote_existing_fixtures_id FROM gpg_job_electrical_subquote WHERE job_electrical_quote_id = '".$job_id."') order by fixture_name  asc"));
		$existing_arr = array(''=>'Existing Fixture','Exnewfixture'=>'Add New Fixture');
		foreach ($existings as $key => $value) {
			$existing_arr[$value->id] = $value->fixture_name;
		}
		$proposed_dd = DB::select(DB::raw("select * from gpg_job_electrical_subquote_proposed_fixtures where archive_status = '0' OR id IN (SELECT gpg_job_electrical_subquote_proposed_fixtures_id FROM gpg_job_electrical_subquote WHERE job_electrical_quote_id = '".$job_id."') order by fixture_name asc"));
		$proposed_arr = array(''=>'Proposed Fixture','Pronewfixture'=>'Add New Fixture');
		foreach ($proposed_dd as $key => $value) {
			$proposed_arr[$value->id] = $value->fixture_name;
		}
		$rebateDrop = DB::select(DB::raw("SELECT id,rebate_measure,rebate_description,rebate_amount,rebate_type,rebate_start_year FROM gpg_rebate"));
		$rebate_arr = array(''=>'-');
		foreach ($rebateDrop as $key => $value) {
			$rebate_arr[$value->id] = $value->rebate_measure.' '.$value->rebate_description.' '.$value->rebate_type.' '.$value->rebate_start_year;
		}
		$sub_quote = DB::table('gpg_job_electrical_subquote')->select('*')->where('job_electrical_quote_id','=',$job_id)->get();
		$getJESQ = array();
		foreach ($sub_quote as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				if ($key == 'gpg_job_electrical_subquote_existing_fixtures_id'){
					$temp_JESQ['fixture_name'] = DB::table('gpg_job_electrical_subquote_existing_fixtures')->where('id','=',$value)->pluck('fixture_name');
				}
				if ($key == 'gpg_job_electrical_subquote_proposed_fixtures_id'){
					$temp_JESQ['fixture_name_pro'] = DB::table('gpg_job_electrical_subquote_proposed_fixtures')->where('id','=',$value)->pluck('fixture_name');
					$temp_JESQ['docs'] = DB::table('gpg_job_electrical_subquote_pro_fix_doc')->select('pro_fix_id')->where('pro_fix_id','=',$value)->count();
				}
				if ($key == 'gpg_rebate1_id'){
					$rebts = DB::table('gpg_rebate')->select('*')->where('id','=',$value)->get();
					$rebate1Detail = array();
					foreach ($rebts as $key3 => $value3) {
						foreach ($value3 as $key => $value) {
							$rebate1Detail[$key] = $value;		
						}	
					}
					$temp_JESQ['$rebate1Detail'] = $rebate1Detail;	
				}
				if ($key == 'gpg_rebate2_id'){
					$rebts2 = DB::table('gpg_rebate')->select('*')->where('id','=',$value)->get();
					$rebate2Detail = array();
					foreach ($rebts2 as $key3 => $value3) {
						foreach ($value3 as $key => $value) {
							$rebate2Detail[$key] = $value;		
						}	
					}
					$temp_JESQ['$rebate2Detail'] = $rebate2Detail;	
				}
				if ($key == 'gpg_rebate3_id'){
					$rebts3 = DB::table('gpg_rebate')->select('*')->where('id','=',$value)->get();
					$rebate3Detail = array();
					foreach ($rebts3 as $key3 => $value3) {
						foreach ($value3 as $key => $value) {
							$rebate3Detail[$key] = $value;		
						}	
					}
					$temp_JESQ['$rebate3Detail'] = $rebate3Detail;	
				}
				if ($key == 'gpg_rebate4_id'){
					$rebts4 = DB::table('gpg_rebate')->select('*')->where('id','=',$value)->get();
					$rebate4Detail = array();
					foreach ($rebts4 as $key3 => $value3) {
						foreach ($value3 as $key => $value) {
							$rebate4Detail[$key] = $value;		
						}	
					}
					$temp_JESQ['$rebate4Detail'] = $rebate4Detail;	
				}

				$temp_JESQ[$key] = $value;
			}
			$getJESQ[] = $temp_JESQ; 
		}
		$installed_fix_arr= array();
		$installed_result = DB::select(DB::raw("SELECT gpg_job_electrical_subquote_fixtures_installed.gpg_job_electrical_subqoute_id, SUM(gpg_job_electrical_subquote_fixtures_installed.quantity_installed) AS installed FROM gpg_job_electrical_subquote_fixtures_installed WHERE gpg_job_electrical_subquote_fixtures_installed.gpg_job_electrical_quote_id = ".$job_id." GROUP BY gpg_job_electrical_subquote_fixtures_installed.gpg_job_electrical_subqoute_id"));
		if (!empty($installed_result)){
			foreach ($installed_result as $key => $value) {
				$installed_fix_arr[$value->gpg_job_electrical_subqoute_id] = $value->installed;
			}	
		}

		$params = array('left_menu' => $modules,'job_id'=>$job_id,'job_num'=>$job_num,'list_quotes'=>$list_quotes,'quote_ids_arr'=>$quote_ids_arr,'jobElectricalQuoteTblRow'=>$Quote_Data,'gpg_settings'=>$gpg_settings,'elecJobTypeArray'=>$this->elecJobTypeArray,'Constants'=>$Constants,'existing_arr'=>$existing_arr,'proposed_arr'=>$proposed_arr,'rebate_arr'=>$rebate_arr,'getJESQ'=>$getJESQ,'installed_fix_arr'=>$installed_fix_arr);
 		return View::make('job.job_electrical_subquote_frm', $params);
	}
	/*
	* updateElectricSubQuoteFrm
	*/
	public function updateElectricSubQuoteFrm(){
		$job_electrical_quote_id = Input::get('job_id');
		$jobNum = Input::get('job_num');
		$sales_tax = Input::get("sales_tax");
		if(strlen($sales_tax)==0)
			$sales_tax=0;
	    $wages = Input::get("txt_wages");
		if(strlen($wages)==0)
			$wages=0;
	    $disposal = Input::get("txt_disposal");
		if(strlen($disposal)==0)
			$disposal=0;
	    $cleanup = Input::get("txt_cleanup");
		if(strlen($cleanup)==0)
			$cleanup=0;
	    $lift_rental = Input::get("txt_lift_rental");
		if(strlen($lift_rental)==0)
			$lift_rental=0;
		$rate_per_kw = Input::get("txt_rate_per_kw");
		if(strlen($rate_per_kw)==0)
			$rate_per_kw=0;
		$incentive_obf = Input::get("incentive_obf");
		if(strlen($incentive_obf)==0)
			$incentive_obf=0;
		$reduction_constant = Input::get("reduction_constant");
		if(strlen($reduction_constant)==0)
			$reduction_constant=0;
		$subquote_total_cost = Input::get("subquote_total_cost");
		if(strlen($subquote_total_cost)==0)
			$subquote_total_cost=0;
		$subquote_material_cost = Input::get("subquote_material_cost");
		if(strlen($subquote_material_cost)==0)
			$subquote_material_cost=0;
		$subquote_material_margin=Input::get('subquote_material_margin');
			if(strlen($subquote_material_margin)==0)
		$subquote_material_margin=0;
		$subquote_labor_hour = Input::get("subquote_labor_hour");
		if(strlen($subquote_labor_hour)==0)
			$subquote_labor_hour=0;
		$subquote_labor_cost = Input::get("subquote_labor_cost");
		if(strlen($subquote_labor_cost)==0)
			$subquote_labor_cost=0;	
		$employee_assigned = Input::get("employee_assigned");
		$reservation_number = Input::get("reservation_number");
		$tax_id = Input::get("tax_id");
		$tax_status = Input::get("tax_status");
		$building_square = Input::get("building_square");
		$aho = Input::get("aho");
		//$job_electrical_quote_id = Input::get("job_electrical_quote_id");
		if(!empty($job_electrical_quote_id) && strlen($job_electrical_quote_id)>0)
			$result =  DB::table('gpg_job_electrical_quote')->where('id','=',$job_electrical_quote_id)->update(array('sales_tax' => $sales_tax,
								'other_wages' => $wages,
								'disposal' => $disposal,
								'clean_up' => $cleanup,
								'lift_rental' => $lift_rental,
								'rate_per_kw' => $rate_per_kw,
								'incentive_obf' => $incentive_obf,
								'reduction_constant' => $reduction_constant,
								'subquote_total_cost' => $subquote_total_cost,
								'subquote_material_cost' => $subquote_material_cost,
								'subquote_material_margin'=> $subquote_material_margin,
								'subquote_labor_cost' => $subquote_labor_cost,
								'subquote_labor_hour' => $subquote_labor_hour,
								'annual_energy_cost' => Input::get('post_annual_energy_multiplier'),
								'material_mark_up' => Input::get('post_material_mark_up'),
								'labor_hours_multiplier' => Input::get('post_labor_hours_multiplier'),
								'labor_rate' => Input::get('post_labor_rate'),
								'incentive_rate' => Input::get('post_incentive_rate'),
								'gpg_employee_assigned' => $employee_assigned,
								'reservation_number' => $reservation_number,
								'tax_status' => $tax_status,
								'building_square' => $building_square,
								'tax_id' => $tax_id,
								'aho' => $aho,
								'modified_on' => date('Y-m-d')));
		$TotalLinesOtherCharges = Input::get('xpCounter_id');
		$gpg_sales_tracking_id = DB::table('gpg_sales_tracking_job_electrical_quote')->where('gpg_job_electrical_quote_id','=',$job_electrical_quote_id)->pluck('gpg_sales_tracking_id');
		$cusFields = array("address"=>"cusAddress1","address2"=>"cusAddress2","city"=>"cusCity","state"=>"cusState","zipcode"=>"cusZip","phone_no"=>"cusPhone","attn"=>"cusAtt");
		$jobElecQuoteQuery = array();
		foreach ($_POST as $ke => $vl) {
			if (preg_match("/_filter_changed_on/i",$ke) || preg_match("/_estimated_close_date/i",$ke))
				$jobElecQuoteQuery[substr($ke,1,strlen($ke))] = ($vl!=''?"'".date('Y-m-d',strtotime($vl))."'":"NULL");
			else if (preg_match("/^_/i",$ke))
				$jobElecQuoteQuery[substr($ke,1,strlen($ke))] = $vl;
		}
		unset($jobElecQuoteQuery['token']);
		$jobElecQutoeUpdate = DB::table('gpg_job_electrical_quote')->where('id','=',$job_electrical_quote_id)->update($jobElecQuoteQuery+array('schedule_time'=>$_POST['schedule_time'],'modified_on'=>date('Y-m-d')));
		
		//**Customer Update
		$jobElecQutoeUpdate = 1;
		$cusFieldQuery = array();
		if($jobElecQutoeUpdate){
			foreach ($cusFields as $key => $value) {
				$cusFieldQuery[$key] = Input::get($value);
			}
		  $cus_update = DB::table('gpg_customer')->where('id','=',Input::get('_GPG_customer_id'))->update($cusFieldQuery+array('modified_on'=>date('Y-m-d')));
		}

		$previous_records=0;
		$q=1;
		if (isset($_POST['other_charge_description_1'])){
			$previous_records = DB::table('gpg_job_electrical_quote_other')->where('gpg_job_electrical_quote_id','=',$job_electrical_quote_id)->count();
			$p_records = DB::table('gpg_job_electrical_quote_other')->select('id')->where('gpg_job_electrical_quote_id','=',$job_electrical_quote_id)->get();
			foreach ($p_records as $key => $value) {
				DB::table('gpg_job_electrical_quote_other')->where('id','=',$value->id)->update(array('gpg_job_electrical_quote_id'=> $job_electrical_quote_id, 'other_charge_qty'=>$_POST["other_charge_qty_".$q], 'other_charge_description'=>$_POST["other_charge_description_".$q], 'other_charge_cost_price'=>$_POST["other_charge_cost_price_".$q], 'modified_on'=> date('Y-m-d')));
				$q++;
			}
		}
		$new_rows = $TotalLinesOtherCharges-$previous_records;
		$j = $q;
		if (isset($_POST['other_charge_description_'.$q]) && $new_rows > 0){
			while ($j <= $new_rows) {
				DB::table('gpg_job_electrical_quote_other')->insert(array('gpg_job_electrical_quote_id'=>$job_electrical_quote_id, 'other_charge_qty'=>$_POST["other_charge_qty_".$j], 'other_charge_description'=>$_POST["other_charge_description_".$j], 'other_charge_cost_price'=>$_POST["other_charge_cost_price_".$j], 'created_on'=>date('Y-m-d') , 'modified_on'=>date('Y-m-d')));
				$j++;
			}
		}
		$getJobNum = DB::table('gpg_job_electrical_quote')->where('id','=',$job_electrical_quote_id)->pluck('GPG_attach_job_num');
		
		$job_electrical_subquote_feilds = array("location","unit_fixture_cost","fixture_quantity_ex","fixture_quantity_pro","lamps_fixture_quantity_ex","lamps_fixture_quantity_pro","annual_hours_of_operation_ex","annual_hours_of_operation_pro");
		$temp_qry_arr = array();
		for ($i=1; $i<=$TotalLinesOtherCharges; $i++){
			$temp_qry_arr = array();
			foreach ($job_electrical_subquote_feilds as $value) {
				$temp_qry_arr[$value] = $_POST['new_'.$value.'_'.$i];
			}
			DB::table('gpg_job_electrical_subquote')->insert($temp_qry_arr);
		}

		return Redirect::to('job/job_electrical_subquote_frm/'.$_POST['job_id'].'/'.$_POST['job_num'].'');
	}

	/*
	* excelQuoteFormExport
	*/
	public function excelQuoteFormExport(){
			
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('ElecQuoteExportFile', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		   	
		   	$j_num = $_REQUEST['j_num'];
		   	$id = $_REQUEST['id'];
		    $query_data = DB::select(DB::raw("select * from gpg_job_electrical_quote where id = '$id'"));
			$other = DB::select(DB::raw("select * from gpg_job_electrical_quote_other WHERE gpg_job_electrical_quote_id = '$id' order by id asc"));
			$quote_other_info = array();
			foreach ($other as $key0 => $value0) {
				$quote_other_info[] = array('id' =>$value0->id ,'gpg_job_electrical_quote_id' =>$value0->gpg_job_electrical_quote_id ,'other_charge_qty' =>$value0->other_charge_qty ,'other_charge_description' =>$value0->other_charge_description ,'other_charge_cost_price' =>$value0->other_charge_cost_price);
			}
			$Quote_Data = array();
			foreach ($query_data as $key2 => $value2){
				foreach ($value2 as $key => $value) {
					if ($key == 'GPG_customer_id') {
						$Quote_Data['customer_drop_down'] = DB::table('gpg_customer')->select('id','name')->lists('name','id');
						$Quote_Data['salesPerson_drop_down'] = DB::table('gpg_employee')->select('id','name')->lists('name','id');
						$Quote_Data['estimator_drop_down'] = DB::table('gpg_employee')->select('id','name')->where('frontend', 'like', '%sales%')->orderBy('name', 'asc')->lists('name','id');
						if (!empty($value)){
							$QData = DB::table('gpg_customer')->select('*')->where('id','=',$value)->get();
							$QdataArr = array();
							foreach ($QData as $key3 => $value3) {
								foreach ($value3 as $key4 => $value4) {
									$QdataArr[$key4] = $value4;
								}
							}
							$Quote_Data['customer_info'] = $QdataArr;	
						}
						else
							$Quote_Data['customer_info'] = array();
					}
					if ($key == 'contact_info_id'){
						$contact_info = DB::table('gpg_settings')->where('name', 'like', '_ContactInfo%')->orderBy('value', 'asc')->lists('value', 'id');
						$terms = DB::table('gpg_settings')->where('name', 'like', '_TermsAndConditions%')->orderBy('value', 'asc')->lists('value', 'id');
						$Quote_Data['contact_info'] = $contact_info;
						$Quote_Data['terms'] = $terms;
					}
					if (is_float($value))
						$Quote_Data[$key] = number_format($value,2);
					else
						$Quote_Data[$key] = $value; 
				}
			}
			$gpg_settings = DB::table('gpg_settings')->where('name', 'like', '_ElectricalQuoteStage%')->orderBy('value', 'asc')->lists('id', 'value');
			$gpg_settings = array('' => 'Select Stage') + $gpg_settings;
			ksort($this->elecJobTypeArray);
			$gen_cost = DB::select(DB::raw("select * FROM gpg_job_electrical_quote_labor_material_list WHERE  gpg_job_electrical_quote_id = '$id' order by id"));
			$arrayTemp = array();
			foreach ($gen_cost as $keyGen => $valueGen) {
				foreach ($valueGen as $key => $value) {
					$arrayGenCost[$key] = $value;
				}
				$arrayTemp[] = $arrayGenCost; 
			}

			$res = DB::select(DB::raw("select id,concat(job_num,' ',ifnull((select name from gpg_customer where id = GPG_customer_id),''),'&nbsp; | &nbsp;',ifnull(project_name,''),'&nbsp; | &nbsp;',ifnull(project_address,''),'') as name from gpg_job_electrical_quote ORDER BY job_num ASC"));
			$list_quotes = array();
			foreach ($res as $key => $value){
				$list_quotes[$value->id] = $value->name; 
			}
			$quotes_ids = DB::select(DB::raw("SELECT id,job_num FROM gpg_job_electrical_quote WHERE if(POSITION(\":\" IN job_num)=0,concat(job_num,\":\"),job_num) LIKE '".(!strpos($j_num,":")?$j_num:substr($j_num,0,strlen($j_num)-3)).":%'"));
			$quote_ids_arr = array();
			foreach ($quotes_ids as $key => $value) {
				$quote_ids_arr[$value->id] = $value->job_num; 
			}
			$params = array('job_id'=>$id,'job_num'=>$j_num,'jobElectricalQuoteTblRow'=>$Quote_Data,'gpg_settings'=>$gpg_settings,'elecJobTypeArray'=>$this->elecJobTypeArray,'quote_other_info'=>$quote_other_info,'gen_cost'=>$arrayTemp,'list_quotes'=>$list_quotes,'quote_ids_arr'=>$quote_ids_arr);
	 		$sheet->loadView('job.excelQuoteFormExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelSubQuoteFormExport
	*/
	public function excelSubQuoteFormExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('ElecSubQuoteExportFile', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));		
		   	$job_num = $_REQUEST['j_num'];
		   	$job_id = $_REQUEST['id'];
		   	$frm = $_REQUEST['frm'];

		   	$job_elec_sq = DB::select(DB::raw("select *,(select GPG_customer_id from gpg_job_electrical_quote where id = job_electrical_quote_id) as customerId from gpg_job_electrical_subquote where job_electrical_quote_id = '$job_id'"));
        	$job_electrical_subquote = array();
        	if (!empty($job_elec_sq)) {
        		foreach ($job_elec_sq as $keyN => $valueN) {
        			foreach ($valueN as $key => $value) {
        				$temp[$key] = $value;			
        			}
        			$job_electrical_subquote[] = $temp; 
        		}
        	}
        	$res = DB::select(DB::raw("select id,concat(job_num,' ',ifnull((select name from gpg_customer where id = GPG_customer_id),''),'&nbsp; | &nbsp;',ifnull(project_name,''),'&nbsp; | &nbsp;',ifnull(project_address,''),'') as name from gpg_job_electrical_quote ORDER BY job_num ASC"));
			$list_quotes = array();
			foreach ($res as $key => $value){
				$list_quotes[$value->id] = $value->name; 
			}
			$quotes_ids = DB::select(DB::raw("SELECT id,job_num FROM gpg_job_electrical_quote WHERE if(POSITION(\":\" IN job_num)=0,concat(job_num,\":\"),job_num) LIKE '".(!strpos($job_num,":")?$job_num:substr($job_num,0,strlen($job_num)-3)).":%'"));
			$quote_ids_arr = array();
			foreach ($quotes_ids as $key => $value) {
				$quote_ids_arr[$value->id] = $value->job_num; 
			}
			$query_data = DB::select(DB::raw("select * from gpg_job_electrical_quote where id = '$job_id'"));
			$Quote_Data = array();
			foreach ($query_data as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					if ($key == 'GPG_customer_id') {
						$Quote_Data['customer_drop_down'] = array(''=>'Select Customer') + DB::table('gpg_customer')->select('id','name')->lists('name','id');
						$Quote_Data['salesPerson_drop_down'] = array(''=>'Select Employee') + DB::table('gpg_employee')->select('id','name')->lists('name','id');
						$Quote_Data['estimator_drop_down'] = array(''=>'Select Estimator') + DB::table('gpg_employee')->select('id','name')->where('frontend', 'like', '%sales%')->orderBy('name', 'asc')->lists('name','id');
						if (!empty($value)){
							$QData = DB::table('gpg_customer')->select('*')->where('id','=',$value)->get();
							$QdataArr = array();
							foreach ($QData as $key3 => $value3) {
								foreach ($value3 as $key4 => $value4) {
									$QdataArr[$key4] = $value4;
								}
							}
							$Quote_Data['customer_info'] = $QdataArr;	
						}
						else
							$Quote_Data['customer_info'] = array();
					}
					if ($key == 'contact_info_id'){
						$contact_info = DB::table('gpg_settings')->where('name', 'like', '_ContactInfo%')->orderBy('value', 'asc')->lists('value', 'id');
						$terms = DB::table('gpg_settings')->where('name', 'like', '_TermsAndConditions%')->orderBy('value', 'asc')->lists('value', 'id');
						$Quote_Data['contact_info'] = $contact_info;
						$Quote_Data['terms'] = $terms;
					}
					if (is_float($value))
						$Quote_Data[$key] = number_format($value,2);
					else
						$Quote_Data[$key] = $value; 
				}
			}

			$gpg_settings = DB::table('gpg_settings')->where('name', 'like', '_ElectricalQuoteStage%')->orderBy('value', 'asc')->lists('id', 'value');
			$gpg_settings = array('' => 'Select Stage') + $gpg_settings;
			$Consts = DB::select(DB::raw("Select annual_energy_cost,material_mark_up,labor_hours_multiplier,labor_rate,incentive_rate From gpg_job_electrical_quote Where id = '$job_id'"));	
			$Constants = array();
			foreach ($Consts as $key2 => $value2) {
				foreach ($value2 as $key => $value){
					$Constants[$key] = $value;
				}
			}
			$existings = DB::select(DB::raw("select * from gpg_job_electrical_subquote_existing_fixtures where archive_status = '0' OR id IN (SELECT gpg_job_electrical_subquote_existing_fixtures_id FROM gpg_job_electrical_subquote WHERE job_electrical_quote_id = '".$job_id."') order by fixture_name  asc"));
			$existing_arr = array(''=>'Existing Fixture','Exnewfixture'=>'Add New Fixture');
			foreach ($existings as $key => $value) {
				$existing_arr[$value->id] = $value->fixture_name;
			}
			$proposed_dd = DB::select(DB::raw("select * from gpg_job_electrical_subquote_proposed_fixtures where archive_status = '0' OR id IN (SELECT gpg_job_electrical_subquote_proposed_fixtures_id FROM gpg_job_electrical_subquote WHERE job_electrical_quote_id = '".$job_id."') order by fixture_name asc"));
			$proposed_arr = array(''=>'Proposed Fixture','Pronewfixture'=>'Add New Fixture');
			foreach ($proposed_dd as $key => $value) {
				$proposed_arr[$value->id] = $value->fixture_name;
			}
			$rebateDrop = DB::select(DB::raw("SELECT id,rebate_measure,rebate_description,rebate_amount,rebate_type,rebate_start_year FROM gpg_rebate"));
			$rebate_arr = array(''=>'-');
			foreach ($rebateDrop as $key => $value) {
				$rebate_arr[$value->id] = $value->rebate_measure.' '.$value->rebate_description.' '.$value->rebate_type.' '.$value->rebate_start_year;
			}
			$sub_quote = DB::table('gpg_job_electrical_subquote')->select('*')->where('job_electrical_quote_id','=',$job_id)->get();
			$getJESQ = array();
			foreach ($sub_quote as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					if ($key == 'gpg_job_electrical_subquote_existing_fixtures_id'){
						$temp_JESQ['fixture_name'] = DB::table('gpg_job_electrical_subquote_existing_fixtures')->where('id','=',$value)->pluck('fixture_name');
					}
					if ($key == 'gpg_job_electrical_subquote_proposed_fixtures_id'){
						$temp_JESQ['fixture_name_pro'] = DB::table('gpg_job_electrical_subquote_proposed_fixtures')->where('id','=',$value)->pluck('fixture_name');
						$temp_JESQ['docs'] = DB::table('gpg_job_electrical_subquote_pro_fix_doc')->select('pro_fix_id')->where('pro_fix_id','=',$value)->count();
					}
					if ($key == 'gpg_rebate1_id'){
						$rebts = DB::table('gpg_rebate')->select('*')->where('id','=',$value)->get();
						$rebate1Detail = array();
						foreach ($rebts as $key3 => $value3) {
							foreach ($value3 as $key => $value) {
								$rebate1Detail[$key] = $value;		
							}	
						}
						$temp_JESQ['$rebate1Detail'] = $rebate1Detail;	
					}
					if ($key == 'gpg_rebate2_id'){
						$rebts2 = DB::table('gpg_rebate')->select('*')->where('id','=',$value)->get();
						$rebate2Detail = array();
						foreach ($rebts2 as $key3 => $value3) {
							foreach ($value3 as $key => $value) {
								$rebate2Detail[$key] = $value;		
							}	
						}
						$temp_JESQ['$rebate2Detail'] = $rebate2Detail;	
					}
					if ($key == 'gpg_rebate3_id'){
						$rebts3 = DB::table('gpg_rebate')->select('*')->where('id','=',$value)->get();
						$rebate3Detail = array();
						foreach ($rebts3 as $key3 => $value3) {
							foreach ($value3 as $key => $value) {
								$rebate3Detail[$key] = $value;		
							}	
						}
						$temp_JESQ['$rebate3Detail'] = $rebate3Detail;	
					}
					if ($key == 'gpg_rebate4_id'){
						$rebts4 = DB::table('gpg_rebate')->select('*')->where('id','=',$value)->get();
						$rebate4Detail = array();
						foreach ($rebts4 as $key3 => $value3) {
							foreach ($value3 as $key => $value) {
								$rebate4Detail[$key] = $value;		
							}	
						}
						$temp_JESQ['$rebate4Detail'] = $rebate4Detail;	
					}

					$temp_JESQ[$key] = $value;
				}
				$getJESQ[] = $temp_JESQ; 
			}
			$installed_fix_arr= array();
			$installed_result = DB::select(DB::raw("SELECT gpg_job_electrical_subquote_fixtures_installed.gpg_job_electrical_subqoute_id, SUM(gpg_job_electrical_subquote_fixtures_installed.quantity_installed) AS installed FROM gpg_job_electrical_subquote_fixtures_installed WHERE gpg_job_electrical_subquote_fixtures_installed.gpg_job_electrical_quote_id = ".$job_id." GROUP BY gpg_job_electrical_subquote_fixtures_installed.gpg_job_electrical_subqoute_id"));
			if (!empty($installed_result)){
				foreach ($installed_result as $key => $value) {
					$installed_fix_arr[$value->gpg_job_electrical_subqoute_id] = $value->installed;
				}	
			}

		$params = array('job_id'=>$job_id,'job_num'=>$job_num,'list_quotes'=>$list_quotes,'quote_ids_arr'=>$quote_ids_arr,'jobElectricalQuoteTblRow'=>$Quote_Data,'gpg_settings'=>$gpg_settings,'elecJobTypeArray'=>$this->elecJobTypeArray,'Constants'=>$Constants,'existing_arr'=>$existing_arr,'proposed_arr'=>$proposed_arr,'rebate_arr'=>$rebate_arr,'getJESQ'=>$getJESQ,'installed_fix_arr'=>$installed_fix_arr,'job_electrical_subquote'=>$job_electrical_subquote);
		 	if ($frm == '1')
				$sheet->loadView('job.excelSubQuoteFormExport',$params);
			else
				$sheet->loadView('job.excelSubQuoteFormExport2',$params);				

		});
		})->export('xls');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$modules = Generic::modules();
		$job_type_arr = DB::table('gpg_job_type')->select('id','name')->lists('name','id');
		$job_type_arr = array(''=>'Select Job Category')+$job_type_arr;

		$customer_arr = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->lists('name','id');
		$customer_arr = array(''=>'Select Customer')+$customer_arr;

		$employee_arr = DB::table('gpg_employee')->select('id','name')->where('status','=','A')->orderBy('name')->lists('name','id');
		$employee_arr = array(''=>'Select Employee')+$employee_arr;


		$params = array('left_menu' => $modules, 'job_type_arr'=>$job_type_arr,'customer_arr'=>$customer_arr,'employee_arr'=>$employee_arr);
		return View::make('job.create', $params);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$modules = Generic::modules();
		$rules = array(
	        'jobCat'             => 'required',                        // just a normal required validation
	        'jobPlan'            => 'required',     // required and must be unique in the ducks table
	        'customer'         => 'required',
	        'job_num' => 'required|unique:gpg_job|max:20',           // required and has to match the password field
	        'task' => 'max:250',           // required and has to match the password field
	        'taskSub' => 'max:250'           // required and has to match the password field
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('quote/create')->withErrors($validator);
		}
		else{
		 	$jobCat = Input::get('jobCat');
		 	$jobPlan = Input::get('jobPlan');
		 	$customer = Input::get('customer');
		 	$jobNum = Input::get('job_num');
		 	$assignTo = Input::get('assignTo');
		 	$priority = Input::get('priority');
		 	$location = Input::get('location');
		 	$genSize = Input::get('genSize');
		 	$task = Input::get('task');
		 	$taskSub = Input::get('taskSub');
		 	DB::table('gpg_job')->insert(array('GPG_job_type_id'=>$jobCat,'GPG_employee_id'=>$assignTo,'GPG_wage_plan_id'=>$jobPlan,'GPG_customer_id'=>$customer,'job_num'=>$jobNum,'location'=>$location,'generator_size'=>$genSize,'task'=>$task,'sub_task'=>$taskSub,'status'=>($assignTo!=""?"A":"N"),'priority'=>$priority,'modified_on'=>date('Y-m-d'),'created_on'=>date('Y-m-d')));
	  		return Redirect::to('quote/create')->withSuccess('New Job has been created successfully');
		}
	}

	/*
	* addJobCategory
	*/
	public function addJobCategory(){
		$name = Input::get('name');
		$modules = Generic::modules();
		if (isset($name) && !empty($name)) {

			$rules = array(
		        'name' => 'required|unique:gpg_job_type|max:50'           // required and has to match the password field
	    	);
	    	$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()){
				if (isset($_POST['name'])) {
				 return Redirect::to('quote/new_jobcat')->withErrors($validator);	
				}else
					return 0;
			}else{

				DB::table('gpg_job_type')->insert(array('name'=>$name,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				if (isset($_POST['name'])) {
					return Redirect::to('quote')->withSuccess('New Job category has been created, successfully');
				}else
					return 1;
			}
		}else{
			$params = array('left_menu' => $modules);
			return View::make('job.new_jobcat', $params);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$modules = Generic::modules();
		$job_type = DB::table('gpg_job_type')->select('*')->where('id','=',$id)->get();
		$job_type_arr = array();
		foreach ($job_type as $key => $value) {
			$job_type_arr = array('id'=>$value->id,'name'=>$value->name);
		}
		$params = array('left_menu' => $modules,'job_type_arr'=>$job_type_arr);
		return View::make('job.edit', $params);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/*
	* deleteEquipLine
	*/
	public function deleteEquipPricingLine(){
		$id = Input::get('id');
		$jid = Input::get('jid');
		$table = Input::get('table');
		DB::table('gpg_job_electrical_'.$table.'_pricing')->where('id', '=',$id)->delete();
		
		$equipmentTotalQuery = DB::select(DB::raw("SELECT  sum(equipment_sell_price) as equipment_sell_price_total, sum(equipment_total_cost) as equipment_total_cost_total, sum(equipment_margin) as equipment_margin_total FROM gpg_job_electrical_equipment_pricing WHERE gpg_job_electrical_quote_id ='".$jid."'"));
		$equipmentTotalArr = array();
		foreach ($equipmentTotalQuery as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$equipmentTotalArr[$key] = $value;	
			}
		}

		$laborTotalQuery = DB::select(DB::raw("SELECT  sum(labor_sell_price) as labor_sell_price_total, sum(labor_total_cost) as labor_total_cost_total, sum(labor_margin) as labor_margin_total FROM gpg_job_electrical_labor_pricing WHERE 	gpg_job_electrical_quote_id ='".$jid."'"));
		$laborTotalArr = array();
		foreach ($laborTotalQuery as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$laborTotalArr[$key] = $value;	
			}
		}

		$miscTotalQuery = DB::select(DB::raw("SELECT  sum(misc_sell_price) as misc_sell_price_total, sum(misc_total_cost) as misc_total_cost_total, sum(misc_margin) as misc_margin_total FROM gpg_job_electrical_misc_pricing WHERE 	gpg_job_electrical_quote_id ='".$jid."'"));
		$miscTotalArr = array();
		foreach ($miscTotalQuery as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$miscTotalArr[$key] = $value;	
			}
		}
		DB::table('gpg_job_electrical_pricing_totals')->where('gpg_job_electrical_quote_id','=',$jid)->update(array('equipment_sell_price_total'=>$equipmentTotalArr['equipment_sell_price_total'], 'equipment_margin_total'=>$equipmentTotalArr['equipment_margin_total'],'labor_sell_price_total'=>$laborTotalArr['labor_sell_price_total'],'labor_total_cost_total'=>$laborTotalArr['labor_total_cost_total'],'labor_margin_total'=>$laborTotalArr['labor_margin_total'], 'misc_sell_price_total'=>$miscTotalArr['misc_sell_price_total'],'misc_total_cost_total'=>$miscTotalArr['misc_total_cost_total'],'misc_margin_total'=>$miscTotalArr['misc_margin_total']));
		return 1;
	}

	/*
	* getElecticalQuotePdfFile
	*/
	public function getElecticalQuotePdfFile(){
		$job_electrical_quote_id = $_REQUEST["job_id"];
		$jobNum = $_REQUEST["job_num"];
		$cusId = $_REQUEST["_GPG_customer_id"];
		$contactInfoId = $_REQUEST["contact_info_id"];
		$termsAndConditionsId = $_REQUEST["terms_and_conditions_id"];
		if (!empty($_REQUEST['_GPG_estimator_id'])){
			$estId = $_REQUEST["_GPG_estimator_id"];		
		}else
			$estId = $_REQUEST["_GPG_employee_id"];

		if(!empty($contactInfoId)){
			$contactInfo = DB::table('gpg_settings')->where('id','=',$contactInfoId)->pluck('value');	
		}else {
			$contactInfo = '';
		}
		if(!empty($termsAndConditionsId)) {
			$termsAndConditions = DB::table('gpg_settings')->where('id','=',$termsAndConditionsId)->pluck('value'); 
			
			$jobRecord = DB::table('gpg_job_electrical_quote')->select('*')->Where('id','=',$job_electrical_quote_id)->get(); 
			$jobElectricalQuoteTblRow = array();
			foreach ($jobRecord as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					$jobElectricalQuoteTblRow[$key] = $value;
				}
			}
			if (isset($_REQUEST['scheduleDate']))
				$date = strftime('%B %d, %Y',strtotime($_REQUEST['scheduleDate']));
			else
				$date = date('Y-m-d');
			$cusName = DB::table('gpg_customer')->where('id','=',$cusId)->pluck('name');
			$estData = array();
			$qestData = DB::table('gpg_employee')->select('name','email','phone')->where('id','=',$estId)->get(); 
			foreach ($qestData as $key => $value) {
				$estData = array('name'=>$value->name,'email'=>$value->email,'phone'=>$value->phone);
			}			
			$arr1 =  explode('<br />',nl2br($jobElectricalQuoteTblRow['scope_of_work']));
			$evaluation_scope_of_work = array();
			for($i=0; $i<count($arr1); $i++ ){
				$evaluation_scope_of_work[$i] = trim($arr1[$i]);
			}	
			$arr2 =  explode('<br />',nl2br($jobElectricalQuoteTblRow['exclusions']));
			$exceptions_and_clarifications = array();
			for($i=0; $i<count($arr2); $i++ ){
				$exceptions_and_clarifications[$i] = trim($arr2[$i]);
			}
			$str2 = array();			
			$grand_total  = $this->clear_num($jobElectricalQuoteTblRow['grand_total']);
			///////// **** Generate PDF *******///////
			$pdf=new Fpdf();
			$pdf->SetFont('Arial','',10);
			$pdf->SetMargins(1, 5, 11, 5); 
			$pdf->AddPage();
			$cellWid = 113;
			$cellHig = 35;
		    $pdf->Cell(80);
			$pdf->Image(storage_path('big_logo.jpg'),80,8,50);
			$pdf->Ln(32);
		    $pdf->Cell(73);
		    $pdf->MultiCell(60,5,strip_tags($contactInfo).'Lic# 870741',0,'C');
		    $pdf->Ln(8);
			$pdf->SetFont('Times','',12);
		    $pdf->Cell(10);
		    $pdf->MultiCell(60,5,$date);
			$pdf->Ln();
			$pdf->SetFont('Times','',12);
		    $pdf->Cell(10);
		    $pdf->MultiCell(60,3,'Job: '.$jobNum);
		    $pdf->Ln();
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(180,3,$cusName);
			$pdf->Ln();
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(160,3,$_REQUEST['cusAddress1']);

		    if($_REQUEST['cusCity'] != '' || $_REQUEST['cusState']!='' || $_REQUEST['cusZip']!=''){
				$pdf->Ln();
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(60,3,$_REQUEST['cusCity'].', '.$_REQUEST['cusState'].' '.$_REQUEST['cusZip']);
			}
			if($_REQUEST['_project_name']!=''){
				$pdf->Ln(5);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(80,5,'Re: '.$_REQUEST['_project_name'].'.');
				}
			if($_REQUEST['_project_contact']!=''){
				$pdf->Ln(5);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(80,5,'Good Day '.$_REQUEST['_project_contact'].',');
			}

			if($evaluation_scope_of_work[0]){
				$pdf->Ln(5);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(180,5,'Evaluation Scope of Work:');
				$pdf->Ln(5);
				$column_width = $pdf->w-20;
				$bullet = array();
				$bullet['bullet'] = '>';
				$bullet['margin'] = '';
				$bullet['indent'] = 3;
				$bullet['spacer'] = 3;
				$bullet['text'] = array();
				for ($i=0; $i< count($evaluation_scope_of_work); $i++){
					$bullet['text'][$i] = $evaluation_scope_of_work[$i];
				}
				$pdf->SetX(15);
				$this->MultiCellBltArray($column_width-$pdf->x, 4, $bullet);
			}
			$pdf->Ln(10);

			if(isset($_REQUEST['consolidate']) && $_REQUEST['consolidate'] == 'on'){ // start if big
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
				$grand_total_str="The cost to complete the above scope of work is ".'$'.number_format($this->clear_num($grand_total),2)." ";
				$grand_total_str .= convert_number_decimals($this->clear_num(number_format($grand_total,2))).".";
				$pdf->MultiCell(180,5,$grand_total_str);
				$chk = 0;
			    for($i = 1 ; $i <= $_REQUEST['Equipment_TotalLines']-1; $i++){
				    if($_REQUEST['Equipment_consolidate_'.$i]){
						$chk = 1;
						break;
					}
				}
			    for($i = 1 ; $i <= $_REQUEST['Labor_TotalLines']-1; $i++) {
				    if($_REQUEST['Labor_consolidate_'.$i]){
						$chk = 1;
						break;
					}
			    }
			    for($i = 1 ; $i <= $_REQUEST['Misc_TotalLines']-1; $i++){
				    if($_REQUEST['Misc_consolidate_'.$i]){
						$chk = 1;
						break;
					}
			    }
			    if($chk == 1){
					$pdf->Ln(5);
					$cellHig=6; 
					$cellWid=20;
					$pdf->Cell(10);
					$pdf->Cell($cellWid,$cellHig,"",1);	
					$str = 'Quantity';
					$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
					$cellWid=163;
					$pdf->Cell($cellWid,$cellHig,"",1);	
					$str = 'Description';
					$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
				}
				if (isset($_REQUEST['genCounter_id'])){
					for($i = 1 ; $i <= $_REQUEST['genCounter_id']-1; $i++) {
						$getElectricalEquipmentPricing = DB::table('gpg_job_electrical_equipment_pricing')->select('equipment_quantity','equipment_description')->where('gpg_job_electrical_quote_id','=',$job_electrical_quote_id)->where('id','=',$_REQUEST['Equipment_consolidate_'.$i])->orderBy('equipment_order')->get();
						$row= array();
						foreach ($getElectricalEquipmentPricing as $key => $value){
							$row = array('equipment_quantity'=>$value->equipment_quantity,'equipment_description'=>$value->equipment_description);
						}						
						if($row){
							$pdf->Ln();
							$pdf->Cell(10);
							$pdf->SetFont('Courier','',9);				 
							$cellWid=20;
							$str = $row['equipment_description'];
							$str2 = array();
							$this->break_string($str,83);
						    $cellHig=$cellHig * count($str2);
							$pdf->Cell($cellWid,$cellHig,"",1);	
							$str = $row['equipment_quantity'];
							$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
							$cellWid=163;
							$pdf->Cell($cellWid,$cellHig,"",1);	
							$stry = 0;
							for ($i12=0 ; $i12 < count($str2) ; $i12++ ){
								$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$i12]);
								 $stry += 5;
							}
							$cellHig = 6;
							$mulval = '';
						} 
					}
				}
				if (isset($_REQUEST['labCounter_id'])){
					for($i = 1 ; $i <= $_REQUEST['labCounter_id']-1; $i++) {
					$getLaborPricing = DB::table('gpg_job_electrical_labor_pricing')->select('labor_quantity','labor_description')->where('gpg_job_electrical_quote_id','=',$job_electrical_quote_id)->where('id','=',$_REQUEST['Labor_consolidate_'.$i])->orderBy('labor_order')->get();
					$row = array();
					foreach ($getLaborPricing as $key => $value) {
						$row = array('labor_quantity'=>$value->labor_quantity,'labor_description'=>$value->labor_description);
					}			
					if($row){
						$pdf->Ln();
						$pdf->Cell(10);
						$pdf->SetFont('Courier','',9);
						$str = $row['labor_description'];
						$str2 = array();
						$this->break_string($str,83);
						$cellWid=20;
						$cellHig=$cellHig * count($str2); 
						$pdf->Cell($cellWid,$cellHig,"",1);	
						$str = $row['labor_quantity'];
						$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
						$cellWid=163;
						$pdf->Cell($cellWid,$cellHig,"",1);	
						$stry = 0;
						for ($i12=0 ; $i12 < count($str2) ; $i12++ ) {
							$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$i12]);
							$stry = $stry + 6;
						}
						$cellHig = 6;
					}
				}
			}
			if (isset($_REQUEST['miscCounter_id'])){
				for($i = 1 ; $i <= $_REQUEST['Misc_TotalLines']-1; $i++){
					$getMiscPricing = DB::table('gpg_job_electrical_misc_pricing')->select('misc_quantity','misc_description')->where('gpg_job_electrical_quote_id','=',$job_electrical_quote_id)->where('id','=',$_REQUEST['Misc_consolidate_'.$i])->orderBy('misc_order')->get();	
					$row = array();
					foreach ($getMiscPricing as $key => $value) {
						$row = array('misc_quantity'=>$value->misc_quantity,'misc_description'=>$value->misc_description);
					}
					if($row){
						$pdf->Ln();
						$pdf->Cell(10);
						$cellWid=20;
						$str2 = array();
						$this->break_string($str,83);
						$cellHig=$cellHig * count($str2); 
						$pdf->Cell($cellWid,$cellHig,"",1);	
						$str = $row['misc_quantity'];
						$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
						$cellWid=163;
						$pdf->Cell($cellWid,$cellHig,"",1);	
						$str = $row['misc_description'];
						$stry = 0;
						for ($i12=0 ; $i12 < count($str2) ; $i12++ ) {
							$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$i12]);
							$stry = $stry + 6;
						}
						$cellHig = 6;
					}
				}
			}
			$pdf->Ln(10);
		}// start if big
		else{ // start else
			$getElectricalEquipmentPricing = DB::table('gpg_job_electrical_equipment_pricing')->select('equipment_quantity','equipment_description','equipment_sell_price')->where('gpg_job_electrical_quote_id','=',$job_electrical_quote_id)->orderBy('equipment_order')->get();
		    $subtotal = 0;
			$pdf->Ln(5);
			$cellHig=6; 
			$cellWid=20;
			$pdf->Cell(10);
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$str = 'Quantity';
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
			$cellWid=133;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$str = 'Description';
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
			$cellWid=20;
			$str = 'Sell Price';
			$pdf->Cell('auto',$cellHig,$str,1,0,'C');
			foreach ($getElectricalEquipmentPricing as $key => $row) {
					$pdf->Ln();
					$pdf->Cell(10);
					$pdf->SetFont('Courier','',9);
					$cellWid=20;
					$str2 = array();
					$str = $row->equipment_description;
					$this->break_string($str,70);
					$cellHig=$cellHig * count($str2); 
					$pdf->Cell($cellWid,$cellHig,"",1);	
					$str = $row->equipment_quantity;
					$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
					$cellWid=133;
					$pdf->Cell($cellWid,$cellHig,"",1);	
					$stry = 0;
					for ($jj=0 ; $jj < count($str2) ; $jj++ ){
						$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$jj]);
						$stry = $stry + 6;
					}
					$cellWid=20;
					$str = number_format($this->clear_num($row->equipment_sell_price),2);
					$pdf->Cell('auto',$cellHig,'$'.number_format($this->clear_num($str),2),1,0,'R');	
					$cellHig = 6;
					$subtotal += $row->equipment_sell_price;
			}
			$getElectricalLaborPricing = DB::table('gpg_job_electrical_labor_pricing')->select('labor_quantity','labor_description','labor_sell_price')->where('gpg_job_electrical_quote_id','=',$job_electrical_quote_id)->orderBy('labor_order')->get();
			foreach ($getElectricalLaborPricing as $key => $row) {
				$pdf->Ln();
				$pdf->Cell(10);
				$pdf->SetFont('Courier','',9);
				$cellWid=20;
				$str2 = array();
				$str = $row->labor_description;
				$this->break_string($str,70);
				$cellHig=$cellHig * count($str2); 
				$pdf->Cell($cellWid,$cellHig,"",1);	
				$str = $row->labor_quantity;
				$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
				$cellWid=133;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				$stry =0;
				for ($jj=0 ; $jj < count($str2) ; $jj++ ) {
					$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$jj]);
					$stry = $stry + 6;
				}
				$cellWid=20;
				$str = number_format($this->clear_num($row->labor_sell_price),2);
				$pdf->Cell('auto',$cellHig,'$'.number_format($this->clear_num($str),2),1,0,'R');	
				$cellHig = 6;
		        $subtotal += $row->labor_sell_price;
			}
			$getElectricalMiscPricing = DB::table('gpg_job_electrical_misc_pricing')->select('misc_quantity','misc_description','misc_sell_price')->where('gpg_job_electrical_quote_id','=',$job_electrical_quote_id)->orderBy('misc_order')->get();
    		foreach ($getElectricalMiscPricing as $key => $row) {
				$pdf->Ln();
				$pdf->Cell(10);
				$pdf->SetFont('Courier','',9);
				$cellWid=20;
				$str2 = array();
				$str = $row->misc_description;
				$this->break_string($str,70);
				$cellHig=$cellHig * count($str2); 
				$pdf->Cell($cellWid,$cellHig,"",1);	
				$str = $row->misc_quantity;
				$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
				$cellWid=133;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				$stry =0;
				for ($jj=0 ; $jj < count($str2) ; $jj++ ) {
					$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$jj]);
					$stry = $stry + 6;
				}
				$cellWid=20;
				$str = number_format($this->clear_num($row->misc_sell_price),2);
				$pdf->Cell('auto',$cellHig,'$'.number_format($this->clear_num($str),2),1,0,'R');	
				$cellHig = 6;
				$subtotal += $row->misc_sell_price;
			}
			$pdf->Ln();
			$pdf->Cell(10);
			$cellWid=153;
			$pdf->SetFont('Courier','B',9);
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$str = 'Subtotal';
			$pdf->Text($pdf->GetX()-($cellWid/8)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
			$cellWid=20;
			$str = $subtotal;
			$pdf->Cell('auto',$cellHig,'$'.number_format($this->clear_num($str),2),1,0,'R');	
			$pdf->Ln();
			$cellWid=153;
			$pdf->Cell(10);
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$str = 'Sales Tax';
			$pdf->Text($pdf->GetX()-($cellWid/8)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
			$cellWid=20;
			if (isset($_REQUEST['sale_price_tax']))
				$str = $this->clear_num($_REQUEST['sale_price_tax']);
			$pdf->Cell('auto',$cellHig,'$'.number_format($this->clear_num($str),2),1,0,'R');
			$pdf->Ln();
			$cellWid=153;
			$pdf->Cell(10);
			$str = 'Total With Sales Tax';
			$pdf->Cell($cellWid,$cellHig,$str,1,0,'R');	
			$cellWid=20;
			if (isset($_REQUEST['sale_price_tax']))	
				$str = $subtotal + $this->clear_num($_REQUEST['sale_price_tax']);
			$pdf->Cell('auto',$cellHig,'$'.number_format($this->clear_num($str),2),1,0,'R');
			$pdf->Ln(10);
		} // end else
		if($exceptions_and_clarifications[0]){
			$pdf->Ln(5);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(180,5,"Exceptions and Clarifications:");
			$pdf->Ln(5);
			$column_width = $pdf->w-20;
			$num_bullet = array();
			$num_bullet['bullet'] = 1;
			$num_bullet['margin'] = '. ';
			$num_bullet['indent'] = 2;
			$num_bullet['spacer'] = 2;
			$num_bullet['text'] = array();
			for ($i=0; $i< count($exceptions_and_clarifications); $i++)
			{
				$num_bullet['text'][$i] = $exceptions_and_clarifications[$i];
			}
			$pdf->SetX(15);
			$this->MultiCellBltArray($column_width-$pdf->x, 4, $num_bullet);
		}
		$pdf->Ln(3);
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"Terms and Conditions:");
		$pdf->Ln(1);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5*count($termsAndConditions), htmlspecialchars(stripslashes($termsAndConditions)));

	    $pdf->Ln(5);
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"APCD Regulations: (If permits are required on job involving a generator)");
		$pdf->Ln(1);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"If the location of the generator is within a 1000 ft. of a school pre-school (kindergarten) and grades K through 12, APCD would require a thirty (30) day public mailing notification to school, residence and businesses within a 1000 ft. or a quarter of a mile radius. A particulate matter trap is required to be installed on the generator engine, if the location of the generator is within 328ft. (100 meters) from the nearest property line of a school. APCD permitting process is usually 120 to 160 days plus the 30 day public mailing notification.  Global Power Group can provide both requirements by APCD for an additional cost.");
		$pdf->Ln(5);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"By signing below the undersigned authorizes Global Power Group Inc. to make such inquiries as are necessary to obtain credit information and authorizes banks or suppliers to release information regarding their account.");
		$pdf->Ln(10);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"Accepted By:");
		$pdf->Ln(5);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"Signature________________________ Print Full Name _________________________");
		$pdf->Ln(2);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"on this ______ day of _____________________  20____  PO#  ___________________");
		$pdf->Ln(5);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"Storage fees may be assessed if your job site is not able to accept delivery on the requested date.");
		$pdf->Ln(5);
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"Validity of the Quote:");
		$pdf->Ln(1);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"This quote is valid for 30 days following the quote date. This quote may be modified and/or rescinded by Global Power Group at its sole discretion unless and until accepted on or before the quote date.");
		$pdf->Ln(5);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"Note:  The Terms and conditions of this quotation govern over any conflict between this quotation and customer's purchase order or other document, made either prior or subsequent to this quotation.");
		$pdf->Ln(8);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"Thank you for the opportunity to be of service. If you have any questions or comments please do not hesitate to let me know how I can help you.");
		$pdf->Ln(5);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"Thank You,");
		if($estData['name']!=''){
			$pdf->Ln(1);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(180,5,$estData['name']);
		}
		$pdf->Ln(1);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(10);
	    $pdf->MultiCell(180,5,"Sales Estimator");
		if($estData['email']!=''){
			$pdf->Ln(1);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(180,5,$estData['email']);
		}
		if($estData['phone']!=''){
			$pdf->Ln(1);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(180,5,"Office:".$estData['phone']);	
		}else{
			$pdf->Ln(1);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(180,5,"Office: 619-579-1221");
		}

			$pdf->Output('quote_form.pdf','D');
		}else
			return Redirect::to('quote/job_electrical_quote_frm/'.$_REQUEST['job_id'].'/'.$_REQUEST['job_num'].'');	
	}
	
	/*
	* getEquipPricingPdfFile
	*/
	public function getEquipPricingPdfFile(){
		$table = $_REQUEST['table'];
		$job_quote_id = $_REQUEST["job_id"];
		$jobNum = $_REQUEST["job_num"];
		$cusId = $_REQUEST["_GPG_customer_id"];
		$contactInfoId = $_REQUEST["contact_info_id"];
		$termsAndConditionsId = $_REQUEST["terms_and_conditions_id"];
		if (!empty($_REQUEST['_GPG_estimator_id'])){
			$estId = $_REQUEST["_GPG_estimator_id"];		
		}else
			$estId = $_REQUEST["_GPG_employee_id"];

		if(!empty($contactInfoId)){
			$contactInfo = DB::table('gpg_settings')->where('id','=',$contactInfoId)->pluck('value');	
		}else {
			$contactInfo = '';
		}
		if(!empty($termsAndConditionsId)) {
			$termsAndConditions = DB::table('gpg_settings')->where('id','=',$termsAndConditionsId)->pluck('value'); 
			$jobRecord = DB::table('gpg_job_'.$table.'_quote')->select('*')->Where('id','=',$job_quote_id)->get(); 
			$jobQuoteTblRow = array();
			foreach ($jobRecord as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					$jobQuoteTblRow[$key] = $value;
				}
			}
			if (isset($_REQUEST['scheduleDate']))
				$date = strftime('%B %d, %Y',strtotime($_REQUEST['scheduleDate']));
			else
				$date = date('Y-m-d');
			$cusName = DB::table('gpg_customer')->where('id','=',$cusId)->pluck('name');
			$estData = array();
			$qestData = DB::table('gpg_employee')->select('name','email','phone')->where('id','=',$estId)->get(); 
			foreach ($qestData as $key => $value) {
				$estData = array('name'=>$value->name,'email'=>$value->email,'phone'=>$value->phone);
			}			
			$arr1 =  explode('<br />',nl2br($jobQuoteTblRow['scope_of_work']));
			$evaluation_scope_of_work = array();
			for($i=0; $i<count($arr1); $i++ ){
				$evaluation_scope_of_work[$i] = trim($arr1[$i]);
			}	
			$arr2 =  explode('<br />',nl2br($jobQuoteTblRow['exclusions']));
			$exceptions_and_clarifications = array();
			for($i=0; $i<count($arr2); $i++ ){
				$exceptions_and_clarifications[$i] = trim($arr2[$i]);
			}
			$str2 = array();			
			$grand_total  = $this->clear_num($jobQuoteTblRow['grand_total']);
			///////// **** Generate PDF *******///////
			$pdf=new Fpdf();
			$pdf->SetFont('Arial','',10);
			$pdf->SetMargins(1, 5, 11, 5); 
			$pdf->AddPage();
			$cellWid = 113;
			$cellHig = 35;
	    	$pdf->Cell(80);
			$pdf->Image(storage_path('big_logo.jpg'),80,8,50);
			$pdf->Ln(32);
	    	$pdf->Cell(73);
	    	$pdf->MultiCell(60,5,strip_tags($contactInfo).'Lic# 870741',0,'C');
			$pdf->Ln(8);
			$pdf->SetFont('Times','',12);
    		$pdf->Cell(10);
   			$pdf->MultiCell(60,5,$date);
    		$pdf->Ln();
			$pdf->SetFont('Times','',12);
    		$pdf->Cell(10);
    		$pdf->MultiCell(60,3,'Quote Number: '.$jobNum);
    		$pdf->Ln();
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
    		$pdf->MultiCell(180,3,$cusName);
			$pdf->Ln();
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
    		$pdf->MultiCell(160,3,$_REQUEST['cusAddress1']);
			if($_REQUEST['cusCity'] != '' || $_REQUEST['cusState']!='' || $_REQUEST['cusZip']!=''){
				$pdf->Ln();
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(60,3,$_REQUEST['cusCity'].', '.$_REQUEST['cusState'].' '.$_REQUEST['cusZip']);
			}
			if($_REQUEST['_project_name']!=''){
				$pdf->Ln(5);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(80,5,'Re: '.$_REQUEST['_project_name'].'.');
			}
			if($_REQUEST['_project_contact']!=''){
				$pdf->Ln(5);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(80,5,'Good Day '.$_REQUEST['_project_contact'].',');
			}
			if($evaluation_scope_of_work[0]){
				$pdf->Ln(5);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
				$pdf->MultiCell(180,5,'Evaluation Scope of Work:');
				$pdf->Ln(5);
				$column_width = $pdf->w-20;
			 	$bullet = array();
				$bullet['bullet'] = '';
				$bullet['margin'] = '';
				$bullet['indent'] = 2;
				$bullet['spacer'] = 2;
				$bullet['text'] = array();
				for ($i=0; $i< count($evaluation_scope_of_work); $i++){
					$bullet['text'][$i] = $evaluation_scope_of_work[$i];
				}
				$pdf->SetX(15);
				$this->MultiCellBltArray($column_width-$pdf->x, 4, $bullet);
			}
				$pdf->Ln(1);

			if(isset($_REQUEST['consolidate']) && $_REQUEST['consolidate']=='on') { 
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
				$grand_total_str="The cost to complete the above scope of work is ".'$'.number_format( $this->clear_num($grand_total),2)." ";
				$grand_total_str .= convert_number_decimals(clear_num(number_format($grand_total,2))).".";
			    $pdf->MultiCell(180,5,$grand_total_str);
				$chk = 0;
				for($i = 1 ; $i <= $_REQUEST['genCounter_id']-1; $i++) {
				    if($_REQUEST['Equipment_consolidate_'.$i]){
						$chk = 1;
						break;
					}
				}
			    for($i = 1 ; $i <= $_REQUEST['labCounter_id']-1; $i++) {
			    	if($_REQUEST['Labor_consolidate_'.$i]){
						$chk = 1;
						break;
					}
			    }
			    for($i = 1 ; $i <= $_REQUEST['miscCounter_id']-1; $i++) {
				    if($_REQUEST['Misc_consolidate_'.$i]){
						$chk = 1;
						break;
					}
			    }
			    if($chk == 1){
					$pdf->Ln(5);
					$cellHig=6; 
					$cellWid=20;
					$pdf->Cell(10);
					$pdf->Cell($cellWid,$cellHig,"",1);	
					$str = 'Quantity';
					$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
					$cellWid=163;
					$pdf->Cell($cellWid,$cellHig,"",1);	
					$str = 'Description';
					$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
				}
				if (isset($_REQUEST['genCounter_id'])){
					for($i = 1 ; $i <= $_REQUEST['genCounter_id']-1; $i++) {
						$getElectricalEquipmentPricing = DB::table('gpg_job_'.$table.'_equipment_pricing')->select('equipment_quantity','equipment_description')->where('gpg_job_'.$table.'_quote_id','=',$job_quote_id)->where('id','=',$_REQUEST['Equipment_consolidate_'.$i])->orderBy('equipment_order')->get();
						$row= array();
						foreach ($getElectricalEquipmentPricing as $key => $value){
							$row = array('equipment_quantity'=>$value->equipment_quantity,'equipment_description'=>$value->equipment_description);
						}						
						if($row){
							$pdf->Ln();
							$pdf->Cell(10);
							$pdf->SetFont('Courier','',9);				 
							$cellWid=20;
							$str = $row['equipment_description'];
							$str2 = array();
							$this->break_string($str,83);
						    $cellHig=$cellHig * count($str2);
							$pdf->Cell($cellWid,$cellHig,"",1);	
							$str = $row['equipment_quantity'];
							$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
							$cellWid=163;
							$pdf->Cell($cellWid,$cellHig,"",1);	
							$stry = 0;
							for ($i12=0 ; $i12 < count($str2) ; $i12++ ){
								$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$i12]);
								 $stry += 5;
							}
							$cellHig = 6;
							$mulval = '';
						} 
					}
				}
				if (isset($_REQUEST['labCounter_id'])){
					for($i = 1 ; $i <= $_REQUEST['labCounter_id']-1; $i++){
					$getLaborPricing = DB::table('gpg_job_'.$table.'_labor_pricing')->select('labor_quantity','labor_description')->where('gpg_job_'.$table.'_quote_id','=',$job_quote_id)->where('id','=',$_REQUEST['Labor_consolidate_'.$i])->orderBy('labor_order')->get();
					$row = array();
					foreach ($getLaborPricing as $key => $value) {
						$row = array('labor_quantity'=>$value->labor_quantity,'labor_description'=>$value->labor_description);
					}			
					if($row){
						$pdf->Ln();
						$pdf->Cell(10);
						$pdf->SetFont('Courier','',9);
						$str = $row['labor_description'];
						$str2 = array();
						$this->break_string($str,83);
						$cellWid=20;
						$cellHig=$cellHig * count($str2); 
						$pdf->Cell($cellWid,$cellHig,"",1);	
						$str = $row['labor_quantity'];
						$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
						$cellWid=163;
						$pdf->Cell($cellWid,$cellHig,"",1);	
						$stry = 0;
						for ($i12=0 ; $i12 < count($str2) ; $i12++ ) {
							$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$i12]);
							$stry = $stry + 6;
						}
						$cellHig = 6;
					}
				}
			}
			if (isset($_REQUEST['miscCounter_id'])){
				for($i = 1 ; $i <= $_REQUEST['Misc_TotalLines']-1; $i++){
					$getMiscPricing = DB::table('gpg_job_'.$table.'_misc_pricing')->select('misc_quantity','misc_description')->where('gpg_job_'.$table.'_quote_id','=',$job_quote_id)->where('id','=',$_REQUEST['Misc_consolidate_'.$i])->orderBy('misc_order')->get();	
					$row = array();
					foreach ($getMiscPricing as $key => $value) {
						$row = array('misc_quantity'=>$value->misc_quantity,'misc_description'=>$value->misc_description);
					}
					if($row){
						$pdf->Ln();
						$pdf->Cell(10);
						$cellWid=20;
						$str2 = array();
						$this->break_string($str,83);
						$cellHig=$cellHig * count($str2); 
						$pdf->Cell($cellWid,$cellHig,"",1);	
						$str = $row['misc_quantity'];
						$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
						$cellWid=163;
						$pdf->Cell($cellWid,$cellHig,"",1);	
						$str = $row['misc_description'];
						$stry = 0;
						for ($i12=0 ; $i12 < count($str2) ; $i12++ ) {
							$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$i12]);
							$stry = $stry + 6;
						}
						$cellHig = 6;
					}
				}
			}
			$pdf->Ln(10);
		}else{
			$getElectricalEquipmentPricing = DB::table('gpg_job_'.$table.'_equipment_pricing')->select('equipment_quantity','equipment_description','equipment_sell_price')->where('gpg_job_'.$table.'_quote_id','=',$job_quote_id)->orderBy('equipment_order')->get();
		    $subtotal = 0;
			$pdf->Ln(5);
			$cellHig=6; 
			$cellWid=20;
			$pdf->Cell(10);
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$str = 'Quantity';
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
			$cellWid=133;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$str = 'Description';
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
			$cellWid=20;
			$str = 'Sell Price';
			$pdf->Cell('auto',$cellHig,$str,1,0,'C');
			foreach ($getElectricalEquipmentPricing as $key => $row){
					$pdf->Ln();
					$pdf->Cell(10);
					$pdf->SetFont('Courier','',9);
					$cellWid=20;
					$str2 = array();
					$str = $row->equipment_description;
					$this->break_string($str,70);
					$cellHig=$cellHig * count($str2); 
					$pdf->Cell($cellWid,$cellHig,"",1);	
					$str = $row->equipment_quantity;
					$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
					$cellWid=133;
					$pdf->Cell($cellWid,$cellHig,"",1);	
					$stry = 0;
					for ($jj=0 ; $jj < count($str2) ; $jj++ ){
						$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$jj]);
						$stry = $stry + 6;
					}
					$cellWid=20;
					$str = number_format($this->clear_num($row->equipment_sell_price),2);
					$pdf->Cell('auto',$cellHig,'$'.number_format($this->clear_num($str),2),1,0,'R');	
					$cellHig = 6;
					$subtotal += $row->equipment_sell_price;
			}
			$getElectricalLaborPricing = DB::table('gpg_job_'.$table.'_labor_pricing')->select('labor_quantity','labor_description','labor_sell_price')->where('gpg_job_'.$table.'_quote_id','=',$job_quote_id)->orderBy('labor_order')->get();
			foreach ($getElectricalLaborPricing as $key => $row){
				$pdf->Ln();
				$pdf->Cell(10);
				$pdf->SetFont('Courier','',9);
				$cellWid=20;
				$str2 = array();
				$str = $row->labor_description;
				$this->break_string($str,70);
				$cellHig=$cellHig * count($str2); 
				$pdf->Cell($cellWid,$cellHig,"",1);	
				$str = $row->labor_quantity;
				$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
				$cellWid=133;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				$stry =0;
				for ($jj=0 ; $jj < count($str2) ; $jj++ ) {
					$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$jj]);
					$stry = $stry + 6;
				}
				$cellWid=20;
				$str = number_format($this->clear_num($row->labor_sell_price),2);
				$pdf->Cell('auto',$cellHig,'$'.number_format($this->clear_num($str),2),1,0,'R');	
				$cellHig = 6;
		        $subtotal += $row->labor_sell_price;
			}
			$getElectricalMiscPricing = DB::table('gpg_job_'.$table.'_misc_pricing')->select('misc_quantity','misc_description','misc_sell_price')->where('gpg_job_'.$table.'_quote_id','=',$job_quote_id)->orderBy('misc_order')->get();
    		foreach ($getElectricalMiscPricing as $key => $row){
				$pdf->Ln();
				$pdf->Cell(10);
				$pdf->SetFont('Courier','',9);
				$cellWid=20;
				$str2 = array();
				$str = $row->misc_description;
				$this->break_string($str,70);
				$cellHig=$cellHig * count($str2); 
				$pdf->Cell($cellWid,$cellHig,"",1);	
				$str = $row->misc_quantity;
				$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
				$cellWid=133;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				$stry =0;
				for ($jj=0 ; $jj < count($str2) ; $jj++ ) {
					$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+4+$stry,$str2[$jj]);
					$stry = $stry + 6;
				}
				$cellWid=20;
				$str = number_format($this->clear_num($row->misc_sell_price),2);
				$pdf->Cell('auto',$cellHig,'$'.number_format($this->clear_num($str),2),1,0,'R');	
				$cellHig = 6;
				$subtotal += $row->misc_sell_price;
			}
				$pdf->Ln();
				$pdf->Cell(10);
				$cellWid=153;
				$pdf->SetFont('Courier','B',9);
				$pdf->Cell($cellWid,$cellHig,"",1);	
				$str = 'Subtotal';
				$pdf->Text($pdf->GetX()-($cellWid/8)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
				$cellWid=20;
				$str = $subtotal;
				$pdf->Cell('auto',$cellHig,'$'.number_format( $this->clear_num($str),2),1,0,'R');
				$pdf->Ln();
				$cellWid=153;
				$pdf->Cell(10);
				$pdf->Cell($cellWid,$cellHig,"",1);	
				$str = 'Sales Tax';
				$pdf->Text($pdf->GetX()-($cellWid/8)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+4,$str);
				$cellWid=20;
				$str =  $this->clear_num($_REQUEST['sale_price_tax']);
				$pdf->Cell('auto',$cellHig,'$'.number_format( $this->clear_num($str),2),1,0,'R');
				$pdf->Ln();
				$cellWid=153;
				$pdf->Cell(10);
				$str = 'Total With Sales Tax';
				$pdf->Cell($cellWid,$cellHig,$str,1,0,'R');	
				$cellWid=20;
				$str = $subtotal +  $this->clear_num($_REQUEST['sale_price_tax']);
				$pdf->Cell('auto',$cellHig,'$'.number_format( $this->clear_num($str),2),1,0,'R');
				$pdf->Ln(10);
		}
			$pdf->Ln(5);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(180,5,"Signature________________________ Print Full Name _________________________");
			$pdf->Ln(2);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(180,5,"on this ______ day of _____________________  20____  PO#  ___________________");
		    $pdf->Ln(5);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(180,5,"Thank You,");
			if($estData['name']!=''){
				$pdf->Ln(1);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(180,5,$estData['name']);
			}
			$pdf->Ln(1);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(10);
		    $pdf->MultiCell(180,5,"Sales Estimator");
			if($estData['email']!=''){
				$pdf->Ln(1);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(180,5,$estData['email']);
			}
			if($estData['phone']!=''){
				$pdf->Ln(1);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(180,5,"Office:".$estData['phone']);	
			}else{
				$pdf->Ln(1);
				$pdf->SetFont('Times','',12);
				$pdf->Cell(10);
			    $pdf->MultiCell(180,5,"Office: 619-579-1221");
			}
	
		$pdf->Output('equipments_quote_form.pdf','D');	
		}else
			return Redirect::to('quote/job_'.$table.'_quote_frm/'.$_REQUEST['job_id'].'/'.$_REQUEST['job_num'].'');		
	}

	/*
	* getElecticalSubQuotePdfFile
	*/
	public function getElecticalSubQuotePdfFile(){
		$job_electrical_quote_id = Input::get('job_id');
		$ProInfo = DB::table('gpg_job_electrical_quote')->select('GPG_customer_id','project_name','project_address','project_city','project_state','project_zip','project_contact','project_phone','schedule_date')->where('id','=',$job_electrical_quote_id)->get();
		$ProInfoRow = array();
		foreach ($ProInfo as $key => $value) {
			$ProInfoRow = array('GPG_customer_id'=>$value->GPG_customer_id,'project_name'=>$value->project_name,'project_address'=>$value->project_address,'project_city'=>$value->project_city,'project_state'=>$value->project_state,'project_zip'=>$value->project_zip,'project_contact'=>$value->project_contact,'project_phone'=>$value->project_phone,'schedule_date'=>$value->schedule_date);	
		}	
		$CusInfo = DB::table('gpg_customer')->select('name','address','address2','city','state','zipcode','phone_no')->where('id','=',$ProInfoRow['GPG_customer_id'])->get();
		$cusRow = array();
		foreach ($CusInfo as $key => $value){
			$cusRow = array('name'=>$value->name,'address'=>$value->address,'address2'=>$value->address2,'city'=>$value->city,'state'=>$value->state,'zipcode'=>$value->zipcode,'phone_no'=>$value->phone_no);
		}
		$subquote_query = DB::table('gpg_job_electrical_subquote')->select('*')->where('job_electrical_quote_id','=',$job_electrical_quote_id)->get();
		$arr_subquote = array();
		$rebate_ids = array();
		foreach ($subquote_query as $key => $sub_arr) {
			$proposed_fixture_watts = DB::table('gpg_job_electrical_subquote_proposed_fixtures')->where('id','=',$sub_arr['gpg_job_electrical_subquote_proposed_fixtures_id'])->pluck('watts');
			$existing_fixture_watts = DB::table('gpg_job_electrical_subquote_existing_fixtures')->where('id','=',$sub_arr['gpg_job_electrical_subquote_existing_fixtures_id'])->pluck('watts');
			$loop=0;
			if($sub_arr['gpg_rebate1_id']!=0 && $sub_arr['gpg_rebate1_id']){
				 if($sub_arr['gpg_rebate1_id'])
				 {
					 $rebate_ids[] = $sub_arr['gpg_rebate1_id'];
					 $arr_subquote[$sub_arr['gpg_rebate1_id']][] = array('has_qty'=>0,'Lamps' => $sub_arr["lamps_fixture_quantity_pro"],'Fixtures' => $sub_arr["fixture_quantity_pro"],"watts_ex" => $existing_fixture_watts,"watts_pro" => $proposed_fixture_watts);
				 }
				 if($sub_arr['gpg_rebate2_id'])
				 {
					 $rebate_ids[] = $sub_arr['gpg_rebate2_id'];
					 $arr_subquote[$sub_arr['gpg_rebate2_id']][] = array('has_qty'=>0,'Lamps' => $sub_arr["lamps_fixture_quantity_pro"],'Fixtures' => $sub_arr["fixture_quantity_pro"],"watts_ex" => $existing_fixture_watts,"watts_pro" => $proposed_fixture_watts);
				 }
				 if($sub_arr['gpg_rebate3_id'])
				 {
					 $rebate_ids[] = $sub_arr['gpg_rebate3_id'];
					 $arr_subquote[$sub_arr['gpg_rebate3_id']][] = array('has_qty'=>0,'Lamps' => $sub_arr["lamps_fixture_quantity_pro"],'Fixtures' => $sub_arr["fixture_quantity_pro"],"watts_ex" => $existing_fixture_watts,"watts_pro" => $proposed_fixture_watts);
				 }
				 if($sub_arr['gpg_rebate4_id'])
				 {
					 $rebate_ids[] = $sub_arr['gpg_rebate4_id'];
					 $arr_subquote[$sub_arr['gpg_rebate4_id']][] = array('has_qty'=>1,'gpg_rebate4_qty' => $sub_arr["gpg_rebate4_qty"],"watts_ex" => $existing_fixture_watts,"watts_pro" => $proposed_fixture_watts,"watts_ex" => $existing_fixture_watts,"watts_pro" => $proposed_fixture_watts);
				 }
				 
			}
		}//end foreach
		$str_rebate_ids = implode(",",$rebate_ids);
		if(!empty($str_rebate_ids))
	 		$rebates_qry = DB::select(DB::raw("SELECT id,rebate_type,rebate_description,rebate_measure,rebate_amount FROM gpg_rebate WHERE id IN (".$str_rebate_ids.")"));
		$data_arr = array();
		$loop = 0;
		if (!empty($rebates_qry))
		foreach ($rebates_qry as $key => $rebate_arr) {
			$data_arr[$loop]['rebate_type'] = $rebate_arr["rebate_type"];
			$data_arr[$loop]['rebate_measure'] = $rebate_arr["rebate_measure"];
			$data_arr[$loop]['rebate_description'] = $rebate_arr["rebate_description"];
			$data_arr[$loop]['rebate_amount'] = $rebate_arr["rebate_amount"];
			$data_arr[$loop]['units'] = 0;
			foreach($arr_subquote[$rebate_arr['id']] as $key => $value)
			{
				$data_arr[$loop]['watts_pro'][$value["watts_pro"]] = $value["watts_pro"];
				$data_arr[$loop]['watts_ex'][$value["watts_ex"]] = $value["watts_ex"];
				if($value["has_qty"])
				{
					$data_arr[$loop]['units'] += $value["gpg_rebate4_qty"];
				}
				else
				{
					if($rebate_arr['rebate_type']=='Fixture')
					{
						$data_arr[$loop]['units'] += $value["Fixtures"];
					} else {
						$data_arr[$loop]['units'] += ($value["Fixtures"] * $value["Lamps"]);
					}
				}
			}
			$loop++;
		}
			$pdf=new Fpdf();
			$pdf->SetFont('Arial','',10);
			$pdf->SetMargins(1, 5); 
			$pdf->AddPage();
			$cellHig = 8;
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"CUSTOMER NAME:");
			$pdf->SetFont('Courier','',9);
			$str = $cusRow['name'];
			$pdf->Text($pdf->GetX()-$cellWid+24,$pdf->GetY()+3,substr($str,0,18));
			$pdf->Text($pdf->GetX()-$cellWid+2,$pdf->GetY()+7,substr($str,18,strlen($str)));
			$cellWid = 83;
			$pdf->Cell($cellWid,$cellHig,"");	
			$pdf->Image(storage_path('big_logo.jpg'),$pdf->GetX()-$cellWid,$pdf->GetY(),$cellWid,40);
			$cellWid = 30;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PROJECT INFO");
			$pdf->SetFont('Courier','',9);
			$str = '';//$poRow['id'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"DATE");
			$pdf->SetFont('Courier','',9);
			$str = ($ProInfoRow['schedule_date']!=""?date('$',strtotime($ProInfoRow['schedule_date'])):date('Y-m-d'));
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ADDRESS:");
			$pdf->SetFont('Courier','',9);
			$str = $cusRow['address'];
			$pdf->Text($pdf->GetX()-$cellWid+15,$pdf->GetY()+3,substr($str,0,22));
			$pdf->Text($pdf->GetX()-$cellWid+2,$pdf->GetY()+7,substr($str,22,strlen($str)));
			$pdf->SetFont('Arial','',7);
			$cellWid = 83;
			$pdf->Cell($cellWid,$cellHig,"");
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"NAME");
			$pdf->SetFont('Courier','',9);
			$str = $ProInfoRow['project_name'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ADDRESS 2:");
			$pdf->SetFont('Courier','',9);
			$str = $cusRow['address2'];
			$pdf->Text($pdf->GetX()-$cellWid+16,$pdf->GetY()+3,substr($str,0,22));
			$pdf->Text($pdf->GetX()-$cellWid+2,$pdf->GetY()+7,substr($str,22,strlen($str)));
			$pdf->SetFont('Arial','',7);
			$cellWid = 83;
			$pdf->Cell($cellWid,$cellHig,"");	
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ADDRESS:");
			$pdf->SetFont('Courier','',9);
			$str =  $ProInfoRow['project_address'];
			$pdf->Text($pdf->GetX()-$cellWid+15,$pdf->GetY()+3,substr($str,0,22));
			$pdf->Text($pdf->GetX()-$cellWid+2,$pdf->GetY()+7,substr($str,22,strlen($str)));
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid=30;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"CITY");
			$pdf->SetFont('Courier','',9);
			$str = $cusRow['city'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=10;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"STATE");
			$pdf->SetFont('Courier','',9);
			$str = $cusRow['state'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=20;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ZIP CODE");
			$pdf->SetFont('Courier','',9);
			$str = $cusRow['zipcode'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=83;
			$pdf->Cell($cellWid,$cellHig,"");
			$cellWid=30;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"CITY");
			$pdf->SetFont('Courier','',9);
			$str = $ProInfoRow['project_city'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=11;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"STATE");
			$pdf->SetFont('Courier','',9);
			$str = $ProInfoRow['project_state'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=19;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ZIP CODE");
			$pdf->SetFont('Courier','',9);
			$str = $ProInfoRow['project_zip'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PHONE");
			$pdf->SetFont('Courier','',9);
			$str = $cusRow['phone_no'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=83;
			$pdf->Cell($cellWid,$cellHig,"");
			$cellWid = 30;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PHONE");
			$pdf->SetFont('Courier','',9);
			$str = $ProInfoRow['project_phone'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"CONTACT");   	
	  	  	$pdf->SetFont('Courier','',9);
			$str = $ProInfoRow['project_contact'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$pdf->Ln();
			$cellWid=203;
			$cellHig = 6;
			$pdf->SetFillColor(2,74,146);
			$pdf->SetFont('Arial','B',11);
			$pdf->SetTextColor(255, 255, 255);
			$pdf->Cell($cellWid,$cellHig,"LIGHTING WORKSHEET",0,1,'L',true);
			$cellHig=20;
			$pdf->Ln(2);
			$pdf->SetFont('Arial','B',7);
			$pdf->SetTextColor(0,0,0);
			$cellWid=15;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX() - $cellWid + 2.5,$pdf->GetY()+$cellHig/2,"Product");
			$pdf->Text($pdf->GetX() - $cellWid + 3.5,$pdf->GetY()+($cellHig/2)+3,"Code");
			$cellWid=80;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX() - $cellWid/1.5 ,$pdf->GetY()+$cellHig/2,"Product Description");
			$cellWid=15;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX() - $cellWid + 2.5,$pdf->GetY()+$cellHig/2,"Units");
			$cellWid=15;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX() - $cellWid + 2.5,$pdf->GetY()+$cellHig/2,"$/Units");
			$pdf->Text($pdf->GetX() - $cellWid + 6,$pdf->GetY()+($cellHig/2)+3,"A");
			$cellWid=15;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX() - $cellWid + 2.5,$pdf->GetY()+$cellHig/2,"# of Units");
			$pdf->Text($pdf->GetX() - $cellWid + 7,$pdf->GetY()+($cellHig/2)+3,"B");
			$cellWid=18;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX() - $cellWid + 2,$pdf->GetY()+$cellHig/2,"Rebate Total");
			$pdf->Text($pdf->GetX() - $cellWid + 3,$pdf->GetY()+($cellHig/2)+3,"(A X B = C)");
			$cellWid=15;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX() - $cellWid + 2,$pdf->GetY()+$cellHig/3,"New Lamp");
			$pdf->Text($pdf->GetX() - $cellWid + 2.5,$pdf->GetY()+($cellHig/2),"& Ballast");
			$pdf->Text($pdf->GetX() - $cellWid + 3,$pdf->GetY()+($cellHig/2)+3,"Wattage");
			$cellWid=15;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX() - $cellWid + 2,$pdf->GetY()+$cellHig/3,"Existing");
			$pdf->Text($pdf->GetX() - $cellWid + 2.5,$pdf->GetY()+($cellHig/2),"Lamp &");
			$pdf->Text($pdf->GetX() - $cellWid + 3,$pdf->GetY()+($cellHig/2)+3,"Ballast");
			$pdf->Text($pdf->GetX() - $cellWid + 3,$pdf->GetY()+($cellHig/2)+6,"Wattage");
			$cellWid=15;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX() - $cellWid + 2.5,$pdf->GetY()+($cellHig/6),"Existing");
			$pdf->Text($pdf->GetX() - $cellWid + .5,$pdf->GetY()+($cellHig/3),"Technology");
			$pdf->Text($pdf->GetX() - $cellWid + 1.5,$pdf->GetY()+($cellHig/2),"(Refer to");
			$pdf->Text($pdf->GetX() - $cellWid + 1,$pdf->GetY()+($cellHig/2)+3,"Ts&Cs for");
			$pdf->Text($pdf->GetX() - $cellWid + 1,$pdf->GetY()+($cellHig/2)+6,"acceptable");
			$pdf->Text($pdf->GetX() - $cellWid + 2.5,$pdf->GetY()+($cellHig/2)+9,"options)");
			$pdf->Ln();
			$cellWid=203;
			$cellHig = 2;
			$pdf->SetFillColor(2,74,146);
			$pdf->Cell($cellWid,$cellHig,"",1,0,'L',true);
			$pdf->Ln();
			$cellHig = 6;
			$pdf->SetFont('Arial','',6);
			foreach($data_arr as $key=> $value){
				$size = sizeof($value["watts_pro"])>sizeof($value["watts_ex"])?sizeof($value["watts_pro"]):sizeof($value["watts_ex"]);
				$cellHig = 6;
				if($size>1)
					$cellHig = $size*4;
				$a=0;
				$b=0;
				$str = $value['rebate_description'];
				$str2 = array();
				break_string($str,75);
				
				if (count($str2)>= 75){
					$cellHig = 6;
				}else{
					$cellHig=$cellHig * count($str2);
				}
				$str = '';
				$pdf->Ln();
				$cellWid=15;
				$pdf->Cell($cellWid,$cellHig,$value['rebate_measure'],1);
				
				$cellWid=80;
				$pdf->Cell($cellWid,$cellHig,"",1);
				$stry = 0;
				if(count($str2)>1)
					$temp_height = 4 ;
				else
					$temp_height = $cellHig/2;
				for($i12=0 ; $i12 < count($str2) ; $i12++ ) {
					$pdf->Text($pdf->GetX()-$cellWid + 1,$pdf->GetY()+$temp_height+$stry,$str2[$i12]);
					$stry += 5;
				}
				$cellWid=15;
				$pdf->Cell($cellWid,$cellHig,$value['rebate_type'],1);
				$cellWid=15;
				$pdf->SetFillColor(253,251,192);
				$pdf->Cell($cellWid,$cellHig,'$'.$value['rebate_amount'],1,0,'L',true);
				$a = $value['rebate_amount'];
				$cellWid=15;
				$pdf->SetFillColor(200,220,255);
				$pdf->Cell($cellWid,$cellHig,$value['units'],1,0,'L',true);
				$b=$value['units'];
				$cellWid=18;
				$pdf->SetFillColor(200,220,255);
				$pdf->Cell($cellWid,$cellHig,'$'.number_format($a*$b,2,'.',','),1,0,'L',true);
				$cellWid=15;
				$pdf->SetFillColor(200,220,255);
				$pdf->Cell($cellWid,$cellHig,"",1,0,'L',true);
				$str= "";
				$num=0;
				$temp_num = 4;
				if($size==1)
					$temp_num = $cellHig/2;
				foreach($value["watts_pro"] as $pro_key => $pro_value)
				{
					$pdf->Text($pdf->GetX() - $cellWid,$pdf->GetY()+$temp_num+$num," ".$pro_key);
					$num+=3;
					//$pdf->Text($pdf->GetX() - $cellWid + 3,$pdf->GetY()+($cellHig/2)+3,$pro_key);
				}
				$str = substr($str,0,strlen($str)-2);
				$cellWid=15;
				$pdf->SetFillColor(200,220,255);
				$pdf->Cell($cellWid,$cellHig,"",1,0,'L',true);
				$num=0;
				$temp_num = 4;
				if($size==1)
					$temp_num = $cellHig/2;
				foreach($value["watts_ex"] as $ex_key => $ex_value){
					$pdf->Text($pdf->GetX() - $cellWid,$pdf->GetY()+$temp_num+$num," ".$ex_key);
					$num+=3;
				}
				$cellWid=15;
				$pdf->SetFillColor(200,220,255);
				$pdf->Cell($cellWid,$cellHig,"",1,0,'L',true);
		}//end foreach
		$pdf->Output('electrica_subquote_form.pdf','D');	
	}

	/*
	* string breaking
	*/
	public function break_string($str,$limit) {
		if (empty($str2))
			$str2 = array();
		if (strlen($str)>$limit) { 
				$str2[] =  substr($str,0,$limit).(substr($str,$limit,1)!=' '?'-':'');
				$str = str_replace(substr($str,0,$limit),'',$str);
				$this->break_string($str,$limit);
			} else {
				$str2[] = $str;
		}
		return $str2;
	}	
	/*
	* multi cell araay
	*/
	public function MultiCellBltArray($w, $h, $blt_array, $border=0, $align='J', $fill=0)
	{
		if (!is_array($blt_array)){
			return 0;
		}
		//Save x.
		$pdf = new Fpdf();
		$bak_x = $w;
		for ($i=0; $i<sizeof($blt_array['text']); $i++){
			//Get bullet width including margin
			$blt_width = $pdf->GetStringWidth($blt_array['bullet'] . $blt_array['margin'])+$pdf->cMargin*2;
			// SetX
			$pdf->SetX($bak_x);
			//Output indent
			if ($blt_array['indent'] > 0)
				$pdf->Cell($blt_array['indent']);
			//Output bullet
			$pdf->Cell($blt_width, $h, $blt_array['bullet'] . $blt_array['margin'], 0, '', $fill);
			//Output text
			//if($w-$blt_width>0 && $h>0)
			//$pdf->MultiCell($w-$blt_width, $h, $blt_array['text'][$i], $border, $align, $fill);
			//Insert a spacer between items if not the last item
			if ($i != sizeof($blt_array['text'])-1)
				$pdf->Ln($blt_array['spacer']);
			//Increment bullet if it's a number
			if (is_numeric($blt_array['bullet']))
				$blt_array['bullet']++;
		}
			//Restore x
			$pdf->x = $bak_x;
	}
	/*
	* updateJobCat
	*/
	public function updateJobCat(){

		$id = Input::get('id');
		$name = Input::get('name');
		$prev_name = Input::get('prev_name');
		if ($prev_name == $name)
			$rules = array(
			        'name' => 'required|max:150'           // required and has to match the password field
		    );
		else
			$rules = array(
			        'name' => 'required|unique:gpg_job_type|max:150'           // required and has to match the password field
		    );
	    $validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
		    return Redirect::to('quote/'.$id.'/edit')->withErrors($validator);	
		}else
			DB::table('gpg_job_type')->where('id','=',$id)->update(array('name'=>$name));
		return Redirect::to('quote/')->withSuccess('Job Type Successfully Updated!');
	}
	/*
	* shopWorkQuoteForm
	*/
	public function shopWorkQuoteForm($id,$j_num){
		$jobRecord = DB::table('gpg_shop_work_quote')->select('*')->where('id','=',$id)->get();
		$jobRecords = array();
		foreach ($jobRecord as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$jobRecords[$key] = $value; 
			}
		}
		$queryComponent = DB::select(DB::raw("select * from gpg_shop_work_quote_component where gpg_shop_work_quote_id = '$id' order by id asc"));
		$queryComponenRows = array();
		foreach ($queryComponent as $key2 => $value2) {
			$queryComponenRows[] = (array)$value2;
		}
		$query2 = DB::select(DB::raw("select * from gpg_shop_work_quote_material where gpg_shop_work_quote_id = '$id' order by id asc"));
		$queryMaterialRes = array();
		foreach ($query2 as $key => $value) {
			$queryMaterialRes[] = (array)$value;
		}
		$getMaterial = DB::table('gpg_field_material_type')->select('id','name')->orderBy('name', 'asc')->lists('name','id');
		$queryLabor = DB::table('gpg_shop_work_quote_labor')->select('*')->where('gpg_shop_work_quote_id','=',$id)->where('type','=','A')->orderBy('id','asc')->get();
		$queryLabor_arr = array();
		foreach ($queryLabor as $key => $value) {
			$queryLabor_arr[] = (array)$value;
		}
		$querySubLabor = DB::table('gpg_shop_work_quote_labor')->select('*')->where('gpg_shop_work_quote_id','=',$id)->where('type','=','S')->orderBy('id','asc')->get();
		$querySubLabor_arr = array();
		foreach ($querySubLabor as $key => $value) {
			$querySubLabor_arr[] = (array)$value;
		}
		$queryOther = DB::table('gpg_shop_work_quote_other')->select('*')->where('gpg_shop_work_quote_id','=',$id)->orderBy('id','asc')->get();
		$queryOtherCharge = array();
		foreach ($queryOther as $key => $value) {
			$queryOtherCharge[] = (array)$value;	
		}
		$gpg_vendor = DB::table('gpg_vendor')->select('id','name')->where('status', '=', 'A')->orderBy('name', 'asc')->lists('name','id');
		$equipments = DB::table('gpg_field_component_type')->select('id','name')->orderBy('name')->lists('name','id');
		$sales_persons = DB::table('gpg_employee')->select('id','name')->where('status','=','A')->lists('name','id');
		$customers = DB::table('gpg_customer')->select('id','name')->lists('name','id');
		$params = array('job_id'=>$id,'job_num'=>$j_num,'sales_persons'=>$sales_persons,'customers'=>$customers,'shopWorkQuoteTblRow'=>$jobRecords,'queryComponenRows'=>$queryComponenRows,'equipments'=>$equipments,'gpg_vendor'=>$gpg_vendor,'queryMaterialRes'=>$queryMaterialRes,'getMaterial'=>$getMaterial,'queryLabor_arr'=>$queryLabor_arr,'querySubLabor_arr'=>$querySubLabor_arr,'queryOtherCharge'=>$queryOtherCharge);
		return View::make('job.shop_work_quote_frm', $params);
	}
	/*
	* addNewTypenPart
	*/
	public function addNewTypenPart(){
		$allInputs = Input::except('_token');
		Input::flash();
		$job_id = Input::get('job_id');
		$job_num = Input::get('job_num');
		$type = Input::get('type'); 
		$_type_name = Input::get('_type_name'); 
		$_description = Input::get('_description'); 
		$_part_number = Input::get('_part_number'); 
		$_manufacturer = Input::get('_manufacturer'); 
		$_cost = Input::get('_cost'); 
		$_margin = Input::get('_margin'); 
		$_list = Input::get('_list'); 
		$_gpg_vendor_id = Input::get('_gpg_vendor_id'); 
		$new_vendor_name = Input::get('new_vendor_name'); 
		$_gpg_vendor_cost = Input::get('_gpg_vendor_cost'); 
		$_note = Input::get('_note'); 
		$_model_number = Input::get('_model_number'); 
		$_serial_number = Input::get('_serial_number'); 
		$_spec_number = Input::get('_spec_number');
		$type = Input::get('type');
		$new_vendor_name = Input::get('new_vendor_name');
		$part_name = Input::get('_part_number');

		if(!empty($new_vendor_name) && !empty($part_name)){
			$chkVendor = DB::table('gpg_vendor')->where('name','=',$new_vendor_name)->pluck('id');
			if (!$chkVendor){ 									   
				$getVenderId = DB::table('gpg_vendor')->max('id') + 1;
				DB::table('gpg_vendor')->insert(array('id'=>$getVenderId,'name'=>$new_vendor_name,'status'=>'A','created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				$newVendorOption = '<option value="'.$getVenderId.'">'.$new_vendor_name.'</option>';
				$_gpg_vendor_id = $getVenderId;
			} else {
				$_gpg_vendor_id = $chkVendor;
			}
		}

		switch ($type) {
			case "comp":
					$type_name = $_type_name;
					$checkType = DB::table('gpg_field_component_type')->where('name','LIKE','%'.$type_name.'%')->pluck('id');
					if (empty($checkType)){
						$getTypeId = DB::table('gpg_field_component_type')->max('id')+1;
						DB::table('gpg_field_component_type')->insert(array('id'=>$getTypeId,'name'=>$type_name,'status'=>'A','created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
					}else{
						$getTypeId = $checkType;
					}
					$queryPart = array(); 
					while (list($ke,$vl)= each($_POST)) {
						if (preg_match("/^_/i",$ke) && $ke!='type' && $ke!='_ittr' && !trim(empty($vl)) && $ke!='_type_name' && $ke!='_token' && $ke!='_gpg_field_component_type_id') 
							$queryPart[substr($ke,1,strlen($ke))] = trim($vl);
					}
					
					$checkPart = DB::table('gpg_field_component')->where('gpg_field_component_type_id','=',$getTypeId)->where('part_number','LIKE','%'.$part_name.'%')->pluck('id');
					if (empty($checkPart) && !empty($part_name)){
						$getPartId = DB::table('gpg_field_component')->max('id');
						DB::table('gpg_field_component')->insert(array('id'=>$getPartId,'gpg_field_component_type_id'=>$getTypeId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'))+$queryPart);
					} else {
						$getPartId	=  $checkPart;
					}
					
					$tblName = "gpg_field_component";
					$queryPart = " gpg_field_component_type_id = '$getTypeId' order by part_number";
					$typeName = "component";
			break;
			case "mat":
					$getTypeId = '0';
					 $type_name = $_type_name;
					 $checkType = DB::table('gpg_field_material_type')->where('name','LIKE','%'.$type_name.'%')->pluck('id');
					 if (empty($checkType)) {
						  $getTypeId = DB::table('gpg_field_material_type')->max('id')+1;
						  DB::table('gpg_field_material_type')->insert(array('id'=>$getTypeId,'name'=>$type_name,'status'=>'A','created_on'=>'A','modified_on'=>date('Y-m-d')));
						 $getTypeId = $checkType;
					 }
					 $queryPart = array();
					 while (list($ke,$vl)= each($_POST)){
						if (preg_match("/^_/i",$ke) && $ke!='type' && $ke!='_ittr' && !trim(empty($vl)) && $ke!='_type_name' && $ke!='_token' && $ke!='_gpg_field_component_type_id') 
							$queryPart[substr($ke,1,strlen($ke))] = trim($vl);
					 }
					 $checkPart = DB::table('gpg_field_material')->where('part_number','LIKE','%'.$part_name.'%')->where('gpg_field_material_type_id','=',$getTypeId)->pluck('id');
					 if (empty($checkPart) && !empty($part_name)){
						$getPartId = DB::table('gpg_field_material')->max('id')+1;
						DB::table('gpg_field_material')->insert(array('id'=>$getPartId,'gpg_field_material_type_id'=>$getTypeId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'))+$queryPart);
					 } else {
						$getPartId	=  $checkPart;
					 }
					 $tblName = "gpg_field_material";
					 $queryPart = " gpg_field_material_type_id = '$getTypeId' order by part_number";
					 $typeName = "material";
			break;
		}
		return Redirect::to('job/shop_work_quote_frm/'.$job_id.'/'.$job_num)->withInput(Request::except('_token'));
	}
	/*
	* getFieldCompnMat
	*/
	public function getFieldCompnMat(){
		$id = Input::get('id');
		$now = Input::get('now');
		$type = Input::get('type');
		$queryPart = '';
		switch ($type) {
			case "comp":
			 $tblName = "gpg_field_component";
			 $fldName = "part_number";
			 $queryPart = " gpg_field_component_type_id = '$id' AND is_active = 1 ORDER BY part_number";
			 $typeName = "component";
			 break;
			case "mat":
			 $tblName = "gpg_field_material";
			 $fldName = "part_number"; 
			 $queryPart = " gpg_field_material_type_id = '$id' AND is_active = 1 ORDER BY part_number";
			 $typeName = "material";
			 break;
		}
		$chkData = DB::select(DB::raw("SELECT $tblName.*,(SELECT name FROM gpg_vendor WHERE id = gpg_vendor_id) AS name FROM $tblName WHERE $queryPart"));
		$otVals = array();
		$str = '<option value="">-</option><option value="NEW'.strtoupper($typeName).'">ADD NEW</option>';
		foreach ($chkData as $key => $chkRow){
			$str .= '<option value="'.$chkRow->id.'">'.$chkRow->part_number.(isset($chkRow->description)?$chkRow->description:'').(isset($chkRow->note)?$chkRow->note:'').'</option>';	
			$otVals = (array)$chkRow;
		}
		if ($now == 'fillcomp')
			return Response::json($otVals);
		else
			return $str;	
	}
	/*
	* addNewPart
	*/
	public function addNewPart(){
		$allInputs = Input::except('_token');
		Input::flash();
		$job_id = Input::get('job_id');
		$job_num = Input::get('job_num');
		$type = Input::get('type'); 
		$_description = Input::get('_description'); 
		$_part_number = Input::get('_part_number'); 
		$_manufacturer = Input::get('_manufacturer'); 
		$_cost = Input::get('_cost'); 
		$_margin = Input::get('_margin'); 
		$_list = Input::get('_list'); 
		$_gpg_vendor_id = Input::get('_gpg_vendor_id'); 
		$new_vendor_name = Input::get('new_vendor_name'); 
		$_gpg_vendor_cost = Input::get('_gpg_vendor_cost'); 
		$_note = Input::get('_note'); 
		$_model_number = Input::get('_model_number'); 
		$_serial_number = Input::get('_serial_number'); 
		$_spec_number = Input::get('_spec_number');
		$type = Input::get('type');
		$new_vendor_name = Input::get('new_vendor_name');
		$part_name = Input::get('_part_number');

		if (!empty($new_vendor_name) && !empty($part_name)) {
			$chkVendor = DB::table('gpg_vendor')->where('name','=',$new_vendor_name)->pluck('id');
			if (!$chkVendor) { 									   
				$getVenderId = DB::table('gpg_vendor')->max('id')+1; 
				DB::table('gpg_vendor')->insert(array('id'=>$getVenderId,'name'=>$new_vendor_name,'status'=>'A','created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				$_gpg_vendor_id = $getVenderId;
			} else {
				$_gpg_vendor_id = $chkVendor;
			}
		}
		switch ($type) {
			case "comp":
		 	 $queryPart = array();
		 	 $_gpg_field_component_type_id = Input::get('_gpg_field_component_type_id'); 
		 	 while (list($ke,$vl)= each($_POST)) {
		    	if (preG_match("/^_/i",$ke) && $ke!='type' && $ke!='_type_name' && !trim(empty($vl)) && $ke!='_token') 
		    		$queryPart[substr($ke,1,strlen($ke))] =trim($vl);
		 	 }
			 $checkPart = DB::table('gpg_field_component')->where('part_number','LIKE','%'.$part_name.'%')->where('gpg_field_component_type_id','LIKE',$_gpg_field_component_type_id)->pluck('id');
			 if (empty($checkPart) && !empty($part_name)) {
				 $getMaxId = DB::table('gpg_field_component')->max('id')+1;
				 DB::table('gpg_field_component')->insert(array('id'=>$getMaxId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'))+$queryPart);
			 } else {
			    $getMaxId = $checkPart;
			 }
			 $tblName = "gpg_field_component";
			 $queryPart = " gpg_field_component_type_id = '$_gpg_field_component_type_id' order by part_number";
			 $typeName = "component";
			break;
			case "mat":
			 $queryPart = array();
			 $id = Input::get('_gpg_field_material_type_id');
			 while (list($ke,$vl)= each($_POST)) {
			 	if (preg_match("/^_/i",$ke) && $ke!='type' && $ke!='_token' && !trim(empty($vl)) && $ke!='_gpg_field_component_type_id') 
			 		$queryPart[substr($ke,1,strlen($ke))] = trim($vl);
		 	 }
			 $checkPart = DB::table('gpg_field_material')->where('part_number','LIKE','%'.$part_name.'%')->where('gpg_field_material_type_id','=',$id)->pluck('id');
		     if (empty($checkPart) && !empty($part_name)) { 	 
				 $getMaxId = DB::table('gpg_field_material')->max('id')+1;
				 DB::table('gpg_field_material')->insert(array('id'=>$getMaxId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'))+$queryPart);
			 } else {	 
			    $getMaxId = $checkPart;
			 }
			 
			 $tblName = "gpg_field_material";
			 $queryPart = " gpg_field_material_type_id = '$id' order by part_number";
			 $typeName = "material";
			break;
		}
		return Redirect::to('job/shop_work_quote_frm/'.$job_id.'/'.$job_num)->withInput(Request::except('_token'));
	}

	/*
	* searchParts
	*/
	public function searchParts(){
		$type = Input::get('type');
		$row = Input::get('row');
		$queryPart = '';
		$tblName = '';
		switch ($type) {
			case "comp":
			 $tblName = "gpg_field_component";
			 $fldName = "part_number";
			 $queryPart = " is_active = 1  order by part_number";
			 $typeName = "component";
			 $close_div = "search_equipment_numberDIV";
			 $clear_div = "equipment"; 	 
			 break;
			case "mat":
			 $tblName = "gpg_field_material";
			 $fldName = "part_number"; 
			 $queryPart = " is_active = 1  order by part_number ";
			 $typeName = "material";
			 $close_div = "search_part_numberDIV";
			 $clear_div = "part";
			 break;
		}
		$chkData = DB::select(DB::raw("select $tblName.*,(select name from gpg_vendor where id = gpg_vendor_id) as name from $tblName WHERE $queryPart"));
		$str='';
		$i=1;
		foreach ($chkData as $key => $chkRow) {
			if ($type == 'comp'){
				$str .='<tr><td>'.$i.'</td><td><button class="btn btn-link" type="comp" name="clickme" row="'.$row.'" id="'.$chkRow->gpg_field_component_type_id.'" >'.substr($chkRow->part_number, 0,50).'</button></td></tr>';
			}elseif($type == 'mat'){
				$str .='<tr><td>'.$i.'</td><td><button class="btn btn-link" type="comp" name="clickme" row="'.$row.'" id="'.$chkRow->gpg_field_material_type_id.'" >'.substr($chkRow->part_number, 0,50).'</button></td></tr>';
			}
			$i++;
		}
		return $str;
	}
	/*
	* updateShopWorkQuoteFrm
	*/
	public function updateShopWorkQuoteFrm(){
		/*echo "<pre>";
		print_r($_REQUEST);
		die();*/
		$shop_work_quote_id = Input::get("job_id");
		$jobNum = Input::get("job_num");
		$TotalLinesMaterial = Input::get("mat_count");
		$TotalLinesComponent = Input::get("equip_count");
		$TotalLinesLaborWorkHour = Input::get("labor_counter");
		$TotalLinesSubLaborWorkHour = Input::get("subl_counter");
		$TotalLinesOtherCharges = Input::get("totals_counter");
		$gpg_sales_tracking_id = DB::table('gpg_sales_tracking_shop_work_quote')->where('gpg_shop_work_quote_id','=',$shop_work_quote_id)->pluck('gpg_sales_tracking_id');
		$shopWorkFormFields = array("GPG_customer_id"=>"customerBillto","GPG_employee_id"=>"salePersonId","location"=>"location","sub_task"=>"jobDescription","main_contact_name"=>"contact","main_contact_phone"=>"phone","fax"=>"fax","task"=>"scopeOfWork","equipment_needed"=>"equipmentNeeded","schedule_date"=>"scheduleDate","tax_amount"=>"_tax","hazmat"=>"_hazmat","labor_shop_cost_rate"=>"_labor_shop_cost_rate","labor_shop_list_rate"=>"_labor_shop_rate","labor_shop_total_hours"=>"_labor_shop_hour","labor_labor_cost_rate"=>"_labor_labor_cost_rate","labor_labor_list_rate"=>"_labor_labor_rate","labor_labor_total_hours"=>"_labor_labor_hour","labor_lbt_cost_rate"=>"_labor_LBT_cost_rate","labor_lbt_list_rate"=>"_labor_LBT_rate","labor_lbt_total_hours"=>"_labor_LBT_hour","labor_ot_cost_rate"=>"_labor_OT_cost_rate","labor_ot_list_rate"=>"_labor_OT_rate","labor_ot_total_hours"=>"_labor_OT_hour","labor_sub_con_cost_rate"=>"_labor_sub_con_cost_rate","labor_sub_con_list_rate"=>"_labor_sub_con_rate","labor_sub_con_total_hours"=>"_labor_sub_con_hour","sub_cost_total"=>"_sub_cost_total","sub_list_total"=>"_sub_total","grand_cost_total"=>"_grand_cost_total","grand_list_total"=>"_grand_total","labor_cost_total"=>"_labor_sub_cost_total","labor_list_total"=>"_labor_sub_total","labor_hour_total"=>"_labor_sub_hour_total","mat_cost_total"=>"_mat_sub_cost_total","mat_list_total"=>"_mat_sub_total","comp_cost_total"=>"_comp_sub_cost_total","comp_list_total"=>"_comp_sub_total");
		$shopWorkQuoteQuery = array();
		while (list($key,$value)= each($shopWorkFormFields)){
		   if (preg_match("/date/i",$key)) 
		   	$shopWorkQuoteQuery[$key] = (Input::get($value)!=""?"'".date('Y-m-d',strtotime(Input::get($value)))."'":"NULL"); 
		   else 
		   	$shopWorkQuoteQuery[$key] = (Input::get($value)!=""?"'".(Input::get($value))."'":"NULL");
		}

		if(!empty($shop_work_quote_id))
		{
			$shop_work_quote_update = DB::table('gpg_shop_work_quote')->where('id','=',$shop_work_quote_id)->update($shopWorkQuoteQuery+array('schedule_time'=>Input::get('schedule_time'),'modified_on'=>date('Y-m-d')));		
			if(!empty($jobNum)) {
				$InvAmt0 = DB::select(DB::raw("SELECT SUM(gpg_job_invoice_info.invoice_amount) as invoice_amount FROM gpg_job_invoice_info,gpg_job WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job.link_job_num = '$jobNum'"));
				if(!empty($InvAmt0) && isset($InvAmt0[0]->invoice_amount))
					$InvAmt = $InvAmt0[0]->invoice_amount;
				else
					$InvAmt = 0;
				$shop_work_quote_contract_amount_update = DB::table('gpg_job')->where('job_num','=',$jobNum)->update(array('contract_amount'=>(Input::get('_grand_total')-$InvAmt)));
			}
		}
		if ($shop_work_quote_update==1) {
			for ($i=1; $i<=$TotalLinesComponent; $i++){
			  $comPartId = Input::get("_comp_part_".$i);
			  $comManufacturer = Input::get("comp_manufacturer_label_".$i);
			  $comVendor = Input::get("comp_vendor_label_".$i);
			  if (Input::get("_comp_component_".$i)!="") {
				  if($comPartId == ""){
					  $componentQuery = DB::table('gpg_shop_work_quote_component')->insert(array('gpg_shop_work_quote_id'=>$shop_work_quote_id, 'quantity'=> Input::get("_comp_quantity_".$i), 'component_id'=>Input::get("_comp_component_".$i), 'part_id'=>$comPartId, 'vendor'=>$comVendor, 'cost_price'=>Input::get("_comp_cost_".$i), 'list_price'=>Input::get("_comp_list_".$i), 'gpg_vendor_cost'=>Input::get("_comp_vendor_cost_".$i), 'gpg_comp_sell_price_cost'=>Input::get("_comp_sell_price_cost_".$i), 'margin'=>Input::get("_comp_margin_".$i), 'manufacturer'=>$comManufacturer , 'created_on'=>date('Y-m-d') , 'modified_on'=>date('Y-m-d')));
				  }
				  else{
					  $componentQuery = DB::table('gpg_shop_work_quote_component')->where('gpg_shop_work_quote_id','=',$shop_work_quote_id)->update(array('gpg_shop_work_quote_id'=>$shop_work_quote_id,'quantity'=>Input::get("_comp_quantity_".$i), 'component_id'=>Input::get("_comp_component_".$i), 'part_id'=>$comPartId, 'vendor'=>$comVendor, 'cost_price'=>Input::get("_comp_cost_".$i), 'list_price'=>Input::get("_comp_list_".$i), 'gpg_vendor_cost'=>Input::get("_comp_vendor_cost_".$i), 'gpg_comp_sell_price_cost'=>Input::get("_comp_sell_price_cost_".$i), 'margin'=>Input::get("_comp_margin_".$i), 'manufacturer'=>$comManufacturer, 'modified_on'=>date('Y-m-d')));
				  }
			  }
			} // end for 
 	    }
 	    if ($shop_work_quote_update==1) {
			for ($i=1; $i<=$TotalLinesMaterial; $i++) {
			  $matPartId = Input::get("_mat_part_".$i);
			  $matManufacturer = Input::get("mat_manufacturer_label_".$i);
			  $matVendor = Input::get("mat_vendor_label_".$i);
			  $matDescription = Input::get("mat_description_label_".$i);
			  if (Input::get("_mat_material_".$i)!="") {
				  if($matPartId == ""){
				     $materialQuery = DB::table('gpg_shop_work_quote_material')->insert(array('gpg_shop_work_quote_id'=>$shop_work_quote_id, 'quantity'=>Input::get("_mat_quantity_".$i), 'material_id'=>Input::get("_mat_material_".$i), 'part_id'=>$matPartId, 'vendor'=>$matVendor, 'cost_price'=>Input::get("_mat_cost_".$i), 'list_price'=>Input::get("_mat_list_".$i), 'gpg_vendor_cost'=>Input::get("_mat_vendor_cost_".$i), 'gpg_mat_sell_price_cost'=>Input::get("_mat_sell_price_".$i), 'margin'=>Input::get("_mat_margin_".$i), 'manufacturer'=>$matManufacturer, 'description'=>$matDescription, 'created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d')));
				  }
				  else{
				  	$materialQuery = DB::table('gpg_shop_work_quote_material')->where('id','=',$matPartId)->update(array('gpg_shop_work_quote_id'=>$shop_work_quote_id, 'quantity'=>Input::get("_mat_quantity_".$i), 'material_id'=>Input::get("_mat_material_".$i), 'part_id'=>$matPartId, 'vendor'=>$matVendor, 'cost_price'=>Input::get("_mat_cost_".$i), 'list_price'=>Input::get("_mat_list_".$i), 'gpg_vendor_cost'=>Input::get("_mat_vendor_cost_".$i), 'gpg_mat_sell_price_cost'=>Input::get("_mat_sell_price_".$i), 'margin'=>Input::get("_mat_margin_".$i), 'manufacturer'=>$matManufacturer, 'description'=>$matDescription, 'modified_on'=>date('Y-m-d')));
				  }
			  } 
			} // end for 
		} 
		if ($shop_work_quote_update==1) {
			for ($i=1; $i<=$TotalLinesLaborWorkHour; $i++) {
			  if (Input::get("_labor_scope_".$i)!="" or Input::get("_labor_shop_".$i)!="" or Input::get("_labor_labor_".$i)!="" or Input::get("_labor_LBT_".$i)!="" or Input::get("_labor_OT_".$i)!="" or Input::get("_labor_sub_con_".$i)!="") {
				  if(Input::get("laborWorkHourId_".$i)==""){
					  $laborWorkHour = DB::table('gpg_shop_work_quote_labor')->insert(array('gpg_shop_work_quote_id'=>$shop_work_quote_id, 'scope_work'=>Input::get("_labor_scope_".$i), 'shop'=>Input::get("_labor_shop_".$i), 'labor'=>Input::get("_labor_labor_".$i), 'lbt'=>Input::get("_labor_LBT_".$i), 'ot'=>Input::get("_labor_OT_".$i), 'sub_con'=>Input::get("_labor_sub_con_".$i), 'type'=>'A', 'created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d')));
				  }
				  else{
				  	$laborWorkHour = DB::table('gpg_shop_work_quote_labor')->where('id','=',Input::get("laborWorkHourId_".$i))->update(array('gpg_shop_work_quote_id'=>$shop_work_quote_id, 'scope_work'=>Input::get("_labor_scope_".$i), 'shop'=>Input::get("_labor_shop_".$i), 'labor'=>Input::get("_labor_labor_".$i), 'lbt'=>Input::get("_labor_LBT_".$i), 'ot'=>Input::get("_labor_OT_".$i), 'sub_con'=>Input::get("_labor_sub_con_".$i),'type'=>'A', 'modified_on'=>date('Y-m-d')));
				  }
			  } 
			} // end for 
		}
		if ($shop_work_quote_update==1) {
			for ($i=1; $i<=$TotalLinesSubLaborWorkHour; $i++) {
			    if (Input::get("_sub_labor_scope_".$i)!="" or Input::get("_sub_labor_shop_".$i)!="" or Input::get("_sub_labor_labor_".$i)!="" or Input::get("_sub_labor_LBT_".$i)!="" or Input::get("_sub_labor_OT_".$i)!="" or Input::get("_sub_labor_sub_con_".$i)!="") {
				  if(Input::get("subLaborWorkHourId_".$i)==""){
				  	$subLaborWorkHour = DB::table('gpg_shop_work_quote_labor')->insert(array('gpg_shop_work_quote_id'=>$shop_work_quote_id, 'scope_work'=>Input::get("_sub_labor_scope_".$i), 'shop'=>Input::get("_sub_labor_shop_".$i), 'labor'=>Input::get("_sub_labor_labor_".$i), 'lbt'=>Input::get("_sub_labor_LBT_".$i), 'ot'=>Input::get("_sub_labor_OT_".$i), 'sub_con'=>Input::get("_sub_labor_sub_con_".$i), 'type'=>'S', 'created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d')));
				  }
				  else{
				  	$subLaborWorkHour = DB::table('gpg_shop_work_quote_labor')->where('id','=',Input::get("subLaborWorkHourId_".$i))->update(array('gpg_shop_work_quote_id'=>$shop_work_quote_id, 'scope_work'=>Input::get("_sub_labor_scope_".$i), 'shop'=>Input::get("_sub_labor_shop_".$i), 'labor'=>Input::get("_sub_labor_labor_".$i), 'lbt'=>Input::get("_sub_labor_LBT_".$i), 'ot'=>Input::get("_sub_labor_OT_".$i), 'sub_con'=>Input::get("_sub_labor_sub_con_".$i), 'type'=>'S', 'modified_on'=>date('Y-m-d')));
				  }
				} 
			} // end for 
	 	}
	 	if ($shop_work_quote_update==1){
	 		for ($i=1; $i<=$TotalLinesOtherCharges; $i++){			 
			  if (Input::get("_other_charge_description_".$i)!="" ){
				  if(Input::get("otherId_".$i)==""){
				   $otherQuery = DB::table('gpg_shop_work_quote_other')->insert(array('gpg_shop_work_quote_id'=>$shop_work_quote_id, 'other_charge_qty'=>Input::get("_other_charge_qty_".$i), 'other_charge_description'=>Input::get("_other_charge_description_".$i), 'other_charge_cost_price'=>Input::get("_other_charge_cost_price_".$i), 'other_charge_price'=>Input::get("_other_charge_price_".$i), 'created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d')));
				  }
				  else{
				 	$otherQuery = DB::table('gpg_shop_work_quote_other')->where('id','=',Input::get("otherId_".$i))->update(array('gpg_shop_work_quote_id'=>$shop_work_quote_id, 'other_charge_qty'=>Input::get("_other_charge_qty_".$i), 'other_charge_description'=>Input::get("_other_charge_description_".$i), 'other_charge_cost_price'=>Input::get("_other_charge_cost_price_".$i), 'other_charge_price'=>Input::get("_other_charge_price_".$i), 'modified_on'=>date('Y-m-d')));
				  }
			  } 
			} // end for 
		}
		$typeofSale = DB::table('gpg_sales_tracking')->where('id','=',$gpg_sales_tracking_id)->pluck('type_of_sale');
		if($typeofSale=="Shop"){
			$salesTrackingQuery = DB::table('gpg_sales_tracking')->where('id','=',$gpg_sales_tracking_id)->update(array('projected_sale_price'=>Input::get('_grand_total'),'material_cost'=>Input::get('_mat_sub_cost_total'),'labor_cost'=>Input::get('_labor_sub_cost_total'),'modified_on'=>date('Y-m-d')));
		}
		return Redirect::to('job/shop_work_quote_frm/'.$shop_work_quote_id.'/'.$jobNum.'');
	}

	/*
	* delete from shop form
	*/
	public function deleteFromFrmShopWork(){
		$id = Input::get('id');
		$table = Input::get('table');
		if(!empty($id) && !empty($table) && $id!='0'){
			DB::table($table)->where('id','=',$id)->delete();
			return 1;
		}else
			return 0;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteQuotes(){
		$id = Input::get('id');
		$table = Input::get('table');
		$table_before = 'gpg_job_';
		if($table == 'Shop Work')
			$table_before = 'gpg_';
		DB::table($table_before.str_replace(' ', '_',strtolower($table)).'_quote')->where('id', '=',$id)->delete();
		return 1;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{	
		try{
			DB::table('gpg_job_type')->where('id', '=',$id)->delete();
	    	return Redirect::to('quote/')->withSuccess('Job Type Deleted Successfully!');     	
	    }catch(\Exception $e){
		    return Redirect::to('quote/')->withErrors(['Job Category related with multiple jobs could not be deleted!']);
		}
	    
	}


}
