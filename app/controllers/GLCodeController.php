<?php

class GLCodeController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		Input::flash();
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$status = Input::get("status");
		$DSQL = "";
		$DQ2 = " order by id desc ";
		$start =0;
		$language ='';
		$limit = 100;
		if ($SDate!="" || $EDate!=""){		  
		    if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND DATE_FORMAT(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (created_on >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
			            AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
		}
		if ($Filter!="" && $status!="") {   
		    if ($Filter =="status") 
		   		$DSQL.= " AND $Filter = '$status'"; 
		}
		$query = DB::select(DB::raw("select * from gpg_gl_code WHERE 1 $DSQL $DQ2 limit $start,$limit"));
		$data_arr = array();
		foreach ($query as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$total_codes = DB::table('gpg_gl_code')->count('id');
		$active_codes = DB::table('gpg_gl_code')->where('status','=','A')->count('id');
		$iac_codes = DB::table('gpg_gl_code')->where('status','=','B')->count('id');
		
		$params = array('left_menu' => $modules,'query'=>$query,'data_arr'=>$data_arr,'total_codes'=>$total_codes,'active_codes'=>$active_codes,'iac_codes'=>$iac_codes);
		return View::make('glcode.index', $params);
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
		return View::make('glcode.create', $params);
	}

	/*
	* addExpenseGlcode
	*/
	public function addExpenseGlcode(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			$_expense_gl_code = Input::get("_expense_gl_code");
			$_parent_id = Input::get("_parent_id");
			$action = Input::get("action");
			$backfrm = Input::get("backfrm");
			$queryPart = array();
			$tags = "";
			$input = Input::all();
			Input::flash();
            $rules = array(
                    'expense_gl_code' => 'required|unique:gpg_expense_gl_code|max:20',
                    '_description' => 'required|max:150'
            );
			$validation = Validator::make($input, $rules);
			if ($validation->fails()){
                return Redirect::to('glcode/add_expense_glcode')->withErrors($validation);
            }
			while (list($ke,$vl)= each($_POST)) {
			   if (preg_match("/parent_id/i",$ke)) 
			   	$queryPart[substr($ke,1,strlen($ke))] = ($vl==''?0:$vl);
			   elseif(preg_match("/gpg_expense_gl_tags/i",$ke)){
				   if(preg_match("/gpg_expense_gl_tags_parent/i",$ke))
				   	$tags = ($vl==''?0:$vl);
					elseif(preg_match("/gpg_expense_gl_tags_child/i",$ke) && $vl!="" && $tags!=0)
					$tags .= (".".$vl);	
			   }
			   else if (preg_match("/^_/i",$ke)) 
			   	$queryPart[substr($ke,1,strlen($ke))] = $vl;
			}
			unset($queryPart['token']);
			if (!array_key_exists('exclude_from_oh',$queryPart)) {
				$queryPart += array('exclude_from_oh'=>0);
			}
			if($tags != "")
				$queryPart += array('gpg_expense_gl_tags'=>$tags);

			$gcodearr = DB::select(DB::raw("select id from gpg_expense_gl_code where expense_gl_code LIKE '$_expense_gl_code' and ".($_parent_id?"parent_id = $_parent_id":"parent_id = 0").""));
			if (!empty($gcodearr)) {
				return Redirect::to('glcode/add_expense_glcode')->withErrors('GL-Code aleady exists!');
			}else{
				$success = DB::table('gpg_expense_gl_code')->insert($queryPart+array('created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));	
				if ($success){
					$gl_code_inserted_id = DB::table('gpg_expense_gl_code')->max('id');
						$total_tag_levels = Input::get('counter_row');

						for($loop = 1; $loop <= $total_tag_levels; $loop++){
							$parent_id = Input::get('parent_tag_'.$loop);
							$child_id = Input::get('child_tag_'.$loop);
							if($parent_id!="" or $child_id!="")
							{
								$p_id = explode("~",$parent_id);
								DB::table('gpg_expense_gl_code_tags_detail')->insert(array('gpg_expense_gl_code_id'=>$gl_code_inserted_id,
										'gpg_expense_gl_code_tags_parent_id'=>$p_id[0],
										'gpg_expense_gl_code_tags_child_id' => $child_id,
										'tag_group_id' => $p_id[1],
										'created_on' => date('Y-m-d')
									));
							}
						}
				}
				return Redirect::to('glcode/add_expense_glcode')->withSuccess('GL-Code Created successfully');
			}
		}//data posted end
		$gcode_arr = DB::select(DB::raw("select id,concat(expense_gl_code,' ',description) as gcode from gpg_expense_gl_code where status='A' and parent_id = 0"));
		$gcodes = array();
		foreach ($gcode_arr as $key => $value) {
			$gcodes[$value->id] = $value->gcode ;
		}
		$gltypes = DB::table('gpg_expense_gl_type')->lists('type','id');
		$ptype = DB::select(DB::raw("SELECT CONCAT(id,'~',tag_group) AS id,tag_name FROM gpg_expense_gl_code_tags WHERE is_parent=0 ORDER BY id"));
		$ptypeArr = array();
		foreach ($ptype as $key => $value) {
			$ptypeArr[$value->id] = $value->tag_name;
		}
		$ctype = DB::select(DB::raw("SELECT id,tag_name FROM gpg_expense_gl_code_tags WHERE is_parent=1 ORDER BY id"));
		$ctypeArr = array();
		foreach ($ctype as $key => $value) {
			$ctypeArr[$value->id] = $value->tag_name;
		}
		$params = array('left_menu' => $modules,'gcodes'=>$gcodes,'gltypes'=>$gltypes,'ptypeArr'=>$ptypeArr,'ctypeArr'=>$ctypeArr);
		return View::make('glcode/add_expense_glcode', $params);
	}

	/*
	* editExpenseGlcode
	*/
	public function editExpenseGlcode($id){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			$_expense_gl_code = Input::get("_expense_gl_code");
			$_parent_id = Input::get("_parent_id");
			$action = Input::get("action");
			$backfrm = Input::get("backfrm");
			$queryPart = array();
			$tags = "";
			while (list($ke,$vl)= each($_POST)) {
			    if (preg_match("/parent_id/i",$ke)) 
			   		$queryPart[substr($ke,1,strlen($ke))] = ($vl==''?0:$vl);
			   	elseif(preg_match("/gpg_expense_gl_tags/i",$ke)){
					if(preg_match("/gpg_expense_gl_tags_parent/i",$ke))
					   	$tags = ($vl==''?0:$vl);
						elseif(preg_match("/gpg_expense_gl_tags_child/i",$ke) && $vl!="" && $tags!=0)
						$tags .= (".".$vl);	
			   	}
			   else if (preg_match("/^_/i",$ke)) 
			   	$queryPart[substr($ke,1,strlen($ke))] = $vl;
			}
			if (!array_key_exists('exclude_from_oh', $queryPart)){
				$queryPart += array('exclude_from_oh'=>0);
			}
			/*echo "<pre>";
			print_r($queryPart);
			die();*/
			unset($queryPart['token']);
			if (!array_key_exists('exclude_from_oh',$queryPart)) {
				$queryPart += array('exclude_from_oh'=>0);
			}
			if($tags != "")
				$queryPart += array('gpg_expense_gl_tags'=>$tags);
			$gcodearr = DB::select(DB::raw("select id from gpg_expense_gl_code where expense_gl_code LIKE '$_expense_gl_code' and ".($_parent_id?"parent_id = $_parent_id":"parent_id = 0").""));
			if (!empty($gcodearr)) {
				return Redirect::to('glcode/edit_expense_glcode/'.$id)->withErrors('GL-Code aleady exists!');
			}else{
				DB::table('gpg_expense_gl_code')->where('id','=',$id)->update($queryPart+array('modified_on'=>date('Y-m-d')));
				DB::table('gpg_expense_gl_code_tags_detail')->where('gpg_expense_gl_code_id','=',$id)->delete();
				$total_tag_levels = Input::get('counter_row');
				for($loop = 1; $loop <= $total_tag_levels; $loop++){
					$parent_id = Input::get('parent_tag_'.$loop);
					$child_id = Input::get('child_tag_'.$loop);
					if($parent_id!="" or $child_id!="")
					{
						$p_id = explode("~",$parent_id);
						DB::table('gpg_expense_gl_code_tags_detail')->insert(array('gpg_expense_gl_code_id'=>$id,
							'gpg_expense_gl_code_tags_parent_id'=>@$p_id[0],
							'gpg_expense_gl_code_tags_child_id' => $child_id,
							'tag_group_id' => @$p_id[1],
							'created_on' => date('Y-m-d')
						));
					}
				}
				return Redirect::to('glcode/edit_expense_glcode/'.$id)->withSuccess('GL-Code Updated successfully');
			}

		}// end post save data
		$gcode_arr = DB::select(DB::raw("select id,concat(expense_gl_code,' ',description) as gcode from gpg_expense_gl_code where status='A' and parent_id = 0"));
		$gcodes = array();
		foreach ($gcode_arr as $key => $value) {
			$gcodes[$value->id] = $value->gcode ;
		}
		$gltypes = DB::table('gpg_expense_gl_type')->lists('type','id');
		$ptype = DB::select(DB::raw("SELECT CONCAT(id,'~',tag_group) AS id,tag_name FROM gpg_expense_gl_code_tags WHERE is_parent=0 ORDER BY id"));
		$ptypeArr = array();
		foreach ($ptype as $key => $value) {
			$ptypeArr[$value->id] = $value->tag_name;
		}
		$ctype = DB::select(DB::raw("SELECT id,tag_name FROM gpg_expense_gl_code_tags WHERE is_parent=1 ORDER BY id"));
		$ctypeArr = array();
		foreach ($ctype as $key => $value) {
			$ctypeArr[$value->id] = $value->tag_name;
		}
		$res = DB::select(DB::raw("select *,(select count(id) from gpg_expense_gl_code where parent_id=a.id) as parentCnt from gpg_expense_gl_code a where id = '$id'"));
		$data_res = array();
		foreach ($res as $key => $value) {
			$data_res = (array)$value;
		}
		$tags_res = DB::table('gpg_expense_gl_code_tags_detail')->select('*')->where('gpg_expense_gl_code_id','=',$id)->get();
		$tags_array = array();
		foreach ($tags_res as $key => $tags_arr) {
			$tags_array[] = array('parent_tag'=>$tags_arr->gpg_expense_gl_code_tags_parent_id."~".$tags_arr->tag_group_id,'child_tag'=>$tags_arr->gpg_expense_gl_code_tags_child_id);				
		}	
		$params = array('left_menu' => $modules,'gcodes'=>$gcodes,'gltypes'=>$gltypes,'ptypeArr'=>$ptypeArr,'ctypeArr'=>$ctypeArr,'res'=>$data_res,'tags_array'=>$tags_array,'id'=>$id);
		return View::make('glcode/edit_expense_glcode', $params);
	}

	/*
	* newGLcodetype
	*/
	public function newGLcodetype(){
		$modules = Generic::modules();
		$data = DB::table('gpg_expense_gl_type')->select('*')->get();
		$params = array('left_menu' => $modules,'data'=>$data);
		return View::make('glcode.new_type', $params);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$gl_code = Input::get("gl_code");
		$description = Input::get("description");
		$status = Input::get("status");
		$id = Input::get("id");
		$action = Input::get("action");
		$rules = array(
	        'gl_code'             => 'required|unique:gpg_gl_code|max:20',           
	        'description' => 'required|max:200'         
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('glcode/create')->withErrors($validator);
		}else{
			DB::table('gpg_gl_code')->insert(array('gl_code'=>$gl_code,'description'=>$description,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'status'=>$status));
			return Redirect::to('glcode/index')->withSuccess('New GL-Code has been created successfully');
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
		$qry = DB::table('gpg_gl_code')->where('id','=',$id)->get();
		$row = array();
		foreach ($qry as $key => $value) {
			$row = (array)$value;
		}
		$params = array('left_menu' => $modules,'row'=>$row);
		return View::make('glcode.edit', $params);	
	}
	/*
	* updateGLCType
	*/
	public function updateGLCType(){
		$id = Input::get('id');
		$type = Input::get('type');
		$input = Input::all();
		Input::flash();
        $rules = array(
            'type' => 'required|unique:gpg_expense_gl_type|max:30'
        );
		$validation = Validator::make($input, $rules);
		if ($validation->fails()){
            return 0;
        }
		if (!empty($id) && !empty($type)){
			DB::table('gpg_expense_gl_type')->where('id','=',$id)->update(array('type'=>$type));
		}
		return Redirect::to('glcode/new_type');
	}
	/*
	* createGLCEType
	*/
	public function createGLCEType(){
		$glcode = Input::get('type');
		$rules = array(
		    'type' => 'required|unique:gpg_expense_gl_type|max:20'
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('glcode/new_type')->withErrors($validator);
		}else{
			DB::table('gpg_expense_gl_type')->insert(array('type'=>$glcode));
			return Redirect::to('glcode/new_type')->withSuccess('New Expense GL-Code has been created successfully');
		}
	}
	/*
	* expenseGlcodeManage
	*/
	public function expenseGlcodeManage(){
		$modules = Generic::modules();
		Input::flash();		
		$page = Input::get('page', 1);
   		$data = $this->getByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$total_codes = DB::table('gpg_expense_gl_code')->count('id');
		$active_codes = DB::table('gpg_expense_gl_code')->where('status','=','A')->count('id');
		$iac_codes = DB::table('gpg_expense_gl_code')->where('status','=','B')->count('id');
		$gl_tags_rs = DB::select(DB::raw("SELECT id,value FROM gpg_settings WHERE NAME LIKE '_gl_tags%'"));
		$arr_tags_names = array(0 => 'No Tag');
		foreach ($gl_tags_rs as $key => $gl_vals) {
			$arr_tags_names[$gl_vals->id] = $gl_vals->value;		
		}
		$params = array('left_menu' => $modules,'total_codes'=>$total_codes,'active_codes'=>$active_codes,'iac_codes'=>$iac_codes,'query_data'=>$query_data,'arr_tags_names'=>$arr_tags_names);
		return View::make('glcode.expense_glcode_index', $params);
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
	  $status = Input::get("status");
	  $DSQL = "";
	  $DQ2 = " order by id desc ";
	  if ($SDate!="" || $EDate!=""){		  
		    if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND DATE_FORMAT(b.created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND b.created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (b.created_on >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
			            AND b.created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
		}
	  if ($Filter!="" && ($FVal!="" || $status!="")) {
	    if ($Filter !="status") $DSQL.= " AND a.$Filter like '%$FVal%' OR b.$Filter like '%$FVal%' "; 
	    elseif ($Filter =="status") $DSQL.= " AND a.$Filter = '$status' OR b.$Filter = '$status'"; 
	  }
	  $DSQL.= " order by a.id desc";
	  $count = DB::select(DB::raw("select count(a.id) as t_count from gpg_expense_gl_code a LEFT JOIN gpg_expense_gl_code b ON a.id = b.parent_id where a.parent_id = 0 $DSQL"));
	  if (!empty($count) && isset($count[0]->t_count)) {
	  	$results->totalItems = $count[0]->t_count;
	  }
	  $result = DB::select(DB::raw("select a.id as parentID,b.id as childID,a.expense_gl_code as parentGlCode, b.expense_gl_code as childGlCode,a.description as parentDescription,b.description as childDescription,a.status as parentStatus,b.status as childStatus, a.exclude_from_oh as parent_exclude, b.exclude_from_oh as child_exclude, IFNULL(a.gpg_expense_gl_tags,0) as expense_tags from gpg_expense_gl_code a LEFT JOIN gpg_expense_gl_code b ON a.id = b.parent_id where a.parent_id = 0 $DSQL $limitOffset"));
	  $data_arr = array();
	  foreach ($result as $key => $value) {
	  	$data_arr[] = (array)$value;
	  }
	  $results->items = $data_arr;
	  return $results;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{	$oid = $id;
		$gl_code = Input::get("gl_code");
		$old_glCode = Input::get("old_glCode");
		$description = Input::get("description");
		$status = Input::get("status");
		$id = Input::get("id");
		$action = Input::get("action");
		if ($gl_code == $old_glCode)
			$rules = array(
		        'gl_code'    => 'required|max:20',           
		        'description' => 'required|max:200'         
	    	);
		else
			$rules = array(
		        'gl_code'    => 'required|unique:gpg_gl_code|max:20',           
		        'description' => 'required|max:200'         
	    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('glcode/'.$oid.'/edit')->withErrors($validator);
		}else{
			DB::table('gpg_gl_code')->where('id','=',$oid)->update(array('gl_code'=>$gl_code,'description'=>$description,'modified_on'=>date('Y-m-d'),'status'=>$status));
			return Redirect::to('glcode/'.$oid.'/edit')->withSuccess('GL-Code has been updated successfully');
		}
	}

	/*
	* deleteGlCType
	*/
	public function deleteGlCType($id){
		if (!empty($id)){
			DB::table('gpg_expense_gl_type')->where('id','=',$id)->delete();
			return Redirect::to('glcode/new_type')->withSuccess('Gl-Code Deleted successfully');
		}else
			return Redirect::to('glcode/new_type')->withErrors('There is problem with delet!');
	}

	/*
	* deleteExpenseGCode
	*/
	public function deleteExpenseGCode($id){
	  $parent = DB::table('gpg_expense_gl_code')->where('id','=',$id)->pluck('parent_id'); 
	  $del_message = DB::table('gpg_expense_gl_code')->where('id','=',$id)->delete();
	  if($del_message && $parent==0) 
	  	$del_parrent_message = DB::table('gpg_expense_gl_code')->where('parent_id','=',$id)->delet();
	  return Redirect::to('glcode/expense_glcode_index')->withSuccess('Gl-Expense Code Deleted successfully');
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
			DB::table('gpg_gl_code')->where('id','=',$id)->delete();
			return Redirect::to('glcode')->withSuccess('Deleted successfully');
		}
		return Redirect::to('glcode')->withErrors('There is problem with deletion!');
	}
}
