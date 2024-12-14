<?php

class EquipmentController extends \BaseController {

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
   		$data = $this->getByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
 		$eqps = DB::table('gpg_equipment_type')->lists('name','id');
		$teqps = DB::table('gpg_equipment')->count('id');
		$aeqps = DB::table('gpg_equipment')->where('status','=','A')->count('id');
		$beqps = DB::table('gpg_equipment')->where('status','=','B')->count('id');
		$params = array('left_menu' => $modules,'teqps'=>$teqps,'aeqps'=>$aeqps,'beqps'=>$beqps,'query_data'=>$query_data,'eqps'=>$eqps);
		return View::make('equipment.index', $params);
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
	  $SDate = Input::get("SDate");
	  $EDate = Input::get("EDate");
	  $Filter = Input::get("Filter");
	  $FVal = Input::get("FVal");
	  $eqp_type = Input::get("eqp_type");
	  $ownership = Input::get("ownership");
	  $DSQL = "";
	  $DQ2 = " order by id desc ";
	  $status = "";

	  if ($SDate!="" || $EDate!="") {
	      if ($SDate!="" && $EDate =="") {
		    $DSQL.= " AND DATE_FORMAT(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
		  } 
		  elseif ($SDate == "" && $EDate != ""){
			$DSQL.= " AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
				$DSQL.= " AND (created_on >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
				    AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
	  	}
	  if ($Filter!="" && ($FVal!="" || $eqp_type!="" || $ownership!="")) {
		    if ($Filter !="ownership" and $Filter!="eqp_type") 
		   		$DSQL.= " AND $Filter like '%$FVal%'"; 
		   elseif ($Filter =="ownership" and $ownership!='') $DSQL.= " AND $Filter = '$ownership'"; 
		   elseif ($Filter =="eqp_type" and $eqp_type!='') $DSQL.= " AND gpg_equipment_type_id = '$eqp_type'"; 
	  }
	  $count = DB::select(DB::raw("select count(id) as t_count from gpg_equipment WHERE 1 $DSQL"));
	  if (!empty($count) && isset($count[0]->t_count)){
	  	$results->totalItems = $count[0]->t_count;
	  }
	  $qry = DB::select(DB::raw("select *,(select sum(ifnull(gpg_job_equipment.qty_out,0)-ifnull(gpg_job_equipment.qty_in,0)) from gpg_job_equipment , gpg_job  where gpg_job_equipment.gpg_job_id = gpg_job.id and gpg_job_equipment.eqp_num = gpg_equipment.eqp_num and gpg_job.rental_status not in (1,6)) as eqp_count from gpg_equipment WHERE 1 $DSQL $DQ2 $limitOffset"));
	  $data_arr = array();
	  foreach ($qry as $key2 => $value2) {
	  	foreach ($value2 as $key => $value) {
	  		$temp_arr[$key] = $value; 
	  	}
	  	$data_arr[] = $temp_arr; 
	  }
	  	$results->items = $data_arr;
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
		$eqps = DB::table('gpg_equipment_type')->lists('name','id');
		$params = array('left_menu' => $modules,'eqps'=>$eqps);
		return View::make('equipment.create', $params);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
	        'eqpType'             => 'required',           
	        'eqp_num' => 'required|unique:gpg_equipment'         
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('equipment/create')->withErrors($validator);
		}else{
			$eqpType = Input::get('eqpType');
			$queryPart = array();
			$queryPart += array('eqp_num'=>Input::get('eqp_num'));
			while (list($ke,$vl)= each($_POST)) {
		    	if (preg_match("/^_/i",$ke) && $ke!= '_token') 
		    		$queryPart[substr($ke,1,strlen($ke))] = $vl;
			}
			if ($eqpType!="1" && $eqpType!="3") 
				$queryPart += array('serial'=>'');
			DB::table('gpg_equipment')->insert($queryPart+array('created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'gpg_equipment_type_id'=>$eqpType));
			return Redirect::to('equipment/create')->withSuccess('New Equipment has been created successfully');
		}
	}
	public function listRentalCompany(){
		$modules = Generic::modules();
		$start = 0;
		$limit = 100;
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
		if ($Filter!="" && ($FVal!="" || $language!="" || $status!="")) {	   
		   if ($Filter !="status" and $Filter!="new_member") 
			   $DSQL.= " AND $Filter like '%$FVal%'"; 
		   elseif ($Filter =="status") $DSQL.= " AND $Filter = '$status'"; 
		   elseif ($Filter =="new_member") { 
		       $DQ2= " order by created_on desc ";
			}		  
		}
		$result = DB::select(DB::raw("select * from gpg_rental_company WHERE 1 $DSQL $DQ2 limit $start,$limit"));
		$qry_data = array();
		foreach ($result as $key => $value) {
			$qry_data[] = (array)$value;
		}
		$trentals = DB::table('gpg_rental_company')->count('id');
		$arentals = DB::table('gpg_rental_company')->where('status','=','A')->count('id');
		$brentals = DB::table('gpg_rental_company')->where('status','=','B')->count('id');
		
		$params = array('left_menu' => $modules,'qry_data'=>$qry_data,'trentals'=>$trentals,'arentals'=>$arentals,'brentals'=>$brentals);
		return View::make('equipment.list_rental_company', $params);
	}

	/*
	* addRentalCompany
	*/
	public function addRentalCompany(){
		$modules = Generic::modules();
		if(!empty($_POST)){
			$queryPart = array();
			while (list($ke,$vl)= each($_POST)) {
			   if (preg_match("/^_/i",$ke) && $ke != '_token') 
			   	$queryPart[substr($ke,1,strlen($ke))] = $vl;
			}
			DB::table('gpg_rental_company')->insert($queryPart+array('created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'status'=>'A'));
			return Redirect::to('equipment/add_rental_company')->withSuccess('New Rental Company has been created successfully');
		}
		$params = array('left_menu' => $modules);
		return View::make('equipment.add_rental_company', $params);
	}
	/*
	* equipmentReadingLog
	*/
	public function equipmentReadingLog(){
		$modules = Generic::modules();
		$start = 0;
		$limit = 100;
		$prev = ""; // new defined
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$status = Input::get("status");
		$DSQL = "";
		$DQ2 = " order by eqp_num, created_on desc ";
		if ($SDate!="" || $EDate!=""){
		    if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND DATE_FORMAT(created_on) = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (created_on >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
			        AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
		}
		if ($Filter!="" && $FVal!="" ) {
		   $DSQL.= " AND (select id from gpg_equipment where eqp_num like '%$FVal%') = gpg_equipment_id "; 
		}
		$query = DB::select(DB::raw("select *,(select eqp_num from gpg_equipment where id = gpg_equipment_id) as eqp_num from gpg_equipment_reading WHERE 1 $DSQL $DQ2 limit $start,$limit"));
		$data_arr = array();
		foreach ($query as $key => $value) {
			$data_arr[] = (array)$value;
		}

		$params = array('left_menu' => $modules,'data_arr'=>$data_arr);
		return View::make('equipment.equipment_reading_log', $params);
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
		$eqp_types = DB::table('gpg_equipment_type')->lists('name','id');
		$data_obj = DB::table('gpg_equipment')->select('*')->where('id','=',$id)->get();
		$data_arr = array();
		foreach ($data_obj as $key => $value) {
			$data_arr = (array)$value;
		}
		$params = array('left_menu' => $modules,'eqps'=>$eqp_types,'data_arr'=>$data_arr);
		return View::make('equipment.edit', $params);
	}
	public function saveNote(){
		$id = Input::get('note_id');
		$note = Input::get('note_text');
		if (!empty($id) && !empty($note)) {
			DB::table('gpg_equipment')->where('id','=',$id)->update(array('note'=>$note));
		}
		return Redirect::to('equipment/')->withSuccess('Note updated successfully');;	
	}
	public function saveReading(){
		$id = Input::get('reading_id');
		$note = Input::get('new_reading');
		if (!empty($id) && !empty($note)) {
			DB::table('gpg_equipment_reading')->where('gpg_equipment_id','=',$id)->update(array('reading'=>$note));
		}
		return Redirect::to('equipment/')->withSuccess('Reading updated successfully');;	
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
	        'eqpType'             => 'required',           
	        'eqp_num' => 'required|unique:gpg_equipment'         
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('equipment/'.$id.'/edit')->withErrors($validator);
		}else{
			Input::flash();
			$eqpType = Input::get('eqpType');
			$queryPart = array();
			$queryPart += array('eqp_num'=>Input::get('eqp_num'));
			while (list($ke,$vl)= each($_POST)) {
		    	if (preg_match("/^_/i",$ke) && $ke!= '_token' && $ke != 'method') 
		    		$queryPart[substr($ke,1,strlen($ke))] = $vl;
			}
			unset($queryPart['method']);
			if ($eqpType!="1" && $eqpType!="3") 
				$queryPart += array('serial'=>'');
			DB::table('gpg_equipment')->where('id','=',$id)->update($queryPart+array('modified_on'=>date('Y-m-d'),'gpg_equipment_type_id'=>$eqpType));
			return Redirect::to('equipment/'.$id.'/edit')->withSuccess('Equipment updated successfully');
		}
	}
	
	/*
	* deleteEquipment
	*/
	public function deleteEquipment($id){
		if (!empty($id)){
			DB::table('gpg_equipment')->where('id','=',$id)->delete();
			return Redirect::to('equipment/index')->withSuccess('Deleted successfully');
		}else
			return Redirect::to('equipment/index')->withErrors('There is problem with deletion!');
	}

	/*
	* delEquipReadingLog
	*/
	public function delEquipReadingLog($id){
		if (!empty($id)){
			DB::table('gpg_equipment_reading')->where('id','=',$id)->delete();
			return Redirect::to('equipment/equipment_reading_log')->withSuccess('Deleted successfully');
		}else
			return Redirect::to('equipment/equipment_reading_log')->withErrors('There is problem with deletion!');
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
			DB::table('gpg_rental_company')->where('id','=',$id)->delete();
			return Redirect::to('equipment/list_rental_company')->withSuccess('Deleted successfully');
		}
		return Redirect::to('equipment/list_rental_company')->withErrors('There is problem with deletion!');
	}


}
