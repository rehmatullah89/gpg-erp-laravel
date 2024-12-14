<?php

class ContractController extends \BaseController {

	protected $ContractTypeArray = array('1'=>'Total Coverage','2'=>'Lump Sum','3'=>'Fixed Labor','4'=>'Itemized Fixed Labor','5'=>'Itemized Lump Sum');
	public function index()
	{
		//
	}

	public function contractList() {
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	    # check if excel export request generated
		$uriSegment = Request::segment(2);
		$data['excelExportReq'] = (isset($uriSegment) && $uriSegment == 'excelExport') ? true : false;

		$data['left_menu']     = Generic::modules();		
		$data['CONTRACT_TYPE'] = Config::get('settings.CONTRACT_TYPE');
		$pageLimit             = Config::get('settings.DEFAULT_PAGE_LIMIT');

		$page = Input::get('page', 1);
   		$results = $this->getByPage($page, $pageLimit);
  		$data['results'] = Paginator::make($results->items, $results->totalItems, $pageLimit);

  		# fill search and filters dropdowns
		$data['customersCombo'] = Gpg_customer::orderBy('name', 'asc')->where('status','=', 'A')->lists('name', 'id');
		$data['employeesCombo'] = Gpg_employee::orderBy('name', 'asc')->whereRaw('status = "A" and concat(",",frontend,",") like "%,sales,%"')->lists('name', 'id');
		$data['deletePermission'] = (Generic::chkModulePerm('contract', 'consum_contract_list', array(1))) ? ['disabled'=>'disabled'] : [];

		# flash input fields to re-populate (state maintain) search form
		Input::flash();
  		
		return View::make('contract.contractList', $data);
	}


	public function getByPage($page = 1, $limit = null)
	{
		$results             = new \StdClass;
		$results->page       = $page;
		$results->totalItems = 0;
		$results->items      = array();
		$defaultDBDateFormat = Config::get('settings.DB_DATE_FORMAT');
	  	
	  	# set limit range (whereas for excel export limit range will not apply)
		$limitRange = '';
		if($limit != null) {
			$results->limit = $limit;
			$start          = $limit * ($page - 1);			
			$limitRange     = 'LIMIT ' . $start . ', ' . $limit;
		}

		$ignoreCostDate         = Input::get("ignoreCostDate");
		$ignoreInvoiceDate      = Input::get("ignoreInvoiceDate");
		$SDate2                 = Input::get("SDate2");
		$EDate2                 = Input::get("EDate2");
		$InvoiceSDate			= Input::get("InvoiceSDate");
		$InvoiceEDate			= Input::get("InvoiceEDate");
		$optJobNumber           = Input::get("optJobNumber");
		$optEmployee            = Input::get("optEmployee");
		$optCustomer            = Input::get("optCustomer");
		$optJobStatus           = Input::get("optJobStatus");
		$optStatus              = Input::get("optStatus");		
		$optStatusRenewd        = Input::get("optStatusRenewd");		
		// $delChk                 = Input::get("delChk");
		$haveAttachedContract   = Input::get("haveAttachedContract");  
		$attachedContractNumber = Input::get("attachedContractNumber");
		$queryPart              = "";
		$fg                     = "";
		$queryPartInvoice		= "";
		$queryPartLaborCost		= "";
		$queryPartMaterialCost	= "";

		// for ($del=0; $del<count($delChk); $del++) {
		// 	$fg = mysql_query("DELETE FROM gpg_consum_contract WHERE id = '".$delChk[$del]."'");
		// }
		 
		 
		if ($SDate2 != "" && $EDate2 != "") 
		{ 
			$queryPart .= " AND cc.created_on >= '".date($defaultDBDateFormat,strtotime($SDate2))." 00:00:00' AND cc.created_on <= '".date($defaultDBDateFormat,strtotime($EDate2))." 23:59:59' ";
			if($ignoreCostDate == '')
			{
				$queryPartMaterialCost =" AND gpg_job_cost.date >= '".date($defaultDBDateFormat,strtotime($SDate2))."' AND gpg_job_cost.date <= '".date($defaultDBDateFormat,strtotime($EDate2))."' ";
				$queryPartLaborCost    =" AND gpg_timesheet.date >= '".date($defaultDBDateFormat,strtotime($SDate2))."' AND gpg_timesheet.date <= '".date($defaultDBDateFormat,strtotime($EDate2))."' ";
			}
			if($ignoreInvoiceDate == '' && $InvoiceSDate == '')
			{
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date($defaultDBDateFormat,strtotime($SDate2))."' AND gpg_job_invoice_info.invoice_date <= '".date($defaultDBDateFormat,strtotime($EDate2))."' ";
			}
		} elseif ($SDate2 != "") {
			$queryPart .= " AND date_format(cc.created_on,'%Y-%m-%d') = '".date($defaultDBDateFormat,strtotime($SDate2))."'";
			
			if($ignoreCostDate == '')
			{
				$queryPartMaterialCost = " AND gpg_job_cost.date = '".date($defaultDBDateFormat,strtotime($SDate2))."' ";
				$queryPartLaborCost    = " AND gpg_timesheet.date = '".date($defaultDBDateFormat,strtotime($SDate2))."' ";
			} 
			if($ignoreInvoiceDate == '' && $InvoiceSDate == '')
			{
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date($defaultDBDateFormat,strtotime($SDate2))."' ";
			} 
		}

		if ($optJobNumber    != "") $queryPart .= " AND job_num = '".$optJobNumber."'";
		if ($optEmployee     != "") $queryPart .= " AND cc.gpg_employee_id = '$optEmployee' ";   
		if ($optCustomer     != "") $queryPart .= " AND cc.gpg_customer_id = '$optCustomer' ";
		if ($optStatus       != "") $queryPart .= " AND consum_contract_status = '$optStatus' ";
		if ($optStatusRenewd != "") $queryPart .= " AND is_renewed = '$optStatusRenewd' ";

		if($haveAttachedContract==1)
		{
			$queryPart .= " AND GPG_attach_contract_number IS NOT NULL ";  
		}
		else if($haveAttachedContract==2)
		{
			$queryPart .= " AND (GPG_attach_contract_number IS NULL OR  GPG_attach_contract_number = '') ";
		}

		if($attachedContractNumber!="")
		{
			$queryPart .= " AND GPG_attach_contract_number LIKE '%".$attachedContractNumber."%' ";  
		}
		
		$queryPart .= " order by cc.created_on desc, job_num desc"; 


	  	$query_count = DB::select( DB::raw("SELECT COUNT(id) AS count FROM gpg_consum_contract AS cc WHERE 1 $queryPart"));
		$query = DB::select(DB::raw("SELECT cc.*,
										eqp.location AS eqp_location,eqp.type AS eqp_type, eqp.make AS eqp_make, eqp.model AS eqp_model, eqp.serial AS eqp_serial, eqp.kw AS eqp_kw, eqp.engMake, eqp.engModel,
								   		(SELECT name FROM gpg_customer WHERE id = cc.GPG_customer_id) AS customer,
								   		(SELECT name FROM gpg_employee WHERE id = cc.GPG_employee_id) AS salesPerson 
									FROM gpg_consum_contract cc
									LEFT JOIN gpg_consum_contract_equipment AS eqp ON ( cc.id = eqp.gpg_consum_contract_id) 
									WHERE 1 									
									$queryPart 									
									$limitRange"));

		if (isset($query_count[0]->count)){
			$results->totalItems = $query_count[0]->count;
			$results->items = $query;
		}

		foreach($results->items as $key=>$getRow) {
			# initializing, fetching, calculating and adding following columns against each data row
			$results->items[$key]->invoice_amount     = 0.00;
			$results->items[$key]->invoice_tax        = 0.00;
			$results->items[$key]->labor_cost         = 0.00;
			$results->items[$key]->material_cost      = 0.00;
			$results->items[$key]->net_margin         = 0.00; 
			$results->items[$key]->commission_amount  = 0.00;
			$results->items[$key]->net_margin_percent = 0.00;
			$results->items[$key]->reduced_comm       = 0.00;
			$results->items[$key]->attachLeadId       = "";

			$attachJobsResults = '';
			if($getRow->GPG_attach_contract_number != null) {
		    	$attachJobsResults = DB::select(DB::raw("SELECT job_num, tax_amount,
						 (SELECT sum(invoice_amount) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice GROUP BY gpg_job_invoice_info.gpg_job_id) AS invoice_amount,
						 (SELECT sum(gpg_job_invoice_info.tax_amount) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice GROUP BY gpg_job_invoice_info.gpg_job_id) AS invoice_tax,
						 (SELECT sum(total_wage) FROM gpg_timesheet_detail , gpg_timesheet WHERE gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id AND gpg_timesheet_detail.job_num = gpg_job.job_num $queryPartLaborCost) AS labor_cost,
						 (SELECT sum(amount) FROM gpg_job_cost WHERE job_num = gpg_job.job_num $queryPartMaterialCost) AS material_cost,
						 (SELECT contract_sales_commission FROM gpg_employee_commission WHERE gpg_employee_commission.gpg_employee_id = gpg_job.GPG_employee_id AND gpg_employee_commission.start_date <= DATE(gpg_job.created_on) order by start_date desc limit 0,1) AS sales_commission  
						 FROM gpg_job WHERE contract_number = '".$getRow->GPG_attach_contract_number."'"));				

				foreach($attachJobsResults as $attachJobRow) {
					$results->items[$key]->invoice_amount     += $attachJobRow->invoice_amount;
					$results->items[$key]->invoice_tax        += $attachJobRow->invoice_tax;
					$results->items[$key]->labor_cost         += $attachJobRow->labor_cost;
					$results->items[$key]->material_cost      += $attachJobRow->material_cost;
					$results->items[$key]->net_margin         += $attachJobRow->invoice_amount - $attachJobRow->invoice_tax - $attachJobRow->labor_cost - $attachJobRow->material_cost;
					$results->items[$key]->net_margin_percent = round(@(($attachJobRow->invoice_amount - $attachJobRow->invoice_tax - $attachJobRow->labor_cost - $attachJobRow->material_cost)/$attachJobRow->invoice_amount)*100,2);
					  
			    	if($results->items[$key]->net_margin_percent > 0 && $results->items[$key]->net_margin_percent <= 50) {
						$results->items[$key]->reduced_comm      = (((50-$results->items[$key]->net_margin_percent)/0.5)/100)*($attachJobRow->sales_commission/100);
						$results->items[$key]->commission_amount += $attachJobRow->invoice_amount*(($attachJobRow->sales_commission/100)-$results->items[$key]->reduced_comm); 
					} else {
						$results->items[$key]->commission_amount += $attachJobRow->invoice_amount*($attachJobRow->sales_commission/100);
					}
				}
			}

			# adding Lead Id against each data row
			$jobNumPtr      = preg_split("/:/", $getRow->job_num);
			$leadContractID = DB::select(DB::raw("SELECT id FROM gpg_consum_contract WHERE job_num ='" . $jobNumPtr['0'] . "'"));
			if(!empty($leadContractID)) {
				$attachLeadId   = DB::select(DB::raw("SELECT gpg_sales_tracking_id FROM gpg_sales_tracking_consum_contract WHERE gpg_consum_contract_id ='" . $leadContractID[0]->id . "'"));
				$renew_query    = DB::select(DB::raw("SELECT COUNT(*) FROM gpg_sales_tracking WHERE parent_id = '" . $attachLeadId[0]->gpg_sales_tracking_id . "' OR (parent_id IS NOT NULL AND id = '" . $attachLeadId[0]->gpg_sales_tracking_id . "')"));

				$results->items[$key]->attachLeadId = (!empty($attachLeadId[0]->gpg_sales_tracking_id)) ? $attachLeadId[0]->gpg_sales_tracking_id : "";
			}

			# adding commession (owed, paid, date and balance) against each data row
			$commOwed = $saleCom = $results->items[$key]->commission_amount;
			$attachJobsResults['id'] = (!empty($attachJobsResults['id'])) ? $attachJobsResults['id'] : '';
			$commData = DB::select(DB::raw("SELECT comm_date, sum(ifnull(comm_paid,0)) AS amt, count(id) AS cnt 
											FROM gpg_job_commission 
											WHERE gpg_job_id = '" . $attachJobsResults['id'] . "' group by gpg_job_id order by created_on desc"));
			
			$results->items[$key]->comm_owed    = (!empty($commOwed)) ? $commOwed : 0.00;
			$results->items[$key]->comm_amount  = (!empty($commData[0]->amt)) ? $commData[0]->amt : 0.00;
			$results->items[$key]->comm_date    = (!empty($commData[0]->comm_date)) ? $commData[0]->comm_date : "-";
			$results->items[$key]->comm_balance = $results->items[$key]->comm_owed - $results->items[$key]->comm_amount;
		}		

		return $results;
	}

	public function downloadFile() {
		$fileName = Input::get('file');
		$filePath = public_path() . '/' . Config::get('settings.CONTRACT_FILE_PATH') . '/' . $fileName;
		return Response::download($filePath);
	}

	public function contractInvoiceAmount() {
		$queyPart       = "";
		$contractNumber = Input::get('contract_num');		
		$SDate2         = Input::get('SDate2');
		$EDate2         = Input::get("EDate2");
		$InvoiceSDate   = Input::get("InvoiceSDate");
		$InvoiceEDate   = Input::get("InvoiceEDate");
		

		if ($InvoiceSDate != '' && $InvoiceEDate != '') {
		    $queyPart = "AND invoice_date >='" . date(Config::get('settings.$defaultDBDateFormat'), strtotime($InvoiceSDate)) . "' AND invoice_date <='" . date(Config::get('settings.$defaultDBDateFormat'), strtotime($InvoiceEDate)) . "' ";
		}
		if ($InvoiceSDate != '' && $InvoiceEDate == '') {
		    $queyPart = "AND invoice_date ='" . date(Config::get('settings.$defaultDBDateFormat'), strtotime($InvoiceSDate)) . "' ";
		}
		if ($InvoiceSDate == '' && $SDate2 != '' && $EDate2 != '') {
		    $queyPart = "AND invoice_date >='" . date(Config::get('settings.$defaultDBDateFormat'), strtotime($SDate2)) . "' AND invoice_date <='" . date(Config::get('settings.$defaultDBDateFormat'), strtotime($EDate2)) . "' ";
		}
		if ($InvoiceSDate == '' && $SDate2 != '' && $EDate2 == '') {
		    $queyPart = "AND invoice_date ='" . date(Config::get('settings.$defaultDBDateFormat'), strtotime($SDate2)) . "' ";
		}

		$attachContractJobs = DB::select(DB::raw("SELECT id,job_num,tax_amount,
					(SELECT sum(total_wage) FROM gpg_timesheet_detail , gpg_timesheet WHERE gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num) as labor_cost , 
					(SELECT sum(amount) FROM gpg_job_cost WHERE job_num = gpg_job.job_num) as material_cost,
					(SELECT sales_commission FROM gpg_employee_commission WHERE gpg_employee_commission.gpg_employee_id = gpg_job.GPG_employee_id and gpg_employee_commission.start_date <= DATE(gpg_job.created_on) order by start_date desc limit 0,1) as sales_commission 
					FROM gpg_job 
					WHERE 
					contract_number = '" . $contractNumber . "'"));

		foreach($attachContractJobs as $key=>$getRow) {
    		$invoiceData = DB::select(DB::raw("SELECT * FROM gpg_job_invoice_info WHERE gpg_job_id = '" . $getRow->id . "' $queyPart ORDER BY invoice_date desc"));
    		$attachContractJobs[$key]->invoiceData = $invoiceData;
    	}
    	
		return View::make('contract.contractInvoiceAmount', array('attachContractJobs' => $attachContractJobs));
	}

	public function deleteContracts() {
		$selectedIds = implode(',', Input::get('selectedIds'));
		
		DB::table('gpg_consum_contract')->whereRaw("id IN ($selectedIds)")->delete();
		return 1;
	}


	public function getContractFiles() {
		$id        = Input::get('id');
		$job_num   = Input::get('num');
		$colcount  = 1;
		$conCatStr = '';
		
		$files = DB::select(DB::raw("SELECT * FROM gpg_consum_contract_attachment WHERE gpg_consum_contract_id = '$id'"));
		
		if (!empty($files)){
			foreach($files as $key=>$row){
        	    $conCatStr .= '<tr>
	    						<td>'.$colcount++.'</td>
    							<td>'.$row->displayname.'</td>
    							<td><a class="btn btn-danger btn-xs" id="'.$row->id.'" name="del_contract_file">Delete</a>
    								<a class="btn btn-success btn-xs" id="'.$row->id.'" name="dld_contract_file">Download</a>
								</td>
    						</tr>';
			}
    	}
    	return $conCatStr;
	}

	public function manageContractFiles() {
		if (!empty($_POST['fjob_id'])) {
			$job_id  = $_POST['fjob_id'];
			$job_num = $_POST['fjob_num'];
			$file    = Input::file('attachment');
			
			$file_type_settings =  DB::table('gpg_settings')
			    ->select('*')
			    ->where('name', '=', '_ImgExt')
			    ->get();    
			$file_types = explode(',', $file_type_settings[0]->value);
			
			if (!empty($file)) {
				if (in_array($file->getClientOriginalExtension(), $file_types)) {
					$ext1            = explode(".",$file->getClientOriginalName());
					$ext2            = end($ext1);
					$filename        = "consum_contract_".rand(99999,10000000)."_".strtotime("now").".".$ext2;
					$destinationPath = public_path() .'/' . Config::get('settings.CONTRACT_FILE_PATH') . '/';
					$uploadSuccess   = $file->move($destinationPath, $filename);
					
					DB::table('gpg_consum_contract_attachment')->insert(array('gpg_consum_contract_id' =>$job_id,'filename'=>$filename ,'displayname'=>$file->getClientOriginalName()));		
				}
			}
		}
		return Redirect::to('contract/contractList');
	}

	public function deleteContractFile(){
		$id = Input::get('id');
		DB::table('gpg_consum_contract_attachment')->where('id', '=',$id)->delete();
		return 1;
	}

	public function excelExport(){
		ini_set('memory_limit', '-1');
	    Excel::create('Contracts List', function($excel) {
		    $excel->sheet('Contracts List', function($sheet) {

			    $sheet->setStyle(array(
	    			'td' => array(
	    				'background' => 'blue'
	    			)
				));	

			    $data['CONTRACT_TYPE'] = Config::get('settings.CONTRACT_TYPE');
				$pageLimit             = Config::get('settings.DEFAULT_PAGE_LIMIT');

				$page = Input::get('page', 1);
		   		$results = $this->getByPage($page);
		  		$data['results'] = Paginator::make($results->items, $results->totalItems, $pageLimit);

		        $sheet->loadView('contract.excelExport', $data);
	    	});
		})->export('xls');
 	}

 	/**
 	*
 	* Consum Contract Service Type Listing and Management
 	*
 	**/
 	
 	public function consumServiceType() {

		$data['left_menu'] = Generic::modules();
		$data['results']   = Gpg_CC_service_type::all()->toArray();

 		return View::make('contract.consumServiceType', $data);
 	}

 	public function saveContractServiceType()
	{	
		$id          = Input::get('id');
		$serviceType = Input::get('service_type');

		if(empty($id)) {
			$result = DB::table('gpg_consum_contract_service_type')
          				->insertGetId(array('service_type' =>$serviceType, 'created_on' =>date("Y-m-d H:i:s")));

			return ($result) ? $result : false;
		} else {
			$result = DB::table('gpg_consum_contract_service_type')
      					->where('id','=', $id)
          				->update(array('service_type' =>$serviceType, 'modified_on' =>date("Y-m-d H:i:s")));

			return ($result) ? 'updated' : false;
  		}
	}
	/*
	* contractAmtOpt
	*/
	public function contractAmtOpt(){
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
					$contract_number = '';
					$values = explode('	', $opt);
					for ($i=0; $i<count($heading); $i++) { 
						if (preg_match("/contract_number/i",$heading[$i])){
							$contract_number = $values[$i];
						}elseif (preg_match("/date/i",$heading[$i])){
							$setValue += array($heading[$i]=>date('Y-m-d',strtotime($values[$i])));
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
						}else
							$setValue += array($heading[$i]=>$values[$i]);
					}//end for loop
					if (!empty($setValue) && !empty($contract_number) && $contract_number != 0){
						$cont_id = DB::table('gpg_contract_amt_list')->where('contract_number','=',$contract_number)->pluck('id');
						if (isset($cont_id) && !empty($cont_id) && $cont_id != '0') {
							DB::table('gpg_contract_amt_list')->where('contract_number','=',$contract_number)->update($setValue+array('modified_on'=>date('Y-m-d')));
						}else{
							DB::table('gpg_contract_amt_list')->insert($setValue+array('contract_number'=>$contract_number,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
						}
					}
				}//end while loop
				return Redirect::to('contract/contract_amt_opt')->withSuccess('Records have been Inserted/Updated Successfully');
			}else{
				$file = Input::file('uploadFile');
				$filename = "";
				if (!empty($file)) {
					$file1 = Input::file('uploadFile')->getClientOriginalName();
					$filename = "contractUpload_".rand(99999,10000000)."_".strtotime("now").".".$file1;
					$destinationPath = public_path().'/img/';
					$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
				}
				$fh = fopen($destinationPath.$filename,'r');
				$jcostopt = trim(fgets($fh));
				$jcostopt_arr = explode("\t", $jcostopt);
				fclose($fh);
				$DBFields = array("customer"=>"Customer Name/Company Name","contract_number"=>"Contract Number","contract_prefix"=>"Contract Prefix","contract_digits"=>"Contract Digits","location"=>"Location","type"=>"Type","start_date"=>"Start Date","end_date"=>"End Date","price_per_year"=>"Price per Year","billing_cycle"=>"Billing Cycle","price_per_visit"=>"Price per Visit","salesPerson"=>"Sales Person");
				$params = array('left_menu' => $modules,'success'=>'0','step'=>'1','jcostopt_arr'=>$jcostopt_arr,'DBFields'=>$DBFields,'dest'=>$destinationPath,'filename'=>$filename);
				return View::make('contract/contract_amt_opt', $params);
			}
		}
		$params = array('left_menu' => $modules,'step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array(),'success'=>'0');
		return View::make('contract/contract_amt_opt', $params);
	}
	
	/*
	* contractInfoImp
	*/
	public function contractInfoImp(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)) {
			$file = Input::file('uploadFile');
			$filename = "";
			if (!empty($file)) {
				$file1 = Input::file('uploadFile')->getClientOriginalName();
				$filename = "contInfoImp_".rand(99999,10000000)."_".strtotime("now").".".$file1;
				$destinationPath = public_path().'/img/';
				$uploadSuccess = Input::file('uploadFile')->move($destinationPath, $filename);
			}
			$filepath = $destinationPath.$filename;
	  		$fileinfo = file($filepath);
	  		$objPHPExcel = PHPExcel_IOFactory::load($filepath);
			$objPHPExcel->setActiveSheetIndex(0);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$arrExceldata  = array();
			$rowTemp = '';
			for ($row = 1; $row <= $highestRow; ++$row) {
				$Rows = 1;
				for ($col = 0; $col <= $highestColumnIndex; ++$col) {
					$currentCellDataToClean  = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
					if(!empty($currentCellDataToClean)){
						if($rowTemp=='')
						$rowTemp = $row;
					    $currentCellDataToClean  = str_replace(",","-",$currentCellDataToClean);
					    $currentCellDataToClean  = str_replace("'","&#39;",$currentCellDataToClean);
					    $currentCellDataToClean  = str_replace("\"","",$currentCellDataToClean);
					    $currentCellDataToClean  = str_replace("=","",$currentCellDataToClean);
					    $arrExceldata[$objWorksheet->getCellByColumnAndRow($col,$rowTemp)->getValue()][$row] = $currentCellDataToClean;
						$Rows ++;
					}
     			}
			}
			$format ='';
			$keys = array('Type','Make','Model','Serial#','kW');
		    foreach ($keys as $key) {		        
				if (array_key_exists($key, $arrExceldata)) {
		         	$format  = '1'; 
				} else {
		        	$format  = '0'; 
					break;
		        }
			}
			if($format =='1'){
				for( $i=1 ; $i<=count($arrExceldata['Contract#']) ; $i++) {
					if (preg_match('/SA/i',$arrExceldata['Contract#'][$i])){
						$ConsumConId =  DB::table('gpg_consum_contract')->where('GPG_attach_contract_number','=',$arrExceldata['Contract#'][$i])->pluck('id');  
						if($ConsumConId!=''){
							$eqp_update = DB::table('gpg_consum_contract_equipment')->where('gpg_consum_contract_id','=',$ConsumConId)->update(array("type="=>$arrExceldata['Type'][$i], "make"=>$arrExceldata['Make'][$i], "model"=>$arrExceldata['Model'][$i], "serial"=>$arrExceldata['Serial#'][$i], "kw"=>$arrExceldata['kW'][$i],"modified_on"=>date('Y-m-d')));
						}
					}
				}
				return Redirect::to('contract/contract_info_imp')->withSuccess('Records have been imported Successfully');
			}
			return Redirect::to('contract/contract_info_imp')->withErrors(['Data according not found, Please select related file only!']);
		}
		$params = array('left_menu' => $modules,'step'=>'0','jcostopt_arr'=>array(),'DBFields'=>array(),'success'=>'0');
		return View::make('contract/contract_info_imp', $params);
	}

	/*
	* consumContractForm
	*/
	public function consumContractForm($job_id,$job_num){
		$modules = Generic::modules();
		$field_serv_ids = DB::select(DB::raw("select a.id,concat(a.job_num,' ',ifnull((select name from gpg_customer where id = a.GPG_customer_id),''),'&nbsp; | &nbsp;',ifnull(a.sub_task,''),'&nbsp; | &nbsp;',ifnull(a.location,''),'') as name from gpg_field_service_work a,gpg_consum_contract_equipment b where a.gpg_consum_contract_equipment_id > 0 and ifnull(b.gpg_consum_contract_id,0)=0 and a.gpg_consum_contract_equipment_id = b.id order by a.job_num"));
		$fs_data = array();
		foreach ($field_serv_ids as $key => $value) {
			$fs_data[$value->id] = $value->name;
		}
		$customers = DB::table('gpg_customer')->where('status','=','A')->orderBy('name')->lists('name','id');
		$cnums = DB::select(DB::raw("select contract_number as id , contract_number as name from gpg_job where ifnull(contract_number,'') <> '' group by contract_number order by contract_number"));
		$cn_data = array();
		foreach ($cnums as $key => $value) {
			$cn_data[$value->id] = $value->name;
		}
		$emps = DB::select(DB::raw("select id,name,reg_pay from gpg_employee where  concat(',',frontend,',') like '%,sales,%'"));
		$emp_data = array();
		foreach ($emps as $key => $value) {
			$emp_data[$value->id] = $value->name;
		}
		$qry = DB::table('gpg_consum_contract')->select('*')->where('id','=',$job_id)->get();
		$consumContractTblRow = array();
		foreach ($qry as $key => $value) {
			$consumContractTblRow = (array)$value;
		}
		$resSalesTrackingRowtbl = DB::table('gpg_sales_tracking_consum_contract')->join('gpg_sales_tracking', 'gpg_sales_tracking_consum_contract.gpg_sales_tracking_id', '=', 'gpg_sales_tracking.id')->where('gpg_consum_contract_id','=',$job_id)->pluck('parent_id');
		$eqpQuery = DB::select(DB::raw("select * from gpg_consum_contract_equipment where gpg_consum_contract_id = '$job_id'"));
		$consumContractEqpTblRow = array();
		foreach ($eqpQuery as $key => $value) {
			$consumContractEqpTblRow = (array)$value;
		}
		$terms = DB::table('gpg_settings')->where('name','LIKE','_ContractTermsAndConditions%')->lists('value','id');
		$address = DB::table('gpg_settings')->where('name','LIKE','_ContactInfo%')->lists('value','id');
		$atsDataQuery = DB::table('gpg_consum_contract_ats')->select('*')->where('gpg_consum_contract_id','=',$job_id)->orderBy('created_on','DESC')->get();
		$ats_arr = array();
		foreach ($atsDataQuery as $key => $value) {
			$ats_arr[] = (array)$value;
		}
		//////////////////////////
		$children = array();
		$childrenData = array();
		$sale = DB::select(DB::raw("SELECT * FROM gpg_sales_tracking_consum_contract gs, gpg_sales_tracking gst WHERE gs.gpg_sales_tracking_id = gst.id AND gs.gpg_consum_contract_id = '".$job_id."'"));
		$saleTracking = array();
		foreach ($sale as $key => $value) {
			$saleTracking[] = (array)$value;
		}
		if(!isset($saleTracking[0])){
            $saleTracking = array();
            $leadContractID = $job_id;
            $sale = DB::select(DB::raw("SELECT * FROM gpg_sales_tracking_consum_contract gs,gpg_sales_tracking gst WHERE gs.gpg_sales_tracking_id = gst.id AND gs.gpg_consum_contract_id = '".$leadContractID."'"));
            $saleTracking = array();
			foreach ($sale as $key => $value) {
				$saleTracking[] = (array)$value;
			}
			if (!isset($saleTracking[0]['singleLead']))
	            $saleTracking[0]['singleLead'] = true;
        } else{
            if (!isset($saleTracking[0]['singleLead']))
                $saleTracking[0]['singleLead'] = false;
        }
        if(isset($saleTracking[0]['parent_id']) && $saleTracking[0]['parent_id'] == '' || empty($saleTracking[0]['parent_id'])){
            $children[] = $saleTracking[0];
            if (isset($saleTracking[0]['id']))
	            $childrenData = $this->categoryChild($saleTracking[0]['id'], $children); //all_children($saleTracking[0]['id'], $saleTracking);
        } else if(!empty($saleTracking[0]['parent_id'])){
            $id = $this->top_parent($saleTracking[0]['parent_id']);
            $sql = DB::select(DB::raw("SELECT * from gpg_sales_tracking where id = '".(int)$id."'"));
            $returnData = array();
            foreach ($sql as $key => $value) {
            	$returnData = (array)$value; 	
            }
            $children[] = $returnData;
            $childrenData = $this->categoryChild($id, $children);
        }
        $load_bank_matrix = DB::table('gpg_consum_contract_matrix_load_bank')->select('*')->where('gpg_consum_contract_id','=',$job_id)->orderBy('id')->get();
 	    $marerialQuery = DB::select(DB::raw("select *,(select name from gpg_field_component_type where id = gpg_consum_contract_component.component_id) as component,(select part_number from gpg_field_component where id = gpg_consum_contract_component.part_id) as partNumber from gpg_consum_contract_component where gpg_consum_contract_id = '".$job_id."' order by created_on desc "));
 	    $materialRows1 = array();
 	    foreach ($marerialQuery as $key => $value) {
 	    	$materialRows1[] = (array)$value;
 	    }
 	    $materialRows2 = array();
 	    $marerialQuery2 = DB::select(DB::raw("select *,(select name from gpg_field_material_type where id = gpg_consum_contract_material.material_id) as material,(select part_number from gpg_field_material where id = gpg_consum_contract_material.part_id) as partNumber from gpg_consum_contract_material where gpg_consum_contract_id = '".$job_id."' order by created_on desc "));
 	    foreach ($marerialQuery2 as $key => $value) {
 	    	$materialRows2[] = (array)$value;
 	    }
 	    $other_charge_cost = 0;
 	    $otherChrage = DB::select(DB::raw("SELECT SUM(IFNULL(other_charge_qty,0) * IFNULL(other_charge_cost_price,0)) as other_charges FROM gpg_consum_contract_other  WHERE gpg_consum_contract_id = '$job_id'"));
		if (isset($otherChrage[0]->other_charges))
			$other_charge_cost = $otherChrage[0]->other_charges;

		$other_charge_price = 0;
 	    $otherPrice = DB::select(DB::raw("SELECT SUM(IFNULL(other_charge_qty,0) * IFNULL(other_charge_price,0)) FROM gpg_consum_contract_other  WHERE gpg_consum_contract_id = '$job_id'"));
		if (isset($otherPrice[0]->other_charges))
			$other_charge_price = $otherPrice[0]->other_charges;
		$ccSecStart = array();
        $ccQry = DB::select(DB::raw("select month,year from gpg_consum_contract_schedule where gpg_consum_contract_id = '". $job_id."' order by id limit 0,1"));
        foreach ($ccQry as $key => $value) {
        	$ccSecStart =  (array)$value;   
        }
        $schDataRs = array();
        $schDataQuery = DB::select(DB::raw("select * FROM gpg_consum_contract_schedule WHERE  gpg_consum_contract_id = '$job_id' order by id asc"));                          
		foreach ($schDataQuery as $key => $value) {
			$schDataRs[] = (array)$value;		
		}

		/*echo "<pre>";
		print_r($schDataRs);
		die();*/
		$other_arr = array('other_charge_cost'=>$other_charge_cost,'other_charge_price'=>$other_charge_price,'ccSecStart'=>$ccSecStart,'schDataRs'=>$schDataRs);
		$params = array('left_menu' => $modules,'job_id'=>$job_id,'job_num'=>$job_num,'fs_data'=>$fs_data,'cn_data'=>$cn_data,'emp_data'=>$emp_data,'consumContractTblRow'=>$consumContractTblRow,'resSalesTrackingRowtbl'=>$resSalesTrackingRowtbl,'customers'=>$customers,'consumContractEqpTblRow'=>$consumContractEqpTblRow,'ContractTypeArray'=>$this->ContractTypeArray,'terms'=>$terms,'address'=>$address,'ats_arr'=>$ats_arr,'childrenData'=>$childrenData,'load_bank_matrix'=>$load_bank_matrix,'materialRows1'=>$materialRows1,'materialRows2'=>$materialRows2)+$other_arr;
		return View::make('contract/consum_contract_frm', $params);
	}

	/*
	* duplicateContract
	*/
	public function duplicateContract(){
		$consum_contract_id = Input::get('id');
		$cusId =  Input::get("cusId");
		$renew = 1;
		$flag=0;
		$consumContractColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract"));
		$getConsumContract = array();
		$getCCQry = DB::select(DB::raw("select * from gpg_consum_contract where id = '$consum_contract_id'")); 
		foreach ($getCCQry as $key => $value) {
			$getConsumContract = (array)$value;
		}
		$insertQuery = array();
		$ignore = array("id","job_num","created_on", "modified_on","annual_charges","pm_charges","show_lump_sum","exclude_from_contract");
		foreach ($consumContractColsRs as $key => $consumContractColsRow){
			    if (!in_array($consumContractColsRow->Field,$ignore)) 
				{
					if($renew==1 and ($consumContractColsRow->Field=='consum_contract_start_date' 
										or $consumContractColsRow->Field=='consum_contract_end_date' 
										or $consumContractColsRow->Field=='is_renewed' 
										or $consumContractColsRow->Field=='consum_contract_status'))
					{
						if($consumContractColsRow->Field=='is_renewed')
							$insertQuery+= array($consumContractColsRow->Field=>'1');
						if($consumContractColsRow->Field=='consum_contract_status')
							$insertQuery+= array($consumContractColsRow->Field=>'Quote');
						if($consumContractColsRow->Field=='consum_contract_start_date')
						{
							$insertQuery+= array($consumContractColsRow->Field=>(!empty($getConsumContract['consum_contract_end_date'])?"'".addslashes($getConsumContract['consum_contract_end_date'])."'":"NULL"));
						}
						elseif($consumContractColsRow->Field=='consum_contract_end_date')
						{
							$end_date = "NULL";
							if(!empty($getConsumContract['consum_contract_end_date']))
							{
								$end_date = "'".date('Y-m-d',strtotime($getConsumContract['consum_contract_end_date']." +1 year"))."'";
							}
							$insertQuery+= array($consumContractColsRow->Field=>$end_date);
						}
					}
					elseif($consumContractColsRow->Field=='is_renewed' )
						$insertQuery+= array($consumContractColsRow->Field=>'0');
					else
						$insertQuery+= array($consumContractColsRow->Field=>(!empty($getConsumContract[$consumContractColsRow->Field])?"'".addslashes($getConsumContract[$consumContractColsRow->Field])."'":"NULL"));
				}
		}
		$getMaxConsumContractId = DB::table('gpg_consum_contract')->max('id')+1;
		$jobNum = explode(":",$getConsumContract['job_num']);
		$getMaxJobNum = '';
		$getMaxJobNumQ = DB::select(DB::raw("select job_num from gpg_consum_contract where concat(job_num,':') like '".$jobNum[0].":%' order by id desc limit 0,1"));
		if (!empty($getMaxJobNumQ) && isset($getMaxJobNumQ[0]->job_num)){
			$getMaxJobNum = $getMaxJobNumQ[0]->job_num;
		}
		$newJobNum = explode(":",$getMaxJobNum);
		if (empty($newJobNum[1])) $newJobNum1 = $newJobNum[0].":02";
		else $newJobNum1 = $newJobNum[0].":".str_pad((int) $newJobNum[1]=$newJobNum[1]+1,2,"0",STR_PAD_LEFT);
		$contactNum = $newJobNum[0];
		$insertQuery2 = DB::table('gpg_consum_contract')->insert($insertQuery+array('id'=>$getMaxConsumContractId, 'job_num'=>$newJobNum1, 'created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d')));
		
		if($insertQuery2){
		 	$flag = 1;
			$ignore1 = array("id", "gpg_consum_contract_id", "created_on", "modified_on");
			$ignore2 = array("id", "gpg_consum_contract_schedule_id","month","year","service1","price1","service2","price2","service3","price3","installment", "created_on", "modified_on");
		    ///// EQUIPMENT		
			$getConsumContractEqp = DB::select(DB::raw("select * from gpg_consum_contract_equipment where gpg_consum_contract_id = '$consum_contract_id'"));
			foreach ($getConsumContractEqp as $key => $eqpRow)
			{
				$eqpRow = (array)$eqpRow;
				$eqpColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_equipment"));
				$insertEqpQuery = array();
				foreach ($eqpColsRs as $key => $eqpColsRow)
				{
					  if (!in_array($eqpColsRow->Field,$ignore1)) 
					  	$insertEqpQuery += array($eqpColsRow->Field => addslashes($eqpRow[$eqpColsRow->Field]));
				}
				$insertEqpQuery2 = DB::table('gpg_consum_contract_equipment')->insert($insertEqpQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId, 'created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d')));
			}
			///// ATS		
			$getConsumContractAts = DB::select(DB::raw("select * from gpg_consum_contract_ats where gpg_consum_contract_id = '$consum_contract_id'"));
			foreach ($getConsumContractAts as $key => $atsRow){
				$atsRow = (array)$atsRow;
				$atsColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_ats"));
				$insertAtsQuery = array();
				foreach ($atsColsRs as $key => $atsColsRow){
					if (!in_array($atsColsRow->Field,$ignore1)) 
					  	$insertAtsQuery += array($atsColsRow->Field =>addslashes($atsRow[$atsColsRow->Field]));
				}
				$insertAtsQuery2 = DB::table('gpg_consum_contract_ats')->insert($insertAtsQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId,'created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d')));
			}
			///// SCHEDULE		
			$getConsumContractSched = DB::select(DB::raw("select * from gpg_consum_contract_schedule where gpg_consum_contract_id = '$consum_contract_id'"));
			$serviceColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_schedule_service"));
			foreach ($getConsumContractSched as $key => $schedRow)
			{
				$schedRow = (array)$schedRow;
				$schedColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_schedule"));
				$insertSchedQuery = array();
				foreach ($schedColsRs as $key => $schedColsRow)
				{
					  if (!in_array($schedColsRow->Field,$ignore1))
					  	$insertSchedQuery += array($schedColsRow->Field=>addslashes($schedRow[$schedColsRow->Field]));
				}
				$insertSchedQuery2 = DB::table('gpg_consum_contract_schedule')->insert($insertSchedQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
					$dup_schedule_service_id = DB::table('gpg_consum_contract_schedule')->max('id');
					$service_rs = DB::select(DB::raw("SELECT * FROM gpg_consum_contract_schedule_service WHERE gpg_consum_contract_schedule_id = ".$schedRow['id']."")); 
					$service_no = 0;
					foreach ($service_rs as $key => $service_rs_row)
					{
						$service_rs_row = (array)$service_rs_row;	
						$insertServiceQuery = array();
						foreach ($serviceColsRs as $key => $serviceColsRow)
						{
							if (!in_array($serviceColsRow->Field,$ignore2)) 
								$insertServiceQuery += array($serviceColsRow->Field=>addslashes($service_rs_row[$serviceColsRow->Field]));
						}
						$service_no++;
						$insertServiceQuery2 = DB::table('gpg_consum_contract_schedule_service')->insert($insertServiceQuery+array('gpg_consum_contract_schedule_id'=>$dup_schedule_service_id)); 
					}
			   }
			///// MATERIAL		
			$getConsumContractMaterial = DB::select(DB::raw("select * from gpg_consum_contract_material where gpg_consum_contract_id = '$consum_contract_id'"));
			foreach ($getConsumContractMaterial as $key => $materialRow)
			{
				$materialRow = (array)$materialRow;
				$materialColsRs = mysql_query("SHOW COLUMNS FROM gpg_consum_contract_material");
				$insertMaterialQuery = array();
				foreach ($materialColsRs as $key => $materialColsRow)
				{
					  if (!in_array($materialColsRow->Field,$ignore1))
					  	$insertMaterialQuery += array($materialColsRow->Field=>addslashes($materialRow[$materialColsRow->Field]));
				}
				$insertMaterialQuery2 = DB::table('gpg_consum_contract_material')->insert($insertMaterialQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId, 'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
			}
			///// COMPONENT		
			$getConsumContractComponent = DB::select(DB::raw("select * from gpg_consum_contract_component where gpg_consum_contract_id = '$consum_contract_id'"));
			foreach ($getConsumContractComponent as $key => $componentRow)
			{
				$componentRow = (array)$componentRow;
				$componentColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_component"));
				$insertComponentQuery = array();
				foreach ($componentColsRs as $key => $componentColsRow)
				{
					  if (!in_array($componentColsRow->Field,$ignore1)) 
					  	$insertComponentQuery += array($componentColsRow->Field=>addslashes($componentRow[$componentColsRow->Field]));
				}
				$insertComponentQuery2 = DB::table('gpg_consum_contract_component')->insert($insertComponentQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
			}
			///// LABOR		
			$getConsumContractLabor = DB::select(DB::raw("select * from gpg_consum_contract_labor where gpg_consum_contract_id = '$consum_contract_id'"));
			foreach ($getConsumContractLabor as $key => $laborRow)
			{
				$laborRow = (array)$laborRow;
				$laborColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_labor"));
				$insertLaborQuery = array();
				foreach ($laborColsRs as $key => $laborColsRow)
				{
		     		if (!in_array($laborColsRow->Field,$ignore1)) 
					  	$insertLaborQuery += array($laborColsRow->Field=>addslashes($laborRow[$laborColsRow->Field]));
				}
				$insertLaborQuery2 = DB::table('gpg_consum_contract_labor')->insert($insertLaborQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId, 'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
			}
			//////////////////////////////
			///// OTHER		
			$getConsumContractOther = DB::select(DB::raw("select * from gpg_consum_contract_other where gpg_consum_contract_id = '$consum_contract_id'"));
			foreach ($getConsumContractOther as $key => $otherRow)
			{
				$otherRow = (array)$otherRow;
				$otherColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_other"));
				$insertOtherQuery = array();
				foreach ($otherColsRs as $key => $otherColsRow)
				{
					  if (!in_array($otherColsRow->Field,$ignore1)) 
					  	$insertOtherQuery += array($otherColsRow->Field=>addslashes($otherRow[$otherColsRow->Field]));
				}			 
				$insertOtherQuery2 = DB::table('gpg_consum_contract_other')->insert($insertOtherQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId, 'created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d')));
			}
			//////////////////////////////						
			$contractInfo = array();
			$totalsCost = array();
			$cinfoQry = DB::select(DB::raw("select id,mat_cost_total,comp_cost_total,grand_list_total,labor_cost_total from gpg_consum_contract where job_num = '".$contactNum."'"));
			foreach ($cinfoQry as $key => $value) {
				$contractInfo = (array)$value;
			}
			$totalsCost['mat_cost_total'] = $contractInfo['mat_cost_total'];
		    $totalsCost['comp_cost_total'] = $contractInfo['comp_cost_total'];
			$totalsCost['grand_list_total'] = $contractInfo['grand_list_total'];
			$totalsCost['labor_cost_total'] = $contractInfo['labor_cost_total'];
			
			$contractLeadId = DB::table('gpg_sales_tracking_consum_contract')->where('gpg_consum_contract_id','=',$contractInfo['id'])->pluck('gpg_sales_tracking_id'); 
			$typeofSale =  DB::table('gpg_sales_tracking')->where('id','=',$contractLeadId)->pluck('type_of_sale'); 
			if($typeofSale=="PMcontract"){
				$costRs = DB::select(DB::raw("select id,mat_cost_total,comp_cost_total,grand_list_total,labor_cost_total from gpg_consum_contract where job_num like '".$contactNum.":%'"));
			    foreach ($costRs as $key => $costRow)
			    {
				  $totalsCost['mat_cost_total'] += $costRow['mat_cost_total'];
				  $totalsCost['comp_cost_total'] += $costRow['comp_cost_total'];
				  $totalsCost['grand_list_total'] += $costRow['grand_list_total'];
				  $totalsCost['labor_cost_total'] += $costRow['labor_cost_total'];
		   		}
		    	$salesTrackingQuery = DB::table('gpg_sales_tracking')->where('id','=',$contractLeadId)->update(array('projected_sale_price'=>$totalsCost['grand_list_total'], 'material_cost'=>($totalsCost['mat_cost_total'] + $totalsCost['comp_cost_total']), 'labor_cost'=>$totalsCost['labor_cost_total'], 'modified_on'=> date('Y-m-d')));
			}
			$qry_load_bank = DB::select(DB::raw("SELECT * FROM gpg_consum_contract_matrix_load_bank WHERE gpg_consum_contract_id = '".$consum_contract_id."' ORDER BY id"));
			foreach ($qry_load_bank as $key => $rrow)
			{	$qry = array();
				foreach($rrow as $k => $v)
				{
					if($k!= 'gpg_consum_contract_id' and $k!= 'id' and $k!= 'created_on' and $k!= 'modified_on')
					{
						$qry += array($k=>$v);
					}
				}
				DB::table('gpg_consum_contract_matrix_load_bank')->insert($qry+array('gpg_consum_contract_id'=>$getMaxConsumContractId, 'created_on'=> date('Y-m-d'), 'modified_on'=> date('Y-m-d')));
			}
			return 1;		 	 
		}else
			return 0;

	}

	/*
	* renewContract
	*/
	public function renewContract(){
		$consum_contract_id = Input::get('id');
		$cusId =  Input::get("cusId");
		$renew = 1;
		$flag=0;
		$getConsumContract = array();
		$getCCQry = DB::select(DB::raw("select * from gpg_consum_contract where id = '$consum_contract_id'")); 
		foreach ($getCCQry as $key => $value) {
			$getConsumContract = (array)$value;
		}
		$jobNum = explode(":",$getConsumContract['job_num']);
		$consum_contract_id= DB::table('gpg_consum_contract')->where('job_num','=',$jobNum[0])->pluck('id');
		$leadId= DB::table('gpg_sales_tracking_consum_contract')->where('gpg_consum_contract_id','=',$consum_contract_id)->pluck('gpg_sales_tracking_id');
		$old_lead= DB::table('gpg_sales_tracking')->where('id','=',$leadId)->select('*')->get();
		$old_lead_ignore = array('id'=>'','status'=>'Quote','created_on'=>'NOW()','modified_on'=>'NOW()','parent_id'=>$leadId);
		$str_query_lead = array();
		$old_lead_arr = array();
		foreach ($old_lead as $key => $value) {
			$old_lead_arr = (array)$value;
		}
		foreach($old_lead_arr as $k => $v){
			if(array_key_exists($k,$old_lead_ignore)){
				if($k!='id')
					$str_query_lead += array($k=>$old_lead_ignore[$k]);
			}
			else{
				$str_query_lead += array($k=>$v);
			}
		}
		DB::table('gpg_sales_tracking')->insert($str_query_lead);
		$new_lead_id = DB::table('gpg_sales_tracking')->max('id');
		$old_lead_ignore['gpg_sales_tracking_id'] = $new_lead_id;
		$old_lead_contact_info_res = DB::select(DB::raw("SELECT * FROM gpg_sales_tracking_contact WHERE gpg_sales_tracking_id = '".$leadId."'"));		
		if(count($old_lead_contact_info_res)>0)
		{
			foreach ($old_lead_contact_info_res as $key => $old_lead_contact_info)
			{
				$str_query_lead = array();
				foreach($old_lead_contact_info as $k => $v){
					if(array_key_exists($k,$old_lead_ignore)){
						if($k!='id')
							$str_query_lead += array($k=>$old_lead_ignore[$k]);
					}
					else{
						$str_query_lead += array($k=>$v);
					}
				}
				DB::table('gpg_sales_tracking_quote_invoice')->insert($str_query_lead);
			}
			
		}
		$ignore = array("id","job_num","created_on", "modified_on","annual_charges","pm_charges","show_lump_sum","exclude_from_contract");
		$getMaxJobNum = '';
		$getMaxJobNumQ = DB::select(DB::raw("SELECT MAX(SUBSTR(job_num,2,5))+1 as job_num FROM gpg_consum_contract"));
		if (!empty($getMaxJobNumQ) && isset($getMaxJobNumQ[0]->job_num))
			$getMaxJobNum = $getMaxJobNumQ[0]->job_num;
		$newJobNum = explode(":",$getMaxJobNum);
		$contractNum = "C".$getMaxJobNum;
		$all_old_contracts = DB::select(DB::raw("SELECT * FROM gpg_consum_contract WHERE job_num LIKE '".$jobNum[0]."%' ORDER BY job_num"));
		///////////////////*************//////////////////
		$all_old_contracts_arr = array();
		foreach ($all_old_contracts as $key => $value) {
			$all_old_contracts_arr = (array)$value;
		}
		$insertQuery = array();
		foreach ($all_old_contracts_arr as $key => $getConsumContract)
		{
			$getMaxConsumContractId = DB::table('gpg_consum_contract')->max('id');
			$contractNum = "C".$getMaxJobNum;
			if(isset($getConsumContract['job_num']) && preg_match("/:/",$getConsumContract['job_num']))
			{
				$sub = explode(":",$getConsumContract['job_num']);
				$contractNum = "C".$getMaxJobNum.":".$sub[1];
			}
			$consumContractColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract"));
			foreach ($consumContractColsRs as $key => $consumContractColsRow)
			{
				    if (!in_array($consumContractColsRow->Field,$ignore)) 
					{
						if(($consumContractColsRow->Field=='consum_contract_start_date' 
											or $consumContractColsRow->Field=='consum_contract_end_date' 
											or $consumContractColsRow->Field=='consum_contract_status'))
						{
							if($consumContractColsRow->Field=='consum_contract_status')
								$insertQuery += array($consumContractColsRow->Field=>'Quote');
							if($consumContractColsRow->Field=='consum_contract_start_date')
							{
								$insertQuery += array($consumContractColsRow->Field=>(!empty($getConsumContract['consum_contract_end_date'])?"'".addslashes($getConsumContract['consum_contract_end_date'])."'":"NULL"));
							}
							elseif($consumContractColsRow->Field=='consum_contract_end_date')
							{
								$end_date = "NULL";
								if(!empty($getConsumContract['consum_contract_end_date']))
								{
									$end_date = "'".date('Y-m-d',strtotime($getConsumContract['consum_contract_end_date']." +1 year"))."'";
								}
								$insertQuery += array($consumContractColsRow->Field=>($end_date));
							}
						}
						else
							$insertQuery += array($consumContractColsRow->Field=>(!empty($getConsumContract[$consumContractColsRow->Field])?"'".addslashes($getConsumContract[$consumContractColsRow->Field])."'":"NULL"));
					}
			}
			$insertQuery2 = DB::table('gpg_consum_contract')->insert($insertQuery+array('job_num'=> $contractNum, 'created_on'=>date('Y-m-d'), 'modified_on'=>date('Y-m-d'))); 
			if($insertQuery2)
			{
				$cont_id = DB::table('gpg_consum_contract')->max('id');			
				DB::table('gpg_sales_tracking_consum_contract')->insert(array('gpg_consum_contract_id'=>$cont_id, 'gpg_sales_tracking_id'=>$new_lead_id));			
			 	$flag = 1;
				$ignore1 = array("id", "gpg_consum_contract_id", "created_on", "modified_on");
				$ignore2 = array("id", "gpg_consum_contract_schedule_id","month","year","service1","price1","service2","price2","service3","price3","installment", "created_on", "modified_on");
			    ///// EQUIPMENT		
				$getConsumContractEqp = DB::select(DB::raw("select * from gpg_consum_contract_equipment where gpg_consum_contract_id = '$consum_contract_id'"));
				foreach ($getConsumContractEqp as $key => $eqpRow)
				{
					$eqpRow = (array)$eqpRow;
					$eqpColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_equipment"));
					$insertEqpQuery = array();
					foreach ($eqpColsRs as $key => $eqpColsRow)
					{
						if (!in_array($eqpColsRow->Field,$ignore1)) 
						  	$insertEqpQuery += array($eqpColsRow->Field=>addslashes($eqpRow[$eqpColsRow->Field]));
					}
					$insertEqpQuery2 = DB::table('gpg_consum_contract_equipment')->insert($insertEqpQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				}
				///// ATS		
				$getConsumContractAts = DB::select(DB::raw("select * from gpg_consum_contract_ats where gpg_consum_contract_id = '$consum_contract_id'"));
				foreach ($getConsumContractAts as $key => $atsRow)
				{
					$atsRow = (array)$atsRow;
					$atsColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_ats"));
					$insertAtsQuery = array();
					foreach ($atsColsRs as $key => $atsColsRow)
					{
						if (!in_array($atsColsRow->Field,$ignore1))
							$insertAtsQuery += array($atsColsRow->Field=>addslashes($atsRow[$atsColsRow->Field]));
					}
					$insertAtsQuery2 = DB::table('gpg_consum_contract_ats')->insert($insertAtsQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId, 'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				}
				///// SCHEDULE		
				$getConsumContractSched = DB::select(DB::raw("select * from gpg_consum_contract_schedule where gpg_consum_contract_id = '$consum_contract_id'"));
				$serviceColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_schedule_service"));
				foreach ($getConsumContractSched as $key => $schedRow)
				{
					$schedRow = (array)$schedRow;
					$schedColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_schedule"));
					$insertSchedQuery = array();
					foreach ($schedColsRs as $key => $schedColsRow)
					{
						  if (!in_array($schedColsRow->Field,$ignore1))
						  	$insertSchedQuery += array($schedColsRow->Field=>addslashes($schedRow[$schedColsRow->Field]));
					}
					$insertSchedQuery2 = DB::table('gpg_consum_contract_schedule')->insert($insertSchedQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
					$dup_schedule_service_id = DB::table('gpg_consum_contract_schedule')->max('id'); 
					$service_rs = DB::select(DB::raw("SELECT * FROM gpg_consum_contract_schedule_service WHERE gpg_consum_contract_schedule_id = ".$schedRow['id']." "));
					foreach ($service_rs as $key => $service_rs_row){
						$service_rs_row = (array)$service_rs_row;	
						$insertServiceQuery = array();
						foreach ($serviceColsRs as $key => $serviceColsRow)
						{
						    if (!in_array($serviceColsRow->Field,$ignore2))
								  	$insertServiceQuery+= array($serviceColsRow->Field=>addslashes($service_rs_row[$serviceColsRow->Field]));
						}
						$insertServiceQuery2 = DB::table('gpg_consum_contract_schedule_service')->insert($insertServiceQuery+array('gpg_consum_contract_schedule_id'=> $dup_schedule_service_id));
					}
				}
				///// MATERIAL		
				$getConsumContractMaterial = DB::select(DB::raw("select * from gpg_consum_contract_material where gpg_consum_contract_id = '$consum_contract_id'"));
				foreach ($getConsumContractMaterial as $key => $materialRow)
				{
					$materialRow = (array)$materialRow;
					$materialColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_material"));
					$insertMaterialQuery = array();
					foreach ($materialColsRs as $key => $materialColsRow){
						  if (!in_array($materialColsRow->Field,$ignore1))
						  	$insertMaterialQuery+= array($materialColsRow->Field=>addslashes($materialRow[$materialColsRow->Field]));
					}
					$insertMaterialQuery2 = DB::table('gpg_consum_contract_material')->insert($insertMaterialQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d'))); 
				}
				///// COMPONENT		
				$getConsumContractComponent = DB::select(DB::raw("select * from gpg_consum_contract_component where gpg_consum_contract_id = '$consum_contract_id'"));
				foreach ($getConsumContractComponent as $key => $componentRow)
				{
					$componentRow = (array)$componentRow;
					$componentColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_component"));
					$insertComponentQuery = array();
					foreach ($componentColsRs as $key => $componentColsRow)
					{
						if (!in_array($componentColsRow->Field,$ignore1))
							$insertComponentQuery += array($componentColsRow->Field=>addslashes($componentRow[$componentColsRow->Field]));
					}
					$insertComponentQuery2 = DB::table('gpg_consum_contract_component')->insert($insertComponentQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				}
				///// LABOR		
				$getConsumContractLabor = DB::select(DB::raw("select * from gpg_consum_contract_labor where gpg_consum_contract_id = '$consum_contract_id'"));
				foreach ($getConsumContractLabor as $key => $laborRow)
				{
					$laborRow = (array)$laborRow;
					$laborColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_labor"));
					$insertLaborQuery = '';
					foreach ($laborColsRs as $key => $laborColsRow)
					{
						if (!in_array($laborColsRow->Field,$ignore1))
						  	$insertLaborQuery+= array($laborColsRow->Field=>addslashes($laborRow[$laborColsRow->Field]));
					}
					DB::table('gpg_consum_contract_labor')->insert($insertLaborQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				}
				///// OTHER		
				$getConsumContractOther = DB::select(DB::raw("select * from gpg_consum_contract_other where gpg_consum_contract_id = '$consum_contract_id'"));
				foreach ($getConsumContractOther as $key => $otherRow)
				{
					$otherRow = (array)$otherRow;
					$otherColsRs = DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_other"));
					$insertOtherQuery = array();
					while ($otherColsRow = mysql_fetch_array($otherColsRs)) {
						if (!in_array($otherColsRow->Field,$ignore1))
							$insertOtherQuery+= array($otherColsRow->Field=>addslashes($otherRow[$otherColsRow->Field]));
					}
					DB::table('gpg_consum_contract_other')->insert($insertOtherQuery+array('gpg_consum_contract_id'=>$getMaxConsumContractId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));	 
				}
				//////////////////////////////				
				$contractInfo = array();
				$contractInfoQ = DB::select(DB::raw("select id,mat_cost_total,comp_cost_total,grand_list_total,labor_cost_total from gpg_consum_contract where job_num = '".$contractNum."'"));
				foreach ($contractInfoQ as $key => $value) {
					$contractInfo = (array)$value;
				}
				$totalsCost['mat_cost_total'] = $contractInfo['mat_cost_total'];
			    $totalsCost['comp_cost_total'] = $contractInfo['comp_cost_total'];
				$totalsCost['grand_list_total'] = $contractInfo['grand_list_total'];
				$totalsCost['labor_cost_total'] = $contractInfo['labor_cost_total'];
				$contractLeadId = DB::table('gpg_sales_tracking_consum_contract')->where('gpg_consum_contract_id','=',$contractInfo['id'])->pluck('gpg_sales_tracking_id');
				$typeofSale = DB::table('gpg_sales_tracking')->where('id','=',$contractLeadId)->pluck('type_of_sale');
				if($typeofSale=="PMcontract"){
					$costRs = DB::select(DB::raw("select id,mat_cost_total,comp_cost_total,grand_list_total,labor_cost_total from gpg_consum_contract where job_num like '".$contractNum.":%'"));
					foreach ($costRs as $key => $costRow)
					{
	 				  $costRow = (array)$costRow;					  	
					  $totalsCost['mat_cost_total'] += $costRow['mat_cost_total'];
					  $totalsCost['comp_cost_total'] += $costRow['comp_cost_total'];
					  $totalsCost['grand_list_total'] += $costRow['grand_list_total'];
					  $totalsCost['labor_cost_total'] += $costRow['labor_cost_total'];
			   		}
			   	    $salesTrackingQuery = DB::table('gpg_sales_tracking')->where('id','=',$contractLeadId)->update(array('projected_sale_price'=>$totalsCost['grand_list_total'], 'material_cost'=>($totalsCost['mat_cost_total'] + $totalsCost['comp_cost_total']), 'labor_cost'=>$totalsCost['labor_cost_total'], 'modified_on'=>date('Y-m-d'))); 
				}
				$qry_load_bank = DB::select(DB::raw("SELECT * FROM gpg_consum_contract_matrix_load_bank WHERE gpg_consum_contract_id = '".$consum_contract_id."' ORDER BY id"));
				foreach ($qry_load_bank as $key => $rrow)
				{
					$qry = array();
					foreach($rrow as $k => $v)
					{
						if($k!= 'gpg_consum_contract_id' and $k!= 'id' and $k!= 'created_on' and $k!= 'modified_on')
						{
							$qry += array($k=>$v);
						}
					}
					DB::table('gpg_consum_contract_matrix_load_bank')->insert($qry+array('gpg_consum_contract_id'=>$getMaxConsumContractId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
				}
			}
		}
			return 1;
	}
	/*
	* getConsumerContractFile
	*/
	public function getConsumerContractFile($consum_contract_id){
		
		$record = DB::select(DB::raw("select * from gpg_consum_contract where id = '$consum_contract_id'"));
		$consumContractTblRow = array();
		foreach ($record as $key => $value) {
			$consumContractTblRow = (array)$value;
		}
		$consumContractTblRow['schedule_date'];
		$date = ($consumContractTblRow['date_job_won']==''?date('Y-m-d',strtotime($consumContractTblRow['schedule_date'])):date(DATE_FORMAT,strtotime($consumContractTblRow['date_job_won'])));
		// Sales Person Data
		$estData = array();
		$q1 = DB::table('gpg_employee')->select('*')->where('id','=',$consumContractTblRow['GPG_employee_id'])->get();
		foreach ($q1 as $key => $value) {
			$estData = (array)$value;
		}
		// Sales Person Data
		$custData = array();
		$q2 = DB::table('gpg_customer')->select('*')->where('id','=',$consumContractTblRow['GPG_customer_id'])->get();
		foreach ($q2 as $key => $value) {
			$custData = (array)$value;
		}
		// equipment data
		$eqpData = array();
		$q3 = DB::table('gpg_consum_contract_equipment')->select('*')->where('gpg_consum_contract_id','=',$consum_contract_id)->get();
		foreach ($q3 as $key => $value) {
			$eqpData = (array)$value;
		}
		$billFirstName = $consumContractTblRow['pri_contact_name'];
		if($billFirstName=="")
			$billFirstName = "Sir";
		$billLastName = "";
		$jobName = $consumContractTblRow['pri_contact_name'];
		$customerTblRow = array();
		$q4 = DB::select(DB::raw("select * from gpg_customer where status = 'A' and id = '".$consumContractTblRow['GPG_customer_id']."'"));
		foreach ($q4 as $key => $value) {
			$customerTblRow = (array)$value;
		}
		$billCompany = $customerTblRow['name'];
		$billCoAddress = $customerTblRow['address'];
		$billCoAddress2 = $customerTblRow['address2'];
		$billCoCity = $customerTblRow['city'];
		$billCoState = $customerTblRow['state'];
		$billCoZip = $customerTblRow['zipcode'];
		if(isset($customerTblRow['phone']))
			$billPhone = $customerTblRow['phone'];
		else
			$billPhone ='';
		$jobCompnay = $billCompany;
		$jobPhone =  $consumContractTblRow['pri_contact_phone'];
		$frequency = "Frequency";
		$jobAdress = $consumContractTblRow['address1']." ".$consumContractTblRow['address2'];
		$jobCity = $consumContractTblRow['city'];
		$jobState = $consumContractTblRow['state'];
		$jobZip = $consumContractTblRow['zip'];
		$genBrand = $consumContractTblRow['gen_make'];
		$genMod = $consumContractTblRow['gen_model'];
		$genSer = $consumContractTblRow['gen_serial'];
		$genSpec = $consumContractTblRow['gen_spec'];
		$price = '';
		$priceQ = DB::select(DB::raw("select sum(ifnull(list_price,0)*ifnull(quantity,0)) as list_price from gpg_consum_contract_material where gpg_consum_contract_id = '".$consum_contract_id."' order by created_on desc "));
		if (!empty($priceQ) && isset($priceQ[0]->list_price))
			$price = $priceQ[0]->list_price;
		$newParts = "";
		$terms_and_conditions = DB::table('gpg_settings')->where('name','LIKE','terms')->pluck('value');
		$address = DB::table('gpg_settings')->where('name','LIKE','address')->pluck('value');
		$NewPageGroup = '';   // variable indicating whether a new group was requested
		$PageGroups = '';     // variable containing the number of pages of the groups
		$CurrPageGroup = '';
		$pdf=new Fpdf();
		$pdf->SetMargins(11, 5, 11, 0);
		$this->StartPageGroup();
		$pdf->AddPage();
		$pdf->SetFont('Times','',12);
		$pdf->Image(storage_path('page_bg.jpg'), 8, 5, 193, 280); 
		$cellWid = 113;
		$cellHig = 35;
	    $pdf->Cell(80);
		$pdf->Image(storage_path('big_logo.jpg'),14,22,70);
		$cellWid = 113;
		$cellHig = 35;
	    $pdf->Cell(80);
		$pdf->Image(storage_path('contract_img3.gif'),20,198,60);
		$cellWid = 113;
		$cellHig = 35;
	    $pdf->Cell(80);
		$pdf->Image(storage_path('contract_img2.gif'),20,158,60);
		$cellWid = 113;
		$cellHig = 35;
	    $pdf->Cell(80);
		$pdf->Image(storage_path('contract_img1.gif'),20,118,60);
		$pdf->Ln(112);
	    $pdf->Cell(80);
		$pdf->SetFont('Times','',24);
		$pdf->SetTextColor(57,167,235); 
	    $pdf->MultiCell(0,10,ucwords($jobCompnay)."",5,'C');
		$pdf->Ln(1);
	    $pdf->Cell(80);
		$pdf->SetFont('Times','',16);
		$pdf->MultiCell(0,5,"Planned Maintenance Agreement Proposal"."",7,'C');
		$pdf->SetTextColor(0,0,0);
		$pdf->SetX(60);
		$pdf->SetY(266);
		$pdf->Cell(45);
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(120,5,"Global Power Group, Inc. ".str_replace(""," ",$address)."866-54-POWER www.globalpowergroup.net",5,'C');
		$this->StartPageGroup();
		$pdf->AddPage();
		$pdf->SetTextColor(0,0,0); 
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','',20);
		$pdf->SetTextColor(57,167,235); 
		$pdf->MultiCell(0,5,"Generator Service Maintenance Agreement",0,'C');
		$pdf->SetFont('Times','',12);
		$pdf->SetTextColor(0,0,0); 
		$pdf->MultiCell(0,5,$jobCompnay,0,'C');
		$pdf->MultiCell(0,5,'',0,'L');
		$pdf->MultiCell(0,5,"Dear ".$billFirstName.",",0,'L');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','',12);
		$text1 = "Thank you for allowing Global Power Group Inc. (GPG) the opportunity to provide this proposal for the preventive maintenance at your facilities. GPG offers a more interactive experience to those offered by other companies.  Our goal is to provide a premium level of service by following the standards established by all major generator and equipment manufacturers.";
		$pdf->MultiCell(0,5,$text1,0,'J');
		$pdf->MultiCell(0,5,"",0,'J');
		$text10="As a GPG contract customer, you and your staff will have access to the most current reports and selected history of your emergency power systems via a secured Web Based Service Management System.  All you need is an Internet connection.";
		$pdf->MultiCell(0,5,$text10,0,'J');
		$pdf->MultiCell(0,5,"",0,'J');
		$text11 = "By working with GPG, you will enjoy:";
		$pdf->MultiCell(0,5,$text11,0,'J');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(0,5," •	24 hour service 7 Days a week",0,'J');
		$pdf->MultiCell(0,5," •	Fuel delivery of #2 EPA diesel with our own certified fuel truck",0,'J');
		$pdf->MultiCell(0,5," •	Access to other services, we hold a current A, B and C-10 Electrical Contractors License.  We can be your One-Stop Shop!",0,'J');
		$pdf->MultiCell(0,5," •	Assistance with Engine Emissions information and APCD Registration.",0,'J');
		$pdf->MultiCell(0,5," •	A drug free workplace. We are DOT certified.",0,'J');
		$pdf->MultiCell(0,5," •	Factory Trained Generator Service Engineers.",0,'J');
		$pdf->MultiCell(0,5," •	Properly covered with the right insurance for your protection. We carry $11 million in Liability Insurance –including Pollution.",0,'J');
		$pdf->MultiCell(0,5," •	Our engineers are Hazmat Certified to legally haul specified amounts of hazardous wastes.",0,'J');
		$pdf->MultiCell(0,5," •	We own fully equipped late model service trucks ready to respond at a moments notice",0,'J');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','',12);
		$text2 = "We will work with your staff to keep your Emergency Power System operating as safely and reliably as possible.  In addition, we will constantly keep you abreast of any changes in local environmental and safety codes and potential problems that could jeopardize the reliability of your equipment.";
		$pdf->MultiCell(0,5,$text2,0,'J');
		$pdf->MultiCell(0,5,"",0,'J');
		$text3 = "Please read the contract completely. Should the terms of this agreement meet with your approval, please sign a copy, and return it to ".str_replace(""," ",$address).". If you have any questions about this proposal, please give me a call to discuss them.";
		$pdf->MultiCell(0,5,$text3,0,'J');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(0,5,"Sincerely,",0,'L');
		$pdf->MultiCell(0,5,"",0,'J');
		if(isset($estData['name']) && $estData['name']!=''){
			$pdf->MultiCell(0,5,$estData['name'],0,'L');
    	}
		$pdf->MultiCell(0,5,"Service Contracts Manager",0,'L');
		if(isset($estData['email']) && $estData['email']!=''){
			$pdf->Ln(1);
		$pdf->MultiCell(180,5,$estData['email']);
		}
		if(isset($estData['phone']) && $estData['phone']!=''){
			$pdf->Ln(1);
			$pdf->MultiCell(180,5,"Phone: ".$estData['phone']);
		}
		$c_num = $consumContractTblRow['job_num'];
		if(strpos($c_num,":")>0)
		{
			$c_num =substr($c_num,0,strpos($c_num,":"));
		}
			$all_contracts_data = DB::select(DB::raw("SELECT
								eqp.kw,
								eqp.model,
								eqp.make,
								contract.id as contract_id,
								contract.job_num,
								contract.location,
								contract.pm_adjust,
								contract.pm_visits,
								contract.annual_adjust,
								contract.annual_visits,
								contract.pm_charges,
								contract.annual_charges,
								contract.job_num,
								contract.contract_notes
							FROM gpg_consum_contract contract,
								gpg_consum_contract_equipment eqp
							WHERE contract.job_num LIKE '".$c_num."%'
								AND contract.id = eqp.gpg_consum_contract_id
								AND contract.annual_visits IS NOT NULL
								AND eqp.kw IS NOT NULL
								AND contract.exclude_from_contract = 0
							GROUP BY job_num"));
		if (count($all_contracts_data)>0) {
			$pdf->AddPage();
			$pdf->SetFont('Times','',20);
			$pdf->SetTextColor(57,167,235); 
			$pdf->MultiCell(0,5,"Pricing",0,'C');
			$pdf->SetFont('Times','',12);
			$pdf->SetTextColor(0,0,0); 
			$long_date = date("M d, Y",strtotime($date));
			$pdf->MultiCell(0,5,$long_date,0,'J');
			$pdf->MultiCell(0,5,"",0,'J');
			$pdf->Cell(100,5,"Sold To:",0,'J');
			$pdf->Ln();
			$pdf->Cell(100,5,ucwords($jobCompnay),0,'J');
			$pdf->Ln();
			$pdf->Cell(100,5,$custData['address'],0,'J');
			$pdf->Ln();
			if($custData['address2'] || $eqpData['address2'] ){
				$pdf->Cell(100,5,$custData['address2'],0,'J');
				$pdf->Ln();
			}
			$dat = "";
			if($custData['city'] || $custData['state'] || $custData['zipcode']){
				if($custData['city'])
					$dat = $custData['city'].", ";
				if($custData['state'])
					$dat .= $custData['state']." ";
				if($custData['zipcode'])	
					$dat .= $custData['zipcode'];
			}
			$pdf->Cell(100,5,$dat,0,'J');
			$dat = "";
			$pdf->Ln();
			if($consumContractTblRow['pri_contact_phone'])
			{
				$pdf->Cell(100,5,"Phone: ".$consumContractTblRow['pri_contact_phone'],0,'J');
				$pdf->Ln();
			}
			$pdf->Ln();
			$pdf->MultiCell(0,5,"RE: Maintenance Agreement Proposal",0,'J');
			$pdf->SetFont('Times','',9);
			$pdf->Ln();
			$pdf->Cell(20,5,"Make",1,0,'C');
			$pdf->Cell(20,5,"Model",1,0,'C');
			$pdf->Cell(60,5,"Service Type",1,0,'C');
			$pdf->Cell(20,5,"Location",1,0,'C');
			$pdf->Cell(15,5,"QTY",1,0,'C');
			$pdf->Cell(25,5,"Price",1,0,'C');
			$pdf->Cell(25,5,"Extended",1,0,'C');
			$celheight = 6;
			$pdf->SetFont('Times','',9);
			$grand_total = 0;
			$grand_total_loadbank = 0;
			$page_lines = 0;
			$all_contracts_data_arr = array();
			foreach ($all_contracts_data as $key => $value) {
				$all_contracts_data_arr = (array)$value;
			}
			foreach ($all_contracts_data_arr as $key => $arr_contract_data){
				$sr_no = 1;
				$eqp_arr = array();
				$eqp_qry = DB::select(DB::raw("SELECT location,engine_level,address1,serial FROM gpg_consum_contract_equipment WHERE gpg_consum_contract_id = '".$arr_contract_data['contract_id']."'"));
				foreach ($eqp_qry as $key => $value) {
					$eqp_arr = (array)$value;
				}
				$contract_location = $eqp_arr['location'];
				$engine_level = $eqp_arr['engine_level'];
				$eqp_address = $eqp_arr['address1'];
				$eng_serial = $eqp_arr['serial'];
				$subtotal = 0;
				$pm_visits = $arr_contract_data['pm_visits']?$arr_contract_data['pm_visits']:'0';
				$adjusted_pm = $arr_contract_data['pm_charges']+$arr_contract_data['pm_adjust'];
				$pm_price = $pm_visits*$adjusted_pm;
				$subtotal += $pm_price;
				$annual_visits = $arr_contract_data['annual_visits']?$arr_contract_data['annual_visits']:'1';
				$adjusted_annual = $arr_contract_data['annual_charges']+$arr_contract_data['annual_adjust'];
				$annual_price = $annual_visits*($adjusted_annual);
				$subtotal += $annual_price;
				$pdf->Ln(); //1
				$page_lines++;
				$pdf->SetFont('Times','B',9);
				$pdf->MultiCell(185,5,$contract_location." - ".($eng_serial?$eng_serial." - ":"").$arr_contract_data['job_num'].($eqp_address?" - ".$eqp_address:""),0,'C');
				$pdf->SetFont('Times','',9);
				$pdf->Cell(20,$celheight,$sr_no.". ".$arr_contract_data['make'],0,'C');
				$sr_no++;
				$pdf->Cell(20,$celheight,$arr_contract_data['model'],0,'C');
				$pdf->Cell(60,$celheight,$arr_contract_data['kw']."Kw Inspection Service (PM Level-I)",0,'c');
				$pdf->Cell(20,$celheight,$engine_level?$engine_level:"-",0,0,'C');
				$pdf->Cell(15,$celheight,$pm_visits,0,0,'C');
				$pdf->Cell(25,$celheight,'$'.$adjusted_pm,0,0,'R');
				$pdf->Cell(25,$celheight,'$'.number_format($pm_price,2),0,0,'R');
				$pdf->Ln();//2
				$page_lines++;
				$pdf->Cell(20,$celheight,$sr_no.". ".$arr_contract_data['make'],0,0,'L');
				$sr_no++;
				$pdf->Cell(20,$celheight,$arr_contract_data['model'],0,0,'L');
				$pdf->Cell(60,$celheight,$arr_contract_data['kw']."Kw Annual Service (Level-II)",0,0,'L');
				$pdf->Cell(20,$celheight,$engine_level?$engine_level:"-",0,0,'C');
				$pdf->Cell(15,$celheight,$annual_visits,0,0,'C');
				$pdf->Cell(25,$celheight,'$'.$adjusted_annual,0,0,'R');
				$pdf->Cell(25,$celheight,'$'.number_format($annual_price,2),0,0,'R');
				$pdf->Ln();//3
				$page_lines++;
				if($arr_contract_data['contract_notes'])
				{
					$notes_len = round(strlen($arr_contract_data['contract_notes'])/152,0);
					$pdf->MultiCell(0,5,$arr_contract_data['contract_notes'],0,'J');
					$page_lines+=$notes_len;
				}
				// displaying load bank charges
				$lodqury = DB::select(DB::raw("SELECT * FROM gpg_consum_contract_matrix_load_bank WHERE gpg_consum_contract_id = '".$arr_contract_data['contract_id']."' ORDER BY id"));
				$load_bank_res = array();
				foreach ($lodqury as $key => $value) {
					$load_bank_res = (array)$value;
				}
				$total_lb = 0;
				$load_bank_type = "";
				foreach ($load_bank_res as $key => $lb_arr)
				{
					if($lb_arr['subtract']!=1)
					{
						$total_lb = $lb_arr['eqp_qty'] * $lb_arr['eqp_rate'] ;
						if($lb_arr['labor_qty'])
							$total_lb += $lb_arr['labor_qty'] * $lb_arr['labor_rate'] ;
						
							//$total_lb *= -1;
						if(Input::get('lump_sum_service')==1)
						{
							$grand_total += $total_lb;
							$grand_total_loadbank += $total_lb;
						}
						else
						{
							$pdf->Cell(20,$celheight,$sr_no.". ".$arr_contract_data['make'],0,0,'L');
							$sr_no++;
							$pdf->Cell(20,$celheight,$arr_contract_data['model'],0,0,'L');
							$pdf->Cell(60,$celheight,$lb_arr['type'],0,0,'L');
							$pdf->Cell(20,$celheight,"",0,'C');
							$pdf->Cell(15,$celheight,$lb_arr['total_qty'],0,0,'C');
							$pdf->Cell(25,$celheight,"",0,0,'R');
							$pdf->Cell(25,$celheight,'$'.number_format($total_lb,2),0,0,'R');
							$pdf->Ln();
							$page_lines++;
							if($lb_arr['notes'])
							{
								$notes_len = round(strlen($lb_arr['notes'])/152,0);
								$pdf->MultiCell(0,5,$lb_arr['notes'],0,'J');
								$page_lines+=$notes_len;
							}
							$subtotal += $total_lb;
						}
					}
				}
				
				$pdf->Cell(160,5,"Subtotal  ",0,0,'R');
				$pdf->Cell(25,5,'$'.number_format($subtotal,2),'T',0,'R');
				$grand_total += $subtotal;
				if($pdf->GetY()>222)
				{
					$pdf->AddPage();
					$page_lines=0;
				}

				if(Input::get('lump_sum_service')==1)
				{
					$pdf->Ln();
					$pdf->Cell(135,5,"",0,0,'R');
					$pdf->Cell(25,5,"Load bank Charges  ",0,0,'R');
					$pdf->Cell(25,5,'$'.number_format($grand_total_loadbank ,2),'T',0,'R');
				}
				$pdf->Ln();
				$pdf->Cell(135,5,"",0,0,'R');
				$pdf->Cell(25,5,"Annual Amount  ",'T',0,'R');
				$pdf->Cell(25,5,'$'.number_format($grand_total ,2),'T',0,'R');
				$pdf->Ln();
				$pdf->Ln();
				$pdf->Cell(160,5,"PM billing rate per visit / ".$consumContractTblRow['visits_per_year'],0,0,'R');
				if($consumContractTblRow['visits_per_year']>0)
					$billing_rate = '$'.number_format($grand_total/$consumContractTblRow['visits_per_year'],2);
				else
					$billing_rate = '$'.number_format($grand_total,2);
				$pdf->Cell(25,5,$billing_rate,0,0,'R');
				$pdf->SetY(243);
				$pdf->MultiCell(0,5,"Accepted By:",0,'J');
				$pdf->SetFont('Times','I',10);
				$pdf->MultiCell(0,5,'',0,'J');
				$pdf->MultiCell(0,5,"Signature____________________________  Print Full Name _______________________________",0,'J');
				$pdf->MultiCell(0,3,'',0,'J');
				$pdf->MultiCell(0,5,"on this ______ day of _____________________  20____     PO#  _____________________________",0,'J');			$pdf->Ln();
				$pdf->MultiCell(0,5,"Please review the terms and condition section in the back of this proposal ",0,'C');
			}//end foreach
		}//end if count
		$pdf->AddPage();
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','BI',13);
		$pdf->SetFont('Times','',20);
		$pdf->SetTextColor(57,167,235); 
		$pdf->MultiCell(0,5,"Inspections",0,'C');	
		$pdf->SetFont('Times','',13);
		$pdf->SetTextColor(0,0,0); 
		$pdf->MultiCell(0,7,ucwords($jobCompnay),0,'C');	
		$pdf->MultiCell(0,7,"Scheduled visits 8am-5pm Monday-Friday excluding Holidays",0,'C');	
		$pdf->Ln();
		$pdf->SetFont('Times','BU',10);
		$pdf->Cell(90,5,"Enclosure",0,'L');
		$pdf->Cell(90,5,"Switchgear",0,'L');
		$pdf->SetFont('Times','',10);
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Inspect installation for obstructions or debris");
		$pdf->Cell(90,5,"•	Automatic transfer switches will be visually inspected");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Inspect enclosure for corrosion or openings");
		$pdf->Cell(90,5,"•	Panel lights and displays will be checked");
		$pdf->Ln();	$pdf->SetFont('Times','BU',10);
		$pdf->Ln();
		$pdf->Cell(90,5,"Batteries ");
		$pdf->Cell(90,5,"Operational Generator Checks");
		$pdf->SetFont('Times','',10);
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Starting batteries will be cleaned");
		$pdf->Cell(90,5,"•	GPG Service Engineer will enter running time meter");	  
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Electrolyte levels and specific gravity will be checked");
		$pdf->Cell(90,5,"start time in logbook ");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Reports made for recharging or replacing");
		$pdf->Cell(90,5,"•	Generator breaker will be opened");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Battery charging system will be checked for proper operation");
		$pdf->Cell(90,5,"•	Generator set will be started");
		$pdf->Ln();
		$pdf->Cell(90,5,"");	
		$pdf->Cell(90,5,"•	Building load will be applied to generator for ");	
		$pdf->Ln();
		$pdf->SetFont('Times','BU',10);
		$pdf->Cell(90,5,"Fuel System");
		$pdf->SetFont('Times','',10);
		$pdf->Cell(90,5,"30 minutes upon authorization");	
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Above ground diesel fuel tanks and lines will be");
		$pdf->Cell(90,5,"•	Engine gauges will be checked for proper operation");	
		$pdf->Ln();
		$pdf->Cell(90,5," inspected for defects.");
		$pdf->Cell(90,5,"•	Equipment will be checked for abnormal speed, operation, vibration,");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Critical fuel levels will be noted, authorization for top ");
		$pdf->Cell(90,5,"leaks and noises");
		$pdf->Ln();
		$pdf->Cell(90,5,"off requested (Refueling will be recommended when");
		$pdf->Cell(90,5,"•	Engine and control gauges will be observed for proper operation");
		$pdf->Ln();
		$pdf->Cell(90,5,"fuel is below 90%)");
		$pdf->Cell(90,5,"•	Engine water pump will be checked for leaks and signs of wear");
		$pdf->Ln();
		$pdf->Cell(90,5,"");
		$pdf->Cell(90,5,"•	Check voltage and frequency outputs");
		$pdf->Ln();
		$pdf->SetFont('Times','BU',10);
		$pdf->Cell(90,5,"Engine");
		$pdf->SetFont('Times','',10);
		$pdf->Cell(90,5,"•	Electrical and mechanical shutdowns will be tested");
		$pdf->Ln();	
		$pdf->Cell(90,5,"•	Check for fuel, oil or coolant leaks, tighten all bolts");
		$pdf->Cell(90,5,"•	Perform building load test to test the emergency circuit ");
		$pdf->Ln();
		$pdf->Cell(90,5," as necessary");
		$pdf->Cell(90,5,"if authorized by owner / operator");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	All fluid levels will be checked and topped-off as");
		$pdf->Cell(90,5,"•	All readings will be recorded on work order");
		$pdf->Ln();
		$pdf->Cell(90,5,"necessary. (Fuel not included)");
		$pdf->Cell(90,5,"•	Generator will be shut down, generator run time log entry made");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Governor system and linkage will be checked for");
		$pdf->Ln();
		$pdf->Cell(90,5,"binding and proper operation");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Air cleaners will be checked and if necessary");
		$pdf->SetFont('Times','BU',10);
		$pdf->Cell(90,5,"Annual Maintenance Services (Once per year)     ");
		$pdf->SetFont('Times','',10);
		$pdf->Ln();
		$pdf->Cell(90,5,"recommendations made for replacement.");
		$pdf->Cell(90,5,"•	Perform the inspections noted above");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Engine block heater(s) and associated plumbing");
		$pdf->Cell(90,5,"•	Change engine lubricating oil and oil filters");
		$pdf->Ln();
		$pdf->Cell(90,5,"will be checked for proper operation.");
		$pdf->Cell(90,5,"•	Change engine fuel filters");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	All belts will be checked for proper tension,");
		$pdf->Cell(90,5,"•	Check air filter elements and, if necessary, make");
		$pdf->Ln();
		$pdf->Cell(90,5,"signs of age and wear");
		$pdf->Cell(90,5," recommendations for replacement");
		$pdf->Ln();
		$pdf->Cell(90,5,"");
		$pdf->Cell(90,5,"•	Change water filters and coolant conditioners when used");
		$pdf->Ln();
		$pdf->SetFont('Times','BU',10);
		$pdf->Cell(90,5,"Cooling System");$pdf->SetFont('Times','',10);
		$pdf->Cell(90,5,"•	Obtain oil sample for analysis by fluid testing laboratories");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Dispose of hazardous wastes from our maintenance service");
		$pdf->Cell(90,5,"•	Obtain coolant sample for analysis by fluid testing laboratories");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Radiator will be checked externally for debris, leaks");
		$pdf->Ln();
		$pdf->Cell(90,5,"or corrosion");
		$pdf->SetFont('Times','BU',10);
		$pdf->Cell(90,5,"Pre –departure Procedure");$pdf->SetFont('Times','',10);
		$pdf->Ln();	
		$pdf->Cell(90,5,"•	Coolant will be tested for proper mixture with a");
		$pdf->Cell(90,5,"•	Service engineer will apply touch up paint, if necessary "); 
		$pdf->Ln();
		$pdf->Cell(90,5,"coolant test strip ");
		$pdf->Cell(90,5,"•	Preserve reasonable overall appearance of equipment");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Internal condition of cooling system noted");
		$pdf->Cell(90,5,"•	Customer representative will be instructed on upkeep procedures,");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Radiator cap will be checked for proper seal");
		$pdf->Cell(90,5,"shown start switch and breaker position.");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Cooling system hoses will be checked tighten all");
		$pdf->Cell(90,5,"•	Report condition of the components tested to");
		$pdf->Ln();
		$pdf->Cell(90,5,"clamps as necessary");
		$pdf->Cell(90,5," the customer representative");
		$pdf->Ln();
		$pdf->Cell(90,5,"");
		$pdf->Cell(90,5,"•	Note any recommendations for repairs that are needed");
		$pdf->Ln();	$pdf->SetFont('Times','BU',10);
		$pdf->Cell(90,5,"Generator Controller");$pdf->SetFont('Times','',10);
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Check electrical connections and wiring for any abrasion or chaffing");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Tighten all electrical connections");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Check switches and gauges ");
		$pdf->Ln();
		$pdf->Cell(90,5,"•	Check panel lights",0,'L');	
		$pdf->AddPage();
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','',20);
		$pdf->SetTextColor(57,167,235); 
		$pdf->MultiCell(0,5,"Optional Services",0,'C');	
		$pdf->SetFont('Times','',13);
		$pdf->SetTextColor(0,0,0); 
		$pdf->MultiCell(0,7,ucwords($jobCompnay),0,'C');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','',10);
	 	$pdf->MultiCell(0,5,"GPG offers additional services that are designed to enhance the service life of your equipment. Local authorities, depending on the jurisdiction, may require these services to be performed.   They are also recommended by generator manufacturers to be performed at least once per year.",0,'J');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','BU',12);
		$pdf->MultiCell(0,5,"Automatic Transfer Switch Service and Testing",0,'L');
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(0,5,"     Perform building load test - Observe Automatic Transfer Switch operation",0,'L');
		$pdf->MultiCell(0,5,"     Test normal voltage sensing relays, in phase monitor, engine start sequence, and shutdowns",0,'L');
		$pdf->MultiCell(0,5,"     Adjust  output voltage and frequency of the generator",0,'L');
		$pdf->MultiCell(0,5,"     Calibrate start delay, transfer/re-transfer timing if necessary",0,'L');
		$pdf->MultiCell(0,5,"     Tests entire emergency power system",0,'L');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','BU',12);
		$pdf->MultiCell(0,5,"Load Bank Testing",0,'L');
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(0,5,"     Tests the generator to manufacturers specifications",0,'L');
		$pdf->MultiCell(0,5,"     Burns out unburned fuel to eliminates “wet stacking” in exhaust systems",0,'L');
		$pdf->MultiCell(0,5,"     Tests governor and fuel system with varying loads",0,'L');
		$pdf->MultiCell(0,5,"     Satisfies FDA and NFPA requirements",0,'L');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','BU',12);
		$pdf->MultiCell(0,5,"Diesel Fuel Polishing",0,'L');
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(0,5,"     Agitates fuel to prevent algae growth",0,'L');
		$pdf->MultiCell(0,5,"     Removes bacterial material from stagnant fuel",0,'L');
		$pdf->MultiCell(0,5,"     Prevent costly fuel injection pump failure",0,'L');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','BU',12);
		$pdf->MultiCell(0,5,"Diesel Fuel Sample",0,'L');
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(0,5,"     Analyzed by atomic absorption spectrophotometry.",0,'L');
		$pdf->MultiCell(0,5,"     Determine if there are any contaminants that would impair the ability of the fuel to burn safely and efficiently",0,'L');
		$pdf->MultiCell(0,5,"     A follow-up report will be available on line at your exclusive password protected web site.",0,'L');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','B',10);
		$pdf->AddPage();
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','',20);
		$pdf->SetTextColor(57,167,235); 
		$pdf->MultiCell(0,5,"Other Generators Services",0,'C');
		$pdf->SetFont('Times','',12);
		$pdf->SetTextColor(0,0,0); 
		$pdf->MultiCell(0,7,ucwords($jobCompnay),0,'C');
		$pdf->SetTextColor(0,0,0); 
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->MultiCell(0,5,"GPG offers services that complement the operation of your equipment",0,'J');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','BU',12);
		$pdf->MultiCell(0,5,"Generator Monitoring Service",0,'L');
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(0,5,"     Expedite emergency response time",0,'L');
		$pdf->MultiCell(0,5,"     Stand alone system with internal battery back-up",0,'L');
		$pdf->MultiCell(0,5,"     Web based security system ",0,'L');
		$pdf->MultiCell(0,5,"     Monitor building power, generator power and or generator faults ",0,'L');
		$pdf->MultiCell(0,5,"     Customer selected personnel text paged and emailed",0,'L');
		$pdf->MultiCell(0,5,"     Exclusive customer driven interactive Web Page included",0,'L');
		$pdf->MultiCell(0,5,"     Equipment run log and service information ",0,'L');
		$pdf->MultiCell(0,5,"     Low monthly maintenance fee",0,'L');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','BU',12);
		$pdf->MultiCell(0,5,"Diesel Fuel Delivery",0,'L');
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(0,5,"     EPA approved California Red Dye #2 low sulfur diesel fuel ",0,'L');
		$pdf->MultiCell(0,5,"     Fuel rates change daily ",0,'L');
		$pdf->MultiCell(0,5,"     Please call for current pricing.",0,'L');
		$pdf->MultiCell(0,5,"     Additional charge for legally inaccessible fuel tanks",0,'L');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','BU',12);
		$pdf->MultiCell(0,5,"Generator Rentals",0,'L');
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(0,5,"     20kW to 2 Megawatt",0,'L');
		$pdf->MultiCell(0,5,"     Special events or planned outages",0,'L');
		$pdf->MultiCell(0,5,"     Delivery and installation",0,'L');
		$pdf->MultiCell(0,5,"     Cable and tem-power boxes",0,'L');
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','BU',12);
		$pdf->MultiCell(0,5,"Equipment is owned not rented – competitive pricing",0,'L');
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(0,5,"     Equipment is owned not rented – competitive pricing",0,'L');
		$pdf->MultiCell(0,5,"     Hot spots on circuits provide precise troubleshooting procedure",0,'L');
		$pdf->MultiCell(0,5,"     Predict future maintenance",0,'L');
		$pdf->MultiCell(0,5,"     Save electricity",0,'L');
		$pdf->MultiCell(0,5,"     Prevent unpredicted circuit failure",0,'L');
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(0,1,'',0,'J');
		$pdf->AddPage();
		$pdf->MultiCell(0,5,"",0,'J');
		$pdf->SetFont('Times','',20);
		$pdf->SetTextColor(57,167,235); 
		$pdf->MultiCell(0,7,"Terms and Conditions",0,'C');
		//$pdf->MultiCell(0,5,"For",0,'C');
		$pdf->SetTextColor(0,0,0); 
		$pdf->MultiCell(0,5,$jobCompnay,0,'C');
		$pdf->SetFont('Times','',10);
		$pdf->MultiCell(0,5,'',0,'J');
		//$pdf->MultiCell(0,5,"This agreement provides that GPG Inc. will perform the preventive maintenance service items that should enhance the service life of the equipment listed on page 4 of this proposal and avoid premature failure of that equipment provided there are no material defects or manufacturing flaws in design and/or production of that equipment when this agreement is initiated.",0,'J');
		$pdf->MultiCell(0,5,$terms_and_conditions,0,'J');
		$pdf->MultiCell(0,5,'',0,'J');
		$pdf->SetFont('Times','B',10);
		$pdf->MultiCell(0,5,'This is a “Lump Sum” Agreement.  For your convenience, you will be billed in equal installments tied to service visits over the term of this agreement.  Unless otherwise agreed upon, you will be billed after each service is performed.  Services cannot be cancelled or moved as the cost of the Annual Service and any “Additional/Optional” services selected in this agreement are included.  If you, the customer, cancel any visits, you will be billed for the visit.',0,'J');
		///////////
		$pdf->AddPage();
		$pdf->SetFont('Times','',26);
		$pdf->SetTextColor(173,2,2); 
		$pdf->MultiCell(0,5,'',0,'J');
		$pdf->MultiCell(0,10,"Make Global Power Group, Inc your one-stop shop.",0,'C');
		$pdf->MultiCell(0,5,'',0,'J');
		$pdf->MultiCell(0,5,'',0,'J');
		$pdf->SetFont('Times','B',20);
		$pdf->SetTextColor(57,167,235); 
		$pdf->MultiCell(80,10,"We're not just a generator company.  We are solutions provider to a number of areas you encounter throughout your organization.",0,'C');
		$pdf->SetFont('Times','',16);
		$pdf->SetTextColor(0,0,0); 
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(80,10,"•	Consulting and Design",0,0,'J');
		$pdf->Ln();
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(80,10,"•	UPS Systems",0,0,'J');
		$pdf->Ln();
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(80,10,"•	Turnkey Equipment Installations",0,0,'J');
		$pdf->Ln();
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(80,10,"•	System Upgrades",0,0,'J');
		$pdf->Ln();
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(80,10,"•	Arc Flash Studies",0,0,'J');
		$pdf->Ln();
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(80,10,"•	Infrared Testing",0,0,'J');
		$pdf->Ln();
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(80,10,"•	Electrical Services including High-Voltage",0,0,'J');
		$pdf->Ln();
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(80,10,"•	Diesel Fuel Deliveries",0,0,'J');
		$pdf->Ln();
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(80,10,"•	Fuel Services (Testing and Polishing)",0,0,'J');
		$pdf->Ln();
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(80,10,"•	Monitoring Services",0,0,'J');
		$pdf->Ln();
		$pdf->Cell(80,10,"",0,0,'C');$pdf->Cell(30,10,"•	Industrial Engines, Parts and Service",0,0,'J');
		$pdf->Output('ConsumerContractDoc.pdf','D');
}

	public function StartPageGroup()
	{
		$this->NewPageGroup = true;
	}
	public function PDF()
	{
		$this->FPDF("P","mm","A4"); 
	}
	public function GroupPageNo()
	{
		return $this->PageGroups[$this->CurrPageGroup];
	}
	public function PageGroupAlias()
	{
		return $this->CurrPageGroup;
	}
	public function _beginpage($orientation, $format)
	{
		parent::_beginpage($orientation, $format);
		if($this->NewPageGroup)
		{
			// start a new group
			$n = sizeof($this->PageGroups)+1;
			$alias = "{nb$n}";
			$this->PageGroups[$alias] = 1;
			$this->CurrPageGroup = $alias;
			$this->NewPageGroup = false;
		}
		elseif($this->CurrPageGroup)
			$this->PageGroups[$this->CurrPageGroup]++;
	}

	public function _putpages()
	{
		$nb = $this->page;
		if (!empty($this->PageGroups))
		{
			// do page number replacement
			foreach ($this->PageGroups as $k => $v)
			{
				for ($n = 1; $n <= $nb; $n++)
				{
					$this->pages[$n] = str_replace($k, $v, $this->pages[$n]);
				}
			}
		}
		parent::_putpages();
	}

	/*
	* copyContract
	*/
	public function copyContract(){
		$consum_contract_id = Input::get('job_id');
		$copy_field_service_work_id = Input::get('fsw_id');
		$eqpFSW= array();
		$eqpQry = DB::select(DB::raw("select gpg_consum_contract_equipment_id,GPG_customer_id from gpg_field_service_work where id = '".$copy_field_service_work_id."'"));
		foreach ($eqpQry as $key => $value) {
			$eqpFSW = (array)$value;
		}
		if(isset($eqpCusFSW["gpg_consum_contract_equipment_id"]) && $eqpCusFSW["gpg_consum_contract_equipment_id"]!==''){
			DB::table('gpg_consum_contract_equipment')->where('gpg_consum_contract_id','=',$consum_contract_id)->update(array('gpg_consum_contract_id'=> NULL, 'modified_on'=> date('Y-m-d')));
			if (isset($eqpFSW["gpg_consum_contract_equipment_id"]))
				DB::table('gpg_consum_contract_equipment')->where('id','=',$eqpFSW["gpg_consum_contract_equipment_id"])->update(array('gpg_consum_contract_id'=>$consum_contract_id,'modified_on'=>date('Y-m-d')));
		}
		$copyFSWColsRs = array();
		$copyColQry =  DB::select(DB::raw("SHOW COLUMNS FROM gpg_field_service_work WHERE `Field` in('sub_task','location','main_contact_name', 'main_contact_phone','fax','equipment_needed','task','labor_shop_cost_rate','labor_shop_list_rate','labor_shop_total_hours','labor_labor_cost_rate','labor_labor_list_rate','labor_labor_total_hours','labor_lbt_cost_rate',
											'labor_lbt_list_rate','labor_lbt_total_hours','labor_ot_cost_rate','labor_ot_list_rate','labor_ot_total_hours','labor_sub_con_cost_rate','labor_sub_con_list_rate','labor_sub_con_total_hours','sub_cost_total','sub_list_total','grand_cost_total','grand_list_total','hazmat','tax_amount','comp_cost_total','comp_list_total','mat_cost_total','mat_list_total','labor_cost_total','labor_list_total','labor_hour_total','other_charge_cost_total','other_charge_total')"));
		foreach ($copyColQry as $key => $value) {
			$copyFSWColsRs[] = (array)$value;
		}
		$getFieldServiceWorkCopy = array();
		$fsCopyQry =  DB::select(DB::raw("select * from gpg_field_service_work where id = '$copy_field_service_work_id'"));	
		foreach ($fsCopyQry as $key => $value) {
			$getFieldServiceWorkCopy = (array)$value;
		}
		$insertQuery = array();
		$copyFSWQuery = array();
		foreach ($copyFSWColsRs as $key => $copyFSWColsRow)
		{
			$copyFSWQuery += array($copyFSWColsRow['Field']=>(isset($getFieldServiceWorkCopy[$copyFSWColsRow['Field']])?"'".addslashes($getFieldServiceWorkCopy[$copyFSWColsRow['Field']])."'":"NULL"));
		}
		$copyFSWQuery = DB::table('gpg_consum_contract')->where('id','=',$consum_contract_id)->update($copyFSWQuery+array('modified_on'=>date('Y-m-d')));
		if($copyFSWQuery){
			//Component
			DB::table('gpg_consum_contract_component')->where('gpg_consum_contract_id','=',$consum_contract_id)->delete();
			$getCopyComponentRs = array();
			$copyCompQry =  DB::select(DB::raw("select * from gpg_field_service_work_component where gpg_field_service_work_id = '$copy_field_service_work_id'"));
			foreach ($copyCompQry as $key => $value) {
				$getCopyComponentRs= (array)$value;
			}
			foreach ($getCopyComponentRs as $key => $copyComponentRow){
				$componentColsRs = array();
				$compoQry =  DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_component"));
				foreach ($compoQry as $key => $value) {
					$componentColsRs[]=(array)$value;
				}
				$componentIgnore = array("id","gpg_consum_contract_id","created_on", "modified_on");
				$componentQuery = array();
					foreach ($componentColsRs as $key => $componentColsRow){
					    if (!in_array($componentColsRow['Field'],$componentIgnore))
					    	$componentQuery += array($componentColsRow['Field']=>(!empty($copyComponentRow[$componentColsRow['Field']])?"'".addslashes($copyComponentRow[$componentColsRow['Field']])."'":"NULL"));
					}
				$componentQuery = DB::table('gpg_consum_contract_component')->insert($componentQuery+array('gpg_consum_contract_id'=>$consum_contract_id,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
			}
		//Material
		DB::table('gpg_consum_contract_material')->where('gpg_consum_contract_id','=',$consum_contract_id)->delete();
		$getCopyMatRs = array();
		$copyMatQry =  DB::select(DB::raw("select * from gpg_field_service_work_material where gpg_field_service_work_id = '$copy_field_service_work_id'"));
		foreach ($copyMatQry as $key => $value) {
			$getCopyMatRs= (array)$value;
		}
		foreach ($getCopyMatRs as $key => $copyMatRow)
		{
			$matColsRs = array();
			$matColsRsQry =  DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_material"));
			foreach ($matColsRsQry as $key => $value) {
				$matColsRs[] = (array)$value;
			}
			$matIgnore = array("id","gpg_consum_contract_id","created_on", "modified_on");
			$matQuery = array();
			foreach ($matColsRs as $key => $matColsRow){
			    if (!in_array($matColsRow['Field'],$matIgnore))
			    	$matQuery += array($matColsRow['Field']=>(!empty($copyMatRow[$matColsRow['Field']])?"'".addslashes($copyMatRow[$matColsRow['Field']])."'":"NULL"));
			}
			$matQuery = DB::table('gpg_consum_contract_material')->insert($matQuery+array('gpg_consum_contract_id'=>$consum_contract_id, 'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
		}
		//Labor
		DB::table('gpg_consum_contract_labor')->where('gpg_consum_contract_id','=',$consum_contract_id)->delete();
		$getCopyLaborRs = array();
		$laborQry = DB::select(DB::raw("select * from gpg_field_service_work_labor where gpg_field_service_work_id = '$copy_field_service_work_id'"));
		foreach ($laborQry as $key => $value) {
			$getCopyLaborRs= (array)$value;
		}
		foreach ($getCopyLaborRs as $key => $copyLaborRow)
		{
			$laborQuery = array();
			$laborColsRs = array();
			$laborColsQry =  DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_labor"));
			foreach ($laborColsQry as $key => $value) {
				$laborColsRs[] = (array)$value;
			}
			$laborIgnore = array("id","gpg_consum_contract_id","created_on", "modified_on");
			foreach ($laborColsRs as $key => $laborColsRow)
			{
				    if (!in_array($laborColsRow['Field'],$laborIgnore))
				    	$laborQuery += array($laborColsRow['Field']=>(!empty($copyLaborRow[$laborColsRow['Field']])?"'".addslashes($copyLaborRow[$laborColsRow['Field']])."'":"NULL"));
				}
			$laborQuery = DB::table('gpg_consum_contract_labor')->insert($laborQuery+array('gpg_consum_contract_id'=>$consum_contract_id,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
		}
			//Other
			DB::table('gpg_consum_contract_other')->where('gpg_consum_contract_id','=',$consum_contract_id)->delete();
			$getCopyOtherRs = array();
			$otQry =  DB::select(DB::raw("select * from gpg_field_service_work_other where gpg_field_service_work_id = '$copy_field_service_work_id'"));
			foreach ($otQry as $key => $value) {
				$getCopyOtherRs= (array)$value;
			}
			foreach ($getCopyOtherRs as $key => $copyOtherRow){
				$otherQuery = array();
				$otherColsRs = array();
				$otcolQry =  DB::select(DB::raw("SHOW COLUMNS FROM gpg_consum_contract_other"));
				foreach ($otcolQry as $key => $value) {
					$otherColsRs[] = (array)$value;
				}
				$otherIgnore = array("id","gpg_consum_contract_id","created_on", "modified_on");
				foreach ($otherColsRs as $key => $otherColsRow) {
					if (!in_array($otherColsRow['Field'],$otherIgnore))
						$otherQuery += array($otherColsRow['Field']=>(!empty($copyOtherRow[$otherColsRow['Field']])?"'".addslashes($copyOtherRow[$otherColsRow['Field']])."'":"NULL"));
				}
				$otherQuery = DB::table('gpg_consum_contract_other')->insert($otherQuery+array('gpg_consum_contract_id'=>$consum_contract_id,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
			}
		}
		return 1;
	}
	public function clear_num($num){
	    return str_replace('$','',str_replace(',','',$num));
    }
	/*
	* updateConsumContractForm
	*/
	public function updateConsumContractForm(){
		$consum_contract_id = Input::get("job_id");
		$jobNum = Input::get("job_num");
		$cusId = Input::get("customerBillto");
		$TotalLinesAts = Input::get("atsCount");
		$lump_sum_service = Input::get("lump_sum_service");
		if (!isset($lump_sum_service) || empty($lump_sum_service))
			$lump_sum_service = NULL;
		$terms_and_conditions = Input::get("contract_terms");
		$address = Input::get("address");
		$GPG_attach_job_id = '';
		$consumContractQuery = array();

		if(Input::get("attachJobNum")!=""){
			$GPG_attach_job_id = DB::table('gpg_job')->where('job_num','=',Input::get("attachJobNum"))->pluck('id');
		}
		$jobFields = array("GPG_customer_id"=>"customerBillto","site"=>"site","site_of"=>"siteOf","pri_contact_name"=>"priContactName","pri_contact_phone"=>"priContactPhone","pri_contact_fax"=>"priContactFax","pri_contact_cell"=>"priContactCell","pri_contact_email"=>"priContactEmail","pri_contact_title"=>"priContactTitle","alt_contact_name"=>"altContactName","alt_contact_phone"=>"altContactPhone","alt_contact_fax"=>"altContactFax","alt_contact_cell"=>"altContactCell","alt_contact_email"=>"altContactEmail","alt_contact_title"=>"altContactTitle","GPG_employee_id"=>"salePersonId","schedule_date"=>"scheduleDate","quoted_estimate"=>"quotedEstimate","consum_contract_type"=>"contractType","consum_contract_start_date"=>"contractSDate","consum_contract_end_date"=>"contractEDate","po_number"=>"poNumber","po_start_date"=>"poSDate","po_end_date"=>"poEDate","special_billing_info"=>"billingInformation","additional_info"=>"additionalInformation","date_job_won"=>"dateJobWon","quarterly_billing"=>"quatBilling","GPG_attach_contract_number"=>"GPG_attach_contract_number","manual_amount"=>"manual_amount","pm_adjust"=>"_pm_adjust","pm_visits"=>"_pm_visits","annual_adjust"=>"_annual_adjust","annual_visits"=>"_annual_visits","visits_per_year"=>"_visits_per_year","pm_charges"=>"_pm_charges","annual_charges" => "_annual_charges","contract_notes"=>"_contract_notes","show_lump_sum"=>"lump_sum_service","exclude_from_contract"=>"exclude_from_contract");
		$cusFields = array("address"=>"cusAddress1","address2"=>"cusAddress2","city"=>"cusCity","state"=>"cusState","zipcode"=>"cusZip","phone_no"=>"cusPhone","attn"=>"cusAtt");
		$eqpFields = array("GPG_customer_id"=>"customerBillto","location"=>"_location","address1"=>"_address1","address2"=>"_address2","city"=>"_city","state"=>"_state","zip"=>"_zip","attn"=>"_attn","make"=>"_make","model"=>"_model","serial"=>"_serial","spec"=>"_spec","kw"=>"_kw","engine_level"=>"_engine_level","phase"=>"_phase","volts"=>"_volts","amps"=>"_amps","engMake"=>"engMake","engModel"=>"engModel","engSerial"=>"engSerial","engSpec"=>"engSpec","eng_cpl_so_ot"=>"engCplSoOT","eng_fuel_capacity"=>"engFuelCap","eng_oil_type"=>"engOilType","eng_oil_quantity"=>"engOilQuantity");
		while (list($key,$value)= each($jobFields)) {
			if($key=='pm_charges' || $key == 'annual_charges'){
				$consumContractQuery += array($key=>(Input::get($value)!=""?"'".($this->clear_num(Input::get($value)))."'":"NULL"));
			}
			else{
			   if (preg_match("/date/i",$key)) 
			   	$consumContractQuery += array($key=>(Input::get($value)!=""?"'".date('Y-m-d',strtotime(Input::get($value)))."'":"NULL"));
			   else 
			   	$consumContractQuery += array($key=>(Input::get($value)!=""?Input::get($value):"NULL"));
			}
		}
		if(!empty($consum_contract_id)){
			$consum_contract_update = DB::table('gpg_consum_contract')->where('id','=',$consum_contract_id)->update($consumContractQuery+array('GPG_attach_job_id'=>$GPG_attach_job_id,'modified_on'=>date('Y-m-d')));
		}else{
			$maxId = DB::table('gpg_consum_contract')->max('id')+1;
			$consum_contract_id= $maxId;
			$consum_contract_insert = DB::table('gpg_consum_contract')->insert($consumContractQuery+array('id'=>$maxId,'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
		}
		if ($consum_contract_update==1) {
			$cusFieldQuery = array();
			while (list($key,$value)= each($cusFields)) {
			   $cusFieldQuery += array($key=>Input::get($value));
			}
			$cus_update = DB::table('gpg_customer')->where('id','=',Input::get('customerBillto'))->update($cusFieldQuery+array('modified_on'=>date('Y-m-d')));
			$consum_contract_eqp_check	= DB::table('gpg_consum_contract_equipment')->where('gpg_consum_contract_id','=',$consum_contract_id)->pluck('id');
			$worf_equipment_id = Input::get('worf_equipment_id');
			if(empty($worf_equipment_id))
			{	
				$eqpFieldQuery = array();
				while (list($key,$value)= each($eqpFields)) {
				   $eqpFieldQuery += array($key=>Input::get($value));
				}
				if(empty($consum_contract_eqp_check)){
					$getEqpMaxId = DB::table('gpg_consum_contract_equipment')->max('id')+1;
					$eqp_update = DB::table('gpg_consum_contract_equipment')->insert($eqpFieldQuery+array('id'=>$getEqpMaxId,'gpg_consum_contract_id'=>$consum_contract_id,'modified_on'=>date('Y-m-d'),'created_on'=>date('Y-m-d')));
				}
				else{
					$eqp_update = DB::table('gpg_consum_contract_equipment')->where('gpg_consum_contract_id','=',$consum_contract_id)->update($eqpFieldQuery+array('modified_on'=>date('Y-m-d')));
				}
			}else{
			   $field_service_check	=  DB::table('gpg_field_service_work')->where('gpg_consum_contract_equipment_id','=',$consum_contract_eqp_check)->pluck('gpg_consum_contract_equipment_id');
			    if(!$field_service_check) {
			   		DB::table('gpg_consum_contract_equipment')->where('gpg_consum_contract_id','=',$consum_contract_id)->delete();
			    }
			  	$eqp_update = DB::table('gpg_consum_contract_equipment')->where('id','=',$worf_equipment_id)->update(array('gpg_consum_contract_id'=>$consum_contract_id,'modified_on'=>date('Y-m-d')));
			}
			// ATS QUERY
			$delAtsId = Input::get("delAtsId");
			for ($del=0; $del<count($delAtsId); $del++) {
			   	DB::table('gpg_consum_contract_ats')->where('id','=',$delAtsId[$del])->delete();
			}
			for ($i=0; $i<=$TotalLinesAts; $i++) {
				if (Input::get("atsModel_".$i)!="" && Input::get("atsMake_".$i)!="") {
					if(Input::get("atsRecId_".$i)==""){
						$atsQuery = DB::table('gpg_consum_contract_ats')->insert(array('gpg_consum_contract_id'=>$consum_contract_id,'model'=>Input::get("atsModel_".$i),'make'=>Input::get("atsMake_".$i),'serial'=>Input::get("atsSerial_".$i),'spec'=>Input::get("atsSpec_".$i),'phase'=>Input::get("atsPhase_".$i),'volts'=>Input::get("atsVolts_".$i),'amps'=>Input::get("atsAmps_".$i),'pole'=>Input::get("atsPole_".$i),'created_on'=>date('Y-m-d'),'modified_on'=>date('Y-m-d')));
					}
					else{
						$updateInvoiceAmountQuery = DB::table('gpg_consum_contract_ats')->where('id','=',Input::get("atsRecId_".$i))->update(array('model'=>Input::get("atsModel_".$i),'make'=>Input::get("atsMake_".$i),'serial'=>Input::get("atsSerial_".$i),'spec'=>Input::get("atsSpec_".$i),'phase'=>Input::get("atsPhase_".$i),'volts'=>Input::get("atsVolts_".$i),'amps'=>Input::get("atsAmps_".$i),'pole'=>Input::get("atsPole_".$i),'modified_on'=>date('Y-m-d')));
					}
				} //end if
			} // end for 
			// END ATS
			// SCHEDULE QUERY
			$total_services = Input::get("total_services");
			$service_no = 0;
			$my_service = -1;
			for ($i=0; $i<=11; $i++) {
				if(Input::get("schRecId_".$i)){
					$updateContractSchedule = DB::table('gpg_consum_contract_schedule')->where('id','=',Input::get("schRecId_".$i))->update(array('month'=>DATE_FORMAT(DATE_ADD(Input::get("schYear")."-".str_pad(Input::get("schMonth"), 2, '0',STR_PAD_LEFT).'-01', 'interval'.$i.'month'),'%c'), 'year'=>DATE_FORMAT(DATE_ADD(Input::get("schYear")."-".str_pad(Input::get("schMonth"), 2, '0',STR_PAD_LEFT).'-01', 'interval'.$i.'month'),'%Y'), 'installment'=>Input::get("schInstallment_".$i), 'modified_on'=>date('Y-m-d')));
					DB::table('gpg_consum_contract_schedule_service')->where('gpg_consum_contract_schedule_id','=',Input::get("schRecId_".$i))->delete();
					for($loop = 1;$loop <= $total_services; $loop++){
						if(Input::get("column_".$loop)){
							$service_no = Input::get("column_".$loop);
							$service_query = DB::table('gpg_consum_contract_schedule_service')->insert(array('gpg_consum_contract_schedule_id'=>Input::get("schRecId_".$i),
																				'service_no' => $service_no,
																				'service' => Input::get("schService".$loop."_".$i),
																				'price' => Input::get("schPrice".$loop."_".$i),
																				'notes' => Input::get("schNotes".$loop."_".$i)));
							}
						}
					}
				} 
			// END SCHEDULE
	   } // end if 
		DB::table('gpg_consum_contract_matrix_load_bank')->where('gpg_consum_contract_id','=',$consum_contract_id)->delete();
		if(Input::get('load_bank') && Input::get('charging_load_bank'))
		{
			foreach($_POST['load_bank'] as $k => $v)
			{
				if(strlen(trim($v['name']))>0)
				{
					DB::table('gpg_consum_contract_matrix_load_bank')->insert(array('gpg_consum_contract_id'=>$consum_contract_id, 
								'type' => trim($v['name']),
								'total_qty' => $v['total_qty'],
								'eqp_qty'  => ($v['eqp_qty']==""?0:$v['eqp_qty']),
								'eqp_rate' => $v['eqp_rate'],
								'labor_qty'  => $v['labor_qty'],
								'labor_rate' => $v['labor_rate'],
								'subtract' => ($v['subtract']==""?0:$v['subtract']),
								'notes' => str_replace("\n",". ",str_replace("\r","",$v['notes'])),
								'created_on' => date('Y-m-d'),
								'modified_on' => date('Y-m-d')));
				}
			}
		}
		return Redirect::to('contract/consum_contract_frm/'.$consum_contract_id.'/'.$jobNum)->withSuccess('Records have been Updated Successfully');
	}

	/*
	* eupdatEquipmentDetails
	*/
	public function eupdatEquipmentDetails(){
		$queryPart = array();
		$id = Input::get('job_id');
		$job_num = Input::get('job_num');
		while (list($ke,$vl)= each($_POST)) {
	   		if (preg_match("/_filter_changed_on/i",$ke)) 
	   			$queryPart[substr($ke,1,strlen($ke))] = ($vl!=''?"'".date('Y-m-d',strtotime($vl))."'":"NULL");
	   		else if (preg_match("/^_/",$ke) && !preg_match("/^_token/",$ke)) 
	   			$queryPart[substr($ke,1,strlen($ke))] = $vl;
		}
		unset($queryPart['job_id']);
		unset($queryPart['job_num']);
		DB::table('gpg_consum_contract_equipment')->where('gpg_consum_contract_id','=',$id)->update($queryPart+array('modified_on'=>date('Y-m-d')));
		return Redirect::to('contract/consum_contract_frm/'.$id.'/'.$job_num)->withSuccess('Records have been Inserted/Updated Successfully');
	}
	public function top_parent($parent_id){
        $sql = DB::select(DB::raw("SELECT id,parent_id FROM gpg_sales_tracking WHERE id = '".(int)$parent_id."'"));
        $data = array();
        foreach ($sql as $key => $value) {
        	$data = (array)$value;
        }
        if(count($data) && !empty($data)){
            if($data['parent_id'] == "" || empty($data['parent_id']) || $data['parent_id'] == NULL){
                return $data['id'];
            }else{
                return  $this->top_parent($data['parent_id']);
            }
        } else {
            return $parent_id;
        }
	}
    public function categoryChild($id , &$children) {
        $sql = DB::select(DB::raw("SELECT * from gpg_sales_tracking where parent_id = '".(int)$id."'"));
        if(count($sql) > 0) {
            # It has children, let's get them.
            foreach ($sql as $key => $row) {
            	# Add the child to the list of children, and get its subchildren
                $children[] = (array)$row;
                $this->categoryChild($row->id, $children);	
            }
        }
        return $children;
    }

	/*
	* deleteContractServiceType
	*/
	public function deleteContractServiceType()
	{	
		$id = Input::get('id');

		$result = DB::table('gpg_consum_contract_service_type')
      				->where('id','=', $id)
          			->delete();
		if($result)
			return 1;
		else 
			return 0;
	}
	
}
