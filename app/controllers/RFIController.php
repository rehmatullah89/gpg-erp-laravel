<?php

class RFIController extends \BaseController {

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

		$params = array('left_menu' => $modules,'query_data'=>$query_data);
 		return View::make('rfi.index', $params);
	}
	public function search($page = 1, $limit = null){
		$modules = Generic::modules();
		Input::flash();		
		
		$page = Input::get('page', 1);
   		$data = $this->getByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);

		$params = array('left_menu' => $modules,'query_data'=>$query_data);
 		return View::make('rfi.index', $params);
	}

	public function getByPage($page = 1, $limit = null){
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
		$queryPart='';
		$jobNum = Input::get('jobNum');
		if($jobNum!=''){
			$queryPart=" AND job_num = '$jobNum'";
		}
		$tcount = DB::select(DB::raw("select count(id) as t_count from gpg_request_for_info where 1 $queryPart "));
		if (!empty($tcount) && isset($tcount[0]->t_count)){
			$results->totalItems = $tcount[0]->t_count;
		}
		$qry = DB::select(DB::raw("select *,(select created_on from gpg_request_for_info_comments where gpg_request_for_info_comments.gpg_rfi_id= gpg_request_for_info.id order by id DESC limit 1) as latest_comm_date
			from gpg_request_for_info where 1 $queryPart  order by status ASC,latest_comm_date DESC $limitOffset"));
		$data_arr = array();
		foreach ($qry as $key2 => $value2) {
			foreach ($value2 as $key => $value) {
				if ($key == 'gpg_requested_by_id'){
					$q2 = DB::select(DB::raw("select concat(fname,' ',lname) as fname from gpg_ad_acc where ad_id = '".$value."'"));
					if (!empty($q2) && isset($q2[0]->fname))
						$temp_arr['fname'] = $q2[0]->fname;
					else
						$temp_arr['fname'] = '-';		
				}
				if ($key == 'gpg_requested_to_id'){
					$q3 = DB::select(DB::raw("select name from gpg_employee where id = '".$value."'"));
					if (!empty($q3) && isset($q3[0]->name))
						$temp_arr['rtname'] = $q3[0]->name;
					else
						$temp_arr['rtname'] = '-';		
				}
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
		$emps = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id'); 
		$params = array('left_menu' => $modules,'emps'=>$emps);
 		return View::make('rfi.create', $params);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$counter = Input::get('counter');
		for ($i=0; $i <=$counter ; $i++) { 
			$title = Input::get('title_'.$i);
			$JobNumber = Input::get('JobNumber_'.$i);
			$RequestToId = Input::get('RequestToId_'.$i);
			$rfi = Input::get('rfi_'.$i);
			$rfiStatus = Input::get('rfiStatus_'.$i);
			$file = Input::file('fileToUpload_'.$i);
			$jobId = DB::table('gpg_job')->where('job_num','=',$JobNumber)->pluck('id');
			$yes = DB::table('gpg_request_for_info')->insert(array('job_num'=>$JobNumber,'gpg_job_id'=>$jobId,'gpg_requested_by_id'=>'1','gpg_requested_to_id'=>$RequestToId,'title'=>$title,'is_admin'=>'1','status'=>$rfiStatus,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));			
			if($yes)
			{
				$rfiId = DB::table('gpg_request_for_info')->max('id');
				$file_type_settings =  DB::table('gpg_settings')
			            ->select('*')
			            ->where('name', '=', '_ImgExt')
			            ->get();    
				$file_types = explode(',', $file_type_settings[0]->value);
				if (!empty($file)){
						if (in_array($file->getClientOriginalExtension(), $file_types)) {
					  		$ext1 = explode(".",$file->getClientOriginalName());
						 	$ext2 = end($ext1);
						 	$filename = "rfiFile_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
							$destinationPath = public_path().'/img/';
							$uploadSuccess = $file->move($destinationPath, $filename);
							DB::table('gpg_request_for_info_comments')->insert(array('gpg_rfi_id'=>$rfiId,'gpg_commenter_id'=>'1','rfi_message'=>$rfi,'is_admin'=>'1','filename'=>$filename,'displayname'=>$file->getClientOriginalName(),'created_on'=>date('Y-m-d')));	
						}
				}else
					DB::table('gpg_request_for_info_comments')->insert(array('gpg_rfi_id'=>$rfiId,'gpg_commenter_id'=>'1','rfi_message'=>$rfi,'is_admin'=>'1','created_on'=>date('Y-m-d')));					
			}else
				return Redirect::to('rfi/create')->withErrors('Error Occured!');
		}
		return Redirect::to('rfi/create')->withSuccess('RFI has been created successfully');
	}

	/*
	* requestForInfo	
	*/
	public  function requestForInfo($jobNum)
	{
		$job_num = DB::table('gpg_request_for_info')->where('id','=',$jobNum)->pluck('job_num');
		$mquery = DB::select(DB::raw("select gpg_request_for_info.*,(select name from gpg_employee where gpg_employee.id= gpg_request_for_info.gpg_requested_by_id) as emp_name,(select concat(fname,' ',lname) as full_name from gpg_ad_acc where gpg_ad_acc.ad_id= gpg_request_for_info.gpg_requested_to_id) as full_name from gpg_request_for_info where id='".$jobNum."'"));
		$res_data = array();
		$comments = array();
		foreach ($mquery as $key => $value) {
			$res_data = (array)$value;
			$cdata = DB::select(DB::raw("select gpg_request_for_info_comments.*,(select name from gpg_employee where gpg_employee.id= gpg_request_for_info.gpg_requested_by_id) as emp_name,(select concat(fname,' ',lname) as full_name from gpg_ad_acc where gpg_ad_acc.ad_id= gpg_request_for_info.gpg_requested_to_id) as full_name from gpg_request_for_info_comments,gpg_request_for_info where gpg_request_for_info.id=gpg_request_for_info_comments.gpg_rfi_id and gpg_rfi_id='".$value->id."' order By created_on ASC"));
		}
		foreach ($cdata as $key => $value) {
			$comments[] = (array)$value;
		}
		$modules = Generic::modules();
		$params = array('left_menu' => $modules,'res_data'=>$res_data,'comments'=>$comments);
 		return View::make('rfi.request_for_info', $params);
	}

	/*
	* closeDiscussion
	*/
	public function closeDiscussion(){
		$id = Input::get('id');
		if (!empty($id))
		DB::table('gpg_request_for_info')->where('id','=',$id)->update(array('status'=>'1'));
		return 1;
	}
	
	/*
	* deleteDiscussion
	*/
	public function deleteDiscussion(){
		$id = Input::get('id');
		if (!empty($id))
		DB::table('gpg_request_for_info')->where('id','=',$id)->delete();
		DB::table('gpg_request_for_info_comments')->where('gpg_rfi_id','=',$id)->delete();
		return 1;
	}
	/*
	* saveReply
	*/
	public function saveReply(){
		$rfi_id = Input::get('rfi_id');
		$new_rfi = Input::get('new_rfi');
		$commenter = Input::get('gpg_requested_by_id');
		$file = Input::file('fileToUpload');
		if(!empty($new_rfi)){
			if (!empty($file)){
						if (in_array($file->getClientOriginalExtension(), $file_types)) {
					  		$ext1 = explode(".",$file->getClientOriginalName());
						 	$ext2 = end($ext1);
						 	$filename = "rfiFile_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
							$destinationPath = public_path().'/img/';
							$uploadSuccess = $file->move($destinationPath, $filename);
							DB::table('gpg_request_for_info_comments')->insert(array('gpg_rfi_id'=>$rfi_id,'gpg_commenter_id'=>$commenter,'rfi_message'=>$new_rfi,'is_admin'=>($commenter=='1'?1:0),'filename'=>$filename,'displayname'=>$file->getClientOriginalName(),'created_on'=>date('Y-m-d')));	
						}
			}else
				DB::table('gpg_request_for_info_comments')->insert(array('gpg_rfi_id'=>$rfi_id,'gpg_commenter_id'=>$commenter,'rfi_message'=>$new_rfi,'is_admin'=>($commenter=='1'?1:0),'created_on'=>date('Y-m-d')));	
		}
		return Redirect::to('rfi/request_for_info/'.$rfi_id)->withSuccess('Message Saved Successfully!');
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
