<?php

class EmailsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = Generic::modules();
		$start = 0; 
		$limit = 100;
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$mailbox = (Input::get("mailbox") != "") ? Input::get("mailbox") : "I";
		$email_filter = Input::get("email_filter") ;
		$ofieldposted = (Input::get("ofieldposted") != "") ? Input::get("ofieldposted") : "email";
		$arr_fields["email"] = "acc.email_add" ;
		$arr_fields["sender"] = ($mailbox == "I") ? "e.from_name" : "e.to_name" ;
		$arr_fields["subject"] = "e.email_subject" ;
		$arr_fields["job_num"] = "e.gpg_attach_job_num" ;
		$arr_fields["sent_date"] = "e.sent_date" ;
		$ofield = (Input::get("ofield") != "") ? $arr_fields[$ofieldposted] : $arr_fields[$ofieldposted] ;
		if(Input::get("otype") != ""){
			$last_order = Input::get("otype") ;
			$otype = (Input::get("otype") == "a") ? "ASC" : "DESC" ;
			$opost = ($otype == "ASC") ? "d" : "a" ;
		}else{
			$otype = "ASC" ;   $opost = "d" ; $last_order = "a" ;
		}
		$order_class = ($otype == "ASC") ? "up" : "down" ;
		$order_by_field = $ofield." ".$otype ;
		$sel_options = "" ; 
		$selected = "" ;
		$emails_found = false;
		$start_date = ($SDate != "") ? date('Y-m-d',strtotime($SDate)). " 00:00:00"  : "" ;
		$end_date = ($EDate != "") ? date('Y-m-d',strtotime($EDate)). " 23:59:59" : ""  ;
		$strFilter="";
		if($start_date != "" && $end_date!= ""){
			$strFilter = " AND sent_date >= '".$start_date."' AND sent_date <= '".$end_date."'" ;
		}
		elseif($start_date != ""){
			$end_date = date('Y-m-d',strtotime($SDate)). " 23:59:59"  ;
			$strFilter = " AND sent_date >= '".$start_date."' AND sent_date <= '".$end_date."'" ;
		}
		if ($Filter!="" && ($FVal!="")) {
			if($Filter=="sender")
				$strFilter .= " AND ".(($mailbox == "I") ? 'from_name' : 'to_name')." like('%".$FVal."%')";
			if($Filter=="subject")
				$strFilter .= " AND  email_subject like('%".$FVal."%')";
			if($Filter=="job_num")
				$strFilter .= " AND  e.gpg_attach_job_num like('%".$FVal."%')";
		}
		if($email_filter != ""){
			$strFilter .= " AND acc.email_add = '".$email_filter."'";
		}
		$count = DB::select(DB::raw("SELECT COUNT(e.id) as t_count FROM gpg_emails AS e,gpg_email_ids AS acc WHERE acc.id = e.gpg_account_id ".$strFilter."  AND status = '".$mailbox."'"));
		if (!empty($count) && isset($count[0]->t_count)) {
			$limit = $count[0]->t_count;
		}
		$qry = DB::select(DB::raw("SELECT  acc.email_add,
			  e.id AS email_id,
			  e.gpg_account_id,
			  e.gpg_attach_job_num,
			  e.message_id,
			  e.msg_no,
			  e.gpg_email_to_id,
			  e.gpg_email_to_id_all,
			  e.gpg_email_cc_id_all,
			  e.gpg_email_bcc_id_all,
			  e.gpg_email_from_id,
			  e.thread_references,
			  e.from_name,
			  e.to_name,
			  e.email_subject,
			  e.email_body,
			  e.status,
			  e.deleted,
			  e.unseen,
			  e.sent_date,
			  (SELECT
			     COUNT(*)
			   FROM gpg_email_attachments 
			   WHERE gpg_email_id = e.id) AS count_attachment
					FROM gpg_emails AS e,gpg_email_ids AS acc
					 WHERE acc.id = e.gpg_account_id ".$strFilter."  
						AND status = '".$mailbox."'
					 ORDER BY $order_by_field  LIMIT  ".$start.",".$limit));
		$data_arr = array();
		foreach ($qry as $key => $value){
			$data_arr[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'data_arr'=>$data_arr,'mailbox'=>$mailbox);
		return View::make('emails.index', $params);
	}

	/*
	* archiveEmails
	*/
	public function archiveEmails(){
		$modules = Generic::modules();
		$start = 0; 
		$limit = 100;
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$Filter = Input::get("Filter");
		$FVal = Input::get("FVal");
		$mailbox = (Input::get("mailbox") != "") ? Input::get("mailbox") : "I";
		$email_filter = Input::get("email_filter") ;
		$delChk = Input::get("delChk");
		$ofieldposted = (Input::get("ofieldposted") != "") ? Input::get("ofieldposted") : "sent_date";
		$arr_fields["email"] = "acc.email_add" ;
		$arr_fields["sender"] = ($mailbox == "I") ? "e.from_name" : "e.to_name" ;
		$arr_fields["subject"] = "e.email_subject" ;
		$arr_fields["job_num"] = "e.gpg_attach_job_num" ;
		$arr_fields["sent_date"] = "e.sent_date" ;
		$ofield = $arr_fields[$ofieldposted];
		if(Input::get("otype") != ""){
			$last_order = Input::get("otype") ;
			$otype = (Input::get("otype") == "a") ? "ASC" : "DESC" ;
			$opost = ($otype == "ASC") ? "d" : "a" ;
		}else{
			$otype = "ASC" ;   $opost = "d" ;	$last_order = "a" ;
		}
		$order_class = ($otype == "ASC") ? "up" : "down" ;
		$order_by_field = $ofield." ".$otype ;
		$sel_options = "" ; 
		$selected = "" ;
		$emails_found = true  ;
		$end_date = ($EDate != "") ? date('Y-m-d',strtotime($EDate)). " 23:59:59" : ""  ;
		$start_date = ($SDate != "") ? date('Y-m-d',strtotime($SDate)). " 00:00:00"  : "" ;
		$strFilter="";
		if($start_date != "" && $end_date!= ""){
			$strFilter = " AND sent_date >= '".$start_date."' AND sent_date <= '".$end_date."'" ;
		}
		elseif($start_date != ""){
			$end_date = date('Y-m-d',strtotime($SDate)). " 23:59:59"  ;
			$strFilter = " AND sent_date >= '".$start_date."' AND sent_date <= '".$end_date."'" ;
		}
		if ($Filter!="" && ($FVal!="")) {
			if($Filter=="sender")
				$strFilter .= " AND ".(($mailbox == "I") ? 'from_name' : 'to_name')." like('%".$FVal."%')";
			if($Filter=="subject")
				$strFilter .= " AND  email_subject like('%".$FVal."%')";
			if($Filter=="job_num")
				$strFilter .= " AND  e.gpg_attach_job_num like('%".$FVal."%')";
		}
		if($email_filter != ""){
			$strFilter .= " AND acc.email_add = '".$email_filter."'";
		}
		$qry = array();
		if (Schema::hasTable('gpg_emails_copy'))
		{
			$count = DB::select(DB::raw("SELECT COUNT(e.id) as t_count FROM gpg_emails_copy AS e,gpg_email_ids AS acc WHERE acc.id = e.gpg_account_id ".$strFilter."  AND status = '".$mailbox."'"));
					if (!empty($count) && isset($count[0]->t_count)) {
						$limit = $count[0]->t_count;
					}
					$qry = DB::select(DB::raw("SELECT  acc.email_add,
						  e.id AS email_id,
						  e.gpg_account_id,
						  e.gpg_attach_lead_num,
						  e.gpg_attach_quote_num,
						  e.gpg_attach_job_num,
						  e.message_id,
						  e.msg_no,
						  e.gpg_email_to_id,
						  e.gpg_email_to_id_all,
						  e.gpg_email_cc_id_all,
						  e.gpg_email_bcc_id_all,
						  e.gpg_email_from_id,
						  e.thread_references,
						  e.from_name,
						  e.to_name,
						  e.email_subject,
						  e.email_body,
						  e.status,
						  e.deleted,
						  e.unseen,
						  e.sent_date,
						  (SELECT
						     COUNT(*)
						   FROM gpg_email_attachments_copy 
						   WHERE gpg_email_id = e.id) AS count_attachment
								FROM gpg_emails_copy AS e,gpg_email_ids AS acc 
								WHERE acc.id = e.gpg_account_id ".$strFilter."  
									AND status = '".$mailbox."' 
								ORDER BY $order_by_field LIMIT  ".$start.",".$limit));
		}

		$data_arr = array();
		foreach ($qry as $key => $value){
			$data_arr[] = (array)$value;
		}
		$params = array('left_menu' => $modules,'data_arr'=>$data_arr,'mailbox'=>$mailbox);
		return View::make('emails/archive_emails', $params);
	}

	/*
	* eblockList
	*/
	public function eblockList(){
		$modules = Generic::modules();
		$start = 0; 
		$limit = 100;
		$DOB = '';
		$DSQL = " ORDER BY modified_on DESC";
		$strFilter = " 1" ;
		$btype_search = Input::get("btype_search");
		$block_value = Input::get("block_value");
		if ($btype_search!="") {
				$strFilter .= " AND block_type = '".$btype_search."'";
		}
		if ($block_value!="") {
				$strFilter .= " AND block_value like '%".$block_value."%'";
		}
		$count = DB::select(DB::raw("select count(id) as t_count from gpg_emails_blocklist WHERE ".$strFilter." $DSQL"));
		if (!empty($count) && isset($count[0]->t_count)){
			$limit = $count[0]->t_count;
		}
		$qry = DB::select(DB::raw("SELECT * FROM gpg_emails_blocklist WHERE".$strFilter.$DSQL." LIMIT  ".$start.",".$limit));
		$data_arr = array();
		foreach ($qry as $key => $value){
			$data_arr[] = (array)$value;
		}

		/*echo "<pre>";
		print_r($data_arr);
		die();*/
		$params = array('left_menu' => $modules,'data_arr'=>$data_arr);
		return View::make('emails/blocklist', $params);
	}

	/*
	* getEmailDetails
	*/
	public function getEmailDetails(){
		$id = Input::get('id');
		$qry = DB::table('gpg_emails')->select('*')->where('id',$id)->get();
		$qry_data = array();
		foreach ($qry as $key => $value) {
			$qry_data = array('from_user'=>$value->from_name,'to_user'=>$value->to_name,'to_date'=>date('Y-m-d',strtotime($value->sent_date)),'subject_email'=>$value->email_subject,'content_email'=>htmlentities($value->email_body));
		}
		return $qry_data;
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
