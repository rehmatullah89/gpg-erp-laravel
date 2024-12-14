<?php

class AssetController extends \BaseController {

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
		$eqpArr = DB::table('gpg_asset_equipment_type')->lists('name','id');
		$tid = DB::table('gpg_asset_equipment')->count('id');
		$aid = DB::table('gpg_asset_equipment')->where('status','=','1')->count('id');
		$bid = DB::table('gpg_asset_equipment')->where('status','=','0')->count('id');
		$params = array('query_data'=>$query_data,'left_menu' => $modules,'eqpArrs'=>$eqpArr,'tid'=>$tid,'aid'=>$aid,'bid'=>$bid);
		return View::make('asset.index', $params);
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
	  if ($Filter!="" && ($FVal!="" || $eqp_type!="" || $status!="")){		   
			if ($Filter !="status" and $Filter!="eqp_type") 
			   $DSQL.= " AND $Filter like '%$FVal%'"; 
			elseif ($Filter =="status" and $status!='') $DSQL.= " AND status = '$status'"; 
			elseif ($Filter =="eqp_type" and $eqp_type!='') $DSQL.= " AND gpg_asset_equipment_type_id = '$eqp_type'";   
      }
      $count = DB::select(DB::raw("select count(id) as t_count from gpg_asset_equipment WHERE 1 $DSQL"));
      if (!empty($count) && isset($count[0]->t_count)) {
      		$results->totalItems = $count[0]->t_count;
      }
      $qry = DB::select(DB::raw("select *,(select name from gpg_asset_equipment_type where id =gpg_asset_equipment_type_id) as asset_equipment_type from gpg_asset_equipment WHERE 1 $DSQL $DQ2 $limitOffset"));
      $qry_data = array();
      foreach ($qry as $key2 => $value2) {
      	foreach ($value2 as $key => $value) {
      		if ($key == 'id') {
      			$tempArr = array();
      			$q2 = DB::select(DB::raw("SELECT checkout_date,job_num,(SELECT name from gpg_employee where id = gpg_employee_id) as employee from gpg_asset_equipment_history where gpg_asset_equipment_id = '".$value."' and current_status = 'checkout' limit 0,1"));
	      		foreach ($q2 as $key3 => $value3) {
	      			$tempArr = (array)$value3;
	      		}
      			$temp_arr['eqpCheckinDate'] = 	$tempArr;
      		}
      		$temp_arr[$key] = $value;		
      	}	
      	$qry_data[] = $temp_arr;
      }
      $results->items = $qry_data;
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
		return View::make('asset.create', $params);
	}

	/*
	*addAssetEquipmentType
	*/
	public function addAssetEquipmentType(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)) {
				$type = Input::get('name');
				$rules = array(
		        'name' => 'required|unique:gpg_asset_equipment_type'         
	    	);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()){
	 	        $allInputs = Input::except('_token');
			    Input::flash();
				$messages = $validator->messages();
		        return Redirect::to('asset/add_asset_equipment_type')->withErrors($validator);
			}else{
				DB::table('gpg_asset_equipment_type')->insert(array('name'=>$type,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				return Redirect::to('asset/add_asset_equipment_type')->withSuccess('New Asset Equipment Type has been created successfully');
			}
		}//post ends here
		$params = array('left_menu' => $modules);
		return View::make('asset/add_asset_equipment_type', $params);
	}

	/*
	* assetEquipmentTypeManage
	*/
	public function assetEquipmentTypeManage(){
		$modules = Generic::modules();
		$qry = DB::table('gpg_asset_equipment_type')->select('*')->get();
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'data_arr'=>$data_arr);
		return View::make('asset/asset_equipment_type_index', $params);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
	        'gpg_asset_equipment_type_id' => 'required',           
	        'eqp_num' => 'required|unique:gpg_asset_equipment'         
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('asset/create')->withErrors($validator);
		}else{
			$id = Input::get('gpg_asset_equipment_type_id');
			$eqp_num = Input::get('eqp_num');
			$eqp_serial_num = Input::get('_eqp_serial_num');
			$eqp_plate_number = Input::get('_eqp_plate_number');
			$status = Input::get('_status');
			$description = Input::get('_description');
			$file = Input::file('eqp_image');
			if (!empty($file)){
				if (in_array($file->getClientOriginalExtension(), $file_types)) {
					$ext1 = explode(".",$file->getClientOriginalName());
				 	$ext2 = end($ext1);
				 	$filename = "assetImg_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = $file->move($destinationPath, $filename);
					//insert into db
					DB::table('gpg_asset_equipment')->insert(array('eqp_image' =>$filename,'gpg_asset_equipment_type_id'=>$id ,'eqp_num'=>$eqp_num ,'eqp_serial_num' =>$eqp_serial_num ,'eqp_plate_number' =>$eqp_plate_number,'status' =>$status,'description' =>$description,'created_on' =>date('Y-m-d'),'modified_on' =>date('Y-m-d')));		
				}
			}else{
				DB::table('gpg_asset_equipment')->insert(array('gpg_asset_equipment_type_id'=>$id ,'eqp_num'=>$eqp_num ,'eqp_serial_num' =>$eqp_serial_num ,'eqp_plate_number' =>$eqp_plate_number,'status' =>$status,'description' =>$description,'created_on' =>date('Y-m-d'),'modified_on' =>date('Y-m-d')));		
			}
			return Redirect::to('asset/create')->withSuccess('New Asset Equipment has been created successfully');
		}
	}
	/*
	* checkoutAssetEquipment
	*/
	public function checkoutAssetEquipment(){
		if (isset($_POST) && !empty($_POST)){
			$queryPart = array();
			while (list($ke,$vl)= each($_POST)) {
	   		   if (preg_match("/^_/i",$ke) && $ke != '_token') { 	   
				   if(preg_match("/date/i",$ke)) 
				   		$queryPart[substr($ke,1,strlen($ke))] = date('Y-m-d',strtotime($vl)); 
				   else 
				   		$queryPart[substr($ke,1,strlen($ke))] = $vl;
				}
			}
			$gpg_job_id = DB::table('gpg_job')->where('job_num','=',Input::get('_job_num'))->pluck('id');
			DB::table('gpg_asset_equipment_history')->insert(array('gpg_job_id'=>$gpg_job_id,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
			DB::table('gpg_asset_equipment')->where('id','=',Input::get('_gpg_asset_equipment_id'))->update(array('assign_status'=>'checkout','modified_on'=>date('Y-m-d')));
			return Redirect::to('asset/checkout_asset_equipment')->withSuccess('Equipment checkout made successfully');
		}
		$modules = Generic::modules();
		$qry = DB::select(DB::raw("select id,concat(eqp_num,' | ',description) as name from gpg_asset_equipment where eqp_condition ='0' and status = '1' and assign_status = 'checkin' order by eqp_num"));
		$asset_arr = array();
		foreach ($qry as $key => $value) {
			$asset_arr[$value->id] = $value->name;
		}
		$techs = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'asset_arr'=>$asset_arr,'techs'=>$techs);
		return View::make('asset/checkout_asset_equipment', $params);
	
	}

	/*
	*checkInAssetEquipment
	*/
	public function checkInAssetEquipment(){
		if (isset($_POST) && !empty($_POST)) {
			$hist_id = Input::get('line_id');
			$id = Input::get('_gpg_asset_equipment_id');
			$checkin_date = Input::get('_checkin_date');
			$health_check = Input::get('_health_check');
			$eqp_checkin_condition_description = Input::get('_eqp_checkin_condition_description');

			$queryPart = array();
			while (list($ke,$vl)= each($_POST)) {
	   		   if (preg_match("/^_/i",$ke) && $ke != '_token') { 	   
				   if(preg_match("/date/i",$ke)) 
				   		$queryPart[substr($ke,1,strlen($ke))] = date('Y-m-d',strtotime($vl)); 
				   else 
				   		$queryPart[substr($ke,1,strlen($ke))] = $vl;
				}
			}
			DB::table('gpg_asset_equipment_history')->where('id','=',$hist_id)->update($queryPart+array('modified_on'=>date('Y-m-d')));
			DB::table('gpg_asset_equipment')->where('id','=',$id)->update(array('assign_status'=>'checkin','eqp_condition'=>($health_check==1?'1':'0'),'modified_on'=>date('Y-m-d')));	
			return Redirect::to('asset/checkin_asset_equipment')->withSuccess('Equipment checkin made successfully');
		}
		$modules = Generic::modules();
		$asset_arr = array();
		$qry = DB::select(DB::raw("select id,concat(eqp_num,' | ',description) as name from gpg_asset_equipment where eqp_condition ='0' and status = '1' and assign_status = 'checkout' order by eqp_num"));
		foreach ($qry as $key => $value) {
			$asset_arr[$value->id] = $value->name;
		}	
		$params = array('left_menu' => $modules,'asset_arr'=>$asset_arr);
		return View::make('asset/checkin_asset_equipment', $params);
	}

	/*
	* getAssetEquipHistory
	*/
	public function getAssetEquipHistory(){
		$id = Input::get('id');
		$qry = DB::select(DB::raw("select *,(select name from gpg_employee where id = gpg_employee_id) as tech from gpg_asset_equipment_history WHERE gpg_asset_equipment_id = '$id' and current_status = 'checkout' order by created_on desc limit 0,1"));
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr = array('id'=>$value->id,'job_num'=>$value->job_num,'tech'=>$value->tech,'checkout_date'=>date('Y-m-d',strtotime($value->checkout_date)));	
		}
		return $data_arr;
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
		$data = array();
		$qry = DB::table('gpg_asset_equipment')->where('id','=',$id)->select('*')->get();
		foreach ($qry as $key => $value) {
			$data = (array)$value;
		}
		$params = array('left_menu' => $modules,'dataArr'=>$data);
		return View::make('asset.edit', $params);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$gpg_asset_equipment_type_id = Input::get('gpg_asset_equipment_type_id');
		$eqp_num = Input::get('eqp_num');
		$eqp_serial_num = Input::get('_eqp_serial_num');
		$eqp_plate_number = Input::get('_eqp_plate_number');
		$status = Input::get('_status');
		$description = Input::get('_description');
		$file = Input::file('eqp_image');
		if (!empty($file)){
			if (in_array($file->getClientOriginalExtension(), $file_types)) {
				$ext1 = explode(".",$file->getClientOriginalName());
			 	$ext2 = end($ext1);
			 	$filename = "assetImg_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
				$destinationPath = public_path().'/img/';
				$uploadSuccess = $file->move($destinationPath, $filename);
				//insert into db
				DB::table('gpg_asset_equipment')->where('id','=',$id)->update(array('eqp_image' =>$filename,'gpg_asset_equipment_type_id'=>$gpg_asset_equipment_type_id ,'eqp_num'=>$eqp_num ,'eqp_serial_num' =>$eqp_serial_num ,'eqp_plate_number' =>$eqp_plate_number,'status' =>$status,'description' =>$description,'created_on' =>date('Y-m-d'),'modified_on' =>date('Y-m-d')));		
			}
		}else{
				DB::table('gpg_asset_equipment')->where('id','=',$id)->update(array('gpg_asset_equipment_type_id'=>$gpg_asset_equipment_type_id ,'eqp_num'=>$eqp_num ,'eqp_serial_num' =>$eqp_serial_num ,'eqp_plate_number' =>$eqp_plate_number,'status' =>$status,'description' =>$description,'created_on' =>date('Y-m-d'),'modified_on' =>date('Y-m-d')));		
		}
			return Redirect::to('asset/'.$id.'/edit')->withSuccess('Asset Equipment has been Updated Successfully');	
	}
	/*
	* editAssetType
	*/
	public function editAssetType($id){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)) {
				$type = Input::get('name');
				$rules = array(
		        'name' => 'required|unique:gpg_asset_equipment_type'         
	    	);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()){
	 	        $allInputs = Input::except('_token');
			    Input::flash();
				$messages = $validator->messages();
		        return Redirect::to('asset/edit_asset_type/'.$id)->withErrors($validator);
			}else{
				DB::table('gpg_asset_equipment_type')->where('id','=',$id)->update(array('name'=>$type,'modified_on'=>date('Y-m-d')));
				return Redirect::to('asset/edit_asset_type/'.$id)->withSuccess('Asset Equipment Type has been updated successfully');
			}
		}
		$name = DB::table('gpg_asset_equipment_type')->where('id','=',$id)->pluck('name');
		$params = array('left_menu' => $modules,'id'=>$id,'name'=>$name);
		return View::make('asset/edit_asset_type', $params);
	}

	/*
	* getEquipHist
	*/
	public function getEquipHist(){
		$id = Input::get('id');
		$resHist= DB::select(DB::raw("select *,(select name from gpg_employee where id = gpg_employee_id) as tech from gpg_asset_equipment_history where gpg_asset_equipment_id='$id' order by id desc"));
		$str = '';
		$i=1;
		foreach ($resHist as $key => $value) {
			$str .= '<tr><td>'.($i++).'</td><td>'.$value->tech.'</td><td>'.$value->job_num.'</td><td>'.date('m/d/Y',strtotime($value->checkout_date)).'</td><td>'.$value->eqp_checkout_condition_description.'</td><td>'.date('m/d/Y',strtotime($value->checkin_date)).'</td><td>'.$value->eqp_checkin_condition_description.'</td></tr>';
		}
		return $str;
	}

	/*
	* getEquipHealth
	*/
	public function getEquipHealth(){
		$id = Input::get('id');
		$resHist= DB::select(DB::raw("select * from gpg_asset_equipment_health_history where gpg_asset_equipment_id='$id' order by id desc"));
		$str = '';
		$i=1;
		foreach ($resHist as $key => $value) {
			$str .= '<tr><td>'.($i++).'</td><td>'.date('m/d/Y',strtotime($value->checkin_date)).'</td><td>'.$value->eqp_checkin_condition_description.'</td><td>'.date('m/d/Y',strtotime($value->date)).'</td><td>'.$value->eqp_health_description.'</td></tr>';
		}
		return $str;
	}

	/*
	* deleteAssetType
	*/
	public function deleteAssetType($id){
		if (!empty($id)){
			DB::table('gpg_asset_equipment_type')->where('id','=',$id)->delete();	
			return Redirect::to('asset/asset_equipment_type_index')->withSuccess('Equipment Type deleted successfully');
		}
		return Redirect::to('asset/asset_equipment_type_index')->withErrors('There is error with deletion');
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
			DB::table('gpg_asset_equipment')->where('id','=',$id)->delete();
			return Redirect::to('asset')->withSuccess('Asset Equipment deleted successfully');
		}else{
			return Redirect::to('asset')->withErrors('There is error with deletion');		
		}
	}


}
