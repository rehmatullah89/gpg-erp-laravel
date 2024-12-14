<?php

class WagesController extends \BaseController {


	Protected $pw_wages_rates_type = array( '1' => "basic hourly rate", '2' => "health and welfare", '3' => "pension", '4' => "vacations", '5' => "training", '6' => "other payments");
	Protected $job_type_array = array(0 =>'PM', 1 => 'QT', 2 => 'TC');
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$task_types = array();
		$gpg_task_type = DB::table('gpg_task_types')
	  		->select('gpg_task_types.task_type','gpg_task_types.id')
            ->get();
        foreach ($gpg_task_type as $key => $value) {
            	$task_types[$value->id] = $value->task_type;
        }
        $emp_types = array();
        $etype =  DB::table('gpg_employee_type')
            ->select('gpg_employee_type.*')
            ->get();
        foreach ($etype as $key => $value) {
            		$emp_types[$value->type_id] = $value->type;
        }    	
        $params = array('left_menu' => $modules, 'query_data'=>$query_data , 'task_types'=>$task_types, 'emp_types'=> $emp_types);
 		return View::make('wages.index', $params);
	}

	public function getByPage($page = 1, $limit = 100)
	{
	  $results = new \StdClass;
	  $results->page = $page;
	  $results->limit = $limit;
	  $results->totalItems = 0;
	  $results->items = array();
	  $items_arr = array();
      $start = $limit * ($page - 1);
	 
	  $query_count = DB::select( DB::raw('SELECT  count(gjr.id) as total_count FROM gpg_job_rates gjr LEFT JOIN gpg_job gj 
													ON gjr.job_number = gj.job_num 
								LEFT JOIN gpg_customer gc
													ON gj.GPG_customer_id = gc.id
								LEFT JOIN gpg_county gct
    												ON gjr.gpg_county_id = gct.id 
								LEFT JOIN gpg_employee_type gemp
													ON gjr.GPG_employee_type_id = gemp.type_id WHERE  gjr.GPG_employee_type_id <> 0 '));

	  $results->totalItems = $query_count[0]->total_count;
	  $query_d = DB::select( DB::raw("SELECT   gjr.* , CONCAT(gc.name,'#~~#',gj.location) AS cus_loc 
													, gemp.type   , IFNULL(gct.county_name,'ALL') AS County_Name
								FROM gpg_job_rates gjr LEFT JOIN gpg_job gj 
													ON gjr.job_number = gj.job_num 
								LEFT JOIN gpg_county gct
    												ON gjr.gpg_county_id = gct.id 
								LEFT JOIN gpg_customer gc
													ON gj.GPG_customer_id = gc.id 
								LEFT JOIN gpg_employee_type gemp
													ON gjr.GPG_employee_type_id = gemp.type_id WHERE  gjr.GPG_employee_type_id <> 0 LIMIT $start, $limit"));
	  foreach ($query_d as $key => $value) {
	  	foreach ($value as $key1 => $value1) {
	  		$items_arr[$key1] = $value1;
	  		if ($key1 == 'job_number') {
	  			if (!empty($value1)) {
	  				$customer_info = DB::table('gpg_job')
			      		->select('gpg_customer.name','location')
			            ->join('gpg_customer', 'gpg_job.GPG_customer_id', '=', 'gpg_customer.id')
			            ->where('job_num', '=', $value1)
  				        ->get();
  				        if (isset($customer_info[0])) {
		 				    $items_arr['customer_name']	= $customer_info[0]->name; 
			  				$items_arr['customer_loc']	= $customer_info[0]->location; 				        	
  				        }else
  				        {
			  				$items_arr['customer_name']	= '-';
			  				$items_arr['customer_loc']	= '-';
	  					}
	 	 		}
	  		}
	  	}
		  	$results->items[] = $items_arr;
	  }
	  //$results->items = $query;
	  return $results;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function search()
	{
		if (empty($_POST['SDate']) && empty($_POST['EDate']) && empty($_POST['Filter']) && empty($_POST['FVal']))
	  		return Redirect::to('wages');
	  	$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getBySearch($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$task_types = array();
		$gpg_task_type = DB::table('gpg_task_types')
	  		->select('gpg_task_types.task_type','gpg_task_types.id')
            ->get();
        foreach ($gpg_task_type as $key => $value) {
            	$task_types[$value->id] = $value->task_type;
        }
        $emp_types = array();
        $etype =  DB::table('gpg_employee_type')
            ->select('gpg_employee_type.*')
            ->get();
        foreach ($etype as $key => $value) {
            		$emp_types[$value->type_id] = $value->type;
        }    	
        $params = array('left_menu' => $modules, 'query_data'=>$query_data , 'task_types'=>$task_types, 'emp_types'=> $emp_types);
 		return View::make('wages.index', $params);
	}

	public function getBySearch($page = 1, $limit = 100)
	{
	  $results = new \StdClass;
	  $results->page = $page;
	  $results->limit = $limit;
	  $results->totalItems = 0;
	  $results->items = array();
	  $items_arr = array();
      $start = $limit * ($page - 1);
      $Filter = "";
	  $FVal = "";
	  $SDate = "";
	  $EDate = "";
      
        if (isset($_POST['SDate']))
	        $SDate = $_POST['SDate'];
	    if (isset($_POST['EDate']))
			$EDate = $_POST['EDate'];
		if (isset($_POST['Filter']))
			$Filter = $_POST['Filter'];
		if (isset($_POST['FVal']))
			$FVal = $_POST['FVal'];
		$DSQL = "";
		$strFilter="";
		if (!empty($Filter) && !empty($FVal)) {
			if($Filter=="name")
				$strFilter = " AND gc.name like '%".$FVal."%'";
			if($Filter=="emp_type")
				$strFilter = " AND gemp.type like '%".$FVal."%'";
			if($Filter=="job_number")
				$strFilter = " AND gjr.job_number like '%".$FVal."%'";
			if($Filter=="contract_number")
				$strFilter = " AND gjr.contract_number like '%".$FVal."%'";
			if($Filter=="job_regarding")
				$strFilter = " AND gjr.gpg_job_regarding like '%".$FVal."%'";
			if($Filter=="county_name")
				$strFilter = " AND gct.county_name like '%".$FVal."%'";
		}
		if (!empty($SDate) || !empty($EDate)) {

			  $DSQL.= " AND ";
		  
		    if ($SDate!="" && $EDate =="") {
			  $DSQL.= " DATE_FORMAT(gjr.start_date,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."' Order By gjr.start_date" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " gjr.end_date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."' Order By gjr.start_date" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " (gjr.start_date >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
			            AND gjr.end_date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."') Order By gjr.start_date" ; 
			}
		}
		if (empty($strFilter) && empty($SDate)) {
			$strFilter = 	Session::get('strFilter');
			$DSQL = 	Session::get('DSQL');
		}else{
			Session::put('strFilter', $strFilter);	
			Session::put('DSQL', $DSQL);	
			//echo $strFilter."<br/>".$DSQL;
		}
	    $query_count = DB::select( DB::raw("SELECT  count(gjr.id) as total_count FROM gpg_job_rates gjr LEFT JOIN gpg_job gj 
													ON gjr.job_number = gj.job_num 
								LEFT JOIN gpg_customer gc
													ON gj.GPG_customer_id = gc.id
								LEFT JOIN gpg_county gct
    												ON gjr.gpg_county_id = gct.id 
								LEFT JOIN gpg_employee_type gemp
													ON gjr.GPG_employee_type_id = gemp.type_id WHERE  gjr.GPG_employee_type_id <> 0 $strFilter $DSQL"));

	  $results->totalItems = $query_count[0]->total_count;
	  $query_d = DB::select( DB::raw("SELECT   gjr.* , CONCAT(gc.name,'#~~#',gj.location) AS cus_loc 
													, gemp.type   , IFNULL(gct.county_name,'ALL') AS County_Name
								FROM gpg_job_rates gjr LEFT JOIN gpg_job gj 
													ON gjr.job_number = gj.job_num 
								LEFT JOIN gpg_county gct
    												ON gjr.gpg_county_id = gct.id 
								LEFT JOIN gpg_customer gc
													ON gj.GPG_customer_id = gc.id 
								LEFT JOIN gpg_employee_type gemp
													ON gjr.GPG_employee_type_id = gemp.type_id WHERE  gjr.GPG_employee_type_id <> 0 $strFilter $DSQL LIMIT $start, $limit"));
	  foreach ($query_d as $key => $value) {
	  	foreach ($value as $key1 => $value1) {
	  		$items_arr[$key1] = $value1;
	  		if ($key1 == 'job_number') {
	  			if (!empty($value1)) {
	  				$customer_info = DB::table('gpg_job')
			      		->select('gpg_customer.name','location')
			            ->join('gpg_customer', 'gpg_job.GPG_customer_id', '=', 'gpg_customer.id')
			            ->where('job_num', '=', $value1)
  				        ->get();
  				        if (isset($customer_info[0])) {
		 				    $items_arr['customer_name']	= $customer_info[0]->name; 
			  				$items_arr['customer_loc']	= $customer_info[0]->location; 				        	
  				        }else
  				        {
			  				$items_arr['customer_name']	= '-';
			  				$items_arr['customer_loc']	= '-';
	  					}
	 	 		}else
  				        {
			  				$items_arr['customer_name']	= '-';
			  				$items_arr['customer_loc']	= '-';
	  					}
	  		}
	  	}
		  	$results->items[] = $items_arr;
	  }
	  //$results->items = $query;
	  return $results;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$modules = Generic::modules();
		$emp_types =  DB::table('gpg_employee_type')
            ->select('*')
            ->get();
        $county_names = DB::table('gpg_county')
		  		->select('*')
	            ->get();
	    $task_types = DB::table('gpg_task_types')
	  		->select('gpg_task_types.task_type','gpg_task_types.id')
            ->get();            

		$params = array('left_menu' => $modules,'county_types'=>$county_names,'task_types'=>$task_types, 'emp_types'=> $emp_types,'pw_wages_rates_type'=> $this->pw_wages_rates_type,'job_type_array'=> $this->job_type_array, 'insert_status_msg'=>0);
		return View::make('wages.create', $params);
	}
	/**
	 * Ajax call for testContractNumber
	 *
	 * @return Response
	 */
	public function testContractNumber()
	{
	   $job_nums_opts = "<option>ALL</option>";		
	   $regarding_opts = "<option>ALL</option>";		
       $term = Input::get('contract_number');
	   $search =  DB::table('gpg_job')
	  				->select('contract_number')
	  				->where('contract_number', '=', $term)
	  				->distinct()
            		->get();
        $job_nums_arr = array();
        $job_tasks_arr = array();
        if (!empty($search)) {
            $job_nums = DB::select( DB::raw("SELECT job_num,task FROM gpg_job WHERE contract_number ='".$term."' ORDER BY job_num ASC"));
           
            foreach ($job_nums as $key => $value) {
            	$job_nums_arr[]  = $value->job_num;
            	$job_tasks_arr[]  = $value->task;
            }
            $regardings = DB::select( DB::raw("SELECT DISTINCT IFNULL(task,'') as task FROM gpg_job WHERE contract_number ='".$term."' ORDER BY task ASC"));
            foreach ($regardings as $key1 => $value1) {
            	$regarding_opts .= "<option value='".$value1->task."'>".$value1->task."</option>" ;
            }
   		}    		

    	if (empty($search))
			return $finalArray = array('search_result'=>0);
		else
			return $finalArray = array('job_nums_arr' => $job_nums_arr,'job_tasks_arr'=>$job_tasks_arr, 'regarding_opts' => $regarding_opts, 'search_result'=>1);;
	}

	/*
	* /////////////////////////////
	* Validate Job number on insert
	* /////////////////////////////
	*/
	public function validateJobNumber(){
		$term = Input::get('job_num');
        $search = DB::select( DB::raw("select job_num from gpg_job where job_num='".$term."'"));
        if (empty($search))
	        return 0;
	    else
    	    return 1;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$status_msg = 0;
		if (isset($_POST['JobNumber'])) {
			// saving data for first table job by job number
			if (empty($_POST['count_job_records']))
				$_POST['count_job_records'] = 1;

			for ($i=0; $i < $_POST['count_job_records']; $i++) { 
				
				DB::table('gpg_job_rates')
		     		->insert(array('GPG_employee_type_id' =>$_POST['etype_'.$i.''], 'gpg_county_id'=>$_POST['countyName_'.$i.''],'job_number'=>$_POST['JobNumber'],'pw_reg'=>$_POST['pwpay_'.$i.''],'pw_overtime'=>$_POST['pwovertime_'.$i.''],'pw_double'=>$_POST['pwdouble_'.$i.''],'start_date'=>$_POST['SDate_'.$i.''],'end_date'=>$_POST['EDate_'.$i.''],'created_on'=>date("Y-m-d H:i:s"),'modified_on'=>date("Y-m-d H:i:s"),'status'=>$_POST['status_'.$i.''],'wage_type'=>$_POST['wageType_'.$i.''],'gpg_task_type'=>$_POST['taskType_'.$i.'']));
				$max_id = DB::table('gpg_job_rates')->max('id');

				for ($j=1; $j <= 6 ; $j++) { 
					DB::table('gpg_job_rates_breakup')
		     		->insert(array('job_rates_id' =>$max_id, 'pw_wages_rate_type'=>$j,'rate'=>$_POST['rateBreakup_'.$i."_".$j.''],'created_on'=>date("Y-m-d H:i:s"),'modified_on'=>date("Y-m-d H:i:s")));
				}
			}
			$status_msg = 1;
		}
		else if (isset($_POST['ContractNumber']) && $_POST['count_records']>0) {
			// saving data for second table by job type by contract number
			for ($k=0; $k < $_POST['count_records']; $k++) { 

				DB::table('gpg_job_rates')
		     		->insert(array('GPG_employee_type_id' =>$_POST['emptype_'.$k.''], 'gpg_county_id'=>$_POST['cntyName_'.$k.''],'job_number'=>$_POST['jobType_'.$k.''],'pw_reg'=>$_POST['regPw_'.$k.''],'pw_overtime'=>$_POST['overtimePW_'.$k.''],'pw_double'=>$_POST['DoublePW_'.$k.''],'start_date'=>$_POST['S_Date_'.$k.''],'end_date'=>$_POST['E_Date_'.$k.''],'created_on'=>date("Y-m-d H:i:s"),'modified_on'=>date("Y-m-d H:i:s"),'status'=>$_POST['Status_'.$k.''],'wage_type'=>$_POST['WageTypes_'.$k.''],'gpg_task_type'=>$_POST['taskTypes_'.$k.''], 'gpg_job_regarding'=>($_POST['contractTypeRegarding_'.$k.''] == 'ALL') ? '~~ALL': $_POST['contractTypeRegarding_'.$k.''], 'prevailing_hours'=>$_POST['pwhours_contract_taskstype_'.$k.'']));

		     	$max_id = DB::table('gpg_job_rates')->max('id');	
				for ($L=1; $L <= 6 ; $L++) { 
					DB::table('gpg_job_rates_breakup')
		     		->insert(array('job_rates_id' =>$max_id, 'pw_wages_rate_type'=>$k,'rate'=>$_POST['RTBreakup_'.$k."_".$L.''],'created_on'=>date("Y-m-d H:i:s"),'modified_on'=>date("Y-m-d H:i:s")));
				}
			}

			if (!empty($_POST['count_cj_records'])) {
				// saving data for last table for job number by contract number
				for ($m=0; $m < $_POST['count_cj_records']+1; $m++) { 
					
					DB::table('gpg_job_rates')
		     		->insert(array('GPG_employee_type_id' =>$_POST['typeOfEmp_'.$m.''], 'gpg_county_id'=>$_POST['nameOfCounty_'.$m.''],'job_number'=>$_POST['numberOfJob_'.$m.''],'pw_reg'=>$_POST['regOFPw_'.$m.''],'pw_overtime'=>$_POST['overPWTime_'.$m.''],'pw_double'=>$_POST['DoublePWTime_'.$m.''],'start_date'=>$_POST['SSDate_'.$m.''],'end_date'=>$_POST['EEDate_'.$m.''],'created_on'=>date("Y-m-d H:i:s"),'modified_on'=>date("Y-m-d H:i:s"),'status'=>$_POST['statusByJobNum_'.$m.''],'wage_type'=>$_POST['typesofWages_'.$m.''],'gpg_task_type'=>$_POST['typesOfTask_'.$m.''], 'gpg_job_regarding'=>($_POST['regardingTask_'.$m.''] == 'ALL') ? '~~ALL': $_POST['regardingTask_'.$m.''], 'prevailing_hours'=>$_POST['ContractTaskTypePwHours_'.$m.'']));

		     		$max_id = DB::table('gpg_job_rates')->max('id');
					for ($n=1; $n <= 6 ; $n++) { 
						DB::table('gpg_job_rates_breakup')
				     		->insert(array('job_rates_id' =>$max_id, 'pw_wages_rate_type'=>$n,'rate'=>$_POST['rtBreakUps_'.$m."_".$n.''],'created_on'=>date("Y-m-d H:i:s"),'modified_on'=>date("Y-m-d H:i:s")));
					}
				}
			}
			$status_msg = 1;
		}
		// Ending method by sending success msg
		$modules = Generic::modules();
		$emp_types =  DB::table('gpg_employee_type')
            ->select('*')
            ->get();
        $county_names = DB::table('gpg_county')
		  		->select('*')
	            ->get();
	    $task_types = DB::table('gpg_task_types')
	  		->select('gpg_task_types.task_type','gpg_task_types.id')
            ->get();            

		$params = array('left_menu' => $modules,'county_types'=>$county_names,'task_types'=>$task_types, 'emp_types'=> $emp_types,'pw_wages_rates_type'=> $this->pw_wages_rates_type,'job_type_array'=> $this->job_type_array, 'insert_status_msg'=>$status_msg);
		return View::make('wages.create', $params);
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
	 * gets Job NumberAutocomplete specified resource 
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getJobNumberAutocomplete()
	{
		$term = Input::get('JobNumber');
        $job_numbers = array();
        $search = DB::select( DB::raw("select job_num from gpg_job where job_num like '".$term."%' limit 0,15"));
        foreach($search as $results => $job_num){
            $job_numbers[] = array('id'=>$job_num->job_num, 'label'=>$job_num->job_num, 'value'=>$job_num->job_num);
        }
        return Response::json($job_numbers);
	}

	/**
	 * gets Job getContractNumberAutocomplete specified resource 
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getContractNumberAutocomplete()
	{
		$term = Input::get('ContractNumber');
        $contract_numbers = array();
        $search = DB::select( DB::raw("select DISTINCT(contract_number) as contract_number from gpg_job where contract_number like '".$term."%'  ORDER BY contract_number ASC limit 0,15"));
        foreach($search as $keys => $cnum){
            $contract_numbers[] = array('id'=>$cnum->contract_number, 'label'=>$cnum->contract_number, 'value'=>$cnum->contract_number);
        }
        return Response::json($contract_numbers);
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$emps_array = array(''=>'Select Type');
		$county_array = array(''=>'ALL');
		$task_tapes_array = array(''=>'ALL');
		$modules = Generic::modules();
		$wages_info = DB::table('gpg_job_rates')
			      		->select('*')
			            ->where('id', '=', $id)
  				        ->get();
  		$emp_types =  DB::table('gpg_employee_type')
            ->select('gpg_employee_type.*')
            ->get();
        foreach ($emp_types as $key => $value) {
        	$emps_array[$value->type_id] = $value->type;    
        } 
        $county_types =  DB::table('gpg_county')
            ->select('*')
            ->get();
       foreach ($county_types as $key => $value) {
            $county_array[$value->id] = $value->county_name; 
        }    
        $task_types = DB::table('gpg_task_types')
	  		->select('gpg_task_types.task_type','gpg_task_types.id')
            ->get();
        foreach ($task_types as $key => $value) {
        	$task_tapes_array[$value->id] = $value->task_type; 
        }
        $job_rates = DB::table('gpg_job_rates_breakup')
	  		->select('pw_wages_rate_type','rate')
	  		->where('job_rates_id','=', $id)
            ->get();
        $rate_row = array();      
        foreach ($job_rates as $key => $value)
            $rate_row[$value->pw_wages_rate_type] = $value->rate>0?$value->rate:"";
               
		$params = array('left_menu' => $modules, 'data'=>$wages_info, 'emp_types'=>$emps_array, 'county_types'=>$county_array, 'task_types'=>$task_tapes_array, 'pw_wages_rates_type'=> $this->pw_wages_rates_type, 'rate_row'=>$rate_row);
		return View::make('wages.edit', $params);
	}

	/**
	*
	*Ajax request for creating
	*	new Task Type
	*
	**/
	public function creatNewTasktype(){
		$task_type_val = Input::get('task_type_val');
		DB::table('gpg_task_types')
     		->insert(array('task_type' =>$task_type_val, 'created_on'=>date("Y-m-d H:i:s"),'modified_on'=>date("Y-m-d H:i:s"))
			);
		return "success";	
	}
	/**
	*
	*Ajax request for creating
	*	new Employee Type
	*
	**/
	public function createNewEmployeetype(){
		$emp_type_val = Input::get('emp_type_val');
		DB::table('gpg_employee_type')
     		->insert(array('type' =>$emp_type_val)
			);
		return "success";	
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @return Response
	 */
	public function updateWageInfo()
	{
		$id = Input::get('wage_id');
		DB::table('gpg_job_rates')
          ->where('id','=', $id)
          ->update(array('job_number' =>$_POST['JobNumber'] ,'GPG_employee_type_id' =>$_POST['etype'] ,'gpg_county_id' =>$_POST['county_name'] ,'gpg_task_type' =>$_POST['task_type'] ,'status' =>$_POST['status'] ,'wage_type' =>$_POST['wage_type'] ,'start_date' =>$_POST['startDate'] ,'end_date' =>$_POST['endDate'] ,'pw_reg' =>$_POST['pwpay1'] ,'pw_overtime' =>$_POST['pwovertime1'],'pw_double' =>$_POST['pwdouble1']));
		
		$i=1;
        while ($i<7) {
          DB::table('gpg_job_rates_breakup')
	        ->where('job_rates_id','=', $id)
	        ->where('pw_wages_rate_type','=', $i)
	        ->update(array('rate' =>$_POST['pw_wages_rate_type_'.$i.'']));
	        $i++;
          }  

        return Redirect::route('wages.index');
	}
	/**
	 * Crone Job for Setting Time Sheet wages.
	 *
	 * @return Response
	 */
	public function setTimesheetWage()
	{
		$modules = Generic::modules();

		$params = array('left_menu' => $modules);
		return View::make('wages.setTimesheetWage', $params);
	}
	/**
	 * Crone Request for Setting Time Sheet wages.
	 *
	 * @return Response
	 */
	public function updateWagesAuto()
	{
		$success = array();
		$fail = array();
		$index = 0;
		$query = DB::select( DB::raw("select a.GPG_employee_Id as EmpId,b.id as id, b.GPG_timetype_id as timetype, b.labor_rate as laborRate , c.pw_reg, c.pw_overtime, c.pw_double from gpg_timesheet a , gpg_timesheet_detail b, gpg_job_rates c WHERE b.gpg_task_type = c.gpg_task_type and a.id = b.GPG_timesheet_id and b.job_num = c.job_number and c.GPG_employee_type_id = (select GPG_employee_type_id from gpg_employee where id = a.GPG_employee_id) and a.date>=c.start_date and a.date<=c.end_date and c.status = 'A' and b.time_diff_dec > 0 "));
		
		foreach ($query as $key => $value) {
			$index++;
			$upQuery ='';
			$perHourLabor = '';

			if($value->laborRate=='0.00')	{
			 	$perHourLabor_rate = DB::select( DB::raw("select rate from gpg_employee_wage where gpg_employee_id = '".$value->EmpId."'"));
				$perHourLabor = $perHourLabor_rate[0]->rate;
			}

			try {
				    if($value->timetype > 2) {
				    	if (empty($perHourLabor)) {
					    	$upQuery = DB::table('gpg_timesheet_detail')
						    	->where('GPG_timetype_id','>', 2)
						    	->where('id','=', $value->id)
						    	->update(array('pw_reg_rate' => "(NULL)", 'pw_ot_rate' => "(NULL)" ,'pw_dt_rate' =>"(NULL)" ,'pw_flag' =>'0'));				    		
					   	}else{
					   		$upQuery = DB::table('gpg_timesheet_detail')
						    	->where('GPG_timetype_id','>', 2)
						    	->where('id','=', $value->id)
						    	->update(array('pw_reg_rate' => "(NULL)", 'pw_ot_rate' => "(NULL)" ,'pw_dt_rate' =>"(NULL)" ,'pw_flag' =>'0' ,'labor_rate' =>$perHourLabor));				    		
					   	}
					} 
					else {
					   if (empty($perHourLabor)) {
					    	$upQuery = DB::table('gpg_timesheet_detail')
						    	->where('id','=', $value->id)
						    	->update(array('pw_reg_rate' => $value->pw_reg, 'pw_ot_rate' => $value->pw_overtime ,'pw_dt_rate' =>$value->pw_double ,'pw_flag' =>'1'));				    		
					   	}else{
					   		$upQuery = DB::table('gpg_timesheet_detail')
						    	->where('id','=', $value->id)
						    	->update(array('pw_reg_rate' => $value->pw_reg, 'pw_ot_rate' => $value->pw_overtime ,'pw_dt_rate' =>$value->pw_double ,'pw_flag' =>'1','labor_rate' =>$perHourLabor));				    		
					   	}
					}
				    $success[$index] = $upQuery;
			}catch(\Exception $e){
			 		$fail[$index] = $upQuery;
			}
		}
		
		return Redirect::route('wages.setTimesheetWage');
	}
	/**
	 * For Recalculating wages.
	 *
	 * @return Response
	 */
	public function recalculateWages()
	{
		$emps_arr = array('ALL'=>'ALL');
		$modules = Generic::modules();
		$gpg_employee = DB::table('gpg_employee')
	  		->select('id','name')
	  		->orderBy('name', 'ASC')
            ->get();
        foreach ($gpg_employee as $key => $value) {
        	$emps_arr[$value->id] = $value->name;
        }
		$params = array('left_menu' => $modules, 'emp_row'=>$emps_arr);
		return View::make('wages.recalculateWages', $params);
	}

	/**
	*Replace Last Chracter
	*
	**/
	public function replaceLastMatch($str, $search, $replace) {
	    $pattern = sprintf('~%s(?!.*%1$s)~', $search);
	    return preg_replace($pattern, $replace, $str, 1);
	}

	/**
	 * For Recalculating Auto wages.
	 *
	 * @return Response
	 */
	public function recalculateWagesAuto()
	{	
		ini_set('max_execution_time', 3600);
		$query_part = "";
		$flag = 0;
		foreach ($_POST['employees'] as $key => $value) {
			if ($value == 'ALL') {
				break;
			}else{
				if ($flag == 0) {
					$query_part = "WHERE gpg_timesheet.GPG_employee_Id IN (";
					$flag =1;					
				}
				$query_part .= "'".$value."',";	
			}
		}
		if ($query_part != ""){
			$query_part .= ")";
			$query_part = $this->replaceLastMatch($query_part, ',', '');	
		}
        $result = DB::select( DB::raw("SELECT * FROM gpg_timesheet ".$query_part));
		$jobArray = array();
		// write queries here to recalculate wages from fix_timesheet.php
		foreach ($result as $key => $data) { // main foreach
			$time_sheet_count = DB::table('gpg_timesheet_detail')
	            ->select('*')
	            ->where('GPG_timesheet_id', '=', $data->id)
	            ->count();
	        $time_sheet_detail = DB::table('gpg_timesheet_detail')
	            ->select('*')
	            ->where('GPG_timesheet_id', '=', $data->id)
	            ->get();    
	        $TotalLines = $time_sheet_count -1;
	        $total_reg = 0;
			$total_ot = 0;
			$total_dt = 0;
			$prev_total_reg = 0;
			$prev_total_ot = 0;
			$prev_total_dt = 0;
			$total_hrs =0;
			$sorting_prevail_first = array();
			$total_hours_per_job_breakup = array();
			$total_hours_per_job_breakup_before = array();
			$TotalLines2 = $TotalLines;
			$result2 = DB::select( DB::raw("SELECT
									  job_num,
									  SUM(time_diff_dec) AS hrs
									FROM gpg_timesheet,
									  gpg_timesheet_detail
									WHERE gpg_timesheet.id = gpg_timesheet_detail.GPG_timesheet_id
										AND gpg_timesheet.GPG_employee_Id = '".$data->GPG_employee_Id."'
										AND gpg_timesheet.date != '".date('Y-m-d',strtotime($data->date))."'
										AND gpg_timesheet_detail.pw_flag = 1
										AND job_num IN(SELECT
														 job_num
													   FROM gpg_timesheet,
														 gpg_timesheet_detail
													   WHERE gpg_timesheet.GPG_employee_Id = '".$data->GPG_employee_Id."'
														   AND gpg_timesheet.id = gpg_timesheet_detail.GPG_timesheet_id
														  # AND gpg_timesheet.date = '".date('Y-m-d',strtotime($data->date))."'
														  )
									GROUP BY job_num"));
			foreach ($result2 as $key => $value) {
				$total_hours_per_job_breakup[$value->job_num]['used_prev'] = $value->hrs;
			}
			$line_loop = 0;
			$i = 0 ;
			if ($time_sheet_count > 0) {
				foreach ($time_sheet_detail as $key => $ts_detail_row) {
					$emp_type =  DB::table('gpg_employee')
				            ->select('GPG_employee_type_id')
				            ->where('id', '=', $data->GPG_employee_Id)
				            ->get(); 
		            $getEmpType = $emp_type[0]->GPG_employee_type_id;

		            $_REQUEST["JobCheck_".$line_loop] = $ts_detail_row->complete_flag;
					$_REQUEST["WorkDone_".$line_loop] = $ts_detail_row->workdone;
					$_REQUEST["Recommendations_".$line_loop] = $ts_detail_row->recommendations;
					$_REQUEST["MilesDrive_".$line_loop] = $ts_detail_row->mileage;
					$_REQUEST["task_type_".$line_loop] = $ts_detail_row->gpg_task_type;
					$_REQUEST["county_id_".$line_loop] = $ts_detail_row->gpg_county_id;
					$_REQUEST["TimeSheetWage_".$line_loop] = $ts_detail_row->labor_rate;
					$_REQUEST["PWRegRate_".$line_loop]  = $ts_detail_row->pw_reg_rate ;
					$_REQUEST["PWotRate_".$line_loop]  = $ts_detail_row->pw_ot_rate;
					$_REQUEST["PWdtRate_".$line_loop]  = $ts_detail_row->pw_dt_rate;
					$_REQUEST["TimeSheetDetail_".$line_loop] = $ts_detail_row->id;
					$_REQUEST["JobNumber_".$line_loop] = $ts_detail_row->job_num;
					$_REQUEST["TimeInHidden_".$line_loop] = $ts_detail_row->time_in;
					$_REQUEST["TimeOutHidden_".$line_loop] = $ts_detail_row->time_out;
					$_REQUEST["TimeDiff_".$line_loop] = $ts_detail_row->time_diff_dec;
					$_REQUEST["labor_rate".$line_loop]  = $ts_detail_row->labor_rate;
					$_REQUEST["TimeType_".$line_loop]  = $ts_detail_row->GPG_timetype_id;

		            $job_row = DB::select( DB::raw("SELECT task,IFNULL(contract_number,'') as contract_number FROM gpg_job WHERE job_num = '".$ts_detail_row->job_num."'"));
		           //preg match start here
		            if(preg_match("/tc/i",$ts_detail_row->job_num) || preg_match("/pm/i",$ts_detail_row->job_num) || preg_match("/qt/i",$ts_detail_row->job_num) && $job_row[0]->contract_number!=""){
		            	
		            	if($total_hours_per_job_breakup[$ts_detail_row->job_num])
							$total_hours_per_job_breakup_before[$ts_detail_row->job_num] = $total_hours_per_job_breakup[$ts_detail_row->job_num];
						else
							$total_hours_per_job_breakup_before[$ts_detail_row->job_num] = 0;
						$hours_per_job = @round((strtotime($ts_detail_row->time_out)-strtotime($ts_detail_row->time_in))/3600,2);
						$total_hours_per_job_breakup[$ts_detail_row->job_num]['hrs'] += $hours_per_job;
						$compare_job_num = strtolower(substr($ts_detail_row->job_num,0,2));

						$getPWRow = DB::select( DB::raw("SELECT *,count(*) as getPWRowSum, IFNULL(prevailing_hours,'') as empty_hours, IFNULL(gpg_job_regarding,'') as empty_gpg_job_regarding
								FROM gpg_job_rates
								WHERE (job_number = '$JobNumber' OR job_number = '".$compare_job_num."')
								AND (gpg_job_regarding = (SELECT task FROM gpg_job WHERE job_num = '$JobNumber') OR gpg_job_regarding = '~~ALL' 
										OR IFNULL(gpg_job_regarding,'')='')
									AND gpg_task_type = '$task_type'
									AND gpg_county_id = '$county_id'
									AND GPG_employee_type_id = '$getEmpType'
									AND (contract_number = '".$job_row[0]->contract_number."' OR IFNULL(contract_number,'')='')
									AND start_date <= '".date('Y-m-d',strtotime($data->date))."' AND end_date>='".date('Y-m-d',strtotime($data->date))."'
								ORDER BY LENGTH(job_number) ASC, gpg_job_regarding DESC LIMIT 1"));
						$regarding_check = false;
						$job_num_check = false;
						$total_hours_per_job_breakup[$ts_detail_row->job_num]['total_prev'] = $getPWRow[0]->empty_hours;
						if ($getPWRow[0]->getPWRowSum > 0) {
						
							if(empty($getPWRow[0]->empty_gpg_job_regarding))
								$regarding_check = true;
							else if($getPWRow[0]->gpg_job_regarding == '~~ALL')
								$regarding_check = true;
							else if($getPWRow[0]->gpg_job_regarding == $job_row[0]->task)
								$regarding_check = true;

							if(strlen($getPWRow[0]->job_number) == 2)
							{   
								if($compare_job_num==$getPWRow[0]->job_number)
									$job_num_check = true;
							}
							else if($getPWRow[0]->job_number == $ts_detail_row->job_num)
								$job_num_check = true;
						}
						if(!$regarding_check or !$job_num_check)
							$getPWRow = array();
						else // Hours check
						{
								$left_hours = $total_hours_per_job_breakup[$ts_detail_row->job_num]['total_prev'] - $total_hours_per_job_breakup[$ts_detail_row->job_num]['used_prev'];
								// case 1: if coming hours are less or equal than left hours
								if(empty($total_hours_per_job_breakup[$ts_detail_row->job_num]['total_prev']) && empty($getPWRow[0]->empty_gpg_job_regarding))
									$total_hours_per_job_breakup[$ts_detail_row->job_num]['used_prev'] += $hours_per_job;
								elseif($left_hours <= 0)
									$getPWRow = array();
								elseif($hours_per_job <= $left_hours)
									$total_hours_per_job_breakup[$ts_detail_row->job_num]['used_prev'] += $hours_per_job;
								elseif($hours_per_job > $left_hours)
								{
									// calculating the end time
									$TimeInHidden_reg = (trim($ts_detail_row->time_in)?date("g:ia",(strtotime($ts_detail_row->time_in) + (60 *60 * $left_hours))):'00:00:00');
									$newrow_id = $TotalLines2+1;
									// updating the time end time for existing job
									  $_REQUEST["TimeSheetDetail_".$newrow_id] =  "";
									  $_REQUEST["TimeSheetWage_".$newrow_id] =  ($_REQUEST["TimeSheetWage_".$i]);
									  $_REQUEST["TimeType_".$newrow_id] =   ($_REQUEST["TimeType_".$i]);
									  $_REQUEST["JobNumber_".$newrow_id] =  ($_REQUEST["JobNumber_".$i]);
									  $_REQUEST["TimeInHidden_".$newrow_id] =  ($TimeInHidden_reg);
									  $_REQUEST["isprevail_".$newrow_id] = 0;
									  $_REQUEST["TimeOutHidden_".$newrow_id] =  ($_REQUEST["TimeOutHidden_".$i]);
									  $_REQUEST["JobCheck_".$newrow_id] =  ($_REQUEST["JobCheck_".$i]);
									  $_REQUEST["WorkDone_".$newrow_id] =  ($_REQUEST["WorkDone_".$i]);
									  $_REQUEST["Recommendations_".$newrow_id] =  ($_REQUEST["Recommendations_".$i]);
									  $_REQUEST["MilesDrive_".$newrow_id] =  ($_REQUEST["MilesDrive_".$i]);
									  $_REQUEST["task_type_".$newrow_id] = ($_REQUEST["task_type_".$i]) ;
									  $_REQUEST["county_id_".$newrow_id] = ($_REQUEST["county_id_".$i]) ;
									  $TotalLines2 = $newrow_id;
									  $total_hours_per_job_breakup[$ts_detail_row->job_num]['used_prev'] += $hours_per_job;
									  $_REQUEST["TimeOutHidden_".$i] = $TimeInHidden_reg;
									
									$sorting_prevail_first['reg'][strtotime($TimeInHidden_reg)] = $newrow_id;
								}
						}

		            }//end if preg matches
		            else // it is a non TC QT and PM job
					{
						$getPWRow = DB::select( DB::raw("select * from gpg_job_rates where job_number = '".$ts_detail_row->job_num."' and gpg_task_type = '$ts_detail_row->gpg_task_type' 
						 and gpg_county_id = '$ts_detail_row->gpg_county_id' and GPG_employee_type_id = '$getEmpType' and start_date<='".date('Y-m-d',strtotime($data->date))."' 
						and end_date>='".date('Y-m-d',strtotime($data->date))."' "));
					}

					$TimeInHidden_t = strtotime($ts_detail_row->time_in);
					if((isset($getPWRow[0]->pw_reg)?$getPWRow[0]->pw_reg:0) > 0 && ($ts_detail_row->GPG_timetype_id == 1 || $ts_detail_row->GPG_timetype_id == 2))
					{
						$_REQUEST["isprevail_".$i] = 1;
						$sorting_prevail_first['pw'][$TimeInHidden_t] = $i;
					}
					else
					{
						$_REQUEST["isprevail_".$i] = 0;
						$sorting_prevail_first['reg'][$TimeInHidden_t] = $i;
					}
					$line_loop++;
					$i++ ;
				}//end foreach for time_sheet_detail
			}// end if time_sheet_count
			$TotalLines = $TotalLines2;
			@ksort($sorting_prevail_first['pw']);
			@ksort($sorting_prevail_first['reg']);
			@ksort($sorting_prevail_first);
			$temp_order_arr = array();
			foreach($sorting_prevail_first as $ak => $av)
			{
				if(is_array($av))
				foreach($av as $bk => $bv)
				{
					$temp_order_arr[] = $bv;
				}
			}
			for ($jktemp=0; $jktemp < sizeof($temp_order_arr); $jktemp++) {
				$i = $temp_order_arr[$jktemp];
				$PWRegRate =  ($_REQUEST["PWRegRate_".$i]);
				$PWotRate =  ($_REQUEST["PWotRate_".$i]);
				$PWdtRate =  ($_REQUEST["PWdtRate_".$i]);
				$TimeSheetDetailId =   ($_REQUEST["TimeSheetDetail_".$i]);
				$TimeSheetWage =  ($_REQUEST["TimeSheetWage_".$i]);
			    $TimeType =   ($_REQUEST["TimeType_".$i]);
				$JobNumber =  ($_REQUEST["JobNumber_".$i]);
				$TimeInHidden =  ($_REQUEST["TimeInHidden_".$i]);
				$TimeOutHidden =  ($_REQUEST["TimeOutHidden_".$i]);
				$JobCheck =  ($_REQUEST["JobCheck_".$i]);
				$WorkDone =  ($_REQUEST["WorkDone_".$i]);
				$Recommendations =  ($_REQUEST["Recommendations_".$i]);
				$MilesDrive =  ($_REQUEST["MilesDrive_".$i]);
				$TimeInHidden = (trim($TimeInHidden)?date('%h:%i',strtotime($TimeInHidden)):'00:00:00');
				$TimeOutHidden = (trim($TimeOutHidden)?date('%h:%i',strtotime($TimeOutHidden)):'00:00:00');
				$task_type = ($_REQUEST["task_type_".$i]) ;
				$county_id = ($_REQUEST["county_id_".$i]) ;
				$prevail_check = ($_REQUEST["isprevail_".$i]) ;
				$pw_reg = 0.00;   
				$pw_ot = 0.00; 
				$pw_dt  = 0.00;
				$getJobIds =  DB::table('gpg_job')
				            ->select('id')
				            ->where('job_num', '=', $JobNumber)
				            ->get(); 
		        $getJobId = $getJobIds[0]->id;
		        $jobArray[$JobNumber] = $getJobId;
			   	$totalHours =0;
			 	$rH = 0;
				$otH = 0;
				$dtH = 0;
			    $totalHours = @round((strtotime($TimeOutHidden)-strtotime($TimeInHidden))/3600,2);
				$total_hrs += $totalHours;
					if ($total_hrs<=8) { $total_reg = $total_hrs; }
					else if ($total_hrs>8 && $total_hrs<=12) { $total_reg = 8; $total_ot = $total_hrs-8; } 
					else if ($total_hrs>12) { $total_reg = 8; $total_ot = 4; $total_dt = $total_hrs-12; }  
				  
					//echo $total_reg." - ".$total_ot." - ".$total_dt."<br />";
					
					if($total_reg <= 8 && $total_ot == 0 && $total_dt == 0) // REG
					{
						$rH = $totalHours;
					}
					elseif($total_reg <= 8 && $total_ot <= 4 && $total_dt == 0) // OT
					{
						$rH = 8 - $prev_total_reg;
						$otH = $totalHours - $rH;
					}
					elseif($total_reg <= 8 && $total_ot <= 4 && $total_dt > 0) // DT
					{
						$rH = 8 - $prev_total_reg;
						$otH = 4 - $prev_total_ot;
						$dtH = $total_hrs - 12 - $prev_total_dt;
					}
					
					$prev_total_reg = $total_reg;
					$prev_total_ot = $total_ot;
					$prev_total_dt = $total_dt;
				if (!empty($TimeType)) {
					DB::table('gpg_job')
			          ->where('id','=', $getJobId)
			          ->update(array('GPG_employee_id' =>$data->GPG_employee_Id ,'status' =>'A' ,'schedule_date' =>date('Y-m-d') ));
					$emp_type =  DB::table('gpg_employee')
				            ->select('GPG_employee_type_id')
				            ->where('id', '=', $data->GPG_employee_Id)
				            ->get(); 
		            $getEmpType = $emp_type[0]->GPG_employee_type_id;
		            if(preg_match("/tc/i",$JobNumber) || preg_match("/pm/i",$JobNumber) || preg_match("/qt/i",$JobNumber))
					{
						$job_row = DB::select( DB::raw("SELECT task,contract_number FROM gpg_job WHERE job_num = '".$JobNumber."'"));
						$getPWRow = DB::select( DB::raw("SELECT *, IFNULL(prevailing_hours,'') as empty_hours, IFNULL(gpg_job_regarding,'') as empty_gpg_job_regarding
												FROM gpg_job_rates
												WHERE (job_number = '$JobNumber' OR job_number = '".$compare_job_num."')
												AND (gpg_job_regarding = (SELECT task FROM gpg_job WHERE job_num = '$JobNumber') OR gpg_job_regarding = '~~ALL'
													OR IFNULL(gpg_job_regarding,'')='')
													AND gpg_task_type = '$task_type'
													AND gpg_county_id = '$county_id'
													AND GPG_employee_type_id = '$getEmpType'
													AND (contract_number = '".$job_row[0]->contract_number."' OR IFNULL(contract_number,'')='')
													AND start_date <= '".date('Y-m-d',strtotime($data->date))."' AND end_date>='".date('Y-m-d',strtotime($data->date))."'
												ORDER BY LENGTH(job_number) ASC, gpg_job_regarding DESC LIMIT 1"));
					}else
						$getPWRow = DB::select( DB::raw("SELECT * from gpg_job_rates where job_number = '$JobNumber' and gpg_task_type = '$task_type' 
										and gpg_county_id = '$county_id' and GPG_employee_type_id = '$getEmpType' and start_date<='".date('Y-m-d',strtotime($data->date))."' 
										and end_date>='".date('Y-m-d',strtotime($data->date))."'"));	
							    $prevail = 0;
								if (empty($TimeSheetDetailId)) {
								    $res = DB::select( DB::raw("select rate from gpg_employee_wage where gpg_employee_id = '".$data->GPG_employee_Id."' and type = 'h' and start_date <= '".date('Y-m-d',strtotime($data->date))."' order by start_date desc limit 0,1"));
								    $perHourLabor = $res[0]->rate;
								    $timeDiffDec = $totalHours;
								    
								    if ($prevail_check==1 && $getPWRow[0]->pw_reg>0 && ($TimeType == 1 || $TimeType == 2)) {
									  	$prevail = $prevail_check;
									   	$pw_reg = $getPWRow[0]->pw_reg;
										$pw_ot = ($getPWRow[0]->pw_overtime>0?$getPWRow[0]->pw_overtime:($getPWRow[0]->pw_reg*1.5));
										$pw_dt = ($getPWRow[0]->pw_double>0?$getPWRow[0]->pw_double:($getPWRow[0]->pw_reg*2));
								  	}
								  $regWage = @round($rH*($prevail==1?$pw_reg:$perHourLabor),2);
								  $otWage = @round($otH*($prevail==1?$pw_ot:($perHourLabor*1.5)),2);
								  $dtWage = @round($dtH*($prevail==1?$pw_dt:($perHourLabor*2)),2);
								  $totalWage = $regWage + $otWage + $dtWage;

									if($TimeInHidden!="00:00:00" && $TimeOutHidden != "00:00:00")
									{
									  $InsertQuery_Time = "INSERT INTO gpg_timesheet_detail( GPG_job_id, GPG_timesheet_id, GPG_timetype_id
									  , job_num , time_in , time_out , complete_flag , workdone , recommendations , mileage , time_diff_dec 
									  , reg_hrs, ot_hrs, dt_hrs, reg_wage , ot_wage , dt_wage, pw_reg_rate , pw_ot_rate , pw_dt_rate , pw_flag, total_wage , labor_rate 
									  , created_on , modified_on , gpg_task_type , gpg_county_id)
									  VALUES ( '".$getJobId."' , '".$data->id."' , '".$TimeType."' , '".$JobNumber."' , '".$TimeInHidden."' , '".$TimeOutHidden."' 
									  , '".$JobCheck."' , '".$WorkDone."' , '".$Recommendations."' , '".$MilesDrive."' , '".$timeDiffDec."', '".$rH."' 
									  , '".$otH."' , '".$dtH."' , '".$regWage."' , '".$otWage."' , '".$dtWage."' , '".$getPWRow[0]->pw_reg."' , '".$getPWRow[0]->pw_overtime."' 
									  , '".$getPWRow[0]->pw_double."' , '".$prevail."' ,'".$totalWage."' , '".$perHourLabor."' 
									  , '".date('Y-m-d')."', '".date('Y-m-d')."' ,'".$task_type."' ,'".$county_id."')";
									}
							   }
							   else {  
									$prhrLbr =  DB::select( DB::raw("SELECT rate from gpg_employee_wage where gpg_employee_id = '".$data->GPG_employee_Id."' and type = 'h' and start_date <= '".date('Y-m-d',strtotime($data->date))."' order by start_date desc limit 0,1"));
									$perHourLabor =  ($TimeSheetWage>0 ? $TimeSheetWage : (isset($prhrLbr[0]->rate)?$prhrLbr[0]->rate:0));
									if ($prevail_check && $getPWRow[0]->pw_reg>0 && ($TimeType == 1 || $TimeType == 2)) {
										$prevail = 1;
									    $pw_reg = $getPWRow[0]->pw_reg;
										$pw_ot = ($getPWRow[0]->pw_overtime>0?$getPWRow[0]->pw_overtime:($getPWRow[0]->pw_reg*1.5));
										$pw_dt = ($getPWRow[0]->pw_double>0?$getPWRow[0]->pw_double:($getPWRow[0]->pw_reg*2));
									} 
									$timeDiffDec = $totalHours;
									$regWage = @round($rH*($prevail==1?$pw_reg:$perHourLabor),2);
									$otWage = @round($otH*($prevail==1?$pw_ot:($perHourLabor*1.5)),2);
									$dtWage = @round($dtH*($prevail==1?$pw_dt:($perHourLabor*2)),2);
									$totalWage = $regWage + $otWage + $dtWage;
									if($TimeInHidden!="00:00:00" && $TimeOutHidden != "00:00:00")
							    	{
										$getSuccess = DB::table('gpg_timesheet_detail')
									        ->where('id','=', $TimeSheetDetailId)
									        ->update(array('GPG_job_id' =>$getJobId ,'GPG_timesheet_id' =>$data->id ,'GPG_timetype_id' =>$TimeType ,'job_num' =>$JobNumber ,'time_in' =>$TimeInHidden ,'time_out' =>$TimeOutHidden ,'complete_flag' =>$JobCheck ,'workdone' =>$WorkDone ,'recommendations' =>$Recommendations ,'mileage' =>$MilesDrive ,'time_diff_dec' =>$timeDiffDec ,'reg_hrs' =>$rH ,'ot_hrs' =>$otH ,'dt_hrs' =>$dtH ,'reg_wage' =>$regWage
									         ,'ot_wage' =>$otWage ,'dt_wage' =>$dtWage ,'total_wage' =>$totalWage ,'pw_reg_rate' =>$pw_reg ,'pw_ot_rate' =>$pw_ot ,'pw_dt_rate' =>$pw_dt ,'labor_rate' =>$perHourLabor ,'pw_flag' =>$prevail ,'modified_on' =>date('Y-m-d') ,'gpg_task_type' =>$task_type ,'gpg_county_id' =>$county_id));
			          				}
								}
								$SuccessFlag = 1;
				}// end if time type not empty	

			} // end jktemp for loop

		}//end main foreach
		foreach ($jobArray as $k=>$v) {
			$res1 = DB::select( DB::raw("select sum(amount) as t_sum from gpg_job_cost where job_num = '".$k."'"));
			$res2 = DB::select( DB::raw("select sum(reg_wage+ot_wage + dt_wage) as rod_sum from gpg_timesheet_detail where job_num = '".$k."'"));
	    	$matCost = $res1[0]->t_sum;
			$labCost = $res2[0]->rod_sum;
			$getSuccess = DB::table('gpg_job')
     				        ->where('job_num','=', $k)
					        ->update(array('cost_to_dat' =>round(($matCost+$labCost),2)));
		}

		return Redirect::route('/wages/recalculateWages');
	}

	/**
	 * For Importing Wages County.
	 *
	 * @return Response
	 */
	public function importWagesCounty()
	{
		$modules = Generic::modules();
		$params = array('left_menu' => $modules, 'success'=>'0');
		return View::make('wages.importWagesCounty', $params);
	}
	/**
	 * For Importing Wages County.
	 *
	 * @return Response
	 */
	public function insertUpdateCounties()
	{
		$file = Input::file('uploadFile');
		$filename = "";
		if (!empty($file)) {
			$file1 = Input::file('uploadFile')->getClientOriginalName();
			$filename = "counties_".rand(99999,10000000)."_".strtotime("now").".".$file1;
			$destinationPath = public_path().'/img/';
			$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
		}
		$fh = fopen($destinationPath.$filename,'r');
		while ($county = fgets($fh)) {
			$county_names = DB::table('gpg_county')
		  		->select('*')
		  		->where('county_name', '=', $county)
	            ->get();
	        if (!isset($county_names[0]->county_name)) {
	        	DB::table('gpg_county')
     				->insert(array('county_name' =>$county, 'created_on'=>date("Y-m-d H:i:s")));
	        }    	   
		}
		fclose($fh);
		$modules = Generic::modules();
		$params = array('left_menu' => $modules, 'success'=>'1');
		return View::make('wages.importWagesCounty', $params);
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
		// php artisan dump-autoload
		$obj = Gpg_wages::find($id)->delete();
		return Redirect::route('wages.index');
	}


}
