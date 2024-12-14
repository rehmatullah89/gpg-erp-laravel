<?php

class PurchaseOrderController extends \BaseController {

	protected $payTypeArray = array( "OnAccount" => "On Account", "Cash" => "Cash", "Check" => "Check", "OwnCC" => "Credit Card/Own", "CCCo" => "Credit Card/Co", "CCAmex" => "Credit Card:Amex", "CCBofA" => "Credit Card:BofA" );
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
		$gcodes = DB::select(DB::raw("select id, concat(ifnull(gl_code,''),' ',ifnull(description,'')) as name from gpg_gl_code where status = 'A' order by name"));
		$gcodeArr = array();
		foreach ($gcodes as $key => $value) {
			$gcodeArr[$value->id] = $value->name;
		}
		$vendors = DB::table('gpg_vendor')->where('status','=','A')->orderBy('name')->lists('name','id');
		$emps = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'gcodeArr'=>$gcodeArr,'payTypeArray'=>$this->payTypeArray,'vendors'=>$vendors,'emps'=>$emps,'query_data'=>$query_data);
		return View::make('purchaseorder.index', $params);
	}
	/*
	* indexRecd
	*/
	public function indexRecd(){
		$modules = Generic::modules();
		Input::flash();		
		$page = Input::get('page', 1);
   		$data = $this->getRecByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$gcodes = DB::select(DB::raw("select id, concat(ifnull(gl_code,''),' ',ifnull(description,'')) as name from gpg_gl_code where status = 'A' order by name"));
		$gcodeArr = array();
		foreach ($gcodes as $key => $value) {
			$gcodeArr[$value->id] = $value->name;
		}
		$vendors = DB::table('gpg_vendor')->where('status','=','A')->orderBy('name')->lists('name','id');
		$emps = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'gcodeArr'=>$gcodeArr,'payTypeArray'=>$this->payTypeArray,'vendors'=>$vendors,'emps'=>$emps,'query_data'=>$query_data);
		return View::make('purchaseorder.index_recd', $params);
	}

	public function getRecByPage($page = 1, $limit = null)
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
		set_time_limit(0);
		$SDate =  Input::get("SDate");
		$EDate =  Input::get("EDate");
		$estReceiptSDate =  Input::get("estReceiptSDate");
		$estReceiptEDate =  Input::get("estReceiptEDate");
		$recdSDate =  Input::get("recdSDate");
		$recdEDate =  Input::get("recdEDate");
		$optSort = Input::get("optSort");
		$optGLCode = Input::get("optGLCode");
		$optPayForm = Input::get("optPayForm");
		$optVendor = Input::get("optVendor");
		$optRequestBy = Input::get("optRequestBy");
		$optPOWriter = Input::get("optPOWriter");
		$SJobNumber = Input::get("SJobNumber");
		$EJobNumber = Input::get("EJobNumber");
		$SPONumber = Input::get("SPONumber");
		$EPONumber = Input::get("EPONumber");
		$optJobNum = Input::get("optJobNum");
		$optPORecd = Input::get("optPORecd");
		$optPODept = Input::get("optPODept");
		$priceFilter = Input::get('priceFilter');
		$priceFilterArray = array('0' => 'All', '1' => 'PO Amount to Date is greater', '2' => 'Quoted amount is greater');
		$optDisplay = "";
		if ($optDisplay=="") $queryPart = " AND ifnull(soft_delete,'') <> 1 ";
		else $queryPart = " AND ifnull(soft_delete,'') = 1 ";
		if ($estReceiptSDate!="" and $estReceiptEDate!="") $queryPart .= " AND po_est_recpt_date >= '".date('Y-m-d',strtotime($estReceiptSDate))."' AND po_est_recpt_date <= '".date('Y-m-d',strtotime($estReceiptEDate))."' ";
		elseif ($estReceiptSDate!="") $queryPart .= " AND po_est_recpt_date = '".date('Y-m-d',strtotime($estReceiptSDate))."'";
		if ($SDate!="" and $EDate!="") $queryPart .= " AND po_date >= '".date('Y-m-d',strtotime($SDate))."' AND po_date <= '".date('Y-m-d',strtotime($EDate))."' ";
		elseif ($SDate!="") $queryPart .= " AND po_date = '".date('Y-m-d',strtotime($SDate))."'";
		if ($recdSDate!="" and $recdEDate!="") $queryPart .= " AND po_recd_date >= '".date('Y-m-d',strtotime($recdSDate))."' AND po_recd_date <= '".date('Y-m-d',strtotime($recdEDate))."' ";
		elseif ($recdSDate!="") $queryPart .= " AND po_recd_date = '".date('Y-m-d',strtotime($recdSDate))."'";
		if ($SPONumber!="" and $EPONumber!="") $queryPart .= " AND id >= '".$SPONumber."' AND id <= '".$EPONumber."' ";
		elseif ($SPONumber!="") $queryPart .= " AND id = '".$SPONumber."'";
		if ($optGLCode!="") $queryPart .= " AND id in (select d.GPG_purchase_order_id from gpg_purchase_order_line_item d where d.GPG_gl_code_id = '$optGLCode')";
		if ($optPayForm!="") $queryPart .= " AND payment_form = '$optPayForm' ";
		if ($optVendor!="") $queryPart .= " AND GPG_vendor_id = '$optVendor' ";
		if ($optPODept==1) $queryPart .= " AND id in (select c.GPG_purchase_order_id from gpg_purchase_order_line_item c where c.job_num like 'GPG%')";
		if ($optPODept==2) $queryPart .= " AND id in (select c.GPG_purchase_order_id from gpg_purchase_order_line_item c where c.job_num not like 'GPG%')";
		if ($optPORecd==1) $queryPart .= " AND po_recd = 1 ";
		if ($optPORecd==2) $queryPart .= " AND ifnull(po_recd,0) = 0 ";
		if ($optJobNum!="") {
		    $queryPart .= " AND id in (select c.GPG_purchase_order_id from gpg_purchase_order_line_item c where c.job_num = '$optJobNum')";
		}
		if ($optRequestBy!="") $queryPart .= " AND request_by_id = '$optRequestBy' ";
		if ($optPOWriter!="") $queryPart .= " AND po_writer_id = '$optPOWriter' ";
		if( $priceFilter == '1' ){
		    $queryPart .= " AND po_amount_to_dat > po_quoted_amount ";
		} else if ( $priceFilter == '2' ) {
		    $queryPart .= " AND po_amount_to_dat < po_quoted_amount ";
		}
		if ($optSort=="") $optSort = "id";
		$queryPart .= " order by $optSort ";

		if ((isset($_REQUEST) && !empty($_REQUEST)) || $page>1){
			$getPO = "select *,
				(select concat(if(b.GPG_gl_code_id>0,(select concat(gl_code,' ',description) from gpg_gl_code where gpg_gl_code.id=b.GPG_gl_code_id),''),'~~',ifnull(b.job_num,'')) from gpg_purchase_order_line_item b where b.GPG_purchase_order_id = gpg_purchase_order.id order by b.id desc limit 0,1) as glCode_jobNum,
                (select name from gpg_vendor where id = GPG_vendor_id and status = 'A') as poVendor,
                (select name from gpg_employee where id = request_by_id and status = 'A') as poRequest,
                (select name from gpg_employee where id = po_writer_id and status = 'A') as poWriter,
                (SELECT invoiced_amount FROM gpg_purchase_order_invoiced_amount WHERE order_id = id) AS amount_to_date from gpg_purchase_order where 1 $queryPart";
            $getCount = "select count(id) as t_count,
				(select concat(if(b.GPG_gl_code_id>0,(select concat(gl_code,' ',description) from gpg_gl_code where gpg_gl_code.id=b.GPG_gl_code_id),''),'~~',ifnull(b.job_num,'')) from gpg_purchase_order_line_item b where b.GPG_purchase_order_id = gpg_purchase_order.id order by b.id desc limit 0,1) as glCode_jobNum,
                (select name from gpg_vendor where id = GPG_vendor_id and status = 'A') as poVendor,
                (select name from gpg_employee where id = request_by_id and status = 'A') as poRequest,
                (select name from gpg_employee where id = po_writer_id and status = 'A') as poWriter,
                (SELECT invoiced_amount FROM gpg_purchase_order_invoiced_amount WHERE order_id = id) AS amount_to_date from gpg_purchase_order where 1 $queryPart";
            set_time_limit(0);
            $query = DB::select(DB::raw($getPO));
            $query_data = array();
            foreach ($query as $key => $value) {
            	$query_data[] = (array)$value; 
            }
            $q2 = DB::select(DB::raw($getCount));
            if (!empty($q2) && isset($q2[0]->t_count)) {
            	$results->totalItems = $q2[0]->t_count;
            }
           $results->items = $query_data;
		}
		return $results;
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
		$SDate =  Input::get("SDate");
		$EDate =  Input::get("EDate");
		$estReceiptSDate =  Input::get("estReceiptSDate");
		$estReceiptEDate =  Input::get("estReceiptEDate");
		$optSort = Input::get("optSort");
		$optGLCode = Input::get("optGLCode");
		$optPayForm = Input::get("optPayForm");
		$optVendor = Input::get("optVendor");
		$optRequestBy = Input::get("optRequestBy");
		$optPOWriter = Input::get("optPOWriter");
		$SJobNumber = Input::get("SJobNumber");
		$EJobNumber = Input::get("EJobNumber");
		$SPONumber = Input::get("SPONumber");
		$EPONumber = Input::get("EPONumber");
		$optJobNum = Input::get("optJobNum");
		$optPORecd = Input::get("optPORecd");
		$VendorInvNum = Input::get("VendorInvNum");
		$poQutAmount = Input::get("poQutAmount");
		$poitemdesc = Input::get("poitemdesc");
		$optDisplay =""; 
		$priceFilter = Input::get('priceFilter');
		$priceFilterArray = array('0' => 'All', '1' => 'PO Amount to Date is greater', '2' => 'Quoted amount is greater');
		if ($VendorInvNum !=""){
	    	$queryPart = " gpg_purchase_order_recd_hist.gpg_purchase_order_id, gpg_purchase_order_recd_hist.vendor_invoice_number  from gpg_purchase_order LEFT JOIN gpg_purchase_order_recd_hist
						ON gpg_purchase_order.id=gpg_purchase_order_recd_hist.gpg_purchase_order_id where 1 AND gpg_purchase_order_recd_hist.vendor_invoice_number = '$VendorInvNum'";
	    if ($optDisplay=="") $queryPart .= " AND ifnull(soft_delete,'') <> 1 ";
	    else $queryPart .= " AND ifnull(soft_delete,'') = 1 ";
		} else {
		    if ($optDisplay=="") $queryPart = " AND ifnull(soft_delete,'') <> 1 ";
		    else $queryPart = " AND ifnull(soft_delete,'') = 1 ";
		}
		if ($estReceiptSDate!="" and $estReceiptEDate!="") $queryPart .= " AND po_est_recpt_date >= '".date('Y-m-d',strtotime($estReceiptSDate))."' AND po_est_recpt_date <= '".date('Y-m-d',strtotime($estReceiptEDate))."' ";
		elseif ($estReceiptSDate!="") $queryPart .= " AND po_est_recpt_date = '".date('Y-m-d',strtotime($estReceiptSDate))."'";
		if ($SDate!="" and $EDate!="") $queryPart .= " AND po_date >= '".date('Y-m-d',strtotime($SDate))."' AND po_date <= '".date('Y-m-d',strtotime($EDate))."' ";
		elseif ($SDate!="") $queryPart .= " AND po_date = '".date('Y-m-d',strtotime($SDate))."'";
		if ($SPONumber!="" and $EPONumber!="") $queryPart .= " AND id >= '".$SPONumber."' AND id <= '".$EPONumber."' ";
		elseif ($SPONumber!="") $queryPart .= " AND id = '".$SPONumber."'";
		if ($optGLCode!="") $queryPart .= " AND id in (select d.GPG_purchase_order_id from gpg_purchase_order_line_item d where d.GPG_gl_code_id = '$optGLCode')";
		if ($optPayForm!="") $queryPart .= " AND payment_form = '$optPayForm' ";
		if ($optVendor!="") $queryPart .= " AND GPG_vendor_id = '$optVendor' ";
		if ($optJobNum!="") {
		    $queryPart .= " AND id in (select c.GPG_purchase_order_id from gpg_purchase_order_line_item c where c.job_num = '$optJobNum')";
		}
		if ($optRequestBy!="") $queryPart .= " AND request_by_id = '$optRequestBy' ";
		if ($optPOWriter!="") $queryPart .= " AND po_writer_id = '$optPOWriter' ";
		if ($optPORecd==1) $queryPart .= " AND po_recd = 1 ";
		if ($optPORecd==2) $queryPart .= " AND ifnull(po_recd,0) = 0 ";
		if ($poQutAmount!="") $queryPart .= " AND po_quoted_amount like '$poQutAmount' ";
		if($poitemdesc!="") $queryPart .= " AND id IN (SELECT GPG_purchase_order_id FROM gpg_purchase_order_line_item WHERE LOWER(description) LIKE '%".strtolower($poitemdesc)."%') ";
		$whereCondition = '1';
		if($priceFilter == 1 ){
		    $whereCondition = " (SELECT SUM(ifnull(amount,0)) FROM gpg_purchase_order_recd_hist where gpg_purchase_order_id = gpg_purchase_order.id ) > po_quoted_amount ";
		} else if($priceFilter == 2) {
		    $whereCondition = " (SELECT SUM(ifnull(amount,0)) FROM gpg_purchase_order_recd_hist where gpg_purchase_order_id = gpg_purchase_order.id ) < po_quoted_amount ";
		}
		if ($optSort=="") $optSort = "gpg_purchase_order.id";
		$queryPart .= " order by $optSort ";
		if ((isset($_REQUEST) && !empty($_REQUEST)) || $page>1){
			$getPO = "";
			if ($VendorInvNum !=""){
			    $getPO = "SELECT
                    gpg_purchase_order.*,
                    (SELECT CONCAT(IF(b.GPG_gl_code_id>0,(SELECT concat(gl_code,' ',description) FROM gpg_gl_code WHERE gpg_gl_code.id=b.GPG_gl_code_id),''),'~~',ifnull(b.job_num,''))
                    FROM gpg_purchase_order_line_item b
                    WHERE b.GPG_purchase_order_id = gpg_purchase_order.id ORDER BY b.id desc limit 0,1) AS glCode_jobNum,
                    (SELECT name from gpg_vendor WHERE id = GPG_vendor_id AND status = 'A') AS poVendor,
                    (SELECT name from gpg_employee WHERE id = request_by_id) AS poRequest,
                    (SELECT invoiced_amount FROM gpg_purchase_order_invoiced_amount WHERE order_id = id) AS amount_to_date,
                    (SELECT name from gpg_employee WHERE id = po_writer_id) AS poWriter, $queryPart $limitOffset";

                $getCount = "SELECT
                    count(gpg_purchase_order.id) as t_count,
                    (SELECT CONCAT(IF(b.GPG_gl_code_id>0,(SELECT concat(gl_code,' ',description) FROM gpg_gl_code WHERE gpg_gl_code.id=b.GPG_gl_code_id),''),'~~',ifnull(b.job_num,''))
                    FROM gpg_purchase_order_line_item b
                    WHERE b.GPG_purchase_order_id = gpg_purchase_order.id ORDER BY b.id desc limit 0,1) AS glCode_jobNum,
                    (SELECT name from gpg_vendor WHERE id = GPG_vendor_id AND status = 'A') AS poVendor,
                    (SELECT name from gpg_employee WHERE id = request_by_id) AS poRequest,
                    (SELECT invoiced_amount FROM gpg_purchase_order_invoiced_amount WHERE order_id = id) AS amount_to_date,
                    (SELECT name from gpg_employee WHERE id = po_writer_id) AS poWriter, $queryPart";    

            }else{
            	$getPO = "SELECT *,
            	    (SELECT CONCAT(IF(b.GPG_gl_code_id>0,(SELECT CONCAT(gl_code,' ',description) FROM gpg_gl_code WHERE gpg_gl_code.id=b.GPG_gl_code_id),''),'~~',ifnull(b.job_num,'')) FROM gpg_purchase_order_line_item b
                        WHERE b.GPG_purchase_order_id = gpg_purchase_order.id order by b.id desc limit 0,1) AS glCode_jobNum,
                    (SELECT name FROM gpg_vendor WHERE id = GPG_vendor_id and status = 'A') AS poVendor,
                    (SELECT name from gpg_employee WHERE id = request_by_id) as poRequest,
                    (SELECT name from gpg_employee WHERE id = po_writer_id) as poWriter,
                    (SELECT invoiced_amount FROM gpg_purchase_order_invoiced_amount WHERE order_id = id) AS amount_to_date FROM gpg_purchase_order WHERE 1 $queryPart $limitOffset";

                $getCount = "SELECT count(id) as t_count,
            	    (SELECT CONCAT(IF(b.GPG_gl_code_id>0,(SELECT CONCAT(gl_code,' ',description) FROM gpg_gl_code WHERE gpg_gl_code.id=b.GPG_gl_code_id),''),'~~',ifnull(b.job_num,'')) FROM gpg_purchase_order_line_item b
                        WHERE b.GPG_purchase_order_id = gpg_purchase_order.id order by b.id desc limit 0,1) AS glCode_jobNum,
                    (SELECT name FROM gpg_vendor WHERE id = GPG_vendor_id and status = 'A') AS poVendor,
                    (SELECT name from gpg_employee WHERE id = request_by_id) as poRequest,
                    (SELECT name from gpg_employee WHERE id = po_writer_id) as poWriter,
                    (SELECT invoiced_amount FROM gpg_purchase_order_invoiced_amount WHERE order_id = id) AS amount_to_date FROM gpg_purchase_order WHERE 1 $queryPart";

            }
            set_time_limit(0);
            $query = DB::select(DB::raw($getPO));
            $query_data = array();
            foreach ($query as $key => $value) {
            	$query_data[] = (array)$value; 
            }
            $q2 = DB::select(DB::raw($getCount));
            if (!empty($q2) && isset($q2[0]->t_count)) {
            	$results->totalItems = $q2[0]->t_count;
            }
           $results->items = $query_data;
		}
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

	public function excelRPOExport(){
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
	   		$data = $this->getRecByPage($page);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$gcodes = DB::select(DB::raw("select id, concat(ifnull(gl_code,''),' ',ifnull(description,'')) as name from gpg_gl_code where status = 'A' order by name"));
			$gcodeArr = array();
			foreach ($gcodes as $key => $value) {
				$gcodeArr[$value->id] = $value->name;
			}
			$vendors = DB::table('gpg_vendor')->where('status','=','A')->orderBy('name')->lists('name','id');
			$emps = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
			$params = array('gcodeArr'=>$gcodeArr,'payTypeArray'=>$this->payTypeArray,'vendors'=>$vendors,'emps'=>$emps,'query_data'=>$query_data);   
		    $sheet->loadView('purchaseorder.excelRPOExport',$params);
		    });
		})->export('xls');
	}

	public function excelPOExport(){
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
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
	 		$gcodes = DB::select(DB::raw("select id, concat(ifnull(gl_code,''),' ',ifnull(description,'')) as name from gpg_gl_code where status = 'A' order by name"));
			$gcodeArr = array();
			foreach ($gcodes as $key => $value) {
				$gcodeArr[$value->id] = $value->name;
			}
			$vendors = DB::table('gpg_vendor')->where('status','=','A')->orderBy('name')->lists('name','id');
			$emps = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
			$params = array('gcodeArr'=>$gcodeArr,'payTypeArray'=>$this->payTypeArray,'vendors'=>$vendors,'emps'=>$emps,'query_data'=>$query_data);
		        $sheet->loadView('purchaseorder.excelPOExport',$params);
		    });
		})->export('xls');
	}
	/*
	* poItemForm
	*/
	public function poItemForm($id){
		if (isset($_POST) && !empty($_POST)){
		   $TotalLines = Input::get("poCounter");
		   $del='';
		   $totalAmount = 0;
		   $jobNumerFlag = '';
		   $glCodeFlag = '';
		   $poId=Input::get("po_id");
		   $poDate=Input::get("poDate");
		   $POpayForm=Input::get("POpayForm");
		   $POreqBy=Input::get("POreqBy");
		   $poEstRecptDate=Input::get("poEstRecptDate");
		   $POquoteAmt=Input::get("POquoteAmt");
		   $POsalesOrd=Input::get("POsalesOrd");
		   $POnote = Input::get("POnote");
		   $POwriter = Input::get("POwriter");
	       $venFieldQuery = array();
		   $UserId = 1;
		   for ($j=0; $j<=$TotalLines; $j++) {
				$totalAmount = $totalAmount + Input::get("POAmount_".$j);
				if(Input::get("POjobNum_".$j)!=''){
					$jobNumerFlag = Input::get("POjobNum_".$j);
				}
			    if(Input::get("POglCode_".$j)!=''){
					$glCodeFlag = Input::get("POglCode_".$j);
				}
			}
		   if(($jobNumerFlag != "" || $glCodeFlag != "")  or ($poId != '' && $poId != '0')){	
				$venFields = array("address"=>"venAddress","city"=>"venCity","state"=>"venState","zipcode"=>"venZip","phone_no"=>"venPhone");
			    while (list($key,$value)= each($venFields)) {
				   $venFieldQuery[$key] = Input::get($value);
				}
				$ven_update = DB::table('gpg_vendor')->where('id','=',Input::get('vendorId'))->update($venFieldQuery+array('modified_on'=>date('Y-m-d')));
			    if($poId==''){
				   $poId = DB::table('gpg_purchase_order')->max('id')+1;
				   $InsertQuery = DB::table('gpg_purchase_order')->insert(array('id'=>$poId,'po_date'=>$poDate,'po_est_recpt_date'=>$poEstRecptDate,'GPG_vendor_id'=>Input::get('vendorId'),'request_by_id'=>$POreqBy,'po_writer_id'=>$POwriter,'payment_form'=>$POpayForm,'po_quoted_amount'=>$POquoteAmt,'sales_order_number'=>$POsalesOrd,'po_note'=>$POnote,'created_on'=>date('Y-m-d'),'modified_by'=>$UserId,'created_by'=>$UserId));
				}else{
					$po_update = DB::table('gpg_purchase_order')->where('id','=',$poId)->update(array('po_est_recpt_date'=>$poEstRecptDate,'GPG_vendor_id'=>Input::get('vendorId'),'request_by_id'=>$POreqBy,'po_writer_id'=>$POwriter,'payment_form'=>$POpayForm,'po_quoted_amount'=>$POquoteAmt,'sales_order_number'=>$POsalesOrd,'po_note'=>$POnote,'modified_on'=>date('Y-m-d'),'modified_by'=>$UserId));
				}	
    			$formFields = array("GPG_job_id"=>"POjobNum","GPG_gl_code_id"=>"POglCode","job_num"=>"POjobNum","description"=>"PODesc","quantity"=>"POQty","rate"=>"PORate","amount"=>"POAmount","po_received"=>"PORcvd");
				for ($i=0; $i<=$TotalLines; $i++) {
	   	   			$field_values = array();
	   				if (((Input::get("POjobNum_".$i)!="" || Input::get("POglCode_".$i)!="") && (Input::get("POQty_".$i)!="" && (int)Input::get("POQty_".$i) > 0))) {
	      			while (list($key,$value)=each($formFields)) {
	        			if ($key=="GPG_job_id") {
							if (Input::get($value."_".$i)!="") { 
						  		$jobId = DB::table('gpg_job')->where('job_num','=',Input::get($value."_".$i))->pluck('id');
						  		$field_values[$key] = $jobId; 
						  	}else{
						    	$field_values[$key] = NULL; 
						  	}
						}else{
							$field_values[$key] = (Input::get($value."_".$i)!=""?"'".(preg_match("/date/i",$key)?date('Y-m-d',strtotime(Input::get($value."_".$i))):Input::get($value."_".$i)):"NULL");
						}
				    }
      				reset($formFields);
		 				if(Input::get("updatePOId_".$i)==""){
						   $chkval = DB::table('gpg_purchase_order_line_item')->max('id');
						   $chkval = ($chkval<1?1:$chkval+1);
						   $getSuccess = DB::table('gpg_purchase_order_line_item')->insert($field_values+array('id'=>$chkval,'GPG_purchase_order_id'=>$poId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
						}else{
						   $getSuccess = DB::table('gpg_purchase_order_line_item')->where('id','=',Input::get("updatePOId_".$i))->update($field_values+array('modified_on'=>date('Y-m-d')));
						}
				    }
			    }
	  		}else{
		 	    if($poId!='' && $poId!='0'){
				  	$venFields = array("address"=>"venAddress","city"=>"venCity","state"=>"venState","zipcode"=>"venZip","phone_no"=>"venPhone");
			       	while (list($key,$value)= each($venFields)){
				   		$venFieldQuery[$key] = Input::get($value);
				    }
			        $ven_update = DB::table('gpg_vendor')->where('id','=',Input::get('vendorId'))->update($venFieldQuery+array('modified_on'=>date('Y-m-d')));
			        $po_update = DB::table('gpg_purchase_order')->where('id','=',$poId)->update(array('po_date'=>$poDate,'GPG_vendor_id'=>Input::get('vendorId'),'request_by_id'=>$POreqBy,'po_writer_id'=>$POwriter,'payment_form'=>$POpayForm,'po_quoted_amount'=>$POquoteAmt,'sales_order_number'=>$POsalesOrd,'po_note'=>$POnote,'modified_on'=>date('Y-m-d'),'modified_by'=>$UserId));
			    }	
	 		}
	 		$t_sum =0;
	 		$ts = DB::select(DB::raw("select sum(ifnull(amount,0)) as t_sum from gpg_purchase_order_line_item where gpg_purchase_order_id = '".$poId."'"));
	 		if (!empty($ts) && isset($ts[0]->t_sum))
	 			$t_sum = $ts[0]->t_sum;
	 		DB::table('gpg_purchase_order')->where('id','=',$poId)->update(array('po_quoted_amount'=>$t_sum));
			return Redirect::to('purchaseorder/po_item_form/'.$poId)->withSuccess('Form Updated successfully');
		}//post data ends here

		$qry = DB::table('gpg_purchase_order')->select('*')->where('id','=',$id)->get();
		$poTblRow = array();
		foreach ($qry as $key => $value) {
			$poTblRow = (array)$value; 
		}
		$getPOItem = DB::select(DB::raw("select *, (select concat(gl_code,' ',description) from gpg_gl_code where id = GPG_gl_code_id and status = 'A') as glCode from gpg_purchase_order_line_item where GPG_purchase_order_id='$id'"));
		$getPORow = array();
		foreach ($getPOItem as $key => $value) {
			$getPORow[] = (array)$value;
		}
		$getPOHist = DB::select(DB::raw("select * from gpg_purchase_order_recd_hist where gpg_purchase_order_id='$id' order by id desc"));
		$getPOHistRow = array();
		foreach ($getPOHist as $key => $value) {
			$getPOHistRow[] = (array)$value;
		}
		
		$gcodes = DB::select(DB::raw("select id, concat(ifnull(gl_code,''),' ',ifnull(description,'')) as name from gpg_gl_code where status = 'A' order by name"));
		$gcodeArr = array();
		foreach ($gcodes as $key => $value) {
			$gcodeArr[$value->id] = $value->name;
		}
		$vendors = DB::table('gpg_vendor')->where('status','=','A')->orderBy('name')->lists('name','id');
		$emps = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
		$params = array('po_id'=>$id,'gcodeArr'=>$gcodeArr,'vendors'=>$vendors,'emps'=>$emps,'payTypeArray'=>$this->payTypeArray,'poTblRow'=>$poTblRow,'getPORows'=>$getPORow,'getPOHistRows'=>$getPOHistRow);
		return View::make('purchaseorder/po_item_form', $params);
	}
	
	/*
	* deletedPurchaseOrders
	*/
	public function deletedPurchaseOrders(){
		$modules = Generic::modules();
		$queryPart = " AND ifnull(soft_delete,'') = 1 ";
		$getPO = "select *,(select concat(if(b.GPG_gl_code_id>0,(select concat(gl_code,' ',description) from gpg_gl_code where gpg_gl_code.id=b.GPG_gl_code_id),''),'~~',ifnull(b.job_num,'')) 
			  	from gpg_purchase_order_line_item b where b.GPG_purchase_order_id = gpg_purchase_order.id order by b.id desc limit 0,1) as glCode_jobNum, (select name from gpg_vendor where id = GPG_vendor_id and status = 'A') as poVendor, (select name from gpg_employee where id = request_by_id and status = 'A') as poRequest, (select name from gpg_employee where id = po_writer_id and status = 'A') as poWriter from gpg_purchase_order where 1 $queryPart";
		$qry = DB::select(DB::raw($getPO));
		$getPORow = array();
		foreach ($qry as $key => $value) {
			$getPORow[] = (array)$value;
		}
		$vendors = DB::table('gpg_vendor')->where('status','=','A')->orderBy('name')->lists('name','id');
		$emps = DB::table('gpg_employee')->where('status','=','A')->orderBy('name')->lists('name','id');
		$params = array('left_menu' => $modules,'getPORows'=>$getPORow,'payTypeArray'=>$this->payTypeArray,'vendors'=>$vendors,'emps'=>$emps);
		return View::make('purchaseorder/delete_purchaseorder', $params);
	}

	/*
	* getPOPdffile
	*/
	public function getPOPdffile($poId){
		if ($poId != '0') {
			$poQry = DB::table('gpg_purchase_order')->select('*')->where('id','=',$poId)->get();
			$poRow = array();
			foreach ($poQry as $key => $value) {
				$poRow = (array)$value;
			}
			$venQry = DB::table('gpg_vendor')->select('*')->where('id','=',$poRow['GPG_vendor_id'])->get();
			$venRow = array();
			foreach ($venQry as $key => $value) {
				$venRow = (array)$value;
			}
			$pdf=new Fpdf();
			$pdf->AddPage();
			$cellHig = 8;
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"VENDOR NAME");
			$pdf->SetFont('Courier','',9);
			$str = $venRow['name'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$cellWid = 83;
			$pdf->Cell($cellWid,$cellHig,"");	
			$pdf->Image(storage_path('po_logo.jpg'),$pdf->GetX()-$cellWid,$pdf->GetY(),$cellWid,40);
			$cellWid = 30;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PO#");
			$pdf->SetFont('Courier','',9);
			$str = $poRow['id'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"DATE");
			$pdf->SetFont('Courier','',9);
			$str = ($poRow['po_date']?date('m/d/Y',strtotime($poRow['po_date'])):'');
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid = 60;
			$venAdd = $this->wrap_text ($venRow['address'],50);
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ADDRESS");
			$pdf->SetFont('Courier','',9);
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($venAdd[0]))/2),$pdf->GetY()+floor($cellHig/2)+2,$venAdd[0]);
			$pdf->SetFont('Arial','',7);
			$cellWid = 83;
			$pdf->Cell($cellWid,$cellHig,"");	
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"FORM OF PAYMENT");
			$pdf->SetFont('Courier','',9);
			$str = $this->payTypeArray[$poRow['payment_form']];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"");
			$pdf->SetFont('Courier','',9);
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth(isset($venAdd[1])?$venAdd[1]:1))/2),$pdf->GetY()+floor($cellHig/2)+2,isset($venAdd[1])?$venAdd[1]:1);
			$pdf->SetFont('Arial','',7);
			$cellWid = 83;
			$pdf->Cell($cellWid,$cellHig,"");	
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"REQUESTED BY");
			$pdf->SetFont('Courier','',9);
			$str = DB::table('gpg_employee')->where('id','=',$poRow['request_by_id'])->pluck('name');
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid=30;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"CITY");
			$pdf->SetFont('Courier','',9);
			$str = $venRow['city'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=10;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"STATE");
			$pdf->SetFont('Courier','',9);
			$str = $venRow['state'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=20;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ZIP CODE");
			$pdf->SetFont('Courier','',9);
			$str = $venRow['zipcode'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=83;
			$pdf->Cell($cellWid,$cellHig,"");
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PO WRITER");
			$pdf->SetFont('Courier','',9);
			$str = DB::table('gpg_employee')->where('id','=',$poRow['po_writer_id'])->pluck('name');
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PHONE");
			$pdf->SetFont('Courier','',9);
			$str = $venRow['phone_no'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=83;
			$pdf->Cell($cellWid,$cellHig,"");
			$cellWid = 30;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"QUOTED AMT");   	
	  	  	$pdf->SetFont('Courier','',9);
			$str = $poRow['po_quoted_amount'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,'$'.number_format($str,2));
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"SALES/ORDER#");
			$pdf->SetFont('Courier','',9);
			$str = $poRow['sales_order_number'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$poNoteLine = $this->wrap_text ($poRow['po_note'],160);
			$cellWid=203;
			$cellHig=8;
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PO NOTE");
			$pdf->SetFont('Courier','',9);
			$pdf->Text((102)-(($pdf->GetStringWidth($poNoteLine[0]))/2),$pdf->GetY()+6,$poNoteLine[0]);
			$pdf->Ln();
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text((102)-(($pdf->GetStringWidth(isset($poNoteLine[1])?$poNoteLine[1]:1))/2),$pdf->GetY()+6,isset($poNoteLine[1])?$poNoteLine[1]:1);
			$pdf->SetFont('Arial','',7);
			$cellHig=7;
			$pdf->Ln();
			$pdf->SetFillColor(102,102,102);
			$pdf->SetTextColor(255);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(.1);
			$pdf->SetFont('Arial','B',7);
			//Header
			$w=array(25,35,80,15,19,19,10);
			$header = array("Job#","GL Code","Description","Qty","Rate","Amount","Rcv'd");
			for($i=0;$i<count($header);$i++)
				$pdf->Cell($w[$i],$cellHig,$header[$i],1,0,'C',true);
			$pdf->Ln();
			//Color and font restoration
			$pdf->SetTextColor(0);
			//Data
			$pdf->SetFillColor(255,255,255);
			$fill=false;
			$pdf->SetFont('Courier','',9);
			$getPOItem = DB::select(DB::raw("select *, (select concat(gl_code,' ',description) from gpg_gl_code where id = GPG_gl_code_id and status = 'A') as glCode from gpg_purchase_order_line_item where GPG_purchase_order_id='$poId'"));
			foreach ($getPOItem as $key => $getPORow) {
				$glLine = $this->wrap_text ($getPORow->glCode,30);
				$decLine = $this->wrap_text ($getPORow->description,75);
				if (count($glLine)>count($decLine)) 
					$cellHig=(7*count($glLine)); 
				else $cellHig=(7*count($decLine)); 
					$pdf->Cell($w[0],$cellHig-1,$getPORow->job_num,'LRBT',0,'L');
				$pdf->Cell($w[1],$cellHig-1,'','LRBT',0,'L');
				for ($jj=0; $jj<count($glLine); $jj++) 
					$pdf->Text($pdf->GetX()+2-$w[1],$pdf->GetY()+3+(6*$jj+1),$glLine[$jj]);
					$pdf->Cell($w[2],$cellHig-1,'','LRBT',0,'L');
				for ($jj=0; $jj<count($decLine); $jj++) 
					$pdf->Text($pdf->GetX()+2-$w[2],$pdf->GetY()+3+(6*$jj+1),$decLine[$jj]);
					$pdf->Cell($w[3],$cellHig-1,$getPORow->quantity,'LRBT',0,'C',$fill);
					$pdf->Cell($w[4],$cellHig-1,'$'.number_format($getPORow->rate,2),'LRBT',0,'C',$fill);
					$pdf->Cell($w[5],$cellHig-1,'$'.number_format($getPORow->amount,2),'LRBT',0,'C',$fill);
					$pdf->Cell($w[6],$cellHig-1,($getPORow->po_received==1?'Yes':'No'),'LRBT',0,'C',$fill);
					$pdf->Ln();	
			}
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(203,5,"CUSTOMER COPY",0,0,'C');	
			$pdf->Output('PurchaseOrder.pdf','D');	
		}else
			return Redirect::to('purchaseorder/po_item_form/0');
	}
	
	/*
	* getPOPdfslip
	*/
	public function getPOPdfslip($poId){
		if ($poId != '0') {
			$poQry = DB::table('gpg_purchase_order')->select('*')->where('id','=',$poId)->get();
			$poRow = array();
			foreach ($poQry as $key => $value) {
				$poRow = (array)$value;
			}
			$venQry = DB::table('gpg_vendor')->select('*')->where('id','=',$poRow['GPG_vendor_id'])->get();
			$venRow = array();
			foreach ($venQry as $key => $value) {
				$venRow = (array)$value;
			}
			$pdf=new Fpdf();
			$pdf->SetFont('Arial','',10);
			$pdf->SetMargins(1, 5); 
			$pdf->AddPage();
			$cellHig = 8;
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"VENDOR NAME");
			$pdf->SetFont('Courier','',9);
			$str = $venRow['name'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$cellWid = 83;
			$pdf->Cell($cellWid,$cellHig,"");	
			$pdf->Image(storage_path('po_logo.jpg'),$pdf->GetX()-$cellWid,$pdf->GetY(),$cellWid,40);
			$cellWid = 30;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PO#");
			$pdf->SetFont('Courier','',9);
			$str = $poRow['id'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"DATE");
			$pdf->SetFont('Courier','',9);
			$str = ($poRow['po_date']?date('m/d/Y',strtotime($poRow['po_date'])):'');
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid = 60;
			$venAdd = $this->wrap_text ($venRow['address'],50);
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ADDRESS");
			$pdf->SetFont('Courier','',9);
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($venAdd[0]))/2),$pdf->GetY()+floor($cellHig/2)+2,$venAdd[0]);
			$pdf->SetFont('Arial','',7);
			$cellWid = 83;
			$pdf->Cell($cellWid,$cellHig,"");	
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"FORM OF PAYMENT");
			$pdf->SetFont('Courier','',9);
			$str = $this->payTypeArray[$poRow['payment_form']];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"");
			$pdf->SetFont('Courier','',9);
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth(isset($venAdd[1])?$venAdd[1]:1))/2),$pdf->GetY()+floor($cellHig/2)+2,isset($venAdd[1])?$venAdd[1]:1);
			$pdf->SetFont('Arial','',7);
			$cellWid = 83;
			$pdf->Cell($cellWid,$cellHig,"");	
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"REQUESTED BY");
			$pdf->SetFont('Courier','',9);
			$str = DB::table('gpg_employee')->where('id','=',$poRow['request_by_id'])->pluck('name');
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid=30;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"CITY");
			$pdf->SetFont('Courier','',9);
			$str = $venRow['city'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=10;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"STATE");
			$pdf->SetFont('Courier','',9);
			$str = $venRow['state'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=20;
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"ZIP CODE");
			$pdf->SetFont('Courier','',9);
			$str = $venRow['zipcode'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=83;
			$pdf->Cell($cellWid,$cellHig,"");
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PO WRITER");
			$pdf->SetFont('Courier','',9);
			$str = DB::table('gpg_employee')->where('id','=',$poRow['po_writer_id'])->pluck('name');
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$cellWid = 60;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PHONE");
			$pdf->SetFont('Courier','',9);
			$str = $venRow['phone_no'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$cellWid=83;
			$pdf->Cell($cellWid,$cellHig,"");
			$cellWid = 30;
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->SetFont('Arial','',7);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"QUOTED AMT");   	
	  	  	$pdf->SetFont('Courier','',9);
			$str = $poRow['po_quoted_amount'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,'$'.number_format($str,2));
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"SALES/ORDER#");
			$pdf->SetFont('Courier','',9);
			$str = $poRow['sales_order_number'];
			$pdf->Text($pdf->GetX()-($cellWid/2)-(($pdf->GetStringWidth($str))/2),$pdf->GetY()+floor($cellHig/2)+2,$str);
			$pdf->SetFont('Arial','',7);
			$pdf->Ln();
			$poNoteLine = $this->wrap_text ($poRow['po_note'],160);
			$cellWid=203;
			$cellHig=8;
			$pdf->SetFont('Arial','',7);
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text($pdf->GetX()-$cellWid+1,$pdf->GetY()+3,"PO NOTE");
			$pdf->SetFont('Courier','',9);
			$pdf->Text((102)-(($pdf->GetStringWidth($poNoteLine[0]))/2),$pdf->GetY()+6,$poNoteLine[0]);
			$pdf->Ln();
			$pdf->Cell($cellWid,$cellHig,"",1);	
			$pdf->Text((102)-(($pdf->GetStringWidth(isset($poNoteLine[1])?$poNoteLine[1]:1))/2),$pdf->GetY()+6,isset($poNoteLine[1])?$poNoteLine[1]:1);   
			$pdf->SetFont('Arial','',7);
			$cellHig=7;
			$pdf->Ln();
			$pdf->SetFillColor(102,102,102);
			$pdf->SetTextColor(255);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(.1);
			$pdf->SetFont('Arial','B',7);
			//Header
			$w=array(25,35,80,15,19,19,10);
			$header = array("Job#","GL Code","Description","Qty","Received","Used","Rcv'd");
			for($i=0;$i<count($header);$i++)
				$pdf->Cell($w[$i],$cellHig,$header[$i],1,0,'C',true);
			    $pdf->Ln();
			//Color and font restoration
			$pdf->SetTextColor(0);
			//Data
			$pdf->SetFillColor(255,255,255);
			$fill=false;
			$pdf->SetFont('Courier','',9);
			$fieldCounter=0;
			$getPOItem = DB::select(DB::raw("select *, (select concat(gl_code,' ',description) from gpg_gl_code where id = GPG_gl_code_id and status = 'A') as glCode from gpg_purchase_order_line_item where GPG_purchase_order_id='$poId'"));
			foreach ($getPOItem as $key => $getPORow) {
			    $glLine = $this->wrap_text ($getPORow->glCode,30);
				$decLine = $this->wrap_text ($getPORow->description,75);
				if (count($glLine)>count($decLine)) $cellHig=(7*count($glLine)); 
				else $cellHig=(7*count($decLine)); 
				$pdf->Cell($w[0],$cellHig-1,$getPORow->job_num,'LRBT',0,'L');
				$pdf->Cell($w[1],$cellHig-1,'','LRBT',0,'L');
				for ($jj=0; $jj<count($glLine); $jj++) 
					$pdf->Text($pdf->GetX()+2-$w[1],$pdf->GetY()+3+(6*$jj+1),$glLine[$jj]);
			  		$pdf->Cell($w[2],$cellHig-1,'','LRBT',0,'L');
				for ($jj=0; $jj<count($decLine); $jj++) 
					$pdf->Text($pdf->GetX()+2-$w[2],$pdf->GetY()+3+(6*$jj+1),$decLine[$jj]);
			  	$pdf->Cell($w[3],$cellHig-1,$getPORow->quantity,'LRBT',0,'C',$fill);
				$pdf->Cell($w[4],$cellHig-1,"",'LRBT',0,'C',$fill);
			  	$pdf->Cell($w[5],$cellHig-1,"",'LRBT',0,'C',$fill);
			  	$pdf->Cell($w[6],$cellHig-1,($getPORow->po_received==1?'Yes':'No'),'LRBT',0,'C',$fill);
			  	$pdf->Ln();
			}    
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(203,5,"CUSTOMER COPY",0,0,'C');	
 			$pdf->SetY(266);
			$pdf->SetFont('Times','I',10);
			$pdf->MultiCell(0,5,"    Name ____________________________                                                                                   Date _______________________________",0,'J');
			$pdf->MultiCell(0,5,"                                Print / Sign         ",0,'J');
			$pdf->Output('PurchaseOrderSlip.pdf','D');
		}else
			return Redirect::to('purchaseorder/po_item_form/0');
	}

	/*
	Local Method
	*/
	function wrap_text ($string,$cellWid) {
		$pdf=new Fpdf();
		$numLines = ceil($pdf->GetStringWidth($string)/$cellWid);
		if ($numLines>1) { 
		   $strLine = explode("##",wordwrap($string, floor(strlen(str_replace("\n","",$string))/$numLines), "##",1));
		}else{
		    $strLine[] = $string;
		}
		return $strLine; 	
	}

	/*
	* getCustomerInfo
	*/
	public function getVendorInfo(){
		$id = Input::get('id');
		$cinfo = DB::table('gpg_vendor')->select('*')->where('id','=',$id)->get();
		$customer_arr = array();
		foreach ($cinfo as $key1 => $value1) {
			foreach ($value1 as $key => $value) {
				$customer_arr[$key] = $value;
			}
		}
		return $customer_arr; 
	}

	/*
	* getQuoteFiles
	*/
	public function getPOFiles(){
		$id = Input::get('id');
		$colcount = 1;
		$conCatStr = '';
		$quote_files = DB::select(DB::raw("select * from gpg_purchase_order_attachment where gpg_purchase_order_id = '$id'"));
		if (!empty($quote_files)){
			foreach($quote_files as $key=>$row){
        	    $conCatStr .='<tr><td>'.$colcount++.'</td><td>'.$row->displayname.'</td><td><a class="btn btn-danger btn-xs" id="'.$row->id.'" name="del_quote_file">Delete</a><a class="btn btn-success btn-xs" id="'.$row->id.'" name="dld_quote_file">Download</a></td></tr>';
			}
    	}
    	return $conCatStr;
	}
	/*
	* manageQuoteFiles
	*/
	public function managePOFiles(){
		if (!empty($_POST['fjob_id'])){
			$job_id = $_POST['fjob_id'];
			$file = Input::file('attachment');
			$file_type_settings =  DB::table('gpg_settings')
			    ->select('*')
			    ->where('name', '=', '_ImgExt')
			    ->get();    
			$file_types = explode(',', $file_type_settings[0]->value);
			if (!empty($file)){
				if (in_array($file->getClientOriginalExtension(), $file_types)) {
					$ext1 = explode(".",$file->getClientOriginalName());
				 	$ext2 = end($ext1);
				 	$filename = "pofile_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = $file->move($destinationPath, $filename);
					//insert into db
					DB::table('gpg_purchase_order_attachment')->insert(array('gpg_purchase_order_id' =>$job_id,'filename'=>$filename ,'displayname'=>$file->getClientOriginalName()));		
				}
			}
		}
		return Redirect::to('purchaseorder/index');
	}
	/*
	* deleteQuoteFile
	*/
	public function deletePOFile(){
		$id = Input::get('id');
		DB::table('gpg_purchase_order_attachment')->where('id', '=',$id)->delete();
		return 1;
	}

	/*
	* updatepoInfo
	*/
	public function updatepoInfo(){
		$form_of_payment = Input::get('form_of_payment');
		$opt_vendor = Input::get('opt_vendor');
		$opt_requester = Input::get('opt_requester');
		$opt_writer = Input::get('opt_writer');
		$poid = Input::get('poid');
		if(!empty($poid)){
			DB::table('gpg_purchase_order')->where('id','=',$poid)->update(array('payment_form'=>$form_of_payment,'GPG_vendor_id'=>$opt_vendor,'request_by_id'=>$opt_requester,'po_writer_id'=>$opt_writer));
			return Redirect::to('purchaseorder/index')->withSuccess('Updated successfully');	
		}else
			return Redirect::to('purchaseorder/index')->withErrors(['msg', 'There is problem with updation!']);
	}
	/*
	* updatePOAmtDate
	*/
	public function updatePOAmtDate(){
		$id = Input::get('post_po_id');
		$amt_to_date = Input::get('amt_to_date');
		$ven_invoice_num = Input::get('ven_invoice_num');
	  	DB::table('gpg_purchase_order')->where('id','=',$id)->update(array('po_recd'=>'1','po_recd_date'=>date('Y-m-d'),'po_amount_to_dat'=>$amt_to_date));	
		DB::table('gpg_purchase_order_recd_hist')->insert(array('gpg_purchase_order_id'=>$id,'vendor_invoice_number'=>$ven_invoice_num,'date'=>date('Y-m-d'),'amount'=>$amt_to_date));	
		return Redirect::to('purchaseorder/index_recd')->withSuccess('Updated successfully');
	}
	
	/*
	* getPOAmt
	*/
	public function getPOAmt(){
		$id = Input::get('id');
		$amt = DB::table('gpg_purchase_order')->where('id','=',$id)->pluck('po_quoted_amount');
		$hist_amt = DB::table('gpg_purchase_order_recd_hist')->where('id','=',$id)->sum('amount');
		if (empty($amt))
			$amt = 0;
		if (empty($hist_amt))
			$hist_amt = 0;
		$arr = array('amt'=>number_format($amt,2),'hist_amt'=>number_format($hist_amt,2));
		return $arr;
	}

	/*
	* restorePO
	*/
	public function restorePO(){
		$id = Input::get('id');
		if (!empty($id)){
			DB::table('gpg_purchase_order')->where('id','=',$id)->update(array('soft_delete'=>NULL));
		}
		return 1;
	}

	/*
	* delPOAmtDate
	*/
	public function delPOAmtDate(){
		$id = Input::get('id');
		if ($id != '')
			$query = DB::table('gpg_purchase_order')->where('id','=',$id)->update(array('po_recd'=> NULL, 'po_recd_date'=>NULL ,'po_amount_to_dat'=>NULL));
		return 1;
	}

	/*
	* delPORow
	*/
	public function delPORow($id){
		if (!empty($id))
			DB::table('gpg_purchase_order_line_item')->where('id', '=',$id)->delete();
		return Redirect::to('purchaseorder/index')->withSuccess('Deleted successfully');
	}

	/*
	* delPOHistRow
	*/
	public function delPOHistRow($id){
		if (!empty($id))
			DB::table('gpg_purchase_order_recd_hist')->where('id', '=',$id)->delete();
		return Redirect::to('purchaseorder/index')->withSuccess('Deleted successfully');
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if (!empty($id)) {
			DB::table('gpg_purchase_order')->where('id','=',$id)->update(array('soft_delete'=>'1'));
			return Redirect::to('purchaseorder/index')->withSuccess('Deleted successfully');
		}else
		return Redirect::to('purchaseorder/index')->withErrors('There is problem with deletion!');
	}


}
