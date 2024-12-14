<?php

class ActivityController extends \BaseController {

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
   		$data = $this->getActivityByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('activity.index', $params);
	}
	
	public function getActivityByPage($page = 1, $limit = null)
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
		$user_type = Input::get("user_type");
		$DSQL = "";
		$DQ2 = " order by id desc";
		if ($SDate!="" || $EDate!="") {
			if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND DATE_FORMAT(datetime_stamp,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'" ;    
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND datetime_stamp <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'" ;
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (datetime_stamp >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."' 
						AND datetime_stamp <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')" ; 
			}
		}
		if ($Filter!="" && ($FVal!="" || $user_type!="")) {
		    if ($Filter !="user_type") 
		   		$DSQL.= " AND $Filter like '%$FVal%'"; 
		    elseif ($Filter =="user_type") $DSQL.= " AND $Filter = '$user_type'"; 
		}
		$total_rec = DB::select(DB::raw("select count(id) as t_id from gpg_activity_log WHERE 1 $DSQL"));
		$results->totalItems = @$total_rec[0]->t_id;
		$data_arr = array();
		$qry = DB::select(DB::raw("select * from gpg_activity_log WHERE 1 $DSQL $DQ2 $limitOffset"));
		foreach ($qry as $key => $value) {
			$data_arr[] = (array)$value;
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
		DB::table('gpg_activity_log')->where('id','=',$id)->delete();
		return Redirect::to('activity.index')->withSuccess('Deleted successfully');
	}


}
