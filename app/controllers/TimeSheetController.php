<?php

class TimeSheetController extends \BaseController {

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

  		$query = DB::table('gpg_employee')
            ->select('id','name')
            ->where('status', '=', 'A')
            ->where('frontend', 'LIKE', '%timesheet%')
            ->orderBy('name', 'ASC')
            ->get(); 
       	$emp_select = "<Select class='form-control m-bot15' id='select_emp' name='select_emp' required><option value=''>Select Employee</option>";	
        foreach ($query as $key => $obj) {
             $emp_select .= "<option value='".$obj->id."'>".$obj->name."</option>";
        }   
        $emp_select .= "</select>";
  		$params = array('left_menu' => $modules, 'query_data'=>$query_data, 'emp_select'=>$emp_select);
 		return View::make('timesheet.index', $params);
	}

	/*
	* empAttenHist
	*/
	public function empAttenHist($id){
		$modules = Generic::modules();
		$emp_name = DB::table('gpg_employee')->where('id','=',$id)->pluck('name');
		$emps_arr = DB::table('gpg_employee')->where('status', '=', 'A')->where('frontend', 'LIKE', '%timesheet%')->lists('name','id');
		$qry = DB::table('gpg_timesheet')->select('*')->where('GPG_employee_Id','=',$id)->get();
		$query_data = array();
		foreach ($qry as $key => $value) {
			$query_data[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'emp_name'=>$emp_name,'id'=>$id,'emps_arr'=>$emps_arr);
 		return View::make('timesheet.emp_hist', $params);
	}

	public function search()
	{	
		if (isset($_POST['filter_val']))
		if (($_POST['filter_val'] == 'none') &&  empty($_POST['SDate']) && empty($_POST['EDate']) && empty($_POST['FVal'])){
	  		return Redirect::to('timesheet');
	  	}
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getBySearch($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$query = DB::table('gpg_employee')
            ->select('id','name')
            ->where('status', '=', 'A')
            ->where('frontend', 'LIKE', '%timesheet%')
            ->orderBy('name', 'ASC')
            ->get(); 
       	$emp_select = "<Select class='form-control m-bot15' id='select_emp' name='select_emp' required><option value=''>Select Employee</option>";	
        foreach ($query as $key => $obj) {
             $emp_select .= "<option value='".$obj->id."'>".$obj->name."</option>";
        }   
        $emp_select .= "</select>";
  		$params = array('left_menu' => $modules, 'query_data'=>$query_data, 'emp_select'=>$emp_select);
 		return View::make('timesheet.index', $params);
	}

	public function getBySearch($page = 1, $limit = 100)
	{
	  $results = new \StdClass;
	  $results->page = $page;
	  $results->limit = $limit;
	  $results->totalItems = 0;
	  $results->items = array();
	 
	  $str = "";
	  $str1 = "";
	  $post = "";
	  $post1 = array();
	  if (Session::has('check_query')) {
	  	$check_query = 	Session::get('check_query');
	  }else
		  $check_query = 0;
	 
	  if (!empty($_POST['ed_lock_check']) && !empty($_POST['SDate']) && !empty($_POST['EDate'])) {
	  		$check_query =1;
		  	Session::put('check_query', $check_query);
		  	$str1 = 'gpg_timesheet.date';
	  		$post1 = array($_POST['SDate'],$_POST['EDate']);
	  		
	  		$str = 'gpg_timesheet.ed_lock';
	  		$post = $_POST['ed_lock_check'];

	  		if ($_POST['ed_lock_check'] == '0')
	  			$post = NULL; 
	  }
	  else if (isset($_POST['lock_time_sheet']) && !empty($_POST['SDate']) && !empty($_POST['EDate'])) {
			$check_query = 3;
		  	Session::put('check_query', $check_query);
			$str1 = 'gpg_timesheet.date';
	  		$post1 = array($_POST['SDate'],$_POST['EDate']);
	  }
	  else if (!empty($_POST['FVal']) && !empty($_POST['SDate']) && !empty($_POST['EDate'])) {
	  	$check_query=1;
  		  	Session::put('check_query', $check_query);
  		  	$str1 = 'gpg_timesheet.date';
	  		$post1 = array($_POST['SDate'],$_POST['EDate']);

	  		$str = 'gpg_employee.name';
	  		$post =$_POST['FVal'];
	  }else if (empty($_POST['FVal']) && empty($_POST['ed_lock_check']) && empty($_POST['SDate']) && !empty($_POST['EDate'])) {
	  	Session::put('check_query', 0);
	  	$str1 = 'gpg_timesheet.date';
	  	$post1 = array(date('1970-01-01'),$_POST['EDate']);
	  }else if (empty($_POST['FVal']) && empty($_POST['ed_lock_check']) && !empty($_POST['SDate']) && empty($_POST['EDate'])) {
	  	Session::put('check_query', 0);
	  	$str1 = 'gpg_timesheet.date';
	  	$post1 = array($_POST['SDate'],date('Y-m-d'));
	  }
	  else if (empty($_POST['FVal']) && empty($_POST['ed_lock_check']) && !empty($_POST['SDate']) && !empty($_POST['EDate'])) {
	  	Session::put('check_query', 0);
	  	$str1 = 'gpg_timesheet.date';
	  	$post1 = array($_POST['SDate'],$_POST['EDate']);
	  }
	  else if (isset($_POST['FVal']) && empty($_POST['SDate']) && empty($_POST['EDate'])) {
	  		$check_query=2;
		  	Session::put('check_query', $check_query);	  		
	  		$str = 'gpg_employee.name';
	  		$post = $_POST['FVal'];
	  }
	  else if ( isset($_POST['ed_lock_check']) && empty($_POST['SDate']) && empty($_POST['EDate'])) {
	  		$check_query=2;
		  	Session::put('check_query', $check_query);
	  		$str = 'gpg_timesheet.ed_lock';
	  		$post = $_POST['ed_lock_check'];

	  		if ($_POST['ed_lock_check'] == '0')
	  			$post = NULL;
	  }
	  else
	  	$this->index();	

	  if ($check_query == 1) {
	  			if (empty($str) && empty($post)) {
	  				$str = Session::get('str');		
	  				$post = Session::get('post');
	  				$str1 = Session::get('str1');		
	  				$post1 = Session::get('post1');		
	  			}else
	  				{
	  					Session::put('str' , $str);		
		  				Session::put('post' , $post);			
	  					Session::put('str1' , $str1);		
		  				Session::put('post1' , $post1);			
	  				}
	    $query_count = DB::table('gpg_timesheet')
	            ->select('*')
	            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_Id', '=', 'gpg_employee.id')
	            ->whereBetween($str1,  $post1)
	            ->where($str, '=', $post)
	            ->count();
	            
		$query = DB::table('gpg_timesheet')
		  		->select('*', 'gpg_timesheet.id as tid', 'gpg_employee.id as eid')
	            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_Id', '=', 'gpg_employee.id')
	            ->whereBetween($str1,  $post1)
	            ->where($str, '=', $post)
	            ->orderBy('date', 'desc')
	            ->skip($limit * ($page - 1))
				->take($limit)
				->get();	  	
	  }
	  else if ($check_query == 2) {
	  			if (empty($str) && empty($post)) {
	  				$str = Session::get('str');		
	  				$post = Session::get('post');		
	  			}else
	  				{
	  					Session::put('str' , $str);		
		  				Session::put('post' , $post);			
	  				}
	  	 $query_count = DB::table('gpg_timesheet')
		            ->select('*')
		            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_Id', '=', 'gpg_employee.id')
		            ->where($str, '=', $post)
		            ->count();
		            
			  $query = DB::table('gpg_timesheet')
			  		->select('*', 'gpg_timesheet.id as tid', 'gpg_employee.id as eid')
		            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_Id', '=', 'gpg_employee.id')
		            ->where($str, '=', $post)
	           	    ->orderBy('date', 'desc')
		            ->skip($limit * ($page - 1))
					->take($limit)
					->get();
	  }
	  else if  ($check_query == 0) {
				if (empty($str1) && empty($post1)) {
	  				$str1 = Session::get('str1');		
	  				$post1 = Session::get('post1');		
	  			}else
	  				{
	  					Session::put('str1' , $str1);		
		  				Session::put('post1' , $post1);			
	  				}
		  	  $query_count = DB::table('gpg_timesheet')
		            ->select('*')
		            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_Id', '=', 'gpg_employee.id')
		            ->whereBetween($str1,  $post1)
		            ->count();
		            
			  $query = DB::table('gpg_timesheet')
			  		->select('*', 'gpg_timesheet.id as tid', 'gpg_employee.id as eid')
		            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_Id', '=', 'gpg_employee.id')
		            ->whereBetween($str1,  $post1)
		            ->orderBy('date', 'desc')
		            ->skip($limit * ($page - 1))
					->take($limit)
					->get();

		}
		else if  ($check_query == 3) {
				if (empty($str1) && empty($post1)) {
	  				$str1 = Session::get('str1');		
	  				$post1 = Session::get('post1');		
	  			}else
	  				{
	  					Session::put('str1' , $str1);		
		  				Session::put('post1' , $post1);			
	  				}	
			 $query0 = DB::table('gpg_timesheet')
			  		->select('*', 'gpg_timesheet.id as tid', 'gpg_employee.id as eid')
		            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_Id', '=', 'gpg_employee.id')
		            ->whereBetween($str1,  $post1)
		            ->get();

		    foreach ($query0 as $key => $value) {
				DB::table('gpg_timesheet')
		           ->where('id', $value->tid)
		           ->update(array('ed_lock' => '1'));	
			}        

		  	  $query_count = DB::table('gpg_timesheet')
		            ->select('*')
		            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_Id', '=', 'gpg_employee.id')
		            ->whereBetween($str1,  $post1)
		            ->count();
		            
			  $query = DB::table('gpg_timesheet')
			  		->select('*', 'gpg_timesheet.id as tid', 'gpg_employee.id as eid')
		            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_Id', '=', 'gpg_employee.id')
		            ->whereBetween($str1,  $post1)
		            ->orderBy('date', 'desc')
		            ->skip($limit * ($page - 1))
					->take($limit)
					->get();

		}

	 
	  $results->totalItems = $query_count;
	  $results->items = $query;
	  return $results;
	}

	public function getByPage($page = 1, $limit = 100)
	{
	  $results = new \StdClass;
	  $results->page = $page;
	  $results->limit = $limit;
	  $results->totalItems = 0;
	  $results->items = array();
	 
	  $query_count = DB::table('gpg_timesheet')
            ->select('*')
            ->join('gpg_employee', 'gpg_timesheet.gpg_employee_id', '=', 'gpg_employee.id')
			->count();

	  $query = DB::table('gpg_timesheet')
	  		->select('*', 'gpg_timesheet.id as tid', 'gpg_employee.id as eid')
            ->join('gpg_employee', 'gpg_timesheet.gpg_employee_id', '=', 'gpg_employee.id')
            ->orderBy('date', 'desc')
            ->skip($limit * ($page - 1))
			->take($limit)
			->get();
	 
	  $results->totalItems = $query_count;
	  $results->items = $query;
	 
	  return $results;
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		set_time_limit(0);
		$modules = Generic::modules();
		$timesheet =  DB::table('gpg_employee')
            ->select('*')
            ->where('gpg_employee.id', '=', Input::get('select_emp'))
            ->get();
      	$result = array();
		foreach ($timesheet as $key => $value) {
   			 foreach ($value as $key1 => $value1) {
 	  			 $result[$key1] = $value1;
   			 }
 		}

      	$time_type_arr = array();
 		$time_type =  DB::table('gpg_timetype')
        ->select('gpg_timetype.id', 'gpg_timetype.name')
        ->where('gpg_timetype.status', '=', '1')
        ->get();
		foreach ($time_type as $key2 => $value2) {
   			 foreach ($value2 as $key3 => $value3) {
 					if ($key3 == 'id') {
 						$id =  $value3;
 					}
 					else
 						$time_type_arr[$id] = $value3;
   			 }
 		}
		$params = array('left_menu' => $modules, 'timesheet'=>$result, 'time_type'=>$time_type_arr);
 		return View::make('timesheet.create', $params);
		
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		set_time_limit(0);
		$emp_id = Input::get('emp_id');
	    $proj_date = Input::get('date');
        $date_time = date('Y-m-d H:i:s');
		/**************
			Pre-Queries	
		*/
        $timesheet_id =  DB::table('gpg_timesheet')
            ->select('gpg_timesheet.id')
            ->where('gpg_timesheet.GPG_employee_Id', '=', Input::get('emp_id'))
            ->where('gpg_timesheet.date', '=', Input::get('date'))
            ->get();    
        	$ts_max_id = 1 + DB::table('gpg_timesheet')->max('id'); 
            if (empty($timesheet_id)) {
            	DB::table('gpg_timesheet')->insert(
				    array('id' =>$ts_max_id ,'GPG_employee_id' => Input::get('emp_id') ,'date' =>Input::get('date') ,'created_on' => $date_time , 'modified_on' => $date_time )
				);
            }
            else
            {
            	DB::table('gpg_timesheet')
		            ->where('id', $timesheet_id[0]->id)
		            ->update(array('modified_on' => $date_time));
            }

		/*pre-queries ends here! */

		$JobCheck ='';
		$time_type ='';
		$project_activity ='';
		$recomend_text ='';
		$daily_log_text = '';
		$miles_driven ='';
		$type_task ='';
		$ot_hrs = '0.00';
		$dt_hrs = '0.00';
		// for time sheet first entery
		if (isset($_POST['JobNumber'])) {
			$JobNumber = $_POST['JobNumber'];
			
			//@@@@ Query #1 in post
			$job_id =  DB::table('gpg_job')
            ->select('id','task','contract_number')
            ->where('gpg_job.job_num', '=', $_POST['JobNumber'])
            ->get(); 
            $gpg_job_id = $job_id[0]->id;
            $gpg_job_task = $job_id[0]->task;
            $gpg_job_contract_number = $job_id[0]->contract_number;
            // ####### End query#1

            //@@@@ Query #1.1 in post
			$emp_type =  DB::table('gpg_employee')
            ->select('GPG_employee_type_id')
            ->where('id', '=', $emp_id)
            ->get(); 
            $emp_type_id = $emp_type[0]->GPG_employee_type_id;
            // ####### End query#1.1

            //@@@@ Query #1.2 in post
            $compare_job_num = strtolower(substr($_POST['JobNumber'],0,2));
          	$get_county = DB::select( DB::raw('SELECT DISTINCT c.id,county_name	
          				FROM gpg_job_rates r, gpg_county c 
						WHERE r.gpg_county_id = c.id 
						AND (r.job_number = "'.$_POST['JobNumber'].'" OR r.job_number = "'.$compare_job_num.'") 
						AND (contract_number = "'.$gpg_job_contract_number.'" OR IFNULL(contract_number,"")="")
						AND r.GPG_employee_type_id = "'.$emp_type_id.'"
					    AND (gpg_job_regarding = (SELECT task FROM gpg_job WHERE job_num = "'.$_POST['JobNumber'].'") OR gpg_job_regarding = "~~ALL"
						OR IFNULL(gpg_job_regarding,"")="") ORDER BY c.county_name'));
          	if (empty($get_county)) {
          		$county_id = '0';
          	}else
	          	$county_id = $get_county[0]->id;
          	// ####### End query#1.2

			//@@@@ Query #1.3 in post
            $wage_rate = DB::table('gpg_employee_wage')
	  		->select('rate')
            ->where('gpg_employee_id', '=',$emp_id)
            ->where('type', '=','h')
            ->where('start_date', '<=', $proj_date)
            ->orderBy('start_date', 'desc')
          	->get();
          	$perHourLabor = $wage_rate[0]->rate;
            // ####### End query#1.3

	 		$time_type = $_POST['time_type'];
			$time_in_ = explode(" ",$_POST['time_in']);
			$time_in = $time_in_[0];
			$time_out_ = explode(" ",$_POST['time_out']);
			$time_out = $time_out_[0];
			$time_diff = explode(":",$_POST['time_differnce']);
			$hrs = explode(" ",trim($time_diff[0])); 
			$mins = explode(" ",trim($time_diff[1])); 
			$time_differnce = $hrs[0].".".$mins[0];
			if (isset($_POST['recomend_text']))
          		$recomend_text = $_POST['recomend_text'];
          	if(isset($_POST['daily_log_text']))
				$daily_log_text = $_POST['daily_log_text'];
			if (isset($_POST['JobCheck']))
				$JobCheck = 1;
			else
				$JobCheck = '';
     		if (isset($_POST['type_task']))
     			$type_task = $_POST['type_task'];
     		if (isset($_POST['project_activity']))
     			$project_activity = $_POST['project_activity'];
     		if (isset($_POST['miles_driven'])) {
     			$miles_driven = $_POST['miles_driven'];
     		}
     		//@@@@ Query #2 in post
			$gpg_job_rates = DB::table('gpg_job_rates')
	  		->select('*')
            ->where('job_number', '=',$JobNumber)
            ->where('gpg_task_type', '=',$type_task)
            ->where('gpg_county_id', '=', $county_id)
            ->where('GPG_employee_type_id', '=', $emp_type_id)
            ->where('start_date', '<=', $proj_date)
          	->get();
 	        $pw_reg=0;
          	if (isset($gpg_job_rates[0]->pw_reg)) {
          		$pw_reg = $gpg_job_rates[0]->pw_reg;	
          	}
			$pw_overtime=0;
          	if (isset($gpg_job_rates[0]->pw_overtime)) {
          		$pw_overtime = $gpg_job_rates[0]->pw_overtime;	
          	}
          	$pw_double=0;
          	if (isset($gpg_job_rates[0]->pw_double)) {
          		$pw_double = $gpg_job_rates[0]->pw_double;	
          	}
			// ####### End query#2

			//@@@@ Pre Calculations #2.1 in post
			 $pw_ot=0;
			 $pw_dt=0;
			 $prevail =0;
			 $rH = $time_differnce;
			if ($pw_reg>0 && ($time_type == 1 || $time_type == 2)) {
				    $prevail = 1;
				    $pw_ot = ($pw_overtime>0?$pw_overtime:($pw_reg*1.5));
                    $pw_dt = ($pw_double>0?$pw_double:($pw_reg*2));
            }
            $regWage = @round($rH*($prevail==1?$pw_reg:$perHourLabor),2);
            $otWage = @round($ot_hrs*($prevail==1?$pw_ot:($perHourLabor*1.5)),2);
            $dtWage = @round($dt_hrs*($prevail==1?$pw_dt:($perHourLabor*2)),2);
            $totalWage = $regWage + $otWage + $dtWage;
			// ####### End Pre Calculations
     		DB::table('gpg_timesheet_detail')->insert(
				    array('GPG_job_id' =>$gpg_job_id ,'GPG_timesheet_id' =>$ts_max_id  ,'job_num' =>$JobNumber ,'GPG_timetype_id'=> $time_type,
				    'time_in' =>$time_in  , 'time_out' =>$time_out ,'complete_flag' =>$JobCheck ,'workdone' =>$daily_log_text ,
				    'recommendations' =>$recomend_text ,'mileage' =>$miles_driven ,'time_diff_dec' =>$time_differnce ,'reg_hrs' =>$time_differnce ,
				    'ot_hrs' =>$ot_hrs ,'dt_hrs' =>$dt_hrs ,'reg_wage' => $regWage,'ot_wage' => $otWage,'dt_wage' => $dtWage,
				    'pw_reg_rate' =>  $pw_reg,'pw_ot_rate' => $pw_overtime, 'pw_dt_rate' => $pw_double, 'pw_flag' => $prevail, 
				    'total_wage' => $totalWage, 'labor_rate' => $perHourLabor, 'created_on' =>$date_time , 'modified_on' =>$date_time , 
				    'gpg_task_type' => $type_task, 'gpg_county_id' => $county_id, 'gpg_activity_id' => $project_activity)
			);
			//die("success");
			$timesheet_detail_id = DB::table('gpg_timesheet_detail')->max('id');
			 $file_type_settings =  DB::table('gpg_settings')
            ->select('*')
            ->where('name', '=', '_ImgExt')
            ->get();    
            $file_types = explode(',', $file_type_settings[0]->value);
			//@@@@ Set attachments
			$file = Input::file('add_file');
			if (!empty($file[0])) {
			  	foreach ($file as $key => $value) {
			  		if (in_array($value->getClientOriginalExtension(), $file_types)) {
				  		$ext1 = explode(".",$value->getClientOriginalName());
				        $ext2 = end($ext1);
		        		$filename = "timesheet_doc_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
						$destinationPath = public_path().'/img/';
						$uploadSuccess = $value->move($destinationPath, $filename);
						//insert into db
						DB::table('gpg_timesheet_attachment')->insert(
					    array('GPG_timesheet_detail_id' =>$timesheet_detail_id ,'filename' => $filename ,'displayname' => $value->getClientOriginalName(),'created_on' => $date_time , 'modified_on' => $date_time )
						);		
			  		}

				}
			}
			// ####### End attachments 			
		}
		// for time sheet next multiplet enteries_++
		$i=1;
		while ($i <= $_POST['count_records']) {
			if (isset($_POST['JobNumber_'.$i])) {
				$JobNumber = $_POST['JobNumber_'.$i];
				
     			//@@@@ Query #1 in post
				$job_id =  DB::table('gpg_job')
	            ->select('id','task','contract_number')
	            ->where('gpg_job.job_num', '=', $JobNumber)
	            ->get(); 
	            $gpg_job_id = $job_id[0]->id;
	            $gpg_job_task = $job_id[0]->task;
	            $gpg_job_contract_number = $job_id[0]->contract_number;
	            // ####### End query#1

	            //@@@@ Query #1.1 in post
				$emp_type =  DB::table('gpg_employee')
	            ->select('GPG_employee_type_id')
	            ->where('gpg_employee.id', '=', $emp_id)
	            ->get(); 
	            $emp_type_id = $emp_type[0]->GPG_employee_type_id;
	            // ####### End query#1.1

	            //@@@@ Query #1.2 in post
	            $compare_job_num = strtolower(substr($JobNumber,0,2));
	          	$get_county = DB::select( DB::raw('SELECT DISTINCT c.id,county_name	
	          				FROM gpg_job_rates r, gpg_county c 
							WHERE r.gpg_county_id = c.id 
							AND (r.job_number = "'.$JobNumber.'" OR r.job_number = "'.$compare_job_num.'") 
							AND (contract_number = "'.$gpg_job_contract_number.'" OR IFNULL(contract_number,"")="")
							AND r.GPG_employee_type_id = "'.$emp_type_id.'"
						    AND (gpg_job_regarding = (SELECT task FROM gpg_job WHERE job_num = "'.$JobNumber.'") OR gpg_job_regarding = "~~ALL"
							OR IFNULL(gpg_job_regarding,"")="") ORDER BY c.county_name'));
	          	if (empty($get_county)) {
	          		$county_id = '0';
	          	}else
		          	$county_id = $get_county[0]->id;
	          	// ####### End query#1.2

				//@@@@ Query #1.3 in post
	            $wage_rate = DB::table('gpg_employee_wage')
		  		->select('rate')
	            ->where('gpg_employee_id', '=',$emp_id)
	            ->where('type', '=','h')
	            ->where('start_date', '<=', $proj_date)
	            ->orderBy('start_date', 'desc')
	          	->get();
	          	$perHourLabor = $wage_rate[0]->rate;
	            // ####### End query#1.3

	          	$time_type = $_POST['time_type_'.$i];
				$time_in_ = explode(" ",$_POST['time_in_'.$i]);
				$time_in = $time_in_[0];
				$time_out_ = explode(" ",$_POST['time_out_'.$i]);
				$time_out = $time_out_[0];
				$time_diff = explode(":",$_POST['time_differnce_'.$i]);
				$hrs = explode(" ",trim($time_diff[0])); 
				$mins = explode(" ",trim($time_diff[1])); 
				$time_differnce = $hrs[0].".".$mins[0];
	          	if (isset($_POST['recomend_text'.$i]))
	          		$recomend_text = $_POST['recomend_text'.$i];
	          	if(isset($_POST['daily_log_text'.$i]))
					$daily_log_text = $_POST['daily_log_text'.$i];
				if (isset($_POST['JobCheck_'.$i]))
					$JobCheck = 1;
				else
					$JobCheck = '';
	     		if (isset($_POST['type_task_'.$i]))
	     			$type_task = $_POST['type_task_'.$i];
	     		if (isset($_POST['project_activity_'.$i]))
	     			$project_activity = $_POST['project_activity_'.$i];
	     		if (isset($_POST['miles_driven'.$i])) {
	     			$miles_driven = $_POST['miles_driven'.$i];
	     		}
	     		//@@@@ Query #2 in post
				$gpg_job_rates = DB::table('gpg_job_rates')
		  		->select('*')
	            ->where('job_number', '=',$JobNumber)
	            ->where('gpg_task_type', '=',$type_task)
	            ->where('gpg_county_id', '=', $county_id)
	            ->where('GPG_employee_type_id', '=', $emp_type_id)
	            ->where('start_date', '<=', $proj_date)
	          	->get();
	 	        $pw_reg=0;
	          	if (isset($gpg_job_rates[0]->pw_reg)) {
	          		$pw_reg = $gpg_job_rates[0]->pw_reg;	
	          	}
				$pw_overtime=0;
	          	if (isset($gpg_job_rates[0]->pw_overtime)) {
	          		$pw_overtime = $gpg_job_rates[0]->pw_overtime;	
	          	}
	          	$pw_double=0;
	          	if (isset($gpg_job_rates[0]->pw_double)) {
	          		$pw_double = $gpg_job_rates[0]->pw_double;	
	          	}
				// ####### End query#2

				//@@@@ Pre Calculations #2.1 in post
				 $pw_ot=0;
				 $pw_dt=0;
				 $prevail =0;
				 $rH = $time_differnce;
				if ($pw_reg>0 && ($time_type == 1 || $time_type == 2)) {
					    $prevail = 1;
					    $pw_ot = ($pw_overtime>0?$pw_overtime:($pw_reg*1.5));
	                    $pw_dt = ($pw_double>0?$pw_double:($pw_reg*2));
	            }
	            $regWage = @round($rH*($prevail==1?$pw_reg:$perHourLabor),2);
	            $otWage = @round($ot_hrs*($prevail==1?$pw_ot:($perHourLabor*1.5)),2);
	            $dtWage = @round($dt_hrs*($prevail==1?$pw_dt:($perHourLabor*2)),2);
	            $totalWage = $regWage + $otWage + $dtWage;
				// ####### End Pre Calculations

	     		DB::table('gpg_timesheet_detail')->insert(
					    array('GPG_job_id' =>$gpg_job_id ,'GPG_timesheet_id' =>$ts_max_id  ,'job_num' =>$JobNumber ,'GPG_timetype_id'=> $time_type,
					    'time_in' =>$time_in  , 'time_out' =>$time_out ,'complete_flag' =>$JobCheck ,'workdone' =>$daily_log_text ,
					    'recommendations' =>$recomend_text ,'mileage' =>$miles_driven ,'time_diff_dec' =>$time_differnce ,'reg_hrs' =>$time_differnce ,
					    'ot_hrs' =>$ot_hrs ,'dt_hrs' =>$dt_hrs ,'reg_wage' => $regWage,'ot_wage' => $otWage,'dt_wage' => $dtWage,
					    'pw_reg_rate' =>  $pw_reg,'pw_ot_rate' => $pw_overtime, 'pw_dt_rate' => $pw_double, 'pw_flag' => $prevail, 
					    'total_wage' => $totalWage, 'labor_rate' => $perHourLabor, 'created_on' =>$date_time , 'modified_on' =>$date_time , 
					    'gpg_task_type' => $type_task, 'gpg_county_id' => $county_id, 'gpg_activity_id' => $project_activity)
				);
				//die("success");
				$timesheet_detail_id = DB::table('gpg_timesheet_detail')->max('id');
				$file_type_settings =  DB::table('gpg_settings')
	            ->select('*')
	            ->where('name', '=', '_ImgExt')
	            ->get();    
	            $file_types = explode(',', $file_type_settings[0]->value);
				//@@@@ Set attachments
				$file = Input::file('add_file_'.$i);
		        if (!empty($file)) {
				  	foreach ($file as $key => $value) {
				  		if (in_array($value->getClientOriginalExtension(), $file_types)) {
					  		$ext1 = explode(".",$value->getClientOriginalName());
					        $ext2 = end($ext1);
			        		$filename = "timesheet_doc_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
							$destinationPath = public_path().'/img/';
							$uploadSuccess = $value->move($destinationPath, $filename);
							//insert into db
							DB::table('gpg_timesheet_attachment')->insert(
						    array('GPG_timesheet_detail_id' =>$timesheet_detail_id ,'filename' => $filename ,'displayname' => $value->getClientOriginalName(),'created_on' => $date_time , 'modified_on' => $date_time )
							);		
				  		}

					}
				}
			}	
			$i++;
		}

		return Redirect::to('timesheet');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function show($id)
	{
		$modules = Generic::modules();
  		//$timesheet = TimeSheet::find($id);
		$timesheet =  DB::table('gpg_timesheet')
            ->select('gpg_employee.*', 'gpg_timesheet.*','gpg_employee_type.*','gpg_timesheet_detail.*')
            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_id', '=', 'gpg_employee.id')
            ->join('gpg_employee_type', 'gpg_employee_type.type_id', '=', 'gpg_employee.GPG_employee_type_id')
            ->join('gpg_timesheet_detail', 'gpg_timesheet_detail.GPG_timesheet_id', '=', 'gpg_timesheet.id')
            ->where('gpg_timesheet.id', '=', $id)
            ->where('gpg_employee.id', '=', Input::get('emp_id'))
            ->where('gpg_timesheet.date', '=', Input::get('date'))
            ->get();
      	$result = array();
		foreach ($timesheet as $key => $value) {
   			 foreach ($value as $key1 => $value1) {
 	  			 $result[$key1] = $value1;
   			 }
 		}
		/*$queries = DB::getQueryLog();
			$last_query = end($queries);
			var_dump( $last_query);*/
  		$params = array('left_menu' => $modules, 'timesheet'=> $result);
  		return View::make('timesheet.show', $params);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function checkEditable()
	{
		$page = Input::get('page');	
		$lock = Input::get('lock');
      	if ($lock == 0)
      		$ed_lock = 0;
      	else
      		$ed_lock = 1;
      	$timesheet =  DB::table('gpg_timesheet')
            ->where('gpg_timesheet.id', '=', Input::get('id'))
			->Update(array('ed_lock' => $ed_lock));
		return Redirect::to('timesheet?page='.$page.'');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function setProjectTaskArrays()
	{
		$JobNumber = Input::get('gpg_job_num');
		$count = Input::get('count');
		$type_task_id = Input::get('type_task_id');
		$proj_actv_id  = Input::get('proj_actv_id');
		if (empty($type_task_id)) 
              $type_task_id = 0;
        if (empty($proj_actv_id))
              $proj_actv_id = 0;

		if (!empty($count)) {
			$count = "_".$count;
		}

		$etype =  DB::table('gpg_employee_type')
            ->select('gpg_employee_type.*', 'gpg_employee.GPG_employee_type_id','gpg_employee.id')
            ->join('gpg_employee', 'gpg_employee_type.type_id', '=', 'gpg_employee.GPG_employee_type_id')
            ->where('gpg_employee.id', '=', Input::get('emp_id'))
            ->get();
      	$emp_type = "";
		foreach ($etype as $key => $value) {
   			 foreach ($value as $key1 => $value1) {
 	  			 if ($key1 == 'type') {
 	  			 	$emp_type = $value1;
 	  			 }
   			 }
 		}
 		$jcno =  DB::table('gpg_job')
            ->select('gpg_job.contract_number')
            ->where('gpg_job.job_num', '=', Input::get('gpg_job_num'))
            ->get();
      	$job_contract_number = 0;
		foreach ($jcno as $key3 => $value3) {
   			 foreach ($value3 as $key4 => $value4) {
 	  			 if ($key4 == 'contract_number' && $value4!=NULL ) {
 	  			 	$job_contract_number = $value4;
 	  			 }
   			 }
 		}
		$search = DB::select( DB::raw('SELECT DISTINCT(gpg_task_type),task_type FROM gpg_job_rates, gpg_task_types 
			WHERE gpg_task_types.id = gpg_job_rates.gpg_task_type AND (gpg_job_rates.job_number = "'.Input::get('gpg_job_num').'" OR gpg_job_rates.job_number = "'.Input::get('gpg_job_num').'") AND gpg_job_rates.GPG_employee_type_id = "'.$emp_type.'" 
			AND (contract_number = "'.$job_contract_number.'" OR IFNULL(contract_number,"")="")
			AND (gpg_job_regarding = (SELECT task FROM gpg_job WHERE job_num = "'.Input::get('gpg_job_num').'") OR gpg_job_regarding = "~~ALL" 
			OR IFNULL(gpg_job_regarding,"")="") GROUP BY gpg_job_rates.gpg_task_type'));
		$task_type_string = "<b>Type of Task:</b> <select class='form-control m-bot15' id='type_task".$count."' name='type_task".$count."' >";
		$gpg_task_type = "";
		foreach ($search as $key5 => $value5) {
   			 foreach ($value5 as $key6 => $value6) {
   			 	if ($key6 == 'gpg_task_type') {
   			 		$gpg_task_type = $value6;
   			 	}
   			 	if ($key6 == 'task_type') {
   			 		if ($gpg_task_type == $type_task_id) {
		 	  			$task_type_string .= "<option selected='selected' value='".$gpg_task_type."'>'".$key6."'</option>";   			 		   			 				
   			 		}
   			 		else		
		 	  			$task_type_string .= "<option value='".$gpg_task_type."'>'".$key6."'</option>";   			 		
   			 	}

   			 }
 		}
 		$task_type_string .="</select>";
 		$p_activity =  DB::table('gpg_job_project')
            ->select('gpg_job_project.*')
            ->where('gpg_job_project.GPG_job_num', '=', Input::get('gpg_job_num'))
            ->orderBy('gpg_job_project.task_type', 'asc')
	        ->orderBy('gpg_job_project.title', 'asc')
            ->get();

        $previous_task_type="";
        $return_str = "<b>Projects Activity:</b> <select class='form-control m-bot15' id='project_activity".$count."' name='project_activity".$count."' >";
        $id=0;   
   		foreach ($p_activity as $key7 => $value7) {
   			 foreach ($value7 as $key8 => $value8) {
   			 	if ($key8 == 'id') {
   			 		$id = $value8;
   			 	}
  			 	if ($key8 == 'task_type') {
	   			 	if ($previous_task_type != $value8) {
	                	$return_str .=  "</optgroup><optgroup label='".$value8."'>";
	                	$previous_task_type = $value8;
	                }

	                if ( $id == $proj_actv_id) {
	                	$return_str .= '<option selected="selected" value="' . $id . '">' . str_replace('"', '',$value7->title) . '</option>';   			 			                	
	                }
	                else{
						$return_str .= '<option value="' . $id . '">' . str_replace('"', '',$value7->title) . '</option>';   			 		
	                }
   			 	}
   			 }
 		}
 		$return_str .= "</select>";
 		$finalArray = array('task_type_options' => $task_type_string, 'project_activity_options' => $return_str);
 		return $finalArray;
		
		/*return Redirect::to('timesheet?page='.$page.'');*/
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		set_time_limit(0);
		$modules = Generic::modules();
		$timesheet =  DB::table('gpg_timesheet')
            ->select('gpg_employee.*', 'gpg_timesheet.*', 'gpg_timesheet_detail.*')
            ->join('gpg_employee', 'gpg_timesheet.GPG_employee_id', '=', 'gpg_employee.id')
            ->join('gpg_timesheet_detail', 'gpg_timesheet_detail.GPG_timesheet_id', '=', 'gpg_timesheet.id')
            ->where('gpg_timesheet.id', '=', $id)
            ->where('gpg_employee.id', '=', Input::get('emp_id'))
            ->where('gpg_timesheet.date', '=', Input::get('date'))
            ->get();
        $result = array();
        $comp_results = array();
		foreach ($timesheet as $key => $value) {
   			 foreach ($value as $key1 => $value1) {
 	  			 $result[$key1] = $value1;
   			 }
   			 $comp_results[] = $result;
 		}
 		
      	$time_type_arr = array();
 		$time_type =  DB::table('gpg_timetype')
        ->select('gpg_timetype.id', 'gpg_timetype.name')
        ->where('gpg_timetype.status', '=', '1')
        ->get();
		foreach ($time_type as $key2 => $value2) {
   			 foreach ($value2 as $key3 => $value3) {
 					if ($key3 == 'id') {
 						$id =  $value3;
 					}
 					else
 						$time_type_arr[$id] = $value3;
   			 }
 		}
 		$f_attachs  = array();
 		$attachments =  DB::table('gpg_timesheet_attachment')
        ->select('id', 'displayname' , 'filename')
        ->where('gpg_timesheet_detail_id', '=', $timesheet[0]->id)
        ->get();
        foreach ($attachments as $key => $value) {
        	$f_attachs['files'][] = array('fname' => $value->displayname, 'flink'=>$value->filename);
        }
    	$params = array('left_menu' => $modules, 'timesheet'=>$comp_results, 'time_type'=>$time_type_arr, 'file_attachments' => json_encode($f_attachs));
 		return View::make('timesheet.edit', $params);
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
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateTimesheet()
	{
		$emp_id = Input::get('emp_id');
        $proj_date = Input::get('date');
        $time_sheet_id = Input::get('GPG_timesheet_id');
        $date_time = date('Y-m-d H:i:s');
		/**************
			Pre-Queries	
		*/
      	DB::table('gpg_timesheet')
          ->where('id', $time_sheet_id)
          ->update(array('modified_on' => $date_time));
		/*pre-queries ends here! */

		$JobCheck ='0';
		$time_type ='0';
		$project_activity ='0';
		$recomend_text ='0';
		$daily_log_text = '0';
		$miles_driven ='0';
		$type_task ='0';
		$ot_hrs = '0.00';
		$dt_hrs = '0.00';
		// for time sheet first entery
		if (isset($_POST['JobNumber'])) {
			$JobNumber = $_POST['JobNumber'];
			
			//@@@@ Query #1 in post
			$job_id =  DB::table('gpg_job')
            ->select('id','task','contract_number')
            ->where('gpg_job.job_num', '=', $_POST['JobNumber'])
            ->get(); 
            $gpg_job_id = $job_id[0]->id;
            $gpg_job_task = $job_id[0]->task;
            $gpg_job_contract_number = $job_id[0]->contract_number;
            // ####### End query#1

            //@@@@ Query #1.1 in post
			$emp_type =  DB::table('gpg_employee')
            ->select('GPG_employee_type_id')
            ->where('gpg_employee.id', '=', $emp_id)
            ->get(); 
            $emp_type_id = $emp_type[0]->GPG_employee_type_id;
            // ####### End query#1.1

            //@@@@ Query #1.2 in post
            $get_county = DB::table('gpg_timesheet_detail')
	  		->select('gpg_timesheet_detail.*')
            ->join('gpg_timesheet', 'gpg_timesheet_detail.GPG_timesheet_id', '=', 'gpg_timesheet.id')
            ->where('gpg_timesheet.date', '=',$proj_date)
            ->where('gpg_timesheet.GPG_employee_id', '=',$emp_id)
            ->orderBy('gpg_timesheet_detail.time_in', 'asc')
          	->get();
          	$county_id = $get_county[0]->gpg_county_id;
          	// ####### End query#1.2

			//@@@@ Query #1.3 in post
            $wage_rate = DB::table('gpg_employee_wage')
	  		->select('rate')
            ->where('gpg_employee_id', '=',$emp_id)
            ->where('type', '=','h')
            ->where('start_date', '<=', $proj_date)
            ->orderBy('start_date', 'desc')
          	->get();
          	$perHourLabor = $wage_rate[0]->rate;
            // ####### End query#1.3

	 		$time_type = $_POST['time_type'];
			$time_in_ = explode(" ",$_POST['time_in']);
			$time_in = $time_in_[0];
			$time_out_ = explode(" ",$_POST['time_out']);
			$time_out = $time_out_[0];
			$time_diff = explode(":",$_POST['time_differnce']);
			if (!isset($time_diff[1])) {
				$time_differnce = explode(" ",trim($time_diff[0]));

			}else{
				$hrs = explode(" ",trim($time_diff[0])); 
				$mins = explode(" ",trim($time_diff[1])); 
				$time_differnce = $hrs[0].".".$mins[0];
			}

			if (isset($_POST['recomend_text']))
          		$recomend_text = $_POST['recomend_text'];
          	if(isset($_POST['daily_log_text']))
				$daily_log_text = $_POST['daily_log_text'];
			if (isset($_POST['JobCheck']))
				$JobCheck = 1;
     		if (isset($_POST['type_task']))
     			$type_task = $_POST['type_task'];
     		if (isset($_POST['project_activity']))
     			$project_activity = $_POST['project_activity'];
     		if (isset($_POST['miles_driven'])) {
     			$miles_driven = $_POST['miles_driven'];
     		}
     		//@@@@ Query #2 in post
			$gpg_job_rates = DB::table('gpg_job_rates')
	  		->select('*')
            ->where('job_number', '=',$JobNumber)
            ->where('gpg_task_type', '=',$type_task)
            ->where('gpg_county_id', '=', $county_id)
            ->where('GPG_employee_type_id', '=', $emp_type_id)
            ->where('start_date', '<=', $proj_date)
          	->get();
 	        $pw_reg=0;
          	if (isset($gpg_job_rates[0]->pw_reg)) {
          		$pw_reg = $gpg_job_rates[0]->pw_reg;	
          	}
			$pw_overtime=0;
          	if (isset($gpg_job_rates[0]->pw_overtime)) {
          		$pw_overtime = $gpg_job_rates[0]->pw_overtime;	
          	}
          	$pw_double=0;
          	if (isset($gpg_job_rates[0]->pw_double)) {
          		$pw_double = $gpg_job_rates[0]->pw_double;	
          	}
			// ####### End query#2

			//@@@@ Pre Calculations #2.1 in post
			 $pw_ot=0;
			 $pw_dt=0;
			 $prevail =0;
			 $rH = $time_differnce;
			if ($pw_reg>0 && ($time_type == 1 || $time_type == 2)) {
				    $prevail = 1;
				    $pw_ot = ($pw_overtime>0?$pw_overtime:($pw_reg*1.5));
                    $pw_dt = ($pw_double>0?$pw_double:($pw_reg*2));
            }
            $regWage = @round($rH[0]*($prevail==1?$pw_reg:$perHourLabor),2);
            $otWage = @round($ot_hrs*($prevail==1?$pw_ot:($perHourLabor*1.5)),2);
            $dtWage = @round($dt_hrs*($prevail==1?$pw_dt:($perHourLabor*2)),2);
            $totalWage = $regWage + $otWage + $dtWage;
           // ####### End Pre Calculations
           
           $abcb = DB::table('gpg_timesheet_detail')
		  		->where('GPG_timesheet_id', $time_sheet_id)
     			->delete();
	          

     		DB::table('gpg_timesheet_detail')
     		->insert(array('GPG_job_id' =>$gpg_job_id ,'GPG_timesheet_id' =>$time_sheet_id  ,'job_num' =>$JobNumber , 'GPG_timetype_id'=> $time_type,
					    'time_in' =>$time_in  , 'time_out' =>$time_out ,'complete_flag' =>$JobCheck ,'workdone' =>$daily_log_text ,
					    'recommendations' =>$recomend_text ,'mileage' =>$miles_driven ,'time_diff_dec' =>$time_differnce ,'reg_hrs' =>$time_differnce ,
					    'ot_hrs' =>$ot_hrs ,'dt_hrs' =>$dt_hrs ,'reg_wage' => $regWage,'ot_wage' => $otWage,'dt_wage' => $dtWage,
					    'pw_reg_rate' =>  $pw_reg,'pw_ot_rate' => $pw_overtime, 'pw_dt_rate' => $pw_double, 'pw_flag' => $prevail, 
					    'total_wage' => $totalWage, 'labor_rate' => $perHourLabor, 'created_on' =>$date_time , 'modified_on' =>$date_time , 
					    'gpg_task_type' => $type_task, 'gpg_county_id' => $county_id, 'gpg_activity_id' => $project_activity)
			);
			//die("success");

			$timesheet_detail_ids = DB::table('gpg_timesheet_detail')
			->select('id')
            ->where('GPG_timesheet_id', '=', $time_sheet_id)
            ->get();    
            $timesheet_detail_id = $timesheet_detail_ids[0]->id;
			
			$file_type_settings =  DB::table('gpg_settings')
            ->select('*')
            ->where('name', '=', '_ImgExt')
            ->get();    
            $file_types = explode(',', $file_type_settings[0]->value);
			//@@@@ Set attachments
			$file = Input::file('add_file');
			if (!empty($file[0])) {
			  	foreach ($file as $key => $value) {
			  		if (in_array($value->getClientOriginalExtension(), $file_types)) {
				  		$ext1 = explode(".",$value->getClientOriginalName());
				        $ext2 = end($ext1);
		        		$filename = "timesheet_doc_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
						$destinationPath = public_path().'/img/';
						$uploadSuccess = $value->move($destinationPath, $filename);
						//insert into db
						DB::table('gpg_timesheet_attachment')->insert(
					    array('GPG_timesheet_detail_id' =>$timesheet_detail_id ,'filename' => $filename ,'displayname' => $value->getClientOriginalName(),'created_on' => $date_time , 'modified_on' => $date_time )
						);		
			  		}

				}
			}
			// ####### End attachments 			
		}
		// for time sheet next multiplet enteries_++
		$i=1;
		while ($i <= $_POST['count_records']) {
			if (isset($_POST['JobNumber_'.$i])) {
				$JobNumber = $_POST['JobNumber_'.$i];
				
     			//@@@@ Query #1 in post
				$job_id =  DB::table('gpg_job')
	            ->select('id','task','contract_number')
	            ->where('gpg_job.job_num', '=', $JobNumber)
	            ->get(); 
	            $gpg_job_id = $job_id[0]->id;
	            $gpg_job_task = $job_id[0]->task;
	            $gpg_job_contract_number = $job_id[0]->contract_number;
	            // ####### End query#1

	            //@@@@ Query #1.1 in post
				$emp_type =  DB::table('gpg_employee')
	            ->select('GPG_employee_type_id')
	            ->where('gpg_employee.id', '=', $emp_id)
	            ->get(); 
	            $emp_type_id = $emp_type[0]->GPG_employee_type_id;
	            // ####### End query#1.1

	            //@@@@ Query #1.2 in post
	            $get_county = DB::table('gpg_timesheet_detail')
		  		->select('gpg_timesheet_detail.*')
	            ->join('gpg_timesheet', 'gpg_timesheet_detail.GPG_timesheet_id', '=', 'gpg_timesheet.id')
	            ->where('gpg_timesheet.date', '=',$proj_date)
	            ->where('gpg_timesheet.GPG_employee_id', '=',$emp_id)
	            ->orderBy('gpg_timesheet_detail.time_in', 'asc')
	          	->get();
	          	$county_id = $get_county[0]->gpg_county_id;
	          	// ####### End query#1.2

				//@@@@ Query #1.3 in post
	            $wage_rate = DB::table('gpg_employee_wage')
		  		->select('rate')
	            ->where('gpg_employee_id', '=',$emp_id)
	            ->where('type', '=','h')
	            ->where('start_date', '<=', $proj_date)
	            ->orderBy('start_date', 'desc')
	          	->get();
	          	$perHourLabor = $wage_rate[0]->rate;
	            // ####### End query#1.3

	          	$time_type = $_POST['time_type_'.$i];
				$time_in_ = explode(" ",$_POST['time_in_'.$i]);
				$time_in = $time_in_[0];
				$time_out_ = explode(" ",$_POST['time_out_'.$i]);
				$time_out = $time_out_[0];
				$time_diff = explode(":",$_POST['time_differnce_'.$i]);
				$hrs = explode(" ",trim($time_diff[0])); 
				$mins = explode(" ",trim($time_diff[1])); 
				$time_differnce = $hrs[0].".".$mins[0];
	          	if (isset($_POST['recomend_text'.$i]))
	          		$recomend_text = $_POST['recomend_text'.$i];
	          	if(isset($_POST['daily_log_text'.$i]))
					$daily_log_text = $_POST['daily_log_text'.$i];
				if (isset($_POST['JobCheck_'.$i]))
					$JobCheck = 1;
				else
					$JobCheck = '';
	     		if (isset($_POST['type_task_'.$i]))
	     			$type_task = $_POST['type_task_'.$i];
	     		if (isset($_POST['project_activity_'.$i]))
	     			$project_activity = $_POST['project_activity_'.$i];
	     		if (isset($_POST['miles_driven'.$i])) {
	     			$miles_driven = $_POST['miles_driven'.$i];
	     		}
	     		//@@@@ Query #2 in post
				$gpg_job_rates = DB::table('gpg_job_rates')
		  		->select('*')
	            ->where('job_number', '=',$JobNumber)
	            ->where('gpg_task_type', '=',$type_task)
	            ->where('gpg_county_id', '=', $county_id)
	            ->where('GPG_employee_type_id', '=', $emp_type_id)
	            ->where('start_date', '<=', $proj_date)
	          	->get();
	 	        $pw_reg=0;
	          	if (isset($gpg_job_rates[0]->pw_reg)) {
	          		$pw_reg = $gpg_job_rates[0]->pw_reg;	
	          	}
				$pw_overtime=0;
	          	if (isset($gpg_job_rates[0]->pw_overtime)) {
	          		$pw_overtime = $gpg_job_rates[0]->pw_overtime;	
	          	}
	          	$pw_double=0;
	          	if (isset($gpg_job_rates[0]->pw_double)) {
	          		$pw_double = $gpg_job_rates[0]->pw_double;	
	          	}
				// ####### End query#2

				//@@@@ Pre Calculations #2.1 in post
				 $pw_ot=0;
				 $pw_dt=0;
				 $prevail =0;
				 $rH = $time_differnce;
				if ($pw_reg>0 && ($time_type == 1 || $time_type == 2)) {
					    $prevail = 1;
					    $pw_ot = ($pw_overtime>0?$pw_overtime:($pw_reg*1.5));
	                    $pw_dt = ($pw_double>0?$pw_double:($pw_reg*2));
	            }
	            $regWage = round($rH*($prevail==1?$pw_reg:$perHourLabor),2);
	            $otWage = round($ot_hrs*($prevail==1?$pw_ot:($perHourLabor*1.5)),2);
	            $dtWage = round($dt_hrs*($prevail==1?$pw_dt:($perHourLabor*2)),2);
	            $totalWage = $regWage + $otWage + $dtWage;
				// ####### End Pre Calculations
	            
	           	DB::table('gpg_timesheet_detail')->insert(
					    array('GPG_job_id' =>$gpg_job_id ,'GPG_timesheet_id' =>$time_sheet_id  ,'job_num' =>$JobNumber , 'GPG_timetype_id'=> $time_type,
					    'time_in' =>$time_in  , 'time_out' =>$time_out ,'complete_flag' =>$JobCheck ,'workdone' =>$daily_log_text ,
					    'recommendations' =>$recomend_text ,'mileage' =>$miles_driven ,'time_diff_dec' =>$time_differnce ,'reg_hrs' =>$time_differnce ,
					    'ot_hrs' =>$ot_hrs ,'dt_hrs' =>$dt_hrs ,'reg_wage' => $regWage,'ot_wage' => $otWage,'dt_wage' => $dtWage,
					    'pw_reg_rate' =>  $pw_reg,'pw_ot_rate' => $pw_overtime, 'pw_dt_rate' => $pw_double, 'pw_flag' => $prevail, 
					    'total_wage' => $totalWage, 'labor_rate' => $perHourLabor, 'created_on' =>$date_time , 'modified_on' =>$date_time , 
					    'gpg_task_type' => $type_task, 'gpg_county_id' => $county_id, 'gpg_activity_id' => $project_activity)
				);
				//die("success");
				$timesheet_detail_id = DB::table('gpg_timesheet_detail')->max('id');
				$file_type_settings =  DB::table('gpg_settings')
	            ->select('*')
	            ->where('name', '=', '_ImgExt')
	            ->get();    
	            $file_types = explode(',', $file_type_settings[0]->value);
				//@@@@ Set attachments
				$file = Input::file('add_file_'.$i);
		        if (!empty($file[0])) {
				  	foreach ($file as $key => $value) {
				  		if (in_array($value->getClientOriginalExtension(), $file_types)) {
					  		$ext1 = explode(".",$value->getClientOriginalName());
					        $ext2 = end($ext1);
			        		$filename = "timesheet_doc_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
							$destinationPath = public_path().'/img/';
							$uploadSuccess = $value->move($destinationPath, $filename);
							//insert into db
							DB::table('gpg_timesheet_attachment')->insert(
						    array('GPG_timesheet_detail_id' =>$timesheet_detail_id ,'filename' => $filename ,'displayname' => $value->getClientOriginalName(),'created_on' => $date_time , 'modified_on' => $date_time )
							);		
				  		}

					}
				}
			}	
			$i++;
		}

		return Redirect::to('timesheet');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$gpg_ts_detail = DB::table('gpg_timesheet_detail')
		  		->where('GPG_timesheet_id', '=',$id)
	          	->delete();
		TimeSheet::find($id)->delete();
        return Redirect::route('timesheet.index');
	}


}
