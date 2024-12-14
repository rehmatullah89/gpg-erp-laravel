<?php

class JobController extends \BaseController {


	/*
	* Static Arrays
	*/
	protected $FSWStatusArray = array( '1' => 'Waiting for parts', '2' => 'Ready for schedule', '3' => 'Scheduled', '4' => 'Next visit', '5' => 'Customer issues' );
	protected $elecJobTypeArray = array(''=>'Select Job Type' ,"GeneratorOnly" => "Generator Only", "GeneratorAndPermit" => "Generator & Permit", "Permit" => "Permit", "MonthlyMaintenance" => "Monthly Maintenance", "Retrofit" => "Retrofit", "Standard" => "Standard", "EmergencyCall" => "Emergency Call", "TroubleShoot" => "Trouble Shoot", "InfraredScan" => "Infrared Scan", "ContractJob" => "Contract Job", "ATS" => "ATS", "ArcFlashStudy" => "Arc Flash Study", "ChartRecording" => "Chart Recording", "CircuitTracing" => "Circuit Tracing", "WarrantyNonBillable" => "Warranty (Non Billable)", "GenTracker" => "Gen Tracker" );
	protected $jobProjectType = array( "smalBussinesSuperSaver" => "Small Bussines Super Saver", "expressEfficiency" => "Express Efficiency", "standardPerformance" => "Standard Performance", "onBillFinancing" => "On Bill Financing" );
	protected $empTypeArr =  array('all' =>'ALL' ,'Electrician' =>'Electrician' ,'Apprentice' =>'Apprentice' ,'Technician' =>'Technician' ,'Salesperson-Field' =>'Salesperson - Field' ,'Admin' =>'Admin' ,'Salesperson-Electrical' =>'Salesperson - Electrical' ,'Shop' =>'Shop' ,'Laborer' =>'Labourer' );
	protected $payTypeArray = array( "OnAccount" => "On Account", "Cash" => "Cash", "Check" => "Check", "OwnCC" => "Credit Card/Own", "CCCo" => "Credit Card/Co", "CCAmex" => "Credit Card:Amex", "CCBofA" => "Credit Card:BofA" );
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}
	/*
	*jobProjectManagement
	*/
	public function jobProjectManagement(){
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getProjectsByPage($page, 5);
  		$query_data = Paginator::make($data->items, $data->totalItems, 5);
  		$sales_emps = DB::select( DB::raw("select id,name from gpg_employee where status ='A' order by name"));
		$sal_emps_arr = array(''=>'Select Option');
		foreach ($sales_emps as $key => $value)
				$sal_emps_arr[$value->id] = $value->name;
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'empTypeArr'=>$this->empTypeArr,'sal_emps_arr'=>$sal_emps_arr);
		return View::make('job.job_project', $params);
	}
	public function getProjectsByPage($page = 1, $limit = null)
	{
		$results = new \StdClass;
		$results->page = $page;
		# set offset (for excel export offset will not apply)
		$limitOffset = '';
		if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}
		$results->totalItems = 0;
		$results->items = array();
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$ProStatus = Input::get("ProStatus");
		$Filter = stripslashes(Input::get("Filter"));
		$FVal = Input::get("FVal");
		$queryPart='';
		$jobTblRow=array();
		if (empty ($SDate) && empty($EDate) && empty($ProStatus) && empty($Filter) && empty($FVal)) {
			$queryPart=" AND a.completed=0";	
		}else{
			if ($SDate!="" and $EDate!="") $queryPart .= " AND a.start_date >= '".date('Y-m-d',strtotime($SDate))."' AND a.start_date <= '".date('Y-m-d',strtotime($EDate))."' ";
			elseif ($SDate!="") $queryPart .= " AND a.start_date = '".date('Y-m-d',strtotime($SDate))."'";
			if ($Filter!="")  $queryPart.= " AND a.".$Filter."='".$FVal."'"; 
			if($ProStatus!="" && $ProStatus!="all") $queryPart.= " AND a.completed='".$ProStatus."'"; 
		}
		$count = DB::select(DB::raw("SELECT count(*) as t_count FROM gpg_job WHERE (SELECT COUNT(*) FROM gpg_job_project a WHERE a.GPG_job_id = gpg_job.id $queryPart) > 0"));
		if (!empty($count) && isset($count[0]->t_count)){
			$results->totalItems = 	$count[0]->t_count;
		}
		$jobs = DB::select(DB::raw("SELECT id FROM gpg_job WHERE (SELECT COUNT(*) FROM gpg_job_project a WHERE a.GPG_job_id = gpg_job.id $queryPart) > 0 ORDER BY created_on desc $limitOffset"));
		$job_ids_str = " AND b.id IN (";
		foreach ($jobs as $key => $value) {
			$job_ids_str .= "'".$value->id."',";		
		}	
		$job_ids_str = substr($job_ids_str,0,strlen($job_ids_str)-1);
		$job_ids_str = $job_ids_str.")";
		$project = DB::select(DB::raw("select a.*,b.project_title,b.job_num,(select title from gpg_job_project where id = a.parent_task) as parentTask, (select sum(time_diff_dec) from gpg_timesheet t1, gpg_timesheet_detail t2 where t1.id = t2.GPG_timesheet_id and t2.job_num = a.GPG_job_num and t1.date>=a.start_date and t1.date<=a.end_date) as timesheet from gpg_job_project a, gpg_job b where a.GPG_job_num=b.job_num".$queryPart.' '.$job_ids_str.' order by b.job_num'));
		$currentDate = date('Y-m-d');
		$compDiff = "";	
		$dateDiff = "";
		$data_arr = array();
		foreach ($project as $key => $value) {
			$data_arr[] = (array)$value;
		}
		/*echo "<pre>";
		print_r($data_arr);
		die();*/
		$results->items = $data_arr;
		return $results;
	}
	/*
	* Local Method
	*/
	public function clear_num($num)
	{
	   return doubleval(str_replace('$','',str_replace(',','',$num)));
	}
	/*
	* Get Invoice Info
	*/
	public function getInvoiceInfo(){
		$job_id = Input::get('job_id');
		$inv_info =  DB::table('gpg_job_invoice_info')
            ->select('*')
            ->where('gpg_job_id','=',$job_id)
            ->get();
        $grandTotal= 0;    
   		$str = "";
		$str .="<table class='table table-bordered table-striped table-condensed cf' align='center'>";
		$str .="<thead><tr><th>Invoice#</th><th>Invoice Date</th><th>Invoice Amount</th><th>Sales Tax Amount</th><th>Net Invoice Amount</th></tr></thead><tbody>";    
        if (!empty($inv_info)) {
        	foreach ($inv_info as $key => $value) {
        		$invAmt = ($value->invoice_amount!=0?$value->invoice_amount - $value->tax_amount:0);
	        	$str .= "<tr><td>".$value->invoice_number."</td><td>".date('m/d/Y',strtotime($value->invoice_date))."</td><td>".'$'.number_format($value->invoice_amount,2)."</td><td>".'$'.number_format($value->tax_amount,2)."</td><td>".'$'.number_format($invAmt,2)."</td></tr>";        		
        		$grandTotal += $invAmt;
        	}
        }    
        $str .="<tr><td colspan='4'>Total</td><td>".'$'.number_format($grandTotal,2)."</td></tr></tbody></table>";                            
		
		return $str;
	}
	/*
	* Local method
	*/
	public function sqlstr_clear($str)
	{
    	return "ROUND(REPLACE(REPLACE(".$str.",',',''),'".'$'."',''),2)";
	}
	/*
	* Electrical Job List
	*/
	public function electricalJobList(){
		$modules = Generic::modules();

		# flash input fields to re-populate (state maintain) search form		
		Input::flash();		
		
		$page = Input::get('page', 1);
   		$data = $this->getByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
 		
 		# set page headings according to requested listing (electrical, grassivy, special)
      	$uriSegment = Request::segment(2);
		$reqJobListing = (isset($uriSegment) && $uriSegment != '') ? $uriSegment : 'elec_job_list';
		switch($reqJobListing) {
			case 'grassivyJobList':
				$pageHeading['main_heading'] = 'GRASSIVY JOBS LISTING';
				$pageHeading['sub_heading'] = 'GRASSIVY JOBS LISTING AND MANAGEMENT';
			break;
			case 'specialProjectJobList':
				$pageHeading['main_heading'] = 'SPECIAL PROJECT JOBS LISTING';
				$pageHeading['sub_heading'] = 'SPECIAL PROJECT JOBS LISTING AND MANAGEMENT';
			break;
			case 'shopWorkJobList':
				$pageHeading['main_heading'] = 'SHOP WORK JOBS LISTING';
				$pageHeading['sub_heading'] = 'SHOP WORK JOBS LISTING AND MANAGEMENT';
			break;
			default:
				$pageHeading['main_heading'] = 'ELECTRICAL JOBS LISTING';
				$pageHeading['sub_heading'] = 'ELECTRICAL JOBS LISTING AND MANAGEMENT';
			break;
		}


 		$customers = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$cust_arr = array(''=>'ALL');
		foreach ($customers as $key => $value)
				$cust_arr[$value->id] = $value->name;

 		$files = DB::table('gpg_sales_tracking_attachment')->select('*')->get();
		$files_arr = array();
		foreach ($files as $key => $value)
				$files_arr[$value->gpg_sales_tracking_id] = wordwrap($value->displayname,1000, "\n",1);	
		
		$technecians = DB::table('gpg_employee')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$tech_arr = array();
		foreach ($technecians as $key3 => $value3)
				$tech_arr[$value3->id] = $value3->name;	

 		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		
		$jobTypes = DB::table('gpg_job')->select('elec_job_type')->where('elec_job_type','!=','')->distinct()->orderBy('elec_job_type')->get();
		$jobtype_arr = array(''=>'Select Job Type');
		foreach ($jobTypes as $key => $value)
				$jobtype_arr[$value->elec_job_type] = preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", " ", $value->elec_job_type);			

		$params = array('left_menu' => $modules, 'query_data'=>$query_data,'totals_qry'=>$data->totals,'cust_arr'=>$cust_arr,'salesp_arr'=>$salesp_arr,'jobtype_arr'=>$jobtype_arr,'tech_arr'=>$tech_arr,'files_arr'=>$files_arr);
		$params['page_heading'] = $pageHeading;		

 		return View::make('job.elec_job_list', $params);
	}
	/*
	* paginator for index holiday management	
	*/
	public function getByPage($page = 1, $limit = null)
	{
	  $results = new \StdClass;
	  $results->page = $page;

	  # set offset (for excel export offset will not apply)
	  $limitOffset = '';
	  if($limit != null) {
	  	$results->limit = $limit;
	  	$start = $limit * ($page - 1);

	  	$limitOffset = 'limit ' . $start . ', ' . $limit;
	  }
	  
	  $results->totalItems = 0;
	  $results->items = array();
	  $results->totals = array();
	  $temp_arr = array();
	  $items_arr = array();
      
      $queryPartInvoice ="";
	  $queryPartLaborCost ="";
	  $queryPartMaterialCost ="";
	  $queryPartInvoice ="";
	  $queryPartLaborCost ="";
	  $queryPartMaterialCost ="";
      $queryPart = "";
 	  		
		# check request for job listing (electrical, grassivy, speical)
		# for electrical job listing gpg_job_type_id=5 and gpg_job_num='%GPG'
		# for grassivy job listing gpg_job_type_id=12 and gpg_job_num='%IG'
      	# for special project job listing gpg_job_type_id=13 and gpg_job_num='%LK'
		# by default electrical job listing selected
      	$uriSegment = Request::segment(2);
      	if(isset($_REQUEST['table']) && !empty($_REQUEST['table']))
      		$uriSegment = $_REQUEST['table'];
		$reqJobListing = (isset($uriSegment) && $uriSegment != '') ? $uriSegment : 'elec_job_list';
		$appendSubQry = '';
		if ($reqJobListing == 'shopWorkJobList') {
			$appendSubQry = 'and SUBSTRING(job_num,3)*1 >= 100000';
		}
		switch($reqJobListing) {
			case 'grassivyJobList':
				$gpg_job_type_id = 12;
				$gpg_job_num  = 'IG%';
			break;
			case 'specialProjectJobList':
				$gpg_job_type_id = 13;
				$gpg_job_num  = 'LK%';
			break;
			case 'shopWorkJobList':
				$gpg_job_type_id = 4;
				$gpg_job_num  = 'SH%';
			break;
			default:
				$gpg_job_type_id = 5;
				$gpg_job_num  = 'GPG%';
			break;
		}

 	  $ignoreCostDate = "";
 	  if (isset($_REQUEST['ignoreCostDate']))
	        $ignoreCostDate = $_REQUEST['ignoreCostDate'];
	   $ignoreInvoiceDate = "";
	  if (isset($_REQUEST['ignoreInvoiceDate']))
	        $ignoreInvoiceDate = $_REQUEST['ignoreInvoiceDate'];  
	   $InvoiceSDate = "";
	  if (isset($_REQUEST['InvoiceSDate']))
	        $InvoiceSDate = $_REQUEST['InvoiceSDate'];  
 	   $InvoiceEDate = "";
 	  if (isset($_REQUEST['InvoiceEDate']))
	        $InvoiceEDate = $_REQUEST['InvoiceEDate'];    
	   $JobWonSDate = "";
	  if (isset($_REQUEST['JobWonSDate']))
	        $JobWonSDate = $_REQUEST['JobWonSDate'];    
	   $JobWonEDate = "";
	  if (isset($_REQUEST['JobWonEDate']))
	        $JobWonEDate = $_REQUEST['JobWonEDate'];    
	   $EqpOrderedSDate = "";
	  if (isset($_REQUEST['EqpOrderedSDate']))
	        $EqpOrderedSDate = $_REQUEST['EqpOrderedSDate'];    
	   $EqpOrderedEDate = "";
	  if (isset($_REQUEST['EqpOrderedEDate']))
	        $EqpOrderedEDate = $_REQUEST['EqpOrderedEDate'];    
	   $EqpEngagedSDate = "";
	  if (isset($_REQUEST['EqpEngagedSDate']))
	        $EqpEngagedSDate = $_REQUEST['EqpEngagedSDate'];    
	   $EqpEngagedEDate = "";
	  if (isset($_REQUEST['EqpEngagedEDate']))
	        $EqpEngagedEDate = $_REQUEST['EqpEngagedEDate'];    
	   $PermitOrderedSDate = "";
	  if (isset($_REQUEST['PermitOrderedSDate']))
	        $PermitOrderedSDate = $_REQUEST['PermitOrderedSDate'];    
	   $PermitOrderedEDate = "";
	  if (isset($_REQUEST['PermitOrderedEDate']))
	        $PermitOrderedEDate = $_REQUEST['PermitOrderedEDate'];    
	   $PermitExpectedSDate = "";
	  if (isset($_REQUEST['PermitExpectedSDate']))
	        $PermitExpectedSDate = $_REQUEST['PermitExpectedSDate'];    
	   $PermitExpectedEDate = "";
	  if (isset($_REQUEST['PermitExpectedEDate']))
	        $PermitExpectedEDate = $_REQUEST['PermitExpectedEDate'];    
	   $CompletedSDate = "";
	  if (isset($_REQUEST['CompletedSDate']))
	        $CompletedSDate = $_REQUEST['CompletedSDate'];    
	   $CompletedEDate = "";
	  if (isset($_REQUEST['CompletedEDate']))
	        $CompletedEDate = $_REQUEST['CompletedEDate'];    
	   $CompletedEDate = "";
	  if (isset($_REQUEST['CompletedEDate']))
	        $CompletedEDate = $_REQUEST['CompletedEDate'];    
	   $CreatedSDate = "";
	  if (isset($_REQUEST['CreatedSDate']))
	        $CreatedSDate = $_REQUEST['CreatedSDate'];    
	   $CreatedEDate = "";
	  if (isset($_REQUEST['CreatedEDate']))
	        $CreatedEDate = $_REQUEST['CreatedEDate'];    
	   $SJobNumber = "";
	  if (isset($_REQUEST['SJobNumber']))
	        // $SJobNumber = $_REQUEST['SJobNumber'];    
	  	$SJobNumber = Input::get('SJobNumber');
	  Debugbar::info($SJobNumber);
	   $EJobNumber = "";
	  if (isset($_REQUEST['EJobNumber']))
	        $EJobNumber = $_REQUEST['EJobNumber'];    
	   $InvNumber = "";
	  if (isset($_REQUEST['InvNumber']))
	        $InvNumber = $_REQUEST['InvNumber'];    
	   $optEmployee = "";
	  if (isset($_REQUEST['optEmployee']))
	        $optEmployee = $_REQUEST['optEmployee'];    
	   $optEstimator = "";
	  if (isset($_REQUEST['optEstimator']))
	        $optEstimator = $_REQUEST['optEstimator'];    
	   $optCustomer = "";
	  if (isset($_REQUEST['optCustomer']))
	        $optCustomer = $_REQUEST['optCustomer'];    
	   $optJobStatus = "";
	  if (isset($_REQUEST['optJobStatus']))
	        $optJobStatus = $_REQUEST['optJobStatus'];    
	   $optJobAccount = "";
	  if (isset($_REQUEST['optJobAccount']))
	        $optJobAccount = $_REQUEST['optJobAccount'];    
	   $optTechAtt = "";
	  if (isset($_REQUEST['optTechAtt']))
	        $optTechAtt = $_REQUEST['optTechAtt'];    
	   $optJobCostStatus = "";
	  if (isset($_REQUEST['optJobCostStatus']))
	        $optJobCostStatus = $_REQUEST['optJobCostStatus'];    
	   $optJobHaving = "";
	  if (isset($_REQUEST['optJobHaving']))
	        $optJobHaving = $_REQUEST['optJobHaving'];    
	   $elecJobType = "";
	  if (isset($_REQUEST['elecJobType']))
	        $elecJobType = $_REQUEST['elecJobType'];    

		/* ********************************************************************* Search variables **************************************************************************************************************************************************************************************************************************************************************************************** */
		if ($InvoiceSDate!="" and $InvoiceEDate!="")
		{
		    $queryPart .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND  gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."'  order by gpg_job_invoice_info.invoice_date desc limit 0,1)";
		    if($ignoreCostDate=='' and $CreatedSDate == '')
		    {
		        $queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
		        $queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
		    }
		    if($ignoreInvoiceDate=='')
		    {
		        $queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
		    }
		}
		elseif ($InvoiceSDate!="")
		{
		    $queryPart .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND  gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' order by gpg_job_invoice_info.invoice_date desc limit 0,1)";
		    if($ignoreCostDate=='' and $CreatedSDate == '')
		    {
		        $queryPartMaterialCost=" AND gpg_job_cost.date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
		        $queryPartLaborCost=" AND gpg_timesheet.date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
		    }
		    if($ignoreInvoiceDate=='')
		    {
		        $queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
		    }
		}

		if ($JobWonSDate!="" and $JobWonEDate!="") $queryPart .= " AND date_job_won >= '".date('Y-m-d',strtotime($JobWonSDate))."' AND date_job_won <= '".date('Y-m-d',strtotime($JobWonEDate))."' ";
		elseif ($JobWonSDate!="") $queryPart .= " AND date_job_won = '".date('Y-m-d',strtotime($JobWonSDate))."'";

		if ($EqpOrderedSDate!="" and $EqpOrderedEDate!="") $queryPart .= " AND date_eqp_ordered >= '".date('Y-m-d',strtotime($EqpOrderedSDate))."' AND date_eqp_ordered <= '".date('Y-m-d',strtotime($EqpOrderedEDate))."' ";
		elseif ($EqpOrderedSDate!="") $queryPart .= " AND date_eqp_ordered = '".date('Y-m-d',strtotime($EqpOrderedSDate))."'";

		if ($EqpEngagedSDate!="" and $EqpEngagedEDate!="") $queryPart .= " AND date_eqp_engaged >= '".date('Y-m-d',strtotime($EqpEngagedSDate))."' AND date_eqp_engaged <= '".date('Y-m-d',strtotime($EqpEngagedEDate))."' ";
		elseif ($EqpEngagedSDate!="") $queryPart .= " AND date_eqp_engaged = '".date('Y-m-d',strtotime($EqpEngagedSDate))."'";

		if ($PermitOrderedSDate!="" and $PermitOrderedEDate!="") $queryPart .= " AND date_permit_ordered >= '".date('Y-m-d',strtotime($PermitOrderedSDate))."' AND date_permit_ordered <= '".date('Y-m-d',strtotime($PermitOrderedEDate))."' ";
		elseif ($PermitOrderedSDate!="") $queryPart .= " AND date_permit_ordered = '".date('Y-m-d',strtotime($PermitOrderedSDate))."'";

		if ($PermitExpectedSDate!="" and $PermitExpectedEDate!="") $queryPart .= " AND date_permit_expected >= '".date('Y-m-d',strtotime($PermitExpectedSDate))."' AND date_permit_expected <= '".date('Y-m-d',strtotime($PermitExpectedEDate))."' ";
		elseif ($PermitExpectedSDate!="") $queryPart .= " AND date_permit_expected = '".date('Y-m-d',strtotime($PermitExpectedSDate))."'";

		if ($CompletedSDate!="" and $CompletedEDate!="") $queryPart .= " AND date_completion >= '".date('Y-m-d',strtotime($CompletedSDate))."' AND date_completion <= '".date('Y-m-d',strtotime($CompletedEDate))."' ";
		elseif ($CompletedSDate!="") $queryPart .= " AND date_completion = '".date('Y-m-d',strtotime($CompletedSDate))."'";

		if ($CreatedSDate!="" and $CreatedEDate!="")
		{
		    $queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($CreatedSDate))." 00:00:00' AND created_on <= '".date('Y-m-d',strtotime($CreatedEDate))." 23:59:59' ";
		    if($ignoreCostDate=='')
		    {
		        $queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($CreatedSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($CreatedEDate))."' ";
		        $queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($CreatedSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($CreatedEDate))."' ";
		    }
		    if($ignoreInvoiceDate=='' and $InvoiceSDate=='')
		    {
		        $queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($CreatedSDate))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($CreatedEDate))."' ";
		    }
		}
		elseif ($CreatedSDate!="")
		{
		    $queryPart .= " AND date_format(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($CreatedSDate))."'";
		    if($ignoreCostDate=='')
		    {
		        $queryPartMaterialCost=" AND gpg_job_cost.date = '".date('Y-m-d',strtotime($CreatedSDate))."' ";
		        $queryPartLaborCost=" AND gpg_timesheet.date = '".date('Y-m-d',strtotime($CreatedSDate))."' ";
		    }
		    if($ignoreInvoiceDate=='' and $InvoiceSDate=='')
		    {
		        $queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($CreatedSDate))."' ";
		    }
		}
		if ($SJobNumber!="" and $EJobNumber!="") $queryPart .= " AND job_num >= '".$SJobNumber."' AND job_num <= '".$EJobNumber."' ";
		elseif ($SJobNumber!="") $queryPart .= " AND job_num = '".$SJobNumber."'";

		if ($InvNumber!="") $queryPart .= " AND invoice_number = '$InvNumber' ";

		if ($optEmployee!="" and $optEmployee!="notSeleted") $queryPart .= " AND gpg_employee_id = '$optEmployee' ";
		if ($optEmployee!="" and $optEmployee=="notSeleted") $queryPart .= " AND ifnull(gpg_employee_id,'') = '' ";
		if ($optEstimator!="") $queryPart .= " AND estimator = '$optEstimator' ";
		if ($optCustomer!="") $queryPart .= " AND gpg_customer_id = '$optCustomer' ";
		if ($elecJobType!="") $queryPart .= " AND elec_job_type = '$elecJobType' ";

		if ($optJobAccount=="national_account") $queryPart .= " AND ifnull(national_account,'') <> '' ";
		if ($optJobAccount=="sub_contractor") $queryPart .= " AND ifnull(sub_contractor,'') <> '' ";

		if ($optTechAtt=="single") $queryPart .= ' AND technicians <> "" AND  technicians NOT LIKE "%,%" ';
		if ($optTechAtt=="multiple") $queryPart .= ' AND technicians <> "" AND  technicians  LIKE "%,%" ';
		if ($optTechAtt=="both") $queryPart .= ' AND technicians <> ""';

		if ($optJobStatus=="completed") $queryPart .= " AND complete = '1' ";
		if ($optJobStatus=="notcompleted") $queryPart .= " AND complete = '0'"; //
		if ($optJobStatus=="invoiced") $queryPart .= " AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1) ";
		if ($optJobStatus=="not_invoiced") $queryPart .= "AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)>0,0,1) ";
		if ($optJobStatus=="comp_inv") $queryPart .= " AND (complete = '1' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)) ";
		if ($optJobStatus=="not_comp_inv") $queryPart .= " AND (complete = '0' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)) "; //
		if ($optJobStatus=="completed_not_invoiced") $queryPart .= " AND (complete = '1' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)>0,0,1)) ";
		if ($optJobStatus=="completed_not_closed") $queryPart .= " AND complete = '1' AND (closed = '0' OR closed IS NULL)";
		if ($optJobStatus=="closed_not_completed") $queryPart .= " AND complete = '0' AND closed = '1' ";

		if ($optJobCostStatus=="no_labor") $queryPart .= " AND (select if(sum(total_wage)>0,0,1) from gpg_timesheet_detail where GPG_job_id = gpg_job.id) ";
		if ($optJobCostStatus=="no_mat") $queryPart .= " AND (select if(sum(amount)>0,0,1) from gpg_job_cost where job_num = gpg_job.job_num) ";
		if ($optJobCostStatus=="no_both") $queryPart .= " AND (select if(sum(amount)>0,0,1) from gpg_job_cost where job_num = gpg_job.job_num) AND (select if(sum(total_wage)>0,0,1) from gpg_timesheet_detail where GPG_job_id = gpg_job.id) ";
		if ($optJobHaving=="cost") $queryPart .= " AND (select if(count(amount)>0,1,0) from gpg_job_cost where job_num = gpg_job.job_num)  ";
		if ($optJobHaving=="po") $queryPart .= " AND (select GPG_job_id from gpg_purchase_order where GPG_job_id = gpg_job.id AND ifnull(soft_delete,0)<>1 limit 0,1)";
		if ($optJobHaving=="timesheet") $queryPart .= " AND (select if(count(total_wage)>0,1,0) from gpg_timesheet_detail where GPG_job_id = gpg_job.id)";
			$queryPart .= " order by job_num desc";

	/* -------------------------------------------------------------------------------- End Search Variables -------------------------------------------------------------------------------------------------------------------------------*/		
	  $query_count = DB::select( DB::raw("select count(id) as count from gpg_job where job_num  like '".$gpg_job_num."' $appendSubQry and GPG_job_type_id='".$gpg_job_type_id."' $queryPart"));
	  $query = DB::select( DB::raw("select *,(select concat(invoice_number,'#~#',sum(invoice_amount),'#~#',invoice_date,'#~#',sum(tax_amount),'#~#',count(id)) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_data,(select name from gpg_customer where id = gpg_customer_id) as customer_name, (select name from gpg_employee where id = gpg_employee_id) as sales_person_name, (select name from gpg_employee where id = estimator) as estimator_name , (select sum(total_wage) from gpg_timesheet_detail , gpg_timesheet where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num $queryPartLaborCost) as labor_cost , (select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost) as material_cost, (select gpg_sales_tracking_id from gpg_sales_tracking_job where   gpg_sales_tracking_job.gpg_job_id = gpg_job.id limit 1) as tracking_id from gpg_job where job_num like '".$gpg_job_num."' $appendSubQry and GPG_job_type_id='".$gpg_job_type_id."' $queryPart " . $limitOffset));	
	 /* ```````````` Totals Query ``````````````*/
	  $totals_query = DB::select( DB::raw("select sum((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id)) as invoice_amount_net, sum(if(".$this->sqlstr_clear('fixed_price').">0,".$this->sqlstr_clear('fixed_price').",if(".$this->sqlstr_clear('nte').">0,".$this->sqlstr_clear('nte').",if(".$this->sqlstr_clear('sub_nte').">0,".$this->sqlstr_clear('sub_nte').",(if(".$this->sqlstr_clear('contract_amount').">0,".$this->sqlstr_clear('contract_amount').",0)))))) as contract_amount, sum((select sum(invoice_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice)) as inv_amount, sum((select sum(tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice)) as tax_amount ,sum((select sum(total_wage) from gpg_timesheet_detail , gpg_timesheet where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and job_num = gpg_job.job_num $queryPartLaborCost)) as lab_cost,sum((select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost)) as mat_cost, sum(cost_to_dat) as cost_to_date from gpg_job where job_num like '".$gpg_job_num."' $appendSubQry and GPG_job_type_id=".$gpg_job_type_id." $queryPart"));
	 if (!empty($totals_query)) 
		$results->totals = $totals_query;	
	/* ````````````````````````````````````````` */
	
	  foreach ($query as $key1 => $v) {
	  	foreach ($v as $key => $value) {
	  		if ($key == 'technicians') {
	  			if (!empty($value)){
	  				$query2 = DB::select( DB::raw("select GROUP_CONCAT(name) as name from gpg_employee where id IN (".$value.")"));
	  				$temp_arr['technicians'] = $query2[0]->name;	
	  			}
	  			else
	  				$temp_arr['technicians'] = '-';
	  		}
	  		else if($key == 'contract_amount'){
	  			$temp_arr['contract_amount'] = number_format((float)number_format((float)$this->clear_num($query[$key1]->fixed_price),2) != "0" ? $this->clear_num($query[$key1]->fixed_price) : ($query[$key1]->nte != "" ? $this->clear_num($query[$key1]->nte) : ($query[$key1]->sub_nte != "" ? $this->clear_num($query[$key1]->sub_nte) : ($query[$key1]->contract_amount != "" ? $this->clear_num($query[$key1]->contract_amount) : 0))),2);
	  		}
		  	else if (empty($value) && $key != 'technicians' && $key != 'contract_amount') {
	  			$temp_arr[$key] = '-';
	  		}
	  		else
		  		$temp_arr[$key] = $value; 
	  	}
	  	$items_arr[] = $temp_arr;
	  }

	  if (isset($query_count[0]->count)){
		  $results->totalItems = $query_count[0]->count;
		  $results->items = $items_arr;
	  }

	  return $results;
	}

	/*
	* updateJobs
	*/
	public function updateJobs(){
		
		$updateArr = array();
		$job_num = Input::get('job_num'); 
		$tech = Input::get('technecian');
		if (!empty($tech)){
			$technecian = rtrim($tech,','); 
			$updateTech = array('technicians' => $technecian);
			$updateArr = array_merge($updateArr, $updateTech);
		}
		$date_schd = Input::get('date_schd');
		if ($date_schd != 0){
			$updateSch = array('schedule_date' => $date_schd);
			$updateArr = array_merge($updateArr, $updateSch);
		}

		$date_comp = "";
		$status = Input::get('status'); 
		if($status == '1'){
			if (isset($_GET['date_comp']) && $_GET['date_comp'] != 0){
				$date_comp = Input::get('date_comp');
				$updateStatus = array('date_completion' => $date_comp, 'complete'=> 1);
				$updateArr = array_merge($updateArr, $updateStatus);
			}
		}
		else if($status == '0'){
			$updateStatus = array('complete' => 0);
			$updateStatus = array('date_completion' => '-', 'complete'=> 0);
			$updateArr = array_merge($updateArr, $updateStatus);
		}
		if (!empty($updateArr)){
			DB::table('gpg_job')->where('job_num','=', $job_num)->update($updateArr);
			if (!empty($technecian)){
				$str = explode(",",$technecian);
				foreach ($str as $key => $value) {
					DB::table('gpg_job_project')->where('GPG_job_num','=', $job_num)->update(array('GPG_employee_id'=>$value));					
				}
			}
		}
		return 1;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
		$job_type_arr = DB::table('gpg_job_type')->select('id','name')->lists('name','id');
		$job_type_arr = array(''=>'Select Job Category')+$job_type_arr;

		$customer_arr = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->lists('name','id');
		$customer_arr = array(''=>'Select Customer')+$customer_arr;

		$employee_arr = DB::table('gpg_employee')->select('id','name')->where('status','=','A')->orderBy('name')->lists('name','id');
		$employee_arr = array(''=>'Select Employee')+$employee_arr;

		$jobObj = Gpg_job::find($id);
		$params = array('jobObj'=>$jobObj,'left_menu' => $modules, 'job_type_arr'=>$job_type_arr,'customer_arr'=>$customer_arr,'employee_arr'=>$employee_arr);
		return View::make('job.editJob', $params);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id){
		//
	}

	public function updateThisJob()
	{
		$id = Input::get('id');
		$jobNum = Input::get('job_num');
		$old_job_num = Input::get('old_job_num');
		$modules = Generic::modules();
		if ($jobNum == $old_job_num)
		$rules = array(
	        'jobCat'             => 'required',                        // just a normal required validation
	        'jobPlan'            => 'required',     // required and must be unique in the ducks table
	        'customer'         => 'required',
	        'job_num' => 'required|max:20',           // required and has to match the password field
	        'task' => 'max:250',           // required and has to match the password field
	        'taskSub' => 'max:250'           // required and has to match the password field
    	);	
		else
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
	        return Redirect::to('job/'.$id.'/edit')->withErrors($validator);
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
		 	DB::table('gpg_job')->where('id','=',$id)->update(array('GPG_job_type_id'=>$jobCat,'GPG_wage_plan_id'=>$jobPlan,'GPG_customer_id'=>$customer,'GPG_employee_id'=>$assignTo,'job_num'=>$jobNum,'location'=>$location,'generator_size'=>$genSize,'task'=>$task,'sub_task'=>$taskSub,'status'=>($assignTo!=""?"A":"N"),'priority'=>$priority,'modified_on'=>date('Y-m-d'),'created_on'=>date('Y-m-d')));
	  		return Redirect::to('job/job_management')->withSuccess('Job has been updated successfully');
		}
	}
	/*
	* Creating PDF
	*/
	public function getPdffile($id)
	{
		
		$jobRow = DB::table('gpg_job')->select('*')->where('id','=',$id)->get();
		$cusRow = DB::table('gpg_customer')->select('*')->where('id','=',$jobRow[0]->GPG_customer_id)->get();
		
		$pdf=new Fpdf();
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetMargins(3, 5); 
		
		$str = $jobRow[0]->task;
		$cellWid=160;
		$numLines = ceil($pdf->GetStringWidth($str)/$cellWid);
		if ($numLines>1) { 
		   //$st = split("##",wordwrap($str, floor(strlen(str_replace("\n","",$str))/$numLines), "##",1));
		   $st = explode("##",wordwrap($str, floor(strlen(str_replace("\n","",$str))/$numLines), "##",1));
		}   
		else {
		   $st[] = $str;
		}
		$jDesc =0;
		
		$pdf->AddPage();
		$cellWid = 20;
		$cellHig = 10;
		$pdf->Cell($cellWid,$cellHig,"CHG",1,0,'C');
		$pdf->Cell($cellWid,$cellHig,"",1);
		$pdf->SetFont('Arial','',7);
		$pdf->Text($pdf->GetX()-$cellWid+3,$pdf->GetY()+3,"APPROVED");
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell($cellWid,$cellHig,"COD",1,0,'C');
		
		$cellWid = 83;
		$pdf->Cell($cellWid,$cellHig,"");	
		$pdf->Image(storage_path('logo.jpg'),$pdf->GetX()-$cellWid,$pdf->GetY(),$cellWid,40);

		$cellWid = 30;
		$pdf->Cell($cellWid,$cellHig,"",1);
		$pdf->SetFont('Arial','',7);
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"MOBILE#");
		
		//$pdf->SetFont('Courier','',9);
		//$str = "+923454552464";
		//$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+7,$str);
		
		$pdf->Cell($cellWid,$cellHig,"",1);
		$pdf->SetFont('Arial','',7);
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"WORK DATE");
		
		$pdf->SetFont('Courier','',9);
		$str = ($jobRow[0]->schedule_date?date('m/d/Y',strtotime($jobRow[0]->schedule_date)):'');
		$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+7,$str);
		
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$pdf->Cell($cellWid,$cellHig,"CHECK",1,0,'C');
		$pdf->Cell($cellWid,$cellHig,"CASH",1,0,'C');
		
		$cellWid = 83;
		$pdf->Cell($cellWid,$cellHig,"");	
		
		$cellWid = 60;
		$pdf->Cell($cellWid,$cellHig,"",1);
		$pdf->SetFont('Arial','',7);
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"WORK ORDER NUMBER.");
		$pdf->SetFont('Courier','',9);
		$str = $jobRow[0]->job_num;
		$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+7,$str);
		$pdf->SetFont('Arial','',7);
		
		
		$pdf->Ln();
		$cellWid = 35;
		$pdf->Cell($cellWid,$cellHig,"",1);
		$pdf->SetFont('Arial','',7);
		$pdf->Text($pdf->GetX()-$cellWid+5,$pdf->GetY()+3,"CREDIT CARD TYPE");
		
		$cellWid=25;
		$pdf->Cell($cellWid,$cellHig,"",1);
		$pdf->Text($pdf->GetX()-$cellWid+6,$pdf->GetY()+3,"EXP. DATE");
		
		$cellWid=83;
		$pdf->Cell($cellWid,$cellHig,"");
		
		$cellWid=60;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"CUSTOMER P.O. NO.");
		$pdf->SetFont('Courier','',9);
		$str = $jobRow[0]->cus_purchase_order;
		$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+7,$str);
		$pdf->SetFont('Arial','',7);
		
		
		$pdf->Ln();
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+16,$pdf->GetY()+3,"CREDIT CARD NUMBER");
		
		$cellWid=83;
		$pdf->Cell($cellWid,$cellHig,"");
		
		$cellWid=20;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"MAP");
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"GRID");
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"LOC.");
 		
		$pdf->Ln();
		$cellWid=101.5;
		$cellHig=8;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"CHARGE TO");
		$pdf->SetFont('Courier','',9);
		$str = $cusRow[0]->name;
		$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
		$pdf->SetFont('Arial','',7);
		
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"NAME");
		$pdf->SetFont('Courier','',9);
		$str = $jobRow[0]->location;
		$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
		$pdf->SetFont('Arial','',7);
		
		$pdf->Ln();
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ADDRESS");
		$pdf->SetFont('Courier','',9);
		$str = $cusRow[0]->address." ".$cusRow[0]->city." ".$cusRow[0]->state." ".$cusRow[0]->zipcode;
		if ($pdf->GetStringWidth($str)<$cellWid) {
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
		} else {
		   $str = $cusRow[0]->address;
		   $pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2)+$pdf->GetStringWidth("ADDRESS")-6,$pdf->GetY()+3,$str);
		   $str = $cusRow[0]->city." ".$cusRow[0]->state." ".$cusRow[0]->zipcode;
		   $pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+7,$str);
		}
		$pdf->SetFont('Arial','',7);
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"LOCATION");
		$pdf->SetFont('Courier','',9);
		$str = $jobRow[0]->address1." ".$jobRow[0]->city." ".$jobRow[0]->state." ".$jobRow[0]->zip;
		if ($pdf->GetStringWidth($str)<$cellWid) {
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
		} else {
		   $str = $jobRow[0]->address1;
		   $pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2)+$pdf->GetStringWidth("LOCATION")-6,$pdf->GetY()+3,$str);
		   $str = $jobRow[0]->city." ".$jobRow[0]->state." ".$jobRow[0]->zip;
		   $pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+7,$str);
		}
		$pdf->SetFont('Arial','',7);
		
		$pdf->Ln();
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"JOB CONTACT NAME");
		$pdf->SetFont('Courier','',9);
		$str = $jobRow[0]->job_site_contact;
		$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PHONE NO.");
		$pdf->SetFont('Courier','',9);
		$str = $jobRow[0]->phone;
		$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
		$pdf->SetFont('Arial','',7);
		
		// total width 203;
		$pdf->Ln();
		$cellWid=8;
		$cellHig=7;
		$pdf->SetFont('Arial','B',6);
		$pdf->Cell($cellWid,$cellHig,"T&M",1,0,'C');	
		
		$cellWid=16;
		$pdf->Cell($cellWid,$cellHig,"CONTRACT",1,0,'C');	
		
		$cellWid=19;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+6,$pdf->GetY()+3,"ADD TO");
		$pdf->Text($pdf->GetX()-$cellWid+4,$pdf->GetY()+6,"CONTRACT");
		
		$cellWid=17;
		$pdf->Cell($cellWid,$cellHig,"COST PLUS",1,0,'C');	
		
		$cellWid=16;
		$pdf->Cell($cellWid,$cellHig,"GO BACK",1,0,'C');	
		
		$cellWid=12.5;
		$pdf->Cell($cellWid,$cellHig,"INTER",1,0,'C');	
		
		$cellWid=13;
		$pdf->Cell($cellWid,$cellHig,"OTHER",1,0,'C');	
		
		$cellWid=35;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+2.5,"CONTRACT");
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+5.5,"AMOUNT");
		
		$cellWid=20;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+2.5,"EST.#");
		
		$cellWid=23.5;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+2.5,"PERMIT NEEDED");
		$pdf->Rect($pdf->GetX()-$cellWid+2,$pdf->GetY()+4,2,2);
		$pdf->Rect($pdf->GetX()-$cellWid+13,$pdf->GetY()+4,2,2);
		$pdf->Text($pdf->GetX()-$cellWid+5,$pdf->GetY()+6,"YES");
		$pdf->Text($pdf->GetX()-$cellWid+16,$pdf->GetY()+6,"NO");
		
		$cellWid=23;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+2.5,"PERMIT");
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+5.5,"COST");
		
		$pdf->Ln();
		$cellWid=203;
		$cellHig=8;
		$pdf->SetFont('Arial','',7);
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"INITIAL JOB DESCRIPTION");
		$pdf->SetFont('Courier','',9);
		
		if(isset($st[$jDesc])) {
			$pdf->Text((102)-(($pdf->GetStringWidth($st[$jDesc]))/2),$pdf->GetY()+6,$st[$jDesc]);
			$jDesc++;
		}

		$pdf->Ln();
		$pdf->Cell($cellWid,$cellHig,"",1);	
		if(isset($st[$jDesc])) {
			$pdf->Text((102)-(($pdf->GetStringWidth($st[$jDesc]))/2),$pdf->GetY()+6,$st[$jDesc]);   
			$jDesc++;
		}
		
		$pdf->SetFont('Arial','',7);
		
		
		$pdf->Ln();
		$cellWid=125;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"I HEREBY AUTHORIZE TO PROCEED");
		$pdf->Text($pdf->GetX()-$cellWid+73,$pdf->GetY()+3,"PRINT NAME");
		$pdf->Text($pdf->GetX()-$cellWid+98,$pdf->GetY()+3,"TITLE");
		$pdf->SetFont('Arial','',10);
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+7,"X");
		
		$cellWid=34;
		$pdf->SetFont('Arial','',7);
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+6,$pdf->GetY()+3,"ESTIMATED COST");
		$pdf->Line($pdf->GetX() + 44 , $pdf->GetY()-86, $pdf->GetX() + 44, $pdf->GetY()+179);
		$pdf->Image(storage_path('roundrect.jpg'),$pdf->GetX()+2,$pdf->GetY()+2,40,31);
		$pdf->SetFont('Arial','I',8);
		$topMargen = 10;
		$pdf->Text($pdf->GetX()+5,$pdf->GetY()+$topMargen,"We at Global Power Group");
		$pdf->Text($pdf->GetX()+5,$pdf->GetY()+$topMargen+4,"thank you for your business");
		$pdf->Text($pdf->GetX()+10,$pdf->GetY()+$topMargen+8,"and look forward to");
		$pdf->Text($pdf->GetX()+12,$pdf->GetY()+$topMargen+12,"being of service");
		$pdf->Text($pdf->GetX()+10,$pdf->GetY()+$topMargen+16,"to you in the future.");
		$pdf->Image(storage_path('roundrect.jpg'),$pdf->GetX()+2,$pdf->GetY()+34,40,31);
		$topMargen = 40;
		$pdf->SetFont('Arial','B',9);
		$pdf->Text($pdf->GetX()+12,$pdf->GetY()+$topMargen,"WARRANTY");
		$pdf->SetFont('Arial','I',8);
		$pdf->Text($pdf->GetX()+6,$pdf->GetY()+$topMargen+5,"Global Power Group, Inc.");
		$pdf->Text($pdf->GetX()+6,$pdf->GetY()+$topMargen+9,"will warranty all labor and");
		$pdf->Text($pdf->GetX()+3,$pdf->GetY()+$topMargen+13,"material, excluding lamps and");
		$pdf->Text($pdf->GetX()+3.5,$pdf->GetY()+$topMargen+17,"fuses, on all electrical service");
		$pdf->Text($pdf->GetX()+12,$pdf->GetY()+$topMargen+21,"for two (2) years.");
		
		$pdf->Image(storage_path('roundrect.jpg'),$pdf->GetX()+2,$pdf->GetY()+66,40,31);
		$topMargen = 72;
		$pdf->SetFont('Arial','B',8);
		$pdf->Text($pdf->GetX()+3.5,$pdf->GetY()+$topMargen,"24 Hour Emergency Service");
		$pdf->SetFont('Arial','I',8);
		$pdf->Text($pdf->GetX()+10,$pdf->GetY()+$topMargen+5,"Available Services:");
		$pdf->SetFont('Arial','',8);
		$pdf->Text($pdf->GetX()+6,$pdf->GetY()+$topMargen+9,"Maintenance Agreements,");
		$pdf->Text($pdf->GetX()+11,$pdf->GetY()+$topMargen+13,"Rental Equipment,");
		$pdf->Text($pdf->GetX()+9,$pdf->GetY()+$topMargen+17,"Generator Systems,");
		$pdf->Text($pdf->GetX()+9,$pdf->GetY()+$topMargen+21,"Service and Repairs");
		
		
		
		$pdf->Ln();
		$cellWid=14;
		$cellHig=7;
		$pdf->SetFont('Arial','B',6);
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+6,$pdf->GetY()+3,"PO");
		$pdf->Text($pdf->GetX()-$cellWid+3,$pdf->GetY()+6,"NUMBER");
		
		$cellWid=15;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+2,$pdf->GetY()+3,"QUANTITY");
		$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+6,"REQUESTED");
		
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+2,$pdf->GetY()+3,"QUANTITY");
		$pdf->Text($pdf->GetX()-$cellWid+2,$pdf->GetY()+6,"RECIEVED");
		
		$cellWid=65;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->SetFont('Arial','B',10);
		$pdf->Text($pdf->GetX()-$cellWid+0.5,$pdf->GetY()+4.5,"MATERIAL");
		$pdf->SetFont('Arial','B',6);
		$pdf->Text($pdf->GetX()-$cellWid+19,$pdf->GetY()+4.5,"CIRCLE QUANTITY RETURNED FOR CREDIT");
		
		$cellWid=16;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+2.5,$pdf->GetY()+3,"QUANTITY");
		$pdf->Text($pdf->GetX()-$cellWid+2,$pdf->GetY()+6,"INSTALLED");
		
		$cellWid=34;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Rect($pdf->GetX()-$cellWid,$pdf->GetY(),$cellWid,2.5,'F');
		$pdf->SetTextColor(255, 255, 255); 
		$pdf->Text($pdf->GetX()-$cellWid+8,$pdf->GetY()+2,"SELLING PRICE");
		$pdf->Rect($pdf->GetX()-$cellWid,$pdf->GetY()+2.5,17,4.5);
		$pdf->SetTextColor(0, 0, 0); 
		$pdf->Text($pdf->GetX()-$cellWid+2.5,$pdf->GetY()+5.5,"UNIT PRICE");
		$pdf->Text($pdf->GetX()-$cellWid+19.5,$pdf->GetY()+5.5,"EXTENDED");
		
		$cellHig=7; 
		for ($i=0; $i<12; $i++) {
		$pdf->Ln();
		$cellWid=14;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=15;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=65;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=16;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=5;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=5;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		}
		
		$pdf->Ln();
		$cellWid=109;
		$cellHig=7;
		$pdf->SetFont('Arial','',6);
		$pdf->Cell($cellWid,$cellHig,"THE FOLLOWING WORK WAS ACCOMPLISHED TODAY USING THE ABOVE MATERIAL",1);	
		
		$cellWid=50;
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell($cellWid,$cellHig,"TOTAL MATERIAL",1,0,'C');	
		
		$cellWid=44;
		$pdf->Cell($cellWid,$cellHig,"JOB COST SUMMARY",1,0,'C');	
		
		
		$pdf->Ln();
		$cellWid=109;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=20;
		$pdf->SetFont('Arial','',6);
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+3,$pdf->GetY()+3,"ELECTRICIAN");
		$pdf->Text($pdf->GetX()-$cellWid+7,$pdf->GetY()+6,"RATE");
		
		$cellWid=13;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Text($pdf->GetX()-$cellWid+6,$pdf->GetY()+3,"X");
		$pdf->Text($pdf->GetX()-$cellWid+3,$pdf->GetY()+6,"HOURS");
		
		$cellWid=17;
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell($cellWid,$cellHig,"TOTAL",1,0,'C');	
		
		$cellWid=20;
		$pdf->SetFont('Arial','',6);
		$pdf->Cell($cellWid,$cellHig,"TOTAL MATERIAL",1,0,'C');	
		
		$cellWid=18;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=6;
		$pdf->Cell($cellWid,$cellHig,"",1);	
				
		$pdf->Ln();
		$cellWid=109;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=33;
		$pdf->Cell($cellWid,$cellHig,"FIRST 1/2 HR. CHARGE",1,0,'C');	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=5;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=20;
		$pdf->SetFont('Arial','',6);
		$pdf->Cell($cellWid,$cellHig,"TOTAL LABOR",1,0,'C');	
		
		$cellWid=18;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=6;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$pdf->Ln();
		$cellWid=109;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=20;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=13;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=5;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=20;
		$pdf->SetFont('Arial','',6);
		$pdf->Cell($cellWid,$cellHig,"SALES TAX",1,0,'C');	
		
		$cellWid=18;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=6;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$pdf->Ln();
		$cellWid=109;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=20;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=13;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=5;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		$pdf->Rect($pdf->GetX(),$pdf->GetY(),20,14);
		$pdf->Rect($pdf->GetX()+20,$pdf->GetY(),24,14);
		$pdf->SetFont('Arial','B',7);
		$pdf->Text($pdf->GetX()+5.5,$pdf->GetY()+6,"TOTAL");
		$pdf->Text($pdf->GetX()+3.5,$pdf->GetY()+9,"JOB COST");
		
		
		$pdf->Ln();
		$cellWid=109;
		$pdf->Cell($cellWid,$cellHig,"",0);	
		$pdf->SetFont('Arial','',6);
		$pdf->Text($pdf->GetX() - $cellWid + 3,$pdf->GetY()+3,"I VERIFY ALL WORK IS ACCOMPLISHED AND APPEARS SATISFACTORY.");
		$pdf->Line($pdf->GetX() - $cellWid, $pdf->GetY(), $pdf->GetX() - $cellWid, $pdf->GetY()+12);
		
		$cellWid=33;
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell($cellWid,$cellHig,"TOTAL LABOR",1,0,'C');	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$cellWid=5;
		$pdf->Cell($cellWid,$cellHig,"",1);	
		
		$pdf->Ln();
		$cellWid=125;
		$cellHig=5;
		$pdf->Cell($cellWid,$cellHig,"",0);	
		
		$pdf->Text($pdf->GetX() - $cellWid + 3,$pdf->GetY()+2,"X"); 
		$pdf->SetLineWidth(0);
		$pdf->Line($pdf->GetX() - $cellWid + 5,$pdf->GetY()+2, $pdf->GetX() - $cellWid + 76,  $pdf->GetY()+2);
		$pdf->SetLineWidth(0.2);
		
		$pdf->SetFont('Arial','',5);
		$pdf->Text($pdf->GetX() - $cellWid + 29,$pdf->GetY()+4,"CUSTOMER SIGNATURE"); 
		
		
		$cellWid=78;
		$pdf->SetTextColor(255, 255, 255); 
		$pdf->Cell($cellWid,$cellHig,"ACKNOWLEDGEMENT OF RECEIPT OF PAYMENT",1,0,'C',1);	
		
		
		$pdf->Ln();
		$pdf->SetTextColor(0, 0, 0); 
		$pdf->SetFont('Arial','B',6);
		$cellWid=37;
		$pdf->Cell($cellWid,$cellHig,"EMPLOYEE",1,0,'C');	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"#",1,0,'C');	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"10-8",1,0,'C');	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"10-7",1,0,'C');	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"LUNCH",1,0,'C');	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"LUNCH",1,0,'C');	
		
		$cellWid=12;
		$pdf->Cell($cellWid,$cellHig,"10-8",1,0,'C');	
		
		$cellWid=16;
		$pdf->Cell($cellWid,$cellHig,"HOURS",1,0,'C');	
		
		$pdf->SetLineWidth(0.3);
		$pdf->Rect($pdf->GetX()+0.2,$pdf->GetY(),77.6,25.8);
		$pdf->SetLineWidth(0.2);
		
		$pdf->SetFont('Arial','',6);
		$pdf->Text($pdf->GetX() + 3,$pdf->GetY()+4,"I hereby acknowledge receipt of"); 
		
		$pdf->SetFont('Arial','B',7);
		$pdf->Text($pdf->GetX() + 3,$pdf->GetY()+11,"$"); 
		$pdf->SetLineWidth(0);
		$pdf->Line($pdf->GetX() + 5,$pdf->GetY()+11, $pdf->GetX() + 42,  $pdf->GetY()+11);
		
		$pdf->SetFont('Arial','',7);
		$pdf->Text($pdf->GetX() + 43,$pdf->GetY()+11,"for payment on account."); 
		
		$pdf->Line($pdf->GetX() + 3,$pdf->GetY()+16, $pdf->GetX() + 75,  $pdf->GetY()+16);
		$pdf->SetFont('Arial','',5);
		$pdf->Text($pdf->GetX() + 29,$pdf->GetY()+18,"ELECTRICIAN SIGNATURE"); 
		$pdf->SetLineWidth(0.2);
		
		
		$cellHig=7; 
		
		for ($i=0; $i<3; $i++) {
		  		$pdf->Ln();
				$cellWid=37;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				
				$cellWid=12;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				
				$cellWid=12;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				
				$cellWid=12;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				if ($i==0) $pdf->Text($pdf->GetX()+4,$pdf->GetY()+2,"OUT");
				
				$cellWid=12;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				if ($i==0) $pdf->Text($pdf->GetX()+5,$pdf->GetY()+2,"IN");
				
				$cellWid=12;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				
				
				$cellWid=12;
				$pdf->Cell($cellWid,$cellHig,"",1);	
				
				$cellWid=16;
				$pdf->Cell($cellWid,$cellHig,"",1);	
		}
		
			$cellWid=78;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->SetFont('Arial','',6);
			$pdf->Text($pdf->GetX()- $cellWid+2,$pdf->GetY()+2.5,"CIRCLE ONE");
			
			$pdf->Text($pdf->GetX()-$cellWid+6,$pdf->GetY()+5,"CHECK #");
			$pdf->SetLineWidth(0);
			$pdf->Line($pdf->GetX()-$cellWid+17,$pdf->GetY()+5, $pdf->GetX()-$cellWid+45,  $pdf->GetY()+5);
			$pdf->SetLineWidth(0.2);			
			$pdf->Text($pdf->GetX()-$cellWid+55,$pdf->GetY()+5,"CASH");
			
			$pdf->Text($pdf->GetX()-$cellWid+68,$pdf->GetY()+5,"C/C");
			
			$pdf->Ln();
			$pdf->SetFont('Arial','B',6);
			$cellWid=15;
			$pdf->SetTextColor(255, 255, 255); 
			$pdf->Cell($cellWid,$cellHig,"",1,0,"",1);	
			$pdf->Text($pdf->GetX()- $cellWid +3.5,$pdf->GetY()+3,"CIRCLE");
			$pdf->Text($pdf->GetX()- $cellWid +0.7,$pdf->GetY()+5.5,"JOB STATUS");
			
			$cellWid=14;
			$pdf->SetTextColor(0, 0, 0); 
			$pdf->Cell($cellWid,$cellHig,"COMPLETE",1,0,'C');	
			
			$cellWid=8;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX() - $cellWid +1,$pdf->GetY()+3,"WILL");
			$pdf->Text($pdf->GetX() - $cellWid +1,$pdf->GetY()+5.5,"CALL");
			
			$cellWid=10;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX() - $cellWid +1,$pdf->GetY()+3,"ORDER");
			$pdf->Text($pdf->GetX() - $cellWid +1,$pdf->GetY()+5.5,"PARTS");
			
			$cellWid=12;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX() - $cellWid +0.5,$pdf->GetY()+3,"CALL FOR");
			$pdf->Text($pdf->GetX() - $cellWid +1.5,$pdf->GetY()+5.5,"APPTMT");
			
			$cellWid=8;
			$pdf->Cell($cellWid,$cellHig,"ASAP",1,0,'C');	
			
			$cellWid=8;
			$pdf->Cell($cellWid,$cellHig,"ASAC",1,0,'C');	
			
			$cellWid=14;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX() - $cellWid + 2,$pdf->GetY()+3,"GO BACK");
			$pdf->Text($pdf->GetX() - $cellWid +0.5,$pdf->GetY()+5.5,"TOMORROW");
			
			$cellWid=36;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX() - $cellWid + 2,$pdf->GetY()+2.5,"MILEAGE");
			
			$cellWid=13;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX() - $cellWid + 2,$pdf->GetY()+2.5,"SUPR OK");
			
			$cellWid=12;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX() - $cellWid +2.5,$pdf->GetY()+3,"PANEL");
			$pdf->Text($pdf->GetX() - $cellWid +1.5,$pdf->GetY()+5.5,"STICKER");
			
			$cellWid=40;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX() - $cellWid +8,$pdf->GetY()+2,"INSPECTION NEEDED");
			$pdf->Text($pdf->GetX() - $cellWid +3,$pdf->GetY()+5.5,"RI          FINAL         CL          NONE");
			
			$cellWid=13;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX() - $cellWid + 2,$pdf->GetY()+3,"METER");
			$pdf->Text($pdf->GetX() - $cellWid +0.5,$pdf->GetY()+5.5,"UNSEALED");
	
	        $pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(203,5,"CUSTOMER COPY",0,0,'C');	
 			
			
            // Page 2
			
			if (count($st)>2) {			
			
			$pdf->AddPage();
			$cellWid=101.5;
			$cellHig = 10;
					
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"WORK DATE");
			
			$pdf->SetFont('Courier','',9);
			$str = ($jobRow[0]->schedule_date?date('m/d/Y',strtotime($jobRow[0]->schedule_date)):'');
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+7,$str);
			
			
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"WORK ORDER NUMBER.");
			$pdf->SetFont('Courier','',9);
			$str = $jobRow[0]->job_num;
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+7,$str);
			$pdf->SetFont('Arial','',7);
			
			
			
			$pdf->Ln();
			
			$cellHig=8;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"CHARGE TO");
			$pdf->SetFont('Courier','',9);
			$str = $cusRow[0]->name;
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
			$pdf->SetFont('Arial','',7);
			
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"NAME");
			$pdf->SetFont('Courier','',9);
			$str = $jobRow[0]->location;
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
			$pdf->SetFont('Arial','',7);
			
			$pdf->Ln();
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ADDRESS");
			$pdf->SetFont('Courier','',9);
			$str = $cusRow[0]->address." ".$cusRow[0]->city." ".$cusRow[0]->state." ".$cusRow[0]->zipcode;
			if ($pdf->GetStringWidth($str)<$cellWid) {
				$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
			} else {
			   $str = $cusRow[0]->address;
			   $pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2)+$pdf->GetStringWidth("ADDRESS")-6,$pdf->GetY()+3,$str);
			   $str = $cusRow[0]->city." ".$cusRow[0]->state." ".$cusRow[0]->zipcode;
			   $pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+7,$str);
			}
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"LOCATION");
			$pdf->SetFont('Courier','',9);
			$str = $jobRow[0]->address1." ".$jobRow[0]->city." ".$jobRow[0]->state." ".$jobRow[0]->zip;
			if ($pdf->GetStringWidth($str)<$cellWid) {
				$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
			} else {
			   $str = $jobRow[0]->address1;
			   $pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2)+$pdf->GetStringWidth("LOCATION")-6,$pdf->GetY()+3,$str);
			   $str = $jobRow[0]->city." ".$jobRow[0]->state." ".$jobRow[0]->zip;
			   $pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+7,$str);
			}
			$pdf->SetFont('Arial','',7);
			
			$pdf->Ln();
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"JOB CONTACT NAME");
			$pdf->SetFont('Courier','',9);
			$str = $jobRow[0]->job_site_contact;
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PHONE NO.");
			$pdf->SetFont('Courier','',9);
			$str = $jobRow[0]->phone;
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+6,$str);
			$pdf->SetFont('Arial','',7);
			
			$pdf->Ln();
			$cellWid=203;
			$cellHig=8;
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"INITIAL JOB DESCRIPTION");
			$pdf->SetFont('Courier','',9);
			
			
			if(isset($st[$jDesc])) {
				$pdf->Text((102)-(($pdf->GetStringWidth($st[$jDesc]))/2),$pdf->GetY()+6,$st[$jDesc]);
				$jDesc++;
			}
			
			for ($j12=0; $j12<=27; $j12++) {
				
				$pdf->Ln();
				$pdf->Cell($cellWid,$cellHig,"",1);	
				if(isset($st[$jDesc])) {
					$pdf->Text((102)-(($pdf->GetStringWidth($st[$jDesc]))/2),$pdf->GetY()+6,$st[$jDesc]);   
					$jDesc++;
				}
			
			}
		    }    
		
			$pdf->Output('invoice.pdf','D');
	}
	/*
	* excelJobCostExport
	*/
	public function excelJobCostExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('JobCostExcelFile', function($sheet) {
		    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
			$page = Input::get('page', 1);
	   		$data = $this->getJobCostPage($page);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'inv_amt_date'=>$data->inv_amt_date,'total_sum'=>$data->total_sum);
			$sheet->loadView('job.excelJobCostExport',$params);
		  });
		})->export('xls');
	}

	/*
	* Export Excel File
	*/
	public function excelExport(){ 
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('ElectricalJobExcelFile', function($sheet) {

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

			$files = DB::table('gpg_sales_tracking_attachment')->select('*')->get();
			$files_arr = array();
			foreach ($files as $key => $value)
					$files_arr[$value->gpg_sales_tracking_id] = wordwrap($value->displayname,40, "\n",1);	
	
				
			$technecians = DB::table('gpg_employee')->select('id','name')->where('status','=','A')->orderBy('name')->get();
			$tech_arr = array();
			foreach ($technecians as $key3 => $value3)
					$tech_arr[$value3->id] = $value3->name;	

	 		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
			$salesp_arr = array(''=>'ALL');
			foreach ($salesPerson as $key => $value)
					$salesp_arr[$value->id] = $value->name;
			
			$jobTypes = DB::table('gpg_job')->select('elec_job_type')->where('elec_job_type','!=','')->distinct()->orderBy('elec_job_type')->get();
			$jobtype_arr = array(''=>'Select Job Type');
			foreach ($jobTypes as $key => $value)
					$jobtype_arr[$value->elec_job_type] = preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", " ", $value->elec_job_type);			

			$params = array('query_data'=>$query_data,'totals_qry'=>$data->totals,'cust_arr'=>$cust_arr,'salesp_arr'=>$salesp_arr,'jobtype_arr'=>$jobtype_arr,'tech_arr'=>$tech_arr,'files_arr'=>$files_arr);
	 		

		        $sheet->loadView('job.excelExport',$params);
		    });
		})->export('xls');
 	}
 	/*
	* Job Form
 	*/
 	public function jobForm($id,$j_num){
 		$job_Id_orig = $id;
 		$modules = Generic::modules();
 		$asset_equip = DB::select( DB::raw("select id,concat((select name from gpg_asset_equipment_type where id=gpg_asset_equipment_type_id),' ',eqp_num) as name from gpg_asset_equipment where status = '1' order by eqp_num"));
		$ae_arr = array(''=>'Select Equipment Number');
		foreach ($asset_equip as $key => $value)
				$ae_arr[$value->id] = $value->name;

 		$nat_acc = DB::select( DB::raw("select id, name from gpg_customer where cus_type = 'C' and status = 'A' order by name"));
		$na_arr = array(''=>'Select Company');
		foreach ($nat_acc as $key => $value)
				$na_arr[$value->id] = $value->name;

		$jobCRecord =  DB::select(DB::raw("select gc.* from gpg_customer gc, gpg_job gj where gc.id=gj.GPG_customer_id AND gj.id=".$job_Id_orig.""));
		$jobCTblRow = array();
		foreach ($jobCRecord as $key => $value){
			foreach ($value as $key => $v) {
				$jobCTblRow[$key] = $v;
			}
		}

		$sub_contact = DB::select( DB::raw("select id, name from gpg_vendor where ven_type = 'S' and status = 'A' order by name"));
		$sc_arr = array(''=>'Select Subcontractor');
		foreach ($sub_contact as $key => $value)
				$sc_arr[$value->id] = $value->name;
		asort($this->elecJobTypeArray);	

		$bill_custs =  DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name', 'ASC')->get();
		$cust_arr = array(''=>'Customer');
		foreach ($bill_custs as $key => $value)
				$cust_arr[$value->id] = $value->name;

		$techs = DB::select( DB::raw("select id,name,reg_pay from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$tech_arr = array();
		foreach ($techs as $key => $value)
				$tech_arr[$value->id] = $value->name;

		$sales_emps = DB::select( DB::raw("select id,name from gpg_employee where status ='A' order by name"));
		$sal_emps_arr = array(''=>'Select Option');
		foreach ($sales_emps as $key => $value)
				$sal_emps_arr[$value->id] = $value->name;	
			
		$equips = DB::select( DB::raw("select id,concat(eqp_num,' | ',description) as name from gpg_asset_equipment where eqp_condition ='0' and assign_status = 'checkin' order by eqp_num"));
		$eqp_arr = array();
		foreach ($equips as $key => $value)
				$eqp_arr[$value->id] = $value->name;		

		$jobRecord =  DB::table('gpg_job')->select('*')->where('id','=',$id)->get();
		$jobTblRow = array();
		foreach ($jobRecord as $key => $value){
			foreach ($value as $key => $v) {
				$jobTblRow[$key] = $v;
			}
		}

		$notes =  DB::table('gpg_job_billing_note')->select('*')->where('gpg_job_id','=',$id)->get();
		$notes_arr = "";
		foreach ($notes as $key => $value) {
			$notes_arr .=  $value->note_text."<br/>";
		}

		$job_invoice_info =  DB::table('gpg_job_invoice_info')->select('*')->where('gpg_job_id','=',$id)->orderBy('invoice_date', 'DESC')->get();
		$job_inv_info_arr = array();
		foreach ($job_invoice_info as $key2 => $value2) {
			$temp_arry = array();
			foreach ($value2 as $key => $value) {
				$temp_arry[$key] = $value;
			}
			$job_inv_info_arr[] = $temp_arry;
		}

		
		$emails = DB::select( DB::raw("SELECT * FROM gpg_emails ge,gpg_email_ids gei,gpg_email_attachments gea WHERE ge.gpg_attach_job_num = '".$j_num."' AND ge.gpg_account_id=gei.id AND gea.gpg_email_id=ge.id ORDER BY ge.gpg_account_id, ge.sent_date DESC"));
		$email_arr = array();
		foreach ($emails as $key2 => $value2){
			foreach ($value2 as $key => $value) {
				$email_arr[$key] = $value;
			}
		}

		$id = 0;
		$job_files_id = "";
		$job_logs = DB::select( DB::raw("SELECT gtd.id,ge.name,gtd.workdone,gt.date AS timesheet_date FROM 	gpg_employee ge, gpg_timesheet_detail gtd, gpg_timesheet gt WHERE ge.id = gt.GPG_employee_Id AND gtd.GPG_timesheet_id = gt.id AND gtd.job_num = '".$j_num."' ORDER BY timesheet_date"));
		$job_ltemps_arr = array();
		$job_logs_arr = array();
		$emp_name_array = array();
		foreach ($job_logs as $key2 => $value2){
			foreach ($value2 as $key => $value) {
				if ($key == 'id'){
					$id = $value;
					$job_files_id .= $value.",";
				}
				if($key == 'name')
					$emp_name_array[$id] = $value;

				$job_ltemps_arr[$key] = $value;
			}
			$job_logs_arr[] = $job_ltemps_arr;
		}
		$job_files_arr = array();
		if(strlen($job_files_id)>0){
			$job_files_id = substr($job_files_id,0,strlen($job_files_id)-1);
			$job_files = DB::select( DB::raw("SELECT * FROM gpg_timesheet_attachment WHERE GPG_timesheet_detail_id IN (".$job_files_id.")"));
			$job_ftemp_arr = array();
			foreach ($job_files as $key2 => $value2){
				foreach ($value2 as $key => $value) {
					if ($key == 'GPG_timesheet_detail_id') {
						$job_ftemp_arr[$key] = $emp_name_array[$value];	
					}
					else
						$job_ftemp_arr[$key] = $value;
				}
				$job_files_arr[] = $job_ftemp_arr;
			}
		}

		$job_project_attachs = DB::select( DB::raw("select * from gpg_job_project_attachment where gpg_job_id = '$job_Id_orig'"));
		$JP_Att_Temp = array();
		$JP_Att_arr = array();
		foreach ($job_project_attachs as $key2 => $value2){
			foreach ($value2 as $key => $value) {
				$JP_Att_Temp[$key] = $value;
			}
			$JP_Att_arr[] =  $JP_Att_Temp;
		}

		$task_Data = DB::select( DB::raw("select task_detail,start_date,completion_date,completion_note from gpg_task where job_num like '$j_num'"));
		$taskDataRes = array();
		foreach ($task_Data as $key2 => $value2){
			foreach ($value2 as $key => $value) {
				$taskDataRes[$key] = $value;
			}
		}

		$rfi_Data = DB::select( DB::raw("select rfi.*,rfic.gpg_rfi_id,rfic.rfi_message,rfic.filename,rfic.displayname from gpg_request_for_info rfi LEFT JOIN gpg_request_for_info_comments rfic ON rfi.id=rfic.gpg_rfi_id where rfi.job_num='".$j_num."' AND rfi.gpg_job_id='".$job_Id_orig."'"));
		$rfi_arr = array();
		$rfi_temp_arr = array();
		foreach ($rfi_Data as $key2 => $value2){
			foreach ($value2 as $key => $value) {
				$rfi_temp_arr[$key] = $value;
			}
			$rfi_arr[] = $rfi_temp_arr; 	
		}

		$job_proj_data = DB::select( DB::raw("select * from gpg_job_project where GPG_job_id='".$job_Id_orig."' order by order_no"));
		$jobProj_SArr = array(''=>'Select Parent Task');
		$job_proj_arr = array();
		$job_poj_all_task = array();
		foreach ($job_proj_data as $key2 => $value2){
			$owner_name = '';
			$GPG_employee_id=0;
			$parent_task = '';
			if (isset($job_proj_data[$key2]->GPG_employee_id)) {
				$GPG_employee_id = $job_proj_data[$key2]->GPG_employee_id;
			}
			if (isset($job_proj_data[$key2]->parent_task)) {
				$parent_task = $job_proj_data[$key2]->parent_task;
			}
			if (!empty($GPG_employee_id))
				$GPG_employee_id = rtrim($GPG_employee_id,',');
			
			$owner_name = '';
			if (!empty($GPG_employee_id))
				$owner_name = DB::select( DB::raw("select name from gpg_employee where id IN (".$GPG_employee_id.") order by name"));
			$p_task = DB::select( DB::raw("select title from gpg_job_project where id = '".$parent_task."'"));
			if (!empty($GPG_employee_id))
				$laborHoursQuery = DB::select( DB::raw("SELECT SUM(gtd.time_diff_dec) as time_diff_dec
					FROM gpg_timesheet_detail gtd, gpg_timesheet gt, gpg_employee ge
					WHERE gt.id = gtd.GPG_timesheet_id
					AND gt.GPG_employee_id IN (".$GPG_employee_id.")
					AND ge.id = gt.GPG_employee_Id
					AND (gt.date <= '".$job_proj_data[$key2]->end_date."' AND gt.date >= '".$job_proj_data[$key2]->start_date."')
					AND gtd.job_num = '".$j_num."'"));
			if (!empty($owner_name)) {
				$temp_name= '';
				foreach ($owner_name as $key => $value) {
					$temp_name = $value->name.',';
				}
				$job_poj_all_task['owner_name'] = rtrim($temp_name, ',');
			}
			else 
				$job_poj_all_task['owner_name'] = '';
			if (!empty($p_task)) {
				$job_poj_all_task['parent_task'] = $p_task[0]->title;
			}else
				$job_poj_all_task['parent_task'] = '-';
			if (!empty($laborHoursQuery)){
				$job_poj_all_task['labour_hours'] = $laborHoursQuery[0]->time_diff_dec;
			}else
				$job_poj_all_task['labour_hours'] = 0;	

			$jobProj_SArr[$value2->id] = $value2->title;
			foreach ($value2 as $key => $value) {
				if ($key == 'id')
					$job_poj_all_task['job_task_id'] = $value;

				if ($key != 'parent_task')
					$job_poj_all_task[$key] = $value;
			}
			$job_proj_arr[] = $job_poj_all_task;
		}
		$labor_data = DB::select(DB::raw("SELECT sum(time_diff_dec) as total_in_decimal,sum(total_wage) as total_wage, (SELECT name FROM gpg_employee WHERE id = a.GPG_employee_id) AS emp_name 
								FROM
								gpg_timesheet a ,
								gpg_timesheet_detail b
							WHERE
								a.id = b.GPG_timesheet_id and
								b.GPG_job_id = '$job_Id_orig' 
							GROUP BY emp_name	
							ORDER BY date DESC"));
		$labor_arr = array();
		$labor_temp_arr = array();
		foreach ($labor_data as $key2 => $value2) {
			$emp_name = '';
			$total_in_decimal =0;
			$total_wage =0;
			foreach ($value2 as $key => $value) {
				if ($key == 'emp_name')	
					$emp_name = $value;
				else if($key == 'total_in_decimal')	
					$total_in_decimal = $value;	
				else if($key == 'total_wage')
					$total_wage = $value;

				$labor_temp_arr =  array('label' =>$emp_name , 'value'=>$total_in_decimal,'amount'=>'$'.number_format($total_wage,2));
			}
			$labor_arr[] =  $labor_temp_arr; 
		}

		$jobCostQry = DB::select(DB::raw("SELECT * FROM gpg_job_cost WHERE job_num = '$j_num' ORDER BY date DESC"));
		$jobCost_Arr = array();
		foreach ($jobCostQry as $key => $value) {
			$jobCost_Arr[] = array('label' =>$value->type ,'value'=>date('m/d/Y',strtotime($value->date)),'amount'=>'$'.number_format($value->amount,2));	
		}

		$jobPOCost = DB::select(DB::raw("SELECT
													*,
													a.id as po_id ,
													(b.job_num) as jobNumber,
													(select concat(gl_code,' ',description) from gpg_gl_code where id = b.GPG_gl_code_id and status = 'A') as glCode,
													(select name from gpg_vendor where id = a.GPG_vendor_id and status = 'A') as poVendor,
													(select name from gpg_employee where id = a.request_by_id and status = 'A') as poRequest,
													(select name from gpg_employee where id = a.po_writer_id and status = 'A') as poWriter ,
													(SELECT SUM(amount) FROM gpg_purchase_order_recd_hist WHERE gpg_purchase_order_id=a.id) as total_inv_amount
											FROM
												gpg_purchase_order a, gpg_purchase_order_line_item b
											WHERE
												a.id = b.gpg_purchase_order_id AND
												b.GPG_job_id = '$job_Id_orig' AND
												ifnull(a.soft_delete,0) <> 1
											GROUP BY a.id,b.GPG_job_id ORDER BY a.id DESC"));
		$poJobCostArr = array();
		foreach ($jobPOCost as $key => $value) {
			$poJobCostArr[] = array('label' =>$value->po_id ,'value' =>date('m/d/Y',strtotime($value->po_date)) ,'amount' => '$'.number_format($value->po_quoted_amount,2));
		}
		$poDataQryDetail = DB::select(DB::raw("SELECT *
					, (SELECT CONCAT(gl_code,' ',description)
 					FROM gpg_gl_code
					WHERE id = GPG_gl_code_id
					AND STATUS = 'A') AS glCode
					, (SELECT
					created_on
					FROM gpg_purchase_order
					WHERE id = GPG_purchase_order_id ) AS purchase_order_created_on
					FROM gpg_purchase_order_line_item
					WHERE GPG_job_id = '$job_Id_orig' ORDER BY purchase_order_created_on DESC"));
		$poDataQryArr = array();
		foreach ($poDataQryDetail as $key => $value) {
			$poDataQryArr[] = array('label' =>$value->GPG_purchase_order_id ,'value' =>$value->id,'amount' => '$'.number_format($value->amount,2));
		}

		$assigned_eqps = DB::select( DB::raw("select * from gpg_asset_equipment_history where gpg_job_id='".$job_Id_orig."' AND job_num='".$j_num."'"));
		$assigned_eqpsArr = array();
		if (!empty($assigned_eqps)) {
			foreach ($assigned_eqps as $key => $value) {
				$equip0 = DB::select( DB::raw("select id,concat(eqp_num,' | ',description) as name from gpg_asset_equipment where id ='".$value->gpg_asset_equipment_id."'"));
				$emps0 = DB::select( DB::raw("select id,name from gpg_employee where id ='".$value->gpg_employee_id."'"));
				$assigned_eqpsArr[] = array('id' =>$value->id ,'name' =>$equip0[0]->name ,'emp_name' =>$emps0[0]->name ,'status' =>ucfirst($value->current_status) ,'codate' =>date('m/d/Y',strtotime($value->checkout_date)) ,'cocomment' =>$value->eqp_checkout_condition_description,'cidate' =>$value->checkin_date,'cicomment' =>$value->eqp_checkin_condition_description ,'hstatus' =>'Condition OK');
			}
		}
		$params = array('left_menu' => $modules,'j_id'=>$job_Id_orig,'job_num'=>$j_num,'asset_equip'=>$ae_arr,'nat_acc'=>$na_arr,'sub_contact'=>$sc_arr,'elec_job'=>$this->elecJobTypeArray,'bill_custs'=>$cust_arr,'tech_arr'=>$tech_arr,'jobTblRow'=>$jobTblRow,'notes_arr'=>$notes_arr,'job_inv_info_arr'=>$job_inv_info_arr,'sal_emps_arr'=>$sal_emps_arr,'eqp_arr'=>$eqp_arr,'email_arr'=>$email_arr,'job_logs_arr'=>$job_logs_arr,'job_files_arr'=>$job_files_arr,'jobProjectType'=>$this->jobProjectType,'JP_Att_arr'=>$JP_Att_arr,'taskDataRes_tbl'=>$taskDataRes,'rfi_arr'=>$rfi_arr,'job_proj_arr'=>$job_proj_arr,'empTypeArr'=>$this->empTypeArr,'jobProj_SArr'=>$jobProj_SArr,'labor_arr'=>($labor_arr),'jobCost_Arr'=>($jobCost_Arr),'poJobCostArr'=>($poJobCostArr),'poDataQryArr'=>($poDataQryArr),'jobCTblRow'=>$jobCTblRow,'assigned_eqpsArr'=>$assigned_eqpsArr);
 		return View::make('job.job_form',$params);
 	}
 	/*
	* updateElectricJobs
 	*/
 	public function updateElectricJobs(){
 		set_time_limit(0);	
 		//ini_set('memory_limit', '-1');
 		$taxAmount = '';
 		$job_id = Input::get('job_id');
		$job_num = Input::get('job_num');
		$scheduleDate = Input::get('scheduleDate');
 		$schedule_time0 = explode(' ', Input::get('schedule_time'));
 		$schedule_time = date('H:i:s',strtotime($schedule_time0[0]));
 		$dateCompletion = Input::get('dateCompletion');
 		$gpg_asset_equipment_id = Input::get('gpg_asset_equipment_id');
 		$jobCompleted = Input::get('jobCompleted');
 		if (isset($_POST['jobCompleted'])){
 			$jobCompleted = 1;
 		}
 		else{
 			$jobCompleted = 0;
 			$dateCompletion = '';
 		}
 		$orderConfirmedMargin = Input::get('orderConfirmedMargin');
 		$dateCompletionExpected = Input::get('dateCompletionExpected');
 		$datePermitExpected = Input::get('datePermitExpected');
 		$datePermitOrdered = Input::get('datePermitOrdered');
 		$dateEqpEngaged = Input::get('dateEqpEngaged');
 		$dateEqpOrdered = Input::get('dateEqpOrdered');
 		$dateJobWon = Input::get('dateJobWon');
 		$jobSiteContact = Input::get('jobSiteContact');
 		$locationID = Input::get('locationID');
 		$jobClosed = Input::get('jobClosed');
 		$closingDate = Input::get('closingDate');
 		$nationalAccount = Input::get('nationalAccount');
 		$subContractor = Input::get('subContractor');
 		$propertyManagement = Input::get('propertyManagement');
 		$elecJobType = Input::get('elecJobType');
 		$customerBillto = Input::get('customerBillto');
 		$cusAddress1 = Input::get('cusAddress1');
 		$cusAddress2 = Input::get('cusAddress2');
 		$cusState = Input::get('cusState');
 		$cusZip = Input::get('cusZip');
 		$cusPhone = Input::get('cusPhone');
 		$cusAtt = Input::get('cusAtt');
 		$cusCity = Input::get('cusCity');
 		$fixedPrice = Input::get('fixedPrice');
 		$NTE = Input::get('NTE');
 		$subNTE = Input::get('subNTE');
		$TM = Input::get('TM');
 		$cusPO = Input::get('cusPO');
 		$contact = Input::get('contact');
 		$jobSite = Input::get('jobSite');
 		$jobAddress1 = Input::get('jobAddress1');
 		$jobAddress2 = Input::get('jobAddress2');
 		$jobCity = Input::get('jobCity');
 		$jobState = Input::get('jobState');
 		$jobZip = Input::get('jobZip');
 		$jobPhone = Input::get('jobPhone');
 		$salePersonId = Input::get('salePersonId');
 		$estimator = Input::get('estimator');
 		$job_manager = Input::get('job_manager');
 		$COD = Input::get('COD');
 		$cusWO = Input::get('cusWO');
 		$otherPhone = Input::get('otherPhone');
 		$contractAmount = Input::get('contractAmount');
 		$billingNoteDate = Input::get('billingNoteDate');
 		$jobDescription = Input::get('jobDescription');
 		$workCompleted = Input::get('workCompleted');
 		$recommendation = Input::get('recommendation');
 		$billingNote = Input::get('billingNote');
 		$amountNote = Input::get('amountnote');
 		$Technecians = "";
 		if (isset($_POST['jobTechnicians'])){
	 		foreach ($_POST['jobTechnicians'] as $key => $value) {
	 				$Technecians .= $value.',';
	 		}
	 		DB::table('gpg_job_project')->where('GPG_job_num', $job_num)->update(array('GPG_employee_id' => $Technecians));
	 	}
	 	$jobProjectType = "";
 		if (isset($_POST['jobProjectType'])){
	 		foreach ($_POST['jobProjectType'] as $key => $value) {
	 				$jobProjectType .= $value.',';
	 		}
	 	}

	 	$invoiceCounter = '';
 		if (!empty($_POST['invoiceCounter']) ){
 			$i=1;
 			if (isset($_POST['invNumber_'.$i]))
	 			DB::table('gpg_job_invoice_info')->where('gpg_job_id', '=',$job_id)->delete();
 			$file_type_settings =  DB::table('gpg_settings')
			            ->select('*')
			            ->where('name', '=', '_ImgExt')
			            ->get();    
			$file_types = explode(',', $file_type_settings[0]->value);
	 		while ($i <= $_POST['invoiceCounter']) {
	 			if (isset($_POST['invNumber_'.$i])) {
		 			$invNumber = $_POST['invNumber_'.$i];
	 				$invDate = $_POST['invDate_'.$i];
		 			$invAmount = str_replace(',', '',$_POST['invAmount_'.$i]);
		 			$invTaxAmount = str_replace(',', '',$_POST['invTaxAmount_'.$i]);
		 			if(!empty($invNumber)){
		 				$file = Input::file('invFile_'.$i);
		 				if (!empty($file)){
							if (in_array($file->getClientOriginalExtension(), $file_types)) {
						  		$ext1 = explode(".",$file->getClientOriginalName());
							 	$ext2 = end($ext1);
							 	$filename = "invcImg_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
								$destinationPath = public_path().'/img/';
								$uploadSuccess = $file->move($destinationPath, $filename);
								//insert into db
								DB::table('gpg_job_invoice_info')->insert(array('gpg_job_id' =>$job_id,'job_num'=>$job_num ,'invoice_number'=>$invNumber ,'invoice_amount' =>$invAmount ,'tax_amount' =>$invTaxAmount,'invoice_date' =>$invDate,'attachment' =>$filename,'created_on' =>date('Y-m-d'),'modified_on' =>date('Y-m-d')));		
						  	}
						}else
			 				DB::table('gpg_job_invoice_info')->insert(array('gpg_job_id' =>$job_id,'job_num'=>$job_num ,'invoice_number'=>$invNumber ,'invoice_amount' =>$invAmount ,'tax_amount' =>$invTaxAmount,'invoice_date' =>$invDate,'attachment' =>'','created_on' =>date('Y-m-d'),'modified_on' =>date('Y-m-d')));
		 			}
	 				$i++;
 				}
 		    } //endwhile
 		}else{
 			if (isset($_POST['invNumber_0'])) {
				$invNumber = $_POST['invNumber_0'];
	 			$invDate = $_POST['invDate_0'];
	 			if (isset($_POST['invFile_0']))
		 			$invFile = $_POST['invFile_0'];
		 		else
		 			$invFile = '';
	 			$invAmount = str_replace(',', '',$_POST['invAmount_0']);
	 			$invTaxAmount = str_replace(',', '',$_POST['invTaxAmount_0']);
	 			if (isset($_POST['invTaxAmount_0']))
	 				DB::table('gpg_job_invoice_info')->where('gpg_job_id', $job_id)->where('invoice_number', $invNumber)->update(array('job_num' => $job_num,'invoice_amount' =>$invAmount ,'tax_amount' =>$invTaxAmount,'invoice_date' =>$invDate,'modified_on' =>date('Y-m-d')));
 			}

 		}


 		$link_to_job_num = $_POST['link_to_job_num'];
 		$link_job_id = '';
 		if (isset($_POST['link_to_job_num']) && !empty($_POST['link_to_job_num']) && $_POST['link_to_job_num']!='NULL'){
 			$link_to_job_num = $_POST['link_to_job_num'];
	 		$link_job_id0 = DB::table('gpg_job')->select('id')->where('job_num', '=', $job_num)->get(); 
			if (!empty($link_job_id0))
 				$link_job_id = $link_job_id0[0]->id;
 		}

 		$job_type_id = '';
 		$job_type_id0 = DB::table('gpg_job')->select('GPG_job_type_id')->where('id', '=', $job_id)->get(); 
		if (!empty($job_type_id0))
 			$job_type_id = $job_type_id0[0]->GPG_job_type_id;
 		//Main Query
 		DB::table('gpg_job')->where('id', $job_id)->update(array('link_job_num' => 'NULL','link_job_id' => 'NULL','project_type' =>$jobProjectType,"GPG_customer_id"=>$customerBillto,"location"=>$jobSite,"national_account"=>$nationalAccount,"elec_job_type"=>$elecJobType,"sub_contractor"=>$subContractor,"location_id"=>$locationID,"address1"=>$jobAddress1,"address2"=>$jobAddress2,"city"=>$jobCity,"state"=>$jobState,"zip"=>$jobZip,"phone"=>$jobPhone,"task"=>$jobDescription,"time_material"=>$TM,"fixed_price"=>$fixedPrice,"nte"=>$NTE,"sub_nte"=>$subNTE,"amount_note"=>$amountNote,"work_completed"=>$workCompleted,"GPG_employee_id"=>$salePersonId,"job_site_contact"=>$jobSiteContact,"tax_amount"=>$taxAmount,"estimator"=>$estimator,"job_manager"=>$job_manager,"cod"=>$COD,"cus_purchase_order"=>$cusPO,"cus_work_order"=>$cusWO,"bill_contact"=>$contact,"bill_phone"=>$otherPhone,"complete"=>$jobCompleted,"closed"=>$jobClosed,"schedule_date"=>$scheduleDate,"date_job_won"=>$dateJobWon,"date_eqp_ordered"=>$dateEqpOrdered,"date_eqp_engaged"=>$dateEqpEngaged,"date_permit_ordered"=>$datePermitOrdered,"date_permit_expected"=>$datePermitExpected,"date_completion_expected"=>$dateCompletionExpected,"recommendation"=>$recommendation,"property_management"=>$propertyManagement,"gpg_asset_equipment_id"=>$gpg_asset_equipment_id,'schedule_date'=>$scheduleDate,'schedule_time' => $schedule_time,'date_completion' => $dateCompletion,'closing_date' => $closingDate,'modified_on' => date('Y-m-d')));
 		if (!empty($link_to_job_num) /*&& !empty($link_job_id)*/) {
 			DB::table('gpg_job')->where('id', $job_id)->update(array('link_job_num' =>$link_to_job_num ,'link_job_id' =>$link_job_id,'date_completion'=>$dateCompletion,'complete' => '1'));
 			$invcAmt0 = DB::select(DB::raw("select sum(invoice_amount) as invoice_amount from gpg_job_invoice_info where gpg_job_id = '$job_id'"));
 			$invcAmt = '';
 			$contactAmt = 0;
 			$contactAmt0 = DB::table('gpg_job')->select('contract_amount')->where('job_num', '=', $link_to_job_num)->get(); 
 			if (!empty($contactAmt0))
	 			$contactAmt = $contactAmt0[0]->contract_amount;
 			if (!empty($invcAmt0)){
 				$invcAmt = $invcAmt0[0]->invoice_amount;
 				$orig_amt = $contactAmt - $invcAmt;
 				DB::table('gpg_job')->where('id', $job_id)->update(array('job_num' =>$link_to_job_num));
 			}
 		}
 		if (!empty($customerBillto))
	 		DB::table('gpg_customer')->where('id', $customerBillto)->update(array("address"=>$cusAddress1,"address2"=>$cusAddress2,"city"=>$cusCity,"state"=>$cusState,"zipcode"=>$cusZip,"phone_no"=>$cusPhone,"attn"=>$cusAtt,'modified_on'=>date('Y-m-d')));
	
	 	$job_notes0 = DB::table('gpg_job_billing_note')->select('id')->where('gpg_job_id', '=', $job_id)->get();
	 	if (empty($job_notes0)) 
		 	DB::table('gpg_job_billing_note')->insert(array('gpg_job_id'=> $job_id,'note_text' =>strip_tags($billingNote,'<br/>'),'note_date_time'=>$billingNoteDate,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
		 else
		 	DB::table('gpg_job_billing_note')->where('gpg_job_id', $job_id)->update(array('note_text' =>strip_tags($billingNote,'<br/>'),'note_date_time'=>$billingNoteDate,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));

 		return Redirect::to('job/job_form/'.$job_id.'/'.$job_num.'');
 	}

 	/*
	* jobFormReport
 	*/
	public function jobFormReport($jid,$jnum,$type,$viewby){
		if (!isset($viewby))
			$viewby ='';
		$jobRecord =  DB::table('gpg_job')->select('*')->where('id','=',$jid)->get();
		$jobTblRow = array();
		foreach ($jobRecord as $key => $value){
			foreach ($value as $key => $v) {
				$jobTblRow[$key] = $v;
			}
		}
		$modules = Generic::modules();
		$strData = "";
		if ($type == 'labor') {
					$orderbyLabor = " ORDER BY date DESC";
					if($viewby == "tech")
						$orderbyLabor = " ORDER BY emp_name ASC, date DESC";
					$labor_data = DB::select(DB::raw("SELECT sum(time_diff_dec) as total_in_decimal,sum(total_wage) as total_wage, (SELECT name FROM gpg_employee WHERE id = a.GPG_employee_id) AS emp_name 
										FROM
										gpg_timesheet a ,
										gpg_timesheet_detail b
									WHERE
										a.id = b.GPG_timesheet_id and
										b.GPG_job_id = '$jid' 
									GROUP BY emp_name	
									ORDER BY date DESC"));
					$labor_keys = array();
					foreach ($labor_data as $key => $value) {
						$labor_keys[$value->emp_name] = $value->total_in_decimal;
					}
					$laborDataQuery =  DB::select(DB::raw("SELECT
										*,
										(SELECT name FROM gpg_employee WHERE id = a.GPG_employee_id) AS emp_name ,
										b.id as d_id,
										b.GPG_timetype_id as timetypId,
										(SELECT name FROM gpg_timetype WHERE id = b.GPG_timetype_id) AS emp_timetype ,
										b.labor_rate as LaborRate,
										b.pw_reg_rate as pw_reg,
										b.pw_ot_rate as pw_ot,
										b.pw_dt_rate as pw_dt
									FROM
										gpg_timesheet a ,
										gpg_timesheet_detail b
									WHERE
										a.id = b.GPG_timesheet_id and
										b.GPG_job_id = '$jid'

								".$orderbyLabor));
					$arr_temp1 = array();
					$arr_temp2 = array();
					foreach ($laborDataQuery as $key2 => $value2) {
						foreach ($value2 as $key => $value) {
							if ($key == 'emp_name') {
								$arr_temp1['total_in_decimal'] = $labor_keys[$value];	
							}
							$arr_temp1[$key] = $value;
						}
						$arr_temp2[] = $arr_temp1;
					}
					$laborDbfieldCounter = 0;
					$subtotalhours = 0;
					$subtotallaborcost = 0;
					$techname = "";
					$name_check = "";
					$str_row = "";
					$rowspan = 0;
					$arr_labor_data = array();
		            $employee_check = "";
					$extra_duplicate_check = "";
					foreach ($arr_temp2 as $key3 => $laborDataRow) {
						if($laborDbfieldCounter == 0)
							$techname = $laborDataRow['emp_name'];
						$index1 = $laborDataRow['date'];
						$index2 = $laborDataRow['emp_name'];
						if($viewby=="tech")
						{
							$index1 = $laborDataRow['emp_name'];
							$index2 = $laborDataRow['date'];
						}
						$multiple_check1 =  DB::select(DB::raw("SELECT
																	  COUNT(*) as count
																	FROM gpg_timesheet a,
																	  gpg_timesheet_detail b
																	WHERE a.id = b.GPG_timesheet_id AND
																	a.GPG_employee_Id = '".$laborDataRow['GPG_employee_Id']."'
																	AND a.date = '".$laborDataRow['date']."'
																	AND b.job_num != '".$laborDataRow['job_num']."'"));
						if (!empty($multiple_check1)) {
							$multiple_check = $multiple_check1[0]->count;
						}else
							$multiple_check = '';
						$timearray = $this->get_time_difference( $laborDataRow['time_in'], $laborDataRow['time_out']);
						$arr_labor_data[$index1][$index2]['date'] = $laborDataRow['date'];
						$arr_labor_data[$index1][$index2]['emp_name'] = $laborDataRow['emp_name'];
						$arr_labor_data[$index1][$index2]['data'][] = array(
																											'labor_rate' =>$laborDataRow['LaborRate'],
																											'timetype' => (($laborDataRow['pw_flag']==1)?"<strong>PREV</strong>&nbsp;/&nbsp;".$laborDataRow['emp_timetype']:$laborDataRow['emp_timetype']),
																											"time_in" => $laborDataRow['time_in'],
		                                                                                                    "time_out" => $laborDataRow['time_out'],
		                                                                                                    "total_time" => $timearray['hours'].":".(($timearray['minutes']==0)?'00':$timearray['minutes']),
																											"dec_time" => $laborDataRow['time_diff_dec'],
																											"prevail" => $laborDataRow['pw_flag'],
																											"worked_on_multiple" => $multiple_check
																											);
						if (!isset($arr_labor_data[$index1][$index2]['total_in_decimal'])) {
							$arr_labor_data[$index1][$index2]['total_in_decimal'] = 0;
						}if (!isset($arr_labor_data[$index1][$index2]['reg'])) {
							$arr_labor_data[$index1][$index2]['reg'] =0;
						}if (!isset($arr_labor_data[$index1][$index2]['dt'])) {
							$arr_labor_data[$index1][$index2]['dt']=0;
						}if (!isset($arr_labor_data[$index1][$index2]['ot'])) {
							$arr_labor_data[$index1][$index2]['ot']=0;
						}if (!isset($arr_labor_data[$index1][$index2]['reg_wage'])) {
							$arr_labor_data[$index1][$index2]['reg_wage']=0;
						}if (!isset($arr_labor_data[$index1][$index2]['ot_wage'])) {
							$arr_labor_data[$index1][$index2]['ot_wage']=0;
						}if (!isset($arr_labor_data[$index1][$index2]['dt_wage'])) {
							$arr_labor_data[$index1][$index2]['dt_wage']=0;
						}if (!isset($arr_labor_data[$index1][$index2]['total_wages'])) {
							$arr_labor_data[$index1][$index2]['total_wages']=0;
						}

						$arr_labor_data[$index1][$index2]['total_in_decimal'] += $laborDataRow['time_diff_dec'];
						$arr_labor_data[$index1][$index2]['reg'] += $laborDataRow['reg_hrs'];
						$arr_labor_data[$index1][$index2]['ot'] += $laborDataRow['ot_hrs'];
						$arr_labor_data[$index1][$index2]['dt'] += $laborDataRow['dt_hrs'];
						$arr_labor_data[$index1][$index2]['reg_wage'] += $laborDataRow['reg_wage'];
						$arr_labor_data[$index1][$index2]['ot_wage'] += $laborDataRow['ot_wage'];
						$arr_labor_data[$index1][$index2]['dt_wage'] += $laborDataRow['dt_wage'];
						$arr_labor_data[$index1][$index2]['total_wages'] += $laborDataRow['total_wage'];

						$laborDbfieldCounter++;	
					}
					$data_count=0;
					$subtotallaborcost = 0;
					$grandBigTotal=0;
					$grandHrsTotal=0;
					$subtotalhours = 0;
				 foreach($arr_labor_data as $key => $value)
				 {
					 foreach($value as $key1 => $value1)
					 {
						 $rowspan = sizeof($value1['data']);
						 $show_summary = 1;
						 if ($value1['data'][0]["worked_on_multiple"] >= 1) {
		                                     $bgc ="#e6f4ff";
		                                     $col = "#e6f4ff";
		                                 }else{
		                                     $bgc ="#FFFFFF";
		                                     $col = "#FFFFCC";
		                                 }
						 foreach($value1['data'] as $key2 => $value2)
						 {
								if($viewby == "tech" && $subtotalhours > 0  && $techname != $value1['emp_name'] && $show_summary==1)
								{
									
										$strData .='<tr>
											<td height="15px" colspan="6" bgcolor="#FFFFFF">&nbsp;</td>
											<td height="30" align="center" bgcolor="#FFFFFF"><strong>SUB TOTALS</strong></td>
											<td colspan="2" align="center" bgcolor="#F2F2F2">Hours</td>
											<td colspan="2" align="center" bgcolor="#FFFFCC"><strong>'.$subtotalhours.'</strong></td>
											<td colspan="2" align="center" bgcolor="#F2F2F2">Total $ </td>
											<td  align="center" bgcolor="#FFC1C1"><strong><DIV style="border-bottom:1px solid; width:80px;">'.number_format($subtotallaborcost,2).'</DIV></strong></td>
										</tr>';
									$subtotallaborcost = 0;
									$subtotalhours = 0;
									$techname = $value1['emp_name'];
								}

							  $strData .= '<tr height="30px" bgcolor="'.$bgc.'">
									<td bgcolor="'.$bgc.'" height="15px">
									  '.$value1['emp_name'].'
										<input type="hidden" id="empLaborRate_'.$data_count.'" value="'.$value2['labor_rate'] .'"  />
									</td>
									<td align="center">'. $value2['timetype'].'</td>
									<td align="center">
									  '.(!empty($value1['date'])?date('m/d/Y',strtotime($value1['date'])):'').'</td>
									<td align="center"><div id="laborTimeIn_'.$data_count.'">'.(!empty($value2['time_in'])?date('H:i',strtotime($value2['time_in'])):'') .'</div></td>
									<td align="center"><div id="laborTimeOut_'.$data_count.'">'.(!empty($value2['time_out'])?date('H:i',strtotime($value2['time_out'])):'') .'</div></td>
									<td bgcolor="'.$col.'"><DIV style="margin:auto;background:none repeat scroll 0 0 ;text-align:center" name="laborTimeTotal_'.$data_count.'" class="textWhite" id="laborTimeTotal_'.$data_count.'"  >'.$value2['total_time'].' </DIV></td>';
									if($show_summary==1)
									{
										$strData .= '<td rowspan="'.$rowspan.'" bgcolor="'.$bgc.'" align="center"><DIV id="laborTimeTotalDec_'.$data_count.'">'.number_format($value1['total_in_decimal'],2).'</DIV></td>
										<td rowspan="'.$rowspan.'"><DIV name="regHours_'.$data_count.'" class="" id="regHours_'.$laborDbfieldCounter.'" style="text-align:center">'.number_format($value1['reg'],2).'</DIV></td>
										<td rowspan="'.$rowspan.'"><DIV name="otHours_'.$data_count.'" class="" id="otHours_'.$laborDbfieldCounter.'" style="text-align:center">'.number_format($value1['ot'],2).'</DIV></td>
										<td rowspan="'.$rowspan.'"><DIV name="dtHours_'.$data_count.'" class="" id="dtHours_'.$laborDbfieldCounter.'" style="text-align:center">'.number_format($value1['dt'],2).'</DIV></td>
										<td rowspan="'.$rowspan.'" align="center"><div id="regWage_'.$data_count.'" class="">'.number_format($value1['reg_wage'],2).'</div></td>
										<td rowspan="'.$rowspan.'" align="center"><div id="otWage_'.$data_count.'" class="">'.$value1['ot_wage'].'</div></td>
										<td rowspan="'.$rowspan.'" align="center"><div id="dtWage_'.$data_count.'" class="">'.$value1['dt_wage'].'</div></td>
										<td rowspan="'.$rowspan.'" class="textRed" width="100" align="center"><div id="totalWage_'.$data_count.'">'.number_format($value1['total_wages'],2).'</div></td>
										';
										$subtotalhours+= $value1['total_in_decimal'];
										$subtotallaborcost += $value1['total_wages'];
										$grandBigTotal +=$value1['total_wages'];
										$grandHrsTotal +=$value1['total_in_decimal'];
									 }
								$show_summary = 0;
								$strData .= '</tr>';
								$data_count++;

						 }
					 }
				 }

				 if($viewby == "tech" && $subtotalhours > 0)
						{
							
		                    	$strData .= '<tr>
		                            <td height="15px" colspan="6" bgcolor="#FFFFFF">&nbsp;</td>
		                            <td height="30" align="center" bgcolor="#FFFFFF"><strong>SUB TOTALS</strong></td>
		                            <td colspan="2" align="center" bgcolor="#F2F2F2">Hours</td>
		                            <td colspan="2" align="center" bgcolor="#FFFFCC"><strong>'.number_format($subtotalhours,2).'</strong></td>
		                            <td colspan="2" align="center" bgcolor="#F2F2F2">Total $ </td>
		                            <td  align="center" bgcolor="#FFC1C1"><strong><DIV style="border-bottom:1px solid; width:80px;">'.number_format($subtotallaborcost,2).'</DIV></strong></td>
		                        </tr>';
		                    $subtotallaborcost = 0;
							$subtotalhours = 0;
							$techname = $laborDataRow['emp_name'];
						}

		    $strData .='<tr>
		        <td height="15px" colspan="6" bgcolor="#FFFFFF">&nbsp;</td>
		        <td height="30" align="center" bgcolor="#FFFFFF"><strong>TOTALS</strong></td>
		        <td colspan="2" align="center" bgcolor="#F2F2F2">Total Hours</td>
		        <td colspan="2" align="center" bgcolor="#FFFFCC" id="total_hours_calculated">'.number_format($grandHrsTotal,2).'</td>
		        <td colspan="2" align="center" bgcolor="#F2F2F2">Grand Total $ </td>
		        <td  align="center" bgcolor="#FFC1C1"><strong><DIV id="grandTotalDIV" style="border-bottom:1px solid; width:80px;">'.number_format($grandBigTotal,2).'</DIV></strong></td>
		    </tr>';
		} // if type is labor ends
		else if ($type == 'jobcost') {
			$cost_order_by = "ORDER BY date DESC";
				if(strlen($viewby)>2)
					$cost_order_by = "ORDER BY ".$viewby." ASC, date DESC";
			$costDataQuery = DB::select(DB::raw("SELECT * FROM gpg_job_cost WHERE job_num = '$jnum' ".$cost_order_by));
			$costDataRowData = array();
			$costTempArr = array();
			foreach ($costDataQuery as $key2 => $value2) {
				foreach ($value2 as $key => $costDataRs) {
					$costTempArr[$key] = $costDataRs;
				}
				$costDataRowData[] = $costTempArr;
			}
			$loop_job_cost = 0;
            $totalMaterialCost = 0;
			$subtotalMaterialCost = 0;
			$field_name = "";
			foreach ($costDataRowData as $key => $costDataRow) {
				if($loop_job_cost == 0)
							$field_name = $costDataRow[$viewby];
						if($loop_job_cost > 0 && $field_name != $costDataRow[$viewby] && strlen($viewby) > 2)
						{
							$strData .= '<tr>
                                <td colspan="8" bgcolor="#FFFFFF">&nbsp;</td>
                                <td width="200" height="30" align="center" bgcolor="#F2F2F2"><strong>Subtotal Material Cost$</strong> </td>
                                <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                                <div style="border-bottom:1px solid; width:80px;">&nbsp;$'.
                                number_format($subtotalMaterialCost,2).'
                                </div></strong></td></tr>';
							$field_name = $costDataRow[$viewby];
							$subtotalMaterialCost = 0;

						}
						$strData .= '<tr height="30">
							<td align="center" bgcolor="#FFFFCC">'. $costDataRow['type'] .'</td>
							<td bgcolor="#FFFFFF" align="center">'.date('m/d/Y',strtotime($costDataRow['date'])).'</td>
							<td align="center" bgcolor="#FFFFFF">'. $costDataRow['num'] .'</td>
							<td bgcolor="#FFFFFF">'.$costDataRow['name'].'</td>
							<td bgcolor="#FFFFFF">'.$costDataRow['source_name'].'</td>
							<td bgcolor="#FFFFFF">'.$costDataRow['memo'].'</td>
							<td bgcolor="#FFFFFF">'.$costDataRow['account'].'</td>
							<td bgcolor="#FFFFFF">'.($costDataRow['clr']==1?"Yes":"No").'</td>
							<td bgcolor="#FFFFFF">'.preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '-', $costDataRow['split']).'</td>
							<td bgcolor="#FFFFCC">$'.number_format($costDataRow['amount'],2).'</td></tr>';
						$totalMaterialCost += $costDataRow['amount'];
						$subtotalMaterialCost += $costDataRow['amount'];
						$loop_job_cost++;
			} //end foreach
			if($loop_job_cost > 0 && strlen($viewby) > 2){
				$strData .= '<tr>
                    <td colspan="8" bgcolor="#FFFFFF">&nbsp;</td>
                    <td width="200" height="30" align="center" bgcolor="#F2F2F2"><strong>Subtotal Material Cost $</strong></td>
                    <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                    <div style="border-bottom:1px solid; width:80px;">&nbsp;
                    $'.number_format($subtotalMaterialCost,2).'</div></strong></td></tr>';
					$field_name = $costDataRow[$viewby];
					$subtotalMaterialCost = 0;
			}
            $strData .= '<input type="hidden" name="totalMatCost" id="totalMatCost" value="<?=$totalMaterialCost ?>">
                    <tr><td colspan="8" bgcolor="#FFFFFF">&nbsp;</td>
                    <td width="200" height="30" align="center" bgcolor="#F2F2F2"><strong>Total Material Cost $</strong></td>
                    <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                    <div style="border-bottom:1px solid; width:80px;" id="total_material_cost">$'.number_format($totalMaterialCost,2).'</div></strong></td></tr>';

		}// end if jobcost ends here
		else if ($type == 'jobpo') {
			$po_order_by = "ORDER BY a.id DESC";
				if(strlen($viewby)>2)
				$po_order_by = "ORDER BY ".$viewby." ASC, a.id DESC";
				$poDataQuery = DB::select(DB::raw("SELECT
													*,
													a.id as po_id ,
													(b.job_num) as jobNumber,
													(select concat(gl_code,' ',description) from gpg_gl_code where id = b.GPG_gl_code_id and status = 'A') as glCode,
													(select name from gpg_vendor where id = a.GPG_vendor_id and status = 'A') as poVendor,
													(select name from gpg_employee where id = a.request_by_id and status = 'A') as poRequest,
													(select name from gpg_employee where id = a.po_writer_id and status = 'A') as poWriter ,
													(SELECT SUM(amount) FROM gpg_purchase_order_recd_hist WHERE gpg_purchase_order_id=a.id) as total_inv_amount
											FROM
												gpg_purchase_order a, gpg_purchase_order_line_item b
											WHERE
												a.id = b.gpg_purchase_order_id AND
												b.GPG_job_id = '$jid' AND
												ifnull(a.soft_delete,0) <> 1
											GROUP BY a.id,b.GPG_job_id
											".$po_order_by));
				$poDataTempArr = array();
				$poDataArr = array();
				foreach ($poDataQuery as $key2 => $value2) {
					foreach ($value2 as $key => $value) {
						$poDataTempArr[$key] = $value;
					}
					$poDataArr[] =  $poDataTempArr;
				}
				$po_count=0;
				$field_name = "";
				$totalQuoteAmt = 0;
				$totalAmtToDate = 0;
				$subtotalAmttodate = 0;
				$subtotalQuoteAmt = 0;
				foreach ($poDataArr as $key => $poDataRow){
					if($po_count==0)
						$field_name = $poDataRow[$viewby];
						if($po_count!=0 && $field_name != $poDataRow[$viewby] && strlen($viewby) > 2)
						{
						    $strData .= '<tr height="30">
                            	<td colspan="7" bgcolor="#F2F2F2" align="right"><strong>SUB TOTALS</strong>&nbsp;&nbsp;</td>
                                  <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                                    <div style="border-bottom:1px solid; width:80px;">
                                      $'.number_format($subtotalQuoteAmt,2).'</div></strong></td>
	                                <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                                    <div style="border-bottom:1px solid; width:80px;">
                                    $'.number_format($subtotalAmttodate,2).'</div></strong></td>
									<td colspan="3" bgcolor="#F2F2F2"></td></tr>';
									 $field_name = $poDataRow[$viewby];
									 $subtotalAmttodate = 0;
									 $subtotalQuoteAmt = 0;
						}
						 $po_count++;

                            $strData .= '<tr height="30">
                              <td align="center" bgcolor="#FFFFCC">'.$poDataRow['po_id'].'</td>
				              <td bgcolor="#FFFFFF" align="center">'.date('d/m/Y',strtotime($poDataRow['po_date'])).'</td>
			                  <td bgcolor="#FFFFFF">'.$poDataRow['jobNumber'].$poDataRow['glCode'].'</td>
				              <td bgcolor="#FFFFFF">'.$this->payTypeArray[$poDataRow['payment_form']].'</td>
				              <td bgcolor="#FFFFFF">'.$poDataRow['poVendor'].'</td>
				     		  <td bgcolor="#FFFFFF">'.$poDataRow['poRequest'].'</td>
				              <td bgcolor="#FFFFFF">'.$poDataRow['poWriter'].'</td>
				              <td bgcolor="#FFFFCC">$'.number_format($poDataRow['po_quoted_amount'],2).'</td>
							  <td bgcolor="#FFFFCC">$'.number_format($poDataRow['total_inv_amount'],2).'</td>
							  <td bgcolor="#FFFFFF" align="center">'.($poDataRow['po_est_recpt_date']?date('d/m/Y',strtotime($poDataRow['po_est_recpt_date'])):'').'</td>
							  <td bgcolor="#FFFFFF">'.$poDataRow['sales_order_number'].'</td>
							  <td bgcolor="#FFFFFF">'.$poDataRow['po_note'].'</td></tr>';
							  $totalQuoteAmt += $poDataRow['po_quoted_amount'];
							  $totalAmtToDate += $poDataRow['total_inv_amount'];
							  $subtotalAmttodate += $poDataRow['total_inv_amount'];
							  $subtotalQuoteAmt += $poDataRow['po_quoted_amount'];
				}//end foreach
				
				if($po_count > 0 && strlen($viewby) > 0)
				{
					$strData .= '<tr height="30">
                        <td colspan="7" bgcolor="#F2F2F2" align="right"><strong>SUB TOTALS</strong>&nbsp;&nbsp;</td>
				        <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                        <div style="border-bottom:1px solid; width:80px;">$'.number_format($subtotalQuoteAmt,2).'
                        </div></strong></td><td width="100" align="center" bgcolor="#FFC1C1"><strong>
                        <div style="border-bottom:1px solid; width:80px;">$'.number_format($subtotalAmttodate,2).'
                        </div></strong></td><td colspan="3" bgcolor="#F2F2F2"></td></tr>';
				}
                    $strData .= '<tr height="30"><td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
                        <td width="200" colspan="3" align="center" bgcolor="#F2F2F2"><strong>Grand Total $</strong></td>
                        <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                        <div style="border-bottom:1px solid; width:80px;">$'.number_format($totalQuoteAmt,2).'
                        </div></strong></td><td width="100" align="center" bgcolor="#FFC1C1"><strong>
                        <div style="border-bottom:1px solid; width:80px;">$'.number_format($totalAmtToDate,2).'
                        </div></strong></td><td colspan="3" bgcolor="#F2F2F2"></td></tr>';

		}//end if jobpo
		if ($type == 'jobpo_detail') {
			$totalRate=0;
			$totalQuoteAmt=0;
			$po_detial_order_by = "ORDER BY purchase_order_created_on DESC";
			if(strlen($viewby)>1 && $viewby != 'purchase_order_created_on')
				$po_detial_order_by = "ORDER BY ".$viewby." ASC, purchase_order_created_on DESC";
				$poDataQuery = DB::select(DB::raw("SELECT *
					, (SELECT CONCAT(gl_code,' ',description)
 					FROM gpg_gl_code
					WHERE id = GPG_gl_code_id
					AND STATUS = 'A') AS glCode
					, (SELECT
					created_on
					FROM gpg_purchase_order
					WHERE id = GPG_purchase_order_id ) AS purchase_order_created_on
					FROM gpg_purchase_order_line_item
					WHERE GPG_job_id = '$jid' ".$po_detial_order_by));
				$poDetailTempArr = array();
				$poDetailArr = array();
				foreach ($poDataQuery as $key2 => $value2) {
					foreach ($value2 as $key => $value) {
						$poDetailTempArr[$key] = $value; 
					}
					$poDetailArr[] = $poDetailTempArr;
				}
				foreach ($poDetailArr as $key => $poDataRow) {
				    $strData .= '<tr height="30">
                    <td align="center" bgcolor="#FFFFCC">'.$poDataRow['GPG_purchase_order_id'].'</td>
		            <td bgcolor="#FFFFFF" align="center">'.date('m/d/Y',strtotime($poDataRow['purchase_order_created_on'])).'</td>
                    <td align="center" bgcolor="#FFFFCC">'.$poDataRow['id'].'</td>
			        <td bgcolor="#FFFFFF" align="center">'.date('m/d/Y',strtotime($poDataRow['created_on'])).'</td>
		            <td bgcolor="#FFFFFF">'.$poDataRow['job_num'].$poDataRow['glCode'].'</td>
			        <td bgcolor="#FFFFFF">'.$poDataRow['description'].'</td>
			     	<td bgcolor="#FFFFFF">'.$poDataRow['quantity'].'</td>
			        <td bgcolor="#FFFFCC">$'.number_format($poDataRow['rate'],2).'</td>
					<td bgcolor="#FFFFCC">$'.number_format($poDataRow['amount'],2).'</td>
					<td bgcolor="#FFFFFF">'.($poDataRow['po_received']==1?'Yes':'No').'</td></tr>';
                    $totalRate += $poDataRow['rate'];
					$totalQuoteAmt += $poDataRow['amount'];
				}
					$strData .= '<tr height="30">
                    <td colspan="5" bgcolor="#FFFFFF">&nbsp;</td>
                    <td width="200" colspan="2" align="center" bgcolor="#F2F2F2"><strong>Grand Total $</strong></td>
                    <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                    <div style="border-bottom:1px solid; width:80px;">
                    $'.number_format($totalRate,2).'</div></strong></td>
                    <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                    <div style="border-bottom:1px solid; width:80px;">$'.number_format($totalQuoteAmt,2).'</div></strong></td><td bgcolor="#F2F2F2"></td></tr>';
		}// end if 	jobpo_detail

 		/*echo "<pre>";
		print_r($poDetailArr);
		die();*/
		$params = array('left_menu' => $modules,'display_data'=>$strData,'job_num'=>$jnum,'job_id'=>$jid,'type'=>$type,'viewby'=>$viewby,'jobTblRow'=>$jobTblRow);
 		return View::make('job.job_form_report',$params);
	}
	/*
	* Excel Download
	*/
	public function jobFormReportExcel($jid,$jnum,$type,$viewby,$budget){
		$GLOBALS['jobFormReportExcelArr'] = array('jid' =>$jid , 'jnum'=>$jnum,'type'=>$type,'viewby'=>$viewby,'budget'=>$budget);
		Excel::create('New file', function($excel) {
		    $excel->sheet('JobFormExcelFile', function($sheet) {
		    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		$jid="";$jnum="";$type="";$viewby="";$budget="";    
		foreach ($GLOBALS['jobFormReportExcelArr'] as $key => $value) {
			if ($key == 'jid')
				$jid= $value;	
			if ($key == 'jnum')
				$jnum= $value;
			if ($key == 'type')
				$type= $value;
			if ($key == 'viewby')
				$viewby= $value;
			if ($key == 'budget')
				$budget= $value;
		}
		if (!isset($viewby))
			$viewby ='';
		$jobRecord =  DB::table('gpg_job')->select('*')->where('id','=',$jid)->get();
		$jobTblRow = array();
		foreach ($jobRecord as $key => $value){
			foreach ($value as $key => $v) {
				$jobTblRow[$key] = $v;
			}
		}
		$strData = "";
		if ($type == 'labor') {
					$orderbyLabor = " ORDER BY date DESC";
					if($viewby == "tech")
						$orderbyLabor = " ORDER BY emp_name ASC, date DESC";
					$labor_data = DB::select(DB::raw("SELECT sum(time_diff_dec) as total_in_decimal,sum(total_wage) as total_wage, (SELECT name FROM gpg_employee WHERE id = a.GPG_employee_id) AS emp_name 
										FROM
										gpg_timesheet a ,
										gpg_timesheet_detail b
									WHERE
										a.id = b.GPG_timesheet_id and
										b.GPG_job_id = '$jid' 
									GROUP BY emp_name	
									ORDER BY date DESC"));
					$labor_keys = array();
					foreach ($labor_data as $key => $value) {
						$labor_keys[$value->emp_name] = $value->total_in_decimal;
					}
					$laborDataQuery =  DB::select(DB::raw("SELECT
										*,
										(SELECT name FROM gpg_employee WHERE id = a.GPG_employee_id) AS emp_name ,
										b.id as d_id,
										b.GPG_timetype_id as timetypId,
										(SELECT name FROM gpg_timetype WHERE id = b.GPG_timetype_id) AS emp_timetype ,
										b.labor_rate as LaborRate,
										b.pw_reg_rate as pw_reg,
										b.pw_ot_rate as pw_ot,
										b.pw_dt_rate as pw_dt
									FROM
										gpg_timesheet a ,
										gpg_timesheet_detail b
									WHERE
										a.id = b.GPG_timesheet_id and
										b.GPG_job_id = '$jid'

								".$orderbyLabor));
					$arr_temp1 = array();
					$arr_temp2 = array();
					foreach ($laborDataQuery as $key2 => $value2) {
						foreach ($value2 as $key => $value) {
							if ($key == 'emp_name') {
								$arr_temp1['total_in_decimal'] = $labor_keys[$value];	
							}
							$arr_temp1[$key] = $value;
						}
						$arr_temp2[] = $arr_temp1;
					}
					$laborDbfieldCounter = 0;
					$subtotalhours = 0;
					$subtotallaborcost = 0;
					$techname = "";
					$name_check = "";
					$str_row = "";
					$rowspan = 0;
					$arr_labor_data = array();
		            $employee_check = "";
					$extra_duplicate_check = "";
					foreach ($arr_temp2 as $key3 => $laborDataRow) {
						if($laborDbfieldCounter == 0)
							$techname = $laborDataRow['emp_name'];
						$index1 = $laborDataRow['date'];
						$index2 = $laborDataRow['emp_name'];
						if($viewby=="tech")
						{
							$index1 = $laborDataRow['emp_name'];
							$index2 = $laborDataRow['date'];
						}
						$multiple_check1 =  DB::select(DB::raw("SELECT
																	  COUNT(*) as count
																	FROM gpg_timesheet a,
																	  gpg_timesheet_detail b
																	WHERE a.id = b.GPG_timesheet_id AND
																	a.GPG_employee_Id = '".$laborDataRow['GPG_employee_Id']."'
																	AND a.date = '".$laborDataRow['date']."'
																	AND b.job_num != '".$laborDataRow['job_num']."'"));
						if (!empty($multiple_check1)) {
							$multiple_check = $multiple_check1[0]->count;
						}else
							$multiple_check = '';
						$timearray = $this->get_time_difference( $laborDataRow['time_in'], $laborDataRow['time_out']);
						$arr_labor_data[$index1][$index2]['date'] = $laborDataRow['date'];
						$arr_labor_data[$index1][$index2]['emp_name'] = $laborDataRow['emp_name'];
						$arr_labor_data[$index1][$index2]['data'][] = array(
																											'labor_rate' =>$laborDataRow['LaborRate'],
																											'timetype' => (($laborDataRow['pw_flag']==1)?"<strong>PREV</strong>&nbsp;/&nbsp;".$laborDataRow['emp_timetype']:$laborDataRow['emp_timetype']),
																											"time_in" => $laborDataRow['time_in'],
		                                                                                                    "time_out" => $laborDataRow['time_out'],
		                                                                                                    "total_time" => $timearray['hours'].":".(($timearray['minutes']==0)?'00':$timearray['minutes']),
																											"dec_time" => $laborDataRow['time_diff_dec'],
																											"prevail" => $laborDataRow['pw_flag'],
																											"worked_on_multiple" => $multiple_check
																											);
						if (!isset($arr_labor_data[$index1][$index2]['total_in_decimal'])) {
							$arr_labor_data[$index1][$index2]['total_in_decimal'] = 0;
						}if (!isset($arr_labor_data[$index1][$index2]['reg'])) {
							$arr_labor_data[$index1][$index2]['reg'] =0;
						}if (!isset($arr_labor_data[$index1][$index2]['dt'])) {
							$arr_labor_data[$index1][$index2]['dt']=0;
						}if (!isset($arr_labor_data[$index1][$index2]['ot'])) {
							$arr_labor_data[$index1][$index2]['ot']=0;
						}if (!isset($arr_labor_data[$index1][$index2]['reg_wage'])) {
							$arr_labor_data[$index1][$index2]['reg_wage']=0;
						}if (!isset($arr_labor_data[$index1][$index2]['ot_wage'])) {
							$arr_labor_data[$index1][$index2]['ot_wage']=0;
						}if (!isset($arr_labor_data[$index1][$index2]['dt_wage'])) {
							$arr_labor_data[$index1][$index2]['dt_wage']=0;
						}if (!isset($arr_labor_data[$index1][$index2]['total_wages'])) {
							$arr_labor_data[$index1][$index2]['total_wages']=0;
						}

						$arr_labor_data[$index1][$index2]['total_in_decimal'] += $laborDataRow['time_diff_dec'];
						$arr_labor_data[$index1][$index2]['reg'] += $laborDataRow['reg_hrs'];
						$arr_labor_data[$index1][$index2]['ot'] += $laborDataRow['ot_hrs'];
						$arr_labor_data[$index1][$index2]['dt'] += $laborDataRow['dt_hrs'];
						$arr_labor_data[$index1][$index2]['reg_wage'] += $laborDataRow['reg_wage'];
						$arr_labor_data[$index1][$index2]['ot_wage'] += $laborDataRow['ot_wage'];
						$arr_labor_data[$index1][$index2]['dt_wage'] += $laborDataRow['dt_wage'];
						$arr_labor_data[$index1][$index2]['total_wages'] += $laborDataRow['total_wage'];

						$laborDbfieldCounter++;	
					}
					$data_count=0;
					$subtotallaborcost = 0;
					$grandBigTotal=0;
					$grandHrsTotal=0;
					$subtotalhours = 0;
				 foreach($arr_labor_data as $key => $value)
				 {
					 foreach($value as $key1 => $value1)
					 {
						 $rowspan = sizeof($value1['data']);
						 $show_summary = 1;
						 if ($value1['data'][0]["worked_on_multiple"] >= 1) {
		                                     $bgc ="#e6f4ff";
		                                     $col = "#e6f4ff";
		                                 }else{
		                                     $bgc ="#FFFFFF";
		                                     $col = "#FFFFCC";
		                                 }
						 foreach($value1['data'] as $key2 => $value2)
						 {
								if($viewby == "tech" && $subtotalhours > 0  && $techname != $value1['emp_name'] && $show_summary==1)
								{
									$strData .='<tr>
											<td height="15px" colspan="6" bgcolor="#FFFFFF">&nbsp;</td>
											<td height="30" align="center" bgcolor="#FFFFFF"><strong>SUB TOTALS</strong></td>
											<td colspan="2" align="center" bgcolor="#F2F2F2">Hours</td>
											<td colspan="2" align="center" bgcolor="#FFFFCC"><strong>'.$subtotalhours.'</strong></td>
											<td colspan="2" align="center" bgcolor="#F2F2F2">Total $ </td>
											<td  align="center" bgcolor="#FFC1C1"><strong><DIV style="border-bottom:1px solid; width:80px;">'.$subtotallaborcost.'</DIV></strong></td>
										</tr>';
									$subtotallaborcost = 0;
									$subtotalhours = 0;
									$techname = $value1['emp_name'];
								}

							  $strData .= '<tr height="30px" bgcolor="'.$bgc.'">
									<td bgcolor="'.$bgc.'" height="15px">
									  '.$value1['emp_name'].'
										<input type="hidden" id="empLaborRate_'.$data_count.'" value="'.$value2['labor_rate'] .'"  />
									</td>
									<td align="center">'. $value2['timetype'].'</td>
									<td align="center">
									  '.(!empty($value1['date'])?date('m/d/Y',strtotime($value1['date'])):'').'</td>
									<td align="center"><div id="laborTimeIn_'.$data_count.'">'.(!empty($value2['time_in'])?date('H:i',strtotime($value2['time_in'])):'') .'</div></td>
									<td align="center"><div id="laborTimeOut_'.$data_count.'">'.(!empty($value2['time_out'])?date('H:i',strtotime($value2['time_out'])):'') .'</div></td>
									<td bgcolor="'.$col.'"><DIV style="margin:auto;background:none repeat scroll 0 0 ;text-align:center" name="laborTimeTotal_'.$data_count.'" class="textWhite" id="laborTimeTotal_'.$data_count.'"  >'.$value2['total_time'].' </DIV></td>';
									if($show_summary==1)
									{
										$strData .= '<td rowspan="'.$rowspan.'" bgcolor="'.$bgc.'" align="center"><DIV id="laborTimeTotalDec_'.$data_count.'">'.number_format($value1['total_in_decimal'],2).'</DIV></td>
										<td rowspan="'.$rowspan.'"><DIV name="regHours_'.$data_count.'" class="" id="regHours_'.$laborDbfieldCounter.'" style="text-align:center">'.number_format($value1['reg'],2).'</DIV></td>
										<td rowspan="'.$rowspan.'"><DIV name="otHours_'.$data_count.'" class="" id="otHours_'.$laborDbfieldCounter.'" style="text-align:center">'.number_format($value1['ot'],2).'</DIV></td>
										<td rowspan="'.$rowspan.'"><DIV name="dtHours_'.$data_count.'" class="" id="dtHours_'.$laborDbfieldCounter.'" style="text-align:center">'.number_format($value1['dt'],2).'</DIV></td>
										<td rowspan="'.$rowspan.'" align="center"><div id="regWage_'.$data_count.'" class="">'.$value1['reg_wage'].'</div></td>
										<td rowspan="'.$rowspan.'" align="center"><div id="otWage_'.$data_count.'" class="">'.$value1['ot_wage'].'</div></td>
										<td rowspan="'.$rowspan.'" align="center"><div id="dtWage_'.$data_count.'" class="">'.$value1['dt_wage'].'</div></td>
										<td rowspan="'.$rowspan.'" class="textRed" width="100" align="center"><div id="totalWage_'.$data_count.'">'.$value1['total_wages'].'</div></td>
										';
										$subtotalhours+= $value1['total_in_decimal'];
										$subtotallaborcost += $value1['total_wages'];
										$grandBigTotal +=$value1['total_wages'];
										$grandHrsTotal +=$value1['total_in_decimal'];
									 }
								$show_summary = 0;
								$strData .= '</tr>';
								$data_count++;

						 }
					 }
				 }

				 if($viewby == "tech" && $subtotalhours > 0)
						{
							
		                    	$strData .= '<tr>
		                            <td height="15px" colspan="6" bgcolor="#FFFFFF">&nbsp;</td>
		                            <td height="30" align="center" bgcolor="#FFFFFF"><strong>SUB TOTALS</strong></td>
		                            <td colspan="2" align="center" bgcolor="#F2F2F2">Hours</td>
		                            <td colspan="2" align="center" bgcolor="#FFFFCC"><strong>'.$subtotalhours.'</strong></td>
		                            <td colspan="2" align="center" bgcolor="#F2F2F2">Total $ </td>
		                            <td  align="center" bgcolor="#FFC1C1"><strong><DIV style="border-bottom:1px solid; width:80px;">'.$subtotallaborcost.'</DIV></strong></td>
		                        </tr>';
		                    $subtotallaborcost = 0;
							$subtotalhours = 0;
							$techname = $laborDataRow['emp_name'];
						}

		    $strData .='<tr>
		        <td height="15px" colspan="6" bgcolor="#FFFFFF">&nbsp;</td>
		        <td height="30" align="center" bgcolor="#FFFFFF"><strong>TOTALS</strong></td>
		        <td colspan="2" align="center" bgcolor="#F2F2F2">Total Hours</td>
		        <td colspan="2" align="center" bgcolor="#FFFFCC" id="total_hours_calculated">'.$grandHrsTotal.'</td>
		        <td colspan="2" align="center" bgcolor="#F2F2F2">Grand Total $ </td>
		        <td  align="center" bgcolor="#FFC1C1"><strong><DIV id="grandTotalDIV" style="border-bottom:1px solid; width:80px;">'.$grandBigTotal.'</DIV></strong></td>
		    </tr>';
		} // if type is labor ends
		else if ($type == 'jobcost') {
			$cost_order_by = "ORDER BY date DESC";
				if(strlen($viewby)>2)
					$cost_order_by = "ORDER BY ".$viewby." ASC, date DESC";
			$costDataQuery = DB::select(DB::raw("SELECT * FROM gpg_job_cost WHERE job_num = '$jnum' ".$cost_order_by));
			$costDataRowData = array();
			$costTempArr = array();
			foreach ($costDataQuery as $key2 => $value2) {
				foreach ($value2 as $key => $costDataRs) {
					$costTempArr[$key] = $costDataRs;
				}
				$costDataRowData[] = $costTempArr;
			}
			$loop_job_cost = 0;
            $totalMaterialCost = 0;
			$subtotalMaterialCost = 0;
			$field_name = "";
			foreach ($costDataRowData as $key => $costDataRow) {
				if($loop_job_cost == 0)
							$field_name = $costDataRow[$viewby];
						if($loop_job_cost > 0 && $field_name != $costDataRow[$viewby] && strlen($viewby) > 2)
						{
							$strData .= '<tr>
                                <td colspan="8" bgcolor="#FFFFFF">&nbsp;</td>
                                <td width="200" height="30" align="center" bgcolor="#F2F2F2"><strong>Subtotal Material Cost$</strong> </td>
                                <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                                <div style="border-bottom:1px solid; width:80px;">&nbsp;$'.
                                number_format($subtotalMaterialCost,2).'
                                </div></strong></td></tr>';
							$field_name = $costDataRow[$viewby];
							$subtotalMaterialCost = 0;

						}
						$strData .= '<tr height="30">
							<td align="center" bgcolor="#FFFFCC">'. $costDataRow['type'] .'</td>
							<td bgcolor="#FFFFFF" align="center">'.date('m/d/Y',strtotime($costDataRow['date'])).'</td>
							<td align="center" bgcolor="#FFFFFF">'. $costDataRow['num'] .'</td>
							<td bgcolor="#FFFFFF">'.$costDataRow['name'].'</td>
							<td bgcolor="#FFFFFF">'.$costDataRow['source_name'].'</td>
							<td bgcolor="#FFFFFF">'.$costDataRow['memo'].'</td>
							<td bgcolor="#FFFFFF">'.$costDataRow['account'].'</td>
							<td bgcolor="#FFFFFF">'.($costDataRow['clr']==1?"Yes":"No").'</td>
							<td bgcolor="#FFFFFF">'.preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '-', $costDataRow['split']).'</td>
							<td bgcolor="#FFFFCC">$'.number_format($costDataRow['amount'],2).'</td></tr>';
						$totalMaterialCost += $costDataRow['amount'];
						$subtotalMaterialCost += $costDataRow['amount'];
						$loop_job_cost++;
			} //end foreach
			if($loop_job_cost > 0 && strlen($viewby) > 2){
				$strData .= '<tr>
                    <td colspan="8" bgcolor="#FFFFFF">&nbsp;</td>
                    <td width="200" height="30" align="center" bgcolor="#F2F2F2"><strong>Subtotal Material Cost $</strong></td>
                    <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                    <div style="border-bottom:1px solid; width:80px;">&nbsp;
                    $'.number_format($subtotalMaterialCost,2).'</div></strong></td></tr>';
					$field_name = $costDataRow[$viewby];
					$subtotalMaterialCost = 0;
			}
            $strData .= '<input type="hidden" name="totalMatCost" id="totalMatCost" value="<?=$totalMaterialCost ?>">
                    <tr><td colspan="8" bgcolor="#FFFFFF">&nbsp;</td>
                    <td width="200" height="30" align="center" bgcolor="#F2F2F2"><strong>Total Material Cost $</strong></td>
                    <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                    <div style="border-bottom:1px solid; width:80px;" id="total_material_cost">$'.number_format($totalMaterialCost,2).'</div></strong></td></tr>';

		}// end if jobcost ends here
		else if ($type == 'jobpo') {
			$po_order_by = "ORDER BY a.id DESC";
				if(strlen($viewby)>2)
				$po_order_by = "ORDER BY ".$viewby." ASC, a.id DESC";
				$poDataQuery = DB::select(DB::raw("SELECT
													*,
													a.id as po_id ,
													(b.job_num) as jobNumber,
													(select concat(gl_code,' ',description) from gpg_gl_code where id = b.GPG_gl_code_id and status = 'A') as glCode,
													(select name from gpg_vendor where id = a.GPG_vendor_id and status = 'A') as poVendor,
													(select name from gpg_employee where id = a.request_by_id and status = 'A') as poRequest,
													(select name from gpg_employee where id = a.po_writer_id and status = 'A') as poWriter ,
													(SELECT SUM(amount) FROM gpg_purchase_order_recd_hist WHERE gpg_purchase_order_id=a.id) as total_inv_amount
											FROM
												gpg_purchase_order a, gpg_purchase_order_line_item b
											WHERE
												a.id = b.gpg_purchase_order_id AND
												b.GPG_job_id = '$jid' AND
												ifnull(a.soft_delete,0) <> 1
											GROUP BY a.id,b.GPG_job_id
											".$po_order_by));
				$poDataTempArr = array();
				$poDataArr = array();
				foreach ($poDataQuery as $key2 => $value2) {
					foreach ($value2 as $key => $value) {
						$poDataTempArr[$key] = $value;
					}
					$poDataArr[] =  $poDataTempArr;
				}
				$po_count=0;
				$field_name = "";
				$totalQuoteAmt = 0;
				$totalAmtToDate = 0;
				$subtotalAmttodate = 0;
				$subtotalQuoteAmt = 0;
				foreach ($poDataArr as $key => $poDataRow){
					if($po_count==0)
						$field_name = $poDataRow[$viewby];
						if($po_count!=0 && $field_name != $poDataRow[$viewby] && strlen($viewby) > 2)
						{
						    $strData .= '<tr height="30">
                            	<td colspan="7" bgcolor="#F2F2F2" align="right"><strong>SUB TOTALS</strong>&nbsp;&nbsp;</td>
                                  <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                                    <div style="border-bottom:1px solid; width:80px;">
                                      $'.number_format($subtotalQuoteAmt,2).'</div></strong></td>
	                                <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                                    <div style="border-bottom:1px solid; width:80px;">
                                    $'.number_format($subtotalAmttodate,2).'</div></strong></td>
									<td colspan="3" bgcolor="#F2F2F2"></td></tr>';
									 $field_name = $poDataRow[$viewby];
									 $subtotalAmttodate = 0;
									 $subtotalQuoteAmt = 0;
						}
						 $po_count++;

                            $strData .= '<tr height="30">
                              <td align="center" bgcolor="#FFFFCC">'.$poDataRow['po_id'].'</td>
				              <td bgcolor="#FFFFFF" align="center">'.date('d/m/Y',strtotime($poDataRow['po_date'])).'</td>
			                  <td bgcolor="#FFFFFF">'.$poDataRow['jobNumber'].$poDataRow['glCode'].'</td>
				              <td bgcolor="#FFFFFF">'.$this->payTypeArray[$poDataRow['payment_form']].'</td>
				              <td bgcolor="#FFFFFF">'.$poDataRow['poVendor'].'</td>
				     		  <td bgcolor="#FFFFFF">'.$poDataRow['poRequest'].'</td>
				              <td bgcolor="#FFFFFF">'.$poDataRow['poWriter'].'</td>
				              <td bgcolor="#FFFFCC">$'.number_format($poDataRow['po_quoted_amount'],2).'</td>
							  <td bgcolor="#FFFFCC">$'.number_format($poDataRow['total_inv_amount'],2).'</td>
							  <td bgcolor="#FFFFFF" align="center">'.($poDataRow['po_est_recpt_date']?date('d/m/Y',strtotime($poDataRow['po_est_recpt_date'])):'').'</td>
							  <td bgcolor="#FFFFFF">'.$poDataRow['sales_order_number'].'</td>
							  <td bgcolor="#FFFFFF">'.$poDataRow['po_note'].'</td></tr>';
							  $totalQuoteAmt += $poDataRow['po_quoted_amount'];
							  $totalAmtToDate += $poDataRow['total_inv_amount'];
							  $subtotalAmttodate += $poDataRow['total_inv_amount'];
							  $subtotalQuoteAmt += $poDataRow['po_quoted_amount'];
				}//end foreach
				
				if($po_count > 0 && strlen($viewby) > 0)
				{
					$strData .= '<tr height="30">
                        <td colspan="7" bgcolor="#F2F2F2" align="right"><strong>SUB TOTALS</strong>&nbsp;&nbsp;</td>
				        <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                        <div style="border-bottom:1px solid; width:80px;">$'.number_format($subtotalQuoteAmt,2).'
                        </div></strong></td><td width="100" align="center" bgcolor="#FFC1C1"><strong>
                        <div style="border-bottom:1px solid; width:80px;">$'.number_format($subtotalAmttodate,2).'
                        </div></strong></td><td colspan="3" bgcolor="#F2F2F2"></td></tr>';
				}
                    $strData .= '<tr height="30"><td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
                        <td width="200" colspan="3" align="center" bgcolor="#F2F2F2"><strong>Grand Total $</strong></td>
                        <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                        <div style="border-bottom:1px solid; width:80px;">$'.number_format($totalQuoteAmt,2).'
                        </div></strong></td><td width="100" align="center" bgcolor="#FFC1C1"><strong>
                        <div style="border-bottom:1px solid; width:80px;">$'.number_format($totalAmtToDate,2).'
                        </div></strong></td><td colspan="3" bgcolor="#F2F2F2"></td></tr>';

		}//end if jobpo
		if ($type == 'jobpo_detail') {
			$totalRate=0;
			$totalQuoteAmt=0;
			$po_detial_order_by = "ORDER BY purchase_order_created_on DESC";
			if(strlen($viewby)>1 && $viewby != 'purchase_order_created_on')
				$po_detial_order_by = "ORDER BY ".$viewby." ASC, purchase_order_created_on DESC";
				$poDataQuery = DB::select(DB::raw("SELECT *
					, (SELECT CONCAT(gl_code,' ',description)
 					FROM gpg_gl_code
					WHERE id = GPG_gl_code_id
					AND STATUS = 'A') AS glCode
					, (SELECT
					created_on
					FROM gpg_purchase_order
					WHERE id = GPG_purchase_order_id ) AS purchase_order_created_on
					FROM gpg_purchase_order_line_item
					WHERE GPG_job_id = '$jid' ".$po_detial_order_by));
				$poDetailTempArr = array();
				$poDetailArr = array();
				foreach ($poDataQuery as $key2 => $value2) {
					foreach ($value2 as $key => $value) {
						$poDetailTempArr[$key] = $value; 
					}
					$poDetailArr[] = $poDetailTempArr;
				}
				foreach ($poDetailArr as $key => $poDataRow) {
				    $strData .= '<tr height="30">
                    <td align="center" bgcolor="#FFFFCC">'.$poDataRow['GPG_purchase_order_id'].'</td>
		            <td bgcolor="#FFFFFF" align="center">'.date('m/d/Y',strtotime($poDataRow['purchase_order_created_on'])).'</td>
                    <td align="center" bgcolor="#FFFFCC">'.$poDataRow['id'].'</td>
			        <td bgcolor="#FFFFFF" align="center">'.date('m/d/Y',strtotime($poDataRow['created_on'])).'</td>
		            <td bgcolor="#FFFFFF">'.$poDataRow['job_num'].$poDataRow['glCode'].'</td>
			        <td bgcolor="#FFFFFF">'.$poDataRow['description'].'</td>
			     	<td bgcolor="#FFFFFF">'.$poDataRow['quantity'].'</td>
			        <td bgcolor="#FFFFCC">$'.number_format($poDataRow['rate'],2).'</td>
					<td bgcolor="#FFFFCC">$'.number_format($poDataRow['amount'],2).'</td>
					<td bgcolor="#FFFFFF">'.($poDataRow['po_received']==1?'Yes':'No').'</td></tr>';
                    $totalRate += $poDataRow['rate'];
					$totalQuoteAmt += $poDataRow['amount'];
				}
					$strData .= '<tr height="30">
                    <td colspan="5" bgcolor="#FFFFFF">&nbsp;</td>
                    <td width="200" colspan="2" align="center" bgcolor="#F2F2F2"><strong>Grand Total $</strong></td>
                    <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                    <div style="border-bottom:1px solid; width:80px;">
                    $'.number_format($totalRate,2).'</div></strong></td>
                    <td width="100" align="center" bgcolor="#FFC1C1"><strong>
                    <div style="border-bottom:1px solid; width:80px;">$'.number_format($totalQuoteAmt,2).'</div></strong></td><td bgcolor="#F2F2F2"></td></tr>';
		}// end if 	jobpo_detail
		$params = array('display_data'=>$strData,'job_num'=>$jnum,'job_id'=>$jid,'type'=>$type,'viewby'=>$viewby,'jobTblRow'=>$jobTblRow);   
        $sheet->loadView('job.job_form_report_excel',$params);
		    });
		})->export('xls');
	}

 	/*
	* manageFiles
 	*/
	public function manageFiles(){
		$job_id = Input::get('job_id');
		$job_num = Input::get('job_num');
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
			 	$filename = "job_project_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
				$destinationPath = public_path().'/img/';
				$uploadSuccess = $file->move($destinationPath, $filename);
				//insert into db
				DB::table('gpg_job_project_attachment')->insert(array('gpg_job_id' =>$job_id,'filename'=>$filename ,'displayname'=>$file->getClientOriginalName()));		
			}
		}
		return Redirect::to('job/job_form/'.$job_id.'/'.$job_num.'');
	}

	/*
	* getDownloadJobFile
	*/
	public function getDownloadJobFile($id,$table){
		$data = DB::table('gpg_job_project_attachment')->where('id', '=',$id)->select('*')->get();
		$file_path = public_path()."/img/".@$data[0]->filename;
		if (file_exists($file_path)){
			$headers = array('Content-Type: application/vnd.ms-excel; charset=utf-8');
 		    return Response::download($file_path, 'info.xls',$headers);
        }else
        	return Redirect::route('job/job_form')->withMessage('file not found');       
	}

 	/*
	* getEmails
 	*/
 	public function getEmails(){
 		$name = Input::get('name');
 		$subject = Input::get('subject');
 		$start_date = Input::get('start_date');
 		$end_date = Input::get('end_date');
 		$job_num = Input::get('job_num');
 		$queryPart = "";

 		if ($name != '0')
 			$queryPart .= " AND to_name like '%,".$name.",%'";
 		if ($subject != '0')
 			$queryPart .= " AND email_subject like '%,".$subject.",%'";
 		if ($start_date != '0' && $end_date!='0')
 			$queryPart .= " AND sent_date >= '".date('Y-m-d',strtotime($start_date))."' AND sent_date <= '".date('Y-m-d',strtotime($end_date))."'";	
 		else if ($start_date != '0' && $end_date=='0')
 			$queryPart .= " AND sent_date >= '".date('Y-m-d',strtotime($start_date))."'";
 		
 		$emails_data = DB::select( DB::raw("SELECT * FROM gpg_emails WHERE gpg_attach_job_num = '".$job_num."' $queryPart ORDER BY gpg_account_id, sent_date DESC"));
		$email_row = "";
		foreach ($emails_data as $key => $value){
			$email_row .= "<tr><td>".$value->to_name."</td><td>".$value->email_subject."</td><td>".$value->sent_date."</td></tr>";
		}

		if (empty($email_row))
			return "<tr><td colspan='3'>No Result Found</td></tr>";
		else
	 		return $email_row;
 	}
 	/*
	* Get Time difference 
 	*/
 	public function get_time_difference( $start, $end )
	{
		
	    $uts['start']      =    strtotime( $start );
	    $uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
	    {
			
	        if( $uts['end'] >= $uts['start'] )
	        {
	            $diff    =    $uts['end'] - $uts['start'];
	            if( $days=intval((floor($diff/86400))) )
	                $diff = $diff % 86400;
	            if( $hours=intval((floor($diff/3600))) )
	                $diff = $diff % 3600;
	            if( $minutes=intval((floor($diff/60))) )
	                $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	        }
	        else
	        {
				
	            $uts['start']      =    strtotime( $start );
	   			$uts['end']        =    strtotime( $end );
				$abc = $uts['end']+86400;
				$diff    =    ($abc - $uts['start']);
	            if( $days=intval((floor($diff/86400))) )
	                $diff = $diff % 86400;
	            if( $hours=intval((floor($diff/3600))) )
	                $diff = $diff % 3600;
	            if( $minutes=intval((floor($diff/60))) )
	                $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	        }
	    }
	    else
	    {
	        trigger_error( "Invalid date/time data detected", E_USER_WARNING );
	    }
	    return( false );
	}
 	/*
	* getAreaAssetFields
	*/
	public function getAreaAssetFields(){
		$Id = Input::get('id');
		$type = Input::get('type');
		$fldName ="";
		$tblName ="";
		$queryPart ="";

		switch ($type) {
			case "Location":
			 $tblName = "gpg_property_location";
			 $fldName = "location_name";
			 $queryPart = " gpg_customer_id = '$Id' order by location_name";
			 	 
			break;
			case "Area":
			 $tblName = "gpg_property_area";
			 $fldName = "area_name"; 
			 $queryPart = " gpg_property_location_id = '$Id' order by area_name";
			break;
			case "Asset":
			 $tblName = "gpg_property_asset";
			 $fldName = "asset_name"; 
			 $queryPart = " gpg_property_area_id = '$Id' order by asset_name";
			break;
		}
		$retStr = "";
		if (!empty($fldName) && !empty($tblName) && !empty($queryPart)){	
			$qry = DB::select( DB::raw("select id,$fldName from $tblName WHERE $queryPart"));
			foreach ($qry as $key => $value) {
				$name = strtolower($type)."_name";
				$retStr .= '<option value="'.$value->id.'">'.$value->$name.'</option>';
			}
		}
		return $retStr;
	}
	/*
	* createRFI
	*/
	public function createRFI(){
		$title = Input::get('rfi_title');
		$requested = Input::get('RequestToId');
		$rfi_text = Input::get('rfi');
		$status = Input::get('rfiStatus');
		if ($status != '')
			$status = 1;
		else
			$status = 0;
		$creator = Input::get('creator');//
		$jobNum = Input::get('JobNumber');
		$jobId = Input::get('jobId');//
		DB::table('gpg_request_for_info')
		    ->insert(array('job_num' =>$jobNum, 'gpg_job_id'=>$jobId,'gpg_requested_by_id'=>$creator,'gpg_requested_to_id'=>$requested,'title'=>$title,'is_admin'=>'1','status'=>$status,'created_on'=>date("Y-m-d H:i:s"),'modified_on'=>date("Y-m-d H:i:s")));
		$max_id = DB::table('gpg_request_for_info')->max('id');
		$file_type_settings =  DB::table('gpg_settings')
            ->select('*')
            ->where('name', '=', '_ImgExt')
            ->get();    
            $file_types = explode(',', $file_type_settings[0]->value);
        //@@@@ Set attachments
		$file = Input::file('rfifileToUpload');
			if (!empty($file)) {
				if (in_array($file->getClientOriginalExtension(), $file_types)) {
			  			$ext1 = explode(".",$file->getClientOriginalName());
				 		$ext2 = end($ext1);
				 		$filename = "rfi_image_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
						$destinationPath = public_path().'/img/';
						$uploadSuccess = $file->move($destinationPath, $filename);
						//insert into db
						DB::table('gpg_request_for_info_comments')->insert(
					    array('gpg_rfi_id' =>$max_id, 'gpg_commenter_id'=>$creator,'rfi_message'=>$rfi_text,'is_admin'=>'1','filename'=>$filename,'displayname'=>$file->getClientOriginalName(),'created_on'=>date("Y-m-d H:i:s"))
						);		
			  	}
			}else
				DB::table('gpg_request_for_info_comments')->insert(array('gpg_rfi_id' =>$max_id, 'gpg_commenter_id'=>$creator,'rfi_message'=>$rfi_text,'is_admin'=>'1','created_on'=>date("Y-m-d H:i:s")));
						
		return Redirect::to('job/job_form/'.$jobId.'/'.$jobNum.'');
	}
	/*
	* createProjJob
	*/
	public function createProjJob(){
		
		$include_days = '';
	    if (isset($_POST['projectIncludeDays']))
	    	$include_days = $_POST['projectIncludeDays'];
	    $completed = '';
	    $completed_date = '';
	    if (isset($_POST['projectCompleted'])){
			$completed = $_POST['projectCompleted'];
			$completed_date = date('Y-m-d');
	    }
	    $gpg_emps ="";
	    if (!empty( $_POST['projectOwnerLeft'])) {
	    	foreach ($_POST['projectOwnerLeft'] as $key => $value) {
	    		$gpg_emps .= $value;
	    	}
	    }
	    $gpg_emps = rtrim($gpg_emps,',');

	    $end_date = '';
		if(!empty($_POST['projectDays']) && !empty($_POST['projectStartDate'])) {
				
				$diffDays = $_POST['projectDays'];

				$start_date = date('Y-m-d',strtotime($_POST['projectStartDate']));
				
				$diffDays-=1;
				if($diffDays==0)
					$end_date = $start_date;
				else
					$end_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($start_date)) . " +$diffDays day"));
				if($include_days == '')
				{
					if(date('l',strtotime($start_date))=='Sunday')
						$start_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($start_date)) . " +1 day"));
					if(date('l',strtotime($start_date))=='Saturday')
						$start_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($start_date)) . " +2 day"));
					$end_date = $this->check_weekends($start_date, $end_date, $diffDays);
				}
		}
		/* ------------------- PCODES --------------------------------*/
		$getProjectId = (DB::table('gpg_job_project')->max('id'))+1;
		$chkPCodeId2 = DB::table('gpg_job_project')->select('pcode_2')->where('gpg_job_id','=',$_POST['jobId'])->get();
		$pcodeId2 = 0;
		if (empty($chkPCodeId2)) {
			$pcodeId2 = (DB::table('gpg_job_project')->max('pcode_2'))+1;
		} else {
		   $pcodeId2 = $chkPCodeId2[0]->pcode_2;
		}
		$pcodeId3 = 1;
		$pcodeId3_ = DB::select(DB::raw("select max(pcode_3) as pcode_3 from gpg_job_project where gpg_job_id = ".$_POST['jobId'].""));
		if (!empty($pcodeId3_)) {
			$pcodeId3 = ($pcodeId3_[0]->pcode_3)+1;
		}
		$order_no = 1;
		$order_no_ = DB::select(DB::raw("select max(order_no) as order_no from gpg_job_project where gpg_job_id = ".$_POST['jobId'].""));
		if (!empty($order_no_)) {
			$order_no = ($order_no_[0]->order_no)+1;
		}
		/* ```````````````````````````````````````````````````````````*/
		$Temp1_Arr  = array('id' =>$getProjectId, 'pcode_1'=>'1', 'pcode_2'=>$pcodeId2, 'pcode_3'=>$pcodeId3,'GPG_job_id'=>$_POST['jobId'],'GPG_job_num'=>$_POST['jobNum'],'resource_hours'=>$_POST['projectResourceForecast'],'title'=>$_POST['projectTaskTitle'],'start_date'=>$_POST['projectStartDate'],'days'=>$_POST['projectDays'],'subcontractor'=>$_POST['subcontractor'],'project_activity_id'=>$_POST['project_activity_id'],'notes'=>$_POST['notes'],'parent_task'=>$_POST['parentTask'],'GPG_employee_id'=>$gpg_emps,'include_days'=>$include_days,'completed'=>$completed,'task_type'=>$_POST['selectEmpType'],'end_date'=>$end_date,'order_no'=>$order_no);
		DB::table('gpg_job_project')->insert($Temp1_Arr);
		DB::table('gpg_job')->where('id', $_POST['jobId'])->update(array('project_title' => $_POST['projectTitle']));    
		return Redirect::to('job/job_form/'.$_POST['jobId'].'/'.$_POST['jobNum'].'');
	}
	/*
	* createProjJob
	*/
	public function createProjTaskJob(){
		
		$include_days = '';
	    if (isset($_POST['projectIncludeDays2']))
	    	$include_days = $_POST['projectIncludeDays2'];
	    $completed = '';
	    $completed_date = '';
	    if (isset($_POST['projectCompleted2'])){
			$completed = $_POST['projectCompleted2'];
			$completed_date = date('Y-m-d');
	    }
	    $gpg_emps ="";
	    if (!empty( $_POST['projectOwnerLeft2'])) {
	    	foreach ($_POST['projectOwnerLeft2'] as $key => $value) {
	    		$gpg_emps .= $value.',';
	    	}
	    }
	    $gpg_emps = rtrim($gpg_emps,',');

	    $end_date = '';
		if(!empty($_POST['projectDays2']) && !empty($_POST['projectStartDate2'])) {
				
				$diffDays = $_POST['projectDays2'];

				$start_date = date('Y-m-d',strtotime($_POST['projectStartDate2']));
				
				$diffDays-=1;
				if($diffDays==0)
					$end_date = $start_date;
				else
					$end_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($start_date)) . " +$diffDays day"));
				if($include_days == '')
				{
					if(date('l',strtotime($start_date))=='Sunday')
						$start_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($start_date)) . " +1 day"));
					if(date('l',strtotime($start_date))=='Saturday')
						$start_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($start_date)) . " +2 day"));
					$end_date = $this->check_weekends($start_date, $end_date, $diffDays);
				}
		}
		/* ------------------- PCODES --------------------------------*/
		$getProjectId = (DB::table('gpg_job_project')->max('id'))+1;
		$chkPCodeId2 = DB::table('gpg_job_project')->select('pcode_2')->where('gpg_job_id','=',$_POST['jobId2'])->get();
		$pcodeId2 = 0;
		if (empty($chkPCodeId2)) {
			$pcodeId2 = (DB::table('gpg_job_project')->max('pcode_2'))+1;
		} else {
		   $pcodeId2 = $chkPCodeId2[0]->pcode_2;
		}
		$pcodeId3 = 1;
		$pcodeId3_ = DB::select(DB::raw("select max(pcode_3) as pcode_3 from gpg_job_project where gpg_job_id = ".$_POST['jobId2'].""));
		if (!empty($pcodeId3_)) {
			$pcodeId3 = ($pcodeId3_[0]->pcode_3)+1;
		}
		$order_no = 1;
		$order_no_ = DB::select(DB::raw("select max(order_no) as order_no from gpg_job_project where gpg_job_id = ".$_POST['jobId2'].""));
		if (!empty($order_no_)) {
			$order_no = ($order_no_[0]->order_no)+1;
		}
		/* ```````````````````````````````````````````````````````````*/
		$Temp1_Arr  = array('id' =>$getProjectId, 'pcode_1'=>'1', 'pcode_2'=>$pcodeId2, 'pcode_3'=>$pcodeId3,'GPG_job_id'=>$_POST['jobId2'],'GPG_job_num'=>$_POST['jobNum2'],'resource_hours'=>$_POST['projectResourceForecast2'],'title'=>$_POST['projectTaskTitle2'],'start_date'=>$_POST['projectStartDate2'],'days'=>$_POST['projectDays2'],'subcontractor'=>$_POST['subcontractor2'],'project_activity_id'=>$_POST['project_activity_id2'],'notes'=>$_POST['notes2'],'parent_task'=>$_POST['parentTask2'],'GPG_employee_id'=>$gpg_emps,'include_days'=>$include_days,'completed'=>$completed,'task_type'=>$_POST['selectEmpType2'],'end_date'=>$end_date,'order_no'=>$order_no);
		DB::table('gpg_job_project')->insert($Temp1_Arr);
		//DB::table('gpg_job')->where('id', $_POST['jobId2'])->update(array('project_title' => $_POST['projectTitle2']));    
		return Redirect::to('job/job_form/'.$_POST['jobId2'].'/'.$_POST['jobNum2'].'');
	}
		/*
	* createProjJob
	*/
	public function updateProjTaskJob(){
		if (!isset($_POST['parentTask3'])) {
			$_POST['parentTask3'] = '';
		}
		$include_days = '';
	    if (isset($_POST['projectIncludeDays3']))
	    	$include_days = Input::get('projectIncludeDays3');
	    $completed = '';
	    $completed_date = '';
	    if (isset($_POST['projectCompleted3'])){
			$completed = Input::get('projectCompleted3');
			$completed_date = date('Y-m-d');
	    }
	    $gpg_emps ="";
	    if (!empty( $_POST['projectOwnerLeft3'])) {
	    	foreach (Input::get('projectOwnerLeft3') as $key => $value) {
	    		$gpg_emps .= $value.',';
	    	}
	    }
	    $gpg_emps = rtrim($gpg_emps,',');

	    $end_date = '';
		if(!empty($_POST['projectDays3']) && !empty($_POST['projectStartDate3'])) {
				
				$diffDays = Input::get('projectDays3');

				$start_date = date('Y-m-d',strtotime(Input::get('projectStartDate3')));
				
				$diffDays-=1;
				if($diffDays==0)
					$end_date = $start_date;
				else
					$end_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($start_date)) . " +$diffDays day"));
				if($include_days == '')
				{
					if(date('l',strtotime($start_date))=='Sunday')
						$start_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($start_date)) . " +1 day"));
					if(date('l',strtotime($start_date))=='Saturday')
						$start_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($start_date)) . " +2 day"));
					$end_date = $this->check_weekends($start_date, $end_date, $diffDays);
				}
		}
		$getProjectId = (DB::table('gpg_job_project')->max('id'))+1;
		$Temp1_Arr  = array('id' =>$getProjectId, 'GPG_job_id'=>Input::get('jobId3'),'GPG_job_num'=>Input::get('jobNum3'),'resource_hours'=>Input::get('projectResourceForecast3'),'title'=>Input::get('projectTaskTitle3'),'start_date'=>Input::get('projectStartDate3'),'days'=>Input::get('projectDays3'),'subcontractor'=>Input::get('subcontractor3'),'project_activity_id'=>Input::get('project_activity_id3'),'notes'=>Input::get('notes3'),'parent_task'=>Input::get('parentTask3'),'GPG_employee_id'=>$gpg_emps,'include_days'=>$include_days,'completed'=>$completed,'task_type'=>Input::get('selectEmpType3'),'end_date'=>$end_date);
		DB::table('gpg_job_project')->where('id', Input::get('task_hidden_id'))->update($Temp1_Arr);
		return Redirect::to('job/job_form/'.$_POST['jobId3'].'/'.$_POST['jobNum3'].'');
	}
	public function check_weekends($f_start_date, $f_end_date, $days)
	{
		// total number of weekends in a date range
		$f_num_weekend = 0;
		// checking each day
		$total_days = $days;
		for($loop_day=1; $loop_day <= $total_days; $loop_day++)
		{
			$loop_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($f_start_date)) . " +".$loop_day." day"));
			if(date('l',strtotime($loop_date))== "Sunday" or date('l',strtotime($loop_date))== "Saturday")
			{
				$total_days++;
				$days++;
			}
		}
		$f_end_date = date('Y-m-d',strtotime(date('Y-m-d', strtotime($f_start_date)) . " +".$days." day"));
		return $f_end_date;
	}
	/*
	* Delete Files for job
	*/
	public function deleteFiles(){
		DB::table('gpg_job_project_attachment')
		  		->where('id', '=',$_GET['id'])
		  		->where('gpg_job_id', '=',$_GET['job_id'])
	          	->delete();
	    return 1;      	
	}

	/*
	* destroyRFI
	*/
	public function destroyRFI($id,$cid,$jid,$jnum){
		DB::table('gpg_request_for_info')
		  		->where('id', '=',$id)
	          	->delete();
	    if ($cid!='0'){	
	    	DB::table('gpg_request_for_info_comments')
		  		->where('id', '=',$cid)
	          	->delete();
	    } 
	    return Redirect::to('job/job_form/'.$jid.'/'.$jnum.'');     	
	}
	/*
	* completeRFI
	*/
	public function completeRFI($id,$jid,$jnum){
		DB::table('gpg_request_for_info')
		    ->where('id', $id)
		    ->update(array('status' => '1'));
		return Redirect::to('job/job_form/'.$jid.'/'.$jnum.'');
	}
	
	/*
	* getJobTaskInfo
	*/
	public function getJobTaskInfo(){
		$task_id = Input::get('task_id');
		$job_task_data = DB::table('gpg_job_project')->select('*')->where('id', '=',$task_id)->get();
		$job_task_arr = array();
		foreach($job_task_data as $dkey => $data){
            foreach ($data as $key => $value) {
            	$job_task_arr[$key] = $value;
            }
        }
        return Response::json($job_task_arr);
	}

	/*
	* Import Excel File
	*/
	public function importExcelJobTasks(){
		$job_id = $_POST['job_id'];
		$job_num = $_POST['job_num'];
		$GLOBALS['jobIdANDJobNum'] = array('job_id'=>$job_id,'job_num'=>$job_num);
		$file = Input::file('ExlFileToUpload');
		$file_type_settings =  DB::table('gpg_settings')
	        ->select('*')
	        ->where('name', '=', '_ImgExt')
	        ->get();    
		$file_types = explode(',', $file_type_settings[0]->value);
		$ext2 = '';
		$filename = '';
		$destinationPath = '';
		if (!empty($file)){
			if (in_array($file->getClientOriginalExtension(), $file_types)) {
				$ext1 = explode(".",$file->getClientOriginalName());
			 	$ext2 = end($ext1);
			 	$filename = "importExcelFile.".$ext2;
				$destinationPath = public_path().'/img/';
				if ($ext2 == 'xls' || $ext2 == 'xlsx')
					$uploadSuccess = $file->move($destinationPath, $filename);
			}
		}
		$file_location = $destinationPath.$filename;
		if ($ext2 == 'xls' || $ext2 == 'xlsx'){

			Excel::selectSheetsByIndex(0)->load($file_location , function($reader) {
				$results = $reader->toArray();
				$job_id = $GLOBALS['jobIdANDJobNum']['job_id'];
				$job_num = $GLOBALS['jobIdANDJobNum']['job_num'];
			    $project_name = $job_num." Project";
				$sum_hours = 0;
				$task_type="";
				$activity_id="";
				$description="";
				$total_hours="";
				$total_mens="";
				$sum_days = 0;
				$total_days="";
				$task_start_date="";
				$task_end_date="";
				$previous_date="";
				$previous_task_type="";
				$hours_array = array();
				$dates_array = array();
				$order_no=0;
			    foreach ($results as $key => $data) {
			    	$description1 = "";
			    	if (!empty($data['activity_id'])) { // main data if starts

			    		/*Fetch Values from excel*/
				    	$previous_task_type=$task_type;
				    	$task_type = $data['type_of_task'];
				    	
				    	$description = $data['task'];
				    	if (strlen($description) > 255) {
							$description = substr($description,0,252).'...';
							$description1 = $data['task'];
						}

						$activity_id = $data['activity_id'];
				    	$total_hours = $data['hours_alloted_per_task'];
				    	$total_mens = $data['men_per_day'];
				    	$total_days = $data['days'];

				    	$tmp_task_start_date = explode(' ', $data['task_start']);
						$task_start_date = date("Y-m-d", strtotime($tmp_task_start_date[1]));

						$previous_date = $task_end_date;
						$tmp_task_end_date = explode(' ', $data['task_end']);
						$task_end_date = date("Y-m-d", strtotime($tmp_task_end_date[1]));
						
						/* Algorithm for modifying values for saving in db*/
						$start = strtotime($task_start_date);
						$end = strtotime($task_end_date);
						$diff_days = ceil(abs($end - $start)/86400);
						$diff_days++;
						$i=0;
						$ik=0;
						$jk=0;
						$ik_point=0;
						$previous_hours=0;
						$temp_hours_array = array();
						$temp_dates_array = array();
	
						while ($i<$diff_days) { // while starts here
							$day_date = date("Y-m-d", strtotime("+$i day", strtotime($task_start_date)));
							$day_name = date("D", strtotime("+$i day", strtotime($task_start_date)));
							
							if ($task_start_date == $previous_date && $previous_task_type==$task_type  && $day_name!= 'Sat' && $day_name!= 'Sun') {
								if ($ik_point == 0) {
									$temp_array = array();
									if ($total_mens<2) {
										$second_half_day_hours = (4*$total_mens);
									}
									else
										$second_half_day_hours = 8;
									array_push($temp_array, $second_half_day_hours);
									$remaning_hours = $total_hours-$second_half_day_hours;
									
									if ($remaning_hours>0) {
										$day_hours1 = floor($remaning_hours/($total_mens*8));
										if ($day_hours1>0) {
											$kp=0;
											while ($kp<$day_hours1) {
												array_push($temp_array, $total_mens*8);
												$kp++;
											}
										}
										$day_hours2 = $remaning_hours%($total_mens*8);
										if ($day_hours2>0) {
											array_push($temp_array, $day_hours2);
										}		
									}
									$ik_point=1;
								}
								if (isset($temp_array[$ik]))
									$day_hours = $temp_array[$ik];
								$ik++;
							}
							else if ($previous_task_type!=$task_type && $day_name!= 'Sat' && $day_name!= 'Sun') {
									if ($jk<$total_days) {
										$day_hours = $total_hours/ceil($total_days);
										$jk++;
									}
									else
										$day_hours="";
							}else if($task_start_date != $previous_date && $previous_task_type==$task_type  && $day_name!= 'Sat' && $day_name!= 'Sun'){
								if ($jk<$total_days) {
										$day_hours = $total_hours/ceil($total_days);
										$jk++;
									}
									else
										$day_hours="";
							}

							if ($day_name!= 'Sat' && $day_name!= 'Sun') {
								array_push($temp_hours_array, $day_hours);
								array_push($temp_dates_array, $day_date);
							}
							$i++;
							if ($i==$diff_days) {
								/**************************************
								------insertion starts here------------
								***************************************/
								/////****** Generting Pcodes ****//////
								$chkPCodeId2 = DB::table('gpg_job_project')->select('pcode_2')->where('gpg_job_id','=',$job_id)->get();
								$pcodeId2 = 0;
								if (empty($chkPCodeId2)) {
									$pcodeId2 = (DB::table('gpg_job_project')->max('pcode_2'))+1;
								} else {
								   $pcodeId2 = $chkPCodeId2[0]->pcode_2;
								}
								$pcodeId3 = 1;
								$pcodeId3_ = DB::select(DB::raw("select max(pcode_3) as pcode_3 from gpg_job_project where gpg_job_id = ".$job_id.""));
								if (!empty($pcodeId3_)) {
									$pcodeId3 = ($pcodeId3_[0]->pcode_3)+1;
								}
								$order_no = 1;
								$order_no_ = DB::select(DB::raw("select max(order_no) as order_no from gpg_job_project where gpg_job_id = ".$job_id.""));
								if (!empty($order_no_)) {
									$order_no = ($order_no_[0]->order_no)+1;
								}
								/* ```````````````````````````````````````````````````````````*/
								// Search for existing activity /////
	                 			$SelectQuery =  DB::select(DB::raw("Select * from gpg_job_project WHERE GPG_job_num = '".$job_num."' AND project_activity_id = '".$activity_id."'"));
	                 			if (!empty($SelectQuery)) { // if activity id exists then update
	                 			  	
	                 				DB::table('gpg_job_project')
									    ->where('GPG_job_num', $job_num)
									    ->where('project_activity_id', $activity_id)
									    ->update(array('pcode_1' => '1', 'pcode_2' => $pcodeId2,'pcode_3' => $pcodeId3,'title' => $description,'days'=>$total_days,'start_date'=>$task_start_date,'end_date'=>$task_end_date,'completed'=>0,'project_activity_id'=>$activity_id,'task_type'=>$task_type,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'order_no'=>$order_no,'notes'=>$description1));

									DB::table('gpg_job')
									    ->where('id', $job_id)
									    ->update(array('project_title'=>$project_name));    

									$uq_result ='';
									$uq_id = '';
									$uq_result = DB::select(DB::raw("SELECT id from gpg_job_project jp Where jp.GPG_job_id = '".$job_id."' AND jp.project_activity_id = '".$activity_id."'"));
									if (!empty($uq_result)) {
										$uq_id = $uq_result[0]->id;
										DB::table('gpg_job_project_task')->where('gpg_job_project_id', '=',$uq_id)->delete();	
								    }            			             
									foreach ($temp_hours_array as $date_hrs_key => $hrs_value) {
										if (!empty($hrs_value)) {
											$project_date = $temp_dates_array[$date_hrs_key];

											DB::table('gpg_job_project_task')
											    ->insert(array('gpg_job_project_id' => $uq_id,
													           'project_task' => $description,
													           'task_status' => 'N',
													           'projected' => $hrs_value,
													           'completed' => 0,
													           'modified_on' => date('Y-m-d'),
													           'created_on' => date('Y-m-d'),
													           'project_date' => $project_date));
										}
									}


	                 			}
	                 			else{ 
		                 				DB::table('gpg_job_project')
											->insert(array('GPG_job_id' => $job_id,
														 'GPG_job_num' =>$job_num,
														 'pcode_1' => '1',
											             'pcode_2' => $pcodeId2,
										    	      	 'pcode_3' => $pcodeId3,
											             'title' => $description,
											             'days' => $total_days,
											             'start_date' => $task_start_date,
											             'end_date' => $task_end_date,
											             'completed' => 0,
											             'project_activity_id' => $activity_id,
											             'task_type' => $task_type,
											             'created_on' => date('Y-m-d'),
											             'modified_on' => date('Y-m-d'),
											             'order_no' => $order_no,
											             'notes' => $description1
											    	));
										$gpg_job_project_id = DB::table('gpg_job_project')->max('id');
										DB::table('gpg_job')->where('id', $job_id)->update(array('project_title'=>$project_name));
										
										foreach ($temp_hours_array as $date_hrs_key => $hrs_value) {
											if (!empty($hrs_value)) {
												$project_date = $temp_dates_array[$date_hrs_key];
												DB::table('gpg_job_project_task')
											    ->insert(array('gpg_job_project_id' => $gpg_job_project_id,
													           'project_task' => $description,
													           'task_status' => 'N',
													           'projected' => $hrs_value,
													           'completed' => 0,
													           'modified_on' => date('Y-m-d'),
													           'created_on' => date('Y-m-d'),
													           'project_date' => $project_date));
											}
										}
									/*insertion ends here----------*/
								}//else ends here
							}
						} //while main ends here
			    	} // main data if ends here
			    	if (isset($data['task']) && $data['task'] == 'Totals') {
			    		$sum_hours = $data['hours_alloted_per_task'];
			    		$sum_days = $data['days'];
			    	}
			    }//end foreach
			});
		}
		return Redirect::to('job/job_form/'.$job_id.'/'.$job_num.'');
	}
	/*
	* Equipments CheckOut
	*/
	public function equipCheckOut(){
		$jid = $_POST['job_id'];
		$jnum = $_POST['job_num'];
		if (isset($_POST['job_id']) && isset($_POST['job_num']) && isset($_POST['assetEqpId']) && isset($_POST['assetEqpTech']) && isset($_POST['assetEqpCheckoutDate'])) {
	   		DB::table('gpg_asset_equipment_history')->insert(array('gpg_asset_equipment_id' =>$_POST['assetEqpId'] ,'gpg_job_id' =>$_POST['job_id'] ,'gpg_employee_id' =>$_POST['assetEqpTech'] ,'job_num' =>$_POST['job_num'] ,'eqp_checkout_condition_description' =>$_POST['assetEqpCond'] ,'checkout_date' =>$_POST['assetEqpCheckoutDate'] ,'current_status'=>'checkout','created_on' =>date('Y-m-d') ,'modified_on' =>date('Y-m-d') ));	 	
	    }
	   return Redirect::to('job/job_form/'.$jid.'/'.$jnum.''); 
	}

	/*
	* displayJobTasks
	*/
	public function displayJobTasks(){
		$id = Input::get('id');
		$job_proj_data = DB::table('gpg_job_project_task')->select('*')->where('gpg_job_project_id', '=',$id)->get();
		$table_data = '';
		$table_data .= '<section id="no-more-tables"  style="padding:2px;"><table class="table table-bordered table-striped table-condensed cf"><thead class="cf"><tr><th>Task</th><th>Date</th><th>Electr.(Y/N)</th><th>Projected</th><th>Actual</th><th>Completed</th><th>Action</th></tr></thead><tbody class="cf">';
		if (!empty($job_proj_data)) {
			foreach ($job_proj_data as $key => $value) {
				$table_data .= '<tr><td data-title="Task:">'.$value->project_task.'</td><td data-title="Proj. Date:">'.$value->project_date.'</td><td data-title="Electrician:">'.($value->electrician=='1'?'Yes':'No').'</td><td data-title="Projected:">'.$value->projected.'</td><td data-title="Actual:">'.$value->actual.'</td><td data-title="Completed:"><input type="checkbox" cmp_id="'.$value->id.'" name="completed_task_status" value="'.$value->completed.'" '.($value->completed=="1"?"checked":"").'></td><td data-title="Action:"><button class="btn btn-danger btn-xs" name="del_job_task" del_id="'.$value->id.'"><i class="fa fa-trash-o"></i></button><button class="btn btn-success btn-xs" name="up_job_task" up_id="'.$value->id.'"><i class="fa fa-check"></i></button></td></tr>';
			}
			$table_data .= '</tbody></table></section>';
			return $table_data;
		}
		else
			echo 'No, data found!';
	}
	
	/*
	* createJobProjectTask
	*/
	public function createJobProjectTask(){
		$job_proj_id = Input::get('job_proj_id');
		$actual = Input::get('actual');
		$proj_task = Input::get('proj_task');
		$electrician = Input::get('electrician');
		$task_status = Input::get('task_status');
		$projected = Input::get('projected');
		$completed_task = Input::get('completed_task');
		DB::table('gpg_job_project_task')->insert(array('gpg_job_project_id'=>$job_proj_id,'project_task'=>$proj_task,'project_date'=>'0000-00-00','electrician'=>$electrician,'task_status'=>($task_status==0?'N':'Y'),'projected'=>$projected,'actual'=>$actual,'completed'=>$completed_task,'modified_on'=>date('Y-m-d'),'created_on'=>date('Y-m-d')));
		return 1;
	}

	/*
	* insertUpdateClearServiceJobs
	*/
	public function insertUpdateClearServiceJobs(){
		$modules = Generic::modules();
		$file = Input::file('uploadFile');
		$filename = "";
		if (!empty($file)) {
			$file1 = Input::file('uploadFile')->getClientOriginalName();
			$filename = "service_jobs_".rand(99999,10000000)."_".strtotime("now").".".$file1;
			$destinationPath = public_path().'/img/';
			$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);

			$fh = fopen($destinationPath.$filename,'r');
			for ($dt=0; $dt<count($fh); $dt++){
				$tt = explode('\t',$fh[$dt]); 
				if (!empty($tt)) {
				    break;
		   		} 
		  	} 
		  	$titles = explode('\t',$fh[$dt]); 
			$titlesTrue = explode('\t',$fh[$dt+1]); 
			$location = array();
			for ($ti=0; $ti<count($titles); $ti++) { 
				if( ( ($titles[$ti] == "Cleared" && $titlesTrue[$ti] == "True") || ($titles[$ti] == "Cleared" && $titlesTrue[$ti] == "False") )  || $titles[$ti] == "Job#" || $titles[$ti] == "Completed Date" || $titles[$ti] == "Cleared Reason" ){ 
					$location[$titles[$ti]]  = $ti; 
				}
			}
			$queryPart = array();
			if(count($location) > 1){

				for ($i=$dt; $i<count($fh); $i++) { 
		    			$oneField = explode('\t',$fh[$i]);
							if($oneField[$location['Cleared']] == 'True') {
								if($oneField[$location['Completed Date']]){
									$CDate = date('Y-m-d',strtotime($oneField[$location['Completed Date']])); 
								} else {
									$CDate = date('Y-m-d'); 
								}
									$queryPart = array('date_completion'=>$CDate,'complete'=>1, 'cleared_reason'=>$oneField[$location['Cleared Reason']]);
								}
					if (preg_match("/^PM/i",$oneField[$location['Job#']])) {
					   $sp1 = preg_split("/-/",$oneField[$location['Job#']]);
					   $oneField[$location['Job#']] =  "PM".preg_replace("/[^0-9]/","",end($sp1));
					}
					$oneField[$location['Job#']] = preg_replace("/UPS-PM/i","PM",$oneField[$location['Job#']]);
					DB::table('gpg_job')->where('job_num','=',$oneField[$location['Job#']])->update($queryPart);
				}
			}else{
				fclose($fh);
				$params = array('left_menu' => $modules,'success'=>'0');
				return View::make('job.clear_service_jobs', $params);				
			}

			fclose($fh);
			$params = array('left_menu' => $modules,'success'=>'1');
			return View::make('job.clear_service_jobs', $params);
		}
		
		$params = array('left_menu' => $modules,'success'=>'0');
		return View::make('job.clear_service_jobs', $params);
	}
	/*
	* assignTechniciansImport
	*/
	public function assignTechniciansImport(){
		$modules = Generic::modules();
		$file = Input::file('uploadFile');
		$filename = "";
		if (!empty($file)){
			$file1 = Input::file('uploadFile')->getClientOriginalName();
			$filename = "service_jobs_".rand(99999,10000000)."_".strtotime("now").".".$file1;
			$destinationPath = public_path().'/img/';
			$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
			$fileinfo = fopen($destinationPath.$filename,'r');

			for ($dt = 0; $dt < count($fileinfo); $dt++ ) {
				$tt = explode('\t', $fileinfo[$dt]);
	            if (!empty($tt) ) {
	                break;
	            }
        	}
        	$location = array();
        	$titles = explode('\t', $fileinfo[$dt]);
			$titlesTrue = explode('\t', $fileinfo[$dt + 1]);
	        for ( $ti = 0; $ti < count($titles); $ti++ ){
	            $titles[$ti] = str_replace('\n','', str_replace('\r','', $titles[$ti]));
	            if ( $titles[$ti] == "Assignee" || $titles[$ti] == "Job#" ){
	                $location[$titles[$ti]] = $ti;
	            }
	        }
	        if ( count($location) > 1 ){
	            for ( $i = $dt; $i < count($fileinfo); $i++ ) {
	                $oneField = explode('\t', $fileinfo[$i]);
					if ( preg_match("/^PM/i", $oneField[$location['Job#']]) ) {
	                	$sp1 = explode("-", $oneField[$location['Job#']]);
	                    $oneField[$location['Job#']] = "PM" . preg_replace("/[^0-9]/", "", end($sp1));
	                }
	                $oneField[$location['Job#']] = preg_replace("/UPS-PM/i", "PM", $oneField[$location['Job#']]);
	
	                if (isset($oneField[$location['Assignee']]))
		                $TecId = DB::table('gpg_employee')->where('name','=',strtolower($oneField[$location['Assignee']]))->pluck('id');
	                
	                if ( sizeof($tecArray[$oneField[$location['Job#']]]) == 0 )
	                    $tecArray[$oneField[$location['Job#']]] = array( );
	                if ( $TecId ){
	                    $tecArray[$oneField[$location['Job#']]][] = $TecId;
	                }else{
	                    if($oneField[$location['Assignee']] == "Assignee" ||  $oneField[$location['Assignee']] == "Unassigned" || trim($oneField[$location['Assignee']]) == "" ){}
	                    else{
	                        $emp_unavailable[$oneField[$location['Assignee']]][] = trim($oneField[$location['Job#']]);
	                    }
	                }
	            }

	            foreach ( $tecArray as $k => $v ) {
	                $tecIdStr = implode(',', array_unique($v));

	                if ( strlen($tecIdStr) > 0 ){
	                	$res = DB::table('gpg_job')->where('job_num','=',$k)->update(array('technicians'=>$tecIdStr));
	                    $q_all_tech = DB::select(DB::raw("SELECT GROUP_CONCAT(name) as name FROM gpg_employee WHERE id IN  ($tecIdStr)"));
	                    if (!empty($q_all_tech))
		                    $AllTec = $q_all_tech[0]->name;
	                } else {
	                    $res = DB::table('gpg_job')->where('job_num','=',$k)->update(array('technicians'=>''));
	                    $AllTec = "";
	                }
	            }
	        } 
	        $params = array('left_menu' => $modules,'success'=>'1');
			return View::make('job.assign_technicians_imp', $params);
		}
		
		$params = array('left_menu' => $modules,'success'=>'0');
		return View::make('job.assign_technicians_imp', $params);
	}
	
	/*
	* jobCostImport	
	*/
	public function jobCostImport(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			if (isset($_POST['hidden_count']) && !empty($_POST['hidden_count']) && $_POST['hidden_count']>0){
				$SDate  = Input::get('SDate');
				$EDate  = Input::get('EDate');
				if(!empty($SDate) && !empty($EDate)){
					$getCost = DB::select(DB::raw("select job_num from gpg_job_cost where date>= '$SDate' and date<= '$EDate'"));
					if (!empty($getCost)){
						foreach ($getCost as $key => $getCostRow) {
							$labCost = DB::table('gpg_timesheet_detail')->where('job_num','=',$getCostRow->job_num)->groupBy('job_num')->sum('total_wage');		
							DB::table('gpg_job')->where('job_num','=',$getCostRow->job_num)->update(array('cost_to_dat'=>$labCost));
						}
					}
					DB::table('gpg_job_cost')->where('date','>=',$SDate)->where('date','<=',$EDate)->delete();
					$setValue = array();
					for ($i=1; $i<$_POST['hidden_count'] ; $i++) { 
						if (preg_match("/date/i",$_POST['file_field_'.$i])) { 
					 			$setValue += array($_POST['file_field_'.$i]=>'');	
					 	} elseif (preg_match("/amount/i",$_POST['file_field_'.$i])) {
								$setValue += array($_POST['file_field_'.$i]=>str_replace(",","",str_replace("\$","",($_POST['file_field_'.$i])))); 
						} elseif (preg_match("/clr/i",$_POST['file_field_'.$i])) {
							$setValue += array($_POST['file_field_'.$i]=>(($_POST['file_field_'.$i])!=""?1:''));	 
						}elseif ($_POST['file_field_'.$i]=="name" || $_POST['file_field_'.$i]=="num"){ 
						    $jobNum = preg_split("/:/",($_POST['file_field_'.$i]));
						   	$setValue += array('job_num'=>(preg_match("/RN/i",$jobNum[count($jobNum)-1])?(str_replace("RN","",$jobNum[count($jobNum)-1])>=25000?str_replace("RN","RNT",$jobNum[count($jobNum)-1]):$jobNum[count($jobNum)-1]):$jobNum[count($jobNum)-1]));	
						} 
					}
					
					if (!empty($setValue)){
						set_time_limit(0);	
						$setValue = $setValue + array('modified_on'=>date('Y-m-d'),'created_on'=>date('Y-m-d'));
						DB::table('gpg_job_cost')->insert($setValue);

						$query = DB::select(DB::raw("select sum(amount)as job_cost,job_num  from gpg_job_cost group by job_num"));
						foreach ($query as $key => $row) {
							$labCost = DB::table('gpg_timesheet_detail')->where('job_num','=',$row->job_num)->groupBy('job_num')->sum('total_wage');
							DB::table('gpg_job')->where('job_num','=',$row->job_num)->update(array('cost_to_dat'=>round(($row->job_cost+$labCost),2)));
						}
					}
					return Redirect::to('job/job_cost_opt')->withSuccess('Records deleted & Job Costs Updated Successfully');
				}
			}else{

				$SDate = Input::get('SDate');
				$EDate = Input::get('EDate');
				$file = Input::file('uploadFile');
				$filename = "";
				if (!empty($file)) {
					$file1 = Input::file('uploadFile')->getClientOriginalName();
					$filename = "jobcost_opt_".rand(99999,10000000)."_".strtotime("now").".".$file1;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
				}
				$fh = fopen($destinationPath.$filename,'r');
				$jcostopt = trim(fgets($fh));
				$jcostopt_arr = explode("\t", $jcostopt);
				fclose($fh);
				$DBFields = array(''=>'Select Option',"type"=>"Type","date"=>"Date","num"=>"Num","name"=>"Name","source_name"=>"Source Name","memo"=>"Memo","account"=>"Account","clr"=>"Clr","split"=>"Split","amount"=>"Amount");
				$params = array('left_menu' => $modules,'success'=>'0','step'=>'1','jcostopt_arr'=>$jcostopt_arr,'DBFields'=>$DBFields,'SDate'=>$SDate,'EDate'=>$EDate);
				return View::make('job.job_cost_opt', $params);
			}
		}
		$params = array('left_menu' => $modules,'success'=>'0','step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array());
		return View::make('job.job_cost_opt', $params);
	}

	/*
	* jobDueAmtImpOptAr
	*/
	public function jobDueAmtImpOptAr(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			if (isset($_POST['hidden_count']) && !empty($_POST['hidden_count']) && $_POST['hidden_count']>0){
				$setValue = array();
				for ($i=1; $i<$_POST['hidden_count'] ; $i++) { 
					if (preg_match("/amount/i",$_POST['file_field_'.$i])) { 
				 			$setValue += array($_POST['file_field_'.$i]=>str_replace(",","",str_replace("\$","",($_POST['file_field_'.$i]))));	
				 	} elseif (preg_match("/name/i",$_POST['file_field_'.$i]) || preg_match("/job_num/i",$_POST['file_field_'.$i])) {
							$setValue += array($_POST['file_field_'.$i]=>$_POST['file_field_'.$i]); 
					} 
				}
				if (!empty($setValue)) {
					DB::table('gpg_job_due_amount')->where('report_type','=',1)->delete();
					DB::table('gpg_job_due_amount')->insert($setValue+array('created_on'=>date('Y-m-d')));
				}
				return Redirect::to('job/job_due_amt_imp_opt_ar')->withSuccess('Records deleted & Updated Successfully');
			}else{
				$file = Input::file('uploadFile');
				$filename = "";
				if (!empty($file)) {
					$file1 = Input::file('uploadFile')->getClientOriginalName();
					$filename = "dueAmtAr_".rand(99999,10000000)."_".strtotime("now").".".$file1;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
				}
				$fh = fopen($destinationPath.$filename,'r');
				$jcostopt = trim(fgets($fh));
				$jcostopt_arr = explode("\t", $jcostopt);
				fclose($fh);
				$DBFields = array(''=>'Select Option',"name"=>"Cust","job_num"=>"Job","amount"=>"TOTAL");
				$params = array('left_menu' => $modules,'success'=>'0','step'=>'1','jcostopt_arr'=>$jcostopt_arr,'DBFields'=>$DBFields);
				return View::make('job.job_due_amt_imp_opt_ar', $params);
			}	
		}
		$params = array('left_menu' => $modules,'success'=>'0','step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array());
		return View::make('job.job_due_amt_imp_opt_ar', $params);
	}
	/*
	* jobDueAmtImpOptAp
	*/
	public function jobDueAmtImpOptAp(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			if (isset($_POST['hidden_count']) && !empty($_POST['hidden_count']) && $_POST['hidden_count']>0){
				$setValue = array();
				for ($i=1; $i<$_POST['hidden_count'] ; $i++) { 
					if (preg_match("/date/i",$_POST['file_field_'.$i])) { 
		 				$setValue += array(trim($_POST['file_field_'.$i])=>date('Y-m-d',strtotime(($_POST['file_field_'.$i]))));	
		 			}elseif (preg_match("/last_modified_on/i",$_POST['file_field_'.$i])) { 
		 				$setValue += array(trim($_POST['file_field_'.$i])=>date('Y-m-d',strtotime(($_POST['file_field_'.$i]))));	
		 			}elseif (preg_match("/clr/i",$_POST['file_field_'.$i])) {
						$setValue += array($_POST['file_field_'.$i]=>(($_POST['file_field_'.$i])!=""?1:''));	 
					}elseif (preg_match("/amount/i",$_POST['file_field_'.$i])) { 
				 		$setValue += array($_POST['file_field_'.$i]=>str_replace(",","",str_replace("\$","",($_POST['file_field_'.$i]))));	
				 	}elseif (preg_match("/source_name/i",$_POST['file_field_'.$i]) || preg_match("/job_num/i",$_POST['file_field_'.$i])) {
						$setValue += array($_POST['file_field_'.$i]=>$_POST['file_field_'.$i]); 
					}elseif (preg_match("/name/i",$_POST['file_field_'.$i])){
						$name = $_POST['file_field_'.$i];
						$pos = strpos($name, ":");
						$last_key = 0 ;
						if($pos !== false){
							$exploded_name = explode(":",$name) ;
							$last_key = end(array_keys($exploded_name));
							$job_num = end($exploded_name) ;
							$job_num = 	str_replace("RN","RNT",$job_num) ;
							$complete_name = ""; 
							for($counter=0; $counter < $last_key;$counter++){
								$complete_name .= $exploded_name[$counter] ;
							}//for
							$setValue+=array('name'=>$complete_name,'job_num'=>$job_num);
					    }
					} 
				}
				if (!empty($setValue)){
					DB::table('gpg_job_due_amount')->where('report_type','=',2)->delete();
					DB::table('gpg_job_due_amount')->insert($setValue+array('created_on'=>date('Y-m-d'),'report_type'=>2));
				}
				return Redirect::to('job/job_due_amt_imp_opt_ap')->withSuccess('Records deleted & Updated Successfully');
			}else{
				$file = Input::file('uploadFile');
				$filename = "";
				if (!empty($file)) {
					$file1 = Input::file('uploadFile')->getClientOriginalName();
					$filename = "dueAmtAp_".rand(99999,10000000)."_".strtotime("now").".".$file1;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
				}
				$fh = fopen($destinationPath.$filename,'r');
				$jcostopt = trim(fgets($fh));
				$jcostopt_arr = explode("\t", $jcostopt);
				fclose($fh);
				$DBFields = array(''=>'Select Option',"type"=>"Type","date"=>"Date","last_modified_by"=>"Last modified by","name"=>"Name","num"=>"Num","source_name"=>"Source Name","memo"=>"Memo","class"=>"Class","account"=>"Account","clr"=>"Clr","split"=>"Split","paid"=>"Paid","amount"=>"Amount","last_modified_on"=>"Entered/Last Modified");
				$params = array('left_menu' => $modules,'success'=>'0','step'=>'1','jcostopt_arr'=>$jcostopt_arr,'DBFields'=>$DBFields);
				return View::make('job.job_due_amt_imp_opt_ap', $params);
			}	
		}
		$params = array('left_menu' => $modules,'success'=>'0','step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array());
		return View::make('job.job_due_amt_imp_opt_ap', $params);
	}
	/*
	* contractAmtImpOpt
	*/
	public function contractAmtImpOpt(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			if (isset($_POST['hidden_count']) && !empty($_POST['hidden_count']) && $_POST['hidden_count']>0){
				$destinationPath = Input::get('dest');
				$filename = Input::get('filename');
				$fh = fopen($destinationPath.$filename,'r');
				$opt = fgets($fh);
				$heading = explode('	', $opt);
				while ($opt = fgets($fh)){
					$setValue = array();
					$contractNumber = '';
					$values = explode('	', $opt);
					for ($i=0; $i<count($heading); $i++) { 
						if (preg_match("/amount/i",$heading[$i])) { 
				 			$setValue += array($heading[$i]=>str_replace(",","",str_replace("\$","",($values[$i]))));	
					 	}elseif (preg_match("/contract_number/i",$heading[$i])){
							$setValue += array($heading[$i]=>$values[$i]);
							$contractNumber =  $values[$i];
						} 
					}
					if (!empty($setValue) && !empty($contractNumber)){
						DB::table('gpg_job')->where('contract_number','=',$contractNumber)->where('job_num','LIKE','PM%')->update($setValue);
					}
				}
				return Redirect::to('job/contract_amt_imp_opt')->withSuccess('Records have been Updated Successfully');
			}else{
				$file = Input::file('uploadFile');
				$filename = "";
				if (!empty($file)) {
					$file1 = Input::file('uploadFile')->getClientOriginalName();
					$filename = "contAmtImp_".rand(99999,10000000)."_".strtotime("now").".".$file1;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
				}
				$fh = fopen($destinationPath.$filename,'r');
				$jcostopt = trim(fgets($fh));
				$jcostopt_arr = explode("\t", $jcostopt);
				fclose($fh);
				$DBFields = array(''=>'Select Option',"contract_number"=>"Contract Number","contract_amount"=>"Contract Amount");
				$params = array('left_menu' => $modules,'success'=>'0','step'=>'1','jcostopt_arr'=>$jcostopt_arr,'DBFields'=>$DBFields,'dest'=>$destinationPath,'filename'=>$filename);
				return View::make('job.contract_amt_imp_opt', $params);
			}	
		}
		$params = array('left_menu' => $modules,'success'=>'0','step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array());
		return View::make('job.contract_amt_imp_opt', $params);
	}
	/*
	* salesAmtImpOpt
	*/
	public function salesAmtImpOpt(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			if (isset($_POST['hidden_count']) && !empty($_POST['hidden_count']) && $_POST['hidden_count']>0){
				$destinationPath = Input::get('dest');
				$filename = Input::get('filename');
				$fh = fopen($destinationPath.$filename,'r');
				$opt = fgets($fh);
				$heading = explode('	', $opt);
				while ($opt = fgets($fh)){
					$setValue = array();
					$contractNumber = '';
					$values = explode('	', $opt);
					$job_id = '';	
					$job_invoice_id = '';
					$invoice_date = '';
					$invoice_amount = '';

					for ($i=0; $i<count($heading); $i++) { 
						if (preg_match("/Num/i",$heading[$i])) { 
				 			$jobNum = str_replace(",","",str_replace("\$","",str_replace("\"","",$values[$i]))); 
				 			$jobNum = (preg_match("/RN/i",$jobNum)?(str_replace("RN","",$jobNum)>=25000?str_replace("RN","RNT",$jobNum):$jobNum):$jobNum);
							$job_invoice_id = DB::table('gpg_job_invoice_info')->where('job_num','=',$jobNum)->pluck('id');
						}elseif(preg_match("/Amount/i",$heading[$i])) { 
				 			$invoice_amount = str_replace(",","",str_replace("\$","",str_replace("\"","",$values[$i])));
				 		}elseif(preg_match("/date/i",$heading[$i])) { 
				 			$invoice_date = date('Y-m-d',strtotime(($values[$i])));
				 		} 
				 		if (empty($job_invoice_id) && !empty($jobNum)){
							$job_id = DB::table('gpg_job')->where('job_num','=',$jobNum)->pluck('id');
							DB::table('gpg_job_invoice_info')->insert(array('gpg_job_id'=>$job_id,'job_num'=>$jobNum,'invoice_number'=>$jobNum,'invoice_amount'=>$invoice_amount,'invoice_date'=>$invoice_date,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
						}else{
							if (!empty($jobNum)){
								$job_id = DB::table('gpg_job')->where('job_num','=',$jobNum)->pluck('id');
								DB::table('gpg_job_invoice_info')->where('job_invoice_id','=',$job_invoice_id)->where('job_num','=',$jobNum)->update(array('gpg_job_id'=>$job_id,'job_num'=>$jobNum,'invoice_number'=>$jobNum,'invoice_amount'=>$invoice_amount,'invoice_date'=>$invoice_date,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
							}
						}	
					}
				}
				return Redirect::to('job/sales_amt_imp_opt')->withSuccess('Records have been Updated Successfully');
			}else{
				$file = Input::file('uploadFile');
				$filename = "";
				if (!empty($file)) {
					$file1 = Input::file('uploadFile')->getClientOriginalName();
					$filename = "salesAmtImp_".rand(99999,10000000)."_".strtotime("now").".".$file1;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
				}
				$fh = fopen($destinationPath.$filename,'r');
				$jcostopt = trim(fgets($fh));
				$jcostopt_arr = explode("\t", $jcostopt);
				fclose($fh);
				$DBFields = array(''=>'Select Option','date'=>"Date",'num'=>"Num", 'name'=>"Name" ,'account'=>"Account" , 'debit'=>"Debit", 'credit'=>"Credit",'type'=>"Type",'amount'=>"Amount");
				$params = array('left_menu' => $modules,'success'=>'0','step'=>'1','jcostopt_arr'=>$jcostopt_arr,'DBFields'=>$DBFields,'dest'=>$destinationPath,'filename'=>$filename);
				return View::make('job.sales_amt_imp_opt', $params);
			}	
		}
		$params = array('left_menu' => $modules,'success'=>'0','step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array());
		return View::make('job.sales_amt_imp_opt', $params);
	}
	
	/*
	* linkJobs	
	*/
	public function linkJobs(){
		set_time_limit(0);
		$modules = Generic::modules();
		$bill_only_job_num = Input::get('bill_only_job_num');
    	$TotalLines = Input::get('count_records');
    	$bill_only_job_id = DB::table('gpg_job')->where('job_num','=',$bill_only_job_num)->pluck('id');
    	if (isset($_POST['bill_only_job_num']) && empty($bill_only_job_id))
	    	return Redirect::to('job/link_jobs')->withErrors('Given Job Number, does not exist in database!');
    	if ($bill_only_job_id){	
    		$jobArr = array();
			for ($i=0; $i<$TotalLines; $i++) {
			    	$link_to_job_num = $_REQUEST["link_to_job_num_".$i]; 
					if(isset($link_to_job_num) && !empty($link_to_job_num)){
						array_push($jobArr,$link_to_job_num);
					}
			}
			if(!empty($jobArr)){
				$arr = DB::table('gpg_job')->whereIn('job_num',$jobArr)->update(array('link_job_num'=>$bill_only_job_num, 'link_job_id'=>$bill_only_job_id, 'date_completion'=> date('Y-m-d'), 'complete'=> '1'));
				$InvAmt = DB::table('gpg_job_invoice_info')->whereIn('job_num',$jobArr)->sum('invoice_amount');
				DB::table('gpg_job')->where('job_num','=',$bill_only_job_num)->update(array('contract_amount'=> $InvAmt));
				return Redirect::to('job/link_jobs')->withSuccess('Linked successfully');
			}
		}

		$params = array('left_menu' => $modules);
		return View::make('job.link_jobs', $params);
	}
	
	/*
	* jobExportOpt
	*/
	public function jobExportOpt(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		$modules = Generic::modules();
		$job_type_arr = DB::table('gpg_job_type')->select('id','name')->lists('name','id');
		$job_type_arr = array(''=>'Select Job Category')+$job_type_arr;
		$params = array('left_menu' => $modules,'job_type_arr'=>$job_type_arr);
		if (isset($_POST) && !empty($_POST)){
			set_time_limit(0);	
			ini_set('memory_limit', '-1');
		    Excel::create('New file', function($excel) {
		    $excel->sheet('JobsExportFile', function($sheet) {
		    $sheet->setStyle(array(
			    'td' => array(
			        'background' => 'blue'
			    )
			));	
		    $SDate = Input::get('SDate');
		    $EDate = Input::get('EDate');
		    $typeId = Input::get('typeId');
		    $queryPart = '';
		    if ($typeId==4) {
				if ($SDate!="" and $EDate!="") 
					$queryPart .= " AND schedule_date >= '".date('Y-m-d',strtotime($SDate))."' AND schedule_date <= '".date('Y-m-d',strtotime($EDate))."' ";
 				elseif ($SDate!="") 
 					$queryPart .= " AND schedule_date = '".date('Y-m-d',strtotime($SDate))."'";
   			}else {
	    		if ($SDate!="" and $EDate!="") 
	    			$queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($SDate))."' AND created_on <= '".date('Y-m-d',strtotime($EDate))."' ";
 				elseif ($SDate!="") 
 					$queryPart .= " AND created_on = '".date('Y-m-d',strtotime($SDate))."'";
  			}
    		if ($typeId!="") 
    			$queryPart .= " AND GPG_job_type_id = '$typeId'";
					$typeName = DB::table('gpg_job_type')->where('id','=',$typeId)->pluck('name');
					$jobRecords = DB::select(DB::raw("select *,(select name from gpg_job_type where id = GPG_job_type_id) as job_type, (select name from gpg_customer where id = gpg_customer_id) as cus_name , (select sum(total_wage) from gpg_timesheet_detail where GPG_job_id = gpg_job.id) as labor_cost, (select sum(time_diff_dec) from gpg_timesheet_detail where GPG_job_id = gpg_job.id) as labor_hour,(select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num) as material_cost from gpg_job where 1 $queryPart"));
					$jobRecord = array();
					foreach ($jobRecords as $key2 => $value2){
						foreach ($value2 as $key => $value){
							$temp_arr[$key] = $value;
						}
						$jobRecord[] = $temp_arr; 
					}
					$params = array('jobRecord'=>$jobRecord);
				    $sheet->loadView('job.jobExport',$params);
				});
			})->export('xls');
		}	


		return View::make('job.job_export_opt', $params);
	}
	/*
	* jobCostCheck
	*/
	public function jobCostCheck(){
		$modules = Generic::modules();
		$allInputs = Input::except('_token');
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getJobCostPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);

		$params = array('left_menu' => $modules,'query_data'=>$query_data,'allInputs'=>$allInputs,'inv_amt_date'=>$data->inv_amt_date,'total_sum'=>$data->total_sum);
		return View::make('job.job_cost_check', $params);
	}
	public function getJobCostPage($page = 1, $limit = null){
		$results = new \StdClass;
		$results->page = $page;
		$results->totalItems = 0;
		$results->total_sum = 0;
		$results->items = array();
		$results->inv_amt_date = array();
		
		# set offset (for excel export offset will not apply)
	  	$limitOffset = '';
	  	if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);

		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
	  	}


		$SDate = Input::get('SDate'); 
		$EDate = Input::get('EDate');
		$not_exist = Input::get('not_exist');
		$not_inv = Input::get('not_inv');
		$out_of_range = Input::get('out_of_range');	
		if (empty($SDate)) 
			$SDate = '01/01/'.date('Y',strtotime(date('Y-m-d',time()).' -1 Year'));
		if (empty($EDate)) 
			$EDate = '12/31/'.date('Y',strtotime(date('Y-m-d',time()).' -1 Year'));

		$Filter = Input::get('Filter');
		$FVal = Input::get('FVal');
		$jobType = Input::get('jobType');
		$DSQL = "";
		$DQ2 = " order by gpg_job.job_num,gpg_job_cost.job_num ";
		if ($SDate!="" || $EDate!="") {
  		    if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND DATE_FORMAT(gpg_job_cost.date,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (gpg_job_cost.date >= '".date('Y-m-d',strtotime($SDate))."' 
			            AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($EDate))."')" ; 
			}
		}
		if ($not_exist==1) 
			$DSQL.= " AND gpg_job.job_num IS NULL" ;    
		if ($not_inv==1) 
			$DSQL.= " AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1) " ;    
		if ($out_of_range==1) 
			$DSQL.= " AND if((select id FROM gpg_job_invoice_info WHERE gpg_job_id = gpg_job.id AND invoice_date >= '".date('Y-m-d',strtotime($SDate))."' 
			            AND invoice_date <= '".date('Y-m-d',strtotime($EDate))."' LIMIT 0,1)>0,0,1) AND if((select id FROM gpg_job_invoice_info WHERE gpg_job_id = gpg_job.id LIMIT 0,1)>0,1,0) " ;    

		if ($Filter!="" && ($FVal!="" || $jobType!="")) {
		   if ($Filter !="jobType") 
		   	$DSQL.= " AND $Filter like '%$FVal%'"; 
		   elseif ($Filter =="jobType" && $jobType=="gpg") 
		   	$DSQL.= " AND gpg_job_cost.job_num like 'GPG%'"; 
		   elseif ($Filter =="jobType" && $jobType=="service") 
		   	$DSQL.= " AND gpg_job_cost.job_num not like 'GPG%'"; 
		}

		$t_rec = DB::select(DB::raw("select gpg_job_cost.id as job_cost_id from gpg_job_cost LEFT JOIN gpg_job on (gpg_job_cost.job_num=gpg_job.job_num) WHERE 1 $DSQL"));	  
		if (count($t_rec) > 0)
			$results->totalItems = count($t_rec);
		else
			$results->totalItems = 0;

		$result = DB::select(DB::raw("select gpg_job_cost.job_num as cost_job_num, gpg_job_cost.date as cost_date, 
					  gpg_job_cost.amount as cost_amount, gpg_job.job_num as job_job_num, gpg_job.task as job_task,(select name from gpg_customer where id = gpg_job.GPG_customer_id) as job_customer, gpg_job.id as job_id 
					  FROM gpg_job_cost LEFT JOIN gpg_job on (gpg_job_cost.job_num=gpg_job.job_num) WHERE 1 $DSQL $DQ2 $limitOffset"));
		$inv_date_amt = array();
		foreach ($result as $key => $value) {
			$inv_qry = DB::select(DB::raw("select invoice_date, invoice_amount, if ((invoice_date >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
	            AND invoice_date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'),0,1) as not_in_range FROM gpg_job_invoice_info WHERE gpg_job_id = '".$value->job_id."'"));
			if (isset($inv_qry[0])){
				$inv_date_amt[$value->job_id] = array('invoice_date'=>$inv_qry[0]->invoice_date,'invoice_amount'=>$inv_qry[0]->invoice_amount);	
			}else{
				$inv_date_amt[$value->job_id] = array('invoice_date'=>'','invoice_amount'=>0);
			}		
		}
		$total_sum = DB::select(DB::raw("select sum(gpg_job_cost.amount) as total_amt from gpg_job_cost LEFT JOIN gpg_job on (gpg_job_cost.job_num=gpg_job.job_num) where 1 $DSQL"));
		if(isset($total_sum[0]) and !empty($total_sum[0]->total_amt))
			$results->total_sum = $total_sum[0]->total_amt;
		$results->inv_amt_date = $inv_date_amt;
		$results->items = $result;
		return $results;
	}
	/*
	* jobCostManage
	*/
	public function jobCostManage(){
		$modules = Generic::modules();
		$allInputs = Input::except('_token');
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getJobCostManagePage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);

		$params = array('left_menu' => $modules,'query_data'=>$query_data,'allInputs'=>$allInputs);
		return View::make('job.job_cost_manage', $params);

	}

	public function getJobCostManagePage($page = 1, $limit = null){
		$results = new \StdClass;
		$results->page = $page;
		$results->totalItems = 0;
		$results->items = array();
		
		# set offset (for excel export offset will not apply)
	  	$limitOffset = '';
	  	if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);

		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
	  	}

		$SDate = Input::get('SDate'); 
		$EDate = Input::get('EDate');
		$Filter = Input::get('Filter');
		$FVal = Input::get('FVal');
		$jobType = Input::get('jobType');
		if($Filter=="jobCheck")
		{
			$DSQL = "";
			$DQ2 = " order by a.job_num desc ";
			if ($SDate!="" || $EDate!="") {
			    if ($SDate!="" && $EDate =="") {
				  $DSQL.= " AND DATE_FORMAT(a.date,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
				} elseif ($SDate == "" && $EDate != "") {
				  $DSQL.= " AND a.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
				} elseif ($SDate != "" && $EDate != "") {
				  $DSQL.= " AND (a.date >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
				            AND a.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
				}
			}
		}
		else
		{
			$DSQL = "";
			$DQ2 = " order by date desc ";
			if ($SDate!="" || $EDate!="") {
			    if ($SDate!="" && $EDate =="") {
				  $DSQL.= " AND DATE_FORMAT(date,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
				} elseif ($SDate == "" && $EDate != "") {
				  $DSQL.= " AND date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
				} elseif ($SDate != "" && $EDate != "") {
				  $DSQL.= " AND (date >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
				            AND date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
				}
			}
			if ($Filter!="" && ($FVal!="" || $jobType!="")) {
			   if ($Filter !="jobType") 
			   $DSQL.= " AND $Filter like '%$FVal%'"; 
			   elseif ($Filter =="jobType" && $jobType=="gpg") $DSQL.= " AND job_num like 'GPG%'"; 
			   elseif ($Filter =="jobType" && $jobType=="service") $DSQL.= " AND job_num not like 'GPG%'"; 
		    }
		}

		$t_rec = DB::select(DB::raw("".($Filter=="jobCheck"?"select a.id from gpg_job_cost a left join gpg_job b on (a.job_num = b.job_num) where ifnull(b.job_num,'') = ''":"select id from gpg_job_cost WHERE 1")." $DSQL"));
		if (count($t_rec) > 0)
			$results->totalItems = count($t_rec);
		else
			$results->totalItems = 0;
		$result = DB::select(DB::raw("".($Filter=="jobCheck"?"select a.* from gpg_job_cost a left join gpg_job b on (a.job_num = b.job_num) where ifnull(b.job_num,'') = ''":"select * from gpg_job_cost WHERE 1")." $DSQL $DQ2 $limitOffset"));
		$results->items = $result;
		return $results;
	}

	/*
	* excelJobCostManageExport
	*/
	public function excelJobCostManageExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('JobCostExportFile', function($sheet) {
		    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
			$page = Input::get('page', 1);
	   		$data = $this->getJobCostManagePage($page);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
			$sheet->loadView('job.excelJobCostManageExport',$params);
		  });
		})->export('xls');
	}

	/*
	* SERVICE FIELD WORK LISTING 
	*/
	public function fieldServiceWorkList(){
		$modules = Generic::modules();
		$allInputs = Input::except('_token');
		Input::flash();
		$customers = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$cust_arr = array(''=>'ALL');
		foreach ($customers as $key => $value)
				$cust_arr[$value->id] = $value->name;
		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		$jobTypes = DB::table('gpg_job')->select('elec_job_type')->where('elec_job_type','!=','')->distinct()->orderBy('elec_job_type')->get();
		$jobtype_arr = array(''=>'Select Job Type');
		foreach ($jobTypes as $key => $value)
				$jobtype_arr[$value->elec_job_type] = preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", " ", $value->elec_job_type);	
		$quote_op_names = DB::select(DB::raw("SELECT DISTINCT(REPLACE(opportunity_name,'\"','')) as opp_name,opportunity_name FROM gpg_sales_tracking,gpg_sales_tracking_field_service_work WHERE gpg_sales_tracking_field_service_work.gpg_sales_tracking_id = gpg_sales_tracking.id ORDER BY opportunity_name"));
		$quote_op_arr = array(''=>'ALL');
		foreach ($quote_op_names as $key => $value) {
			$quote_op_arr[$value->opp_name] = $value->opportunity_name;
		}
		$page = Input::get('page', 1);
   		$data = $this->getfieldServicePage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);

		$params = array('left_menu' => $modules,'query_data'=>$query_data,'allInputs'=>$allInputs,'cust_arr'=>$cust_arr,'salesp_arr'=>$salesp_arr,'jobtype_arr'=>$jobtype_arr,'quote_op_arr'=>$quote_op_arr);
		return View::make('job.field_service_work_list', $params);
	}
	public function getfieldServicePage($page = 1, $limit = null){
		$results = new \StdClass;
		$results->page = $page;
		# set offset (for excel export offset will not apply)
		$limitOffset = '';
		if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);

		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}  
		$results->totalItems = 0;
		$results->items = array();

		$ignoreCostDate =  Input::get("ignoreCostDate");
		$ignoreInvoiceDate =  Input::get("ignoreInvoiceDate");
		$SDate2 =  Input::get("SDate2");
		$EDate2 =  Input::get("EDate2");
		$JobWonSDate =  Input::get("JobWonSDate");
		$JobWonEDate =  Input::get("JobWonEDate");
		$InvoiceSDate =  Input::get("InvoiceSDate");
		$InvoiceEDate =  Input::get("InvoiceEDate");
		$optJobNumber = Input::get("optJobNumber");
		$optAttJobNumber = Input::get("optAttJobNumber");
		$optEmployee = Input::get("optEmployee");
		$optCustomer = Input::get("optCustomer");
		$optStatus = Input::get("optStatus");
		$optJobStatus = Input::get("optJobStatus");
		$optSort = Input::get("optSort");
		$optAttachedJob = Input::get("optAttachedJob");
		$optOpportunity = Input::get("optOpportunity"); 
		 /********* New Defined**********/
		$queryPart ="";
		$queryPartSort="";
		$fg ="";
		$queryPartInvoice ="";
		$queryPartLaborCost ="";
		$queryPartMaterialCost ="";
		/************/
		if ($SDate2!="" and $EDate2!=""){
			$queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($SDate2))." 00:00:00' AND created_on <= '".date('Y-m-d',strtotime($EDate2))." 23:59:59' ";
		}
		elseif ($SDate2!="") 
			$queryPart .= " AND date_format(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate2))."'";
		if ($SDate2!="" and $EDate2!=""){ 
			$queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($SDate2))." 00:00:00' AND created_on <= '".date('Y-m-d',strtotime($EDate2))." 23:59:59' ";
			if($ignoreCostDate==''){
				$queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($SDate2))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($EDate2))."' ";
			 	$queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($SDate2))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($EDate2))."' ";
			}
			if($ignoreInvoiceDate=='' and $InvoiceSDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($SDate2))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($EDate2))."' ";
			}
		}
		elseif ($SDate2!=""){
			$queryPart .= " AND date_format(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate2))."'";
		    if($ignoreCostDate==''){
				 $queryPartMaterialCost=" AND gpg_job_cost.date = '".date('Y-m-d',strtotime($SDate2))."' ";
				 $queryPartLaborCost=" AND gpg_timesheet.date = '".date('Y-m-d',strtotime($SDate2))."' ";
		 	} 
		  	if($ignoreInvoiceDate=='' and $InvoiceSDate==''){
				 $queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($SDate2))."' ";
		 	} 
		} 
		if ($InvoiceSDate!="" and $InvoiceEDate!=""){ 	
			$queryPart .= " AND GPG_attach_job_id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= GPG_attach_job_id AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND  gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."'  order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
			if($ignoreCostDate=='' and $SDate==''){
			 $queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
			 $queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
			}
		 	if($ignoreInvoiceDate==''){
			  $queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
		 	}
		}
		elseif ($InvoiceSDate!=""){ 	
			 $queryPart .= " AND GPG_attach_job_id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= GPG_attach_job_id AND  gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
			if($ignoreCostDate=='' and $SDate==''){
			 $queryPartMaterialCost=" AND gpg_job_cost.date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
			 $queryPartLaborCost=" AND gpg_timesheet.date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
			} 
			if($ignoreInvoiceDate==''){
			 	$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
		 	} 
		} 
		if ($JobWonSDate!="" and $JobWonEDate!="") 
			$queryPart .= " AND date_job_won >= '".date('Y-m-d',strtotime($JobWonSDate))."' AND date_job_won <= '".date('Y-m-d',strtotime($JobWonEDate))."' ";
		elseif ($JobWonSDate!="") 
			$queryPart .= " AND date_job_won = '".date('Y-m-d',strtotime($JobWonSDate))."'";
		if ($optJobNumber!="") 
			$queryPart .= " AND job_num = '".$optJobNumber."'";
		if ($optAttJobNumber!="") 
			$queryPart .= " AND gpg_attach_job_num = '".$optAttJobNumber."'";
		if ($optEmployee!="") 
			$queryPart .= " AND gpg_employee_id = '$optEmployee' ";   
		if ($optCustomer!="") 
			$queryPart .= " AND gpg_customer_id = '$optCustomer' ";
		if ($optStatus!="") 
			$queryPart .= " AND field_service_work_status = '$optStatus' ";
		if ($optJobStatus=="completed") 
			$queryPart .= " AND (select id from gpg_job where  complete = '1' and id = GPG_attach_job_id limit 0,1)";
		if ($optJobStatus=="notcompleted") 
			$queryPart .= " AND (select id from gpg_job where  complete = '0' and id = GPG_attach_job_id limit 0,1)";
		if ($optJobStatus=="invoiced") 
			$queryPart .= " AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = GPG_attach_job_id  limit 0,1) ";   
		if ($optJobStatus=="not_invoiced") 
			$queryPart .= "AND ifnull(GPG_attach_job_id,0) > 0 AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = GPG_attach_job_id  limit 0,1)>0,0,1) ";   
		if ($optJobStatus=="comp_inv") 
			$queryPart .= "AND ((select id from gpg_job where  complete = '1' and id = GPG_attach_job_id limit 0,1) AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = GPG_attach_job_id  limit 0,1)) ";
		if ($optJobStatus=="not_comp_inv") 
			$queryPart .= " AND ((select id from gpg_job where  complete = '0' and id = GPG_attach_job_id limit 0,1) AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = GPG_attach_job_id  limit 0,1)) ";
		if ($optJobStatus=="completed_not_invoiced") 
			$queryPart .= " AND ((select id from gpg_job where  complete = '1' and id = GPG_attach_job_id limit 0,1) AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = GPG_attach_job_id  limit 0,1)>0,0,1)) ";
		if ($optAttachedJob=="attachedJobs") 
			$queryPart .= " AND ifnull(GPG_attach_job_id,0)<>0";
		if ($optAttachedJob=="noAttachedJobs") 
			$queryPart .= " AND ifnull(GPG_attach_job_id,0)=0";
		if($optOpportunity!=""){
			$queryPart .= " AND id IN (SELECT fs.gpg_field_service_work_id FROM gpg_sales_tracking_field_service_work fs, gpg_sales_tracking gs WHERE fs.gpg_sales_tracking_id = gs.id AND REPLACE(gs.opportunity_name,'\"','') LIKE '".$optOpportunity."')";
		}
		if ($optSort=="")  
			$queryPartSort .= " order by created_on desc";
		if($optSort=="customerAndDate")  
			$queryPartSort .= " order by customer,created_on desc";
		if($optSort=="salespersonAndDate")  
			$queryPartSort .= " order by salesPerson,created_on desc";

		$qryCount = DB::select(DB::raw("select count(id) as total_count from gpg_field_service_work where 1 $queryPart"));
		if (!empty($qryCount) && isset($qryCount[0]->total_count)) {
			$results->totalItems = $qryCount[0]->total_count;
		}
		$getFieldServiceWork = DB::select(DB::raw("select *,(select name from gpg_customer where id = GPG_customer_id) as customer,(select name from gpg_employee where id = GPG_employee_id) as salesPerson , (select location from gpg_consum_contract_equipment where id = gpg_consum_contract_equipment_id) as eqp_location from gpg_field_service_work where 1 $queryPart $queryPartSort $limitOffset"));
		$getRs = array();
		foreach ($getFieldServiceWork as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				$temp_arr[$key] = $value;
				if($key == 'id'){
					$attachLeadId = '-';
					$attachLeadId = DB::table('gpg_sales_tracking_field_service_work')->where('gpg_field_service_work_id','=',$value)->pluck('gpg_sales_tracking_id');
					$temp_arr['attachLeadId'] = $attachLeadId;
					$opportunity_name = '-';
					if (!empty($attachLeadId))
						$opportunity_name = DB::table('gpg_sales_tracking')->where('id','=',$attachLeadId)->pluck('opportunity_name');
					$temp_arr['opportunity_name'] = $opportunity_name;
					$fr0 = DB::select(DB::raw("select sum(other_charge_cost_price*other_charge_qty) as sum_fr from gpg_field_service_work_other where other_charge_description='Freight' and gpg_field_service_work_id = '".$value."'"));
					if (!empty($fr0))
						$temp_arr['freight'] = $fr0[0]->sum_fr;
					else
						$temp_arr['freight'] = '-';

					$ml0 = DB::select(DB::raw("select sum(other_charge_cost_price*other_charge_qty) as sum_ml from gpg_field_service_work_other where other_charge_description='Mileage' and gpg_field_service_work_id = '".$value."'"));
					if (!empty($ml0))
						$temp_arr['mileage'] = $ml0[0]->sum_ml;
					else
						$temp_arr['mileage'] = '-';
					$sums0 = DB::select(DB::raw("SELECT SUM(shop+labor+lbt+ot+sub_con) as sums_sum FROM gpg_field_service_work_labor WHERE gpg_field_service_work_id = '".$value."' AND TYPE = 'A' ORDER BY id asc"));
					if (!empty($sums0))
						$temp_arr['sums_sum'] = $sums0[0]->sums_sum;
					else
						$temp_arr['sums_sum'] = '-';					
				}
				if($key == 'GPG_attach_job_num'){
					if (!empty($value)){
						$attachJobQ = DB::select(DB::raw("select id,job_num,tax_amount,(select concat(invoice_number,'#~#',sum(invoice_amount),'#~#',invoice_date,'#~#',sum(tax_amount),'#~#',count(id)) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_data,(select sum(total_wage) from gpg_timesheet_detail , gpg_timesheet where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num $queryPartLaborCost) as labor_cost , (select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost) as material_cost,(SELECT sales_commission FROM gpg_employee_commission WHERE gpg_employee_commission.gpg_employee_id = gpg_job.GPG_employee_id and gpg_employee_commission.start_date <= DATE(gpg_job.created_on) order by start_date desc limit 0,1) as sales_commission from gpg_job where job_num =  '".$value."' "));
						$attachJobQuery = array();
						foreach ($attachJobQ as $key => $value){
							$attachJobQuery = (array)$value;
							foreach ($attachJobQuery as $key3 => $value3) {
								if ($key3 == 'id'){
									$rest = DB::select(DB::raw("select comm_date,sum(ifnull(comm_paid,0)) as amt,count(id) as cnt from gpg_job_commission WHERE gpg_job_id = '".$value3."' group by gpg_job_id order by created_on desc"));
									if ($rest != ''){
										foreach ($rest as $key4 => $value4) {
											$temp_arr['commData'] = (array)$value4;		
										}
									}
									else
										$temp_arr['commData'] = '-';
								}
							}
						}
					}
					else
						$attachJobQuery = '-';
					
					$temp_arr['attachJobRes'] = $attachJobQuery;
					$time_diff_dec = '';
					//$time_diff_dec = DB::select(DB::raw("SELECT count(time_diff_dec) as time_diff_dec FROM gpg_timesheet_detail WHERE job_num = '".$value."'"));
					if ($time_diff_dec != '')
						$temp_arr['time_diff_sum'] = $time_diff_dec[0]->time_diff_dec;
					else
						$temp_arr['time_diff_sum'] = '-';
				}
			}
			$getRs[] = $temp_arr;
		}
		/*echo "<pre>";
		print_r($getRs);
		die();*/
		$results->items = $getRs;
		return $results;
	}

	/*
	* deleteEquipHistory	
	*/
	public function deleteEquipHistory(){
		$id = Input::get('id');
		DB::table('gpg_asset_equipment_history')->where('id', '=',$id)->delete();
		return 1;
	}

	/*
	* updateJobProjectTask
	*/
	public function updateJobProjectTask(){
		$id = Input::get('id');
		$job_id = Input::get('job_id');
		$job_num = Input::get('job_num');
		DB::table('gpg_job_project_task')->where('id', '=',$id)->update(array('completed' =>1));
		return Redirect::to('job/job_form/'.$job_id.'/'.$job_num.'');
	}
	
	/*
	* deleteJobTask
	*/
	public function deleteJobTask(){
		$id = Input::get('id');
		$job_id = Input::get('job_id');
		$job_num = Input::get('job_num');
		DB::table('gpg_job_project')->where('id', '=',$id)->delete();
		return Redirect::to('job/job_form/'.$job_id.'/'.$job_num.'');
	}
	/*
	*deleteJobProjTasks
	*/
	public function deleteJobProjTasks($id){
		DB::table('gpg_job_project')->where('id','=',$id)->delete();
		DB::table('gpg_job_project_task')->where('gpg_job_project_id','=',$id)->delete();
		return Redirect::to('job/job_project/'.$job_id.'/'.$job_num.'')->withSuccess('Record deleted Successfully');
	}

	/*
	* deleteJobProjectTask
	*/
	public function deleteJobProjectTask(){
		$id = Input::get('id');
		$job_id = Input::get('job_id');
		$job_num = Input::get('job_num');
		DB::table('gpg_job_project_task')->where('id', '=',$id)->delete();
		return Redirect::to('job/job_form/'.$job_id.'/'.$job_num.'');
	}

	/*
	* Delete Service Field Work
	*/
	public function deleteSFW()
	{
		$id = Input::get('id');
		DB::table('gpg_field_service_work')->where('id', '=',$id)->delete();
		return 1;
	}
	/*
	* getFSWFiles
	*/
	public function getFSWFiles(){
		$conCatStr = '';
		$colcount=1;
		$id = Input::get('id');
		$job_num = Input::get('num');
		$fsw_files = DB::select(DB::raw("select * from gpg_field_service_work_attachment where gpg_field_service_work_id = '$id'"));
		if (!empty($fsw_files)){
			foreach($fsw_files as $key=>$row){
        	    $conCatStr .='<tr><td>'.$colcount++.'</td><td>'.$row->displayname.'</td><td><a class="btn btn-danger btn-xs" id="'.$row->id.'" name="del_fsw_file">Delete</a><a class="btn btn-success btn-xs" id="'.$row->id.'" name="dld_fsw_file">Download</a></td></tr>';
			}
    	}
    	return $conCatStr;
	}

	/*
	* manageFSWFiles
	*/
	public function manageFSWFiles(){
		if (!empty($_POST['fjob_id'])){
			$job_id = Input::get('fjob_id');
			$job_num = Input::get('fjob_num');
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
				 	$filename = "fsw_job_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = $file->move($destinationPath, $filename);
					//insert into db
					DB::table('gpg_field_service_work_attachment')->insert(array('gpg_field_service_work_id' =>$job_id,'filename'=>$filename ,'displayname'=>$file->getClientOriginalName()));		
				}
			}
		}
		return Redirect::to('job/field_service_work_list');
	}
	
	/*
	* deleteFSWFiles
	*/
	public function deleteFSWFiles(){
		$id = Input::get('id');
		DB::table('gpg_field_service_work_attachment')->where('id', '=',$id)->delete();
		return 1;
	}
	/*
	* workOrderFrm
	*/
	public function workOrderFrm($job_id,$job_num){
		$jobRecord = DB::table('gpg_field_service_work')->select('*')->where('id','=',$job_id)->get();
		$workOrderTblRow = array();
		$componentRow = array();
		$materialRow = array();
		foreach ($jobRecord as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				if ($key == 'id'){
					$workOrderTblRow['customer_drop_down'] = DB::table('gpg_customer')->select('id','name')->lists('name','id');
					
					$componentQuery = DB::select(DB::raw("select *,(select name from gpg_field_component_type where id = gpg_field_service_work_component.component_id) as component,(select part_number from gpg_field_component where id = gpg_field_service_work_component.part_id) as partNumber from gpg_field_service_work_component where gpg_field_service_work_id = '".$value."' order by created_on desc "));
					foreach ($componentQuery as $key => $value5) {
						$componentRow[] = (array)$value5;
					}
					$marerialQuery = DB::select(DB::raw("select *,(select name from gpg_field_material_type where id = gpg_field_service_work_material.material_id) as material,(select part_number from gpg_field_material where id = gpg_field_service_work_material.part_id) as partNumber from gpg_field_service_work_material where gpg_field_service_work_id = '".$value."' order by created_on desc"));
					foreach ($marerialQuery as $key => $value6) {
						$materialRow[] = (array)$value6;
					}
				}
				if ($key == 'GPG_customer_id') {
					$workOrderTblRow['locationNameId'] = DB::table('gpg_consum_contract_equipment')->where('gpg_customer_id','=',$value)->lists('location','id');
					$workOrderTblRow['salesPerson_drop_down'] = DB::table('gpg_employee')->select('id','name')->lists('name','id');
				}
				if ($key == 'gpg_consum_contract_equipment_id'){
					if (!empty($value))
						$eqpQuery = DB::table('gpg_consum_contract_equipment')->select('*')->where('id','=',$value)->get();
					else
						$eqpQuery = array();
					$eqpArr = array();
					foreach ($eqpQuery as $key3 => $value3) {
						$eqpArr = (array)$value3;
					}
					$workOrderTblRow['consumContractEqpTblRow'] = $eqpArr;
				}
				$workOrderTblRow[$key] = $value;	
			}
		}
		$qr = DB::table('gpg_settings')->where('name','LIKE','scope_template_%')->get();
		$sett_arr = array();
		$sett_arr2 = array();
		foreach ($qr as $key => $ar) {
			$dat = explode("##@##",$ar->value);
			$sett_arr[$ar->id] = $dat[0];
			$sett_arr2[$ar->id] = $dat[1];
		}
		$zone_index_arr = DB::table('gpg_settings')->where('name','LIKE','_zone_index%')->lists('value','id');
		$params = array('workOrderTblRow'=>$workOrderTblRow,'job_id'=>$job_id,'job_num'=>$job_num,'sett_arr'=>$sett_arr,'sett_arr2'=>$sett_arr2,'componentRows'=>$componentRow,'materialRows'=>$materialRow,'zone_index_arr'=>$zone_index_arr);
		return View::make('job.work_order_frm', $params);
	}
	/*
	* updateFWS
	*/
	public function updateFWS()
	{
		$gpg_id = Input::get('gpg_id');
		$status = Input::get('status');
		if (!empty($gpg_id) && !empty($status))
			DB::table('gpg_job')->where('id','=',$gpg_id)->update(array('fws_status'=>$status));
		return 1;
	}

	/*
	* updateFSWFrm
	*/
	public function updateFSWFrm(){
		$locationName = '';
		$work_order_insert = '';
		$field_service_work_id = Input::get("job_id");
		$consum_contract_equipment_id = Input::get("locationNameId");
		$jobNum = Input::get("job_num");
		$customerBillto = Input::get("customerBillto");
		$GPG_attach_job_id = '';
		if(Input::get("attachJobNum") != ""){
			$GPG_attach_job_id = DB::table('gpg_job')->where('job_num','=',Input::get('attachJobNum'))->pluck('id');
		}
		$jobFields = array("GPG_customer_id"=>"customerBillto","billing_contact_name"=>"billingContactName","main_contact_name"=>"mainContactName","main_contact_phone"=>"mainContactPhone","cell"=>"cell","job_site_contact"=>"siteContactName","task"=>"scopeOfWork","eng_make"=>"engMake","eng_model"=>"engModel","eng_serial"=>"engSerial","eng_spec"=>"engSpec","qty_techs"=>"qtyofTechs","weekend_work"=>"weekendWork","qty_helpers"=>"qtyofHelpers","after_hours_work"=>"afterHoursWork","skill_level_tech"=>"skillofTech","tech_desired"=>"TechDesired","tech_days"=>"multiJobDays","multiday_job"=>"multiJob","GPG_employee_id"=>"salePersonId","schedule_date"=>"scheduleDate","job_check"=>"jobCheck","po_number"=>"poNumber","zone_index_id"=>"_zone_index_id","work_order_info"=>"miscInfo","special_billing_ins"=>"specBillingIns","mpower_quote_number"=>"mPowereQuoteNumber","GPG_attach_job_num"=>"attachJobNum");	
	    $cusFields = array("address"=>"cusAddress1","address2"=>"cusAddress2","city"=>"cusCity","state"=>"cusState","zipcode"=>"cusZip","phone_no"=>"cusPhone","attn"=>"cusAtt","email_add"=>"cusEmail");
	    $eqpFields = array("address1"=>"_address1","address2"=>"_address2","city"=>"_city","state"=>"_state","zip"=>"_zip","phone"=>"_phone","make"=>"_make","model"=>"_model","serial"=>"_serial","spec"=>"_spec","engMake"=>"engMake","engModel"=>"engModel","engSerial"=>"engSerial", "engSpec"=>"engSpec","kw"=>"_genkw");
	    $eqpFieldQuery = array();
	    while (list($key,$value)= each($eqpFields)) {
		   if (preg_match("/date/i",$key)) 
		   	$eqpFieldQuery[$key] = (Input::get($value)!=""?date('Y-m-d',strtotime(Input::get($value))):NULL); 
		   else
		    $eqpFieldQuery[$key] = (Input::get($value)!=""?(Input::get($value)):NULL);
		} 
		if(!empty($locationName)){
			$chkLocation = DB::table('gpg_consum_contract_equipment')->where('gpg_customer_id','=',$customerBillto)->where('location','=',Input::get('locationNameId'))->pluck('id');
			if ($chkLocation) {
				$consum_contract_equipment_update = DB::table('gpg_consum_contract_equipment')->where('id','=',$chkLocation)->update($eqpFieldQuery+array('modified_on'=>date('Y-m-d')));
				$consum_contract_equipment_id = $chkLocation;
			}else{
				$maxEqpId = DB::table('gpg_consum_contract_equipment')->max('id')+1;
				$consum_contract_equipment_id = $maxEqpId;
				$consum_contract_equipment_insert = DB::table('gpg_consum_contract_equipment')->insert($eqpFieldQuery+array('id'=>$maxEqpId,'gpg_customer_id'=>$customerBillto,'location'=>$locationName,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
			}
		}
		else if(!empty($consum_contract_equipment_id)){
			$consum_contract_equipment_update = DB::table('gpg_consum_contract_equipment')->where('id','=',$consum_contract_equipment_id)->update($eqpFieldQuery+array('modified_on'=>date('Y-m-d')));
		}
		$workOrderFieldQuery = array();
		while (list($key,$value)= each($jobFields)) {
	      if (preg_match("/date/i",$key)) 
	      	$workOrderFieldQuery[$key] = (Input::get($value)!=""?date('Y-m-d',strtotime(Input::get($value))):NULL); 
		  else 
		  	$workOrderFieldQuery[$key] = (Input::get($value)!=""?(Input::get($value)):NULL);
		}
		if(!empty($field_service_work_id)){
			$work_order_update = DB::table('gpg_field_service_work')->where('id','=',$field_service_work_id)->update($workOrderFieldQuery+array('gpg_consum_contract_equipment_id'=>$consum_contract_equipment_id,'GPG_attach_job_id'=>$GPG_attach_job_id,'modified_on'=>date('Y-m-d')));
		}
		else{
			$maxId = DB::table('gpg_field_service_work')->max('id')+1;
			$field_service_work_id= $maxId;
			$work_order_insert = DB::table('gpg_field_service_work')->insert($workOrderFieldQuery+array('id'=>$maxId,'gpg_consum_contract_equipment_id'=>$consum_contract_equipment_id,'GPG_attach_job_id'=>$GPG_attach_job_id,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
		}
		$cusFieldQuery = array();
		while (list($key,$value)= each($cusFields)) {
		   $cusFieldQuery[$key] = Input::get($value);
		}
		$cus_update = DB::table('gpg_customer')->where('id','=',Input::get('customerBillto'))->update($cusFieldQuery+array('modified_on'=>date('Y-m-d')));
		// EQP NEEDED 
		if ($work_order_update==1 or $work_order_insert==1) {
			if($GPG_attach_job_id){
				$jobUpdate = DB::table('gpg_job')->where('id','=',$GPG_attach_job_id)->update(array('contract_amount'=>Input::get("grand_list_total"),'GPG_employee_id'=>Input::get("salePersonId")));
			}
		}
		return Redirect::to('job/work_order_frm/'.$field_service_work_id.'/'.$jobNum)->withSuccess('Record Updated Successfully');
	}
	/*
	* commissionList
	*/
	public function commissionList(){
		$modules = Generic::modules();
		# flash input fields to re-populate (state maintain) search form		
		Input::flash();		
		$page = Input::get('page', 1);
   		$data = $this->getCommissionByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);

		$customers = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$cust_arr = array(''=>'ALL');
		foreach ($customers as $key => $value)
				$cust_arr[$value->id] = $value->name;

 		$files = DB::table('gpg_sales_tracking_attachment')->select('*')->get();
		$files_arr = array();
		foreach ($files as $key => $value)
				$files_arr[$value->gpg_sales_tracking_id] = wordwrap($value->displayname,1000, "\n",1);	
		
		$technecians = DB::table('gpg_employee')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$tech_arr = array();
		foreach ($technecians as $key3 => $value3)
				$tech_arr[$value3->id] = $value3->name;	

 		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		
		$jobTypes = DB::table('gpg_job')->select('elec_job_type')->where('elec_job_type','!=','')->distinct()->orderBy('elec_job_type')->get();
		$jobtype_arr = array(''=>'Select Job Type');
		foreach ($jobTypes as $key => $value)
				$jobtype_arr[$value->elec_job_type] = preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", " ", $value->elec_job_type);			

		$params = array('left_menu' => $modules, 'query_data'=>$query_data,'totalsRow'=>$data->totals,'cust_arr'=>$cust_arr,'salesp_arr'=>$salesp_arr,'jobtype_arr'=>$jobtype_arr,'tech_arr'=>$tech_arr,'files_arr'=>$files_arr);
		return View::make('job.commission_list', $params);
	}

	public function getCommissionByPage($page = 1, $limit = null){
		$results = new \StdClass;
		$results->page = $page;

		# set offset (for excel export offset will not apply)
		$limitOffset = '';
		if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);

		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}
		  
		$results->totalItems = 0;
		$results->items = array();
		$results->totals = array();
		
		$ignoreCostDate =  Input::get("ignoreCostDate");
		$ignoreInvoiceDate =  Input::get("ignoreInvoiceDate");
		$InvoiceSDate =  Input::get("InvoiceSDate");
		$InvoiceEDate =  Input::get("InvoiceEDate");
		$JobWonSDate =  Input::get("JobWonSDate");
		$JobWonEDate =  Input::get("JobWonEDate");
		$EqpOrderedSDate =  Input::get("EqpOrderedSDate");
		$EqpOrderedEDate =  Input::get("EqpOrderedEDate");
		$EqpEngagedSDate =  Input::get("EqpEngagedSDate");
		$EqpEngagedEDate =  Input::get("EqpEngagedEDate");
		$PermitOrderedSDate =  Input::get("PermitOrderedSDate");
		$PermitOrderedEDate =  Input::get("PermitOrderedEDate");
		$PermitExpectedSDate =  Input::get("PermitExpectedSDate");
		$PermitExpectedEDate =  Input::get("PermitExpectedEDate");
		$CompletedSDate =  Input::get("CompletedSDate");
		$CompletedEDate =  Input::get("CompletedEDate");
		$CreatedSDate =  Input::get("CreatedSDate");
		$CreatedEDate =  Input::get("CreatedEDate");
		$SJobNumber = Input::get("SJobNumber");
		$EJobNumber = Input::get("EJobNumber");
		$InvNumber = Input::get("InvNumber");
		$optEmployee = Input::get("optEmployee");
		$optCustomer = Input::get("optCustomer");
		$optJobStatus = Input::get("optJobStatus");
		$optJobAccount = Input::get("optJobAccount");
		$optJobCostStatus =  Input::get("optJobCostStatus");
		$optJobHaving =  Input::get("optJobHaving");
		$optJobType =  Input::get("optJobType");
		$estimator = Input::get("estimator");
		
		$queryPartInvoice ="";
		$queryPart = "";
		$queryPartLaborCost = "";
		$queryPartMaterialCost = "";
		$fg = "";
		$invAmt = "";
		if ($InvoiceSDate!="" and $InvoiceEDate!="") { 
			$queryPart .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND  gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."'  order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
			if($ignoreCostDate=='' and $CreatedSDate == ''){
				$queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
				$queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
			}
			if($ignoreInvoiceDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
			}
		}
		elseif ($InvoiceSDate!=""){ 
			$queryPart .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND  gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
			if($ignoreCostDate=='' and $CreatedSDate == ''){
				$queryPartMaterialCost=" AND gpg_job_cost.date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
				$queryPartLaborCost=" AND gpg_timesheet.date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
			} 
			if($ignoreInvoiceDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
			} 
		}
		if ($JobWonSDate!="" and $JobWonEDate!="") $queryPart .= " AND date_job_won >= '".date('Y-m-d',strtotime($JobWonSDate))."' AND date_job_won <= '".date('Y-m-d',strtotime($JobWonEDate))."' ";
		elseif ($JobWonSDate!="") $queryPart .= " AND date_job_won = '".date('Y-m-d',strtotime($JobWonSDate))."'";
		if ($EqpOrderedSDate!="" and $EqpOrderedEDate!="") $queryPart .= " AND date_eqp_ordered >= '".date('Y-m-d',strtotime($EqpOrderedSDate))."' AND date_eqp_ordered <= '".date('Y-m-d',strtotime($EqpOrderedEDate))."' ";
		elseif ($EqpOrderedSDate!="") $queryPart .= " AND date_eqp_ordered = '".date('Y-m-d',strtotime($EqpOrderedSDate))."'";
		if ($EqpEngagedSDate!="" and $EqpEngagedEDate!="") $queryPart .= " AND date_eqp_engaged >= '".date('Y-m-d',strtotime($EqpEngagedSDate))."' AND date_eqp_engaged <= '".date('Y-m-d',strtotime($EqpEngagedEDate))."' ";
		elseif ($EqpEngagedSDate!="") $queryPart .= " AND date_eqp_engaged = '".date('Y-m-d',strtotime($EqpEngagedSDate))."'";
		if ($PermitOrderedSDate!="" and $PermitOrderedEDate!="") $queryPart .= " AND date_permit_ordered >= '".date('Y-m-d',strtotime($PermitOrderedSDate))."' AND date_permit_ordered <= '".date('Y-m-d',strtotime($PermitOrderedEDate))."' ";
		elseif ($PermitOrderedSDate!="") $queryPart .= " AND date_permit_ordered = '".date('Y-m-d',strtotime($PermitOrderedSDate))."'";
		if ($PermitExpectedSDate!="" and $PermitExpectedEDate!="") $queryPart .= " AND date_permit_expected >= '".date('Y-m-d',strtotime($PermitExpectedSDate))."' AND date_permit_expected <= '".date('Y-m-d',strtotime($PermitExpectedEDate))."' ";
		elseif ($PermitExpectedSDate!="") $queryPart .= " AND date_permit_expected = '".date('Y-m-d',strtotime($PermitExpectedSDate))."'";
		if ($CompletedSDate!="" and $CompletedEDate!="") $queryPart .= " AND date_completion >= '".date('Y-m-d',strtotime($CompletedSDate))."' AND date_completion <= '".date('Y-m-d',strtotime($CompletedEDate))."' ";
		elseif ($CompletedSDate!="") $queryPart .= " AND date_completion = '".date('Y-m-d',strtotime($CompletedSDate))."'";
		if ($CreatedSDate!="" and $CreatedEDate!=""){ 
			$queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($CreatedSDate))." 00:00:00' AND created_on <= '".date('Y-m-d',strtotime($CreatedEDate))." 23:59:59' ";
		 	if($ignoreCostDate==''){
				$queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($CreatedSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($CreatedEDate))."' ";
				$queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($CreatedSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($CreatedEDate))."' ";
		 	}
			if($ignoreInvoiceDate=='' and $InvoiceSDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($CreatedSDate))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($CreatedEDate))."' ";
		 	}
		}elseif ($CreatedSDate!=""){
			$queryPart .= " AND date_format(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($CreatedSDate))."'";
		  	if($ignoreCostDate==''){
				$queryPartMaterialCost=" AND gpg_job_cost.date = '".date('Y-m-d',strtotime($CreatedSDate))."' ";
		 		$queryPartLaborCost=" AND gpg_timesheet.date = '".date('Y-m-d',strtotime($CreatedSDate))."' ";
		} 
			if($ignoreInvoiceDate=='' and $InvoiceSDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($CreatedSDate))."' ";
			} 
		}
		if ($SJobNumber!="" and $EJobNumber!="") $queryPart .= " AND job_num >= '".$SJobNumber."' AND job_num <= '".$EJobNumber."' ";
		elseif ($SJobNumber!="") $queryPart .= " AND job_num = '".$SJobNumber."'";
		if ($InvNumber!="") $queryPart .= " AND invoice_number = '$InvNumber' ";   
		if ($optEmployee!="" and $optEmployee!="notSeleted") $queryPart .= " AND (gpg_employee_id = '$optEmployee' ".($estimator?" OR estimator = '$optEmployee'":"").") ";
		if ($optEmployee!="" and $optEmployee=="notSeleted") $queryPart .= " AND ifnull(gpg_employee_id,'') = '' ";  
		if ($optCustomer!="") $queryPart .= " AND gpg_customer_id = '$optCustomer' ";   
		if ($optJobAccount=="national_account") $queryPart .= " AND ifnull(national_account,'') <> '' ";
		if ($optJobAccount=="sub_contractor") $queryPart .= " AND ifnull(sub_contractor,'') <> '' ";
		if ($optJobStatus=="completed") $queryPart .= " AND complete = '1' ";
		if ($optJobStatus=="notcompleted") $queryPart .= " AND complete = '0' ";
		if ($optJobStatus=="invoiced") $queryPart .= " AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1) ";   
		if ($optJobStatus=="not_invoiced") $queryPart .= "AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)>0,0,1) ";   
		if ($optJobStatus=="comp_inv") $queryPart .= " AND (complete = '1' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)) ";
		if ($optJobStatus=="not_comp_inv") $queryPart .= " AND (complete = '0' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)) ";
		if ($optJobStatus=="completed_not_invoiced") $queryPart .= " AND (complete = '1' AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)>0,0,1)) ";
		if ($optJobCostStatus=="no_labor") $queryPart .= " AND (select if(sum(total_wage)>0,0,1) from gpg_timesheet_detail where GPG_job_id = gpg_job.id) "; 
		if ($optJobCostStatus=="no_mat") $queryPart .= " AND (select if(sum(amount)>0,0,1) from gpg_job_cost where job_num = gpg_job.job_num) "; 
		if ($optJobCostStatus=="no_both") $queryPart .= " AND (select if(sum(amount)>0,0,1) from gpg_job_cost where job_num = gpg_job.job_num) AND (select if(sum(total_wage)>0,0,1) from gpg_timesheet_detail where GPG_job_id = gpg_job.id) "; 
		if ($optJobHaving=="cost") $queryPart .= " AND (select if(count(amount)>0,1,0) from gpg_job_cost where job_num = gpg_job.job_num)  ";
		if ($optJobHaving=="po") $queryPart .= " AND (select GPG_job_id from gpg_purchase_order where GPG_job_id = gpg_job.id AND ifnull(soft_delete,0)<>1 limit 0,1)";
		if ($optJobHaving=="timesheet") $queryPart .= " AND (select if(count(total_wage)>0,1,0) from gpg_timesheet_detail where GPG_job_id = gpg_job.id)";
		if ($optJobType=='') { 
			$queryPart .= " AND job_num like 'GPG%'"; 
			$_REQUEST['optJobType'] = "GPG";
		}
		if ($optJobType!='' and $optJobType!="service" and $optJobType!="all") $queryPart .= " AND job_num like '$optJobType%'";
		if($optJobType!='' and $optJobType=="service") $queryPart .= "AND GPG_job_type_id=4 AND job_num not like 'SH1_____%' AND job_num not like 'RNT%'";
		if($optJobType!='' and $optJobType=="all") $queryPart .= "";
		$queryPart .= " order by job_num desc"; 

		$t_rec = DB::select(DB::raw("SELECT COUNT(id) as total_records FROM gpg_job WHERE 1 $queryPart"));
		if (!empty($t_rec)){
			$results->totalItems = $t_rec[0]->total_records;
		}else
			$results->totalItems = 0;
		$whrEstimator = "";
		if(strlen($optEmployee)>0 && $estimator==1){
			$whrEstimator = " AND gpg_employee_commission.gpg_employee_id = '".$optEmployee."' ";
		}
		elseif(strlen($optEmployee)>0 && $estimator==0){
			$whrEstimator = " AND 0 ";
		}
		$totalsQuery = DB::select(DB::raw("select sum(if(((ifnull((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice),0) - ifnull(gpg_job.cost_to_dat,0)))>0,(ifnull((ifnull((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice),0) - ifnull(gpg_job.cost_to_dat,0)),0) - ifnull((select sum(ifnull(est_comm_paid,0)) from gpg_job_estimate_commission where gpg_job_id=gpg_job.id),0) - ifnull((select sum(ifnull(comm_paid,0)) from gpg_job_commission where gpg_job_id=gpg_job.id),0)),0.00)) as totNetMargin,
		sum((select sum(ifnull(est_comm_paid,0)) from gpg_job_estimate_commission where gpg_job_id=gpg_job.id )) as totEstCommPaid,
		sum(if(((ifnull((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice),0) - ifnull(gpg_job.cost_to_dat,0))>0 AND (SELECT name FROM gpg_employee WHERE id = gpg_job.gpg_employee_id)<>(SELECT name FROM gpg_employee WHERE id = gpg_job.estimator) AND (SELECT name FROM gpg_employee WHERE id = gpg_job.estimator)<>''),(((ifnull((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice),0) - ifnull(gpg_job.cost_to_dat,0))*(SELECT estimate_commission FROM gpg_employee_commission WHERE gpg_employee_commission.gpg_employee_id = gpg_job.estimator $whrEstimator and gpg_employee_commission.start_date <= DATE(gpg_job.created_on) order by start_date desc limit 0,1))/100.00),0.00)) as totEstComm,
		sum((select sum(ifnull(comm_paid,0)) from gpg_job_commission where gpg_job_id=gpg_job.id)) as totSalesCommPaid,
		sum(if(((ifnull((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice),0) - ifnull(gpg_job.cost_to_dat,0)))>0,(((ifnull((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice),0) - ifnull(gpg_job.cost_to_dat,0))*(SELECT sales_commission FROM gpg_employee_commission WHERE gpg_employee_commission.gpg_employee_id = gpg_job.GPG_employee_id ".(strlen($optEmployee)>0?" AND gpg_job.GPG_employee_id = '".$optEmployee."' ":"")." and gpg_employee_commission.start_date <= DATE(gpg_job.created_on) order by start_date desc limit 0,1))/100.00),0.00)) as totSalesComm,
		sum((ifnull((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice),0) - ifnull(gpg_job.cost_to_dat,0))) as totCalcMatgin,
		sum((select sum(invoice_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice)) as inv_amount,
		sum((select sum(tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice)) as tax_amount,
		sum((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id)) as invoice_amount_net,
		sum(if(GPG_job_type_id IN (5,12,13),if(fixed_price>0,fixed_price,if(nte>0,nte,if(sub_nte>0,sub_nte,(if(contract_amount>0,contract_amount,0))))),contract_amount)) as contract_amount, 
		sum((select sum(total_wage) from gpg_timesheet_detail , gpg_timesheet where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and job_num = gpg_job.job_num $queryPartLaborCost)) as lab_cost,
		sum((select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost)) as mat_cost, 
		sum(cost_to_dat) as cost_to_date from gpg_job where 1 $queryPart"));
		$whrSalePerson = "";
		$whrEstimatorqry = "";
		$whrEstimatorqry2 = "";
		if(strlen($optEmployee)>0 and $estimator==0){
			$whrSalePerson = " AND gpg_employee_id = '".$optEmployee."'";
			$whrEstimatorqry = " AND 0 ";
		}
		elseif(strlen($optEmployee)>0 and $estimator==1){
			$whrSalePerson = " AND gpg_employee_id = '".$optEmployee."'";
			$whrEstimatorqry = " AND estimator = '".$optEmployee."'";
			$whrEstimatorqry2 = " AND gpg_employee_id = '".$optEmployee."'";
		}
	
    	$getSales = DB::select(DB::raw("SELECT *,(SELECT CONCAT(invoice_number,'#~#',sum(invoice_amount),'#~#',invoice_date,'#~#',sum(tax_amount),'#~#',count(id)) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice GROUP BY gpg_job_invoice_info.gpg_job_id) AS invoice_data,
					(SELECT name FROM gpg_customer WHERE id = gpg_customer_id) AS customer_name, 
					(SELECT name FROM gpg_employee WHERE id = gpg_employee_id $whrSalePerson) AS sales_person_name, 
					(SELECT name FROM gpg_employee WHERE id = estimator $whrEstimatorqry) AS estimator_name,
					(SELECT SUM(total_wage) FROM gpg_timesheet_detail WHERE job_num = gpg_job.job_num) AS labor_cost,
					(SELECT SUM(amount) FROM gpg_job_cost WHERE job_num = gpg_job.job_num) AS material_cost, 
					IFNULL((SELECT gpg_sales_tracking_id FROM gpg_sales_tracking_job 
					WHERE gpg_sales_tracking_job.gpg_job_id = gpg_job.id),(SELECT gpg_sales_tracking_id
     				FROM gpg_sales_tracking_shop_work_job
     				WHERE gpg_sales_tracking_shop_work_job.gpg_job_id = gpg_job.id)) AS tracking_id, 
					(SELECT estimate_commission FROM gpg_employee_commission WHERE gpg_employee_commission.gpg_employee_id = gpg_job.estimator  AND gpg_employee_commission.start_date <= DATE(gpg_job.created_on) ORDER BY start_date DESC LIMIT 0,1) AS estimator_commission,
					(SELECT sales_commission FROM gpg_employee_commission WHERE gpg_employee_commission.gpg_employee_id = gpg_job.GPG_employee_id $whrSalePerson AND gpg_employee_commission.start_date <= DATE(gpg_job.created_on) ORDER BY start_date DESC LIMIT 0,1) AS sales_commission,
					(SELECT IFNULL(SUM(amount),0) FROM gpg_job_due_amount WHERE report_type=1 AND gpg_job_due_amount.job_num = gpg_job.job_num) AS AR_on_job,
					(SELECT IFNULL(SUM(amount),0) FROM gpg_job_due_amount WHERE report_type=2 AND gpg_job_due_amount.job_num = gpg_job.job_num) AS AP_on_job
					FROM gpg_job WHERE 1 $queryPart LIMIT $start,$limit"));
		$totalsQueryArr =  array();
		foreach ($totalsQuery as $key => $value) {
			$totalsQueryArr = (array)$value;
		}
		$getSalesArr = array();
		$temp_getSalesArr = array();
		foreach ($getSales as $key => $value2) {
			foreach ($value2 as $key => $value) {
				if ($key == 'id'){			
					$qr = DB::select(DB::raw("select comm_date,sum(ifnull(comm_paid,0)) as amt,count(id) as cnt from gpg_job_commission WHERE gpg_job_id = '".$value."' group by gpg_job_commission.gpg_job_id"));
					$qrArr = array();
					foreach ($qr as $key3 => $value3) {
						$qrArr = (array)$value3;
					}
					$temp_getSalesArr['commData'] = $qrArr;
					//////////////////////////////////
					$qr2 = DB::select(DB::raw("select est_comm_date,sum(ifnull(est_comm_paid,0)) as amt,count(id) as cnt from gpg_job_estimate_commission WHERE gpg_job_id = '".$value."' group by gpg_job_estimate_commission.gpg_job_id"));
					$qrArr2 = array();
					foreach ($qr2 as $key4 => $value4) {
						$qrArr2 = (array)$value2;
					}
					$temp_getSalesArr['estCommData'] = $qrArr2;
				}
				$temp_getSalesArr[$key] = $value;		
			}
			$getSalesArr[] = $temp_getSalesArr;
		}
		$results->items = $getSalesArr;
		$results->totals = $totalsQueryArr;					
		return $results;
	}

	/*
	* getCommAmt
	*/
	public function getCommAmt(){
		$id = Input::get('id');
		$t_sum = DB::select(DB::raw("select sum(ifnull(comm_paid,0)) as t_sum from gpg_job_commission WHERE gpg_job_id = '".$id."'"));
		if(isset($t_sum[0]->t_sum)) {
			return $t_sum[0]->t_sum;
		}else
			return 0;
	}
	/*
	* getEstCommAmt
	*/
	public function getEstCommAmt(){
		$id = Input::get('id');
		$t_sum = DB::select(DB::raw("select sum(ifnull(est_comm_paid,0)) as t_sum from gpg_job_estimate_commission WHERE gpg_job_id = '".$id."'"));
		if(isset($t_sum[0]->t_sum)) {
			return $t_sum[0]->t_sum;
		}else
			return 0;
	}
	/*
	* postEstCommAmt
	*/
	public function postEstCommAmt()
	{
		$id = Input::get('serv_id');
		$jobNum = Input::get('job_num');
		$est_com_amt = Input::get('est_com_amt');
		$est_com_date = Input::get('est_com_date');
		$data = DB::select(DB::raw("select sum(ifnull(est_comm_paid,0)) as t_sum from gpg_job_estimate_commission WHERE gpg_job_id = '".$id."'"));
		if (isset($data[0]->t_sum) && $data[0]->t_sum>0){
			DB::table('gpg_job_estimate_commission')->where('gpg_job_id','=',$id)->update(array('job_num'=>$jobNum,'est_comm_date'=>$est_com_date,'est_comm_paid'=>$est_com_amt,'modified_on'=>date('Y-m-d')));
		}else
			DB::table('gpg_job_estimate_commission')->insert(array('gpg_job_id'=>$id,'job_num'=>$jobNum,'est_comm_date'=>$est_com_date,'est_comm_paid'=>$est_com_amt,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
		return Redirect::to('job/commission_list');
	}
	/*
	* postCommAmt
	*/
	public function postCommAmt()
	{
		$id = Input::get('serv_id');
		$jobNum = Input::get('job_num');
		$est_com_amt = Input::get('com_amt');
		$est_com_date = Input::get('com_date');
		$data = DB::select(DB::raw("select sum(ifnull(est_comm_paid,0)) as t_sum from gpg_job_estimate_commission WHERE gpg_job_id = '".$id."'"));
		if (isset($data[0]->t_sum) && $data[0]->t_sum>0){
			DB::table('gpg_job_commission')->where('gpg_job_id','=',$id)->update(array('job_num'=>$jobNum,'comm_date'=>$est_com_date,'comm_paid'=>$est_com_amt,'modified_on'=>date('Y-m-d')));
		}else
			DB::table('gpg_job_commission')->insert(array('gpg_job_id'=>$id,'job_num'=>$jobNum,'comm_date'=>$est_com_date,'comm_paid'=>$est_com_amt,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
		return Redirect::to('job/commission_list');
	}
	/*
	* financialReport
	*/
	public  function financialReport()
	{
		$modules = Generic::modules();
		# flash input fields to re-populate (state maintain) search form		
		Input::flash();		
		$page = Input::get('page', 1);
   		$data = $this->getFinanceReport($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);

 		$customers = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$cust_arr = array(''=>'ALL');
		foreach ($customers as $key => $value)
				$cust_arr[$value->id] = $value->name;

 		$files = DB::table('gpg_sales_tracking_attachment')->select('*')->get();
		$files_arr = array();
		foreach ($files as $key => $value)
				$files_arr[$value->gpg_sales_tracking_id] = wordwrap($value->displayname,1000, "\n",1);	
		
		$technecians = DB::table('gpg_employee')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$tech_arr = array();
		foreach ($technecians as $key3 => $value3)
				$tech_arr[$value3->id] = $value3->name;	

 		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		
		$jobTypes = DB::table('gpg_job')->select('elec_job_type')->where('elec_job_type','!=','')->distinct()->orderBy('elec_job_type')->get();
		$jobtype_arr = array(''=>'Select Job Type');
		foreach ($jobTypes as $key => $value)
				$jobtype_arr[$value->elec_job_type] = preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", " ", $value->elec_job_type);			
		$job_types = DB::table('gpg_job_type')->lists('name','id');
	
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'task_array'=>$data->task_array,'cust_arr'=>$cust_arr,'salesp_arr'=>$salesp_arr,'jobtype_arr'=>$jobtype_arr,'tech_arr'=>$tech_arr,'files_arr'=>$files_arr,'job_types'=>$job_types,'elecJobTypeArray'=>$this->elecJobTypeArray);
 		return View::make('job.financial_report', $params);
	}
	public function getFinanceReport($page = 1, $limit = null)
	{
		$results = new \StdClass;
	  	$results->page = $page;
		# set offset (for excel export offset will not apply)
	    $limitOffset = '';
	    if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}
	    $results->totalItems = 0;
	    $results->items = array();
	    $results->task_array = array();

	    $timesheetQueryPart =""; 
		$materialQueryPart =""; 
		$poQueryPart ="";	
		$invoiceQueryPart =""; 
		$poIssuedT = "";
		$marginT ="";
		$contactAmountT="";
		$budgetMaterialT="";
		$variable="";
		$invAmtT="";
		$invAmtTotal =0;
		$taxAmtT =0;
		$materialCostT="";
		$laborCostT="";
		$totalCostT="";	
		$variable="";
		$budgetLaborT="";
		$perform_search=0;
		$ar_total=0;
		$ap_total=0;

		$ReportSDate =  Input::get("ReportSDate");
		$ReportEDate =  Input::get("ReportEDate");
		$InvoiceSDate =  Input::get("InvoiceSDate");
		$InvoiceEDate =  Input::get("InvoiceEDate");
		$ignoreDate =  Input::get("ignoreDate");
		$ignoreInvoiceDate =  Input::get("ignoreInvoiceDate");
		$SJobNumber = Input::get("SJobNumber");
		$EJobNumber = Input::get("EJobNumber");
		$optEmployee = Input::get("optEmployee");
		$optEstimator = Input::get("optEstimator");
		$optCustomer = Input::get("optCustomer");
		$optJobType = Input::get("optJobType");
		$jobStatus = Input::get("jobStatus");
		$JobCompleteSDate=Input::get("JobCompleteSDate");
		$JobCompleteEDate=Input::get("JobCompleteEDate");
		$optJobCostStatus =  Input::get("optJobCostStatus");
		$optJobHaving =  Input::get("optJobHaving");
		$contract_number =  Input::get("contract_number");
		$jobTypeTask =  Input::get("jobTypeTask");
		$queryPart ="";
		$point_total=0;

		if (!count($optJobType)) $optJobType = array(4,5);
		if (!count($optJobHaving)) $optJobHaving = array();
		if ($ReportSDate!="" and $ReportEDate!=""){
		    $queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($ReportSDate))." 00:00:00' AND  created_on  <= '".date('Y-m-d',strtotime($ReportEDate))." 23:59:59' ";
		    if($ignoreDate==''){
		        $timesheetQueryPart .= " AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($ReportSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($ReportEDate))."' ";
		        $materialQueryPart .= " AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($ReportSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($ReportEDate))."' ";
		        $poQueryPart .= " AND gpg_purchase_order.po_date >= '".date('Y-m-d',strtotime($ReportSDate))."' AND gpg_purchase_order.po_date <= '".date('Y-m-d',strtotime($ReportEDate))."' ";
		    }
		    if($ignoreInvoiceDate=='' and $InvoiceSDate==''){
		        $invoiceQueryPart .= " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($ReportSDate))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($ReportEDate))."' ";
		    }
		}
		elseif ($ReportSDate!="") {
		    $queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($ReportSDate))." 00:00:00' and created_on <= '".date('Y-m-d',strtotime($ReportSDate))." 23:59:59' ";
		    if($ignoreDate==''){
		        $timesheetQueryPart .= " AND gpg_timesheet.date = '".date('Y-m-d',strtotime($ReportSDate))."'";
		        $materialQueryPart .= " AND gpg_job_cost.date = '".date('Y-m-d',strtotime($ReportSDate))."'";
		        $poQueryPart .= " AND gpg_purchase_order.po_date = '".date('Y-m-d',strtotime($ReportSDate))."'";
		    }
		    if($ignoreInvoiceDate=='' and $InvoiceSDate==''){
		        $invoiceQueryPart .= " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($ReportSDate))."' ";
		    }
		}
		if ($InvoiceSDate!="" and $InvoiceEDate!="") {
		    $queryPart .= " AND (SELECT IF(gpg_job_invoice_info.gpg_job_id,1,0) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND  gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."'  ORDER BY gpg_job_invoice_info.invoice_date DESC LIMIT 0,1) ";
		    if($ignoreDate=='' and $ReportSDate==''){
		        $timesheetQueryPart .= " AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
		        $materialQueryPart .= " AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
		        $poQueryPart .= " AND gpg_purchase_order.po_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_purchase_order.po_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
		    }
		    if($ignoreInvoiceDate==''){
		        $invoiceQueryPart .= " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
		    }
		}
		elseif ($InvoiceSDate!="") {
		    $queryPart .= " AND (SELECT IF(gpg_job_invoice_info.gpg_job_id,1,0) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id= gpg_job.id AND  gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ORDER BY gpg_job_invoice_info.invoice_date DESC LIMIT 0,1)";
		    if($ignoreDate=='' and $ReportSDate==''){
		        $timesheetQueryPart .= " AND gpg_timesheet.date = '".date('Y-m-d',strtotime($InvoiceSDate))."'";
		        $materialQueryPart .= " AND gpg_job_cost.date = '".date('Y-m-d',strtotime($InvoiceSDate))."'";
		        $poQueryPart .= " AND gpg_purchase_order.po_date = '".date('Y-m-d',strtotime($InvoiceSDate))."'";
		    }
		    if($ignoreInvoiceDate==''){
		        $invoiceQueryPart .= " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
		    }
		}
		if ($SJobNumber!="" and $EJobNumber!="") $queryPart .= " AND job_num >= '".$SJobNumber."' AND job_num <= '".$EJobNumber."' ";
		elseif ($SJobNumber!="") $queryPart .= " AND job_num = '".$SJobNumber."'";
		if ($optEstimator!="") $queryPart .= " AND estimator = '$optEstimator' ";
		if (count($optJobType)) $queryPart .= " AND GPG_job_type_id in( ". implode(",", $optJobType)." )";
		if ($optEmployee!="") $queryPart .= " AND gpg_employee_id = '$optEmployee' ";
		if ($optCustomer!="") $queryPart .= " AND gpg_customer_id = '$optCustomer' ";
		if (in_array("no_labor",$optJobHaving)) $queryPart .= " AND IF((SELECT IFNULL(total_wage,0) FROM gpg_timesheet_detail WHERE GPG_job_id = gpg_job.id ORDER BY total_wage DESC LIMIT 0,1)>0,0,1)  ";
		if (in_array("no_mat",$optJobHaving)) $queryPart .= " AND IF((SELECT IFNULL(amount,0) FROM gpg_job_cost WHERE job_num = gpg_job.job_num ORDER BY amount DESC LIMIT 0,1)>0,0,1) ";
		if (in_array("cost",$optJobHaving)) $queryPart .= " AND (SELECT IF(count(amount)>0,1,0) FROM gpg_job_cost WHERE job_num = gpg_job.job_num LIMIT 0,1)  ";
		if (in_array("po",$optJobHaving)) $queryPart .= " AND (SELECT IF(count(id)>0,1,0) FROM gpg_purchase_order WHERE GPG_job_id = gpg_job.id LIMIT 0, 1)";
		if (in_array("timesheet",$optJobHaving)) $queryPart .= " AND (SELECT IF(count(total_wage)>0,1,0) FROM gpg_timesheet_detail WHERE GPG_job_id = gpg_job.id LIMIT 0,1)";
		if (in_array("no_inv",$optJobHaving)) $queryPart .= " AND IF(IFNULL(invoice_date,'0000-00-00')='0000-00-00',1,0) ";
		if (in_array("inv_cost",$optJobHaving)) $queryPart .= " AND IF((SELECT IFNULL(total_wage,0) FROM gpg_timesheet_detail WHERE GPG_job_id = gpg_job.id ORDER BY total_wage DESC LIMIT 0,1)>0,1,0) AND IF((SELECT IFNULL(amount,0) FROM gpg_job_cost WHERE job_num = gpg_job.job_num ORDER BY amount DESC LIMIT 0,1)>0,1,0) AND IF(IFNULL(invoice_date,'0000-00-00')<>'0000-00-00',1,0) ";
		if($JobCompleteSDate!='' && $JobCompleteEDate!=''){
		    $queryPart .= " AND date_completion >= '".date('Y-m-d',strtotime($JobCompleteSDate))."' AND date_completion <= '".date('Y-m-d',strtotime($JobCompleteEDate))."' ";
		}elseif ($JobCompleteSDate!="") {
		    $queryPart .= " AND date_completion >= '".date('Y-m-d',strtotime($JobCompleteSDate))."' ";
		}
		if($jobStatus=='1'){
		    $queryPart .= " AND complete='1'";
		}
		if($jobStatus=='0'){
		    $queryPart .= " AND complete <> '1'";
		}
		//added for contract
		if($contract_number != ''){
		    $queryPart .= " AND gpg_job.contract_number like '".$contract_number."%'";
		}
		//added for job type task
		if($jobTypeTask != '' && $jobTypeTask != 'ALL'){
		    $queryPart .= " AND (gpg_job.task = '".$jobTypeTask."' OR gpg_job.elec_job_type = '".$jobTypeTask."')";
		}
		if(!empty($ReportSDate) or !empty($InvoiceSDate) or !empty($SJobNumber) or !empty($EJobNumber) or !empty($JobCompleteSDate) or !empty($optJobCostStatus) or !empty($contract_number)){
		    $perform_search = 1 ;
		}
		$queryPart .= " ORDER BY job_num DESC $limitOffset";
		if (isset($_POST) && !empty($_POST)){
			set_time_limit(0);
			$t_rec = DB::select(DB::raw("SELECT COUNT(id) as total_records FROM gpg_job WHERE 1 $queryPart"));
			if (!empty($t_rec[0]->total_records)){
				 $results->totalItems = $t_rec[0]->total_records;
			}
			$qry = "SELECT gpg_job.id as id , job_num,attach_job_num, contract_amount,  budgeted_material , budgeted_labor, complete, date_completion, gpg_job.elec_job_type, gpg_job.task, gpg_job.fixed_price, gpg_job.nte, gpg_job.sub_nte, gpg_job.cost_to_dat,gpg_job.contract_number,
								(SELECT name FROM gpg_customer WHERE id = gpg_customer_id) AS customer_name,
								(SELECT name FROM gpg_employee WHERE id = estimator) AS estimator_name, 
								(SELECT name FROM gpg_employee WHERE id = gpg_employee_id) AS sales_person_name,
								(SELECT IFNULL(SUM(amount),0) FROM gpg_job_due_amount WHERE report_type=1 AND gpg_job_due_amount.job_num = gpg_job.job_num) AS AR_on_job,
								(SELECT IFNULL(SUM(amount),0) FROM gpg_job_due_amount WHERE report_type=2 AND gpg_job_due_amount.job_num = gpg_job.job_num) AS AP_on_job,
								(SELECT CONCAT(invoice_number,'#~#',SUM(invoice_amount),'#~#',invoice_date,'#~#',SUM(tax_amount),'#~#',COUNT(id)) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id $invoiceQueryPart GROUP BY gpg_job_invoice_info.gpg_job_id) AS invoice_data
								FROM gpg_job WHERE 1  $queryPart ";
			//echo "qry=>".$qry;
			//die();					
		    $getSales = DB::select(DB::raw("SELECT gpg_job.id as id , job_num,attach_job_num, contract_amount,  budgeted_material , budgeted_labor, complete, date_completion, gpg_job.elec_job_type, gpg_job.task, gpg_job.fixed_price, gpg_job.nte, gpg_job.sub_nte, gpg_job.cost_to_dat,gpg_job.contract_number,
								(SELECT name FROM gpg_customer WHERE id = gpg_customer_id) AS customer_name,
								(SELECT name FROM gpg_employee WHERE id = estimator) AS estimator_name, 
								(SELECT name FROM gpg_employee WHERE id = gpg_employee_id) AS sales_person_name,
								(SELECT IFNULL(SUM(amount),0) FROM gpg_job_due_amount WHERE report_type=1 AND gpg_job_due_amount.job_num = gpg_job.job_num) AS AR_on_job,
								(SELECT IFNULL(SUM(amount),0) FROM gpg_job_due_amount WHERE report_type=2 AND gpg_job_due_amount.job_num = gpg_job.job_num) AS AP_on_job,
								(SELECT CONCAT(invoice_number,'#~#',SUM(invoice_amount),'#~#',invoice_date,'#~#',SUM(tax_amount),'#~#',COUNT(id)) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id $invoiceQueryPart GROUP BY gpg_job_invoice_info.gpg_job_id) AS invoice_data
								FROM gpg_job WHERE 1  $queryPart"));
			
			$getSalesArr = array();
			foreach ($getSales as $key2 => $value2) {
				foreach ($value2 as $key => $value) {
					if ($key == 'job_num') {
						 	if (preg_match("/GPG/i",$value)) {
					             $index1= DB::select(DB::raw("SELECT FORMAT(SUM(gp.labor_quantity),2) as res FROM gpg_job_electrical_labor_pricing gp, gpg_job_electrical_quote gq WHERE gq.GPG_attach_job_num = '".$value."' AND gq.id = gp.gpg_job_electrical_quote_id"));
					            	if (!empty($index1)){
					            		$temp_arry['index1'] = $index1[0]->res;		
					            	}	
					            }
					            elseif(preg_match("/SH/i",$value))
					            {
					            	 $index1= DB::select(DB::raw("SELECT  SUM(shop + labor + lbt + ot + sub_con) as res
																				FROM gpg_shop_work_quote_labor,
																				  gpg_shop_work_quote
																				WHERE gpg_shop_work_quote_id = gpg_shop_work_quote.id
																				AND GPG_attach_job_num = '".$value."'"));	
					            	if (!empty($index1)){
					            		$temp_arry['index1'] = $index1[0]->res;		
					            	}	
					            }
					            elseif(preg_match("/QT/i",$value))
					            {
					            	$index1= DB::select(DB::raw("SELECT  SUM(shop + labor + lbt + ot + sub_con) as res
																				FROM gpg_field_service_work_labor,
																				  gpg_field_service_work
																				WHERE gpg_field_service_work_id = gpg_field_service_work.id
																				AND GPG_attach_job_num = '".$value."'"));	
					            	if (!empty($index1)){
					            		$temp_arry['index1'] = $index1[0]->res;		
					            	}	
					            }
					            if(empty($temp_arry['index1']))
					            	$temp_arry['index1'] = '-';
					}
					$temp_arry[$key] = $value;
				}
				$getSalesArr[] = $temp_arry;
			}
			$results->items = $getSalesArr;
		}
		////**** Populating records for job type task from table gpg_type start****///////
		$task_array = array() ;
		$result = DB::select(DB::raw("SELECT DISTINCT task FROM `gpg_job` WHERE task <> '' AND `job_num` NOT REGEXP 'sh|gpg' ORDER BY `task`"));
		foreach ($result as $key => $row) {
			$task_array[$row->task] = $row->task ;
		}
		asort($task_array);
		$results->task_array = $task_array;
		return $results;
	}

	/*
	* serviceJobList
	*/
	public function serviceJobList()
	{
		$modules = Generic::modules();
		# flash input fields to re-populate (state maintain) search form		
		Input::flash();		
		
		$page = Input::get('page', 1);
   		$data = $this->getServiceJobs($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
 		
 		$customers = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$cust_arr = array(''=>'ALL');
		foreach ($customers as $key => $value)
				$cust_arr[$value->id] = $value->name;

 		$files = DB::table('gpg_sales_tracking_attachment')->select('*')->get();
		$files_arr = array();
		foreach ($files as $key => $value)
				$files_arr[$value->gpg_sales_tracking_id] = wordwrap($value->displayname,1000, "\n",1);	
		
		$technecians = DB::table('gpg_employee')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$tech_arr = array();
		foreach ($technecians as $key3 => $value3)
				$tech_arr[$value3->id] = $value3->name;	

 		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		
		$jobTypes = DB::table('gpg_job')->select('elec_job_type')->where('elec_job_type','!=','')->distinct()->orderBy('elec_job_type')->get();
		$jobtype_arr = array(''=>'Select Job Type');
		foreach ($jobTypes as $key => $value)
				$jobtype_arr[$value->elec_job_type] = preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", " ", $value->elec_job_type);			
		$technicians = DB::table('gpg_employee')->where('status','=','A')->whereIn('GPG_employee_type_id',array('9','4','8','1'))->orderBy('name')->lists('name','id');	
		$cleared_reason = DB::select(DB::raw("select distinct(cleared_reason) as id, cleared_reason as name from gpg_job where cleared_reason<>'' order by name"));
		$cleared_reasonArr = array();
		foreach ($cleared_reason as $key => $value) {
			$cleared_reasonArr[$value->id] = $value->name;
		}
		
		$params = array('left_menu' => $modules ,'query_data'=>$query_data,'totalsRow'=>$data->totals,'cust_arr'=>$cust_arr,'salesp_arr'=>$salesp_arr,'jobtype_arr'=>$jobtype_arr,'tech_arr'=>$tech_arr,'files_arr'=>$files_arr,'technicians'=>$technicians,'cleared_reasonArr'=>$cleared_reasonArr,'FSWStatusArray'=>$this->FSWStatusArray);
		return View::make('job.service_job_list', $params);
	}
	public function getServiceJobs($page = 1, $limit = null)
	{
		$results = new \StdClass;
	  	$results->page = $page;
		# set offset (for excel export offset will not apply)
	    $limitOffset = '';
	    if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
	    }
	    $results->totalItems = 0;
	  	$results->items = array();
	  	$results->totals = array();
	  	$flagBig = 0;
	  	$ignoreCostDate =  Input::get("ignoreCostDate");
		$ignoreInvoiceDate =  Input::get("ignoreInvoiceDate");
		$JobWonSDate =  Input::get("JobWonSDate");
		$JobWonEDate =  Input::get("JobWonEDate");
		$PartsOrderedSDate =  Input::get("PartsOrderedSDate");
		$PartsOrderedEDate =  Input::get("PartsOrderedEDate");
		$PartsRecievedSDate =  Input::get("PartsRecievedSDate");
		$PartsRecievedEDate =  Input::get("PartsRecievedEDate");
		$PartsScheduledSDate =  Input::get("PartsScheduledSDate");
		$PartsScheduledEDate =  Input::get("PartsScheduledEDate");
		$ScheduledSDate =  Input::get("ScheduledSDate");
		$ScheduledEDate =  Input::get("ScheduledEDate");
		$SDate2 =  Input::get("SDate2");
		$EDate2 =  Input::get("EDate2");
		$InvoiceSDate =  Input::get("InvoiceSDate");
		$InvoiceEDate =  Input::get("InvoiceEDate");
		$SJobNumber = Input::get("SJobNumber");
		$EJobNumber = Input::get("EJobNumber");
		$SContractNumber = Input::get("SContractNumber");
		$EContractNumber = Input::get("EContractNumber");
		$optEmployee = Input::get("optEmployee"); 
		$optTech = Input::get("optTech");
		$optCustomer = Input::get("optCustomer");
		$optWonJob = Input::get("optWonJob");
		$optRecQuote = Input::get("optRecQuote");
		$InvNumber = Input::get("InvNumber");
		$optJobStatus = Input::get("optJobStatus");
		$optJobAccount = Input::get("optJobAccount");
		$optJobCostStatus =  Input::get("optJobCostStatus");
		$optJobHaving =  Input::get("optJobHaving");
		$optRegarding = Input::get("optRegarding");
		$optClearedReason = Input::get("optClearedReason");
		$optTechAtt = Input::get("optTechAtt");
		$optAttachedFlist = Input::get("optAttachedFlist");
		$optLaborMaterialActivity = Input::get("optLaborMaterialActivity");
		$dropEmployee = Input::get("dropEmployee");
		$RecCategoryValue = Input::get("RecCategoryValue");
		$editing_jobs = Input::get("editing_jobs");
		$sort_order = Input::get("sort_order");
		$sort_type = Input::get("sort_type");
		$multiple_contract_nums = Input::get("multiple_contract_nums");
		/********* New defined*******/
		$service_job_list = "";
		$queryPartInvoice ="";
		$queryPartLaborCost ="";
		$queryPartMaterialCost ="";
		$queryPartInvoice ="";
		$queryPartLaborCost ="";
		$queryPartMaterialCost ="";
		$queryPart ="";
		$quoteDate ="";
		$fg="";
		/*********/
		if ($PartsScheduledSDate!="" and $PartsScheduledEDate!="") $queryPart .= " AND date_job_scheduled_for >= '".date('Y-m-d',strtotime($PartsScheduledSDate))."' AND date_job_scheduled_for <= '".date('Y-m-d',strtotime($PartsScheduledEDate))."' ";
		elseif ($PartsScheduledSDate!="") $queryPart .= " AND date_job_scheduled_for = '".date('Y-m-d',strtotime($PartsScheduledSDate))."'";
		if ($ScheduledSDate!="" and $ScheduledEDate!="") $queryPart .= " AND schedule_date >= '".date('Y-m-d',strtotime($ScheduledSDate))."' AND schedule_date <= '".date('Y-m-d',strtotime($ScheduledEDate))."' ";
		elseif ($ScheduledSDate!="") $queryPart .= " AND schedule_date = '".date('Y-m-d',strtotime($ScheduledSDate))."'";
		if ($PartsOrderedSDate!="" and $PartsOrderedEDate!="") $queryPart .= " AND date_parts_ordered >= '".date('Y-m-d',strtotime($PartsOrderedSDate))."' AND date_parts_ordered <= '".date('Y-m-d',strtotime($PartsOrderedEDate))."' ";
		elseif ($PartsOrderedSDate!="") $queryPart .= " AND date_parts_ordered = '".date('Y-m-d',strtotime($PartsOrderedSDate))."'";
		if ($PartsRecievedSDate!="" and $PartsRecievedEDate!="") $queryPart .= " AND date_parts_Recieved >= '".date('Y-m-d',strtotime($PartsRecievedSDate))."' AND date_parts_Recieved <= '".date('Y-m-d',strtotime($PartsRecievedEDate))."' ";
		elseif ($PartsRecievedSDate!="") $queryPart .= " AND date_parts_Recieved = '".date('Y-m-d',strtotime($PartsRecievedSDate))."'";
		if ($JobWonSDate!="" and $JobWonEDate!="") $queryPart .= " AND date_job_won >= '".date('Y-m-d',strtotime($JobWonSDate))."' AND date_job_won <= '".date('Y-m-d',strtotime($JobWonEDate))."' ";
		elseif ($JobWonSDate!="") $queryPart .= " AND date_job_won = '".date('Y-m-d',strtotime($JobWonSDate))."'";
		if ($SDate2!="" and $EDate2!=""){
			$queryPart .= " AND gpg_job.created_on >= '".date('Y-m-d',strtotime($SDate2))." 00:00:00' AND gpg_job.created_on <= '".date('Y-m-d',strtotime($EDate2))." 23:59:59' ";
		 	if($ignoreCostDate==''){
				$queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($SDate2))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($EDate2))."' ";
				$queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($SDate2))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($EDate2))."' ";
		}
			if($ignoreInvoiceDate=='' and $InvoiceSDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($SDate2))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($EDate2))."' ";
			}
		}
		elseif ($EDate2!="")  $queryPart .= " AND gpg_job.created_on <= '".date('Y-m-d',strtotime($EDate2))." 23:59:59' ";
		elseif ($SDate2!="" and $flagBig!='1') $queryPart .= " AND date_format(gpg_job.created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate2))."'";
		elseif ($SDate2!=""){
			$queryPart .= " AND gpg_job.created_on <= '".date('Y-m-d',strtotime($SDate2))." 23:59:59'";
		 	if($ignoreCostDate==''){
				$queryPartMaterialCost=" AND gpg_job_cost.date = '".date('Y-m-d',strtotime($SDate2))."' ";
				$queryPartLaborCost=" AND gpg_timesheet.date = '".date('Y-m-d',strtotime($SDate2))."' ";
			} 
			if($ignoreInvoiceDate=='' and $InvoiceSDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($SDate2))."' ";
			} 
		}
		if ($InvoiceSDate!="" and $InvoiceEDate!=""){ 
			$queryPart .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND  gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."'  order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
		 	if($ignoreCostDate=='' and $SDate==''){
				$queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
				$queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
			}
		 	if($ignoreInvoiceDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
		 	}
		}
		elseif ($InvoiceSDate!=""){ 
			$queryPart .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND  gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
			if($ignoreCostDate=='' and $SDate==''){
		 		$queryPartMaterialCost=" AND gpg_job_cost.date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
			 	$queryPartLaborCost=" AND gpg_timesheet.date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
		 	} 
		 	if($ignoreInvoiceDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
			} 
		} 
		if ($SJobNumber!="" and $EJobNumber!="") $queryPart .= " AND job_num >= '".$SJobNumber."' AND job_num <= '".$EJobNumber."' ";
		elseif($SJobNumber!="" and strpos($SJobNumber,'|')){
			$jobType=explode('|',$SJobNumber);
		 	$queryPart .= " AND (";
		 	$c = count($jobType);
		 	while (list($k,$v)=each($jobType)) {
			 $queryPart .=" job_num like '".$v."%' ";
			 if(($c-1) > $k){
				 $queryPart .=" OR ";
			 }
		 	}
		 	$queryPart.=" ) ";
		}
		elseif ($SJobNumber!="") $queryPart .= " AND job_num like '".$SJobNumber."%'";
		if ($SContractNumber!="" and $EContractNumber!="") $queryPart .= " AND contract_number >= '".$SContractNumber."' AND contract_number <= '".$EContractNumber."' ";
		elseif($SContractNumber!="" and strpos($SContractNumber,'|')){
			$contractNumType=explode('|',$SContractNumber);
			$queryPart .= " AND (";
		 	$c = count($contractNumType);
		 	while (list($k,$v)=each($contractNumType)) {
		 		$queryPart .=" contract_number like '".$v."%' ";
		 		if(($c-1) > $k){
					$queryPart .=" OR ";
		 		}
			}
		 $queryPart.=" ) ";
		}
		elseif ($SContractNumber!="") $queryPart .= " AND contract_number like '".$SContractNumber."%'"; 
		if ($InvNumber!="") $queryPart .= " AND invoice_number = '$InvNumber' ";   
		if ($optEmployee!="" and $optEmployee!="notSeleted") $queryPart .= " AND gpg_employee_id = '$optEmployee' ";
		if ($optTech!="") $queryPart .= " AND gpg_job.id IN (select d.GPG_job_id from gpg_timesheet_detail d, gpg_timesheet e where e.id = d.GPG_timesheet_id  and e.GPG_employee_Id = '$optTech')";
		if ($optEmployee!="" and $optEmployee=="notSeleted") $queryPart .= " AND ifnull(gpg_employee_id,'') = '' ";   
		if ($optCustomer!="") $queryPart .= " AND gpg_customer_id = '$optCustomer' ";   
		if ($optJobAccount=="national_account") $queryPart .= " AND ifnull(national_account,'') <> '' ";
		if ($optJobAccount=="sub_contractor") $queryPart .= " AND ifnull(sub_contractor,'') <> '' ";
		if ($optJobStatus=="completed") $queryPart .= " AND complete = '1' ";
		if ($optJobStatus=="notcompleted") $queryPart .= " AND complete = '0'";
		if ($optJobStatus=="invoiced") $queryPart .= " AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1) ";   
		if ($optJobStatus=="not_invoiced") $queryPart .= "AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)>0,0,1) ";   
		if ($optJobStatus=="comp_inv") $queryPart .= " AND (complete = '1' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)) ";
		if ($optJobStatus=="completed_not_invoiced") $queryPart .= " AND (complete = '1'  AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)>0,0,1)) ";
		if ($optJobStatus=="won_not_ordered") $queryPart .= " AND ifnull(date_job_won,'')<>'' AND ifnull(date_parts_ordered,'')='' ";
		if ($optJobStatus=="ordered_not_recieved") $queryPart .= " AND ifnull(date_parts_ordered,'')<>'' AND ifnull(date_parts_recieved,'')='' ";
		if ($optJobStatus=="recieved_not_scheduled") $queryPart .= " AND ifnull(date_parts_recieved,'')<>'' AND ifnull(date_job_scheduled_for,'')='' ";
		if ($optJobStatus=="completed_not_closed") $queryPart .= " AND complete = '1' AND (closed = '0' OR closed IS NULL)";
		if ($optJobStatus=="closed_not_completed") $queryPart .= " AND (complete = '0' OR complete IS NULL) AND closed = '1' ";
		if ($optJobCostStatus=="no_labor") $queryPart .= " AND (select if(sum(total_wage)>0,0,1) from gpg_timesheet_detail where GPG_job_id = gpg_job.id) "; 
		if ($optJobCostStatus=="no_mat") $queryPart .= " AND (select if(sum(amount)>0,0,1) from gpg_job_cost where job_num = gpg_job.job_num limit 0,1) "; 
		if ($optJobCostStatus=="no_both") $queryPart .= " AND (select if(sum(amount)>0,0,1) from gpg_job_cost where job_num = gpg_job.job_num) AND (select if(sum(total_wage)>0,0,1) from gpg_timesheet_detail where GPG_job_id = gpg_job.id) "; 
		if ($optJobHaving=="cost") $queryPart .= " AND (select if(count(amount)>0,1,0) from gpg_job_cost where job_num = gpg_job.job_num limit 0,1)  ";
		if ($optJobHaving=="po") $queryPart .= " AND (select GPG_job_id from gpg_purchase_order where GPG_job_id = gpg_job.id AND ifnull(soft_delete,0)<>1 limit 0,1)";
		if ($optJobHaving=="timesheet") $queryPart .= " AND (select if(count(total_wage)>0,1,0) from gpg_timesheet_detail where GPG_job_id = gpg_job.id limit 0,1)";
		if ($optWonJob=="won") $queryPart .= " AND ifnull(date_job_won,'') <> '' ";   
		if ($optWonJob=="not_won") $queryPart .= " AND ifnull(date_job_won,'') = '' ";
		if ($optAttachedFlist=="attached") $queryPart .= " AND gpg_job.id IN (select GPG_attach_job_id from gpg_field_service_work where GPG_attach_job_id=gpg_job.id)";  
		if ($optAttachedFlist=="notAttached") $queryPart .= " AND gpg_job.id NOT IN (select GPG_attach_job_id from gpg_field_service_work where ifnull(GPG_attach_job_id,0)<>0)";
		if ($optLaborMaterialActivity=="noLaborMaterialActivity") $queryPart .= " AND ((job_num in (select b.job_num from gpg_timesheet c , gpg_timesheet_detail b WHERE c.id = b.GPG_timesheet_id and b.job_num=job_num AND DATEDIFF(CURDATE(),c.date)>7)) OR (job_num in (select job_num from gpg_job_cost a where a.job_num=job_num AND DATEDIFF(CURDATE(),a.date)>7)))";
		if ($optRecQuote!="") { 
		    if ($optRecQuote=="noQuote") {
			  $queryPart .= " AND (select count(gpg_job_id) from gpg_job_doc where type = 'Q' and ifnull(filename,'') <> '' and gpg_job_id = gpg_job.id limit 0,1)=0 ";   
			} elseif ($optRecQuote=="quoteNoRec"){
				$queryPart .= " AND (select count(gpg_job_id) from gpg_job_doc where type = 'R' and ifnull(filename,'') <> '' and gpg_job_id = gpg_job.id limit 0,1) > 0 AND (select count(gpg_job_id) from gpg_job_doc where type = 'Q' and ifnull(filename,'') <> '' and gpg_job_id = gpg_job.id limit 0,1)=0 ";   
		 	} elseif ($optRecQuote=="recNoQuote"){
				$queryPart .= " AND (select count(gpg_job_id) from gpg_job_doc where type = 'R' and ifnull(filename,'') <> '' and gpg_job_id = gpg_job.id limit 0,1) = 0 AND (select count(gpg_job_id) from gpg_job_doc where type = 'Q' and ifnull(filename,'') <> '' and gpg_job_id = gpg_job.id limit 0,1)>0 ";   
		 	} else {
		    $queryPart .= " AND (select gpg_job_id from gpg_job_doc where type = '$optRecQuote' and ifnull(filename,'') <> '' and gpg_job_id = gpg_job.id limit 0,1) ";   
			}
		}
		if($dropEmployee!="")	$queryPart .= " AND gpg_job_doc.GPG_asign_employee_id = '$dropEmployee'";
		if($RecCategoryValue!="")	$queryPart .= " AND gpg_job_doc.RecCategory = '$RecCategoryValue'";
		if ($optRegarding!="") $queryPart .= " AND task = '$optRegarding'";
		if ($optClearedReason!="") $queryPart .= " AND cleared_reason = '$optClearedReason'";
		if ($optTechAtt=="single") $queryPart .= ' AND technicians <> "" AND  technicians NOT LIKE "%,%" ';
		if ($optTechAtt=="multiple") $queryPart .= ' AND technicians <> "" AND  technicians  LIKE "%,%" ';
		if ($optTechAtt=="both") $queryPart .= ' AND technicians <> ""';
		/******************************************************/
		$queryPartContractNums = "";
		if(strlen($multiple_contract_nums)>0){
			$temp_arr = explode("_",$multiple_contract_nums);
			foreach($temp_arr as $key=>$value){
				$queryPartContractNums .= '"'.$value.'",';
			}
			$queryPartContractNums = " AND gpg_job.contract_number IN (".substr($queryPartContractNums,0,strlen($queryPartContractNums)-1).") AND (gpg_job.job_num like 'BO%' OR gpg_job.job_num like 'PM%')  ";
		}
		$queryPartOrder = " ORDER BY gpg_job.created_on DESC"; 	

		$totalsQuery = DB::select(DB::raw("select 
		sum((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id)) as invoice_amount_net, 
		sum(if(fixed_price>0,fixed_price,contract_amount)) as contract_amount, 
		sum((select sum(invoice_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice)) as inv_amount, 
		sum((select sum(tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice)) as tax_amount ,
		sum((select sum(total_wage) from gpg_timesheet_detail , gpg_timesheet where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and job_num = gpg_job.job_num $queryPartLaborCost)) as lab_cost,
		sum((select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost)) as mat_cost, 
		sum(cost_to_dat) as cost_to_date 
		from gpg_job LEFT JOIN gpg_job_doc ON (gpg_job.id=gpg_job_doc.gpg_job_id and gpg_job_doc.id = (SELECT id FROM gpg_job_doc WHERE gpg_job.id=gpg_job_doc.gpg_job_id ORDER BY gpg_job_doc.type ASC LIMIT 1)) where GPG_job_type_id='4' and job_num not like 'RNT%' $queryPart ".$queryPartContractNums. $queryPartOrder));
		$totals_arr = array();
		foreach ($totalsQuery as $key => $value) {
			$totals_arr = (array)$value;
		}
		$results->totals = $totals_arr;
		if($sort_type!="ASC")
			$sort_type = "DESC";
		if($sort_order=="recommendation_days"){
			$queryPartOrder = " ORDER BY rec_days ".$sort_type; 
		}
		elseif($sort_order=='customer'){
			$queryPartOrder = " ORDER BY customer_name ".$sort_type; 
		}
		else{
			$queryPartOrder = " ORDER BY gpg_job.created_on ".$sort_type; 	
			$sort_order="job_created_date";
		}
		$count_rows = DB::select(DB::raw("select count(*) as t_count from gpg_job LEFT JOIN gpg_job_doc ON (gpg_job.id=gpg_job_doc.gpg_job_id and gpg_job_doc.id = (SELECT id FROM gpg_job_doc WHERE gpg_job.id=gpg_job_doc.gpg_job_id ORDER BY gpg_job_doc.type ASC LIMIT 1)) where GPG_job_type_id='4' and job_num not like 'RNT%' $queryPart $queryPartContractNums "));
		if (!empty($count_rows) && isset($count_rows[0]->t_count)) {
			$results->totalItems = $count_rows[0]->t_count;
		}
		$getSales = DB::select(DB::raw("SELECT gpg_job.*,
	 							gpg_job_doc.RecCategory,
								IF(gpg_job_doc.created_on IS NULL,0,(IF(gpg_job_doc.type='Q',DATEDIFF(gpg_job_doc.created_on,(SELECT created_on FROM gpg_job_doc WHERE TYPE='R' AND gpg_job_doc.gpg_job_id = gpg_job.id LIMIT 1)),DATEDIFF(NOW(),gpg_job_doc.created_on))))  AS rec_days,	 							
								gpg_job_doc.GPG_asign_employee_id,
								(SELECT concat(invoice_number,'#~#',SUM(invoice_amount),'#~#',invoice_date,'#~#',SUM(tax_amount),'#~#',count(id)) FROM gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) AS invoice_data,
								(SELECT name FROM gpg_customer WHERE id = gpg_customer_id) AS customer_name, 
								(SELECT name FROM gpg_employee WHERE id = GPG_employee_id) AS sales_person_name , 
								(SELECT SUM(total_wage) FROM gpg_timesheet_detail , gpg_timesheet where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and job_num = gpg_job.job_num $queryPartLaborCost) AS labor_cost , 
								(SELECT SUM(amount) FROM gpg_job_cost WHERE job_num = gpg_job.job_num $queryPartMaterialCost) AS material_cost 
							FROM 
								gpg_job 
							LEFT JOIN 
								gpg_job_doc 
							ON 
								(gpg_job.id=gpg_job_doc.gpg_job_id and gpg_job_doc.id = (SELECT id FROM gpg_job_doc WHERE gpg_job.id=gpg_job_doc.gpg_job_id ORDER BY gpg_job_doc.type ASC LIMIT 1)) 
							Where 
								GPG_job_type_id='4'  AND job_num NOT LIKE 'RNT%' $queryPart $queryPartContractNums ".$queryPartOrder."  $limitOffset"));
	
		$data_arr = array();
		foreach ($getSales as $key => $value2) {
			foreach ($value2 as $key => $value) {
				if ($key == 'technicians'){
					$techs = array();
					$tec_arr = explode(', ', $value);
					$tec_arr = implode (", ", $tec_arr);
					if (!empty($tec_arr[0]))
						$techs = DB::select(DB::raw("select GROUP_CONCAT(name) as name from gpg_employee where id IN (".$tec_arr.")"));
					$strTech = '';
					foreach ($techs as $key => $value) {
						$strTech .= $value->name;
					}
					$temp_arr['technicians_str'] = substr($strTech,0,30)."...";
				}
				if ($key == 'GPG_asign_employee_id'){
					if (!empty($value) && is_numeric($value)){
						$emp_name =	DB::table('gpg_employee')->where('id','=',$value)->pluck('name');
						$temp_arr['emp_name'] = $emp_name;
					}
					else
						$temp_arr['emp_name'] = '-';
				}
				$temp_arr[$key] = $value;
			}
			$data_arr[] = $temp_arr;
		}
		$results->items = $data_arr;
		/*echo "<pre>";
		print_r($results->items);
		die();*/
		return $results;
	}
	/*
	* creatUpdataNotes
	*/
	public function creatUpdataNotes(){
		$job_id = Input::get('cjob_id');
		$CDate = Input::get('CDate');
		$contactPerson = Input::get('contactPerson');
		$contactDetails = Input::get('contactDetails');
		DB::table('gpg_job_note')->insert(array('gpg_job_id'=>$job_id,'notes'=>$contactDetails,'entered_by'=>$contactPerson,'dated'=>$CDate,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
		return Redirect::to('job/service_job_list');
	}

	/*
	* getnShowNotes
	*/
	public function getnShowNotes(){
		$id = Input::get('id');
		$str = '';
		$qry = DB::table('gpg_job_note')->select('*')->where('gpg_job_id','=',$id)->get();
		foreach ($qry as $key => $value) {
			$str .= '<tr><td>'.$value->notes.'</td><td><button name="delete_note" id="'.$value->id.'" class="btn btn-danger btn-xs">Delete</button></td></tr>';
		}
		return $str;
	}

	/*
	* attachJobNum
	*/
	public function attachJobNum(){
		$id = Input::get('vjob_id');
		$job_num = Input::get('jobNumberFind');
		$res = DB::table('gpg_job')->where('job_num','=',$job_num)->get();
		if (!empty($res)) {
			DB::table('gpg_job')->where('id','=',$id)->update(array('attach_job_num'=>$job_num));	
		}
		return Redirect::to('job/service_job_list');
	}

	/*
	* attachJobDate
	*/
	public function attachJobDate(){
		$id = Input::get('jjob_id');
		$field_name = Input::get('field_name');
		$get_date = Input::get('get_date');
		DB::table('gpg_job')->where('id','=',$id)->update(array($field_name=>$get_date));	
		return Redirect::to('job/service_job_list');
	}
	/*
	* creatUpdataJobFiles
	*/
	public function creatUpdataJobFiles(){
		$id = Input::get('fjob_id');
		$emp = Input::get('dropEmployee');
		$cat = Input::get('RecCategoryValue');
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
			 	$filename = "job_doc_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
				$destinationPath = public_path().'/img/';
				$uploadSuccess = $file->move($destinationPath, $filename);
				//insert into db
				DB::table('gpg_job_doc')->where('gpg_job_id','=',$id)->delete();
				DB::table('gpg_job_doc')->insert(array('gpg_job_id'=>$id,'filename'=>$filename ,'displayname'=>$file->getClientOriginalName(),'type'=>'R','GPG_asign_employee_id'=>$emp,'RecCategory'=>$cat,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));		
			}
		}
		return Redirect::to('job/service_job_list');
	}
	/*
	*getSJAttaches
	*/
	public function getSJAttaches(){
		$id = Input::get('id');
		$qry = DB::table('gpg_job_doc')->where('gpg_job_id','=',$id)->select('*')->get();
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr = array('file'=>$value->displayname,'cat'=>$value->RecCategory,'emp'=>$value->GPG_asign_employee_id);
		}
		return $data_arr;
	}

	/*
	* serviceCustomerView
	*/
	public function serviceCustomerView(){
		$modules = Generic::modules();
		$SDate =  Input::get("SDate");
		$EDate =  Input::get("EDate");
		$orderBy = Input::get("orderBy");
		$CNumberStart = Input::get("CNumberStart");
		$CNumberEnd = Input::get("CNumberEnd");
		$JNumberStart = Input::get("JNumberStart");
		$JNumberEnd = Input::get("JNumberEnd");
		$jobstatus = Input::get("jobstatus");
		$sortby = Input::get("sortby");
		$queryPart ='';
		$currentSDate = date('m/d/Y',strtotime('01/01/2012'));
		$currentEDate = date('m/d/Y');
		$complete = "";
		$columns = array();

		if (empty($SDate)) $SDate = $currentSDate;
		if (empty($EDate)) $EDate = $currentEDate;
		if (empty($orderBy)) $orderBy = 'gpg_over_head_budget.gpg_expense_gl_code_id';
		if ($SDate!="" && $EDate!=""){ 
		 	 $queryPart = " AND created_on >= '".date('Y-m-d',strtotime($SDate))."' AND created_on <= '".date('Y-m-d',strtotime($EDate))."' ";
		}
		if($CNumberStart!="" && $CNumberEnd==""){
			$queryPart .= " AND contract_number like '".$CNumberStart."%' ";
		}
		elseif($CNumberStart!="" && $CNumberEnd!=""){
			$queryPart .= " AND contract_number >= '".$CNumberStart."' AND  contract_number <= '".$CNumberEnd."'";
		}
		if($JNumberStart!="" && $JNumberEnd==""){
			$queryPart .= " AND job_num like '".$JNumberStart."%' ";
		}
		elseif($JNumberStart!="" && $JNumberEnd!=""){
			$queryPart .= " AND job_num >= '".$JNumberStart."' AND  job_num <= '".$JNumberEnd."'";
		}
		$jobstatus2="";
		if($jobstatus=='complete')
			$jobstatus2=1;
		elseif($jobstatus=='incomplete') 
			$jobstatus2=0;
		elseif($jobstatus=='all')
			$jobstatus2 = 'all';
		if($jobstatus2=="0" || $jobstatus2=="1"){
			 $queryPart .= " AND complete = '".$jobstatus2."'";
		}
		elseif($jobstatus2==""){
			$queryPart .= " AND complete = '0'";
			$jobstatus='incomplete';
		}
		if($sortby=="service_type"){
			$queryPart .= " ORDER BY task";
		}
		else{
			$queryPart .= " ORDER BY complete";
			$sortby='complete';
		}
		$select_columns = "";
		if(!is_null($columns) && in_array('invoice_data',$columns)){
			$select_columns .=",(SELECT
		     CONCAT(SUM(IFNULL(invoice_amount,0)))
		   FROM gpg_job_invoice_info
		   WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id
		   GROUP BY gpg_job_invoice_info.gpg_job_id) AS invoice_data";
		}
		if(!is_null($columns) && in_array('material_cost',$columns)){
			$select_columns .=",(SELECT SUM(amount) FROM gpg_job_cost WHERE job_num = gpg_job.job_num) AS material_cost";
		}
		if(!is_null($columns) && in_array('labor_cost',$columns)){
			$select_columns .=",(SELECT SUM(total_wage) FROM gpg_timesheet_detail , gpg_timesheet WHERE gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id AND gpg_timesheet_detail.job_num = gpg_job.job_num) AS labor_cost";
		}
		$qry = DB::select(DB::raw("SELECT (select name from gpg_customer where id = gpg_job.GPG_customer_id) as customer_name ,gpg_job.*".$select_columns." FROM gpg_job WHERE  1 ".$queryPart));
		$qry_data = array();
		$arr_data = array();
		$total_all = 0;
		$total_c = 0;
		$total_ic = 0;
		foreach($qry as  $key => $arr_res)
		{
			$customer_name = $arr_res->customer_name;
			$c_index = (ucwords($customer_name));
			$location_index = ($arr_res->location);
			$arr_data[$c_index][$location_index][$arr_res->job_num] = array(
																				'location'=>$arr_res->location,	
																				'complete'=>$arr_res->complete,
																				'task'=>$arr_res->task.($arr_res->sub_task?" - ".$arr_res->sub_task:""),
																				'contract_number'=>$arr_res->contract_number,
																			);
			if(isset($arr_res->invoice_data))
			{
				$arr_data[$c_index]['invoice_data'] += $arr_res->invoice_data;
				$arr_data[$c_index][$location_index][$arr_res->job_num]['invoice_data'] = $arr_res->invoice_data;
			}
			if(isset($arr_res->material_cost))
			{
				$arr_data[$c_index]['material_cost'] += $arr_res->material_cost;
				$arr_data[$c_index][$location_index][$arr_res->job_num]['material_cost'] = $arr_res->material_cost;
			}
			if(isset($arr_res->labor_cost))
			{
				$arr_data[$c_index]['labor_cost'] += $arr_res->labor_cost;
				$arr_data[$c_index][$location_index][$arr_res->job_num]['labor_cost'] = $arr_res->labor_cost;
			}
			if(!is_null($columns) && in_array('cost_to_dat',$columns))
			{
				$arr_data[$c_index]['cost_to_dat'] += $arr_res->cost_to_dat;
				$arr_data[$c_index][$location_index][$arr_res->job_num]['cost_to_dat'] = $arr_res->cost_to_dat;
			}

			$arr_data[$c_index]['cus_name'] = $customer_name;
			if (!isset($arr_data[$c_index]['service_types'][$arr_res->task])){
				$arr_data[$c_index]['service_types'][$arr_res->task]=0;
			}else
				$arr_data[$c_index]['service_types'][$arr_res->task]++;
			$arr_data[$c_index][$location_index]['location'] = $arr_res->location;
			$arr_data[$c_index][$location_index]['cus_name'] = $customer_name;
			if(!isset($arr_data[$c_index][$location_index]['count']['total'])){
				$arr_data[$c_index][$location_index]['count']['total'] = 0;
			}else
				$arr_data[$c_index][$location_index]['count']['total']++;
			if (!isset($arr_data[$c_index]['count']['total'])){
				$arr_data[$c_index]['count']['total'] = 0;
			}else
				$arr_data[$c_index]['count']['total']++;
			
			if($arr_res->complete)
			{
				$arr_data[$c_index][$location_index]['count']['complete']++;
				$arr_data[$c_index]['count']['complete']++;
				$total_c++;
			}
			else
			{
				if (!isset($arr_data[$c_index]['count']['incomplete'])){
					$arr_data[$c_index]['count']['incomplete'] = 0;
				}else
					$arr_data[$c_index]['count']['incomplete']++;
				if (!isset($arr_data[$c_index][$location_index]['count']['incomplete'])){
					$arr_data[$c_index][$location_index]['count']['incomplete'] = 0;
				}else	
					$arr_data[$c_index][$location_index]['count']['incomplete']++;
				$total_ic++;
			}
			$total_all++;
			ksort($arr_data[$c_index]);
		}
		ksort($arr_data);
		$params = array('left_menu' => $modules, 'qry_data'=>$arr_data,'total_all'=>$total_all,'total_c'=>$total_c,'total_ic'=>$total_ic);
 		return View::make('job.service_customer_view', $params);
	}
	
	/*
	* serviceCustomerExport
	*/
	public function serviceCustomerExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('ServiceJobCustomersExport', function($sheet) {
		    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
			$SDate =  Input::get("SDate");
		$EDate =  Input::get("EDate");
		$orderBy = Input::get("orderBy");
		$CNumberStart = Input::get("CNumberStart");
		$CNumberEnd = Input::get("CNumberEnd");
		$JNumberStart = Input::get("JNumberStart");
		$JNumberEnd = Input::get("JNumberEnd");
		$jobstatus = Input::get("jobstatus");
		$sortby = Input::get("sortby");
		$queryPart ='';
		$currentSDate = date('m/d/Y',strtotime('01/01/2012'));
		$currentEDate = date('m/d/Y');
		$complete = "";
		$columns = array();

		if (empty($SDate)) $SDate = $currentSDate;
		if (empty($EDate)) $EDate = $currentEDate;
		if (empty($orderBy)) $orderBy = 'gpg_over_head_budget.gpg_expense_gl_code_id';
		if ($SDate!="" && $EDate!=""){ 
		 	 $queryPart = " AND created_on >= '".date('Y-m-d',strtotime($SDate))."' AND created_on <= '".date('Y-m-d',strtotime($EDate))."' ";
		}
		if($CNumberStart!="" && $CNumberEnd==""){
			$queryPart .= " AND contract_number like '".$CNumberStart."%' ";
		}
		elseif($CNumberStart!="" && $CNumberEnd!=""){
			$queryPart .= " AND contract_number >= '".$CNumberStart."' AND  contract_number <= '".$CNumberEnd."'";
		}
		if($JNumberStart!="" && $JNumberEnd==""){
			$queryPart .= " AND job_num like '".$JNumberStart."%' ";
		}
		elseif($JNumberStart!="" && $JNumberEnd!=""){
			$queryPart .= " AND job_num >= '".$JNumberStart."' AND  job_num <= '".$JNumberEnd."'";
		}
		$jobstatus2="";
		if($jobstatus=='complete')
			$jobstatus2=1;
		elseif($jobstatus=='incomplete') 
			$jobstatus2=0;
		elseif($jobstatus=='all')
			$jobstatus2 = 'all';
		if($jobstatus2=="0" || $jobstatus2=="1"){
			 $queryPart .= " AND complete = '".$jobstatus2."'";
		}
		elseif($jobstatus2==""){
			$queryPart .= " AND complete = '0'";
			$jobstatus='incomplete';
		}
		if($sortby=="service_type"){
			$queryPart .= " ORDER BY task";
		}
		else{
			$queryPart .= " ORDER BY complete";
			$sortby='complete';
		}
		$select_columns = "";
		if(!is_null($columns) && in_array('invoice_data',$columns)){
			$select_columns .=",(SELECT
		     CONCAT(SUM(IFNULL(invoice_amount,0)))
		   FROM gpg_job_invoice_info
		   WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id
		   GROUP BY gpg_job_invoice_info.gpg_job_id) AS invoice_data";
		}
		if(!is_null($columns) && in_array('material_cost',$columns)){
			$select_columns .=",(SELECT SUM(amount) FROM gpg_job_cost WHERE job_num = gpg_job.job_num) AS material_cost";
		}
		if(!is_null($columns) && in_array('labor_cost',$columns)){
			$select_columns .=",(SELECT SUM(total_wage) FROM gpg_timesheet_detail , gpg_timesheet WHERE gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id AND gpg_timesheet_detail.job_num = gpg_job.job_num) AS labor_cost";
		}
		$qry = DB::select(DB::raw("SELECT (select name from gpg_customer where id = gpg_job.GPG_customer_id) as customer_name ,gpg_job.*".$select_columns." FROM gpg_job WHERE  1 ".$queryPart));
		$qry_data = array();
		$arr_data = array();
		$total_all = 0;
		$total_c = 0;
		$total_ic = 0;
		foreach($qry as  $key => $arr_res)
		{
			$customer_name = $arr_res->customer_name;
			$c_index = (ucwords($customer_name));
			$location_index = ($arr_res->location);
			$arr_data[$c_index][$location_index][$arr_res->job_num] = array(
																				'location'=>$arr_res->location,	
																				'complete'=>$arr_res->complete,
																				'task'=>$arr_res->task.($arr_res->sub_task?" - ".$arr_res->sub_task:""),
																				'contract_number'=>$arr_res->contract_number,
																			);
			if(isset($arr_res->invoice_data))
			{
				$arr_data[$c_index]['invoice_data'] += $arr_res->invoice_data;
				$arr_data[$c_index][$location_index][$arr_res->job_num]['invoice_data'] = $arr_res->invoice_data;
			}
			if(isset($arr_res->material_cost))
			{
				$arr_data[$c_index]['material_cost'] += $arr_res->material_cost;
				$arr_data[$c_index][$location_index][$arr_res->job_num]['material_cost'] = $arr_res->material_cost;
			}
			if(isset($arr_res->labor_cost))
			{
				$arr_data[$c_index]['labor_cost'] += $arr_res->labor_cost;
				$arr_data[$c_index][$location_index][$arr_res->job_num]['labor_cost'] = $arr_res->labor_cost;
			}
			if(!is_null($columns) && in_array('cost_to_dat',$columns))
			{
				$arr_data[$c_index]['cost_to_dat'] += $arr_res->cost_to_dat;
				$arr_data[$c_index][$location_index][$arr_res->job_num]['cost_to_dat'] = $arr_res->cost_to_dat;
			}

			$arr_data[$c_index]['cus_name'] = $customer_name;
			if (!isset($arr_data[$c_index]['service_types'][$arr_res->task])){
				$arr_data[$c_index]['service_types'][$arr_res->task]=0;
			}else
				$arr_data[$c_index]['service_types'][$arr_res->task]++;
			$arr_data[$c_index][$location_index]['location'] = $arr_res->location;
			$arr_data[$c_index][$location_index]['cus_name'] = $customer_name;
			if(!isset($arr_data[$c_index][$location_index]['count']['total'])){
				$arr_data[$c_index][$location_index]['count']['total'] = 0;
			}else
				$arr_data[$c_index][$location_index]['count']['total']++;
			if (!isset($arr_data[$c_index]['count']['total'])){
				$arr_data[$c_index]['count']['total'] = 0;
			}else
				$arr_data[$c_index]['count']['total']++;
			
			if($arr_res->complete)
			{
				$arr_data[$c_index][$location_index]['count']['complete']++;
				$arr_data[$c_index]['count']['complete']++;
				$total_c++;
			}
			else
			{
				if (!isset($arr_data[$c_index]['count']['incomplete'])){
					$arr_data[$c_index]['count']['incomplete'] = 0;
				}else
					$arr_data[$c_index]['count']['incomplete']++;
				if (!isset($arr_data[$c_index][$location_index]['count']['incomplete'])){
					$arr_data[$c_index][$location_index]['count']['incomplete'] = 0;
				}else	
					$arr_data[$c_index][$location_index]['count']['incomplete']++;
				$total_ic++;
			}
			$total_all++;
			ksort($arr_data[$c_index]);
		}
			ksort($arr_data);
			$params = array('qry_data'=>$arr_data,'total_all'=>$total_all,'total_c'=>$total_c,'total_ic'=>$total_ic);
 			$sheet->loadView('job.serviceCustomerExport',$params);
		  });
		})->export('xls');
	}

	/*
	* contractView
	*/
	public function contractView(){
		$modules = Generic::modules();
		$SDate =  Input::get("SDate");
	    $EDate =  Input::get("EDate");
	    $orderBy = Input::get("orderBy");
	    $CNumberStart = Input::get("CNumberStart");
	    $CNumberEnd = Input::get("CNumberEnd");
	    $JNumberStart = Input::get("JNumberStart");
	    $JNumberEnd = Input::get("JNumberEnd");
	    $jobstatus = Input::get("jobstatus");
	    $sortby = Input::get("sortby");
	    $queryPart ='';
	    $currentSDate = date('m/d/Y',strtotime('01/01/2012'));
	    $currentEDate = date('m/d/Y');
		$complete = "";
	    if (empty($SDate)) $SDate = $currentSDate;
	    if (empty($EDate)) $EDate = $currentEDate;
	    if (empty($orderBy)) $orderBy = 'gpg_over_head_budget.gpg_expense_gl_code_id';
	    if ($SDate!="" && $EDate!="") { 
	 	$queryPart = " AND created_on >= '".date('Y-m-d',strtotime($SDate))."' AND created_on <= '".date('Y-m-d',strtotime($EDate))."' ";
		}
		if($CNumberStart!="" && $CNumberEnd==""){
			$queryPart .= " AND contract_number like '".$CNumberStart."%' ";
		}
		elseif($CNumberStart!="" && $CNumberEnd!=""){
			$queryPart .= " AND contract_number >= '".$CNumberStart."' AND  contract_number <= '".$CNumberEnd."'";
		}
		if($JNumberStart!="" && $JNumberEnd==""){
			$queryPart .= " AND job_num like '".$JNumberStart."%' ";
		}
		elseif($JNumberStart!="" && $JNumberEnd!=""){
			$queryPart .= " AND job_num >= '".$JNumberStart."' AND  job_num <= '".$JNumberEnd."'";
		}
		$jobstatus2="";
		if($jobstatus=='complete')
			$jobstatus2=1;
		elseif($jobstatus=='incomplete') 
			$jobstatus2=0;
		elseif($jobstatus=='all')
			$jobstatus2 = 'all';
		if($jobstatus2=="0" || $jobstatus2=="1"){
			 $queryPart .= " AND complete = '".$jobstatus2."'";
		}
		elseif($jobstatus2==""){
			$queryPart .= " AND complete = '0'";
			$jobstatus='incomplete';
		}
		if($sortby=="service_type"){
			$queryPart .= " ORDER BY task";
		}
		else{
			$queryPart .= " ORDER BY complete";
			$sortby='complete';
		}
		$qry = DB::select(DB::raw("SELECT (select name from gpg_customer where id = gpg_job.GPG_customer_id) as customer_name , gpg_job.* FROM gpg_job WHERE contract_number IS NOT NULL AND contract_number != '' ".$queryPart));
		$arr_data = array();
		$total_all = 0;
		$total_c = 0;
		$total_ic = 0;
		foreach ($qry as $key => $arr_res) {
			$customer_name = $arr_res->customer_name;
			$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id][$arr_res->job_num] = array(
														'location'=>$arr_res->location,	
														'complete'=>$arr_res->complete,
														'task'=>$arr_res->task.($arr_res->sub_task?" - ".$arr_res->sub_task:"")
														) ;
			$arr_data[$arr_res->contract_number]['cus_name'] = $customer_name.",";
			if (!isset($arr_data[$arr_res->contract_number]['service_types'][$arr_res->task])){
				$arr_data[$arr_res->contract_number]['service_types'][$arr_res->task]=0;
			}else
				$arr_data[$arr_res->contract_number]['service_types'][$arr_res->task]++;
			$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['cus_name'] = $customer_name;
			if (!isset($arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['total'])){
				$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['total']=0;
			}else
				$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['total']++;
			if (!isset($arr_data[$arr_res->contract_number]['count']['total'])){
					$arr_data[$arr_res->contract_number]['count']['total']=0;
			}else		
				$arr_data[$arr_res->contract_number]['count']['total']++;
			if($arr_res->complete){
				if (!isset($arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['complete'])){
					$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['complete']=0;
				}else
					$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['complete']++;
				if (!isset($arr_data[$arr_res->contract_number]['count']['complete'])) {
					$arr_data[$arr_res->contract_number]['count']['complete']=0;
				}else	
					$arr_data[$arr_res->contract_number]['count']['complete']++;
				$total_c++;
			}
			else{
				if (!isset($arr_data[$arr_res->contract_number]['count']['incomplete'])){
					$arr_data[$arr_res->contract_number]['count']['incomplete']=0;
				}else
					$arr_data[$arr_res->contract_number]['count']['incomplete']++;
				if (!isset($arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['incomplete'])) {
					$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['incomplete']=0;		
				}else		
					$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['incomplete']++;
				$total_ic++;
			}
			$total_all++;
		}
		$params = array('left_menu' => $modules, 'qry_data'=>$arr_data,'total_all'=>$total_all,'total_c'=>$total_c,'total_ic'=>$total_ic);
 		return View::make('job.contract_view', $params);
	}

	/*
	*serviceContractExport
	*/
	public function serviceContractExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('ServiceJobContractsExport', function($sheet) {
		    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		$SDate =  Input::get("SDate");
	    $EDate =  Input::get("EDate");
	    $orderBy = Input::get("orderBy");
	    $CNumberStart = Input::get("CNumberStart");
	    $CNumberEnd = Input::get("CNumberEnd");
	    $JNumberStart = Input::get("JNumberStart");
	    $JNumberEnd = Input::get("JNumberEnd");
	    $jobstatus = Input::get("jobstatus");
	    $sortby = Input::get("sortby");
	    $queryPart ='';
	    $currentSDate = date('m/d/Y',strtotime('01/01/2012'));
	    $currentEDate = date('m/d/Y');
		$complete = "";
	    if (empty($SDate)) $SDate = $currentSDate;
	    if (empty($EDate)) $EDate = $currentEDate;
	    if (empty($orderBy)) $orderBy = 'gpg_over_head_budget.gpg_expense_gl_code_id';
	    if ($SDate!="" && $EDate!="") { 
	 	$queryPart = " AND created_on >= '".date('Y-m-d',strtotime($SDate))."' AND created_on <= '".date('Y-m-d',strtotime($EDate))."' ";
		}
		if($CNumberStart!="" && $CNumberEnd==""){
			$queryPart .= " AND contract_number like '".$CNumberStart."%' ";
		}
		elseif($CNumberStart!="" && $CNumberEnd!=""){
			$queryPart .= " AND contract_number >= '".$CNumberStart."' AND  contract_number <= '".$CNumberEnd."'";
		}
		if($JNumberStart!="" && $JNumberEnd==""){
			$queryPart .= " AND job_num like '".$JNumberStart."%' ";
		}
		elseif($JNumberStart!="" && $JNumberEnd!=""){
			$queryPart .= " AND job_num >= '".$JNumberStart."' AND  job_num <= '".$JNumberEnd."'";
		}
		$jobstatus2="";
		if($jobstatus=='complete')
			$jobstatus2=1;
		elseif($jobstatus=='incomplete') 
			$jobstatus2=0;
		elseif($jobstatus=='all')
			$jobstatus2 = 'all';
		if($jobstatus2=="0" || $jobstatus2=="1"){
			 $queryPart .= " AND complete = '".$jobstatus2."'";
		}
		elseif($jobstatus2==""){
			$queryPart .= " AND complete = '0'";
			$jobstatus='incomplete';
		}
		if($sortby=="service_type"){
			$queryPart .= " ORDER BY task";
		}
		else{
			$queryPart .= " ORDER BY complete";
			$sortby='complete';
		}
		$qry = DB::select(DB::raw("SELECT (select name from gpg_customer where id = gpg_job.GPG_customer_id) as customer_name , gpg_job.* FROM gpg_job WHERE contract_number IS NOT NULL AND contract_number != '' ".$queryPart));
		$arr_data = array();
		$total_all = 0;
		$total_c = 0;
		$total_ic = 0;
		foreach ($qry as $key => $arr_res) {
			$customer_name = $arr_res->customer_name;
			$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id][$arr_res->job_num] = array(
														'location'=>$arr_res->location,	
														'complete'=>$arr_res->complete,
														'task'=>$arr_res->task.($arr_res->sub_task?" - ".$arr_res->sub_task:"")
														) ;
			$arr_data[$arr_res->contract_number]['cus_name'] = $customer_name.",";
			if (!isset($arr_data[$arr_res->contract_number]['service_types'][$arr_res->task])){
				$arr_data[$arr_res->contract_number]['service_types'][$arr_res->task]=0;
			}else
				$arr_data[$arr_res->contract_number]['service_types'][$arr_res->task]++;
			$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['cus_name'] = $customer_name;
			if (!isset($arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['total'])){
				$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['total']=0;
			}else
				$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['total']++;
			if (!isset($arr_data[$arr_res->contract_number]['count']['total'])){
					$arr_data[$arr_res->contract_number]['count']['total']=0;
			}else		
				$arr_data[$arr_res->contract_number]['count']['total']++;
			if($arr_res->complete){
				if (!isset($arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['complete'])){
					$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['complete']=0;
				}else
					$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['complete']++;
				if (!isset($arr_data[$arr_res->contract_number]['count']['complete'])) {
					$arr_data[$arr_res->contract_number]['count']['complete']=0;
				}else	
					$arr_data[$arr_res->contract_number]['count']['complete']++;
				$total_c++;
			}
			else{
				if (!isset($arr_data[$arr_res->contract_number]['count']['incomplete'])){
					$arr_data[$arr_res->contract_number]['count']['incomplete']=0;
				}else
					$arr_data[$arr_res->contract_number]['count']['incomplete']++;
				if (!isset($arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['incomplete'])) {
					$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['incomplete']=0;		
				}else		
					$arr_data[$arr_res->contract_number][$arr_res->GPG_customer_id]['count']['incomplete']++;
				$total_ic++;
			}
			$total_all++;
		}
			$params = array('qry_data'=>$arr_data,'total_all'=>$total_all,'total_c'=>$total_c,'total_ic'=>$total_ic);
			$sheet->loadView('job.serviceContractExport',$params);
		  });
		})->export('xls');
	}

	/*
	* jobManagement
	*/
	public function jobManagement(){
		$modules = Generic::modules();
		$allInputs = Input::except('_token');
		# flash input fields to re-populate (state maintain) search form		
		Input::flash();		
		$page = Input::get('page', 1);
   		$data = $this->getManagedJobs($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		
  		$total_jobs = DB::table('gpg_job')->count('id');
  		$comp_jobs = DB::table('gpg_job')->where('complete','=','1')->count('id');
  		$assign_jobs = DB::table('gpg_job')->where('status','=','A')->count('id');
  		$uassign_jobs = DB::table('gpg_job')->where('status','=','N')->count('id');
		$params = array('left_menu' => $modules,'allInputs'=>$allInputs,'qry_data'=>$query_data,'total_jobs'=>$total_jobs,'comp_jobs'=>$comp_jobs,'assign_jobs'=>$assign_jobs,'uassign_jobs'=>$uassign_jobs);
 		return View::make('job.job_management', $params);
	}
	public function getManagedJobs($page = 1, $limit = null){
		$results = new \StdClass;
		$results->page = $page;
    	$limitOffset = '';
	    if($limit != null){
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}
		$results->totalItems = 0;
		$results->items = array();

		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$status = Input::get("status");
		Input::flash();
		$DSQL = "";
		$DQ2 = " order by created_on desc ";
		if ($SDate!="" || $EDate!="") {
		    if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND DATE_FORMAT(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (created_on >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
			            AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
		}
		if ($Filter!="" && ($FVal!="" || $status!="")) {
		   if ($Filter !="status" and $Filter!="new_member") 
		   $DSQL.= " AND $Filter like '%$FVal%'"; 
		   elseif ($Filter =="status" && $status=="1") 
		   	$DSQL.= " AND GPG_employee_id != ''";
		   elseif ($Filter =="status" && $status=="2") 
		   	$DSQL.= " AND ISNULL(GPG_employee_id)"; 
		}
		$count = DB::select(DB::raw("select count(id) as count_id from gpg_job WHERE 1 $DSQL"));
		if(!empty($count) && isset($count[0]->count_id))
			$results->totalItems = $count[0]->count_id;
		$qry = DB::select(DB::raw("select * from gpg_job WHERE 1 $DSQL $DQ2 $limitOffset"));
		$data_arr = array();
		foreach ($qry as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				if ($key == 'id') {
					$assigned_to = DB::table('gpg_employee_job')->where('gpg_job_id','=',$value)->pluck('gpg_employee_id');
					$temp_arr['assigned_to'] = $assigned_to;
				}
				if ($key == 'GPG_job_type_id') {
					$gpg_job_type = DB::table('gpg_job_type')->where('id','=',$value)->pluck('name');		
					$temp_arr['GPG_job_type_name'] = $gpg_job_type;
				}
				if ($key == 'GPG_wage_plan_id') {
					$price = DB::table('gpg_wage_plan')->where('id','=',$value)->pluck('price');
					$temp_arr['price'] = $price;
				}
				if ($key == 'GPG_customer_id'){
					$name = DB::table('gpg_customer')->where('id','=',$value)->pluck('name');
					$temp_arr['gpg_customer_name'] = $name;
				}
				$temp_arr[$key] = $value;
			}
			$data_arr[] = $temp_arr;
		}
		$results->items = $data_arr;
		return $results;
	}

	/*
	* jobCalendar
	*/
	public function jobCalendar(){
		$modules = Generic::modules();
		$employeeFilter = Input::get("employeeFilter");
		$techniciansFilter = Input::get("techniciansFilter");
		$jobTypeFilter = Input::get("jobTypeFilter");
		if(!isset($slm)){
			$slm = "";
		}
		if(!isset($sly)){
			$sly = "";
		}
		if(!isset($group_view)){
			$group_view = "";
		}
		$fg = "";
		$fg1 = "";
		$fg2 = "";
		if(isset($_REQUEST["m"]))
			$m = $_REQUEST["m"];
		else
			$m = ($slm) ? $slm : date('m');

		if(isset($_REQUEST["y"]))
			$y = $_REQUEST["y"];
		else
			$y = ($sly) ? $sly : date('Y');
		$prevY = $y;
		$nextY = $y;
		if ($m<=1) { $prevM=12; $prevY--; }
		else $prevM = $m-1;
		if ($m>=12) { $nextM=1; $nextY++; }
		else $nextM = $m+1;
		$year_display = 20;
		$days = array("Sun"=>7, "Mon"=>1, "Tue"=>2, "Wed"=>3, "Thu"=>4, "Fri"=>5, "Sat"=>6);
		$jobsQuery = "SELECT 
					gpg_job.id as job_id,
					gpg_job.schedule_date AS dated, 
					gpg_job.job_num, 
					(SELECT NAME FROM gpg_employee WHERE id = gpg_job.GPG_employee_id) AS assigned_to,
					(SELECT NAME FROM gpg_customer WHERE id = gpg_job.GPG_customer_id) AS customer,
					(SELECT NAME FROM gpg_job_type WHERE id = gpg_job.GPG_job_type_id) AS job_type,	
					(SELECT count(gpg_attach_job_num) FROM gpg_field_service_work WHERE gpg_attach_job_num = gpg_job.job_num) AS IsJobAttach,	
					gpg_job.complete AS job_status,
					gpg_job.date_completion,
					gpg_job.task,
					gpg_job.technicians,
					(SELECT COUNT(*) FROM gpg_job_project WHERE GPG_job_num = gpg_job.job_num) AS has_project,
					((SELECT COUNT(gpg_job_id) FROM gpg_job_doc WHERE TYPE = 'R' AND IFNULL(filename,'') <> '' AND gpg_job_id = gpg_job.id LIMIT 0,1) > 0 AND (SELECT COUNT(gpg_job_id) FROM gpg_job_doc WHERE TYPE = 'Q' AND IFNULL(filename,'') <> '' AND gpg_job_id = gpg_job.id LIMIT 0,1)=0) AS has_recommendations
				FROM gpg_job 
				WHERE 
					gpg_job.schedule_date >= '$y-$m-1' AND gpg_job.schedule_date <= '$nextY-$nextM-1'".
					(!empty($employeeFilter)?" AND GPG_employee_id = '$employeeFilter'":'').
					(!empty($techniciansFilter)?" AND concat(',',technicians,',') like '%,$techniciansFilter,%'":'').
					(!empty($jobTypeFilter)?" AND GPG_job_type_id = '$jobTypeFilter'":'')." HAVING has_project = 0";
		$result = DB::select(DB::raw($jobsQuery));		
		$projectsQuery = "SELECT
					gjp.GPG_job_num as job_num, 
					gjp.start_date, 
					gjp.end_date,
					gjp.title AS task,
					gjp.GPG_employee_id as technicians,
					gjp.completed AS job_status,
					gjp.include_days,
					gjp.completed_date as date_completion,
					(SELECT COUNT(gpg_attach_job_num) FROM gpg_field_service_work WHERE gpg_attach_job_num = gj.job_num) AS IsJobAttach,
					(SELECT NAME FROM gpg_customer WHERE id = gj.GPG_customer_id) AS customer,
					(SELECT NAME FROM gpg_job_type WHERE id = gj.GPG_job_type_id) AS job_type,
					((SELECT COUNT(gpg_job_id) FROM gpg_job_doc WHERE TYPE = 'R' AND IFNULL(filename,'') <> '' AND gpg_job_id = gj.id LIMIT 0,1) > 0 AND (SELECT COUNT(gpg_job_id) FROM gpg_job_doc WHERE TYPE = 'Q' AND IFNULL(filename,'') <> '' AND gpg_job_id = gj.id LIMIT 0,1)=0) AS has_recommendations
				FROM 
					gpg_job_project gjp,
					gpg_job gj
				WHERE
					gj.id = gjp.GPG_job_id AND 
					(
						(gjp.start_date BETWEEN '$y-$m-1' AND '$nextY-$nextM-1')   OR 
						(gjp.end_date BETWEEN '$y-$m-1' AND '$nextY-$nextM-1')     OR
						('$y-$m-1' BETWEEN gjp.start_date AND gjp.end_date) OR
						('$nextY-$nextM-1' BETWEEN gjp.start_date AND gjp.end_date)
					)";
		$result1 = DB::select(DB::raw($projectsQuery));		
		$events_arr = array();
		foreach ($result as $key => $value) {
			$e = array();
		    $e['title'] = substr("Technicians:".'('.$value->job_num.') '.preg_replace('/[^,;a-zA-Z0-9_-]|[,;]$/s', '', $value->customer).' '.preg_replace('/[^,;a-zA-Z0-9_-]|[,;]$/s', '', $value->task), 0, 35);
		    $e['start'] = date('Y-m-d',strtotime($value->dated));
		    $e['url'] = url('job/job_form/'.$value->job_id.'/'.$value->job_num);
		    array_push($events_arr, $e);
		}
		$allInputs = Input::except('_token');
		Input::flash();		
		$gpg_job_type = DB::table('gpg_job_type')->orderBy('name')->lists('name','id');
		$gpg_employee = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'gpg_job_type'=>$gpg_job_type,'gpg_employee'=>$gpg_employee,'events'=>($events_arr));
 		return View::make('job/job_calendar', $params)->withInput($allInputs);
	}

	/*
	* jobCalendarView
	*/
	public function jobCalendarView(){
		$modules = Generic::modules();
		$employeeFilter = Input::get("employeeFilter");
		$techniciansFilter = Input::get("techniciansFilter");
		$jobTypeFilter = Input::get("jobTypeFilter");
		$group_view = Input::get('group_view'); //  customer or job_type
		if($group_view=="")
		 	$group_view = 'customer';
		if(isset($_REQUEST["m"]))
			$m = $_REQUEST["m"];
		else
			$m = !empty($slm) ? $slm : date('m');
		if(isset($_REQUEST["y"]))
			$y = $_REQUEST["y"];
		else
			$y = !empty($sly) ? $sly : date('Y');
		$prevY = $y;
		$nextY = $y;
		if ($m<=1) { $prevM=12; $prevY--; }
		else $prevM = $m-1;
		if ($m>=12) { $nextM=1; $nextY++; }
		else $nextM = $m+1;
		$year_display = 20;
		$days = array("Sun"=>7, "Mon"=>1, "Tue"=>2, "Wed"=>3, "Thu"=>4, "Fri"=>5, "Sat"=>6);
		$jobsQuery = "SELECT 
				gpg_job.schedule_date AS dated, 
				gpg_job.job_num, 
				(SELECT NAME FROM gpg_employee WHERE id = gpg_job.GPG_employee_id) AS assigned_to,
				(SELECT NAME FROM gpg_customer WHERE id = gpg_job.GPG_customer_id) AS customer,
				(SELECT NAME FROM gpg_job_type WHERE id = gpg_job.GPG_job_type_id) AS job_type,	
				(SELECT count(gpg_attach_job_num) FROM gpg_field_service_work WHERE gpg_attach_job_num = gpg_job.job_num) AS IsJobAttach,	
				gpg_job.complete AS job_status,
				gpg_job.date_completion,
				gpg_job.task,
				gpg_job.technicians,
				(SELECT COUNT(*) FROM gpg_job_project WHERE GPG_job_num = gpg_job.job_num) AS has_project,
				((SELECT COUNT(gpg_job_id) FROM gpg_job_doc WHERE TYPE = 'R' AND IFNULL(filename,'') <> '' AND gpg_job_id = gpg_job.id LIMIT 0,1) > 0 AND (SELECT COUNT(gpg_job_id) FROM gpg_job_doc WHERE TYPE = 'Q' AND IFNULL(filename,'') <> '' AND gpg_job_id = gpg_job.id LIMIT 0,1)=0) AS has_recommendations
			FROM gpg_job 
			WHERE 
				gpg_job.schedule_date >= '$y-$m-1' AND gpg_job.schedule_date <= '$nextY-$nextM-1'
				".
				(!empty($employeeFilter)?" AND GPG_employee_id = '$employeeFilter'":'').
				(!empty($techniciansFilter)?" AND concat(',',technicians,',') like '%,$techniciansFilter,%'":'').
				(!empty($jobTypeFilter)?" AND GPG_job_type_id = '$jobTypeFilter'":'')." HAVING has_project = 0";
		if($group_view=='customer')
			$str_whr = " order by gpg_job.GPG_customer_id";
		if($group_view=='assigned_to')
			$str_whr = " order by assigned_to";
		$result = DB::select(DB::raw($jobsQuery));
		$events_arr = array();
		foreach ($result as $key => $value) {
			$e = array();
		    $e['title'] = substr(preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($value->customer, ENT_QUOTES)).'('.$value->job_num.') '.' '.preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($value->task, ENT_QUOTES)), 0, 35);
		    $e['start'] = date('Y-m-d',strtotime($value->dated));
		    array_push($events_arr, $e);
		}
		$gpg_job_type = DB::table('gpg_job_type')->orderBy('name')->lists('name','id');
		$gpg_employee = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
		$allInputs = Input::except('_token');
		Input::flash();
		$params = array('left_menu' => $modules,'gpg_job_type'=>$gpg_job_type,'gpg_employee'=>$gpg_employee,'events'=>($events_arr));
 		return View::make('job.job_calendar_view', $params)->withInput($allInputs);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteJobs()
	{
		$id = Input::get('id');
		$jobNum = DB::table('gpg_job')->select('job_num')->where('id','=',$id)->get();
		DB::table('gpg_employee_job')->where('gpg_job_id', '=',$id)->delete();
		DB::table('gpg_job_assigned_hist')->where('GPG_job_id', '=',$id)->delete();
		DB::table('gpg_timesheet_detail')->where('GPG_job_id', '=',$id)->delete();
		DB::table('gpg_sales_tracking_job')->where('gpg_job_id', '=',$id)->delete();
		DB::table('gpg_job_billing_note')->where('gpg_job_id', '=',$id)->delete();
		DB::table('gpg_job_rates')->where('job_number', '=',$jobNum[0]->job_num)->delete();
		DB::table('gpg_job_doc')->where('gpg_job_id', '=',$id)->delete();
		Gpg_job::find($id)->delete();
		return 1;
	}
	/*
	* destroyJobCost
	*/
	public function destroyJobCost(){
		$id = Input::get('id');
		$res = DB::table('gpg_job_cost')->select('job_num', 'amount')->where('id','=',$id)->get();
		if(isset($res) && !empty($res[0])){
			DB::table('gpg_job_cost')->where('id','=',$id)->delete();
			$cost_to_dat = DB::table('gpg_job')->where('job_num','=',$res[0]->job_num)->pluck('cost_to_dat');
			DB::table('gpg_job')->where('job_num','=',$res[0]->job_num)->update(array('cost_to_dat'=>$cost_to_dat-$res[0]->amount));
			return Redirect::to('job/job_cost_manage')->withSuccess('Record deleted Successfully');
		}
		return Redirect::to('job/job_cost_manage')->withErrors('Error Occured!');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
