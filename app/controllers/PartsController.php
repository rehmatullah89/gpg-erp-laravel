<?php

class PartsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		$type_arr = DB::table('gpg_field_material_type')->orderBy('name')->lists('name','id');
		$page = Input::get('page', 1);
   		$data = $this->getDataByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'type_arr'=>$type_arr,'query_data'=>$query_data);
		return View::make('parts.index', $params);
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
	  $results->totalItems = 0;
	  $results->items = array();
	  $SDate = Input::get("SDate");
	  $EDate = Input::get("EDate");
	  $Filter = Input::get("Filter");
	  $FVal = Input::get("FVal");
	  $gpg_field_material_type_id = Input::get("gpg_field_material_type_id");
	  $DSQL = "";
	  $DQ2 = " order by id desc ";
	  $status =""; //new defined
	  	if ($SDate!="" || $EDate!="") {
		    if ($SDate!="" && $EDate =="") {
		    	$DSQL.= " AND DATE_FORMAT(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;
			} elseif ($SDate == "" && $EDate != "") {
			    $DSQL.= " AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'";
			} elseif ($SDate != "" && $EDate != "") {
				$DSQL.= " AND (created_on >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."'
				        AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')";
			}
	  	}
	    if ($Filter!="" && ($FVal!="" || $gpg_field_material_type_id!="" )) {   
			if ($Filter !="part_number" && $Filter !="gpg_field_material_type_id") 
			   $DSQL.= " AND $Filter like '%$FVal%'"; 
			   elseif ($Filter =="part_number") $DSQL.= " AND ".$this->db_remove_special($Filter)." LIKE '%".($FVal)."%'"; 
			   elseif ($Filter =="gpg_field_material_type_id") $DSQL.= " AND ".$this->db_remove_special($Filter)." = '$gpg_field_material_type_id'";
		}
		$count = DB::select(DB::raw("select count(id) as t_count from gpg_field_material WHERE 1 $DSQL"));
		if (!empty($count) && isset($count[0]->t_count))
			$results->totalItems = $count[0]->t_count;
		$res = DB::select(DB::raw("select *,(select name from gpg_field_material_type where status = 'A' and id = gpg_field_material_type_id) as material_type from gpg_field_material WHERE 1 $DSQL $DQ2 $limitOffset"));
		$data_arr = array();
		foreach ($res as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		return $results;
	}
	public function db_remove_special($FieldsName){
   		return "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE($FieldsName,'\'',''),'\"',''),',',''),')',''),'(',''),'-',''),'/',''),'.',''),':',''),'?',''),'\r',''),'\n',''),'&',''),' ',''),'$',''),'#','') ";
	}

	/*
	* fieldMaterialUsed
	*/
	public function fieldMaterialUsed(){
		set_time_limit(0);
		$modules = Generic::modules();
		$type_arr = DB::table('gpg_field_material_type')->orderBy('name')->lists('name','id');
		$page = Input::get('page', 1);
   		$data = $this->getMatDataByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'type_arr'=>$type_arr,'query_data'=>$query_data);
		return View::make('parts.field_material_used', $params);
	}

	/*
	* getMatDataByPage
	*/
	public function getMatDataByPage($page = 1, $limit = null){
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
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$gpg_field_material_type_id = Input::get("gpg_field_material_type_id");
		$DSQL = "";
		$DQ2 = " order by id desc ";
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
		if ($Filter!="" && ($FVal!="" || $gpg_field_material_type_id!="" )) {
		   if ($Filter !="part_number" && $Filter !="gpg_field_material_type_id") 
		   $DSQL.= " AND $Filter like '%$FVal%'"; 
		   elseif ($Filter =="part_number") $DSQL.= " AND ".db_remove_special($Filter)." = '".remove_special($FVal)."'"; 
		   elseif ($Filter =="gpg_field_material_type_id") $DSQL.= " AND ".db_remove_special($Filter)." = '$gpg_field_material_type_id'"; 
		}
		set_time_limit(0);
		DB::select(DB::raw("create temporary table if not exists temp_parts as (select a.*,b.gpg_field_service_work_id as attachJobID,b.list_price as attachListPrice,b.cost_price as attachCostPrice,b.margin as attachMargin,b.quantity as attachQuantity,(select job_num from gpg_field_service_work where id = b.gpg_field_service_work_id) as attachJobNum from gpg_field_material a LEFT JOIN gpg_field_service_work_material b on (a.id=b.part_id) having attachJobNum is not null)"));
		DB::select(DB::raw("insert into temp_parts (select a.*,c.gpg_consum_contract_id as attachJobID,c.list_price as attachListPrice,c.cost_price as attachCostPrice,c.margin as attachMargin,c.quantity as attachQuantity,(select job_num from gpg_consum_contract where id = c.gpg_consum_contract_id) as attachJobNum from gpg_field_material a LEFT JOIN gpg_consum_contract_material c on (a.id = c.part_id) having attachJobNum is not null)"));
		DB::select(DB::raw("insert into temp_parts (select a.*,d.gpg_shop_work_quote_id as attachJobID,d.list_price as attachListPrice,d.cost_price as attachCostPrice,d.margin as attachMargin,d.quantity as attachQuantity,(select job_num from gpg_shop_work_quote where id = d.gpg_shop_work_quote_id) as attachJobNum from gpg_field_material a LEFT JOIN gpg_shop_work_quote_material d on (a.id = d.part_id) having attachJobNum is not null )"));
		$count = DB::select(DB::raw("select count(id) as t_count from temp_parts WHERE 1 $DSQL"));
		if (!empty($count) && isset($count[0]->t_count)) {
			$results->totalItems = $count[0]->t_count;
		}
		$qry = DB::select(DB::raw("select *,(select name from gpg_field_material_type where status = 'A' and id = gpg_field_material_type_id) as material_type from temp_parts where ifnull(part_number,'')<>'' $DSQL order by part_number,attachJobNum $limitOffset"));
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		DB::select(DB::raw("drop temporary table  temp_parts"));
		return $results;
	}

	/*
	* excelPartsExport
	*/
	public function excelPartsExport(){
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
	   	$data = $this->getMatDataByPage($page);
	  	$query_data = Paginator::make($data->items, $data->totalItems, 100);
	  	$type_arr = DB::table('gpg_field_material_type')->orderBy('name')->lists('name','id');
	  	$params = array('query_data'=>$query_data,'type_arr'=>$type_arr);
	 	$sheet->loadView('parts.excelPartsExport',$params);
		    });
		})->export('xls');
	}

	/*
	* existingFixtureIndex
	*/
	public function existingFixtureIndex(){
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getEFixesByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$types = DB::table('gpg_job_electrical_subquote_fixtures_type')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'types'=>$types,'query_data'=>$query_data);
		return View::make('parts.existing_fixture_index', $params);
	}
	public function getEFixesByPage($page = 1, $limit = null)
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
	  $Filter = Input::get("Filter");
	  $FVal = Input::get("FVal");
	  $fixture_type = Input::get("fixture_type");
	  $DSQL = "";
	  $DQ2 = " order by id desc ";
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
		if ($Filter!="" && ($FVal!="" || $fixture_type!="")) {  
		   if ($Filter !="gpg_job_electrical_subquote_fixtures_type_id") 
		   $DSQL.= " AND $Filter like '%$FVal%'"; 
		   elseif ($Filter =="gpg_job_electrical_subquote_fixtures_type_id") $DSQL.= " AND ".$this->db_remove_special($Filter)." = '".($fixture_type)."'"; 
		}
		$count = DB::select(DB::raw("select count(id) as t_count from gpg_job_electrical_subquote_existing_fixtures WHERE 1 $DSQL"));
		if (!empty($count) && isset($count[0]->t_count)) {
			$results->totalItems = $count[0]->t_count;
		}
		$qry = DB::select(DB::raw("select *,(select name from gpg_job_electrical_subquote_fixtures_type where id = gpg_job_electrical_subquote_fixtures_type_id) as fixture_type from gpg_job_electrical_subquote_existing_fixtures WHERE 1 $DSQL $DQ2 limit $start,$limit"));
		$query_data = array();
		foreach ($qry as $key => $value) {
			$query_data[] = (array)$value;
		}
		$results->items = $query_data;
		return $results;
	}
	/*
	* fieldComponentIndex
	*/
	public function fieldComponentIndex(){
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getCompsByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('parts.field_component_index', $params);
	}
	public function getCompsByPage($page = 1, $limit = null)
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
	  $status = ""; 
	  $SDate = Input::get("SDate");
	  $EDate = Input::get("EDate");
	  $Filter = Input::get("Filter");
	  $FVal = Input::get("FVal");
      $DSQL = "";
	  $DQ2 = " order by id desc ";
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
	  if ($Filter!="" && ($FVal!="" || $part_number!="")) {		   
		if ($Filter !="part_number") 
		   $DSQL.= " AND $Filter like '%$FVal%'"; 
		elseif ($Filter =="part_number" and $FVal!='') $DSQL.= " AND ".db_remove_special($Filter)." = '".($FVal)."'"; 
	  }
	  $count = DB::select(DB::raw("select count(id) as t_count from gpg_field_component WHERE 1 $DSQL"));
		if (!empty($count) && isset($count[0]->t_count))
			$results->totalItems = $count[0]->t_count;
		$res = DB::select(DB::raw("select *,(select name from gpg_field_component_type where status = 'A' and id = gpg_field_component_type_id) as component_type from gpg_field_component WHERE 1 $DSQL $DQ2 $limitOffset"));
		$data_arr = array();
		foreach ($res as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		return $results;
	}
	/*
	* fieldMaterialTypeIndex
	*/
	public function fieldMaterialTypeIndex(){
		$modules = Generic::modules();
		$page = Input::get('page', 1);
   		$data = $this->getByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('parts.field_material_type_index', $params);
	}
	public function getByPage($page = 1, $limit = 100)
	{
	  $results = new \StdClass;
	  $results->page = $page;
	  $results->limit = $limit;
	  $results->totalItems = 0;
	  $results->items = array();
	  $results->totalItems = DB::table('gpg_field_material_type')->count('id');
	  $qry = DB::table('gpg_field_material_type')
	  		->select('*')->orderBy('name')
	  		->skip($limit * ($page - 1))
			->take($limit)
			->get();
		$results->items = $qry;
		return $results;
	}

	/*
	* updatePartType
	*/
	public function updatePartType(){
		$id = Input::get('id');
		$name = Input::get('name');
		DB::table('gpg_field_material_type')->where('id','=',$id)->update(array('name'=>$name,'status'=>'A','modified_on'=>date('Y-m-d')));
		return 1;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$modules = Generic::modules();
		$type_arr = DB::table('gpg_field_material_type')->orderBy('name')->lists('name','id');
		$gpg_vendor = DB::table('gpg_vendor')->where('status','=','A')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'type_arr'=>$type_arr,'gpg_vendor'=>$gpg_vendor);
		return View::make('parts.create', $params);
	}
	/*
	* addProposedFixture
	*/
	public function addProposedFixture(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			$rules = array(
	        '_gpg_job_electrical_subquote_fixtures_type_id' => 'required',           
	        'fixture_name' => 'required|unique:gpg_job_electrical_subquote_proposed_fixtures'
	    	);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()){
	 	        $allInputs = Input::except('_token');
			    Input::flash();
				$messages = $validator->messages();
		        return Redirect::to('parts/add_proposed_fixture')->withErrors($validator);
			}else{
				$fixture_name = Input::get('fixture_name');
				$queryPart = array();
				while (list($ke,$vl)= each($_POST)) {
				    if(preg_match("/^_/i",$ke))
				   		$queryPart += array(substr($ke,1,strlen($ke)) => str_replace("\r","",str_replace("\n","",($vl))));
				}
				unset($queryPart['token']);
				DB::table('gpg_job_electrical_subquote_proposed_fixtures')->insert($queryPart+array('fixture_name'=>$fixture_name,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				return Redirect::to('parts/add_proposed_fixture')->withSuccess('PROPOSED FIXTURE has been added successfully');
			}

		}
		$types = DB::table('gpg_job_electrical_subquote_fixtures_type')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'types'=>$types);
		return View::make('parts/add_proposed_fixture', $params);
	}

	/*
	* addExistingFixture
	*/
	public function addExistingFixture(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			$rules = array(
	        '_gpg_job_electrical_subquote_fixtures_type_id' => 'required',           
	        'fixture_name' => 'required|unique:gpg_job_electrical_subquote_existing_fixtures'
	    	);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()){
	 	        $allInputs = Input::except('_token');
			    Input::flash();
				$messages = $validator->messages();
		        return Redirect::to('parts/add_existing_fixture')->withErrors($validator);
			}else{
				$fixture_name = Input::get('fixture_name');
				$queryPart = array();
				while (list($ke,$vl)= each($_POST)) {
				    if(preg_match("/^_/i",$ke))
				   		$queryPart += array(substr($ke,1,strlen($ke)) => str_replace("\r","",str_replace("\n","",($vl))));
				}
				unset($queryPart['token']);
				DB::table('gpg_job_electrical_subquote_existing_fixtures')->insert($queryPart+array('fixture_name'=>$fixture_name,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				return Redirect::to('parts/add_existing_fixture')->withSuccess('Existing Fixture added successfully');
			}

		}//end if
		$types = DB::table('gpg_job_electrical_subquote_fixtures_type')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'types'=>$types);
		return View::make('parts/add_existing_fixture', $params);
	}

	/*
	* addFieldComponent
	*/
	public function addFieldComponent(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			$rules = array(
	        '_gpg_field_component_type_id' => 'required',           
	        'part_number' => 'required|unique:gpg_field_component'
	    	);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()){
	 	        $allInputs = Input::except('_token');
			    Input::flash();
				$messages = $validator->messages();
		        return Redirect::to('parts/add_field_component')->withErrors($validator);
			}else{
				$part_number = Input::get('part_number');
				$getMaxId = DB::table('gpg_field_component')->max('id')+1;
				$queryPart = array();
				while (list($ke,$vl)= each($_POST)) {
				   if (preg_match("/^_/i",$ke)) {
				   		$queryPart += array(substr($ke,1,strlen($ke))=>$vl);
				   }
				}
				unset($queryPart['token']);
				DB::table('gpg_field_component')->insert($queryPart+array('id'=>$getMaxId,'part_number'=>$part_number,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'modified_by'=>1));
				return Redirect::to('parts/add_field_component')->withSuccess('New Comp. has been created successfully');
			}
		}
		$type_arr = DB::table('gpg_field_component_type')->where('status','=','A')->orderBy('name')->lists('name','id');
		$gpg_vendor = DB::table('gpg_vendor')->where('status','=','A')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'type_arr'=>$type_arr,'gpg_vendor'=>$gpg_vendor);
		return View::make('parts.add_field_component', $params);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
	        '_gpg_field_material_type_id' => 'required',           
	        '_description' => 'required',
	        'part_number' => 'required|unique:gpg_field_material'

    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('parts/create')->withErrors($validator);
		}else{
			$part_number = Input::get('part_number');
			$getMaxId = DB::table('gpg_field_material')->max('id')+1;
			$queryPart = array();
			while (list($ke,$vl)= each($_POST)) {
			   if (preg_match("/^_/i",$ke)) {
			   		$queryPart += array(substr($ke,1,strlen($ke))=>$vl);
			   }
			}
			unset($queryPart['token']);
			DB::table('gpg_field_material')->insert($queryPart+array('id'=>$getMaxId,'part_number'=>$part_number,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'modified_by'=>1));
			return Redirect::to('parts/create')->withSuccess('New Part has been created successfully');
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
		$type_arr = DB::table('gpg_field_material_type')->orderBy('name')->lists('name','id');
		$gpg_vendor = DB::table('gpg_vendor')->where('status','=','A')->orderBy('name')->lists('name','id');
		$data = DB::table('gpg_field_material')->select('*')->where('id','=',$id)->get();
		$params = array('left_menu' => $modules,'type_arr'=>$type_arr,'gpg_vendor'=>$gpg_vendor,'data'=>$data);
		return View::make('parts.edit', $params);
	}

	/*
	* editFieldComponent
	*/
	public function editFieldComponent($id){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)) {
			$rules = array(
	        '_gpg_field_component_type_id' => 'required',           
	        'part_number' => 'required'

	    	);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()){
	 	        $allInputs = Input::except('_token');
			    Input::flash();
				$messages = $validator->messages();
		        return Redirect::to('parts/edit_field_component/'.$id)->withErrors($validator);
			}else{
				$part_number = Input::get('part_number');
				$queryPart = array();
				while (list($ke,$vl)= each($_POST)) {
				   if (preg_match("/^_/i",$ke)) {
				   		$queryPart += array(substr($ke,1,strlen($ke))=>$vl);
				   }
				}
				unset($queryPart['token']);
				DB::table('gpg_field_component')->where('id','=',$id)->update($queryPart+array('part_number'=>$part_number,'modified_on'=>date('Y-m-d'),'modified_by'=>1));
				return Redirect::to('parts/edit_field_component/'.$id)->withSuccess('Comp. has been updated successfully');
			}

		}
		$type_arr = DB::table('gpg_field_component_type')->orderBy('name')->lists('name','id');
		$gpg_vendor = DB::table('gpg_vendor')->where('status','=','A')->orderBy('name')->lists('name','id');
		$data = DB::table('gpg_field_component')->select('*')->where('id','=',$id)->get();
		$params = array('left_menu' => $modules,'type_arr'=>$type_arr,'gpg_vendor'=>$gpg_vendor,'data'=>$data);
		return View::make('parts/edit_field_component', $params);
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
	        '_gpg_field_material_type_id' => 'required',           
	        '_description' => 'required',
	        'part_number' => 'required'

    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('parts/'.$id.'/edit')->withErrors($validator);
		}else{
			$part_number = Input::get('part_number');
			$queryPart = array();
			while (list($ke,$vl)= each($_POST)) {
			   if (preg_match("/^_/i",$ke)) {
			   		$queryPart += array(substr($ke,1,strlen($ke))=>$vl);
			   }
			}
			unset($queryPart['token']);
			unset($queryPart['method']);
			DB::table('gpg_field_material')->where('id','=',$id)->update($queryPart+array('part_number'=>$part_number,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'modified_by'=>1));
			return Redirect::to('parts/'.$id.'/edit')->withSuccess('Part has been updated successfully');
		}
	}
	public function switchPartStatus(){
		$id = Input::get('id');
		$status = Input::get('status');
		DB::table('gpg_field_material')->where('id','=',$id)->update(array('is_active'=>$status));
		return Redirect::to('parts/index')->withSuccess('Updated successfully');
	}
	public function switchCompStatus(){
		$id = Input::get('id');
		$status = Input::get('status');
		DB::table('gpg_field_component')->where('id','=',$id)->update(array('is_active'=>$status));
		return Redirect::to('parts/index')->withSuccess('Updated successfully');
	}
	public function switchArchStatus(){
		$id = Input::get('id');
		$status = Input::get('status');
		DB::table('gpg_job_electrical_subquote_existing_fixtures')->where('id','=',$id)->update(array('archive_status'=>$status));
		return 1;
	}
	/*
	*addRebate
	*/
	public function addRebate(){
		$modules = Generic::modules();
		$query_data = array();
		$qry = DB::select(DB::raw("select  * from gpg_rebate order by id DESC"));
		foreach ($qry as $key => $value) {
			$query_data[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('parts.add_rebate', $params);
	}

	/*
	* fieldMaterialUploader
	*/
	public function fieldMaterialUploader(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			if (isset($_POST['hidden_count']) && !empty($_POST['hidden_count']) && $_POST['hidden_count']>0){
				$destinationPath = Input::get('dest');
				$filename = Input::get('filename');
				$fh = fopen($destinationPath.$filename,'r');
				$opt = fgets($fh); //remove first line of file for heading and search for matched headings
				$file_headings = explode('	', $opt);
				$partNumber = '';
				$heading = array();
				$jobCat = Input::get('jobCat');
				for ($i=1; $i<=Input::get('hidden_count'); $i++) { 
					$heading[] = Input::get('db_field_'.$i);
				}
				while ($opt = fgets($fh)){
					$setValue = array();
					$job_num = '';
					$values = explode('	', $opt);
					for ($i=0; $i<count($heading); $i++) { 
						if (preg_match("/part_number/i",$heading[$i])){
							$partNumber = $values[$i];
						}elseif (preg_match("/materialType/i",$heading[$i])){
							$materialType = $values[$i];
						}elseif (preg_match("/cost/i",$heading[$i]) || preg_match("/list/i",$heading[$i])) { 
				 			$setValue += array($heading[$i]=>str_replace(",","",str_replace("\$","",$values[$i])));	
					 	}elseif (preg_match("/date/i",$heading[$i])){
							$setValue += array($heading[$i]=>date('Y-m-d',strtotime($values[$i])));
						}elseif (preg_match("/vendor/i",$heading[$i])){
							if (!is_numeric($values[$i])){
								$cid = DB::table('gpg_vendor')->max('id')+1;
								DB::table('gpg_vendor')->insert(array('name'=>$values[$i],'id'=>$cid,'status'=>'A','created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
								$setValue += array('gpg_vendor_id'=>$cid);
							}else
								$setValue += array('gpg_vendor_id'=>$values[$i]);
						}  
					}
					if($partNumber!='' && $materialType!='' && !empty($setValue))	{
						$check_id = DB::table('gpg_field_material')->where('part_number','LIKE','%'.$partNumber.'%')->pluck('id');
						if (empty($check_id)){
							DB::table('gpg_field_material')->insert($setValue+array('modified_on'=>date('Y-m-d'),'created_on'=>date('Y-m-d')));			
						}else
							DB::table('gpg_field_material')->where('id','=',$check_id)->update($setValue+array('modified_on'=>date('Y-m-d')));			
					}
				}//end while
				return Redirect::to('parts/field_material_uploader_opt')->withSuccess('Records have been Updated Successfully');
			}else{
				$file = Input::file('uploadFile');
				$filename = "";
				if (!empty($file)) {
					$file1 = Input::file('uploadFile')->getClientOriginalName();
					$filename = "partUploader_".rand(99999,10000000)."_".strtotime("now").".".$file1;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
				}
				$fh = fopen($destinationPath.$filename,'r');
				$jcostopt = trim(fgets($fh));
				$jcostopt_arr = explode("\t", $jcostopt);
				fclose($fh);
				$DBFields = array("part_number"=>"Part Number","materialType"=>"Field Material Type","vendor"=>"Vendor","description"=>"Description","cost"=>"Cost","list"=>"List","margin"=>"Margin","manufacturer"=>"Manufacturer","note"=>"Note","model_number"=>"Model Number","serial_number"=>"Serial Number","spec_number"=>"Spec Number","job_num"=>"Job Number");
				$params = array('left_menu' => $modules,'success'=>'0','step'=>'1','jcostopt_arr'=>$jcostopt_arr,'DBFields'=>$DBFields,'dest'=>$destinationPath,'filename'=>$filename);
				return View::make('parts/field_material_uploader_opt', $params);
			}//end else
		}
		$params = array('left_menu' => $modules,'step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array(),'success'=>'0');
		return View::make('parts.field_material_uploader_opt', $params);
	}

	/*
	* updateCreateRebate
	*/
	public function updateCreateRebate(){
		$id = Input::get('id');
		$measure = Input::get('measure');
		$desc = Input::get('description');
		$amount = Input::get('amount');
		$type = Input::get('type');
		$year = Input::get('year');
		if (!empty($id))
			DB::table('gpg_rebate')->where('id','=',$id)->update(array('rebate_measure'=>$measure,'rebate_description'=>$desc,'rebate_amount'=>$amount,'rebate_type'=>$type,'rebate_start_year'=>$year,'modified_on'=>date('Y-m-d')));
		else
			DB::table('gpg_rebate')->insert(array('rebate_measure'=>$measure,'rebate_description'=>$desc,'rebate_amount'=>$amount,'rebate_type'=>$type,'rebate_start_year'=>$year,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
		return 1;
	}

	/*
	* fieldComponentTypeIndex
	*/
	public function fieldComponentTypeIndex(){
		$modules = Generic::modules();
		$data = DB::table('gpg_field_component_type')->select('*')->get();
		$data_arr = array();
		foreach ($data as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'query_data'=>$data_arr);
		return View::make('parts.field_component_type_index', $params);
	}
	public function updateEquipType(){
		$id = Input::get('id');
		$name = Input::get('name');
		DB::table('gpg_field_component_type')->where('id','=',$id)->update(array('name'=>$name));
		return 1;
	}

	/*
	* manageFixtureType
	*/
	public function manageFixtureType(){
		$modules = Generic::modules();
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$status = Input::get("status");
		$DSQL = "";
		$DQ2 = " order by id desc ";
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
		   if ($Filter !="status") $DSQL.= " AND $Filter like '%$FVal%'"; 
		   elseif ($Filter =="status") $DSQL.= " AND $Filter = '$status'";
		}
		$result = DB::select(DB::raw("select * from gpg_job_electrical_subquote_fixtures_type WHERE 1 $DSQL $DQ2"));
		$query_data = array();
		foreach ($result as $key => $value) {
			$query_data[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('parts.manage_fixture_type', $params);
	}

	/*
	* editEFix
	*/
	public function editEFix($id){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			$rules = array(
	        '_gpg_job_electrical_subquote_fixtures_type_id' => 'required',           
	        'fixture_name' => 'required'
	    	);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()){
	 	        $allInputs = Input::except('_token');
			    Input::flash();
				$messages = $validator->messages();
		        return Redirect::to('parts/edit_existing_fixture/'.$id)->withErrors($validator);
			}else{
				$fixture_name = Input::get('fixture_name');
				$queryPart = array();
				while (list($ke,$vl)= each($_POST)) {
				    if(preg_match("/^_/i",$ke))
				   		$queryPart += array(substr($ke,1,strlen($ke)) => str_replace("\r","",str_replace("\n","",($vl))));
				}
				unset($queryPart['token']);
				DB::table('gpg_job_electrical_subquote_existing_fixtures')->where('id','=',$id)->update($queryPart+array('fixture_name'=>$fixture_name,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				return Redirect::to('parts/edit_existing_fixture/'.$id)->withSuccess('Existing Fixture updated successfully');
			}

		}//end if
		$qry = DB::table('gpg_job_electrical_subquote_existing_fixtures')->select('*')->where('id','=',$id)->get();
		$types = DB::table('gpg_job_electrical_subquote_fixtures_type')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'types'=>$types,'data'=>$qry);
		return View::make('parts/edit_existing_fixture', $params);
	}

	/*
	*updateFixType
	*/
	public function updateFixType(){
		$id = Input::get('id');
		$status = Input::get('status');
		$ftype = Input::get('ftype');
		DB::table('gpg_job_electrical_subquote_fixtures_type')->where('id','=',$id)->update(array('name'=>$ftype,'status'=>$status));
		return 1;
	}

	/*
	* destroyEFix
	*/
	public function destroyEFix($id){
		DB::table('gpg_job_electrical_subquote_existing_fixtures')->where('id','=',$id)->delete();
		return Redirect::to('parts/existing_fixture_index')->withSuccess('Deleted successfully');
	}

	/*
	* deleteFixtureType
	*/
	public function deleteFixtureType($id){
		DB::table('gpg_job_electrical_subquote_fixtures_type')->where('id','=',$id)->delete();
		return Redirect::to('parts/manage_fixture_type')->withSuccess('Deleted successfully');
	}

	/*
	* deleteEquipType
	*/
	public function deleteEquipType($id){
		DB::table('gpg_field_component_type')->where('id','=',$id)->delete();
		return Redirect::to('parts/field_component_type_index')->withSuccess('Deleted successfully');
	}
	/*
	* deleteSEquip
	*/
	public function deleteSEquip($id){
		DB::table('gpg_field_component')->where('id','=',$id)->delete();
		return Redirect::to('parts/field_component_index')->withSuccess('Deleted successfully');
	}

	/*
	* deletePartType
	*/
	public function deletePartType($id){
		DB::table('gpg_field_material_type')->where('id','=',$id)->delete();
		return Redirect::to('parts/field_material_type_index')->withSuccess('Deleted successfully');
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		DB::table('gpg_field_material')->where('id','=',$id)->delete();
		return Redirect::to('parts/index')->withSuccess('Deleted successfully');
	}


}
