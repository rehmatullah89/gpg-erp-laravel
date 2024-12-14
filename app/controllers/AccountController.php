<?php

class AccountController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		Input::flash();		
		$page = Input::get('page', 1);
   		$data = $this->getAccountsByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('account.index', $params);
	}
	public function getAccountsByPage($page = 1, $limit = null)
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
		$results->totalItems = DB::table('gpg_ad_acc')->count('ad_id');
		$members = DB::select(DB::raw("select * from gpg_ad_acc $limitOffset")); 
		$mem_arr = array();
		foreach ($members as $key => $value) {
			$mem_arr[] = (array)$value;
		}
		$results->items = $mem_arr;
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
		Input::flash();		
		$countries = DB::table('gpg_country')->orderBy('country')->lists('country','country_id');
		$params = array('left_menu' => $modules,'countries'=>$countries);
		return View::make('account.create', $params);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$uname = Input::get("uname");
		$pwd = Input::get("pwd");
		$repwd = Input::get("repwd");
		$fname = Input::get("fname");
		$lname = Input::get("lname");
		$email = Input::get("email");
		$email_pwd = Input::get("email_pwd");
		$country = Input::get("country");
		$ad_cat = Input::get("ad_cat");
		$phone = Input::get("phone");
		$id = Input::get("id");
		$oldpass = Input::get("oldpass");
		$newpass = Input::get("newpass");
		$modPerm = Input::get("modPerm");
		$allowed_emps_arr = Input::get("allow_emps");
		$allowed_emps = "";
		if(is_array($allowed_emps_arr)){
			for($i=0; $i<count($allowed_emps_arr); $i++){
				$allowed_emps .= $allowed_emps_arr[$i].",";
			}
		}
		$rules = array(
            'email' => 'required | between:5,100 | email ',
            'uname' => 'required | unique:gpg_ad_acc,uname',
            'pwd'  => 'required|max:20',
            'repwd' => 'required|max:20|same:pwd'
        );
        $validation = Validator::make(Input::all(), $rules);     
        if ($validation->fails()){
            return Redirect::to('account/create')->withErrors($validation);
        }
	    $maxId = DB::table('gpg_ad_acc')->max('ad_id')+1;
		$query = DB::table('gpg_ad_acc')->insert(array('ad_id'=>$maxId,'uname'=>$uname, 'pwd'=>md5($pwd), 'fname'=>$fname, 'lname'=>$lname, 'country_id'=>$country, 'email'=>$email,'email_pwd'=>$email_pwd, 'phone'=>$phone,'allowed_employees'=>$allowed_emps, 'created_date'=>date('Y-m-d'), 'last_modified_date'=>date('Y-m-d'),'ad_cat_id'=>$ad_cat));	
		if($query){
			DB::table('gpg_mod_perm')->where('GPG_ad_acc_id','=',$maxId)->delete();  
			if (!empty($modPerm)) {
			    foreach ($modPerm as $val) {
			  		$parentId = DB::table('gpg_module')->where('id','=',$val)->pluck('parent');			
				    if ($parentId) {
					    $checkParent = DB::table('gpg_mod_perm')->where('GPG_ad_acc_id','=',$maxId)->where('GPG_module_id','=',$parentId)->pluck('id');
						if (empty($checkParent)) 
							DB::table('gpg_mod_perm')->insert(array('GPG_ad_acc_id'=>$maxId, 'GPG_module_id'=>$parentId));
			  		}
			  		DB::table('gpg_mod_perm')->insert(array('GPG_ad_acc_id'=>$maxId, 'GPG_module_id'=>$val));
				}
			  }
			  return Redirect::to('account/create')->withSuccess('Record has been added successfully');
			} else {
			  return Redirect::to('account/create')->withSuccess('Record has been added successfully without permission modification');
			}
	}

	/*
	* excelAccountsExport
	*/
	public function excelAccountsExport(){
		set_time_limit(0);
		Excel::create('AccountsExport', function($excel) {
		    $excel->sheet('AccountsExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getAccountsByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		    $sheet->loadView('account.excelAccountsExport',$params);
		    });
		})->export('xls');
	}

	/*
	* changePass
	*/
	public function changePass(){
		$rules = array(
            'newpass'  => 'required|max:20',
            'repass' => 'required|max:20|same:newpass'
        );
        $validation = Validator::make(Input::all(), $rules); 
        if ($validation->fails()){
            return Redirect::to('account/create')->withErrors($validation);
        } 
        $id = Input::get('hidden_id');
		$newpass = Input::get('newpass');
		$repass = Input::get('repass');
		DB::table('gpg_ad_acc')->where('ad_id','=',$id)->update(array('pwd'=>md5($newpass),'last_modified_date'=>date('Y-m-d')));
		return Redirect::to('account/index')->withSuccess('Password Changed Successfully!');
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
	public function edit($id) {
		$modules = Generic::modules();
		Input::flash();
		$employee_info = DB::table('gpg_ad_acc')->where('ad_id','=',$id)->select('*')->get();
		$permissions = DB::select(DB::raw("select a.id mod_id, a.module_name, b.GPG_module_id from gpg_module a left join gpg_mod_perm b on (a.id = b.GPG_module_id and b.GPG_ad_acc_id = '$id') where a.parent = 0"));
		$perms = array();
		foreach ($permissions as $key => $value) {
			$perms[] = (array)$value;
		}
		$urow = DB::select(DB::raw("select * from gpg_ad_acc where ad_id = '$id'"));
		$allowed_emps = explode(",",$urow[0]->allowed_employees);
		$countries = DB::table('gpg_country')->orderBy('country')->lists('country','country_id');
		$params = array('left_menu' => $modules,'countries'=>$countries,'employee_info'=>$employee_info,'modRow'=>$perms,'id'=>$id,'allowed_emps'=>$allowed_emps);
		return View::make('account.edit', $params);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$uname = Input::get("uname");
		$p_uname = Input::get("p_uname");
		$pwd = Input::get("pwd");
		$repwd = Input::get("repwd");
		$fname = Input::get("fname");
		$lname = Input::get("lname");
		$email = Input::get("email");
		$email_pwd = Input::get("email_pwd");
		$country = Input::get("country");
		$ad_cat = Input::get("ad_cat");
		$phone = Input::get("phone");
		$oldpass = Input::get("oldpass");
		$newpass = Input::get("newpass");
		$modPerm = Input::get("modPerm");
		$allowed_emps_arr = Input::get("allow_emps");
		$allowed_emps = "";
		if(is_array($allowed_emps_arr)){
			for($i=0; $i<count($allowed_emps_arr); $i++){
				$allowed_emps .= $allowed_emps_arr[$i].",";
			}
		}
		if (!empty($uname) && !empty($p_uname) && $p_uname == $uname) 
			$rules = array(
	            'email' => 'required | between:5,100 | email ',
	            'uname' => 'required',
	        );
		else
			$rules = array(
	            'email' => 'required | between:5,100 | email ',
	            'uname' => 'required | unique:gpg_ad_acc,uname',
	        );
        $validation = Validator::make(Input::all(), $rules);     
        if ($validation->fails()){
            return Redirect::to('account/'.$id.'/edit')->withErrors($validation);
        }
		$email_pwd_query = ($email_pwd != "") ? ", email_pwd=>'$email_pwd'" :  "";
		$query = DB::table('gpg_ad_acc')->where('ad_id','=',$id)->update(array('uname'=>$uname,'fname'=>$fname,'lname'=>$lname,'country_id'=>$country,'email'=>$email,'phone'=>$phone,'allowed_employees'=>$allowed_emps,'last_modified_date'=>date('Y-m-d'),'ad_cat_id'=>$ad_cat));
		if ($query) {
			DB::table('gpg_mod_perm')->where('GPG_ad_acc_id','=',$id);
			if (!empty($modPerm)) {
			    foreach ($modPerm as $val) {
			   		$parentId = DB::table('gpg_module')->where('id','=',$val)->pluck('parent');			
			  		if ($parentId!="0") {
			     		$checkParent = DB::table('gpg_mod_perm')->where('GPG_ad_acc_id','=',$id)->where('GPG_module_id','=',$parentId)->pluck('id');
				 	if ($checkParent=="") 
				 		DB::table('gpg_mod_perm')->insert(array('GPG_ad_acc_id'=>$id,'GPG_module_id'=>$parentId));
					}
					DB::table('gpg_mod_perm')->insert(array('GPG_ad_acc_id'=>$id, 'GPG_module_id'=>$val));	
	  			}
			}
			return Redirect::to('account/'.$id.'/edit')->withSuccess('Record has been updated successfully');
		} else {
			return Redirect::to('account/'.$id.'/edit')->withSuccess('Record has been updated successfully');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$gpg_holiday = DB::table('gpg_ad_acc')
		  		->where('id', '=',$id)
	          	->delete();
        return Redirect::route('account.index');
	}


}
