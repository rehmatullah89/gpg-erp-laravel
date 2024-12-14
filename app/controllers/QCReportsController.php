<?php
class QCReportsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	Protected $elecJobTypeArray = array( "GeneratorOnly" => "Generator Only", "GeneratorAndPermit" => "Generator & Permit", "Permit" => "Permit", "MonthlyMaintenance" => "Monthly Maintenance", "Retrofit" => "Retrofit", "Standard" => "Standard", "EmergencyCall" => "Emergency Call", "TroubleShoot" => "Trouble Shoot", "InfraredScan" => "Infrared Scan", "ContractJob" => "Contract Job", "ATS" => "ATS", "ArcFlashStudy" => "Arc Flash Study", "ChartRecording" => "Chart Recording", "CircuitTracing" => "Circuit Tracing", "WarrantyNonBillable" => "Warranty (Non Billable)", "GenTracker" => "Gen Tracker" );	
	protected $arr_regarding = array("Annual Service"=>"Annual Service","Emergency Call"=>"Emergency Call","Fuel Delivery"=>"Fuel Delivery","Fuel Polish"=>"Fuel Polish","Fuel Sample"=>"Fuel Sample","Load Bank Test"=>"Load Bank Test","Repair"=>"Repair","Service Generator"=>"Service Generator");
	public function get_childs($parent_id = 0,$temp=0){
		if (!isset($GLOBALS['tags_arr2'])) {
			$GLOBALS['tags_arr2'] = array();
		}
		if (!isset($GLOBALS['parent_arr'])) {
			$GLOBALS['parent_arr'] = array();
		}
		$result_parent = DB::select(DB::raw("SELECT id, parent_id,IFNULL(gpg_expense_gl_code.gpg_expense_gl_tags,0) AS gl_tags FROM gpg_expense_gl_code WHERE parent_id = '".$parent_id."'"));
		foreach ($result_parent as $key => $arr) {
			$GLOBALS['parent_arr'][$parent_id][] = $arr->id;
			if($arr->gl_tags=="")
				$GLOBALS['tags_arr2'][0][] = $arr->id;
			else
				$GLOBALS['tags_arr2'][$arr->gl_tags][] = $arr->id;
			$this->get_childs($arr->id);
		}
	}
	function get_childs2($parent_id = 0,$temp=0){
		if (!isset($GLOBALS['parent_arr'])) {
			$GLOBALS['parent_arr'] = array();
		}
		$qry = DB::select(DB::raw("SELECT id, parent_id, exclude_from_oh FROM gpg_expense_gl_code WHERE parent_id = '".$parent_id."'"));
		foreach ($qry as $key => $arr) {
			$GLOBALS['parent_arr'][$parent_id][$arr->id] = $arr->exclude_from_oh;
			$this->get_childs2($arr->id);

		}
	}
	function get_childs3($parent_id = 0,$temp=0) {
		if (!isset($GLOBALS['parent_arr'])) {
			$GLOBALS['parent_arr'] = array();
		}
		if (!isset($GLOBALS['data_arr']))
			$GLOBALS['data_arr'] = array();
		if (!isset($GLOBALS['str_excluded_ids']))
			$GLOBALS['str_excluded_ids'] = '';
		$result_parent = DB::select(DB::raw("SELECT id, parent_id, exclude_from_oh,CONCAT(expense_gl_code,' - ',description) as descr FROM gpg_expense_gl_code WHERE parent_id = '".$parent_id."'"));
		foreach ($result_parent as $key => $arr) {
			$GLOBALS['parent_arr'][$parent_id][$arr->id] = array(
				"excluded" => $arr->exclude_from_oh,
				"title" => $arr->descr,
				"credit_sum" =>  @$GLOBALS['data_arr'][$arr->id]->credit_sum,
				"debit_sum" =>   @$GLOBALS['data_arr'][$arr->id]->debit_sum,
		    	"amount_sum" =>  @$GLOBALS['data_arr'][$arr->id]->amount_sum
			);
			if(isset($arr->exclude_from_oh)){
				$GLOBALS['str_excluded_ids'] .= $arr->id.",";
			}
			$this->get_childs3($arr->id);
		}
	}
	public function set_totals($parent_id=0,$sub=0,$main_parent=0,$gl_tag = 0){
		$GLOBALS['arr_final_totals'];
		$GLOBALS['parent_arr'];
		if (!isset($GLOBALS['data_arr']))
			$GLOBALS['data_arr'] = array();
		$credit_sum = 0;
		if(!isset($GLOBALS['arr_final_totals'][$parent_id]['credit_total']))
			$GLOBALS['arr_final_totals'][$parent_id]['credit_total'] = 0;
		if(!isset($GLOBALS['arr_final_totals'][$parent_id]['debit_total']))
			$GLOBALS['arr_final_totals'][$parent_id]['debit_total'] = 0;
		if(!isset($GLOBALS['arr_final_totals'][$parent_id]['amount_total']))
			$GLOBALS['arr_final_totals'][$parent_id]['amount_total'] = 0;
		if(isset($GLOBALS['data_arr'][$parent_id])){
			$GLOBALS['arr_final_totals'][$parent_id]['credit_total'] += $GLOBALS['data_arr'][$parent_id]->credit_sum;
			$GLOBALS['arr_final_totals'][$parent_id]['debit_total'] += $GLOBALS['data_arr'][$parent_id]->debit_sum;
			$GLOBALS['arr_final_totals'][$parent_id]['amount_total'] += $GLOBALS['data_arr'][$parent_id]->amount_sum;
			if(!isset($GLOBALS['arr_final_totals'][$parent_id]['gl_tags']))
				$GLOBALS['arr_final_totals'][$parent_id]['gl_tags'] = $GLOBALS['data_arr'][$parent_id]->gl_tags;
		}
		if(isset($GLOBALS['parent_arr'][$parent_id]))
		{
			$arr = $GLOBALS['parent_arr'][$parent_id];
			if(is_array($arr))
			{
				foreach($arr as $key => $val)
				{
					if($sub==0)
					{
						if(isset($GLOBALS['data_arr'][$val]))
						{
							$GLOBALS['arr_final_totals'][$parent_id]['credit_total'] += $GLOBALS['data_arr'][$val]->credit_sum;
							$GLOBALS['arr_final_totals'][$parent_id]['debit_total']  += $GLOBALS['data_arr'][$val]->debit_sum;
							$GLOBALS['arr_final_totals'][$parent_id]['amount_total'] += $GLOBALS['data_arr'][$val]->amount_sum;
							if(!isset($GLOBALS['arr_final_totals'][$parent_id]['gl_tags']))
								$GLOBALS['arr_final_totals'][$parent_id]['gl_tags'] = $GLOBALS['data_arr'][$val]->gl_tags;
						}
					}
					else
					{
						if(isset($GLOBALS['data_arr'][$val]))
						{
							$GLOBALS['arr_final_totals'][$main_parent]['credit_total'] += $GLOBALS['data_arr'][$val]->credit_sum;
							$GLOBALS['arr_final_totals'][$main_parent]['debit_total']  += $GLOBALS['data_arr'][$val]->debit_sum;
							$GLOBALS['arr_final_totals'][$main_parent]['amount_total'] += $GLOBALS['data_arr'][$val]->amount_sum;

							if(!isset($GLOBALS['arr_final_totals'][$main_parent]['gl_tags']))
								$GLOBALS['arr_final_totals'][$main_parent]['gl_tags'] = $GLOBALS['data_arr'][$val]->gl_tags;
						}
						$parent_id = $main_parent;
					}
					if(isset($GLOBALS['parent_arr'][$val]) && is_array($GLOBALS['parent_arr'][$val]))
						$this->set_totals($val,1,$parent_id);
				}
			}
		}
	}
	function set_totals2($parent_id=0,$sub=0,$main_parent=0){
		$GLOBALS['arr_final_totals'];
		$GLOBALS['parent_arr'];
		if (!isset($GLOBALS['data_arr']))
			$GLOBALS['data_arr'] = array();
		$excluded = 0;
		$credit_sum = 0;
		@$GLOBALS['arr_final_totals'][$parent_id]['credit_total'] += @$GLOBALS['data_arr'][$parent_id]->credit_sum;
		@$GLOBALS['arr_final_totals'][$parent_id]['debit_total'] += @$GLOBALS['data_arr'][$parent_id]->debit_sum;
		@$GLOBALS['arr_final_totals'][$parent_id]['amount_total'] += @$GLOBALS['data_arr'][$parent_id]->amount_sum;
		if((is_numeric($GLOBALS['parent_arr'][0][$parent_id]) && $GLOBALS['parent_arr'][0][$parent_id]==1)	)
		{
			$excluded = 1;
			@$GLOBALS['arr_final_totals'][$parent_id]['exclude_from_oh'] += @$GLOBALS['data_arr'][$parent_id]->amount_sum;
		}
		
		$arr = @$GLOBALS['parent_arr'][$parent_id];
		if(is_array($arr))
		{
			foreach($arr as $key => $val)
			{
				if($sub==0)
				{
					@$GLOBALS['arr_final_totals'][$parent_id]['credit_total'] += @$GLOBALS['data_arr'][$key]->credit_sum;
					@$GLOBALS['arr_final_totals'][$parent_id]['debit_total'] += @$GLOBALS['data_arr'][$key]->debit_sum;
					@$GLOBALS['arr_final_totals'][$parent_id]['amount_total'] += @$GLOBALS['data_arr'][$key]->amount_sum;
					if($GLOBALS['parent_arr'][0][$parent_id] or $val==1)
						@$GLOBALS['arr_final_totals'][$parent_id]['exclude_from_oh'] += @$GLOBALS['data_arr'][$key]->amount_sum;
				}
				else
				{
					@$GLOBALS['arr_final_totals'][$main_parent]['credit_total'] += @$GLOBALS['data_arr'][$key]->credit_sum;
					@$GLOBALS['arr_final_totals'][$main_parent]['debit_total'] += @$GLOBALS['data_arr'][$key]->debit_sum;
					@$GLOBALS['arr_final_totals'][$main_parent]['amount_total'] += @$GLOBALS['data_arr'][$key]->amount_sum;
					if(isset($GLOBALS['parent_arr'][$main_parent][$parent_id]) or $val==1)
						@$GLOBALS['arr_final_totals'][$main_parent]['exclude_from_oh'] += @$GLOBALS['data_arr'][$key]->amount_sum;
		
					$parent_id = $main_parent;
				}
				if(isset($GLOBALS['parent_arr'][$key]) && is_array($GLOBALS['parent_arr'][$key]))
					$this->set_totals2($key,1,$parent_id);
			}
		}
	}
	function set_totals3($parent_id=0,$sub=0,$main_parent=0) {
		if(!isset($GLOBALS['arr_final_totals']))
			$GLOBALS['arr_final_totals'] = array();
		if(!isset($GLOBALS['parent_arr']))
			$GLOBALS['parent_arr'] = array();
		if(!isset($GLOBALS['data_arr']))
			$GLOBALS['data_arr'] = array();
		if(!isset($GLOBALS['cell_end']))
			$GLOBALS['cell_end'] = array();
		if(!isset($GLOBALS['arr_total_cells']))
			$GLOBALS['arr_total_cells'] = array();
		if(!isset($GLOBALS['all_arr']))
			$GLOBALS['all_arr'] = array();
		$excluded = 0;
		$credit_sum = 0;
		@$GLOBALS['arr_final_totals'][$parent_id]['credit_total'] += @$GLOBALS['data_arr'][$parent_id]->credit_sum;
		@$GLOBALS['arr_final_totals'][$parent_id]['debit_total'] +=  @$GLOBALS['data_arr'][$parent_id]->debit_sum;
		@$GLOBALS['arr_final_totals'][$parent_id]['amount_total'] += @$GLOBALS['data_arr'][$parent_id]->amount_sum;
		@$GLOBALS['arr_total_cells'][] = $GLOBALS['cell_end'];
		if(isset($GLOBALS['all_arr'][$parent_id]['excluded'])){
			@$GLOBALS['arr_final_totals'][$parent_id]['exclude_from_oh'] += @$GLOBALS['data_arr'][$parent_id]->amount_sum;
		}
		@$GLOBALS['all_arr'][$parent_id]["amount"] = @$GLOBALS['arr_final_totals'][$parent_id]['amount_total'];
		$arr = @$GLOBALS['parent_arr'][$parent_id];
		$subtotal= 0;
		if(is_array($arr))
		{
			foreach($arr as $key => $val)
			{
				@$GLOBALS['arr_final_totals'][$parent_id]['excluded'] = @$GLOBALS['parent_arr'][$parent_id][$key]["excluded"];
				@$GLOBALS['arr_final_totals'][$parent_id]['credit_total'] += @$GLOBALS['data_arr'][$key]->credit_sum;
				@$GLOBALS['arr_final_totals'][$parent_id]['debit_total'] += @$GLOBALS['data_arr'][$key]->debit_sum;
				@$GLOBALS['arr_final_totals'][$parent_id]['amount_total'] += @$GLOBALS['data_arr'][$key]->amount_sum;
				if(isset($GLOBALS['all_arr'][$key]['excluded'])){
					@$GLOBALS['arr_final_totals'][$parent_id]['exclude_from_oh'] += @$GLOBALS['data_arr'][$key]->amount_sum;
				}
				$GLOBALS['all_arr'][$key]["amount"] = @$GLOBALS['data_arr'][$key]->amount_sum;
			}
			$GLOBALS['arr_total_cells'][] = @$GLOBALS['cell_end'];
		}
		$GLOBALS['all_arr'][$parent_id]["amount"] = @$GLOBALS['arr_final_totals'][$parent_id]['amount_total'];
	}
	public function index()
	{
		//
	}
	/*
	*serviceJobCheck
	*/
	public function serviceJobCheck(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getDupServJobRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('qc_reports.service_job_check', $params);
	}
	public function getDupServJobRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$jobnumber = Input::get("jobnumber");
		$str="";
		if(strlen($jobnumber)>0)
			$str = " and job_num='".$jobnumber."' ";
		$result = DB::select(DB::raw("SELECT COUNT(*) AS total, job_num FROM gpg_job WHERE gpg_job_type_id = 4 ".$str." GROUP BY job_num HAVING total>1 ORDER BY total desc"));
		$results->totalItems = count($result);
		$results->items = $result;
		return $results;
	}
	
	/*
	* salesTaxExceptionReport
	*/
	public function salesTaxExceptionReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getSalesTaxExcpRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'Toal_tax'=>$data->Toal_tax,'Total_mat_cost'=>$data->Total_mat_cost);
		return View::make('qc_reports.sales_tax_exception_report', $params);
	}
	public function getSalesTaxExcpRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$results->Toal_tax = 0;
		$results->Total_mat_cost = 0;
		$qryFilter = Input::get('optStatus');
		if($qryFilter == 'zero_tax_amount'){
		$WherequeryPart = "HAVING tax_amt='0.00' AND material_cost > '0.00'";
		}else {
			$WherequeryPart = "HAVING material_cost='0.00' AND tax_amt > '0.00'";
		}

		$result = DB::select(DB::raw("SELECT job_num,(SELECT if(SUM(amount),SUM(amount),0) FROM gpg_job_cost WHERE job_num = gpg_job.job_num) as material_cost,(SELECT SUM(tax_amount) FROM gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id ) as tax_amt FROM gpg_job WHERE job_num like 'GPG%' $WherequeryPart ")); 
		$Toal_tax = "";
		$Total_mat_cost = 0;
		$data = array();
		if(count($result)>0)
		{
			$SrNo = 0;
			foreach ($result as $key => $value){
				$row = (array)$value;
				$SrNo++;
				$data[$SrNo]['job_num'] 		= $row['job_num'];
				$data[$SrNo]['material_cost'] 	= $row['material_cost'];
				$data[$SrNo]['tax_amt'] 		= $row['tax_amt'];
				$Total_mat_cost 	+= $data[$SrNo]['material_cost'];
				$Toal_tax 			+= $data[$SrNo]['tax_amt'];
			}
		}
		$results->totalItems = count($data);
		$results->items = $data;
		$results->Toal_tax = $Toal_tax;
		$results->Total_mat_cost = $Total_mat_cost;
		return $results;
	}
	/*
	* wrongPrevailingJobsReport
	*/
	public function wrongPrevailingJobsReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getWrongPrevJobRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('qc_reports.wrong_prevailing_jobs_report', $params);
	}
	public function getWrongPrevJobRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$qryFilter = Input::get('optJobnum');
		$WherequeryPart ="";
		if($qryFilter != ''){
			$WherequeryPart = "AND  job_num='".$qryFilter."'";
		}
		$result = DB::select(DB::raw("select date, 
				(select name from gpg_employee where id = a.GPG_employee_id) as emp_name ,
				b.id as d_id, b.GPG_timetype_id as timetypId,
				b.job_num,b.time_in,b.time_out,b.GPG_timesheet_id, b.GPG_job_id
				from gpg_timesheet a , gpg_timesheet_detail b 
				WHERE a.id = b.GPG_timesheet_id AND b.pw_flag='1' AND b.pw_reg_rate='0.00'  $WherequeryPart order by date DESC")); 
		$data = array();
		if(count($result)>0)
		{
			$SrNo = 0;
			while($row = mysql_fetch_array($result))
			{
				$SrNo++;
				$data[$SrNo]['job_num'] 		= $row['job_num'];
				$data[$SrNo]['time_in'] 	= $row['time_in'];
				$data[$SrNo]['time_out'] 		= $row['time_out'];
				$data[$SrNo]['GPG_timesheet_id'] 		= $row['GPG_timesheet_id'];
				$data[$SrNo]['gpg_timetype_id'] 		= $row['gpg_timetype_id'];
				$data[$SrNo]['GPG_job_id'] 		= $row['GPG_job_id'];
				$data[$SrNo]['date'] 		= $row['date'];
			}
		}
		$results->totalItems = count($data);
		$results->items = array_slice($data,$start,$limit);
		return $results;
	}

	/*
	* projectedMarginReport
	*/
	public function projectedMarginReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getProjMarginRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('qc_reports.projected_margin_report', $params);
	}
	public function getProjMarginRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$optList = Input::get('optList');
		$SProjectedMargin = Input::get('SProjectedMargin');
		$EProjectedMargin = Input::get('EProjectedMargin');
		$SQuotedAmount = Input::get('SQuotedAmount');
		$EQuotedAmount = Input::get('EQuotedAmount');
		$sQuoteMade = Input::get('sqMade');
		$eQuoteMade = Input::get('eqMade');
		$sQuoteWon = Input::get('sqWon');
		$eQuoteWon = Input::get('eqWon');
		$qryFilter ="";
		$queryPart = '';
		if($optList==''){
			$optList = 'elist';
		}
		if($qryFilter != ''){
			$queryPart .= "AND  job_num='".$qryFilter."'";
		}
		if($sQuoteMade != "" && $eQuoteMade != ""){
			$queryPart .= " AND created_on >='".date('Y-m-d', strtotime($sQuoteMade))."' AND created_on <= '".date('Y-m-d', strtotime($eQuoteMade))."' ";
		}
		if($sQuoteWon != "" && $eQuoteWon != ""){
			$queryPart .= " AND date_job_won >= '".date('Y-m-d', strtotime($sQuoteWon))."' AND date_job_won <= '".date('Y-m-d', strtotime($eQuoteWon))."' ";
		}
		if ($SProjectedMargin!="" and $EProjectedMargin!=""){
			$queryPart .= " HAVING projected_margin >= '".$SProjectedMargin."' AND projected_margin <= '".$EProjectedMargin."' ";
		}
		elseif ($SProjectedMargin!="") $queryPart .= " HAVING projected_margin >= '".$SProjectedMargin."'";
		elseif ($EProjectedMargin!="") $queryPart .= " HAVING projected_margin <= '".$EProjectedMargin."'";
		if ($SQuotedAmount!="" and $EQuotedAmount!=""){
			$queryPart .= " HAVING quoted_amount >= '".$SQuotedAmount."' AND quoted_amount <= '".$EQuotedAmount."' ";
		}
		elseif ($SQuotedAmount!="") $queryPart .= " HAVING quoted_amount >= '".$SQuotedAmount."'";
		elseif ($EQuotedAmount!="") $queryPart .= " HAVING quoted_amount <= '".$EQuotedAmount."'";
		$bgcolor="#FFFFFF";
		$queryPart .= ' order by job_num DESC';
		if($optList == 'elist'){
			$getList = "select created_on, date_job_won, job_num,margin_gross_total as projected_margin,electrical_status as status, grand_total as quoted_amount  from gpg_job_electrical_quote where 1 $queryPart ";
		}
		if($optList == 'flist'){
			$getList = "select created_on, date_job_won, job_num,field_service_work_status as status,(grand_list_total-grand_cost_total) as projected_margin, grand_list_total as quoted_amount from gpg_field_service_work where field_service_work_status='Quote' $queryPart";
		}
		if($optList == 'hlist'){
			$getList = "select created_on, date_job_won, job_num,shop_work_quote_status as status,(grand_list_total-grand_cost_total) as projected_margin, grand_list_total as quoted_amount from gpg_shop_work_quote where shop_work_quote_status='Quote' $queryPart";
		}
		if($optList == 'glist'){
			$getList = "select created_on, date_job_won, job_num,margin_gross_total as projected_margin,grassivy_status as status, grand_total as quoted_amount  from gpg_job_grassivy_quote where 1 $queryPart ";
		}
		if($optList == 'splist'){
			$getList = "select created_on, date_job_won, job_num,margin_gross_total as projected_margin,special_project_status as status, grand_total as quoted_amount  from gpg_job_special_project_quote where 1 $queryPart ";
		}
		$result = DB::select(DB::raw($getList));
		$data = array();
		if(count($result)>0){
			$SrNo = 0;
			$Total_mat_cost = 0;
			foreach ($result as $key => $value){
				$row = (array)$value;
				$SrNo++;
				$data[$SrNo]['created_on'] = date('m/d/Y',strtotime($row['created_on']));		
				$data[$SrNo]['job_num'] 		 = $row['job_num'];
				$data[$SrNo]['projected_margin'] = $row['projected_margin'];
				$data[$SrNo]['quoted_amount'] = $row['quoted_amount'];
				$data[$SrNo]['status'] = $row['status'];
				$data[$SrNo]['date_job_won'] = $row['date_job_won']!="" ? date('m/d/Y',strtotime($row['date_job_won'])):" - ";
			}
		}
		$results->totalItems = count($data);
		$results->items = array_slice($data,$start,$limit);
		return $results;
	}
	/*
	* jobExceptionReport
	*/
	public function jobExceptionReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getJobExcpRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$job_types = array(''=>'ALL')+DB::table('gpg_job_type')->lists('name','id');
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'job_types'=>$job_types);
		return View::make('qc_reports.job_exception_report', $params);
	}
	public function getJobExcpRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$job_type = Input::get("job_type");
		$date_diff = Input::get("date_diff");
		$chkclose = Input::get("chkclose");
		$DSQL = "";
		$Filter="";
		$colcount=0;
		if ($SDate!="" || $EDate!="") {  
		    if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND 'm/d/Y'(gpg_job.closing_date,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND gpg_job.closing_date <= '".date('Y-m-d',strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (gpg_job.closing_date >= '".date('Y-m-d',strtotime($SDate))."' 
			            AND gpg_job.closing_date <= '".date('Y-m-d',strtotime($EDate))."')" ; 
			}
		}
		if($job_type){
			$DSQL.= " AND gpg_job.GPG_job_type_id = '".$job_type."' ";
		}
		if($date_diff){
			$DSQL.= " AND DATEDIFF(gpg_job.closing_date, gpg_job.date_completion) = '".$date_diff."'";
		}else{
			$date_diff=0;
		}
		if($chkclose && $chkclose == "only_closed"){
			$DSQL.= " AND (gpg_job.complete = '0' OR gpg_job.complete IS NULL ) ";
		}
		$order_by = " ORDER BY gpg_job.closing_date desc";
		$jobsQuery = DB::select(DB::raw("SELECT
			gpg_job.*,
			(SELECT COUNT(*) FROM gpg_purchase_order gpo, gpg_purchase_order_line_item gpoli WHERE gpoli.job_num = gpg_job.job_num AND gpoli.GPG_purchase_order_id = gpo.id AND gpo.po_date > gpg_job.closing_date) AS has_pos,
			(SELECT COUNT(*) FROM gpg_timesheet, gpg_timesheet_detail WHERE gpg_timesheet.id = gpg_timesheet_detail.GPG_timesheet_id AND gpg_timesheet_detail.job_num = gpg_job.job_num AND gpg_timesheet.date > gpg_job.closing_date) AS has_timesheets,
			(SELECT COUNT(*) FROM gpg_job_cost WHERE gpg_job.job_num = gpg_job_cost.job_num AND gpg_job_cost.date > gpg_job.closing_date) AS has_job_costs,
			(SELECT COUNT(*) FROM gpg_job_invoice_info WHERE  gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job_invoice_info.invoice_date > gpg_job.closing_date ORDER BY invoice_date DESC) AS has_invoices
		FROM
			gpg_job 
		WHERE
			gpg_job.closed = 1 AND
			gpg_job.closing_date != '' AND
			gpg_job.job_num != '' ".$DSQL." AND
			(
			(SELECT COUNT(*) FROM gpg_purchase_order gpo, gpg_purchase_order_line_item gpoli WHERE gpoli.job_num = gpg_job.job_num AND gpoli.GPG_purchase_order_id = gpo.id AND gpo.po_date > gpg_job.closing_date) > 0 OR
			(SELECT COUNT(*) FROM gpg_timesheet, gpg_timesheet_detail WHERE gpg_timesheet.id = gpg_timesheet_detail.GPG_timesheet_id AND gpg_timesheet_detail.job_num = gpg_job.job_num AND gpg_timesheet.date > gpg_job.closing_date) > 0 OR
			(SELECT COUNT(*) FROM gpg_job_cost WHERE gpg_job.job_num = gpg_job_cost.job_num AND gpg_job_cost.date > gpg_job.closing_date) > 0 OR
			(SELECT COUNT(*) FROM gpg_job_invoice_info WHERE  gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job_invoice_info.invoice_date > gpg_job.closing_date ORDER BY invoice_date DESC) > 0
			)
			".$order_by.' '.$limitOffset));
		$jobsQuery2 = DB::select(DB::raw("SELECT COUNT(*) as t_count FROM gpg_job WHERE
			gpg_job.closed = 1 AND
			gpg_job.closing_date != '' AND
			gpg_job.job_num != '' ".$DSQL." AND
			(
			(SELECT COUNT(*) FROM gpg_purchase_order gpo, gpg_purchase_order_line_item gpoli WHERE gpoli.job_num = gpg_job.job_num AND gpoli.GPG_purchase_order_id = gpo.id AND gpo.po_date > gpg_job.closing_date) > 0 OR
			(SELECT COUNT(*) FROM gpg_timesheet, gpg_timesheet_detail WHERE gpg_timesheet.id = gpg_timesheet_detail.GPG_timesheet_id AND gpg_timesheet_detail.job_num = gpg_job.job_num AND gpg_timesheet.date > gpg_job.closing_date) > 0 OR
			(SELECT COUNT(*) FROM gpg_job_cost WHERE gpg_job.job_num = gpg_job_cost.job_num AND gpg_job_cost.date > gpg_job.closing_date) > 0 OR
			(SELECT COUNT(*) FROM gpg_job_invoice_info WHERE  gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job_invoice_info.invoice_date > gpg_job.closing_date ORDER BY invoice_date DESC) > 0
			)"));
		
		$results->totalItems = @$jobsQuery2[0]->t_count;
		$data_arr = array();
		foreach ($jobsQuery as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		return $results;
	}
	/*
	* getDetailsInfo
	*/
	public function getDetailsInfo(){
		$id = Input::get('id');
		$type = Input::get('type');
		$job_num = Input::get('job_num');
		$closing_date = Input::get('closing_date');
		$thead = '';
		$tbody = '';
		$data_arr = array('thead'=>$thead,'tbody'=>$tbody);
		if ($type == 'Invoices') {
			$thead = '<tr><th>Invoice No.</th><th>Invoice Date</th><th>Invoice Amount</th><th>Sales Tax Amount</th><th>Net Invoice Amount</th></tr>';
			$query = DB::select(DB::raw("select * FROM gpg_job_invoice_info WHERE  gpg_job_id = '".$id."' AND gpg_job_invoice_info.invoice_date > '".$closing_date."' order by invoice_date desc"));
			$total_invoice_amount=0;
		    $total_tax_amount=0;
			$total_invoice_net=0;
			foreach ($query as $key => $value) {
				$total_tax_amount += $value->tax_amount;
				$net = $value->invoice_amount>0?$value->invoice_amount - $value->tax_amount:0;
				$total_invoice_net += $net;
				$tbody .= '<tr><td>'.$value->invoice_number.'</td><td>'.date('m/d/Y',strtotime($value->invoice_date)).'</td><td>'.$value->invoice_amount.'</td><td>'.$value->tax_amount.'</td><td>'.$net.'</td></tr>';
			}
			$data_arr = array('thead'=>$thead,'tbody'=>$tbody);
		}elseif($type == 'Purchase_orders') {
			$total_quoted_amount = 0;
			$total_amnt_date = 0;
			$thead = '<tr><th>PO No.</th><th>Date</th><th>Vendor</th><th>Quoted Amount For PO</th><th> 	PO Amount to Date</th></tr>';
			$query = DB::select(DB::raw("select *,a.id as po_id ,(b.job_num) as jobNumber, (select concat(gl_code,' ',description) from gpg_gl_code where id = b.GPG_gl_code_id and status = 'A') as glCode, (select name from gpg_vendor where id = a.GPG_vendor_id and status = 'A') as poVendor, (select name from gpg_employee where id = a.request_by_id and status = 'A') as poRequest, (select name from gpg_employee where id = a.po_writer_id and status = 'A') as poWriter from gpg_purchase_order a, gpg_purchase_order_line_item b where a.id = b.gpg_purchase_order_id and b.GPG_job_id = '".$id."' and a.po_date > '".$closing_date."' and ifnull(a.soft_delete,0) <> 1 group by a.id,b.GPG_job_id order by a.id desc"));
			foreach ($query as $key => $value) {
				$total_quoted_amount += $value->po_quoted_amount;
				$tbody .= '<tr><td>'.$value->po_id.'</td><td>'.date('m/d/Y',strtotime($value->po_date)).'</td><td>'.(DB::table('gpg_vendor')->where('id','=',$value->GPG_vendor_id)->pluck('name')).'</td><td>'.'$'.number_format($value->po_quoted_amount,2).'</td><td>'.'$'.number_format(($value->po_amount_to_dat?$value->po_amount_to_dat:"0.00"),2).'</td></tr>';
			}
			$data_arr = array('thead'=>$thead,'tbody'=>$tbody);
		}elseif ($type == 'Job_Costs') {
			$total_job_cost_amnt =0;
			$thead = '<tr><th>Type</th><th>Date</th><th>Num</th><th>Name</th><th>Memo</th><th>Amount</th></tr>';
			$query = DB::select(DB::raw("select * FROM gpg_job_cost WHERE  job_num = '".$job_num."' AND gpg_job_cost.date > '".$closing_date."' order by date desc"));
			foreach ($query as $key => $value) {
				$tbody .= '<tr><td>'.$value->type.'</td><td>'.date('m/d/Y',strtotime($value->date)).'</td><td>'.$value->num.'</td><td>'.$value->name.'</td><td>'.$value->memo.'</td><td>'.($value->amount>0?$value->amount:0).'</td></tr>';
				$total_job_cost_amnt += $jobcost_obj->amount;
			}
			$data_arr = array('thead'=>$thead,'tbody'=>$tbody);
		}elseif ($type == 'Time_Cards') {
			$total_hours = 0;
			$total_mins = 0;
			$thead = '<tr><th>Tech</th><th>Type</th><th>Date</th><th>Total Hours</th></tr>';
			$query = DB::select(DB::raw("select *, (select name from gpg_employee where id = a.GPG_employee_id) as emp_name , b.id as d_id, b.GPG_timetype_id as timetypId, b.labor_rate as LaborRate, b.pw_reg_rate as pw_reg, b.pw_ot_rate as pw_ot, b.pw_dt_rate as pw_dt from gpg_timesheet a , gpg_timesheet_detail b WHERE a.id = b.GPG_timesheet_id and b.GPG_job_id = '".$id."' and date > '".$closing_date."' order by date desc"));
			foreach ($query as $key => $value) {
				$rH = 0; $otH = 0; $dtH = 0; 
				$pw_reg= $value->pw_reg;
				$pw_ot =$value->pw_ot;
				$pw_dt =$value->pw_dt;
				$perHourLabor = $value->LaborRate;
				$prevail=0;
				if($value->pw_flag=='1' && ($value->timetypId ==1 || $value->timetypId ==2)){
				  	$prevail = 1;
				}
				$timetyp = DB::table('gpg_timetype')->where('id','=',$labor_obj->timetypId)->pluck('name'); 
				$timearray = $this->get_time_difference( $labor_obj->time_in, $labor_obj->time_out); 
				$pwType = $this->convertTime($timearray['hours'].":".$timearray['minutes']);
				if ($pwType<=8) { $rH = $pwType; }
				elseif ($pwType>8 && $pwType<=12) { $rH = 8; $otH = $pwType-8; } 
				elseif ($pwType>12) { $rH = 8; $otH = 4; $dtH = $pwType-12; }  
				$regWage = @round($rH*($prevail==1?$pw_reg:($perHourLabor)),2);
				$otWage =@round($otH*($prevail==1?$pw_ot:($perHourLabor*1.5)),2);
				$dtWage = @round($dtH*($prevail==1?$pw_dt:($perHourLabor*2)),2);
				$totalWage = $regWage + $otWage + $dtWage;
                $txt_timetype = $prevail==1?"<strong>PREV</strong>&nbsp;/&nbsp;".$timetyp:$timetyp;
				$tbody .= '<tr><td>'.$value->emp_name.'</td><td>'.$txt_timetype.'</td><td>'.date('m/d/Y',strtotime($value->date)).'</td><td>'.$timearray['hours'].":".$timearray['minutes']==0?'00':$timearray['minutes'].'</td></tr>';
				$total_hours += $timearray['hours'];
				$total_mins += $timearray['minutes'];
			}
			$data_arr = array('thead'=>$thead,'tbody'=>$tbody);
		}
		return $data_arr;
	}

	/*
	* customerJobReport
	*/
	public function customerJobReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getCustJobRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$task_array = array(''=>'ALL') ;
		$q = DB::select(DB::raw("SELECT DISTINCT task FROM `gpg_job` WHERE task <> '' AND `job_num` NOT REGEXP 'sh|gpg' ORDER BY `task`")); 
		foreach ($q as $key => $row) {
			$task_array[$row->task] = $row->task;
		}		
		foreach($this->elecJobTypeArray as $key => $value){
			$task_array[$key] = $value ;
		}
		asort($task_array);
  		$customers = DB::table('gpg_customer')->where('status','=','A')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'job_types'=>$task_array,'customers'=>$customers,'cusData'=>$data->cusData,'elecJobTypeArray'=>$this->elecJobTypeArray,'arr_ar_ap_report'=>$data->arr_ar_ap_report);
		return View::make('qc_reports.customer_job_report', $params);
	}
	public function getCustJobRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		$results = new \StdClass;
		$results->page = $page;
		$limitQry = "";
		# set offset (for excel export offset will not apply)
		$limitOffset = '';
		if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		  	$limitQry = $limitOffset;
		}
		$results->totalItems = 0;
		$results->items = array();
		$results->cusData = array();
		$results->arr_ar_ap_report = array();
		$InvoiceSDate =  Input::get("InvoiceSDate");
	    $InvoiceEDate =  Input::get("InvoiceEDate");
	    $optJobStatus = Input::get("optJobStatus");
	    $optCustomer = Input::get("optCustomer");
	    $view =  Input::get("view");
	    $contract_number =  Input::get("contract_number");
	    $jobTypeTask =  Input::get("jobTypeTask");
		$currentSDate = date('m/d/Y',strtotime('01/01/2010'));
	    $currentEDate = date('m/d/Y');
	    $queryPartInvoice = $mainLimit = $queryPart = "";
		if (empty($InvoiceSDate)) $InvoiceSDate = $currentSDate;
	   	if (empty($InvoiceEDate)) $InvoiceEDate = $currentEDate;	
	    $param = $InvoiceSDate.'~~'.$InvoiceEDate.'~~'.$optJobStatus.'~~'.$optCustomer;
	    if(Input::get("page")==''){
			$page = 0;
		} else {
			$page = Input::get("page");
		}
		if(Input::get("view")==''){
			$view = "expand";
		} else {
			$view = Input::get("view");
		}
		$arr_job_nums_not = array('P0','P1','P2','P3','P4','P5','P6','P7','P8','P9','VS','ER');
		$job_nums_query_part ="";
		foreach($arr_job_nums_not as $key => $val){
			$job_nums_query_part .= " AND gpg_job.job_num NOT LIKE '%".$val."%' ";
		}
		$job_nums_query_part .= " AND SUBSTR(LOWER(gpg_job.job_num),1,1) NOT BETWEEN '0' AND '9' ";
		$queryeQuote = "";
	 	$queryfQuote = "";
	 	$queryshQuote = "";
	 	$queryPartMaterialCost = "";
	 	$queryPartLaborCost = "";
	 	$querymQuote = ""; //for grassivy
	 	$queryjQuote = ""; //for special project
		if ($InvoiceSDate!="" and $InvoiceEDate!="") { 
			$queryeQuote = " AND gpg_job_electrical_quote.created_on >= '".date('Y-m-d',strtotime($InvoiceSDate))." 00:00:00' AND gpg_job_electrical_quote.created_on <= '".date('Y-m-d',strtotime($InvoiceEDate))." 23:59:59' ";
			$querymQuote = " AND gpg_job_grassivy_quote.created_on >= '".date('Y-m-d',strtotime($InvoiceSDate))." 00:00:00' AND gpg_job_grassivy_quote.created_on <= '".date('Y-m-d',strtotime($InvoiceEDate))." 23:59:59' ";
			$queryjQuote = " AND gpg_job_special_project_quote.created_on >= '".date('Y-m-d',strtotime($InvoiceSDate))." 00:00:00' AND gpg_job_special_project_quote.created_on <= '".date('Y-m-d',strtotime($InvoiceEDate))." 23:59:59' ";
			$queryfQuote = " AND gpg_field_service_work.created_on >= '".date('Y-m-d',strtotime($InvoiceSDate))." 00:00:00' AND gpg_field_service_work.created_on <= '".date('Y-m-d',strtotime(   	$InvoiceEDate))." 23:59:59' ";	
			$queryshQuote = " AND gpg_shop_work_quote.created_on >= '".date('Y-m-d',strtotime($InvoiceSDate))." 00:00:00' AND gpg_shop_work_quote.created_on <= '".date('Y-m-d',strtotime(   	$InvoiceEDate))." 23:59:59' ";	
			$queryQuote = " AND created_on >= '".date('Y-m-d',strtotime($InvoiceSDate))." 00:00:00' AND created_on <= '".date('Y-m-d',strtotime($InvoiceEDate))." 23:59:59' ";
			$queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
			$queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
			$queryPartInvoice .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND  gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."'  order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
			$queryPartJobNotEx = " AND (gpg_job_cost.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."')";
		}
		if ($optCustomer != "") {
			$queryPart .= " AND gpg_customer.id = '$optCustomer' ";   
			$queryshQuote .= " AND gpg_job.gpg_customer_id = '$optCustomer' ";	
			$queryPartLaborCost .= " AND gpg_job.gpg_customer_id = '$optCustomer' ";	
			$queryPartInvoice .= " AND gpg_job.gpg_customer_id = '$optCustomer' ";	
	 	}
	 	if ($optJobStatus == "completed") $queryPart .= " AND gpg_job.complete = '1' ";
	 	if ($optJobStatus == "notcompleted") $queryPart .= " AND gpg_job.complete = '0' ";
	 	if ($contract_number != "") $queryPart .= " AND gpg_job.contract_number = '$contract_number' ";
	 	if ($jobTypeTask != "" && $jobTypeTask != "ALL") $queryPart .= " AND (gpg_job.task = '".$jobTypeTask."' OR gpg_job.elec_job_type = '".$jobTypeTask."')" ;
		$cusData = array();
		$cusArr = array();
		$cusJobArr = array();
		//-------- Actual labor cost --------// Where Time Type Not In (Holiday, Vacation And Off Day)
		$LaborData = DB::select(DB::raw("select 
				gpg_job.job_num,
				sum(total_wage) as labor_cost,
				gpg_job.gpg_customer_id as cusID
				from  gpg_timesheet_detail, gpg_timesheet, gpg_job 
				where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id 
				and gpg_timesheet_detail.GPG_timetype_id NOT IN ('6','7','8') 
		  		and gpg_timesheet_detail.job_num = gpg_job.job_num $queryPartLaborCost  
		  		group by gpg_timesheet_detail.job_num having labor_cost>0 $limitQry"));
		foreach ($LaborData as $key => $value1){
			$row = (array)$value1;	
			$cusArr[$row['cusID']] = "'".$row['cusID']."'";
			$cusJobArr[$row['job_num']] = "'".$row['job_num']."'";
			$cusData[$row['cusID']][$row['job_num']]['labor_cost'] = $row['labor_cost'];
		}
		//-------- Actual labor cost --------// Where Time Type Not In (Holiday, Vacation And Off Day)
		//-------- Actual material cost --------//
		$MatData = DB::select(DB::raw("select
			   gpg_job.job_num,
			   gpg_job.gpg_customer_id as cusID,
			   sum(amount) as material_cost 
			   from  gpg_job_cost,gpg_job 
			   where gpg_job_cost.job_num = gpg_job.job_num $queryPartMaterialCost 
			   group by gpg_job.job_num  having  material_cost <>0 $limitQry"));
		foreach ($MatData as $key => $value2){
			$row = (array)$value2;
			$cusArr[$row['cusID']] = "'".$row['cusID']."'";
			$cusJobArr[$row['job_num']] = "'".$row['job_num']."'";
			$cusData[$row['cusID']][$row['job_num']]['material_cost'] = $row['material_cost'];
		}
		//-------- Actual material cost --------//
		//-------- Actual Invoice Amt --------//
		$InvData =  DB::select(DB::raw("select 
				gpg_job.job_num,
				gpg_job.gpg_customer_id as cusID,
				sum(gpg_job_invoice_info.invoice_amount) as InvAmt,sum(gpg_job_invoice_info.tax_amount) as InvTax
				from gpg_job_invoice_info,gpg_job 
				where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice  group by gpg_job_invoice_info.gpg_job_id having InvAmt <> 0 $limitQry"));
		foreach ($InvData as $key => $value3){
			$row = (array)$value3;
			$cusArr[$row['cusID']] = "'".$row['cusID']."'";
			$cusJobArr[$row['job_num']] = "'".$row['job_num']."'";
			$cusData[$row['cusID']][$row['job_num']]['invoice_amount'] = $row['InvAmt'];
			$cusData[$row['cusID']][$row['job_num']]['InvTax'] = $row['InvTax'];
		}
		//-------- Actual Invoice Amt --------//
		//-------- f quote --------//
		$fquote = DB::select(DB::raw("select 
			gpg_field_service_work.*,
			gpg_field_service_work.id as quote_id,
			gpg_field_service_work.job_num as quote_attached,
			gpg_job.job_num,
			gpg_job.gpg_customer_id as cusID
			from gpg_field_service_work,gpg_job 
			where gpg_field_service_work.GPG_attach_job_num  = gpg_job.job_num $queryfQuote  group by GPG_attach_job_num $limitQry"));
		foreach ($fquote as $key => $value4){
			$row = (array)$value4;
			$freightQuery = DB::select(DB::raw("select sum(other_charge_cost_price*other_charge_qty) as t_sum from gpg_field_service_work_other where other_charge_description='Freight' and gpg_field_service_work_id = '".$row['id']."'"));
			$freight = @$freightQuery[0]->t_sum;
			$mileageQuery = DB::select(DB::raw("select sum(other_charge_cost_price*other_charge_qty) as t_milage from gpg_field_service_work_other where other_charge_description='Mileage' and gpg_field_service_work_id = '".$row['id']."'"));
			$mileage = @$mileageQuery[0]->t_milage;
			$cusArr[$row['cusID']] 	= "'".$row['cusID']."'";
			$cusJobArr[$row['job_num']] = "'".$row['job_num']."'";
			$cusData[$row['cusID']][$row['job_num']]['quote_id']       = $row['quote_id'];
			$cusData[$row['cusID']][$row['job_num']]['quote_attached'] = $row['quote_attached'];
			$cusData[$row['cusID']][$row['job_num']]['est_inv_amt']    = $row['grand_list_total'];
			$cusData[$row['cusID']][$row['job_num']]['est_mat_cost']   = $row['mat_cost_total']+$row['comp_cost_total']+($row['mat_cost_total']*($row['tax_amount']*.01))+$freight;
			$cusData[$row['cusID']][$row['job_num']]['est_labor_cost'] = $row['labor_cost_total']+($row['sub_cost_total']*($row['hazmat']*.01))+$mileage;
		}
		//-------- f quote --------//
		//-------- e quote --------//
		$equote = DB::select(DB::raw("select 
			gpg_job_electrical_quote.*,
			gpg_job_electrical_quote.id as quote_id,
			gpg_job_electrical_quote.job_num as quote_attached,
			gpg_job.job_num,
			gpg_job.gpg_customer_id as cusID
			from gpg_job_electrical_quote,gpg_job 
			where gpg_job_electrical_quote.GPG_attach_job_num  = gpg_job.job_num $queryeQuote
			AND gpg_job_electrical_quote.job_num IN ( 
			SELECT MAX(job_num)  FROM gpg_job_electrical_quote
			WHERE  job_num  NOT LIKE '%:%' OR (job_num LIKE '%:%' AND gpg_job_electrical_quote.electrical_status ='Won')
			GROUP BY GPG_attach_job_num ) $limitQry"));
		foreach ($equote as $key => $value5){
			$row = (array)$value5;
			$cusArr[$row['cusID']] = "'".$row['cusID']."'";
			$cusJobArr[$row['job_num']] = "'".$row['job_num']."'";
			$cusData[$row['cusID']][$row['job_num']]['quote_id'] = $row['quote_id'];
			$cusData[$row['cusID']][$row['job_num']]['quote_attached'] = $row['quote_attached'];
			$cusData[$row['cusID']][$row['job_num']]['est_inv_amt']    = $row['grand_total']+$row['subquote_total_cost'];
			$cusData[$row['cusID']][$row['job_num']]['est_mat_cost']   = $row['grand_total_material']+$row['subquote_material_cost'];
			$cusData[$row['cusID']][$row['job_num']]['est_labor_cost'] = $row['grand_total_labor']+$row['subquote_labor_cost']; 
		}	
		//-------- e quote --------//
		//-------- m quote --------//
		$mquote = DB::select(DB::raw("select 
			gpg_job_grassivy_quote.*,
			gpg_job_grassivy_quote.id as quote_id,
			gpg_job_grassivy_quote.job_num as quote_attached,
			gpg_job.job_num,
			gpg_job.gpg_customer_id as cusID
			from gpg_job_grassivy_quote,gpg_job 
			where gpg_job_grassivy_quote.GPG_attach_job_num  = gpg_job.job_num $querymQuote
			AND gpg_job_grassivy_quote.job_num IN ( 
			SELECT MAX(job_num)  FROM gpg_job_grassivy_quote
			WHERE  job_num  NOT LIKE '%:%' OR (job_num LIKE '%:%' AND gpg_job_grassivy_quote.grassivy_status ='Won')
			GROUP BY GPG_attach_job_num ) $limitQry"));
		foreach ($mquote as $key => $value6){
			$row = (array)$value6;
			$cusArr[$row['cusID']] = "'".$row['cusID']."'";
			$cusJobArr[$row['job_num']] = "'".$row['job_num']."'";
			$cusData[$row['cusID']][$row['job_num']]['quote_id'] = $row['quote_id'];
			$cusData[$row['cusID']][$row['job_num']]['quote_attached'] = $row['quote_attached'];
			$cusData[$row['cusID']][$row['job_num']]['est_inv_amt'] = $row['grand_total']+$row['subquote_total_cost'];
			$cusData[$row['cusID']][$row['job_num']]['est_mat_cost'] = $row['grand_total_material']+$row['subquote_material_cost'];
			$cusData[$row['cusID']][$row['job_num']]['est_labor_cost'] = $row['grand_total_labor']+$row['subquote_labor_cost']; 
		}
		//-------- m quote --------//
		//-------- j quote --------//
		$jquote = DB::select(DB::raw("select 
			gpg_job_special_project_quote.*,
			gpg_job_special_project_quote.id as quote_id,
			gpg_job_special_project_quote.job_num as quote_attached,
			gpg_job.job_num,
			gpg_job.gpg_customer_id as cusID
			from gpg_job_special_project_quote,gpg_job 
			where gpg_job_special_project_quote.GPG_attach_job_num  = gpg_job.job_num $queryjQuote
			AND gpg_job_special_project_quote.job_num IN ( 
			SELECT MAX(job_num)  FROM gpg_job_special_project_quote
			WHERE  job_num  NOT LIKE '%:%' OR (job_num LIKE '%:%' AND gpg_job_special_project_quote.special_project_status ='Won')
			GROUP BY GPG_attach_job_num ) $limitQry"));
		foreach ($jquote as $key => $value7){
			$row = (array)$value7;
			$cusArr[$row['cusID']] = "'".$row['cusID']."'";
			$cusJobArr[$row['job_num']] = "'".$row['job_num']."'";
			$cusData[$row['cusID']][$row['job_num']]['quote_id']       = $row['quote_id'];
			$cusData[$row['cusID']][$row['job_num']]['quote_attached'] = $row['quote_attached'];
			$cusData[$row['cusID']][$row['job_num']]['est_inv_amt']    = $row['grand_total']+$row['subquote_total_cost'];
			$cusData[$row['cusID']][$row['job_num']]['est_mat_cost']   = $row['grand_total_material']+$row['subquote_material_cost'];
			$cusData[$row['cusID']][$row['job_num']]['est_labor_cost'] = $row['grand_total_labor']+$row['subquote_labor_cost']; 
		}
		//-------- j quote --------//
		//-------- SH quote --------//
		$shquote = DB::select(DB::raw("select 
			gpg_shop_work_quote.*,
			gpg_shop_work_quote.id as quote_id,
			gpg_shop_work_quote.job_num as quote_attached,
			gpg_job.job_num,
			gpg_job.gpg_customer_id as cusID
			from gpg_shop_work_quote,gpg_job 
			where gpg_shop_work_quote.GPG_attach_job_num  = gpg_job.job_num $queryshQuote  group by GPG_attach_job_num $limitQry"));
		foreach ($shquote as $key => $value8){
			$row = (array)$value8;
			$freightQuery = DB::select(DB::raw("SELECT SUM(other_charge_cost_price*other_charge_qty) as t_freigt FROM gpg_field_service_work_other WHERE other_charge_description='Freight' AND gpg_field_service_work_id = '".$row['id']."'"));
			$freight = @$freightQuery[0]->t_freigt;
			$mileageQuery = DB::select(DB::raw("SELECT SUM(other_charge_cost_price*other_charge_qty) as t_mialge FROM gpg_field_service_work_other WHERE other_charge_description='Mileage' AND gpg_field_service_work_id = '".$row['id']."'"));
			$mileage = @$mileageQuery[0]->t_mialge;
			$cusArr[$row['cusID']] = "'".$row['cusID']."'";
			$cusJobArr[$row['job_num']] = "'".$row['job_num']."'";
			$cusData[$row['cusID']][$row['job_num']]['quote_id']       = $row['quote_id'];
			$cusData[$row['cusID']][$row['job_num']]['quote_attached'] = $row['quote_attached'];
			$cusData[$row['cusID']][$row['job_num']]['est_inv_amt']    = $row['grand_list_total'];
			$cusData[$row['cusID']][$row['job_num']]['est_mat_cost']   = $row['mat_cost_total']+$row['comp_cost_total']+($row['mat_cost_total']*($row['tax_amount']*.01))+$freight;
			$cusData[$row['cusID']][$row['job_num']]['est_labor_cost'] = $row['labor_cost_total']+($row['sub_cost_total']*($row['hazmat']*.01))+$mileage;
		}
		$results->cusData = $cusData;
		//-------- SH quote --------//
		//-------- AR and AP info -----//
		$arr_ar_ap_report = array();
		$qry_ar_ap_report = DB::select(DB::raw("SELECT
	  		job_num,
	  		SUM(amount) as amnt,
	  		report_type
			FROM gpg_job_due_amount
			GROUP BY job_num,report_type
			ORDER BY job_num,report_type"));
		foreach ($qry_ar_ap_report as $key => $value9){
			$row_ar_ap = (array)$value9;
			if($row_ar_ap['report_type']==2) // AP
				$arr_ar_ap_report[$row_ar_ap['job_num']]['AP'] = $row_ar_ap['amnt'];
			if($row_ar_ap['report_type']==1) // AR
				$arr_ar_ap_report[$row_ar_ap['job_num']]['AR'] = $row_ar_ap['amnt'];
		}
		$results->arr_ar_ap_report = $arr_ar_ap_report;
		//-------- AR and AP info END-----//
		$cusDataIn = implode(",", $cusArr);
		$cuscusJobDataIn = implode(",", $cusJobArr);
		//-------- paging --------//
		$t_rec = DB::select(DB::raw("select 
			count(gpg_customer.id) as t_count
			from  gpg_customer,gpg_job
			where gpg_job.gpg_customer_id= gpg_customer.id $queryPart 
			AND gpg_customer.id IN ($cusDataIn) AND gpg_job.job_num IN ($cuscusJobDataIn) $job_nums_query_part order by gpg_customer.name asc  "));
		$results->totalItems = @$t_rec[0]->t_count;
		//-------- paging --------//
		$job_view_order_by = " gpg_customer.name ";
		if ($view == 'jobView'){
			$job_view_order_by = " sum_inv_amount_after DESC, complete ASC, actual_material_cost DESC ";
		}
		$q=DB::select(DB::raw("select 
			gpg_customer.id,gpg_customer.name,
			gpg_job.job_num, gpg_job.id as jobId, gpg_job.rental_status,
			gpg_job.GPG_job_type_id, IF(gpg_job.complete=1,1,0) as complete, gpg_job.date_completion,
			(SELECT IFNULL(IF(SUM(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)<=0,0,SUM(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)),0) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job_invoice_info.invoice_date > '".date('Y-m-d',strtotime($InvoiceEDate))."')as sum_inv_amount_after,
			(SELECT SUM(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job_invoice_info.invoice_date < '".date('Y-m-d',strtotime($InvoiceSDate))."')as sum_inv_amount_before,
			(SELECT COUNT(*) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id) as invoices,
			(SELECT IFNULL(SUM(amount),0) FROM gpg_job_cost WHERE gpg_job_cost.job_num = gpg_job.job_num AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ) as actual_material_cost
			,gpg_job.contract_number,gpg_job.elec_job_type,gpg_job.task
			FROM 
			gpg_customer,gpg_job
			WHERE
			gpg_job.gpg_customer_id= gpg_customer.id $queryPart AND gpg_customer.id IN ($cusDataIn) AND gpg_job.job_num IN ($cuscusJobDataIn) $job_nums_query_part
			ORDER BY $job_view_order_by"));
		$Cus =  DB::select(DB::raw("select 
			gpg_customer.id,gpg_customer.name,
			gpg_job.job_num, gpg_job.id as jobId, gpg_job.rental_status,
			gpg_job.GPG_job_type_id, IF(gpg_job.complete=1,1,0) as complete, gpg_job.date_completion,
			(SELECT IFNULL(IF(SUM(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)<=0,0,SUM(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount)),0) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job_invoice_info.invoice_date > '".date('Y-m-d',strtotime($InvoiceEDate))."')as sum_inv_amount_after,
			(SELECT SUM(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id AND gpg_job_invoice_info.invoice_date < '".date('Y-m-d',strtotime($InvoiceSDate))."')as sum_inv_amount_before,
			(SELECT COUNT(*) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id) as invoices,
			(SELECT IFNULL(SUM(amount),0) FROM gpg_job_cost WHERE gpg_job_cost.job_num = gpg_job.job_num AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ) as actual_material_cost
			,gpg_job.contract_number,gpg_job.elec_job_type,gpg_job.task
			FROM 
			gpg_customer,gpg_job
			WHERE
			gpg_job.gpg_customer_id= gpg_customer.id $queryPart AND gpg_customer.id IN ($cusDataIn) AND gpg_job.job_num IN ($cuscusJobDataIn) $job_nums_query_part
			ORDER BY $job_view_order_by $limitQry"));
		$data = array();
		foreach ($Cus as $key => $value11) {
			$data[] = (array)$value11;
		}
		$results->items = $data;
		
		return $results;
	}

	/*
	* proFixturesUsageFreq
	*/
	public function proFixturesUsageFreq(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getProFixtUsageRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('qc_reports.pro_fixtures_usage_freq', $params);
	}
	public function getProFixtUsageRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		$results = new \StdClass;
		$results->page = $page;
		$limitQry = "";
		# set offset (for excel export offset will not apply)
		$limitOffset = '';
		if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}
		$results->totalItems = 0;
		$results->items = array();
		$order_by = "ORDER BY gjespf.fixture_name";
		$result = array();
		$profixQuery2 = DB::select(DB::raw("SELECT DISTINCT(gjes.gpg_job_electrical_subquote_proposed_fixtures_id) as ids FROM gpg_job_electrical_subquote gjes, gpg_job_electrical_subquote_proposed_fixtures gjespf WHERE gjespf.id = gjes.gpg_job_electrical_subquote_proposed_fixtures_id ".$order_by." $limitOffset"));
		$str_ids = "";
		foreach ($profixQuery2 as $key => $rtemp){
			$str_ids.= $rtemp->ids.",";
		}
		$profixQuery3 = DB::select(DB::raw("SELECT DISTINCT(gjes.gpg_job_electrical_subquote_proposed_fixtures_id) FROM gpg_job_electrical_subquote gjes, gpg_job_electrical_subquote_proposed_fixtures gjespf	WHERE gjespf.id = gjes.gpg_job_electrical_subquote_proposed_fixtures_id ".$order_by));
		if(strlen($str_ids)>0)
		{
			$str_ids = substr($str_ids,0,strlen($str_ids)-1);
			$profixQuery = DB::select(DB::raw("SELECT
				gjespf.id,
				gjespf.fixture_name,
				gjes.gpg_job_electrical_subquote_proposed_fixtures_id,
				SUM(gjes.fixture_quantity_pro) AS pro_qty_used,
				gjeq.job_num,
				gjeq.id as gjeqid,
				(SELECT COUNT(*) FROM gpg_job_electrical_subquote WHERE gpg_job_electrical_subquote.job_electrical_quote_id=gjeq.id AND gpg_job_electrical_subquote.gpg_job_electrical_subquote_proposed_fixtures_id=gjespf.id ) AS occurence,
				(SELECT name FROM gpg_customer WHERE id = gjeq.GPG_customer_id) as cus_name
			FROM
				gpg_job_electrical_subquote gjes,
				gpg_job_electrical_subquote_proposed_fixtures gjespf,
				gpg_job_electrical_quote gjeq
			WHERE
				gjes.gpg_job_electrical_subquote_proposed_fixtures_id = gjespf.id AND
				gjeq.id = gjes.job_electrical_quote_id and
				gjes.gpg_job_electrical_subquote_proposed_fixtures_id IN (".$str_ids.")
			GROUP BY gjeq.job_num, gjespf.id ".$order_by));
			foreach ($profixQuery as $key => $value) {
				$result[] = (array)$value;
			}
			$results->items = $result;
		}
		$results->totalItems =	count($profixQuery3);
		return $results;
	}

	/*
	* customerJobDetailReport
	*/
	public function customerJobDetailReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getCustDetailJobRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$customers = DB::table('gpg_customer')->where('status','=','A')->orderBy('name')->lists('name','id');
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'customers'=>$customers);
		return View::make('qc_reports.customer_job_detail_report', $params);
	}
	public function getCustDetailJobRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		$results = new \StdClass;
		$results->page = $page;
		$limitQry = "";
		# set offset (for excel export offset will not apply)
		$limitOffset = '';
		if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}
		$results->totalItems = 0;
		$results->items = array();
		$optCustomer = Input::get("optCustomer");
		$InvoiceSDate = Input::get("InvoiceSDate");
		$InvoiceEDate = Input::get("InvoiceEDate");
		$cusData = array();
		$DSQL = "";
		$INV = "";
		$cusId = '';
		$cusDataIn = '';
		if ($optCustomer!="") {
			$DSQL.= " AND id = '$optCustomer'"; 
		}
		if ($InvoiceSDate!="" and $InvoiceEDate!=""){
			$INV .= " AND c.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND c.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
		}
		elseif ($InvoiceSDate!="") {
			$INV .= " AND c.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
		}
		$DSQL .= " order by name asc ";
    	if (strlen($INV) > 0) {
			$result = DB::select(DB::raw("select g.job_num, a.name, a.id,
			sum(if(c.invoice_amount<>0,(c.invoice_amount-c.tax_amount),0))  as inv_amt,
			c.invoice_number
			from gpg_job_invoice_info c, gpg_job g, gpg_customer a 
			where g.id = c.gpg_job_id 
			and g.GPG_customer_id = a.id
			$INV 
			group by g.job_num  order by a.name,g.job_num")); 
			foreach ($result as $key => $row){
				$cusId .= $row->id.",";
			}
			$cusId = substr($cusId ,0,strlen($cusId)-1);
			$cus     = DB::select(DB::raw("select id from gpg_customer WHERE id in ( $cusId ) $DSQL  limit $start,$limit"));
			$cus_total = DB::select(DB::raw("select id from gpg_customer WHERE id in ( $cusId ) $DSQL "));
		} else {	
			$cus = DB::select(DB::raw("select id from gpg_customer WHERE 1 $DSQL  limit $start,$limit"));
			$cus_total = DB::select(DB::raw("select id from gpg_customer WHERE 1 $DSQL "));
		}
		foreach ($cus as $key => $row) {
			$cusDataIn .= $row->id.",";
		}
		$cusDataIn = substr($cusDataIn ,0,strlen($cusDataIn)-1);
		$result1 = DB::select(DB::raw("select g.job_num,
			a.name,
			a.id,
			sum(if(c.invoice_amount<>0,(c.invoice_amount-c.tax_amount),0))  as inv_amt,
			c.invoice_number
			from gpg_job_invoice_info c, gpg_job g, gpg_customer a 
			where g.id = c.gpg_job_id 
			and g.GPG_customer_id = a.id
			and a.id IN (".$cusDataIn.")
			$INV 
			group by g.job_num  order by a.name,g.job_num")); 
		$results->totalItems = count($cus_total);
		foreach ($result1 as $key => $value1) {
			$rowCus = (array)$value1;
			$cusData[$rowCus['id']]['name'] = 	$rowCus['name'];
			// electrical job list
			if(strtoupper(substr($rowCus['job_num'],0,3)) == 'GPG' ) {
				$cusData[$rowCus['id']]['gpg_job'][$rowCus['job_num']] = $rowCus['inv_amt'].'~~'.$rowCus['invoice_number'];
			}
			// grassivy job list
			if(strtoupper(substr($rowCus['job_num'],0,2)) == 'IG' ) {
				$cusData[$rowCus['id']]['gpg_grassivy_job'][$rowCus['job_num']] = $rowCus['inv_amt'].'~~'.$rowCus['invoice_number'];
			}
			// special project job list
			if(strtoupper(substr($rowCus['job_num'],0,2)) == 'LK' ) {
				$cusData[$rowCus['id']]['gpg_special_project_job'][$rowCus['job_num']] = $rowCus['inv_amt'].'~~'.$rowCus['invoice_number'];
			}
			// shop work list 
			if(strtoupper(substr($rowCus['job_num'],0,2)) == 'SH' )
				$cusData[$rowCus['id']]['sh_job'][$rowCus['job_num']] = $rowCus['inv_amt'].'~~'.$rowCus['invoice_number'];
			// service job list 
			if( strtoupper(substr($rowCus['job_num'],0,2)) == 'PM' || strtoupper(substr($rowCus['job_num'],0,2)) == 'TC' || strtoupper(substr($rowCus['job_num'],0,2)) == 'BQ' || strtoupper(substr($rowCus['job_num'],0,2)) == 'QT' || strtoupper(substr($rowCus['job_num'],0,3)) == 'UPS' || strtoupper(substr($rowCus['job_num'],0,2)) == 'BO' || (strtoupper(substr($rowCus['job_num'],0,2)) == 'RN' && strtoupper(substr($rowCus['job_num'],0,3)) != 'RNT'))
				$cusData[$rowCus['id']]['service_job'][$rowCus['job_num']] = $rowCus['inv_amt'].'~~'.$rowCus['invoice_number'];
			// rent job list 
			if(strtoupper(substr($rowCus['job_num'],0,3)) == 'RNT' )
				$cusData[$rowCus['id']]['rnt_job'][$rowCus['job_num']] = $rowCus['inv_amt'].'~~'.$rowCus['invoice_number'];
		}
		$results->items = $cusData;
		return $results;
	}
	/*
	* overHeadBudgetingReport
	*/
	public function overHeadBudgetingReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getOHBudgetRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$parent_arr = array();
  		$parent = DB::select(DB::raw("select id,concat(expense_gl_code,' ',description) as name from gpg_expense_gl_code where status='A' and parent_id = 0"));
  		foreach ($parent as $key => $value) {
  			$parent_arr[$value->id] = $value->name;
  		}
  		$gpg_expense_gl_type = DB::table('gpg_expense_gl_type')->lists('type','id');
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'arr_tags_names'=>$data->arr_tags_names,'tagging'=>$data->tagging,'arr_heads_name'=>$data->arr_heads_name,'groupBy_new'=>$data->groupBy_new,'tags_arr2'=>$data->tags_arr2,'arr_final_totals'=>$data->arr_final_totals,'parent_arr'=>$parent_arr,'gpg_expense_gl_type'=>$gpg_expense_gl_type);
		return View::make('qc_reports.over_head_budgeting_report', $params);
	}
	public function getOHBudgetRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		$results = new \StdClass;
		$results->page = $page;
		$limitQry = "";
		# set offset (for excel export offset will not apply)
		$limitOffset = '';
		if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}
		$results->totalItems = 0;
		$results->tagging = 0;
		$results->groupBy_new = '';
		$results->items = array();
		$results->tags_arr2 = array();
		$results->arr_heads_name = array();
		$results->arr_tags_names = array();
		$results->arr_final_totals = array();
		$SDate =  Input::get("SDate");
	    $EDate =  Input::get("EDate");
	    $CView = Input::get("view");
	    $groupBy = Input::get("groupBy");
	    $ohArr = array();
	    $queryPart ='';
	    $currentSDate = date('m/d/Y',strtotime('01/01/2010'));
	    $currentEDate = date('m/d/Y');
	    if (empty($SDate)) $SDate = $currentSDate;
	    if (empty($EDate)) $EDate = $currentEDate;
	    if ($CView == 'custom_view') { // main if condition for view
		   	if (empty($orderBy)) $orderBy = 'gpg_over_head_budget.gpg_expense_gl_code_id';
   			if ($SDate!="" && $EDate!="") { 
			 	 $queryPart = " AND gpg_over_head_budget.date >= '".date('Y-m-d',strtotime($SDate))."' AND gpg_over_head_budget.date <= '".date('Y-m-d',strtotime($EDate))."' ";
			}
			$result_parent = DB::select(DB::raw("SELECT 
					gpg_expense_gl_code.parent_id,
					gpg_expense_gl_code.id,
					gpg_expense_gl_code.description,
					gpg_expense_gl_code.expense_gl_code,
					SUM(gpg_over_head_budget.credit) AS credit_sum,
					SUM(gpg_over_head_budget.debit) AS debit_sum,
					SUM(gpg_over_head_budget.amount) AS amount_sum,
					IFNULL(gpg_expense_gl_code.gpg_expense_gl_tags,0) AS gl_tags
				FROM
					gpg_over_head_budget,
					gpg_expense_gl_code 
				WHERE
					gpg_over_head_budget.gpg_expense_gl_code_id = gpg_expense_gl_code.id
					".$queryPart."
				GROUP BY 
					gpg_expense_gl_code.id
				ORDER BY gpg_expense_gl_code.gpg_expense_gl_tags,$orderBy"));
			$data_arr = array();
			$tags_arr = array();
			$tags_arr2 = array();
			foreach ($result_parent as $key => $arr) {
				$data_arr[$arr->id] = $arr;
			}
			$gl_tags_rs = DB::select(DB::raw("SELECT id,value FROM gpg_settings WHERE NAME LIKE '_gl_tags%'"));
			$arr_tags_names = array(0 => 'No Tag');
			foreach ($gl_tags_rs as $key => $gl_vals) {
				$arr_tags_names[$gl_vals->id] = $gl_vals->value;
			}
			$this->get_childs(0);
			$GLOBALS['arr_final_totals'] = array();
			$arr = @$GLOBALS['parent_arr'][0];
			if (isset($arr) && !empty($arr))
				foreach($arr as $key => $val){
					$this->set_totals($val);
				}
			$results->tags_arr2 = $GLOBALS['tags_arr2'];
			$results->arr_final_totals = $GLOBALS['arr_final_totals'];
	    }else{ // main else condition for view
		    if (empty($groupBy)) 
		    	$groupBy = 'gpg_expense_gl_code_id';
		    $tagging = 0;
			if(preg_match("/tags/",$groupBy)){
				$tagging = 1;
				$groupBy_new = 'gpg_expense_gl_code_id';
			}else{
				$tagging = 0;
				$groupBy_new = $groupBy;
			}	   
			if ($SDate!="" && $EDate!="") {
		 		$queryPart = " AND date >= '".date('Y-m-d',strtotime($SDate))."' AND date <= '".date('Y-m-d',strtotime($EDate))."' ";
			}
		 	$gl_tags_rs = DB::select(DB::raw("SELECT description, gpg_expense_gl_code.id, IFNULL(GROUP_CONCAT(CONCAT(gpg_expense_gl_code_tags_detail.tag_group_id,'~',gpg_expense_gl_code_tags_detail.gpg_expense_gl_code_tags_parent_id,'~',gpg_expense_gl_code_tags_detail.gpg_expense_gl_code_tags_child_id)),0) AS tag_detail
					FROM gpg_expense_gl_code
		  			LEFT JOIN gpg_expense_gl_code_tags_detail
		    		ON gpg_expense_gl_code_tags_detail.gpg_expense_gl_code_id = gpg_expense_gl_code.id
					GROUP BY gpg_expense_gl_code.id"));
			$group_id = explode("_",$groupBy);
			$arr_oh_tags_names = array();
			$all_gl_ids = array();
			foreach ($gl_tags_rs as $key => $value1){
				$gl_vals = (array)$value1;
				if($gl_vals['tag_detail']==0){
					$arr_oh_tags_names[$gl_vals['tag_detail']][] = $gl_vals['id'];
				}
				else{
					if(!preg_match("/".$group_id[1]."/",$gl_vals['tag_detail']))
						$arr_oh_tags_names[0][] = $gl_vals['id'];
					else
					{
						$group_ex = explode(",",$gl_vals['tag_detail']);
						foreach($group_ex as $k=>$v)
						{
							$v2 = explode("~",$v);
							if($v2[0] == $group_id[1])
							{
								$arr_oh_tags_names[$v2[0]][$v2[1]][$v2[2]][] = $gl_vals['id'];
							}
						}
					}
				}
			}
			krsort($arr_oh_tags_names);
			$sequence = "";
			if(isset($arr_oh_tags_names[$group_id[1]]) && is_array($arr_oh_tags_names[$group_id[1]]))
			foreach($arr_oh_tags_names[$group_id[1]] as $parent_k => $parent_v){
				foreach($parent_v as $child_k => $child_v)
				{
					if(strlen($sequence>0))
						$sequence.= ",";
					$sequence.=implode(",",$child_v);
				}
			}
			if(strlen($sequence>0))
				$sequence.= ",";
			if(sizeof($arr_oh_tags_names[0])>0)
			$sequence.= implode(",",$arr_oh_tags_names[0]);
			//-------- paging --------//
			if($tagging==1){
				$t_rec = DB::select(DB::raw("select count(id) as t_count from gpg_over_head_budget where 1 $queryPart order by FIELD(gpg_expense_gl_code_id,".$sequence.")"));
				$results->totalItems = @$t_rec[0]->t_count;
			}
			else{
				$t_rec = DB::select(DB::raw("select count(id) as t_count from gpg_over_head_budget where 1 $queryPart order by $groupBy_new"));
				$results->totalItems = @$t_rec[0]->t_count;
			}
			//-------- Actual labor cost --------//
			if($tagging==1){
				$ohData = DB::select(DB::raw("select * from gpg_over_head_budget where 1 $queryPart order by FIELD(gpg_expense_gl_code_id,".$sequence.")  limit $start,$limit"));
			}
			else{
				$ohData = DB::select(DB::raw("select * from gpg_over_head_budget where 1 $queryPart order by $groupBy_new  limit $start,$limit"));
			}
			foreach ($ohData as $key => $value2){
				$row = (array)$value2;		
				$ohArr[$row[$groupBy_new]]['gpg_expense_gl_code_id'][] = $row['gpg_expense_gl_code_id'];
				$ohArr[$row[$groupBy_new]]['type'][] = $row['type'];
				$ohArr[$row[$groupBy_new]]['num'][] = $row['num'];
				$ohArr[$row[$groupBy_new]]['name'][] = $row['name'];
				$ohArr[$row[$groupBy_new]]['source_name'][] = $row['source_name'];
				$ohArr[$row[$groupBy_new]]['memo'][] = $row['memo'];
				$ohArr[$row[$groupBy_new]]['class'][] = $row['class'];
				$ohArr[$row[$groupBy_new]]['clr'][] = $row['clr'];
				$ohArr[$row[$groupBy_new]]['debit'][] = $row['debit'];
				$ohArr[$row[$groupBy_new]]['credit'][] = $row['credit'];
				$ohArr[$row[$groupBy_new]]['date'][] = $row['date'];
				$ohArr[$row[$groupBy_new]]['modified_by'][] = $row['modified_by'];
				$ohArr[$row[$groupBy_new]]['last_modified_on'][] = $row['last_modified_on'];
			}
			$results->items = $ohArr;
			$expense_heads_rs = DB::select(DB::raw("SELECT id, CONCAT(gpg_expense_gl_code.expense_gl_code,'-',gpg_expense_gl_code.description) AS account_name,
		  		IFNULL(gpg_expense_gl_code.gpg_expense_gl_tags,0) AS tag_id FROM gpg_expense_gl_code"));
			$arr_heads_name = array();
			foreach ($expense_heads_rs as $key => $value3){
				$expense_heads_vals = (array)$value3;
				$arr_heads_name[$expense_heads_vals['id']] = array(0=>$expense_heads_vals['account_name']);
				$tag_arr = explode(".",$expense_heads_vals['tag_id']);
				foreach($tag_arr as $k => $v)
				{
					$arr_heads_name[$expense_heads_vals['id']][] = $v;
				}
			}
			$results->arr_heads_name = $arr_heads_name;
			$gl_tags_rs = DB::select(DB::raw("SELECT id,value FROM gpg_settings WHERE NAME LIKE '_gl_tags%'"));
			$arr_tags_names = array("gpg_expense_gl_code_id"=>"Account","source_name"=>"Source Name","class"=>"Class");
			$arr_tags_names_only = array('0'=>"No Tag");
			foreach ($gl_tags_rs as $key => $value4) {
				$gl_vals = (array)$value4;
				$arr_tags_names["tags_".$gl_vals['id']] = $gl_vals['value'];
				$arr_tags_names_only[$gl_vals['id']] = $gl_vals['value'];
			}
			$oh_tags_parent_child_names = array(0=>"No Tag");
			$gl_tags_rs = DB::table('gpg_expense_gl_code_tags')->select('id','tag_name')->get();
			foreach ($gl_tags_rs as $key => $gl_tags_arr){
				$oh_tags_parent_child_names[$gl_tags_arr->id] = $gl_tags_arr->tag_name;
			}
			$results->arr_tags_names = $arr_tags_names;
			$results->tagging = $tagging;
			$results->groupBy_new = $groupBy_new;
	    }
		return $results;
	}
	/*
	* getGLCodeData
	*/
	public function getGLCodeData(){
		$id = Input::get('id');
		$qry = DB::select(DB::raw("select *,(select count(id) from gpg_expense_gl_code where parent_id=a.id) as parentCnt from gpg_expense_gl_code a where id = '$id'"));
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr = array('parent_id'=>$value->parent_id,'expense_gl_code'=>$value->expense_gl_code,'description'=>$value->description,'status'=>$value->status,'parentCnt'=>$value->parentCnt,'gpg_expense_gl_type_id'=>$value->gpg_expense_gl_type_id,'exclude_from_oh'=>$value->exclude_from_oh);
		}	
		return $data_arr;	
	}
	/*
	* updateGLCodeData
	*/
	public function updateGLCodeData(){
		$id = Input::get("id");
		$_expense_gl_code = Input::get("expense_gl_code");
		$_parent_id = Input::get("parent_id");
		$_gpg_expense_gl_type_id = Input::get("gpg_expense_gl_type_id");
		$_description = Input::get("description");
		$_status = Input::get("status");
		$query = DB::table('gpg_expense_gl_code')->where('id','=',$id)->update(array('expense_gl_code'=>$_expense_gl_code,'parent_id'=>$_parent_id,'gpg_expense_gl_type_id'=>$_gpg_expense_gl_type_id,'description'=>$_description,'status'=>$_status,'modified_on'=>date('Y-m-d')));
		return 1;
	}

	/*
	* getOHBudgetInfo
	*/
	public function getOHBudgetInfo(){
		$SDate =  Input::get("SDate");
		$EDate =  Input::get("EDate");
		$orderBy = Input::get("orderBy");
		$oh_id = Input::get('id');
		$currentSDate = date('m/d/Y',strtotime('01/01/2010'));
		$currentEDate = date('m/d/Y');
		if (empty($SDate)) $SDate = $currentSDate;
		if (empty($EDate)) $EDate = $currentEDate;
		if (empty($orderBy)) $orderBy = 'gpg_over_head_budget.gpg_expense_gl_code_id';
		if ($SDate!="" && $EDate!="") { 
		 	 $queryPart = " AND gpg_over_head_budget.date >= '".date('Y-m-d',strtotime($SDate))."' AND gpg_over_head_budget.date <= '".date('Y-m-d',strtotime($EDate))."' ";
		}
		$result_parent = DB::select(DB::raw("SELECT 
					gpg_expense_gl_code.id,
					gpg_expense_gl_code.description,
					gpg_expense_gl_code.expense_gl_code,
					SUM(gpg_over_head_budget.credit) AS credit_sum,
					SUM(gpg_over_head_budget.debit) AS debit_sum,
					SUM(gpg_over_head_budget.amount) AS amount_sum
				FROM
					gpg_over_head_budget,
					gpg_expense_gl_code 
				WHERE
					gpg_over_head_budget.gpg_expense_gl_code_id = gpg_expense_gl_code.id
					".$queryPart."
					GROUP BY 
					gpg_expense_gl_code.id
					ORDER BY $orderBy"));
		$data_arr = array();
		foreach ($result_parent as $key => $arr){
			$data_arr[$arr->id] = $arr;
		}
		$this->get_childs($oh_id);
		$arr = @$GLOBALS['parent_arr'][$oh_id];
		if(isset($arr) && is_array($arr))
		foreach($arr as $key => $val){
			$this->set_totals($val);
		}
		$data_result = DB::select(DB::raw("SELECT date, last_modified_on, modified_by, type,  num, name, source_name, memo, class, clr, split,debit,credit,  amount FROM gpg_over_head_budget WHERE gpg_expense_gl_code_id = '".$oh_id."' $queryPart ORDER BY $orderBy"));
		$gl_tags_rs = DB::select(DB::raw("SELECT id,value FROM gpg_settings WHERE NAME LIKE '_gl_tags%'"));
		$arr_tags_names = array(0 => 'No Tag');
		foreach ($gl_tags_rs as $key => $gl_vals) {
			$arr_tags_names[$gl_vals->id] = $gl_vals->value;
		}
		$loop = 0;
		$str = '';
		$total_credit = 0;
		$total_debit = 0;
		$total_amount = 0;
		foreach ($data_result as $key => $value1) {
			$obj = (array)$value1;
			$str .= "<tr>";
			foreach($obj as $key => $val){
				if(!is_numeric($key)){
					if($key == 'last_modified_on' || $key == 'date'){
						if($val!="")
								$str .= "<td>".date('m/d/Y',strtotime($val))."</td>";
							else
								$str .= "<td>-</td>";
					}
					elseif($key == 'credit' || $key == 'debit')
						$str .=  "<td bgcolor='#FFFFCC' align='right'>".'$'.number_format($val,2)."</td>";
					elseif($key == 'amount')
						$str .= "<td bgcolor='#FFC1C1' align='right'>".'$'.number_format($val,2)."</td>";
					else
						$str .= "<td>".htmlentities($val)."</td>";
				}
			}
			$str .= "</tr>";
			$total_credit += $obj['credit'];
			$total_debit += $obj['debit'];
			$total_amount += $obj['amount'];
			$loop++;
		}
		if($total_credit > 0 || $total_debit > 0 || $total_amount > 0)
		$str .= "<tr height='20px'><td colspan='11'></td><td bgcolor='#FFFFCC' align='right'><strong>".'$'.number_format($total_debit,2)."</strong></td><td bgcolor='#FFFFCC' align='right'><strong>".'$'.number_format($total_credit,2)."</strong></td><td bgcolor='#FFC1C1' align='right'><strong>".'$'.number_format($total_amount,2)."</strong></td></tr>";

		return $str;
	}

	/*
	* glDetailReportExport
	*/
	public function glDetailReportExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		Excel::create('GLDetailsReportExport', function($excel) {
		    $excel->sheet('GLDetailsReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
			$SDate =  Input::get("SDate");
		    $EDate =  Input::get("EDate");
		    $groupBy = Input::get("groupBy");
		    $ohArr = array();
		    $queryPart ='';	 
		    $currentSDate = date('m/d/Y',strtotime('01/01/2010'));
		    $currentEDate = date('m/d/Y');
		    if (empty($SDate)) $SDate = $currentSDate;
		    if (empty($EDate)) $EDate = $currentEDate;
		    if (empty($groupBy)) $groupBy = 'gpg_expense_gl_code_id';
			if ($SDate!="" && $EDate!="") { 
		 		$queryPart = " AND date >= '".date('Y-m-d',strtotime($SDate))."' AND date <= '".date('Y-m-d',strtotime($EDate))."' ";
			}
		 	//-------- Actual labor cost --------//
			$ohData = DB::select(DB::raw("select * from gpg_over_head_budget where 1 $queryPart order by $groupBy"));
			foreach ($ohData as $key => $value2){
				$row = (array)$value2;
				$ohArr[$row[$groupBy]]['gpg_expense_gl_code_id'][] = $row['gpg_expense_gl_code_id'];
				$ohArr[$row[$groupBy]]['type'][] = $row['type'];
				$ohArr[$row[$groupBy]]['num'][] = $row['num'];
				$ohArr[$row[$groupBy]]['name'][] = $row['name'];
				$ohArr[$row[$groupBy]]['source_name'][] = $row['source_name'];
				$ohArr[$row[$groupBy]]['memo'][] = $row['memo'];
				$ohArr[$row[$groupBy]]['class'][] = $row['class'];
				$ohArr[$row[$groupBy]]['clr'][] = $row['clr'];
				$ohArr[$row[$groupBy]]['debit'][] = $row['debit'];
				$ohArr[$row[$groupBy]]['credit'][] = $row['credit'];
				$ohArr[$row[$groupBy]]['date'][] = $row['date'];
				$ohArr[$row[$groupBy]]['modified_by'][] = $row['modified_by'];
				$ohArr[$row[$groupBy]]['last_modified_on'][] = $row['last_modified_on'];
			}   
			$params = array('ohArr'=>$ohArr,'SDate'=>$SDate,'EDate'=>$EDate,'groupBy'=>$groupBy);
		    $sheet->loadView('qc_reports.glDetailReportExport',$params);
		    });
		})->export('xls');
	}
	/*
	* glDetailAccountNosExport
	*/
	public function glDetailAccountNosExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		Excel::create('GLDetailsAcctNoReportExport', function($excel) {
		    $excel->sheet('GLDetailsAcctNoReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
			$SDate =  Input::get("SDate");
		    $EDate =  Input::get("EDate");
		    $groupBy = Input::get("groupBy");
		    $ohArr = array();
		    $queryPart ='';	 
		    $currentSDate = date('m/d/Y',strtotime('01/01/2010'));
		    $currentEDate = date('m/d/Y');
		    if (empty($SDate)) $SDate = $currentSDate;
		    if (empty($EDate)) $EDate = $currentEDate;
		    if (empty($groupBy)) $groupBy = 'gpg_expense_gl_code_id';
			if ($SDate!="" && $EDate!="") { 
		 		$queryPart = " AND date >= '".date('Y-m-d',strtotime($SDate))."' AND date <= '".date('Y-m-d',strtotime($EDate))."' ";
			}
		 	$gl_tags_rs = DB::select(DB::raw("SELECT id,value FROM gpg_settings WHERE NAME LIKE '_gl_tags%'"));
			$arr_tags_names = array(0 => 'No Tag');
			foreach ($gl_tags_rs as $key => $gl_vals) {
				$arr_tags_names[$gl_vals->id] = $gl_vals->value;
			}
			$ohData = DB::select(DB::raw("SELECT
			  oh.*,
			  CONCAT(gl.expense_gl_code,'-',gl.description) AS code_desc,
			  IFNULL(gl.gpg_expense_gl_tags,0) AS gpg_expense_gl_tags
			  FROM 
			  gpg_expense_gl_code gl
			  LEFT JOIN gpg_over_head_budget oh
			  ON gl.id = oh.gpg_expense_gl_code_id where 1 $queryPart"));

			$tags = DB::select(DB::raw("SELECT DISTINCT(tag_group_id) AS group_id FROM gpg_expense_gl_code_tags_detail ORDER BY tag_group_id"));
			$tagging = count($tags);
			$tags_col_counts = 0;
			$tags_detail_names = DB::select(DB::raw("SELECT * FROM gpg_expense_gl_code_tags"));
			$tags_detail_data_names = array();
			foreach ($tags_detail_names as $key => $detail_arr) {
				$tags_detail_data_names[$detail_arr->id] = $detail_arr->tag_name;
			}
			$tags_detail = DB::select(DB::raw("SELECT * FROM gpg_expense_gl_code_tags_detail"));
			$tags_detail_data = array();
			foreach ($tags_detail as $key => $detail_arr) {
				$tags_detail_data[$detail_arr->tag_group_id][$detail_arr->gpg_expense_gl_code_id][$detail_arr->gpg_expense_gl_code_tags_parent_id] = $detail_arr->gpg_expense_gl_code_tags_child_id;
			}
			$tagging_cols_str = "";
			$total_tags = array();
		    if($tagging > 0){
				foreach ($tags as $key => $tags_arr) {
					$tagging_cols_str .= '<td class=xl261 >'.$arr_tags_names[$tags_arr->group_id].'</td>';
					$tagging_cols_str .= '<td class=xl261 >'.$arr_tags_names[$tags_arr->group_id].' SUB</td>';
					$tags_col_counts++;
					$total_tags[] = $tags_arr->group_id;
				}
			}
			$params = array('total_tags'=>$total_tags,'tagging_cols_str'=>$tagging_cols_str,'tags_col_counts'=>$tags_col_counts,'tagging'=>$tagging,'arr_tags_names'=>$arr_tags_names,'ohData'=>$ohData,'tags_detail_data_names'=>$tags_detail_data_names,'tags_detail_data'=>$tags_detail_data,'SDate'=>$SDate,'EDate'=>$EDate,'groupBy'=>$groupBy);
		    $sheet->loadView('qc_reports.glDetailAccountNosExport',$params);
		    });
		})->export('xls');
	}

	/*
	* ohBudgetingReportSimpleExport
	*/
	public function ohBudgetingReportSimpleExport(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		Excel::create('oHSimpleReportExport', function($excel) {
		    $excel->sheet('oHSimpleReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
			$SDate =  Input::get("SDate");
		    $EDate =  Input::get("EDate");
		    $orderBy = Input::get("orderBy");
		    $ohArr = array();
		    $queryPart ='';
			$parent_arr = array(); 
		    $currentSDate = date('m/d/Y',strtotime('01/01/2010'));
		    $currentEDate = date('m/d/Y');
 		    if (empty($SDate)) $SDate = $currentSDate;
		    if (empty($EDate)) $EDate = $currentEDate;
		    if (empty($orderBy)) $orderBy = 'gpg_over_head_budget.gpg_expense_gl_code_id';	   
			if ($SDate!="" && $EDate!="") { 
			 	$queryPart = " AND gpg_over_head_budget.date >= '".date('Y-m-d',strtotime($SDate))."' AND gpg_over_head_budget.date <= '".date('Y-m-d',strtotime($EDate))."' ";
			}
			$result_parent = DB::select(DB::raw("SELECT 
					gpg_expense_gl_code.id,
					gpg_expense_gl_code.description,
					gpg_expense_gl_code.expense_gl_code,
					SUM(gpg_over_head_budget.credit) AS credit_sum,
					SUM(gpg_over_head_budget.debit) AS debit_sum,
					SUM(gpg_over_head_budget.amount) AS amount_sum,
					gpg_expense_gl_code.exclude_from_oh
				FROM
					gpg_over_head_budget ,
					gpg_expense_gl_code 
				WHERE
					gpg_over_head_budget.gpg_expense_gl_code_id = gpg_expense_gl_code.id
					".$queryPart."
				GROUP BY 
					gpg_expense_gl_code.id
				ORDER BY $orderBy "));
				$data_arr = array();
				foreach ($result_parent as $key => $arr) {
					$data_arr[$arr->id] = $arr;
				}
			$this->get_childs2(0);
			$GLOBALS['arr_final_totals'] = array();
			$arr = $GLOBALS['parent_arr'][0];
			foreach($arr as $key => $val) {
				$this->set_totals2($key);
			}
			$params = array('SDate'=>$SDate,'EDate'=>$EDate,'orderBy'=>$orderBy,'arr_final_totals'=>$GLOBALS['arr_final_totals']);
		    $sheet->loadView('qc_reports.ohBudgetingReportSimpleExport',$params);
		    });
		})->export('xls');
	}
	/*
	* ohBudgetingReportParentChildExport
	*/
	public function ohBudgetingReportParentChildExport(){
		set_time_limit(0);
		Excel::create('OhParentChildReportExport', function($excel) {
		    $excel->sheet('OhParentChildReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $SDate =  Input::get("SDate");
		    $EDate =  Input::get("EDate");
		    $orderBy = Input::get("orderBy");
		    $ohArr = array();
		    $queryPart ='';
			$parent_arr = array(); 
		    $currentSDate = date('m/d/Y',strtotime('01/01/2010'));
		    $currentEDate = date('m/d/Y');
			if (empty($SDate)) $SDate = $currentSDate;
		    if (empty($EDate)) $EDate = $currentEDate;
		    if (empty($orderBy)) $orderBy = 'gpg_over_head_budget.gpg_expense_gl_code_id';
		   	if ($SDate!="" && $EDate!="") { 
		 		$queryPart = " AND gpg_over_head_budget.date >= '".date('Y-m-d',strtotime($SDate))."' AND gpg_over_head_budget.date <= '".date('Y-m-d',strtotime($EDate))."' ";
			}
			$result_parent = DB::select(DB::raw("SELECT 
					gpg_expense_gl_code.id,
					gpg_expense_gl_code.description,
					gpg_expense_gl_code.expense_gl_code,
					SUM(gpg_over_head_budget.credit) AS credit_sum,
					SUM(gpg_over_head_budget.debit) AS debit_sum,
					SUM(gpg_over_head_budget.amount) AS amount_sum,
					gpg_expense_gl_code.exclude_from_oh
				FROM
					gpg_over_head_budget ,
					gpg_expense_gl_code 
				WHERE
					gpg_over_head_budget.gpg_expense_gl_code_id = gpg_expense_gl_code.id
					".$queryPart."
				GROUP BY 
					gpg_expense_gl_code.id
				ORDER BY $orderBy"));
			$data_arr = array();
			foreach ($result_parent as $key => $arr) {
				$data_arr[$arr->id] = $arr;
			}
			$GLOBALS['data_arr'] = $data_arr;
			$this->get_childs3(0);
			$params = array('SDate'=>$SDate,'EDate'=>$EDate,'orderBy'=>$orderBy);
		    $sheet->loadView('qc_reports.ohBudgetingReportParentChildExport',$params);
		    });
		})->export('xls');
	}

	/*
	* companyModelingExport
	*/
	public function companyModelingExport(){
		set_time_limit(0);
		Excel::create('CompanyModelingReportExport', function($excel) {
		    $excel->sheet('CompanyModelingReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $SDate = Input::get("SDate");
			$EDate = Input::get("EDate");
			$dbDateStart = date('Y-m-d',strtotime($SDate));
			$dbDateEnd = date('Y-m-d',strtotime($EDate));
			$DSQL = "";
			$DQ2 = " order by emp_type, name ";
			$non_billable = array(6,5,7);
			$cqry = DB::select(DB::raw("select DATEDIFF('".$dbDateEnd."','".$dbDateStart."') as d_diff"));
			$tDays = @$cqry[0]->d_diff+1;
			$result = DB::select(DB::raw("select *,
						(select type from gpg_employee_type where type_id = gpg_employee.GPG_employee_type_id) as emp_type 
						FROM gpg_employee 
						WHERE 
						exclude_oh = 0 AND
						((terminated_date <= '".$dbDateEnd."' AND terminated_date >= '".$dbDateStart."' ) OR terminated_date IS NULL OR terminated_date = '0000-00-00') $DSQL $DQ2"));
			$res2 = DB::select(DB::raw("SELECT 
				gt.GPG_employee_Id,
				pw_flag,
				gt.date,
				gd.time_in,
				gd.time_out,
				time_diff_dec,
				SUM(time_diff_dec),
				IF(SUM(time_diff_dec)<=8,SUM(time_diff_dec),8) AS reg_hours,
				IF(SUM(time_diff_dec)>8 && SUM(time_diff_dec) <= 12,SUM(time_diff_dec)-8,IF(SUM(time_diff_dec)>8,4,0)) AS ot_hours,
				IF(SUM(time_diff_dec)>12,SUM(time_diff_dec)-12,0) AS dt_hours,
				pw_reg_rate,
				pw_ot_rate,
				pw_dt_rate,
				reg_wage,
				ot_wage,
				dt_wage
			FROM
				gpg_timesheet_detail gd, gpg_timesheet gt
			WHERE
				gt.id = gd.GPG_timesheet_id AND 
				gt.date >= '".$dbDateStart."' AND
				gt.date <= '".$dbDateEnd."' 
			GROUP BY gt.date,pw_flag,gt.GPG_employee_Id"));
			$arr_employee_wages = array();
			foreach ($res2 as $key => $value) {
				$row = (array)$value;
				$wage  =0;
				if($row['pw_flag']==1){
					$wage = ($row['reg_hours'] * $row['pw_reg_rate']) + ($row['ot_hours'] * $row['pw_ot_rate']) + ($row['dt_hours'] * $row['pw_dt_rate']);
				}
				else{
					$perHourLabor = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$row['GPG_employee_Id'])->where('type','=','h')->where('start_date','<=',$dbDateEnd)->orderBy('start_date','DESC')->pluck('rate');
					$regWage = @round($row['reg_hours']*$perHourLabor,2);
					$otWage = @round($row['ot_hours']*($perHourLabor*1.5),2);
					$dtWage = @round($row['dt_hours']*($perHourLabor*2),2);
					$wage = $regWage + $otWage + $dtWage;
				}
				$arr_employee_wages[$row['GPG_employee_Id']] += $wage;
			}
			$starting_column = 14; 
			$ending_column = $starting_column-1;
			$queryPart ='';
			$parent_arr = array(); 
		    $currentSDate = date('m/d/Y',strtotime('01/01/2010'));
		    $currentEDate = date('m/d/Y');
		    if (empty($SDate)) $SDate = $currentSDate;
		    if (empty($EDate)) $EDate = $currentEDate;
		    if (empty($orderBy)) $orderBy = 'gpg_over_head_budget.gpg_expense_gl_code_id';
		    if ($SDate!="" && $EDate!="") { 
			 	 $queryPart = " AND gpg_over_head_budget.date >= '".date('Y-m-d',strtotime($SDate))."' AND gpg_over_head_budget.date <= '".date('Y-m-d',strtotime($EDate))."' ";
			}
			$result_parent = DB::select(DB::raw("SELECT 
				gpg_expense_gl_code.id,
				gpg_expense_gl_code.description,
				gpg_expense_gl_code.expense_gl_code,
				SUM(gpg_over_head_budget.credit) AS credit_sum,
				SUM(gpg_over_head_budget.debit) AS debit_sum,
				SUM(gpg_over_head_budget.amount) AS amount_sum,
				gpg_expense_gl_code.exclude_from_oh
				FROM
					gpg_over_head_budget ,
					gpg_expense_gl_code 
				WHERE
					gpg_over_head_budget.gpg_expense_gl_code_id = gpg_expense_gl_code.id
					".$queryPart."
				GROUP BY 
					gpg_expense_gl_code.id
					ORDER BY $orderBy"));
			$data_arr = array();
			foreach ($result_parent as $key => $arr) {
				$data_arr[$arr->id] = $arr;
			}
			$all_arr = array();
			$GLOBALS['str_excluded_ids'] = "";
			$this->get_childs3(0);
			$arr_excluded_ids = array();
			if(strlen($GLOBALS['str_excluded_ids'])>0) {
				$GLOBALS['str_excluded_ids'] = substr($GLOBALS['str_excluded_ids'],0,strlen($GLOBALS['str_excluded_ids'])-1);
				$arr_excluded_ids = explode(",",$GLOBALS['str_excluded_ids']);
			}
			$arr_final_totals = array();
			$arr_total_cells = array();
			$arr = @$GLOBALS['parent_arr'][0];
			$str2 = "<table>";
			foreach($arr as $key => $val){
				$this->set_totals3($key);
			}
			
			$params = array('SDate'=>$SDate,'EDate'=>$EDate,'orderBy'=>$orderBy,'arr_excluded_ids'=>$arr_excluded_ids,'all_arr'=>$GLOBALS['all_arr'],'result'=>$result,'arr_final_totals'=>$GLOBALS['arr_final_totals'],'parent_arr'=>$GLOBALS['parent_arr'],'data_arr'=>$GLOBALS['data_arr'],'cell_end'=>$GLOBALS['cell_end'],'arr_total_cells'=>$GLOBALS['arr_total_cells']);
		    $sheet->loadView('qc_reports.companyModelingExport',$params);
		    });
		})->export('xls');
	}

	/*
	* billableHoursReport
	*/
	public function billableHoursReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getBillableHrsRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$employees = DB::select(DB::raw("select id, name from gpg_employee where status = 'A' AND concat(',',frontend,',') like '%,sales,%' or concat(',',frontend,',') like '%timesheet%' order by name"));
		$emp_arr = array();
		foreach ($employees as $key => $value) {
			$emp_arr[$value->id] = $value->name;
		}
		$emp_types = DB::table('gpg_employee_type')->lists('TYPE','type_id');
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'emp_arr'=>$emp_arr,'emp_types'=>$emp_types,'billable_records_next'=>$data->billable_records_next,'total_record'=>$data->total_record,'billable_records_prev'=>$data->billable_records_prev,'non_billable_next'=>$data->non_billable_next,'non_billable_prev'=>$data->non_billable_prev);
		return View::make('qc_reports.billable_hours_report', $params);
	}
	public function getBillableHrsRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$results->total_record = array();
		$results->billable_records_next = array();
		$results->billable_records_prev = array();
		$results->non_billable_prev = array();
		$results->non_billable_next = array();
		$sdate = Input::get("SDate1");
		$edate = Input::get("EDate1");
		$billable_records_next = array();
		$billable_records_prev = array();
		if ( $sdate == "" )
		    $sdate = "01/01/" . date("Y");
		if ( $edate == "" )
		    $edate = date("m/d/Y");
		$optEmployee = Input::get("optEmployee");
		$empType = Input::get("empType");
		$order_by = Input::get("orderby");
		if ( $order_by == "" ) {
		    $order_by = "ename";
		}
		if ( $order_by == 'etype' )
		    $order_by = "etype ASC, " . "ename";
		$sdate = date('Y-m-d', strtotime($sdate));
		$edate = date('Y-m-d', strtotime($edate));
		$start_date = date('Y-m-d', strtotime($sdate) - (strtotime($edate) - strtotime($sdate)));
		$query_str = "?SDate1=" . $sdate . "&EDate1=" . $edate . "&optEmployee=" . $optEmployee . "&empType=" . $empType . "&orderby=" . $order_by;
		$total_record = array( );
		$total_record['prev']['hours_paid'] = 0;
		$total_record['prev']['hours_billable'] = 0;
		$total_record['prev']["reg_hrs"] = 0;
		$total_record['prev']['p_jobs'] = 0;
		$total_record['prev']['vacation'] = 0;
		$total_record['prev']['sick'] = 0;
		$total_record['prev']['ot_hrs'] = 0;
		$total_record['prev']['dt_hrs'] = 0;
		$total_record['prev']['pw_reg_hrs'] = 0;
		$total_record['prev']['pw_ot_hrs'] = 0;
		$total_record['prev']['pw_dt_hrs'] = 0;
		$total_record['prev']['deductions'] = 0;
		$total_record['prev']['amount_paid'] = 0;
		$total_record['next']['hours_paid'] = 0;
		$total_record['next']['hours_billable'] = 0;
		$total_record['next']["reg_hrs"] = 0;
		$total_record['next']['p_jobs'] = 0;
		$total_record['next']['vacation'] = 0;
		$total_record['next']['sick'] = 0;
		$total_record['next']['ot_hrs'] = 0;
		$total_record['next']['dt_hrs'] = 0;
		$total_record['next']['pw_reg_hrs'] = 0;
		$total_record['next']['pw_ot_hrs'] = 0;
		$total_record['next']['pw_dt_hrs'] = 0;
		$total_record['next']['deductions'] = 0;
		$total_record['next']['amount_paid'] = 0;
		$results->total_record = $total_record;
		$emp_type_total_prev = array();
		$emp_type_total_next = array( );
		$strwhr = "";
		if ( $optEmployee != "" )
		    $strwhr = " AND ts.GPG_employee_id = '" . $optEmployee . "' ";
		if ( $empType != "" )
		    $strwhr .= " AND e.GPG_employee_type_id = '" . $empType . "' ";
		$billable_hours_query = DB::select(DB::raw("SELECT
			  tsd.id,
			  ts.GPG_employee_Id   as emp_id,
			  e.GPG_employee_type_id as etypeid,
			  e.name AS ename,
		     (SELECT
		        gpg_employee_type.type
		      FROM gpg_employee_type
		      WHERE gpg_employee_type.type_id = e.GPG_employee_type_id) AS etype,
			  ts.date,
			  tsd.GPG_job_Id       AS job_id,
			  tsd.GPG_timesheet_id AS timesheet_id,
			  tsd.job_num          AS job_number,
			  tsd.pw_flag          AS prevailing,
			  (SELECT wage_type
			   FROM gpg_job_rates
			   WHERE tsd.gpg_task_type = gpg_job_rates.gpg_task_type
		       AND job_number = tsd.job_num
		       AND GPG_employee_type_id = (SELECT GPG_employee_type_id
	           FROM gpg_employee
	           WHERE gpg_employee.id = ts.GPG_employee_id)
		       AND start_date <= ts.date
		       AND end_date >= ts.date
			   ORDER BY modified_on DESC
			   LIMIT 1) AS wage_type,
			  tsd.time_out,
			  tsd.time_in,
			  tsd.gpg_task_type,
			  tsd.labor_rate,
			  tsd.reg_wage,
			  tsd.ot_wage,
			  tsd.dt_wage,
			  tsd.pw_reg_rate,
			  tsd.pw_dt_rate,
			  tsd.pw_dt_rate,
			  tsd.total_wage,
			  tsd.time_diff_dec
			FROM gpg_timesheet ts,
				gpg_timesheet_detail tsd,
			    gpg_employee e
			WHERE tsd.GPG_timesheet_id = ts.id
			    AND	e.id = ts.GPG_employee_Id
			    AND ts.date >= '" . $start_date . "'
			    AND ts.date <= '" . $edate . "'
			    " . $strwhr . "
			ORDER BY " . $order_by . " ASC, prevailing DESC, job_number ASC, tsd.id DESC"));
		// $billable_records is Dataset which is holding data of query response
		$billable_record = array( );
		$billable_hours_result = $billable_hours_query;
		$str_jobs_task_types = "";
		$str_jobs_task_types2 = "";
		$non_billable_next = array( );
		$non_billable_prev = array( );
		foreach ($billable_hours_result as $key => $value1) {
			$billable_hours_row =  (array)$value1;
		    $time_diff = (strtotime($billable_hours_row['time_out']) - strtotime($billable_hours_row['time_in'])) / 3600;
		    $billable_hours_row['working_hours'] = $time_diff;
		    if ( strtotime($billable_hours_row['date']) < strtotime($sdate) ) {
		        $reg_hours = 0;
		        $ot_hours = 0;
		        $dt_hours = 0;
		        $str_jobs_task_types .= "'" . $billable_hours_row['job_number'] . "~" . $billable_hours_row['gpg_task_type'] ."~".$billable_hours_row['etypeid']."',";
		            if ( @strpos($billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['job_nums'], $billable_hours_row['job_number']) === false ) {
		                @$billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['job_nums'] .= $billable_hours_row['job_number'] . "~" . $billable_hours_row['gpg_task_type'] . "@" . ($billable_hours_row['wage_type'] == "" ? 0 : $billable_hours_row['wage_type']) . "!" . ($billable_hours_row['prevailing'] == "" ? 0 : $billable_hours_row['prevailing']) . ","; //.$billable_hours_row['working_hours'].",";
		            }
		            $hours_before_addition = @$billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['working_hours'];
		            @$billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['working_hours'] += $billable_hours_row['working_hours'];
		            $billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['labor_rate'] = $billable_hours_row['labor_rate'];
		            $billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['wage_type'] = $billable_hours_row['wage_type'];
		            $billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['prevailing'] = $billable_hours_row['prevailing'];
		            $billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['ename'] = $billable_hours_row['ename'];
		            $billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['etype'] = $billable_hours_row['etype'];
		            $hour_job = @$billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['working_hours'];
		            if ( $hour_job <= 8 ) {
		                $reg_hours = $billable_hours_row['working_hours']; //5.50
		            } else if ( $hour_job > 8 && $hour_job <= 12 ) {
		                $reg_hours = $hours_before_addition < 8 ? (8 - $hours_before_addition) : 0;
		                $rem = ($hours_before_addition == 0 ? 8 : $hours_before_addition);
		                $ot_hours = ($hour_job > 8 ? $hour_job - $rem - (($hour_job - $rem - $reg_hours) < 0 ? 0 : $reg_hours) : $billable_hours_row['working_hours']);
		            } else {
		                $remaining = $hour_job - 8;
		                $reg_hours = $hours_before_addition < 8 ? (8 - $hours_before_addition) : 0;
		                if ( $hours_before_addition > 7 )
		                    $hours_before_addition -= 8;
		                if ( $hours_before_addition < 0 || (4 - $hours_before_addition) < 0 )
		                    $remaining = 4;
		                $ot_hours = ($remaining > 4 ? 4 - $hours_before_addition : $remaining);
		                $dt_hours = ($hour_job - 12);
		            }
		            if ( $reg_hours > 0 )
		                @$billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['reg_hours'][$billable_hours_row['job_number']] += $reg_hours;
		            if ( $ot_hours > 0 )
		                @$billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['ot_hours'][$billable_hours_row['job_number']] += $ot_hours;
		            if ( $dt_hours > 0 )
		                @$billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['dt_hours'][$billable_hours_row['job_number']] += $dt_hours;
		            $billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['reg_rate'] = $billable_hours_row['reg_wage'];
		            $billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['ot_rate'] = $billable_hours_row['ot_wage'];
		            $billable_records_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['dt_rate'] = $billable_hours_row['dt_wage'];
		        if ( @strpos($billable_hours_row['job_number'], "P000") === 0 ){
		            @$non_billable_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['non_billable']['P000'] += $billable_hours_row['working_hours'];
		        }else if(@strpos($billable_hours_row['job_number'], "Sick") === 0 ){
		            @$non_billable_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['non_billable']['Sick'] += $billable_hours_row['working_hours'];
		        }else if(@strpos($billable_hours_row['job_number'], "Vacation") === 0 ){
		            @$non_billable_prev[$billable_hours_row['emp_id']][$billable_hours_row['date']]['non_billable']['Vacation'] += $billable_hours_row['working_hours'];
		        }
		        $results->billable_records_prev = $billable_records_prev;
		    	$results->non_billable_prev = $non_billable_prev;
		   
		    } else {
		        // Report 2 calculation Array starts here
		        $reg_hours2 = 0;
		        $ot_hours2 = 0;
		        $dt_hours2 = 0;
		        $str_jobs_task_types2 .= "'" . $billable_hours_row['job_number'] . "~" . $billable_hours_row['gpg_task_type'] ."~".$billable_hours_row['etypeid']."',";
		        if ( @strpos($billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['job_nums'], $billable_hours_row['job_number']) === false ) {
		            $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['job_nums'] .= $billable_hours_row['job_number'] . "~" . $billable_hours_row['gpg_task_type'] . "@" . ($billable_hours_row['wage_type'] == "" ? 0 : $billable_hours_row['wage_type']) . "!" . ($billable_hours_row['prevailing'] == "" ? 0 : $billable_hours_row['prevailing']) . ","; //.$billable_hours_row['working_hours'].",";
		        }
		        $hours_before_addition2 = $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['working_hours'];
		        $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['working_hours'] += $billable_hours_row['working_hours'];
		        $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['labor_rate'] = $billable_hours_row['labor_rate'];
		        $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['wage_type'] = $billable_hours_row['wage_type'];
		        $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['prevailing'] = $billable_hours_row['prevailing'];
		        $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['ename'] = $billable_hours_row['ename'];
		        $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['etype'] = $billable_hours_row['etype'];
		        $hour_job2 = $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['working_hours'];
	            if ( $hour_job2 <= 8 ) {
	                $reg_hours2 = $billable_hours_row['working_hours']; //12.5
		        } else if ( $hour_job2 > 8 && $hour_job2 <= 12 ) {
			        $reg_hours2 = $hours_before_addition2 < 8 ? (8 - $hours_before_addition2) : 0;
		            $rem2 = ($hours_before_addition2 == 0 ? 8 : $hours_before_addition2);
		            $ot_hours2 = ($hour_job2 > 8 ? $hour_job2 - $rem2 - (($hour_job2 - $rem2 - $reg_hours2) < 0 ? 0 : $reg_hours2) : $billable_hours_row['working_hours']);
		        } else {
		            $remaining2 = $hour_job2 - 8;
		            $reg_hours2 = $hours_before_addition2 < 8 ? (8 - $hours_before_addition2) : 0;
		            if ( $hours_before_addition2 > 7 )
		                $hours_before_addition2 -= 8;
		            if ( $hours_before_addition2 < 0 || (4 - $hours_before_addition2) < 0 )
		                $remaining2 = 4;
		                $ot_hours2 = ($remaining2 > 4 ? 4 - $hours_before_addition2 : $remaining2);
		                $dt_hours2 = ($hour_job2 - 12);
		        }
		        if ( $reg_hours2 > 0 )
		            $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['reg_hours'][$billable_hours_row['job_number']] += $reg_hours2;
		        if ( $ot_hours2 > 0 )
		            $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['ot_hours'][$billable_hours_row['job_number']] += $ot_hours2;
		        if ( $dt_hours2 > 0 )
		            $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['dt_hours'][$billable_hours_row['job_number']] += $dt_hours2;
		            $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['reg_rate'] = $billable_hours_row['reg_wage'];
		            $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['ot_rate'] = $billable_hours_row['ot_wage'];
		            $billable_records_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['dt_rate'] = $billable_hours_row['dt_wage'];
		        $results->billable_records_next = $billable_records_next;
		        if ( @strpos($billable_hours_row['job_number'], "P000") === 0 ){
		            $non_billable_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['non_billable']['P000'] += $billable_hours_row['working_hours'];
		        }else if(@strpos($billable_hours_row['job_number'], "Sick") === 0 ){
		            $non_billable_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['non_billable']['sick'] += $billable_hours_row['working_hours'];
		        }else if(@strpos($billable_hours_row['job_number'], "Vacation") === 0 ){
		            $non_billable_next[$billable_hours_row['emp_id']][$billable_hours_row['date']]['non_billable']['vacation'] += $billable_hours_row['working_hours'];
		        }
		        $results->non_billable_next = $non_billable_next;
		    }
		}//end foreach
		$str_jobs_task_types = $str_jobs_task_types . $str_jobs_task_types2;
		$str_jobs_task_types = substr($str_jobs_task_types, 0, strlen($str_jobs_task_types) - 1);
		$job_rates_result = DB::select(DB::raw("SELECT
		          jr.job_number  AS job_number,
		          jr.pw_reg,
		          jr.pw_overtime,
		          jr.pw_double AS pw_doubletime,
		          jr.status,
		          jr.wage_type,
		          jr.start_date,
		          jr.end_date,
		          jr.gpg_task_type
		        FROM gpg_job_rates jr
		        WHERE jr.end_date >= '" . $start_date . "'
		        AND jr.start_date <= '" . $edate . "'         
		        AND CONCAT(jr.job_number,'~',jr.gpg_task_type,'~',jr.GPG_employee_type_id) IN ($str_jobs_task_types)"));
		$rates = array( );
		foreach ($job_rates_result as $key => $Val2) {
			$rates_row = (array)$Val2;
		    $tmp = $rates_row['job_number'] . '~' . $rates_row['gpg_task_type'];
		    $rates[$tmp] = $rates_row;
		}
		return $results;
	}
	
	/*
	* partsCostsCheck
	*/
	public function partsCostsCheck(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getPartsCostsRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('qc_reports.parts_costs_check', $params);
	}
	public function getPartsCostsRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$jobnumber = Input::get("jobnumber");
		$cp = Input::get("cp");
		$lp = Input::get("lp");
		$mp = Input::get("mp");
		$str="";
		if(strlen($jobnumber)>0)
			$str .= " and gpg_field_service_work.job_num LIKE '".$jobnumber."%' ";
		if(strlen($cp)==1)
			$str .= " and gfm.cost != gfswm.cost_price ";
		if(strlen($lp)==1)
			$str .= " and gfm.list != gfswm.list_price ";
		if(strlen($mp)==1)
			$str .= " and gfm.margin != gfswm.margin ";
		$t_rec = DB::select(DB::raw("SELECT
		  count(*) as t_count
		FROM gpg_field_service_work_material gfswm,
		  gpg_field_material gfm
		WHERE gfm.id = gfswm.part_id
		".$str."
		    AND (gfm.cost != gfswm.cost_price
		          OR gfm.list != gfswm.list_price
		          OR gfm.margin != gfswm.margin)"));
		$results->totalItems = @$t_rec[0]->t_count;
		$qry = DB::select(DB::raw("SELECT
		  gpg_field_service_work.job_num,
		  (SELECT
		     NAME
		   FROM gpg_field_material_type
		   WHERE gpg_field_material_type.id = gfm.gpg_field_material_type_id) AS part_type,
		  gfm.part_number,
		  gfm.serial_number,
		  gfm.spec_number,
		  gfm.cost          AS mat_cost,
		  gfswm.cost_price  AS job_mat_cost,
		  IF(gfm.cost != gfswm.cost_price,1,0) AS cp,
		  gfm.list          AS mat_list,
		  gfswm.list_price  AS job_mat_list,
		  IF(gfm.list != gfswm.list_price,1,0) AS lp,
		  gfm.margin        AS mat_margin,
		  gfswm.margin      AS job_mat_margin,
		  IF(gfm.margin != gfswm.margin,1,0) AS mp,
		  gfm.modified_on
		FROM gpg_field_service_work_material gfswm,
		  gpg_field_material gfm,
		  gpg_field_service_work
		WHERE gfm.id = gfswm.part_id
		AND gpg_field_service_work.id = gfswm.gpg_field_service_work_id
		".$str."
		    AND (gfm.cost != gfswm.cost_price
		          OR gfm.list != gfswm.list_price
		          OR gfm.margin != gfswm.margin)
				  order by gfm.modified_on DESC
		LIMIT $start,$limit"));
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		return $results;
	}

	/*
	* customerContactDetailReport
	*/
	public function customerContactDetailReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getCustContactDetailRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$customers = DB::table('gpg_customer')->where('status','=','A')->orderBy('name')->lists('name','id');
		$city = DB::select(DB::raw("SELECT DISTINCT(city) as city FROM gpg_customer"));
		$city_arr = array();
		foreach ($city as $key => $value) {
			$city_arr[$value->city] = $value->city;
		}
		$state = DB::select(DB::raw("SELECT DISTINCT(state) as state FROM gpg_customer"));
		$state_arr = array();
		foreach ($state as $key => $value) {
			$state_arr[$value->state] = $value->state;
		}
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'customers'=>$customers,'city_arr'=>$city_arr,'state_arr'=>$state_arr,'customer_flag'=>$data->customer_flag);
		return View::make('qc_reports.customer_contact_detail_report', $params);
	}
	public function getCustContactDetailRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$results->customer_flag = false;
		$results->items = array();
		$view_mode = Input::get('optview');
		$optCustomer = Input::get('optCustomer');
		$sdate = Input::get('SDate');
		$edate = Input::get('EDate');
		$have = Input::get('have'); 
		$city_filter = Input::get('city_filter');
		$state_filter = Input::get('state_filter');
		if($view_mode == ""){
			$view_mode = "customers";
		}
		if($sdate == "") $sdate = '01/01/2010';
		if($edate == "") $edate = date("m/d/Y",time());
		$from = " FROM gpg_customer c ";
		$where = " Where 1 ";
		$sub_where = "";
		$h_where = "";
		$f_where = "";
		$e_where = "";
		if($have == 'have_job_num'){	
			if($view_mode == "ebomb") {
				$sub_where .= " AND e.GPG_attach_job_num IS NOT NULL ";
			}else if($view_mode == "fbomb"){
				$sub_where .= " AND f.GPG_attach_job_num IS NOT NULL ";
			}else if("hbomb" == $view_mode){
				$sub_where .= " AND h.GPG_attach_job_num IS NOT NULL ";
			}else{
				$e_where .= " AND e.GPG_attach_job_num IS NOT NULL ";
				$f_where .= " AND f.GPG_attach_job_num IS NOT NULL ";
				$h_where .= " AND h.GPG_attach_job_num IS NOT NULL ";
			}
		}
		if($optCustomer != ''){$where .= " AND c.id = '".$optCustomer."' ";}
		if($city_filter != "") $where .= "AND c.city = '".$city_filter."' ";
		if($state_filter != "") $where .= "AND c.state = '".$state_filter."' ";
		$order_by = " ORDER BY customer_name ";
		$query_limit = " LIMIT ".$start.",".$limit;
		$customer_flag = false;
		$customers = array();
		$query_all = array();
		$result = array();
		$query = "SELECT
		  c.id AS customer_id,
		  c.name AS customer_name,
		  c.address AS customer_address,
		  c.address2 AS customer_address2,
		  c.city AS customer_city,
		  c.state AS customer_state,
		  c.zipcode AS customer_zip,
		  c.phone_no AS customer_phone,
		  c.email_add AS customer_email,
		  c.cus_type AS customer_type
		  ";
		  $having = ""; 
		  $query_part = '';
		if($view_mode == "ebomb"){ // view start select here
			$from .= ",gpg_job_electrical_quote e ";
			$query_part = ",e.job_num AS quote_no,
			  e.GPG_attach_job_num AS job_no,
			  e.project_name,
			  e.project_address as address,
			  e.project_city,
			  e.project_state,
			  e.project_zip,
			  e.project_phone,
			  e.project_email,
			  e.date_job_won";
			  $where .= "AND c.id = e.GPG_customer_id AND e.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND e.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' ".$sub_where;

		}else if($view_mode == "fbomb"){
			$from .= ", gpg_field_service_work f ";
			$query_part = ",
			  f.GPG_customer_id AS cust_id,  
			  f.GPG_attach_job_num AS job_no,
			  f.address1 AS address,
			  f.address2 AS address2,
			  f.job_num AS quote_no,
			  f.city AS project_city,
			  f.state AS project_state,
			  f.zip AS project_zip,
			  f.main_contact_name AS project_name ";		  
			$where .= "AND c.id = f.GPG_customer_id  AND f.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND f.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' ".$sub_where;
			
		}else if($view_mode == "hbomb"){
			$from .= ",gpg_shop_work_quote h ";
			$query_part = ",
			   h.GPG_customer_id AS cust_id,
			   h.main_contact_name AS project_name,
			   h.GPG_attach_job_num AS job_no,
			   h.location AS address,
		       h.main_contact_phone AS project_phone,
		       h.job_num AS quote_no,
		       h.fax AS project_fax ";
			$where .= "AND c.id = h.GPG_customer_id AND h.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND h.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' ".$sub_where;	
		}
		else if($view_mode == "gbomb"){
			$from .= ",gpg_job_grassivy_quote m ";
			$query_part = ",m.job_num AS quote_no,
			  m.GPG_attach_job_num AS job_no,
			  m.project_name,
			  m.project_address as address,
			  m.project_city,
			  m.project_state,
			  m.project_zip,
			  m.project_phone,
			  m.project_email,
			  m.date_job_won";
			  $where .= "AND c.id = m.GPG_customer_id AND m.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND m.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' ".$sub_where;

		}
		else if($view_mode == "spbomb"){
			$from .= ",gpg_job_special_project_quote m ";
			$query_part = ",m.job_num AS quote_no,
			  m.GPG_attach_job_num AS job_no,
			  m.project_name,
			  m.project_address as address,
			  m.project_city,
			  m.project_state,
			  m.project_zip,
			  m.project_phone,
			  m.project_email,
			  m.date_job_won";
			  $where .= "AND c.id = m.GPG_customer_id AND m.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND m.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' ".$sub_where;

		}
		else if($view_mode == "customers"){
			$having = " HAVING field_jobs IS NOT NULL
			OR electrical_jobs IS NOT NULL
			OR shop_jobs IS NOT NULL
			OR grassivy_jobs IS NOT NULL 
			OR special_project_jobs IS NOT NULL";
			
			$customer_flag = true;
		 	$query = "SELECT
			  c.id        AS customer_id,
			  c.name      AS customer_name,
			  c.address   AS customer_address,
			  c.address2  AS customer_address2,
			  c.city      AS customer_city,
			  c.state     AS customer_state,
			  c.zipcode   AS customer_zip,
			  c.phone_no  AS customer_phone,
			  c.email_add AS customer_email,
			  c.cus_type  AS customer_type,
			  (SELECT
				 GROUP_CONCAT( f.GPG_customer_id,'^',IFNULL(f.GPG_attach_job_num,\"\"),'^',IFNULL(f.address1,\"\"),'^',IFNULL(f.address2,\"\"),'^',if(f.job_num=\"\",NULL,f.job_num),'^',IFNULL(f.city,\"\"),'^',IFNULL(f.state,\"\"),'^',IFNULL(f.zip,\"\"),'^',IFNULL(f.main_contact_name,\"\"),'~' )
			   FROM gpg_field_service_work f
			   WHERE f.GPG_customer_id = c.id AND f.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND f.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00'  AND f.job_num IS NOT NULL AND f.job_num != \"\"  ".$f_where.") AS field_jobs,
			  (SELECT
				 GROUP_CONCAT(h.GPG_customer_id,'^', IFNULL(h.main_contact_name,\"\"),'^', IFNULL(h.GPG_attach_job_num,\"\"),'^', IFNULL(h.location,\"\"),'^', IFNULL(h.main_contact_phone,\"\"),'^',IF(h.job_num=\"\",NULL,h.job_num),'~' )
			   FROM gpg_shop_work_quote h
			   WHERE h.GPG_customer_id = c.id AND h.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND h.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00'  AND h.job_num IS NOT NULL AND h.job_num != \"\"  ".$h_where.") AS shop_jobs,
			  (SELECT
				 GROUP_CONCAT(e.job_num,'^', IFNULL(e.GPG_attach_job_num,\"\"),'^',IFNULL(e.project_name,\"\"),'^',IFNULL(e.project_address,\"\"),'^',IFNULL( e.project_city,\"\"),'^',IFNULL(e.project_state,\"\"),'^',IFNULL(e.project_zip,\"\"),'^',IFNULL(e.project_phone,\"\"),'^',IFNULL(e.project_email,\"\"),'^', IFNULL(e.date_job_won,\"\"),'~')
			   FROM gpg_job_electrical_quote e
			   WHERE e.GPG_customer_id = c.id AND e.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND e.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' AND e.job_num IS NOT NULL AND e.job_num != \"\"   ".$e_where.") AS electrical_jobs,
			  (SELECT
				 GROUP_CONCAT(m.job_num,'^', IFNULL(m.GPG_attach_job_num,\"\"),'^',IFNULL(m.project_name,\"\"),'^',IFNULL(m.project_address,\"\"),'^',IFNULL( m.project_city,\"\"),'^',IFNULL(m.project_state,\"\"),'^',IFNULL(m.project_zip,\"\"),'^',IFNULL(m.project_phone,\"\"),'^',IFNULL(m.project_email,\"\"),'^', IFNULL(m.date_job_won,\"\"),'~')
			   FROM gpg_job_grassivy_quote m
			   WHERE m.GPG_customer_id = c.id AND m.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND m.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' AND m.job_num IS NOT NULL AND m.job_num != \"\"   ".$e_where.") AS grassivy_jobs,
			  (SELECT
				 GROUP_CONCAT(m.job_num,'^', IFNULL(m.GPG_attach_job_num,\"\"),'^',IFNULL(m.project_name,\"\"),'^',IFNULL(m.project_address,\"\"),'^',IFNULL( m.project_city,\"\"),'^',IFNULL(m.project_state,\"\"),'^',IFNULL(m.project_zip,\"\"),'^',IFNULL(m.project_phone,\"\"),'^',IFNULL(m.project_email,\"\"),'^', IFNULL(m.date_job_won,\"\"),'~')
			   FROM gpg_job_special_project_quote m
			   WHERE m.GPG_customer_id = c.id AND m.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND m.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' AND m.job_num IS NOT NULL AND m.job_num != \"\"   ".$e_where.") AS special_project_jobs
			FROM gpg_customer c ".$where . $having . $order_by . $query_limit;

			$query_all = DB::select(DB::raw("SELECT
			  c.id        AS customer_id,
			  c.name      AS customer_name,
			  c.address   AS customer_address,
			  c.address2  AS customer_address2,
			  c.city      AS customer_city,
			  c.state     AS customer_state,
			  c.zipcode   AS customer_zip,
			  c.phone_no  AS customer_phone,
			  c.email_add AS customer_email,
			  c.cus_type  AS customer_type,
			  (SELECT
				 GROUP_CONCAT( f.GPG_customer_id,'^',IFNULL(f.GPG_attach_job_num,\"\"),'^',IFNULL(f.address1,\"\"),'^',IFNULL(f.address2,\"\"),'^',if(f.job_num=\"\",NULL,f.job_num),'^',IFNULL(f.city,\"\"),'^',IFNULL(f.state,\"\"),'^',IFNULL(f.zip,\"\"),'^',IFNULL(f.main_contact_name,\"\"),'~' )
			   FROM gpg_field_service_work f
			   WHERE f.GPG_customer_id = c.id AND f.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND f.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00'  AND f.job_num IS NOT NULL AND f.job_num != \"\"  ".$f_where.") AS field_jobs,
			  (SELECT
				 GROUP_CONCAT(h.GPG_customer_id,'^', IFNULL(h.main_contact_name,\"\"),'^', IFNULL(h.GPG_attach_job_num,\"\"),'^', IFNULL(h.location,\"\"),'^', IFNULL(h.main_contact_phone,\"\"),'^',IF(h.job_num=\"\",NULL,h.job_num),'~' )
			   FROM gpg_shop_work_quote h
			   WHERE h.GPG_customer_id = c.id AND h.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND h.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00'  AND h.job_num IS NOT NULL AND h.job_num != \"\"  ".$h_where.") AS shop_jobs,
			  (SELECT
				 GROUP_CONCAT(e.job_num,'^', IFNULL(e.GPG_attach_job_num,\"\"),'^',IFNULL(e.project_name,\"\"),'^',IFNULL(e.project_address,\"\"),'^',IFNULL( e.project_city,\"\"),'^',IFNULL(e.project_state,\"\"),'^',IFNULL(e.project_zip,\"\"),'^',IFNULL(e.project_phone,\"\"),'^',IFNULL(e.project_email,\"\"),'^', IFNULL(e.date_job_won,\"\"),'~')
			   FROM gpg_job_electrical_quote e
			   WHERE e.GPG_customer_id = c.id AND e.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND e.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' AND e.job_num IS NOT NULL AND e.job_num != \"\"   ".$e_where.") AS electrical_jobs,
			  (SELECT
				 GROUP_CONCAT(m.job_num,'^', IFNULL(m.GPG_attach_job_num,\"\"),'^',IFNULL(m.project_name,\"\"),'^',IFNULL(m.project_address,\"\"),'^',IFNULL( m.project_city,\"\"),'^',IFNULL(m.project_state,\"\"),'^',IFNULL(m.project_zip,\"\"),'^',IFNULL(m.project_phone,\"\"),'^',IFNULL(m.project_email,\"\"),'^', IFNULL(m.date_job_won,\"\"),'~')
			   FROM gpg_job_grassivy_quote m
			   WHERE m.GPG_customer_id = c.id AND m.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND m.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' AND m.job_num IS NOT NULL AND m.job_num != \"\"   ".$e_where.") AS grassivy_jobs,
			  (SELECT
				 GROUP_CONCAT(m.job_num,'^', IFNULL(m.GPG_attach_job_num,\"\"),'^',IFNULL(m.project_name,\"\"),'^',IFNULL(m.project_address,\"\"),'^',IFNULL( m.project_city,\"\"),'^',IFNULL(m.project_state,\"\"),'^',IFNULL(m.project_zip,\"\"),'^',IFNULL(m.project_phone,\"\"),'^',IFNULL(m.project_email,\"\"),'^', IFNULL(m.date_job_won,\"\"),'~')
			   FROM gpg_job_special_project_quote m
			   WHERE m.GPG_customer_id = c.id AND m.created_on >= '".date('Y-m-d',strtotime($sdate))." 00:00:00' AND m.created_on <= '".date('Y-m-d',strtotime($edate))." 00:00:00' AND m.job_num IS NOT NULL AND m.job_num != \"\"   ".$e_where.") AS special_project_jobs
			FROM gpg_customer c ".$where . $having));
			$customer_result = DB::select(DB::raw($query));
			foreach ($customer_result as $key => $value1) {
				$customer_row = (array)$value1;
				if($customer_row['field_jobs'] != ""){
					$t_field_array = explode("~",$customer_row['field_jobs']);
					
					for($i = 0; $i<count($t_field_array)-1; $i++){
						$field_array = explode("^",$t_field_array[$i]);				
						$customers[$customer_row['customer_name']]['field_job'][$field_array[1]]['cust_id'] = str_replace(",","",$field_array[0]);
						$customers[$customer_row['customer_name']]['field_job'][$field_array[1]]['job_no'] = $field_array[1];
						$customers[$customer_row['customer_name']]['field_job'][$field_array[1]]['address'] = $field_array[2];
						$customers[$customer_row['customer_name']]['field_job'][$field_array[1]]['address2'] = $field_array[3];
						$customers[$customer_row['customer_name']]['field_job'][$field_array[1]]['quote_no'] = $field_array[4];
						$customers[$customer_row['customer_name']]['field_job'][$field_array[1]]['project_city'] = $field_array[5];
						$customers[$customer_row['customer_name']]['field_job'][$field_array[1]]['project_state'] = $field_array[6];
						$customers[$customer_row['customer_name']]['field_job'][$field_array[1]]['project_zip'] = $field_array[7];
						$customers[$customer_row['customer_name']]['field_job'][$field_array[1]]['project_name'] = $field_array[8];
					}
				}
				if($customer_row['shop_jobs'] != ""){
					$t_shop_array = explode("~",$customer_row['shop_jobs']);
					
					for($i = 0; $i<count($t_shop_array)-1; $i++){
						$shop_array = explode("^",$t_shop_array[$i]);
						$customers[$customer_row['customer_name']]['shop_job'][$shop_array[2]]['cust_id'] = $shop_array[0];
						$customers[$customer_row['customer_name']]['shop_job'][$shop_array[2]]['project_name'] = $shop_array[1];
						$customers[$customer_row['customer_name']]['shop_job'][$shop_array[2]]['job_no'] = $shop_array[2];
						$customers[$customer_row['customer_name']]['shop_job'][$shop_array[2]]['address'] = $shop_array[3];
						$customers[$customer_row['customer_name']]['shop_job'][$shop_array[2]]['project_phone'] = $shop_array[4];
						$customers[$customer_row['customer_name']]['shop_job'][$shop_array[2]]['quote_no'] = $shop_array[5];
					}
				}
				if($customer_row['electrical_jobs'] != ""){
					$t_elec_jobs = explode("~",$customer_row['electrical_jobs']);
					
					for($i = 0; $i<count($t_elec_jobs)-1; $i++){			
						$elec_array = explode("^",$t_elec_jobs[$i]);
						$customers[$customer_row['customer_name']]['elec_job'][$elec_array[0]]['job_no'] = str_replace(",","",$elec_array[1]);
						$customers[$customer_row['customer_name']]['elec_job'][$elec_array[0]]['quote_no'] = str_replace(",","",$elec_array[0]);
						$customers[$customer_row['customer_name']]['elec_job'][$elec_array[0]]['project_name'] = $elec_array[2];
						$customers[$customer_row['customer_name']]['elec_job'][$elec_array[0]]['address'] = $elec_array[3];
						$customers[$customer_row['customer_name']]['elec_job'][$elec_array[0]]['project_city'] = $elec_array[4];
						$customers[$customer_row['customer_name']]['elec_job'][$elec_array[0]]['project_state'] = $elec_array[5];
						$customers[$customer_row['customer_name']]['elec_job'][$elec_array[0]]['project_zip'] = $elec_array[6];
						$customers[$customer_row['customer_name']]['elec_job'][$elec_array[0]]['project_phone'] = $elec_array[7];
						$customers[$customer_row['customer_name']]['elec_job'][$elec_array[0]]['project_email'] = $elec_array[8];
					}
				}
				if($customer_row['grassivy_jobs'] != ""){
					$t_elec_jobs = explode("~",$customer_row['grassivy_jobs']);
					
					for($i = 0; $i<count($t_elec_jobs)-1; $i++){			
						$elec_array = explode("^",$t_elec_jobs[$i]);
						$customers[$customer_row['customer_name']]['grassivy_job'][$elec_array[0]]['job_no'] = str_replace(",","",$elec_array[1]);
						$customers[$customer_row['customer_name']]['grassivy_job'][$elec_array[0]]['quote_no'] = str_replace(",","",$elec_array[0]);
						$customers[$customer_row['customer_name']]['grassivy_job'][$elec_array[0]]['project_name'] = $elec_array[2];
						$customers[$customer_row['customer_name']]['grassivy_job'][$elec_array[0]]['address'] = $elec_array[3];
						$customers[$customer_row['customer_name']]['grassivy_job'][$elec_array[0]]['project_city'] = $elec_array[4];
						$customers[$customer_row['customer_name']]['grassivy_job'][$elec_array[0]]['project_state'] = $elec_array[5];
						$customers[$customer_row['customer_name']]['grassivy_job'][$elec_array[0]]['project_zip'] = $elec_array[6];
						$customers[$customer_row['customer_name']]['grassivy_job'][$elec_array[0]]['project_phone'] = $elec_array[7];
						$customers[$customer_row['customer_name']]['grassivy_job'][$elec_array[0]]['project_email'] = $elec_array[8];
					}
				}//if
				if($customer_row['special_project_jobs'] != ""){
					$t_elec_jobs = explode("~",$customer_row['special_project_jobs']);
					
					for($i = 0; $i<count($t_elec_jobs)-1; $i++){			
						$elec_array = explode("^",$t_elec_jobs[$i]);
						$customers[$customer_row['customer_name']]['special_project_job'][$elec_array[0]]['job_no'] = str_replace(",","",$elec_array[1]);
						$customers[$customer_row['customer_name']]['special_project_job'][$elec_array[0]]['quote_no'] = str_replace(",","",$elec_array[0]);
						$customers[$customer_row['customer_name']]['special_project_job'][$elec_array[0]]['project_name'] = $elec_array[2];
						$customers[$customer_row['customer_name']]['special_project_job'][$elec_array[0]]['address'] = $elec_array[3];
						$customers[$customer_row['customer_name']]['special_project_job'][$elec_array[0]]['project_city'] = $elec_array[4];
						$customers[$customer_row['customer_name']]['special_project_job'][$elec_array[0]]['project_state'] = $elec_array[5];
						$customers[$customer_row['customer_name']]['special_project_job'][$elec_array[0]]['project_zip'] = $elec_array[6];
						$customers[$customer_row['customer_name']]['special_project_job'][$elec_array[0]]['project_phone'] = $elec_array[7];
						$customers[$customer_row['customer_name']]['special_project_job'][$elec_array[0]]['project_email'] = $elec_array[8];
					}
				}//if
				$customers[$customer_row['customer_name']]['customer_id'] = $customer_row['customer_id'];
				$customers[$customer_row['customer_name']]['customer_address'] = $customer_row['customer_address'];
				$customers[$customer_row['customer_name']]['customer_address2'] = $customer_row['customer_address2'];
				$customers[$customer_row['customer_name']]['customer_city'] = $customer_row['customer_city'];
				$customers[$customer_row['customer_name']]['customer_state'] = $customer_row['customer_state'];
				$customers[$customer_row['customer_name']]['customer_zip'] = $customer_row['customer_zip'];
				$customers[$customer_row['customer_name']]['customer_phone'] = $customer_row['customer_phone'];
				$customers[$customer_row['customer_name']]['customer_email'] = $customer_row['customer_email'];
				$customers[$customer_row['customer_name']]['customer_type'] = $customer_row['customer_type'];
				$results->items = $customers;
			}

		}
		if(!$customer_flag){
			$query .= $query_part . $from . $where . $having . $order_by . $query_limit;
			$query_all = DB::select(DB::raw("select count(*) as customer_count" . $from . $where . $having));
			$result = DB::select(DB::raw($query));
			$data_items = array();
			foreach ($result as $key => $value2) {
				$data_items[] = (array)$value2;
			}
			$results->items = $data_items;
		}
		$results->totalItems = (!$customer_flag) ?(@$query_all[0]->customer_count) : count($query_all);
		$results->customer_flag = $customer_flag;
		return $results;
	}

	/*
	* employeePayableAmountReport
	*/
	public function employeePayableAmountReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getEmpPayableAmtRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$employees = DB::select(DB::raw("select id, name from gpg_employee where status = 'A' AND concat(',',frontend,',') like '%,sales,%' or concat(',',frontend,',') like '%timesheet%' order by name"));
		$emp_arr = array();
		foreach ($employees as $key => $value) {
			$emp_arr[$value->id] = $value->name;
		}
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'emp_arr'=>$emp_arr);
		return View::make('qc_reports.employee_payable_amount_report', $params);	
	}
	public function getEmpPayableAmtRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$SJobNumber = strtoupper(Input::get("SJobNumber"));
		$EJobNumber = strtoupper(Input::get("EJobNumber"));
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$DSQL = "";
		$DQ2 = " ORDER BY employee_name";
		if ($SDate!="" || $EDate!="") {
			if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND DATE_FORMAT(date,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND t.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (t.date >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
						AND t.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
		}
		$employee = Input::get("employee") ; ;
		if ($employee!="" || (int)$employee > 0) {	
			$DSQL.= " AND e.id  = '".$employee."'"; 
		}
		if ($SJobNumber!="" and $EJobNumber!=""){
			$DSQL .= " AND j.job_num >= '".$SJobNumber."' AND j.job_num <= '".$EJobNumber."' ";
		}
		elseif ($SJobNumber!=""){ 
		 	$DSQL .= " AND j.job_num = '".$SJobNumber."'";
		}
		$query2 = "SELECT DISTINCT
		  t.GPG_employee_Id
		  , e.name AS employee_name
		  , r.id              AS job_rate_id
		  , r.job_number
		  , td.job_num
		  , r.pw_reg
		  , r.start_date
		  , r.end_date
		  , SUM(td.time_diff_dec) AS total_prevailing_hours
		  , e.GPG_employee_type_id AS time_employee_type_id
		  , t.date            AS timesheet_date
		FROM gpg_job_rates r
		  , gpg_timesheet t
		  , gpg_timesheet_detail td
		  , gpg_employee e
		  , gpg_job j
		WHERE r.job_number = IF(LENGTH(r.job_number) < 3,SUBSTRING(td.job_num,1,LENGTH(r.job_number)),td.job_num)
		    AND IFNULL(r.contract_number,'') = IF(LENGTH(r.job_number) < 3,(j.contract_number),'')
		    AND r.gpg_task_type = td.gpg_task_type
		    AND r.gpg_county_id = td.gpg_county_id
		    AND td.GPG_timesheet_id = t.id
		    AND e.id = t.GPG_employee_Id
			AND j.job_num = td.job_num
		    AND e.GPG_employee_type_id = r.GPG_employee_type_id
			AND e.GPG_employee_type_id != 3
		    AND t.date >= r.start_date
		    AND t.date <= r.end_date
		    AND td.pw_flag = 1
			AND td.GPG_timetype_id != 1
			$DSQL
		GROUP BY t.GPG_employee_Id, td.job_num,time_employee_type_id, td.gpg_county_id, td.labor_rate, td.gpg_task_type,start_date,end_date" ;
		$result =  DB::select(DB::raw("".($Filter=="jobCheck"?"select a.* from gpg_job_cost a left join gpg_job b on (a.job_num = b.job_num) where ifnull(b.job_num,'') = ''":$query2)." $DSQL $DQ2")); 
		$data_arr = array();
		foreach ($result as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		return $results;
	}
	
	/*
	* getEmpPayableAmtInfo
	*/
	public function getEmpPayableAmtInfo(){
		$str = '';
		$employee_id = Input::get("emp_id");
		$SJobNumber = Input::get("SJobNumber");
		$EJobNumber = Input::get("EJobNumber");
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		 $DSQL = "";
		if ($SDate!="" || $EDate!="") {
			if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND DATE_FORMAT(date,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND t.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (t.date >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
						AND t.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
		}
		if ($SJobNumber!="" and $EJobNumber!=""){
			$DSQL .= " AND j.job_num >= '".$SJobNumber."' AND j.job_num <= '".$EJobNumber."' ";
		}
		elseif ($SJobNumber!=""){ 
		 	$DSQL .= " AND j.job_num = '".$SJobNumber."'";
		}
		$result = DB::select(DB::raw("SELECT DISTINCT
		    t.GPG_employee_Id
		  , r.id AS job_rate_id
		  , r.job_number
		  , td.job_num
		  , r.pw_reg
		  , r.start_date
		  , r.end_date
		  , SUM(td.time_diff_dec) as total_prevailing_hours
		  , e.GPG_employee_type_id AS time_employee_type_id
		  , t.date AS timesheet_date
		  , r.pw_reg
		  , td.labor_rate
		  , (SELECT name FROM gpg_customer WHERE id = j.GPG_customer_id) as cus_name
		FROM gpg_job_rates r
		  , gpg_timesheet t
		  , gpg_timesheet_detail td
		  , gpg_employee e
		  , gpg_job j
		WHERE r.job_number = IF(LENGTH(r.job_number) < 3,SUBSTRING(td.job_num,1,LENGTH(r.job_number)),td.job_num)
		    AND IFNULL(r.contract_number,'') = IF(LENGTH(r.job_number) < 3,(j.contract_number),'')
		    AND r.gpg_task_type = td.gpg_task_type
		    AND r.gpg_county_id = td.gpg_county_id
		    AND td.GPG_timesheet_id = t.id
			AND e.id = t.GPG_employee_Id
			AND j.job_num = td.job_num
		    AND e.GPG_employee_type_id = r.GPG_employee_type_id
			AND e.GPG_employee_type_id != 3
		    AND t.date >= r.start_date
		    AND t.date <= r.end_date
		    AND td.pw_flag = 1
			AND td.GPG_timetype_id != 1
			AND t.GPG_employee_Id = '".$employee_id."'
			$DSQL
		GROUP BY t.GPG_employee_Id, td.job_num,time_employee_type_id, td.gpg_county_id, td.labor_rate, td.gpg_task_type,start_date,end_date
		ORDER BY td.job_num"));
		$colcount=0;
		$grand_total_employee_actual_rate = 0;
		$grand_total_actual_weca_rate = 0;
		foreach ($result as $key => $value2) {
			$row = (array)$value2;
			$colcount++;
			$perHourLabor = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$employee_id)->where('type','=','h')->where('start_date','<=',$row['timesheet_date'])->orderBy('start_date','DESC')->pluck('rate');
			$perHourLabor = ((float)$perHourLabor > 0) ? $perHourLabor : $row["labor_rate"] ;
			$basic_hourly_rate_job = 0 ;
			$health_and_welfare_job = 0 ;
			$pension_job = 0 ;
			$vacations_job = 0 ;
			$training_job = 0 ;
			$other_payments_job = 0 ;
			$total_prevailing_other_job_rate = 0 ;
			$basic_hourly_rate_deduction = 0 ;
			$health_and_welfare_deduction = 0 ;
			$pension_deduction = 0 ;
			$pension_deduction_calc = 0 ;
			$vacations_deduction = 0 ;
			$training_deduction = 0 ;
			$other_payments_deduction = 0 ;
			$total_prevailing_other_deduction_rate = 0 ;
			$grand_total_employee_deduction = 0 ;
			$grand_total_prevailing_job_rates = 0;
			$calc_basic_hourly_rate_job = 0 ;
			$grand_total_deduction_hourly_rate = 0 ;
			$calc_basic_hourly_rate_deduction = 0;
			$job_prevailing_rate = 0 ;
			$total_employee_actual_rate = 0 ;
			$total_actual_weca_rate = 0 ;
			if((float)$perHourLabor > 0){
				$result_rates = DB::select(DB::raw("SELECT * FROM gpg_job_rates_breakup WHERE job_rates_id = '".$row['job_rate_id']."'"));
				foreach ($result_rates as $key => $value3) {
					$row_rates = (array)$value3;
					if((float)$row_rates["pw_wages_rate_type"] == 1){
						$basic_hourly_rate_job = $row_rates["rate"] ;
					}
					elseif((float)$row_rates["pw_wages_rate_type"] == 2){
						$health_and_welfare_job = $row_rates["rate"] ;
						$total_prevailing_other_job_rate +=  $health_and_welfare_job ;
					}
					elseif((float)$row_rates["pw_wages_rate_type"] == 3){
						$pension_job = $row_rates["rate"] ;
						$total_prevailing_other_job_rate +=  $pension_job ;
					}
					elseif((float)$row_rates["pw_wages_rate_type"] == 4){
						$vacations_job = $row_rates["rate"] ;
						$total_prevailing_other_job_rate +=  $vacations_job ;
					}
					elseif((float)$row_rates["pw_wages_rate_type"] == 5){
						$training_job = $row_rates["rate"] ;
					}
					elseif((float)$row_rates["pw_wages_rate_type"] == 6){
						$other_payments_job = $row_rates["rate"] ;
						$total_prevailing_other_job_rate +=  $other_payments_job ;
					}
				}//end foreach
				if($basic_hourly_rate_job <= 0){
					$job_prevailing_rate = $row["pw_reg"] ;
					$basic_hourly_rate_job = $job_prevailing_rate ;
				} 
				$total_prevailing_rate_sum0 = DB::select(DB::raw("SELECT IFNULL(SUM(rate),0) as rate FROM gpg_job_rates_breakup WHERE job_rates_id = '".$row['job_rate_id']."'"));
				$total_prevailing_rate_sum = @$total_prevailing_rate_sum0[0]->rate;
				$result_rates = DB::select(DB::raw("SELECT * FROM gpg_employee_deduction WHERE gpg_employee_id = '".$employee_id."'"));
				if(count($result_rates)>0){
					foreach ($result_rates as $key => $value4) {
						$row_rates = (array)$value4;
						if((float)$row_rates["pw_wage_rate_type"] == 1){
							$basic_hourly_rate_deduction = $row_rates["rate"] ;
						}
						elseif((float)$row_rates["pw_wage_rate_type"] == 2){
							$health_and_welfare_deduction = $row_rates["rate"] ;
							$total_prevailing_other_deduction_rate +=  $health_and_welfare_deduction ;
						}
						elseif((float)$row_rates["pw_wage_rate_type"] == 3){
							$pension_deduction = $row_rates["rate"] ;
							$pension_deduction = $pension_deduction/100; // converting to percentage
							$pension_deduction_calc = (((float)$pension_deduction / 2) > 0.03) ? ((float)$basic_hourly_rate_job * 0.03) : ((float)$basic_hourly_rate_job * ((float)$pension_deduction / 2)) ;
						}
						elseif((float)$row_rates["pw_wage_rate_type"] == 4){
							$vacations_deduction = $row_rates["rate"] ;
							$total_prevailing_other_deduction_rate +=  $vacations_deduction ;
						}
						elseif((float)$row_rates["pw_wage_rate_type"] == 5){
							$training_deduction = $row_rates["rate"] ;
						}
						elseif((float)$row_rates["pw_wage_rate_type"] == 6){
							$other_payments_deduction = $row_rates["rate"] ;
							$total_prevailing_other_deduction_rate +=  $other_payments_deduction ;
						}
					}//end foreach
				}
			if(((float)$total_prevailing_rate_sum > 0) || ((float)$job_prevailing_rate > 0)){
				if((float)$perHourLabor > (float)$basic_hourly_rate_job){
					$calc_basic_hourly_rate_deduction = (float)$perHourLabor - (float)$basic_hourly_rate_job ;
					$calc_basic_hourly_rate_deduction = ((float)$basic_hourly_rate_deduction > 0) ? (($calc_basic_hourly_rate_deduction > $basic_hourly_rate_deduction) ? ($calc_basic_hourly_rate_deduction - $basic_hourly_rate_deduction) : 0) : $calc_basic_hourly_rate_deduction ;
				}//if
				elseif((float)$perHourLabor <= (float)$basic_hourly_rate_job){
					$calc_basic_hourly_rate_job = 0 ;
					$calc_basic_hourly_rate_deduction = 0 ;
				}//elseif
			}
			$grand_total_prevailing_job_rates = $calc_basic_hourly_rate_job + $total_prevailing_other_job_rate ;
			$grand_total_employee_deduction = $calc_basic_hourly_rate_deduction + $total_prevailing_other_deduction_rate + $pension_deduction_calc ;
			$grand_total_deduction_hourly_rate  = $perHourLabor + $basic_hourly_rate_deduction ;
			$employee_actual_job_rate = ($grand_total_prevailing_job_rates - $grand_total_employee_deduction) ;
			$actual_weca_rate = ($training_job - $training_deduction) ;
			$prevailing_hours = (float)$row["total_prevailing_hours"] ;
			$total_employee_actual_rate = $prevailing_hours * $employee_actual_job_rate ;
			$grand_total_employee_actual_rate += ($total_employee_actual_rate > 0) ? $total_employee_actual_rate : 0  ;
			$total_actual_weca_rate = $prevailing_hours * $actual_weca_rate ;
			$grand_total_actual_weca_rate += ($total_actual_weca_rate > 0) ? $total_actual_weca_rate : 0 ;
		}
			$str .= "<tr>";
	        $str .= "<td><strong>".$row['job_num']."</strong></td>";
	        $str .= "<td><strong>".$row['cus_name']."</strong></td>";
	        $str .= "<td>".(($perHourLabor > 0) ? number_format($perHourLabor,2) : '-')."</td>";
	        $str .= "<td>".(($basic_hourly_rate_job > 0) ? number_format($basic_hourly_rate_job,2):'-')."</td>";
	        $str .= "<td>".(($health_and_welfare_job > 0) ? number_format($health_and_welfare_job,2) : '-')."</td>";
	        $str .= "<td>".(($pension_job > 0) ? number_format($pension_job,2) : '-')."</td>";
	        $str .= "<td>".(($vacations_job > 0) ? number_format($vacations_job,2) : '-')."</td>";
	        $str .= "<td>".(($training_job > 0) ? number_format($training_job,2) : '-')."</td>";
	        $str .= "<td>".(($other_payments_job > 0) ? number_format($other_payments_job,2) : '-')."</td>";
	        $str .= "<td>".(($grand_total_prevailing_job_rates > 0) ? number_format($grand_total_prevailing_job_rates,2) : '-')."</td>";
	        $str .= "<td><strong>".(($prevailing_hours > 0) ? number_format($prevailing_hours,2) : '-')."</strong></td>";
	    	$str .= "</tr><tr>";
	    	$str .= "<td><strong>Employee Deductions</strong></td><td>&nbsp;</td><td>&nbsp;</td>";
	        $str .= "<td>".(($calc_basic_hourly_rate_deduction > 0) ? number_format($calc_basic_hourly_rate_deduction,2) : '-')."</td>";
	        $str .= "<td>".(($health_and_welfare_deduction > 0) ? number_format($health_and_welfare_deduction,2) : '-')."</td>";
	        $str .= "<td>".(($pension_deduction_calc > 0) ? number_format($pension_deduction_calc,2) : '-')."</td>";
	        $str .= "<td>".(($vacations_deduction > 0) ? number_format($vacations_deduction,2) : '-')."</td>";
	        $str .= "<td>".(($training_deduction > 0) ? number_format($training_deduction,2) : '-')."</td>";
	        $str .= "<td>".(($other_payments_deduction > 0) ? number_format($other_payments_deduction,2) : '-')."</td>";
	        $str .= "<td>".(($grand_total_employee_deduction > 0) ? number_format($grand_total_employee_deduction,2) : '-')."</td>";
	        $str .= "<td>&nbsp;</td></tr><tr>";
	        $str .= "<td colspan='9'><div style='float:right;padding-right:8px;'><strong>Difference</strong></div></td>";
	        $str .= "<td><strong>".(((float)$employee_actual_job_rate > 0) ? number_format($employee_actual_job_rate ,2) : '-')."</strong></td>";
	        $str .= "<td><strong>".(((float)$total_employee_actual_rate > 0) ? number_format($total_employee_actual_rate,2) : '-')."</strong></td></tr>";
	        $str .= "<tr><td colspan='9'><div style='float:right;padding-right:8px;''><strong>Training Difference (WECA)</strong></div></td>";
	        $str .= "<td><strong>".(((float)$actual_weca_rate > 0) ? number_format($actual_weca_rate,2) : '-')."</strong></td>";
	        $str .= "<td><strong>".(((float)$total_actual_weca_rate > 0) ? number_format($total_actual_weca_rate,2) : '-')."</strong></td>";
	        $str .= "</tr><tr><td colspan='11'>&nbsp;</td></tr>";
        }
        return $str;
	}
	/*
	* customerContactInfo
	*/
	public function customerContactInfo(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getCustomerContactInfoRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$customers = DB::table('gpg_customer')->orderBy('name')->lists('name','id');
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'customers'=>$customers);
		return View::make('qc_reports.customer_contact_info', $params);	
	}
	public function getCustomerContactInfoRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$cus_name = Input::get('cus_name');
		$part_sql="";
		if($cus_name!="") {
			$part_sql= "AND gpg_customer_id = '".$cus_name."'";
		}
		$count = DB::select(DB::raw('SELECT
				gpg_customer_id,
				(SELECT
				NAME
				FROM gpg_customer
				WHERE id = gpg_customer_id) AS cus_name,
				COUNT(*) as tot_contact,
				GROUP_CONCAT(id,"--@--",IFNULL(STATUS,""),"--@--",IFNULL(type_of_sale,""),"--@--",IF(gpg_employee_id IS NULL,"",IFNULL((SELECT
				NAME
				FROM gpg_employee
				WHERE id = gpg_employee_id),"")),"--@--",IFNULL(contact_info,""),"~@@@~") AS cnt_info
				FROM gpg_sales_tracking
				WHERE 1 '.$part_sql.'
				GROUP BY gpg_customer_id
				ORDER BY cus_name'));
		$result = DB::select(DB::raw('SELECT
				gpg_customer_id,
				(SELECT
				NAME
				FROM gpg_customer
				WHERE id = gpg_customer_id) AS cus_name,
				COUNT(*) as tot_contact,
				GROUP_CONCAT(id,"--@--",IFNULL(STATUS,""),"--@--",IFNULL(type_of_sale,""),"--@--",IF(gpg_employee_id IS NULL,"",IFNULL((SELECT
				NAME
				FROM gpg_employee
				WHERE id = gpg_employee_id),"")),"--@--",IFNULL(contact_info,""),"~@@@~") AS cnt_info
				FROM gpg_sales_tracking
				WHERE 1 '.$part_sql.'
				GROUP BY gpg_customer_id
				ORDER BY cus_name '.$limitOffset));
		$data_arr = array();
		foreach ($result as $key => $value1) {
			$data_arr[] = (array)$value1;
		}	
		$results->totalItems = count($count);
		$results->items = $data_arr;
		return $results;
	}
	
	/*
	* missingJobsInfo
	*/
	public function missingJobsInfo(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getMissingJobInfoRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('qc_reports.missing_jobs_info', $params);	
	}
	public function getMissingJobInfoRepByPage($page = 1, $limit = null){
		set_time_limit(0);
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
		$cus_name = "";
		$opt_type = Input::get('opt_type');
		$opt_group= Input::get('opt_group');
		if($opt_group=="")
			$opt_group = "emp";
		if($opt_type=="")
			$opt_type = "loc";
		$part_sql="";
		$DSQL = "";
		$group_part = "";
		if($opt_group=="emp"){
			$DSQL = " GROUP BY IFNULL(gpg_field_service_work.GPG_employee_id,0)"; $g_key = 'GPG_employee_id';
		}elseif($opt_group=="cus")
		{
		 	$DSQL = " GROUP BY GPG_customer_id"; $g_key = 'GPG_customer_id';
		}
		if($opt_type=="loc"){
	 		$part_sql= "SELECT count(*) as tot_count,
						gpg_field_service_work.GPG_employee_id,
						gpg_field_service_work.GPG_customer_id,
			 			(SELECT name FROM gpg_customer WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
						(SELECT name FROM gpg_employee WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
						FROM 
						gpg_field_service_work
						WHERE 
						gpg_field_service_work.gpg_consum_contract_equipment_id IS NULL 
						OR gpg_field_service_work.gpg_consum_contract_equipment_id = \"\" ";
		 }
		 elseif($opt_type=="job_comp")
		 {
			 $part_sql= "SELECT COUNT(*) AS tot_count,
					    gpg_field_service_work.GPG_employee_id,
						gpg_field_service_work.GPG_customer_id,
						(SELECT
						NAME
						FROM gpg_customer
						WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
						(SELECT
						NAME
						FROM gpg_employee
						WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
						FROM gpg_field_service_work,
						gpg_consum_contract_equipment
						WHERE gpg_consum_contract_equipment.id = gpg_field_service_work.gpg_consum_contract_equipment_id
						AND (gpg_field_service_work.job_site_contact IS NULL
						OR gpg_field_service_work.job_site_contact = \"\")
						AND (gpg_consum_contract_equipment.address1 IS NULL
						OR gpg_consum_contract_equipment.address1 = \"\")
						AND (gpg_consum_contract_equipment.city IS NULL
						OR gpg_consum_contract_equipment.city = \"\")
						AND (gpg_consum_contract_equipment.state IS NULL
						OR gpg_consum_contract_equipment.state = \"\")
						AND (gpg_consum_contract_equipment.phone IS NULL
						OR gpg_consum_contract_equipment.phone = \"\")";
		 }
		  elseif($opt_type=="job_part")
		 {
			 $part_sql= "SELECT
							  COUNT(*)                               AS tot_count,
							  gpg_field_service_work.GPG_employee_id,
							  gpg_field_service_work.GPG_customer_id,
							  (SELECT
								 NAME
							   FROM gpg_customer
							   WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
							  (SELECT
								 NAME
							   FROM gpg_employee
							   WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
							FROM gpg_field_service_work,
							  gpg_consum_contract_equipment
							WHERE gpg_consum_contract_equipment.id = gpg_field_service_work.gpg_consum_contract_equipment_id
								AND ((gpg_field_service_work.job_site_contact IS NULL
									  OR gpg_field_service_work.job_site_contact = \"\")
								OR (gpg_consum_contract_equipment.address1 IS NULL
									  OR gpg_consum_contract_equipment.address1 = \"\")
								OR (gpg_consum_contract_equipment.city IS NULL
									  OR gpg_consum_contract_equipment.city = \"\")
								OR (gpg_consum_contract_equipment.state IS NULL
									  OR gpg_consum_contract_equipment.state = \"\")
								OR (gpg_consum_contract_equipment.phone IS NULL
									  OR gpg_consum_contract_equipment.phone = \"\"))
							";
		}

		elseif($opt_type=="eng_part")
		{
			 $part_sql= "SELECT COUNT(*) AS tot_count,
						gpg_field_service_work.GPG_employee_id,
						gpg_field_service_work.GPG_customer_id,
						(SELECT
						NAME
						FROM gpg_customer
						WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
						(SELECT
						NAME
						FROM gpg_employee
						WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
						FROM gpg_field_service_work
						WHERE (gpg_field_service_work.eng_make IS NULL
						OR gpg_field_service_work.eng_make = \"\")
						OR (gpg_field_service_work.eng_model IS NULL
						OR gpg_field_service_work.eng_model = \"\")
						OR (gpg_field_service_work.eng_serial IS NULL
						OR gpg_field_service_work.eng_serial = \"\")
						OR (gpg_field_service_work.eng_spec IS NULL
						OR gpg_field_service_work.eng_spec = \"\")";
		}elseif($opt_type=="eng_comp")
		{
			$part_sql= "SELECT count(*) as tot_count, GROUP_CONCAT(
								job_num,
								\"~##~\",(SELECT name FROM gpg_customer WHERE id = gpg_field_service_work.GPG_customer_id),
								\"~##~\",(SELECT name FROM gpg_employee WHERE id = gpg_field_service_work.GPG_employee_id),
								\"@###@\"						
								) as msng_dat,
								 gpg_field_service_work.GPG_employee_id,
							  gpg_field_service_work.GPG_customer_id,
			 				(SELECT name FROM gpg_customer WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
							(SELECT name FROM gpg_employee WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
							FROM 
							  gpg_field_service_work
							WHERE 
							(gpg_field_service_work.eng_make IS NULL 
							OR gpg_field_service_work.eng_make = \"\")
							AND
							(gpg_field_service_work.eng_model IS NULL 
							OR gpg_field_service_work.eng_model = \"\")
							AND
							(gpg_field_service_work.eng_serial IS NULL 
							OR gpg_field_service_work.eng_serial = \"\")
							AND
							(gpg_field_service_work.eng_spec IS NULL 
							OR gpg_field_service_work.eng_spec = \"\")
							";
		}
		elseif($opt_type=="gen_part")
		{
			 $part_sql= "SELECT COUNT(*) AS tot_count,
						gpg_field_service_work.GPG_employee_id,
						gpg_field_service_work.GPG_customer_id,
						(SELECT
						NAME
						FROM gpg_customer
						WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
						(SELECT
						NAME
						FROM gpg_employee
						WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
						FROM gpg_field_service_work
						WHERE (gpg_field_service_work.gen_make IS NULL
						OR gpg_field_service_work.gen_make = \"\")
						OR (gpg_field_service_work.gen_model IS NULL
						OR gpg_field_service_work.gen_model = \"\")
						OR (gpg_field_service_work.gen_serial IS NULL
						OR gpg_field_service_work.gen_serial = \"\")
						OR (gpg_field_service_work.gen_spec IS NULL
						OR gpg_field_service_work.gen_spec = \"\") ";
		}
		elseif($opt_type=="gen_comp") {
			$part_sql= "SELECT count(*) as tot_count,
					    gpg_field_service_work.GPG_employee_id,
						gpg_field_service_work.GPG_customer_id,
			 			(SELECT name FROM gpg_customer WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
						(SELECT name FROM gpg_employee WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
						FROM 
						gpg_field_service_work
						WHERE 
						(gpg_field_service_work.gen_make IS NULL 
						OR gpg_field_service_work.gen_make = \"\")
						AND
						(gpg_field_service_work.gen_model IS NULL 
						OR gpg_field_service_work.gen_model = \"\")
						AND
						(gpg_field_service_work.gen_serial IS NULL 
						OR gpg_field_service_work.gen_serial = \"\")
						AND
						(gpg_field_service_work.gen_spec IS NULL 
						OR gpg_field_service_work.gen_spec = \"\")";
		}
		$result = DB::select(DB::raw($part_sql.$DSQL));
		$data_arr = array();
		foreach ($result as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		return $results;
	}
	
	/*
	* mappingExport
	*/
	public function mappingExport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		if (isset($_POST['method']) && !empty($_POST['method'])) {
			$this->mappingExportResult();
		}
		$params = array('left_menu' => $modules);
		return View::make('qc_reports.mapping_export', $params);	
	}

	/*
	* mappingExportResult
	*/
	public function mappingExportResult(){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		Excel::create('MappingReportExport', function($excel) {
		    $excel->sheet('MappingReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $request = explode('~', Input::get('method'));
	        $data = DB::select(DB::raw("SELECT object_id FROM gpg_quickbook_mapping WHERE type = '".@$request[1]."' AND mapping_type = '0'"));
	        $ids = array();
	        foreach($data as $value) {
	            $parts = explode('~', $value->object_id);
	            if(count($parts) > 0){
	                for($i = 0; $i < count($parts); $i++){
	                    $ids[] = $parts[$i];
	                }
	            } else {
	                $ids[] = $parts[0];
	            }

	        }
	        $data = DB::select(DB::raw("SELECT * FROM `".$request[0]."` WHERE id NOT IN(".implode(', ', $ids).")"));
	        $data_arr = array();
	        foreach ($data as $key => $value2) {
	        	$data_arr[] = (array)$value2;
	        }
	    	$params = array('query_data'=>$data_arr);
		    $sheet->loadView('qc_reports.mappingExportResult',$params);
		    });
		})->export('csv');
	}

	public function getAllInvalidRecords($table){
	    if($table == 'gpg_job'){
	      $query = "SELECT id, job_num as name FROM ".$table." WHERE length(job_num) > 41";
	    }else if($table == 'gpg_expense_gl_code'){
	    	$query = "SELECT id, (expense_gl_code · description) as name FROM ".$table." WHERE length(expense_gl_code · description) > 41";
	  	}else {
	   		$query = "SELECT id, name FROM ".$table." WHERE length(name) > 41";
	  	}
	  	$data = DB::select(DB::raw($query));
	  return $data;
	}
	/*
	* quickbookAnalyzer
	*/
	public function quickbookAnalyzer(){
		set_time_limit(0);
		ini_set('memory_limit','-1');
		$modules = Generic::modules();
		Input::flash();
		$tables = array(
		    'gpg_vendor' => 'Vendors',
		    'gpg_customer' => 'Customers',
		    'gpg_job' => 'Jobs',
		    'gpg_expense_gl_code' => 'Account',
		    );
	    $comparison = array(
		    'QtoG' => 'Quickbook To GPG',
		    'GtoQ' => 'GPG to Quickbook'
		    );
		if (isset($_POST) && !empty($_POST)) {
			$inValidRecords = $this->getAllInvalidRecords(Input::get('method'));
			if (count($inValidRecords) == 0) {
				////////////////// Quick Book Start //////////////////
				$json = array();
			    if(Input::get('getmatching') && Input::get('match_mapping')){
			       if ( Input::get('comparison') == '0' ) {
			            if(count(Input::get('match_mapping')) > 0){
			                foreach(Input::get('match_mapping') as $key => $ArrayValue){
			                    foreach($ArrayValue as $subKey => $value){
			                        if(Input::get('type') == 0 && strpos($value,' · ') !== false){
			                            $map = str_replace(' · ', '@~#~@', $value);
			                        } else {
			                            $map = $value;
			                        }
			                        $record = DB::select(DB::raw("SELECT * FROM gpg_quickbook_mapping WHERE type='".Input::get('type')."' AND mapping = '".($map)."' AND mapping_type = '".Input::get('comparison')."'"));
			                        if(isset($record[0]->object_id)){
			                            $check = explode('~', $record[0]->object_id);
			                            $search = array_search($key, $check);
			                            if($check[$search] == $key){
			                                $json['matched'][$key] = $value;
			                            }
			                        }
			                    }
			                }
			            }
			            if(isset($json['matched']) && count($json['matched'])){
			                $json['status'] = 'success';
			                $json['message'] = 'success';
			                $json['mapping_type'] = '0';
			            } else {
			                $json['status'] = 'error';
			                $json['message'] = 'error';
			                $json['matched'] = array();
			            }
			        } elseif ( Input::get('comparison') == '1' ) {
			            if(count(Input::get('match_mapping')) > 0){
			               foreach(Input::get('match_mapping') as $key => $value){
			                    if(is_array($value)){
			                        foreach($value as $mapValue){
			                            $record = DB::select(DB::raw("SELECT * FROM gpg_quickbook_mapping WHERE type='".Input::get('type')."' AND object_id = '".($key)."' AND mapping LIKE '%".($mapValue)."%' AND mapping_type = '".Input::get('comparison')."'"));
			                            if(isset($record[0]->object_id)){
			                                $check = explode('~#~', $record[0]->mapping);
			                                $search = array_search($mapValue, $check);
			                                if($check[$search] == $mapValue){
			                                    $json['matched'][$key][] = $value;
			                                }
			                            }
			                        }
			                    }
			                }
			            }
			            if(isset($json['matched']) && count($json['matched'])){
			                $json['status'] = 'success';
			                $json['message'] = 'success';
			                $json['mapping_type'] = '1';
			            } else {
			                $json['status'] = 'error';
			                $json['message'] = 'error';
			                $json['matched'] = array();
			            }
			        }
			        echo json_encode($json);
			        die();
			    }
			    else if(count(Input::get('mapping'))){
			        /*--------------- Delete the mapped records --------------*/
			        if(Input::get('comparison') == '0'){
			            foreach(Input::get('deleteMapping') as $key => $value){
			                if(Input::get('type') == 0 && strpos($value,' · ') !== false){
			                    $map = str_replace(' · ', '@~#~@', $value);
			                } else {
			                    $map = $value;
			                }
			                $record = DB::select(DB::raw("SELECT * FROM gpg_quickbook_mapping WHERE type='".Input::get('type')."' AND mapping = '".($map)."' AND mapping_type = '".Input::get('comparison')."'"));
			                if(isset($record[0]->object_id)){
			                    $check = explode('~', $record[0]->object_id);
			                    $search = array_search($key, $check);
			                    if($check[$search] == $key){
			                        unset($check[$search]);
			                        if(count($check) > 1){ 
			                        	DB::table('gpg_quickbook_mapping')->where('id','=',$record[0]->id)->update(array('object_id'=>(implode('~', $check))));
			                        } else {
			                        	DB::table('gpg_quickbook_mapping')->where('id','=',$record[0]->id)->delete();
			                        }
			                    }
			                }
			            }
			        }
			        else if(Input::get('comparison') == '1'){
			        	//
			        }
			        /*---------------- Save or update mapped records ---------------*/
			       foreach (Input::get('mapping') as $key => $value) {
			           if(Input::get('comparison') == '0'){
			               if(Input::get('type') == 0 && strpos($value,' · ') !== false){
			                   $map = str_replace(' · ', '@~#~@', $value);
			               } else {
			                   $map = $value;
			               }
			                $record = DB::select(DB::raw("SELECT * FROM gpg_quickbook_mapping WHERE type='".Input::get('type')."' AND mapping = '".($map)."' AND mapping_type = '".Input::get('comparison')."'"));
			                if(isset($record[0]->object_id)){
			                    $check = explode('~', $record[0]->object_id);
			                    $search = array_search($key, $check);
			                    if($check[$search] != $key){
			                        $object_id = $record[0]->object_id.'~'.$key;
			                        DB::table('gpg_quickbook_mapping')->where('id','=',$record[0]->id)->update(array('object_id'=>$object_id,'created_date'=>date('Y-m-d')));
			                    }                    
			                }
			                else {
			                	DB::table('gpg_quickbook_mapping')->insert(array('mapping'=>$map,'object_id'=>$key,'type'=>Input::get('type'),'created_date'=>date('Y-m-d'),'mapping_type'=>Input::get('comparison')));
			                }
			            } else if(Input::get('comparison') == '1'){
			                $record = DB::select(DB::raw("SELECT * FROM gpg_quickbook_mapping WHERE type='".Input::get('type')."' AND object_id = '".($key)."' AND mapping_type = '".Input::get('comparison')."'"));
			                if(isset($record[0]->object_id)){
			                    $check = explode('~#~', $record[0]->mapping);
			                    if(is_array($value)){
			                        foreach ($value as $valuekey => $KeyValue) {
			                            $search = array_search($KeyValue, $check);
			                            if($check[$search] != $KeyValue){
			                                $check[] = $KeyValue;
			                            }   
			                        }
			                        DB::table('gpg_quickbook_mapping')->where('id','=',$record[0]->id)->update(array('mapping'=>implode('~#~', $check),'created_date'=>date('Y-m-d')));
			                    }else {
			                        $search = array_search($value, $check);
			                        if($check[$search] != $value){
			                            $mapping = $record[0]->mapping.'~#~'.$value;
			                            DB::table('gpg_quickbook_mapping')->where('id','=',$record[0]->id)->update(array('mapping'=>$mapping,'created_date'=>date('Y-m-d')));
			                        }
			                    }                    
			                }
			                else {
			                    if(is_array($value)){
			                        $mappingValue = '';
			                        foreach ($value as $valueKey => $keyValue) {
			                            $mappingValue[] = $keyValue;  
			                        }  
			                        DB::table('gpg_quickbook_mapping')->insert(array('mapping'=>implode('~#~', $mappingValue),'object_id'=>$key,'type'=>Input::get('type'),'created_date'=>date('Y-m-d'),'mapping_type'=>Input::get('comparison')));       
			                    }else {
			                    	DB::table('gpg_quickbook_mapping')->insert(array('mapping'=>$value,'object_id'=>$key,'type'=>Input::get('type'),'created_date'=>date('Y-m-d'),'mapping_type'=>Input::get('comparison')));       
			                    }                    
			                }
			            }            
			                   
			        }
			        $json['status'] = 'success';
			        $json['message'] = 'Data saved into database successfully!';
			    }else {
			        $json['status'] = 'error';
			        $json['message'] = 'Input parameters are empty.';
			    }
			    return Redirect::to('qc_reports/quickbook_analyzer')->withSuccess('Records Updated Successfully');
				///////////////// Quick Book Ends ///////////////////
			}
		}

		$params = array('left_menu' => $modules);
		return View::make('qc_reports.quickbook_analyzer', $params);	
	}
	/*
	* overHeadBudgeting
	*/
	public function overHeadBudgeting(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			if (isset($_POST['hidden_count']) && !empty($_POST['hidden_count']) && $_POST['hidden_count']>0){
				ini_set("max_execution_time", 14400);
				$SDate = Input::get('SDate');
				$EDate = Input::get('EDate');
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
					$gpg_expense_gl_code_id=0;
					$otherArr = array();
					$counter = count($heading)-1;
					for ($i=0; $i<count($heading); $i++) { 
						if(preg_match("/date/i",$heading[$i]) || preg_match("/last_modified_on/i",$heading[$i])) { 
				 			$date = date('Y-m-d',strtotime(($values[$i])));
				 			$otherArr += array('date'=>$date);
				 		}
				 		elseif(preg_match("/glcode/i",$heading[$i])) { 
				 			$gpg_expense_gl_code_id = $values[$i];
				 			$otherArr += array('gpg_expense_gl_code_id'=>$gpg_expense_gl_code_id);
				 		} 
				 		elseif(preg_match("/amount/i",$heading[$i]) || preg_match("/credit/i",$heading[$i]) || preg_match("/debit/i",$heading[$i])) { 
				 			$amount = $values[$i];
				 			$otherArr += array($heading[$i]=>$amount);
				 		}  
				 		/*else{
							$otherVal = $values[$i];
							$otherArr += array($heading[$i]=>$otherVal);
						}*/
						if ($counter == $i) {
							if ($gpg_expense_gl_code_id != 0 && !empty($otherArr)) {
								if($SDate!=='' and $EDate!='')
									DB::table('gpg_over_head_budget')->where('date','>=',date('Y-m-d',strtotime($SDate)))->where('date','<=',date('Y-m-d',strtotime($EDate)))->delete();
								DB::table('gpg_over_head_budget')->insert($otherArr+array('modified_on'=>date('Y-m-d'),'created_on'=>date('Y-m-d')));
							}
						}	
					}
				}
				return Redirect::to('qc_reports/over_head_budgeting')->withSuccess('Records have been Updated Successfully');
			}else{
				$SDate = Input::get('SDate');
				$EDate = Input::get('EDate');
				$file = Input::file('uploadFile');
				$filename = "";
				if (!empty($file)) {
					$file1 = Input::file('uploadFile')->getClientOriginalName();
					$filename = "ohBudgetingImp_".rand(99999,10000000)."_".strtotime("now").".".$file1;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
				}
				$fh = fopen($destinationPath.$filename,'r');
				$jcostopt = trim(fgets($fh));
				$jcostopt_arr = explode("\t", $jcostopt);
				fclose($fh);
				$DBFields = array("type"=>"Type","date"=>"Date","num"=>"Num","name"=>"Name","source_name"=>"Source Name","memo"=>"Memo","class"=>"Class","clr"=>"Clr","split"=>"Split","debit"=>"Debit","credit"=>"Credit","amount"=>"Amount","last_modified_on"=>"Entered/Last Modified","modified_by"=>"Last modified by");
				$params = array('left_menu' => $modules,'success'=>'0','step'=>'1','jcostopt_arr'=>$jcostopt_arr,'DBFields'=>$DBFields,'dest'=>$destinationPath,'filename'=>$filename,'SDate'=>$SDate,'EDate'=>$EDate);
				return View::make('qc_reports.over_head_budgeting', $params);
			}	
		}
		$params = array('left_menu' => $modules,'success'=>'0','step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array());
		return View::make('qc_reports.over_head_budgeting', $params);
	}
	
	/*
	* projActivityReport
	*/
	public function projActivityReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$str = '';
		$query = DB::select(DB::raw("SELECT DISTINCT (project_title),id FROM gpg_job where project_title!='' Group by project_title"));
		$projects = array();
		foreach ($query as $key => $value) {
			$projects[$value->id] = $value->project_title;
		}
		if (isset($_POST['project_title']) && !empty($_POST['project_title'])) {
			$mainOption = Input::get('project_title');
			if ($mainOption != '0') {
				$result = DB::select(DB::raw("SELECT
				gpg_job.project_title,
				jp.id,
				jp.GPG_job_num,
				jp.GPG_job_id,
				jp.title,
				jp.days,
				jp.start_date,
				MAX(jp.end_date) end_date,
				jp.project_activity_id,
				jp.notes 
			FROM gpg_job,
				 gpg_job_project jp 
			WHERE 
				gpg_job.id = jp.GPG_job_id AND
				jp.GPG_job_id='".$mainOption."'"));
				
				$tables =  DB::select(DB::raw("SELECT
				gpg_job.project_title,
				jp.id,
				jp.GPG_job_num,
				jp.GPG_job_id,
				jp.title,
				jp.days,
				jp.start_date,
				jp.end_date,
				jp.project_activity_id,
				jp.task_type,
				jp.notes 
				FROM gpg_job,
					 gpg_job_project jp 
				WHERE 
				gpg_job.id = jp.GPG_job_id AND
				jp.GPG_job_id='".$mainOption."'"));
				//////////////
				$count_estimated_hrs =  DB::select(DB::raw("SELECT 
				(SELECT IFNULL(SUM(IFNULL(gpg_job_project_task.projected,0)),0)
			     FROM gpg_job_project_task WHERE gpg_job_project_id = jp.id) AS estimated_hrs,
			     jp.project_activity_id
				FROM 
					gpg_job_project jp,
					gpg_job 
				WHERE 
					 gpg_job.id=jp.GPG_job_id AND
					 gpg_job.id='".$mainOption."' 
				Group by (jp.id)"));
				$estimated_hrs = array();
				foreach ($count_estimated_hrs as $key => $row2) {
					array_push($estimated_hrs,$row2->estimated_hrs);
				}
				$count_actual_hrs =  DB::select(DB::raw("SELECT 
				gjp.project_activity_id,
			    SUM(tsd.time_diff_dec) AS hrs_worked,
			    ts.date
				FROM 
					gpg_timesheet_detail tsd,
				    gpg_timesheet ts,
				    gpg_job_project gjp
				WHERE
				    tsd.gpg_activity_id = gjp.id AND
					tsd.GPG_job_id='".$mainOption."' AND 
				    tsd.GPG_timesheet_id=ts.id
				GROUP BY tsd.gpg_activity_id,ts.date    
				ORDER By gjp.project_activity_id"));
				$actual_hrs = array();
				foreach ($count_actual_hrs as $key => $row4) {
					$actual_hrs[$row4->project_activity_id][$row4->date]= array('work_time' => $row4->hrs_worked);
				}
			///////////
			$emp_working_info = DB::select(DB::raw("SELECT 
				gjp.project_activity_id,
			    tsd.time_diff_dec AS hrs_worked,
			    ts.date ,
			    emp.name
				FROM 
					gpg_timesheet_detail tsd,
				    gpg_timesheet ts,
				    gpg_job_project gjp,
				    gpg_employee emp
				WHERE
					tsd.gpg_activity_id = gjp.id AND
					tsd.GPG_job_id='".$mainOption."' AND 
				    tsd.GPG_timesheet_id=ts.id AND
				    ts.GPG_employee_Id = emp.id
				ORDER By gjp.project_activity_id"));		
					$i=0;
					$prev="";
					$emp_data = array();
				foreach ($emp_working_info as $key => $row6) {
					$emp_data[$i][$row6->project_activity_id] = array('work_date' => $row6->date,'hrs_worked' => $row6->hrs_worked,'emp_name' => $row6->name);
					$i++;
				}
			$total_actual_hours = DB::select(DB::raw("SELECT 
				gjp.project_activity_id,
			    SUM(tsd.time_diff_dec) as total_working_hrs, 
			    ts.date
				FROM 
					gpg_timesheet_detail tsd,
				    gpg_timesheet ts,
				    gpg_job_project gjp
				WHERE
				    tsd.gpg_activity_id = gjp.id AND
					tsd.GPG_job_id='".$mainOption."' AND 
				    tsd.GPG_timesheet_id=ts.id
				GROUP BY tsd.gpg_activity_id    
				ORDER By gjp.project_activity_id"));	
				$sum_of_actual_hrs = array();
				foreach ($total_actual_hours as $key => $row5) {
					$sum_of_actual_hrs[$row5->project_activity_id]= array('total_time' => $row5->total_working_hrs);
				}	
				$projected_inspection = DB::select(DB::raw("SELECT
				jpt.inspection,
				jpt.projected,
				jp.project_activity_id,
				jpt.project_date 
				FROM gpg_job_project_task jpt,
					gpg_job_project jp,
					gpg_job 
				WHERE jpt.gpg_job_project_id=jp.id and
					gpg_job.id=jp.GPG_job_id AND
					gpg_job.id='".$mainOption."'"));
				$proj_inspect_array = array();
				foreach ($projected_inspection as $key => $row3) {
					$proj_inspect_array[$row3->project_activity_id][$row3->project_date]= array('projected' => $row3->projected,'inspection' => $row3->inspection);
				}
				$str .= "<span id='header'>"."Project Name:&nbsp&nbsp<i>".$result[0]->project_title."</i></span><br/>";
				$str .= "<span id='header3'>"."GPG#:&nbsp&nbsp<i>".$result[0]->GPG_job_num."</i></span><br/>";
				$str .= "<span id='header2'>"."Start Date:<i>".$result[0]->start_date."</i></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
				$str .= "&nbsp&nbsp&nbsp&nbsp<span id='header2'>"."End Date:<i>".$result[0]->end_date."</i></span>";
				$str .= "<br/><div><b>Construction Crew:&nbsp&nbsp</b><span class='disp_block'></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>Electrical Crew:&nbsp&nbsp</b><span class='disp_block1'></span>";
				$str .= "</div><hr/>";
				$str .= "<div class='p-tooltip'></div>";
				$str .= "<table border='1' id='datalist'>";
				$str .= "<tr>
						<td rowspan='3' bgcolor='#DCDCDC' class='txt_align'><b>Approx. 2 man days<b/></td>
						<td rowspan='3' id='td_color' class='txt_align' nowrap><b>Activity ID<b/></td>
						<td rowspan='3' id='td_color' class='txt_align' nowrap><b>Est. Hours<b/></td>
						<td rowspan='3' id='td_color' class='txt_align'><b>Actual Hours<b/></td>
						<td  rowspan='3' width='150%' id='td_color' class='txt_align'><b>Description<b/></td>
						<td id='td_color' class='code'><b>Week</b></td>";
						
				$start = strtotime($result[0]->start_date);
				$end = strtotime($result[0]->end_date);
				$diff_days = ceil(abs($end - $start) / 86400);
				$count_me=0;
				$project_day_for_week = date("D", strtotime($result[0]->start_date));
				$proj_date_temp1 = $result[0]->start_date;
				if ($project_day_for_week!='Mon') {
					while ($project_day_for_week!='Mon') {
						$project_date_orig1 = date("Y-m-d", strtotime("-1 day", strtotime($proj_date_temp1)));
						$proj_date_temp1 = $project_date_orig1;
						$project_day_for_week = date("D", strtotime($project_date_orig1));
						$count_me++;
					}
				}
				if ($diff_days==0) {
					$diff_days++;
					$l= $diff_days;
				}else{
					$c_days = $diff_days+$count_me;
					if (strtotime($result[0]->end_date) <= strtotime(date("Y-m-d", strtotime("+$c_days day", strtotime($result[0]->start_date)))) ) {
						$diff_days=$diff_days+1;
					}
					$l=ceil(($diff_days+$count_me)/7);
				}
				$p=1;
				while($p <= $l){
					$str .= "<td colspan='7' id='heading'><b>".$this->addOrdinalNumberSuffix($p)."  WEEK ESTIMATED</b></td>";
					$str .= "<td colspan='7' id='heading2'><b>".$this->addOrdinalNumberSuffix($p)." WEEK ACTUAL</b></td>";
					$p++;	
				}			
				$str .= "</tr><tr><td id='td_color'><b>Date</b></td>";
				$j=0;
				$counter=0;
				$check_day=0;
				$proj_date_arr = array();
				$global_project_date_array = array();
				while($j < $diff_days){
					$project_day = date("D", strtotime($result[0]->start_date));
					if ($check_day==0 && $project_day!='Mon' && !empty($result[0]->start_date) && $result[0]->start_date!='0000-00-00') {
						$tmp_date_arr = array();
						$tmp_full_date_arr = array();
						$proj_date_temp = $result[0]->start_date;
						while ($project_day!='Mon') {
							$project_date_orig = date("Y-m-d", strtotime("-1 day", strtotime($proj_date_temp)));
							$proj_date_temp = $project_date_orig;
							$project_date = date("m/d", strtotime($project_date_orig));
							$project_day = date("D", strtotime($project_date_orig));
							array_push($tmp_full_date_arr,$project_date_orig);
							array_push($tmp_date_arr,$project_date);
							$counter++;
						}
						$tmp_cnt=count($tmp_date_arr);
						while ($tmp_cnt != 0) {
							array_push($global_project_date_array, $tmp_full_date_arr[$tmp_cnt-1]);
							array_push($proj_date_arr, $tmp_date_arr[$tmp_cnt-1]);
							$str .= "<td id='td_color' class='txt_align'>".$tmp_date_arr[$tmp_cnt-1]."</td>";
							$tmp_cnt--;
						}
						$check_day++;					
					}
					$project_date = date("m/d", strtotime("+$j day", strtotime($result[0]->start_date)));
					$project_date_orig = date("Y-m-d", strtotime("+$j day", strtotime($result[0]->start_date)));
					array_push($global_project_date_array, $project_date_orig);
					$str .= "<td id='td_color' class='txt_align'>".$project_date."</td>";
					$j++;
					array_push($proj_date_arr, $project_date);
					if (count($proj_date_arr) == 7) {
						$c=0;
						while($c<count($proj_date_arr)){
							$str .= "<td id='td_color' class='txt_align'>".$proj_date_arr[$c]."</td>";
							$c++;
						}
						$counter=0;
						unset($proj_date_arr);
						$proj_date_arr = array();
					}
					$project_day = date("D", strtotime($result[0]->end_date));
					if($j == $diff_days && $project_day!='Sun'){
						while ( $project_day!='Sun') {
							$project_day = date("D", strtotime("+$j day", strtotime($result[0]->start_date)));
							$project_date = date("m/d", strtotime("+$j day", strtotime($result[0]->start_date)));
							$project_date_orig = date("Y-m-d", strtotime("+$j day", strtotime($result[0]->start_date)));
							array_push($global_project_date_array, $project_date_orig);
							array_push($proj_date_arr, $project_date);
							$str .= "<td id='td_color' class='txt_align'>".$project_date."</td>";
							$j++;
						}
						$c=0;
						while($c<count($proj_date_arr)){
							$str .= "<td id='td_color' class='txt_align'>".$proj_date_arr[$c]."</td>";
							$c++;
						}
					}						
				}
				$str .= "</tr><tr><td id='td_color'><b>Day</b></td>";
				$k=0;
				$counter=0;
				$check_day=0;
				$proj_day_arr = array();
				while($k < $diff_days){
					$project_day = date("D", strtotime($result[0]->start_date));
					if ($check_day==0 && $project_day!='Mon' && !empty($result[0]->start_date) && $result[0]->start_date!='0000-00-00') {
						$temp_days_arr = array();
						$proj_date_temp = $result[0]->start_date;
						while ($project_day!='Mon') {
							$project_date_orig = date("Y-m-d", strtotime("-1 day", strtotime($proj_date_temp)));
							$proj_date_temp = $project_date_orig;
							$project_day = date("D", strtotime($project_date_orig));
							array_push($temp_days_arr, $project_day);
						}
						$tmp_cnt=count($temp_days_arr);
						while ($tmp_cnt != 0) {
							$str .= "<td id='td_color' class='txt_align'>".$temp_days_arr[$tmp_cnt-1]."</td>";
							array_push($proj_day_arr, $temp_days_arr[$tmp_cnt-1]);
							$tmp_cnt--;
						}
						$check_day++;					
					}
					$project_day = date("D", strtotime("+$k day", strtotime($result[0]->start_date)));
					$str .= "<td id='td_color' class='txt_align'>".$project_day."</td>";
					$k++;	
					array_push($proj_day_arr, $project_day);
					if (count($proj_day_arr) == 7) {
						$c=0;
						while($c<count($proj_day_arr)){
							$str .= "<td id='td_color' class='txt_align'>".$proj_day_arr[$c]."</td>";
							$c++;
						}
						$counter=0;
						unset($proj_day_arr);
						$proj_day_arr = array();
					}
					if($k == $diff_days){
						while ( $project_day!='Sun') {
							$project_day = date("D", strtotime("+$k day", strtotime($result[0]->start_date)));
							array_push($proj_day_arr, $project_day);
							$str .= "<td id='td_color' class='txt_align'>".$project_day."</td>";
							$k++;
						}
						$c=0;
						while($c<count($proj_day_arr)){
							$str .= "<td id='td_color' class='txt_align'>".$proj_day_arr[$c]."</td>";
							$c++;
						}
					}			
				}
				$str .= "</tr></tr>";
				$i=0;	
				foreach ($tables as $key => $value2) {
					$row = (array)$value2;
					$str .= "<tr>
						<td bgcolor='#DCDCDC' class='txt_align'>".$row['days']."</td>";
						if ($row['task_type']=='Electrical') {
									$str .= "<td bgcolor='#AAD4FF' class='txt_align'>".$row['project_activity_id']."</td>";
						}else
							$str .= "<td bgcolor='#ABDB77' class='txt_align'>".$row['project_activity_id']."</td>";
						$str .= "<td class='txt_align'>".$estimated_hrs[$i]."</td>";
						$bell=0;
						foreach ($sum_of_actual_hrs as $key => $value) {
							if ($key == $row['project_activity_id']) {
									$str .= "<td class='txt_align'>".$value['total_time']."</td>";
									$bell=1;
									break;
							}
						}
						if ($bell == 0) {
							$str .= "<td class='txt_align'>"."-"."</td>";
						}
						$str .= "<td colspan='2' nowrap>".$row['title']."</td>";
						
						$temp_array =  array();//for estimated week
						$temp_array = $global_project_date_array;
						foreach ($proj_inspect_array as $key => $value) {
							if ($key == $row['project_activity_id']) {
								foreach ($value as $key_date => $value2) {
									$d=0;
									while ($d < count($temp_array)) {
										if ($temp_array[$d] == $key_date) {
												if ($value2['inspection'] == NULL)
													$temp_array[$d]=$value2['projected'];
												else
													$temp_array[$d]=$value2['inspection'];
											break;
										}
										$d++;
									}
								}
								break;
							}
						}
						$temp2_array =  array(); //for actual week
						$temp2_array = $global_project_date_array;
						foreach ($actual_hrs as $key3 => $value3) {
							if ($key3 == $row['project_activity_id']) {
								foreach ($value3 as $key2_date => $value4) {
									$b=0;
									while ($b < count($temp2_array)) {
										if ($temp2_array[$b] == $key2_date) {
													$temp2_array[$b]=$value4['work_time'];
											break;
										}
										$b++;
									}
								}
								break;
							}
						}
						$c=0;
						$emp_count=0;
						while ($c <= count($temp_array)) {
							if (($c+7)%7 == 0 && $c!=0) {
								$v=0;
								while ($v < 7) {
									if (strlen($temp2_array[($c-7)+$v])>5) {
										$str .= "<td id='td_color' class='txt_align'>".""."</td>";
									}
									else{
										$str .= "<td class='txt_align'>".$temp2_array[($c-7)+$v]."<div id='profile'>";
										$str .= "<div class='t_Heading'>
												<div  class='divCell'><b>Name</b></div>
												<div  class='divCell'><b>Hours</b></div>
											  </div>";
										$cn=0;
										$prev_date="";
										$next_date="";	
										
										while ($cn < count($emp_data)) {
											$next_date=$emp_data[$cn][$row['project_activity_id']]['work_date'];
											if (!empty($emp_data[$cn][$row['project_activity_id']])) {
													if ($prev_date != $next_date && $global_project_date_array[($c-7)+$v] == $emp_data[$cn][$row['project_activity_id']]['work_date']) {
														$str .= "<div class='divRow'>
																<div  class='divCell'>".$emp_data[$cn][$row['project_activity_id']]['emp_name']."</div>
																<div  class='divCell'>".$emp_data[$cn][$row['project_activity_id']]['hrs_worked']."</div>
															 </div>";
														$prev_date=$emp_data[$cn][$row['project_activity_id']]['work_date'];		
													}
													else if ($prev_date == $next_date) {
														$str .= "<div class='divRow'>
																 <div  class='divCell'>".$emp_data[$cn][$row['project_activity_id']]['emp_name']."</div>
																 <div  class='divCell'>".$emp_data[$cn][$row['project_activity_id']]['hrs_worked']."</div>
															 </div>";
													}
											}
											$cn++;		
										}
										$str .= "</div></td>";
										$emp_count++;
									}
									$v++;
								}
								if ($c == count($temp_array)) {
									break;
								}
							}
							if (strlen($temp_array[$c])>5) {
								$str .= "<td class='txt_align'>".""."</td>";
							}
							else
							{
								if ($row['task_type']=='Electrical') {
									$str .= "<td bgcolor='#AAD4FF' class='txt_align'>".$temp_array[$c]."</td>";
								}
								else if ($temp_array[$c] == 0) {
									$str .= "<td bgcolor='#FFFFCC' class='txt_align'>".$temp_array[$c]."</td>";
								}
								else if (is_numeric($temp_array[$c])) {
									$str .= "<td bgcolor='#ABDB77' class='txt_align'>".$temp_array[$c]."</td>";
								}
										
							}
								$c++;
						}
						
						$str .= "</tr>";
					$i++;	
				}
				$str .= "</table>";
			}
		}
		$params = array('left_menu' => $modules,'projects'=>$projects,'report'=>$str);
		return View::make('qc_reports.proj_activity_report', $params);	
	}
	public  function addOrdinalNumberSuffix($num) {
	    if (!in_array(($num % 100),array(11,12,13))){
	      switch ($num % 10) {
	        case 1:  return $num.'st';
	        case 2:  return $num.'nd';
	        case 3:  return $num.'rd';
		    }
	    }
		return $num.'th';
	}
	/*
	* contractProfitabilityReport
	*/
	public function contractProfitabilityReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getContractProfitRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'arr_regarding'=>$this->arr_regarding,'regardImplode'=>$data->regardImplode);
		return View::make('qc_reports.contract_profitability_report', $params);
	}
	public function getContractProfitRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		$results = new \StdClass;
		$results->page = $page;
		$limitQry = "";
		# set offset (for excel export offset will not apply)
		$limitOffset = '';
		if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}
		$results->totalItems = 0;
		$regardImplode = '';
		$results->regardImplode = '';
		$results->items = array();
		$SDate =  Input::get("SDate");
		$EDate =  Input::get("EDate");
		$regard = Input::get("regard");
		$c_num = Input::get("c_num");
		$c_num_end = Input::get("c_num_end");
		$querypart = "";
		if ($SDate!="" and $EDate!="") {
			$querypart .= " AND  gpg_job.created_on > '".date('Y-m-d',strtotime($SDate))." 00:00:00'
						    AND gpg_job.created_on < '".date('Y-m-d',strtotime($EDate))." 23:59:59'";	
		}
		elseif($SDate!="" and $EDate=="") {
			$querypart .= " AND  gpg_job.created_on > '".date('Y-m-d',strtotime($SDate))." 00:00:00'
						    AND gpg_job.created_on < '".date('Y-m-d',strtotime($SDate))." 23:59:59'";	
		}
		if(is_array($regard)){

		} if( $regard == "" || (isset($regard[0]) && $regard[0] == "")) {
		    $regardImplode = '';
			$querypart .= " AND task IN ('".implode("','",$this->arr_regarding)."')";
		} elseif(is_array($regard) and sizeof($regard)>0 and $regard[0]!=""){
		    $regardImplode = implode('~@@~', $regard);
			$querypart .= " AND task IN ('".implode("','", $regard)."')";
		}
			$contractArray = explode(",", $c_num);
		if($c_num!="" && $c_num_end!="" && count($contractArray) == 1) {
			$querypart .= " AND contract_number >= '".($c_num)."' AND contract_number <= '".($c_num_end)."'";
		}
		elseif($c_num!="" && count($contractArray) == 1)
		{
			$querypart .= " AND contract_number LIKE '".($c_num)."%'";
		}
		elseif($c_num!="" && count($contractArray) > 1)
		{
		   for($i = 0; $i < count($contractArray); $i++){
		        $in[]  = "'".trim($contractArray[$i])."'";
		    }
			$querypart .= " AND contract_number IN (".implode(',',$in).")";
		}
		$lcost = DB::select(DB::raw("SELECT
            sum(total_wage) AS labor_cost, SUBSTR(gpg_job.job_num,1,1) AS j_num,
            gpg_job.task,
            gpg_job.contract_number
            FROM gpg_timesheet_detail,
            gpg_job
            WHERE gpg_timesheet_detail.job_num = gpg_job.job_num
            ".$querypart."
            GROUP BY gpg_job.contract_number,gpg_job.task , 2"));
		foreach ($lcost as $key => $rowRec) {
		    $labor_cost_array[$rowRec->contract_number][$rowRec->j_num][$rowRec->task] = array(
		        'labor_cost' => $rowRec->labor_cost);
		}
		// material cost
		$mcost = DB::select(DB::raw("SELECT
		        SUM(gpg_job_cost.amount) AS material_cost,SUBSTR(gpg_job.job_num,1,1) AS j_num,
		        gpg_job.task,
		        gpg_job.contract_number
		        FROM gpg_job_cost,
		        gpg_job
		        WHERE gpg_job_cost.job_num = gpg_job.job_num
		        ".$querypart."
		        GROUP BY gpg_job.contract_number,gpg_job.task , 2"));
		foreach ($mcost as $key => $rowRec) {
		    $material_cost_array[$rowRec->contract_number][$rowRec->j_num][$rowRec->task] = array(
		        'material_cost' => $rowRec->material_cost);
		}
		// total invoiced cost
		$invoiced_amount = DB::select(DB::raw("SELECT
		        SUM(IFNULL(gpg_job_invoice_info.invoice_amount,0))-SUM(IFNULL(gpg_job_invoice_info.tax_amount,0)) AS invd_amnt_net,
		        SUBSTR(gpg_job.job_num,1,1) AS j_num,
		        gpg_job.task,
		        gpg_job.contract_number
		        FROM gpg_job_invoice_info,
		        gpg_job
		        WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id
		        ".$querypart."
		        GROUP BY gpg_job.contract_number,gpg_job.task"));
		foreach ($invoiced_amount as $key => $rowRec) {
		    $invd_amnt_net[$rowRec->contract_number][$rowRec->j_num][$rowRec->task] = array(
		        'invd_amnt_net' => $rowRec->invd_amnt_net);
		}
		$res = DB::select(DB::raw("SELECT
		  	gpg_job.contract_number,
		  	SUBSTR(gpg_job.job_num,1,1) AS job_start,
		  	gpg_job.task AS regarding,
		  	SUM(IFNULL(gpg_job.cost_to_dat,0)) AS cost_to_date
		  	FROM
		  	gpg_job
			WHERE 1
			".$querypart."
			GROUP BY gpg_job.contract_number,gpg_job.task, 2"));
		$arr_data = array();
		foreach ($res as $key => $value0) {
			$arr = (array)$value0;
		    $labor_cost     = @$labor_cost_array[$arr['contract_number']][$arr['job_start']][$arr['regarding']]['labor_cost'];
		    $material_cost  = @$material_cost_array[$arr['contract_number']][$arr['job_start']][$arr['regarding']]['material_cost'];
		    $total_invoiced_amount = @$invd_amnt_net[$arr['contract_number']][$arr['job_start']][$arr['regarding']]['invd_amnt_net'];
		    $profit = $total_invoiced_amount - $arr['cost_to_date'];
		    $arr_data[$arr['contract_number']][$arr['job_start']][$arr['regarding']] = array(
		        'regarding'         => $arr['regarding'],
		        'contract_number'   => $arr['contract_number'],
		        'inv_amnt'          => (!empty($total_invoiced_amount) ? $total_invoiced_amount : 0),
		        'material_cost'     => (!empty($material_cost) ? $material_cost : 0),
		        'cost_to_date'      => $arr['cost_to_date'],
		        'labor_cost'        => (!empty($labor_cost) ? $labor_cost : 0),
		        'profit'            => $profit,
		        'margin_percent'    => $total_invoiced_amount==0?0:(($profit / $total_invoiced_amount) * 100)
		    );
		}
		$results->regardImplode = $regardImplode;
		$results->totalItems = count($arr_data);
		$results->items = array_slice($arr_data,$start,$limit);
		return $results;
	}

	/*
	* engineKwPricing
	*/
	public function engineKwPricing(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getEngineKWPricingRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'arr_eng'=>$data->arr_eng,'kw_array'=>$data->kw_array);
		return View::make('qc_reports.engine_kw_pricing', $params);
	}
	public function getEngineKWPricingRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		$results = new \StdClass;
		$results->page = $page;
		$limitQry = "";
		# set offset (for excel export offset will not apply)
		$limitOffset = '';
		if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}
		$results->totalItems = 0;
		$results->kw_array = array();
		$results->arr_eng = array();
		$results->items = array();
		$range_start = Input::get("range_start");
		$range_end = Input::get("range_end");
		$interval = Input::get("interval");
		$start_date = Input::get("start_date");
		$end_date = Input::get("end_date");
		$engine_filter = Input::get("engine_filter");
		if(empty($start_date))
			$start_date = "2006-01-01";
		if(empty($end_date))
			$end_date = date("Y-m-d", strtotime("-1 month") ) ;
		if(empty($range_start))
			$range_start = 0;
		if(empty($range_end))
			$range_end = 3000;
		if(empty($interval))
			$interval = 500;
		$include_job_like_arr = array('PM','BO');
		$query_like_jobnum = " (";
		foreach($include_job_like_arr as $key => $value){
			$query_like_jobnum .= " gj.job_num like '".$value."%' or ";
		}
		$query_like_jobnum = substr($query_like_jobnum ,0, strlen($query_like_jobnum )-3)." ) ";
		$loop_count = ($range_end-$range_start) / ($interval);
		$inc = $range_start;
		$kw_array = array();
		$arr_eng = array();
		$str_gen_whr = "";
		$engine_filter = Input::get("engine_filter");
		switch($engine_filter){
			case "both":
				$str_gen_whr = " AND (eqp.make IS NOT NULL or eqp.make != '') AND (eqp.engMake IS NOT NULL or eqp.engMake != '') ";
			break;
			case "no_engine":
				$str_gen_whr = " AND (eqp.engMake IS NULL or eqp.engMake = '') ";
			break;
			case "no_generator":
				$str_gen_whr = " AND (eqp.make IS NULL or eqp.make = '') ";
			break;
		}

		$kw_result = DB::select(DB::raw("SELECT
			gcc.GPG_attach_contract_number,
			(SELECT SUM(gpg_job_cost.amount) FROM gpg_job_cost WHERE (gpg_job_cost.job_num = gj.job_num) GROUP BY gcc.GPG_attach_contract_number) AS mat_sum,
			(SELECT SUM(total_wage) FROM gpg_timesheet_detail WHERE gpg_timesheet_detail.job_num = gj.job_num AND gj.contract_number = gcc.GPG_attach_contract_number) AS lab_sum,
			(gj.contract_amount) AS sum_contract,
			(gj.cost_to_dat) AS sum_cost,
			CONCAT(IFNULL(eqp.make,''),'~',IFNULL(eqp.engMake,'')) AS engines,
			IFNULL(eqp.kw,0) AS gen_kw,
			gcc.job_num 
		FROM
			gpg_consum_contract gcc,
			gpg_job gj 
			,gpg_consum_contract_equipment eqp
		WHERE
			eqp.gpg_consum_contract_id = gcc.id AND
			date_format(gcc.created_on,'%Y-%m-%d') <= '".date('Y-m-d',strtotime($end_date))."' AND
			date_format(gcc.created_on,'%Y-%m-%d') >= '".date('Y-m-d',strtotime($start_date))."' AND
			gj.contract_number = gcc.GPG_attach_contract_number
			AND ".$query_like_jobnum."
			".$str_gen_whr."
			ORDER BY engines"));
		$arr_eng = array();
		$kw_result_array = array();
		foreach ($kw_result as $key => $value) {
			$kw_object = (array)$value;
			$kw_result_array[] = $kw_object;
		}
		foreach($kw_result_array as $kw_key => $kw_value){
			$arr_eng[$kw_value['engines']] = $kw_value['engines'];
		}
		for($loop = 0; $loop < $loop_count; $loop++){
			$end = $inc + $interval;
			if($loop!=0)
				$inc+=1;
			$kw_array[$loop]["range"] = $inc." - ".($end);
			$kw_array[$loop]["range_start"] = $inc;
			$kw_array[$loop]["range_end"] = $end;
			$kw_array[$loop]["kw"] = 0;
			foreach($arr_eng as $key => $value) {
				$kw_array[$loop]["engines"][$value] = array();
			}
			if($loop!=0)
					$inc-=1;
			$inc += $interval;		
		}
		foreach($kw_result_array as $kw_key => $kw_value)
		{
			for($loop = 0; $loop < sizeof($kw_array); $loop++)
			{
				if($kw_value['gen_kw'] <= $kw_array[$loop]["range_end"] && $kw_value['gen_kw'] >= $kw_array[$loop]["range_start"])
				{
					$kw_array[$loop]["kw"] = $kw_value['gen_kw'];
					@$kw_array[$loop]["engines"][$kw_value['engines']]["mat_sum"] += @$kw_value['mat_sum'];
					@$kw_array[$loop]["engines"][$kw_value['engines']]["lab_sum"] += @$kw_value['lab_sum'];
					@$kw_array[$loop]["engines"][$kw_value['engines']]["sum_cost"] += @$kw_value['sum_cost'];
					@$kw_array[$loop]["engines"][$kw_value['engines']]["sum_contract"] += @$kw_value['sum_contract'];
					@$kw_array[$loop]["engines"][$kw_value['engines']]["contract_num"][$kw_value['GPG_attach_contract_number']] += @$kw_value['GPG_attach_contract_number'];
					
					break;
				}
			}
		}
		$results->kw_array = $kw_array;
		$results->arr_eng = $arr_eng;
		return $results;
	}

	/*
	*excelEngineKWPricingExport
	*/
	public function excelEngineKWPricingExport(){
		set_time_limit(0);
		Excel::create('EngineKWPricingReportExport', function($excel) {
		    $excel->sheet('EngineKWPricingReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getEngineKWPricingRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'arr_eng'=>$data->arr_eng,'kw_array'=>$data->kw_array);
		    $sheet->loadView('qc_reports.excelEngineKWPricingExport',$params);
		    });
		})->export('xls');
	}

	/*
	* contractProfitablityExport
	*/
	public function contractProfitablityExport(){
		set_time_limit(0);
		Excel::create('ContractProfitReportExport', function($excel) {
		    $excel->sheet('ContractProfitReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		   	$SDate =  Input::get("SDate");
			$qpart =  Input::get("qpart");
			$EDate =  Input::get("EDate");
			$regard = Input::get("regard");
			$regard = explode('~@@~', $regard);
			$c_num = Input::get("c_num");
			$c_num_end = Input::get("c_num_end");
			$export_type = Input::get("export_type");
			$arr_data = array();
			if($export_type=="")
			    $export_type=1;
			$querypart = "";
			if ($SDate!="" and $EDate!=""){
			    $querypart .= " AND  gpg_job.created_on > '".date('Y-m-d',strtotime($SDate))." 00:00:00'
							    AND gpg_job.created_on < '".date('Y-m-d',strtotime($EDate))." 23:59:59'";
			}
			elseif($SDate!="" and $EDate==""){
			    $querypart .= " AND  gpg_job.created_on > '".date('Y-m-d',strtotime($SDate))." 00:00:00'
							    AND gpg_job.created_on < '".date('Y-m-d',strtotime($SDate))." 23:59:59'";
			}
			if($regard=="" || empty($regard) || $regard[0] == ""){
			    $querypart .= " AND task IN ('".implode("','",$this->arr_regarding)."')";
			}
			elseif(is_array($regard) and sizeof($regard)>0 and $regard[0]!=""){
			    $querypart .= " AND task IN ('".implode("','", $regard)."')";
			}
			$contractArray = explode(",", $c_num);
			if($c_num!="" && $c_num_end!="" && count($contractArray) == 1){
			    $querypart .= " AND contract_number >= '".($c_num)."' AND contract_number <= '".($c_num_end)."'";
			}
			elseif($c_num!="" && count($contractArray) == 1){
			    $querypart .= " AND contract_number LIKE '".($c_num)."%'";
			}
			elseif($c_num!="" && count($contractArray) > 1){
			    for($i = 0; $i < count($contractArray); $i++){
			        $in[]  = "'".trim($contractArray[$i])."'";
			    }
			    $querypart .= " AND contract_number IN (".implode(',',$in).")";
			}
			if($export_type !=3){
			    $lcost = DB::select(DB::raw("SELECT
		            sum(total_wage) AS labor_cost, SUBSTR(gpg_job.job_num,1,1) AS j_num,
			        gpg_job.task,
			        gpg_job.contract_number
			        FROM gpg_timesheet_detail,
			        gpg_job
			        WHERE gpg_timesheet_detail.job_num = gpg_job.job_num
			        ".$querypart."
			        GROUP BY gpg_job.contract_number,gpg_job.task , 2"));
			    	foreach ($lcost as $key => $rowRec) {
				        $labor_cost_array[$rowRec->contract_number][$rowRec->j_num][$rowRec->task] = array(
				        'labor_cost' => $rowRec->labor_cost);
			    	}
			// material cost
			    $mcost = DB::select(DB::raw("SELECT
			           SUM(gpg_job_cost.amount) AS material_cost,SUBSTR(gpg_job.job_num,1,1) AS j_num,
			           gpg_job.task,
			           gpg_job.contract_number
			           FROM gpg_job_cost,
			           gpg_job
			           WHERE gpg_job_cost.job_num = gpg_job.job_num
			           ".$querypart."
			           GROUP BY gpg_job.contract_number,gpg_job.task , 2"));
					foreach ($mcost as $key => $rowRec) {
					       $material_cost_array[$rowRec->contract_number][$rowRec->j_num][$rowRec->task] = array(
					       'material_cost' => $rowRec->material_cost);
					}
			// total invoiced cost
			    $invoiced_amount = DB::select(DB::raw("SELECT
			            SUM(IFNULL(gpg_job_invoice_info.invoice_amount,0))-SUM(IFNULL(gpg_job_invoice_info.tax_amount,0)) AS invd_amnt_net,
			            SUBSTR(gpg_job.job_num,1,1) AS j_num,
			            gpg_job.task,
			            gpg_job.contract_number
			            FROM gpg_job_invoice_info,
			            gpg_job
			            WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id
			            ".$querypart."
			            GROUP BY gpg_job.contract_number,gpg_job.task"));
					    foreach ($invoiced_amount as $key => $rowRec) {
					        $invd_amnt_net[$rowRec->contract_number][$rowRec->j_num][$rowRec->task] = array(
					            'invd_amnt_net' => $rowRec->invd_amnt_net);
					    }
			$res = DB::select(DB::raw("SELECT
			  		gpg_job.contract_number,
			  		SUBSTR(gpg_job.job_num,1,1) AS job_start,
			  		gpg_job.task AS regarding,
			  		SUM(IFNULL(gpg_job.cost_to_dat,0)) AS cost_to_date
					FROM
					gpg_job
					WHERE 1
					".$querypart."
					GROUP BY gpg_job.contract_number,gpg_job.task, 2"));
			    	foreach ($res as $key => $value1) {
				    	$arr = (array)$value1;
				        $labor_cost     = @$labor_cost_array[$arr['contract_number']][$arr['job_start']][$arr['regarding']]['labor_cost'];
				        $material_cost  = @$material_cost_array[$arr['contract_number']][$arr['job_start']][$arr['regarding']]['material_cost'];
				        $total_invoiced_amount = @$invd_amnt_net[$arr['contract_number']][$arr['job_start']][$arr['regarding']]['invd_amnt_net'];
				        $profit = $total_invoiced_amount - $arr['cost_to_date'];
				        $arr_data[$arr['contract_number']][$arr['job_start']][$arr['regarding']] = array(
				            'regarding'         => $arr['regarding'],
				            'contract_number'   => $arr['contract_number'],
				            'inv_amnt'          => (!empty($total_invoiced_amount) ? $total_invoiced_amount : 0),
				            'material_cost'     => (!empty($material_cost) ? $material_cost : 0),
				            'cost_to_date'      => $arr['cost_to_date'],
				            'labor_cost'        => (!empty($labor_cost) ? $labor_cost : 0),
				            'profit'            => $profit,
				            'margin_percent'    => $total_invoiced_amount==0?0:(($profit / $total_invoiced_amount) * 100)
				        );
				    }
			}
			$params = array('arr_data'=>$arr_data);
		    $sheet->loadView('qc_reports.contractProfitablityExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelMissingJobsInfoRepExport
	*/
	public function excelMissingJobsInfoRepExport(){
		set_time_limit(0);
		Excel::create('MissingJobsInfoReportExport', function($excel) {
		    $excel->sheet('MissingJobsInfoReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getMissingJobInfoRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		    $sheet->loadView('qc_reports.excelMissingJobsInfoRepExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelCustContactInfoRepExport
	*/
	public function excelCustContactInfoRepExport(){
		set_time_limit(0);
		Excel::create('CustContactInfoReportExport', function($excel) {
		    $excel->sheet('CustContactInfoReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getCustomerContactInfoRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		    $sheet->loadView('qc_reports.excelCustContactInfoRepExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelEmpPayableAmtRepExport
	*/
	public function excelEmpPayableAmtRepExport(){
		set_time_limit(0);
		Excel::create('EmpPayableAmtReportExport', function($excel) {
		    $excel->sheet('EmpPayableAmtReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $SDate = Input::get("SDate");
			$EDate = Input::get("EDate");
			$type = Input::get("Etype");
			$Filter = Input::get("Filter");
			$employee = Input::get("employee"); 
			$SJobNumber = strtoupper(Input::get("SJobNumber"));
			$EJobNumber = strtoupper(Input::get("EJobNumber"));
			$DSQL = "";
			$DQ2 = " ORDER BY employee_name, td.job_num";
			if ($SDate!="" || $EDate!="") {
				if ($SDate!="" && $EDate =="") {
				  $DSQL.= " AND DATE_FORMAT(date,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
				} elseif ($SDate == "" && $EDate != "") {
				  $DSQL.= " AND t.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
				} elseif ($SDate != "" && $EDate != "") {
				  $DSQL.= " AND (t.date >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
							AND t.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
				}
			}
			if ($employee!="" || (int)$employee > 0) {	
				$DSQL.= " AND e.id  = '".$employee."'"; 
			}
			if ($SJobNumber!="" and $EJobNumber!=""){
				$DSQL .= " AND j.job_num >= '".$SJobNumber."' AND j.job_num <= '".$EJobNumber."' ";
			}
			elseif ($SJobNumber!=""){ 
			 	$DSQL .= " AND j.job_num = '".$SJobNumber."'";
			}
			$query2 = "SELECT DISTINCT
			  t.GPG_employee_Id
			  , e.name AS employee_name
			  ,(SELECT name FROM gpg_customer WHERE gpg_customer.id = j.GPG_customer_id) as cus_name_detail
			  , r.id              AS job_rate_id
			  , r.job_number
			  , td.job_num
			  , r.pw_reg
			  , r.start_date
			  , r.end_date
			  , SUM(td.time_diff_dec) AS total_prevailing_hours
			  , e.GPG_employee_type_id AS time_employee_type_id
			  , t.date            AS timesheet_date
			  ,td.labor_rate
			FROM gpg_job_rates r
			  , gpg_timesheet t
			  , gpg_timesheet_detail td
			  , gpg_employee e
			  , gpg_job j
			WHERE r.job_number = IF(LENGTH(r.job_number) < 3,SUBSTRING(td.job_num,1,LENGTH(r.job_number)),td.job_num)
			    AND IFNULL(r.contract_number,'') = IF(LENGTH(r.job_number) < 3,(j.contract_number),'')
			    AND r.gpg_task_type = td.gpg_task_type
			    AND r.gpg_county_id = td.gpg_county_id
			    AND td.GPG_timesheet_id = t.id
			    AND e.id = t.GPG_employee_Id
				AND j.job_num = td.job_num
			    AND e.GPG_employee_type_id = r.GPG_employee_type_id
				AND e.GPG_employee_type_id != 3
			    AND t.date >= r.start_date
			    AND t.date <= r.end_date
			    AND td.pw_flag = 1
				AND td.GPG_timetype_id != 1
				$DSQL
			GROUP BY t.GPG_employee_Id, td.job_num,time_employee_type_id, td.gpg_county_id, td.labor_rate, td.gpg_task_type,start_date,end_date" ;
			$result = DB::select(DB::raw("".($Filter=="jobCheck"?"select a.* from gpg_job_cost a left join gpg_job b on (a.job_num = b.job_num) where ifnull(b.job_num,'') = ''":$query2)." $DSQL $DQ2")); 
			$data_arr = array();
			foreach ($result as $key => $value) {
				$data_arr[] = (array)$value;
			}
			$params = array('query_data'=>$data_arr,'SDate'=>$SDate,'EDate'=>$EDate,'Etype'=>$type,'employee'=>$employee,'SJobNumber'=>$SJobNumber,'EJobNumber'=>$EJobNumber);
		    $sheet->loadView('qc_reports.excelEmpPayableAmtRepExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelCustContactDetailRepExport
	*/
	public function excelCustContactDetailRepExport(){
		set_time_limit(0);
		Excel::create('CustContactDetailReportExport', function($excel) {
		    $excel->sheet('CustContactDetailReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getCustContactDetailRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'customer_flag'=>$data->customer_flag);
		    $sheet->loadView('qc_reports.excelCustContactDetailRepExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelPartsCostsExport
	*/
	public function excelPartsCostsExport(){
		set_time_limit(0);
		Excel::create('PartsCostsReportExport', function($excel) {
		    $excel->sheet('PartsCostsReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getPartsCostsRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		    $sheet->loadView('qc_reports.excelPartsCostsExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelBillableHoursExport
	*/
	public function excelBillableHoursExport(){
		set_time_limit(0);
		Excel::create('BillableHoursReportExport', function($excel) {
		    $excel->sheet('BillableHoursReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getBillableHrsRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'billable_records_next'=>$data->billable_records_next,'total_record'=>$data->total_record,'billable_records_prev'=>$data->billable_records_prev,'non_billable_next'=>$data->non_billable_next,'non_billable_prev'=>$data->non_billable_prev);
		    $sheet->loadView('qc_reports.excelBillableHoursExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelCustJobDetailRepExport
	*/
	public function excelCustJobDetailRepExport(){
		set_time_limit(0);
		Excel::create('CustomerDetailJobReportExport', function($excel) {
		    $excel->sheet('CustomerDetailJobReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getCustDetailJobRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		    $sheet->loadView('qc_reports.excelCustJobDetailRepExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelProFixtureUsageExport
	*/
	public function excelProFixtureUsageExport(){
		set_time_limit(0);
		Excel::create('ProFixtureReportExport', function($excel) {
		    $excel->sheet('ProFixtureReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getProFixtUsageRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		    $sheet->loadView('qc_reports.excelProFixtureUsageExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelCustomerJobRepExport
	*/
	public function excelCustomerJobRepExport(){
		set_time_limit(0);
		Excel::create('CustomerJobReportExport', function($excel) {
		    $excel->sheet('CustomerJobReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getCustJobRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'cusData'=>$data->cusData,'elecJobTypeArray'=>$this->elecJobTypeArray,'arr_ar_ap_report'=>$data->arr_ar_ap_report);
		    $sheet->loadView('qc_reports.excelCustomerJobRepExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelProjMarjinRepExport
	*/
	public function excelProjMarjinRepExport(){
		set_time_limit(0);
		Excel::create('ProjMarginReportExport', function($excel) {
		    $excel->sheet('ProjMarginReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getProjMarginRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		    $sheet->loadView('qc_reports.excelProjMarjinRepExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelWrongPrevJobExport
	*/
	public function excelWrongPrevJobExport(){
		set_time_limit(0);
		Excel::create('WrongPrevJobReportExport', function($excel) {
		    $excel->sheet('WrongPrevJobReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getWrongPrevJobRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		    $sheet->loadView('qc_reports.excelWrongPrevJobExport',$params);
		    });
		})->export('xls');
	}

	/*
	*excelSalesTaxExcpExport
	*/
	public function excelSalesTaxExcpExport(){
		set_time_limit(0);
		Excel::create('SalesTaxExcpReportExport', function($excel) {
		    $excel->sheet('SalesTaxExcpReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getSalesTaxExcpRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'Toal_tax'=>$data->Toal_tax,'Total_mat_cost'=>$data->Total_mat_cost);
		    $sheet->loadView('qc_reports.excelSalesTaxExcpExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelDupServJobExport
	*/
	public function excelDupServJobExport(){
		set_time_limit(0);
		Excel::create('DupServJobReportExport', function($excel) {
		    $excel->sheet('DupServJobReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getDupServJobRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);	
		    $sheet->loadView('qc_reports.excelDupServJobExport',$params);
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
