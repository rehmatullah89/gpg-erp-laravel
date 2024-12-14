<?php

class ReportsController extends \BaseController {

	public $FSWStatusArray = array( '1' => 'Waiting for parts', '2' => 'Ready for schedule', '3' => 'Scheduled', '4' => 'Next visit', '5' => 'Customer issues' );
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/*
	* generateReport
	*/
	public function generateReport(){
		$modules = Generic::modules();
		if (isset($_POST) && !empty($_POST)){
			$this->excelCostDataExport();
		}
		$params = array('left_menu' => $modules);
		return View::make('reports/opt', $params);
	}

	/*
	* excelCostDataExport
	*/
	public function excelCostDataExport(){
		set_time_limit(0);
		$file1 = Input::file('uploadDataFile'); 
	    $fileinfo = "";
		if (!empty($file1)) {
			$file1 = Input::file('uploadDataFile')->getClientOriginalName();
			$fileinfo = "dataFile_".rand(99999,10000000)."_".strtotime("now").".".$file1;
			$destinationPath = public_path().'/img/';
			$uploadSuccess = Input::file('uploadDataFile')->move($destinationPath, $fileinfo);
		}		
	    $file2 = Input::file('uploadCostFile'); 		
		$fileinfo1 = "";
		if (!empty($file1)) {
				$file2 = Input::file('uploadCostFile')->getClientOriginalName();
				$fileinfo1 = "costFile_".rand(99999,10000000)."_".strtotime("now").".".$file2;
				$destinationPath = public_path().'/img/';
				$uploadSuccess = Input::file('uploadCostFile')->move($destinationPath, $fileinfo1);
		}
			$fh1 = fopen($destinationPath.$fileinfo,'r');
			$opt1 = fgets($fh1);
			$headings1 = explode('	', $opt1);
			$fh2 = fopen($destinationPath.$fileinfo1,'r');
			$opt2 = fgets($fh2);
			$headings2 = explode('	', $opt2);
			$file_name = public_path().'/img/testing.xls';
			$workbook = new Workbook($file_name);
			$worksheet =& $workbook->add_worksheet('Data');
			$fhead =& $workbook->add_format();
	  		$fhead->set_size(8);
	        $fhead->set_color('black');
			$fhead->set_bold();
	  		$fhead->set_pattern();
	  		$fhead->set_fg_color('lightgray');
			$fbody = & $workbook->add_format();
			$fbody->set_size(8);
	        $fbody->set_color('black');
			$fbodybold = & $workbook->add_format();
			$fbodybold->set_size(8);
	        $fbodybold->set_color('black');
			$fbodybold->set_bold();
			$fint = & $workbook->add_format();
			$fint->set_size(8);
	        $fint->set_color('black');
			$fint->set_num_format('#,##0.00;-#,##0.00');
			$fintYellow = & $workbook->add_format();
			$fintYellow->set_size(8);
	        $fintYellow->set_color('black');
			$fintYellow->set_border(1);
			$fintYellow->set_pattern();
			$fintYellow->set_fg_color('yellow');
			$fintYellow->set_border_color('silver');
			$fintYellow->set_num_format('#,##0.00;-#,##0.00');
			$ffloat = & $workbook->add_format();
			$ffloat->set_size(8);
	        $ffloat->set_color('black');
			$ffloat->set_num_format('#,##0.00###;-#,##0.00###');
			$fdate = & $workbook->add_format();
			$fdate->set_size(8);
	        $fdate->set_color('black');
			$fdate->set_num_format('mm/dd/yyyy');
			$srow = 0;
			$scol = 0;
			$worksheet->write_string($srow,$scol,"Type",$fhead);
	 		$worksheet->write_string($srow,$scol+1,"Date",$fhead);
			$worksheet->write_string($srow,$scol+2,"Num",$fhead);
			$worksheet->write_string($srow,$scol+3,"Memo",$fhead);
			$worksheet->write_string($srow,$scol+4,"Name",$fhead);
	 		$worksheet->write_string($srow,$scol+5,"Job Number",$fhead);
			$worksheet->write_string($srow,$scol+6,"Sales Person",$fhead);
			$worksheet->write_string($srow,$scol+7,"Tech Hours",$fhead);
	 		$worksheet->write_string($srow,$scol+8,"Technician",$fhead);
			$worksheet->write_string($srow,$scol+9,"Item",$fhead);
			$worksheet->write_string($srow,$scol+10,"Qty",$fhead);
			$worksheet->write_string($srow,$scol+11,"Sales Price",$fhead);
			$worksheet->write_string($srow,$scol+12,"Amount",$fhead);
			$worksheet->write_string($srow,$scol+13,"Labor Costs",$fhead);
			$worksheet->write_string($srow,$scol+14,"Sales-Lbr",$fhead);
			$worksheet->write_string($srow,$scol+15,"Mat'l Costs",$fhead);
			$worksheet->write_string($srow,$scol+16,"S-L-M",$fhead);
			$worksheet->write_string($srow,$scol+17,"Sales 2",$fhead);
			$worksheet->write_string($srow,$scol+18,"Name 2",$fhead);
			$worksheet->write_string($srow,$scol+19,"Job 2",$fhead);
			$worksheet->write_string($srow,$scol+23,"Act. Cost",$fhead);
			$worksheet->write_string($srow,$scol+24,"Act. Revenue",$fhead);
			$worksheet->write_string($srow,$scol+25,"($) Diff.",$fhead);
			$JobNumberStr = "";
			$FFlag = 0;
			$tRows = (count($fileinfo)>=count($fileinfo1)?count($fileinfo):count($fileinfo1));
			for ($dt=1; $dt<$tRows; $dt++) {
				$ChkBlank  = 1; 
				$ChkBlankCost = 1;
			    $rows = split(DELIMITER,$fileinfo[$dt]); 
				$rows_cost = split(DELIMITER,$fileinfo1[$dt]); 
				 $curRow = ($dt+1);
			// TYPE	 
			if (trim($rows[0])!="") { $worksheet->write_string($dt,$scol,trim($rows[0]),$fbody); $ChkBlank  = 0; }
			else $worksheet->write_blank($dt,$scol); 
			// Date
	 		if (trim($rows[1])!="") { $worksheet->write($dt,$scol+1,trim($rows[1]),$fdate); $ChkBlank  = 0; }
			else $worksheet->write_blank($dt,$scol+1); 
			// Num
			if (trim($rows[2])!="") { $worksheet->write_string($dt,$scol+2,trim($rows[2]),$fbody); $ChkBlank  = 0; }
			else $worksheet->write_blank($dt,$scol+2); 
			// Memo
			if (trim($rows[3])!="") { $worksheet->write_string($dt,$scol+3,trim($rows[3]),$fbody); $ChkBlank  = 0; }
			else $worksheet->write_blank($dt,$scol+3); 
			// Name
			if (trim($rows[4])!="") { $worksheet->write_string($dt,$scol+4,trim($rows[4]),$fbody); $ChkBlank  = 0; }
			else $worksheet->write_blank($dt,$scol+4); 
			// Job Number
	 		if (trim($rows[5])!="") { $worksheet->write_string($dt,$scol+5,trim($rows[5]),$fbody); $ChkBlank  = 0; }
			else $worksheet->write_blank($dt,$scol+5); 
			// Sales Person
			if (trim($rows[6])!="") { $worksheet->write_string($dt,$scol+6,trim($rows[6]),$fbody); $ChkBlank  = 0; }
			else $worksheet->write_blank($dt,$scol+6); 
			// Tech Hours
			if (trim($rows[7])!="") 
			  {
				//if (!eregi("~".trim($rows[5])."~",$JobNumberStr)) { 
			     if (!preg_match("/~/".trim($rows[5])."~",$JobNumberStr)) { 
			       $worksheet->write_string($dt,$scol+7,$this->standNum(trim($rows[7])),$fbody);
				   $JobNumberStr.="~".trim($rows[5])."~";
				   $FFlag = 1;
				   $ChkBlank = 0;
				 } else $worksheet->write_blank($dt,$scol+7); 
				 
			  }
			else $worksheet->write_blank($dt,$scol+7); 
			// Technician
			if (trim($rows[8])!="") { $worksheet->write_string($dt,$scol+8,trim($rows[8]),$fbody); $ChkBlank  = 0; }
			else $worksheet->write_blank($dt,$scol+8); 
			// Item
			if (trim($rows[9])!="") { $worksheet->write_string($dt,$scol+9,trim($rows[9]),$fbody); $ChkBlank  = 0; }
			else $worksheet->write_blank($dt,$scol+9); 
			// Quantity
			if (trim($rows[10])!="") { $worksheet->write_number($dt,$scol+10,$this->standNum(trim($rows[10])),$fint); $ChkBlank  = 0; }
			else $worksheet->write_blank($dt,$scol+10); 
			// Sales Price
			if (trim($rows[11])!="") { $worksheet->write_number($dt,$scol+11,$this->standNum(trim($rows[11])),$ffloat);  $ChkBlank = 0; }
			else $worksheet->write_blank($dt,$scol+11); 
			if ($ChkBlank==0) {
			// Amount Formula
			if (trim($rows[11])!="" && trim($rows[10])!="") 
			$worksheet->write_formula($dt,$scol+12,"= ROUND(IF(ISNUMBER(L".$curRow."), K".$curRow."*L".$curRow.", K".$curRow."),5)",$fint); 
			elseif (trim($rows[11])!="") $worksheet->write_number($dt,$scol+12,trim($rows[11]),$fint);
			else $worksheet->write_number($dt,$scol+12,0,$fint);
			// Labor Costs		
			$worksheet->write_formula($dt,$scol+13,"=(H".$curRow."*(0-32))",$fintYellow); 
			// Sales-Lbr
			$worksheet->write_formula($dt,$scol+14,"=(M".$curRow."+N".$curRow.")",$fintYellow); 
			} else {
			  $worksheet->write_blank($dt,$scol+13,$fintYellow); 
			  $worksheet->write_blank($dt,$scol+14,$fintYellow); 
			}
			// Mat'l Costs
			if ($FFlag==1) {
				$worksheet->write_formula($dt,$scol+15,"=(0-VLOOKUP(F".$curRow.",W2:Z".count($fileinfo1).",2))",$fintYellow); 
			    $FFlag=0;
			} else $worksheet->write_blank($dt,$scol+15,$fintYellow); 
			// S-L-M
			if ($ChkBlank==0) {
			$worksheet->write_formula($dt,$scol+16,"=(O".$curRow."+P".$curRow.")",$fint); 
			}
			// Sales 2
			$worksheet->write_blank($dt,$scol+17); 
			if (trim($rows[4])!="") $splVal = split(":",trim($rows[4]));
			// Name 2
			if ($splVal[0]!="") $worksheet->write_string($dt,$scol+18,$splVal[0],$fbody); 
			else $worksheet->write_blank($dt,$scol+18); 
			// Job Number 2
			if ($splVal[1]!="") $worksheet->write_string($dt,$scol+19,$splVal[1],$fbody); 
			else $worksheet->write_blank($dt,$scol+19); 
			//----------------------- COST Portion --------------------------
			// Cost Job Number		
			if (trim($rows_cost[0])!="") { $worksheet->write_string($dt,$scol+22,trim($rows_cost[0]),$fbodybold); $ChkBlankCost = 0; }
			else $worksheet->write_blank($dt,$scol+22); 
			
			// Cost Actual Cost		
	 		if (trim($rows_cost[1])!="") { $worksheet->write_number($dt,$scol+23,$this->standNum(trim($rows_cost[1])),$fint); $ChkBlankCost = 0; }
			else $worksheet->write_blank($dt,$scol+23); 
			
			// Cost Actual Revenue
			if (trim($rows_cost[2])!="") { $worksheet->write_number($dt,$scol+24,$this->standNum(trim($rows_cost[2])),$fint); $ChkBlankCost = 0; }
			else $worksheet->write_blank($dt,$scol+24); 
			
			// Cost ($) Difference	formula
			if ($ChkBlankCost == 0)  
			$worksheet->write_formula($dt,$scol+25,"= ROUND((Y".$curRow."-X".$curRow."),5)",$ffloat);
		} 
		$workbook->close();
		$this->HeaderingExcel($file_name);
	}

	function standNum($val) {
	     return str_replace(",","",str_replace("\"","",$val));
	}
	
	function HeaderingExcel($filename) {
      header("Content-type: application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=$filename" );
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
      header("Pragma: public");
	  readfile($filename);
    }

    /*
	* detailedFinancialReportSummary
    */
    public function detailedFinancialReportSummary(){
    	$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getFinRSByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		$params = array('left_menu' => $modules,'query_data'=>$query_data,'salesp_arr'=>$salesp_arr);
		return View::make('reports.detailed_financial_report_summary', $params);
    }
    public function getFinRSByPage($page = 1, $limit = null)
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
		$optEmployee = Input::get('optEmployee');	
		$queryPart ="";	
		$reportYearStart = Input::get("yStart");
		if (empty($reportYearStart))
			$reportYearStart = date('Y');
		$reportYearEnd = Input::get("yEnd");
		if(empty($reportYearEnd))
			$reportYearEnd = date('Y');
		$reportMonthStart = Input::get("mStart");
		if(empty($reportMonthStart))
			$reportMonthStart = 01;
		$reportMonthEnd = Input::get("mEnd");
		if(empty($reportMonthEnd))
			$reportMonthEnd = date('m');
		$reportStartDate = date('m/d/Y',strtotime("$reportYearStart-$reportMonthStart-01"));
 		$reportEndDate = $this->check_date($reportMonthEnd,$reportYearEnd);
		if ($optEmployee!="") $queryPart .= " AND GPG_employee_id = '$optEmployee' ";   
		$repairQuery = "select id,GPG_employee_id,(select name from gpg_employee where id=GPG_employee_id) as empName,field_service_work_status,grand_list_total, created_on, 1 as cont  from gpg_field_service_work where created_on >= '".date('Y-m-d',strtotime($reportStartDate))."' AND created_on <= '".date('Y-m-d',strtotime($reportEndDate))."' and ifnull(GPG_employee_id,0)<>0 $queryPart order by field_service_work_status desc";
	 	$shopQuery = "select id,GPG_employee_id,(select name from gpg_employee where id=GPG_employee_id) as empName,shop_work_quote_status,grand_list_total, created_on, 1 as cont from gpg_shop_work_quote where created_on >= '".date('Y-m-d',strtotime($reportStartDate))."' AND created_on <= '".date('Y-m-d',strtotime($reportEndDate))."' and ifnull(GPG_employee_id,0)<>0 $queryPart order by shop_work_quote_status desc";
		$elecQuery = "select id,GPG_employee_id,(select name from gpg_employee where id=GPG_employee_id) as empName,electrical_status,(ifNULL(grand_total,0)+IFNULL(subquote_total_cost,0)) as grand_total_quote, created_on, 1 as cont from gpg_job_electrical_quote where created_on >= '".date('Y-m-d',strtotime($reportStartDate))."' AND created_on <= '".date('Y-m-d',strtotime($reportEndDate))."' and ifnull(GPG_employee_id,0)<>0 $queryPart 
	  	AND job_num IN(
		SELECT MAX(a.job_num) leadId  FROM gpg_job_electrical_quote a
		JOIN gpg_sales_tracking_job_electrical_quote b ON (a.id = b.gpg_job_electrical_quote_id)
		WHERE  a.job_num  NOT LIKE '%:%' OR (a.job_num LIKE '%:%' AND a.electrical_status ='Won')
		GROUP BY b.gpg_sales_tracking_id ) order by electrical_status desc";
	 	$grassivyQuery = "select id,GPG_employee_id,(select name from gpg_employee where id=GPG_employee_id) as empName,grassivy_status,(ifNULL(grand_total,0)+IFNULL(subquote_total_cost,0)) as grand_total_quote, created_on, 1 as cont from gpg_job_grassivy_quote where created_on >= '".date('Y-m-d',strtotime($reportStartDate))."' AND created_on <= '".date('Y-m-d',strtotime($reportEndDate))."' and ifnull(GPG_employee_id,0)<>0 $queryPart 
	  	AND job_num IN(
		SELECT MAX(a.job_num) leadId  FROM gpg_job_grassivy_quote a
		JOIN gpg_sales_tracking_job_grassivy_quote b ON (a.id = b.gpg_job_grassivy_quote_id)
		WHERE  a.job_num  NOT LIKE '%:%' OR (a.job_num LIKE '%:%' AND a.grassivy_status ='Won')
		GROUP BY b.gpg_sales_tracking_id ) order by grassivy_status desc";
	 	$specialProjectQuery = "select id,GPG_employee_id,(select name from gpg_employee where id=GPG_employee_id) as empName,special_project_status,(ifNULL(grand_total,0)+IFNULL(subquote_total_cost,0)) as grand_total_quote, created_on, 1 as cont from gpg_job_special_project_quote where created_on >= '".date('Y-m-d',strtotime($reportStartDate))."' AND created_on <= '".date('Y-m-d',strtotime($reportEndDate))."' and ifnull(GPG_employee_id,0)<>0 $queryPart 
	  	AND job_num IN(
		SELECT MAX(a.job_num) leadId  FROM gpg_job_special_project_quote a
		JOIN gpg_sales_tracking_job_special_project_quote b ON (a.id = b.gpg_job_special_project_quote_id)
		WHERE  a.job_num  NOT LIKE '%:%' OR (a.job_num LIKE '%:%' AND a.special_project_status ='Won')
		GROUP BY b.gpg_sales_tracking_id ) order by special_project_status desc";
		$repairRS = DB::select(DB::raw($repairQuery));
		$shopRS = DB::select(DB::raw($shopQuery));
		$elecRS = DB::select(DB::raw($elecQuery));
		$grassivyRS = DB::select(DB::raw($grassivyQuery));
		$specialProjectRS = DB::select(DB::raw($specialProjectQuery));
		$repairWonQuery = "select id,GPG_employee_id,(select name from gpg_employee where id=GPG_employee_id) as empName,field_service_work_status,grand_list_total, date_job_won, 1 as cont  from gpg_field_service_work where date_job_won >= '".date('Y-m-d',strtotime($reportStartDate))."' AND date_job_won <= '".date('Y-m-d',strtotime($reportEndDate))."' and ifnull(GPG_employee_id,0)<>0 $queryPart";
	 	$shopWonQuery = "select id,GPG_employee_id,(select name from gpg_employee where id=GPG_employee_id) as empName,shop_work_quote_status,grand_list_total, date_job_won, 1 as cont from gpg_shop_work_quote where date_job_won >= '".date('Y-m-d',strtotime($reportStartDate))."' AND date_job_won <= '".date('Y-m-d',strtotime($reportEndDate))."' and ifnull(GPG_employee_id,0)<>0 $queryPart";
	 	$elecWonQuery = "select id,GPG_employee_id,(select name from gpg_employee where id=GPG_employee_id) as empName,electrical_status,(ifNULL(grand_total,0)+IFNULL(subquote_total_cost,0)) as grand_total_quote, date_job_won, 1 as cont from gpg_job_electrical_quote where date_job_won >= '".date('Y-m-d',strtotime($reportStartDate))."' AND date_job_won <= '".date('Y-m-d',strtotime($reportEndDate))."' and ifnull(GPG_employee_id,0)<>0 $queryPart
	  	AND job_num IN(
		SELECT MAX(a.job_num) leadId  FROM gpg_job_electrical_quote a
		JOIN gpg_sales_tracking_job_electrical_quote b ON (a.id = b.gpg_job_electrical_quote_id)
		WHERE  a.job_num  NOT LIKE '%:%' OR (a.job_num LIKE '%:%' AND a.electrical_status ='Won')
		GROUP BY b.gpg_sales_tracking_id ) ";
		$grassivyWonQuery = "select id,GPG_employee_id,(select name from gpg_employee where id=GPG_employee_id) as empName,grassivy_status,(ifNULL(grand_total,0)+IFNULL(subquote_total_cost,0)) as grand_total_quote, date_job_won, 1 as cont from gpg_job_grassivy_quote where date_job_won >= '".date('Y-m-d',strtotime($reportStartDate))."' AND date_job_won <= '".date('Y-m-d',strtotime($reportEndDate))."' and ifnull(GPG_employee_id,0)<>0 $queryPart
	 	AND job_num IN(
		SELECT MAX(a.job_num) leadId  FROM gpg_job_grassivy_quote a
		JOIN gpg_sales_tracking_job_grassivy_quote b ON (a.id = b.gpg_job_grassivy_quote_id)
		WHERE  a.job_num  NOT LIKE '%:%' OR (a.job_num LIKE '%:%' AND a.grassivy_status ='Won')
		GROUP BY b.gpg_sales_tracking_id ) ";
		$specialProjectWonQuery = "select id,GPG_employee_id,(select name from gpg_employee where id=GPG_employee_id) as empName,special_project_status,(ifNULL(grand_total,0)+IFNULL(subquote_total_cost,0)) as grand_total_quote, date_job_won, 1 as cont from gpg_job_special_project_quote where date_job_won >= '".date('Y-m-d',strtotime($reportStartDate))."' AND date_job_won <= '".date('Y-m-d',strtotime($reportEndDate))."' and ifnull(GPG_employee_id,0)<>0 $queryPart
		AND job_num IN(
		SELECT MAX(a.job_num) leadId  FROM gpg_job_special_project_quote a
		JOIN gpg_sales_tracking_job_special_project_quote b ON (a.id = b.gpg_job_special_project_quote_id)
		WHERE  a.job_num  NOT LIKE '%:%' OR (a.job_num LIKE '%:%' AND a.special_project_status ='Won')
		GROUP BY b.gpg_sales_tracking_id ) ";
		$repairWonRS = DB::select(DB::raw($repairWonQuery));
		$shopWonRS = DB::select(DB::raw($shopWonQuery));
		$elecWonRS = DB::select(DB::raw($elecWonQuery));
		$grassivyWonRS = DB::select(DB::raw($grassivyWonQuery));
		$specialProjectWonRS = DB::select(DB::raw($specialProjectWonQuery));
		$dataArray = array();
		foreach ($repairWonRS as $key => $repairWonRow){
			$repairWonRow = (array)$repairWonRow;
			if (!isset($dataArray[$repairWonRow['empName']]['repairs']['won[Date]'][date("m-Y",strtotime($repairWonRow['date_job_won']))]['count']))
				$dataArray[$repairWonRow['empName']]['repairs']['won[Date]'][date("m-Y",strtotime($repairWonRow['date_job_won']))]['count'] = 0;
			$countVal = $dataArray[$repairWonRow['empName']]['repairs']['won[Date]'][date("m-Y",strtotime($repairWonRow['date_job_won']))]['count']+=$repairWonRow['cont'];
			if (!isset($dataArray[$repairWonRow['empName']]['repairs']['won[Date]'][date("m-Y",strtotime($repairWonRow['date_job_won']))]['amount']))
				$dataArray[$repairWonRow['empName']]['repairs']['won[Date]'][date("m-Y",strtotime($repairWonRow['date_job_won']))]['amount'] = 0;
			$amtVal = $dataArray[$repairWonRow['empName']]['repairs']['won[Date]'][date("m-Y",strtotime($repairWonRow['date_job_won']))]['amount']+=$repairWonRow['grand_list_total'];
			$dataArray[$repairWonRow['empName']]['repairs']['won[Date]'][date("m-Y",strtotime($repairWonRow['date_job_won']))] = array('count'=>$countVal,'amount'=>$amtVal,"employeeID"=>$repairWonRow['GPG_employee_id']);
		}
		foreach ($shopWonRS as $key => $shopWonRow){
			$shopWonRow = (array)$shopWonRow;		
			if (!isset($dataArray[$shopWonRow['empName']]['shop']['won[Date]'][date("m-Y",strtotime($shopWonRow['date_job_won']))]['count']))
					$dataArray[$shopWonRow['empName']]['shop']['won[Date]'][date("m-Y",strtotime($shopWonRow['date_job_won']))]['count'] = 0;
			$countVal = $dataArray[$shopWonRow['empName']]['shop']['won[Date]'][date("m-Y",strtotime($shopWonRow['date_job_won']))]['count']+=$shopWonRow['cont'];
			if (!isset($dataArray[$shopWonRow['empName']]['shop']['won[Date]'][date("m-Y",strtotime($shopWonRow['date_job_won']))]['amount']))
				$dataArray[$shopWonRow['empName']]['shop']['won[Date]'][date("m-Y",strtotime($shopWonRow['date_job_won']))]['amount'] = 0;
			$amtVal = $dataArray[$shopWonRow['empName']]['shop']['won[Date]'][date("m-Y",strtotime($shopWonRow['date_job_won']))]['amount']+=$shopWonRow['grand_list_total'];
			$dataArray[$shopWonRow['empName']]['shop']['won[Date]'][date("m-Y",strtotime($shopWonRow['date_job_won']))] = array('count'=>$countVal,'amount'=>$amtVal,"employeeID"=>$shopWonRow['GPG_employee_id']);
		}
		foreach($elecWonRS as $key => $elecWonRow){
			$elecWonRow = (array)$elecWonRow;
			if (!isset($dataArray[$elecWonRow['empName']]['elec']['won[Date]'][date("m-Y",strtotime($elecWonRow['date_job_won']))]['count']))
				$dataArray[$elecWonRow['empName']]['elec']['won[Date]'][date("m-Y",strtotime($elecWonRow['date_job_won']))]['count'] =0;
			$countVal = $dataArray[$elecWonRow['empName']]['elec']['won[Date]'][date("m-Y",strtotime($elecWonRow['date_job_won']))]['count']+=$elecWonRow['cont'];
			if (!isset($dataArray[$elecWonRow['empName']]['elec']['won[Date]'][date("m-Y",strtotime($elecWonRow['date_job_won']))]['amount']))
				$dataArray[$elecWonRow['empName']]['elec']['won[Date]'][date("m-Y",strtotime($elecWonRow['date_job_won']))]['amount'] = 0;
			$amtVal =  $dataArray[$elecWonRow['empName']]['elec']['won[Date]'][date("m-Y",strtotime($elecWonRow['date_job_won']))]['amount']+=$elecWonRow['grand_total_quote'];
			$dataArray[$elecWonRow['empName']]['elec']['won[Date]'][date("m-Y",strtotime($elecWonRow['date_job_won']))] = array('count'=>$countVal,'amount'=>$amtVal,"employeeID"=>$elecWonRow['GPG_employee_id']); 
		}
		foreach ($grassivyWonRS as $key => $grassivyWonRow){
			$grassivyWonRow	= (array)$grassivyWonRow;		 
			if (!isset($dataArray[$grassivyWonRow['empName']]['grassivy']['won[Date]'][date("m-Y",strtotime($grassivyWonRow['date_job_won']))]['count']))
				$dataArray[$grassivyWonRow['empName']]['grassivy']['won[Date]'][date("m-Y",strtotime($grassivyWonRow['date_job_won']))]['count'] =0;
			$countVal = $dataArray[$grassivyWonRow['empName']]['grassivy']['won[Date]'][date("m-Y",strtotime($grassivyWonRow['date_job_won']))]['count']+=$grassivyWonRow['cont'];
			if (!isset($dataArray[$grassivyWonRow['empName']]['grassivy']['won[Date]'][date("m-Y",strtotime($grassivyWonRow['date_job_won']))]['amount']))
				$dataArray[$grassivyWonRow['empName']]['grassivy']['won[Date]'][date("m-Y",strtotime($grassivyWonRow['date_job_won']))]['amount'] = 0;
			$amtVal =  $dataArray[$grassivyWonRow['empName']]['grassivy']['won[Date]'][date("m-Y",strtotime($grassivyWonRow['date_job_won']))]['amount']+=$grassivyWonRow['grand_total_quote'];
			$dataArray[$grassivyWonRow['empName']]['grassivy']['won[Date]'][date("m-Y",strtotime($grassivyWonRow['date_job_won']))] = array('count'=>$countVal,'amount'=>$amtVal,"employeeID"=>$grassivyWonRow['GPG_employee_id']);	 
		}
		foreach ($specialProjectWonRS as $key => $specialProjectWonRow){
			$specialProjectWonRow = (array)$specialProjectWonRow;
			if (!isset($dataArray[$specialProjectWonRow['empName']]['specialProject']['won[Date]'][date("m-Y",strtotime($specialProjectWonRow['date_job_won']))]['count']))
				$dataArray[$specialProjectWonRow['empName']]['specialProject']['won[Date]'][date("m-Y",strtotime($specialProjectWonRow['date_job_won']))]['count'] =0;
			$countVal = $dataArray[$specialProjectWonRow['empName']]['specialProject']['won[Date]'][date("m-Y",strtotime($specialProjectWonRow['date_job_won']))]['count']+=$specialProjectWonRow['cont'];
			if (!isset($dataArray[$specialProjectWonRow['empName']]['specialProject']['won[Date]'][date("m-Y",strtotime($specialProjectWonRow['date_job_won']))]['amount']))
				$dataArray[$specialProjectWonRow['empName']]['specialProject']['won[Date]'][date("m-Y",strtotime($specialProjectWonRow['date_job_won']))]['amount'] =0;
			$amtVal =  $dataArray[$specialProjectWonRow['empName']]['specialProject']['won[Date]'][date("m-Y",strtotime($specialProjectWonRow['date_job_won']))]['amount']+=$specialProjectWonRow['grand_total_quote'];
			$dataArray[$specialProjectWonRow['empName']]['specialProject']['won[Date]'][date("m-Y",strtotime($specialProjectWonRow['date_job_won']))] = array('count'=>$countVal,'amount'=>$amtVal,"employeeID"=>$specialProjectWonRow['GPG_employee_id']); 
		}
		//////////
		foreach ($repairRS as $key => $repairRow){
			$repairRow = (array)$repairRow; 
			if (!isset($dataArray[$repairRow['empName']]['repairs'][$repairRow['field_service_work_status']][date("m-Y",strtotime($repairRow['created_on']))]['count']))
				$dataArray[$repairRow['empName']]['repairs'][$repairRow['field_service_work_status']][date("m-Y",strtotime($repairRow['created_on']))]['count'] = 0;
			$countVal = $dataArray[$repairRow['empName']]['repairs'][$repairRow['field_service_work_status']][date("m-Y",strtotime($repairRow['created_on']))]['count']+=$repairRow['cont'];
			if (!isset($dataArray[$repairRow['empName']]['repairs'][$repairRow['field_service_work_status']][date("m-Y",strtotime($repairRow['created_on']))]['amount']))
				$dataArray[$repairRow['empName']]['repairs'][$repairRow['field_service_work_status']][date("m-Y",strtotime($repairRow['created_on']))]['amount'] = 0;
			$amtVal = $dataArray[$repairRow['empName']]['repairs'][$repairRow['field_service_work_status']][date("m-Y",strtotime($repairRow['created_on']))]['amount']+=$repairRow['grand_list_total'];
			$dataArray[$repairRow['empName']]['repairs'][$repairRow['field_service_work_status']][date("m-Y",strtotime($repairRow['created_on']))] = array('count'=>$countVal,'amount'=>$amtVal,"employeeID"=>$repairRow['GPG_employee_id']);
		}
		foreach ($shopRS as $key => $shopRow){
			$shopRow = (array)$shopRow;
			if (!isset($dataArray[$shopRow['empName']]['shop'][$shopRow['shop_work_quote_status']][date("m-Y",strtotime($shopRow['created_on']))]['count']))
				$dataArray[$shopRow['empName']]['shop'][$shopRow['shop_work_quote_status']][date("m-Y",strtotime($shopRow['created_on']))]['count'] =0 ;
			$countVal = $dataArray[$shopRow['empName']]['shop'][$shopRow['shop_work_quote_status']][date("m-Y",strtotime($shopRow['created_on']))]['count']+=$shopRow['cont'];
			if (!isset($dataArray[$shopRow['empName']]['shop'][$shopRow['shop_work_quote_status']][date("m-Y",strtotime($shopRow['created_on']))]['amount']))
				$dataArray[$shopRow['empName']]['shop'][$shopRow['shop_work_quote_status']][date("m-Y",strtotime($shopRow['created_on']))]['amount'] = 0;
			$amtVal = $dataArray[$shopRow['empName']]['shop'][$shopRow['shop_work_quote_status']][date("m-Y",strtotime($shopRow['created_on']))]['amount']+=$shopRow['grand_list_total'];
			$dataArray[$shopRow['empName']]['shop'][$shopRow['shop_work_quote_status']][date("m-Y",strtotime($shopRow['created_on']))] = array('count'=>$countVal,'amount'=>$amtVal,"employeeID"=>$shopRow['GPG_employee_id']);
		}
		foreach ($elecRS as $key => $elecRow){
			$elecRow = (array)$elecRow;
			if (!isset($dataArray[$elecRow['empName']]['elec'][$elecRow['electrical_status']][date("m-Y",strtotime($elecRow['created_on']))]['count']))
				$dataArray[$elecRow['empName']]['elec'][$elecRow['electrical_status']][date("m-Y",strtotime($elecRow['created_on']))]['count'] = 0;
			$countVal = $dataArray[$elecRow['empName']]['elec'][$elecRow['electrical_status']][date("m-Y",strtotime($elecRow['created_on']))]['count']+=$elecRow['cont'];
			if (!isset($dataArray[$elecRow['empName']]['elec'][$elecRow['electrical_status']][date("m-Y",strtotime($elecRow['created_on']))]['amount']))
				$dataArray[$elecRow['empName']]['elec'][$elecRow['electrical_status']][date("m-Y",strtotime($elecRow['created_on']))]['amount'] =0;
			$amtVal = $dataArray[$elecRow['empName']]['elec'][$elecRow['electrical_status']][date("m-Y",strtotime($elecRow['created_on']))]['amount']+=$elecRow['grand_total_quote'];
			$dataArray[$elecRow['empName']]['elec'][$elecRow['electrical_status']][date("m-Y",strtotime($elecRow['created_on']))] = array('count'=>$countVal,'amount'=>$amtVal,"employeeID"=>$elecRow['GPG_employee_id']);
			 
		}
		foreach ($grassivyRS as $key => $grassivyRow){
			$grassivyRow = (array)$grassivyRow;
			if (!isset($dataArray[$grassivyRow['empName']]['grassivy'][$grassivyRow['grassivy_status']][date("m-Y",strtotime($grassivyRow['created_on']))]['count']))
				$dataArray[$grassivyRow['empName']]['grassivy'][$grassivyRow['grassivy_status']][date("m-Y",strtotime($grassivyRow['created_on']))]['count'] =0;
			$countVal = $dataArray[$grassivyRow['empName']]['grassivy'][$grassivyRow['grassivy_status']][date("m-Y",strtotime($grassivyRow['created_on']))]['count']+=$grassivyRow['cont'];
			if (!isset($dataArray[$grassivyRow['empName']]['grassivy'][$grassivyRow['grassivy_status']][date("m-Y",strtotime($grassivyRow['created_on']))]['amount']))
				$dataArray[$grassivyRow['empName']]['grassivy'][$grassivyRow['grassivy_status']][date("m-Y",strtotime($grassivyRow['created_on']))]['amount']=0;
			$amtVal = $dataArray[$grassivyRow['empName']]['grassivy'][$grassivyRow['grassivy_status']][date("m-Y",strtotime($grassivyRow['created_on']))]['amount']+=$grassivyRow['grand_total_quote'];
			$dataArray[$grassivyRow['empName']]['grassivy'][$grassivyRow['grassivy_status']][date("m-Y",strtotime($grassivyRow['created_on']))] = array('count'=>$countVal,'amount'=>$amtVal,"employeeID"=>$grassivyRow['GPG_employee_id']);		 
		}
		foreach ($specialProjectRS as $key => $specialProjectRow){
			$specialProjectRow = (array)$specialProjectRow;
			if (!isset($dataArray[$specialProjectRow['empName']]['specialProject'][$specialProjectRow['special_project_status']][date("m-Y",strtotime($specialProjectRow['created_on']))]['count']))
				$dataArray[$specialProjectRow['empName']]['specialProject'][$specialProjectRow['special_project_status']][date("m-Y",strtotime($specialProjectRow['created_on']))]['count']=0;
			$countVal = $dataArray[$specialProjectRow['empName']]['specialProject'][$specialProjectRow['special_project_status']][date("m-Y",strtotime($specialProjectRow['created_on']))]['count']+=$specialProjectRow['cont'];
			if (!isset($dataArray[$specialProjectRow['empName']]['specialProject'][$specialProjectRow['special_project_status']][date("m-Y",strtotime($specialProjectRow['created_on']))]['amount']))
				$dataArray[$specialProjectRow['empName']]['specialProject'][$specialProjectRow['special_project_status']][date("m-Y",strtotime($specialProjectRow['created_on']))]['amount']=0;
			$amtVal = $dataArray[$specialProjectRow['empName']]['specialProject'][$specialProjectRow['special_project_status']][date("m-Y",strtotime($specialProjectRow['created_on']))]['amount']+=$specialProjectRow['grand_total_quote'];
			$dataArray[$specialProjectRow['empName']]['specialProject'][$specialProjectRow['special_project_status']][date("m-Y",strtotime($specialProjectRow['created_on']))] = array('count'=>$countVal,'amount'=>$amtVal,"employeeID"=>$specialProjectRow['GPG_employee_id']);
			 
		}
		$results->totalItems = count($dataArray);
		$results->items = array_slice($dataArray,$start,$limit);		
		return $results;
	}
	public function check_date($mon,$year) {
		for ($j=31; $j>=28; $j--) {
	    	if (checkdate($mon,$j,$year)) return date('m/d/Y',strtotime($year.'-'.$mon.'-'.$j));
	    } 
    }

    /*
	* tcJobsReport
    */
    public function tcJobsReport(){
    	$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getTCJReportByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$customer_arr = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->lists('name','id');
  		$res_task = DB::select(DB::raw("SELECT DISTINCT(task) AS aid FROM gpg_job WHERE job_num LIKE 'TC%' AND task != '' ORDER BY aid ASC"));
		$arrtasks = array();
		foreach ($res_task as $key => $value){
			$arrtasks[$value->aid] = substr($value->aid,0,30);
		}
		$params = array('left_menu' => $modules,'arrtasks'=>$arrtasks,'customer_arr'=>$customer_arr,'query_data'=>$query_data);
		return View::make('reports.tc_jobs_report', $params);
    }
    public function getTCJReportByPage($page = 1, $limit = null)
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
		$queryPart = "";   
		$PartsOrderedSDate="";   
		$PartsOrderedEDate="";   
		$PartsRecievedSDate="";   
		$PartsRecievedEDate="";   
		$PartsScheduledSDate="";   
		$PartsScheduledEDate="";   
		$optEmployee="";   
		$OpenedSDate = Input::get("OpenedSDate");
		$OpenedEDate = Input::get("OpenedEDate");
		if($OpenedSDate==""){
			$OpenedSDate = "01/01/2012";
		}
		if($OpenedEDate==""){
			$OpenedEDate = date("m/d/Y");
		}
		$ScheduledSDate = Input::get("ScheduledSDate");
		$ScheduledEDate = Input::get("ScheduledEDate");
		$SJobNumber = Input::get("SJobNumber");
		$EJobNumber = Input::get("EJobNumber");
		$SContractNumber = Input::get("SContractNumber");
		$EContractNumber = Input::get("EContractNumber");
		$optCustomer = Input::get("optCustomer");
		$optJobStatus = Input::get("optJobStatus");
		$FSWStatus = Input::get("FSWStatus");
		$zone_index_val = Input::get("zone_index_val");
		$order_by = Input::get("order_by");
		$order_type = Input::get("orderby_type");
		$task = Input::get("tasks");
		$optJobStatus = Input::get("optJobStatus");
		$queryPart="";
		if ($OpenedSDate!="" and $OpenedEDate!=""){
			$queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($OpenedSDate))." 00:00:00' AND created_on <= '".date('Y-m-d',strtotime($OpenedEDate))." 23:59:59' ";
		}
		elseif ($OpenedSDate!="") $queryPart .= " AND date_format(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($OpenedSDate))."'";
		if ($ScheduledSDate!="" and $ScheduledEDate!="") $queryPart .= " AND schedule_date >= '".date('Y-m-d',strtotime($ScheduledSDate))."' AND schedule_date <= '".date('Y-m-d',strtotime($ScheduledEDate))."' ";
		elseif ($ScheduledSDate!="") $queryPart .= " AND schedule_date = '".date('Y-m-d',strtotime($ScheduledSDate))."'";	 
		if ($SJobNumber!="" and $EJobNumber!="") $queryPart .= " AND job_num >= '".$SJobNumber."' AND job_num <= '".$EJobNumber."' ";
		elseif ($SJobNumber!="") $queryPart .= " AND job_num like '".$SJobNumber."%'";
		if ($SContractNumber!="" and $EContractNumber!="") $queryPart .= " AND contract_number >= '".$SContractNumber."' AND contract_number <= '".$EContractNumber."' ";
		elseif ($SContractNumber!="") $queryPart .= " AND contract_number like '".$SContractNumber."%'";
		if ($optCustomer!="") $queryPart .= " AND GPG_customer_id = '$optCustomer' "; 
		if ($task!='') $queryPart .= " AND task = '$task' ";
		if ($optJobStatus=="completed") $queryPart .= " AND complete = '1' ";
		else if($optJobStatus=="notcompleted") $queryPart .= " AND complete = '0' "; 
		if($optJobStatus=="")$optJobStatus="notcompleted";$queryPart .= " AND complete = '0' "; 
		if ($optJobStatus=="notcompleted") $queryPart .= " AND complete = '0' ";
		$count = DB::select(DB::raw("SELECT count(gpg_job.id) as t_id FROM gpg_job WHERE job_num LIKE 'TC%' $queryPart ORDER BY id DESC"));
		if (!empty($count) && isset($count[0]->t_id)){
			$results->totalItems = $count[0]->t_id;
		}
		if($order_by!= "" ){
			if($order_by == "zone_index"){
				$queryPart .= " order by ".$order_by." ".$order_type.",fDateWon ASC";
			}else{
				$queryPart .= " order by ".$order_by." ".$order_type;
			}
		}else{
			$queryPart .= " order by created_on DESC";
		}
		$result = DB::select(DB::raw("SELECT DATEDIFF(NOW(),created_on) AS count_days,complete,gpg_job.contract_number, gpg_job.created_on,gpg_job.id AS jobID, job_num,GPG_customer_id,(SELECT NAME FROM gpg_customer WHERE id = GPG_customer_id) AS cus_name,job_note,gpg_job.task,gpg_job.sub_task,schedule_date,date_job_scheduled_for FROM gpg_job WHERE job_num LIKE 'TC%' $queryPart limit $start,$limit"));
		$data_arr = array();
		foreach ($result as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		return $results;
	}

	/*
	* serviceJobFSWReport
	*/
	public function serviceJobFSWReport(){
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getFSWReportByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$customer_arr = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->lists('name','id');
  		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
		$zone_indexes = DB::table('gpg_settings')->where('name','LIKE','_zone_index%')->orderBy('value')->lists('value','id');	
  		$params = array('left_menu' => $modules,'customer_arr'=>$customer_arr,'query_data'=>$query_data,'salesp_arr'=>$salesp_arr,'zone_indexes'=>$zone_indexes,'FSWStatusArray'=>$this->FSWStatusArray,'totalsData'=>$data->totalsData);
		return View::make('reports.service_job_fsw_report', $params);
	}
	public function getFSWReportByPage($page = 1, $limit = null)
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
		$results->totalsData = 0;
		$QuotedSDate = Input::get("QuotedSDate");
		$QuotedEDate = Input::get("QuotedEDate");
		$WonSDate = Input::get("WonSDate");
		$WonEDate = Input::get("WonEDate");
		$PartsOrderedSDate =  Input::get("PartsOrderedSDate");
		$PartsOrderedEDate =  Input::get("PartsOrderedEDate");
		$PartsRecievedSDate =  Input::get("PartsRecievedSDate");
		$PartsRecievedEDate =  Input::get("PartsRecievedEDate");
		$PartsScheduledSDate =  Input::get("PartsScheduledSDate");
		$PartsScheduledEDate =  Input::get("PartsScheduledEDate");
		$SJobNumber = Input::get("SJobNumber");
		$EJobNumber = Input::get("EJobNumber");
		$SFSWNumber = Input::get("SFSWNumber");
		$EFSWNumber = Input::get("EFSWNumber");
		$optEmployee = Input::get("optEmployee");
		$optCustomer = Input::get("optCustomer");
		$optJobStatus = Input::get("optJobStatus");
		$FSWStatus = Input::get("FSWStatus");
		$zone_index_val = Input::get("zone_index_val");
		$order_by = Input::get("order_by");
		$order_type = Input::get("orderby_type");
		$ignore_date = Input::get("ignore_date");
		if($ignore_date=="")
			$ignore_date = 0;
		$queryPart =""; 
		if ($QuotedSDate!="" and $QuotedEDate!=""){
			$queryPart .= " AND a.created_on >= '".date('Y-m-d',strtotime($QuotedSDate))." 00:00:00' AND a.created_on <= '".date('Y-m-d',strtotime($QuotedEDate))." 23:59:59' ";
		}
		elseif ($QuotedSDate!="") $queryPart .= " AND date_format(a.created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($QuotedSDate))."'";
		if ($WonSDate!="" and $WonEDate!="") $queryPart .= " AND a.date_job_won >= '".date('Y-m-d',strtotime($WonSDate))."' AND a.date_job_won <= '".date('Y-m-d',strtotime($WonEDate))."' ";
		elseif ($WonSDate!="") $queryPart .= " AND a.date_job_won = '".date('Y-m-d',strtotime($WonSDate))."'";	 
		if ($PartsScheduledSDate!="" and $PartsScheduledEDate!="") $queryPart .= " AND b.date_job_scheduled_for >= '".date('Y-m-d',strtotime($PartsScheduledSDate))."' AND b.date_job_scheduled_for <= '".date('Y-m-d',strtotime($PartsScheduledEDate))."' ";
		elseif ($PartsScheduledSDate!="") $queryPart .= " AND b.date_job_scheduled_for = '".date('Y-m-d',strtotime($PartsScheduledSDate))."'";
		if ($PartsOrderedSDate!="" and $PartsOrderedEDate!="") $queryPart .= " AND b.date_parts_ordered >= '".date('Y-m-d',strtotime($PartsOrderedSDate))."' AND b.date_parts_ordered <= '".date('Y-m-d',strtotime($PartsOrderedEDate))."' ";
		elseif ($PartsOrderedSDate!="") $queryPart .= " AND b.date_parts_ordered = '".date('Y-m-d',strtotime($PartsOrderedSDate))."'";
		if ($PartsRecievedSDate!="" and $PartsRecievedEDate!="") $queryPart .= " AND b.date_parts_Recieved >= '".date('Y-m-d',strtotime($PartsRecievedSDate))."' AND b.date_parts_Recieved <= '".date('Y-m-d',strtotime($PartsRecievedEDate))."' ";
		elseif ($PartsRecievedSDate!="") $queryPart .= " AND b.date_parts_Recieved = '".date('Y-m-d',strtotime($PartsRecievedSDate))."'";
		if ($SJobNumber!="" and $EJobNumber!="") $queryPart .= " AND b.job_num >= '".$SJobNumber."' AND b.job_num <= '".$EJobNumber."' ";
		elseif ($SJobNumber!="") $queryPart .= " AND b.job_num like '".$SJobNumber."%'";
		if ($SFSWNumber!="" and $EFSWNumber!="") $queryPart .= " AND a.job_num >= '".$SFSWNumber."' AND a.job_num <= '".$EFSWNumber."' ";
		elseif ($SFSWNumber!="") $queryPart .= " AND a.job_num like '".$SFSWNumber."%'";
		if ($optEmployee!="" and $optEmployee!="notSeleted") $queryPart .= " AND b.GPG_employee_id = '$optEmployee' ";
		if ($optCustomer!="") $queryPart .= " AND b.GPG_customer_id = '$optCustomer' "; 
		if ($optJobStatus=="completed") $queryPart .= " AND b.complete = '1' ";
		if ($optJobStatus=="notcompleted") $queryPart .= " AND b.complete = '0' ";
		if ($FSWStatus!='') $queryPart .= " AND b.fws_status = '$FSWStatus' ";
		if($zone_index_val !='') $queryPart .= " AND zone_index_id = '$zone_index_val' ";
		if($ignore_date == 0) $queryPart .= " AND ifnull(a.date_job_won,0)<>0 ";
		$count = DB::select(DB::raw("select count(b.id) as t_id from gpg_field_service_work a LEFT JOIN gpg_job b ON a.GPG_attach_job_id = b.id where ifnull(a.GPG_attach_job_id,0)<>0 and ifnull(a.date_job_won,0)<>0 $queryPart"));
		if (!empty($count) && isset($count[0]->t_id)){
			$results->totalItems = $count[0]->t_id;
		}
		$totalsQuery = DB::select(DB::raw("select sum(ifnull(a.grand_list_total,0)) as totFQoueAmt from gpg_field_service_work a LEFT JOIN gpg_job b ON a.GPG_attach_job_id = b.id where ifnull(a.GPG_attach_job_id,0)<>0 and ifnull(a.date_job_won,0)<>0 $queryPart"));
		if (!empty($totalsQuery) && isset($totalsQuery[0]->totFQoueAmt)){
			$results->totalsData = $totalsQuery[0]->totFQoueAmt;
		}
		if($order_by!= "" ){
			if($order_by == "zone_index"){
				$queryPart .= " order by ".$order_by." ".$order_type.",fDateWon ASC";
			}else{
				$queryPart .= " order by ".$order_by." ".$order_type;
			}
		}else{
			$queryPart .= " order by fwsStatus ASC";
		}
		$data_arr = array();
		$qry = DB::select(DB::raw("select b.job_num as jobNum, zone_index_id, b.id as jobID,ifnull(b.fws_status,1000) as fwsStatus,(select name from gpg_customer where id = b.GPG_customer_id) as jobCustomer,(select name from gpg_employee where id = b.GPG_employee_id) as jobEmployee ,b.location as jobSite ,b.date_parts_recieved as jobDatePartsRecieved ,b.date_job_scheduled_for as jobDateSchduled ,b.date_parts_ordered as jobDatePartsOrderd ,a.job_num as fNum,a.grand_list_total as fQoueAmt ,a.created_on as fQouteDate ,a.date_job_won fDateWon from gpg_field_service_work a LEFT JOIN gpg_job b ON a.GPG_attach_job_id = b.id where ifnull(a.GPG_attach_job_id,0)<>0 $queryPart $limitOffset"));
		foreach ($qry as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		
		return $results;
	}

	/*
	* jobRegardingReport
	*/
	public function jobRegardingReport(){
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getJobRegardingRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'regardingArray'=>$data->MainData);
		return View::make('reports.job_regarding_report', $params);
	}
	public function getJobRegardingRepByPage($page = 1, $limit = null)
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
		$results->MainData = array();
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$SchDate = Input::get("SchDate1");
		$SchEDate = Input::get("SchEDate1");
		$queryPart = "";
		$optJobStatus = Input::get("optJobStatus");
		if ($SDate!="" and $EDate!=""){
			$queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($SDate))." 00:00:00' AND created_on <= '".date('Y-m-d',strtotime($EDate))." 23:59:59' ";
		}
		elseif ($SDate!=""){
			$queryPart .= " AND created_on <= '".date('Y-m-d',strtotime($SDate))." 23:59:59'";
		} 
		if ($optJobStatus=="completed") $queryPart .= " AND complete = '1' ";
		if ($optJobStatus=="notcompleted") $queryPart .= " AND complete = '0' ";
		if($SchDate != "" && $SchEDate != ""){
			$queryPart = " AND schedule_date >= '".date('Y-m-d'." 00:00:00",strtotime($SchDate))."' AND schedule_date <= '".date('Y-m-d'." 23:59:59",strtotime($SchEDate))."' ";
		}
		$regardingArray = array("Fuel Delivery","Coolant Flush","Fuel Polish","Fuel Sample","Load Bank Test","Permit Fine","Repair","Service Engine-Fire Pump","Service Generator");
		$regardingQuery = DB::select(DB::raw("SELECT (SELECT name FROM gpg_customer WHERE id=gpg_customer_id) AS customer,COUNT(id) AS cnt,gpg_customer_id,task FROM gpg_job WHERE gpg_job_type_id = 4 AND task IN( '".strtolower(implode("','", $regardingArray))."')  $queryPart GROUP BY gpg_customer_id,task"));
		$regardingQuery2 = DB::select(DB::raw("SELECT (SELECT name FROM gpg_customer WHERE id=gpg_customer_id) AS customer,COUNT(id) AS cnt,gpg_customer_id,task FROM gpg_job WHERE gpg_job_type_id = 4 AND task IN( '".strtolower(implode("','", $regardingArray))."')  $queryPart GROUP BY gpg_customer_id,task $limitOffset"));
		$results->totalItems = count($regardingQuery);
		$data_arr = array();
		$main_data = array();
		foreach ($regardingQuery2 as $key => $row) {
			$data_arr[$row->customer][$row->task] = array("cnt"=>$row->cnt,"cust"=>$row->gpg_customer_id);
			//$main_data[] = (array)$row;
		}
		$results->items = $data_arr;
		$results->MainData = $regardingArray;
		return $results;
	}

	/*
	* wipReport
	*/
	public function wipReport(){
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getWIPgRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$customer_arr = DB::table('gpg_customer')->select('id','name')->where('status','=','A')->orderBy('name')->lists('name','id');
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'customer_arr'=>$customer_arr,'totalsArr'=>$data->totalsArr);
		return View::make('reports.wip_report', $params);
	}
	
	public function getWIPgRepByPage($page = 1, $limit = null)
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
		$results->totalsArr = array();
		$optJobHaving=""; 
		$queryPart="";
		$queryPartInvoice="";
		$queryPartLaborCost="";
		$queryPartMaterialCost="";
		$ignoreCostDate =  Input::get("ignoreCostDate");
		$ignoreInvoiceDate =  Input::get("ignoreInvoiceDate");
		$InvoiceSDate =  urldecode(Input::get("InvoiceSDate"));
		$InvoiceEDate =  urldecode(Input::get("InvoiceEDate"));
		$CreatedSDate =  urldecode(Input::get("CreatedSDate"));
		$CreatedEDate =  urldecode(Input::get("CreatedEDate"));
		$optJobClass =  urldecode(Input::get("optJobClass"));
		$SJobNumber = urldecode(Input::get("SJobNumber"));
		$EJobNumber = urldecode(Input::get("EJobNumber"));
		$optCustomer = Input::get("optCustomer");
		$jobActivity =  Input::get("jobActivity"); 
		$optJobHaving =  (!isset($_REQUEST["optJobHaving"])?array():$_REQUEST["optJobHaving"]);
		if (!is_array($optJobHaving)) {
		    $optJobHaving = unserialize(stripslashes($optJobHaving));
		}
		if (!count($optJobHaving)) $optJobHaving = array("all");
		if ($InvoiceSDate!="" and $InvoiceEDate!="") { 
			$queryPart .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND  gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."'  order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
			if($ignoreCostDate=='' and $CreatedSDate == ''){
				$queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
				$queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
			}
			if($ignoreInvoiceDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($InvoiceSDate))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($InvoiceEDate))."' ";
			}
		}
		elseif ($InvoiceSDate!=""){ 
			$queryPart .= " AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND  gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' order by gpg_job_invoice_info.invoice_date desc limit 0,1)"; 
			if($ignoreCostDate=='' and $CreatedSDate == ''){
				$queryPartMaterialCost=" AND gpg_job_cost.date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
				$queryPartLaborCost=" AND gpg_timesheet.date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
			} 
			if($ignoreInvoiceDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($InvoiceSDate))."' ";
			} 
		} 
		if ($CreatedSDate!="" and $CreatedEDate!=""){ 
		 	$queryPart .= " AND created_on >= '".date('Y-m-d',strtotime($CreatedSDate))." 00:00:00' AND created_on <= '".date('Y-m-d',strtotime($CreatedEDate))." 23:59:59' ";
			$completeQueryPart = " AND not (date_completion >= '".date('Y-m-d',strtotime($CreatedSDate))." 00:00:00' AND date_completion <= '".date('Y-m-d',strtotime($CreatedEDate))." 23:59:59')  ";
		    if($ignoreCostDate==''){
				$queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d',strtotime($CreatedSDate))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($CreatedEDate))."' ";
				$queryPartLaborCost=" AND gpg_timesheet.date >= '".date('Y-m-d',strtotime($CreatedSDate))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($CreatedEDate))."' ";
			}
			if($ignoreInvoiceDate=='' and $InvoiceSDate==''){
				$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($CreatedSDate))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($CreatedEDate))."' ";
		 	}
		}elseif ($CreatedSDate!=""){
			$queryPart .= " AND date_format(created_on,'%Y-%m-%d') = '".date('Y-m-d',strtotime($CreatedSDate))."'";
			$completeQueryPart = " AND date_format(date_completion,'%Y-%m-%d') <> '".date('Y-m-d',strtotime($CreatedSDate))."'  ";
		    if($ignoreCostDate==''){
		 		$queryPartMaterialCost=" AND gpg_job_cost.date = '".date('Y-m-d',strtotime($CreatedSDate))."' ";
		 		$queryPartLaborCost=" AND gpg_timesheet.date = '".date('Y-m-d',strtotime($CreatedSDate))."' ";
		 	} 
		    if($ignoreInvoiceDate=='' and $InvoiceSDate==''){
		 		$queryPartInvoice = " AND gpg_job_invoice_info.invoice_date = '".date('Y-m-d',strtotime($CreatedSDate))."' ";
		 	} 
		} else {
			$completeQueryPart = " AND complete='0' ";
		}
		if ($jobActivity!="") $queryPart .= " AND ((select if(count(amount)>0,1,0) from gpg_job_cost where job_num = gpg_job.job_num limit 0,1) OR (select if(count(total_wage)>0,1,0) from gpg_timesheet_detail where GPG_job_id = gpg_job.id limit 0,1) ) ";    
		if ($optCustomer!="") $queryPart .= " AND gpg_customer_id = '$optCustomer' ";   
		if ($SJobNumber!="" and $EJobNumber!="") $queryPart .= " AND job_num >= '".$SJobNumber."' AND job_num <= '".$EJobNumber."' ";
		elseif ($SJobNumber!="") $queryPart .= " AND job_num like '".$SJobNumber."%'";
		if ($optJobClass=="service") $queryPart .= " AND GPG_job_type_id = '4'";
		if ($optJobClass=="electrical") $queryPart .= " AND GPG_job_type_id = '5'";
		if (in_array("all",$optJobHaving)) $queryPart .= ""; 
		if (in_array("laborCost",$optJobHaving)) $queryPart .= " AND (select if(count(total_wage)>0,1,0) from gpg_timesheet_detail where GPG_job_id = gpg_job.id limit 0,1) ";
		if (in_array("materialCost",$optJobHaving)) $queryPart .= " AND (select if(count(amount)>0,1,0) from gpg_job_cost where job_num = gpg_job.job_num limit 0,1)  ";
		$queryPart .= " order by created_on desc"; 
		$count = DB::select(DB::raw("select count(id) as t_id from gpg_job where GPG_job_type_id IN (4,5) and complete='0' AND job_num not like 'VS%' AND if(job_num like 'RNT%',(select if (invoice_amount,0,1) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id order by invoice_amount desc limit 0,1),1) AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_number not like 'Non Billable' limit 0,1)  $queryPart"));
		if (!empty($count) && isset($count[0]->t_id)){
			$results->totalItems = $count[0]->t_id;
		}
		$data_arr = array();
		$qry = DB::select(DB::raw("select *,(select sum(invoice_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as inv_amount,(select name from gpg_customer where id = gpg_customer_id) as customer_name,(select sum(total_wage) from gpg_timesheet_detail , gpg_timesheet where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num $queryPartLaborCost) as labor_cost , (select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost) as material_cost from gpg_job where GPG_job_type_id  IN (4,5) $completeQueryPart AND job_num not like 'VS%' AND if(job_num like 'RNT%',(select if (invoice_amount,0,1) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id order by invoice_amount desc limit 0,1),1) AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_number not like 'Non Billable' limit 0,1)  $queryPart $limitOffset"));
		foreach ($qry as $key => $value){
			$data_arr[] = (array)$value;
		}
		$getWip = DB::select(DB::raw("select *,(select sum(invoice_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as inv_amount,(select name from gpg_customer where id = gpg_customer_id) as customer_name,(select sum(total_wage) from gpg_timesheet_detail , gpg_timesheet where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num $queryPartLaborCost) as labor_cost , (select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost) as material_cost from gpg_job where GPG_job_type_id  IN (4,5) $completeQueryPart AND job_num not like 'VS%' AND if(job_num like 'RNT%',(select if (invoice_amount,0,1) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id order by invoice_amount desc limit 0,1),1) AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_number not like 'Non Billable' limit 0,1)  AND job_num not like 'VS%' AND if(job_num like 'RNT%',(select if (invoice_amount,0,1) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id order by invoice_amount desc limit 0,1),1) AND gpg_job.id = (select gpg_job_invoice_info.gpg_job_id from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id AND gpg_job_invoice_info.invoice_number not like 'Non Billable' limit 0,1)  $queryPart"));
		$totals = array();
		foreach ($getWip as $key => $value) {
			$totals[] = (array)$value;
		} 
		$results->totalsArr = $totals;
		$results->items = $data_arr;
		return $results;
	}

	/*
	*contractComparisonReport
	*/
	public function contractComparisonReport(){
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getCCRByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'yearFirstResult'=>$data->items,'yearSecondArray'=>$data->secondItems);
		return View::make('reports.contract_comparison_report', $params);
	}
	public function getCCRByPage($page = 1, $limit = null)
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
		$results->secondItems = array();
		$yearSecondArray = array();
		if(!empty($_REQUEST["month"]))
			$month = $_REQUEST["month"];
		else
			$month = date('m');
		if(!empty($_REQUEST["SYear"]))
			$SYear = $_REQUEST["SYear"];
		else
			$SYear = date('Y')-1;
		if(!empty($_REQUEST["EYear"]))
			$EYear = $_REQUEST["EYear"];
		else
			$EYear = date('Y');
	    $queryYearFirst = "SELECT a.id,a.job_num,a.contract_number ,sum(b.invoice_amount) as invoice_amt,(SELECT name from gpg_customer where id = a.GPG_customer_id) as customer 
		FROM gpg_job a , gpg_job_invoice_info b WHERE a.id = b.gpg_job_id AND IFNULL(a.contract_number,'')<>'' AND YEAR(b.invoice_date)= '$SYear' AND MONTH(b.invoice_date)= '$month' GROUP BY a.contract_number  ORDER BY a.contract_number";
		$queryYearSecond = "SELECT a.id,a.job_num,a.contract_number,(SELECT SUM(invoice_amount) FROM gpg_job_invoice_info where gpg_job_id = a.id ) as invoice_amt,(SELECT name from gpg_customer where id = a.GPG_customer_id) as customer  FROM gpg_job a  WHERE IFNULL(a.contract_number,'')<>'' AND YEAR(a.created_on)= '$EYear' AND MONTH(a.created_on)= '$month' 
		GROUP BY a.contract_number ORDER BY a.contract_number";

		$yearFirstResult = DB::select(DB::raw($queryYearFirst));
		$yearSecondResult = DB::select(DB::raw($queryYearSecond));
		$yearSecondRow = array();
		foreach ($yearSecondResult as $key => $value){
			$yearSecondRow = (array)$value;
			$yearSecondArray[$yearSecondRow['contract_number']]['contractNum'] = $yearSecondRow['contract_number'];
			$yearSecondArray[$yearSecondRow['contract_number']]['jobNum'] = $yearSecondRow['job_num'];
			$yearSecondArray[$yearSecondRow['contract_number']]['invoiceAmt'] = $yearSecondRow['invoice_amt']; 
			$yearSecondArray[$yearSecondRow['contract_number']]['customer'] = $yearSecondRow['customer']; 
		}
		$data_arr = array();
		foreach ($yearFirstResult as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$results->items = $data_arr;
		$results->secondItems = $yearSecondRow;
		return $results;
	}

	/*
	* completedServiceJobsReport
	*/
	public function completedServiceJobsReport(){
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getCSJRByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'invoiceTotals'=>$data->totals);
		return View::make('reports.completed_service_jobs_report', $params);
	}
	public function getCSJRByPage($page = 1, $limit = null)
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
		$results->totals = array();
		$SDate = Input::get("SDate");
		$EDate = Input::get("EDate");
		$elecJobCheck = Input::get("elecJobCheck");
		$grassivyJobCheck = Input::get("grassivyJobCheck");
		$specialProjectJobCheck = Input::get("specialProjectJobCheck");
		$serviceJobCheck = Input::get("serviceJobCheck");
		$shopJobCheck = Input::get("shopJobCheck");
		$rentalJobCheck = Input::get("rentalJobCheck");
		$billonlyJobCheck = Input::get("billonlyJobCheck"); 
		$job_num = Input::get("jobNum");
		$check_box = Input::get('check_box');
		$invoiceAmountTotal = 0;
		$DSQL = "";
		$DQ2 = " order by date_completion desc ";
		$queryPart = "";
		if ($SDate!="" || $EDate!="") {
		    if ($SDate!="" && $EDate =="") {
			  $DSQL.= " AND DATE_FORMAT(date_completion,'%Y-%m-%d') = '".date('Y-m-d',strtotime($SDate))."'";
			} elseif ($SDate == "" && $EDate != "") {
			  $DSQL.= " AND date_completion <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."'";
			} elseif ($SDate != "" && $EDate != "") {
			  $DSQL.= " AND (date_completion >= '".date('Y-m-d'." 00:00:00",strtotime($SDate))."'
			            AND date_completion <= '".date('Y-m-d'." 23:59:59",strtotime($EDate))."')";
			}
		}
		if($shopJobCheck == "1"){
			$queryPart .= $queryPart==""?"":" OR ";
			$queryPart .= "job_num like 'SH%' ";
		}
		if($rentalJobCheck=="1"){
			$queryPart .= $queryPart==""?"":" OR ";
			$queryPart .= "job_num like 'RNT%' ";
		}
		if($elecJobCheck=="1"){
			$queryPart .= $queryPart==""?"":" OR ";
			$queryPart .= "job_num like 'GPG%' ";
		}
		if($grassivyJobCheck=="1"){
			$queryPart .= $queryPart==""?"":" OR ";
			$queryPart .= "job_num like 'IG%' ";
		}
		if($specialProjectJobCheck=="1"){
			$queryPart .= $queryPart==""?"":" OR ";
			$queryPart .= "job_num like 'LK%' ";
		}
		if($serviceJobCheck=="1"){
			$queryPart .= $queryPart==""?"":" OR ";
			$queryPart .= "job_num like 'PM%' or job_num like 'QT%' or job_num like 'TC%' ";
		}
		if($billonlyJobCheck =="1"){
			$queryPart .= $queryPart==""?"":" OR ";
			$queryPart .= "job_num like 'BO%' ";
		}
		if($queryPart!=""){
			$queryPart = " AND ( ".$queryPart." ) ";
		}
		if($job_num != ''){
			$queryPart .= " AND job_num like '".$job_num."%' ";
		}
		if(!isset($check_box)){
			$check_box = 1;
		}else if($check_box==1){
			$check_box = 0;
		}
		$count = DB::select(DB::raw("select count(id) as t_id from gpg_job where complete='1' ".$queryPart.$DSQL));
		if (!empty($count) && isset($count[0]->t_id)) {
			$results->totalItems = $count[0]->t_id;
		}
		$sql_query = DB::select(DB::raw("select *, (select name from gpg_customer where id = gpg_customer_id) as customer_name, (select concat(invoice_number,'#~#',sum(invoice_amount),'#~#',invoice_date,'#~#',count(id)) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id group by gpg_job_invoice_info.gpg_job_id) as invoice_data from gpg_job where complete='1' ".$queryPart." $DSQL $DQ2 $limitOffset"));
		$totals_query = DB::select(DB::raw("SELECT SUM(contract_amount) AS contract_amount_total,
					  	SUM((SELECT
					    SUM(invoice_amount)
					    FROM gpg_job_invoice_info
					    WHERE gpg_job_invoice_info.gpg_job_id = gpg_job.id
					    GROUP BY gpg_job_invoice_info.gpg_job_id)) AS invoice_total
					 	from gpg_job where complete='1' ".$queryPart." $DSQL $DQ2"));
		$data_arr = array();
		foreach ($sql_query as $key => $value) {
			$data_arr[] = (array)$value;
		}
		$totals_data = array();
		foreach ($totals_query as $key => $value) {
			$totals_data = (array)$value;
		}
		$results->items = $data_arr;
		$results->totals = $totals_data;
		return $results;
	}

	/*
	* invoiceAmtReport
	*/
	public function invoiceAmtReport(){
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getInvAmtRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'service_jobs_row'=>$data->arr1,'elec_jobs_row'=>$data->arr2,'grassivy_jobs_row'=>$data->arr3,'special_project_jobs_row'=>$data->arr4);
		return View::make('reports.invoice_amt_report', $params);
	}
	public function getInvAmtRepByPage($page = 1, $limit = null)
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
		$results->arr1 = array();
		$results->arr2 = array();
		$results->arr3 = array();
		$results->arr4 = array();
		$m = Input::get('m');
        if(empty($m))
            $m = date('m');
        $y = Input::get('y');
        if(empty($y))
            $y = date('Y');
        $prevY = $y;
        $nextY = $y;
        if ($m<=1) { $prevM=12; $prevY--; }
        else $prevM = $m-1;
        if ($m>=12) { $nextM=1; $nextY++; }
        else $nextM = $m+1;                                   
        $currentDate = $m.'/01/'.$y;
        $check_date= Input::get("check_date");			
        if($check_date!=1){
			$SDateScheduleService = "SDate2";
			$EDateScheduleService = "EDate2";
			$SDateScheduleElectrical = "CreatedSDate";
			$EDateScheduleElectrical = "CreatedEDate";
		}else{
			$SDateScheduleService = "InvoiceSDate";
			$EDateScheduleService = "InvoiceEDate";
			$SDateScheduleElectrical = "InvoiceSDate";
			$EDateScheduleElectrical = "InvoiceEDate";
		}
		$curMonth = date('m',strtotime($currentDate));
		$curMonthCap = date('M',strtotime($currentDate));
		$curYear = date('Y',strtotime($currentDate));
		$preYear = $curYear-1;
		if($check_date!=1){
			$queryPart ="AND complete = '1' AND Year(created_on)<='$preYear' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id limit 0,1)";
		}else{
			$queryPart ="AND complete = '1' AND (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id AND Year(invoice_date)<='$preYear' limit 0,1) ";
		}
		$ServiceJobsQuery = DB::select(DB::raw("Select count(id) as count_service, (select min(gpg_job_invoice_info.invoice_date) from gpg_job_invoice_info) as min_invoice_date, (select min(created_on)) as min_created_on from gpg_job where gpg_job_type_id='4' and job_num not like 'RNT%' $queryPart"));
		$service_jobs_row = array();
		foreach ($ServiceJobsQuery as $key => $value){
			$service_jobs_row = (array)$value;
		}
		if($check_date!=1){
			$SDate = $service_jobs_row['min_created_on'];
		}else{
			$SDate = $service_jobs_row['min_invoice_date'];
		}
		$EDate= ($y-1).'-12-31';
		$results->arr1 = $service_jobs_row;
		////////////////////////////////////////////
		$ElecJobsQuery = DB::select(DB::raw("Select count(id) as count_elec, (select min(invoice_date)) as min_invoice_date, (select min(created_on)) as min_created_on from gpg_job where job_num like 'GPG%' and GPG_job_type_id=5 $queryPart"));
		$elec_jobs_row=array();
		foreach ($ElecJobsQuery as $key => $value) {
			$elec_jobs_row = (array)$value;
		}
		if($check_date!=1){
			$SDate1 = $elec_jobs_row['min_created_on'];
		}else{
			$SDate1 = $elec_jobs_row['min_invoice_date'];
		}
			$EDate1 = ($y-1).'-12-31';
		$results->arr2 = $elec_jobs_row;
		//////////////////////////////////////////////
		$GrassivyJobsQuery = DB::select(DB::raw("Select count(id) as count_elec, (select min(invoice_date)) as min_invoice_date, (select min(created_on)) as min_created_on from gpg_job where job_num like 'IG%' and GPG_job_type_id=5 $queryPart"));
		$grassivy_jobs_row = array();
		foreach ($GrassivyJobsQuery as $key => $value) {
			$grassivy_jobs_row = (array)$value; 
		}
		if($check_date!=1){
			$SDate1 = $grassivy_jobs_row['min_created_on'];
		}else{
			$SDate1 = $grassivy_jobs_row['min_invoice_date'];
		}
		$EDate1 = ($y-1).'-12-31';
		$results->arr3 = $grassivy_jobs_row;
		/////////////////////////////////////////////////
		$SpecialProjectJobsQuery= DB::select(DB::raw("Select count(id) as count_elec, (select min(invoice_date)) as min_invoice_date, (select min(created_on)) as min_created_on from gpg_job where job_num like 'LK%' and GPG_job_type_id=5 $queryPart"));
		$special_project_jobs_row = array();
		foreach ($SpecialProjectJobsQuery as $key => $value) {
			$special_project_jobs_row = (array)$value;
		}
		if($check_date!=1){
			$SDate1 = $special_project_jobs_row['min_created_on'];
		}else{
			$SDate1 = $special_project_jobs_row['min_invoice_date'];
		}
		$EDate1 = ($y-1).'-12-31';
		$results->arr4 = $special_project_jobs_row;
		return $results;
	}
	/*
	* financialReportSummary
	*/
	public function financialReportSummary(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getfinRepSumryByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
		$salesp_arr = array(''=>'ALL');
		foreach ($salesPerson as $key => $value)
				$salesp_arr[$value->id] = $value->name;
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'salesp_arr'=>$salesp_arr,'valuesMonth'=>$data->items);
		return View::make('reports.financial_report_summary', $params);
	}
	public function getfinRepSumryByPage($page = 1, $limit = null)
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
		$reportYear = Input::get('y');
        if(empty($reportYear))
            $reportYear = date('Y');
        	$prevY = $reportYear;
			$nextY = $reportYear;
			$totElecRev = '';
			$totElecCost = '';
			$totElecGross = '';
			$totSerRev = '';
			$totSerCost = '';
			$totSerGross = '';
			$totRntRev = '';
			$totRntCost = '';
			$totRntGross = '';
			$totShopRev = '';
			$totShopCost = '';
			$totShopGross = '';
			for($i=1;$i<=12;$i++) //main foreach
			{
				$queryPart = " AND (select if(gpg_job_invoice_info.gpg_job_id,1,0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id  AND gpg_job_invoice_info.invoice_date  >= '$reportYear-$i-01' AND gpg_job_invoice_info.invoice_date  <= '".date('Y-m-d',strtotime($this->check_date($i,$reportYear)))."' order by gpg_job_invoice_info.invoice_date desc limit 0,1)";
				$timesheetQueryPart = " AND gpg_timesheet.date >= '".date('Y-m-d',strtotime("$reportYear-$i-01"))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($this->check_date($i,$reportYear)))."' ";
				$materialQueryPart = " AND gpg_job_cost.date >= '".date('Y-m-d',strtotime("$reportYear-$i-01"))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($this->check_date($i,$reportYear)))."' ";
				$invoiceQueryPart = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime("$reportYear-$i-01"))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($this->check_date($i,$reportYear)))."' ";
				if(!empty($optEmployee)){
					$queryPart .= " AND gpg_job.GPG_employee_id = '$optEmployee'"; 
				}
				$queryPart .= " order by job_num desc"; 
		 		$getSales = DB::select(DB::raw("select  
		 		sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num like ('SH%') and SUBSTRING(gpg_job.job_num,3)*1 >= 100000  $invoiceQueryPart group by gpg_job_invoice_info.gpg_job_id)) as shop_invoice_data,
		 		sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num like ('RNT%')  $invoiceQueryPart group by gpg_job_invoice_info.gpg_job_id)) as rnt_invoice_data,
		 		sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num like ('GPG%')  $invoiceQueryPart group by gpg_job_invoice_info.gpg_job_id)) as gpg_invoice_data,
		 		sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num not like ('RNT%') and gpg_job.job_num not like ('SH1_____%') and gpg_job.job_num not like ('GPG%') and gpg_job.job_num not like ('IG%') and gpg_job.job_num not like ('LK%')  $invoiceQueryPart group by gpg_job_invoice_info.gpg_job_id)) as ser_invoice_data,
		  		sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num like ('SH%') and SUBSTRING(gpg_job.job_num,3)*1 >= 100000 $timesheetQueryPart)) as shopLaborCost ,
		 		sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num like ('RNT%') $timesheetQueryPart)) as rntLaborCost ,
		 		sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num like ('GPG%') $timesheetQueryPart)) as gpgLaborCost ,
		 		sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num not like ('RNT%') and gpg_job.job_num not like ('SH1_____%') and gpg_job.job_num not like ('GPG%') and gpg_job.job_num not like ('IG%') and gpg_job.job_num not like ('LK%') $timesheetQueryPart)) as serLaborCost ,
		 		sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num =  gpg_job.job_num  and gpg_job.job_num like ('SH%') and SUBSTRING(gpg_job.job_num,3)*1 >= 100000 $materialQueryPart)) as shopMaterialCost ,
		 		sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num =  gpg_job.job_num  and gpg_job.job_num like ('RNT%') $materialQueryPart)) as rntMaterialCost ,
		 		sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num = gpg_job.job_num and gpg_job.job_num like ('GPG%')  $materialQueryPart)) as gpgMaterialCost ,
		  		sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num =  gpg_job.job_num and gpg_job.job_num not like ('RNT%') and gpg_job.job_num not like ('SH1_____%') and gpg_job.job_num not like ('GPG%') and gpg_job.job_num not like ('IG%') and gpg_job.job_num not like ('LK%')  $materialQueryPart)) as serMaterialCost,
		 		sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num like ('IG%')  $invoiceQueryPart group by gpg_job_invoice_info.gpg_job_id)) as grassivy_invoice_data,
		  		sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num = gpg_job.job_num and gpg_job.job_num like ('IG%')  $materialQueryPart)) as grassivyMaterialCost,
		 		sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num like ('IG%') $timesheetQueryPart)) as grassivyLaborCost,
		 		sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num like ('LK%')  $invoiceQueryPart group by gpg_job_invoice_info.gpg_job_id)) as special_project_invoice_data,
		  		sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num = gpg_job.job_num and gpg_job.job_num like ('LK%')  $materialQueryPart)) as specialProjectMaterialCost,
		 		sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num like ('LK%') $timesheetQueryPart)) as specialProjectLaborCost
		  		from gpg_job where 1  $queryPart"));
				$getRow = array();
				foreach ($getSales as $key => $value) {
					$getRow = (array)$value;
				}
				$valuesMonth['label_1'] = 'Electrical Revenue'; 
				$valuesMonth['label_2'] = 'Cost of Sale'; 
				$valuesMonth['label_3'] = 'Gross Sales'; 
				$valuesMonth['label_4'] = 'Service Revenue'; 
				$valuesMonth['label_5'] = 'Cost of Sale'; 
				$valuesMonth['label_6'] = 'Gross Sales'; 
				$valuesMonth['label_7'] = 'Rental Revenue'; 
				$valuesMonth['label_8'] = 'Cost of Sale'; 
				$valuesMonth['label_9'] = 'Gross Sales';
				$valuesMonth['label_10'] = 'Shop Revenue'; 
				$valuesMonth['label_11'] = 'Cost of Sale'; 
				$valuesMonth['label_12'] = 'Gross Sales'; 
				$valuesMonth['label_13'] = 'Grassivy Revenue'; 
				$valuesMonth['label_14'] = 'Cost of Sale'; 
				$valuesMonth['label_15'] = 'Gross Sales';
				$valuesMonth['label_16'] = 'Special Project Revenue'; 
				$valuesMonth['label_17'] = 'Cost of Sale'; 
				$valuesMonth['label_18'] = 'Gross Sales';  
				$valuesMonth['label_19'] = 'Revenue'; 
				$valuesMonth['label_20'] = 'Cost Of Sale'; 
				$valuesMonth['label_21'] = 'Gross Sales';
				$valuesMonth['ref_1'] = 'Elec~rev'; 
				$valuesMonth['ref_2'] = 'Elec~cost'; 
				$valuesMonth['ref_3'] = 'Elec~gross';
				$valuesMonth['ref_4'] = 'Ser~rev'; 
				$valuesMonth['ref_5'] = 'Ser~cost'; 
				$valuesMonth['ref_6'] = 'Ser~gross';
				$valuesMonth['ref_7'] = 'Rnt~rev'; 
				$valuesMonth['ref_8'] = 'Rnt~cost'; 
				$valuesMonth['ref_9'] = 'Rnt~gross';
				$valuesMonth['ref_10'] = 'Shop~rev'; 
				$valuesMonth['ref_11'] = 'Shop~cost'; 
				$valuesMonth['ref_12'] = 'Shop~gross';
				$valuesMonth['ref_13'] = 'Grassivy~rev'; 
				$valuesMonth['ref_14'] = 'Grassivy~cost'; 
				$valuesMonth['ref_15'] = 'Grassivy~gross';
				$valuesMonth['ref_16'] = 'SpecialProject~rev'; 
				$valuesMonth['ref_17'] = 'SpecialProject~cost'; 
				$valuesMonth['ref_18'] = 'SpecialProject~gross';
				$valuesMonth['ref_19'] = 'Total~rev'; 
				$valuesMonth['ref_20'] = 'Total~cost'; 
				$valuesMonth['ref_21'] = 'Total~gross';
				$valuesMonth[$i]['Elec']['rev'] = $getRow['gpg_invoice_data'];
				$valuesMonth[$i]['Elec']['cost'] = $gpgcostOfSale = $getRow['gpgMaterialCost'] + $getRow['gpgLaborCost'];
				$valuesMonth[$i]['Elec']['gross'] = $elecGross = $getRow['gpg_invoice_data'] - $gpgcostOfSale;
				$valuesMonth[$i]['Ser']['rev'] = $getRow['ser_invoice_data'];
				$valuesMonth[$i]['Ser']['cost'] = $sercostOfSale =  $getRow['serMaterialCost'] + $getRow['serLaborCost'];
				$valuesMonth[$i]['Ser']['gross'] = $serGross =  $getRow['ser_invoice_data'] - $sercostOfSale;
				$valuesMonth[$i]['Rnt']['rev'] = $getRow['rnt_invoice_data'];
				$valuesMonth[$i]['Rnt']['cost'] = $rntcostOfSale = $getRow['rntMaterialCost'] + $getRow['rntLaborCost'];
				$valuesMonth[$i]['Rnt']['gross'] = $rntGross = $getRow['rnt_invoice_data'] - $rntcostOfSale;
				$valuesMonth[$i]['Shop']['rev'] = $getRow['shop_invoice_data'];
				$valuesMonth[$i]['Shop']['cost'] = $shopcostOfSale = $getRow['shopMaterialCost'] + $getRow['shopLaborCost'];
				$valuesMonth[$i]['Shop']['gross'] = $shopGross = $getRow['shop_invoice_data'] - $shopcostOfSale;
				$valuesMonth[$i]['Grassivy']['rev'] = $getRow['grassivy_invoice_data'];
				$valuesMonth[$i]['Grassivy']['cost'] = $grassivycostOfSale = $getRow['grassivyMaterialCost'] + $getRow['grassivyLaborCost'];
				$valuesMonth[$i]['Grassivy']['gross'] = $grassivyGross = $getRow['grassivy_invoice_data'] - $grassivycostOfSale;
				$valuesMonth[$i]['SpecialProject']['rev'] = $getRow['special_project_invoice_data'];
				$valuesMonth[$i]['SpecialProject']['cost'] = $specialprojectcostOfSale = $getRow['specialProjectMaterialCost'] + $getRow['specialProjectLaborCost'];
				$valuesMonth[$i]['SpecialProject']['gross'] = $specialProjectGross = $getRow['special_project_invoice_data'] - $specialprojectcostOfSale;
				if (!isset($valuesMonth[$i]['Total']['rev']))
					$valuesMonth[$i]['Total']['rev'] = 0;
				if (!isset($valuesMonth[$i]['Total']['cost']))
					$valuesMonth[$i]['Total']['cost'] = 0;
				if (!isset($valuesMonth[$i]['Total']['gross']))
					$valuesMonth[$i]['Total']['gross'] = 0;
				$valuesMonth[$i]['Total']['rev'] += ($getRow['gpg_invoice_data']+$getRow['ser_invoice_data']+$getRow['rnt_invoice_data']+$getRow['shop_invoice_data'] + $getRow['grassivy_invoice_data'] + $getRow['special_project_invoice_data']);
				$valuesMonth[$i]['Total']['cost'] += ($gpgcostOfSale+$sercostOfSale+$rntcostOfSale+$shopcostOfSale+$grassivycostOfSale+$specialprojectcostOfSale);
				$valuesMonth[$i]['Total']['gross'] += ($elecGross+$serGross+$rntGross+$shopGross+$grassivyGross+$specialProjectGross);
				if($i==12){
					$queryPart1 = " AND (select if(gpg_job_invoice_info.gpg_job_id,1,0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id= gpg_job.id  AND gpg_job_invoice_info.invoice_date  >= '$reportYear-01-01' AND gpg_job_invoice_info.invoice_date  <= '".date('Y-m-d',strtotime($this->check_date($i,$reportYear)))."' order by gpg_job_invoice_info.invoice_date desc limit 0,1)";
				    $timesheetQueryPart1 = " AND gpg_timesheet.date >= '".date('Y-m-d',strtotime("$reportYear-01-01"))."' AND gpg_timesheet.date <= '".date('Y-m-d',strtotime($this->check_date($i,$reportYear)))."' ";
					$materialQueryPart1 = " AND gpg_job_cost.date >= '".date('Y-m-d',strtotime("$reportYear-01-01"))."' AND gpg_job_cost.date <= '".date('Y-m-d',strtotime($this->check_date($i,$reportYear)))."' ";
					$invoiceQueryPart1 = " AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime("$reportYear-01-01"))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($this->check_date($i,$reportYear)))."' ";
					if(!empty($optEmployee)){
						$queryPart1 .= " AND gpg_job.GPG_employee_id = '$optEmployee'"; 
					}
					$queryPart1 .= " order by job_num desc";
					$yearTotalQuery = DB::select(DB::raw("select  
 					sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num like ('SH%') and SUBSTRING(gpg_job.job_num,3)*1 >= 100000  $invoiceQueryPart1 group by gpg_job_invoice_info.gpg_job_id)) as shop_invoice_data,
					sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num like ('RNT%')  $invoiceQueryPart1 group by gpg_job_invoice_info.gpg_job_id)) as rnt_invoice_data,
					sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num like ('GPG%')  $invoiceQueryPart1 group by gpg_job_invoice_info.gpg_job_id)) as gpg_invoice_data,
					sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num not like ('RNT%') and gpg_job.job_num not like ('SH1_____%') and gpg_job.job_num not like ('GPG%') and gpg_job.job_num not like ('IG%') and gpg_job.job_num not like ('LK%')  $invoiceQueryPart1 group by gpg_job_invoice_info.gpg_job_id)) as ser_invoice_data,
					sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num like ('SH%') and SUBSTRING(gpg_job.job_num,3)*1 >= 100000 $timesheetQueryPart1)) as shopLaborCost ,
					sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num like ('RNT%') $timesheetQueryPart1)) as rntLaborCost ,
					sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num like ('GPG%') $timesheetQueryPart1)) as gpgLaborCost ,
					sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num not like ('RNT%') and gpg_job.job_num not like ('SH1_____%') and gpg_job.job_num not like ('GPG%') and gpg_job.job_num not like ('IG%') and gpg_job.job_num not like ('LK%') $timesheetQueryPart1)) as serLaborCost ,
					sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num =  gpg_job.job_num  and gpg_job.job_num like ('SH%') and SUBSTRING(gpg_job.job_num,3)*1 >= 100000 $materialQueryPart1)) as shopMaterialCost ,
					sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num =  gpg_job.job_num  and gpg_job.job_num like ('RNT%') $materialQueryPart1)) as rntMaterialCost ,
					sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num = gpg_job.job_num and gpg_job.job_num like ('GPG%')  $materialQueryPart1)) as gpgMaterialCost ,
					sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num =  gpg_job.job_num and gpg_job.job_num not like ('RNT%') and gpg_job.job_num not like ('SH1_____%') and gpg_job.job_num not like ('GPG%') and gpg_job.job_num not like ('IG%') and gpg_job.job_num not like ('LK%')  $materialQueryPart1)) as serMaterialCost,
					sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num like ('IG%')  $invoiceQueryPart1 group by gpg_job_invoice_info.gpg_job_id)) as grassivy_invoice_data ,
					sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num = gpg_job.job_num and gpg_job.job_num like ('IG%')  $materialQueryPart1)) as grassivyMaterialCost,
					sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num like ('IG%') $timesheetQueryPart1)) as grassivyLaborCost,
					sum((select if(invoice_amount > 0 ,sum(invoice_amount-tax_amount),0) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id and gpg_job.job_num like ('LK%')  $invoiceQueryPart1 group by gpg_job_invoice_info.gpg_job_id)) as special_project_invoice_data ,
					sum((select sum(ifnull(amount,0)) from gpg_job_cost where gpg_job_cost.job_num = gpg_job.job_num and gpg_job.job_num like ('LK%')  $materialQueryPart1)) as specialProjectMaterialCost,
					sum((select sum(ifnull(total_wage,0)) from gpg_timesheet,gpg_timesheet_detail where gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id and gpg_timesheet_detail.job_num = gpg_job.job_num and gpg_job.job_num like ('LK%') $timesheetQueryPart1)) as specialProjectLaborCost
					from gpg_job where 1  $queryPart1"));
					$yearTotalRow = array();
					foreach ($yearTotalQuery as $key => $value) {
						$yearTotalRow = (array)$value;
					}
					$valuesMonth[$i]['Elec']['year']['rev'] = $yearTotalRow['gpg_invoice_data'];
					$valuesMonth[$i]['Elec']['year']['cost'] = $gpgYearCostOfSale = $yearTotalRow['gpgMaterialCost'] + $yearTotalRow['gpgLaborCost'];
					$valuesMonth[$i]['Elec']['year']['gross']  = $elecYearGross = $yearTotalRow['gpg_invoice_data'] - $gpgYearCostOfSale;
					$valuesMonth[$i]['Ser']['year']['rev'] = $yearTotalRow['ser_invoice_data'];
					$valuesMonth[$i]['Ser']['year']['cost'] = $serYearCostOfSale = $yearTotalRow['serMaterialCost'] + $yearTotalRow['serLaborCost'];
					$valuesMonth[$i]['Ser']['year']['gross']  = $serYearGross = $yearTotalRow['ser_invoice_data'] - $serYearCostOfSale;
					$valuesMonth[$i]['Rnt']['year']['rev'] = $yearTotalRow['rnt_invoice_data'];
					$valuesMonth[$i]['Rnt']['year']['cost'] = $rntYearCostOfSale = $yearTotalRow['rntMaterialCost'] + $yearTotalRow['rntLaborCost'];
					$valuesMonth[$i]['Rnt']['year']['gross']  = $rntYearGross = $yearTotalRow['rnt_invoice_data'] - $rntYearCostOfSale;
					$valuesMonth[$i]['Shop']['year']['rev'] = $yearTotalRow['shop_invoice_data'];
					$valuesMonth[$i]['Shop']['year']['cost'] = $shopYearCostOfSale = $yearTotalRow['shopMaterialCost'] + $yearTotalRow['shopLaborCost'];
					$valuesMonth[$i]['Shop']['year']['gross']  = $shopYearGross = $yearTotalRow['shop_invoice_data'] - $shopYearCostOfSale;
					$valuesMonth[$i]['Grassivy']['year']['rev'] = $yearTotalRow['grassivy_invoice_data'];
					$valuesMonth[$i]['Grassivy']['year']['cost'] = $grassivyYearCostOfSale = $yearTotalRow['grassivyMaterialCost'] + $yearTotalRow['grassivyLaborCost'];
					$valuesMonth[$i]['Grassivy']['year']['gross']  = $grassivyYearGross = $yearTotalRow['grassivy_invoice_data'] - $grassivyYearCostOfSale;
					$valuesMonth[$i]['SpecialProject']['year']['rev'] = $yearTotalRow['special_project_invoice_data'];
					$valuesMonth[$i]['SpecialProject']['year']['cost'] = $specialProjectYearCostOfSale = $yearTotalRow['specialProjectMaterialCost'] + $yearTotalRow['specialProjectLaborCost'];
					$valuesMonth[$i]['SpecialProject']['year']['gross']  = $specialProjectYearGross = $yearTotalRow['special_project_invoice_data'] - $specialProjectYearCostOfSale;
					if (!isset($valuesMonth[$i]['Total']['year']['rev']))
						$valuesMonth[$i]['Total']['year']['rev'] =0;
					if (!isset($valuesMonth[$i]['Total']['year']['cost']))
						$valuesMonth[$i]['Total']['year']['cost'] =0;
					if (!isset($valuesMonth[$i]['Total']['year']['gross']))
						$valuesMonth[$i]['Total']['year']['gross'] =0;				
					$valuesMonth[$i]['Total']['year']['rev'] += ($yearTotalRow['gpg_invoice_data']+$yearTotalRow['ser_invoice_data']+$yearTotalRow['rnt_invoice_data']+$yearTotalRow['shop_invoice_data'] +$yearTotalRow['grassivy_invoice_data'] + $yearTotalRow['special_project_invoice_data']);
					$valuesMonth[$i]['Total']['year']['cost'] += ($gpgYearCostOfSale+$serYearCostOfSale+$rntYearCostOfSale+$shopYearCostOfSale+$grassivyYearCostOfSale+$specialProjectYearCostOfSale);
					$valuesMonth[$i]['Total']['year']['gross'] += ($elecYearGross+$serYearGross+$rntYearGross+$shopYearGross+$grassivyYearGross+$specialProjectYearGross);	
				}//end if 12
 			}// end main foreach
 		$results->items = $valuesMonth;
		return $results;
	}

	/*
	* costDuplicationReport
	*/
	public function costDuplicationReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getCostDupRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data);
		return View::make('reports.cost_duplication_report', $params);
	}
	public function getCostDupRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$queryPart="";
		$queryHavingPart ="";
		$SelParam = Input::get("SelParam");
		if($SelParam=='') {
			$SelParam[0]='name';
			$SelParam[1]='source_name';
			$SelParam[2]='amount';
		}
		if(isset($SelParam) && $SelParam!=''){
			foreach ($SelParam as $val){
				$queryPart .= $val.',';
			}
			$queryPart = substr($queryPart,0,strlen($queryPart)-1);
			foreach ($SelParam as $val){
					$queryHavingPart .= "count($val)>1";
					$queryHavingPart .=' and ';
			}
			$queryHavingPart = substr($queryHavingPart,0,strlen($queryHavingPart)-5);
			$duplicateVal = DB::select(DB::raw("SELECT $queryPart FROM gpg_job_cost GROUP BY  $queryPart HAVING  $queryHavingPart order by date DESC"));
		}
		$fg = false;
		$chk='0';
		$main_arr = array();
		if(isset($duplicateVal)){
			foreach ($duplicateVal as $value){
				$param = (array)$value;
				$reportQuery = '';
				while (list($ke,$vl)= each($param)) {
					$reportQuery.=substr($ke,0,strlen($ke))."='".preg_replace("/'/", "\&#39;", $vl)."' and ";
			  	}
		  		$reportQuery = substr($reportQuery,0,strlen($reportQuery)-5);
		 		$duplicateRes = DB::select(DB::raw("select * from gpg_job_cost where $reportQuery order by date DESC"));
				$temp_arr = array();
				foreach ($duplicateRes as $key => $value) {
					$chk='1';
					$temp_arr = (array)$value;
				}
				$main_arr[] = $temp_arr;
				if($chk=='1'){
					$fg = !$fg;
					$chk='0';
				}
			}
		}
		$results->totalItems = count($main_arr);
		$results->items = array_slice($main_arr,$start,$limit);
		return $results;
	}
	/*
	* jobTimeReport 
	*/
	public function jobTimeReport(){
		$modules = Generic::modules();
		Input::flash();
		if (isset($_POST) && !empty($_POST)){
			$this->excelExportJobTimeRep();
		}
		$params = array('left_menu' => $modules);
		return View::make('reports.job_time_report', $params);
	}
	/*
	* missingHoursReport
	*/
	public function missingHoursReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getMissingHourRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
		$emp_types = DB::table('gpg_employee_type')->lists('type','type_id');
		$params = array('left_menu' => $modules,'emp_types'=>$emp_types,'query_data'=>$query_data,'tDays'=>$data->tDays,'summaryDatesArr'=>$data->DatesArr,'leavesArr'=>$data->leavesArr);
		return View::make('reports.missing_hour_report', $params);
	}
	public function getMissingHourRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		
		$results = new \StdClass;
		$results->page = $page;
		# set offset (for excel export offset will not apply)
		$limitOffset = '';
		if($limit != null) {
		  	$results->limit = $limit;
		  	$start = $limit * ($page - 1);
		  	$limitOffset = 'limit ' . $start . ', ' . $limit;
		}
		$results->tDays = 0;
		$results->totalItems = 0;
		$results->items = array();
		$results->DatesArr = array();
		$results->leavesArr = array();
		$optEmployeeType = Input::get('optEmployeeType');
		$SDateCreated = Input::get("SDateCreatedMissing");
		$EDateCreated = Input::get("EDateCreatedMissing");

		$employee = ""; 		
		$employeeType = "";		
		$partialSummaryDatesArr =array(); 
		$datesArr = array(); 		
		$jNum =0; 				
		$summaryDatesArr = array();	
		$leavesArr = array();	
		$holidayArr = array();	

		$dbDateStart = date('Y-m-d',strtotime($SDateCreated));
		$dbDateEnd = date('Y-m-d',strtotime($EDateCreated));
		$str_employee_type = "";
		if(sizeof($optEmployeeType)>0){
			foreach($optEmployeeType as $key => $value)
			{
				if(strlen($value)>0)
				$str_employee_type .=" '".$value."',";
			}
			$str_employee_type = substr($str_employee_type,0,strlen($str_employee_type)-1);
		}
		$tDays =1;
		$tDay_s = DB::select(DB::raw("select DATEDIFF('".$dbDateEnd."','".$dbDateStart."') as t_diff"));
		if(!empty($tDay_s) && isset($tDay_s[0]->t_diff)) {
			$tDays = $tDays + $tDay_s[0]->t_diff;
			$results->tDays = $tDays;
		}
		$getLeavesQ = DB::select(DB::raw("select hours, gpg_employee_id, leave_date from gpg_leaveapp where status = 'A' and leave_date >= '$dbDateStart' and leave_date<='$dbDateEnd'".($employee!=""?" and gpg_employee_id in($employee)":"")));
		foreach ($getLeavesQ as $key => $getLeavesRec) {
			$getLeavesRow = (array)$getLeavesRec;
			$leavesArr[$getLeavesRow['leave_date']][$getLeavesRow['gpg_employee_id']] = $getLeavesRow['hours'];
		}
		$results->leavesArr = $leavesArr;
		$getEmployeeRec = DB::select(DB::raw("select a.date as tsDate , b.job_num as jobNum, b.id as jobId, b.time_in, b.time_out, a.GPG_employee_Id as empId, b.GPG_timetype_id as time_type, (select GPG_job_type_id from gpg_job where id = b.GPG_job_id) as job_type from gpg_timesheet a , gpg_timesheet_detail b where a.id = b.gpg_timesheet_id and a.date >= '$dbDateStart' and a.date<='$dbDateEnd' ".($employee!=""?" and a.gpg_employee_id in($employee) ":"").(count($employeeType)>0 && !empty($employeeType[0])?" and a.gpg_employee_id in (select id from gpg_employee where GPG_employee_type_id in($employeeTypes) ) ":"")." order by a.date, a.GPG_employee_Id, b.GPG_job_id"));
		foreach ($getEmployeeRec as $key => $value){
			$getEmployeeRow = (array)$value;
	        $getHolidayQ = DB::select(DB::raw("select '8' as hours, date from gpg_holiday where date >= '$dbDateStart' and date<='$dbDateEnd'"));
	   		foreach ($getHolidayQ as $key => $getHolidayRec){
	   			$getHolidayRow = (array)$getHolidayRec;
	   			$holidayArr[$getHolidayRow['date']][$getEmployeeRow['empId']] = $getHolidayRow['hours'];					
			}
		    $timearray = $this->get_time_difference( $getEmployeeRow['time_in'], $getEmployeeRow['time_out']); 
			if (!isset($datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['hours']))
				$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['hours'] =0;
			$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['hours'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['time_type'] = $getEmployeeRow['time_type'];
			$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['job_type'] = $getEmployeeRow['job_type'];
			if (preg_match("/workshop/i",$getEmployeeRow['jobNum']) || preg_match("/sick/i",$getEmployeeRow['jobNum']) || preg_match("/vacation/i",$getEmployeeRow['jobNum'])) {
				if (preg_match("/workshop/i",$getEmployeeRow['jobNum'])){ 
					if (!isset($partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['Workshop']))
						$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['Workshop'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
				}
				else{ 
						if (!isset($partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['LeaveHours']))
							$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['LeaveHours'] =0;
						$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['LeaveHours'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
				}	
			}else{
				if (!isset($partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs']))
					$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs'] =0;
				$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			}
			@$summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			
			if ($getEmployeeRow['time_type']==8) $summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']] = '<b>Off</b>';
		} 
		$results->totalItems = count($summaryDatesArr);
		$results->DatesArr = $summaryDatesArr;
		$sumQueryPart = " group by emp.id "; 
		$qry_part = "";
		if(strlen($str_employee_type)>0 and $str_employee_type!=" ''")
		 	$qry_part = " AND emp.GPG_employee_type_id IN (".$str_employee_type.") ";
		$EmployeeJob_query = DB::select(DB::raw("select emp.name as empName , (SELECT type FROM gpg_employee_type WHERE type_id = emp.GPG_employee_type_id) as emp_type, emp.id as empId, date(emp.created_on) as empCreatedDate , if(c.job_number!='NULL',1,0) as prevail, a.job_num as JobNum , a.id as JobId from gpg_employee emp LEFT JOIN gpg_timesheet b on (emp.id=b.GPG_employee_Id and b.date >= '$dbDateStart' and b.date<='$dbDateEnd') LEFT JOIN gpg_timesheet_detail a on (a.GPG_timesheet_Id = b.id ) LEFT JOIN gpg_job_rates c on (a.gpg_task_type = c.gpg_task_type and a.job_num = c.job_number and c.status = 'A') WHERE if(emp.status = 'B', if(terminated_date>'$dbDateEnd',1,0),1) ".$qry_part." ".($employee!=""?" AND b.gpg_employee_id in($employee)":"").(count($employeeType)>0 && !empty($employeeType[0])?" and emp.GPG_employee_type_id in ($employeeTypes) ":"")." ".($jNum!=""?" AND a.job_num = '$jNum'":"")." AND concat(',',emp.frontend,',') like '%timesheet%' $sumQueryPart ".(empty($sumQueryPart)?"group by jobNum,empId":"")." order by b.GPG_employee_Id desc, a.job_num")); 
		$data_arr = array();
		foreach ($EmployeeJob_query as $key => $value) {
			$data_arr[] = (array)$value; 
		}
		$results->items = $data_arr;
		return $results;
	}
	public function convertTime($vtime){
	   if ($vtime!="") {
		   $ptr = ":";
		   $v1 = explode($ptr,$vtime);
		   $vtime = $v1[0]+($v1[1]/60);
	   }
   	   return round($vtime,2);
	}
	public function get_time_difference( $start, $end )
	{
		
	    $uts['start']      =    strtotime( $start );
	    $uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
	    {
			if( $uts['end'] >= $uts['start'] )
	        {
	            $diff    =    $uts['end'] - $uts['start'];
	            if( $days=intval((floor($diff/86400))) )
	                $diff = $diff % 86400;
	            if( $hours=intval((floor($diff/3600))) )
	                $diff = $diff % 3600;
	            if( $minutes=intval((floor($diff/60))) )
	                $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	        }
	        else
	        {
				
	            $uts['start']      =    strtotime( $start );
	   			$uts['end']        =    strtotime( $end );
				$abc = $uts['end']+86400;
				$diff    =    ($abc - $uts['start']);
	            if( $days=intval((floor($diff/86400))) )
	                $diff = $diff % 86400;
	            if( $hours=intval((floor($diff/3600))) )
	                $diff = $diff % 3600;
	            if( $minutes=intval((floor($diff/60))) )
	                $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	        }
	    }
	    else
	    {
	        trigger_error( "Invalid date/time data detected", E_USER_WARNING );
	    }
	    return( false );
	}

	/*
	* genServiceJobsReport
	*/
	public function genServiceJobsReport(){
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getGenSerJobRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'queryPartPM'=>$data->queryPartPM,'queryPartQT'=>$data->queryPartQT,'queryPartTC'=>$data->queryPartTC,'queryPart2'=>$data->queryPart2,'queryPartPM1'=>$data->queryPartPM1,'queryPartQT1'=>$data->queryPartQT1,'queryPartTC1'=>$data->queryPartTC1,'queryPartTimesheet'=>$data->queryPartTimesheet,'queryPartInvoice'=>$data->queryPartInvoice,'queryPartMaterialCost'=>$data->queryPartMaterialCost,'totalCustomer'=>$data->totalCustomer,'completed_workorders'=>$data->completed_workorders,'completed_workorders_tech'=>$data->completed_workorders_tech,'completed_workorders_salesperson'=>$data->completed_workorders_salesperson,'completed_workorders_profit'=>$data->completed_workorders_profit,'comp_work_sale_productivity'=>$data->comp_work_sale_productivity);
		return View::make('reports.gen_service_jobs_report', $params);
	}
	public function getGenSerJobRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		
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
		$results->totalCustomer = array();
		$results->completed_workorders = array();
		$results->completed_workorders_tech = array();
		$results->completed_workorders_salesperson = array();
		$results->completed_workorders_profit = array();
		$results->comp_work_sale_productivity = array();
		$results->queryPartPM = '';
		$results->queryPartQT = '';
		$results->queryPartTC = '';
		$results->queryPart2 = '';
		$results->queryPartPM1 = '';
		$results->queryPartQT1 = '';
		$results->queryPartTC1 = '';
		$results->queryPartTimesheet = '';
		$results->queryPartInvoice = '';
		$checks = array();
		$SDate2 = Input::get("SDate2");
		$EDate2 = Input::get("EDate2");
		$ignoreCostDate =  Input::get("ignoreCostDate");
		$ignoreInvoiceDate =  Input::get("ignoreInvoiceDate");
		$queryPartInvoice = '';
		$queryPartPM = '';
		$queryPartQT = '';
		$queryPartTC = '';
		$queryPart2 = '';
		$queryPartPM1 = '';
		$queryPartQT1 = '';
		$queryPartTC1 = '';
		$queryPart2 = '';
		$queryPartMaterialCost = '';
		$queryPartTimesheet = '';
		$queryPartTech = '';
		if($SDate2!='' and $EDate2!=''){
			$min_date_pm_0 = DB::select(DB::raw("select date_format(created_on,'%Y-%m-%d') as dt_form from gpg_job where ifnull(created_on,'0000-00-00 00:00:00')<>'0000-00-00 00:00:00' and job_num like 'PM%' order by created_on limit 0,1"));
			$min_date_pm = @$min_date_pm_0[0]->dt_form;
			$queryPartPM="and created_on >= '".date('Y-m-d'." 00:00:00",strtotime($min_date_pm))."' and created_on <= '".date('Y-m-d'." 23:59:59",strtotime($SDate2))."'";
			$min_date_qt_0=DB::select(DB::raw("select date_format(created_on,'%Y-%m-%d')  as dt_form from gpg_job where ifnull(created_on,'0000-00-00 00:00:00')<>'0000-00-00 00:00:00' and job_num like 'QT%' order by created_on limit 0,1"));
			$min_date_qt = @$min_date_qt_0[0]->dt_form;
			$queryPartQT="and created_on >= '".date('Y-m-d'." 00:00:00",strtotime($min_date_pm))."' and created_on <= '".date('Y-m-d'." 23:59:59",strtotime($SDate2))."'";
			$min_date_tc_0=DB::select(DB::raw("select date_format(created_on,'%Y-%m-%d')  as dt_form from gpg_job where ifnull(created_on,'0000-00-00 00:00:00')<>'0000-00-00 00:00:00' and job_num like 'PM%' order by created_on limit 0,1"));
			$min_date_tc = @$min_date_tc_0[0]->dt_form;
			$queryPartTC="and created_on >= '".date('Y-m-d'." 00:00:00",strtotime($min_date_tc))."' and created_on <= '".date('Y-m-d'." 23:59:59",strtotime($SDate2))."'";
			///////////////////
			$queryPart2="and created_on >= '".date('Y-m-d'." 00:00:00",strtotime($SDate2))."' AND created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate2))."' ";
			$queryPartTech=" AND c.created_on >= '".date('Y-m-d'." 00:00:00",strtotime($SDate2))."' AND c.created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate2))."' ";
			if($ignoreCostDate==''){
				$queryPartTimesheet=" AND b.date >= '".date('Y-m-d',strtotime($SDate2))."' AND b.date <= '".date('Y-m-d',strtotime($EDate2))."' ";
				$queryPartMaterialCost=" AND gpg_job_cost.date >= '".date('Y-m-d'." 00:00:00",strtotime($SDate2))."' AND gpg_job_cost.date <= '".date('Y-m-d'." 23:59:59",strtotime($EDate2))."' ";
			}
			if($ignoreInvoiceDate==''){
				$queryPartInvoice=" AND gpg_job_invoice_info.invoice_date >= '".date('Y-m-d',strtotime($SDate2))."' AND gpg_job_invoice_info.invoice_date <= '".date('Y-m-d',strtotime($EDate2))."' ";
			}
			$queryPartPM1="and created_on >= '".date('Y-m-d'." 00:00:00",strtotime($min_date_pm))."' and created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate2))."'";
			$queryPartQT1="and created_on >= '".date('Y-m-d'." 00:00:00",strtotime($min_date_pm))."' and created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate2))."'";
			$queryPartTC1="and created_on >= '".date('Y-m-d'." 00:00:00",strtotime($min_date_tc))."' and created_on <= '".date('Y-m-d'." 23:59:59",strtotime($EDate2))."'";
		}
		$filters = array();
		$filters['__gen'] = 'General Service Report';
		$filters['__tech_job'] = 'Technicians On Job';
		$filters['__open_cus'] = 'Customer Having Open Jobs';
		$filters['__comp_work'] = 'Completed Work Orders';
		$filters['__comp_work_tech'] = 'Completed Work Orders by Particular Technician';
		$filters['__comp_work_sold'] = 'Who Sold The Work';
		$filters['__comp_work_profit'] = '% of Jobs Profitable';
		$filters['__comp_work_sale_productivity'] = 'Salesperson Productivity Report';
		while (list($k,$v)=each($_REQUEST)) {
		  if (preg_match("/^__/i",$k)) {
			$checks[$k] = $v;
		  }
		}
		if(@$checks['__open_cus']==1 and @$checks['__comp_work']!=1 and @$checks['__comp_work_tech']!=1 and @$checks['__comp_work_sold']!=1 and @$checks['__comp_work_profit']!=1 ){
			$t_rec = DB::select(DB::raw("select GPG_customer_id, count(id) as t_count, count(if(job_num like 'PM%',1,NULL)) as pm_count, count(if(job_num like 'QT%',1,NULL)) as qt_count, count(if(job_num like 'TC%',1,NULL)) as tc_count, ( select name from gpg_customer where id=GPG_customer_id) as customer from gpg_job where complete = '0' and (job_num like 'PM%' or job_num like 'QT%' or job_num like 'TC%') $queryPart2  group by customer")); 
			if (!empty($t_rec) && isset($t_rec[0]->t_count)) {
				$results->totalItems = $t_rec[0]->t_count;
			}
		}
		elseif (@$checks['__tech_job']==1 or @$checks['__open_cus']==1 or @$checks['__comp_work']==1 or @$checks['__comp_work_tech']==1 or @$checks['__comp_work_sold']==1 or @$checks['__comp_work_profit']==1 ){
			$t_rec = DB::select(DB::raw("select count(id) as t_count from gpg_job where complete='1'  $queryPart2")); 
			if (!empty($t_rec) && isset($t_rec[0]->t_count)) {
				$results->totalItems = $t_rec[0]->t_count;
			}
		}
		$results->queryPartMaterialCost = $queryPartMaterialCost;
		$results->queryPartPM = $queryPartPM;
		$results->queryPartQT = $queryPartQT;
		$results->queryPartTC = $queryPartTC;
		$results->queryPart2 = $queryPart2;
		$results->queryPartPM1 = $queryPartPM1;
		$results->queryPartQT1 = $queryPartQT1;
		$results->queryPartTC1 = $queryPartTC1;
		$results->queryPartInvoice = $queryPartInvoice;
		$results->queryPartTimesheet = $queryPartTimesheet;
		$totalCustomer = array();
		if (isset($_REQUEST['__open_cus'])){
			$count_arr = DB::select(DB::raw("select GPG_customer_id, count(id) as count, count(if(job_num like 'PM%',1,NULL)) as pm_count, count(if(job_num like 'QT%',1,NULL)) as qt_count, count(if(job_num like 'TC%',1,NULL)) as tc_count, ( select name from gpg_customer where id=GPG_customer_id) as customer from gpg_job where complete = '0' and (job_num like 'PM%' or job_num like 'QT%' or job_num like 'TC%') $queryPart2  group by customer"));
			$totalCustomers = DB::select(DB::raw("select GPG_customer_id, count(id) as count, count(if(job_num like 'PM%',1,NULL)) as pm_count, count(if(job_num like 'QT%',1,NULL)) as qt_count, count(if(job_num like 'TC%',1,NULL)) as tc_count, ( select name from gpg_customer where id=GPG_customer_id) as customer from gpg_job where complete = '0' and (job_num like 'PM%' or job_num like 'QT%' or job_num like 'TC%') $queryPart2  group by customer limit $start,$limit")); 
			foreach ($totalCustomers as $key => $value) {
				$totalCustomer[] = (array)$value;
			}
			$results->totalItems = count($count_arr);
			$results->items = $totalCustomer;
			$results->totalCustomer = $totalCustomer;
		}
		$completed_workorders_arr = array();
		if(isset($_REQUEST['__comp_work'])){
			$count_arr = DB::select(DB::raw("select id,job_num,GPG_customer_id,location,complete,(select sum(gpg_job_invoice_info.invoice_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_sum,( select name from gpg_customer where id=GPG_customer_id) as customer from gpg_job where complete='1'  $queryPart2"));
			$completed_workorders = DB::select(DB::raw("select id,job_num,GPG_customer_id,location,complete,(select sum(gpg_job_invoice_info.invoice_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_sum,( select name from gpg_customer where id=GPG_customer_id) as customer from gpg_job where complete='1'  $queryPart2 limit $start,$limit"));
			foreach ($completed_workorders as $key => $value4) {
				$completed_workorders_arr[] = (array)$value4;
			}
			$results->totalItems = count($count_arr);
			$results->items = $completed_workorders_arr;
			$results->completed_workorders = $completed_workorders_arr;
		}
		$completed_workorders_tech_arr = array();
		if (isset($_REQUEST['__comp_work_tech'])) {
			$count_arr = DB::select(DB::raw("select id,job_num,GPG_customer_id,location,complete,(select sum(gpg_job_invoice_info.invoice_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_sum,( select name from gpg_customer where id=GPG_customer_id) as customer from gpg_job where complete='1'  $queryPart2"));
			$completed_workorders_tech = DB::select(DB::raw("select id,job_num,GPG_customer_id,location,complete,(select sum(gpg_job_invoice_info.invoice_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_sum,( select name from gpg_customer where id=GPG_customer_id) as customer from gpg_job where complete='1'  $queryPart2 limit $start,$limit"));
			foreach ($completed_workorders_tech as $key => $value5) {
				$completed_workorders_tech_arr[] = (array)$value5;
			}
			$results->totalItems = count($count_arr);
			$results->items = $completed_workorders_tech_arr;
			$results->completed_workorders_tech = $completed_workorders_tech_arr;
		}
		$completed_workorders_salesperson_arr = array();
		if (isset($_REQUEST['__comp_work_sold'])) {
			$count_arr = DB::select(DB::raw("select id,job_num,GPG_customer_id,location,complete,GPG_employee_id,(select sum(gpg_job_invoice_info.invoice_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_sum,( select name from gpg_customer where id=GPG_customer_id) as customer , ( select name from gpg_employee where id=GPG_employee_id) as sales_person from gpg_job where complete='1'  $queryPart2"));
			$completed_workorders_salesperson= DB::select(DB::raw("select id,job_num,GPG_customer_id,location,complete,GPG_employee_id,(select sum(gpg_job_invoice_info.invoice_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_sum,( select name from gpg_customer where id=GPG_customer_id) as customer , ( select name from gpg_employee where id=GPG_employee_id) as sales_person from gpg_job where complete='1'  $queryPart2 limit $start,$limit")); 
			foreach ($completed_workorders_salesperson as $key => $value6) {
				$completed_workorders_salesperson_arr[] = (array)$value6;
			}
			$results->totalItems = count($count_arr);
			$results->items = $completed_workorders_salesperson_arr;
			$results->completed_workorders_salesperson = $completed_workorders_salesperson_arr;
		}
		$completed_workorders_profit_arr = array();
		if (isset($_REQUEST['__comp_work_profit'])){
			$count_arr = DB::select(DB::raw("select id,job_num,GPG_customer_id,location,complete,GPG_employee_id,(select sum(gpg_job_invoice_info.invoice_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_sum,( select name from gpg_customer where id=GPG_customer_id) as customer , ( select name from gpg_employee where id=GPG_employee_id) as sales_person, (select sum(total_wage) from gpg_timesheet_detail a , gpg_timesheet b where b.id = a.gpg_timesheet_id and a.job_num = gpg_job.job_num $queryPartTimesheet) as labor_cost , (select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost) as material_cost  from gpg_job where complete='1'  $queryPart2"));
			$completed_workorders_profit = DB::select(DB::raw("select id,job_num,GPG_customer_id,location,complete,GPG_employee_id,(select sum(gpg_job_invoice_info.invoice_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id) as invoice_sum,( select name from gpg_customer where id=GPG_customer_id) as customer , ( select name from gpg_employee where id=GPG_employee_id) as sales_person, (select sum(total_wage) from gpg_timesheet_detail a , gpg_timesheet b where b.id = a.gpg_timesheet_id and a.job_num = gpg_job.job_num $queryPartTimesheet) as labor_cost , (select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost) as material_cost  from gpg_job where complete='1'  $queryPart2 limit $start,$limit"));
			foreach ($completed_workorders_profit as $key => $value7) {
				$completed_workorders_profit_arr[] = (array)$value7;
			}
			$results->totalItems = count($count_arr);
			$results->items = $completed_workorders_profit_arr;
			$results->completed_workorders_profit = $completed_workorders_profit_arr;
		}
		$comp_work_sale_productivity_arr = array();
		if (isset($_REQUEST['__comp_work_sale_productivity'])) {
			$count_arr = DB::select(DB::raw("select gpg_employee_id, (select name from gpg_job_type where id = gpg_job_type_id) as job_type, job_num , (select name from gpg_employee where id=gpg_employee_id) as sales_person ,sum((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id)) as invoice_amount_net,  sum((select sum(total_wage) from gpg_timesheet_detail a , gpg_timesheet b  where b.id = a.gpg_timesheet_id and a.job_num = gpg_job.job_num $queryPartTimesheet)) as labor_cost , sum((select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost)) as material_cost, if(LEFT(job_num,3)='RNT' or LEFT(job_num,3)='GPG' or LEFT(job_num,3)='UPS',LEFT(job_num,3),LEFT(job_num,2)) as PM_job FROM gpg_job WHERE complete='1'  $queryPart2 group by ifnull(gpg_employee_id,0), gpg_job_type_id, PM_job order by gpg_employee_id, gpg_job_type_id, job_num"));
			$comp_work_sale_productivity = DB::select(DB::raw("select gpg_employee_id, (select name from gpg_job_type where id = gpg_job_type_id) as job_type, job_num , (select name from gpg_employee where id=gpg_employee_id) as sales_person ,sum((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id)) as invoice_amount_net,  sum((select sum(total_wage) from gpg_timesheet_detail a , gpg_timesheet b  where b.id = a.gpg_timesheet_id and a.job_num = gpg_job.job_num $queryPartTimesheet)) as labor_cost , sum((select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost)) as material_cost, if(LEFT(job_num,3)='RNT' or LEFT(job_num,3)='GPG' or LEFT(job_num,3)='UPS',LEFT(job_num,3),LEFT(job_num,2)) as PM_job FROM gpg_job WHERE complete='1'  $queryPart2 group by ifnull(gpg_employee_id,0), gpg_job_type_id, PM_job order by gpg_employee_id, gpg_job_type_id, job_num $limitOffset")); 
			foreach ($comp_work_sale_productivity as $key => $value8) {
				$comp_work_sale_productivity_arr[] = (array)$value8;
			}
			$results->totalItems = count($count_arr);
			$results->items = $comp_work_sale_productivity_arr;
			$results->comp_work_sale_productivity = $comp_work_sale_productivity_arr;
		}
		return $results;
	}
	/*
	* payrollReport
	*/
	public function payrollReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getPayrollRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where status = 'A' and concat(',',frontend,',') like '%,timesheet,%' order by name"));
			$salesp_arr = array(''=>'All Employees');
			foreach ($salesPerson as $key => $value)
					$salesp_arr[$value->id] = $value->name;
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'salesp_arr'=>$salesp_arr,'tDays'=>$data->tDays,'datesArr'=>$data->datesArr);
		return View::make('reports.pay_reportopt', $params);
	}
	public function getPayrollRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		
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
		$results->datesArr = array();
		$results->items = array();
		$results->tDays = 0;
		$prevJob = ""; 
		$reportView = '';
		$leavesArr = array();
		$datesArr = array();
		$summaryDatesArr = array();
		$partialSummaryDatesArr = array();
		$employee = Input::get("employee");
		$start = Input::get("SDate");
		$end = Input::get("EDate");
		$jNum = Input::get("jNum");
		$dbDateStart = date('Y-m-d', strtotime($start));
		$dbDateEnd = date('Y-m-d', strtotime($end));
		$partialArray['AllOtherJobs'] = "AllOtherJobs";
		$partialArray['Workshop'] = "Workshop";
		$partialArray['LeaveHours'] = "LeaveHours";
		$tDays = 0;
		$tday_query = DB::select(DB::raw("select DATEDIFF('" . $dbDateEnd . "','" . $dbDateStart . "') as t_diff"));
		if (!empty($tday_query) && isset($tday_query[0]->t_diff)) {
			$tDays = $tday_query[0]->t_diff+1;
		}
		$getLeavesQ = DB::select(DB::raw("select hours, gpg_employee_id, leave_date from gpg_leaveapp where status = 'A' and leave_date >= '$dbDateStart' and leave_date<='$dbDateEnd'" . ($employee != "" ? " and gpg_employee_id in($employee)" : "")));
		foreach ($getLeavesQ as $key => $value){	
			$getLeavesRow = (array)$value;
		    $leavesArr[$getLeavesRow['leave_date']][$getLeavesRow['gpg_employee_id']] = $getLeavesRow['hours'];
		}
		$getEmployeeQ = DB::select(DB::raw("select a.date as tsDate , b.job_num as jobNum, b.id as jobId, b.time_in, b.time_out, a.GPG_employee_Id as empId , b.* from gpg_timesheet a , gpg_timesheet_detail b where a.id = b.gpg_timesheet_id and a.date >= '$dbDateStart' and a.date<='$dbDateEnd' " . ($employee != "" ? " and a.gpg_employee_id in($employee) " : "") . "" . ($jNum != "" ? " AND b.job_num = '$jNum'" : "") . " order by a.date, a.GPG_employee_Id, b.GPG_job_id"));
		foreach ($getEmployeeQ as $key => $value1){
			$getEmployeeRow = (array)$value1;
		    $getHolidayQ = DB::select(DB::raw("select '8' as hours, date from gpg_holiday where date >= '$dbDateStart' and date<='$dbDateEnd'"));
		    foreach ($getHolidayQ as $key => $value2){
		    	$getHolidayRow = (array)$value2;
		        $holidayArr[$getHolidayRow['date']][$getEmployeeRow['empId']] = $getHolidayRow['hours'];
		    }
		    $timearray = $this->get_time_difference($getEmployeeRow['time_in'], $getEmployeeRow['time_out']);
		    @$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['pw_flag']][$getEmployeeRow['jobNum']]['hours'] += $this->convertTime($timearray['hours'] . ":" . $timearray['minutes']);
		    $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['pw_flag']][$getEmployeeRow['jobNum']]['time_type'] = $getEmployeeRow['GPG_timetype_id'];
		    if ( $getEmployeeRow['pw_flag'] == 1 ) {
				$compare_job_num = strtolower(substr($getEmployeeRow['jobNum'],0,2));
		        $pw_wage_type = DB::table('gpg_job_rates')->where('job_number','=',$getEmployeeRow['jobNum'])->orWhere('job_number','=',$compare_job_num)->pluck('wage_type'); 
		        $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['pw_flag']][$getEmployeeRow['jobNum']]['is_pw'] = $getEmployeeRow['pw_flag'];
		        $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['pw_flag']][$getEmployeeRow['jobNum']]['pw_type'] = $pw_wage_type;
		        $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['pw_flag']][$getEmployeeRow['jobNum']]['pw_reg'] = $getEmployeeRow['pw_reg_rate'];
		        $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['pw_flag']][$getEmployeeRow['jobNum']]['pw_ot'] = $getEmployeeRow['pw_ot_rate'];
		        $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['pw_flag']][$getEmployeeRow['jobNum']]['pw_dt'] = $getEmployeeRow['pw_dt_rate'];
		    }
		    $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['pw_flag']][$getEmployeeRow['jobNum']]['emp_reg'] = $getEmployeeRow['labor_rate'];
		    if ( preg_match("/workshop/i", $getEmployeeRow['jobNum']) || preg_match("/sick/i", $getEmployeeRow['jobNum']) || preg_match("/vacation/i", $getEmployeeRow['jobNum']) ) {
		       if ( preg_match("/workshop/i", $getEmployeeRow['jobNum']) )
		            @$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['Workshop'] += $this->convertTime($timearray['hours'] . ":" . $timearray['minutes']);
		        else
		            @$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['LeaveHours'] += $this->convertTime($timearray['hours'] . ":" . $timearray['minutes']);
		    }
		    else {
		        @$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs'] += $this->convertTime($timearray['hours'] . ":" . $timearray['minutes']);
		    }
		    @$summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']] += $this->convertTime($timearray['hours'] . ":" . $timearray['minutes']);
		}
		$results->datesArr = $datesArr;
		$results->tDays = $tDays;
		$sumQueryPart = ($reportView != "" ? " group by emp.id " : "");
		$count = DB::select(DB::raw("select emp.name as empName , emp.id as empId, a.job_num as JobNum,a.GPG_timetype_id as timetype,a.pw_flag , a.id as JobId , c.adjustment AS adjustment, (select wage_type from gpg_job_rates where a.gpg_task_type = gpg_job_rates.gpg_task_type AND (job_number = a.job_num OR job_number = SUBSTRING(a.job_num,1,2)) and GPG_employee_type_id = (select GPG_employee_type_id from gpg_employee where id = b.GPG_employee_id) and start_date<=b.date and end_date>=b.date AND a.pw_flag = 1 ORDER BY modified_on DESC LIMIT 1) as wage_type, salaried from gpg_employee emp 
		   LEFT JOIN gpg_timesheet b on (emp.id=b.GPG_employee_Id and b.date >= '$dbDateStart' and b.date<='$dbDateEnd') 
		   LEFT JOIN gpg_timesheet_detail a on (a.GPG_timesheet_Id = b.id ) 
		   LEFT JOIN gpg_timesheet_adjustment c ON (c.gpg_employee_id=emp.id AND c.start_date='$dbDateStart' AND c.end_date='$dbDateEnd' AND (c.is_prevail = a.pw_flag or emp.salaried ='1' ))   
		   WHERE if(emp.status = 'B', if(terminated_date>'$dbDateEnd',1,0),1)  " . ($employee != "" ? " AND b.gpg_employee_id in($employee)" : "") . " " . ($jNum != "" ? " AND a.job_num = '$jNum'" : "") . "  $sumQueryPart  group by emp.id, a.job_num,timetype,a.pw_flag order by emp.name asc, wage_type, timetype "));
		$EmployeeJob_query = DB::select(DB::raw("select emp.name as empName , emp.id as empId, a.job_num as JobNum,a.GPG_timetype_id as timetype,a.pw_flag , a.id as JobId , c.adjustment AS adjustment, (select wage_type from gpg_job_rates where a.gpg_task_type = gpg_job_rates.gpg_task_type AND (job_number = a.job_num OR job_number = SUBSTRING(a.job_num,1,2)) and GPG_employee_type_id = (select GPG_employee_type_id from gpg_employee where id = b.GPG_employee_id) and start_date<=b.date and end_date>=b.date AND a.pw_flag = 1 ORDER BY modified_on DESC LIMIT 1) as wage_type, salaried from gpg_employee emp 
		   LEFT JOIN gpg_timesheet b on (emp.id=b.GPG_employee_Id and b.date >= '$dbDateStart' and b.date<='$dbDateEnd') 
		   LEFT JOIN gpg_timesheet_detail a on (a.GPG_timesheet_Id = b.id ) 
		   LEFT JOIN gpg_timesheet_adjustment c ON (c.gpg_employee_id=emp.id AND c.start_date='$dbDateStart' AND c.end_date='$dbDateEnd' AND (c.is_prevail = a.pw_flag or emp.salaried ='1' ))   
		   WHERE if(emp.status = 'B', if(terminated_date>'$dbDateEnd',1,0),1)  " . ($employee != "" ? " AND b.gpg_employee_id in($employee)" : "") . " " . ($jNum != "" ? " AND a.job_num = '$jNum'" : "") . "  $sumQueryPart  group by emp.id, a.job_num,timetype,a.pw_flag order by emp.name asc, wage_type, timetype $limitOffset"));
		$data_arr = array();
		foreach ($EmployeeJob_query as $key => $value) {
		   	$data_arr[] = (array)$value;
		}   
		$results->totalItems = count($count);
		$results->items = $data_arr;
		return $results;
	}

	/*
	* employeeReport
	*/
	public function employeeReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getEmployeeRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where status = 'A' and concat(',',frontend,',') like '%,timesheet,%' order by name"));
			$salesp_arr = array(''=>'Select Employee');
			foreach ($salesPerson as $key => $value)
					$salesp_arr[$value->id] = $value->name;
		$emp_type = DB::table('gpg_employee_type')->orderBy('type')->lists('type','type_id');
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'emp_type'=>$emp_type,'salesp_arr'=>$salesp_arr,'tDays'=>$data->tDays,'getEmpInfo'=>$data->getEmpInfo,'num_records_emp'=>$data->num_records_emp,'datesArr'=>$data->datesArr);
		return View::make('reports.emp_reportopt', $params);
	}
	public function getEmployeeRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		
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
		$results->datesArr = array();
		$results->num_records_emp = 0;
		$results->tDays = 0;
		$datesArr = array();
		$results->getEmpInfo = array();
		$employee = Input::get("employee");
		$employee_type = Input::get("employee_type");	
		$start = Input::get("SDate");
		$end = Input::get("EDate");
		$dbDateStart = date('Y-m-d',strtotime($start));
		$dbDateEnd = date('Y-m-d',strtotime($end));
		$queryPart ='';
		if ($employee!="") $queryPart .= " AND id = '$employee' ";   
		if ($employee_type!="") $queryPart .= " AND GPG_employee_type_id = '$employee_type' AND status = 'A' ";   
		$getEmpInfo = DB::select(DB::raw("select id,name,email,phone,GPG_employee_type_id from gpg_employee where 1 $queryPart"));
		$results->getEmpInfo = $getEmpInfo;
		$tDays = 0;
		$tday_query = DB::select(DB::raw("select DATEDIFF('" . $dbDateEnd . "','" . $dbDateStart . "') as t_diff"));
		if (!empty($tday_query) && isset($tday_query[0]->t_diff)) {
			$tDays = $tday_query[0]->t_diff+1;
		}
		$results->tDays = $tDays;
		foreach ($getEmpInfo as $key => $value2){
			$Emp = (array)$value2;
			$getLeavesQ = DB::select(DB::raw("select hours, gpg_employee_id, leave_date, off_type from gpg_leaveapp where status = 'A' and leave_date >= '$dbDateStart' and leave_date<='$dbDateEnd' and gpg_employee_id = '".$Emp['id']."'"));
			foreach ($getLeavesQ as $key => $value3){
				$getLeavesRow = (array)$value3;
		 		$leavesArr[$getLeavesRow['leave_date']][$getLeavesRow['off_type']] = $getLeavesRow['hours'];
		    }
		    $getHolidayQ = DB::select(DB::raw("select '8' as hours, date from gpg_holiday where date >= '$dbDateStart' and date<='$dbDateEnd'"));
		    foreach ($getHolidayQ as $key => $value4){
		    	$getHolidayRow = (array)$value4;
	 			$holidayArr[$getHolidayRow['date']] = $getHolidayRow['hours'];
		    }
			$getEmployeeQ = DB::select(DB::raw("select a.date as tsDate,SEC_TO_TIME(sum(time_to_sec(TIMEDIFF(b.time_out,b.time_in)))) as t_jobs_time, a.id as timesheet_id from gpg_timesheet a,gpg_timesheet_detail b WHERE a.id = b.GPG_timesheet_id and a.GPG_employee_Id = '".$Emp['id']."' and a.date>='$dbDateStart' and  a.date<='$dbDateEnd' group by a.id"));
			$num_records_emp = count($getEmployeeQ);
			$results->num_records_emp = $num_records_emp;
			foreach ($getEmployeeQ as $key => $value5){
			 	$getEmployeeRow = (array)$value5;
			 	$datesArr[$getEmployeeRow['tsDate']]['t_jobs_time'] = $getEmployeeRow['t_jobs_time'];
				$datesArr[$getEmployeeRow['tsDate']]['t_id'] = $getEmployeeRow['timesheet_id'];
			} 
		}
		$results->datesArr = $datesArr;

		return $results;
	}

	/*
	* activityReport
	*/
	public function activityReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getActivityRepByPage($page, 100);
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'tDays'=>$data->tDays,'datesArr'=>$data->datesArr);
		return View::make('reports.activity_report', $params);
	}
	public function getActivityRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		
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
		$results->tDays = 0;
		$results->items = array();
		$results->datesArr = array();
		$datesArr = array();
		$start = Input::get("SDateCreated");
		$end = Input::get("EDateCreated");
		if(!isset($employee)) $employee = "";
		if(!isset($jNum)) $jNum = "";
		$dbDateStart = date('Y-m-d',strtotime($start));
		$dbDateEnd = date('Y-m-d',strtotime($end));
		$reportView = Input::get('reportView');
		$activityCount = array();
		$tDays = 0;
		$tday_query = DB::select(DB::raw("select DATEDIFF('" . $dbDateEnd . "','" . $dbDateStart . "') as t_diff"));
		if (!empty($tday_query) && isset($tday_query[0]->t_diff)) {
			$tDays = $tday_query[0]->t_diff+1;
		}
		$results->tDays = $tDays;
		$getEmployeeQ = DB::select(DB::raw("select DATE_FORMAT(b.created_on,'%Y-%m-%d') as tsDate , b.job_num as jobNum, b.id as jobId, b.time_in, b.time_out, a.GPG_employee_Id as empId , b.GPG_timetype_id as time_type from gpg_timesheet a , gpg_timesheet_detail b  where a.id = b.gpg_timesheet_id and b.created_on >= '$dbDateStart 00:00:00' and b.created_on<='$dbDateEnd 23:59:59' order by b.created_on, a.GPG_employee_Id, b.GPG_job_id"));
		foreach ($getEmployeeQ as $key => $value){
			$getEmployeeRow = (array)$value;
		    $timearray = $this->get_time_difference( $getEmployeeRow['time_in'], $getEmployeeRow['time_out']); 
			$convertTime = $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']][$getEmployeeRow['jobId']] = ($getEmployeeRow['time_type']==8?'<b>Off</b>':number_format($convertTime,2));	
		} 
		$results->datesArr = $datesArr;
		$sumQueryPart = ($reportView!=""?" group by emp.id ":"");
		$count = DB::select(DB::raw("select emp.name as empName , emp.id as empId, if(c.job_number!='NULL',1,0) as prevail, a.job_num as JobNum , a.id as JobId, b.date as tsDate from gpg_employee emp LEFT JOIN gpg_timesheet b on (emp.id=b.GPG_employee_Id) LEFT JOIN gpg_timesheet_detail a on (a.GPG_timesheet_Id = b.id ) LEFT JOIN gpg_job_rates c on (a.gpg_task_type = c.gpg_task_type and a.job_num = c.job_number and c.status = 'A') WHERE a.created_on >= '$dbDateStart 00:00:00' and a.created_on<='$dbDateEnd 23:59:59' and emp.status = 'A' ".($employee!=""?" AND b.gpg_employee_id in($employee)":"")." ".($jNum!=""?" AND a.job_num = '$jNum'":"")." AND concat(',',emp.frontend,',') like '%timesheet%' $sumQueryPart order by b.GPG_employee_Id desc, a.job_num"));
		$EmployeeJob_query = DB::select(DB::raw("select emp.name as empName , emp.id as empId, if(c.job_number!='NULL',1,0) as prevail, a.job_num as JobNum , a.id as JobId, b.date as tsDate from gpg_employee emp LEFT JOIN gpg_timesheet b on (emp.id=b.GPG_employee_Id) LEFT JOIN gpg_timesheet_detail a on (a.GPG_timesheet_Id = b.id ) LEFT JOIN gpg_job_rates c on (a.gpg_task_type = c.gpg_task_type and a.job_num = c.job_number and c.status = 'A') WHERE a.created_on >= '$dbDateStart 00:00:00' and a.created_on<='$dbDateEnd 23:59:59' and emp.status = 'A' ".($employee!=""?" AND b.gpg_employee_id in($employee)":"")." ".($jNum!=""?" AND a.job_num = '$jNum'":"")." AND concat(',',emp.frontend,',') like '%timesheet%' $sumQueryPart order by b.GPG_employee_Id desc, a.job_num $limitOffset"));
		$data_arr = array();
		foreach ($EmployeeJob_query as $key => $value3) {
			$data_arr[] = (array)$value3;
		}
		$results->totalItems = count($count);
		$results->items = $data_arr;
		return $results;
	}

	/*
	* compModelingReport
	*/
	public function compModelingReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getModelingRepByPage($page, 100);
   		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where status = 'A' and concat(',',frontend,',') like '%,timesheet,%' order by name"));
		$salesp_arr = array(''=>'Select Employee');
		foreach ($salesPerson as $key => $value)
			$salesp_arr[$value->id] = $value->name;
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'salesp_arr'=>$salesp_arr,'tDays'=>$data->tDays,'datesArr'=>$data->datesArr,'summaryDatesArr'=>$data->summaryDatesArr,'partialSummaryDatesArr'=>$data->partialSummaryDatesArr);
		return View::make('reports.modeling_reportopt', $params);
	}
	public function getModelingRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
		
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
		$results->datesArr = array();
		$results->summaryDatesArr = array();
		$results->partialSummaryDatesArr = array();
		$results->tDays = 0;
		$summaryDatesArr = array();
		$partialSummaryDatesArr = array();
		$datesArr = array();
		$jNum = Input::get('jNum');
		$reportYear = Input::get("year");
		$employee = Input::get("employee");
		$yearlyPayrollTax = array();
		$yearQry = DB::select(DB::raw("select payroll_tax from gpg_gl_expense_columns where report_year =  '$reportYear'")); 
		foreach ($yearQry as $key => $value) {
			$yearlyPayrollTax = (array)$value;
		}
		if (isset($yearlyPayrollTax[0]))
		    define('_DefaultTaxRateSalary', number_format($yearlyPayrollTax[0], 2));
		else
		    define('_DefaultTaxRateSalary', 15.00);
		$start = "01/01/" . $reportYear;
		$end = "12/31/" . $reportYear;
		$dbDateStart = date('Y-m-d', strtotime($start));
		$dbDateEnd = date('Y-m-d', strtotime($end));
		$reportView = Input::get('reportView');
		$partialArray['AllOtherJobs'] = "AllOtherJobs";
		$partialArray['Workshop'] = "Workshop";
		$partialArray['LeaveHours'] = "LeaveHours";
		$columnsArray = array(
		    'id' => 'ColumnID',
		    'col_name' => 'ColumnName',
		    'gpg_gl_expense_ids' => 'ColumnGL',
		    'gpg_employee_ids' => 'Employee',
		    'default_value' => 'ColumnDefaultValue',
		    'cal_type' => 'ColumnCalType',
		    'rate_type' => 'ColumnApplyRate',
		    'default_rate' => 'ColumnDefaultRate',
		    'add_cols' => 'CalcCols',
		    'multiply_with' => 'MultWith',
		    'divide_with' => 'DivWith',
		    'include_salary' => 'IncSal',
		    'include_tax' => 'IncTax'
		);
		$tDays = 0;
		$tday_query = DB::select(DB::raw("select DATEDIFF('" . $dbDateEnd . "','" . $dbDateStart . "') as t_diff"));
		if (!empty($tday_query) && isset($tday_query[0]->t_diff)){
			$tDays = $tday_query[0]->t_diff+1;
		}
		$results->tDays = $tDays;
		$getLeavesQ = DB::select(DB::raw("select hours, gpg_employee_id, leave_date from gpg_leaveapp where status = 'A' and leave_date >= '$dbDateStart' and leave_date<='$dbDateEnd'" . ($employee != "" ? " and gpg_employee_id in($employee)" : "")));
		foreach ($getLeavesQ as $key => $value1){
			$getLeavesRow = (array)$value1;
		    $leavesArr[$getLeavesRow['leave_date']][$getLeavesRow['gpg_employee_id']] = $getLeavesRow['hours'];
		}
		$getEmployeeQ = DB::select(DB::raw("select a.date as tsDate , b.job_num as jobNum, b.id as jobId, b.time_in, b.time_out, a.GPG_employee_Id as empId , b.* from gpg_timesheet a , gpg_timesheet_detail b where a.id = b.gpg_timesheet_id and a.date >= '$dbDateStart' and a.date<='$dbDateEnd' " . ($employee != "" ? " and a.gpg_employee_id in($employee) " : "") . " order by a.date, a.GPG_employee_Id, b.GPG_job_id"));
		foreach ($getEmployeeQ as $key => $value2){
			$getEmployeeRow = (array)$value2;
		    $getHolidayQ = DB::select(DB::raw("select '8' as hours, date from gpg_holiday where date >= '$dbDateStart' and date<='$dbDateEnd'"));
		    foreach ($getHolidayQ as $key => $value3){
		    	$getHolidayRow = (array)$value3;
		        $holidayArr[$getHolidayRow['date']][$getEmployeeRow['empId']] = $getHolidayRow['hours'];
		    }
		    $timearray = $this->get_time_difference($getEmployeeRow['time_in'], $getEmployeeRow['time_out']);
		    if (!isset($datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['hours']))
		    	$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['hours']=0;
		    $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['hours'] += $this->convertTime($timearray['hours'] . ":" . $timearray['minutes']);
		    $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['time_type'] = $getEmployeeRow['GPG_timetype_id'];
		    if ( $getEmployeeRow['pw_flag'] == 1 ) {
		        $pw_wage_type = DB::table('gpg_job_rates')->where('job_number','=',$getEmployeeRow['jobNum'])->pluck('wage_type');
		        $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['is_pw'] = $getEmployeeRow['pw_flag'];
		        $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['pw_type'] = $pw_wage_type;
		        $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['pw_reg'] = $getEmployeeRow['pw_reg_rate'];
		        $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['pw_ot'] = $getEmployeeRow['pw_ot_rate'];
		        $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['pw_dt'] = $getEmployeeRow['pw_dt_rate'];
		    }
		    $datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['emp_reg'] = $getEmployeeRow['labor_rate'];
			if ( preg_match("/workshop/i", $getEmployeeRow['jobNum']) || preg_match("/sick/i", $getEmployeeRow['jobNum']) || preg_match("/vacation/i", $getEmployeeRow['jobNum']) ) {
		        if ( preg_match("/workshop/", $getEmployeeRow['jobNum']) )
			        $partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['Workshop'] += $this->convertTime($timearray['hours'] . ":" . $timearray['minutes']);
		        else
		            @$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['LeaveHours'] += $this->convertTime($timearray['hours'] . ":" . $timearray['minutes']);
		    }else {
		    	if (!isset($partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs']))
		    		$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs']=0;
		        $partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs'] += $this->convertTime($timearray['hours'] . ":" . $timearray['minutes']);
		    }
		    if (!isset($summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]))
		    	$summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]=0;
		    $summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']] += $this->convertTime($timearray['hours'] . ":" . $timearray['minutes']);
		}// end foreach
		$results->datesArr = $datesArr;
		$results->partialSummaryDatesArr = $partialSummaryDatesArr;
		$results->summaryDatesArr = $summaryDatesArr;
	 	$sumQueryPart = ($reportView != "" ? " group by emp.id " : "");
        $ColumnsEmp_rs = $EmployeeJob_rs = DB::select(DB::raw("select emp.name as empName , emp.id as empId, a.job_num as JobNum , a.id as JobId , (select wage_type from gpg_job_rates where a.gpg_task_type = gpg_job_rates.gpg_task_type and job_number = a.job_num and GPG_employee_type_id = (select GPG_employee_type_id from gpg_employee where id = b.GPG_employee_id)  and start_date<=b.date and end_date>=b.date) as wage_type, salaried from gpg_employee emp 
		LEFT JOIN gpg_timesheet b on (emp.id=b.GPG_employee_Id and b.date >= '$dbDateStart' and b.date<='$dbDateEnd') 
		LEFT JOIN gpg_timesheet_detail a on (a.GPG_timesheet_Id = b.id )  
		WHERE if(emp.status = 'B', if(terminated_date>'$dbDateEnd',1,0),1)  " . ($employee != "" ? " AND b.gpg_employee_id in($employee)" : "") . " " . ($jNum != "" ? " AND a.job_num = '$jNum'" : "") . "  $sumQueryPart group by emp.id, a.job_num order by emp.name asc, wage_type "));        
		#Prepare Dynamic Columns
		$DynamicColumnValues = array();
        $RSCurrentRow = DB::select(DB::raw("select if(column_value=0,NULL,column_value) as column_value , if(field_rate=0,NULL,field_rate) as field_rate , employee_id, column_name, wtype from gpg_gl_expense_columns_values where reportyear = '" . $reportYear . "' "));
        foreach ($RSCurrentRow as $key => $value3){
        	$RowCurrentRow = (array)$value3;
            $DynamicColumnValues[$RowCurrentRow['employee_id']][$RowCurrentRow['column_name']][$RowCurrentRow['wtype']]['field_value'] = $RowCurrentRow['column_value'];
            $DynamicColumnValues[$RowCurrentRow['employee_id']][$RowCurrentRow['column_name']][$RowCurrentRow['wtype']]['field_rate'] = $RowCurrentRow['field_rate'];
        }
        $data_arr = array();
        $results->totalItems = count($EmployeeJob_rs);
		foreach ($EmployeeJob_rs as $key => $value4) {
			$data_arr[] = (array)$value4;
		}
		$results->items = $data_arr;
		return $results;
	}

	/*
	* generalReport
	*/
	public function generalReport(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getGenRepByPage($page, 100);
   		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where status = 'A' and concat(',',frontend,',') like '%,timesheet,%' order by name"));
		$salesp_arr = array(''=>'Select Employee');
		foreach ($salesPerson as $key => $value)
			$salesp_arr[$value->id] = $value->name;
		$emp_types = DB::table('gpg_employee_type')->lists('type','type_id');
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'salesp_arr'=>$salesp_arr,'emp_type'=>$emp_types,'tDays'=>$data->tDays,'datesArr'=>$data->datesArr,'summaryDatesArr'=>$data->summaryDatesArr,'partialSummaryDatesArr'=>$data->partialSummaryDatesArr,'holidayArr'=>$data->holidayArr,'leavesArr'=>$data->leavesArr);
		return View::make('reports.gen_reportopt', $params);
	}
	public function getGenRepByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$results->leavesArr = array();
		$results->datesArr = array();
		$results->holidayArr = array();
		$results->summaryDatesArr = array();
		$results->partialSummaryDatesArr = array();
		$results->tDays =0;
		$employee = Input::get("employee");
		$employeeType = Input::get("employeeType");
	    $employee_in = Input::get("employee_in");
	    $start = Input::get("SDate");
		$end = Input::get("EDate");
		$jNum = Input::get("jNum");
		$dbDateStart = Input::get("SDate");
        $dbDateEnd = Input::get("EDate");
		$reportView = Input::get('reportView');
		$partialArray['AllOtherJobs'] = "AllOtherJobs";
		$partialArray['Workshop'] = "Workshop";
		$partialArray['LeaveHours'] = "LeaveHours";
		$employeeTypes = @implode(",",$employeeType);
		$datesArr = array();
		$partialSummaryDatesArr = array();
		$showFlag = "";
		$holidayArr = array();
		$leavesArr = array(); 
		$summaryDatesArr  = array();  
		$jobHoursArray  = array();  
		$jobValue ="";
		$totalJobHours =0;
		if (empty($employeeType[0])) $employeeTypes = substr($employeeTypes,1,strlen($employeeTypes));

		$tDays = 0;
		$tday_query = DB::select(DB::raw("select DATEDIFF('" . $dbDateEnd . "','" . $dbDateStart . "') as t_diff"));
		if (!empty($tday_query) && isset($tday_query[0]->t_diff)){
			$tDays = $tday_query[0]->t_diff+1;
		}
		$results->tDays = $tDays+1;
		$getLeavesRec = DB::select(DB::raw("select hours, gpg_employee_id, leave_date from gpg_leaveapp where status = 'A' and leave_date >= '$dbDateStart' and leave_date<='$dbDateEnd'".($employee!=""?" and gpg_employee_id in($employee)":"")));
		foreach ($getLeavesRec as $key => $value1){
			$getLeavesRow = (array)$value1;
	 		$leavesArr[$getLeavesRow['leave_date']][$getLeavesRow['gpg_employee_id']] = $getLeavesRow['hours'];
		}
	   	$getEmployeeRec = DB::select(DB::raw("select a.date as tsDate , b.job_num as jobNum, b.id as jobId, b.time_in, b.time_out, a.GPG_employee_Id as empId, b.GPG_timetype_id as time_type, (select GPG_job_type_id from gpg_job where id = b.GPG_job_id) as job_type from gpg_timesheet a , gpg_timesheet_detail b where a.id = b.gpg_timesheet_id and a.date >= '$dbDateStart' and a.date<='$dbDateEnd' ".($employee!=""?" and a.gpg_employee_id in($employee) ":"").(count($employeeType)>0 && !empty($employeeType[0])?" and a.gpg_employee_id in (select id from gpg_employee where GPG_employee_type_id in($employeeTypes) ) ":"")." order by a.date, a.GPG_employee_Id, b.GPG_job_id"));
	 	foreach ($getEmployeeRec as $key => $value2){
	 		$getEmployeeRow = (array)$value2;
	    	$getHolidayRec = DB::select(DB::raw("select '8' as hours, date from gpg_holiday where date >= '$dbDateStart' and date<='$dbDateEnd'"));
   			foreach ($getHolidayRec as $key => $value3){
   			   	$getHolidayRow = (array)$value3;
				$holidayArr[$getHolidayRow['date']][$getEmployeeRow['empId']] = $getHolidayRow['hours'];
			}
	        $timearray = $this->get_time_difference( $getEmployeeRow['time_in'], $getEmployeeRow['time_out']); 
			if (!isset($datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['hours']))
				$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['hours'] =0;
			$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['hours'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			if (!isset($datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']][$getEmployeeRow['time_type']]['hours']))
				$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']][$getEmployeeRow['time_type']]['hours'] =0;
			$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']][$getEmployeeRow['time_type']]['hours'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['time_type'] = $getEmployeeRow['time_type'];
			$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['job_type'] = $getEmployeeRow['job_type'];
			if (preg_match("/workshop/i",$getEmployeeRow['jobNum']) || preg_match("/sick/i",$getEmployeeRow['jobNum']) || preg_match("/vacation/i",$getEmployeeRow['jobNum'])) {
			if (preg_match("/workshop/",$getEmployeeRow['jobNum']))
			$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['Workshop'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			else $partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['LeaveHours'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			}
			else { 
				if (!isset($partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs']))
					$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs'] =0;
				$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			}				
			if (!isset($summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]))
				$summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']] =0;
			$summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			if ($getEmployeeRow['time_type']==8) $summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']] = '<b>Off</b>';
		}//endforeach val2 
		if(isset($employee_in) && count($employee_in) > 0){
        	$emp_in = '';
            foreach($employee_in as $in){
                $emp_in[] = $in;
            }
        	$emp_in_query = ' AND emp.exclude_oh IN ('.implode(',', $emp_in).')';
        }else {
            $emp_in_query = '';
        }
		$sumQueryPart = ($reportView!=""?" group by emp.id ":"");
		$EmployeeJob_rs = DB::select(DB::raw("select IF(LENGTH(emp.name) - LENGTH(REPLACE(emp.name, ' ', ''))=1,
		SUBSTRING_INDEX(emp.name, ' ',-1),
		SUBSTRING_INDEX(emp.name, ' ',((LENGTH(emp.name) - LENGTH(REPLACE(emp.name, ' ', '')))*(-1)))) AS employeeSortName, emp.name as empName , emp.status as empStatus, emp.id as empId,a.GPG_timetype_id as time_type,  a.job_num as JobNum, a.pw_flag as prevail , a.id as JobId,a.GPG_timetype_id as timetypeId, (select name from gpg_customer where id = d.gpg_customer_id) as customer_name, d.location from gpg_employee emp LEFT JOIN gpg_timesheet b on (emp.id=b.GPG_employee_Id and b.date >= '$dbDateStart' and b.date<='$dbDateEnd') LEFT JOIN gpg_timesheet_detail a on (a.GPG_timesheet_Id = b.id ) LEFT JOIN gpg_job_rates c on (a.gpg_task_type = c.gpg_task_type and a.job_num = c.job_number and c.status = 'A') 
		INNER JOIN gpg_job d on (a.job_num = d.job_num)
		WHERE 1 AND emp.status = 'A' $emp_in_query ".($employee!=""?" AND b.gpg_employee_id in($employee)":"").(count($employeeType)>0 && !empty($employeeType[0])?" and emp.GPG_employee_type_id in ($employeeTypes) ":"")." ".($jNum!=""?" AND a.job_num = '$jNum'":"")." AND concat(',',emp.frontend,',') like '%timesheet%' $sumQueryPart ".(empty($sumQueryPart)?"group by time_type,jobNum,empId":"")." ORDER BY employeeSortName ASC, b.GPG_employee_Id desc, a.job_num"));
		$data_arr = array();
		foreach ($EmployeeJob_rs as $key => $value4) {
			$data_arr[] = (array)$value4;
		}	
		$results->leavesArr = $leavesArr;
		$results->datesArr = $datesArr;	
		$results->totalItems = count($EmployeeJob_rs);
		$results->items = $data_arr;
		$results->holidayArr = $holidayArr;
		$results->summaryDatesArr = $summaryDatesArr;
		$results->partialSummaryDatesArr = $partialSummaryDatesArr;
		return $results;
	}
	/*
	*generalReportSummary
	*/
	public function generalReportSummary(){
		set_time_limit(0);
		$modules = Generic::modules();
		Input::flash();
		$page = Input::get('page', 1);
   		$data = $this->getGenRepSumryByPage($page, 100);
   		$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where status = 'A' and concat(',',frontend,',') like '%,timesheet,%' order by name"));
		$salesp_arr = array(''=>'Select Employee');
		foreach ($salesPerson as $key => $value)
			$salesp_arr[$value->id] = $value->name;
		$emp_types = DB::table('gpg_employee_type')->lists('type','type_id');
  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
  		$params = array('left_menu' => $modules,'query_data'=>$query_data,'salesp_arr'=>$salesp_arr,'emp_type'=>$emp_types,'leavesArr'=>$data->leavesArr,'datesArr'=>$data->datesArr,'holidayArr'=>$data->holidayArr,'summaryDatesArr'=>$data->summaryDatesArr,'partialSummaryDatesArr'=>$data->partialSummaryDatesArr,'tDays'=>$data->tDays);
		return View::make('reports.gen_reportsummary', $params);
	}
	public function getGenRepSumryByPage($page = 1, $limit = null)
	{
		set_time_limit(0);
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
		$results->tDays = 0;
		$employee = Input::get("employee");
		$employeeType = Input::get("employeeType");
	    $employee_in = Input::get("employee_in");
		$start = Input::get("SDate");
		$end = Input::get("EDate");
		$jNum = Input::get("jNum");
		$tech_filter = Input::get("tech_flag");
		$dbDateStart = $start;
		$dbDateEnd = $end;
		$partialArray = array();
		$reportView = Input::get('reportView');
		$partialArray['AllOtherJobs'] = "AllOtherJobs";
		$partialArray['Workshop'] = "Workshop";
		$partialArray['LeaveHours'] = "LeaveHours";
		$employeeTypes = @implode(",",$employeeType);
		$datesArr = array(); 
		$partialSummaryDatesArr = array(); 
		$showFlag = ""; 
		$holidayArr = array(); 
		$leavesArr = array();  
		$summaryDatesArr  = array();   
		$jobHoursArray  = array();   
		$jobValue ="";	
		$totalJobHours =0;
		if (empty($employeeType[0])) $employeeTypes = substr($employeeTypes,1,strlen($employeeTypes));
		$tDays =1;
		$tDay_s = DB::select(DB::raw("select DATEDIFF('".$dbDateEnd."','".$dbDateStart."') as t_diff"));
		if(!empty($tDay_s) && isset($tDay_s[0]->t_diff)) {
			$tDays = $tDays + $tDay_s[0]->t_diff;
			$results->tDays = $tDays;
		}
		$getLeavesQ = DB::select(DB::raw("select hours, gpg_employee_id, leave_date from gpg_leaveapp where status = 'A' and leave_date >= '$dbDateStart' and leave_date<='$dbDateEnd'".($employee!=""?" and gpg_employee_id in($employee)":"")));
		foreach ($getLeavesQ as $key => $value1){
	   		$getLeavesRow = (array)$value1;
	 		$leavesArr[$getLeavesRow['leave_date']][$getLeavesRow['gpg_employee_id']] = $getLeavesRow['hours'];
		}
		$getEmployeeQ = DB::select(DB::raw("select a.date as tsDate , b.job_num as jobNum, b.id as jobId, b.time_in, b.time_out, a.GPG_employee_Id as empId, b.GPG_timetype_id as time_type, (select GPG_job_type_id from gpg_job where id = b.GPG_job_id) as job_type from gpg_timesheet a , gpg_timesheet_detail b where a.id = b.gpg_timesheet_id and a.date >= '$dbDateStart' and a.date<='$dbDateEnd' ".($employee!=""?" and a.gpg_employee_id in($employee) ":"").(count($employeeType)>0 && !empty($employeeType[0])?" and a.gpg_employee_id in (select id from gpg_employee where GPG_employee_type_id in($employeeTypes) ) ":"")." order by a.date, a.GPG_employee_Id, b.GPG_job_id"));
		$techMultipleJobArray = array();
	 	foreach ($getEmployeeQ as $key => $value2){
	 		$getEmployeeRow = (array)$value2;
	        $getHolidayQ = DB::select(DB::raw("select '8' as hours, date from gpg_holiday where date >= '$dbDateStart' and date<='$dbDateEnd'"));
   			foreach ($getHolidayQ as $key => $value3){
				$getHolidayRow = (array)$value3;
				$holidayArr[$getHolidayRow['date']][$getEmployeeRow['empId']] = $getHolidayRow['hours'];
			}
	        $timearray = $this->get_time_difference( $getEmployeeRow['time_in'], $getEmployeeRow['time_out']); 
			@$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['hours'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			@$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']][$getEmployeeRow['time_type']]['hours'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['time_type'] = $getEmployeeRow['time_type'];
			$datesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']][$getEmployeeRow['jobNum']]['job_type'] = $getEmployeeRow['job_type'];
			if(@$tech_filter == 1){
				$techMultipleJobArray[$getEmployeeRow['empId']][] = $getEmployeeRow['jobNum'];
	 		}
			if (preg_match("/workshop/i",$getEmployeeRow['jobNum']) || preg_match("/sick/i",$getEmployeeRow['jobNum']) || preg_match("/vacation/i",$getEmployeeRow['jobNum'])) {
				if (preg_match("/workshop/",$getEmployeeRow['jobNum'])) 
					@$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['Workshop'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
				else 
					@$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['LeaveHours'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			}
			else { 
			 	@$partialSummaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']]['AllOtherJobs'] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			}				
			@$summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']] += $this->convertTime($timearray['hours'].":".$timearray['minutes']);
			if ($getEmployeeRow['time_type']==8) $summaryDatesArr[$getEmployeeRow['tsDate']][$getEmployeeRow['empId']] = '<b>Off</b>';
		}//end foreach 
		if(isset($employee_in) && count($employee_in) > 0){
            $emp_in = '';
            foreach($employee_in as $in){
                $emp_in[] = $in;
            }
           	$emp_in_query = ' AND emp.exclude_oh IN ('.implode(',', $emp_in).')';
        }else {
            $emp_in_query = '';
        }
		$sumQueryPart = ($reportView!=""?" group by emp.id ":"");
		$EmployeeJob_query = DB::select(DB::raw("select IF(LENGTH(emp.name) - LENGTH(REPLACE(emp.name, ' ', ''))=1,
		SUBSTRING_INDEX(emp.name, ' ',-1),
		SUBSTRING_INDEX(emp.name, ' ',((LENGTH(emp.name) - LENGTH(REPLACE(emp.name, ' ', '')))*(-1)))) AS employeeSortName, emp.name as empName ,a.labor_rate,(SELECT CONCAT(invoice_number,'#~#',IFNULL(sum(invoice_amount),0),'#~#',invoice_date,'#~#',SUM(tax_amount),'#~#',COUNT(id)) FROM gpg_job_invoice_info WHERE gpg_job_invoice_info.gpg_job_id = d.id GROUP BY gpg_job_invoice_info.gpg_job_id) AS invoice_data,b.date as display_date,IFNULL(sum(a.total_wage),0) as labor_cost, emp.status as empStatus, emp.id as empId,a.GPG_timetype_id as time_type,  a.job_num as JobNum, a.pw_flag as prevail , a.id as JobId,a.GPG_timetype_id as timetypeId, (select IFNULL(SUM(inv.invoice_amount),0) from gpg_job_invoice_info inv where inv.job_num = a.job_num) as invoice_amount,(select name from gpg_customer where id = d.gpg_customer_id) as customer_name, d.location from gpg_employee emp LEFT JOIN gpg_timesheet b on (emp.id=b.GPG_employee_Id and b.date >= '$dbDateStart' and b.date<='$dbDateEnd') LEFT JOIN gpg_timesheet_detail a on (a.GPG_timesheet_Id = b.id ) LEFT JOIN gpg_job_rates c on (a.gpg_task_type = c.gpg_task_type and a.job_num = c.job_number and c.status = 'A') 
		INNER JOIN gpg_job d on (a.job_num = d.job_num)
		WHERE 1 AND emp.status = 'A' $emp_in_query ".($employee!=""?" AND b.gpg_employee_id in($employee)":"").(count($employeeType)>0 && !empty($employeeType[0])?" and emp.GPG_employee_type_id in ($employeeTypes) ":"")." ".($jNum!=""?" AND a.job_num = '$jNum'":"")." AND concat(',',emp.frontend,',') like '%timesheet%' $sumQueryPart ".(empty($sumQueryPart)?"group by b.date,time_type,jobNum,empId":"")." ORDER BY b.date ASC, b.GPG_employee_Id desc, a.job_num"));
		$query_data = array();
		foreach ($EmployeeJob_query as $key => $value4) {
			$query_data[] = (array)$value4;
		}
		$results->leavesArr = $leavesArr;
		$results->datesArr = $datesArr;	
		$results->totalItems = count($query_data);
		$results->items = $query_data;
		$results->holidayArr = $holidayArr;
		$results->summaryDatesArr = $summaryDatesArr;
		$results->partialSummaryDatesArr = $partialSummaryDatesArr;
		return $results;
	}

	/*
	* excelGenRepSummaryExport
	*/
	public function excelGenRepSummaryExport(){
		set_time_limit(0);
		Excel::create('GeneralReportSummaryExport', function($excel) {
		    $excel->sheet('GeneralReportSummaryExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getGenRepSumryByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'leavesArr'=>$data->leavesArr,'datesArr'=>$data->datesArr,'holidayArr'=>$data->holidayArr,'summaryDatesArr'=>$data->summaryDatesArr,'partialSummaryDatesArr'=>$data->partialSummaryDatesArr,'tDays'=>$data->tDays);
		    $sheet->loadView('reports.excelGenRepSummaryExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelGenRepExport
	*/
	public function excelGenRepExport(){
		set_time_limit(0);
		Excel::create('GeneralReportExport', function($excel) {
		    $excel->sheet('GeneralReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getGenRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'tDays'=>$data->tDays,'datesArr'=>$data->datesArr,'summaryDatesArr'=>$data->summaryDatesArr,'partialSummaryDatesArr'=>$data->partialSummaryDatesArr,'holidayArr'=>$data->holidayArr,'leavesArr'=>$data->leavesArr);
		    $sheet->loadView('reports.excelGenRepExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelCompModelingExport
	*/
	public function excelCompModelingExport(){
		set_time_limit(0);
		Excel::create('CompModelReportExport', function($excel) {
		    $excel->sheet('CompModelReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getModelingRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where status = 'A' and concat(',',frontend,',') like '%,timesheet,%' order by name"));
		$salesp_arr = array(''=>'Select Employee');
		foreach ($salesPerson as $key => $value)
			$salesp_arr[$value->id] = $value->name;
  			$params = array('query_data'=>$query_data,'salesp_arr'=>$salesp_arr,'tDays'=>$data->tDays,'datesArr'=>$data->datesArr,'summaryDatesArr'=>$data->summaryDatesArr,'partialSummaryDatesArr'=>$data->partialSummaryDatesArr);
		    $sheet->loadView('reports.excelCompModelingExport',$params);
		    });
		})->export('xls');
	}

	/*
	*excelEmpRepExport
	*/
	public function excelEmpRepExport(){
		set_time_limit(0);
		Excel::create('EmployeeReportExport', function($excel) {
		    $excel->sheet('EmployeeReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getEmployeeRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'tDays'=>$data->tDays,'getEmpInfo'=>$data->getEmpInfo,'num_records_emp'=>$data->num_records_emp,'datesArr'=>$data->datesArr);
		        $sheet->loadView('reports.excelEmpRepExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelPayRollExport
	*/
	public function excelPayRollExport(){
		set_time_limit(0);
		Excel::create('PayrollReportExport', function($excel) {
		    $excel->sheet('PayrollReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getPayrollRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'tDays'=>$data->tDays,'datesArr'=>$data->datesArr);
		        $sheet->loadView('reports.excelPayRollExport',$params);
		    });
		})->export('xls');
	}
	/*
	* serviceJobMultipleExport
	*/
	public function serviceJobMultipleExport(){
		set_time_limit(0);
		Excel::create('ServiceJobsReportExport', function($excel) {
		    $excel->sheet('ServiceJobsReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getGenSerJobRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'queryPartPM'=>$data->queryPartPM,'queryPartQT'=>$data->queryPartQT,'queryPartTC'=>$data->queryPartTC,'queryPart2'=>$data->queryPart2,'queryPartPM1'=>$data->queryPartPM1,'queryPartQT1'=>$data->queryPartQT1,'queryPartTC1'=>$data->queryPartTC1,'queryPartTimesheet'=>$data->queryPartTimesheet,'queryPartInvoice'=>$data->queryPartInvoice,'queryPartMaterialCost'=>$data->queryPartMaterialCost,'totalCustomer'=>$data->totalCustomer,'completed_workorders'=>$data->completed_workorders,'completed_workorders_tech'=>$data->completed_workorders_tech,'completed_workorders_salesperson'=>$data->completed_workorders_salesperson,'completed_workorders_profit'=>$data->completed_workorders_profit,'comp_work_sale_productivity'=>$data->comp_work_sale_productivity);
		        $sheet->loadView('reports.serviceJobMultipleExport',$params);
		    });
		})->export('xls');
	}
	/*
	* excelMissingHourReportExport
	*/
	public function excelMissingHourReportExport(){
		set_time_limit(0);
		Excel::create('MissingHourReportExport', function($excel) {
		    $excel->sheet('MissingHourReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getMissingHourRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'tDays'=>$data->tDays,'summaryDatesArr'=>$data->DatesArr,'leavesArr'=>$data->leavesArr);
		        $sheet->loadView('reports.excelMissingHourReportExport',$params);
		    });
		})->export('xls');
	}
	
	/*
	* excelExportJobTimeRep
	*/
	public function excelExportJobTimeRep(){
		set_time_limit(0);
		Excel::create('JobTimeReportExport', function($excel) {
		    $excel->sheet('JobTimeReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $jobNum = Input::get('jobNum');
			$SDate = Input::get('JobTimeSDate');
			$EDate = Input::get('JobTimeEDate');
			$queryPart = '';
			if ($SDate!="" and $EDate!="") $queryPart .= " AND date >= '".date('Y-m-d',strtotime($SDate))."' AND date <= '".date('Y-m-d',strtotime($EDate))."' ";
 			elseif ($SDate!="") $queryPart .= " AND date = '".date('Y-m-d',strtotime($SDate))."'";
			$tWage = 0;
			$laborDataQuery = DB::table(DB::raw("select *, (select name from gpg_employee where id = a.GPG_employee_id) as emp_name , b.id as d_id, b.GPG_timetype_id as timetypId, b.labor_rate as LaborRate, b.pw_reg_rate as pw_reg, b.pw_ot_rate as pw_ot, b.pw_dt_rate as pw_dt from gpg_timesheet a , gpg_timesheet_detail b WHERE a.id = b.GPG_timesheet_id and b.job_num = '$jobNum' $queryPart order by date desc"));
			$data_arr = array();
			foreach ($laborDataQuery as $key => $value) {
				$data_arr[] = (array)$value;
			}
			$params = array('jobNum'=>$jobNum,'laborDataRows'=>$data_arr);
		        $sheet->loadView('reports.excelExportJobTimeRep',$params);
		    });
		})->export('xls');
	}

	/*
	* excelCostDuplicReportExport
	*/
	public function excelCostDuplicReportExport(){
		set_time_limit(0);
		Excel::create('CostDuplicReportExport', function($excel) {
		    $excel->sheet('CostDuplicReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getCostDupRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		        $sheet->loadView('reports.excelCostDuplicReportExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelFinRepSumryExport
	*/
	public function excelFinRepSumryExport(){
		set_time_limit(0);
		Excel::create('FinancialSumReportExport', function($excel) {
		    $excel->sheet('FinSumryReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getfinRepSumryByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$salesPerson = DB::select( DB::raw("select id, name from gpg_employee where  concat(',',frontend,',') like '%,sales,%' order by name"));
			$salesp_arr = array(''=>'ALL');
			foreach ($salesPerson as $key => $value)
					$salesp_arr[$value->id] = $value->name;
	  		$params = array('query_data'=>$query_data,'salesp_arr'=>$salesp_arr,'valuesMonth'=>$data->items);
			    $sheet->loadView('reports.excelFinRepSumryExport',$params);
		    });
		})->export('xls');
	}

	/*
	* exceInvoiceAmtRepExport
	*/
	public function exceInvoiceAmtRepExport(){
		set_time_limit(0);
		Excel::create('InvAmtReportExport', function($excel) {
		    $excel->sheet('InvAmtReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getInvAmtRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'service_jobs_row'=>$data->arr1,'elec_jobs_row'=>$data->arr2,'grassivy_jobs_row'=>$data->arr3,'special_project_jobs_row'=>$data->arr4);
		        $sheet->loadView('reports.exceInvoiceAmtRepExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelCSJReportExport
	*/
	public function excelCSJReportExport(){
		set_time_limit(0);
		Excel::create('SJReportExport', function($excel) {
		    $excel->sheet('SJReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getCSJRByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'invoiceTotals'=>$data->totals);
		        $sheet->loadView('reports.excelCSJReportExport',$params);
		    });
		})->export('xls');
	}

	/*
	* excelCCRExport
	*/
	public function excelCCRExport(){
		set_time_limit(0);
		Excel::create('CCReportExport', function($excel) {
		    $excel->sheet('CCReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getCCRByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'yearFirstResult'=>$data->items,'yearSecondArray'=>$data->secondItems);
		        $sheet->loadView('reports.excelCCRExport',$params);
		    });
		})->export('xls');
	}

	/*
	*excelWIPReportExport
	*/
	public function excelWIPReportExport(){
		set_time_limit(0);
		Excel::create('WIPReportExport', function($excel) {
		    $excel->sheet('WIPReportExport', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getWIPgRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'totalsArr'=>$data->totalsArr);
		        $sheet->loadView('reports.excelWIPReportExport',$params);
		    });
		})->export('xls');
	}

    /*
	* Export Excel File
	*/
	public function excelDFRSExport(){
		set_time_limit(0);
		Excel::create('FinancialReportExport', function($excel) {
		    $excel->sheet('QuoteExportFile', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getFinRSByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		        $sheet->loadView('reports.excelDFRSExport',$params);
		    });
		})->export('xls');
 	}
 	/*
	* excelTCJReportExport
 	*/
 	public function excelTCJReportExport(){
 		set_time_limit(0);
		Excel::create('TroubleCallReportExport', function($excel) {
		    $excel->sheet('QuoteExportFile', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getTCJReportByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data);
		        $sheet->loadView('reports.excelTCJReportExport',$params);
		    });
		})->export('xls');
 	}
 	/*
	* servJobFSWRepExport
 	*/
 	public function servJobFSWRepExport(){
 		set_time_limit(0);
		Excel::create('ServiceJobExport', function($excel) {
		    $excel->sheet('QuoteExportFile', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getFSWReportByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'FSWStatusArray'=>$this->FSWStatusArray);
		        $sheet->loadView('reports.servJobFSWRepExport',$params);
		    });
		})->export('xls');
 	}

 	/*
	* excelRegardingReportExport
 	*/
 	public function excelRegardingReportExport(){
 		set_time_limit(0);
		Excel::create('RegardingReportExport', function($excel) {
		    $excel->sheet('RegardingExportFile', function($sheet) {
				    $sheet->setStyle(array(
		    'td' => array(
		        'background' => 'blue'
		    )
		));	
		    $page = Input::get('page', 1);
	   		$data = $this->getJobRegardingRepByPage($page, 100);
	  		$query_data = Paginator::make($data->items, $data->totalItems, 100);
			$params = array('query_data'=>$query_data,'regardingArray'=>$data->MainData);
		
		        $sheet->loadView('reports.excelRegardingReportExport',$params);
		    });
		})->export('xls');
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

	/*
	* deleteCostDuplic
	*/
	public function deleteCostDuplic($id){
		DB::table('gpg_job_cost')->where('id','=',$id)->delete();
		return Redirect::to('reports/cost_duplication_report')->withSuccess('Item has been deleted successfully');
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
