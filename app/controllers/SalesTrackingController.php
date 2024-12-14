<?php

class SalesTrackingController extends \BaseController {

	protected $saleTypeArray = array( "PMcontract" => "PM contract", "Rental" => "Rental", "ServiceQuote" => "Service Quote", "Electrical" => "Electrical", "Generators" => "Generators", "Shop" => "Shop", "Permits" => "Permits", "Parts" => "Parts", "OtherDesc" => "Other: Desc", "Grassivy" => "Grassivy", "Special_Project" => "Special Project" );
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getDataByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$gpg_customers = DB::table('gpg_customer')->lists('name','id');
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		$allInputs = Input::except('_token');
		Input::flash();
		$params = array('left_menu' => $modules,'salesp_arr'=>$salesp_arr,'query_data'=>$query_data,'saleTypeArray'=>$this->saleTypeArray,'gpg_customers'=>$gpg_customers);
 		return View::make('salestracking.index', $params)->withInput($allInputs);
	}
	public function getDataByPage($page = 1, $limit = null)
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
	    $queryPart ='';
	    $results->totalItems = 0;
	    $results->items = array();
	    $UserId = 1;
		$SDate =  Input::get("SDate");
		$EDate =  Input::get("EDate");
		$LeadNumberStart = Input::get("LeadNumberStart");
		$LeadNumberEnd = Input::get("LeadNumberEnd");
		$jobNumber= Input::get("jobNumber");
		$StatusSDate = Input::get("StatusSDate");
		$StatusEDate = Input::get("StatusEDate");
		$ActivitySDate = Input::get("ActivitySDate");
		$ActivityEDate = Input::get("ActivityEDate");
		$CloseSDate = Input::get("CloseSDate");
		$CloseEDate = Input::get("CloseEDate");
		$optEmployee =  Input::get("optEmployee");
		$optStatus =  Input::get("optStatus");
		$optDisplay = Input::get("optDisplay");
		$optSort = Input::get("optSort");
		$quoteNum="";
		if ($optDisplay=="") $queryPart = " AND ifnull(soft_delete,'') <> 1 ";
		else $queryPart = " AND ifnull(soft_delete,'') = 1 ";
		if ($SDate!="" and $EDate!="") $queryPart .= " AND enter_date >= '".date('Y-m-d',strtotime($SDate))."' AND enter_date <= '".date('Y-m-d',strtotime($EDate))."' ";
		elseif ($SDate!="") $queryPart .= " AND enter_date = '".date('Y-m-d',strtotime($SDate))."'";
		if ($StatusSDate!="" and $StatusEDate!="") $queryPart .= " AND status_change_date >= '".date('Y-m-d',strtotime($StatusSDate))."' AND status_change_date <= '".date('Y-m-d',strtotime($StatusEDate))."' ";
		elseif ($StatusSDate!="") $queryPart .= " AND status_change_date = '".date('Y-m-d',strtotime($SDate))."'";
		if ($ActivitySDate!="" and $ActivityEDate!="") $queryPart .= "AND id IN (select gpg_sales_tracking_id from gpg_sales_tracking_contact where dated >= '".date('Y-m-d',strtotime($ActivitySDate))."' AND dated <= '".date('Y-m-d',strtotime($ActivityEDate))."')";
		elseif ($ActivitySDate!="") $queryPart .= " AND id IN (select gpg_sales_tracking_id from gpg_sales_tracking_contact where dated='".date('Y-m-d',strtotime($ActivitySDate))."')";
		if ($CloseSDate!="" and $CloseEDate!="") $queryPart .= " AND close_date >= '".date('Y-m-d',strtotime($CloseSDate))."' AND close_date <= '".date('Y-m-d',strtotime($CloseEDate))."' ";
		elseif ($CloseSDate!="") $queryPart .= " AND close_date = '".date('Y-m-d',strtotime($CloseSDate))."'";
		if ($LeadNumberStart!="" and $LeadNumberEnd!="") $queryPart .= " AND  id >= '$LeadNumberStart' AND id <= '$LeadNumberEnd' ";
		elseif ($LeadNumberStart!="") $queryPart .= " AND id = '$LeadNumberStart'";
		if ($optEmployee!="") $queryPart .= " AND gpg_employee_id = '$optEmployee' ";
		if ($optStatus!="") $queryPart .= " AND status = '$optStatus' ";
		if ($UserId != 1) {
		    $getAdmin = DB::table('gpg_ad_acc')->where('uname','=','admin')->pluck('ad_id');
		    $queryPart .= " AND (modified_by = '$getAdmin' OR modified_by = '$UserId') ";
		}
		if ($jobNumber!="") {
		    $gpg_job_id = DB::table('gpg_job')->where('job_num','=',$jobNumber)->pluck('id');
		    $queryPart .= " AND id IN (select c.gpg_sales_tracking_id from gpg_sales_tracking_job c where c.gpg_job_id = '$gpg_job_id')";
		}
		if ($optSort=="") $optSort = "id";
		$queryPart .= " order by $optSort ";
		$count = DB::select(DB::raw("select count(*) as t_count,(select concat(contact_details,'#~#',dated) from gpg_sales_tracking_contact where gpg_sales_tracking_id = gpg_sales_tracking.id order by gpg_sales_tracking_contact.dated desc limit 0,1) as activity_data ,(select name from gpg_customer where id = gpg_customer_id) as customer_name, (select name from gpg_employee where id = gpg_employee_id) as sales_person_name,
						  IFNULL((SELECT job_num FROM gpg_job, gpg_sales_tracking_job
						  WHERE gpg_job.id = gpg_sales_tracking_job.gpg_job_id
						  AND gpg_sales_tracking_job.gpg_sales_tracking_id = gpg_sales_tracking.id
						  AND gpg_sales_tracking_job.gpg_sales_tracking_type_of_sale = gpg_sales_tracking.type_of_sale LIMIT 1),
						  IFNULL((SELECT job_num FROM gpg_job, gpg_sales_tracking_shop_work_job
						  WHERE gpg_job.id = gpg_sales_tracking_shop_work_job.gpg_job_id
						  AND gpg_sales_tracking_shop_work_job.gpg_sales_tracking_id = gpg_sales_tracking.id LIMIT 1),
						  (SELECT job_num FROM gpg_job, gpg_sales_tracking_rental
						  WHERE gpg_job.id = gpg_sales_tracking_rental.gpg_job_id
						  AND gpg_sales_tracking_rental.gpg_sales_tracking_id = gpg_sales_tracking.id LIMIT 1)))
						  AS job_number from gpg_sales_tracking where 1 $queryPart"));
		if (!empty($count) && isset($count[0]->t_count)) {
			$results->totalItems = 	$count[0]->t_count;
		}	
		$getSales = DB::select(DB::raw("select *,(select concat(contact_details,'#~#',dated) from gpg_sales_tracking_contact where gpg_sales_tracking_id = gpg_sales_tracking.id order by gpg_sales_tracking_contact.dated desc limit 0,1) as activity_data ,(select name from gpg_customer where id = gpg_customer_id) as customer_name, (select name from gpg_employee where id = gpg_employee_id) as sales_person_name,
				  IFNULL((SELECT job_num FROM gpg_job, gpg_sales_tracking_job
				  WHERE gpg_job.id = gpg_sales_tracking_job.gpg_job_id
				  AND gpg_sales_tracking_job.gpg_sales_tracking_id = gpg_sales_tracking.id
				  AND gpg_sales_tracking_job.gpg_sales_tracking_type_of_sale = gpg_sales_tracking.type_of_sale LIMIT 1),
				  IFNULL((SELECT job_num FROM gpg_job, gpg_sales_tracking_shop_work_job
				  WHERE gpg_job.id = gpg_sales_tracking_shop_work_job.gpg_job_id
				  AND gpg_sales_tracking_shop_work_job.gpg_sales_tracking_id = gpg_sales_tracking.id LIMIT 1),
				  (SELECT job_num FROM gpg_job, gpg_sales_tracking_rental
				  WHERE gpg_job.id = gpg_sales_tracking_rental.gpg_job_id
				  AND gpg_sales_tracking_rental.gpg_sales_tracking_id = gpg_sales_tracking.id LIMIT 1)))
				  AS job_number from gpg_sales_tracking where 1 $queryPart $limitOffset"));
		$query_data = array();
		foreach ($getSales as $key => $value){
			$query_data[] = (array)$value;
		}
		$results->items = $query_data;
		return $results;
	}
	/*
	* deletedSalesTracking
	*/
	public function deletedSalesTracking(){
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getDSTDataByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$allInputs = Input::except('_token');
		Input::flash();
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
 		return View::make('salestracking.delete_salestracking', $params)->withInput($allInputs);
	}
	public function getDSTDataByPage($page = 1, $limit = null)
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
	    $queryPart ='';
	    $results->totalItems = 0;
	    $results->items = array();
	    $optDisplay  = "";
	    $queryPart = '';
	    if ($optDisplay=="") $queryPart = " AND ifnull(soft_delete,'') = 1 ";
	    $count = DB::select(DB::raw("select count(*) as t_count from gpg_sales_tracking where 1 $queryPart "));
	    if (!empty($count) && isset($count[0]->t_count)) {
			$results->totalItems = 	$count[0]->t_count;
		}
		$getSales = DB::select(DB::raw("select * from gpg_sales_tracking where 1 $queryPart $limitOffset"));
		$data_arr = array();
		foreach ($getSales as $key => $value) {
			$data_arr[] = (array)$value;
		}	
		$results->items = $data_arr;
	    return $results;
	}

	/*
	* restoreSTR
	*/
	public function restoreSTR(){
		$id = Input::get('id');
		DB::table('gpg_sales_tracking')->where('id','=',$id)->update(array('soft_delete'=>'NULL'));
		return 1;
	}

	/*
	*listQuotePhase
	*/
	public function listQuotePhase(){
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getQuoteDataByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$gpg_customers = DB::table('gpg_customer')->lists('name','id');
		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$gpg_customers = DB::table('gpg_customer')->lists('name','id');
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		$allInputs = Input::except('_token');
		Input::flash();
		$params = array('left_menu' => $modules,'salesp_arr'=>$salesp_arr,'query_data'=>$query_data,'gpg_customers'=>$gpg_customers,'saleTypeArray'=>$this->saleTypeArray);
 		return View::make('salestracking.index_quote_phase', $params)->withInput($allInputs);
	}
	public function getQuoteDataByPage($page = 1, $limit = null)
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
	    $queryPart ='';
	    $results->totalItems = 0;
	    $results->items = array();
	    $UserId = 1;
		$SDate =  Input::get("SDate");
		$EDate =  Input::get("EDate");
		$LeadNumberStart = Input::get("LeadNumberStart");
		$LeadNumberEnd = Input::get("LeadNumberEnd");
		$jobNumber= Input::get("jobNumber");
		$StatusSDate = Input::get("StatusSDate");
		$StatusEDate = Input::get("StatusEDate");
		$ActivitySDate = Input::get("ActivitySDate");
		$ActivityEDate = Input::get("ActivityEDate");
		$CloseSDate = Input::get("CloseSDate");
		$CloseEDate = Input::get("CloseEDate");
		$optEmployee =  Input::get("optEmployee");
		$optStatus =  Input::get("optStatus");
		$optDisplay = Input::get("optDisplay");
		$optSort = Input::get("optSort");
		$adPath = ""; // new defined 
 		$flagF = ""; //new defined
 		if ($optDisplay=="") $queryPart = " AND ifnull(soft_delete,'') <> 1 ";
		else $queryPart = " AND ifnull(soft_delete,'') = 1 ";
		if ($SDate!="" and $EDate!="") $queryPart .= " AND enter_date >= '".date('Y-m-d',strtotime($SDate))."' AND enter_date <= '".date('Y-m-d',strtotime($EDate))."' ";
		elseif ($SDate!="") $queryPart .= " AND enter_date = '".date('Y-m-d',strtotime($SDate))."'";
		if ($StatusSDate!="" and $StatusEDate!="") $queryPart .= " AND status_change_date >= '".date('Y-m-d',strtotime($StatusSDate))."' AND status_change_date <= '".date('Y-m-d',strtotime($StatusEDate))."' ";
		elseif ($StatusSDate!="") $queryPart .= " AND status_change_date = '".date('Y-m-d',strtotime($SDate))."'";
		if ($ActivitySDate!="" and $ActivityEDate!="") $queryPart .= "AND id IN (select gpg_sales_tracking_id from gpg_sales_tracking_contact where dated >= '".date('Y-m-d',strtotime($ActivitySDate))."' AND dated <= '".date('Y-m-d',strtotime($ActivityEDate))."')";
		elseif ($ActivitySDate!="") $queryPart .= " AND id IN (select gpg_sales_tracking_id from gpg_sales_tracking_contact where dated='".date('Y-m-d',strtotime($ActivitySDate))."')";
		if ($CloseSDate!="" and $CloseEDate!="") $queryPart .= " AND close_date >= '".date('Y-m-d',strtotime($CloseSDate))."' AND close_date <= '".date('Y-m-d',strtotime($CloseEDate))."' ";
		elseif ($CloseSDate!="") $queryPart .= " AND close_date = '".date('Y-m-d',strtotime($CloseSDate))."'";
		if ($LeadNumberStart!="" and $LeadNumberEnd!="") $queryPart .= " AND  id >= '$LeadNumberStart' AND id <= '$LeadNumberEnd' ";
		elseif ($LeadNumberStart!="") $queryPart .= " AND id = '$LeadNumberStart'";
		if ($optEmployee!="") $queryPart .= " AND gpg_employee_id = '$optEmployee' ";   
		$queryPart .= " AND status = 'Quote' ";   
		if (Input::get("ADMIN")!=1) { 
		    $getAdmin = DB::table('gpg_ad_acc')->where('uname','=','admin')->pluck('ad_id');
		    $queryPart .= " AND (modified_by = '$getAdmin' OR modified_by = '$UserId') ";   
		}  
		if ($jobNumber!="") {
		    $gpg_job_id = DB::table('gpg_job')->where('job_num','=',$jobNumber)->pluck('id');
			$queryPart .= " AND id IN (select c.gpg_sales_tracking_id from gpg_sales_tracking_job c where c.gpg_job_id = '$gpg_job_id')";   
		}
		if ($optSort=="") $optSort = "id";	 
		 	$queryPart .= " order by $optSort "; 
		$count = DB::select(DB::raw("select count(*) as t_count,(select concat(contact_details,'#~#',dated) from gpg_sales_tracking_contact where gpg_sales_tracking_id = gpg_sales_tracking.id order by gpg_sales_tracking_contact.dated desc limit 0,1) as activity_data ,(select name from gpg_customer where id = gpg_customer_id) as customer_name, (select name from gpg_employee where id = gpg_employee_id) as sales_person_name, IFNULL((select job_num from gpg_job, gpg_sales_tracking_job where gpg_job.id = gpg_sales_tracking_job.gpg_job_id and gpg_sales_tracking_job.gpg_sales_tracking_id = gpg_sales_tracking.id  AND gpg_sales_tracking_job.gpg_sales_tracking_type_of_sale = gpg_sales_tracking.type_of_sale),(SELECT job_num FROM gpg_job , gpg_sales_tracking_shop_work_job WHERE gpg_job.id = gpg_sales_tracking_shop_work_job.gpg_job_id
         AND gpg_sales_tracking_shop_work_job.gpg_sales_tracking_id = gpg_sales_tracking.id)) as job_number from gpg_sales_tracking where 1 $queryPart "));
		if (!empty($count) && isset($count[0]->t_count)) {
			$results->totalItems = 	$count[0]->t_count;
		}	
		$getSales = DB::select(DB::raw("select *,(select concat(contact_details,'#~#',dated) from gpg_sales_tracking_contact where gpg_sales_tracking_id = gpg_sales_tracking.id order by gpg_sales_tracking_contact.dated desc limit 0,1) as activity_data ,(select name from gpg_customer where id = gpg_customer_id) as customer_name, (select name from gpg_employee where id = gpg_employee_id) as sales_person_name, IFNULL((select job_num from gpg_job, gpg_sales_tracking_job where gpg_job.id = gpg_sales_tracking_job.gpg_job_id and gpg_sales_tracking_job.gpg_sales_tracking_id = gpg_sales_tracking.id  AND gpg_sales_tracking_job.gpg_sales_tracking_type_of_sale = gpg_sales_tracking.type_of_sale),(SELECT job_num FROM gpg_job , gpg_sales_tracking_shop_work_job WHERE gpg_job.id = gpg_sales_tracking_shop_work_job.gpg_job_id
         AND gpg_sales_tracking_shop_work_job.gpg_sales_tracking_id = gpg_sales_tracking.id)) as job_number from gpg_sales_tracking where 1 $queryPart $limitOffset"));
		$data_arr = array();
		foreach ($getSales as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		return $results;
	}
	/*
	*listContactPhase 
	*/
	public function listContactPhase(){
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getContactDataByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$gpg_customers = DB::table('gpg_customer')->lists('name','id');
		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$gpg_customers = DB::table('gpg_customer')->lists('name','id');
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		
		$allInputs = Input::except('_token');
		Input::flash();
		$params = array('left_menu' => $modules,'salesp_arr'=>$salesp_arr,'query_data'=>$query_data,'gpg_customers'=>$gpg_customers);
 		return View::make('salestracking.index_contact_phase', $params)->withInput($allInputs);
	}
	public function getContactDataByPage($page = 1, $limit = null)
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
	    $queryPart ='';
	    $results->totalItems = 0;
	    $results->items = array();
	    $UserId = 1;
		$SDate =  Input::get("SDate");
		$EDate =  Input::get("EDate");
		$LeadNumberStart = Input::get("LeadNumberStart");
		$LeadNumberEnd = Input::get("LeadNumberEnd");
		$ActivitySDate = Input::get("ActivitySDate");
		$ActivityEDate = Input::get("ActivityEDate");
		$optEmployee =  Input::get("optEmployee");
		$optStatus =  Input::get("optStatus");
		$optDisplay = Input::get("optDisplay");
		$optSort = Input::get("optSort");
		if ($optDisplay=="") $queryPart = " AND ifnull(soft_delete,'') <> 1 ";
		else $queryPart = " AND ifnull(soft_delete,'') = 1 ";
		if ($SDate!="" and $EDate!="") $queryPart .= " AND enter_date >= '".date('Y-m-d',strtotime($SDate))."' AND enter_date <= '".date('Y-m-d',strtotime($EDate))."' ";
		elseif ($SDate!="") $queryPart .= " AND enter_date = '".date('Y-m-d',strtotime($SDate))."'";
		if ($ActivitySDate!="" and $ActivityEDate!="") $queryPart .= "AND id IN (select gpg_sales_tracking_id from gpg_sales_tracking_contact where dated >= '".date('Y-m-d',strtotime($ActivitySDate))."' AND dated <= '".date('Y-m-d',strtotime($ActivityEDate))."')";
		elseif ($ActivitySDate!="") $queryPart .= " AND id IN (select gpg_sales_tracking_id from gpg_sales_tracking_contact where dated='".date('Y-m-d',strtotime($ActivitySDate))."')";
		if ($LeadNumberStart!="" and $LeadNumberEnd!="") $queryPart .= " AND  id >= '$LeadNumberStart' AND id <= '$LeadNumberEnd' ";
		elseif ($LeadNumberStart!="") $queryPart .= " AND id = '$LeadNumberStart'";
		if ($optEmployee!="") $queryPart .= " AND gpg_employee_id = '$optEmployee' ";   
		$queryPart .= " AND status = 'Contact' ";   
		if ($UserId!=1){ 
		    $getAdmin = DB::table('gpg_ad_acc')->where('uname','=','admin')->pluck('ad_id');
		    $queryPart .= " AND (modified_by = '$getAdmin' OR modified_by = '$UserId') ";   
		}  
		if ($optSort=="") $optSort = "id";	 
		 	$queryPart .= " order by $optSort "; 
		$count = DB::select(DB::raw("select count(*) as t_count,(select concat(contact_details,'#~#',dated) from gpg_sales_tracking_contact where gpg_sales_tracking_id = gpg_sales_tracking.id order by gpg_sales_tracking_contact.dated desc limit 0,1) as activity_data ,(select name from gpg_customer where id = gpg_customer_id) as customer_name, (select name from gpg_employee where id = gpg_employee_id) as sales_person_name from gpg_sales_tracking where 1 $queryPart"));
		if (!empty($count) && isset($count[0]->t_count)) {
			$results->totalItems = $count[0]->t_count;
		}
		$getSales = DB::select(DB::raw("select *,(select concat(contact_details,'#~#',dated) from gpg_sales_tracking_contact where gpg_sales_tracking_id = gpg_sales_tracking.id order by gpg_sales_tracking_contact.dated desc limit 0,1) as activity_data ,(select name from gpg_customer where id = gpg_customer_id) as customer_name, (select name from gpg_employee where id = gpg_employee_id) as sales_person_name from gpg_sales_tracking where 1 $queryPart $limitOffset"));
		$data_arr = array();
		foreach ($getSales as $key => $value) {
		    $data_arr[] = (array)$value;
		}    	
		$results->items = $data_arr;
		return $results;
	}
	/*
	* getContactSTInfo
	*/
	public function getContactSTInfo(){
		$id = Input::get('id');
		$qry = DB::table('gpg_sales_tracking_contact')->select('*')->where('gpg_sales_tracking_id','=',$id)->get();
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr = array('contact_note'=>$value->contact_details,'location'=>$value->location,'time'=>$value->time);
		}
		return $data_arr;
	}
	public function updateCSTModal(){
		$id = Input::get('sales_id');
		$location = Input::get('location');
		$dated = Input::get('CDate');
		$time0 = Input::get('timeTotal');
		$time1 = explode(' ', $time0);
		$time = @$time1[0];
		$contact_details = Input::get('contactDetails');
		DB::table('gpg_sales_tracking_contact')->where('gpg_sales_tracking_id','=',$id)->update(array('location'=>$location,'dated'=>$dated,'time'=>$time,'contact_details'=>$contact_details));
		return Redirect::to('salestracking')->withSuccess('Updated successfully');
	}
	/*
	* createQSTModal
	*/
	public function createQSTModal(){
		$UserId = 1;
		$ADMIN = 1;
		$adminId = DB::table('gpg_ad_acc')->where('uname','=','admin')->pluck('ad_id');
		$formFields = array("gpg_customer_id"=>"cusNameNew","enter_date"=>"enterDateNew","status"=>"leadStatusNew","gpg_employee_id"=>"salesPersonNew","contact_info"=>"contactInfoNew");
		$contact_info_data = Input::get("contactInfoLabelNew");
		$contact_info_data_arr = explode("#@@#",$contact_info_data);
		$ADD_arr   = explode("::",@$contact_info_data_arr[3]);
		$ADD2_arr  = explode("::",@$contact_info_data_arr[4]);
		$city_arr  = explode("::",@$contact_info_data_arr[5]);
		$state_arr = explode("::",@$contact_info_data_arr[6]);
		$zip_arr   = explode("::",@$contact_info_data_arr[7]);
		$phn_arr   = explode("::",@$contact_info_data_arr[10]);
		$EA_arr    = explode("::",@$contact_info_data_arr[11]);
		DB::table('gpg_customer')->where('id','=',Input::get("cusNameNew"))
								->update(array('email_add'=>@$EA_arr[1],
												'address'=>@$ADD_arr[1],
												'address2'=>@$ADD2_arr[1],
												'phone_no'=>@$phn_arr[1],
												'city'=>@$city_arr[1],
												'state'=>@$state_arr[1],
												'zipcode'=>@$zip_arr[1]));
		$query_str = array('gpg_customer_id'=>(Input::get('cusNameNew')!=""?Input::get('cusNameNew'):"NULL"),
		'enter_date'=>(Input::get('enterDateNew')!=""?date('Y-m-d', strtotime(Input::get('enterDateNew'))):"NULL"),
		'location'=>(Input::get('locationNew')!=""?Input::get('locationNew'):"NULL"),
		'contact_info'=>(Input::get('contactInfoLabelNew')!=""?Input::get('contactInfoLabelNew'):"NULL"),
		'opportunity_name'=>(Input::get('opNameNew')!=""?Input::get('opNameNew'):"NULL"),
		'projected_sale_price'=>(Input::get('pSaleNew')!=""?Input::get('pSaleNew'):"NULL"),
		'status'=>(Input::get('leadStatusNew')!=""?Input::get('leadStatusNew'):"NULL"),
		'labor_cost'=>(Input::get('laborCostNew')!=""?Input::get('laborCostNew'):"NULL"),
		'material_cost'=>(Input::get('matCostNew')!=""?Input::get('matCostNew'):"NULL"),
		'close_date'=>(Input::get('closeDateNew')!=""?Input::get('closeDateNew'):"NULL"),
		'gpg_employee_id'=>(Input::get('salesPersonNew')!=""?Input::get('salesPersonNew'):"NULL"),
		'modified_by'=>($UserId!=""?$UserId:"1"),
		'modified_on'=>date('Y-m-d'),
		'created_on'=>date('Y-m-d'));
		DB::table('gpg_sales_tracking')->insert($query_str);
		$max_sale_id = DB::table('gpg_sales_tracking')->max('id');
		DB::table('gpg_sales_tracking_contact')->insert(array('gpg_sales_tracking_id'=>$max_sale_id,'contact_details'=>Input::get('followUpCommentsNew'),'dated'=>Input::get('followUpNew')));
		$file_type_settings =  DB::table('gpg_settings')
			    ->select('*')
			    ->where('name', '=', '_ImgExt')
			    ->get();    
		$file_types = explode(',', $file_type_settings[0]->value);
		$file = Input::file('attachmentNew');
		if (!empty($file)){
			if (in_array($file->getClientOriginalExtension(), $file_types)) {
				$ext1 = explode(".",$file->getClientOriginalName());
			 	$ext2 = end($ext1);
			 	$filename = "sales_tracking_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
				$destinationPath = public_path().'/img/';
				$uploadSuccess = $file->move($destinationPath, $filename);
				//insert into db
				DB::table('gpg_sales_tracking_attachment')->insert(array('gpg_sales_tracking_id' =>$max_sale_id,'filename'=>$filename ,'displayname'=>$file->getClientOriginalName()));		
			}
		}
		return Redirect::to('salestracking/index_quote_phase');
	}

	/*
	*createRCPModal
	*/
	public function createRCPModal(){
		$UserId = 1;
		$ADMIN = 1;
		$adminId = DB::table('gpg_ad_acc')->where('uname','=','admin')->pluck('ad_id');
		$formFields = array("gpg_customer_id"=>"cusNameNew","enter_date"=>"enterDateNew","status"=>"leadStatusNew","gpg_employee_id"=>"salesPersonNew","contact_info"=>"contactInfoNew");
		$contact_info_data = Input::get("contactInfoLabelNew");
		$contact_info_data_arr = explode("#@@#",$contact_info_data);
		$ADD_arr   = explode("::",@$contact_info_data_arr[3]);
		$ADD2_arr  = explode("::",@$contact_info_data_arr[4]);
		$city_arr  = explode("::",@$contact_info_data_arr[5]);
		$state_arr = explode("::",@$contact_info_data_arr[6]);
		$zip_arr   = explode("::",@$contact_info_data_arr[7]);
		$phn_arr   = explode("::",@$contact_info_data_arr[10]);
		$EA_arr    = explode("::",@$contact_info_data_arr[11]);
		DB::table('gpg_customer')->where('id','=',Input::get("cusNameNew"))
								->update(array('email_add'=>@$EA_arr[1],
												'address'=>@$ADD_arr[1],
												'address2'=>@$ADD2_arr[1],
												'phone_no'=>@$phn_arr[1],
												'city'=>@$city_arr[1],
												'state'=>@$state_arr[1],
												'zipcode'=>@$zip_arr[1]));
		$query_str = array('gpg_customer_id'=>(Input::get('cusNameNew')!=""?Input::get('cusNameNew'):"NULL"),
		'enter_date'=>(Input::get('enterDateNew')!=""?date('Y-m-d', strtotime(Input::get('enterDateNew'))):"NULL"),
		'contact_info'=>(Input::get('contactInfoLabelNew')!=""?Input::get('contactInfoLabelNew'):"NULL"),
		'status'=>(Input::get('leadStatusNew')!=""?Input::get('leadStatusNew'):"NULL"),
		'gpg_employee_id'=>(Input::get('salesPersonNew')!=""?Input::get('salesPersonNew'):"NULL"),
		'modified_by'=>($UserId!=""?$UserId:"1"),
		'modified_on'=>date('Y-m-d'),
		'created_on'=>date('Y-m-d'));
		DB::table('gpg_sales_tracking')->insert($query_str);
		$max_sale_id = DB::table('gpg_sales_tracking')->max('id');
		DB::table('gpg_sales_tracking_contact')->insert(array('gpg_sales_tracking_id'=>$max_sale_id,'contact_details'=>Input::get('followUpCommentsNew'),'dated'=>Input::get('followUpNew')));
		$file = Input::file('attachmentNew');
		if (!empty($file)){
			if (in_array($file->getClientOriginalExtension(), $file_types)) {
				$ext1 = explode(".",$file->getClientOriginalName());
			 	$ext2 = end($ext1);
			 	$filename = "sales_tracking_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
				$destinationPath = public_path().'/img/';
				$uploadSuccess = $file->move($destinationPath, $filename);
				//insert into db
				DB::table('gpg_sales_tracking_attachment')->insert(array('gpg_sales_tracking_id' =>$max_sale_id,'filename'=>$filename ,'displayname'=>$file->getClientOriginalName()));		
			}
		}
		return Redirect::to('salestracking/index_contact_phase');
	}

	/*
	* getSTFiles
	*/
	public function getSTFiles(){
		$id = Input::get('id');
		$colcount = 1;
		$conCatStr = '';
		$quote_files = DB::select(DB::raw("select * from gpg_sales_tracking_attachment where gpg_sales_tracking_id = '$id'"));
		if (!empty($quote_files)){
			foreach($quote_files as $key=>$row){
        	    $conCatStr .='<tr><td>'.$colcount++.'</td><td>'.$row->displayname.'</td><td><a class="btn btn-danger btn-xs" id="'.$row->id.'" name="del_quote_file">Delete</a><a class="btn btn-success btn-xs" id="'.$row->id.'" name="dld_quote_file">Download</a></td></tr>';
			}
    	}
    	return $conCatStr;
	}
	/*
	*updateRCPModal
	*/
	public function updateRCPModal(){
		$userId = 1;
		$query_str = array('gpg_customer_id'=>(Input::get('cusName')!=""?Input::get('cusName'):"NULL"),
		'enter_date'=>(Input::get('enterDate')!=""?date('Y-m-d', strtotime(Input::get('enterDate'))):"NULL"),
		'contact_info'=>(Input::get('contactInfo')!=""?Input::get('contactInfo'):"NULL"),
		'status'=>(Input::get('leadStatus')!=""?Input::get('leadStatus'):"NULL"),
		'gpg_employee_id'=>(Input::get('salesPerson')!=""?Input::get('salesPerson'):"NULL"),
		'modified_by'=>($userId!=""?$userId:"1"),
		'modified_on'=>date('Y-m-d'));
		DB::table('gpg_sales_tracking')->where('id','=',Input::get('row_id'))->update($query_str);
		// update contact info of customer if no contact info exists
		$contact_info_data = Input::get("contactInfoLabel");
		$contact_info_data_arr = explode("#@@#",$contact_info_data);
		$ADD_arr   = explode("::",@$contact_info_data_arr[3]);
		$ADD2_arr  = explode("::",@$contact_info_data_arr[4]);
		$city_arr  = explode("::",@$contact_info_data_arr[5]);
		$state_arr = explode("::",@$contact_info_data_arr[6]);
		$zip_arr   = explode("::",@$contact_info_data_arr[7]);
		$phn_arr   = explode("::",@$contact_info_data_arr[10]);
		$EA_arr    = explode("::",@$contact_info_data_arr[11]);
		DB::table('gpg_customer')->where('id','=',Input::get("cusName"))
								->update(array('email_add'=>@$EA_arr[1],
												'address'=>@$ADD_arr[1],
												'address2'=>@$ADD2_arr[1],
												'phone_no'=>@$phn_arr[1],
												'city'=>@$city_arr[1],
												'state'=>@$state_arr[1],
												'zipcode'=>@$zip_arr[1]));
		return Redirect::to('salestracking/index_contact_phase');
	}

	/*
	* updateSTRModal
	*/
	public function updateSTRModal(){
		$userId = 1;
		$attached_email_ids = Input::get("attached_email_ids");
		$type_of_sale = Input::get('saleType') ;
		$update_lead_id = Input::get('row_id') ;
		$quoteNum = "" ;
		$job_attach_num = "";
		$rowFields = array('gpg_customer_id'=>(Input::get('cusName')!=""?Input::get('cusName'):"NULL"),
			'enter_date'=>(Input::get('enterDate')!=""?date('Y-m-d', strtotime(Input::get('enterDate'))):"NULL"),
			'location'=>(Input::get('location')!=""?Input::get('location'):"NULL"),
			'type_of_sale'=>($type_of_sale!=""?$type_of_sale:"NULL"),
			'sale_type_val'=>(Input::get('saleTypeVal')!=""?Input::get('saleTypeVal'):"NULL"),
			'opportunity_name'=>(Input::get('opName')!=""?Input::get('opName'):"NULL"),
			'projected_sale_price'=>(Input::get('pSale')!=""?(Input::get('pSale')):"NULL"),
			'include_tax'=>(Input::get('includeTax')!=""?Input::get('includeTax'):"NULL"),
			'labor_cost'=> (Input::get('laborCost')!=""?(Input::get('laborCost')):"NULL"),
			'rental_cost'=> (Input::get('rentCost')!=""?(Input::get('rentCost')):"NULL"),
			'material_cost'=> (Input::get('matCost')!=""?(Input::get('matCost')):"NULL"),
			'dollar_won'=> (Input::get('leadStatus')=="Won"?(Input::get('pSale')):"NULL"),
			'status'=> (Input::get('leadStatus')!=""?Input::get('leadStatus'):"NULL"),
			'status_change_date'=> date('Y-m-d'),
			'type_of_sale'=>($type_of_sale!=""?$type_of_sale:"NULL"),
			'w_o_number'=> (Input::get('WO')!=""?Input::get('WO'):"NULL"),
			'invoice_number'=> (Input::get('invoice')!=""?Input::get('invoice'):"NULL"),
			'close_date'=> (Input::get('closeDate')!=""?date('Y-m-d', strtotime(Input::get('closeDate'))):"NULL"),
			'gpg_employee_id'=> (Input::get('salesPerson')!=""?Input::get('salesPerson'):"NULL"),
			'subcontact'=> (Input::get('subContact')!=""?Input::get('subContact'):"NULL"),
			'subcontact_name'=> (Input::get('subConName')!=""?Input::get('subConName'):"NULL"),
			'contact_info'=> (Input::get('contactInfo')!=""?Input::get('contactInfo'):"NULL"),
			'modified_by'=> ($userId!=""?$userId:"1"),
			'modified_on'=> date('Y-m-d'));
			DB::table('gpg_sales_tracking')->where('id','=',$update_lead_id)->update($rowFields);
		if($type_of_sale != ""){
			switch ($type_of_sale) {
				  case 'PMcontract':
					$quoteNum = DB::table('gpg_consum_contract')->join('gpg_sales_tracking_consum_contract', 'gpg_consum_contract.id', '=', 'gpg_sales_tracking_consum_contract.gpg_consum_contract_id')->where('gpg_sales_tracking_consum_contract.gpg_sales_tracking_id','=',$update_lead_id)->pluck('gpg_consum_contract.job_num');
				  break;
				  case 'ServiceQuote':
					$quoteNum = DB::select(DB::raw("select a.job_num,a.main_contact_name,a.main_contact_phone,a.fax from gpg_field_service_work a, gpg_sales_tracking_field_service_work b where a.id = b.gpg_field_service_work_id and b.gpg_sales_tracking_id = '".$update_lead_id."'"));
					$quoteNum = @$quoteNum[0]->job_num ;
				  break;
				  case 'Electrical':
				  case 'Generators':
					$quoteNums = DB::select(DB::raw("SELECT
				  (SELECT
					   job_num
					 FROM gpg_job_electrical_quote a
					   , gpg_sales_tracking_job_electrical_quote b
					 WHERE b.gpg_job_electrical_quote_id = a.id
						 AND b.gpg_sales_tracking_id = e.id ORDER BY a.electrical_status DESC LIMIT 0,1) AS job_num
				  , (SELECT
					   job_num
					 FROM gpg_job c
					   , gpg_sales_tracking_job d
					 WHERE d.gpg_job_id = c.id
						 AND d.gpg_sales_tracking_id = e.id
						 AND e.type_of_sale = d.gpg_sales_tracking_type_of_sale) AS GPG_attach_job_num
				FROM gpg_sales_tracking e
				WHERE e.id = '".$update_lead_id."' LIMIT 0,1"));
					foreach ($quoteNums as $key => $value) {
						$quoteNum_arr = (array)$value;
					}
					$job_attach_num = $quoteNum_arr['GPG_attach_job_num'] ;
					$quoteNum = $quoteNum_arr['job_num'] ;
				  break;
				  case 'Shop':
					$quoteNums = DB::select(DB::raw("select a.job_num,a.GPG_attach_job_num from gpg_shop_work_quote a, gpg_sales_tracking_shop_work_quote b where a.id = b.gpg_shop_work_quote_id and b.gpg_sales_tracking_id = '".$update_lead_id."'"));
					$quoteNum_arr = array();
					foreach ($quoteNums as $key => $value) {
						$quoteNum_arr = (array)$value;
					}
					$quoteNum = $quoteNum_arr['job_num'] ;
					$job_attach_num = $quoteNum_arr['GPG_attach_job_num'] ;
				  break;
				  case 'Grassivy':
					$quoteNums = DB::select(DB::raw("SELECT
				  (SELECT
					   job_num
					 FROM gpg_job_grassivy_quote a
					   , gpg_sales_tracking_job_grassivy_quote b
					 WHERE b.gpg_job_grassivy_quote_id = a.id
						 AND b.gpg_sales_tracking_id = e.id ORDER BY a.grassivy_status DESC LIMIT 0,1) AS job_num
				  , (SELECT
					   job_num
					 FROM gpg_job c
					   , gpg_sales_tracking_job d
					 WHERE d.gpg_job_id = c.id
						 AND d.gpg_sales_tracking_id = e.id
						 AND e.type_of_sale = d.gpg_sales_tracking_type_of_sale) AS GPG_attach_job_num
				FROM gpg_sales_tracking e
				WHERE e.id = '".$update_lead_id."'  LIMIT 0,1 ")); 
					$quoteNum_arr = array();
					foreach ($quoteNums as $key => $value) {
						$quoteNum_arr = (array)$value;
					}
					$job_attach_num = $quoteNum_arr['GPG_attach_job_num'] ;
					$quoteNum = $quoteNum_arr['job_num'] ;
				  break;
				 case 'Special_Project':
					$quoteNums = DB::select(DB::raw("SELECT
				  (SELECT
					   job_num
					 FROM gpg_job_special_project_quote a
					   , gpg_sales_tracking_job_special_project_quote b
					 WHERE b.gpg_job_special_project_quote_id = a.id
						 AND b.gpg_sales_tracking_id = e.id ORDER BY a.special_project_status DESC LIMIT 0,1) AS job_num
				  , (SELECT
					   job_num
					 FROM gpg_job c
					   , gpg_sales_tracking_job d
					 WHERE d.gpg_job_id = c.id
						 AND d.gpg_sales_tracking_id = e.id 
						     AND e.type_of_sale = d.gpg_sales_tracking_type_of_sale) AS GPG_attach_job_num
				FROM gpg_sales_tracking e
				WHERE e.id = '".$update_lead_id."'  LIMIT 0,1 "));
					$quoteNum_arr = array();
					foreach ($quoteNums as $key => $value) {
						$quoteNum_arr = (array)$value;
					}
					$job_attach_num = $quoteNum_arr['GPG_attach_job_num'] ;
					$quoteNum = $quoteNum_arr['job_num'] ;
				  break;
			}//end switch
		}
		// updating lead and quote number in attached emails
		if($attached_email_ids != ""){
			$q_job_num = DB::table('gpg_emails')->whereIn('id', $attached_email_ids)->update(array('gpg_attach_lead_num'=>$update_lead_id,'gpg_attach_quote_num'=>$quoteNum,'gpg_attach_job_num'=>$job_attach_num));	
		}
		return Redirect::to('salestracking');
	}

	/*
	* getSTRowData	
	*/
	public function getSTRowData(){
		$param = Input::get('id');
		$qry = DB::table('gpg_sales_tracking')->select('*')->where('id','=',$param)->get();
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr = (array)$value;
		}
		return $data_arr;
	}

	/*
	* deleteSTFile
	*/
	public function deleteSTFile(){
		$id = Input::get('id');
		DB::table('gpg_sales_tracking_attachment')->where('id', '=',$id)->delete();
		return 1;
	}

	/*
	* manageSTFiles
	*/
	public function manageSTFiles(){
		if (!empty($_POST['fjob_id'])){
			$job_id = $_POST['fjob_id'];
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
				 	$filename = "sales_tracking_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = $file->move($destinationPath, $filename);
					//insert into db
					DB::table('gpg_sales_tracking_attachment')->insert(array('gpg_sales_tracking_id' =>$job_id,'filename'=>$filename ,'displayname'=>$file->getClientOriginalName()));		
				}
			}
		}
		return Redirect::to('salestracking');
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
		//
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
	* contactCalendar
	*/
	public function contactCalendar(){
		$modules = Generic::modules();
		$employeeFilter = Input::get("employeeFilter");
 		$slm = $sly = "";
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
		$contactsQuery = "select *, (select concat(ifnull(status,''),'~~',ifnull(enter_date,'')) from gpg_sales_tracking where id = gpg_sales_tracking_id) as sales_status, (select name from gpg_employee where id = entered_by) as contact_person, (select name from gpg_customer a, gpg_sales_tracking b where a.id = b.gpg_customer_id and b.id = gpg_sales_tracking_id) as customer_name from gpg_sales_tracking_contact where dated >= '$y-$m-1' and dated <= '$nextY-$nextM-1' and ifnull(contact_details,'')<>''".(!empty($employeeFilter)?" AND entered_by = '$employeeFilter'":'');
		$result = DB::select(DB::raw($contactsQuery));
		$events_arr = array();
		foreach ($result as $key => $value) {
			$e = array();
		    $e['title'] = substr(preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($value->contact_person, ENT_QUOTES)).'('.$value->customer_name.') '.' '.preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($value->sales_status, ENT_QUOTES)), 0, 35);
		    $e['start'] = date('Y-m-d',strtotime($value->dated));
		    array_push($events_arr, $e);
		}		
		$gpg_employee = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
		$allInputs = Input::except('_token');
		Input::flash();
		$params = array('left_menu' => $modules,'gpg_employee'=>$gpg_employee,'events'=>($events_arr));
 		return View::make('salestracking/contact_calendar', $params)->withInput($allInputs);
	}
	/*
	* leadEnteredReport
	*/
	public function leadEnteredReport(){
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getLeadsByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$allInputs = Input::except('_token');
		Input::flash();
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
 		return View::make('salestracking/lead_entered_report', $params)->withInput($allInputs);
	}

	public function getLeadsByPage($page = 1, $limit = null)
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
	  $queryPart ='';
	  $results->totalItems = 0;
	  $results->items = array();
	  $FVal = Input::get("FVal");
	  if(!empty($FVal)){
		$queryPart .= " AND (select count(id) from gpg_sales_tracking_contact where gpg_sales_tracking_id=a.id)='0' AND  DATEDIFF(date('Y-m-d'),a.enter_date)>='".$FVal."'";
	  }
	  $results->totalItems = count(DB::select(DB::raw("select a.enter_date,a.id,a.location,(select name from gpg_customer where id=a.gpg_customer_id) as customer,(select name from gpg_employee where id=a.gpg_employee_id) as salesPerson, DATEDIFF(date('Y-m-d'),a.enter_date) as daysAsofToday from gpg_sales_tracking a where 1 AND (a.status='Quote' OR a.status='Contact') $queryPart")));
	  $query = DB::select(DB::raw("select a.enter_date,a.id,a.location,(select name from gpg_customer where id=a.gpg_customer_id) as customer,(select name from gpg_employee where id=a.gpg_employee_id) as salesPerson, DATEDIFF(date('Y-m-d'),a.enter_date) as daysAsofToday from gpg_sales_tracking a where 1 AND (a.status='Quote' OR a.status='Contact') $queryPart $limitOffset"));
	  $query_data = array();
	  foreach ($query as $key => $value) {
	  	$query_data[] = (array)$value;
	  }
	  $results->items = $query_data;
	  return $results;
	}

	/*
	*excelLDExport
	*/
	public function excelLDExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('New sheet', function($sheet) {
		    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
			$page = Input::get('page', 1);
	   		$data = $this->getLeadsByPage($page);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
			$sheet->loadView('salestracking.excelLDExport',$params);
		  });
		})->export('xls');
	}

	/*
	*contactQuotePhaseReport
	*/
	public function contactQuotePhaseReport(){
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);

		$cus_arr = DB::table('gpg_customer')->orderBy('name')->lists('name','id');
		$gpg_employee = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
		$allInputs = Input::except('_token');
		Input::flash();
		$params = array('left_menu' => $modules,'gpg_employee'=>$gpg_employee,'cus_arr'=>$cus_arr,'query_data'=>$query_data);
 		return View::make('salestracking/contact_quote_phase_report', $params)->withInput($allInputs);
	}
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
	  $queryPart ='';
	  $results->totalItems = 0;
	  $results->items = array();
	  $Filter = Input::get("Filter");
	  $FVal = Input::get("FVal");
	  $lead_id_start = Input::get("lead_id_start");
	  $lead_id_end = Input::get("lead_id_end");
	  $SDate = Input::get("SDate");
	  $EDate = Input::get("EDate");
	  $cusid = Input::get("cusid");
	  $days_order = Input::get("days_order");
	  if($Filter=="daysAsOfToday"){
		$queryPart .= "AND DATEDIFF(date('Y-m-d'),a.enter_date)='".$FVal."'";
	  }
	  if($Filter=="sinceLastContact"){
		$queryPart .= "AND DATEDIFF((select dated from gpg_sales_tracking_contact where gpg_sales_tracking_id=a.id order by dated desc limit 0,1),(select dated from gpg_sales_tracking_contact where gpg_sales_tracking_id=a.id order by dated desc limit 1,1))>='".$FVal."'";
	  }
      if(strlen($lead_id_start)>0 && strlen($lead_id_end)==0){
		$queryPart .= " AND a.id = '".$lead_id_start."' ";
	  }
	  elseif(strlen($lead_id_start)>0 && strlen($lead_id_end)>0){
		$queryPart .= " AND a.id >= '".$lead_id_start."' AND a.id <= '".$lead_id_end."' ";
	  }
	  if(strlen($SDate)>0 && strlen($EDate)==0){
		$queryPart .= " AND a.enter_date = '".date('Y-m-d',strtotime($SDate))."' ";
	  }
	  elseif(strlen($SDate)>0 && strlen($EDate)>0){
		$queryPart .= " AND a.enter_date >= '".date('Y-m-d',strtotime($SDate))."' AND a.enter_date <= '".date('Y-m-d',strtotime($EDate))."' ";
	  }
	  if($cusid){
	 	$queryPart .= " AND a.gpg_customer_id = '".$cusid."' ";
	  }
	  if($Filter=="activeDays"){
	 	$queryPart .= "AND ifnull(contact_note,'')='' AND DATEDIFF(date('Y-m-d'),b.dated) >= '".$FVal."'";
	  }
	  $order_part = "";
	  if($days_order && $days_order!=''){
		$order_part .= "  ".str_replace("~"," ",$days_order).", ";
	  }
	   $query = DB::select(DB::raw("SELECT
	  						  a.enter_date    AS lead_entered,
	  						  a.id            AS lead_id,
	  						  b.dated         AS contact_entered,
	  						  contact_details,
	  						  contact_note,
	  						  (SELECT
	  						     NAME
	  						   FROM gpg_customer
	  						   WHERE id = a.gpg_customer_id) AS customer,
	  						  a.location      AS lead_loaction,
	  						  (SELECT
	  						     NAME
	  						   FROM gpg_employee
	  						   WHERE id = a.gpg_employee_id) AS salesPerson,
	  						  DATEDIFF(b.dated,a.enter_date) AS daysSinceCreated,
	  						  DATEDIFF(date('Y-m-d'),a.enter_date) AS daysAsOfToday
	  						FROM gpg_sales_tracking a,
	  						  gpg_sales_tracking_contact b
	  						WHERE a.id = b.gpg_sales_tracking_id
	  						    AND (a.status = 'Quote'
	  						          OR a.status = 'Contact') $queryPart"));
	   $results->totalItems = count($query);
	  $query = DB::select(DB::raw("SELECT
			  a.enter_date    AS lead_entered,
			  a.id            AS lead_id,
			  b.dated         AS contact_entered,
			  contact_details,
			  contact_note,
			  (SELECT
			     NAME
			   FROM gpg_customer
			   WHERE id = a.gpg_customer_id) AS customer,
			  a.location      AS lead_loaction,
			  (SELECT
			     NAME
			   FROM gpg_employee
			   WHERE id = a.gpg_employee_id) AS salesPerson,
			  DATEDIFF(b.dated,a.enter_date) AS daysSinceCreated,
			  DATEDIFF(date('Y-m-d'),a.enter_date) AS daysAsOfToday
			FROM gpg_sales_tracking a,
			  gpg_sales_tracking_contact b
			WHERE a.id = b.gpg_sales_tracking_id
			    AND (a.status = 'Quote'
			          OR a.status = 'Contact') $queryPart order by ".$order_part." a.id $limitOffset"));

	  $data_arr = array();
	  foreach ($query as $key => $value) {
	  	$data_arr[] = (array)$value;		
	  }	
	  $results->items = $data_arr;
	  return $results;
	}
	/*
	* excelSTExport
	*/
	public function excelSTExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    Excel::create('New file', function($excel) {
		    $excel->sheet('New sheet', function($sheet) {
		    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
			$page = Input::get('page', 1);
	   		$data = $this->getByPage($page);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$cus_arr = DB::table('gpg_customer')->orderBy('name')->lists('name','id');
			$gpg_employee = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
			$params = array('gpg_employee'=>$gpg_employee,'cus_arr'=>$cus_arr,'query_data'=>$query_data);
			$sheet->loadView('salestracking.excelSTExport',$params);
		  });
		})->export('xls');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		DB::table('gpg_sales_tracking')->where('id','=',$id)->delete();
		return Redirect::to('salestracking.index')->withSuccess('Deleted successfully');
	}


}
