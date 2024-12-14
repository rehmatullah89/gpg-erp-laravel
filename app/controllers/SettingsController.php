<?php

class SettingsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (isset($_POST) && !empty($_POST)) {
			$query_part = array();
			foreach ($_POST as $key => $value) {
				if (strpos($key,"_token")=== false && strpos($key,"_zone_index") === false && strpos($key,"_AllocatedPmHours")=== false && strpos($key,"_gl_tags_index")=== false && strpos($key,"scope_template_name")=== false && strpos($key,"scope_template")=== false && strpos($key,"_parts_allow_emps")=== false && strpos($key,"_time_card_allow_emps")=== false  && strpos($key,"glcode_count")=== false  && strpos($key,"zone_count")=== false  && strpos($key,"scop_work_count")=== false) {
					$query_part = array_merge($query_part, array($key => $value));
				}
			}
			$query_part2 = array();
			$zone_count = Input::get('zone_count');
			//die($zone_count);
			for ($i=1; $i <= $zone_count; $i++) {
				if (isset($_POST['_zone_index_'.$i]))
				$query_part2 = array_merge($query_part2, array('_zone_index_'.$i => Input::get('_zone_index_'.$i)));
			}
			$query_part3 = array();
			for ($i=1; $i <Input::get('glcode_count'); $i++) {
				if (isset($_POST['_gl_tags_index_'.$i]))
				$query_part3 = array_merge($query_part3, array('_gl_tags_index_'.$i => Input::get('_gl_tags_index_'.$i)));
			}
			$query_part4 = array();
			for ($i=1; $i <Input::get('scop_work_count'); $i++) {
				if (isset($_POST['scope_template_name_'.$i]))
				$query_part4 = array_merge($query_part4, array('scope_template_'.$i => Input::get('scope_template_name_'.$i).'##@##'.Input::get('scope_template_'.$i)));
			}
			$parts_allow_emps = implode(',', Input::get('_parts_allow_emps'));
			$query_part5 = array('_parts_allow_emps'=>$parts_allow_emps);
			$time_card_allow_emps = Input::get('_time_card_allow_emps');
			if (!empty($time_card_allow_emps))
			$time_card_allow_emps = implode(',', Input::get('_time_card_allow_emps'));
			$query_part6 = array('_time_card_allow_emps'=>$time_card_allow_emps);
			$full_array = $query_part+$query_part2+$query_part3+$query_part4+$query_part5+$query_part6;
			foreach ($full_array as $key => $value) {
				if (Gpg_settings::where('name', '=', $key)->count() > 0) {
					DB::table('gpg_settings')->where('name','=',$key)->update(array('value'=>$value));
				}else{
					if (!empty($value))
						DB::table('gpg_settings')->insert(array('name'=>$key,'value'=>$value));
				}
			}
			return Redirect::to('settings/index')->withSuccess('Settings Updated Successfully');
		}
		$modules = Generic::modules();
		$settings = DB::table('gpg_settings')->select('*')->get();
		$setval = array();
		foreach ($settings as $key => $value) {
			$setval[$value->name] = $value->value;
		}
		$qry = DB::select(DB::raw("Select value from gpg_settings where name like '%_AllocatedPmHours%' order by id"));
		$alloc_arr = array();
		$arr = explode(',', $qry[0]->value);
		foreach ($arr as $key => $value) {
			$entity = explode('~', $value);
			$alloc_arr[$entity[0]] = $entity[1];
		}
		$con = DB::select(DB::raw("SELECT name,value FROM gpg_settings where name like '_ContactInfo_%'"));
		$con_arr = array();
		foreach ($con as $key => $value) {
			$con_arr[] = $value->value;
		}
		$zoni = DB::select(DB::raw("SELECT name,value FROM gpg_settings where name like '_zone_index_%' ORDER BY id"));
		$zon_arr = array();
		foreach ($zoni as $key => $value) {
			$zon_arr[] = $value->value;
		}
		$tag = DB::select(DB::raw("SELECT name,value FROM gpg_settings where name like '_gl_tags_%' ORDER BY id"));
		$tag_arr = array();
		foreach ($tag as $key => $value) {
			$tag_arr[] = $value->value;
		}
		$templt = DB::select(DB::raw("SELECT * FROM gpg_settings WHERE NAME LIKE 'scope_template_%'"));
		$templt_arr = array();
		foreach ($templt as $key => $value) {
			$tmp = explode('##@##', $value->value);
			$templt_arr[$tmp[0]] = $tmp[1];
		}
		$ad_acc = DB::select(DB::raw("SELECT * FROM gpg_ad_acc ORDER BY fname ASC "));
		$ad_acc_arr = array();
		foreach ($ad_acc as $key => $value) {
			$ad_acc_arr[] = (array)$value;
		}
		
		$params = array('left_menu' => $modules,'setval'=>$setval,'alloc_arr'=>$alloc_arr,'con_arr'=>$con_arr,'zon_arr'=>$zon_arr,'tag_arr'=>$tag_arr,'templt_arr'=>$templt_arr,'ad_acc_arr'=>$ad_acc_arr);
		return View::make('settings.index', $params);
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
		return View::make('settings.create', $params);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
	        'country' => 'required|unique:gpg_country'         
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('settings/create')->withErrors($validator);
		}else{
			$country = Input::get('country');
			$state2 = Input::get('state2');
			$state3 = Input::get('state3');
			$zip = Input::get('zip');
			DB::table('gpg_country')->insert(array('country'=>$country,'state2'=>$state2,'state3'=>$state3,'zip'=>$zip));
			return Redirect::to('settings/create')->withSuccess('New Country has been created successfully');
		}
	}

	/*
	* countryList
	*/
	public function countryList(){
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);

		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('settings/cview', $params);
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
		$results->totalItems = 0;
		$results->items = array();
		$results->totalItems = DB::table('gpg_country')->count('country_id');
		$qry = DB::select(DB::raw("select * from gpg_country $limitOffset"));
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		return $results;
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
	/*
	* kwMatrix
	*/
	public function kwMatrix(){
		$modules = Generic::modules();
		$query = DB::table('gpg_kw_matrix')->select('*')->orderBy('start_kw')->get();
		$query_data = array();
		foreach ($query as $key => $value) {
			$query_data[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('settings/kw_matrix', $params);
	}

	public function updateKWMatrix(){
		$id = Input::get('id');
		$range = Input::get('range');
		$range = explode("-", $range);
		$start_kw = $range[0];
		$end_kw = $range[1];
		$level1 = Input::get('level1');
		$level2 = Input::get('level2');
		DB::table('gpg_kw_matrix')->where('id','=',$id)->update(array('start_kw'=>$start_kw,'end_kw'=>$end_kw,'pm_charges'=>$level1,'annual_charges'=>$level2));
		return 1;
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
		$data = DB::table('gpg_country')->select('*')->where('country_id','=',$id)->get();
		$params = array('left_menu' => $modules,'data'=>$data);
		return View::make('settings.edit', $params);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$country = Input::get('country');
		$state2 = Input::get('state2');
		$state3 = Input::get('state3');
		$zip = Input::get('zip');
		DB::table('gpg_country')->where('country_id','=',$id)->update(array('country'=>$country,'state2'=>$state2,'state3'=>$state3,'zip'=>$zip));
		return Redirect::to('settings/'.$id.'/edit')->withSuccess('Country has been Updated successfully');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(!empty($id)){
			DB::table('gpg_country')->where('country_id','=',$id)->delete();
			return Redirect::to('settings/cview')->withSuccess('Deleted successfully');
		}
		return Redirect::to('settings/cview')->withErrors('There is problem with deletion!');
	}


}
