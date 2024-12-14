<?php

class HolidayController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getByPage($page, 10);
  		$query_data = Paginator::make($data->items, $data->totalItems, 10);
		$params = array('left_menu' => $modules, 'query_data'=>$query_data);
 		return View::make('holiday.index', $params);
	}
	/*
	* paginator for index holiday management	
	*/
	public function getByPage($page = 1, $limit = 10)
	{
	  $results = new \StdClass;
	  $results->page = $page;
	  $results->limit = $limit;
	  $results->totalItems = 0;
	  $results->items = array();
	 
	  $query_count = DB::table('gpg_holiday')
            ->select('*')
			->count();

	  $query = DB::table('gpg_holiday')
	  		->select('*')
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
		$modules = Generic::modules();
		$params = array('left_menu' => $modules/*, 'query_data'=>$query_data*/);
 		return View::make('holiday.create', $params);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		DB::table('gpg_holiday')
     		->insert(array('name' =>Input::get('holiday_desc'), 'date'=>Input::get('DOB')));
		return Redirect::route('holiday.index');
	}

	/**
	 * Manage Leaves.
	 *
	 * @return Response
	 */
	public function manageLeaves()
	{
		if (!empty($_POST))
			Session::forget('DSQL');
		$modules = Generic::modules();
		$page = Input::get('page', 1);
		$data = $this->getBySearch($page, 10);
		$query_data = Paginator::make($data->items, $data->totalItems, 10);
		$params = array('left_menu' => $modules, 'query_data'=>$query_data);
 		return View::make('holiday.manage_leaves', $params);
	}
	/*
	* Search managed leaves
	*/
	public function getBySearch($page = 1, $limit = 10)
	{
	  $results = new \StdClass;
	  $results->page = $page;
	  $results->limit = $limit;
	  $results->totalItems = 0;
	  $results->items = array();
	  $items_arr = array();
      $start = $limit * ($page - 1);
      $SDate = "";
	  $EDate = "";
	  $Filter = "";
	  $FVal = "";
	  $filter_status = "";
	  $DSQL = "";
	  $db_tb = "";
	  $select_emp = "";
      $DQ2 = " order by created_on desc ";
      
        if (isset($_POST['SDate']))
	        $SDate = $_POST['SDate'];
	    if (isset($_POST['EDate']))
			$EDate = $_POST['EDate'];
		if (isset($_POST['filter_val']))
			$Filter = $_POST['filter_val'];
		if (isset($_POST['FVal']))
			$FVal = $_POST['FVal'];
		if (isset($_POST['filter_status']))
			$filter_status = $_POST['filter_status'];

		if (!empty($SDate) || !empty($EDate)) {

			if (!empty($SDate) && empty($EDate)) {
				  $DSQL.= " AND DATE_FORMAT(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif (empty($SDate) && !empty($EDate)) {
				  $DSQL.= " AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif (!empty($SDate) && !empty($EDate)) {
				  $DSQL.= " AND (created_on >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
				            AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
		}
		
		if (!empty($Filter) && (!empty($FVal) || !empty($filter_status))) {
		
		    if ($Filter !="status" and $Filter!="new_member") {
		    	$db_tb = ",gpg_employee";
		    	 $select_emp = ", gpg_employee.name, gpg_employee.login";
		    	$DSQL.= " AND gpg_employee.id=gpg_leaveapp.gpg_employee_id AND $Filter like '%$FVal%'"; 
		    }
			elseif ($Filter =="status") $DSQL.= " AND $Filter = '$filter_status'"; 
			elseif ($Filter =="new_member") { 
			    $DQ2= " order by created_on desc ";
			}	
  		}
		
		if (empty($DSQL)) {
			$DSQL = 	Session::get('DSQL');
		}else{
			Session::put('DSQL', $DSQL);	
		}

	    $query_count = DB::select( DB::raw("SELECT  count(gpg_leaveapp.id) as total_count $select_emp FROM gpg_leaveapp $db_tb WHERE 1 $DSQL"));
	    $results->totalItems = $query_count[0]->total_count;
	  	$qry_data =  DB::select( DB::raw("select gpg_leaveapp.* $select_emp from gpg_leaveapp $db_tb WHERE 1 $DSQL $DQ2 limit $start,$limit"));
	  	foreach ($qry_data as $key => $data){
	  		$query = DB::table('gpg_employee')
	  	 		->select('name')
	  	  		->where('id','=',$data->gpg_employee_id)
	  			->get();
	  		$items_arr[] = array('id' =>$data->id ,'gpg_employee_id' =>$data->gpg_employee_id,'emp_name'=>$query[0]->name ,'off_type' =>$data->off_type ,'leave_date' =>$data->leave_date ,'start_time' =>$data->start_time,'end_time' =>$data->end_time,'hours' =>$data->hours,'status' =>$data->status,'description' =>$data->description,'created_on' =>$data->created_on,'modified_on' =>$data->modified_on);
	  	}
	  	$results->items = $items_arr;	
		return $results;
	}
	/*
	* Update Status for Leave application
	*/
	public function updateStatus(){
		$id = Input::get('id');
		$status = Input::get('status');

		DB::table('gpg_leaveapp')
          ->where('id','=', $id)
          ->update(array('status' =>$status ));
        return 1;  
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
	 * Show the form for editing the vacation sicks.
	 *
	 * @return Response
	 */
	public function editVacationSick()
	{
		if (!empty($_POST))
			Session::forget('DSQL');
		$modules = Generic::modules();
		$page = Input::get('page', 1);
		$data = $this->getSearchForSickVacations($page, 100);
		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules, 'query_data'=>$query_data);
 		return View::make('holiday.edit_vacation_sick', $params);
	}
	/*
	* Search managed leaves
	*/
	public function getSearchForSickVacations($page = 1, $limit = 100)
	{
	  $results = new \StdClass;
	  $results->page = $page;
	  $results->limit = $limit;
	  $results->totalItems = 0;
	  $results->items = array();
	  $items_arr = array();
      $start = $limit * ($page - 1);
      $SDate = "";
	  $EDate = "";
	  $Filter = "";
	  $FVal = "";
	  $filter_status = "";
	  $DSQL = "";
	  $db_tb = "";
	  $select_emp = "";
      $DQ2 = " order by created_on desc ";
      
        if (isset($_POST['SDate']))
	        $SDate = $_POST['SDate'];
	    if (isset($_POST['EDate']))
			$EDate = $_POST['EDate'];
		if (isset($_POST['filter_val']))
			$Filter = $_POST['filter_val'];
		if (isset($_POST['FVal']))
			$FVal = $_POST['FVal'];
		if (isset($_POST['filter_status']))
			$filter_status = $_POST['filter_status'];

		if (!empty($SDate) || !empty($EDate)) {

			if (!empty($SDate) && empty($EDate)) {
				  $DSQL.= " AND DATE_FORMAT(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif (empty($SDate) && !empty($EDate)) {
				  $DSQL.= " AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif (!empty($SDate) && !empty($EDate)) {
				  $DSQL.= " AND (created_on >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
				            AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
		}
		
		if (!empty($Filter) && (!empty($FVal) || !empty($filter_status))) {
		
		    if ($Filter !="status" and $Filter!="new_member")
		  		$DSQL.= " AND $Filter like '%$FVal%'"; 
			elseif ($Filter =="status") 
				$DSQL.= " AND $Filter = '$filter_status'"; 
			elseif ($Filter =="new_member") 
			    $DQ2= " order by created_on desc ";
  		}
		
		if (empty($DSQL))
			$DSQL = Session::get('DSQL');
		else
			Session::put('DSQL', $DSQL);	
		
	    $query_count = DB::select( DB::raw("SELECT  count(id) as total_count FROM gpg_employee WHERE 1 $DSQL"));
	    $results->totalItems = $query_count[0]->total_count;
	  	$qry_data =  DB::select( DB::raw("SELECT * from gpg_employee WHERE 1 $DSQL $DQ2 limit $start,$limit"));
	  	foreach ($qry_data as $key => $data)
	  		$items_arr[] = array('id' =>$data->id ,'name' =>$data->name,'vacation'=>$data->vacation ,'sick' =>$data->sick);
	  	$results->items = $items_arr;	
		return $results;
	}
	/*
	* updateBalance
	*/
	public function updateBalance(){
		if (isset($_POST['empid'])) {
			foreach ($_POST['empid'] as $arrIndex => $emp_id) {
				if (!empty($_POST['vacation'][$arrIndex]) || !empty($_POST['sick'][$arrIndex])) {
					DB::table('gpg_employee')
			          ->where('id','=', $emp_id)
			          ->update(array('vacation' =>$_POST['vacation'][$arrIndex] ,'sick' =>$_POST['sick'][$arrIndex]));		
				}
			}			
		}
		return Redirect::to('holiday/edit_vacation_sick');
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
	 * Update the Holiday information
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateHolidayInfo()
	{
		$id = Input::get('id');
		$desc = Input::get('desc');
		$date = Input::get('date');

		DB::table('gpg_holiday')
          ->where('id','=', $id)
          ->update(array('name' =>$desc ,'date' =>$date ,'modified_on' =>date("Y-m-d H:i:s")));
		return Redirect::route('holiday.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$gpg_holiday = DB::table('gpg_holiday')
		  		->where('id', '=',$id)
	          	->delete();
        return Redirect::route('holiday.index');
	}


}
