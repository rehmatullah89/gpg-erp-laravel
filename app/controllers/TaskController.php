<?php

class TaskController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$head_id = Auth::user()->ad_id;
		$Department = Input::get("Department");
		$queryPart='';
		if ($SDate!="" and $EDate!="") $queryPart .= " AND a.start_date >= '".date('Y-m-d',strtotime($SDate))."' AND a.start_date <= '".date('Y-m-d',strtotime($EDate))."' ";
		elseif ($SDate!="") $queryPart .= " AND a.start_date = '".date('Y-m-d',strtotime($SDate))."'";
		if ($Filter!="" && ($FVal!="" || $Department!="")){
		    if ($Filter =="Department") 
			   $queryPart.= " AND a.gpg_department_id='". $Department."'"; 
		}
		if(Auth::user()->ad_id == 1){
			$query="select *, id as task_id,
				(select dept_name from gpg_department where id = gpg_department_id ) as dept_name,
				if(task_creator_type='admin',(select concat(fname,' ',lname) from gpg_ad_acc where ad_id = a.task_creator_id ),(select name from gpg_employee where id = a.task_creator_id )) as task_creator,
				(select name from gpg_employee where id = gpg_employee_id ) as emp_name from gpg_task a 
				where ifnull(status,'')<>'A' ".$queryPart." order by id desc";
		}else{	
			$query="select *, a.id as task_id,
				(select dept_name from gpg_department where id = a.gpg_department_id ) as dept_name,
				if(task_creator_type='admin',(select concat(fname,' ',lname) from gpg_ad_acc where ad_id = task_creator_id ),(select name from gpg_employee where id = task_creator_id )) as task_creator,
				(select name from gpg_employee where id = a.gpg_employee_id ) as emp_name 
				from gpg_task a, gpg_department_user b where a.gpg_department_id = b.gpg_department_id and b.gpg_employee_id_head='$head_id' and ifnull(a.status,'')<>'A' ".$queryPart." group by a.id order by a.id desc";
		}
		$qry = DB::select(DB::raw($query));
		$query_data = array();
		foreach ($qry as $key => $value) {			
			$query_data[] = (array)$value;
		}
		$customers = DB::table('gpg_customer')->orderBy('name')->lists('name','id');
		$depts = DB::table('gpg_department')->orderBy('dept_name')->lists('dept_name','id');
		$params = array('left_menu' => $modules,'depts'=>$depts,'query_data'=>$query_data,'customers'=>$customers);
		return View::make('task.index', $params);
	}
	public function getModalInfo(){
		$id = Input::get('id');
		$emps_list = '<select class="form-control" id="assignTo" name="assignTo"><option value="">Select Employee</option>';
		$qry = DB::table('gpg_task')->select('*')->where('id','=',$id)->get();
		$q2 = DB::select(DB::raw("select gpg_employee_id,(select name from gpg_employee where id=gpg_employee_id) as name from gpg_department_user where gpg_department_id=".$qry[0]->gpg_department_id.""));		
		foreach ($q2 as $key2 => $value2) {
			$emps_list .= '<option value="'.$value2->gpg_employee_id.'">'.$value2->name.'</option>';
		}
		$emps_list .='</select>';
		return array('id'=>$qry[0]->id,'customer'=>$qry[0]->gpg_customer_id,'tasktype'=>$qry[0]->task_type,'recuring'=>$qry[0]->recuring,'priority'=>$qry[0]->task_priority,'endDate'=>$qry[0]->expected_end_date,'POjobNum'=>$qry[0]->job_num,'task_detail'=>$qry[0]->task_detail,'assignTo'=>$emps_list);
	}
	public function updateInfoModal(){
		$id = Input::get('task_id');
		$endDate = Input::get('endDate');
		$tasktype = Input::get('tasktype');
		$recuring = Input::get('recuring');
		DB::table('gpg_task')->where('id','=',$id)->update(array('gpg_employee_id'=>Input::get('assignTo'),'gpg_customer_id'=>Input::get('customer'),'job_num'=>Input::get('POjobNum'),'task_priority'=>Input::get('priority'),'task_type'=>Input::get('tasktype'),'task_detail'=>Input::get('task_detail'),'recuring'=>Input::get('recuring'),'status'=>'A','expected_end_date'=>(empty($endDate)?($tasktype=="Ongoing"?($recuring=="Daily"?date('Y-m-d'):($recuring=="Weekly"?date('Y-m-d', strtotime(date('Y-m-d').' + 6 days')):date('Y-m-d', strtotime(date('Y-m-d').' + 29 days')))):"NULL"):date('Y-m-d',strtotime($endDate)))));
		return Redirect::to('task/index')->withSuccess('New Task has been Updated successfully');
	}
	public function updateFollowUp(){
		$id = Input::get('gpgtask_id');
		$followup = Input::get('followup');
		DB::table('gpg_task_followup')->insert(array('task_id'=>$id,'followup_question'=>$followup,'followup_type'=>'A','parent_followup'=>'0','created_on'=>date('Y-m-d')));
		return Redirect::to('task/index')->withSuccess('Follow Up Question has been added successfully');
	}

	
	public function getFollowupList(){
		$id = Input::get('id');
		$qry = DB::table('gpg_task_followup')->where('task_id','=',$id)->select('*')->get();
		$rows= '';
		foreach ($qry as $key => $value) {
			$rows .= '<tr><td>'.$value->followup_question.'</td><td><a href="#myModal4" class="btn btn-primary btn-xs" data-toggle="modal" name="followup_answer" rowId='.$value->id.' id='.$value->task_id.' question='.$value->followup_question.'>Reply</a></td></tr>';
		}
		return $rows;
	}
	public function answerFollowUp(){
		$id = Input::get('gpg_task_id');
		$followupReply = Input::get('followupReply');
		DB::table('gpg_task_followup')->where('id','=',$id)->update(array('followup_answer'=>$followupReply,'viewed'=>'1'));
		return Redirect::to('task/index')->withSuccess('Follow Up Reply has been saved successfully');
	}
	public function date_diff($startdate,$enddate)
	{ 
		$qry = DB::select(DB::raw("select DATEDIFF('".date('Y-m-d',strtotime($enddate))."','".date('Y-m-d',strtotime($startdate))."')"));
	  	$res = array();
	  	foreach ($qry as $key => $value) {
	  		$res[] = (array)$value;
	  	}
	  return $res; 
	}
	/*
	* assignedTask
	*/
	public function assignedTask(){
		$modules = Generic::modules();
		$expectedSDate =  Input::get("expectedSDate");
		$expectedEDate =  Input::get("expectedEDate");
		$searchDepartment =  Input::get("searchDepartment");
		$searchEstimate =  Input::get("searchEstimate");
		$searchEmployee =  Input::get("searchEmployee");
		$taskcompleteddetails =  Input::get("taskcompleteddetails");
		$queryPart ="";
		if ($expectedSDate!="" and $expectedEDate!="") $queryPart .= " AND a.expected_end_date >= '".date('Y-m-d',strtotime($expectedSDate))."' AND a.expected_end_date <= '".date('Y-m-d',strtotime($expectedEDate))."' ";
		elseif ($expectedSDate!="") $queryPart .= " AND a.expected_end_date = '".date('Y-m-d',strtotime($expectedSDate))."'";
		elseif ($expectedEDate!="") $queryPart .= " AND a.expected_end_date = '".date('Y-m-d',strtotime($expectedEDate))."'"; 
		if ($taskcompleteddetails!="" and $taskcompleteddetails=="completed") $queryPart .= " AND ifnull(a.completion_date,'')<>''";
		if ($taskcompleteddetails!="" and $taskcompleteddetails=="open") $queryPart .= " AND ifnull(a.completion_date,'')=''";
		if ($searchEmployee!="") $queryPart .= " AND a.gpg_employee_id = '".$searchEmployee."'";
		if ($searchDepartment!="") $queryPart .= " AND a.gpg_department_id = '".$searchDepartment."'";
		if ($searchEstimate!="") $queryPart .= " AND if(ifnull(a.completion_date,'0000-00-00')<>'0000-00-00',datediff(a.expected_end_date,a.completion_date),datediff(a.expected_end_date,curdate())) = '".$searchEstimate."'";
		$admin = Auth::user()->ad_id;
		$head_id = Auth::user()->ad_id;
		if($admin==1){
			$query="select *, id as task_id,
					(select dept_name from gpg_department where id = gpg_department_id ) as dept_name,
					(select name from gpg_customer where id = gpg_customer_id ) as cus_name,
					(select name from gpg_employee where id = gpg_employee_id ) as emp_name, 
					DATEDIFF(expected_end_date,if(CURDATE()>completion_date,completion_date,CURDATE())) as now_diff,
					DATEDIFF(expected_end_date,completion_date) as comp_diff,
					DATEDIFF(completion_date,start_date) as comp_date,
					(select viewed from gpg_task_followup where viewed = 1 and task_id = a.id limit 0,1) as followup_flag 
				from gpg_task a where status='A' AND owned <> '1' ".$queryPart." order by id desc";
		}else{	
			$query="select *, a.id as task_id,
					(select dept_name from gpg_department where id = a.gpg_department_id ) as dept_name,
					(select name from gpg_customer where id = a.gpg_customer_id ) as cus_name,
					(select name from gpg_employee where id = a.gpg_employee_id ) as emp_name,
					DATEDIFF(a.expected_end_date,if(CURDATE()>a.completion_date,a.completion_date,CURDATE())) as now_diff,
					DATEDIFF(a.completion_date,expected_end_date) as comp_diff,
					(select viewed from gpg_task_followup where viewed = 1 and task_id = a.id limit 0,1) as followup_flag 
				from gpg_task a, gpg_department_user b 
				where a.gpg_department_id = b.gpg_department_id AND b.gpg_employee_id_head='$head_id' and ifnull(a.status,'')='A' AND owned <> '1' ".$queryPart." group by a.id order by a.id desc";
		}
		$query = DB::select(DB::raw($query));
		$query_data = array();
		foreach ($query as $key => $value) {
			$query_data[] = (array)$value;
		}

		$emps = DB::table('gpg_employee')->orderBy('name')->lists('name','id');
		$depts = DB::table('gpg_department')->orderBy('dept_name')->lists('dept_name','id');
		$params = array('left_menu' => $modules,'depts'=>$depts,'emps'=>$emps,'query_data'=>$query_data);
		return View::make('task/assigned_task', $params);	
	}

	/*
	* ownedTask
	*/
	public function ownedTask(){
		$modules = Generic::modules();
		$expectedSDate =  Input::get("expectedSDate");
		$expectedEDate =  Input::get("expectedEDate");
		$searchEstimate =  Input::get("searchEstimate");
		$taskcompleteddetails =  Input::get("taskcompleteddetails");
		$queryPart ="";
		if ($expectedSDate!="" and $expectedEDate!="") $queryPart .= " AND a.expected_end_date >= '".date('Y-m-d',strtotime($expectedSDate))."' AND a.expected_end_date <= '".date('Y-m-d',strtotime($expectedEDate))."' ";
		 elseif ($expectedSDate!="") $queryPart .= " AND a.expected_end_date = '".date('Y-m-d',strtotime($expectedSDate))."'";
		 elseif ($expectedEDate!="") $queryPart .= " AND a.expected_end_date = '".date('Y-m-d',strtotime($expectedEDate))."'"; 
		if ($taskcompleteddetails!="" and $taskcompleteddetails=="completed") $queryPart .= " AND ifnull(a.completion_date,'')<>''";
		if ($taskcompleteddetails!="" and $taskcompleteddetails=="open") $queryPart .= " AND ifnull(a.completion_date,'')=''";
		if ($searchEstimate!="") $queryPart .= " AND if(ifnull(a.completion_date,'')<>'',datediff(a.expected_end_date,a.completion_date),datediff(a.expected_end_date,curdate())) = '".$searchEstimate."'";
		$admin = Auth::user()->ad_id;
		$head_id=Auth::user()->ad_id;
		$query="select *,id as task_id,
					(select dept_name from gpg_department where id = gpg_department_id ) as dept_name,
					(select name from gpg_customer where id = gpg_customer_id ) as cus_name,
					DATEDIFF(expected_end_date,if(CURDATE()>completion_date,completion_date,CURDATE())) as now_diff,
					DATEDIFF(expected_end_date,completion_date) as comp_diff,
					DATEDIFF(completion_date,start_date) as comp_date
					from gpg_task a where status='A' AND owned = '1' ".$queryPart." ".($admin!=1?" AND task_creator_id = '$head_id' AND task_creator_type = 'admin' ":'')." order by id desc";
		$qry = DB::select(DB::raw($query));
		$query_data = array();
		foreach ($qry as $key => $value) {
			$query_data[] = (array)$value;
		}
					
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('task/own_task', $params);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$modules = Generic::modules();
		$depts = DB::table('gpg_department')->orderBy('dept_name')->lists('dept_name','id');
		$customers = DB::table('gpg_customer')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'depts'=>$depts,'customers'=>$customers);
		return View::make('task.create', $params);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
	        'gpg_department_id'             => 'required',           
	        'task_detail' => 'required'         
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('task/create')->withErrors($validator);
		}else{
			$department = Input::get("gpg_department_id");
			$assignTo = Input::get("assignTo");
			$customer = Input::get("customer");
			$task_creator_id = Input::get("task_creator_id");
			$task_creator_type = Input::get("task_creator_type");
			$taskpriority = Input::get("priority");
			$tasktype = Input::get("tasktype");
			$POjobNum = Input::get("POjobNum");
			$recuring = Input::get("recuring");
			$endDate = Input::get("endDate");
			$completionDate = Input::get("completionDate");
			$taskdetails = Input::get("task_detail");
			$completionNotes = Input::get("completionNotes");
			$ownedTask = Input::get("ownedTask");
			$id = DB::table('gpg_task')->max('id')+1;
			DB::table('gpg_task')->insert(array('id'=>$id
				,'gpg_department_id'=>$department,'gpg_customer_id'=>$customer,'start_date'=>date('Y-m-d')
				,'expected_end_date'=>(empty($endDate)?($tasktype=="Ongoing"?($recuring=="Daily"?date('Y-m-d'):($recuring=="Weekly"?date('Y-m-d', strtotime(date('Y-m-d').' + 6 days')):date('Y-m-d', strtotime(date('Y-m-d').' + 29 days')))):"NULL"):date('Y-m-d',strtotime($endDate)))
				,'task_priority'=>$taskpriority,'task_type'=>$tasktype,'recuring'=>$recuring,'task_detail'=>$taskdetails
				,'job_num'=>$POjobNum,'task_creator_id'=>Auth::user()->ad_id,'task_creator_type'=>(Auth::user()->ad_id==1?'admin':'user')
				,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'owned'=>$ownedTask,'status'=>($ownedTask==1?"'A'":"NULL")));
			return Redirect::to('task/create')->withSuccess('New Task has been created successfully');
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
		$query = DB::table('gpg_task')->where('id','=',$id)->select('*')->get();
		$row = array();
		foreach ($query as $key => $value) {
			$row = (array)$value;
		}
		$depts = DB::table('gpg_department')->orderBy('dept_name')->lists('dept_name','id');
		$customers = DB::table('gpg_customer')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'depts'=>$depts,'customers'=>$customers,'row'=>$row);
		return View::make('task.edit', $params);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$rules = array(
	        'gpg_department_id'             => 'required',           
	        'task_detail' => 'required'         
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('task/'.$id.'/edit')->withErrors($validator);
		}else{
			$department = Input::get("gpg_department_id");
			$assignTo = Input::get("assignTo");
			$customer = Input::get("customer");
			$task_creator_id = Input::get("task_creator_id");
			$task_creator_type = Input::get("task_creator_type");
			$taskpriority = Input::get("priority");
			$tasktype = Input::get("tasktype");
			$POjobNum = Input::get("POjobNum");
			$recuring = Input::get("recuring");
			$endDate = Input::get("endDate");
			$completionDate = Input::get("completionDate");
			$taskdetails = Input::get("task_detail");
			$completionNotes = Input::get("completionNotes");
			$ownedTask = Input::get("ownedTask");
			DB::table('gpg_task')->where('id','=',$id)->update(array('gpg_department_id'=>$department,'gpg_customer_id'=>$customer,'start_date'=>date('Y-m-d')
				,'expected_end_date'=>(empty($endDate)?($tasktype=="Ongoing"?($recuring=="Daily"?date('Y-m-d'):($recuring=="Weekly"?date('Y-m-d', strtotime(date('Y-m-d').' + 6 days')):date('Y-m-d', strtotime(date('Y-m-d').' + 29 days')))):"NULL"):date('Y-m-d',strtotime($endDate)))
				,'task_priority'=>$taskpriority,'task_type'=>$tasktype,'recuring'=>$recuring,'task_detail'=>$taskdetails
				,'job_num'=>$POjobNum,'task_creator_id'=>Auth::user()->ad_id,'task_creator_type'=>(Auth::user()->ad_id==1?'admin':'user')
				,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'owned'=>$ownedTask,'status'=>($ownedTask==1?"'A'":"NULL")));
			return Redirect::to('task/'.$id.'/edit')->withSuccess('Task has been updated successfully');
		}
	}

	/*
	* adminFollowup
	*/
	public function adminFollowup(){
		$modules = Generic::modules();
		$admin = Auth::user()->ad_id;
		$task_creator_id=Auth::user()->ad_id;
		if($admin==1){
			$query="select *, (select dept_name from gpg_department where id = a.gpg_department_id ) as dept_name from gpg_task a, gpg_task_followup b where a.id = b.task_id and b.followup_type = 'A' order by a.created_on desc ";
		}else{
			$query="select *, (select dept_name from gpg_department where id = a.gpg_department_id ) as dept_name from gpg_task a, gpg_task_followup b where a.id = b.task_id and b.followup_type = 'A' and a.task_creator_id = '$task_creator_id' and a.task_creator_type='admin'  order by b.created_on desc ";
		}
		$qry = DB::select(DB::raw($query));
		$query_data = array();
		foreach ($qry as $key => $value){
			$query_data[] = (array)$value;
		}

		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('task/admin_followup', $params);
	}

	/*
	* userFollowup
	*/
	public function userFollowup(){
		$modules = Generic::modules();
		$admin = Auth::user()->ad_id;
		$task_creator_id=Auth::user()->ad_id;
		if($admin==1){
			$query="select *,(select a.id ) as task_id, (select b.id ) as followup_id  , (select dept_name from gpg_department where id = a.gpg_department_id ) as dept_name from gpg_task a, gpg_task_followup b where a.id = b.task_id and b.followup_type = 'U' order by a.created_on desc ";
		}else{
			$query="select gt.*,gb.*,gu.*,(select gb.id ) as followup_id , (select dept_name from gpg_department where id = gt.gpg_department_id ) as dept_name from gpg_task gt, gpg_task_followup as gb,gpg_department_user as gu  where gt.id = gb.task_id and gt.gpg_department_id = gu.gpg_department_id and gu.gpg_employee_id_head='$head_id' and gb.followup_type = 'U' and ifnull(gb.followup_question,'')<>'' group by gb.id order by gb.created_on desc";
		}
		$qry = DB::select(DB::raw($query));
		$query_data = array();
		foreach ($qry as $key => $value){
			$query_data[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('task/user_followup', $params);
	}

	/*
	* deleteFollowUp
	*/
	public function deleteFollowUp(){
		DB::table('gpg_task_followup')->where('id','=',$id);
		return Redirect::to('task/admin_followup')->withSuccess('Task deleted successfully');
	}

	/*
	* deleteOwnedTask
	*/
	public function deleteOwnedTask($id){
		DB::table('gpg_task')->where('id','=',$id);
		return Redirect::to('task/own_task')->withSuccess('Task deleted successfully');
	}

	/*
	* deleteAssignedTask
	*/
	public function deleteAssignedTask($id){
		DB::table('gpg_task')->where('id','=',$id);
		return Redirect::to('task/assigned_task')->withSuccess('Task deleted successfully');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		DB::table('gpg_task')->where('id','=',$id);
		return Redirect::to('task.index')->withSuccess('Task deleted successfully');
	}


}
