<?php

class DepartmentController extends \BaseController {

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
 		return View::make('department.index', $params);
	}
	/*
	* paginator for Department Management	
	*/
	public function getByPage($page = 1, $limit = 10)
	{
	  $fullName = "";		
	  $results = new \StdClass;
	  $results->page = $page;
	  $results->limit = $limit;
	  $results->totalItems = 0;
	  $results->items = array();
	  $final_arr = array();
	 
	  $query_count = DB::table('gpg_department')
            ->select('*')
			->count();

	  $query = DB::table('gpg_department')
	  		->select('*')
            ->skip($limit * ($page - 1))
			->take($limit)
			->get();
	
	foreach ($query as $key => $value) {

	  	$query1 = DB::table('gpg_ad_acc')
	  		->select('gpg_ad_acc.fname','gpg_ad_acc.lname','gpg_department_user.gpg_employee_id_head')
	  		->join('gpg_department_user', 'gpg_ad_acc.ad_id', '=', 'gpg_department_user.gpg_employee_id_head')
            ->where('gpg_department_user.gpg_department_id','=',$value->id)
            ->distinct()
            ->get();
        if (!empty($query1))       	
        	$fullName = $query1[0]->fname." ".$query1[0]->lname;

        $users = "";
        $query2 = DB::table('gpg_employee')
	  		->select('gpg_employee.name','gpg_department_user.gpg_employee_id_head')
	  		->join('gpg_department_user', 'gpg_employee.id', '=', 'gpg_department_user.gpg_employee_id')
            ->where('gpg_department_user.gpg_department_id','=',$value->id)
            ->distinct()
            ->get();
    
        foreach ($query2 as $key2 => $value2) {
             $user = "";
        	 if (!empty($value2))
        	 	$user = $value2->name;
        	 $users .= $user.", ";    	
        }    
 		$final_arr[] = array('id' =>$value->id ,'dept_name' =>$value->dept_name ,'head' =>$fullName ,'dept_users' =>$users);
	  }

	  $results->totalItems = $query_count;
	  $results->items = $final_arr;
	 
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
		$params = array('left_menu' => $modules);
 		return View::make('department.create', $params);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		DB::table('gpg_department')
     		->insert(array('dept_name' =>Input::get('dept_name'),'created_on'=>date("Y-m-d H:i:s"),'modified_on' =>date("Y-m-d H:i:s")));
		return Redirect::route('department.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$deptName = "";
		$modules = Generic::modules();
		$query_data = DB::table('gpg_department')
	  		->select('dept_name')
            ->where('id','=',$id)
            ->get();

        $head = DB::table('gpg_department_user')
        	->select('*')
        	->where('gpg_department_id', '=' , $id)
        	->get();
        $dept_head_id = "";
        $dept_emps = array();
        foreach ($head as $key => $value) {
        	if(empty($dept_head_id))
        		$dept_head_id = $value->gpg_employee_id_head;
        	$dept_emps [] = $value->gpg_employee_id;
        }	
		$qry_ad_list = DB::table('gpg_ad_acc')
        	->select('ad_id','fname','lname')
        	->orderBy('fname', 'ASC')
        	->get();
        
        $create_drop_list = "";
        foreach ($qry_ad_list as $key => $data) {
        	if ($data->ad_id == $dept_head_id)
    	    	$create_drop_list .= "<option value='".$data->ad_id."' selected='selected'>".$data->fname." ".$data->lname."</option>";  	
        	else
	        	$create_drop_list .= "<option value='".$data->ad_id."'>".$data->fname." ".$data->lname."</option>";  	
        }
        $all_emps = DB::table('gpg_employee')
        	->select('id','name')
        	->orderBy('name', 'ASC')
        	->get();
        $all_emps_arr = array();	
        foreach ($all_emps as $key2 => $value2) {
        	$all_emps_arr[] = array('id' => $value2->id ,'name'=> $value2->name);
        }
        if (!empty($query_data))
        	$deptName = $query_data[0]->dept_name;
		$params = array('left_menu' => $modules, 'query_data'=>$deptName, 'id'=>$id, 'option_list'=> $create_drop_list, 'dept_users'=>$dept_emps, 'all_emps'=>$all_emps_arr);
 		return View::make('department.show', $params);
	}
	/*
	* Manage department users
	*/
	public function manageDepartmentUsers($id){
		
		DB::table('gpg_department_user')->where('gpg_department_id', '=', $id)->delete();
		if (isset($_POST['headOfDept']) && !empty($_POST['emp'])) {
			foreach ($_POST['emp'] as $key => $value) {
				DB::table('gpg_department_user')
		     		->insert(array('gpg_department_id' =>$id,'gpg_employee_id'=>$value,'gpg_employee_id_head' =>$_POST['headOfDept']));		
			}
		}elseif (isset($_POST['headOfDept']) && empty($_POST['emp'])) {
			DB::table('gpg_department_user')
		     		->insert(array('gpg_department_id' =>$id,'gpg_employee_id_head' =>$_POST['headOfDept']));		
		}
		return Redirect::route('department.index');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$deptName = "";
		$modules = Generic::modules();
		$query_data = DB::table('gpg_department')
	  		->select('dept_name')
            ->where('id','=',$id)
            ->get();
        if (!empty($query_data))
        	$deptName = $query_data[0]->dept_name;
		$params = array('left_menu' => $modules, 'query_data'=>$deptName, 'id'=>$id);
 		return View::make('department.edit', $params);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		DB::table('gpg_department')
          ->where('id','=', $id)
          ->update(array('dept_name' =>Input::get('dept_name')));

        return Redirect::route('department.index');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		DB::table('gpg_department')
			->where('id', '=' ,$id)
	       	->delete();
        return Redirect::route('department.index');
	}


}
