<?php

class ExpenseController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		$tcount = DB::table('gpg_expense_type')->count('id');	
		$acount = DB::table('gpg_expense_type')->where('status','=','A')->count('id');	
		$bcount = DB::table('gpg_expense_type')->where('status','=','B')->count('id');	
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$status = Input::get("status");
		$ID = Input::get("ID");
		$DSQL = "";
		$start = 0;
		$limit = 100;
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
		if ($Filter!="" && ($FVal!="" || $ID!="" || $status!="")) {
		   if ($Filter =="status") $DSQL.= " AND $Filter = '$status'";
		   elseif ($Filter =="ID") $DSQL.= " AND $Filter = '$ID'";
		   elseif ($Filter =="new_member") { 
		       $DQ2= " order by id desc ";
			}	
		}
		$result = DB::select(DB::raw("select * from gpg_expense_type WHERE 1 $DSQL $DQ2 limit $start,$limit"));
		$query_data = array();
		foreach ($result as $key => $value){
			$query_data[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'tcount'=>$tcount,'acount'=>$acount,'bcount'=>$bcount,'query_data'=>$query_data);
		return View::make('expense.index', $params);
	}

	/*
	* expenseAmtImpOpt
	*/
	public function expenseAmtImpOpt(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			if (isset($_POST['hidden_count']) && !empty($_POST['hidden_count']) && $_POST['hidden_count']>0) {
				//////////////////////////////////////////////////////***********
				$start_date = Input::get('start_date');
				$end_date = Input::get('end_date');
				$destinationPath = Input::get('dest');
				$filename = Input::get('filename');
				$fh = fopen($destinationPath.$filename,'r');
				$opt = fgets($fh); //remove first line of file for heading and search for matched headings
				$file_headings = explode('	', $opt);
				$heading = array();
				$jobCat = Input::get('jobCat');
				for ($i=1; $i<=Input::get('hidden_count'); $i++) { 
					$heading[] = Input::get('db_field_'.$i);
				}
				DB::table('gpg_gl_expense')->whereBetween('date',array($start_date,$end_date))->delete();
				while ($opt = fgets($fh)){
					$setValue = array();
					$job_num = '';
					$values = explode('	', $opt);
					for ($i=0; $i<count($heading); $i++) { 
						if (preg_match("/date/i",$heading[$i])){
							$setValue += array($heading[$i]=>date('Y-m-d',strtotime($values[$i])));
						}elseif (preg_match("/amount/i",$heading[$i]) || preg_match("/credit/i",$heading[$i]) || preg_match("/debit/i",$heading[$i])){
							$setValue += array($heading[$i]=>str_replace(",","",str_replace("\$","",$values[$i])));						
						}else{
								$setValue += array($heading[$i]=>$values[$i]);
						}  
					}
					if (!empty($setValue) && array_key_exists('num', $setValue)){
						DB::table('gpg_gl_expense')->insert($setValue+array('modified_on'=>date('Y-m-d'),'created_on'=>date('Y-m-d')));
					}
				}//end while
				return Redirect::to('expense/expense_amt_imp_opt')->withSuccess('Records has been inserted Successfully');
				///////////////////////////////////////////////////////```````````````
			}else{
				$file = Input::file('uploadFile');
				$SDate = Input::get('SDate');
				$EDate = Input::get('EDate');
				$filename = "";
				if (!empty($file)) {
					$file1 = Input::file('uploadFile')->getClientOriginalName();
					$filename = "expGlCodes_".rand(99999,10000000)."_".strtotime("now").".".$file1;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
				}
				$fh = fopen($destinationPath.$filename,'r');
				$jcostopt = trim(fgets($fh));
				$jcostopt_arr = explode("\t", $jcostopt);
				fclose($fh);
				$DBFields = array("type"=>"Type","date"=>"Date","num"=>"Num","name"=>"Name","source_name"=>"Source Name","memo"=>"Memo","class"=>"Class","clr"=>"Clr","split"=>"Split","debit"=>"Debit","credit"=>"Credit","amount"=>"Amount");
				$params = array('left_menu' => $modules,'success'=>'0','step'=>'1','jcostopt_arr'=>$jcostopt_arr,'DBFields'=>$DBFields,'dest'=>$destinationPath,'filename'=>$filename,'SDate'=>$SDate,'EDate'=>$EDate);
				return View::make('expense/expense_amt_imp_opt', $params);
			}
		}
		$params = array('left_menu' => $modules,'step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array(),'success'=>'0');
		return View::make('expense/expense_amt_imp_opt', $params);
	}

	/*
	* currentExpense
	*/
	public function currentExpense(){
		$modules = Generic::modules();
		$emps = DB::table('gpg_employee')->orderBy('name')->lists('name','id');
		$cust = DB::table('gpg_customer')->orderBy('name')->lists('name','id');
		$expt = DB::table('gpg_expense_type')->orderBy('exp_type')->lists('exp_type','id');
		$emp_options = '';
		foreach ($emps as $key => $value) {
			$emp_options .= '<option value='.$key.'>'.preg_replace("/'/", "\&#39;", $value).'</option>';
		}
		$cus_options = '';
		foreach ($cust as $key => $value) {
			$cus_options .= '<option value='.$key.'>'.preg_replace("/'/", "\&#39;", $value).'</option>';
		}
		$expType_options = '';
		foreach ($expt as $key => $value) {
			$expType_options .= '<option value='.$key.'>'.preg_replace("/'/", "\&#39;", $value).'</option>';
		}
		$tDays =""; //new defined
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$emp = Input::get("emp");
		$cus = Input::get("cus");
		$job = Input::get("job");
		$expenseType = Input::get("expenseType");
		$queryPart='';
		if ($SDate!="" and $EDate!="") $queryPart .= " AND a.expense_date >= '".date('Y-m-d',strtotime($SDate))."' AND a.expense_date <= '".date('Y-m-d',strtotime($EDate))."' ";
		 elseif ($SDate!="") $queryPart .= " AND a.expense_date = '".date('Y-m-d',strtotime($SDate))."'";
		if ($Filter!="" && ($FVal!="" || $emp!="" || $cus!="" || $job!="" || $expenseType!="")){
		   if ($Filter =="emp") 
		   $queryPart.= " AND a.gpg_empolyee_id='". $emp."'"; 
		   if ($Filter =="cus") 
		   $queryPart.= " AND a.gpg_customer_id='". $cus."'";
		   if ($Filter =="expenseType") 
		   $queryPart.= " AND a.gpg_expense_type_id='". $expenseType."'";
		   if ($Filter =="job") 
		   $queryPart.= " AND ifnull(a.GPG_job_id,'') = ".(empty($job)?"''":"(select id from gpg_job where job_num = '".$job."')"); 
		}
		$getExpense = "select a.*,(select b.id) as employee, b.name, (select name from gpg_customer where id = a.gpg_customer_id) as customer_name ,(select job_num from gpg_job where id = a.GPG_job_id) as jobNumber ,(select exp_type from gpg_expense_type where id = a.gpg_expense_type_id) as expense_type from gpg_expense a, gpg_employee b where a.gpg_empolyee_id=b.id ".$queryPart;
		$qry = DB::select(DB::raw($getExpense));
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'emp_options'=>$emp_options,'cus_options'=>$cus_options,'expType_options'=>$expType_options,'data_arr'=>$data_arr);
		return View::make('expense/current_expense', $params);
	}

	/*
	* expenseHistory
	*/
	public function expenseHistory(){
		$modules = Generic::modules();
		$emps = DB::table('gpg_employee')->orderBy('name')->lists('name','id');
		$cust = DB::table('gpg_customer')->orderBy('name')->lists('name','id');
		$expt = DB::table('gpg_expense_type')->orderBy('exp_type')->lists('exp_type','id');
		$emp_options = '';
		foreach ($emps as $key => $value) {
			$emp_options .= '<option value='.$key.'>'.preg_replace("/'/", "\&#39;", $value).'</option>';
		}
		$cus_options = '';
		foreach ($cust as $key => $value) {
			$cus_options .= '<option value='.$key.'>'.preg_replace("/'/", "\&#39;", $value).'</option>';
		}
		$expType_options = '';
		foreach ($expt as $key => $value) {
			$expType_options .= '<option value='.$key.'>'.preg_replace("/'/", "\&#39;", $value).'</option>';
		}
		$tDays =""; //new defined
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$emp = Input::get("emp");
		$cus = Input::get("cus");
		$job = Input::get("job");
		$expenseType = Input::get("expenseType");
		$queryPart='';
		if ($SDate!="" and $EDate!="") $queryPart .= " AND a.expense_date >= '".date('Y-m-d',strtotime($SDate))."' AND a.expense_date <= '".date('Y-m-d',strtotime($EDate))."' ";
		 elseif ($SDate!="") $queryPart .= " AND a.expense_date = '".date('Y-m-d',strtotime($SDate))."'";
		if ($Filter!="" && ($FVal!="" || $emp!="" || $cus!="" || $job!="" || $expenseType!="")){
		   if ($Filter =="emp") 
		   $queryPart.= " AND a.gpg_empolyee_id='". $emp."'"; 
		   if ($Filter =="cus") 
		   $queryPart.= " AND a.gpg_customer_id='". $cus."'";
		   if ($Filter =="expenseType") 
		   $queryPart.= " AND a.gpg_expense_type_id='". $expenseType."'";
		   if ($Filter =="job") 
		   $queryPart.= " AND ifnull(a.GPG_job_id,'') = ".(empty($job)?"''":"(select id from gpg_job where job_num = '".$job."')"); 
		}
		$queryPart.= "order by a.gpg_empolyee_id, process_date desc";
		$getExpense = "select a.*, b.id as employee, b.name, (select name from gpg_customer where id = a.gpg_customer_id) as customer_name ,(select job_num from gpg_job where id = a.GPG_job_id) as jobNumber ,(select exp_type from gpg_expense_type where id = a.gpg_expense_type_id) as expense_type from gpg_expense_history a, gpg_employee b where a.gpg_empolyee_id=b.id ".$queryPart;
		$qry = DB::select(DB::raw($getExpense));
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'emp_options'=>$emp_options,'cus_options'=>$cus_options,'expType_options'=>$expType_options,'data_arr'=>$data_arr);
		return View::make('expense/expense_history', $params);
	}

	/*
	* glcodeExpenseIndex
	*/
	public function glcodeExpenseIndex(){
		$modules = Generic::modules();
		$qr1 = "select a.id as parentID,b.id as childID,a.expense_gl_code as parentGlCode, b.expense_gl_code as childGlCode,a.description as parentDescription,b.description as childDescription,a.status as parentStatus,b.status as childStatus from gpg_expense_gl_code a LEFT JOIN gpg_expense_gl_code b ON a.id = b.parent_id where a.parent_id = 0";
		$find_glc = DB::select(DB::raw($qr1));
		$options = '';
		$prev = '';
		foreach ($find_glc as $key => $row) {
			if ($prev!= $row->parentID){
				$options .= '<option value='.$row->parentID.'>'.preg_replace("/'/", "\&#39;", $row->parentGlCode).'-'.preg_replace("/'/", "\&#39;", $row->parentDescription).'</option>';
			}if ($row->childID){
				$options .= '<option value='.$row->childID.'>'.preg_replace("/'/", "\&#39;", $row->childGlCode).'-'.preg_replace("/'/", "\&#39;", $row->childDescription).'</option>';
			}
			$prev = $row->parentID;
		}
		Input::flash();		
		$page = Input::get('page', 1);
   		$data = $this->getByPage($page, 100);
  		$data_arr = Paginator::make($data->items, $data->totalItems, 100);

		$params = array('left_menu' => $modules,'options'=>$options,'data_arr'=>$data_arr);
		return View::make('expense/glcode_expense_index', $params);
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
		$expenseGl = Input::get("expenseGl");
	    $DSQL = "";
		$DQ2 = "  order by b.parent_id asc,a.gpg_expense_gl_code_id,a.id,a.date desc";
		$prev = "";
		if ($SDate!="" || $EDate!="") { 
		    if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND DATE_FORMAT(a.date,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND a.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (a.date >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
			            AND a.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
		}
		if ($Filter!="" && ($FVal!="" || $expenseGl!="")) {  
		   if ($Filter !="expenseGl") 
		   $DSQL.= " AND a.$Filter like '%$FVal%'"; 
		   elseif ($Filter =="expenseGl" && $expenseGl!='') { 
		     $q = mysql_query("select id from gpg_expense_gl_code where parent_id = '".$expenseGl."'");
			 while ($r = mysql_fetch_array($q)) $qPart .= ','.$r['id'];
		     $DSQL.= " AND a.gpg_expense_gl_code_id in( $expenseGl $qPart )"; 
		   }
		}
		$count = DB::select(DB::raw("select count(a.id) as t_count from gpg_gl_expense a LEFT JOIN gpg_expense_gl_code b ON a.gpg_expense_gl_code_id = b.id WHERE 1 $DSQL"));
		if (isset($count) && !empty($count[0]->t_count)){
			$results->totalItems = $count[0]->t_count;
		}
		$qry = DB::select(DB::raw("select a.*,concat(b.expense_gl_code,' - ',b.description) as expenseGlCode,b.parent_id as expenseParent from gpg_gl_expense a LEFT JOIN gpg_expense_gl_code b ON a.gpg_expense_gl_code_id = b.id WHERE 1 $DSQL $DQ2 $limitOffset"));
		$data_arr = array();
		foreach ($qry as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		/*echo "<pre>";
		print_r($count);
		die();*/
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
		return View::make('expense.create',array('left_menu' => $modules));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
	        'exp_type' => 'required|unique:gpg_expense_type'         
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('expense/create')->withErrors($validator);
		}else{
			$exp_type = Input::get('exp_type'); 
			$status = Input::get('status'); 
			$id = DB::table('gpg_expense_type')->max('id')+1;
			DB::table('gpg_expense_type')->insert(array('id'=>$id,'exp_type'=>$exp_type,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'status'=>$status));
			return Redirect::to('expense/create')->withSuccess('New Expense Type has been created successfully');
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
		$qry = DB::table('gpg_expense_type')->where('id','=',$id)->select('*')->get();
		$row = array();
		foreach ($qry as $key => $value) {
			$row = (array)$value;
		}
		return View::make('expense.edit',array('left_menu' => $modules,'row'=>$row));
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
	        'exp_type' => 'required'         
    	);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()){
 	        $allInputs = Input::except('_token');
		    Input::flash();
			$messages = $validator->messages();
	        return Redirect::to('expense/'.$id.'/edit')->withErrors($validator);
		}else{
			$exp_type = Input::get('exp_type'); 
			$status = Input::get('status'); 
			DB::table('gpg_expense_type')->where('id','=',$id)->update(array('exp_type'=>$exp_type,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'),'status'=>$status));
			return Redirect::to('expense/'.$id.'/edit')->withSuccess('New Expense Type has been created successfully');
		}
	}

	/*
	* excelGLExpExport
	*/
	public function excelGLExpExport(){
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
	   		$data = $this->getByPage($page);
	  		$data_arr = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('data_arr'=>$data_arr);
			$sheet->loadView('expense.excelGLExpExport',$params);
		  });
		})->export('xls');
	}

	/*
	* deleteGlCodeExpense
	*/
	public function deleteGlCodeExpense($id){
		if(!empty($id)){
			DB::table('gpg_gl_expense')->where('id','=',$id)->delete();
			return Redirect::to('expense/glcode_expense_index')->withSuccess('Deleted successfully');
		}
		return Redirect::to('expense/glcode_expense_index')->withErrors('There is problem with deletion!');
	}

	/*
	* deleteCurrentExp
	*/
	public function deleteCurrentExp($id){
		if(!empty($id)){
			DB::table('gpg_expense')->where('id','=',$id)->delete();
			return Redirect::to('expense/current_expense')->withSuccess('Deleted successfully');
		}
		return Redirect::to('expense/current_expense')->withErrors('There is problem with deletion!');
	}

	/*
	* deleteExpHistory
	*/
	public function deleteExpHistory($id){
		if(!empty($id)){
			DB::table('gpg_expense_history')->where('id','=',$id)->delete();
			return Redirect::to('expense/expense_history')->withSuccess('Deleted successfully');
		}
		return Redirect::to('expense/expense_history')->withErrors('There is problem with deletion!');
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
			DB::table('gpg_expense_type')->where('id','=',$id)->delete();
			return Redirect::to('expense/index')->withSuccess('Deleted successfully');
		}
		return Redirect::to('expense/index')->withErrors('There is problem with deletion!');
	
	}


}
