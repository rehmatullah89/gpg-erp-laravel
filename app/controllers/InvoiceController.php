<?php

class InvoiceController extends \BaseController {

	protected $rentalStatus = array("0"=>'-',"1" => "Quote", "2" => "Won", "3" => "In Process", "4" => "Complete", "5" => "Invoiced", "6" => "Lost" );
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

		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		$customers = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->get();
		$cust_arr = array(''=>'ALL');
		foreach ($customers as $key => $value)
				$cust_arr[$value->id] = $value->name;
		$params = array('left_menu' => $modules,'salesp_arr'=>$salesp_arr,'cust_arr'=>$cust_arr,'query_data'=>$query_data,'rentalStatus'=>$this->rentalStatus);
 		return View::make('invoice.index', $params);
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
	  $OutSDate =  Input::get("OutSDate");
	  $OutEDate =  Input::get("OutEDate");
	  $ReturnSDate =  Input::get("ReturnSDate");
	  $ReturnEDate =  Input::get("ReturnEDate");
	  $ApprovedSDate =  Input::get("ApprovedSDate");
	  $ApprovedEDate =  Input::get("ApprovedEDate");
	  $CreatedSDate =  Input::get("CreatedSDate");
	  $CreatedEDate =  Input::get("CreatedEDate");
	  $SQuoteInvoiceNumber =  Input::get("SQuoteInvoiceNumber");
	  $EQuoteInvoiceNumber =  Input::get("EQuoteInvoiceNumber");
	  $InvoiceSDate =  Input::get("InvoiceSDate");
	  $InvoiceEDate =  Input::get("InvoiceEDate");
	  $optJobStatus = Input::get("optJobStatus");
	  $optEmployee =  Input::get("optEmployee");
	  $optCustomer =  Input::get("optCustomer");
	  $optStatus =  Input::get("optStatus");
	  $optEqpStatus =  Input::get("optEqpStatus");
	  $rental_status =  Input::get("rental_status");
	  $queryPart =""; //new defined
	  if ($CreatedSDate!="" and $CreatedEDate!=""){ 
			$queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($CreatedSDate))." 00:00:00' AND created_on <= '".date('Y-m-d',strtotime($CreatedEDate))." 23:59:59' ";
	  }
	  elseif ($CreatedSDate!=""){
	 		$queryPart .= " AND date_format(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($CreatedSDate))."'";
	  }
	  if ($InvoiceSDate!="" and $InvoiceEDate!="") { 
	 		$queryPart .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND  gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."'  order by gpg_job_invoice_info.invoice_date desc limit 0,1)";  
	  }
	  elseif ($InvoiceSDate!=""){ 
	 		$queryPart .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND  gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
	  }
	  if ($optJobStatus=="completed")  $queryPart .= " AND rental_status = '4' ";
	  if ($optJobStatus=="notcompleted") $queryPart .= " AND ifnull(rental_status,0) not in ('4','5') ";
	  if ($optJobStatus=="comp_inv") $queryPart .= "  AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1) ";
	  if ($optJobStatus=="completed_not_invoiced") $queryPart .= "  AND if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id  limit 0,1)>0,0,1) ";
	  if ($OutSDate!="" and $OutEDate!="") $queryPart .= " AND schedule_date >= '".date('Y-m-d',strtotime($OutSDate))."' AND schedule_date <= '".date('Y-m-d',strtotime($OutEDate))."' ";
	  elseif ($OutSDate!="") $queryPart .= " AND schedule_date = '".date('Y-m-d',strtotime($OutSDate))."'";
 	  if ($ReturnSDate!="" and $ReturnEDate!="") $queryPart .= " AND date_return >= '".date('Y-m-d',strtotime($ReturnSDate))."' AND date_return <= '".date('Y-m-d',strtotime($ReturnEDate))."' ";
	  elseif ($ReturnSDate!="") $queryPart .= " AND date_return = '".date('Y-m-d',strtotime($ReturnSDate))."'";
	  if ($ApprovedSDate!="" and $ApprovedEDate!="") $queryPart .= " AND date_approved >= '".date('Y-m-d',strtotime($ApprovedSDate))."' AND date_approved <= '".date('Y-m-d',strtotime($ApprovedEDate))."' ";
	  elseif ($ApprovedSDate!="") $queryPart .= " AND date_approved = '".date('Y-m-d',strtotime($ApprovedSDate))."'";
	  if ($SQuoteInvoiceNumber!="" and $EQuoteInvoiceNumber!="") $queryPart .= " AND job_num >= '".$SQuoteInvoiceNumber."' AND job_num <= '".$EQuoteInvoiceNumber."' ";
	  elseif ($SQuoteInvoiceNumber!="") $queryPart .= " AND job_num = '".$SQuoteInvoiceNumber."'";
	  if ($optEmployee!="") $queryPart .= " AND approved_by = '$optEmployee' ";   
	  if ($optCustomer!="") $queryPart .= " AND gpg_customer_id = '$optCustomer' ";   
	  if ($optStatus!="") $queryPart .= " AND form_type = '$optStatus' ";
	  if ($rental_status!="") $queryPart .= " AND rental_status = '$rental_status' ";      
	  $queryPart .= "order by job_num desc";
	  $t_rec = DB::select(DB::raw("select count(id) as t_count from gpg_job WHERE job_num like 'RNT%' $queryPart"));
	  if (!empty($t_rec) && isset($t_rec[0]->t_count)){
	   		$results->totalItems = $t_rec[0]->t_count;
	   }
	   $getSales = DB::select(DB::raw("select *, if(curdate()>=ADDDATE(schedule_date,28) and rental_status = 3,1,0) as flag_duplicate,(select name from gpg_customer where id = gpg_customer_id) as cusName, (select gpg_sales_tracking_id from gpg_sales_tracking_rental  where gpg_job_id = gpg_job.id ) as lead_id, (select name from gpg_employee where id = GPG_employee_id) as GPG_employee_id, (select sum(ifnull(gpg_job_equipment.qty_out,0)-ifnull(gpg_job_equipment.qty_in,0)) from gpg_job_equipment,gpg_equipment where gpg_job_equipment.eqp_num = gpg_equipment.eqp_num and gpg_job_equipment.gpg_job_id = gpg_job.id  and gpg_equipment.status = 'A' and gpg_equipment.gpg_equipment_type_id = '1') as eqp_count,(select concat(invoice_number,'#~#',invoice_amount,'#~#',invoice_date) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id limit 0,1) as invoice_data from gpg_job WHERE  job_num like 'RNT%' $queryPart $limitOffset")); 
	   $sale_arr = array();
	   foreach ($getSales as $key => $value) {
	   		$sale_arr[] = (array)$value;
	   }
	   $results->items = $sale_arr;
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

	/*
	*excelInvExport
	*/
	public function excelInvExport(){
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
	  		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
			$salesp_arr = array(''=>'ALL');
			foreach ($salesPerson as $key => $value)
					$salesp_arr[$value->id] = $value->name;
			$customers = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->get();
			$cust_arr = array(''=>'ALL');
			foreach ($customers as $key => $value)
					$cust_arr[$value->id] = $value->name;
			$params = array('salesp_arr'=>$salesp_arr,'cust_arr'=>$cust_arr,'query_data'=>$query_data,'rentalStatus'=>$this->rentalStatus);
			$sheet->loadView('invoice.excelInvExport',$params);
		  });
		})->export('xls');
	}
	public	function drawText($text,$start,$end,$top){
		  $pdf=new Fpdf();
		  $pdf->SetFont('Courier','',11);
		  
		  $width = $pdf->GetStringWidth($text);
		  $len = $end - $start;
		  $X = ($len/2)-($width/2)+$start;
		  $pdf->Text($X,$top-2,$text);  
		  $pdf->SetFont('Helvetica','',11);  
		}
		
	public	function addPage(){
		$pdf=new Fpdf();
		$curY = 0;
		  if ($curY>200) { 
		    $pdf->AddPage();
			$curY = 11;
			$pdf->Line(148,10,148,203);
		  }
	}
		
	public function splitComment($comData) {
		$pdf=new Fpdf();
		  
		  if ($pdf->GetStringWidth($comData)>=32) { 
				$com[0] = substr($comData,0,32-2)."-"; 
			   	$com[1] = $comData1 = substr($comData,32-2); 
				if ($pdf->GetStringWidth($comData1)>=54) { 
				  	$com[1] = substr($comData1,0,54-2)."-"; 
			   	  	$com[2] = substr($comData1,54-2); 
				}
			}
		else $com[0] = $comData ;
		return $com;
	}
	/*
	* getInvPdffile
	*/
	public function getInvPdffile($id,$cid){
		$quoteInvoiceId = $id;
		$customerId = $cid;

		$invQry = DB::table('gpg_job')->where('id','=',$id)->select('*')->get();
		$invRow = array();
		foreach ($invQry as $key => $value) {
			$invRow = (array)$value;
		}
		$cusQry = DB::table('gpg_customer')->where('id','=',$customerId)->select('*')->get();
		$cusRow = array();
		foreach ($cusQry as $key => $value) {
			$cusRow = (array)$value;
		}
		$getFields = DB::select(DB::raw("show fields from gpg_job_checkout"));
		$eqpFields[] = "eqp_type";
		foreach ($getFields as $key => $chkFields) {
			$eqpFields[] = $chkFields->Field;
		}

		$getRow = DB::select(DB::raw("select *, (select name from gpg_equipment a ,gpg_equipment_type b where a.gpg_equipment_type_id = b.id and a.eqp_num = gpg_equipment_num) as eqp_type, (select name from gpg_employee where id = check_by) as check_name from gpg_job_checkout where gpg_job_id = '$quoteInvoiceId'"));
		$chkData = array();
		 $chkOutBy = '';
		 $chkOutDate = '';
		 $chkInBy = '';
		 $chkInDate = '';
		foreach ($getRow as $key => $chkRow) {
			for($i1=0; $i1<count($eqpFields);$i1++) {
			    if ($chkRow->type=='checkout') {
				   $chkData['checkout'][$chkRow->gpg_equipment_num][$eqpFields[$i1]] = $chkRow->$eqpFields[$i1];
				   $chkOutDate = ($chkRow->check_date!=""?date('m/d/Y',strtotime($chkRow->check_date)):"");
				   $chkOutBy = $chkRow->check_name;
				} else {
				   $chkData['checkin'][$chkRow->gpg_equipment_num][$eqpFields[$i1]] = $chkRow->$eqpFields[$i1];
				   $chkInDate = ($chkRow->check_date!=""?date('m/d/Y',strtotime($chkRow->check_date)):"");
				   $chkInBy = $chkRow->check_name;
				}   
		   }
		}
		$pdf=new Fpdf('L');
		$pdf->SetFont('Arial','',11);
		$pdf->SetMargins(10,10);
		$pdf->AddPage();
		$curX = 20; 
		$curY = 11;
		$diff = 9;
		$pdf->SetFont('Helvetica','',11);
		$pdf->Text($curX,$curY,"Customer Name:");
		$pdf->Line($curX+33,$curY,$curX+135,$curY);
		$this->drawText($cusRow['name'],$curX+33,$curX+135,$curY);
		$pdf->Text($curX+140,$curY,"Date of Contract:");
		$pdf->Line($curX+173,$curY,$curX+260,$curY);
		if ($invRow['schedule_date']!="") $this->drawText(date('m/d/Y',strtotime($invRow['schedule_date'])),$curX+173,$curX+260,$curY);
			$this->addPage();
			$curY = $curY+$diff; 
			$pdf->Text($curX,$curY,"Job Name:");
			$pdf->Line($curX+33,$curY,$curX+135,$curY);
			$pdf->Text($curX+140,$curY,"Date of Return:");
			$pdf->Line($curX+173,$curY,$curX+260,$curY);
		if ($invRow['date_return']!="") $this->drawText(date('m/d/Y',strtotime($invRow['date_return'])),$curX+173,$curX+260,$curY);
			$this->addPage(); 
			$curY = $curY + $diff; 
			$pdf->Text($curX,$curY,"Job Location:");
			$pdf->Line($curX+33,$curY,$curX+135,$curY);
			$this->drawText($invRow['location'],$curX+33,$curX+135,$curY);
			$pdf->Text($curX+140,$curY,"Job#:");
			$pdf->Line($curX+173,$curY,$curX+260,$curY);
			$this->drawText($invRow['job_num'],$curX+173,$curX+260,$curY);
			$pdf->Line($curX+128,$curY+10,$curX+128,$curY+173);		
			$this->addPage();
			$curY = $curY + $diff; 
			$pdf->SetFont('Helvetica','BU',11);
			$pdf->Text($curX+45,$curY,"CHECK-OUT");
			$pdf->Text($curX+195,$curY,"CHECK-IN");
			$pdf->SetFont('Helvetica','',11);
			$curY = $curY + 2; 
			$curX = $curX-5;
			$otherEqp = array();
			$secPan = 141;
		if (isset($chkData['checkout']) && is_array($chkData['checkout'])) {
		while (list($key,$value) = each($chkData['checkout'])) {
		      if ($chkData['checkout'][$key]['eqp_type']=="GENERATOR") {
				$this->addPage();
				$curY = $curY + $diff;
				$pdf->Text($curX,$curY,"Equipment#:");
				$pdf->Line($curX+25,$curY,$curX+125,$curY);
				$this->drawText($chkData['checkout'][$key]['gpg_equipment_num'],$curX+25,$curX+125,$curY);
				
				$pdf->Text($curX+141,$curY,"Equipment#:");
				$pdf->Line($curX+165,$curY,$curX+270,$curY);
				$this->drawText($chkData['checkin'][$key]['gpg_equipment_num'],$curX+165,$curX+270,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Hours on Equip.:");
				$pdf->Line($curX+32,$curY,$curX+125,$curY);
				$this->drawText($chkData['checkout'][$key]['hours_on_equipment'],$curX+32,$curX+125,$curY);
				
				$pdf->Text($curX+141,$curY,"Hours on Equip.:");
				$pdf->Line($curX+173,$curY,$curX+270,$curY);
				$this->drawText($chkData['checkin'][$key]['hours_on_equipment'],$curX+173,$curX+270,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Last Service Hrs.:");
				$pdf->Line($curX+34,$curY,$curX+125,$curY);
				$this->drawText($chkData['checkout'][$key]['last_service_hours'],$curX+34,$curX+125,$curY);
				
				$pdf->Text($curX+141,$curY,"Last Service Hrs.:");
				$pdf->Line($curX+175,$curY,$curX+270,$curY);
				$this->drawText($chkData['checkin'][$key]['last_service_hours'],$curX+175,$curX+270,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Voltage:");
				$pdf->Line($curX+18,$curY,$curX+125,$curY);
				$this->drawText($chkData['checkout'][$key]['voltage'],$curX+18,$curX+125,$curY);
						
				$pdf->Text($curX+141,$curY,"Voltage:");
				$pdf->Line($curX+159,$curY,$curX+270,$curY);
				$this->drawText($chkData['checkin'][$key]['voltage'],$curX+159,$curX+270,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Fuel @ 90%:");
				$pdf->Text($curX+47 ,$curY,"Yes:");
				$pdf->Line($curX+58,$curY,$curX+67,$curY);
				if ($chkData['checkout'][$key]['fuel']=="Yes")
				$this->drawText("X",$curX+58,$curX+67,$curY);
				
				$pdf->Text($curX+83,$curY,"No:");
				$pdf->Line($curX+92,$curY,$curX+101,$curY);
				if ($chkData['checkout'][$key]['fuel']=="No")
				$this->drawText("X",$curX+92,$curX+101,$curY);
				
				$pdf->Text($curX+$secPan,$curY,"Fuel Used:");
				$pdf->Line($curX+22+$secPan,$curY,$curX+82+$secPan,$curY);
				$pdf->Text($curX+83+$secPan,$curY,"gallons");
				$this->drawText($chkData['checkin'][$key]['fuel'],$curX+22+$secPan,$curX+82+$secPan,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Fluid Levels:");
				$pdf->Line($curX+25,$curY,$curX+60,$curY);
				$this->drawText($chkData['checkout'][$key]['fluid_level'],$curX+25,$curX+60,$curY);
				$pdf->Text($curX+68 ,$curY,"Tires:");
				$pdf->Line($curX+81,$curY,$curX+125,$curY);
				$this->drawText($chkData['checkout'][$key]['tires'],$curX+81,$curX+125,$curY);
				
				$pdf->Text($curX+$secPan,$curY,"Fluid Levels:");
				$pdf->Line($curX+25+$secPan,$curY,$curX+60+$secPan,$curY);
				$this->drawText($chkData['checkin'][$key]['fluid_level'],$curX+25+$secPan,$curX+60+$secPan,$curY);
				$pdf->Text($curX+68+$secPan,$curY,"Tires:");
				$pdf->Line($curX+81+$secPan,$curY,$curX+129+$secPan,$curY);
				$this->drawText($chkData['checkin'][$key]['tires'],$curX+81+$secPan,$curX+129+$secPan,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Chains:");
				$pdf->Line($curX+20,$curY,$curX+60,$curY);
				$this->drawText($chkData['checkout'][$key]['chains'],$curX+20,$curX+60,$curY);
				$pdf->Text($curX+68 ,$curY,"Grounding Rod:");
				$pdf->Line($curX+98,$curY,$curX+125,$curY);
				$this->drawText($chkData['checkout'][$key]['g_rods'],$curX+98,$curX+125,$curY);
				
				$pdf->Text($curX+$secPan,$curY,"Chains:");
				$pdf->Line($curX+20+$secPan,$curY,$curX+60+$secPan,$curY);
				$this->drawText($chkData['checkin'][$key]['chains'],$curX+20+$secPan,$curX+60+$secPan,$curY);
				$pdf->Text($curX+68+$secPan,$curY,"Grounding Rod:");
				$pdf->Line($curX+98+$secPan,$curY,$curX+129+$secPan,$curY);
				$this->drawText($chkData['checkin'][$key]['g_rods'],$curX+98+$secPan,$curX+129+$secPan,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Tire Presure:");
				$pdf->Line($curX+26,$curY,$curX+60,$curY);
				$this->drawText($chkData['checkout'][$key]['t_presure'],$curX+26,$curX+60,$curY);
				$pdf->Text($curX+68 ,$curY,"Trailer Lights:");
				$pdf->Line($curX+95,$curY,$curX+125,$curY);
				$this->drawText($chkData['checkout'][$key]['t_light'],$curX+95,$curX+125,$curY);
				
				$pdf->Text($curX+$secPan,$curY,"Tire Presure:");
				$pdf->Line($curX+26+$secPan,$curY,$curX+60+$secPan,$curY);
				$this->drawText($chkData['checkin'][$key]['t_presure'],$curX+26+$secPan,$curX+60+$secPan,$curY);
				$pdf->Text($curX+68+$secPan,$curY,"Trailer Lights:");
				$pdf->Line($curX+95+$secPan,$curY,$curX+125+$secPan,$curY);
				$this->drawText($chkData['checkin'][$key]['t_light'],$curX+95+$secPan,$curX+125+$secPan,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Hitch Type:");
				$pdf->Line($curX+30,$curY,$curX+47,$curY);
				$this->drawText($chkData['checkout'][$key]['hatch_pintle'],$curX+30,$curX+47,$curY);
				$pdf->Text($curX+48 ,$curY,"Pintle");
				$pdf->Line($curX+63,$curY,$curX+79,$curY);
				$this->drawText($chkData['checkout'][$key]['hatch_ball'],$curX+63,$curX+79,$curY);
				$pdf->Text($curX+80,$curY,"2\"Ball");
				$pdf->Line($curX+97,$curY,$curX+114,$curY);
				$this->drawText($chkData['checkout'][$key]['hatch_other'],$curX+97,$curX+114,$curY);
				$pdf->Text($curX+115,$curY,"Other");
				
				$pdf->Text($curX+$secPan,$curY,"Hitch Type:");
				$pdf->Line($curX+30+$secPan,$curY,$curX+47+$secPan,$curY);
				$this->drawText($chkData['checkin'][$key]['hatch_pintle'],$curX+30+$secPan,$curX+47+$secPan,$curY);
				$pdf->Text($curX+48+$secPan,$curY,"Pintle");
				$pdf->Line($curX+63+$secPan,$curY,$curX+79+$secPan,$curY);
				$this->drawText($chkData['checkin'][$key]['hatch_ball'],$curX+63+$secPan,$curX+79+$secPan,$curY);
				$pdf->Text($curX+80+$secPan,$curY,"2\"Ball");
				$pdf->Line($curX+97+$secPan,$curY,$curX+114+$secPan,$curY);
				$this->drawText($chkData['checkin'][$key]['hatch_other'],$curX+97+$secPan,$curX+114+$secPan,$curY);
				$pdf->Text($curX+115+$secPan,$curY,"Other");
				
				$this->addPage();
				$comText = splitComment($chkData['checkout'][$key]['comments']);
				$comTextIn = splitComment($chkData['checkin'][$key]['comments']);
				
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Comments on Equipment:");
				$pdf->Line($curX+48,$curY,$curX+125,$curY);
				$this->drawText($comText[0],$curX+48,$curX+125,$curY);
				
				$pdf->Text($curX+$secPan,$curY,"Comments on Equipment:");
				$pdf->Line($curX+48+$secPan,$curY,$curX+129+$secPan,$curY);
				$this->drawText($comTextIn[0],$curX+48+$secPan,$curX+129+$secPan,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Line($curX,$curY,$curX+125,$curY);
				$this->drawText($comText[1],$curX,$curX+125,$curY);
				$pdf->Line($curX+$secPan,$curY,$curX+129+$secPan,$curY);
				$this->drawText($comTextIn[1],$curX+$secPan,$curX+129+$secPan,$curY);
								
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Line($curX,$curY,$curX+125,$curY);
				$this->drawText($comText[2],$curX,$curX+125,$curY);
				$pdf->Line($curX+$secPan,$curY,$curX+129+$secPan,$curY);
				$this->drawText($comTextIn[2],$curX+$secPan,$curX+129+$secPan,$curY);
				$curY = $curY + $diff; 
				
		     } else {
			    $otherEqp['checkout'][$chkData['checkout'][$key]['eqp_type']] = $chkData['checkout'][$key]; 
				$otherEqp['checkin'][$chkData['checkin'][$key]['eqp_type']] = $chkData['checkin'][$key]; 
			 }
		}
		} else {
		      $this->addPage();
				$curY = $curY + $diff;
				$pdf->Text($curX,$curY,"Equipment#:");  	$pdf->Line($curX+25,$curY,$curX+125,$curY);
				$pdf->Text($curX+142,$curY,"Equipment#:");	$pdf->Line($curX+165,$curY,$curX+270,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Hours on Equip.:");      $pdf->Line($curX+32,$curY,$curX+125,$curY);
				$pdf->Text($curX+141,$curY,"Hours on Equip.:");  $pdf->Line($curX+173,$curY,$curX+270,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Last Service Hrs.:");       $pdf->Line($curX+34,$curY,$curX+125,$curY);
				$pdf->Text($curX+141,$curY,"Last Service Hrs.:");	$pdf->Line($curX+175,$curY,$curX+270,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Voltage:");			$pdf->Line($curX+18,$curY,$curX+125,$curY);
				$pdf->Text($curX+141,$curY,"Voltage:");		$pdf->Line($curX+159,$curY,$curX+270,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Fuel @ 90%:"); 		$pdf->Text($curX+47 ,$curY,"Yes:");
				$pdf->Line($curX+58,$curY,$curX+67,$curY);	$pdf->Text($curX+83,$curY,"No:");
				$pdf->Line($curX+92,$curY,$curX+101,$curY);
				
				$pdf->Text($curX+$secPan,$curY,"Fuel Used:");	$pdf->Line($curX+22+$secPan,$curY,$curX+82+$secPan,$curY);
				$pdf->Text($curX+83+$secPan,$curY,"gallons");
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Fluid Levels:");		$pdf->Line($curX+25,$curY,$curX+60,$curY);
				$pdf->Text($curX+68 ,$curY,"Tires:");			$pdf->Line($curX+81,$curY,$curX+125,$curY);
				
				$pdf->Text($curX+$secPan,$curY,"Fluid Levels:");	$pdf->Line($curX+25+$secPan,$curY,$curX+60+$secPan,$curY);
				$pdf->Text($curX+68+$secPan,$curY,"Tires:");		$pdf->Line($curX+81+$secPan,$curY,$curX+129+$secPan,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Chains:");				$pdf->Line($curX+20,$curY,$curX+60,$curY);
				$pdf->Text($curX+68 ,$curY,"Grounding Rod:");	$pdf->Line($curX+98,$curY,$curX+125,$curY);
				
				$pdf->Text($curX+$secPan,$curY,"Chains:");				$pdf->Line($curX+20+$secPan,$curY,$curX+60+$secPan,$curY);
				$pdf->Text($curX+68+$secPan,$curY,"Grounding Rod:");	$pdf->Line($curX+98+$secPan,$curY,$curX+129+$secPan,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Tire Presure:");		$pdf->Line($curX+26,$curY,$curX+60,$curY);
				$pdf->Text($curX+68 ,$curY,"Trailer Lights:");	$pdf->Line($curX+95,$curY,$curX+125,$curY);
				
				$pdf->Text($curX+$secPan,$curY,"Tire Presure:");		$pdf->Line($curX+26+$secPan,$curY,$curX+60+$secPan,$curY);
				$pdf->Text($curX+68+$secPan,$curY,"Trailer Lights:");	$pdf->Line($curX+95+$secPan,$curY,$curX+125+$secPan,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Hitch Type:");		$pdf->Line($curX+30,$curY,$curX+47,$curY);
				$pdf->Text($curX+48 ,$curY,"Pintle");		$pdf->Line($curX+63,$curY,$curX+79,$curY);
				$pdf->Text($curX+80,$curY,"2\"Ball");		$pdf->Line($curX+97,$curY,$curX+114,$curY);
				$pdf->Text($curX+115,$curY,"Other");
				
				$pdf->Text($curX+$secPan,$curY,"Hitch Type:");	$pdf->Line($curX+30+$secPan,$curY,$curX+47+$secPan,$curY);
				$pdf->Text($curX+48+$secPan,$curY,"Pintle");	$pdf->Line($curX+63+$secPan,$curY,$curX+79+$secPan,$curY);
				$pdf->Text($curX+80+$secPan,$curY,"2\"Ball");	$pdf->Line($curX+97+$secPan,$curY,$curX+114+$secPan,$curY);
				$pdf->Text($curX+115+$secPan,$curY,"Other");
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Text($curX,$curY,"Comments on Equipment:");	$pdf->Line($curX+48,$curY,$curX+125,$curY);
				
				$pdf->Text($curX+$secPan,$curY,"Comments on Equipment:");	$pdf->Line($curX+48+$secPan,$curY,$curX+129+$secPan,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Line($curX,$curY,$curX+125,$curY);
				$pdf->Line($curX+$secPan,$curY,$curX+129+$secPan,$curY);
				
				$this->addPage();
				$curY = $curY + $diff; 
				$pdf->Line($curX,$curY,$curX+125,$curY);
				$pdf->Line($curX+$secPan,$curY,$curX+129+$secPan,$curY);
				
		}
		$this->addPage();
		$curY = $curY + $diff; 
		$pdf->Text($curX,$curY,"Cable Quantity:");
		$pdf->Line($curX+30,$curY,$curX+53,$curY);
		if (isset($otherEqp['checkout']['CABLE']['cable_qty_run']))
		$this->drawText($otherEqp['checkout']['CABLE']['cable_qty_run'],$curX+30,$curX+53,$curY);
		$pdf->Text($curX+57 ,$curY,"Runs X");
		$pdf->Line($curX+73,$curY,$curX+92,$curY);
		if (isset($otherEqp['checkout']['CABLE']['cable_qty_feet']))
		$this->drawText($otherEqp['checkout']['CABLE']['cable_qty_feet'],$curX+73,$curX+92,$curY);
		$pdf->Text($curX+96,$curY,"Feet of");
		$pdf->Line($curX+110,$curY,$curX+125,$curY);
		if (isset($otherEqp['checkout']['CABLE']['cable_qty_run']) && isset($otherEqp['checkout']['CABLE']['cable_qty_feet']))
		$this->drawText(($otherEqp['checkout']['CABLE']['cable_qty_run']*$otherEqp['checkout']['CABLE']['cable_qty_feet']),$curX+110,$curX+125,$curY);
		
		
		$pdf->Text($curX+$secPan,$curY,"Cable Quantity:");
		$pdf->Line($curX+30+$secPan,$curY,$curX+53+$secPan,$curY);
		if (isset($otherEqp['checkin']['CABLE']['cable_qty_run']))
		$this->drawText($otherEqp['checkin']['CABLE']['cable_qty_run'],$curX+30+$secPan,$curX+53+$secPan,$curY);
		$pdf->Text($curX+57+$secPan,$curY,"Runs X");
		$pdf->Line($curX+73+$secPan,$curY,$curX+92+$secPan,$curY);
		if (isset($otherEqp['checkin']['CABLE']['cable_qty_feet']))
		$this->drawText($otherEqp['checkin']['CABLE']['cable_qty_feet'],$curX+73+$secPan,$curX+92+$secPan,$curY);
		$pdf->Text($curX+96+$secPan,$curY,"Feet of");
		$pdf->Line($curX+110+$secPan,$curY,$curX+129+$secPan,$curY);
		if (isset($otherEqp['checkin']['CABLE']['cable_qty_run']) && isset($otherEqp['checkin']['CABLE']['cable_qty_feet']))
		$this->drawText(($otherEqp['checkin']['CABLE']['cable_qty_run']*$otherEqp['checkin']['CABLE']['cable_qty_feet']),$curX+110+$secPan,$curX+129+$secPan,$curY);
		
		$this->addPage();
		$curY = $curY + $diff; 
		$pdf->Text($curX,$curY,"Pigtails:");
		$pdf->Line($curX+30,$curY,$curX+47,$curY);
		
		$pigTailQty1=0;
		if (isset($otherEqp['checkout']['PIGTAIL']['pigtail_male']))
		$this->drawText($otherEqp['checkout']['PIGTAIL']['pigtail_male'],$curX+30,$curX+47,$curY);
		$pdf->Text($curX+48 ,$curY,"Male");
		$pdf->Line($curX+60,$curY,$curX+76,$curY);
		if (isset($otherEqp['checkout']['PIGTAIL']['pigtail_female']))
		$this->drawText($otherEqp['checkout']['PIGTAIL']['pigtail_female'],$curX+60,$curX+76,$curY);
		$pdf->Text($curX+77,$curY,"Female");
		$pdf->Line($curX+94,$curY,$curX+111,$curY);
		if (isset($otherEqp['checkout']['PIGTAIL']['pigtail_s_o']))
		$this->drawText($otherEqp['checkout']['PIGTAIL']['pigtail_s_o'],$curX+94,$curX+111,$curY);
		if (isset($otherEqp['checkout']['PIGTAIL']['pigtail_male']) && isset($otherEqp['checkout']['PIGTAIL']['pigtail_female']))
			$pigTailQty1 = $otherEqp['checkout']['PIGTAIL']['pigtail_male']+ $otherEqp['checkout']['PIGTAIL']['pigtail_female'] + $otherEqp['checkout']['PIGTAIL']['pigtail_s_o'];
		$pdf->Text($curX+112,$curY,"6/4 S/O");
		
		$pigTailQty2=0;  
		$pdf->Text($curX+$secPan,$curY,"Pigtails:");
		$pdf->Line($curX+30+$secPan,$curY,$curX+47+$secPan,$curY);
		if (isset($otherEqp['checkin']['PIGTAIL']['pigtail_male']))
		$this->drawText($otherEqp['checkin']['PIGTAIL']['pigtail_male'],$curX+30+$secPan,$curX+47+$secPan,$curY);
		$pdf->Text($curX+48+$secPan ,$curY,"Male");
		$pdf->Line($curX+60+$secPan,$curY,$curX+76+$secPan,$curY);
		if (isset($otherEqp['checkin']['PIGTAIL']['pigtail_female']))
		$this->drawText($otherEqp['checkin']['PIGTAIL']['pigtail_female'],$curX+60+$secPan,$curX+76+$secPan,$curY);
		$pdf->Text($curX+77+$secPan,$curY,"Female");
		$pdf->Line($curX+94+$secPan,$curY,$curX+111+$secPan,$curY);
		if (isset($otherEqp['checkin']['PIGTAIL']['pigtail_s_o']))
		$this->drawText($otherEqp['checkin']['PIGTAIL']['pigtail_s_o'],$curX+94+$secPan,$curX+111+$secPan,$curY);
		if (isset($otherEqp['checkin']['PIGTAIL']['pigtail_male']) && isset($otherEqp['checkin']['PIGTAIL']['pigtail_female']))
		$pigTailQty2 = $otherEqp['checkin']['PIGTAIL']['pigtail_male']+$otherEqp['checkin']['PIGTAIL']['pigtail_female']+$otherEqp['checkin']['PIGTAIL']['pigtail_s_o'];
		$pdf->Text($curX+112+$secPan,$curY,"6/4 S/O");
		
		$this->addPage();
		$curY = $curY + $diff; 
		$pdf->Text($curX,$curY,"Temp Boxes:");
		$pdf->Line($curX+25,$curY,$curX+43,$curY);
		if (isset($otherEqp['checkout']['TEMPBOX']['temp_boxes']))
		$this->drawText($otherEqp['checkout']['TEMPBOX']['temp_boxes'],$curX+25,$curX+43,$curY);
		$pdf->Text($curX+47 ,$curY,"Pigtails:");
		$pdf->Line($curX+63,$curY,$curX+79,$curY);
		$this->drawText($pigTailQty1,$curX+63,$curX+79,$curY);
		$pdf->Text($curX+83,$curY,"Cable Ramps:");
		$pdf->Line($curX+110,$curY,$curX+125,$curY);
		if (isset($otherEqp['checkout']['CABLE RAMP']['cable_ramps']))
		$this->drawText($otherEqp['checkout']['CABLE RAMP']['cable_ramps'],$curX+110,$curX+125,$curY);
		
		$pdf->Text($curX+$secPan,$curY,"Temp Boxes:");
		$pdf->Line($curX+25+$secPan,$curY,$curX+43+$secPan,$curY);
		if (isset($otherEqp['checkin']['TEMPBOX']['temp_boxes']))
		$this->drawText($otherEqp['checkin']['TEMPBOX']['temp_boxes'],$curX+25+$secPan,$curX+43+$secPan,$curY);
		$pdf->Text($curX+47+$secPan,$curY,"Pigtails:");
		$pdf->Line($curX+63+$secPan,$curY,$curX+79+$secPan,$curY);
		$this->drawText($pigTailQty2,$curX+63+$secPan,$curX+79+$secPan,$curY);
		$pdf->Text($curX+83+$secPan,$curY,"Cable Ramps:");
		$pdf->Line($curX+110+$secPan,$curY,$curX+129+$secPan,$curY);
		if (isset($otherEqp['checkin']['CABLE RAMP']['cable_ramps']))
		$this->drawText($otherEqp['checkin']['CABLE RAMP']['cable_ramps'],$curX+110+$secPan,$curX+125+$secPan,$curY);
		
		$curY = $curY + 4;
		$this->addPage();
		$curY = $curY + $diff; 
		$pdf->Text($curX,$curY,"Check out by:");
		$pdf->Line($curX+26,$curY,$curX+76,$curY);
		$this->drawText($chkOutBy,$curX+26,$curX+76,$curY);
		$pdf->Text($curX+83 ,$curY,"Date:");
		$pdf->Line($curX+95,$curY,$curX+125,$curY);
		$this->drawText($chkOutDate,$curX+95,$curX+125,$curY);
		
		$pdf->Text($curX+$secPan,$curY,"Check in by:");
		$pdf->Line($curX+26+$secPan,$curY,$curX+76+$secPan,$curY);
		$this->drawText($chkInBy,$curX+26+$secPan,$curX+76+$secPan,$curY);
		$pdf->Text($curX+83+$secPan,$curY,"Date:");
		$pdf->Line($curX+95+$secPan,$curY,$curX+129+$secPan,$curY);
		$this->drawText($chkInDate,$curX+95+$secPan,$curX+129+$secPan,$curY);
		
		$this->addPage();
		$curY = $curY + $diff; 
		$pdf->Text($curX,$curY,"Signature:");
		$pdf->Line($curX+25,$curY,$curX+125,$curY);
		
		$pdf->Text($curX+$secPan,$curY,"Signature:");
		$pdf->Line($curX+25+$secPan,$curY,$curX+129+$secPan,$curY);
		$pdf->Output('invoiceRentals.pdf','D');
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
	/*
	* postDateCompletion
	*/
	public function postDateCompletion(){
		$id = Input::get('post_id');	
		$date = Input::get('completionDate');	
		DB::table('gpg_job')->where('id','=',$id)->update(array('complete'=>1,'date_completion'=>$date,'modified_on'=>date('Y-m-d')));
		return Redirect::to('invoice/index')->withSuccess('Record Updated Successfully');
	}

	/*
	* postDateCompletion
	*/
	public function delDateCompletion(){
		$id = Input::get('id');
		if (!empty($id))
			DB::table('gpg_job')->where('id','=',$id)->update(array('complete'=>0,'date_completion'=>NULL,'modified_on'=>date('Y-m-d')));
		return 1;	
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$jobNum = DB::table('gpg_job')->select('job_num')->where('id','=',$id)->get();
		DB::table('gpg_employee_job')->where('gpg_job_id', '=',$id)->delete();
		DB::table('gpg_job_assigned_hist')->where('GPG_job_id', '=',$id)->delete();
		DB::table('gpg_timesheet_detail')->where('GPG_job_id', '=',$id)->delete();
		DB::table('gpg_sales_tracking_job')->where('gpg_job_id', '=',$id)->delete();
		DB::table('gpg_job_billing_note')->where('gpg_job_id', '=',$id)->delete();
		DB::table('gpg_job_rates')->where('job_number', '=',$jobNum[0]->job_num)->delete();
		DB::table('gpg_job_doc')->where('gpg_job_id', '=',$id)->delete();
		Gpg_job::find($id)->delete();
		return Redirect::to('invoice/index')->withSuccess('Record deleted Successfully');
	}


}
