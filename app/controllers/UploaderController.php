<?php

class UploaderController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			if (isset($_POST['hidden_count']) && !empty($_POST['hidden_count']) && $_POST['hidden_count']>0){
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
				while ($opt = fgets($fh)){
					$setValue = array();
					$job_num = '';
					$values = explode('	', $opt);
					for ($i=0; $i<count($heading); $i++) { 
						if (preg_match("/job_num/i",$heading[$i])){
							$job_num = $values[$i];
						}elseif (preg_match("/complete/i",$heading[$i])) { 
				 			$setValue += array($heading[$i]=>(($values[$i]=='' || $values[$i]=='0')?0:1));	
					 	}elseif (preg_match("/date_completion/i",$heading[$i]) || preg_match("/date/i",$heading[$i])){
							$setValue += array($heading[$i]=>date('Y-m-d',strtotime($values[$i])));
						}elseif (preg_match("/schedule_date/i",$heading[$i])){
							if ($values[$i] == '')
								$values[$i]= date('Y-m-d');
							$setValue += array($heading[$i]=>date('Y-m-d',strtotime($values[$i])),'created_on'=>date('Y-m-d',strtotime($values[$i])),'modified_on'=>date('Y-m-d',strtotime($values[$i])));
						}elseif (preg_match("/customer/i",$heading[$i])){
							if (!is_numeric($values[$i])){
								$cid = DB::table('gpg_customer')->max('id')+1;
								DB::table('gpg_customer')->insert(array('name'=>$values[$i],'id'=>$cid,'status'=>'A','created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
								$setValue += array('GPG_customer_id'=>$cid);
							}else
								$setValue += array('GPG_customer_id'=>$values[$i]);
						}elseif (preg_match("/salesPerson/i",$heading[$i])){
							if (!is_numeric($values[$i])){
								$eid = DB::table('gpg_employee')->max('id')+1;
								DB::table('gpg_employee')->insert(array('name'=>$values[$i],'id'=>$eid,'status'=>'A','created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
								$setValue += array('GPG_employee_id'=>$eid);
							}else
								$setValue += array('GPG_employee_id'=>$values[$i]);
						}  
					}
					$ot_arr = array('GPG_job_type_id'=>$jobCat,'status'=>'N','GPG_wage_plan_id'=>1,'priority'=>'MEDIUM');
					if (!empty($job_num)){
						DB::table('gpg_job')->where('job_num','=',$job_num)->update($setValue+$ot_arr);
					}
				}//end while
				return Redirect::to('uploader/index')->withSuccess('Records have been Updated Successfully');
			}else{
				$file = Input::file('uploadFile');
				$filename = "";
				if (!empty($file)) {
					$file1 = Input::file('uploadFile')->getClientOriginalName();
					$filename = "bulkFilUpload_".rand(99999,10000000)."_".strtotime("now").".".$file1;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
				}
				$fh = fopen($destinationPath.$filename,'r');
				$jcostopt = trim(fgets($fh));
				$jcostopt_arr = explode("\t", $jcostopt);
				fclose($fh);
				$job_cat_arr = DB::table('gpg_job_type')->lists('name','id');
				$DBFields = array("date_completion"=>"Completed Date","complete"=>"Cleared","cleared_reason"=>"Cleared Reason","customer"=>"Customer Name/Company Name","salesPerson"=>"Sales Person","job_num"=>"Job Number","location"=>"Location","generator_size"=>"Generator Size","task"=>"Task/What To Do","sub_task"=>"Task Details","address1"=>"Address","city"=>"City","state"=>"State","zip"=>"Zip Code","phone"=>"Phone","schedule_date"=>"Start / Schedule Date","tax_amount"=>"TAX","invoice_number"=>"Invoice Number","invoice_date"=>"Invoice Date","invoice_amount"=>"Invoice Amount","cost_to_dat"=>"Cost To Date","contract_number"=>"Contract No.");
				$params = array('left_menu' => $modules,'success'=>'0','step'=>'1','jcostopt_arr'=>$jcostopt_arr,'DBFields'=>$DBFields,'dest'=>$destinationPath,'filename'=>$filename,'job_cat_arr'=>$job_cat_arr);
				return View::make('uploader.index', $params);
			}	
		}
		$params = array('left_menu' => $modules,'job_cat_arr'=>array(),'step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array(),'success'=>'0');
		return View::make('uploader.index', $params);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
